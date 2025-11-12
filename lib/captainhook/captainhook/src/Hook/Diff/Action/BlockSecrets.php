<?php

/**
 * This file is part of CaptainHook.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Hook\Diff\Action;

use CaptainHook\App\Config;
use CaptainHook\App\Console\IO;
use CaptainHook\App\Console\IOUtil;
use CaptainHook\App\Exception\ActionFailed;
use CaptainHook\App\Git\Range\Detector\PrePush;
use CaptainHook\App\Hook\Action;
use CaptainHook\App\Hook\Constrained;
use CaptainHook\App\Hook\Restriction;
use CaptainHook\App\Hook\Util;
use CaptainHook\App\Hooks;
use CaptainHook\Secrets\Detector;
use CaptainHook\Secrets\Entropy\Shannon;
use CaptainHook\Secrets\Regex\Supplier\Ini;
use CaptainHook\Secrets\Regex\Supplier\Json;
use CaptainHook\Secrets\Regex\Supplier\PHP;
use CaptainHook\Secrets\Regex\Supplier\Yaml;
use CaptainHook\Secrets\Regexer;
use Exception;
use SebastianFeldmann\Git\Diff\File;
use SebastianFeldmann\Git\Repository;

class BlockSecrets implements Action, Constrained
{
    /**
     * @var \CaptainHook\App\Console\IO
     */
    private IO $io;

    /**
     * @var \CaptainHook\Secrets\Detector
     */
    private Detector $detector;

    /**
     * List of allowed patterns
     *
     * @var array<string>
     */
    private array $allowed;

    /**
     * Additional information for a file
     *
     * @var array<string, string>
     */
    private array $info = [];

    /**
     * Max allowed entropy for words
     *
     * @var float
     */
    private float $entropyThreshold;

    /**
     * Map filetype regex supplier
     *
     * @var array<string>
     */
    private array $fileTypeSupplier = [
        'json' => Json::class,
        'php'  => PHP::class,
        'yml'  => Yaml::class,
        'ini'  => Ini::class,
    ];

    /**
     * Make sure this action is only used pro pre-commit hooks
     *
     * @return \CaptainHook\App\Hook\Restriction
     */
    public static function getRestriction(): Restriction
    {
        return new Restriction('pre-commit', 'pre-push');
    }

    /**
     * Execute the action
     *
     * @param \CaptainHook\App\Config           $config
     * @param \CaptainHook\App\Console\IO       $io
     * @param \SebastianFeldmann\Git\Repository $repository
     * @param \CaptainHook\App\Config\Action    $action
     * @return void
     * @throws \CaptainHook\App\Exception\ActionFailed
     */
    public function execute(Config $config, IO $io, Repository $repository, Config\Action $action): void
    {
        $this->io = $io;
        $this->setUp($action->getOptions());

        $filesFailed  = 0;
        $filesToCheck = $this->getChanges($repository);

        foreach ($filesToCheck as $file) {
            if ($this->isSecretInFile($file->getName(), $this->getLines($file))) {
                $filesFailed++;
                $io->write('  ' . IOUtil::PREFIX_FAIL . ' ' . $file->getName() . $this->errorDetails($file->getName()));
                continue;
            }
            $io->write('  ' . IOUtil::PREFIX_OK . ' ' . $file->getName(), true, IO::VERBOSE);
        }
        if ($filesFailed > 0) {
            $s = $filesFailed > 1 ? 's' : '';
            throw new ActionFailed('Found secrets in ' . $filesFailed . ' file' . $s);
        }
    }

    /**
     * Checks if some added lines contain secrets that are not allowed
     *
     * @param string        $file
     * @param array<string> $lines
     * @return bool
     */
    private function isSecretInFile(string $file, array $lines): bool
    {
        $result = $this->detector->detectIn(implode(PHP_EOL, $lines));
        if ($result->wasSecretDetected()) {
            foreach ($result->matches() as $match) {
                if (!$this->isAllowed($match)) {
                    $this->info[$file] = $match;
                    return true;
                }
            }
        }
        if ($this->containsSuspiciousText($file, $lines)) {
            return true;
        }
        return false;
    }

    /**
     * Tries to find passwords by entropy
     *
     * @param string        $file
     * @param array<string> $lines
     * @return bool
     */
    private function containsSuspiciousText(string $file, array $lines): bool
    {
        if ($this->entropyThreshold < 0.1) {
            return false;
        }
        $ext = $this->getFileExtension($file);
        // if we don't have a supplier for this filetype just exit
        if (!isset($this->fileTypeSupplier[$ext])) {
            return $this->lookForSecretsBruteForce($file, $lines);
        }
        return $this->lookForSecretsWithSupplier($this->fileTypeSupplier[$ext], $lines, $file);
    }

    /**
     * @param \SebastianFeldmann\Git\Diff\File $file
     * @return array<string>
     */
    private function getLines(File $file): array
    {
        $lines = [];
        foreach ($file->getChanges() as $change) {
            array_push($lines, ...$change->getAddedContent());
        }
        return $lines;
    }

    /**
     * Checks if a found blocked pattern should be allowed anyway
     *
     * @param  string $blocked
     * @return bool
     */
    private function isAllowed(string $blocked): bool
    {
        foreach ($this->allowed as $regex) {
            $matchCount = preg_match($regex, $blocked, $matches);
            if ($matchCount) {
                return true;
            }
        }
        return false;
    }

    /**
     * Read all options and set up the action properly
     *
     * @param \CaptainHook\App\Config\Options $options
     * @throws \CaptainHook\App\Exception\ActionFailed
     */
    private function setUp(Config\Options $options): void
    {
        $this->detector = Detector::create();

        $this->setUpSuppliers($options);
        $this->setUpBlocked($options);
        $this->entropyThreshold = (float) $options->get('entropyThreshold', 0.0);
        $this->allowed          = $options->get('allowed', []);
    }

    /**
     * Set up the blocked regex
     *
     * @param \CaptainHook\App\Config\Options $options
     * @throws \CaptainHook\App\Exception\ActionFailed
     */
    private function setUpSuppliers(Config\Options $options): void
    {
        try {
            $this->detector->useSupplierConfig($options->get('suppliers', []));
        } catch (Exception $e) {
            throw new ActionFailed($e->getMessage(), 0, $e);
        }
    }

    /**
     * @param \CaptainHook\App\Config\Options $options
     * @return void
     */
    private function setUpBlocked(Config\Options $options): void
    {
        $this->detector->useRegex(...$options->get('blocked', []));
    }

    /**
     * Return an error message appendix
     *
     * @param  string $file
     * @return string
     */
    protected function errorDetails(string $file): string
    {
        return ' found <comment>' . $this->info[$file] . '</comment>';
    }

    /**
     * @param \SebastianFeldmann\Git\Repository $repository
     * @return array<\SebastianFeldmann\Git\Diff\File>
     */
    private function getChanges(Repository $repository): array
    {
        if (Util::isRunningHook($this->io, Hooks::PRE_PUSH)) {
            $detector = new PrePush();
            $ranges   = $detector->getRanges($this->io);
            $newHash  = 'HEAD';
            $oldHash  = 'HEAD@{1}';
            if (!empty($ranges) && !$ranges[0]->to()->isZeroRev()) {
                $oldHash = $ranges[0]->from()->id();
                $newHash = $ranges[0]->to()->id();
            }
            return $repository->getDiffOperator()->compare($oldHash, $newHash);
        }
        return $repository->getDiffOperator()->compareIndexTo('HEAD');
    }

    /**
     * Return the file suffix for a given file name
     *
     * @param string $file
     * @return string
     */
    private function getFileExtension(string $file): string
    {
        $fileInfo = pathinfo($file);
        return $fileInfo['extension'] ?? '';
    }

    /**
     * Should match be blocked because of entropy value
     *
     * @param string $file
     * @param string $match
     * @return bool
     */
    private function isEntropyTooHigh(string $file, string $match): bool
    {
        $entropy = Shannon::entropy($match);
        $this->io->write('Entropy of ' . $match . ' is ' . $entropy, true, IO::DEBUG);
        if ($entropy > $this->entropyThreshold) {
            if (!$this->isAllowed($match)) {
                $this->info[$file] = $match;
                return true;
            }
        }
        return false;
    }

    /**
     * Uses supplier and regexer to find possible risky parts of a string
     *
     * @param string        $supplierClass
     * @param array<string> $lines
     * @param string $file
     * @return bool
     */
    private function lookForSecretsWithSupplier(string $supplierClass, array $lines, string $file): bool
    {
        /** @var \CaptainHook\Secrets\Regex\Grouped $supplier */
        $supplier = new $supplierClass();
        $regexer  = Regexer::create()->useGroupedSupplier($supplier);
        foreach ($lines as $line) {
            $result = $regexer->detectIn($line);
            if (!$result->wasSecretDetected()) {
                continue;
            }
            if ($this->isEntropyTooHigh($file, $result->matches()[0])) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check every word in a file if the entropy is too high
     *
     * @param string        $file
     * @param array<string> $lines
     * @return bool
     */
    private function lookForSecretsBruteForce(string $file, array $lines): bool
    {
        $matches = [];
        if (preg_match_all('#\b\S{8,}\b#', implode(' ', $lines), $matches)) {
            foreach ($matches[0] as $word) {
                if ($this->isEntropyTooHigh($file, $word)) {
                    return true;
                }
            }
        }
        return false;
    }
}

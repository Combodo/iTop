<?php

/**
 * This file is part of CaptainHook
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace CaptainHook\App\Hook\PHP\Action;

use CaptainHook\App\Config;
use CaptainHook\App\Console\IO;
use CaptainHook\App\Exception\ActionFailed;
use CaptainHook\App\Hook\Action;
use CaptainHook\App\Hook\PHP\CoverageResolver;
use SebastianFeldmann\Git\Repository;

/**
 * Class TestCoverage
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 1.2.0
 */
class TestCoverage implements Action
{
    /**
     * Clover XML file
     *
     * @var string
     */
    private string $cloverXmlFile;

    /**
     * Path to PHPUnit
     *
     * @var string
     */
    private string $phpUnit;

    /**
     * Minimum coverage in percent
     *
     * @var int
     */
    private int $minCoverage;

    /**
     * Executes the action.
     *
     * @param  \CaptainHook\App\Config           $config
     * @param  \CaptainHook\App\Console\IO       $io
     * @param  \SebastianFeldmann\Git\Repository $repository
     * @param  \CaptainHook\App\Config\Action    $action
     * @return void
     * @throws \Exception
     */
    public function execute(Config $config, IO $io, Repository $repository, Config\Action $action): void
    {
        $io->write('checking coverage:', true, IO::VERBOSE);
        $this->handleOptions($action->getOptions());

        $coverageResolver = $this->getCoverageResolver();
        $coverage         = $coverageResolver->getCoverage();

        $this->verifyCoverage($coverage);
        $io->write('<info>Test coverage: ' . $coverage . '%</info>', true, IO::VERBOSE);
    }

    /**
     * Setup local properties with given options.
     *
     * @param  \CaptainHook\App\Config\Options $options
     * @return void
     * @throws \RuntimeException
     */
    protected function handleOptions(Config\Options $options): void
    {
        $this->cloverXmlFile = $options->get('cloverXml', '');
        $this->phpUnit       = $options->get('phpUnit', 'phpunit');
        $this->minCoverage   = (int) $options->get('minCoverage', 80);
    }

    /**
     * Return the adequate coverage resolver.
     *
     * @return \CaptainHook\App\Hook\PHP\CoverageResolver
     */
    protected function getCoverageResolver(): CoverageResolver
    {
        // if clover xml is configured use it to read coverage data
        if (!empty($this->cloverXmlFile)) {
            return new CoverageResolver\CloverXML($this->cloverXmlFile);
        }

        // no clover xml so use phpunit to get current test coverage
        return new CoverageResolver\PHPUnit($this->phpUnit);
    }

    /**
     * Check if current coverage is high enough.
     *
     * @param  float $coverage
     * @return void
     * @throws \CaptainHook\App\Exception\ActionFailed
     */
    protected function verifyCoverage(float $coverage): void
    {
        if ($coverage < $this->minCoverage) {
            throw new ActionFailed(
                'Test coverage to low!' . PHP_EOL .
                'Current coverage is at ' . $coverage . '% but should be at least ' . $this->minCoverage . '%'
            );
        }
    }
}

<?php

/**
 * This file is part of CaptainHook
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Hook\Composer\Action;

use CaptainHook\App\Config;
use CaptainHook\App\Console\IO;
use CaptainHook\App\Hook\Action;
use Exception;
use SebastianFeldmann\Git\Repository;

/**
 * Class CheckLockFile
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 1.0.1
 */
class CheckLockFile implements Action
{
    /**
     * Composer configuration keys that are relevant for the 'content-hash' creation
     *
     * @var array<string>
     */
    private $relevantKeys = [
        'name',
        'version',
        'require',
        'require-dev',
        'conflict',
        'replace',
        'provide',
        'minimum-stability',
        'prefer-stable',
        'repositories',
        'extra',
    ];

    /**
     * Executes the action
     *
     * @param  \CaptainHook\App\Config           $config
     * @param  \CaptainHook\App\Console\IO       $io
     * @param  \SebastianFeldmann\Git\Repository $repository
     * @param  \CaptainHook\App\Config\Action    $action
     * @throws \Exception
     */
    public function execute(Config $config, IO $io, Repository $repository, Config\Action $action): void
    {
        $path           = $action->getOptions()->get('path', getcwd());
        $name           = $action->getOptions()->get('name', 'composer');
        $pathname       = $path . DIRECTORY_SEPARATOR . $name;
        $lockFileHash   = $this->getLockFileHash($pathname . '.lock');
        $configFileHash = $this->getConfigFileHash($pathname . '.json');

        if ($lockFileHash !== $configFileHash) {
            throw new Exception('Your composer.lock file is out of date');
        }
    }

    /**
     * Read the composer.lock file and extract the composer.json hash
     *
     * @param  string $composerLock
     * @return string
     * @throws \Exception
     */
    private function getLockFileHash(string $composerLock): string
    {
        $lockFile = json_decode($this->loadFile($composerLock));
        $hashKey  = 'content-hash';

        if (!isset($lockFile->$hashKey)) {
            throw new Exception('could not find content hash, please update composer to the latest version');
        }

        return $lockFile->$hashKey;
    }

    /**
     * Read the composer.json file and create a md5 hash on its relevant content
     *
     * This more or less is composer internal code to generate the content-hash so this might not be the best idea
     * and will be removed in the future.
     *
     * @param  string $composerJson
     * @return string
     * @throws \Exception
     */
    private function getConfigFileHash(string $composerJson): string
    {
        $content         = json_decode($this->loadFile($composerJson), true);
        $relevantContent = [];

        foreach (array_intersect($this->relevantKeys, array_keys($content)) as $key) {
            $relevantContent[$key] = $content[$key];
        }
        if (isset($content['config']['platform'])) {
            $relevantContent['config']['platform'] = $content['config']['platform'];
        }
        ksort($relevantContent);

        return md5((string)json_encode($relevantContent));
    }

    /**
     * Load a composer file
     *
     * @param  string $file
     * @return string
     * @throws \Exception
     */
    private function loadFile(string $file): string
    {
        if (!file_exists($file)) {
            throw new Exception($file . ' not found');
        }
        return (string)file_get_contents($file);
    }
}

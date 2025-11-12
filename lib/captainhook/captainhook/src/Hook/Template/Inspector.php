<?php

/**
 * This file is part of CaptainHook.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Hook\Template;

use CaptainHook\App\CH;
use CaptainHook\App\Console\IO;
use Exception;
use SebastianFeldmann\Git\Repository;

/**
 * Inspector
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.22.0
 */
class Inspector
{
    /**
     * @var string
     */
    private string $hook;

    /**
     * @var \CaptainHook\App\Console\IO
     */
    private IO $io;

    /**
     * @var \SebastianFeldmann\Git\Repository
     */
    private Repository $repository;

    /**
     * @param string                            $hook
     * @param \CaptainHook\App\Console\IO       $io
     * @param \SebastianFeldmann\Git\Repository $repo
     */
    public function __construct(string $hook, IO $io, Repository $repo)
    {
        $this->hook       = $hook;
        $this->io         = $io;
        $this->repository = $repo;
    }

    /**
     * Check if the hooks script needs an update
     *
     * @return void
     * @throws \Exception
     */
    public function inspect(): void
    {
        $path = $this->repository->getHooksDir() . '/' . $this->hook;
        // hook script not installed or at different location
        if (!file_exists($path)) {
            return;
        }

        $hookScript       = file_get_contents($path);
        $installerVersion = $this->detectInstallerVersion((string) $hookScript);

        // could not find any installer version
        // this is not optimal but if people decide to customise there is only so much I can do
        if (empty($installerVersion)) {
            return;
        }

        if (version_compare($installerVersion, CH::MIN_REQ_INSTALLER) < 0) {
            $this->io->write([
                '<fg=red>Warning: Hook script is out of date</>',
                'The git hook script needs to be updated.',
                'Required version is <info>' . CH::MIN_REQ_INSTALLER . '</info>'
                  . ' found <fg=red>' . $installerVersion . '</>.',
                'Please re-install your hook by running:',
                '  <comment>captainhook install ' . $this->hook . '</comment>',
                '',
                '<fg=red>captainhook failed executing your hooks</>',
            ]);
            throw new Exception('hook code out of date');
        }
    }

    /**
     * Try to find the version that installed the hook script
     *
     * @param  string $hookScript
     * @return string
     */
    private function detectInstallerVersion(string $hookScript): string
    {
        $version = '';
        $matches = [];

        if (preg_match('#installed by Captainhook ([0-9]+\.[0-9]+\.[0-9]+)#i', $hookScript, $matches)) {
            $version = $matches[1];
        }
        return $version;
    }
}

<?php

/**
 * This file is part of CaptainHook.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Runner\Bootstrap;

use CaptainHook\App\Config;
use RuntimeException;

/**
 * Bootstrap Util
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.23.3
 */
class Util
{
    /**
     * Return the bootstrap file to load (can be empty)
     *
     * @param  bool                    $isPhar
     * @param  \CaptainHook\App\Config $config
     * @return string
     */
    public static function validateBootstrapPath(bool $isPhar, Config $config): string
    {
        $bootstrapFile = dirname($config->getPath()) . '/' . $config->getBootstrap();
        if (!file_exists($bootstrapFile)) {
            // since the phar has its own autoloader we don't need to do anything
            // if the bootstrap file is not actively set
            if ($isPhar && empty($config->getBootstrap(''))) {
                return '';
            }
            throw new RuntimeException('bootstrap file not found');
        }
        return $bootstrapFile;
    }

    /**
     * Returns the bootstrap command option (can be empty)
     *
     * @param bool                    $isPhar
     * @param \CaptainHook\App\Config $config
     * @return string
     */
    public static function bootstrapCmdOption(bool $isPhar, Config $config): string
    {
        // nothing to load => no option
        if ($isPhar && empty($config->getBootstrap(''))) {
            return '';
        }
        return ' --bootstrap=' . $config->getBootstrap();
    }
}

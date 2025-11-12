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

namespace CaptainHook\App;

/**
 * Class CH
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 0.9.0
 */
final class CH
{
    /**
     * Current CaptainHook version
     */
    public const VERSION = '5.25.11';

    /**
     * Release date of the current version
     */
    public const RELEASE_DATE = '2025-08-12';

    /**
     * Default configuration file
     */
    public const CONFIG = 'captainhook.json';

    /**
     * Minimal required version for the installer
     */
    public const MIN_REQ_INSTALLER = '5.22.0';
}

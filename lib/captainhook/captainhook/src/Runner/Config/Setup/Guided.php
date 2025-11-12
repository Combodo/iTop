<?php

/**
 * This file is part of CaptainHook
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Runner\Config\Setup;

use CaptainHook\App\Console\IO;
use Exception;

/**
 * Class Guided
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 2.2.0
 */
abstract class Guided
{
    /**
     * @var \CaptainHook\App\Console\IO
     */
    protected $io;

    /**
     * Guided constructor
     *
     * @param \CaptainHook\App\Console\IO $io
     */
    public function __construct(IO $io)
    {
        $this->io = $io;
    }

    /**
     * PHP action option validation
     *
     * @param  string $option
     * @return string
     * @throws \Exception
     */
    public static function isPHPActionOptionValid(string $option): string
    {
        if (count(explode(':', $option)) !== 2) {
            throw new Exception('Invalid option, use "key:value"');
        }
        return $option;
    }
}

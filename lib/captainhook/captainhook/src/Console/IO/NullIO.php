<?php

/**
 * This file is part of CaptainHook
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Console\IO;

/**
 * Class NullIO
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 0.9.0
 */
class NullIO extends Base
{
    /**
     * {@inheritDoc}
     */
    public function isInteractive()
    {
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function isVerbose()
    {
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function isVeryVerbose()
    {
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function isDebug()
    {
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function write($messages, $newline = true, $verbosity = self::NORMAL)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function writeError($messages, $newline = true, $verbosity = self::NORMAL)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function ask($question, $default = null)
    {
        return (string) $default;
    }

    /**
     * {@inheritDoc}
     */
    public function askConfirmation($question, $default = true)
    {
        return $default;
    }

    /**
     * {@inheritDoc}
     */
    public function askAndValidate($question, $validator, $attempts = null, $default = null)
    {
        return $default;
    }
}

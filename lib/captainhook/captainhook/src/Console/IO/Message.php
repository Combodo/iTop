<?php

/**
 * This file is part of CaptainHook.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Console\IO;

/**
 * Class Message
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.19.0
 */
class Message
{
    /**
     * Message, either a string or list of string for multiple lines
     *
     * @var string|array<string>
     */
    private string|array $message;

    /**
     * Should message be ended with a new line character
     *
     * @var bool
     */
    private bool $newLine;

    /**
     * Current application verbosity
     *
     * @var int
     */
    private int $verbosity;

    /**
     * Constructor
     *
     * @param string|array<string> $message
     * @param bool                 $newLine
     * @param int                  $verbosity
     */
    public function __construct(string|array $message, bool $newLine, int $verbosity)
    {
        $this->message   = $message;
        $this->newLine   = $newLine;
        $this->verbosity = $verbosity;
    }

    /**
     * Returns the message to print
     *
     * @return string|array<string>
     */
    public function message(): string|array
    {
        return $this->message;
    }

    /**
     * If true message should end with a new line
     *
     * @return bool
     */
    public function newLine(): bool
    {
        return $this->newLine;
    }

    /**
     * Minimum verbosity this message should be displayed at
     *
     * @return int
     */
    public function verbosity(): int
    {
        return $this->verbosity;
    }
}

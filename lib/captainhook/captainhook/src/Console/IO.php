<?php

/**
 * This file is part of CaptainHook
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Console;

/**
 * Interface IO
 *
 * @package CaptainHook
 * @author  Nils Adermann <naderman@naderman.de>
 * @author  Jordi Boggiano <j.boggiano@seld.be>
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 0.9.0
 */
interface IO
{
    public const QUIET = 1;
    public const NORMAL = 2;
    public const VERBOSE = 4;
    public const VERY_VERBOSE = 8;
    public const DEBUG = 16;

    /**
     * Return the original cli arguments
     *
     * @return array<mixed>
     */
    public function getArguments(): array;

    /**
     * Return the original cli argument or a given default
     *
     * @param  string $name
     * @param  string $default
     * @return string
     */
    public function getArgument(string $name, string $default = ''): string;

    /**
     * Returns the piped in standard input
     *
     * @return string[]
     */
    public function getStandardInput(): array;

    /**
     * Is this input interactive?
     *
     * @return bool
     */
    public function isInteractive();

    /**
     * Is this output verbose?
     *
     * @return bool
     */
    public function isVerbose();

    /**
     * Is the output very verbose?
     *
     * @return bool
     */
    public function isVeryVerbose();

    /**
     * Is the output in debug verbosity?
     *
     * @return bool
     */
    public function isDebug();

    /**
     * Writes a message to the output
     *
     * @param  string|array<string> $messages  The message as an array of lines or a single string
     * @param  bool                 $newline   Whether to add a newline or not
     * @param  int                  $verbosity Verbosity level from the VERBOSITY_* constants
     * @return void
     */
    public function write($messages, $newline = true, $verbosity = self::NORMAL);

    /**
     * Writes a message to the error output
     *
     * @param  string|array<string> $messages  The message as an array of lines or a single string
     * @param  bool                 $newline   Whether to add a newline or not
     * @param  int                  $verbosity Verbosity level from the VERBOSITY_* constants
     * @return void
     */
    public function writeError($messages, $newline = true, $verbosity = self::NORMAL);

    /**
     * Asks a question to the user
     *
     * @param  string $question  The question to ask
     * @param  string $default   The default answer if none is given by the user
     * @throws \RuntimeException If there is no data to read in the input stream
     * @return string            The user answer
     */
    public function ask($question, $default = null);

    /**
     * Asks a confirmation to the user
     *
     * The question will be asked until the user answers by nothing, yes, or no.
     *
     * @param  string $question The question to ask
     * @param  bool   $default  The default answer if the user enters nothing
     * @return bool true if the user has confirmed, false otherwise
     */
    public function askConfirmation($question, $default = true);

    /**
     * Asks for a value and validates the response
     *
     * The validator receives the data to validate. It must return the
     * validated data when the data is valid and throw an exception
     * otherwise.
     *
     * @param  string   $question  The question to ask
     * @param  callable $validator A PHP callback
     * @param  int      $attempts  Max number of times to ask before giving up (default of null means infinite)
     * @param  mixed    $default   The default answer if none is given by the user
     * @throws \Exception When any of the validators return an error
     * @return mixed
     */
    public function askAndValidate($question, $validator, $attempts = null, $default = null);
}

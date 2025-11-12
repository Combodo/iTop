<?php

/**
 * This file is part of SebastianFeldmann\Cli.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianFeldmann\Cli\Command;

use SebastianFeldmann\Cli\Output\Util as OutputUtil;

/**
 * Class Result
 *
 * @package SebastianFeldmann\Cli
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/sebastianfeldmann/cli
 * @since   Class available since Release 0.9.0
 */
class Result
{
    /**
     * Command that got executed.
     *
     * @var string
     */
    private $cmd;

    /**
     * Result code.
     *
     * @var int
     */
    private $code;

    /**
     * List of valid exit codes.
     *
     * @var int[]
     */
    private $validExitCodes;

    /**
     * Output buffer.
     *
     * @var array
     */
    private $buffer;

    /**
     * StdOut.
     *
     * @var string
     */
    private $stdOut;

    /**
     * StdErr.
     *
     * @var string
     */
    private $stdErr;

    /**
     * Path where the output is redirected to.
     *
     * @var string
     */
    private $redirectPath;

    /**
     * Result constructor.
     *
     * @param string $cmd
     * @param int    $code
     * @param string $stdOut
     * @param string $stdErr
     * @param string $redirectPath
     * @param int[]  $validExitCodes
     */
    public function __construct(
        string $cmd,
        int $code,
        string $stdOut = '',
        string $stdErr = '',
        string $redirectPath = '',
        array $validExitCodes = [0]
    ) {
        $this->cmd            = $cmd;
        $this->code           = $code;
        $this->stdOut         = $stdOut;
        $this->stdErr         = $stdErr;
        $this->redirectPath   = $redirectPath;
        $this->validExitCodes = $validExitCodes;
    }

    /**
     * Cmd getter.
     *
     * @return string
     */
    public function getCmd(): string
    {
        return $this->cmd;
    }

    /**
     * Code getter.
     *
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * Command executed successful.
     */
    public function isSuccessful(): bool
    {
        return in_array($this->code, $this->validExitCodes);
    }

    /**
     * StdOutput getter.
     *
     * @return string
     */
    public function getStdOut(): string
    {
        return $this->stdOut;
    }

    /**
     * StdError getter.
     *
     * @return string
     */
    public function getStdErr(): string
    {
        return $this->stdErr;
    }

    /**
     * Is the output redirected to a file.
     *
     * @return bool
     */
    public function isOutputRedirected(): bool
    {
        return !empty($this->redirectPath);
    }

    /**
     * Return path to the file where the output is redirected to.
     *
     * @return string
     */
    public function getRedirectPath(): string
    {
        return $this->redirectPath;
    }

    /**
     * Return the output as array.
     *
     * @return array
     */
    public function getStdOutAsArray(): array
    {
        if (null === $this->buffer) {
            $this->buffer = $this->textToBuffer();
        }
        return $this->buffer;
    }

    /**
     * Converts a string into an array.
     *
     * @return array
     */
    private function textToBuffer(): array
    {
        return OutputUtil::trimEmptyLines(explode("\n", OutputUtil::normalizeLineEndings($this->stdOut)));
    }

    /**
     * Magic to string method.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->stdOut;
    }
}

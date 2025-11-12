<?php

/**
 * This file is part of SebastianFeldmann\Cli.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianFeldmann\Cli\Reader;

use Exception;

/**
 * StandardInput
 *
 * @package SebastianFeldmann\Cli
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/sebastianfeldmann/cli
 * @since   Class available since Release 3.3.0
 */
class StandardInput extends Abstraction
{
    /**
     * Standard Input stream handle
     *
     * @var resource
     */
    private $handle;

    /**
     * StandardInput constructor.
     *
     * @param resource $stdInHandle
     */
    public function __construct($stdInHandle)
    {
        $this->handle = $stdInHandle;
    }

    /**
     * Create the generator
     *
     * @return iterable
     * @throws \Exception
     */
    protected function createIterator(): iterable
    {
        $read   = [$this->handle];
        $write  = [];
        $except = [];
        $result = stream_select($read, $write, $except, 0);

        if ($result === false) {
            throw new Exception('stream_select failed');
        }
        if ($result !== 0) {
            while (!\feof($this->handle)) {
                yield \fgets($this->handle);
            }
        }
    }
}

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

use Iterator;
use SebastianFeldmann\Cli\Reader;

/**
 * Abstract Reader class
 *
 * @package SebastianFeldmann\Cli
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/sebastianfeldmann/cli
 * @since   Class available since Release 3.3.0
 */
abstract class Abstraction implements Reader
{
    /**
     * Internal iterator to handle foreach
     *
     * @var \Iterator
     */
    private $iterator;

    /**
     * Return the internal iterator
     *
     * @return \Iterator
     */
    private function getIterator(): Iterator
    {
        return $this->iterator;
    }

    /**
     * Set the pointer to the next line
     *
     * @return void
     */
    public function next(): void
    {
        $this->getIterator()->next();
    }

    /**
     * Get the line number of the current line
     *
     * @return int
     */
    public function key(): int
    {
        return $this->getIterator()->key();
    }

    /**
     * Check whether the current line is valid
     *
     * @return bool
     */
    public function valid(): bool
    {
        return $this->getIterator()->valid();
    }

    /**
     * Recreate/rewind the iterator
     *
     * @return void
     */
    public function rewind(): void
    {
        $this->iterator = $this->createIterator();
    }

    /**
     * Get the current line
     *
     * @return string
     */
    public function current(): string
    {
        return $this->getIterator()->current();
    }

    /**
     * Create the internal iterator
     *
     * @return iterable
     */
    abstract protected function createIterator(): iterable;
}

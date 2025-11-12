<?php

/**
 * This file is part of SebastianFeldmann\Git.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianFeldmann\Git\Command\Output;

use SebastianFeldmann\Cli\Command\OutputFormatter;

/**
 * Output formatter that uses explode to return list of maps
 *
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/sebastianfeldmann/git
 * @since   Class available since Release 3.10.0
 */
class Exploded implements OutputFormatter
{
    /**
     * @var string
     */
    protected string $separator;

    /**
     * @var array<int, string>
     */
    private array $names;

    /**
     * @param string             $separator
     * @param array<int, string> $names
     */
    public function __construct(string $separator, array $names)
    {
        $this->separator = $separator;
        $this->names     = $names;
    }

    /**
     * Format the output
     *
     * @param  array<string> $output
     * @return iterable<array<string, string>>
     */
    public function format(array $output): iterable
    {
        $logs = [];
        foreach ($output as $line) {
            $parts = explode($this->separator, $line);
            $log   = [];
            foreach ($parts as $index => $value) {
                $log[$this->names[$index]] = $value;
            }
            $logs[] = $log;
        }
        return $logs;
    }
}

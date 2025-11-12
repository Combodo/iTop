<?php

/**
 * This file is part of SebastianFeldmann\Git.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianFeldmann\Git\Command\DiffIndex\GetStagedFiles;

use SebastianFeldmann\Cli\Command\OutputFormatter;

/**
 * Class FilterByStatus
 *
 * @package SebastianFeldmann\Git
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/sebastianfeldmann/git
 * @since   Class available since Release 0.9.0
 */
class FilterByStatus implements OutputFormatter
{
    /**
     * List of status to keep
     *
     * @var array<string>
     */
    private array $status;

    /**
     * FilterByStatus constructor
     *
     * @param array<string> $status
     */
    public function __construct(array $status)
    {
        $this->status = $status;
    }

    /**
     * Format the output
     *
     * @param  array<string> $output
     * @return iterable<string>
     */
    public function format(array $output): iterable
    {
        $formatted = [];
        $pattern = sprintf('#^(?:%s)\t(.+)$#i', implode('|', $this->status));
        foreach ($output as $row) {
            $matches = [];
            if (preg_match($pattern, $row, $matches)) {
                $formatted[] = $matches[1];
            }
        }
        return $formatted;
    }
}

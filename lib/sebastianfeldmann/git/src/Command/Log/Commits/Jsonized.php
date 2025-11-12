<?php

/**
 * This file is part of SebastianFeldmann\Git.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianFeldmann\Git\Command\Log\Commits;

use SebastianFeldmann\Cli\Command\OutputFormatter;
use SebastianFeldmann\Git\Log\Commit;

/**
 * Class Jsonized
 *
 * @package SebastianFeldmann\Git
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/sebastianfeldmann/git
 * @since   Class available since Release 0.9.0
 */
class Jsonized implements OutputFormatter
{
    /**
     * Git log format to use.
     *
     * @var string
     */
    public const FORMAT = '{"hash": "%h", "names": "%d", "subject": "%s", "date": "%ci", "author": "%an"}';

    /**
     * Format the output.
     *
     * @param  array<string> $output
     * @return iterable<Commit>
     * @throws \Exception
     */
    public function format(array $output): iterable
    {
        $formatted = [];
        foreach ($output as $row) {
            $formatted[] = $this->createCommit($row);
        }
        return $formatted;
    }

    /**
     * Create a log commit object.
     *
     * @param  string $row
     * @return \SebastianFeldmann\Git\Log\Commit
     * @throws \Exception
     */
    private function createCommit(string $row): Commit
    {
        $std   = json_decode($row);
        $date  = new \DateTimeImmutable($std->date);
        $names = array_map('trim', explode(',', str_replace(['(', ')'], '', $std->names)));

        return new Commit($std->hash, $names, $std->subject, '', $date, $std->author);
    }
}

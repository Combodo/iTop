<?php

/**
 * This file is part of SebastianFeldmann\Git.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianFeldmann\Git\Command\Config;

use SebastianFeldmann\Cli\Command\OutputFormatter;

/**
 * Class MapSettings
 *
 * @package SebastianFeldmann\Git
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/sebastianfeldmann/git
 * @since   Class available since Release 1.0.8
 */
class MapValues implements OutputFormatter
{
    /**
     * Format the output
     *
     * @param  array<string> $output
     * @return iterable<string, string>
     */
    public function format(array $output): iterable
    {
        $formatted = [];
        foreach ($output as $row) {
            $keyValue                      = explode('=', $row);
            $formatted[trim($keyValue[0])] = trim(implode('=', array_slice($keyValue, 1)));
        }
        return $formatted;
    }
}

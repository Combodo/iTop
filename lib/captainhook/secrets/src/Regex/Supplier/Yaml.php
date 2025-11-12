<?php

/**
 * This file is part of CaptainHook Secrets.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\Secrets\Regex\Supplier;

use CaptainHook\Secrets\Regex\Grouped;

/**
 * Find any possible string assignment in a yaml file
 *
 * Finds:
 *  - foo: "string"
 *  - foo: string
 */
class Yaml implements Grouped
{
    /**
     * Returns a list of patterns to check
     *
     * @return array<string>
     */
    public function patterns(): array
    {
        return [
            '#: ("|\'?)([^\n]*)\\1+#i',
        ];
    }

    /**
     * Return capture group to access the password
     *
     * @return array<int>
     */
    public function indexes(): array
    {
        return [2];
    }
}

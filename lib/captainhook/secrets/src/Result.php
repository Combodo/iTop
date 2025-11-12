<?php

/**
 * This file is part of CaptainHook Secrets.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace CaptainHook\Secrets;

/**
 * Result
 *
 * Holds all information about the executed detection
 *
 * @package CaptainHook-Secrets
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/secrets
 * @since   Class available since Release 0.9.1
 */
class Result
{
    /**
     * List of found secrets
     *
     * @var array<string>
     */
    private array $matches;

    /**
     * @param array<string> $matches
     */
    public function __construct(array $matches)
    {
        $this->matches = $matches;
    }

    /**
     * Returns true if a secret was found
     *
     * @return bool
     */
    public function wasSecretDetected(): bool
    {
        return count($this->matches) > 0;
    }

    /**
     * Returns a list of all matches found during detection
     *
     * @return array<string>
     */
    public function matches(): array
    {
        return $this->matches;
    }
}

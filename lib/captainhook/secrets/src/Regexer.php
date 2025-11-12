<?php

/**
 * This file is part of secrets.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\Secrets;

use CaptainHook\Secrets\Regex\Grouped;

class Regexer
{
    /**
     * @var \CaptainHook\Secrets\Regex\Grouped
     */
    private Grouped $supplier;

    /**
     * Creates a new Detector
     *
     * @return \CaptainHook\Secrets\Regexer
     */
    public static function create(): self
    {
        return new self();
    }

    /**
     * The Regexer can only deal with Grouped Suppliers
     * Those Suppliers provide the index of the detection group where to find the potential password.
     *
     * @param \CaptainHook\Secrets\Regex\Grouped $supplier
     * @return $this
     */
    public function useGroupedSupplier(Grouped $supplier): self
    {
        $this->supplier = $supplier;
        return $this;
    }

    /**
     * Returns a lost of all potential passwords
     *
     * @param string $text
     * @return \CaptainHook\Secrets\Result
     */
    public function detectIn(string $text): Result
    {
        $allMatches = [];
        foreach ($this->supplier->patterns() as $num => $regex) {
            $matches = [];
            if (preg_match($regex, $text, $matches)) {
                $allMatches[] = $matches[$this->supplier->indexes()[$num]];
            }
        }
        return new Result($allMatches);
    }
}

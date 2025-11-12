<?php

/**
 * This file is part of SebastianFeldmann\Git.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianFeldmann\Git\Diff;

/**
 * Class Change.
 *
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/sebastianfeldmann/git
 * @since   Class available since Release 1.2.0
 */
class Change
{
    /**
     * Optional file header.
     *
     * @var string
     */
    private string $header;

    /**
     * Pre range.
     *
     * @var array{from: int|null, to: int|null}
     */
    private array $pre;

    /**
     * Post range.
     *
     * @var array{from: int|null, to: int|null}
     */
    private array $post;

    /**
     * List of changed lines.
     *
     * @var \SebastianFeldmann\Git\Diff\Line[]
     */
    private array $lines = [];

    /**
     * Chan
     * ge constructor.
     *
     * @param string $ranges
     * @param string $header
     */
    public function __construct(string $ranges, string $header = '')
    {
        $this->header = $header;
        $this->splitRanges($ranges);
    }

    /**
     * Header getter
     *
     * @return string
     */
    public function getHeader(): string
    {
        return $this->header;
    }

    /**
     * Pre range getter
     *
     * @return array<string, int|null>
     */
    public function getPre(): array
    {
        return $this->pre;
    }

    /**
     * Post range getter
     *
     * @return array<string, int|null>
     */
    public function getPost(): array
    {
        return $this->post;
    }

    /**
     * Return list of changed lines
     *
     * @return \SebastianFeldmann\Git\Diff\Line[]
     */
    public function getLines(): array
    {
        return $this->lines;
    }

    /**
     * Return list of added content
     *
     * @return string[]
     */
    public function getAddedContent(): array
    {
        $added = [];
        foreach ($this->lines as $line) {
            if ($line->getOperation() === Line::ADDED) {
                $added[] = $line->getContent();
            }
        }
        return $added;
    }

    /**
     * Add a line to the change
     *
     * @param  \SebastianFeldmann\Git\Diff\Line $line
     * @return void
     */
    public function addLine(Line $line): void
    {
        $this->lines[] = $line;
    }

    /**
     * Parse ranges and split them into pre and post range
     *
     * @param  string $ranges
     * @return void
     */
    private function splitRanges(string $ranges): void
    {
        $matches = [];
        if (!preg_match('#^[\-|+](\d+)(?:,(\d+))? [\-+](\d+)(?:,(\d+))?$#', $ranges, $matches)) {
            throw new \RuntimeException('invalid ranges: ' . $ranges);
        }

        $matches = array_map(
            function ($value) {
                if (strlen($value) === 0) {
                    return null;
                }
                return $value;
            },
            $matches
        );

        $this->pre = [
            'from' => isset($matches[1]) ? (int) $matches[1] : null,
            'to'   => isset($matches[2]) ? (int) $matches[2] : null,
        ];

        $this->post = [
            'from' => isset($matches[3]) ? (int) $matches[3] : null,
            'to'   => isset($matches[4]) ? (int) $matches[4] : null,
        ];
    }
}

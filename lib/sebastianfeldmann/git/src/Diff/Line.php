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
 * Class Line.
 *
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/sebastianfeldmann/git
 * @since   Class available since Release 1.2.0
 */
class Line
{
    /**
     * Possible line operations
     */
    public const ADDED   = 'added';
    public const REMOVED = 'removed';
    public const EXISTED = 'existed';

    /**
     * Map diff output to file operation
     * @var array<string, string>
     */
    public static $opsMap = [
        '+' => self::ADDED,
        '-' => self::REMOVED,
        ' ' => self::EXISTED
    ];

    /**
     * @var string
     */
    private string $operation;

    /**
     * @var string
     */
    private string $content;

    /**
     * Line constructor.
     *
     * @param string $operation
     * @param string $content
     */
    public function __construct(string $operation, string $content)
    {
        if (!in_array($operation, self::$opsMap)) {
            throw new \RuntimeException('invalid line operation: ' . $operation);
        }
        $this->operation = $operation;
        $this->content   = $content;
    }

    /**
     * Operation getter.
     *
     * @return string
     */
    public function getOperation(): string
    {
        return $this->operation;
    }

    /**
     * Content getter.
     *
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }
}

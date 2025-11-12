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
 * Class File
 *
 * @package SebastianFeldmann\Git
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/sebastianfeldmann/git
 * @since   Class available since Release 1.2.0
 */
class File
{
    public const OP_DELETED = 'deleted';
    public const OP_CREATED = 'created';
    public const OP_MODIFIED = 'modified';
    public const OP_RENAMED = 'renamed';
    public const OP_COPIED = 'copied';

    /**
     * List of changes.
     *
     * @var \SebastianFeldmann\Git\Diff\Change[]
     */
    private array $changes = [];

    /**
     * Filename
     *
     * @var string
     */
    private string $name;

    /**
     * Operation performed on the file.
     *
     * @var string
     */
    private string $operation;

    /**
     * File constructor.
     *
     * @param string $name
     * @param string $operation
     */
    public function __construct(string $name, string $operation)
    {
        $this->operation = $operation;
        $this->name      = $name;
    }

    /**
     * Returns the filename.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Returns the performed operation.
     *
     * @return string
     */
    public function getOperation(): string
    {
        return $this->operation;
    }

    /**
     * Returns the list of changes in this file.
     *
     * @return \SebastianFeldmann\Git\Diff\Change[]
     */
    public function getChanges(): array
    {
        return $this->changes;
    }

    /**
     * Add a change to the list of changes.
     *
     * @param  \SebastianFeldmann\Git\Diff\Change $change
     * @return void
     */
    public function addChange(Change $change): void
    {
        $this->changes[] = $change;
    }

    /**
     * Return all newly added content
     *
     * @return string[]
     */
    public function getAddedContent(): array
    {
        $content = [];
        if ($this->operation === self::OP_DELETED) {
            return $content;
        }

        foreach ($this->changes as $change) {
            $content = array_merge($content, $change->getAddedContent());
        }

        return $content;
    }
}

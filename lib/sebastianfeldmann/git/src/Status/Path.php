<?php

/**
 * This file is part of SebastianFeldmann\Git.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianFeldmann\Git\Status;

/**
 * Class Path
 *
 * @package SebastianFeldmann\Git
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/sebastianfeldmann/git
 * @since   Class available since Release 3.6.0
 * @link    https://git-scm.com/docs/git-status#_output git-status status codes
 */
class Path
{
    public const UNMODIFIED = "\x20";
    public const MODIFIED = 'M';
    public const ADDED = 'A';
    public const DELETED = 'D';
    public const RENAMED = 'R';
    public const COPIED = 'C';
    public const UPDATED_UNMERGED = 'U';
    public const UNTRACKED = '??';
    public const IGNORED = '!!';

    /**
     * Status code tuple.
     *
     * We initialize each item in the tuple with a single space (U+0020),
     * since a space is a valid character, meaning "unmodified," in the
     * `git status` output.
     *
     * @var array{0: string, 1: string}
     */
    private array $statusCode = [self::UNMODIFIED, self::UNMODIFIED];

    /**
     * Path.
     *
     * @var string
     */
    private string $path = '';

    /**
     * Original path, if this is a copied or renamed path.
     *
     * @var string|null
     */
    private ?string $originalPath = null;

    /**
     * Path constructor.
     *
     * @param string $statusCode
     * @param string $path
     * @param string|null $originalPath
     */
    public function __construct(string $statusCode, string $path, ?string $originalPath = null)
    {
        $this->statusCode[0] = $statusCode[0] ?? self::UNMODIFIED;
        $this->statusCode[1] = $statusCode[1] ?? self::UNMODIFIED;
        $this->path = $path;
        $this->originalPath = $originalPath;
    }

    /**
     * Returns the status code tuple.
     *
     * @return array{0: string, 1: string}
     */
    public function getStatusCode(): array
    {
        return $this->statusCode;
    }

    /**
     * Returns the status code as it appears in the raw `git status` output.
     *
     * @return string
     */
    public function getRawStatusCode(): string
    {
        return implode('', $this->statusCode);
    }

    /**
     * Returns the path.
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Returns the original path, if this is a copied or renamed path.
     *
     * @return string|null
     */
    public function getOriginalPath(): ?string
    {
        return $this->originalPath;
    }

    /**
     * Returns true if the path is not updated in the index.
     *
     * @return bool
     */
    public function isNotUpdated(): bool
    {
        return $this->getStatusCode()[0] === self::UNMODIFIED;
    }

    /**
     * Returns true if the path is updated in the index.
     *
     * @return bool
     */
    public function isUpdatedInIndex(): bool
    {
        return $this->getStatusCode()[0] === self::MODIFIED;
    }

    /**
     * Returns true if the path is a new file added to the index.
     *
     * @return bool
     */
    public function isAddedToIndex(): bool
    {
        return !$this->isUnmerged() && $this->getStatusCode()[0] === self::ADDED;
    }

    /**
     * Return true if the path is deleted from the index.
     *
     * @return bool
     */
    public function isDeletedFromIndex(): bool
    {
        return !$this->isUnmerged() && $this->getStatusCode()[0] === self::DELETED;
    }

    /**
     * Returns true if the path is renamed in the index.
     *
     * @return bool
     */
    public function isRenamedInIndex(): bool
    {
        return $this->getStatusCode()[0] === self::RENAMED;
    }

    /**
     * Returns true if the path is copied in the index.
     *
     * @return bool
     */
    public function isCopiedInIndex(): bool
    {
        return $this->getStatusCode()[0] === self::COPIED;
    }

    /**
     * Returns true if the path in the index matches that in the working tree.
     *
     * @return bool
     */
    public function doesIndexMatchWorkingTree(): bool
    {
        return $this->getStatusCode()[1] === self::UNMODIFIED;
    }

    /**
     * Returns true if the path in the working tree has changes
     * that are not in the index.
     *
     * @return bool
     */
    public function hasWorkingTreeChangedSinceIndex(): bool
    {
        return $this->getStatusCode()[1] === self::MODIFIED;
    }

    /**
     * Returns true if the path is deleted in the working tree
     * but not in the index.
     *
     * @return bool
     */
    public function isDeletedInWorkingTree(): bool
    {
        return !$this->isUnmerged() && $this->getStatusCode()[1] === self::DELETED;
    }

    /**
     * Returns true if the path is renamed in the working tree
     * but not in the index.
     *
     * @return bool
     */
    public function isRenamedInWorkingTree(): bool
    {
        return $this->getStatusCode()[1] === self::RENAMED;
    }

    /**
     * Returns true if the path is copied in the working tree
     * but not in the index.
     *
     * @return bool
     */
    public function isCopiedInWorkingTree(): bool
    {
        return $this->getStatusCode()[1] === self::COPIED;
    }

    /**
     * Returns true if the path is added in the working tree
     * but not in the index (a.k.a. intent to add).
     *
     * @return bool
     */
    public function isAddedInWorkingTree(): bool
    {
        return !$this->isUnmerged() && $this->getStatusCode()[1] === self::ADDED;
    }

    /**
     * Returns true if there is currently a merge conflict
     * and the path needs conflicts resolved.
     *
     * @return bool
     */
    public function isUnmerged(): bool
    {
        return in_array(self::UPDATED_UNMERGED, $this->getStatusCode())
            || $this->areBothDeleted()
            || $this->areBothAdded();
    }

    /**
     * Returns true if the path is in conflict and deleted by each head of the merge.
     *
     * @return bool
     */
    public function areBothDeleted(): bool
    {
        return $this->getStatusCode()[0] === self::DELETED
            && $this->getStatusCode()[1] === self::DELETED;
    }

    /**
     * Returns true if the path is in conflict and added by each head of the merge.
     *
     * @return bool
     */
    public function areBothAdded(): bool
    {
        return $this->getStatusCode()[0] === self::ADDED
            && $this->getStatusCode()[1] === self::ADDED;
    }

    /**
     * Returns true if the path is in conflict and modified by each head of the merge.
     *
     * @return bool
     */
    public function areBothModified(): bool
    {
        return $this->getStatusCode()[0] === self::UPDATED_UNMERGED
            && $this->getStatusCode()[1] === self::UPDATED_UNMERGED;
    }

    /**
     * Returns true if the path is in conflict and added by us.
     *
     * @return bool
     */
    public function isAddedByUs(): bool
    {
        return $this->getStatusCode()[0] === self::ADDED
            && $this->getStatusCode()[1] === self::UPDATED_UNMERGED;
    }

    /**
     * Returns true if the path is in conflict and deleted by us.
     *
     * @return bool
     */
    public function isDeletedByUs(): bool
    {
        return $this->getStatusCode()[0] === self::DELETED
            && $this->getStatusCode()[1] === self::UPDATED_UNMERGED;
    }

    /**
     * Returns true if the path is in conflict and added by them.
     *
     * @return bool
     */
    public function isAddedByThem(): bool
    {
        return $this->getStatusCode()[0] === self::UPDATED_UNMERGED
            && $this->getStatusCode()[1] === self::ADDED;
    }

    /**
     * Returns true if the path is in conflict and deleted by them.
     *
     * @return bool
     */
    public function isDeletedByThem(): bool
    {
        return $this->getStatusCode()[0] === self::UPDATED_UNMERGED
            && $this->getStatusCode()[1] === self::DELETED;
    }

    /**
     * Returns true if the path is untracked.
     *
     * @return bool
     */
    public function isUntracked(): bool
    {
        return $this->getRawStatusCode() === self::UNTRACKED;
    }

    /**
     * Returns true if the path is ignored.
     *
     * @return bool
     */
    public function isIgnored(): bool
    {
        return $this->getRawStatusCode() === self::IGNORED;
    }
}

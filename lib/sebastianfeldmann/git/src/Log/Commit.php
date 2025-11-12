<?php

/**
 * This file is part of SebastianFeldmann\Git.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianFeldmann\Git\Log;

/**
 * Class Commit
 *
 * @package SebastianFeldmann\Git
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/sebastianfeldmann/git
 * @since   Class available since Release 1.2.0
 */
class Commit
{
    /**
     * @var string
     */
    private string $hash;

    /**
     * @var array<string>
     */
    private array $names;

    /**
     * @var string
     */
    private string $subject;

    /**
     * @var string
     */
    private string $body;

    /**
     * @var \DateTimeImmutable
     */
    private \DateTimeImmutable $date;

    /**
     * @var string
     */
    private string $author;

    /**
     * Commit constructor
     *
     * @param string             $hash
     * @param array<string>      $names
     * @param string             $subject
     * @param string             $body
     * @param \DateTimeImmutable $date
     * @param string             $author
     */
    public function __construct(
        string $hash,
        array $names,
        string $subject,
        string $body,
        \DateTimeImmutable $date,
        string $author
    ) {
        $this->hash    = $hash;
        $this->names   = $names;
        $this->subject = $subject;
        $this->body    = $body;
        $this->date    = $date;
        $this->author  = $author;
    }

    /**
     * Hash getter
     *
     * @return string
     */
    public function getHash(): string
    {
        return $this->hash;
    }

    /**
     * Does the commit have names
     *
     * @return bool
     */
    public function hasNames(): bool
    {
        return !empty($this->names);
    }

    /**
     * Names getter
     *
     * @return array<string>
     */
    public function getNames(): array
    {
        return $this->names;
    }

    /**
     * Description getter
     *
     * @deprecated
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->getSubject();
    }

    /**
     * Subject getter
     *
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * Body getter
     *
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * Date getter
     *
     * @return \DateTimeImmutable
     */
    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }

    /**
     * Author getter
     *
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }
}

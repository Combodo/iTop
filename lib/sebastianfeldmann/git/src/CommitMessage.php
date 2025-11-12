<?php

/**
 * This file is part of SebastianFeldmann\Git.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianFeldmann\Git;

use RuntimeException;

/**
 * Class CommitMessage
 *
 * @package SebastianFeldmann\Git
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/sebastianfeldmann/git
 * @since   Class available since Release 0.9.0
 */
class CommitMessage
{
    /**
     * Commit Message content
     *
     * This includes lines that are comments.
     *
     * @var string
     */
    private string $rawContent;

    /**
     * Content split lines
     *
     * This includes lines that are comments.
     *
     * @var array<int, string>
     */
    private array $rawLines;

    /**
     * Amount of lines
     *
     * This includes lines that are comments
     *
     * @var int
     */
    private int $rawLineCount;

    /**
     * The comment character
     *
     * @var string
     */
    private string $commentCharacter;

    /**
     * All non comment lines
     *
     * @var array<int, string>
     */
    private array $contentLines;

    /**
     * Get the number of lines
     *
     * This excludes lines which are comments.
     *
     * @var int
     */
    private int $contentLineCount;

    /**
     * Commit Message content
     *
     * This excludes lines that are comments.
     *
     * @var string
     */
    private string $content;

    /**
     * CommitMessage constructor
     *
     * @param string $content
     * @param string $commentCharacter
     */
    public function __construct(string $content, string $commentCharacter = '#')
    {
        $this->rawContent       = $content;
        $this->rawLines         = empty($content) ? [] : $this->splitByLine($content);
        $this->rawLineCount     = count($this->rawLines);
        $this->commentCharacter = $commentCharacter;
        $this->contentLines     = $this->getContentLines($this->rawLines, $commentCharacter);
        $this->contentLineCount = count($this->contentLines);
        $this->content          = implode(PHP_EOL, $this->contentLines);
    }

    /**
     * Is message empty.
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->content);
    }

    /**
     * Is this a fixup commit
     *
     * @return bool
     */
    public function isFixup(): bool
    {
        return str_starts_with($this->rawContent, 'fixup!');
    }

    /**
     * Is this a squash commit
     *
     * @return bool
     */
    public function isSquash(): bool
    {
        return str_starts_with($this->rawContent, 'squash!');
    }

    /**
     * Get commit message content
     *
     * This excludes lines that are comments.
     *
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Get complete commit message content
     *
     * This includes lines that are comments.
     *
     * @return string
     */
    public function getRawContent(): string
    {
        return $this->rawContent;
    }

    /**
     * Return all lines
     *
     * This includes lines that are comments.
     *
     * @return array<string>
     */
    public function getLines(): array
    {
        return $this->rawLines;
    }

    /**
     * Return line count
     *
     * This includes lines that are comments.
     *
     * @return int
     */
    public function getLineCount(): int
    {
        return $this->rawLineCount;
    }

    /**
     * Return content line count
     *
     * This doesn't includes lines that are comments.
     *
     * @return int
     */
    public function getContentLineCount(): int
    {
        return $this->contentLineCount;
    }

    /**
     * Get a specific line
     *
     * @param  int $index
     * @return string
     */
    public function getLine(int $index): string
    {
        return $this->rawLines[$index] ?? '';
    }

    /**
     * Get a specific content line
     *
     * @param  int $index
     * @return string
     */
    public function getContentLine(int $index): string
    {
        return $this->contentLines[$index] ?? '';
    }

    /**
     * Return first line
     *
     * @return string
     */
    public function getSubject(): string
    {
        return $this->contentLines[0] ?? '';
    }

    /**
     * Return content from line nr. 3 to the last line
     *
     * @return string
     */
    public function getBody(): string
    {
        return implode(PHP_EOL, $this->getBodyLines());
    }

    /**
     * Return lines from line nr. 3 to the last line
     *
     * @return array<int, string>
     */
    public function getBodyLines(): array
    {
        return $this->contentLineCount < 3 ? [] : array_slice($this->contentLines, 2);
    }

    /**
     * Returns all comment lines as string
     *
     * @return string
     */
    public function getComments(): string
    {
        return implode(PHP_EOL, $this->getCommentLines());
    }

    /**
     * Returns all comment lines in an array
     *
     * @return array<int, string>
     */
    public function getCommentLines(): array
    {
        $comments           = [];
        $allCommentFromHere = false;
        foreach ($this->getLines() as $line) {
            if ($this->isCommentLine($line) || $allCommentFromHere) {
                if (!$allCommentFromHere && $this->isScissorLine($line)) {
                    $allCommentFromHere = true;
                }
                $comments[] = $line;
            }
        }
        return $comments;
    }

    /**
     * Get the comment character
     *
     * Comment character defaults to '#'.
     *
     * @return string
     */
    public function getCommentCharacter(): string
    {
        return $this->commentCharacter;
    }

    /**
     * Get the lines that are not comments
     *
     * @param  array<int, string> $rawLines
     * @param  string             $commentCharacter
     * @return array<int, string>
     */
    private function getContentLines(array $rawLines, string $commentCharacter): array
    {
        $lines = [];
        foreach ($rawLines as $line) {
            // if we handle a comment line
            if ($this->isCommentLine($line)) {
                // check if we should ignore all following lines
                if ($this->isScissorLine($line)) {
                    break;
                }
                // or only the current one
                continue;
            }
            $lines[] = $line;
        }
        return $lines;
    }

    /**
     * Is the line a comment line
     *
     * @param  string $line
     * @return bool
     */
    private function isCommentLine(string $line): bool
    {
        return str_starts_with(trim($line), $this->commentCharacter);
    }

    /**
     * Check if the scissor operator is used to mark all following lines as comment
     *
     * @param  string $line
     * @return bool
     */
    public function isScissorLine(string $line): bool
    {
        return str_contains($line, '------------------------ >8 ------------------------');
    }

    /**
     * Split message into separate lines
     *
     * @param  string $content
     * @return array<int, string>
     */
    private function splitByLine(string $content): array
    {
        $lines = (array) preg_split("/\\r\\n|\\r|\\n/", $content);
        return array_filter($lines, fn($line) => is_string($line));
    }

    /**
     * Create CommitMessage from file
     *
     * @param  string $path
     * @param  string $commentCharacter
     * @return \SebastianFeldmann\Git\CommitMessage
     */
    public static function createFromFile(string $path, string $commentCharacter = '#'): CommitMessage
    {
        if (!file_exists($path)) {
            throw new RuntimeException('Commit message file not found');
        }
        return new CommitMessage((string) file_get_contents($path), $commentCharacter);
    }
}

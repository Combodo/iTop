<?php

/**
 * This file is part of SebastianFeldmann\Git.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianFeldmann\Git\Command\Diff\Compare;

use SebastianFeldmann\Cli\Command\OutputFormatter;
use SebastianFeldmann\Git\Diff\Change;
use SebastianFeldmann\Git\Diff\File;
use SebastianFeldmann\Git\Diff\Line;

/**
 * FullDiffList output formatter.
 *
 * Returns a list of SebastianFeldmann\Git\Diff\File objects. Each containing
 * the list of changes that happened in that file.
 *
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/sebastianfeldmann/git
 * @since   Class available since Release 1.2.0
 */
class FullDiffList implements OutputFormatter
{
    /**
     * Available line types of git diff output
     */
    private const LINE_TYPE_START      = 'Start';
    private const LINE_TYPE_HEADER     = 'Header';
    private const LINE_TYPE_SIMILARITY = 'HeaderSimilarity';
    private const LINE_TYPE_OP         = 'HeaderOp';
    private const LINE_TYPE_INDEX      = 'HeaderIndex';
    private const LINE_TYPE_FORMAT     = 'HeaderFormat';
    private const LINE_TYPE_POSITION   = 'ChangePosition';
    private const LINE_TYPE_CODE       = 'ChangeCode';

    /**
     * Search and parse strategy
     *
     * Define possible follow up lines for each line type to minimize search effort.
     *
     * @var array<string, array<string>>
     */
    private static array $lineTypesToCheck = [
        self::LINE_TYPE_START => [
            self::LINE_TYPE_HEADER
        ],
        self::LINE_TYPE_HEADER => [
            self::LINE_TYPE_SIMILARITY,
            self::LINE_TYPE_OP,
            self::LINE_TYPE_INDEX,
        ],
        self::LINE_TYPE_SIMILARITY => [
            self::LINE_TYPE_OP,
            self::LINE_TYPE_INDEX,
        ],
        self::LINE_TYPE_OP => [
            self::LINE_TYPE_OP,
            self::LINE_TYPE_INDEX
        ],
        self::LINE_TYPE_INDEX => [
            self::LINE_TYPE_FORMAT
        ],
        self::LINE_TYPE_FORMAT => [
            self::LINE_TYPE_FORMAT,
            self::LINE_TYPE_POSITION
        ],
        self::LINE_TYPE_POSITION => [
            self::LINE_TYPE_CODE
        ],
        self::LINE_TYPE_CODE => [
            self::LINE_TYPE_HEADER,
            self::LINE_TYPE_POSITION,
            self::LINE_TYPE_CODE
        ]
    ];

    /**
     * Maps git diff output to file operations
     *
     * @var array<string, string>
     */
    private static array $opsMap = [
        'old'     => File::OP_MODIFIED,
        'new'     => File::OP_CREATED,
        'deleted' => File::OP_DELETED,
        'rename'  => File::OP_RENAMED,
        'copy'    => File::OP_COPIED,
    ];

    /**
     * List of diff File objects
     *
     * @var array<\SebastianFeldmann\Git\Diff\File>
     */
    private array $files = [];

    /**
     * The currently processed file
     *
     * @var \SebastianFeldmann\Git\Diff\File|null
     */
    private ?File $currentFile = null;

    /**
     * The file name of the currently processed file
     *
     * @var string|null
     */
    private ?string $currentFileName = null;

    /**
     * The change position of the currently processed file
     *
     * @var string|null
     */
    private ?string $currentPosition = null;

    /**
     * The operation of the currently processed file
     *
     * @var string|null
     */
    private ?string $currentOperation = null;

    /**
     * List of collected changes
     *
     * @var \SebastianFeldmann\Git\Diff\Change[]
     */
    private array $currentChanges = [];

    /**
     * Format the output
     *
     * @param  array<string> $output
     * @return iterable<\SebastianFeldmann\Git\Diff\File>
     */
    public function format(array $output): iterable
    {
        $previousLineType = self::LINE_TYPE_START;
        // for each line of the output
        for ($i = 0, $length = count($output); $i < $length; $i++) {
            $line = $output[$i];
            // depending on the previous line type
            // check for all possible following line types and handle it
            foreach (self::$lineTypesToCheck[$previousLineType] as $typeToCheck) {
                $call = 'is' . $typeToCheck . 'Line';
                // if the line type could be matched
                if ($this->$call($line)) {
                    // remember the line type
                    $previousLineType = $typeToCheck;
                    break;
                }
            }
        }
        $this->appendCollectedFileAndChanges();

        return $this->files;
    }

    /**
     * Is the given line a diff header line
     *
     * diff --git a/some/file b/some/file
     *
     * @param  string $line
     * @return bool
     */
    private function isHeaderLine(string $line): bool
    {
        $matches = [];
        if (preg_match('#^diff --git [abciwo]/(.*) [abciwo]/(.*)#', $line, $matches)) {
            $this->appendCollectedFileAndChanges();
            $this->currentOperation = File::OP_MODIFIED;
            $this->currentFileName  = $matches[2];
            return true;
        }
        return false;
    }

    /**
     * Is the given line a diff header similarity line.
     *
     * similarity index 96%
     *
     * @param  string $line
     * @return bool
     */
    private function isHeaderSimilarityLine(string $line): bool
    {
        $matches = [];
        return (bool)preg_match('#^(similarity|dissimilarity) index [0-9]+%$#', $line, $matches);
    }

    /**
     * Is the given line a diff header operation line.
     *
     * new file mode 100644
     * delete file
     * rename from some/file
     * rename to some/other/file
     *
     * @param  string $line
     * @return bool
     */
    private function isHeaderOpLine(string $line): bool
    {
        $matches = [];
        if (preg_match('#^(old|new|deleted|rename|copy) (file mode|from|to) (.+)#', $line, $matches)) {
            $this->currentOperation = self::$opsMap[$matches[1]];
            return true;
        }
        return false;
    }

    /**
     * Is the given line a diff header index line.
     *
     * index f7fc435..7b5bd26 100644
     *
     * @param  string $line
     * @return bool
     */
    private function isHeaderIndexLine(string $line): bool
    {
        $matches = [];
        if (preg_match('#^index\s([a-z0-9]+)\.\.([a-z0-9]+)(.*)$#i', $line, $matches)) {
            $this->currentFile = new File($this->currentFileName, $this->currentOperation);
            return true;
        }
        return false;
    }

    /**
     * Is the given line a diff header format line.
     *
     * --- a/some/file
     * +++ b/some/file
     *
     * @param  string $line
     * @return bool
     */
    private function isHeaderFormatLine(string $line): bool
    {
        $matches = [];
        return (bool)preg_match('#^[\\-\\+]{3} [abciwo]?/.*#', $line, $matches);
    }

    /**
     * Is the given line a diff change position line.
     *
     * @@ -4,3 +4,10 @@ some file hint
     *
     * @param  string $line
     * @return bool
     */
    private function isChangePositionLine(string $line): bool
    {
        $matches = [];
        if (preg_match('#^@@ (-\d+(?:,\d+)? \+\d+(?:,\d+)?) @@ ?(.*)$#', $line, $matches)) {
            $this->currentPosition                        = $matches[1];
            $this->currentChanges[$this->currentPosition] = new Change($matches[1], $matches[2]);
            return true;
        }
        return false;
    }

    /**
     * In our case we treat every line as code line if no other line type matched before.
     *
     * @param  string $line
     * @return bool
     */
    private function isChangeCodeLine(string $line): bool
    {
        $line = $this->parseCodeLine($line);
        if ($line === null) {
            return false;
        }
        $this->currentChanges[$this->currentPosition]->addLine($line);
        return true;
    }

    /**
     * Determines the line type and cleans up the line.
     *
     * @param  string $line
     * @return \SebastianFeldmann\Git\Diff\Line|null
     */
    private function parseCodeLine(string $line): ?Line
    {
        if (strlen($line) == 0) {
            return new Line(Line::EXISTED, '');
        }

        $firstChar = $line[0];
        if (!array_key_exists($firstChar, Line::$opsMap)) {
            return null;
        }
        $cleanLine = rtrim(substr($line, 1));

        return new Line(Line::$opsMap[$firstChar], $cleanLine);
    }

    /**
     * Add all collected changes to its file.
     *
     * @return void
     */
    private function appendCollectedFileAndChanges(): void
    {
        if ($this->currentFile !== null) {
            foreach ($this->currentChanges as $change) {
                $this->currentFile->addChange($change);
            }
            $this->files[] = $this->currentFile;
        }
        $this->currentChanges = [];
    }
}

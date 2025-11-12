<?php

/**
 * This file is part of CaptainHook
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Hook\Notify;

/**
 * Class Notification
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.4.5
 */
class Notification
{
    /**
     * List of rules to check
     *
     * @var string[]
     */
    private $lines = [];

    /**
     * Max line length
     *
     * @var int
     */
    private $maxLineLength = 0;

    /**
     * Constructor
     *
     * @param  string[] $lines
     */
    public function __construct(array $lines)
    {
        $this->lines = $lines;
        foreach ($this->lines as $line) {
            $lineLength = mb_strlen($line);
            if ($lineLength > $this->maxLineLength) {
                $this->maxLineLength = $lineLength;
            }
        }
    }

    /**
     * Return line count
     *
     * @return int
     */
    public function length(): int
    {
        return count($this->lines);
    }

    /**
     * Returns the string to display
     *
     * @return string
     */
    public function banner(): string
    {
        $text   = [];
        $text[] = '<error>' . str_repeat(' ', $this->maxLineLength + 6) . '</error>';
        $text[] = '<error>  </error> ' . str_repeat(' ', $this->maxLineLength) . ' <error>  </error>';
        foreach ($this->lines as $line) {
            $text[] = $this->formatLine($line);
        }
        $text[] = '<error>  </error> ' . str_repeat(' ', $this->maxLineLength) . ' <error>  </error>';
        $text[] = '<error>' . str_repeat(' ', $this->maxLineLength + 6) . '</error>';

        return PHP_EOL . implode(PHP_EOL, $text) . PHP_EOL;
    }

    private function formatLine(string $line): string
    {
        $length = mb_strlen($line);
        $left   = '';
        $right  = '';
        if ($length < $this->maxLineLength) {
            $space = $this->maxLineLength - $length;
            $left  = str_repeat(' ', (int) floor($space / 2));
            $right = str_repeat(' ', (int) ceil($space / 2));
        }
        return '<error>  </error> ' . $left . $line . $right . ' <error>  </error>';
    }
}

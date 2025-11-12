<?php

/**
 * This file is part of CaptainHook
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Hook\Message\Rule;

use SebastianFeldmann\Git\CommitMessage;

/**
 * Class UseImperativeMood
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 0.9.0
 */
class Blacklist extends Base
{
    /**
     * Case sensitivity
     *
     * @var bool
     */
    protected $isCaseSensitive;

    /**
     * Blacklisted words
     *
     * @var array<array<string>>
     */
    protected $blacklist = [
        'subject' => [],
        'body'    => [],
    ];

    /**
     * @var \Closure
     */
    protected $stringDetection;

    /**
     * Constructor
     *
     * @param bool $caseSensitive
     */
    public function __construct(bool $caseSensitive = false)
    {
        $this->isCaseSensitive = $caseSensitive;
        $this->hint            = 'Commit message should not contain blacklisted words';
        $this->stringDetection = function (string $content, string $term): bool {
            return strpos($content, $term) !== false;
        };
    }

    /**
     * Set body blacklist
     *
     * @param  array<string> $list
     * @return void
     */
    public function setBodyBlacklist(array $list): void
    {
        $this->setBlacklist($list, 'body');
    }

    /**
     * Set subject blacklist
     *
     * @param  array<string> $list
     * @return void
     */
    public function setSubjectBlacklist(array $list): void
    {
        $this->setBlacklist($list, 'subject');
    }

    /**
     * Blacklist setter
     *
     * @param  array<string> $list
     * @param  string        $type
     * @return void
     */
    protected function setBlacklist(array $list, string $type): void
    {
        $this->blacklist[$type] = $list;
    }

    /**
     * Check if the message contains blacklisted words
     *
     * @param  \SebastianFeldmann\Git\CommitMessage $msg
     * @return bool
     */
    public function pass(CommitMessage $msg): bool
    {
        return $this->isSubjectValid($msg) && $this->isBodyValid($msg);
    }

    /**
     * Check commit message subject for blacklisted words
     *
     * @param \SebastianFeldmann\Git\CommitMessage $msg
     * @return bool
     */
    protected function isSubjectValid(CommitMessage $msg): bool
    {
        return !$this->containsBlacklistedWord($this->blacklist['subject'], $msg->getSubject());
    }

    /**
     * Check commit message body for blacklisted words
     *
     * @param \SebastianFeldmann\Git\CommitMessage $msg
     * @return bool
     */
    protected function isBodyValid(CommitMessage $msg): bool
    {
        return !$this->containsBlacklistedWord($this->blacklist['body'], $msg->getBody());
    }

    /**
     * Contains blacklisted word
     *
     * @param  array<string> $list
     * @param  string        $content
     * @return bool
     */
    protected function containsBlacklistedWord(array $list, string $content): bool
    {
        if (!$this->isCaseSensitive) {
            $content = strtolower($content);
            $list    = array_map('strtolower', $list);
        }
        foreach ($list as $term) {
            if (($this->stringDetection)($content, $term)) {
                $this->hint .= PHP_EOL . 'Invalid use of \'' . $term . '\'';
                return true;
            }
        }
        return false;
    }
}

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
 * Class LimitSubjectLength
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 0.9.0
 */
class LimitSubjectLength extends Base
{
    /**
     * Length limit
     *
     * @var int
     */
    protected $maxLength;

    /**
     * Constructor
     *
     * @param int $length
     */
    public function __construct(int $length = 50)
    {
        $this->hint      = 'Subject line should not exceed ' . $length . ' characters';
        $this->maxLength = $length;
    }

    /**
     * Check if commit message doesn't exceeed the max length
     *
     * @param  \SebastianFeldmann\Git\CommitMessage $msg
     * @return bool
     */
    public function pass(CommitMessage $msg): bool
    {
        $subjectLength = mb_strlen($msg->getSubject());
        if ($subjectLength > $this->maxLength) {
            $this->hint .= ' (' . $subjectLength . ')';
            return false;
        }
        return true;
    }
}

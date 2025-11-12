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
 * Class SeparateSubjectFromBodyWithBlankLine
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 0.9.0
 */
class SeparateSubjectFromBodyWithBlankLine extends Base
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->hint = 'Subject and body have to be separated by a blank line';
    }

    /**
     * Check if subject and body are separated by a blank line
     *
     * @param  \SebastianFeldmann\Git\CommitMessage $msg
     * @return bool
     */
    public function pass(CommitMessage $msg): bool
    {
        return $msg->getContentLineCount() < 2 || empty($msg->getContentLine(1));
    }
}

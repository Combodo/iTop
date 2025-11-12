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
 * Class MsgNotEmpty
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 0.9.0
 */
class MsgNotEmpty extends Base
{
    /**
     * SubjectStartsUpperCase constructor
     */
    public function __construct()
    {
        $this->hint = 'Commit message can not be empty';
    }

    /**
     * Check if commit message is not empty
     *
     * @param  \SebastianFeldmann\Git\CommitMessage $msg
     * @return bool
     */
    public function pass(CommitMessage $msg): bool
    {
        return !$msg->isEmpty();
    }
}

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
 * Class NoPeriodOnSubjectEnd
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 0.9.0
 */
class NoPeriodOnSubjectEnd extends Base
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->hint = 'Subject should not end with a period';
    }

    /**
     * Check if commit message doesn't end with a period
     *
     * @param  \SebastianFeldmann\Git\CommitMessage $msg
     * @return bool
     */
    public function pass(CommitMessage $msg): bool
    {
        return substr(trim($msg->getSubject()), -1) !== '.';
    }
}

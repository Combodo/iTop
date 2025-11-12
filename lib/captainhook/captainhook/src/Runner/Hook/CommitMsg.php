<?php

/**
 * This file is part of CaptainHook
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Runner\Hook;

use CaptainHook\App\Hooks;
use CaptainHook\App\Runner\Hook;
use SebastianFeldmann\Git;

/**
 * CommitMsg
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 3.1.0
 */
class CommitMsg extends Hook
{
    /**
     * Hook to execute
     *
     * @var string
     */
    protected string $hook = Hooks::COMMIT_MSG;

    /**
     * Read the commit message from file
     */
    public function beforeHook(): void
    {
        $commentChar = $this->repository->getConfigOperator()->getSettingSafely('core.commentchar', '#');
        $commitMsg   = Git\CommitMessage::createFromFile(
            $this->io->getArgument(Hooks::ARG_MESSAGE_FILE, ''),
            $commentChar
        );

        $this->repository->setCommitMsg($commitMsg);

        parent::beforeHook();
    }

    /**
     * Makes sure we do not run commit message validation for fixup commits
     *
     * @return void
     * @throws \Exception
     */
    protected function runHook(): void
    {
        $msg = $this->repository->getCommitMsg();
        if ($msg->isFixup()) {
            $this->io->write(' - no commit message validation for fixup commits: skipping all actions');
            return;
        }
        parent::runHook();
    }
}

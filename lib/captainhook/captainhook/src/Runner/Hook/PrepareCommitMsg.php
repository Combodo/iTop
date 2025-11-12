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

use CaptainHook\App\Config;
use CaptainHook\App\Hooks;
use CaptainHook\App\Runner\Hook;
use CaptainHook\App\Runner\Util;
use RuntimeException;
use SebastianFeldmann\Git;

/**
 *  Hook
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 3.1.0
 */
class PrepareCommitMsg extends Hook
{
    /**
     * Hook to execute
     *
     * @var string
     */
    protected string $hook = Hooks::PREPARE_COMMIT_MSG;

    /**
     * @var string
     */
    private string $commentChar;

    /**
     * Path to commit message file
     *
     * @var string
     */
    private string $file;

    /**
     * Commit mode, empty or [message|template|merge|squash|commit]
     *
     * @var string
     */
    private string $mode;

    /**
     * Commit hash if mode is commit during -c or --amend
     *
     * @var string
     */
    private string $hash;

    /**
     * Fetch the original hook arguments and message related config settings
     *
     * @return void
     */
    public function beforeHook(): void
    {
        $this->commentChar = $this->repository->getConfigOperator()->getSettingSafely('core.commentchar', '#');
        $this->file        = $this->io->getArgument(Hooks::ARG_MESSAGE_FILE);
        $this->mode        = $this->io->getArgument(Hooks::ARG_MODE);
        $this->hash        = $this->io->getArgument(Hooks::ARG_HASH);

        if (empty($this->file)) {
            throw new RuntimeException('commit message file argument is missing');
        }

        parent::beforeHook();
    }

    /**
     * Read the commit message from file
     *
     * @param Config\Action $action
     * @return void
     */
    public function beforeAction(Config\Action $action): void
    {
        $this->repository->setCommitMsg(Git\CommitMessage::createFromFile($this->file, $this->commentChar));
        parent::beforeAction($action);
    }

    /**
     * Write the commit message to disk so git or the next action can proceed further
     *
     * @param Config\Action $action
     * @return void
     */
    public function afterAction(Config\Action $action): void
    {
        // only write the commit message to disk if a php action was executed
        // and potentially changed the message object
        if (Util::getExecType($action->getAction()) !== 'cli') {
            file_put_contents($this->file, $this->repository->getCommitMsg()->getRawContent());
        }
        parent::afterAction($action);
    }
}

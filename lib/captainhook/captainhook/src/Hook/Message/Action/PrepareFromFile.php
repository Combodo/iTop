<?php

/**
 * This file is part of CaptainHook
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace CaptainHook\App\Hook\Message\Action;

use CaptainHook\App\Config;
use CaptainHook\App\Console\IO;
use CaptainHook\App\Exception\ActionFailed;
use CaptainHook\App\Hook\Action;
use SebastianFeldmann\Git\CommitMessage;
use SebastianFeldmann\Git\Repository;

/**
 * Class PrepareFromFile
 *
 * Example configuration:
 * {
 *   "action": "\\CaptainHook\\App\\Hook\\Message\\Action\\PrepareFromFile"
 *   "options": {
 *     "file": ".git/CH_MSG_CACHE"
 *   }
 * }
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.11.0
 */
class PrepareFromFile implements Action
{
    /**
     * Execute the configured action
     *
     * @param  \CaptainHook\App\Config           $config
     * @param  \CaptainHook\App\Console\IO       $io
     * @param  \SebastianFeldmann\Git\Repository $repository
     * @param  \CaptainHook\App\Config\Action    $action
     * @return void
     * @throws \Exception
     */
    public function execute(Config $config, IO $io, Repository $repository, Config\Action $action): void
    {
        $options   = $action->getOptions();
        $cacheFile = $repository->getRoot() . '/' . $options->get('file', '');
        if (empty($options->get('file', ''))) {
            throw new ActionFailed('PrepareFromFile requires \'file\' option');
        }

        if (!is_file($cacheFile)) {
            return;
        }

        // if there is a commit message don't do anything just delete the file
        if ($repository->getCommitMsg()->isEmpty()) {
            $msg = (string)file_get_contents($cacheFile);
            $repository->setCommitMsg(
                new CommitMessage($msg, $repository->getCommitMsg()->getCommentCharacter())
            );
        }

        if (!$options->get('keep', false)) {
            unlink($cacheFile);
        }
    }
}

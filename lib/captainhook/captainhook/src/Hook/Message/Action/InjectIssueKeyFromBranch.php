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
use CaptainHook\App\Config\Options;
use CaptainHook\App\Console\IO;
use CaptainHook\App\Exception\ActionFailed;
use CaptainHook\App\Hook\Action;
use CaptainHook\App\Hook\Constrained;
use CaptainHook\App\Hook\Restriction;
use CaptainHook\App\Hooks;
use SebastianFeldmann\Git\CommitMessage;
use SebastianFeldmann\Git\Repository;

/**
 * Class PrepareFromFile
 *
 * Example configuration:
 * {
 *   "action": "\\CaptainHook\\App\\Hook\\Message\\Action\\InjectIssueKeyFromBranch",
 *   "options": {
 *     "regex": "#([A-Z]+\\-[0-9]+)#i",
 *     "into": "body",
 *     "mode": "append",
 *     "prefix": "\nissue: ",
 *     "force": true
 *   }
 * }
 *
 * The regex option needs group $1 (...) to be the issue key
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.16.0
 */
class InjectIssueKeyFromBranch implements Action, Constrained
{
    /**
     * Mode constants
     */
    private const MODE_APPEND  = 'append';
    private const MODE_PREPEND = 'prepend';

    /**
     * Target constants
     */
    private const TARGET_SUBJECT = 'subject';
    private const TARGET_BODY    = 'body';

    /**
     * Returns a list of applicable hooks
     *
     * @return \CaptainHook\App\Hook\Restriction
     */
    public static function getRestriction(): Restriction
    {
        return Restriction::fromArray([Hooks::PREPARE_COMMIT_MSG]);
    }

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
        $options = $action->getOptions();
        $branch  = $repository->getInfoOperator()->getCurrentBranch();
        $pattern = $options->get('regex', '#([A-Z]+\-[0-9]+)#i');
        $issueID = $this->extractIssueId($branch, $pattern);

        // did we actually find an issue id?
        if (empty($issueID)) {
            if ($options->get('force', false)) {
                throw new ActionFailed('No issue key found in branch name');
            }
        }

        $msg = $repository->getCommitMsg();

        // make sure the issue key is not already in the commit message
        if (stripos($msg->getSubject() . $msg->getContent(), $issueID) !== false) {
            return;
        }

        $repository->setCommitMsg($this->createNewCommitMessage($options, $msg, $issueID));
    }

    /**
     * Extract issue id from branch name
     *
     * @param  string $branch
     * @param  string $pattern
     * @return string
     */
    private function extractIssueId(string $branch, string $pattern): string
    {
        $match = [];
        // can we actually find an issue id?
        if (!preg_match($pattern, $branch, $match)) {
            return '';
        }
        return $match[1] ?? '';
    }

    /**
     * Will create the new commit message with the injected issue key
     *
     * @param  \CaptainHook\App\Config\Options      $options
     * @param  \SebastianFeldmann\Git\CommitMessage $msg
     * @param  string                               $issueID
     * @return \SebastianFeldmann\Git\CommitMessage
     */
    private function createNewCommitMessage(Options $options, CommitMessage $msg, string $issueID): CommitMessage
    {
        // let's figure out where to put the issueID
        $target = $options->get('into', self::TARGET_BODY);
        $mode   = $options->get('mode', self::MODE_APPEND);

        // overwrite either subject or body
        $pattern          = $this->handlePrefixAndSuffix($mode, $options);
        $msgData          = [self::TARGET_SUBJECT => $msg->getSubject(), self::TARGET_BODY => $msg->getBody()];
        $msgData[$target] = $this->injectIssueId($issueID, $msgData[$target], $mode, $pattern);

        // combine all the parts to create a new commit message
        $msgText = $msgData[self::TARGET_SUBJECT] . PHP_EOL
                 . PHP_EOL
                 . $msgData[self::TARGET_BODY] . PHP_EOL
                 . $msg->getComments();

        return new CommitMessage($msgText, $msg->getCommentCharacter());
    }

    /**
     * Appends or prepends the issue id to the given message part
     *
     * @param  string $issueID
     * @param  string $msg
     * @param  string $mode
     * @param  string $pattern
     * @return string
     */
    private function injectIssueId(string $issueID, string $msg, string $mode, string $pattern): string
    {
        $issueID = preg_replace_callback(
            '/\$(\d+)/',
            function ($matches) use ($issueID) {
                return $matches[1] === '1' ? $issueID : '';
            },
            $pattern
        );

        return ltrim($mode === self::MODE_PREPEND ? $issueID . $msg : $msg . $issueID);
    }

    /**
     * Make sure the prefix and suffix options still works even if they should not be used anymore
     *
     * @param  string                          $mode
     * @param  \CaptainHook\App\Config\Options $options
     * @return string
     */
    private function handlePrefixAndSuffix(string $mode, Options $options): string
    {
        $space   = '';
        $pattern = $options->get('pattern', '');
        if (empty($pattern)) {
            $space   = ' ';
            $pattern = '$1';
        }
        // depending on the mode use a whitespace as prefix or suffix
        $prefix = $options->get('prefix', $mode == 'append' ? $space : '');
        $suffix = $options->get('suffix', $mode == 'prepend' ? $space : '');
        return $prefix . $pattern . $suffix;
    }
}

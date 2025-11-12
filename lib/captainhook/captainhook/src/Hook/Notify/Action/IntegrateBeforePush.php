<?php

/**
 * This file is part of CaptainHook
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Hook\Notify\Action;

use CaptainHook\App\Config;
use CaptainHook\App\Console\IO;
use CaptainHook\App\Exception\ActionFailed;
use CaptainHook\App\Hook\Action;
use CaptainHook\App\Hook\Constrained;
use CaptainHook\App\Hook\Restriction;
use CaptainHook\App\Git\Rev\Util as RevUtil;
use CaptainHook\App\Hooks;
use SebastianFeldmann\Git\Repository;

/**
 * Class IntegrateBeforePush
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.19.1
 */
class IntegrateBeforePush implements Action, Constrained
{
    /**
     * Returns a list of applicable hooks
     *
     * @return \CaptainHook\App\Hook\Restriction
     */
    public static function getRestriction(): Restriction
    {
        return Restriction::fromArray([Hooks::PRE_PUSH]);
    }

    /**
     * Executes the action
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
        $trigger       = $action->getOptions()->get('trigger', '[merge]');
        $branchToWatch = $action->getOptions()->get('branch', 'origin/main');
        $branchInfo    = RevUtil::extractBranchInfo($branchToWatch);

        $repository->getRemoteOperator()->fetchBranch($branchInfo['remote'], $branchInfo['branch']);

        foreach ($repository->getLogOperator()->getCommitsBetween('HEAD', $branchToWatch) as $commit) {
            $message = $commit->getSubject() . PHP_EOL . $commit->getBody();
            if (str_contains($message, $trigger)) {
                throw new ActionFailed('integrate ' . $branchInfo['branch'] . ' before you push!');
            }
        }
    }
}

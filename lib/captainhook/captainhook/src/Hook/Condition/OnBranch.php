<?php

/**
 * This file is part of CaptainHook.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace CaptainHook\App\Hook\Condition;

use CaptainHook\App\Console\IO;
use CaptainHook\App\Hook\Condition;
use SebastianFeldmann\Git\Repository;

/**
 * OnBranch condition
 *
 * @package    CaptainHook
 * @author     Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link       https://github.com/captainhook-git/captainhook
 * @since      Class available since Release 5.0.0
 * @deprecated Replaced be CaptainHook\App\Hook\Condition\Branch\On
 */
class OnBranch extends Condition\Branch\On
{
}

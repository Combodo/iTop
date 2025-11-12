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
use CaptainHook\App\Hook\Message\Rule;

/**
 * Class Base
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 0.9.0
 */
abstract class Base implements Rule
{
    /**
     * Rule hint.
     *
     * @var string
     */
    protected $hint;

    /**
     * @return string
     */
    public function getHint(): string
    {
        return $this->hint;
    }

    /**
     * @param  \SebastianFeldmann\Git\CommitMessage $msg
     * @return bool
     */
    abstract public function pass(CommitMessage $msg): bool;
}

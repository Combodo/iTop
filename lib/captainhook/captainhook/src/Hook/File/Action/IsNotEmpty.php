<?php

/**
 * This file is part of CaptainHook
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Hook\File\Action;

use SebastianFeldmann\Git\Repository;

/**
 * Class IsNotEmpty
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.4.1
 */
class IsNotEmpty extends Emptiness
{
    /**
     * Actual action name for better error messages
     *
     * @var string
     */
    protected string $actionName = 'IsNotEmpty';

    /**
     * Checks if the file is valid or not
     *
     * @param  \SebastianFeldmann\Git\Repository $repository
     * @param  string                            $file
     * @return bool
     */
    protected function isValid(Repository $repository, string $file): bool
    {
        return !$this->isEmpty($file);
    }
}

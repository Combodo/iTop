<?php

/**
 * This file is part of CaptainHook.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Git\Rev;

use CaptainHook\App\Git\Rev;

/**
 * Generic range implementation
 *
 * The simplest imaginable range implementation without any extra information.
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.15.0
 */
class Generic implements Rev
{
    /**
     * Referencing a git state
     *
     * @var string
     */
    private string $id;

    /**
     * Constructor
     *
     * @param string $id
     */
    public function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * Return the git reference
     *
     * @return string
     */
    public function id(): string
    {
        return $this->id;
    }
}

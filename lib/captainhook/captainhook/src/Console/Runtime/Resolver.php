<?php

/**
 * This file is part of captainhook.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Console\Runtime;

/**
 * Class Resolver
 *
 * @package CaptainHook\App
 */
class Resolver
{
    /**
     * PHAR flag, replaced by box during PHAR building
     *
     * @var string
     */
    private string $runtime = '@runtime@';

    /**
     * Path to the currently executed 'binary'
     *
     * @var string
     */
    private string $executable;

    /**
     * Resolver constructor.
     *
     * @param string $executable
     */
    public function __construct(string $executable = 'bin/vendor/captainhook')
    {
        $this->executable = $executable;
    }

    /**
     * Return current executed 'binary'
     *
     * @return string
     */
    public function getExecutable(): string
    {
        return $this->executable;
    }

    /**
     * Check if the current runtime is executed via PHAR
     *
     * @return bool
     */
    public function isPharRelease(): bool
    {
        return $this->runtime === 'PHAR';
    }
}

<?php

/**
 * This file is part of CaptainHook
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Config;

/**
 * Class Action
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 4.2.0
 * @internal
 */
class Condition
{
    /**
     * Condition executable
     *
     * @var string
     */
    private string $exec;

    /**
     * Condition arguments
     *
     * @var array<mixed>
     */
    private array $args;

    /**
     * Condition constructor
     *
     * @param string       $exec
     * @param array<mixed> $args
     */
    public function __construct(string $exec, array $args = [])
    {
        $this->exec = $exec;
        $this->args = $args;
    }

    /**
     * Exec getter
     *
     * @return string
     */
    public function getExec(): string
    {
        return $this->exec;
    }

    /**
     * Args getter
     *
     * @return array<mixed>
     */
    public function getArgs(): array
    {
        return $this->args;
    }

    /**
     * Return config data
     *
     * @return array<string, mixed>
     */
    public function getJsonData(): array
    {
        return [
            'exec' => $this->exec,
            'args' => $this->args,
        ];
    }
}

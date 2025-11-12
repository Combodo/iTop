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
 * Class Options
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 1.0.0
 */
class Options
{
    /**
     * Map of options
     *
     * @var array<string, mixed>
     */
    private array $options;

    /**
     * Options constructor
     *
     * @param array<string, mixed> $options
     */
    public function __construct(array $options)
    {
        $this->options = $options;
    }

    /**
     * Return a option value
     *
     * @template ProvidedDefault
     * @param  string          $name
     * @param  ProvidedDefault $default
     * @return ProvidedDefault|mixed
     */
    public function get(string $name, $default = null)
    {
        return $this->options[$name] ?? $default;
    }

    /**
     * Return all options
     *
     * @return array<string, mixed>
     */
    public function getAll(): array
    {
        return $this->options;
    }
}

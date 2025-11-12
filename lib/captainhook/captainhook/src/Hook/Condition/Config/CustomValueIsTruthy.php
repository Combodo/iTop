<?php

/**
 * This file is part of CaptainHook
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Hook\Condition\Config;

use CaptainHook\App\Console\IO;
use CaptainHook\App\Hook\Condition;
use CaptainHook\App\Hooks;
use SebastianFeldmann\Git\Repository;

/**
 * Class CustomValueIsTruthy
 *
 * Example configuration:
 *
 * "action": "some-action"
 * "conditions": [
 *   {"exec": "\\CaptainHook\\App\\Hook\\Condition\\Config\\CustomValueIsTruthy",
 *    "args": [
 *      "NAME_OF_CUSTOM_VALUE"
 *    ]}
 * ]
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.17.2
 */
class CustomValueIsTruthy extends Condition\Config
{
    /**
     * Custom config value to check
     *
     * @var string
     */
    private string $value;

    /**
     * CustomValueIsTruthy constructor
     *
     * @param string $value
     */
    public function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * Evaluates the condition
     *
     * @param  \CaptainHook\App\Console\IO       $io
     * @param  \SebastianFeldmann\Git\Repository $repository
     * @return bool
     */
    public function isTrue(IO $io, Repository $repository): bool
    {
        return $this->checkCustomValue($this->value, false);
    }
}

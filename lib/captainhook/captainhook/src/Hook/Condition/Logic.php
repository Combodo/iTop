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

use CaptainHook\App\Hook\Condition;

/**
 * Logical condition base class
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @author  Andreas Heigl <andreas@heigl.org>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.7.0
 */
abstract class Logic implements Condition
{
    /**
     * List of conditions to logically connect
     *
     * @var \CaptainHook\App\Hook\Condition[]
     */
    protected array $conditions = [];

    final private function __construct(Condition ...$conditions)
    {
        $this->conditions = $conditions;
    }

    /**
     * Create a logic condition
     *
     * @param  array<Condition> $conditions
     * @return \CaptainHook\App\Hook\Condition
     */
    public static function fromConditionsArray(array $conditions): Condition
    {
        $realConditions = [];
        foreach ($conditions as $condition) {
            if (! $condition instanceof Condition) {
                continue;
            }
            $realConditions[] = $condition;
        }

        return new static(...$realConditions);
    }
}

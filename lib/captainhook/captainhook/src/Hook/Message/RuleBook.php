<?php

/**
 * This file is part of CaptainHook
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Hook\Message;

use SebastianFeldmann\Git\CommitMessage;

/**
 * Class RuleBook
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 0.9.0
 */
class RuleBook
{
    /**
     * List of rules to check
     *
     * @var \CaptainHook\App\Hook\Message\Rule[]
     */
    private $rules = [];

    /**
     * Set rules to check
     *
     * @param  \CaptainHook\App\Hook\Message\Rule[] $rules
     * @return \CaptainHook\App\Hook\Message\RuleBook
     */
    public function setRules(array $rules): RuleBook
    {
        $this->rules = $rules;
        return $this;
    }

    /**
     * Add a rule to the list
     *
     * @param  \CaptainHook\App\Hook\Message\Rule $rule
     * @return \CaptainHook\App\Hook\Message\RuleBook
     */
    public function addRule(Rule $rule): RuleBook
    {
        $this->rules[] = $rule;
        return $this;
    }

    /**
     * Validates all rules
     *
     * Returns a list of problems found checking the commit message.
     * If the list is empty the message is valid.
     *
     * @param  \SebastianFeldmann\Git\CommitMessage $msg
     * @return array<string>
     */
    public function validate(CommitMessage $msg): array
    {
        $problems = [];
        foreach ($this->rules as $rule) {
            if (!$rule->pass($msg)) {
                $problems[] = $rule->getHint();
            }
        }
        return $problems;
    }
}

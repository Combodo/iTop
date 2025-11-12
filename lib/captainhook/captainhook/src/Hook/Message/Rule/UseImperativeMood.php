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

/**
 * Class UseImperativeMood
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 0.9.0
 */
class UseImperativeMood extends Blacklist
{
    /**
     * Constructor
     *
     * @param bool $checkOnlyBeginning
     */
    public function __construct(bool $checkOnlyBeginning = false)
    {
        parent::__construct();

        $this->hint = 'A commit message subject should always complete the following sentence.'  . PHP_EOL .
                      'This commit will [YOUR COMMIT MESSAGE].';

        $this->setSubjectBlacklist(
            [
                'added',
                'changed',
                'created',
                'deleted',
                'fixed',
                'reformatted',
                'removed',
                'updated',
                'uploaded'
            ]
        );

        if ($checkOnlyBeginning) {
            // overwrite the detection logic to only check the beginning og the string
            $this->stringDetection = function (string $content, string $term): bool {
                return strpos($content, $term) === 0;
            };
        }
    }
}

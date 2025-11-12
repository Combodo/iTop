<?php

/**
 * This file is part of CaptainHook.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Runner\Action\Cli\Command\Placeholder;

/**
 * Class StdIn
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.23.5
 */
class StdIn extends Foundation
{
    /**
     * Return the original hook stdIn (shell escaped)
     *
     * Returns at least ''
     *
     * @param  array<string, mixed> $options
     * @return string
     */
    public function replacement(array $options): string
    {
        return escapeshellarg(implode(PHP_EOL, $this->io->getStandardInput()));
    }
}

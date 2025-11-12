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

use CaptainHook\App\Hook\FileList;

/**
 * Class UpdatedFiles
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 5.0.0
 */
class StagedFiles extends Foundation
{
    /**
     * @param  array<string, string> $options
     * @return string
     */
    public function replacement(array $options): string
    {
        $filter = isset($options['diff-filter']) ? str_split($options['diff-filter']) : ['A', 'C', 'M', 'R'];
        $files  = isset($options['of-type'])
                ? $this->repository->getIndexOperator()->getStagedFilesOfType($options['of-type'], $filter)
                : $this->repository->getIndexOperator()->getStagedFiles($filter);

        $files = FileList::filterByDirectory($files, $options);
        $files = FileList::replaceInAll($files, $options);

        return implode(($options['separated-by'] ?? ' '), $files);
    }
}

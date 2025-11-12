<?php

/**
 * This file is part of CaptainHook
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CaptainHook\App\Hook\PHP\CoverageResolver;

use RuntimeException;
use CaptainHook\App\Hook\PHP\CoverageResolver;
use SebastianFeldmann\Cli\Processor\ProcOpen as Processor;

/**
 * Class PHPUnit
 *
 * @package CaptainHook
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/captainhook
 * @since   Class available since Release 1.2.0
 */
class PHPUnit implements CoverageResolver
{
    /**
     * Path to phpunit
     *
     * @var string
     */
    private $phpUnit;

    /**
     * PHPUnit constructor.
     *
     * @param string $pathToPHPUnit
     */
    public function __construct(string $pathToPHPUnit)
    {
        $this->phpUnit = $pathToPHPUnit;
    }

    /**
     * Run PHPUnit to calculate code coverage.
     * Shamelessly ripped from bruli/php-git-hooks.
     *
     * @author Pablo Braulio
     * @return int
     */
    public function getCoverage(): int
    {
        $processor = new Processor();
        $result    = $processor->run($this->phpUnit . ' --coverage-text|grep Classes|cut -d " " -f 4|cut -d "%" -f 1');
        $output    = $result->getStdOut();
        if (!$result->isSuccessful() || empty($output)) {
            throw new RuntimeException('Error while executing PHPUnit: ' . $result->getStdErr());
        }
        return (int) ceil((float) $output);
    }
}

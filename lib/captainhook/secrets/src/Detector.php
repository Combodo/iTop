<?php

/**
 * This file is part of CaptainHook Secrets.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace CaptainHook\Secrets;

use CaptainHook\Secrets\Regex\Supplier;
use RuntimeException;

/**
 * Secret Detector
 *
 * @package CaptainHook-Secrets
 * @author  Sebastian Feldmann <sf@sebastian-feldmann.info>
 * @link    https://github.com/captainhook-git/secrets
 * @since   Class available since Release 0.9.1
 */
class Detector
{
    /**
     * @var array<string>
     */
    private array $patterns = [];

    /**
     * Creates a new Detector
     *
     * @return \CaptainHook\Secrets\Detector
     */
    public static function create(): self
    {
        return new self();
    }

    /**
     * Add a list of wanted suppliers
     * This takes care of creating and validating the configured suppliers.
     *
     * @param array<string> $config List of class names
     * @return $this
     */
    public function useSupplierConfig(array $config): self
    {
        $suppliers = [];
        foreach ($config as $class) {
            if (!class_exists($class)) {
                throw new RuntimeException('class not found:' . $class);
            }
            $supplier = new $class();
            if (!$supplier instanceof Supplier) {
                throw new RuntimeException('class is not a supplier:' . $class);
            }
            $suppliers[] = $supplier;
        }
        return $this->useSuppliers(...$suppliers);
    }

    /**
     * Adds the regular expressions of a Supplier to the list
     *
     * @param \CaptainHook\Secrets\Regex\Supplier ...$suppliers The RegexSupplier to add
     * @return $this
     */
    public function useSuppliers(Supplier ...$suppliers): self
    {
        foreach ($suppliers as $supplier) {
            $this->useRegex(...$supplier->patterns());
        }
        return $this;
    }

    /**
     * Add regular expressions to the detector
     *
     * @param  string ...$regularExpressions Regular expression to detect secret
     * @return $this
     */
    public function useRegex(string ...$regularExpressions): self
    {
        foreach ($regularExpressions as $regularExpression) {
            $this->patterns[] = $regularExpression;
        }

        return $this;
    }

    /**
     * Detect secrets in string
     *
     * @param string $content
     * @return \CaptainHook\Secrets\Result
     */
    public function detectIn(string $content): Result
    {
        $allMatches = [];
        foreach ($this->patterns as $regex) {
            $matches = [];
            if (preg_match($regex, $content, $matches)) {
                $allMatches[] = $matches[0];
            }
        }
        return new Result($allMatches);
    }
}

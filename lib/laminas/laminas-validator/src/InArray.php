<?php

namespace Laminas\Validator;

use RecursiveArrayIterator;
use RecursiveIteratorIterator;

use function in_array;
use function is_bool;
use function is_float;
use function is_int;
use function is_string;

class InArray extends AbstractValidator
{
    public const NOT_IN_ARRAY = 'notInArray';

    // Type of Strict check
    /**
     * standard in_array strict checking value and type
     */
    public const COMPARE_STRICT = 1;

    /**
     * Non strict check but prevents "asdf" == 0 returning TRUE causing false/positive.
     * This is the most secure option for non-strict checks and replaces strict = false
     * This will only be effective when the input is a string
     */
    public const COMPARE_NOT_STRICT_AND_PREVENT_STR_TO_INT_VULNERABILITY = 0;

    /**
     * Standard non-strict check where "asdf" == 0 returns TRUE
     * This will be wanted when comparing "0" against int 0
     */
    public const COMPARE_NOT_STRICT = -1;

    /** @var array<string, string> */
    protected $messageTemplates = [
        self::NOT_IN_ARRAY => 'The input was not found in the haystack',
    ];

    /**
     * Haystack of possible values
     *
     * @var array
     */
    protected $haystack;

    /**
     * Type of strict check to be used. Due to "foo" == 0 === TRUE with in_array when strict = false,
     * an option has been added to prevent this. When $strict = 0/false, the most
     * secure non-strict check is implemented. if $strict = -1, the default in_array non-strict
     * behaviour is used
     *
     * @var int
     */
    protected $strict = self::COMPARE_NOT_STRICT_AND_PREVENT_STR_TO_INT_VULNERABILITY;

    /**
     * Whether a recursive search should be done
     *
     * @var bool
     */
    protected $recursive = false;

    /**
     * Returns the haystack option
     *
     * @return mixed
     * @throws Exception\RuntimeException If haystack option is not set.
     */
    public function getHaystack()
    {
        if ($this->haystack === null) {
            throw new Exception\RuntimeException('haystack option is mandatory');
        }
        return $this->haystack;
    }

    /**
     * Sets the haystack option
     *
     * @param  mixed $haystack
     * @return $this Provides a fluent interface
     */
    public function setHaystack(array $haystack)
    {
        $this->haystack = $haystack;
        return $this;
    }

    /**
     * Returns the strict option
     *
     * @return bool|int
     */
    public function getStrict()
    {
        // To keep BC with new strict modes
        if (
            $this->strict === self::COMPARE_NOT_STRICT_AND_PREVENT_STR_TO_INT_VULNERABILITY
            || $this->strict === self::COMPARE_STRICT
        ) {
            return (bool) $this->strict;
        }
        return $this->strict;
    }

    /**
     * Sets the strict option mode
     * InArray::COMPARE_STRICT
     * InArray::COMPARE_NOT_STRICT_AND_PREVENT_STR_TO_INT_VULNERABILITY
     * InArray::COMPARE_NOT_STRICT
     *
     * @param  int|bool $strict
     * @return $this Provides a fluent interface
     * @throws Exception\InvalidArgumentException
     */
    public function setStrict($strict)
    {
        if (is_bool($strict)) {
            $strict = $strict ? self::COMPARE_STRICT : self::COMPARE_NOT_STRICT_AND_PREVENT_STR_TO_INT_VULNERABILITY;
        }

        $checkTypes = [
            self::COMPARE_NOT_STRICT_AND_PREVENT_STR_TO_INT_VULNERABILITY, // 0
            self::COMPARE_STRICT, // 1
            self::COMPARE_NOT_STRICT, // -1
        ];

        // validate strict value
        if (! in_array($strict, $checkTypes)) {
            throw new Exception\InvalidArgumentException('Strict option must be one of the COMPARE_ constants');
        }

        $this->strict = $strict;
        return $this;
    }

    /**
     * Returns the recursive option
     *
     * @return bool
     */
    public function getRecursive()
    {
        return $this->recursive;
    }

    /**
     * Sets the recursive option
     *
     * @param  bool $recursive
     * @return $this Provides a fluent interface
     */
    public function setRecursive($recursive)
    {
        $this->recursive = (bool) $recursive;
        return $this;
    }

    /**
     * Returns true if and only if $value is contained in the haystack option. If the strict
     * option is true, then the type of $value is also checked.
     *
     * @param mixed $value
     * See {@link http://php.net/manual/function.in-array.php#104501}
     * @return bool
     */
    public function isValid($value)
    {
        // we create a copy of the haystack in case we need to modify it
        $haystack = $this->getHaystack();

        // if the input is a string or float, and vulnerability protection is on
        // we type cast the input to a string
        if (
            self::COMPARE_NOT_STRICT_AND_PREVENT_STR_TO_INT_VULNERABILITY === $this->strict
            && (is_int($value) || is_float($value))
        ) {
            $value = (string) $value;
        }

        $this->setValue($value);

        if ($this->getRecursive()) {
            $iterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($haystack));
            foreach ($iterator as $element) {
                if (self::COMPARE_STRICT === $this->strict) {
                    if ($element === $value) {
                        return true;
                    }

                    continue;
                }

                // add protection to prevent string to int vuln's
                $el = $element;
                if (
                    self::COMPARE_NOT_STRICT_AND_PREVENT_STR_TO_INT_VULNERABILITY === $this->strict
                    && is_string($value) && (is_int($el) || is_float($el))
                ) {
                    $el = (string) $el;
                }

                // phpcs:ignore SlevomatCodingStandard.Operators.DisallowEqualOperators.DisallowedEqualOperator
                if ($el == $value) {
                    return true;
                }
            }

            $this->error(self::NOT_IN_ARRAY);
            return false;
        }

        /**
         * If the check is not strict, then, to prevent "asdf" being converted to 0
         * and returning a false positive if 0 is in haystack, we type cast
         * the haystack to strings. To prevent "56asdf" == 56 === TRUE we also
         * type cast values like 56 to strings as well.
         *
         * This occurs only if the input is a string and a haystack member is an int
         */
        if (
            self::COMPARE_NOT_STRICT_AND_PREVENT_STR_TO_INT_VULNERABILITY === $this->strict
            && is_string($value)
        ) {
            foreach ($haystack as &$h) {
                if (is_int($h) || is_float($h)) {
                    $h = (string) $h;
                }
            }

            if (in_array($value, $haystack, (bool) $this->strict)) {
                return true;
            }

            $this->error(self::NOT_IN_ARRAY);
            return false;
        }

        if (in_array($value, $haystack, self::COMPARE_STRICT === $this->strict)) {
            return true;
        }

        if (self::COMPARE_NOT_STRICT === $this->strict) {
            return true;
        }

        $this->error(self::NOT_IN_ARRAY);
        return false;
    }
}

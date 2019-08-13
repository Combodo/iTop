<?php

namespace Pelago\Tests\Support\Traits;

/**
 * Provides assertion methods for use with CSS content where whitespace may vary.
 *
 * @author Jake Hotson <jake.github@qzdesign.co.uk>
 */
trait AssertCss
{
    /**
     * Processing of @media rules may involve removal of some unnecessary whitespace from the CSS placed in the <style>
     * element added to the docuemnt, due to the way that certain parts are `trim`med.  Notably, whitespace either side
     * of "{", "}" and "," or at the beginning of the CSS may be removed.
     *
     * This method helps takes care of that, by converting a search needle for an exact match into a regular expression
     * that allows for such whitespace removal, so that the tests themselves do not need to be written less humanly
     * readable and can use inputs containing extra whitespace.
     *
     * @param string $needle Needle that would be used with `assertContains` or `assertNotContains`.
     *
     * @return string Needle to use with `assertRegExp` or `assertNotRegExp` instead.
     */
    private static function getCssNeedleRegExp($needle)
    {
        $needleMatcher = \preg_replace_callback(
            '/\\s*+([{},])\\s*+|(^\\s++)|(>)\\s*+|(?:(?!\\s*+[{},]|^\\s)[^>])++/',
            function (array $matches) {
                if (isset($matches[1]) && $matches[1] !== '') {
                    // matched possibly some whitespace, followed by "{", "}" or ",", then possibly more whitespace
                    return '\\s*+' . \preg_quote($matches[1], '/') . '\\s*+';
                }
                if (isset($matches[2]) && $matches[2] !== '') {
                    // matched whitespace at start
                    return '\\s*+';
                }
                if (isset($matches[3]) && $matches[3] !== '') {
                    // matched ">" (e.g. end of <style> tag) followed by possibly some whitespace
                    return \preg_quote($matches[3], '/') . '\\s*+';
                }
                // matched any other sequence which could not overlap with the above
                return \preg_quote($matches[0], '/');
            },
            $needle
        );
        return '/' . $needleMatcher . '/';
    }

    /**
     * Like `assertContains` but allows for removal of some unnecessary whitespace from the CSS.
     *
     * @param string $needle
     * @param string $haystack
     */
    private static function assertContainsCss($needle, $haystack)
    {
        static::assertRegExp(
            static::getCssNeedleRegExp($needle),
            $haystack,
            'Plain text needle: "' . $needle . '"'
        );
    }

    /**
     * Like `assertNotContains` and also enforces the assertion with removal of some unnecessary whitespace from the
     * CSS.
     *
     * @param string $needle
     * @param string $haystack
     */
    private static function assertNotContainsCss($needle, $haystack)
    {
        static::assertNotRegExp(
            static::getCssNeedleRegExp($needle),
            $haystack,
            'Plain text needle: "' . $needle . '"'
        );
    }

    /**
     * Asserts that a string of CSS occurs exactly a certain number of times in the result, allowing for removal of some
     * unnecessary whitespace.
     *
     * @param int $expectedCount
     * @param string $needle
     * @param string $haystack
     */
    private static function assertContainsCssCount(
        $expectedCount,
        $needle,
        $haystack
    ) {
        static::assertSame(
            $expectedCount,
            \preg_match_all(static::getCssNeedleRegExp($needle), $haystack),
            'Plain text needle: "' . $needle . "\"\nHaystack: \"" . $haystack . '"'
        );
    }
}

<?php

namespace Pelago\Tests\Unit\Emogrifier\HtmlProcessor;

use Pelago\Emogrifier\HtmlProcessor\AbstractHtmlProcessor;
use Pelago\Emogrifier\HtmlProcessor\HtmlNormalizer;

/**
 * Test case.
 *
 * @author Oliver Klee <github@oliverklee.de>
 */
class HtmlNormalizerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function classIsAbstractHtmlProcessor()
    {
        static::assertInstanceOf(AbstractHtmlProcessor::class, new HtmlNormalizer('<html></html>'));
    }
}

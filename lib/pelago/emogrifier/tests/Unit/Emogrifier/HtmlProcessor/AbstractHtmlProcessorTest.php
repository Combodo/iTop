<?php

namespace Pelago\Tests\Unit\Emogrifier\HtmlProcessor;

use Pelago\Emogrifier\HtmlProcessor\AbstractHtmlProcessor;
use Pelago\Tests\Unit\Emogrifier\HtmlProcessor\Fixtures\TestingHtmlProcessor;

/**
 * Test case.
 *
 * @author Oliver Klee <github@oliverklee.de>
 */
class AbstractHtmlProcessorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function fixtureIsAbstractHtmlProcessor()
    {
        static::assertInstanceOf(AbstractHtmlProcessor::class, new TestingHtmlProcessor('<html></html>'));
    }

    /**
     * @test
     */
    public function reformatsHtml()
    {
        $rawHtml = '<!DOCTYPE HTML>' .
            '<html>' .
            '<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>' .
            '<body></body>' .
            '</html>';
        $formattedHtml = "<!DOCTYPE HTML>\n" .
            "<html>\n" .
            '<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>' . "\n" .
            "<body></body>\n" .
            "</html>\n";

        $subject = new TestingHtmlProcessor($rawHtml);

        static::assertSame($formattedHtml, $subject->render());
    }

    /**
     * @return array[]
     */
    public function nonHtmlDataProvider()
    {
        return [
            'empty string' => [''],
            'null' => [null],
            'integer' => [2],
            'float' => [3.14159],
            'object' => [new \stdClass()],
        ];
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     *
     * @param mixed $html
     *
     * @dataProvider nonHtmlDataProvider
     */
    public function constructorWithNoHtmlDataThrowsException($html)
    {
        new TestingHtmlProcessor($html);
    }

    /**
     * @return string[][]
     */
    public function invalidHtmlDataProvider()
    {
        return [
            'broken nesting gets nested' => ['<b><i></b></i>', '<b><i></i></b>'],
            'partial opening tag gets closed' => ['<b', '<b></b>'],
            'only opening tag gets closed' => ['<b>', '<b></b>'],
            'only closing tag gets removed' => ['foo</b> bar', 'foo bar'],
        ];
    }

    /**
     * @test
     *
     * @param string $input
     * @param string $expectedHtml
     *
     * @dataProvider invalidHtmlDataProvider
     */
    public function renderRepairsBrokenHtml($input, $expectedHtml)
    {
        $subject = new TestingHtmlProcessor($input);
        $result = $subject->render();

        static::assertContains($expectedHtml, $result);
    }

    /**
     * @return string[][]
     */
    public function contentWithoutHtmlTagDataProvider()
    {
        return [
            'doctype only' => ['<!DOCTYPE html>'],
            'body content only' => ['<p>Hello</p>'],
            'HEAD element' => ['<head></head>'],
            'BODY element' => ['<body></body>'],
            'HEAD AND BODY element' => ['<head></head><body></body>'],
        ];
    }

    /**
     * @test
     *
     * @param string $html
     *
     * @dataProvider contentWithoutHtmlTagDataProvider
     */
    public function addsMissingHtmlTag($html)
    {
        $subject = new TestingHtmlProcessor($html);

        $result = $subject->render();

        static::assertContains('<html>', $result);
    }

    /**
     * @return string[][]
     */
    public function contentWithoutHeadTagDataProvider()
    {
        return [
            'doctype only' => ['<!DOCTYPE html>'],
            'body content only' => ['<p>Hello</p>'],
            'BODY element' => ['<body></body>'],
        ];
    }

    /**
     * @test
     *
     * @param string $html
     *
     * @dataProvider contentWithoutHeadTagDataProvider
     */
    public function addsMissingHeadTag($html)
    {
        $subject = new TestingHtmlProcessor($html);

        $result = $subject->render();

        static::assertContains('<head>', $result);
    }

    /**
     * @return string[][]
     */
    public function contentWithoutBodyTagDataProvider()
    {
        return [
            'doctype only' => ['<!DOCTYPE html>'],
            'HEAD element' => ['<head></head>'],
            'body content only' => ['<p>Hello</p>'],
        ];
    }

    /**
     * @test
     *
     * @param string $html
     *
     * @dataProvider contentWithoutBodyTagDataProvider
     */
    public function addsMissingBodyTag($html)
    {
        $subject = new TestingHtmlProcessor($html);

        $result = $subject->render();

        static::assertContains('<body>', $result);
    }

    /**
     * @test
     */
    public function putsMissingBodyElementAroundBodyContent()
    {
        $subject = new TestingHtmlProcessor('<p>Hello</p>');

        $result = $subject->render();

        static::assertContains('<body><p>Hello</p></body>', $result);
    }

    /**
     * @return string[][]
     */
    public function specialCharactersDataProvider()
    {
        return [
            'template markers with dollar signs & square brackets' => ['$[USER:NAME]$'],
            'UTF-8 umlauts' => ['Küss die Hand, schöne Frau.'],
            'HTML entities' => ['a &amp; b &gt; c'],
        ];
    }

    /**
     * @test
     *
     * @param string $codeNotToBeChanged
     *
     * @dataProvider specialCharactersDataProvider
     */
    public function keepsSpecialCharacters($codeNotToBeChanged)
    {
        $html = '<html><p>' . $codeNotToBeChanged . '</p></html>';
        $subject = new TestingHtmlProcessor($html);

        $result = $subject->render();

        static::assertContains($codeNotToBeChanged, $result);
    }

    /**
     * @test
     */
    public function addMissingHtml5DocumentType()
    {
        $subject = new TestingHtmlProcessor('<html></html>');

        $result = $subject->render();

        static::assertContains('<!DOCTYPE html>', $result);
    }

    /**
     * @return string[][]
     */
    public function documentTypeDataProvider()
    {
        return [
            'HTML5' => ['<!DOCTYPE html>'],
            'XHTML 1.0 strict' => [
                '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" ' .
                '"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">',
            ],
            'XHTML 1.0 transitional' => [
                '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" ' .
                '"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">',
            ],
            'HTML 4 transitional' => [
                '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" ' .
                '"http://www.w3.org/TR/REC-html40/loose.dtd">',
            ],
        ];
    }

    /**
     * @test
     *
     * @param string $documentType
     *
     * @dataProvider documentTypeDataProvider
     */
    public function keepsExistingDocumentType($documentType)
    {
        $html = $documentType . '<html></html>';
        $subject = new TestingHtmlProcessor($html);

        $result = $subject->render();

        static::assertContains($documentType, $result);
    }

    /**
     * @test
     */
    public function addsMissingContentTypeMetaTag()
    {
        $subject = new TestingHtmlProcessor('<p>Hello</p>');

        $result = $subject->render();

        static::assertContains('<meta http-equiv="Content-Type" content="text/html; charset=utf-8">', $result);
    }

    /**
     * @test
     */
    public function notAddsSecondContentTypeMetaTag()
    {
        $html = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>';
        $subject = new TestingHtmlProcessor($html);

        $result = $subject->render();

        $numberOfContentTypeMetaTags = \substr_count($result, 'Content-Type');
        static::assertSame(1, $numberOfContentTypeMetaTags);
    }

    /**
     * @test
     *
     * @param string $documentType
     *
     * @dataProvider documentTypeDataProvider
     */
    public function convertsXmlSelfClosingTagsToNonXmlSelfClosingTag($documentType)
    {
        $subject = new TestingHtmlProcessor($documentType . '<html><body><br/></body></html>');

        $result = $subject->render();

        static::assertContains('<body><br></body>', $result);
    }

    /**
     * @test
     *
     * @param string $documentType
     *
     * @dataProvider documentTypeDataProvider
     */
    public function keepsNonXmlSelfClosingTags($documentType)
    {
        $subject = new TestingHtmlProcessor($documentType . '<html><body><br></body></html>');

        $result = $subject->render();

        static::assertContains('<body><br></body>', $result);
    }

    /**
     * @test
     */
    public function renderBodyContentForEmptyBodyReturnsEmptyString()
    {
        $subject = new TestingHtmlProcessor('<html><body></body></html>');

        $result = $subject->renderBodyContent();

        static::assertSame('', $result);
    }

    /**
     * @test
     */
    public function renderBodyContentReturnsBodyContent()
    {
        $bodyContent = '<p>Hello world</p>';
        $subject = new TestingHtmlProcessor('<html><body>' . $bodyContent . '</body></html>');

        $result = $subject->renderBodyContent();

        static::assertSame($bodyContent, $result);
    }

    /**
     * @test
     */
    public function getDomDocumentReturnsDomDocument()
    {
        $subject = new TestingHtmlProcessor('<html></html>');

        static::assertInstanceOf(\DOMDocument::class, $subject->getDomDocument());
    }

    /**
     * @test
     */
    public function getDomDocumentWithNormalizedHtmlRepresentsTheGivenHtml()
    {
        $html = "<!DOCTYPE html>\n<html>\n<head>" .
            '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">' .
            "</head>\n<body>\n<br>\n</body>\n</html>\n";
        $subject = new TestingHtmlProcessor($html);

        $domDocument = $subject->getDomDocument();

        self::assertSame($html, $domDocument->saveHTML());
    }
}

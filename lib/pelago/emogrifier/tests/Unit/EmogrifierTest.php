<?php

namespace Pelago\Tests\Unit;

use Pelago\Emogrifier;
use Pelago\Tests\Support\Traits\AssertCss;

/**
 * Test case.
 *
 * @author Oliver Klee <github@oliverklee.de>
 * @author Zoli Szabó <zoli.szabo+github@gmail.com>
 */
class EmogrifierTest extends \PHPUnit_Framework_TestCase
{
    use AssertCss;

    /**
     * @var string Common HTML markup with a variety of elements and attributes for testing with
     */
    const COMMON_TEST_HTML = '
        <html>
            <body>
                <p class="p-1"><span>some text</span></p>
                <p class="p-2"><span title="bonjour">some</span> text</p>
                <p class="p-3"><span title="buenas dias">some</span> more text</p>
                <p class="p-4" id="p4"><span title="avez-vous">some</span> more <span id="text">text</span></p>
                <p class="p-5 additional-class"><span title="buenas dias bom dia">some</span> more text</p>
                <p class="p-6"><span title="title: subtitle; author">some</span> more text</p>
            </body>
        </html>
    ';

    /**
     * @var string
     */
    private $html5DocumentType = '<!DOCTYPE html>';

    /**
     * @var Emogrifier
     */
    private $subject = null;

    /**
     * Sets up the test case.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->subject = new Emogrifier();
        $this->subject->setDebug(true);
    }

    /**
     * @test
     */
    public function getDomDocumentReturnsDomDocument()
    {
        $subject = new Emogrifier('<html></html>');

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
        $subject = new Emogrifier($html);

        $domDocument = $subject->getDomDocument();

        self::assertSame($html, $domDocument->saveHTML());
    }

    /**
     * @test
     *
     * @expectedException \BadMethodCallException
     */
    public function emogrifyForNoDataSetThrowsException()
    {
        $this->subject->emogrify();
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
     * @dataProvider nonHtmlDataProvider
     */
    public function setHtmlNoHtmlDataThrowsException($html)
    {
        $this->subject->setHtml($html);
    }

    /**
     * @test
     *
     * @expectedException \BadMethodCallException
     */
    public function emogrifyBodyContentForNoDataSetThrowsException()
    {
        $this->subject->emogrifyBodyContent();
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
    public function emogrifyAddsMissingHtmlTag($html)
    {
        $this->subject->setHtml($html);

        $result = $this->subject->emogrify();

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
    public function emogrifyAddsMissingHeadTag($html)
    {
        $this->subject->setHtml($html);

        $result = $this->subject->emogrify();

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
    public function emogrifyAddsMissingBodyTag($html)
    {
        $this->subject->setHtml($html);

        $result = $this->subject->emogrify();

        static::assertContains('<body>', $result);
    }

    /**
     * @test
     */
    public function emogrifyPutsMissingBodyElementAroundBodyContent()
    {
        $this->subject->setHtml('<p>Hello</p>');

        $result = $this->subject->emogrify();

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
    public function emogrifyKeepsSpecialCharacters($codeNotToBeChanged)
    {
        $html = '<html><p>' . $codeNotToBeChanged . '</p></html>';
        $this->subject->setHtml($html);

        $result = $this->subject->emogrify();

        static::assertContains($codeNotToBeChanged, $result);
    }

    /**
     * @test
     *
     * @param string $codeNotToBeChanged
     *
     * @dataProvider specialCharactersDataProvider
     */
    public function emogrifyBodyContentKeepsSpecialCharacters($codeNotToBeChanged)
    {
        $html = '<html><p>' . $codeNotToBeChanged . '</p></html>';
        $this->subject->setHtml($html);

        $result = $this->subject->emogrifyBodyContent();

        static::assertContains($codeNotToBeChanged, $result);
    }

    /**
     * @return string[][]
     */
    public function documentTypeDataProvider()
    {
        return [
            'HTML5' => ['<!DOCTYPE html>'],
            'XHTML 1 strict' => [
                '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" ' .
                '"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">',
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
    public function emogrifyForHtmlWithDocumentTypeKeepsDocumentType($documentType)
    {
        $html = $documentType . '<html></html>';
        $this->subject->setHtml($html);

        $result = $this->subject->emogrify();

        static::assertContains($documentType, $result);
    }

    /**
     * @test
     */
    public function emogrifyAddsMissingContentTypeMetaTag()
    {
        $this->subject->setHtml('<p>Hello</p>');

        $result = $this->subject->emogrify();

        static::assertContains('<meta http-equiv="Content-Type" content="text/html; charset=utf-8">', $result);
    }

    /**
     * @test
     */
    public function emogrifyNotAddsSecondContentTypeMetaTag()
    {
        $html = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>';
        $this->subject->setHtml($html);

        $result = $this->subject->emogrify();

        $numberOfContentTypeMetaTags = \substr_count($result, 'Content-Type');
        static::assertSame(1, $numberOfContentTypeMetaTags);
    }

    /**
     * @test
     */
    public function emogrifyByDefaultRemovesWbrTag()
    {
        $html = '<html>foo<wbr/>bar</html>';
        $this->subject->setHtml($html);

        $result = $this->subject->emogrify();

        static::assertNotContains('<wbr', $result);
    }

    /**
     * @test
     */
    public function addUnprocessableTagRemovesEmptyTag()
    {
        $this->subject->setHtml('<html><p></p></html>');

        $this->subject->addUnprocessableHtmlTag('p');
        $result = $this->subject->emogrify();

        static::assertNotContains('<p>', $result);
    }

    /**
     * @test
     */
    public function addUnprocessableTagNotRemovesNonEmptyTag()
    {
        $this->subject->setHtml('<html><p>foobar</p></html>');

        $this->subject->addUnprocessableHtmlTag('p');
        $result = $this->subject->emogrify();

        static::assertContains('<p>', $result);
    }

    /**
     * @test
     */
    public function removeUnprocessableHtmlTagKeepsTagAgainAgain()
    {
        $this->subject->setHtml('<html><p></p></html>');

        $this->subject->addUnprocessableHtmlTag('p');
        $this->subject->removeUnprocessableHtmlTag('p');
        $result = $this->subject->emogrify();

        static::assertContains('<p>', $result);
    }

    /**
     * @return string[][]
     */
    public function matchedCssDataProvider()
    {
        // The sprintf placeholders %1$s and %2$s will automatically be replaced with CSS declarations
        // like 'color: red;' or 'text-align: left;'.
        return [
            'two declarations from one rule can apply to the same element' => [
                'html { %1$s %2$s }',
                '<html style="%1$s %2$s">',
            ],
            'two identical matchers with different rules get combined' => [
                'p { %1$s } p { %2$s }',
                '<p class="p-1" style="%1$s %2$s">',
            ],
            'two different matchers rules matching the same element get combined' => [
                'p { %1$s } .p-1 { %2$s }',
                '<p class="p-1" style="%1$s %2$s">',
            ],
            'type => one element' => ['html { %1$s }', '<html style="%1$s">'],
            'type (case-insensitive) => one element' => ['HTML { %1$s }', '<html style="%1$s">'],
            'type => first matching element' => ['p { %1$s }', '<p class="p-1" style="%1$s">'],
            'type => second matching element' => ['p { %1$s }', '<p class="p-2" style="%1$s">'],
            'class => with class' => ['.p-2 { %1$s }', '<p class="p-2" style="%1$s">'],
            'two classes s=> with both classes' => [
                '.p-5.additional-class { %1$s }',
                '<p class="p-5 additional-class" style="%1$s">',
            ],
            'type & class => type with class' => ['p.p-2 { %1$s }', '<p class="p-2" style="%1$s">'],
            'ID => with ID' => ['#p4 { %1$s }', '<p class="p-4" id="p4" style="%1$s">'],
            'type & ID => type with ID' => ['p#p4 { %1$s }', '<p class="p-4" id="p4" style="%1$s">'],
            'universal => HTML' => ['* { %1$s }', '<html style="%1$s">'],
            'attribute presence => with attribute' => ['[title] { %1$s }', '<span title="bonjour" style="%1$s">'],
            'attribute exact value, double quotes => with exact attribute match' => [
                '[title="bonjour"] { %1$s }',
                '<span title="bonjour" style="%1$s">',
            ],
            'attribute exact value, single quotes => with exact match' => [
                '[title=\'bonjour\'] { %1$s }',
                '<span title="bonjour" style="%1$s">',
            ],
            // broken: attribute exact value without quotes => with exact match
            // broken: attribute exact two-word value, double quotes => with exact attribute value match
            // broken: attribute exact two-word value, single quotes => with exact attribute value match
            // broken: attribute exact value with ~, double quotes => exact attribute match
            // broken: attribute exact value with ~, single quotes => exact attribute match
            // broken: attribute exact value with ~, no quotes => exact attribute match
            // broken: attribute value with |, double quotes => with exact match
            // broken: attribute value with |, single quotes => with exact match
            // broken: attribute value with |, no quotes => with exact match
            // broken: attribute value with ^, double quotes => with exact match
            // broken: attribute value with ^, single quotes => with exact match
            // broken: attribute value with ^, no quotes => with exact match
            // broken: attribute value with $, double quotes => with exact match
            // broken: attribute value with $, single quotes => with exact match
            // broken: attribute value with $, no quotes => with exact match
            // broken: attribute value with *, double quotes => with exact match
            // broken: attribute value with *, single quotes => with exact match
            // broken: attribute value with *, no quotes => with exact match
            // broken: type & attribute presence => with type & attribute
            'type & attribute exact value, double quotes => with type & exact attribute value match' => [
                'span[title="bonjour"] { %1$s }',
                '<span title="bonjour" style="%1$s">',
            ],
            'type & attribute exact value, single quotes => with type & exact attribute value match' => [
                'span[title=\'bonjour\'] { %1$s }',
                '<span title="bonjour" style="%1$s">',
            ],
            'type & attribute exact value without quotes => with type & exact attribute value match' => [
                'span[title=bonjour] { %1$s }',
                '<span title="bonjour" style="%1$s">',
            ],
            'type & attribute exact two-word value, double quotes => with type & exact attribute value match' => [
                'span[title="buenas dias"] { %1$s }',
                '<span title="buenas dias" style="%1$s">',
            ],
            'type & attribute exact four-word value, double quotes => with type & exact attribute value match' => [
                'span[title="buenas dias bom dia"] { %1$s }',
                '<span title="buenas dias bom dia" style="%1$s">',
            ],
            'type & attribute exact two-word value, single quotes => with type & exact attribute value match' => [
                'span[title=\'buenas dias\'] { %1$s }',
                '<span title="buenas dias" style="%1$s">',
            ],
            'type & attribute exact four-word value, single quotes => with type & exact attribute value match' => [
                'span[title=\'buenas dias bom dia\'] { %1$s }',
                '<span title="buenas dias bom dia" style="%1$s">',
            ],
            'type & attribute value with ~, double quotes => with type & exact attribute match' => [
                'span[title~="bonjour"] { %1$s }',
                '<span title="bonjour" style="%1$s">',
            ],
            'type & attribute value with ~, single quotes => with type & exact attribute match' => [
                'span[title~=\'bonjour\'] { %1$s }',
                '<span title="bonjour" style="%1$s">',
            ],
            'type & attribute value with ~, no quotes => with type & exact attribute match' => [
                'span[title~=bonjour] { %1$s }',
                '<span title="bonjour" style="%1$s">',
            ],
            'type & attribute value with ~, double quotes => with type & word as 1st of 2 in attribute' => [
                'span[title~="buenas"] { %1$s }',
                '<span title="buenas dias" style="%1$s">',
            ],
            'type & attribute value with ~, double quotes => with type & word as 2nd of 2 in attribute' => [
                'span[title~="dias"] { %1$s }',
                '<span title="buenas dias" style="%1$s">',
            ],
            'type & attribute value with ~, double quotes => with type & word as 1st of 4 in attribute' => [
                'span[title~="buenas"] { %1$s }',
                '<span title="buenas dias bom dia" style="%1$s">',
            ],
            'type & attribute value with ~, double quotes => with type & word as 2nd of 4 in attribute' => [
                'span[title~="dias"] { %1$s }',
                '<span title="buenas dias bom dia" style="%1$s">',
            ],
            'type & attribute value with ~, double quotes => with type & word as last of 4 in attribute' => [
                'span[title~="dia"] { %1$s }',
                '<span title="buenas dias bom dia" style="%1$s">',
            ],
            'type & attribute value with |, double quotes => with exact match' => [
                'span[title|="bonjour"] { %1$s }',
                '<span title="bonjour" style="%1$s">',
            ],
            'type & attribute value with |, single quotes => with exact match' => [
                'span[title|=\'bonjour\'] { %1$s }',
                '<span title="bonjour" style="%1$s">',
            ],
            'type & attribute value with |, no quotes => with exact match' => [
                'span[title|=bonjour] { %1$s }',
                '<span title="bonjour" style="%1$s">',
            ],
            'type & two-word attribute value with |, double quotes => with exact match' => [
                'span[title|="buenas dias"] { %1$s }',
                '<span title="buenas dias" style="%1$s">',
            ],
            'type & attribute value with |, double quotes => with match before hyphen & another word' => [
                'span[title|="avez"] { %1$s }',
                '<span title="avez-vous" style="%1$s">',
            ],
            'type & attribute value with ^, double quotes => with exact match' => [
                'span[title^="bonjour"] { %1$s }',
                '<span title="bonjour" style="%1$s">',
            ],
            'type & attribute value with ^, single quotes => with exact match' => [
                'span[title^=\'bonjour\'] { %1$s }',
                '<span title="bonjour" style="%1$s">',
            ],
            'type & attribute value with ^, no quotes => with exact match' => [
                'span[title^=bonjour] { %1$s }',
                '<span title="bonjour" style="%1$s">',
            ],
            // broken: type & two-word attribute value with ^, double quotes => with exact match
            'type & attribute value with ^, double quotes => with prefix math' => [
                'span[title^="bon"] { %1$s }',
                '<span title="bonjour" style="%1$s">',
            ],
            'type & attribute value with ^, double quotes => with match before another word' => [
                'span[title^="buenas"] { %1$s }',
                '<span title="buenas dias" style="%1$s">',
            ],
            'type & attribute value with $, double quotes => with exact match' => [
                'span[title$="bonjour"] { %1$s }',
                '<span title="bonjour" style="%1$s">',
            ],
            'type & attribute value with $, single quotes => with exact match' => [
                'span[title$=\'bonjour\'] { %1$s }',
                '<span title="bonjour" style="%1$s">',
            ],
            'type & attribute value with $, no quotes => with exact match' => [
                'span[title$=bonjour] { %1$s }',
                '<span title="bonjour" style="%1$s">',
            ],
            'type & two-word attribute value with $, double quotes => with exact match' => [
                'span[title$="buenas dias"] { %1$s }',
                '<span title="buenas dias" style="%1$s">',
            ],
            'type & attribute value with $, double quotes => with suffix math' => [
                'span[title$="jour"] { %1$s }',
                '<span title="bonjour" style="%1$s">',
            ],
            'type & attribute value with $, double quotes => with match after another word' => [
                'span[title$="dias"] { %1$s }',
                '<span title="buenas dias" style="%1$s">',
            ],
            'type & two-word attribute value with *, double quotes => with exact match' => [
                'span[title*="buenas dias"] { %1$s }',
                '<span title="buenas dias" style="%1$s">',
            ],
            'type & attribute value with *, double quotes => with prefix math' => [
                'span[title*="bon"] { %1$s }',
                '<span title="bonjour" style="%1$s">',
            ],
            'type & attribute value with *, double quotes => with suffix math' => [
                'span[title*="jour"] { %1$s }',
                '<span title="bonjour" style="%1$s">',
            ],
            'type & attribute value with *, double quotes => with substring math' => [
                'span[title*="njo"] { %1$s }',
                '<span title="bonjour" style="%1$s">',
            ],
            'type & attribute value with *, double quotes => with match before another word' => [
                'span[title*="buenas"] { %1$s }',
                '<span title="buenas dias" style="%1$s">',
            ],
            'type & attribute value with *, double quotes => with match after another word' => [
                'span[title*="dias"] { %1$s }',
                '<span title="buenas dias" style="%1$s">',
            ],
            'type & special characters attribute value with *, double quotes => with substring match' => [
                'span[title*=": subtitle; author"] { %1$s }',
                '<span title="title: subtitle; author" style="%1$s">',
            ],
            'adjacent => 2nd of many' => ['p + p { %1$s }', '<p class="p-2" style="%1$s">'],
            'adjacent => last of many' => ['p + p { %1$s }', '<p class="p-6" style="%1$s">'],
            'adjacent (without space after +) => last of many' => ['p +p { %1$s }', '<p class="p-6" style="%1$s">'],
            'adjacent (without space before +) => last of many' => ['p+ p { %1$s }', '<p class="p-6" style="%1$s">'],
            'adjacent (without space before or after +) => last of many' => [
                'p+p { %1$s }',
                '<p class="p-6" style="%1$s">',
            ],
            'child (with spaces around >) => direct child' => ['p > span { %1$s }', '<span style="%1$s">'],
            'child (without space after >) => direct child' => ['p >span { %1$s }', '<span style="%1$s">'],
            'child (without space before >) => direct child' => ['p> span { %1$s }', '<span style="%1$s">'],
            'child (without space before or after >) => direct child' => ['p>span { %1$s }', '<span style="%1$s">'],
            'descendant => child' => ['p span { %1$s }', '<span style="%1$s">'],
            'descendant => grandchild' => ['body span { %1$s }', '<span style="%1$s">'],
            // broken: descendent attribute presence => with attribute
            // broken: descendent attribute exact value => with exact attribute match
            // broken: descendent type & attribute presence => with type & attribute
            'descendent type & attribute exact value => with type & exact attribute match' => [
                'body span[title="bonjour"] { %1$s }',
                '<span title="bonjour" style="%1$s">',
            ],
            'descendent type & attribute exact two-word value => with type & exact attribute match' => [
                'body span[title="buenas dias"] { %1$s }',
                '<span title="buenas dias" style="%1$s">',
            ],
            'descendent type & attribute value with ~ => with type & exact attribute match' => [
                'body span[title~="bonjour"] { %1$s }',
                '<span title="bonjour" style="%1$s">',
            ],
            'descendent type & attribute value with ~ => with type & word as 1st of 2 in attribute' => [
                'body span[title~="buenas"] { %1$s }',
                '<span title="buenas dias" style="%1$s">',
            ],
            'descendant of type & class: type & attribute exact value, no quotes => with type & exact match (#381)' => [
                'p.p-2 span[title=bonjour] { %1$s }',
                '<span title="bonjour" style="%1$s">',
            ],
            'descendant of attribute presence => parent with attribute' => [
                '[class] span { %1$s }',
                '<p class="p-1"><span style="%1$s">',
            ],
            'descendant of attribute exact value => parent with type & exact attribute match' => [
                '[id="p4"] span { %1$s }',
                '<p class="p-4" id="p4"><span title="avez-vous" style="%1$s">',
            ],
            // broken: descendant of type & attribute presence => parent with type & attribute
            'descendant of type & attribute exact value => parent with type & exact attribute match' => [
                'p[id="p4"] span { %1$s }',
                '<p class="p-4" id="p4"><span title="avez-vous" style="%1$s">',
            ],
            // broken: descendant of type & attribute exact two-word value => parent with type & exact attribute match
            //         (exact match doesn't currently match hyphens, which would be needed to match the class attribute)
            'descendant of type & attribute value with ~ => parent with type & exact attribute match' => [
                'p[class~="p-1"] span { %1$s }',
                '<p class="p-1"><span style="%1$s">',
            ],
            'descendant of type & attribute value with ~ => parent with type & word as 1st of 2 in attribute' => [
                'p[class~="p-5"] span { %1$s }',
                '<p class="p-5 additional-class"><span title="buenas dias bom dia" style="%1$s">',
            ],
            // broken: first-child => 1st of many
            'type & :first-child => 1st of many' => ['p:first-child { %1$s }', '<p class="p-1" style="%1$s">'],
            // broken: last-child => last of many
            'type & :last-child => last of many' => ['p:last-child { %1$s }', '<p class="p-6" style="%1$s">'],
            // broken: :not with type => other type
            // broken: :not with class => no class
            // broken: :not with class => other class
            'type & :not with class => without class' => ['span:not(.foo) { %1$s }', '<span style="%1$s">'],
            'type & :not with class => with other class' => ['p:not(.foo) { %1$s }', '<p class="p-1" style="%1$s">'],
        ];
    }

    /**
     * @test
     *
     * @param string $css CSS statements, potentially with %1$s and $2$s placeholders for a CSS declaration
     * @param string $expectedHtml HTML, potentially with %1$s and $2$s placeholders for a CSS declaration
     *
     * @dataProvider matchedCssDataProvider
     */
    public function emogrifyAppliesCssToMatchingElements($css, $expectedHtml)
    {
        $cssDeclaration1 = 'color: red;';
        $cssDeclaration2 = 'text-align: left;';
        $this->subject->setHtml(static::COMMON_TEST_HTML);
        $this->subject->setCss(\sprintf($css, $cssDeclaration1, $cssDeclaration2));

        $result = $this->subject->emogrify();

        static::assertContains(\sprintf($expectedHtml, $cssDeclaration1, $cssDeclaration2), $result);
    }

    /**
     * @return string[][]
     */
    public function nonMatchedCssDataProvider()
    {
        // The sprintf placeholders %1$s and %2$s will automatically be replaced with CSS declarations
        // like 'color: red;' or 'text-align: left;'.
        return [
            'type => not other type' => ['html { %1$s }', '<body>'],
            'class => not other class' => ['.p-2 { %1$s }', '<p class="p-1">'],
            'class => not without class' => ['.p-2 { %1$s }', '<body>'],
            'two classes => not only first class' => ['.p-1.another-class { %1$s }', '<p class="p-1">'],
            'two classes => not only second class' => ['.another-class.p-1 { %1$s }', '<p class="p-1">'],
            'type & class => not only type' => ['html.p-1 { %1$s }', '<html>'],
            'type & class => not only class' => ['html.p-1 { %1$s }', '<p class="p-1">'],
            'ID => not other ID' => ['#yeah { %1$s }', '<p class="p-4" id="p4">'],
            'ID => not without ID' => ['#yeah { %1$s }', '<span>'],
            'type & ID => not other type with that ID' => ['html#p4 { %1$s }', '<p class="p-4" id="p4">'],
            'type & ID => not that type with other ID' => ['p#p5 { %1$s }', '<p class="p-4" id="p4">'],
            'attribute presence => not element without that attribute' => ['[title] { %1$s }', '<span>'],
            'attribute exact value => not element without that attribute' => ['[title="bonjour"] { %1$s }', '<span>'],
            'attribute exact value => not element with different attribute value' => [
                '[title="hi"] { %1$s }',
                '<span title="bonjour">',
            ],
            'attribute exact value => not element with only substring match in attribute value' => [
                '[title="njo"] { %1$s }',
                '<span title="bonjour">',
            ],
            'type & attribute value with ~ => not element with only prefix match in attribute value' => [
                'span[title~="bon"] { %1$s }',
                '<span title="bonjour">',
            ],
            'type & attribute value with |, double quotes => not element with match after another word & hyphen' => [
                'span[title|="vous"] { %1$s }',
                '<span title="avez-vous">',
            ],
            'type & attribute value with ^ => not element with only substring match in attribute value' => [
                'span[title^="njo"] { %1$s }',
                '<span title="bonjour">',
            ],
            'type & attribute value with ^, double quotes => not element with only suffix match in attribute value' => [
                'span[title^="jour"] { %1$s }',
                '<span title="bonjour">',
            ],
            'type & attribute value with $ => not element with only substring match in attribute value' => [
                'span[title$="njo"] { %1$s }',
                '<span title="bonjour">',
            ],
            'type & attribute value with $, double quotes => not element with only prefix match in attribute value' => [
                'span[title$="bon"] { %1$s }',
                '<span title="bonjour">',
            ],
            'type & attribute value with * => not element with different attribute value' => [
                'span[title*="hi"] { %1$s }',
                '<span title="bonjour">',
            ],
            'adjacent => not 1st of many' => ['p + p { %1$s }', '<p class="p-1">'],
            'child => not grandchild' => ['html > span { %1$s }', '<span>'],
            'child => not parent' => ['span > html { %1$s }', '<html>'],
            'descendant => not sibling' => ['span span { %1$s }', '<span>'],
            'descendant => not parent' => ['p body { %1$s }', '<body>'],
            'type & :first-child => not 2nd of many' => ['p:first-child { %1$s }', '<p class="p-2">'],
            'type & :first-child => not last of many' => ['p:first-child { %1$s }', '<p class="p-6">'],
            'type & :last-child => not 1st of many' => ['p:last-child { %1$s }', '<p class="p-1">'],
            'type & :last-child => not 2nd of many' => ['p:last-child { %1$s }', '<p class="p-2">'],
            'type & :not with class => not with class' => ['p:not(.p-1) { %1$s }', '<p class="p-1">'],
        ];
    }

    /**
     * @test
     *
     * @param string $css CSS statements, potentially with %1$s and $2$s placeholders for a CSS declaration
     * @param string $expectedHtml HTML, potentially with %1$s and $2$s placeholders for a CSS declaration
     *
     * @dataProvider nonMatchedCssDataProvider
     */
    public function emogrifyNotAppliesCssToNonMatchingElements($css, $expectedHtml)
    {
        $cssDeclaration1 = 'color: red;';
        $cssDeclaration2 = 'text-align: left;';
        $this->subject->setHtml(static::COMMON_TEST_HTML);
        $this->subject->setCss(\sprintf($css, $cssDeclaration1, $cssDeclaration2));

        $result = $this->subject->emogrify();

        static::assertContains(\sprintf($expectedHtml, $cssDeclaration1, $cssDeclaration2), $result);
    }

    /**
     * Provides data to test the following selector specificity ordering:
     *     * < t < 2t < . < .+t < .+2t < 2. < 2.+t < 2.+2t
     *     < # < #+t < #+2t < #+. < #+.+t < #+.+2t < #+2. < #+2.+t < #+2.+2t
     *     < 2# < 2#+t < 2#+2t < 2#+. < 2#+.+t < 2#+.+2t < 2#+2. < 2#+2.+t < 2#+2.+2t
     * where '*' is the universal selector, 't' is a type selector, '.' is a class selector, and '#' is an ID selector.
     *
     * Also confirm up to 99 class selectors are supported (much beyond this would require a more complex comparator).
     *
     * Specificity ordering for selectors involving pseudo-classes, attributes and `:not` is covered through the
     * combination of these tests and the equal specificity tests and thus does not require explicit separate testing.
     *
     * @return string[][]
     */
    public function differentCssSelectorSpecificityDataProvider()
    {
        /**
         * @var string[] Selectors targeting `<span id="text">` with increasing specificity
         */
        $selectors = [
            'universal' => '*',
            'type' => 'span',
            '2 types' => 'p span',
            'class' => '.p-4 *',
            'class & type' => '.p-4 span',
            'class & 2 types' => 'p.p-4 span',
            '2 classes' => '.p-4.p-4 *',
            '2 classes & type' => '.p-4.p-4 span',
            '2 classes & 2 types' => 'p.p-4.p-4 span',
            'ID' => '#text',
            'ID & type' => 'span#text',
            'ID & 2 types' => 'p span#text',
            'ID & class' => '.p-4 #text',
            'ID & class & type' => '.p-4 span#text',
            'ID & class & 2 types' => 'p.p-4 span#text',
            'ID & 2 classes' => '.p-4.p-4 #text',
            'ID & 2 classes & type' => '.p-4.p-4 span#text',
            'ID & 2 classes & 2 types' => 'p.p-4.p-4 span#text',
            '2 IDs' => '#p4 #text',
            '2 IDs & type' => '#p4 span#text',
            '2 IDs & 2 types' => 'p#p4 span#text',
            '2 IDs & class' => '.p-4#p4 #text',
            '2 IDs & class & type' => '.p-4#p4 span#text',
            '2 IDs & class & 2 types' => 'p.p-4#p4 span#text',
            '2 IDs & 2 classes' => '.p-4.p-4#p4 #text',
            '2 IDs & 2 classes & type' => '.p-4.p-4#p4 span#text',
            '2 IDs & 2 classes & 2 types' => 'p.p-4.p-4#p4 span#text',
        ];

        $datasets = [];
        $previousSelector = '';
        $previousDescription = '';
        foreach ($selectors as $description => $selector) {
            if ($previousSelector !== '') {
                $datasets[$description . ' more specific than ' . $previousDescription] = [
                    '<span id="text"',
                    $previousSelector,
                    $selector,
                ];
            }
            $previousSelector = $selector;
            $previousDescription = $description;
        }

        // broken: class more specific than 99 types (requires support for chaining `:not(h1):not(h1)...`)
        $datasets['ID more specific than 99 classes'] = [
            '<p class="p-4" id="p4"',
            \str_repeat('.p-4', 99),
            '#p4',
        ];

        return $datasets;
    }

    /**
     * @test
     *
     * @param string $matchedTagPart Tag expected to be matched by both selectors, without the closing '>',
     *                               e.g. '<p class="p-1"'
     * @param string $lessSpecificSelector A selector expression
     * @param string $moreSpecificSelector Some other, more specific selector expression
     *
     * @dataProvider differentCssSelectorSpecificityDataProvider
     */
    public function emogrifyAppliesMoreSpecificCssSelectorToMatchingElements(
        $matchedTagPart,
        $lessSpecificSelector,
        $moreSpecificSelector
    ) {
        $this->subject->setHtml(static::COMMON_TEST_HTML);
        $this->subject->setCss(
            $lessSpecificSelector . ' { color: red; } ' .
            $moreSpecificSelector . ' { color: green; } ' .
            $moreSpecificSelector . ' { background-color: green; } ' .
            $lessSpecificSelector . ' { background-color: red; }'
        );

        $result = $this->subject->emogrify();

        static::assertContains($matchedTagPart . ' style="color: green; background-color: green;"', $result);
    }

    /**
     * @return string[][]
     */
    public function equalCssSelectorSpecificityDataProvider()
    {
        return [
            // pseudo-class
            'pseudo-class as specific as class' => ['<p class="p-1"', '*:first-child', '.p-1'],
            'type & pseudo-class as specific as type & class' => ['<p class="p-1"', 'p:first-child', 'p.p-1'],
            'class & pseudo-class as specific as two classes' => ['<p class="p-1"', '.p-1:first-child', '.p-1.p-1'],
            'ID & pseudo-class as specific as ID & class' => [
                '<span title="avez-vous"',
                '#p4 *:first-child',
                '#p4.p-4 *',
            ],
            '2 types & 2 classes & 2 IDs & pseudo-class as specific as 2 types & 3 classes & 2 IDs' => [
                '<span id="text"',
                'p.p-4.p-4#p4 span#text:last-child',
                'p.p-4.p-4.p-4#p4 span#text',
            ],
            // attribute
            'attribute as specific as class' => ['<span title="bonjour"', '[title="bonjour"]', '.p-2 *'],
            'type & attribute as specific as type & class' => [
                '<span title="bonjour"',
                'span[title="bonjour"]',
                '.p-2 span',
            ],
            'class & attribute as specific as two classes' => ['<p class="p-4" id="p4"', '.p-4[id="p4"]', '.p-4.p-4'],
            'ID & attribute as specific as ID & class' => ['<p class="p-4" id="p4"', '#p4[id="p4"]', '#p4.p-4'],
            '2 types & 2 classes & 2 IDs & attribute as specific as 2 types & 3 classes & 2 IDs' => [
                '<span id="text"',
                'p.p-4.p-4#p4[id="p4"] span#text',
                'p.p-4.p-4.p-4#p4 span#text',
            ],
            // :not
            // ideally these tests would be more minimal with just combinators and universal selectors in the :not
            // argument, however Symfony CssSelector only supports simple (single-element) selectors here
            ':not with type as specific as type and universal' => ['<p class="p-1"', '*:not(html)', 'html *'],
            'type & :not with type as specific as 2 types' => ['<p class="p-1"', 'p:not(html)', 'html p'],
            'class & :not with type as specific as type & class' => ['<p class="p-1"', '.p-1:not(html)', 'html .p-1'],
            'ID & :not with type as specific as type & ID' => ['<p class="p-4" id="p4"', '#p4:not(html)', 'html #p4'],
            '2 types & 2 classes & 2 IDs & :not with type as specific as 3 types & 2 classes & 2 IDs' => [
                '<span id="text"',
                'p.p-4.p-4#p4 span#text:not(html)',
                'html p.p-4.p-4#p4 span#text',
            ],
            // argument of :not
            ':not with type as specific as type' => ['<p class="p-1"', '*:not(h1)', 'p'],
            ':not with class as specific as class' => ['<p class="p-1"', '*:not(.p-2)', '.p-1'],
            ':not with ID as specific as ID' => ['<p class="p-4" id="p4"', '*:not(#p1)', '#p4'],
            // broken: :not with 2 types & 2 classes & 2 IDs as specific as 2 types & 2 classes & 2 IDs
            //         (`*:not(.p-1 #p1)`, i.e. with both class and ID, causes "Invalid type in selector")
        ];
    }

    /**
     * @test
     *
     * @param string $matchedTagPart Tag expected to be matched by both selectors, without the closing '>',
     *                               e.g. '<p class="p-1"'
     * @param string $selector1 A selector expression
     * @param string $selector2 Some other, equally specific selector expression
     *
     * @dataProvider equalCssSelectorSpecificityDataProvider
     */
    public function emogrifyAppliesLaterEquallySpecificCssSelectorToMatchingElements(
        $matchedTagPart,
        $selector1,
        $selector2
    ) {
        $this->subject->setHtml(static::COMMON_TEST_HTML);
        $this->subject->setCss(
            $selector1 . ' { color: red; } ' .
            $selector2 . ' { color: green; } ' .
            $selector2 . ' { background-color: red; } ' .
            $selector1 . ' { background-color: green; }'
        );

        $result = $this->subject->emogrify();

        static::assertContains($matchedTagPart . ' style="color: green; background-color: green;"', $result);
    }

    /**
     * @return string[][]
     */
    public function cssDeclarationWhitespaceDroppingDataProvider()
    {
        return [
            'no whitespace, trailing semicolon' => ['color:#000;'],
            'no whitespace, no trailing semicolon' => ['color:#000'],
            'space after colon, no trailing semicolon' => ['color: #000'],
            'space before colon, no trailing semicolon' => ['color :#000'],
            'space before property name, no trailing semicolon' => [' color:#000'],
            'space before trailing semicolon' => [' color:#000 ;'],
            'space after trailing semicolon' => [' color:#000; '],
            'space after property value, no trailing semicolon' => [' color:#000 '],
            'space after property value, trailing semicolon' => [' color:#000; '],
            'newline before property name, trailing semicolon' => ["\ncolor:#000;"],
            'newline after property semicolon' => ["color:#000;\n"],
            'newline before colon, trailing semicolon' => ["color\n:#000;"],
            'newline after colon, trailing semicolon' => ["color:\n#000;"],
            'newline after semicolon' => ["color:#000\n;"],
        ];
    }

    /**
     * @test
     *
     * @param string $cssDeclaration the CSS declaration block (without the curly braces)
     *
     * @dataProvider cssDeclarationWhitespaceDroppingDataProvider
     */
    public function emogrifyTrimsWhitespaceFromCssDeclarations($cssDeclaration)
    {
        $this->subject->setHtml('<html></html>');
        $this->subject->setCss('html {' . $cssDeclaration . '}');

        $result = $this->subject->emogrify();

        static::assertContains('<html style="color: #000;">', $result);
    }

    /**
     * @return string[][]
     */
    public function formattedCssDeclarationDataProvider()
    {
        return [
            'one declaration' => ['color: #000;', 'color: #000;'],
            'one declaration with dash in property name' => ['font-weight: bold;', 'font-weight: bold;'],
            'one declaration with space in property value' => ['margin: 0 4px;', 'margin: 0 4px;'],
            'two declarations separated by semicolon' => ['color: #000;width: 3px;', 'color: #000; width: 3px;'],
            'two declarations separated by semicolon & space'
            => ['color: #000; width: 3px;', 'color: #000; width: 3px;'],
            'two declarations separated by semicolon & linefeed' => [
                "color: #000;\nwidth: 3px;",
                'color: #000; width: 3px;',
            ],
            'two declarations separated by semicolon & Windows line ending' => [
                "color: #000;\r\nwidth: 3px;",
                'color: #000; width: 3px;',
            ],
            'one declaration with leading dash in property name' => [
                '-webkit-text-size-adjust:none;',
                '-webkit-text-size-adjust: none;',
            ],
            'one declaration with linefeed in property value' => [
                "text-shadow:\n1px 1px 3px #000,\n1px 1px 1px #000;",
                "text-shadow: 1px 1px 3px #000,\n1px 1px 1px #000;",
            ],
            'one declaration with Windows line ending in property value' => [
                "text-shadow:\r\n1px 1px 3px #000,\r\n1px 1px 1px #000;",
                "text-shadow: 1px 1px 3px #000,\r\n1px 1px 1px #000;",
            ],
        ];
    }

    /**
     * @test
     *
     * @param string $cssDeclarationBlock the CSS declaration block (without the curly braces)
     * @param string $expectedStyleAttributeContent the expected value of the style attribute
     *
     * @dataProvider formattedCssDeclarationDataProvider
     */
    public function emogrifyFormatsCssDeclarations($cssDeclarationBlock, $expectedStyleAttributeContent)
    {
        $this->subject->setHtml('<html></html>');
        $this->subject->setCss('html {' . $cssDeclarationBlock . '}');

        $result = $this->subject->emogrify();

        static::assertContains('<html style="' . $expectedStyleAttributeContent . '">', $result);
    }

    /**
     * @return string[][]
     */
    public function invalidDeclarationDataProvider()
    {
        return [
            'missing dash in property name' => ['font weight: bold;'],
            'invalid character in property name' => ['-9webkit-text-size-adjust:none;'],
            'missing :' => ['-webkit-text-size-adjust none'],
            'missing value' => ['-webkit-text-size-adjust :'],
        ];
    }

    /**
     * @test
     *
     * @param string $cssDeclarationBlock the CSS declaration block (without the curly braces)
     *
     * @dataProvider invalidDeclarationDataProvider
     */
    public function emogrifyDropsInvalidCssDeclaration($cssDeclarationBlock)
    {
        $this->subject->setHtml('<html></html>');
        $this->subject->setCss('html {' . $cssDeclarationBlock . '}');

        $result = $this->subject->emogrify();

        static::assertContains('<html style="">', $result);
    }

    /**
     * @test
     */
    public function emogrifyKeepsExistingStyleAttributes()
    {
        $styleAttribute = 'style="color: #ccc;"';
        $this->subject->setHtml('<html ' . $styleAttribute . '></html>');

        $result = $this->subject->emogrify();

        static::assertContains($styleAttribute, $result);
    }

    /**
     * @test
     */
    public function emogrifyAddsNewCssBeforeExistingStyle()
    {
        $styleAttributeValue = 'color: #ccc;';
        $this->subject->setHtml('<html style="' . $styleAttributeValue . '"></html>');
        $cssDeclarations = 'margin: 0 2px;';
        $css = 'html {' . $cssDeclarations . '}';
        $this->subject->setCss($css);

        $result = $this->subject->emogrify();

        static::assertContains('style="' . $cssDeclarations . ' ' . $styleAttributeValue . '"', $result);
    }

    /**
     * @test
     */
    public function emogrifyCanMatchMinifiedCss()
    {
        $this->subject->setHtml('<html><p></p></html>');
        $this->subject->setCss('p{color:blue;}html{color:red;}');

        $result = $this->subject->emogrify();

        static::assertContains('<html style="color: red;">', $result);
    }

    /**
     * @test
     */
    public function emogrifyLowercasesAttributeNamesFromStyleAttributes()
    {
        $this->subject->setHtml('<html style="COLOR:#ccc;"></html>');

        $result = $this->subject->emogrify();

        static::assertContains('style="color: #ccc;"', $result);
    }

    /**
     * @test
     */
    public function emogrifyLowercasesAttributeNamesFromPassedInCss()
    {
        $this->subject->setHtml('<html></html>');
        $this->subject->setCss('html {mArGiN:0 2pX;}');

        $result = $this->subject->emogrify();

        static::assertContains('style="margin: 0 2pX;"', $result);
    }

    /**
     * @test
     */
    public function emogrifyPreservesCaseForAttributeValuesFromPassedInCss()
    {
        $cssDeclaration = "content: 'Hello World';";
        $this->subject->setHtml('<html><body><p>target</p></body></html>');
        $this->subject->setCss('p {' . $cssDeclaration . '}');

        $result = $this->subject->emogrify();

        static::assertContains('<p style="' . $cssDeclaration . '">target</p>', $result);
    }

    /**
     * @test
     */
    public function emogrifyPreservesCaseForAttributeValuesFromParsedStyleBlock()
    {
        $cssDeclaration = "content: 'Hello World';";
        $this->subject->setHtml(
            '<html><head><style>p {' . $cssDeclaration . '}</style></head><body><p>target</p></body></html>'
        );

        $result = $this->subject->emogrify();

        static::assertContains('<p style="' . $cssDeclaration . '">target</p>', $result);
    }

    /**
     * @test
     */
    public function emogrifyRemovesStyleNodes()
    {
        $this->subject->setHtml('<html><style type="text/css"></style></html>');

        $result = $this->subject->emogrify();

        static::assertNotContains('<style', $result);
    }

    /**
     * @test
     *
     * @expectedException \InvalidArgumentException
     */
    public function emogrifyInDebugModeForInvalidCssSelectorThrowsException()
    {
        $this->subject->setDebug(true);

        $this->subject->setHtml(
            '<html><style type="text/css">p{color:red;} <style data-x="1">html{cursor:text;}</style></html>'
        );

        $this->subject->emogrify();
    }

    /**
     * @test
     */
    public function emogrifyNotInDebugModeIgnoresInvalidCssSelectors()
    {
        $this->subject->setDebug(false);

        $html = '<html><style type="text/css">' .
            'p{color:red;} <style data-x="1">html{cursor:text;} p{background-color:blue;}</style> ' .
            '<body><p></p></body></html>';
        $this->subject->setHtml($html);

        $html = $this->subject->emogrify();

        static::assertContains('color: red', $html);
        static::assertContains('background-color: blue', $html);
    }

    /**
     * @test
     */
    public function emogrifyByDefaultIgnoresInvalidCssSelectors()
    {
        $subject = new Emogrifier();

        $html = '<html><style type="text/css">' .
            'p{color:red;} <style data-x="1">html{cursor:text;} p{background-color:blue;}</style> ' .
            '<body><p></p></body></html>';
        $subject->setHtml($html);

        $html = $subject->emogrify();
        static::assertContains('color: red', $html);
        static::assertContains('background-color: blue', $html);
    }

    /**
     * Data provider for things that should be left out when applying the CSS.
     *
     * @return string[][]
     */
    public function unneededCssThingsDataProvider()
    {
        return [
            'CSS comments with one asterisk' => ['p {color: #000;/* black */}', 'black'],
            'CSS comments with two asterisks' => ['p {color: #000;/** black */}', 'black'],
            '@import directive' => ['@import "foo.css";', '@import'],
            'two @import directives, minified' => ['@import "foo.css";@import "bar.css";', '@import'],
            '@charset directive' => ['@charset "UTF-8";', '@charset'],
            'style in "aural" media type rule' => ['@media aural {p {color: #000;}}', '#000'],
            'style in "braille" media type rule' => ['@media braille {p {color: #000;}}', '#000'],
            'style in "embossed" media type rule' => ['@media embossed {p {color: #000;}}', '#000'],
            'style in "handheld" media type rule' => ['@media handheld {p {color: #000;}}', '#000'],
            'style in "projection" media type rule' => ['@media projection {p {color: #000;}}', '#000'],
            'style in "speech" media type rule' => ['@media speech {p {color: #000;}}', '#000'],
            'style in "tty" media type rule' => ['@media tty {p {color: #000;}}', '#000'],
            'style in "tv" media type rule' => ['@media tv {p {color: #000;}}', '#000'],
            'style in "tv" media type rule with extra spaces' => [
                '  @media  tv  {  p  {  color  :  #000  ;  }  }  ',
                '#000',
            ],
            'style in "tv" media type rule with linefeeds' => [
                "\n@media\ntv\n{\np\n{\ncolor\n:\n#000\n;\n}\n}\n",
                '#000',
            ],
            'style in "tv" media type rule with Windows line endings' => [
                "\r\n@media\r\ntv\r\n{\r\np\r\n{\r\ncolor\r\n:\r\n#000\r\n;\r\n}\r\n}\r\n",
                '#000',
            ],
            'style in "only tv" media type rule' => ['@media only tv {p {color: #000;}}', '#000'],
            'style in "only tv" media type rule with extra spaces' => [
                '  @media  only  tv  {  p  {  color  :  #000  ;  }  }  ',
                '#000',
            ],
            'style in "only tv" media type rule with linefeeds' => [
                "\n@media\nonly\ntv\n{\np\n{\ncolor\n:\n#000\n;\n}\n}\n",
                '#000',
            ],
            'style in "only tv" media type rule with Windows line endings' => [
                "\r\n@media\r\nonly\r\ntv\r\n{\r\np\r\n{\r\ncolor\r\n:\r\n#000\r\n;\r\n}\r\n}\r\n",
                '#000',
            ],
        ];
    }

    /**
     * @test
     *
     * @param string $unneededCss
     * @param string $markerNotExpectedInHtml
     *
     * @dataProvider unneededCssThingsDataProvider
     */
    public function emogrifyFiltersUnneededCssThings($unneededCss, $markerNotExpectedInHtml)
    {
        $this->subject->setHtml('<html><p>foo</p></html>');
        $this->subject->setCss($unneededCss);

        $result = $this->subject->emogrify();

        static::assertNotContains($markerNotExpectedInHtml, $result);
    }

    /**
     * @test
     *
     * @param string $unneededCss
     *
     * @dataProvider unneededCssThingsDataProvider
     */
    public function emogrifyMatchesRuleAfterUnneededCssThing($unneededCss)
    {
        $this->subject->setHtml('<html><body></body></html>');
        $this->subject->setCss($unneededCss . ' body { color: green; }');

        $result = $this->subject->emogrify();

        static::assertContains('<body style="color: green;">', $result);
    }

    /**
     * Data provider for media rules.
     *
     * @return string[][]
     */
    public function mediaRulesDataProvider()
    {
        return [
            'style in "only all" media type rule' => ['@media only all {p {color: #000;}}'],
            'style in "only screen" media type rule' => ['@media only screen {p {color: #000;}}'],
            'style in "only screen" media type rule with extra spaces'
            => ['  @media  only  screen  {  p  {  color  :  #000;  }  }  '],
            'style in "only screen" media type rule with linefeeds'
            => ["\n@media\nonly\nscreen\n{\np\n{\ncolor\n:\n#000;\n}\n}\n"],
            'style in "only screen" media type rule with Windows line endings'
            => ["\r\n@media\r\nonly\r\nscreen\r\n{\r\np\r\n{\r\ncolor\r\n:\r\n#000;\r\n}\r\n}\r\n"],
            'style in media type rule' => ['@media {p {color: #000;}}'],
            'style in media type rule with extra spaces' => ['  @media  {  p  {  color  :  #000;  }  }  '],
            'style in media type rule with linefeeds' => ["\n@media\n{\np\n{\ncolor\n:\n#000;\n}\n}\n"],
            'style in media type rule with Windows line endings'
            => ["\r\n@media\r\n{\r\np\r\n{\r\ncolor\r\n:\r\n#000;\r\n}\r\n}\r\n"],
            'style in "screen" media type rule' => ['@media screen {p {color: #000;}}'],
            'style in "screen" media type rule with extra spaces'
            => ['  @media  screen  {  p  {  color  :  #000;  }  }  '],
            'style in "screen" media type rule with linefeeds'
            => ["\n@media\nscreen\n{\np\n{\ncolor\n:\n#000;\n}\n}\n"],
            'style in "screen" media type rule with Windows line endings'
            => ["\r\n@media\r\nscreen\r\n{\r\np\r\n{\r\ncolor\r\n:\r\n#000;\r\n}\r\n}\r\n"],
            'style in "print" media type rule' => ['@media print {p {color: #000;}}'],
            'style in "all" media type rule' => ['@media all {p {color: #000;}}'],
        ];
    }

    /**
     * @test
     *
     * @param string $css
     *
     * @dataProvider mediaRulesDataProvider
     */
    public function emogrifyKeepsMediaRules($css)
    {
        $this->subject->setHtml('<html><p>foo</p></html>');
        $this->subject->setCss($css);

        $result = $this->subject->emogrify();

        static::assertContainsCss($css, $result);
    }

    /**
     * @return string[][]
     */
    public function orderedRulesAndSurroundingCssDataProvider()
    {
        $possibleSurroundingCss = [
            'nothing' => '',
            'space' => ' ',
            'linefeed' => "\n",
            'Windows line ending' => "\r\n",
            'comment' => '/* hello */',
            'other non-matching CSS' => 'h6 { color: #f00; }',
            'other matching CSS' => 'p { color: #f00; }',
            'disallowed media rule' => '@media tv { p { color: #f00; } }',
            'allowed but non-matching media rule' => '@media screen { h6 { color: #f00; } }',
            'non-matching CSS with pseudo-component' => 'h6:hover { color: #f00; }',
        ];
        $possibleCssBefore = $possibleSurroundingCss + [
                '@import' => '@import "foo.css";',
                '@charset' => '@charset "UTF-8";',
            ];

        $datasetsSurroundingCss = [];
        foreach ($possibleCssBefore as $descriptionBefore => $cssBefore) {
            foreach ($possibleSurroundingCss as $descriptionBetween => $cssBetween) {
                foreach ($possibleSurroundingCss as $descriptionAfter => $cssAfter) {
                    // every combination would be a ridiculous c.1000 datasets - choose a select few
                    // test all possible CSS before once
                    if (($cssBetween === '' && $cssAfter === '')
                        // test all possible CSS between once
                        || ($cssBefore === '' && $cssAfter === '')
                        // test all possible CSS after once
                        || ($cssBefore === '' && $cssBetween === '')
                        // test with each possible CSS in all three positions
                        || ($cssBefore === $cssBetween && $cssBetween === $cssAfter)
                    ) {
                        $description = ' with ' . $descriptionBefore . ' before, '
                            . $descriptionBetween . ' between, '
                            . $descriptionAfter . ' after';
                        $datasetsSurroundingCss[$description] = [$cssBefore, $cssBetween, $cssAfter];
                    }
                }
            }
        }

        $datasets = [];
        foreach ($datasetsSurroundingCss as $description => $datasetSurroundingCss) {
            $datasets += [
                'two media rules' . $description => \array_merge(
                    ['@media all { p { color: #333; } }', '@media print { p { color: #000; } }'],
                    $datasetSurroundingCss
                ),
                'two rules involving pseudo-components' . $description => \array_merge(
                    ['a:hover { color: blue; }', 'a:active { color: green; }'],
                    $datasetSurroundingCss
                ),
                'media rule followed by rule involving pseudo-components' . $description => \array_merge(
                    ['@media screen { p { color: #000; } }', 'a:hover { color: green; }'],
                    $datasetSurroundingCss
                ),
                'rule involving pseudo-components followed by media rule' . $description => \array_merge(
                    ['a:hover { color: green; }', '@media screen { p { color: #000; } }'],
                    $datasetSurroundingCss
                ),
            ];
        }
        return $datasets;
    }

    /**
     * @test
     *
     * @param string $rule1
     * @param string $rule2
     * @param string $cssBefore CSS to insert before the first rule
     * @param string $cssBetween CSS to insert between the rules
     * @param string $cssAfter CSS to insert after the second rule
     *
     * @dataProvider orderedRulesAndSurroundingCssDataProvider
     */
    public function emogrifyKeepsRulesCopiedToStyleElementInSpecifiedOrder(
        $rule1,
        $rule2,
        $cssBefore,
        $cssBetween,
        $cssAfter
    ) {
        $this->subject->setHtml('<html><p><a>foo</a></p></html>');
        $this->subject->setCss($cssBefore . $rule1 . $cssBetween . $rule2 . $cssAfter);

        $result = $this->subject->emogrify();

        static::assertContainsCss($rule1 . $rule2, $result);
    }

    /**
     * @test
     */
    public function removeAllowedMediaTypeRemovesStylesForTheGivenMediaType()
    {
        $css = '@media screen { html { some-property: value; } }';
        $this->subject->setHtml('<html></html>');
        $this->subject->setCss($css);
        $this->subject->removeAllowedMediaType('screen');

        $result = $this->subject->emogrify();

        static::assertNotContains('@media', $result);
    }

    /**
     * @test
     */
    public function addAllowedMediaTypeKeepsStylesForTheGivenMediaType()
    {
        $css = '@media braille { html { some-property: value; } }';
        $this->subject->setHtml('<html></html>');
        $this->subject->setCss($css);
        $this->subject->addAllowedMediaType('braille');

        $result = $this->subject->emogrify();

        static::assertContainsCss($css, $result);
    }

    /**
     * @test
     */
    public function emogrifyKeepsExistingHeadElementContent()
    {
        $this->subject->setHtml('<html><head><!-- original content --></head></html>');
        $this->subject->setCss('@media all { html { some-property: value; } }');

        $result = $this->subject->emogrify();

        static::assertContains('<!-- original content -->', $result);
    }

    /**
     * @test
     */
    public function emogrifyAddsStyleElementToBody()
    {
        $html = $this->html5DocumentType . '<html><head><!-- original content --></head></html>';
        $this->subject->setHtml($html);
        $this->subject->setCss('@media all { html { some-property: value; } }');

        $result = $this->subject->emogrify();

        static::assertContains('<body><style type="text/css">', $result);
    }

    /**
     * Valid media query which need to be preserved
     *
     * @return string[][]
     */
    public function validMediaPreserveDataProvider()
    {
        return [
            'style in "only screen and size" media type rule' => [
                '@media only screen and (min-device-width: 320px) and (max-device-width: 480px) { h1 { color:red; } }',
            ],
            'style in "screen size" media type rule' => [
                '@media screen and (min-device-width: 320px) and (max-device-width: 480px) { h1 { color:red; } }',
            ],
            'style in "only screen and screen size" media type rule' => [
                '@media only screen and (min-device-width: 320px) and (max-device-width: 480px) { h1 { color:red; } }',
            ],
            'style in "all and screen size" media type rule' => [
                '@media all and (min-device-width: 320px) and (max-device-width: 480px) { h1 { color:red; } }',
            ],
            'style in "only all and" media type rule' => [
                '@media only all and (min-device-width: 320px) and (max-device-width: 480px) { h1 { color:red; } }',
            ],
            'style in "all" media type rule' => ['@media all {p {color: #000;}}'],
            'style in "only screen" media type rule' => ['@media only screen { h1 { color:red; } }'],
            'style in "only all" media type rule' => ['@media only all { h1 { color:red; } }'],
            'style in "screen" media type rule' => ['@media screen { h1 { color:red; } }'],
            'style in "print" media type rule' => ['@media print { * { color:#000 !important; } }'],
            'style in media type rule without specification' => ['@media { h1 { color:red; } }'],
            'style with multiple media type rules' => [
                '@media all { p { color: #000; } }' .
                '@media only screen { h1 { color:red; } }' .
                '@media only all { h1 { color:red; } }' .
                '@media print { * { color:#000 !important; } }' .
                '@media { h1 { color:red; } }',
            ],
        ];
    }

    /**
     * @test
     *
     * @param string $css
     *
     * @dataProvider validMediaPreserveDataProvider
     */
    public function emogrifyWithValidMediaQueryContainsInnerCss($css)
    {
        $this->subject->setHtml('<html><h1></h1><p></p></html>');
        $this->subject->setCss($css);

        $result = $this->subject->emogrify();

        static::assertContainsCss('<style type="text/css">' . $css . '</style>', $result);
    }

    /**
     * @test
     *
     * @param string $css
     *
     * @dataProvider validMediaPreserveDataProvider
     */
    public function emogrifyWithValidMinifiedMediaQueryContainsInnerCss($css)
    {
        // Minify CSS by removing unnecessary whitespace.
        $css = \preg_replace('/\\s*{\\s*/', '{', $css);
        $css = \preg_replace('/;?\\s*}\\s*/', '}', $css);
        $css = \preg_replace('/@media{/', '@media {', $css);

        $this->subject->setHtml('<html><h1></h1><p></p></html>');
        $this->subject->setCss($css);

        $result = $this->subject->emogrify();

        static::assertContains('<style type="text/css">' . $css . '</style>', $result);
    }

    /**
     * @test
     *
     * @param string $css
     *
     * @dataProvider validMediaPreserveDataProvider
     */
    public function emogrifyForHtmlWithValidMediaQueryContainsInnerCss($css)
    {
        $this->subject->setHtml('<html><style type="text/css">' . $css . '</style><h1></h1><p></p></html>');

        $result = $this->subject->emogrify();

        static::assertContainsCss('<style type="text/css">' . $css . '</style>', $result);
    }

    /**
     * @test
     *
     * @param string $css
     *
     * @dataProvider validMediaPreserveDataProvider
     */
    public function emogrifyWithValidMediaQueryNotContainsInlineCss($css)
    {
        $this->subject->setHtml('<html><h1></h1></html>');
        $this->subject->setCss($css);

        $result = $this->subject->emogrify();

        static::assertNotContains('style=', $result);
    }

    /**
     * Invalid media query which need to be strip
     *
     * @return string[][]
     */
    public function invalidMediaPreserveDataProvider()
    {
        return [
            'style in "braille" type rule' => ['@media braille { h1 { color:red; } }'],
            'style in "embossed" type rule' => ['@media embossed { h1 { color:red; } }'],
            'style in "handheld" type rule' => ['@media handheld { h1 { color:red; } }'],
            'style in "projection" type rule' => ['@media projection { h1 { color:red; } }'],
            'style in "speech" type rule' => ['@media speech { h1 { color:red; } }'],
            'style in "tty" type rule' => ['@media tty { h1 { color:red; } }'],
            'style in "tv" type rule' => ['@media tv { h1 { color:red; } }'],
        ];
    }

    /**
     * @test
     *
     * @param string $css
     *
     * @dataProvider invalidMediaPreserveDataProvider
     */
    public function emogrifyWithInvalidMediaQueryNotContainsInnerCss($css)
    {
        $this->subject->setHtml('<html><h1></h1></html>');
        $this->subject->setCss($css);

        $result = $this->subject->emogrify();

        static::assertNotContainsCss($css, $result);
    }

    /**
     * @test
     *
     * @param string $css
     *
     * @dataProvider invalidMediaPreserveDataProvider
     */
    public function emogrifyWithInvalidMediaQueryNotContainsInlineCss($css)
    {
        $this->subject->setHtml('<html><h1></h1></html>');
        $this->subject->setCss($css);

        $result = $this->subject->emogrify();

        static::assertNotContains('style=', $result);
    }

    /**
     * @test
     *
     * @param string $css
     *
     * @dataProvider invalidMediaPreserveDataProvider
     */
    public function emogrifyFromHtmlWithInvalidMediaQueryNotContainsInnerCss($css)
    {
        $this->subject->setHtml('<html><style type="text/css">' . $css . '</style><h1></h1></html>');

        $result = $this->subject->emogrify();

        static::assertNotContainsCss($css, $result);
    }

    /**
     * @test
     *
     * @param string $css
     *
     * @dataProvider invalidMediaPreserveDataProvider
     */
    public function emogrifyFromHtmlWithInvalidMediaQueryNotContainsInlineCss($css)
    {
        $this->subject->setHtml('<html><style type="text/css">' . $css . '</style><h1></h1></html>');

        $result = $this->subject->emogrify();

        static::assertNotContains('style=', $result);
    }

    /**
     * @test
     */
    public function emogrifyIgnoresEmptyMediaQuery()
    {
        $this->subject->setHtml('<html><h1></h1></html>');
        $this->subject->setCss('@media screen {} @media tv { h1 { color: red; } }');

        $result = $this->subject->emogrify();

        static::assertNotContains('style=', $result);
        static::assertNotContains('@media screen', $result);
    }

    /**
     * @test
     */
    public function emogrifyIgnoresMediaQueryWithWhitespaceOnly()
    {
        $this->subject->setHtml('<html><h1></h1></html>');
        $this->subject->setCss('@media screen { } @media tv { h1 { color: red; } }');

        $result = $this->subject->emogrify();

        static::assertNotContains('style=', $result);
        static::assertNotContains('@media screen', $result);
    }

    /**
     * @return string[][]
     */
    public function mediaTypeDataProvider()
    {
        return [
            'disallowed type' => ['tv'],
            'allowed type' => ['screen'],
        ];
    }

    /**
     * @test
     *
     * @param string $emptyRuleMediaType
     *
     * @dataProvider mediaTypeDataProvider
     */
    public function emogrifyKeepsMediaRuleAfterEmptyMediaRule($emptyRuleMediaType)
    {
        $this->subject->setHtml('<html><h1></h1></html>');
        $this->subject->setCss('@media ' . $emptyRuleMediaType . ' {} @media all { h1 { color: red; } }');

        $result = $this->subject->emogrify();

        static::assertContainsCss('@media all { h1 { color: red; } }', $result);
    }

    /**
     * @test
     *
     * @param string $emptyRuleMediaType
     *
     * @dataProvider mediaTypeDataProvider
     */
    public function emogrifyNotKeepsUnneededMediaRuleAfterEmptyMediaRule($emptyRuleMediaType)
    {
        $this->subject->setHtml('<html><h1></h1></html>');
        $this->subject->setCss('@media ' . $emptyRuleMediaType . ' {} @media speech { h1 { color: red; } }');

        $result = $this->subject->emogrify();

        static::assertNotContains('@media', $result);
    }

    /**
     * @param string[] $precedingSelectorComponents Array of selectors to which each type of pseudo-component is
     *                                              appended to create a selector for a CSS rule.
     *                                              Keys are human-readable descriptions.
     *
     * @return string[][]
     */
    private function getCssRuleDatasetsWithSelectorPseudoComponents(array $precedingSelectorComponents)
    {
        $rulesComponents = [
            'pseudo-element' => [
                'selectorPseudoComponent' => '::after',
                'declarationsBlock' => 'content: "bar";',
            ],
            'CSS2 pseudo-element' => [
                'selectorPseudoComponent' => ':after',
                'declarationsBlock' => 'content: "bar";',
            ],
            'hyphenated pseudo-element' => [
                'selectorPseudoComponent' => '::first-letter',
                'declarationsBlock' => 'color: green;',
            ],
            'pseudo-class' => [
                'selectorPseudoComponent' => ':hover',
                'declarationsBlock' => 'color: green;',
            ],
            'hyphenated pseudo-class' => [
                'selectorPseudoComponent' => ':read-only',
                'declarationsBlock' => 'color: green;',
            ],
            'pseudo-class with parameter' => [
                'selectorPseudoComponent' => ':lang(en)',
                'declarationsBlock' => 'color: green;',
            ],
        ];

        $datasets = [];
        foreach ($precedingSelectorComponents as $precedingComponentDescription => $precedingSelectorComponent) {
            foreach ($rulesComponents as $pseudoComponentDescription => $ruleComponents) {
                $datasets[$precedingComponentDescription . ' ' . $pseudoComponentDescription] = [
                    $precedingSelectorComponent . $ruleComponents['selectorPseudoComponent']
                    . ' { ' . $ruleComponents['declarationsBlock'] . ' }',
                ];
            }
        }
        return $datasets;
    }

    /**
     * @return string[][]
     */
    public function matchingSelectorWithPseudoComponentCssRuleDataProvider()
    {
        $datasetsWithSelectorPseudoComponents = $this->getCssRuleDatasetsWithSelectorPseudoComponents(
            [
                'lone' => '',
                'type &' => 'a',
                'class &' => '.a',
                'ID &' => '#a',
                'attribute &' => 'a[href="a"]',
                'static pseudo-class &' => 'a:first-child',
                'ancestor &' => 'p ',
                'ancestor & type &' => 'p a',
            ]
        );
        $datasetsWithCombinedPseudoSelectors = [
            'pseudo-class & descendant' => ['p:hover a { color: green; }'],
            'pseudo-class & pseudo-element' => ['a:hover::after { content: "bar"; }'],
            'pseudo-element & pseudo-class' => ['a::after:hover { content: "bar"; }'],
            'two pseudo-classes' => ['a:focus:hover { color: green; }'],
        ];

        return \array_merge($datasetsWithSelectorPseudoComponents, $datasetsWithCombinedPseudoSelectors);
    }

    /**
     * @test
     *
     * @param string $css
     *
     * @dataProvider matchingSelectorWithPseudoComponentCssRuleDataProvider
     */
    public function emogrifyKeepsRuleWithPseudoComponentInMatchingSelector($css)
    {
        $this->subject->setHtml('<html><p><a id="a" class="a" href="a">foo</a></p></html>');
        $this->subject->setCss($css);

        $result = $this->subject->emogrify();

        self::assertContainsCss($css, $result);
    }

    /**
     * @return string[][]
     */
    public function nonMatchingSelectorWithPseudoComponentCssRuleDataProvider()
    {
        $datasetsWithSelectorPseudoComponents = $this->getCssRuleDatasetsWithSelectorPseudoComponents(
            [
                'type &' => 'b',
                'class &' => '.b',
                'ID &' => '#b',
                'attribute &' => 'a[href="b"]',
                'static pseudo-class &' => 'a:not(.a)',
                'ancestor &' => 'ul ',
                'ancestor & type &' => 'p b',
            ]
        );
        $datasetsWithCombinedPseudoSelectors = [
            'pseudo-class & descendant' => ['ul:hover a { color: green; }'],
            'pseudo-class & pseudo-element' => ['b:hover::after { content: "bar"; }'],
            'pseudo-element & pseudo-class' => ['b::after:hover { content: "bar"; }'],
            'two pseudo-classes' => ['input:focus:hover { color: green; }'],
        ];

        return \array_merge($datasetsWithSelectorPseudoComponents, $datasetsWithCombinedPseudoSelectors);
    }

    /**
     * @test
     *
     * @param string $css
     *
     * @dataProvider nonMatchingSelectorWithPseudoComponentCssRuleDataProvider
     */
    public function emogrifyNotKeepsRuleWithPseudoComponentInNonMatchingSelector($css)
    {
        $this->subject->setHtml('<html><p><a id="a" class="a" href="#">foo</a></p></html>');
        $this->subject->setCss($css);

        $result = $this->subject->emogrify();

        self::assertNotContainsCss($css, $result);
    }

    /**
     * @test
     */
    public function emogrifyKeepsRuleInMediaQueryWithPseudoComponentInMatchingSelector()
    {
        $this->subject->setHtml('<html><a>foo</a></html>');
        $css = '@media screen { a:hover { color: green; } }';
        $this->subject->setCss($css);

        $result = $this->subject->emogrify();

        self::assertContainsCss($css, $result);
    }

    /**
     * @test
     */
    public function emogrifyNotKeepsRuleInMediaQueryWithPseudoComponentInNonMatchingSelector()
    {
        $this->subject->setHtml('<html><a>foo</a></html>');
        $css = '@media screen { b:hover { color: green; } }';
        $this->subject->setCss($css);

        $result = $this->subject->emogrify();

        self::assertNotContainsCss($css, $result);
    }

    /**
     * @test
     */
    public function emogrifyKeepsRuleWithPseudoComponentInMultipleMatchingSelectorsFromSingleRule()
    {
        $this->subject->setHtml('<html><p>foo</p><a>bar</a></html>');
        $css = 'p:hover, a:hover { color: green; }';
        $this->subject->setCss($css);

        $result = $this->subject->emogrify();

        static::assertContainsCss($css, $result);
    }

    /**
     * @test
     */
    public function emogrifyKeepsOnlyMatchingSelectorsWithPseudoComponentFromSingleRule()
    {
        $this->subject->setHtml('<html><a>foo</a></html>');
        $this->subject->setCss('p:hover, a:hover { color: green; }');

        $result = $this->subject->emogrify();

        static::assertContainsCss('<style type="text/css">a:hover { color: green; }</style>', $result);
    }

    /**
     * @test
     */
    public function emogrifyAppliesCssToMatchingElementsAndKeepsRuleWithPseudoComponentFromSingleRule()
    {
        $this->subject->setHtml('<html><p>foo</p><a>bar</a></html>');
        $this->subject->setCss('p, a:hover { color: green; }');

        $result = $this->subject->emogrify();

        static::assertContains('<p style="color: green;">', $result);
        static::assertContainsCss('<style type="text/css">a:hover { color: green; }</style>', $result);
    }

    /**
     * @return string[][]
     */
    public function mediaTypesDataProvider()
    {
        return [
            'disallowed type after disallowed type' => ['tv', 'speech'],
            'allowed type after disallowed type' => ['tv', 'all'],
            'disallowed type after allowed type' => ['screen', 'tv'],
            'allowed type after allowed type' => ['screen', 'all'],
        ];
    }

    /**
     * @test
     *
     * @param string $emptyRuleMediaType
     * @param string $mediaType
     *
     * @dataProvider mediaTypesDataProvider
     */
    public function emogrifyAppliesCssBetweenEmptyMediaRuleAndMediaRule($emptyRuleMediaType, $mediaType)
    {
        $this->subject->setHtml('<html><h1></h1></html>');
        $this->subject->setCss(
            '@media ' . $emptyRuleMediaType . ' {} h1 { color: green; } @media ' . $mediaType
            . ' { h1 { color: red; } }'
        );

        $result = $this->subject->emogrify();

        static::assertContains('<h1 style="color: green;">', $result);
    }

    /**
     * @test
     *
     * @param string $emptyRuleMediaType
     * @param string $mediaType
     *
     * @dataProvider mediaTypesDataProvider
     */
    public function emogrifyAppliesCssBetweenEmptyMediaRuleAndMediaRuleWithCssAfter($emptyRuleMediaType, $mediaType)
    {
        $this->subject->setHtml('<html><h1></h1></html>');
        $this->subject->setCss(
            '@media ' . $emptyRuleMediaType . ' {} h1 { color: green; } @media ' . $mediaType
            . ' { h1 { color: red; } } h1 { font-size: 24px; }'
        );

        $result = $this->subject->emogrify();

        static::assertContains('<h1 style="color: green; font-size: 24px;">', $result);
    }

    /**
     * @test
     */
    public function emogrifyAppliesCssFromStyleNodes()
    {
        $styleAttributeValue = 'color: #ccc;';
        $this->subject->setHtml('<html><style type="text/css">html {' . $styleAttributeValue . '}</style></html>');

        $result = $this->subject->emogrify();

        static::assertContains('<html style="' . $styleAttributeValue . '">', $result);
    }

    /**
     * @test
     */
    public function emogrifyWhenDisabledNotAppliesCssFromStyleBlocks()
    {
        $styleAttributeValue = 'color: #ccc;';
        $this->subject->setHtml('<html><style type="text/css">html {' . $styleAttributeValue . '}</style></html>');
        $this->subject->disableStyleBlocksParsing();

        $result = $this->subject->emogrify();

        static::assertNotContains('style=', $result);
    }

    /**
     * @test
     */
    public function emogrifyWhenStyleBlocksParsingDisabledKeepInlineStyles()
    {
        $styleAttributeValue = 'text-align: center;';
        $this->subject->setHtml(
            '<html><head><style type="text/css">p { color: #ccc; }</style></head>' .
            '<body><p style="' . $styleAttributeValue . '">paragraph</p></body></html>'
        );
        $this->subject->disableStyleBlocksParsing();

        $result = $this->subject->emogrify();

        static::assertContains('<p style="' . $styleAttributeValue . '">', $result);
    }

    /**
     * @test
     */
    public function emogrifyWhenDisabledNotAppliesCssFromInlineStyles()
    {
        $this->subject->setHtml('<html style="color: #ccc;"></html>');
        $this->subject->disableInlineStyleAttributesParsing();

        $result = $this->subject->emogrify();

        static::assertNotContains('<html style', $result);
    }

    /**
     * @test
     */
    public function emogrifyWhenInlineStyleAttributesParsingDisabledKeepStyleBlockStyles()
    {
        $styleAttributeValue = 'color: #ccc;';
        $this->subject->setHtml(
            '<html><head><style type="text/css">p { ' . $styleAttributeValue . ' }</style></head>' .
            '<body><p style="text-align: center;">paragraph</p></body></html>'
        );
        $this->subject->disableInlineStyleAttributesParsing();

        $result = $this->subject->emogrify();

        static::assertContains('<p style="' . $styleAttributeValue . '">', $result);
    }

    /**
     * Emogrify was handling case differently for passed-in CSS vs. CSS parsed from style blocks.
     *
     * @test
     */
    public function emogrifyAppliesCssWithMixedCaseAttributesInStyleBlock()
    {
        $this->subject->setHtml(
            '<html><head><style>#topWrap p {padding-bottom: 1px;PADDING-TOP: 0;}</style></head>' .
            '<body><div id="topWrap"><p style="text-align: center;">some content</p></div></body></html>'
        );

        $result = $this->subject->emogrify();

        static::assertContains('<p style="padding-bottom: 1px; padding-top: 0; text-align: center;">', $result);
    }

    /**
     * Style block CSS overrides values.
     *
     * @test
     */
    public function emogrifyMergesCssWithMixedCaseAttribute()
    {
        $this->subject->setHtml(
            '<html><head><style>#topWrap p {padding-bottom: 3px;PADDING-TOP: 1px;}</style></head>' .
            '<body><div id="topWrap"><p style="text-align: center;">some content</p></div></body></html>'
        );
        $this->subject->setCss('p { margin: 0; padding-TOP: 0; PADDING-bottom: 1PX;}');

        $result = $this->subject->emogrify();

        static::assertContains(
            '<p style="margin: 0; padding-bottom: 3px; padding-top: 1px; text-align: center;">',
            $result
        );
    }

    /**
     * @test
     */
    public function emogrifyMergesCssWithMixedUnits()
    {
        $this->subject->setHtml(
            '<html><head><style>#topWrap p {margin:0;padding-bottom: 1px;}</style></head>' .
            '<body><div id="topWrap"><p style="text-align: center;">some content</p></div></body></html>'
        );
        $this->subject->setCss('p { margin: 1px; padding-bottom:0;}');

        $result = $this->subject->emogrify();

        static::assertContains('<p style="margin: 0; padding-bottom: 1px; text-align: center;">', $result);
    }

    /**
     * @test
     */
    public function emogrifyByDefaultRemovesElementsWithDisplayNoneFromExternalCss()
    {
        $this->subject->setHtml('<html><body><div class="foo"></div></body></html>');
        $this->subject->setCss('div.foo { display: none; }');

        $result = $this->subject->emogrify();

        static::assertNotContains('<div class="foo"></div>', $result);
    }

    /**
     * @test
     */
    public function emogrifyByDefaultRemovesElementsWithDisplayNoneInStyleAttribute()
    {
        $this->subject->setHtml(
            '<html><body><div class="foobar" style="display: none;"></div>' .
            '</body></html>'
        );

        $result = $this->subject->emogrify();

        static::assertNotContains('<div', $result);
    }

    /**
     * @test
     */
    public function emogrifyAfterDisableInvisibleNodeRemovalPreservesInvisibleElements()
    {
        $this->subject->setHtml('<html><body><div class="foo"></div></body></html>');
        $this->subject->setCss('div.foo { display: none; }');

        $this->subject->disableInvisibleNodeRemoval();
        $result = $this->subject->emogrify();

        static::assertContains('<div class="foo" style="display: none;">', $result);
    }

    /**
     * @test
     */
    public function emogrifyKeepsCssMediaQueriesWithCssCommentAfterMediaQuery()
    {
        $this->subject->setHtml('<html><body></body></html>');
        $this->subject->setCss(
            '@media only screen and (max-width: 480px) { body { color: #ffffff } /* some comment */ }'
        );

        $result = $this->subject->emogrify();

        static::assertContains('@media only screen and (max-width: 480px)', $result);
    }

    /**
     * @test
     *
     * @param string $documentType
     *
     * @dataProvider documentTypeDataProvider
     */
    public function emogrifyConvertsXmlSelfClosingTagsToNonXmlSelfClosingTag($documentType)
    {
        $this->subject->setHtml(
            $documentType . '<html><body><br/></body></html>'
        );

        $result = $this->subject->emogrify();

        static::assertContains('<br>', $result);
    }

    /**
     * @test
     */
    public function emogrifyAutomaticallyClosesUnclosedTag()
    {
        $this->subject->setHtml('<html><body><p></body></html>');

        $result = $this->subject->emogrify();

        static::assertContains('<body><p></p></body>', $result);
    }

    /**
     * @test
     */
    public function emogrifyReturnsCompleteHtmlDocument()
    {
        $this->subject->setHtml('<html><body><p></p></body></html>');

        $result = $this->subject->emogrify();

        static::assertSame(
            $this->html5DocumentType . "\n" .
            "<html>\n" .
            '<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>' . "\n" .
            "<body><p></p></body>\n" .
            "</html>\n",
            $result
        );
    }

    /**
     * @test
     */
    public function emogrifyBodyContentReturnsBodyContentFromHtml()
    {
        $this->subject->setHtml('<html><body><p></p></body></html>');

        $result = $this->subject->emogrifyBodyContent();

        static::assertSame('<p></p>', $result);
    }

    /**
     * @test
     */
    public function emogrifyBodyContentReturnsBodyContentFromPartialContent()
    {
        $this->subject->setHtml('<p></p>');

        $result = $this->subject->emogrifyBodyContent();

        static::assertSame('<p></p>', $result);
    }

    /**
     * Sets HTML of subject to boilerplate HTML with a single `<p>` in `<body>` and empty `<head>`
     *
     * @param string $style Optional value for the style attribute of the `<p>` element
     *
     * @return void
     */
    private function setSubjectBoilerplateHtml($style = '')
    {
        $html = '<html><head></head><body><p';
        if ($style !== '') {
            $html .= ' style="' . $style . '"';
        }
        $html .= '>some content</p></body></html>';
        $this->subject->setHtml($html);
    }

    /**
     * @test
     */
    public function importantInExternalCssOverwritesInlineCss()
    {
        $this->setSubjectBoilerplateHtml('margin: 2px;');
        $this->subject->setCss('p { margin: 1px !important; }');

        $result = $this->subject->emogrify();

        static::assertContains('<p style="margin: 1px;">', $result);
    }

    /**
     * @test
     */
    public function importantInExternalCssKeepsInlineCssForOtherAttributes()
    {
        $this->setSubjectBoilerplateHtml('margin: 2px; text-align: center;');
        $this->subject->setCss('p { margin: 1px !important; }');

        $result = $this->subject->emogrify();

        static::assertContains('<p style="text-align: center; margin: 1px;">', $result);
    }

    /**
     * @test
     */
    public function importantIsCaseInsensitive()
    {
        $this->setSubjectBoilerplateHtml('margin: 2px;');
        $this->subject->setCss('p { margin: 1px !ImPorTant; }');

        $result = $this->subject->emogrify();

        static::assertContains('<p style="margin: 1px !ImPorTant;">', $result);
    }

    /**
     * @test
     */
    public function secondImportantStyleOverwritesFirstOne()
    {
        $this->setSubjectBoilerplateHtml();
        $this->subject->setCss('p { margin: 1px !important; } p { margin: 2px !important; }');

        $result = $this->subject->emogrify();

        static::assertContains('<p style="margin: 2px;">', $result);
    }

    /**
     * @test
     */
    public function secondNonImportantStyleOverwritesFirstOne()
    {
        $this->setSubjectBoilerplateHtml();
        $this->subject->setCss('p { margin: 1px; } p { margin: 2px; }');

        $result = $this->subject->emogrify();

        static::assertContains('<p style="margin: 2px;">', $result);
    }

    /**
     * @test
     */
    public function secondNonImportantStyleNotOverwritesFirstImportantOne()
    {
        $this->setSubjectBoilerplateHtml();
        $this->subject->setCss('p { margin: 1px !important; } p { margin: 2px; }');

        $result = $this->subject->emogrify();

        static::assertContains('<p style="margin: 1px;">', $result);
    }

    /**
     * @test
     */
    public function emogrifyAppliesLaterShorthandStyleAfterIndividualStyle()
    {
        $this->setSubjectBoilerplateHtml();
        $this->subject->setCss('p { margin-top: 1px; } p { margin: 2px; }');

        $result = $this->subject->emogrify();

        static::assertContains('<p style="margin-top: 1px; margin: 2px;">', $result);
    }

    /**
     * @test
     */
    public function emogrifyAppliesLaterOverridingStyleAfterStyleAfterOverriddenStyle()
    {
        $this->setSubjectBoilerplateHtml();
        $this->subject->setCss('p { margin-top: 1px; } p { margin: 2px; } p { margin-top: 3px; }');

        $result = $this->subject->emogrify();

        static::assertContains('<p style="margin: 2px; margin-top: 3px;">', $result);
    }

    /**
     * @test
     */
    public function emogrifyAppliesInlineOverridingStyleAfterCssStyleAfterOverriddenCssStyle()
    {
        $this->setSubjectBoilerplateHtml('margin-top: 3px;');
        $this->subject->setCss('p { margin-top: 1px; } p { margin: 2px; }');

        $result = $this->subject->emogrify();

        static::assertContains('<p style="margin: 2px; margin-top: 3px;">', $result);
    }

    /**
     * @test
     */
    public function emogrifyAppliesLaterInlineOverridingStyleAfterEarlierInlineStyle()
    {
        $this->setSubjectBoilerplateHtml('margin: 2px; margin-top: 3px;');
        $this->subject->setCss('p { margin-top: 1px; }');

        $result = $this->subject->emogrify();

        static::assertContains('<p style="margin: 2px; margin-top: 3px;">', $result);
    }

    /**
     * @test
     */
    public function irrelevantMediaQueriesAreRemoved()
    {
        $uselessQuery = '@media all and (max-width: 500px) { em { color:red; } }';
        $this->subject->setCss($uselessQuery);
        $this->subject->setHtml('<html><body><p></p></body></html>');

        $result = $this->subject->emogrify();

        static::assertNotContains('@media', $result);
    }

    /**
     * @test
     */
    public function relevantMediaQueriesAreRetained()
    {
        $usefulQuery = '@media all and (max-width: 500px) { p { color:red; } }';
        $this->subject->setCss($usefulQuery);
        $this->subject->setHtml('<html><body><p></p></body></html>');

        $result = $this->subject->emogrify();

        static::assertContainsCss($usefulQuery, $result);
    }

    /**
     * @test
     */
    public function importantStyleRuleFromInlineCssOverwritesImportantStyleRuleFromExternalCss()
    {
        $this->setSubjectBoilerplateHtml('margin: 2px !important; text-align: center;');
        $this->subject->setCss('p { margin: 1px !important; padding: 1px;}');

        $result = $this->subject->emogrify();

        static::assertContains('<p style="padding: 1px; text-align: center; margin: 2px;">', $result);
    }

    /**
     * @test
     */
    public function addExcludedSelectorRemovesMatchingElementsFromEmogrification()
    {
        $this->subject->setHtml('<html><body><p class="x"></p></body></html>');
        $this->subject->setCss('p { margin: 0; }');

        $this->subject->addExcludedSelector('p.x');
        $result = $this->subject->emogrify();

        static::assertContains('<p class="x"></p>', $result);
    }

    /**
     * @test
     */
    public function addExcludedSelectorExcludesMatchingElementEventWithWhitespaceAroundSelector()
    {
        $this->subject->setHtml('<html><body><p class="x"></p></body></html>');
        $this->subject->setCss('p { margin: 0; }');

        $this->subject->addExcludedSelector(' p.x ');
        $result = $this->subject->emogrify();

        static::assertContains('<p class="x"></p>', $result);
    }

    /**
     * @test
     */
    public function addExcludedSelectorKeepsNonMatchingElementsInEmogrification()
    {
        $this->subject->setHtml('<html><body><p></p></body></html>');
        $this->subject->setCss('p { margin: 0; }');

        $this->subject->addExcludedSelector('p.x');
        $result = $this->subject->emogrify();

        static::assertContains('<p style="margin: 0;"></p>', $result);
    }

    /**
     * @test
     */
    public function removeExcludedSelectorGetsMatchingElementsToBeEmogrifiedAgain()
    {
        $this->subject->setHtml('<html><body><p class="x"></p></body></html>');
        $this->subject->setCss('p { margin: 0; }');

        $this->subject->addExcludedSelector('p.x');
        $this->subject->removeExcludedSelector('p.x');

        $result = $this->subject->emogrify();

        static::assertContains('<p class="x" style="margin: 0;"></p>', $result);
    }

    /**
     * @test
     *
     * @expectedException \InvalidArgumentException
     */
    public function emogrifyInDebugModeForInvalidExcludedSelectorThrowsException()
    {
        $this->subject->setDebug(true);

        $this->subject->setHtml('<html></html>');
        $this->subject->addExcludedSelector('..p');

        $this->subject->emogrify();
    }

    /**
     * @test
     */
    public function emogrifyNotInDebugModeIgnoresInvalidExcludedSelector()
    {
        $this->subject->setDebug(false);

        $this->subject->setHtml('<html><p class="x"></p></html>');
        $this->subject->addExcludedSelector('..p');

        $result = $this->subject->emogrify();

        static::assertContains('<p class="x"></p>', $result);
    }

    /**
     * @test
     */
    public function emogrifyNotInDebugModeIgnoresOnlyInvalidExcludedSelector()
    {
        $this->subject->setDebug(false);

        $this->subject->setHtml('<html><p class="x"></p><p class="y"></p><p class="z"></p></html>');
        $this->subject->setCss('p { color: red };');
        $this->subject->addExcludedSelector('p.x');
        $this->subject->addExcludedSelector('..p');
        $this->subject->addExcludedSelector('p.z');

        $result = $this->subject->emogrify();

        static::assertContains('<p class="x"></p>', $result);
        static::assertContains('<p class="y" style="color: red;"></p>', $result);
        static::assertContains('<p class="z"></p>', $result);
    }

    /**
     * @test
     */
    public function emptyMediaQueriesAreRemoved()
    {
        $emptyQuery = '@media all and (max-width: 500px) { }';
        $this->subject->setCss($emptyQuery);
        $this->subject->setHtml('<html><body><p></p></body></html>');

        $result = $this->subject->emogrify();

        static::assertNotContains('@media', $result);
    }

    /**
     * @test
     */
    public function multiLineMediaQueryWithWindowsLineEndingsIsAppliedOnlyOnce()
    {
        $css = "@media all {\r\n" .
            ".medium {font-size:18px;}\r\n" .
            ".small {font-size:14px;}\r\n" .
            '}';
        $this->subject->setCss($css);
        $this->subject->setHtml(
            '<html><body>' .
            '<p class="medium">medium</p>' .
            '<p class="small">small</p>' .
            '</body></html>'
        );

        $result = $this->subject->emogrify();

        static::assertContainsCssCount(1, $css, $result);
    }

    /**
     * @test
     */
    public function multiLineMediaQueryWithUnixLineEndingsIsAppliedOnlyOnce()
    {
        $css = "@media all {\n" .
            ".medium {font-size:18px;}\n" .
            ".small {font-size:14px;}\n" .
            '}';
        $this->subject->setCss($css);
        $this->subject->setHtml(
            '<html><body>' .
            '<p class="medium">medium</p>' .
            '<p class="small">small</p>' .
            '</body></html>'
        );

        $result = $this->subject->emogrify();

        static::assertContainsCssCount(1, $css, $result);
    }

    /**
     * @test
     */
    public function multipleMediaQueriesAreAppliedOnlyOnce()
    {
        $css = "@media all {\n" .
            ".medium {font-size:18px;}\n" .
            ".small {font-size:14px;}\n" .
            '}' .
            "@media screen {\n" .
            ".medium {font-size:24px;}\n" .
            ".small {font-size:18px;}\n" .
            '}';
        $this->subject->setCss($css);
        $this->subject->setHtml(
            '<html><body>' .
            '<p class="medium">medium</p>' .
            '<p class="small">small</p>' .
            '</body></html>'
        );

        $result = $this->subject->emogrify();

        static::assertContainsCssCount(1, $css, $result);
    }

    /**
     * @return string[][]
     */
    public function dataUriMediaTypeDataProvider()
    {
        return [
            'nothing' => [''],
            ';charset=utf-8' => [';charset=utf-8'],
            ';base64' => [';base64'],
            ';charset=utf-8;base64' => [';charset=utf-8;base64'],
        ];
    }

    /**
     * @test
     *
     * @param string $dataUriMediaType
     *
     * @dataProvider dataUriMediaTypeDataProvider
     */
    public function dataUrisAreConserved($dataUriMediaType)
    {
        $this->subject->setHtml('<html></html>');
        $styleRule = 'background-image: url(data:image/png' . $dataUriMediaType .
            ',iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAIAAAAC64paAAABUk' .
            'lEQVQ4y81UsY6CQBCdWXBjYWFMjEgAE0piY8c38B9+iX+ksaHCgs5YWEhIrJCQYGJBomiC7lzhVcfqEa+5KXfey3s783bRdd00TR' .
            'VFAQAAICJEhN/q8Xjoug7D4RA+qsFgwDjn9QYiTiaT+Xx+OByOx+NqtapjWq0WjEajekPTtCAIiIiIyrKMoqiOMQxDlVqyLMt1XQ' .
            'A4nU6z2Wy9XkthEnK/3zdN8znC/X7v+36WZfJ7120vFos4joUQRHS5XDabzXK5bGrbtu1er/dtTFU1TWu3202VHceZTqe3242Itt' .
            'ut53nj8bip8m6345wLIQCgKIowDIuikAoz6Wm3233mjHPe6XRe5UROJqImIWPwh/pvZMbYM2GKorx5oUw6m+v1miTJ+XzO8/x+v7' .
            '+UtizrM8+GYahVVSFik9/jxy6rqlJN02SM1cmI+GbbQghd178AAO2FXws6LwMAAAAASUVORK5CYII=);';
        $this->subject->setCss('html {' . $styleRule . '}');

        $result = $this->subject->emogrify();

        static::assertContains(
            '<html style="' . $styleRule . '">',
            $result
        );
    }

    /**
     * Data provider for CSS to HTML mapping.
     *
     * @return string[][]
     */
    public function matchingCssToHtmlMappingDataProvider()
    {
        return [
            'background-color => bgcolor'
            => ['<p>hi</p>', 'p {background-color: red;}', 'p', 'bgcolor="red"'],
            'background-color (with !important) => bgcolor'
            => ['<p>hi</p>', 'p {background-color: red !important;}', 'p', 'bgcolor="red"'],
            'p.text-align => align'
            => ['<p>hi</p>', 'p {text-align: justify;}', 'p', 'align="'],
            'div.text-align => align'
            => ['<div>hi</div>', 'div {text-align: justify;}', 'div', 'align="'],
            'td.text-align => align'
            => ['<table><tr><td>hi</td></tr></table>', 'td {text-align: justify;}', 'td', 'align="'],
            'text-align: left => align=left'
            => ['<p>hi</p>', 'p {text-align: left;}', 'p', 'align="left"'],
            'text-align: right => align=right'
            => ['<p>hi</p>', 'p {text-align: right;}', 'p', 'align="right"'],
            'text-align: center => align=center'
            => ['<p>hi</p>', 'p {text-align: center;}', 'p', 'align="center"'],
            'text-align: justify => align:justify'
            => ['<p>hi</p>', 'p {text-align: justify;}', 'p', 'align="justify"'],
            'img.float: right => align=right'
            => ['<img>', 'img {float: right;}', 'img', 'align="right"'],
            'img.float: left => align=left'
            => ['<img>', 'img {float: left;}', 'img', 'align="left"'],
            'table.float: right => align=right'
            => ['<table></table>', 'table {float: right;}', 'table', 'align="right"'],
            'table.float: left => align=left'
            => ['<table></table>', 'table {float: left;}', 'table', 'align="left"'],
            'table.border-spacing: 0 => cellspacing=0'
            => ['<table><tr><td></td></tr></table>', 'table {border-spacing: 0;}', 'table', 'cellspacing="0"'],
            'background => bgcolor'
            => ['<p>Bonjour</p>', 'p {background: red top;}', 'p', 'bgcolor="red"'],
            'width with px'
            => ['<p>Hello</p>', 'p {width: 100px;}', 'p', 'width="100"'],
            'width with %'
            => ['<p>Hello</p>', 'p {width: 50%;}', 'p', 'width="50%"'],
            'height with px'
            => ['<p>Hello</p>', 'p {height: 100px;}', 'p', 'height="100"'],
            'height with %'
            => ['<p>Hello</p>', 'p {height: 50%;}', 'p', 'height="50%"'],
            'img.margin: 0 auto (= horizontal centering) => align=center'
            => ['<img>', 'img {margin: 0 auto;}', 'img', 'align="center"'],
            'img.margin: auto (= horizontal centering) => align=center'
            => ['<img>', 'img {margin: auto;}', 'img', 'align="center"'],
            'img.margin: 10 auto 30 auto (= horizontal centering) => align=center'
            => ['<img>', 'img {margin: 10 auto 30 auto;}', 'img', 'align="center"'],
            'table.margin: 0 auto (= horizontal centering) => align=center'
            => ['<table></table>', 'table {margin: 0 auto;}', 'table', 'align="center"'],
            'table.margin: auto (= horizontal centering) => align=center'
            => ['<table></table>', 'table {margin: auto;}', 'table', 'align="center"'],
            'table.margin: 10 auto 30 auto (= horizontal centering) => align=center'
            => ['<table></table>', 'table {margin: 10 auto 30 auto;}', 'table', 'align="center"'],
            'img.border: none => border=0'
            => ['<img>', 'img {border: none;}', 'img', 'border="0"'],
            'img.border: 0 => border=0'
            => ['<img>', 'img {border: none;}', 'img', 'border="0"'],
            'table.border: none => border=0'
            => ['<table></table>', 'table {border: none;}', 'table', 'border="0"'],
            'table.border: 0 => border=0'
            => ['<table></table>', 'table {border: none;}', 'table', 'border="0"'],
        ];
    }

    /**
     * @test
     *
     * @param string $body The HTML
     * @param string $css The complete CSS
     * @param string $tagName The name of the tag that should be modified
     * @param string $attributes The attributes that are expected on the element
     *
     * @dataProvider matchingCssToHtmlMappingDataProvider
     */
    public function emogrifierMapsSuitableCssToHtmlIfFeatureIsEnabled($body, $css, $tagName, $attributes)
    {
        $this->subject->setHtml('<html><body>' . $body . '</body></html>');
        $this->subject->setCss($css);

        $this->subject->enableCssToHtmlMapping();
        $html = $this->subject->emogrify();

        static::assertRegExp('/<' . \preg_quote($tagName, '/') . '[^>]+' . \preg_quote($attributes, '/') . '/', $html);
    }

    /**
     * Data provider for CSS to HTML mapping.
     *
     * @return string[][]
     */
    public function notMatchingCssToHtmlMappingDataProvider()
    {
        return [
            'background URL'
            => ['<p>Hello</p>', 'p {background: url(bg.png);}', 'bgcolor'],
            'background URL with position'
            => ['<p>Hello</p>', 'p {background: url(bg.png) top;}', 'bgcolor'],
            'img.margin: 10 5 30 auto (= no horizontal centering)'
            => ['<img>', 'img {margin: 10 5 30 auto;}', 'align'],
            'p.margin: auto'
            => ['<p>Bonjour</p>', 'p {margin: auto;}', 'align'],
            'p.border: none'
            => ['<p>Bonjour</p>', 'p {border: none;}', 'border'],
            'img.border: 1px solid black'
            => ['<p>Bonjour</p>', 'p {border: 1px solid black;}', 'border'],
            'span.text-align'
            => ['<span>hi</span>', 'span {text-align: justify;}', 'align'],
            'text-align: inherit'
            => ['<p>hi</p>', 'p {text-align: inherit;}', 'align'],
            'span.float'
            => ['<span>hi</span>', 'span {float: right;}', 'align'],
            'float: none'
            => ['<table></table>', 'table {float: none;}', 'align'],
            'p.border-spacing'
            => ['<p>Hello</p>', 'p {border-spacing: 5px;}', 'cellspacing'],
            'height: auto'
            => ['<img src="logo.png" alt="">', 'img {width: 110px; height: auto;}', 'height'],
            'width: auto'
            => ['<img src="logo.png" alt="">', 'img {width: auto; height: 110px;}', 'width'],
        ];
    }

    /**
     * @test
     *
     * @param string $body the HTML
     * @param string $css the complete CSS
     * @param string $attribute the attribute that must not be present on this element
     *
     * @dataProvider notMatchingCssToHtmlMappingDataProvider
     */
    public function emogrifierNotMapsUnsuitableCssToHtmlIfFeatureIsEnabled($body, $css, $attribute)
    {
        $this->subject->setHtml('<html><body>' . $body . '</body></html>');
        $this->subject->setCss($css);

        $this->subject->enableCssToHtmlMapping();
        $html = $this->subject->emogrify();

        static::assertNotContains(
            $attribute . '=',
            $html
        );
    }

    /**
     * @test
     */
    public function emogrifierNotMapsCssToHtmlIfFeatureIsNotEnabled()
    {
        $this->subject->setHtml('<html><body><img></body></html>');
        $this->subject->setCss('img {float: right;}');

        $html = $this->subject->emogrify();

        static::assertNotContains(
            'align=',
            $html
        );
    }

    /**
     * @test
     */
    public function emogrifierIgnoresPseudoClassCombinedWithPseudoElement()
    {
        $this->subject->setHtml('<html><body><div></div></body></html>');
        $this->subject->setCss('div:last-child::after {float: right;}');

        $html = $this->subject->emogrify();

        static::assertContains('<div></div>', $html);
    }

    /**
     * @test
     */
    public function emogrifyKeepsInlineStylePriorityVersusStyleBlockRules()
    {
        $this->subject->setHtml(
            '<html><head><style>p {padding:10px};</style></head><body><p style="padding-left:20px;"></p></body></html>'
        );

        $result = $this->subject->emogrify();

        static::assertContains('<p style="padding: 10px; padding-left: 20px;">', $result);
    }

    /**
     * @test
     */
    public function emogrifyMovesStyleElementFromHeadToBody()
    {
        $style = '<style type="text/css">@media all { html {  color: red; } }</style>';
        $html = '<html><head>' . $style . '</head></html>';
        $this->subject->setHtml($html);

        $result = $this->subject->emogrify();

        static::assertContainsCss(
            '<body>' . $style . '</body>',
            $result
        );
    }

    /**
     * Asserts that $html contains a $tagName tag with the $attribute attribute.
     *
     * @param string $html the HTML string we are searching in
     * @param string $tagName the HTML tag we are looking for
     * @param string $attribute the attribute we are looking for (with or even without a value)
     */
    private function assertHtmlStringContainsTagWithAttribute($html, $tagName, $attribute)
    {
        static::assertTrue(
            \preg_match('/<' . \preg_quote($tagName, '/') . '[^>]+' . \preg_quote($attribute, '/') . '/', $html) > 0
        );
    }

    /**
     * @test
     */
    public function emogrifyPrefersInlineStyleOverCssBlockStyleForHtmlAttributesMapping()
    {
        $this->subject->setHtml(
            '<html><head><style>p {width:1px}</style></head><body><p style="width:2px"></p></body></html>'
        );
        $this->subject->enableCssToHtmlMapping();

        $result = $this->subject->emogrify();

        $this->assertHtmlStringContainsTagWithAttribute($result, 'p', 'width="2"');
    }

    /**
     * @test
     */
    public function emogrifyCorrectsHtmlAttributesMappingWhenMultipleMatchingRulesAndLastRuleIsAuto()
    {
        $this->subject->setHtml(
            '<html><head><style>p {width:1px}</style></head><body><p class="autoWidth"></p></body></html>'
        );
        $this->subject->setCss('p.autoWidth {width:auto}');
        $this->subject->enableCssToHtmlMapping();

        $result = $this->subject->emogrify();

        static::assertContains('<p class="autoWidth" style="width: auto;">', $result);
    }

    /**
     * @return string[][]
     */
    public function cssForImportantRuleRemovalDataProvider()
    {
        return [
            'one !important rule only' => [
                'width: 1px !important',
                'width: 1px;',
            ],
            'multiple !important rules only' => [
                'width: 1px !important; height: 1px !important',
                'width: 1px; height: 1px;',
            ],
            'multiple declarations, one !important rule at the beginning' => [
                'width: 1px !important; height: 1px; color: red',
                'height: 1px; color: red; width: 1px;',
            ],
            'multiple declarations, one !important rule somewhere in the middle' => [
                'height: 1px; width: 1px !important; color: red',
                'height: 1px; color: red; width: 1px;',
            ],
            'multiple declarations, one !important rule at the end' => [
                'height: 1px; color: red; width: 1px !important',
                'height: 1px; color: red; width: 1px;',
            ],
            'multiple declarations, multiple !important rules at the beginning' => [
                'width: 1px !important; height: 1px !important; color: red; float: left',
                'color: red; float: left; width: 1px; height: 1px;',
            ],
            'multiple declarations, multiple consecutive !important rules somewhere in the middle (#1)' => [
                'color: red; width: 1px !important; height: 1px !important; float: left',
                'color: red; float: left; width: 1px; height: 1px;',
            ],
            'multiple declarations, multiple consecutive !important rules somewhere in the middle (#2)' => [
                'color: red; width: 1px !important; height: 1px !important; float: left; clear: both',
                'color: red; float: left; clear: both; width: 1px; height: 1px;',
            ],
            'multiple declarations, multiple not consecutive !important rules somewhere in the middle' => [
                'color: red; width: 1px !important; clear: both; height: 1px !important; float: left',
                'color: red; clear: both; float: left; width: 1px; height: 1px;',
            ],
            'multiple declarations, multiple !important rules at the end' => [
                'color: red; float: left; width: 1px !important; height: 1px !important',
                'color: red; float: left; width: 1px; height: 1px;',
            ],
        ];
    }

    /**
     * @test
     *
     * @param string $originalStyleAttributeContent
     * @param string $expectedStyleAttributeContent
     *
     * @dataProvider cssForImportantRuleRemovalDataProvider
     */
    public function emogrifyRemovesImportantRule($originalStyleAttributeContent, $expectedStyleAttributeContent)
    {
        $this->subject->setHtml(
            '<html><head><body><p style="' . $originalStyleAttributeContent . '"></p></body></html>'
        );

        $result = $this->subject->emogrify();

        static::assertContains('<p style="' . $expectedStyleAttributeContent . '">', $result);
    }

    /**
     * @test
     *
     * @expectedException \InvalidArgumentException
     */
    public function emogrifyInDebugModeForInvalidSelectorsInMediaQueryBlocksThrowsException()
    {
        $this->subject->setDebug(true);

        $this->subject->setHtml('<html></html>');
        $this->subject->setCss('@media screen {p^^ {color: red;}}');

        $this->subject->emogrify();
    }

    /**
     * @test
     */
    public function emogrifyNotInDebugModeKeepsInvalidOrUnrecognizedSelectorsInMediaQueryBlocks()
    {
        $this->subject->setDebug(false);

        $this->subject->setHtml('<html></html>');
        $css = '@media screen {p^^ {color: red;}}';
        $this->subject->setCss($css);

        $result = $this->subject->emogrify();

        static::assertContainsCss($css, $result);
    }
}

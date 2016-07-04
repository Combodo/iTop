<?php
namespace Pelago\Tests\Unit;

use Pelago\Emogrifier;
/**
 * Test case.
 *
 * @author Oliver Klee <typo3-coding@oliverklee.de>
 */
class EmogrifierTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    const LF = '
';
    /**
     * @var string
     */
    private $html4TransitionalDocumentType = '';
    /**
     * @var string
     */
    private $xhtml1StrictDocumentType = '';
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
        $this->html4TransitionalDocumentType = '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" ' . '"http://www.w3.org/TR/REC-html40/loose.dtd">';
        $this->xhtml1StrictDocumentType = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" ' . '"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
        $this->subject = new Emogrifier();
    }
    /**
     * @test
     *
     * @expectedException \BadMethodCallException
     */
    public function emogrifyForNoDataSetReturnsThrowsException()
    {
        $this->subject->emogrify();
    }
    /**
     * @test
     *
     * @expectedException \BadMethodCallException
     */
    public function emogrifyForEmptyHtmlAndEmptyCssThrowsException()
    {
        $this->subject->setHtml('');
        $this->subject->setCss('');
        $this->subject->emogrify();
    }
    /**
     * @test
     *
     * @expectedException \BadMethodCallException
     */
    public function emogrifyBodyContentForNoDataSetReturnsThrowsException()
    {
        $this->subject->emogrifyBodyContent();
    }
    /**
     * @test
     *
     * @expectedException \BadMethodCallException
     */
    public function emogrifyBodyContentForEmptyHtmlAndEmptyCssThrowsException()
    {
        $this->subject->setHtml('');
        $this->subject->setCss('');
        $this->subject->emogrifyBodyContent();
    }
    /**
     * @test
     */
    public function emogrifyAddsHtmlTagIfNoHtmlTagAndNoHeadTagAreProvided()
    {
        $this->subject->setHtml('<p>Hello</p>');
        $emogrifiedHtml = $this->subject->emogrify();
        self::assertContains('<html>', $emogrifiedHtml);
    }
    /**
     * @test
     */
    public function emogrifyAddsHtmlTagIfHeadTagIsProvidedButNoHtmlTaqg()
    {
        $this->subject->setHtml('<head><title>Hello</title></head><p>World</p>');
        $emogrifiedHtml = $this->subject->emogrify();
        self::assertContains('<html>', $emogrifiedHtml);
    }
    /**
     * @test
     */
    public function emogrifyAddsHeadTagIfNoHtmlTagAndNoHeadTagAreProvided()
    {
        $this->subject->setHtml('<p>Hello</p>');
        $emogrifiedHtml = $this->subject->emogrify();
        self::assertContains('<head>', $emogrifiedHtml);
    }
    /**
     * @test
     */
    public function emogrifyAddsHtmlTagIfHtmlTagIsProvidedButNoHeadTaqg()
    {
        $this->subject->setHtml('<html></head><p>World</p></html>');
        $emogrifiedHtml = $this->subject->emogrify();
        self::assertContains('<head>', $emogrifiedHtml);
    }
    /**
     * @test
     */
    public function emogrifyKeepsDollarSignsAndSquareBrackets()
    {
        $templateMarker = '$[USER:NAME]$';
        $html = $this->html5DocumentType . '<html><p>' . $templateMarker . '</p></html>';
        $this->subject->setHtml($html);
        self::assertContains($templateMarker, $this->subject->emogrify());
    }
    /**
     * @test
     */
    public function emogrifyKeepsUtf8UmlautsInHtml5()
    {
        $umlautString = 'Küss die Hand, schöne Frau.';
        $html = $this->html5DocumentType . '<html><p>' . $umlautString . '</p></html>';
        $this->subject->setHtml($html);
        self::assertContains($umlautString, $this->subject->emogrify());
    }
    /**
     * @test
     */
    public function emogrifyKeepsUtf8UmlautsInXhtml()
    {
        $umlautString = 'Öösel läks õunu täis ämber uhkelt ümber.';
        $html = $this->xhtml1StrictDocumentType . '<html<p>' . $umlautString . '</p></html>';
        $this->subject->setHtml($html);
        self::assertContains($umlautString, $this->subject->emogrify());
    }
    /**
     * @test
     */
    public function emogrifyKeepsUtf8UmlautsInHtml4()
    {
        $umlautString = 'Öösel läks õunu täis ämber uhkelt ümber.';
        $html = $this->html4TransitionalDocumentType . '<html><p>' . $umlautString . '</p></html>';
        $this->subject->setHtml($html);
        self::assertContains($umlautString, $umlautString, $this->subject->emogrify());
    }
    /**
     * @test
     */
    public function emogrifyKeepsHtmlEntities()
    {
        $entityString = 'a &amp; b &gt; c';
        $html = $this->html5DocumentType . '<html><p>' . $entityString . '</p></html>';
        $this->subject->setHtml($html);
        self::assertContains($entityString, $this->subject->emogrify());
    }
    /**
     * @test
     */
    public function emogrifyKeepsHtmlEntitiesInXhtml()
    {
        $entityString = 'a &amp; b &gt; c';
        $html = $this->xhtml1StrictDocumentType . '<html<p>' . $entityString . '</p></html>';
        $this->subject->setHtml($html);
        self::assertContains($entityString, $this->subject->emogrify());
    }
    /**
     * @test
     */
    public function emogrifyKeepsHtmlEntitiesInHtml4()
    {
        $entityString = 'a &amp; b &gt; c';
        $html = $this->html4TransitionalDocumentType . '<html><p>' . $entityString . '</p></html>';
        $this->subject->setHtml($html);
        self::assertContains($entityString, $entityString, $this->subject->emogrify());
    }
    /**
     * @test
     */
    public function emogrifyKeepsUtf8UmlautsWithoutDocumentType()
    {
        $umlautString = 'Küss die Hand, schöne Frau.';
        $html = '<html><head></head><p>' . $umlautString . '</p></html>';
        $this->subject->setHtml($html);
        self::assertContains($umlautString, $this->subject->emogrify());
    }
    /**
     * @test
     */
    public function emogrifyKeepsUtf8UmlautsWithoutDocumentTypeAndWithoutHtmlAndWithoutHead()
    {
        $umlautString = 'Küss die Hand, schöne Frau.';
        $html = '<p>' . $umlautString . '</p>';
        $this->subject->setHtml($html);
        self::assertContains($umlautString, $this->subject->emogrify());
    }
    /**
     * @test
     */
    public function emogrifyKeepsUtf8UmlautsWithoutDocumentTypeAndWithHtmlAndWithoutHead()
    {
        $umlautString = 'Küss die Hand, schöne Frau.';
        $html = '<html><p>' . $umlautString . '</p></html>';
        $this->subject->setHtml($html);
        self::assertContains($umlautString, $this->subject->emogrify());
    }
    /**
     * @test
     */
    public function emogrifyKeepsUtf8UmlautsWithoutDocumentTypeAndWithoutHtmlAndWithHead()
    {
        $umlautString = 'Küss die Hand, schöne Frau.';
        $html = '<head></head><p>' . $umlautString . '</p>';
        $this->subject->setHtml($html);
        self::assertContains($umlautString, $this->subject->emogrify());
    }
    /**
     * @test
     */
    public function emogrifyForHtmlTagOnlyAndEmptyCssByDefaultAddsHtml5DocumentType()
    {
        $html = '<html></html>';
        $this->subject->setHtml($html);
        $this->subject->setCss('');
        self::assertContains($this->html5DocumentType, $this->subject->emogrify());
    }
    /**
     * @test
     */
    public function emogrifyForHtmlTagWithXhtml1StrictDocumentTypeKeepsDocumentType()
    {
        $html = $this->xhtml1StrictDocumentType . '<html></html>';
        $this->subject->setHtml($html);
        self::assertContains($this->xhtml1StrictDocumentType, $this->subject->emogrify());
    }
    /**
     * @test
     */
    public function emogrifyForHtmlTagWithXhtml5DocumentTypeKeepsDocumentType()
    {
        $html = $this->html5DocumentType . '<html></html>';
        $this->subject->setHtml($html);
        self::assertContains($this->html5DocumentType, $this->subject->emogrify());
    }
    /**
     * @test
     */
    public function emogrifyAddsContentTypeMetaTag()
    {
        $html = $this->html5DocumentType . '<p>Hello</p>';
        $this->subject->setHtml($html);
        self::assertContains('<meta http-equiv="Content-Type" content="text/html; charset=utf-8">', $this->subject->emogrify());
    }
    /**
     * @test
     */
    public function emogrifyForExistingContentTypeMetaTagNotAddsSecondContentTypeMetaTag()
    {
        $html = $this->html5DocumentType . '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>' . '<body><p>Hello</p></body></html>';
        $this->subject->setHtml($html);
        $numberOfContentTypeMetaTags = substr_count($this->subject->emogrify(), 'Content-Type');
        self::assertSame(1, $numberOfContentTypeMetaTags);
    }
    /**
     * @test
     */
    public function emogrifyByDefaultRemovesWbrTag()
    {
        $html = $this->html5DocumentType . '<html>foo<wbr/>bar</html>';
        $this->subject->setHtml($html);
        self::assertContains('foobar', $this->subject->emogrify());
    }
    /**
     * @test
     */
    public function addUnprocessableTagCausesGivenEmptyTagToBeRemoved()
    {
        $this->subject->addUnprocessableHtmlTag('p');
        $html = $this->html5DocumentType . '<html><p></p></html>';
        $this->subject->setHtml($html);
        self::assertNotContains('<p>', $this->subject->emogrify());
    }
    /**
     * @test
     */
    public function addUnprocessableTagNotRemovesGivenTagWithContent()
    {
        $this->subject->addUnprocessableHtmlTag('p');
        $html = $this->html5DocumentType . '<html><p>foobar</p></html>';
        $this->subject->setHtml($html);
        self::assertContains('<p>', $this->subject->emogrify());
    }
    /**
     * @test
     */
    public function removeUnprocessableHtmlTagCausesTagToStayAgain()
    {
        $this->subject->addUnprocessableHtmlTag('p');
        $this->subject->removeUnprocessableHtmlTag('p');
        $html = $this->html5DocumentType . '<html><p>foo<br/><span>bar</span></p></html>';
        $this->subject->setHtml($html);
        self::assertContains('<p>', $this->subject->emogrify());
    }
    /**
     * @test
     */
    public function emogrifyCanAddMatchingElementRuleOnHtmlElementFromCss()
    {
        $html = $this->html5DocumentType . '<html></html>';
        $this->subject->setHtml($html);
        $styleRule = 'color: #000;';
        $this->subject->setCss('html {' . $styleRule . '}');
        self::assertContains('<html style="' . $styleRule . '">', $this->subject->emogrify());
    }
    /**
     * @test
     */
    public function emogrifyNotAddsNotMatchingElementRuleOnHtmlElementFromCss()
    {
        $html = $this->html5DocumentType . '<html></html>';
        $this->subject->setHtml($html);
        $this->subject->setCss('p {color:#000;}');
        self::assertContains('<html>', $this->subject->emogrify());
    }
    /**
     * @test
     */
    public function emogrifyCanMatchTwoElements()
    {
        $html = $this->html5DocumentType . '<html><p></p><p></p></html>';
        $this->subject->setHtml($html);
        $styleRule = 'color: #000;';
        $this->subject->setCss('p {' . $styleRule . '}');
        self::assertSame(2, substr_count($this->subject->emogrify(), '<p style="' . $styleRule . '">'));
    }
    /**
     * @test
     */
    public function emogrifyCanAssignTwoStyleRulesFromSameMatcherToElement()
    {
        $html = $this->html5DocumentType . '<html><p></p></html>';
        $this->subject->setHtml($html);
        $styleRulesIn = 'color:#000; text-align:left;';
        $styleRulesOut = 'color: #000; text-align: left;';
        $this->subject->setCss('p {' . $styleRulesIn . '}');
        self::assertContains('<p style="' . $styleRulesOut . '">', $this->subject->emogrify());
    }
    /**
     * @test
     */
    public function emogrifyCanMatchAttributeOnlySelector()
    {
        $html = $this->html5DocumentType . '<html><p hidden="hidden"></p></html>';
        $this->subject->setHtml($html);
        $this->subject->setCss('[hidden] { color:red; }');
        self::assertContains('<p hidden="hidden" style="color: red;">', $this->subject->emogrify());
    }
    /**
     * @test
     */
    public function emogrifyCanAssignStyleRulesFromTwoIdenticalMatchersToElement()
    {
        $html = $this->html5DocumentType . '<html><p></p></html>';
        $this->subject->setHtml($html);
        $styleRule1 = 'color: #000;';
        $styleRule2 = 'text-align: left;';
        $this->subject->setCss('p {' . $styleRule1 . '}  p {' . $styleRule2 . '}');
        self::assertContains('<p style="' . $styleRule1 . ' ' . $styleRule2 . '">', $this->subject->emogrify());
    }
    /**
     * @test
     */
    public function emogrifyCanAssignStyleRulesFromTwoDifferentMatchersToElement()
    {
        $html = $this->html5DocumentType . '<html><p class="x"></p></html>';
        $this->subject->setHtml($html);
        $styleRule1 = 'color: #000;';
        $styleRule2 = 'text-align: left;';
        $this->subject->setCss('p {' . $styleRule1 . '} .x {' . $styleRule2 . '}');
        self::assertContains('<p class="x" style="' . $styleRule1 . ' ' . $styleRule2 . '">', $this->subject->emogrify());
    }
    /**
     * Data provide for selectors.
     *
     * @return string[][]
     */
    public function selectorDataProvider()
    {
        $styleRule = 'color: red;';
        $styleAttribute = 'style="' . $styleRule . '"';
        return array('universal selector HTML' => array('* {' . $styleRule . '} ', '#<html id="html" ' . $styleAttribute . '>#'), 'universal selector BODY' => array('* {' . $styleRule . '} ', '#<body ' . $styleAttribute . '>#'), 'universal selector P' => array('* {' . $styleRule . '} ', '#<p[^>]*' . $styleAttribute . '>#'), 'type selector matches first P' => array('p {' . $styleRule . '} ', '#<p class="p-1" ' . $styleAttribute . '>#'), 'type selector matches second P' => array('p {' . $styleRule . '} ', '#<p class="p-2" ' . $styleAttribute . '>#'), 'descendant selector P SPAN' => array('p span {' . $styleRule . '} ', '#<span ' . $styleAttribute . '>#'), 'descendant selector BODY SPAN' => array('body span {' . $styleRule . '} ', '#<span ' . $styleAttribute . '>#'), 'child selector P > SPAN matches direct child' => array('p > span {' . $styleRule . '} ', '#<span ' . $styleAttribute . '>#'), 'child selector BODY > SPAN not matches grandchild' => array('body > span {' . $styleRule . '} ', '#<span>#'), 'adjacent selector P + P not matches first P' => array('p + p {' . $styleRule . '} ', '#<p class="p-1">#'), 'adjacent selector P + P matches second P' => array('p + p {' . $styleRule . '} ', '#<p class="p-2" style="' . $styleRule . '">#'), 'adjacent selector P + P matches third P' => array('p + p {' . $styleRule . '} ', '#<p class="p-3" style="' . $styleRule . '">#'), 'ID selector #HTML' => array('#html {' . $styleRule . '} ', '#<html id="html" ' . $styleAttribute . '>#'), 'type and ID selector HTML#HTML' => array('html#html {' . $styleRule . '} ', '#<html id="html" ' . $styleAttribute . '>#'), 'class selector .P-1' => array('.p-1 {' . $styleRule . '} ', '#<p class="p-1" ' . $styleAttribute . '>#'), 'type and class selector P.P-1' => array('p.p-1 {' . $styleRule . '} ', '#<p class="p-1" ' . $styleAttribute . '>#'), 'attribute presence selector SPAN[title] matches element with matching attribute' => array('span[title] {' . $styleRule . '} ', '#<span title="bonjour" ' . $styleAttribute . '>#'), 'attribute presence selector SPAN[title] not matches element without any attributes' => array('span[title] {' . $styleRule . '} ', '#<span>#'), 'attribute value selector [id="html"] matches element with matching attribute value' => array('[id="html"] {' . $styleRule . '} ', '#<html id="html" ' . $styleAttribute . '>#'), 'attribute value selector SPAN[title] matches element with matching attribute value' => array('span[title="bonjour"] {' . $styleRule . '} ', '#<span title="bonjour" ' . $styleAttribute . '>#'), 'attribute value selector SPAN[title] not matches element with other attribute value' => array('span[title="bonjour"] {' . $styleRule . '} ', '#<span title="buenas dias">#'), 'attribute value selector SPAN[title] not matches element without any attributes' => array('span[title="bonjour"] {' . $styleRule . '} ', '#<span>#'), 'BODY:first-child matches first child' => array('body:first-child {' . $styleRule . '} ', '#<p class="p-1" style="' . $styleRule . '">#'), 'BODY:first-child not matches middle child' => array('body:first-child {' . $styleRule . '} ', '#<p class="p-2">#'), 'BODY:first-child not matches last child' => array('body:first-child {' . $styleRule . '} ', '#<p class="p-3">#'), 'BODY:last-child not matches first child' => array('body:last-child {' . $styleRule . '} ', '#<p class="p-1">#'), 'BODY:last-child not matches middle child' => array('body:last-child {' . $styleRule . '} ', '#<p class="p-2">#'), 'BODY:last-child matches last child' => array('body:last-child {' . $styleRule . '} ', '#<p class="p-3" style="' . $styleRule . '">#'));
    }
    /**
     * @test
     *
     * @param string $css the complete CSS
     * @param string $htmlRegularExpression regular expression for the the HTML that needs to be contained in the HTML
     *
     * @dataProvider selectorDataProvider
     */
    public function emogrifierMatchesSelectors($css, $htmlRegularExpression)
    {
        $html = $this->html5DocumentType . '<html id="html">' . '  <body>' . '    <p class="p-1"><span>some text</span></p>' . '    <p class="p-2"><span title="bonjour">some</span> text</p>' . '    <p class="p-3"><span title="buenas dias">some</span> more text</p>' . '  </body>' . '</html>';
        $this->subject->setHtml($html);
        $this->subject->setCss($css);
        $result = $this->subject->emogrify();
        self::assertRegExp($htmlRegularExpression, $result);
    }
    /**
     * Data provider for emogrifyDropsWhitespaceFromCssDeclarations.
     *
     * @return string[][]
     */
    public function cssDeclarationWhitespaceDroppingDataProvider()
    {
        return array('no whitespace, trailing semicolon' => array('color:#000;', 'color: #000;'), 'no whitespace, no trailing semicolon' => array('color:#000', 'color: #000;'), 'space after colon, no trailing semicolon' => array('color: #000', 'color: #000;'), 'space before colon, no trailing semicolon' => array('color :#000', 'color: #000;'), 'space before property name, no trailing semicolon' => array(' color:#000', 'color: #000;'), 'space before trailing semicolon' => array(' color:#000 ;', 'color: #000;'), 'space after trailing semicolon' => array(' color:#000; ', 'color: #000;'), 'space after property value, no trailing semicolon' => array(' color:#000 ', 'color: #000;'), 'space after property value, trailing semicolon' => array(' color:#000; ', 'color: #000;'), 'newline before property name, trailing semicolon' => array('
color:#222;', 'color: #222;'), 'newline after property semicolon' => array('color:#222;
', 'color: #222;'), 'newline before colon, trailing semicolon' => array('color
:#333;', 'color: #333;'), 'newline after colon, trailing semicolon' => array('color:
#333;', 'color: #333;'), 'newline after semicolon' => array('color:#333
;', 'color: #333;'));
    }
    /**
     * @test
     *
     * @param string $cssDeclaration the CSS declaration block (without the curly braces)
     * @param string $expectedStyleAttributeContent the expected value of the style attribute
     *
     * @dataProvider cssDeclarationWhitespaceDroppingDataProvider
     */
    public function emogrifyDropsLeadingAndTrailingWhitespaceFromCssDeclarations($cssDeclaration, $expectedStyleAttributeContent)
    {
        $html = $this->html5DocumentType . '<html></html>';
        $css = 'html {' . $cssDeclaration . '}';
        $this->subject->setHtml($html);
        $this->subject->setCss($css);
        $result = $this->subject->emogrify();
        self::assertContains('html style="' . $expectedStyleAttributeContent . '">', $result);
    }
    /**
     * Data provider for emogrifyFormatsCssDeclarations.
     *
     * @return string[][]
     */
    public function formattedCssDeclarationDataProvider()
    {
        return array('one declaration' => array('color: #000;', 'color: #000;'), 'one declaration with dash in property name' => array('font-weight: bold;', 'font-weight: bold;'), 'one declaration with space in property value' => array('margin: 0 4px;', 'margin: 0 4px;'), 'two declarations separated by semicolon' => array('color: #000;width: 3px;', 'color: #000; width: 3px;'), 'two declarations separated by semicolon and space' => array('color: #000; width: 3px;', 'color: #000; width: 3px;'), 'two declarations separated by semicolon and linefeed' => array('color: #000;' . self::LF . 'width: 3px;', 'color: #000; width: 3px;'), 'two declarations separated by semicolon and Windows line ending' => array('color: #000;
width: 3px;', 'color: #000; width: 3px;'), 'one declaration with leading dash in property name' => array('-webkit-text-size-adjust:none;', '-webkit-text-size-adjust: none;'));
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
        $html = $this->html5DocumentType . '<html></html>';
        $css = 'html {' . $cssDeclarationBlock . '}';
        $this->subject->setHtml($html);
        $this->subject->setCss($css);
        self::assertContains('html style="' . $expectedStyleAttributeContent . '">', $this->subject->emogrify());
    }
    /**
     * Data provider for emogrifyInvalidDeclaration.
     *
     * @return string[][]
     */
    public function invalidDeclarationDataProvider()
    {
        return array('missing dash in property name' => array('font weight: bold;'), 'invalid character in property name' => array('-9webkit-text-size-adjust:none;'), 'missing :' => array('-webkit-text-size-adjust none'), 'missing value' => array('-webkit-text-size-adjust :'));
    }
    /**
     * @test
     *
     * @param string $cssDeclarationBlock the CSS declaration block (without the curly braces)
     *
     * @dataProvider invalidDeclarationDataProvider
     */
    public function emogrifyDropsInvalidDeclaration($cssDeclarationBlock)
    {
        $html = $this->html5DocumentType . '<html></html>';
        $css = 'html {' . $cssDeclarationBlock . '}';
        $this->subject->setHtml($html);
        $this->subject->setCss($css);
        self::assertContains('<html style="">', $this->subject->emogrify());
    }
    /**
     * @test
     */
    public function emogrifyKeepsExistingStyleAttributes()
    {
        $styleAttribute = 'style="color: #ccc;"';
        $html = $this->html5DocumentType . '<html ' . $styleAttribute . '></html>';
        $this->subject->setHtml($html);
        self::assertContains($styleAttribute, $this->subject->emogrify());
    }
    /**
     * @test
     */
    public function emogrifyAddsCssAfterExistingStyle()
    {
        $styleAttributeValue = 'color: #ccc;';
        $html = $this->html5DocumentType . '<html style="' . $styleAttributeValue . '"></html>';
        $this->subject->setHtml($html);
        $cssDeclarations = 'margin: 0 2px;';
        $css = 'html {' . $cssDeclarations . '}';
        $this->subject->setCss($css);
        self::assertContains('style="' . $styleAttributeValue . ' ' . $cssDeclarations . '"', $this->subject->emogrify());
    }
    /**
     * @test
     */
    public function emogrifyCanMatchMinifiedCss()
    {
        $html = $this->html5DocumentType . '<html><p></p></html>';
        $this->subject->setHtml($html);
        $this->subject->setCss('p{color:blue;}html{color:red;}');
        self::assertContains('<html style="color: red;">', $this->subject->emogrify());
    }
    /**
     * @test
     */
    public function emogrifyLowercasesAttributeNamesFromStyleAttributes()
    {
        $html = $this->html5DocumentType . '<html style="COLOR:#ccc;"></html>';
        $this->subject->setHtml($html);
        self::assertContains('style="color: #ccc;"', $this->subject->emogrify());
    }
    /**
     * @test
     */
    public function emogrifyLowerCasesAttributeNames()
    {
        $html = $this->html5DocumentType . '<html></html>';
        $this->subject->setHtml($html);
        $cssIn = 'html {mArGiN:0 2pX;}';
        $cssOut = 'margin: 0 2pX;';
        $this->subject->setCss($cssIn);
        self::assertContains('style="' . $cssOut . '"', $this->subject->emogrify());
    }
    /**
     * @test
     */
    public function emogrifyPreservesCaseForAttributeValuesFromPassedInCss()
    {
        $css = 'content: \'Hello World\';';
        $html = $this->html5DocumentType . '<html><body><p>target</p></body></html>';
        $this->subject->setHtml($html);
        $this->subject->setCss('p {' . $css . '}');
        self::assertContains('<p style="' . $css . '">target</p>', $this->subject->emogrify());
    }
    /**
     * @test
     */
    public function emogrifyPreservesCaseForAttributeValuesFromParsedStyleBlock()
    {
        $css = 'content: \'Hello World\';';
        $html = $this->html5DocumentType . '<html><head><style>p {' . $css . '}</style></head><body><p>target</p></body></html>';
        $this->subject->setHtml($html);
        self::assertContains('<p style="' . $css . '">target</p>', $this->subject->emogrify());
    }
    /**
     * @test
     */
    public function emogrifyRemovesStyleNodes()
    {
        $html = $this->html5DocumentType . '<html><style type="text/css"></style></html>';
        $this->subject->setHtml($html);
        self::assertNotContains('<style>', $this->subject->emogrify());
    }
    /**
     * @test
     */
    public function emogrifyIgnoresInvalidCssSelector()
    {
        $html = $this->html5DocumentType . '<html><style type="text/css">p{color:red;} <style data-x="1">html{cursor:text;}</style></html>';
        $this->subject->setHtml($html);
        $hasError = false;
        set_error_handler(function ($errorNumber, $errorMessage) use(&$hasError) {
            if ($errorMessage === 'DOMXPath::query(): Invalid expression') {
                return true;
            }
            $hasError = true;
            return true;
        });
        $this->subject->emogrify();
        restore_error_handler();
        self::assertFalse($hasError);
    }
    /**
     * Data provider for things that should be left out when applying the CSS.
     *
     * @return array[]
     */
    public function unneededCssThingsDataProvider()
    {
        return array('CSS comments with one asterisk' => array('p {color: #000;/* black */}', 'black'), 'CSS comments with two asterisks' => array('p {color: #000;/** black */}', 'black'), '@import directive' => array('@import "foo.css";', '@import'), 'style in "aural" media type rule' => array('@media aural {p {color: #000;}}', '#000'), 'style in "braille" media type rule' => array('@media braille {p {color: #000;}}', '#000'), 'style in "embossed" media type rule' => array('@media embossed {p {color: #000;}}', '#000'), 'style in "handheld" media type rule' => array('@media handheld {p {color: #000;}}', '#000'), 'style in "projection" media type rule' => array('@media projection {p {color: #000;}}', '#000'), 'style in "speech" media type rule' => array('@media speech {p {color: #000;}}', '#000'), 'style in "tty" media type rule' => array('@media tty {p {color: #000;}}', '#000'), 'style in "tv" media type rule' => array('@media tv {p {color: #000;}}', '#000'));
    }
    /**
     * @test
     *
     * @param string $css
     * @param string $markerNotExpectedInHtml
     *
     * @dataProvider unneededCssThingsDataProvider
     */
    public function emogrifyFiltersUnneededCssThings($css, $markerNotExpectedInHtml)
    {
        $html = $this->html5DocumentType . '<html><p>foo</p></html>';
        $this->subject->setHtml($html);
        $this->subject->setCss($css);
        self::assertNotContains($markerNotExpectedInHtml, $this->subject->emogrify());
    }
    /**
     * Data provider for media rules.
     *
     * @return array[]
     */
    public function mediaRulesDataProvider()
    {
        return array('style in "only all" media type rule' => array('@media only all {p {color: #000;}}'), 'style in "only screen" media type rule' => array('@media only screen {p {color: #000;}}'), 'style in media type rule' => array('@media {p {color: #000;}}'), 'style in "screen" media type rule' => array('@media screen {p {color: #000;}}'), 'style in "print" media type rule' => array('@media print {p {color: #000;}}'), 'style in "all" media type rule' => array('@media all {p {color: #000;}}'));
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
        $html = $this->html5DocumentType . '<html><p>foo</p></html>';
        $this->subject->setHtml($html);
        $this->subject->setCss($css);
        self::assertContains($css, $this->subject->emogrify());
    }
    /**
     * @test
     */
    public function removeAllowedMediaTypeRemovesStylesForTheGivenMediaType()
    {
        $css = '@media screen { html {} }';
        $html = $this->html5DocumentType . '<html></html>';
        $this->subject->setHtml($html);
        $this->subject->setCss($css);
        $this->subject->removeAllowedMediaType('screen');
        self::assertNotContains($css, $this->subject->emogrify());
    }
    /**
     * @test
     */
    public function addAllowedMediaTypeKeepsStylesForTheGivenMediaType()
    {
        $css = '@media braille { html { some-property: value; } }';
        $html = $this->html5DocumentType . '<html></html>';
        $this->subject->setHtml($html);
        $this->subject->setCss($css);
        $this->subject->addAllowedMediaType('braille');
        self::assertContains($css, $this->subject->emogrify());
    }
    /**
     * @test
     */
    public function emogrifyAddsMissingHeadElement()
    {
        $html = $this->html5DocumentType . '<html></html>';
        $this->subject->setHtml($html);
        $this->subject->setCss('@media all { html {} }');
        self::assertContains('<head>', $this->subject->emogrify());
    }
    /**
     * @test
     */
    public function emogrifyKeepExistingHeadElementContent()
    {
        $html = $this->html5DocumentType . '<html><head><!-- original content --></head></html>';
        $this->subject->setHtml($html);
        $this->subject->setCss('@media all { html {} }');
        self::assertContains('<!-- original content -->', $this->subject->emogrify());
    }
    /**
     * @test
     */
    public function emogrifyKeepExistingHeadElementAddStyleElement()
    {
        $html = $this->html5DocumentType . '<html><head><!-- original content --></head></html>';
        $this->subject->setHtml($html);
        $this->subject->setCss('@media all { html {} }');
        self::assertContains('<style type="text/css">', $this->subject->emogrify());
    }
    /**
     * Valid media query which need to be preserved
     *
     * @return array[]
     */
    public function validMediaPreserveDataProvider()
    {
        return array('style in "only screen and size" media type rule' => array('@media only screen and (min-device-width: 320px) and (max-device-width: 480px) { h1 { color:red; } }'), 'style in "screen size" media type rule' => array('@media screen and (min-device-width: 320px) and (max-device-width: 480px) { h1 { color:red; } }'), 'style in "only screen and screen size" media type rule' => array('@media only screen and (min-device-width: 320px) and (max-device-width: 480px) { h1 { color:red; } }'), 'style in "all and screen size" media type rule' => array('@media all and (min-device-width: 320px) and (max-device-width: 480px) { h1 { color:red; } }'), 'style in "only all and" media type rule' => array('@media only all and (min-device-width: 320px) and (max-device-width: 480px) { h1 { color:red; } }'), 'style in "all" media type rule' => array('@media all {p {color: #000;}}'), 'style in "only screen" media type rule' => array('@media only screen { h1 { color:red; } }'), 'style in "only all" media type rule' => array('@media only all { h1 { color:red; } }'), 'style in "screen" media type rule' => array('@media screen { h1 { color:red; } }'), 'style in media type rule without specification' => array('@media { h1 { color:red; } }'));
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
        $html = $this->html5DocumentType . PHP_EOL . '<html><h1></h1><p></p></html>';
        $this->subject->setHtml($html);
        $this->subject->setCss($css);
        self::assertContains($css, $this->subject->emogrify());
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
        $html = $this->html5DocumentType . PHP_EOL . '<html><style type="text/css">' . $css . '</style><h1></h1><p></p></html>';
        $this->subject->setHtml($html);
        self::assertContains($css, $this->subject->emogrify());
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
        $html = $this->html5DocumentType . PHP_EOL . '<html><h1></h1></html>';
        $this->subject->setHtml($html);
        $this->subject->setCss($css);
        self::assertNotContains('style="color:red"', $this->subject->emogrify());
    }
    /**
     * Invalid media query which need to be strip
     *
     * @return array[]
     */
    public function invalidMediaPreserveDataProvider()
    {
        return array('style in "braille" type rule' => array('@media braille { h1 { color:red; } }'), 'style in "embossed" type rule' => array('@media embossed { h1 { color:red; } }'), 'style in "handheld" type rule' => array('@media handheld { h1 { color:red; } }'), 'style in "projection" type rule' => array('@media projection { h1 { color:red; } }'), 'style in "speech" type rule' => array('@media speech { h1 { color:red; } }'), 'style in "tty" type rule' => array('@media tty { h1 { color:red; } }'), 'style in "tv" type rule' => array('@media tv { h1 { color:red; } }'));
    }
    /**
     * @test
     *
     * @param string $css
     *
     * @dataProvider invalidMediaPreserveDataProvider
     */
    public function emogrifyWithInvalidMediaQueryaNotContainsInnerCss($css)
    {
        $html = $this->html5DocumentType . PHP_EOL . '<html><h1></h1></html>';
        $this->subject->setHtml($html);
        $this->subject->setCss($css);
        self::assertNotContains($css, $this->subject->emogrify());
    }
    /**
     * @test
     *
     * @param string $css
     *
     * @dataProvider invalidMediaPreserveDataProvider
     */
    public function emogrifyWithInValidMediaQueryNotContainsInlineCss($css)
    {
        $html = $this->html5DocumentType . PHP_EOL . '<html><h1></h1></html>';
        $this->subject->setHtml($html);
        $this->subject->setCss($css);
        self::assertNotContains('style="color: red"', $this->subject->emogrify());
    }
    /**
     * @test
     *
     * @param string $css
     *
     * @dataProvider invalidMediaPreserveDataProvider
     */
    public function emogrifyFromHtmlWithInValidMediaQueryNotContainsInnerCss($css)
    {
        $html = $this->html5DocumentType . PHP_EOL . '<html><style type="text/css">' . $css . '</style><h1></h1></html>';
        $this->subject->setHtml($html);
        self::assertNotContains($css, $this->subject->emogrify());
    }
    /**
     * @test
     *
     * @param string $css
     *
     * @dataProvider invalidMediaPreserveDataProvider
     */
    public function emogrifyFromHtmlWithInValidMediaQueryNotContainsInlineCss($css)
    {
        $html = $this->html5DocumentType . PHP_EOL . '<html><style type="text/css">' . $css . '</style><h1></h1></html>';
        $this->subject->setHtml($html);
        self::assertNotContains('style="color: red"', $this->subject->emogrify());
    }
    /**
     * @test
     */
    public function emogrifyAppliesCssFromStyleNodes()
    {
        $styleAttributeValue = 'color: #ccc;';
        $html = $this->html5DocumentType . '<html><style type="text/css">html {' . $styleAttributeValue . '}</style></html>';
        $this->subject->setHtml($html);
        self::assertContains('<html style="' . $styleAttributeValue . '">', $this->subject->emogrify());
    }
    /**
     * @test
     */
    public function emogrifyWhenDisabledNotAppliesCssFromStyleBlocks()
    {
        $styleAttributeValue = 'color: #ccc;';
        $html = $this->html5DocumentType . '<html><style type="text/css">html {' . $styleAttributeValue . '}</style></html>';
        $this->subject->setHtml($html);
        $this->subject->disableStyleBlocksParsing();
        self::assertNotContains('<html style="' . $styleAttributeValue . '">', $this->subject->emogrify());
    }
    /**
     * @test
     */
    public function emogrifyWhenStyleBlocksParsingDisabledKeepInlineStyles()
    {
        $styleAttributeValue = 'text-align: center;';
        $html = $this->html5DocumentType . '<html><head><style type="text/css">p { color: #ccc; }</style></head>' . '<body><p style="' . $styleAttributeValue . '">paragraph</p></body></html>';
        $expected = '<p style="' . $styleAttributeValue . '">';
        $this->subject->setHtml($html);
        $this->subject->disableStyleBlocksParsing();
        self::assertContains($expected, $this->subject->emogrify());
    }
    /**
     * @test
     */
    public function emogrifyWhenDisabledNotAppliesCssFromInlineStyles()
    {
        $styleAttributeValue = 'color: #ccc;';
        $html = $this->html5DocumentType . '<html style="' . $styleAttributeValue . '"></html>';
        $this->subject->setHtml($html);
        $this->subject->disableInlineStyleAttributesParsing();
        self::assertNotContains('<html style', $this->subject->emogrify());
    }
    /**
     * @test
     */
    public function emogrifyWhenInlineStyleAttributesParsingDisabledKeepStyleBlockStyles()
    {
        $styleAttributeValue = 'color: #ccc;';
        $html = $this->html5DocumentType . '<html><head><style type="text/css">p { ' . $styleAttributeValue . ' }</style></head>' . '<body><p style="text-align: center;">paragraph</p></body></html>';
        $expected = '<p style="' . $styleAttributeValue . '">';
        $this->subject->setHtml($html);
        $this->subject->disableInlineStyleAttributesParsing();
        self::assertContains($expected, $this->subject->emogrify());
    }
    /**
     * @test
     */
    public function emogrifyAppliesCssWithUpperCaseSelector()
    {
        $html = $this->html5DocumentType . '<html><style type="text/css">P { color:#ccc; }</style><body><p>paragraph</p></body></html>';
        $expected = '<p style="color: #ccc;">';
        $this->subject->setHtml($html);
        self::assertContains($expected, $this->subject->emogrify());
    }
    /**
     * Emogrify was handling case differently for passed in CSS vs CSS parsed from style blocks.
     * @test
     */
    public function emogrifyAppliesCssWithMixedCaseAttributesInStyleBlock()
    {
        $html = $this->html5DocumentType . '<html><head><style>#topWrap p {padding-bottom: 1px;PADDING-TOP: 0;}</style></head>' . '<body><div id="topWrap"><p style="text-align: center;">some content</p></div></body></html>';
        $expected = '<p style="text-align: center; padding-bottom: 1px; padding-top: 0;">';
        $this->subject->setHtml($html);
        self::assertContains($expected, $this->subject->emogrify());
    }
    /**
     * Passed in CSS sets the order, but style block CSS overrides values.
     * @test
     */
    public function emogrifyMergesCssWithMixedCaseAttribute()
    {
        $css = 'p { margin: 0; padding-TOP: 0; PADDING-bottom: 1PX;}';
        $html = $this->html5DocumentType . '<html><head><style>#topWrap p {padding-bottom: 3px;PADDING-TOP: 1px;}</style></head>' . '<body><div id="topWrap"><p style="text-align: center;">some content</p></div></body></html>';
        $expected = '<p style="text-align: center; margin: 0; padding-top: 1px; padding-bottom: 3px;">';
        $this->subject->setHtml($html);
        $this->subject->setCss($css);
        self::assertContains($expected, $this->subject->emogrify());
    }
    /**
     * @test
     */
    public function emogrifyMergesCssWithMixedUnits()
    {
        $css = 'p { margin: 1px; padding-bottom:0;}';
        $html = $this->html5DocumentType . '<html><head><style>#topWrap p {margin:0;padding-bottom: 1px;}</style></head>' . '<body><div id="topWrap"><p style="text-align: center;">some content</p></div></body></html>';
        $expected = '<p style="text-align: center; margin: 0; padding-bottom: 1px;">';
        $this->subject->setHtml($html);
        $this->subject->setCss($css);
        self::assertContains($expected, $this->subject->emogrify());
    }
    /**
     * @test
     */
    public function emogrifyByDefaultRemovesElementsWithDisplayNoneFromExternalCss()
    {
        $css = 'div.foo { display: none; }';
        $html = $this->html5DocumentType . '<html><body><div class="bar"></div><div class="foo"></div></body></html>';
        $expected = '<div class="bar"></div>';
        $this->subject->setHtml($html);
        $this->subject->setCss($css);
        self::assertContains($expected, $this->subject->emogrify());
    }
    /**
     * @test
     */
    public function emogrifyByDefaultRemovesElementsWithDisplayNoneInStyleAttribute()
    {
        $html = $this->html5DocumentType . '<html><body><div class="bar"></div><div class="foobar" style="display: none;"></div>' . '</body></html>';
        $expected = '<div class="bar"></div>';
        $this->subject->setHtml($html);
        self::assertContains($expected, $this->subject->emogrify());
    }
    /**
     * @test
     */
    public function emogrifyAfterDisableInvisibleNodeRemovalPreservesInvisibleElements()
    {
        $css = 'div.foo { display: none; }';
        $html = $this->html5DocumentType . '<html><body><div class="bar"></div><div class="foo"></div></body></html>';
        $expected = '<div class="foo" style="display: none;">';
        $this->subject->setHtml($html);
        $this->subject->setCss($css);
        $this->subject->disableInvisibleNodeRemoval();
        self::assertContains($expected, $this->subject->emogrify());
    }
    /**
     * @test
     */
    public function emogrifyKeepsCssMediaQueriesWithCssCommentAfterMediaQuery()
    {
        $css = '@media only screen and (max-width: 480px) { body { color: #ffffff } /* some comment */ }';
        $html = $this->html5DocumentType . '<html><body></body></html>';
        $expected = '@media only screen and (max-width: 480px)';
        $this->subject->setHtml($html);
        $this->subject->setCss($css);
        self::assertContains($expected, $this->subject->emogrify());
    }
    /**
     * @test
     */
    public function emogrifyForXhtmlDocumentTypeConvertsXmlSelfClosingTagsToNonXmlSelfClosingTag()
    {
        $this->subject->setHtml($this->xhtml1StrictDocumentType . '<html><body><br/></body></html>');
        self::assertContains('<body><br></body>', $this->subject->emogrify());
    }
    /**
     * @test
     */
    public function emogrifyForHtml5DocumentTypeKeepsNonXmlSelfClosingTagsAsNonXmlSelfClosing()
    {
        $this->subject->setHtml($this->html5DocumentType . '<html><body><br></body></html>');
        self::assertContains('<body><br></body>', $this->subject->emogrify());
    }
    /**
     * @test
     */
    public function emogrifyForHtml5DocumentTypeConvertXmlSelfClosingTagsToNonXmlSelfClosingTag()
    {
        $this->subject->setHtml($this->html5DocumentType . '<html><body><br/></body></html>');
        self::assertContains('<body><br></body>', $this->subject->emogrify());
    }
    /**
     * @test
     */
    public function emogrifyAutomaticallyClosesUnclosedTag()
    {
        $this->subject->setHtml($this->html5DocumentType . '<html><body><p></body></html>');
        self::assertContains('<body><p></p></body>', $this->subject->emogrify());
    }
    /**
     * @test
     */
    public function emogrifyReturnsCompleteHtmlDocument()
    {
        $this->subject->setHtml($this->html5DocumentType . '<html><body><p></p></body></html>');
        self::assertSame($this->html5DocumentType . self::LF . '<html>' . self::LF . '<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>' . self::LF . '<body><p></p></body>' . self::LF . '</html>' . self::LF, $this->subject->emogrify());
    }
    /**
     * @test
     */
    public function emogrifyBodyContentReturnsBodyContentFromHtml()
    {
        $this->subject->setHtml($this->html5DocumentType . '<html><body><p></p></body></html>');
        self::assertSame('<p></p>' . self::LF, $this->subject->emogrifyBodyContent());
    }
    /**
     * @test
     */
    public function emogrifyBodyContentReturnsBodyContentFromContent()
    {
        $this->subject->setHtml('<p></p>');
        self::assertSame('<p></p>' . self::LF, $this->subject->emogrifyBodyContent());
    }
    /**
     * @test
     */
    public function importantInExternalCssOverwritesInlineCss()
    {
        $css = 'p { margin: 1px !important; }';
        $html = $this->html5DocumentType . '<html><head</head><body><p style="margin: 2px;">some content</p></body></html>';
        $expected = '<p style="margin: 1px !important;">';
        $this->subject->setHtml($html);
        $this->subject->setCss($css);
        self::assertContains($expected, $this->subject->emogrify());
    }
    /**
     * @test
     */
    public function importantInExternalCssKeepsInlineCssForOtherAttributes()
    {
        $css = 'p { margin: 1px !important; }';
        $html = $this->html5DocumentType . '<html><head</head><body><p style="margin: 2px; text-align: center;">some content</p></body></html>';
        $expected = '<p style="margin: 1px !important; text-align: center;">';
        $this->subject->setHtml($html);
        $this->subject->setCss($css);
        self::assertContains($expected, $this->subject->emogrify());
    }
    /**
     * @test
     */
    public function emogrifyHandlesImportantStyleTagCaseInsensitive()
    {
        $css = 'p { margin: 1px !ImPorTant; }';
        $html = $this->html5DocumentType . '<html><head</head><body><p style="margin: 2px;">some content</p></body></html>';
        $expected = '<p style="margin: 1px !ImPorTant;">';
        $this->subject->setHtml($html);
        $this->subject->setCss($css);
        self::assertContains($expected, $this->subject->emogrify());
    }
    /**
     * @test
     */
    public function irrelevantMediaQueriesAreRemoved()
    {
        $uselessQuery = '@media all and (max-width: 500px) { em { color:red; } }';
        $this->subject->setCss($uselessQuery);
        $this->subject->setHtml($this->html5DocumentType . '<html><body><p></p></body></html>');
        $result = $this->subject->emogrify();
        self::assertNotContains($uselessQuery, $result);
    }
    /**
     * @test
     */
    public function relevantMediaQueriesAreRetained()
    {
        $usefulQuery = '@media all and (max-width: 500px) { p { color:red; } }';
        $this->subject->setCss($usefulQuery);
        $this->subject->setHtml($this->html5DocumentType . '<html><body><p></p></body></html>');
        $result = $this->subject->emogrify();
        self::assertContains($usefulQuery, $result);
    }
    /**
     * @test
     */
    public function importantStyleRuleFromInlineCssOverwritesImportantStyleRuleFromExternalCss()
    {
        $css = 'p { margin: 1px !important; padding: 1px;}';
        $html = $this->html5DocumentType . '<html><head</head><body><p style="margin: 2px !important; text-align: center;">some content</p>' . '</body></html>';
        $expected = '<p style="margin: 2px !important; text-align: center; padding: 1px;">';
        $this->subject->setHtml($html);
        $this->subject->setCss($css);
        self::assertContains($expected, $this->subject->emogrify());
    }
    /**
     * @test
     */
    public function addExcludedSelectorRemovesMatchingElementsFromEmogrification()
    {
        $css = 'p { margin: 0; }';
        $this->subject->setHtml($this->html5DocumentType . '<html><body><p class="x"></p></body></html>');
        $this->subject->setCss($css);
        $this->subject->addExcludedSelector('p.x');
        $html = $this->subject->emogrify();
        self::assertContains('<p class="x"></p>', $html);
    }
    /**
     * @test
     */
    public function addExcludedSelectorExcludesMatchingElementEventWithWhitespaceAroundSelector()
    {
        $css = 'p { margin: 0; }';
        $this->subject->setHtml($this->html5DocumentType . '<html><body><p class="x"></p></body></html>');
        $this->subject->setCss($css);
        $this->subject->addExcludedSelector(' p.x ');
        $html = $this->subject->emogrify();
        self::assertContains('<p class="x"></p>', $html);
    }
    /**
     * @test
     */
    public function addExcludedSelectorKeepsNonMatchingElementsInEmogrification()
    {
        $css = 'p { margin: 0; }';
        $this->subject->setHtml($this->html5DocumentType . '<html><body><p></p></body></html>');
        $this->subject->setCss($css);
        $this->subject->addExcludedSelector('p.x');
        $html = $this->subject->emogrify();
        self::assertContains('<p style="margin: 0;"></p>', $html);
    }
    /**
     * @test
     */
    public function removeExcludedSelectorGetsMatchingElementsToBeEmogrifiedAgain()
    {
        $css = 'p { margin: 0; }';
        $this->subject->setHtml($this->html5DocumentType . '<html><body><p class="x"></p></body></html>');
        $this->subject->setCss($css);
        $this->subject->addExcludedSelector('p.x');
        $this->subject->removeExcludedSelector('p.x');
        $html = $this->subject->emogrify();
        self::assertContains('<p class="x" style="margin: 0;"></p>', $html);
    }
    /**
     * @test
     */
    public function emptyMediaQueriesAreRemoved()
    {
        $emptyQuery = '@media all and (max-width: 500px) { }';
        $this->subject->setCss($emptyQuery);
        $this->subject->setHtml($this->html5DocumentType . '<html><body><p></p></body></html>');
        $result = $this->subject->emogrify();
        self::assertNotContains($emptyQuery, $result);
    }
    /**
     * @test
     */
    public function multiLineMediaQueryWithWindowsLineEndingsIsAppliedOnlyOnce()
    {
        $css = '@media all {
' . '.medium {font-size:18px;}
' . '.small {font-size:14px;}
' . '}';
        $this->subject->setCss($css);
        $this->subject->setHtml($this->html5DocumentType . '<html><body>' . '<p class="medium">medium</p>' . '<p class="small">small</p>' . '</body></html>');
        $result = $this->subject->emogrify();
        self::assertSame(1, substr_count($result, '<style type="text/css">' . $css . '</style>'));
    }
    /**
     * @test
     */
    public function multiLineMediaQueryWithUnixLineEndingsIsAppliedOnlyOnce()
    {
        $css = '@media all {
' . '.medium {font-size:18px;}
' . '.small {font-size:14px;}
' . '}';
        $this->subject->setCss($css);
        $this->subject->setHtml($this->html5DocumentType . '<html><body>' . '<p class="medium">medium</p>' . '<p class="small">small</p>' . '</body></html>');
        $result = $this->subject->emogrify();
        self::assertSame(1, substr_count($result, '<style type="text/css">' . $css . '</style>'));
    }
    /**
     * @test
     */
    public function multipleMediaQueriesAreAppliedOnlyOnce()
    {
        $css = '@media all {
' . '.medium {font-size:18px;
' . '.small {font-size:14px;}
' . '}' . '@media screen {
' . '.medium {font-size:24px;}
' . '.small {font-size:18px;}
' . '}';
        $this->subject->setCss($css);
        $this->subject->setHtml($this->html5DocumentType . '<html><body>' . '<p class="medium">medium</p>' . '<p class="small">small</p>' . '</body></html>');
        $result = $this->subject->emogrify();
        self::assertSame(1, substr_count($result, '<style type="text/css">' . $css . '</style>'));
    }
    /**
     * @return string[][]
     */
    public function dataUriMediaTypeDataProvider()
    {
        return array('nothing' => array(''), ';charset=utf-8' => array(';charset=utf-8'), ';base64' => array(';base64'), ';charset=utf-8;base64' => array(';charset=utf-8;base64'));
    }
    /**
     * @test
     * @param string $dataUriMediaType
     * @dataProvider dataUriMediaTypeDataProvider
     */
    public function dataUrisAreConserved($dataUriMediaType)
    {
        $html = $this->html5DocumentType . '<html></html>';
        $this->subject->setHtml($html);
        $styleRule = 'background-image: url(data:image/png' . $dataUriMediaType . ',iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAIAAAAC64paAAABUk' . 'lEQVQ4y81UsY6CQBCdWXBjYWFMjEgAE0piY8c38B9+iX+ksaHCgs5YWEhIrJCQYGJBomiC7lzhVcfqEa+5KXfey3s783bRdd00TR' . 'VFAQAAICJEhN/q8Xjoug7D4RA+qsFgwDjn9QYiTiaT+Xx+OByOx+NqtapjWq0WjEajekPTtCAIiIiIyrKMoqiOMQxDlVqyLMt1XQ' . 'A4nU6z2Wy9XkthEnK/3zdN8znC/X7v+36WZfJ7120vFos4joUQRHS5XDabzXK5bGrbtu1er/dtTFU1TWu3202VHceZTqe3242Itt' . 'ut53nj8bip8m6345wLIQCgKIowDIuikAoz6Wm3233mjHPe6XRe5UROJqImIWPwh/pvZMbYM2GKorx5oUw6m+v1miTJ+XzO8/x+v7' . '+UtizrM8+GYahVVSFik9/jxy6rqlJN02SM1cmI+GbbQghd178AAO2FXws6LwMAAAAASUVORK5CYII=);';
        $this->subject->setCss('html {' . $styleRule . '}');
        $result = $this->subject->emogrify();
        self::assertContains('<html style="' . $styleRule . '">', $result);
    }
}

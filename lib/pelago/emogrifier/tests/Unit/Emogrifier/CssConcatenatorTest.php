<?php

namespace Pelago\Tests\Unit\Emogrifier;

use Pelago\Emogrifier\CssConcatenator;

/**
 * Test case.
 *
 * @author Jake Hotson <jake.github@qzdesign.co.uk>
 */
class CssConcatenatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CssConcatenator
     */
    private $subject = null;

    /**
     * @return void
     */
    protected function setUp()
    {
        $this->subject = new CssConcatenator();
    }

    /**
     * @test
     */
    public function getCssInitiallyReturnsEmptyString()
    {
        $result = $this->subject->getCss();

        static::assertSame('', $result);
    }

    /**
     * @test
     */
    public function appendSetsFirstRule()
    {
        $this->subject->append(['p'], 'color: green;');

        $result = $this->subject->getCss();

        static::assertSame('p{color: green;}', $result);
    }

    /**
     * @test
     */
    public function appendWithMediaQuerySetsFirstRuleInMediaRule()
    {
        $this->subject->append(['p'], 'color: green;', '@media screen');

        $result = $this->subject->getCss();

        static::assertSame('@media screen{p{color: green;}}', $result);
    }

    /**
     * @return string[][]
     */
    public function equivalentSelectorsDataProvider()
    {
        return [
            'one selector' => [['p'], ['p']],
            'two selectors' => [
                ['p', 'ul'],
                ['p', 'ul'],
            ],
            'two selectors in different order' => [
                ['p', 'ul'],
                ['ul', 'p'],
            ],
        ];
    }

    /**
     * @test
     *
     * @param string[] $selectors1
     * @param string[] $selectors2
     *
     * @dataProvider equivalentSelectorsDataProvider
     */
    public function appendCombinesRulesWithEquivalentSelectors(array $selectors1, array $selectors2)
    {
        $this->subject->append($selectors1, 'color: green;');
        $this->subject->append($selectors2, 'font-size: 16px;');

        $result = $this->subject->getCss();

        $expectedResult = \implode(',', $selectors1) . '{color: green;font-size: 16px;}';

        static::assertSame($expectedResult, $result);
    }

    /**
     * @test
     */
    public function appendInsertsSemicolonCombiningRulesWithoutTrailingSemicolon()
    {
        $this->subject->append(['p'], 'color: green');
        $this->subject->append(['p'], 'font-size: 16px');

        $result = $this->subject->getCss();

        static::assertSame('p{color: green;font-size: 16px}', $result);
    }

    /**
     * @return string[][]
     */
    public function differentSelectorsDataProvider()
    {
        return [
            'single selectors' => [
                ['p'],
                ['ul'],
                ['p', 'ul'],
            ],
            'single selector and an entirely different pair' => [
                ['p'],
                ['ul', 'ol'],
                ['p', 'ul', 'ol'],
            ],
            'single selector and a superset pair' => [
                ['p'],
                ['p', 'ul'],
                ['p', 'ul'],
            ],
            'pair of selectors and an entirely different single' => [
                ['p', 'ul'],
                ['ol'],
                ['p', 'ul', 'ol'],
            ],
            'pair of selectors and a subset single' => [
                ['p', 'ul'],
                ['ul'],
                ['p', 'ul'],
            ],
            'entirely different pairs of selectors' => [
                ['p', 'ul'],
                ['ol', 'h1'],
                ['p', 'ul', 'ol', 'h1'],
            ],
            'pairs of selectors with one common' => [
                ['p', 'ul'],
                ['ul', 'ol'],
                ['p', 'ul', 'ol'],
            ],
        ];
    }

    /**
     * @test
     *
     * @param string[] $selectors1
     * @param string[] $selectors2
     * @param string[] $combinedSelectors
     *
     * @dataProvider differentSelectorsDataProvider
     */
    public function appendCombinesSameRulesWithDifferentSelectors(
        array $selectors1,
        array $selectors2,
        array $combinedSelectors
    ) {
        $this->subject->append($selectors1, 'color: green;');
        $this->subject->append($selectors2, 'color: green;');

        $result = $this->subject->getCss();

        $expectedResult = \implode(',', $combinedSelectors) . '{color: green;}';

        static::assertSame($expectedResult, $result);
    }

    /**
     * @test
     *
     * @param string[] $selectors1
     * @param string[] $selectors2
     *
     * @dataProvider differentSelectorsDataProvider
     */
    public function appendNotCombinesDifferentRulesWithDifferentSelectors(array $selectors1, array $selectors2)
    {
        $this->subject->append($selectors1, 'color: green;');
        $this->subject->append($selectors2, 'font-size: 16px;');

        $result = $this->subject->getCss();

        $expectedResult = \implode(',', $selectors1) . '{color: green;}'
            . \implode(',', $selectors2) . '{font-size: 16px;}';

        static::assertSame($expectedResult, $result);
    }

    /**
     * @test
     */
    public function appendCombinesRulesForSameMediaQueryInMediaRule()
    {
        $this->subject->append(['p'], 'color: green;', '@media screen');
        $this->subject->append(['ul'], 'font-size: 16px;', '@media screen');

        $result = $this->subject->getCss();

        static::assertSame('@media screen{p{color: green;}ul{font-size: 16px;}}', $result);
    }

    /**
     * @test
     *
     * @param string[] $selectors1
     * @param string[] $selectors2
     *
     * @dataProvider equivalentSelectorsDataProvider
     */
    public function appendCombinesRulesWithEquivalentSelectorsWithinMediaRule(array $selectors1, array $selectors2)
    {
        $this->subject->append($selectors1, 'color: green;', '@media screen');
        $this->subject->append($selectors2, 'font-size: 16px;', '@media screen');

        $result = $this->subject->getCss();

        $expectedResult = '@media screen{' . \implode(',', $selectors1) . '{color: green;font-size: 16px;}}';

        static::assertSame($expectedResult, $result);
    }

    /**
     * @test
     *
     * @param string[] $selectors1
     * @param string[] $selectors2
     * @param string[] $combinedSelectors
     *
     * @dataProvider differentSelectorsDataProvider
     */
    public function appendCombinesSameRulesWithDifferentSelectorsWithinMediaRule(
        array $selectors1,
        array $selectors2,
        array $combinedSelectors
    ) {
        $this->subject->append($selectors1, 'color: green;', '@media screen');
        $this->subject->append($selectors2, 'color: green;', '@media screen');

        $result = $this->subject->getCss();

        $expectedResult = '@media screen{' . \implode(',', $combinedSelectors) . '{color: green;}}';

        static::assertSame($expectedResult, $result);
    }

    /**
     * @test
     */
    public function appendNotCombinesRulesForDifferentMediaQueryInMediaRule()
    {
        $this->subject->append(['p'], 'color: green;', '@media screen');
        $this->subject->append(['p'], 'color: green;', '@media print');

        $result = $this->subject->getCss();

        static::assertSame('@media screen{p{color: green;}}@media print{p{color: green;}}', $result);
    }

    /**
     * @return mixed[][]
     */
    public function combinableRulesDataProvider()
    {
        return [
            'same selectors' => [['p'], 'color: green;', ['p'], 'font-size: 16px;', ''],
            'same declarations block' => [['p'], 'color: green;', ['ul'], 'color: green;', ''],
            'same media query' => [['p'], 'color: green;', ['ul'], 'font-size: 16px;', '@media screen'],
        ];
    }

    /**
     * @test
     *
     * @param array $rule1Selectors
     * @param string $rule1DeclarationsBlock
     * @param array $rule2Selectors
     * @param string $rule2DeclarationsBlock
     * @param string $media
     *
     * @dataProvider combinableRulesDataProvider
     */
    public function appendNotCombinesNonadjacentRules(
        array $rule1Selectors,
        $rule1DeclarationsBlock,
        array $rule2Selectors,
        $rule2DeclarationsBlock,
        $media
    ) {
        $this->subject->append($rule1Selectors, $rule1DeclarationsBlock, $media);
        $this->subject->append(['.intervening'], '-intervening-property: 0;');
        $this->subject->append($rule2Selectors, $rule2DeclarationsBlock, $media);

        $result = $this->subject->getCss();

        $expectedRule1Css = \implode(',', $rule1Selectors) . '{' . $rule1DeclarationsBlock . '}';
        $expectedRule2Css = \implode(',', $rule2Selectors) . '{' . $rule2DeclarationsBlock . '}';
        if ($media !== '') {
            $expectedRule1Css = $media . '{' . $expectedRule1Css . '}';
            $expectedRule2Css = $media . '{' . $expectedRule2Css . '}';
        }
        $expectedResult = $expectedRule1Css . '.intervening{-intervening-property: 0;}' . $expectedRule2Css;

        static::assertSame($expectedResult, $result);
    }
}

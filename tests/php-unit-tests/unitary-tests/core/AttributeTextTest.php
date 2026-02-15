<?php
/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Core;

use AttributeText;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;

class AttributeTextTest extends ItopDataTestCase
{
	protected \Organization $oTestOrganizationForAttributeText;

	public function setUp(): void
	{
		parent::setUp();
		$this->oTestOrganizationForAttributeText = $this->CreateOrganization('Test for AttributeTextTest');
	}

	/**
     * @covers AttributeText::RenderWikiHtml
     */
    public function testRenderWikiHtml_nonWikiUrlVariants()
    {
        // String value
        $sInput = 'This hyperlink https://combodo.com should be in an anchor tag.';
        $sExpected = 'This hyperlink <a href="https://combodo.com">https://combodo.com</a> should be in an anchor tag.';
        $this->assertEquals($sExpected, AttributeText::RenderWikiHtml($sInput));

        // Empty string value
        $this->assertEquals('', AttributeText::RenderWikiHtml(''));

        // Null value
        $this->assertEquals('', AttributeText::RenderWikiHtml(null));
    }

    /**
     * @covers AttributeText::RenderWikiHtml
     */
    public function testRenderWikiHtml_bWikiOnlyAbsentOrFalse_shouldTransformBothRegularAndWikiHyperlinks()
    {
        $sInput = 'A regular hyperlink https://combodo.com and a wiki hyperlink to an existing object [[Organization:'.$this->oTestOrganizationForAttributeText->GetKey().']]';

		// bWikiOnly default value
        $sResult = AttributeText::RenderWikiHtml($sInput);
        $this->assertStringContainsString('<a href="https://combodo.com">', $sResult);
        $this->assertStringContainsString('class="object-ref-link"', $sResult);

		// bWikiOnly = false
        $sResult = AttributeText::RenderWikiHtml($sInput, false);
        $this->assertStringContainsString('<a href="https://combodo.com">', $sResult);
        $this->assertStringContainsString('class="object-ref-link"', $sResult);
    }

    /**
     * @covers AttributeText::RenderWikiHtml
     */
    public function testRenderWikiHtml_bWikiOnlyToTrue_shouldNotTransformRegularHyperlinkButTransformWikiHyperlink()
    {
        $sInput = 'A regular hyperlink https://combodo.com and a wiki hyperlink to an existing object [[Organization:'.$this->oTestOrganizationForAttributeText->GetKey().']]';
        $sResult = AttributeText::RenderWikiHtml($sInput, true);
        $this->assertStringNotContainsString('<a href="https://combodo.com">', $sResult);
        $this->assertStringContainsString('class="object-ref-link"', $sResult);
    }

    /**
     * @covers AttributeText::RenderWikiHtml
     */
    public function testRenderWikiHtml_shouldTransformWikiHyperlinkForExistingObjectsOnly()
    {
        $sInput = 'A wiki hyperlink to a non existing object [[Organization:123456789]]  and a wiki hyperlink to an existing object [[Organization:'.$this->oTestOrganizationForAttributeText->GetKey().']]';
        $sResult = AttributeText::RenderWikiHtml($sInput);
        $this->assertStringContainsString('wiki_broken_link', $sResult);
        $this->assertStringContainsString('class="object-ref-link"', $sResult);
    }
}

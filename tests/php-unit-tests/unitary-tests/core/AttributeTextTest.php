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
        $input = 'This hyperlink https://combodo.com should be in an anchor tag.';
        $expected = 'This hyperlink <a href="https://combodo.com">https://combodo.com</a> should be in an anchor tag.';
        $this->assertEquals($expected, AttributeText::RenderWikiHtml($input));

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
        $input = 'A regular hyperlink https://combodo.com and a wiki hyperlink to an existing object [[Organization:'.$this->oTestOrganizationForAttributeText->GetKey().']]';

		// bWikiOnly default value
        $result = AttributeText::RenderWikiHtml($input);
        $this->assertStringContainsString('<a href="https://combodo.com">', $result);
        $this->assertStringContainsString('class="object-ref-link"', $result);

		// bWikiOnly = false
        $result = AttributeText::RenderWikiHtml($input, false);
        $this->assertStringContainsString('<a href="https://combodo.com">', $result);
        $this->assertStringContainsString('class="object-ref-link"', $result);
    }

    /**
     * @covers AttributeText::RenderWikiHtml
     */
    public function testRenderWikiHtml_bWikiOnlyToTrue_shouldNotTransformRegularHyperlinkButTransformWikiHyperlink()
    {
        $input = 'A regular hyperlink https://combodo.com and a wiki hyperlink to an existing object [[Organization:'.$this->oTestOrganizationForAttributeText->GetKey().']]';
        $result = AttributeText::RenderWikiHtml($input, true);
        $this->assertStringNotContainsString('<a href="https://combodo.com">', $result);
        $this->assertStringContainsString('class="object-ref-link"', $result);
    }

    /**
     * @covers AttributeText::RenderWikiHtml
     */
    public function testRenderWikiHtml_shouldTransformWikiHyperlinkForExistingObjectsOnly()
    {
        $input = 'A wiki hyperlink to a non existing object [[Organization:123456789]]  and a wiki hyperlink to an existing object [[Organization:'.$this->oTestOrganizationForAttributeText->GetKey().']]';
        $result = AttributeText::RenderWikiHtml($input);
        $this->assertStringContainsString('wiki_broken_link', $result);
        $this->assertStringContainsString('class="object-ref-link"', $result);
    }
}

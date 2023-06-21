<?php
/**
 * Copyright (C) 2018 Dennis Lassiter
 *
 * This file is part of iTop.
 *
 *  iTop is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with iTop. If not, see <http://www.gnu.org/licenses/>
 *
 */

namespace Combodo\iTop\Test\UnitTest\Application;

use Combodo\iTop\Test\UnitTest\ItopTestCase;
use DesignerXMLField;
use utils;

/**
 * @covers DesignerFormField
 */
class DesignerFormFieldTest extends ItopTestCase
{
	/**
	 * @param string $sFieldFQCN
	 * @param string $sInputValue
	 * @param string $sExpectedValue
	 *
	 * @return void
	 * @throws \ReflectionException
	 * @covers DesignerLongTextField::PrepareValueForRendering
	 * @dataProvider PrepareValueForRenderingProvider
	 */
	public function testPrepareValueForRendering(string $sFieldFQCN, string $sInputValue, string $sExpectedValue)
	{
		$oField = new $sFieldFQCN('field_code', 'Field label', $sInputValue);

		$sTestedValue = $this->InvokeNonPublicMethod($sFieldFQCN, 'PrepareValueForRendering', $oField, []);
		$this->assertEquals($sExpectedValue, $sTestedValue);
	}

	public function PrepareValueForRenderingProvider(): array
	{
		return [
			'DesignerLongTextField should not double encode XML' => [
				'\\DesignerLongTextField',
				<<<XML
<root>
	<title id="title">Foo &amp; Bar</title>
</root>
XML,
				<<<HTML
&lt;root&gt;
	&lt;title id=&quot;title&quot;&gt;Foo &amp; Bar&lt;/title&gt;
&lt;/root&gt;
HTML
			],
			'DesignerXMLField should double encode XML' => [
				'\\DesignerXMLField',
				<<<XML
<root>
	<title id="title">Foo &amp; Bar</title>
</root>
XML,
				<<<HTML
&lt;root&gt;
	&lt;title id=&quot;title&quot;&gt;Foo &amp;amp; Bar&lt;/title&gt;
&lt;/root&gt;
HTML
			],
		];
	}
}

<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
namespace Combodo\iTop\Test\UnitTest\sources\Forms\IO\Converter;

use Combodo\iTop\Forms\IO\Converter\StringToBooleanConverter;
use Combodo\iTop\Test\UnitTest\sources\Forms\AbstractFormsTest;

class TestStringToBooleanConverter  extends AbstractFormsTest
{
	public function testConvertingStringToBooleanIsOK()
	{
		$oConverter = new StringToBooleanConverter();
		$oIOFormat = $oConverter->Convert('1');

		$this->assertTrue($oIOFormat->IsTrue());

		//$oIOFormat = $oConverter->Convert(null);
		//$this->assertFalse($oIOFormat->IsFalse());
	}
}
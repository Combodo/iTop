<?php

namespace Combodo\iTop\Test\UnitTest\Core;

use Combodo\iTop\FormSDK\Field\FormFieldTypeEnumeration;
use Combodo\iTop\Test\UnitTest\ItopTestCase;

class FormFieldTypeEnumerationTest extends ItopTestCase {


	/**
	 * @param \Combodo\iTop\FormSDK\Field\FormFieldTypeEnumeration $oType
	 * @param array $aFieldOptions
	 * @param bool $bExpectedValid
	 * @param array $aExpectedInvalidOptions
	 *
	 * @return void
	 * @dataProvider CheckOptionsProvider
	 *
	 */
	public function testCheckOptions(FormFieldTypeEnumeration $oType, array $aFieldOptions, bool $bExpectedValid, array $aExpectedInvalidOptions): void
	{
		$aResult = 	$oType->CheckOptions($aFieldOptions);
		$this->assertIsArray($aResult);
		$this->assertEquals($bExpectedValid, $aResult['valid']);
		$this->assertEquals($aExpectedInvalidOptions, $aResult['invalid_options']);
	}

	public function CheckOptionsProvider() : array
	{
		$this->RequireOnceItopFile( 'sources/FormSDK/Field/FormFieldTypeEnumeration.php');

		return [
			'SELECT with incompatible option with_minutes' => [
				FormFieldTypeEnumeration::SELECT,
				['label' => 'Fruits', 'with_minutes' => true],
				false,
				['with_minutes']
			],
			'SELECT with GENERIC COMPATIBLE options' => [
				FormFieldTypeEnumeration::SELECT,
				['label' => 'Fruits'],
				true,
				[]
			],
			'FIELDSET with SPECIFIC COMPATIBLE options' => [
				FormFieldTypeEnumeration::FIELDSET,
				['layout' => []],
				true,
				[]
			]
		];
	}


}

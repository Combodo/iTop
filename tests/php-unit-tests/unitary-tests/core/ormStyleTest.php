<?php
/*!
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Core;

use Combodo\iTop\Test\UnitTest\ItopTestCase;
use ormStyle;
use utils;

/**
 * Tests of the ormStyle class
 */
class ormStyleTest extends ItopTestCase
{

	/**
	 * @param string $sSetterName
	 * @param string $sGetterName
	 * @param mixed $expectedAfterInstantiation
	 * @param mixed $inputValue
	 * @param mixed $expectedValueAfterSetter
	 *
	 * @covers       ormStyle::GetMainColor
	 * @covers       ormStyle::SetMainColor
	 * @covers       ormStyle::HasMainColor
	 * @covers       ormStyle::GetComplementaryColor
	 * @covers       ormStyle::SetComplementaryColor
	 * @covers       ormStyle::HasComplementaryColor
	 * @covers       ormStyle::GetStyleClass
	 * @covers       ormStyle::SetStyleClass
	 * @covers       ormStyle::HasStyleClass
	 * @covers       ormStyle::GetAltStyleClass
	 * @covers       ormStyle::SetAltStyleClass
	 * @covers       ormStyle::HasAltStyleClass
	 * @covers       ormStyle::GetDecorationClasses
	 * @covers       ormStyle::SetDecorationClasses
	 * @covers       ormStyle::HasDecorationClasses
	 *
	 * @dataProvider BaseSetsProvider
	 */
	public function testPropertiesThatShouldNotBeAnAEmptyString(string $sSetterName, string $sGetterName, $expectedAfterInstantiation, $inputValue, $expectedValueAfterSetter)
	{
		$oStyle = new ormStyle();

		// Test getters straight from instantiation
		// Note: Use of assertTrue instead of assertEquals, otherwise it considers null to be equals to ""
		$this->assertTrue($oStyle->$sGetterName() === $expectedAfterInstantiation);

		// Test that setters don't change passed value
		$oStyle->$sSetterName($inputValue);
		// Note: Use of assertTrue instead of assertEquals, otherwise it considers null to be equals to ""
		$this->assertTrue($oStyle->$sGetterName() === $expectedValueAfterSetter);
	}

	public function BaseSetsProvider(): array
	{
		return [
			'Main color as hexa color' => [
				'SetMainColor',
				'GetMainColor',
				null,
				'#ABCDEF',
				'#ABCDEF',
			],
			'Main color as empty string' => [
				'SetMainColor',
				'GetMainColor',
				null,
				'',
				null,
			],
			'Main color as null' => [
				'SetMainColor',
				'GetMainColor',
				null,
				null,
				null,
			],
			'Main color not present' => [
				'SetMainColor',
				'HasMainColor',
				false,
				null,
				false,
			],
			'Main color present' => [
				'SetMainColor',
				'HasMainColor',
				false,
				'#ABCDEF',
				true,
			],
			'Complementary color as hexa color' => [
				'SetComplementaryColor',
				'GetComplementaryColor',
				null,
				'#ABCDEF',
				'#ABCDEF',
			],
			'Complementary color as empty string' => [
				'SetComplementaryColor',
				'GetComplementaryColor',
				null,
				'',
				null,
			],
			'Complementary color as null' => [
				'SetComplementaryColor',
				'GetComplementaryColor',
				null,
				null,
				null,
			],
			'Complementary color not present' => [
				'SetComplementaryColor',
				'HasComplementaryColor',
				false,
				null,
				false,
			],
			'Complementary color present' => [
				'SetComplementaryColor',
				'HasComplementaryColor',
				false,
				'#ABCDEF',
				true,
			],
			'At least main color present' => [
				'SetMainColor',
				'HasAtLeastOneColor',
				false,
				'#ABCDEF',
				true,
			],
			'At least complementary color present' => [
				'SetComplementaryColor',
				'HasAtLeastOneColor',
				false,
				'#ABCDEF',
				true,
			],
			'Style class as CSS class' => [
				'SetStyleClass',
				'GetStyleClass',
				null,
				'foo-css-class',
				'foo-css-class',
			],
			'Style class as empty string' => [
				'SetStyleClass',
				'GetStyleClass',
				null,
				'',
				null,
			],
			'Style class as null' => [
				'SetStyleClass',
				'GetStyleClass',
				null,
				null,
				null,
			],
			'Style class not present' => [
				'SetStyleClass',
				'HasComplementaryColor',
				false,
				null,
				false,
			],
			'Style class present' => [
				'SetStyleClass',
				'HasStyleClass',
				false,
				'foo-css-class',
				true,
			],
			'Alt style class as CSS class' => [
				'SetAltStyleClass',
				'GetAltStyleClass',
				null,
				'foo-css-class',
				'foo-css-class',
			],
			'Alt style class as empty string' => [
				'SetAltStyleClass',
				'GetAltStyleClass',
				null,
				'',
				null,
			],
			'Alt style class as null' => [
				'SetAltStyleClass',
				'GetAltStyleClass',
				null,
				null,
				null,
			],
			'Alt style class not present' => [
				'SetAltStyleClass',
				'HasAltStyleClass',
				false,
				null,
				false,
			],
			'Alt style class present' => [
				'SetAltStyleClass',
				'HasAltStyleClass',
				false,
				'foo-css-class',
				true,
			],
			'Decoration classes as CSS classes' => [
				'SetMainColor',
				'GetMainColor',
				null,
				'fas fa-user',
				'fas fa-user',
			],
			'Decoration classes as empty string' => [
				'SetMainColor',
				'GetMainColor',
				null,
				'',
				null,
			],
			'Decoration classes as null' => [
				'SetMainColor',
				'GetMainColor',
				null,
				null,
				null,
			],
			'Decoration classes not present' => [
				'SetDecorationClasses',
				'HasDecorationClasses',
				false,
				null,
				false,
			],
			'Decoration classes present' => [
				'SetDecorationClasses',
				'HasDecorationClasses',
				false,
				'fas fa-user',
				true,
			],
		];
	}

	/**
	 * @param string|null $sRegularClass
	 * @param string|null $sAlternativeClass
	 * @param string|null $sMainColor
	 * @param string|null $sComplementaryColor
	 * @param string|null $sDecorationClasses
	 * @param string|null $sIconRelPath
	 *
	 * @throws \Exception
	 * @covers       ormStyle::GetIconAsAbsUrl
	 *
	 * @dataProvider GetIconAsAbsUrlProvider
	 *
	 */
	public function testGetIconAsAbsUrl(?string $sRegularClass, ?string $sAlternativeClass, ?string $sMainColor, ?string $sComplementaryColor, ?string $sDecorationClasses, ?string $sIconRelPath)
	{
		$oStyle = new ormStyle($sRegularClass, $sAlternativeClass, $sMainColor, $sComplementaryColor, $sDecorationClasses, $sIconRelPath);

		$sExpectedIconAbsUrl = (is_null($sIconRelPath) || (strlen($sIconRelPath) === 0)) ? null : utils::GetAbsoluteUrlModulesRoot().$sIconRelPath;
		$this->assertEquals($sExpectedIconAbsUrl, $oStyle->GetIconAsAbsUrl());
	}

	public function GetIconAsAbsUrlProvider(): array
	{
		return [
			'Complete style with icon from /images folder' => [
				'regular-class',
				'alternative-class',
				'#ABCDEF',
				'#123456',
				'fas fa-user',
				'../../images/icons/icons8-organization.svg',
			],
			'Complete style with icon from module folder' => [
				'regular-class',
				'alternative-class',
				'#ABCDEF',
				'#123456',
				'fas fa-user',
				'images/user-request.png',
			],
			'Style with empty icon path' => [
				'regular-class',
				'alternative-class',
				'#ABCDEF',
				'#123456',
				'fas fa-user',
				'',
			],
			'Style with null icon path' => [
				'regular-class',
				'alternative-class',
				'#ABCDEF',
				'#123456',
				'fas fa-user',
				null,
			],
		];
	}
}

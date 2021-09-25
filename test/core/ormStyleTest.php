<?php
/*!
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Core;

use Combodo\iTop\Test\UnitTest\ItopTestCase;
use ormStyle;
use utils;

/**
 * Tests of the ormStyle class
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class ormStyleTest extends ItopTestCase
{

	/**
	 * @param string $sRegularClass
	 * @param string $sAlternativeClass
	 * @param string|null $sMainColor
	 * @param string|null $sComplementaryColor
	 * @param string|null $sDecorationClasses
	 * @param string|null $sIconRelPath
	 *
	 * @covers       ormStyle::GetStyleClass
	 * @covers       ormStyle::GetAltStyleClass
	 * @covers       ormStyle::GetMainColor
	 * @covers       ormStyle::GetComplementaryColor
	 * @covers       ormStyle::GetDecorationClasses
	 * @covers       ormStyle::GetIconAsRelPath
	 * @covers       ormStyle::SetStyleClass
	 * @covers       ormStyle::SetAltStyleClass
	 * @covers       ormStyle::SetMainColor
	 * @covers       ormStyle::SetComplementaryColor
	 * @covers       ormStyle::SetDecorationClasses
	 * @covers       ormStyle::SetIcon
	 *
	 * @dataProvider BaseSetsProvider
	 */
	public function testNonAlteringMethods(string $sRegularClass, string $sAlternativeClass, ?string $sMainColor, ?string $sComplementaryColor, ?string $sDecorationClasses, ?string $sIconRelPath)
	{
		$oStyle = new ormStyle($sRegularClass, $sAlternativeClass, $sMainColor, $sComplementaryColor, $sDecorationClasses, $sIconRelPath);

		// Test getters straight from instantiation
		$this->assertEquals($sRegularClass, $oStyle->GetStyleClass());
		$this->assertEquals($sAlternativeClass, $oStyle->GetAltStyleClass());
		$this->assertEquals($sMainColor, $oStyle->GetMainColor());
		$this->assertEquals($sComplementaryColor, $oStyle->GetComplementaryColor());
		$this->assertEquals($sDecorationClasses, $oStyle->GetDecorationClasses());
		$this->assertEquals($sIconRelPath, $oStyle->GetIconAsRelPath());

		// Test that setters don't change passed value
		$oStyle->SetStyleClass($sRegularClass);
		$this->assertEquals($sRegularClass, $oStyle->GetStyleClass());

		$oStyle->SetAltStyleClass($sAlternativeClass);
		$this->assertEquals($sAlternativeClass, $oStyle->GetAltStyleClass());

		$oStyle->SetMainColor($sMainColor);
		$this->assertEquals($sMainColor, $oStyle->GetMainColor());

		$oStyle->SetComplementaryColor($sComplementaryColor);
		$this->assertEquals($sComplementaryColor, $oStyle->GetComplementaryColor());

		$oStyle->SetDecorationClasses($sDecorationClasses);
		$this->assertEquals($sDecorationClasses, $oStyle->GetDecorationClasses());

		$oStyle->SetIcon($sIconRelPath);
		$this->assertEquals($sIconRelPath, $oStyle->GetIconAsRelPath());
	}

	/**
	 * @param string $sRegularClass
	 * @param string $sAlternativeClass
	 * @param string|null $sMainColor
	 * @param string|null $sComplementaryColor
	 * @param string|null $sDecorationClasses
	 * @param string|null $sIconRelPath
	 *
	 * @covers ormStyle::GetIconAsAbsUrl
	 *
	 * @dataProvider BaseSetsProvider
	 *
	 * @throws \Exception
	 */
	public function testGetIconAsAbsUrl(string $sRegularClass, string $sAlternativeClass, ?string $sMainColor, ?string $sComplementaryColor, ?string $sDecorationClasses, ?string $sIconRelPath)
	{
		$oStyle = new ormStyle($sRegularClass, $sAlternativeClass, $sMainColor, $sComplementaryColor, $sDecorationClasses, $sIconRelPath);

		$sExpectedIconAbsUrl = (is_null($sIconRelPath) || (strlen($sIconRelPath) === 0)) ? null : utils::GetAbsoluteUrlModulesRoot().$sIconRelPath;
		$this->assertEquals($sExpectedIconAbsUrl, $oStyle->GetIconAsAbsUrl());
	}

	public function BaseSetsProvider(): array
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

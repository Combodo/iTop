<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\sources\Forms\IO;

use Combodo\iTop\Forms\IO\FormBlockIOException;
use Combodo\iTop\Test\UnitTest\sources\Forms\AbstractFormsTest;
use Symfony\Component\Form\FormEvents;

class AbstractFormIOTest extends AbstractFormsTest
{
	public function testFormIoHasNoDataAtCreation()
	{

		$oInput = $this->GivenInput('test');

		$this->assertFalse($oInput->IsDataReady(), 'Created Input must no have data ready at creation');
		$this->assertFalse($oInput->HasValue(), 'Created Input must no have value at creation');
		$this->assertFalse($oInput->HasBindingOut());

		$oOutput = $this->GivenOutput('test');

		$this->assertFalse($oOutput->IsDataReady(), 'Created output must no have data ready at creation');
		$this->assertFalse($oOutput->HasValue(), 'Created output must no have value at creation');
		$this->assertFalse($oOutput->HasBindingOut());
	}

	public function testFormIoHasDataAfterSetValue()
	{

		$oInput = $this->GivenInput('test');
		$oInput->SetValue(FormEvents::POST_SET_DATA, 'test');

		$this->assertTrue($oInput->IsDataReady(), 'Input must have data ready when set');
		$this->assertTrue($oInput->HasValue(), 'Input must have value when set');

		$oOutput = $this->GivenOutput('test');
		$oOutput->SetValue(FormEvents::POST_SET_DATA, 'test');

		$this->assertTrue($oOutput->IsDataReady(), 'Output must have data ready when set');
		$this->assertTrue($oOutput->HasValue(), 'Output must have value when set');
	}

	public function testIOValueReflectsTheValuePostedOrTheValueSet()
	{
		$oInput = $this->GivenInput('test');

		// When
		$oInput->SetValue(FormEvents::POST_SET_DATA, 'The value set');

		// Then
		$this->assertEquals('The value set', $oInput->GetValue());

		// When
		$oInput->SetValue(FormEvents::POST_SUBMIT, 'The value posted');

		// Then
		$this->assertEquals('The value posted', $oInput->GetValue());
	}

	/**
	 * @dataProvider NameFormatSupportsOnlyLettersUnderscoreAndNumbersProvider
	 * @return void
	 * @throws \Combodo\iTop\Forms\IO\FormBlockIOException
	 */
	public function testNameFormatSupportsOnlyLettersUnderscoreAndNumbersAndDot(string $sName, bool $bGenerateException = true)
	{

		if ($bGenerateException) {
			$this->expectException(FormBlockIOException::class);
		}
		$oInput = $this->GivenInput($sName);
		if (!$bGenerateException) {
			$this->assertEquals($sName, $oInput->GetName());
		}
	}

	public function NameFormatSupportsOnlyLettersUnderscoreAndNumbersProvider()
	{
		return  [
			// Incorrects
			'Spaces not supported' => ['The test name'],
			'Minus not supported' => ['The-test-name'],
			'Percent not supported' => ['name%'],
			'Accent not supported' => ['namé'],
			'emoji not supported' => ['🎄🎄🎄🎄🎄'],
			'.name not supported' => ['.name'],
			'name. not supported' => ['name.'],

			// Corrects
			'Numbers OK' => ['name123', false],
			'Starting with number OK' => ['123name123', false],
			'Underscore OK' => ['The_test_name', false],
			'Camel OK' => ['TheTestName', false],
			'name.subname OK' => ['name.subname', false],
		];
	}

	public function testCreatingIOWithUnknownFormatThrowsException()
	{
		$this->expectException(FormBlockIOException::class);
		$oInput = $this->GivenInput('test', 'test_toto');
	}
}

<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Sources\Forms\Register;

use Combodo\iTop\Forms\Register\OptionsRegister;
use Combodo\iTop\Forms\Register\RegisterException;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;

class OptionsRegisterTest extends ItopDataTestCase
{
	private OptionsRegister $oOptionsRegister;

	protected function setUp(): void
	{
		parent::setUp();

		$this->oOptionsRegister = new OptionsRegister();
	}

	public function testSetOptionWithInvalidName(): void
	{
		$this->oOptionsRegister->SetOption('valid_option_name', 'value');

		$this->expectException(RegisterException::class);
		$this->oOptionsRegister->SetOption('not valid option name', 'value');
	}

	public function testSetOptionTwice(): void
	{
		$this->oOptionsRegister->SetOption('valid_option_name', 'value');
		$this->oOptionsRegister->SetOption('valid_option_name', 'value2');

		$this->assertEquals('value2', $this->oOptionsRegister->GetOption('valid_option_name'));
	}

	public function testSetNonTypeOption(): void
	{
		$this->oOptionsRegister->SetOption('not_a_type_option', 'value', false);

		$this->assertArrayNotHasKey('not_a_type_option', $this->oOptionsRegister->GetOptions());
	}

	public function testSetOptionArrayValue(): void
	{
		$this->oOptionsRegister->SetOptionArrayValue('att', 'class', 'ibo-class');

		$this->assertEquals('ibo-class', $this->oOptionsRegister->GetOption('att')['class']);
	}

	public function testHasOption(): void
	{
		$this->oOptionsRegister->SetOption('option', true);

		$this->assertTrue($this->oOptionsRegister->HasOption('option'));
	}

}

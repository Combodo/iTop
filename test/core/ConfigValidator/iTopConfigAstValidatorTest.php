<?php
/*
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace ConfigValidator;

use Combodo\iTop\Config\Validator\iTopConfigAstValidator;
use Combodo\iTop\Test\UnitTest\ItopTestCase;
use Exception;

class iTopConfigAstValidatorTest extends ItopTestCase
{
	protected function setUp(): void
	{
		parent::setUp();
		require_once APPROOT.'env-production/itop-config/src/Validator/iTopConfigAstValidator.php';
		require_once APPROOT.'env-production/itop-config/src/Validator/ConfigNodesVisitor.php';
	}

	public function testValidateFileValid()
	{
		try {
			$this->CallValidatorOnFile('config-itop_VALID.php');
		}
		catch (Exception $e) {
			$this->fail('An exception was thrown by the validation method on a valid file: '.$e->getMessage());
		}

		$this->assertTrue(true, 'The file is valid and interpreted as such');
	}

	public function testValidateFileValidLogLevelMinConst()
	{
		$this->markTestSkipped(' disabled test, is failing for now with error "Invalid configuration: LEVEL_WARNING of type Identifier is forbidden in line 152"');
		try {
			$this->CallValidatorOnFile('config-itop_VALID_log-level-min_const.php');
		}
		catch (Exception $e) {
			$this->fail('An exception was thrown by the validation method on a valid file: '.$e->getMessage());
		}

		$this->assertTrue(true, 'The file is valid and interpreted as such');
	}

	public function testValidateFileWithCode()
	{
		$this->expectExceptionMessage('type Stmt_Function is forbidden');
		$this->CallValidatorOnFile('config-itop_KO_function.php');
	}

	public function testValidateFileValidWithCodeAtTheEnd()
	{
		$this->expectExceptionMessage('Stmt_Echo is forbidden');
		$this->CallValidatorOnFile('config-itop_KO_config_plus_code.php');
	}

	/**
	 * @throws \Exception
	 */
	private function CallValidatorOnFile(string $sConfigFilePath)
	{
		$sContents = file_get_contents(__DIR__.DIRECTORY_SEPARATOR.$sConfigFilePath);

		$oiTopConfigValidator = new iTopConfigAstValidator();
		$oiTopConfigValidator->Validate($sContents);
	}
}
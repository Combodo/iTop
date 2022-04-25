<?php

namespace Combodo\iTop\Test\UnitTest\Core;

use ActionEmail;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use Exception;
use MetaModel;
use utils;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 * @covers \ActionEmail
 */
class ActionEmailTest extends ItopDataTestCase
{
	/**
	 * @inheritDoc
	 */
	const CREATE_TEST_ORG = true;

	/** @var \ActionEmail|null Temp ActionEmail created for tests */
	protected static $oActionEmail = null;

	protected function setUp(): void
	{
		parent::setUp();

		static::$oActionEmail = MetaModel::NewObject('ActionEmail', [
			'name' => 'Test action',
			'status' => 'disabled',
			'from' => 'unit-test@openitop.org',
			'subject' => 'Test subject',
			'body' => 'Test body',
		]);
		static::$oActionEmail->DBInsert();
	}

	/**
	 * @covers \ActionEmail::GenerateIdentifierForHeaders
	 * @dataProvider GenerateIdentifierForHeadersProvider
	 * @throws \Exception
	 */
	public function testGenerateIdentifierForHeaders(string $sHeaderName)
	{
		// Retrieve object
		$oObject = MetaModel::GetObject('Organization', $this->getTestOrgId(), true, true);
		$sObjClass = get_class($oObject);
		$sObjId = $oObject->GetKey();

		try {
			$sTestedIdentifier = $this->InvokeNonPublicMethod('\ActionEmail', 'GenerateIdentifierForHeaders', static::$oActionEmail, [$oObject, $sHeaderName]);
		} catch (Exception $oException) {
			$sTestedIdentifier = null;
		}

		$sAppName = utils::Sanitize(ITOP_APPLICATION_SHORT, '', utils::ENUM_SANITIZATION_FILTER_VARIABLE_NAME);
		$sEnvironmentHash = MetaModel::GetEnvironmentId();

		switch ($sHeaderName) {
			case ActionEmail::ENUM_HEADER_NAME_MESSAGE_ID:
				// Note: For this test we can't use the more readable sprintf test as the generated timestamp will never be the same as the one generated during the call of the tested method
				//   $sTimestamp = microtime(true /* get as float*/);
				//   $sExpectedIdentifier = sprintf('%s_%s_%d_%f@%s.openitop.org', $sAppName, $sObjClass, $sObjId, $sTimestamp, $sEnvironmentHash);
				$this->assertEquals(1, preg_match('/'.$sAppName.'_'.$sObjClass.'_'.$sObjId.'_[\d]+\.[\d]+@'.$sEnvironmentHash.'.openitop.org/', $sTestedIdentifier), "Identifier doesn't match regexp for header $sHeaderName, got $sTestedIdentifier");
				break;

			case ActionEmail::ENUM_HEADER_NAME_REFERENCES:
				$sExpectedIdentifier = '<'.sprintf('%s_%s_%d@%s.openitop.org', $sAppName, $sObjClass, $sObjId, $sEnvironmentHash).'>';
				$this->assertEquals($sExpectedIdentifier, $sTestedIdentifier);
				break;

			default:
				$sExpectedIdentifier = null;
				$this->assertEquals($sExpectedIdentifier, $sTestedIdentifier);
				break;
		}

	}

	public function GenerateIdentifierForHeadersProvider()
	{
		return [
			'Message-ID' => ['Message-ID'],
			'References' => ['References'],
			'IncorrectHeaderName' => ['IncorrectHeaderName'],
		];
	}
}
<?php
declare(strict_types=1);

namespace Combodo\iTop\Test\UnitTest\Core;

use ArchivedObjectException;
use AttributeEncryptedString;
use Combodo\iTop\Test\UnitTest\ItopCustomDatamodelTestCase;
use CoreException;
use CoreUnexpectedValue;
use Exception;
use MetaModel;
use ormLinkSet;
use PasswordTest;
use RestResultWithObjects;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class RestServicesSanitizeOutputTest extends ItopCustomDatamodelTestCase
{
	private const SIMPLE_PASSWORD = '123456';

	/**
	 * @return string Abs path to the XML delta to use for the tests of that class
	 */
	public function GetDatamodelDeltaAbsPath(): string
	{
		return __DIR__.'/Delta/delta_test_sanitize_output.xml';
	}

	/**
	 * @return void
	 * @throws CoreException
	 */
	public function testSanitizeAttributeOnRequestedObject()
	{
		$oContactTest = MetaModel::NewObject('ContactTest', [
				'password' => self::SIMPLE_PASSWORD
			]
		);
		$oRestResultWithObject = new RestResultWithObjects();
		$oRestResultWithObject->AddObject(0, 'ok', $oContactTest, ['ContactTest' => ['password']]);
		$oRestResultWithObject->SanitizeContent();
		static::assertJsonStringEqualsJsonString(
			'{"objects":{"ContactTest::-1":{"code":0,"message":"ok","class":"ContactTest","key":-1,"fields":{"password":"*****"}}},"code":0,"message":null}',
			json_encode($oRestResultWithObject));
	}

	/**
	 * @return void
	 * @throws Exception
	 */
	public function testSanitizeAttributeExternalFieldOnLink()
	{
		$oContactTest = $this->createObject('ContactTest', [
				'password' => self::SIMPLE_PASSWORD
			]
		);

		$oTestServer = $this->createObject('TestServer', [
			'name' => 'test_server',
		]);


		// create lnkContactTestToServer
		$oLnkContactTestToServer = $this->createObject('lnkContactTestToServer', [
			'contact_test_id' => $oContactTest->GetKey(),
			'test_server_id'  => $oTestServer->GetKey()
		]);

		$oRestResultWithObject = new RestResultWithObjects();
		$oRestResultWithObject->AddObject(0, 'ok', $oLnkContactTestToServer,
			['lnkContactTestToServer' => ['contact_test_password']]);

		$oRestResultWithObject->SanitizeContent();

		static::assertStringContainsString(
			'*****',
			json_encode($oRestResultWithObject));

		static::assertStringNotContainsString(
			self::SIMPLE_PASSWORD,
			json_encode($oRestResultWithObject));
	}

	/**
	 * @throws Exception
	 */
	public function testSanitizeAttributeOnObjectRelatedThroughNNRelation()
	{
		$oContactTest = $this->createObject('ContactTest', [
			'password' => self::SIMPLE_PASSWORD
		]);

		$oTestServer = $this->createObject('TestServer', [
			'name' => 'test_server',
		]);

		// create lnkContactTestToServer
		$this->createObject('lnkContactTestToServer', [
			'contact_test_id' => $oContactTest->GetKey(),
			'test_server_id'  => $oTestServer->GetKey()
		]);

		$oTestServer->Reload();

		$oRestResultWithObject = new RestResultWithObjects();
		$oRestResultWithObject->AddObject(0, 'ok', $oTestServer,
			['TestServer' => ['contact_list']]);

		$oRestResultWithObject->SanitizeContent();
		static::assertStringContainsString(
			'*****',
			json_encode($oRestResultWithObject));

		static::assertStringNotContainsString(
			self::SIMPLE_PASSWORD,
			json_encode($oRestResultWithObject));
	}


	/**
	 * @throws CoreException
	 * @throws CoreUnexpectedValue
	 * @throws ArchivedObjectException
	 * @throws Exception
	 */
	public function testSanitizeOnObjectRelatedThrough1NRelation()
	{
		$oTestServer = $this->createObject('TestServer', [
			'name' => 'my_server',
		]);

		$oPassword = new PasswordTest();
		$oPassword->Set('password', self::SIMPLE_PASSWORD);
		$oPassword->Set('server_test_id', $oTestServer->GetKey());

		/** @var ormLinkSet $oContactList */
		$oContactList = $oTestServer->Get('password_list');
		$oContactList->AddItem($oPassword);
		$oTestServer->Set('password_list', $oContactList);

		$oRestResultWithObject = new RestResultWithObjects();
		$oRestResultWithObject->AddObject(0, 'ok', $oTestServer, ['TestServer' => ['id', 'password_list']]);
		$oRestResultWithObject->SanitizeContent();

		static::assertStringContainsString(
			'*****',
			json_encode($oRestResultWithObject));

		static::assertStringNotContainsString(
			self::SIMPLE_PASSWORD,
			json_encode($oRestResultWithObject));

	}
}

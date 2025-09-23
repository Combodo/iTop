<?php
declare(strict_types=1);

namespace Combodo\iTop\Test\UnitTest\Core;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use CoreException;
use CoreServices;
use CoreUnexpectedValue;
use RestResultWithObjects;
use UserLocal;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class RestServicesTest extends ItopDataTestCase
{
	/**
	 * @return void
	 * @dataProvider providerTestSanitizeJsonInput
	 */
	public function testSanitizeJsonInput($sJsonData, $sExpectedJsonDataSanitized)
	{
		$oRS = new CoreServices();
		$sOutputJson = $oRS->SanitizeJsonInput($sJsonData);
		static::assertJsonStringEqualsJsonString($sExpectedJsonDataSanitized, $sOutputJson);
	}

	/**
	 * @return array[]
	 */
	public function providerTestSanitizeJsonInput(): array
	{
		return [
			'core/check_credentials' => [
				'{"operation": "core/check_credentials", "user": "admin", "password": "admin"}',
				'{
    "operation": "core/check_credentials",
    "user": "admin",
    "password": "*****"
}'
			],
			'core/update' => [
				'{"operation": "core/update", "comment": "Update user", "class": "UserLocal", "key": {"id":1}, "output_fields": "first_name, password", "fields": {"password" : "123456"}}',
				'{
    "operation": "core/update",
    "comment": "Update user",
    "class": "UserLocal",
    "key": {
        "id": 1
    },
    "output_fields": "first_name, password",
    "fields": {
        "password": "*****"
    }
}'
			],
			'core/create' => [
				'{"operation": "core/create", "comment": "Create user", "class": "UserLocal", "fields": {"first_name": "John", "last_name": "Doe", "email": "jd@example/com", "password" : "123456"}}',
				'{
    "operation": "core/create",
    "comment": "Create user",
    "class": "UserLocal",
    "fields": {
        "first_name": "John",
        "last_name": "Doe",
        "email": "jd@example/com",
        "password": "*****"
    }
}'
			],
		];
	}

	/**
	 * @param $sOperation
	 * @param $aJsonData
	 * @param $sExpectedJsonDataSanitized
	 * @return void
	 * @throws CoreException
	 * @throws CoreUnexpectedValue
	 * @dataProvider providerTestSanitizeJsonOutput
	 */
	public function testSanitizeJsonOutput($sOperation, $aJsonData, $sExpectedJsonDataSanitized)
	{
		$oUser = new UserLocal();
		$oUser->Set('password', '123456');
		$oRestResultWithObject = new RestResultWithObjects();
		$oRestResultWithObject->AddObject(0, 'ok', $oUser, ['UserLocal' => ['login', 'password']]);
		$oRestResultWithObject->SanitizeContent();
		static::assertJsonStringEqualsJsonString($sExpectedJsonDataSanitized, json_encode($oRestResultWithObject));
	}

	/**
	 * @return array[]
	 */
	public function providerTestSanitizeJsonOutput(): array
	{
		return [

			'core/update' => [
				'core/update',
				['comment' => 'Update user', 'class' => 'UserLocal', 'key' => ['login' => 'my_example'], 'output_fields' => 'password', 'fields' => ['password' => 'opkB!req57']],
				'{"objects":{"UserLocal::-1":{"code":0,"message":"ok","class":"UserLocal","key":-1,"fields":{"login":"","password":"*****"}}},"code":0,"message":null}'
			],
			'core/create' => [
				'core/create',
				['comment' => 'Create user', 'class' => 'UserLocal', 'fields' => ['password' => 'Azertyuiiop*12', 'login' => 'toto', 'profile_list' => [1]]],
				'{"objects":{"UserLocal::-1":{"code":0,"message":"ok","class":"UserLocal","key":-1,"fields":{"login":"","password":"*****"}}},"code":0,"message":null}'
			],
			'core/get' => [
				'core/get',
				['comment' => 'Get user', 'class' => 'UserLocal', 'key' => ['login' => 'my_example'], 'output_fields' => 'first_name, password'],
				'{"objects":{"UserLocal::-1":{"code":0,"message":"ok","class":"UserLocal","key":-1,"fields":{"login":"","password":"*****"}}},"code":0,"message":null}'
			],
			'core/check_credentials' => [
				'core/check_credentials',
				['user' => 'admin', 'password' => 'admin'],
				'{"objects":{"UserLocal::-1":{"code":0,"message":"ok","class":"UserLocal","key":-1,"fields":{"login":"","password":"*****"}}},"code":0,"message":null}'
			],
		];
	}
}

<?php

namespace Combodo\iTop\Test\UnitTest\Webservices;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use Exception;
use MetaModel;
use utils;

/**
 * @group itopRequestMgmt
 * @group restApi
 * @group defaultProfiles
 */
class CronTest extends ItopDataTestCase
{
	public const USE_TRANSACTION = false;
	public const CREATE_TEST_ORG = false;

	private static $sLogin;
	private static $sPassword = "Iuytrez9876543ç_è-(";

	/**
	 * This method is called before the first test of this test class is run (in the current process).
	 */
	public static function setUpBeforeClass(): void
	{
		parent::setUpBeforeClass();
	}

	/**
	 * This method is called after the last test of this test class is run (in the current process).
	 */
	public static function tearDownAfterClass(): void
	{
		parent::tearDownAfterClass();
	}

	/**
	 * @throws Exception
	 */
	protected function setUp(): void
	{
		parent::setUp();

		static::$sLogin = "rest-user-".date('dmYHis');

		$this->CreateTestOrganization();
	}

	public function testListOperationsAndJSONPCallback()
	{
		$this->CreateUserWithProfiles([self::$aURP_Profiles['Administrator'], self::$aURP_Profiles['REST Services User']]);
		$aPostFields = [
			'version' => '1.3',
			'auth_user' => static::$sLogin,
			'auth_pwd' => static::$sPassword,
			'json_data' => '{"operation": "list_operations"}',
		];

		// Test regular JSON result
		$sJSONResult = $this->CallItopUri("/webservices/rest.php", $aPostFields);

		$sExpected = <<<JSON
{"code":0,"message":"Operations: 7","version":"1.3","operations":[{"verb":"core\/create","description":"Create an object","extension":"CoreServices"},{"verb":"core\/update","description":"Update an object","extension":"CoreServices"},{"verb":"core\/apply_stimulus","description":"Apply a stimulus to change the state of an object","extension":"CoreServices"},{"verb":"core\/get","description":"Search for objects","extension":"CoreServices"},{"verb":"core\/delete","description":"Delete objects","extension":"CoreServices"},{"verb":"core\/get_related","description":"Get related objects through the specified relation","extension":"CoreServices"},{"verb":"core\/check_credentials","description":"Check user credentials","extension":"CoreServices"}]}
JSON;

		$this->assertEquals($sExpected, $sJSONResult);
	}

	private function CreateUserWithProfiles(array $aProfileIds): void
	{
		if (count($aProfileIds) > 0) {
			$oUser = null;
			foreach ($aProfileIds as $oProfileId) {
				if (is_null($oUser)) {
					$oUser = $this->CreateUser(static::$sLogin, $oProfileId, static::$sPassword);
				} else {
					$this->AddProfileToUser($oUser, $oProfileId);
				}
			}
		}
	}
}

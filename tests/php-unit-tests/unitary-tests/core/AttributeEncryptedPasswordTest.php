<?php

namespace Combodo\iTop\Test\UnitTest\Core;

use AttributeEncryptedPassword;
use Combodo\iTop\Test\UnitTest\ItopCustomDatamodelTestCase;
use MetaModel;
use hiddenUsablePassword;
use RemoteiTopConnectionToken2;

class AttributeEncryptedPasswordTest extends ItopCustomDatamodelTestCase {
	const CREATE_TEST_ORG = true;
	const USE_TRANSACTION = false;

	protected function setUp(): void {
		parent::setUp();
		require_once(APPROOT.'core/attributedef.class.inc.php');
	}

	public function GetDatamodelDeltaAbsPath(): string
	{
		return __DIR__ . '/resources/add-attribute-encrypted-pwd.xml';
	}

	public function testHasAValue_Default()
	{
		$oObject = MetaModel::NewObject(\RemoteiTopConnectionToken2::class);

		// Test attribute without a value yet
		$this->assertEquals(false, $oObject->HasAValue('token'));
	}

	public function HasAValueProvider(){
		return [
			'non empty string' => [ 'gabuzomeu', true ],
			'empty string' => [ '', false ],
			'non empty hiddenUsablePassword' => [ new hiddenUsablePassword('gabuzomeu'), true],
			'empty hiddenUsablePassword' => [ new hiddenUsablePassword(''), false],
		];
	}

	/**
	 * @dataProvider HasAValueProvider
	 */
	public function testHasAValue_hiddenUsablePassword($sValue, bool $bExpected)
	{
		$oObject = MetaModel::NewObject(RemoteiTopConnectionToken2::class);
		$oObject->Set('token', $sValue);
		$this->assertEquals($bExpected, $oObject->HasAValue('token'));
	}

	private function CreateRemoteiTopConnectionToken2(): RemoteiTopConnectionToken2 {
		$oRemoteApplicationType = $this->createObject(\RemoteApplicationType::class, ['name'=> 'toto']);

		/** @var RemoteiTopConnectionToken2 $oRemoteiTopConnectionToken2 */
		$oRemoteiTopConnectionToken2 =  $this->createObject(RemoteiTopConnectionToken2::class,
			[
				'token' => new hiddenUsablePassword('gabuzomeu'),
				//'remoteapplicationtype_id' => $oRemoteApplicationType->GetKey(),
				//'url' => 'http://blabla.fr',
				'name' => 'blabla.fr',
			]
		);
		return $oRemoteiTopConnectionToken2;
	}

	public function testObjectCreation(){
		$oRemoteiTopConnectionToken2 = $this->CreateRemoteiTopConnectionToken2();

		$oToken = $oRemoteiTopConnectionToken2->Get('token');
		$this->assertEquals(hiddenUsablePassword::class, get_class($oToken));

		$sMaskedPwd = '******';
		$this->assertEquals($sMaskedPwd, $oToken->GetDisplayValue());
		$this->assertEquals($sMaskedPwd, $oToken->GetAsHTML());
		$this->assertEquals($sMaskedPwd, '' . $oToken);

		$this->assertEquals('gabuzomeu', $oToken->GetValueForUsage());
	}

	public function testObjectUpdate(){
		$oRemoteiTopConnectionToken2 = $this->CreateRemoteiTopConnectionToken2();
		$oRemoteiTopConnectionToken2 =  $this->updateObject(RemoteiTopConnectionToken2::class, $oRemoteiTopConnectionToken2->GetKey(),
			[
				'token' => new hiddenUsablePassword('gabuzomeu2'),
			]
		);

		$oToken = $oRemoteiTopConnectionToken2->Get('token');
		$this->assertEquals('gabuzomeu2', $oToken->GetValueForUsage());

		$oRemoteiTopConnectionToken2->Reload();
	}
}

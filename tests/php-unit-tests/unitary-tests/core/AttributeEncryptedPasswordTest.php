<?php

namespace Combodo\iTop\Test\UnitTest\Core;

use AttributeEncryptedPassword;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use MetaModel;
use hiddenUsablePassword;
use RemoteiTopConnectionToken;

class AttributeEncryptedPasswordTest extends ItopDataTestCase {
	const CREATE_TEST_ORG = true;
	const USE_TRANSACTION = false;

	protected function setUp(): void {
		parent::setUp();
		require_once(APPROOT.'core/attributedef.class.inc.php');
	}

	public function testHasAValue_Default()
	{
		$oObject = MetaModel::NewObject(\RemoteiTopConnectionToken::class);

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
		$oObject = MetaModel::NewObject(RemoteiTopConnectionToken::class);
		$oObject->Set('token', $sValue);
		$this->assertEquals($bExpected, $oObject->HasAValue('token'));
	}

	private function CreateRemoteiTopConnectionToken(): RemoteiTopConnectionToken {
		$oRemoteApplicationType = $this->createObject(\RemoteApplicationType::class, ['name'=> 'toto']);

		/** @var RemoteiTopConnectionToken $oRemoteiTopConnectionToken */
		$oRemoteiTopConnectionToken =  $this->createObject(RemoteiTopConnectionToken::class,
			[
				'token' => new hiddenUsablePassword('gabuzomeu'),
				'remoteapplicationtype_id' => $oRemoteApplicationType->GetKey(),
				'url' => 'http://blabla.fr',
				'name' => 'blabla.fr',
			]
		);
		return $oRemoteiTopConnectionToken;
	}

	public function testObjectCreation(){
		$oRemoteiTopConnectionToken = $this->CreateRemoteiTopConnectionToken();

		$oToken = $oRemoteiTopConnectionToken->Get('token');
		$this->assertEquals(hiddenUsablePassword::class, get_class($oToken));

		$sMaskedPwd = '******';
		$this->assertEquals($sMaskedPwd, $oToken->GetDisplayValue());
		$this->assertEquals($sMaskedPwd, $oToken->GetAsHTML());
		$this->assertEquals($sMaskedPwd, '' . $oToken);

		$this->assertEquals('gabuzomeu', $oToken->GetValueForUsage());
	}

	public function testObjectUpdate(){
		$oRemoteiTopConnectionToken = $this->CreateRemoteiTopConnectionToken();
		$oRemoteiTopConnectionToken =  $this->updateObject(RemoteiTopConnectionToken::class, $oRemoteiTopConnectionToken->GetKey(),
			[
				'token' => new hiddenUsablePassword('gabuzomeu2'),
			]
		);

		$oToken = $oRemoteiTopConnectionToken->Get('token');
		$this->assertEquals('gabuzomeu2', $oToken->GetValueForUsage());

		$oRemoteiTopConnectionToken->Reload();
	}
}

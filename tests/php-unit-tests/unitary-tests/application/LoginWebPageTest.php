<?php

namespace Combodo\iTop\Test\UnitTest\Application;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;

/**
 * @covers LoginWebPage
 */
class LoginWebPageTest extends ItopDataTestCase
{
	public function testProvisionUserOnly(){
		$iNum = uniqid();
		$sEmail = "ProvisionUser".$iNum . "@gabuzomeu.shadok";
		$oUser = \LoginWebPage::ProvisionUser($sEmail, null, [ 'Portal User']);

		$this->assertNotNull($oUser);

		$oUser = \MetaModel::GetObject(\UserExternal::class, $oUser->GetKey());
		$this->assertNotNull($oUser);
		$this->assertEquals($sEmail, $oUser->Get('login'));
		$this->assertEquals(\MetaModel::GetConfig()->GetDefaultLanguage(), $oUser->Get('language'));
		$this->assertEquals(0, $oUser->Get('contactid'));
	}

	public function testProvisionUserWithPerson(){
		$iNum = uniqid();
		$this->CreateTestOrganization();
		$oPerson = $this->CreatePerson($iNum);

		$sEmail = "ProvisionUser".$iNum . "@gabuzomeu.shadok";
		$oUser = \LoginWebPage::ProvisionUser($sEmail, $oPerson, [ 'Portal User']);

		$this->assertNotNull($oUser);

		$oUser = \MetaModel::GetObject(\UserExternal::class, $oUser->GetKey());
		$this->assertNotNull($oUser);
		$this->assertEquals($sEmail, $oUser->Get('login'));
		$this->assertEquals(\MetaModel::GetConfig()->GetDefaultLanguage(), $oUser->Get('language'));
		$this->assertEquals($oPerson->GetKey(), $oUser->Get('contactid'));
	}
}

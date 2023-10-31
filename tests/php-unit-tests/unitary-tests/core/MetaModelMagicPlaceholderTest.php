<?php

namespace Combodo\iTop\Test\UnitTest\Core;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use MetaModel;
use UserRights;
use VariableExpression;

/**
 * Class MetaModelMagicPlaceholderTest
 * @since 3.1.1 N°6824
 * @covers MetaModel::AddMagicPlaceholders()
 * @package Combodo\iTop\Test\UnitTest\Core
 */
class MetaModelMagicPlaceholderTest extends ItopDataTestCase
{
	protected function setUp(): void
	{
		parent::setUp();
		$this->RequireOnceItopFile('/core/metamodel.class.php');
	}

	protected function tearDown(): void
	{
		parent::tearDown();
	}

	public function AddMagicPlaceholdersProvider(){
		return [
			'a user with contact' => [
				'bUser' => true,
				'bContact' => true,
			],
			'a user WITHOUT contact' => [
				'bUser' => true,
				'bContact' => false,
			],
			'no user no contact' => [
				'bUser' => false,
				'bContact' => false,
			],
		];
	}

	/**
	 * @dataProvider AddMagicPlaceholdersProvider
	 */
	public function testAddMagicPlaceholders_NoExpectedArgs($bUser, $bContact) {
		$this->testAddMagicPlaceholders($bUser, $bContact, null);
	}

	/**
	 * @dataProvider AddMagicPlaceholdersProvider
	 */
	public function testAddMagicPlaceholders_ValidExpectedArgs($bUser, $bContact) {
		$aExpectedArgs = [
			new VariableExpression('current_user->id'),
			new VariableExpression('current_contact_id'),
			new VariableExpression('current_contact->org_id'),
			new VariableExpression('current_user->login'),
		];
		$this->testAddMagicPlaceholders($bUser, $bContact, $aExpectedArgs);
	}

	/**
	 * @dataProvider AddMagicPlaceholdersProvider
	 */
	public function testAddMagicPlaceholders_InValidExpectedArgs($bUser, $bContact) {
		$aExpectedArgs = [
			new VariableExpression('current_user->age'),
			new VariableExpression('current_contact_id'),
			new VariableExpression('current_contact->family'),
		];
		$this->testAddMagicPlaceholders($bUser, $bContact, $aExpectedArgs);
	}

	private function testAddMagicPlaceholders($bUser, $bContact, $aExpectedArgs){
		$aProvidedPlaceholders = [ 'gabu' => "zomeu" ];
		$aNewPlaceHolders = [];

		$_SESSION = [];
		$oUser = null;
		$oPerson = null;
		$sContactId = '';
		if ($bUser) {
			$sContactId = '0';
			$iNum = uniqid();
			$sLogin = "AddMagicPlaceholders".$iNum;

			if ($bContact) {
				$this->CreateTestOrganization();
				$oPerson = $this->CreatePerson($iNum);
				$sContactId = $oPerson->GetKey();
				$oUser = $this->CreateUser($sLogin, 1, "Abcdef@12345678", $oPerson->GetKey());
				$aNewPlaceHolders['current_contact->object()'] = $oPerson;
			} else {
				$oUser = $this->CreateContactlessUser($sLogin, 1, "Abcdef@12345678");
			}

			$aNewPlaceHolders['current_user->object()'] = $oUser;
			$aNewPlaceHolders['current_contact_id'] = $sContactId;

			UserRights::Login($sLogin);
		}

		if (! is_null($aExpectedArgs)) {
			foreach ($aExpectedArgs as $sExpression) {
				$oCurrentObj = null;
				$aName = explode('->', $sExpression->GetName());
				if ($aName[0] == 'current_contact_id') {
					$aNewPlaceHolders['current_contact_id'] = $sContactId;
					continue;
				}
				if ($aName[0] == 'current_user') {
					$oCurrentObj = $oUser;
				} else if ($aName[0] == 'current_contact') {
					$oCurrentObj = $oPerson;
				} else {
					continue;
				}
				$sFieldName = $aName[1];
				if (! is_null($oCurrentObj) && MetaModel::IsValidAttCode(get_class($oCurrentObj), $sFieldName)) {
					$aNewPlaceHolders[$sExpression->GetName()] = $oCurrentObj->Get($sFieldName);
				} else {
					$aNewPlaceHolders[$sExpression->GetName()] = \Dict::Format("PLACEHOLDER_CANNOT_BE_RESOLVED", $sExpression->GetName());
				}
			}
		}

		$aExpectedReturnedPlaceholders = array_merge($aProvidedPlaceholders, $aNewPlaceHolders);

		$aPlaceholders = MetaModel::AddMagicPlaceholders($aProvidedPlaceholders, $aExpectedArgs);

		$aErrors = [];
		foreach ($aExpectedReturnedPlaceholders as $sKey => $oExpectedObj){
			if (! array_key_exists($sKey, $aPlaceholders)){
				$aErrors[] = "missing $sKey";
			} else {
				$oActualObj = $aPlaceholders[$sKey];
				if ($oExpectedObj instanceof \DBObject){
					if (get_class($oExpectedObj) !== get_class($oActualObj)){
						$aErrors[] = sprintf("wrong class for $sKey (actual: %s/ expected:%s )", get_class($oActualObj), get_class($oExpectedObj));
					} else if ($oExpectedObj->GetKey() !== $oActualObj->GetKey()){
						$aErrors[] = sprintf("wrong id for $sKey (actual:%s/ expected:%s )", get_class($oActualObj), get_class($oExpectedObj));
					}
				} else if ($oExpectedObj != $oActualObj) {
					$aErrors[] = sprintf("wrong value for $sKey (actual:%s/ expected:%s)", $oActualObj, $oExpectedObj);
				}
			}
		}
		$this->assertEquals([], $aErrors, var_export($aErrors, true));

		UserRights::Logoff();
	}

}

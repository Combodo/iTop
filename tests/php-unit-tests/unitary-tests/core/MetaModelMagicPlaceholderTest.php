<?php

namespace Combodo\iTop\Test\UnitTest\Core;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use MetaModel;
use PHPUnit\Framework\ExpectationFailedException;
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
	/**
	 * Asserts that two array with DBObjects are equal (the important is to check the {class,id} couple
	 *
	 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
	 * @throws ExpectationFailedException
	 */
	public static function assertEqualsShallow($expected, $actual, string $message = ''): void
	{
		if (is_array($expected)) {
			foreach ($expected as $key => $value) {
				if ($value instanceof \DBObject) {
					$expected[$key] = get_class($value).'::'.$value->GetKey();
				}
			}
			foreach ($actual as $key => $value) {
				if ($value instanceof \DBObject) {
					$actual[$key] = get_class($value).'::'.$value->GetKey();
				}
			}
		}
		parent::assertEquals($expected, $actual, $message);
	}

	public function testAddMagicPlaceholdersWhenLoggedInUserHasAContact()
	{
		// Create data fixture => User + Person
		$iNum = uniqid();
		$sLogin = "AddMagicPlaceholders".$iNum;
		$this->CreateTestOrganization();
		$oPerson = $this->CreatePerson($iNum);
		$sContactId = $oPerson->GetKey();
		$oUser = $this->CreateUser($sLogin, 1, "Abcdef@12345678", $oPerson->GetKey());
		UserRights::Login($sLogin);

		// Test legacy behavior (no expected args)
		$aPlaceholders = MetaModel::AddMagicPlaceholders(['gabu' => "zomeu"]);
		$this->assertEqualsShallow(
			[
				'gabu' => 'zomeu',
				'current_contact_id' => $sContactId,
				'current_user->object()' => $oUser,
				'current_contact->object()' => $oPerson,
			],
			$aPlaceholders,
			'AddMagicPlaceholders without second parameter (legacy) should add "curent_contact_id/current_user->object()/current_contact->object()"'
		);

		// With expected arguments explicitly given as "none"
		$aExpectedArgs = [];
		$aPlaceholders = MetaModel::AddMagicPlaceholders(['gabu' => "zomeu"], $aExpectedArgs);
		$this->assertEqualsShallow(
			[
				'gabu' => 'zomeu',
			],
			$aPlaceholders,
			'AddMagicPlaceholders should add only expected arguments'
		);

		// Test new behavior (with expected args)
		$aExpectedArgs = [
			new VariableExpression('current_user->login'),
			new VariableExpression('current_user->not_existing_attribute'),
			new VariableExpression('current_contact_id'),
			new VariableExpression('current_contact->id'),
			new VariableExpression('current_contact->friendlyname'),
			new VariableExpression('current_contact->org_id'),
			new VariableExpression('current_contact->org_id_friendlyname'),
			new VariableExpression('current_contact->not_existing_attribute'),
		];
		$aPlaceholders = MetaModel::AddMagicPlaceholders(['gabu' => "zomeu"], $aExpectedArgs);
		$this->assertEqualsShallow(
			[
				'gabu' => 'zomeu',
				'current_contact_id' => $sContactId,
				'current_user->object()' => $oUser,
				'current_user->not_existing_attribute' => '(current_user->not_existing_attribute : cannot be resolved)',
				'current_user->login' => $sLogin,
				'current_contact->object()' => $oPerson,
				'current_contact->id' => $oPerson->GetKey(),
				'current_contact->friendlyname' => $oPerson->GetName(),
				'current_contact->org_id' => $oPerson->Get('org_id'),
				'current_contact->org_id_friendlyname' => $oPerson->Get('org_id_friendlyname'),
				'current_contact->not_existing_attribute' => '(current_contact->not_existing_attribute : cannot be resolved)',
			],
			$aPlaceholders,
			'AddMagicPlaceholders should add expected arguments and render them with an explicit error when the information could not be known'
		);
	}

	public function testAddMagicPlaceholdersWhenLoggedInUserHasNoContact()
	{
		// Create data fixture => User without contact
		$iNum = uniqid();
		$sLogin = "AddMagicPlaceholders".$iNum;
		$oUser = $this->CreateContactlessUser($sLogin, 1, "Abcdef@12345678");
		UserRights::Login($sLogin);

		// Test legacy behavior (no expected args)
		$aPlaceholders = MetaModel::AddMagicPlaceholders(['gabu' => "zomeu"]);
		$this->assertEqualsShallow(
			[
				'gabu' => 'zomeu',
				'current_contact_id' => 0,
				'current_user->object()' => $oUser,
			],
			$aPlaceholders,
			'AddMagicPlaceholders without second parameter (legacy) should add "current_contact_id=0/current_user->object()"'
		);

		// Test with expected arguments explicitly given as "none"
		$aExpectedArgs = [];
		$aPlaceholders = MetaModel::AddMagicPlaceholders(['gabu' => "zomeu"], $aExpectedArgs);
		$this->assertEqualsShallow(
			[
				'gabu' => 'zomeu',
			],
			$aPlaceholders,
			'AddMagicPlaceholders should add only expected arguments'
		);

		// Test with a few expected arguments, some of which being invalid attributes
		$aExpectedArgs = [
			new VariableExpression('current_user->login'),
			new VariableExpression('current_user->not_existing_attribute'),
			new VariableExpression('current_contact_id'),
			new VariableExpression('current_contact->org_id'),
			new VariableExpression('current_contact->not_existing_attribute'),
		];
		$aPlaceholders = MetaModel::AddMagicPlaceholders(['gabu' => "zomeu"], $aExpectedArgs);
		$this->assertEqualsShallow(
			[
				'gabu' => 'zomeu',
				'current_contact_id' => 0,
				'current_user->object()' => $oUser,
				'current_user->not_existing_attribute' => '(current_user->not_existing_attribute : cannot be resolved)',
				'current_user->login' => $sLogin,
				'current_contact->object()' => '(current_contact->object() : cannot be resolved)',
				'current_contact->org_id' => '(current_contact->org_id : cannot be resolved)',
				'current_contact->not_existing_attribute' => '(current_contact->not_existing_attribute : cannot be resolved)',
			],
			$aPlaceholders,
			'AddMagicPlaceholders should add expected arguments and render them with an explicit error when the information could not be known'
		);
	}

	public function testAddMagicPlaceholdersWhenThereIsNoLoggedInUser()
	{
		// Test legacy behavior (no expected args)
		$aPlaceholders = MetaModel::AddMagicPlaceholders(['gabu' => "zomeu"]);
		$this->assertEqualsShallow(
			[
				'gabu' => 'zomeu',
				'current_contact_id' => '',
			],
			$aPlaceholders,
			'AddMagicPlaceholders without second parameter (legacy) should add "curent_contact_id"'
		);

		// Test with expected arguments explicitly given as "none"
		$aExpectedArgs = [];
		$aPlaceholders = MetaModel::AddMagicPlaceholders(['gabu' => "zomeu"], $aExpectedArgs);
		$this->assertEqualsShallow(
			[
				'gabu' => 'zomeu',
			],
			$aPlaceholders,
			'AddMagicPlaceholders should add only expected arguments'
		);

		// Test with a few expected arguments, some of which being invalid attributes
		$aExpectedArgs = [
			new VariableExpression('current_user->login'),
			new VariableExpression('current_user->not_existing_attribute'),
			new VariableExpression('current_contact_id'),
			new VariableExpression('current_contact->org_id'),
			new VariableExpression('current_contact->not_existing_attribute'),
		];
		$aPlaceholders = MetaModel::AddMagicPlaceholders(['gabu' => "zomeu"], $aExpectedArgs);
		$this->assertEqualsShallow(
			[
				'gabu' => 'zomeu',
			    'current_contact_id' => '',
				'current_user->object()' => '(current_user->object() : cannot be resolved)',
			    'current_user->not_existing_attribute' => '(current_user->not_existing_attribute : cannot be resolved)',
				'current_user->login' => '(current_user->login : cannot be resolved)',
			    'current_contact->object()' => '(current_contact->object() : cannot be resolved)',
				'current_contact->org_id' => '(current_contact->org_id : cannot be resolved)',
			    'current_contact->not_existing_attribute' => '(current_contact->not_existing_attribute : cannot be resolved)',
			],
			$aPlaceholders,
			'AddMagicPlaceholders should add expected arguments and render them with an explicit error when the information could not be known'
		);
	}
}

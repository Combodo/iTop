<?php

namespace Combodo\iTop\Test\UnitTest\Core;

use AttributeDate;
use AttributeDateTime;
use Change;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use MetaModel;
use UserRequest;

class AttributeDefinitionTest extends ItopDataTestCase {
	const CREATE_TEST_ORG = true;

	protected function setUp(): void {
		parent::setUp();
		require_once(APPROOT.'core/attributedef.class.inc.php');

	}

	public function testGetImportColumns(){
		$oAttributeDefinition = MetaModel::GetAttributeDef("ApplicationSolution", "status");
		$aImportColumns = $oAttributeDefinition->GetImportColumns();
		var_dump($aImportColumns);

		$this->assertTrue(is_array($aImportColumns), var_export($aImportColumns, true));
		$this->assertEquals(["status" => "ENUM('active','inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"],
			$aImportColumns);
	}

	/**
	 * @dataProvider HasAValueProvider
	 * @covers AttributeDefinition::HasAValue
	 *
	 * @param $sObjectClass
	 * @param $sAttCode
	 * @param $sUpdateCode
	 * @param $bHasAValueInitially
	 * @param $bHasAValueOnceSet
	 *
	 * @return void
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 */
	public function testHasAValue($sObjectClass, $sAttCode, $sUpdateCode, $bHasAValueInitially, $bHasAValueOnceSet)
	{
		$oObject = MetaModel::NewObject($sObjectClass);

		// Test attribute without a value yet
		$this->assertEquals($bHasAValueInitially, $oObject->HasAValue($sAttCode));

		eval($sUpdateCode);

		// Test attribute once a value has been set
		$this->assertEquals($bHasAValueOnceSet, $oObject->HasAValue($sAttCode));
	}

	public function HasAValueProvider(): array
	{
		// Note: This is test is not great as we are datamodel dependent and don't have a class with all the attribute types
		return [
			'AttributeDateTime' => [
				UserRequest::class,
				'start_date', // no default value on this field
				<<<PHP
\$oObject->Set('start_date', '2023-09-06 12:26:00');
PHP
				,
				false,
				true,
			],
			'AttributeFrienlyName' => [
				UserRequest::class,
				'friendlyname',
				'',
				true,
				true,
			],
			'AttributeDashboard' => [
				'Organization',
				'overview',
				'',
				false,
				false,
			],
			'AttributeLinkedSet' => [
				'UserRequest',
				'workorders_list',
				<<<PHP
/** @var \ormLinkSet \$ormLinkset */
\$ormLinkset = \$oObject->Get('workorders_list');
\$ormLinkset->AddItem(MetaModel::NewObject('WorkOrder', []));
\$oObject->Set('workorders_list', \$ormLinkset);
PHP
				,
				false,
				true,
			],
			'AttributeLinkedSetIndirect' => [
				'UserRequest',
				'contacts_list',
				<<<PHP
/** @var \ormLinkSet \$ormLinkset */
\$ormLinkset = \$oObject->Get('contacts_list');
\$ormLinkset->AddItem(MetaModel::NewObject('lnkContactToTicket', []));
\$oObject->Set('contacts_list', \$ormLinkset);
PHP
				,
				false,
				true,
			],
			'AttributeInteger' => [
				'SLT',
				'value',
				<<<PHP
\$oObject->Set('value', 100);
PHP
				,
				false,
				true,
			],
			'AttributeDecimal' => [
				'PhysicalInterface',
				'speed',
				<<<PHP
\$oObject->Set('speed', 1024.5);
PHP
				,
				false,
				true,
			],
			'AttributeString' => [
				'UserRequest',
				'title',
				<<<PHP
\$oObject->Set('title', 'Some title');
PHP
				,
				false,
				true,
			],
			'AttributeObjectKey' => [
				'Attachment',
				'item_id',
				<<<PHP
\$oObject->Set('item_id', 12);
PHP
				,
				false,
				true,
			],
			'AttributeExternalKey' => [
				'UserRequest',
				'org_id',
				<<<PHP
\$oObject->Set('org_id', 3);
PHP
				,
				false,
				true,
			],
			'AttributeBlob' => [
				'DocumentFile',
				'file',
				<<<PHP
\$oObject->Set('file', new ormDocument('something', 'text/plain', 'something.txt'));
PHP
				,
				false,
				true,
			],
			'AttributeStopWatch' => [
				'UserRequest',
				'tto',
				'',
				true,
				true,
			],
			'AttributeSubItem' => [
				'UserRequest',
				'tto_escalation_deadline',
				'', // read-only attribute
				false,
				false,
			],
			'AttributeOneWayPassword' => [
				'UserLocal',
				'password',
				<<<PHP
/** @var \ormPassword \$ormPassword */
\$ormPassword = new ormPassword('somehash', 'somesalt');
\$oObject->Set('password', \$ormPassword);
PHP
				,
				false,
				true,
			],
		];
	}

	public function testMakeFormField(): void
	{
		$oPerson = $this->CreatePerson(1);
		$oPerson->Set('email', 'toto@tutu.com');
		$oAttDef = MetaModel::GetAttributeDef(get_class($oPerson), 'email');
		$oFormFieldWithTouchedAtt = $oAttDef->MakeFormField($oPerson);
		$this->assertFalse($oFormFieldWithTouchedAtt->IsValidationDisabled(), 'email is part of modified fields, we must have field validation');

		$oPerson->DBUpdate(); // reset list of changed attributes
		$oFormFieldNoTouchedAtt = $oAttDef->MakeFormField($oPerson);
		$this->assertTrue($oFormFieldNoTouchedAtt->IsValidationDisabled(), 'email wasn\'t modified, we must not validate the corresponding field');
	}

	/**
	 * @dataProvider WithConstraintParameterProvider
	 *
	 * @param string $sClass
	 * @param string $sAttCode
	 * @param bool $bConstraintExpected
	 * @param bool $bComputationExpected
	 *
	 * @return void
	 * @throws \Exception
	 */
	public function testWithConstraintAndComputationParameters(string $sClass, string $sAttCode, bool $bConstraintExpected, bool $bComputationExpected)
	{
		$oAttDef = \MetaModel::GetAttributeDef($sClass, $sAttCode);
		$sConstraintExpected = $bConstraintExpected ? 'true' : 'false';
		$sComputationExpected = $bComputationExpected ? 'true' : 'false';
		$this->assertEquals($bConstraintExpected, $oAttDef->HasPHPConstraint(), "Standard DataModel should be configured with property 'has_php_constraint'=$sConstraintExpected for $sClass:$sAttCode");
		$this->assertEquals($bComputationExpected, $oAttDef->HasPHPComputation(), "Standard DataModel should be configured with property 'has_php_computation'=$sComputationExpected for $sClass:$sAttCode");
	}

	public static function WithConstraintParameterProvider()
	{
		return [
			['User', 'profile_list', true, true],
			['User', 'allowed_org_list', true, false],
			['Person', 'team_list', false, false],
			['Ticket', 'functionalcis_list', false, true],
		];
	}

	public function testDateTimeEmptyDefaultReturnsNullAsDefaultValue()
	{
		// Given
		$oDateAttribute = new AttributeDateTime('start_date', ['sql' => 'start_date', 'is_null_allowed' => false, 'default_value' => '', 'allowed_values' => null, 'depends_on' => [], 'always_load_in_tables' => false]);
		$oDateAttribute->SetHostClass('WorkOrder');

		//When
		$defaultValue = $oDateAttribute->GetDefaultValue();

		// Then
		self::assertNull($defaultValue, 'Empty default value for DateTime attribute should give null default value');
	}

	public function testDateEmptyDefaultReturnsNullAsDefaultValue()
	{
		// Given
		$oDateAttribute = new AttributeDate('start_date', ['sql' => 'start_date', 'is_null_allowed' => false, 'default_value' => '', 'allowed_values' => null, 'depends_on' => [], 'always_load_in_tables' => false]);
		$oDateAttribute->SetHostClass('WorkOrder');

		//When
		$defaultValue = $oDateAttribute->GetDefaultValue();

		// Then
		self::assertNull($defaultValue, 'Empty default value for Date attribute should give null default value');
	}


	public function testDateTimeNowAsDefaultGivesCurrentDateAsDefaultValue()
	{
		// Given
		$oDateAttribute = new AttributeDateTime('start_date', [
			'sql' => 'start_date',
			'is_null_allowed' => false,
			'default_value' => 'NOW()',
			'allowed_values' => null,
			'depends_on' => [],
			'always_load_in_tables' => false
		]);
		$oDateAttribute->SetHostClass('WorkOrder');

		//When
		$defaultValue = $oDateAttribute->GetDefaultValue();

		// Then
		$sNow = date($oDateAttribute->GetInternalFormat());
		self::assertEquals($sNow, $defaultValue, 'Now as default value for DateTime attribute should give current date as default value');
	}


	public function testDateNowAsDefaultGivesCurrentDateAsDefaultValue()
	{
		// Given
		$oDateAttribute = new AttributeDate('start_date', [
			'sql' => 'start_date',
			'is_null_allowed' => false,
			'default_value' => 'NOW()',
			'allowed_values' => null,
			'depends_on' => [],
			'always_load_in_tables' => false
		]);
		$oDateAttribute->SetHostClass('WorkOrder');

		//When
		$defaultValue = $oDateAttribute->GetDefaultValue();

		// Then
		$sNow = date($oDateAttribute->GetInternalFormat());
		self::assertEquals($sNow, $defaultValue, 'Now as default value for Date attribute should give current date as default value');
	}

	public function testDateTimeIntervalAsDefaultGivesCorrectDateAsDefaultValue()
	{
		// Given
		$oDateAttribute = new AttributeDateTime('start_date', ['sql' => 'start_date', 'is_null_allowed' => false, 'default_value' => 'DATE_ADD(NOW(), INTERVAL 1 DAY)', 'allowed_values' => null, 'depends_on' => [], 'always_load_in_tables' => false]);
		$oDateAttribute->SetHostClass('WorkOrder');

		//When
		$defaultValue = $oDateAttribute->GetDefaultValue();

		// Then
		$oDate = new \DateTimeImmutable('+1day');
		$sExpected = $oDate->format($oDateAttribute->GetInternalFormat());
		self::assertEquals($sExpected, $defaultValue, 'Interval as default value for DateTime attribute should give correct date as default value');
	}

	public function testDateIntervalAsDefaultGivesCorrectDateAsDefaultValue()
	{
		// Given
		$oDateAttribute = new AttributeDate('start_date', ['sql' => 'start_date', 'is_null_allowed' => false, 'default_value' => 'DATE_ADD(NOW(), INTERVAL 1 DAY)', 'allowed_values' => null, 'depends_on' => [], 'always_load_in_tables' => false]);
		$oDateAttribute->SetHostClass('WorkOrder');

		//When
		$defaultValue = $oDateAttribute->GetDefaultValue();

		// Then
		$oDate = new \DateTimeImmutable('+1day');
		$sExpected = $oDate->format($oDateAttribute->GetInternalFormat());
		self::assertEquals($sExpected, $defaultValue, 'Interval as default value for Date attribute should give correct date as default value');
	}

}
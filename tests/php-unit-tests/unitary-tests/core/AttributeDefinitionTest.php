<?php

namespace Combodo\iTop\Test\UnitTest\Core;

use AttributeDate;
use AttributeDateTime;
use Change;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use MetaModel;
use UserRequest;

class AttributeDefinitionTest extends ItopDataTestCase
{
	public const CREATE_TEST_ORG = true;

	public function testGetImportColumns()
	{
		$oAttributeDefinition = MetaModel::GetAttributeDef("ApplicationSolution", "status");
		$aImportColumns = $oAttributeDefinition->GetImportColumns();
		var_dump($aImportColumns);

		$this->assertTrue(is_array($aImportColumns), var_export($aImportColumns, true));
		$this->assertEquals(
			["status" => "ENUM('active','inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"],
			$aImportColumns
		);
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
		$oDateAttribute = $this->GivenAttribute(\WorkOrder::class, 'start_date', AttributeDateTime::class, '', false);

		$defaultValue = $oDateAttribute->GetDefaultValue();

		self::assertNull($defaultValue, 'Empty default value for DateTime attribute should give null default value');
	}

	public function testDateTimeInvalidDefaultReturnsNullAsDefaultValue()
	{
		$oDateAttribute = $this->GivenAttribute(\WorkOrder::class, 'start_date', AttributeDateTime::class, 'zabugomeuh', false);

		$defaultValue = $oDateAttribute->GetDefaultValue();

		self::assertNull($defaultValue, 'Invalid default value for DateTime attribute should give null default value');
		$this->AssertLastErrorLogEntryContains("Invalid default value 'zabugomeuh' for field 'start_date' on class 'WorkOrder', defaulting to null", "Last error log entry should contain a meaningful message");
	}

	public function testDateEmptyDefaultReturnsNullAsDefaultValue()
	{
		$oDateAttribute = $this->GivenAttribute(\WorkOrder::class, 'start_date', AttributeDate::class, '', false);

		$defaultValue = $oDateAttribute->GetDefaultValue();

		self::assertNull($defaultValue, 'Empty default value for Date attribute should give null default value');
	}

	public function testDateInvalidDefaultReturnsNullAsDefaultValue_Case1()
	{
		$oDateAttribute = $this->GivenAttribute(\WorkOrder::class, 'start_date', AttributeDate::class, 'zabugomeuh', false);

		$defaultValue = $oDateAttribute->GetDefaultValue();
		$this->AssertLastErrorLogEntryContains("Invalid default value 'zabugomeuh' for field 'start_date' on class 'WorkOrder', defaulting to null", "Last error log entry should contain a meaningful message");

		self::assertNull($defaultValue, 'Invalid default value for Date attribute should give null default value');
	}

	public function testDateInvalidDefaultReturnsNullAsDefaultValue_Case2()
	{
		$oDateAttribute = $this->GivenAttribute(\WorkOrder::class, 'start_date', AttributeDate::class, '"27/01/2025"', false);

		$defaultValue = $oDateAttribute->GetDefaultValue();

		$this->AssertLastErrorLogEntryContains("Invalid default value '\"27/01/2025\"' for field 'start_date' on class 'WorkOrder', defaulting to null", "Last error log entry should contain a meaningful message");
		self::assertNull($defaultValue, 'Invalid default value for Date attribute should give null default value');
	}

	public function testDateTimeNowAsDefaultGivesCurrentDateAsDefaultValue()
	{
		$oDateAttribute = $this->GivenAttribute(\WorkOrder::class, 'start_date', AttributeDateTime::class, 'NOW()', false);
		$sDefaultValue = $oDateAttribute->GetDefaultValue();

		self::AssertDateTimeEqualsNow($sDefaultValue, 'NOW() should be evaluated as the current date and time');
	}

	public function testDateNowAsDefaultGivesCurrentDateAsDefaultValue()
	{
		$oDateAttribute = $this->GivenAttribute(\WorkOrder::class, 'start_date', AttributeDate::class, 'NOW()', false);

		$defaultValue = $oDateAttribute->GetDefaultValue();

		self::AssertDateEqualsNow($defaultValue, 'NOW() should be evaluated as the current date');
	}

	public function testDateTimeIntervalAsDefaultGivesCorrectDateAsDefaultValue()
	{
		$oDateAttribute = $this->GivenAttribute(\WorkOrder::class, 'start_date', AttributeDateTime::class, 'DATE_ADD(NOW(), INTERVAL 1 DAY)', false);

		$defaultValue = $oDateAttribute->GetDefaultValue();

		$oDate = new \DateTimeImmutable('+1day');
		$sExpected = $oDate->format($oDateAttribute->GetInternalFormat());
		self::assertEquals($sExpected, $defaultValue, 'Interval as default value for DateTime attribute should give correct date as default value');
	}

	public function testDateIntervalAsDefaultGivesCorrectDateAsDefaultValue()
	{
		$oDateAttribute = $this->GivenAttribute(\WorkOrder::class, 'start_date', AttributeDate::class, 'DATE_ADD(NOW(), INTERVAL 1 DAY)', false);

		$defaultValue = $oDateAttribute->GetDefaultValue();

		$oDate = new \DateTimeImmutable('+1day');
		$sExpected = $oDate->format($oDateAttribute->GetInternalFormat());
		self::assertEquals($sExpected, $defaultValue, 'Interval as default value for Date attribute should give correct date as default value');
	}

	public function GivenAttribute(string $sHostClass, string $sAttCode, string $sAttributeClass, mixed $defaultValue, bool $bIsNullAllowed): \AttributeDefinition
	{
		$oAttribute = new $sAttributeClass($sAttCode, [
			'sql'                   => $sAttCode,
			'is_null_allowed'       => $bIsNullAllowed,
			'default_value'         => $defaultValue,
			'allowed_values'        => null,
			'depends_on'            => [],
			'always_load_in_tables' => false,
		]);
		$oAttribute->SetHostClass($sHostClass);
		return $oAttribute;
	}

}

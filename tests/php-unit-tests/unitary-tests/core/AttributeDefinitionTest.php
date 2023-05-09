<?php

namespace Combodo\iTop\Test\UnitTest\Core;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use MetaModel;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
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
				'',
				true,
				true,
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
}
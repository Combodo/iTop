<?php

/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Core\AttributeDefinition;

use Combodo\iTop\Core\AttributeDefinition\AttributeCaseLog;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use CoreException;
use Dict;
use MetaModel;
use ormCaseLog;

/**
 * Tests for CaseLog change-record metadata.
 *
 * @since 3.3.0 N°1365 - Test CaseLog edit snapshots
 */
class AttributeCaseLogTest extends ItopDataTestCase
{
	public const CREATE_TEST_ORG = true;

	/**
	 * @covers \Combodo\iTop\Core\AttributeDefinition\AttributeCaseLog::GetChangeRecordAdditionalData
	 * @since 3.3.0 N°1365 - Store edited CaseLog content snapshots
	 */
	public function testGetChangeRecordAdditionalDataStoresEditedSnapshots(): void
	{
		$oAttribute = MetaModel::GetAttributeDef('UserRequest', 'private_log');
		$this->assertInstanceOf(AttributeCaseLog::class, $oAttribute);

		$oOriginalLog = new ormCaseLog();
		$oOriginalLog->AddLogEntry('Original entry', 'Original author');
		$oEditedLog = clone $oOriginalLog;
		$oEditedLog->UpdateEntryAt(0, '<p>Edited entry</p>');

		$oChangeOp = MetaModel::NewObject('CMDBChangeOpSetAttributeCaseLog');
		$this->InvokeNonPublicMethod(AttributeCaseLog::class, 'GetChangeRecordAdditionalData', $oAttribute, [
			$oChangeOp,
			MetaModel::NewObject('UserRequest'),
			$oOriginalLog,
			$oEditedLog,
		]);

		$this->assertSame(0, (int)$oChangeOp->Get('lastentry'));
		$this->assertTrue((bool)$oChangeOp->Get('is_edit'));
		$this->assertSame('<p>Original entry</p>', $oChangeOp->Get('oldvalue'));
		$this->assertSame('<p>Edited entry</p>', $oChangeOp->Get('newvalue'));
	}

	/**
	 * @covers \Combodo\iTop\Core\AttributeDefinition\AttributeCaseLog::GetChangeRecordAdditionalData
	 * @since 3.3.0 N°1365 - Record appended CaseLog entries without edit snapshots
	 */
	public function testGetChangeRecordAdditionalDataLeavesAppendSnapshotsEmpty(): void
	{
		$oAttribute = MetaModel::GetAttributeDef('UserRequest', 'private_log');
		$oOriginalLog = new ormCaseLog();
		$oOriginalLog->AddLogEntry('Original entry', 'Original author');
		$oAppendedLog = clone $oOriginalLog;
		$oAppendedLog->AddLogEntry('Appended entry', 'Another author');

		$oChangeOp = MetaModel::NewObject('CMDBChangeOpSetAttributeCaseLog');
		$this->InvokeNonPublicMethod(AttributeCaseLog::class, 'GetChangeRecordAdditionalData', $oAttribute, [
			$oChangeOp,
			MetaModel::NewObject('UserRequest'),
			$oOriginalLog,
			$oAppendedLog,
		]);

		$this->assertSame(1, (int)$oChangeOp->Get('lastentry'));
		$this->assertFalse((bool)$oChangeOp->Get('is_edit'));
		$this->assertSame('', $oChangeOp->Get('oldvalue'));
		$this->assertSame('', $oChangeOp->Get('newvalue'));
	}

	/**
	 * @covers \Combodo\iTop\Core\AttributeDefinition\AttributeCaseLog::GetChangeRecordAdditionalData
	 * @since 3.3.0 N°1365 - Reject edits without an original CaseLog snapshot
	 */
	public function testGetChangeRecordAdditionalDataRequiresAnOriginalLog(): void
	{
		$oAttribute = MetaModel::GetAttributeDef('UserRequest', 'private_log');
		$oEditedLog = new ormCaseLog();
		$oEditedLog->AddLogEntry('Edited entry', 'Original author');
		$oEditedLog->UpdateEntryAt(0, '<p>Edited entry</p>');
		$oChangeOp = MetaModel::NewObject('CMDBChangeOpSetAttributeCaseLog');

		$this->expectException(CoreException::class);
		$this->InvokeNonPublicMethod(AttributeCaseLog::class, 'GetChangeRecordAdditionalData', $oAttribute, [
			$oChangeOp,
			MetaModel::NewObject('UserRequest'),
			null,
			$oEditedLog,
		]);
	}

	/**
	 * @covers \CMDBChangeOpSetAttributeCaseLog::GetDescription
	 * @since 3.3.0 N°1365 - Distinguish edited CaseLog entries in native history
	 */
	public function testGetDescriptionDistinguishesEditedEntriesFromNewEntries(): void
	{
		$oObject = $this->CreateUserRequest('CaseLogEditDescription'.uniqid());
		$oCaseLog = new ormCaseLog();
		$oCaseLog->AddLogEntry('Edited entry');
		$oObject->Set('private_log', $oCaseLog);
		$oObject->DBUpdate();

		$oChangeOp = MetaModel::NewObject('CMDBChangeOpSetAttributeCaseLog');
		$oChangeOp->Set('objclass', 'UserRequest');
		$oChangeOp->Set('objkey', $oObject->GetKey());
		$oChangeOp->Set('attcode', 'private_log');
		$oChangeOp->Set('lastentry', 0);

		$sAttributeLabel = MetaModel::GetAttributeDef('UserRequest', 'private_log')->GetLabel();
		$sEntryHtml = '<div class="history_entry history_entry_truncated"><div class="history_html_content">'.$oCaseLog->GetEntryAt(0).'</div></div>';

		$oChangeOp->Set('is_edit', true);
		$this->assertSame(
			Dict::Format('Change:AttName_EntryEdited', $sAttributeLabel, $sEntryHtml),
			$oChangeOp->GetDescription()
		);

		$oChangeOp->Set('is_edit', false);
		$this->assertSame(
			Dict::Format('Change:AttName_EntryAdded', $sAttributeLabel, $sEntryHtml),
			$oChangeOp->GetDescription()
		);
	}
}

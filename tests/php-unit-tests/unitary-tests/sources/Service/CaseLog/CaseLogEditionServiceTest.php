<?php

/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Service\CaseLog;

use Combodo\iTop\Application\EventRegister\ApplicationEvents;
use Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry\ActivityEntryFactory;
use Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry\CaseLogEditEntry;
use Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry\CaseLogEntry;
use Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry\EditsEntry;
use Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityPanel;
use Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityPanelFactory;
use Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityPanelHelper;
use Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityPanelPrint;
use Combodo\iTop\Controller\Base\Layout\ActivityPanelController;
use Combodo\iTop\Renderer\BlockRenderer;
use Combodo\iTop\Service\CaseLog\CaseLogEditionService;
use Combodo\iTop\Service\Events\EventData;
use Combodo\iTop\Service\Events\EventService;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use CMDBObject;
use CoreException;
use DateTime;
use DBObjectSearch;
use DBObjectSet;
use MetaModel;
use UserRights;
use utils;
use ormCaseLog;

/**
 * Tests for editing existing CaseLog entries.
 *
 * @since 3.3.0 N°1365 - Test CaseLog edition
 */
class CaseLogEditionServiceTest extends ItopDataTestCase
{
	public const CREATE_TEST_ORG = true;

	private $oService;
	private $oObject;
	private $oEditor;

	/**
	 * @since 3.3.0 N°1365 - Prepare a CaseLog and its owning user
	 */
	protected function setUp(): void
	{
		parent::setUp();

		$oConfig = MetaModel::GetConfig();
		$oConfig->Set('caselog_edition.enabled', false);
		$oConfig->Set('caselog_edition.modification_delay', 0);
		$oConfig->Set('caselog_edition.allowed_profiles', []);
		$oConfig->Set('caselog_edition.allowed_classes', []);
		$oConfig->Set('caselog_edition.allowed_case_logs', []);

		$this->oService = CaseLogEditionService::GetInstance();
		$this->oEditor = $this->CreateContactlessUser('CaseLogEditor'.uniqid(), self::$aURP_Profiles['Administrator'], 'Password1!');
		$_SESSION = [];
		UserRights::Login($this->oEditor->Get('login'));

		$this->oObject = $this->CreateUserRequest('CaseLogEdition'.uniqid());
		$oCaseLog = new ormCaseLog();
		$oCaseLog->AddLogEntry('First CaseLog entry');
		$this->oObject->Set('private_log', $oCaseLog);
		$this->oObject->DBUpdate();

		if (!EventService::IsEventRegistered(ApplicationEvents::APPLICATION_EVENT_CASELOG_ENTRY_EDITED)) {
			(new ApplicationEvents())->RegisterEventsAndListeners();
		}
	}

	/**
	 * @covers \Combodo\iTop\Service\CaseLog\CaseLogEditionService::GetInstance
	 * @since 3.3.0 N°1365 - Verify the CaseLog service singleton
	 */
	public function testGetInstanceReturnsTheSameService(): void
	{
		$this->assertSame($this->oService, CaseLogEditionService::GetInstance());
	}

	/**
	 * @covers \Combodo\iTop\Service\CaseLog\CaseLogEditionService::CanEditEntry
	 * @since 3.3.0 N°1365 - Verify the edition feature switch and entry indexes
	 */
	public function testCanEditEntryRequiresEnabledFeatureAndValidEntry(): void
	{
		$this->assertFalse($this->oService->CanEditEntry($this->oObject, 'private_log', 0));

		MetaModel::GetConfig()->Set('caselog_edition.enabled', true);
		$this->assertTrue($this->oService->CanEditEntry($this->oObject, 'private_log', 0));
		$this->assertFalse($this->oService->CanEditEntry($this->oObject, 'private_log', -1));
		$this->assertFalse($this->oService->CanEditEntry($this->oObject, 'private_log', 1));
		$this->assertFalse($this->oService->CanEditEntry($this->oObject, 'title', 0));
		$this->assertFalse($this->oService->CanEditEntry($this->oObject, 'not_an_attribute', 0));

		$oNewObject = MetaModel::NewObject('UserRequest');
		$this->assertFalse($this->oService->CanEditEntry($oNewObject, 'private_log', 0));

		$oEmptyObject = MetaModel::GetObject('UserRequest', $this->oObject->GetKey());
		$oEmptyObject->Set('private_log', new ormCaseLog());
		$this->assertFalse($this->oService->CanEditEntry($oEmptyObject, 'private_log', 0));
	}

	/**
	 * @covers \Combodo\iTop\Service\CaseLog\CaseLogEditionService::CanEditEntry
	 * @since 3.3.0 N°1365 - Verify CaseLog authorship and modification windows
	 */
	public function testCanEditEntryChecksAuthorAndModificationDelay(): void
	{
		MetaModel::GetConfig()->Set('caselog_edition.enabled', true);
		$oOtherUser = $this->CreateContactlessUser('CaseLogOther'.uniqid(), self::$aURP_Profiles['Administrator'], 'Password1!');
		$oCaseLog = $this->oObject->Get('private_log');
		$oCaseLog->AddLogEntry('Another user entry', '', $oOtherUser->GetKey());
		$oCaseLog->AddLogEntryFromJSON((object)[
			'date' => date('Y-m-d H:i:s', time() - 7200),
			'message' => '<p>Expired entry</p>',
			'user_id' => UserRights::GetUserId(),
		]);
		$this->oObject->Set('private_log', $oCaseLog);
		$this->oObject->DBUpdate();

		$iExpiredEntryIndex = $oCaseLog->GetLatestEntryIndex();
		MetaModel::GetConfig()->Set('caselog_edition.modification_delay', 1);
		$this->assertTrue($this->oService->CanEditEntry($this->oObject, 'private_log', 0));
		$this->assertFalse($this->oService->CanEditEntry($this->oObject, 'private_log', 1));
		$this->assertFalse($this->oService->CanEditEntry($this->oObject, 'private_log', $iExpiredEntryIndex));

		MetaModel::GetConfig()->Set('caselog_edition.modification_delay', 0);
		$this->assertTrue($this->oService->CanEditEntry($this->oObject, 'private_log', $iExpiredEntryIndex));
		MetaModel::GetConfig()->Set('caselog_edition.modification_delay', '');
		$this->assertTrue($this->oService->CanEditEntry($this->oObject, 'private_log', $iExpiredEntryIndex));
		MetaModel::GetConfig()->Set('caselog_edition.modification_delay', null);
		$this->assertTrue($this->oService->CanEditEntry($this->oObject, 'private_log', $iExpiredEntryIndex));
		MetaModel::GetConfig()->Set('caselog_edition.modification_delay', -1);
		$this->assertFalse($this->oService->CanEditEntry($this->oObject, 'private_log', $iExpiredEntryIndex));
	}

	/**
	 * @covers \Combodo\iTop\Service\CaseLog\CaseLogEditionService::CanEditEntry
	 * @since 3.3.0 N°1365 - Verify CaseLog profile, class and attribute allow-lists
	 */
	public function testCanEditEntryHonorsConfiguredAllowlists(): void
	{
		MetaModel::GetConfig()->Set('caselog_edition.enabled', true);
		$this->assertTrue($this->oService->CanEditEntry($this->oObject, 'private_log', 0));

		MetaModel::GetConfig()->Set('caselog_edition.allowed_profiles', ['Portal user']);
		$this->assertFalse($this->oService->CanEditEntry($this->oObject, 'private_log', 0));
		MetaModel::GetConfig()->Set('caselog_edition.allowed_profiles', ['Administrator']);
		$this->assertTrue($this->oService->CanEditEntry($this->oObject, 'private_log', 0));
		MetaModel::GetConfig()->Set('caselog_edition.allowed_profiles', 'Portal user; Administrator');
		$this->assertTrue($this->oService->CanEditEntry($this->oObject, 'private_log', 0));

		MetaModel::GetConfig()->Set('caselog_edition.allowed_classes', ['Organization']);
		$this->assertFalse($this->oService->CanEditEntry($this->oObject, 'private_log', 0));
		MetaModel::GetConfig()->Set('caselog_edition.allowed_classes', 'UserRequest');
		$this->assertTrue($this->oService->CanEditEntry($this->oObject, 'private_log', 0));

		MetaModel::GetConfig()->Set('caselog_edition.allowed_case_logs', ['UserRequest' => ['public_log']]);
		$this->assertFalse($this->oService->CanEditEntry($this->oObject, 'private_log', 0));
		MetaModel::GetConfig()->Set('caselog_edition.allowed_case_logs', ['UserRequest' => ['private_log']]);
		$this->assertTrue($this->oService->CanEditEntry($this->oObject, 'private_log', 0));
		MetaModel::GetConfig()->Set('caselog_edition.allowed_case_logs', ['UserRequest::private_log']);
		$this->assertTrue($this->oService->CanEditEntry($this->oObject, 'private_log', 0));
		MetaModel::GetConfig()->Set('caselog_edition.allowed_case_logs', 'UserRequest::public_log');
		$this->assertFalse($this->oService->CanEditEntry($this->oObject, 'private_log', 0));
	}

	/**
	 * @covers \Combodo\iTop\Service\CaseLog\CaseLogEditionService::CanEditEntry
	 * @since 3.3.0 N°1365 - Verify attribute flags, user rights and login checks
	 */
	public function testCanEditEntryRequiresWritableAttributesAndObjectRights(): void
	{
		MetaModel::GetConfig()->Set('caselog_edition.enabled', true);
		$this->oObject->AddAttributeFlags('private_log', OPT_ATT_READONLY);
		$this->assertFalse($this->oService->CanEditEntry($this->oObject, 'private_log', 0));

		$oHiddenObject = MetaModel::GetObject('UserRequest', $this->oObject->GetKey());
		$oHiddenObject->ForceAttributeFlags('private_log', OPT_ATT_HIDDEN);
		$this->assertFalse($this->oService->CanEditEntry($oHiddenObject, 'private_log', 0));

		$oLimitedUser = $this->CreateContactlessUser('CaseLogLimited'.uniqid(), self::$aURP_Profiles['Ticket ReadOnly'], 'Password1!');
		$oCaseLog = $this->oObject->Get('private_log');
		$oCaseLog->AddLogEntry('Restricted user entry', '', $oLimitedUser->GetKey());
		$this->oObject->Set('private_log', $oCaseLog);
		$this->oObject->DBUpdate();
		UserRights::Logoff();
		$_SESSION = [];
		UserRights::Login($oLimitedUser->Get('login'));
		MetaModel::GetConfig()->Set('caselog_edition.allowed_profiles', ['Ticket ReadOnly']);

		$this->assertSame(UR_ALLOWED_NO, UserRights::IsActionAllowed('UserRequest', UR_ACTION_MODIFY));
		$this->assertFalse($this->oService->CanEditEntry($this->oObject, 'private_log', $oCaseLog->GetLatestEntryIndex()));

		UserRights::Logoff();
		$this->assertFalse($this->oService->CanEditEntry($this->oObject, 'private_log', 0));
	}

	/**
	 * @covers \Combodo\iTop\Service\CaseLog\CaseLogEditionService::NormalizeList
	 * @covers \Combodo\iTop\Service\CaseLog\CaseLogEditionService::IsTargetAllowed
	 * @covers \Combodo\iTop\Service\CaseLog\CaseLogEditionService::HasAllowedProfile
	 * @since 3.3.0 N°1365 - Verify CaseLog allow-list normalization
	 */
	public function testConfigurationListHelpersNormalizeAndMatchValues(): void
	{
		$aNormalizeCases = [
			['Organization, UserRequest; UserRequest', ['Organization', 'UserRequest']],
			[['private_log', 0, false, null, '', ['nested'], 'private_log'], ['private_log', '0']],
			[null, []],
			[42, []],
		];
		foreach ($aNormalizeCases as [$mInput, $aExpected]) {
			$this->assertSame($aExpected, $this->InvokeNonPublicMethod(CaseLogEditionService::class, 'NormalizeList', $this->oService, [$mInput]));
		}

		$aTargetCases = [
			[42, [], false],
			[[], 42, false],
			[[], [], true],
			[['Organization'], [], false],
			['UserRequest Organization', null, true],
			[[], '', true],
			[[], 'UserRequest::private_log', true],
			[[], 'UserRequest::public_log', false],
			[[], ['UserRequest' => []], true],
			[[], ['UserRequest' => 'public_log; private_log'], true],
			[[], ['UserRequest' => ['public_log']], false],
			[[], ['Organization' => ['name']], false],
			[[], ['UserRequest::private_log'], true],
			[[], [false, 'UserRequest::public_log'], false],
		];
		foreach ($aTargetCases as [$mClasses, $mCaseLogs, $bExpected]) {
			$this->assertSame(
				$bExpected,
				$this->InvokeNonPublicMethod(CaseLogEditionService::class, 'IsTargetAllowed', $this->oService, ['UserRequest', 'private_log', $mClasses, $mCaseLogs])
			);
		}

		$this->assertTrue($this->InvokeNonPublicMethod(CaseLogEditionService::class, 'HasAllowedProfile', $this->oService, [[]]));
		$this->assertTrue($this->InvokeNonPublicMethod(CaseLogEditionService::class, 'HasAllowedProfile', $this->oService, [['Administrator']]));
		$this->assertFalse($this->InvokeNonPublicMethod(CaseLogEditionService::class, 'HasAllowedProfile', $this->oService, [['Portal user']]));
		$this->assertFalse($this->InvokeNonPublicMethod(CaseLogEditionService::class, 'HasAllowedProfile', $this->oService, [42]));

		UserRights::Logoff();
		$this->assertFalse($this->InvokeNonPublicMethod(CaseLogEditionService::class, 'HasAllowedProfile', $this->oService, [[]]));
	}

	/**
	 * @covers \Combodo\iTop\Service\CaseLog\CaseLogEditionService::EditEntry
	 * @since 3.3.0 N°1365 - Verify edits are sanitized and announced
	 */
	public function testEditEntrySanitizesContentPreservesMetadataAndFiresEvent(): void
	{
		MetaModel::GetConfig()->Set('caselog_edition.enabled', true);
		$aBefore = $this->oObject->Get('private_log')->GetAsArray()[0];
		$aReceivedEvents = [];
		$this->EventService_RegisterListener(
			ApplicationEvents::APPLICATION_EVENT_CASELOG_ENTRY_EDITED,
			static function (EventData $oEventData) use (&$aReceivedEvents): void {
				$aReceivedEvents[] = $oEventData;
			}
		);

		$aResult = $this->oService->EditEntry($this->oObject, 'private_log', 0, '<p>Edited safely</p><script>alert(1)</script>');

		$this->assertTrue($aResult['changed']);
		$this->assertStringContainsString('Edited safely', $aResult['message_html']);
		$this->assertStringNotContainsString('<script', strtolower($aResult['message_html']));
		$this->assertSame($aResult['message_html'], $this->oObject->Get('private_log')->GetEntryAt(0));
		$aAfter = $this->oObject->Get('private_log')->GetAsArray()[0];
		$this->assertSame($aBefore['date'], $aAfter['date']);
		$this->assertSame($aBefore['user_login'], $aAfter['user_login']);
		$this->assertCount(1, $aReceivedEvents);
		$this->assertSame(ApplicationEvents::APPLICATION_EVENT_CASELOG_ENTRY_EDITED, $aReceivedEvents[0]->GetEvent());
		$this->assertSame('private_log', $aReceivedEvents[0]->GetEventData()['attribute_code']);
		$this->assertSame(0, $aReceivedEvents[0]->GetEventData()['entry_index']);

		$aEditEntries = ActivityPanelHelper::GetCaseLogEditEntries('UserRequest', (string)$this->oObject->GetKey(), 'private_log', 0);
		$this->assertCount(1, $aEditEntries);
		$this->assertInstanceOf(CaseLogEditEntry::class, $aEditEntries[0]);
		$this->assertSame('private_log', $aEditEntries[0]->GetAttCode());
		$this->assertSame(0, $aEditEntries[0]->GetEntryIndex());
		$this->assertTrue($aEditEntries[0]->HasContentSnapshot());
		$this->assertStringContainsString('First CaseLog entry', strip_tags($aEditEntries[0]->GetPreviousContent()));
		$this->assertStringContainsString('ibo-rich-text-diff--removed', $aEditEntries[0]->GetPreviousContent());
		$this->assertStringContainsString('Edited safely', strip_tags($aEditEntries[0]->GetNewContent()));
		$this->assertStringContainsString('ibo-rich-text-diff--added', $aEditEntries[0]->GetNewContent());
		$this->assertStringNotContainsString('<script', strtolower($aEditEntries[0]->GetNewContent()));
	}

	/**
	 * @covers \Combodo\iTop\Service\CaseLog\CaseLogEditionService::EditEntry
	 * @since 3.3.0 N°1365 - Verify no-op edits and denied edits
	 */
	public function testEditEntryDoesNotWriteNoOpEditsAndRejectsDeniedEdits(): void
	{
		MetaModel::GetConfig()->Set('caselog_edition.enabled', true);
		$aReceivedEvents = [];
		$this->EventService_RegisterListener(
			ApplicationEvents::APPLICATION_EVENT_CASELOG_ENTRY_EDITED,
			static function (EventData $oEventData) use (&$aReceivedEvents): void {
				$aReceivedEvents[] = $oEventData;
			}
		);
		$sOriginalContent = $this->oObject->Get('private_log')->GetAsArray()[0]['message_html'];

		$aResult = $this->oService->EditEntry($this->oObject, 'private_log', 0, $sOriginalContent);
		$this->assertFalse($aResult['changed']);
		$this->assertSame($sOriginalContent, $aResult['message_html']);
		$this->assertCount(0, $aReceivedEvents);

		MetaModel::GetConfig()->Set('caselog_edition.enabled', false);
		$this->expectException(CoreException::class);
		$this->oService->EditEntry($this->oObject, 'private_log', 0, '<p>Denied</p>');
	}

	/**
	 * @covers \Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry\ActivityEntryFactory::MakeFromCaseLogEntryArray
	 * @covers \Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry\CaseLogEntry
	 * @covers \Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityPanel::IsCaseLogEditionEnabled
	 * @covers \Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityPanel::GetCaseLogEditionEditorConfiguration
	 * @covers \Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityPanel::GetCaseLogEditionImageUploadUrl
	 * @since 3.3.0 N°1365 - Verify the activity-panel editor UI
	 */
	public function testActivityPanelExposesTheInPlaceCaseLogEditor(): void
	{
		MetaModel::GetConfig()->Set('caselog_edition.enabled', false);
		$oDisabledPanel = new ActivityPanel($this->oObject);
		$this->assertFalse($oDisabledPanel->IsCaseLogEditionEnabled());

		MetaModel::GetConfig()->Set('caselog_edition.enabled', true);
		$mOriginalMentionAllowedClasses = MetaModel::GetConfig()->Get('mentions.allowed_classes');
		MetaModel::GetConfig()->Set('mentions.allowed_classes', ['@' => 'Person']);
		$oPanel = new ActivityPanel($this->oObject);
		$this->assertTrue($oPanel->IsCaseLogEditionEnabled());
		$this->assertStringContainsString('pages/ajax.render.php', $oPanel->GetEditCaseLogEntryEndpoint());
		$aEditorConfiguration = $oPanel->GetCaseLogEditionEditorConfiguration();
		$this->assertArrayHasKey('mention', $aEditorConfiguration);
		$this->assertArrayHasKey('feeds', $aEditorConfiguration['mention']);
		$aFeedUrl = $aEditorConfiguration['mention']['feeds'][0]['feed_ajax_options']['url'];
		parse_str((string)parse_url($aFeedUrl, PHP_URL_QUERY), $aFeedQuery);
		$this->assertSame('UserRequest', $aFeedQuery['host_class']);
		$this->assertSame((string)$this->oObject->GetKey(), $aFeedQuery['host_id']);
		$this->assertArrayHasKey('needle', $aFeedQuery);
		MetaModel::GetConfig()->Set('mentions.allowed_classes', $mOriginalMentionAllowedClasses);
		$this->assertNotNull($oPanel->GetCaseLogEditionImageUploadUrl());
		$this->SetNonPublicProperty($oPanel, 'sTransactionId', null);
		$this->assertNull($oPanel->GetCaseLogEditionImageUploadUrl());

		$oPanel->SetObjectMode(\cmdbAbstractObject::ENUM_DISPLAY_MODE_EDIT);
		$this->assertFalse($oPanel->IsCaseLogEditionEnabled());
		$oPrintPanel = new ActivityPanelPrint($this->oObject);
		$oPrintPanel->SetObjectMode(\cmdbAbstractObject::ENUM_DISPLAY_MODE_VIEW);
		$this->assertFalse($oPrintPanel->IsCaseLogEditionEnabled());

		$aEntryData = $this->oObject->Get('private_log')->GetAsArray()[0];
		$oEntry = ActivityEntryFactory::MakeFromCaseLogEntryArray('private_log', $aEntryData, 0, $this->oObject);
		$this->assertInstanceOf(CaseLogEntry::class, $oEntry);
		$this->assertSame(0, $oEntry->GetEntryIndex());
		$this->assertTrue($oEntry->CanEditEntry());

		$sHtml = BlockRenderer::RenderBlockTemplates($oEntry);
		$this->assertStringContainsString('ibo-caselog-entry--edition-form', $sHtml);
		$this->assertStringContainsString('data-entry-caselog-index="0"', $sHtml);
		$this->assertStringContainsString('data-role="ibo-caselog-entry--edit-button"', $sHtml);
		$this->assertStringContainsString('First CaseLog entry', $sHtml);
		$this->assertLessThan(strpos($sHtml, 'ibo-caselog-entry--edition-form'), strpos($sHtml, 'ibo-caselog-entry--content'));

		$oNonEditableEntry = ActivityEntryFactory::MakeFromCaseLogEntryArray('private_log', $aEntryData, 0, $this->oObject, false);
		$this->assertFalse($oNonEditableEntry->CanEditEntry());
	}

	/**
	 * @covers \Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry\CaseLogEntry::SetModificationHistory
	 * @covers \Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry\CaseLogEntry::HasModificationHistory
	 * @covers \Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry\CaseLogEntry::GetModificationHistory
	 * @covers \Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry\CaseLogEntry::GetFormattedLastModificationDateTime
	 * @covers \Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry\CaseLogEditEntry::HasContentSnapshot
	 * @covers \Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry\CaseLogEditEntry::GetPreviousContent
	 * @covers \Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry\CaseLogEditEntry::GetNewContent
	 * @since 3.3.0 N°1365 - Verify CaseLog modification metadata
	 */
	public function testCaseLogEntryExposesItsModificationHistory(): void
	{
		$oEntry = new CaseLogEntry(new DateTime('2026-01-02 03:04:05'), '', 'private_log', '<p>Content</p>', 'Legacy author');
		$this->assertFalse($oEntry->HasModificationHistory());
		$this->assertSame([], $oEntry->GetModificationHistory());
		$this->assertSame('', $oEntry->GetFormattedLastModificationDateTime());

		$oEditEntry = new CaseLogEditEntry(new DateTime('2026-02-03 04:05:06'), '', 'private_log', 0, 123);
		$this->assertFalse($oEditEntry->HasContentSnapshot());
		$this->assertSame('', $oEditEntry->GetPreviousContent());
		$this->assertSame('', $oEditEntry->GetNewContent());
		$oEntry->SetModificationHistory([$oEditEntry]);
		$this->assertTrue($oEntry->HasModificationHistory());
		$this->assertSame([$oEditEntry], $oEntry->GetModificationHistory());
		$this->assertSame($oEditEntry->GetFormattedDateTime(), $oEntry->GetFormattedLastModificationDateTime());
		$sHtml = BlockRenderer::RenderBlockTemplates($oEntry);
		$this->assertStringContainsString('ibo-caselog-entry--modification--snapshot-unavailable', $sHtml);
	}

	/**
	 * @covers \Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityPanelFactory::MakeForObjectDetails
	 * @covers \Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityPanelHelper::GetCaseLogEditEntries
	 * @covers \Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityPanelHelper::GetCMDBChangeOpEditsEntriesForObject
	 * @covers \Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry\CMDBChangeOp\CMDBChangeOpSetAttributeCaseLogFactory::MakeFromCmdbChangeOp
	 * @since 3.3.0 N°1365 - Verify CaseLog edit diffs without Activity entries
	 */
	public function testCaseLogEditShowsEachVersionDiffWithoutActivityEntry(): void
	{
		MetaModel::GetConfig()->Set('caselog_edition.enabled', true);
		$this->assertSame([], ActivityPanelHelper::GetCaseLogEditEntries('UserRequest', (string)$this->oObject->GetKey(), 'private_log', 0));
		$oInitialPanel = ActivityPanelFactory::MakeForObjectDetails($this->oObject);
		$oInitialCaseLogEntry = null;
		foreach ($oInitialPanel->GetEntries() as $oEntry) {
			if ($oEntry instanceof CaseLogEntry) {
				$oInitialCaseLogEntry = $oEntry;
				break;
			}
		}
		$this->assertInstanceOf(CaseLogEntry::class, $oInitialCaseLogEntry);
		$this->assertFalse($oInitialCaseLogEntry->HasModificationHistory());

		$this->oService->EditEntry($this->oObject, 'private_log', 0, '<p>First edit</p>');
		$this->oService->EditEntry($this->oObject, 'private_log', 0, '<p>Second edit</p>');

		$aEditHistory = ActivityPanelHelper::GetCaseLogEditEntries('UserRequest', (string)$this->oObject->GetKey(), 'private_log', 0);
		$this->assertCount(2, $aEditHistory);
		$this->assertInstanceOf(CaseLogEditEntry::class, $aEditHistory[0]);
		$this->assertStringContainsString('First edit', strip_tags($aEditHistory[0]->GetPreviousContent()));
		$this->assertStringContainsString('ibo-rich-text-diff--removed', $aEditHistory[0]->GetPreviousContent());
		$this->assertStringContainsString('Second edit', strip_tags($aEditHistory[0]->GetNewContent()));
		$this->assertStringContainsString('ibo-rich-text-diff--added', $aEditHistory[0]->GetNewContent());
		$this->assertStringContainsString('First CaseLog entry', strip_tags($aEditHistory[1]->GetPreviousContent()));
		$this->assertStringContainsString('ibo-rich-text-diff--removed', $aEditHistory[1]->GetPreviousContent());
		$this->assertStringContainsString('First edit', strip_tags($aEditHistory[1]->GetNewContent()));
		$this->assertStringContainsString('ibo-rich-text-diff--added', $aEditHistory[1]->GetNewContent());

		$aHistory = ActivityPanelHelper::GetCMDBChangeOpEditsEntriesForObject('UserRequest', (string)$this->oObject->GetKey());
		foreach ($aHistory['entries'] as $oHistoryEntry) {
			$this->assertNotInstanceOf(CaseLogEditEntry::class, $oHistoryEntry);
		}

		$oPanel = ActivityPanelFactory::MakeForObjectDetails($this->oObject);
		$oCaseLogEntry = null;
		foreach ($oPanel->GetEntries() as $oEntry) {
			if ($oEntry instanceof CaseLogEntry) {
				$oCaseLogEntry = $oEntry;
			}
			$this->assertNotInstanceOf(CaseLogEditEntry::class, $oEntry);
		}

		$this->assertInstanceOf(CaseLogEntry::class, $oCaseLogEntry);
		$this->assertTrue($oCaseLogEntry->HasModificationHistory());
		$this->assertNotSame('', $oCaseLogEntry->GetFormattedLastModificationDateTime());

		$sHtml = BlockRenderer::RenderBlockTemplates($oCaseLogEntry);
		$sHtmlText = strip_tags($sHtml);
		$this->assertStringContainsString('ibo-caselog-entry--modification-history', $sHtml);
		$this->assertStringContainsString('ibo-rich-text-diff--version--previous', $sHtml);
		$this->assertStringContainsString('ibo-rich-text-diff--version--new', $sHtml);
		$this->assertStringContainsString('ibo-rich-text-diff--removed', $sHtml);
		$this->assertStringContainsString('ibo-rich-text-diff--added', $sHtml);
		$this->assertStringContainsString('First CaseLog entry', $sHtmlText);
		$this->assertStringContainsString('First edit', $sHtmlText);
		$this->assertStringContainsString('Second edit', $sHtmlText);
		$this->assertStringNotContainsString('CaseLog entry edited', $sHtml);
	}

	/**
	 * @covers \Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry\CMDBChangeOp\CMDBChangeOpSetAttributeHTMLFactory::MakeFromCmdbChangeOp
	 * @covers \Combodo\iTop\Application\Helper\HtmlDiffHelper::Compare
	 * @since 3.3.0 N°1365 - Verify rendered diffs for ticket HTML attributes
	 */
	public function testHtmlAttributeHistoryShowsOnlyVisibleTextDifferences(): void
	{
		$oInitialCurrentChange = CMDBObject::GetCurrentChange();
		try {
			CMDBObject::SetCurrentChange(null);
			$this->oObject->Set('description', '<p><strong>Ticket</strong> keeps the old phrase.</p>');
			$this->oObject->DBUpdate();
			CMDBObject::SetCurrentChange(null);
			$this->oObject->Set('description', '<div><em>Ticket</em> keeps the new phrase.</div>');
			$this->oObject->DBUpdate();
		} finally {
			CMDBObject::SetCurrentChange($oInitialCurrentChange);
		}

		$aHistory = ActivityPanelHelper::GetCMDBChangeOpEditsEntriesForObject('UserRequest', (string)$this->oObject->GetKey());
		$oHtmlEditEntry = null;
		foreach ($aHistory['entries'] as $oEntry) {
			if (($oEntry instanceof EditsEntry) && $oEntry->HasHtmlComparison('description')) {
				$aComparison = $oEntry->GetHtmlComparison('description');
				$sNewHtmlText = strip_tags($aComparison['new']);
				if (strpos($sNewHtmlText, 'new phrase') !== false) {
					$oHtmlEditEntry = $oEntry;
					break;
				}
			}
		}

		$this->assertInstanceOf(EditsEntry::class, $oHtmlEditEntry);
		$aComparison = $oHtmlEditEntry->GetHtmlComparison('description');
		$this->assertStringContainsString('old phrase', strip_tags($aComparison['previous']));
		$this->assertStringContainsString('ibo-rich-text-diff--removed', $aComparison['previous']);
		$this->assertStringNotContainsString('ibo-rich-text-diff--removed">Ticket', $aComparison['previous']);
		$this->assertStringContainsString('new phrase', strip_tags($aComparison['new']));
		$this->assertStringContainsString('ibo-rich-text-diff--added', $aComparison['new']);
		$this->assertStringNotContainsString('ibo-rich-text-diff--added">Ticket', $aComparison['new']);

		$sHtml = BlockRenderer::RenderBlockTemplates($oHtmlEditEntry);
		$this->assertStringContainsString('ibo-rich-text-diff--versions', $sHtml);
		$this->assertStringContainsString('ibo-rich-text-diff--removed', $sHtml);
		$this->assertStringContainsString('ibo-rich-text-diff--added', $sHtml);
	}

	/**
	 * @covers \Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityPanelHelper::GetCMDBChangeOpEditsEntriesForObject
	 * @since 3.3.0 N°1365 - Verify CaseLog edit history is independent of Activity pagination
	 */
	public function testCaseLogEditHistoryIsIndependentOfActivityPagination(): void
	{
		MetaModel::GetConfig()->Set('caselog_edition.enabled', true);
		$this->oService->EditEntry($this->oObject, 'private_log', 0, '<p>First edit</p>');
		$this->oService->EditEntry($this->oObject, 'private_log', 0, '<p>Second edit</p>');
		$iOriginalMaxHistoryLength = (int)MetaModel::GetConfig()->Get('max_history_length');
		MetaModel::GetConfig()->Set('max_history_length', 1);

		try {
			$aEditHistory = ActivityPanelHelper::GetCaseLogEditEntries('UserRequest', (string)$this->oObject->GetKey(), 'private_log', 0);
			$this->assertCount(2, $aEditHistory);

			$aActivityHistory = ActivityPanelHelper::GetCMDBChangeOpEditsEntriesForObject('UserRequest', (string)$this->oObject->GetKey());
			foreach ($aActivityHistory['entries'] as $oHistoryEntry) {
				$this->assertNotInstanceOf(CaseLogEditEntry::class, $oHistoryEntry);
			}
		} finally {
			MetaModel::GetConfig()->Set('max_history_length', $iOriginalMaxHistoryLength);
		}
	}

	/**
	 * @covers \Combodo\iTop\Controller\Base\Layout\ActivityPanelController::EditCaseLogEntry
	 * @since 3.3.0 N°1365 - Verify the CaseLog edit endpoint rejects incomplete requests
	 */
	public function testEditCaseLogEntryRejectsMissingRequestParameters(): void
	{
		$_POST = [];
		$this->expectException(CoreException::class);
		(new ActivityPanelController())->EditCaseLogEntry();
	}

	/**
	 * @covers \Combodo\iTop\Controller\Base\Layout\ActivityPanelController::EditCaseLogEntry
	 * @since 3.3.0 N°1365 - Verify the CaseLog edit endpoint checks transaction tokens
	 */
	public function testEditCaseLogEntryRejectsInvalidTransactionToken(): void
	{
		$_POST = [
			'object_class' => 'UserRequest',
			'object_id' => $this->oObject->GetKey(),
			'attribute_code' => 'private_log',
			'entry_index' => 0,
			'transaction_id' => 'invalid-token',
			'content' => '<p>Edited</p>',
		];
		$this->expectException(CoreException::class);
		(new ActivityPanelController())->EditCaseLogEntry();
	}

	/**
	 * @covers \Combodo\iTop\Controller\Base\Layout\ActivityPanelController::EditCaseLogEntry
	 * @since 3.3.0 N°1365 - Verify the CaseLog edit endpoint rejects malformed parameters
	 */
	public function testEditCaseLogEntryRejectsNonStringContentAndInvalidIndexes(): void
	{
		$aBaseRequest = [
			'object_class' => 'UserRequest',
			'object_id' => $this->oObject->GetKey(),
			'attribute_code' => 'private_log',
			'entry_index' => 0,
			'transaction_id' => utils::GetNewTransactionId(),
			'content' => '<p>Edited</p>',
		];
		$aInvalidRequests = [
			array_merge($aBaseRequest, ['content' => ['not', 'html']]),
			array_merge($aBaseRequest, ['entry_index' => -1]),
			array_merge($aBaseRequest, ['object_id' => 0]),
		];

		foreach ($aInvalidRequests as $aRequest) {
			$_POST = $aRequest;
			try {
				(new ActivityPanelController())->EditCaseLogEntry();
				$this->fail('The endpoint should reject malformed CaseLog edit parameters.');
			} catch (CoreException $oException) {
				$this->assertNotSame('', $oException->getMessage());
			}
		}
	}

	/**
	 * @covers \Combodo\iTop\Controller\Base\Layout\ActivityPanelController::EditCaseLogEntry
	 * @since 3.3.0 N°1365 - Verify the CaseLog edit endpoint updates without Activity rows
	 */
	public function testEditCaseLogEntryReturnsUpdatedMarkupWithoutActivityRow(): void
	{
		MetaModel::GetConfig()->Set('caselog_edition.enabled', true);
		$_POST = [
			'object_class' => 'UserRequest',
			'object_id' => $this->oObject->GetKey(),
			'attribute_code' => 'private_log',
			'entry_index' => 0,
			'transaction_id' => utils::GetNewTransactionId(),
			'content' => '<p>Edited through the endpoint</p>',
		];

		$aResult = (new ActivityPanelController())->EditCaseLogEntry();

		$this->assertTrue($aResult['success']);
		$this->assertStringContainsString('Edited through the endpoint', $aResult['entry_html']);
		$this->assertArrayNotHasKey('activity_entry_html', $aResult);

		$_POST['transaction_id'] = utils::GetNewTransactionId();
		$aNoOpResult = (new ActivityPanelController())->EditCaseLogEntry();
		$this->assertTrue($aNoOpResult['success']);
		$this->assertArrayNotHasKey('activity_entry_html', $aNoOpResult);
	}
}

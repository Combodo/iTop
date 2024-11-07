<?php
/**
 * Copyright (C) 2013-2024 Combodo SAS
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */

namespace Combodo\iTop\Application\UI\Base\Layout\ActivityPanel;


use appUserPreferences;
use AttributeDateTime;
use cmdbAbstractObject;
use Combodo\iTop\Application\UI\Base\Component\PopoverMenu\PopoverMenu;
use Combodo\iTop\Application\UI\Base\Component\PopoverMenu\PopoverMenuItem\PopoverMenuItemFactory;
use Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry\ActivityEntry;
use Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry\CaseLogEntry;
use Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\CaseLogEntryForm\CaseLogEntryForm;
use Combodo\iTop\Application\UI\Base\UIBlock;
use DBObject;
use Exception;
use MetaModel;
use URLPopupMenuItem;
use utils;

/**
 * Class ActivityPanel
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Application\UI\Base\Layout\ActivityPanel
 * @internal
 * @since 3.0.0
 */
class ActivityPanel extends UIBlock
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-activity-panel';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/layouts/activity-panel/layout';
	public const DEFAULT_JS_TEMPLATE_REL_PATH = 'base/layouts/activity-panel/layout';
	public const DEFAULT_JS_FILES_REL_PATH = [
		'js/jquery.ba-bbq.min.js',
		'js/leave_handler.js',
		'js/layouts/activity-panel/activity-panel.js',
	];

	/**
	 * @var bool
	 * @see static::$bShowMultipleEntriesSubmitConfirmation
	 */
	public const DEFAULT_SHOW_MULTIPLE_ENTRIES_SUBMI_CONFIRMATION = true;

	/** @var \DBObject $oObject The object for which the activity panel is for */
	protected $oObject;
	/**
	 * @see \cmdbAbstractObject::ENUM_DISPLAY_MODE_XXX
	 * @var string $sObjectMode Display mode of $oObject (create, edit, view, ...)
	 */
	protected $sObjectMode;
	/** @var null|string $sTransactionId Only when $sObjectMode is set to \cmdbAbstractObject::ENUM_DISPLAY_MODE_VIEW */
	protected $sTransactionId;
	/** @var array $aCaseLogs Metadata of the case logs (att. code, color, ...), will be use to make the tabs and identify them easily */
	protected $aCaseLogs;
	/** @var ActivityEntry[] $aEntries */
	protected $aEntries;
	/** @var bool $bAreEntriesSorted True if the entries have been sorted by date */
	protected $bAreEntriesSorted;
	/** @var bool True if there are more entries to load asynchroniously */
	protected $bHasMoreEntriesToLoad;
	/** @var array IDs of the last loaded entries of each type, makes it easier to load the next entries asynchronioulsy */
	protected $aLastLoadedEntriesIds;
	/**
	 * @see MetaModel::HasStateAttributeCode()
	 * @var bool True if the host object has states (but not necessary a lifecycle)
	 */
	protected $bHasStates;
	/** @var \Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\CaseLogEntryForm\CaseLogEntryForm[] $aCaseLogTabsEntryForms */
	protected $aCaseLogTabsEntryForms;
	/** @var \Combodo\iTop\Application\UI\Base\Component\PopoverMenu\PopoverMenu Menu displaying the editable log entry forms the user can go to */
	protected $oComposeMenu;
	/** @var bool Whether a confirmation dialog should be prompt when multiple entries are about to be submitted at once */
	protected $bShowMultipleEntriesSubmitConfirmation;
	/** @var int */
	protected $iDatetimesReformatLimit;
	/** @var int */
	protected $iLockWatcherPeriod;
	/** @var bool */
	protected $bPrefilterOnlyCurrentLog;
	/** @var bool */
	protected $bPrefilterStateChangesOnLogs;
	/** @var bool */
	protected $bPrefilterEditsOnLogs;


	/**
	 * ActivityPanel constructor.
	 *
	 * @param \DBObject $oObject
	 * @param ActivityEntry[] $aEntries
	 * @param string|null $sId
	 *
	 * @throws \CoreException
	 * @throws \Exception
	 */
	public function __construct(DBObject $oObject, array $aEntries = [], ?string $sId = null)
	{
		parent::__construct($sId);

		$oConfig = MetaModel::GetConfig();
		$this->InitializeCaseLogTabs();
		$this->InitializeCaseLogTabsEntryForms();
		$this->InitializeComposeMenu();
		$this->SetObjectMode(cmdbAbstractObject::DEFAULT_DISPLAY_MODE);
		$this->SetObject($oObject);
		$this->SetEntries($aEntries);
		$this->SetDatetimesReformatLimit($oConfig->Get('activity_panel.datetimes_reformat_limit'));
		$this->SetLockWatcherPeriod($oConfig->Get('activity_panel.lock_watcher_period'));
		$this->SetPrefilterOnlyCurrentLog($oConfig->Get('activity_panel.prefilter_only_current_log'));
		$this->SetPrefilterStateChangesOnLogs($oConfig->Get('activity_panel.prefilter_state_changes_on_logs'));
		$this->SetPrefilterEditsOnLogs($oConfig->Get('activity_panel.prefilter_edits_on_logs'));
		$this->bAreEntriesSorted = false;
		$this->bHasMoreEntriesToLoad = false;
		$this->aLastLoadedEntriesIds = [];
		$this->ComputedShowMultipleEntriesSubmitConfirmation();
	}

	/**
	 * Set the object the panel is for, and initialize the corresponding case log tabs.
	 *
	 * @param \DBObject $oObject
	 *
	 * @return $this
	 * @throws \CoreException
	 * @throws \Exception
	 */
	protected function SetObject(DBObject $oObject)
	{
		$this->oObject = $oObject;
		$sObjectClass = get_class($this->oObject);

		// Check if object has a lifecycle
		$this->bHasStates = MetaModel::HasStateAttributeCode($sObjectClass);

		// Initialize the case log tabs
		$this->InitializeCaseLogTabs();
		$this->InitializeCaseLogTabsEntryForms();
		$this->InitializeComposeMenu();

		// Get only case logs from the "details" zlist, but if none (2.7 and older) show them all
		$aCaseLogAttCodes = MetaModel::GetCaseLogs($sObjectClass, 'details');
		if (empty($aCaseLogAttCodes)) {
			$aCaseLogAttCodes = MetaModel::GetCaseLogs($sObjectClass);
		}

		foreach ($aCaseLogAttCodes as $sCaseLogAttCode) {
			$this->AddCaseLogTab($sCaseLogAttCode);
		}


		return $this;
	}

	/**
	 * Return the object for which the activity panel is for
	 *
	 * @return \DBObject
	 */
	public function GetObject()
	{
		return $this->oObject;
	}

	/**
	 * Return the object id for which the activity panel is for
	 *
	 * @return int
	 */
	public function GetObjectId(): int {
		return $this->oObject->GetKey();
	}

	/**
	 * Return the object class for which the activity panel is for
	 *
	 * @return string
	 */
	public function GetObjectClass(): string {
		return get_class($this->oObject);
	}

	/**
	 * Set the display mode of the $oObject
	 *
	 * @param string $sMode
	 * @see cmdbAbstractObject::ENUM_DISPLAY_MODE_XXX
	 *
	 * @return $this
	 * @throws \Exception
	 */
	public function SetObjectMode(string $sMode)
	{
		// Consistency check
		if(!in_array($sMode, cmdbAbstractObject::EnumDisplayModes())){
			throw new Exception("Activity panel: Object mode '$sMode' not allowed, should be either ".implode(' / ', cmdbAbstractObject::EnumDisplayModes()));
		}

		$this->sObjectMode = $sMode;

		return $this;
	}

	/**
	 * Return the display mode of the $oObject
	 *
	 * @see cmdbAbstractObject::ENUM_DISPLAY_MODE_XXX
	 * @return string
	 */
	public function GetObjectMode(): string
	{
		return $this->sObjectMode;
	}

	/**
	 * @return bool True if it should be expanded, false otherwise. Based on the user pref. or reduced by default.
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 */
	public function IsExpanded(): bool
	{
		$bDefault = false;
		$aStates = appUserPreferences::GetPref('activity_panel.is_expanded', []);

		return $aStates[$this->GetObjectClass().'::'.$this->GetObjectMode()] ?? $bDefault;
	}

	/**
	 * @return bool True if it should be closed, false otherwise. Based on the user pref. or closed by default if the object has no case log.
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 */
	public function IsClosed(): bool
	{
		$bDefault = !$this->HasCaseLogTabs();
		$aStates = appUserPreferences::GetPref('activity_panel.is_closed', []);

		return $aStates[$this->GetObjectClass().'::'.$this->GetObjectMode()] ?? $bDefault;
	}

	/**
	 * @return bool
	 * @uses static::$sTransactionId
	 */
	public function HasTransactionId(): bool
	{
		return (null !== $this->sTransactionId);
	}

	/**
	 * @return string|null
	 * @uses static::$sTransactionId
	 */
	public function GetTransactionId(): ?string
	{
		return $this->sTransactionId;
	}

	/**
	 * @return bool True if the lock mechanism has to be enabled
	 * @uses \cmdbAbstractObject::ENUM_DISPLAY_MODE_VIEW
	 * @uses static::HasAnEditableCaseLogTab()
	 * @uses "concurrent_lock_enabled" config. param.
	 */
	public function IsLockEnabled(): bool
	{
		return (cmdbAbstractObject::ENUM_DISPLAY_MODE_VIEW === $this->sObjectMode) && (MetaModel::GetConfig()->Get('concurrent_lock_enabled')) && (true === $this->HasAnEditableCaseLogTab());
	}

	/**
	 * Set all entries at once.
	 *
	 * @param ActivityEntry[] $aEntries
	 *
	 * @return $this
	 * @throws \Exception
	 */
	public function SetEntries(array $aEntries)
	{
		// Reset entries
		$this->aEntries = [];

		foreach ($aEntries as $oEntry)
		{
			$this->AddEntry($oEntry);
		}

		return $this;
	}

	/**
	 * Return all the entries
	 *
	 * @return ActivityEntry[]
	 */
	public function GetEntries(): array
	{
		if ($this->bAreEntriesSorted === false)
		{
			$this->SortEntries();
		}

		return $this->aEntries;
	}

	/**
	 * Return all the entries grouped by author / origin (case log).
	 * This is useful for the template as it avoid to make the processing there.
	 *
	 * @return array
	 */
	public function GetGroupedEntries(): array
	{
		$aGroupedEntries = [];

		$aCurrentGroup = ['author_login' => null, 'origin' => null, 'entries' => []];
		$aPreviousEntryData = ['author_login' => null, 'origin' => null];
		foreach($this->GetEntries() as $sId => $oEntry)
		{
			// New entry data
			$sAuthorLogin = $oEntry->GetAuthorLogin();
			$sOrigin = $oEntry->GetOrigin();

			// Check if it's time to change of group
			if(($sAuthorLogin !== $aPreviousEntryData['author_login']) || ($sOrigin !== $aPreviousEntryData['origin']))
			{
				// Flush current group if necessary
				if(empty($aCurrentGroup['entries']) === false)
				{
					$aGroupedEntries[] = $aCurrentGroup;
				}

				// Init (first iteration) or reset (other iterations) current group
				$aCurrentGroup = ['author_login' => $sAuthorLogin, 'origin' => $sOrigin, 'entries' => []];
			}

			$aCurrentGroup['entries'][] = $oEntry;
			$aPreviousEntryData = ['author_login' => $sAuthorLogin, 'origin' => $sOrigin];
		}

		// Flush last group
		if(empty($aCurrentGroup['entries']) === false)
		{
			$aGroupedEntries[] = $aCurrentGroup;
		}

		return $aGroupedEntries;
	}

	/**
	 * Sort all entries based on the their date, descending.
	 *
	 * @return $this
	 */
	protected function SortEntries()
	{
		if(count($this->aEntries) > 1)
		{
			uasort($this->aEntries, function($oEntryA, $oEntryB){
				/** @var ActivityEntry $oEntryA */
				/** @var ActivityEntry $oEntryB */
				$sDateTimeA = $oEntryA->GetRawDateTime();
				$sDateTimeB = $oEntryB->GetRawDateTime();

				if ($sDateTimeA === $sDateTimeB)
				{
					return 0;
				}

				return ($sDateTimeA > $sDateTimeB) ? -1 : 1;
			});
		}
		$this->bAreEntriesSorted = true;

		return $this;
	}

	/**
	 * Add an $oEntry after all others, excepted if there is already an entry with the same ID in which case it replaces it.
	 *
	 * @param \Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry\ActivityEntry $oEntry
	 *
	 * @return $this
	 * @throws \Exception
	 */
	public function AddEntry(ActivityEntry $oEntry)
	{
		$this->aEntries[$oEntry->GetId()] = $oEntry;
		$this->bAreEntriesSorted = false;

		// Add case log to the panel and update metadata when necessary
		if ($oEntry instanceof CaseLogEntry)
		{
			$sCaseLogAttCode = $oEntry->GetAttCode();
			$sAuthorLogin = $oEntry->GetAuthorLogin();

			// Initialize case log metadata
			if ($this->HasCaseLogTab($sCaseLogAttCode) === false)
			{
				$this->AddCaseLogTab($sCaseLogAttCode);
			}

			// Add case log rank to the entry
			$oEntry->SetCaseLogRank($this->aCaseLogs[$sCaseLogAttCode]['rank']);

			// Update metadata
			// - Message count
			$this->aCaseLogs[$sCaseLogAttCode]['total_messages_count']++;
			// - Authors
			if(array_key_exists($sAuthorLogin, $this->aCaseLogs[$sCaseLogAttCode]['authors']) === false)
			{
				$this->aCaseLogs[$sCaseLogAttCode]['authors'][$sAuthorLogin] = [
					'messages_count' => 0,
				];
			}
			$this->aCaseLogs[$sCaseLogAttCode]['authors'][$sAuthorLogin]['messages_count']++;
		}

		return $this;
	}

	/**
	 * Remove entry of ID $sEntryId.
	 * Note that if there is no entry with that ID, it proceeds silently.
	 *
	 * @param string $sEntryId
	 *
	 * @return $this
	 */
	public function RemoveEntry(string $sEntryId)
	{
		if (array_key_exists($sEntryId, $this->aEntries))
		{
			// Recompute case logs metadata only if necessary
			$oEntry = $this->aEntries[$sEntryId];
			if ($oEntry instanceof CaseLogEntry)
			{
				$sCaseLogAttCode = $oEntry->GetAttCode();
				$sAuthorLogin = $oEntry->GetAuthorLogin();

				// Update metadata
				// - Message count
				$this->aCaseLogs[$sCaseLogAttCode]['total_messages_count']--;
				// - Authors
				$this->aCaseLogs[$sCaseLogAttCode]['authors'][$sAuthorLogin]['messages_count']--;
				if($this->aCaseLogs[$sCaseLogAttCode]['authors'][$sAuthorLogin]['messages_count'] === 0)
				{
					unset($this->aCaseLogs[$sCaseLogAttCode]['authors'][$sAuthorLogin]);
				}
			}

			unset($this->aEntries[$sEntryId]);
		}

		return $this;
	}

	/**
	 * Return true if there is at least one entry
	 *
	 * @return bool
	 */
	public function HasEntries(): bool
	{
		return !empty($this->aEntries);
	}

	/**
	 * @see static::$bHasMoreEntriesToLoad
	 *
	 * @param bool $bHasMoreEntriesToLoad
	 *
	 * @return $this
	 */
	public function SetHasMoreEntriesToLoad(bool $bHasMoreEntriesToLoad)
	{
		$this->bHasMoreEntriesToLoad = $bHasMoreEntriesToLoad;

		return $this;
	}

	/**
	 * @see static::$bHasMoreEntriesToLoad
	 * @return bool
	 */
	public function HasMoreEntriesToLoad(): bool
	{
		return $this->bHasMoreEntriesToLoad;
	}

	/**
	 * @param string $sEntryType Type of entry (eg. cmdbchangeop, caselog, notification)
	 * @param string $sEntryId ID of the last loaded entry
	 *
	 * @return $this
	 * @uses static::$aLastLoadedEntriesIds
	 */
	public function SetLastEntryId(string $sEntryType, string $sEntryId)
	{
		$this->aLastLoadedEntriesIds[$sEntryType] = $sEntryId;

		return $this;
	}

	/**
	 * @return array Hash array of the last loaded entries
	 * @uses static::$aLastLoadedEntriesIds
	 */
	public function GetLastEntryIds(): array
	{
		return $this->aLastLoadedEntriesIds;
	}

	/**
	 * Return all the case log tabs metadata, not their entries
	 *
	 * @return array
	 */
	public function GetCaseLogTabs(): array
	{
		return $this->aCaseLogs;
	}

	/**
	 * @return $this
	 */
	protected function InitializeCaseLogTabs()
	{
		$this->aCaseLogs = [];
		return $this;
	}

	/**
	 * Add the case log tab to the panel
	 * Note: Case log entries are added separately, see static::AddEntry()
	 * Note: If hidden, the case log will not be added
	 *
	 * @param string $sAttCode
	 *
	 * @return $this
	 * @throws \Exception
	 */
	protected function AddCaseLogTab(string $sAttCode)
	{
		// Add case log only if not already existing
		if (!array_key_exists($sAttCode, $this->aCaseLogs))
		{
			$iFlags = ($this->GetObject()->IsNew()) ? $this->GetObject()->GetInitialStateAttributeFlags($sAttCode) : $this->GetObject()->GetAttributeFlags($sAttCode);
			$bIsHidden = (OPT_ATT_HIDDEN === ($iFlags & OPT_ATT_HIDDEN));
			$bIsReadOnly = (OPT_ATT_READONLY === ($iFlags & OPT_ATT_READONLY));

			// Only if not hidden
			if (false === $bIsHidden) {
				$sLogLabel = MetaModel::GetLabel(get_class($this->oObject), $sAttCode);

				$this->aCaseLogs[$sAttCode] = [
					'rank' => count($this->aCaseLogs) + 1,
					'title' => $sLogLabel,
					'total_messages_count' => 0,
					'authors' => [],
					'is_read_only' => $bIsReadOnly,
				];

				// Transaction ID is generated only when:
				// - There is a least 1 *writable* case log
				// - And object is in view mode (in edit mode, it will be handled by the general form)
				// Otherwise we generate unnecessary transaction IDs that could saturate the system
				if ((false === $bIsReadOnly) && (false === $this->HasTransactionId()) && (cmdbAbstractObject::ENUM_DISPLAY_MODE_VIEW === $this->sObjectMode)) {
					$this->sTransactionId = (cmdbAbstractObject::ENUM_DISPLAY_MODE_VIEW === $this->sObjectMode) ? utils::GetNewTransactionId() : null;
				}

				// Add log to compose button menu only if it is editable
				if (false === $bIsReadOnly) {
					$oItem = PopoverMenuItemFactory::MakeFromApplicationPopupMenuItem(
						new URLPopupMenuItem('log-'.$sAttCode, $sLogLabel, '#')
					)
						->AddDataAttribute('caselog-attribute-code', $sAttCode);

					$this->oComposeMenu->AddItem('editable-logs', $oItem);
				}
			}
		}

		return $this;
	}

	/**
	 * Remove the case log tab from the panel.
	 * Note: Case log entries will not be removed.
	 *
	 * @param string $sAttCode
	 *
	 * @return $this
	 */
	protected function RemoveCaseLogTab(string $sAttCode)
	{
		if (array_key_exists($sAttCode, $this->aCaseLogs))
		{
			unset($this->aCaseLogs[$sAttCode]);
		}

		return $this;
	}

	/**
	 * Return true if the case log of $sIs code has been initialized.
	 *
	 * @param string $sAttCode
	 *
	 * @return bool
	 */
	public function HasCaseLogTab(string $sAttCode): bool
	{
		return isset($this->aCaseLogs[$sAttCode]);
	}

	/**
	 * Return true if there is at least one case log declared.
	 *
	 * @return bool
	 */
	public function HasCaseLogTabs(): bool
	{
		return !empty($this->aCaseLogs);
	}

	/**
	 * @return bool true if there is at least 1 editable case log
	 */
	public function HasAnEditableCaseLogTab(): bool
	{
		$bHasEditable = false;

		foreach ($this->GetCaseLogTabs() as $aCaseLogTabData) {
			if (false === $aCaseLogTabData['is_read_only']) {
				$bHasEditable = true;
				break;
			}
		}

		return $bHasEditable;
	}

	/**
	 * Empty the caselogs entry forms
	 *
	 * @return $this
	 */
	protected function InitializeCaseLogTabsEntryForms()
	{
		$this->aCaseLogTabsEntryForms = [];
		return $this;
	}

	/**
	 * Return all entry forms for all case log tabs
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\CaseLogEntryForm\CaseLogEntryForm[]
	 */
	public function GetCaseLogTabsEntryForms(): array
	{
		return $this->aCaseLogTabsEntryForms;
	}

	/**
	 * Set the $oCaseLogEntryForm for the $sCaseLogId tab.
	 * Note: If there is no caselog for that ID, it will proceed silently.
	 *
	 * @param string                                                                              $sCaseLogId
	 * @param \Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\CaseLogEntryForm\CaseLogEntryForm $oCaseLogEntryForm
	 *
	 * @return $this
	 */
	public function SetCaseLogTabEntryForm(string $sCaseLogId, CaseLogEntryForm $oCaseLogEntryForm)
	{
		if ($this->HasCaseLogTab($sCaseLogId)){
			$this->aCaseLogTabsEntryForms[$sCaseLogId] = $oCaseLogEntryForm;
		}

		return $this;
	}

	/**
	 * Return the caselog entry form for the $sCaseLogId tab
	 *
	 * @param string $sCaseLogId
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\CaseLogEntryForm\CaseLogEntryForm
	 */
	public function GetCaseLogTabEntryForm(string $sCaseLogId)
	{
		return $this->aCaseLogTabsEntryForms[$sCaseLogId];
	}

	/**
	 * @param string $sCaseLogId
	 *
	 * @return bool
	 */
	public function HasCaseLogTabEntryForm(string $sCaseLogId): bool
	{
		return !empty($this->aCaseLogTabsEntryForms[$sCaseLogId]);
	}

	/**
	 * @uses static::$bShowMultipleEntriesSubmitConfirmation
	 * @return bool
	 */
	public function GetShowMultipleEntriesSubmitConfirmation(): bool
	{
		return $this->bShowMultipleEntriesSubmitConfirmation;
	}

	/**
	 * Whether the submission of the case logs present in the activity panel is autonomous or will be handled by another form
	 *
	 * @return bool
	 */
	public function IsCaseLogsSubmitAutonomous(): bool
	{
		$iAutonomousSubmission = 0;
		$iBridgedSubmissions = 0;
		foreach ($this->GetCaseLogTabsEntryForms() as $oCaseLogEntryForm) {
			if ($oCaseLogEntryForm->IsSubmitAutonomous()) {
				$iAutonomousSubmission++;
			}
			else {
				$iBridgedSubmissions++;
			}
		}

		if (($iAutonomousSubmission > 0) && ($iBridgedSubmissions > 0)) {
			throw new Exception('All case logs should have the same submission mode (Autonomous: '.$iAutonomousSubmission.', Bridged: '.$iBridgedSubmissions);
		}

		return $iAutonomousSubmission > 0;
	}

	/**
	 * @return bool Whether the "compose a new entry" button is enabled
	 * @throws \Exception
	 */
	public function IsComposeButtonEnabled(): bool
	{
		return $this->HasAnEditableCaseLogTab() && $this->IsCaseLogsSubmitAutonomous();
	}

	/**
	 * @return bool Whether there is a menu on the "compose" button to select which log entry form to open
	 * @uses static::$oComposeMenu
	 */
	public function HasComposeMenu(): bool
	{
		return $this->oComposeMenu->HasItems();
	}

	/**
	 * @return \Combodo\iTop\Application\UI\Base\Component\PopoverMenu\PopoverMenu
	 * @uses static::$oComposeMenu
	 */
	public function GetComposeMenu()
	{
		return $this->oComposeMenu;
	}

	/**
	 * @return $this
	 * @uses static::$oComposeMenu
	 */
	protected function InitializeComposeMenu()
	{
		// Note: There is no toggler set on purpose, menu will be toggle depending on the active tab
		$this->oComposeMenu = new PopoverMenu('ibo-activity-panel--compose-menu');
		$this->oComposeMenu->SetTogglerJSSelector('#ibo-activity-panel--add-caselog-entry-button');

		return $this;
	}

	/**
	 * @return bool True if the entry form should be opened by default, false otherwise. Based on the user pref. or the config. param. by default.
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 */
	public function IsEntryFormOpened(): bool
	{
		// First check if user has a pref.
		$bValue = appUserPreferences::GetPref('activity_panel.is_entry_form_opened', null);
		if (null === $bValue) {
			// Otherwise get the default config. param.
			$bValue = MetaModel::GetConfig()->Get('activity_panel.entry_form_opened_by_default');
		}

		return $bValue;
	}

	/**
	 * @return bool
	 * @uses $bHasStates
	 */
	public function HasStates(): bool
	{
		return $this->bHasStates;
	}

	/**
	 * Return the formatted (user-friendly) date time format for the JS widget.
	 * Will be used by moment.js for instance.
	 *
	 * @return string
	 */
	public function GetDateTimeFormatForJSWidget(): string
	{
		$oDateTimeFormat = AttributeDateTime::GetFormat();

		return $oDateTimeFormat->ToMomentJS();
	}

	/**
	 * @return string The endpoint for all "lock" related operations
	 * @throws \Exception
	 */
	public function GetLockEndpoint(): string
	{
		return utils::GetAbsoluteUrlAppRoot().'pages/ajax.render.php';
	}

	/**
	 * @return string The endpoint for the state (expanded, closed, ...) changes to be saved
	 * @throws \Exception
	 */
	public function GetSaveStateEndpoint(): string
	{
		return utils::GetAbsoluteUrlAppRoot().'pages/ajax.render.php';
	}

	/**
	 * @return string The endpoint to load the remaining entries
	 * @throws \Exception
	 */
	public function GetLoadMoreEntriesEndpoint(): string
	{
		return utils::GetAbsoluteUrlAppRoot().'pages/ajax.render.php';
	}

	/**
	 * @return int
	 */
	public function GetDatetimesReformatLimit(): int
	{
		return $this->iDatetimesReformatLimit;
	}

	/**
	 * @param int $iDatetimesReformatLimit
	 */
	public function SetDatetimesReformatLimit(int $iDatetimesReformatLimit): void
	{
		$this->iDatetimesReformatLimit = $iDatetimesReformatLimit;
	}

	/**
	 * @return int
	 */
	public function GetLockWatcherPeriod(): int
	{
		return $this->iLockWatcherPeriod;
	}

	/**
	 * @param int $iLockWatcherPeriod
	 */
	public function SetLockWatcherPeriod(int $iLockWatcherPeriod): void
	{
		$this->iLockWatcherPeriod = $iLockWatcherPeriod;
	}

	/**
	 * @return bool
	 */
	public function GetPrefilterOnlyCurrentLog(): bool
	{
		return $this->bPrefilterOnlyCurrentLog;
	}

	/**
	 * @param bool $bPrefilterOnlyCurrentLog
	 */
	public function SetPrefilterOnlyCurrentLog(bool $bPrefilterOnlyCurrentLog): void
	{
		$this->bPrefilterOnlyCurrentLog = $bPrefilterOnlyCurrentLog;
	}

	/**
	 * @return bool
	 */
	public function GetPrefilterStateChangesOnLogs(): bool
	{
		return $this->bPrefilterStateChangesOnLogs;
	}

	/**
	 * @param bool $bPrefilterStateChangesOnLogs
	 */
	public function SetPrefilterStateChangesOnLogs(bool $bPrefilterStateChangesOnLogs): void
	{
		$this->bPrefilterStateChangesOnLogs = $bPrefilterStateChangesOnLogs;
	}

	/**
	 * @return bool
	 */
	public function GetPrefilterEditsOnLogs(): bool
	{
		return $this->bPrefilterEditsOnLogs;
	}

	/**
	 * @param bool $bPrefilterEditsOnLogs
	 */
	public function SetPrefilterEditsOnLogs(bool $bPrefilterEditsOnLogs): void
	{
		$this->bPrefilterEditsOnLogs = $bPrefilterEditsOnLogs;
	}

	/**
	 * @inheritdoc
	 */
	public function GetSubBlocks(): array
	{
		$aSubBlocks = array();

		foreach ($this->GetCaseLogTabsEntryForms() as $sCaseLogId => $oCaseLogEntryForm) {
			$aSubBlocks[$oCaseLogEntryForm->GetId()] = $oCaseLogEntryForm;
		}

		$aSubBlocks[$this->GetComposeMenu()->GetId()] = $this->GetComposeMenu();

		return $aSubBlocks;
	}

	/**
	 * @see static::$bShowMultipleEntriesSubmitConfirmation
	 * @return $this
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 */
	protected function ComputedShowMultipleEntriesSubmitConfirmation()
	{
		// Note: Test on a string is necessary as we can only store strings from the JS API, not booleans.
		// Note 2: Do not invert the test to "=== 'true'" as it won't work. Default value is a bool ("true"), values from the DB are strings (true|false)
		$this->bShowMultipleEntriesSubmitConfirmation = appUserPreferences::GetPref('activity_panel.show_multiple_entries_submit_confirmation', static::DEFAULT_SHOW_MULTIPLE_ENTRIES_SUBMI_CONFIRMATION) !== 'false';
		return $this;
	}
}
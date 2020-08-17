<?php
/**
 * Copyright (C) 2013-2020 Combodo SARL
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

namespace Combodo\iTop\Application\UI\Layout\ActivityPanel;


use AttributeDateTime;
use Combodo\iTop\Application\UI\Layout\ActivityPanel\ActivityEntry\ActivityEntry;
use Combodo\iTop\Application\UI\Layout\ActivityPanel\ActivityEntry\ActivityEntryFactory;
use Combodo\iTop\Application\UI\Layout\ActivityPanel\ActivityEntry\CaseLogEntry;
use Combodo\iTop\Application\UI\UIBlock;
use DBObject;
use MetaModel;

/**
 * Class ActivityPanel
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Application\UI\Layout\ActivityPanel
 * @internal
 * @since 2.8.0
 */
class ActivityPanel extends UIBlock
{
	// Overloaded constants
	const BLOCK_CODE = 'ibo-activity-panel';
	const HTML_TEMPLATE_REL_PATH = 'layouts/activity-panel/layout';
	const JS_TEMPLATE_REL_PATH = 'layouts/activity-panel/layout';
	const JS_FILES_REL_PATH = [
		'js/layouts/activity-panel.js',
	];

	/** @var \DBObject $oObject The object for which the activity panel is for */
	protected $oObject;
	/** @var array $aCaseLogs Metadata of the case logs (att. code, color, ...), will be use to make the tabs and identify them easily */
	protected $aCaseLogs;
	/** @var ActivityEntry[] $aEntries */
	protected $aEntries;
	/** @var bool $bAreEntriedSorted True if the entries have been sorted by date */
	protected $bAreEntriedSorted;
	/** @var bool $bHasLifecycle True if the host object has a lifecycle */
	protected $bHasLifecycle;

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
	public function __construct(DBObject $oObject, $aEntries = [], $sId = null)
	{
		parent::__construct($sId);

		$this->InitializeCaseLogTabs();
		$this->SetObject($oObject);
		$this->SetEntries($aEntries);
		$this->bAreEntriedSorted = false;
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

		// Initialize the case log tabs
		$this->InitializeCaseLogTabs();
		$aCaseLogAttCodes = MetaModel::GetAttributesList($sObjectClass, ['AttributeCaseLog']);
		foreach($aCaseLogAttCodes as $sCaseLogAttCode)
		{
			$this->AddCaseLogTab($sCaseLogAttCode);
		}

		// Check if object has a lifecycle
		$this->bHasLifecycle = !empty(MetaModel::GetStateAttributeCode($sObjectClass));

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
	 * Set all entries at once.
	 *
	 * @param ActivityEntry[] $aEntries
	 *
	 * @return $this
	 * @throws \Exception
	 */
	public function SetEntries($aEntries)
	{
		// Reset entries
		$this->aEntries = [];

		foreach($aEntries as $oEntry)
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
	public function GetEntries()
	{
		if($this->bAreEntriedSorted === false)
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
	public function GetGroupedEntries()
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
		$aGroupedEntries[] = $aCurrentGroup;

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

				if($sDateTimeA === $sDateTimeB)
				{
					return 0;
				}

				return ($sDateTimeA > $sDateTimeB) ? -1 : 1;
			});
		}
		$this->bAreEntriedSorted = true;

		return $this;
	}

	/**
	 * Add an $oEntry after all others, excepted if there is already an entry with the same ID in which case it replaces it.
	 *
	 * @param \Combodo\iTop\Application\UI\Layout\ActivityPanel\ActivityEntry\ActivityEntry $oEntry
	 *
	 * @return $this
	 * @throws \Exception
	 */
	public function AddEntry(ActivityEntry $oEntry)
	{
		$this->aEntries[$oEntry->GetId()] = $oEntry;
		$this->bAreEntriedSorted = false;

		// Add case log to the panel and update metadata when necessary
		if($oEntry instanceof CaseLogEntry)
		{
			$sCaseLogAttCode = $oEntry->GetAttCode();
			$sAuthorLogin = $oEntry->GetAuthorLogin();

			// Initialize case log metadata
			if($this->HasCaseLogTab($sCaseLogAttCode) === false)
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
	public function RemoveEntry($sEntryId)
	{
		if(array_key_exists($sEntryId, $this->aEntries))
		{
			// Recompute case logs metadata only if necessary
			$oEntry = $this->aEntries[$sEntryId];
			if($oEntry instanceof CaseLogEntry)
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
	public function HasEntries()
	{
		return !empty($this->aEntries);
	}

	/**
	 * Return all the case log tabs metadata, not their entries
	 *
	 * @return array
	 */
	public function GetCaseLogTabs()
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
	 *
	 * @param string $sAttCode
	 *
	 * @return $this
	 * @throws \Exception
	 */
	protected function AddCaseLogTab($sAttCode)
	{
		// Add case log only if not already existing
		if(!array_key_exists($sAttCode, $this->aCaseLogs))
		{
			$this->aCaseLogs[$sAttCode] = [
				'rank' => count($this->aCaseLogs) + 1,
				'title' => MetaModel::GetLabel(get_class($this->oObject), $sAttCode),
				'total_messages_count' => 0,
				'authors' => [],
			];
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
	protected function RemoveCaseLogTab($sAttCode)
	{
		if(array_key_exists($sAttCode, $this->aCaseLogs))
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
	public function HasCaseLogTab($sAttCode)
	{
		return isset($this->aCaseLogs[$sAttCode]);
	}

	/**
	 * Return true if there is at least one case log declared.
	 *
	 * @return bool
	 */
	public function HasCaseLogTabs()
	{
		return !empty($this->aCaseLogs);
	}

	/**
	 * Return true if the host object has a lifecycle
	 *
	 * @return bool
	 */
	public function HasLifecycle()
	{
		return $this->bHasLifecycle;
	}

	/**
	 * Return the formatted (user-friendly) date time format for the JS widget.
	 * Will be used by moment.js for instance.
	 *
	 * @return string
	 */
	public function GetDateTimeFormatForJSWidget()
	{
		$oDateTimeFormat = AttributeDateTime::GetFormat();
		return $oDateTimeFormat->ToMomentJS();
	}
}
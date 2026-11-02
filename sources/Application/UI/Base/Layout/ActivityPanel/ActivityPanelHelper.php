<?php

/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Base\Layout\ActivityPanel;

use appUserPreferences;
use BinaryExpression;
use cmdbAbstractObject;
use Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry\ActivityEntryFactory;
use Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry\CaseLogEditEntry;
use Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry\EditsEntry;
use DBObjectSearch;
use DBObjectSet;
use Exception;
use ExceptionLog;
use FieldExpression;
use IssueLog;
use MetaModel;
use VariableExpression;

/**
 * Class ActivityPanelHelper
 *
 * @internal
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @since 3.0.0
 * @package Combodo\iTop\Application\UI\Base\Layout\ActivityPanel
 */
class ActivityPanelHelper
{
	/**
	 * Find all native history entries for an edited CaseLog entry.
	 *
	 * @param string $sObjectClass
	 * @param string $sObjectId
	 * @param string $sAttCode
	 * @param int $iEntryIndex Internal CaseLog index, oldest entry first
	 *
	 * @return array<int, \Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry\CaseLogEditEntry>
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \OQLException
	 * @throws \ReflectionException
	 * @since 3.3.0 N°1365 - Display CaseLog edit history
	 */
	public static function GetCaseLogEditEntries(string $sObjectClass, string $sObjectId, string $sAttCode, int $iEntryIndex): array
	{
		$oSearch = DBObjectSearch::FromOQL('SELECT CO, C FROM CMDBChangeOpSetAttributeCaseLog AS CO JOIN CMDBChange AS C ON CO.change = C.id WHERE CO.objclass = :obj_class AND CO.objkey = :obj_key AND CO.attcode = :att_code AND CO.lastentry = :entry_index AND CO.is_edit = :is_edit');
		$oSet = new DBObjectSet($oSearch, ['CO.id' => false], [
			'obj_class' => $sObjectClass,
			'obj_key' => $sObjectId,
			'att_code' => $sAttCode,
			'entry_index' => $iEntryIndex,
			'is_edit' => true,
		]);

		$aEntries = [];
		while ($aChangeRow = $oSet->FetchAssoc()) {
			$oChangeOp = $aChangeRow['CO'];
			try {
				$oEntry = ActivityEntryFactory::MakeFromCmdbChangeOp($oChangeOp, $aChangeRow['C']);
			} catch (Exception $oException) {
				ExceptionLog::LogException($oException);
				continue;
			}
			if ($oEntry instanceof CaseLogEditEntry) {
				$aEntries[] = $oEntry;
			}
		}

		return $aEntries;
	}

	/**
	 * Save in the user pref. if the activity panel should be expanded or not for $sObjectClass in $sObjectMode
	 *
	 * @param string $sObjectClass
	 * @param string $sObjectMode
	 * @param bool $bIsExpanded
	 *
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 * @throws \Exception
	 */
	public static function SaveExpandedStateForClass(string $sObjectClass, string $sObjectMode, bool $bIsExpanded): void
	{
		if (false === MetaModel::IsValidClass($sObjectClass)) {
			throw new Exception('"'.$sObjectClass.'" must be a valid class.');
		}

		if (false === in_array($sObjectMode, cmdbAbstractObject::EnumDisplayModes())) {
			throw new Exception('Wrong object mode "'.$sObjectMode.'", must be among '.implode(' / ', cmdbAbstractObject::EnumDisplayModes()));
		}

		$aStates = appUserPreferences::GetPref('activity_panel.is_expanded', []);
		$aStates[$sObjectClass.'::'.$sObjectMode] = $bIsExpanded;
		appUserPreferences::SetPref('activity_panel.is_expanded', $aStates);
	}

	/**
	 * Save in the user pref. if the activity panel should be expanded or not for $sObjectClass in $sObjectMode
	 *
	 * @param string $sObjectClass
	 * @param string $sObjectMode
	 * @param bool $bIsClosed
	 *
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 * @throws \Exception
	 */
	public static function SaveClosedStateForClass(string $sObjectClass, string $sObjectMode, bool $bIsClosed)
	{
		if (false === MetaModel::IsValidClass($sObjectClass)) {
			throw new Exception('"'.$sObjectClass.'" must be a valid class.');
		}

		if (false === in_array($sObjectMode, cmdbAbstractObject::EnumDisplayModes())) {
			throw new Exception('Wrong object mode "'.$sObjectMode.'", must be among '.implode(' / ', cmdbAbstractObject::EnumDisplayModes()));
		}

		$aStates = appUserPreferences::GetPref('activity_panel.is_closed', []);
		$aStates[$sObjectClass.'::'.$sObjectMode] = $bIsClosed;
		appUserPreferences::SetPref('activity_panel.is_closed', $aStates);
	}

	/**
	 * @param string $sObjectClass
	 * @param string $sObjectId
	 * @param string|null $sChangeOpIdToOffsetFrom Entries will be retrieved after this CMDBChangeOp ID. Typically used for pagination.
	 * @param bool $bLimitResultsLength True to limit to the X previous entries, false to retrieve them all
	 *
	 * @return array The 'max_history_length' Activity entries from the CMDBChangeOp of the object, starting from $sChangeOpIdToOffsetFrom. Flag to know if more entries are available and the ID of the last returned entry are also provided.
	 *
	 * [
	 *  'entries' => ActivityEntry[],
	 *  'last_loaded_entry_id' => null|int,
	 *  'more_entries_to_load' => bool,
	 * ]
	 *
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 * @since 3.3.0 N°1365 - Keep CaseLog edits out of the Activity feed
	 */
	public static function GetCMDBChangeOpEditsEntriesForObject(string $sObjectClass, string $sObjectId, ?string $sChangeOpIdToOffsetFrom = null, bool $bLimitResultsLength = true): array
	{
		$iMaxHistoryLength = (int)MetaModel::GetConfig()->Get('max_history_length');
		$aResults = [
			'entries' => [],
			'last_loaded_entry_id' => null,
			'more_entries_to_load' => false,
		];

		// - Prepare query to retrieve changes
		// N°3924: The "CO.objkey > 0" clause is there to avoid retrieving orphan elements from objects that have not been completely created / cleaned. There seem to be a lot of them due to some cron tasks.
		$oSearch = DBObjectSearch::FromOQL('SELECT CO, C FROM CMDBChangeOp AS CO JOIN CMDBChange AS C ON CO.change = C.id WHERE CO.objclass = :obj_class AND CO.objkey = :obj_key AND CO.objkey > 0 AND CO.finalclass NOT IN (:excluded_optypes)');
		$aArgs = [
			'obj_class' => $sObjectClass,
			'obj_key' => $sObjectId,
			'excluded_optypes' => ['CMDBChangeOpSetAttributeCaseLog'],
		];

		// - Optional offset condition
		if (null !== $sChangeOpIdToOffsetFrom) {
			$oSearch->AddConditionExpression(
				new BinaryExpression(
					new FieldExpression('id', 'CO'),
					'<',
					new VariableExpression('id')
				)
			);
			$aArgs['id'] = $sChangeOpIdToOffsetFrom;
		}

		// Note: We can't order by date (only) as something multiple CMDBChangeOp rows are inserted at the same time (eg. Delivery model of the "Demo" Organization in the sample data).
		// As the DB returns rows "chronologically", we get the older first and it messes with the processing. Ordering by the ID is way much simpler and less DB CPU consuming.
		$oSet = new DBObjectSet($oSearch, ['CO.id' => false], $aArgs);

		$aChangeRows = [];
		if ($bLimitResultsLength) {
			$oSet->SetLimit($iMaxHistoryLength + 1);
		}
		while ($aElements = $oSet->FetchAssoc()) {
			$aChangeRows[] = $aElements;
		}
		unset($oSet);

		usort($aChangeRows, static function (array $aLeft, array $aRight): int {
			return (int)$aRight['CO']->GetKey() <=> (int)$aLeft['CO']->GetKey();
		});
		if ($bLimitResultsLength && (count($aChangeRows) > $iMaxHistoryLength)) {
			$aResults['more_entries_to_load'] = true;
			$aChangeRows = array_slice($aChangeRows, 0, $iMaxHistoryLength);
		}

		// Prepare previous values to group edits within a same CMDBChange
		$iPreviousChangeId = 0;
		/** @var string|int $iPreviousChangeOpId Only used for pagination */
		$iPreviousChangeOpId = 0;
		$oPreviousEditsEntry = null;

		/** @var \CMDBChangeOp $oChangeOp */
		foreach ($aChangeRows as $aElements) {
			$oChange = $aElements['C'];
			$oChangeOp = $aElements['CO'];

			// Make entry from CMDBChangeOp
			$iChangeId = $oChangeOp->Get('change');
			$iPreviousChangeOpId = $oChangeOp->GetKey();
			try {
				$oEntry = ActivityEntryFactory::MakeFromCmdbChangeOp($oChangeOp, $oChange);
			} catch (Exception $oException) {
				IssueLog::Debug(static::class.': Could not create entry from CMDBChangeOp #'.$oChangeOp->GetKey().' related to '.$oChangeOp->Get('objclass').'::'.$oChangeOp->Get('objkey').': '.$oException->getMessage());
				$oPreviousEditsEntry = null;
				$iPreviousChangeId = $iChangeId;
				continue;
			}
			// If same CMDBChange and mergeable edits entry from the same author, we merge them
			if (($iChangeId == $iPreviousChangeId) && ($oPreviousEditsEntry instanceof EditsEntry) && ($oEntry instanceof EditsEntry) && ($oPreviousEditsEntry->GetAuthorLogin() === $oEntry->GetAuthorLogin())) {
				$oPreviousEditsEntry->Merge($oEntry);
			} else {
				$aResults['entries'][] = $oEntry;
				$oPreviousEditsEntry = ($oEntry instanceof EditsEntry) ? $oEntry : null;
			}

			$iPreviousChangeId = $iChangeId;
		}
		unset($oSet);

		// - Set last entry ID so the other can be loaded later
		if ((true === $aResults['more_entries_to_load']) && (0 !== $iPreviousChangeOpId)) {
			$aResults['last_loaded_entry_id'] = $iPreviousChangeOpId;
		}

		return $aResults;
	}
}

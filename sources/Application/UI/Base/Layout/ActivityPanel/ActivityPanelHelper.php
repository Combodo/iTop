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
use Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry\EditsEntry;
use DBObjectSearch;
use DBObjectSet;
use Exception;
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
	 * @return array The 'max_history_length' edits entries from the CMDBChangeOp of the object, starting from $sChangeOpIdToOffsetFrom. Flag to know if more entries are available and the ID of the last returned entry are also provided.
	 *
	 * [
	 *  'entries' => EditsEntry[],
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
	 */
	public static function GetCMDBChangeOpEditsEntriesForObject(string $sObjectClass, string $sObjectId, ?string $sChangeOpIdToOffsetFrom = null, bool $bLimitResultsLength = true): array
	{
		$iMaxHistoryLength = MetaModel::GetConfig()->Get('max_history_length');
		$aResults = [
			'entries' => [],
			'last_loaded_entry_id' => null,
			'more_entries_to_load' => false,
		];

		// - Prepare query to retrieve changes
		// N°3924: The "CO.objkey > 0" clause is there to avoid retrieving orphan elements from objects that have not been completely created / cleaned. There seem to be a lot of them due to some cron tasks.
		$oSearch = DBObjectSearch::FromOQL('SELECT CO, C FROM CMDBChangeOp AS CO JOIN CMDBChange AS C ON CO.change = C.id WHERE CO.objclass = :obj_class AND CO.objkey = :obj_key AND CO.objkey > 0 AND CO.finalclass NOT IN (:excluded_optypes)');
		$aArgs = ['obj_class' => $sObjectClass, 'obj_key' => $sObjectId, 'excluded_optypes' => ['CMDBChangeOpSetAttributeCaseLog']];

		// - Optional offset condition
		if (null !== $sChangeOpIdToOffsetFrom) {
			$oSearch->AddConditionExpression(
				new BinaryExpression(
					new FieldExpression('id', 'CO'), '<', new VariableExpression('id')
				)
			);
			$aArgs['id'] = $sChangeOpIdToOffsetFrom;
		}

		// Note: We can't order by date (only) as something multiple CMDBChangeOp rows are inserted at the same time (eg. Delivery model of the "Demo" Organization in the sample data).
		// As the DB returns rows "chronologically", we get the older first and it messes with the processing. Ordering by the ID is way much simpler and less DB CPU consuming.
		$oSet = new DBObjectSet($oSearch, ['CO.id' => false], $aArgs);

		// - Limit history entries to display
		if ($bLimitResultsLength) {
			$bMoreEntriesToLoad = $oSet->CountExceeds($iMaxHistoryLength);
			$oSet->SetLimit($iMaxHistoryLength);
		} else {
			$bMoreEntriesToLoad = false;
		}

		// Prepare previous values to group edits within a same CMDBChange
		$iPreviousChangeId = 0;
		/** @var string|int $iPreviousChangeOpId Only used for pagination */
		$iPreviousChangeOpId = 0;
		$oPreviousEditsEntry = null;

		/** @var \CMDBChangeOp $oChangeOp */
		while ($aElements = $oSet->FetchAssoc()) {
			$oChange = $aElements['C'];
			$oChangeOp = $aElements['CO'];

			// Skip case log changes as they are handled directly from the attributes themselves (most of them should have been excluded by the OQL above, but some derivated classes could still be retrieved)
			if ($oChangeOp instanceof CMDBChangeOpSetAttributeCaseLog) {
				continue;
			}

			// Make entry from CMDBChangeOp
			$iChangeId = $oChangeOp->Get('change');
			try {
				$oEntry = ActivityEntryFactory::MakeFromCmdbChangeOp($oChangeOp, $oChange);
			}
			catch (Exception $oException) {
				IssueLog::Debug(static::class.': Could not create entry from CMDBChangeOp #'.$oChangeOp->GetKey().' related to '.$oChangeOp->Get('objclass').'::'.$oChangeOp->Get('objkey').': '.$oException->getMessage());
				continue;
			}
			// If same CMDBChange and mergeable edits entry from the same author, we merge them
			if (($iChangeId == $iPreviousChangeId) && ($oPreviousEditsEntry instanceof EditsEntry) && ($oEntry instanceof EditsEntry) && ($oPreviousEditsEntry->GetAuthorLogin() === $oEntry->GetAuthorLogin())) {
				$oPreviousEditsEntry->Merge($oEntry);
			} else {
				$aResults['entries'][] = $oEntry;

				// Set previous edits entry
				if ($oEntry instanceof EditsEntry) {
					$oPreviousEditsEntry = $oEntry;
				}
			}

			$iPreviousChangeId = $iChangeId;
			$iPreviousChangeOpId = $oChangeOp->GetKey();
		}
		unset($oSet);

		// - Set last entry ID so the other can be loaded later
		if ((true === $bMoreEntriesToLoad) && (0 !== $iPreviousChangeOpId)) {
			$aResults['last_loaded_entry_id'] = $iPreviousChangeOpId;
			$aResults['more_entries_to_load'] = true;
		}

		return $aResults;
	}
}
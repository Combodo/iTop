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

use cmdbAbstractObject;
use Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry\ActivityEntryFactory;
use Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry\CaseLogEditEntry;
use Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityPanelAction\ActivityPanelActionFactory;
use Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\CaseLogEntryForm\CaseLogEntryFormFactory;
use Combodo\iTop\Service\InterfaceDiscovery\InterfaceDiscovery;
use DBObject;
use DBObjectSearch;
use DBObjectSet;
use Dict;
use Exception;
use ExceptionLog;
use ExecutionKPI;
use iPopupMenuExtension;
use IssueLog;
use MetaModel;

/**
 * Class ActivityPanelFactory
 *
 * @internal
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Application\UI\Base\Layout\ActivityPanel
 * @since 3.0.0
 */
class ActivityPanelFactory
{
	/**
	 * Make an activity panel for an object details layout, meaning that it should contain the case logs and the activity.
	 *
	 * @see cmdbAbstractObject::ENUM_DISPLAY_MODE_XXX
	 *
	 * @param \DBObject $oObject
	 * @param string $sMode Mode the object is being displayed (view, edit, create, ...), default is view.
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityPanel
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \DictExceptionMissingString
	 * @throws \MySQLException
	 * @throws \OQLException
	 * @since 3.3.0 N°1365 - Display native CaseLog edit history
	 */
	public static function MakeForObjectDetails(DBObject $oObject, string $sMode = cmdbAbstractObject::DEFAULT_DISPLAY_MODE)
	{
		$oKPI = new ExecutionKPI();
		$sObjClass = get_class($oObject);
		$sObjId = $oObject->GetKey();

		if ($sMode == cmdbAbstractObject::ENUM_DISPLAY_MODE_PRINT) {
			$oActivityPanel = new ActivityPanelPrint($oObject, [], ActivityPanel::BLOCK_CODE);
			$sMode = cmdbAbstractObject::ENUM_DISPLAY_MODE_VIEW;
		} else {
			$oActivityPanel = new ActivityPanel($oObject, [], ActivityPanel::BLOCK_CODE);
		}
		$oActivityPanel->SetObjectMode($sMode);

		// Prepare caselogs
		$aCaseLogTabs = $oActivityPanel->GetCaseLogTabs();
		foreach ($aCaseLogTabs as $sCaseLogAttCode => $aCaseLogData) {
			/** @var \ormCaseLog $oCaseLog */
			$oCaseLog = $oObject->Get($sCaseLogAttCode);

			// Add new entry block only if the case log is not read only
			if (false === $aCaseLogData['is_read_only']) {
				$oActivityPanel->SetCaseLogTabEntryForm($sCaseLogAttCode, CaseLogEntryFormFactory::MakeForCaselogTab($oObject, $sCaseLogAttCode, $sMode));

				// Prefill input if default value passed for new object
				if ($oObject->IsNew() && !$oCaseLog->IsEmpty()) {
					$oActivityPanel->GetCaseLogTabEntryForm($sCaseLogAttCode)->GetTextInput()->SetValue($oCaseLog->GetModifiedEntry(\ormCaseLog::ENUM_FORMAT_HTML));
				}
			}

			if ($oObject->IsNew()) {
				// Skip unnecessary sql requests in creation
				continue;
			}

			// CaseLog operations store the internal entry index, so origin and edit metadata can be matched without inferring identity from entry content.
			$oCaseLogEntriesOriginsSearch = DBObjectSearch::FromOQL('SELECT C, CO FROM CMDBChange AS C JOIN CMDBChangeOpSetAttributeCaseLog AS CO ON CO.change = C.id WHERE CO.objclass = :objclass AND CO.objkey = :objkey AND CO.attcode = :attcode');
			$oCaseLogEntriesOriginsSearch->SetInternalParams([
				'objclass' => $sObjClass,
				'objkey' => $sObjId,
				'attcode' => $sCaseLogAttCode,
			]);

			$oCaseLogEntriesOriginsSet = new DBObjectSet($oCaseLogEntriesOriginsSearch, ['CO.id' => false]);
			$oCaseLogEntriesOriginsSet->OptimizeColumnLoad(['C' => ['origin'], 'CO' => ['attcode', 'date', 'is_edit', 'lastentry', 'oldvalue', 'newvalue', 'user_id', 'userinfo']]);

			$aEntryOriginsByIndex = [];
			$aModificationHistoryByIndex = [];
			while ($aChangeRow = $oCaseLogEntriesOriginsSet->FetchAssoc()) {
				$oChangeOp = $aChangeRow['CO'];
				$iEntryIndex = (int)$oChangeOp->Get('lastentry');
				if ((bool)$oChangeOp->Get('is_edit')) {
					try {
						$oEditActivityEntry = ActivityEntryFactory::MakeFromCmdbChangeOp($oChangeOp, $aChangeRow['C']);
					} catch (Exception $oException) {
						ExceptionLog::LogException($oException);
						continue;
					}
					if ($oEditActivityEntry instanceof CaseLogEditEntry) {
						$aModificationHistoryByIndex[$iEntryIndex][] = $oEditActivityEntry;
					}
				} elseif (!isset($aEntryOriginsByIndex[$iEntryIndex])) {
					$aEntryOriginsByIndex[$iEntryIndex] = $aChangeRow['C']->Get('origin');
				}
			}

			if ($oCaseLog->GetEntryCount() !== count($aEntryOriginsByIndex)) {
				IssueLog::Debug(static::class.": Number of log entries ({$oCaseLog->GetEntryCount()}) don't match number of corresponding CMDBChanges (".count($aEntryOriginsByIndex).") for object {$sObjClass}::{$sObjId} / attribute {$sCaseLogAttCode}");
			}

			$iCurrentEntryIndex = $oCaseLog->GetLatestEntryIndex();
			foreach ($oCaseLog->GetAsArray() as $aOrmEntry) {
				$oCaseLogEntry = ActivityEntryFactory::MakeFromCaseLogEntryArray($sCaseLogAttCode, $aOrmEntry, $iCurrentEntryIndex, $oObject, $oActivityPanel->IsCaseLogEditionEnabled());
				if (isset($aEntryOriginsByIndex[$iCurrentEntryIndex])) {
					$oCaseLogEntry->SetOrigin($aEntryOriginsByIndex[$iCurrentEntryIndex]);
				}
				if (isset($aModificationHistoryByIndex[$iCurrentEntryIndex])) {
					$oCaseLogEntry->SetModificationHistory($aModificationHistoryByIndex[$iCurrentEntryIndex]);
				}

				$oActivityPanel->AddEntry($oCaseLogEntry);
				$iCurrentEntryIndex--;
			}
		}

		if (!$oObject->IsNew()) {
			// Retrieve history changes (excluding case logs entries)
			$aChangesData = ActivityPanelHelper::GetCMDBChangeOpEditsEntriesForObject($sObjClass, $sObjId);

			// - Set metadata for pagination
			if (true === $aChangesData['more_entries_to_load']) {
				$oActivityPanel->SetHasMoreEntriesToLoad(true);
				$oActivityPanel->SetLastEntryId('cmdbchangeop', $aChangesData['last_loaded_entry_id']);
			}

			// - Add history entries
			/** @var \Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry\EditsEntry $oEntry */
			foreach ($aChangesData['entries'] as $oEntry) {
				$oActivityPanel->AddEntry($oEntry);
			}

			// Retrieving notification events for cmdbAbstractObject only
			if ($oObject instanceof cmdbAbstractObject) {
				$aRelatedTriggersIDs = $oObject->GetRelatedTriggersIDs();

				// Protection for classes which have no related trigger
				if (false === empty($aRelatedTriggersIDs)) {
					// - Prepare query to retrieve events
					$oNotifEventsSearch = DBObjectSearch::FromOQL('SELECT EN FROM EventNotification AS EN JOIN Action AS A ON EN.action_id = A.id WHERE EN.trigger_id IN (:triggers_ids) AND EN.object_id = :object_id');
					$oNotifEventsSearch->AllowAllData();
					$oNotifEventsSet = new DBObjectSet($oNotifEventsSearch, ['id' => false], ['triggers_ids' => $aRelatedTriggersIDs, 'object_id' => $sObjId]);
					$oNotifEventsSet->SetLimit(MetaModel::GetConfig()->Get('max_history_length'));

					/** @var \EventNotification $oNotifEvent */
					while ($oNotifEvent = $oNotifEventsSet->Fetch()) {
						try {
							$oEntry = ActivityEntryFactory::MakeFromEventNotification($oNotifEvent);
						} catch (Exception $oException) {
							IssueLog::Debug(static::class.': Could not create entry from EventNotification #'.$oNotifEvent->GetKey().' related to trigger "'.$oNotifEvent->Get('trigger_id_friendlyname').'" / action "'.$oNotifEvent->Get('action_id_friendlyname').'" / object #'.$oNotifEvent->Get('object_id').': '.$oException->getMessage());
							continue;
						}

						$oActivityPanel->AddEntry($oEntry);
					}
					unset($oNotifEventsSet);
				}
			}
		}

		if ($sMode === cmdbAbstractObject::ENUM_DISPLAY_MODE_VIEW) {
			$aActionItems = [];
			/** @var \iPopupMenuExtension $oExtensionInstance */
			foreach (InterfaceDiscovery::GetInstance()->FindItopClasses('iPopupMenuExtension') as $oExtensionInstance) {
				// Iterate over every case log tab to retrieve the actions for each of them
				foreach ($aCaseLogTabs as $sCaseLogAttCode => $aCaseLogData) {
					$aCaselogActions = [];

					// Let's iterate over the menu items for this case log
					foreach ($oExtensionInstance::EnumItems(iPopupMenuExtension::MENU_OBJDETAILS_ACTIVITY_PANEL_ACTIONS, ['object' => $oObject, 'caselog_att_code' => $sCaseLogAttCode]) as $oMenuItem) {
						$aCaselogActions[] = $oMenuItem;
					}

					if (empty($aCaselogActions)) {
						continue;
					}

					// Create a new section for the case log if it doesn't exist yet
					if (!array_key_exists($sCaseLogAttCode, $aActionItems)) {
						$aActionItems[$sCaseLogAttCode] = [
							'label' => MetaModel::GetLabel($sObjClass, $sCaseLogAttCode),
							'items' => [],
						];
					}

					$aActionItems[$sCaseLogAttCode]['items'] = array_merge($aActionItems[$sCaseLogAttCode]['items'], $aCaselogActions);
				}

				$aActivityItems = [];
				// Don't forget 1 last iteration for the activity tab
				foreach ($oExtensionInstance::EnumItems(iPopupMenuExtension::MENU_OBJDETAILS_ACTIVITY_PANEL_ACTIONS, ['object' => $oObject, 'caselog_att_code' => 'activity']) as $oMenuItem) {
					$aActivityItems[] = $oMenuItem;
				}
				if (!empty($aActivityItems)) {
					if (!array_key_exists('activity', $aActionItems)) {
						$aActionItems['activity'] = [
							'label' => Dict::S('UI:Layout:ActivityPanel:Tab:Activity:Title'),
							'items' => [],
						];
					}
					$aActionItems['activity']['items'] = array_merge($aActionItems['activity']['items'], $aActivityItems);
				}
			}

			$oActivityPanel->SetActivityActions(ActivityPanelActionFactory::MakeFromApplicationPopupItems($aActionItems));
		}

		$oKPI->ComputeStatsForExtension(new ActivityPanelFactory(), 'MakeForObjectDetails');

		return $oActivityPanel;
	}
}

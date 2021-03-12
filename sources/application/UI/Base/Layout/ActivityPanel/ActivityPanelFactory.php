<?php
/**
 * Copyright (C) 2013-2021 Combodo SARL
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
use CMDBChangeOpSetAttributeCaseLog;
use Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry\ActivityEntryFactory;
use Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry\EditsEntry;
use Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\CaseLogEntryFormFactory\CaseLogEntryFormFactory;
use DBObject;
use DBObjectSearch;
use DBObjectSet;
use Exception;
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
	 * @see cmdbAbstractObject::ENUM_OBJECT_MODE_XXX
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
	 */
	public static function MakeForObjectDetails(DBObject $oObject, string $sMode = cmdbAbstractObject::DEFAULT_OBJECT_MODE)
	{
		$sObjClass = get_class($oObject);
		$iObjId = $oObject->GetKey();

		if ($sMode == cmdbAbstractObject::ENUM_OBJECT_MODE_PRINT) {
			$oActivityPanel = new ActivityPanelPrint($oObject, [], ActivityPanel::BLOCK_CODE);
			$sMode = cmdbAbstractObject::ENUM_OBJECT_MODE_VIEW;
		} else {
			$oActivityPanel = new ActivityPanel($oObject, [], ActivityPanel::BLOCK_CODE);
		}
		$oActivityPanel->SetObjectMode($sMode);

		// Prepare caselogs
		$aCaseLogTabs = $oActivityPanel->GetCaseLogTabs();
		foreach($aCaseLogTabs as $sCaseLogAttCode => $aCaseLogData)
		{
			// Add new entry block only if the case log is not read only
			if (false === $aCaseLogData['is_read_only']) {
				$oActivityPanel->SetCaseLogTabEntryForm($sCaseLogAttCode, CaseLogEntryFormFactory::MakeForCaselogTab($oObject, $sCaseLogAttCode, $sMode));
			}

			// Retrieve case logs entries
			/** @var \ormCaseLog $oCaseLog */
			$oCaseLog = $oObject->Get($sCaseLogAttCode);
			foreach ($oCaseLog->GetAsArray() as $aOrmEntry) {
				$oCaseLogEntry = ActivityEntryFactory::MakeFromCaseLogEntryArray($sCaseLogAttCode, $aOrmEntry);
				$oActivityPanel->AddEntry($oCaseLogEntry);
			}
		}

		// Retrieve history changes (excluding case logs entries)
		// - Prepare query to retrieve changes
		$oChangesSearch = DBObjectSearch::FromOQL('SELECT CMDBChangeOp WHERE objclass = :obj_class AND objkey = :obj_key AND finalclass NOT IN (:excluded_optypes)');
		// Note: We can't order by date (only) as something multiple CMDBChangeOp rows are inserted at the same time (eg. Delivery model of the "Demo" Organization in the sample data).
		// As the DB returns rows "chronologically", we get the older first and it messes with the processing. Ordering by the ID is way much simpler and less DB CPU consuming.
		$oChangesSet = new DBObjectSet($oChangesSearch, ['id' => false], ['obj_class' => $sObjClass, 'obj_key' => $iObjId, 'excluded_optypes' => ['CMDBChangeOpSetAttributeCaseLog']]);
		$oChangesSet->SetLimit(MetaModel::GetConfig()->Get('max_history_length'));

		// Prepare previous values to group edits within a same CMDBChange
		$iPreviousChangeId = 0;
		$oPreviousEditsEntry = null;

		/** @var \CMDBChangeOp $oChangeOp */
		while ($oChangeOp = $oChangesSet->Fetch()) {
			// Skip case log changes as they are handled directly from the attributes themselves (most of them should have been excluded by the OQL above, but some derivated classes could still be retrieved)
			if ($oChangeOp instanceof CMDBChangeOpSetAttributeCaseLog) {
				continue;
			}

			// Make entry from CMDBChangeOp
			$iChangeId = $oChangeOp->Get('change');
			try {
				$oEntry = ActivityEntryFactory::MakeFromCmdbChangeOp($oChangeOp);
			}
			catch (Exception $oException) {
				IssueLog::Debug(static::class.': Could not create entry from CMDBChangeOp #'.$oChangeOp->GetKey().' related to '.$oChangeOp->Get('objclass').'::'.$oChangeOp->Get('objkey').': '.$oException->getMessage());
				continue;
			}
			// If same CMDBChange and mergeable edits entry from the same author, we merge them
			if (($iChangeId == $iPreviousChangeId) && ($oPreviousEditsEntry instanceof EditsEntry) && ($oEntry instanceof EditsEntry) && ($oPreviousEditsEntry->GetAuthorLogin() === $oEntry->GetAuthorLogin())) {
				$oPreviousEditsEntry->Merge($oEntry);
			} else {
				$oActivityPanel->AddEntry($oEntry);

				// Set previous edits entry
				if ($oEntry instanceof EditsEntry) {
					$oPreviousEditsEntry = $oEntry;
				}
			}

			$iPreviousChangeId = $iChangeId;
		}
		unset($oChangesSet);

		// Retrieving notification events for cmdbAbstractObject only
		if ($oObject instanceof cmdbAbstractObject) {
			$aRelatedTriggersIDs = $oObject->GetRelatedTriggersIDs();

			// Protection for classes which have no related trigger
			if (false === empty($aRelatedTriggersIDs)) {
				// - Prepare query to retrieve events
				$oNotifEventsSearch = DBObjectSearch::FromOQL('SELECT EN FROM EventNotification AS EN JOIN Action AS A ON EN.action_id = A.id WHERE EN.trigger_id IN (:triggers_ids) AND EN.object_id = :object_id');
				$oNotifEventsSet = new DBObjectSet($oNotifEventsSearch, ['id' => false], ['triggers_ids' => $aRelatedTriggersIDs, 'object_id' => $iObjId]);
				$oNotifEventsSet->SetLimit(MetaModel::GetConfig()->Get('max_history_length'));

				/** @var \EventNotification $oNotifEvent */
				while ($oNotifEvent = $oNotifEventsSet->Fetch()) {
					try {
						$oEntry = ActivityEntryFactory::MakeFromEventNotification($oNotifEvent);
					}
					catch (Exception $oException) {
						IssueLog::Debug(static::class.': Could not create entry from EventNotification #'.$oNotifEvent->GetKey().' related to trigger "'.$oNotifEvent->Get('trigger_id_friendlyname').'" / action "'.$oNotifEvent->Get('action_id_friendlyname').'" / object #'.$oNotifEvent->Get('object_id').': '.$oException->getMessage());
						continue;
					}

					$oActivityPanel->AddEntry($oEntry);
				}
				unset($oNotifEventsSet);
			}
		}

		return $oActivityPanel;
	}
}
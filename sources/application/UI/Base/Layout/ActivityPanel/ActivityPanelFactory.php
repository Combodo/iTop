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
	 * @param \DBObject $oObject
	 * @param string    $sMode Mode the object is being displayed (view, edit, create, ...), default is view.
	 *
	 * @see cmdbAbstractObject::ENUM_OBJECT_MODE_XXX
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

		$oActivityPanel = new ActivityPanel($oObject);
		$oActivityPanel->SetObjectMode($sMode);

		// Prepare caselogs
		$aCaseLogAttCodes = array_keys($oActivityPanel->GetCaseLogTabs());
		foreach($aCaseLogAttCodes as $sCaseLogAttCode)
		{
			// Add new entry block
			$oActivityPanel->SetCaseLogTabEntryForm($sCaseLogAttCode, CaseLogEntryFormFactory::MakeForCaselogTab($oObject, $sCaseLogAttCode, $sMode));

			// Retrieve case logs entries
			/** @var \ormCaseLog $oCaseLog */
			$oCaseLog = $oObject->Get($sCaseLogAttCode);
			foreach($oCaseLog->GetAsArray() as $aOrmEntry)
			{
				$oCaseLogEntry = ActivityEntryFactory::MakeFromCaseLogEntryArray($sCaseLogAttCode, $aOrmEntry);
				$oActivityPanel->AddEntry($oCaseLogEntry);
			}
		}

		// Activity tab entry form is only in view mode
		// As caselog tabs input will be attached to the main object form and submit button hidden, we can't have an entry form in the activity tab as it's not for a specific caselog
		if($sMode === cmdbAbstractObject::ENUM_OBJECT_MODE_VIEW) {
			$oActivityPanel->SetActivityTabEntryForm(CaseLogEntryFormFactory::MakeForActivityTab($oObject, $sMode));
		}

		// Retrieve history changes (including case logs entries)
		// - Prepare query to retrieve changes
		$oChangesSearch = DBObjectSearch::FromOQL('SELECT CMDBChangeOp WHERE objclass = :obj_class AND objkey = :obj_key');
		// Note: We can't order by date (only) as something multiple CMDBChangeOp rows are inserted at the same time (eg. Delivery model of the "Demo" Organization in the sample data).
		// As the DB returns rows "chronologically", we get the older first and it messes with the processing. Ordering by the ID is way much simpler and less DB CPU consuming.
		$oChangesSet = new DBObjectSet($oChangesSearch, ['id' => false], ['obj_class' => $sObjClass, 'obj_key' => $iObjId]);
		// Note: This limit will include case log changes which will be skipped, but still we count them as they are displayed anyway by the case log attributes themselves
		$oChangesSet->SetLimit(MetaModel::GetConfig()->Get('max_history_length'));

		// Prepare previous values to group edits within a same CMDBChange
		$iPreviousChangeId = 0;
		$oPreviousEditsEntry = null;

		/** @var \CMDBChangeOp $oChangeOp */
		while($oChangeOp = $oChangesSet->Fetch()) {
			// Skip case log changes as they are handled directly from the attributes themselves
			if ($oChangeOp instanceof CMDBChangeOpSetAttributeCaseLog) {
				continue;
			}

			// Make entry from CMDBChangeOp
			$iChangeId = $oChangeOp->Get('change');
			try {
				$oEntry = ActivityEntryFactory::MakeFromCmdbChangeOp($oChangeOp);
			} catch (Exception $e) {
				continue;
			}
			// If same CMDBChange and mergeable edits entry from the same author, we merge them
			if (($iChangeId == $iPreviousChangeId) && ($oPreviousEditsEntry instanceof EditsEntry) && ($oEntry instanceof EditsEntry) && ($oPreviousEditsEntry->GetAuthorLogin() === $oEntry->GetAuthorLogin())) {
				$oPreviousEditsEntry->Merge($oEntry);
			} else {
				$oActivityPanel->AddEntry($oEntry);

				// Set previous edits entry
				if($oEntry instanceof EditsEntry)
				{
					$oPreviousEditsEntry = $oEntry;
				}
			}

			$iPreviousChangeId = $iChangeId;
		}

		return $oActivityPanel;
	}
}
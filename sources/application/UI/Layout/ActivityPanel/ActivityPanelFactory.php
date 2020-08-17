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


use CMDBChangeOpSetAttributeCaseLog;
use Combodo\iTop\Application\UI\Layout\ActivityPanel\ActivityEntry\ActivityEntryFactory;
use Combodo\iTop\Application\UI\Layout\ActivityPanel\ActivityEntry\EditsEntry;
use DBObject;
use DBObjectSearch;
use DBObjectSet;
use MetaModel;
use UserRights;

/**
 * Class ActivityPanelFactory
 *
 * @internal
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Application\UI\Layout\ActivityPanel
 * @since 2.8.0
 */
class ActivityPanelFactory
{
	/**
	 * Make an activity panel for an object details layout, meaning that it should contain the caselogs and the activity.
	 *
	 * @param \DBObject $oObject
	 *
	 * @return \Combodo\iTop\Application\UI\Layout\ActivityPanel\ActivityPanel
	 * @throws \CoreException
	 * @throws \Exception
	 */
	public static function MakeForObjectDetails(DBObject $oObject)
	{
		$sObjClass = get_class($oObject);
		$iObjId = $oObject->GetKey();

		$oActivityPanel = new ActivityPanel($oObject);

		// Retrieve case logs entries
		$aCaseLogAttCodes = array_keys($oActivityPanel->GetCaseLogTabs());
		foreach($aCaseLogAttCodes as $sCaseLogAttCode)
		{
			/** @var \ormCaseLog $oCaseLog */
			$oCaseLog = $oObject->Get($sCaseLogAttCode);
			foreach($oCaseLog->GetAsArray() as $aOrmEntry)
			{
				$oCaseLogEntry = ActivityEntryFactory::MakeFromCaseLogEntryArray($sCaseLogAttCode, $aOrmEntry);
				$oActivityPanel->AddEntry($oCaseLogEntry);
			}
		}

		// Retrieve history changes (including case logs entries)
		// - Prepare query to retrieve changes
		$oChangesSearch = DBObjectSearch::FromOQL('SELECT CMDBChangeOp WHERE objclass = :obj_class AND objkey = :obj_key');
		$oChangesSet = new DBObjectSet($oChangesSearch, ['date' => false], ['obj_class' => $sObjClass, 'obj_key' => $iObjId]);
		// Note: This limit will include case log changes which will be skipped, but still we count them as they are displayed anyway by the case log attributes themselves
		$oChangesSet->SetLimit(MetaModel::GetConfig()->Get('max_history_length'));

		// Prepare previous values to group edits within a same CMDBChange
		$iPreviousChangeId = 0;
		$oPreviousEditsEntry = null;

		/** @var \CMDBChangeOp $oChangeOp */
		while($oChangeOp = $oChangesSet->Fetch())
		{
			// Skip case log changes as they are handled directly from the attributes themselves
			if($oChangeOp instanceof CMDBChangeOpSetAttributeCaseLog)
			{
				continue;
			}

			// Make entry from CMDBChangeOp
			$iChangeId = $oChangeOp->Get('change');
			$oEntry = ActivityEntryFactory::MakeFromCmdbChangeOp($oChangeOp);

			// If same CMDBChange and mergeable edits entry, we merge them
			if( ($iChangeId == $iPreviousChangeId) && ($oPreviousEditsEntry instanceof EditsEntry) && ($oEntry instanceof EditsEntry))
			{
				$oPreviousEditsEntry->Merge($oEntry);
			}
			else
			{
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
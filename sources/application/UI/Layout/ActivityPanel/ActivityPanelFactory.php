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


use Combodo\iTop\Application\UI\Layout\ActivityPanel\ActivityEntry\ActivityEntryFactory;
use DBObject;
use MetaModel;

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

		// Retrieve history changes

		return $oActivityPanel;
	}
}
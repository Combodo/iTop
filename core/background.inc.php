<?php
// Copyright (C) 2024 Combodo SAS
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify	
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>


/**
 * Tasks performed in the background
 *
 * @copyright   Copyright (C) 2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


class ObsolescenceDateUpdater implements iBackgroundProcess
{
	public function GetPeriodicity()
	{
		return MetaModel::GetConfig()->Get('obsolescence.date_update_interval'); // 10 mn
	}

	public function Process($iUnixTimeLimit)
	{
		$iCountSet = 0;
		$iCountReset = 0;
		$iClasses = 0;
		foreach (MetaModel::EnumObsoletableClasses() as $sClass)
		{
			$oObsoletedToday = new DBObjectSearch($sClass);
			$oObsoletedToday->AddCondition('obsolescence_flag', 1, '=');
			$oObsoletedToday->AddCondition('obsolescence_date', null, '=');
			$sToday = date(AttributeDate::GetSQLFormat());
			$iCountSet += MetaModel::BulkUpdate($oObsoletedToday, array('obsolescence_date' => $sToday));

			$oObsoletedToday = new DBObjectSearch($sClass);
			$oObsoletedToday->AddCondition('obsolescence_flag', 1, '!=');
			$oObsoletedToday->AddCondition('obsolescence_date', null, '!=');
			$iCountReset += MetaModel::BulkUpdate($oObsoletedToday, array('obsolescence_date' => null));
		}
		return "Obsolescence date updated (classes: $iClasses ; set: $iCountSet ; reset: $iCountReset)\n";
	}
}

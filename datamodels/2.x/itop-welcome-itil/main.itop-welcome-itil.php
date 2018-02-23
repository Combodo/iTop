<?php
// Copyright (C) 2010-2012 Combodo SARL
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

// Add the standard menus (done in XML)
/*
 * +--------------------+
 * | Welcome            |
 * +--------------------+
 * 		Welcome To iTop
 * +--------------------+
 * | Tools              |
 * +--------------------+
 * 		CSV Import
 * +--------------------+
 * | Admin Tools        |
 * +--------------------+
 *		User Accounts
 *		Profiles
 *		Notifications
 *		Run Queries
 *		Export
 *		Data Model
 *		Universal Search
 */

/**
 * Direct end-users to the standard Portal application
 */ 
class MyPortalURLMaker implements iDBObjectURLMaker
{
	public static function MakeObjectURL($sClass, $iId)
	{
		if (strpos(MetaModel::GetConfig()->Get('portal_tickets'), $sClass) !== false)
		{
			$sAbsoluteUrl = utils::GetAbsoluteUrlAppRoot();
			$sUrl = "{$sAbsoluteUrl}portal/index.php?operation=details&class=$sClass&id=$iId";
		}
		else
		{
			$sUrl = '';
		}
		return $sUrl;
	}
}

?>

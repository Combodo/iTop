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


// Add the standard menus
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
 * | Admin Tools        | << Only present if the user is an admin
 * +--------------------+
 *		User Accounts
 *		Profiles
 *		Notifications
 *		Run Queries
 *		Export
 *		Data Model
 *		Universal Search
 */


class ItopWelcome extends ModuleHandlerAPI
{
	public static function OnMenuCreation()
	{
		$oToolsMenu = new MenuGroup('DataAdministration', 70 /* fRank */, 'Organization', UR_ACTION_MODIFY, UR_ALLOWED_YES|UR_ALLOWED_DEPENDS);
		new WebPageMenuNode('CSVImportMenu', utils::GetAbsoluteUrlAppRoot().'pages/csvimport.php', $oToolsMenu->GetIndex(), 1 /* fRank */);
		
		// Add the admin menus
		if (UserRights::IsAdministrator())
		{
			$oAdminMenu = new MenuGroup('AdminTools', 80 /* fRank */);
			new OQLMenuNode('UserAccountsMenu', 'SELECT User', $oAdminMenu->GetIndex(), 1 /* fRank */);
			new OQLMenuNode('ProfilesMenu', 'SELECT URP_Profiles', $oAdminMenu->GetIndex(), 2 /* fRank */);
			new WebPageMenuNode('NotificationsMenu', utils::GetAbsoluteUrlAppRoot().'pages/notifications.php', $oAdminMenu->GetIndex(), 3 /* fRank */);
			new OQLMenuNode('AuditCategories', 'SELECT AuditCategory', $oAdminMenu->GetIndex(), 4 /* fRank */);
			new WebPageMenuNode('RunQueriesMenu', utils::GetAbsoluteUrlAppRoot().'pages/run_query.php', $oAdminMenu->GetIndex(), 8 /* fRank */);
			new OQLMenuNode('QueryMenu', 'SELECT Query', $oAdminMenu->GetIndex(), 8.5 /* fRank */, true);
			new WebPageMenuNode('ExportMenu', utils::GetAbsoluteUrlAppRoot().'webservices/export.php', $oAdminMenu->GetIndex(), 9 /* fRank */);
			new WebPageMenuNode('DataModelMenu', utils::GetAbsoluteUrlAppRoot().'pages/schema.php', $oAdminMenu->GetIndex(), 10 /* fRank */);
			new WebPageMenuNode('UniversalSearchMenu', utils::GetAbsoluteUrlAppRoot().'pages/UniversalSearch.php', $oAdminMenu->GetIndex(), 11 /* fRank */);
		}
	}
}

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

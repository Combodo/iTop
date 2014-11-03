<?php
// Copyright (C) 2013 Combodo SARL
//
//   This program is free software; you can redistribute it and/or modify
//   it under the terms of the GNU General Public License as published by
//   the Free Software Foundation; version 3 of the License.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of the GNU General Public License
//   along with this program; if not, write to the Free Software
//   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA


//require_once(APPROOT.'setup/setuputils.class.inc.php');

class ItopConfigEditor extends ModuleHandlerAPI
{
	public static function OnMenuCreation()
	{
		if (UserRights::IsAdministrator())
		{
			$oAdminMenu = new MenuGroup('AdminTools', 80 /* fRank */);
			new WebPageMenuNode('ConfigEditor', utils::GetAbsoluteUrlModulesRoot().'itop-config/config.php', $oAdminMenu->GetIndex(), 18 /* fRank */);
		}
	}
}

<?php
// Copyright (C) 2010 Combodo SARL
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

/**
 * Authent Local
 * User authentication Module, password stored in the local database
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */


class UserLocal extends UserInternal
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "addon/authentication",
			"key_type" => "autoincrement",
			"name_attcode" => "login",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_user_local",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributePassword("password", array("allowed_values"=>null, "sql"=>"pwd", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('contactid', 'first_name', 'email', 'login', 'password', 'language', 'profile_list', 'allowed_org_list')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('first_name', 'last_name', 'login')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('login', 'contactid')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('login', 'contactid')); // Criteria of the advanced search form
	}

	public function CheckCredentials($sPassword)
	{
		if ($this->Get('password') == $sPassword)
		{
			return true;
		}
		return false;
	}

	public function TrustWebServerContext()
	{
		return true;
	}

	public function CanChangePassword()
	{
		// For now everyone can change their password..
		return true;
	}

	public function CanLogOff()
	{
		// Internal authentication allows everybody to log off
		return true;
	}

	public function ChangePassword($sOldPassword, $sNewPassword)
	{
		if ($this->Get('password') == $sOldPassword)
		{
			$this->Set('password', $sNewPassword);
			$oChange = MetaModel::NewObject("CMDBChange");
			$oChange->Set("date", time());
			if (UserRights::IsImpersonated())
			{
				$sUserString = Dict::Format('UI:Archive_User_OnBehalfOf_User', UserRights::GetRealUser(), UserRights::GetUser());
			}
			else
			{
				$sUserString = UserRights::GetUser();
			}
			$oChange->Set("userinfo", $sUserString);
			$this->DBUpdateTracked($oChange);
			return true;
		}
		return false;
	}
}


?>

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


/**
 * Authent Local
 * User authentication Module, password stored in the local database
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
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
			"reconc_keys" => array('login'),
			"db_table" => "priv_user_local",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeOneWayPassword("password", array("allowed_values"=>null, "sql"=>"pwd", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('contactid', 'first_name', 'email', 'login', 'password', 'language', 'profile_list', 'allowed_org_list')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('first_name', 'last_name', 'login')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('login', 'contactid')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('login', 'contactid')); // Criteria of the advanced search form
	}

	public function CheckCredentials($sPassword)
	{
		$oPassword = $this->Get('password'); // ormPassword object
		// Cannot compare directly the values since they are hashed, so
		// Let's ask the password to compare the hashed values
		if ($oPassword->CheckPassword($sPassword))
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
		if (MetaModel::GetConfig()->Get('demo_mode'))
		{
			return false;
		}
		return true;
	}

	public function ChangePassword($sOldPassword, $sNewPassword)
	{
		$oPassword = $this->Get('password'); // ormPassword object
		// Cannot compare directly the values since they are hashed, so
		// Let's ask the password to compare the hashed values
		if ($oPassword->CheckPassword($sOldPassword))
		{
			$this->SetPassword($sNewPassword);
			return true;
		}
		return false;
	}

	/**
	 * Use with care!
	 */	 	
	public function SetPassword($sNewPassword)
	{
		$this->Set('password', $sNewPassword);
		$oChange = MetaModel::NewObject("CMDBChange");
		$oChange->Set("date", time());
		$sUserString = CMDBChange::GetCurrentUserName();
		$oChange->Set("userinfo", $sUserString);
		$oChange->DBInsert();
		$this->DBUpdateTracked($oChange, true);
	}

	/**
	 * Returns the set of flags (OPT_ATT_HIDDEN, OPT_ATT_READONLY, OPT_ATT_MANDATORY...)
	 * for the given attribute in the current state of the object
	 * @param $sAttCode string $sAttCode The code of the attribute
	 * @param $aReasons array To store the reasons why the attribute is read-only (info about the synchro replicas)
	 * @param $sTargetState string The target state in which to evalutate the flags, if empty the current state will be used
	 * @return integer Flags: the binary combination of the flags applicable to this attribute
	 */	 	  	 	
	public function GetAttributeFlags($sAttCode, &$aReasons = array(), $sTargetState = '')
	{
		$iFlags = parent::GetAttributeFlags($sAttCode, $aReasons, $sTargetState);
		if (MetaModel::GetConfig()->Get('demo_mode'))
		{
			if (strpos('contactid,login,language,password,profile_list,allowed_org_list', $sAttCode) !== false)
			{
				// contactid and allowed_org_list are disabled to make sure the portal remains accessible 
				$aReasons[] = 'Sorry, this attribute is read-only in the demonstration mode!';
				$iFlags |= OPT_ATT_READONLY;
			}
		}
		return $iFlags;
	}
}


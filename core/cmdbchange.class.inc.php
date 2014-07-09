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
 * Persistent class (internal) cmdbChange
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


/**
 * A change as requested/validated at once by user, may groups many atomic changes 
 *
 * @package     iTopORM
 */
class CMDBChange extends DBObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "core/cmdb",
			"key_type" => "autoincrement",
			"name_attcode" => "date",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_change",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			'indexes' => array(
				array('origin'),
			)
		);
		MetaModel::Init_Params($aParams);
		//MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeDateTime("date", array("allowed_values"=>null, "sql"=>"date", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("userinfo", array("allowed_values"=>null, "sql"=>"userinfo", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("origin", array("allowed_values"=>new ValueSetEnum('interactive,csv-interactive,csv-import.php,webservice-soap,webservice-rest,synchro-data-source,email-processing,custom-extension'), "sql"=>"origin", "default_value"=>"interactive", "is_null_allowed"=>true, "depends_on"=>array())));
	}

	// Helper to keep track of the author of a given change,
	// taking into account a variety of cases (contact attached or not, impersonation)
	static public function GetCurrentUserName()
	{
		if (UserRights::IsImpersonated())
		{
			$sUserString = Dict::Format('UI:Archive_User_OnBehalfOf_User', UserRights::GetRealUserFriendlyName(), UserRights::GetUserFriendlyName());
		}
		else
		{
			$sUserString = UserRights::GetUserFriendlyName();
		}
		return $sUserString;
	}

	public function GetUserName()
	{
		if (preg_match('/^(.*)\\(CSV\\)$/i', $this->Get('userinfo'), $aMatches))
		{
			$sUser = $aMatches[1];
		}
		else
		{
			$sUser = $this->Get('userinfo');
		}
		return $sUser;
	}
}

?>

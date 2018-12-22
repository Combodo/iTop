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
 * Authent External
 * User authentication Module, for authentication outside of the iTop application
 * for example using a .htaccess file. The web server is in charge of authentifying the users
 * and providing the name (= 'login') of the authentified user in the $_SERVER['REMOTE_USER']
 * variable that is passed to PHP. iTop will not make any attempt to authentify such users.
 * Similarly once inside iTop, there is no way for the users to change their password or
 * log off from the iTop application, this has to be handled outside of iTop.
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


class UserExternal extends User
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "addon/authentication,grant_by_profile",
			"key_type" => "autoincrement",
			"name_attcode" => "login",
			"state_attcode" => "",
			"reconc_keys" => array('login'),
			"db_table" => "priv_user_external",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		
		// Add token attribute
		MetaModel::Init_AddAttribute(new AttributeOneWayPassword("token", array("allowed_values"=>null, "sql"=>"token", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('contactid', 'first_name', 'email', 'login', 'token', 'language', 'status', 'profile_list', 'allowed_org_list')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('first_name', 'last_name', 'login', 'status')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('login', 'contactid', 'status')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('login', 'contactid')); // Criteria of the advanced search form
	}

	/**
	 * Check if authentication is valid. iTop Administrator determines whether iTop simply accepts the username (ext_auth_variable) or if validation is still needed (ext_auth_token_variable). 
	 */
	public function CheckCredentials($sToken)
	{ 
		// If no ext_auth_token_variable is specified, always return 'true'
		// This means that only the name needs to be passed through (default behavior)
		if(MetaModel::GetConfig()->GetExternalAuthenticationTokenVariable() == '')
		{
			return true;
		}
		else 
		{
			$oPassword = $this->Get('token'); // ormPassword object
			// Cannot compare directly the values since they are hashed.
			// Let's use an internal function to check if the token is fine.
			if ($oPassword->CheckPassword($sToken))
			{
				return true;
			}
			return false; 
		}
	}

	public function TrustWebServerContext()
	{
		return true;
	}

	public function CanChangePassword()
	{
		// External authentication: iTop has no way to change a user's password
		return false;
	}

	public function ChangePassword($sOldPassword, $sNewPassword)
	{
		return false;
	}
}


?>

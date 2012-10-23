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
 * Authent LDAP
 * User authentication Module, no password at all!
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


class UserLDAP extends UserInternal
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
			"db_table" => "",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		// Display lists
		MetaModel::Init_SetZListItems('details', array('contactid', 'first_name', 'email', 'login', 'language', 'profile_list', 'allowed_org_list')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('first_name', 'last_name', 'login')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('login', 'contactid')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('login', 'contactid')); // Criteria of the advanced search form
	}

	/**
	 * Check the user's password against the LDAP server
	 * Algorithm:
	 * 1) Connect to the LDAP server, using a predefined account (or anonymously)
	 * 2) Search for the specified user, based on a specific search query/pattern
	 * 3) If exactly one user is found, continue, otherwise return false (wrong user or wrong query configured)
	 * 3) Bind again to LDAP using the DN of the found user and the password
	 * 4) If the bind is successful return true, otherwise return false (wrong password)
	 * @param string $sPassword The user's password to validate against the LDAP server
	 * @return boolean True if the password is Ok, false otherwise
	 */
	public function CheckCredentials($sPassword)
	{
		$sLDAPHost = MetaModel::GetModuleSetting('authent-ldap', 'host', 'localhost');
		$iLDAPPort = MetaModel::GetModuleSetting('authent-ldap', 'port', 389);
		
		$sDefaultLDAPUser = MetaModel::GetModuleSetting('authent-ldap', 'default_user', '');
		$sDefaultLDAPPwd = MetaModel::GetModuleSetting('authent-ldap', 'default_pwd', '');
		
		
		$hDS = @ldap_connect($sLDAPHost, $iLDAPPort);
		if ($hDS === false)
		{
			$this->LogMessage("ldap_authentication: can not connect to the LDAP server '$sLDAPHost' (port: $iLDAPPort). Check the configuration file config-itop.php.");
			return false;
		}
		$aOptions = MetaModel::GetModuleSetting('authent-ldap', 'options', array());
		foreach($aOptions as $name => $value)
		{
			ldap_set_option($hDS, $name, $value);
		}
				
		if ($bind = @ldap_bind($hDS, $sDefaultLDAPUser, $sDefaultLDAPPwd))
		{
			// Search for the person, using the specified query expression
			$sLDAPUserQuery = MetaModel::GetModuleSetting('authent-ldap', 'user_query', '');
			$sBaseDN = MetaModel::GetModuleSetting('authent-ldap', 'base_dn', '');
			
			$sLogin = $this->Get('login');
			$iContactId = $this->Get('contactid');
			$sFirstName = '';
			$sLastName = '';
			$sEMail = '';
			if ($iContactId > 0)
			{
				$oPerson = MetaModel::GetObject('Person', $iContactId);
				if (is_object($oPerson))
				{
					$sFirstName = $oPerson->Get('first_name');
					$sLastName = $oPerson->Get('name');
					$sEMail = $oPerson->Get('email');
				}
			}
			// %1$s => login
			// %2$s => first name
			// %3$s => last name			
			// %4$s => email
			$sQuery = sprintf($sLDAPUserQuery, $sLogin, $sFirstName, $sLastName, $sEMail);
			$hSearchResult = @ldap_search($hDS, $sBaseDN, $sQuery);

			$iCountEntries = ($hSearchResult !== false) ? @ldap_count_entries($hDS, $hSearchResult) : 0;
			switch($iCountEntries)
			{
				case 1:
				// Exactly one entry found, let's check the password by trying to bind with this user
				$aEntry = ldap_get_entries($hDS, $hSearchResult);
				$sUserDN = $aEntry[0]['dn'];
				$bUserBind =  @ldap_bind($hDS, $sUserDN, $sPassword);
				if (($bUserBind !== false) && !empty($sPassword))
				{
					ldap_unbind($hDS);
					return true; // Password Ok
				}
				$this->LogMessage("ldap_authentication: wrong password for user: '$sUserDN'.");
				return false; // Wrong password
				break;
				
				case 0:
				// User not found...
				$this->LogMessage("ldap_authentication: no entry found with the query '$sQuery', base_dn = '$sBaseDN'. User not found in LDAP.");
				break;
				
				default:
				// More than one entry... maybe the query is not specific enough...
				$this->LogMessage("ldap_authentication: several (".ldap_count_entries($hDS, $hSearchResult).") entries match the query '$sQuery', base_dn = '$sBaseDN', check that the query defined in config-itop.php is specific enough.");
			}
			return false;
		}
		else
		{
			// Trace: invalid default user for LDAP initial binding
			$this->LogMessage("ldap_authentication: can not bind to the LDAP server '$sLDAPHost' (port: $iLDAPPort), user='$sDefaultLDAPUser', pwd='$sDefaultLDAPPwd'. Error: '".ldap_error($hDS)."'. Check the configuration file config-itop.php.");
			return false;
		}
	}

	public function TrustWebServerContext()
	{
		return false;
	}

	public function CanChangePassword()
	{
		return false;
	}

	public function ChangePassword($sOldPassword, $sNewPassword)
	{
		return false;
	}
	
	protected function LogMessage($sMessage, $aData = array())
	{
		if (MetaModel::IsLogEnabledIssue())
		{
			if (MetaModel::IsValidClass('EventIssue'))
			{
				$oLog = new EventIssue();
	
				$oLog->Set('message', $sMessage);
				$oLog->Set('userinfo', '');
				$oLog->Set('issue', 'LDAP Authentication');
				$oLog->Set('impact', 'User login rejected');
				$oLog->Set('data', $aData);
				$oLog->DBInsertNoReload();
			}
	
			IssueLog::Error($sMessage);
		}		
	}
}


?>

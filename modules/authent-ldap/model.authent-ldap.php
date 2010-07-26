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
 * Authent LDAP
 * User authentication Module, no password at all!
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */


class UserLDAP extends User
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
			"db_table" => "",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		// Display lists
		MetaModel::Init_SetZListItems('details', array('contactid', 'first_name', 'email', 'login', 'language', 'profile_list')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('first_name', 'last_name', 'login')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('login', 'contactid')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('login', 'contactid')); // Criteria of the advanced search form
	}

	public function CheckCredentials($sPassword)
	{
		$aLDAPConfig['host'] = MetaModel::GetModuleSetting('authent-ldap', 'host', 'localhost');
		$aLDAPConfig['port'] = MetaModel::GetModuleSetting('authent-ldap', 'port', 389);
		$aLDAPConfig['basedn'] = MetaModel::GetModuleSetting('authent-ldap', 'basedn', 'dc=net');
		
		$ds = @ldap_connect($aLDAPConfig['host'], $aLDAPConfig['port']);
		ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
		ldap_set_option($ds, LDAP_OPT_REFERRALS, 0);
		
		$sDN = "uid=".$this->Get('login').",ou=people,".$aLDAPConfig['basedn'];
		
		if ($bind = @ldap_bind($ds, $sDN, $sPassword))
		{
			return true;
		}
		else
		{
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
}


?>

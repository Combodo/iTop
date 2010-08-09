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
 * Authent External
 * User authentication Module, for authentication outside of the iTop application
 * for example using a .htaccess file. The web server is in charge of authentifying the users
 * and providing the name (= 'login') of the authentified user in the $_SERVER['REMOTE_USER']
 * variable that is passed to PHP. iTop will not make any attempt to authentify such users.
 * Similarly once inside iTop, there is no way for the users to change their password or
 * log off from the iTop application, this has to be handled outside of iTop.
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */


class UserExternal extends User
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

	/**
	 * Check the user's password... always return true. Actually the password
	 * is not even passed to this function, we trust the web server for authentifiying
	 * the users
	 */
	public function CheckCredentials($sPassword)
	{
		// External authentication: for iTop it's always Ok
		return true;
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

	public function CanLogOff()
	{
		// External authentication: iTop has no way to force a log off
		return false;
	}

	public function ChangePassword($sOldPassword, $sNewPassword)
	{
		return false;
	}
}


?>

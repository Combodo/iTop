<?php
// Copyright (C) 2010-2024 Combodo SAS
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
 * Store and retrieve user's preferences (i.e persistent per user settings)
 *
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
require_once(APPROOT.'/core/dbobject.class.php');
require_once(APPROOT.'/core/userrights.class.inc.php');

/**
 * This class is used to store, in a persistent manner, user related settings (preferences)
 * For each user, one record in the database will be created, making their preferences permanent and storing a list
 * of properties (pairs of name/value strings)
 * This overcomes some limitations of cookies: limited number of cookies, maximum size, depends on the browser, etc..
 * This class is used in conjunction with the GetUserPreferences/SetUserPreferences javascript functions (utils.js)
 */
class appUserPreferences extends DBObject
{
	/** @var array Associative array of the prefs. of users: <USER_ID> => <PREFS> */
	private static $aUsersPrefs = []; // Local cache

	/**
	 * Get the value of the given property/preference
	 * If not set, the default value will be returned
	 *
	 * @param string $sCode Code/Name of the property to set
	 * @param mixed $defaultValue The default value
	 * @param string|null $sUserId Added in 3.0.0. ID of the user we want the pref. from, default is the current user
	 *
	 * @return mixed The value of the property for the current user
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 * @throws \OQLException
	 * @since 3.0.0 Added the $sUserId parameter
	 */
	public static function GetPref($sCode, $defaultValue, ?string $sUserId = null)
	{
		if (null === $sUserId) {
			$sUserId = UserRights::GetUserId();
		}

		if (false === array_key_exists($sUserId, self::$aUsersPrefs)) {
			self::Load($sUserId);
		}

		$aPrefs = self::$aUsersPrefs[$sUserId]->Get('preferences');
		if (array_key_exists($sCode, $aPrefs)) {
			return $aPrefs[$sCode];
		} else {
			return $defaultValue;
		}
	}

	/**
	 * Set the value for a given preference for the current user, and stores it into the database
	 *
	 * @param string $sCode Code/Name of the property/preference to set
	 * @param mixed $value Value to set
	 *
	 * @return void
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 */
	public static function SetPref($sCode, $value)
	{
		$sUserId = UserRights::GetUserId();
		if (false === array_key_exists($sUserId, self::$aUsersPrefs)) {
			self::Load($sUserId);
		}

		$aPrefs = self::$aUsersPrefs[$sUserId]->Get('preferences');
		if (array_key_exists($sCode, $aPrefs) && ($aPrefs[$sCode] === $value)) {
			// Do not write it again
		} else {
			$aPrefs[$sCode] = $value;
			self::$aUsersPrefs[$sUserId]->Set('preferences', $aPrefs);
			self::Save();
		}
	}

	/**
	 * Clears the value for a given preference (or list of preferences that matches a pattern) for the current user, and updates the database
	 *
	 * @param string $sCodeOrPattern Code/Pattern of the properties/preferences to reset
	 * @param boolean $bPattern Whether or not the supplied code is a PCRE pattern
	 *
	 * @return void
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 */
	public static function UnsetPref($sCodeOrPattern, $bPattern = false)
	{
		$sUserId = UserRights::GetUserId();
		if (false === array_key_exists($sUserId, self::$aUsersPrefs)) {
			self::Load($sUserId);
		}

		$aPrefs = self::$aUsersPrefs[$sUserId]->Get('preferences');
		if ($bPattern) {
			// the supplied code is a pattern, clear all preferences that match
			foreach ($aPrefs as $sKey => $void) {
				if (preg_match($sCodeOrPattern, $sKey)) {
					unset($aPrefs[$sKey]);
				}
			}
			self::$aUsersPrefs[$sUserId]->Set('preferences', $aPrefs);
		} else {
			unset($aPrefs[$sCodeOrPattern]);
			self::$aUsersPrefs[$sUserId]->Set('preferences', $aPrefs);
		}

		// Save only if needed
		if (self::$aUsersPrefs[$sUserId]->IsModified()) {
			self::Save();
		}
	}

	/**
	 * Call this function to get all the preferences for the current user, packed as a JSON object
	 *
	 * @return string JSON representation of the preferences
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 */
	public static function GetAsJSON()
	{
		$sUserId = UserRights::GetUserId();
		if (false === array_key_exists($sUserId, self::$aUsersPrefs)) {
			self::Load($sUserId);
		}

		$aPrefs = self::$aUsersPrefs[$sUserId]->Get('preferences');

		return json_encode($aPrefs);
	}

	/**
	 * Call this function if the user has changed (like when doing a logoff...)
	 *
	 * @return void
	 */
	public static function ResetPreferences()
	{
		self::$aUsersPrefs = [];
	}

	/**
	 * Call this function to ERASE all the preferences from the current user (only in memory, not in DB)
	 *
	 * @return void
	 */
	public static function ClearPreferences()
	{
		$sUserId = UserRights::GetUserId();
		unset(self::$aUsersPrefs[$sUserId]);
	}

	/**
	 * Save preferences of the current user in the DB, for now we don't allow interfering with an other users preferences
	 *
	 * @return void;
	 */
	protected static function Save()
	{
		$sUserId = UserRights::GetUserId();

		if (array_key_exists($sUserId, self::$aUsersPrefs)) {
			if (self::$aUsersPrefs[$sUserId]->IsModified()) {
				utils::PushArchiveMode(false);
				self::$aUsersPrefs[$sUserId]->DBUpdate();
				utils::PopArchiveMode();
			}
		}
	}

	/**
	 * Loads the preferences for the current user, creating the record in the database
	 * if needed
	 *
	 * @param string|null $sUserId Added in 3.0.0. ID of the user to load the prefs for, if null then current user will be used.
	 *
	 * @return void;
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 * @throws \OQLException
	 * @since 3.0.0 Added $sUserId parameter
	 */
	protected static function Load(?string $sUserId = null)
	{
		// Already in cache
		if (array_key_exists($sUserId, self::$aUsersPrefs)) {
			return;
		}

		if (null === $sUserId) {
			$sUserId = UserRights::GetUserId();
		}

		$oSearch = new DBObjectSearch('appUserPreferences');
		$oSearch->AddCondition('userid', $sUserId, '=');
		$oSet = new DBObjectSet($oSearch);
		$oObj = $oSet->Fetch();
		if ($oObj == null) {
			// No prefs (yet) for this user, create the object
			$oObj = new appUserPreferences();
			$oObj->Set('userid', $sUserId);
			$oObj->Set('preferences', array()); // Default preferences: an empty array
			try {
				utils::PushArchiveMode(false);
				$oObj->DBInsert();
				utils::PopArchiveMode();
			}
			catch (Exception $e) {
				// Ignore errors
			}
		}
		self::$aUsersPrefs[$sUserId] = $oObj;
	}

	/**
	 * @throws \CoreException
	 */
	public static function Init()
	{
		$aParams = array
		(
			"category" => "gui",
			"key_type" => "autoincrement",
			"name_attcode" => "userid",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_app_preferences",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
		);
		
		MetaModel::Init_Params($aParams);
		MetaModel::Init_AddAttribute(new AttributeExternalKey("userid", array("targetclass"=>"User", "allowed_values"=>null, "sql"=>"userid", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributePropertySet("preferences", array("allowed_values"=>null, "sql"=>"preferences", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
	}

	/**
	 * Overloading this function here to secure a fix done right before the release
	 * The real fix should be to implement this verb in DBObject
	 *
	 * @param \CMDBChange $oChange
	 * @param bool|null $bSkipStrongSecurity
	 * @param \DeletionPlan|null $oDeletionPlan
	 *
	 * @throws \ArchivedObjectException
	 * @throws \CoreCannotSaveObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \DeleteException
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 */
	public function DBDeleteTracked(CMDBChange $oChange, $bSkipStrongSecurity = null, &$oDeletionPlan = null)
	{
		utils::PushArchiveMode(false);
		$this->DBDelete($oDeletionPlan);
		utils::PopArchiveMode();
	}
}

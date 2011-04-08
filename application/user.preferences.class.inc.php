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
 * Store and retrieve user's preferences (i.e persistent per user settings)
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
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
	static $oUserPrefs = null; // Local cache
	
	/**
	 * Get the value of the given property/preference
	 * If not set, the default value will be returned
	 * @param string $sCode Code/Name of the property to set
	 * @param string $sDefaultValue The default value
	 * @return string The value of the property for the current user
	 */
	static function GetPref($sCode, $sDefaultValue)
	{
		if (self::$oUserPrefs == null)
		{
			self::Load();
		}
		$aPrefs = self::$oUserPrefs->Get('preferences');
		if (isset($aPrefs[$sCode]))
		{
			return $aPrefs[$sCode];
		}
		else
		{
			return $sDefaultValue;
		}
	}
	
	/**
	 * Set the value for a given preference, and stores it into the database
	 * @param string $sCode Code/Name of the property/preference to set
	 * @param string $sValue Value to set
	 */
	static function SetPref($sCode, $sValue)
	{
		if (self::$oUserPrefs == null)
		{
			self::Load();
		}
		$aPrefs = self::$oUserPrefs->Get('preferences');
		$aPrefs[$sCode] = $sValue;
		self::$oUserPrefs->Set('preferences', $aPrefs);
		self::Save();
	}
	
	/**
	 * Call this function to get all the preferences for the user, packed as a JSON object
	 * @return string JSON representation of the preferences
	 */
	static function GetAsJSON()
	{
		if (self::$oUserPrefs == null)
		{
			self::Load();
		}
		$aPrefs = self::$oUserPrefs->Get('preferences');
		return json_encode($aPrefs);
	}

	/**
	 * Call this function if the user has changed (like when doing a logoff...)
	 */
	static public function Reset()
	{
		self::$oUserPrefs = null;
	}
	/**
	 * Call this function to ERASE all the preferences from the current user
	 */
	static public function ClearPreferences()
	{
		self::$oUserPrefs = null;
	}
	
	static protected function Save()
	{
		if (self::$oUserPrefs != null)
		{
			if (self::$oUserPrefs->IsModified())
			{
				self::$oUserPrefs->DBUpdate();
			}
		}
	}
	
	/**
	 * Loads the preferences for the current user, creating the record in the database
	 * if needed
	 */
	static protected function Load()
	{
		if (self::$oUserPrefs != null) return;
		$oSearch = new DBObjectSearch('appUserPreferences');
		$oSearch->AddCondition('userid', UserRights::GetUserId(), '=');
		$oSet = new DBObjectSet($oSearch);
		$oObj = $oSet->Fetch();
		if ($oObj == null)
		{
			// No prefs (yet) for this user, create the object
			$oObj = new appUserPreferences();
			$oObj->Set('userid', UserRights::GetUserId());
			$oObj->Set('preferences', array()); // Default preferences: an empty array
			try
			{
				$oObj->DBInsert();
			}
			catch(Exception $e)
			{
				// Ignore errors
			}
		}
		self::$oUserPrefs = $oObj;
	}

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
	*/
	public function DBDeleteTracked(CMDBChange $oChange, $bSkipStrongSecurity = null, &$oDeletionPlan = null)
	{
		$this->DBDelete($oDeletionPlan);
	}
}
?>

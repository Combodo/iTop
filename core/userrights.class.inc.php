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
 * User rights management API
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */


class UserRightException extends CoreException
{
}


define('UR_ALLOWED_NO', 0);
define('UR_ALLOWED_YES', 1);
define('UR_ALLOWED_DEPENDS', 2);

define('UR_ACTION_READ', 1); // View an object
define('UR_ACTION_MODIFY', 2); // Create/modify an object/attribute
define('UR_ACTION_DELETE', 3); // Delete an object

define('UR_ACTION_BULK_READ', 4); // Export multiple objects
define('UR_ACTION_BULK_MODIFY', 5); // Create/modify multiple objects
define('UR_ACTION_BULK_DELETE', 6); // Delete multiple objects

define('UR_ACTION_APPLICATION_DEFINED', 10000); // Application specific actions (CSV import, View schema...)

/**
 * User management module API  
 *
 * @package     iTopORM
 */
abstract class UserRightsAddOnAPI
{
	abstract public function Setup(); // initial installation
	abstract public function CreateAdministrator($sAdminUser, $sAdminPwd, $sLanguage = 'EN US'); // could be used during initial installation

	abstract public function Init(); // loads data (possible optimizations)
	abstract public function CheckCredentials($sLogin, $sPassword); // returns the id of the user or false
	abstract public function CanChangePassword(); // Whether or not a user can change her/his own password
	abstract public function ChangePassword($iUserId, $sOldPassword, $sNewPassword); // Change the password of the specified user
	abstract public function GetUserLanguage($sLogin); // returns the language code (e.g "EN US")
	abstract public function GetUserId($sLogin); // returns the id of the user or false
	abstract public function GetContactId($sLogin); // returns the id of the "business" user or false
	abstract public function GetFilter($sLogin, $sClass); // returns a filter object
	abstract public function IsActionAllowed($iUserId, $sClass, $iActionCode, /*dbObjectSet*/ $oInstanceSet = null);
	abstract public function IsStimulusAllowed($iUserId, $sClass, $sStimulusCode, /*dbObjectSet*/ $oInstanceSet = null);
	abstract public function IsActionAllowedOnAttribute($iUserId, $sClass, $sAttCode, $iActionCode, /*dbObjectSet*/ $oInstanceSet = null);
	abstract public function IsAdministrator($iUserId);
	abstract public function FlushPrivileges();
}



/**
 * User management core API  
 *
 * @package     iTopORM
 */
class UserRights
{
	protected static $m_oAddOn;
	protected static $m_sUser;
	protected static $m_sRealUser;
	protected static $m_iUserId;
	protected static $m_iRealUserId;
	protected static $m_sUserLanguage;

	public static function SelectModule($sModuleName)
	{
		if (!class_exists($sModuleName))
		{
			throw new CoreException("Could not select this module, '$sModuleName' in not a valid class name");
			return;
		}
		if (!is_subclass_of($sModuleName, 'UserRightsAddOnAPI'))
		{
			throw new CoreException("Could not select this module, the class '$sModuleName' is not derived from UserRightsAddOnAPI");
			return;
		}
		self::$m_oAddOn = new $sModuleName;
		self::$m_oAddOn->Init();
		self::$m_sUser = '';
		self::$m_sRealUser = '';
		self::$m_iUserId = 0;
		self::$m_iRealUserId = 0;
		self::$m_sUserLanguage = 'EN US';
	}

	public static function GetModuleInstance()
	{
		return self::$m_oAddOn;
	}

	// Installation: create the very first user
	public static function CreateAdministrator($sAdminUser, $sAdminPwd, $sLanguage = 'EN US')
	{
		return self::$m_oAddOn->CreateAdministrator($sAdminUser, $sAdminPwd, $sLanguage);
	}
	
	// Installation (e.g: give default values for users)
	public static function Setup()
	{
		// to be discussed...
		return self::$m_oAddOn->Setup();
	}

	protected static function IsLoggedIn()
	{
		return (!empty(self::$m_sUser));
	}

	public static function Login($sName, $sPassword)
	{
		self::$m_iUserId = self::$m_oAddOn->CheckCredentials($sName, $sPassword);
		if (self::$m_iUserId !== false)
		{
			self::$m_sUser = $sName;
			self::$m_iRealUserId = self::$m_iUserId;
			self::$m_sRealUser = $sName;
			self::$m_sUserLanguage = self::$m_oAddOn->GetUserLanguage($sName);
			Dict::SetUserLanguage(self::GetUserLanguage());
			return true;
		}
		else
		{
			return false;
		}
	}

	public static function CanChangePassword()
	{
		if (!is_null(self::$m_iUserId))
		{
 			return self::$m_oAddOn->CanChangePassword(self::$m_iUserId);
		}
		else
		{
			return false;
		}
	}

	public static function ChangePassword($sCurrentPassword, $sNewPassword)
	{
		if (!is_null(self::$m_iUserId))
		{
 			return self::$m_oAddOn->ChangePassword(self::$m_iUserId, $sCurrentPassword, $sNewPassword);
		}
		else
		{
			return false;
		}
	}

	public static function Impersonate($sName, $sPassword)
	{
		if (!self::CheckLogin()) return false;

		self::$m_iRealUserId = self::$m_oAddOn->CheckCredentials($sName, $sPassword);
		if (self::$m_iRealUserId !== false)
		{
			self::$m_sUser = $sName;
			self::$m_sUserLanguage = self::$m_oAddOn->GetUserLanguage($sName);
			Dict::SetUserLanguage(self::GetUserLanguage());
			return true;
		}
		else
		{
			return false;
		}
	}

	public static function GetUser()
	{
		return self::$m_sUser;
	}

	public static function GetUserLanguage()
	{
		return self::$m_sUserLanguage;
	}

	public static function GetUserId($sName = '')
	{
		if (empty($sName))
		{
			// return current user id
			return self::$m_iUserId;
		}
		else
		{
			// find the id out of the login string
			return self::$m_oAddOn->GetUserId($sName);
		}
	}

	public static function GetContactId($sName = '')
	{
		// note: returns null if the user management module is not related to the business data model
		if (empty($sName))
		{
			$sName = self::$m_sUser;
		}
		return self::$m_oAddOn->GetContactId($sName);
	}

	public static function GetRealUser()
	{
		return self::$m_sRealUser;
	}

	public static function GetRealUserId()
	{
		return self::$m_iRealUserId;
	}

	protected static function CheckLogin()
	{
		if (!self::IsLoggedIn())
		{
			//throw new UserRightException('No user logged in', array());	
			return false;
		}
		return true;
	}


	public static function GetFilter($sClass)
	{
		if (!self::CheckLogin()) return false;
		if (self::IsAdministrator()) return new DBObjectSearch($sClass);

		// this module is forbidden for non admins
		if (MetaModel::HasCategory($sClass, 'addon/userrights')) return false;

		// the rest is allowed (#@# to be improved)
		if (!MetaModel::HasCategory($sClass, 'bizmodel')) return new DBObjectSearch($sClass);

		return self::$m_oAddOn->GetFilter(self::$m_iUserId, $sClass);
	}

	public static function IsActionAllowed($sClass, $iActionCode, /*dbObjectSet*/ $oInstanceSet = null, $iUserId = null)
	{
		if (!self::CheckLogin()) return false;
		if (self::IsAdministrator($iUserId)) return true;

		// this module is forbidden for non admins
		if (MetaModel::HasCategory($sClass, 'addon/userrights')) return false;

		// the rest is allowed (#@# to be improved)
		if (!MetaModel::HasCategory($sClass, 'bizmodel')) return true;

		if (is_null($iUserId))
		{
			return self::$m_oAddOn->IsActionAllowed(self::$m_iUserId, $sClass, $iActionCode, $oInstanceSet);
		}
		else
		{
			return self::$m_oAddOn->IsActionAllowed($iUserId, $sClass, $iActionCode, $oInstanceSet);
		}
	}

	public static function IsStimulusAllowed($sClass, $sStimulusCode, /*dbObjectSet*/ $oInstanceSet = null, $iUserId = null)
	{
		if (!self::CheckLogin()) return false;
		if (self::IsAdministrator($iUserId)) return true;

		// this module is forbidden for non admins
		if (MetaModel::HasCategory($sClass, 'addon/userrights')) return false;

		// the rest is allowed (#@# to be improved)
		if (!MetaModel::HasCategory($sClass, 'bizmodel')) return true;

		if (is_null($iUserId))
		{
			return self::$m_oAddOn->IsStimulusAllowed(self::$m_iUserId, $sClass, $sStimulusCode, $oInstanceSet);
		}
		else
		{
			return self::$m_oAddOn->IsStimulusAllowed($iUserId, $sClass, $sStimulusCode, $oInstanceSet);
		}
	}

	public static function IsActionAllowedOnAttribute($sClass, $sAttCode, $iActionCode, /*dbObjectSet*/ $oInstanceSet = null, $iUserId = null)
	{
		if (!self::CheckLogin()) return false;
		if (self::IsAdministrator($iUserId)) return true;

		// this module is forbidden for non admins
		if (MetaModel::HasCategory($sClass, 'addon/userrights')) return false;

		// the rest is allowed (#@# to be improved)
		if (!MetaModel::HasCategory($sClass, 'bizmodel')) return true;

		if (is_null($iUserId))
		{
			return self::$m_oAddOn->IsActionAllowedOnAttribute(self::$m_iUserId, $sClass, $sAttCode, $iActionCode, $oInstanceSet);
		}
		else
		{
			return self::$m_oAddOn->IsActionAllowedOnAttribute($iUserId, $sClass, $sAttCode, $iActionCode, $oInstanceSet);
		}
	}

	public static function IsAdministrator($iUserId = null)
	{
		if (!self::CheckLogin()) return false;

		if (is_null($iUserId))
		{
			return self::$m_oAddOn->IsAdministrator(self::$m_iUserId);
		}
		else
		{
			return self::$m_oAddOn->IsAdministrator($iUserId);
		}
	}

	public static function FlushPrivileges()
	{
		return self::$m_oAddOn->FlushPrivileges();
	}

}


?>

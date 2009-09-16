<?php

/**
 * UserRights
 * User management API 
 *
 * @package     iTopORM
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @author      Denis Flaven <denisflave@free.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     1.1.1.1 $
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
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     $itopversion$
 */
abstract class UserRightsAddOnAPI
{
	abstract public function Setup(); // initial installation
	abstract public function CreateAdministrator($sAdminUser, $sAdminPwd); // could be used during initial installation

	abstract public function Init(); // loads data (possible optimizations)
	abstract public function CheckCredentials($sLogin, $sPassword); // returns the id of the user or false
	abstract public function GetUserId($sLogin); // returns the id of the user or false
	abstract public function GetContactId($sLogin); // returns the id of the "business" user or false
	abstract public function GetFilter($sLogin, $sClass); // returns a filter object
	abstract public function IsActionAllowed($iUserId, $sClass, $iActionCode, /*dbObjectSet*/ $oInstanceSet = null);
	abstract public function IsStimulusAllowed($iUserId, $sClass, $sStimulusCode, /*dbObjectSet*/ $oInstanceSet = null);
	abstract public function IsActionAllowedOnAttribute($iUserId, $sClass, $sAttCode, $iActionCode, /*dbObjectSet*/ $oInstanceSet = null);
	abstract public function IsAdministrator($iUserId);
}



/**
 * User management core API  
 *
 * @package     iTopORM
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     $itopversion$
 */
class UserRights
{
	protected static $m_oAddOn;
	protected static $m_sUser;
	protected static $m_sRealUser;
	protected static $m_iUserId;
	protected static $m_iRealUserId;

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
	}

	public static function GetModuleInstance()
	{
		return self::$m_oAddOn;
	}

	// Installation: create the very first user
	public static function CreateAdministrator($sAdminUser, $sAdminPwd)
	{
		return self::$m_oAddOn->CreateAdministrator($sAdminUser, $sAdminPwd);
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
		if ( self::$m_iUserId !== false )
		{
			self::$m_sUser = $sName;
			self::$m_iRealUserId = self::$m_iUserId;
			self::$m_sRealUser = $sName;
			return true;
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
		if ( self::$m_iRealUserId !== false)
		{
			self::$m_sUser = $sName;
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
		if (!MetaModel::HasCategory($sClass, 'bizmodel')) return new DBObjectSearch($sClass);
		if (!self::CheckLogin()) return false;

		return self::$m_oAddOn->GetFilter(self::$m_iUserId, $sClass);
	}

	public static function IsActionAllowed($sClass, $iActionCode, /*dbObjectSet*/ $oInstanceSet = null, $iUserId = null)
	{
		if (!MetaModel::HasCategory($sClass, 'bizmodel')) return true;
		if (!self::CheckLogin()) return false;

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
		if (!MetaModel::HasCategory($sClass, 'bizmodel')) return true;
		if (!self::CheckLogin()) return false;

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
		if (!MetaModel::HasCategory($sClass, 'bizmodel')) return true;
		if (!self::CheckLogin()) return false;

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
}


?>

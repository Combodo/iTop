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
	abstract public function CreateAdministrator($sAdminUser, $sAdminPwd, $sLanguage = 'EN US'); // could be used during initial installation

	abstract public function Init(); // loads data (possible optimizations)

	// Used to build select queries showing only objects visible for the given user
	abstract public function GetSelectFilter($sLogin, $sClass); // returns a filter object

	abstract public function IsActionAllowed($oUser, $sClass, $iActionCode, /*dbObjectSet*/ $oInstanceSet = null);
	abstract public function IsStimulusAllowed($oUser, $sClass, $sStimulusCode, /*dbObjectSet*/ $oInstanceSet = null);
	abstract public function IsActionAllowedOnAttribute($oUser, $sClass, $sAttCode, $iActionCode, /*dbObjectSet*/ $oInstanceSet = null);
	abstract public function IsAdministrator($oUser);
	abstract public function IsPortalUser($oUser);
	abstract public function FlushPrivileges();
}


abstract class User extends cmdbAbstractObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "core",
			"key_type" => "autoincrement",
			"name_attcode" => "login",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_user",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		//MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeExternalKey("contactid", array("targetclass"=>"Person", "allowed_values"=>null, "sql"=>"contactid", "is_null_allowed"=>true, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("last_name", array("allowed_values"=>null, "extkey_attcode"=> 'contactid', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeExternalField("first_name", array("allowed_values"=>null, "extkey_attcode"=> 'contactid', "target_attcode"=>"first_name")));
		MetaModel::Init_AddAttribute(new AttributeExternalField("email", array("allowed_values"=>null, "extkey_attcode"=> 'contactid', "target_attcode"=>"email")));

		MetaModel::Init_AddAttribute(new AttributeString("login", array("allowed_values"=>null, "sql"=>"login", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));

		MetaModel::Init_AddAttribute(new AttributeApplicationLanguage("language", array("sql"=>"language", "default_value"=>"EN US", "is_null_allowed"=>false, "depends_on"=>array())));

		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("profile_list", array("linked_class"=>"URP_UserProfile", "ext_key_to_me"=>"userid", "ext_key_to_remote"=>"profileid", "allowed_values"=>null, "count_min"=>1, "count_max"=>0, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("allowed_org_list", array("linked_class"=>"URP_UserOrg", "ext_key_to_me"=>"userid", "ext_key_to_remote"=>"allowed_org_id", "allowed_values"=>null, "count_min"=>1, "count_max"=>0, "depends_on"=>array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('contactid', 'first_name', 'email', 'login', 'language', 'profile_list', 'allowed_org_list')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('finalclass', 'first_name', 'last_name', 'login')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('login', 'contactid')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('login', 'contactid')); // Criteria of the advanced search form
	}

	abstract public function CheckCredentials($sPassword);
	abstract public function TrustWebServerContext();
	abstract public function CanChangePassword();
	abstract public function ChangePassword($sOldPassword, $sNewPassword);

	/*
	* Overload the standard behavior
	*/	
	public function DoCheckToWrite()
	{
		parent::DoCheckToWrite();

		// Note: This MUST be factorized later: declare unique keys (set of columns) in the data model
		$aChanges = $this->ListChanges();
		if (array_key_exists('login', $aChanges))
		{
			$sNewLogin = $aChanges['login'];
			$oSearch = DBObjectSearch::FromOQL_AllData("SELECT User WHERE login = :newlogin");
			$oSet = new DBObjectSet($oSearch, array(), array('newlogin' => $sNewLogin));
			if ($oSet->Count() > 0)
			{
				$this->m_aCheckIssues[] = Dict::Format('Class:User/Error:LoginMustBeUnique', $sNewLogin);
			}
		}
		// Check that this user has at least one profile assigned
		$oSet = $this->Get('profile_list');
		$aProfileLinks = $oSet->ToArray();
		if (count($aProfileLinks) == 0)
		{
			$this->m_aCheckIssues[] = Dict::Format('Class:User/Error:AtLeastOneProfileIsNeeded');
		}
		
	}

	function GetGrantAsHtml($sClass, $iAction)
	{
		if (UserRights::IsActionAllowed($sClass, $iAction, null, $this)) 
		{
			return '<span style="background-color: #ddffdd;">'.Dict::S('UI:UserManagement:ActionAllowed:Yes').'</span>';
		}
		else
		{
			return '<span style="background-color: #ffdddd;">'.Dict::S('UI:UserManagement:ActionAllowed:No').'</span>';
		}
	}
	
	function DoShowGrantSumary($oPage, $sClassCategory)
	{
		if (UserRights::IsAdministrator($this))
		{
			// Looks dirty, but ok that's THE ONE
			$oPage->p(Dict::S('UI:UserManagement:AdminProfile+'));
			return;
		}

		$oKPI = new ExecutionKPI();

		$aDisplayData = array();
		foreach (MetaModel::GetClasses($sClassCategory) as $sClass)
		{
			$aClassStimuli = MetaModel::EnumStimuli($sClass);
			if (count($aClassStimuli) > 0)
			{
				$aStimuli = array();
				foreach ($aClassStimuli as $sStimulusCode => $oStimulus)
				{
					if (UserRights::IsStimulusAllowed($sClass, $sStimulusCode, null, $this))
					{
						$aStimuli[] = '<span title="'.$sStimulusCode.': '.htmlentities($oStimulus->GetDescription()).'">'.htmlentities($oStimulus->GetLabel()).'</span>';
					}
				}
				$sStimuli = implode(', ', $aStimuli);
			}
			else
			{
				$sStimuli = '<em title="'.Dict::S('UI:UserManagement:NoLifeCycleApplicable+').'">'.Dict::S('UI:UserManagement:NoLifeCycleApplicable').'</em>';
			}
			
			$aDisplayData[] = array(
				'class' => MetaModel::GetName($sClass),
				'read' => $this->GetGrantAsHtml($sClass, UR_ACTION_READ),
				'bulkread' => $this->GetGrantAsHtml($sClass, UR_ACTION_BULK_READ),
				'write' => $this->GetGrantAsHtml($sClass, UR_ACTION_MODIFY),
				'bulkwrite' => $this->GetGrantAsHtml($sClass, UR_ACTION_BULK_MODIFY),
				'stimuli' => $sStimuli,
			);
		}

		$oKPI->ComputeAndReport('Computation of user rights');
	
		$aDisplayConfig = array();
		$aDisplayConfig['class'] = array('label' => Dict::S('UI:UserManagement:Class'), 'description' => Dict::S('UI:UserManagement:Class+'));
		$aDisplayConfig['read'] = array('label' => Dict::S('UI:UserManagement:Action:Read'), 'description' => Dict::S('UI:UserManagement:Action:Read+'));
		$aDisplayConfig['bulkread'] = array('label' => Dict::S('UI:UserManagement:Action:BulkRead'), 'description' => Dict::S('UI:UserManagement:Action:BulkRead+'));
		$aDisplayConfig['write'] = array('label' => Dict::S('UI:UserManagement:Action:Modify'), 'description' => Dict::S('UI:UserManagement:Action:Modify+'));
		$aDisplayConfig['bulkwrite'] = array('label' => Dict::S('UI:UserManagement:Action:BulkModify'), 'description' => Dict::S('UI:UserManagement:Action:BulkModify+'));
		$aDisplayConfig['stimuli'] = array('label' => Dict::S('UI:UserManagement:Action:Stimuli'), 'description' => Dict::S('UI:UserManagement:Action:Stimuli+'));
		$oPage->table($aDisplayConfig, $aDisplayData);
	}

	function DisplayBareRelations(WebPage $oPage, $bEditMode = false)
	{
		parent::DisplayBareRelations($oPage, $bEditMode);
		if (!$bEditMode)
		{
			$oPage->SetCurrentTab(Dict::S('UI:UserManagement:GrantMatrix'));
			$this->DoShowGrantSumary($oPage, 'bizmodel');
	
			// debug
			if (false)
			{
				$oPage->SetCurrentTab('More on user rigths (dev only)');
				$oPage->add("<h3>User rights</h3>\n");
				$this->DoShowGrantSumary($oPage, 'addon/userrights');
				$oPage->add("<h3>Change log</h3>\n");
				$this->DoShowGrantSumary($oPage, 'core/cmdb');
				$oPage->add("<h3>Application</h3>\n");
				$this->DoShowGrantSumary($oPage, 'application');
				$oPage->add("<h3>GUI</h3>\n");
				$this->DoShowGrantSumary($oPage, 'gui');
				
			}					
		}
	}
}

/**
 * Abstract class for all types of "internal" authentication i.e. users
 * for which the application is supplied a login and a password opposed
 * to "external" users for whom the authentication is performed outside
 * of the application (by the web server for example).
 * Note that "internal" users do not necessary correspond to a local authentication
 * they may be authenticated by a remote system, like in authent-ldap.
 */
abstract class UserInternal extends User
{
	// Nothing special, just a base class to categorize this type of authenticated users	
	public static function Init()
	{
		$aParams = array
		(
			"category" => "core",
			"key_type" => "autoincrement",
			"name_attcode" => "login",
			"state_attcode" => "",
			"reconc_keys" => array('login'),
			"db_table" => "priv_internalUser",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		// Display lists
		MetaModel::Init_SetZListItems('details', array('contactid', 'first_name', 'email', 'login', 'language', 'profile_list', 'allowed_org_list')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('finalclass', 'first_name', 'last_name', 'login')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('login', 'contactid')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('login', 'contactid')); // Criteria of the advanced search form
	}
}

/**
 * User management core API  
 *
 * @package     iTopORM
 */
class UserRights
{
	protected static $m_oAddOn;
	protected static $m_oUser;
	protected static $m_oRealUser;

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
		self::$m_oUser = null;
		self::$m_oRealUser = null;
	}

	public static function GetModuleInstance()
	{
		return self::$m_oAddOn;
	}

	// Installation: create the very first user
	public static function CreateAdministrator($sAdminUser, $sAdminPwd, $sLanguage = 'EN US')
	{
		$bRes = self::$m_oAddOn->CreateAdministrator($sAdminUser, $sAdminPwd, $sLanguage);
		self::FlushPrivileges(true /* reset admin cache */);
		return $bRes;
	}
	
	protected static function IsLoggedIn()
	{
		if (self::$m_oUser == null)
		{
			return false;
		}
		else
		{
			return true;
		}	
	}

	public static function Login($sName, $sAuthentication = 'any')
	{
		$oUser = self::FindUser($sName, $sAuthentication);
		if (is_null($oUser))
		{
			return false;
		}
		self::$m_oUser = $oUser;
		Dict::SetUserLanguage(self::GetUserLanguage());
		return true;
	}

	public static function CheckCredentials($sName, $sPassword, $sAuthentication = 'any')
	{
		$oUser = self::FindUser($sName, $sAuthentication);
		if (is_null($oUser))
		{
			return false;
		}

		if (!$oUser->CheckCredentials($sPassword))
		{
			return false;
		}
		return true;
	}

	public static function TrustWebServerContext()
	{
		if (!is_null(self::$m_oUser))
		{
 			return self::$m_oUser->TrustWebServerContext();
		}
		else
		{
			return false;
		}
	}

	public static function CanChangePassword()
	{
		if (MetaModel::DBIsReadOnly())
		{
			return false;
		}

		if (!is_null(self::$m_oUser))
		{
 			return self::$m_oUser->CanChangePassword();
		}
		else
		{
			return false;
		}
	}

	public static function CanLogOff()
	{
		if (!is_null(self::$m_oUser))
		{
 			return self::$m_oUser->CanLogOff();
		}
		else
		{
			return false;
		}
	}

	public static function ChangePassword($sCurrentPassword, $sNewPassword)
	{
		if (!is_null(self::$m_oUser))
		{
 			return self::$m_oUser->ChangePassword($sCurrentPassword, $sNewPassword);
		}
		else
		{
			return false;
		}
	}

	public static function Impersonate($sName, $sPassword)
	{
		if (!self::CheckLogin()) return false;

		$oUser = self::FindUser($sName);
		if (is_null($oUser))
		{
			return false;
		}
		if (!$oUser->CheckCredentials($sPassword))
		{
			return false;
		}

		self::$m_oRealUser = self::$m_oUser;
		self::$m_oUser = $oUser;
		Dict::SetUserLanguage(self::GetUserLanguage());
		return true;
	}

	public static function GetUser()
	{
		if (is_null(self::$m_oUser))
		{
			return '';
		}
		else
		{
			return self::$m_oUser->Get('login');
		}
	}

	public static function GetUserLanguage()
	{
		if (is_null(self::$m_oUser))
		{
			return 'EN US';
		
		}
		else
		{
			return self::$m_oUser->Get('language');
		}
	}

	public static function GetUserId($sName = '')
	{
		if (empty($sName))
		{
			// return current user id
			if (is_null(self::$m_oUser))
			{
				return null;
			}
			return self::$m_oUser->GetKey();
		}
		else
		{
			// find the id out of the login string
			$oUser = self::$m_oAddOn->FindUser($sName);
			if (is_null($oUser))
			{
				return null;
			}
			return $oUser->GetKey();
		}
	}

	public static function GetContactId($sName = '')
	{
		if (empty($sName))
		{
			$oUser = self::$m_oUser;
		}
		else
		{
			$oUser = FindUser($sName);
		}
		if (is_null($oUser))
		{
			return '';
		}
		if (!MetaModel::IsValidAttCode(get_class($oUser), 'contactid'))
		{
			return '';
		}
		return $oUser->Get('contactid');
	}

	public static function IsImpersonated()
	{
		if (is_null(self::$m_oRealUser))
		{
			return false;
		}
		return true;
	}

	public static function GetRealUser()
	{
		if (is_null(self::$m_oRealUser))
		{
			return '';
		}
		return self::$m_oRealUser->Get('login');
	}

	public static function GetRealUserId()
	{
		if (is_null(self::$m_oRealUser))
		{
			return '';
		}
		return self::$m_oRealUser->GetKey();
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

	public static function GetSelectFilter($sClass)
	{
		// When initializing, we need to let everything pass trough
		if (!self::CheckLogin()) return true;

		if (self::IsAdministrator()) return true;
		// Portal users actions are limited by the portal page...
		if (self::IsPortalUser()) return true;

		if (MetaModel::HasCategory($sClass, 'bizmodel'))
		{
			return self::$m_oAddOn->GetSelectFilter(self::$m_oUser, $sClass);
		}
		else
		{
			return true;
		}
	}

	public static function IsActionAllowed($sClass, $iActionCode, /*dbObjectSet*/ $oInstanceSet = null, $oUser = null)
	{
		// When initializing, we need to let everything pass trough
		if (!self::CheckLogin()) return true;

		if (self::IsAdministrator($oUser)) return true;

		if (MetaModel::DBIsReadOnly())
		{
			if ($iActionCode == UR_ACTION_MODIFY) return false;
			if ($iActionCode == UR_ACTION_DELETE) return false;
			if ($iActionCode == UR_ACTION_BULK_MODIFY) return false;
			if ($iActionCode == UR_ACTION_BULK_DELETE) return false;
		}

		if (MetaModel::HasCategory($sClass, 'bizmodel'))
		{
			// #@# Temporary?????
			// The read access is controlled in MetaModel::MakeSelectQuery()
			if ($iActionCode == UR_ACTION_READ) return true;

			if (is_null($oUser))
			{
				$oUser = self::$m_oUser;
			}
			return self::$m_oAddOn->IsActionAllowed($oUser, $sClass, $iActionCode, $oInstanceSet);
		}
		elseif(($iActionCode == UR_ACTION_READ) && MetaModel::HasCategory($sClass, 'view_in_gui'))
		{
			return true;
		}
		else
		{
			// Other classes could be edited/listed by the administrators
			return false;
		}
	}

	public static function IsStimulusAllowed($sClass, $sStimulusCode, /*dbObjectSet*/ $oInstanceSet = null, $oUser = null)
	{
		// When initializing, we need to let everything pass trough
		if (!self::CheckLogin()) return true;

		if (self::IsAdministrator($oUser)) return true;

		if (MetaModel::DBIsReadOnly())
		{
			if ($iActionCode == UR_ACTION_MODIFY) return false;
			if ($iActionCode == UR_ACTION_DELETE) return false;
			if ($iActionCode == UR_ACTION_BULK_MODIFY) return false;
			if ($iActionCode == UR_ACTION_BULK_DELETE) return false;
		}

		if (MetaModel::HasCategory($sClass, 'bizmodel'))
		{
			if (is_null($oUser))
			{
				$oUser = self::$m_oUser;
			}
			return self::$m_oAddOn->IsStimulusAllowed($oUser, $sClass, $sStimulusCode, $oInstanceSet);
		}
		else
		{
			// Other classes could be edited/listed by the administrators
			return false;
		}
	}

	public static function IsActionAllowedOnAttribute($sClass, $sAttCode, $iActionCode, /*dbObjectSet*/ $oInstanceSet = null, $oUser = null)
	{
		// When initializing, we need to let everything pass trough
		if (!self::CheckLogin()) return true;

		if (self::IsAdministrator($oUser)) return true;

		if (MetaModel::DBIsReadOnly())
		{
			if ($iActionCode == UR_ACTION_MODIFY) return false;
			if ($iActionCode == UR_ACTION_DELETE) return false;
			if ($iActionCode == UR_ACTION_BULK_MODIFY) return false;
			if ($iActionCode == UR_ACTION_BULK_DELETE) return false;
		}

		// this module is forbidden for non admins
		if (MetaModel::HasCategory($sClass, 'addon/userrights')) return false;

		// the rest is allowed (#@# to be improved)
		if (!MetaModel::HasCategory($sClass, 'bizmodel')) return true;

		if (is_null($oUser))
		{
			$oUser = self::$m_oUser;
		}
		return self::$m_oAddOn->IsActionAllowedOnAttribute($oUser, $sClass, $sAttCode, $iActionCode, $oInstanceSet);
	}

	static $m_aAdmins = array();
	public static function IsAdministrator($oUser = null)
	{
		if (!self::CheckLogin()) return false;

		if (is_null($oUser))
		{
			$oUser = self::$m_oUser;
		}
		$iUser = $oUser->GetKey();
		if (!isset(self::$m_aAdmins[$iUser]))
		{
			self::$m_aAdmins[$iUser] = self::$m_oAddOn->IsAdministrator($oUser);
		}
		return self::$m_aAdmins[$iUser];
	}

	static $m_aPortalUsers = array();
	public static function IsPortalUser($oUser = null)
	{
		if (!self::CheckLogin()) return false;

		if (is_null($oUser))
		{
			$oUser = self::$m_oUser;
		}
		$iUser = $oUser->GetKey();
		if (!isset(self::$m_aPortalUsers[$iUser]))
		{
			self::$m_aPortalUsers[$iUser] = self::$m_oAddOn->IsPortalUser($oUser);
		}
		return self::$m_aPortalUsers[$iUser];
	}

	/**
	 * Reset cached data
	 * @param Bool Reset admin cache as well
	 * @return void
	 */
	// Reset cached data
	//
	public static function FlushPrivileges($bResetAdminCache = false)
	{
		if ($bResetAdminCache)
		{
			self::$m_aAdmins = array();
		}
		return self::$m_oAddOn->FlushPrivileges();
	}

	static $m_aCacheUsers;
	/**
	 * Find a user based on its login and its type of authentication
	 * @param string $sLogin Login/identifier of the user
	 * @param string $sAuthentication Type of authentication used: internal|external|any
	 * @return User The found user or null
	 */
	protected static function FindUser($sLogin, $sAuthentication = 'any')
	{
		if ($sAuthentication == 'any')
		{
			$oUser = self::FindUser($sLogin, 'internal');
			if ($oUser == null)
			{
				$oUser = self::FindUser($sLogin, 'external');
			}
		}
		else
		{
			if (!isset(self::$m_aCacheUsers))
			{
				self::$m_aCacheUsers = array('internal' => array(), 'external' => array());
			}
			
			if (!isset(self::$m_aCacheUsers[$sAuthentication][$sLogin]))
			{
				switch($sAuthentication)
				{
					case 'external':
					$sBaseClass = 'UserExternal';
					break;
					
					case 'internal':
					$sBaseClass = 'UserInternal';
					break;
					
					default:
					echo "<p>sAuthentication = $sAuthentication</p>\n";
					assert(false); // should never happen
				}
				$oSearch = DBObjectSearch::FromOQL("SELECT $sBaseClass WHERE login = :login");
				$oSet = new DBObjectSet($oSearch, array(), array('login' => $sLogin));
				$oUser = $oSet->fetch();
				self::$m_aCacheUsers[$sAuthentication][$sLogin] = $oUser;
			}
			$oUser = self::$m_aCacheUsers[$sAuthentication][$sLogin];
		}
		return $oUser;
	}
}

?>

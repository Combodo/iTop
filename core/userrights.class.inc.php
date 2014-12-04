<?php
// Copyright (C) 2010-2013 Combodo SARL
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
 * User rights management API
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
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

define('UR_ACTION_CREATE', 7); // Instantiate an object

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
	abstract public function GetSelectFilter($sLogin, $sClass, $aSettings = array()); // returns a filter object

	abstract public function IsActionAllowed($oUser, $sClass, $iActionCode, /*dbObjectSet*/ $oInstanceSet = null);
	abstract public function IsStimulusAllowed($oUser, $sClass, $sStimulusCode, /*dbObjectSet*/ $oInstanceSet = null);
	abstract public function IsActionAllowedOnAttribute($oUser, $sClass, $sAttCode, $iActionCode, /*dbObjectSet*/ $oInstanceSet = null);
	abstract public function IsAdministrator($oUser);
	abstract public function IsPortalUser($oUser);
	abstract public function FlushPrivileges();

	/**
	 *	...
	 */
	public function MakeSelectFilter($sClass, $aAllowedOrgs, $aSettings = array(), $sAttCode = null)
	{
		if ($sAttCode == null)
		{
			$sAttCode = $this->GetOwnerOrganizationAttCode($sClass);
		}
		if (empty($sAttCode))
		{
			return $oFilter  = new DBObjectSearch($sClass);
		}

		$oExpression = new FieldExpression($sAttCode, $sClass);
		$oFilter  = new DBObjectSearch($sClass);
		$oListExpr = ListExpression::FromScalars($aAllowedOrgs);
		
		$oCondition = new BinaryExpression($oExpression, 'IN', $oListExpr);
		$oFilter->AddConditionExpression($oCondition);

		if ($this->HasSharing())
		{
			if (($sAttCode == 'id') && isset($aSettings['bSearchMode']) && $aSettings['bSearchMode'])
			{
				// Querying organizations (or derived)
				// and the expected list of organizations will be used as a search criteria
				// Therefore the query can also return organization having objects shared with the allowed organizations
				//
				// 1) build the list of organizations sharing something with the allowed organizations
				// Organization <== sharing_org_id == SharedObject having org_id IN {user orgs}
				$oShareSearch = new DBObjectSearch('SharedObject');
				$oOrgField = new FieldExpression('org_id', 'SharedObject');
				$oShareSearch->AddConditionExpression(new BinaryExpression($oOrgField, 'IN', $oListExpr));
	
				$oSearchSharers = new DBObjectSearch('Organization');
				$oSearchSharers->AllowAllData();
				$oSearchSharers->AddCondition_ReferencedBy($oShareSearch, 'sharing_org_id');
				$aSharers = array();
				foreach($oSearchSharers->ToDataArray(array('id')) as $aRow)
				{
					$aSharers[] = $aRow['id'];
				}
				// 2) Enlarge the overall results: ... OR id IN(id1, id2, id3)
				if (count($aSharers) > 0)
				{
					$oSharersList = ListExpression::FromScalars($aSharers);
					$oFilter->MergeConditionExpression(new BinaryExpression($oExpression, 'IN', $oSharersList));
				}
			}
	
			$aShareProperties = SharedObject::GetSharedClassProperties($sClass);
			if ($aShareProperties)
			{
				$sShareClass = $aShareProperties['share_class'];
				$sShareAttCode = $aShareProperties['attcode'];
	
				$oSearchShares = new DBObjectSearch($sShareClass);
				$oSearchShares->AllowAllData();
	
				$sHierarchicalKeyCode = MetaModel::IsHierarchicalClass('Organization');
				$oOrgField = new FieldExpression('org_id', $sShareClass);
				$oSearchShares->AddConditionExpression(new BinaryExpression($oOrgField, 'IN', $oListExpr));
				$aShared = array();
				foreach($oSearchShares->ToDataArray(array($sShareAttCode)) as $aRow)
				{
					$aShared[] = $aRow[$sShareAttCode];
				}
				if (count($aShared) > 0)
				{
					$oObjId = new FieldExpression('id', $sClass);
					$oSharedIdList = ListExpression::FromScalars($aShared);
					$oFilter->MergeConditionExpression(new BinaryExpression($oObjId, 'IN', $oSharedIdList));
				}
			}
		} // if HasSharing

		return $oFilter;
	}
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
	* Compute a name in best effort mode
	*/	
	public function GetFriendlyName()
	{
		if (!MetaModel::IsValidAttCode(get_class($this), 'contactid'))
		{
			return $this->Get('login');
		}
		if ($this->Get('contactid') != 0)
		{
			$sFirstName = $this->Get('first_name');
			$sLastName = $this->Get('last_name');
			$sEmail = $this->Get('email');
			if (strlen($sFirstName) > 0)
			{
				return "$sFirstName $sLastName";
			}
			elseif (strlen($sEmail) > 0)
			{
				return "$sLastName <$sEmail>";
			}
			else
			{
				return $sLastName;
			}
		}
		return $this->Get('login');
	}

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
			if (strcasecmp($this->Get('login'), $this->GetOriginal('login')) !== 0)
			{
				$sNewLogin = $aChanges['login'];
				$oSearch = DBObjectSearch::FromOQL_AllData("SELECT User WHERE login = :newlogin");
				if (!$this->IsNew())
				{
					$oSearch->AddCondition('id', $this->GetKey(), '!=');
				}
				$oSet = new DBObjectSet($oSearch, array(), array('newlogin' => $sNewLogin));
				if ($oSet->Count() > 0)
				{
					$this->m_aCheckIssues[] = Dict::Format('Class:User/Error:LoginMustBeUnique', $sNewLogin);
				}
			}
		}
		// Check that this user has at least one profile assigned
		$oSet = $this->Get('profile_list');
		if ($oSet->Count() == 0)
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
						$aStimuli[] = '<span title="'.$sStimulusCode.': '.htmlentities($oStimulus->GetDescription(), ENT_QUOTES, 'UTF-8').'">'.htmlentities($oStimulus->GetLabel(), ENT_QUOTES, 'UTF-8').'</span>';
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
	
  	public function CheckToDelete(&$oDeletionPlan)
  	{
  		if (MetaModel::GetConfig()->Get('demo_mode'))
		{
			// Users deletion is NOT allowed in demo mode
			$oDeletionPlan->AddToDelete($this, null);
			$oDeletionPlan->SetDeletionIssues($this, array('deletion not allowed in demo mode.'), true);
			$oDeletionPlan->ComputeResults();
			return false;
		}
		return parent::CheckToDelete($oDeletionPlan);
  	} 
	
	protected function DBDeleteSingleObject()
	{
		if (MetaModel::GetConfig()->Get('demo_mode'))
		{
			// Users deletion is NOT allowed in demo mode
			return;
		}
		parent::DBDeleteSingleObject();
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
			"db_table" => "priv_internaluser",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		// When set, this token allows for password reset
		MetaModel::Init_AddAttribute(new AttributeString("reset_pwd_token", array("allowed_values"=>null, "sql"=>"reset_pwd_token", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('contactid', 'first_name', 'email', 'login', 'language', 'profile_list', 'allowed_org_list')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('finalclass', 'first_name', 'last_name', 'login')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('login', 'contactid')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('login', 'contactid')); // Criteria of the advanced search form
	}

	/**
	 * Use with care!
	 */	 	
	public function SetPassword($sNewPassword)
	{
	}

	/**
	 * The email recipient is the person who is allowed to regain control when the password gets lost	
	 * Throws an exception if the feature cannot be available
	 */	
	public function GetResetPasswordEmail()
	{
		if (!MetaModel::IsValidAttCode(get_class($this), 'contactid'))
		{
			throw new Exception(Dict::S('UI:ResetPwd-Error-NoContact'));
		}
		$iContactId = $this->Get('contactid');
		if ($iContactId == 0)
		{
			throw new Exception(Dict::S('UI:ResetPwd-Error-NoContact'));
		}
		$oContact = MetaModel::GetObject('Contact', $iContactId);
		// Determine the email attribute (the first one will be our choice)
		foreach (MetaModel::ListAttributeDefs(get_class($oContact)) as $sAttCode => $oAttDef)
		{
			if ($oAttDef instanceof AttributeEmailAddress)
			{
				$sEmailAttCode = $sAttCode;
				// we've got one, exit the loop
				break;
			}
		}
		if (!isset($sEmailAttCode))
		{
			throw new Exception(Dict::S('UI:ResetPwd-Error-NoEmailAtt'));
		}
		$sRes = trim($oContact->Get($sEmailAttCode));
		return $sRes;
	}
}

/**
 * Self register extension  
 *
 * @package     iTopORM
 */
interface iSelfRegister
{
	/**
	 * Called when no user is found in iTop for the corresponding 'name'. This method
	 * can create/synchronize the User in iTop with an external source (such as AD/LDAP) on the fly
	 * @param string $sName The typed-in user name
	 * @param string $sPassword The typed-in password
	 * @param string $sLoginMode The login method used (cas|form|basic|url)
	 * @param string $sAuthentication The authentication method used (any|internal|external)
	 * @return bool true if the user is a valid one, false otherwise
	 */
	public static function CheckCredentialsAndCreateUser($sName, $sPassword, $sLoginMode, $sAuthentication);
	
	/**
	 * Called after the user has been authenticated and found in iTop. This method can
	 * Update the user's definition on the fly (profiles...) to keep it in sync with an external source 
	 * @param User $oUser The user to update/synchronize
	 * @param string $sLoginMode The login mode used (cas|form|basic|url)
	 * @param string $sAuthentication The authentication method used
	 * @return void
	 */
	public static function UpdateUser(User $oUser, $sLoginMode, $sAuthentication);
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
	protected static $m_sSelfRegisterAddOn = null;

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

	public static function SelectSelfRegister($sModuleName)
	{
		if (!class_exists($sModuleName))
		{
			throw new CoreException("Could not select the class, '$sModuleName' for self register, is not a valid class name");
		}
		self::$m_sSelfRegisterAddOn = $sModuleName;
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

	public static function CheckCredentials($sName, $sPassword, $sLoginMode = 'form', $sAuthentication = 'any')
	{
		$oUser = self::FindUser($sName, $sAuthentication);
		if (is_null($oUser))
		{
			return self::CheckCredentialsAndCreateUser($sName, $sPassword, $sLoginMode, $sAuthentication);
		}

		if (!$oUser->CheckCredentials($sPassword))
		{
			return false;
		}
		self::UpdateUser($oUser, $sLoginMode, $sAuthentication);
		return true;
	}
	
	public static function CheckCredentialsAndCreateUser($sName, $sPassword, $sLoginMode, $sAuthentication)
	{
		if (self::$m_sSelfRegisterAddOn != null)
		{
			return call_user_func(array(self::$m_sSelfRegisterAddOn, 'CheckCredentialsAndCreateUser'), $sName, $sPassword, $sLoginMode, $sAuthentication);
		}
	}

	public static function UpdateUser($oUser, $sLoginMode, $sAuthentication)
	{
		if (self::$m_sSelfRegisterAddOn != null)
		{
			call_user_func(array(self::$m_sSelfRegisterAddOn, 'UpdateUser'), $oUser, $sLoginMode, $sAuthentication);
		}
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

	public static function ChangePassword($sOldPassword, $sNewPassword, $sName = '')
	{
		if (empty($sName))
		{
			$oUser = self::$m_oUser;
		}
		else
		{
			// find the id out of the login string
			$oUser = self::FindUser($sName);
		}
		if (is_null($oUser))
		{
			return false;
		}
		else
		{
			return $oUser->ChangePassword($sOldPassword, $sNewPassword);
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

	public static function GetUserObject()
	{
		if (is_null(self::$m_oUser))
		{
			return null;
		}
		else
		{
			return self::$m_oUser;
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

	// Render the user name in best effort mode
	public static function GetUserFriendlyName($sName = '')
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
		return $oUser->GetFriendlyName();
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

	public static function GetRealUserFriendlyName()
	{
		if (is_null(self::$m_oRealUser))
		{
			return '';
		}
		return self::$m_oRealUser->GetFriendlyName();
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

	public static function GetSelectFilter($sClass, $aSettings = array())
	{
		// When initializing, we need to let everything pass trough
		if (!self::CheckLogin()) return true;

		if (self::IsAdministrator()) return true;

		if (MetaModel::HasCategory($sClass, 'bizmodel'))
		{
			return self::$m_oAddOn->GetSelectFilter(self::$m_oUser, $sClass, $aSettings);
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

		if (MetaModel::DBIsReadOnly())
		{
			if ($iActionCode == UR_ACTION_CREATE) return false;
			if ($iActionCode == UR_ACTION_MODIFY) return false;
			if ($iActionCode == UR_ACTION_BULK_MODIFY) return false;
			if ($iActionCode == UR_ACTION_DELETE) return false;
			if ($iActionCode == UR_ACTION_BULK_DELETE) return false;
		}

		$aPredefinedObjects = call_user_func(array($sClass, 'GetPredefinedObjects'));
		if ($aPredefinedObjects != null)
		{
			// As opposed to the read-only DB, modifying an object is allowed
			// (the constant columns will be marked as read-only)
			//
			if ($iActionCode == UR_ACTION_CREATE) return false;
			if ($iActionCode == UR_ACTION_DELETE) return false;
			if ($iActionCode == UR_ACTION_BULK_DELETE) return false;
		}

		if (self::IsAdministrator($oUser)) return true;

		if (MetaModel::HasCategory($sClass, 'bizmodel'))
		{
			if (is_null($oUser))
			{
				$oUser = self::$m_oUser;
			}
			if ($iActionCode == UR_ACTION_CREATE)
			{
				// The addons currently DO NOT handle the case "CREATE"
				// Therefore it is considered to be equivalent to "MODIFY"
				$iActionCode = UR_ACTION_MODIFY;
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

		if (MetaModel::DBIsReadOnly())
		{
			return false;
		}

		if (self::IsAdministrator($oUser)) return true;

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

		if (MetaModel::DBIsReadOnly())
		{
			if ($iActionCode == UR_ACTION_MODIFY) return false;
			if ($iActionCode == UR_ACTION_DELETE) return false;
			if ($iActionCode == UR_ACTION_BULK_MODIFY) return false;
			if ($iActionCode == UR_ACTION_BULK_DELETE) return false;
		}

		if (self::IsAdministrator($oUser)) return true;

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

	public static function MakeSelectFilter($sClass, $aAllowedOrgs, $aSettings = array(), $sAttCode = null)
	{
		return self::$m_oAddOn->MakeSelectFilter($sClass, $aAllowedOrgs, $aSettings, $sAttCode);
	}
}

/**
 * Helper class to get the number/list of items for which a given action is allowed/possible
 */
class ActionChecker
{
	var $oFilter;
	var $iActionCode;
	var $iAllowedCount = null;
	var $aAllowedIDs = null;
	
	public function __construct(DBObjectSearch $oFilter, $iActionCode)
	{
		$this->oFilter = $oFilter;
		$this->iActionCode = $iActionCode;
		$this->iAllowedCount = null;
		$this->aAllowedIDs = null;
	}
	
	/**
	 * returns the number of objects for which the action is allowed
	 * @return integer The number of "allowed" objects 0..N
	 */
	public function GetAllowedCount()
	{
		if ($this->iAllowedCount == null) $this->CheckObjects();
		return $this->iAllowedCount;
	}
	
	/**
	 * If IsAllowed returned UR_ALLOWED_DEPENDS, this methods returns
	 * an array of ObjKey => Status (true|false)
	 * @return array
	 */
	public function GetAllowedIDs()
	{
		if ($this->aAllowedIDs == null) $this->IsAllowed();
		return $this->aAllowedIDs;
	}
	
	/**
	 * Check if the speficied stimulus is allowed for the set of objects
	 * @return UR_ALLOWED_YES, UR_ALLOWED_NO or UR_ALLOWED_DEPENDS
	 */
	public function IsAllowed()
	{
		$sClass = $this->oFilter->GetClass();
		$oSet = new DBObjectSet($this->oFilter);
		$iActionAllowed = UserRights::IsActionAllowed($sClass, $this->iActionCode, $oSet);
		if ($iActionAllowed == UR_ALLOWED_DEPENDS)
		{
			// Check for each object if the action is allowed or not
			$this->aAllowedIDs = array();
			$oSet->Rewind();
			$this->iAllowedCount = 0;
			while($oObj = $oSet->Fetch())
			{
				$oObjSet = DBObjectSet::FromArray($sClass, array($oObj));
				if (UserRights::IsActionAllowed($sClass, $this->iActionCode, $oObjSet) == UR_ALLOWED_NO)
				{
					$this->aAllowedIDs[$oObj->GetKey()] = false;
				}
				else
				{
					// Assume UR_ALLOWED_YES, since there is just one object !
					$this->aAllowedIDs[$oObj->GetKey()] = true;
					$this->iAllowedCount++;
				}
			}
		}
		else if ($iActionAllowed == UR_ALLOWED_YES)
		{
			$this->iAllowedCount = $oSet->Count();
			$this->aAllowedIDs = array(); // Optimization: not filled when Ok for all objects
		}
		else // UR_ALLOWED_NO
		{
			$this->iAllowedCount = 0;
			$this->aAllowedIDs = array();
		}
		return $iActionAllowed;
	}
}

/**
 * Helper class to get the number/list of items for which a given stimulus can be applied (allowed & possible)
 */
class StimulusChecker extends ActionChecker
{
	var $sState = null;
	
	public function __construct(DBObjectSearch $oFilter, $sState, $iStimulusCode)
	{
		parent::__construct($oFilter, $iStimulusCode);
		$this->sState = $sState;
	}

	/**
	 * Check if the speficied stimulus is allowed for the set of objects
	 * @return UR_ALLOWED_YES, UR_ALLOWED_NO or UR_ALLOWED_DEPENDS
	 */
	public function IsAllowed()
	{
		$sClass = $this->oFilter->GetClass();
		if (MetaModel::IsAbstract($sClass)) return UR_ALLOWED_NO; // Safeguard, not implemented if the base class of the set is abstract !
		
		$oSet = new DBObjectSet($this->oFilter);
		$iActionAllowed = UserRights::IsStimulusAllowed($sClass,  $this->iActionCode, $oSet);
		if ($iActionAllowed == UR_ALLOWED_NO)
		{
			$this->iAllowedCount = 0;
			$this->aAllowedIDs = array();
		}
		else // Even if UR_ALLOWED_YES, we need to check if each object is in the appropriate state
		{
			// Hmmm, may not be needed right now because we limit the "multiple" action to object in
			// the same state... may be useful later on if we want to extend this behavior...
			
			// Check for each object if the action is allowed or not
			$this->aAllowedIDs = array();
			$oSet->Rewind();
			$iAllowedCount = 0;
			$iActionAllowed = UR_ALLOWED_DEPENDS;
			while($oObj = $oSet->Fetch())
			{
				$aTransitions = $oObj->EnumTransitions();
				if (array_key_exists($this->iActionCode, $aTransitions))
				{
					// Temporary optimization possible: since the current implementation
					// of IsActionAllowed does not perform a 'per instance' check, we could
					// skip this second validation phase and assume it would return UR_ALLOWED_YES
					$oObjSet = DBObjectSet::FromArray($sClass, array($oObj));
					if (!UserRights::IsStimulusAllowed($sClass, $this->iActionCode, $oObjSet))
					{
						$this->aAllowedIDs[$oObj->GetKey()] = false;
					}
					else
					{
						// Assume UR_ALLOWED_YES, since there is just one object !
						$this->aAllowedIDs[$oObj->GetKey()] = true;
						$this->iState = $oObj->GetState();
						$this->iAllowedCount++;
					}					
				}
				else
				{
					$this->aAllowedIDs[$oObj->GetKey()] = false;					
				}				
			}
		}
		
		if ($this->iAllowedCount == $oSet->Count())
		{
			$iActionAllowed = UR_ALLOWED_YES;
		}
		if ($this->iAllowedCount == 0)
		{
			$iActionAllowed = UR_ALLOWED_NO;
		}

		return $iActionAllowed;
	}
	
	public function GetState()
	{
		return $this->iState;
	}		
}

/**
 * Self-register extension to allow the automatic creation & update of CAS users
 * 
 * @package iTopORM
 *
 */
class CAS_SelfRegister implements iSelfRegister
{
	/**
	 * Called when no user is found in iTop for the corresponding 'name'. This method
	 * can create/synchronize the User in iTop with an external source (such as AD/LDAP) on the fly
	 * @param string $sName The CAS authenticated user name
	 * @param string $sPassword Ignored
	 * @param string $sLoginMode The login mode used (cas|form|basic|url)
	 * @param string $sAuthentication The authentication method used
	 * @return bool true if the user is a valid one, false otherwise
	 */
	public static function CheckCredentialsAndCreateUser($sName, $sPassword, $sLoginMode, $sAuthentication)
	{
		$bOk = true;
		if ($sLoginMode != 'cas') return false; // Must be authenticated via CAS

		$sCASMemberships = MetaModel::GetConfig()->Get('cas_memberof');
		$bFound =  false;
		if (!empty($sCASMemberships))
		{
			if (phpCAS::hasAttribute('memberOf'))
			{
				// A list of groups is specified, the user must a be member of (at least) one of them to pass
				$aCASMemberships = array();
				$aTmp = explode(';', $sCASMemberships);
				setlocale(LC_ALL, "en_US.utf8"); // !!! WARNING: this is needed to have  the iconv //TRANSLIT working fine below !!!
				foreach($aTmp as $sGroupName)
				{
					$aCASMemberships[] = trim(iconv('UTF-8', 'ASCII//TRANSLIT', $sGroupName)); // Just in case remove accents and spaces...
				}

				$aMemberOf = phpCAS::getAttribute('memberOf');
				if (!is_array($aMemberOf)) $aMemberOf = array($aMemberOf); // Just one entry, turn it into an array
				$aFilteredGroupNames = array();
				foreach($aMemberOf as $sGroupName)
				{
					phpCAS::log("Info: user if a member of the group: ".$sGroupName);
					$sGroupName = trim(iconv('UTF-8', 'ASCII//TRANSLIT', $sGroupName)); // Remove accents and spaces as well
					$aFilteredGroupNames[] = $sGroupName;
					$bIsMember = false;
					foreach($aCASMemberships as $sCASPattern)
					{
						if (self::IsPattern($sCASPattern))
						{
							if (preg_match($sCASPattern, $sGroupName))
							{
								$bIsMember = true;
								break;
							}
						}
						else if ($sPattern == $sGroupName)
						{
							$bIsMember = true;
							break;
						}
					}
					if ($bIsMember)
					{
						$bCASUserSynchro = MetaModel::GetConfig()->Get('cas_user_synchro');
						if ($bCASUserSynchro)
						{
							// If needed create a new user for this email/profile
							phpCAS::log('Info: cas_user_synchro is ON');
							$bOk = self::CreateCASUser(phpCAS::getUser(), $aMemberOf);
							if($bOk)
							{
								$bFound = true;
							}
							else
							{
								phpCAS::log("User ".phpCAS::getUser()." cannot be created in iTop. Logging off...");
							}
						}
						else
						{
							phpCAS::log('Info: cas_user_synchro is OFF');
							$bFound = true;
						}
						break;
					}	
				}
				if($bOk && !$bFound)
				{
					phpCAS::log("User ".phpCAS::getUser().", none of his/her groups (".implode('; ', $aFilteredGroupNames).") match any of the required groups: ".implode('; ', $aCASMemberships));
				}
			}
			else
			{
				// Too bad, the user is not part of any of the group => not allowed
				phpCAS::log("No 'memberOf' attribute found for user ".phpCAS::getUser().". Are you using the SAML protocol (S1) ?");
			}
		}
		else
		{
			// No membership required, anybody will pass
			$bFound = true;
		}
		
		if (!$bFound)
		{
			// The user is not part of the allowed groups, => log out
			$sUrl = utils::GetAbsoluteUrlAppRoot().'pages/UI.php';
			$sCASLogoutUrl = MetaModel::GetConfig()->Get('cas_logout_redirect_service');
			if (empty($sCASLogoutUrl))
			{
				$sCASLogoutUrl = $sUrl;
			}
			phpCAS::logoutWithRedirectService($sCASLogoutUrl); // Redirects to the CAS logout page
			// Will never return !			
		}
		return $bFound;
	}
	
	/**
	 * Called after the user has been authenticated and found in iTop. This method can
	 * Update the user's definition (profiles...) on the fly to keep it in sync with an external source 
	 * @param User $oUser The user to update/synchronize
	 * @param string $sLoginMode The login mode used (cas|form|basic|url)
	 * @param string $sAuthentication The authentication method used
	 * @return void
	 */
	public static function UpdateUser(User $oUser, $sLoginMode, $sAuthentication)
	{
		$bCASUpdateProfiles = MetaModel::GetConfig()->Get('cas_update_profiles');
		if (($sLoginMode == 'cas') && $bCASUpdateProfiles && (phpCAS::hasAttribute('memberOf')))
		{
			$aMemberOf = phpCAS::getAttribute('memberOf');
			if (!is_array($aMemberOf)) $aMemberOf = array($aMemberOf); // Just one entry, turn it into an array
			
			return self::SetProfilesFromCAS($oUser, $aMemberOf);
		}
		// No groups defined in CAS or not CAS at all: do nothing...
		return true;
	}
	
	/**
	 * Helper method to create a CAS based user
	 * @param string $sEmail
	 * @param array $aGroups
	 * @return bool true on success, false otherwise
	 */
	protected static function CreateCASUser($sEmail, $aGroups)
	{
		if (!MetaModel::IsValidClass('URP_Profiles'))
		{
			phpCAS::log("URP_Profiles is not a valid class. Automatic creation of Users is not supported in this context, sorry.");
			return false;
		}
				
		$oUser = MetaModel::GetObjectByName('UserExternal', $sEmail, false);
		if ($oUser == null)
		{
			// Create the user, link it to a contact
			phpCAS::log("Info: the user '$sEmail' does not exist. A new UserExternal will be created.");
			$oSearch = new DBObjectSearch('Person');
			$oSearch->AddCondition('email', $sEmail);
			$oSet = new DBObjectSet($oSearch);
			$iContactId = 0;
			switch($oSet->Count())
			{
				case 0:
				phpCAS::log("Error: found no contact with the email: '$sEmail'. Cannot create the user in iTop.");
				return false;

				case 1:
				$oContact = $oSet->Fetch();
				$iContactId = $oContact->GetKey();
				phpCAS::log("Info: Found 1 contact '".$oContact->GetName()."' (id=$iContactId) corresponding to the email '$sEmail'.");
				break;

				default:
				phpCAS::log("Error: ".$oSet->Count()." contacts have the same email: '$sEmail'. Cannot create a user for this email.");
				return false;
			}
			
			$oUser = new UserExternal();
			$oUser->Set('login', $sEmail);
			$oUser->Set('contactid', $iContactId);
			$oUser->Set('language', MetaModel::GetConfig()->GetDefaultLanguage());
		}
		else
		{
			phpCAS::log("Info: the user '$sEmail' already exists (id=".$oUser->GetKey().").");
		}

		// Now synchronize the profiles
		if (!self::SetProfilesFromCAS($oUser, $aGroups))
		{
			return false;
		}
		else 
		{
			if ($oUser->IsNew() || $oUser->IsModified())
			{
				$oMyChange = MetaModel::NewObject("CMDBChange");
				$oMyChange->Set("date", time());
				$oMyChange->Set("userinfo", 'CAS/LDAP Synchro');
				$oMyChange->DBInsert();
				if ($oUser->IsNew())
				{
					$oUser->DBInsertTracked($oMyChange);
				}
				else
				{
					$oUser->DBUpdateTracked($oMyChange);
				}
			}
			
			return true;
		}
	}
	
	protected static function SetProfilesFromCAS($oUser, $aGroups)
	{
		if (!MetaModel::IsValidClass('URP_Profiles'))
		{
			phpCAS::log("URP_Profiles is not a valid class. Automatic creation of Users is not supported in this context, sorry.");
			return false;
		}
		
		// read all the existing profiles
		$oProfilesSearch = new DBObjectSearch('URP_Profiles');
		$oProfilesSet = new DBObjectSet($oProfilesSearch);
		$aAllProfiles = array();
		while($oProfile = $oProfilesSet->Fetch())
		{
			$aAllProfiles[strtolower($oProfile->GetName())] = $oProfile->GetKey();
		}
		
		// Translate the CAS/LDAP group names into iTop profile names
		$aProfiles = array();
		$sPattern = MetaModel::GetConfig()->Get('cas_profile_pattern');
		foreach($aGroups as $sGroupName)
		{
			if (preg_match($sPattern, $sGroupName, $aMatches))
			{
				if (array_key_exists(strtolower($aMatches[1]), $aAllProfiles))
				{
					$aProfiles[] = $aAllProfiles[strtolower($aMatches[1])];
					phpCAS::log("Info: Adding the profile '{$aMatches[1]}' from CAS.");
				}
				else
				{
					phpCAS::log("Warning: {$aMatches[1]} is not a valid iTop profile (extracted from group name: '$sGroupName'). Ignored.");
				}
			}
			else
			{
				phpCAS::log("Info: The CAS group '$sGroupName' does not seem to match an iTop pattern. Ignored.");
			}
		}
		if (count($aProfiles) == 0)
		{
			phpCAS::log("Info: The user '".$oUser->GetName()."' has no profiles retrieved from CAS. Default profile(s) will be used.");

			// Second attempt: check if there is/are valid default profile(s)
			$sCASDefaultProfiles = MetaModel::GetConfig()->Get('cas_default_profiles');
			$aCASDefaultProfiles = explode(';', $sCASDefaultProfiles);
			foreach($aCASDefaultProfiles as $sDefaultProfileName)
			{
				if (array_key_exists(strtolower($sDefaultProfileName), $aAllProfiles))
				{
					$aProfiles[] = $aAllProfiles[strtolower($sDefaultProfileName)];
					phpCAS::log("Info: Adding the default profile '".$aAllProfiles[strtolower($sDefaultProfileName)]."' from CAS.");
				}
				else
				{
					phpCAS::log("Warning: the default profile {$sDefaultProfileName} is not a valid iTop profile. Ignored.");
				}
			}
			
			if (count($aProfiles) == 0)
			{
				phpCAS::log("Error: The user '".$oUser->GetName()."' has no profiles in iTop, and therefore cannot be created.");
				return false;
			}
		}
		
		// Now synchronize the profiles
		$oProfilesSet = DBObjectSet::FromScratch('URP_UserProfile');
		foreach($aProfiles as $iProfileId)
		{
			$oLink = new URP_UserProfile();
			$oLink->Set('profileid', $iProfileId);
			$oLink->Set('reason', 'CAS/LDAP Synchro');
			$oProfilesSet->AddObject($oLink);
		}
		$oUser->Set('profile_list', $oProfilesSet);
		phpCAS::log("Info: the user '".$oUser->GetName()."' (id=".$oUser->GetKey().") now has the following profiles: '".implode("', '", $aProfiles)."'.");
		if ($oUser->IsModified())
		{
			$oMyChange = MetaModel::NewObject("CMDBChange");
			$oMyChange->Set("date", time());
			$oMyChange->Set("userinfo", 'CAS/LDAP Synchro');
			$oMyChange->DBInsert();
			if ($oUser->IsNew())
			{
				$oUser->DBInsertTracked($oMyChange);
			}
			else
			{
				$oUser->DBUpdateTracked($oMyChange);
			}
		}
		
		return true;
	}
	/**
	 * Helper function to check if the supplied string is a litteral string or a regular expression pattern
	 * @param string $sCASPattern
	 * @return bool True if it's a regular expression pattern, false otherwise
	 */
	protected static function IsPattern($sCASPattern)
	{
		if ((substr($sCASPattern, 0, 1) == '/') && (substr($sCASPattern, -1) == '/'))
		{
			// the string is enclosed by slashes, let's assume it's a pattern
			return true;
		}
		else
		{
			return false;
		}
	}
}

// By default enable the 'CAS_SelfRegister' defined above
UserRights::SelectSelfRegister('CAS_SelfRegister');
?>

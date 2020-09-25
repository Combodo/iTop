<?php
/**
 * Copyright (C) 2013-2020 Combodo SARL
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
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
	 * Default behavior for addons that do not support profiles
	 *
	 * @param $oUser User
	 * @return array
	 */
	public function ListProfiles($oUser)
	{
		return array();
	}

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


require_once(APPROOT.'/application/cmdbabstract.class.inc.php');
abstract class User extends cmdbAbstractObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "core,grant_by_profile,silo",
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
		MetaModel::Init_AddAttribute(new AttributeExternalField("org_id", array("allowed_values"=>null, "extkey_attcode"=> 'contactid', "target_attcode"=>"org_id")));

		MetaModel::Init_AddAttribute(new AttributeString("login", array("allowed_values"=>null, "sql"=>"login", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));

		MetaModel::Init_AddAttribute(new AttributeApplicationLanguage("language", array("sql"=>"language", "default_value"=>"EN US", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("status", array("allowed_values" => new ValueSetEnum('enabled,disabled'), "sql"=>"status", "default_value"=>"enabled", "is_null_allowed"=>false, "depends_on"=>array())));
		
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("profile_list", array("linked_class"=>"URP_UserProfile", "ext_key_to_me"=>"userid", "ext_key_to_remote"=>"profileid", "allowed_values"=>null, "count_min"=>1, "count_max"=>0, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("allowed_org_list", array("linked_class"=>"URP_UserOrg", "ext_key_to_me"=>"userid", "ext_key_to_remote"=>"allowed_org_id", "allowed_values"=>null, "count_min"=>1, "count_max"=>0, "depends_on"=>array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('contactid', 'org_id', 'email', 'login', 'language', 'status', 'profile_list', 'allowed_org_list')); // Unused as it's an abstract class !
		MetaModel::Init_SetZListItems('list', array('finalclass', 'first_name', 'last_name', 'status', 'org_id')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('login', 'contactid', 'email', 'language', 'status', 'org_id')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('default_search', array('login', 'contactid', 'org_id')); // Default criteria of the search banner
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

	protected $oContactObject;

	/**
	 * Fetch and memorize the associated contact (if any)
	 */
	public function GetContactObject()
	{
		if (is_null($this->oContactObject))
		{
			if (MetaModel::IsValidAttCode(get_class($this), 'contactid') && ($this->Get('contactid') != 0))
			{
				$this->oContactObject = MetaModel::GetObject('Contact', $this->Get('contactid'));
			}
		}
		return $this->oContactObject;
	}

	/**
	 * Overload the standard behavior.
	 *
	 * @throws \CoreException
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
		// Check that this user has at least one profile assigned when profiles have changed
		if (array_key_exists('profile_list', $aChanges))
		{
			$oSet = $this->Get('profile_list');
			if ($oSet->Count() == 0)
			{
				$this->m_aCheckIssues[] = Dict::S('Class:User/Error:AtLeastOneProfileIsNeeded');
			}
		}
		// Only administrators can manage administrators
		if (UserRights::IsAdministrator($this) && !UserRights::IsAdministrator())
		{
			$this->m_aCheckIssues[] = Dict::S('UI:Login:Error:AccessRestricted');
		}

		if (!UserRights::IsAdministrator())
		{
			$oUser = UserRights::GetUserObject();
			$oAddon = UserRights::GetModuleInstance();
			if (!is_null($oUser) && method_exists($oAddon, 'GetUserOrgs'))
			{
				if ((empty($this->GetOriginal('contactid')) && !($this->IsNew())) || empty($this->Get('contactid')))
				{
					$this->m_aCheckIssues[] = Dict::S('Class:User/Error:PersonIsMandatory');
				}
				else
				{
					$aOrgs = $oAddon->GetUserOrgs($oUser, '');
					if (count($aOrgs) > 0)
					{
						// Check that the modified User belongs to one of our organization
						if (!in_array($this->GetOriginal('org_id'), $aOrgs) && !in_array($this->Get('org_id'), $aOrgs))
						{
							$this->m_aCheckIssues[] = Dict::S('Class:User/Error:UserOrganizationNotAllowed');
						}
						// Check users with restricted organizations when allowed organizations have changed
						if ($this->IsNew() || array_key_exists('allowed_org_list', $aChanges))
						{
							$oSet = $this->get('allowed_org_list');
							if ($oSet->Count() == 0)
							{
								$this->m_aCheckIssues[] = Dict::S('Class:User/Error:AtLeastOneOrganizationIsNeeded');
							}
							else
							{
								$aModifiedLinks = $oSet->ListModifiedLinks();
								foreach ($aModifiedLinks as $oLink)
								{
									if (!in_array($oLink->Get('allowed_org_id'), $aOrgs))
									{
										$this->m_aCheckIssues[] = Dict::S('Class:User/Error:OrganizationNotAllowed');
									}
								}
							}
						}
					}
				}
			}
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
				'delete' => $this->GetGrantAsHtml($sClass, UR_ACTION_DELETE),
				'bulkdelete' => $this->GetGrantAsHtml($sClass, UR_ACTION_BULK_DELETE),
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
		$aDisplayConfig['delete'] = array('label' => Dict::S('UI:UserManagement:Action:Delete'), 'description' => Dict::S('UI:UserManagement:Action:Delete+'));
		$aDisplayConfig['bulkdelete'] = array('label' => Dict::S('UI:UserManagement:Action:BulkDelete'), 'description' => Dict::S('UI:UserManagement:Action:BulkDelete+'));
		$aDisplayConfig['stimuli'] = array('label' => Dict::S('UI:UserManagement:Action:Stimuli'), 'description' => Dict::S('UI:UserManagement:Action:Stimuli+'));
		$oPage->table($aDisplayConfig, $aDisplayData);
	}

	function DisplayBareRelations(WebPage $oPage, $bEditMode = false)
	{
		parent::DisplayBareRelations($oPage, $bEditMode);
		if (!$bEditMode)
		{
			$oPage->SetCurrentTab('UI:UserManagement:GrantMatrix');
			$this->DoShowGrantSumary($oPage, 'bizmodel,grant_by_profile');

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
			"category" => "core,grant_by_profile",
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
		MetaModel::Init_AddAttribute(new AttributeOneWayPassword("reset_pwd_token", array("allowed_values"=>null, "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('contactid', 'org_id', 'email', 'login', 'status', 'language', 'profile_list', 'allowed_org_list')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('finalclass', 'first_name', 'last_name', 'status', 'org_id')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('login', 'contactid', 'status', 'org_id')); // Criteria of the std search form
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
	/** @var UserRightsAddOnAPI $m_oAddOn */
	protected static $m_oAddOn;
	protected static $m_oUser;
	protected static $m_oRealUser;
	protected static $m_sSelfRegisterAddOn = null;
	/** @var array array('sName' => $sName, 'bSuccess' => $bSuccess); */
	private static $m_sLastLoginStatus = null;

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
	
	public static function IsLoggedIn()
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

		if (isset($_SESSION['impersonate_user']))
		{
			self::$m_oRealUser = self::$m_oUser;
			self::$m_oUser = self::FindUser($_SESSION['impersonate_user']);
		}

		Dict::SetUserLanguage(self::GetUserLanguage());
		return true;
	}

	public static function CheckCredentials($sName, $sPassword, $sLoginMode = 'form', $sAuthentication = 'any')
	{
		$oUser = self::FindUser($sName, $sAuthentication);
		if (is_null($oUser))
		{
			// Check if the user does not exist at all or if it is just disabled
			if (self::FindUser($sName, $sAuthentication, true) == null)
			{
				// User does not exist at all
				$bCheckCredentialsAndCreateUser = self::CheckCredentialsAndCreateUser($sName, $sPassword, $sLoginMode, $sAuthentication);
				self::$m_sLastLoginStatus = array('sName' => $sName, 'bSuccess' => $bCheckCredentialsAndCreateUser);
				return $bCheckCredentialsAndCreateUser;
			}
			else
			{
				// User is actually disabled
				self::$m_sLastLoginStatus = array('sName' => $sName, 'bSuccess' => false);
				return  false;
			}
		}

		if (!$oUser->CheckCredentials($sPassword))
		{
			self::$m_sLastLoginStatus = array('sName' => $sName, 'bSuccess' => false);
			return false;
		}
		self::UpdateUser($oUser, $sLoginMode, $sAuthentication);
		self::$m_sLastLoginStatus = array('sName' => $sName, 'bSuccess' => true);

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

	/**
	 * Tells whether or not the archive mode is allowed to the current user
	 * @return boolean
	 */
	static function CanBrowseArchive()
	{
		if (is_null(self::$m_oUser))
		{
			$bRet = false;
		}
		elseif (isset($_SESSION['archive_allowed']))
		{
			$bRet = $_SESSION['archive_allowed'];
		}
		else
		{
			// As of now, anybody can switch to the archive mode as soon as there is an archivable class
			$bRet = (count(MetaModel::EnumArchivableClasses()) > 0);
			$_SESSION['archive_allowed'] = $bRet;
		}
		return $bRet;
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
			$oUser->AllowWrite(true);
			return $oUser->ChangePassword($sOldPassword, $sNewPassword);
		}
	}

	/**
	 * @param string $sName Login identifier of the user to impersonate
	 * @return bool True if an impersonation occurred
	 */
	public static function Impersonate($sName)
	{
		if (!self::CheckLogin()) return false;

		$bRet = false;
		$oUser = self::FindUser($sName);
		if ($oUser)
		{
			$bRet = true;
			if (is_null(self::$m_oRealUser))
			{
				// First impersonation
				self::$m_oRealUser = self::$m_oUser;
			}
			if (self::$m_oRealUser && (self::$m_oRealUser->GetKey() == $oUser->GetKey()))
			{
				// Equivalent to "Deimpersonate"
				self::Deimpersonate();
			}
			else
			{
				// Do impersonate!
				self::$m_oUser = $oUser;
				Dict::SetUserLanguage(self::GetUserLanguage());
				$_SESSION['impersonate_user'] = $sName;
				self::_ResetSessionCache();
			}
		}
		return $bRet;
	}

	public static function Deimpersonate()
	{
		if (!is_null(self::$m_oRealUser))
		{
			self::$m_oUser = self::$m_oRealUser;
			Dict::SetUserLanguage(self::GetUserLanguage());
			unset($_SESSION['impersonate_user']);
			self::_ResetSessionCache();
		}
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

	/** User */
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

	public static function GetContactObject()
	{
		if (is_null(self::$m_oUser))
		{
			return null;
		}
		else
		{
			return self::$m_oUser->GetContactObject();
		}
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

	public static function GetRealUserObject()
	{
		return self::$m_oRealUser;
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

	/**
	 * Add additional filter for organization silos to all the requests.
	 *
	 * @param $sClass
	 * @param array $aSettings
	 *
	 * @return bool|\Expression
	 */
	public static function GetSelectFilter($sClass, $aSettings = array())
	{
		// When initializing, we need to let everything pass trough
		if (!self::CheckLogin()) {return true;}

		if (self::IsAdministrator()) {return true;}

		try
		{
			// Check Bug 1436 for details
			if (MetaModel::HasCategory($sClass, 'bizmodel') || MetaModel::HasCategory($sClass, 'silo'))
			{
				return self::$m_oAddOn->GetSelectFilter(self::$m_oUser, $sClass, $aSettings);
			}
			else
			{
				return true;
			}
		} catch (Exception $e)
		{
			return false;
		}
	}

	/**
	 * @param string $sClass
	 * @param int $iActionCode
	 * @param DBObjectSet $oInstanceSet
	 * @param User $oUser
	 * @return int (UR_ALLOWED_YES|UR_ALLOWED_NO|UR_ALLOWED_DEPENDS)
	 */
	public static function IsActionAllowed($sClass, $iActionCode, /*dbObjectSet*/$oInstanceSet = null, $oUser = null)
	{
		// When initializing, we need to let everything pass trough
		if (!self::CheckLogin()) return UR_ALLOWED_YES;

		if (MetaModel::DBIsReadOnly())
		{
			if ($iActionCode == UR_ACTION_CREATE) return UR_ALLOWED_NO;
			if ($iActionCode == UR_ACTION_MODIFY) return UR_ALLOWED_NO;
			if ($iActionCode == UR_ACTION_BULK_MODIFY) return UR_ALLOWED_NO;
			if ($iActionCode == UR_ACTION_DELETE) return UR_ALLOWED_NO;
			if ($iActionCode == UR_ACTION_BULK_DELETE) return UR_ALLOWED_NO;
		}

		$aPredefinedObjects = call_user_func(array($sClass, 'GetPredefinedObjects'));
		if ($aPredefinedObjects != null)
		{
			// As opposed to the read-only DB, modifying an object is allowed
			// (the constant columns will be marked as read-only)
			//
			if ($iActionCode == UR_ACTION_CREATE) return UR_ALLOWED_NO;
			if ($iActionCode == UR_ACTION_DELETE) return UR_ALLOWED_NO;
			if ($iActionCode == UR_ACTION_BULK_DELETE) return UR_ALLOWED_NO;
		}

		if (self::IsAdministrator($oUser)) return UR_ALLOWED_YES;

		if (MetaModel::HasCategory($sClass, 'bizmodel') || MetaModel::HasCategory($sClass, 'grant_by_profile'))
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
			return UR_ALLOWED_YES;
		}
		else
		{
			// Other classes could be edited/listed by the administrators
			return UR_ALLOWED_NO;
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

	/**
	 * @param string $sClass
	 * @param string $sAttCode
	 * @param int $iActionCode
	 * @param DBObjectSet $oInstanceSet
	 * @param User $oUser
	 * @return int (UR_ALLOWED_YES|UR_ALLOWED_NO)
	 */
	public static function IsActionAllowedOnAttribute($sClass, $sAttCode, $iActionCode, /*dbObjectSet*/$oInstanceSet = null, $oUser = null)
	{
		// When initializing, we need to let everything pass trough
		if (!self::CheckLogin()) return UR_ALLOWED_YES;

		if (MetaModel::DBIsReadOnly())
		{
			if ($iActionCode == UR_ACTION_MODIFY) return UR_ALLOWED_NO;
			if ($iActionCode == UR_ACTION_DELETE) return UR_ALLOWED_NO;
			if ($iActionCode == UR_ACTION_BULK_MODIFY) return UR_ALLOWED_NO;
			if ($iActionCode == UR_ACTION_BULK_DELETE) return UR_ALLOWED_NO;
		}

		if (self::IsAdministrator($oUser)) return UR_ALLOWED_YES;

		if (MetaModel::HasCategory($sClass, 'bizmodel') || MetaModel::HasCategory($sClass, 'grant_by_profile'))
		{
			if (is_null($oUser))
			{
				$oUser = self::$m_oUser;
			}
			return self::$m_oAddOn->IsActionAllowedOnAttribute($oUser, $sClass, $sAttCode, $iActionCode, $oInstanceSet);
		}

		// this module is forbidden for non admins
		if (MetaModel::HasCategory($sClass, 'addon/userrights')) return UR_ALLOWED_NO;

		// the rest is allowed
		return UR_ALLOWED_YES;


	}

	protected static $m_aAdmins = array();
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

	protected static $m_aPortalUsers = array();
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

	public static function GetAllowedPortals()
    {
        $aAllowedPortals = array();
        $aPortalsConf = PortalDispatcherData::GetData();
        $aDispatchers = array();
        foreach ($aPortalsConf as $sPortalId => $aConf)
        {
            $sHandlerClass = $aConf['handler'];
            $aDispatchers[$sPortalId] = new $sHandlerClass($sPortalId);
        }

        foreach ($aDispatchers as $sPortalId => $oDispatcher)
        {
            if ($oDispatcher->IsUserAllowed())
            {
                $aAllowedPortals[] = array(
                    'id' => $sPortalId,
                    'label' => $oDispatcher->GetLabel(),
                    'url' => $oDispatcher->GetUrl(),
                );
            }
        }
        return $aAllowedPortals;
    }

    public static function ListProfiles($oUser = null)
	{
		if (is_null($oUser))
		{
			$oUser = self::$m_oUser;
		}
		if ($oUser === null)
		{
			// Not logged in: no profile at all
			$aProfiles = array();
		}
		elseif ((self::$m_oUser !== null) && ($oUser->GetKey() == self::$m_oUser->GetKey()))
		{
			// Data about the current user can be found into the session data
			if (array_key_exists('profile_list', $_SESSION))
			{
				$aProfiles = $_SESSION['profile_list'];
			}
		}

		if (!isset($aProfiles))
		{
			$aProfiles = self::$m_oAddOn->ListProfiles($oUser);
		}
		return $aProfiles;
	}

	/**
	 * @param string $sProfileName Profile name to search for
	 * @param User|null $oUser
	 *
	 * @return bool
	 */
	public static function HasProfile($sProfileName, $oUser = null)
	{
		$bRet = in_array($sProfileName, self::ListProfiles($oUser));
		return $bRet;
	}

	/**
	 * Reset cached data
	 * @param Bool Reset admin cache as well
	 * @return void
	 */
	public static function FlushPrivileges($bResetAdminCache = false)
	{
		if ($bResetAdminCache)
		{
			self::$m_aAdmins = array();
			self::$m_aPortalUsers = array();
		}
		if (!isset($_SESSION) && !utils::IsModeCLI())
		{
			session_name('itop-'.md5(APPROOT));
			session_start();
		}
		self::_ResetSessionCache();
		if (self::$m_oAddOn)
		{
			self::$m_oAddOn->FlushPrivileges();
		}
	}

	static $m_aCacheUsers;

	/**
	 * Find a user based on its login and its type of authentication
	 *
	 * @param string $sLogin Login/identifier of the user
	 * @param string $sAuthentication Type of authentication used: internal|external|any
	 * @param bool $bAllowDisabledUsers Whether or not to retrieve disabled users (status != enabled)
	 *
	 * @return User The found user or null
	 * @throws \OQLException
	 */
	protected static function FindUser($sLogin, $sAuthentication = 'any', $bAllowDisabledUsers = false)
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
				if (!$bAllowDisabledUsers)
				{
					$oSearch->AddCondition('status', 'enabled');
				}
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

	public static function _InitSessionCache()
	{
		// Cache data about the current user into the session
		if (isset($_SESSION))
		{
			$_SESSION['profile_list'] = self::ListProfiles();
		}

		$oConfig = MetaModel::GetConfig();
		$bSessionIdRegeneration = $oConfig->Get('regenerate_session_id_enabled');
		if ($bSessionIdRegeneration)
		{
			// Protection against session fixation/injection: generate a new session id.

			// Alas a PHP bug (technically a bug in the memcache session handler, https://bugs.php.net/bug.php?id=71187)
			// causes session_regenerate_id to fail with a catchable fatal error in PHP 7.0 if the session handler is memcache(d).
			// The bug has been fixed in PHP 7.2, but in case session_regenerate_id()
			// fails we just silently ignore the error and keep the same session id...
			$old_error_handler = set_error_handler(array(__CLASS__, 'VoidErrorHandler'));
			session_regenerate_id(true);
			if ($old_error_handler !== null) {
				set_error_handler($old_error_handler);
			}
		}
	}

	public static function _ResetSessionCache()
	{
		if (isset($_SESSION['profile_list']))
		{
			unset($_SESSION['profile_list']);
		}
		if (isset($_SESSION['archive_allowed']))
		{
			unset($_SESSION['archive_allowed']);
		}
	}
	
	/**
	 * Fake error handler to silently discard fatal errors
	 * @param int $iErrNo
	 * @param string $sErrStr
	 * @param string $sErrFile
	 * @param int $iErrLine
	 * @return boolean
	 */
	public static function VoidErrorHandler($iErrno, $sErrStr, $sErrFile, $iErrLine)
	{
		return true; // Ignore the error
	}

	/**
	 * @return null|array The last login/result (null if none has failed) the array has this structure : array('sName' => $sName, 'bSuccess' => $bSuccess);
	 */
	public static function GetLastLoginStatus()
	{
		return self::$m_sLastLoginStatus;
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
	
	public function __construct(DBSearch $oFilter, $iActionCode)
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
	
	public function __construct(DBSearch $oFilter, $sState, $iStimulusCode)
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

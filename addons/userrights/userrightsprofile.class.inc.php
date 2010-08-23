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
 * UserRightsProfile
 * User management Module, basing the right on profiles and a matrix (similar to UserRightsMatrix, but profiles and other decorations have been added) 
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

define('ADMIN_PROFILE_ID', 1);

class UserRightsBaseClass extends cmdbAbstractObject
{
	// Whenever something changes, reload the privileges
	
	public function DBInsertTracked(CMDBChange $oChange)
	{
		parent::DBInsertTracked($oChange);
		UserRights::FlushPrivileges();
	}

	public function DBUpdateTracked(CMDBChange $oChange)
	{
		parent::DBUpdateTracked($oChange);
		UserRights::FlushPrivileges();
	}

	public function DBDeleteTracked(CMDBChange $oChange)
	{
		parent::DBDeleteTracked($oChange);
		UserRights::FlushPrivileges();
	}
}




class URP_Profiles extends UserRightsBaseClass
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "addon/userrights",
			"key_type" => "autoincrement",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_urp_profiles",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		//MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeString("name", array("allowed_values"=>null, "sql"=>"name", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("description", array("allowed_values"=>null, "sql"=>"description", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));

		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("user_list", array("linked_class"=>"URP_UserProfile", "ext_key_to_me"=>"profileid", "ext_key_to_remote"=>"userid", "allowed_values"=>null, "count_min"=>1, "count_max"=>0, "depends_on"=>array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('name', 'description', 'user_list')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('description')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('name')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
	}

	function GetGrantAsHtml($oUserRights, $sClass, $sAction)
	{
		$oGrant = $oUserRights->GetProfileActionGrant($this->GetKey(), $sClass, $sAction);
		if (is_object($oGrant) && ($oGrant->Get('permission') == 'yes')) 
		{
			return '<span style="background-color: #ddffdd;">'.Dict::S('UI:UserManagement:ActionAllowed:Yes').'</span>';
		}
		else
		{
			return '<span style="background-color: #ffdddd;">'.Dict::S('UI:UserManagement:ActionAllowed:No').'</span>';
		}
	}
	
	function DoShowGrantSumary($oPage)
	{
		if ($this->GetName() == "Administrator")
		{
			// Looks dirty, but ok that's THE ONE
			$oPage->p(Dict::S('UI:UserManagement:AdminProfile+'));
			return;
		}

		// Note: for sure, we assume that the instance is derived from UserRightsProfile
		$oUserRights = UserRights::GetModuleInstance();
	
		$aDisplayData = array();
		foreach (MetaModel::GetClasses('bizmodel') as $sClass)
		{
			// Skip non instantiable classes
			if (MetaModel::IsAbstract($sClass)) continue;

			$aStimuli = array();
			foreach (MetaModel::EnumStimuli($sClass) as $sStimulusCode => $oStimulus)
			{
				$oGrant = $oUserRights->GetClassStimulusGrant($this->GetKey(), $sClass, $sStimulusCode);
				if (is_object($oGrant) && ($oGrant->Get('permission') == 'yes'))
				{ 
					$aStimuli[] = '<span title="'.$sStimulusCode.': '.htmlentities($oStimulus->GetDescription()).'">'.htmlentities($oStimulus->GetLabel()).'</span>';
				}
			}
			$sStimuli = implode(', ', $aStimuli);
			
			$aDisplayData[] = array(
				'class' => MetaModel::GetName($sClass),
				'read' => $this->GetGrantAsHtml($oUserRights, $sClass, 'Read'),
				'bulkread' => $this->GetGrantAsHtml($oUserRights, $sClass, 'Bulk Read'),
				'write' => $this->GetGrantAsHtml($oUserRights, $sClass, 'Modify'),
				'bulkwrite' => $this->GetGrantAsHtml($oUserRights, $sClass, 'Bulk Modify'),
				'delete' => $this->GetGrantAsHtml($oUserRights, $sClass, 'Delete'),
				'bulkdelete' => $this->GetGrantAsHtml($oUserRights, $sClass, 'Bulk Delete'),
				'stimuli' => $sStimuli,
			);
		}
	
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
		parent::DisplayBareRelations($oPage);
		if (!$bEditMode)
		{
			$oPage->SetCurrentTab(Dict::S('UI:UserManagement:GrantMatrix'));
			$this->DoShowGrantSumary($oPage);		
		}
	}
}



class URP_UserProfile extends UserRightsBaseClass
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "addon/userrights",
			"key_type" => "autoincrement",
			"name_attcode" => "userid",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_urp_userprofile",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		//MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeExternalKey("userid", array("targetclass"=>"User", "jointype"=> "", "allowed_values"=>null, "sql"=>"userid", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("userlogin", array("allowed_values"=>null, "extkey_attcode"=> 'userid', "target_attcode"=>"login")));

		MetaModel::Init_AddAttribute(new AttributeExternalKey("profileid", array("targetclass"=>"URP_Profiles", "jointype"=> "", "allowed_values"=>null, "sql"=>"profileid", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("profile", array("allowed_values"=>null, "extkey_attcode"=> 'profileid', "target_attcode"=>"name")));

		MetaModel::Init_AddAttribute(new AttributeString("reason", array("allowed_values"=>null, "sql"=>"description", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('userid', 'profileid', 'reason')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('profileid', 'reason')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('userid', 'profileid')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('userid', 'profileid')); // Criteria of the advanced search form
	}

	public function GetName()
	{
		return Dict::Format('UI:UserManagement:LinkBetween_User_And_Profile', $this->Get('userlogin'), $this->Get('profile'));
	}
}

class URP_UserOrg extends UserRightsBaseClass
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "addon/userrights",
			"key_type" => "autoincrement",
			"name_attcode" => "userid",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_urp_userorg",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		//MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeExternalKey("userid", array("targetclass"=>"User", "jointype"=> "", "allowed_values"=>null, "sql"=>"userid", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("userlogin", array("allowed_values"=>null, "extkey_attcode"=> 'userid', "target_attcode"=>"login")));

		MetaModel::Init_AddAttribute(new AttributeExternalKey("allowed_org_id", array("targetclass"=>"Organization", "jointype"=> "", "allowed_values"=>null, "sql"=>"allowed_org_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("allowed_org_name", array("allowed_values"=>null, "extkey_attcode"=> 'allowed_org_id', "target_attcode"=>"name")));

		MetaModel::Init_AddAttribute(new AttributeString("reason", array("allowed_values"=>null, "sql"=>"reason", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('userid', 'allowed_org_id', 'reason')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('allowed_org_id', 'reason')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('userid', 'allowed_org_id')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('userid', 'allowed_org_id')); // Criteria of the advanced search form
	}

	public function GetName()
	{
		return Dict::Format('UI:UserManagement:LinkBetween_User_And_Org', $this->Get('userlogin'), $this->Get('allowed_org_name'));
	}
}


class URP_ActionGrant extends UserRightsBaseClass
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "addon/userrights",
			"key_type" => "autoincrement",
			"name_attcode" => "profileid",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_urp_grant_actions",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		//MetaModel::Init_InheritAttributes();

		// Common to all grant classes (could be factorized by class inheritence, but this has to be benchmarked)
		MetaModel::Init_AddAttribute(new AttributeExternalKey("profileid", array("targetclass"=>"URP_Profiles", "jointype"=> "", "allowed_values"=>null, "sql"=>"profileid", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("profile", array("allowed_values"=>null, "extkey_attcode"=> 'profileid', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeClass("class", array("class_category"=>"", "more_values"=>"", "sql"=>"class", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("permission", array("allowed_values"=>new ValueSetEnum('yes,no'), "sql"=>"permission", "default_value"=>"yes", "is_null_allowed"=>false, "depends_on"=>array())));

		MetaModel::Init_AddAttribute(new AttributeString("action", array("allowed_values"=>null, "sql"=>"action", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('profileid', 'class', 'permission', 'action')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('class', 'permission', 'action')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('profileid', 'class', 'permission', 'action')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('profileid', 'class', 'permission', 'action')); // Criteria of the advanced search form
	}
}


class URP_StimulusGrant extends UserRightsBaseClass
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "addon/userrights",
			"key_type" => "autoincrement",
			"name_attcode" => "profileid",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_urp_grant_stimulus",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		//MetaModel::Init_InheritAttributes();

		// Common to all grant classes (could be factorized by class inheritence, but this has to be benchmarked)
		MetaModel::Init_AddAttribute(new AttributeExternalKey("profileid", array("targetclass"=>"URP_Profiles", "jointype"=> "", "allowed_values"=>null, "sql"=>"profileid", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("profile", array("allowed_values"=>null, "extkey_attcode"=> 'profileid', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeClass("class", array("class_category"=>"", "more_values"=>"", "sql"=>"class", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("permission", array("allowed_values"=>new ValueSetEnum('yes,no'), "sql"=>"permission", "default_value"=>"yes", "is_null_allowed"=>false, "depends_on"=>array())));

		MetaModel::Init_AddAttribute(new AttributeString("stimulus", array("allowed_values"=>null, "sql"=>"action", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('profileid', 'class', 'permission', 'stimulus')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('class', 'permission', 'stimulus')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('profileid', 'class', 'permission', 'stimulus')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('profileid', 'class', 'permission', 'stimulus')); // Criteria of the advanced search form
	}
}


class URP_AttributeGrant extends UserRightsBaseClass
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "addon/userrights",
			"key_type" => "autoincrement",
			"name_attcode" => "actiongrantid",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_urp_grant_attributes",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		//MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeExternalKey("actiongrantid", array("targetclass"=>"URP_ActionGrant", "jointype"=> "", "allowed_values"=>null, "sql"=>"actiongrantid", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("attcode", array("allowed_values"=>null, "sql"=>"attcode", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('actiongrantid', 'attcode')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('attcode')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('actiongrantid', 'attcode')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('actiongrantid', 'attcode')); // Criteria of the advanced search form
	}
}




class UserRightsProfile extends UserRightsAddOnAPI
{
	static public $m_aActionCodes = array(
		UR_ACTION_READ => 'read',
		UR_ACTION_MODIFY => 'modify',
		UR_ACTION_DELETE => 'delete',
		UR_ACTION_BULK_READ => 'bulk read',
		UR_ACTION_BULK_MODIFY => 'bulk modify',
		UR_ACTION_BULK_DELETE => 'bulk delete',
	);

	// Installation: create the very first user
	public function CreateAdministrator($sAdminUser, $sAdminPwd, $sLanguage = 'EN US')
	{
		// Create a change to record the history of the User object
		$oChange = MetaModel::NewObject("CMDBChange");
		$oChange->Set("date", time());
		$oChange->Set("userinfo", "Initialization");
		$iChangeId = $oChange->DBInsert();

		$oOrg = new Organization();
		$oOrg->Set('name', 'My Company/Department');
		$oOrg->Set('code', 'SOMECODE');
//		$oOrg->Set('status', 'implementation');
		//$oOrg->Set('parent_id', xxx);
		$iOrgId = $oOrg->DBInsertTrackedNoReload($oChange);

		$oContact = new Person();
		$oContact->Set('name', 'My last name');
		//$oContact->Set('first_name', 'My first name');
		//$oContact->Set('status', 'available');
		$oContact->Set('org_id', $iOrgId);
		$oContact->Set('email', 'my.email@foo.org');
		//$oContact->Set('phone', '');
		//$oContact->Set('location_id', $iLocationId);
		//$oContact->Set('employee_number', '');
		$iContactId = $oContact->DBInsertTrackedNoReload($oChange);
		
		$oUser = new UserLocal();
		$oUser->Set('login', $sAdminUser);
		$oUser->Set('password', $sAdminPwd);
		$oUser->Set('contactid', $iContactId);
		$oUser->Set('language', $sLanguage); // Language was chosen during the installation
		$iUserId = $oUser->DBInsertTrackedNoReload($oChange);
		
		// Add this user to the very specific 'admin' profile
		$oUserProfile = new URP_UserProfile();
		$oUserProfile->Set('userid', $iUserId);
		$oUserProfile->Set('profileid', ADMIN_PROFILE_ID);
		$oUserProfile->Set('reason', 'By definition, the administrator must have the administrator profile');
		$oUserProfile->DBInsertTrackedNoReload($oChange);
		return true;
	}

	public function IsAdministrator($oUser)
	{
		if (in_array($oUser->GetKey(), $this->m_aAdmins))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function Setup()
	{
		SetupProfiles::ComputeITILProfiles();
		//SetupProfiles::ComputeBasicProfiles();

		SetupProfiles::DoCreateProfiles();
		return true;
	}

	public function Init()
	{
		MetaModel::RegisterPlugin('userrights', 'ACbyProfile', array($this, 'CacheData'));
	}

	protected $m_aProfiles = array(); // id -> object
	protected $m_aUserProfiles = array(); // userid,profileid -> object
	protected $m_aUserOrgs = array(); // userid,orgid -> object

	protected $m_aAdmins = array(); // id of users being linked to the well-known admin profile

	protected $m_aClassActionGrants = array(); // profile, class, action -> permission
	protected $m_aClassStimulusGrants = array(); // profile, class, stimulus -> permission

	public function CacheData()
	{
		// Could be loaded in a shared memory (?)

		$oProfileSet = new DBObjectSet(DBObjectSearch::FromOQL_AllData("SELECT URP_Profiles"));
		$this->m_aProfiles = array(); 
		while ($oProfile = $oProfileSet->Fetch())
		{
			$this->m_aProfiles[$oProfile->GetKey()] = $oProfile; 
		}

		$oUserProfileSet = new DBObjectSet(DBObjectSearch::FromOQL_AllData("SELECT URP_UserProfile"));
		$this->m_aUserProfiles = array();
		$this->m_aAdmins = array();
		while ($oUserProfile = $oUserProfileSet->Fetch())
		{
			$this->m_aUserProfiles[$oUserProfile->Get('userid')][$oUserProfile->Get('profileid')] = $oUserProfile;
			if ($oUserProfile->Get('profileid') == ADMIN_PROFILE_ID)
			{
				$this->m_aAdmins[] = $oUserProfile->Get('userid');
			}
		}

		$oUserOrgSet = new DBObjectSet(DBObjectSearch::FromOQL_AllData("SELECT URP_UserOrg"));
		$this->m_aUserOrgs = array();
		while ($oUserOrg = $oUserOrgSet->Fetch())
		{
			$this->m_aUserOrgs[$oUserOrg->Get('userid')][$oUserOrg->Get('allowed_org_id')] = $oUserOrg;
		}
/*
		echo "<pre>\n";
		print_r($this->m_aProfiles);
		print_r($this->m_aUserProfiles);
		print_r($this->m_aUserOrgs);
		echo "</pre>\n";
exit;
*/

		return true;
	}

	public function GetSelectFilter($oUser, $sClass)
	{
		$aObjectPermissions = $this->GetUserActionGrant($oUser, $sClass, UR_ACTION_READ);
		if ($aObjectPermissions['permission'] == UR_ALLOWED_NO)
		{
			return false;
		}

		// Determine how to position the objects of this class
		//
		if ($sClass == 'Organization')
		{
			$sAttCode = 'id';
		}
		elseif(MetaModel::IsValidAttCode($sClass, 'org_id'))
		{
			$sAttCode = 'org_id';
		}
		else
		{
			// The objects of this class are not positioned in this dimension
			// All of them are visible
			return true;
		}
		$oExpression = new FieldExpression($sAttCode, $sClass);

		// Position the user
		//
		@$aUserOrgs = $this->m_aUserOrgs[$oUser->GetKey()];
		if (!isset($aUserOrgs) || count($aUserOrgs) == 0)
		{
			// No position means 'Everywhere'
			return true;
		}

		$aIds = array_keys($aUserOrgs);
		$oListExpr = ListExpression::FromScalars($aIds);
		$oCondition = new BinaryExpression($oExpression, 'IN', $oListExpr);

		$oFilter  = new DBObjectSearch($sClass);
		$oFilter->AddConditionExpression($oCondition);
		return $oFilter;
	}

	// This verb has been made public to allow the development of an accurate feedback for the current configuration
	public function GetProfileActionGrant($iProfile, $sClass, $sAction)
	{
		if (isset($this->m_aClassActionGrants[$iProfile][$sClass][$sAction]))
		{
			return $this->m_aClassActionGrants[$iProfile][$sClass][$sAction];
		}

		// Get the permission for this profile/class/action
		$oSearch = DBObjectSearch::FromOQL_AllData("SELECT URP_ActionGrant WHERE class = :class AND action = :action AND profileid = :profile AND permission = 'yes'");
		$oSet = new DBObjectSet($oSearch, array(), array('class'=>$sClass, 'action'=>$sAction, 'profile'=>$iProfile));
		if ($oSet->Count() >= 1)
		{
			$oGrantRecord = $oSet->Fetch();
		}
		else
		{
			$sParentClass = MetaModel::GetParentPersistentClass($sClass);
			if (empty($sParentClass))
			{
				$oGrantRecord = null;
			}
			else
			{
				// Recursively look for the grant record in the class hierarchy
				$oGrantRecord = $this->GetProfileActionGrant($iProfile, $sParentClass, $sAction);
			}
		}

		$this->m_aClassActionGrants[$iProfile][$sClass][$sAction] = $oGrantRecord;
		return $oGrantRecord;
	}

	protected function GetUserActionGrant($oUser, $sClass, $iActionCode)
	{
		// load and cache permissions for the current user on the given class
		//
		$aTest = @$this->m_aObjectActionGrants[$oUser->GetKey()][$sClass][$iActionCode];
		if (is_array($aTest)) return $aTest;

		$sAction = self::$m_aActionCodes[$iActionCode];

		$iInstancePermission = UR_ALLOWED_NO;
		$aAttributes = array();
		if (isset($this->m_aUserProfiles[$oUser->GetKey()]))
		{
			foreach($this->m_aUserProfiles[$oUser->GetKey()] as $iProfile => $oProfile)
			{
				$oGrantRecord = $this->GetProfileActionGrant($iProfile, $sClass, $sAction);
				if (is_null($oGrantRecord))
				{
					continue; // loop to the next profile
				}
				else
				{
					$iInstancePermission = UR_ALLOWED_YES;
	
					// update the list of attributes with those allowed for this profile
					//
					$oSearch = DBObjectSearch::FromOQL_AllData("SELECT URP_AttributeGrant WHERE actiongrantid = :actiongrantid");
					$oSet = new DBObjectSet($oSearch, array(), array('actiongrantid' => $oGrantRecord->GetKey()));
					$aProfileAttributes = $oSet->GetColumnAsArray('attcode', false);
					if (count($aProfileAttributes) == 0)
					{
						$aAllAttributes = array_keys(MetaModel::ListAttributeDefs($sClass));
						$aAttributes = array_merge($aAttributes, $aAllAttributes);
					}
					else
					{
						$aAttributes = array_merge($aAttributes, $aProfileAttributes);
					}
				}
			}
		}

		$aRes = array(
			'permission' => $iInstancePermission,
			'attributes' => $aAttributes,
		);
		$this->m_aObjectActionGrants[$oUser->GetKey()][$sClass][$iActionCode] = $aRes;
		return $aRes;
	}
	
	public function IsActionAllowed($oUser, $sClass, $iActionCode, $oInstanceSet = null)
	{
		if (is_null($oInstanceSet))
		{
			$aObjectPermissions = $this->GetUserActionGrant($oUser, $sClass, $iActionCode);
			return $aObjectPermissions['permission'];
		}

		$oInstanceSet->Rewind();
		while($oObject = $oInstanceSet->Fetch())
		{
			$aObjectPermissions = $this->GetUserActionGrant($oUser, get_class($oObject), $iActionCode);

			$iInstancePermission = $aObjectPermissions['permission'];
			if (isset($iGlobalPermission))
			{
				if ($iInstancePermission != $iGlobalPermission)
				{
					$iGlobalPermission = UR_ALLOWED_DEPENDS;
					break;
				}
			}
			else
			{
				$iGlobalPermission = $iInstancePermission;
			}
		}
		$oInstanceSet->Rewind();

		if (isset($iGlobalPermission))
		{
			return $iGlobalPermission;
		}
		else
		{
			return UR_ALLOWED_NO;
		}
	}

	public function IsActionAllowedOnAttribute($oUser, $sClass, $sAttCode, $iActionCode, $oInstanceSet = null)
	{
		if (is_null($oInstanceSet))
		{
			$aObjectPermissions = $this->GetUserActionGrant($oUser, $sClass, $iActionCode);
			$aAttributes = $aObjectPermissions['attributes'];
			if (in_array($sAttCode, $aAttributes))
			{
				return $aObjectPermissions['permission'];
			}
			else
			{
				return UR_ALLOWED_NO;
			}
		}

		$oInstanceSet->Rewind();
		while($oObject = $oInstanceSet->Fetch())
		{
			$aObjectPermissions = $this->GetUserActionGrant($oUser, get_class($oObject), $iActionCode);
			$aAttributes = $aObjectPermissions['attributes'];
			if (in_array($sAttCode, $aAttributes))
			{
				$iInstancePermission = $aObjectPermissions['permission'];
			}
			else
			{
				$iInstancePermission = UR_ALLOWED_NO; 
			}

			if (isset($iGlobalPermission))
			{
				if ($iInstancePermission != $iGlobalPermission)
				{
					$iGlobalPermission = UR_ALLOWED_DEPENDS;
				}
			}
			else
			{
				$iGlobalPermission = $iInstancePermission;
			}
		}
		$oInstanceSet->Rewind();

		if (isset($iGlobalPermission))
		{
			return $iGlobalPermission;
		}
		else
		{
			return UR_ALLOWED_NO;
		}
	}

	// This verb has been made public to allow the development of an accurate feedback for the current configuration
	public function GetClassStimulusGrant($iProfile, $sClass, $sStimulusCode)
	{
		if (isset($this->m_aClassStimulusGrants[$iProfile][$sClass][$sStimulusCode]))
		{
			return $this->m_aClassStimulusGrants[$iProfile][$sClass][$sStimulusCode];
		}

		// Get the permission for this profile/class/stimulus
		$oSearch = DBObjectSearch::FromOQL_AllData("SELECT URP_StimulusGrant WHERE class = :class AND stimulus = :stimulus AND profileid = :profile AND permission = 'yes'");
		$oSet = new DBObjectSet($oSearch, array(), array('class'=>$sClass, 'stimulus'=>$sStimulusCode, 'profile'=>$iProfile));
		if ($oSet->Count() >= 1)
		{
			$oGrantRecord = $oSet->Fetch();
		}
		else
		{
			$oGrantRecord = null;
		}

		$this->m_aClassStimulusGrants[$iProfile][$sClass][$sStimulusCode] = $oGrantRecord;
		return $oGrantRecord;
	}

	public function IsStimulusAllowed($oUser, $sClass, $sStimulusCode, $oInstanceSet = null)
	{
		// Note: this code is VERY close to the code of IsActionAllowed()

		if (is_null($oInstanceSet))
		{
			$iInstancePermission = UR_ALLOWED_NO;
			if (isset($this->m_aUserProfiles[$oUser->GetKey()]))
			{
				foreach($this->m_aUserProfiles[$oUser->GetKey()] as $iProfile => $oProfile)
				{
					$oGrantRecord = $this->GetClassStimulusGrant($iProfile, $sClass, $sStimulusCode);
					if (!is_null($oGrantRecord))
					{
						// no need to fetch the record, we've requested the records having permission = 'yes'
						$iInstancePermission = UR_ALLOWED_YES;
					}
				}
			}
			return $iInstancePermission;
		}

		$oInstanceSet->Rewind();
		while($oObject = $oInstanceSet->Fetch())
		{
			$iInstancePermission = UR_ALLOWED_NO;
			if (isset($this->m_aUserProfiles[$oUser->GetKey()]))
			{
				foreach($this->m_aUserProfiles[$oUser->GetKey()] as $iProfile => $oProfile)
				{
					$oGrantRecord = $this->GetClassStimulusGrant($iProfile, get_class($oObject), $sStimulusCode);
					if (!is_null($oGrantRecord))
					{
						// no need to fetch the record, we've requested the records having permission = 'yes'
						$iInstancePermission = UR_ALLOWED_YES;
					}
				}
			}
			if (isset($iGlobalPermission))
			{
				if ($iInstancePermission != $iGlobalPermission)
				{
					$iGlobalPermission = UR_ALLOWED_DEPENDS;
				}
			}
			else
			{
				$iGlobalPermission = $iInstancePermission;
			}
		}
		$oInstanceSet->Rewind();

		if (isset($iGlobalPermission))
		{
			return $iGlobalPermission;
		}
		else
		{
			return UR_ALLOWED_NO;
		}
	}

	public function FlushPrivileges()
	{
		$this->CacheData();
	}
}

//
// Create simple profiles into our user management model:
// - administrator
// - readers
// - contributors
//
class SetupProfiles
{
	protected static $m_aActions = array(
		UR_ACTION_READ => 'Read',
		UR_ACTION_MODIFY => 'Modify',
		UR_ACTION_DELETE => 'Delete',
		UR_ACTION_BULK_READ => 'Bulk Read',
		UR_ACTION_BULK_MODIFY => 'Bulk Modify',
		UR_ACTION_BULK_DELETE => 'Bulk Delete',
	);

	// Note: It is possible to specify the same class in several modules
	//
	protected static $m_aModules = array();
	protected static $m_aProfiles = array();

	
	protected static function DoCreateActionGrant($iProfile, $iAction, $sClass, $bPermission = true)
	{
		$oNewObj = MetaModel::NewObject("URP_ActionGrant");
		$oNewObj->Set('profileid', $iProfile);
		$oNewObj->Set('permission', $bPermission ? 'yes' : 'no');
		$oNewObj->Set('class', $sClass);
		$oNewObj->Set('action', self::$m_aActions[$iAction]);
		$iId = $oNewObj->DBInsertNoReload();
		return $iId;
	}
	
	protected static function DoCreateStimulusGrant($iProfile, $sStimulusCode, $sClass)
	{
		$oNewObj = MetaModel::NewObject("URP_StimulusGrant");
		$oNewObj->Set('profileid', $iProfile);
		$oNewObj->Set('permission', 'yes');
		$oNewObj->Set('class', $sClass);
		$oNewObj->Set('stimulus', $sStimulusCode);
		$iId = $oNewObj->DBInsertNoReload();
		return $iId;
	}
	
	protected static function DoCreateAdminProfile()
	{
		$oNewObj = MetaModel::NewObject("URP_Profiles");
		$oNewObj->Set('name', 'Administrator');
		$oNewObj->Set('description', 'Has the rights on everything (bypassing any control)');
		$iNewId = $oNewObj->DBInsertNoReload();
		if ($iNewId != ADMIN_PROFILE_ID)
		{
			throw new CoreException('Admin profile could not be created with its standard id', array('requested'=>ADMIN_PROFILE_ID, 'obtained'=>$iNewId));
		}
	}

	protected static function DoCreateOneProfile($sName, $aProfileData)
	{
		$sDescription = $aProfileData['description'];
		if (strlen(trim($aProfileData['write_modules'])) == 0)
		{
			$aWriteModules = array(); 
		}
		else
		{
			$aWriteModules = explode(',', trim($aProfileData['write_modules']));
		}
		$aStimuli = $aProfileData['stimuli'];
		
		$oNewObj = MetaModel::NewObject("URP_Profiles");
		$oNewObj->Set('name', $sName);
		$oNewObj->Set('description', $sDescription);
		$iProfile = $oNewObj->DBInsertNoReload();
	
		// Grant read rights for everything
		//
		foreach (MetaModel::GetClasses('bizmodel') as $sClass)
		{
			// Skip non instantiable classes
			if (MetaModel::IsAbstract($sClass)) continue;

			self::DoCreateActionGrant($iProfile, UR_ACTION_READ, $sClass);
			self::DoCreateActionGrant($iProfile, UR_ACTION_BULK_READ, $sClass);
		}
	
		// Grant write for given modules
		// Start by compiling the information, because some modules may overlap
		$aWriteableClasses = array();
		foreach ($aWriteModules as $sModule)
		{
			//$oPage->p('Granting write access for the module"'.$sModule.'" - '.count(self::$m_aModules[$sModule]).' classes');
			foreach (self::$m_aModules[$sModule] as $sClass)
			{
				$aWriteableClasses[$sClass] = true;
			}
		}
		foreach ($aWriteableClasses as $sClass => $foo)
		{
			// Skip non instantiable classes
			if (MetaModel::IsAbstract($sClass)) continue;

			if (!MetaModel::IsValidClass($sClass))
			{
				throw new CoreException("Invalid class name '$sClass'");
			}
			self::DoCreateActionGrant($iProfile, UR_ACTION_MODIFY, $sClass);
			self::DoCreateActionGrant($iProfile, UR_ACTION_DELETE, $sClass);
			self::DoCreateActionGrant($iProfile, UR_ACTION_BULK_MODIFY, $sClass);
			// By default, do not allow bulk deletion operations for standard users
			// self::DoCreateActionGrant($iProfile, UR_ACTION_BULK_DELETE, $sClass);
		}
		
		// Grant stimuli for given classes
		foreach ($aStimuli as $sClass => $sAllowedStimuli)
		{
			if (!MetaModel::IsValidClass($sClass))
			{
				// Could be a class defined in a module that wasn't installed
				continue;
				//throw new CoreException("Invalid class name '$sClass'");
			}

			if ($sAllowedStimuli == 'any')
			{
				$aAllowedStimuli = array_keys(MetaModel::EnumStimuli($sClass));
			}
			elseif ($sAllowedStimuli == 'none')
			{
				$aAllowedStimuli = array();
			}
			else
			{
				$aAllowedStimuli = explode(',', $sAllowedStimuli);
			}
			foreach ($aAllowedStimuli as $sStimulusCode)
			{
				self::DoCreateStimulusGrant($iProfile, $sStimulusCode, $sClass);
			}
		}
	}
	
	public static function DoCreateProfiles()
	{
		self::DoCreateAdminProfile();

		foreach(self::$m_aProfiles as $sName => $aProfileData)
		{
			self::DoCreateOneProfile($sName, $aProfileData);
		}
	}

	public static function ComputeBasicProfiles()
	{
		// In this profiling scheme, one single module represents all the classes
		//
		self::$m_aModules = array(
			'UserData' => MetaModel::GetClasses('bizmodel'),
		);

		self::$m_aProfiles = array(
			'Reader' => array(
				'description' => 'Person having a ready-only access to the data',
				'write_modules' => '',
				'stimuli' => array(
				),
			),
			'Writer' => array(
				'description' => 'Contributor to the contents (read + write access)',
				'write_modules' => 'UserData',
				'stimuli' => array(
					// any class => 'any'
				),
			),
		);
	}

	public static function ComputeITILProfiles()
	{
		// In this profiling scheme, modules are based on ITIL recommendations
		//
		self::$m_aModules = array(
			/*
			'WriteModule' => array(
				'someclass',
				'anotherclass',
			),
			*/
			'General' => MetaModel::GetClasses('structure'),
			'Documentation' => MetaModel::GetClasses('documentation'),
			'Configuration' => MetaModel::GetClasses('configmgmt'),
			'Incident' => MetaModel::GetClasses('incidentmgmt'),
			'Problem' => MetaModel::GetClasses('problemmgmt'),
			'Change' => MetaModel::GetClasses('changemgmt'),
			'Service' => MetaModel::GetClasses('servicemgmt'),
			'Call' => MetaModel::GetClasses('requestmgmt'),
			'KnownError' => MetaModel::GetClasses('knownerrormgmt'),
		);
		
		self::$m_aProfiles = array(
			'Configuration Manager' => array(
				'description' => 'Person in charge of the documentation of the managed CIs',
				'write_modules' => 'General,Documentation,Configuration',
				'stimuli' => array(
					//'bizServer' => 'none',
					//'bizContract' => 'none',
					//'bizIncidentTicket' => 'none',
					//'bizChangeTicket' => 'any',
				),
			),
			'Service Desk Agent' => array(
				'description' => 'Person in charge of creating incident reports',
				'write_modules' => 'Incident,Call',
				'stimuli' => array(
					'Incident' => 'ev_assign',
					'UserRequest' => 'ev_assign',
				),
			),
			'Support Agent' => array(
				'description' => 'Person analyzing and solving the current incidents or problems',
				'write_modules' => 'Incident,Problem,KnownError',
				'stimuli' => array(
					'Incident' => 'ev_assign,ev_reassign,ev_resolve,ev_close',
					'UserRequest' => 'ev_assign,ev_reassign,ev_resolve,ev_close,ev_freeze',
				),
			),
			'Change Implementor' => array(
				'description' => 'Person executing the changes',
				'write_modules' => 'Change',
				'stimuli' => array(
					'NormalChange' => 'ev_plan,ev_replan,ev_implement,ev_monitor',
					'EmergencyChange' => 'ev_plan,ev_replan,ev_implement,ev_monitor',
					'RoutineChange' => 'ev_plan,ev_replan,ev_implement,ev_monitor',
				),
			),
			'Change Supervisor' => array(
				'description' => 'Person responsible for the overall change execution',
				'write_modules' => 'Change',
				'stimuli' => array(
					'NormalChange' => 'ev_validate,ev_reject,ev_assign,ev_reopen,ev_finish',
					'EmergencyChange' => 'ev_assign,ev_reopen,ev_finish',
					'RoutineChange' => 'ev_assign,ev_reopen,ev_finish',
				),
			),
			'Change Approver' => array(
				'description' => 'Person who could be impacted by some changes',
				'write_modules' => 'Change',
				'stimuli' => array(
					'NormalChange' => 'ev_approve,ev_notapprove',
					'EmergencyChange' => 'ev_approve,ev_notapprove',
					'RoutineChange' => 'none',
				),
			),
			'Service Manager' => array(
				'description' => 'Person responsible for the service delivered to the [internal] customer',
				'write_modules' => 'Service',
				'stimuli' => array(
				),
			),
			'Document author' => array(
				'description' => 'Any person who could contribute to documentation',
				'write_modules' => 'Documentation',
				'stimuli' => array(
				),
			),
		);
	}
}

UserRights::SelectModule('UserRightsProfile');

?>

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

define('ADMIN_PROFILE_NAME', 'Administrator');
define('PORTAL_PROFILE_NAME', 'Portal user');

class UserRightsBaseClassGUI extends cmdbAbstractObject
{
	// Whenever something changes, reload the privileges
	
	protected function AfterInsert()
	{
		UserRights::FlushPrivileges();
	}

	protected function AfterUpdate()
	{
		UserRights::FlushPrivileges();
	}

	protected function AfterDelete()
	{
		UserRights::FlushPrivileges();
	}
}

class UserRightsBaseClass extends DBObject
{
	// Whenever something changes, reload the privileges
	
	protected function AfterInsert()
	{
		UserRights::FlushPrivileges();
	}

	protected function AfterUpdate()
	{
		UserRights::FlushPrivileges();
	}

	protected function AfterDelete()
	{
		UserRights::FlushPrivileges();
	}
}




class URP_Profiles extends UserRightsBaseClassGUI
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

	protected $m_bCheckReservedNames = true;
	protected function DisableCheckOnReservedNames()
	{
		$this->m_bCheckReservedNames = false;
	}

	
	protected static $m_aActions = array(
		UR_ACTION_READ => 'Read',
		UR_ACTION_MODIFY => 'Modify',
		UR_ACTION_DELETE => 'Delete',
		UR_ACTION_BULK_READ => 'Bulk Read',
		UR_ACTION_BULK_MODIFY => 'Bulk Modify',
		UR_ACTION_BULK_DELETE => 'Bulk Delete',
	);

	protected static $m_aCacheActionGrants = null;
	protected static $m_aCacheStimulusGrants = null;
	protected static $m_aCacheProfiles = null;
	
	public static function DoCreateProfile($sName, $sDescription, $bReservedName = false)
	{
		if (is_null(self::$m_aCacheProfiles))
		{
			self::$m_aCacheProfiles = array();
			$oFilterAll = new DBObjectSearch('URP_Profiles');
			$oSet = new DBObjectSet($oFilterAll);
			while ($oProfile = $oSet->Fetch())
			{
				self::$m_aCacheProfiles[$oProfile->Get('name')] = $oProfile->GetKey();
			}
		}	

		$sCacheKey = $sName;
		if (isset(self::$m_aCacheProfiles[$sCacheKey]))
		{
			return self::$m_aCacheProfiles[$sCacheKey];
		}
		$oNewObj = MetaModel::NewObject("URP_Profiles");
		$oNewObj->Set('name', $sName);
		$oNewObj->Set('description', $sDescription);
		if ($bReservedName)
		{
			$oNewObj->DisableCheckOnReservedNames();			
		}
		$iId = $oNewObj->DBInsertNoReload();
		self::$m_aCacheProfiles[$sCacheKey] = $iId;	
		return $iId;
	}
	
	public static function DoCreateActionGrant($iProfile, $iAction, $sClass, $bPermission = true)
	{
		$sAction = self::$m_aActions[$iAction];
	
		if (is_null(self::$m_aCacheActionGrants))
		{
			self::$m_aCacheActionGrants = array();
			$oFilterAll = new DBObjectSearch('URP_ActionGrant');
			$oSet = new DBObjectSet($oFilterAll);
			while ($oGrant = $oSet->Fetch())
			{
				self::$m_aCacheActionGrants[$oGrant->Get('profileid').'-'.$oGrant->Get('action').'-'.$oGrant->Get('class')] = $oGrant->GetKey();
			}
		}	

		$sCacheKey = "$iProfile-$sAction-$sClass";
		if (isset(self::$m_aCacheActionGrants[$sCacheKey]))
		{
			return self::$m_aCacheActionGrants[$sCacheKey];
		}

		$oNewObj = MetaModel::NewObject("URP_ActionGrant");
		$oNewObj->Set('profileid', $iProfile);
		$oNewObj->Set('permission', $bPermission ? 'yes' : 'no');
		$oNewObj->Set('class', $sClass);
		$oNewObj->Set('action', $sAction);
		$iId = $oNewObj->DBInsertNoReload();
		self::$m_aCacheActionGrants[$sCacheKey] = $iId;	
		return $iId;
	}
	
	public static function DoCreateStimulusGrant($iProfile, $sStimulusCode, $sClass)
	{
		if (is_null(self::$m_aCacheStimulusGrants))
		{
			self::$m_aCacheStimulusGrants = array();
			$oFilterAll = new DBObjectSearch('URP_StimulusGrant');
			$oSet = new DBObjectSet($oFilterAll);
			while ($oGrant = $oSet->Fetch())
			{
				self::$m_aCacheStimulusGrants[$oGrant->Get('profileid').'-'.$oGrant->Get('stimulus').'-'.$oGrant->Get('class')] = $oGrant->GetKey();
			}
		}	

		$sCacheKey = "$iProfile-$sStimulusCode-$sClass";
		if (isset(self::$m_aCacheStimulusGrants[$sCacheKey]))
		{
			return self::$m_aCacheStimulusGrants[$sCacheKey];
		}
		$oNewObj = MetaModel::NewObject("URP_StimulusGrant");
		$oNewObj->Set('profileid', $iProfile);
		$oNewObj->Set('permission', 'yes');
		$oNewObj->Set('class', $sClass);
		$oNewObj->Set('stimulus', $sStimulusCode);
		$iId = $oNewObj->DBInsertNoReload();
		self::$m_aCacheStimulusGrants[$sCacheKey] = $iId;	
		return $iId;
	}
	
	/*
	* Create the built-in Administrator profile with its reserved name
	*/	
	public static function DoCreateAdminProfile()
	{
		self::DoCreateProfile(ADMIN_PROFILE_NAME, 'Has the rights on everything (bypassing any control)', true /* reserved name */);
	}

	/*
	* Overload the standard behavior to preserve reserved names
	*/	
	public function DoCheckToWrite()
	{
		parent::DoCheckToWrite();

		if ($this->m_bCheckReservedNames)
		{
			$aChanges = $this->ListChanges();
			if (array_key_exists('name', $aChanges))
			{
				if ($this->GetOriginal('name') == ADMIN_PROFILE_NAME)
				{
					$this->m_aCheckIssues[] = "The name of the Administrator profile must not be changed";
				}
				elseif ($this->Get('name') == ADMIN_PROFILE_NAME)
				{
					$this->m_aCheckIssues[] = ADMIN_PROFILE_NAME." is a reserved to the built-in Administrator profile";
				}
				elseif ($this->GetOriginal('name') == PORTAL_PROFILE_NAME)
				{
					$this->m_aCheckIssues[] = "The name of the User Portal profile must not be changed";
				}
				elseif ($this->Get('name') == PORTAL_PROFILE_NAME)
				{
					$this->m_aCheckIssues[] = PORTAL_PROFILE_NAME." is a reserved to the built-in User Portal profile";
				}
			}
		}
	}

	function GetGrantAsHtml($oUserRights, $sClass, $sAction)
	{
		$iGrant = $oUserRights->GetProfileActionGrant($this->GetKey(), $sClass, $sAction);
		if (!is_null($iGrant))
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
					$aStimuli[] = '<span title="'.$sStimulusCode.': '.htmlentities($oStimulus->GetDescription(), ENT_QUOTES, 'UTF-8').'">'.htmlentities($oStimulus->GetLabel(), ENT_QUOTES, 'UTF-8').'</span>';
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
		parent::DisplayBareRelations($oPage, $bEditMode);
		if (!$bEditMode)
		{
			$oPage->SetCurrentTab(Dict::S('UI:UserManagement:GrantMatrix'));
			$this->DoShowGrantSumary($oPage);		
		}
	}
}



class URP_UserProfile extends UserRightsBaseClassGUI
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
		MetaModel::Init_SetZListItems('list', array('userid', 'profileid', 'reason')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('userid', 'profileid')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('userid', 'profileid')); // Criteria of the advanced search form
	}

	public function GetName()
	{
		return Dict::Format('UI:UserManagement:LinkBetween_User_And_Profile', $this->Get('userlogin'), $this->Get('profile'));
	}
}

class URP_UserOrg extends UserRightsBaseClassGUI
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

		$iContactId = 0;
		// Support drastic data model changes: no organization class (or not writable)!
		if (MetaModel::IsValidClass('Organization') && !MetaModel::IsAbstract('Organization'))
		{
			$oOrg = new Organization();
			$oOrg->Set('name', 'My Company/Department');
			$oOrg->Set('code', 'SOMECODE');
			$iOrgId = $oOrg->DBInsertTrackedNoReload($oChange, true /* skip security */);

			// Support drastic data model changes: no Person class  (or not writable)!
			if (MetaModel::IsValidClass('Person') && !MetaModel::IsAbstract('Person'))
			{
				$oContact = new Person();
				$oContact->Set('name', 'My last name');
				$oContact->Set('first_name', 'My first name');
				if (MetaModel::IsValidAttCode('Person', 'org_id'))
				{
					$oContact->Set('org_id', $iOrgId);
				}
				$oContact->Set('email', 'my.email@foo.org');
				$iContactId = $oContact->DBInsertTrackedNoReload($oChange, true /* skip security */);
			}
		}


		$oUser = new UserLocal();
		$oUser->Set('login', $sAdminUser);
		$oUser->Set('password', $sAdminPwd);
		if (MetaModel::IsValidAttCode('UserLocal', 'contactid') && ($iContactId != 0))
		{
			$oUser->Set('contactid', $iContactId);
		}
		$oUser->Set('language', $sLanguage); // Language was chosen during the installation

		// Add this user to the very specific 'admin' profile
		$oAdminProfile = MetaModel::GetObjectFromOQL("SELECT URP_Profiles WHERE name = :name", array('name' => ADMIN_PROFILE_NAME), true /*all data*/);
		if (is_object($oAdminProfile))
		{
			$oUserProfile = new URP_UserProfile();
			//$oUserProfile->Set('userid', $iUserId);
			$oUserProfile->Set('profileid', $oAdminProfile->GetKey());
			$oUserProfile->Set('reason', 'By definition, the administrator must have the administrator profile');
			//$oUserProfile->DBInsertTrackedNoReload($oChange, true /* skip security */);
			$oSet = DBObjectSet::FromObject($oUserProfile);
			$oUser->Set('profile_list', $oSet);
		}
		$iUserId = $oUser->DBInsertTrackedNoReload($oChange, true /* skip security */);
		return true;
	}

	public function Init()
	{
	}


	protected $m_aAdmins; // id of users being linked to the well-known admin profile
	protected $m_aPortalUsers; // id of users being linked to the well-known admin profile

	protected $m_aProfiles; // id -> object
	protected $m_aUserProfiles; // userid,profileid -> object
	protected $m_aUserOrgs; // userid,orgid -> object

	// Those arrays could be completed on demand (inheriting parent permissions)
	protected $m_aClassActionGrants = null; // profile, class, action -> actiongrantid (or false if NO, or null/missing if undefined)
	protected $m_aClassStimulusGrants = array(); // profile, class, stimulus -> permission

	// Built on demand, could be optimized if necessary (doing a query for each attribute that needs to be read)
	protected $m_aObjectActionGrants = array();

	public function ResetCache()
	{
		// Loaded by Load cache
		$this->m_aProfiles = null; 
		$this->m_aUserProfiles = null;
		$this->m_aUserOrgs = null;

		$this->m_aAdmins = null;
		$this->m_aPortalUsers = null;

		// Loaded on demand (time consuming as compared to the others)
		$this->m_aClassActionGrants = null;
		$this->m_aClassStimulusGrants = null;
		
		$this->m_aObjectActionGrants = array();
	}

	// Separate load: this cache is much more time consuming while loading
	// Thus it is loaded iif required
	// Could be improved by specifying the profile id
	public function LoadActionGrantCache()
	{
		if (!is_null($this->m_aClassActionGrants)) return;

		$oKPI = new ExecutionKPI();

		$oFilter = DBObjectSearch::FromOQL_AllData("SELECT URP_ActionGrant AS p WHERE p.permission = 'yes'");
		$aGrants = $oFilter->ToDataArray();
		foreach($aGrants as $aGrant)
		{
			$this->m_aClassActionGrants[$aGrant['profileid']][$aGrant['class']][strtolower($aGrant['action'])] = $aGrant['id'];
		}

		$oKPI->ComputeAndReport('Load of action grants');
	}

	public function LoadCache()
	{
		if (!is_null($this->m_aProfiles)) return;
		// Could be loaded in a shared memory (?)

		$oKPI = new ExecutionKPI();

		$oProfileSet = new DBObjectSet(DBObjectSearch::FromOQL_AllData("SELECT URP_Profiles"));
		$this->m_aProfiles = array(); 
		while ($oProfile = $oProfileSet->Fetch())
		{
			$this->m_aProfiles[$oProfile->GetKey()] = $oProfile; 
		}

		$oUserProfileSet = new DBObjectSet(DBObjectSearch::FromOQL_AllData("SELECT URP_UserProfile"));
		$this->m_aUserProfiles = array();
		$this->m_aAdmins = array();
		$this->m_aPortalUsers = array();
		while ($oUserProfile = $oUserProfileSet->Fetch())
		{
			$this->m_aUserProfiles[$oUserProfile->Get('userid')][$oUserProfile->Get('profileid')] = $oUserProfile;
			if ($oUserProfile->Get('profile') == ADMIN_PROFILE_NAME)
			{
				$this->m_aAdmins[] = $oUserProfile->Get('userid');
			}
			elseif ($oUserProfile->Get('profile') == PORTAL_PROFILE_NAME)
			{
				$this->m_aPortalUsers[] = $oUserProfile->Get('userid');
			}
		}

		$oUserOrgSet = new DBObjectSet(DBObjectSearch::FromOQL_AllData("SELECT URP_UserOrg"));
		$this->m_aUserOrgs = array();
		while ($oUserOrg = $oUserOrgSet->Fetch())
		{
			$this->m_aUserOrgs[$oUserOrg->Get('userid')][$oUserOrg->Get('allowed_org_id')] = $oUserOrg;
		}

		$this->m_aClassStimulusGrants = array();
		$oStimGrantSet = new DBObjectSet(DBObjectSearch::FromOQL_AllData("SELECT URP_StimulusGrant"));
		$this->m_aStimGrants = array();
		while ($oStimGrant = $oStimGrantSet->Fetch())
		{
			$this->m_aClassStimulusGrants[$oStimGrant->Get('profileid')][$oStimGrant->Get('class')][$oStimGrant->Get('stimulus')] = $oStimGrant;
		}

		$oKPI->ComputeAndReport('Load of user management cache (excepted Action Grants)');

/*
		echo "<pre>\n";
		print_r($this->m_aProfiles);
		print_r($this->m_aUserProfiles);
		print_r($this->m_aUserOrgs);
		print_r($this->m_aClassActionGrants);
		print_r($this->m_aClassStimulusGrants);
		echo "</pre>\n";
exit;
*/

		return true;
	}

	public function IsAdministrator($oUser)
	{
		$this->LoadCache();

		if (in_array($oUser->GetKey(), $this->m_aAdmins))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function IsPortalUser($oUser)
	{
		$this->LoadCache();

		if (in_array($oUser->GetKey(), $this->m_aPortalUsers))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function GetSelectFilter($oUser, $sClass)
	{
		$this->LoadCache();

		$aObjectPermissions = $this->GetUserActionGrant($oUser, $sClass, UR_ACTION_READ);
		if ($aObjectPermissions['permission'] == UR_ALLOWED_NO)
		{
			return false;
		}

		// Determine how to position the objects of this class
		//
		$aCallSpec = array($sClass, 'MapContextParam');
		if ($sClass == 'Organization')
		{
			$sAttCode = 'id';
		}
		elseif (is_callable($aCallSpec))
		{
			$sAttCode = call_user_func($aCallSpec, 'org_id'); // Returns null when there is no mapping for this parameter

			if ($sAttCode == null)
			{
				return true;
			}
			if (!MetaModel::IsValidAttCode($sClass, $sAttCode))
			{
				// Skip silently. The data model checker will tell you something about this...
				return true;
			}
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
		// Position the user
		//
		@$aUserOrgs = $this->m_aUserOrgs[$oUser->GetKey()];
		if (!isset($aUserOrgs) || count($aUserOrgs) == 0)
		{
			// No position means 'Everywhere'
			return true;
		}

		$oExpression = new FieldExpression($sAttCode, $sClass);
		$oFilter  = new DBObjectSearch($sClass);
		$aIds = array_keys($aUserOrgs);
		$oListExpr = ListExpression::FromScalars($aIds);
		
		// Check if the condition points to a hierarchical key
		$bConditionAdded = false;
		if ($sAttCode == 'id')
		{
			// Filtering on the objects themselves
			$sHierarchicalKeyCode = MetaModel::IsHierarchicalClass($sClass);
			
			if ($sHierarchicalKeyCode !== false)
			{
				$oRootFilter = new DBObjectSearch($sClass);
				$oCondition = new BinaryExpression($oExpression, 'IN', $oListExpr);
				$oRootFilter->AddConditionExpression($oCondition);
				$oFilter->AddCondition_PointingTo($oRootFilter, $sHierarchicalKeyCode, TREE_OPERATOR_BELOW); // Use the 'below' operator by default
				$bConditionAdded = true;
			}
		}
		else
		{
			$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
			if ($oAttDef->IsExternalKey())
			{
				$sHierarchicalKeyCode = MetaModel::IsHierarchicalClass($oAttDef->GetTargetClass());
				
				if ($sHierarchicalKeyCode !== false)
				{
					$oRootFilter = new DBObjectSearch($oAttDef->GetTargetClass());
					$oExpression = new FieldExpression('id', $oAttDef->GetTargetClass());
					$oCondition = new BinaryExpression($oExpression, 'IN', $oListExpr);
					$oRootFilter->AddConditionExpression($oCondition);
					$oHKFilter = new DBObjectSearch($oAttDef->GetTargetClass());
					$oHKFilter->AddCondition_PointingTo($oRootFilter, $sHierarchicalKeyCode, TREE_OPERATOR_BELOW); // Use the 'below' operator by default
					$oFilter->AddCondition_PointingTo($oHKFilter, $sAttCode);
					$bConditionAdded = true;
				}
			}
		}
		if (!$bConditionAdded)
		{
			$oCondition = new BinaryExpression($oExpression, 'IN', $oListExpr);
			$oFilter->AddConditionExpression($oCondition);
		}
		return $oFilter;
	}

	// This verb has been made public to allow the development of an accurate feedback for the current configuration
	public function GetProfileActionGrant($iProfile, $sClass, $sAction)
	{
		$this->LoadActionGrantCache();

		// Note: action is forced lowercase to be more flexible (historical bug)
		$sAction = strtolower($sAction);
		if (isset($this->m_aClassActionGrants[$iProfile][$sClass][$sAction]))
		{
			return $this->m_aClassActionGrants[$iProfile][$sClass][$sAction];
		}

		// Recursively look for the grant record in the class hierarchy
		$sParentClass = MetaModel::GetParentPersistentClass($sClass);
		if (empty($sParentClass))
		{
			$iGrant = null;
		}
		else
		{
			// Recursively look for the grant record in the class hierarchy
			$iGrant = $this->GetProfileActionGrant($iProfile, $sParentClass, $sAction);
		}

		$this->m_aClassActionGrants[$iProfile][$sClass][$sAction] = $iGrant;
		return $iGrant;
	}

	protected function GetUserActionGrant($oUser, $sClass, $iActionCode)
	{
		$this->LoadCache();

		// load and cache permissions for the current user on the given class
		//
		$iUser = $oUser->GetKey();
		$aTest = @$this->m_aObjectActionGrants[$iUser][$sClass][$iActionCode];
		if (is_array($aTest)) return $aTest;

		$sAction = self::$m_aActionCodes[$iActionCode];

		$iPermission = UR_ALLOWED_NO;
		$aAttributes = array();
		if (isset($this->m_aUserProfiles[$iUser]))
		{
			foreach($this->m_aUserProfiles[$iUser] as $iProfile => $oProfile)
			{
				$iGrant = $this->GetProfileActionGrant($iProfile, $sClass, $sAction);
				if (is_null($iGrant) || !$iGrant)
				{
					continue; // loop to the next profile
				}
				else
				{
					$iPermission = UR_ALLOWED_YES;
	
					// update the list of attributes with those allowed for this profile
					//
					$oSearch = DBObjectSearch::FromOQL_AllData("SELECT URP_AttributeGrant WHERE actiongrantid = :actiongrantid");
					$oSet = new DBObjectSet($oSearch, array(), array('actiongrantid' => $iGrant));
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
			'permission' => $iPermission,
			'attributes' => $aAttributes,
		);
		$this->m_aObjectActionGrants[$iUser][$sClass][$iActionCode] = $aRes;
		return $aRes;
	}
	
	public function IsActionAllowed($oUser, $sClass, $iActionCode, $oInstanceSet = null)
	{
		$this->LoadCache();

		// Note: The object set is ignored because it was interesting to optimize for huge data sets
		//       and acceptable to consider only the root class of the object set
		$aObjectPermissions = $this->GetUserActionGrant($oUser, $sClass, $iActionCode);
		return $aObjectPermissions['permission'];
	}

	public function IsActionAllowedOnAttribute($oUser, $sClass, $sAttCode, $iActionCode, $oInstanceSet = null)
	{
		$this->LoadCache();

		// Note: The object set is ignored because it was interesting to optimize for huge data sets
		//       and acceptable to consider only the root class of the object set
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

	// This verb has been made public to allow the development of an accurate feedback for the current configuration
	public function GetClassStimulusGrant($iProfile, $sClass, $sStimulusCode)
	{
		$this->LoadCache();

		if (isset($this->m_aClassStimulusGrants[$iProfile][$sClass][$sStimulusCode]))
		{
			return $this->m_aClassStimulusGrants[$iProfile][$sClass][$sStimulusCode];
		}
		else
		{
			return null;
		}
	}

	public function IsStimulusAllowed($oUser, $sClass, $sStimulusCode, $oInstanceSet = null)
	{
		$this->LoadCache();
		// Note: this code is VERY close to the code of IsActionAllowed()
		$iUser = $oUser->GetKey();

		// Note: The object set is ignored because it was interesting to optimize for huge data sets
		//       and acceptable to consider only the root class of the object set
		$iPermission = UR_ALLOWED_NO;
		if (isset($this->m_aUserProfiles[$iUser]))
		{
			foreach($this->m_aUserProfiles[$iUser] as $iProfile => $oProfile)
			{
				$oGrantRecord = $this->GetClassStimulusGrant($iProfile, $sClass, $sStimulusCode);
				if (!is_null($oGrantRecord))
				{
					// no need to fetch the record, we've requested the records having permission = 'yes'
					$iPermission = UR_ALLOWED_YES;
				}
			}
		}
		return $iPermission;
	}

	public function FlushPrivileges()
	{
		$this->ResetCache();
	}
}


UserRights::SelectModule('UserRightsProfile');

?>

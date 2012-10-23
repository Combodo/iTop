<?php
// Copyright (C) 2010-2012 Combodo SARL
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
 * UserRightsProjection
 * User management Module, basing the right on profiles and a matrix (similar to UserRightsProfile, but enhanced with dimensions and projection of classes and profile over the dimensions) 
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

define('ADMIN_PROFILE_ID', 1);

class UserRightsBaseClass extends cmdbAbstractObject
{
	// Whenever something changes, reload the privileges
	
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
		$oGrant = $oUserRights->GetClassActionGrant($this->GetKey(), $sClass, $sAction);
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
		if ($this->GetRawName() == "Administrator")
		{
			// Looks dirty, but ok that's THE ONE
			$oPage->p(Dict::S('UI:UserManagement:AdminProfile+'));
			return;
		}

		// Note: for sure, we assume that the instance is derived from UserRightsProjection
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


class URP_Dimensions extends UserRightsBaseClass
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
			"db_table" => "priv_urp_dimensions",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		//MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeString("name", array("allowed_values"=>null, "sql"=>"name", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("description", array("allowed_values"=>null, "sql"=>"description", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeClass("type", array("class_category"=>"bizmodel", "more_values"=>"String,Integer", "sql"=>"type", "default_value"=>'String', "is_null_allowed"=>false, "depends_on"=>array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('name', 'description', 'type')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('description')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('name')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
	}

	public function CheckProjectionSpec($oProjectionSpec, $sProjectedClass)
	{
		$sExpression = $oProjectionSpec->Get('value');
		$sAttribute = $oProjectionSpec->Get('attribute');

		// Shortcut: "any value" or "no value" means no projection
		if (empty($sExpression)) return;
		if ($sExpression == '<any>') return;

		// 1st - compute the data type for the dimension
		//
		$sType = $this->Get('type');
		if (MetaModel::IsValidClass($sType))
		{
			$sExpectedType = $sType;
		}
		else
		{
			$sExpectedType = '_scalar_';
		}

		// 2nd - compute the data type for the projection
		//
		$sTargetClass = '';
		if (($sExpression == '<this>') || ($sExpression == '<user>'))
		{
			$sTargetClass = $sProjectedClass;
		}
		elseif ($sExpression == '<any>')
		{
			$sTargetClass = '';
		}
		else
		{
			// Evaluate wether it is a constant or not
			try
			{
				$oObjectSearch = DBObjectSearch::FromOQL_AllData($sExpression);

				$sTargetClass = $oObjectSearch->GetClass();
			}
			catch (OqlException $e)
			{
			}
		}

		if (empty($sTargetClass))
		{
			$sFoundType = '_void_';
		}
		else
		{
			if (empty($sAttribute))
			{
				$sFoundType = $sTargetClass;
			}
			else
			{
				if (!MetaModel::IsValidAttCode($sTargetClass, $sAttribute))
				{
					throw new CoreException('Unkown attribute code in projection specification', array('found' => $sAttribute, 'expecting' => MetaModel::GetAttributesList($sTargetClass), 'class' => $sTargetClass, 'projection' => $oProjectionSpec));
				}
				$oAttDef = MetaModel::GetAttributeDef($sTargetClass, $sAttribute);
				if ($oAttDef->IsExternalKey())
				{
					$sFoundType = $oAttDef->GetTargetClass();
				}
				else
				{
					$sFoundType = '_scalar_';
				}
			}
		}

		// Compare the dimension type and projection type
		if (($sFoundType != '_void_') && ($sFoundType != $sExpectedType))
		{
			throw new CoreException('Wrong type in projection specification', array('found' => $sFoundType, 'expecting' => $sExpectedType, 'expression' => $sExpression, 'attribute' => $sAttribute, 'projection' => $oProjectionSpec));
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


class URP_ProfileProjection extends UserRightsBaseClass
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
			"db_table" => "priv_urp_profileprojection",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		//MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeExternalKey("dimensionid", array("targetclass"=>"URP_Dimensions", "jointype"=> "", "allowed_values"=>null, "sql"=>"dimensionid", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("dimension", array("allowed_values"=>null, "extkey_attcode"=> 'dimensionid', "target_attcode"=>"name")));

		MetaModel::Init_AddAttribute(new AttributeExternalKey("profileid", array("targetclass"=>"URP_Profiles", "jointype"=> "", "allowed_values"=>null, "sql"=>"profileid", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("profile", array("allowed_values"=>null, "extkey_attcode"=> 'profileid', "target_attcode"=>"name")));

		MetaModel::Init_AddAttribute(new AttributeString("value", array("allowed_values"=>null, "sql"=>"value", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("attribute", array("allowed_values"=>null, "sql"=>"attribute", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('dimensionid', 'profileid', 'value', 'attribute')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('profileid', 'value', 'attribute')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('dimensionid', 'profileid')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('dimensionid', 'profileid')); // Criteria of the advanced search form
	}

	protected $m_aUserProjections; // cache

	public function ProjectUser(User $oUser)
	{
		if (is_array($this->m_aUserProjections))
		{
			// Hit!
			return $this->m_aUserProjections;
		}

		$sExpr = $this->Get('value');
		if ($sExpr == '<user>')
		{
			$sColumn = $this->Get('attribute');
			if (empty($sColumn))
			{
				$aRes = array($oUser->GetKey());
			}
			else
			{
				$aRes = array($oUser->Get($sColumn));
			}
			
		}
		elseif (($sExpr == '<any>') || ($sExpr == ''))
		{
			$aRes = null;
		}
		elseif (strtolower(substr($sExpr, 0, 6)) == 'select')
		{
			$sColumn = $this->Get('attribute');
			// SELECT...
			$oValueSetDef = new ValueSetObjects($sExpr, $sColumn, array(), true /*allow all data*/);
			$aRes = $oValueSetDef->GetValues(array('user' => $oUser), '');
		}
		else
		{
			// Constant value(s)
			$aRes = explode(';', trim($sExpr));
		}
		$this->m_aUserProjections = $aRes;
		return $aRes;
	}
}


class URP_ClassProjection extends UserRightsBaseClass
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "addon/userrights",
			"key_type" => "autoincrement",
			"name_attcode" => "dimensionid",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_urp_classprojection",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		//MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeExternalKey("dimensionid", array("targetclass"=>"URP_Dimensions", "jointype"=> "", "allowed_values"=>null, "sql"=>"dimensionid", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("dimension", array("allowed_values"=>null, "extkey_attcode"=> 'dimensionid', "target_attcode"=>"name")));

		MetaModel::Init_AddAttribute(new AttributeClass("class", array("class_category"=>"", "more_values"=>"", "sql"=>"class", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));

		MetaModel::Init_AddAttribute(new AttributeString("value", array("allowed_values"=>null, "sql"=>"value", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("attribute", array("allowed_values"=>null, "sql"=>"attribute", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('dimensionid', 'class', 'value', 'attribute')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('class', 'value', 'attribute')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('dimensionid', 'class')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('dimensionid', 'class')); // Criteria of the advanced search form
	}

	public function ProjectObject($oObject)
	{
		$sExpr = $this->Get('value');
		if ($sExpr == '<this>')
		{
			$sColumn = $this->Get('attribute');
			if (empty($sColumn))
			{
				$aRes = array($oObject->GetKey());
			}
			else
			{
				$aRes = array($oObject->Get($sColumn));
			}
			
		}
		elseif (($sExpr == '<any>') || ($sExpr == ''))
		{
			$aRes = null;
		}
		elseif (strtolower(substr($sExpr, 0, 6)) == 'select')
		{ 
			$sColumn = $this->Get('attribute');
			// SELECT...
			$oValueSetDef = new ValueSetObjects($sExpr, $sColumn, array(), true /*allow all data*/);
			$aRes = $oValueSetDef->GetValues(array('this' => $oObject), '');
		}
		else
		{
			// Constant value(s)
			$aRes = explode(';', trim($sExpr));
		}
		return $aRes;
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




class UserRightsProjection extends UserRightsAddOnAPI
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
		$iOrgId = $oOrg->DBInsertTrackedNoReload($oChange, true /* skip strong security */);

		// Location : optional
		//$oLocation = new bizLocation();
		//$oLocation->Set('name', 'MyOffice');
		//$oLocation->Set('status', 'implementation');
		//$oLocation->Set('org_id', $iOrgId);
		//$oLocation->Set('severity', 'high');
		//$oLocation->Set('address', 'my building in my city');
		//$oLocation->Set('country', 'my country');
		//$oLocation->Set('parent_location_id', xxx);
		//$iLocationId = $oLocation->DBInsertNoReload();

		$oContact = new Person();
		$oContact->Set('name', 'My last name');
		$oContact->Set('first_name', 'My first name');
		//$oContact->Set('status', 'available');
		$oContact->Set('org_id', $iOrgId);
		$oContact->Set('email', 'my.email@foo.org');
		//$oContact->Set('phone', '');
		//$oContact->Set('location_id', $iLocationId);
		//$oContact->Set('employee_number', '');
		$iContactId = $oContact->DBInsertTrackedNoReload($oChange, true /* skip security */);
		
		$oUser = new UserLocal();
		$oUser->Set('login', $sAdminUser);
		$oUser->Set('password', $sAdminPwd);
		$oUser->Set('contactid', $iContactId);
		$oUser->Set('language', $sLanguage); // Language was chosen during the installation
		$iUserId = $oUser->DBInsertTrackedNoReload($oChange, true /* skip security */);
		
		// Add this user to the very specific 'admin' profile
		$oUserProfile = new URP_UserProfile();
		$oUserProfile->Set('userid', $iUserId);
		$oUserProfile->Set('profileid', ADMIN_PROFILE_ID);
		$oUserProfile->Set('reason', 'By definition, the administrator must have the administrator profile');
		$oUserProfile->DBInsertTrackedNoReload($oChange, true /* skip security */);
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

	public function IsPortalUser($oUser)
	{
		return true;
		// See implementation of userrightsprofile
	}

	public function Init()
	{
		// CacheData to be invoked in a module extension
		//MetaModel::RegisterPlugin('userrights', 'ACbyProfile', array($this, 'CacheData'));
	}

	protected $m_aDimensions = array(); // id -> object
	protected $m_aClassProj = array(); // class,dimensionid -> object
	protected $m_aProfiles = array(); // id -> object
	protected $m_aUserProfiles = array(); // userid,profileid -> object
	protected $m_aProPro = array(); // profileid,dimensionid -> object

	protected $m_aAdmins = array(); // id of users being linked to the well-known admin profile

	protected $m_aClassActionGrants = array(); // profile, class, action -> permission
	protected $m_aClassStimulusGrants = array(); // profile, class, stimulus -> permission
	protected $m_aObjectActionGrants = array(); // userid, class, id, action -> permission, list of attributes

	public function CacheData()
	{
		// Could be loaded in a shared memory (?)

		$oDimensionSet = new DBObjectSet(DBObjectSearch::FromOQL_AllData("SELECT URP_Dimensions"));
		$this->m_aDimensions = array(); 
		while ($oDimension = $oDimensionSet->Fetch())
		{
			$this->m_aDimensions[$oDimension->GetKey()] = $oDimension; 
		}
		
		$oClassProjSet = new DBObjectSet(DBObjectSearch::FromOQL_AllData("SELECT URP_ClassProjection"));
		$this->m_aClassProjs = array(); 
		while ($oClassProj = $oClassProjSet->Fetch())
		{
			$this->m_aClassProjs[$oClassProj->Get('class')][$oClassProj->Get('dimensionid')] = $oClassProj; 
		}

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

		$oProProSet = new DBObjectSet(DBObjectSearch::FromOQL_AllData("SELECT URP_ProfileProjection"));
		$this->m_aProPros = array(); 
		while ($oProPro = $oProProSet->Fetch())
		{
			$this->m_aProPros[$oProPro->Get('profileid')][$oProPro->Get('dimensionid')] = $oProPro; 
		}

/*
		echo "<pre>\n";
		print_r($this->m_aDimensions);
		print_r($this->m_aClassProjs);
		print_r($this->m_aProfiles);
		print_r($this->m_aUserProfiles);
		print_r($this->m_aProPros);
		echo "</pre>\n";
exit;
*/

		return true;
	}

	public function GetSelectFilter($oUser, $sClass, $aSettings = array())
	{
		$aConditions = array();
		foreach ($this->m_aDimensions as $iDimension => $oDimension)
		{
			$oClassProj = @$this->m_aClassProjs[$sClass][$iDimension];
			if (is_null($oClassProj))
			{
				// Authorize any for this dimension, then no additional criteria is required
				continue;
			}
 
			// 1 - Get class projection info
			//
			$oExpression = null;
			$sExpr = $oClassProj->Get('value');
			if ($sExpr == '<this>')
			{
				$sColumn = $oClassProj->Get('attribute');
				if (empty($sColumn))
				{
					$oExpression = new FieldExpression('id', $sClass);
				}
				else
				{
					$oExpression = new FieldExpression($sColumn, $sClass);
				}
			}
			elseif (($sExpr == '<any>') || ($sExpr == ''))
			{
				// Authorize any for this dimension, then no additional criteria is required
				continue;
			}
			elseif (strtolower(substr($sExpr, 0, 6)) == 'select')
			{
				throw new CoreException('Sorry, projections by the mean of OQL are not supported currently, please specify an attribute instead', array('class' => $sClass, 'expression' => $sExpr)); 
			}
			else
			{
				// Constant value(s)
				// unsupported
				throw new CoreException('Sorry, constant projections are not supported currently, please specify an attribute instead', array('class' => $sClass, 'expression' => $sExpr)); 
//				$aRes = explode(';', trim($sExpr));
			}

			// 2 - Get profile projection info and use it if needed
			//
			$aProjections = self::GetReadableProjectionsByDim($oUser, $sClass, $oDimension);
			if (is_null($aProjections))
			{
				// Authorize any for this dimension, then no additional criteria is required
				continue;
			}
			elseif (count($aProjections) == 0)
			{
				// Authorize none, then exit as quickly as possible
				return false;
			}
			else
			{
				// Authorize the given set of values
				$oListExpr = ListExpression::FromScalars($aProjections);
				$oCondition = new BinaryExpression($oExpression, 'IN', $oListExpr);
				$aConditions[] = $oCondition;
			}
		}

		if (count($aConditions) == 0)
		{
			// allow all
			return true;
		}
		else
		{
			$oFilter  = new DBObjectSearch($sClass);
			foreach($aConditions as $oCondition)
			{
				$oFilter->AddConditionExpression($oCondition);
			}
			//return true;
			return $oFilter;
		}
	}

	// This verb has been made public to allow the development of an accurate feedback for the current configuration
	public function GetClassActionGrant($iProfile, $sClass, $sAction)
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
				$oGrantRecord = $this->GetClassActionGrant($iProfile, $sParentClass, $sAction);
			}
		}

		$this->m_aClassActionGrants[$iProfile][$sClass][$sAction] = $oGrantRecord;
		return $oGrantRecord;
	}

	protected function GetObjectActionGrant($oUser, $sClass, $iActionCode, /*DBObject*/ $oObject = null)
	{
		if(is_null($oObject))
		{
			$iObjectRef = -999;
		}
		else
		{
			$iObjectRef = $oObject->GetKey();
		}
		// load and cache permissions for the current user on the given object
		//
		$aTest = @$this->m_aObjectActionGrants[$oUser->GetKey()][$sClass][$iObjectRef][$iActionCode];
		if (is_array($aTest)) return $aTest;

		$sAction = self::$m_aActionCodes[$iActionCode];

		$iInstancePermission = UR_ALLOWED_NO;
		$aAttributes = array();
		foreach($this->GetMatchingProfiles($oUser, $sClass, $oObject) as $iProfile)
		{
			$oGrantRecord = $this->GetClassActionGrant($iProfile, $sClass, $sAction);
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

		$aRes = array(
			'permission' => $iInstancePermission,
			'attributes' => $aAttributes,
		);
		$this->m_aObjectActionGrants[$oUser->GetKey()][$sClass][$iObjectRef][$iActionCode] = $aRes;
		return $aRes;
	}
	
	public function IsActionAllowed($oUser, $sClass, $iActionCode, $oInstanceSet = null)
	{
		if (is_null($oInstanceSet))
		{
			$aObjectPermissions = $this->GetObjectActionGrant($oUser, $sClass, $iActionCode);
			return $aObjectPermissions['permission'];
		}

		$oInstanceSet->Rewind();
		while($oObject = $oInstanceSet->Fetch())
		{
			$aObjectPermissions = $this->GetObjectActionGrant($oUser, $sClass, $iActionCode, $oObject);

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
			$aObjectPermissions = $this->GetObjectActionGrant($oUser, $sClass, $iActionCode);
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
			$aObjectPermissions = $this->GetObjectActionGrant($oUser, $sClass, $iActionCode, $oObject);

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
			foreach($this->GetMatchingProfiles($oUser, $sClass) as $iProfile)
			{
				$oGrantRecord = $this->GetClassStimulusGrant($iProfile, $sClass, $sStimulusCode);
				if (!is_null($oGrantRecord))
				{
					// no need to fetch the record, we've requested the records having permission = 'yes'
					$iInstancePermission = UR_ALLOWED_YES;
				}
			}
			return $iInstancePermission;
		}

		$oInstanceSet->Rewind();
		while($oObject = $oInstanceSet->Fetch())
		{
			$iInstancePermission = UR_ALLOWED_NO;
			foreach($this->GetMatchingProfiles($oUser, $sClass, $oObject) as $iProfile)
			{
				$oGrantRecord = $this->GetClassStimulusGrant($iProfile, $sClass, $sStimulusCode);
				if (!is_null($oGrantRecord))
				{
					// no need to fetch the record, we've requested the records having permission = 'yes'
					$iInstancePermission = UR_ALLOWED_YES;
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

	// Copied from GetMatchingProfilesByDim()
	// adapted to the optimized implementation of GetSelectFilter()
	// Note: shares the cache m_aProPros with GetMatchingProfilesByDim()
	// Returns   null if any object is readable
	//           an array of allowed projections otherwise (could be an empty array if none is allowed)
	protected function GetReadableProjectionsByDim($oUser, $sClass, $oDimension)
	{
		//
		// Given a dimension, lists the values for which the user will be allowed to read the objects
		//
		$iUser = $oUser->GetKey();
		$iDimension = $oDimension->GetKey();

		$aRes = array();
		if (array_key_exists($iUser, $this->m_aUserProfiles))
		{
			foreach ($this->m_aUserProfiles[$iUser] as $iProfile => $oProfile)
			{
				// user projection to be cached on a given page !
				if (!isset($this->m_aProPros[$iProfile][$iDimension]))
				{
					// No projection for a given profile: default to 'any'
					return null;
				}

				$aUserProjection = $this->m_aProPros[$iProfile][$iDimension]->ProjectUser($oUser);
				if (is_null($aUserProjection))
				{
					// No projection for a given profile: default to 'any'
					return null;
				}
				$aRes = array_unique(array_merge($aRes, $aUserProjection));
			}
		}
		return $aRes;
	}

	// Note: shares the cache m_aProPros with GetReadableProjectionsByDim()
	protected function GetMatchingProfilesByDim($oUser, $oObject, $oDimension)
	{
		//
		// List profiles for which the user projection overlaps the object projection in the given dimension
		//
		$iUser = $oUser->GetKey();
		$sClass = get_class($oObject);
		$iPKey = $oObject->GetKey();
		$iDimension = $oDimension->GetKey();

		if (isset($this->m_aClassProjs[$sClass][$iDimension]))
		{
			$aObjectProjection = $this->m_aClassProjs[$sClass][$iDimension]->ProjectObject($oObject);
		}
		else
		{
			// No projection for a given class: default to 'any'
			$aObjectProjection = null;
		}

		$aRes = array();
		if (array_key_exists($iUser, $this->m_aUserProfiles))
		{
			foreach ($this->m_aUserProfiles[$iUser] as $iProfile => $oProfile)
			{
				if (is_null($aObjectProjection))
				{
					$aRes[] = $iProfile;
				}
				else
				{
					// user projection to be cached on a given page !
					if (isset($this->m_aProPros[$iProfile][$iDimension]))
					{
						$aUserProjection = $this->m_aProPros[$iProfile][$iDimension]->ProjectUser($oUser);
					}
					else
					{
						// No projection for a given profile: default to 'any'
						$aUserProjection = null;
					}

					if (is_null($aUserProjection))
					{
						$aRes[] = $iProfile;
					}
					else
					{
						$aMatchingValues = array_intersect($aObjectProjection, $aUserProjection);
						if (count($aMatchingValues) > 0)
						{
							$aRes[] = $iProfile;
						}
					}
				}
			}
		}
		return $aRes;
	}

	protected $m_aMatchingProfiles = array(); // cache of the matching profiles for a given user/object
	
	protected function GetMatchingProfiles($oUser, $sClass, /*DBObject*/ $oObject = null)
	{
		$iUser = $oUser->GetKey();

		if(is_null($oObject))
		{
			$iObjectRef = -999;
		}
		else
		{
			$iObjectRef = $oObject->GetKey();
		}

		//
		// List profiles for which the user projection overlaps the object projection in each and every dimension
		// Caches the result
		//
		$aTest = @$this->m_aMatchingProfiles[$iUser][$sClass][$iObjectRef];
		if (is_array($aTest))
		{
			return $aTest;
		}

		if (is_null($oObject))
		{
			if (array_key_exists($iUser, $this->m_aUserProfiles))
			{
				$aRes = array_keys($this->m_aUserProfiles[$iUser]);
			}
			else
			{
				// no profile has been defined for this user
				$aRes = array();
			}
		}
		else
		{
			$aProfileRes = array();
			foreach ($this->m_aDimensions as $iDimension => $oDimension)
			{
				foreach ($this->GetMatchingProfilesByDim($oUser, $oObject, $oDimension) as $iProfile)
				{
					@$aProfileRes[$iProfile] += 1;
				}
			}
	
			$aRes = array();
			$iDimCount = count($this->m_aDimensions);
			foreach ($aProfileRes as $iProfile => $iMatches)
			{
				if ($iMatches == $iDimCount)
				{
					$aRes[] = $iProfile;
				}
			}
		}

		// store into the cache
		$this->m_aMatchingProfiles[$iUser][$sClass][$iObjectRef] = $aRes;
		return $aRes; 
	}

	public function FlushPrivileges()
	{
		$this->CacheData();
	}
}


UserRights::SelectModule('UserRightsProjection');

?>

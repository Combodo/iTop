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


class URP_Users extends UserRightsBaseClass
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "addon/userrights",
			"key_type" => "autoincrement",
			"key_label" => "",
			"name_attcode" => "login",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_urp_users",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		//MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeExternalKey("userid", array("targetclass"=>"bizPerson", "allowed_values"=>null, "sql"=>"userid", "is_null_allowed"=>true, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("last_name", array("allowed_values"=>null, "extkey_attcode"=> 'userid', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeExternalField("first_name", array("allowed_values"=>null, "extkey_attcode"=> 'userid', "target_attcode"=>"first_name")));
		MetaModel::Init_AddAttribute(new AttributeExternalField("email", array("allowed_values"=>null, "extkey_attcode"=> 'userid', "target_attcode"=>"email")));

		MetaModel::Init_AddAttribute(new AttributeString("login", array("allowed_values"=>null, "sql"=>"login", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributePassword("password", array("allowed_values"=>null, "sql"=>"pwd", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));

		//MetaModel::Init_AddAttribute(new AttributeString("language", array("allowed_values"=>array('EN US,FR FR'), "sql"=>"language", "default_value"=>"EN US", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeApplicationLanguage("language", array("sql"=>"language", "default_value"=>"EN US", "is_null_allowed"=>false, "depends_on"=>array())));

		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("profiles", array("linked_class"=>"URP_UserProfile", "ext_key_to_me"=>"userid", "ext_key_to_remote"=>"profileid", "allowed_values"=>null, "count_min"=>1, "count_max"=>0, "depends_on"=>array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('userid', 'first_name', 'email', 'login', 'language')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('first_name', 'last_name', 'login')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('login', 'userid')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('login', 'userid')); // Criteria of the advanced search form
	}

	function GetGrantAsHtml($sClass, $iAction)
	{
		if (UserRights::IsActionAllowed($sClass, $iAction, null, $this->GetKey())) 
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
		$iUserId = $this->GetKey();
		if (UserRights::IsAdministrator($iUserId))
		{
			// Looks dirty, but ok that's THE ONE
			$oPage->p(Dict::S('UI:UserManagement:AdminProfile+'));
			return;
		}

		$aDisplayData = array();
		foreach (MetaModel::GetClasses($sClassCategory) as $sClass)
		{
			$aClassStimuli = MetaModel::EnumStimuli($sClass);
			if (count($aClassStimuli) > 0)
			{
				$aStimuli = array();
				foreach ($aClassStimuli as $sStimulusCode => $oStimulus)
				{
					if (UserRights::IsStimulusAllowed($sClass, $sStimulusCode, null, $iUserId))
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
	
		$aDisplayConfig = array();
		$aDisplayConfig['class'] = array('label' => Dict::S('UI:UserManagement:Class'), 'description' => Dict::S('UI:UserManagement:Class+'));
		$aDisplayConfig['read'] = array('label' => Dict::S('UI:UserManagement:Action:Read'), 'description' => Dict::S('UI:UserManagement:Action:Read+'));
		$aDisplayConfig['bulkread'] = array('label' => Dict::S('UI:UserManagement:Action:BulkRead'), 'description' => Dict::S('UI:UserManagement:Action:BulkRead+'));
		$aDisplayConfig['write'] = array('label' => Dict::S('UI:UserManagement:Action:Modify'), 'description' => Dict::S('UI:UserManagement:Action:Modify+'));
		$aDisplayConfig['bulkwrite'] = array('label' => Dict::S('UI:UserManagement:Action:BulkModify'), 'description' => Dict::S('UI:UserManagement:Action:BulkModify+'));
		$aDisplayConfig['stimuli'] = array('label' => Dict::S('UI:UserManagement:Action:Stimuli'), 'description' => Dict::S('UI:UserManagement:Action:Stimuli+'));
		$oPage->table($aDisplayConfig, $aDisplayData);
	}

	function DisplayBareRelations(WebPage $oPage)
	{
		parent::DisplayBareRelations($oPage);

		$oPage->SetCurrentTabContainer('Related Objects');

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


class URP_Profiles extends UserRightsBaseClass
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "addon/userrights",
			"key_type" => "autoincrement",
			"key_label" => "",
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

		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("users", array("linked_class"=>"URP_UserProfile", "ext_key_to_me"=>"profileid", "ext_key_to_remote"=>"userid", "allowed_values"=>null, "count_min"=>1, "count_max"=>0, "depends_on"=>array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('name', 'description')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('name', 'description')); // Attributes to be displayed for a list
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

	function DisplayBareRelations(WebPage $oPage)
	{
		parent::DisplayBareRelations($oPage);

		$oPage->SetCurrentTabContainer('Related Objects');

		$oPage->SetCurrentTab(Dict::S('UI:UserManagement:GrantMatrix'));
		$this->DoShowGrantSumary($oPage);		
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
			"key_label" => "",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_urp_dimensions",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "../business/templates/default.html",
		);
		MetaModel::Init_Params($aParams);
		//MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeString("name", array("allowed_values"=>null, "sql"=>"name", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("description", array("allowed_values"=>null, "sql"=>"description", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeClass("type", array("class_category"=>"bizmodel", "more_values"=>"String,Integer", "sql"=>"type", "default_value"=>'String', "is_null_allowed"=>false, "depends_on"=>array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('name', 'description', 'type')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('name', 'description')); // Attributes to be displayed for a list
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
				$oObjectSearch = DBObjectSearch::FromOQL($sExpression);

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
			"key_label" => "",
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
		MetaModel::Init_AddAttribute(new AttributeExternalKey("userid", array("targetclass"=>"URP_Users", "jointype"=> "", "allowed_values"=>null, "sql"=>"userid", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
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
		return Dict::Format('UI:UserManagement:LinkBetween_User_And_Profile');
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
			"key_label" => "",
			"name_attcode" => "profileid",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_urp_profileprojection",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "../business/templates/default.html",
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
		MetaModel::Init_SetZListItems('list', array('dimensionid', 'profileid', 'value', 'attribute')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('dimensionid', 'profileid')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('dimensionid', 'profileid')); // Criteria of the advanced search form
	}

	public function ProjectUser(URP_Users $oUser)
	{
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
		elseif ($sExpr == '<any>')
		{
			$aRes = null;
		}
		elseif (strtolower(substr($sExpr, 0, 6)) == 'select')
		{ 
			$sColumn = $this->Get('attribute');
			// SELECT...
			$oValueSetDef = new ValueSetObjects($sExpr, $sColumn);
			$aValues = $oValueSetDef->GetValues(array('user' => $oUser), '');
			$aRes = array_keys($aValues);
		}
		else
		{
			// Constant value(s)
			$aRes = explode(';', trim($sExpr));
		}
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
			"key_label" => "",
			"name_attcode" => "dimensionid",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_urp_classprojection",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "../business/templates/default.html",
		);
		MetaModel::Init_Params($aParams);
		//MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeExternalKey("dimensionid", array("targetclass"=>"URP_Dimensions", "jointype"=> "", "allowed_values"=>null, "sql"=>"dimensionid", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("dimension", array("allowed_values"=>null, "extkey_attcode"=> 'dimensionid', "target_attcode"=>"name")));

		MetaModel::Init_AddAttribute(new AttributeClass("class", array("class_category"=>"", "more_values"=>"", "sql"=>"class", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));

		MetaModel::Init_AddAttribute(new AttributeString("value", array("allowed_values"=>null, "sql"=>"value", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("attribute", array("allowed_values"=>null, "sql"=>"attribute", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('dimensionid', 'class', 'value', 'attribute')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('dimensionid', 'class', 'value', 'attribute')); // Attributes to be displayed for a list
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
		elseif ($sExpr == '<any>')
		{
			$aRes = null;
		}
		elseif (strtolower(substr($sExpr, 0, 6)) == 'select')
		{ 
			$sColumn = $this->Get('attribute');
			// SELECT...
			$oValueSetDef = new ValueSetObjects($sExpr, $sColumn);
			$aValues = $oValueSetDef->GetValues(array('this' => $oObject), '');
			$aRes = array_keys($aValues);
		}
		elseif ($sExpr == '<any>')
		{
			$aRes = null;
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
			"key_label" => "",
			"name_attcode" => "profileid",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_urp_grant_actions",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "../business/templates/default.html",
		);
		MetaModel::Init_Params($aParams);
		//MetaModel::Init_InheritAttributes();

		// Common to all grant classes (could be factorized by class inheritence, but this has to be benchmarked)
		MetaModel::Init_AddAttribute(new AttributeExternalKey("profileid", array("targetclass"=>"URP_Profiles", "jointype"=> "", "allowed_values"=>null, "sql"=>"profileid", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("profile", array("allowed_values"=>null, "extkey_attcode"=> 'profileid', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeClass("class", array("class_category"=>"", "more_values"=>"", "sql"=>"class", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("permission", array("allowed_values"=>new ValueSetEnum('yes,no'), "sql"=>"permission", "default_value"=>"yes", "is_null_allowed"=>false, "depends_on"=>array())));

		MetaModel::Init_AddAttribute(new AttributeString("action", array("allowed_values"=>null, "sql"=>"action", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('profileid', 'class', 'permission', 'action')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('profileid', 'class', 'permission', 'action')); // Attributes to be displayed for a list
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
			"key_label" => "",
			"name_attcode" => "profileid",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_urp_grant_stimulus",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "../business/templates/default.html",
		);
		MetaModel::Init_Params($aParams);
		//MetaModel::Init_InheritAttributes();

		// Common to all grant classes (could be factorized by class inheritence, but this has to be benchmarked)
		MetaModel::Init_AddAttribute(new AttributeExternalKey("profileid", array("targetclass"=>"URP_Profiles", "jointype"=> "", "allowed_values"=>null, "sql"=>"profileid", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("profile", array("allowed_values"=>null, "extkey_attcode"=> 'profileid', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeClass("class", array("class_category"=>"", "more_values"=>"", "sql"=>"class", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("permission", array("allowed_values"=>new ValueSetEnum('yes,no'), "sql"=>"permission", "default_value"=>"yes", "is_null_allowed"=>false, "depends_on"=>array())));

		MetaModel::Init_AddAttribute(new AttributeString("stimulus", array("allowed_values"=>null, "sql"=>"action", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('profileid', 'class', 'permission', 'stimulus')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('profileid', 'class', 'permission', 'stimulus')); // Attributes to be displayed for a list
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
			"key_label" => "",
			"name_attcode" => "actiongrantid",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_urp_grant_attributes",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "../business/templates/default.html",
		);
		MetaModel::Init_Params($aParams);
		//MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeExternalKey("actiongrantid", array("targetclass"=>"URP_ActionGrant", "jointype"=> "", "allowed_values"=>null, "sql"=>"actiongrantid", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("attcode", array("allowed_values"=>null, "sql"=>"attcode", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('actiongrantid', 'attcode')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('actiongrantid', 'attcode')); // Attributes to be displayed for a list
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
	public function CreateAdministrator($sAdminUser, $sAdminPwd)
	{
		// Create a change to record the history of the User object
		$oChange = MetaModel::NewObject("CMDBChange");
		$oChange->Set("date", time());
		$oChange->Set("userinfo", "Initialization");
		$iChangeId = $oChange->DBInsert();

		$oOrg = new bizOrganization();
		$oOrg->Set('name', 'My Company/Department');
		$oOrg->Set('code', 'SOMECODE');
		$oOrg->Set('status', 'implementation');
		//$oOrg->Set('parent_id', xxx);
		$iOrgId = $oOrg->DBInsertTrackedNoReload($oChange);

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

		$oContact = new bizPerson();
		$oContact->Set('name', 'My last name');
		$oContact->Set('first_name', 'My first name');
		$oContact->Set('status', 'available');
		$oContact->Set('org_id', $iOrgId);
		$oContact->Set('email', 'my.email@foo.org');
		$oContact->Set('phone', '');
		//$oContact->Set('location_id', $iLocationId);
		$oContact->Set('employee_number', '');
		$iContactId = $oContact->DBInsertTrackedNoReload($oChange);
		
		$oUser = new URP_Users();
		$oUser->Set('login', $sAdminUser);
		$oUser->Set('password', $sAdminPwd);
		$oUser->Set('userid', $iContactId);
		$iUserId = $oUser->DBInsertTrackedNoReload($oChange);
		
		// Add this user to the very specific 'admin' profile
		$oUserProfile = new URP_UserProfile();
		$oUserProfile->Set('userid', $iUserId);
		$oUserProfile->Set('profileid', ADMIN_PROFILE_ID);
		$oUserProfile->Set('reason', 'By definition, the administrator must have the administrator profile');
		$oUserProfile->DBInsertTrackedNoReload($oChange);
		return true;
	}

	public function IsAdministrator($iUserId)
	{
		if (in_array($iUserId, $this->m_aAdmins))
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

		SetupProfiles::DoCreateDimensions();
		SetupProfiles::DoCreateDimensions();
		SetupProfiles::DoCreateProfiles();
		return true;
	}

	public function Init()
	{
		MetaModel::RegisterPlugin('userrights', 'ACbyProfile', array($this, 'CacheData'));
	}

	protected $m_aUsers = array(); // id -> object
	protected $m_aDimensions = array(); // id -> object
	protected $m_aClassProj = array(); // class,dimensionid -> object
	protected $m_aProfiles = array(); // id -> object
	protected $m_aUserProfiles = array(); // userid,profileid -> object
	protected $m_aProPro = array(); // profileid,dimensionid -> object

	protected $m_aLogin2UserId = array(); // login -> id

	protected $m_aAdmins = array(); // id of users being linked to the well-known admin profile

	protected $m_aClassActionGrants = array(); // profile, class, action -> permission
	protected $m_aClassStimulusGrants = array(); // profile, class, stimulus -> permission
	protected $m_aObjectActionGrants = array(); // userid, class, id, action -> permission, list of attributes

	public function CacheData()
	{
		// Could be loaded in a shared memory (?)

		$oUserSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT URP_Users"));
		$this->m_aUsers = array();
		$this->m_aLogin2UserId = array();  
		while ($oUser = $oUserSet->Fetch())
		{
			$this->m_aUsers[$oUser->GetKey()] = $oUser;
			$this->m_aLogin2UserId[$oUser->Get('login')] = $oUser->GetKey();  
		}

		$oDimensionSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT URP_Dimensions"));
		$this->m_aDimensions = array(); 
		while ($oDimension = $oDimensionSet->Fetch())
		{
			$this->m_aDimensions[$oDimension->GetKey()] = $oDimension; 
		}
		
		$oClassProjSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT URP_ClassProjection"));
		$this->m_aClassProjs = array(); 
		while ($oClassProj = $oClassProjSet->Fetch())
		{
			$this->m_aClassProjs[$oClassProj->Get('class')][$oClassProj->Get('dimensionid')] = $oClassProj; 
		}

		$oProfileSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT URP_Profiles"));
		$this->m_aProfiles = array(); 
		while ($oProfile = $oProfileSet->Fetch())
		{
			$this->m_aProfiles[$oProfile->GetKey()] = $oProfile; 
		}

		$oUserProfileSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT URP_UserProfile"));
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

		$oProProSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT URP_ProfileProjection"));
		$this->m_aProPros = array(); 
		while ($oProPro = $oProProSet->Fetch())
		{
			$this->m_aProPros[$oProPro->Get('profileid')][$oProPro->Get('dimensionid')] = $oProPro; 
		}

/*
		echo "<pre>\n";
		print_r($this->m_aUsers);
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

	public function CheckCredentials($sUserName, $sPassword)
	{
		$oSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT URP_Users WHERE login = :login"), array(), array('login' => $sUserName));
		if ($oSet->Count() < 1)
		{
		// todo: throw an exception?
			return false;
		}

		$oUser = $oSet->Fetch();
		if ($oUser->Get('password') == $sPassword)
		{
			return $oUser->GetKey();
		}
		// todo: throw an exception?
		return false;
	}

	public function GetUserId($sUserName)
	{
		if (array_key_exists($sUserName, $this->m_aLogin2UserId))
		{
			// This happens really when the list of users is being loaded into the cache!!!
			$iUserId = $this->m_aLogin2UserId[$sUserName];  
			return $iUserId;
		}
		return null;
	}

	public function GetUserLanguage($sUserName)
	{
		if (array_key_exists($sUserName, $this->m_aLogin2UserId))
		{
			// This happens really when the list of users is being loaded into the cache!!!
			$iUserId = $this->m_aLogin2UserId[$sUserName];  
			$oUser = $this->m_aUsers[$iUserId];
			return $oUser->Get('language');
		}
		return 'EN US';
	}

	public function GetContactId($sUserName)
	{
		if (array_key_exists($sUserName, $this->m_aLogin2UserId))
		{
			// This happens really when the list of users is being loaded into the cache!!!
			$iUserId = $this->m_aLogin2UserId[$sUserName];  
			$oUser = $this->m_aUsers[$iUserId];
			return $oUser->Get('userid');
		}
		return null;
	}

	public function GetFilter($sUserName, $sClass)
	{
		$oNullFilter  = new DBObjectSearch($sClass);
		return $oNullFilter;
	}

	// This verb has been made public to allow the development of an accurate feedback for the current configuration
	public function GetClassActionGrant($iProfile, $sClass, $sAction)
	{
		if (isset($this->m_aClassActionGrants[$iProfile][$sClass][$sAction]))
		{
			return $this->m_aClassActionGrants[$iProfile][$sClass][$sAction];
		}

		// Get the permission for this profile/class/action
		$oSearch = DBObjectSearch::FromOQL("SELECT URP_ActionGrant WHERE class = :class AND action = :action AND profileid = :profile AND permission = 'yes'");
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
				$oSearch = DBObjectSearch::FromOQL("SELECT URP_AttributeGrant WHERE actiongrantid = :actiongrantid");
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
	
	public function IsActionAllowed($iUserId, $sClass, $iActionCode, $oInstanceSet = null)
	{
		$oUser = $this->m_aUsers[$iUserId];

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

	public function IsActionAllowedOnAttribute($iUserId, $sClass, $sAttCode, $iActionCode, $oInstanceSet = null)
	{
		$oUser = $this->m_aUsers[$iUserId];

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
		$oSearch = DBObjectSearch::FromOQL("SELECT URP_StimulusGrant WHERE class = :class AND stimulus = :stimulus AND profileid = :profile AND permission = 'yes'");
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

	public function IsStimulusAllowed($iUserId, $sClass, $sStimulusCode, $oInstanceSet = null)
	{
		$oUser = $this->m_aUsers[$iUserId];

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

	protected function GetMatchingProfilesByDim($oUser, $oObject, $oDimension)
	{
		//
		// List profiles for which the user projection overlaps the object projection in the given dimension
		//
		$iUser = $oUser->GetKey();
		$sClass = get_class($oObject);
		$iPKey = $oObject->GetKey();
		$iDimension = $oDimension->GetKey();

		$aObjectProjection = $this->m_aClassProjs[$sClass][$iDimension]->ProjectObject($oObject);

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
					$aUserProjection = $this->m_aProPros[$iProfile][$iDimension]->ProjectUser($oUser);

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

//
// Create simple profiles into our user management model:
// - administrator
// - readers
// - contributors
//
class SetupProfiles
{
	protected static $m_aDimensions = array(
		'organization' => array(
			'description' => '',
			'type' => 'bizOrganization',
		),
	);

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

	protected static function DoCreateClassProjection($iDimension, $sClass)
	{
		$oNewObj = MetaModel::NewObject("URP_ClassProjection");
		$oNewObj->Set('dimensionid', $iDimension);
		$oNewObj->Set('class', $sClass);
		$oNewObj->Set('attribute', '');
		$iId = $oNewObj->DBInsertNoReload();
		return $iId;
	}

	protected static function DoCreateDimension($sName, $aDimensionData)
	{
		$oNewObj = MetaModel::NewObject("URP_Dimensions");
		$oNewObj->Set('name', $sName);
		$oNewObj->Set('description', $aDimensionData['description']);
		$oNewObj->Set('type', $aDimensionData['type']);
		$iId = $oNewObj->DBInsertNoReload();
		return $iId;
	}
	
	
	protected static function DoCreateProfileProjection($iProfile, $iDimension)
	{
		$oNewObj = MetaModel::NewObject("URP_ProfileProjection");
		$oNewObj->Set('profileid', $iProfile);
		$oNewObj->Set('dimensionid', $iDimension);
		$oNewObj->Set('value', '<any>');
		$oNewObj->Set('attribute', '');
		$iId = $oNewObj->DBInsertNoReload();
		return $iId;
	}
	
	
	protected static function DoCreateActionGrant($iProfile, $iAction, $sClass, $bPermission = true)
	{
		$oNewObj = MetaModel::NewObject("URP_ActionGrant");
		$oNewObj->Set('profileid', $iProfile);
		$oNewObj->Set('permission', $bPermission);
		$oNewObj->Set('class', $sClass);
		$oNewObj->Set('action', self::$m_aActions[$iAction]);
		$iId = $oNewObj->DBInsertNoReload();
		return $iId;
	}
	
	protected static function DoCreateStimulusGrant($iProfile, $sStimulusCode, $sClass)
	{
		$oNewObj = MetaModel::NewObject("URP_StimulusGrant");
		$oNewObj->Set('profileid', $iProfile);
		$oNewObj->Set('permission', true);
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
	
		// Project in every dimension
		//
		$oDimensionSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT URP_Dimensions"));
		while ($oDimension = $oDimensionSet->Fetch())
		{
			$iDimension = $oDimension->GetKey();
			self::DoCreateProfileProjection($iProfile, $iDimension);
		}
	
		// Grant read rights for everything
		//
		foreach (MetaModel::GetClasses('bizmodel') as $sClass)
		{
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
				throw new CoreException("Invalid class name '$sClass'");
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
	
	public static function DoCreateDimensions()
	{
		$aClass = MetaModel::GetClasses();
		foreach(self::$m_aDimensions as $sName => $aDimensionData)
		{
			$iDimension = self::DoCreateDimension($sName, $aDimensionData);
			
			foreach($aClass as $sClass)
			{
				if (!MetaModel::IsValidClass($sClass))
				{
					throw new CoreException("Invalid class name '$sClass'");
				}
				self::DoCreateClassProjection($iDimension, $sClass);
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
			'General' => array(
				'bizOrganization',
			),
			'Documentation' => array(
				'bizDocument',
				'lnkDocumentRealObject',
				'lnkDocumentContract',
				'lnkDocumentError',
			),
			'Configuration' => array(
				'logRealObject',
				'lnkContactRealObject',
	//			'lnkInterfaces',
				'lnkClientServer',
				'lnkInfraGrouping',
				'lnkContactInfra',
				'lnkContactTeam',
			),
			'Incident' => array(
				'bizIncidentTicket',
				'lnkRelatedTicket',
				'lnkInfraTicket',
				'lnkContactTicket',
			),
			'Problem' => array(
				'bizKnownError',
				'lnkInfraError',
				'lnkDocumentError',
			),
			'Change' => array(
				'bizChangeTicket',
				'lnkInfraChangeTicket',
				'lnkContactChange',
			),
			'Service' => array(
				'bizService',
				'bizContract',
				'lnkInfraContract',
				'lnkContactContract',
				'lnkDocumentContract',
			),
			'Call' => array(
				'bizServiceCall',
				'lnkCallTicket',
				'lnkInfraCall',
			),
		);
		
		self::$m_aProfiles = array(
			'Configuration Manager' => array(
				'description' => 'Person in charge of the documentation of the managed CIs',
				'write_modules' => 'Documentation,Configuration',
				'stimuli' => array(
					'bizServer' => 'any',
					//'bizServer' => 'ev_store,ev_ship,ev_plug,ev_configuration_finished,ev_val_failed,ev_mtp,ev_start_change,ev_end_change,ev_decomission,ev_obsolete,ev_recycle',
					'bizContract' => 'none',
					'bizIncidentTicket' => 'none',
					'bizChangeTicket' => 'none',
				),
			),
			'Service Desk Agent' => array(
				'description' => 'Person in charge of creating incident reports',
				'write_modules' => 'Incident,Call',
				'stimuli' => array(
					'bizServer' => 'none',
					'bizContract' => 'none',
					'bizIncidentTicket' => 'ev_assign',
					'bizChangeTicket' => 'none',
					'bizServiceCall' => 'any',
				),
			),
			'Support Agent' => array(
				'description' => 'Person analyzing and solving the current incidents or problems',
				'write_modules' => 'Incident,Problem',
				'stimuli' => array(
					'bizIncidentTicket' => 'any',
					//'bizIncidentTicket' => 'ev_assign,ev_reassign,ev_start_working,ev_close',
				),
			),
			'Change Implementor' => array(
				'description' => 'Person executing the changes',
				'write_modules' => 'Change',
				'stimuli' => array(
					'bizServer' => 'none',
					'bizContract' => 'none',
					'bizIncidentTicket' => 'none',
					'bizChangeTicket' => 'ev_plan,ev_replan,ev_implement,ev_monitor',
				),
			),
			'Change Supervisor' => array(
				'description' => 'Person responsible for the overall change execution',
				'write_modules' => 'Change',
				'stimuli' => array(
					'bizServer' => 'none',
					'bizContract' => 'none',
					'bizIncidentTicket' => 'none',
					'bizChangeTicket' => 'ev_assign,ev_validate,ev_reject,ev_reopen,ev_finish',
				),
			),
			'Change Approver' => array(
				'description' => 'Person who could be impacted by some changes',
				'write_modules' => 'Change',
				'stimuli' => array(
					'bizServer' => 'none',
					'bizContract' => 'none',
					'bizIncidentTicket' => 'none',
					'bizChangeTicket' => 'ev_approve,ev_notapprove',
				),
			),
			'Service Manager' => array(
				'description' => 'Person responsible for the service delivered to the [internal] customer',
				'write_modules' => 'Service',
				'stimuli' => array(
					'bizServer' => 'none',
					'bizContract' => 'any',
					//'bizContract' => 'ev_freeze_version,ev_sign,ev_begin,ev_notice,ev_terminate,ev_elapsed',
					'bizIncidentTicket' => 'none',
					'bizChangeTicket' => 'none',
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

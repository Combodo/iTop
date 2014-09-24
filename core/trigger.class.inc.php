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
 * Persistent class Trigger and derived
 * User defined triggers, that may be used in conjunction with user defined actions 
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


/**
 * A user defined trigger, to customize the application 
 * A trigger will activate an action
 *
 * @package     iTopORM
 */
abstract class Trigger extends cmdbAbstractObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "core/cmdb",
			"key_type" => "autoincrement",
			"name_attcode" => "description",
			"state_attcode" => "",
			"reconc_keys" => array('description'),
			"db_table" => "priv_trigger",
			"db_key_field" => "id",
			"db_finalclass_field" => "realclass",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		//MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeString("description", array("allowed_values"=>null, "sql"=>"description", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("action_list", array("linked_class"=>"lnkTriggerAction", "ext_key_to_me"=>"trigger_id", "ext_key_to_remote"=>"action_id", "allowed_values"=>null, "count_min"=>1, "count_max"=>0, "depends_on"=>array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('finalclass', 'description', 'action_list')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('finalclass')); // Attributes to be displayed for a list
		// Search criteria
//		MetaModel::Init_SetZListItems('standard_search', array('name')); // Criteria of the std search form
//		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
	}

	public function DoActivate($aContextArgs)
	{
		// Find the related actions
		$oLinkedActions = $this->Get('action_list');
		while ($oLink = $oLinkedActions->Fetch())
		{
			$iActionId = $oLink->Get('action_id');
			$oAction = MetaModel::GetObject('Action', $iActionId);
			if ($oAction->IsActive())
			{
				$oAction->DoExecute($this, $aContextArgs);
			}
		}
	}
	
	/**
	 * Check whether the given object is in the scope of this trigger
	 * and can potentially be the subject of notifications
	 * @param DBObject $oObject The object to check
	 * @return bool
	 */
	public function IsInScope(DBObject $oObject)
	{
		// By default the answer is no
		// Overload this function in your own derived class for a different behavior
		return false;
	}
}

abstract class TriggerOnObject extends Trigger
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "core/cmdb",
			"key_type" => "autoincrement",
			"name_attcode" => "description",
			"state_attcode" => "",
			"reconc_keys" => array('description'),
			"db_table" => "priv_trigger_onobject",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeClass("target_class", array("class_category"=>"bizmodel", "more_values"=>"User,UserExternal,UserInternal,UserLDAP,UserLocal", "sql"=>"target_class", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeOQL("filter", array("allowed_values"=>null, "sql"=>"filter", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('description', 'target_class', 'filter', 'action_list')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('finalclass', 'target_class', 'description')); // Attributes to be displayed for a list
		// Search criteria
//		MetaModel::Init_SetZListItems('standard_search', array('name')); // Criteria of the std search form
//		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
	}

	public function DoCheckToWrite()
	{
		parent::DoCheckToWrite();

		$sFilter = trim($this->Get('filter'));
		if (strlen($sFilter) > 0)
		{
			try
			{
				$oSearch = DBObjectSearch::FromOQL($sFilter);

				if (!MetaModel::IsParentClass($this->Get('target_class'), $oSearch->GetClass()))
				{
					$this->m_aCheckIssues[] = Dict::Format('TriggerOnObject:WrongFilterClass', $this->Get('target_class'));
				}
			}
			catch(OqlException $e)
			{
				$this->m_aCheckIssues[] = Dict::Format('TriggerOnObject:WrongFilterQuery', $e->getMessage());
			}
		}
	}

	/**
	 * Check whether the given object is in the scope of this trigger
	 * and can potentially be the subject of notifications
	 * @param DBObject $oObject The object to check
	 * @return bool
	 */
	public function IsInScope(DBObject $oObject)
	{
		$sRootClass = $this->Get('target_class');
		return ($oObject instanceof $sRootClass);
	}

	public function DoActivate($aContextArgs)
	{
		$bGo = true;
		if (isset($aContextArgs['this->id']))
		{
			$bGo = $this->IsTargetObject($aContextArgs['this->id']);
		}
		if ($bGo)
		{
			parent::DoActivate($aContextArgs);
		}
	}

	public function IsTargetObject($iObjectId)
	{
		$sFilter = trim($this->Get('filter'));
		if (strlen($sFilter) > 0)
		{
			$oSearch = DBObjectSearch::FromOQL($sFilter);
			$oSearch->AddCondition('id', $iObjectId, '=');
			$oSet = new DBObjectSet($oSearch);
			$bRet = ($oSet->Count() > 0);
		}
		else
		{
			$bRet = true;
		}
		return $bRet;
	}
}
/**
 * To trigger notifications when a ticket is updated from the portal
 */
class TriggerOnPortalUpdate extends TriggerOnObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "core/cmdb,bizmodel",
			"key_type" => "autoincrement",
			"name_attcode" => "description",
			"state_attcode" => "",
			"reconc_keys" => array('description'),
			"db_table" => "priv_trigger_onportalupdate",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		// Display lists
		MetaModel::Init_SetZListItems('details', array('description', 'target_class', 'filter', 'action_list')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('finalclass', 'target_class', 'description')); // Attributes to be displayed for a list
		// Search criteria
	}
}

abstract class TriggerOnStateChange extends TriggerOnObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "core/cmdb",
			"key_type" => "autoincrement",
			"name_attcode" => "description",
			"state_attcode" => "",
			"reconc_keys" => array('description'),
			"db_table" => "priv_trigger_onstatechange",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeString("state", array("allowed_values"=>null, "sql"=>"state", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));	

		// Display lists
		MetaModel::Init_SetZListItems('details', array('description', 'target_class', 'filter', 'state', 'action_list')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('finalclass', 'target_class', 'state')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('description', 'target_class', 'state')); // Criteria of the std search form
//		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
	}
}

class TriggerOnStateEnter extends TriggerOnStateChange
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "core/cmdb,bizmodel",
			"key_type" => "autoincrement",
			"name_attcode" => "description",
			"state_attcode" => "",
			"reconc_keys" => array('description'),
			"db_table" => "priv_trigger_onstateenter",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		// Display lists
		MetaModel::Init_SetZListItems('details', array('description', 'target_class', 'filter', 'state', 'action_list')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('target_class', 'state')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('description', 'target_class', 'state')); // Criteria of the std search form
//		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
	}
}

class TriggerOnStateLeave extends TriggerOnStateChange
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "core/cmdb,bizmodel",
			"key_type" => "autoincrement",
			"name_attcode" => "description",
			"state_attcode" => "",
			"reconc_keys" => array('description'),
			"db_table" => "priv_trigger_onstateleave",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		// Display lists
		MetaModel::Init_SetZListItems('details', array('description', 'target_class', 'filter', 'state', 'action_list')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('target_class', 'state')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('description', 'target_class', 'state')); // Criteria of the std search form
//		MetaModel::Init_SetZListItems('advanced_search', array('')); // Criteria of the advanced search form
	}
}

class TriggerOnObjectCreate extends TriggerOnObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "core/cmdb,bizmodel",
			"key_type" => "autoincrement",
			"name_attcode" => "description",
			"state_attcode" => "",
			"reconc_keys" => array('description'),
			"db_table" => "priv_trigger_onobjcreate",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		// Display lists
		MetaModel::Init_SetZListItems('details', array('description', 'target_class', 'filter', 'action_list')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('finalclass', 'target_class')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('description', 'target_class')); // Criteria of the std search form
//		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
	}
}

class lnkTriggerAction extends cmdbAbstractObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "core/cmdb,bizmodel",
			"key_type" => "autoincrement",
			"name_attcode" => "",
			"state_attcode" => "",
			"reconc_keys" => array('action_id', 'trigger_id'),
			"db_table" => "priv_link_action_trigger",
			"db_key_field" => "link_id",
			"db_finalclass_field" => "",
			"display_template" => "",
			"is_link" => true,
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_AddAttribute(new AttributeExternalKey("action_id", array("targetclass"=>"Action", "jointype"=> '', "allowed_values"=>null, "sql"=>"action_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("action_name", array("allowed_values"=>null, "extkey_attcode"=> 'action_id', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("trigger_id", array("targetclass"=>"Trigger", "jointype"=> '', "allowed_values"=>null, "sql"=>"trigger_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("trigger_name", array("allowed_values"=>null, "extkey_attcode"=> 'trigger_id', "target_attcode"=>"description")));
		MetaModel::Init_AddAttribute(new AttributeInteger("order", array("allowed_values"=>null, "sql"=>"order", "default_value"=>0, "is_null_allowed"=>true, "depends_on"=>array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('action_id', 'trigger_id', 'order')); // Attributes to be displayed for a list
		MetaModel::Init_SetZListItems('list', array('action_id', 'trigger_id', 'order')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('action_id', 'trigger_id', 'order')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('action_id', 'trigger_id', 'order')); // Criteria of the advanced search form
	}
}

class TriggerOnThresholdReached extends TriggerOnObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "core/cmdb,bizmodel",
			"key_type" => "autoincrement",
			"name_attcode" => "description",
			"state_attcode" => "",
			"reconc_keys" => array('description'),
			"db_table" => "priv_trigger_threshold",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeString("stop_watch_code", array("allowed_values"=>null, "sql"=>"stop_watch_code", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));	
		MetaModel::Init_AddAttribute(new AttributeString("threshold_index", array("allowed_values"=>null, "sql"=>"threshold_index", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));	

		// Display lists
		MetaModel::Init_SetZListItems('details', array('description', 'target_class', 'stop_watch_code', 'threshold_index', 'action_list')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('target_class', 'threshold_index', 'threshold_index')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('description', 'target_class')); // Criteria of the std search form
//		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
	}
}
?>

<?php

/**
 * A user defined trigger, to customize the application 
 * A trigger will activate an action
 *
 * @package     iTopORM
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @author      Denis Flaven <denisflave@free.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     1.1.1.1 $
 */
class Trigger extends cmdbAbstractObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "core/cmdb",
			"name" => "trigger",
			"description" => "Custom event handler",
			"key_type" => "autoincrement",
			"key_label" => "",
			"name_attcode" => "",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_trigger",
			"db_key_field" => "id",
			"db_finalclass_field" => "realclass",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		//MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeString("description", array("label"=>"Description", "description"=>"one line description", "allowed_values"=>null, "sql"=>"description", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("linked_actions", array("label"=>"Triggered actions", "description"=>"Actions performed when the trigger is activated", "linked_class"=>"lnkTriggerAction", "ext_key_to_me"=>"trigger_id", "ext_key_to_remote"=>"action_id", "allowed_values"=>null, "count_min"=>1, "count_max"=>0, "depends_on"=>array())));

		//MetaModel::Init_InheritFilters();
		MetaModel::Init_AddFilterFromAttribute("description");

		// Display lists
		MetaModel::Init_SetZListItems('details', array('finalclass', 'description')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('finalclass', 'description')); // Attributes to be displayed for a list
		// Search criteria
//		MetaModel::Init_SetZListItems('standard_search', array('name')); // Criteria of the std search form
//		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
	}

	public function DoActivate($aContextArgs)
	{
		// Find the related 
		$oLinkedActions = $this->Get('linked_actions');
		while ($oLink = $oLinkedActions->Fetch())
		{
			$iActionId = $oLink->Get('action_id');
			$oAction = MetaModel::GetObject('Action', $iActionId);
			$oAction->DoExecute($this, $aContextArgs);
		}
	}
}

class TriggerOnStateChange extends Trigger
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "core/cmdb",
			"name" => "Trigger on object state change",
			"description" => "Trigger on object state change",
			"key_type" => "autoincrement",
			"key_label" => "",
			"name_attcode" => "",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_trigger_onstatechange",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeClass("target_class", array("label"=>"Target class", "description"=>"label", "class_category"=>"bizmodel", "more_values"=>null, "sql"=>"target_class", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("state", array("label"=>"State", "description"=>"label", "allowed_values"=>null, "sql"=>"state", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));	

		MetaModel::Init_InheritFilters();
		MetaModel::Init_AddFilterFromAttribute("target_class");
		MetaModel::Init_AddFilterFromAttribute("state");

		// Display lists
		MetaModel::Init_SetZListItems('details', array('description', 'target_class', 'state')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('finalclass', 'target_class', 'state')); // Attributes to be displayed for a list
		// Search criteria
//		MetaModel::Init_SetZListItems('standard_search', array('name')); // Criteria of the std search form
//		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
	}
}

class TriggerOnStateEnter extends TriggerOnStateChange
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "core/cmdb",
			"name" => "Trigger on object entering a state",
			"description" => "Trigger on object state change - entering",
			"key_type" => "autoincrement",
			"key_label" => "",
			"name_attcode" => "",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_trigger_onstateenter",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_InheritFilters();

		// Display lists
		MetaModel::Init_SetZListItems('details', array('description', 'target_class', 'state')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('target_class', 'state')); // Attributes to be displayed for a list
		// Search criteria
//		MetaModel::Init_SetZListItems('standard_search', array('name')); // Criteria of the std search form
//		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
	}
}

class TriggerOnStateLeave extends TriggerOnStateChange
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "core/cmdb",
			"name" => "Trigger on object leaving a state",
			"description" => "Trigger on object state change - leaving",
			"key_type" => "autoincrement",
			"key_label" => "",
			"name_attcode" => "",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_trigger_onstateleave",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_InheritFilters();

		// Display lists
		MetaModel::Init_SetZListItems('details', array('description', 'target_class', 'state')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('target_class', 'state')); // Attributes to be displayed for a list
		// Search criteria
//		MetaModel::Init_SetZListItems('standard_search', array('')); // Criteria of the std search form
//		MetaModel::Init_SetZListItems('advanced_search', array('')); // Criteria of the advanced search form
	}
}

class TriggerOnObjectCreate extends Trigger
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "core/cmdb",
			"name" => "Trigger on object creation",
			"description" => "Trigger on object creation of [a child class of] the given class",
			"key_type" => "autoincrement",
			"key_label" => "",
			"name_attcode" => "",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_trigger_onobjcreate",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeClass("target_class", array("label"=>"Target class", "description"=>"label", "class_category"=>"bizmodel", "more_values"=>null, "sql"=>"target_class", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));

		MetaModel::Init_InheritFilters();
		MetaModel::Init_AddFilterFromAttribute("target_class");

		// Display lists
		MetaModel::Init_SetZListItems('details', array('description', 'target_class')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('finalclass', 'target_class')); // Attributes to be displayed for a list
		// Search criteria
//		MetaModel::Init_SetZListItems('standard_search', array('name')); // Criteria of the std search form
//		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
	}
}

class lnkTriggerAction extends cmdbAbstractObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "core/cmdb",
			"name" => "Actions-Trigger",
			"description" => "Link between a trigger and an action",
			"key_type" => "autoincrement",
			"key_label" => "Link ID",
			"name_attcode" => "",
			"state_attcode" => "",
			"reconc_keys" => array(""),
			"db_table" => "priv_link_action_trigger",
			"db_key_field" => "link_id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_AddAttribute(new AttributeExternalKey("action_id", array("targetclass"=>"Action", "jointype"=> '', "label"=>"Action", "description"=>"The action to be executed", "allowed_values"=>null, "sql"=>"action_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("action_name", array("label"=>"Action Name", "description"=>"Name of the action", "allowed_values"=>null, "extkey_attcode"=> 'action_id', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("trigger_id", array("targetclass"=>"Trigger", "jointype"=> '', "label"=>"Trigger", "description"=>"Trigger", "allowed_values"=>null, "sql"=>"trigger_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeInteger("order", array("label"=>"Order", "description"=>"Actions execution order", "allowed_values"=>null, "sql"=>"order", "default_value"=>0, "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_AddFilterFromAttribute("action_id");
		MetaModel::Init_AddFilterFromAttribute("trigger_id");
		
		// Display lists
		MetaModel::Init_SetZListItems('details', array('action_id', 'trigger_id', 'order')); // Attributes to be displayed for a list
		MetaModel::Init_SetZListItems('list', array('action_id', 'trigger_id', 'order')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('action_id', 'trigger_id', 'order')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('action_id', 'trigger_id', 'order')); // Criteria of the advanced search form
	}
}
?>

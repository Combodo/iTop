<?php

class EventNotification extends Event
{
    public static function Init()
    {
        $aParams = array
        (
            "category" => "core/cmdb,view_in_gui",
            "key_type" => "autoincrement",
            "name_attcode" => "",
            "state_attcode" => "",
            "reconc_keys" => array(),
            "db_table" => "priv_event_notification",
            "db_key_field" => "id",
            "db_finalclass_field" => "",
            "order_by_default" => array('date' => false),
            'indexes' => array(
                array('object_class', 'object_id'),
            )
        );
        MetaModel::Init_Params($aParams);
        MetaModel::Init_InheritAttributes();
        MetaModel::Init_AddAttribute(new AttributeExternalKey("trigger_id", array("targetclass" => "Trigger", "jointype" => "", "allowed_values" => null, "sql" => "trigger_id", "is_null_allowed" => false, "on_target_delete" => DEL_AUTO, "depends_on" => array())));
        MetaModel::Init_AddAttribute(new AttributeExternalKey("action_id", array("targetclass" => "Action", "jointype" => "", "allowed_values" => null, "sql" => "action_id", "is_null_allowed" => false, "on_target_delete" => DEL_AUTO, "depends_on" => array())));
        MetaModel::Init_AddAttribute(new AttributeInteger("object_id", array("allowed_values" => null, "sql" => "object_id", "default_value" => 0, "is_null_allowed" => false, "depends_on" => array())));
        //@since 3.2.0
        MetaModel::Init_AddAttribute(new AttributeClass("object_class", array("class_category" => "", "more_values" => "", "sql" => "object_class", "default_value" => null, "is_null_allowed" => true /*to avoid setting AbstractResource as default in database*/, "depends_on" => array())));

        // Display lists
        MetaModel::Init_SetZListItems('details', array('date', 'message', 'userinfo', 'trigger_id', 'action_id', 'object_class', 'object_id')); // Attributes to be displayed for the complete details
        MetaModel::Init_SetZListItems('list', array('date', 'message')); // Attributes to be displayed for a list
        // Search criteria
//		MetaModel::Init_SetZListItems('standard_search', array('name')); // Criteria of the std search form
//		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
    }
}
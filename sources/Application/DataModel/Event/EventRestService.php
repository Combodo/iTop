<?php

class EventRestService extends Event
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
            "db_table" => "priv_event_restservice",
            "db_key_field" => "id",
            "db_finalclass_field" => "",
            "order_by_default" => array('date' => false)
        );
        MetaModel::Init_Params($aParams);
        MetaModel::Init_InheritAttributes();
        MetaModel::Init_AddAttribute(new AttributeString("operation", array("allowed_values" => null, "sql" => "operation", "default_value" => null, "is_null_allowed" => true, "depends_on" => array())));
        MetaModel::Init_AddAttribute(new AttributeString("version", array("allowed_values" => null, "sql" => "version", "default_value" => null, "is_null_allowed" => true, "depends_on" => array())));
        MetaModel::Init_AddAttribute(new AttributeText("json_input", array("allowed_values" => null, "sql" => "json_input", "default_value" => null, "is_null_allowed" => true, "depends_on" => array())));

        MetaModel::Init_AddAttribute(new AttributeInteger("code", array("allowed_values" => null, "sql" => "code", "default_value" => 0, "is_null_allowed" => false, "depends_on" => array())));
        MetaModel::Init_AddAttribute(new AttributeText("json_output", array("allowed_values" => null, "sql" => "json_output", "default_value" => null, "is_null_allowed" => true, "depends_on" => array())));
        MetaModel::Init_AddAttribute(new AttributeString("provider", array("allowed_values" => null, "sql" => "provider", "default_value" => null, "is_null_allowed" => true, "depends_on" => array())));

        // Display lists
        MetaModel::Init_SetZListItems('details', array('date', 'userinfo', 'operation', 'version', 'json_input', 'message', 'code', 'json_output', 'provider')); // Attributes to be displayed for the complete details
        MetaModel::Init_SetZListItems('list', array('date', 'userinfo', 'operation', 'message')); // Attributes to be displayed for a list
        // Search criteria
//		MetaModel::Init_SetZListItems('standard_search', array('name')); // Criteria of the std search form
//		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
    }
}
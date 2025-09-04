<?php

class EventWebService extends Event
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
            "db_table" => "priv_event_webservice",
            "db_key_field" => "id",
            "db_finalclass_field" => "",
            "order_by_default" => array('date' => false)
        );
        MetaModel::Init_Params($aParams);
        MetaModel::Init_InheritAttributes();
        MetaModel::Init_AddAttribute(new AttributeString("verb", array("allowed_values" => null, "sql" => "verb", "default_value" => null, "is_null_allowed" => false, "depends_on" => array())));
        //MetaModel::Init_AddAttribute(new AttributeStructure("arguments", array("allowed_values"=>null, "sql"=>"data", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
        MetaModel::Init_AddAttribute(new AttributeBoolean("result", array("allowed_values" => null, "sql" => "result", "default_value" => null, "is_null_allowed" => true, "depends_on" => array())));
        MetaModel::Init_AddAttribute(new AttributeText("log_info", array("allowed_values" => null, "sql" => "log_info", "default_value" => null, "is_null_allowed" => true, "depends_on" => array())));
        MetaModel::Init_AddAttribute(new AttributeText("log_warning", array("allowed_values" => null, "sql" => "log_warning", "default_value" => null, "is_null_allowed" => true, "depends_on" => array())));
        MetaModel::Init_AddAttribute(new AttributeText("log_error", array("allowed_values" => null, "sql" => "log_error", "default_value" => null, "is_null_allowed" => true, "depends_on" => array())));
        MetaModel::Init_AddAttribute(new AttributeText("data", array("allowed_values" => null, "sql" => "data", "default_value" => null, "is_null_allowed" => true, "depends_on" => array())));

        // Display lists
        MetaModel::Init_SetZListItems('details', array('date', 'userinfo', 'verb', 'result', 'log_info', 'log_warning', 'log_error', 'data')); // Attributes to be displayed for the complete details
        MetaModel::Init_SetZListItems('list', array('date', 'userinfo', 'verb', 'result')); // Attributes to be displayed for a list
        // Search criteria
//		MetaModel::Init_SetZListItems('standard_search', array('name')); // Criteria of the std search form
//		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
    }
}
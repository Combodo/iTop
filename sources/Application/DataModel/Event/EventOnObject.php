<?php

class EventOnObject extends Event
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
            "db_table" => "priv_event_onobject",
            "db_key_field" => "id",
            "db_finalclass_field" => "",
            "display_template" => "",
            "order_by_default" => array('date' => false)
        );
        MetaModel::Init_Params($aParams);
        MetaModel::Init_InheritAttributes();
        MetaModel::Init_AddAttribute(new AttributeString("obj_class", array("allowed_values" => null, "sql" => "obj_class", "default_value" => null, "is_null_allowed" => false, "depends_on" => array())));
        MetaModel::Init_AddAttribute(new AttributeInteger("obj_key", array("allowed_values" => null, "sql" => "obj_key", "default_value" => null, "is_null_allowed" => false, "depends_on" => array())));

        // Display lists
        MetaModel::Init_SetZListItems('details', array('date', 'userinfo', 'obj_class', 'obj_key', 'message')); // Attributes to be displayed for the complete details
        MetaModel::Init_SetZListItems('list', array('date', 'userinfo', 'obj_class', 'obj_key', 'message')); // Attributes to be displayed for a list
    }
}
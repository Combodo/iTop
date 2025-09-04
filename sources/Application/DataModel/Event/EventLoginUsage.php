<?php

class EventLoginUsage extends Event
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
            "db_table" => "priv_event_loginusage",
            "db_key_field" => "id",
            "db_finalclass_field" => "",
            "order_by_default" => array('date' => false)
        );
        MetaModel::Init_Params($aParams);
        MetaModel::Init_InheritAttributes();

        MetaModel::Init_AddAttribute(new AttributeExternalKey("user_id", array("targetclass" => "User", "jointype" => "", "allowed_values" => null, "sql" => "user_id", "is_null_allowed" => false, "on_target_delete" => DEL_SILENT, "depends_on" => array())));
        $aZList = array('date', 'user_id');
        if (MetaModel::IsValidAttCode('Contact', 'name')) {
            MetaModel::Init_AddAttribute(new AttributeExternalField("contact_name", array("allowed_values" => null, "extkey_attcode" => "user_id", "target_attcode" => "contactid", "is_null_allowed" => true, "depends_on" => array())));
            $aZList[] = 'contact_name';
        }
        if (MetaModel::IsValidAttCode('Contact', 'email')) {
            MetaModel::Init_AddAttribute(new AttributeExternalField("contact_email", array("allowed_values" => null, "extkey_attcode" => "user_id", "target_attcode" => "email", "is_null_allowed" => true, "depends_on" => array())));
            $aZList[] = 'contact_email';
        }
        // Display lists
        MetaModel::Init_SetZListItems('details', array_merge($aZList, array('userinfo', 'message'))); // Attributes to be displayed for the complete details
        MetaModel::Init_SetZListItems('list', array_merge($aZList, array('userinfo'))); // Attributes to be displayed for a list
        // Search criteria
        MetaModel::Init_SetZListItems('standard_search', $aZList); // Criteria of the std search form
//		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
    }
}
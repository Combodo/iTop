<?php

class EventNotificationEmail extends EventNotification
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
            "db_table" => "priv_event_email",
            "db_key_field" => "id",
            "db_finalclass_field" => "",
            "order_by_default" => array('date' => false)
        );
        MetaModel::Init_Params($aParams);
        MetaModel::Init_InheritAttributes();
        MetaModel::Init_AddAttribute(new AttributeText("to", array("allowed_values" => null, "sql" => "to", "default_value" => null, "is_null_allowed" => true, "depends_on" => array())));
        MetaModel::Init_AddAttribute(new AttributeText("cc", array("allowed_values" => null, "sql" => "cc", "default_value" => null, "is_null_allowed" => true, "depends_on" => array())));
        MetaModel::Init_AddAttribute(new AttributeText("bcc", array("allowed_values" => null, "sql" => "bcc", "default_value" => null, "is_null_allowed" => true, "depends_on" => array())));
        MetaModel::Init_AddAttribute(new AttributeText("from", array("allowed_values" => null, "sql" => "from", "default_value" => null, "is_null_allowed" => true, "depends_on" => array())));
        MetaModel::Init_AddAttribute(new AttributeText("subject", array("allowed_values" => null, "sql" => "subject", "default_value" => null, "is_null_allowed" => true, "depends_on" => array())));
        MetaModel::Init_AddAttribute(new AttributeHTML("body", array("allowed_values" => null, "sql" => "body", "default_value" => null, "is_null_allowed" => true, "depends_on" => array())));
        MetaModel::Init_AddAttribute(new AttributeTable("attachments", array("allowed_values" => null, "sql" => "attachments", "default_value" => null, "is_null_allowed" => true, "depends_on" => array())));

        // Display lists
        MetaModel::Init_SetZListItems('details', array('date', 'userinfo', 'message', 'trigger_id', 'action_id', 'object_class', 'object_id', 'to', 'cc', 'bcc', 'from', 'subject', 'body', 'attachments')); // Attributes to be displayed for the complete details
        MetaModel::Init_SetZListItems('list', array('date', 'message', 'to', 'subject', 'attachments')); // Attributes to be displayed for a list

        // Search criteria
//		MetaModel::Init_SetZListItems('standard_search', array('name')); // Criteria of the std search form
//		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
    }
}
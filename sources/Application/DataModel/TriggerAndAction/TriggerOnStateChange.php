<?php

/**
 * Class TriggerOnStateChange
 */
abstract class TriggerOnStateChange extends TriggerOnObject
{
    /**
     * @throws \CoreException
     * @throws \Exception
     */
    public static function Init()
    {
        $aParams = array
        (
            "category" => "grant_by_profile,core/cmdb",
            "key_type" => "autoincrement",
            "name_attcode" => "description",
            "complementary_name_attcode" => ['finalclass', 'complement'],
            "state_attcode" => "",
            "reconc_keys" => ['description'],
            "db_table" => "priv_trigger_onstatechange",
            "db_key_field" => "id",
            "db_finalclass_field" => "",
        );
        MetaModel::Init_Params($aParams);
        MetaModel::Init_InheritAttributes();
        MetaModel::Init_AddAttribute(new AttributeClassState("state", array("class_field" => 'target_class', "allowed_values" => null, "sql" => "state", "default_value" => null, "is_null_allowed" => false, "depends_on" => array('target_class'))));

        // Display lists
        MetaModel::Init_SetZListItems('details', array('description', 'context', 'target_class', 'filter', 'state', 'subscription_policy', 'action_list')); // Attributes to be displayed for the complete details
        MetaModel::Init_SetZListItems('list', array('finalclass', 'target_class', 'state')); // Attributes to be displayed for a list
        // Search criteria
        MetaModel::Init_SetZListItems('standard_search', array('description', 'target_class', 'state')); // Criteria of the std search form
        //		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
    }
}
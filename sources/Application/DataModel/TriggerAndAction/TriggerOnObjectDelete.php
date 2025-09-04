<?php

/**
 * Class TriggerOnObjectCreate
 */
class TriggerOnObjectDelete extends TriggerOnObject
{
    /**
     * @throws \CoreException
     */
    public static function Init()
    {
        $aParams = array
        (
            "category" => "grant_by_profile,core/cmdb,application",
            "key_type" => "autoincrement",
            "name_attcode" => "description",
            "complementary_name_attcode" => ['finalclass', 'complement'],
            "state_attcode" => "",
            "reconc_keys" => ['description'],
            "db_table" => "priv_trigger_onobjdelete",
            "db_key_field" => "id",
            "db_finalclass_field" => "",
        );
        MetaModel::Init_Params($aParams);
        MetaModel::Init_InheritAttributes();

        // Display lists
        MetaModel::Init_SetZListItems('details', array('description', 'context', 'target_class', 'filter', 'subscription_policy', 'action_list')); // Attributes to be displayed for the complete details
        MetaModel::Init_SetZListItems('list', array('finalclass', 'target_class')); // Attributes to be displayed for a list
        // Search criteria
        MetaModel::Init_SetZListItems('standard_search', array('description', 'target_class')); // Criteria of the std search form
        //		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
    }
}
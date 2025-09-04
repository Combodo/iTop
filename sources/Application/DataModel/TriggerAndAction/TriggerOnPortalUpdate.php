<?php

/**
 * To trigger notifications when a ticket is updated from the portal
 */
class TriggerOnPortalUpdate extends TriggerOnObject
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
            "db_table" => "priv_trigger_onportalupdate",
            "db_key_field" => "id",
            "db_finalclass_field" => "",
        );
        MetaModel::Init_Params($aParams);
        MetaModel::Init_InheritAttributes();

        // Display lists
        MetaModel::Init_SetZListItems('details', array('description', 'context', 'target_class', 'filter', 'subscription_policy', 'action_list')); // Attributes to be displayed for the complete details
        MetaModel::Init_SetZListItems('list', array('finalclass', 'target_class', 'description')); // Attributes to be displayed for a list
        // Search criteria
    }
}
<?php

/**
 * Class lnkTriggerAction
 */
class lnkTriggerAction extends cmdbAbstractObject
{
    /**
     * @throws \CoreException
     * @throws \Exception
     */
    public static function Init()
    {
        $aParams = array
        (
            "category" => "grant_by_profile,core/cmdb,application",
            "key_type" => "autoincrement",
            "name_attcode" => "",
            "state_attcode" => "",
            "reconc_keys" => array('action_id', 'trigger_id'),
            "db_table" => "priv_link_action_trigger",
            "db_key_field" => "link_id",
            "db_finalclass_field" => "",
            "is_link" => true,
            'uniqueness_rules' => array(
                'no_duplicate' => array(
                    'attributes' => array(
                        0 => 'action_id',
                        1 => 'trigger_id',
                    ),
                    'filter' => '',
                    'disabled' => false,
                    'is_blocking' => true,
                ),
            ),
        );
        MetaModel::Init_Params($aParams);
        MetaModel::Init_AddAttribute(new AttributeExternalKey("action_id", array("targetclass" => "Action", "jointype" => '', "allowed_values" => null, "sql" => "action_id", "is_null_allowed" => false, "on_target_delete" => DEL_AUTO, "depends_on" => array())));
        MetaModel::Init_AddAttribute(new AttributeExternalField("action_name", array("allowed_values" => null, "extkey_attcode" => 'action_id', "target_attcode" => "name")));
        MetaModel::Init_AddAttribute(new AttributeExternalKey("trigger_id", array("targetclass" => "Trigger", "jointype" => '', "allowed_values" => null, "sql" => "trigger_id", "is_null_allowed" => false, "on_target_delete" => DEL_AUTO, "depends_on" => array())));
        MetaModel::Init_AddAttribute(new AttributeExternalField("trigger_name", array("allowed_values" => null, "extkey_attcode" => 'trigger_id', "target_attcode" => "description")));
        MetaModel::Init_AddAttribute(new AttributeInteger("order", array("allowed_values" => null, "sql" => "order", "default_value" => 0, "is_null_allowed" => true, "depends_on" => array())));

        // Display lists
        MetaModel::Init_SetZListItems('details', array('action_id', 'trigger_id', 'order')); // Attributes to be displayed for a list
        MetaModel::Init_SetZListItems('list', array('action_id', 'trigger_id', 'order')); // Attributes to be displayed for a list
        // Search criteria
        MetaModel::Init_SetZListItems('standard_search', array('action_id', 'trigger_id', 'order')); // Criteria of the std search form
        MetaModel::Init_SetZListItems('advanced_search', array('action_id', 'trigger_id', 'order')); // Criteria of the advanced search form
    }
}
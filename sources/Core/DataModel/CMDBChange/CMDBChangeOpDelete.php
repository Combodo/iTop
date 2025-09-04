<?php

/**
 * Record the deletion of an object
 *
 * @package     iTopORM
 */
class CMDBChangeOpDelete extends CMDBChangeOp
{
    /**
     * @inheritDoc
     */
    public static function Init()
    {
        $aParams = array
        (
            "category" => "core/cmdb, grant_by_profile",
            "key_type" => "",
            "name_attcode" => "change",
            "state_attcode" => "",
            "reconc_keys" => array(),
            "db_table" => "priv_changeop_delete",
            "db_key_field" => "id",
            "db_finalclass_field" => "",
        );
        MetaModel::Init_Params($aParams);
        MetaModel::Init_InheritAttributes();

        // Final class of the object (objclass must be set to the root class for efficiency purposes)
        MetaModel::Init_AddAttribute(new AttributeString("fclass", array("allowed_values" => null, "sql" => "fclass", "default_value" => "", "is_null_allowed" => false, "depends_on" => array())));
        // Last friendly name of the object
        MetaModel::Init_AddAttribute(new AttributeString("fname", array("allowed_values" => null, "sql" => "fname", "default_value" => "", "is_null_allowed" => true, "depends_on" => array())));
    }

    /**
     * @inheritDoc
     */
    public function GetDescription()
    {
        return Dict::S('Change:ObjectDeleted');
    }
}
<?php

/**
 * Record the creation of an object
 *
 * @package     iTopORM
 */
class CMDBChangeOpCreate extends CMDBChangeOp
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
            "db_table" => "priv_changeop_create",
            "db_key_field" => "id",
            "db_finalclass_field" => "",
        );
        MetaModel::Init_Params($aParams);
        MetaModel::Init_InheritAttributes();
    }

    /**
     * @inheritDoc
     */
    public function GetDescription()
    {
        return Dict::S('Change:ObjectCreated');
    }
}
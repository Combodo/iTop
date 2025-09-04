<?php

/**
 * Record added/removed objects from within a link set
 *
 * @package     iTopORM
 */
abstract class CMDBChangeOpSetAttributeLinks extends CMDBChangeOpSetAttribute
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
            "db_table" => "priv_changeop_links",
            "db_key_field" => "id",
            "db_finalclass_field" => "",
        );
        MetaModel::Init_Params($aParams);
        MetaModel::Init_InheritAttributes();

        // Note: item class/id points to the link class itself in case of a direct link set (e.g. Server::interface_list => Interface)
        //       item class/id points to the remote class in case of a indirect link set (e.g. Server::contract_list => Contract)
        MetaModel::Init_AddAttribute(new AttributeString("item_class", array("allowed_values" => null, "sql" => "item_class", "default_value" => '', "is_null_allowed" => false, "depends_on" => array())));
        MetaModel::Init_AddAttribute(new AttributeInteger("item_id", array("allowed_values" => null, "sql" => "item_id", "default_value" => 0, "is_null_allowed" => false, "depends_on" => array())));
    }
}
<?php

/**
 * Record an action made by a plug-in
 *
 * @package     iTopORM
 */
class CMDBChangeOpPlugin extends CMDBChangeOp
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
            "db_table" => "priv_changeop_plugin",
            "db_key_field" => "id",
            "db_finalclass_field" => "",
        );
        MetaModel::Init_Params($aParams);
        MetaModel::Init_AddAttribute(new AttributeString("description", array("allowed_values" => null, "sql" => "description", "default_value" => '', "is_null_allowed" => false, "depends_on" => array())));
        /* May be used later when implementing an extension mechanism that will allow the plug-ins to store some extra information and still degrades gracefully when the plug-in is desinstalled
        MetaModel::Init_AddAttribute(new AttributeString("extension_class", array("allowed_values"=>null, "sql"=>"extension_class", "default_value"=>'', "is_null_allowed"=>false, "depends_on"=>array())));
        MetaModel::Init_AddAttribute(new AttributeInteger("extension_id", array("allowed_values"=>null, "sql"=>"extension_id", "default_value"=>0, "is_null_allowed"=>false, "depends_on"=>array())));
        */
        MetaModel::Init_InheritAttributes();
    }

    /**
     * @inheritDoc
     */
    public function GetDescription()
    {
        return $this->Get('description');
    }
}
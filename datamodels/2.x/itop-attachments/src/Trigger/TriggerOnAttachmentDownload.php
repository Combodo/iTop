<?php

/**
 * Class TriggerOnAttachmentDownload
 *
 * @since 3.1.0
 */
class TriggerOnAttachmentDownload extends TriggerOnAttributeBlobDownload
{
    /**
     * @inheritDoc
     * @throws \CoreException
     * @throws \Exception
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
            "db_table" => "priv_trigger_onattdownload",
            "db_key_field" => "id",
            "db_finalclass_field" => "",
            "display_template" => "",
        );
        MetaModel::Init_Params($aParams);
        MetaModel::Init_InheritAttributes();
    }
}
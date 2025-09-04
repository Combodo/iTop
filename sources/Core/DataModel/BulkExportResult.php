<?php

/**
 * Class BulkExport
 *
 * @copyright   Copyright (C) 2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
class BulkExportResult extends DBObject
{
    public static function Init()
    {
        $aParams = array
        (
            "category" => 'core/cmdb',
            "key_type" => 'autoincrement',
            "name_attcode" => array('created'),
            "state_attcode" => '',
            "reconc_keys" => array(),
            "db_table" => 'priv_bulk_export_result',
            "db_key_field" => 'id',
            "db_finalclass_field" => '',
            "display_template" => '',
        );
        MetaModel::Init_Params($aParams);

        MetaModel::Init_AddAttribute(new AttributeDateTime("created", array("allowed_values" => null, "sql" => "created", "default_value" => "NOW()", "is_null_allowed" => false, "depends_on" => array())));
        MetaModel::Init_AddAttribute(new AttributeInteger("user_id", array("allowed_values" => null, "sql" => "user_id", "default_value" => 0, "is_null_allowed" => false, "depends_on" => array())));
        MetaModel::Init_AddAttribute(new AttributeInteger("chunk_size", array("allowed_values" => null, "sql" => "chunk_size", "default_value" => 0, "is_null_allowed" => true, "depends_on" => array())));
        MetaModel::Init_AddAttribute(new AttributeString("format", array("allowed_values" => null, "sql" => "format", "default_value" => '', "is_null_allowed" => false, "depends_on" => array())));
        MetaModel::Init_AddAttribute(new AttributeString("temp_file_path", array("allowed_values" => null, "sql" => "temp_file_path", "default_value" => '', "is_null_allowed" => true, "depends_on" => array())));
        MetaModel::Init_AddAttribute(new AttributeLongText("search", array("allowed_values" => null, "sql" => "search", "default_value" => '', "is_null_allowed" => false, "depends_on" => array())));
        MetaModel::Init_AddAttribute(new AttributeLongText("status_info", array("allowed_values" => null, "sql" => "status_info", "default_value" => '', "is_null_allowed" => false, "depends_on" => array())));
        MetaModel::Init_AddAttribute(new AttributeBoolean("localize_output", array("allowed_values" => null, "sql" => "localize_output", "default_value" => true, "is_null_allowed" => true, "depends_on" => array())));

    }

    /**
     * @throws CoreUnexpectedValue
     * @throws Exception
     */
    public function ComputeValues()
    {
        $this->Set('user_id', UserRights::GetUserId());
    }
}
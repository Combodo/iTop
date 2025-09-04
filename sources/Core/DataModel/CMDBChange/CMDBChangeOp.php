<?php

/**
 * Various atomic change operations, to be tracked
 *
 */
class CMDBChangeOp extends DBObject implements iCMDBChangeOp
{
    public static function Init()
    {
        $aParams = array
        (
            "category" => "core/cmdb, grant_by_profile",
            "key_type" => "autoincrement",
            "name_attcode" => "change",
            "state_attcode" => "",
            "reconc_keys" => array(),
            "db_table" => "priv_changeop",
            "db_key_field" => "id",
            "db_finalclass_field" => "optype",
            'indexes' => array(
                array('objclass', 'objkey'),
            ),
        );
        MetaModel::Init_Params($aParams);
        //MetaModel::Init_InheritAttributes();
        MetaModel::Init_AddAttribute(new AttributeExternalKey("change", array("allowed_values" => null, "sql" => "changeid", "targetclass" => "CMDBChange", "is_null_allowed" => false, "on_target_delete" => DEL_MANUAL, "depends_on" => array())));
        MetaModel::Init_AddAttribute(new AttributeExternalField("date", array("allowed_values" => null, "extkey_attcode" => "change", "target_attcode" => "date")));
        MetaModel::Init_AddAttribute(new AttributeExternalField("userinfo", array("allowed_values" => null, "extkey_attcode" => "change", "target_attcode" => "userinfo")));
        MetaModel::Init_AddAttribute(new AttributeExternalField("user_id", array("allowed_values" => null, "extkey_attcode" => "change", "target_attcode" => "user_id")));
        MetaModel::Init_AddAttribute(new AttributeString("objclass", array("allowed_values" => null, "sql" => "objclass", "default_value" => "", "is_null_allowed" => false, "depends_on" => array())));
        MetaModel::Init_AddAttribute(new AttributeObjectKey("objkey", array("allowed_values" => null, "class_attcode" => "objclass", "sql" => "objkey", "is_null_allowed" => false, "depends_on" => array())));

        MetaModel::Init_SetZListItems('details', array('change', 'date', 'userinfo')); // Attributes to be displayed for the complete details
        MetaModel::Init_SetZListItems('list', array('change', 'date', 'userinfo')); // Attributes to be displayed for the complete details
    }

    /**
     * @inheritDoc
     */
    public function GetDescription()
    {
        return '';
    }

    /**
     * Safety net:
     * * if change isn't persisted yet, use the current change and persist it if needed
     * * in case the change is not given, let's guarantee that it will be set to the current ongoing change (or create a new one)
     *
     * @since 2.7.7 3.0.2 3.1.0 N°3717 do persist the current change if needed
     */
    protected function OnInsert()
    {
        $iChange = $this->Get('change');
        if (($iChange <= 0) || (is_null($iChange))) {
            $oChange = CMDBObject::GetCurrentChange();
            if ($oChange->IsNew()) {
                $oChange->DBWrite();
            }
            $this->Set('change', $oChange);
        }

        parent::OnInsert();
    }
}
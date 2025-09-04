<?php

/**
 * Record the modification of a scalar attribute
 *
 * @package     iTopORM
 */
class CMDBChangeOpSetAttributeScalar extends CMDBChangeOpSetAttribute
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
            "db_table" => "priv_changeop_setatt_scalar",
            "db_key_field" => "id",
            "db_finalclass_field" => "",
        );
        MetaModel::Init_Params($aParams);
        MetaModel::Init_InheritAttributes();
        MetaModel::Init_AddAttribute(new AttributeString("oldvalue", array("allowed_values" => null, "sql" => "oldvalue", "default_value" => null, "is_null_allowed" => true, "depends_on" => array())));
        MetaModel::Init_AddAttribute(new AttributeString("newvalue", array("allowed_values" => null, "sql" => "newvalue", "default_value" => null, "is_null_allowed" => true, "depends_on" => array())));

        // Display lists
        MetaModel::Init_SetZListItems('details', array('date', 'userinfo', 'attcode', 'oldvalue', 'newvalue')); // Attributes to be displayed for the complete details
        MetaModel::Init_SetZListItems('list', array('date', 'userinfo', 'attcode', 'oldvalue', 'newvalue')); // Attributes to be displayed for a list
    }

    /**
     * @inheritDoc
     */
    public function GetDescription()
    {
        $sResult = '';
        $oTargetObjectClass = $this->Get('objclass');
        $oTargetObjectKey = $this->Get('objkey');
        $oTargetSearch = new DBObjectSearch($oTargetObjectClass);
        $oTargetSearch->AddCondition('id', $oTargetObjectKey, '=');

        $oMonoObjectSet = new DBObjectSet($oTargetSearch);
        if (UserRights::IsActionAllowedOnAttribute($this->Get('objclass'), $this->Get('attcode'), UR_ACTION_READ, $oMonoObjectSet) == UR_ALLOWED_YES) {
            if (!MetaModel::IsValidAttCode($this->Get('objclass'), $this->Get('attcode'))) return ''; // Protects against renamed attributes...

            $oAttDef = MetaModel::GetAttributeDef($this->Get('objclass'), $this->Get('attcode'));
            $sAttName = $oAttDef->GetLabel();
            $sNewValue = $this->Get('newvalue');
            $sOldValue = $this->Get('oldvalue');
            $sResult = $oAttDef->DescribeChangeAsHTML($sOldValue, $sNewValue);
        }
        return $sResult;
    }
}
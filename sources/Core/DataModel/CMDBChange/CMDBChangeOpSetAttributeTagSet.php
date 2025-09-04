<?php

/**
 * Record the modification of a tag set attribute
 *
 * @package     iTopORM
 */
class CMDBChangeOpSetAttributeTagSet extends CMDBChangeOpSetAttribute
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
            "db_table" => "priv_changeop_setatt_tagset",
            "db_key_field" => "id",
            "db_finalclass_field" => "",
        );
        MetaModel::Init_Params($aParams);
        MetaModel::Init_InheritAttributes();
        MetaModel::Init_AddAttribute(new AttributeText("oldvalue", array("allowed_values" => null, "sql" => "oldvalue", "default_value" => null, "is_null_allowed" => true, "depends_on" => array())));
        MetaModel::Init_AddAttribute(new AttributeText("newvalue", array("allowed_values" => null, "sql" => "newvalue", "default_value" => null, "is_null_allowed" => true, "depends_on" => array())));

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
        $sTargetObjectClass = $this->Get('objclass');
        $oTargetObjectKey = $this->Get('objkey');
        $sAttCode = $this->Get('attcode');
        $oTargetSearch = new DBObjectSearch($sTargetObjectClass);
        $oTargetSearch->AddCondition('id', $oTargetObjectKey, '=');

        $oMonoObjectSet = new DBObjectSet($oTargetSearch);
        if (UserRights::IsActionAllowedOnAttribute($sTargetObjectClass, $sAttCode, UR_ACTION_READ, $oMonoObjectSet) == UR_ALLOWED_YES) {
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
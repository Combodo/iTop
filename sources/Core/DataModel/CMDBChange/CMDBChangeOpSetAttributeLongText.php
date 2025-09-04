<?php

/**
 * Record the modification of a multiline string (text)
 *
 * @package     iTopORM
 */
class CMDBChangeOpSetAttributeLongText extends CMDBChangeOpSetAttribute
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
            "db_table" => "priv_changeop_setatt_longtext",
            "db_key_field" => "id",
            "db_finalclass_field" => "",
        );
        MetaModel::Init_Params($aParams);
        MetaModel::Init_InheritAttributes();
        MetaModel::Init_AddAttribute(new AttributeLongText("prevdata", array("allowed_values" => null, "sql" => "prevdata", "default_value" => "", "is_null_allowed" => true, "depends_on" => array())));

        // Display lists
        MetaModel::Init_SetZListItems('details', array('date', 'userinfo', 'attcode')); // Attributes to be displayed for the complete details
        MetaModel::Init_SetZListItems('list', array('date', 'userinfo', 'attcode')); // Attributes to be displayed for a list
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
            if (MetaModel::IsValidAttCode($this->Get('objclass'), $this->Get('attcode'))) {
                $oAttDef = MetaModel::GetAttributeDef($this->Get('objclass'), $this->Get('attcode'));
                $sAttName = $oAttDef->GetLabel();
            } else {
                // The attribute was renamed or removed from the object ?
                $sAttName = $this->Get('attcode');
            }
            $sTextView = '<div>' . $this->GetAsHtml('prevdata') . '</div>';

            //$sDocView = $oPrevDoc->GetDisplayInline(get_class($this), $this->GetKey(), 'prevdata');
            $sResult = Dict::Format('Change:AttName_Changed_PreviousValue_OldValue', $sAttName, $sTextView);
        }
        return $sResult;
    }
}
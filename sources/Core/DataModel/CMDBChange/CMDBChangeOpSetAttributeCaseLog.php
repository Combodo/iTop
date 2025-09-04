<?php

/**
 * Record the modification of a caselog (text)
 * since the caselog itself stores the history
 * of its entries, there is no need to duplicate
 * the text here
 *
 * @package     iTopORM
 */
class CMDBChangeOpSetAttributeCaseLog extends CMDBChangeOpSetAttribute
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
            "db_table" => "priv_changeop_setatt_log",
            "db_key_field" => "id",
            "db_finalclass_field" => "",
        );
        MetaModel::Init_Params($aParams);
        MetaModel::Init_InheritAttributes();
        MetaModel::Init_AddAttribute(new AttributeInteger("lastentry", array("allowed_values" => null, "sql" => "lastentry", "default_value" => 0, "is_null_allowed" => true, "depends_on" => array())));

        // Display lists
        MetaModel::Init_SetZListItems('details', array('date', 'userinfo', 'attcode')); // Attributes to be displayed for the complete details
        MetaModel::Init_SetZListItems('list', array('date', 'userinfo', 'attcode')); // Attributes to be displayed for a list
    }

    /**
     * @inheritDoc
     */
    public function GetDescription()
    {
        // Temporary, until we change the options of GetDescription() -needs a more global revision
        $bIsHtml = true;

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
            $oObj = $oMonoObjectSet->Fetch();
            $oCaseLog = $oObj->Get($this->Get('attcode'));
            $sTextEntry = '<div class="history_entry history_entry_truncated"><div class="history_html_content">' . $oCaseLog->GetEntryAt($this->Get('lastentry')) . '</div></div>';

            $sResult = Dict::Format('Change:AttName_EntryAdded', $sAttName, $sTextEntry);
        }
        return $sResult;
    }

    /**
     * @param string $sRawText
     *
     * @return string
     */
    protected function ToHtml($sRawText)
    {
        return str_replace(array("\r\n", "\n", "\r"), "<br/>", utils::EscapeHtml($sRawText));
    }
}
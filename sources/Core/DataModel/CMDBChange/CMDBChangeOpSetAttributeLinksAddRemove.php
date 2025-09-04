<?php

/**
 * Record added/removed objects from within a link set
 *
 * @package     iTopORM
 */
class CMDBChangeOpSetAttributeLinksAddRemove extends CMDBChangeOpSetAttributeLinks
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
            "db_table" => "priv_changeop_links_addremove",
            "db_key_field" => "id",
            "db_finalclass_field" => "",
        );
        MetaModel::Init_Params($aParams);
        MetaModel::Init_InheritAttributes();

        MetaModel::Init_AddAttribute(new AttributeEnum("type", array("allowed_values" => new ValueSetEnum('added,removed'), "sql" => "type", "default_value" => "added", "is_null_allowed" => false, "depends_on" => array())));
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

            $sItemDesc = MetaModel::GetHyperLink($this->Get('item_class'), $this->Get('item_id'));

            $sResult = $sAttName . ' - ';
            switch ($this->Get('type')) {
                case 'added':
                    $sResult .= Dict::Format('Change:LinkSet:Added', $sItemDesc);
                    break;

                case 'removed':
                    $sResult .= Dict::Format('Change:LinkSet:Removed', $sItemDesc);
                    break;
            }
        }
        return $sResult;
    }
}
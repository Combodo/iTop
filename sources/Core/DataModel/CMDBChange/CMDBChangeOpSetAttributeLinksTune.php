<?php

/**
 * Record attribute changes from within a link set
 * A single record redirects to the modifications made within the same change
 *
 * @package     iTopORM
 */
class CMDBChangeOpSetAttributeLinksTune extends CMDBChangeOpSetAttributeLinks
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
            "db_table" => "priv_changeop_links_tune",
            "db_key_field" => "id",
            "db_finalclass_field" => "",
        );
        MetaModel::Init_Params($aParams);
        MetaModel::Init_InheritAttributes();

        MetaModel::Init_AddAttribute(new AttributeInteger("link_id", array("allowed_values" => null, "sql" => "link_id", "default_value" => 0, "is_null_allowed" => false, "depends_on" => array())));
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

            $sLinkClass = $oAttDef->GetLinkedClass();
            $aLinkClasses = MetaModel::EnumChildClasses($sLinkClass, ENUM_CHILD_CLASSES_ALL);

            // Search for changes on the corresponding link
            //
            $oSearch = new DBObjectSearch('CMDBChangeOpSetAttribute');
            $oSearch->AddCondition('change', $this->Get('change'), '=');
            $oSearch->AddCondition('objkey', $this->Get('link_id'), '=');
            if (count($aLinkClasses) == 1) {
                // Faster than the whole building of the expression below for just one value ??
                $oSearch->AddCondition('objclass', $sLinkClass, '=');
            } else {
                $oField = new FieldExpression('objclass', $oSearch->GetClassAlias());
                $sListExpr = '(' . implode(', ', CMDBSource::Quote($aLinkClasses)) . ')';
                $sOQLCondition = $oField->RenderExpression() . " IN $sListExpr";
                $oNewCondition = Expression::FromOQL($sOQLCondition);
                $oSearch->AddConditionExpression($oNewCondition);
            }
            $oSet = new DBObjectSet($oSearch);
            $aChanges = array();
            while ($oChangeOp = $oSet->Fetch()) {
                $aChanges[] = $oChangeOp->GetDescription();
            }
            if (count($aChanges) == 0) {
                return '';
            }

            $sItemDesc = MetaModel::GetHyperLink($this->Get('item_class'), $this->Get('item_id'));

            $sResult = $sAttName . ' - ';
            $sResult .= Dict::Format('Change:LinkSet:Modified', $sItemDesc);
            $sResult .= ' : ' . implode(', ', $aChanges);
        }
        return $sResult;
    }
}
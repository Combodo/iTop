<?php

/**
 * Class TriggerOnObjectCreate
 */
class TriggerOnObjectUpdate extends TriggerOnObject
{
    /**
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
            "db_table" => "priv_trigger_onobjupdate",
            "db_key_field" => "id",
            "db_finalclass_field" => "",
        );
        MetaModel::Init_Params($aParams);
        MetaModel::Init_InheritAttributes();
        MetaModel::Init_AddAttribute(new AttributeClassAttCodeSet('target_attcodes', array("allowed_values" => null, "class_field" => "target_class", "sql" => "target_attcodes", "default_value" => null, "is_null_allowed" => true, "max_items" => 20, "min_items" => 0, "attribute_definition_exclusion_list" => "AttributeDashboard,AttributeExternalField,AttributeFinalClass,AttributeFriendlyName,AttributeObsolescenceDate,AttributeObsolescenceFlag,AttributeSubItem", "attribute_definition_list" => null, "depends_on" => array('target_class'))));

        // Display lists
        MetaModel::Init_SetZListItems('details', array('description', 'context', 'target_class', 'filter', 'target_attcodes', 'subscription_policy', 'action_list')); // Attributes to be displayed for the complete details
        MetaModel::Init_SetZListItems('list', array('finalclass', 'target_class')); // Attributes to be displayed for a list
        // Search criteria
        MetaModel::Init_SetZListItems('standard_search', array('description', 'target_class')); // Criteria of the std search form
    }

    public function IsTargetObject($iObjectId, $aChanges = array())
    {
        if (!parent::IsTargetObject($iObjectId, $aChanges)) {
            return false;
        }

        // Check the attribute
        $oAttCodeSet = $this->Get('target_attcodes');
        $aAttCodes = $oAttCodeSet->GetValues();
        if (empty($aAttCodes)) {
            return true;
        }

        foreach ($aAttCodes as $sAttCode) {
            if (array_key_exists($sAttCode, $aChanges)) {
                return true;
            }
        }
        return false;
    }

    public function ComputeValues()
    {
        parent::ComputeValues();

        // Remove unwanted attribute codes
        $aChanges = $this->ListChanges();
        if (isset($aChanges['target_attcodes'])) {
            $oAttDef = MetaModel::GetAttributeDef(get_class($this), 'target_attcodes');
            $aArgs = array('this' => $this);
            $aAllowedValues = $oAttDef->GetAllowedValues($aArgs);

            /** @var \ormSet $oValue */
            $oValue = $this->Get('target_attcodes');
            $aValues = $oValue->GetValues();
            $bChanged = false;
            foreach ($aValues as $key => $sValue) {
                if (!isset($aAllowedValues[$sValue])) {
                    unset($aValues[$key]);
                    $bChanged = true;
                }
            }
            if ($bChanged) {
                $oValue->SetValues($aValues);
                $this->Set('target_attcodes', $oValue);
            }
        }
    }

}
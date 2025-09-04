<?php

/**
 * Class TriggerOnObjectMention
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @since 3.0.0
 */
class TriggerOnObjectMention extends TriggerOnObject
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
            "db_table" => "priv_trigger_onobjmention",
            "db_key_field" => "id",
            "db_finalclass_field" => "",
            "display_template" => "",
        );
        MetaModel::Init_Params($aParams);
        MetaModel::Init_InheritAttributes();
        MetaModel::Init_AddAttribute(new AttributeOQL("mentioned_filter", array("allowed_values" => null, "sql" => "mentioned_filter", "default_value" => null, "is_null_allowed" => true, "depends_on" => array())));

        // Display lists
        MetaModel::Init_SetZListItems('details', array('description', 'context', 'target_class', 'filter', 'mentioned_filter', 'subscription_policy', 'action_list')); // Attributes to be displayed for the complete details
        MetaModel::Init_SetZListItems('list', array('finalclass', 'target_class')); // Attributes to be displayed for a list
        // Search criteria
        MetaModel::Init_SetZListItems('standard_search', array('description', 'target_class')); // Criteria of the std search form
    }

    /**
     * @param \DBObject $oObject
     *
     * @return bool True if $oObject is within the scope of the OQL defined by the "mentioned_filter" attribute OR if no mentioned_filter defined. Otherwise, returns false.
     *
     * @throws \ArchivedObjectException
     * @throws \CoreException
     * @throws \MissingQueryArgument
     * @throws \MySQLException
     * @throws \MySQLHasGoneAwayException
     * @throws \OQLException
     */
    public function IsMentionedObjectInScope(DBObject $oObject)
    {
        $sFilter = trim($this->Get('mentioned_filter'));
        if (strlen($sFilter) > 0) {
            $oSearch = DBObjectSearch::FromOQL($sFilter);
            $sSearchClass = $oSearch->GetClass();

            // If filter not on current object class (or descendants), consider it as not in scope
            if (is_a($oObject, $sSearchClass, true) === false) {
                return false;
            }

            $oSearch->AddCondition('id', $oObject->GetKey(), '=');
            if (MetaModel::IsAbstract($oSearch->GetClass())) {
                $oSearch->AddCondition('finalclass', get_class($oObject), '=');
            }

            $aParams = $oObject->ToArgs('this');
            $oSet = new DBObjectSet($oSearch, [], $aParams);
            $bRet = $oSet->CountExceeds(0);
        } else {
            $bRet = true;
        }

        return $bRet;
    }
}
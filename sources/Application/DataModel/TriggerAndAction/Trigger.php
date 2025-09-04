<?php

/**
 * A user defined trigger, to customize the application
 * A trigger will activate an action
 *
 * @package     iTopORM
 */
abstract class Trigger extends cmdbAbstractObject
{
    /**
     * @throws \CoreException
     * @throws \Exception
     */
    public static function Init()
    {
        $aParams = array
        (
            "category" => "grant_by_profile,core/cmdb",
            "key_type" => "autoincrement",
            "name_attcode" => "description",
            "complementary_name_attcode" => ['finalclass', 'complement'],
            "state_attcode" => "",
            "reconc_keys" => ['description'],
            "db_table" => "priv_trigger",
            "db_key_field" => "id",
            "db_finalclass_field" => "realclass",
            'style' => new ormStyle(null, null, null, null, null, '../images/icons/icons8-conflict.svg'),
        );
        MetaModel::Init_Params($aParams);
        //MetaModel::Init_InheritAttributes();
        MetaModel::Init_AddAttribute(new AttributeString("description", array("allowed_values" => null, "sql" => "description", "default_value" => null, "is_null_allowed" => false, "depends_on" => array())));
        MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("action_list",
            array("linked_class" => "lnkTriggerAction", "ext_key_to_me" => "trigger_id", "ext_key_to_remote" => "action_id", "allowed_values" => null, "count_min" => 1, "count_max" => 0, "depends_on" => array())));
        $aTags = ContextTag::GetTags();
        MetaModel::Init_AddAttribute(new AttributeEnumSet("context", array("allowed_values" => null, "possible_values" => new ValueSetEnumPadded($aTags, true), "sql" => "context", "depends_on" => array(), "is_null_allowed" => true, "max_items" => 12)));
        // "complement" is a computed field, fed by Trigger sub-classes, in general in ComputeValues method, for eg. the TriggerOnObject fed it with target_class info
        MetaModel::Init_AddAttribute(new AttributeString("complement", array("allowed_values" => null, "sql" => "complement", "default_value" => null, "is_null_allowed" => true, "depends_on" => array())));
        MetaModel::Init_AddAttribute(new AttributeEnum("subscription_policy", array("allowed_values" => new ValueSetEnum(Combodo\iTop\Core\Trigger\Enum\SubscriptionPolicy::cases()), "sql" => "subscription_policy", "default_value" => \Combodo\iTop\Core\Trigger\Enum\SubscriptionPolicy::AllowNoChannel->value, "is_null_allowed" => false, "depends_on" => array())));

        // Display lists
        MetaModel::Init_SetZListItems('details', array('finalclass', 'description', 'context', 'subscription_policy', 'action_list', 'complement')); // Attributes to be displayed for the complete details
        MetaModel::Init_SetZListItems('list', array('finalclass', 'complement')); // Attributes to be displayed for a list
        // Search criteria
        //		MetaModel::Init_SetZListItems('standard_search', array('name')); // Criteria of the std search form
        //		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
    }

    /**
     * Check if the trigger can be used in the current context
     *
     * @return bool true if context OK
     * @throws \ArchivedObjectException
     * @throws \CoreException
     */
    public function IsContextValid()
    {
        // Check the context
        $oContext = $this->Get('context');
        $bChecked = false;
        $bValid = false;
        foreach ($oContext->GetValues() as $sValue) {
            $bChecked = true;
            if (ContextTag::Check($sValue)) {
                $bValid = true;
                break;
            }
        }
        if ($bChecked && !$bValid) {
            // Trigger does not match the current context
            return false;
        }

        return true;
    }

    /**
     * @param $aContextArgs
     *
     * @throws \ArchivedObjectException
     * @throws \CoreException
     */
    public function DoActivate($aContextArgs)
    {
        // Check the context
        if (!$this->IsContextValid()) {
            // Trigger does not match the current context
            $sClass = get_class($this);
            $sName = $this->Get('friendlyname');
            IssueLog::Debug("Context NOT valid for : {$sClass} '$sName'");
            return;
        }

        $aContextArgs['trigger->object()'] = $this;

        // Find the related actions
        $oLinkedActions = $this->Get('action_list');

        // Order actions as expected
        $aActionListOrdered = [];
        while ($oLink = $oLinkedActions->Fetch()) {
            $aActionListOrdered[(int)$oLink->Get('order')][] = $oLink;
        }
        ksort($aActionListOrdered);

        // Execute actions
        foreach ($aActionListOrdered as $aActionSubList) {
            foreach ($aActionSubList as $oLink) /** @var \DBObject $oLink */ {
                /** @var \DBObject $oLink */
                $iActionId = $oLink->Get('action_id');
                /** @var \Action $oAction */
                $oAction = MetaModel::GetObject('Action', $iActionId);
                if ($oAction->IsActive()) {
                    $oKPI = new ExecutionKPI();
                    $aContextArgs['action->object()'] = $oAction;
                    $oAction->DoExecute($this, $aContextArgs);
                    $oKPI->ComputeStatsForExtension($oAction, 'DoExecute');
                }
            }
        }
    }

    /**
     * Check whether the given object is in the scope of this trigger
     * and can potentially be the subject of notifications
     *
     * @param DBObject $oObject The object to check
     *
     * @return bool
     */
    public function IsInScope(DBObject $oObject)
    {
        // By default the answer is no
        // Overload this function in your own derived class for a different behavior
        return false;
    }
}
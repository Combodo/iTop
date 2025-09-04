<?php

/**
 * A user defined action, to customize the application
 *
 * @package     iTopORM
 */
abstract class Action extends cmdbAbstractObject
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
            "name_attcode" => "name",
            "complementary_name_attcode" => ['finalclass', 'description'],
            "state_attcode" => "status",
            "reconc_keys" => ['name'],
            "db_table" => "priv_action",
            "db_key_field" => "id",
            "db_finalclass_field" => "realclass",
            "style" => new ormStyle("ibo-dm-class--Action", "ibo-dm-class-alt--Action", "var(--ibo-dm-class--Action--main-color)", "var(--ibo-dm-class--Action--complementary-color)", null, '../images/icons/icons8-in-transit.svg'),
        );
        MetaModel::Init_Params($aParams);
        //MetaModel::Init_InheritAttributes();
        MetaModel::Init_AddAttribute(new AttributeString("name", array("allowed_values" => null, "sql" => "name", "default_value" => null, "is_null_allowed" => false, "depends_on" => array())));
        MetaModel::Init_AddAttribute(new AttributeString("description", array("allowed_values" => null, "sql" => "description", "default_value" => null, "is_null_allowed" => true, "depends_on" => array())));

        MetaModel::Init_AddAttribute(new AttributeEnum("status", array(
            "allowed_values" => new ValueSetEnum(array('test' => 'Being tested', 'enabled' => 'In production', 'disabled' => 'Inactive')),
            "styled_values" => [
                'test' => new ormStyle('ibo-dm-enum--Action-status-test', 'ibo-dm-enum-alt--Action-status-test', 'var(--ibo-dm-enum--Action-status-test--main-color)', 'var(--ibo-dm-enum--Action-status-test--complementary-color)', null, null),
                'enabled' => new ormStyle('ibo-dm-enum--Action-status-enabled', 'ibo-dm-enum-alt--Action-status-enabled', 'var(--ibo-dm-enum--Action-status-enabled--main-color)', 'var(--ibo-dm-enum--Action-status-enabled--complementary-color)', 'fas fa-check', null),
                'disabled' => new ormStyle('ibo-dm-enum--Action-status-disabled', 'ibo-dm-enum-alt--Action-status-disabled', 'var(--ibo-dm-enum--Action-status-disabled--main-color)', 'var(--ibo-dm-enum--Action-status-disabled--complementary-color)', null, null),
            ],
            "display_style" => 'list',
            "sql" => "status",
            "default_value" => "test",
            "is_null_allowed" => false,
            "depends_on" => array(),
        )));

        MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("trigger_list",
            array("linked_class" => "lnkTriggerAction", "ext_key_to_me" => "action_id", "ext_key_to_remote" => "trigger_id", "allowed_values" => null, "count_min" => 0, "count_max" => 0, "depends_on" => array(), "display_style" => 'property')));
        MetaModel::Init_AddAttribute(new AttributeEnum("asynchronous", array("allowed_values" => new ValueSetEnum(['use_global_setting' => 'Use global settings', 'yes' => 'Yes', 'no' => 'No']), "sql" => "asynchronous", "default_value" => 'use_global_setting', "is_null_allowed" => false, "depends_on" => array())));

        // Display lists
        // - Attributes to be displayed for the complete details
        MetaModel::Init_SetZListItems('details', array('name', 'description', 'status', 'trigger_list'));
        // - Attributes to be displayed for a list
        MetaModel::Init_SetZListItems('list', array('finalclass', 'name', 'description', 'status'));
        // Search criteria
        // - Default criteria of the search form
        MetaModel::Init_SetZListItems('default_search', array('name', 'description', 'status'));

    }

    /**
     * Encapsulate the execution of the action and handle failure & logging
     *
     * @param \Trigger $oTrigger
     * @param array $aContextArgs
     *
     * @return mixed
     */
    abstract public function DoExecute($oTrigger, $aContextArgs);

    /**
     * @return bool
     * @throws \ArchivedObjectException
     * @throws \CoreException
     */
    public function IsActive()
    {
        switch ($this->Get('status')) {
            case 'enabled':
            case 'test':
                return true;

            default:
                return false;
        }
    }

    /**
     * Return true if the current action status is set on "test"
     *
     * @return bool
     * @throws \ArchivedObjectException
     * @throws \CoreException
     */
    public function IsBeingTested()
    {
        switch ($this->Get('status')) {
            case 'test':
                return true;

            default:
                return false;
        }
    }

    /**
     * @inheritDoc
     * @since 3.0.0
     */
    public function AfterInsert()
    {
        parent::AfterInsert();
        $this->DoCheckIfHasTrigger();
    }

    /**
     * @inheritDoc
     * @since 3.0.0
     */
    public function AfterUpdate()
    {
        parent::AfterUpdate();
        $this->DoCheckIfHasTrigger();
    }

    /**
     * Check if the Action has at least 1 trigger linked. Otherwise, it adds a warning.
     * @return void
     * @since 3.0.0
     */
    protected function DoCheckIfHasTrigger()
    {
        $oTriggersSet = $this->Get('trigger_list');
        if ($oTriggersSet->Count() === 0) {
            $this->m_aCheckWarnings[] = Dict::S('Action:WarningNoTriggerLinked');
        }
    }

    /**
     * @since 3.2.0 N°5472 method creation
     */
    public function DisplayBareRelations(\Combodo\iTop\Application\WebPage\WebPage $oPage, $bEditMode = false)
    {
        parent::DisplayBareRelations($oPage, false);

        if ($oPage instanceof iTopWebPage && !$this->IsNew()) {
            $this->GenerateLastExecutionsTab($oPage, $bEditMode);
        }
    }

    /**
     * @since 3.2.0 N°5472 method creation
     */
    protected function GenerateLastExecutionsTab(iTopWebPage $oPage, $bEditMode)
    {
        $oRouter = \Combodo\iTop\Service\Router\Router::GetInstance();
        $sActionLastExecutionsPageUrl = $oRouter->GenerateUrl('notifications.action.last_executions_tab', ['action_id' => $this->GetKey()]);
        $oPage->AddAjaxTab('action_errors', $sActionLastExecutionsPageUrl, false, Dict::S('Action:last_executions_tab'));
    }

    /**
     * @param \Combodo\iTop\Application\WebPage\WebPage $oPage
     *
     * @throws \ApplicationException
     * @throws \ArchivedObjectException
     * @throws \ConfigException
     * @throws \CoreException
     * @throws \CoreUnexpectedValue
     * @throws \DictExceptionMissingString
     * @throws \InvalidConfigParamException
     * @throws \MissingQueryArgument
     * @throws \MySQLException
     * @throws \MySQLHasGoneAwayException
     * @throws \OQLException
     * @throws \ReflectionException
     * @since 3.2.0 N°5472 method creation
     */
    public function GetLastExecutionsTabContent(\Combodo\iTop\Application\WebPage\WebPage $oPage): void
    {
        $oConfig = utils::GetConfig();
        $sLastExecutionDaysConfigParamName = 'notifications.last_executions_days';
        $iLastExecutionDays = $oConfig->Get($sLastExecutionDaysConfigParamName);

        if ($iLastExecutionDays < 0) {
            throw new InvalidConfigParamException("Invalid value for {$sLastExecutionDaysConfigParamName} config parameter. Param desc: " . $oConfig->GetDescription($sLastExecutionDaysConfigParamName));
        }

        $sActionQueryOql = 'SELECT EventNotification WHERE action_id = :action_id';
        $aActionQueryParams = ['action_id' => $this->GetKey()];
        if ($iLastExecutionDays > 0) {
            $sActionQueryOql .= ' AND date > DATE_SUB(NOW(), INTERVAL :days DAY)';
            $aActionQueryParams['days'] = $iLastExecutionDays;
            $sActionQueryLimit = Dict::Format('Action:last_executions_tab_limit_days', $iLastExecutionDays);
        } else {
            $sActionQueryLimit = Dict::S('Action:last_executions_tab_limit_none');
        }

        $oActionFilter = DBObjectSearch::FromOQL($sActionQueryOql, $aActionQueryParams);
        $oSet = new DBObjectSet($oActionFilter, ['date' => false]);

        $sPanelTitle = Dict::Format('Action:last_executions_tab_panel_title', $sActionQueryLimit);
        $oExecutionsListBlock = \Combodo\iTop\Application\UI\Base\Component\DataTable\DataTableUIBlockFactory::MakeForResult($oPage, 'action_executions_list', $oSet, ['panel_title' => $sPanelTitle]);

        $oPage->AddUiBlock($oExecutionsListBlock);
    }

    /**
     * Will be overloaded by the children classes to return the value of their global asynchronous setting (eg. `email_asynchronous` for `\ActionEmail`, `prefer_asynchronous` for `\ActionWebhook`, ...)
     *
     * @return bool true if the global setting for this kind of action if to be executed asynchronously, false otherwise.
     * @since 3.2.0
     */
    public static function GetAsynchronousGlobalSetting(): bool
    {
        return false;
    }

    /**
     * @return bool true if that action instance should be executed asynchronously, otherwise false
     * @throws \ArchivedObjectException
     * @throws \CoreException
     * @since 3.2.0
     */
    public function IsAsynchronous(): bool
    {
        $sAsynchronous = $this->Get('asynchronous');
        if ($sAsynchronous === 'use_global_setting') {
            return static::GetAsynchronousGlobalSetting();
        }
        return $sAsynchronous === 'yes';
    }
}
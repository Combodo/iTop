<?php

/**
 * An async notification to be sent to iTop users through the newsroom
 * @since 3.2.0
 */
class AsyncSendNewsroom extends AsyncTask
{

    public static function Init()
    {
        $aParams = array
        (
            "category" => "core/cmdb",
            "key_type" => "autoincrement",
            "name_attcode" => "created",
            "state_attcode" => "",
            "reconc_keys" => array(),
            "db_table" => "priv_async_send_newsroom",
            "db_key_field" => "id",
            "db_finalclass_field" => "",
        );
        MetaModel::Init_Params($aParams);
        MetaModel::Init_InheritAttributes();

        MetaModel::Init_AddAttribute(new AttributeText("recipients", array("allowed_values" => null, "sql" => "recipients", "default_value" => null, "is_null_allowed" => false, "depends_on" => array())));
        MetaModel::Init_AddAttribute(new AttributeExternalKey("action_id", array("targetclass" => "Action", "allowed_values" => null, "sql" => "action_id", "default_value" => null, "is_null_allowed" => false, "on_target_delete" => DEL_AUTO, "depends_on" => array())));
        MetaModel::Init_AddAttribute(new AttributeExternalKey("trigger_id", array("targetclass" => "Trigger", "allowed_values" => null, "sql" => "trigger_id", "default_value" => null, "is_null_allowed" => false, "on_target_delete" => DEL_AUTO, "depends_on" => array())));
        MetaModel::Init_AddAttribute(new AttributeText("title", array("allowed_values" => null, "sql" => "title", "default_value" => null, "is_null_allowed" => false, "depends_on" => array())));
        MetaModel::Init_AddAttribute(new AttributeText("message", array("allowed_values" => null, "sql" => "message", "default_value" => null, "is_null_allowed" => false, "depends_on" => array())));
        MetaModel::Init_AddAttribute(new AttributeInteger("object_id", array("allowed_values" => null, "sql" => "object_id", "default_value" => null, "is_null_allowed" => false, "on_target_delete" => DEL_AUTO, "depends_on" => array())));
        MetaModel::Init_AddAttribute(new AttributeString("object_class", array("allowed_values" => null, "sql" => "object_class", "default_value" => null, "is_null_allowed" => false, "on_target_delete" => DEL_AUTO, "depends_on" => array())));
        MetaModel::Init_AddAttribute(new AttributeText("url", array("allowed_values" => null, "sql" => "url", "default_value" => null, "is_null_allowed" => false, "on_target_delete" => DEL_AUTO, "depends_on" => array())));
        MetaModel::Init_AddAttribute(new AttributeDateTime("date", array("allowed_values" => null, "sql" => "date", "default_value" => 'NOW()', "is_null_allowed" => false, "on_target_delete" => DEL_AUTO, "depends_on" => array())));

    }

    /**
     * @throws \ArchivedObjectException
     * @throws \CoreCannotSaveObjectException
     * @throws \CoreException
     * @throws \CoreUnexpectedValue
     * @throws \CoreWarning
     * @throws \MySQLException
     * @throws \OQLException
     */
    public static function AddToQueue(int $iActionId, int $iTriggerId, array $aRecipients, string $sMessage, string $sTitle, string $sUrl, int $iObjectId, ?string $sObjectClass): void
    {
        $oNew = new static();
        $oNew->Set('action_id', $iActionId);
        $oNew->Set('trigger_id', $iTriggerId);
        $oNew->Set('recipients', json_encode($aRecipients));
        $oNew->Set('message', $sMessage);
        $oNew->Set('title', $sTitle);
        $oNew->Set('url', $sUrl);
        $oNew->Set('object_id', $iObjectId);
        $oNew->Set('object_class', $sObjectClass);
        $oNew->SetCurrentDate('date');

        $oNew->DBInsert();
    }

    /**
     * @inheritDoc
     */
    public function DoProcess()
    {
        $oAction = MetaModel::GetObject('Action', $this->Get('action_id'));
        $iTriggerId = $this->Get('trigger_id');
        $aRecipients = json_decode($this->Get('recipients'));
        $sMessage = $this->Get('message');
        $sTitle = $this->Get('title');
        $sUrl = $this->Get('url');
        $iObjectId = $this->Get('object_id');
        $sObjectClass = $this->Get('object_class');
        $sDate = $this->Get('date');

        foreach ($aRecipients as $iRecipientId) {
            $oEvent = \Combodo\iTop\Service\Notification\Event\EventNotificationNewsroomService::MakeEventFromAction($oAction, $iRecipientId, $iTriggerId, $sMessage, $sTitle, $sUrl, $iObjectId, $sObjectClass, $sDate);
            $oEvent->DBInsertNoReload();
        }

        return "Sent";
    }
}
<?php

/**
 * An email notification
 *
 * @package     iTopORM
 */
class AsyncSendEmail extends AsyncTask
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
            "db_table" => "priv_async_send_email",
            "db_key_field" => "id",
            "db_finalclass_field" => "",
        );
        MetaModel::Init_Params($aParams);
        MetaModel::Init_InheritAttributes();

        MetaModel::Init_AddAttribute(new AttributeInteger("version", array("allowed_values" => null, "sql" => "version", "default_value" => Email::ORIGINAL_FORMAT, "is_null_allowed" => false, "depends_on" => array())));
        MetaModel::Init_AddAttribute(new AttributeText("to", array("allowed_values" => null, "sql" => "to", "default_value" => null, "is_null_allowed" => true, "depends_on" => array())));
        MetaModel::Init_AddAttribute(new AttributeText("subject", array("allowed_values" => null, "sql" => "subject", "default_value" => null, "is_null_allowed" => false, "depends_on" => array())));
        MetaModel::Init_AddAttribute(new AttributeLongText("message", array("allowed_values" => null, "sql" => "message", "default_value" => null, "is_null_allowed" => false, "depends_on" => array())));

        // Display lists
//		MetaModel::Init_SetZListItems('details', array('name', 'description', 'status', 'test_recipient', 'from', 'reply_to', 'to', 'cc', 'bcc', 'subject', 'body', 'importance', 'trigger_list')); // Attributes to be displayed for the complete details
//		MetaModel::Init_SetZListItems('list', array('name', 'status', 'to', 'subject')); // Attributes to be displayed for a list
        // Search criteria
//		MetaModel::Init_SetZListItems('standard_search', array('name')); // Criteria of the std search form
//		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
    }

    static public function AddToQueue(EMail $oEMail, $oLog)
    {
        $oNew = MetaModel::NewObject(__class__);
        if ($oLog) {
            $oNew->Set('event_id', $oLog->GetKey());
        }
        $oNew->Set('to', $oEMail->GetRecipientTO(true /* string */));
        $oNew->Set('subject', $oEMail->GetSubject());

        $oNew->Set('version', 2);
        $sMessage = $oEMail->SerializeV2();
        $oNew->Set('message', $sMessage);
        $oNew->DBInsert();
    }

    /**
     * @inheritDoc
     * @throws \ArchivedObjectException
     * @throws \CoreException
     */
    public function DoProcess()
    {
        $sMessage = $this->Get('message');
        $iVersion = (int)$this->Get('version');
        switch ($iVersion) {
            case Email::FORMAT_V2:
                $oEMail = Email::UnSerializeV2($sMessage);
                break;

            case Email::ORIGINAL_FORMAT:
                $oEMail = unserialize($sMessage);
                break;

            default:
                return 'Unknown version of the serialization format: ' . $iVersion;
        }
        $iRes = $oEMail->Send($aIssues, true /* force synchro !!!!! */);
        switch ($iRes) {
            case EMAIL_SEND_OK:
                return "Sent";

            case EMAIL_SEND_PENDING:
                return "Bug - the email should be sent in synchronous mode";

            case EMAIL_SEND_ERROR:
                if (is_array($aIssues)) {
                    $sMessage = "Sending eMail failed: " . implode(', ', $aIssues);
                } else {
                    $sMessage = "Sending eMail failed.";
                }
                throw new Exception($sMessage);
        }
        return '';
    }
}
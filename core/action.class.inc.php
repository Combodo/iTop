<?php
// Copyright (C) 2010-2021 Combodo SARL
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify	
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>


/**
 * Persistent classes (internal): user defined actions
 *
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


require_once(APPROOT.'/core/asynctask.class.inc.php');
require_once(APPROOT.'/core/email.class.inc.php');

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
			"state_attcode" => "",
			"reconc_keys" => array('name'),
			"db_table" => "priv_action",
			"db_key_field" => "id",
			"db_finalclass_field" => "realclass",
			'style' =>  new ormStyle(null, null, null, null, null, '../images/icons/icons8-in-transit.svg'),
		);
		MetaModel::Init_Params($aParams);
		//MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeString("name", array("allowed_values"=>null, "sql"=>"name", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("description", array("allowed_values"=>null, "sql"=>"description", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("status", array("allowed_values"=>new ValueSetEnum(array('test'=>'Being tested' ,'enabled'=>'In production', 'disabled'=>'Inactive')), "sql"=>"status", "default_value"=>"test", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("trigger_list", array("linked_class"=>"lnkTriggerAction", "ext_key_to_me"=>"action_id", "ext_key_to_remote"=>"trigger_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));

		// Display lists
		// - Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('details', array('name', 'description', 'status', 'trigger_list'));
		// - Attributes to be displayed for a list
		MetaModel::Init_SetZListItems('list', array('finalclass', 'name', 'description', 'status'));
		// Search criteria
		// - Criteria of the std search form
		MetaModel::Init_SetZListItems('default_search', array('name', 'description', 'status'));
		// - Criteria of the advanced search form
//		MetaModel::Init_SetZListItems('advanced_search', array('name'));
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
		switch($this->Get('status'))
		{
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
		switch($this->Get('status'))
		{
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
}

/**
 * A notification  
 *
 * @package     iTopORM
 */
abstract class ActionNotification extends Action
{
	/**
	 * @inheritDoc
	 * @throws \CoreException
	 */
	public static function Init()
	{
		$aParams = array
		(
			"category" => "grant_by_profile,core/cmdb",
			"key_type" => "autoincrement",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array('name'),
			"db_table" => "priv_action_notification",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		// Display lists
		// - Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('details', array('name', 'description', 'status', 'trigger_list'));
		// - Attributes to be displayed for a list
		MetaModel::Init_SetZListItems('list', array('finalclass', 'name', 'description', 'status'));
		// Search criteria
		// - Criteria of the std search form
//		MetaModel::Init_SetZListItems('standard_search', array('name'));
		// - Criteria of the advanced search form
//		MetaModel::Init_SetZListItems('advanced_search', array('name'));
	}
}

/**
 * An email notification  
 *
 * @package     iTopORM
 */
class ActionEmail extends ActionNotification
{
	/**
	 * @var string
	 * @since 3.0.1
	 */
	const ENUM_HEADER_NAME_MESSAGE_ID = 'Message-ID';
	/**
	 * @var string
	 * @since 3.0.1
	 */
	const ENUM_HEADER_NAME_REFERENCES = 'References';

	/**
	 * @inheritDoc
	 */
	public static function Init()
	{
		$aParams = array
		(
			"category"            => "grant_by_profile,core/cmdb,application",
			"key_type"            => "autoincrement",
			"name_attcode"        => "name",
			"state_attcode"       => "",
			"reconc_keys"         => array('name'),
			"db_table"            => "priv_action_email",
			"db_key_field"        => "id",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeEmailAddress("test_recipient", array("allowed_values"=>null, "sql"=>"test_recipient", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_AddAttribute(new AttributeString("from", array("allowed_values"=>null, "sql"=>"from", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("from_label", array("allowed_values"=>null, "sql"=>"from_label", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("reply_to", array("allowed_values"=>null, "sql"=>"reply_to", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("reply_to_label", array("allowed_values"=>null, "sql"=>"reply_to_label", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeOQL("to", array("allowed_values"=>null, "sql"=>"to", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeOQL("cc", array("allowed_values"=>null, "sql"=>"cc", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeOQL("bcc", array("allowed_values"=>null, "sql"=>"bcc", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeTemplateString("subject", array("allowed_values"=>null, "sql"=>"subject", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeTemplateHTML("body", array("allowed_values"=>null, "sql"=>"body", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("importance", array("allowed_values"=>new ValueSetEnum('low,normal,high'), "sql"=>"importance", "default_value"=>'normal', "is_null_allowed"=>false, "depends_on"=>array())));

		// Display lists
		// - Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('details', array('name', 'description', 'status', 'test_recipient', 'from', 'from_label', 'reply_to', 'reply_to_label', 'to', 'cc', 'bcc', 'subject', 'body', 'importance', 'trigger_list'));
		// - Attributes to be displayed for a list
		MetaModel::Init_SetZListItems('list', array('name', 'status', 'to', 'subject'));
		// Search criteria
		// - Criteria of the std search form
		MetaModel::Init_SetZListItems('standard_search', array('name','description', 'status', 'subject'));
		// - Criteria of the advanced search form
//		MetaModel::Init_SetZListItems('advanced_search', array('name'));
	}

	// count the recipients found
	protected $m_iRecipients;

	// Errors management : not that simple because we need that function to be
	// executed in the background, while making sure that any issue would be reported clearly
	protected $m_aMailErrors; //array of strings explaining the issue

	/**
	 * Return a the list of emails as a string, or a detailed error description
	 *
	 * @param string $sRecipAttCode
	 * @param array $aArgs
	 *
	 * @return string
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 */
	protected function FindRecipients($sRecipAttCode, $aArgs)
	{
		$sOQL = $this->Get($sRecipAttCode);
		if (strlen($sOQL) == '') return '';

		try
		{
			$oSearch = DBObjectSearch::FromOQL($sOQL);
			$oSearch->AllowAllData();
		}
		catch (OQLException $e)
		{
			$this->m_aMailErrors[] = "query syntax error for recipient '$sRecipAttCode'";
			return $e->getMessage();
		}

		$sClass = $oSearch->GetClass();
		// Determine the email attribute (the first one will be our choice)
		foreach (MetaModel::ListAttributeDefs($sClass) as $sAttCode => $oAttDef)
		{
			if ($oAttDef instanceof AttributeEmailAddress)
			{
				$sEmailAttCode = $sAttCode;
				// we've got one, exit the loop
				break;
			}
		}
		if (!isset($sEmailAttCode))
		{
			$this->m_aMailErrors[] = "wrong target for recipient '$sRecipAttCode'";
			return "The objects of the class '$sClass' do not have any email attribute";
		}

		$oSet = new DBObjectSet($oSearch, array() /* order */, $aArgs);
		$aRecipients = array();
		while ($oObj = $oSet->Fetch())
		{
			$sAddress = trim($oObj->Get($sEmailAttCode));
			if (strlen($sAddress) > 0)
			{
				$aRecipients[] = $sAddress;
				$this->m_iRecipients++;
			}
		}
		return implode(', ', $aRecipients);
	}

	/**
	 * @inheritDoc
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \CoreWarning
	 */
	public function DoExecute($oTrigger, $aContextArgs)
	{
		if (MetaModel::IsLogEnabledNotification())
		{
			$oLog = new EventNotificationEmail();
			if ($this->IsBeingTested())
			{
				$oLog->Set('message', 'TEST - Notification sent ('.$this->Get('test_recipient').')');
			}
			else
			{
				$oLog->Set('message', 'Notification pending');
			}
			$oLog->Set('userinfo', UserRights::GetUser());
			$oLog->Set('trigger_id', $oTrigger->GetKey());
			$oLog->Set('action_id', $this->GetKey());
			$oLog->Set('object_id', $aContextArgs['this->object()']->GetKey());
			// Must be inserted now so that it gets a valid id that will make the link
			// between an eventual asynchronous task (queued) and the log
			$oLog->DBInsertNoReload();
		}
		else
		{
			$oLog = null;
		}

		try
		{
			$sRes = $this->_DoExecute($oTrigger, $aContextArgs, $oLog);

			if ($this->IsBeingTested())
			{
				$sPrefix = 'TEST ('.$this->Get('test_recipient').') - ';
			}
			else
			{
				$sPrefix = '';
			}

			if ($oLog)
			{
				$oLog->Set('message', $sPrefix . $sRes);
                $oLog->DBUpdate();
            }

		}
		catch (Exception $e)
		{
			if ($oLog)
			{
				$oLog->Set('message', 'Error: '.$e->getMessage());

				try
				{
                    $oLog->DBUpdate();
				}
				catch (Exception $eSecondTryUpdate)
				{
                    IssueLog::Error("Failed to process email ".$oLog->GetKey()." - reason: ".$e->getMessage()."\nTrace:\n".$e->getTraceAsString());

                    $oLog->Set('message', 'Error: more details in the log for email "'.$oLog->GetKey().'"');
                    $oLog->DBUpdate();
                }
			}
		}

	}

	/**
	 * @param \Trigger $oTrigger
	 * @param array $aContextArgs
	 * @param \EventNotification $oLog
	 *
	 * @return string
	 * @throws \CoreException
	 * @throws \Exception
	 */
	protected function _DoExecute($oTrigger, $aContextArgs, &$oLog)
	{
		$sPreviousUrlMaker = ApplicationContext::SetUrlMakerClass();
		try
		{
			$this->m_iRecipients = 0;
			$this->m_aMailErrors = array();

			// Determine recipients
			//
			$sTo = $this->FindRecipients('to', $aContextArgs);
			$sCC = $this->FindRecipients('cc', $aContextArgs);
			$sBCC = $this->FindRecipients('bcc', $aContextArgs);

			$sFrom = MetaModel::ApplyParams($this->Get('from'), $aContextArgs);
			$sFromLabel = MetaModel::ApplyParams($this->Get('from_label'), $aContextArgs);
			$sReplyTo = MetaModel::ApplyParams($this->Get('reply_to'), $aContextArgs);
			$sReplyToLabel = MetaModel::ApplyParams($this->Get('reply_to_label'), $aContextArgs);

			$sSubject = MetaModel::ApplyParams($this->Get('subject'), $aContextArgs);
			$sBody = MetaModel::ApplyParams($this->Get('body'), $aContextArgs);

			$oObj = $aContextArgs['this->object()'];
			$sMessageId = $this->GenerateIdentifierForHeaders($oObj, static::ENUM_HEADER_NAME_MESSAGE_ID);
			$sReference = $this->GenerateIdentifierForHeaders($oObj, static::ENUM_HEADER_NAME_REFERENCES);
		}
		catch (Exception $e) {
			/** @noinspection PhpUnhandledExceptionInspection */
			throw $e;
		}
		finally {
			ApplicationContext::SetUrlMakerClass($sPreviousUrlMaker);
		}

		if (!is_null($oLog)) {
			// Note: we have to secure this because those values are calculated
			// inside the try statement, and we would like to keep track of as
			// many data as we could while some variables may still be undefined
			if (isset($sTo)) {
				$oLog->Set('to', $sTo);
			}
			if (isset($sCC)) {
				$oLog->Set('cc', $sCC);
			}
			if (isset($sBCC)) {
				$oLog->Set('bcc', $sBCC);
			}
			if (isset($sFrom)) {
				$oLog->Set('from', $sFrom);
			}
			if (isset($sSubject)) {
				$oLog->Set('subject', $sSubject);
			}
			if (isset($sBody)) {
				$oLog->Set('body', $sBody);
			}
		}
		$sStyles = file_get_contents(APPROOT.'css/email.css');
		$sStyles .= MetaModel::GetConfig()->Get('email_css');

		$oEmail = new EMail();

		if ($this->IsBeingTested()) {
			$oEmail->SetSubject('TEST['.$sSubject.']');
			$sTestBody = $sBody;
			$sTestBody .= "<div style=\"border: dashed;\">\n";
			$sTestBody .= "<h1>Testing email notification ".$this->GetHyperlink()."</h1>\n";
			$sTestBody .= "<p>The email should be sent with the following properties\n";
			$sTestBody .= "<ul>\n";
			$sTestBody .= "<li>TO: $sTo</li>\n";
			$sTestBody .= "<li>CC: $sCC</li>\n";
			$sTestBody .= "<li>BCC: $sBCC</li>\n";
			$sTestBody .= empty($sFromLabel) ? "<li>From: $sFrom</li>\n" : "<li>From: $sFromLabel &lt;$sFrom&gt;</li>\n";
			$sTestBody .= empty($sReplyToLabel) ? "<li>Reply-To: $sReplyTo</li>\n" : "<li>Reply-To: $sReplyToLabel &lt;$sReplyTo&gt;</li>\n";
			$sTestBody .= "<li>References: $sReference</li>\n";
			$sTestBody .= "</ul>\n";
			$sTestBody .= "</p>\n";
			$sTestBody .= "</div>\n";
			$oEmail->SetBody($sTestBody, 'text/html', $sStyles);
			$oEmail->SetRecipientTO($this->Get('test_recipient'));
			$oEmail->SetRecipientFrom($sFrom, $sFromLabel);
			$oEmail->SetReferences($sReference);
			$oEmail->SetMessageId($sMessageId);
			// Note: N°4849 We pass the "References" identifier instead of the "Message-ID" on purpose as we want notifications emails to group around the triggering iTop object, not just the users' replies to the notification
			$oEmail->SetInReplyTo($sReference);
		} else {
			$oEmail->SetSubject($sSubject);
			$oEmail->SetBody($sBody, 'text/html', $sStyles);
			$oEmail->SetRecipientTO($sTo);
			$oEmail->SetRecipientCC($sCC);
			$oEmail->SetRecipientBCC($sBCC);
			$oEmail->SetRecipientFrom($sFrom, $sFromLabel);
			$oEmail->SetRecipientReplyTo($sReplyTo, $sReplyToLabel);
			$oEmail->SetReferences($sReference);
			$oEmail->SetMessageId($sMessageId);
			// Note: N°4849 We pass the "References" identifier instead of the "Message-ID" on purpose as we want notifications emails to group around the triggering iTop object, not just the users' replies to the notification
			$oEmail->SetInReplyTo($sReference);
		}

		if (isset($aContextArgs['attachments']))
		{
			$aAttachmentReport = array();
			foreach($aContextArgs['attachments'] as $oDocument)
			{
				$oEmail->AddAttachment($oDocument->GetData(), $oDocument->GetFileName(), $oDocument->GetMimeType());
				$aAttachmentReport[] = array($oDocument->GetFileName(), $oDocument->GetMimeType(), strlen($oDocument->GetData()));
			}
			$oLog->Set('attachments', $aAttachmentReport);
		}

		if (empty($this->m_aMailErrors))
		{
			if ($this->m_iRecipients == 0)
			{
				return 'No recipient';
			}
			else
			{
				$iRes = $oEmail->Send($aErrors, false, $oLog); // allow asynchronous mode
				switch ($iRes)
				{
					case EMAIL_SEND_OK:
						return "Sent";

					case EMAIL_SEND_PENDING:
						return "Pending";

					case EMAIL_SEND_ERROR:
						return "Errors: ".implode(', ', $aErrors);
				}
			}
		} else {
			if (is_array($this->m_aMailErrors) && count($this->m_aMailErrors) > 0) {
				$sError = implode(', ', $this->m_aMailErrors);
			} else {
				$sError = 'Unknown reason';
			}

			return 'Notification was not sent: '.$sError;
		}
	}

	/**
	 * @param \DBObject $oObject
	 * @param string $sHeaderName {@see \ActionEmail::ENUM_HEADER_NAME_REFERENCES}, {@see \ActionEmail::ENUM_HEADER_NAME_MESSAGE_ID}
	 *
	 * @return string The formatted identifier for $sHeaderName based on $oObject
	 * @throws \Exception
	 * @since 3.0.1 N°4849
	 */
	protected function GenerateIdentifierForHeaders(DBObject $oObject, string $sHeaderName): string
	{
		$sObjClass = get_class($oObject);
		$sObjId = $oObject->GetKey();
		$sAppName = utils::Sanitize(ITOP_APPLICATION_SHORT, '', utils::ENUM_SANITIZATION_FILTER_VARIABLE_NAME);

		switch ($sHeaderName) {
			case static::ENUM_HEADER_NAME_MESSAGE_ID:
			case static::ENUM_HEADER_NAME_REFERENCES:
				// Prefix
				$sPrefix = sprintf('%s_%s_%d', $sAppName, $sObjClass, $sObjId);
				if ($sHeaderName === static::ENUM_HEADER_NAME_MESSAGE_ID) {
					$sPrefix .= sprintf('_%f', microtime(true /* get as float*/));
				}
				// Suffix
				$sSuffix = sprintf('@%s.openitop.org', MetaModel::GetEnvironmentId());
				// Identifier
				$sIdentifier = $sPrefix.$sSuffix;
				if ($sHeaderName === static::ENUM_HEADER_NAME_REFERENCES) {
					$sIdentifier = "<$sIdentifier>";
				}

				return $sIdentifier;
		}

		// Requested header name invalid
		$sErrorMessage = sprinf('%s: Could not generate identifier for header "%s", only %s are supported', static::class, $sHeaderName, implode(' / ', [static::ENUM_HEADER_NAME_MESSAGE_ID, static::ENUM_HEADER_NAME_REFERENCES]));
		IssueLog::Error($sErrorMessage, LogChannels::NOTIFICATIONS, [
			'Object' => $sObjClass.'::'.$sObjId.' ('.$oObject->GetRawName().')',
			'Action' => get_class($this).'::'.$this->GetKey().' ('.$this->GetRawName().')',
		]);
		throw new Exception($sErrorMessage);
	}
}

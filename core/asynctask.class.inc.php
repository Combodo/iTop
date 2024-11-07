<?php
// Copyright (C) 2010-2024 Combodo SAS
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
use Combodo\iTop\Service\Notification\Event\EventNotificationNewsroomService;


/**
 * Persistent classes (internal): user defined actions
 *
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


class ExecAsyncTask implements iBackgroundProcess
{
	public function GetPeriodicity()
	{	
		return 2; // seconds
	}

	public function Process($iTimeLimit)
	{
		$sNow = date(AttributeDateTime::GetSQLFormat());
		// Criteria: planned, and expected to occur... ASAP or in the past
		$sOQL = "SELECT AsyncTask WHERE (status = 'planned') AND (ISNULL(planned) OR (planned < '$sNow'))";
		$iProcessed = 0;
		while (time() < $iTimeLimit)
		{
			// Next one ?
			$oSet = new CMDBObjectSet(DBObjectSearch::FromOQL($sOQL), array('created' => true) /* order by*/, array(), null, 1 /* limit count */);
			$oTask = $oSet->Fetch();
			if (is_null($oTask))
			{
				// Nothing to be done
				break;
			}
			$iProcessed++;
			if ($oTask->Process())
			{
				$oTask->DBDelete();
			}
		}
		return "processed $iProcessed tasks";
	}
}

/**
 * A   
 *
 * @package     iTopORM
 */
abstract class AsyncTask extends DBObject
{
	/**
	 * @throws \CoreException
	 * @throws \Exception
	 */
	public static function Init()
	{
		$aParams = array
		(
			"category" => "core/cmdb",
			"key_type" => "autoincrement",
			"name_attcode" => array('created'),
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_async_task",
			"db_key_field" => "id",
			"db_finalclass_field" => "realclass",
		);
		MetaModel::Init_Params($aParams);

		// Null is allowed to ease the migration from iTop 2.0.2 and earlier, when the status did not exist, and because the default value is not taken into account in the SQL definition
		// The value is set from null to planned in the setup program
		MetaModel::Init_AddAttribute(new AttributeEnum("status", array("allowed_values"=>new ValueSetEnum('planned,running,idle,error'), "sql"=>"status", "default_value"=>"planned", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_AddAttribute(new AttributeDateTime("created", array("allowed_values"=>null, "sql"=>"created", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeDateTime("started", array("allowed_values"=>null, "sql"=>"started", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeDateTime("planned", array("allowed_values"=>null, "sql"=>"planned", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("event_id", array("targetclass"=>"Event", "jointype"=> "", "allowed_values"=>null, "sql"=>"event_id", "is_null_allowed"=>true, "on_target_delete"=>DEL_SILENT, "depends_on"=>array())));

		MetaModel::Init_AddAttribute(new AttributeInteger("remaining_retries", array("allowed_values"=>null, "sql"=>"remaining_retries", "default_value"=>0, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeInteger("last_error_code", array("allowed_values"=>null, "sql"=>"last_error_code", "default_value"=>0, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("last_error", array("allowed_values"=>null, "sql"=>"last_error", "default_value"=>'', "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeDateTime("last_attempt", array("allowed_values"=>null, "sql"=>"last_attempt", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
	}

	/**
	 * Every is fine
	 */
	const OK = 0;
	/**
	 * The task no longer exists
	 */
	const DELETED = 1;
	/**
	 * The task is already being executed
	 */
	const ALREADY_RUNNING = 2;

	/**
	 *	The current process requests the ownership on the task.
	 *	In case the task can be accessed concurrently, this function can be overloaded to add a critical section.
	 *	The function must not block the caller if another process is already owning the task	 
	 *		 
	 *	@return integer A code among OK/DELETED/ALREADY_RUNNING.	  	 
	 */	
	public function MarkAsRunning()
	{
		try
		{
			if ($this->Get('status') == 'running')
			{
				return self::ALREADY_RUNNING;
			}
			else
			{
				$this->Set('status', 'running');
				$this->Set('started', time());
				$this->DBUpdate();
				return self::OK;
			}
		}
		catch(Exception $e)
		{
			// Corrupted task !! (for example: "Failed to reload object")
			IssueLog::Error('Failed to process async task #'.$this->GetKey().' - reason: '.$e->getMessage().' - fatal error, deleting the task.');
	   	if ($this->Get('event_id') != 0)
	   	{
	   		$oEventLog = MetaModel::GetObject('Event', $this->Get('event_id'));
	   		$oEventLog->Set('message', 'Failed, corrupted data: '.$e->getMessage());
	   		$oEventLog->DBUpdate();
			}
			$this->DBDelete();
			return self::DELETED;
		}
	}

	public function GetRetryDelay($iErrorCode = null)
	{
		$iRetryDelay = 600;
		$aRetries = MetaModel::GetConfig()->Get('async_task_retries');
		if (is_array($aRetries) && array_key_exists(get_class($this), $aRetries))
		{
			$aConfig = $aRetries[get_class($this)];
			$iRetryDelay = $aConfig['retry_delay'] ?? $iRetryDelay;
		}
		return $iRetryDelay;
	}

	public function GetMaxRetries($iErrorCode = null)
	{
		$iMaxRetries = 0;
		$aRetries = MetaModel::GetConfig()->Get('async_task_retries');
		if (is_array($aRetries) && array_key_exists(get_class($this), $aRetries))
		{
			$aConfig = $aRetries[get_class($this)];
			$iMaxRetries = $aConfig['max_retries'] ?? $iMaxRetries;
		}
		return $iMaxRetries;
	}

	public function IsRetryDelayExponential()
	{
	    $bExponential = false;
	    $aRetries = MetaModel::GetConfig()->Get('async_task_retries');
	    if (is_array($aRetries) && array_key_exists(get_class($this), $aRetries))
	    {
	        $aConfig = $aRetries[get_class($this)];
		    $bExponential = (bool) ($aConfig['exponential_delay'] ?? $bExponential);
	    }
	    return $bExponential;
	}

	public static function CheckRetryConfig(Config $oConfig, $sAsyncTaskClass)
	{
	    $aMessages = [];
	    $aRetries = $oConfig->Get('async_task_retries');
	    if (is_array($aRetries) && array_key_exists($sAsyncTaskClass, $aRetries))
	    {
	        $aValidKeys = array("retry_delay", "max_retries", "exponential_delay");
	        $aConfig = $aRetries[$sAsyncTaskClass];
	        if (!is_array($aConfig))
	        {
	            $aMessages[] = Dict::Format('Class:AsyncTask:InvalidConfig_Class_Keys', $sAsyncTaskClass, implode(', ', $aValidKeys));
	        }
	        else
	        {
	            foreach($aConfig as $key => $value)
	            {
	                if (!in_array($key, $aValidKeys))
	                {
	                    $aMessages[] = Dict::Format('Class:AsyncTask:InvalidConfig_Class_InvalidKey_Keys', $sAsyncTaskClass, $key, implode(', ', $aValidKeys));
	                }
	            }
	        }
	    }
	    return $aMessages;
	}

	/**
	 * Compute the delay to wait for the "next retry", based on the given parameters
	 * @param bool $bIsExponential
	 * @param int $iRetryDelay
	 * @param int $iMaxRetries
	 * @param int $iRemainingRetries
	 * @return int
	 */
	public static function GetNextRetryDelay($bIsExponential, $iRetryDelay, $iMaxRetries, $iRemainingRetries)
	{
	    if ($bIsExponential)
	    {
	        $iExponent = $iMaxRetries - $iRemainingRetries;
	        if ($iExponent < 0) $iExponent = 0; // Safety net in case on configuration change in the middle of retries
	        return $iRetryDelay * (2 ** $iExponent);
	    }
	    else
	    {
	        return $iRetryDelay;
	    }
	}

	/**
	 * Override to notify people that a task cannot be performed
	 */
	protected function OnDefinitiveFailure()
	{
	}

  	protected function OnInsert()
	{
		$this->Set('created', time());
	}

   /**
    * @return boolean True if the task record can be deleted
    */
	public function Process()
	{
		// By default: consider that the task is not completed
		$bRet = false;

		// Attempt to take the ownership
		$iStatus = $this->MarkAsRunning();
		if ($iStatus == self::OK)
		{
			try
			{
				$sStatus = $this->DoProcess();
				if ($this->Get('event_id') != 0)
				{
					$oEventLog = MetaModel::GetObject('Event', $this->Get('event_id'));
					$oEventLog->Set('message', $sStatus);
					$oEventLog->DBUpdate();
				}
				$bRet = true;
			} catch (Exception $e)
			{
				$this->HandleError($e->getMessage(), $e->getCode());
			}
		}
		else
		{
			// Already done or being handled by another process... skip...
			$bRet = false;
		}

		return $bRet;
	}

	/**
	 * Overridable to extend the behavior in case of error (logging)
	 */
	protected function HandleError($sErrorMessage, $iErrorCode)
	{
		if ($this->Get('last_attempt') == '')
		{
			// First attempt
			$this->Set('remaining_retries', $this->GetMaxRetries($iErrorCode));
		}

		$this->SetTrim('last_error', $sErrorMessage);
		$this->Set('last_error_code', $iErrorCode); // Note: can be ZERO !!!
		$this->Set('last_attempt', time());

		$iRemaining = $this->Get('remaining_retries');
		if ($iRemaining > 0)
		{
			$iRetryDelay = $this->GetRetryDelay($iErrorCode);
			$iNextRetryDelay = static::GetNextRetryDelay($this->IsRetryDelayExponential(), $iRetryDelay, $this->GetMaxRetries($iErrorCode), $iRemaining);
			IssueLog::Info('Failed to process async task #'.$this->GetKey().' - reason: '.$sErrorMessage.' - remaining retries: '.$iRemaining.' - next retry in '.$iNextRetryDelay.'s');
			if ($this->Get('event_id') != 0)
			{
				$oEventLog = MetaModel::GetObject('Event', $this->Get('event_id'));
				$oEventLog->Set('message', "$sErrorMessage\nFailed to process async task. Remaining retries: $iRemaining. Next retry in {$iNextRetryDelay}s");
                try
                {
                    $oEventLog->DBUpdate();
                }
                catch (Exception $e)
                {
                    $oEventLog->Set('message', "Failed to process async task. Remaining retries: $iRemaining. Next retry in {$iNextRetryDelay}s, more details in the log");
                    $oEventLog->DBUpdate();
                }
			}
			$this->Set('remaining_retries', $iRemaining - 1);
			$this->Set('status', 'planned');
			$this->Set('started', null);
			$this->Set('planned', time() + $iNextRetryDelay);
		}
		else
		{
			IssueLog::Error('Failed to process async task #'.$this->GetKey().' - reason: '.$sErrorMessage);
			if ($this->Get('event_id') != 0)
			{
				$oEventLog = MetaModel::GetObject('Event', $this->Get('event_id'));
				$oEventLog->Set('message', "$sErrorMessage\nFailed to process async task.");
				try
				{
                    $oEventLog->DBUpdate();
				}
				catch (Exception $e)
				{
                    $oEventLog->Set('message', 'Failed to process async task, more details in the log');
                    $oEventLog->DBUpdate();
				}
			}
			$this->Set('status', 'error');
			$this->Set('started', null);
			$this->Set('planned', null);
			$this->OnDefinitiveFailure();
		}
		$this->DBUpdate();
	}

	/**
	 * Throws an exception (message and code)
	 *
	 * @return string
	 */	 	
	abstract public function DoProcess();

	/**
	 * Describes the error codes that DoProcess can return by the mean of exceptions
	 */	
	static public function EnumErrorCodes()
	{
		return array();
	}
}

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

		MetaModel::Init_AddAttribute(new AttributeInteger("version", array("allowed_values"=>null, "sql"=>"version", "default_value"=>Email::ORIGINAL_FORMAT, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeText("to", array("allowed_values"=>null, "sql"=>"to", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeText("subject", array("allowed_values"=>null, "sql"=>"subject", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLongText("message", array("allowed_values"=>null, "sql"=>"message", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));

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
		if ($oLog)
		{
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
		$iVersion = (int) $this->Get('version');
		switch($iVersion)
		{
			case Email::FORMAT_V2:
			$oEMail = Email::UnSerializeV2($sMessage);				
			break;
			
			case Email::ORIGINAL_FORMAT:
			$oEMail = unserialize($sMessage);				
			break;
			
			default:
			return 'Unknown version of the serialization format: '.$iVersion;				
		}
		$iRes = $oEMail->Send($aIssues, true /* force synchro !!!!! */);
		switch ($iRes)
		{
		case EMAIL_SEND_OK:
			return "Sent";

		case EMAIL_SEND_PENDING:
			return "Bug - the email should be sent in synchronous mode";

		case EMAIL_SEND_ERROR:
		    if (is_array($aIssues)) {
		        $sMessage = "Sending eMail failed: ".implode(', ', $aIssues);
		    } else {
		        $sMessage = "Sending eMail failed.";
		    }
		    throw new Exception($sMessage);
		}
		return '';
	}
}

/**
 * An async notification to be sent to iTop users through the newsroom
 * @since 3.2.0
 */
class AsyncSendNewsroom extends AsyncTask {

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

		MetaModel::Init_AddAttribute(new AttributeText("recipients", array("allowed_values"=>null, "sql"=>"recipients", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("action_id", array("targetclass"=>"Action", "allowed_values"=>null, "sql"=>"action_id", "default_value"=>null, "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("trigger_id", array("targetclass"=>"Trigger", "allowed_values"=>null, "sql"=>"trigger_id", "default_value"=>null, "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeText("title", array("allowed_values"=>null, "sql"=>"title", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeText("message", array("allowed_values"=>null, "sql"=>"message", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeInteger("object_id", array("allowed_values"=>null, "sql"=>"object_id", "default_value"=>null, "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("object_class", array("allowed_values"=>null, "sql"=>"object_class", "default_value"=>null, "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeText("url", array("allowed_values"=>null, "sql"=>"url", "default_value"=>null, "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeDateTime("date", array("allowed_values"=>null, "sql"=>"date", "default_value"=>null, "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));

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
		
		foreach ($aRecipients as $iRecipientId)
		{
			$oEvent = EventNotificationNewsroomService::MakeEventFromAction($oAction, $iRecipientId, $iTriggerId, $sMessage, $sTitle, $sUrl, $iObjectId, $sObjectClass, $sDate);
			$oEvent->DBInsertNoReload();
		}
		
		return "Sent";
	}
}
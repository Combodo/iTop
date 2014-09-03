<?php
// Copyright (C) 2010-2012 Combodo SARL
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
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
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
		$sNow = date('Y-m-d H:i:s');
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
			"display_template" => "",
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
		$aRetries = MetaModel::GetConfig()->Get('async_task_retries', array());
		if (is_array($aRetries) && array_key_exists(get_class($this), $aRetries))
		{
			$aConfig = $aRetries[get_class($this)];
			$iRetryDelay = $aConfig['retry_delay'];
		}
		return $iRetryDelay;
	}

	public function GetMaxRetries($iErrorCode = null)
	{
		$iMaxRetries = 0;
		$aRetries = MetaModel::GetConfig()->Get('async_task_retries', array());
		if (is_array($aRetries) && array_key_exists(get_class($this), $aRetries))
		{
			$aConfig = $aRetries[get_class($this)];
			$iMaxRetries = $aConfig['max_retries'];
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
			}
			catch(Exception $e)
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

		$this->Set('last_error', $sErrorMessage);
		$this->Set('last_error_code', $iErrorCode); // Note: can be ZERO !!!
		$this->Set('last_attempt', time());

		$iRemaining = $this->Get('remaining_retries');
		if ($iRemaining > 0)
		{
			$iRetryDelay = $this->GetRetryDelay($iErrorCode);
			IssueLog::Info('Failed to process async task #'.$this->GetKey().' - reason: '.$sErrorMessage.' - remaining retries: '.$iRemaining.' - next retry in '.$iRetryDelay.'s');

			$this->Set('remaining_retries', $iRemaining - 1);
			$this->Set('status', 'planned');
			$this->Set('started', null);
			$this->Set('planned', time() + $iRetryDelay);
		}
		else
		{
			IssueLog::Error('Failed to process async task #'.$this->GetKey().' - reason: '.$sErrorMessage);

			$this->Set('status', 'error');
			$this->Set('started', null);
			$this->Set('planned', null);
			$this->OnDefinitiveFailure();
		}
		$this->DBUpdate();
	}

	/**
	 * Throws an exception (message and code)
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
			"display_template" => "",
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

//		$oNew->Set('version', 1);
//		$sMessage = serialize($oEMail);
		$oNew->Set('version', 2);
		$sMessage = $oEMail->SerializeV2();
		$oNew->Set('message', $sMessage);
		$oNew->DBInsert();
	}

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
			return "Failed: ".implode(', ', $aIssues);
		}
	}
}
?>

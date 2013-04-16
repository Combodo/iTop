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
		$sOQL = "SELECT AsyncTask WHERE ISNULL(started) AND (ISNULL(planned) OR (planned < NOW()))";
		$oSet = new CMDBObjectSet(DBObjectSearch::FromOQL($sOQL), array('created' => true) /* order by*/, array());
		$iProcessed = 0;
		while ((time() < $iTimeLimit) && ($oTask = $oSet->Fetch()))
		{
			$oTask->Set('started', time());
			$oTask->DBUpdate();

			$oTask->Process();
			$iProcessed++;

			$oTask->DBDelete();
		}
		if ($iProcessed == $oSet->Count())
		{
			return "processed $iProcessed tasks";
		}
		else
		{
			return "processed $iProcessed tasks (remaining: ".($oSet->Count() - $iProcessed).")";
		}
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
		//MetaModel::Init_InheritAttributes();
//		MetaModel::Init_AddAttribute(new AttributeString("name", array("allowed_values"=>null, "sql"=>"name", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeDateTime("created", array("allowed_values"=>null, "sql"=>"created", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeDateTime("started", array("allowed_values"=>null, "sql"=>"started", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		// planned... still not used - reserved for timer management
		MetaModel::Init_AddAttribute(new AttributeDateTime("planned", array("allowed_values"=>null, "sql"=>"planned", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("event_id", array("targetclass"=>"Event", "jointype"=> "", "allowed_values"=>null, "sql"=>"event_id", "is_null_allowed"=>true, "on_target_delete"=>DEL_SILENT, "depends_on"=>array())));

		// Display lists
//		MetaModel::Init_SetZListItems('details', array()); // Attributes to be displayed for the complete details
//		MetaModel::Init_SetZListItems('list', array()); // Attributes to be displayed for a list
		// Search criteria
//		MetaModel::Init_SetZListItems('standard_search', array('name')); // Criteria of the std search form
//		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
	}

  	protected function OnInsert()
	{
		$this->Set('created', time());
	}

   public function Process()
   {
   	$sStatus = $this->DoProcess();
   	if ($this->Get('event_id') != 0)
   	{
   		$oEventLog = MetaModel::GetObject('Event', $this->Get('event_id'));
   		$oEventLog->Set('message', $sStatus);
   		$oEventLog->DBUpdate();
		}
	}

	abstract public function DoProcess();
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
		MetaModel::Init_AddAttribute(new AttributeText("to", array("allowed_values"=>null, "sql"=>"to", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
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

<?php
// Copyright (C) 2010 Combodo SARL
//
//   This program is free software; you can redistribute it and/or modify
//   it under the terms of the GNU General Public License as published by
//   the Free Software Foundation; version 3 of the License.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of the GNU General Public License
//   along with this program; if not, write to the Free Software
//   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

/**
 * Persistent classes (internal): user defined actions
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
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

		MetaModel::Init_AddAttribute(new AttributeText("to", array("allowed_values"=>null, "sql"=>"to", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeText("subject", array("allowed_values"=>null, "sql"=>"subject", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeText("body", array("allowed_values"=>null, "sql"=>"body", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeText("header", array("allowed_values"=>null, "sql"=>"header", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));

		// Display lists
//		MetaModel::Init_SetZListItems('details', array('name', 'description', 'status', 'test_recipient', 'from', 'reply_to', 'to', 'cc', 'bcc', 'subject', 'body', 'importance', 'trigger_list')); // Attributes to be displayed for the complete details
//		MetaModel::Init_SetZListItems('list', array('name', 'status', 'to', 'subject')); // Attributes to be displayed for a list
		// Search criteria
//		MetaModel::Init_SetZListItems('standard_search', array('name')); // Criteria of the std search form
//		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
	}

	static public function AddToQueue($sTo, $sSubject, $sBody, $aHeaders, $oLog)
	{
		$oNew = MetaModel::NewObject(__class__);
		if ($oLog)
		{
			$oNew->Set('event_id', $oLog->GetKey());
		}
		$oNew->Set('to', $sTo);
		$oNew->Set('subject', $sSubject);
		$oNew->Set('body', $sBody);
		$sHeaders = serialize($aHeaders);
		$oNew->Set('header', $sHeaders);
		$oNew->DBInsert();
	}

	public function DoProcess()
	{
		$sTo = $this->Get('to');
		$sSubject = $this->Get('subject');
		$sBody = $this->Get('body');
		$sHeaders = $this->Get('header');
		$aHeaders = unserialize($sHeaders);

		$oEmail = new EMail($sTo, $sSubject, $sBody, $aHeaders);
		$iRes = $oEmail->Send($aIssues, true /* force synchro !!!!! */);
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
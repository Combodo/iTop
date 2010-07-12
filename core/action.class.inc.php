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


require_once('../core/email.class.inc.php');

/**
 * A user defined action, to customize the application  
 *
 * @package     iTopORM
 */
abstract class Action extends cmdbAbstractObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "core/cmdb",
			"key_type" => "autoincrement",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_action",
			"db_key_field" => "id",
			"db_finalclass_field" => "realclass",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		//MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeString("name", array("allowed_values"=>null, "sql"=>"name", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("description", array("allowed_values"=>null, "sql"=>"description", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("status", array("allowed_values"=>new ValueSetEnum(array('test'=>'Being tested' ,'enabled'=>'In production', 'disabled'=>'Inactive')), "sql"=>"status", "default_value"=>"test", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("trigger_list", array("linked_class"=>"lnkTriggerAction", "ext_key_to_me"=>"action_id", "ext_key_to_remote"=>"trigger_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('name', 'description', 'status', 'trigger_list')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('finalclass', 'name', 'description', 'status')); // Attributes to be displayed for a list
		// Search criteria
//		MetaModel::Init_SetZListItems('standard_search', array('name')); // Criteria of the std search form
//		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
	}

	abstract public function DoExecute($oTrigger, $aContextArgs);

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
}

/**
 * A notification  
 *
 * @package     iTopORM
 */
abstract class ActionNotification extends Action
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "core/cmdb",
			"key_type" => "autoincrement",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_action_notification",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		// Display lists
		MetaModel::Init_SetZListItems('details', array('name', 'description', 'status')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('finalclass', 'name', 'description', 'status')); // Attributes to be displayed for a list
		// Search criteria
//		MetaModel::Init_SetZListItems('standard_search', array('name')); // Criteria of the std search form
//		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
	}
}

/**
 * An email notification  
 *
 * @package     iTopORM
 */
class ActionEmail extends ActionNotification
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "core/cmdb",
			"key_type" => "autoincrement",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_action_email",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeEmailAddress("test_recipient", array("allowed_values"=>null, "sql"=>"test_recipient", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_AddAttribute(new AttributeString("from", array("allowed_values"=>null, "sql"=>"from", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("reply_to", array("allowed_values"=>null, "sql"=>"reply_to", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeOQL("to", array("allowed_values"=>null, "sql"=>"to", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeOQL("cc", array("allowed_values"=>null, "sql"=>"cc", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeOQL("bcc", array("allowed_values"=>null, "sql"=>"bcc", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeTemplateString("subject", array("allowed_values"=>null, "sql"=>"subject", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeTemplateText("body", array("allowed_values"=>null, "sql"=>"body", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("importance", array("allowed_values"=>new ValueSetEnum('low,normal,high'), "sql"=>"importance", "default_value"=>'normal', "is_null_allowed"=>false, "depends_on"=>array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('name', 'description', 'status', 'test_recipient', 'from', 'reply_to', 'to', 'cc', 'bcc', 'subject', 'body', 'importance')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('name', 'status', 'to', 'subject')); // Attributes to be displayed for a list
		// Search criteria
//		MetaModel::Init_SetZListItems('standard_search', array('name')); // Criteria of the std search form
//		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
	}

	// count the recipients found
	protected $m_iRecipients;

	// Errors management : not that simple because we need that function to be
	// executed in the background, while making sure that any issue would be reported clearly
	protected $m_aMailErrors; //array of strings explaining the issue

	// returns a the list of emails as a string, or a detailed error description
	protected function FindRecipients($sRecipAttCode, $aArgs)
	{
		$sOQL = $this->Get($sRecipAttCode);
		if (strlen($sOQL) == '') return '';

		try
		{
			$oSearch = DBObjectSearch::FromOQL($sOQL);
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
			$aRecipients[] = $oObj->Get($sEmailAttCode);
			$this->m_iRecipients++;
		}
		return implode(', ', $aRecipients);
	}


	public function DoExecute($oTrigger, $aContextArgs)
	{
		$this->m_iRecipients = 0;
		$this->m_aMailErrors = array();
		$bRes = false; // until we do succeed in sending the email
		try
		{
			// Determine recicipients
			//
			$sTo = $this->FindRecipients('to', $aContextArgs);
			$sCC = $this->FindRecipients('cc', $aContextArgs);
			$sBCC = $this->FindRecipients('bcc', $aContextArgs);

			$sFrom = $this->Get('from');
			$sReplyTo = $this->Get('reply_to');
	
			$sSubject = MetaModel::ApplyParams($this->Get('subject'), $aContextArgs);
			$sBody = MetaModel::ApplyParams($this->Get('body'), $aContextArgs);

			$oEmail = new EMail();

			if ($this->IsBeingTested())
			{
				$oEmail->SetSubject('TEST['.$sSubject.']');
				$sTestBody = $sBody;
				$sTestBody .= "<div style=\"border: dashed;\">\n";
				$sTestBody .= "<h1>Testing email notification ".$this->GetHyperlink()."</h1>\n";
				$sTestBody .= "<p>The email should be sent with the following properties\n";
				$sTestBody .= "<ul>\n";
				$sTestBody .= "<li>TO: $sTo</li>\n";
				$sTestBody .= "<li>CC: $sCC</li>\n";
				$sTestBody .= "<li>BCC: $sBCC</li>\n";
				$sTestBody .= "<li>From: $sFrom</li>\n";
				$sTestBody .= "<li>Reply-To: $sReplyTo</li>\n";
				$sTestBody .= "</ul>\n";
				$sTestBody .= "</p>\n";
				$sTestBody .= "</div>\n";
				$oEmail->SetBody($sTestBody);
				$oEmail->SetRecipientTO($this->Get('test_recipient'));
				$oEmail->SetRecipientFrom($this->Get('test_recipient'));
			}
			else
			{
				$oEmail->SetSubject($sSubject);
				$oEmail->SetBody($sBody);
				$oEmail->SetRecipientTO($sTo);
				$oEmail->SetRecipientCC($sCC);
				$oEmail->SetRecipientBCC($sBCC);
				$oEmail->SetRecipientFrom($sFrom);
				$oEmail->SetRecipientReplyTo($sReplyTo);
			}
	
			if (empty($this->m_aMailErrors))
			{
				if ($this->m_iRecipients == 0)
				{
					$this->m_aMailErrors[] = 'No recipient';
				}
				else
				{
					$this->m_aMailErrors = array_merge($this->m_aMailErrors, $oEmail->Send());
				}
			}
		}
		catch (Exception $e)
		{
			$this->m_aMailErrors[] = $e->getMessage();
		}

		if (MetaModel::IsLogEnabledNotification())
		{
			$oLog = new EventNotificationEmail();
			if (empty($this->m_aMailErrors))
			{
				if ($this->IsBeingTested())
				{
					$oLog->Set('message', 'TEST - Notification sent ('.$this->Get('test_recipient').')');
				}
				else
				{
					$oLog->Set('message', 'Notification sent');
				}
			}
			else
			{
				if (is_array($this->m_aMailErrors) && count($this->m_aMailErrors) > 0)
				{
					$sError = implode(', ', $this->m_aMailErrors);
				}
				else
				{
					$sError = 'Unknown reason';
				}
				if ($this->IsBeingTested())
				{
					$oLog->Set('message', 'TEST - Notification was not sent: '.$sError);
				}
				else
				{
					$oLog->Set('message', 'Notification was not sent: '.$sError);
				}
			}
			$oLog->Set('userinfo', UserRights::GetUser());
			$oLog->Set('trigger_id', $oTrigger->GetKey());
			$oLog->Set('action_id', $this->GetKey());
			$oLog->Set('object_id', $aContextArgs['this->id']);

			// Note: we have to secure this because those values are calculated
			// inside the try statement, and we would like to keep track of as
			// many data as we could while some variables may still be undefined
			if (isset($sTo))       $oLog->Set('to', $sTo);
			if (isset($sCC))       $oLog->Set('cc', $sCC);
			if (isset($sBCC))      $oLog->Set('bcc', $sBCC);
			if (isset($sFrom))     $oLog->Set('from', $sFrom);
			if (isset($sSubject))  $oLog->Set('subject', $sSubject);
			if (isset($sBody))     $oLog->Set('body', $sBody);
			$oLog->DBInsertNoReload();
		}
	}
}
?>

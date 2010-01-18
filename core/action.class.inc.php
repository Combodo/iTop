<?php

/**
 * A user defined action, to customize the application  
 *
 * @package     iTopORM
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @author      Denis Flaven <denisflave@free.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     1.1.1.1 $
 */
abstract class Action extends cmdbAbstractObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "core/cmdb",
			"name" => "action",
			"description" => "Custom action",
			"key_type" => "autoincrement",
			"key_label" => "",
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
		MetaModel::Init_AddAttribute(new AttributeString("name", array("label"=>"Name", "description"=>"label", "allowed_values"=>null, "sql"=>"name", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("description", array("label"=>"Description", "description"=>"one line description", "allowed_values"=>null, "sql"=>"description", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("related_triggers", array("label"=>"Related Triggers", "description"=>"Triggers linked to this action", "linked_class"=>"lnkTriggerAction", "ext_key_to_me"=>"action_id", "ext_key_to_remote"=>"trigger_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));

		//MetaModel::Init_InheritFilters();
		MetaModel::Init_AddFilterFromAttribute("name");
		MetaModel::Init_AddFilterFromAttribute("description");

		// Display lists
		MetaModel::Init_SetZListItems('details', array('name', 'description')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('name', 'description')); // Attributes to be displayed for a list
		// Search criteria
//		MetaModel::Init_SetZListItems('standard_search', array('name')); // Criteria of the std search form
//		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
	}

	abstract public function DoExecute($oTrigger, $aContextArgs);
}

/**
 * A notification  
 *
 * @package     iTopORM
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @author      Denis Flaven <denisflave@free.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     1.1.1.1 $
 */
abstract class ActionNotification extends Action
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "core/cmdb",
			"name" => "notification",
			"description" => "Notification (abstract)",
			"key_type" => "autoincrement",
			"key_label" => "",
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

		MetaModel::Init_InheritFilters();

		// Display lists
		MetaModel::Init_SetZListItems('details', array('name', 'description')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('finalclass', 'name', 'description')); // Attributes to be displayed for a list
		// Search criteria
//		MetaModel::Init_SetZListItems('standard_search', array('name')); // Criteria of the std search form
//		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
	}
}

/**
 * An email notification  
 *
 * @package     iTopORM
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @author      Denis Flaven <denisflave@free.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     1.1.1.1 $
 */
class ActionEmail extends ActionNotification
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "core/cmdb",
			"name" => "email notification",
			"description" => "Action: Email notification",
			"key_type" => "autoincrement",
			"key_label" => "",
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

		MetaModel::Init_AddAttribute(new AttributeString("from", array("label"=>"From", "description"=>"Will be sent into the email header", "allowed_values"=>null, "sql"=>"from", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("reply_to", array("label"=>"Reply to", "description"=>"Will be sent into the email header", "allowed_values"=>null, "sql"=>"reply_to", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeOQL("to", array("label"=>"To", "description"=>"Destination of the email", "allowed_values"=>null, "sql"=>"to", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeOQL("cc", array("label"=>"Cc", "description"=>"Carbon Copy", "allowed_values"=>null, "sql"=>"cc", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeOQL("bcc", array("label"=>"bcc", "description"=>"Blind Carbon Copy", "allowed_values"=>null, "sql"=>"bcc", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeTemplateString("subject", array("label"=>"subject", "description"=>"Title of the email", "allowed_values"=>null, "sql"=>"subject", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeTemplateText("body", array("label"=>"body", "description"=>"Contents of the email", "allowed_values"=>null, "sql"=>"body", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("importance", array("label"=>"importance", "description"=>"Importance flag", "allowed_values"=>new ValueSetEnum('low,normal,high'), "sql"=>"importance", "default_value"=>'normal', "is_null_allowed"=>false, "depends_on"=>array())));

		MetaModel::Init_InheritFilters();

		// Display lists
		MetaModel::Init_SetZListItems('details', array('name', 'description', 'from', 'reply_to', 'to', 'cc', 'bcc', 'subject', 'body', 'importance')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('name', 'to', 'subject')); // Attributes to be displayed for a list
		// Search criteria
//		MetaModel::Init_SetZListItems('standard_search', array('name')); // Criteria of the std search form
//		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
	}

	// args: a search object
	// returns an array of emails
	protected function FindRecipients($sAttCode, $aArgs)
	{
		$sOQL = $this->Get($sAttCode);
		if (strlen($sOQL) == '') return '';

		$oSearch = DBObjectSearch::FromOQL($sOQL);
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

		$oSet = new DBObjectSet($oSearch, array() /* order */, $aArgs);
		$aRecipients = array();
		while ($oObj = $oSet->Fetch())
		{
			$aRecipients[] = $oObj->Get($sEmailAttCode);
		}
		return implode(', ', $aRecipients);
	}

	public function DoExecute($oTrigger, $aContextArgs)
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

		// To send HTML mail, the Content-type header must be set
		$sHeaders  = 'MIME-Version: 1.0' . "\r\n";
		$sHeaders .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

		// Additional headers
		if (strlen($sFrom) > 0)
		{
			$sHeaders .= "From: $sFrom\r\n";
			// This is required on Windows because otherwise I would get the error
			// "sendmail_from" not set in php.ini" even if it is correctly working
			// (apparently, once it worked the SMTP server won't claim anymore for it)
			ini_set("sendmail_from", $sFrom);
		}
		if (strlen($sReplyTo) > 0)
		{
			$sHeaders .= "Reply-To: $sReplyTo\r\n";
		}
		if (strlen($sCC) > 0)
		{
			$sHeaders .= "Cc: $sCC\r\n";
		}
		if (strlen($sBCC) > 0)
		{
			$sHeaders .= "Bcc: $sBCC\r\n";
		}

		$oLog = new EventNotificationEmail();
		if (mail($sTo, $sSubject, $sBody, $sHeaders))
		{
			$oLog->Set('message', 'Notification sent');
		}
		else
		{
			$aLastError = error_get_last();
			$oLog->Set('message', 'Mail could not be sent: '.$aLastError['message']);
			//throw new CoreException('mail not sent', array('action'=>$this->GetKey(), 'to'=>$sTo, 'subject'=>$sSubject, 'headers'=>$sHeaders));
		}

		$oLog->Set('userinfo', UserRights::GetUser());
		$oLog->Set('trigger_id', $oTrigger->GetKey());
		$oLog->Set('action_id', $this->GetKey());
		$oLog->Set('object_id', $aContextArgs['this->id']);
		$oLog->Set('from', $sFrom);
		$oLog->Set('to', $sTo);
		$oLog->Set('cc', $sCC);
		$oLog->Set('bcc', $sBCC);
		$oLog->Set('subject', $sSubject);
		$oLog->Set('body', $sBody);
		$oLog->DBInsertNoReload();
	}
}
?>

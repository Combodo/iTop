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
 * Persistent class Event and derived
 * Application internal events
 * There is also a file log 
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

class Event extends DBObject implements iDisplay
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "core/cmdb,view_in_gui",
			"key_type" => "autoincrement",
			"name_attcode" => "",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_event",
			"db_key_field" => "id",
			"db_finalclass_field" => "realclass",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		//MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeText("message", array("allowed_values"=>null, "sql"=>"message", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeDateTime("date", array("allowed_values"=>null, "sql"=>"date", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("userinfo", array("allowed_values"=>null, "sql"=>"userinfo", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
//		MetaModel::Init_AddAttribute(new AttributeString("userinfo", array("allowed_values"=>null, "sql"=>"userinfo", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('date', 'message', 'userinfo')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('date', 'finalclass', 'message')); // Attributes to be displayed for a list
		// Search criteria
//		MetaModel::Init_SetZListItems('standard_search', array('name')); // Criteria of the std search form
//		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
	}

	/**
	 * Maps the given context parameter name to the appropriate filter/search code for this class
	 * @param string $sContextParam Name of the context parameter, i.e. 'org_id'
	 * @return string Filter code, i.e. 'customer_id'
	 */
	public static function MapContextParam($sContextParam)
	{
		if ($sContextParam == 'menu')
		{
			return null;
		}
		else
		{
			return $sContextParam;
		}
	}

	/**
	 * This function returns a 'hilight' CSS class, used to hilight a given row in a table
	 * There are currently (i.e defined in the CSS) 4 possible values HILIGHT_CLASS_CRITICAL,
	 * HILIGHT_CLASS_WARNING, HILIGHT_CLASS_OK, HILIGHT_CLASS_NONE
	 * To Be overridden by derived classes
	 * @param void
	 * @return String The desired higlight class for the object/row
	 */
	public function GetHilightClass()
	{
		// Possible return values are:
		// HILIGHT_CLASS_CRITICAL, HILIGHT_CLASS_WARNING, HILIGHT_CLASS_OK, HILIGHT_CLASS_NONE	
		return HILIGHT_CLASS_NONE; // Not hilighted by default
	}

	public static function GetUIPage()
	{
		return 'UI.php';
	}

	function DisplayDetails(WebPage $oPage, $bEditMode = false)
	{
		// Object's details
		//$this->DisplayBareHeader($oPage, $bEditMode);
		$oPage->AddTabContainer(OBJECT_PROPERTIES_TAB);
		$oPage->SetCurrentTabContainer(OBJECT_PROPERTIES_TAB);
		$oPage->SetCurrentTab(Dict::S('UI:PropertiesTab'));
		$this->DisplayBareProperties($oPage, $bEditMode);
	}
	
	function DisplayBareProperties(WebPage $oPage, $bEditMode = false, $aExtraParams = array())
	{
		if ($bEditMode) return; // Not editable
		
		$aDetails = array();
		$sClass = get_class($this);
		$aZList = MetaModel::FlattenZlist(MetaModel::GetZListItems($sClass, 'details'));
		foreach( $aZList as $sAttCode)
		{
			$sDisplayValue = $this->GetAsHTML($sAttCode);	
			$aDetails[] = array('label' => '<span title="'.MetaModel::GetDescription($sClass, $sAttCode).'">'.MetaModel::GetLabel($sClass, $sAttCode).'</span>', 'value' => $sDisplayValue);
		}
		$oPage->Details($aDetails);
	}
}

class EventNotification extends Event
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "core/cmdb,view_in_gui",
			"key_type" => "autoincrement",
			"name_attcode" => "",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_event_notification",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeExternalKey("trigger_id", array("targetclass"=>"Trigger", "jointype"=> "", "allowed_values"=>null, "sql"=>"trigger_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("action_id", array("targetclass"=>"Action", "jointype"=> "", "allowed_values"=>null, "sql"=>"action_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeInteger("object_id", array("allowed_values"=>null, "sql"=>"object_id", "default_value"=>0, "is_null_allowed"=>false, "depends_on"=>array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('date', 'message', 'userinfo', 'trigger_id', 'action_id', 'object_id')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('date', 'message')); // Attributes to be displayed for a list
		// Search criteria
//		MetaModel::Init_SetZListItems('standard_search', array('name')); // Criteria of the std search form
//		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
	}

}

class EventNotificationEmail extends EventNotification
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "core/cmdb,view_in_gui",
			"key_type" => "autoincrement",
			"name_attcode" => "",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_event_email",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeText("to", array("allowed_values"=>null, "sql"=>"to", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeText("cc", array("allowed_values"=>null, "sql"=>"cc", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeText("bcc", array("allowed_values"=>null, "sql"=>"bcc", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeText("from", array("allowed_values"=>null, "sql"=>"from", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeText("subject", array("allowed_values"=>null, "sql"=>"subject", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeText("body", array("allowed_values"=>null, "sql"=>"body", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('date', 'userinfo', 'message', 'trigger_id', 'action_id', 'object_id', 'to', 'cc', 'bcc', 'from', 'subject', 'body')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('date', 'message', 'to', 'subject')); // Attributes to be displayed for a list

		// Search criteria
//		MetaModel::Init_SetZListItems('standard_search', array('name')); // Criteria of the std search form
//		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
	}

}

class EventIssue extends Event
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "core/cmdb,view_in_gui",
			"key_type" => "autoincrement",
			"name_attcode" => "",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_event_issue",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeString("issue", array("allowed_values"=>null, "sql"=>"issue", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("impact", array("allowed_values"=>null, "sql"=>"impact", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("page", array("allowed_values"=>null, "sql"=>"page", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributePropertySet("arguments_post", array("allowed_values"=>null, "sql"=>"arguments_post", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributePropertySet("arguments_get", array("allowed_values"=>null, "sql"=>"arguments_get", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeTable("callstack", array("allowed_values"=>null, "sql"=>"callstack", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributePropertySet("data", array("allowed_values"=>null, "sql"=>"data", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('date', 'userinfo', 'issue', 'impact', 'page', 'arguments_post', 'arguments_get', 'callstack', 'data')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('date', 'userinfo', 'issue', 'impact')); // Attributes to be displayed for a list
		// Search criteria
//		MetaModel::Init_SetZListItems('standard_search', array('name')); // Criteria of the std search form
//		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
	}

	protected function OnInsert()
	{
		// Init page information: name, arguments
		//
		$this->Set('page', @$GLOBALS['_SERVER']['SCRIPT_NAME']);

		if (array_key_exists('_GET', $GLOBALS) && is_array($GLOBALS['_GET']))
		{
			$this->Set('arguments_get', $GLOBALS['_GET']);
		}
		else
		{
			$this->Set('arguments_get', array());
		}

		if (array_key_exists('_POST', $GLOBALS) && is_array($GLOBALS['_POST']))
		{
			$aPost = array();
			foreach($GLOBALS['_POST'] as $sKey => $sValue)
			{
				if (is_string($sValue))
				{
					if (strlen($sValue) < 256)
					{
						$aPost[$sKey] = $sValue;
					}
					else
					{
						$aPost[$sKey] = "!long string: ".strlen($sValue). " chars";
					}
				}
				else
				{
					// Not a string
					$aPost[$sKey] = (string) $sValue;
				}
			}
			$this->Set('arguments_post', $aPost);
		}
		else
		{
			$this->Set('arguments_post', array());
		}

		$sLength = strlen($this->Get('issue'));
		if ($sLength > 255)
		{
			$this->Set('issue', substr($this->Get('issue'), 0, 200)." -truncated ($sLength chars)");
		}

		$sLength = strlen($this->Get('impact'));
		if ($sLength > 255)
		{
			$this->Set('impact', substr($this->Get('impact'), 0, 200)." -truncated ($sLength chars)");
		}

		$sLength = strlen($this->Get('page'));
		if ($sLength > 255)
		{
			$this->Set('page', substr($this->Get('page'), 0, 200)." -truncated ($sLength chars)");
		}
	}
}


class EventWebService extends Event
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "core/cmdb,view_in_gui",
			"key_type" => "autoincrement",
			"name_attcode" => "",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_event_webservice",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeString("verb", array("allowed_values"=>null, "sql"=>"verb", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		//MetaModel::Init_AddAttribute(new AttributeStructure("arguments", array("allowed_values"=>null, "sql"=>"data", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeBoolean("result", array("allowed_values"=>null, "sql"=>"result", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeText("log_info", array("allowed_values"=>null, "sql"=>"log_info", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeText("log_warning", array("allowed_values"=>null, "sql"=>"log_warning", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeText("log_error", array("allowed_values"=>null, "sql"=>"log_error", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeText("data", array("allowed_values"=>null, "sql"=>"data", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('date', 'userinfo', 'verb', 'result', 'log_info', 'log_warning', 'log_error', 'data')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('date', 'userinfo', 'verb', 'result')); // Attributes to be displayed for a list
		// Search criteria
//		MetaModel::Init_SetZListItems('standard_search', array('name')); // Criteria of the std search form
//		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
	}
}

class EventLoginUsage extends Event
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "core/cmdb,view_in_gui",
			"key_type" => "autoincrement",
			"name_attcode" => "",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_event_loginusage",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeExternalKey("user_id", array("targetclass"=>"User", "jointype"=> "", "allowed_values"=>null, "sql"=>"user_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		if (MetaModel::IsValidAttCode('Contact', 'name'))
		{
			MetaModel::Init_AddAttribute(new AttributeExternalField("contact_name", array("allowed_values"=>null, "extkey_attcode"=>"user_id", "target_attcode"=>"contactid", "is_null_allowed"=>true, "depends_on"=>array())));
			$aZList[] = 'contact_name';
		}
		if (MetaModel::IsValidAttCode('Contact', 'email'))
		{
			MetaModel::Init_AddAttribute(new AttributeExternalField("contact_email", array("allowed_values"=>null, "extkey_attcode"=>"user_id", "target_attcode"=>"email", "is_null_allowed"=>true, "depends_on"=>array())));
			$aZList[] = 'contact_email';
		}
		// Display lists
		MetaModel::Init_SetZListItems('details', array('date', 'user_id', 'contact_name', 'contact_email', 'userinfo', 'message')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('date', 'user_id', 'contact_name', 'contact_email', 'userinfo')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('date', 'user_id', 'contact_name', 'contact_email')); // Criteria of the std search form
//		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
	}
}

?>

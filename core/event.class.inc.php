<?php

use Combodo\iTop\Application\WebPage\WebPage;

/**
 * Copyright (C) 2013-2024 Combodo SAS
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */

class Event extends DBObject implements iDisplay
{
	public static function Init()
	{
		$aParams =
		[
			"category" => "core/cmdb,view_in_gui",
			"key_type" => "autoincrement",
			"name_attcode" => "",
			"state_attcode" => "",
			"reconc_keys" => [],
			"db_table" => "priv_event",
			"db_key_field" => "id",
			"db_finalclass_field" => "realclass",
			"order_by_default" => ['date' => false],
		];
		MetaModel::Init_Params($aParams);
		//MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeText("message", ["allowed_values" => null, "sql" => "message", "default_value" => null, "is_null_allowed" => false, "depends_on" => []]));
		MetaModel::Init_AddAttribute(new AttributeDateTime("date", ["allowed_values" => null, "sql" => "date", "default_value" => "NOW()", "is_null_allowed" => false, "depends_on" => []]));
		MetaModel::Init_AddAttribute(new AttributeString("userinfo", ["allowed_values" => null, "sql" => "userinfo", "default_value" => null, "is_null_allowed" => true, "depends_on" => []]));
		//		MetaModel::Init_AddAttribute(new AttributeString("userinfo", array("allowed_values"=>null, "sql"=>"userinfo", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', ['date', 'message', 'userinfo']); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', ['date', 'finalclass', 'message']); // Attributes to be displayed for a list
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
		if ($sContextParam == 'menu') {
			return null;
		} else {
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

	public function DisplayDetails(WebPage $oPage, $bEditMode = false)
	{
		// Object's details
		//$this->DisplayBareHeader($oPage, $bEditMode);
		$oPage->AddTabContainer(OBJECT_PROPERTIES_TAB);
		$oPage->SetCurrentTabContainer(OBJECT_PROPERTIES_TAB);
		$oPage->SetCurrentTab('UI:PropertiesTab');
		$this->DisplayBareProperties($oPage, $bEditMode);
	}

	public function DisplayBareProperties(WebPage $oPage, $bEditMode = false, $sPrefix = '', $aExtraParams = [])
	{
		if ($bEditMode) {
			return [];
		} // Not editable

		$aDetails = [];
		$sClass = get_class($this);
		$aZList = MetaModel::FlattenZlist(MetaModel::GetZListItems($sClass, 'details'));
		foreach ($aZList as $sAttCode) {
			$sDisplayValue = $this->GetAsHTML($sAttCode);
			$aDetails[] = ['label' => '<span title="'.MetaModel::GetDescription($sClass, $sAttCode).'">'.MetaModel::GetLabel($sClass, $sAttCode).'</span>', 'value' => $sDisplayValue];
		}
		$oPage->Details($aDetails);

		return [];
	}
}

class EventNotification extends Event
{
	public static function Init()
	{
		$aParams =
		[
			"category" => "core/cmdb,view_in_gui",
			"key_type" => "autoincrement",
			"name_attcode" => "",
			"state_attcode" => "",
			"reconc_keys" => [],
			"db_table" => "priv_event_notification",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"order_by_default" => ['date' => false],
			'indexes' => [
				['object_id'],
			],
		];
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeExternalKey("trigger_id", ["targetclass" => "Trigger", "jointype" => "", "allowed_values" => null, "sql" => "trigger_id", "is_null_allowed" => false, "on_target_delete" => DEL_AUTO, "depends_on" => []]));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("action_id", ["targetclass" => "Action", "jointype" => "", "allowed_values" => null, "sql" => "action_id", "is_null_allowed" => false, "on_target_delete" => DEL_AUTO, "depends_on" => []]));
		MetaModel::Init_AddAttribute(new AttributeInteger("object_id", ["allowed_values" => null, "sql" => "object_id", "default_value" => 0, "is_null_allowed" => false, "depends_on" => []]));

		// Display lists
		MetaModel::Init_SetZListItems('details', ['date', 'message', 'userinfo', 'trigger_id', 'action_id', 'object_id']); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', ['date', 'message']); // Attributes to be displayed for a list
		// Search criteria
		//		MetaModel::Init_SetZListItems('standard_search', array('name')); // Criteria of the std search form
		//		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
	}
}

class EventNotificationEmail extends EventNotification
{
	public static function Init()
	{
		$aParams =
		[
			"category" => "core/cmdb,view_in_gui",
			"key_type" => "autoincrement",
			"name_attcode" => "",
			"state_attcode" => "",
			"reconc_keys" => [],
			"db_table" => "priv_event_email",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"order_by_default" => ['date' => false],
		];
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeText("to", ["allowed_values" => null, "sql" => "to", "default_value" => null, "is_null_allowed" => true, "depends_on" => []]));
		MetaModel::Init_AddAttribute(new AttributeText("cc", ["allowed_values" => null, "sql" => "cc", "default_value" => null, "is_null_allowed" => true, "depends_on" => []]));
		MetaModel::Init_AddAttribute(new AttributeText("bcc", ["allowed_values" => null, "sql" => "bcc", "default_value" => null, "is_null_allowed" => true, "depends_on" => []]));
		MetaModel::Init_AddAttribute(new AttributeText("from", ["allowed_values" => null, "sql" => "from", "default_value" => null, "is_null_allowed" => true, "depends_on" => []]));
		MetaModel::Init_AddAttribute(new AttributeText("subject", ["allowed_values" => null, "sql" => "subject", "default_value" => null, "is_null_allowed" => true, "depends_on" => []]));
		MetaModel::Init_AddAttribute(new AttributeHTML("body", ["allowed_values" => null, "sql" => "body", "default_value" => null, "is_null_allowed" => true, "depends_on" => []]));
		MetaModel::Init_AddAttribute(new AttributeTable("attachments", ["allowed_values" => null, "sql" => "attachments", "default_value" => null, "is_null_allowed" => true, "depends_on" => []]));

		// Display lists
		MetaModel::Init_SetZListItems('details', ['date', 'userinfo', 'message', 'trigger_id', 'action_id', 'object_id', 'to', 'cc', 'bcc', 'from', 'subject', 'body', 'attachments']); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', ['date', 'message', 'to', 'subject', 'attachments']); // Attributes to be displayed for a list

		// Search criteria
		//		MetaModel::Init_SetZListItems('standard_search', array('name')); // Criteria of the std search form
		//		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
	}
}

class EventIssue extends Event
{
	public static function Init()
	{
		$aParams =
		[
			"category" => "core/cmdb,view_in_gui",
			"key_type" => "autoincrement",
			"name_attcode" => "",
			"state_attcode" => "",
			"reconc_keys" => [],
			"db_table" => "priv_event_issue",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"order_by_default" => ['date' => false],
		];
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeString("issue", ["allowed_values" => null, "sql" => "issue", "default_value" => null, "is_null_allowed" => false, "depends_on" => []]));
		MetaModel::Init_AddAttribute(new AttributeString("impact", ["allowed_values" => null, "sql" => "impact", "default_value" => null, "is_null_allowed" => true, "depends_on" => []]));
		MetaModel::Init_AddAttribute(new AttributeString("page", ["allowed_values" => null, "sql" => "page", "default_value" => null, "is_null_allowed" => false, "depends_on" => []]));
		MetaModel::Init_AddAttribute(new AttributePropertySet("arguments_post", ["allowed_values" => null, "sql" => "arguments_post", "default_value" => null, "is_null_allowed" => true, "depends_on" => []]));
		MetaModel::Init_AddAttribute(new AttributePropertySet("arguments_get", ["allowed_values" => null, "sql" => "arguments_get", "default_value" => null, "is_null_allowed" => true, "depends_on" => []]));
		MetaModel::Init_AddAttribute(new AttributeTable("callstack", ["allowed_values" => null, "sql" => "callstack", "default_value" => null, "is_null_allowed" => true, "depends_on" => []]));
		MetaModel::Init_AddAttribute(new AttributePropertySet("data", ["allowed_values" => null, "sql" => "data", "default_value" => null, "is_null_allowed" => true, "depends_on" => []]));

		// Display lists
		MetaModel::Init_SetZListItems('details', ['date', 'message', 'userinfo', 'issue', 'impact', 'page', 'arguments_post', 'arguments_get', 'callstack', 'data']); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', ['date', 'userinfo', 'issue', 'impact']); // Attributes to be displayed for a list
		// Search criteria
		//		MetaModel::Init_SetZListItems('standard_search', array('name')); // Criteria of the std search form
		//		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
	}

	protected function OnInsert()
	{
		// Init page information: name, arguments
		//
		$this->Set('page', @$GLOBALS['_SERVER']['SCRIPT_NAME']);

		if (strlen($this->Get('userinfo')) == 0) {
			$this->Set('userinfo', UserRights::GetUserId());
		}

		if (array_key_exists('_GET', $GLOBALS) && is_array($GLOBALS['_GET'])) {
			$this->Set('arguments_get', $this->SanitizeRequestParams($GLOBALS['_GET']));
		} else {
			$this->Set('arguments_get', []);
		}

		if (array_key_exists('_POST', $GLOBALS) && is_array($GLOBALS['_POST'])) {
			$this->Set('arguments_post', $this->SanitizeRequestParams($GLOBALS['_POST']));
		} else {
			$this->Set('arguments_post', []);
		}
		$sLength = mb_strlen($this->Get('issue'));
		if ($sLength > 255) {
			$this->Set('issue', mb_substr($this->Get('issue'), 0, 210)." -truncated ($sLength chars)");
		}

		$sLength = mb_strlen($this->Get('impact'));
		if ($sLength > 255) {
			$this->Set('impact', mb_substr($this->Get('impact'), 0, 210)." -truncated ($sLength chars)");
		}

		$sLength = mb_strlen($this->Get('page'));
		if ($sLength > 255) {
			$this->Set('page', mb_substr($this->Get('page'), 0, 210)." -truncated ($sLength chars)");
		}
	}

	protected function SanitizeRequestParams(array $aParams): array
	{
		$aSanitizedParams = [];

		foreach ($aParams as $sKey => $sValue) {
			if (is_string($sValue)) {
				if (stristr($sKey, 'pwd') !== false || stristr($sKey, 'passwd') !== false || stristr($sKey, 'password') !== false) {
					$aSanitizedParams[$sKey] = '****';
				} elseif (mb_strlen($sValue) < 256) {
					$aSanitizedParams[$sKey] = $sValue;
				} else {
					$aSanitizedParams[$sKey] = '!long string: '.mb_strlen($sValue).' chars';
				}
			} else {
				// Not a string (avoid warnings in case the value cannot be easily cast into a string)
				$aSanitizedParams[$sKey] = @(string)$sValue;
			}
		}

		return $aSanitizedParams;
	}
}

class EventWebService extends Event
{
	public static function Init()
	{
		$aParams =
		[
			"category" => "core/cmdb,view_in_gui",
			"key_type" => "autoincrement",
			"name_attcode" => "",
			"state_attcode" => "",
			"reconc_keys" => [],
			"db_table" => "priv_event_webservice",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"order_by_default" => ['date' => false],
		];
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeString("verb", ["allowed_values" => null, "sql" => "verb", "default_value" => null, "is_null_allowed" => false, "depends_on" => []]));
		//MetaModel::Init_AddAttribute(new AttributeStructure("arguments", array("allowed_values"=>null, "sql"=>"data", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeBoolean("result", ["allowed_values" => null, "sql" => "result", "default_value" => null, "is_null_allowed" => true, "depends_on" => []]));
		MetaModel::Init_AddAttribute(new AttributeText("log_info", ["allowed_values" => null, "sql" => "log_info", "default_value" => null, "is_null_allowed" => true, "depends_on" => []]));
		MetaModel::Init_AddAttribute(new AttributeText("log_warning", ["allowed_values" => null, "sql" => "log_warning", "default_value" => null, "is_null_allowed" => true, "depends_on" => []]));
		MetaModel::Init_AddAttribute(new AttributeText("log_error", ["allowed_values" => null, "sql" => "log_error", "default_value" => null, "is_null_allowed" => true, "depends_on" => []]));
		MetaModel::Init_AddAttribute(new AttributeText("data", ["allowed_values" => null, "sql" => "data", "default_value" => null, "is_null_allowed" => true, "depends_on" => []]));

		// Display lists
		MetaModel::Init_SetZListItems('details', ['date', 'userinfo', 'verb', 'result', 'log_info', 'log_warning', 'log_error', 'data']); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', ['date', 'userinfo', 'verb', 'result']); // Attributes to be displayed for a list
		// Search criteria
		//		MetaModel::Init_SetZListItems('standard_search', array('name')); // Criteria of the std search form
		//		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
	}
}

class EventRestService extends Event
{
	public static function Init()
	{
		$aParams =
		[
			"category" => "core/cmdb,view_in_gui",
			"key_type" => "autoincrement",
			"name_attcode" => "",
			"state_attcode" => "",
			"reconc_keys" => [],
			"db_table" => "priv_event_restservice",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"order_by_default" => ['date' => false],
		];
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeString("operation", ["allowed_values" => null, "sql" => "operation", "default_value" => null, "is_null_allowed" => true, "depends_on" => []]));
		MetaModel::Init_AddAttribute(new AttributeString("version", ["allowed_values" => null, "sql" => "version", "default_value" => null, "is_null_allowed" => true, "depends_on" => []]));
		MetaModel::Init_AddAttribute(new AttributeText("json_input", ["allowed_values" => null, "sql" => "json_input", "default_value" => null, "is_null_allowed" => true, "depends_on" => []]));

		MetaModel::Init_AddAttribute(new AttributeInteger("code", ["allowed_values" => null, "sql" => "code", "default_value" => 0, "is_null_allowed" => false, "depends_on" => []]));
		MetaModel::Init_AddAttribute(new AttributeText("json_output", ["allowed_values" => null, "sql" => "json_output", "default_value" => null, "is_null_allowed" => true, "depends_on" => []]));
		MetaModel::Init_AddAttribute(new AttributeString("provider", ["allowed_values" => null, "sql" => "provider", "default_value" => null, "is_null_allowed" => true, "depends_on" => []]));

		// Display lists
		MetaModel::Init_SetZListItems('details', ['date', 'userinfo', 'operation', 'version', 'json_input', 'message', 'code', 'json_output', 'provider']); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', ['date', 'userinfo', 'operation', 'message']); // Attributes to be displayed for a list
		// Search criteria
		//		MetaModel::Init_SetZListItems('standard_search', array('name')); // Criteria of the std search form
		//		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
	}
}

class EventLoginUsage extends Event
{
	public static function Init()
	{
		$aParams =
		[
			"category" => "core/cmdb,view_in_gui",
			"key_type" => "autoincrement",
			"name_attcode" => "",
			"state_attcode" => "",
			"reconc_keys" => [],
			"db_table" => "priv_event_loginusage",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"order_by_default" => ['date' => false],
		];
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeExternalKey("user_id", ["targetclass" => "User", "jointype" => "", "allowed_values" => null, "sql" => "user_id", "is_null_allowed" => false, "on_target_delete" => DEL_SILENT, "depends_on" => []]));
		$aZList = ['date', 'user_id'];
		if (MetaModel::IsValidAttCode('Contact', 'name')) {
			MetaModel::Init_AddAttribute(new AttributeExternalField("contact_name", ["allowed_values" => null, "extkey_attcode" => "user_id", "target_attcode" => "contactid", "is_null_allowed" => true, "depends_on" => []]));
			$aZList[] = 'contact_name';
		}
		if (MetaModel::IsValidAttCode('Contact', 'email')) {
			MetaModel::Init_AddAttribute(new AttributeExternalField("contact_email", ["allowed_values" => null, "extkey_attcode" => "user_id", "target_attcode" => "email", "is_null_allowed" => true, "depends_on" => []]));
			$aZList[] = 'contact_email';
		}
		// Display lists
		MetaModel::Init_SetZListItems('details', array_merge($aZList, ['userinfo', 'message'])); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array_merge($aZList, ['userinfo'])); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', $aZList); // Criteria of the std search form
		//		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
	}
}

class EventOnObject extends Event
{
	public static function Init()
	{
		$aParams =
		[
			"category" => "core/cmdb,view_in_gui",
			"key_type" => "autoincrement",
			"name_attcode" => "",
			"state_attcode" => "",
			"reconc_keys" => [],
			"db_table" => "priv_event_onobject",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
			"order_by_default" => ['date' => false],
		];
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeString("obj_class", ["allowed_values" => null, "sql" => "obj_class", "default_value" => null, "is_null_allowed" => false, "depends_on" => []]));
		MetaModel::Init_AddAttribute(new AttributeInteger("obj_key", ["allowed_values" => null, "sql" => "obj_key", "default_value" => null, "is_null_allowed" => false, "depends_on" => []]));

		// Display lists
		MetaModel::Init_SetZListItems('details', ['date', 'userinfo', 'obj_class', 'obj_key', 'message']); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', ['date', 'userinfo', 'obj_class', 'obj_key', 'message']); // Attributes to be displayed for a list
	}
}

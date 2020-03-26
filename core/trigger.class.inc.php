<?php
// Copyright (C) 2010-2018 Combodo SARL
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
 * Persistent class Trigger and derived
 * User defined triggers, that may be used in conjunction with user defined actions
 *
 * @copyright   Copyright (C) 2010-2018 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


/**
 * A user defined trigger, to customize the application
 * A trigger will activate an action
 *
 * @package     iTopORM
 */
abstract class Trigger extends cmdbAbstractObject
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
			"name_attcode" => "description",
			"state_attcode" => "",
			"reconc_keys" => array('description'),
			"db_table" => "priv_trigger",
			"db_key_field" => "id",
			"db_finalclass_field" => "realclass",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		//MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeString("description", array("allowed_values" => null, "sql" => "description", "default_value" => null, "is_null_allowed" => false, "depends_on" => array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("action_list", array("linked_class" => "lnkTriggerAction", "ext_key_to_me" => "trigger_id", "ext_key_to_remote" => "action_id", "allowed_values" => null, "count_min" => 1, "count_max" => 0, "depends_on" => array())));
		$aTags = ContextTag::GetTags();
		MetaModel::Init_AddAttribute( new AttributeEnumSet("context", array("allowed_values" => null, "possible_values" => new ValueSetEnumPadded($aTags), "sql" => "context", "depends_on" => array(), "is_null_allowed" => true, "max_items" => 12)));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('finalclass', 'description', 'context', 'action_list')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('finalclass')); // Attributes to be displayed for a list
		// Search criteria
		//		MetaModel::Init_SetZListItems('standard_search', array('name')); // Criteria of the std search form
		//		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
	}

	/**
	 * Check if the trigger can be used in the current context
	 *
	 * @return bool true if context OK
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 */
	public function IsContextValid()
	{
		// Check the context
		$oContext = $this->Get('context');
		$bChecked = false;
		$bValid = false;
		foreach ($oContext->GetValues() as $sValue)
		{
			$bChecked = true;
			if (ContextTag::Check($sValue))
			{
				$bValid = true;
				break;
			}
		}
		if ($bChecked && !$bValid)
		{
			// Trigger does not match the current context
			return false;
		}

		return true;
	}

	/**
	 * @param $aContextArgs
	 *
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 */
	public function DoActivate($aContextArgs)
	{
		// Check the context
		if (!$this->IsContextValid())
		{
			// Trigger does not match the current context
			$sClass = get_class($this);
			$sName = $this->Get('friendlyname');
			IssueLog::Debug("Context NOT valid for : {$sClass} '$sName'");
			return;
		}

		// Find the related actions
		$oLinkedActions = $this->Get('action_list');
		while ($oLink = $oLinkedActions->Fetch())
		{
			/** @var \DBObject $oLink */
			$iActionId = $oLink->Get('action_id');
			/** @var \Action $oAction */
			$oAction = MetaModel::GetObject('Action', $iActionId);
			if ($oAction->IsActive())
			{
				$oAction->DoExecute($this, $aContextArgs);
			}
		}
	}

	/**
	 * Check whether the given object is in the scope of this trigger
	 * and can potentially be the subject of notifications
	 *
	 * @param DBObject $oObject The object to check
	 *
	 * @return bool
	 */
	public function IsInScope(DBObject $oObject)
	{
		// By default the answer is no
		// Overload this function in your own derived class for a different behavior
		return false;
	}
}

/**
 * Class TriggerOnObject
 */
abstract class TriggerOnObject extends Trigger
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
			"name_attcode" => "description",
			"state_attcode" => "",
			"reconc_keys" => array('description'),
			"db_table" => "priv_trigger_onobject",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeClass("target_class", array("class_category" => "bizmodel", "more_values" => "User,UserExternal,UserInternal,UserLDAP,UserLocal", "sql" => "target_class", "default_value" => null, "is_null_allowed" => false, "depends_on" => array())));
		MetaModel::Init_AddAttribute(new AttributeOQL("filter", array("allowed_values" => null, "sql" => "filter", "default_value" => null, "is_null_allowed" => true, "depends_on" => array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('description', 'context', 'target_class', 'filter', 'action_list')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('finalclass', 'target_class', 'description')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('default_search', array('description', 'target_class'));  // Default criteria of the search banner
		//		MetaModel::Init_SetZListItems('standard_search', array('name', 'target_class', 'description')); // Criteria of the search form
	}

	/**
	 * @throws \CoreException
	 */
	public function DoCheckToWrite()
	{
		parent::DoCheckToWrite();

		$sFilter = trim($this->Get('filter'));
		if (strlen($sFilter) > 0)
		{
			try
			{
				$oSearch = DBObjectSearch::FromOQL($sFilter);

				if (!MetaModel::IsParentClass($this->Get('target_class'), $oSearch->GetClass()))
				{
					$this->m_aCheckIssues[] = Dict::Format('TriggerOnObject:WrongFilterClass', $this->Get('target_class'));
				}
			} catch (OqlException $e)
			{
				$this->m_aCheckIssues[] = Dict::Format('TriggerOnObject:WrongFilterQuery', $e->getMessage());
			}
		}
	}

	/**
	 * Check whether the given object is in the scope of this trigger
	 * and can potentially be the subject of notifications
	 *
	 * @param DBObject $oObject The object to check
	 *
	 * @return bool
	 * @throws \CoreException
	 */
	public function IsInScope(DBObject $oObject)
	{
		$sRootClass = $this->Get('target_class');

		return ($oObject instanceof $sRootClass);
	}

	/**
	 * @param $aContextArgs
	 *
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 */
	public function DoActivate($aContextArgs)
	{
		$bGo = true;
		if (isset($aContextArgs['this->object()']))
		{
			/** @var \DBObject $oObject */
			$oObject = $aContextArgs['this->object()'];
			$bGo = $this->IsTargetObject($oObject->GetKey(), $oObject->ListChanges());
		}
		if ($bGo)
		{
			parent::DoActivate($aContextArgs);
		}
	}

	/**
	 * @param $iObjectId
	 * @param array $aChanges
	 *
	 * @return bool
	 * @throws \CoreException
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 */
	public function IsTargetObject($iObjectId, $aChanges = array())
	{
		$sFilter = trim($this->Get('filter'));
		if (strlen($sFilter) > 0)
		{
			$oSearch = DBObjectSearch::FromOQL($sFilter);
			$oSearch->AddCondition('id', $iObjectId, '=');
			$oSet = new DBObjectSet($oSearch);
			$bRet = ($oSet->Count() > 0);
		}
		else
		{
			$bRet = true;
		}

		return $bRet;
	}
}

/**
 * To trigger notifications when a ticket is updated from the portal
 */
class TriggerOnPortalUpdate extends TriggerOnObject
{
	/**
	 * @throws \CoreException
	 */
	public static function Init()
	{
		$aParams = array
		(
			"category" => "grant_by_profile,core/cmdb,application",
			"key_type" => "autoincrement",
			"name_attcode" => "description",
			"state_attcode" => "",
			"reconc_keys" => array('description'),
			"db_table" => "priv_trigger_onportalupdate",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		// Display lists
		MetaModel::Init_SetZListItems('details', array('description', 'context', 'target_class', 'filter', 'action_list')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('finalclass', 'target_class', 'description')); // Attributes to be displayed for a list
		// Search criteria
	}
}

/**
 * Class TriggerOnStateChange
 */
abstract class TriggerOnStateChange extends TriggerOnObject
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
			"name_attcode" => "description",
			"state_attcode" => "",
			"reconc_keys" => array('description'),
			"db_table" => "priv_trigger_onstatechange",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeClassState("state", array("class_field" => 'target_class', "allowed_values" => null, "sql" => "state", "default_value" => null, "is_null_allowed" => false, "depends_on" => array('target_class'))));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('description', 'context', 'target_class', 'filter', 'state', 'action_list')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('finalclass', 'target_class', 'state')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('description', 'target_class', 'state')); // Criteria of the std search form
		//		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
	}
}

/**
 * Class TriggerOnStateEnter
 */
class TriggerOnStateEnter extends TriggerOnStateChange
{
	/**
	 * @throws \CoreException
	 */
	public static function Init()
	{
		$aParams = array
		(
			"category" => "grant_by_profile,core/cmdb,application",
			"key_type" => "autoincrement",
			"name_attcode" => "description",
			"state_attcode" => "",
			"reconc_keys" => array('description'),
			"db_table" => "priv_trigger_onstateenter",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		// Display lists
		MetaModel::Init_SetZListItems('details', array('description', 'context', 'target_class', 'filter', 'state', 'action_list')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('target_class', 'state')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('description', 'target_class', 'state')); // Criteria of the std search form
		//		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
	}
}

/**
 * Class TriggerOnStateLeave
 */
class TriggerOnStateLeave extends TriggerOnStateChange
{
	/**
	 * @throws \CoreException
	 */
	public static function Init()
	{
		$aParams = array
		(
			"category" => "grant_by_profile,core/cmdb,application",
			"key_type" => "autoincrement",
			"name_attcode" => "description",
			"state_attcode" => "",
			"reconc_keys" => array('description'),
			"db_table" => "priv_trigger_onstateleave",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		// Display lists
		MetaModel::Init_SetZListItems('details', array('description', 'context', 'target_class', 'filter', 'state', 'action_list')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('target_class', 'state')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('description', 'target_class', 'state')); // Criteria of the std search form
		//		MetaModel::Init_SetZListItems('advanced_search', array('')); // Criteria of the advanced search form
	}
}

/**
 * Class TriggerOnObjectCreate
 */
class TriggerOnObjectCreate extends TriggerOnObject
{
	/**
	 * @throws \CoreException
	 */
	public static function Init()
	{
		$aParams = array
		(
			"category" => "grant_by_profile,core/cmdb,application",
			"key_type" => "autoincrement",
			"name_attcode" => "description",
			"state_attcode" => "",
			"reconc_keys" => array('description'),
			"db_table" => "priv_trigger_onobjcreate",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		// Display lists
		MetaModel::Init_SetZListItems('details', array('description', 'context', 'target_class', 'filter', 'action_list')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('finalclass', 'target_class')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('description', 'target_class')); // Criteria of the std search form
		//		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
	}
}

/**
 * Class TriggerOnObjectCreate
 */
class TriggerOnObjectDelete extends TriggerOnObject
{
	/**
	 * @throws \CoreException
	 */
	public static function Init()
	{
		$aParams = array
		(
			"category" => "grant_by_profile,core/cmdb,application",
			"key_type" => "autoincrement",
			"name_attcode" => "description",
			"state_attcode" => "",
			"reconc_keys" => array('description'),
			"db_table" => "priv_trigger_onobjdelete",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		// Display lists
		MetaModel::Init_SetZListItems('details', array('description', 'context', 'target_class', 'filter', 'action_list')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('finalclass', 'target_class')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('description', 'target_class')); // Criteria of the std search form
		//		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
	}
}

/**
 * Class TriggerOnObjectCreate
 */
class TriggerOnObjectUpdate extends TriggerOnObject
{
	/**
	 * @throws \CoreException
	 * @throws \Exception
	 */
	public static function Init()
	{
		$aParams = array
		(
			"category" => "grant_by_profile,core/cmdb,application",
			"key_type" => "autoincrement",
			"name_attcode" => "description",
			"state_attcode" => "",
			"reconc_keys" => array('description'),
			"db_table" => "priv_trigger_onobjupdate",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeClassAttCodeSet('target_attcodes', array("allowed_values" => null, "class_field" => "target_class", "sql" => "target_attcodes", "default_value" => null, "is_null_allowed" => true, "max_items" => 20, "min_items" => 0, "attribute_definition_exclusion_list" => "AttributeDashboard,AttributeExternalField,AttributeFinalClass,AttributeFriendlyName,AttributeObsolescenceDate,AttributeObsolescenceFlag,AttributeSubItem", "attribute_definition_list" => null, "depends_on" => array('target_class'))));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('description', 'context', 'target_class', 'filter', 'target_attcodes', 'action_list')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('finalclass', 'target_class')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('description', 'target_class')); // Criteria of the std search form
	}

	public function IsTargetObject($iObjectId, $aChanges = array())
	{
		if (!parent::IsTargetObject($iObjectId, $aChanges))
		{
			return false;
		}

		// Check the attribute
		$oAttCodeSet = $this->Get('target_attcodes');
		$aAttCodes = $oAttCodeSet->GetValues();
		if (empty($aAttCodes))
		{
			return true;
		}

		foreach($aAttCodes as $sAttCode)
		{
			if (array_key_exists($sAttCode, $aChanges))
			{
				return true;
			}
		}
		return false;
	}

	public function ComputeValues()
	{
		parent::ComputeValues();

		// Remove unwanted attribute codes
		$aChanges = $this->ListChanges();
		if (isset($aChanges['target_attcodes']))
		{
			$oAttDef = MetaModel::GetAttributeDef(get_class($this), 'target_attcodes');
			$aArgs = array('this' => $this);
			$aAllowedValues = $oAttDef->GetAllowedValues($aArgs);

			/** @var \ormSet $oValue */
			$oValue = $this->Get('target_attcodes');
			$aValues = $oValue->GetValues();
			$bChanged = false;
			foreach($aValues as $key => $sValue)
			{
				if (!isset($aAllowedValues[$sValue]))
				{
					unset($aValues[$key]);
					$bChanged = true;
				}
			}
			if ($bChanged)
			{
				$oValue->SetValues($aValues);
				$this->Set('target_attcodes', $oValue);
			}
		}
	}

}

/**
 * Class lnkTriggerAction
 */
class lnkTriggerAction extends cmdbAbstractObject
{
	/**
	 * @throws \CoreException
	 * @throws \Exception
	 */
	public static function Init()
	{
		$aParams = array
		(
			"category" => "grant_by_profile,core/cmdb,application",
			"key_type" => "autoincrement",
			"name_attcode" => "",
			"state_attcode" => "",
			"reconc_keys" => array('action_id', 'trigger_id'),
			"db_table" => "priv_link_action_trigger",
			"db_key_field" => "link_id",
			"db_finalclass_field" => "",
			"display_template" => "",
			"is_link" => true,
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_AddAttribute(new AttributeExternalKey("action_id", array("targetclass" => "Action", "jointype" => '', "allowed_values" => null, "sql" => "action_id", "is_null_allowed" => false, "on_target_delete" => DEL_AUTO, "depends_on" => array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("action_name", array("allowed_values" => null, "extkey_attcode" => 'action_id', "target_attcode" => "name")));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("trigger_id", array("targetclass" => "Trigger", "jointype" => '', "allowed_values" => null, "sql" => "trigger_id", "is_null_allowed" => false, "on_target_delete" => DEL_AUTO, "depends_on" => array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("trigger_name", array("allowed_values" => null, "extkey_attcode" => 'trigger_id', "target_attcode" => "description")));
		MetaModel::Init_AddAttribute(new AttributeInteger("order", array("allowed_values" => null, "sql" => "order", "default_value" => 0, "is_null_allowed" => true, "depends_on" => array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('action_id', 'trigger_id', 'order')); // Attributes to be displayed for a list
		MetaModel::Init_SetZListItems('list', array('action_id', 'trigger_id', 'order')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('action_id', 'trigger_id', 'order')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('action_id', 'trigger_id', 'order')); // Criteria of the advanced search form
	}
}

/**
 * Class TriggerOnThresholdReached
 */
class TriggerOnThresholdReached extends TriggerOnObject
{
	/**
	 * @throws \CoreException
	 * @throws \Exception
	 */
	public static function Init()
	{
		$aParams = array
		(
			"category" => "grant_by_profile,core/cmdb,application",
			"key_type" => "autoincrement",
			"name_attcode" => "description",
			"state_attcode" => "",
			"reconc_keys" => array('description'),
			"db_table" => "priv_trigger_threshold",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeClassAttCodeSet('stop_watch_code', array("allowed_values" => null, "class_field" => "target_class", "sql" => "stop_watch_code", "default_value" => null, "is_null_allowed" => false, "max_items" => 1, "min_items" => 1, "attribute_definition_exclusion_list" => null, "attribute_definition_list" => "AttributeStopWatch", "include_child_classes_attributes" => true, "depends_on" => array('target_class'))));
		MetaModel::Init_AddAttribute(new AttributeString("threshold_index", array("allowed_values" => null, "sql" => "threshold_index", "default_value" => null, "is_null_allowed" => false, "depends_on" => array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('description', 'context', 'target_class', 'stop_watch_code', 'threshold_index', 'filter', 'action_list')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('target_class', 'threshold_index', 'threshold_index')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('description', 'target_class')); // Criteria of the std search form
		//		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
	}
}

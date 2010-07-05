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
 * Persistent classes for a CMDB
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */



abstract class Change extends Ticket
{

	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,changemgmt",
			"key_type" => "autoincrement",
			"name_attcode" => "ref",
			"state_attcode" => "status",
			"reconc_keys" => array("ref"),
			"db_table" => "change",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeEnum("status", array("allowed_values"=>new ValueSetEnum('approved,assigned,closed,implemented,monitored,new,notapproved,plannedscheduled,rejected,validated'), "sql"=>"status", "default_value"=>"new", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("reason", array("allowed_values"=>null, "sql"=>"reason", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("requestor_id", array("targetclass"=>"Person", "jointype"=>null, "allowed_values"=>null, "sql"=>"requestor_id", "is_null_allowed"=>true, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("requestor_email", array("allowed_values"=>null, "extkey_attcode"=>"requestor_id", "target_attcode"=>"email", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("customer_id", array("targetclass"=>"Organization", "jointype"=>null, "allowed_values"=>null, "sql"=>"customer_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("customer_name", array("allowed_values"=>null, "extkey_attcode"=>"customer_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("workgroup_id", array("targetclass"=>"Team", "jointype"=>null, "allowed_values"=>null, "sql"=>"workgroup_id", "is_null_allowed"=>true, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("workgroup_name", array("allowed_values"=>null, "extkey_attcode"=>"workgroup_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeDate("creation_date", array("allowed_values"=>null, "sql"=>"creation_date", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeDate("last_update", array("allowed_values"=>null, "sql"=>"last_update", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeDate("start_date", array("allowed_values"=>null, "sql"=>"start_date", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeDate("end_date", array("allowed_values"=>null, "sql"=>"end_date", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeDate("close_date", array("allowed_values"=>null, "sql"=>"close_date", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("impact", array("allowed_values"=>null, "sql"=>"impact", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("agent_id", array("targetclass"=>"Person", "jointype"=>null, "allowed_values"=>null, "sql"=>"agent_id", "is_null_allowed"=>true, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("agent_name", array("allowed_values"=>null, "extkey_attcode"=>"agent_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("agent_email", array("allowed_values"=>null, "extkey_attcode"=>"agent_id", "target_attcode"=>"email", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("supervisor_group_id", array("targetclass"=>"Team", "jointype"=>null, "allowed_values"=>null, "sql"=>"supervisor_group_id", "is_null_allowed"=>true, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("supervisor_group_name", array("allowed_values"=>null, "extkey_attcode"=>"supervisor_group_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("supervisor_id", array("targetclass"=>"Person", "jointype"=>null, "allowed_values"=>null, "sql"=>"supervisor_id", "is_null_allowed"=>true, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("supervisor_email", array("allowed_values"=>null, "extkey_attcode"=>"supervisor_id", "target_attcode"=>"email", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("manager_group_id", array("targetclass"=>"Team", "jointype"=>null, "allowed_values"=>null, "sql"=>"manager_group_id", "is_null_allowed"=>true, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("manager_group_name", array("allowed_values"=>null, "extkey_attcode"=>"manager_group_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("manager_id", array("targetclass"=>"Person", "jointype"=>null, "allowed_values"=>null, "sql"=>"manager_id", "is_null_allowed"=>true, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("manager_email", array("allowed_values"=>null, "extkey_attcode"=>"manager_id", "target_attcode"=>"email", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeBoolean("outage", array("allowed_values"=>null, "sql"=>"outage", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeText("change_request", array("allowed_values"=>null, "sql"=>"change_request", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeText("change_log", array("allowed_values"=>null, "sql"=>"change_log", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeText("fallback", array("allowed_values"=>null, "sql"=>"fallback", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('title', 'ticket_log', 'start_date', 'document_list', 'ci_list', 'contact_list', 'status', 'reason', 'requestor_id', 'customer_id', 'workgroup_id', 'creation_date', 'last_update', 'end_date', 'close_date', 'impact', 'agent_id', 'agent_email', 'supervisor_group_id', 'supervisor_id', 'manager_group_id', 'manager_id', 'outage', 'change_request', 'change_log', 'fallback'));
		MetaModel::Init_SetZListItems('advanced_search', array('ref', 'title', 'start_date', 'status', 'reason', 'requestor_id', 'customer_id', 'workgroup_id', 'creation_date', 'last_update', 'end_date', 'close_date', 'impact', 'agent_id', 'agent_email', 'supervisor_group_id', 'supervisor_id', 'manager_group_id', 'manager_id', 'outage', 'change_request', 'change_log', 'fallback'));
		MetaModel::Init_SetZListItems('standard_search', array('ref', 'title', 'start_date', 'status', 'reason', 'requestor_id', 'customer_id', 'workgroup_id', 'creation_date', 'last_update', 'end_date', 'close_date', 'impact', 'agent_id', 'agent_email', 'supervisor_group_id', 'supervisor_id', 'manager_group_id', 'manager_id', 'outage', 'change_request', 'change_log', 'fallback'));
		MetaModel::Init_SetZListItems('list', array('title', 'start_date', 'status'));


		MetaModel::Init_DefineState(
			"new",
			array(
				"attribute_inherit" => null,
				"attribute_list" => array(
					'ref' => OPT_ATT_READONLY,
					'title' => OPT_ATT_MANDATORY,
					'reason' => OPT_ATT_MANDATORY,
					'workgroup_id' => OPT_ATT_HIDDEN,
					'creation_date' => OPT_ATT_READONLY,
					'last_update' => OPT_ATT_READONLY,
					'end_date' => OPT_ATT_HIDDEN,
					'close_date' => OPT_ATT_HIDDEN,
					'agent_id' => OPT_ATT_HIDDEN,
					'supervisor_group_id' => OPT_ATT_HIDDEN,
					'supervisor_id' => OPT_ATT_HIDDEN,
					'manager_group_id' => OPT_ATT_HIDDEN,
					'manager_id' => OPT_ATT_HIDDEN,
				),
			)
		);
		MetaModel::Init_DefineState(
			"validated",
			array(
				"attribute_inherit" => 'new',
				"attribute_list" => array(
					'title' => OPT_ATT_READONLY,
					'reason' => OPT_ATT_READONLY,
					'workgroup_id' => OPT_ATT_MUSTCHANGE,
					'change_request' => OPT_ATT_READONLY,
				),
			)
		);
		MetaModel::Init_DefineState(
			"rejected",
			array(
				"attribute_inherit" => 'new',
				"attribute_list" => array(
				),
			)
		);
		MetaModel::Init_DefineState(
			"assigned",
			array(
				"attribute_inherit" => 'validated',
				"attribute_list" => array(
					'workgroup_id' => OPT_ATT_MANDATORY,
					'agent_id' => OPT_ATT_MUSTCHANGE,
					'supervisor_group_id' => OPT_ATT_MUSTCHANGE,
					'supervisor_id' => OPT_ATT_MUSTCHANGE,
					'manager_group_id' => OPT_ATT_MUSTCHANGE,
					'manager_id' => OPT_ATT_MUSTCHANGE,
				),
			)
		);
		MetaModel::Init_DefineState(
			"plannedscheduled",
			array(
				"attribute_inherit" => 'assigned',
				"attribute_list" => array(
					'requestor_id' => OPT_ATT_READONLY,
					'customer_id' => OPT_ATT_READONLY,
					'workgroup_id' => OPT_ATT_READONLY,
					'start_date' => OPT_ATT_MANDATORY,
					'end_date' => OPT_ATT_MANDATORY,
					'impact' => OPT_ATT_MANDATORY,
					'agent_id' => OPT_ATT_MANDATORY,
					'supervisor_group_id' => OPT_ATT_MANDATORY,
					'supervisor_id' => OPT_ATT_MANDATORY,
					'manager_group_id' => OPT_ATT_MANDATORY,
					'manager_id' => OPT_ATT_MANDATORY,
					'fallback' => OPT_ATT_MANDATORY,
				),
			)
		);
		MetaModel::Init_DefineState(
			"approved",
			array(
				"attribute_inherit" => 'plannedscheduled',
				"attribute_list" => array(
					'start_date' => OPT_ATT_READONLY,
					'end_date' => OPT_ATT_MANDATORY,
					'impact' => OPT_ATT_READONLY,
					'supervisor_group_id' => OPT_ATT_READONLY,
					'supervisor_id' => OPT_ATT_READONLY,
					'manager_group_id' => OPT_ATT_READONLY,
					'manager_id' => OPT_ATT_READONLY,
					'outage' => OPT_ATT_READONLY,
				),
			)
		);
		MetaModel::Init_DefineState(
			"notapproved",
			array(
				"attribute_inherit" => 'plannedscheduled',
				"attribute_list" => array(
					'impact' => OPT_ATT_READONLY,
					'supervisor_group_id' => OPT_ATT_READONLY,
					'supervisor_id' => OPT_ATT_READONLY,
					'manager_group_id' => OPT_ATT_READONLY,
					'manager_id' => OPT_ATT_READONLY,
				),
			)
		);
		MetaModel::Init_DefineState(
			"implemented",
			array(
				"attribute_inherit" => 'approved',
				"attribute_list" => array(
				),
			)
		);
		MetaModel::Init_DefineState(
			"monitored",
			array(
				"attribute_inherit" => 'implemented',
				"attribute_list" => array(
					'end_date' => OPT_ATT_READONLY,
					'agent_id' => OPT_ATT_READONLY,
					'change_request' => OPT_ATT_READONLY,
					'fallback' => OPT_ATT_READONLY,
				),
			)
		);
		MetaModel::Init_DefineState(
			"closed",
			array(
				"attribute_inherit" => 'implemented',
				"attribute_list" => array(
					'close_date' => OPT_ATT_READONLY,
				),
			)
		);

		MetaModel::Init_DefineStimulus(new StimulusUserAction("ev_validate", array()));
		MetaModel::Init_DefineStimulus(new StimulusUserAction("ev_reject", array()));
		MetaModel::Init_DefineStimulus(new StimulusUserAction("ev_assign", array()));

		MetaModel::Init_DefineStimulus(new StimulusUserAction("ev_reopen", array()));
		MetaModel::Init_DefineStimulus(new StimulusUserAction("ev_plan", array()));
	  	MetaModel::Init_DefineStimulus(new StimulusUserAction("ev_approve", array()));
		MetaModel::Init_DefineStimulus(new StimulusUserAction("ev_replan", array()));
		MetaModel::Init_DefineStimulus(new StimulusUserAction("ev_notapprove", array()));
		MetaModel::Init_DefineStimulus(new StimulusUserAction("ev_implement", array()));
		MetaModel::Init_DefineStimulus(new StimulusUserAction("ev_monitor", array()));
		MetaModel::Init_DefineStimulus(new StimulusUserAction("ev_finish", array()));

	}

	public function SetClosureDate($sStimulusCode)
	{
		$this->Set('end_date', time());
		return true;
	}
	public function SetLastUpDate($sStimulusCode)
	{
		$this->Set('last_update', time());
		return true;
	}

	public function ComputeValues()
	{
		$iKey = $this->GetKey();
		if ($iKey < 0)
		{
			// Object not yet in the Database
			$iKey = MetaModel::GetNextKey(get_class($this));
		}
		$sName = sprintf('C-%06d', $iKey);
		$this->Set('ref', $sName);
	}
}

class RoutineChange extends Change
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,changemgmt",
			"key_type" => "autoincrement",
			"name_attcode" => "ref",
			"state_attcode" => "",
			"reconc_keys" => array("ref"),
			"db_table" => "change_routine",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_InheritLifecycle();

		MetaModel::Init_SetZListItems('details', array('title', 'ticket_log', 'start_date', 'document_list', 'ci_list', 'contact_list', 'status', 'reason', 'requestor_id', 'customer_id', 'workgroup_id', 'creation_date', 'last_update', 'end_date', 'close_date', 'impact', 'agent_id', 'agent_email', 'supervisor_group_id', 'supervisor_id', 'manager_group_id', 'manager_id', 'outage', 'change_request', 'change_log', 'fallback'));
		MetaModel::Init_SetZListItems('advanced_search', array('ref', 'title', 'start_date', 'status', 'reason', 'requestor_id', 'customer_id', 'workgroup_id', 'creation_date', 'last_update', 'end_date', 'close_date', 'impact', 'agent_id', 'agent_email', 'supervisor_group_id', 'supervisor_id', 'manager_group_id', 'manager_id', 'outage', 'change_request', 'change_log', 'fallback'));
		MetaModel::Init_SetZListItems('standard_search', array('ref', 'title', 'start_date', 'status', 'reason', 'requestor_id', 'customer_id', 'workgroup_id', 'creation_date', 'last_update', 'end_date', 'close_date', 'impact', 'agent_id', 'agent_email', 'supervisor_group_id', 'supervisor_id', 'manager_group_id', 'manager_id', 'outage', 'change_request', 'change_log', 'fallback'));
		MetaModel::Init_SetZListItems('list', array('title', 'start_date', 'status', 'requestor_id', 'customer_id', 'workgroup_id', 'creation_date', 'last_update', 'end_date', 'close_date', 'impact', 'agent_id', 'agent_email', 'supervisor_group_id', 'supervisor_id', 'manager_group_id', 'manager_id', 'outage', 'change_request', 'change_log', 'fallback'));

		MetaModel::Init_DefineTransition("new", "ev_assign", array("target_state"=>"assigned", "actions"=>array('SetLastUpDate'), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("assigned", "ev_plan", array("target_state"=>"plannedscheduled", "actions"=>array('SetLastUpDate'), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("plannedscheduled", "ev_implement", array("target_state"=>"implemented", "actions"=>array('SetLastUpDate'), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("implemented", "ev_monitor", array("target_state"=>"monitored", "actions"=>array('SetLastUpDate'), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("implemented", "ev_finish", array("target_state"=>"closed", "actions"=>array('SetClosureDate','SetLastUpDate'), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("monitored", "ev_finish", array("target_state"=>"closed", "actions"=>array('SetClosureDate','SetLastUpDate'), "user_restriction"=>null));		
	}
}

abstract class ApprovedChange extends Change
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,changemgmt",
			"key_type" => "autoincrement",
			"name_attcode" => "ref",
			"state_attcode" => "",
			"reconc_keys" => array("ref"),
			"db_table" => "change_approved",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_InheritLifecycle();

		MetaModel::Init_AddAttribute(new AttributeDate("approval_date", array("allowed_values"=>null, "sql"=>"approval_date", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("approval_comment", array("allowed_values"=>null, "sql"=>"approval_comment", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('title', 'ticket_log', 'start_date', 'document_list', 'ci_list', 'contact_list', 'status', 'reason', 'requestor_id', 'customer_id', 'workgroup_id', 'creation_date', 'last_update', 'end_date', 'close_date', 'impact', 'agent_id', 'agent_email', 'supervisor_group_id', 'supervisor_id', 'manager_group_id', 'manager_id', 'outage', 'change_request', 'change_log', 'fallback', 'approval_date', 'approval_comment'));
		MetaModel::Init_SetZListItems('advanced_search', array('ref', 'title', 'start_date', 'status', 'reason', 'requestor_id', 'customer_id', 'workgroup_id', 'creation_date', 'last_update', 'end_date', 'close_date', 'impact', 'agent_id', 'agent_email', 'supervisor_group_id', 'supervisor_id', 'manager_group_id', 'manager_id', 'outage', 'change_request', 'change_log', 'fallback', 'approval_date', 'approval_comment'));
		MetaModel::Init_SetZListItems('standard_search', array('ref', 'title', 'start_date', 'status', 'reason', 'requestor_id', 'customer_id', 'workgroup_id', 'creation_date', 'last_update', 'end_date', 'close_date', 'impact', 'agent_id', 'agent_email', 'supervisor_group_id', 'supervisor_id', 'manager_group_id', 'manager_id', 'outage', 'change_request', 'change_log', 'fallback', 'approval_date', 'approval_comment'));
		MetaModel::Init_SetZListItems('list', array('title', 'start_date', 'status', 'requestor_id', 'customer_id', 'workgroup_id', 'creation_date', 'last_update', 'end_date', 'close_date', 'impact', 'agent_id', 'agent_email', 'supervisor_group_id', 'supervisor_id', 'manager_group_id', 'manager_id', 'outage', 'change_request', 'change_log', 'fallback', 'approval_date', 'approval_comment'));

		MetaModel::Init_OverloadStateAttribute('new', 'approval_date', OPT_ATT_HIDDEN);
		MetaModel::Init_OverloadStateAttribute('new', 'approval_comment', OPT_ATT_HIDDEN);

		MetaModel::Init_OverloadStateAttribute('approved', 'approval_date', OPT_ATT_MUSTCHANGE);
		MetaModel::Init_OverloadStateAttribute('approved', 'approval_comment', OPT_ATT_MUSTCHANGE);

		MetaModel::Init_OverloadStateAttribute('implemented', 'approval_date', OPT_ATT_READONLY);
		MetaModel::Init_OverloadStateAttribute('implemented', 'approval_comment', OPT_ATT_READONLY);
	}
}

class NormalChange extends ApprovedChange
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,changemgmt",
			"key_type" => "autoincrement",
			"name_attcode" => "ref",
			"state_attcode" => "",
			"reconc_keys" => array("ref"),
			"db_table" => "change_normal",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_InheritLifecycle();

		MetaModel::Init_AddAttribute(new AttributeDate("acceptance_date", array("allowed_values"=>null, "sql"=>"acceptance_date", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("acceptance_comment", array("allowed_values"=>null, "sql"=>"acceptance_comment", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('title', 'ticket_log', 'start_date', 'document_list', 'ci_list', 'contact_list', 'status', 'reason', 'requestor_id', 'customer_id', 'workgroup_id', 'creation_date', 'last_update', 'end_date', 'close_date', 'impact', 'agent_id', 'agent_email', 'supervisor_group_id', 'supervisor_id', 'manager_group_id', 'manager_id', 'outage', 'change_request', 'change_log', 'fallback', 'approval_date', 'approval_comment', 'acceptance_date', 'acceptance_comment'));
		MetaModel::Init_SetZListItems('advanced_search', array('ref', 'title', 'start_date', 'status', 'reason', 'requestor_id', 'customer_id', 'workgroup_id', 'creation_date', 'last_update', 'end_date', 'close_date', 'impact', 'agent_id', 'agent_email', 'supervisor_group_id', 'supervisor_id', 'manager_group_id', 'manager_id', 'outage', 'change_request', 'change_log', 'fallback'));
		MetaModel::Init_SetZListItems('standard_search', array('ref', 'title', 'start_date', 'status', 'reason', 'requestor_id', 'customer_id', 'workgroup_id', 'creation_date', 'last_update', 'end_date', 'close_date', 'impact', 'agent_id', 'agent_email', 'supervisor_group_id', 'supervisor_id', 'manager_group_id', 'manager_id', 'outage', 'change_request', 'change_log', 'fallback'));
		MetaModel::Init_SetZListItems('list', array('title', 'start_date', 'status', 'requestor_id', 'customer_id', 'workgroup_id', 'creation_date', 'last_update', 'end_date', 'close_date', 'impact', 'agent_id', 'agent_email', 'supervisor_group_id', 'supervisor_id', 'manager_group_id', 'manager_id', 'outage', 'change_request', 'change_log', 'fallback'));

		MetaModel::Init_OverloadStateAttribute('new', 'acceptance_date', OPT_ATT_HIDDEN);
		MetaModel::Init_OverloadStateAttribute('new', 'acceptance_comment', OPT_ATT_HIDDEN);

		MetaModel::Init_OverloadStateAttribute('validated', 'acceptance_date', OPT_ATT_MUSTCHANGE);
		MetaModel::Init_OverloadStateAttribute('validated', 'acceptance_comment', OPT_ATT_MUSTCHANGE);

		MetaModel::Init_OverloadStateAttribute('plannedschedule', 'acceptance_date', OPT_ATT_READONLY);
		MetaModel::Init_OverloadStateAttribute('plannedschedule', 'acceptance_comment', OPT_ATT_READONLY);

		MetaModel::Init_DefineTransition("new", "ev_validate", array("target_state"=>"validated", "actions"=>array('SetLastUpDate'), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("new", "ev_reject", array("target_state"=>"rejected", "actions"=>array('SetLastUpDate'), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("rejected", "ev_reopen", array("target_state"=>"new", "actions"=>array('SetLastUpDate'), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("validated", "ev_assign", array("target_state"=>"assigned", "actions"=>array('SetLastUpDate'), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("assigned", "ev_plan", array("target_state"=>"plannedscheduled", "actions"=>array('SetLastUpDate'), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("plannedscheduled", "ev_approve", array("target_state"=>"approved", "actions"=>array('SetLastUpDate'), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("plannedscheduled", "ev_notapprove", array("target_state"=>"notapproved", "actions"=>array('SetLastUpDate'), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("notapproved", "ev_replan", array("target_state"=>"plannedscheduled", "actions"=>array('SetLastUpDate'), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("approved", "ev_implement", array("target_state"=>"implemented", "actions"=>array('SetLastUpDate'), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("implemented", "ev_monitor", array("target_state"=>"monitored", "actions"=>array('SetLastUpDate'), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("implemented", "ev_finish", array("target_state"=>"closed", "actions"=>array('SetClosureDate','SetLastUpDate'), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("monitored", "ev_finish", array("target_state"=>"closed", "actions"=>array('SetClosureDate','SetLastUpDate'), "user_restriction"=>null));		
	}
}

class EmergencyChange extends ApprovedChange
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,changemgmt",
			"key_type" => "autoincrement",
			"name_attcode" => "ref",
			"state_attcode" => "",
			"reconc_keys" => array("ref"),
			"db_table" => "change_emergency",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_InheritLifecycle();

		MetaModel::Init_SetZListItems('details', array('title', 'ticket_log', 'start_date', 'document_list', 'ci_list', 'contact_list', 'status', 'reason', 'requestor_id', 'customer_id', 'workgroup_id', 'creation_date', 'last_update', 'end_date', 'close_date', 'impact', 'agent_id', 'agent_email', 'supervisor_group_id', 'supervisor_id', 'manager_group_id', 'manager_id', 'outage', 'change_request', 'change_log', 'fallback', 'approval_date', 'approval_comment'));
		MetaModel::Init_SetZListItems('advanced_search', array('ref', 'title', 'start_date', 'status', 'reason', 'requestor_id', 'customer_id', 'workgroup_id', 'creation_date', 'last_update', 'end_date', 'close_date', 'impact', 'agent_id', 'agent_email', 'supervisor_group_id', 'supervisor_id', 'manager_group_id', 'manager_id', 'outage', 'change_request', 'change_log', 'fallback', 'approval_date', 'approval_comment'));
		MetaModel::Init_SetZListItems('standard_search', array('ref', 'title', 'start_date', 'status', 'reason', 'requestor_id', 'customer_id', 'workgroup_id', 'creation_date', 'last_update', 'end_date', 'close_date', 'impact', 'agent_id', 'agent_email', 'supervisor_group_id', 'supervisor_id', 'manager_group_id', 'manager_id', 'outage', 'change_request', 'change_log', 'fallback', 'approval_date', 'approval_comment'));
		MetaModel::Init_SetZListItems('list', array('title', 'start_date', 'status', 'requestor_id', 'customer_id', 'workgroup_id', 'creation_date', 'last_update', 'end_date', 'close_date', 'impact', 'agent_id', 'agent_email', 'supervisor_group_id', 'supervisor_id', 'manager_group_id', 'manager_id', 'outage', 'change_request', 'change_log', 'fallback', 'approval_date', 'approval_comment'));

		MetaModel::Init_DefineTransition("new", "ev_assign", array("target_state"=>"assigned", "actions"=>array('SetLastUpDate'), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("assigned", "ev_plan", array("target_state"=>"plannedscheduled", "actions"=>array('SetLastUpDate'), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("plannedscheduled", "ev_approve", array("target_state"=>"approved", "actions"=>array('SetLastUpDate'), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("plannedscheduled", "ev_notapprove", array("target_state"=>"notapproved", "actions"=>array('SetLastUpDate'), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("notapproved", "ev_replan", array("target_state"=>"plannedscheduled", "actions"=>array('SetLastUpDate'), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("approved", "ev_implement", array("target_state"=>"implemented", "actions"=>array('SetLastUpDate'), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("implemented", "ev_monitor", array("target_state"=>"monitored", "actions"=>array('SetLastUpDate'), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("implemented", "ev_finish", array("target_state"=>"closed", "actions"=>array('SetClosureDate','SetLastUpDate'), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("monitored", "ev_finish", array("target_state"=>"closed", "actions"=>array('SetClosureDate','SetLastUpDate'), "user_restriction"=>null));		
	}
}


$oMyMenuGroup = new MenuGroup('ChangeManagement', 1 /* fRank */);

new OQLMenuNode('Changes', 'SELECT Change', $oMyMenuGroup->GetIndex(), 0 /* fRank */);
new OQLMenuNode('WaitingApproval', 'SELECT ApprovedChange WHERE status IN ("new")', $oMyMenuGroup->GetIndex(), 0 /* fRank */);
new OQLMenuNode('WaitingAcceptance', 'SELECT NormalChange WHERE status IN ("new")', $oMyMenuGroup->GetIndex(), 0 /* fRank */);

?>

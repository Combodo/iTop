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






abstract class Ticket extends cmdbAbstractObject
{

	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,ticketing",
			"key_type" => "autoincrement",
			"name_attcode" => "ref",
			"state_attcode" => "",
			"reconc_keys" => array("name"),
			"db_table" => "ticket",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeString("ref", array("allowed_values"=>null, "sql"=>"ref", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("title", array("allowed_values"=>null, "sql"=>"title", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeText("ticket_log", array("allowed_values"=>null, "sql"=>"ticket_log", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeDateTime("start_date", array("allowed_values"=>null, "sql"=>"start_date", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("document_list", array("linked_class"=>"lnkTicketToDoc", "ext_key_to_me"=>"ticket_id", "ext_key_to_remote"=>"document_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("ci_list", array("linked_class"=>"lnkTicketToCI", "ext_key_to_me"=>"ticket_id", "ext_key_to_remote"=>"ci_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("contact_list", array("linked_class"=>"lnkTicketToContact", "ext_key_to_me"=>"ticket_id", "ext_key_to_remote"=>"contact_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('ref', 'title', 'ticket_log', 'start_date', 'document_list', 'ci_list', 'contact_list'));
		MetaModel::Init_SetZListItems('advanced_search', array('ref', 'title', 'ticket_log', 'start_date'));
		MetaModel::Init_SetZListItems('standard_search', array('ref', 'title', 'ticket_log', 'start_date'));
		MetaModel::Init_SetZListItems('list', array('ref', 'title', 'ticket_log', 'start_date'));
	}
}
class lnkTicketToDoc extends cmdbAbstractObject
{

	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,ticketing",
			"key_type" => "autoincrement",
			"name_attcode" => "ticket_id",
			"state_attcode" => "",
			"reconc_keys" => array("name"),
			"db_table" => "lnktickettodoc",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeExternalKey("ticket_id", array("targetclass"=>"Ticket", "jointype"=>null, "allowed_values"=>null, "sql"=>"ticket_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("ticket_ref", array("allowed_values"=>null, "extkey_attcode"=>"ticket_id", "target_attcode"=>"ref", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("document_id", array("targetclass"=>"Document", "jointype"=>null, "allowed_values"=>null, "sql"=>"document_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("document_name", array("allowed_values"=>null, "extkey_attcode"=>"document_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('ticket_id', 'document_id'));
		MetaModel::Init_SetZListItems('advanced_search', array('ticket_id', 'document_id'));
		MetaModel::Init_SetZListItems('standard_search', array('ticket_id', 'document_id'));
		MetaModel::Init_SetZListItems('list', array('ticket_id', 'document_id'));
	}
}
class lnkTicketToContact extends cmdbAbstractObject
{

	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,ticketing",
			"key_type" => "autoincrement",
			"name_attcode" => "ticket_id",
			"state_attcode" => "",
			"reconc_keys" => array("name"),
			"db_table" => "lnktickettocontact",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeExternalKey("ticket_id", array("targetclass"=>"Ticket", "jointype"=>null, "allowed_values"=>null, "sql"=>"ticket_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("ticket_ref", array("allowed_values"=>null, "extkey_attcode"=>"ticket_id", "target_attcode"=>"ref", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("contact_id", array("targetclass"=>"Contact", "jointype"=>null, "allowed_values"=>null, "sql"=>"contact_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("contact_name", array("allowed_values"=>null, "extkey_attcode"=>"contact_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("contact_email", array("allowed_values"=>null, "extkey_attcode"=>"contact_id", "target_attcode"=>"email", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('ticket_id', 'contact_id', 'contact_email'));
		MetaModel::Init_SetZListItems('advanced_search', array('ticket_id', 'contact_id', 'contact_email'));
		MetaModel::Init_SetZListItems('standard_search', array('ticket_id', 'contact_id', 'contact_email'));
		MetaModel::Init_SetZListItems('list', array('ticket_id', 'contact_id', 'contact_email'));
	}
}
class lnkTicketToCI extends cmdbAbstractObject
{

	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,ticketing",
			"key_type" => "autoincrement",
			"name_attcode" => "ticket_id",
			"state_attcode" => "",
			"reconc_keys" => array("name"),
			"db_table" => "lnktickettoci",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeExternalKey("ticket_id", array("targetclass"=>"Ticket", "jointype"=>null, "allowed_values"=>null, "sql"=>"ticket_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("ticket_ref", array("allowed_values"=>null, "extkey_attcode"=>"ticket_id", "target_attcode"=>"ref", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("ci_id", array("targetclass"=>"FunctionalCI", "jointype"=>null, "allowed_values"=>null, "sql"=>"ci_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("ci_name", array("allowed_values"=>null, "extkey_attcode"=>"ci_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("ci_status", array("allowed_values"=>null, "extkey_attcode"=>"ci_id", "target_attcode"=>"status", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('ticket_id', 'ci_id', 'ci_status'));
		MetaModel::Init_SetZListItems('advanced_search', array('ticket_id', 'ci_id', 'ci_status'));
		MetaModel::Init_SetZListItems('standard_search', array('ticket_id', 'ci_id', 'ci_status'));
		MetaModel::Init_SetZListItems('list', array('ticket_id', 'ci_id', 'ci_status'));
	}
}
class Incident extends Ticket
{

	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,incidentmgmt",
			"key_type" => "autoincrement",
			"name_attcode" => "ref",
			"state_attcode" => "status",
			"reconc_keys" => array("name"),
			"db_table" => "incident",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeEnum("status", array("allowed_values"=>new ValueSetEnum('new,assigned,escalated_tto,escalated_ttr,resolved,closed'), "sql"=>"status", "default_value"=>"new", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("caller_id", array("targetclass"=>"Person", "jointype"=>null, "allowed_values"=>null, "sql"=>"caller_id", "is_null_allowed"=>true, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("workgroup_name", array("allowed_values"=>null, "extkey_attcode"=>"caller_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("customer_id", array("targetclass"=>"Organization", "jointype"=>null, "allowed_values"=>null, "sql"=>"customer_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("customer_name", array("allowed_values"=>null, "extkey_attcode"=>"customer_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("service_id", array("targetclass"=>"Service", "jointype"=>null, "allowed_values"=>null, "sql"=>"service_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("service_name", array("allowed_values"=>null, "extkey_attcode"=>"service_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("servicesubcategory_id", array("targetclass"=>"ServiceSubcategory", "jointype"=>null, "allowed_values"=>null, "sql"=>"servicesubcategory_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("servicesubcategory_name", array("allowed_values"=>null, "extkey_attcode"=>"servicesubcategory_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("product", array("allowed_values"=>null, "sql"=>"product", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("impact", array("allowed_values"=>new ValueSetEnum('1,2,3'), "sql"=>"impact", "default_value"=>"1", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("urgency", array("allowed_values"=>new ValueSetEnum('1,2,3'), "sql"=>"urgency", "default_value"=>"1", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("priority", array("allowed_values"=>new ValueSetEnum('1,2,3'), "sql"=>"priority", "default_value"=>"low", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("workgroup_id", array("targetclass"=>"Team", "jointype"=>null, "allowed_values"=>null, "sql"=>"workgroup_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("workgroup_name", array("allowed_values"=>null, "extkey_attcode"=>"workgroup_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("agent_id", array("targetclass"=>"Person", "jointype"=>null, "allowed_values"=>null, "sql"=>"agent_id", "is_null_allowed"=>true, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("agent_name", array("allowed_values"=>null, "extkey_attcode"=>"agent_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("agent_email", array("allowed_values"=>null, "extkey_attcode"=>"agent_id", "target_attcode"=>"email", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("related_problem_id", array("targetclass"=>"Problem", "jointype"=>null, "allowed_values"=>null, "sql"=>"related_problem_id", "is_null_allowed"=>true, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("related_problem_ref", array("allowed_values"=>null, "extkey_attcode"=>"related_problem_id", "target_attcode"=>"ref", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("related_change_id", array("targetclass"=>"Change", "jointype"=>null, "allowed_values"=>null, "sql"=>"related_change_id", "is_null_allowed"=>true, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("related_change_ref", array("allowed_values"=>null, "extkey_attcode"=>"related_change_id", "target_attcode"=>"ref", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeDateTime("closure_date", array("allowed_values"=>null, "sql"=>"closure_date", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeDateTime("last_update", array("allowed_values"=>null, "sql"=>"last_update", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeDateTime("assignment_date", array("allowed_values"=>null, "sql"=>"assignment_date", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeDeadline("escalation_deadline", array("allowed_values"=>null, "sql"=>"escalation_deadline", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeDeadline("closure_deadline", array("allowed_values"=>null, "sql"=>"closure_deadline", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("resolution_code", array("allowed_values"=>new ValueSetEnum('fixed,duplicate,couldnotreproduce,irrelevant'), "sql"=>"resolution_code", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeText("solution", array("allowed_values"=>null, "sql"=>"solution", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("user_satisfaction", array("allowed_values"=>new ValueSetEnum('1,2,3,4'), "sql"=>"user_satisfaction", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeText("user_commment", array("allowed_values"=>null, "sql"=>"user_commment", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('ref', 'title', 'ticket_log', 'start_date', 'escalation_deadline', 'closure_deadline', 'document_list', 'ci_list', 'contact_list', 'status', 'caller_id', 'customer_id', 'service_id', 'servicesubcategory_id', 'product', 'impact', 'urgency', 'priority', 'workgroup_id', 'agent_id', 'agent_email', 'related_problem_id', 'related_change_id', 'closure_date', 'last_update', 'assignment_date', 'escalation_deadline', 'closure_deadline', 'resolution_code', 'solution', 'user_satisfaction', 'user_commment'));
		MetaModel::Init_SetZListItems('advanced_search', array('ref', 'title', 'ticket_log', 'start_date', 'status', 'caller_id', 'customer_id', 'service_id', 'servicesubcategory_id', 'product', 'impact', 'urgency', 'priority', 'workgroup_id', 'agent_id', 'agent_email', 'related_problem_id', 'related_change_id', 'closure_date', 'last_update', 'assignment_date', 'escalation_deadline', 'closure_deadline', 'resolution_code', 'solution', 'user_satisfaction', 'user_commment'));
		MetaModel::Init_SetZListItems('standard_search', array('ref', 'title', 'ticket_log', 'start_date', 'status', 'caller_id', 'customer_id', 'service_id', 'servicesubcategory_id', 'product', 'impact', 'urgency', 'priority', 'workgroup_id', 'agent_id', 'agent_email', 'related_problem_id', 'related_change_id', 'closure_date', 'last_update', 'assignment_date', 'escalation_deadline', 'closure_deadline', 'resolution_code', 'solution', 'user_satisfaction', 'user_commment'));
		MetaModel::Init_SetZListItems('list', array('ref', 'title', 'ticket_log', 'start_date', 'status', 'caller_id', 'customer_id', 'service_id', 'servicesubcategory_id', 'product', 'impact', 'urgency', 'priority', 'workgroup_id', 'agent_id', 'agent_email', 'related_problem_id', 'related_change_id', 'closure_date', 'last_update', 'assignment_date', 'escalation_deadline', 'closure_deadline', 'resolution_code', 'solution', 'user_satisfaction', 'user_commment'));

		// Lifecycle
		MetaModel::Init_DefineState(
			"new",
			array(
				"attribute_inherit" => null,
				"attribute_list" => array(
					'ref' => OPT_ATT_READONLY,
					'contact_list' => OPT_ATT_READONLY,
					'start_date' => OPT_ATT_READONLY,
					'last_update' => OPT_ATT_READONLY,
					'assignment_date' => OPT_ATT_HIDDEN,
					'escalation_deadline' => OPT_ATT_READONLY,
					'closure_deadline' => OPT_ATT_HIDDEN,
					'closure_date' => OPT_ATT_HIDDEN,
					'customer_id' => OPT_ATT_MUSTCHANGE,
					'service_id' => OPT_ATT_MUSTCHANGE,
					'servicesubcategory_id' => OPT_ATT_MUSTCHANGE,
					'product' => OPT_ATT_MUSTPROMPT,
					'impact' => OPT_ATT_MUSTCHANGE,
					'urgency' => OPT_ATT_MUSTCHANGE,
					'priority' => OPT_ATT_READONLY,
					'workgroup_id' => OPT_ATT_MUSTCHANGE,
					'agent_id' => OPT_ATT_HIDDEN,
					'related_problem_id' => OPT_ATT_HIDDEN,
					'related_change_id' => OPT_ATT_HIDDEN,
					'resolution_code' => OPT_ATT_HIDDEN,
					'solution' => OPT_ATT_HIDDEN,
					'user_satisfaction' => OPT_ATT_HIDDEN,
					'user_commment' => OPT_ATT_HIDDEN,
				),
			)
		);
		MetaModel::Init_DefineState(
			"escalated_tto",
			array(
				"attribute_inherit" => 'new',
				"attribute_list" => array(
				),
			)
		);
		MetaModel::Init_DefineState(
			"assigned",
			array(
				"attribute_inherit" => 'new',
				"attribute_list" => array(
					'agent_id' => OPT_ATT_MANDATORY,
					'related_problem_id' => OPT_ATT_NORMAL,
					'related_change_id' => OPT_ATT_NORMAL,
					'closure_deadline' => OPT_ATT_READONLY,
					'escalation_deadline' => OPT_ATT_HIDDEN,
				),
			)
		);
		MetaModel::Init_DefineState(
			"escalated_ttr",
			array(
				"attribute_inherit" => 'assigned',
				"attribute_list" => array(
				),
			)
		);
		MetaModel::Init_DefineState(
			"resolved",
			array(
				"attribute_inherit" => 'assigned',
				"attribute_list" => array(
					'resolution_code' => OPT_ATT_MUSTCHANGE,
					'solution' => OPT_ATT_MUSTPROMPT,
				),
			)
		);
		MetaModel::Init_DefineState(
			"closed",
			array(
				"attribute_inherit" => 'resolved',
				"attribute_list" => array(
					'user_satisfaction' => OPT_ATT_MUSTCHANGE,
					'user_commment' => OPT_ATT_MUSTPROMPT,
				),
			)
		);

		MetaModel::Init_DefineStimulus(new StimulusUserAction("ev_assign", array()));
		MetaModel::Init_DefineStimulus(new StimulusUserAction("ev_reassign", array()));
		MetaModel::Init_DefineStimulus(new StimulusInternal("ev_timeout", array()));
		MetaModel::Init_DefineStimulus(new StimulusUserAction("ev_resolve", array()));
		MetaModel::Init_DefineStimulus(new StimulusUserAction("ev_close", array()));

		MetaModel::Init_DefineTransition("new", "ev_assign", array("target_state"=>"assigned", "actions"=>array(), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("new", "ev_timeout", array("target_state"=>"escalated_tto", "actions"=>array(), "user_restriction"=>null));

		MetaModel::Init_DefineTransition("escalated_tto", "ev_assign", array("target_state"=>"assigned", "actions"=>array(), "user_restriction"=>null));

		MetaModel::Init_DefineTransition("assigned", "ev_reassign", array("target_state"=>"assigned", "actions"=>array(), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("assigned", "ev_timeout", array("target_state"=>"escalated_ttr", "actions"=>array(), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("assigned", "ev_resolve", array("target_state"=>"resolved", "actions"=>array(), "user_restriction"=>null));

		MetaModel::Init_DefineTransition("escalated_ttr", "ev_reassign", array("target_state"=>"escalated_ttr", "actions"=>array(), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("escalated_ttr", "ev_resolve", array("target_state"=>"resolved", "actions"=>array(), "user_restriction"=>null));

		MetaModel::Init_DefineTransition("resolved", "ev_reassign", array("target_state"=>"assigned", "actions"=>array(), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("resolved", "ev_close", array("target_state"=>"closed", "actions"=>array(), "user_restriction"=>null));
	}

	/**
	 * Determines the shortest SLT, for this ticket, for the given metric. Returns null is no SLT was found
	 * @param string $sMetric Type of metric 'TTO', 'TTR', etc as defined in the SLT class
	 * @return hash Array with 'SLT' => name of the SLT selected, 'value' => duration in seconds of the SLT metric, null if no SLT applies to this ticket
	 */
	public function ComputeSLT($sMetric = 'TTO')
	{
		$aResult = null;
		if (MetaModel::IsValidClass('SLT'))
		{
			$sOQL = "SELECT SLT JOIN lnkSLTToSLA AS L1 ON L1.slt_id=SLT.id JOIN SLA ON L1.sla_id = SLA.id JOIN lnkContractToSLA AS L2 ON L2.sla_id = SLA.id JOIN CustomerContract ON L2.contract_id = CustomerContract.id 
					WHERE SLT.ticket_priority = :priority AND SLA.service_id = :service_id AND SLT.metric = :metric AND CustomerContract.customer_id = :customer_id";
			$oSLTSet = new DBObjectSet(DBObjectSearch::FromOQL($sOQL),
							array(),
							array(
								'priority' => $this->Get('priority'),
								'service_id' => $this->Get('service_id'),
								'metric' => $sMetric,
								'customer_id' => $this->Get('customer_id'),
							)
							);
							
			$iMinDuration = PHP_INT_MAX;
			$sSLTName = '';
	
			while($oSLT = $oSLTSet->Fetch())
			{
				$iDuration = $oSLT->Get('value');
				$sUnit = $oSLT->Get('value_unit');
				switch($sUnit)
				{
					case 'days':
					$iDuration = $iDuration * 24; // 24 hours in 1 days
					// Fall though
					
					case 'hours':
					$iDuration = $iDuration * 60; // 60 minutes in 1 hour
					// Fall though
					
					case 'minutes':
					$iDuration = $iDuration * 60;
				}
				if ($iDuration < $iMinDuration)
				{
					$iMinDuration = $iDuration;
					$sSLTName = $oSLT->GetName();
				}
			}
			if ($iMinDuration == PHP_INT_MAX)
			{
				$aResult = null;
			}
			else
			{
				array('SLT' => $sSLTName, 'value' => $iMinDuration);
			}
		}
		return $aResult;
	}

	/**
	 * Compute the priority of the ticket based on its impact and urgency
	 * @return integer The priority of the ticket 1(high) .. 3(low)
	 */
	public function ComputePriority()
	{
		// priority[impact][urgency]
		$aPriorities = array(
			// single person
			1 => array(
					1 => 1,
					2 => 1,
					3 => 2,
			),
			// a group
			2 => array(
				1 => 1,
				2 => 2,
				3 => 3,
			),
			// a departement!
			3 => array(
					1 => 2,
					2 => 3,
					3 => 3,
			),
		);
		$iPriority = $aPriorities[(int)$this->Get('impact')][(int)$this->Get('urgency')];
		return $iPriority;		
	}
	
	public function ComputeValues()
	{
		$iKey = $this->GetKey();
		if ($iKey < 0)
		{
			// Object not yet in the Database
			$iKey = MetaModel::GetNextKey(get_class($this));
		}
		$sName = sprintf('I-%06d', $iKey);
		$this->Set('ref', $sName);
		
		// Compute the priority of the ticket
		$this->Set('priority', $this->ComputePriority());
		
		// Compute the SLA deadlines, if any is applicable to this ticket
		$aSLT = $this->ComputeSLT('TTO');
		if ($aSLT != null)
		{
			//echo "<p>TTO: SLT found: {$aSLT['SLT']}, value: {$aSLT['value']}</p>\n";
			$iStartDate = AttributeDateTime::GetAsUnixSeconds($this->Get('start_date'));		
			$this->Set('escalation_deadline', $iStartDate + $aSLT['value']);
		}
		else
		{
			$this->Set('escalation_deadline', null);
		}
		$aSLT = $this->ComputeSLT('TTR');
		if ($aSLT != null)
		{
			//echo "<p>TTR: SLT found: {$aSLT['SLT']}, value: {$aSLT['value']}</p>\n";
			$iStartDate = AttributeDateTime::GetAsUnixSeconds($this->Get('start_date'));		
			$this->Set('closure_deadline', $iStartDate + $aSLT['value']);
		}
		else
		{
			$this->Set('closure_deadline', null);
		}
	}
}
class Change extends Ticket
{

	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,changemgmt",
			"key_type" => "autoincrement",
			"name_attcode" => "ref",
			"state_attcode" => "",
			"reconc_keys" => array("name"),
			"db_table" => "change",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeString("reason", array("allowed_values"=>null, "sql"=>"reason", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("status", array("allowed_values"=>new ValueSetEnum('Approved,Assigned,Closed,Implemented,Monitored,New,NotApproved,PlannedScheduled,Rejected,Validated'), "sql"=>"status", "default_value"=>"New", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("caller_id", array("targetclass"=>"Person", "jointype"=>null, "allowed_values"=>null, "sql"=>"caller_id", "is_null_allowed"=>true, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("workgroup_name", array("allowed_values"=>null, "extkey_attcode"=>"caller_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeDate("start", array("allowed_values"=>null, "sql"=>"start", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeDate("end", array("allowed_values"=>null, "sql"=>"end", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeDate("last_update", array("allowed_values"=>null, "sql"=>"last_update", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('ref', 'title', 'ticket_log', 'start_date', 'document_list', 'ci_list', 'contact_list', 'reason', 'status', 'caller_id', 'start', 'end', 'last_update'));
		MetaModel::Init_SetZListItems('advanced_search', array('ref', 'title', 'ticket_log', 'start_date', 'reason', 'status', 'caller_id', 'start', 'end', 'last_update'));
		MetaModel::Init_SetZListItems('standard_search', array('ref', 'title', 'ticket_log', 'start_date', 'reason', 'status', 'caller_id', 'start', 'end', 'last_update'));
		MetaModel::Init_SetZListItems('list', array('ref', 'title', 'ticket_log', 'start_date', 'reason', 'status', 'caller_id', 'start', 'end', 'last_update'));

		MetaModel::Init_DefineState(
			"new",
			array(
				"attribute_inherit"=>null,
				"attribute_list"=>array(
					'xxx' => OPT_ATT_READONLY,
					'xxx' => OPT_ATT_HIDDEN,
					'xxx' => OPT_ATT_MUSTCHANGE,
					'xxx' => OPT_ATT_MUSTPROMPT,
					'xxx' => OPT_ATT_MANDATORY,
				),
			)
		);
	}
}
class UserRequest extends Ticket
{

	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,callmgmt",
			"key_type" => "autoincrement",
			"name_attcode" => "ref",
			"state_attcode" => "",
			"reconc_keys" => array("name"),
			"db_table" => "userrequest",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeString("foo", array("allowed_values"=>null, "sql"=>"foo", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('ref', 'title', 'ticket_log', 'start_date', 'document_list', 'ci_list', 'contact_list', 'foo'));
		MetaModel::Init_SetZListItems('advanced_search', array('ref', 'title', 'ticket_log', 'start_date', 'foo'));
		MetaModel::Init_SetZListItems('standard_search', array('ref', 'title', 'ticket_log', 'start_date', 'foo'));
		MetaModel::Init_SetZListItems('list', array('ref', 'title', 'ticket_log', 'start_date', 'foo'));
	}
}
class Problem extends Ticket
{

	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,problemmgmt",
			"key_type" => "autoincrement",
			"name_attcode" => "ref",
			"state_attcode" => "",
			"reconc_keys" => array("name"),
			"db_table" => "problem",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeString("foo", array("allowed_values"=>null, "sql"=>"foo", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('ref', 'title', 'ticket_log', 'start_date', 'document_list', 'ci_list', 'contact_list', 'foo'));
		MetaModel::Init_SetZListItems('advanced_search', array('ref', 'title', 'ticket_log', 'start_date', 'foo'));
		MetaModel::Init_SetZListItems('standard_search', array('ref', 'title', 'ticket_log', 'start_date', 'foo'));
		MetaModel::Init_SetZListItems('list', array('ref', 'title', 'ticket_log', 'start_date', 'foo'));

		MetaModel::Init_DefineState(
			"new",
			array(
				"attribute_inherit"=>null,
				"attribute_list"=>array(
					'xxx' => OPT_ATT_READONLY,
					'xxx' => OPT_ATT_HIDDEN,
					'xxx' => OPT_ATT_MUSTCHANGE,
					'xxx' => OPT_ATT_MUSTPROMPT,
					'xxx' => OPT_ATT_MANDATORY,
				),
			)
		);
	}
}
class KnownError extends Ticket
{

	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,knownerrormgmt",
			"key_type" => "autoincrement",
			"name_attcode" => "ref",
			"state_attcode" => "",
			"reconc_keys" => array("name"),
			"db_table" => "knownerror",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeString("foo", array("allowed_values"=>null, "sql"=>"foo", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('ref', 'title', 'ticket_log', 'start_date', 'document_list', 'ci_list', 'contact_list', 'foo'));
		MetaModel::Init_SetZListItems('advanced_search', array('ref', 'title', 'ticket_log', 'start_date', 'foo'));
		MetaModel::Init_SetZListItems('standard_search', array('ref', 'title', 'ticket_log', 'start_date', 'foo'));
		MetaModel::Init_SetZListItems('list', array('ref', 'title', 'ticket_log', 'start_date', 'foo'));

		MetaModel::Init_DefineState(
			"new",
			array(
				"attribute_inherit"=>null,
				"attribute_list"=>array(
					'xxx' => OPT_ATT_READONLY,
					'xxx' => OPT_ATT_HIDDEN,
					'xxx' => OPT_ATT_MUSTCHANGE,
					'xxx' => OPT_ATT_MUSTPROMPT,
					'xxx' => OPT_ATT_MANDATORY,
				),
			)
		);
	}
}
class lnkKnownErrorToProblem extends Ticket
{

	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,knownerrormgmt",
			"key_type" => "autoincrement",
			"name_attcode" => "ref",
			"state_attcode" => "",
			"reconc_keys" => array("name"),
			"db_table" => "lnkknownerrortoproblem",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeString("foo", array("allowed_values"=>null, "sql"=>"foo", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('ref', 'title', 'ticket_log', 'start_date', 'document_list', 'ci_list', 'contact_list', 'foo'));
		MetaModel::Init_SetZListItems('advanced_search', array('ref', 'title', 'ticket_log', 'start_date', 'foo'));
		MetaModel::Init_SetZListItems('standard_search', array('ref', 'title', 'ticket_log', 'start_date', 'foo'));
		MetaModel::Init_SetZListItems('list', array('ref', 'title', 'ticket_log', 'start_date', 'foo'));
	}
}




//////////////////////////////////////////////////////////////////////////////
// Menu:
//   +----------------------------------------+
//   | My Module                              |
//   +----------------------------------------+
//		+ All items
//			+ ...
//			+ ...
////////////////////////////////////////////////////////////////////////////////////
// Create the top-level group. fRank = 1, means it will be inserted after the group '0', which is usually 'Welcome'
$oMyMenuGroup = new MenuGroup('IncidentManagement', 1 /* fRank */);

// By default, one entry per class
new OQLMenuNode('Incidents', 'SELECT Incident', $oMyMenuGroup->GetIndex(), 0 /* fRank */);
new OQLMenuNode('OpenedIncidents', 'SELECT Incident WHERE status IN ("new", "assigned", "escalation")', $oMyMenuGroup->GetIndex(), 0 /* fRank */);
new OQLMenuNode('ClosedIncidents', 'SELECT Incident WHERE status IN ("resolved", "closed")', $oMyMenuGroup->GetIndex(), 0 /* fRank */);
//new TemplateMenuNode('WelcomeMenuPage', '../business/templates/welcome_menu.html', $oWelcomeMenu->GetIndex() /* oParent */, 1 /* fRank */);

?>

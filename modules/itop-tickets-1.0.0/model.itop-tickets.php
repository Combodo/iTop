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
			"reconc_keys" => array("ref"),
			"db_table" => "ticket",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeString("ref", array("allowed_values"=>null, "sql"=>"ref", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("title", array("allowed_values"=>null, "sql"=>"title", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeText("description", array("allowed_values"=>null, "sql"=>"description", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeText("ticket_log", array("allowed_values"=>null, "sql"=>"ticket_log", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeDateTime("start_date", array("allowed_values"=>null, "sql"=>"start_date", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("document_list", array("linked_class"=>"lnkTicketToDoc", "ext_key_to_me"=>"ticket_id", "ext_key_to_remote"=>"document_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("ci_list", array("linked_class"=>"lnkTicketToCI", "ext_key_to_me"=>"ticket_id", "ext_key_to_remote"=>"ci_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("contact_list", array("linked_class"=>"lnkTicketToContact", "ext_key_to_me"=>"ticket_id", "ext_key_to_remote"=>"contact_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('ref', 'title', 'description', 'ticket_log', 'start_date', 'document_list', 'ci_list', 'contact_list'));
		MetaModel::Init_SetZListItems('advanced_search', array('finalclass', 'ref', 'title', 'ticket_log', 'start_date'));
		MetaModel::Init_SetZListItems('standard_search', array('finalclass', 'ref', 'title', 'ticket_log', 'start_date'));
		MetaModel::Init_SetZListItems('list', array('finalclass', 'ref', 'title', 'ticket_log', 'start_date'));
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
		MetaModel::Init_AddAttribute(new AttributeString("role", array("allowed_values"=>null, "sql"=>"role", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('ticket_id', 'contact_id', 'contact_email', 'role'));
		MetaModel::Init_SetZListItems('advanced_search', array('ticket_id', 'contact_id', 'contact_email', 'role'));
		MetaModel::Init_SetZListItems('standard_search', array('ticket_id', 'contact_id', 'contact_email', 'role'));
		MetaModel::Init_SetZListItems('list', array('ticket_id', 'contact_id', 'contact_email', 'role'));
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


abstract class ResponseTicket extends Ticket
{

	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel",
			"key_type" => "autoincrement",
			"name_attcode" => "ref",
			"state_attcode" => "status",
			"reconc_keys" => array("ref"),
			"db_table" => "ticket_response",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeEnum("status", array("allowed_values"=>new ValueSetEnum('new,assigned,frozen,escalated_tto,escalated_ttr,resolved,closed'), "sql"=>"status", "default_value"=>"new", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("caller_id", array("targetclass"=>"Person", "jointype"=>null, "allowed_values"=>null, "sql"=>"caller_id", "is_null_allowed"=>true, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("caller_email", array("allowed_values"=>null, "extkey_attcode"=>"caller_id", "target_attcode"=>"email", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("org_id", array("targetclass"=>"Organization", "jointype"=>null, "allowed_values"=>null, "sql"=>"org_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("org_name", array("allowed_values"=>null, "extkey_attcode"=>"org_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
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
		MetaModel::Init_AddAttribute(new AttributeDateTime("close_date", array("allowed_values"=>null, "sql"=>"close_date", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeDateTime("last_update", array("allowed_values"=>null, "sql"=>"last_update", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeDateTime("assignment_date", array("allowed_values"=>null, "sql"=>"assignment_date", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeDeadline("escalation_deadline", array("allowed_values"=>null, "sql"=>"escalation_deadline", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeDeadline("closure_deadline", array("allowed_values"=>null, "sql"=>"closure_deadline", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("resolution_code", array("allowed_values"=>new ValueSetEnum('fixed,duplicate,couldnotreproduce,irrelevant'), "sql"=>"resolution_code", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeText("solution", array("allowed_values"=>null, "sql"=>"solution", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("user_satisfaction", array("allowed_values"=>new ValueSetEnum('1,2,3,4'), "sql"=>"user_satisfaction", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeText("user_commment", array("allowed_values"=>null, "sql"=>"user_commment", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('ref', 'title', 'ticket_log', 'start_date', 'escalation_deadline', 'closure_deadline', 'document_list', 'ci_list', 'contact_list', 'status', 'caller_id', 'org_id', 'service_id', 'servicesubcategory_id', 'product', 'impact', 'urgency', 'priority', 'workgroup_id', 'agent_id', 'agent_email', 'related_problem_id', 'related_change_id', 'close_date', 'last_update', 'assignment_date', 'closure_deadline', 'resolution_code', 'solution', 'user_satisfaction', 'user_commment'));
		MetaModel::Init_SetZListItems('advanced_search', array('finalclass', 'ref', 'title', 'ticket_log', 'start_date', 'status', 'caller_id', 'org_id', 'service_id', 'servicesubcategory_id', 'product', 'impact', 'urgency', 'priority', 'workgroup_id', 'agent_id', 'agent_email', 'related_problem_id', 'related_change_id', 'close_date', 'last_update', 'assignment_date', 'escalation_deadline', 'closure_deadline', 'resolution_code', 'solution', 'user_satisfaction', 'user_commment'));
		MetaModel::Init_SetZListItems('standard_search', array('finalclass', 'ref', 'title', 'ticket_log', 'start_date', 'status', 'caller_id', 'org_id', 'service_id', 'servicesubcategory_id', 'product', 'impact', 'urgency', 'priority', 'workgroup_id', 'agent_id', 'agent_email', 'related_problem_id', 'related_change_id', 'close_date', 'last_update', 'assignment_date', 'escalation_deadline', 'closure_deadline', 'resolution_code', 'solution', 'user_satisfaction', 'user_commment'));
		MetaModel::Init_SetZListItems('list', array('finalclass', 'ref', 'title', 'ticket_log', 'start_date', 'status', 'caller_id', 'org_id', 'service_id', 'servicesubcategory_id', 'product', 'impact', 'urgency', 'priority', 'workgroup_id', 'agent_id', 'agent_email', 'related_problem_id', 'related_change_id', 'close_date', 'last_update', 'assignment_date', 'escalation_deadline', 'closure_deadline', 'resolution_code', 'solution', 'user_satisfaction', 'user_commment'));

		// Lifecycle
		MetaModel::Init_DefineState(
			"new",
			array(
				"attribute_inherit" => null,
				"attribute_list" => array(
					'ref' => OPT_ATT_READONLY,
					'ticket_log' => OPT_ATT_HIDDEN,
					'caller_id' => OPT_ATT_MANDATORY,
					'related_change_id' => OPT_ATT_HIDDEN,
					'description' => OPT_ATT_MUSTCHANGE,
					'contact_list' => OPT_ATT_READONLY,
					'start_date' => OPT_ATT_READONLY,
					'last_update' => OPT_ATT_READONLY,
					'assignment_date' => OPT_ATT_HIDDEN,
					'escalation_deadline' => OPT_ATT_READONLY,
					'closure_deadline' => OPT_ATT_HIDDEN,
					'close_date' => OPT_ATT_HIDDEN,
					'org_id' => OPT_ATT_MUSTCHANGE,
					'service_id' => OPT_ATT_MUSTCHANGE,
					'servicesubcategory_id' => OPT_ATT_MUSTCHANGE,
					'product' => OPT_ATT_MUSTPROMPT,
					'impact' => OPT_ATT_MUSTCHANGE,
					'urgency' => OPT_ATT_MUSTCHANGE,
					'priority' => OPT_ATT_READONLY,
					'workgroup_id' => OPT_ATT_MUSTCHANGE,
					'agent_id' => OPT_ATT_HIDDEN,
					'agent_email' => OPT_ATT_HIDDEN,
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
					'title' => OPT_ATT_READONLY,
					'caller_id' => OPT_ATT_READONLY,
					'org_id' => OPT_ATT_READONLY,
					'ticket_log' => OPT_ATT_NORMAL,
					'description' => OPT_ATT_READONLY,
					'agent_id' => OPT_ATT_MUSTPROMPT | OPT_ATT_MANDATORY,
					'agent_email' => OPT_ATT_READONLY,
					'workgroup_id' => OPT_ATT_MUSTPROMPT | OPT_ATT_MANDATORY,
					'closure_deadline' => OPT_ATT_HIDDEN,
					'escalation_deadline' => OPT_ATT_READONLY,
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
					'service_id' => OPT_ATT_READONLY,
					'servicesubcategory_id' => OPT_ATT_READONLY,
					'product' => OPT_ATT_READONLY,
					'impact' => OPT_ATT_READONLY,
					'workgroup_id' => OPT_ATT_READONLY,
					'agent_id' => OPT_ATT_READONLY,
					'urgency' => OPT_ATT_READONLY,
					'resolution_code' => OPT_ATT_MANDATORY,
					'solution' => OPT_ATT_MUSTPROMPT,
				),
			)
		);
		MetaModel::Init_DefineState(
			"closed",
			array(
				"attribute_inherit" => 'resolved',
				"attribute_list" => array(
					'ticket_log' => OPT_ATT_READONLY,
					'user_satisfaction' => OPT_ATT_MUSTPROMPT,
					'user_commment' => OPT_ATT_MUSTPROMPT,
					'resolution_code' => OPT_ATT_READONLY,
					'solution' => OPT_ATT_READONLY,
					'close_date' => OPT_ATT_READONLY,
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
		MetaModel::Init_DefineTransition("resolved", "ev_close", array("target_state"=>"closed", "actions"=>array('SetClosureDate'), "user_restriction"=>null));
	}

	// Lifecycle actions
	//
	public function SetClosureDate($sStimulusCode)
	{
		$this->Set('close_date', time());
		return true;
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
					WHERE SLT.ticket_priority = :priority AND SLA.service_id = :service_id AND SLT.metric = :metric AND CustomerContract.org_id = :org_id";
			
			$oSLTSet = new DBObjectSet(DBObjectSearch::FromOQL($sOQL),
							array(),
							array(
								'priority' => $this->Get('priority'),
								'service_id' => $this->Get('service_id'),
								'metric' => $sMetric,
								'org_id' => $this->Get('org_id'),
							)
							);
							
			$iMinDuration = PHP_INT_MAX;
			$sSLTName = '';
	
			while($oSLT = $oSLTSet->Fetch())
			{
				$iDuration = (int)$oSLT->Get('value');
				$sUnit = $oSLT->Get('value_unit');
				//echo "<p>Found SLT: ".$oSLT->GetName()." - $iDuration ($sUnit)</p>\n";
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
				$aResult = array('SLT' => $sSLTName, 'value' => $iMinDuration);
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
	
	/**
	 * Determines if the ticket must be hilighted in the list, if we're about to miss a SLA for instance
	 */
	public function GetHilightClass()
	{
		$sHilightClass = '';
		switch($this->GetState())
		{
			case 'new':
			$oEscalationDeadline = $this->Get('escalation_deadline');
			if ($oEscalationDeadline != null)
			{
				// A SLA is running
				$iStartDate = AttributeDateTime::GetAsUnixSeconds($this->Get('start_date'));
				$iEscalationDeadline = AttributeDateTime::GetAsUnixSeconds($oEscalationDeadline);
				$ratio = ($iEscalationDeadline - time())/($iEscalationDeadline - $iStartDate);
				if ($ratio <= 0)
				{
					$sHilightClass = HILIGHT_CLASS_CRITICAL;
				}
				else if ($ratio <= 0.25)
				{
					$sHilightClass = HILIGHT_CLASS_WARNING;
				}
			}
			break;
			
			case 'assigned':
			$oClosureDeadline = $this->Get('closure_deadline');
			if ($oClosureDeadline != null)
			{
				// A SLA is running
				$iStartDate = AttributeDateTime::GetAsUnixSeconds($this->Get('start_date'));
				$iClosureDeadline = AttributeDateTime::GetAsUnixSeconds($oClosureDeadline);
				$ratio = ($iClosureDeadline - time())/($iClosureDeadline - $iStartDate);
				if ($ratio <= 0)
				{
					$sHilightClass = HILIGHT_CLASS_CRITICAL;
				}
				else if ($ratio <= 0.25)
				{
					$sHilightClass = HILIGHT_CLASS_WARNING;
				}
			}
			break;
			
			case 'escalated_tto':
			case 'escalated_ttr':
			$sHilightClass = HILIGHT_CLASS_CRITICAL;
			break;
		}
		return $sHilightClass;
	}

	protected function OnInsert()
	{
		$this->Set('last_update', time());
	}
	protected function OnUpdate()
	{
		$this->Set('last_update', time());
	}
/*
	EXAMPLE: OnInsert....

	protected function OnInsert()
	{
		// Romain: ajouter cette ligne
		$oToNotify = $this->Get('contacts_a_notifier');

		// Romain: ca c'etait pour verifier que ca fonctionne bien
		// $oFirstContact = MetaModel::GetObject('bizPerson', 6);
		// $oNewLink = new lnkContactTicket();
		// $oNewLink->Set('contact_id', 6);
		// $oNewLink->Set('role', 'created before');
		// $oToNotify->AddObject($oNewLink);

		$oImpactedInfras = DBObjectSet::FromLinkSet($this, 'impacted_infra_manual', 'infra_id');

		$aComputed = $oImpactedInfras->GetRelatedObjects('impacts', 10);

		if (array_key_exists('logRealObject', $aComputed))
		{
			foreach($aComputed['logRealObject'] as $iKey => $oObject)
			{
				if (MetaModel::IsParentClass('bizContact', get_class($oObject)))
				{
					$oNewLink = new lnkContactTicket();
					$oNewLink->Set('contact_id', $iKey);
					//$oNewLink->Set('ticket_id', $this->GetKey()); // unkown at that time!
					$oNewLink->Set('role', 'contact automatically computed');

					// Romain: transformer cette ligne
					$oToNotify->AddObject($oNewLink);
				}
			}
			// Romain: supprimer cette ligne
			// $this->Set('contacts_a_notifier', $oToNotify);
		}
	}
*/
}

?>

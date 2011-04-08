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


MetaModel::RegisterRelation("impacts", array("description"=>"Objects impacted by", "verb_down"=>"impacts", "verb_up"=>"depends on"));
MetaModel::RegisterRelation("depends on", array("description"=>"That impacts ", "verb_down"=>"depends on", "verb_up"=>"impacts"));

class Organization extends cmdbAbstractObject
{

	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,structure",
			"key_type" => "autoincrement",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array("name"),
			"db_table" => "organization",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeString("name", array("allowed_values"=>null, "sql"=>"name", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("code", array("allowed_values"=>null, "sql"=>"code", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("status", array("allowed_values"=>new ValueSetEnum('active,inactive'), "sql"=>"status", "default_value"=>"active", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("parent_id", array("targetclass"=>"Organization", "jointype"=>null, "allowed_values"=>null, "sql"=>"parent_id", "is_null_allowed"=>true, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("parent_name", array("allowed_values"=>null, "extkey_attcode"=>"parent_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('name', 'code', 'status', 'parent_id'));
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'code', 'status'));
		MetaModel::Init_SetZListItems('standard_search', array('name', 'code', 'status'));
		MetaModel::Init_SetZListItems('list', array('status', 'parent_id'));
	}
}
class Location extends cmdbAbstractObject
{

	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,structure",
			"key_type" => "autoincrement",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array("name", "org_id", "org_name"),
			"db_table" => "location",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"icon" => "../modules/itop-config-mgmt-1.0.0/images/location.png",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeString("name", array("allowed_values"=>null, "sql"=>"name", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("status", array("allowed_values"=>new ValueSetEnum('active,inactive'), "sql"=>"status", "default_value"=>"active", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("org_id", array("targetclass"=>"Organization", "jointype"=>null, "allowed_values"=>null, "sql"=>"org_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("org_name", array("allowed_values"=>null, "extkey_attcode"=>"org_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeText("address", array("allowed_values"=>null, "sql"=>"address", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("postal_code", array("allowed_values"=>null, "sql"=>"postal_code", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("city", array("allowed_values"=>null, "sql"=>"city", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("country", array("allowed_values"=>null, "sql"=>"country", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("parent_id", array("targetclass"=>"Location", "jointype"=>null, "allowed_values"=>new ValueSetObjects('SELECT Location AS L WHERE L.org_id = :this->org_id'), "sql"=>"parent_id", "is_null_allowed"=>true, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array("org_id"))));
		MetaModel::Init_AddAttribute(new AttributeExternalField("parent_name", array("allowed_values"=>null, "extkey_attcode"=>"parent_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_AddAttribute(new AttributeLinkedSet("contact_list", array("linked_class"=>"Contact", "ext_key_to_me"=>"location_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSet("infra_list", array("linked_class"=>"InfrastructureCI", "ext_key_to_me"=>"location_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('name', 'status', 'org_id', 'address', 'postal_code', 'city', 'country', 'parent_id', 'contact_list', 'infra_list'));
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'status', 'org_id', 'country'));
		MetaModel::Init_SetZListItems('standard_search', array('name', 'status', 'org_id', 'city', 'country'));
		MetaModel::Init_SetZListItems('list', array('status', 'org_id', 'city', 'country'));
	}
}
abstract class Contact extends cmdbAbstractObject
{

	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,structure",
			"key_type" => "autoincrement",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array("name","org_id", "org_name", "email"),
			"db_table" => "contact",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"icon" => "../modules/itop-config-mgmt-1.0.0/images/team.png",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeString("name", array("allowed_values"=>null, "sql"=>"name", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("status", array("allowed_values"=>new ValueSetEnum('active,inactive'), "sql"=>"status", "default_value"=>"active", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("org_id", array("targetclass"=>"Organization", "jointype"=>null, "allowed_values"=>null, "sql"=>"org_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("org_name", array("allowed_values"=>null, "extkey_attcode"=>"org_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEmailAddress("email", array("allowed_values"=>null, "sql"=>"email", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("phone", array("allowed_values"=>null, "sql"=>"phone", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("location_id", array("targetclass"=>"Location", "jointype"=>null, "allowed_values"=>new ValueSetObjects('SELECT Location AS L WHERE L.org_id = :this->org_id'), "sql"=>"location_id", "is_null_allowed"=>true, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array('org_id'))));
		MetaModel::Init_AddAttribute(new AttributeExternalField("location_name", array("allowed_values"=>null, "extkey_attcode"=>"location_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("contract_list", array("linked_class"=>"lnkContractToContact", "ext_key_to_me"=>"contact_id", "ext_key_to_remote"=>"contract_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("service_list", array("linked_class"=>"lnkServiceToContact", "ext_key_to_me"=>"contact_id", "ext_key_to_remote"=>"service_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("ticket_list", array("linked_class"=>"lnkTicketToContact", "ext_key_to_me"=>"contact_id", "ext_key_to_remote"=>"ticket_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("ci_list", array("linked_class"=>"lnkCIToContact", "ext_key_to_me"=>"contact_id", "ext_key_to_remote"=>"ci_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("team_list", array("linked_class"=>"lnkTeamToContact", "ext_key_to_me"=>"contact_id", "ext_key_to_remote"=>"team_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('name', 'status', 'org_id', 'email', 'phone', 'location_id', 'contract_list', 'service_list', 'ticket_list', 'ci_list', 'team_list'));
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'status', 'org_id', 'email', 'phone', 'location_id'));
		MetaModel::Init_SetZListItems('standard_search', array('name', 'status', 'org_id', 'email', 'phone', 'location_id'));
		MetaModel::Init_SetZListItems('list', array('finalclass', 'status', 'org_id', 'email', 'phone', 'location_id'));
	}
}
class Person extends Contact
{

	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,structure",
			"key_type" => "autoincrement",
			"name_attcode" => array('first_name', 'name'),
			"state_attcode" => "",
			"reconc_keys" => array("name","first_name","org_id","email"),
			"db_table" => "person",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"icon" => "../modules/itop-config-mgmt-1.0.0/images/person.png",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeString("first_name", array("allowed_values"=>null, "sql"=>"first_name", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("employee_id", array("allowed_values"=>null, "sql"=>"employee_id", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('name','first_name', 'org_id', 'status', 'location_id', 'email', 'phone', 'employee_id','team_list', 'contract_list', 'service_list', 'ticket_list', 'ci_list'));
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'status', 'org_id', 'email', 'phone', 'location_id', 'first_name', 'employee_id'));
		MetaModel::Init_SetZListItems('standard_search', array('name', 'status', 'org_id', 'email', 'phone', 'location_id', 'first_name', 'employee_id'));
		MetaModel::Init_SetZListItems('list', array('name','first_name','status', 'org_id', 'email', 'phone', 'location_id'));
	}
}
class Team extends Contact
{

	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,structure",
			"key_type" => "autoincrement",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array("name", "org_id"),
			"db_table" => "team",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"icon" => "../modules/itop-config-mgmt-1.0.0/images/team.png",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("member_list", array("linked_class"=>"lnkTeamToContact", "ext_key_to_me"=>"team_id", "ext_key_to_remote"=>"contact_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('name', 'status', 'org_id', 'location_id', 'email', 'phone', 'member_list', 'contract_list', 'service_list', 'ticket_list', 'ci_list', 'team_list'));
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'status', 'org_id', 'email', 'phone', 'location_id'));
		MetaModel::Init_SetZListItems('standard_search', array('name', 'status', 'org_id', 'email', 'phone', 'location_id'));
		MetaModel::Init_SetZListItems('list', array('status', 'org_id', 'email', 'phone', 'location_id'));
	}
}
class lnkTeamToContact extends cmdbAbstractObject
{

	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,structure",
			"key_type" => "autoincrement",
			"name_attcode" => "team_id",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "lnkteamtocontact",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeExternalKey("team_id", array("targetclass"=>"Team", "jointype"=>null, "allowed_values"=>null, "sql"=>"team_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("team_name", array("allowed_values"=>null, "extkey_attcode"=>"team_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("contact_id", array("targetclass"=>"Contact", "jointype"=>null, "allowed_values"=>null, "sql"=>"contact_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("contact_name", array("allowed_values"=>null, "extkey_attcode"=>"contact_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("contact_location_id", array("allowed_values"=>null, "extkey_attcode"=>"contact_id", "target_attcode"=>"location_id", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("contact_location_name", array("allowed_values"=>null, "extkey_attcode"=>"contact_id", "target_attcode"=>"location_name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("contact_email", array("allowed_values"=>null, "extkey_attcode"=>"contact_id", "target_attcode"=>"email", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("contact_phone", array("allowed_values"=>null, "extkey_attcode"=>"contact_id", "target_attcode"=>"phone", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("role", array("allowed_values"=>null, "sql"=>"role", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('team_id', 'contact_id', 'role'));
		MetaModel::Init_SetZListItems('advanced_search', array('team_id', 'contact_id', 'role'));
		MetaModel::Init_SetZListItems('standard_search', array('team_id', 'contact_id', 'role'));
		MetaModel::Init_SetZListItems('list', array('team_id', 'contact_id', 'contact_location_id', 'contact_email', 'contact_phone', 'role'));
	}
}
abstract class Document extends cmdbAbstractObject
{

	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,documentation",
			"key_type" => "autoincrement",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array("name","org_id","org_name"),
			"db_table" => "document",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"icon" => "../modules/itop-config-mgmt-1.0.0/images/document.png",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeString("name", array("allowed_values"=>null, "sql"=>"name", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("org_id", array("targetclass"=>"Organization", "jointype"=>null, "allowed_values"=>null, "sql"=>"org_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("org_name", array("allowed_values"=>null, "extkey_attcode"=>"org_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeText("description", array("allowed_values"=>null, "sql"=>"description", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("type", array("allowed_values"=>new ValueSetEnum('contract,networkmap,presentation,training,whitePaper,workinginstructions'), "sql"=>"type", "default_value"=>"presentation", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("status", array("allowed_values"=>new ValueSetEnum('draft,published,obsolete'), "sql"=>"status", "default_value"=>"draft", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("contract_list", array("linked_class"=>"lnkContractToDoc", "ext_key_to_me"=>"document_id", "ext_key_to_remote"=>"contract_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("service_list", array("linked_class"=>"lnkServiceToDoc", "ext_key_to_me"=>"document_id", "ext_key_to_remote"=>"service_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("ticket_list", array("linked_class"=>"lnkTicketToDoc", "ext_key_to_me"=>"document_id", "ext_key_to_remote"=>"ticket_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("ci_list", array("linked_class"=>"lnkCIToDoc", "ext_key_to_me"=>"document_id", "ext_key_to_remote"=>"ci_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('name', 'org_id', 'description', 'type', 'status', 'contract_list', 'service_list', 'ticket_list', 'ci_list'));
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'org_id', 'description', 'type', 'status'));
		MetaModel::Init_SetZListItems('standard_search', array('name', 'org_id', 'description', 'type', 'status'));
		MetaModel::Init_SetZListItems('list', array('org_id', 'type', 'status'));
	}
}
class WebDoc extends Document
{

	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,documentation",
			"key_type" => "autoincrement",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array("name","org_id","org_name"),
			"db_table" => "externaldoc",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"icon" => "../modules/itop-config-mgmt-1.0.0/images/document.png",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeURL("url", array("target"=>"_blank", "allowed_values"=>null, "sql"=>"url", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('name', 'org_id', 'description', 'type', 'status', 'contract_list', 'service_list', 'ticket_list', 'ci_list', 'url'));
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'org_id', 'description', 'type', 'status', 'url'));
		MetaModel::Init_SetZListItems('standard_search', array('name', 'org_id', 'description', 'type', 'status', 'url'));
		MetaModel::Init_SetZListItems('list', array('org_id', 'type', 'status', 'url'));
	}
}
class Note extends Document
{

	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,documentation",
			"key_type" => "autoincrement",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array("name","org_id","org_name"),
			"db_table" => "note",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"icon" => "../modules/itop-config-mgmt-1.0.0/images/document.png",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeText("note", array("allowed_values"=>null, "sql"=>"note", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('name', 'org_id', 'description', 'type', 'status', 'contract_list', 'service_list', 'ticket_list', 'ci_list', 'note'));
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'org_id', 'description', 'type', 'status', 'note'));
		MetaModel::Init_SetZListItems('standard_search', array('name', 'org_id', 'description', 'type', 'status', 'note'));
		MetaModel::Init_SetZListItems('list', array('org_id', 'type', 'status', 'note'));
	}
}
class FileDoc extends Document
{

	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,documentation",
			"key_type" => "autoincrement",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array("name","org_id","org_name"),
			"db_table" => "filedoc",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"icon" => "../modules/itop-config-mgmt-1.0.0/images/document.png",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeBlob("contents", array("depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('name', 'org_id', 'description', 'type', 'status', 'contract_list', 'service_list', 'ticket_list', 'ci_list', 'contents'));
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'org_id', 'description', 'type', 'status'));
		MetaModel::Init_SetZListItems('standard_search', array('name', 'org_id', 'description', 'type', 'status'));
		MetaModel::Init_SetZListItems('list', array('org_id', 'type', 'status', 'contents'));
	}
	
	/**
	 * Overload the display of the properties to add a tab (the first one)
	 * with the preview of the document
	 */
	public function DisplayBareProperties(WebPage $oPage, $bEditMode = false)
	{
		if (!$bEditMode)
	{
		$oPage->SetCurrentTab(Dict::S('Class:Document:PreviewTab'));
		$oPage->add($this->DisplayDocumentInline($oPage, 'contents'));
		$oPage->SetCurrentTab(Dict::S('UI:PropertiesTab'));
		}
		parent::DisplayBareProperties($oPage, $bEditMode);
		
	}
}
class Licence extends cmdbAbstractObject
{

	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,configmgmt",
			"key_type" => "autoincrement",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array("name","org_id", "org_name"),
			"db_table" => "licence",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"icon" => "../modules/itop-config-mgmt-1.0.0/images/licence.png",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeString("provider", array("allowed_values"=>null, "sql"=>"provider", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("org_id", array("targetclass"=>"Organization", "jointype"=>null, "allowed_values"=>null, "sql"=>"org_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("org_name", array("allowed_values"=>null, "extkey_attcode"=>"org_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("product", array("allowed_values"=>null, "sql"=>"product", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("name", array("allowed_values"=>null, "sql"=>"name", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeDate("start", array("allowed_values"=>null, "sql"=>"start", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeDate("end", array("allowed_values"=>null, "sql"=>"end", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("licence_key", array("allowed_values"=>null, "sql"=>"licence_key", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeText("scope", array("allowed_values"=>null, "sql"=>"scope", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeInteger("usage_limit", array("allowed_values"=>null, "sql"=>"usage_limit", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSet("usage_list", array("linked_class"=>"SoftwareInstance", "ext_key_to_me"=>"licence_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('name','org_id','provider', 'product', 'start', 'end', 'licence_key', 'scope', 'usage_limit', 'usage_list'));
		MetaModel::Init_SetZListItems('advanced_search', array('provider', 'product', 'name', 'start', 'end', 'licence_key', 'scope'));
		MetaModel::Init_SetZListItems('standard_search', array('org_id','provider', 'product', 'name', 'start', 'end', 'licence_key', 'scope'));
		MetaModel::Init_SetZListItems('list', array('org_id','provider', 'product',  'start', 'end'));
	}
}
class Subnet extends cmdbAbstractObject
{

	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,configmgmt",
			"key_type" => "autoincrement",
			"name_attcode" => array('ip', 'ip_mask'),
			"state_attcode" => "",
			"reconc_keys" => array("ip", "ip_mask","org_id", "org_name"),
			"db_table" => "subnet",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"icon" => "../modules/itop-config-mgmt-1.0.0/images/subnet.png",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		//MetaModel::Init_AddAttribute(new AttributeString("name", array("allowed_values"=>null, "sql"=>"name", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeText("description", array("allowed_values"=>null, "sql"=>"description", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("org_id", array("targetclass"=>"Organization", "jointype"=>null, "allowed_values"=>null, "sql"=>"org_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("org_name", array("allowed_values"=>null, "extkey_attcode"=>"org_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeIPAddress("ip", array("allowed_values"=>null, "sql"=>"ip", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeIPAddress("ip_mask", array("allowed_values"=>null, "sql"=>"ip_mask", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('ip', 'ip_mask', 'org_id', 'description'));
		MetaModel::Init_SetZListItems('advanced_search', array('ip', 'ip_mask', 'org_id', 'description'));
		MetaModel::Init_SetZListItems('standard_search', array('ip', 'ip_mask', 'org_id', 'description'));
		MetaModel::Init_SetZListItems('list', array('ip', 'ip_mask', 'org_id'));
	}

	function DisplayBareRelations(WebPage $oPage, $bEditMode = false)
	{
		parent::DisplayBareRelations($oPage, $bEditMode);

		if (!$bEditMode)
		{
			$oPage->SetCurrentTab(Dict::S('Class:Subnet/Tab:IPUsage'));
	
			$bit_ip = ip2long($this->Get('ip'));
			$bit_mask = ip2long($this->Get('ip_mask'));
	
			$iIPMin = ($bit_ip & $bit_mask) + 1; // exclude the first one: identifies the subnet itsel
			$iIPMax = ($bit_ip | (~$bit_mask)) - 1; // exclude the last one : reserved for DHCP
	
			$sIPMin = long2ip($iIPMin);
			$sIPMax = long2ip($iIPMax);
	
			$oPage->p(Dict::Format('Class:Subnet/Tab:IPUsage-explain', $sIPMin, $sIPMax));
			
			$oIfSet = new CMDBObjectSet(DBObjectSearch::FromOQL("SELECT NetworkInterface AS if WHERE INET_ATON(if.ip_address) >= INET_ATON('$sIPMin') AND INET_ATON(if.ip_address) <= INET_ATON('$sIPMax')"));
			self::DisplaySet($oPage, $oIfSet, array('block_id' => 'nwif'));
	
			$iCountUsed = $oIfSet->Count();
			$iCountRange = $iIPMax - $iIPMin;
			$iFreeCount =  $iCountRange - $iCountUsed;
	
			$oPage->SetCurrentTab(Dict::S('Class:Subnet/Tab:FreeIPs'));
			$oPage->p(Dict::Format('Class:Subnet/Tab:FreeIPs-count', $iFreeCount));
			$oPage->p(Dict::S('Class:Subnet/Tab:FreeIPs-explain'));
	
			$aUsedIPs = $oIfSet->GetColumnAsArray('ip_address', false);
			$iAnIP = $iIPMin;
			$iFound = 0;
			while (($iFound < min($iFreeCount, 10)) && ($iAnIP <= $iIPMax))
			{
				$sAnIP = long2ip($iAnIP);
				if (!in_array($sAnIP, $aUsedIPs))
				{
					$iFound++;
					$oPage->p($sAnIP);
				}
				else
				{
				}
				$iAnIP++;
			}
		}
	}
}
class Patch extends cmdbAbstractObject
{

	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,configmgmt",
			"key_type" => "autoincrement",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array("name"),
			"db_table" => "patch",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"icon" => "../modules/itop-config-mgmt-1.0.0/images/patch.png",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeString("name", array("allowed_values"=>null, "sql"=>"name", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeText("description", array("allowed_values"=>null, "sql"=>"description", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("target_sw", array("allowed_values"=>null, "sql"=>"target_sw", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("version", array("allowed_values"=>null, "sql"=>"version", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("type", array("allowed_values"=>new ValueSetEnum('application,os,security,servicepack'), "sql"=>"type", "default_value"=>"security", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("ci_list", array("linked_class"=>"lnkPatchToCI", "ext_key_to_me"=>"patch_id", "ext_key_to_remote"=>"ci_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('name', 'description', 'target_sw', 'version', 'type', 'ci_list'));
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'target_sw', 'version', 'type'));
		MetaModel::Init_SetZListItems('standard_search', array('name', 'target_sw', 'version', 'type','description'));
		MetaModel::Init_SetZListItems('list', array('target_sw', 'version', 'type'));
	}
}
abstract class Software extends cmdbAbstractObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,configmgmt",
			"key_type" => "autoincrement",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array("name"),
			"db_table" => "software",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"icon" => "../modules/itop-config-mgmt-1.0.0/images/software.png",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeString("name", array("allowed_values"=>null, "sql"=>"name", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeText("description", array("allowed_values"=>null, "sql"=>"description", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('name', 'description'));
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'description'));
		MetaModel::Init_SetZListItems('standard_search', array('name', 'description'));
		MetaModel::Init_SetZListItems('list', array('description'));
	}
}
class Application extends Software
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,configmgmt",
			"key_type" => "autoincrement",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array("name"),
			"db_table" => "software_app",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"icon" => "../modules/itop-config-mgmt-1.0.0/images/software.png",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeLinkedSet("instance_list", array("linked_class"=>"ApplicationInstance", "ext_key_to_me"=>"software_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('name', 'description', 'instance_list'));
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'description'));
		MetaModel::Init_SetZListItems('standard_search', array('name', 'description'));
		MetaModel::Init_SetZListItems('list', array('description'));
	}
}
class DBServer extends Software
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,configmgmt",
			"key_type" => "autoincrement",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array("name"),
			"db_table" => "software_db",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"icon" => "../modules/itop-config-mgmt-1.0.0/images/software.png",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeLinkedSet("instance_list", array("linked_class"=>"DBServerInstance", "ext_key_to_me"=>"software_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('name', 'description', 'instance_list'));
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'description'));
		MetaModel::Init_SetZListItems('standard_search', array('name', 'description'));
		MetaModel::Init_SetZListItems('list', array('description'));
	}
}
class lnkPatchToCI extends cmdbAbstractObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,configmgmt",
			"key_type" => "autoincrement",
			"name_attcode" => "patch_id",
			"state_attcode" => "",
			"reconc_keys" => array("patch_id","ci_id"),
			"db_table" => "lnkpatchtoci",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeExternalKey("patch_id", array("targetclass"=>"Patch", "jointype"=>null, "allowed_values"=>null, "sql"=>"patch_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("patch_name", array("allowed_values"=>null, "extkey_attcode"=>"patch_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("ci_id", array("targetclass"=>"Device", "jointype"=>null, "allowed_values"=>null, "sql"=>"ci_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("ci_name", array("allowed_values"=>null, "extkey_attcode"=>"ci_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("ci_status", array("allowed_values"=>null, "extkey_attcode"=>"ci_id", "target_attcode"=>"status", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('patch_id', 'ci_id', 'ci_status'));
		MetaModel::Init_SetZListItems('advanced_search', array('patch_id', 'ci_id'));
		MetaModel::Init_SetZListItems('standard_search', array('patch_id', 'ci_id'));
		MetaModel::Init_SetZListItems('list', array('patch_id', 'ci_id', 'ci_status'));
	}
}
abstract class FunctionalCI extends cmdbAbstractObject
{

	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,configmgmt",
			"key_type" => "autoincrement",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array("name","org_id", "owner_name"),
			"db_table" => "functionalci",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"icon" => "../modules/itop-config-mgmt-1.0.0/images/server.png",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeString("name", array("allowed_values"=>null, "sql"=>"name", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("status", array("allowed_values"=>new ValueSetEnum('implementation,production,obsolete'), "sql"=>"status", "default_value"=>"implementation", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("org_id", array("targetclass"=>"Organization", "jointype"=>null, "allowed_values"=>null, "sql"=>"org_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("owner_name", array("allowed_values"=>null, "extkey_attcode"=>"org_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("importance", array("allowed_values"=>new ValueSetEnum('low,medium,high'), "sql"=>"importance", "default_value"=>"medium", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("contact_list", array("linked_class"=>"lnkCIToContact", "ext_key_to_me"=>"ci_id", "ext_key_to_remote"=>"contact_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("document_list", array("linked_class"=>"lnkCIToDoc", "ext_key_to_me"=>"ci_id", "ext_key_to_remote"=>"document_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("solution_list", array("linked_class"=>"lnkSolutionToCI", "ext_key_to_me"=>"ci_id", "ext_key_to_remote"=>"solution_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("contract_list", array("linked_class"=>"lnkContractToCI", "ext_key_to_me"=>"ci_id", "ext_key_to_remote"=>"contract_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("ticket_list", array("linked_class"=>"lnkTicketToCI", "ext_key_to_me"=>"ci_id", "ext_key_to_remote"=>"ticket_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));


		MetaModel::Init_SetZListItems('details', array('name', 'status', 'org_id', 'importance', 'contact_list', 'document_list', 'solution_list', 'contract_list', 'ticket_list'));
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'status', 'org_id', 'importance'));
		MetaModel::Init_SetZListItems('standard_search', array('name', 'status', 'org_id', 'importance'));
		MetaModel::Init_SetZListItems('list', array('finalclass', 'status', 'org_id', 'importance'));
	}

	public static function GetRelationQueries($sRelCode)
	{
		switch ($sRelCode)
		{
		case "impacts":
			$aRels = array(
				"contact" => array("sQuery"=>"SELECT Contact AS c JOIN lnkCIToContact AS l1 ON l1.contact_id = c.id WHERE l1.ci_id = :this->id", "bPropagate"=>true, "iDistance"=>3),
				"solution" => array("sQuery"=>"SELECT ApplicationSolution AS s JOIN lnkSolutionToCI AS l1 ON l1.solution_id = s.id WHERE l1.ci_id = :this->id", "bPropagate"=>true, "iDistance"=>2),
			);
			return array_merge($aRels, parent::GetRelationQueries($sRelCode));
			break;
		default:
			return parent::GetRelationQueries($sRelCode);
		}
	}
	
}
abstract class SoftwareInstance extends FunctionalCI
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,configmgmt",
			"key_type" => "autoincrement",
			"name_attcode" => array('name', 'device_id_friendlyname'),
			"state_attcode" => "",
			"reconc_keys" => array("name", "device_id", "device_name", "org_id", "owner_name"),
			"db_table" => "softwareinstance",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"icon" => "../modules/itop-config-mgmt-1.0.0/images/application.png",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeExternalKey("device_id", array("targetclass"=>"Device", "jointype"=>null, "allowed_values"=>new ValueSetObjects('SELECT Device WHERE org_id = :this->org_id'), "sql"=>"device_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array("org_id"))));
		MetaModel::Init_AddAttribute(new AttributeExternalField("device_name", array("allowed_values"=>null, "extkey_attcode"=>"device_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("licence_id", array("targetclass"=>"Licence", "jointype"=>null, "allowed_values"=>new ValueSetObjects('SELECT Licence WHERE org_id = :this->org_id'), "sql"=>"licence_id", "is_null_allowed"=>true, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array("org_id"))));
		MetaModel::Init_AddAttribute(new AttributeExternalField("licence_name", array("allowed_values"=>null, "extkey_attcode"=>"licence_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("version", array("allowed_values"=>null, "sql"=>"version", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeText("description", array("allowed_values"=>null, "sql"=>"description", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('name', 'status', 'org_id', 'importance', 'device_id', 'licence_id', 'version', 'description', 'contact_list', 'document_list', 'solution_list', 'contract_list', 'ticket_list'));
		MetaModel::Init_SetZListItems('advanced_search', array('status', 'org_id', 'importance', 'device_id', 'licence_id',  'version'));
		MetaModel::Init_SetZListItems('standard_search', array('status', 'org_id', 'importance', 'device_id', 'licence_id', 'version'));
		MetaModel::Init_SetZListItems('list', array('finalclass', 'status', 'org_id', 'importance', 'device_id', 'version'));
	}

	public function ComputeValues()
	{
	}

	public static function GetRelationQueries($sRelCode)
	{
		switch ($sRelCode)
		{
			case "impacts":
			$aRels = array(
				// Actually this should be limited to the Software instances based on a DBServer Application type...
				"db_instances" => array("sQuery"=>"SELECT DatabaseInstance AS db WHERE db.db_server_instance_id = :this->id", "bPropagate"=>true, "iDistance"=>5),
			);
			return array_merge($aRels, parent::GetRelationQueries($sRelCode));
			break;
			
			case 'depends on':
			$aRels = array(
				"applications" => array("sQuery"=>"SELECT Device JOIN SoftwareInstance AS app ON app.device_id = Device.id WHERE app.id = :this->id", "bPropagate"=>true, "iDistance"=>5),			
			);
			return array_merge($aRels, parent::GetRelationQueries($sRelCode));
			break;
			
			default:
			return parent::GetRelationQueries($sRelCode);			
		}
	}
}
class DBServerInstance extends SoftwareInstance
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,configmgmt",
			"key_type" => "autoincrement",
			"name_attcode" => array('name', 'device_id_friendlyname'),
			"state_attcode" => "",
			"reconc_keys" => array("name","software_id","software_name","device_id","device_name","org_id","owner_name"),
			"db_table" => "softwareinstance_dbserver",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"icon" => "../modules/itop-config-mgmt-1.0.0/images/database.png",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeExternalKey("software_id", array("targetclass"=>"DBServer", "jointype"=>null, "allowed_values"=>null, "sql"=>"software_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("software_name", array("allowed_values"=>null, "extkey_attcode"=>"software_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSet("dbinstance_list", array("linked_class"=>"DatabaseInstance", "ext_key_to_me"=>"db_server_instance_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('name', 'status', 'org_id', 'importance', 'device_id', 'licence_id', 'software_id', 'version', 'description', 'contact_list', 'document_list', 'solution_list', 'contract_list', 'ticket_list', 'dbinstance_list'));
		MetaModel::Init_SetZListItems('advanced_search', array('status', 'org_id', 'importance', 'device_id', 'licence_id', 'software_id', 'version'));
		MetaModel::Init_SetZListItems('standard_search', array('status', 'org_id', 'importance', 'device_id', 'licence_id', 'software_id', 'version'));
		MetaModel::Init_SetZListItems('list', array('status', 'org_id', 'importance', 'device_id', 'software_id', 'version'));
	}
}
class ApplicationInstance extends SoftwareInstance
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,configmgmt",
			"key_type" => "autoincrement",
			"name_attcode" => array('name', 'device_id_friendlyname'),
			"state_attcode" => "",
			"reconc_keys" => array("name","software_id","software_name","device_id","device_name","org_id","owner_name"),
			"db_table" => "softwareinstance_application",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"icon" => "../modules/itop-config-mgmt-1.0.0/images/application.png",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeExternalKey("software_id", array("targetclass"=>"Application", "jointype"=>null, "allowed_values"=>null, "sql"=>"software_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("software_name", array("allowed_values"=>null, "extkey_attcode"=>"software_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('name', 'status', 'org_id', 'importance', 'device_id', 'licence_id', 'software_id', 'version', 'description', 'contact_list', 'document_list', 'solution_list', 'contract_list', 'ticket_list'));
		MetaModel::Init_SetZListItems('advanced_search', array('status', 'org_id', 'importance', 'device_id', 'licence_id', 'software_id', 'version'));
		MetaModel::Init_SetZListItems('standard_search', array('status', 'org_id', 'importance', 'device_id', 'licence_id', 'software_id', 'version'));
		MetaModel::Init_SetZListItems('list', array('status', 'org_id', 'importance', 'device_id', 'software_id', 'version'));
	}
}

class DatabaseInstance extends FunctionalCI
{

	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,configmgmt",
			"key_type" => "autoincrement",
			"name_attcode" => array('name', 'db_server_instance_id_friendlyname'),
			"state_attcode" => "",
			"reconc_keys" => array("name","org_id","owner_name","db_server_instance_id","db_server_instance_name"),
			"db_table" => "databaseinstance",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"icon" => "../modules/itop-config-mgmt-1.0.0/images/database-instance.png",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeExternalKey("db_server_instance_id", array("targetclass"=>"DBServerInstance", "jointype"=>null, "allowed_values"=>new ValueSetObjects('SELECT DBServerInstance WHERE org_id = :this->org_id'), "sql"=>"db_server_instance_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array("org_id"))));
		MetaModel::Init_AddAttribute(new AttributeExternalField("db_server_instance_name", array("allowed_values"=>null, "extkey_attcode"=>"db_server_instance_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("db_server_instance_version", array("allowed_values"=>null, "extkey_attcode"=>"db_server_instance_id", "target_attcode"=>"version", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeText("description", array("allowed_values"=>null, "sql"=>"description", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('name', 'status', 'org_id', 'importance', 'db_server_instance_id', 'db_server_instance_version', 'description', 'contact_list', 'document_list', 'solution_list', 'contract_list', 'ticket_list'));
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'status', 'org_id', 'importance', 'db_server_instance_id', 'db_server_instance_version'));
		MetaModel::Init_SetZListItems('standard_search', array('name', 'status', 'org_id', 'importance', 'db_server_instance_id', 'db_server_instance_version'));
		MetaModel::Init_SetZListItems('list', array('status', 'org_id', 'importance', 'db_server_instance_id', 'db_server_instance_version'));
	}

	public static function GetRelationQueries($sRelCode)
	{
		switch ($sRelCode)
		{
			case "depends on":
			$aRels = array(
				"db_instances" => array("sQuery"=>"SELECT DBServerInstance AS db_server_inst JOIN DatabaseInstance AS db ON  db.db_server_instance_id = db_server_inst.id WHERE db.id = :this->id", "bPropagate"=>true, "iDistance"=>5),
			);
			return array_merge($aRels, parent::GetRelationQueries($sRelCode));
			break;
						
			default:
			return parent::GetRelationQueries($sRelCode);			
		}
	}
}
class Group extends cmdbAbstractObject
{

	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,configmgmt",
			"key_type" => "autoincrement",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array("name","org_id","owner_name"),
			"db_table" => "group",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"icon" => "../modules/itop-config-mgmt-1.0.0/images/group.png",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeString("name", array("allowed_values"=>null, "sql"=>"name", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("status", array("allowed_values"=>new ValueSetEnum('production,implementation,obsolete'), "sql"=>"status", "default_value"=>"implementation", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("org_id", array("targetclass"=>"Organization", "jointype"=>null, "allowed_values"=>null, "sql"=>"org_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("owner_name", array("allowed_values"=>null, "extkey_attcode"=>"org_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeText("description", array("allowed_values"=>null, "sql"=>"description", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("type", array("allowed_values"=>null, "sql"=>"type", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("parent_id", array("targetclass"=>"Group", "jointype"=>null, "allowed_values"=>null, "sql"=>"parent_id", "is_null_allowed"=>true, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array("org_id"))));
		MetaModel::Init_AddAttribute(new AttributeExternalField("parent_name", array("allowed_values"=>null, "extkey_attcode"=>"parent_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("ci_list", array("linked_class"=>"lnkGroupToCI", "ext_key_to_me"=>"group_id", "ext_key_to_remote"=>"ci_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('name', 'status', 'org_id', 'type','description', 'parent_id', 'ci_list'));
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'status', 'org_id', 'type'));
		MetaModel::Init_SetZListItems('standard_search', array('name', 'status', 'org_id', 'type'));
		MetaModel::Init_SetZListItems('list', array('status', 'org_id', 'type','parent_id'));
	}
}
class lnkGroupToCI extends cmdbAbstractObject
{

	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,configmgmt",
			"key_type" => "autoincrement",
			"name_attcode" => "group_id",
			"state_attcode" => "",
			"reconc_keys" => array("group_id","ci_id"),
			"db_table" => "lnkgrouptoci",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeExternalKey("group_id", array("targetclass"=>"Group", "jointype"=>null, "allowed_values"=>null, "sql"=>"group_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("group_name", array("allowed_values"=>null, "extkey_attcode"=>"group_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("ci_id", array("targetclass"=>"FunctionalCI", "jointype"=>null, "allowed_values"=>null, "sql"=>"ci_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("ci_name", array("allowed_values"=>null, "extkey_attcode"=>"ci_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("ci_status", array("allowed_values"=>null, "extkey_attcode"=>"ci_id", "target_attcode"=>"status", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("reason", array("allowed_values"=>null, "sql"=>"reason", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('group_id', 'ci_id', 'ci_status', 'reason'));
		MetaModel::Init_SetZListItems('advanced_search', array('group_id', 'ci_id', 'reason'));
		MetaModel::Init_SetZListItems('standard_search', array('group_id', 'ci_id', 'reason'));
		MetaModel::Init_SetZListItems('list', array('group_id', 'ci_id', 'ci_status', 'reason'));
	}
}
class ApplicationSolution extends FunctionalCI
{

	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,configmgmt",
			"key_type" => "autoincrement",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array("name","org_id","owner_name"),
			"db_table" => "applicationsolution",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"icon" => "../modules/itop-config-mgmt-1.0.0/images/solution.png",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeText("description", array("allowed_values"=>null, "sql"=>"description", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("ci_list", array("linked_class"=>"lnkSolutionToCI", "ext_key_to_me"=>"solution_id", "ext_key_to_remote"=>"ci_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("process_list", array("linked_class"=>"lnkProcessToSolution", "ext_key_to_me"=>"solution_id", "ext_key_to_remote"=>"process_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('name', 'status', 'org_id', 'importance', 'description', 'contact_list', 'document_list', 'solution_list', 'contract_list', 'ticket_list', 'ci_list', 'process_list'));
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'status', 'org_id', 'importance'));
		MetaModel::Init_SetZListItems('standard_search', array('name', 'status', 'org_id', 'importance'));
		MetaModel::Init_SetZListItems('list', array('status', 'org_id', 'importance'));
	}
	
	public static function GetRelationQueries($sRelCode)
	{
		switch ($sRelCode)
		{
			case "impacts":
			$aRels = array(
				"process" => array("sQuery"=>"SELECT BusinessProcess AS p JOIN lnkProcessToSolution AS l1 ON l1.process_id = p.id WHERE l1.solution_id = :this->id", "bPropagate"=>true, "iDistance"=>3),
			);
			return array_merge($aRels, parent::GetRelationQueries($sRelCode));
			break;
			
			case "depends on":
			$aRels = array(
				"solution" => array("sQuery"=>"SELECT FunctionalCI AS ci JOIN lnkSolutionToCI AS l1 ON l1.ci_id = ci.id WHERE l1.solution_id = :this->id", "bPropagate"=>true, "iDistance"=>2),
			);
			return array_merge($aRels, parent::GetRelationQueries($sRelCode));
			break;
						
			default:
			return parent::GetRelationQueries($sRelCode);			
		}
	}
}
class BusinessProcess extends FunctionalCI
{

	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,configmgmt",
			"key_type" => "autoincrement",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array("name","org_id","owner_name"),
			"db_table" => "businessprocess",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"icon" => "../modules/itop-config-mgmt-1.0.0/images/business-process.png",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeText("description", array("allowed_values"=>null, "sql"=>"description", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("used_solution_list", array("linked_class"=>"lnkProcessToSolution", "ext_key_to_me"=>"process_id", "ext_key_to_remote"=>"solution_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('name', 'status', 'org_id', 'importance', 'description', 'contact_list', 'document_list', 'contract_list', 'ticket_list', 'used_solution_list'));
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'status', 'org_id', 'importance', 'description'));
		MetaModel::Init_SetZListItems('standard_search', array('name', 'status', 'org_id', 'importance', 'description'));
		MetaModel::Init_SetZListItems('list', array('status', 'org_id', 'importance'));
	}
	
	public static function GetRelationQueries($sRelCode)
	{
		switch ($sRelCode)
		{
			case "depends on":
			$aRels = array(
				"solution" => array("sQuery"=>"SELECT ApplicationSolution AS app JOIN lnkProcessToSolution AS l1 ON l1.solution_id = app.id WHERE l1.process_id = :this->id", "bPropagate"=>true, "iDistance"=>3),
			);
			return array_merge($aRels, parent::GetRelationQueries($sRelCode));
			break;
			
			default:
			return parent::GetRelationQueries($sRelCode);			
		}
	}
}
abstract class ConnectableCI extends FunctionalCI
{

	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,configmgmt",
			"key_type" => "autoincrement",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array("name","org_id","owner_name"),
			"db_table" => "connectableci",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeString("brand", array("allowed_values"=>null, "sql"=>"brand", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("model", array("allowed_values"=>null, "sql"=>"model", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("serial_number", array("allowed_values"=>null, "sql"=>"serial_number", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("asset_ref", array("allowed_values"=>null, "sql"=>"asset_ref", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('name', 'status', 'org_id', 'importance', 'brand', 'model', 'serial_number', 'asset_ref', 'contact_list', 'document_list', 'solution_list', 'contract_list', 'ticket_list'));
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'status', 'org_id', 'importance', 'brand', 'model', 'serial_number', 'asset_ref'));
		MetaModel::Init_SetZListItems('standard_search', array('name', 'status', 'org_id', 'importance', 'brand', 'model', 'serial_number', 'asset_ref'));
		MetaModel::Init_SetZListItems('list', array('finalclass', 'status', 'org_id', 'importance', 'brand', 'model', 'serial_number', 'asset_ref'));
	}
}
class NetworkInterface extends ConnectableCI
{

	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,configmgmt",
			"key_type" => "autoincrement",
			"name_attcode" => array('device_id_friendlyname', 'name'),
			"state_attcode" => "",
			"reconc_keys" => array("name","device_id","device_name","org_id"),
			"db_table" => "networkinterface",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"icon" => "../modules/itop-config-mgmt-1.0.0/images/interface.png",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeExternalKey("device_id", array("targetclass"=>"Device", "jointype"=>null, "allowed_values"=>new ValueSetObjects('SELECT Device WHERE org_id = :this->org_id'), "sql"=>"device_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array("org_id"))));
		MetaModel::Init_AddAttribute(new AttributeExternalField("device_name", array("allowed_values"=>null, "extkey_attcode"=>"device_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("logical_type", array("allowed_values"=>new ValueSetEnum('backup,logical,port,primary,secondary'), "sql"=>"logical_type", "default_value"=>"primary", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("physical_type", array("allowed_values"=>new ValueSetEnum('atm,ethernet,framerelay,vlan'), "sql"=>"physical_type", "default_value"=>"ethernet", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeIPAddress("ip_address", array("allowed_values"=>null, "sql"=>"ip_address", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeIPAddress("ip_mask", array("allowed_values"=>null, "sql"=>"ip_mask", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
//		With a validation pattern for '00:1a:4b:68:e3:97'
//		MetaModel::Init_AddAttribute(new AttributeString("mac_address", array("allowed_values"=>null, "sql"=>"mac_address", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array(), "validation_pattern"=>"^[0-9a-f]{2}:[0-9a-f]{2}:[0-9a-f]{2}:[0-9a-f]{2}:[0-9a-f]{2}:[0-9a-f]{2}$")));
		MetaModel::Init_AddAttribute(new AttributeString("mac_address", array("allowed_values"=>null, "sql"=>"mac_address", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array(), "validation_pattern"=>"")));
		MetaModel::Init_AddAttribute(new AttributeInteger("speed", array("allowed_values"=>null, "sql"=>"speed", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("duplex", array("allowed_values"=>new ValueSetEnum('full,half,auto,unknown'), "sql"=>"duplex", "default_value"=>"full", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("connected_if", array("targetclass"=>"NetworkInterface", "jointype"=>null, "allowed_values"=>new ValueSetObjects('SELECT NetworkInterface WHERE org_id = :this->org_id'), "sql"=>"connected_if", "is_null_allowed"=>true, "on_target_delete"=>DEL_AUTO, "depends_on"=>array("org_id"))));
		MetaModel::Init_AddAttribute(new AttributeExternalField("connected_name", array("allowed_values"=>null, "extkey_attcode"=>"connected_if", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("connected_if_device_id", array("allowed_values"=>null, "extkey_attcode"=>"connected_if", "target_attcode"=>"device_id", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("connected_if_device_id_name", array("allowed_values"=>null, "extkey_attcode"=>"connected_if", "target_attcode"=>"device_name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("link_type", array("allowed_values"=>new ValueSetEnum('uplink,downlink'), "sql"=>"link_type", "default_value"=>"uplink", "is_null_allowed"=>false, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('name', 'status', 'org_id', 'importance', 'brand', 'model', 'serial_number', 'asset_ref', 'device_id', 'logical_type', 'physical_type', 'ip_address', 'ip_mask', 'mac_address', 'speed', 'duplex', 'link_type', 'connected_if', 'connected_if_device_id', 'contact_list', 'document_list', 'solution_list', 'contract_list', 'ticket_list'));
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'status', 'org_id', 'importance', 'brand', 'model', 'serial_number', 'asset_ref', 'device_id', 'logical_type', 'physical_type', 'ip_address', 'ip_mask', 'mac_address', 'speed', 'duplex', 'connected_if', 'connected_if_device_id'));
		MetaModel::Init_SetZListItems('standard_search', array('name', 'status', 'org_id', 'importance', 'device_id', 'logical_type', 'physical_type', 'ip_address', 'ip_mask', 'mac_address', 'connected_if_device_id'));
		MetaModel::Init_SetZListItems('list', array('status', 'ip_address', 'importance', 'device_id', 'logical_type', 'physical_type', 'link_type', 'connected_if_device_id'));
	}

	public static function GetRelationQueries($sRelCode)
	{
		switch ($sRelCode)
		{
			case "impacts":
			$aRels = array(
				"connected_devices" => array("sQuery"=>"SELECT Device AS dev JOIN NetworkInterface AS if1 ON if1.device_id = dev.id JOIN NetworkInterface AS if2 ON if2.connected_if = if1.id WHERE if2.id = :this->id AND if2.link_type='downlink'", "bPropagate"=>true, "iDistance"=>5),
			);
			return array_merge($aRels, parent::GetRelationQueries($sRelCode));
			break;
			
			default:
			return parent::GetRelationQueries($sRelCode);			
		}
	}

	protected function UpdateConnectedInterface()
	{
		$oConnIf = MetaModel::GetObject('NetworkInterface', $this->Get('connected_if'), false /* no exception if not found */);
		if (!is_null($oConnIf))
		{
			$sLink = $this->Get('link_type');
			$sConnLink = ($sLink == 'uplink') ? 'downlink' : 'uplink';

			if (($oConnIf->Get('connected_if') != $this->GetKey()) || ($sConnLink != $oConnIf->Get('link_type')))
			{
				// Something has to be changed on the connected interface...
				if ($oConnIf->Get('connected_if') != $this->GetKey())
				{
					// It is connected to another interface: reset that third one...
					$oThirdIf = MetaModel::GetObject('NetworkInterface', $oConnIf->Get('connected_if'), false);
					if (!is_null($oThirdIf))
					{
						$oThirdIf->Set('connected_if', 0);			
						// Need to backup the current change, because it is reset when DBUpdateTracked is complete
						$oCurrChange = self::$m_oCurrChange;
						$oThirdIf->DBUpdateTracked($oCurrChange);
						self::$m_oCurrChange = $oCurrChange;
					}
				}
				// Connect the remote interface to the current one
				$oConnIf->Set('connected_if', $this->GetKey());
				$oConnIf->Set('link_type', $sConnLink);

				// Need to backup the current change, because it is reset when DBUpdateTracked is complete
				$oCurrChange = self::$m_oCurrChange;
				$oConnIf->DBUpdateTracked($oCurrChange);
				self::$m_oCurrChange = $oCurrChange;
			}
		}
	}

	protected function AfterInsert()
	{
		$this->UpdateConnectedInterface();
		parent::AfterInsert();
	}

	protected function AfterUpdate()
	{
		$this->UpdateConnectedInterface();
		parent::AfterUpdate();
	}

}
abstract class Device extends ConnectableCI
{

	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,configmgmt",
			"key_type" => "autoincrement",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array("name","org_id","owner_name"),
			"db_table" => "device",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"icon" => "../modules/itop-config-mgmt-1.0.0/images/server.png",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeLinkedSet("nwinterface_list", array("linked_class"=>"NetworkInterface", "ext_key_to_me"=>"device_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('name', 'status', 'org_id', 'importance', 'brand', 'model', 'serial_number', 'asset_ref', 'contact_list', 'document_list', 'solution_list', 'contract_list', 'ticket_list', 'nwinterface_list'));
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'status', 'org_id', 'importance', 'brand', 'model', 'serial_number', 'asset_ref'));
		MetaModel::Init_SetZListItems('standard_search', array('name', 'status', 'org_id', 'importance', 'brand', 'model', 'serial_number', 'asset_ref'));
		MetaModel::Init_SetZListItems('list', array('finalclass', 'status', 'org_id', 'importance', 'brand', 'model', 'serial_number', 'asset_ref'));
	}
	
	public static function GetRelationQueries($sRelCode)
	{
		switch ($sRelCode)
		{
			case "impacts":
			$aRels = array(
				"applications" => array("sQuery"=>"SELECT SoftwareInstance AS app WHERE app.device_id = :this->id", "bPropagate"=>true, "iDistance"=>5),
				"connected_devices" => array("sQuery"=>"SELECT Device AS dev JOIN NetworkInterface AS if1 ON if1.device_id = dev.id JOIN NetworkInterface AS if2 ON if2.connected_if = if1.id WHERE if2.device_id = :this->id AND if2.link_type='downlink'", "bPropagate"=>true, "iDistance"=>5),
			);
			return array_merge($aRels, parent::GetRelationQueries($sRelCode));
			break;
			
			case "depends on":
			$aRels = array(
				"connected_devices" => array("sQuery"=>"SELECT Device AS dev JOIN NetworkInterface AS if1 ON if1.device_id = dev.id JOIN NetworkInterface AS if2 ON if2.connected_if = if1.id WHERE if2.device_id = :this->id AND if2.link_type='uplink'", "bPropagate"=>true, "iDistance"=>5),
			);
			return array_merge($aRels, parent::GetRelationQueries($sRelCode));
			break;

			default:
			return parent::GetRelationQueries($sRelCode);			
		}
	}
}
class PC extends Device
{

	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,configmgmt",
			"key_type" => "autoincrement",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array("name","org_id","owner_name"),
			"db_table" => "pc",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"icon" => "../modules/itop-config-mgmt-1.0.0/images/laptop.png",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeString("cpu", array("allowed_values"=>null, "sql"=>"cpu", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("ram", array("allowed_values"=>null, "sql"=>"ram", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("hdd", array("allowed_values"=>null, "sql"=>"hdd", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("os_family", array("allowed_values"=>null, "sql"=>"os_family", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("os_version", array("allowed_values"=>null, "sql"=>"os_version", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSet("application_list", array("linked_class"=>"SoftwareInstance", "ext_key_to_me"=>"device_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("patch_list", array("linked_class"=>"lnkPatchToCI", "ext_key_to_me"=>"ci_id", "ext_key_to_remote"=>"patch_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('name', 'status', 'org_id', 'importance', 'brand', 'model', 'serial_number', 'asset_ref', 'cpu', 'ram', 'hdd', 'os_family', 'os_version', 'contact_list', 'document_list', 'solution_list', 'contract_list', 'ticket_list', 'nwinterface_list', 'application_list', 'patch_list'));
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'status', 'org_id', 'importance', 'brand', 'model', 'serial_number', 'asset_ref', 'cpu', 'ram', 'hdd', 'os_family', 'os_version'));
		MetaModel::Init_SetZListItems('standard_search', array('name', 'status', 'org_id', 'importance', 'brand', 'model', 'serial_number', 'asset_ref', 'os_family', 'os_version'));
		MetaModel::Init_SetZListItems('list', array('status', 'org_id', 'importance', 'brand', 'model', 'os_family'));
	}
}
abstract class MobileCI extends Device
{

	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,configmgmt",
			"key_type" => "autoincrement",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array("name","org_id","owner_name"),
			"db_table" => "mobileci",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"icon" => "../modules/itop-config-mgmt-1.0.0/images/mobile-phone.png",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();


		MetaModel::Init_SetZListItems('details', array('name', 'status', 'org_id', 'importance', 'brand', 'model', 'serial_number', 'asset_ref', 'contact_list', 'document_list', 'solution_list', 'contract_list', 'ticket_list', 'nwinterface_list'));
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'status', 'org_id', 'importance', 'brand', 'model', 'serial_number', 'asset_ref'));
		MetaModel::Init_SetZListItems('standard_search', array('name', 'status', 'org_id', 'importance', 'brand', 'model', 'serial_number', 'asset_ref'));
		MetaModel::Init_SetZListItems('list', array('status', 'org_id', 'importance', 'brand', 'model'));
	}
}
class MobilePhone extends MobileCI
{

	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,configmgmt",
			"key_type" => "autoincrement",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array("name","org_id","owner_name"),
			"db_table" => "mobilephone",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"icon" => "../modules/itop-config-mgmt-1.0.0/images/mobile-phone.png",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeString("number", array("allowed_values"=>null, "sql"=>"number", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("imei", array("allowed_values"=>null, "sql"=>"IMIE", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("hw_pin", array("allowed_values"=>null, "sql"=>"hw_pin", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('name', 'status', 'org_id', 'importance', 'brand', 'model', 'serial_number', 'asset_ref', 'number', 'imei', 'hw_pin', 'contact_list', 'document_list', 'solution_list', 'contract_list', 'ticket_list', 'nwinterface_list'));
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'status', 'org_id', 'importance', 'brand', 'model', 'serial_number', 'asset_ref', 'number', 'imei'));
		MetaModel::Init_SetZListItems('standard_search', array('name', 'status', 'org_id', 'importance', 'brand', 'model', 'serial_number', 'asset_ref', 'number', 'imei'));
		MetaModel::Init_SetZListItems('list', array('status', 'org_id', 'importance', 'brand', 'model'));
	}
}
abstract class InfrastructureCI extends Device
{

	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,configmgmt",
			"key_type" => "autoincrement",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array("name","org_id","owner_name","location_id","location_name"),
			"db_table" => "infrastructureci",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"icon" => "../modules/itop-config-mgmt-1.0.0/images/server.png",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeText("description", array("allowed_values"=>null, "sql"=>"description", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("location_id", array("targetclass"=>"Location", "jointype"=>null, "allowed_values"=>new ValueSetObjects('SELECT Location AS l WHERE l.org_id = :this->org_id'), "sql"=>"location_id", "is_null_allowed"=>true, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array("org_id"))));
		MetaModel::Init_AddAttribute(new AttributeExternalField("location_name", array("allowed_values"=>null, "extkey_attcode"=>"location_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeText("location_details", array("allowed_values"=>null, "sql"=>"location_details", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeIPAddress("management_ip", array("allowed_values"=>null, "sql"=>"management_ip", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("default_gateway", array("allowed_values"=>null, "sql"=>"default_gateway", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('name', 'status', 'org_id', 'importance', 'brand', 'model', 'serial_number', 'asset_ref', 'description', 'location_id', 'location_details', 'management_ip', 'default_gateway', 'contact_list', 'document_list', 'solution_list', 'contract_list', 'ticket_list', 'nwinterface_list'));
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'status', 'org_id', 'importance', 'brand', 'model', 'serial_number', 'asset_ref', 'description', 'location_id', 'location_details', 'management_ip', 'default_gateway'));
		MetaModel::Init_SetZListItems('standard_search', array('name', 'status', 'org_id', 'importance', 'brand', 'model', 'serial_number', 'asset_ref','location_id','management_ip', 'default_gateway'));
		MetaModel::Init_SetZListItems('list', array('status', 'org_id', 'importance', 'brand', 'model', 'location_id'));
	}
}
class NetworkDevice extends InfrastructureCI
{

	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,configmgmt",
			"key_type" => "autoincrement",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array("name","org_id","owner_name","location_id","location_name"),
			"db_table" => "networkdevice",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
			"icon" => "../modules/itop-config-mgmt-1.0.0/images/switch.png",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeEnum("type", array("allowed_values"=>new ValueSetEnum('wanaccelerator,firewall,hub,loadbalancer,router,switch'), "sql"=>"type", "default_value"=>"switch", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("ios_version", array("allowed_values"=>null, "sql"=>"ios_version", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("ram", array("allowed_values"=>null, "sql"=>"ram", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("snmp_read", array("allowed_values"=>null, "sql"=>"snmp_read", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("snmp_write", array("allowed_values"=>null, "sql"=>"snmp_write", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('name', 'status', 'org_id', 'importance', 'brand', 'model', 'serial_number', 'asset_ref', 'description', 'location_id', 'location_details', 'management_ip', 'default_gateway', 'type', 'ios_version', 'ram', 'snmp_read', 'snmp_write', 'contact_list', 'document_list', 'solution_list', 'contract_list', 'ticket_list', 'nwinterface_list'));
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'status', 'org_id', 'importance', 'brand', 'model', 'serial_number', 'asset_ref', 'description', 'location_id', 'location_details', 'management_ip', 'default_gateway', 'type', 'ios_version', 'ram'));
		MetaModel::Init_SetZListItems('standard_search', array('name', 'status', 'org_id', 'importance', 'brand', 'model', 'serial_number', 'asset_ref', 'location_id','management_ip', 'default_gateway', 'type', 'ios_version'));
		MetaModel::Init_SetZListItems('list', array('status', 'org_id', 'importance', 'brand', 'model', 'location_id', 'type'));
	}
}
class Server extends InfrastructureCI
{

	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,configmgmt",
			"key_type" => "autoincrement",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array("name","org_id","owner_name","location_id","location_name"),
			"db_table" => "server",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"icon" => "../modules/itop-config-mgmt-1.0.0/images/server.png",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeString("cpu", array("allowed_values"=>null, "sql"=>"cpu", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("ram", array("allowed_values"=>null, "sql"=>"ram", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("hdd", array("allowed_values"=>null, "sql"=>"hdd", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("os_family", array("allowed_values"=>null, "sql"=>"os_family", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("os_version", array("allowed_values"=>null, "sql"=>"os_version", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSet("application_list", array("linked_class"=>"SoftwareInstance", "ext_key_to_me"=>"device_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("patch_list", array("linked_class"=>"lnkPatchToCI", "ext_key_to_me"=>"ci_id", "ext_key_to_remote"=>"patch_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('name', 'status', 'org_id', 'importance', 'brand', 'model', 'serial_number', 'asset_ref', 'description', 'location_id', 'location_details', 'management_ip', 'default_gateway', 'cpu', 'ram', 'hdd', 'os_family', 'os_version', 'contact_list', 'document_list', 'solution_list', 'contract_list', 'ticket_list', 'nwinterface_list', 'application_list', 'patch_list'));
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'status', 'org_id', 'importance', 'brand', 'model', 'serial_number', 'asset_ref', 'description', 'location_id', 'location_details', 'management_ip', 'default_gateway', 'cpu', 'ram', 'hdd', 'os_family', 'os_version'));
		MetaModel::Init_SetZListItems('standard_search', array('name', 'status', 'org_id', 'importance', 'brand', 'model', 'serial_number', 'asset_ref', 'location_id', 'management_ip', 'default_gateway', 'os_family', 'os_version'));
		MetaModel::Init_SetZListItems('list', array('status', 'org_id', 'importance', 'brand', 'model', 'location_id', 'os_family'));
	}
}
class Printer extends InfrastructureCI
{

	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,configmgmt",
			"key_type" => "autoincrement",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array("name","org_id","owner_name","location_id","location_name"),
			"db_table" => "printer",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"icon" => "../modules/itop-config-mgmt-1.0.0/images/printer.png",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeEnum("type", array("allowed_values"=>new ValueSetEnum('mopier,printer'), "sql"=>"type", "default_value"=>"printer", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("technology", array("allowed_values"=>new ValueSetEnum('laser,inkjet,tracer'), "sql"=>"technology", "default_value"=>"laser", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('name', 'status', 'org_id', 'importance', 'brand', 'model', 'serial_number', 'asset_ref', 'description', 'location_id', 'location_details', 'management_ip', 'default_gateway', 'type', 'technology', 'contact_list', 'document_list', 'solution_list', 'contract_list', 'ticket_list', 'nwinterface_list'));
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'status', 'org_id', 'importance', 'brand', 'model', 'serial_number', 'asset_ref', 'description', 'location_id', 'location_details', 'management_ip', 'default_gateway', 'type', 'technology'));
		MetaModel::Init_SetZListItems('standard_search', array('name', 'status', 'org_id', 'importance', 'brand', 'model', 'serial_number', 'asset_ref', 'description', 'location_id', 'location_details', 'management_ip', 'default_gateway', 'type', 'technology'));
		MetaModel::Init_SetZListItems('list', array('status', 'org_id', 'importance', 'brand', 'model', 'serial_number', 'asset_ref', 'location_id',  'management_ip', 'default_gateway', 'type', 'technology'));
	}
}
class lnkCIToDoc extends cmdbAbstractObject
{

	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,configmgmt",
			"key_type" => "autoincrement",
			"name_attcode" => "ci_id",
			"state_attcode" => "",
			"reconc_keys" => array("ci_id","document_id"),
			"db_table" => "lnkcitodoc",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeExternalKey("ci_id", array("targetclass"=>"FunctionalCI", "jointype"=>null, "allowed_values"=>null, "sql"=>"ci_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("ci_name", array("allowed_values"=>null, "extkey_attcode"=>"ci_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("ci_status", array("allowed_values"=>null, "extkey_attcode"=>"ci_id", "target_attcode"=>"status", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("document_id", array("targetclass"=>"Document", "jointype"=>null, "allowed_values"=>null, "sql"=>"document_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("document_name", array("allowed_values"=>null, "extkey_attcode"=>"document_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("document_type", array("allowed_values"=>null, "extkey_attcode"=>"document_id", "target_attcode"=>"type", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("document_status", array("allowed_values"=>null, "extkey_attcode"=>"document_id", "target_attcode"=>"status", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('ci_id', 'ci_status', 'document_id', 'document_type', 'document_status'));
		MetaModel::Init_SetZListItems('advanced_search', array('ci_id', 'document_id'));
		MetaModel::Init_SetZListItems('standard_search', array('ci_id', 'document_id'));
		MetaModel::Init_SetZListItems('list', array('ci_id', 'ci_status', 'document_id', 'document_type', 'document_status'));
	}
}
class lnkCIToContact extends cmdbAbstractObject
{

	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,configmgmt",
			"key_type" => "autoincrement",
			"name_attcode" => "ci_id",
			"state_attcode" => "",
			"reconc_keys" => array("ci_id","contact_id"),
			"db_table" => "lnkcitocontact",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"icon" => "../modules/itop-config-mgmt-1.0.0/images/contact.png",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeExternalKey("ci_id", array("targetclass"=>"FunctionalCI", "jointype"=>null, "allowed_values"=>null, "sql"=>"ci_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("ci_name", array("allowed_values"=>null, "extkey_attcode"=>"ci_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("ci_status", array("allowed_values"=>null, "extkey_attcode"=>"ci_id", "target_attcode"=>"status", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("contact_id", array("targetclass"=>"Contact", "jointype"=>null, "allowed_values"=>null, "sql"=>"contact_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("contact_name", array("allowed_values"=>null, "extkey_attcode"=>"contact_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("contact_email", array("allowed_values"=>null, "extkey_attcode"=>"contact_id", "target_attcode"=>"email", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("role", array("allowed_values"=>null, "sql"=>"role", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('ci_id', 'ci_status', 'contact_id', 'contact_email', 'role'));
		MetaModel::Init_SetZListItems('advanced_search', array('ci_id', 'contact_id', 'role'));
		MetaModel::Init_SetZListItems('standard_search', array('ci_id', 'contact_id', 'role'));
		MetaModel::Init_SetZListItems('list', array('ci_id', 'ci_status', 'contact_id', 'contact_email', 'role'));
	}
}
class lnkSolutionToCI extends cmdbAbstractObject
{

	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,configmgmt",
			"key_type" => "autoincrement",
			"name_attcode" => "solution_id",
			"state_attcode" => "",
			"reconc_keys" => array("solution_id","ci_id"),
			"db_table" => "lnksolutiontoci",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeExternalKey("solution_id", array("targetclass"=>"ApplicationSolution", "jointype"=>null, "allowed_values"=>null, "sql"=>"solution_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("solution_name", array("allowed_values"=>null, "extkey_attcode"=>"solution_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("ci_id", array("targetclass"=>"FunctionalCI", "jointype"=>null, "allowed_values"=>null, "sql"=>"ci_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("ci_name", array("allowed_values"=>null, "extkey_attcode"=>"ci_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("ci_status", array("allowed_values"=>null, "extkey_attcode"=>"ci_id", "target_attcode"=>"status", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("utility", array("allowed_values"=>null, "sql"=>"utility", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('solution_id', 'ci_id', 'ci_status', 'utility'));
		MetaModel::Init_SetZListItems('advanced_search', array('solution_id', 'ci_id', 'utility'));
		MetaModel::Init_SetZListItems('standard_search', array('solution_id', 'ci_id', 'utility'));
		MetaModel::Init_SetZListItems('list', array('solution_id', 'ci_id', 'ci_status', 'utility'));
	}
}
class lnkProcessToSolution extends cmdbAbstractObject
{

	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,configmgmt",
			"key_type" => "autoincrement",
			"name_attcode" => "solution_id",
			"state_attcode" => "",
			"reconc_keys" => array("solution_id","process_id"),
			"db_table" => "lnkprocesstosolution",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeExternalKey("solution_id", array("targetclass"=>"ApplicationSolution", "jointype"=>null, "allowed_values"=>null, "sql"=>"solution_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("solution_name", array("allowed_values"=>null, "extkey_attcode"=>"solution_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("process_id", array("targetclass"=>"BusinessProcess", "jointype"=>null, "allowed_values"=>null, "sql"=>"process_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("process_name", array("allowed_values"=>null, "extkey_attcode"=>"process_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("reason", array("allowed_values"=>null, "sql"=>"reason", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('solution_id', 'process_id', 'reason'));
		MetaModel::Init_SetZListItems('advanced_search', array('solution_id', 'process_id', 'reason'));
		MetaModel::Init_SetZListItems('standard_search', array('solution_id', 'process_id', 'reason'));
		MetaModel::Init_SetZListItems('list', array('solution_id', 'process_id', 'reason'));
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


// Note (RQ) :
// After 1.0.1, the welcome page and menus have been removed from the application
// and put into a separate module "itop-welcome-itil"
// Until we develop a migration utility, and as would like to preserve the
// capability to upgrade iTop without any manual operation, we have decided to
// implement this dirty workaround that makes it...
require_once(APPROOT.'modules/itop-welcome-itil/model.itop-welcome-itil.php');


$oAdminMenu = new MenuGroup('DataAdministration', 70 /* fRank */, 'Organization', UR_ACTION_MODIFY, UR_ALLOWED_YES|UR_ALLOWED_DEPENDS);
$iAdminGroup = $oAdminMenu->GetIndex();

new WebPageMenuNode('Audit', '../pages/audit.php', $iAdminGroup, 33 /* fRank */);

$oTypologyNode = new TemplateMenuNode('Catalogs', '', $iAdminGroup, 50 /* fRank */);
$iTopology = $oTypologyNode->GetIndex();
new OQLMenuNode('Organization', 'SELECT Organization', $iTopology, 10 /* fRank */, true /* bSearch */);
new OQLMenuNode('Application', 'SELECT Application', $iTopology, 20 /* fRank */);
new OQLMenuNode('DBServer', 'SELECT DBServer', $iTopology, 40 /* fRank */);


$oConfigManagementGroup = new MenuGroup('ConfigManagement', 20 /* fRank */);

// Create an entry, based on a custom template, for the Configuration management overview, under the top-level group
new TemplateMenuNode('ConfigManagementOverview', '../modules/itop-config-mgmt-1.0.0/overview.html', $oConfigManagementGroup->GetIndex(), 0 /* fRank */);


$oContactNode = new TemplateMenuNode('Contact', '../modules/itop-config-mgmt-1.0.0/contacts_menu.html', $oConfigManagementGroup->GetIndex(), 1 /* fRank */);
new NewObjectMenuNode('NewContact', 'Contact', $oContactNode->GetIndex(), 1 /* fRank */);
new SearchMenuNode('SearchContacts', 'Contact', $oContactNode->GetIndex(), 2 /* fRank */);

new OQLMenuNode('Document', 'SELECT Document', $oConfigManagementGroup->GetIndex(), 2 /* fRank */, true /* bSearch */);
new OQLMenuNode('Location', 'SELECT Location', $oConfigManagementGroup->GetIndex(), 3 /* fRank */, true /* bSearch */);
new OQLMenuNode('Group', 'SELECT Group', $oConfigManagementGroup->GetIndex(), 4 /* fRank */, true /* bSearch */);


$oCINode = new TemplateMenuNode('ConfigManagementCI', '../modules/itop-config-mgmt-1.0.0/cis_menu.html', $oConfigManagementGroup->GetIndex(), 5 /* fRank */);
new NewObjectMenuNode('NewCI', 'FunctionalCI', $oCINode->GetIndex(), 0 /* fRank */);
new SearchMenuNode('SearchCIs', 'FunctionalCI', $oCINode->GetIndex(), 1 /* fRank */);

$oShortcutsNode = new TemplateMenuNode('ConfigManagement:Shortcuts', '', $oConfigManagementGroup->GetIndex(), 6 /* fRank */);
new OQLMenuNode('Server', 'SELECT Server', $oShortcutsNode->GetIndex(), 1 /* fRank */);
new OQLMenuNode('NetworkDevice', 'SELECT NetworkDevice', $oShortcutsNode->GetIndex(), 2 /* fRank */);
new OQLMenuNode('Printer', 'SELECT Printer', $oShortcutsNode->GetIndex(), 3 /* fRank */);
new OQLMenuNode('PC', 'SELECT PC', $oShortcutsNode->GetIndex(), 4 /* fRank */);
new OQLMenuNode('BusinessProcess', 'SELECT BusinessProcess', $oShortcutsNode->GetIndex(), 5 /* fRank */);
new OQLMenuNode('ApplicationSolution', 'SELECT ApplicationSolution', $oShortcutsNode->GetIndex(), 6 /* fRank */);

?>

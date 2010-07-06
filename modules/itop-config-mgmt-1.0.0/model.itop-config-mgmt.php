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

require_once('../application/cmdbabstract.class.inc.php');
require_once('../application/template.class.inc.php');

MetaModel::RegisterRelation("impacts", array("description"=>"Objects impacted by", "verb_down"=>"impacts", "verb_up"=>"is impacted by"));

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
			"reconc_keys" => array("name"),
			"db_table" => "location",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"icon" => "../business/templates/location.png",
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
		MetaModel::Init_AddAttribute(new AttributeExternalKey("parent_id", array("targetclass"=>"Location", "jointype"=>null, "allowed_values"=>null, "sql"=>"parent_id", "is_null_allowed"=>true, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
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
			"reconc_keys" => array("name"),
			"db_table" => "contact",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"icon" => "../business/templates/team.png",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeString("name", array("allowed_values"=>null, "sql"=>"name", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("status", array("allowed_values"=>new ValueSetEnum('active,inactive'), "sql"=>"status", "default_value"=>"active", "is_null_allowed"=>true, "depends_on"=>array())));
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
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array("name"),
			"db_table" => "person",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"icon" => "../business/templates/person.png",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeString("first_name", array("allowed_values"=>null, "sql"=>"first_name", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("employee_id", array("allowed_values"=>null, "sql"=>"employee_id", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('first_name', 'name', 'org_id', 'status', 'location_id', 'email', 'phone', 'employee_id', 'contract_list', 'service_list', 'ticket_list', 'ci_list', 'team_list'));
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'status', 'org_id', 'email', 'phone', 'location_id', 'first_name', 'employee_id'));
		MetaModel::Init_SetZListItems('standard_search', array('name', 'status', 'org_id', 'email', 'phone', 'location_id', 'first_name', 'employee_id'));
		MetaModel::Init_SetZListItems('list', array('status', 'org_id', 'email', 'phone', 'location_id'));
	}
	
	public function GetName()
	{
		return $this->Get('first_name').' '.$this->Get('name');
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
			"reconc_keys" => array("name"),
			"db_table" => "team",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"icon" => "../business/templates/team.png",
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
			"reconc_keys" => array("name"),
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
			"reconc_keys" => array("name"),
			"db_table" => "document",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"icon" => "../business/templates/document.png",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeString("name", array("allowed_values"=>null, "sql"=>"name", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("description", array("allowed_values"=>null, "sql"=>"description", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("type", array("allowed_values"=>new ValueSetEnum('contract,networkmap,presentation,training,whitePaper,workinginstructions'), "sql"=>"type", "default_value"=>"presentation", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("status", array("allowed_values"=>new ValueSetEnum('draft,published,obsolete'), "sql"=>"status", "default_value"=>"draft", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("contract_list", array("linked_class"=>"lnkContractToDoc", "ext_key_to_me"=>"document_id", "ext_key_to_remote"=>"contract_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("service_list", array("linked_class"=>"lnkServiceToDoc", "ext_key_to_me"=>"document_id", "ext_key_to_remote"=>"service_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("ticket_list", array("linked_class"=>"lnkTicketToDoc", "ext_key_to_me"=>"document_id", "ext_key_to_remote"=>"ticket_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("ci_list", array("linked_class"=>"lnkCIToDoc", "ext_key_to_me"=>"document_id", "ext_key_to_remote"=>"ci_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('name', 'description', 'type', 'status', 'contract_list', 'service_list', 'ticket_list', 'ci_list'));
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'description', 'type', 'status'));
		MetaModel::Init_SetZListItems('standard_search', array('name', 'description', 'type', 'status'));
		MetaModel::Init_SetZListItems('list', array('type', 'status'));
	}
}
class ExternalDoc extends Document
{

	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,documentation",
			"key_type" => "autoincrement",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array("name"),
			"db_table" => "externaldoc",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"icon" => "../business/templates/document.png",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeURL("url", array("target"=>"_blank", "allowed_values"=>null, "sql"=>"url", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('name', 'description', 'type', 'status', 'contract_list', 'service_list', 'ticket_list', 'ci_list', 'url'));
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'description', 'type', 'status', 'url'));
		MetaModel::Init_SetZListItems('standard_search', array('name', 'description', 'type', 'status', 'url'));
		MetaModel::Init_SetZListItems('list', array('type', 'status', 'url'));
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
			"reconc_keys" => array("name"),
			"db_table" => "note",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"icon" => "../business/templates/document.png",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeWikiText("note", array("allowed_values"=>null, "sql"=>"note", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('name', 'description', 'type', 'status', 'contract_list', 'service_list', 'ticket_list', 'ci_list', 'note'));
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'description', 'type', 'status', 'note'));
		MetaModel::Init_SetZListItems('standard_search', array('name', 'description', 'type', 'status', 'note'));
		MetaModel::Init_SetZListItems('list', array('type', 'status', 'note'));
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
			"reconc_keys" => array("name"),
			"db_table" => "filedoc",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"icon" => "../business/templates/document.png",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeBlob("contents", array("depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('name', 'description', 'type', 'status', 'contract_list', 'service_list', 'ticket_list', 'ci_list', 'contents'));
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'description', 'type', 'status'));
		MetaModel::Init_SetZListItems('standard_search', array('name', 'description', 'type', 'status'));
		MetaModel::Init_SetZListItems('list', array('type', 'status', 'contents'));
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
			"reconc_keys" => array("name"),
			"db_table" => "licence",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"icon" => "../business/templates/licence.png",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeString("provider", array("allowed_values"=>null, "sql"=>"provider", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("product", array("allowed_values"=>null, "sql"=>"product", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("name", array("allowed_values"=>null, "sql"=>"name", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeDate("start", array("allowed_values"=>null, "sql"=>"start", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeDate("end", array("allowed_values"=>null, "sql"=>"end", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("licence_key", array("allowed_values"=>null, "sql"=>"licence_key", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeText("scope", array("allowed_values"=>null, "sql"=>"scope", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeInteger("usage_limit", array("allowed_values"=>null, "sql"=>"usage_limit", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSet("usage_list", array("linked_class"=>"SoftwareInstance", "ext_key_to_me"=>"licence_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('provider', 'product', 'name', 'start', 'end', 'licence_key', 'scope', 'usage_limit', 'usage_list'));
		MetaModel::Init_SetZListItems('advanced_search', array('provider', 'product', 'name', 'start', 'end', 'licence_key', 'scope'));
		MetaModel::Init_SetZListItems('standard_search', array('provider', 'product', 'name', 'start', 'end', 'licence_key', 'scope'));
		MetaModel::Init_SetZListItems('list', array('provider', 'product', 'name', 'start', 'end'));
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
			"name_attcode" => "ip",
			"state_attcode" => "",
			"reconc_keys" => array("ip", "ip_mask"),
			"db_table" => "subnet",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"icon" => "../business/templates/subnet.png",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		//MetaModel::Init_AddAttribute(new AttributeString("name", array("allowed_values"=>null, "sql"=>"name", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeWikiText("description", array("allowed_values"=>null, "sql"=>"description", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("org_id", array("targetclass"=>"Organization", "jointype"=>null, "allowed_values"=>null, "sql"=>"org_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("org_name", array("allowed_values"=>null, "extkey_attcode"=>"org_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeIPAddress("ip", array("allowed_values"=>null, "sql"=>"ip", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeIPAddress("ip_mask", array("allowed_values"=>null, "sql"=>"ip_mask", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('ip', 'ip_mask', 'org_id', 'description'));
		MetaModel::Init_SetZListItems('advanced_search', array('ip', 'ip_mask', 'org_id', 'description'));
		MetaModel::Init_SetZListItems('standard_search', array('ip', 'ip_mask', 'org_id', 'description'));
		MetaModel::Init_SetZListItems('list', array('ip', 'ip_mask', 'org_id'));
	}

	public function GetName()
	{
		return $this->Get('ip').' / '.$this->Get('ip_mask');
	}

	function DisplayBareRelations(WebPage $oPage, $bEditMode = false)
	{
		parent::DisplayBareRelations($oPage, $bEditMode);

		if (!$bEditMode)
		{
		$oPage->SetCurrentTab(Dict::S('Class:Subnet/Tab:IPUsage'));

		$bit_ip = ip2long($this->Get('ip'));
		$bit_mask = ip2long($this->Get('ip_mask'));

		$iIPMin = $bit_ip & $bit_mask;
		$iIPMax = ($bit_ip | (~$bit_mask)) - 1;

		$sIPMin = long2ip($iIPMin);
		$sIPMax = long2ip($iIPMax);

		$oPage->p(Dict::Format('Class:Subnet/Tab:IPUsage-explain', $sIPMin, $sIPMax));
		
		$oIfSet = new CMDBObjectSet(DBObjectSearch::FromOQL("SELECT NetworkInterface AS if WHERE INET_ATON(if.ip_address) >= INET_ATON('$sIPMin') AND INET_ATON(if.ip_address) <= INET_ATON('$sIPMax')"));
		self::DisplaySet($oPage, $oIfSet);

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
			"icon" => "../business/templates/patch.png",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeString("name", array("allowed_values"=>null, "sql"=>"name", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeWikiText("description", array("allowed_values"=>null, "sql"=>"description", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("target_sw", array("allowed_values"=>null, "sql"=>"target_sw", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("version", array("allowed_values"=>null, "sql"=>"version", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("type", array("allowed_values"=>new ValueSetEnum('application,os,security,servicepack'), "sql"=>"type", "default_value"=>"security", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("ci_list", array("linked_class"=>"lnkPatchToCI", "ext_key_to_me"=>"patch_id", "ext_key_to_remote"=>"ci_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('name', 'description', 'target_sw', 'version', 'type', 'ci_list'));
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'target_sw', 'version', 'type'));
		MetaModel::Init_SetZListItems('standard_search', array('name', 'target_sw', 'version', 'type'));
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
			"icon" => "../business/templates/software.png",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeString("name", array("allowed_values"=>null, "sql"=>"name", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeWikiText("description", array("allowed_values"=>null, "sql"=>"description", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

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
			"icon" => "../business/templates/software.png",
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
			"icon" => "../business/templates/software.png",
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
			"reconc_keys" => array("name"),
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
			"reconc_keys" => array("name"),
			"db_table" => "functionalci",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"icon" => "../business/templates/server.png",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeString("name", array("allowed_values"=>null, "sql"=>"name", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("status", array("allowed_values"=>new ValueSetEnum('implementation,production,obsolete'), "sql"=>"status", "default_value"=>"implementation", "is_null_allowed"=>true, "depends_on"=>array())));
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
			"name_attcode" => "software_name",
			"state_attcode" => "",
			"reconc_keys" => array("software_id", "device_id"),
			"db_table" => "softwareinstance",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"icon" => "../business/templates/application.png",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeExternalKey("device_id", array("targetclass"=>"Device", "jointype"=>null, "allowed_values"=>null, "sql"=>"device_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("device_name", array("allowed_values"=>null, "extkey_attcode"=>"device_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("licence_id", array("targetclass"=>"Licence", "jointype"=>null, "allowed_values"=>null, "sql"=>"licence_id", "is_null_allowed"=>true, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("licence_name", array("allowed_values"=>null, "extkey_attcode"=>"licence_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("software_id", array("targetclass"=>"Software", "jointype"=>null, "allowed_values"=>null, "sql"=>"software_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("software_name", array("allowed_values"=>null, "extkey_attcode"=>"software_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("version", array("allowed_values"=>null, "sql"=>"version", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeWikiText("description", array("allowed_values"=>null, "sql"=>"description", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('name', 'status', 'org_id', 'importance', 'device_id', 'licence_id', 'software_id', 'version', 'description', 'contact_list', 'document_list', 'solution_list', 'contract_list', 'ticket_list'));
		MetaModel::Init_SetZListItems('advanced_search', array('status', 'org_id', 'importance', 'device_id', 'licence_id', 'software_id', 'version'));
		MetaModel::Init_SetZListItems('standard_search', array('status', 'org_id', 'importance', 'device_id', 'licence_id', 'software_id', 'version'));
		MetaModel::Init_SetZListItems('list', array('finalclass', 'status', 'org_id', 'importance', 'device_id', 'software_id', 'version'));
	}

	public function GetName()
	{
		return $this->Get('name').' - '.$this->Get('device_name');
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
			"name_attcode" => "software_name",
			"state_attcode" => "",
			"reconc_keys" => array("software_id", "device_id"),
			"db_table" => "softwareinstance_dbserver",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"icon" => "../business/templates/application.png",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_OverloadAttributeParams("software_id", array("targetclass"=>"DBServer"));
		//MetaModel::Init_OverloadAttributeParams("software_id", array("allowed_values"=>new ValueSetObjects('SELECT DBServer')));
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
			"name_attcode" => "software_name",
			"state_attcode" => "",
			"reconc_keys" => array("software_id", "device_id"),
			"db_table" => "softwareinstance_application",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"icon" => "../business/templates/application.png",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_OverloadAttributeParams("software_id", array("targetclass"=>"Application"));

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
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array("name"),
			"db_table" => "databaseinstance",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"icon" => "../business/templates/database-instance.png",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeExternalKey("db_server_instance_id", array("targetclass"=>"DBServerInstance", "jointype"=>null, "allowed_values"=>null, "sql"=>"db_server_instance_id", "is_null_allowed"=>true, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("db_server_instance_name", array("allowed_values"=>null, "extkey_attcode"=>"db_server_instance_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("db_server_instance_version", array("allowed_values"=>null, "extkey_attcode"=>"db_server_instance_id", "target_attcode"=>"version", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeWikiText("description", array("allowed_values"=>null, "sql"=>"description", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('name', 'status', 'org_id', 'importance', 'db_server_instance_id', 'db_server_instance_version', 'description', 'contact_list', 'document_list', 'solution_list', 'contract_list', 'ticket_list'));
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'status', 'org_id', 'importance', 'db_server_instance_id', 'db_server_instance_version'));
		MetaModel::Init_SetZListItems('standard_search', array('name', 'status', 'org_id', 'importance', 'db_server_instance_id', 'db_server_instance_version'));
		MetaModel::Init_SetZListItems('list', array('status', 'org_id', 'importance', 'db_server_instance_id', 'db_server_instance_version'));
	}

	public function GetName()
	{
		return $this->Get('name').' - '.$this->Get('db_server_instance_name');
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
			"reconc_keys" => array("name"),
			"db_table" => "applicationsolution",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"icon" => "../business/templates/solution.png",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeWikiText("description", array("allowed_values"=>null, "sql"=>"description", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
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
			"reconc_keys" => array("name"),
			"db_table" => "businessprocess",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"icon" => "../business/templates/business-process.png",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeWikiText("description", array("allowed_values"=>null, "sql"=>"description", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("used_solution_list", array("linked_class"=>"lnkProcessToSolution", "ext_key_to_me"=>"process_id", "ext_key_to_remote"=>"solution_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('name', 'status', 'org_id', 'importance', 'description', 'contact_list', 'document_list', 'solution_list', 'contract_list', 'ticket_list', 'used_solution_list'));
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'status', 'org_id', 'importance', 'description'));
		MetaModel::Init_SetZListItems('standard_search', array('name', 'status', 'org_id', 'importance', 'description'));
		MetaModel::Init_SetZListItems('list', array('status', 'org_id', 'importance', 'description'));
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
			"reconc_keys" => array("name"),
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
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array("name"),
			"db_table" => "networkinterface",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"icon" => "../business/templates/interface.png",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeExternalKey("device_id", array("targetclass"=>"Device", "jointype"=>null, "allowed_values"=>null, "sql"=>"device_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("device_name", array("allowed_values"=>null, "extkey_attcode"=>"device_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("logical_type", array("allowed_values"=>new ValueSetEnum('backup,logical,port,primary,secondary'), "sql"=>"logical_type", "default_value"=>"primary", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("physical_type", array("allowed_values"=>new ValueSetEnum('atm,ethernet,framerelay,vlan'), "sql"=>"physical_type", "default_value"=>"ethernet", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeIPAddress("ip_address", array("allowed_values"=>null, "sql"=>"ip_address", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeIPAddress("ip_mask", array("allowed_values"=>null, "sql"=>"ip_mask", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("mac_address", array("allowed_values"=>null, "sql"=>"mac_address", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeInteger("speed", array("allowed_values"=>null, "sql"=>"speed", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("duplex", array("allowed_values"=>new ValueSetEnum('full,half,unknown'), "sql"=>"duplex", "default_value"=>"full", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("connected_if", array("targetclass"=>"NetworkInterface", "jointype"=>null, "allowed_values"=>null, "sql"=>"connected_if", "is_null_allowed"=>true, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("connected_name", array("allowed_values"=>null, "extkey_attcode"=>"connected_if", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("connected_if_device_id", array("allowed_values"=>null, "extkey_attcode"=>"connected_if", "target_attcode"=>"device_id", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("connected_if_device_id_name", array("allowed_values"=>null, "extkey_attcode"=>"connected_if", "target_attcode"=>"device_name", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('name', 'status', 'org_id', 'importance', 'brand', 'model', 'serial_number', 'asset_ref', 'device_id', 'logical_type', 'physical_type', 'ip_address', 'ip_mask', 'mac_address', 'speed', 'duplex', 'connected_if', 'connected_if_device_id', 'contact_list', 'document_list', 'solution_list', 'contract_list', 'ticket_list'));
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'status', 'org_id', 'importance', 'brand', 'model', 'serial_number', 'asset_ref', 'device_id', 'logical_type', 'physical_type', 'ip_address', 'ip_mask', 'mac_address', 'speed', 'duplex', 'connected_if', 'connected_if_device_id'));
		MetaModel::Init_SetZListItems('standard_search', array('name', 'status', 'org_id', 'importance', 'brand', 'model', 'serial_number', 'asset_ref', 'device_id', 'logical_type', 'physical_type', 'ip_address', 'ip_mask', 'mac_address', 'speed', 'duplex', 'connected_if', 'connected_if_device_id'));
		MetaModel::Init_SetZListItems('list', array('status', 'org_id', 'importance', 'device_id', 'logical_type', 'physical_type', 'connected_if'));
	}

	public function GetName()
	{
		return $this->Get('device_name').' - '.$this->Get('name');
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
			"reconc_keys" => array("name"),
			"db_table" => "device",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"icon" => "../business/templates/server.png",
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
			);
			return array_merge($aRels, parent::GetRelationQueries($sRelCode));
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
			"reconc_keys" => array("name"),
			"db_table" => "pc",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"icon" => "../business/templates/laptop.png",
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
		MetaModel::Init_SetZListItems('standard_search', array('name', 'status', 'org_id', 'importance', 'brand', 'model', 'serial_number', 'asset_ref', 'cpu', 'ram', 'hdd', 'os_family', 'os_version'));
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
			"reconc_keys" => array("name"),
			"db_table" => "mobileci",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"icon" => "../business/templates/mobile-phone.png",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();


		MetaModel::Init_SetZListItems('details', array('name', 'status', 'org_id', 'importance', 'brand', 'model', 'serial_number', 'asset_ref', 'contact_list', 'document_list', 'solution_list', 'contract_list', 'ticket_list', 'nwinterface_list'));
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'status', 'org_id', 'importance', 'brand', 'model', 'serial_number', 'asset_ref'));
		MetaModel::Init_SetZListItems('standard_search', array('name', 'status', 'org_id', 'importance', 'brand', 'model', 'serial_number', 'asset_ref'));
		MetaModel::Init_SetZListItems('list', array('status', 'org_id', 'importance', 'brand', 'model', 'serial_number', 'asset_ref'));
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
			"reconc_keys" => array("name"),
			"db_table" => "mobilephone",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"icon" => "../business/templates/mobile-phone.png",
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
			"reconc_keys" => array("name"),
			"db_table" => "infrastructureci",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"icon" => "../business/templates/server.png",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeWikiText("description", array("allowed_values"=>null, "sql"=>"description", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("location_id", array("targetclass"=>"Location", "jointype"=>null, "allowed_values"=>new ValueSetObjects('SELECT Location AS l WHERE l.org_id = :this->org_id'), "sql"=>"location_id", "is_null_allowed"=>true, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array("org_id"))));
		MetaModel::Init_AddAttribute(new AttributeExternalField("location_name", array("allowed_values"=>null, "extkey_attcode"=>"location_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeText("location_details", array("allowed_values"=>null, "sql"=>"location_details", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeIPAddress("management_ip", array("allowed_values"=>null, "sql"=>"management_ip", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("default_gateway", array("allowed_values"=>null, "sql"=>"default_gateway", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('name', 'status', 'org_id', 'importance', 'brand', 'model', 'serial_number', 'asset_ref', 'description', 'location_id', 'location_details', 'management_ip', 'default_gateway', 'contact_list', 'document_list', 'solution_list', 'contract_list', 'ticket_list', 'nwinterface_list'));
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'status', 'org_id', 'importance', 'brand', 'model', 'serial_number', 'asset_ref', 'description', 'location_id', 'location_details', 'management_ip', 'default_gateway'));
		MetaModel::Init_SetZListItems('standard_search', array('name', 'status', 'org_id', 'importance', 'brand', 'model', 'serial_number', 'asset_ref', 'description', 'location_id', 'location_details', 'management_ip', 'default_gateway'));
		MetaModel::Init_SetZListItems('list', array('name', 'status', 'org_id', 'importance', 'brand', 'model', 'location_id'));
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
			"reconc_keys" => array("name"),
			"db_table" => "networkdevice",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
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
		MetaModel::Init_SetZListItems('standard_search', array('name', 'status', 'org_id', 'importance', 'brand', 'model', 'serial_number', 'asset_ref', 'description', 'location_id', 'location_details', 'management_ip', 'default_gateway', 'type', 'ios_version', 'ram'));
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
			"reconc_keys" => array("name"),
			"db_table" => "server",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"icon" => "../business/templates/server.png",
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
		MetaModel::Init_SetZListItems('standard_search', array('name', 'status', 'org_id', 'importance', 'brand', 'model', 'serial_number', 'asset_ref', 'description', 'location_id', 'location_details', 'management_ip', 'default_gateway', 'cpu', 'ram', 'hdd', 'os_family', 'os_version'));
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
			"reconc_keys" => array("name"),
			"db_table" => "printer",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"icon" => "../business/templates/printer.png",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeEnum("type", array("allowed_values"=>new ValueSetEnum('mopier,printer'), "sql"=>"type", "default_value"=>"printer", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("technology", array("allowed_values"=>new ValueSetEnum('laser,inkjet,tracer'), "sql"=>"technology", "default_value"=>"laser", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('name', 'status', 'org_id', 'importance', 'brand', 'model', 'serial_number', 'asset_ref', 'description', 'location_id', 'location_details', 'management_ip', 'default_gateway', 'type', 'technology', 'contact_list', 'document_list', 'solution_list', 'contract_list', 'ticket_list', 'nwinterface_list'));
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'status', 'org_id', 'importance', 'brand', 'model', 'serial_number', 'asset_ref', 'description', 'location_id', 'location_details', 'management_ip', 'default_gateway', 'type', 'technology'));
		MetaModel::Init_SetZListItems('standard_search', array('name', 'status', 'org_id', 'importance', 'brand', 'model', 'serial_number', 'asset_ref', 'description', 'location_id', 'location_details', 'management_ip', 'default_gateway', 'type', 'technology'));
		MetaModel::Init_SetZListItems('list', array('status', 'org_id', 'importance', 'brand', 'model', 'serial_number', 'asset_ref', 'description', 'location_id', 'location_details', 'management_ip', 'default_gateway', 'type', 'technology'));
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
			"reconc_keys" => array("name"),
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
			"reconc_keys" => array("name"),
			"db_table" => "lnkcitocontact",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"icon" => "../business/templates/contact.png",
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
			"reconc_keys" => array("name"),
			"db_table" => "lnksolutiontoci",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeExternalKey("solution_id", array("targetclass"=>"ApplicationSolution", "jointype"=>null, "allowed_values"=>null, "sql"=>"solution_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("solution_name", array("allowed_values"=>null, "extkey_attcode"=>"solution_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("ci_id", array("targetclass"=>"FunctionalCI", "jointype"=>null, "allowed_values"=>null, "sql"=>"ci_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
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
			"reconc_keys" => array("name"),
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

$oAdminMenu = new MenuGroup('DataAdministration', 999);
$iAdminGroup = $oAdminMenu->GetIndex();

new WebPageMenuNode('Audit', '../pages/audit.php', $iAdminGroup, 33 /* fRank */);

$oTypologyNode = new TemplateMenuNode('Catalogs', '', $iAdminGroup, 50 /* fRank */);
$iTopology = $oTypologyNode->GetIndex();
new OQLMenuNode('Organization', 'SELECT Organization', $iTopology, 10 /* fRank */);
new OQLMenuNode('Application', 'SELECT Application', $iTopology, 20 /* fRank */);
new OQLMenuNode('DBServer', 'SELECT DBServer', $iTopology, 40 /* fRank */);


$oConfigManagementGroup = new MenuGroup('ConfigManagement', 1 /* fRank */);

// Create an entry, based on a custom template, for the Configuration management overview, under the top-level group
new TemplateMenuNode('ConfigManagementOverview', '../business/templates/configuration_management_menu.html', $oConfigManagementGroup->GetIndex(), 0 /* fRank */);


$oContactNode = new TemplateMenuNode('Contact', '../business/templates/contacts_menu.html', $oConfigManagementGroup->GetIndex(), 1 /* fRank */);
new OQLMenuNode('Person', 'SELECT Person', $oContactNode->GetIndex(), 1 /* fRank */);
new OQLMenuNode('Team', 'SELECT Team', $oContactNode->GetIndex(), 2 /* fRank */);

new OQLMenuNode('Document', 'SELECT Document', $oConfigManagementGroup->GetIndex(), 2 /* fRank */);
new OQLMenuNode('Location', 'SELECT Location', $oConfigManagementGroup->GetIndex(), 3 /* fRank */);


$oCINode = new TemplateMenuNode('ConfigManagementCI', '../business/templates/configuration_items_menu.html', $oConfigManagementGroup->GetIndex(), 2 /* fRank */);

new OQLMenuNode('BusinessProcess', 'SELECT BusinessProcess', $oCINode->GetIndex(), 0 /* fRank */);
new OQLMenuNode('ApplicationSolution', 'SELECT ApplicationSolution', $oCINode->GetIndex(), 1 /* fRank */);

$oSWNode = new TemplateMenuNode('ConfigManagementSoftware', '', $oCINode->GetIndex(), 2 /* fRank */);
new OQLMenuNode('Licence', 'SELECT Licence', $oSWNode->GetIndex(), 0 /* fRank */);
new OQLMenuNode('Patch', 'SELECT Patch', $oSWNode->GetIndex(), 1 /* fRank */);
new OQLMenuNode('ApplicationInstance', 'SELECT SoftwareInstance', $oSWNode->GetIndex(), 2 /* fRank */);

$oHWNode = new TemplateMenuNode('ConfigManagementHardware', '', $oCINode->GetIndex(), 3 /* fRank */);
new OQLMenuNode('Subnet', 'SELECT Subnet', $oHWNode->GetIndex(), 0 /* fRank */);
new OQLMenuNode('NetworkDevice', 'SELECT NetworkDevice', $oHWNode->GetIndex(), 1 /* fRank */);
new OQLMenuNode('Server', 'SELECT Server', $oHWNode->GetIndex(), 2 /* fRank */);
new OQLMenuNode('Printer', 'SELECT Printer', $oHWNode->GetIndex(), 3 /* fRank */);
new OQLMenuNode('MobilePhone', 'SELECT MobilePhone', $oHWNode->GetIndex(), 4 /* fRank */);
new OQLMenuNode('PC', 'SELECT PC', $oHWNode->GetIndex(), 5 /* fRank */);

?>

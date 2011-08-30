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


abstract class Contract extends cmdbAbstractObject
{

	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,servicemgmt",
			"key_type" => "autoincrement",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array("name"),
			"db_table" => "contract",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"icon" => "../modules/itop-service-mgmt-1.0.0/contract.png",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeString("name", array("allowed_values"=>null, "sql"=>"name", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeText("description", array("allowed_values"=>null, "sql"=>"description", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeDateTime("start_date", array("allowed_values"=>null, "sql"=>"start_date", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeDateTime("end_date", array("allowed_values"=>null, "sql"=>"end_date", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeInteger("cost", array("allowed_values"=>null, "sql"=>"cost", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("cost_currency", array("allowed_values"=>new ValueSetEnum('dollars,euros'), "sql"=>"cost_currency", "default_value"=>"euros", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("cost_unit", array("allowed_values"=>null, "sql"=>"cost_unit", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("billing_frequency", array("allowed_values"=>null, "sql"=>"billing_frequency", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("contact_list", array("linked_class"=>"lnkContractToContact", "ext_key_to_me"=>"contract_id", "ext_key_to_remote"=>"contact_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("document_list", array("linked_class"=>"lnkContractToDoc", "ext_key_to_me"=>"contract_id", "ext_key_to_remote"=>"document_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("ci_list", array("linked_class"=>"lnkContractToCI", "ext_key_to_me"=>"contract_id", "ext_key_to_remote"=>"ci_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('name', 'description', 'start_date', 'end_date', 'cost', 'cost_currency', 'cost_unit', 'billing_frequency', 'contact_list', 'document_list', 'ci_list'));
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'description', 'start_date', 'end_date', 'cost', 'cost_currency', 'cost_unit', 'billing_frequency'));
		MetaModel::Init_SetZListItems('standard_search', array('name', 'description', 'start_date', 'end_date', 'cost', 'cost_currency', 'cost_unit', 'billing_frequency'));
		MetaModel::Init_SetZListItems('list', array('description', 'start_date', 'end_date', 'cost', 'cost_currency', 'cost_unit', 'billing_frequency'));
	}
}
class ProviderContract extends Contract
{

	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,servicemgmt",
			"key_type" => "autoincrement",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array("name","provider_id"),
			"db_table" => "providercontract",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"icon" => "../modules/itop-service-mgmt-1.0.0/contract.png",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeExternalKey("provider_id", array("targetclass"=>"Organization", "jointype"=>null, "allowed_values"=>null, "sql"=>"provider_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("provider_name", array("allowed_values"=>null, "extkey_attcode"=>"provider_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("sla", array("allowed_values"=>null, "sql"=>"sla", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("coverage", array("allowed_values"=>null, "sql"=>"coverage", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('name', 'description', 'start_date', 'end_date', 'cost', 'cost_currency', 'cost_unit', 'billing_frequency', 'contact_list', 'document_list', 'ci_list', 'provider_id', 'sla', 'coverage'));
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'description', 'start_date', 'end_date', 'cost', 'cost_currency', 'cost_unit', 'billing_frequency', 'provider_id', 'sla', 'coverage'));
		MetaModel::Init_SetZListItems('standard_search', array('name', 'description', 'start_date', 'end_date', 'cost', 'cost_currency', 'cost_unit', 'billing_frequency', 'provider_id', 'sla', 'coverage'));
		MetaModel::Init_SetZListItems('list', array('start_date', 'end_date', 'provider_id', 'sla', 'coverage'));
	}

	/**
	 * Maps the given context parameter name to the appropriate filter/search code for this class
	 * @param string $sContextParam Name of the context parameter, e.g. 'org_id'
	 * @return string Filter code, e.g. 'customer_id'
	 */
	public static function MapContextParam($sContextParam)
	{
		if ($sContextParam == 'org_id')
		{
			return 'provider_id';
		}
		else
		{
			return parent::MapContextParam($sContextParam); // Ask the parent what to do with other parameters...
		}
	}
}
class CustomerContract extends Contract
{

	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,servicemgmt",
			"key_type" => "autoincrement",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array("name","org_id","provider_id"),
			"db_table" => "customercontract",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"icon" => "../modules/itop-service-mgmt-1.0.0/contract.png",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeExternalKey("org_id", array("targetclass"=>"Organization", "jointype"=>null, "allowed_values"=>null, "sql"=>"org_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("org_name", array("allowed_values"=>null, "extkey_attcode"=>"org_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("provider_id", array("targetclass"=>"Organization", "jointype"=>null, "allowed_values"=>null, "sql"=>"provider_id", "is_null_allowed"=>true, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("provider_name", array("allowed_values"=>null, "extkey_attcode"=>"provider_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("support_team_id", array("targetclass"=>"Team", "jointype"=>null, "allowed_values"=>new ValueSetObjects('SELECT Team WHERE Team.org_id = :this->provider_id'), "sql"=>"support_team_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array('provider_id'))));
		MetaModel::Init_AddAttribute(new AttributeExternalField("support_team_name", array("allowed_values"=>null, "extkey_attcode"=>"support_team_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("sla_list", array("linked_class"=>"lnkContractToSLA", "ext_key_to_me"=>"contract_id", "ext_key_to_remote"=>"sla_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("provider_list", array("linked_class"=>"lnkCustomerContractToProviderContract", "ext_key_to_me"=>"customer_contract_id", "ext_key_to_remote"=>"provider_contract_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('name', 'org_id', 'description', 'start_date', 'end_date', 'cost', 'cost_currency', 'cost_unit', 'billing_frequency', 'contact_list', 'document_list', 'ci_list', 'provider_list','provider_id', 'support_team_id', 'sla_list'));
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'description', 'start_date', 'end_date', 'cost', 'cost_currency', 'cost_unit', 'billing_frequency', 'org_id', 'support_team_id'));
		MetaModel::Init_SetZListItems('standard_search', array('name', 'description', 'org_id', 'start_date', 'end_date', 'cost', 'cost_currency', 'cost_unit', 'billing_frequency', 'provider_id', 'support_team_id'));
		MetaModel::Init_SetZListItems('list', array('org_id', 'start_date', 'end_date', 'provider_id', 'support_team_id'));
	}
}
class lnkCustomerContractToProviderContract extends cmdbAbstractObject
{

	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,servicemgmt,lnkservice",
			"key_type" => "autoincrement",
			"name_attcode" => "customer_contract_id",
			"state_attcode" => "",
			"reconc_keys" => array("customer_contract_id","provider_contract_id"),
			"db_table" => "lnkcustomercontracttoprovider",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeExternalKey("customer_contract_id", array("targetclass"=>"CustomerContract", "jointype"=>null, "allowed_values"=>null, "sql"=>"customer_contract_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("customer_contract_name", array("allowed_values"=>null, "extkey_attcode"=>"customer_contract_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("provider_contract_id", array("targetclass"=>"ProviderContract", "jointype"=>null, "allowed_values"=>null, "sql"=>"provider_contract_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("provider_contract_name", array("allowed_values"=>null, "extkey_attcode"=>"provider_contract_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("provider_sla", array("allowed_values"=>null, "extkey_attcode"=>"provider_contract_id", "target_attcode"=>"sla", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("provider_coverage", array("allowed_values"=>null, "extkey_attcode"=>"provider_contract_id", "target_attcode"=>"coverage", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('customer_contract_id', 'provider_contract_id','provider_sla','provider_coverage'));
		MetaModel::Init_SetZListItems('advanced_search', array('customer_contract_id', 'provider_contract_id'));
		MetaModel::Init_SetZListItems('standard_search', array('customer_contract_id', 'provider_contract_id'));
		MetaModel::Init_SetZListItems('list', array('customer_contract_id', 'provider_contract_id','provider_sla','provider_coverage'));
	}
}
class lnkContractToSLA extends cmdbAbstractObject
{

	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,servicemgmt,lnkservice",
			"key_type" => "autoincrement",
			"name_attcode" => "contract_id",
			"state_attcode" => "",
			"reconc_keys" => array("contract_id","sla_id"),
			"db_table" => "lnkcontracttosla",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeExternalKey("contract_id", array("targetclass"=>"CustomerContract", "jointype"=>null, "allowed_values"=>null, "sql"=>"contract_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("contract_name", array("allowed_values"=>null, "extkey_attcode"=>"contract_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("sla_id", array("targetclass"=>"SLA", "jointype"=>null, "allowed_values"=>null, "sql"=>"sla_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("sla_name", array("allowed_values"=>null, "extkey_attcode"=>"sla_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("sla_service_name", array("allowed_values"=>null, "extkey_attcode"=>"sla_id", "target_attcode"=>"service_name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("coverage", array("allowed_values"=>null, "sql"=>"coverage", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('contract_id', 'sla_id','sla_service_name', 'coverage'));
		MetaModel::Init_SetZListItems('advanced_search', array('contract_id', 'sla_id', 'coverage'));
		MetaModel::Init_SetZListItems('standard_search', array('contract_id', 'sla_id', 'coverage'));
		MetaModel::Init_SetZListItems('list', array('contract_id', 'sla_id', 'sla_service_name','coverage'));
	}
}
class lnkContractToDoc extends cmdbAbstractObject
{

	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,servicemgmt,lnkservice",
			"key_type" => "autoincrement",
			"name_attcode" => "contract_id",
			"state_attcode" => "",
			"reconc_keys" => array("contract_id","document_id"),
			"db_table" => "lnkcontracttodoc",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeExternalKey("contract_id", array("targetclass"=>"Contract", "jointype"=>null, "allowed_values"=>null, "sql"=>"contract_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("contract_name", array("allowed_values"=>null, "extkey_attcode"=>"contract_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("document_id", array("targetclass"=>"Document", "jointype"=>null, "allowed_values"=>null, "sql"=>"document_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("document_name", array("allowed_values"=>null, "extkey_attcode"=>"document_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("document_type", array("allowed_values"=>null, "extkey_attcode"=>"document_id", "target_attcode"=>"type", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("document_status", array("allowed_values"=>null, "extkey_attcode"=>"document_id", "target_attcode"=>"status", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('contract_id', 'document_id', 'document_type', 'document_status'));
		MetaModel::Init_SetZListItems('advanced_search', array('contract_id', 'document_id', 'document_type', 'document_status'));
		MetaModel::Init_SetZListItems('standard_search', array('contract_id', 'document_id', 'document_type', 'document_status'));
		MetaModel::Init_SetZListItems('list', array('contract_id', 'document_id', 'document_type', 'document_status'));
	}
}
class lnkContractToContact extends cmdbAbstractObject
{

	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,servicemgmt,lnkservice",
			"key_type" => "autoincrement",
			"name_attcode" => "contract_id",
			"state_attcode" => "",
			"reconc_keys" => array("contract_id","contact_id"),
			"db_table" => "lnkcontracttocontact",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeExternalKey("contract_id", array("targetclass"=>"Contract", "jointype"=>null, "allowed_values"=>null, "sql"=>"contract_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("contract_name", array("allowed_values"=>null, "extkey_attcode"=>"contract_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("contact_id", array("targetclass"=>"Contact", "jointype"=>null, "allowed_values"=>null, "sql"=>"contact_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("contact_name", array("allowed_values"=>null, "extkey_attcode"=>"contact_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("contact_email", array("allowed_values"=>null, "extkey_attcode"=>"contact_id", "target_attcode"=>"email", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("role", array("allowed_values"=>null, "sql"=>"role", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('contract_id', 'contact_id', 'contact_email', 'role'));
		MetaModel::Init_SetZListItems('advanced_search', array('contract_id', 'contact_id', 'contact_email', 'role'));
		MetaModel::Init_SetZListItems('standard_search', array('contract_id', 'contact_id', 'contact_email', 'role'));
		MetaModel::Init_SetZListItems('list', array('contract_id', 'contact_id', 'contact_email', 'role'));
	}
}
class lnkContractToCI extends cmdbAbstractObject
{

	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,servicemgmt,lnkservice",
			"key_type" => "autoincrement",
			"name_attcode" => "contract_id",
			"state_attcode" => "",
			"reconc_keys" => array("contract_id","ci_id"),
			"db_table" => "lnkcontracttoci",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeExternalKey("contract_id", array("targetclass"=>"Contract", "jointype"=>null, "allowed_values"=>null, "sql"=>"contract_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("contract_name", array("allowed_values"=>null, "extkey_attcode"=>"contract_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("ci_id", array("targetclass"=>"FunctionalCI", "jointype"=>null, "allowed_values"=>null, "sql"=>"ci_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("ci_name", array("allowed_values"=>null, "extkey_attcode"=>"ci_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("ci_status", array("allowed_values"=>null, "extkey_attcode"=>"ci_id", "target_attcode"=>"status", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('contract_id', 'ci_id', 'ci_status'));
		MetaModel::Init_SetZListItems('advanced_search', array('contract_id', 'ci_id', 'ci_status'));
		MetaModel::Init_SetZListItems('standard_search', array('contract_id', 'ci_id', 'ci_status'));
		MetaModel::Init_SetZListItems('list', array('contract_id', 'ci_id', 'ci_status'));
	}
}
class Service extends cmdbAbstractObject
{

	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,servicemgmt",
			"key_type" => "autoincrement",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array("name","org_id"),
			"db_table" => "service",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"icon" => "../modules/itop-service-mgmt-1.0.0/service.png",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeExternalKey("org_id", array("targetclass"=>"Organization", "jointype"=>null, "allowed_values"=>null, "sql"=>"org_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("provider_name", array("allowed_values"=>null, "extkey_attcode"=>"org_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("name", array("allowed_values"=>null, "sql"=>"name", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeText("description", array("allowed_values"=>null, "sql"=>"description", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("type", array("allowed_values"=>new ValueSetEnum('RequestManagement,IncidentManagement'), "sql"=>"type", "default_value"=>"IncidentManagement", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("status", array("allowed_values"=>new ValueSetEnum('design,production,obsolete'), "sql"=>"status", "default_value"=>"design", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSet("subcategory_list", array("linked_class"=>"ServiceSubcategory", "ext_key_to_me"=>"service_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSet("sla_list", array("linked_class"=>"SLA", "ext_key_to_me"=>"service_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("document_list", array("linked_class"=>"lnkServiceToDoc", "ext_key_to_me"=>"service_id", "ext_key_to_remote"=>"document_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("contact_list", array("linked_class"=>"lnkServiceToContact", "ext_key_to_me"=>"service_id", "ext_key_to_remote"=>"contact_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('name', 'description', 'org_id', 'type', 'status', 'subcategory_list', 'sla_list', 'document_list', 'contact_list'));
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'description', 'org_id', 'type', 'status'));
		MetaModel::Init_SetZListItems('standard_search', array('name', 'description', 'org_id', 'type', 'status'));
		MetaModel::Init_SetZListItems('list', array('description', 'org_id', 'type', 'status'));

	}

	function DisplayBareRelations(WebPage $oPage, $bEditMode = false)
	{
		parent::DisplayBareRelations($oPage, $bEditMode);
		$aExtraParam = array ('menu' => false, 'block_id' => 'service');
		$ServiceID=$this->GetKey();
		if (!$bEditMode)
		{
			$oPage->SetCurrentTab(Dict::S('Class:Service/Tab:Related_Contracts'));
			$oCustomerContracts=new CMDBObjectSet(DBObjectSearch::FromOQL("SELECT CustomerContract AS cc JOIN lnkContractToSLA AS ln ON ln.contract_id=cc.id JOIN SLA AS sla ON ln.sla_id=sla.id WHERE sla.service_id=$ServiceID"));
			self::DisplaySet($oPage,$oCustomerContracts,$aExtraParam);
		}
	}
}
class ServiceSubcategory extends cmdbAbstractObject
{

	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,servicemgmt",
			"key_type" => "autoincrement",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array("name","service_id"),
			"db_table" => "servicesubcategory",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"icon" => "../modules/itop-service-mgmt-1.0.0/sla.png",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeString("name", array("allowed_values"=>null, "sql"=>"name", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("description", array("allowed_values"=>null, "sql"=>"description", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("service_id", array("targetclass"=>"Service", "jointype"=>null, "allowed_values"=>null, "sql"=>"service_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("service_name", array("allowed_values"=>null, "extkey_attcode"=>"service_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("org_id", array("allowed_values"=>null, "extkey_attcode"=>"service_id", "target_attcode"=>"org_id", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("provider_name", array("allowed_values"=>null, "extkey_attcode"=>"service_id", "target_attcode"=>"provider_name", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('name', 'org_id','service_id','description'));
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'description', 'service_id'));
		MetaModel::Init_SetZListItems('standard_search', array('name', 'description', 'service_id','provider_name'));
		MetaModel::Init_SetZListItems('list', array('service_id','description'));
	}
}
class SLA extends cmdbAbstractObject
{

	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,servicemgmt",
			"key_type" => "autoincrement",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array("name","service_id"),
			"db_table" => "sla",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"icon" => "../modules/itop-service-mgmt-1.0.0/sla.png",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeString("name", array("allowed_values"=>null, "sql"=>"name", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("service_id", array("targetclass"=>"Service", "jointype"=>null, "allowed_values"=>null, "sql"=>"service_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("service_name", array("allowed_values"=>null, "extkey_attcode"=>"service_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("slt_list", array("linked_class"=>"lnkSLTToSLA", "ext_key_to_me"=>"sla_id", "ext_key_to_remote"=>"slt_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('name', 'service_id', 'slt_list'));
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'service_id'));
		MetaModel::Init_SetZListItems('standard_search', array('name', 'service_id'));
		MetaModel::Init_SetZListItems('list', array('service_id'));
	}
}
class SLT extends cmdbAbstractObject
{

	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,servicemgmt",
			"key_type" => "autoincrement",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array("name"),
			"db_table" => "slt",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"icon" => "../modules/itop-service-mgmt-1.0.0/slt.png",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeString("name", array("allowed_values"=>null, "sql"=>"name", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("metric", array("allowed_values"=>new ValueSetEnum('TTO,TTR'), "sql"=>"metric", "default_value"=>'TTO', "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("ticket_priority", array("allowed_values"=>new ValueSetEnum('1,2,3'), "sql"=>"ticket_priority", "default_value"=>"1", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeInteger("value", array("allowed_values"=>null, "sql"=>"value", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("value_unit", array("allowed_values"=>new ValueSetEnum('days,hours,minutes'), "sql"=>"value_unit", "default_value"=>"hours", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("sla_list", array("linked_class"=>"lnkSLTToSLA", "ext_key_to_me"=>"slt_id", "ext_key_to_remote"=>"sla_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('name', 'metric', 'ticket_priority', 'value', 'value_unit', 'sla_list'));
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'metric', 'ticket_priority', 'value', 'value_unit'));
		MetaModel::Init_SetZListItems('standard_search', array('name', 'metric', 'ticket_priority', 'value', 'value_unit'));
		MetaModel::Init_SetZListItems('list', array('metric', 'ticket_priority', 'value', 'value_unit'));
	}
}
class lnkSLTToSLA extends cmdbAbstractObject
{

	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,servicemgmt,lnkservice",
			"key_type" => "autoincrement",
			"name_attcode" => "sla_id",
			"state_attcode" => "",
			"reconc_keys" => array("sla_id","slt_id"),
			"db_table" => "lnkslttosla",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeExternalKey("sla_id", array("targetclass"=>"SLA", "jointype"=>null, "allowed_values"=>null, "sql"=>"sla_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("sla_name", array("allowed_values"=>null, "extkey_attcode"=>"sla_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("slt_id", array("targetclass"=>"SLT", "jointype"=>null, "allowed_values"=>null, "sql"=>"slt_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("slt_name", array("allowed_values"=>null, "extkey_attcode"=>"slt_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("slt_metric", array("allowed_values"=>null, "extkey_attcode"=>"slt_id", "target_attcode"=>"metric", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("slt_ticket_priority", array("allowed_values"=>null, "extkey_attcode"=>"slt_id", "target_attcode"=>"ticket_priority", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("slt_value", array("allowed_values"=>null, "extkey_attcode"=>"slt_id", "target_attcode"=>"value", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("slt_value_unit", array("allowed_values"=>null, "extkey_attcode"=>"slt_id", "target_attcode"=>"value_unit", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('sla_id', 'slt_id', 'slt_metric', 'slt_ticket_priority', 'slt_value', 'slt_value_unit'));
		MetaModel::Init_SetZListItems('advanced_search', array('sla_id', 'slt_id', 'slt_metric', 'slt_ticket_priority', 'slt_value', 'slt_value_unit'));
		MetaModel::Init_SetZListItems('standard_search', array('sla_id', 'slt_id', 'slt_metric', 'slt_ticket_priority', 'slt_value', 'slt_value_unit'));
		MetaModel::Init_SetZListItems('list', array('sla_id', 'slt_id', 'slt_metric', 'slt_ticket_priority', 'slt_value', 'slt_value_unit'));
	}
}
class lnkServiceToDoc extends cmdbAbstractObject
{

	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,servicemgmt,lnkservice",
			"key_type" => "autoincrement",
			"name_attcode" => "service_id",
			"state_attcode" => "",
			"reconc_keys" => array("service_id","document_id"),
			"db_table" => "lnkservicetodoc",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeExternalKey("service_id", array("targetclass"=>"Service", "jointype"=>null, "allowed_values"=>null, "sql"=>"service_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("service_name", array("allowed_values"=>null, "extkey_attcode"=>"service_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("document_id", array("targetclass"=>"Document", "jointype"=>null, "allowed_values"=>null, "sql"=>"document_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("document_name", array("allowed_values"=>null, "extkey_attcode"=>"document_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("document_type", array("allowed_values"=>null, "extkey_attcode"=>"document_id", "target_attcode"=>"type", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("document_status", array("allowed_values"=>null, "extkey_attcode"=>"document_id", "target_attcode"=>"status", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('service_id', 'document_id', 'document_type', 'document_status'));
		MetaModel::Init_SetZListItems('advanced_search', array('service_id', 'document_id', 'document_type', 'document_status'));
		MetaModel::Init_SetZListItems('standard_search', array('service_id', 'document_id', 'document_type', 'document_status'));
		MetaModel::Init_SetZListItems('list', array('service_id', 'document_id', 'document_type', 'document_status'));
	}
}
class lnkServiceToContact extends cmdbAbstractObject
{

	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,servicemgmt,lnkservice",
			"key_type" => "autoincrement",
			"name_attcode" => "service_id",
			"state_attcode" => "",
			"reconc_keys" => array("service_id","contact_id"),
			"db_table" => "lnkservicetocontact",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeExternalKey("service_id", array("targetclass"=>"Service", "jointype"=>null, "allowed_values"=>null, "sql"=>"service_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("service_name", array("allowed_values"=>null, "extkey_attcode"=>"service_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("contact_id", array("targetclass"=>"Contact", "jointype"=>null, "allowed_values"=>null, "sql"=>"contact_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("contact_name", array("allowed_values"=>null, "extkey_attcode"=>"contact_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("contact_email", array("allowed_values"=>null, "extkey_attcode"=>"contact_id", "target_attcode"=>"email", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("role", array("allowed_values"=>null, "sql"=>"role", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('service_id', 'contact_id', 'contact_email', 'role'));
		MetaModel::Init_SetZListItems('advanced_search', array('service_id', 'contact_id', 'contact_email', 'role'));
		MetaModel::Init_SetZListItems('standard_search', array('service_id', 'contact_id', 'contact_email', 'role'));
		MetaModel::Init_SetZListItems('list', array('service_id', 'contact_id', 'contact_email', 'role'));
	}
}
class lnkServiceToCI extends cmdbAbstractObject
{

	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,servicemgmt,lnkservice",
			"key_type" => "autoincrement",
			"name_attcode" => "service_id",
			"state_attcode" => "",
			"reconc_keys" => array("service_id","ci_id"),
			"db_table" => "lnkservicetoci",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeExternalKey("service_id", array("targetclass"=>"Service", "jointype"=>null, "allowed_values"=>null, "sql"=>"service_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("service_name", array("allowed_values"=>null, "extkey_attcode"=>"service_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("ci_id", array("targetclass"=>"FunctionalCI", "jointype"=>null, "allowed_values"=>null, "sql"=>"ci_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("ci_name", array("allowed_values"=>null, "extkey_attcode"=>"ci_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("ci_status", array("allowed_values"=>null, "extkey_attcode"=>"ci_id", "target_attcode"=>"status", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('service_id', 'ci_id', 'ci_status'));
		MetaModel::Init_SetZListItems('advanced_search', array('service_id', 'ci_id', 'ci_status'));
		MetaModel::Init_SetZListItems('standard_search', array('service_id', 'ci_id', 'ci_status'));
		MetaModel::Init_SetZListItems('list', array('service_id', 'ci_id', 'ci_status'));
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

$oServiceManagementGroup = new MenuGroup('ServiceManagement', 60 /* fRank */);
$iRank = 0;
new TemplateMenuNode('Service:Overview', dirname(__FILE__).'/overview.html', $oServiceManagementGroup->GetIndex() /* oParent */, $iRank++ /* fRank */);
new OQLMenuNode('ProviderContract', 'SELECT ProviderContract', $oServiceManagementGroup->GetIndex(), $iRank++,true /* bsearch */);
new OQLMenuNode('CustomerContract', 'SELECT CustomerContract', $oServiceManagementGroup->GetIndex(),  $iRank++,true /* bsearch */);
new OQLMenuNode('Service', 'SELECT Service', $oServiceManagementGroup->GetIndex(), $iRank++,true /* bsearch */);
new OQLMenuNode('ServiceSubcategory', 'SELECT ServiceSubcategory', $oServiceManagementGroup->GetIndex(), $iRank++,true /* bsearch */);
new OQLMenuNode('SLA', 'SELECT SLA', $oServiceManagementGroup->GetIndex(), $iRank++,true /* bsearch */);
new OQLMenuNode('SLT', 'SELECT SLT', $oServiceManagementGroup->GetIndex(), $iRank++,true /* bsearch */);

?>

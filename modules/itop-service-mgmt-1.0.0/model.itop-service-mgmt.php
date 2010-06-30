<?

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
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeString("name", array("allowed_values"=>null, "sql"=>"name", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("status", array("allowed_values"=>new ValueSetEnum('draft,agreed,running'), "sql"=>"status", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("description", array("allowed_values"=>null, "sql"=>"description", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeDate("signed", array("allowed_values"=>null, "sql"=>"signed", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeDate("begin", array("allowed_values"=>null, "sql"=>"begin", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeDate("end", array("allowed_values"=>null, "sql"=>"end", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeInteger("cost", array("allowed_values"=>null, "sql"=>"cost", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("cost_currency", array("allowed_values"=>new ValueSetEnum('dollars,euros'), "sql"=>"cost_currency", "default_value"=>"euros", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("cost_unit", array("allowed_values"=>null, "sql"=>"cost_unit", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("billing_frequency", array("allowed_values"=>null, "sql"=>"billing_frequency", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("contact_list", array("linked_class"=>"lnkContractToContact", "ext_key_to_me"=>"contract_id", "ext_key_to_remote"=>"contact_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("document_list", array("linked_class"=>"lnkContractToDoc", "ext_key_to_me"=>"contract_id", "ext_key_to_remote"=>"document_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("ci_list", array("linked_class"=>"lnkContractToCI", "ext_key_to_me"=>"contract_id", "ext_key_to_remote"=>"ci_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('name', 'status', 'description', 'signed', 'begin', 'end', 'cost', 'cost_currency', 'cost_unit', 'billing_frequency', 'contact_list', 'document_list', 'ci_list'));
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'status', 'description', 'signed', 'begin', 'end', 'cost', 'cost_currency', 'cost_unit', 'billing_frequency'));
		MetaModel::Init_SetZListItems('standard_search', array('name', 'status', 'description', 'signed', 'begin', 'end', 'cost', 'cost_currency', 'cost_unit', 'billing_frequency'));
		MetaModel::Init_SetZListItems('list', array('name', 'status', 'description', 'signed', 'begin', 'end', 'cost', 'cost_currency', 'cost_unit', 'billing_frequency'));
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
			"reconc_keys" => array("name"),
			"db_table" => "providercontract",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeEnum("type", array("allowed_values"=>new ValueSetEnum('a,b,c'), "sql"=>"type", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("provider_id", array("targetclass"=>"Organization", "jointype"=>null, "allowed_values"=>null, "sql"=>"provider_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("provider_name", array("allowed_values"=>null, "extkey_attcode"=>"provider_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("ola", array("allowed_values"=>null, "sql"=>"ola", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("coverage", array("allowed_values"=>null, "sql"=>"coverage", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("customer_list", array("linked_class"=>"lnkProviderToCustomer", "ext_key_to_me"=>"provider_id", "ext_key_to_remote"=>"customer_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("sla_list", array("linked_class"=>"lnkContractToSLA", "ext_key_to_me"=>"contract_id", "ext_key_to_remote"=>"sla_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('name', 'status', 'description', 'signed', 'begin', 'end', 'cost', 'cost_currency', 'cost_unit', 'billing_frequency', 'contact_list', 'document_list', 'ci_list', 'type', 'provider_id', 'ola', 'coverage', 'customer_list', 'sla_list'));
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'status', 'description', 'signed', 'begin', 'end', 'cost', 'cost_currency', 'cost_unit', 'billing_frequency', 'type', 'provider_id', 'ola', 'coverage'));
		MetaModel::Init_SetZListItems('standard_search', array('name', 'status', 'description', 'signed', 'begin', 'end', 'cost', 'cost_currency', 'cost_unit', 'billing_frequency', 'type', 'provider_id', 'ola', 'coverage'));
		MetaModel::Init_SetZListItems('list', array('name', 'status', 'description', 'signed', 'begin', 'end', 'cost', 'cost_currency', 'cost_unit', 'billing_frequency', 'type', 'provider_id', 'ola', 'coverage'));
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
			"reconc_keys" => array("name"),
			"db_table" => "customercontract",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeEnum("type", array("allowed_values"=>new ValueSetEnum('x,y,z'), "sql"=>"type", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("customer_id", array("targetclass"=>"Organization", "jointype"=>null, "allowed_values"=>null, "sql"=>"customer_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("customer_name", array("allowed_values"=>null, "extkey_attcode"=>"customer_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("provider_list", array("linked_class"=>"lnkProviderToCustomer", "ext_key_to_me"=>"customer_id", "ext_key_to_remote"=>"provider_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('name', 'status', 'description', 'signed', 'begin', 'end', 'cost', 'cost_currency', 'cost_unit', 'billing_frequency', 'contact_list', 'document_list', 'ci_list', 'type', 'customer_id', 'provider_list'));
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'status', 'description', 'signed', 'begin', 'end', 'cost', 'cost_currency', 'cost_unit', 'billing_frequency', 'type', 'customer_id'));
		MetaModel::Init_SetZListItems('standard_search', array('name', 'status', 'description', 'signed', 'begin', 'end', 'cost', 'cost_currency', 'cost_unit', 'billing_frequency', 'type', 'customer_id'));
		MetaModel::Init_SetZListItems('list', array('name', 'status', 'description', 'signed', 'begin', 'end', 'cost', 'cost_currency', 'cost_unit', 'billing_frequency', 'type', 'customer_id'));
	}
}
class lnkProviderToCustomer extends cmdbAbstractObject
{

	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,servicemgmt",
			"key_type" => "autoincrement",
			"name_attcode" => "provider_id",
			"state_attcode" => "",
			"reconc_keys" => array("name"),
			"db_table" => "lnkprovidertocustomer",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeExternalKey("provider_id", array("targetclass"=>"Organization", "jointype"=>null, "allowed_values"=>null, "sql"=>"provider_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("provider_name", array("allowed_values"=>null, "extkey_attcode"=>"provider_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("customer_id", array("targetclass"=>"Organization", "jointype"=>null, "allowed_values"=>null, "sql"=>"customer_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("customer_name", array("allowed_values"=>null, "extkey_attcode"=>"customer_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('provider_id', 'customer_id'));
		MetaModel::Init_SetZListItems('advanced_search', array('provider_id', 'customer_id'));
		MetaModel::Init_SetZListItems('standard_search', array('provider_id', 'customer_id'));
		MetaModel::Init_SetZListItems('list', array('provider_id', 'customer_id'));
	}
}
class lnkContractToSLA extends cmdbAbstractObject
{

	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,servicemgmt",
			"key_type" => "autoincrement",
			"name_attcode" => "contract_id",
			"state_attcode" => "",
			"reconc_keys" => array("name"),
			"db_table" => "lnkcontracttosla",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeExternalKey("contract_id", array("targetclass"=>"Contract", "jointype"=>null, "allowed_values"=>null, "sql"=>"contract_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("contract_name", array("allowed_values"=>null, "extkey_attcode"=>"contract_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("sla_id", array("targetclass"=>"SLA", "jointype"=>null, "allowed_values"=>null, "sql"=>"sla_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("sla_name", array("allowed_values"=>null, "extkey_attcode"=>"sla_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("coverage", array("allowed_values"=>null, "sql"=>"coverage", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('contract_id', 'sla_id', 'coverage'));
		MetaModel::Init_SetZListItems('advanced_search', array('contract_id', 'sla_id', 'coverage'));
		MetaModel::Init_SetZListItems('standard_search', array('contract_id', 'sla_id', 'coverage'));
		MetaModel::Init_SetZListItems('list', array('contract_id', 'sla_id', 'coverage'));
	}
}
class lnkContractToDoc extends cmdbAbstractObject
{

	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,servicemgmt",
			"key_type" => "autoincrement",
			"name_attcode" => "contract_id",
			"state_attcode" => "",
			"reconc_keys" => array("name"),
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
			"category" => "bizmodel,searchable,servicemgmt",
			"key_type" => "autoincrement",
			"name_attcode" => "contract_id",
			"state_attcode" => "",
			"reconc_keys" => array("name"),
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

		MetaModel::Init_SetZListItems('details', array('contract_id', 'contact_id', 'contact_email'));
		MetaModel::Init_SetZListItems('advanced_search', array('contract_id', 'contact_id', 'contact_email'));
		MetaModel::Init_SetZListItems('standard_search', array('contract_id', 'contact_id', 'contact_email'));
		MetaModel::Init_SetZListItems('list', array('contract_id', 'contact_id', 'contact_email'));
	}
}
class lnkContractToCI extends cmdbAbstractObject
{

	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,servicemgmt",
			"key_type" => "autoincrement",
			"name_attcode" => "contract_id",
			"state_attcode" => "",
			"reconc_keys" => array("name"),
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
class ServiceType extends cmdbAbstractObject
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
			"db_table" => "servicetype",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeString("name", array("allowed_values"=>null, "sql"=>"name", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("description", array("allowed_values"=>null, "sql"=>"description", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('name', 'description'));
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'description'));
		MetaModel::Init_SetZListItems('standard_search', array('name', 'description'));
		MetaModel::Init_SetZListItems('list', array('name', 'description'));
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
			"name_attcode" => "servicetype_id",
			"state_attcode" => "",
			"reconc_keys" => array("name"),
			"db_table" => "service",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeExternalKey("servicetype_id", array("targetclass"=>"ServiceType", "jointype"=>null, "allowed_values"=>null, "sql"=>"servicetype_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("servicetype_name", array("allowed_values"=>null, "extkey_attcode"=>"servicetype_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('servicetype_id'));
		MetaModel::Init_SetZListItems('advanced_search', array('servicetype_id'));
		MetaModel::Init_SetZListItems('standard_search', array('servicetype_id'));
		MetaModel::Init_SetZListItems('list', array('servicetype_id'));
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
			"reconc_keys" => array("name"),
			"db_table" => "sla",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeString("name", array("allowed_values"=>null, "sql"=>"name", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("service_id", array("targetclass"=>"Service", "jointype"=>null, "allowed_values"=>null, "sql"=>"service_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("levels_list", array("linked_class"=>"lnkLevelToSLA", "ext_key_to_me"=>"sla_id", "ext_key_to_remote"=>"servicelevel_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('name', 'service_id', 'levels_list'));
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'service_id'));
		MetaModel::Init_SetZListItems('standard_search', array('name', 'service_id'));
		MetaModel::Init_SetZListItems('list', array('name', 'service_id'));
	}
}
class ServiceLevel extends cmdbAbstractObject
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
			"db_table" => "servicelevel",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeString("name", array("allowed_values"=>null, "sql"=>"name", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("metric", array("allowed_values"=>new ValueSetEnum('TTO,TTR'), "sql"=>"metric", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("ticket_type", array("allowed_values"=>null, "sql"=>"ticket_type", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("ticket_priorities", array("allowed_values"=>null, "sql"=>"ticket_priorities", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeInteger("value", array("allowed_values"=>null, "sql"=>"value", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("sla_list", array("linked_class"=>"lnkLevelToSLA", "ext_key_to_me"=>"servicelevel_id", "ext_key_to_remote"=>"sla_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('name', 'metric', 'ticket_type', 'ticket_priorities', 'value', 'sla_list'));
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'metric', 'ticket_type', 'ticket_priorities', 'value'));
		MetaModel::Init_SetZListItems('standard_search', array('name', 'metric', 'ticket_type', 'ticket_priorities', 'value'));
		MetaModel::Init_SetZListItems('list', array('name', 'metric', 'ticket_type', 'ticket_priorities', 'value'));
	}
}
class lnkLevelToSLA extends cmdbAbstractObject
{

	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable,servicemgmt",
			"key_type" => "autoincrement",
			"name_attcode" => "sla_id",
			"state_attcode" => "",
			"reconc_keys" => array("name"),
			"db_table" => "lnkleveltosla",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeExternalKey("sla_id", array("targetclass"=>"SLA", "jointype"=>null, "allowed_values"=>null, "sql"=>"sla_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("sla_name", array("allowed_values"=>null, "extkey_attcode"=>"sla_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("servicelevel_id", array("targetclass"=>"ServiceLevel", "jointype"=>null, "allowed_values"=>null, "sql"=>"servicelevel_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("servicelevel_name", array("allowed_values"=>null, "extkey_attcode"=>"servicelevel_id", "target_attcode"=>"name", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('sla_id', 'servicelevel_id'));
		MetaModel::Init_SetZListItems('advanced_search', array('sla_id', 'servicelevel_id'));
		MetaModel::Init_SetZListItems('standard_search', array('sla_id', 'servicelevel_id'));
		MetaModel::Init_SetZListItems('list', array('sla_id', 'servicelevel_id'));
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
$oMyMenuGroup = new MenuGroup('Menu:MyModule', 1 /* fRank */);

// Create an entry, based on a custom template, for the Configuration management overview, under the top-level group
$oMyMenuNode = new TemplateMenuNode('Menu:MyModule', '../business/templates/configuration_management_menu.html', $oMyMenuGroup->GetIndex(), 0 /* fRank */);
// By default, one entry per class
new OQLMenuNode('Menu:Class:Contract/Name', 'Menu:Class:Contract/Title', 'SELECT Contract', $oMyMenuNode->GetIndex(), 0 /* fRank */);
new OQLMenuNode('Menu:Class:ProviderContract/Name', 'Menu:Class:ProviderContract/Title', 'SELECT ProviderContract', $oMyMenuNode->GetIndex(), 1 /* fRank */);
new OQLMenuNode('Menu:Class:CustomerContract/Name', 'Menu:Class:CustomerContract/Title', 'SELECT CustomerContract', $oMyMenuNode->GetIndex(), 2 /* fRank */);
new OQLMenuNode('Menu:Class:lnkProviderToCustomer/Name', 'Menu:Class:lnkProviderToCustomer/Title', 'SELECT lnkProviderToCustomer', $oMyMenuNode->GetIndex(), 3 /* fRank */);
new OQLMenuNode('Menu:Class:lnkContractToSLA/Name', 'Menu:Class:lnkContractToSLA/Title', 'SELECT lnkContractToSLA', $oMyMenuNode->GetIndex(), 4 /* fRank */);
new OQLMenuNode('Menu:Class:lnkContractToDoc/Name', 'Menu:Class:lnkContractToDoc/Title', 'SELECT lnkContractToDoc', $oMyMenuNode->GetIndex(), 5 /* fRank */);
new OQLMenuNode('Menu:Class:lnkContractToContact/Name', 'Menu:Class:lnkContractToContact/Title', 'SELECT lnkContractToContact', $oMyMenuNode->GetIndex(), 6 /* fRank */);
new OQLMenuNode('Menu:Class:lnkContractToCI/Name', 'Menu:Class:lnkContractToCI/Title', 'SELECT lnkContractToCI', $oMyMenuNode->GetIndex(), 7 /* fRank */);
new OQLMenuNode('Menu:Class:ServiceType/Name', 'Menu:Class:ServiceType/Title', 'SELECT ServiceType', $oMyMenuNode->GetIndex(), 8 /* fRank */);
new OQLMenuNode('Menu:Class:Service/Name', 'Menu:Class:Service/Title', 'SELECT Service', $oMyMenuNode->GetIndex(), 9 /* fRank */);
new OQLMenuNode('Menu:Class:SLA/Name', 'Menu:Class:SLA/Title', 'SELECT SLA', $oMyMenuNode->GetIndex(), 10 /* fRank */);
new OQLMenuNode('Menu:Class:ServiceLevel/Name', 'Menu:Class:ServiceLevel/Title', 'SELECT ServiceLevel', $oMyMenuNode->GetIndex(), 11 /* fRank */);
new OQLMenuNode('Menu:Class:lnkLevelToSLA/Name', 'Menu:Class:lnkLevelToSLA/Title', 'SELECT lnkLevelToSLA', $oMyMenuNode->GetIndex(), 12 /* fRank */);






$oAdminMenu = new MenuGroup('UI:AdminToolsMenu', 999);
$iAdminGroup = $oAdminMenu->GetIndex();
new OQLMenuNode('Menu:Class:ServiceType/Name', 'Menu:Class:ServiceType/Title', 'SELECT ServiceType', $iAdminGroup, 20 /* fRank */);

$oServiceManagementGroup = new MenuGroup('Menu:ServiceManagement', 2 /* fRank */);

new OQLMenuNode('Menu:Class:ProviderContract/Name', 'Menu:Class:ProviderContract/Title', 'SELECT ProviderContract', $oServiceManagementGroup->GetIndex(), 1 /* fRank */);
new OQLMenuNode('Menu:Class:ProviderContract/Name', 'Menu:Class:ProviderContract/Title', 'SELECT ProviderContract', $oServiceManagementGroup->GetIndex(), 2 /* fRank */);
new OQLMenuNode('Menu:Class:ProviderContract/Name', 'Menu:Class:ProviderContract/Title', 'SELECT Service', $oServiceManagementGroup->GetIndex(), 3 /* fRank */);
new OQLMenuNode('Menu:Class:SLA/Name', 'Menu:Class:SLA/Title', 'SELECT SLA', $oServiceManagementGroup->GetIndex(), 4 /* fRank */);
new OQLMenuNode('Menu:Class:ServiceLevel/Name', 'Menu:Class:ServiceLevel/Title', 'SELECT ServiceLevel', $oServiceManagementGroup->GetIndex(), 5 /* fRank */);

?>

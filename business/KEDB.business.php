<?php

////////////////////////////////////////////////////////////////////////////////////
/**
* Description of known error
*/
////////////////////////////////////////////////////////////////////////////////////
class bizKnownError extends cmdbAbstractObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable",
			"key_type" => "autoincrement",
			"key_label" => "id",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array("org_id", "name"), // inherited attributes
			"db_table" => "known_error",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "../business/templates/knownError.html",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
    MetaModel::Init_AddAttribute(new AttributeString("name", array("allowed_values"=>null, "sql"=>"name", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));

    MetaModel::Init_AddAttribute(new AttributeExternalKey("org_id", array("targetclass"=>"bizOrganization", "allowed_values"=>null, "sql"=>"cust_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("cust_name", array("allowed_values"=>null, "extkey_attcode"=> 'org_id', "target_attcode"=>"name")));

   	MetaModel::Init_AddAttribute(new AttributeText("symptom", array("allowed_values"=>null, "sql"=>"symptom", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
 	 	MetaModel::Init_AddAttribute(new AttributeText("root_cause", array("allowed_values"=>null, "sql"=>"rootcause", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
 	  MetaModel::Init_AddAttribute(new AttributeText("workaround", array("allowed_values"=>null, "sql"=>"workaround", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
 		MetaModel::Init_AddAttribute(new AttributeText("solution", array("allowed_values"=>null, "sql"=>"solution", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
 
		MetaModel::Init_AddAttribute(new AttributeString("error_code", array("allowed_values"=>null, "sql"=>"error_code", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("domain", array("allowed_values"=>new ValueSetEnum("Network, Server, Application, Desktop"), "sql"=>"domain", "default_value"=>"Application", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("vendor", array("allowed_values"=>null, "sql"=>"vendor", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("model", array("allowed_values"=>null, "sql"=>"model", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("version", array("allowed_values"=>null, "sql"=>"version", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('name', 'org_id','error_code','domain','vendor','model','version', 'symptom','root_cause','workaround','solution')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('name', 'org_id','error_code', 'symptom')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('name', 'error_code','domain')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'org_id','error_code', 'error_code','symptom')); // Criteria of the advanced search form

	}
	
	// State machine actions
	public function IncrementVersion($sStimulusCode)
	{
		$this->Set('version_number', $this->Get('version_number') + 1);
		return true;
	}
}

////////////////////////////////////////////////////////////////////////////////////
/**
* n-n link between any Infra and a Known Error
*/
////////////////////////////////////////////////////////////////////////////////////
class lnkInfraError extends cmdbAbstractObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable",
			"key_type" => "autoincrement",
			"key_label" => "link_id",
			"name_attcode" => "",  // ????
			"state_attcode" => "",
			"reconc_keys" => array("infra_id","error_id"),  // ????
			"db_table" => "infra_error_links",
			"db_key_field" => "link_id",
			"db_finalclass_field" => "",
			"display_template" => "../business/templates/default.html",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_AddAttribute(new AttributeExternalKey("infra_id", array("targetclass"=>"logInfra", "jointype"=> '', "allowed_values"=>null, "sql"=>"infra_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("infra_name", array("allowed_values"=>null, "extkey_attcode"=> 'infra_id', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeExternalField("infra_status", array("allowed_values"=>null, "extkey_attcode"=> 'infra_id', "target_attcode"=>"status")));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("error_id", array("targetclass"=>"bizKnownError", "jointype"=> '', "allowed_values"=>null, "sql"=>"error_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("error_name", array("allowed_values"=>null, "extkey_attcode"=> 'error_id', "target_attcode"=>"name")));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('infra_id', 'error_id')); // Attributes to be displayed for a list
		MetaModel::Init_SetZListItems('list', array('infra_id', 'infra_status','error_id')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('infra_id', 'error_id')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('infra_id', 'error_id')); // Criteria of the advanced search form
	}

	
}

////////////////////////////////////////////////////////////////////////////////////
/**
* n-n link between any Contract and a Document
*/
////////////////////////////////////////////////////////////////////////////////////
class lnkDocumentError extends cmdbAbstractObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable",
			"key_type" => "autoincrement",
			"key_label" => "link_id",
			"name_attcode" => "link_type",
			"state_attcode" => "",
			"reconc_keys" => array("doc_name", "error_name"),
			"db_table" => "documents_error_link",
			"db_key_field" => "link_id",
			"db_finalclass_field" => "",
			"display_template" => "../business/templates/default.html",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_AddAttribute(new AttributeExternalKey("doc_id", array("targetclass"=>"bizDocument", "allowed_values"=>null, "sql"=>"doc_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("doc_name", array("allowed_values"=>null, "extkey_attcode"=> 'doc_id', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("error_id", array("targetclass"=>"bizKnownError", "allowed_values"=>null, "sql"=>"error_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("error_name", array("allowed_values"=>null, "extkey_attcode"=> 'error_id', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeString("link_type", array("allowed_values"=>null, "sql"=>"link_type", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('doc_id', 'error_name', 'link_type')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('doc_id', 'error_name', 'link_type')); // Attributes to be displayed for a list
	}
}


?>

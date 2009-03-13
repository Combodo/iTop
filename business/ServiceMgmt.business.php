<?php

////////////////////////////////////////////////////////////////////////////////////
/**
* Description of a contract signed with a customer
*/
////////////////////////////////////////////////////////////////////////////////////
class bizContract extends cmdbAbstractObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable",
			"name" => "Contract",
			"description" => "Contract signed by an organization",
			"key_type" => "autoincrement",
			"key_label" => "id",
			"name_attcode" => "name",
			"state_attcode" => "status",
			"reconc_keys" => array("customer_id", "name"), // inherited attributes
			"db_table" => "contracts",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "../business/templates/contract.html",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
    MetaModel::Init_AddAttribute(new AttributeString("name", array("label"=>"Name", "description"=>"Name of the contract", "allowed_values"=>null, "sql"=>"name", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));

    MetaModel::Init_AddAttribute(new AttributeExternalKey("customer_id", array("targetclass"=>"bizOrganization", "label"=>"Customer", "description"=>"Customer for this contract", "allowed_values"=>null, "sql"=>"customer_id", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("customer_name", array("label"=>"Customer", "description"=>"name of the Customer", "allowed_values"=>null, "extkey_attcode"=> 'customer_id', "target_attcode"=>"name")));

    MetaModel::Init_AddAttribute(new AttributeExternalKey("provider_id", array("targetclass"=>"bizOrganization", "label"=>"Provider", "description"=>"Provider for this contract", "allowed_values"=>null, "sql"=>"provider_id", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("provider_name", array("label"=>"Provider", "description"=>"name of the service provider", "allowed_values"=>null, "extkey_attcode"=> 'provider_id', "target_attcode"=>"name")));
    MetaModel::Init_AddAttribute(new AttributeString("service_name", array("label"=>"Service Name", "description"=>"Name of service for this contract", "allowed_values"=>null, "sql"=>"service_name", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("team_id", array("targetclass"=>"bizTeam", "label"=>"Team", "description"=>"Team managing this contract", "allowed_values"=>null, "sql"=>"team_id", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("team_name", array("label"=>"Team", "description"=>"name of the team managing this contract", "allowed_values"=>null, "extkey_attcode"=> 'team_id', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeEnum("service_level", array("label"=>"Service Level", "description"=>"Level of service for this contract", "allowed_values"=>new ValueSetEnum("Gold,Silver,Bronze"), "sql"=>"service_level", "default_value"=>"Bronze", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("cost_unit", array("label"=>"Cost unit", "description"=>"Cost unit to compute global cost for this contract", "allowed_values"=>new ValueSetEnum("Devices,Persons,Applications,Global"), "sql"=>"cost_unit", "default_value"=>"Global", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("cost_freq", array("label"=>"Cost frequency", "description"=>"Frequency of cost for this contract", "allowed_values"=>new ValueSetEnum("Monthly,Yearly,Once"), "sql"=>"cost_freq", "default_value"=>"Once", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("cost", array("label"=>"Cost", "description"=>"Cost of this contract", "allowed_values"=>null, "sql"=>"cost", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("currency", array("label"=>"Currency", "description"=>"Currency of cost for this contract", "allowed_values"=>new ValueSetEnum("Euros,Dollars"), "sql"=>"currency", "default_value"=>"Euros", "is_null_allowed"=>false, "depends_on"=>array())));

		MetaModel::Init_AddAttribute(new AttributeText("description", array("label"=>"Description", "description"=>"Description of this contract", "allowed_values"=>null, "sql"=>"description", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeDate("move2prod_date", array("label"=>"Date of move to production", "description"=>"Date when the contract is on production", "allowed_values"=>null, "sql"=>"move2prod_date", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeDate("end_prod", array("label"=>"Date of end of production", "description"=>"Date when the contract is stopped", "allowed_values"=>null, "sql"=>"end_date", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("status", array("label"=>"Status", "description"=>"Status of the contract", "allowed_values"=>new ValueSetEnum("New, Negotiating, Signed, Production, Notice, Finished"), "sql"=>"status", "default_value"=>"New", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("type", array("label"=>"Type", "description"=>"Type of the contract", "allowed_values"=>new ValueSetEnum("Hardware,Software,Support,Licence"), "sql"=>"type", "default_value"=>"Support", "is_null_allowed"=>false, "depends_on"=>array())));

		MetaModel::Init_AddAttribute(new AttributeInteger("version_number", array("label"=>"Version number", "description"=>"Revision number for this contract", "allowed_values"=>null, "sql"=>"version_number", "default_value"=>1, "is_null_allowed"=>false, "depends_on"=>array())));



		MetaModel::Init_InheritFilters();
		MetaModel::Init_AddFilterFromAttribute("service_name");
		MetaModel::Init_AddFilterFromAttribute("provider_id");
		MetaModel::Init_AddFilterFromAttribute("customer_id");
		MetaModel::Init_AddFilterFromAttribute("team_id");
		MetaModel::Init_AddFilterFromAttribute("team_name");
		MetaModel::Init_AddFilterFromAttribute("service_level");
		MetaModel::Init_AddFilterFromAttribute("end_prod");
		MetaModel::Init_AddFilterFromAttribute("status");
		MetaModel::Init_AddFilterFromAttribute("version_number");
		MetaModel::Init_AddFilterFromAttribute("name");
		MetaModel::Init_AddFilterFromAttribute("type");


		// Life cycle
		MetaModel::Init_DefineState("New", array("label"=>"New", "description"=>"Newly created contract", "attribute_inherit"=>null,
												 "attribute_list"=>array()));
		MetaModel::Init_DefineState("Negotiating", array("label"=>"Negotiating", "description"=>"The contract is being worked on", "attribute_inherit"=>null,
												"attribute_list"=>array()));
		MetaModel::Init_DefineState("Signed", array("label"=>"Signed", "description"=>"The contract has been signed", "attribute_inherit"=>null,
													"attribute_list"=>array()));
		MetaModel::Init_DefineState("Production", array("label"=>"Production", "description"=>"The contract is effective in production", "attribute_inherit"=>null,
												"attribute_list"=>array()));
		MetaModel::Init_DefineState("Notice", array("label"=>"Notice", "description"=>"The contract is about to be terminated", "attribute_inherit"=>null,
												"attribute_list"=>array()));
		MetaModel::Init_DefineState("Finished", array("label"=>"Finished", "description"=>"The contract is terminated", "attribute_inherit"=>null,
												"attribute_list"=>array()));

		MetaModel::Init_DefineStimulus("ev_freeze_version", new StimulusUserAction(array("label"=>"Freeze this version", "description"=>"This version of the contract is published")));
		MetaModel::Init_DefineStimulus("ev_sign", new StimulusUserAction(array("label"=>"Sign this contract", "description"=>"This contract is being signed")));
		MetaModel::Init_DefineStimulus("ev_begin", new StimulusUserAction(array("label"=>"Move to production", "description"=>"The contract becomes applicable in production")));
		MetaModel::Init_DefineStimulus("ev_notice", new StimulusUserAction(array("label"=>"Start notice period", "description"=>"The end date of the contract is approaching")));
		MetaModel::Init_DefineStimulus("ev_terminate", new StimulusUserAction(array("label"=>"Ends this contract", "description"=>"The contract is ending")));
		MetaModel::Init_DefineStimulus("ev_elapsed", new StimulusUserAction(array("label"=>"Times up [Do not click!]", "description"=>"The contract over")));

		MetaModel::Init_DefineTransition("New", "ev_freeze_version", array("target_state"=>"Negotiating", "actions"=>array('IncrementVersion'), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("Negotiating", "ev_freeze_version", array("target_state"=>"Negotiating", "actions"=>array('IncrementVersion'), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("Negotiating", "ev_sign", array("target_state"=>"Signed", "actions"=>array(), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("Negotiating", "ev_terminate", array("target_state"=>"Finished", "actions"=>array(), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("Signed", "ev_freeze_version", array("target_state"=>"Signed", "actions"=>array('IncrementVersion'), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("Signed", "ev_begin", array("target_state"=>"Production", "actions"=>array(), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("Signed", "ev_terminate", array("target_state"=>"Finished", "actions"=>array(), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("Production", "ev_freeze_version", array("target_state"=>"Production", "actions"=>array('IncrementVersion'), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("Production", "ev_elapsed", array("target_state"=>"Notice", "actions"=>array(), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("Production", "ev_terminate", array("target_state"=>"Finished", "actions"=>array(), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("Notice", "ev_elapsed", array("target_state"=>"Finished", "actions"=>array(), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("Notice", "ev_terminate", array("target_state"=>"Finished", "actions"=>array(), "user_restriction"=>null));


		MetaModel::Init_SetZListItems('details', array('name', 'status', 'customer_id', 'service_name','provider_id','type','description','team_id','service_level','cost','currency','cost_unit','cost_freq','move2prod_date','end_prod', 'version_number')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('name', 'status', 'customer_id', 'provider_id','service_name','service_level','type')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('name', 'status','service_name','provider_id','team_name','service_level','type')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'status', 'service_name','provider_id','team_name', 'service_level', 'org_id')); // Criteria of the advanced search form

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
* n-n link between any Infra and a Contract
*/
////////////////////////////////////////////////////////////////////////////////////
class lnkInfraContract extends cmdbAbstractObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable",
			"name" => "InfraContractLinks",
			"description" => "Infra covered by a contract",
			"key_type" => "autoincrement",
			"key_label" => "link_id",
			"name_attcode" => "coverage",  // ????
			"state_attcode" => "",
			"reconc_keys" => array("infra_id","contract_id"),  // ????
			"db_table" => "infra_contract_links",
			"db_key_field" => "link_id",
			"db_finalclass_field" => "",
			"display_template" => "../business/templates/default.html",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_AddAttribute(new AttributeExternalKey("infra_id", array("targetclass"=>"logInfra", "jointype"=> '', "label"=>"Infrastructure", "description"=>"The infrastructure impacted", "allowed_values"=>null, "sql"=>"infra_id", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("infra_name", array("label"=>"Infrastructure name", "description"=>"Name of the impacted infrastructure", "allowed_values"=>null, "extkey_attcode"=> 'infra_id', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeExternalField("infra_status", array("label"=>"Status", "description"=>"Status of the impacted infrastructure", "allowed_values"=>null, "extkey_attcode"=> 'infra_id', "target_attcode"=>"status")));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("contract_id", array("targetclass"=>"bizContract", "jointype"=> '', "label"=>"Contract name", "description"=>"Contract id", "allowed_values"=>null, "sql"=>"contract_id", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("contract_name", array("label"=>"Contract name", "description"=>"Name of the contract", "allowed_values"=>null, "extkey_attcode"=> 'contract_id', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeString("coverage", array("label"=>"coverage", "description"=>"coverage for the given infra", "allowed_values"=>null, "sql"=>"coverage", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));

		MetaModel::Init_AddFilterFromAttribute("infra_id");
		MetaModel::Init_AddFilterFromAttribute("contract_id");
		
		// Display lists
		MetaModel::Init_SetZListItems('details', array('infra_id', 'contract_id', 'coverage')); // Attributes to be displayed for a list
		MetaModel::Init_SetZListItems('list', array('infra_id', 'infra_status','contract_id' , 'coverage')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('infra_id', 'contract_id')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('infra_id', 'contract_id')); // Criteria of the advanced search form
	}

	
}
////////////////////////////////////////////////////////////////////////////////////
/**
* n-n link between any contact and a Contract
*/
////////////////////////////////////////////////////////////////////////////////////
class lnkContactContract extends cmdbAbstractObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable",
			"name" => "ContactContractLink",
			"description" => "Contact associated to a contract",
			"key_type" => "autoincrement",
			"key_label" => "link_id",
			"name_attcode" => "role",  // ????
			"state_attcode" => "",
			"reconc_keys" => array("role"),  // ????
			"db_table" => "contact_contract",
			"db_key_field" => "link_id",
			"db_finalclass_field" => "",
			"display_template" => "../business/templates/default.html",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_AddAttribute(new AttributeExternalKey("contact_id", array("targetclass"=>"bizContact", "jointype"=> '', "label"=>"Contact", "description"=>"The contact linked to contract", "allowed_values"=>null, "sql"=>"contact_id", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("contact_mail", array("label"=>"Contact E-mail", "description"=>"Mail for the contact", "allowed_values"=>null, "extkey_attcode"=> 'contact_id', "target_attcode"=>"email")));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("contract_id", array("targetclass"=>"bizContract", "jointype"=> '', "label"=>"Contract", "description"=>"Contract ID", "allowed_values"=>null, "sql"=>"contract_id", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("contract_name", array("label"=>"Contract name", "description"=>"Name of the contract", "allowed_values"=>null, "extkey_attcode"=> 'contract_id', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeString("role", array("label"=>"Role", "description"=>"Role of this contact for this contract", "allowed_values"=>null, "sql"=>"role", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_AddFilterFromAttribute("contract_id");
		MetaModel::Init_AddFilterFromAttribute("contact_id");
		
		// Display lists
		MetaModel::Init_SetZListItems('details', array('contract_id', 'contact_id', 'role')); // Attributes to be displayed for a list
		MetaModel::Init_SetZListItems('list', array('contract_id', 'contact_id', 'role')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('contract_id', 'contact_id')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('contract_id', 'contact_id')); // Criteria of the advanced search form
	}

	public function Generate(cmdbDataGenerator $oGenerator)
	{
		$this->Set('contract_id', $oGenerator->GenerateKey("logInfra", array('org_id' =>$oGenerator->GetOrganizationId() )));
		$this->Set('contact_id', $oGenerator->GenerateKey("bizIncidentTicket", array('org_id' =>$oGenerator->GetOrganizationId() )));
		$this->Set('role', $oGenerator->GenerateString("enum(none,mandatory,partial)"));
	}

}
////////////////////////////////////////////////////////////////////////////////////
/**
* n-n link between any Contract and a Document
*/
////////////////////////////////////////////////////////////////////////////////////
class lnkDocumentContract extends cmdbAbstractObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable",
			"name" => "DocumentsContractLinks",
			"description" => "A link between a document and another contract",
			"key_type" => "autoincrement",
			"key_label" => "link_id",
			"name_attcode" => "link_type",
			"state_attcode" => "",
			"reconc_keys" => array("doc_name", "contract_name"),
			"db_table" => "documents_contracts",
			"db_key_field" => "link_id",
			"db_finalclass_field" => "",
			"display_template" => "../business/templates/default.html",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_AddAttribute(new AttributeExternalKey("doc_id", array("targetclass"=>"bizDocument", "label"=>"Document Name", "description"=>"id of the Document", "allowed_values"=>null, "sql"=>"doc_id", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("doc_name", array("label"=>"Document", "description"=>"name of the document", "allowed_values"=>null, "extkey_attcode"=> 'doc_id', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("contract_id", array("targetclass"=>"bizContract", "label"=>"Contract", "description"=>"Contract linked to this document", "allowed_values"=>null, "sql"=>"contract_id", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("contract_name", array("label"=>"contract name", "description"=>"name of the linked contract", "allowed_values"=>null, "extkey_attcode"=> 'contract_id', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeString("link_type", array("label"=>"link_type", "description"=>"Type of the link", "allowed_values"=>null, "sql"=>"link_type", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_AddFilterFromAttribute("doc_id");
		MetaModel::Init_AddFilterFromAttribute("doc_name");
		MetaModel::Init_AddFilterFromAttribute("contract_id");
		MetaModel::Init_AddFilterFromAttribute("contract_name");
		MetaModel::Init_AddFilterFromAttribute("link_type");
		
		// Display lists
		MetaModel::Init_SetZListItems('details', array('doc_id', 'contract_name', 'link_type')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('doc_id', 'contract_name', 'link_type')); // Attributes to be displayed for a list
	}
}


?>

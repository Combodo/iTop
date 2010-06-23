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
 * Persistent classes for service management
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

////////////////////////////////////////////////////////////////////////////////////
/**
* Description of a service provided by an organization
*/
////////////////////////////////////////////////////////////////////////////////////
class bizService extends cmdbAbstractObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable",
			"key_type" => "autoincrement",
			"name_attcode" => "name",
			//"state_attcode" => "status",
			"state_attcode" => "",
			"reconc_keys" => array("org_id", "name"), // inherited attributes
			"db_table" => "services",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "../business/templates/service.html",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeString("name", array("allowed_values"=>null, "sql"=>"name", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		
		MetaModel::Init_AddAttribute(new AttributeExternalKey("org_id", array("targetclass"=>"bizOrganization", "allowed_values"=>null, "sql"=>"customer_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("provider_name", array("allowed_values"=>null, "extkey_attcode"=> 'org_id', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeEnum("service_category", array("allowed_values"=>new ValueSetEnum("Server,Network,End-User,Desktop,Application"), "sql"=>"service_category", "default_value"=>"End-User", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeText("description", array("allowed_values"=>null, "sql"=>"description", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("status", array("allowed_values"=>new ValueSetEnum("New, Implementation,Production,Obsolete"), "sql"=>"status", "default_value"=>"New", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("type", array("allowed_values"=>new ValueSetEnum("Hardware,Software,Support"), "sql"=>"type", "default_value"=>"Support", "is_null_allowed"=>false, "depends_on"=>array())));

/*
		// Life cycle
		MetaModel::Init_DefineState("New", array("attribute_inherit"=>null,
												 "attribute_list"=>array()));
		MetaModel::Init_DefineState("Implementation", array("attribute_inherit"=>null,
												"attribute_list"=>array()));
		MetaModel::Init_DefineState("Production", array("attribute_inherit"=>null,
												"attribute_list"=>array()));
		MetaModel::Init_DefineState("Obsolete", array("attribute_inherit"=>null,
												"attribute_list"=>array()));
	
		MetaModel::Init_DefineStimulus(new StimulusUserAction("ev_implement", array())); // "Implement this service / This service is under construction"
		MetaModel::Init_DefineStimulus(new StimulusUserAction("ev_move2prod", array())); // "Move to production / This service is now on production"
		MetaModel::Init_DefineStimulus(new StimulusUserAction("ev_obsolete", array())); // "Obsolete / Thi service is no more delivered"

		MetaModel::Init_DefineTransition("New", "ev_implement", array("target_state"=>"Implementation", "actions"=>array(), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("Implementation", "ev_move2prod", array("target_state"=>"Production", "actions"=>array(), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("Production", "ev_obsolete", array("target_state"=>"Obsolete", "actions"=>array('IncrementVersion'), "user_restriction"=>null));
*/	

		MetaModel::Init_SetZListItems('details', array('name', 'status', 'org_id','service_category','type','status','description')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('name', 'status', 'org_id','service_category','type')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('name', 'status','org_id','service_category','type')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'status','org_id','service_category','type')); // Criteria of the advanced search form

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
			"key_type" => "autoincrement",
			"name_attcode" => "name",
			//"state_attcode" => "status",
			"state_attcode" => "",
			"reconc_keys" => array("org_id", "name"), // inherited attributes
			"db_table" => "contracts",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "../business/templates/contract.html",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeString("name", array("allowed_values"=>null, "sql"=>"name", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("org_id", array("targetclass"=>"bizOrganization", "allowed_values"=>null, "sql"=>"customer_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("customer_name", array("allowed_values"=>null, "extkey_attcode"=> 'org_id', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("service_id", array("targetclass"=>"bizService", "allowed_values"=>null, "sql"=>"service_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("provider_name", array("allowed_values"=>null, "extkey_attcode"=> 'service_id', "target_attcode"=>"provider_name")));
		MetaModel::Init_AddAttribute(new AttributeExternalField("service_name", array("allowed_values"=>null, "extkey_attcode"=> 'service_id', "target_attcode"=>"name")));
 		MetaModel::Init_AddAttribute(new AttributeExternalKey("team_id", array("targetclass"=>"bizTeam", "allowed_values"=>null, "sql"=>"team_id", "is_null_allowed"=>true, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("team_name", array("allowed_values"=>null, "extkey_attcode"=> 'team_id', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeEnum("service_level", array("allowed_values"=>new ValueSetEnum("Gold,Silver,Bronze"), "sql"=>"service_level", "default_value"=>"Bronze", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("cost_unit", array("allowed_values"=>new ValueSetEnum("Devices,Persons,Applications,Global"), "sql"=>"cost_unit", "default_value"=>"Global", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("cost_freq", array("allowed_values"=>new ValueSetEnum("Monthly,Yearly,Once"), "sql"=>"cost_freq", "default_value"=>"Once", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("cost", array("allowed_values"=>null, "sql"=>"cost", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("currency", array("allowed_values"=>new ValueSetEnum("Euros,Dollars"), "sql"=>"currency", "default_value"=>"Euros", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeText("description", array("allowed_values"=>null, "sql"=>"description", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeDateTime("move2prod_date", array("allowed_values"=>null, "sql"=>"move2prod_date", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeDateTime("end_prod", array("allowed_values"=>null, "sql"=>"end_date", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("status", array("allowed_values"=>new ValueSetEnum("New, Negotiating, Signed, Production,Finished"), "sql"=>"status", "default_value"=>"New", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("type", array("allowed_values"=>new ValueSetEnum("Hardware,Software,Support,Licence"), "sql"=>"type", "default_value"=>"Support", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeInteger("version_number", array("allowed_values"=>null, "sql"=>"version_number", "default_value"=>1, "is_null_allowed"=>false, "depends_on"=>array())));

/*
		// Life cycle
		MetaModel::Init_DefineState("New", array("attribute_inherit"=>null,
												 "attribute_list"=>array('name' => OPT_ATT_MANDATORY,'org_id' => OPT_ATT_MANDATORY, 'service_id' => OPT_ATT_MANDATORY,'type' => OPT_ATT_MANDATORY, 'description' => OPT_ATT_MANDATORY)));
		MetaModel::Init_DefineState("Negotiating", array("attribute_inherit"=>null,
												"attribute_list"=>array('name' => OPT_ATT_READONLY,'org_id' => OPT_ATT_READONLY)));
		MetaModel::Init_DefineState("Signed", array("attribute_inherit"=>null,
													"attribute_list"=>array( 'name' => OPT_ATT_READONLY,'org_id' => OPT_ATT_READONLY, 'service_id' => OPT_ATT_READONLY,'type' => OPT_ATT_READONLY, 'service_level' => OPT_ATT_MANDATORY , 'cost_unit' => OPT_ATT_MANDATORY , 'cost_freq' => OPT_ATT_MANDATORY , 'cost' => OPT_ATT_MANDATORY, 'currency' => OPT_ATT_MANDATORY)));
		MetaModel::Init_DefineState("Production", array("attribute_inherit"=>null,
												"attribute_list"=>array('name' => OPT_ATT_READONLY,'org_id' => OPT_ATT_READONLY, 'service_id' => OPT_ATT_READONLY,'type' => OPT_ATT_READONLY, 'service_level' => OPT_ATT_READONLY , 'cost_unit' => OPT_ATT_READONLY , 'cost_freq' => OPT_ATT_READONLY , 'cost' => OPT_ATT_READONLY, 'currency' => OPT_ATT_READONLY,'move2prod_date' => OPT_ATT_MUSTPROMPT,'end_prod' => OPT_ATT_MANDATORY)));
		MetaModel::Init_DefineState("Finished", array("attribute_inherit"=>null,
												"attribute_list"=>array('name' => OPT_ATT_READONLY,'org_id' => OPT_ATT_READONLY, 'service_id' => OPT_ATT_READONLY,'type' => OPT_ATT_READONLY, 'service_level' => OPT_ATT_READONLY , 'cost_unit' => OPT_ATT_READONLY , 'cost_freq' => OPT_ATT_READONLY , 'cost' => OPT_ATT_READONLY, 'currency' => OPT_ATT_READONLY,'move2prod_date' => OPT_ATT_READONLY,'end_prod' => OPT_ATT_READONLY,'team_id' => OPT_ATT_READONLY,'description' => OPT_ATT_READONLY)));

		MetaModel::Init_DefineStimulus(new StimulusUserAction("ev_negociate", array())); // "Negotiate this contract / This version of the contract is published"
		MetaModel::Init_DefineStimulus(new StimulusUserAction("ev_sign", array())); // "Sign this contract / This contract is being signed"
		MetaModel::Init_DefineStimulus(new StimulusUserAction("ev_begin", array())); // "Move to production / The contract becomes applicable in production"
		MetaModel::Init_DefineStimulus(new StimulusUserAction("ev_terminate", array())); // "Ends this contract / The contract is ending"

		MetaModel::Init_DefineTransition("New", "ev_negociate", array("target_state"=>"Negotiating", "actions"=>array('IncrementVersion'), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("Negotiating", "ev_sign", array("target_state"=>"Signed", "actions"=>array(), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("Negotiating", "ev_terminate", array("target_state"=>"Finished", "actions"=>array(), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("Signed", "ev_begin", array("target_state"=>"Production", "actions"=>array('SetProdDate'), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("Signed", "ev_terminate", array("target_state"=>"Finished", "actions"=>array(), "user_restriction"=>null));
		MetaModel::Init_DefineTransition("Production", "ev_terminate", array("target_state"=>"Finished", "actions"=>array(), "user_restriction"=>null));
*/

		MetaModel::Init_SetZListItems('details', array('name', 'status', 'org_id', 'service_id','provider_name','type','description','team_id','service_level','cost','currency','cost_unit','cost_freq','move2prod_date','end_prod', 'version_number')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('name', 'status', 'org_id', 'service_id','provider_name','service_name','service_level','type')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('name', 'status','service_id','provider_name','team_name','service_level','type')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'status','service_id','team_name', 'service_level', 'org_id')); // Criteria of the advanced search form

	}
	
	// State machine actions
	public function IncrementVersion($sStimulusCode)
	{
		$this->Set('version_number', $this->Get('version_number') + 1);
		return true;
	}
	
		public function SetProdDate($sStimulusCode)
	{
		$this->Set('move2prod_date', time());
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
			"key_type" => "autoincrement",
			"name_attcode" => "coverage",  // ????
			"state_attcode" => "",
			"reconc_keys" => array("infra_id","contract_id"),  // ????
			"db_table" => "infra_contract_links",
			"db_key_field" => "link_id",
			"db_finalclass_field" => "",
			"display_template" => "../business/templates/default.html",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_AddAttribute(new AttributeExternalKey("infra_id", array("targetclass"=>"logInfra", "jointype"=> '', "allowed_values"=>null, "sql"=>"infra_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("infra_name", array("allowed_values"=>null, "extkey_attcode"=> 'infra_id', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeExternalField("infra_status", array("allowed_values"=>null, "extkey_attcode"=> 'infra_id', "target_attcode"=>"status")));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("contract_id", array("targetclass"=>"bizContract", "jointype"=> '', "allowed_values"=>null, "sql"=>"contract_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("contract_name", array("allowed_values"=>null, "extkey_attcode"=> 'contract_id', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeString("coverage", array("allowed_values"=>null, "sql"=>"coverage", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("service_level", array("allowed_values"=>null, "sql"=>"sla", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		
		// Display lists
		MetaModel::Init_SetZListItems('details', array('infra_id', 'contract_id', 'coverage','service_level')); // Attributes to be displayed for a list
		MetaModel::Init_SetZListItems('list', array('infra_id', 'infra_status','contract_id' , 'coverage','service_level')); // Attributes to be displayed for a list
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
			"key_type" => "autoincrement",
			"name_attcode" => "role",  // ????
			"state_attcode" => "",
			"reconc_keys" => array("role"),  // ????
			"db_table" => "contact_Contract",
			"db_key_field" => "link_id",
			"db_finalclass_field" => "",
			"display_template" => "../business/templates/default.html",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_AddAttribute(new AttributeExternalKey("contact_id", array("targetclass"=>"bizContact", "jointype"=> '', "allowed_values"=>null, "sql"=>"contact_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("contact_mail", array("allowed_values"=>null, "extkey_attcode"=> 'contact_id', "target_attcode"=>"email")));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("contract_id", array("targetclass"=>"bizContract", "jointype"=> '', "allowed_values"=>null, "sql"=>"contract_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("contract_name", array("allowed_values"=>null, "extkey_attcode"=> 'contract_id', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeString("role", array("allowed_values"=>null, "sql"=>"role", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		
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
			"key_type" => "autoincrement",
			"name_attcode" => "link_type",
			"state_attcode" => "",
			"reconc_keys" => array("doc_name", "contract_name"),
			"db_table" => "documents_contracts",
			"db_key_field" => "link_id",
			"db_finalclass_field" => "",
			"display_template" => "../business/templates/default.html",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_AddAttribute(new AttributeExternalKey("doc_id", array("targetclass"=>"bizDocument", "allowed_values"=>null, "sql"=>"doc_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("doc_name", array("allowed_values"=>null, "extkey_attcode"=> 'doc_id', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("contract_id", array("targetclass"=>"bizContract", "allowed_values"=>null, "sql"=>"contract_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("contract_name", array("allowed_values"=>null, "extkey_attcode"=> 'contract_id', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeString("link_type", array("allowed_values"=>null, "sql"=>"link_type", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		
		// Display lists
		MetaModel::Init_SetZListItems('details', array('doc_id', 'contract_name', 'link_type')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('doc_id', 'contract_name', 'link_type')); // Attributes to be displayed for a list
	}
}

// require_once('ServiceRequest.business.php');

?>

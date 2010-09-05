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

////////////////////////////////////////////////////////////////////////////////////
/**
* Description of known error
*/
////////////////////////////////////////////////////////////////////////////////////
class KnownError extends cmdbAbstractObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "bizmodel,searchable",
			"name" => "Known Error",
			"description" => "Error documented for a known issue",
			"key_type" => "autoincrement",
			"key_label" => "id",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array("org_id", "name"), // inherited attributes
			"db_table" => "known_error",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
			"icon" => "../modules/itop-knownerror-mgmt-1.0.0/images/known-error.png",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeString("name", array("allowed_values"=>null, "sql"=>"name", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));

		MetaModel::Init_AddAttribute(new AttributeExternalKey("org_id", array("targetclass"=>"Organization", "allowed_values"=>null, "sql"=>"cust_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("cust_name", array("allowed_values"=>null, "extkey_attcode"=> 'org_id', "target_attcode"=>"name")));

		MetaModel::Init_AddAttribute(new AttributeExternalKey("problem_id", array("targetclass"=>"Problem", "allowed_values"=>null, "sql"=>"problem_id", "is_null_allowed"=>true, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("problem_ref", array("allowed_values"=>null, "extkey_attcode"=> 'problem_id', "target_attcode"=>"ref")));
		MetaModel::Init_AddAttribute(new AttributeText("symptom", array("allowed_values"=>null, "sql"=>"symptom", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
 	 	MetaModel::Init_AddAttribute(new AttributeText("root_cause", array("allowed_values"=>null, "sql"=>"rootcause", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
 	  	MetaModel::Init_AddAttribute(new AttributeText("workaround", array("allowed_values"=>null, "sql"=>"workaround", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
 		MetaModel::Init_AddAttribute(new AttributeText("solution", array("allowed_values"=>null, "sql"=>"solution", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
 
		MetaModel::Init_AddAttribute(new AttributeString("error_code", array("allowed_values"=>null, "sql"=>"error_code", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("domain", array("allowed_values"=>new ValueSetEnum("Network, Server, Application, Desktop"), "sql"=>"domain", "default_value"=>"Application", "is_null_allowed"=>false, "depends_on"=>array())));
	  	MetaModel::Init_AddAttribute(new AttributeString("vendor", array("allowed_values"=>null, "sql"=>"vendor", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
    		MetaModel::Init_AddAttribute(new AttributeString("model", array("allowed_values"=>null, "sql"=>"model", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
    		MetaModel::Init_AddAttribute(new AttributeString("version", array("allowed_values"=>null, "sql"=>"version", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
       		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("ci_list", array("linked_class"=>"lnkInfraError", "ext_key_to_me"=>"error_id", "ext_key_to_remote"=>"infra_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));
      		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("document_list", array("linked_class"=>"lnkDocumentError", "ext_key_to_me"=>"error_id", "ext_key_to_remote"=>"doc_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array())));




		MetaModel::Init_SetZListItems('details', array('name', 'org_id','problem_id','error_code','domain','vendor','model','version', 'symptom','root_cause','workaround','solution','ci_list','document_list')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('org_id','problem_id','error_code', 'symptom')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('name', 'org_id','problem_id','error_code','domain','symptom')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'org_id','problem_id', 'error_code','domain','symptom')); // Criteria of the advanced search form

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
			"name" => "InfraErrorLinks",
			"description" => "Infra related to a known error",
			"key_type" => "autoincrement",
			"key_label" => "link_id",
			"name_attcode" => "",  // ????
			"state_attcode" => "",
			"reconc_keys" => array("infra_id","error_id"),  // ????
			"db_table" => "infra_error_links",
			"db_key_field" => "link_id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_AddAttribute(new AttributeExternalKey("infra_id", array("targetclass"=>"FunctionalCI", "jointype"=> '',"allowed_values"=>null, "sql"=>"infra_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("infra_name", array("allowed_values"=>null, "extkey_attcode"=> 'infra_id', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeExternalField("infra_status", array("allowed_values"=>null, "extkey_attcode"=> 'infra_id', "target_attcode"=>"status")));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("error_id", array("targetclass"=>"KnownError", "jointype"=> '', "allowed_values"=>null, "sql"=>"error_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("error_name", array( "allowed_values"=>null, "extkey_attcode"=> 'error_id', "target_attcode"=>"name")));
	        MetaModel::Init_AddAttribute(new AttributeString("reason", array("allowed_values"=>null, "sql"=>"dummy", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		
		// Display lists
		MetaModel::Init_SetZListItems('details', array('infra_id', 'error_id','reason')); // Attributes to be displayed for a list
		MetaModel::Init_SetZListItems('list', array('infra_id', 'infra_status','error_id','reason')); // Attributes to be displayed for a list
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
			"name" => "DocumentsErrorLinks",
			"description" => "A link between a document and a known error",
			"key_type" => "autoincrement",
			"key_label" => "link_id",
			"name_attcode" => "link_type",
			"state_attcode" => "",
			"reconc_keys" => array("doc_id", "error_id"),
			"db_table" => "documents_error_link",
			"db_key_field" => "link_id",
			"db_finalclass_field" => "",
			"display_template" => "../business/templates/default.html",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_AddAttribute(new AttributeExternalKey("doc_id", array("targetclass"=>"Document","allowed_values"=>null, "sql"=>"doc_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("doc_name", array("allowed_values"=>null, "extkey_attcode"=> 'doc_id', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("error_id", array("targetclass"=>"KnownError","allowed_values"=>null, "sql"=>"error_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("error_name", array("allowed_values"=>null, "extkey_attcode"=> 'error_id', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeString("link_type", array("allowed_values"=>null, "sql"=>"link_type", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		
		// Display lists
		MetaModel::Init_SetZListItems('details', array('doc_id', 'error_name', 'link_type')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('doc_id', 'error_name', 'link_type')); // Attributes to be displayed for a list
	}
}


$oMyMenuGroup = new MenuGroup('ProblemManagement', 42 /* fRank */);
new OQLMenuNode('Problem:KnownErrors', 'SELECT KnownError', $oMyMenuGroup->GetIndex(), 3 /* fRank */);

?>

<?php
require_once('../application/cmdbabstract.class.inc.php');

/**
 * This class manages the audit "categories". Each category defines a set of objects
 * to check and is linked to a set of rules that determine the valid or invalid objects
 * inside the set 
 */
class AuditCategory extends cmdbAbstractObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "application",
			"key_type" => "autoincrement",
			"key_label" => "",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array('name'),
			"db_table" => "priv_auditcategory",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "../application/templates/audit_category.html",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_AddAttribute(new AttributeString("name", array("description"=>"Short name for this category", "allowed_values"=>null, "sql"=>"name", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("description", array("allowed_values"=>null, "sql"=>"description", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeText("definition_set", array("allowed_values"=>null, "sql"=>"definition_set", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('name', 'description', 'definition_set')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('name', 'description', )); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('name', 'description')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'description', 'definition_set')); // Criteria of the advanced search form
	}
}
?>

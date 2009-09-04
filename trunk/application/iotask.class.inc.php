<?php
require_once('../application/cmdbabstract.class.inc.php');

/**
 * This class manages the input/output tasks
 * for synchronizing information with external data sources 
 */
class InputOutputTask extends cmdbAbstractObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "application",
			"name" => "IOTask",
			"description" => "Input / Output Task for synchronizing information with external data sources",
			"key_type" => "autoincrement",
			"key_label" => "",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_iotask",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "../business/templates/default.html",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_AddAttribute(new AttributeString("name", array("label"=>"Task Name", "description"=>"Short name for this task", "allowed_values"=>null, "sql"=>"name", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("description", array("label"=>"Task Description", "description"=>"Long description for this task", "allowed_values"=>null, "sql"=>"description", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("category", array("label"=>"Category", "description"=>"Type of task", "allowed_values"=>new ValueSetEnum('Input, Ouput'), "sql"=>"category", "default_value"=>"Input", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("source_type", array("label"=>"Source Type", "description"=>"Type of data source", "allowed_values"=>new ValueSetEnum('File, Database, Web Service'), "sql"=>"source_type", "default_value"=>"File", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("source_subtype", array("label"=>"Source Subtype", "description"=>"Subtype of Data Source", "allowed_values"=>new ValueSetEnum('Oracle, MySQL, Postgress, MSSQL, SOAP, HTTP-Get, HTTP-Post, XML/RPC, CSV, XML, Excel'), "sql"=>"source_subtype", "default_value"=>"CSV", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("source_path", array("label"=>"Source Path", "description"=>"Path to the icon o the menu", "allowed_values"=>null, "sql"=>"source_path", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("objects_class", array("label"=>"Objects Class", "description"=>"Class of the objects processed by this task", "allowed_values"=>new ValueSetEnumClasses(), "sql"=>"objects_class", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("test_mode", array("label"=>"Test Mode", "description"=>"If set to 'Yes' the modifications are not applied", "allowed_values"=>new ValueSetEnum('Yes,No'), "sql"=>"test_mode", "default_value"=>'No', "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("verbose_mode", array("label"=>"Verbose Mode", "description"=>"If set to 'Yes' extra debug information is added to the log", "allowed_values"=>new ValueSetEnum('Yes,No'), "sql"=>"verbose_mode", "default_value" => 'No', "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("options", array("label"=>"Options", "description"=>"Reconciliation options", "allowed_values"=>new ValueSetEnum('Full, Update Only, Creation Only'), "sql"=>"options", "default_value"=> 'Full', "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_AddFilterFromAttribute("name");
		MetaModel::Init_AddFilterFromAttribute("description");
		MetaModel::Init_AddFilterFromAttribute("category");
		MetaModel::Init_AddFilterFromAttribute("source_type");
		MetaModel::Init_AddFilterFromAttribute("source_subtype");
		MetaModel::Init_AddFilterFromAttribute("objects_class");

		// Display lists
		MetaModel::Init_SetZListItems('details', array('name', 'description', 'category', 'objects_class', 'source_type', 'source_subtype', 'source_path' , 'options', 'test_mode', 'verbose_mode')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('name', 'description', 'category', 'objects_class', 'source_type', 'source_subtype', 'options')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('name', 'category', 'objects_class', 'source_type', 'source_subtype')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'description', 'category', 'objects_class', 'source_type', 'source_subtype')); // Criteria of the advanced search form
	}
}
?>

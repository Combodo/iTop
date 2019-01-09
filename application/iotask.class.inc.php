<?php
// Copyright (C) 2010-2012 Combodo SARL
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify	
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>


/**
 * Persistent class InputOutputTask
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

require_once(APPROOT.'/application/cmdbabstract.class.inc.php');

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
			"key_type" => "autoincrement",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_iotask",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_AddAttribute(new AttributeString("name", array("allowed_values"=>null, "sql"=>"name", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("description", array("allowed_values"=>null, "sql"=>"description", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("category", array("allowed_values"=>new ValueSetEnum('Input, Ouput'), "sql"=>"category", "default_value"=>"Input", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("source_type", array("allowed_values"=>new ValueSetEnum('File, Database, Web Service'), "sql"=>"source_type", "default_value"=>"File", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("source_subtype", array("allowed_values"=>new ValueSetEnum('Oracle, MySQL, Postgress, MSSQL, SOAP, HTTP-Get, HTTP-Post, XML/RPC, CSV, XML, Excel'), "sql"=>"source_subtype", "default_value"=>"CSV", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("source_path", array("allowed_values"=>null, "sql"=>"source_path", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeClass("objects_class", array("class_category"=>"", "more_values"=>"", "sql"=>"objects_class", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("test_mode", array("allowed_values"=>new ValueSetEnum('Yes,No'), "sql"=>"test_mode", "default_value"=>'No', "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("verbose_mode", array("allowed_values"=>new ValueSetEnum('Yes,No'), "sql"=>"verbose_mode", "default_value" => 'No', "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("options", array("allowed_values"=>new ValueSetEnum('Full, Update Only, Creation Only'), "sql"=>"options", "default_value"=> 'Full', "is_null_allowed"=>true, "depends_on"=>array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('name', 'description', 'category', 'objects_class', 'source_type', 'source_subtype', 'source_path' , 'options', 'test_mode', 'verbose_mode')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('description', 'category', 'objects_class', 'source_type', 'source_subtype', 'options')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('name', 'category', 'objects_class', 'source_type', 'source_subtype')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'description', 'category', 'objects_class', 'source_type', 'source_subtype')); // Criteria of the advanced search form
	}
}
?>

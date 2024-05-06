<?php
// Copyright (C) 2010-2024 Combodo SAS
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
 * Persistent class ModuleInstallation to record the installed modules
 * Log of module installations
 *
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

class ModuleInstallation extends cmdbAbstractObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category"            => "core,view_in_gui",
			"key_type"            => "autoincrement",
			'name_attcode'        => array('name', 'version'),
			"state_attcode"       => "",
			"reconc_keys"         => array(),
			"db_table" => "priv_module_install",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			'order_by_default' => array('installed' => false),
		);
		MetaModel::Init_Params($aParams);
		//MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeString("name", array("allowed_values" => null, "sql" => "name", "default_value" => null, "is_null_allowed" => false, "depends_on" => array())));
		MetaModel::Init_AddAttribute(new AttributeString("version", array("allowed_values" => null, "sql" => "version", "default_value" => null, "is_null_allowed" => true, "depends_on" => array())));
		MetaModel::Init_AddAttribute(new AttributeDateTime("installed", array("allowed_values" => null, "sql" => "installed", "default_value" => null, "is_null_allowed" => true, "depends_on" => array())));
		MetaModel::Init_AddAttribute(new AttributeText("comment", array("allowed_values" => null, "sql" => "comment", "default_value" => null, "is_null_allowed" => true, "depends_on" => array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("parent_id", array("targetclass" => "ModuleInstallation", "jointype" => "", "allowed_values" => null, "sql" => "parent_id", "is_null_allowed" => true, "on_target_delete" => DEL_MANUAL, "depends_on" => array())));


		// Display lists
		MetaModel::Init_SetZListItems('details', array('name', 'version', 'installed', 'comment', 'parent_id')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('installed', 'comment')); // Attributes to be displayed for a list
		// Search criteria
//		MetaModel::Init_SetZListItems('standard_search', array('name')); // Criteria of the std search form
//		MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
	}
}

/**
 * Persistent class ExtensionInstallation to record the installed extensions
 * Log of extensions installations
 *
 * @copyright   Copyright (C) 2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
class ExtensionInstallation extends cmdbAbstractObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category"                => "core,view_in_gui",
			"key_type"                => "autoincrement",
			"name_attcode"            => "",
			"state_attcode"           => "",
			"reconc_keys"             => array(),
				"db_table" => "priv_extension_install",
				"db_key_field" => "id",
				"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		//MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeString("code", array("allowed_values"=>null, "sql"=>"code", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("label", array("allowed_values"=>null, "sql"=>"label", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("version", array("allowed_values"=>null, "sql"=>"version", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("source", array("allowed_values"=>null, "sql"=>"source", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeDateTime("installed", array("allowed_values"=>null, "sql"=>"installed", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		
		
		// Display lists
		MetaModel::Init_SetZListItems('details', array('code', 'label', 'version', 'installed', 'source')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('code', 'label', 'version', 'installed', 'source')); // Attributes to be displayed for a list
		MetaModel::Init_SetZListItems('standard_search', array('code', 'label', 'version', 'installed', 'source')); // Attributes to be displayed in the search form
	}
}



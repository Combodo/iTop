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
 * This class manages the audit "categories". Each category defines a set of objects
 * to check and is linked to a set of rules that determine the valid or invalid objects
 * inside the set 
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

require_once(APPROOT.'/application/cmdbabstract.class.inc.php');

class AuditCategory extends cmdbAbstractObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "application",
			"key_type" => "autoincrement",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array('name'),
			"db_table" => "priv_auditcategory",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_AddAttribute(new AttributeString("name", array("description"=>"Short name for this category", "allowed_values"=>null, "sql"=>"name", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("description", array("allowed_values"=>null, "sql"=>"description", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeOQL("definition_set", array("allowed_values"=>null, "sql"=>"definition_set", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSet("rules_list", array("linked_class"=>"AuditRule", "ext_key_to_me"=>"category_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array(), "edit_mode" => LINKSET_EDITMODE_INPLACE)));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('name', 'description', 'definition_set', 'rules_list')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('description', )); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('name', 'description')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('name', 'description', 'definition_set')); // Criteria of the advanced search form
	}
}
?>

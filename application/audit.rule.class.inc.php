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
 * This class manages the audit "rule" linked to a given audit category.
 * Each rule is based on an OQL expression that returns either the "good" objects
 * or the "bad" ones. The core audit engines computes the complement to the definition
 * set when needed to obtain either the valid objects, or the ones with an error
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

require_once(APPROOT.'/application/audit.category.class.inc.php');

class AuditRule extends cmdbAbstractObject
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
			"db_table" => "priv_auditrule",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_AddAttribute(new AttributeString("name", array("allowed_values"=>null, "sql"=>"name", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("description", array("allowed_values"=>null, "sql"=>"description", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeOQL("query", array("allowed_values"=>null, "sql"=>"query", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("valid_flag", array("allowed_values"=>new ValueSetEnum('true,false'), "sql"=>"valid_flag", "default_value"=>"true", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("category_id", array("allowed_values"=>null, "sql"=>"category_id", "targetclass"=>"AuditCategory", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("category_name", array("allowed_values"=>null, "extkey_attcode"=> 'category_id', "target_attcode"=>"name")));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('category_id', 'name', 'description', 'query', 'valid_flag')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('category_id', 'description', 'valid_flag')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('category_id', 'name', 'description', 'valid_flag')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('advanced_search', array('category_id', 'name', 'description', 'valid_flag', 'query')); // Criteria of the advanced search form
	}
}
?>

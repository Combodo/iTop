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
 * This class manages the audit "categories". Each category defines a set of objects
 * to check and is linked to a set of rules that determine the valid or invalid objects
 * inside the set
 *
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

require_once(APPROOT.'/application/cmdbabstract.class.inc.php');

class AuditCategory extends cmdbAbstractObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category"            => "application,grant_by_profile",
			"key_type"            => "autoincrement",
			"name_attcode"        => "name",
			"state_attcode"       => "",
			"reconc_keys"         => array('name'),
			"db_table"            => "priv_auditcategory",
			"db_key_field"        => "id",
			"db_finalclass_field" => "",
			'style'               => new ormStyle(null, null, null, null, null, '../images/icons/icons8-audit-folder.svg'),
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_AddAttribute(new AttributeString("name", array("description"=>"Short name for this category", "allowed_values"=>null, "sql"=>"name", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("description", array("allowed_values"=>null, "sql"=>"description", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeOQL("definition_set", array("allowed_values"=>null, "sql"=>"definition_set", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSet("rules_list", array("linked_class"=>"AuditRule", "ext_key_to_me"=>"category_id", "allowed_values"=>null, "count_min"=>0, "count_max"=>0, "depends_on"=>array(), "edit_mode" => LINKSET_EDITMODE_INPLACE, "tracking_level" => LINKSET_TRACKING_ALL)));
		MetaModel::Init_AddAttribute(new AttributeInteger("ok_error_tolerance", array("allowed_values"=>null, "sql"=>"ok_error_tolerance", "default_value"=>5, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeInteger("warning_error_tolerance", array("allowed_values" => null, "sql" => "warning_error_tolerance", "default_value" => 25, "is_null_allowed" => true, "depends_on" => array())));
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("domains_list",
			array("linked_class" => "lnkAuditCategoryToAuditDomain", "ext_key_to_me" => "category_id", "ext_key_to_remote" => "domain_id", "allowed_values" => null, "count_min" => 0, "count_max" => 0, "depends_on" => array(), "display_style" => 'property')));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('name', 'description', 'definition_set', 'ok_error_tolerance', 'warning_error_tolerance', 'rules_list', 'domains_list')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('description', )); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('description', 'definition_set')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('default_search', array('name', 'description')); // Criteria of the default search form
	}

	/**
	 * @param int $iTotal
	 * @param int $iErrors
	 *
	 * @return string A semantic color name (eg. red, green, orange, success, failure, ... {@see css/backoffice/utils/variables/colors/_semantic-palette.scss}) to use for this category depending on its error count and tolerance
	 * @throws \CoreException
	 *
	 * @since 3.1.0
	 */
	public function GetReportColor($iTotal, $iErrors)
	{
		$sResult = 'red';
		if ( ($iTotal == 0) || ($iErrors / $iTotal) <= ($this->Get('ok_error_tolerance') / 100) ) {
			$sResult = 'green';
		} else if (($iErrors / $iTotal) <= ($this->Get('warning_error_tolerance') / 100)) {
			$sResult = 'orange';
		}

		return $sResult;
	}

	public static function GetShortcutActions($sFinalClass)
	{
		$aShortcutActions = parent::GetShortcutActions($sFinalClass);
		if (!in_array('UI:Menu:RunAudit', $aShortcutActions)) {
			$aShortcutActions[] = 'UI:Menu:RunAudit';
		}

		return $aShortcutActions;
	}
}
?>

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

/**
 * @since 3.1.0
 */
class AuditDomain extends cmdbAbstractObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category"                   => "application,grant_by_profile",
			"key_type"                   => "autoincrement",
			"name_attcode"               => "name",
			"complementary_name_attcode" => array('description'),
			"state_attcode"              => "",
			"reconc_keys"                => array('name'),
			"db_table"                   => "priv_auditdomain",
			"db_key_field"               => "id",
			"db_finalclass_field"        => "",
			'style'                      => new ormStyle(null, null, null, null, null, '../images/icons/icons8-audit-album.svg'),
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_AddAttribute(new AttributeString("name", array("description" => "Short name for this category", "allowed_values" => null, "sql" => "name", "default_value" => "", "is_null_allowed" => false, "depends_on" => array())));
		MetaModel::Init_AddAttribute(new AttributeString("description", array("allowed_values" => null, "sql" => "description", "default_value" => "", "is_null_allowed" => true, "depends_on" => array())));
		MetaModel::Init_AddAttribute(new AttributeImage("icon", array("is_null_allowed" => true, "depends_on" => array(), "display_max_width" => 96, "display_max_height" => 96, "storage_max_width" => 256, "storage_max_height" => 256, "default_image" => null, "always_load_in_tables" => false)));
		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("categories_list",
			array("linked_class" => "lnkAuditCategoryToAuditDomain", "ext_key_to_me" => "domain_id", "ext_key_to_remote" => "category_id", "allowed_values" => null, "count_min" => 0, "count_max" => 0, "depends_on" => array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('name', 'description', 'icon', 'categories_list')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('description',)); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('description')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('default_search', array('name', 'description')); // Criteria of the default search form
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

/**
 * @since 3.1.0
 */
class lnkAuditCategoryToAuditDomain extends cmdbAbstractObject
{
	/**
	 * @throws \CoreException
	 * @throws \Exception
	 */
	public static function Init()
	{
		$aParams = array
		(
			"category"            => "application,grant_by_profile",
			"key_type"            => "autoincrement",
			"name_attcode"        => "",
			"state_attcode"       => "",
			"reconc_keys"         => array('category_id', 'domain_id'),
			"db_table"            => "priv_link_audit_category_domain",
			"db_key_field"        => "id",
			"db_finalclass_field" => "",
			"is_link"             => true,
			'uniqueness_rules'    => array(
				'no_duplicate' => array(
					'attributes'  => array(
						0 => 'category_id',
						1 => 'domain_id',
					),
					'filter'      => '',
					'disabled'    => false,
					'is_blocking' => true,
				),
			),
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_AddAttribute(new AttributeExternalKey("category_id", array("targetclass" => "AuditCategory", "jointype" => '', "allowed_values" => null, "sql" => "category_id", "is_null_allowed" => false, "on_target_delete" => DEL_AUTO, "depends_on" => array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("category_name", array("allowed_values" => null, "extkey_attcode" => 'category_id', "target_attcode" => "name")));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("domain_id", array("targetclass" => "AuditDomain", "jointype" => '', "allowed_values" => null, "sql" => "domain_id", "is_null_allowed" => false, "on_target_delete" => DEL_AUTO, "depends_on" => array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("domain_name", array("allowed_values" => null, "extkey_attcode" => 'domain_id', "target_attcode" => "name")));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('category_id', 'domain_id'));
		MetaModel::Init_SetZListItems('list', array('category_id', 'domain_id'));
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('category_id', 'domain_id'));
	}
}


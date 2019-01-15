<?php
/**
 *
 * @copyright   Copyright (C) 2010-2019 Combodo SARL
 * @license     https://www.combodo.com/documentation/combodo-software-license.html
 *
 */

/**
 * To trigger notifications when an object is in scope of an Expiration Rule
 */
class TriggerOnExpirationRule extends TriggerOnObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "core/cmdb,application", // "application" category => admins can perform a CSV import
			"key_type" => "autoincrement",
			"name_attcode" => "description",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_trigger_expirationrule",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
			"display_template" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		// Display lists
		MetaModel::Init_SetZListItems('details', array('description', 'target_class', 'filter', 'action_list')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('finalclass', 'target_class', 'description')); // Attributes to be displayed for a list
		// Search criteria
	}
}
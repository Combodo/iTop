<?php

/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


/**
 * Class TriggerOnAttachmentCreate
 *
 * @since 3.1.1
 */
class TriggerOnAttachmentDelete extends TriggerOnObject
{
	/**
	 * @inheritDoc
	 * @throws \CoreException
	 * @throws \Exception
	 */
	public static function Init()
	{
		$aParams = array
		(
			"category"            => "grant_by_profile,core/cmdb,application",
			"key_type"            => "autoincrement",
			"name_attcode"        => "description",
            "complementary_name_attcode" => ['finalclass', 'complement'],
			"state_attcode"       => "",
			"reconc_keys"         => ['description'],
			"db_table"            => "priv_trigger_onattdelete",
			"db_key_field"        => "id",
			"db_finalclass_field" => "",
			"display_template"    => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		// Display lists
		MetaModel::Init_SetZListItems('details', array('description', 'context', 'filter', 'action_list', 'target_class')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('finalclass', 'target_class')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('description', 'target_class')); // Criteria of the std search form

	}

}
<?php

/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

use MetaModel;
use ormSet;
use TriggerOnObjectUpdate;

/**
 * Class TriggerOnCreateAttachment
 *
 * @since 3.1.1
 */
class TriggerOnCreateAttachment extends TriggerOnObjectUpdate
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
			"state_attcode"       => "",
			"reconc_keys"         => array('description'),
			"db_table"            => "priv_trigger_onattcreate",
			"db_key_field"        => "id",
			"db_finalclass_field" => "",
			"display_template"    => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();


		// Display lists
		MetaModel::Init_SetZListItems('details', array('description', 'context', 'filter', 'action_list')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('finalclass', 'target_class')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('description', 'target_class')); // Criteria of the std search form
	}


	public function ComputeValues()
	{
		$this->Set('target_class', 'Attachment');
		$oValue = new ormSet('TriggerOnObjectUpdate', 'target_attcodes');
		$oValue->SetValues(['temp_id']);
		$this->Set('target_attcodes', $oValue);

		parent::ComputeValues();
	}
}
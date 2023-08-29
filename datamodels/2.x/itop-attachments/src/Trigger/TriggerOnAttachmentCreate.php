<?php

/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

use MetaModel;
use ormSet;
use TriggerOnObjectUpdate;

/**
 * Class TriggerOnAttachmentCreate
 *
 * @since 3.1.1
 */
class TriggerOnAttachmentCreate extends TriggerOnObjectUpdate
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
		MetaModel::Init_SetZListItems('details', array('description', 'context', 'filter', 'action_list', 'filter_class')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('finalclass', 'filter_class')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('description', 'filter_class')); // Criteria of the std search form
		MetaModel::Init_AddAttribute(new AttributeClass("filter_class",
			array("class_category" => "bizmodel", "more_values" => "User,UserExternal,UserInternal,UserLDAP,UserLocal", "sql" => "filter_class", "default_value" => null, "is_null_allowed" => false, "depends_on" => array(), "class_exclusion_list" => "Attachment")));

	}


	public function ComputeValues()
	{
		$this->Set('target_class', 'Attachment');
		$oValue = new ormSet('TriggerOnObjectUpdate', 'target_attcodes');
		$oValue->SetValues(['temp_id']);
		$this->Set('target_attcodes', $oValue);

		parent::ComputeValues();
	}

	public function IsTargetObject($iObjectId, $aChanges = array())
	{
		$sFilter = trim($this->Get('filter') ?? '');
		if (strlen($sFilter) > 0) {
			$oSearch = DBObjectSearch::FromOQL($sFilter);
			$oSearch->AddCondition('id', $iObjectId, '=');
			if (utils::IsNotNullOrEmptyString($this->Get('filter_class'))) {
				$oSearch->AddCondition('item_class', $this->Get('filter_class'), '=');
			}
			$oSearch->AllowAllData();
			$oSet = new DBObjectSet($oSearch);
			$bRet = ($oSet->Count() > 0);
		} else {
			if (utils::IsNotNullOrEmptyString($this->Get('filter_class'))) {
				$oSearch = new DBObjectSearch('Attachment');
				$oSearch->AddCondition('item_class', $this->Get('filter_class'), '=');
				$oSearch->AddCondition('id', $iObjectId, '=');
				$oSearch->AllowAllData();
				$oSet = new DBObjectSet($oSearch);
				$bRet = ($oSet->Count() > 0);
			} else {
				$bRet = true;
			}
		}

		return $bRet;
	}

}
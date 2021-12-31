<?php
/*
 * Copyright (C) 2010-2021 Combodo SARL
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */

use Combodo\iTop\Application\UI\Base\Component\Alert\AlertUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Field\Field;
use Combodo\iTop\Application\UI\Base\Component\Field\FieldUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Html\Html;
use Combodo\iTop\Application\UI\Base\Component\Input\TextArea;

abstract class Query extends cmdbAbstractObject
{
	/**
	 * @throws \CoreException
	 * @since 3.0.0 N°3227 add is_template field for predefined queries
	 */
	public static function Init()
	{
		$aParams = array
		(
			"category" => "core/cmdb,view_in_gui,application,grant_by_profile",
			"key_type" => "autoincrement",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_query",
			"db_key_field" => "id",
			"db_finalclass_field" => "realclass",
		);
		MetaModel::Init_Params($aParams);

		MetaModel::Init_AddAttribute(new AttributeString("name", array(
			"allowed_values" => null,
			"sql" => "name",
			"default_value" => null,
			"is_null_allowed" => false,
			"depends_on" => array(),
		)));
		MetaModel::Init_AddAttribute(new AttributeText("description", array(
			"allowed_values" => null,
			"sql" => "description",
			"default_value" => null,
			"is_null_allowed" => false,
			"depends_on" => array(),
		)));

		MetaModel::Init_AddAttribute(new AttributeEnum("is_template", array(
			'allowed_values' => new ValueSetEnum('yes,no'),
			'sql' => 'is_template',
			'default_value' => 'no',
			'is_null_allowed' => false,
			'depends_on' => [],
			'display_style' => 'radio_horizontal',
		)));

		// Display lists
		MetaModel::Init_SetZListItems('details',
			array('name', 'is_template', 'description')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('description')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('name', 'description', 'is_template')); // Criteria of the std search form
		MetaModel::Init_SetZListItems('default_search',
			array('name', 'description', 'is_template')); // Criteria of the default search form
		// MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
	}
}

class QueryOQL extends Query
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "core/cmdb,view_in_gui,application,grant_by_profile",
			"key_type" => "autoincrement",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array('oql', 'is_template'),
			"db_table" => "priv_query_oql",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeOQL("oql", array(
			"allowed_values" => null,
			"sql" => "oql",
			"default_value" => null,
			"is_null_allowed" => false,
			"depends_on" => array(),
		)));
		MetaModel::Init_AddAttribute(new AttributeText("fields", array(
			"allowed_values" => null,
			"sql" => "fields",
			"default_value" => null,
			"is_null_allowed" => true,
			"depends_on" => array(),
		)));
		// Rolled back to AttributeText until AttributeQueryAttCodeSet can manage fields order correctly
		//MetaModel::Init_AddAttribute(new AttributeQueryAttCodeSet("fields", array("allowed_values"=>null,"max_items" => 1000, "query_field" => "oql", "sql"=>"fields", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array('oql'))));

		// Display lists
		MetaModel::Init_SetZListItems('details',
			array('name', 'is_template', 'description', 'oql', 'fields')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('description')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search',
			array('name', 'description', 'is_template', 'fields', 'oql')); // Criteria of the std search form
	}

	function DisplayBareProperties(WebPage $oPage, $bEditMode = false, $sPrefix = '', $aExtraParams = array())
	{
		$aFieldsMap = parent::DisplayBareProperties($oPage, $bEditMode, $sPrefix, $aExtraParams);
		$oPage->add_script("$('[name=\"attr_oql\"]').addClass('ibo-query-oql ibo-is-code'); $('[data-attribute-code=\"oql\"]').addClass('ibo-query-oql ibo-is-code');");

		if (!$bEditMode) {
			$sFields = trim($this->Get('fields'));
			$bExportV1Recommended = ($sFields == '');
			if ($bExportV1Recommended) {
				$oFieldAttDef = MetaModel::GetAttributeDef('QueryOQL', 'fields');
				$oAlert = AlertUIBlockFactory::MakeForFailure()
					->SetIsClosable(false)
					->SetIsCollapsible(false);
				$oAlert->AddCSSClass('mb-5');
				$oAlert->AddSubBlock(new Html(Dict::Format('UI:Query:UrlV1', '')));
				$oPage->AddSubBlock($oAlert);
				$sUrl = utils::GetAbsoluteUrlAppRoot().'webservices/export.php?format=spreadsheet&login_mode=basic&query='.$this->GetKey();
			} else {
				$sUrl = utils::GetAbsoluteUrlAppRoot().'webservices/export-v2.php?format=spreadsheet&login_mode=basic&date_format='.urlencode((string)AttributeDateTime::GetFormat()).'&query='.$this->GetKey();
			}
			$sOql = $this->Get('oql');
			$sMessage = null;
			try {
				$oSearch = DBObjectSearch::FromOQL($sOql);
				$aParameters = $oSearch->GetQueryParams();
				foreach ($aParameters as $sParam => $val) {
					$sUrl .= '&arg_'.$sParam.'=["'.$sParam.'"]';
				}

				$oTextArea = new TextArea("", $sUrl, null, 80, 3);
				$oFieldUrl = FieldUIBlockFactory::MakeFromObject(Dict::S('UI:Query:UrlForExcel'), $oTextArea, Field::ENUM_FIELD_LAYOUT_LARGE);
				$oPage->AddSubBlock($oFieldUrl);

				if (count($aParameters) == 0) {
					$oBlock = new DisplayBlock($oSearch, 'list');
					$aExtraParams = array(
						//'menu' => $sShowMenu,
						'table_id' => 'query_preview_'.$this->getKey(),
					);
					$sBlockId = 'block_query_preview_'.$this->GetKey(); // make a unique id (edition occuring in the same DOM)
					$oBlock->Display($oPage, $sBlockId, $aExtraParams);
				}
			}
			catch
			(OQLException $e) {
				$oAlert = AlertUIBlockFactory::MakeForFailure(Dict::Format('UI:RunQuery:Error'), $e->getHtmlDesc())
					->SetIsClosable(false)
					->SetIsCollapsible(false);
				$oAlert->AddCSSClass('mb-5');
				$oPage->AddSubBlock($oAlert);
			}
		}
		return $aFieldsMap;
	}

// Rolled back until 'fields' can be properly managed by AttributeQueryAttCodeSet
//
//	public function ComputeValues()
//	{
//		parent::ComputeValues();
//
//		// Remove unwanted attribute codes
//		$aChanges = $this->ListChanges();
//		if (isset($aChanges['fields']))
//		{
//			$oAttDef = MetaModel::GetAttributeDef(get_class($this), 'fields');
//			$aArgs = array('this' => $this);
//			$aAllowedValues = $oAttDef->GetAllowedValues($aArgs);
//
//			/** @var \ormSet $oValue */
//			$oValue = $this->Get('fields');
//			$aValues = $oValue->GetValues();
//			$bChanged = false;
//			foreach($aValues as $key => $sValue)
//			{
//				if (!isset($aAllowedValues[$sValue]))
//				{
//					unset($aValues[$key]);
//					$bChanged = true;
//				}
//			}
//			if ($bChanged)
//			{
//				$oValue->SetValues($aValues);
//				$this->Set('fields', $oValue);
//			}
//		}
//	}

}

?>

<?php

/*
 * Copyright (C) 2010-2024 Combodo SAS
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
use Combodo\iTop\Application\UI\Base\Component\FieldSet\FieldSetUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Html\Html;
use Combodo\iTop\Application\UI\Base\Component\Input\TextArea;
use Combodo\iTop\Application\WebPage\WebPage;

abstract class Query extends cmdbAbstractObject
{
	/**
	 * @throws \CoreException
	 * @since 3.0.0 N°3227 add is_template field for predefined queries
	 */
	public static function Init()
	{
		$aParams =
		[
			"category" => "core/cmdb,view_in_gui,application,grant_by_profile",
			"key_type" => "autoincrement",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => [],
			"db_table" => "priv_query",
			"db_key_field" => "id",
			"db_finalclass_field" => "realclass",
		];
		MetaModel::Init_Params($aParams);

		MetaModel::Init_AddAttribute(new AttributeString("name", [
			"allowed_values" => null,
			"sql" => "name",
			"default_value" => null,
			"is_null_allowed" => false,
			"depends_on" => [],
		]));

		MetaModel::Init_AddAttribute(new AttributeText("description", [
			"allowed_values" => null,
			"sql" => "description",
			"default_value" => null,
			"is_null_allowed" => false,
			"depends_on" => [],
		]));

		MetaModel::Init_AddAttribute(new AttributeEnum("is_template", [
			'allowed_values' => new ValueSetEnum('yes,no'),
			'sql' => 'is_template',
			'default_value' => 'no',
			'is_null_allowed' => false,
			'depends_on' => [],
			'display_style' => 'radio_horizontal',
		]));

		MetaModel::Init_AddAttribute(new AttributeInteger("export_count", [
			"allowed_values" => null,
			"sql" => "export_count",
			"default_value" => 0,
			"is_null_allowed" => false,
			"depends_on" => [],
			"tracking_level" => ATTRIBUTE_TRACKING_NONE,
		]));

		MetaModel::Init_AddAttribute(new AttributeDateTime("export_last_date", [
			"allowed_values" => null,
			"sql" => "export_last_date",
			"default_value" => null,
			"is_null_allowed" => true,
			"depends_on" => [],
			"tracking_level" => ATTRIBUTE_TRACKING_NONE,
		]));

		MetaModel::Init_AddAttribute(new AttributeExternalKey(
			"export_last_user_id",
			[
				"targetclass" => 'User',
				"allowed_values" => null,
				"sql" => 'user_id',
				"is_null_allowed" => true,
				"depends_on" => [],
				"display_style" => 'select',
				"always_load_in_tables" => false,
				"on_target_delete" => DEL_SILENT,
				"tracking_level" => ATTRIBUTE_TRACKING_NONE,
			]
		));

		MetaModel::Init_AddAttribute(new AttributeExternalField(
			"export_last_user_contact",
			[
				"allowed_values" => null,
				"extkey_attcode" => "export_last_user_id",
				"target_attcode" => "contactid",
				"tracking_level" => ATTRIBUTE_TRACKING_NONE,
			]
		));

		// Display lists
		MetaModel::Init_SetZListItems(
			'details',
			['name', 'is_template', 'description']
		); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', ['description']); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', ['name', 'description', 'is_template']); // Criteria of the std search form
		MetaModel::Init_SetZListItems(
			'default_search',
			['name', 'description', 'is_template']
		); // Criteria of the default search form
		// MetaModel::Init_SetZListItems('advanced_search', array('name')); // Criteria of the advanced search form
	}

	/**
	 * @inheritdoc
	 *
	 * @since 3.1.0
	 */
	public function GetAttributeFlags($sAttCode, &$aReasons = [], $sTargetState = '')
	{
		// read only attribute
		if (in_array($sAttCode, ['export_count', 'export_last_date', 'export_last_user_id'])) {
			return OPT_ATT_READONLY;
		}

		return parent::GetAttributeFlags($sAttCode, $aReasons, $sTargetState);
	}

	/**
	 * Return export url.
	 *
	 * @param array|null $aValues optional values for the query
	 *
	 * @return string|null
	 * @since 3.1.0
	 */
	abstract public function GetExportUrl(?array $aValues = null): ?string;

	/**
	 * Update last export information.
	 *
	 * @return void
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 * @since 3.1.0
	 */
	public function UpdateLastExportInformation(): void
	{
		// last export information
		$this->Set('export_last_date', date(AttributeDateTime::GetSQLFormat()));
		$this->Set('export_last_user_id', UserRights::GetUserObject());
		$this->AllowWrite(true);
		$this->DBUpdate();

		// increment usage counter
		$this->DBIncrement('export_count');
	}
}

class QueryOQL extends Query
{
	public static function Init()
	{
		$aParams =
		[
			"category" => "core/cmdb,view_in_gui,application,grant_by_profile",
			"key_type" => "autoincrement",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => ['oql', 'is_template'],
			"db_table" => "priv_query_oql",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
		];
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeOQL("oql", [
			"allowed_values" => null,
			"sql" => "oql",
			"default_value" => null,
			"is_null_allowed" => false,
			"depends_on" => [],
		]));
		MetaModel::Init_AddAttribute(new AttributeText("fields", [
			"allowed_values" => null,
			"sql" => "fields",
			"default_value" => null,
			"is_null_allowed" => true,
			"depends_on" => [],
		]));
		// Rolled back to AttributeText until AttributeQueryAttCodeSet can manage fields order correctly
		//MetaModel::Init_AddAttribute(new AttributeQueryAttCodeSet("fields", array("allowed_values"=>null,"max_items" => 1000, "query_field" => "oql", "sql"=>"fields", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array('oql'))));

		// Display lists
		MetaModel::Init_SetZListItems(
			'details',
			[
				'col:col1' => ['fieldset:Query:baseinfo' => ['name', 'is_template', 'description', 'oql', 'fields']],
				'col:col2' => ['fieldset:Query:exportInfo' => ['export_count', 'export_last_date', 'export_last_user_id', 'export_last_user_contact']],
			]
		); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', ['description']); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems(
			'standard_search',
			['name', 'description', 'is_template', 'fields', 'oql']
		); // Criteria of the std search form
	}

	/** @inheritdoc */
	public function GetExportUrl(?array $aValues = null): ?string
	{
		try {
			// retrieve attributes
			$sOql = $this->Get('oql');

			// construct base url depending on version
			$sUrl = utils::GetAbsoluteUrlAppRoot().'webservices/export-v2.php?format=spreadsheet&login_mode=basic&date_format='.urlencode((string)AttributeDateTime::GetFormat()).'&query='.$this->GetKey();

			// search object from OQL
			$oSearch = DBObjectSearch::FromOQL($sOql);

			// inject parameters
			$aParameters = $oSearch->GetQueryParams();
			foreach ($aParameters as $sParam => $val) {
				$paramValue = ($aValues === null || $aValues[$sParam] === null) ? $sParam : $aValues[$sParam];
				$sUrl .= '&arg_'.$sParam.'='.$paramValue;
			}

			return $sUrl;
		} catch (Exception $e) {
			return null;
		}
	}

	public function DisplayBareProperties(WebPage $oPage, $bEditMode = false, $sPrefix = '', $aExtraParams = [])
	{
		$aFieldsMap = parent::DisplayBareProperties($oPage, $bEditMode, $sPrefix, $aExtraParams);
		$oPage->add_script("$('[name=\"attr_oql\"]').addClass('ibo-query-oql ibo-is-code'); $('[data-attribute-code=\"oql\"]').addClass('ibo-query-oql ibo-is-code');");

		if (!$bEditMode) {
			$sUrl = utils::GetAbsoluteUrlAppRoot().'webservices/export-v2.php?format=spreadsheet&login_mode=basic&date_format='.urlencode((string)AttributeDateTime::GetFormat()).'&query='.$this->GetKey();

			$sOql = $this->Get('oql');
			try {
				$oSearch = DBObjectSearch::FromOQL($sOql);
				$aParameters = $oSearch->GetQueryParams();
				foreach ($aParameters as $sParam => $val) {
					$sUrl .= '&arg_'.$sParam.'=["'.$sParam.'"]';
				}

				// add text area inside field set
				$oFieldSet = FieldSetUIBlockFactory::MakeStandard(Dict::S('UI:Query:UrlForExcel'));
				$oTextArea = new TextArea("", $sUrl, null, 80, 3);
				$oFieldSet->AddSubBlock($oTextArea);
				$oPage->AddSubBlock($oFieldSet);

				if (count($aParameters) == 0) {
					$oBlock = new DisplayBlock($oSearch, 'list');
					$aExtraParams = [
						//'menu' => $sShowMenu,
						'table_id' => 'query_preview_'.$this->getKey(),
					];
					$sBlockId = 'block_query_preview_'.$this->GetKey(); // make a unique id (edition occuring in the same DOM)
					$oBlock->Display($oPage, $sBlockId, $aExtraParams);
				}
			} catch (OQLException $e) {
				$oAlert = AlertUIBlockFactory::MakeForFailure(Dict::S('UI:RunQuery:Error'), $e->getHtmlDesc())
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

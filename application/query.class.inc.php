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

		MetaModel::Init_AddAttribute(new AttributeInteger("export_count", array(
			"allowed_values" => null,
			"sql" => "export_count",
			"default_value" => 0,
			"is_null_allowed" => false,
			"depends_on" => array(),
			"tracking_level" => ATTRIBUTE_TRACKING_NONE,
		)));

		MetaModel::Init_AddAttribute(new AttributeDateTime("export_last_date", array(
			"allowed_values" => null,
			"sql" => "export_last_date",
			"default_value" => null,
			"is_null_allowed" => true,
			"depends_on" => array(),
			"tracking_level" => ATTRIBUTE_TRACKING_NONE,
		)));

		MetaModel::Init_AddAttribute(new AttributeExternalKey("export_last_user_id",
			array(
				"targetclass"=>'User',
				"allowed_values"=>null,
				"sql"=>'user_id',
				"is_null_allowed"=>true,
				"depends_on"=>array(),
				"display_style"=>'select',
				"always_load_in_tables"=>false,
				"on_target_delete"=>DEL_SILENT,
				"tracking_level" => ATTRIBUTE_TRACKING_NONE,
			)));

		MetaModel::Init_AddAttribute(new AttributeExternalField("export_last_user_contact",
			array(
				"allowed_values"=>null,
				"extkey_attcode"=> "export_last_user_id",
				"target_attcode"=>"contactid",
				"tracking_level" => ATTRIBUTE_TRACKING_NONE,
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


	/**
	 * @inheritdoc
	 *
	 * @since 3.1.0
	 */
	public function GetAttributeFlags($sAttCode, &$aReasons = array(), $sTargetState = '')
	{
		// read only attribute
		if (in_array($sAttCode, ['export_count', 'export_last_date', 'export_last_user_id'])){
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
	abstract public function GetExportUrl(array $aValues = null) : ?string;

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
	public function UpdateLastExportInformation() : void
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
			array(
				'col:col1' => array('fieldset:Query:baseinfo' => array('name', 'is_template', 'description', 'oql', 'fields')),
				'col:col2' => array('fieldset:Query:exportInfo' => array('export_count', 'export_last_date', 'export_last_user_id', 'export_last_user_contact'))
			)
		); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('description')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search',
			array('name', 'description', 'is_template', 'fields', 'oql')); // Criteria of the std search form
	}

	/** @inheritdoc */
	public function GetExportUrl(array $aValues = null) : ?string
	{
		try{
			// retrieve attributes
			$sFields = trim($this->Get('fields'));
			$sOql = $this->Get('oql');

			// construct base url depending on version
			$bExportV1Recommended = ($sFields == '');
			if ($bExportV1Recommended) {
				$sUrl = utils::GetAbsoluteUrlAppRoot().'webservices/export.php?format=spreadsheet&login_mode=basic&query='.$this->GetKey();
			}
			else{
				$sUrl = utils::GetAbsoluteUrlAppRoot().'webservices/export-v2.php?format=spreadsheet&login_mode=basic&date_format='.urlencode((string)AttributeDateTime::GetFormat()).'&query='.$this->GetKey();
			}

			// search object from OQL
			$oSearch = DBObjectSearch::FromOQL($sOql);

			// inject parameters
			$aParameters = $oSearch->GetQueryParams();
			foreach ($aParameters as $sParam => $val) {
				$paramValue = ($aValues === null || $aValues[$sParam] === null) ? $sParam : $aValues[$sParam];
				$sUrl .= '&arg_' . $sParam . '=' . $paramValue;
			}

			return $sUrl;
		}
		catch(Exception $e){
			return null;
		}
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

				// add text area inside field set
				$oFieldSet = FieldSetUIBlockFactory::MakeStandard(Dict::S('UI:Query:UrlForExcel'));
				$oTextArea = new TextArea("", $sUrl, null, 80, 3);
				$oFieldSet->AddSubBlock($oTextArea);
				$oPage->AddSubBlock($oFieldSet);

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

?>

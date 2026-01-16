<?php

// Copyright (C) 2012-2024 Combodo SAS
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

namespace Combodo\iTop\Application\Dashlet\Core;

use Combodo\iTop\Application\Dashlet\Dashlet;
use Combodo\iTop\Application\UI\Base\Component\Dashlet\DashletContainer;
use Combodo\iTop\Application\UI\Base\Component\Html\Html;
use DBObjectSearch;
use DesignerComboField;
use DesignerForm;
use DesignerFormSelectorField;
use DesignerHiddenField;
use DesignerIntegerField;
use DesignerLongTextField;
use DesignerTextField;
use Dict;
use DisplayBlock;
use Exception;
use MetaModel;
use utils;

abstract class DashletGroupBy extends Dashlet
{
	public function __construct($oModelReflection, $sId, string $sDashletType = null)
	{
		parent::__construct($oModelReflection, $sId, $sDashletType);
		$this->aProperties['title'] = '';
		$this->aProperties['query'] = 'SELECT Contact';
		$this->aProperties['group_by'] = 'status';
		$this->aProperties['style'] = 'table';
		$this->aProperties['aggregation_function'] = 'count';
		$this->aProperties['aggregation_attribute'] = '';
		$this->aProperties['limit'] = '';
		$this->aProperties['order_by'] = '';
		$this->aProperties['order_direction'] = '';
	}

	protected $sGroupByLabel = null;
	protected $sGroupByExpr = null;
	protected $sGroupByAttCode = null;
	protected $sFunction = null;
	protected $sAggregationFunction = null;
	protected $sAggregationAttribute = null;
	protected $sLimit = null;
	protected $sOrderBy = null;
	protected $sOrderDirection = null;
	protected $sClass = null;

	/**
	 * Compute Grouping
	 *
	 * @inheritdoc
	 */
	public function OnUpdate()
	{
		$this->sGroupByExpr = null;
		$this->sGroupByLabel = null;
		$this->sGroupByAttCode = null;
		$this->sFunction = null;
		$this->sClass = null;

		$sQuery = $this->aProperties['query'];
		$sGroupBy = $this->aProperties['group_by'];

		$this->sAggregationFunction = $this->aProperties['aggregation_function'];
		$this->sAggregationAttribute = $this->aProperties['aggregation_attribute'] ?? '';

		$this->sLimit = $this->aProperties['limit'] ?? 0;
		$this->sOrderBy = $this->aProperties['order_by'] ?? null;
		if (empty($this->sOrderBy)) {
			if ($this->aProperties['style'] == 'pie') {
				$this->sOrderBy = 'function';
			} else {
				$this->sOrderBy = 'attribute';
			}
		}

		// First perform the query - if the OQL is not ok, it will generate an exception : no need to go further
		try {
			$oQuery = $this->oModelReflection->GetQuery($sQuery);
			$this->sClass = $oQuery->GetClass();
			$sClassAlias = $oQuery->GetClassAlias();
		} catch (Exception $e) {
			// Invalid query, let the user edit the dashlet/dashboard anyhow
			$this->sClass = null;
			$sClassAlias = '';
		}

		// Check groupby... it can be wrong at this stage
		if (preg_match('/^(.*):(.*)$/', $sGroupBy, $aMatches)) {
			$this->sGroupByAttCode = $aMatches[1];
			$this->sFunction = $aMatches[2];
		} else {
			$this->sGroupByAttCode = $sGroupBy;
			$this->sFunction = null;
		}

		if ((!is_null($this->sClass)) && empty($this->aProperties['order_direction'])) {
			$aAttributeTypes = $this->oModelReflection->ListAttributes($this->sClass);
			if (isset($aAttributeTypes[$this->sGroupByAttCode])) {
				$sAttributeType = $aAttributeTypes[$this->sGroupByAttCode];
				if (is_subclass_of($sAttributeType, 'AttributeDateTime') || $sAttributeType == 'AttributeDateTime') {
					$this->sOrderDirection = 'asc';
				} else {
					$this->sOrderDirection = 'desc';
				}
			}
		} else {
			$this->sOrderDirection = $this->aProperties['order_direction'];
		}

		if ((!is_null($this->sClass)) && $this->oModelReflection->IsValidAttCode($this->sClass, $this->sGroupByAttCode)) {
			$sAttLabel = $this->oModelReflection->GetLabel($this->sClass, $this->sGroupByAttCode);
			if (!is_null($this->sFunction)) {
				switch ($this->sFunction) {
					case 'hour':
						$this->sGroupByLabel = Dict::Format('UI:DashletGroupBy:Prop-GroupBy:Hour', $sAttLabel);
						$this->sGroupByExpr = "DATE_FORMAT($sClassAlias.{$this->sGroupByAttCode}, '%H')"; // 0 -> 23
						break;

					case 'month':
						$this->sGroupByLabel = Dict::Format('UI:DashletGroupBy:Prop-GroupBy:Month', $sAttLabel);
						$this->sGroupByExpr = "DATE_FORMAT($sClassAlias.{$this->sGroupByAttCode}, '%Y-%m')"; // yyyy-mm
						break;

					case 'day_of_week':
						$this->sGroupByLabel = Dict::Format('UI:DashletGroupBy:Prop-GroupBy:DayOfWeek', $sAttLabel);
						$this->sGroupByExpr = "DATE_FORMAT($sClassAlias.{$this->sGroupByAttCode}, '%w')";
						break;

					case 'day_of_month':
						$this->sGroupByLabel = Dict::Format('UI:DashletGroupBy:Prop-GroupBy:DayOfMonth', $sAttLabel);
						$this->sGroupByExpr = "DATE_FORMAT($sClassAlias.{$this->sGroupByAttCode}, '%Y-%m-%d')"; // mm-dd
						break;

					default:
						$this->sGroupByLabel = 'Unknown group by function '.$this->sFunction;
						$this->sGroupByExpr = $sClassAlias.'.'.$this->sGroupByAttCode;
				}
			} else {
				$this->sGroupByExpr = $sClassAlias.'.'.$this->sGroupByAttCode;
				$this->sGroupByLabel = $sAttLabel;
			}
		} else {
			$this->sGroupByAttCode = null;
		}
	}

	/**
	 * @inheritdoc
	 *
	 * @throws \CoreException
	 * @throws \ArchivedObjectException
	 */
	public function Render($oPage, $bEditMode = false, $aExtraParams = [])
	{
		$sTitle = $this->aProperties['title'];
		$sQuery = $this->aProperties['query'];
		$sStyle = $this->aProperties['style'];

		// First perform the query - if the OQL is not ok, it will generate an exception : no need to go further
		if (isset($aExtraParams['query_params'])) {
			$aQueryParams = $aExtraParams['query_params'];
		} elseif (isset($aExtraParams['this->class']) && isset($aExtraParams['this->id'])) {
			$oObj = MetaModel::GetObject($aExtraParams['this->class'], $aExtraParams['this->id']);
			$aQueryParams = $oObj->ToArgsForQuery();
		} else {
			$aQueryParams = [];
		}
		$oFilter = DBObjectSearch::FromOQL($sQuery, $aQueryParams);
		$oFilter->SetShowObsoleteData(utils::ShowObsoleteData());

		$sClass = $oFilter->GetClass();
		if (!$this->oModelReflection->IsValidAttCode($sClass, $this->sGroupByAttCode)) {
			return new Html('<p>'.Dict::S('UI:DashletGroupBy:MissingGroupBy').'</p>');
		}

		switch ($sStyle) {
			case 'bars':
				$sType = 'chart';
				$aParams = [
					'chart_type'            => 'bars',
					'chart_title'           => $sTitle,
					'group_by'              => $this->sGroupByExpr,
					'group_by_label'        => $this->sGroupByLabel,
					'aggregation_function'  => $this->sAggregationFunction,
					'aggregation_attribute' => $this->sAggregationAttribute,
					'limit'                 => $this->sLimit,
					'order_direction'       => $this->sOrderDirection,
					'order_by'              => $this->sOrderBy,
				];
				$sHtmlTitle = ''; // done in the itop block
				break;

			case 'pie':
				$sType = 'chart';
				$aParams = [
					'chart_type'            => 'pie',
					'chart_title'           => $sTitle,
					'group_by'              => $this->sGroupByExpr,
					'group_by_label'        => $this->sGroupByLabel,
					'aggregation_function'  => $this->sAggregationFunction,
					'aggregation_attribute' => $this->sAggregationAttribute,
					'limit'                 => $this->sLimit,
					'order_direction'       => $this->sOrderDirection,
					'order_by'              => $this->sOrderBy,
				];
				$sHtmlTitle = ''; // done in the itop block
				break;

			case 'table':
			default:
				$sHtmlTitle = utils::HtmlEntities(Dict::S($sTitle)); // done in the itop block
				$sType = 'count';
				$aParams = [
					'group_by'              => $this->sGroupByExpr,
					'group_by_label'        => $this->sGroupByLabel,
					'aggregation_function'  => $this->sAggregationFunction,
					'aggregation_attribute' => $this->sAggregationAttribute,
					'limit'                 => $this->sLimit,
					'order_direction'       => $this->sOrderDirection,
					'order_by'              => $this->sOrderBy,
				];
				break;
		}

		//$oPanel = \Combodo\iTop\Application\UI\Base\Layout\UIContentBlockUIBlockFactory::MakeStandard();
		//PanelUIBlockFactory::MakeForClass($sClass, Dict::S($sTitle));

		$sBlockId = 'block_'.$this->sId.($bEditMode ? '_edit' : ''); // make a unique id (edition occurring in the same DOM)
		$oBlock = new DisplayBlock($oFilter, $sType);
		//$oBlock->DisplayIntoContentBlock($oPanel, $oPage, $sBlockId, array_merge($aExtraParams, $aParams));
		$aExtraParams["surround_with_panel"] = true;
		$aExtraParams["panel_title"] = Dict::S($sTitle);
		$aExtraParams["panel_class"] = $sClass;
		$oPanel = $oBlock->GetDisplay($oPage, $sBlockId, array_merge($aExtraParams, $aParams));
		if ($bEditMode) {
			$oPanel->AddHtml('<div class="ibo-dashlet-blocker dashlet-blocker"></div>');
		}

		return $oPanel;
	}

	/**
	 * @return array
	 */
	protected function MakeSimulatedData()
	{
		$sQuery = $this->aProperties['query'];

		$oQuery = $this->oModelReflection->GetQuery($sQuery);
		$sClass = $oQuery->GetClass();

		$aDisplayValues = [];
		if ($this->oModelReflection->IsValidAttCode($sClass, $this->sGroupByAttCode)) {
			$aAttributeTypes = $this->oModelReflection->ListAttributes($sClass);
			$sAttributeType = $aAttributeTypes[$this->sGroupByAttCode];
			if (is_subclass_of($sAttributeType, 'AttributeDateTime') || $sAttributeType == 'AttributeDateTime') {
				// Note: an alternative to this somewhat hardcoded way of doing things would be to implement...
				//$oExpr = Expression::FromOQL($this->sGroupByExpr);
				//$aTranslationData = array($oQuery->GetClassAlias() => array($this->sGroupByAttCode => new ScalarExpression(date('Y-m-d H:i:s', $iTime))));
				//$sRawValue = CMDBSource::QueryToScalar('SELECT '.$oExpr->Translate($aTranslationData)->Render());
				//$sValueLabel = $oExpr->MakeValueLabel(oFilter, $sRawValue, $sRawValue);
				// Anyhow, this requires :
				// - an update to the prototype of MakeValueLabel() so that it takes ModelReflection parameters
				// - propose clever date/times samples

				$aValues = [];
				switch ($this->sFunction) {
					case 'hour':
						$aValues = [8, 9, 15, 18];
						break;

					case 'month':
						$aValues = ['2013 '.Dict::S('Month-11'), '2013 '.Dict::S('Month-12'), '2014 '.Dict::S('Month-01'), '2014 '.Dict::S('Month-02'), '2014 '.Dict::S('Month-03')];
						break;

					case 'day_of_week':
						$aValues = [Dict::S('DayOfWeek-Monday'), Dict::S('DayOfWeek-Wednesday'), Dict::S('DayOfWeek-Thursday'), Dict::S('DayOfWeek-Friday')];
						break;

					case 'day_of_month':
						$aValues = [Dict::S('Month-03').' 30', Dict::S('Month-03').' 31', Dict::S('Month-04').' 01', Dict::S('Month-04').' 02', Dict::S('Month-04').' 03'];
						break;
				}
				foreach ($aValues as $sValue) {
					$aDisplayValues[] = ['label' => $sValue, 'value' => (int)rand(1, 15)];
				}
			} elseif (is_subclass_of($sAttributeType, 'AttributeEnum') || $sAttributeType == 'AttributeEnum') {
				$aAllowed = $this->oModelReflection->GetAllowedValues_att($sClass, $this->sGroupByAttCode);
				if ($aAllowed) { // null for non enums
					foreach ($aAllowed as $sValue => $sValueLabel) {
						$iCount = (int)rand(2, 100);
						$aDisplayValues[] = [
							'label' => $sValueLabel,
							'value' => $iCount,
						];
					}
				}
			} else {
				$aDisplayValues[] = ['label' => 'a', 'value' => 123];
				$aDisplayValues[] = ['label' => 'b', 'value' => 321];
				$aDisplayValues[] = ['label' => 'c', 'value' => 456];
			}
		}

		return $aDisplayValues;
	}

	/**
	 * @inheritdoc
	 */
	public function RenderNoData($oPage, $bEditMode = false, $aExtraParams = [])
	{
		$oDashletContainer = new DashletContainer(null, ['dashlet-content']);
		$oDashletContainer->AddHtml('error!');

		return $oDashletContainer;
	}

	/**
	 * @inheritdoc
	 */
	public function GetPropertiesFields(DesignerForm $oForm)
	{
		$oField = new DesignerTextField('title', Dict::S('UI:DashletGroupBy:Prop-Title'), $this->aProperties['title']);
		$oForm->AddField($oField);

		$oField = new DesignerLongTextField('query', Dict::S('UI:DashletGroupBy:Prop-Query'), $this->aProperties['query']);
		$oField->SetMandatory();
		$oField->AddCSSClass("ibo-query-oql");
		$oField->AddCSSClass("ibo-is-code");
		$oForm->AddField($oField);

		try {
			// Group by field: build the list of possible values (attribute codes + ...)
			$aGroupBy = $this->GetGroupByOptions($this->aProperties['query']);

			$oField = new DesignerComboField('group_by', Dict::S('UI:DashletGroupBy:Prop-GroupBy'), $this->aProperties['group_by']);
			$oField->SetMandatory();
			$oField->SetAllowedValues($aGroupBy);
		} catch (Exception $e) {
			$oField = new DesignerTextField('group_by', Dict::S('UI:DashletGroupBy:Prop-GroupBy'), $this->aProperties['group_by']);
			$oField->SetReadOnly();
			$aGroupBy = [];
		}
		$oForm->AddField($oField);

		$aStyles = [
			'pie'   => Dict::S('UI:DashletGroupByPie:Label'),
			'bars'  => Dict::S('UI:DashletGroupByBars:Label'),
			'table' => Dict::S('UI:DashletGroupByTable:Label'),
		];

		$oField = new DesignerComboField('style', Dict::S('UI:DashletGroupBy:Prop-Style'), $this->aProperties['style']);
		$oField->SetMandatory();
		$oField->SetAllowedValues($aStyles);
		$oForm->AddField($oField);

		$aFunctionAttributes = $this->GetNumericAttributes($this->aProperties['query']);
		$aFunctions = $this->GetAllowedFunctions($aFunctionAttributes);
		$oSelectorField = new DesignerFormSelectorField('aggregation_function', Dict::S('UI:DashletGroupBy:Prop-Function'), $this->aProperties['aggregation_function']);
		$oForm->AddField($oSelectorField);
		$oSelectorField->SetMandatory();
		// Count sub-menu
		$oSubForm = new DesignerForm();
		$oSelectorField->AddSubForm($oSubForm, Dict::S('UI:GroupBy:count'), 'count');
		foreach ($aFunctions as $sFct => $sLabel) {
			$oSubForm = new DesignerForm();
			$oField = new DesignerComboField('aggregation_attribute', Dict::S('UI:DashletGroupBy:Prop-FunctionAttribute'), $this->aProperties['aggregation_attribute']);
			$oField->SetMandatory();
			$oField->SetAllowedValues($aFunctionAttributes);
			$oSubForm->AddField($oField);
			$oSelectorField->AddSubForm($oSubForm, $sLabel, $sFct);
		}

		$aOrderField = [];

		if (isset($this->aProperties['group_by']) && isset($aGroupBy[$this->aProperties['group_by']])) {
			$aOrderField['attribute'] = $aGroupBy[$this->aProperties['group_by']];
		}

		if ($this->aProperties['aggregation_function'] == 'count') {
			$aOrderField['function'] = Dict::S('UI:GroupBy:count');
		} else {
			$aOrderField['function'] = $aFunctions[$this->aProperties['aggregation_function']];
		}
		$oSelectorField = new DesignerFormSelectorField('order_by', Dict::S('UI:DashletGroupBy:Prop-OrderField'), $this->aProperties['order_by']);
		$oForm->AddField($oSelectorField);
		$oSelectorField->SetMandatory();
		foreach ($aOrderField as $sField => $sLabel) {
			$oSubForm = new DesignerForm();
			if ($sField == 'function') {
				$oField = new DesignerIntegerField('limit', Dict::S('UI:DashletGroupBy:Prop-Limit'), $this->aProperties['limit']);
				$oSubForm->AddField($oField);
			}
			$oSelectorField->AddSubForm($oSubForm, $sLabel, $sField);
		}

		$aOrderDirections = [
			'asc'  => Dict::S('UI:DashletGroupBy:Order:asc'),
			'desc' => Dict::S('UI:DashletGroupBy:Order:desc'),
		];
		$sOrderDirection = empty($this->aProperties['order_direction']) ? $this->sOrderDirection : $this->aProperties['order_direction'];
		$oField = new DesignerComboField('order_direction', Dict::S('UI:DashletGroupBy:Prop-OrderDirection'), $sOrderDirection);
		$oField->SetMandatory();
		$oField->SetAllowedValues($aOrderDirections);
		$oForm->AddField($oField);

	}

	/**
	 * @return array
	 */
	protected function GetOrderBy()
	{
		if (is_null($this->sClass)) {
			return [];
		}

		return [
			$this->aProperties['group_by']                          => $this->oModelReflection->GetLabel($this->sClass, $this->aProperties['group_by']),
			'_itop_'.$this->aProperties['aggregation_function'].'_' => Dict::S('UI:GroupBy:'.$this->aProperties['aggregation_function']),
		];
	}

	/**
	 * @param array $aFunctionAttributes
	 *
	 * @return array
	 */
	protected function GetAllowedFunctions($aFunctionAttributes)
	{
		$aFunctions = [];

		if (!empty($aFunctionAttributes) || is_null($this->sClass)) {
			$aFunctions['sum'] = Dict::S('UI:GroupBy:sum');
			$aFunctions['avg'] = Dict::S('UI:GroupBy:avg');
			$aFunctions['min'] = Dict::S('UI:GroupBy:min');
			$aFunctions['max'] = Dict::S('UI:GroupBy:max');
		}

		return $aFunctions;
	}

	/**
	 * @param string $sOql
	 *
	 * @return array
	 */
	protected function GetNumericAttributes($sOql)
	{
		$aFunctionAttributes = [];
		try {
			$oQuery = $this->oModelReflection->GetQuery($sOql);
			$sClass = $oQuery->GetClass();
			if (is_null($sClass)) {
				return $aFunctionAttributes;
			}
			foreach ($this->oModelReflection->ListAttributes($sClass) as $sAttCode => $sAttType) {
				switch ($sAttType) {
					case 'AttributeDecimal':
					case 'AttributeDuration':
					case 'AttributeInteger':
					case 'AttributePercentage':
					case 'AttributeSubItem': // TODO: Known limitation: no unit displayed (values in sec)
						$sLabel = $this->oModelReflection->GetLabel($sClass, $sAttCode);
						$aFunctionAttributes[$sAttCode] = $sLabel;
						break;
				}
			}
		} catch (Exception $e) {
			// In case the OQL is bad
		}

		return $aFunctionAttributes;
	}

	/**
	 * @inheritdoc
	 */
	public function Update($aValues, $aUpdatedFields)
	{
		if (in_array('query', $aUpdatedFields)) {
			try {
				$sCurrQuery = $aValues['query'];
				$oCurrSearch = $this->oModelReflection->GetQuery($sCurrQuery);
				$sCurrClass = $oCurrSearch->GetClass();

				$sPrevQuery = $this->aProperties['query'];
				$oPrevSearch = $this->oModelReflection->GetQuery($sPrevQuery);
				$sPrevClass = $oPrevSearch->GetClass();

				if ($sCurrClass != $sPrevClass) {
					$this->bFormRedrawNeeded = true;
					// wrong but not necessary - unset($aUpdatedFields['group_by']);
					$this->aProperties['group_by'] = '';
				}
			} catch (Exception $e) {
				$this->bFormRedrawNeeded = true;
			}
		}
		$oDashlet = parent::Update($aValues, $aUpdatedFields);

		if (in_array('style', $aUpdatedFields)) {
			switch ($aValues['style']) {
				// Style changed, mutate to the specified type of chart
				case 'pie':
					$oDashlet = new DashletGroupByPie($this->oModelReflection, $this->sId);
					break;

				case 'bars':
					$oDashlet = new DashletGroupByBars($this->oModelReflection, $this->sId);
					break;

				case 'table':
					$oDashlet = new DashletGroupByTable($this->oModelReflection, $this->sId);
					break;
			}
			$oDashlet->FromParams($aValues);
			$oDashlet->bRedrawNeeded = true;
			$oDashlet->bFormRedrawNeeded = true;
		}
		if (in_array('aggregation_attribute', $aUpdatedFields) || in_array('order_direction', $aUpdatedFields) || in_array('order_by', $aUpdatedFields) || in_array('limit', $aUpdatedFields)) {
			$oDashlet->bRedrawNeeded = true;
		}
		if (in_array('group_by', $aUpdatedFields) || in_array('aggregation_function', $aUpdatedFields)) {
			$oDashlet->bRedrawNeeded = true;
			$oDashlet->bFormRedrawNeeded = true;
		}

		return $oDashlet;
	}

	/**
	 * @inheritdoc
	 */
	public static function CanCreateFromOQL()
	{
		return true;
	}

	/**
	 * @inheritdoc
	 */
	public function GetPropertiesFieldsFromOQL(DesignerForm $oForm, $sOQL = null)
	{
		$oField = new DesignerTextField('title', Dict::S('UI:DashletGroupBy:Prop-Title'), '');
		$oForm->AddField($oField);

		$oField = new DesignerHiddenField('query', Dict::S('UI:DashletGroupBy:Prop-Query'), $sOQL);
		$oField->SetMandatory();
		$oField->AddCSSClass("ibo-query-oql");
		$oField->AddCSSClass("ibo-is-code");
		$oForm->AddField($oField);

		if (!is_null($sOQL)) {
			$oField = new DesignerComboField('group_by', Dict::S('UI:DashletGroupBy:Prop-GroupBy'), null);
			$aGroupBy = $this->GetGroupByOptions($sOQL);
			$oField->SetAllowedValues($aGroupBy);
		} else {
			// Creating a form for reading parameters!
			$oField = new DesignerTextField('group_by', Dict::S('UI:DashletGroupBy:Prop-GroupBy'), null);
		}
		$oField->SetMandatory();

		$oForm->AddField($oField);

		$oField = new DesignerHiddenField('style', '', $this->aProperties['style']);
		$oField->SetMandatory();
		$oForm->AddField($oField);
	}
}

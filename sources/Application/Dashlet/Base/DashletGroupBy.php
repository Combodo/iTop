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

namespace Combodo\iTop\Application\Dashlet\Base;

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
	 * @inheritdoc
	 */
	public static function CanCreateFromOQL()
	{
		return true;
	}
}

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

use ApplicationContext;
use Combodo\iTop\Application\Dashlet\Dashlet;
use Combodo\iTop\Application\UI\Base\Component\Dashlet\DashletContainer;
use Combodo\iTop\Application\UI\Base\Component\Panel\PanelUIBlockFactory;
use DBObjectSearch;
use DBObjectSet;
use DesignerComboField;
use DesignerForm;
use DesignerLongTextField;
use DesignerTextField;
use Dict;
use DisplayBlock;
use Exception;
use MetaModel;
use UnknownClassOqlException;
use utils;

class DashletHeaderDynamic extends Dashlet
{
	/**
	 * @inheritdoc
	 */
	public function __construct($oModelReflection, $sId)
	{
		parent::__construct($oModelReflection, $sId);
		$this->aProperties['title'] = Dict::S('UI:DashletHeaderDynamic:Prop-Title:Default');
		// TODO 3.3 default icon
		$this->aProperties['icon'] = null;
		$this->aProperties['subtitle'] = Dict::S('UI:DashletHeaderDynamic:Prop-Subtitle:Default');
		$this->aProperties['query'] = 'SELECT Contact';
		$this->aProperties['group_by'] = 'status';
		$this->aProperties['values'] = ['active', 'inactive'];
	}

	/**
	 * @return array
	 */
	protected function GetValues()
	{
		$sQuery = $this->aProperties['query'];
		$sGroupBy = $this->aProperties['group_by'];
		$aValues = $this->aProperties['values'];

		if (empty($aValues)) {
			$aValues = [];
		}

		$oQuery = $this->oModelReflection->GetQuery($sQuery);
		$sClass = $oQuery->GetClass();

		if ($this->oModelReflection->IsValidAttCode($sClass, $sGroupBy)) {
			if (count($aValues) == 0) {
				$aAllowed = $this->oModelReflection->GetAllowedValues_att($sClass, $sGroupBy);
				if (is_array($aAllowed)) {
					$aValues = array_keys($aAllowed);
				}
			}
		}

		return $aValues;
	}

	/**
	 * @inheritdoc
	 *
	 * @throws \CoreException
	 * @throws \ArchivedObjectException
	 */
	public function Render($oPage, $bEditMode = false, $aExtraParams = [])
	{
		$sTitle = utils::HtmlEntities($this->aProperties['title']);
		$sIcon = $this->aProperties['icon'];
		$sSubtitle = utils::HtmlEntities($this->aProperties['subtitle']);
		$sQuery = $this->aProperties['query'];
		$sGroupBy = $this->aProperties['group_by'];

		$oIconSelect = $this->oModelReflection->GetIconSelectionField('icon');
		$sIconPath = '';
		if (Utils::IsNotNullOrEmptyString($sIcon)) {
			$sIconPath = $oIconSelect->MakeFileUrl($sIcon);
		}

		$aValues = $this->GetValues();
		if (count($aValues) > 0) {
			// Stats grouped by <group_by>
			$sCSV = implode(',', $aValues);
			$aParams = [
				'title[block]'        => $sTitle,
				'label[block]'        => $sSubtitle,
				'status[block]'       => $sGroupBy,
				'status_codes[block]' => $sCSV,
				'context_filter'      => 1,
			];
		} else {
			// Simple stats
			$aParams = [
				'title[block]'   => $sTitle,
				'label[block]'   => $sSubtitle,
				'context_filter' => 1,
			];
		}

		if (isset($aExtraParams['query_params'])) {
			$aQueryParams = $aExtraParams['query_params'];
		} elseif (isset($aExtraParams['this->class'])) {
			$oObj = MetaModel::GetObject($aExtraParams['this->class'], $aExtraParams['this->id']);
			$aQueryParams = $oObj->ToArgsForQuery();
		} else {
			$aQueryParams = [];
		}
		$oFilter = DBObjectSearch::FromOQL($sQuery, $aQueryParams);
		$oFilter->SetShowObsoleteData(utils::ShowObsoleteData());
		$sClass = $oFilter->GetClass();

		$oPanel = PanelUIBlockFactory::MakeNeutral(Dict::S(str_replace('_', ':', $sTitle)))
			->SetIcon($sIconPath)
			->SetColorFromClass($sClass);
		$oBlock = new DisplayBlock($oFilter, 'summary');
		$sBlockId = 'block_'.$this->sId.($bEditMode ? '_edit' : ''); // make a unique id (edition occuring in the same DOM)
		$oBlock->DisplayIntoContentBlock($oPanel, $oPage, $sBlockId, array_merge($aExtraParams, $aParams));

		$oSubTitle = $oPanel->GetSubTitleBlock();
		$oSet = new DBObjectSet($oFilter);
		$iCount = $oSet->Count();
		$oAppContext = new ApplicationContext();
		$sHyperlink = utils::GetAbsoluteUrlAppRoot().'pages/UI.php?operation=search'.$oAppContext->GetForLink(true).'&filter='.rawurlencode($oFilter->serialize());
		$oSubTitle->AddHtml('<a class="summary" href="'.$sHyperlink.'">'.Dict::Format(str_replace('_', ':', $sSubtitle), $iCount).'</a>');

		return $oPanel;
	}

	/**
	 * @inheritdoc
	 */
	public function RenderNoData($oPage, $bEditMode = false, $aExtraParams = [])
	{
		$sTitle = utils::HtmlEntities($this->aProperties['title']);
		$sIcon = $this->aProperties['icon'];
		$sSubtitle = utils::HtmlEntities($this->aProperties['subtitle']);
		$sQuery = $this->aProperties['query'];
		$sGroupBy = $this->aProperties['group_by'];

		$aValueLabels = [];
		$aValues = [];
		try {
			$oQuery = $this->oModelReflection->GetQuery($sQuery);
			$sClass = $oQuery->GetClass();
			$aValues = $this->GetValues();
			foreach ($aValues as $sValue) {
				$aValueLabels[] = $this->oModelReflection->GetValueLabel($sClass, $sGroupBy, $sValue);
			}
		} catch (UnknownClassOqlException $e) {
			$aValueLabels[] = $e->GetUserFriendlyDescription();
			$aValues[] = 1;
		}

		$oIconSelect = $this->oModelReflection->GetIconSelectionField('icon');
		$sIconPath = utils::HtmlEntities($oIconSelect->MakeFileUrl($sIcon));

		$oDashletContainer = new DashletContainer(null, ['dashlet-content']);

		$sHtml = '';
		$sHtml .= '<img src="'.$sIconPath.'">';

		$sBlockId = 'block_fake_'.$this->sId.($bEditMode ? '_edit' : ''); // make a unique id (edition occuring in the same DOM)

		$iTotal = 0;

		$sHtml .= '<div class="display_block" id="'.$sBlockId.'">';
		$sHtml .= '<div class="summary-details">';
		$sHtml .= '<table><tbody>';
		$sHtml .= '<tr>';
		foreach ($aValueLabels as $sValueLabel) {
			$sHtml .= '	<th>'.$sValueLabel.'</th>';
		}
		$sHtml .= '</tr>';
		$sHtml .= '<tr>';
		foreach ($aValues as $sValue) {
			$iCount = rand(2, 100);
			$iTotal += $iCount;
			$sHtml .= '	<td>'.$iCount.'</td>';
		}
		$sHtml .= '</tr>';
		$sHtml .= '</tbody></table>';
		$sHtml .= '</div>';

		$sTitle = $this->oModelReflection->DictString($sTitle);
		$sSubtitle = $this->oModelReflection->DictFormat($sSubtitle, $iTotal);

		$sHtml .= '<h1>'.utils::HtmlEntities($sTitle).'</h1>';
		$sHtml .= '<a class="summary">'.utils::HtmlEntities($sSubtitle).'</a>';
		$sHtml .= '</div>';

		$oDashletContainer->AddHtml($sHtml);

		return $oDashletContainer;

	}

	/**
	 * @inheritdoc
	 */
	public function GetPropertiesFields(DesignerForm $oForm)
	{
		$oField = new DesignerTextField('title', Dict::S('UI:DashletHeaderDynamic:Prop-Title'), $this->aProperties['title']);
		$oForm->AddField($oField);

		$oField = $this->oModelReflection->GetIconSelectionField('icon', Dict::S('UI:DashletHeaderDynamic:Prop-Icon'), $this->aProperties['icon']);
		$oField->AddAllowedValue(['value' => '', 'label' => Dict::S('UI:DashletIcon:None'), 'icon' => '']);
		$oForm->AddField($oField);

		$oField = new DesignerTextField('subtitle', Dict::S('UI:DashletHeaderDynamic:Prop-Subtitle'), $this->aProperties['subtitle']);
		$oForm->AddField($oField);

		$oField = new DesignerLongTextField('query', Dict::S('UI:DashletHeaderDynamic:Prop-Query'), $this->aProperties['query']);
		$oField->SetMandatory();
		$oField->AddCSSClass("ibo-query-oql");
		$oField->AddCSSClass("ibo-is-code");
		$oForm->AddField($oField);

		try {
			// Group by field: build the list of possible values (attribute codes + ...)
			$oQuery = $this->oModelReflection->GetQuery($this->aProperties['query']);
			$sClass = $oQuery->GetClass();
			$aGroupBy = $this->GetGroupByOptions($this->aProperties['query']);
			$oField = new DesignerComboField('group_by', Dict::S('UI:DashletHeaderDynamic:Prop-GroupBy'), $this->aProperties['group_by']);
			$oField->SetMandatory();
			$oField->SetAllowedValues($aGroupBy);
		} catch (Exception $e) {
			$oField = new DesignerTextField('group_by', Dict::S('UI:DashletHeaderDynamic:Prop-GroupBy'), $this->aProperties['group_by']);
			$oField->SetReadOnly();
		}
		$oForm->AddField($oField);

		$oField = new DesignerComboField('values', Dict::S('UI:DashletHeaderDynamic:Prop-Values'), $this->aProperties['values']);
		$oField->MultipleSelection(true);
		if (isset($sClass) && $this->oModelReflection->IsValidAttCode($sClass, $this->aProperties['group_by'])) {
			$aValues = $this->oModelReflection->GetAllowedValues_att($sClass, $this->aProperties['group_by']);
			$oField->SetAllowedValues($aValues);
		} else {
			$oField->SetReadOnly();
		}
		$oForm->AddField($oField);
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
					$this->aProperties['values'] = [];
				}
			} catch (Exception $e) {
				$this->bFormRedrawNeeded = true;
			}
		}
		if (in_array('group_by', $aUpdatedFields)) {
			$this->bFormRedrawNeeded = true;
			$this->aProperties['values'] = [];
		}

		return parent::Update($aValues, $aUpdatedFields);
	}

	/**
	 * @inheritdoc
	 */
	protected function PropertyFromDOMNode($oDOMNode, $sProperty)
	{
		if ($sProperty == 'icon') {
			$oIconField = $this->oModelReflection->GetIconSelectionField('icon');

			return $oIconField->ValueFromDOMNode($oDOMNode);
		} else {
			return parent::PropertyFromDOMNode($oDOMNode, $sProperty);
		}
	}

	/**
	 * @inheritdoc
	 */
	protected function PropertyToDOMNode($oDOMNode, $sProperty, $value)
	{
		if ($sProperty == 'icon') {
			$oIconField = $this->oModelReflection->GetIconSelectionField('icon');
			$oIconField->ValueToDOMNode($oDOMNode, $value);
		} else {
			parent::PropertyToDOMNode($oDOMNode, $sProperty, $value);
		}
	}
}

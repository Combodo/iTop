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
}

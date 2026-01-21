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
use DBObjectSearch;
use DesignerBooleanField;
use DesignerForm;
use DesignerHiddenField;
use DesignerLongTextField;
use DesignerTextField;
use Dict;
use DisplayBlock;
use MetaModel;
use utils;

class DashletObjectList extends Dashlet
{
	/**
	 * @inheritdoc
	 */
	public function __construct($oModelReflection, $sId)
	{
		parent::__construct($oModelReflection, $sId);
		$this->aProperties['title'] = '';
		$this->aProperties['query'] = 'SELECT Contact';
		$this->aProperties['menu'] = false;
	}

	/**
	 * @inheritdoc
	 *
	 * @throws \OQLException
	 * @throws \CoreException
	 * @throws \ArchivedObjectException
	 */
	public function Render($oPage, $bEditMode = false, $aExtraParams = [])
	{
		$sTitle = $this->aProperties['title'];
		$sShowMenu = $this->aProperties['menu'] ? '1' : '0';
		$oFilter = $this->GetDBSearch($aExtraParams);
		$sClass = $oFilter->GetClass();
		//$oPanel = PanelUIBlockFactory::MakeForClass($sClass, Dict::S($sTitle))
		//	->AddCSSClass('ibo-datatable-panel');

		$oBlock = new DisplayBlock($oFilter, 'list');
		$aParams = [
			'menu'                => $sShowMenu,
			'table_id'            => self::APP_USER_PREFERENCES_PREFIX.$this->sId,
			'surround_with_panel' => true,
			'max_height'          => '500px',
			"panel_title"         => Dict::S($sTitle),
			"panel_class"         => $sClass,
		];
		$sBlockId = 'block_'.$this->sId.($bEditMode ? '_edit' : ''); // make a unique id (edition occurring in the same DOM)
		//$oBlock->DisplayIntoContentBlock($oPanel, $oPage, $sBlockId, array_merge($aExtraParams, $aParams));

		$oPanel = $oBlock->GetDisplay($oPage, $sBlockId, array_merge($aExtraParams, $aParams));

		return $oPanel;
	}

	/**
	 * @inheritdoc
	 */
	public function RenderNoData($oPage, $bEditMode = false, $aExtraParams = [])
	{
		$oDashletContainer = new DashletContainer($this->sId, ['dashlet-content']);
		$sTitle = $this->aProperties['title'];
		$sQuery = $this->aProperties['query'];
		$bShowMenu = $this->aProperties['menu'];
		$sHtmlTitle = utils::HtmlEntities($this->oModelReflection->DictString($sTitle));
		if ($sHtmlTitle != '') {
			$sHtmlTitle = '<h1>'.$sHtmlTitle.'</h1>';
		}
		$oQuery = $this->oModelReflection->GetQuery($sQuery);
		$sClass = $oQuery->GetClass();
		$sId = $this->sId;
		$sMessage = Dict::S('UI:NoObjectToDisplay');
		$sMenu = '';
		if ($bShowMenu) {
			$sMenu = '<p><a>'.Dict::Format('UI:ClickToCreateNew', $this->oModelReflection->GetName($sClass)).'</a></p>';
		}

		$sHtml = <<<HTML
<div class="dashlet-content">
<h1>$sHtmlTitle</h1>
<div id="block_fake_$sId" class="display_block">
<p>$sMessage</p>
$sMenu
</div>
</div>
HTML;

		$oDashletContainer->AddHtml($sHtml);

		return $oDashletContainer;
	}

	public function GetDBSearch($aExtraParams = [])
	{
		$sQuery = $this->aProperties['query'];
		if (isset($aExtraParams['query_params'])) {
			$aQueryParams = $aExtraParams['query_params'];
		} elseif (isset($aExtraParams['this->class']) && isset($aExtraParams['this->id'])) {
			$oObj = MetaModel::GetObject($aExtraParams['this->class'], $aExtraParams['this->id']);
			$aQueryParams = $oObj->ToArgsForQuery();
		} else {
			$aQueryParams = [];
		}

		return DBObjectSearch::FromOQL($sQuery, $aQueryParams);
	}

	/**
	 * @inheritdoc
	 */
	public function GetPropertiesFields(DesignerForm $oForm)
	{
		$oField = new DesignerTextField('title', Dict::S('UI:DashletObjectList:Prop-Title'), $this->aProperties['title']);
		$oForm->AddField($oField);

		$oField = new DesignerLongTextField('query', Dict::S('UI:DashletObjectList:Prop-Query'), $this->aProperties['query']);
		$oField->SetMandatory();
		$oField->AddCSSClass("ibo-query-oql");
		$oField->AddCSSClass("ibo-is-code");
		$oForm->AddField($oField);

		$oField = new DesignerBooleanField('menu', Dict::S('UI:DashletObjectList:Prop-Menu'), $this->aProperties['menu']);
		$oForm->AddField($oField);
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
		$oField = new DesignerTextField('title', Dict::S('UI:DashletObjectList:Prop-Title'), '');
		$oForm->AddField($oField);

		$oField = new DesignerHiddenField('query', Dict::S('UI:DashletObjectList:Prop-Query'), $sOQL);
		$oField->SetMandatory();
		$oField->AddCSSClass("ibo-query-oql");
		$oField->AddCSSClass("ibo-is-code");
		$oForm->AddField($oField);

		$oField = new DesignerBooleanField('menu', Dict::S('UI:DashletObjectList:Prop-Menu'), $this->aProperties['menu']);
		$oForm->AddField($oField);
	}
}

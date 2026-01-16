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
use DBObjectSearch;
use DesignerForm;
use DesignerIconSelectionField;
use Dict;
use DisplayBlock;
use utils;

class DashletBadge extends Dashlet
{
	/**
	 * @inheritdoc
	 */
	public function __construct($oModelReflection, $sId)
	{
		parent::__construct($oModelReflection, $sId);
		$this->aProperties['class'] = 'Contact';
		$this->aCSSClasses[] = 'ibo-dashlet--is-inline';
		$this->aCSSClasses[] = 'ibo-dashlet-badge';
	}

	/**
	 * @inheritdoc
	 *
	 * @throws \Exception
	 */
	public function Render($oPage, $bEditMode = false, $aExtraParams = [])
	{
		$oDashletContainer = new DashletContainer($this->sId, ['dashlet-content']);

		$sClass = $this->aProperties['class'];
		$oFilter = new DBObjectSearch($sClass);
		$oBlock = new DisplayBlock($oFilter, 'actions');
		$aExtraParams['context_filter'] = 1;
		$aExtraParams['withJSRefreshCallBack'] = true;
		$sBlockId = 'block_'.$this->sId.($bEditMode ? '_edit' : ''); // make a unique id (edition occurring in the same DOM)
		$oBlock->DisplayIntoContentBlock($oDashletContainer, $oPage, $sBlockId, $aExtraParams);

		return $oDashletContainer;
	}

	/**
	 * @inheritdoc
	 */
	public function RenderNoData($oPage, $bEditMode = false, $aExtraParams = [])
	{
		$oDashletContainer = new DashletContainer($this->sId, ['dashlet-content']);

		$sClass = $this->aProperties['class'];
		$sIconUrl = utils::HtmlEntities($this->oModelReflection->GetClassIcon($sClass, false));
		$sClassLabel = $this->oModelReflection->GetName($sClass);
		$sId = $this->sId;
		$sClassCreate = Dict::Format('UI:ClickToCreateNew', $sClassLabel);

		$sHtml = <<<HTML
<div id="block_fake_$sId" class="display_block">
   <div class="ibo-dashlet-badge--body" data-role="ibo-dashlet-badge--body" title="$sClassLabel">
      <div class="ibo-dashlet-badge--icon-container"><img class="ibo-dashlet-badge--icon" src="$sIconUrl"></div>
      <div class="ibo-dashlet-badge--actions"><a class="ibo-dashlet-badge--action-list" href="#" data-role="ibo-dashlet-badge--action-list"><span class="ibo-dashlet-badge--action-list-count">4</span><span class="ibo-dashlet-badge--action-list-label">$sClassLabel</span></a><a class="ibo-dashlet-badge--action-create" href="#"><span class="ibo-dashlet-badge--action-create-icon fas fa-plus"></span><span class="ibo-dashlet-badge--action-create-label"> $sClassCreate </span></a></div>
   </div>
</div>
HTML;

		$oDashletContainer->AddHtml($sHtml);

		return $oDashletContainer;
	}

	protected static $aClassList = null;

	/**
	 * @inheritdoc
	 *
	 * @throws \Exception
	 */
	public function GetPropertiesFields(DesignerForm $oForm)
	{
		if (is_null(self::$aClassList)) {
			// Cache the ordered list of classes (ordered on the label)
			// (has a significant impact when editing a page with lots of badges)
			//
			$aClasses = [];
			foreach ($this->oModelReflection->GetClasses('bizmodel', true /*exclude links*/) as $sClass) {
				$aClasses[$sClass] = $this->oModelReflection->GetName($sClass);
			}
			asort($aClasses);

			self::$aClassList = [];
			foreach ($aClasses as $sClass => $sLabel) {
				$sIconUrl = $this->oModelReflection->GetClassIcon($sClass, false);
				if ($sIconUrl == '') {
					// The icon does not exist, let's use a transparent one of the same size.
					$sIconUrl = utils::GetAbsoluteUrlAppRoot().'images/transparent_32_32.png';
				}
				self::$aClassList[] = ['value' => $sClass, 'label' => $sLabel, 'icon' => $sIconUrl];
			}
		}

		$oField = new DesignerIconSelectionField('class', Dict::S('UI:DashletBadge:Prop-Class'), $this->aProperties['class']);
		$oField->SetAllowedValues(self::$aClassList);

		$oForm->AddField($oField);
	}
}

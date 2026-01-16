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
use Combodo\iTop\Application\UI\Base\Component\Dashlet\DashletFactory;
use DesignerForm;
use DesignerTextField;
use Dict;
use utils;

class DashletHeaderStatic extends Dashlet
{
	/**
	 * @inheritdoc
	 */
	public function __construct($oModelReflection, $sId)
	{
		parent::__construct($oModelReflection, $sId);
		$this->aProperties['title'] = Dict::S('UI:DashletHeaderStatic:Prop-Title:Default');
		$oIconSelect = $this->oModelReflection->GetIconSelectionField('icon');
		$this->aProperties['icon'] = $oIconSelect->GetDefaultValue('Contact');
	}

	/**
	 * @inheritdoc
	 */
	public function Render($oPage, $bEditMode = false, $aExtraParams = [])
	{
		$sTitle = $this->aProperties['title'];
		$sIcon = $this->aProperties['icon'];

		$oIconSelect = $this->oModelReflection->GetIconSelectionField('icon');
		$sIconPath = '';
		if (utils::IsNotNullOrEmptyString($sIcon)) {
			$sIconPath = utils::HtmlEntities($oIconSelect->MakeFileUrl($sIcon));
		}

		return DashletFactory::MakeForDashletHeaderStatic($this->oModelReflection->DictString($sTitle), $sIconPath);
	}

	/**
	 * @inheritdoc
	 */
	public function GetPropertiesFields(DesignerForm $oForm)
	{
		$oField = new DesignerTextField('title', Dict::S('UI:DashletHeaderStatic:Prop-Title'), $this->aProperties['title']);
		$oForm->AddField($oField);

		$oField = $this->oModelReflection->GetIconSelectionField('icon', Dict::S('UI:DashletHeaderStatic:Prop-Icon'), $this->aProperties['icon']);
		$oField->AddAllowedValue(['value' => '', 'label' => Dict::S('UI:DashletIcon:None'), 'icon' => '']);
		$oForm->AddField($oField);
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

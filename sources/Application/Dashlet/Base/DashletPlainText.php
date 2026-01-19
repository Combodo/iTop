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
use Combodo\iTop\Application\UI\Base\Component\Dashlet\DashletFactory;
use DesignerForm;
use DesignerLongTextField;
use Dict;
use utils;

class DashletPlainText extends Dashlet
{
	/**
	 * @inheritdoc
	 */
	public function __construct($oModelReflection, $sId)
	{
		parent::__construct($oModelReflection, $sId);
		$this->aProperties['text'] = Dict::S('UI:DashletPlainText:Prop-Text:Default');
	}

	/**
	 * @inheritdoc
	 */
	public function Render($oPage, $bEditMode = false, $aExtraParams = [])
	{
		$sText = $this->aProperties['text'];
		$sText = utils::EscapeHtml(Dict::S($sText));
		$sText = str_replace(["\r\n", "\n", "\r"], "<br/>", $sText);

		$sId = 'plaintext_'.($bEditMode ? 'edit_' : '').$this->sId;

		return DashletFactory::MakeForDashletPlainText($sText, $sId);
	}

	/**
	 * @inheritdoc
	 */
	public function GetPropertiesFields(DesignerForm $oForm)
	{
		$oField = new DesignerLongTextField('text', Dict::S('UI:DashletPlainText:Prop-Text'), $this->aProperties['text']);
		$oField->SetMandatory();
		$oForm->AddField($oField);
	}
}

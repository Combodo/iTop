<?php
// Copyright (C) 2016 Combodo SARL
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

namespace Combodo\iTop\Renderer\Console\FieldRenderer;

use \Dict;
use Combodo\iTop\Renderer\Console\ConsoleFormRenderer;
use Combodo\iTop\Renderer\FieldRenderer;
use Combodo\iTop\Renderer\RenderingOutput;

class ConsoleSubFormFieldRenderer extends FieldRenderer
{
	public function Render()
	{
		$oOutput = new RenderingOutput();

		$oOutput->AddHtml('<div id="fieldset_'.$this->oField->GetGlobalId().'">');
		$oOutput->AddHtml('</div>');

		$oRenderer = new ConsoleFormRenderer($this->oField->GetForm());
		$aRenderRes = $oRenderer->Render();

		$aFieldSetOptions = array(
			'fields_list' => $aRenderRes,
			'fields_impacts' => $this->oField->GetForm()->GetFieldsImpacts(),
			'form_path' => $this->oField->GetForm()->GetId()
		);
		$sFieldSetOptions = json_encode($aFieldSetOptions);
		$oOutput->AddJs(
<<<EOF
			$("#fieldset_{$this->oField->GetGlobalId()}").field_set($sFieldSetOptions);
			$("[data-field-id='{$this->oField->GetId()}'][data-form-path='{$this->oField->GetFormPath()}']").subform_field({field_set: $("#fieldset_{$this->oField->GetGlobalId()}")});
EOF
				);
		return $oOutput;
	}
}
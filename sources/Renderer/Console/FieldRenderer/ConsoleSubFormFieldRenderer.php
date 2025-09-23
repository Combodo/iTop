<?php
/**
 * Copyright (C) 2013-2024 Combodo SAS
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

namespace Combodo\iTop\Renderer\Console\FieldRenderer;

use Combodo\iTop\Renderer\Console\ConsoleFormRenderer;
use Combodo\iTop\Renderer\FieldRenderer;

/**
 * Class ConsoleSubFormFieldRenderer
 *
 * @author Romain Quetiez <romain.quetiez@combodo.com>
 */
class ConsoleSubFormFieldRenderer extends FieldRenderer
{
	/**
	 * @inheritDoc
	 * @throws \Exception
	 */
    public function Render()
	{
		$oOutput = parent::Render();

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
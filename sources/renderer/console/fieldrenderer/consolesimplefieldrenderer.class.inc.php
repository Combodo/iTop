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
use Combodo\iTop\Renderer\FieldRenderer;
use Combodo\iTop\Renderer\RenderingOutput;

class ConsoleSimpleFieldRenderer extends FieldRenderer
{
	public function Render()
	{
		$oOutput = new RenderingOutput();
		$sFieldClass = get_class($this->oField);

		// TODO : Shouldn't we have a field type so we don't have to maintain FQN classname ?
		// Rendering field in edition mode
		if (!$this->oField->GetReadOnly())
		{
			switch ($sFieldClass)
			{
				case 'Combodo\\iTop\\Form\\Field\\StringField':
					if ($this->oField->GetLabel() !== '')
					{
						$oOutput->AddHtml('<label for="' . $this->oField->GetGlobalId() . '" class="control-label">' . $this->oField->GetLabel() . '</label>');
					}
					$oOutput->AddHtml('<input type="text" id="'.$this->oField->GetGlobalId().'" value="' . $this->oField->GetCurrentValue() . '" size="30" />');
					$oOutput->AddHtml('<span class="form_validation"></span>');
					break;
			}
		}
		// ... and in read-only mode
		else
		{
			switch ($sFieldClass)
			{
				case 'Combodo\\iTop\\Form\\Field\\StringField':
					if ($this->oField->GetLabel() !== '')
					{
						$oOutput->AddHtml('<label for="' . $this->oField->GetGlobalId() . '" class="control-label">' . $this->oField->GetLabel() . '</label>');
					}
					$oOutput->AddHtml('<div class="form-control-static">' . $this->oField->GetCurrentValue() . '</div>');
					break;
			}
		}

		switch ($sFieldClass)
		{
			case 'Combodo\\iTop\\Form\\Field\\StringField':
				$oOutput->AddJs(
<<<EOF
                    $("#{$this->oField->GetGlobalId()}").off("change").on("change keyup", function(){
                    	var me = this;

                        $(this).closest(".field_set").trigger("field_change", {
                            id: $(me).attr("id"),
                            name: $(me).closest(".form_field").attr("data-field-id"),
                            value: $(me).val()
                        });
                    });
EOF
				);
				break;
		}

		// JS Form field widget construct
		$aValidators = array();
		foreach ($this->oField->GetValidators() as $oValidator)
		{
			$aValidators[$oValidator::GetName()] = array(
				'reg_exp' => $oValidator->GetRegExp(),
				'message' => Dict::S($oValidator->GetErrorMessage())
			);
		}

		$sValidators = json_encode($aValidators);
		$sFormFieldOptions =
<<<EOF
{
	validators: $sValidators,
	on_validation_callback: function(me, oResult) {
		var oValidationElement = $(me.element).find('span.form_validation');
		if (oResult.is_valid)
		{
			oValidationElement.html('');
		}
		else
		{
			//TODO: escape html entities
			var sExplain = oResult.error_messages.join(', ');
			oValidationElement.html('<img src="../images/validation_error.png" style="vertical-align:middle" data-tooltip="'+sExplain+'"/>');
			oValidationElement.tooltip({
				items: 'span',
				tooltipClass: 'form_field_error',
				content: function() {
					return $(this).find('img').attr('data-tooltip'); // As opposed to the default 'content' handler, do not escape the contents of 'title'
				}
			});
		}
	}
}
EOF
			;

		switch ($sFieldClass)
		{
			case 'Combodo\\iTop\\Form\\Field\\StringField':
			case 'Combodo\\iTop\\Form\\Field\\TextAreaField':
			case 'Combodo\\iTop\\Form\\Field\\SelectField':
			case 'Combodo\\iTop\\Form\\Field\\HiddenField':
			case 'Combodo\\iTop\\Form\\Field\\RadioField':
			case 'Combodo\\iTop\\Form\\Field\\CheckboxField':
				$oOutput->AddJs(
					<<<EOF
                    $("[data-field-id='{$this->oField->GetId()}'][data-form-path='{$this->oField->GetFormPath()}']").form_field($sFormFieldOptions);
EOF
				);
				break;
		}

		return $oOutput;
	}
}
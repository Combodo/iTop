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

use Combodo\iTop\Form\Field\StringField;
use \Dict;
use Combodo\iTop\Renderer\FieldRenderer;
use Combodo\iTop\Renderer\RenderingOutput;

class ConsoleSimpleFieldRenderer extends FieldRenderer
{
	public function Render()
	{
		$oOutput = new RenderingOutput();
		$sFieldClass = get_class($this->oField);

		if ($sFieldClass == 'Combodo\\iTop\\Form\\Field\\HiddenField')
		{
			$oOutput->AddHtml('<input type="hidden" id="'.$this->oField->GetGlobalId().'" value="' . htmlentities($this->oField->GetCurrentValue(), ENT_QUOTES, 'UTF-8') . '"/>');
		}
		else
		{
			$oOutput->AddHtml('<table class="form-field-container">');
			$oOutput->AddHtml('<tr>');
			if ($this->oField->GetLabel() != '')
			{
				$oOutput->AddHtml('<td class="form-field-label label"><span><label for="'.$this->oField->GetGlobalId().'">'.$this->oField->GetLabel().'</label></span></td>');
			}
			switch ($sFieldClass)
			{
				case 'Combodo\\iTop\\Form\\Field\\StringField':
					$oOutput->AddHtml('<td class="form-field-content">');
					if ($this->oField->GetReadOnly())
					{
						$oOutput->AddHtml('<input type="hidden" id="'.$this->oField->GetGlobalId().'" value="' . htmlentities($this->oField->GetCurrentValue(), ENT_QUOTES, 'UTF-8') . '"/>');
						$oOutput->AddHtml('<span class="form-field-data">'.htmlentities($this->oField->GetCurrentValue(), ENT_QUOTES, 'UTF-8').'</span>');
					}
					else
					{
						$oOutput->AddHtml('<input class="form-field-data" type="text" id="'.$this->oField->GetGlobalId().'" value="'.htmlentities($this->oField->GetCurrentValue(), ENT_QUOTES, 'UTF-8').'" size="30"/>');
					}
					$oOutput->AddHtml('<span class="form_validation"></span>');
					$oOutput->AddHtml('</td>');
					break;

				case 'Combodo\\iTop\\Form\\Field\\SelectField':
					$oOutput->AddHtml('<td class="form-field-content">');
					if ($this->oField->GetReadOnly())
					{
						$aChoices = $this->oField->GetChoices();
						$sCurrentLabel = isset($aChoices[$this->oField->GetCurrentValue()]) ? $aChoices[$this->oField->GetCurrentValue()] : '' ;
						$oOutput->AddHtml('<input type="hidden" id="'.$this->oField->GetGlobalId().'" value="' . htmlentities($this->oField->GetCurrentValue(), ENT_QUOTES, 'UTF-8') . '"/>');
						$oOutput->AddHtml('<span class="form-field-data">'.htmlentities($sCurrentLabel, ENT_QUOTES, 'UTF-8').'</span>');
					}
					else
					{
						$oOutput->AddHtml('<select class="form-field-data" id="'.$this->oField->GetGlobalId().'" '.(($this->oField->GetMultipleValuesEnabled()) ? 'multiple' : '').'>');
						foreach ($this->oField->GetChoices() as $sChoice => $sLabel)
						{
							// Note : The test is a double equal on purpose as the type of the value received from the XHR is not always the same as the type of the allowed values. (eg : string vs int)
							$sSelectedAtt = ($this->oField->GetCurrentValue() == $sChoice) ? 'selected' : '';
							$oOutput->AddHtml('<option value="'.htmlentities($sChoice, ENT_QUOTES, 'UTF-8').'" '.$sSelectedAtt.' >'.htmlentities($sLabel, ENT_QUOTES, 'UTF-8').'</option>');
						}
						$oOutput->AddHtml('</select>');
					}
					$oOutput->AddHtml('<span class="form_validation"></span>');
					$oOutput->AddHtml('</td>');
					break;
			}
			$oOutput->AddHtml('</tr>');
			$oOutput->AddHtml('</table>');
		}

		switch ($sFieldClass)
		{
			case 'Combodo\\iTop\\Form\\Field\\StringField':
				$oOutput->AddJs(
<<<EOF
                    $("#{$this->oField->GetGlobalId()}").off("change keyup").on("change keyup", function(){
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
			case 'Combodo\\iTop\\Form\\Field\\SelectField':
				$oOutput->AddJs(
<<<EOF
                    $("#{$this->oField->GetGlobalId()}").off("change").on("change", function(){
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

		$oOutput->AddJs(
			<<<EOF
                    $("[data-field-id='{$this->oField->GetId()}'][data-form-path='{$this->oField->GetFormPath()}']").form_field($sFormFieldOptions);
EOF
		);
		switch ($sFieldClass)
		{
			case 'Combodo\\iTop\\Form\\Field\\SelectField':
				$oOutput->AddJs(
					<<<EOF
	                    $("[data-field-id='{$this->oField->GetId()}'][data-form-path='{$this->oField->GetFormPath()}']").form_field('option', 'get_current_value_callback', function(me){ return $(me.element).find('select').val();});
EOF
				);
				break;

			case 'Combodo\\iTop\\Form\\Field\\HiddenField':
			case 'Combodo\\iTop\\Form\\Field\\StringField':
			case 'Combodo\\iTop\\Form\\Field\\TextAreaField':
			case 'Combodo\\iTop\\Form\\Field\\HiddenField':
			case 'Combodo\\iTop\\Form\\Field\\RadioField':
			case 'Combodo\\iTop\\Form\\Field\\CheckboxField':
				break;
		}

		return $oOutput;
	}
}
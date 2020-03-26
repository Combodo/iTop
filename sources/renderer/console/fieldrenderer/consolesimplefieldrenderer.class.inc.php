<?php
/**
 * Copyright (C) 2013-2019 Combodo SARL
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

use AttributeDate;
use AttributeDateTime;
use AttributeDuration;
use DateTimeFormat;
use Dict;
use InlineImage;
use UserRights;
use utils;
use Combodo\iTop\Form\Field\TextAreaField;
use Combodo\iTop\Renderer\FieldRenderer;

/**
 * Class ConsoleSimpleFieldRenderer
 *
 * @author Romain Quetiez <romain.quetiez@combodo.com>
 */
class ConsoleSimpleFieldRenderer extends FieldRenderer
{
	public function Render()
	{
		$oOutput = parent::Render();
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
				case 'Combodo\\iTop\\Form\\Field\\DateTimeField':
					$sDateTimeFormat = $this->oField->GetPHPDateTimeFormat();
					$oFormat = new DateTimeFormat($sDateTimeFormat);
					$sPlaceHolder = $oFormat->ToPlaceholder();
					$oOutput->AddHtml('<td class="form-field-content">');
					if ($this->oField->GetReadOnly())
					{
						$oOutput->AddHtml('<input type="hidden" id="'.$this->oField->GetGlobalId().'" value="' . htmlentities($this->oField->GetCurrentValue(), ENT_QUOTES, 'UTF-8') . '"/>');
						$oOutput->AddHtml('<span class="form-field-data">'.htmlentities($this->oField->GetCurrentValue(), ENT_QUOTES, 'UTF-8').'</span>');
					}
					else
					{
						$oOutput->AddHtml('<input class="form-field-data datetime-pick" size="15" type="text" placeholder="'.htmlentities($sPlaceHolder, ENT_QUOTES, 'UTF-8').'" id="'.$this->oField->GetGlobalId().'" value="'.htmlentities($this->oField->GetCurrentValue(), ENT_QUOTES, 'UTF-8').'" size="30"/>');
					}
					$oOutput->AddHtml('<span class="form_validation"></span>');
					$oOutput->AddHtml('</td>');
					break;

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

				case 'Combodo\\iTop\\Form\\Field\\TextAreaField':
					$bRichEditor = ($this->oField->GetFormat() === TextAreaField::ENUM_FORMAT_HTML);

					$oOutput->AddHtml('<td class="form-field-content">');
					if ($this->oField->GetReadOnly())
					{
						$oOutput->AddHtml('<textarea disabled="disabled" id="' . $this->oField->GetGlobalId() . '" class="form-field-data resizable" rows="8" cols="40">' . $this->oField->GetCurrentValue() . '</textarea>');
					}
					else
					{
						$oOutput->AddHtml('<textarea id="' . $this->oField->GetGlobalId() . '" class="form-field-data resizable" rows="8" cols="40">' . $this->oField->GetCurrentValue() . '</textarea>');
						// Some additional stuff if we are displaying it with a rich editor
						if ($bRichEditor)
						{
							$sEditorLanguage = strtolower(trim(UserRights::GetUserLanguage()));
							$oOutput->AddJs(
<<<EOF
								$('#{$this->oField->GetGlobalId()}').addClass('htmlEditor');
							$('#{$this->oField->GetGlobalId()}').ckeditor(function(){}, {language: '$sEditorLanguage', contentsLanguage: '$sEditorLanguage', extraPlugins: 'codesnippet'});
EOF
							);
							if (($this->oField->GetObject() !== null) && ($this->oField->GetTransactionId() !== null))
							{
								$oOutput->AddJs(InlineImage::EnableCKEditorImageUpload($this->oField->GetObject(), utils::GetUploadTempId($this->oField->GetTransactionId())));
							}
						}
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

				case 'Combodo\\iTop\\Form\\Field\\RadioField':
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
						$bVertical = true;
						$idx = 0;
						$bMandatory = $this->oField->GetMandatory();
						$value = $this->oField->GetCurrentValue();
						$sId = $this->oField->GetGlobalId();
						$oOutput->AddHtml('<div>');
						$aChoices = $this->oField->GetChoices();
						foreach ($aChoices as $sChoice => $sLabel)
						{
							if ((count($aChoices)== 1) && $bMandatory)
							{
								// When there is only once choice, select it by default
								$sSelected = 'checked';
							    $value = $sChoice;
							}
							else
							{
								$sSelected = ($value == $sChoice) ? 'checked' : '';
							}
							$oOutput->AddHtml("<input type=\"radio\" id=\"{$sId}_{$idx}\" name=\"radio_$sId\" onChange=\"$('#{$sId}').val(this.value).trigger('change');\" value=\"".htmlentities($sChoice, ENT_QUOTES, 'UTF-8')."\" $sSelected><label class=\"radio\" for=\"{$sId}_{$idx}\">&nbsp;".htmlentities($sLabel, ENT_QUOTES, 'UTF-8')."</label>&nbsp;");
							if ($bVertical)
							{
								$oOutput->AddHtml("<br>\n");
							}
							$idx++;
						}
						$oOutput->AddHtml('</div>');
						$oOutput->AddHtml("<input type=\"hidden\" id=\"$sId\" name=\"$sId\" value=\"$value\"/>");
					}
					$oOutput->AddHtml('<span class="form_validation"></span>');
					$oOutput->AddHtml('</td>');
					break;

				case 'Combodo\\iTop\\Form\\Field\\DurationField':
					$oOutput->AddHtml('<td class="form-field-content">');
					$value = $this->oField->GetCurrentValue();
					if ($this->oField->GetReadOnly())
					{
						$oOutput->AddHtml('<input type="hidden" id="'.$this->oField->GetGlobalId().'" value="' . htmlentities($value, ENT_QUOTES, 'UTF-8') . '"/>');
						$oOutput->AddHtml('<span class="form-field-data">'.htmlentities(\AttributeDuration::FormatDuration($value), ENT_QUOTES, 'UTF-8').'</span>');
					}
					else
					{
						$sId = $this->oField->GetGlobalId();

						$aVal = AttributeDuration::SplitDuration($value);
						$sDays = "<input type=\"text\" size=\"3\" name=\"{$sId}[d]\" value=\"{$aVal['days']}\" id=\"{$sId}_d\"/>";
						$sHours = "<input type=\"text\" size=\"2\" name=\"{$sId}[h]\" value=\"{$aVal['hours']}\" id=\"{$sId}_h\"/>";
						$sMinutes = "<input type=\"text\" size=\"2\" name=\"{$sId}[m]\" value=\"{$aVal['minutes']}\" id=\"{$sId}_m\"/>";
						$sSeconds = "<input type=\"text\" size=\"2\" name=\"{$sId}[s]\" value=\"{$aVal['seconds']}\" id=\"{$sId}_s\"/>";
						$oOutput->AddHtml(Dict::Format('UI:DurationForm_Days_Hours_Minutes_Seconds', $sDays, $sHours, $sMinutes, $sSeconds));
						$oOutput->AddHtml("<input type=\"hidden\" id=\"{$sId}\" value=\"".htmlentities($value, ENT_QUOTES, 'UTF-8')."\"/>");

						$oOutput->AddJs("$('#{$sId}_d').bind('keyup change', function(evt, sFormId) { return UpdateDuration('$sId'); });");
						$oOutput->AddJs("$('#{$sId}_h').bind('keyup change', function(evt, sFormId) { return UpdateDuration('$sId'); });");
						$oOutput->AddJs("$('#{$sId}_m').bind('keyup change', function(evt, sFormId) { return UpdateDuration('$sId'); });");
						$oOutput->AddJs("$('#{$sId}_s').bind('keyup change', function(evt, sFormId) { return UpdateDuration('$sId'); });");
						$oOutput->AddJs("$('#{$sId}').bind('update', function(evt, sFormId) { return ToggleDurationField('$sId'); });");
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
			case 'Combodo\\iTop\\Form\\Field\\DateTimeField':
				$sDateTimeFormat = $this->oField->GetPHPDateTimeFormat();
				$sJSDaysMin = json_encode(array(Dict::S('DayOfWeek-Sunday-Min'), Dict::S('DayOfWeek-Monday-Min'), Dict::S('DayOfWeek-Tuesday-Min'), Dict::S('DayOfWeek-Wednesday-Min'),
								Dict::S('DayOfWeek-Thursday-Min'), Dict::S('DayOfWeek-Friday-Min'), Dict::S('DayOfWeek-Saturday-Min')));
				$sJSMonthsShort = json_encode(array(Dict::S('Month-01-Short'), Dict::S('Month-02-Short'), Dict::S('Month-03-Short'), Dict::S('Month-04-Short'), Dict::S('Month-05-Short'), Dict::S('Month-06-Short'), 
													Dict::S('Month-07-Short'), Dict::S('Month-08-Short'), Dict::S('Month-09-Short'), Dict::S('Month-10-Short'), Dict::S('Month-11-Short'), Dict::S('Month-12-Short')));
				$iFirstDayOfWeek = (int) Dict::S('Calendar-FirstDayOfWeek');
				$sJSDateFormat = json_encode(AttributeDate::GetFormat()->ToDatePicker());
				$sTimeFormat = AttributeDateTime::GetFormat()->ToTimeFormat();
				$oTimeFormat = new DateTimeFormat($sTimeFormat);
				$sJSTimeFormat = json_encode($oTimeFormat->ToDatePicker());
				$sJSOk = json_encode(Dict::S('UI:Button:Ok'));
				if ($this->oField->IsDateOnly())
				{
					$oOutput->AddJs(
<<<EOF
				$("#{$this->oField->GetGlobalId()}").datepicker({
						showOn: 'button',
						buttonImage: '../images/calendar.png',
						buttonImageOnly: true,
						dateFormat: $sJSDateFormat,
						constrainInput: false,
						changeMonth: true,
						changeYear: true,
						dayNamesMin: $sJSDaysMin,
						monthNamesShort: $sJSMonthsShort,
						firstDay: $iFirstDayOfWeek
				});
EOF
					);
				}
				else
				{
					$oOutput->AddJs(
<<<EOF
				$("#{$this->oField->GetGlobalId()}").datetimepicker({
						showOn: 'button',
						buttonImage: '../images/calendar.png',
						buttonImageOnly: true,
						dateFormat: $sJSDateFormat,
						constrainInput: false,
						changeMonth: true,
						changeYear: true,
						dayNamesMin: $sJSDaysMin,
						monthNamesShort: $sJSMonthsShort,
						firstDay: $iFirstDayOfWeek,
						// time picker options	
						timeFormat: $sJSTimeFormat,
						controlType: 'select',
						closeText: $sJSOk
				});
EOF
					);
				}
				
				$oOutput->AddJs(
<<<EOF
                    $("#{$this->oField->GetGlobalId()}").off("change keyup").on("change keyup", function(){
                    	var me = this;

                        $(this).closest(".field_set").trigger("field_change", {
                            id: $(me).attr("id"),
                            name: $(me).closest(".form_field").attr("data-field-id"),
                            value: $(me).val()
                        })
                        .closest('.form_handler').trigger('value_change');
                    });
EOF
				);
				break;				
			break;
			
			case 'Combodo\\iTop\\Form\\Field\\StringField':
			case 'Combodo\\iTop\\Form\\Field\\TextAreaField':
				$oOutput->AddJs(
<<<EOF
                    $("#{$this->oField->GetGlobalId()}").off("change keyup").on("change keyup", function(){
                    	var me = this;

                        $(this).closest(".field_set").trigger("field_change", {
                            id: $(me).attr("id"),
                            name: $(me).closest(".form_field").attr("data-field-id"),
                            value: $(me).val()
                        })
                        .closest('.form_handler').trigger('value_change');
                    });
EOF
				);
				break;

			case 'Combodo\\iTop\\Form\\Field\\SelectField':
			case 'Combodo\\iTop\\Form\\Field\\RadioField':
			case 'Combodo\\iTop\\Form\\Field\\DurationField':
				$oOutput->AddJs(
<<<EOF
                    $("#{$this->oField->GetGlobalId()}").off("change").on("change", function(){
                    	var me = this;

                        $(this).closest(".field_set").trigger("field_change", {
                            id: $(me).attr("id"),
                            name: $(me).closest(".form_field").attr("data-field-id"),
                            value: $(me).val()
                        })
                        .closest('.form_handler').trigger('value_change');
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
		}

		$oOutput->AddJs(
			<<<JS
                   $("[data-field-id='{$this->oField->GetId()}'][data-form-path='{$this->oField->GetFormPath()}']").trigger('validate');
JS
		);

		return $oOutput;
	}
}

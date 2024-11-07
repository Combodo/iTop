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

use AttributeDate;
use AttributeDateTime;
use AttributeDuration;
use Combodo\iTop\Application\Helper\CKEditorHelper;
use Combodo\iTop\Application\UI\Base\Component\Field\FieldUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Html\Html;
use Combodo\iTop\Application\UI\Base\Component\Html\HtmlFactory;
use Combodo\iTop\Application\UI\Base\Component\Input\InputUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Input\Select\SelectOptionUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Input\Select\SelectUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Input\TextArea;
use Combodo\iTop\Application\UI\Base\Component\Text\Text;
use Combodo\iTop\Application\UI\Base\Layout\UIContentBlockUIBlockFactory;
use Combodo\iTop\Form\Field\TextAreaField;
use Combodo\iTop\Form\Validator\AbstractRegexpValidator;
use Combodo\iTop\Renderer\BlockRenderer;
use Combodo\iTop\Renderer\FieldRenderer;
use DateTimeFormat;
use Dict;
use InlineImage;
use utils;

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
			$oOutput->AddHtml('<input type="hidden" id="'.$this->oField->GetGlobalId().'" value="'.utils::EscapeHtml($this->oField->GetCurrentValue()).'"/>');
		}
		else
		{
			$oBlock = FieldUIBlockFactory::MakeStandard($this->oField->GetLabel());
			$oBlock->SetAttLabel($this->oField->GetLabel())
				->AddDataAttribute("input-id",$this->oField->GetGlobalId())
				->AddDataAttribute("input-type",$sFieldClass);

			// Propagate data attribute from Field to UIBlock
			// Note: This might no longer be necessary after the upcoming attributes rework project
			foreach ($this->oField->GetMetadata() as $sMetadataKey => $sMetadataValue) {
				switch ($sMetadataKey) {
					// Important: Only some data attributes can be overloaded, this is done on purpose (eg. "input-type" set previously by an AttributeCustomFields)
					case 'attribute-code':
					case 'attribute-type':
					case 'input-type':
						if (utils::IsNotNullOrEmptyString($sMetadataValue)) {
							switch ($sMetadataKey) {
								case 'attribute-code':
									$oBlock->SetAttCode($sMetadataValue);
									break;

								case 'attribute-type':
									$oBlock->SetAttType($sMetadataValue ?? '');
									break;

								case 'input-type':
									$oBlock->AddDataAttribute($sMetadataKey, $sMetadataValue ?? '');
									break;
							}
						}
						break;

					default:
						if (false === $oBlock->HasDataAttribute($sMetadataKey)) {
							$oBlock->AddDataAttribute($sMetadataKey, $sMetadataValue ?? '');
						}
						break;
		       }
			}

			switch ($sFieldClass)
			{
				case 'Combodo\\iTop\\Form\\Field\\DateTimeField':
					$oValue = UIContentBlockUIBlockFactory::MakeStandard("",["form-field-content"]);

					$sDateTimeFormat = $this->oField->GetPHPDateTimeFormat();
					$oFormat = new DateTimeFormat($sDateTimeFormat);
					$sPlaceHolder = $oFormat->ToPlaceholder();
					if ($this->oField->GetReadOnly())
					{
						$oValue->AddSubBlock(InputUIBlockFactory::MakeForHidden("",$this->oField->GetCurrentValue(),$this->oField->GetGlobalId()));
						$oValue->AddSubBlock(new Html($this->oField->GetCurrentValue()));
					}
					else {
						$oField = UIContentBlockUIBlockFactory::MakeStandard("", ["field_input_zone", "field_input_datetime", "ibo-input-field-wrapper", "ibo-input-datetime-wrapper"]);
						$oValue->AddSubBlock($oField);
						$oField->AddSubBlock(new Html('<input class="date-pick ibo-input ibo-input-date" type="text" placeholder="'.utils::EscapeHtml($sPlaceHolder).'" id="'.$this->oField->GetGlobalId().'" value="'.utils::EscapeHtml($this->oField->GetCurrentValue()).'" autocomplete="off"/>'));
						$oField->AddSubBlock(new Html('<span class="form_validation"></span>'));
					}
					$oBlock->AddSubBlock($oValue);
				break;

				case 'Combodo\\iTop\\Form\\Field\\LabelField':
					$oValue = UIContentBlockUIBlockFactory::MakeStandard("",[""]);
					$oBlock->AddSubBlock($oValue);
					$oValue->AddSubBlock(new Text($this->oField->GetCurrentValue()));
					$oValue->AddSubBlock(new Html('<span class="form_validation"></span>'));
					break;

				case 'Combodo\\iTop\\Form\\Field\\StringField':
					$oValue = UIContentBlockUIBlockFactory::MakeStandard("",["ibo-input-field-wrapper"]);

					if ($this->oField->GetReadOnly())
					{
						$oValue->AddSubBlock(InputUIBlockFactory::MakeForHidden("",$this->oField->GetCurrentValue(),$this->oField->GetGlobalId()));
						$oValue->AddSubBlock(new Html($this->oField->GetCurrentValue()));
					}
					else
					{
						$oValue->AddSubBlock(InputUIBlockFactory::MakeStandard("text","", $this->oField->GetCurrentValue(),$this->oField->GetGlobalId()));
						$oValue->AddSubBlock(new Html('<span class="form_validation"></span>'));
					}
					$oBlock->AddSubBlock($oValue);
					break;

				case 'Combodo\\iTop\\Form\\Field\\TextAreaField':
					$oValue = UIContentBlockUIBlockFactory::MakeStandard("",["form-field-content", "ibo-input-field-wrapper"]);

					$bRichEditor = ($this->oField->GetFormat() === TextAreaField::ENUM_FORMAT_HTML);


					if ($this->oField->GetReadOnly())
					{
						$oValue->AddSubBlock(UIContentBlockUIBlockFactory::MakeStandard())->AddSubBlock(HtmlFactory::MakeHtmlContent($this->oField->GetDisplayValue()));
						$oValue->AddSubBlock(InputUIBlockFactory::MakeForHidden("",$this->oField->GetCurrentValue(), $this->oField->GetGlobalId()));
					}
					else
					{
						$oText = new TextArea("", CKEditorHelper::PrepareCKEditorValueTextEncodingForTextarea($this->oField->GetCurrentValue()),$this->oField->GetGlobalId(),40,8);
						$oText->AddCSSClasses(['ibo-input-field-wrapper', 'ibo-input']);
						$oValue->AddSubBlock($oText);
						// Some additional stuff if we are displaying it with a rich editor
						if ($bRichEditor)
						{
							$oText->AddCSSClass('ibo-input-richtext-placeholder');

							// Enable CKEditor
							CKEditorHelper::ConfigureCKEditorElementForRenderingOutput($oOutput, $this->oField->GetGlobalId(), $this->oField->GetCurrentValue());

							if (($this->oField->GetObject() !== null) && ($this->oField->GetTransactionId() !== null)){
								$oOutput->AddJs(InlineImage::EnableCKEditorImageUpload($this->oField->GetObject(), utils::GetUploadTempId($this->oField->GetTransactionId())));
							}
						}
						$oValue->AddSubBlock(new Html('<span class="form_validation"></span>'));
					}
					$oBlock->AddSubBlock($oValue);
					break;

				case 'Combodo\\iTop\\Form\\Field\\SelectField':
					$oValue = UIContentBlockUIBlockFactory::MakeStandard("",["form-field-content","ibo-input-field-wrapper"]);
					if ($this->oField->GetReadOnly())
					{
						$aChoices = $this->oField->GetChoices();
						$sCurrentLabel = isset($aChoices[$this->oField->GetCurrentValue()]) ? $aChoices[$this->oField->GetCurrentValue()] : '' ;
						$oValue->AddSubBlock(InputUIBlockFactory::MakeForHidden("",$this->oField->GetCurrentValue(),$this->oField->GetGlobalId()));
						$oValue->AddSubBlock(new Html($sCurrentLabel));
					}
					else
					{
						$oSelect = SelectUIBlockFactory::MakeForSelect("",$this->oField->GetGlobalId());
						$oSelect->AddCSSClass('ibo-input-field-wrapper');
						if ($this->oField->GetMultipleValuesEnabled()) {
							$oSelect->SetIsMultiple(true);
						}
						foreach ($this->oField->GetChoices() as $sChoice => $sLabel)
						{
							// Note : The test is a double equal on purpose as the type of the value received from the XHR is not always the same as the type of the allowed values. (eg : string vs int)
							$oSelect->AddOption(SelectOptionUIBlockFactory::MakeForSelectOption($sChoice,$sLabel, ($this->oField->GetCurrentValue() == $sChoice)));
						}
						$oValue->AddSubBlock($oSelect);
						$oValue->AddSubBlock(new Html('<span class="form_validation"></span>'));
					}
					$oBlock->AddSubBlock($oValue);
					break;

				case 'Combodo\\iTop\\Form\\Field\\RadioField':
					$oValue = UIContentBlockUIBlockFactory::MakeStandard("",["form-field-content"]);
					if ($this->oField->GetReadOnly())
					{
						$aChoices = $this->oField->GetChoices();
						$sCurrentLabel = isset($aChoices[$this->oField->GetCurrentValue()]) ? $aChoices[$this->oField->GetCurrentValue()] : '' ;
						$oValue->AddSubBlock(InputUIBlockFactory::MakeForHidden("",$this->oField->GetCurrentValue(),$this->oField->GetGlobalId()));
						$oValue->AddSubBlock(new Html($sCurrentLabel));
					}
					else
					{
						$bVertical = true;
						$idx = 0;
						$bMandatory = $this->oField->GetMandatory();
						$value = $this->oField->GetCurrentValue();
						$sId = $this->oField->GetGlobalId();
						$aChoices = $this->oField->GetChoices();
						foreach ($aChoices as $sChoice => $sLabel)
						{
							if ((count($aChoices) == 1) && $bMandatory) {
								// When there is only once choice, select it by default
								$sSelected = 'checked';
								$value = $sChoice;
							} else {
								$sSelected = ($value == $sChoice) ? 'checked' : '';
							}
							$oRadio = InputUIBlockFactory::MakeForInputWithLabel($sLabel, "radio_".$sId, $sChoice, "{$sId}_{$idx}", "radio");
							$oRadio->GetInput()->SetIsChecked($sSelected);
							$oRadio->SetBeforeInput(false);
							$oRadio->GetInput()->AddCSSClass('ibo-input-checkbox');
							$oValue->AddSubBlock($oRadio);
							if ($bVertical) {
								$oValue->AddSubBlock(new Html("<br>"));
							}
							$oOutput->AddJs(
								<<<EOF
	                    $("#{$sId}_{$idx}").off("change").on("change", function(){
	                        $('#{$sId}').val(this.value).trigger('change');
	                    });
EOF
							);
							$idx++;
						}
						$oValue->AddSubBlock(InputUIBlockFactory::MakeForHidden("",$value,$sId));
						$oValue->AddSubBlock(new Html('<span class="form_validation"></span>'));
					}
					$oBlock->AddSubBlock($oValue);
					break;

				case 'Combodo\\iTop\\Form\\Field\\DurationField':
					$oValue = UIContentBlockUIBlockFactory::MakeStandard("",["form-field-content","ibo-input-field-wrapper"]);
					$value = $this->oField->GetCurrentValue();
					if ($this->oField->GetReadOnly())
					{
						$oValue->AddSubBlock(InputUIBlockFactory::MakeForHidden("",$value,$this->oField->GetGlobalId()));
						$oValue->AddSubBlock(new Html(AttributeDuration::FormatDuration($value)));
					}
					else {
						$sId = $this->oField->GetGlobalId();

						$aVal = AttributeDuration::SplitDuration($value);
						$sDays = "<input type=\"text\" size=\"3\" class=\"ibo-input ibo-input-vanilla\" name=\"{$sId}[d]\" value=\"{$aVal['days']}\" id=\"{$sId}_d\"/>";
						$sHours = "<input type=\"text\" size=\"2\" class=\"ibo-input ibo-input-vanilla\" name=\"{$sId}[h]\" value=\"{$aVal['hours']}\" id=\"{$sId}_h\"/>";
						$sMinutes = "<input type=\"text\" size=\"2\" class=\"ibo-input ibo-input-vanilla\" name=\"{$sId}[m]\" value=\"{$aVal['minutes']}\" id=\"{$sId}_m\"/>";
						$sSeconds = "<input type=\"text\" size=\"2\" class=\"ibo-input ibo-input-vanilla\" name=\"{$sId}[s]\" value=\"{$aVal['seconds']}\" id=\"{$sId}_s\"/>";
						$oTime = UIContentBlockUIBlockFactory::MakeStandard("",["pt-2"]);
						$oTime->AddSubBlock(new Html(Dict::Format('UI:DurationForm_Days_Hours_Minutes_Seconds', $sDays, $sHours, $sMinutes, $sSeconds)));
						$oValue->AddSubBlock($oTime);
						$oValue->AddSubBlock(InputUIBlockFactory::MakeForHidden("",$value,$sId));

						$oOutput->AddJs("$('#{$sId}_d').on('keyup change', function(evt, sFormId) { return UpdateDuration('$sId'); });");
						$oOutput->AddJs("$('#{$sId}_h').on('keyup change', function(evt, sFormId) { return UpdateDuration('$sId'); });");
						$oOutput->AddJs("$('#{$sId}_m').on('keyup change', function(evt, sFormId) { return UpdateDuration('$sId'); });");
						$oOutput->AddJs("$('#{$sId}_s').on('keyup change', function(evt, sFormId) { return UpdateDuration('$sId'); });");
						$oOutput->AddJs("$('#{$sId}').on('update', function(evt, sFormId) { return ToggleDurationField('$sId'); });");
						$oValue->AddSubBlock(new Html('<span class="form_validation"></span>'));
					}
					$oBlock->AddSubBlock($oValue);
					break;
			}
			$oOutput->AddHtml(BlockRenderer::RenderBlockTemplates($oBlock));
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
			var oInput = "#{$this->oField->GetGlobalId()}";
			$(oInput).addClass('is-widget-ready');
			
			$(oInput).datepicker({
								"showOn":"button",
								"buttonText":"",
								"dateFormat": $sJSDateFormat,
								"constrainInput":false,
								"changeMonth":true,
								"changeYear":true,
								"dayNamesMin":$sJSDaysMin,
								"monthNamesShort": $sJSMonthsShort,
								"firstDay":$iFirstDayOfWeek,
								"onSelect":function(a,b){ $("#{$this->oField->GetGlobalId()}").trigger("change");},
								}).next("img").wrap("<span>");

EOF
					);
				}
				else
				{
					$oOutput->AddJs(
<<<EOF
			var oInput = "#{$this->oField->GetGlobalId()}";
			$(oInput).addClass('is-widget-ready');
			
				$(oInput).datetimepicker({
							showOn: 'button',
							buttonText: "",
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
				$oOutput->AddJs(
					<<<EOF
 $("#{$this->oField->GetGlobalId()}").selectize({
    sortField: 'text',
    onChange: function(value){
    			 var me = this.\$input;
                me.closest(".field_set").trigger("field_change", {
                    id: me.attr("id"),
                    name: me.closest(".form_field").attr("data-field-id"),
                    value: me.val()
                })
                .closest('.form_handler').trigger('value_change');
    }
});
EOF
				);
				break;
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
		foreach ($this->oField->GetValidators() as $oValidator) {
			if (false === ($oValidator instanceof AbstractRegexpValidator)) {
				// no JS counterpart, so skipping !
				continue;
			}

			$aValidators[$oValidator::GetName()] = array(
				'reg_exp' => $oValidator->GetRegExp(),
				'message' => Dict::S($oValidator->GetErrorMessage()),
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
			 $(me.element).find('.ibo-input-field-wrapper').removeClass("is-error");
		}
		else
		{
			var sExplain = oResult.error_messages.join(', ');
			oValidationElement.html(sExplain);
			oValidationElement.addClass('ibo-field-validation');
			 $(me.element).find('.ibo-input-field-wrapper').addClass("is-error");
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

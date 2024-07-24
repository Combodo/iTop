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

namespace Combodo\iTop\Renderer\Bootstrap\FieldRenderer;

use AttributeDate;
use AttributeDateTime;
use AttributeText;
use Combodo\iTop\Application\Helper\CKEditorHelper;
use Combodo\iTop\Form\Field\DateField;
use Combodo\iTop\Form\Field\DateTimeField;
use Combodo\iTop\Form\Field\Field;
use Combodo\iTop\Form\Field\MultipleChoicesField;
use Combodo\iTop\Form\Field\TextAreaField;
use Combodo\iTop\Form\Validator\AbstractRegexpValidator;
use Combodo\iTop\Form\Validator\MandatoryValidator;
use Combodo\iTop\Renderer\RenderingOutput;
use Dict;
use InlineImage;
use MetaModel;
use UserRights;
use utils;

/**
 * Description of BsSimpleFieldRenderer
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 */
class BsSimpleFieldRenderer extends BsFieldRenderer
{

	/**
	 * @inheritDoc
	 */
	public function Render() {
		$oOutput = parent::Render();

		$sFieldClass = get_class($this->oField);
		$sFieldMandatoryClass = ($this->oField->GetMandatory()) ? 'form_mandatory' : '';
		$sFieldDescriptionForHTMLTag = ($this->oField->HasDescription()) ? 'data-tooltip-content="'.utils::HtmlEntities($this->oField->GetDescription()).'"' : '';

		// Prepare input validations tags
		$sInputTags = $this->ComputeInputValidationTags($this->oField);

		// Rendering field in edition mode
		if (!$this->oField->GetReadOnly() && !$this->oField->GetHidden()) {
			// HTML content
			switch ($sFieldClass) {
				case 'Combodo\\iTop\\Form\\Field\\DateTimeField':
				case 'Combodo\\iTop\\Form\\Field\\PasswordField':
				case 'Combodo\\iTop\\Form\\Field\\StringField':
				case 'Combodo\\iTop\\Form\\Field\\UrlField':
				case 'Combodo\\iTop\\Form\\Field\\EmailField':
				case 'Combodo\\iTop\\Form\\Field\\PhoneField':
				case 'Combodo\\iTop\\Form\\Field\\SelectField':
				case 'Combodo\\iTop\\Form\\Field\\MultipleSelectField':
					// Opening container
				$oOutput->AddHtml('<div class="form-group form_group_small '.$sFieldMandatoryClass.'">');

				// Label
				$oOutput->AddHtml('<div class="form_field_label">');
				if ($this->oField->GetLabel() !== '') {
					$oOutput->AddHtml('<label for="'.$this->oField->GetGlobalId().'" class="control-label" '.$sFieldDescriptionForHTMLTag.'>')->AddHtml($this->oField->GetLabel(), true)->AddHtml('</label>');
				}
				$oOutput->AddHtml('</div>');

				// Value
				$oOutput->AddHtml('<div class="form_field_control">');
				// - Help block
				$oOutput->AddHtml('<div class="help-block"></div>');

				// - Value regarding the field type
				switch ($sFieldClass) {
					case 'Combodo\\iTop\\Form\\Field\\DateTimeField':

						/* @see N°803 - Allow display & edition of attributes on n:n relations on Portal
						 * LinkedSetFieldRenderer allow modification of link attributes, the default widget positioning truncates the popup.
						 */
						$sParent = '';
						if ($this->oField->GetDateTimePickerWidgetParent() != null) {
							$sParent = ", widgetParent: '{$this->oField->GetDateTimePickerWidgetParent()}'";
						}

						$oOutput->AddHtml('<div class="input-group date" id="datepicker_'.$this->oField->GetGlobalId().'">');
						$oOutput->AddHtml('<input type="text" id="'.$this->oField->GetGlobalId().'" name="'.$this->oField->GetId().'" value="')->AddHtml($this->oField->GetDisplayValue(), true)->AddHtml('" class="form-control" maxlength="255" '.$sInputTags.'/>');
						$oOutput->AddHtml('<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>');
						$oOutput->AddHtml('</div>');
						$sJSFormat = json_encode($this->oField->GetJSDateTimeFormat());
						$sLocale = Dict::S('Portal:Calendar-FirstDayOfWeek');
						$oOutput->AddJs(
							<<<EOF
                                					$('#datepicker_{$this->oField->GetGlobalId()}').datetimepicker({format: $sJSFormat, locale: '$sLocale' $sParent});
EOF
						);
						break;

					case 'Combodo\\iTop\\Form\\Field\\PasswordField':
						$oOutput->AddHtml('<input type="password" id="'.$this->oField->GetGlobalId().'" name="'.$this->oField->GetId().'" value="')->AddHtml($this->oField->GetCurrentValue(), true)->AddHtml('" class="form-control" maxlength="255" autocomplete="off" '.$sInputTags.'/>');
						break;

					case 'Combodo\\iTop\\Form\\Field\\StringField':
					case 'Combodo\\iTop\\Form\\Field\\UrlField':
					case 'Combodo\\iTop\\Form\\Field\\EmailField':
					case 'Combodo\\iTop\\Form\\Field\\PhoneField':
						$oOutput->AddHtml('<input type="text" id="'.$this->oField->GetGlobalId().'" name="'.$this->oField->GetId().'" value="')->AddHtml($this->oField->GetCurrentValue(),
							true)->AddHtml('" class="form-control" maxlength="255" '.$sInputTags.'/>');
						break;

					case 'Combodo\\iTop\\Form\\Field\\SelectField':
					case 'Combodo\\iTop\\Form\\Field\\MultipleSelectField':
					$oOutput->AddHtml('<select id="'.$this->oField->GetGlobalId().'" name="'.$this->oField->GetId().'" '.(($this->oField->GetMultipleValuesEnabled()) ? 'multiple' : '').' class="form-control"  '.$sInputTags.'>');
						foreach ($this->oField->GetChoices() as $sChoice => $sLabel) {
							// Note : The test is a double equal on purpose as the type of the value received from the XHR is not always the same as the type of the allowed values. (eg : string vs int)
							$sSelectedAtt = ($this->oField->GetCurrentValue() == $sChoice) ? 'selected' : '';
							$oOutput->AddHtml('<option value="'.$sChoice.'" '.$sSelectedAtt.' >')->AddHtml($sLabel)->AddHtml('</option>');
						}
						$oOutput->AddHtml('</select>');
						break;
				}
					$oOutput->AddHtml('</div>');

					// Closing container
					$oOutput->AddHtml('</div>');
					break;

				case 'Combodo\\iTop\\Form\\Field\\TextAreaField':
				case 'Combodo\\iTop\\Form\\Field\\CaseLogField':
					$bRichEditor = ($this->oField->GetFormat() === TextAreaField::ENUM_FORMAT_HTML);

					// Opening container
					$oOutput->AddHtml('<div class="form-group ' . $sFieldMandatoryClass . '">');

					// Label
					$oOutput->AddHtml('<div class="form_field_label">');
				if ($this->oField->GetLabel() !== '') {
					$oOutput->AddHtml('<label for="'.$this->oField->GetGlobalId().'" class="control-label" '.$sFieldDescriptionForHTMLTag.'>')->AddHtml($this->oField->GetLabel(), true)->AddHtml('</label>');
				}
				$oOutput->AddHtml('</div>');

				// Value
				$oOutput->AddHtml('<div class="form_field_control">');
				// - Help block
				$oOutput->AddHtml('<div class="help-block"></div>');
				// First the edition area
				$oOutput->AddHtml('<div>');
				$sEditorClasses = $bRichEditor ? 'htmlEditor' : '';
				$oOutput->AddHtml('<textarea id="'.$this->oField->GetGlobalId().'" name="'.$this->oField->GetId().'" class="' . $sEditorClasses . ' form-control" rows="8"  '.$sInputTags.'>'. CKEditorHelper::PrepareCKEditorValueTextEncodingForTextarea($this->oField->GetCurrentValue()) .'</textarea>');
				$oOutput->AddHtml('</div>');
				// Then the previous entries if necessary
				if ($sFieldClass === 'Combodo\\iTop\\Form\\Field\\CaseLogField') {
					$this->PreparingCaseLogEntries($oOutput);
				}
				$oOutput->AddHtml('</div>');

				// Closing container
				$oOutput->AddHtml('</div>');

				// Some additional stuff if we are displaying it with a rich editor
					if ($bRichEditor) {

						// Enable CKEditor
						CKEditorHelper::ConfigureCKEditorElementForRenderingOutput($oOutput, $this->oField->GetGlobalId(), $this->oField->GetCurrentValue(), false, false, ['maximize' => []]);

						if (($this->oField->GetObject() !== null) && ($this->oField->GetTransactionId() !== null)) {
							$oOutput->AddJs(InlineImage::EnableCKEditorImageUpload($this->oField->GetObject(), utils::GetUploadTempId($this->oField->GetTransactionId())));
						}
					}
					break;

				case 'Combodo\\iTop\\Form\\Field\\RadioField':
				case 'Combodo\\iTop\\Form\\Field\\CheckboxField':
					$sFieldType = ($sFieldClass === 'Combodo\\iTop\\Form\\Field\\RadioField') ? 'radio' : 'checkbox';

					// Opening container
					$oOutput->AddHtml('<div class="form-group ' . $sFieldMandatoryClass . '" id="' . $this->oField->GetGlobalId() . '">');

					// Label
					$oOutput->AddHtml('<div class="form_field_label">');
					if ($this->oField->GetLabel() !== '') {
						$oOutput->AddHtml('<div><label class="control-label" '.$sFieldDescriptionForHTMLTag.'>')->AddHtml($this->oField->GetLabel(), true)->AddHtml('</label></div>');
					}
					$oOutput->AddHtml('</div>');

					// Value
					$oOutput->AddHtml('<div class="form_field_control">');
					// - Help block
					$oOutput->AddHtml('<div class="help-block"></div>');
					$oOutput->AddHtml('<div class="btn-group" data-toggle="buttons">');
					$i = 0;
					foreach ($this->oField->GetChoices() as $sChoice => $sLabel) {
						// Note : The test is a double equal on purpose as the type of the value received from the XHR is not always the same as the type of the allowed values. (eg : string vs int)
						$sCheckedAtt = ($this->oField->IsAmongValues($sChoice)) ? 'checked' : '';
						$sCheckedClass = ($this->oField->IsAmongValues($sChoice)) ? 'active' : '';
						$oOutput->AddHtml('<label class="btn btn-default ' . $sCheckedClass . '"><input type="' . $sFieldType . '" name="' . $this->oField->GetId() . '" id="' . $this->oField->GetId() . $i . '" value="' . $sChoice . '" ' . $sCheckedAtt . ' />' . $sLabel . '</label>');
						$i++;
					}
					$oOutput->AddHtml('</div>');
					$oOutput->AddHtml('</div>');

					// Closing container
					$oOutput->AddHtml('</div>');
					break;

				case 'Combodo\\iTop\\Form\\Field\\HiddenField':
					$oOutput->AddHtml('<input type="hidden" id="' . $this->oField->GetGlobalId() . '" name="' . $this->oField->GetId() . '" value="')->AddHtml($this->oField->GetCurrentValue(), true)->AddHtml('"/>');
					break;
			}

			// JS FieldChange trigger (:input are not always at the same depth)
			switch ($sFieldClass) {
				case 'Combodo\\iTop\\Form\\Field\\PasswordField':
				case 'Combodo\\iTop\\Form\\Field\\StringField':
				case 'Combodo\\iTop\\Form\\Field\\UrlField':
				case 'Combodo\\iTop\\Form\\Field\\EmailField':
				case 'Combodo\\iTop\\Form\\Field\\PhoneField':
				case 'Combodo\\iTop\\Form\\Field\\SelectField':
				case 'Combodo\\iTop\\Form\\Field\\MultipleSelectField':
				case 'Combodo\\iTop\\Form\\Field\\HiddenField':
					$oOutput->AddJs(<<<JS
	                    $("#{$this->oField->GetGlobalId()}").off("change keyup").on("change keyup", function(){
							var me = this;
	
							$(this).closest(".field_set").trigger("field_change", {
								id: $(me).attr("id"),
								name: $(me).closest(".form_field").attr("data-field-id"),
								value: $(me).val()
							});
						}).on("mouseup", function(){this.focus();});
JS
					);
					break;

				case 'Combodo\\iTop\\Form\\Field\\TextAreaField':
				case 'Combodo\\iTop\\Form\\Field\\CaseLogField':
					if ($this->oField->GetFormat() === TextAreaField::ENUM_FORMAT_HTML) {
						$oOutput->AddJs(<<<JS
							CombodoCKEditorHandler.GetInstance("#{$this->oField->GetGlobalId()}")
								.then((oCKEditor) => {
									oCKEditor.model.document.on("change:data", () => {
										console.log("desc changed!");
										const oFieldElem = $("#{$this->oField->GetGlobalId()}");
										oFieldElem.closest(".field_set").trigger("field_change", {
											id: oFieldElem.attr("id"),
											name: oFieldElem.closest(".form_field").attr("data-field-id"),
											value: oCKEditor.getData()
										});
									});
								});
JS
						);
					} else {
						$oOutput->AddJs(<<<JS
                            $("#{$this->oField->GetGlobalId()}").off("change keyup").on("change keyup", function(){
								var me = this;
		
								$(this).closest(".field_set").trigger("field_change", {
									id: $(me).attr("id"),
									name: $(me).closest(".form_field").attr("data-field-id"),
									value: $(me).val()
								});
							}).on("mouseup", function(){this.focus();});
JS
						);
					}
					break;

				case 'Combodo\\iTop\\Form\\Field\\DateTimeField':
					// We need the focusout event has the datepicker widget seems to override the change event
					$oOutput->AddJs(
						<<<EOF
                        					$("#{$this->oField->GetGlobalId()}").off("change keyup focusout").on("change keyup focusout", function(){
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

				case 'Combodo\\iTop\\Form\\Field\\RadioField':
				case 'Combodo\\iTop\\Form\\Field\\CheckboxField':
					$oOutput->AddJs(
						<<<EOF
                        					$("#{$this->oField->GetGlobalId()} input").off("change").on("change", function(){
						var me = this;

						$(this).closest(".field_set").trigger("field_change", {
							id: $(me).closest("#{$this->oField->GetGlobalId()}").attr("id"),
							name: $(me).attr("name"),
							value: $(me).val()
						});
					});
EOF
					);
					break;
			}
		}
		// ... and in read-only mode (or hidden)
		else {
			// ... specific rendering for fields with multiple values
			if (($this->oField instanceof MultipleChoicesField) && ($this->oField->GetMultipleValuesEnabled())) {
				// TODO
			}
			// ... classic rendering for fields with only one value
			else {
				switch ($sFieldClass) {
					case 'Combodo\\iTop\\Form\\Field\\LabelField':
					case 'Combodo\\iTop\\Form\\Field\\StringField':
					case 'Combodo\\iTop\\Form\\Field\\UrlField':
					case 'Combodo\\iTop\\Form\\Field\\EmailField':
					case 'Combodo\\iTop\\Form\\Field\\PhoneField':
					case 'Combodo\\iTop\\Form\\Field\\DateTimeField':
					case 'Combodo\\iTop\\Form\\Field\\DurationField':
						// Opening container
						$oOutput->AddHtml('<div class="form-group form_group_small">');

						// Showing label / value only if read-only but not hidden
						if (!$this->oField->GetHidden()) {
							// Label
							$oOutput->AddHtml('<div class="form_field_label">');
							if ($this->oField->GetLabel() !== '') {
								$oOutput->AddHtml('<label for="' . $this->oField->GetGlobalId() . '" class="control-label" '.$sFieldDescriptionForHTMLTag.'>')->AddHtml($this->oField->GetLabel(), true)->AddHtml('</label>');
							}
							$oOutput->AddHtml('</div>');

							// Value
							$bEncodeHtmlEntities = ( in_array($sFieldClass, array('Combodo\\iTop\\Form\\Field\\UrlField', 'Combodo\\iTop\\Form\\Field\\EmailField', 'Combodo\\iTop\\Form\\Field\\PhoneField')) ) ? false : true;
							$oOutput->AddHtml('<div class="form_field_control">');
							$oOutput->AddHtml('<div class="form-control-static">')->AddHtml($this->oField->GetDisplayValue(), $bEncodeHtmlEntities)->AddHtml('</div>');
							$oOutput->AddHtml('</div>');
						}

						// Adding hidden input if not a label
						if($sFieldClass !== 'Combodo\\iTop\\Form\\Field\\LabelField') {
							$sValueForInput = ($sFieldClass === 'Combodo\\iTop\\Form\\Field\\DateTimeField') ? $this->oField->GetDisplayValue() : $this->oField->GetCurrentValue();
							$oOutput->AddHtml('<input type="hidden" id="' . $this->oField->GetGlobalId() . '" name="' . $this->oField->GetId() . '" value="')->AddHtml($sValueForInput, true)->AddHtml('" class="form-control" />');
						}

						// Closing container
						$oOutput->AddHtml('</div>');
						break;

					case 'Combodo\\iTop\\Form\\Field\\TextAreaField':
						// Opening container
						$oOutput->AddHtml('<div class="form-group">');

						// Showing label / value only if read-only but not hidden
						if (!$this->oField->GetHidden()) {
							// Label
							$oOutput->AddHtml('<div class="form_field_label">');
							if ($this->oField->GetLabel() !== '') {
								$oOutput->AddHtml('<label for="' . $this->oField->GetGlobalId() . '" class="control-label" '.$sFieldDescriptionForHTMLTag.'>')->AddHtml($this->oField->GetLabel(), true)->AddHtml('</label>');
							}
							$oOutput->AddHtml('</div>');

							// Value
							$oOutput->AddHtml('<div class="form_field_control">');
							$oOutput->AddHtml('<div class="form-control-static ipb-is-html-content">')->AddHtml($this->oField->GetDisplayValue(), false)->AddHtml('</div>');
							$oOutput->AddHtml('</div>');
						}

						// Adding hidden input
						$oOutput->AddHtml('<input type="hidden" id="' . $this->oField->GetGlobalId() . '" name="' . $this->oField->GetId() . '" value="')->AddHtml($this->oField->GetCurrentValue(), true)->AddHtml('" class="form-control" />');

						// Closing container
						$oOutput->AddHtml('</div>');
						break;

					case 'Combodo\\iTop\\Form\\Field\\CaseLogField':
						// Opening container
						$oOutput->AddHtml('<div class="form-group ' . $sFieldMandatoryClass . '">');

						// Label
						$oOutput->AddHtml('<div class="form_field_label">');
						if ($this->oField->GetLabel() !== '') {
							$oOutput->AddHtml('<label for="' . $this->oField->GetGlobalId() . '" class="control-label" '.$sFieldDescriptionForHTMLTag.'>')->AddHtml($this->oField->GetLabel(), true)->AddHtml('</label>');
						}
						$oOutput->AddHtml('</div>');

						// Value
						$oOutput->AddHtml('<div class="form_field_control">');
						// - Entries if necessary
						$this->PreparingCaseLogEntries($oOutput);
						$oOutput->AddHtml('</div>');

						// Closing container
						$oOutput->AddHtml('</div>');
						break;

					case 'Combodo\\iTop\\Form\\Field\\BlobField':
					case 'Combodo\\iTop\\Form\\Field\\ImageField':
						// Opening container
						$oOutput->AddHtml('<div class="form-group">');

						// Showing label / value only if read-only but not hidden
						if (!$this->oField->GetHidden()) {
							// Label
							$oOutput->AddHtml('<div class="form_field_label">');
							if ($this->oField->GetLabel() !== '') {
								$oOutput->AddHtml('<label for="' . $this->oField->GetGlobalId() . '" class="control-label" '.$sFieldDescriptionForHTMLTag.'>')->AddHtml($this->oField->GetLabel(), true)->AddHtml('</label>');
							}
							$oOutput->AddHtml('</div>');

							// Value
							$oOutput->AddHtml('<div class="form_field_control">');
							$oOutput->AddHtml('<div class="form-control-static">');
							if($sFieldClass === 'Combodo\\iTop\\Form\\Field\\ImageField') {
								$oOutput->AddHtml('<img src="' . $this->oField->GetDisplayUrl() . '" />', false);
							}
							else {
								$oOutput->AddHtml($this->oField->GetDisplayValue(), false);
							}
							$oOutput->AddHtml('</div>');
							$oOutput->AddHtml('</div>');
						}
						$oOutput->AddHtml('<input type="hidden" id="' . $this->oField->GetGlobalId() . '" name="' . $this->oField->GetId() . '" value="')->AddHtml($this->oField->GetCurrentValue(), true)->AddHtml('" class="form-control" />');

						// Closing container
						$oOutput->AddHtml('</div>');
						break;

					case 'Combodo\\iTop\\Form\\Field\\RadioField':
					case 'Combodo\\iTop\\Form\\Field\\SelectField':
					case 'Combodo\\iTop\\Form\\Field\\MultipleSelectField':
						$aFieldChoices = $this->oField->GetChoices();
						$sFieldValue = (isset($aFieldChoices[$this->oField->GetCurrentValue()])) ? $aFieldChoices[$this->oField->GetCurrentValue()] : Dict::S('UI:UndefinedObject');

						// Opening container
						$oOutput->AddHtml('<div class="form-group form_group_small">');

						// Showing label / value only if read-only but not hidden
						if (!$this->oField->GetHidden()) {
							// Label
							$oOutput->AddHtml('<div class="form_field_label">');
							if ($this->oField->GetLabel() !== '') {
								$oOutput->AddHtml('<label for="' . $this->oField->GetGlobalId() . '" class="control-label" '.$sFieldDescriptionForHTMLTag.'>')->AddHtml($this->oField->GetLabel(), true)->AddHtml('</label>');
							}
							$oOutput->AddHtml('</div>');

							// Value
							$oOutput->AddHtml('<div class="form_field_control">');
							$oOutput->AddHtml('<div class="form-control-static">' . $sFieldValue . '</div>');
							$oOutput->AddHtml('</div>');
						}

						// Adding hidden value
						$oOutput->AddHtml('<input type="hidden" id="' . $this->oField->GetGlobalId() . '" name="' . $this->oField->GetId() . '" value="' . $this->oField->GetCurrentValue() . '" class="form-control" />');

						// Closing container
						$oOutput->AddHtml('</div>');
						break;

					case 'Combodo\\iTop\\Form\\Field\\HiddenField':
						$oOutput->AddHtml('<input type="hidden" id="' . $this->oField->GetGlobalId() . '" name="' . $this->oField->GetId() . '" value="')->AddHtml($this->oField->GetCurrentValue(), true)->AddHtml('"/>');
						break;
				}
			}
		}

		// Attaching JS widget only if field is hidden or NOT read only
		if($this->oField->GetHidden() || !$this->oField->GetReadOnly()) {

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

			$sFormFieldOptions = json_encode(array(
				'validators' => $aValidators
			));

			switch ($sFieldClass) {
				case 'Combodo\\iTop\\Form\\Field\\PasswordField':
				case 'Combodo\\iTop\\Form\\Field\\StringField':
				case 'Combodo\\iTop\\Form\\Field\\UrlField':
				case 'Combodo\\iTop\\Form\\Field\\EmailField':
				case 'Combodo\\iTop\\Form\\Field\\PhoneField':
				case 'Combodo\\iTop\\Form\\Field\\SelectField':
				case 'Combodo\\iTop\\Form\\Field\\MultipleSelectField':
				case 'Combodo\\iTop\\Form\\Field\\HiddenField':
				case 'Combodo\\iTop\\Form\\Field\\RadioField':
				case 'Combodo\\iTop\\Form\\Field\\CheckboxField':
				case 'Combodo\\iTop\\Form\\Field\\DateTimeField':
					$oOutput->AddJs(
						<<<EOF
    					$("[data-field-id='{$this->oField->GetId()}'][data-form-path='{$this->oField->GetFormPath()}']").portal_form_field($sFormFieldOptions);
EOF
					);
					break;
				case 'Combodo\\iTop\\Form\\Field\\TextAreaField':
				case 'Combodo\\iTop\\Form\\Field\\CaseLogField':
					$bRichEditor = ($this->oField->GetFormat() === TextAreaField::ENUM_FORMAT_HTML);
					if($bRichEditor) {
						// Overloading $sFormFieldOptions to include the set_current_value_callback. It would have been nicer to refactor the variable for all field types, but as this is a fix for a maintenance release, we rather be safe.
						$sValidators = json_encode($aValidators);
						$oOutput->AddJs(
							<<<EOF
                            $("[data-field-id='{$this->oField->GetId()}'][data-form-path='{$this->oField->GetFormPath()}']").portal_form_field_html({
                            validators: $sValidators,
                            set_current_value_callback: function(me, oEvent, oData){ $(me.element).find('textarea').val(oData); }
                        });
EOF
						);
					}
					else {
						$oOutput->AddJs(
							<<<EOF
        					$("[data-field-id='{$this->oField->GetId()}'][data-form-path='{$this->oField->GetFormPath()}']").portal_form_field($sFormFieldOptions);
EOF
						);
					}
					break;
			}
		}

		// Finally, no matter the field mode
		switch ($sFieldClass) {
			case 'Combodo\\iTop\\Form\\Field\\TextAreaField':
			case 'Combodo\\iTop\\Form\\Field\\CaseLogField':
				$bRichEditor = ($this->oField->GetFormat() === TextAreaField::ENUM_FORMAT_HTML);
				if($bRichEditor) {
					// MagnificPopup on images
					$oOutput->AddJs(InlineImage::FixImagesWidth());
					// Trigger highlighter for all code blocks in this caselog
					$oOutput->AddJs(<<<JS
$("[data-field-id='{$this->oField->GetId()}'][data-form-path='{$this->oField->GetFormPath()}'] pre").each(function(i, block) {
    hljs.highlightBlock(block);
});
JS
					);
				}
				break;
		}

		return $oOutput;
	}

	/**
	 * Note: Since 3.0.0 this is highly inspired from an extension of the community (see https://github.com/Molkobain/itop-bubble-caselogs)
	 *
	 * @param RenderingOutput $oOutput
	 *
	 * @throws \Exception
	 */
	protected function PreparingCaseLogEntries(RenderingOutput &$oOutput) {
		$aEntries = $this->oField->GetEntries();
		$iNbEntries = count($aEntries);

		if ($iNbEntries > 0) {
			// Dict entries
			$sOpenAllEntriesTooltip = utils::HtmlEntities(Dict::S('UI:Layout:ActivityPanel:Tab:Toolbar:Action:OpenAll:Tooltip'));
			$sCloseAllEntriesTooltip = utils::HtmlEntities(Dict::S('UI:Layout:ActivityPanel:Tab:Toolbar:Action:CloseAll:Tooltip'));
			$sUsersCountTooltip = utils::HtmlEntities(Dict::S('UI:Layout:ActivityPanel:Tab:Toolbar:Info:AuthorsCount:Tooltip'));
			$sEntriesCountTooltip = utils::HtmlEntities(Dict::S('UI:Layout:ActivityPanel:Tab:Toolbar:Info:MessagesCount:Tooltip'));
			$sCloseEntryTooltip = utils::HtmlEntities(Dict::S('Portal:Form:Caselog:Entry:Close:Tooltip'));

			// First pass to retrieve number of users
			$aUserIds = array();
			for ($i = 0; $i < $iNbEntries; $i++) {
				$iEntryUserId = $aEntries[$i]['user_id'];
				if (!in_array($iEntryUserId, $aUserIds)) {
					$aUserIds[] = $iEntryUserId;
				}
			}
			$iNbUsers = count($aUserIds);

			// Opening thread
			$oOutput->AddHtml(<<<HTML
<div class="caselog-thread ipb-is-html-content">
HTML
			);
			// - Header
			$oOutput->AddHtml(<<<HTML
    <div class="caselog-thread--header">
        <span class="caselog-thread--header-togglers">
            <a href="#" class="caselog-thread--header-toggler caselog-thread--open-all-toggler" data-tooltip-content="{$sOpenAllEntriesTooltip}"><span class="fas fa-book-open"></span></a>
            <a href="#" class="caselog-thread--header-toggler caselog-thread--close-all-toggler" data-tooltip-content="{$sCloseAllEntriesTooltip}"><span class="fas fa-book"></span></a>
        </span>
        <span class="caselog-thread--header-info pull-right">
	        <span class="caselog-thread--participants-count" data-tooltip-content="{$sUsersCountTooltip}">{$iNbUsers}<span class="fas fa-users"></span></span>
	        <span class="caselog-thread--messages-count" data-tooltip-content="{$sEntriesCountTooltip}">{$iNbEntries}<span class="fas fa-comment-alt"></span></span>
		</span>
    </div>
HTML
			);
			// - Content
			$oOutput->AddHtml(<<<HTML
	<div class="caselog-thread--content">
HTML
			);

			$sThreadUniqueId = uniqid();
			$sLastDate = null;
			$sLastUserId = null;
			$iLastLoopIndex = $iNbEntries - 1;

			// Caching profile picture url as it is resource consuming
			$aContactPicturesCache = array();
			$aPeerColorClassCache = array();
			// Note: Yes, the config. param. is named after the backoffice element but we hope that we will "soon" have some kind of "light" activity panel in the portal too, so we keep this name.
			$bHideContactPicture = false;
			if (defined('PORTAL_ID'))
			{
				$bHideContactPicture= in_array(PORTAL_ID, utils::GetConfig()->Get('activity_panel.hide_avatars'));
			}
			// Current user
			$iCurrentUserId = UserRights::GetUserId();

			for ($i = 0; $i < $iNbEntries; $i++) {
				$sEntryDatetime = AttributeDateTime::GetFormat()->Format($aEntries[$i]['date']);
				$sEntryDate = AttributeDate::GetFormat()->Format($aEntries[$i]['date']);

				$sEntryUserLogin = $aEntries[$i]['user_login'];
				$iEntryUserId = $aEntries[$i]['user_id'];
				// - Friendlyname
				if (false === empty($iEntryUserId)) {
					$oEntryUser = MetaModel::GetObject('User', $iEntryUserId, false /* Necessary in case user has been deleted */, true);
					if(!is_null($oEntryUser)) {
						$sEntryUserLogin = UserRights::GetUserFriendlyName($oEntryUser->Get('login'));
					}

					// Retrieve (and cache) profile picture if available (standard datamodel)
					// Note: Here the cache is more about nor retrieving the User object several times rather than computing the picture URL
					if (!array_key_exists($iEntryUserId, $aContactPicturesCache)) {
						// First, check if we should display the picture
						if ($bHideContactPicture === true) {
							$sEntryContactPictureAbsoluteUrl = null;
						}
						// Otherwise try to retrieve one for the current contact
						else {
							if(is_null($oEntryUser)) {
								$sEntryContactPictureAbsoluteUrl = null;
							}
							else {
								$sEntryContactPictureAbsoluteUrl = UserRights::GetUserPictureAbsUrl($oEntryUser->Get('login'), false);
							}
						}

						$aContactPicturesCache[$iEntryUserId] = $sEntryContactPictureAbsoluteUrl;
					}
				}

				// Open user block if previous user was different or if previous date was different
				if (($iEntryUserId !== $sLastUserId) || ($sEntryDate !== $sLastDate)) {
					if ($sEntryDate !== $sLastDate) {
						$oOutput->AddHtml(<<<HTML
		<div class="caselog-thread--date">{$sEntryDate}</div>
HTML
						);
					}

					// Open block
					if ($iEntryUserId === $iCurrentUserId) {
						$sEntryBlockClass = 'caselog-thread--block-me';
					}
					else {
						if (!array_key_exists($iEntryUserId, $aPeerColorClassCache)) {
							$iPeerClassNumber = (count($aPeerColorClassCache) % 5) + 1;
							$aPeerColorClassCache[$iEntryUserId] = 'caselog-thread--block-color-'.$iPeerClassNumber;
						}
						$sEntryBlockClass = $aPeerColorClassCache[$iEntryUserId];
					}
					$oOutput->AddHtml(<<<HTML
		<div class="caselog-thread--block {$sEntryBlockClass}">
HTML
					);

					// Open medallion from profile picture or first name letter
					$bEntryHasMedallionPicture = (empty($aContactPicturesCache[$iEntryUserId]) === false);
					$sEntryMedallionStyle = $bEntryHasMedallionPicture ? ' background-image: url(\''.$aContactPicturesCache[$iEntryUserId].'\');' : '';
					$sEntryMedallionContent = $bEntryHasMedallionPicture ? '' : utils::FormatInitialsForMedallion(UserRights::GetUserInitials($sEntryUserLogin));
					// - Entry tooltip
					$sEntryMedallionTooltip = utils::HtmlEntities($sEntryUserLogin);
					$sEntryMedallionTooltipPlacement = ($iEntryUserId === $iCurrentUserId) ? 'left' : 'right';
					$oOutput->AddHtml(<<<HTML
	    <div class="caselog-thread--block-medallion" style="{$sEntryMedallionStyle}" data-tooltip-content="{$sEntryMedallionTooltip}" data-placement="{$sEntryMedallionTooltipPlacement}">
	        $sEntryMedallionContent
	    </div>
	    <div class="caselog-thread--block-user">{$sEntryMedallionTooltip}</div>
HTML
					);

					// Open entries
					$oOutput->AddHtml(<<<HTML
			<div class="caselog-thread--block-entries">
HTML
					);
				}

				// Prepare entry content
				$sEntryId = 'caselog-thread--block-entry-'.$sThreadUniqueId.'-'.$i;
				$sEntryHtml = AttributeText::RenderWikiHtml($aEntries[$i]['message_html'], true /* wiki only */);
				$sEntryHtml = InlineImage::FixUrls($sEntryHtml);

				// Add entry
				$oOutput->AddHtml(<<<HTML
			    <div class="caselog-thread--block-entry" id="{$sEntryId}">
			        <div class="caselog-thread--block-entry-content">{$sEntryHtml}</div>
			        <div class="caselog-thread--block-entry-date">{$sEntryDatetime}</div>
			        <div class="caselog-thread--block-entry-toggler"><span class="fas fa-caret-up" title="{$sCloseEntryTooltip}"></span></div>
			    </div>
HTML
				);

				// Close user block if next user is different or if last entry or if next entry is for another date
				if (($i === $iLastLoopIndex)
					|| ($i < $iLastLoopIndex && $iEntryUserId !== $aEntries[$i + 1]['user_id'])
					|| ($i < $iLastLoopIndex && $sEntryDate !== AttributeDate::GetFormat()->Format($aEntries[$i + 1]['date']))) {
					// Close entries and block
					$oOutput->AddHtml(<<<HTML
			</div>
		</div>
HTML
					);
				}

				// Update current loop informations
				$sLastDate = $sEntryDate;
				$sLastUserId = $iEntryUserId;
			}

			// Close thread content and thread
			$oOutput->AddHtml(<<<HTML
	</div>
</div>
HTML
			);

			// Add JS handlers
			$oOutput->AddJs(<<<JS
$('[data-field-id="{$this->oField->GetId()}"][data-form-path="{$this->oField->GetFormPath()}"]')
	.on('click', '.caselog-thread--block-entry-toggler, .caselog-thread--block-entry.closed', function(){
		$(this).closest('.caselog-thread--block-entry').toggleClass('closed');
	})
	.on('click', '.caselog-thread--open-all-toggler', function(oEvent){
		oEvent.preventDefault()
		$('[data-field-id="{$this->oField->GetId()}"][data-form-path="{$this->oField->GetFormPath()}"]').find('.caselog-thread--block-entry').removeClass('closed');
	})
	.on('click', '.caselog-thread--close-all-toggler', function(oEvent){
		oEvent.preventDefault()
		$('[data-field-id="{$this->oField->GetId()}"][data-form-path="{$this->oField->GetFormPath()}"]').find('.caselog-thread--block-entry').addClass('closed');
	});
JS
			);
		}
	}

	/**
	 * @param \Combodo\iTop\Form\Field\Field $oField
	 *
	 * @return string
	 */
	private function ComputeInputValidationTags(Field $oField): string
	{
		// Result tags
		$sTags = '';

		// Iterate throw validators...
		foreach ($oField->GetValidators() as $oValidator) {

			// Validator
			if ($oValidator instanceof AbstractRegexpValidator) {
				if (!($oField instanceof DateField || $oField instanceof DateTimeField)) { // unrecognized regular expression
					$sTags .= ' pattern="'.$oValidator->GetRegExp().'" ';
				}
			}

			// Mandatory validator
			if ($oValidator instanceof MandatoryValidator) {
				$sTags .= ' required ';
			}

		}

		return $sTags;
	}

}

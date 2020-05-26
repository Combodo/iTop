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

namespace Combodo\iTop\Renderer\Bootstrap\FieldRenderer;

use utils;
use Dict;
use UserRights;
use AttributeDateTime;
use AttributeText;
use InlineImage;
use Combodo\iTop\Renderer\RenderingOutput;
use Combodo\iTop\Form\Field\TextAreaField;
use Combodo\iTop\Form\Field\MultipleChoicesField;

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
	public function Render()
	{
		$oOutput = parent::Render();

		$sFieldClass = get_class($this->oField);
		$sFieldMandatoryClass = ($this->oField->GetMandatory()) ? 'form_mandatory' : '';
		
		// Rendering field in edition mode
		if (!$this->oField->GetReadOnly() && !$this->oField->GetHidden())
		{
		    // HTML content
			switch ($sFieldClass)
			{
                case 'Combodo\\iTop\\Form\\Field\\DateTimeField':
                case 'Combodo\\iTop\\Form\\Field\\PasswordField':
                case 'Combodo\\iTop\\Form\\Field\\StringField':
                case 'Combodo\\iTop\\Form\\Field\\UrlField':
                case 'Combodo\\iTop\\Form\\Field\\EmailField':
                case 'Combodo\\iTop\\Form\\Field\\PhoneField':
                case 'Combodo\\iTop\\Form\\Field\\SelectField':
                case 'Combodo\\iTop\\Form\\Field\\MultipleSelectField':
                    // Opening container
                    $oOutput->AddHtml('<div class="form-group form_group_small ' . $sFieldMandatoryClass . '">');

                    // Label
                    $oOutput->AddHtml('<div class="form_field_label">');
                    if ($this->oField->GetLabel() !== '')
                    {
                        $oOutput->AddHtml('<label for="' . $this->oField->GetGlobalId() . '" class="control-label">')->AddHtml($this->oField->GetLabel(), true)->AddHtml('</label>');
                    }
                    $oOutput->AddHtml('</div>');

                    // Value
                    $oOutput->AddHtml('<div class="form_field_control">');
                    // - Help block
                    $oOutput->AddHtml('<div class="help-block"></div>');
                    // - Value regarding the field type
                    switch($sFieldClass)
                    {
                        case 'Combodo\\iTop\\Form\\Field\\DateTimeField':
                            $oOutput->AddHtml('<div class="input-group date" id="datepicker_' . $this->oField->GetGlobalId() . '">');
                            $oOutput->AddHtml('<input type="text" id="' . $this->oField->GetGlobalId() . '" name="' . $this->oField->GetId() . '" value="')->AddHtml($this->oField->GetDisplayValue(), true)->AddHtml('" class="form-control" maxlength="255" />');
                            $oOutput->AddHtml('<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>');
                            $oOutput->AddHtml('</div>');
                            $sJSFormat = json_encode($this->oField->GetJSDateTimeFormat());
                            $sLocale = Dict::S('Portal:Calendar-FirstDayOfWeek');
                            $oOutput->AddJs(
                                <<<EOF
                                					$('#datepicker_{$this->oField->GetGlobalId()}').datetimepicker({format: $sJSFormat, locale: '$sLocale'});
EOF
                            );
                            break;

                        case 'Combodo\\iTop\\Form\\Field\\PasswordField':
                            $oOutput->AddHtml('<input type="password" id="' . $this->oField->GetGlobalId() . '" name="' . $this->oField->GetId() . '" value="')->AddHtml($this->oField->GetCurrentValue(), true)->AddHtml('" class="form-control" maxlength="255" autocomplete="off" />');
                            break;

                        case 'Combodo\\iTop\\Form\\Field\\StringField':
                        case 'Combodo\\iTop\\Form\\Field\\UrlField':
                        case 'Combodo\\iTop\\Form\\Field\\EmailField':
                        case 'Combodo\\iTop\\Form\\Field\\PhoneField':
                            $oOutput->AddHtml('<input type="text" id="' . $this->oField->GetGlobalId() . '" name="' . $this->oField->GetId() . '" value="')->AddHtml($this->oField->GetCurrentValue(), true)->AddHtml('" class="form-control" maxlength="255" />');
                            break;

                        case 'Combodo\\iTop\\Form\\Field\\SelectField':
                        case 'Combodo\\iTop\\Form\\Field\\MultipleSelectField':
                            $oOutput->AddHtml('<select id="' . $this->oField->GetGlobalId() . '" name="' . $this->oField->GetId() . '" ' . ( ($this->oField->GetMultipleValuesEnabled()) ? 'multiple' : '' ) . ' class="form-control">');
                            foreach ($this->oField->GetChoices() as $sChoice => $sLabel)
                            {
                                // Note : The test is a double equal on purpose as the type of the value received from the XHR is not always the same as the type of the allowed values. (eg : string vs int)
                                $sSelectedAtt = ($this->oField->GetCurrentValue() == $sChoice) ? 'selected' : '';
                                $oOutput->AddHtml('<option value="' . $sChoice . '" ' . $sSelectedAtt . ' >')->AddHtml($sLabel)->AddHtml('</option>');
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
					if ($this->oField->GetLabel() !== '')
					{
						$oOutput->AddHtml('<label for="' . $this->oField->GetGlobalId() . '" class="control-label">')->AddHtml($this->oField->GetLabel(), true)->AddHtml('</label>');
					}
					$oOutput->AddHtml('</div>');

					// Value
                    $oOutput->AddHtml('<div class="form_field_control">');
                    // - Help block
					$oOutput->AddHtml('<div class="help-block"></div>');
					// First the edition area
					$oOutput->AddHtml('<div>');
					$oOutput->AddHtml('<textarea id="' . $this->oField->GetGlobalId() . '" name="' . $this->oField->GetId() . '" class="form-control" rows="8">' . $this->oField->GetCurrentValue() . '</textarea>');
					$oOutput->AddHtml('</div>');
					// Then the previous entries if necessary
					if ($sFieldClass === 'Combodo\\iTop\\Form\\Field\\CaseLogField')
					{
						$this->PreparingCaseLogEntries($oOutput);
						// Trigger highlighter for all code blocks in this caselog
						$oOutput->AddJs(
							<<<JS
   $("[data-field-id='{$this->oField->GetId()}'][data-form-path='{$this->oField->GetFormPath()}'] .caselog_field_entry_content > pre").each(function(i, block) {
            hljs.highlightBlock(block);
        });
JS
						);
					}
					$oOutput->AddHtml('</div>');

					// Closing container
					$oOutput->AddHtml('</div>');

					// Some additional stuff if we are displaying it with a rich editor
					if ($bRichEditor)
					{
						$sEditorLanguage = strtolower(trim(UserRights::GetUserLanguage()));
						$oOutput->AddJs(
<<<EOF
							$('#{$this->oField->GetGlobalId()}').addClass('htmlEditor');
							$('#{$this->oField->GetGlobalId()}').ckeditor(function(){}, {language: '$sEditorLanguage', contentsLanguage: '$sEditorLanguage', extraPlugins: 'codesnippet'}).editor.on("change", function(){
                                	$('#{$this->oField->GetGlobalId()}').trigger("change");
                              });
EOF
						);
						if (($this->oField->GetObject() !== null) && ($this->oField->GetTransactionId() !== null))
						{
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
					if ($this->oField->GetLabel() !== '')
					{
						$oOutput->AddHtml('<div><label class="control-label">')->AddHtml($this->oField->GetLabel(), true)->AddHtml('</label></div>');
					}
					$oOutput->AddHtml('</div>');

					// Value
                    $oOutput->AddHtml('<div class="form_field_control">');
                    // - Help block
					$oOutput->AddHtml('<div class="help-block"></div>');
					$oOutput->AddHtml('<div class="btn-group" data-toggle="buttons">');
					$i = 0;
					foreach ($this->oField->GetChoices() as $sChoice => $sLabel)
					{
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
            switch ($sFieldClass)
            {
                case 'Combodo\\iTop\\Form\\Field\\PasswordField':
                case 'Combodo\\iTop\\Form\\Field\\StringField':
                case 'Combodo\\iTop\\Form\\Field\\UrlField':
                case 'Combodo\\iTop\\Form\\Field\\EmailField':
                case 'Combodo\\iTop\\Form\\Field\\PhoneField':
                case 'Combodo\\iTop\\Form\\Field\\TextAreaField':
                case 'Combodo\\iTop\\Form\\Field\\CaseLogField':
                case 'Combodo\\iTop\\Form\\Field\\SelectField':
                case 'Combodo\\iTop\\Form\\Field\\MultipleSelectField':
                case 'Combodo\\iTop\\Form\\Field\\HiddenField':
                    $oOutput->AddJs(
                        <<<EOF
                        					$("#{$this->oField->GetGlobalId()}").off("change keyup").on("change keyup", function(){
						var me = this;

						$(this).closest(".field_set").trigger("field_change", {
							id: $(me).attr("id"),
							name: $(me).closest(".form_field").attr("data-field-id"),
							value: $(me).val()
						});
					}).on("mouseup", function(){this.focus();});
EOF
                    );
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
		else
		{
			// ... specific rendering for fields with multiple values
			if (($this->oField instanceof MultipleChoicesField) && ($this->oField->GetMultipleValuesEnabled()))
			{
				// TODO
			}
			// ... classic rendering for fields with only one value
			else
			{
				switch ($sFieldClass)
				{
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
						if (!$this->oField->GetHidden())
						{
						    // Label
                            $oOutput->AddHtml('<div class="form_field_label">');
							if ($this->oField->GetLabel() !== '')
							{
								$oOutput->AddHtml('<label for="' . $this->oField->GetGlobalId() . '" class="control-label">')->AddHtml($this->oField->GetLabel(), true)->AddHtml('</label>');
							}
                            $oOutput->AddHtml('</div>');

                            // Value
                            $bEncodeHtmlEntities = ( in_array($sFieldClass, array('Combodo\\iTop\\Form\\Field\\UrlField', 'Combodo\\iTop\\Form\\Field\\EmailField', 'Combodo\\iTop\\Form\\Field\\PhoneField')) ) ? false : true;
                            $oOutput->AddHtml('<div class="form_field_control">');
							$oOutput->AddHtml('<div class="form-control-static">')->AddHtml($this->oField->GetDisplayValue(), $bEncodeHtmlEntities)->AddHtml('</div>');
                            $oOutput->AddHtml('</div>');
						}

						// Adding hidden input if not a label
                        if($sFieldClass !== 'Combodo\\iTop\\Form\\Field\\LabelField')
                        {
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
						if (!$this->oField->GetHidden())
						{
                            // Label
                            $oOutput->AddHtml('<div class="form_field_label">');
                            if ($this->oField->GetLabel() !== '')
							{
								$oOutput->AddHtml('<label for="' . $this->oField->GetGlobalId() . '" class="control-label">')->AddHtml($this->oField->GetLabel(), true)->AddHtml('</label>');
							}
							$oOutput->AddHtml('</div>');

                            // Value
                            $oOutput->AddHtml('<div class="form_field_control">');
                            $oOutput->AddHtml('<div class="form-control-static">')->AddHtml($this->oField->GetDisplayValue(), false)->AddHtml('</div>');
                            $oOutput->AddHtml('</div>');
                            // Trigger highlighter for all code blocks in this html text field 
                            $oOutput->AddJs(
                            <<<JS
$("[data-field-id='{$this->oField->GetId()}'][data-form-path='{$this->oField->GetFormPath()}'] .HTML pre").each(function(i, block) {
    hljs.highlightBlock(block);
});
JS
							);
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
                        if ($this->oField->GetLabel() !== '')
						{
							$oOutput->AddHtml('<label for="' . $this->oField->GetGlobalId() . '" class="control-label">')->AddHtml($this->oField->GetLabel(), true)->AddHtml('</label>');
						}
                        $oOutput->AddHtml('</div>');

                        // Value
                        $oOutput->AddHtml('<div class="form_field_control">');
                        // - Entries if necessary
						$this->PreparingCaseLogEntries($oOutput);
                        $oOutput->AddHtml('</div>');

                        // Closing container
                        $oOutput->AddHtml('</div>');
                        // Trigger highlighter for all code blocks in this caselog
						$oOutput->AddJs(
							<<<JS
   $("[data-field-id='{$this->oField->GetId()}'][data-form-path='{$this->oField->GetFormPath()}'] .caselog_field_entry_content pre").each(function(i, block) {
            hljs.highlightBlock(block);
        });
JS
						);
						break;

					case 'Combodo\\iTop\\Form\\Field\\BlobField':
                    case 'Combodo\\iTop\\Form\\Field\\ImageField':
                        // Opening container
						$oOutput->AddHtml('<div class="form-group">');

						// Showing label / value only if read-only but not hidden
						if (!$this->oField->GetHidden())
						{
						    // Label
                            $oOutput->AddHtml('<div class="form_field_label">');
							if ($this->oField->GetLabel() !== '')
							{
								$oOutput->AddHtml('<label for="' . $this->oField->GetGlobalId() . '" class="control-label">')->AddHtml($this->oField->GetLabel(), true)->AddHtml('</label>');
							}
							$oOutput->AddHtml('</div>');

							// Value
                            $oOutput->AddHtml('<div class="form_field_control">');
                            $oOutput->AddHtml('<div class="form-control-static">');
							if($sFieldClass === 'Combodo\\iTop\\Form\\Field\\ImageField')
                            {
                                $oOutput->AddHtml('<img src="' . $this->oField->GetDisplayUrl() . '" />', false);
                            }
                            else
                            {
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
						if (!$this->oField->GetHidden())
						{
						    // Label
                            $oOutput->AddHtml('<div class="form_field_label">');
							if ($this->oField->GetLabel() !== '')
							{
								$oOutput->AddHtml('<label for="' . $this->oField->GetGlobalId() . '" class="control-label">')->AddHtml($this->oField->GetLabel(), true)->AddHtml('</label>');
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
        if($this->oField->GetHidden() || !$this->oField->GetReadOnly())
        {

            // JS Form field widget construct
            $aValidators = array();
            foreach ($this->oField->GetValidators() as $oValidator)
            {
                $aValidators[$oValidator::GetName()] = array(
                    'reg_exp' => $oValidator->GetRegExp(),
                    'message' => Dict::S($oValidator->GetErrorMessage())
                );
            }

            $sFormFieldOptions = json_encode(array(
                'validators' => $aValidators
            ));

            switch ($sFieldClass)
            {
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
                    if($bRichEditor)
                    {
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
                    else
                    {
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
		switch ($sFieldClass)
		{
			case 'Combodo\\iTop\\Form\\Field\\TextAreaField':
			case 'Combodo\\iTop\\Form\\Field\\CaseLogField':
				$bRichEditor = ($this->oField->GetFormat() === TextAreaField::ENUM_FORMAT_HTML);
				if($bRichEditor)
				{
					// MagnificPopup on images
					$oOutput->AddJs(InlineImage::FixImagesWidth());
				}
				break;
		}

		return $oOutput;
	}

    /**
     * @param RenderingOutput $oOutput
     *
     * @throws \Exception
     */
	protected function PreparingCaseLogEntries(RenderingOutput &$oOutput)
	{
		$aEntries = $this->oField->GetEntries();
		if (count($aEntries) > 0)
		{
			$oOutput->AddHtml('<div>');
			for ($i = 0; $i < count($aEntries); $i++)
			{
				$sEntryDate = AttributeDateTime::GetFormat()->Format($aEntries[$i]['date']);
				$sEntryUser = $aEntries[$i]['user_login'];
				$sEntryHeader = Dict::Format('UI:CaseLog:Header_Date_UserName', $sEntryDate, $sEntryUser);

				// Only the last 2 entries are expanded by default
				$sEntryContentExpanded = ($i < 2) ? 'true' : 'false';
				$sEntryHeaderButtonClass = ($i < 2) ? '' : 'collapsed';
				$sEntryContentClass = ($i < 2) ? 'in' : '';
				$sEntryContentId = 'caselog_field_entry_content-' . $this->oField->GetGlobalId() . '-' . $i;
				$sEntryHtml = AttributeText::RenderWikiHtml($aEntries[$i]['message_html'], true /* wiki only */);
				$sEntryHtml = InlineImage::FixUrls($sEntryHtml);

				// Note : We use CKEditor stylesheet to format this
				$oOutput->AddHtml(
<<<EOF
					<div class="caselog_field_entry cke_inner">
						<div class="caselog_field_entry_header">
							{$sEntryHeader}
							<div class="pull-right">
								<span class="caselog_field_entry_button {$sEntryHeaderButtonClass}" data-toggle="collapse" href="#{$sEntryContentId}" aria-expanded="{$sEntryContentExpanded}" aria-controls="{$sEntryContentId}"></span>
							</div>
						</div>
						<div class="caselog_field_entry_content collapse {$sEntryContentClass}" id="{$sEntryContentId}">
							{$sEntryHtml}
						</div>
					</div>
EOF
				);
			}
			$oOutput->AddHtml('</div>');
		}
	}

}

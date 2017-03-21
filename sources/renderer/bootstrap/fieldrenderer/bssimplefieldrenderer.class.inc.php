<?php

// Copyright (C) 2010-2016 Combodo SARL
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

namespace Combodo\iTop\Renderer\Bootstrap\FieldRenderer;

use \utils;
use \Dict;
use \UserRights;
use \AttributeDateTime;
use \AttributeText;
use \InlineImage;
use \Combodo\iTop\Renderer\FieldRenderer;
use \Combodo\iTop\Renderer\RenderingOutput;
use \Combodo\iTop\Form\Field\TextAreaField;

/**
 * Description of BsSimpleFieldRenderer
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 */
class BsSimpleFieldRenderer extends FieldRenderer
{

	/**
	 * Returns a RenderingOutput for the FieldRenderer's Field
	 *
	 * @return \Combodo\iTop\Renderer\RenderingOutput
	 */
	public function Render()
	{
		$oOutput = new RenderingOutput();
		$sFieldClass = get_class($this->oField);
		$sFieldMandatoryClass = ($this->oField->GetMandatory()) ? 'form_mandatory' : '';
		
		// Rendering field in edition mode
		if (!$this->oField->GetReadOnly() && !$this->oField->GetHidden())
		{
			switch ($sFieldClass)
			{
				case 'Combodo\\iTop\\Form\\Field\\DateTimeField':
					$oOutput->AddHtml('<div class="form-group ' . $sFieldMandatoryClass . '">');
					if ($this->oField->GetLabel() !== '')
					{
						$oOutput->AddHtml('<label for="' . $this->oField->GetGlobalId() . '" class="control-label">')->AddHtml($this->oField->GetLabel(), true)->AddHtml('</label>');
					}
					$oOutput->AddHtml('<div class="help-block"></div>');
					$oOutput->AddHtml('<div class="input-group date" id="datepicker_' . $this->oField->GetGlobalId() . '">');
					$oOutput->AddHtml('<input type="text" id="' . $this->oField->GetGlobalId() . '" name="' . $this->oField->GetId() . '" value="')->AddHtml($this->oField->GetDisplayValue(), true)->AddHtml('" class="form-control" maxlength="255" />');
					$oOutput->AddHtml('<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>');
					$oOutput->AddHtml('</div>');
					$oOutput->AddHtml('</div>');
					$sJSFormat = json_encode($this->oField->GetJSDateTimeFormat());
					$oOutput->AddJs(
<<<EOF
					$('#datepicker_{$this->oField->GetGlobalId()}').datetimepicker({format: $sJSFormat});
EOF
					);
					break;
				case 'Combodo\\iTop\\Form\\Field\\PasswordField':
					$oOutput->AddHtml('<div class="form-group ' . $sFieldMandatoryClass . '">');
					if ($this->oField->GetLabel() !== '')
					{
						$oOutput->AddHtml('<label for="' . $this->oField->GetGlobalId() . '" class="control-label">')->AddHtml($this->oField->GetLabel(), true)->AddHtml('</label>');
					}
					$oOutput->AddHtml('<div class="help-block"></div>');
					$oOutput->AddHtml('<input type="password" id="' . $this->oField->GetGlobalId() . '" name="' . $this->oField->GetId() . '" value="')->AddHtml($this->oField->GetCurrentValue(), true)->AddHtml('" class="form-control" maxlength="255" autocomplete="off" />');
					$oOutput->AddHtml('</div>');
					break;

				case 'Combodo\\iTop\\Form\\Field\\StringField':
					$oOutput->AddHtml('<div class="form-group ' . $sFieldMandatoryClass . '">');
					if ($this->oField->GetLabel() !== '')
					{
						$oOutput->AddHtml('<label for="' . $this->oField->GetGlobalId() . '" class="control-label">')->AddHtml($this->oField->GetLabel(), true)->AddHtml('</label>');
					}
					$oOutput->AddHtml('<div class="help-block"></div>');
					$oOutput->AddHtml('<input type="text" id="' . $this->oField->GetGlobalId() . '" name="' . $this->oField->GetId() . '" value="')->AddHtml($this->oField->GetCurrentValue(), true)->AddHtml('" class="form-control" maxlength="255" />');
					$oOutput->AddHtml('</div>');
					break;

				case 'Combodo\\iTop\\Form\\Field\\TextAreaField':
				case 'Combodo\\iTop\\Form\\Field\\CaseLogField':
					$bRichEditor = ($this->oField->GetFormat() === TextAreaField::ENUM_FORMAT_HTML);
					
					$oOutput->AddHtml('<div class="form-group ' . $sFieldMandatoryClass . '">');
					if ($this->oField->GetLabel() !== '')
					{
						$oOutput->AddHtml('<label for="' . $this->oField->GetGlobalId() . '" class="control-label">')->AddHtml($this->oField->GetLabel(), true)->AddHtml('</label>');
					}
					$oOutput->AddHtml('<div class="help-block"></div>');
					// First the edition area
					$oOutput->AddHtml('<div>');
					$oOutput->AddHtml('<textarea id="' . $this->oField->GetGlobalId() . '" name="' . $this->oField->GetId() . '" class="form-control" rows="8">' . $this->oField->GetCurrentValue() . '</textarea>');
					$oOutput->AddHtml('</div>');
					// Then the previous entries if necessary
					if ($sFieldClass === 'Combodo\\iTop\\Form\\Field\\CaseLogField')
					{
						$this->PreparingCaseLogEntries($oOutput);
					}

					$oOutput->AddHtml('</div>');
					// Some additional stuff if we are displaying it with a rich editor
					if ($bRichEditor)
					{
						$sEditorLanguage = strtolower(trim(UserRights::GetUserLanguage()));
						$oOutput->AddJs(
<<<EOF
							$('#{$this->oField->GetGlobalId()}').addClass('htmlEditor');
							$('#{$this->oField->GetGlobalId()}').ckeditor(function(){}, {language: '$sEditorLanguage', contentsLanguage: '$sEditorLanguage'});
EOF
						);
						if (($this->oField->GetObject() !== null) && ($this->oField->GetTransactionId() !== null))
						{
							$oOutput->AddJs(InlineImage::EnableCKEditorImageUpload($this->oField->GetObject(), utils::GetUploadTempId($this->oField->GetTransactionId())));
						}
					}
					break;

				case 'Combodo\\iTop\\Form\\Field\\SelectField':
				case 'Combodo\\iTop\\Form\\Field\\MultipleSelectField':
					$oOutput->AddHtml('<div class="form-group ' . $sFieldMandatoryClass . '">');
					if ($this->oField->GetLabel() !== '')
					{
						$oOutput->AddHtml('<label for="' . $this->oField->GetGlobalId() . '" class="control-label">')->AddHtml($this->oField->GetLabel(), true)->AddHtml('</label>');
					}
					$oOutput->AddHtml('<div class="help-block"></div>');
					$oOutput->AddHtml('<select id="' . $this->oField->GetGlobalId() . '" name="' . $this->oField->GetId() . '" ' . ( ($this->oField->GetMultipleValuesEnabled()) ? 'multiple' : '' ) . ' class="form-control">');
					foreach ($this->oField->GetChoices() as $sChoice => $sLabel)
					{
						// Note : The test is a double equal on purpose as the type of the value received from the XHR is not always the same as the type of the allowed values. (eg : string vs int)
						$sSelectedAtt = ($this->oField->GetCurrentValue() == $sChoice) ? 'selected' : '';
						$oOutput->AddHtml('<option value="' . $sChoice . '" ' . $sSelectedAtt . ' >')->AddHtml($sLabel)->AddHtml('</option>');
					}
					$oOutput->AddHtml('</select>');
					$oOutput->AddHtml('</div>');
					break;

				case 'Combodo\\iTop\\Form\\Field\\RadioField':
				case 'Combodo\\iTop\\Form\\Field\\CheckboxField':
					$sFieldType = ($sFieldClass === 'Combodo\\iTop\\Form\\Field\\RadioField') ? 'radio' : 'checkbox';

					$oOutput->AddHtml('<div class="form-group ' . $sFieldMandatoryClass . '" id="' . $this->oField->GetGlobalId() . '">');

					if ($this->oField->GetLabel() !== '')
					{
						$oOutput->AddHtml('<div><label class="control-label">')->AddHtml($this->oField->GetLabel(), true)->AddHtml('</label></div>');
					}

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
					break;

				case 'Combodo\\iTop\\Form\\Field\\HiddenField':
					$oOutput->AddHtml('<input type="hidden" id="' . $this->oField->GetGlobalId() . '" name="' . $this->oField->GetId() . '" value="')->AddHtml($this->oField->GetCurrentValue(), true)->AddHtml('"/>');
					break;
			}
		}
		// ... and in read-only mode (or hidden)
		else
		{
			// ... specific rendering for fields with multiple values
			if (($this->oField instanceof Combodo\iTop\Form\Field\MultipleChoicesField) && ($this->oField->GetMultipleValuesEnabled()))
			{
				// TODO
			}
			// ... clasic rendering for fields with only one value
			else
			{
				switch ($sFieldClass)
				{
					case 'Combodo\\iTop\\Form\\Field\\LabelField':
						$oOutput->AddHtml('<div class="form-group">');
						// Showing label / value only if read-only but not hidden
						if (!$this->oField->GetHidden())
						{
							if ($this->oField->GetLabel() !== '')
							{
								$oOutput->AddHtml('<label for="' . $this->oField->GetGlobalId() . '" class="control-label">')->AddHtml($this->oField->GetLabel(), true)->AddHtml('</label>');
							}
							$oOutput->AddHtml('<div class="form-control-static">')->AddHtml($this->oField->GetCurrentValue(), true)->AddHtml('</div>');
						}
						$oOutput->AddHtml('</div>');
						break;

					case 'Combodo\\iTop\\Form\\Field\\StringField':
					case 'Combodo\\iTop\\Form\\Field\\TextAreaField':
						$oOutput->AddHtml('<div class="form-group">');
						// Showing label / value only if read-only but not hidden
						if (!$this->oField->GetHidden())
						{
							if ($this->oField->GetLabel() !== '')
							{
								$oOutput->AddHtml('<label for="' . $this->oField->GetGlobalId() . '" class="control-label">')->AddHtml($this->oField->GetLabel(), true)->AddHtml('</label>');
							}

							if($sFieldClass === 'Combodo\\iTop\\Form\\Field\\TextAreaField')
							{
								$bEncodeHtmlEntities = false;
								$sDisplayValue = $this->oField->GetDisplayValue();
							}
							else
							{
								$bEncodeHtmlEntities = true;
								$sDisplayValue = $this->oField->GetCurrentValue();
							}
							$oOutput->AddHtml('<div class="form-control-static">')->AddHtml($sDisplayValue, $bEncodeHtmlEntities)->AddHtml('</div>');
						}
						$oOutput->AddHtml('<input type="hidden" id="' . $this->oField->GetGlobalId() . '" name="' . $this->oField->GetId() . '" value="')->AddHtml($this->oField->GetCurrentValue(), true)->AddHtml('" class="form-control" />');
						$oOutput->AddHtml('</div>');
						break;

					case 'Combodo\\iTop\\Form\\Field\\CaseLogField':
						$oOutput->AddHtml('<div class="form-group ' . $sFieldMandatoryClass . '">');
						if ($this->oField->GetLabel() !== '')
						{
							$oOutput->AddHtml('<label for="' . $this->oField->GetGlobalId() . '" class="control-label">')->AddHtml($this->oField->GetLabel(), true)->AddHtml('</label>');
						}
						// Entries if necessary
						$this->PreparingCaseLogEntries($oOutput);
						$oOutput->AddHtml('</div>');
						break;

					case 'Combodo\\iTop\\Form\\Field\\DateTimeField':
						$oOutput->AddHtml('<div class="form-group">');
						// Showing label / value only if read-only but not hidden
						if (!$this->oField->GetHidden())
						{
							if ($this->oField->GetLabel() !== '')
							{
								$oOutput->AddHtml('<label for="' . $this->oField->GetGlobalId() . '" class="control-label">')->AddHtml($this->oField->GetLabel(), true)->AddHtml('</label>');
							}
							$oOutput->AddHtml('<div class="form-control-static">')->AddHtml($this->oField->GetDisplayValue(), true)->AddHtml('</div>');
						}
						$oOutput->AddHtml('<input type="hidden" id="' . $this->oField->GetGlobalId() . '" name="' . $this->oField->GetId() . '" value="')->AddHtml($this->oField->GetDisplayValue(), true)->AddHtml('" class="form-control" />');
						$oOutput->AddHtml('</div>');
						break;

					case 'Combodo\\iTop\\Form\\Field\\DurationField':
						$oOutput->AddHtml('<div class="form-group">');
						// Showing label / value only if read-only but not hidden
						if (!$this->oField->GetHidden())
						{
							if ($this->oField->GetLabel() !== '')
							{
								$oOutput->AddHtml('<label for="' . $this->oField->GetGlobalId() . '" class="control-label">')->AddHtml($this->oField->GetLabel(), true)->AddHtml('</label>');
							}
							$oOutput->AddHtml('<div class="form-control-static">')->AddHtml($this->oField->GetDisplayValue(), true)->AddHtml('</div>');
						}
						$oOutput->AddHtml('<input type="hidden" id="' . $this->oField->GetGlobalId() . '" name="' . $this->oField->GetId() . '" value="')->AddHtml($this->oField->GetCurrentValue(), true)->AddHtml('" class="form-control" />');
						$oOutput->AddHtml('</div>');
						break;

					case 'Combodo\\iTop\\Form\\Field\\BlobField':
						$oOutput->AddHtml('<div class="form-group">');
						// Showing label / value only if read-only but not hidden
						if (!$this->oField->GetHidden())
						{
							if ($this->oField->GetLabel() !== '')
							{
								$oOutput->AddHtml('<label for="' . $this->oField->GetGlobalId() . '" class="control-label">')->AddHtml($this->oField->GetLabel(), true)->AddHtml('</label>');
							}
							$oOutput->AddHtml('<div class="form-control-static">')->AddHtml($this->oField->GetDisplayValue(), false)->AddHtml('</div>');
						}
						$oOutput->AddHtml('<input type="hidden" id="' . $this->oField->GetGlobalId() . '" name="' . $this->oField->GetId() . '" value="')->AddHtml($this->oField->GetCurrentValue(), true)->AddHtml('" class="form-control" />');
						$oOutput->AddHtml('</div>');
						break;

					case 'Combodo\\iTop\\Form\\Field\\RadioField':
					case 'Combodo\\iTop\\Form\\Field\\SelectField':
					case 'Combodo\\iTop\\Form\\Field\\MultipleSelectField':
						$aFieldChoices = $this->oField->GetChoices();
						$sFieldValue = (isset($aFieldChoices[$this->oField->GetCurrentValue()])) ? $aFieldChoices[$this->oField->GetCurrentValue()] : Dict::S('UI:UndefinedObject');

						$oOutput->AddHtml('<div class="form-group">');
						// Showing label / value only if read-only but not hidden
						if (!$this->oField->GetHidden())
						{
							if ($this->oField->GetLabel() !== '')
							{
								$oOutput->AddHtml('<label for="' . $this->oField->GetGlobalId() . '" class="control-label">')->AddHtml($this->oField->GetLabel(), true)->AddHtml('</label>');
							}
							$oOutput->AddHtml('<div class="form-control-static">' . $sFieldValue . '</div>');
						}
						$oOutput->AddHtml('<input type="hidden" id="' . $this->oField->GetGlobalId() . '" name="' . $this->oField->GetId() . '" value="' . $this->oField->GetCurrentValue() . '" class="form-control" />');
						$oOutput->AddHtml('</div>');
						break;

					case 'Combodo\\iTop\\Form\\Field\\HiddenField':
						$oOutput->AddHtml('<input type="hidden" id="' . $this->oField->GetGlobalId() . '" name="' . $this->oField->GetId() . '" value="')->AddHtml($this->oField->GetCurrentValue(), true)->AddHtml('"/>');
						break;
				}
			}
		}

		// JS FieldChange trigger (:input are not always at the same depth)
		switch ($sFieldClass)
		{
			case 'Combodo\\iTop\\Form\\Field\\PasswordField':
			case 'Combodo\\iTop\\Form\\Field\\StringField':
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
					});
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
                    // MagnificPopup on images
                    $oOutput->AddJs(InlineImage::FixImagesWidth());
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

		return $oOutput;
	}

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

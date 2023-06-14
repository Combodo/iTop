<?php
/**
 * Copyright (C) 2013-2023 Combodo SARL
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

use CaptureWebPage;
use Combodo\iTop\Application\UI\Base\Component\Field\FieldUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Html\Html;
use Combodo\iTop\Application\UI\Base\Component\Input\InputUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Input\Select\SelectOptionUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Input\SelectUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Layout\UIContentBlockUIBlockFactory;
use Combodo\iTop\Core\MetaModel\FriendlyNameType;
use Combodo\iTop\Form\Field\SelectObjectField;
use Combodo\iTop\Form\Validator\MandatoryValidator;
use Combodo\iTop\Renderer\BlockRenderer;
use Combodo\iTop\Renderer\FieldRenderer;
use DBObjectSet;
use Dict;
use MetaModel;
use UIExtKeyWidget;

/**
 * Class ConsoleSelectObjectFieldRenderer
 *
 * @author Romain Quetiez <romain.quetiez@combodo.com>
 */
class ConsoleSelectObjectFieldRenderer extends FieldRenderer
{
	/**
	 * @inheritDoc
	 */
	public function Render()
	{
		$oOutput = parent::Render();

		$oBlock = FieldUIBlockFactory::MakeStandard($this->oField->GetLabel());
		$oBlock->AddDataAttribute("input-id", $this->oField->GetGlobalId());

		$sFieldClass = get_class($this->oField);
		\IssueLog::Error($this->oField->GetLabel().':render select multi:'.$sFieldClass);
		$sEditType = 'none';
		if ($this->oField->GetReadOnly()) {
			$oBlock->AddDataAttribute("input-type", "Combodo\\iTop\\Form\\Field\\SelectObjectField\readonly");
			$oSearch = $this->oField->GetSearch()->DeepClone();
			$oSearch->AddCondition('id', $this->oField->GetCurrentValue());
			$oSet = new DBObjectSet($oSearch);
			$oObject = $oSet->Fetch();
			if ($oObject) {
				$sCurrentLabel = $oObject->Get('friendlyname');
			} else {
				$sCurrentLabel = '';
			}
			$oValue = UIContentBlockUIBlockFactory::MakeStandard();
			$oValue->AddSubBlock(InputUIBlockFactory::MakeForHidden("", $this->oField->GetCurrentValue(), $this->oField->GetGlobalId()));
			$oValue->AddSubBlock(new Html($sCurrentLabel));
			$oBlock->SetValue($oValue);
		} else {


			$oSearch = $this->oField->GetSearch()->DeepClone();
			$oSearch->SetModifierProperty('UserRightsGetSelectFilter', 'bSearchMode', true);

			$oSet = new DBObjectSet($oSearch);
			$oSet->ApplyParameters();
			$oSet->OptimizeColumnLoad(array($oSearch->GetClassAlias() => array('friendlyname')));

			$sTargetClass = $oSearch->GetClass();
			$oAllowedValues = new DBObjectSet($oSearch);

			$iMaxComboLength = $this->oField->GetMaximumComboLength();
			$iCount = $oAllowedValues->Count();

			switch ($sFieldClass) {
				case 'Combodo\\iTop\\Form\\Field\\MultipleSelectObjectField':
					$oValue = UIContentBlockUIBlockFactory::MakeStandard("", ["form-field-content"]);
					$bVertical = true;
					$idx = 0;
					$aValues = $this->oField->GetCurrentValue();
					$sId = $this->oField->GetGlobalId();

					// N°4792 - load only the required fields
					$aFieldsToLoad = [];

					$aComplementAttributeSpec = MetaModel::GetNameSpec($oAllowedValues->GetClass(), FriendlyNameType::COMPLEMENTARY);
					$sFormatAdditionalField = $aComplementAttributeSpec[0];
					$aAdditionalField = $aComplementAttributeSpec[1];
					$bAddingValue = false;

					if (count($aAdditionalField) > 0) {
						$sClassAllowed = $oAllowedValues->GetClass();
						$bAddingValue = true;
						$aFieldsToLoad[$sClassAllowed] = $aAdditionalField;
					}
					$sObjectImageAttCode = MetaModel::GetImageAttributeCode($sClassAllowed);
					if (!empty($sObjectImageAttCode)) {
						$aFieldsToLoad[$sClassAllowed][] = $sObjectImageAttCode;
					}
					$aFieldsToLoad[$sClassAllowed][] = 'friendlyname';
					$oAllowedValues->OptimizeColumnLoad($aFieldsToLoad);
					$bInitValue = false;
					while ($oObj = $oAllowedValues->Fetch()) {
						/*$aOption = [];
						$aOption['value'] = $oObj->GetKey();
						$aOption['label'] = $oObj->GetName();
						$aOption['search_label'] = utils::HtmlEntityDecode($oObj->GetName());

						if ($oObj->IsObsolete()) {
							$aOption['obsolescence_flag'] = "1";
						}
						if ($bAddingValue) {
							$aArguments = [];
							foreach ($aAdditionalField as $sAdditionalField) {
								array_push($aArguments, $oObj->Get($sAdditionalField));
							}
							$aOption['additional_field'] = utils::HtmlEntities(vsprintf($sFormatAdditionalField, $aArguments));
						}
						if (!empty($sObjectImageAttCode)) {
							// Try to retrieve image for contact
							// @var \ormDocument $oImage
							$oImage = $oObj->Get($sObjectImageAttCode);
							if (!$oImage->IsEmpty()) {
								$aOption['picture_url'] = $oImage->GetDisplayURL($sClassAllowed, $oObj->GetKey(), $sObjectImageAttCode);
								$aOption['initials'] = '';
							} else {
								$aOption['initials'] = utils::FormatInitialsForMedallion(utils::ToAcronym($oObj->Get('friendlyname')));
							}
						}*/
						$sLabel = $oObj->GetName();
						$oCheckBox = InputUIBlockFactory::MakeForInputWithLabel($sLabel, "checkbox_".$sId, $oObj->GetKey(), "{$sId}", "checkbox");
						if (in_array($oObj->GetKey(), $aValues, true)) {
							$oCheckBox->GetInput()->SetIsChecked(true);
						}
						$oCheckBox->SetBeforeInput(false);
						$oCheckBox->GetInput()->AddCSSClass('ibo-input-checkbox');
						$oValue->AddSubBlock($oCheckBox);
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
					$oValue->AddSubBlock(InputUIBlockFactory::MakeForHidden("", json_encode($aValues), $sId));
					$oValue->AddSubBlock(new Html('<span class="form_validation"></span>'));
					$oBlock->AddSubBlock($oValue);
					break;
				case 'Combodo\\iTop\\Form\\Field\\TagSetObjectField':
					/** @var \ormSet $value code qui vient des tagset
					 * $sJson = $oAttDef->GetJsonForWidget($value, $aArgs);
					 * $sEscapedJson = utils::EscapeHtml($sJson);
					 * $sSetInputName = "attr_{$sFormPrefix}{$sAttCode}";
					 *
					 * // handle form validation
					 * $aEventsList[] = 'change';
					 * $aEventsList[] = 'validate';
					 * $sNullValue = '';
					 * $sFieldToValidateId = $sFieldToValidateId.AttributeSet::EDITABLE_INPUT_ID_SUFFIX;
					 *
					 * // generate form HTML output
					 * $sValidationSpan = "<span class=\"form_validation ibo-field-validation\" id=\"v_{$sFieldToValidateId}\"></span>";
					 * $sHTMLValue = '<div class="field_input_zone field_input_set ibo-input-wrapper ibo-input-tagset-wrapper" data-validation="untouched"><input id="'.$iId.'" name="'.$sSetInputName.'" type="hidden" value="'.$sEscapedJson.'"></div>'.$sValidationSpan.$sReloadSpan;
					 * $sScript = "$('#$iId').set_widget({inputWidgetIdSuffix: '".AttributeSet::EDITABLE_INPUT_ID_SUFFIX."'});";
					 * $oPage->add_ready_script($sScript);*/

					$oValue = UIContentBlockUIBlockFactory::MakeStandard("", ["form-field-content"]);
					$aValues = $this->oField->GetCurrentValue();

					$aFieldsToLoad = [];
					$aComplementAttributeSpec = MetaModel::GetNameSpec($oAllowedValues->GetClass(), FriendlyNameType::COMPLEMENTARY);
					$sFormatAdditionalField = $aComplementAttributeSpec[0];
					$aAdditionalField = $aComplementAttributeSpec[1];
					$bAddingValue = false;

					if (count($aAdditionalField) > 0) {
						$sClassAllowed = $oAllowedValues->GetClass();
						$bAddingValue = true;
						$aFieldsToLoad[$sClassAllowed] = $aAdditionalField;

						$sObjectImageAttCode = MetaModel::GetImageAttributeCode($sClassAllowed);
						if (!empty($sObjectImageAttCode)) {
							$aFieldsToLoad[$sClassAllowed][] = $sObjectImageAttCode;
						}
						$aFieldsToLoad[$sClassAllowed][] = 'friendlyname';
					}

					$oAllowedValues->OptimizeColumnLoad($aFieldsToLoad);
//MAYBE USE INPUT SET ?
					$oSelect = SelectUIBlockFactory::MakeForSelectWithLabel($this->oField->GetLabel(), $this->oField->GetDescription(), $this->oField->GetGlobalId());
					$oSelect->SetIsMultiple(true);
					while ($oObj = $oAllowedValues->Fetch()) {
						$oSelect->AddSubBlock(SelectOptionUIBlockFactory::MakeForSelectOption(
							$oObj->GetKey(),
							$oObj->GetName(),
							(is_null($aValues) || !is_array($aValues)) ? false : in_array($oObj->GetKey(), $aValues, true) === true)
						);
					}

					$oOutput->AddJs(
						<<<JS
							 $("#{$this->oField->GetGlobalId()}").selectize({
								maxItems: null,
							    sortField: 'text',
							    onChange: function(value){
					                  console.warn('chane');
					                  var me = this.\$input;
					                  me.trigger("field_change", {
					                            id: me.attr("id"),
					                            name: me.closest(".form_field").attr("data-field-id"),
					                            value: me.val()
					                        })
					                        .closest('.form_handler').trigger('change');
					                        
			                        $(me).closest(".field_set").trigger("field_change", {
			                            id: $(me).attr("id"),
			                            name: $(me).closest(".form_field").attr("data-field-id"),
			                            value: $(me).val()
			                        })
			                        .closest('.form_handler').trigger('value_change');
							    }, 
							     loadingClass: '',
                                itemClass: 'item attribute-set-item',
								inputClass: 'attribute-set ibo-input ibo-input-selectize',	
								render: {
							                        option: function(option) {
							            return CombodoGlobalToolbox.RenderTemplate('#{$this->oField->GetGlobalId()}_options_template', option, this.settings.optionClass)[0].outerHTML;
							        },
							                        item: function (item) {
							            return CombodoGlobalToolbox.RenderTemplate('#{$this->oField->GetGlobalId()}_items_template', item, this.settings.itemClass)[0].outerHTML;
							        },
							    },
							});
							 $("#{$this->oField->GetGlobalId()}").closest('div').addClass('ibo-input-select-wrapper--with-buttons');
							  $("#{$this->oField->GetGlobalId()}").off("change").on("change", function(){
		                        var me = this;

		                    });
JS
					);

					$oValue->AddSubBlock($oSelect);
					$oValue->AddSubBlock(new Html('<span class="form_validation"></span>'));
					$oBlock->AddSubBlock($oValue);
					break;
				default:
					if ($iCount > $iMaxComboLength) {

						// Auto-complete
						//
						$oBlock->AddDataAttribute("input-type", "Combodo\\iTop\\Form\\Field\\SelectObjectField\\Autocomplete");
						$sEditType = 'autocomplete';
						$sFieldName = $this->oField->GetGlobalId();
						$sFieldId = $sFieldName;
						$sFormPrefix = '';
						$oWidget = new UIExtKeyWidget($sTargetClass, $sFieldId, '', true);
						$aArgs = array();
						$sTitle = $this->oField->GetLabel();

						$oPage = new CaptureWebPage();
						$sHTMLValue = $oWidget->DisplaySelect($oPage, $iMaxComboLength, false /* $bAllowTargetCreation */, $sTitle, $oSet, $this->oField->GetCurrentValue(), $this->oField->GetMandatory(), $sFieldName, $sFormPrefix, $aArgs);
						$oValue = UIContentBlockUIBlockFactory::MakeStandard();
						$oValue->AddSubBlock(new Html($sHTMLValue));
						$oValue->AddSubBlock(new Html($oPage->GetHtml()));
						$oBlock->AddSubBlock($oValue);

						$oOutput->AddJs($oPage->GetJS());
						$oOutput->AddJs($oPage->GetReadyJS());
						foreach ($oPage->GetCSS() as $sCss) {
							$oOutput->AddCss($sCss);
						}
						foreach ($oPage->GetJSFiles() as $sFile) {
							$oOutput->AddJsFile($sFile);
						}
						foreach ($oPage->GetCSSFiles() as $sFile) {
							$oOutput->AddCssFile($sFile);
						}
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
					} elseif ($this->oField->GetControlType() == SelectObjectField::CONTROL_RADIO_VERTICAL) {
						$oBlock->AddDataAttribute("input-type", "Combodo\\iTop\\Form\\Field\\SelectObjectField\\Radio");
						// Radio buttons (vertical)
						//
						$sEditType = 'radio';
						$bVertical = true;
						$idx = 0;
						$bMandatory = $this->oField->GetMandatory();
						$value = $this->oField->GetCurrentValue();
						$sId = $this->oField->GetGlobalId();

						$oValue = UIContentBlockUIBlockFactory::MakeStandard();

						while ($oObject = $oSet->Fetch()) {
							$iObject = $oObject->GetKey();
							$sLabel = $oObject->Get('friendlyname');
							if (($iCount == 1) && $bMandatory) {
								// When there is only once choice, select it by default
								$sSelected = 'checked';
								$value = $iObject;
							} else {
								$sSelected = ($value == $iObject) ? 'checked' : '';
							}
							$oRadioCustom = InputUIBlockFactory::MakeForInputWithLabel($sLabel, "radio_$sId", $iObject, "{$sId}_{$iObject}", "radio");
							$oRadioCustom->AddCSSClass('ibo-input-field-wrapper');
							$oRadioCustom->GetInput()->SetIsChecked($sSelected);
							$oRadioCustom->SetBeforeInput(false);
							$oRadioCustom->GetInput()->AddCSSClass('ibo-input-checkbox');
							$oValue->AddSubBlock($oRadioCustom);
							$oOutput->AddJs(
								<<<EOF
	                    $("#{$sId}_{$iObject}").off("change").on("change", function(){
	                        $('#{$sId}').val(this.value).trigger('change');
	                    });
EOF
							);
							if ($bVertical) {
								$oValue->AddSubBlock(new Html("<br>"));
							}
							$idx++;
						}
						$oValue->AddSubBlock(InputUIBlockFactory::MakeForHidden($sId, $value, $sId));
						$oBlock->AddSubBlock($oValue);
						$oBlock->AddSubBlock(new Html('<span class="form_validation"></span>'));
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
					} else {
						// Drop-down select
						//
						$oBlock->AddDataAttribute("input-type", "Combodo\\iTop\\Form\\Field\\SelectObjectField\\Select");
						$sEditType = 'select';
						$oSelect = SelectUIBlockFactory::MakeForSelect("", $this->oField->GetGlobalId());
						$oSelect->AddCSSClass('ibo-input-select-placeholder');
						$oBlock->AddSubBlock(UIContentBlockUIBlockFactory::MakeStandard(null, ['ibo-input-field-wrapper'])->AddSubBlock($oSelect));
						$oSelect->AddOption(SelectOptionUIBlockFactory::MakeForSelectOption('', Dict::S('UI:SelectOne'), false));
						while ($oObject = $oSet->Fetch()) {
							$iObject = $oObject->GetKey();
							$sLabel = $oObject->Get('friendlyname');
							// Note : The test is a double equal on purpose as the type of the value received from the XHR is not always the same as the type of the allowed values. (eg : string vs int)
							$oSelect->AddOption(SelectOptionUIBlockFactory::MakeForSelectOption($iObject, $sLabel, ($this->oField->GetCurrentValue() == $iObject)));
						}
						$oBlock->AddSubBlock(new Html('<span class="form_validation"></span>'));
						$oOutput->AddJs(
							<<<JS
 $("#{$this->oField->GetGlobalId()}").selectize({
    sortField: 'text',
    onChange: function(value){
    			  var me = this.\$input;
    			  me.trigger("field_change", {
                            id: me.attr("id"),
                            name: me.closest(".form_field").attr("data-field-id"),
                            value: me.val()
                        })
                        .closest('.form_handler').trigger('value_change');
     },  
     loadingClass: '',
     itemClass: 'item attribute-set-item',
	 inputClass: 'ibo-input-vanilla ibo-input ibo-input-selectize',	
	 plugins: {
                'combodo_update_operations' : {
			            initial: ["5"],
			        },
                'combodo_auto_position' : {
                         maxDropDownHeight: 300,         
                },
                'remove_button' : {},
            },
});
 $("#{$this->oField->GetGlobalId()}").closest('div').addClass('ibo-input-select-wrapper--with-buttons');
JS
						);
					}
			}

			$oBlock->AddDataAttribute("input-type", $sFieldClass);
			$oOutput->AddHtml((BlockRenderer::RenderBlockTemplates($oBlock)));
			// JS Form field widget construct
			$aValidators = array();
			foreach ($this->oField->GetValidators() as $oValidator) {
				if ($oValidator::GetName() == 'notemptyextkey') {
					// The autocomplete widget returns an empty string if the value is undefined (and the select has been aligned with this behavior)
					$oValidator = new MandatoryValidator();
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
			//TODO: escape html entities
			var sExplain = oResult.error_messages.join(', ');
			oValidationElement.html(sExplain);
			oValidationElement.addClass(' ibo-field-validation');
			 $(me.element).find('.ibo-input-field-wrapper').addClass("is-error");
		}
	}
}
EOF;

			$oOutput->AddJs(
				<<<EOF
                    $("[data-field-id='{$this->oField->GetId()}'][data-form-path='{$this->oField->GetFormPath()}']").form_field($sFormFieldOptions);
EOF
			);
		}
		switch ($sEditType)
		{
			case 'autocomplete':
			case 'radio':
			case 'select':
				$oOutput->AddJs(
					<<<EOF
	                    $("[data-field-id='{$this->oField->GetId()}'][data-form-path='{$this->oField->GetFormPath()}']").form_field('option', 'get_current_value_callback', function(me){ return $(me.element).find('#{$this->oField->GetGlobalId()}').val();});
EOF
				);
				break;


			case 'none':
			default:
				// Not editable
		}
		$oOutput->AddJs(
			<<<JS
                   $("[data-field-id='{$this->oField->GetId()}'][data-form-path='{$this->oField->GetFormPath()}']").trigger('validate');
JS
		);
		return $oOutput;
	}
}
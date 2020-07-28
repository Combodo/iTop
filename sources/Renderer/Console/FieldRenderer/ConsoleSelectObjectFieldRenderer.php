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

use Combodo\iTop\Form\Validator\MandatoryValidator;
use \Dict;
use \DBObjectSet;
use Combodo\iTop\Renderer\FieldRenderer;
use Combodo\iTop\Form\Field\SelectObjectField;

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

		$oOutput->AddHtml('<table class="form-field-container">');
		$oOutput->AddHtml('<tr>');
		if ($this->oField->GetLabel() != '')
		{
			$oOutput->AddHtml('<td class="form-field-label label"><span><label for="'.$this->oField->GetGlobalId().'">'.$this->oField->GetLabel().'</label></span></td>');
		}
		$oOutput->AddHtml('<td class="form-field-content">');
		$sEditType = 'none';
		if ($this->oField->GetReadOnly())
		{
			$oSearch = $this->oField->GetSearch()->DeepClone();
			$oSearch->AddCondition('id', $this->oField->GetCurrentValue());
			$oSet = new DBObjectSet($oSearch);
			$oObject = $oSet->Fetch();
			if ($oObject)
			{
				$sCurrentLabel = $oObject->Get('friendlyname');
			}
			else
			{
				$sCurrentLabel = '';
			}
			$oOutput->AddHtml('<input type="hidden" id="'.$this->oField->GetGlobalId().'" value="' . htmlentities($this->oField->GetCurrentValue(), ENT_QUOTES, 'UTF-8') . '"/>');
			$oOutput->AddHtml('<span class="form-field-data">'.htmlentities($sCurrentLabel, ENT_QUOTES, 'UTF-8').'</span>');
		}
		else
		{
			$oSearch = $this->oField->GetSearch()->DeepClone();
			$oSearch->SetModifierProperty('UserRightsGetSelectFilter', 'bSearchMode', true);

			$oSet = new \DBObjectSet($oSearch);
			$oSet->ApplyParameters();
			$oSet->OptimizeColumnLoad(array($oSearch->GetClassAlias() => array('friendlyname')));

			$sTargetClass = $oSearch->GetClass();
			$oAllowedValues = new DBObjectSet($oSearch);

			$iMaxComboLength = $this->oField->GetMaximumComboLength();
			$iCount = $oAllowedValues->Count();
			if ($iCount > $iMaxComboLength)
			{
				// Auto-complete
				//
				$sEditType = 'autocomplete';
				$aExtKeyParams = array();
				$aExtKeyParams['iFieldSize'] = 10;
				$aExtKeyParams['iMinChars'] = $this->oField->GetMinAutoCompleteChars();
				$sFieldName = $this->oField->GetGlobalId();
				$sFieldId = $sFieldName;
				$sFormPrefix = '';
				$oWidget = new \UIExtKeyWidget($sTargetClass, $sFieldId, '', true);
				$aArgs = array();
				$sDisplayStyle = 'select';
				$sTitle = $this->oField->GetLabel();
				require_once(APPROOT.'application/capturewebpage.class.inc.php');
				$oPage = new \CaptureWebPage();
				$sHTMLValue = $oWidget->Display($oPage, $iMaxComboLength, false /* $bAllowTargetCreation */, $sTitle, $oSet, $this->oField->GetCurrentValue(), $sFieldId, $this->oField->GetMandatory(), $sFieldName, $sFormPrefix, $aArgs, null, $sDisplayStyle);
				$oOutput->AddHtml($sHTMLValue);
				$oOutput->AddHtml($oPage->GetHtml());
				$oOutput->AddJs($oPage->GetJS());
				$oOutput->AddJs($oPage->GetReadyJS());
				foreach ($oPage->GetCSS() as $sCss)
				{
					$oOutput->AddCss($sCss);
				}
				foreach ($oPage->GetJSFiles() as $sFile)
				{
					$oOutput->AddJsFile($sFile);
				}
				foreach ($oPage->GetCSSFiles() as $sFile)
				{
					$oOutput->AddCssFile($sFile);
				}
			}
			elseif($this->oField->GetControlType() == SelectObjectField::CONTROL_RADIO_VERTICAL)
			{
				// Radio buttons (vertical)
				//
				$sEditType = 'radio';
				$bVertical = true;
				$idx = 0;
				$bMandatory = $this->oField->GetMandatory();
				$value = $this->oField->GetCurrentValue();
				$sId = $this->oField->GetGlobalId();
				$oOutput->AddHtml('<div>');
				while ($oObject = $oSet->Fetch())
				{
					$iObject = $oObject->GetKey();
					$sLabel = $oObject->Get('friendlyname');
					if (($iCount == 1) && $bMandatory)
					{
						// When there is only once choice, select it by default
						$sSelected = 'checked';
                        $value = $iObject;
					}
					else
					{
						$sSelected = ($value == $iObject) ? 'checked' : '';
					}
					$oOutput->AddHtml("<input type=\"radio\" id=\"{$sId}_{$iObject}\" name=\"radio_$sId\" onChange=\"$('#{$sId}').val(this.value).trigger('change');\" value=\"$iObject\" $sSelected><label class=\"radio\" for=\"{$sId}_{$iObject}\">&nbsp;".htmlentities($sLabel, ENT_QUOTES, 'UTF-8')."</label>&nbsp;");
					if ($bVertical)
					{
						$oOutput->AddHtml("<br>\n");
					}
					$idx++;
				}
				$oOutput->AddHtml('</div>');
				$oOutput->AddHtml("<input type=\"hidden\" id=\"$sId\" name=\"$sId\" value=\"$value\"/>");
			}
			else
			{
				// Drop-down select
				//
				$sEditType = 'select';
				$oOutput->AddHtml('<select class="form-field-data" id="'.$this->oField->GetGlobalId().'">');
				$oOutput->AddHtml('<option value="">'.Dict::S('UI:SelectOne').'</option>');
				while ($oObject = $oSet->Fetch())
				{
					$iObject = $oObject->GetKey();
					$sLabel = $oObject->Get('friendlyname');
					// Note : The test is a double equal on purpose as the type of the value received from the XHR is not always the same as the type of the allowed values. (eg : string vs int)
					$sSelectedAtt = ($this->oField->GetCurrentValue() == $iObject) ? 'selected' : '';
					$oOutput->AddHtml('<option value="'.$iObject.'" '.$sSelectedAtt.' >'.htmlentities($sLabel, ENT_QUOTES, 'UTF-8').'</option>');
				}
				$oOutput->AddHtml('</select>');
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
		}
		$oOutput->AddHtml('<span class="form_validation"></span>');
		$oOutput->AddHtml('</td>');

		$oOutput->AddHtml('</tr>');
		$oOutput->AddHtml('</table>');

		// JS Form field widget construct
		$aValidators = array();
		foreach ($this->oField->GetValidators() as $oValidator)
		{
			if ($oValidator::GetName() == 'notemptyextkey')
			{
				// The autocomplete widget returns an empty string if the value is undefined (and the select has been aligned with this behavior)
				$oValidator = new MandatoryValidator();
			}
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
		switch ($sEditType)
		{
			case 'autocomplete':
			case 'radio':
				$oOutput->AddJs(
					<<<EOF
	                    $("[data-field-id='{$this->oField->GetId()}'][data-form-path='{$this->oField->GetFormPath()}']").form_field('option', 'get_current_value_callback', function(me){ return $(me.element).find('#{$this->oField->GetGlobalId()}').val();});
EOF
				);
				break;
			case 'select':
				$oOutput->AddJs(
					<<<EOF
	                    $("[data-field-id='{$this->oField->GetId()}'][data-form-path='{$this->oField->GetFormPath()}']").form_field('option', 'get_current_value_callback', function(me){ return $(me.element).find('select').val();});
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
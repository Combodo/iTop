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

use MetaModel;
use utils;

/**
 * Description of BsSetFieldRenderer
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 */
class BsSetFieldRenderer extends BsFieldRenderer
{
    /**
     * @inheritDoc
     */
	public function Render()
	{
	    $oOutput = parent::Render();

		$sFieldMandatoryClass = ($this->oField->GetMandatory()) ? 'form_mandatory' : '';
		$sFieldDescriptionForHTMLTag = ($this->oField->HasDescription()) ? 'data-tooltip-content="'.utils::HtmlEntities($this->oField->GetDescription()).'"' : '';
		// Vars to build the table
//		$sAttributesToDisplayAsJson = json_encode($this->oField->GetAttributesToDisplay());
//		$sAttCodesToDisplayAsJson = json_encode($this->oField->GetAttributesToDisplay(true));
//		$aItems = array();
//		$aItemIds = array();
//		$this->PrepareItems($aItems, $aItemIds);
//		$sItemsAsJson = json_encode($aItems);
//        $sItemIdsAsJson = htmlentities(json_encode(array('current' => $aItemIds)), ENT_QUOTES, 'UTF-8');

		// Rendering field
		if (!$this->oField->GetHidden())
		{
			/** @var \ormSet $oOrmItemSet */
			$oOrmItemSet = $this->oField->GetCurrentValue();

			// Opening container
			$oOutput->AddHtml('<div class="form-group form_group_small ' . $sFieldMandatoryClass . '">');

			// Label
			$oOutput->AddHtml('<div class="form_field_label">');
			if ($this->oField->GetLabel() !== '')
			{
				$oOutput->AddHtml('<label for="' . $this->oField->GetGlobalId() . '" class="control-label" '.$sFieldDescriptionForHTMLTag.'>')
						->AddHtml($this->oField->GetLabel(), true)
						->AddHtml('</label>');
			}
			$oOutput->AddHtml('</div>');

			// Value
			$oOutput->AddHtml('<div class="form_field_control">');
			// ... in edit mode
			if(!$this->oField->GetReadOnly())
			{
				$oAttDef = MetaModel::GetAttributeDef($oOrmItemSet->GetClass(), $oOrmItemSet->GetAttCode());
				$sJSONForWidget = $oAttDef->GetJsonForWidget($oOrmItemSet);

				// - Help block
				$oOutput->AddHtml('<div class="help-block"></div>');

				// - Value regarding the field type
				$oOutput->AddHtml('<input type="hidden" id="' . $this->oField->GetGlobalId() . '" name="' . $this->oField->GetId() . '" value="')
						->AddHtml($sJSONForWidget, true)
						->AddHtml('" class="form-control" />');

				// Attaching JS widget only if field is hidden or NOT read only
				// JS Form field widget construct
				$aValidators = array();
				$sValidators = json_encode($aValidators);
				$oOutput->AddJs(
<<<EOF
    $("[data-field-id='{$this->oField->GetId()}'][data-form-path='{$this->oField->GetFormPath()}']").portal_form_field_set({
        validators: $sValidators,
        // Overloading default callback as the Selectize widget adds several inputs and we want to retrieve only the one with the value.
        get_current_value_callback: function(me, oEvent, oData){
			var value = null;

			// Retrieving JSON value as a string and not an object
			value = me.element.find('#{$this->oField->GetGlobalId()}').val();

			return value;
		},
    });
EOF
				);
			}
			// ... in view mode
			else
			{
				if ($oOrmItemSet instanceof \ormTagSet) {
					$aItems = $oOrmItemSet->GetTags();
					$fExtractTagData = static function($oTag, &$sItemLabel, &$sItemDescription) {
						$sItemLabel = $oTag->Get('label');
						$sItemDescription = $oTag->Get('description');
					};
				} else {
					$aItems = $oOrmItemSet->GetValues();
					$oAttDef = MetaModel::GetAttributeDef($oOrmItemSet->GetClass(), $oOrmItemSet->GetAttCode());
					$fExtractTagData = static function($sEnumSetValue, &$sItemLabel, &$sItemDescription) use ($oAttDef) {
						$sItemLabel = $oAttDef->GetValueLabel($sEnumSetValue);
						$sItemDescription = '';
					};
				}

				$oOutput->AddHtml('<div class="form-control-static">')
						->AddHtml('<span class="label-group">');
				foreach($aItems as $sItemCode => $value)
				{
					$fExtractTagData($value, $sItemLabel, $sItemDescription);

					$sDescriptionAttr = (empty($sItemDescription))
						? ''
						: ' data-description="'.utils::HtmlEntities($sItemDescription).'"';

					$oOutput->AddHtml('<span class="label label-default" data-code="'.$sItemCode.'" data-label="')
							->AddHtml($sItemLabel, true)
							->AddHtml('"')
							->AddHtml($sDescriptionAttr)
							->AddHtml('>')
							->AddHtml($sItemLabel, true)
							->AddHtml('</span>');
				}
				$oOutput->AddHtml('</span>')
						->AddHtml('</div>');
			}
			$oOutput->AddHtml('</div>');
		}

		return $oOutput;
	}
}

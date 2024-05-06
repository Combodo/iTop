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

use ApplicationContext;
use Combodo\iTop\Renderer\RenderingOutput;
use ContextTag;
use CoreException;
use DBObjectSet;
use Dict;
use Exception;
use IssueLog;
use MetaModel;
use utils;

/**
 * Description of BsSelectObjectFieldRenderer
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 *
 * @property \Combodo\iTop\Form\Field\SelectObjectField $oField
 */
class BsSelectObjectFieldRenderer extends BsFieldRenderer
{

	/**
	 * @inheritDoc
	 */
	public function Render()
	{
		$oOutput = parent::Render();

		$sFieldValueClass = $this->oField->GetSearch()->GetClass();
		$sFieldMandatoryClass = ($this->oField->GetMandatory()) ? 'form_mandatory' : '';
		$iFieldControlType = $this->oField->GetControlType();
		$sFieldDescriptionForHTMLTag = ($this->oField->HasDescription()) ? 'data-tooltip-content="'.utils::HtmlEntities($this->oField->GetDescription()).'"' : '';

		// TODO : Remove this when hierarchical search supported
		$this->oField->SetHierarchical(false);

		// Rendering field in edition mode
		if (!$this->oField->GetReadOnly() && !$this->oField->GetHidden())
		{
			// Debug trace: This is very useful when this kind of field doesn't return the expected values.
			if(ContextTag::Check('debug'))
			{
				IssueLog::Info('Form field #'.$this->oField->GetId().' OQL query: '.$this->oField->GetSearch()->ToOQL(true));
			}

			// Rendering field
			// - Opening container
			$oOutput->AddHtml('<div class="form-group form_group_small ' . $sFieldMandatoryClass . '">');

			// Label
			$oOutput->AddHtml('<div class="form_field_label">');
			if ($this->oField->GetLabel() !== '')
			{
				$oOutput->AddHtml('<label for="' . $this->oField->GetGlobalId() . '" class="control-label" '.$sFieldDescriptionForHTMLTag.'>')->AddHtml($this->oField->GetLabel(), true)->AddHtml('</label>');
			}
			$oOutput->AddHtml('</div>');

			// Value
			$oOutput->AddHtml('<div class="form_field_control">');
			$oOutput->AddHtml('<div class="help-block"></div>');
			// - As a select
			// TODO : This should be changed when we do the radio button display. For now we display everything with select
			//if ($iFieldControlType === SelectObjectField::CONTROL_SELECT)
			if (true)
			{
				// Checking if regular select or autocomplete
				$oSearch = $this->oField->GetSearch()->DeepClone();
				$oCountSet = new DBObjectSet($oSearch);
				$iSetCount = $oCountSet->Count();
				// Note : Autocomplete/Search is disabled for template fields as they are not external keys, thus they will just be displayed as regular select.
				$bRegularSelect = ( ($iSetCount < $this->oField->GetMaximumComboLength()) || ($this->oField->GetSearchEndpoint() === null) || ($this->oField->GetSearchEndpoint() === '') );
				unset($oCountSet);

				// - For regular select
				if ($bRegularSelect)
				{
					// HTML for select part
					// - Opening row
					$oOutput->AddHtml('<div class="row">');
					// - Rendering select
					$oOutput->AddHtml('<div class="col-xs-' . ( $this->oField->GetHierarchical() ? 10 : 12 ) . ' col-sm-' . ( $this->oField->GetHierarchical() ? 9 : 12 ) . ' col-md-' . ( $this->oField->GetHierarchical() ? 10 : 12 ) . '">');
					$oOutput->AddHtml('<select id="' . $this->oField->GetGlobalId() . '" name="' . $this->oField->GetId() . '" class="form-control">');
					$oOutput->AddHtml('<option value="">')->AddHtml(Dict::S('UI:SelectOne'), false)->AddHtml('</option>');
					// - Retrieving choices
					$oChoicesSet = new DBObjectSet($oSearch);
					$oChoicesSet->OptimizeColumnLoad(array($oSearch->GetClassAlias() => array('friendlyname')));
					while ($oChoice = $oChoicesSet->Fetch())
					{
						// Note : The test is a double equal on purpose as the type of the value received from the XHR is not always the same as the type of the allowed values. (eg : string vs int)
						$sSelectedAtt = ($this->oField->GetCurrentValue() == $oChoice->GetKey()) ? 'selected' : '';
						$oOutput->AddHtml('<option value="' . $oChoice->GetKey() . '" ' . $sSelectedAtt . ' >')->AddHtml($oChoice->GetName(), false)->AddHtml('</option>');
					}
					unset($oChoicesSet);
					$oOutput->AddHtml('</select>');
					$oOutput->AddHtml('</div>');
					// - Closing col for autocomplete & opening col for hierarchy, rendering hierarchy button, closing col and row
					$oOutput->AddHtml('<div class="col-xs-' . ( $this->oField->GetHierarchical() ? 2 : 0 ) . ' col-sm-' . ( $this->oField->GetHierarchical() ? 3 : 0 ) . ' col-md-' . ( $this->oField->GetHierarchical() ? 2 : 0 ) . ' text-right">');
					$this->RenderHierarchicalSearch($oOutput);
					$oOutput->AddHtml('</div>');
					// - Closing row
					$oOutput->AddHtml('</div>');

					// JS FieldChange trigger (:input are not always at the same depth)
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

					// Attaching JS widget
					$oOutput->AddJs(
<<<EOF
						$("[data-field-id='{$this->oField->GetId()}'][data-form-path='{$this->oField->GetFormPath()}']").portal_form_field({
							'validators': {$this->GetValidatorsAsJson()}
						});
EOF
					);
				}
				// - For autocomplete
				else
				{
					$sAutocompleteFieldId = 's_ac_' . $this->oField->GetGlobalId();
					$sEndpoint = str_replace('-sMode-', 'autocomplete', $this->oField->GetSearchEndpoint());
					$sNoResultText = Dict::S('Portal:Autocomplete:NoResult');

					// Retrieving field value
					$currentValue = $this->oField->GetCurrentValue();
					if (!empty($currentValue))
					{
						try
						{
							// Note : AllowAllData set to true here instead of checking scope's flag because we are displaying a value that has been set and validated
							$oFieldValue = MetaModel::GetObject($sFieldValueClass, $this->oField->GetCurrentValue(), true, true);
						}
						catch (CoreException $e)
						{
							IssueLog::Error('Could not retrieve object ' . $sFieldValueClass . '::' . $this->oField->GetCurrentValue() . ' for "' . $this->oField->GetId() . '" field.');
							throw new Exception($e->getMessage(), $e->getCode(), $e->getPrevious());
						}
						$sFieldValue = $oFieldValue->GetName();
					}
					else
					{
						$sFieldValue = '';
					}

					// HTML for autocomplete part
					// - Opening input group
					$oOutput->AddHtml('<div class="input-group selectobject">');
					// - Rendering autocomplete search
					$oOutput->AddHtml('<input type="text" id="' . $sAutocompleteFieldId . '" name="' . $sAutocompleteFieldId . '" value="')->AddHtml($sFieldValue)->AddHtml('" data-target-id="' . $this->oField->GetGlobalId() . '" class="form-control" />');
					$oOutput->AddHtml('<input type="hidden" id="' . $this->oField->GetGlobalId() . '" name="' . $this->oField->GetId() . '" value="' . $this->oField->GetCurrentValue() . '" />');
					// - Rendering buttons
					//   - Rendering hierarchy button
					$this->RenderHierarchicalSearch($oOutput);
					//   - Rendering regular search
					$this->RenderRegularSearch($oOutput);
					// - Closing input group
					$oOutput->AddHtml('</div>');

					// JS FieldChange trigger (:input are not always at the same depth)
					// Note : Not used for that field type
					// Attaching JS widget
					$oOutput->AddJs(
<<<EOF
					$("[data-field-id='{$this->oField->GetId()}'][data-form-path='{$this->oField->GetFormPath()}']").portal_form_field({
						'validators': {$this->GetValidatorsAsJson()},
						'get_current_value_callback': function(me, oEvent, oData){
							var value = null;

							value = me.element.find('#{$this->oField->GetGlobalId()}').val();

							return value;
						},
						'set_current_value_callback': function(me, oEvent, oData){
							var sItemId = Object.keys(oData.value)[0];
							var sItemName = oData.value[sItemId];

							// Updating autocomplete field
							me.element.find('#{$this->oField->GetGlobalId()}').val(sItemId);
							me.element.find('#{$sAutocompleteFieldId}').val(sItemName);
							oAutocompleteSource_{$this->oField->GetId()}.index.datums[sItemId] = {id: sItemId, name: sItemName};							
							$('#$sAutocompleteFieldId').typeahead('val', sItemName);							
//console.log('callback', oData);
							// Triggering field change event
							me.element.closest(".field_set").trigger("field_change", {
								id: me.element.find('#{$this->oField->GetGlobalId()}').attr("id"),
								name: me.element.find('#{$this->oField->GetGlobalId()}').attr("name"),
								value: me.element.find('#{$this->oField->GetGlobalId()}').val()
							});							
						}
					});
EOF
					);

					// Preparing JS part for autocomplete
					$oOutput->AddJs(
<<<EOF
						var oAutocompleteSource_{$this->oField->GetId()} = new Bloodhound({
							queryTokenizer: Bloodhound.tokenizers.whitespace,
							datumTokenizer: Bloodhound.tokenizers.whitespace,
							remote: {
								url : '{$sEndpoint}',
								prepare: function(query, settings){
									settings.type = "POST";
									settings.contentType = "application/json; charset=UTF-8";
									settings.data = {
											sQuery: query,
											sFormPath: '{$this->oField->GetFormPath()}',
											sFieldId: '{$this->oField->GetId()}',
											formmanager_class: $("[data-field-id='{$this->oField->GetId()}'][data-form-path='{$this->oField->GetFormPath()}']").closest('.portal_form_handler').portal_form_handler('getOptions').formmanager_class,
											formmanager_data: $("[data-field-id='{$this->oField->GetId()}'][data-form-path='{$this->oField->GetFormPath()}']").closest('.portal_form_handler').portal_form_handler('getOptions').formmanager_data,
											current_values: $("[data-field-id='{$this->oField->GetId()}'][data-form-path='{$this->oField->GetFormPath()}']").closest('.portal_form_handler').portal_form_handler('getCurrentValues')
									}
									return settings;
								},
								filter: function(response){
									var oItems = response.results.items;
									// Manualy adding data from remote to the index.datums so we can check data later
									for(var sItemKey in oItems)
									{
										oAutocompleteSource_{$this->oField->GetId()}.index.datums[oItems[sItemKey].id] = oItems[sItemKey];
									}
									return oItems;
								}
							}
						});

						// This check is only for IE9... Otherwise the widget is duplicated on the field causing misbehaviour.
						if($('#$sAutocompleteFieldId').typeahead('val') === undefined)
						{
							$('#$sAutocompleteFieldId').typeahead({
								hint: true,
								hightlight: true,
								minLength: {$this->oField->GetMinAutoCompleteChars()}
							},{
								name: '{$this->oField->GetId()}',
								source: oAutocompleteSource_{$this->oField->GetId()},
								limit: {$this->oField->GetMaxAutoCompleteResults()},
								display: 'name',
								templates: {
									suggestion: Handlebars.compile('<div>{{name}}</div>'),
									pending: $("#page_overlay .content_loader").prop('outerHTML'),
									notFound: '<div class="no_result">{$sNoResultText}</div>'
								}
							})
							.off('typeahead:select').on('typeahead:select', function(oEvent, oSuggestion){
								$('#{$this->oField->GetGlobalId()}').val(oSuggestion.id);
								$('#{$sAutocompleteFieldId}').val(oSuggestion.name);
								// Triggering set_current_value event
								var oValue = {};
								oValue[oSuggestion.id] = oSuggestion.name;
								$("[data-field-id='{$this->oField->GetId()}'][data-form-path='{$this->oField->GetFormPath()}']").trigger('set_current_value', {value: oValue});
							})
							.off('typeahead:change').on('typeahead:change', function(oEvent, oSuggestion){
								// Checking if the value is a correct value. This is necessary because the user could empty the field / remove some chars and typeahead would not update the hidden input
								var oDatums = oAutocompleteSource_{$this->oField->GetId()}.index.datums;
								var bFound = false;
								for(var i in oDatums)
								{
									if(oDatums[i].name == oSuggestion)
									{
										bFound = true;
										$('#{$this->oField->GetGlobalId()}').val(oDatums[i].id);
										$('#{$sAutocompleteFieldId}').val(oDatums[i].name);
										break;
									}
								}
								// Emptying the fields if value is incorrect
								if(!bFound)
								{
									$('#{$this->oField->GetGlobalId()}').val(0);
									$('#{$sAutocompleteFieldId}').val('');
								}
							});
						}
EOF
					);
				}
			}
			$oOutput->AddHtml('</div>');

			// - Closing container
			$oOutput->AddHtml('</div>');
		}
		// ... and in read-only mode (or hidden)
		else
		{
			// Retrieving field value
			if ($this->oField->GetCurrentValue() !== null && $this->oField->GetCurrentValue() !== 0 && $this->oField->GetCurrentValue() !== '')
			{
				// Note : AllowAllData set to true here instead of checking scope's flag because we are displaying a value that has been set and validated
				$oFieldValue = MetaModel::GetObjectWithArchive($sFieldValueClass, $this->oField->GetCurrentValue(), true, true);
				$sFieldHtmlValue = $oFieldValue->GetName();
				if($oFieldValue->IsArchived())
				{
					$sFieldHtmlValue = '<span class="text_decoration"><span class="fas fa-archive"></span></span>' . $sFieldHtmlValue;
				}
				else
				{
					$sFieldUrl = ApplicationContext::MakeObjectUrl($sFieldValueClass, $this->oField->GetCurrentValue());
					if (!empty($sFieldUrl))
					{
						$sFieldHtmlValue = '<a href="' . $sFieldUrl . '" data-toggle="itop-portal-modal">' . $sFieldHtmlValue . '</a>';
					}
				}
			}
			else
			{
				$sFieldHtmlValue = Dict::S('UI:UndefinedObject');
			}

			// Opening container
			$oOutput->AddHtml('<div class="form-group form_group_small">');

			// Showing label / value only if read-only but not hidden
			if (!$this->oField->GetHidden())
			{
				// Label
				$oOutput->AddHtml('<div class="form_field_label">');
				if ($this->oField->GetLabel() !== '')
				{
					$oOutput->AddHtml('<label for="' . $this->oField->GetGlobalId() . '" class="control-label" '.$sFieldDescriptionForHTMLTag.'>')->AddHtml($this->oField->GetLabel(), true)->AddHtml('</label>');
				}
				$oOutput->AddHtml('</div>');

				// Value
				$oOutput->AddHtml('<div class="form_field_control">');
				$oOutput->AddHtml('<div class="form-control-static">'.$sFieldHtmlValue.'</div>');
				$oOutput->AddHtml('</div>');
			}

			// Adding hidden value
			$oOutput->AddHtml('<input type="hidden" id="' . $this->oField->GetGlobalId() . '" name="' . $this->oField->GetId() . '" value="' . $this->oField->GetCurrentValue() . '" class="form-control" />');

			// Closing container
			$oOutput->AddHtml('</div>');
		}

		return $oOutput;
	}

	/**
	 * Renders an hierarchical search button
	 *
	 * @param RenderingOutput $oOutput
	 */
	protected function RenderHierarchicalSearch(RenderingOutput &$oOutput)
	{
		if ($this->oField->GetHierarchical())
		{
			$sHierarchicalButtonId = 's_hi_' . $this->oField->GetGlobalId();
			$sEndpoint = str_replace('-sMode-', 'hierarchy', $this->oField->GetSearchEndpoint());

			$oOutput->AddHtml('<div class="input-group-addon" id="' . $sHierarchicalButtonId . '"><span class="fas fa-sitemap"></span></div>');

			$oOutput->AddJs(
<<<JS
				$('#{$sHierarchicalButtonId}').off('click').on('click', function(){
					// Creating a new modal
					CombodoModal.OpenModal({
						attributes: {
							'data-source-element': '{$sHierarchicalButtonId}',
						},
						content: {
							endpoint: '{$sEndpoint}',
							data: {
								sFormPath: '{$this->oField->GetFormPath()}',
								sFieldId: '{$this->oField->GetId()}',
							},
						},
						size: 'sm',
					});
				});
JS
			);
		}
	}

	/**
	 * Renders an regular search button
	 *
	 * @param RenderingOutput $oOutput
	 */
	protected function RenderRegularSearch(RenderingOutput &$oOutput)
	{
		$sSearchButtonId = 's_rg_' . $this->oField->GetGlobalId();
		$sEndpoint = str_replace('-sMode-', 'from-attribute', $this->oField->GetSearchEndpoint());

		$oOutput->AddHtml('<div class="input-group-addon" id="' . $sSearchButtonId . '"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></div>');

		$oOutput->AddJs(
<<<JS
			$('#{$sSearchButtonId}').off('click').on('click', function(){
				// Creating a new modal
				var oOptions =
				{
					content: {
						endpoint: '{$sEndpoint}',
						data: {
							sFormPath: '{$this->oField->GetFormPath()}',
							sFieldId: '{$this->oField->GetId()}',
							formmanager_class: $(this).closest('.portal_form_handler').portal_form_handler('getOptions').formmanager_class,
							formmanager_data: JSON.stringify($(this).closest('.portal_form_handler').portal_form_handler('getOptions').formmanager_data),
							current_values: $(this).closest('.portal_form_handler').portal_form_handler('getCurrentValues')
						},
					},
				};

				if($('.modal[data-source-element="{$sSearchButtonId}"]').length === 0)
				{
					oOptions['attributes'] = {'data-source-element': '{$sSearchButtonId}'};
				}
				else
				{
					oOptions['base_modal'] = {
						'usage': 'replace',
						'selector': '.modal[data-source-element="{$sSearchButtonId}"]:first'
					};
				}
				CombodoModal.OpenModal(oOptions);
			});
JS
		);
	}

}

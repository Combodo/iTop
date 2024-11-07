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
use AttributeFriendlyName;
use Combodo\iTop\Form\Field\DateTimeField;
use Combodo\iTop\Form\Field\Field;
use Combodo\iTop\Portal\Helper\ApplicationHelper;
use Combodo\iTop\Renderer\Bootstrap\BsFieldRendererMappings;
use Combodo\iTop\Renderer\FieldRenderer;
use Combodo\iTop\Renderer\RenderingOutput;
use DBObject;
use Dict;
use Exception;
use IssueLog;
use MetaModel;
use utils;

/**
 * Description of BsLinkedSetFieldRenderer
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * 
 * @property \Combodo\iTop\Form\Field\LinkedSetField $oField 
 * 
 */
class BsLinkedSetFieldRenderer extends BsFieldRenderer
{
    /**
     * @inheritDoc
     */
	public function Render()
	{
		$oOutput = parent::Render();

		$sFieldMandatoryClass = ($this->oField->GetMandatory()) ? 'form_mandatory' : '';
		$sFieldDescriptionForHTMLTag = ($this->oField->HasDescription()) ? 'data-tooltip-content="'.utils::HtmlEntities($this->oField->GetDescription()).'"' : '';

		// Retrieve link and remote attributes
		$aAttributesToDisplay = $this->oField->GetAttributesToDisplay();
		$aLnkAttributesToDisplay = $this->oField->GetLnkAttributesToDisplay();

		// we sort the table on the first non link column
		$iSortColumnIndex = count($this->oField->GetLnkAttributesToDisplay());
		// if we are in edition mode, we skip the first column (selection checkbox column)
		if(!$this->oField->GetReadOnly()){
			$iSortColumnIndex++;
		}

		// Vars to build the table
		$sAttributesToDisplayAsJson = json_encode($aAttributesToDisplay);
		$sLnkAttributesToDisplayAsJson = json_encode($aLnkAttributesToDisplay);
		$sAttCodesToDisplayAsJson = json_encode($this->oField->GetAttributesToDisplay(true));
		$sLnkAttCodesToDisplayAsJson = json_encode($this->oField->GetLnkAttributesToDisplay(true));

		$aItems = array();
		$aItemIds = array();
		$aAddedItemIds = array();
		$aAddedTargetIds = array();
		$this->InjectRendererFileAssets($this->oField->GetLinkedClass(), $this->oField->GetLnkAttributesToDisplay(true), $oOutput);
		$this->PrepareItems($aItems, $aItemIds, $oOutput, $aAddedItemIds, $aAddedTargetIds);
		$sItemsAsJson = json_encode($aItems);
		$sItemIdsAsJson = utils::EscapeHtml(json_encode(array('current' => $aItemIds, 'add' => $aAddedItemIds)));

		foreach ($aAddedTargetIds as $sId) {
			$aItemIds[$sId] = array();
		}

		if (!$this->oField->GetHidden()) {
			// Rendering field
			$sIsEditable = ($this->oField->GetReadOnly()) ? 'false' : 'true';
			$sCollapseTogglerIconVisibleClass = 'glyphicon-menu-down';
			$sCollapseTogglerIconHiddenClass = 'glyphicon-menu-down collapsed';
			$sCollapseTogglerClass = 'form_linkedset_toggler';
			$sCollapseTogglerId = $sCollapseTogglerClass.'_'.$this->oField->GetGlobalId();
			$sFieldWrapperId = 'form_linkedset_wrapper_'.$this->oField->GetGlobalId();

			// Preparing collapsed state
			if ($this->oField->GetDisplayOpened()) {
				$sCollapseTogglerExpanded = 'true';
				$sCollapseTogglerIconClass = $sCollapseTogglerIconVisibleClass;
				$sCollapseJSInitState = 'true';
			} else {
				$sCollapseTogglerClass .= ' collapsed';
				$sCollapseTogglerExpanded = 'false';
				$sCollapseTogglerIconClass = $sCollapseTogglerIconHiddenClass;
				$sCollapseJSInitState = 'false';
			}

			$oOutput->AddHtml('<div class="form-group '.$sFieldMandatoryClass.'">');
			if ($this->oField->GetLabel() !== '') {
				$oOutput->AddHtml('<label for="'.$this->oField->GetGlobalId().'" class="control-label" '.$sFieldDescriptionForHTMLTag.'>')
					->AddHtml('<a id="'.$sCollapseTogglerId.'" class="'.$sCollapseTogglerClass.'" data-toggle="collapse" href="#'.$sFieldWrapperId.'" aria-expanded="'.$sCollapseTogglerExpanded.'" aria-controls="'.$sFieldWrapperId.'">')
					->AddHtml($this->oField->GetLabel(), true)
					->AddHtml('<span class="text">'.count($aItemIds).'</span>')
					->AddHtml('<span class="glyphicon '.$sCollapseTogglerIconClass.'"></>')
					->AddHtml('</a>')
					->AddHtml('</label>');
			}
			$oOutput->AddHtml('<div class="help-block"></div>');

			// Rendering table
			// - Vars
			$sTableId = 'table_' . $this->oField->GetGlobalId();
			// - Output
			$oOutput->AddHtml(
				<<<EOF
				<div class="form_linkedset_wrapper collapse" id="{$sFieldWrapperId}">
					<div class="row">
						<div class="col-xs-12">
							<input type="hidden" id="{$this->oField->GetGlobalId()}" name="{$this->oField->GetId()}" value="{$sItemIdsAsJson}" />
							<table id="{$sTableId}" data-field-id="{$this->oField->GetId()}" class="table table-striped table-bordered responsive" cellspacing="0" width="100%">
								<tbody>
								</tbody>
							</table>
						</div>
					</div>
EOF
			);

			// Rendering table widget
			// - Vars
			$sEmptyTableLabel = utils::EscapeHtml(Dict::S(($this->oField->GetReadOnly()) ? 'Portal:Datatables:Language:EmptyTable' : 'UI:Message:EmptyList:UseAdd'));
			$sLabelGeneralCheckbox = utils::EscapeHtml(Dict::S('Core:BulkExport:CheckAll').' / '.Dict::S('Core:BulkExport:UncheckAll'));
			$sSelectionOptionHtml = ($this->oField->GetReadOnly()) ? 'false' : '{"style": "multi"}';
			$sSelectionInputGlobalHtml = ($this->oField->GetReadOnly()) ? '' : '<span class="row_input"><input type="checkbox" id="'.$this->oField->GetGlobalId().'_check_all" name="'.$this->oField->GetGlobalId().'_check_all" title="'.$sLabelGeneralCheckbox.'" /></span>';
			$sSelectionInputHtml = ($this->oField->GetReadOnly()) ? '' : '<span class="row_input"><input type="checkbox" data-type="row-selection" name="'.$this->oField->GetGlobalId().'" /></span>';
			// - Output
			$oOutput->AddJs(
				<<<JS
				// Collapse handlers
				// - Collapsing by default to optimize form space
				// It would be better to be able to construct the widget as collapsed, but in this case, datatables thinks the container is very small and therefore renders the table as if it was in microbox.
				$('#{$sFieldWrapperId}').collapse({toggle: {$sCollapseJSInitState}});
				// - Change toggle icon class
				$('#{$sFieldWrapperId}').on('shown.bs.collapse', function(){
					// Creating the table if null (first expand). If we create it on start, it will be displayed as if it was in a micro screen due to the div being "display: none;"
					if(oTable_{$this->oField->GetGlobalId()} === undefined)
					{
						buildTable_{$this->oField->GetGlobalId()}();
					}
				})
				.on('show.bs.collapse', function(){
					$('#{$sCollapseTogglerId} > span.glyphicon').removeClass('{$sCollapseTogglerIconHiddenClass}').addClass('{$sCollapseTogglerIconVisibleClass}');
				})
				.on('hide.bs.collapse', function(){
					$('#{$sCollapseTogglerId} > span.glyphicon').removeClass('{$sCollapseTogglerIconVisibleClass}').addClass('{$sCollapseTogglerIconHiddenClass}');
				});

				// Places a loader in the empty datatables
				$('#{$sTableId} > tbody').html('<tr><td class="datatables_overlay" colspan="100">' + $('#page_overlay').html() + '</td></tr>');

				// Prepares data for datatables
				var oLnkColumnProperties_{$this->oField->GetGlobalId()} = {$sLnkAttributesToDisplayAsJson};
				var oColumnProperties_{$this->oField->GetGlobalId()} = {$sAttributesToDisplayAsJson};
				var oRawDatas_{$this->oField->GetGlobalId()} = {$sItemsAsJson};
				var oTable_{$this->oField->GetGlobalId()};
				var oSelectedItems_{$this->oField->GetGlobalId()} = {};
                var oRenderersJs_{$this->oField->GetGlobalId()} = '';

				var getColumnsDefinition_{$this->oField->GetGlobalId()} = function()
				{
					var aColumnsDefinition = [];

					if({$sIsEditable})
					{
						aColumnsDefinition.push({
								"width": "auto",
								"searchable": false,
								"sortable": false,
								"title": '{$sSelectionInputGlobalHtml}',
								"type": "html",
								"data": "id",
								"render": function(data, type, row)
								{
									var oCheckboxElem = $('{$sSelectionInputHtml}');
                                    if(row.limited_access)
									{
										oCheckboxElem.html('-');
									}
									else
									{
										oCheckboxElem.find(':input').attr('data-object-id', row.id).attr('data-target-object-id', row.target_id);
									}
									return oCheckboxElem.prop('outerHTML');
								}
						});
					}

					for(sKey in oLnkColumnProperties_{$this->oField->GetGlobalId()})
					{
                        aColumnProperties = oLnkColumnProperties_{$this->oField->GetGlobalId()}[sKey];
                                                 
						// Level main column
						aColumnsDefinition.push({
							"width": "auto",
							"searchable": true,
							"sortable": false,
							"title": aColumnProperties.label,
							"defaultContent": "",
							"type": "html",
							"data": "attributes.lnk__" + sKey,
							"className": {$sIsEditable} && aColumnProperties.mandatory ? 'mandatory' : '',
							"render": function(data, type, row){
								var cellElem;
                                var metadataNames = ['object_class', 'object_id', 'attribute_code', 'attribute_type', 'value_raw'];
									
								// Preparing the cell data
								if(data.url !== undefined)
								{
									cellElem = $('<a></a>');
									cellElem.attr('href', data.url);
								}
								else
								{
									cellElem = $('<span></span>');
								}
								for(var sPropName in row.attributes[data.prefix+data.attribute_code])
			                    {
			                        var propValue = row.attributes[data.prefix+data.attribute_code][sPropName];
			                        if(sPropName === 'value_html')
			                        {
			                            cellElem.html(propValue);
			                        }
			                        else if(metadataNames.indexOf(sPropName) > -1)
			                        {
			                            cellElem.attr('data-'+sPropName.replace('_', '-'), propValue)
			                        }
			                    }
                                                                
								return cellElem.prop('outerHTML');
							},
						});
					}
                    
                    for(sKey in oColumnProperties_{$this->oField->GetGlobalId()})
					{
                        aColumnProperties = oColumnProperties_{$this->oField->GetGlobalId()}[sKey];
						// Level main column
						aColumnsDefinition.push({
							"width": "auto",
							"searchable": true,
							"sortable": true,
							"title": aColumnProperties.label,
							"defaultContent": "",
							"type": "html",
							"data": "attributes." + sKey,
							"className": aColumnProperties.mandatory ? 'mandatory' : '',
							"render": function(data, type, row){
								var cellElem;                                
                                var metadataNames = ['object_class', 'object_id', 'attribute_code', 'attribute_type', 'value_raw'];
                                
								// Preparing the cell data
								if(data.url !== undefined)
								{
									cellElem = $('<a></a>');
									cellElem.attr('href', data.url);
								}
								else
								{
									cellElem = $('<span></span>');
								}
								
								for(var sPropName in row.attributes[data.attribute_code])
			                    {
			                     	var propValue = row.attributes[data.attribute_code][sPropName];
			                        if(sPropName === 'value_html')
			                        {
			                            cellElem.html(propValue);
			                        }
			                        else if(metadataNames.indexOf(sPropName) > -1)
			                        {
			                            cellElem.attr('data-'+sPropName.replace('_', '-'), propValue)
			                        }
			                    }
                                
								return cellElem.prop('outerHTML');
							},
						});
					}

					return aColumnsDefinition;
				};

				// Helper to build the datatable
				// Note : Those options should be externalized in an library so we can use them on any DataTables for the portal.
				// We would just have to override / complete the necessary elements
				var buildTable_{$this->oField->GetGlobalId()} = function()
				{
					var iDefaultOrderColumnIndex = {$iSortColumnIndex};

					// Instantiates datatables
					oTable_{$this->oField->GetGlobalId()} = $('#{$sTableId}').DataTable({
						"language": {
							"emptyTable":	  "{$sEmptyTableLabel}"
						},
						"displayLength": -1,
						"scrollY": "300px",
						"scrollCollapse": true,
						"retrieve": true,
						"order": [[iDefaultOrderColumnIndex, "asc"]],
						"dom": 't',
						"columns": getColumnsDefinition_{$this->oField->GetGlobalId()}(),
						"select": {$sSelectionOptionHtml},
						"rowId": "id",
						"data": oRawDatas_{$this->oField->GetGlobalId()},
						"rowCallback": function(oRow, oData){
							if(oData.limited_access)
							{
								$(oRow).addClass('limited_access');
							}
							// Opening in a new modal on click
							$(oRow).find('a').off('click').on('click', function(oEvent){
								// Prevents link opening.
								oEvent.preventDefault();
								// Prevents row selection
								oEvent.stopPropagation();
								
								// Note : This could be better if we check for an existing modal first instead of always creating a new one
								CombodoModal.OpenModal({
									content: {
										endpoint: $(this).attr('href'),
									},
								});
							});
                            
                            // Prevent row selection on input click
                            $('input,select,textarea,.input-group-addon', oRow).on('click', function(oEvent){
                                if($(this).data('type') !== 'row-selection'){
                                    // Prevents row selection
									oEvent.stopPropagation();
                                }
                            });
                            
                            // Store attributes inline css and js
                            for (var key in oData.attributes) {
							 	const aElement = oData.attributes[key];
                              	if(aElement.css_inline !== undefined){
                              		$('td:first-child', oRow).append($('<style>' + aElement.css_inline + '</style>'));
                                }
	                            if(aElement.js_inline !== undefined){
                                    oRenderersJs_{$this->oField->GetGlobalId()} += aElement.js_inline;
	                            }
							}

						},
                        "initComplete": function(){
                            
                            // Execute inline js provided by attributes renderers
                            eval(oRenderersJs_{$this->oField->GetGlobalId()});
                            
                        },
					});
						
					// Handles items selection/deselection
					// - Preventing limited access rows to be selected on click
					oTable_{$this->oField->GetGlobalId()}.off('user-select').on('user-select', function(oEvent, dt, type, cell, originalEvent){
						if($(originalEvent.target).closest('tr[id]').hasClass('limited_access'))
						{
							oEvent.preventDefault();
						}
					});
					// - Selecting when clicking on the rows (instead of the global checkbox)
					oTable_{$this->oField->GetGlobalId()}.off('select').on('select', function(oEvent, dt, type, indexes){
						var aData = oTable_{$this->oField->GetGlobalId()}.rows(indexes).data().toArray();

						// Checking input
						$('#{$sTableId} tbody tr[id].selected td:first-child input').prop('checked', true);
						// Saving values in temp array
						for(var i in aData)
						{
							var iItemId = aData[i].id;
							if(!(iItemId in oSelectedItems_{$this->oField->GetGlobalId()}))
							{
								oSelectedItems_{$this->oField->GetGlobalId()}[iItemId] = aData[i].name;
							}
						}
						// Updating remove button
						updateRemoveButtonState_{$this->oField->GetGlobalId()}();
					});
					// - Deselecting when clicking on the rows (instead of the global checkbox)
					oTable_{$this->oField->GetGlobalId()}.off('deselect').on('deselect', function(oEvent, dt, type, indexes){
						var aData = oTable_{$this->oField->GetGlobalId()}.rows(indexes).data().toArray();

						// Checking input
						$('#{$sTableId} tbody tr[id]:not(.selected) td:first-child input').prop('checked', false);
						// Saving values in temp array
						for(var i in aData)
						{
							var iItemId = aData[i].id;
							if(iItemId in oSelectedItems_{$this->oField->GetGlobalId()})
							{
								delete oSelectedItems_{$this->oField->GetGlobalId()}[iItemId];
							}
						}
						// Unchecking global checkbox
						$('#{$this->oField->GetGlobalId()}_check_all').prop('checked', false);
						// Updating remove button
						updateRemoveButtonState_{$this->oField->GetGlobalId()}();
					});
					// - From the global button
					$('#{$this->oField->GetGlobalId()}_check_all').off('click').on('click', function(oEvent){
						if($(this).prop('checked'))
						{
							oTable_{$this->oField->GetGlobalId()}.rows(':not(.limited_access)').select();
						}
						else
						{
							oTable_{$this->oField->GetGlobalId()}.rows(':not(.limited_access)').deselect();
						}
						updateRemoveButtonState_{$this->oField->GetGlobalId()}();
					});
				};
JS
			);

			// Additional features if in edition mode
			if (!$this->oField->GetReadOnly()) {
				$aErrorMessagesMandatory = Dict::S('Core:Validator:Mandatory');
				$aErrorMessagesDefault = Dict::S('Core:Validator:Default');
				// Attaching JS widget
				$sObjectInformationsUrl = $this->oField->GetInformationEndpoint();
				$oOutput->AddJs(
					<<<JS
                $("[data-field-id='{$this->oField->GetId()}'][data-form-path='{$this->oField->GetFormPath()}']").portal_form_field({
					'validators': {$this->GetValidatorsAsJson()},
					'on_validation_callback': function(oFormField){
                        	const aLinkedSetInputs = $('#{$sFieldWrapperId} input,select,textarea', oFormField.element);
							aLinkedSetInputs.each(function(e){
								const oInput = $(this);
								const aInputValidity = oInput[0].validity;
								const oFormFieldControl = oInput.closest('.form_field_control');
								if(aInputValidity.valueMissing){
									 oFormFieldControl.toggleClass('has-error', true);
									 $('.help-block', oFormFieldControl).html('$aErrorMessagesMandatory');
								}
								else if(aInputValidity.patternMismatch){
									 oFormFieldControl.toggleClass('has-error', true);
									 $('.help-block', oFormFieldControl).html('$aErrorMessagesDefault');
								}
                                else{
                                     oFormFieldControl.toggleClass('has-error', false);
									 $('.help-block', oFormFieldControl).empty();
                                }
							});
					},
					'get_current_value_callback': function(me, oEvent, oData){
                        
                    	// Read linked set value as array
                        var aValue = JSON.parse(me.element.find('#{$this->oField->GetGlobalId()}').val());
                        
						// Iterate throw table rows and extract link attributes input values...				
						$('tbody tr', me.element).each(function(){
                            
                        	// Extract link id
                            const sId = $(this).attr('id');
                            
                            // Security
                        	if(sId !== undefined){
                                
                            	// Prepare link attributes values
                              	const aValues = {};
                                
                                // Extract inputs values...  
                                $('input,select,textarea', $(this)).each(function(){
                                    if($(this).attr('id') !== undefined){
                                      aValues[$(this).attr('name')] = $(this).val();
                                    }
	                            });
	                            
                                // Set values
                                if(aValue.current !== undefined && aValue.current[sId] !== undefined){
                                    aValue.current[sId] = aValues;
                                }
                                const iAddId = -parseInt(sId);
                              	if(aValue.add !== undefined && aValue.add[iAddId] !== undefined){
                                    aValue.add[iAddId] = aValues;
                                }
                            }
  
						});
                        
						return JSON.stringify(aValue);
					},
					'set_current_value_callback': function(me, oEvent, oData){
						// When we have data (meaning that we picked objects from search)
						if(oData !== undefined && Object.keys(oData.values).length > 0)
						{
							// Showing loader while retrieving informations
							$('#page_overlay').fadeIn(200);

							// Retrieving new rows ids
							var aObjectIds = Object.keys(oData.values);
                            
							// Retrieving rows informations so we can add them
							$.post(
								'{$sObjectInformationsUrl}',
								{
									sObjectClass: '{$this->oField->GetTargetClass()}',
									sLinkClass: '{$this->oField->GetLinkedClass()}',
									aObjectIds: aObjectIds,
									aObjectAttCodes: $sAttCodesToDisplayAsJson,
									aLinkAttCodes: $sLnkAttCodesToDisplayAsJson,
									sDateTimePickerWidgetParent: '#table_{$this->oField->GetGlobalId()}_wrapper'
								},
								function(oData){
                                    
									// Updating datatables
									if(oData.items !== undefined)
									{
									    for(var i in oData.items)
										{
											// Adding target item id information
											oData.items[i].target_id = oData.items[i].id;
											
											// Adding item to table only if it's not already there
											if($('#{$sTableId} tr[id] > td input[data-target-object-id="' + oData.items[i].target_id + '"], #{$sTableId} tr[id] > td input[data-target-object-id="' + (oData.items[i].target_id*-1) + '"]').length === 0)
											{
												// Making id negative in order to recognize it when persisting
												oData.items[i].id = -1 * parseInt(oData.items[i].id);
												oTable_{$this->oField->GetGlobalId()}.row.add(oData.items[i]);
											}
																						
										}
										oTable_{$this->oField->GetGlobalId()}.draw();
                                        
                                        // Execute inline js for each attributes renderers
                                        for(let i in oData.items)
										{
			                                for(let key in oData.items[i].attributes){
			                                    eval(oData.items[i].attributes[key].js_inline)
			                                }
                                        }
                                        
										// Updating input
						                updateInputValue_{$this->oField->GetGlobalId()}();
									}
								}
							)
							.done(function(oData){
								// Updating items count
								updateItemCount_{$this->oField->GetGlobalId()}();
								// Updating global checkbox
								$('#{$this->oField->GetGlobalId()}_check_all').prop('checked', false);
							})
							.always(function(oData){
								// Hiding loader
								$('#page_overlay').fadeOut(200);
							});
						}
						// We come from a button
						else
						{
						    // Updating input
						    updateInputValue_{$this->oField->GetGlobalId()}();
							// Updating items count
							updateItemCount_{$this->oField->GetGlobalId()}();
							// Updating global checkbox
							$('#{$this->oField->GetGlobalId()}_check_all').prop('checked', false);
						}
					}
				});
JS
                );

				// Rendering table
				// - Vars
				$sButtonRemoveId = 'btn_remove_' . $this->oField->GetGlobalId();
				$sButtonAddId = 'btn_add_' . $this->oField->GetGlobalId();
				$sLabelRemove = Dict::S('UI:Button:Remove');
				$sLabelAdd = Dict::S('UI:Button:AddObject');
				// - Output
				$oOutput->AddHtml(
<<<EOF
					<div class="row">
						<div class="col-xs-12">
							<div class="btn-group" role="group">
								<button type="button" class="btn btn-sm btn-danger" id="{$sButtonRemoveId}" title="{$sLabelRemove}" disabled><span class="glyphicon glyphicon-minus"></span></button>
								<button type="button" class="btn btn-sm btn-default" id="{$sButtonAddId}" title="{$sLabelAdd}"><span class="glyphicon glyphicon-plus"></span></button>
							</div>
						</div>
					</div>
EOF
				);

				// Rendering table widget
				// - Vars
				$sAddButtonEndpoint = str_replace('-sMode-', 'from-attribute', $this->oField->GetSearchEndpoint());
				// - Output
				$oOutput->AddJs(
	<<<JS
					// Handles items selection/deselection
					// - Remove button state handler
					var updateRemoveButtonState_{$this->oField->GetGlobalId()} = function()
					{
						var bIsDisabled = (Object.keys(oSelectedItems_{$this->oField->GetGlobalId()}).length == 0);
						$('#{$sButtonRemoveId}').prop('disabled', bIsDisabled);
					};
					// - Item count state handler
					var updateItemCount_{$this->oField->GetGlobalId()} = function()
					{
						$('#{$sCollapseTogglerId} > .text').text( oTable_{$this->oField->GetGlobalId()}.rows().count() );
					};
					// - Field input handler
					var updateInputValue_{$this->oField->GetGlobalId()} = function()
					{
					    // Retrieving table rows
					    var aData = oTable_{$this->oField->GetGlobalId()}.rows().data().toArray();
                        
					    // Retrieving input values
                        var oValues = JSON.parse($('#{$this->oField->GetGlobalId()}').val());
                        oValues.add = {};
                        oValues.remove = {};
                        
					    // Checking removed objects
					    for(var i in oValues.current)
					    {
					        if($('#{$sTableId} tr[id="'+i+'"]').length === 0)
                            {
                                oValues.remove[i] = {};
                            }
					    }
					    
					    // Checking added objects
					    for(var i in aData)
					    {
					        if(oValues.current[aData[i].id] === undefined)
					        {
					            oValues.add[aData[i].target_id] = {};
                            }
					    }
					    
                        // Setting input values
                        $('#{$this->oField->GetGlobalId()}').val(JSON.stringify(oValues));
					};

					// Handles items remove/add
					$('#{$sButtonRemoveId}').off('click').on('click', function(){
						// Removing items from table
						oTable_{$this->oField->GetGlobalId()}.rows({selected: true}).remove().draw();
						// Resetting selected items
						oSelectedItems_{$this->oField->GetGlobalId()} = {};
						// Updating form value
						$("[data-field-id='{$this->oField->GetId()}'][data-form-path='{$this->oField->GetFormPath()}']").triggerHandler('set_current_value');
						// Updating global checkbox state
						$('#{$this->oField->GetGlobalId()}_check_all').prop('checked', false);
						// Updating remove button
						updateRemoveButtonState_{$this->oField->GetGlobalId()}();
					});
					$('#{$sButtonAddId}').off('click').on('click', function(){
						// Preparing current values
						var aObjectIdsToIgnore = [];
						$('#{$sTableId} tr[id] > td input[data-target-object-id]').each(function(iIndex, oElem){
							aObjectIdsToIgnore.push( $(oElem).attr('data-target-object-id') );
						});
						
						// Creating a new modal
						var oOptions =
						{
							content: {
								endpoint: '{$sAddButtonEndpoint}',
								data: {
									sFormPath: '{$this->oField->GetFormPath()}',
									sFieldId: '{$this->oField->GetId()}',
									aObjectIdsToIgnore : aObjectIdsToIgnore
								},
							},
						};
					
						if($('.modal[data-source-element="{$sButtonAddId}"]').length === 0)
						{
							oOptions['attributes'] = {'data-source-element': '{$sButtonAddId}'};
						}
						else
						{
							oOptions['base_modal'] = {
								'usage': 'replace',
								'selector': '.modal[data-source-element="{$sButtonAddId}"]:first'
							};
						}
						CombodoModal.OpenModal(oOptions);
					});
JS
				);
			}
		}
		// ... and in hidden mode
		else
		{
			$oOutput->AddHtml('<input type="hidden" id="' . $this->oField->GetGlobalId() . '" name="' . $this->oField->GetId() . '" value="' . $sItemIdsAsJson . '" />');
		}

		// End of table rendering
		$oOutput->AddHtml('</div>');
		$oOutput->AddHtml('</div>');

		return $oOutput;
	}

    /**
     * @param $aItems
     * @param $aItemIds
     *
     * @throws \Exception
     * @throws \CoreException
     */
	protected function PrepareItems(&$aItems, &$aItemIds, $oOutput, &$aAddedItemIds, &$aAddedTargetIds)
	{
		/** @var \ormLinkSet $oValueSet */
		$oValueSet = $this->oField->GetCurrentValue();
		$oValueSet->OptimizeColumnLoad(array($this->oField->GetTargetClass() => $this->oField->GetAttributesToDisplay(true)));
		while ($oItem = $oValueSet->Fetch()) {

			// In case of indirect linked set, we must retrieve the remote object
			if ($this->oField->IsIndirect()) {
				try {
					// Note : AllowAllData set to true here instead of checking scope's flag because we are displaying a value that has been set and validated
					$oRemoteItem = MetaModel::GetObject($this->oField->GetTargetClass(), $oItem->Get($this->oField->GetExtKeyToRemote()), true, true);
				}
				catch (Exception $e) {
					// In some cases we can't retrieve an object from a linkedset, eg. when the extkey to remote is 0 due to a database corruption.
					// Rather than crashing we rather just skip the object like in the administration console
					IssueLog::Error('Could not retrieve object of linkedset in form #'.$this->oField->GetFormPath().' for field #'.$this->oField->GetId().'. Message: '.$e->getMessage());
					continue;
				}
			} else {
				$oRemoteItem = $oItem;
			}

			// Skip item if not supposed to be displayed
			$bLimitedAccessItem = $this->oField->IsLimitedAccessItem($oRemoteItem->GetKey());
			if ($bLimitedAccessItem && !$this->oField->GetDisplayLimitedAccessItems()) {
				continue;
			}

			$aItemProperties = array(
				'id'             => ($this->oField->IsIndirect() && $oItem->IsNew()) ? -1 * $oRemoteItem->GetKey() : $oItem->GetKey(),
				'target_id'      => $oRemoteItem->GetKey(),
				'name'           => $oItem->GetName(),
				'attributes'     => array(),
				'limited_access' => $bLimitedAccessItem,
				'disabled'       => true,
				'active'         => false,
				'inactive'       => true,
				'not-selectable' => true,
			);

			// Link attributes to display
			$this->PrepareItem($oItem, $this->oField->GetLinkedClass(), $this->oField->GetLnkAttributesToDisplay(true), !$this->oField->GetReadOnly(), $aItemProperties, 'lnk__');

			// Remote attributes to display
			$this->PrepareItem($oRemoteItem, $this->oField->GetTargetClass(), $this->oField->GetAttributesToDisplay(true), false, $aItemProperties);

			// Remap objects to avoid added item to be considered as current item when form validation isn't valid
			// and form reconstruct
			$aItems[] = $aItemProperties;
			if ($oItem->IsNew()) {
				$aAddedItemIds[-1 * $aItemProperties['id']] = array();
				$aAddedTargetIds[] = $oRemoteItem->GetKey();
			} else {
				$aItemIds[$aItemProperties['id']] = array();
			}
		}
		$oValueSet->rewind();
	}

	/**
	 * @param string $sClass
	 * @param array $aAttributesCodesToDisplay
	 * @param $oOutput
	 *
	 * @return void
	 * @throws \CoreException
	 */
	protected function InjectRendererFileAssets(string $sClass, array $aAttributesCodesToDisplay, $oOutput)
	{
		// handle abstract class
		while(MetaModel::IsAbstract($sClass)){
			$aChildClasses = MetaModel::EnumChildClasses($sClass);
			if(count($aChildClasses) > 0){
				$sClass = $aChildClasses[0];
			}
		}

		// create a fake object to pass to renderers for retrieving global assets
		$oItem = MetaModel::NewObject($sClass);

		// Iterate throw attributes...
		foreach ($aAttributesCodesToDisplay as $sAttCode) {

			// Retrieve attribute definition
			$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);

			// make form field from attribute
			$oField = $oAttDef->MakeFormField($oItem);

			// retrieve the form field renderer
			$sFieldRendererClass = static::GetFieldRendererClass($oField);

			// retrieve renderer global assets
			if ($sFieldRendererClass !== null) {
				/** @var FieldRenderer $oFieldRenderer */
				$oFieldRenderer = new $sFieldRendererClass($oField);
				$oFieldOutput = $oFieldRenderer->Render();
				static::TransferFieldRendererGlobalOutput($oFieldOutput, $oOutput);
			}
		}
	}

	/**
	 * @param \DBObject $oItem
	 * @param string $sClass
	 * @param array $aAttributesCodesToDisplay
	 * @param bool $bIsEditable
	 * @param array $aItemProperties
	 * @param $oOutput
	 *
	 * @return void
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 */
	protected function PrepareItem(DBObject $oItem, string $sClass, array $aAttributesCodesToDisplay, bool $bIsEditable, array &$aItemProperties, string $sAttribueKeyPrefix = '')
	{
		// Iterate throw attributes...
		foreach ($aAttributesCodesToDisplay as $sAttCode) {

			if ($sAttCode !== 'id') {

				// Retrieve attribute definition
				$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);

				// Prepare attribute properties
				$aAttProperties = [
						'prefix'=> $sAttribueKeyPrefix,
						'object_class'  => $sClass,
						'object_id'  => $oItem->GetKey(),
						'attribute_code' => $sAttCode,
						'attribute_type' => get_class($oAttDef),
				];
				// - Value raw
				// For simple fields, we get the raw (stored) value as well
				$bExcludeRawValue = false;
				foreach (ApplicationHelper::GetAttDefClassesToExcludeFromMarkupMetadataRawValue() as $sAttDefClassToExclude)
				{
					if (is_a($oAttDef, $sAttDefClassToExclude, true))
					{
						$bExcludeRawValue = true;
						break;
					}
				}
				$aAttProperties['value_raw'] = ($bExcludeRawValue === false) ? $oItem->Get($sAttCode) : null;

				// External key specific
				if ($bIsEditable) {

					$oField = $oAttDef->MakeFormField($oItem);

					// Prevent datetimepicker popup to be truncated
					if ($oField instanceof DateTimeField) {
						$oField->SetDateTimePickerWidgetParent('#table_'.$this->oField->GetGlobalId().'_wrapper');
					}

					$sFieldRendererClass = static::GetFieldRendererClass($oField);

					if ($sFieldRendererClass !== null) {
						/** @var FieldRenderer $oFieldRenderer */
						$oFieldRenderer = new $sFieldRendererClass($oField);
						$oFieldOutput = $oFieldRenderer->Render();
						$aAttProperties['js_inline'] = $oFieldOutput->GetJs();
						$aAttProperties['css_inline'] = $oFieldOutput->GetCss();
						$aAttProperties['value_html'] = $oFieldOutput->GetHtml();
					}

				} else if ($oAttDef->IsExternalKey()) {

					/** @var \AttributeExternalKey $oAttDef */
					$aAttProperties['value_html'] = $oItem->Get($sAttCode.'_friendlyname');

					// Checking if user can access object's external key
					$sObjectUrl = ApplicationContext::MakeObjectUrl($oAttDef->GetTargetClass(), $oItem->Get($sAttCode));
					if (!empty($sObjectUrl)) {
						$aAttProperties['url'] = $sObjectUrl;
					}

				} else { // Others attributes

					$aAttProperties['value_html'] = $oAttDef->GetAsHTML($oItem->Get($sAttCode));

					if ($oAttDef instanceof AttributeFriendlyName) {
						// Checking if user can access object
						$sObjectUrl = ApplicationContext::MakeObjectUrl($sClass, $oItem->GetKey());
						if (!empty($sObjectUrl)) {
							$aAttProperties['url'] = $sObjectUrl;
						}
					}
				}

				$aItemProperties['attributes'][$sAttribueKeyPrefix.$sAttCode] = $aAttProperties;

			}
		}
	}

	/**
	 * Transfer field renderer output to page output.
	 *
	 * @param \Combodo\iTop\Renderer\RenderingOutput $oFieldOutput
	 * @param \Combodo\iTop\Renderer\RenderingOutput $oPageOutput
	 *
	 * @return void
	 */
	public static function TransferFieldRendererGlobalOutput(RenderingOutput $oFieldOutput, RenderingOutput $oPageOutput)
	{
		foreach ($oFieldOutput->GetJsFiles() as $sJsFile) {
			$oPageOutput->AddJsFile($sJsFile);
		}
		foreach ($oFieldOutput->GetCssFiles() as $sCssFile) {
			$oPageOutput->AddCssFile($sCssFile);
		}
	}

	/**
	 * Retrieve a field renderer class.
	 *
	 * @param \Combodo\iTop\Form\Field\Field $oField
	 *
	 * @return string|null
	 */
	public static function GetFieldRendererClass(Field $oField): ?string
	{
		$aRegisteredFields = BsFieldRendererMappings::RegisterSupportedFields();
		$sFieldClass = get_class($oField);
		foreach ($aRegisteredFields as $aRegisteredField) {
			if ($aRegisteredField['field'] === $sFieldClass) {
				return $aRegisteredField['field_renderer'];
			}
		}

		return null;
	}

}

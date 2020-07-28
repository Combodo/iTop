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

use ApplicationContext;
use AttributeFriendlyName;
use Dict;
use Exception;
use IssueLog;
use MetaModel;

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
		// Vars to build the table
		$sAttributesToDisplayAsJson = json_encode($this->oField->GetAttributesToDisplay());
		$sAttCodesToDisplayAsJson = json_encode($this->oField->GetAttributesToDisplay(true));
		$aItems = array();
		$aItemIds = array();
		$this->PrepareItems($aItems, $aItemIds);
		$sItemsAsJson = json_encode($aItems);
        $sItemIdsAsJson = htmlentities(json_encode(array('current' => $aItemIds)), ENT_QUOTES, 'UTF-8');

        if (!$this->oField->GetHidden())
		{
			// Rendering field
			$sIsEditable = ($this->oField->GetReadOnly()) ? 'false' : 'true';
			$sCollapseTogglerIconVisibleClass = 'glyphicon-menu-down';
			$sCollapseTogglerIconHiddenClass = 'glyphicon-menu-down collapsed';
			$sCollapseTogglerClass = 'form_linkedset_toggler';
			$sCollapseTogglerId = $sCollapseTogglerClass . '_' . $this->oField->GetGlobalId();
			$sFieldWrapperId = 'form_linkedset_wrapper_' . $this->oField->GetGlobalId();

			// Preparing collapsed state
            if($this->oField->GetDisplayOpened())
            {
                $sCollapseTogglerExpanded = 'true';
                $sCollapseTogglerIconClass = $sCollapseTogglerIconVisibleClass;
                $sCollapseJSInitState = 'true';
            }
            else
            {
                $sCollapseTogglerClass .= ' collapsed';
                $sCollapseTogglerExpanded = 'false';
                $sCollapseTogglerIconClass = $sCollapseTogglerIconHiddenClass;
                $sCollapseJSInitState = 'false';
            }

			$oOutput->AddHtml('<div class="form-group ' . $sFieldMandatoryClass . '">');
			if ($this->oField->GetLabel() !== '')
			{
				$oOutput->AddHtml('<label for="' . $this->oField->GetGlobalId() . '" class="control-label">')
					->AddHtml('<a id="' . $sCollapseTogglerId . '" class="' . $sCollapseTogglerClass . '" data-toggle="collapse" href="#' . $sFieldWrapperId . '" aria-expanded="' . $sCollapseTogglerExpanded . '" aria-controls="' . $sFieldWrapperId . '">')
					->AddHtml($this->oField->GetLabel(), true)
					->AddHtml('<span class="text">' . count($aItemIds) . '</span>')
					->AddHtml('<span class="glyphicon ' . $sCollapseTogglerIconClass . '"></>')
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
			$sEmptyTableLabel = htmlentities(Dict::S(($this->oField->GetReadOnly()) ? 'Portal:Datatables:Language:EmptyTable' : 'UI:Message:EmptyList:UseAdd'), ENT_QUOTES, 'UTF-8');
			$sLabelGeneralCheckbox = htmlentities(Dict::S('Core:BulkExport:CheckAll') . ' / ' . Dict::S('Core:BulkExport:UncheckAll'), ENT_QUOTES, 'UTF-8');
			$sSelectionOptionHtml = ($this->oField->GetReadOnly()) ? 'false' : '{"style": "multi"}';
			$sSelectionInputGlobalHtml = ($this->oField->GetReadOnly()) ? '' : '<span class="row_input"><input type="checkbox" id="' . $this->oField->GetGlobalId() . '_check_all" name="' . $this->oField->GetGlobalId() . '_check_all" title="' . $sLabelGeneralCheckbox . '" /></span>';
			$sSelectionInputHtml = ($this->oField->GetReadOnly()) ? '' : '<span class="row_input"><input type="checkbox" name="' . $this->oField->GetGlobalId() . '" /></span>';
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
				var oColumnProperties_{$this->oField->GetGlobalId()} = {$sAttributesToDisplayAsJson};
				var oRawDatas_{$this->oField->GetGlobalId()} = {$sItemsAsJson};
				var oTable_{$this->oField->GetGlobalId()};
				var oSelectedItems_{$this->oField->GetGlobalId()} = {};

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
								"data": "",
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

					for(sKey in oColumnProperties_{$this->oField->GetGlobalId()})
					{
						// Level main column
						aColumnsDefinition.push({
							"width": "auto",
							"searchable": true,
							"sortable": true,
							"title": oColumnProperties_{$this->oField->GetGlobalId()}[sKey],
							"defaultContent": "",
							"type": "html",
							"data": "attributes."+sKey+".att_code",
							"render": function(data, type, row){
								var cellElem;

								// Preparing the cell data
								if(row.attributes[data].url !== undefined)
								{
									cellElem = $('<a></a>');
									cellElem.attr('href', row.attributes[data].url);
								}
								else
								{
									cellElem = $('<span></span>');
								}
								cellElem.html('<span>' + row.attributes[data].value + '</span>');

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
					var iDefaultOrderColumnIndex = ({$sIsEditable}) ? 1 : 0;

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
								CombodoPortalToolbox.OpenModal({
									content: {
										endpoint: $(this).attr('href'),
									},
								});
							});
						},
					});
						
					// Handles items selection/deselection
					// - Preventing limited access rows to be selected on click
					oTable_{$this->oField->GetGlobalId()}.off('user-select').on('user-select', function(oEvent, dt, type, cell, originalEvent){
						if($(originalEvent.target).closest('tr[role="row"]').hasClass('limited_access'))
						{
							oEvent.preventDefault();
						}
					});
					// - Selecting when clicking on the rows (instead of the global checkbox)
					oTable_{$this->oField->GetGlobalId()}.off('select').on('select', function(oEvent, dt, type, indexes){
						var aData = oTable_{$this->oField->GetGlobalId()}.rows(indexes).data().toArray();

						// Checking input
						$('#{$sTableId} tbody tr[role="row"].selected td:first-child input').prop('checked', true);
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
						$('#{$sTableId} tbody tr[role="row"]:not(.selected) td:first-child input').prop('checked', false);
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
			if (!$this->oField->GetReadOnly())
			{
                // Attaching JS widget
                $sObjectInformationsUrl = $this->oField->GetInformationEndpoint();
                $oOutput->AddJs(
<<<EOF
                $("[data-field-id='{$this->oField->GetId()}'][data-form-path='{$this->oField->GetFormPath()}']").portal_form_field({
					'validators': {$this->GetValidatorsAsJson()},
					'get_current_value_callback': function(me, oEvent, oData){
						var value = null;

						// Retrieving JSON value as a string and not an object
						//
						// Note : The value is passed as a string instead of an array because the attribute would not be included in the posted data when empty.
						// Which was an issue when deleting all objects from linkedset
						//
						// Old code : value = JSON.parse(me.element.find('#{$this->oField->GetGlobalId()}').val());
						value = me.element.find('#{$this->oField->GetGlobalId()}').val();

						return value;
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
									aObjectIds: aObjectIds,
									aObjectAttCodes: $sAttCodesToDisplayAsJson
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
											if($('#{$sTableId} tr[role="row"] > td input[data-target-object-id="' + oData.items[i].target_id + '"], #{$sTableId} tr[role="row"] > td input[data-target-object-id="' + (oData.items[i].target_id*-1) + '"]').length === 0)
											{
												// Making id negative in order to recognize it when persisting
												oData.items[i].id = -1 * parseInt(oData.items[i].id);
												oTable_{$this->oField->GetGlobalId()}.row.add(oData.items[i]);
											}
											
											
										}
										oTable_{$this->oField->GetGlobalId()}.draw();
										
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
EOF
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
					        if($('#{$sTableId} tr[role="row"][id="'+i+'"]').length === 0)
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
						$('#{$sTableId} tr[role="row"] > td input[data-target-object-id]').each(function(iIndex, oElem){
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
						CombodoPortalToolbox.OpenModal(oOptions);
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
	protected function PrepareItems(&$aItems, &$aItemIds)
	{
		/** @var \ormLinkSet $oValueSet */
		$oValueSet = $this->oField->GetCurrentValue();
		$oValueSet->OptimizeColumnLoad(array($this->oField->GetTargetClass() => $this->oField->GetAttributesToDisplay(true)));
		while ($oItem = $oValueSet->Fetch())
		{
			// In case of indirect linked set, we must retrieve the remote object
			if ($this->oField->IsIndirect())
			{
			    try{
                    // Note : AllowAllData set to true here instead of checking scope's flag because we are displaying a value that has been set and validated
                    $oRemoteItem = MetaModel::GetObject($this->oField->GetTargetClass(), $oItem->Get($this->oField->GetExtKeyToRemote()), true, true);
                }
                catch(Exception $e)
                {
                    // In some cases we can't retrieve an object from a linkedset, eg. when the extkey to remote is 0 due to a database corruption.
                    // Rather than crashing we rather just skip the object like in the administration console
                    IssueLog::Error('Could not retrieve object of linkedset in form #'.$this->oField->GetFormPath().' for field #'.$this->oField->GetId().'. Message: '.$e->getMessage());
                    continue;
                }
			}
			else
			{
				$oRemoteItem = $oItem;
			}
			
			// Skip item if not supposed to be displayed
			$bLimitedAccessItem = $this->oField->IsLimitedAccessItem($oRemoteItem->GetKey());
			if ($bLimitedAccessItem && !$this->oField->GetDisplayLimitedAccessItems())
			{
				continue;
			}

			$aItemProperties = array(
				'id' => ($this->oField->IsIndirect() && $oItem->IsNew()) ? -1*$oRemoteItem->GetKey() : $oItem->GetKey(),
				'target_id' => $oRemoteItem->GetKey(),
				'name' => $oItem->GetName(),
				'attributes' => array(),
				'limited_access' => $bLimitedAccessItem,
				'disabled' => true,
				'active' => false,
				'inactive' => true,
				'not-selectable' => true,
 			);

			// Target object others attributes
            // TODO: Support for AttributeImage, AttributeBlob
			foreach ($this->oField->GetAttributesToDisplay(true) as $sAttCode)
			{
				if ($sAttCode !== 'id')
				{
					$aAttProperties = array(
						'att_code' => $sAttCode
					);

					$oAttDef = MetaModel::GetAttributeDef($this->oField->GetTargetClass(), $sAttCode);
					if ($oAttDef->IsExternalKey())
					{
						/** @var \AttributeExternalKey $oAttDef */
						$aAttProperties['value'] = $oRemoteItem->Get($sAttCode . '_friendlyname');

						// Checking if user can access object's external key
						$sObjectUrl = ApplicationContext::MakeObjectUrl($oAttDef->GetTargetClass(), $oRemoteItem->Get($sAttCode));
						if(!empty($sObjectUrl))
						{
							$aAttProperties['url'] = $sObjectUrl;
						}
					}
					else
					{
						$aAttProperties['value'] = $oAttDef->GetAsHTML($oRemoteItem->Get($sAttCode));

						if ($oAttDef instanceof AttributeFriendlyName)
						{
							// Checking if user can access object
							$sObjectUrl = ApplicationContext::MakeObjectUrl(get_class($oRemoteItem), $oRemoteItem->GetKey());
							if(!empty($sObjectUrl))
							{
								$aAttProperties['url'] = $sObjectUrl;
							}
						}
					}

					$aItemProperties['attributes'][$sAttCode] = $aAttProperties;
				}
			}
			
			$aItems[] = $aItemProperties;
			$aItemIds[$aItemProperties['id']] = array();
		}
		$oValueSet->rewind();
	}

}

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
use \InlineImage;
use \DBObjectSet;
use \MetaModel;
use \Combodo\iTop\Renderer\FieldRenderer;
use \Combodo\iTop\Renderer\RenderingOutput;
use \Combodo\iTop\Form\Field\LinkedSetField;

/**
 * Description of BsSelectObjectFieldRenderer
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 */
class BsLinkedSetFieldRenderer extends FieldRenderer
{

	/**
	 * Returns a RenderingOutput for the FieldRenderer's Field
	 *
	 * @return \Combodo\iTop\Renderer\RenderingOutput
	 */
	public function Render()
	{
		$oOutput = new RenderingOutput();
		$sFieldMandatoryClass = ($this->oField->GetMandatory()) ? 'form_mandatory' : '';
		// Vars to build the table
		$sAttributesToDisplayAsJson = json_encode($this->oField->GetAttributesToDisplay());
		$sAttCodesToDisplayAsJson = json_encode($this->oField->GetAttributesToDisplay(true));
		$aItems = array();
		$aItemIds = array();
		$this->PrepareItems($aItems, $aItemIds);
		$sItemsAsJson = json_encode($aItems);
		$sItemIdsAsJson = htmlentities(json_encode($aItemIds), ENT_QUOTES, 'UTF-8');
		
		if (!$this->oField->GetHidden())
		{
			// Rendering field
			$oOutput->AddHtml('<div class="form-group ' . $sFieldMandatoryClass . '">');
			if ($this->oField->GetLabel() !== '')
			{
				$oOutput->AddHtml('<label for="' . $this->oField->GetGlobalId() . '" class="control-label">')->AddHtml($this->oField->GetLabel(), true)->AddHtml('</label>');
			}
			$oOutput->AddHtml('<div class="help-block"></div>');

			// Rendering table
			// - Vars
			$sTableId = 'table_' . $this->oField->GetGlobalId();
			// - Output
			$oOutput->AddHtml(
<<<EOF
				<div class="form_linkedset_wrapper">
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
			$sEmptyTableLabel = htmlentities(Dict::S('UI:Message:EmptyList:UseAdd'), ENT_QUOTES, 'UTF-8');
			$sSelectionOptionHtml = ($this->oField->GetReadOnly()) ? 'false' : '{"style": "multi"}';
			$sSelectionInputHtml = ($this->oField->GetReadOnly()) ? '' : '<span class="row_input"><input type="checkbox" name="' . $this->oField->GetId() . '" /></span>';
			// - Output
			$oOutput->AddJs(
<<<EOF
				var oColumnProperties_{$this->oField->GetGlobalId()} = {$sAttributesToDisplayAsJson};
				var oRawDatas_{$this->oField->GetGlobalId()} = {$sItemsAsJson};
				var oTable_{$this->oField->GetGlobalId()};
				var oSelectedItems_{$this->oField->GetGlobalId()} = {};

				var getColumnsDefinition_{$this->oField->GetGlobalId()} = function()
				{
					var aColumnsDefinition = [];
					var sFirstColumnId = Object.keys(oColumnProperties_{$this->oField->GetGlobalId()})[0];

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
									cellElem.attr('target', '_blank').attr('href', row.attributes[data].url);
								}
								else
								{
									cellElem = $('<span></span>');
								}
								cellElem.attr('data-object-id', row.id).html('<span>' + row.attributes[data].value + '</span>');

								if(data === sFirstColumnId)
								{
									cellElem.prepend('{$sSelectionInputHtml}');
								}

								return cellElem.prop('outerHTML');
							},
						});
					}

					return aColumnsDefinition;
				};

				// Note : Those options should be externalized in an library so we can use them on any DataTables for the portal.
				// We would just have to override / complete the necessary elements
				oTable_{$this->oField->GetGlobalId()} = $('#{$sTableId}').DataTable({
					"language": {
						"emptyTable":	  "{$sEmptyTableLabel}"
					},
					"displayLength": -1,
					"scrollY": "300px",
					"scrollCollapse": true,
					"dom": 't',
					"columns": getColumnsDefinition_{$this->oField->GetGlobalId()}(),
					"select": {$sSelectionOptionHtml},
					"rowId": "id",
					"data": oRawDatas_{$this->oField->GetGlobalId()},
				});
EOF
			);

			// Attaching JS widget
			$sObjectInformationsUrl = $this->oField->GetInformationEndpoint();
			$oOutput->AddJs(
<<<EOF
				$("[data-field-id='{$this->oField->GetId()}'][data-form-path='{$this->oField->GetFormPath()}']").portal_form_field({
					'validators': {$this->GetValidatorsAsJson()},
					'get_current_value_callback': function(me, oEvent, oData){
						var value = null;

						value = JSON.parse(me.element.find('#{$this->oField->GetGlobalId()}').val());

						return value;
					},
					'set_current_value_callback': function(me, oEvent, oData){
						// When we have data (meaning that we picked objects from search)
						if(oData !== undefined && Object.keys(oData.values).length > 0)
						{
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
											// Adding item to table only if it's not already there
											if($('#{$sTableId} tr#' + oData.items[i].id + '[role="row"]').length === 0)
											{
												// Making id negative in order to recognize it when persisting
												oData.items[i].id = -1 * parseInt(oData.items[i].id);
												oTable_{$this->oField->GetGlobalId()}.row.add(oData.items[i]);
											}
										}
										oTable_{$this->oField->GetGlobalId()}.draw();
									}
								}
							)
							.done(function(oData){
								// Updating hidden field
								var aData = oTable_{$this->oField->GetGlobalId()}.rows().data().toArray();
								var aObjectIds = [];

								for(var i in aData)
								{
									aObjectIds.push({id: aData[i].id});
								}

								$('#{$this->oField->GetGlobalId()}').val(JSON.stringify(aObjectIds));
							});
						}
						// We come from a button
						else
						{
							// Updating hidden field
							var aData = oTable_{$this->oField->GetGlobalId()}.rows().data().toArray();
							var aObjectIds = [];

							for(var i in aData)
							{
								aObjectIds.push({id: aData[i].id});
							}

							$('#{$this->oField->GetGlobalId()}').val(JSON.stringify(aObjectIds));
						}
					}
				});
EOF
			);

			// Additional features if in edition mode
			if (!$this->oField->GetReadOnly())
			{
				// Rendering table
				// - Vars
				$sButtonAllId = 'btn_all_' . $this->oField->GetGlobalId();
				$sButtonNoneId = 'btn_none_' . $this->oField->GetGlobalId();
				$sButtonRemoveId = 'btn_remove_' . $this->oField->GetGlobalId();
				$sButtonAddId = 'btn_add_' . $this->oField->GetGlobalId();
				$sLabelAll = Dict::S('Core:BulkExport:CheckAll');
				$sLabelNone = Dict::S('Core:BulkExport:UncheckAll');
				$sLabelRemove = Dict::S('UI:Button:Remove');
				$sLabelAdd = Dict::S('UI:Button:AddObject');
				// - Output
				$oOutput->AddHtml(
<<<EOF
					<div class="row">
						<div class="col-xs-6">
							<button type="button" class="btn btn-secondary" id="{$sButtonAllId}">{$sLabelAll}</button>
							<button type="button" class="btn btn-secondary" id="{$sButtonNoneId}">{$sLabelNone}</button>
						</div>
						<div class="col-xs-6 text-right">
							<button type="button" class="btn btn-danger" id="{$sButtonRemoveId}">{$sLabelRemove}</button>
							<button type="button" class="btn btn-default" id="{$sButtonAddId}">{$sLabelAdd}</button>
						</div>
					</div>
EOF
				);

				// Rendering table widget
				// - Vars
				$sAddButtonEndpoint = str_replace('-sMode-', 'from-attribute', $this->oField->GetSearchEndpoint());
				// - Output
				$oOutput->AddJs(
	<<<EOF
					// Handles items selection/deselection
					// - Directly on the table
					oTable_{$this->oField->GetGlobalId()}.off('select').on('select', function(oEvent, dt, type, indexes){
						var aData = oTable_{$this->oField->GetGlobalId()}.rows(indexes).data().toArray();

						// Checking input
						$('#{$sTableId} tr[role="row"].selected td:first-child input').prop('checked', true);
						// Saving values in temp array
						for(var i in aData)
						{
							var iItemId = aData[i].id;
							if(!(iItemId in oSelectedItems_{$this->oField->GetGlobalId()}))
							{
								oSelectedItems_{$this->oField->GetGlobalId()}[iItemId] = aData[i].name;
							}
						}
					});
					oTable_{$this->oField->GetGlobalId()}.off('deselect').on('deselect', function(oEvent, dt, type, indexes){
						var aData = oTable_{$this->oField->GetGlobalId()}.rows(indexes).data().toArray();

						// Checking input
						$('#{$sTableId} tr[role="row"]:not(.selected) td:first-child input').prop('checked', false);
						// Saving values in temp array
						for(var i in aData)
						{
							var iItemId = aData[i].id;
							if(iItemId in oSelectedItems_{$this->oField->GetGlobalId()})
							{
								delete oSelectedItems_{$this->oField->GetGlobalId()}[iItemId];
							}
						}
					});
					// - From the bottom buttons
					$('#{$sButtonAllId}').off('click').on('click', function(){
						oTable_{$this->oField->GetGlobalId()}.rows().select();
					});
					$('#{$sButtonNoneId}').off('click').on('click', function(){
						oTable_{$this->oField->GetGlobalId()}.rows().deselect();
					});

					// Handles items remove/add
					$('#{$sButtonRemoveId}').off('click').on('click', function(){
						oTable_{$this->oField->GetGlobalId()}.rows({selected: true}).remove().draw();
						$("[data-field-id='{$this->oField->GetId()}'][data-form-path='{$this->oField->GetFormPath()}']").triggerHandler('set_current_value');
					});
					$('#{$sButtonAddId}').off('click').on('click', function(){
						// Creating a new modal
						var oModalElem;
						if($('.modal[data-source-element="{$sButtonAddId}"]').length === 0)
						{
							oModalElem = $('#modal-for-all').clone();
							oModalElem.attr('id', '').attr('data-source-element', '{$sButtonAddId}').appendTo('body');
						}
						else
						{
							oModalElem = $('.modal[data-source-element="{$sButtonAddId}"]').first();
						}
						// Resizing to small modal
						oModalElem.find('.modal-dialog').removeClass('modal-sm').addClass('modal-lg');
						// Loading content
						oModalElem.find('.modal-content').html($('#page_overlay .overlay_content').html());
						oModalElem.find('.modal-content').load(
							'{$sAddButtonEndpoint}',
							{
								sFormPath: '{$this->oField->GetFormPath()}',
								sFieldId: '{$this->oField->GetId()}'
							}
						);
						oModalElem.modal('show');
					});
EOF
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

	protected function PrepareItems(&$aItems, &$aItemIds)
	{
		$oValueSet = $this->oField->GetCurrentValue();
		$oValueSet->OptimizeColumnLoad(array($this->oField->GetTargetClass() => $this->oField->GetAttributesToDisplay(true)));
		while ($oItem = $oValueSet->Fetch())
		{
			$aItemProperties = array(
				'id' => $oItem->GetKey(),
				'name' => $oItem->GetName(),
				'attributes' => array()
			);

			// In case of indirect linked set, we must retrieve the remote object
			if ($this->oField->IsIndirect())
			{
				$oRemoteItem = MetaModel::GetObject($this->oField->GetTargetClass(), $oItem->Get($this->oField->GetExtKeyToRemote()));
			}
			else
			{
				$oRemoteItem = $oItem;
			}

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
						$aAttProperties['value'] = $oRemoteItem->Get($sAttCode . '_friendlyname');
					}
					else
					{
						$aAttProperties['value'] = $oAttDef->GetValueLabel($oRemoteItem->Get($sAttCode));
					}

					$aItemProperties['attributes'][$sAttCode] = $aAttProperties;
				}
			}

			$aItems[] = $aItemProperties;
			$aItemIds[] = array('id' => $oItem->GetKey());
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

		$oOutput->AddHtml('<div class="col-xs-2 col-lg-1">');
		$oOutput->AddHtml('<button type="button" class="btn btn-default" id="' . $sSearchButtonId . '">S</button>');
		$oOutput->AddHtml('</div>');

		$oOutput->AddJs(
<<<EOF
			$('#{$sSearchButtonId}').off('click').on('click', function(){
				// Creating a new modal
				var oModalElem;
				if($('.modal[data-source-element="{$sSearchButtonId}"]').length === 0)
				{
					oModalElem = $('#modal-for-all').clone();
					oModalElem.attr('id', '').attr('data-source-element', '{$sSearchButtonId}').appendTo('body');
				}
				else
				{
					oModalElem = $('.modal[data-source-element="{$sSearchButtonId}"]').first();
				}
				// Resizing to small modal
				oModalElem.find('.modal-dialog').removeClass('modal-sm').addClass('modal-lg');
				// Loading content
				oModalElem.find('.modal-content').html($('#page_overlay .overlay_content').html());
				oModalElem.find('.modal-content').load(
					'{$sEndpoint}',
					{
						sFormPath: '{$this->oField->GetFormPath()}',
						sFieldId: '{$this->oField->GetId()}'
					}
				);
				oModalElem.modal('show');
			});
EOF
		);
	}

}

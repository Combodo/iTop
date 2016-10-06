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
use \DBObjectSearch;
use \MetaModel;
use \Combodo\iTop\Renderer\FieldRenderer;
use \Combodo\iTop\Renderer\RenderingOutput;
use \Combodo\iTop\Form\Field\LinkedSetField;

/**
 * Description of BsFileUploadFieldRenderer
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 */
class BsFileUploadFieldRenderer extends FieldRenderer
{

	/**
	 * Returns a RenderingOutput for the FieldRenderer's Field
	 *
	 * @return \Combodo\iTop\Renderer\RenderingOutput
	 */
	public function Render()
	{
		$oOutput = new RenderingOutput();

		$sObjectClass = get_class($this->oField->GetObject());
		$sIsDeleteAllowed = ($this->oField->GetAllowDelete() && !$this->oField->GetReadOnly()) ? 'true' : 'false';
		$sDeleteBtn = Dict::S('Portal:Button:Delete');
		$sTempId = session_id() . '_' . $this->oField->GetTransactionId();
		$sUploadDropZoneLabel = Dict::S('Portal:Attachments:DropZone:Message');

		// Starting field container
		$oOutput->AddHtml('<div class="form-group">');
		// Field label
		if ($this->oField->GetLabel() !== '')
		{
			$oOutput->AddHtml('<label for="' . $this->oField->GetGlobalId() . '" class="control-label">')->AddHtml($this->oField->GetLabel(), true)->AddHtml('</label>');
		}
		// Field feedback
		$oOutput->AddHtml('<div class="help-block"></div>');
		// Starting files container
		$oOutput->AddHtml('<div class="fileupload_field_content">');
		// Files list
		$oOutput->AddHtml('<div class="attachments_container row">');
		$this->PrepareExistingFiles($oOutput);
		$oOutput->Addhtml('</div>');
		// Removing upload input if in read only
		// TODO : Add max upload size when itop attachment has been refactored
		if (!$this->oField->GetReadOnly())
		{
			$oOutput->AddHtml('<div class="upload_container row">' . Dict::S('Attachments:AddAttachment') . '<input type="file" id="' . $this->oField->GetGlobalId() . '" name="' . $this->oField->GetId() . '" /><span class="loader glyphicon glyphicon-refresh"></span></div>');
		}
		// Ending files container
		$oOutput->AddHtml('</div>');
		// Ending field container
		$oOutput->AddHtml('</div>');
		
		// JS for file upload
		// Note : This is based on itop-attachement/main.attachments.php
		$oOutput->AddJs(
<<<EOF
			var RemoveAttachment = function(sAttId)
			{
				$('#attachment_' + sAttId).attr('name', 'removed_attachments[]');
				$('#display_attachment_' + sAttId).hide();
			};

			$('#{$this->oField->GetGlobalId()}').fileupload({
				url: '{$this->oField->GetUploadEndpoint()}',
				formData: { operation: 'add', temp_id: '{$sTempId}', object_class: '{$sObjectClass}', 'field_name': '{$this->oField->GetId()}' },
				dataType: 'json',
				pasteZone: null, // Don't accept files via Chrome's copy/paste
				done: function (e, data) {
					if((data.result.error !== undefined) && window.console)
					{
						console.log(data.result.error);
					}
					else
					{
						var sDownloadLink = '{$this->oField->GetDownloadEndpoint()}'.replace(/-sAttachmentId-/, data.result.att_id);

						$(this).closest('.fileupload_field_content').find('.attachments_container').append(
							'<div class="attachment col-xs-6 col-sm-3 col-md-2" id="display_attachment_'+data.result.att_id+'">'+
							'	<a data-preview="'+data.result.preview+'" href="'+sDownloadLink+'" title="'+data.result.msg+'">'+
							'		<div class="attachment_icon"><img src="'+data.result.icon+'"></div>'+
							'		<div class="attachment_name">'+data.result.msg+'</div>'+
							'		<input id="attachment_'+data.result.att_id+'" type="hidden" name="attachments[]" value="'+data.result.att_id+'"/>'+
							'	</a>'+
							'	<input type="button" class="btn btn-xs btn-danger hidden" value="{$sDeleteBtn}"/>'+
							'</div>'
						);
						// Preview tooltip
						if(data.result.preview){
							$('#display_attachment_'+data.result.att_id).tooltip({
								html: true,
								title: function(){ return '<img src="'+sDownloadLink+'" style="max-width: 100%;" />'; }
							});
						}
						// Showing remove button on hover
						$('#display_attachment_'+data.result.att_id).hover( function(){
							$(this).children(':button').toggleClass('hidden');
						});
						// Remove button handler
						$('#display_attachment_'+data.result.att_id+' :button').click(function(oEvent){
							oEvent.preventDefault();
							RemoveAttachment(data.result.att_id);
						});
					}
				},
				start: function() {
					// Scrolling to dropzone so the user can see that attachments are uploaded
					$(this)[0].scrollIntoView();
					// Showing loader
					$(this).closest('.upload_container').find('.loader').css('visibility', 'visible');
				},
				stop: function() {
					// Hiding the loader
					$(this).closest('.upload_container').find('.loader').css('visibility', 'hidden');
					// Adding this field to the touched fields of the field set so the cancel event is called if necessary
					$(this).closest(".field_set").trigger("field_change", {
						id: '{$this->oField->GetGlobalId()}',
						name: '{$this->oField->GetId()}'
					});
				}
			});


			// Preview tooltip
			$('.attachment [data-preview="true"]').each(function(iIndex, oElem){
				$(oElem).parent().tooltip({
					html: true,
					title: function(){ return '<img src="'+$(oElem).attr('href')+'" style="max-width: 100%;" />'; }
				});
			});
			// Remove button handler
			$('.attachments_container .attachment :button').click(function(oEvent){
				oEvent.preventDefault();
				RemoveAttachment($(this).closest('.attachment').find(':input[name="attachments[]"]').val());
			});
			// Remove button showing
			if($sIsDeleteAllowed)
			{
				$('.attachment').hover( function(){
					$(this).find(':button').toggleClass('hidden');
				});
			}

			// Handles a drag / drop overlay
			if($('#drag_overlay').length === 0)
			{
				$('body').append( $('<div id="drag_overlay" class="global_overlay"><div class="overlay_content"><div class="content_uploader"><div class="icon glyphicon glyphicon-cloud-upload"></div><div class="message">{$sUploadDropZoneLabel}</div></div></div></div>') );
			}

			// Handles highlighting of the drop zone
			// Note : This is inspired by itop-attachments/main.attachments.php
			$(document).on('dragover', function(oEvent){
				var bFiles = false;
				if (oEvent.dataTransfer && oEvent.dataTransfer.types)
				{
					for (var i = 0; i < oEvent.dataTransfer.types.length; i++)
					{
						if (oEvent.dataTransfer.types[i] == "application/x-moz-nativeimage")
						{
							bFiles = false; // mozilla contains "Files" in the types list when dragging images inside the page, but it also contains "application/x-moz-nativeimage" before
							break;
						}

						if (oEvent.dataTransfer.types[i] == "Files")
						{
							bFiles = true;
							break;
						}
					}
				}

				if (!bFiles) return; // Not dragging files

				var oDropZone = $('#drag_overlay');
				var oTimeout = window.dropZoneTimeout;
				// This is to detect when there is no drag over because there is no "drag out" event
				if (!oTimeout) {
					oDropZone.removeClass('drag_out').addClass('drag_in');
				} else {
					clearTimeout(oTimeout);
				}
				window.dropZoneTimeout = setTimeout(function () {
					window.dropZoneTimeout = null;
					oDropZone.removeClass('drag_in').addClass('drag_out');
				}, 200);
			});

EOF
		);

		return $oOutput;
	}

	/**
	 * 
	 * @param RenderingOutput $oOutput
	 */
	protected function PrepareExistingFiles(RenderingOutput &$oOutput)
	{
		$sObjectClass = get_class($this->oField->GetObject());
		$sDeleteBtn = Dict::S('Portal:Button:Delete');

		$oSearch = DBObjectSearch::FromOQL("SELECT Attachment WHERE item_class = :class AND item_id = :item_id");
		// Note : AllowAllData set to true here instead of checking scope's flag because we are displaying a value that has been set and validated
		$oSearch->AllowAllData();
		$oSet = new DBObjectSet($oSearch, array(), array('class' => $sObjectClass, 'item_id' => $this->oField->GetObject()->GetKey()));

		// If in read only and no attachments, we display a short message
		if ($this->oField->GetReadOnly() && ($oSet->Count() === 0))
		{
			$oOutput->AddHtml(Dict::S('Attachments:NoAttachment'));
		}
		else
		{
			while ($oAttachment = $oSet->Fetch())
			{
				$iAttId = $oAttachment->GetKey();
				$oDoc = $oAttachment->Get('contents');
				$sFileName = htmlentities($oDoc->GetFileName(), ENT_QUOTES, 'UTF-8');
				$sIcon = utils::GetAbsoluteUrlAppRoot() . 'env-' . utils::GetCurrentEnvironment() . '/itop-attachments/icons/image.png';
				$sPreview = $oDoc->IsPreviewAvailable() ? 'true' : 'false';
				$sDownloadLink = str_replace('-sAttachmentId-', $iAttId, $this->oField->GetDownloadEndpoint());

				$oOutput->Addhtml(
<<<EOF
				<div class="attachment col-xs-6 col-sm-3 col-md-2" id="display_attachment_{$iAttId}">
					<a data-preview="{$sPreview}" href="{$sDownloadLink}" title="{$sFileName}">
						<div class="attachment_icon"><img src="{$sIcon}"></div>
						<div class="attachment_name">{$sFileName}</div>
						<input id="attachment_{$iAttId}" type="hidden" name="attachments[]" value="{$iAttId}"/>
					</a>
					<input id="btn_remove_{$iAttId}" type="button" class="btn btn-xs btn-danger hidden" value="{$sDeleteBtn}"/>
				</div>
EOF
				);
			}
		}
	}

}

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

use AbstractAttachmentsRenderer;
use AttachmentPlugIn;
use Combodo\iTop\Renderer\RenderingOutput;
use DBObjectSearch;
use DBObjectSet;
use Dict;
use InlineImage;
use utils;

/**
 * This is the class used to render attachments in the user portal.
 *
 * In the iTop console this is handled in the itop-attachments module. Most of the code here is a duplicate of this module.
 *
 * @see \AbstractAttachmentsRenderer and its implementations for the iTop console
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 */
class BsFileUploadFieldRenderer extends BsFieldRenderer
{
	/**
	 * @inheritDoc
	 */
	public function Render()
	{
		$oOutput = parent::Render();

		$sObjectClass = get_class($this->oField->GetObject());
		$bIsDeleteAllowed = ($this->oField->GetAllowDelete() && !$this->oField->GetReadOnly());
		$sTempId = utils::GetUploadTempId($this->oField->GetTransactionId());
		$sUploadDropZoneLabel = Dict::S('Portal:Attachments:DropZone:Message');

		// Starting field container
		$oOutput->AddHtml('<div class="form-group">');

		// Label
		$oOutput->AddHtml('<div class="form_field_label">');
		if ($this->oField->GetLabel() !== '')
		{
			$oOutput->AddHtml('<label for="'.$this->oField->GetGlobalId().'" class="control-label">')->AddHtml($this->oField->GetLabel(),
				true)->AddHtml('</label>');
		}
		$oOutput->AddHtml('</div>');

		// Value
		$oOutput->AddHtml('<div class="form_field_control">');
		// - Field feedback
		$oOutput->AddHtml('<div class="help-block"></div>');
		// Starting files container
		$oOutput->AddHtml('<div class="fileupload_field_content">');
		// Files list
		$oOutput->AddHtml('<div class="attachments_container row">');
		$this->PrepareExistingFiles($oOutput, $bIsDeleteAllowed);
		$oOutput->Addhtml('</div>');

		// Removing upload input if in read only
		// TODO : Add max upload size when itop attachment has been refactored
		if (!$this->oField->GetReadOnly())
		{
			$oOutput->AddHtml('<div class="upload_container row">'.Dict::S('Attachments:AddAttachment').'<input type="file" id="'.$this->oField->GetGlobalId().'" name="'.$this->oField->GetId().'" /><span class="loader glyphicon glyphicon-refresh"></span>'.InlineImage::GetMaxUpload().'</div>');
		}
		// Ending files container
		$oOutput->AddHtml('</div>');
		$oOutput->AddHtml('</div>');

		// Ending field container
		$oOutput->AddHtml('</div>');

		// JS for file upload
		$iMaxUploadInBytes = AttachmentPlugIn::GetMaxUploadSize();
		$sMaxUploadLabel = AttachmentPlugIn::GetMaxUpload();
		$sFileTooBigLabel = Dict::Format('Attachments:Error:FileTooLarge', $sMaxUploadLabel);
		$sFileTooBigLabelForJS = addslashes($sFileTooBigLabel);
		// Note : This is based on itop-attachement/main.itop-attachments.php
		$sAttachmentTableRowTemplate = json_encode(self::GetAttachmentTableRow(
			'{{iAttId}}',
			'{{sLineStyle}}',
			'{{sDocDownloadUrl}}',
			'{{sIconClass}}',
			'{{sAttachmentThumbUrl}}',
			'{{sFileName}}',
			'{{sAttachmentMeta}}',
			'{{sFileSize}}',
			'{{sAttachmentDate}}',
			'{{sAttachmentCreator}}',
			$bIsDeleteAllowed
		));
		$oOutput->AddJs(
			<<<JS
			var attachmentRowTemplate = $sAttachmentTableRowTemplate;
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
						var iAttId = data.result.att_id,
							sDownloadLink = '{$this->oField->GetDownloadEndpoint()}'.replace(/-sAttachmentId-/, iAttId),
							sIconClass = (data.result.preview == 'true') ? 'trigger-preview' : '',
							sAttachmentMeta = '<input id="attachment_'+iAttId+'" type="hidden" name="attachments[]" value="'+iAttId+'"/>';

						var replaces = [
							{search: "{{iAttId}}", replace:iAttId },
							{search: "{{lineStyle}}", replace:'' },
							{search: "{{sDocDownloadUrl}}", replace:sDownloadLink },
							{search: "{{sIconClass}}", replace:sIconClass },
							{search: "{{sAttachmentThumbUrl}}", replace:data.result.icon },
							{search: "{{sFileName}}", replace: data.result.msg },
							{search: "{{sAttachmentMeta}}", replace:sAttachmentMeta },
							{search: "{{sFileSize}}", replace:data.result.file_size },
							{search: "{{sAttachmentDate}}", replace:data.result.creation_date },
							{search: "{{sAttachmentCreator}}", replace:data.result.contact_id_friendlyname },
						];
						var sAttachmentRow = attachmentRowTemplate;
						$.each(replaces, function(indexInArray, value ) {
							var re = new RegExp(value.search, 'gi');
							sAttachmentRow = sAttachmentRow.replace(re, value.replace);
						});
						
						$(this).closest('.fileupload_field_content').find('.attachments_container table.attachmentsList>tbody').append(sAttachmentRow);
						// Preview tooltip
						if(data.result.preview){
							$('#display_attachment_'+data.result.att_id +' a.trigger-preview').tooltip({
								container: 'body',
								html: true,
								title: function(){ 
									return '<div class="attachment-tooltip"><img src="'+sDownloadLink+'"></div>'; 
								}
							});
						}
						// Remove button handler
						$('#display_attachment_'+data.result.att_id+' :button').click(function(oEvent){
							oEvent.preventDefault();
							RemoveAttachment(data.result.att_id);
						});
					}
				},
			    send: function(e, data){
			        // Don't send attachment if size is greater than PHP post_max_size, otherwise it will break the request and all its parameters (\$_REQUEST, \$_POST, ...)
			        // Note: We loop on the files as the data structures is an array but in this case, we only upload 1 file at a time.
			        var iTotalSizeInBytes = 0;
			        for(var i = 0; i < data.files.length; i++)
			        {
			            iTotalSizeInBytes += data.files[i].size;
			        }
			        
			        if(iTotalSizeInBytes > $iMaxUploadInBytes)
			        {
			            alert('$sFileTooBigLabelForJS');
				        return false;
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
			$('table.attachmentsList>tbody>tr>td a.trigger-preview').each(function(iIndex, oElem){
				$(oElem).parent().tooltip({
					container: 'body',
					html: true,
					title: function(){ return '<div class="attachment-tooltip"><img src="'+$(oElem).attr('href')+'"></div>'; }
				});
			});
			// Remove button handler
			$('.attachments_container table.attachmentsList>tbody>tr>td :button').click(function(oEvent){
				oEvent.preventDefault();
				RemoveAttachment($(this).closest('.attachment').find(':input[name="attachments[]"]').val());
			});

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
JS
		);

		return $oOutput;
	}

	/**
	 *
	 * @param \Combodo\iTop\Renderer\RenderingOutput $oOutput
	 * @param boolean $bIsDeleteAllowed
	 *
	 * @throws \Exception
	 * @throws \CoreException
	 * @throws \OQLException
	 */
	protected function PrepareExistingFiles(RenderingOutput $oOutput, $bIsDeleteAllowed)
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
			$sTitleThumbnail = Dict::S('Attachments:File:Thumbnail');
			$sTitleFileName = Dict::S('Attachments:File:Name');
			$sTitleFileSize = Dict::S('Attachments:File:Size');
			$sTitleFileDate = Dict::S('Attachments:File:Date');
			$sTitleFileCreator = Dict::S('Attachments:File:Creator');
			$sTitleFileType = Dict::S('Attachments:File:MimeType');
			$oOutput->Addhtml(<<<HTML
<table class="table table-striped attachmentsList">
	<thead>
		<th>$sTitleThumbnail</th>
		<th>$sTitleFileName</th>
		<th>$sTitleFileSize</th>
		<th>$sTitleFileDate</th>
		<th>$sTitleFileCreator</th>
		<th></th>
	</thead>
<tbody>
HTML
			);

			/** @var Attachment $oAttachment */
			while ($oAttachment = $oSet->Fetch())
			{
				$iAttId = $oAttachment->GetKey();

				$sLineStyle = '';

				$sAttachmentMeta = '<input id="attachment_'.$iAttId.'" type="hidden" name="attachments[]" value="'.$iAttId.'">';

				/** @var \ormDocument $oDoc */
				$oDoc = $oAttachment->Get('contents');
				$sFileName = htmlentities($oDoc->GetFileName(), ENT_QUOTES, 'UTF-8');

				$sDocDownloadUrl = str_replace('-sAttachmentId-', $iAttId, $this->oField->GetDownloadEndpoint());

				$sAttachmentThumbUrl = utils::GetAbsoluteUrlAppRoot().AttachmentPlugIn::GetFileIcon($sFileName);
				$sIconClass = '';
				if ($oDoc->IsPreviewAvailable())
				{
					$sIconClass = 'trigger-preview';
					if ($oDoc->GetSize() <= AbstractAttachmentsRenderer::MAX_SIZE_FOR_PREVIEW)
					{
						$sAttachmentThumbUrl = $sDocDownloadUrl;
					}
				}

				$sFileSize = $oDoc->GetFormattedSize();

				$bIsTempAttachment = ($oAttachment->Get('item_id') === 0);
				$sAttachmentDate = '';
				if (!$bIsTempAttachment)
				{
					$sAttachmentDate = $oAttachment->Get('creation_date');
				}

				$sAttachmentCreator = $oAttachment->Get('contact_id_friendlyname');

				$oOutput->Addhtml(self::GetAttachmentTableRow(
					$iAttId,
					$sLineStyle,
					$sDocDownloadUrl,
					$sIconClass,
					$sAttachmentThumbUrl,
					$sFileName,
					$sAttachmentMeta,
					$sFileSize,
					$sAttachmentDate,
					$sAttachmentCreator,
					$bIsDeleteAllowed
				));
			}

			$oOutput->Addhtml(<<<HTML
	</tbody>
</table>
HTML
			);
		}
	}

	/**
	 * @param $iAttId
	 * @param $sLineStyle
	 * @param $sDocDownloadUrl
	 * @param $sIconClass
	 * @param $sAttachmentThumbUrl
	 * @param $sFileName
	 * @param $sAttachmentMeta
	 * @param $sFileSize
	 * @param $sAttachmentDate
	 * @param $sAttachmentCreator
	 * @param $bIsDeleteAllowed
	 *
	 * @return string
	 */
	protected static function GetAttachmentTableRow(
		$iAttId, $sLineStyle, $sDocDownloadUrl, $sIconClass, $sAttachmentThumbUrl, $sFileName, $sAttachmentMeta, $sFileSize,
		$sAttachmentDate, $sAttachmentCreator, $bIsDeleteAllowed
	) {
		$sDeleteButton = '';
		if ($bIsDeleteAllowed)
		{
			$sDeleteBtnLabel = Dict::S('Portal:Button:Delete');
			$sDeleteButton = '<input id="btn_remove_'.$iAttId.'" type="button" class="btn btn-xs btn-primary" value="'.$sDeleteBtnLabel.'">';
		}

		return <<<HTML
	<tr id="display_attachment_{$iAttId}" class="attachment" $sLineStyle>
	  <td><a href="$sDocDownloadUrl" target="_blank" class="$sIconClass"><img $sIconClass style="max-height: 48px;" src="$sAttachmentThumbUrl"></a></td>
	  <td><a href="$sDocDownloadUrl" target="_blank">$sFileName</a>$sAttachmentMeta</td>
	  <td>$sFileSize</td>
	  <td>$sAttachmentDate</td>
	  <td>$sAttachmentCreator</td>
	  <td>$sDeleteButton</td>
	</tr>
HTML;
	}
}

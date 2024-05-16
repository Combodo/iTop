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

use AbstractAttachmentsRenderer;
use AttachmentPlugIn;
use AttributeDateTime;
use Combodo\iTop\Form\Field\Field;
use Combodo\iTop\Renderer\RenderingOutput;
use DBObjectSearch;
use DBObjectSet;
use Dict;
use InlineImage;
use MetaModel;
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
	/** @var DBObjectSet */
	private $oAttachmentsSet;

	public function __construct(Field $oField)
	{
		parent::__construct($oField);

		$oSearch = DBObjectSearch::FromOQL('SELECT Attachment WHERE item_class = :class AND item_id = :item_id');
		// Note : AllowAllData set to true here instead of checking scope's flag because we are displaying a value that has been set and validated
		$oSearch->AllowAllData();
		$sObjectClass = get_class($this->oField->GetObject());
		$this->oAttachmentsSet = new DBObjectSet($oSearch, array(), array('class' => $sObjectClass, 'item_id' => $this->oField->GetObject()->GetKey()));
	}

	/**
	 * @inheritDoc
	 * @throws \CoreException
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

		$sCollapseTogglerIconVisibleClass = 'glyphicon-menu-down';
		$sCollapseTogglerIconHiddenClass = 'glyphicon-menu-down collapsed';
		$sCollapseTogglerClass = 'form_linkedset_toggler';
		$sCollapseTogglerId = $sCollapseTogglerClass . '_' . $this->oField->GetGlobalId();
		$sFieldWrapperId = 'form_upload_wrapper_' . $this->oField->GetGlobalId();
		$sFieldDescriptionForHTMLTag = ($this->oField->HasDescription()) ? 'data-tooltip-content="'.utils::HtmlEntities($this->oField->GetDescription()).'"' : '';

		// If collapsed
		$sCollapseTogglerClass .= ' collapsed';
		$sCollapseTogglerExpanded = 'false';
		$sCollapseTogglerIconClass = $sCollapseTogglerIconHiddenClass;
		$sCollapseJSInitState = 'false';

		// Label
		$oOutput->AddHtml('<div class="form_field_label">');
		if ($this->oField->GetLabel() !== '')
		{
			$iAttachmentsCount = $this->oAttachmentsSet->Count();
			$oOutput
				->AddHtml('<label for="'.$this->oField->GetGlobalId().'" class="control-label" '.$sFieldDescriptionForHTMLTag.'>')
				->AddHtml('<a id="' . $sCollapseTogglerId . '" class="' . $sCollapseTogglerClass . '" data-toggle="collapse" href="#' . $sFieldWrapperId . '" aria-expanded="' . $sCollapseTogglerExpanded . '" aria-controls="' . $sFieldWrapperId . '">')
				->AddHtml($this->oField->GetLabel(),true)
				->AddHtml(' (<span class="attachments-count">'.$iAttachmentsCount.'</span>)')
				->AddHtml('<span class="glyphicon ' . $sCollapseTogglerIconClass . '">')
				->AddHtml('</a>')
				->AddHtml('</label>');
		}
		$oOutput->AddHtml('</div>');

		// Value
		$oOutput->AddHtml('<div class="form_field_control form_upload_wrapper collapse" id="'.$sFieldWrapperId.'">');
		// - Field feedback
		$oOutput->AddHtml('<div class="help-block"></div>');
		// Starting files container
		$oOutput->AddHtml('<div class="fileupload_field_content">');
		// Files list
		$oOutput->AddHtml('<div class="attachments_container row">');
		$this->PrepareExistingFiles($oOutput, $bIsDeleteAllowed);
		$oOutput->Addhtml('</div>');

		$sAttachmentTableId = $this->GetAttachmentsTableId();
		$sNoAttachmentLabel = json_encode(Dict::S('Attachments:NoAttachment'));
		$sDeleteColumnDef = $bIsDeleteAllowed ? '{ targets: [4], orderable: false},' : '';
		$oOutput->AddJs(
			<<<JS
// Collapse handlers
// - Collapsing by default to optimize form space
// It would be better to be able to construct the widget as collapsed, but in this case, datatables thinks the container is very small and therefore renders the table as if it was in microbox.
$('#{$sFieldWrapperId}').collapse({toggle: {$sCollapseJSInitState}});
// - Change toggle icon class
$('#{$sFieldWrapperId}')
	.on('shown.bs.collapse', function(){
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

var oTable_{$this->oField->GetGlobalId()};


// Build datatable
var buildTable_{$this->oField->GetGlobalId()} = function()
{
	oTable_{$this->oField->GetGlobalId()} = $("table#$sAttachmentTableId").DataTable( {
		"dom": "tp",
	    "order": [[3, "asc"]],
	    "columnDefs": [
	        $sDeleteColumnDef
	        { targets: '_all', orderable: true },
	    ],
	    "language": {
			"infoEmpty": $sNoAttachmentLabel,
			"zeroRecords": $sNoAttachmentLabel
		}
	} );
}
JS
		);

		// Removing upload input if in read only
		// TODO : Add max upload size when itop attachment has been refactored
		if (!$this->oField->GetReadOnly())
		{
			$oOutput->AddHtml('<div class="upload_container">'.Dict::S('Attachments:AddAttachment').'<input type="file" id="'.$this->oField->GetGlobalId().'" name="'.$this->oField->GetId().'" /><span class="loader glyphicon glyphicon-refresh"></span>'.InlineImage::GetMaxUpload().'</div>');
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
		     true,
			'{{sAttachmentThumbUrl}}',
			'{{sFileName}}',
			'{{sAttachmentMeta}}',
			'{{sFileSize}}',
			'{{iFileSizeRaw}}',
			'{{iFileDownloadsCount}}',
			'{{sAttachmentDate}}',
			'{{iAttachmentDateRaw}}',
			$bIsDeleteAllowed
		));
		$sAttachmentTableId = $this->GetAttachmentsTableId();
		$oOutput->AddJs(
			<<<JS
			var attachmentRowTemplate = $sAttachmentTableRowTemplate;
			function RemoveAttachment(sAttId)
			{
				$('#attachment_' + sAttId).attr('name', 'removed_attachments[]');
				$('#display_attachment_' + sAttId).hide();
				DecreaseAttachementsCount();
			}
			function IncreaseAttachementsCount()
			{
				UpdateAttachmentsCount(1);
			}
			function DecreaseAttachementsCount()
			{
				UpdateAttachmentsCount(-1);
			}
			function UpdateAttachmentsCount(iIncrement)
			{
				var countContainer = $("a#$sCollapseTogglerId>span.attachments-count"),
				iCountCurrentValue = parseInt(countContainer.text());
				countContainer.text(iCountCurrentValue+iIncrement);
			}

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
						var \$oAttachmentTBody = $(this).closest('.fileupload_field_content').find('.attachments_container table#$sAttachmentTableId>tbody'),
							iAttId = data.result.att_id,
							sDownloadLink = '{$this->oField->GetDownloadEndpoint()}'.replace(/-sAttachmentId-/, iAttId),
							sAttachmentMeta = '<input id="attachment_'+iAttId+'" type="hidden" name="attachments[]" value="'+iAttId+'"/>';

						// hide "no attachment" line if present
						\$oAttachmentFirstRow = \$oAttachmentTBody.find("tr:first-child");
						\$oAttachmentFirstRow.find("td[colspan]").closest("tr").hide();
						
						// update attachments count
						IncreaseAttachementsCount();
						 
						var replaces = [
							{search: "{{iAttId}}", replace:iAttId },
							{search: "{{lineStyle}}", replace:'' },
							{search: "{{sDocDownloadUrl}}", replace:sDownloadLink },
							{search: "{{sAttachmentThumbUrl}}", replace:data.result.icon },
							{search: "{{sFileName}}", replace: data.result.msg },
							{search: "{{sAttachmentMeta}}", replace:sAttachmentMeta },
							{search: "{{sFileSize}}", replace:data.result.file_size },
							{search: "{{iFileDownloadsCount}}", replace:data.result.downloads_count },
							{search: "{{sAttachmentDate}}", replace:data.result.creation_date },
						];
						var sAttachmentRow = attachmentRowTemplate   ;
						$.each(replaces, function(indexInArray, value ) {
							var re = new RegExp(value.search, 'gi');
							sAttachmentRow = sAttachmentRow.replace(re, value.replace);
						});
						
						var oElem = $(sAttachmentRow);
						if(!data.result.preview){
							oElem.find('[data-tooltip-html-enabled="true"]').removeAttr('data-tooltip-content');
							oElem.find('[data-tooltip-html-enabled="true"]').removeAttr('data-tooltip-html-enabled');
						}
						\$oAttachmentTBody.append(oElem);
						// Remove button handler
						$('#display_attachment_'+data.result.att_id+' :button').on('click', function(oEvent){
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

			// Remove button handler
			$('.attachments_container table#$sAttachmentTableId>tbody>tr>td :button').on('click', function(oEvent){
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
	 */
	protected function PrepareExistingFiles(RenderingOutput $oOutput, $bIsDeleteAllowed)
	{
		$sAttachmentTableId = $this->GetAttachmentsTableId();
		$sDeleteBtn = Dict::S('Portal:Button:Delete');

		// If in read only and no attachments, we display a short message
		if ($this->oField->GetReadOnly() && ($this->oAttachmentsSet->Count() === 0))
		{
			$oOutput->AddHtml(Dict::S('Attachments:NoAttachment'));
		}
		else
		{
			$sTableHead = self::GetAttachmentTableHeader($bIsDeleteAllowed);
			$oOutput->Addhtml(<<<HTML
<table id="$sAttachmentTableId" class="attachments-list table table-striped table-bordered responsive" cellspacing="0" width="100%">
	$sTableHead
<tbody>
HTML
			);

			/** @var \Attachment $oAttachment */
			while ($oAttachment = $this->oAttachmentsSet->Fetch())
			{
				$iAttId = $oAttachment->GetKey();

				$sLineStyle = '';

				$sAttachmentMeta = '<input id="attachment_'.$iAttId.'" type="hidden" name="attachments[]" value="'.$iAttId.'">';

				/** @var \ormDocument $oDoc */
				$oDoc = $oAttachment->Get('contents');
				$sFileName = utils::EscapeHtml($oDoc->GetFileName());

				$sDocDownloadUrl = str_replace('-sAttachmentId-', $iAttId, $this->oField->GetDownloadEndpoint());

				$sAttachmentThumbUrl = utils::GetAbsoluteUrlAppRoot().AttachmentPlugIn::GetFileIcon($sFileName);
				$bHasPreview = false;
				if ($oDoc->IsPreviewAvailable()) {
					$bHasPreview = true;
					$iMaxSizeForPreview = MetaModel::GetModuleSetting('itop-attachments', 'icon_preview_max_size', AbstractAttachmentsRenderer::DEFAULT_MAX_SIZE_FOR_PREVIEW);
					if ($oDoc->GetSize() <= $iMaxSizeForPreview)
					{
						$sAttachmentThumbUrl = $sDocDownloadUrl;
					}
				}

				$iFileSizeRaw = $oDoc->GetSize();
				$sFileSize = $oDoc->GetFormattedSize();
				$iFileDownloadsCount = $oDoc->GetDownloadsCount();

				$bIsTempAttachment = ($oAttachment->Get('item_id') === 0);
				$sAttachmentDate = '';
				$iAttachmentDateRaw = '';
				if (!$bIsTempAttachment)
				{
					$sAttachmentDate = $oAttachment->Get('creation_date');
					$iAttachmentDateRaw = AttributeDateTime::GetAsUnixSeconds($sAttachmentDate);
				}

				$oOutput->Addhtml(self::GetAttachmentTableRow(
					$iAttId,
					$sLineStyle,
					$sDocDownloadUrl,
					$bHasPreview,
					$sAttachmentThumbUrl,
					$sFileName,
					$sAttachmentMeta,
					$sFileSize,
					$iFileSizeRaw,
					$iFileDownloadsCount,
					$sAttachmentDate,
					$iAttachmentDateRaw,
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
	 * @param bool $bIsDeleteAllowed
	 *
	 * @return string
	 * @since 2.7.0
	 */
	protected static function GetAttachmentTableHeader($bIsDeleteAllowed)
	{
		$sTitleThumbnail = Dict::S('Attachments:File:Thumbnail');
		$sTitleFileName = Dict::S('Attachments:File:Name');
		$sTitleFileSize = Dict::S('Attachments:File:Size');
		$sTitleFileDate = Dict::S('Attachments:File:Date');
		$sTitleFileDownloadsCount = Dict::S('Attachments:File:DownloadsCount');

		// Optional column
		$sDeleteHeaderAsHtml = ($bIsDeleteAllowed) ? '<th role="delete" data-priority="1"></th>' : '';

		return <<<HTML
	<thead>
		<th role="icon">$sTitleThumbnail</th>
		<th role="filename" data-priority="1">$sTitleFileName</th>
		<th role="formatted-size">$sTitleFileSize</th>
		<th role="upload-date">$sTitleFileDate</th>
		<th role="downloads-count">$sTitleFileDownloadsCount</th>
		$sDeleteHeaderAsHtml
	</thead>
HTML;
	}

	/**
	 * @param int $iAttId
	 * @param string $sLineStyle
	 * @param string $sDocDownloadUrl
	 * @param bool $bHasPreview replace string $sIconClass since 3.0.1
	 * @param string $sAttachmentThumbUrl
	 * @param string $sFileName
	 * @param string $sAttachmentMeta
	 * @param string $sFileSize
	 * @param integer $iFileSizeRaw
	 * @param string $sAttachmentDate
	 * @param integer $iAttachmentDateRaw
	 * @param boolean $bIsDeleteAllowed
	 *
	 * @return string
	 * @since 2.7.0
	 */
	protected static function GetAttachmentTableRow(
		$iAttId, $sLineStyle, $sDocDownloadUrl, $bHasPreview, $sAttachmentThumbUrl, $sFileName, $sAttachmentMeta, $sFileSize,
		$iFileSizeRaw, $iFileDownloadsCount, $sAttachmentDate, $iAttachmentDateRaw, $bIsDeleteAllowed
	) {
		$sDeleteCell = '';
		if ($bIsDeleteAllowed)
		{
			$sDeleteBtnLabel = Dict::S('Portal:Button:Delete');
			$sDeleteCell = '<td role="delete"><input id="btn_remove_'.$iAttId.'" type="button" class="btn btn-xs btn-primary" value="'.$sDeleteBtnLabel.'"></td>';
		}
		$sHtml =  "<tr id=\"display_attachment_{$iAttId}\" class=\"attachment\" $sLineStyle>";

		if($bHasPreview) {
			$sHtml .= "<td role=\"icon\"><a href=\"$sDocDownloadUrl\" target=\"_blank\" data-tooltip-content=\"<img class='attachment-tooltip' src='{$sDocDownloadUrl}'>\" data-tooltip-html-enabled=true><img src=\"$sAttachmentThumbUrl\" ></a></td>";
		} else {
			$sHtml .= "<td role=\"icon\"><a href=\"$sDocDownloadUrl\" target=\"_blank\"><img src=\"$sAttachmentThumbUrl\" ></a></td>";
		}

		$sHtml .=  <<<HTML
		<td role="filename"><a href="$sDocDownloadUrl" target="_blank">$sFileName</a>$sAttachmentMeta</td>
	    <td role="formatted-size" data-order="$iFileSizeRaw">$sFileSize</td>
	    <td role="upload-date" data-order="$iAttachmentDateRaw">$sAttachmentDate</td>
	    <td role="downloads-count">$iFileDownloadsCount</td>
	    $sDeleteCell
	</tr>
HTML;
		return $sHtml;
	}

	/**
	 * @return string
	 */
	protected function GetAttachmentsTableId()
	{
		$sFormFieldId = $this->oField->GetGlobalId();
		$sAttachmentTableId = 'attachments-'.$sFormFieldId;

		return $sAttachmentTableId;
	}
}

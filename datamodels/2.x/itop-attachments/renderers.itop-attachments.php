<?php
// Copyright (C) 2010-2017 Combodo SARL
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

/**
 * Attachments rendering for iTop console.
 *
 * For the user portal, see \Combodo\iTop\Renderer\Bootstrap\FieldRenderer\BsFileUploadFieldRenderer
 */


define('ATTACHMENT_DOWNLOAD_URL', 'pages/ajax.document.php?operation=download_document&class=Attachment&field=contents&id=');
define('ATTACHMENTS_RENDERER', 'TableDetailsAttachmentsRenderer');


/**
 * For now this factory is just a helper to instanciate the renderer
 */
class AttachmentsRendererFactory
{
	/**
	 * @param \WebPage $oPage
	 * @param string $sObjClass class name of the objects holding the attachments
	 * @param int $iObjKey key of the objects holding the attachments
	 * @param string $sTransactionId CSRF token
	 *
	 * @return \AbstractAttachmentsRenderer rendering impl
	 */
	public static function GetInstance($oPage, $sObjClass, $iObjKey, $sTransactionId)
	{
		$sRendererClass = ATTACHMENTS_RENDERER;
		/** @var \AbstractAttachmentsRenderer $oAttachmentsRenderer */
		$oAttachmentsRenderer = new $sRendererClass($oPage, $sObjClass, $iObjKey, $sTransactionId);

		return $oAttachmentsRenderer;
	}
}


/**
 * Common code for attachment rendering
 *
 * On each attachment you'll need to have :
 *
 *  * an id on the attachment container (see GetAttachmentContainerId)
 *  * an input hidden inside the container (see GetAttachmentHiddenInput)
 *
 * @see \AttachmentPlugIn::DisplayAttachments()
 */
abstract class AbstractAttachmentsRenderer
{
	/**
	 * If size (in bits) is above this, then we will display a file icon instead of preview
	 */
	const MAX_SIZE_FOR_PREVIEW = 500000;

	/** @var \WebPage */
	protected $oPage;
	/**
	 * @var string CSRF token, must be provided cause when getting content from AJAX we need the one from the original page, not the
	 *     ajaxpage
	 */
	private $sTransactionId;
	/** @var string */
	protected $sObjClass;
	/** @var int */
	protected $iObjKey;
	/** @var \DBObjectSet */
	protected $oTempAttachmentsSet;
	/** @var \DBObjectSet */
	protected $oAttachmentsSet;

	/**
	 * @param \WebPage $oPage
	 * @param string $sObjClass class name of the objects holding the attachments
	 * @param int $iObjKey key of the objects holding the attachments
	 * @param string $sTransactionId CSRF token
	 *
	 * @throws \OQLException
	 */
	public function __construct(\WebPage $oPage, $sObjClass, $iObjKey, $sTransactionId)
	{
		$this->oPage = $oPage;
		$this->sObjClass = $sObjClass;
		$this->iObjKey = $iObjKey;
		$this->sTransactionId = $sTransactionId;

		$oSearch = DBObjectSearch::FromOQL('SELECT Attachment WHERE item_class = :class AND item_id = :item_id');
		$this->oAttachmentsSet = new DBObjectSet($oSearch, array(), array('class' => $sObjClass, 'item_id' => $iObjKey));

		$oSearchTemp = DBObjectSearch::FromOQL('SELECT Attachment WHERE temp_id = :temp_id');
		$this->oTempAttachmentsSet = new DBObjectSet($oSearchTemp, array(), array('temp_id' => $this->sTransactionId));
	}

	/**
	 * @return \DBObjectSet
	 */
	public function GetTempAttachmentsSet()
	{
		return $this->oTempAttachmentsSet;
	}

	/**
	 * @return \DBObjectSet
	 */
	public function GetAttachmentsSet()
	{
		return $this->oAttachmentsSet;
	}

	public function GetAttachmentsCount()
	{
		return $this->GetAttachmentsSet()->Count() + $this->GetTempAttachmentsSet()->Count();
	}

	/**
	 * @param int[] $aAttachmentsDeleted Attachments id that should be deleted after form submission
	 *
	 * @return string
	 */
	abstract public function RenderEditAttachmentsList($aAttachmentsDeleted = array());

	abstract public function RenderViewAttachmentsList();

	protected function AddUploadButton()
	{
		$sClass = $this->sObjClass;
		$sId = $this->iObjKey;

		$this->oPage->add('<div style="clear:both"></div>');
		$iMaxUploadInBytes = AttachmentPlugIn::GetMaxUploadSize();
		$sMaxUploadLabel = AttachmentPlugIn::GetMaxUpload();
		$sFileTooBigLabel = Dict::Format('Attachments:Error:FileTooLarge', $sMaxUploadLabel);
		$sFileTooBigLabelForJS = addslashes($sFileTooBigLabel);
		$this->oPage->p(Dict::S('Attachments:AddAttachment').'<input type="file" name="file" id="file"><span style="display:none;" id="attachment_loading">&nbsp;<img src="../images/indicator.gif"></span> '.$sMaxUploadLabel);

		$this->oPage->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/jquery.iframe-transport.js');
		$this->oPage->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/jquery.fileupload.js');

		$this->oPage->add_ready_script(
			<<<JS
	function RefreshAttachmentsDisplay()
	{
		var sContentNode = '#AttachmentsContent',
			aAttachmentsDeletedHiddenInputs = $('table.attachmentsList>tbody>tr[id^="display_attachment_"]>td input[name="removed_attachments[]"]'),
			aAttachmentsDeletedIds = aAttachmentsDeletedHiddenInputs.map(function() { return $(this).val() }).toArray();
		$(sContentNode).block();
		$.post(GetAbsoluteUrlModulesRoot()+'itop-attachments/ajax.itop-attachment.php',
		   { 
		   	    operation: 'refresh_attachments_render', 
		   	    objclass: '$sClass', 
		   	    objkey: $sId, 
		   	    temp_id: '$this->sTransactionId', 
		   	    edit_mode: 1, 
		   	    attachments_deleted: aAttachmentsDeletedIds
	        },
		   function(data) {
			 $(sContentNode).html(data);
			 $(sContentNode).unblock();
			}
		 );
	}
	
    $('#file').fileupload({
		url: GetAbsoluteUrlModulesRoot()+'itop-attachments/ajax.itop-attachment.php',
		formData: { operation: 'add', temp_id: '$this->sTransactionId', obj_class: '$sClass' },
        dataType: 'json',
		pasteZone: null, // Don't accept files via Chrome's copy/paste
        done: RefreshAttachmentsDisplay,
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
        	$('#attachment_loading').show();
		},
        stop: function() {
        	$('#attachment_loading').hide();
		}
    });

	$(document).bind('dragover', function (e) {
		var bFiles = false;
		if (e.dataTransfer && e.dataTransfer.types)
		{
			for (var i = 0; i < e.dataTransfer.types.length; i++)
			{
				if (e.dataTransfer.types[i] == "application/x-moz-nativeimage")
				{
					bFiles = false; // mozilla contains "Files" in the types list when dragging images inside the page, but it also contains "application/x-moz-nativeimage" before
					break;
				}
				
				if (e.dataTransfer.types[i] == "Files")
				{
					bFiles = true;
					break;
				}
			}
		}
	
		if (!bFiles) return; // Not dragging files
		
		var dropZone = $('#file').closest('fieldset');
		if (!dropZone.is(':visible'))
		{
			// Hidden, but inside an inactive tab? Higlight the tab
			var sTabId = dropZone.closest('.ui-tabs-panel').attr('aria-labelledby');
			dropZone = $('#'+sTabId).closest('li');
		}
	    timeout = window.dropZoneTimeout;
	    if (!timeout) {
	        dropZone.addClass('drag_in');
	    } else {
	        clearTimeout(timeout);
	    }
	    window.dropZoneTimeout = setTimeout(function () {
	        window.dropZoneTimeout = null;
	        dropZone.removeClass('drag_in');
	    }, 300);
	});
	
	// check if the attachments are used by inline images
	window.setTimeout( function() {
		$('.attachment a').each(function() {
			var sUrl = $(this).attr('href');
			if($('img[src="'+sUrl+'"]').length > 0)
			{
				$(this).addClass('image-in-use').find('img').wrap('<div class="image-in-use-wrapper" style="position:relative;display:inline-block;"></div>');
			}
		});
		$('.htmlEditor').each(function() {
			var oEditor = $(this).ckeditorGet();
			var sHtml = oEditor.getData();
			var jElement = $('<div/>').html(sHtml).contents();
			jElement.find('img').each(function() {
				var sSrc = $(this).attr('src');
				$('.attachment a[href="'+sSrc+'"]').parent().addClass('image-in-use').find('img').wrap('<div class="image-in-use-wrapper" style="position:relative;display:inline-block;"></div>');
			});
		});
		$('.image-in-use-wrapper').append('<div style="position:absolute;top:0;left:0;"><img src="../images/transp-lock.png"></div>');
	}, 200 );
JS
		);
		$this->oPage->p('<span style="display:none;" id="attachment_loading">Loading, please wait...</span>');
		$this->oPage->p('<input type="hidden" id="attachment_plugin" name="attachment_plugin"/>');

		$this->oPage->add_style(<<<CSS
.drag_in {
	-webkit-box-shadow:inset 0 0 10px 2px #1C94C4;
	box-shadow:inset 0 0 10px 2px #1C94C4;
}
CSS
		);
	}

	protected function GetAttachmentContainerId($iAttachmentId)
	{
		return 'display_attachment_'.$iAttachmentId;
	}

	protected function GetAttachmentHiddenInput($iAttachmentId, $bIsDeletedAttachment)
	{
		$sInputNamePrefix = $bIsDeletedAttachment ? 'removed_' : '';

		return '<input id="attachment_'.$iAttachmentId.'" type="hidden" name="'.$sInputNamePrefix.'attachments[]" value="'.$iAttachmentId.'">';
	}

	protected function GetDeleteAttachmentButton($iAttId)
	{
		return '<input id="btn_remove_'.$iAttId.'" type="button" class="btn_hidden" value="'.Dict::S('Attachments:DeleteBtn').'" onClick="RemoveAttachment('.$iAttId.');"/>';
	}

	protected function GetDeleteAttachmentJs()
	{
		return <<<JS
	function RemoveAttachment(att_id)
	{
		var bDelete = true;
		if ($('#display_attachment_'+att_id).hasClass('image-in-use'))
		{
				bDelete = window.confirm('This image is used in a description. Delete it anyway?');
		}
		if (bDelete)
		{
			$('#attachment_'+att_id).attr('name', 'removed_attachments[]');
			$('#display_attachment_'+att_id).hide();
			$('#attachment_plugin').trigger('remove_attachment', [att_id]);
		}
		return false; // Do not submit the form !
	}
JS;
	}
}


class TableDetailsAttachmentsRenderer extends AbstractAttachmentsRenderer
{
	private function AddAttachmentsTable($bWithDeleteButton, $aAttachmentsDeleted = array())
	{
		if ($this->GetAttachmentsCount() === 0)
		{
			$this->oPage->add(Dict::S('Attachments:NoAttachment'));

			return;
		}


		$sThumbnail = Dict::S('Attachments:File:Thumbnail');
		$sFileName = Dict::S('Attachments:File:Name');
		$sFileSize = Dict::S('Attachments:File:Size');
		$sFileDate = Dict::S('Attachments:File:Date');
		$sFileCreator = Dict::S('Attachments:File:Creator');
		$sFileType = Dict::S('Attachments:File:MimeType');
		$sDeleteColumn = '';
		if ($bWithDeleteButton)
		{
			$sDeleteColumn = '<th></th>';
		}
		$this->oPage->add(<<<HTML
<table class="listResults attachmentsList">
	<thead>
		<th>$sThumbnail</th>
		<th>$sFileName</th>
		<th>$sFileSize</th>
		<th>$sFileDate</th>
		<th>$sFileCreator</th>
		<th>$sFileType</th>
		$sDeleteColumn
	</thead>
<tbody>
HTML
		);

		$iMaxWidth = MetaModel::GetModuleSetting('itop-attachments', 'preview_max_width', 290);
		$sPreviewNotAvailable = addslashes(Dict::S('Attachments:PreviewNotAvailable'));
		$this->oPage->add_ready_script(
			<<<JS
$(document).tooltip({
	items: 'table.attachmentsList>tbody>tr>td a.trigger-preview',
	position: {
		my: 'left top', at: 'right top', using: function (position, feedback) {
			$(this).css(position);
		}
	},
	content: function () {
		if ($(this).hasClass("preview"))
		{
			return ('<img style=\"max-width:{$iMaxWidth}px\" src=\"'+$(this).attr('href')+'\"></img>');
		}
		else
		{
			return '$sPreviewNotAvailable';
		}
	}
});
JS
		);
		if ($bWithDeleteButton)
		{
			$this->oPage->add_script($this->GetDeleteAttachmentJs());
		}
		$this->oPage->add_style(
			<<<CSS
table.attachmentsList>tbody>tr>td:first-child {
	text-align: center;
}
CSS
		);

		$bIsEven = false;
		$aAttachmentsDate = AttachmentsHelper::GetAttachmentsDateAddedFromDb($this->sObjClass, $this->iObjKey);
		while ($oAttachment = $this->oAttachmentsSet->Fetch())
		{
			$bIsEven = ($bIsEven) ? false : true;
			$this->AddAttachmentsTableLine($bWithDeleteButton, $bIsEven, $oAttachment, $aAttachmentsDate, $aAttachmentsDeleted);
		}
		while ($oTempAttachment = $this->oTempAttachmentsSet->Fetch())
		{
			$bIsEven = ($bIsEven) ? false : true;
			$this->AddAttachmentsTableLine($bWithDeleteButton, $bIsEven, $oTempAttachment, $aAttachmentsDate, $aAttachmentsDeleted);
		}

		$this->oPage->add('</tbody>'.PHP_EOL);
		$this->oPage->add('</table>'.PHP_EOL);
	}

	/**
	 * @param $bWithDeleteButton
	 * @param $bIsEven
	 * @param \DBObject $oAttachment
	 * @param array $aAttachmentsDate
	 * @param int[] $aAttachmentsDeleted
	 *
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 */
	private function AddAttachmentsTableLine($bWithDeleteButton, $bIsEven, $oAttachment, $aAttachmentsDate, $aAttachmentsDeleted)
	{
		$iAttachmentId = $oAttachment->GetKey();

		$sLineClass = '';
		if ($bIsEven)
		{
			$sLineClass = 'class="even"';
		}

		$sLineStyle = '';
		$bIsDeletedAttachment = false;
		if (in_array($iAttachmentId, $aAttachmentsDeleted, true))
		{
			$sLineStyle = 'style="display: none;"';
			$bIsDeletedAttachment = true;
		}

		/** @var \ormDocument $oDoc */
		$oDoc = $oAttachment->Get('contents');

		$sDocDownloadUrl = utils::GetAbsoluteUrlAppRoot().ATTACHMENT_DOWNLOAD_URL.$iAttachmentId;
		$sFileName = utils::HtmlEntities($oDoc->GetFileName());
		$sTrId = $this->GetAttachmentContainerId($iAttachmentId);
		$sAttachmentMeta = $this->GetAttachmentHiddenInput($iAttachmentId, $bIsDeletedAttachment);
		$sFileSize = $oDoc->GetFormattedSize();
		$bIsTempAttachment = ($oAttachment->Get('item_id') === 0);
		$sAttachmentDate = '';
		if (!$bIsTempAttachment)
		{
			$sAttachmentDate = $oAttachment->Get('creation_date');
			if (empty($sAttachmentDate) && array_key_exists($iAttachmentId, $aAttachmentsDate))
			{
				$sAttachmentDate = $aAttachmentsDate[$iAttachmentId];
			}
		}

		$sAttachmentCreator = $oAttachment->Get('contact_id_friendlyname');

		$sFileType = $oDoc->GetMimeType();

		$sAttachmentThumbUrl = utils::GetAbsoluteUrlAppRoot().AttachmentPlugIn::GetFileIcon($sFileName);
		$sIconClass = '';
		if ($oDoc->IsPreviewAvailable())
		{
			$sIconClass = ' preview';
			if ($oDoc->GetSize() <= self::MAX_SIZE_FOR_PREVIEW)
			{
				$sAttachmentThumbUrl = $sDocDownloadUrl;
			}
		}

		$sDeleteColumn = '';
		if ($bWithDeleteButton)
		{
			$sDeleteButton = $this->GetDeleteAttachmentButton($iAttachmentId);
			$sDeleteColumn = "<td>$sDeleteButton</td>";
		}

		$this->oPage->add(<<<HTML
	<tr id="$sTrId" $sLineClass $sLineStyle>
	  <td><a href="$sDocDownloadUrl" target="_blank" class="trigger-preview $sIconClass"><img $sIconClass style="max-height: 48px;" src="$sAttachmentThumbUrl"></a></td>
	  <td><a href="$sDocDownloadUrl" target="_blank" class="$sIconClass">$sFileName</a>$sAttachmentMeta</td>
	  <td>$sFileSize</td>
	  <td>$sAttachmentDate</td>
	  <td>$sAttachmentCreator</td>
	  <td>$sFileType</td>
	  $sDeleteColumn
	</tr>
HTML
		);
	}

	/**
	 * @inheritDoc
	 */
	public function RenderEditAttachmentsList($aAttachmentsDeleted = array())
	{
		$this->AddUploadButton();

		$this->AddAttachmentsTable(true, $aAttachmentsDeleted);
	}

	/**
	 * @inheritDoc
	 */
	public function RenderViewAttachmentsList()
	{
		$this->AddAttachmentsTable(false);
	}
}

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

define('ATTACHMENT_DOWNLOAD_URL', 'pages/ajax.document.php?operation=download_document&class=Attachment&field=contents&id=');

class AttachmentPlugIn implements iApplicationUIExtension, iApplicationObjectExtension
{
	protected static $m_bIsModified = false;

	public function OnDisplayProperties($oObject, WebPage $oPage, $bEditMode = false)
	{
		if ($this->GetAttachmentsPosition() == 'properties')
		{
			$this->DisplayAttachments($oObject, $oPage, $bEditMode);		
		}
	}

	public function OnDisplayRelations($oObject, WebPage $oPage, $bEditMode = false)
	{
		if ($this->GetAttachmentsPosition() == 'relations')
		{
			$this->DisplayAttachments($oObject, $oPage, $bEditMode);		
		}
	}

	public function OnFormSubmit($oObject, $sFormPrefix = '')
	{
		if ($this->IsTargetObject($oObject))
		{
			// For new objects attachments are processed in OnDBInsert
			if (!$oObject->IsNew())
			{
				self::UpdateAttachments($oObject);
			}
		}
	}

	protected function GetMaxUpload()
	{
		$iMaxUpload = ini_get('upload_max_filesize');
		if (!$iMaxUpload)
		{
			$sRet = Dict::S('Attachments:UploadNotAllowedOnThisSystem');
		}
		else
		{
			$iMaxUpload = utils::ConvertToBytes($iMaxUpload);
			if ($iMaxUpload > 1024*1024*1024)
			{
				$sRet = Dict::Format('Attachment:Max_Go', sprintf('%0.2f', $iMaxUpload/(1024*1024*1024)));
			}
			else if ($iMaxUpload > 1024*1024)
			{
				$sRet = Dict::Format('Attachment:Max_Mo', sprintf('%0.2f', $iMaxUpload/(1024*1024)));
			}
			else
			{
				$sRet = Dict::Format('Attachment:Max_Ko', sprintf('%0.2f', $iMaxUpload/(1024)));
			}
		}
		return $sRet;
	}
	
	public function OnFormCancel($sTempId)
	{
		// Delete all "pending" attachments for this form
		$sOQL = 'SELECT Attachment WHERE temp_id = :temp_id';
		$oSearch = DBObjectSearch::FromOQL($sOQL);
		$oSet = new DBObjectSet($oSearch, array(), array('temp_id' => $sTempId));
		while($oAttachment = $oSet->Fetch())
		{
			$oAttachment->DBDelete();
			// Pending attachment, don't mention it in the history
		}
	}

	public function EnumUsedAttributes($oObject)
	{
		return array();
	}

	public function GetIcon($oObject)
	{
		return '';
	}

	public function GetHilightClass($oObject)
	{
		// Possible return values are:
		// HILIGHT_CLASS_CRITICAL, HILIGHT_CLASS_WARNING, HILIGHT_CLASS_OK, HILIGHT_CLASS_NONE	
		return HILIGHT_CLASS_NONE;
	}

	public function EnumAllowedActions(DBObjectSet $oSet)
	{
		// No action
		return array();
    }

	public function OnIsModified($oObject)
	{
		return self::$m_bIsModified;
	}

	public function OnCheckToWrite($oObject)
	{
		return array();
	}

	public function OnCheckToDelete($oObject)
	{
		return array();
	}

	public function OnDBUpdate($oObject, $oChange = null)
	{
		if ($this->IsTargetObject($oObject))
		{
			// Get all current attachments
			$oSearch = DBObjectSearch::FromOQL("SELECT Attachment WHERE item_class = :class AND item_id = :item_id");
			$oSet = new DBObjectSet($oSearch, array(), array('class' => get_class($oObject), 'item_id' => $oObject->GetKey()));
			while ($oAttachment = $oSet->Fetch())
			{
				$oAttachment->SetItem($oObject, true /*updateonchange*/);
			}			
		}
	}
	
	public function OnDBInsert($oObject, $oChange = null)
	{
		if ($this->IsTargetObject($oObject))
		{
			self::UpdateAttachments($oObject, $oChange);
		}
	}
	
	public function OnDBDelete($oObject, $oChange = null)
	{
		if ($this->IsTargetObject($oObject))
		{
			$oSearch = DBObjectSearch::FromOQL("SELECT Attachment WHERE item_class = :class AND item_id = :item_id");
			$oSet = new DBObjectSet($oSearch, array(), array('class' => get_class($oObject), 'item_id' => $oObject->GetKey()));
			while ($oAttachment = $oSet->Fetch())
			{
				$oAttachment->DBDelete();
			}
		}			
	}

	///////////////////////////////////////////////////////////////////////////////////////////////////////
	//
	// Plug-ins specific functions
	//
	///////////////////////////////////////////////////////////////////////////////////////////////////////
	
	protected function IsTargetObject($oObject)
	{
		$aAllowedClasses = MetaModel::GetModuleSetting('itop-attachments', 'allowed_classes', array('Ticket'));
		foreach($aAllowedClasses as $sAllowedClass)
		{
			if ($oObject instanceof $sAllowedClass)
			{
				return true;
			}
		}
		return false;
	}

	protected function GetAttachmentsPosition()
	{
		return MetaModel::GetModuleSetting('itop-attachments', 'position', 'relations');
	}

	var $m_bDeleteEnabled = true;

	public function EnableDelete($bEnabled)
	{
		$this->m_bDeleteEnabled = $bEnabled;
	}

	public function DisplayAttachments($oObject, WebPage $oPage, $bEditMode = false)
	{
		// Exit here if the class is not allowed
		if (!$this->IsTargetObject($oObject)) return;
		
		$oSearch = DBObjectSearch::FromOQL("SELECT Attachment WHERE item_class = :class AND item_id = :item_id");
		$oSet = new DBObjectSet($oSearch, array(), array('class' => get_class($oObject), 'item_id' => $oObject->GetKey()));
		if ($this->GetAttachmentsPosition() == 'relations')
		{
			$sTitle = ($oSet->Count() > 0)? Dict::Format('Attachments:TabTitle_Count', $oSet->Count()) : Dict::S('Attachments:EmptyTabTitle');
			$oPage->SetCurrentTab($sTitle);
		}
		$sMaxWidth = MetaModel::GetModuleSetting('itop-attachment', 'inline_image_max_width', '450px');
		$oPage->add_style(
<<<EOF
.attachment {
	display: inline-block;
	text-align:center;
	float:left;
	padding:5px;	
}
.attachment:hover {
	background-color: #e0e0e0;
}
.attachment img {
	border: 0;
}
.attachment a {
	text-decoration: none;
	color: #1C94C4;
}
.btn_hidden {
	display: none;
}
.drag_in {
	-webkit-box-shadow:inset 0 0 10px 2px #1C94C4;
	box-shadow:inset 0 0 10px 2px #1C94C4;
}
#history .attachment-history-added {
	padding: 0;
	float: none;
}
.inline-image {
	cursor: zoom-in;
}
EOF
		);
		$oPage->add('<fieldset>');
		$oPage->add('<legend>'.Dict::S('Attachments:FieldsetTitle').'</legend>');

		if ($bEditMode)
		{
			$sIsDeleteEnabled = $this->m_bDeleteEnabled ? 'true' : 'false';
			$iTransactionId = $oPage->GetTransactionId();
			$sClass = get_class($oObject);
			$iObjectId = $oObject->Getkey();
			$sTempId = session_id().'_'.$iTransactionId;
			$sDeleteBtn = Dict::S('Attachments:DeleteBtn');
			$oPage->add_script(
<<<EOF
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
EOF
);
			$oPage->add('<span id="attachments">');
			while ($oAttachment = $oSet->Fetch())
			{
				$iAttId = $oAttachment->GetKey();
				$oDoc = $oAttachment->Get('contents');
				$sFileName = htmlentities($oDoc->GetFileName(), ENT_QUOTES, 'UTF-8');
				$sIcon = utils::GetAbsoluteUrlAppRoot().AttachmentPlugIn::GetFileIcon($sFileName);
				$sPreview = $oDoc->IsPreviewAvailable() ? 'true' : 'false';
				$sDownloadLink = utils::GetAbsoluteUrlAppRoot().ATTACHMENT_DOWNLOAD_URL.$iAttId;
				$oPage->add('<div class="attachment" id="display_attachment_' . $iAttId . '"><a data-preview="' . $sPreview . '" href="' . $sDownloadLink . '"><img src="' . $sIcon . '"><br/>' . $sFileName . '<input id="attachment_' . $iAttId . '" type="hidden" name="attachments[]" value="' . $iAttId . '"/></a><br/>&nbsp;<input id="btn_remove_' . $iAttId . '" type="button" class="btn_hidden" value="' . Dict::S('Attachments:DeleteBtn') . '" onClick="RemoveAttachment(' . $iAttId . ');"/>&nbsp;</div>');
			}
			
			// Suggested attachments are listed here but treated as temporary
			$aDefault = utils::ReadParam('default', array(), false, 'raw_data');
			if (array_key_exists('suggested_attachments', $aDefault))
			{
				$sSuggestedAttachements = $aDefault['suggested_attachments'];
				if (is_array($sSuggestedAttachements))
				{
					$sSuggestedAttachements = implode(',', $sSuggestedAttachements);
				}
				$oSearch = DBObjectSearch::FromOQL("SELECT Attachment WHERE id IN($sSuggestedAttachements)");
				$oSet = new DBObjectSet($oSearch, array());
				if ($oSet->Count() > 0)
				{
					while ($oAttachment = $oSet->Fetch())
					{
						// Mark the attachments as temporary attachments for the current object/form
						$oAttachment->Set('temp_id', $sTempId);
						$oAttachment->DBUpdate();
						// Display them
						$iAttId = $oAttachment->GetKey();
						$oDoc = $oAttachment->Get('contents');
						$sFileName = htmlentities($oDoc->GetFileName(), ENT_QUOTES, 'UTF-8');
						$sIcon = utils::GetAbsoluteUrlAppRoot().AttachmentPlugIn::GetFileIcon($sFileName);
						$sDownloadLink = utils::GetAbsoluteUrlAppRoot().ATTACHMENT_DOWNLOAD_URL.$iAttId;
						$sPreview = $oDoc->IsPreviewAvailable() ? 'true' : 'false';
						$oPage->add('<div class="attachment" id="display_attachment_'.$iAttId.'"><a data-preview="'.$sPreview.'" href="'.$sDownloadLink.'"><img src="'.$sIcon.'"><br/>'.$sFileName.'<input id="attachment_'.$iAttId.'" type="hidden" name="attachments[]" value="'.$iAttId.'"/></a><br/>&nbsp;<input id="btn_remove_'.$iAttId.'" type="button" class="btn_hidden" value="Delete" onClick="RemoveAttachment('.$iAttId.');"/>&nbsp;</div>');
						$oPage->add_ready_script("$('#attachment_plugin').trigger('add_attachment', [$iAttId, '".addslashes($sFileName)."', false /* not an line image */]);");
					}
				}
			}
			
			$oPage->add('</span>');			
			$oPage->add('<div style="clear:both"></div>');			
			$sMaxUpload = $this->GetMaxUpload();
			$oPage->p(Dict::S('Attachments:AddAttachment').'<input type="file" name="file" id="file"><span style="display:none;" id="attachment_loading">&nbsp;<img src="../images/indicator.gif"></span> '.$sMaxUpload);
			
			$oPage->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/jquery.iframe-transport.js');
			$oPage->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/jquery.fileupload.js');		
			
			$sDownloadLink = utils::GetAbsoluteUrlAppRoot().ATTACHMENT_DOWNLOAD_URL;		
			$oPage->add_ready_script(
<<< EOF
    $('#file').fileupload({
		url: GetAbsoluteUrlModulesRoot()+'itop-attachments/ajax.attachment.php',
		formData: { operation: 'add', temp_id: '$sTempId', obj_class: '$sClass' },
        dataType: 'json',
		pasteZone: null, // Don't accept files via Chrome's copy/paste
        done: function (e, data) {
			if(typeof(data.result.error) != 'undefined')
			{
				if(data.result.error != '')
				{
					alert(data.result.error);
				}
				else
				{
					var sDownloadLink = '$sDownloadLink'+data.result.att_id;
					$('#attachments').append('<div class="attachment" id="display_attachment_'+data.result.att_id+'"><a data-preview="'+data.result.preview+'" href="'+sDownloadLink+'"><img src="'+data.result.icon+'"><br/>'+data.result.msg+'<input id="attachment_'+data.result.att_id+'" type="hidden" name="attachments[]" value="'+data.result.att_id+'"/></a><br/><input type="button" class="btn_hidden" value="{$sDeleteBtn}" onClick="RemoveAttachment('+data.result.att_id+');"/></div>');
					if($sIsDeleteEnabled)
					{
						$('#display_attachment_'+data.result.att_id).hover( function() { $(this).children(':button').toggleClass('btn_hidden'); } );
					}
					$('#attachment_plugin').trigger('add_attachment', [data.result.att_id, data.result.msg, false /* inline image */]);
				}
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
EOF
);
			$oPage->p('<span style="display:none;" id="attachment_loading">Loading, please wait...</span>');
			$oPage->p('<input type="hidden" id="attachment_plugin" name="attachment_plugin"/>');
			if ($this->m_bDeleteEnabled)
			{
				$oPage->add_ready_script('$(".attachment").hover( function() {$(this).children(":button").toggleClass("btn_hidden"); } );');
			}
		}
		else
		{
			$oPage->add('<span id="attachments">');
			if ($oSet->Count() == 0)
			{
				$oPage->add(Dict::S('Attachments:NoAttachment'));	
			}
			else
			{
				while ($oAttachment = $oSet->Fetch())
				{
					$iAttId = $oAttachment->GetKey();
					$oDoc = $oAttachment->Get('contents');
					$sFileName = htmlentities($oDoc->GetFileName(), ENT_QUOTES, 'UTF-8');
					$sIcon = utils::GetAbsoluteUrlAppRoot().AttachmentPlugIn::GetFileIcon($sFileName);
					$sPreview = $oDoc->IsPreviewAvailable() ? 'true' : 'false';
					$sDownloadLink = utils::GetAbsoluteUrlAppRoot().ATTACHMENT_DOWNLOAD_URL.$iAttId;
					$oPage->add('<div class="attachment" id="attachment_'.$iAttId.'"><a data-preview="'.$sPreview.'" href="'.$sDownloadLink.'"><img src="'.$sIcon.'"><br/>'.$sFileName.'</a><input type="hidden" name="attachments[]" value="'.$iAttId.'"/><br/>&nbsp;&nbsp;</div>');
				}
			}
			$oPage->add('</span>');
		}
		$oPage->add('</fieldset>');
		$sPreviewNotAvailable = addslashes(Dict::S('Attachments:PreviewNotAvailable'));
		$iMaxWidth = MetaModel::GetModuleSetting('itop-attachments', 'preview_max_width', 290);
		$oPage->add_ready_script(
<<<EOF
	$(document).tooltip({
		items: '.attachment a',
		position: { my: 'left top', at: 'right top', using: function( position, feedback ) { $( this ).css( position ); }},
		content: function() { if ($(this).attr('data-preview') == 'true') { return('<img style=\"max-width:{$iMaxWidth}px\" src=\"'+$(this).attr('href')+'\"></img>');} else { return '$sPreviewNotAvailable'; }}
	});
EOF
		);
	}

	protected static function UpdateAttachments($oObject, $oChange = null)
	{
		self::$m_bIsModified = false;

		if (utils::ReadParam('attachment_plugin', 'not-in-form') == 'not-in-form')
		{
			// Workaround to an issue in iTop < 2.0
			// Leave silently if there is no trace of the attachment form
			return;
		}
		$iTransactionId = utils::ReadParam('transaction_id', null);
		if (!is_null($iTransactionId))
		{
			$aActions = array();
			$aAttachmentIds = utils::ReadParam('attachments', array());
			$aRemovedAttachmentIds = utils::ReadParam('removed_attachments', array());
			
			// Get all current attachments
			$oSearch = DBObjectSearch::FromOQL("SELECT Attachment WHERE item_class = :class AND item_id = :item_id");
			$oSet = new DBObjectSet($oSearch, array(), array('class' => get_class($oObject), 'item_id' => $oObject->GetKey()));
			while ($oAttachment = $oSet->Fetch())
			{
				// Remove attachments that are no longer attached to the current object
				if (in_array($oAttachment->GetKey(), $aRemovedAttachmentIds))
				{
					$oAttachment->DBDelete();
					$aActions[] = self::GetActionChangeOp($oAttachment, false /* false => deletion */);
				}
			}			

			// Attach new (temporary) attachements
			$sTempId = session_id().'_'.$iTransactionId;
			// The object is being created from a form, check if there are pending attachments
			// for this object, but deleting the "new" ones that were already removed from the form
			$sOQL = 'SELECT Attachment WHERE temp_id = :temp_id';
			$oSearch = DBObjectSearch::FromOQL($sOQL);
			foreach($aAttachmentIds as $iAttachmentId)
			{
				$oSet = new DBObjectSet($oSearch, array(), array('temp_id' => $sTempId));
				while($oAttachment = $oSet->Fetch())
				{
					if (in_array($oAttachment->GetKey(),$aRemovedAttachmentIds))
					{
						$oAttachment->DBDelete();
						// temporary attachment removed, don't even mention it in the history
					}
					else
					{
						$oAttachment->SetItem($oObject);
						$oAttachment->Set('temp_id', '');
						$oAttachment->DBUpdate();
						// temporary attachment confirmed, list it in the history
						$aActions[] = self::GetActionChangeOp($oAttachment, true /* true => creation */);
					}
				}
			}
			if (count($aActions) > 0)
			{
				foreach($aActions as $oChangeOp)
				{
					self::RecordHistory($oChange, $oObject, $oChangeOp);
				}
				self::$m_bIsModified = true;
			}
		}
	}
	
	/////////////////////////////////////////////////////////////////////////////////////////
	public static function GetFileIcon($sFileName)
	{
		$aPathParts = pathinfo($sFileName);
		if (!array_key_exists('extension', $aPathParts))
		{
			// No extension: use the default icon
			$sIcon = 'document.png';
		}
		else 
		{
			switch($aPathParts['extension'])
			{
				case 'doc':
				case 'docx':
				$sIcon = 'doc.png';
				break;
				
				case 'xls':
				case 'xlsx':
				$sIcon = 'xls.png';
				break;
				
				case 'ppt':
				case 'pptx':
				$sIcon = 'ppt.png';
				break;
				
				case 'pdf':
				$sIcon = 'pdf.png';
				break;
				
				case 'txt':
				case 'text':
				$sIcon = 'txt.png';
				break;
				
				case 'rtf':
				$sIcon = 'rtf.png';
				break;
				
				case 'odt':
				$sIcon = 'odt.png';
				break;
				
				case 'ods':
				$sIcon = 'ods.png';
				break;
				
				case 'odp':
				$sIcon = 'odp.png';
				break;
				
				case 'html':
				case 'htm':
				$sIcon = 'html.png';
				break;
				
				case 'png':
				case 'gif':
				case 'jpg':
				case 'jpeg':
				case 'tiff':
				case 'tif':
				case 'bmp':
				$sIcon = 'image.png';
				
				break;
				case 'zip':
				case 'gz':
				case 'tgz':
				case 'rar':
				$sIcon = 'zip.png';
				break;
	
				default:
				$sIcon = 'document.png';
				break;
			}
		}
		
		return 'env-'.utils::GetCurrentEnvironment()."/itop-attachments/icons/$sIcon";
	}
	
	/////////////////////////////////////////////////////////////////////////
	private static function RecordHistory($oChange, $oTargetObject, $oMyChangeOp)
	{
		if (!is_null($oChange))
		{
			$oMyChangeOp->Set("change", $oChange->GetKey());
		}
		$oMyChangeOp->Set("objclass", get_class($oTargetObject));
		$oMyChangeOp->Set("objkey", $oTargetObject->GetKey());
		$iId = $oMyChangeOp->DBInsertNoReload();
	}

	/////////////////////////////////////////////////////////////////////////
	private static function GetActionChangeOp($oAttachment, $bCreate = true)
	{
		$oBlob = $oAttachment->Get('contents');
		$sFileName = $oBlob->GetFileName();
		if ($bCreate)
		{
			$oChangeOp = new CMDBChangeOpAttachmentAdded();
			$oChangeOp->Set('attachment_id', $oAttachment->GetKey());
			$oChangeOp->Set('filename', $sFileName);
		}
		else
		{
			$oChangeOp = new CMDBChangeOpAttachmentRemoved();
			$oChangeOp->Set('filename', $sFileName);
		}
		return $oChangeOp;
	}	
}

/**
 * Record the modification of a caselog (text)
 * since the caselog itself stores the history
 * of its entries, there is no need to duplicate
 * the text here
 *
 * @package     iTopORM
 */
class CMDBChangeOpAttachmentAdded extends CMDBChangeOp
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "core/cmdb",
			"key_type" => "",
			"name_attcode" => "change",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_changeop_attachment_added",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeExternalKey("attachment_id", array("targetclass"=>"Attachment", "allowed_values"=>null, "sql"=>"attachment_id", "is_null_allowed"=>true, "on_target_delete"=>DEL_SILENT, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("filename", array("allowed_values"=>null, "sql"=>"filename", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		
		// Display lists
		MetaModel::Init_SetZListItems('details', array('attachment_id')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('attachment_id')); // Attributes to be displayed for a list
	}
	
	/**
	 * Describe (as a text string) the modifications corresponding to this change
	 */	 
	public function GetDescription()
	{
		// Temporary, until we change the options of GetDescription() -needs a more global revision
		$bIsHtml = true;
		
		$sResult = '';
		$sTargetObjectClass = 'Attachment';
		$iTargetObjectKey = $this->Get('attachment_id');
		$sFilename = htmlentities($this->Get('filename'), ENT_QUOTES, 'UTF-8');
		$oTargetSearch = new DBObjectSearch($sTargetObjectClass);
		$oTargetSearch->AddCondition('id', $iTargetObjectKey, '=');

		$oMonoObjectSet = new DBObjectSet($oTargetSearch);
		if ($oMonoObjectSet->Count() > 0)
		{
			$oAttachment = $oMonoObjectSet->Fetch();
			$oDoc = $oAttachment->Get('contents');
			$sPreview = $oDoc->IsPreviewAvailable() ? 'data-preview="true"' : '';
			$sResult = Dict::Format('Attachments:History_File_Added', '<span class="attachment-history-added attachment"><a '.$sPreview.' target="_blank" href="'.$oDoc->GetDownloadURL($sTargetObjectClass, $iTargetObjectKey, 'contents').'">'.$sFilename.'</a></span>');
		}
		else
		{
			$sResult = Dict::Format('Attachments:History_File_Added', '<span class="attachment-history-deleted">'.$sFilename.'</span>');
		}
		return $sResult;
	}
}
	
class CMDBChangeOpAttachmentRemoved extends CMDBChangeOp
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "core/cmdb",
			"key_type" => "",
			"name_attcode" => "change",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_changeop_attachment_removed",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeString("filename", array("allowed_values"=>null, "sql"=>"filename", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('filename')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('filename')); // Attributes to be displayed for a list
	}

	/**
	 * Describe (as a text string) the modifications corresponding to this change
	 */
	public function GetDescription()
	{
		// Temporary, until we change the options of GetDescription() -needs a more global revision
		$bIsHtml = true;

		$sResult = Dict::Format('Attachments:History_File_Removed', '<span class="attachment-history-deleted">'.htmlentities($this->Get('filename'), ENT_QUOTES, 'UTF-8').'</span>');
		return $sResult;
	}
}


<?php
// Copyright (C) 2010 Combodo SARL
//
//   This program is free software; you can redistribute it and/or modify
//   it under the terms of the GNU General Public License as published by
//   the Free Software Foundation; version 3 of the License.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of the GNU General Public License
//   along with this program; if not, write to the Free Software
//   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

/**
 * Module attachments
 * 
 * A quick and easy way to upload and attach files to *any* (see Configuration below) object in the CMBD in one click
 *
 * Configuration: the list of classes for which the "Attachments" tab is visible is defined via the module's 'allowed_classes'
 * configuration parameter. By default the tab is active for all kind of Tickets.
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

class Attachment extends DBObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "addon,bizmodel",
			"key_type" => "autoincrement",
			"name_attcode" => array('item_class', 'temp_id'),
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "attachment",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeDateTime("expire", array("allowed_values"=>null, "sql"=>"expire", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("temp_id", array("allowed_values"=>null, "sql"=>"temp_id", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_AddAttribute(new AttributeString("item_class", array("allowed_values"=>null, "sql"=>"item_class", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("item_id", array("allowed_values"=>null, "sql"=>"item_id", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeInteger("item_org_id", array("allowed_values"=>null, "sql"=>"item_org_id", "default_value"=>0, "is_null_allowed"=>true, "depends_on"=>array())));

		MetaModel::Init_AddAttribute(new AttributeBlob("contents", array("depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('temp_id', 'item_class', 'item_id', 'item_org_id'));
		MetaModel::Init_SetZListItems('advanced_search', array('temp_id', 'item_class', 'item_id'));
		MetaModel::Init_SetZListItems('standard_search', array('temp_id', 'item_class', 'item_id'));
		MetaModel::Init_SetZListItems('list', array('temp_id', 'item_class', 'item_id'));
	}

	/**
	 * Maps the given context parameter name to the appropriate filter/search code for this class
	 * @param string $sContextParam Name of the context parameter, e.g. 'org_id'
	 * @return string Filter code, e.g. 'customer_id'
	 */
	public static function MapContextParam($sContextParam)
	{
		if ($sContextParam == 'org_id')
		{
			return 'item_org_id';
		}
		else
		{
			return null;
		}
	}

	/**
	 * Set/Update all of the '_item' fields
	 * @param object $oItem Container item
	 * @return void
	 */
	public function SetItem($oItem, $bUpdateOnChange = false)
	{
		$sClass = get_class($oItem);
		$iItemId = $oItem->GetKey();

 		$this->Set('item_class', $sClass);
 		$this->Set('item_id', $iItemId);

		$aCallSpec = array($sClass, 'MapContextParam');
		if (is_callable($aCallSpec))
		{
			$sAttCode = call_user_func($aCallSpec, 'org_id'); // Returns null when there is no mapping for this parameter					
			if (MetaModel::IsValidAttCode($sClass, $sAttCode))
			{
				$iOrgId = $oItem->Get($sAttCode);
				if ($iOrgId > 0)
				{
					if ($iOrgId != $this->Get('item_org_id'))
					{
						$this->Set('item_org_id', $iOrgId);
						if ($bUpdateOnChange)
						{
							$this->DBUpdate();
						}
					}
				}
			}
		}
	}

	/**
	 * Give a default value for item_org_id (if relevant...)
	 * @return void
	 */
	public function SetDefaultOrgId()
	{
		// First check that the organization CAN be fetched from the target class
		//
		$sClass = $this->Get('item_class');
		$aCallSpec = array($sClass, 'MapContextParam');
		if (is_callable($aCallSpec))
		{
			$sAttCode = call_user_func($aCallSpec, 'org_id'); // Returns null when there is no mapping for this parameter					
			if (MetaModel::IsValidAttCode($sClass, $sAttCode))
			{
				// Second: check that the organization CAN be fetched from the current user
				//
				if (MetaModel::IsValidClass('Person'))
				{
					$aCallSpec = array($sClass, 'MapContextParam');
					if (is_callable($aCallSpec))
					{
						$sAttCode = call_user_func($aCallSpec, 'org_id'); // Returns null when there is no mapping for this parameter					
						if (MetaModel::IsValidAttCode($sClass, $sAttCode))
						{
							// OK - try it
							//
							$oCurrentPerson = MetaModel::GetObject('Person', UserRights::GetContactId(), false);
							if ($oCurrentPerson)
							{
						 		$this->Set('item_org_id', $oCurrentPerson->Get($sAttCode));
						 	}
						}
					}
				}
			}
		}
	}

	// Todo - implement a cleanup function (see a way to do that generic !)
}

class AttachmentPlugIn implements iApplicationUIExtension, iApplicationObjectExtension
{
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
		if ($this->IsTargetObject($oObject))
		{
			$aAttachmentIds = utils::ReadParam('attachments', array());
			$aRemovedAttachmentIds = utils::ReadParam('removed_attachments', array());
			if ( (count($aAttachmentIds) > 0) || (count($aRemovedAttachmentIds) > 0) )
			{
				return true;
			}
		}
		return false;
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
EOF
		);
		$oPage->add('<fieldset>');
		$oPage->add('<legend>'.Dict::S('Attachments:FieldsetTitle').'</legend>');

		if ($bEditMode)
		{
			$sIsDeleteEnabled = $this->m_bDeleteEnabled ? 'true' : 'false';
			$iTransactionId = $oPage->GetTransactionId();
			$oPage->add_linked_script(utils::GetAbsoluteUrlAppRoot().'modules/itop-attachments/ajaxfileupload.js');
			$sClass = get_class($oObject);
			$sTempId = session_id().'_'.$iTransactionId;
			$sDeleteBtn = Dict::S('Attachments:DeleteBtn');
			$oPage->add_script(
<<<EOF
	function RemoveNewAttachment(att_id)
	{
		$('#attachment_'+att_id).attr('name', 'removed_attachments[]');
		$('#display_attachment_'+att_id).hide();
		return false; // Do not submit the form !
	}
	function ajaxFileUpload()
	{
		//starting setting some animation when the ajax starts and completes
		$("#loading").ajaxStart(function(){
			$(this).show();
		}).ajaxComplete(function(){
			$(this).hide();
		});
		
		/*
			prepareing ajax file upload
			url: the url of script file handling the uploaded files
                        fileElementId: the file type of input element id and it will be the index of  $_FILES Array()
			dataType: it support json, xml
			secureuri:use secure protocol
			success: call back function when the ajax complete
			error: callback function when the ajax failed
			
                */
		$.ajaxFileUpload
		(
			{
				url: GetAbsoluteUrlAppRoot()+'modules/itop-attachments/ajax.attachment.php?obj_class={$sClass}&temp_id={$sTempId}&operation=add', 
				secureuri:false,
				fileElementId:'file',
				dataType: 'json',
				success: function (data, status)
				{
					if(typeof(data.error) != 'undefined')
					{
						if(data.error != '')
						{
							alert(data.error);
						}
						else
						{
							var sDownloadLink = GetAbsoluteUrlAppRoot()+'pages/ajax.render.php/?operation=download_document&class=Attachment&id='+data.att_id+'&field=contents';
							$('#attachments').append('<div class="attachment" id="display_attachment_'+data.att_id+'"><a href="'+sDownloadLink+'"><img src="'+data.icon+'"><br/>'+data.msg+'<input id="attachment_'+data.att_id+'" type="hidden" name="attachments[]" value="'+data.att_id+'"/></a><br/><input type="button" class="btn_hidden" value="{$sDeleteBtn}" onClick="RemoveNewAttachment('+data.att_id+');"/></div>');
							if($sIsDeleteEnabled)
							{
								$('#display_attachment_'+data.att_id).hover( function() { $(this).children(':button').toggleClass('btn_hidden'); } );
							}
							//alert(data.msg);
						}
					}
				},
				error: function (data, status, e)
				{
					alert(e);
				}
			}
		)
		
		return false;

	}
EOF
);
			$oPage->add('<span id="attachments">');
			while ($oAttachment = $oSet->Fetch())
			{
				$iAttId = $oAttachment->GetKey();
				$oDoc = $oAttachment->Get('contents');
				$sFileName = $oDoc->GetFileName();
				$sIcon = utils::GetAbsoluteUrlAppRoot().AttachmentPlugIn::GetFileIcon($sFileName);
				$sDownloadLink = utils::GetAbsoluteUrlAppRoot().'pages/ajax.render.php/?operation=download_document&class=Attachment&id='.$iAttId.'&field=contents';
				$oPage->add('<div class="attachment" id="attachment_'.$iAttId.'"><a href="'.$sDownloadLink.'"><img src="'.$sIcon.'"><br/>'.$sFileName.'<input type="hidden" name="attachments[]" value="'.$iAttId.'"/></a><br/>&nbsp;<input id="btn_remove_'.$iAttId.'" type="button" class="btn_hidden" value="Delete" onClick="$(\'#attachment_'.$iAttId.'\').remove();"/>&nbsp;</div>');
			}
			$oPage->add('</span>');			
			$oPage->add('<div style="clear:both"></div>');			
			$sMaxUpload = $this->GetMaxUpload();
			$oPage->p(Dict::S('Attachments:AddAttachment').'<input type="file" name="file" id="file" onChange="ajaxFileUpload();"><span style="display:none;" id="loading">&nbsp;<img src="../images/indicator.gif"></span> '.$sMaxUpload);
			//$oPage->p('<input type="button" onClick="ajaxFileUpload();" value=" Upload !">');
			$oPage->p('<span style="display:none;" id="loading">Loading, please wait...</span>');
			$oPage->add('</fieldset>');
			if ($this->m_bDeleteEnabled)
			{
				$oPage->add_ready_script('$(".attachment").hover( function() {$(this).children(":button").toggleClass("btn_hidden"); } );');
			}
		}
		else
		{
			$oPage->add('<span id="attachments">');
			while ($oAttachment = $oSet->Fetch())
			{
				$iAttId = $oAttachment->GetKey();
				$oDoc = $oAttachment->Get('contents');
				$sFileName = $oDoc->GetFileName();
				$sIcon = utils::GetAbsoluteUrlAppRoot().AttachmentPlugIn::GetFileIcon($sFileName);
				$sDownloadLink = utils::GetAbsoluteUrlAppRoot().'pages/ajax.render.php/?operation=download_document&class=Attachment&id='.$iAttId.'&field=contents';
				$oPage->add('<div class="attachment" id="attachment_'.$iAttId.'"><a href="'.$sDownloadLink.'"><img src="'.$sIcon.'"><br/>'.$sFileName.'</a><input type="hidden" name="attachments[]" value="'.$iAttId.'"/><br/>&nbsp;&nbsp;</div>');
			}
		}
	}

	protected static function UpdateAttachments($oObject, $oChange = null)
	{
		$iTransactionId = utils::ReadParam('transaction_id', null);
		if (!is_null($iTransactionId))
		{
			$aActions = array();
			$aAttachmentIds = utils::ReadParam('attachments', array());

			// Get all current attachments
			$oSearch = DBObjectSearch::FromOQL("SELECT Attachment WHERE item_class = :class AND item_id = :item_id");
			$oSet = new DBObjectSet($oSearch, array(), array('class' => get_class($oObject), 'item_id' => $oObject->GetKey()));
			while ($oAttachment = $oSet->Fetch())
			{
				// Remove attachments that are no longer attached to the current object
				if (!in_array($oAttachment->GetKey(), $aAttachmentIds))
				{
					$oAttachment->DBDelete();
					$aActions[] = self::GetActionDescription($oAttachment, false /* false => deletion */);
				}
			}			

			// Attach new (temporary) attachements
			$sTempId = session_id().'_'.$iTransactionId;
			// The object is being created from a form, check if there are pending attachments
			// for this object, but deleting the "new" ones that were already removed from the form
			$aRemovedAttachmentIds = utils::ReadParam('removed_attachments', array());
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
						$aActions[] = self::GetActionDescription($oAttachment, true /* true => creation */);
					}
				}
			}
			if (count($aActions) > 0)
			{
				if ($oChange == null)
				{
					// Let's create a change if non is supplied
					$oChange = MetaModel::NewObject("CMDBChange");
					$oChange->Set("date", time());
					$sUserString = CMDBChange::GetCurrentUserName();
					$oChange->Set("userinfo", $sUserString);
					$iChangeId = $oChange->DBInsert();							
				}
				foreach($aActions as $sActionDescription)
				{
					self::RecordHistory($oChange, $oObject, $sActionDescription);
				}
			}
		}
	}
	
	/////////////////////////////////////////////////////////////////////////////////////////
	public static function GetFileIcon($sFileName)
	{
		$aPathParts = pathinfo($sFileName);
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
		
		return "modules/itop-attachments/icons/$sIcon";
	}
	
	/////////////////////////////////////////////////////////////////////////
	private static function RecordHistory(CMDBChange $oChange, $oTargetObject, $sDescription)
	{
		$oMyChangeOp = MetaModel::NewObject("CMDBChangeOpPlugin");
		$oMyChangeOp->Set("change", $oChange->GetKey());
		$oMyChangeOp->Set("objclass", get_class($oTargetObject));
		$oMyChangeOp->Set("objkey", $oTargetObject->GetKey());
		$oMyChangeOp->Set("description", $sDescription);
		$iId = $oMyChangeOp->DBInsertNoReload();
	}

	/////////////////////////////////////////////////////////////////////////
	private static function GetActionDescription($oAttachment, $bCreate = true)
	{
		$oBlob = $oAttachment->Get('contents');
		$sFileName = $oBlob->GetFileName();
		if ($bCreate)
		{
			$sDescription = Dict::Format('Attachments:History_File_Added', $sFileName);
		}
		else
		{
			$sDescription = Dict::Format('Attachments:History_File_Removed', $sFileName);
		}
		return $sDescription;
	}	
}

?>

<?php
/**
 * Copyright (C) 2013-2020 Combodo SARL
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

class AttachmentPlugIn implements iApplicationUIExtension, iApplicationObjectExtension
{
	const ENUM_GUI_ALL = 'all';
	const ENUM_GUI_BACKOFFICE = 'backoffice';
	const ENUM_GUI_PORTALS = 'portals';

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

	/**
	 * Returns the value of "upload_max_filesize" in bytes if upload allowed, false otherwise.
	 *
	 * @return number|boolean
	 * @since 2.6.1
	 *
	 */
	public static function GetMaxUploadSize()
	{
		$sMaxUpload = ini_get('upload_max_filesize');
		if (!$sMaxUpload)
		{
			$result = false;
		}
		else
		{
			$result = utils::ConvertToBytes($sMaxUpload);
		}

		return $result;
	}

	/**
	 * Returns the max. file upload size allowed as a dictionary entry
	 *
	 * @return string
	 */
	public static function GetMaxUpload()
	{
		$iMaxUpload = static::GetMaxUploadSize();
		if (!$iMaxUpload)
		{
			$sRet = Dict::S('Attachments:UploadNotAllowedOnThisSystem');
		}
		else
		{
			if ($iMaxUpload > 1024 * 1024 * 1024)
			{
				$sRet = Dict::Format('Attachment:Max_Go', sprintf('%0.2f', $iMaxUpload / (1024 * 1024 * 1024)));
			}
			else
			{
				if ($iMaxUpload > 1024 * 1024)
				{
					$sRet = Dict::Format('Attachment:Max_Mo', sprintf('%0.2f', $iMaxUpload / (1024 * 1024)));
				}
				else
				{
					$sRet = Dict::Format('Attachment:Max_Ko', sprintf('%0.2f', $iMaxUpload / (1024)));
				}
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
		while ($oAttachment = $oSet->Fetch())
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
		foreach ($aAllowedClasses as $sAllowedClass)
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

	/**
	 * @param \DBObject $oObject
	 * @param \WebPage $oPage
	 * @param bool $bEditMode
	 *
	 * @throws \CoreException
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \InvalidParameterException
	 */
	public function DisplayAttachments(DBObject $oObject, WebPage $oPage, $bEditMode = false)
	{
		// Exit here if the class is not allowed
		if (!$this->IsTargetObject($oObject))
		{
			return;
		}

		$sObjClass = get_class($oObject);
		$iObjKey = $oObject->GetKey();
		$sTransactionId = $oPage->GetTransactionId();
		if ($bEditMode && empty($sTransactionId))
		{
			throw new InvalidParameterException('Attachments renderer : invalid transaction id');
		}
		$oAttachmentsRenderer = AttachmentsRendererFactory::GetInstance($oPage, $sObjClass, $iObjKey, $sTransactionId);

		if ($this->GetAttachmentsPosition() === 'relations')
		{
			$iCount = $oAttachmentsRenderer->GetAttachmentsSet()->Count() + $oAttachmentsRenderer->GetTempAttachmentsSet()->Count();
			$sTitle = ($iCount > 0) ? Dict::Format('Attachments:TabTitle_Count', $iCount) : Dict::S('Attachments:EmptyTabTitle');
			$oPage->SetCurrentTab('Attachments:Tab', $sTitle);
		}

		$oPage->add('<fieldset>');
		$oPage->add('<legend>'.Dict::S('Attachments:FieldsetTitle').'</legend>');

		$oPage->add('<div id="AttachmentsContent">');
		$bIsReadOnlyState = self::IsReadonlyState($oObject, $oObject->GetState(), AttachmentPlugIn::ENUM_GUI_BACKOFFICE);
		if ($bEditMode && !$bIsReadOnlyState)
		{
			$oAttachmentsRenderer->RenderEditAttachmentsList();
		}
		else
		{
			$oAttachmentsRenderer->RenderViewAttachmentsList();
		}
		$oPage->add('</div>');

		$oPage->add('</fieldset>');
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
		$sTransactionId = utils::ReadParam('transaction_id', null, false, 'transaction_id');
		if (!is_null($sTransactionId))
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

			// Attach new (temporary) attachments
			$sTempId = utils::GetUploadTempId($sTransactionId);
			// The object is being created from a form, check if there are pending attachments
			// for this object, but deleting the "new" ones that were already removed from the form
			$sOQL = 'SELECT Attachment WHERE temp_id = :temp_id';
			$oSearch = DBObjectSearch::FromOQL($sOQL);
			foreach ($aAttachmentIds as $iAttachmentId)
			{
				$oSet = new DBObjectSet($oSearch, array(), array('temp_id' => $sTempId));
				while ($oAttachment = $oSet->Fetch())
				{
					if (in_array($oAttachment->GetKey(), $aRemovedAttachmentIds))
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
				foreach ($aActions as $oChangeOp)
				{
					self::RecordHistory($oChange, $oObject, $oChangeOp);
				}
				self::$m_bIsModified = true;
			}
		}
	}

	public static function CopyAttachments($oObject, $sTransactionId)
	{
		$oSearch = DBObjectSearch::FromOQL("SELECT Attachment WHERE item_class = :class AND item_id = :item_id");
		$oSet = new DBObjectSet($oSearch, array(), array('class' => get_class($oObject), 'item_id' => $oObject->GetKey()));
		// Attach new (temporary) attachments
		$sTempId = utils::GetUploadTempId($sTransactionId);
		while ($oAttachment = $oSet->Fetch())
		{
			$oTempAttachment = clone $oAttachment;
			$oTempAttachment->Set('item_id', null);
			$oTempAttachment->Set('temp_id', $sTempId);
			$oTempAttachment->DBInsert();
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
			switch ($aPathParts['extension'])
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
		$oMyChangeOp->DBInsertNoReload();
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

	/////////////////////////////////////////////////////////////////////////

	/**
	 * Returns if Attachments should be readonly for $oObject in the $sState state for the $sGUI GUI
	 *
	 * @param DBObject $oObject
	 * @param string $sState
	 * @param string $sGUI
	 *
	 * @return bool
	 * @throws \CoreException
	 */
	public static function IsReadonlyState(DBObject $oObject, $sState, $sGUI = self::ENUM_GUI_ALL)
	{
		$aParamDefaultValue = array(
			static::ENUM_GUI_ALL => array(
				'Ticket' => array('closed'),
			),
		);

		$bReadonly = false;
		$sClass = get_class($oObject);
		$aReadonlyStatus = MetaModel::GetModuleSetting('itop-attachments', 'readonly_states', $aParamDefaultValue);
		if (!empty($aReadonlyStatus))
		{
			// Merging GUIs entries
			$aEntries = array();
			// - All
			if (array_key_exists(static::ENUM_GUI_ALL, $aReadonlyStatus))
			{
				$aEntries = array_merge_recursive($aEntries, $aReadonlyStatus[static::ENUM_GUI_ALL]);
			}
			// - Backoffice & Portals
			foreach (array(static::ENUM_GUI_BACKOFFICE, static::ENUM_GUI_PORTALS) as $sEnumGUI)
			{
				if (in_array($sGUI, array(static::ENUM_GUI_ALL, $sEnumGUI)))
				{
					if (array_key_exists($sEnumGUI, $aReadonlyStatus))
					{
						$aEntries = array_merge_recursive($aEntries, $aReadonlyStatus[$sEnumGUI]);
					}
				}
			}

			$aParentClasses = array_reverse(MetaModel::EnumParentClasses($sClass, ENUM_PARENT_CLASSES_ALL));
			foreach ($aParentClasses as $sParentClass)
			{
				if (array_key_exists($sParentClass, $aEntries))
				{
					// If we found an ancestor of the object's class, we stop looking event if the current state is not specified
					if (in_array($oObject->GetState(), $aEntries[$sParentClass]))
					{
						$bReadonly = true;
					}
					break;
				}
			}
		}

		return $bReadonly;
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
		MetaModel::Init_AddAttribute(new AttributeExternalKey("attachment_id", array(
			"targetclass" => "Attachment",
			"allowed_values" => null,
			"sql" => "attachment_id",
			"is_null_allowed" => true,
			"on_target_delete" => DEL_SILENT,
			"depends_on" => array(),
		)));
		MetaModel::Init_AddAttribute(new AttributeString("filename", array(
			"allowed_values" => null,
			"sql" => "filename",
			"default_value" => "",
			"is_null_allowed" => false,
			"depends_on" => array(),
		)));

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
			$sResult = Dict::Format('Attachments:History_File_Added',
				'<span class="attachment-history-added attachment"><a '.$sPreview.' target="_blank" href="'.$oDoc->GetDownloadURL($sTargetObjectClass,
					$iTargetObjectKey, 'contents').'">'.$sFilename.'</a></span>');
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
		MetaModel::Init_AddAttribute(new AttributeString("filename", array(
			"allowed_values" => null,
			"sql" => "filename",
			"default_value" => "",
			"is_null_allowed" => false,
			"depends_on" => array(),
		)));

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
		$sResult = Dict::Format('Attachments:History_File_Removed',
			'<span class="attachment-history-deleted">'.htmlentities($this->Get('filename'), ENT_QUOTES, 'UTF-8').'</span>');

		return $sResult;
	}
}


class AttachmentsHelper
{
	/**
	 * @param string $sObjClass class name of the objects holding the attachments
	 * @param int $iObjKey key of the objects holding the attachments
	 *
	 * @return array containing attachment_id as key and date as value
	 */
	public static function GetAttachmentsDateAddedFromDb($sObjClass, $iObjKey)
	{
		$sQuery = "SELECT CMDBChangeOpAttachmentAdded WHERE objclass='$sObjClass' AND objkey=$iObjKey";
		try
		{
			$oSearch = DBObjectSearch::FromOQL($sQuery);
		}
		catch (OQLException $e)
		{
			return array();
		}
		$oSet = new DBObjectSet($oSearch);

		try
		{
			$aAttachmentDates = array();
			while ($oChangeOpAttAdded = $oSet->Fetch())
			{
				$iAttachmentId = $oChangeOpAttAdded->Get('attachment_id');
				$sAttachmentDate = $oChangeOpAttAdded->Get('date');
				$aAttachmentDates[$iAttachmentId] = $sAttachmentDate;
			}
		}
		catch (Exception $e)
		{
			return array();
		}

		return $aAttachmentDates;
	}
}

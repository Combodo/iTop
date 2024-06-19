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

define('INLINEIMAGE_DOWNLOAD_URL', 'pages/ajax.document.php?operation=download_inlineimage&id=');

/**
 * Persistent classes (internal): store images referenced inside HTML formatted text fields
 */
class InlineImage extends DBObject
{
	/** @var string attribute to be added to IMG tags to contain ID */
	const DOM_ATTR_ID = 'data-img-id';
	/** @var string attribute to be added to IMG tags to contain secret */
	const DOM_ATTR_SECRET = 'data-img-secret';

	/**
	 *
	 * @throws \CoreException
	 * @throws \Exception
	 */
	public static function Init()
	{
		$aParams = array
		(
			'category' => 'addon',
			'key_type' => 'autoincrement',
			'name_attcode' => array('item_class', 'temp_id'),
			'state_attcode' => '',
			'reconc_keys' => array(''),
			'db_table' => 'inline_image',
			'db_key_field' => 'id',
			'db_finalclass_field' => '',
			'indexes' => array(
				array('temp_id'),
				array('item_class', 'item_id'),
				array('item_org_id'),
			),
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeDateTime("expire", array("allowed_values"=>null, "sql"=>'expire', "default_value"=>'', "is_null_allowed"=>false, "depends_on"=>array(), "always_load_in_tables"=>false)));
		MetaModel::Init_AddAttribute(new AttributeString("temp_id", array("allowed_values"=>null, "sql"=>'temp_id', "default_value"=>'', "is_null_allowed"=>true, "depends_on"=>array(), "always_load_in_tables"=>false)));
		MetaModel::Init_AddAttribute(new AttributeString("item_class", array("allowed_values"=>null, "sql"=>'item_class', "default_value"=>'', "is_null_allowed"=>false, "depends_on"=>array(), "always_load_in_tables"=>false)));
		MetaModel::Init_AddAttribute(new AttributeObjectKey("item_id", array("class_attcode"=>'item_class', "allowed_values"=>null, "sql"=>'item_id', "is_null_allowed"=>true, "depends_on"=>array(), "always_load_in_tables"=>false)));
		MetaModel::Init_AddAttribute(new AttributeInteger("item_org_id", array("allowed_values"=>null, "sql"=>'item_org_id', "default_value"=>'0', "is_null_allowed"=>true, "depends_on"=>array(), "always_load_in_tables"=>false)));
		MetaModel::Init_AddAttribute(new AttributeBlob("contents", array("is_null_allowed"=>false, "depends_on"=>array(), "always_load_in_tables"=>false)));
		MetaModel::Init_AddAttribute(new AttributeString("secret", array("allowed_values"=>null, "sql" => "secret", "default_value"=>'', "is_null_allowed"=>false, "depends_on"=>array(), "always_load_in_tables"=>false)));


		MetaModel::Init_SetZListItems('details', array('temp_id', 'item_class', 'item_id', 'item_org_id'));
		MetaModel::Init_SetZListItems('standard_search', array('temp_id', 'item_class', 'item_id'));
		MetaModel::Init_SetZListItems('list', array('temp_id', 'item_class', 'item_id' ));
	}


	/**
	 * Maps the given context parameter name to the appropriate filter/search code for this class
	 *
	 * @param string $sContextParam Name of the context parameter, e.g. 'org_id'
	 * @return string|null Filter code, e.g. 'customer_id'
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
	 *
	 * @param DBObject $oItem Container item
	 * @param bool $bUpdateOnChange
	 *
	 * @return void
	 * @throws \ArchivedObjectException
	 * @throws \CoreCannotSaveObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 */
	public function SetItem(DBObject $oItem, $bUpdateOnChange = false)
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
	 *
	 * @return void
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \Exception
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

	/**
	 * When posting a form, finalize the creation of the inline images
	 * related to the specified object
	 *
	 * @param DBObject $oObject
	 *
	 * @return void
	 * @throws \CoreCannotSaveObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 * @throws \OQLException
	 */
	public static function FinalizeInlineImages(DBObject $oObject)
	{
		$iTransactionId = utils::ReadParam('transaction_id', null, false, 'transaction_id');
		if (!is_null($iTransactionId))
		{
			// Attach new (temporary) inline images
			
			$sTempId = utils::GetUploadTempId($iTransactionId);
			// The object is being created from a form, check if there are pending inline images for this object
			$sOQL = 'SELECT InlineImage WHERE temp_id = :temp_id';
			$oSearch = DBObjectSearch::FromOQL($sOQL);
			$oSet = new DBObjectSet($oSearch, array(), array('temp_id' => $sTempId));
			$aInlineImagesId = array();
			while ($oInlineImage = $oSet->Fetch()) {
				$aInlineImagesId[] = $oInlineImage->GetKey();
				$oInlineImage->SetItem($oObject);
				$oInlineImage->Set('temp_id', '');
				$oInlineImage->DBUpdate();
			}
			IssueLog::Trace('FinalizeInlineImages (see $aInlineImagesId for the id list)', LogChannels::INLINE_IMAGE, array(
				'$sObjectClass' => get_class($oObject),
				'$sTransactionId' => $iTransactionId,
				'$sTempId' => $sTempId,
				'$aInlineImagesId' => $aInlineImagesId,
				'$sUser' => UserRights::GetUser(),
				'HTTP_REFERER' => @$_SERVER['HTTP_REFERER'],
			));
		}
        else {
	        IssueLog::Trace('FinalizeInlineImages "error" $iTransactionId is null', LogChannels::INLINE_IMAGE, array(
		        '$sObjectClass' => get_class($oObject),
		        '$sTransactionId' => $iTransactionId,
		        '$sUser' => UserRights::GetUser(),
		        'HTTP_REFERER' => @$_SERVER['HTTP_REFERER'],
	        ));
        }
	}

	/**
	 * Cleanup the pending images if the form is not submitted
	 *
	 * @param string $sTempId
	 *
	 * @return bool True if cleaning was successful, false if anything aborted it
	 * @throws \ArchivedObjectException
	 * @throws \CoreCannotSaveObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \DeleteException
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 */
	public static function OnFormCancel($sTempId): bool
	{
		// Protection against unfortunate massive delete of inline images when a null temp ID is passed
		if (utils::IsNullOrEmptyString($sTempId)) {
			IssueLog::Trace('OnFormCancel "error" $sTempId is null or empty', LogChannels::INLINE_IMAGE, array(
				'$sTempId' => $sTempId,
				'$sUser' => UserRights::GetUser(),
				'HTTP_REFERER' => @$_SERVER['HTTP_REFERER'],
			));

			return false;
		}

		// Delete all "pending" InlineImages for this form
		$sOQL = 'SELECT InlineImage WHERE temp_id = :temp_id';
		$oSearch = DBObjectSearch::FromOQL($sOQL);
		$oSet = new DBObjectSet($oSearch, array(), array('temp_id' => $sTempId));
        $aInlineImagesId = array();
		while($oInlineImage = $oSet->Fetch())
		{
            $aInlineImagesId[] = $oInlineImage->GetKey();
			$oInlineImage->DBDelete();
		}
		IssueLog::Trace('OnFormCancel', LogChannels::INLINE_IMAGE, array(
			'$sTempId' => $sTempId,
			'$aInlineImagesId' => $aInlineImagesId,
			'$sUser' => UserRights::GetUser(),
			'HTTP_REFERER' => @$_SERVER['HTTP_REFERER'],
		));

		return true;
	}

	/**
	 * Parses the supplied HTML fragment to rebuild the attribute src="" for images
	 * that refer to an InlineImage (detected via the attribute data-img-id="") so that
	 * the URL is consistent with the current URL of the application.
	 *
	 * @param string $sHtml The HTML fragment to process
	 *
	 * @return string The modified HTML
	 * @throws \Exception
	 */
	public static function FixUrls($sHtml)
	{
		$aNeedles = array();
		$aReplacements = array();
		// Find img tags with an attribute data-img-id
		if (preg_match_all('/<img ([^>]*)'.self::DOM_ATTR_ID.'="([0-9]+)"([^>]*)>/i',
			$sHtml, $aMatches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE))
		{
			$sUrl = utils::GetAbsoluteUrlAppRoot().INLINEIMAGE_DOWNLOAD_URL;
			foreach($aMatches as $aImgInfo)
			{
				$sImgTag = $aImgInfo[0][0];
				$sSecret = '';
				if (preg_match('/data-img-secret="([0-9a-f]+)"/', $sImgTag, $aSecretMatches)) {
					$sSecret = '&s='.$aSecretMatches[1];
				}
				$sAttId = $aImgInfo[2][0];

				$sNewImgTag = preg_replace('/src="[^"]+"/', 'src="'.utils::EscapeHtml($sUrl.$sAttId.$sSecret).'"', $sImgTag); // preserve other attributes, must convert & to &amp; to be idempotent with CKEditor
				$aNeedles[] = $sImgTag;
				$aReplacements[] = $sNewImgTag;
			}
			$sHtml = str_replace($aNeedles, $aReplacements, $sHtml);
		}
		return $sHtml;
	}

	/**
	 * Add an extra attribute data-img-id for images which are based on an actual InlineImage
	 * so that we can later reconstruct the full "src" URL when needed
	 *
	 * @param \DOMElement $oElement
	 *
	 * @return void
	 * @throws \Exception
	 */
	public static function ProcessImageTag(DOMElement $oElement)
	{
		$sSrc = $oElement->getAttribute('src');
		$sDownloadUrl = str_replace(array('.', '?'), array('\.', '\?'), INLINEIMAGE_DOWNLOAD_URL); // Escape . and ?
		$sUrlPattern = '|'.$sDownloadUrl.'([0-9]+)&s=([0-9a-f]+)|';
		$bIsInlineImage = preg_match($sUrlPattern, $sSrc, $aMatches);
		if (!$bIsInlineImage)
		{
			return;
		}
		$iInlineImageId = $aMatches[1];
		$sInlineIMageSecret = $aMatches[2];

		$sAppRoot = utils::GetAbsoluteUrlAppRoot();
		$sAppRootPattern = '/^'.preg_quote($sAppRoot, '/').'/';
		$bIsSameItop = preg_match($sAppRootPattern, $sSrc);
		if (!$bIsSameItop)
		{
			// @see N°1921
			// image from another iTop should be treated as external images
			$oElement->removeAttribute(self::DOM_ATTR_ID);
			$oElement->removeAttribute(self::DOM_ATTR_SECRET);

			return;
		}

		$oElement->setAttribute(self::DOM_ATTR_ID, $iInlineImageId);
		$oElement->setAttribute(self::DOM_ATTR_SECRET, $sInlineIMageSecret);
	}

	/**
	 * Get the javascript fragment  - to be added to "on document ready" - to adjust (on the fly) the width on Inline Images
	 *
	 * @return string
	 */
	public static function FixImagesWidth()
	{
		$iMaxWidth = (int)MetaModel::GetConfig()->Get('inline_image_max_display_width', 0);
		$sJS = '';
		if ($iMaxWidth != 0)
		{
			$sJS =
<<<JS
CombodoInlineImage.SetMaxWidth('{$iMaxWidth}');
CombodoInlineImage.FixImagesWidth();
JS
			;
		}
		
		return $sJS;
	}
	
	/**
	 * Check if an the given mimeType is an image that can be processed by the system
	 *
	 * @param string $sMimeType
	 *
	 * @return boolean always false if php-gd not installed
	 *                 otherwise true if file is one of those type : image/gif, image/jpeg, image/png
	 * @uses php-gd extension
	 */
	public static function IsImage($sMimeType)
	{
		if (!function_exists('gd_info')) return false; // no image processing capability on this system
	
		$bRet = false;
		$aInfo = gd_info(); // What are the capabilities
		switch($sMimeType)
		{
			case 'image/gif':
				return $aInfo['GIF Read Support'];
				break;
					
			case 'image/jpeg':
				return $aInfo['JPEG Support'];
				break;
					
			case 'image/png':
				return $aInfo['PNG Support'];
				break;
	
		}
		return $bRet;
	}
	
	/**
	 * Resize an image so that it fits the maximum width/height defined in the config file
	 * @param ormDocument $oImage The original image stored as an array (content / mimetype / filename)
	 * @return ormDocument The resampled image (or the original one if it already fit)
	 */
	public static function ResizeImageToFit(ormDocument $oImage, &$aDimensions = null)
	{
		$img = false;
		switch($oImage->GetMimeType())
		{
			case 'image/gif':
			case 'image/jpeg':
			case 'image/png':
				$img = @imagecreatefromstring($oImage->GetData());
				break;
					
			default:
				// Unsupported image type, return the image as-is
				$aDimensions = null;
				return $oImage;
		}
		if ($img === false)
		{
			$aDimensions = null;
			return $oImage;
		}
		else
		{
			// Let's scale the image, preserving the transparency for GIFs and PNGs
			$iWidth = imagesx($img);
			$iHeight = imagesy($img);
			$aDimensions = array('width' => $iWidth, 'height' => $iHeight);
			$iMaxImageSize = (int)MetaModel::GetConfig()->Get('inline_image_max_storage_width', 0);
						
			if (($iMaxImageSize > 0) && ($iWidth <= $iMaxImageSize) && ($iHeight <= $iMaxImageSize))
			{
				// No need to resize
				return $oImage;
			}
				
			$fScale = min($iMaxImageSize / $iWidth, $iMaxImageSize / $iHeight);
	
			$iNewWidth = (int) ($iWidth * $fScale);
			$iNewHeight = (int) ($iHeight * $fScale);
			
			$aDimensions['width'] = $iNewWidth;
			$aDimensions['height'] = $iNewHeight;
				
			$new = imagecreatetruecolor($iNewWidth, $iNewHeight);
				
			// Preserve transparency
			if(($oImage->GetMimeType() == "image/gif") || ($oImage->GetMimeType() == "image/png"))
			{
				imagecolortransparent($new, imagecolorallocatealpha($new, 0, 0, 0, 127));
				imagealphablending($new, false);
				imagesavealpha($new, true);
			}
				
			imagecopyresampled($new, $img, 0, 0, 0, 0, $iNewWidth, $iNewHeight, $iWidth, $iHeight);
				
			ob_start();
			switch ($oImage->GetMimeType())
			{
				case 'image/gif':
					imagegif($new); // send image to output buffer
					break;
	
				case 'image/jpeg':
					imagejpeg($new, null, 80); // null = send image to output buffer, 80 = good quality
					break;
						
				case 'image/png':
					imagepng($new, null, 5); // null = send image to output buffer, 5 = medium compression
					break;
			}
			$oNewImage = new ormDocument(ob_get_contents(), $oImage->GetMimeType(), $oImage->GetFileName());
			@ob_end_clean();
				
			imagedestroy($img);
			imagedestroy($new);
	
			return $oNewImage;
		}
	
	}

	/**
	 * Get the (localized) textual representation of the max upload size
	 * @return string
	 */
	public static function GetMaxUpload()
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
	
	/**
	 * Get the fragment of javascript needed to complete the initialization of
	 * CKEditor when creating/modifying an object
	 *
	 * @param \DBObject $oObject The object being edited
	 * @param string $sTempId Generated through utils::GetUploadTempId($iTransactionId)
	 *
	 * @return string The JS fragment to insert in "on document ready"
	 * @throws \Exception
	 */
	public static function EnableCKEditorImageUpload(DBObject $oObject, $sTempId)
	{
		$sObjClass = get_class($oObject);
		$iObjKey = $oObject->GetKey();

		$sAbsoluteUrlAppRoot = utils::GetAbsoluteUrlAppRoot();
		$sToggleFullScreen = utils::EscapeHtml(Dict::S('UI:ToggleFullScreen'));
		return <<<JS
		$('.htmlEditor').each(function() {
			CombodoCKEditorHandler.EnableImageUpload('#' + $(this).attr('id'), '$sAbsoluteUrlAppRoot'+'pages/ajax.render.php?operation=cke_img_upload&temp_id=$sTempId&obj_class=$sObjClass&obj_key=$iObjKey');
		});
JS;

		return
			<<<JS
		// Hook the file upload of all CKEditor instances
		$('.htmlEditor').each(function() {
			var oEditor = $(this).ckeditorGet();
			oEditor.config.filebrowserBrowseUrl = '$sAbsoluteUrlAppRoot'+'pages/ajax.render.php?operation=cke_browse&temp_id=$sTempId&obj_class=$sObjClass&obj_key=$iObjKey';
			oEditor.on( 'fileUploadResponse', function( evt ) {
				var fileLoader = evt.data.fileLoader;
				var xhr = fileLoader.xhr;
				var data = evt.data;
				try {
			        var response = JSON.parse( xhr.responseText );
		
			        // Error message does not need to mean that upload finished unsuccessfully.
			        // It could mean that ex. file name was changes during upload due to naming collision.
			        if ( response.error && response.error.message ) {
			            data.message = response.error.message;
			        }
		
			        // But !uploaded means error.
			        if ( !response.uploaded ) {
			            evt.cancel();
			        } else {
			            data.fileName = response.fileName;
			           	data.url = response.url;
						
			            // Do not call the default listener.
			            evt.stop();
			        }
			    } catch ( err ) {
			        // Response parsing error.
			        data.message = fileLoader.lang.filetools.responseError;
			        window.console && window.console.log( xhr.responseText );
		
			        evt.cancel();
			    }
			} );
	
			oEditor.on( 'fileUploadRequest', function( evt ) {
				evt.data.fileLoader.uploadUrl += '?operation=cke_img_upload&temp_id=$sTempId&obj_class=$sObjClass';
			}, null, null, 4 ); // Listener with priority 4 will be executed before priority 5.
		
			oEditor.on( 'instanceReady', function() {
				if(!CKEDITOR.env.iOS && $('#'+oEditor.id+'_toolbox .ibo-vendors-ckeditor--toolbar-fullscreen-button').length == 0)
				{
					$('#'+oEditor.id+'_toolbox').append('<span class="ibo-vendors-ckeditor--toolbar-fullscreen-button editor-fullscreen-button" data-role="ibo-vendors-ckeditor--toolbar-fullscreen-button" title="$sToggleFullScreen">&nbsp;</span>');
					$('#'+oEditor.id+'_toolbox .ibo-vendors-ckeditor--toolbar-fullscreen-button').on('click', function() {
							oEditor.execCommand('maximize');
							if ($(this).closest('.cke_maximized').length != 0)
							{
								$('#'+oEditor.id+'_toolbar_collapser').trigger('click');
							}
					});
				}
				if (oEditor.widgets.registered.uploadimage)
				{
					oEditor.widgets.registered.uploadimage.onUploaded = function( upload ) {
					var oData = JSON.parse(upload.xhr.responseText);
				    	this.replaceWith( '<img src="' + upload.url + '" ' +
				    		'width="' + oData.width + '" ' +
							'height="' + oData.height + '">' );
				    }
				}
			});
		});
JS
		;
	}
	public static function EnableCKEditor5ImageUpload(DBObject $oObject, $sTempId){
		return <<<JS
		// Hook the file upload of all CKEditor instances
JS;

	}


	/**
	 * @inheritDoc
	 */
    protected function AfterInsert()
    {
	    IssueLog::Trace(__METHOD__, LogChannels::INLINE_IMAGE, array(
		    'id' => $this->GetKey(),
		    'expire' => $this->Get('expire'),
		    'temp_id' => $this->Get('temp_id'),
		    'item_class' => $this->Get('item_class'),
		    'item_id' => $this->Get('item_id'),
		    'item_org_id' => $this->Get('item_org_id'),
		    'secret' => $this->Get('secret'),
		    'user' => $sUser = UserRights::GetUser(),
		    'HTTP_REFERER' => @$_SERVER['HTTP_REFERER'],
		    'REQUEST_URI' => @$_SERVER['REQUEST_URI'],
        ));

	    parent::AfterInsert();
    }

	/**
	 * @inheritDoc
	 */
    protected function AfterUpdate()
    {
	    IssueLog::Trace(__METHOD__, LogChannels::INLINE_IMAGE, array(
		    'id' => $this->GetKey(),
		    'expire' => $this->Get('expire'),
		    'temp_id' => $this->Get('temp_id'),
		    'item_class' => $this->Get('item_class'),
		    'item_id' => $this->Get('item_id'),
		    'item_org_id' => $this->Get('item_org_id'),
		    'secret' => $this->Get('secret'),
		    'user' => $sUser = UserRights::GetUser(),
		    'HTTP_REFERER' => @$_SERVER['HTTP_REFERER'],
		    'REQUEST_URI' => @$_SERVER['REQUEST_URI'],
        ));

	    parent::AfterUpdate();
    }

	/**
	 * @inheritDoc
	 */
	protected function AfterDelete()
    {
	    IssueLog::Trace(__METHOD__, LogChannels::INLINE_IMAGE, array(
		    'id' => $this->GetKey(),
		    'expire' => $this->Get('expire'),
		    'temp_id' => $this->Get('temp_id'),
		    'item_class' => $this->Get('item_class'),
		    'item_id' => $this->Get('item_id'),
		    'item_org_id' => $this->Get('item_org_id'),
		    'secret' => $this->Get('secret'),
		    'user' => $sUser = UserRights::GetUser(),
		    'HTTP_REFERER' => @$_SERVER['HTTP_REFERER'],
		    'REQUEST_URI' => @$_SERVER['REQUEST_URI'],
        ));

	    parent::AfterDelete();
    }

}


/**
 * Garbage collector for cleaning "old" temporary InlineImages (and Attachments).
 */
class InlineImageGC implements iBackgroundProcess
{
	/**
	 * @inheritDoc
	 */
	public function GetPeriodicity()
    {
        return 1;
    }

	/**
	 * @inheritDoc
	 */
	public function Process($iTimeLimit)
	{
		$sDateLimit = date(AttributeDateTime::GetSQLFormat(), time()); // Every temporary InlineImage/Attachment expired will be deleted

		$aResults = array();
		$aClasses = array('InlineImage', 'Attachment');
		foreach($aClasses as $sClass)
		{
			$iProcessed = 0;
			if(class_exists($sClass))
			{
				$iProcessed = $this->DeleteExpiredDocuments($sClass, $iTimeLimit, $sDateLimit);
			}
			$aResults[] = "$iProcessed old temporary $sClass(s)";
		}

		return "Cleaned ".implode(' and ', $aResults).".";
	}

	/**
	 * Remove $sClass instance based on their `expire` field value.
	 * This `expire` field contains current time + draft_attachments_lifetime config parameter, it is initialized on object creation.
	 *
	 * @param string $sClass
	 * @param int $iTimeLimit
	 * @param string $sDateLimit
	 *
	 * @return int
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \DeleteException
	 * @throws \MySQLException
	 * @throws \OQLException
	 */
	protected function DeleteExpiredDocuments($sClass, $iTimeLimit, $sDateLimit)
	{
		$iProcessed = 0;
		$sOQL = "SELECT $sClass WHERE (item_id = 0) AND (expire < '$sDateLimit')";
		// Next one ?
		$oSet = new CMDBObjectSet(DBObjectSearch::FromOQL($sOQL), array('expire' => true) /* order by*/, array(), null,
			1 /* limit count */);
		$oSet->OptimizeColumnLoad(array());
		while ((time() < $iTimeLimit) && ($oResult = $oSet->Fetch()))
		{
			/** @var \ormDocument $oDocument */
			$oDocument = $oResult->Get('contents');
			IssueLog::Info($sClass.' GC: Removed temp. file '.$oDocument->GetFileName().' on "'.$oResult->Get('item_class').'" #'.$oResult->Get('item_id').' as it has expired.');
			$oResult->DBDelete();
			$iProcessed++;
		}

		return $iProcessed;
	}
}
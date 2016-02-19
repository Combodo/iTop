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


/**
 * Handles various ajax requests
 *
 * @copyright   Copyright (C) 2010-2016 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

require_once('../../approot.inc.php');
require_once(APPROOT.'/application/application.inc.php');
require_once(APPROOT.'/application/webpage.class.inc.php');
require_once(APPROOT.'/application/ajaxwebpage.class.inc.php');

try
{
	require_once(APPROOT.'/application/startup.inc.php');
//	require_once(APPROOT.'/application/user.preferences.class.inc.php');
	
	require_once(APPROOT.'/application/loginwebpage.class.inc.php');
	LoginWebPage::DoLoginEx(null /* any portal */, false);
	
	$oPage = new ajax_page("");
	$oPage->no_cache();
	
	$sOperation = utils::ReadParam('operation', '');

	switch($sOperation)
	{
	case 'add':
		$aResult = array(
			'error' => '',
			'att_id' => 0,
			'preview' => 'false',
			'msg' => ''
		);
		$sObjClass = stripslashes(utils::ReadParam('obj_class', '', false, 'class'));
		$sTempId = utils::ReadParam('temp_id', '');
		if (empty($sObjClass))
		{
			$aResult['error'] = "Missing argument 'obj_class'";
		}
		elseif (empty($sTempId))
		{
			$aResult['error'] = "Missing argument 'temp_id'";
		}
		else
		{
			try
			{
				$oDoc = utils::ReadPostedDocument('file');
				$oAttachment = MetaModel::NewObject('Attachment');
				$oAttachment->Set('expire', time() + 3600); // one hour...
				$oAttachment->Set('temp_id', $sTempId);
				$oAttachment->Set('item_class', $sObjClass);
				$oAttachment->SetDefaultOrgId();
				$oAttachment->Set('contents', $oDoc);
				$iAttId = $oAttachment->DBInsert();
				
				$aResult['msg'] = $oDoc->GetFileName();
				$aResult['icon'] = utils::GetAbsoluteUrlAppRoot().AttachmentPlugIn::GetFileIcon($oDoc->GetFileName());
				$aResult['att_id'] = $iAttId;
				$aResult['preview'] = $oDoc->IsPreviewAvailable() ? 'true' : 'false';
			}
			catch (FileUploadException $e)
			{
					$aResult['error'] = $e->GetMessage();
			}
		}
		$oPage->add(json_encode($aResult));
		break;
	
	case 'remove':
	$iAttachmentId = utils::ReadParam('att_id', '');
	$oSearch = DBObjectSearch::FromOQL("SELECT Attachment WHERE id = :id");
	$oSet = new DBObjectSet($oSearch, array(), array('id' => $iAttachmentId));
	while ($oAttachment = $oSet->Fetch())
	{
		$oAttachment->DBDelete();
	}
	break;


	case 'cke_img_upload':
	// Image uploaded via CKEditor
	$aResult = array(
	'uploaded' => 0,
	'fileName' => '',
	'url' => '',
	'icon' => '',
	'msg' => '',
	'att_id' => 0,
	'preview' => 'false',
	);

	$sObjClass = stripslashes(utils::ReadParam('obj_class', '', false, 'class'));
	$sTempId = utils::ReadParam('temp_id', '');
	if (empty($sObjClass))
	{
		$aResult['error'] = "Missing argument 'obj_class'";
	}
	elseif (empty($sTempId))
	{
		$aResult['error'] = "Missing argument 'temp_id'";
	}
	else
	{
		try
		{
			$oDoc = utils::ReadPostedDocument('upload');
			$oAttachment = MetaModel::NewObject('Attachment');
			$oAttachment->Set('expire', time() + 3600); // one hour...
			$oAttachment->Set('temp_id', $sTempId);
			$oAttachment->Set('item_class', $sObjClass);
			$oAttachment->SetDefaultOrgId();
			$oAttachment->Set('contents', $oDoc);
			$iAttId = $oAttachment->DBInsert();

			$aResult['uploaded'] = 1;
			$aResult['msg'] = $oDoc->GetFileName();
			$aResult['fileName'] = $oDoc->GetFileName();
			$aResult['url'] = utils::GetAbsoluteUrlAppRoot().ATTACHMENT_DOWNLOAD_URL.$iAttId;
			$aResult['icon'] = utils::GetAbsoluteUrlAppRoot().AttachmentPlugIn::GetFileIcon($oDoc->GetFileName());
			$aResult['att_id'] = $iAttId;
			$aResult['preview'] = $oDoc->IsPreviewAvailable() ? 'true' : 'false';
		}
		catch (FileUploadException $e)
		{
			$aResult['error'] = $e->GetMessage();
		}
	}
	$oPage->add(json_encode($aResult));
	break;
	
	case 'cke_browse':
	$oPage = new NiceWebPage('Browse for image...');
	$oPage->add_linked_stylesheet(utils::GetAbsoluteUrlModulesRoot().'itop-attachments/css/magnific-popup.css');
	$oPage->add_linked_script(utils::GetAbsoluteUrlModulesRoot().'itop-attachments/js/jquery.magnific-popup.min.js');
	$sImgUrl = utils::GetAbsoluteUrlAppRoot().ATTACHMENT_DOWNLOAD_URL;
	$oPage->add_script(
<<<EOF
        // Helper function to get parameters from the query string.
        function getUrlParam( paramName ) {
            var reParam = new RegExp( '(?:[\?&]|&)' + paramName + '=([^&]+)', 'i' );
            var match = window.location.search.match( reParam );

            return ( match && match.length > 1 ) ? match[1] : null;
        }
        // Simulate user action of selecting a file to be returned to CKEditor.
        function returnFileUrl(iAttId, sAltText) {

            var funcNum = getUrlParam( 'CKEditorFuncNum' );
            var fileUrl = '$sImgUrl'+iAttId;
            window.opener.CKEDITOR.tools.callFunction( funcNum, fileUrl, function() {
                // Get the reference to a dialog window.
                var dialog = this.getDialog();
                // Check if this is the Image Properties dialog window.
                if ( dialog.getName() == 'image' ) {
                    // Get the reference to a text field that stores the "alt" attribute.
                    var element = dialog.getContentElement( 'info', 'txtAlt' );
                    // Assign the new value.
                    if ( element )
                        element.setValue(sAltText);
                }
                // Return "false" to stop further execution. In such case CKEditor will ignore the second argument ("fileUrl")
                // and the "onSelect" function assigned to the button that called the file manager (if defined).
                // return false;
            } );
            window.close();
        }
EOF
	);
	$oPage->add_ready_script(
<<<EOF
$('.img-picker').magnificPopup({type: 'image', closeOnContentClick: true });
EOF
	);
	$sTempId = utils::ReadParam('temp_id');
	$sClass = utils::ReadParam('obj_class', '', false, 'class');
	$iObjectId = utils::ReadParam('obj_key', 0, false, 'integer');
	$sOQL = "SELECT Attachment WHERE ((temp_id = :temp_id) OR (item_class = :obj_class AND item_id = :obj_id))";
	$oSet = new DBObjectSet(DBObjectSearch::FromOQL($sOQL), array(), array('temp_id' => $sTempId, 'obj_class' => $sClass, 'obj_id' => $iObjectId));
	while($oAttachment = $oSet->Fetch())
	{
		$oDoc = $oAttachment->Get('contents');
		if ($oDoc->GetMainMimeType() == 'image')
		{
			$sDocName = addslashes(htmlentities($oDoc->GetFileName(), ENT_QUOTES, 'UTF-8'));
			$iAttId = $oAttachment->GetKey();
			$oPage->add("<div style=\"float:left;margin:1em;text-align:center;\"><img class=\"img-picker\" style=\"max-width:300px;cursor:zoom-in\" href=\"{$sImgUrl}{$iAttId}\" alt=\"$sDocName\" title=\"$sDocName\" src=\"{$sImgUrl}{$iAttId}\"><br/><button onclick=\"returnFileUrl($iAttId, '$sDocName')\">Insert</button></div>");
		}
	}
	break;
	
	default:
		$oPage->p("Missing argument 'operation'");
	}

	$oPage->output();
}
catch (Exception $e)
{
	// note: transform to cope with XSS attacks
	echo htmlentities($e->GetMessage(), ENT_QUOTES, 'utf-8');
	IssueLog::Error($e->getMessage());
}
?>

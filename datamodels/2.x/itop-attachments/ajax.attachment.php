<?php
// Copyright (C) 2010-2012 Combodo SARL
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
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
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
	LoginWebPage::DoLogin(false /* bMustBeAdmin */, true /* IsAllowedToPortalUsers */); // Check user rights and prompt if needed
	
	$oPage = new ajax_page("");
	$oPage->no_cache();
	
	$sOperation = utils::ReadParam('operation', '');

	switch($sOperation)
	{
	case 'add':
		$aResult = array(
			'error' => '',
			'att_id' => 0,
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

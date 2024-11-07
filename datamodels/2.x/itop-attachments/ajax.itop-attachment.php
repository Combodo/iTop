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

use Combodo\iTop\Application\WebPage\AjaxPage;
use Combodo\iTop\Application\WebPage\JsonPage;

require_once('../../approot.inc.php');
require_once(APPROOT.'/application/application.inc.php');

/**
 * @param \Combodo\iTop\Application\WebPage\AjaxPage $oPage
 * @param int $iTransactionId
 *
 * @throws \ArchivedObjectException
 * @throws \CoreException
 * @throws \OQLException
 */
function RenderAttachments(AjaxPage $oPage, $iTransactionId)
{
	$sClass = utils::ReadParam('objclass', '', false, 'class');
	$sId = utils::ReadParam('objkey', '');
	$oObject = MetaModel::GetObject($sClass, $sId, false);
	$bEditMode = utils::ReadParam('edit_mode', 0);
	$aAttachmentsDeleted = utils::ReadParam('attachments_deleted', array());

	$oPage->SetContentType('text/html');
	$oAttachmentsRenderer = AttachmentsRendererFactory::GetInstance($oPage, $sClass, $sId, $iTransactionId);

	$bIsReadOnlyState = (is_null($oObject))
		? false
		: AttachmentPlugIn::IsReadonlyState($oObject, $oObject->GetState(), AttachmentPlugIn::ENUM_GUI_BACKOFFICE);
	if ($bEditMode && !$bIsReadOnlyState)
	{
		$oAttachmentsRenderer->AddAttachmentsListContent(true, $aAttachmentsDeleted);
	}
	else
	{
		$oAttachmentsRenderer->RenderViewAttachmentsList();
	}
}

try
{
	require_once APPROOT.'/application/startup.inc.php';
	require_once APPROOT.'/application/loginwebpage.class.inc.php';
	LoginWebPage::DoLoginEx(null /* any portal */, false);

	$oPage = new AjaxPage("");

	$sOperation = utils::ReadParam('operation', '');

	switch ($sOperation)
	{
		case 'add':
			$oPage = new JsonPage();
			$oPage->SetOutputDataOnly(true);

			$aResult = array(
				'error' => '',
				'att_id' => 0,
				'preview' => 'false',
				'msg' => '',
			);
			$sClass = stripslashes(utils::ReadParam('obj_class', '', false, 'class'));
			$sTempId = utils::ReadParam('temp_id', '', false, 'transaction_id');
			if (empty($sClass))
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
					if ($oDoc->IsEmpty())
					{
						throw new FileUploadException(Dict::S('Attachments:Error:UploadedFileEmpty'));
					}
					/** @var Attachment $oAttachment */
					$oAttachment = MetaModel::NewObject('Attachment');
					$oAttachment->Set('expire', time() + MetaModel::GetConfig()->Get('draft_attachments_lifetime'));
					$oAttachment->Set('temp_id', $sTempId);
					$oAttachment->Set('item_class', $sClass);
					$oAttachment->SetDefaultOrgId();
					$oAttachment->Set('contents', $oDoc);
					$iAttId = $oAttachment->DBInsert();

					$aResult['msg'] = utils::EscapeHtml($oDoc->GetFileName());
					$aResult['icon'] = utils::GetAbsoluteUrlAppRoot().AttachmentPlugIn::GetFileIcon($oDoc->GetFileName());
					$aResult['att_id'] = $iAttId;
					$aResult['preview'] = $oDoc->IsPreviewAvailable() ? 'true' : 'false';
				}
				catch (FileUploadException $e)
				{
					$aResult['error'] = $e->GetMessage();
				}
			}
			$oPage->SetData($aResult);
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

		case 'refresh_attachments_render':
			$sTempId = utils::ReadParam('temp_id', '', false, 'transaction_id');
			RenderAttachments($oPage, $sTempId);
			break;

		default:
			$oPage->p("Missing argument 'operation'");
	}

	$oPage->output();
}
catch (Exception $e) {
	// note: transform to cope with XSS attacks
	echo utils::EscapeHtml($e->GetMessage());
	IssueLog::Error($e->getMessage());
}

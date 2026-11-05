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

use Combodo\iTop\Application\Helper\AjaxHelper;
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
	$sClass = utils::ReadPostedParam('objclass', '', 'class');
	$sId = utils::ReadPostedParam('objkey');
	$oObject = MetaModel::GetObject($sClass, $sId, false);
	$bEditMode = utils::ReadPostedParam('edit_mode', 0);
	$aAttachmentsDeleted = utils::ReadPostedParam('attachments_deleted', []);

	$oPage->SetContentType('text/html');
	$oAttachmentsRenderer = AttachmentsRendererFactory::GetInstance($oPage, $sClass, $sId, $iTransactionId);

	$bIsReadOnlyState = (is_null($oObject))
		? false
		: AttachmentPlugIn::IsReadonlyState($oObject, $oObject->GetState(), AttachmentPlugIn::ENUM_GUI_BACKOFFICE);
	if ($bEditMode && !$bIsReadOnlyState) {
		$oAttachmentsRenderer->AddAttachmentsListContent(true, $aAttachmentsDeleted);
	} else {
		$oAttachmentsRenderer->RenderViewAttachmentsList();
	}
}

try {
	require_once APPROOT.'/application/startup.inc.php';
	require_once APPROOT.'/application/loginwebpage.class.inc.php';
	LoginWebPage::DoLogin(); // No user portal should access this endpoint.

	// check if header contains X-Combodo-Ajax for POST request (CSRF protection for ajax calls)
	// check must be performed after DoLoginEx to be logged in and to be able to check the token (based on the transaction id)
	AjaxHelper::RejectUnauthorizedAjaxRequest();

	$oPage = new AjaxPage("");

	$sOperation = utils::ReadPostedParam('operation', '');

	switch ($sOperation) {
		case 'add':
			$oPage = new JsonPage();
			$oPage->SetOutputDataOnly(true);

			$aResult = [
				'error'      => '',
				'error_code' => 0,
				'att_id'     => 0,
				'preview'    => 'false',
				'msg'        => '',
			];
			$sClass = stripslashes(utils::ReadPostedParam('obj_class', '', 'class'));
			$sTempId = utils::ReadPostedParam('temp_id', '', 'transaction_id');
			if (empty($sClass)) {
				$aResult['error'] = "Missing argument 'obj_class'";
				$aResult['error_code'] = 1;
			} elseif (empty($sTempId)) {
				$aResult['error'] = "Missing argument 'temp_id'";
				$aResult['error_code'] = 2;
			} else {
				try {
					$oDoc = utils::ReadPostedDocument('file');
					if ($oDoc->IsEmpty()) {
						throw new FileUploadException(Dict::S('Attachments:Error:UploadedFileEmpty'));
					}
					if (!UserRights::IsActionAllowed($sClass, UR_ACTION_MODIFY)) {
						throw new SecurityException(Dict::S('UI:Error:ActionNotAllowed'));
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
				} catch (FileUploadException $e) {
					$aResult['error'] = $e->GetMessage();
					$aResult['error_code'] = 10;
				} catch (SecurityException $e) {
					$aResult['error'] = $e->GetMessage();
					$aResult['error_code'] = 20;
				}
			}
			$oPage->SetData($aResult);
			break;

		case 'refresh_attachments_render':
			$sTempId = utils::ReadPostedParam('temp_id');
			RenderAttachments($oPage, $sTempId);
			break;

		default:
			http_response_code(400);
			$oPage->p("Missing argument 'operation'");
	}

	$oPage->output();
} catch (Exception $e) {
	IssueLog::Error($e->getMessage());
	http_response_code(500);
	die(Dict::S('UI:PageTitle:FatalError'));
}

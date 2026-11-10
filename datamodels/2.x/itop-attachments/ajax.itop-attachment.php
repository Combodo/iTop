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
use Combodo\iTop\Application\Helper\RequestParamDTO;
use Combodo\iTop\Application\WebPage\AjaxPage;
use Combodo\iTop\Application\WebPage\JsonPage;
use Combodo\iTop\Attachments\Enum\AttachmentAjaxErrorEnum;

require_once('../../approot.inc.php');
require_once(APPROOT.'/application/application.inc.php');

/**
 * AddAttachment.
 *
 * @return void
 * @throws ArchivedObjectException
 * @throws CoreCannotSaveObjectException
 * @throws CoreException
 * @throws CoreUnexpectedValue
 * @throws CoreWarning
 * @throws MySQLException
 * @throws OQLException
 */
function AddAttachment(): void
{
	$oPage = new JsonPage();
	$oPage->SetOutputDataOnly(true);

	// reads request add parameters
	[$sClass, $sTempId] = AjaxHelper::HandleRequestPostedParam(
		RequestParamDTO::Create('obj_class', utils::ENUM_SANITIZATION_FILTER_CLASS, true),
		RequestParamDTO::Create('temp_id', utils::ENUM_SANITIZATION_FILTER_TRANSACTION_ID, true)
	);

	$aResult = [
		'error'   => '',
		'att_id'  => 0,
		'preview' => 'false',
		'msg'     => '',
	];

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
		$aResult['error_code'] = AttachmentAjaxErrorEnum::UPLOAD_FILE_EMPTY;
	} catch (SecurityException $e) {
		$aResult['error'] = $e->GetMessage();
		$aResult['error_code'] = AttachmentAjaxErrorEnum::ACTION_NOT_ALLOWED;
	}
	$oPage->SetData($aResult);
	$oPage->output();
}

/**
 * RenderAttachments.
 *
 * @throws ArchivedObjectException
 * @throws CoreException
 * @throws OQLException
 */
function RenderAttachments(): void
{
	$oPage = new AjaxPage("");

	// reads request render parameters
	[$sClass, $sKey, $sTempId, $bEditMode, $aAttachmentsDeleted] = AjaxHelper::HandleRequestPostedParam(
		RequestParamDTO::Create('objclass', utils::ENUM_SANITIZATION_FILTER_CLASS, bIsMandatory: true),
		RequestParamDTO::Create('objkey', bIsMandatory: true),
		RequestParamDTO::Create('temp_id', utils::ENUM_SANITIZATION_FILTER_TRANSACTION_ID, bIsMandatory: true),
		RequestParamDTO::Create('edit_mode', utils::ENUM_SANITIZATION_FILTER_INTEGER, oDefaultValue: 0),
		RequestParamDTO::Create('attachments_deleted', oDefaultValue: []),
	);

	$oObject = MetaModel::GetObject($sClass, $sKey, false);

	$oAttachmentsRenderer = AttachmentsRendererFactory::GetInstance($oPage, $sClass, $sKey, $sTempId);

	$bIsReadOnlyState = !(is_null($oObject)) && AttachmentPlugIn::IsReadonlyState($oObject, $oObject->GetState(), AttachmentPlugIn::ENUM_GUI_BACKOFFICE);
	if ($bEditMode && !$bIsReadOnlyState) {
		$oAttachmentsRenderer->AddAttachmentsListContent(true, $aAttachmentsDeleted);
	} else {
		$oAttachmentsRenderer->RenderViewAttachmentsList();
	}

	$oPage->SetContentType('text/html');
	$oPage->output();
}

try {
	require_once APPROOT.'/application/startup.inc.php';
	require_once APPROOT.'/application/loginwebpage.class.inc.php';

	// No user portal should access this endpoint.
	LoginWebPage::DoLogin();

	// check if header contains X-Combodo-Ajax for POST request (CSRF protection for ajax calls)
	// check must be performed after DoLoginEx to be logged in and to be able to check the token (based on the transaction id)
	AjaxHelper::RejectUnauthorizedAjaxRequest();

	// reads request operation
	[$sOperation] = AjaxHelper::HandleRequestPostedParam(
		RequestParamDTO::Create('operation', utils::ENUM_SANITIZATION_FILTER_OPERATION, true)
	);

	switch ($sOperation) {

		case 'add':
			AddAttachment();
			break;

		case 'refresh_attachments_render':
			RenderAttachments();
			break;
	}

} catch (Exception $e) {
	IssueLog::Error($e->getMessage());
	http_response_code(500);
	die(Dict::S('UI:PageTitle:FatalError'));
}

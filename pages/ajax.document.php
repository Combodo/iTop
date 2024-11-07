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

use Combodo\iTop\Application\WebPage\DownloadPage;

require_once('../approot.inc.php');
require_once(APPROOT.'application/utils.inc.php');


if (array_key_exists('HTTP_IF_MODIFIED_SINCE', $_SERVER) && (strlen($_SERVER['HTTP_IF_MODIFIED_SINCE']) > 0))
{
	// The content is garanteed to be unmodified since the URL includes a signature based on the contents of the document
	header('Last-Modified: Mon, 1 January 2018 00:00:00 GMT', true, 304); // Any date in the past
	exit;
}

try
{
	require_once(APPROOT.'/application/application.inc.php');
	require_once(APPROOT.'/application/startup.inc.php');

	require_once(APPROOT.'/application/loginwebpage.class.inc.php');
	IssueLog::Trace('----- Request: '.utils::GetRequestUri(), LogChannels::WEB_REQUEST);

	$oPage = new DownloadPage("");

	$operation = utils::ReadParam('operation', '');
	$sClass = utils::ReadParam('class', 'MissingAjaxParam', false, 'class');

	switch ($operation) {
		case 'download_document':
			LoginWebPage::DoLoginEx('backoffice', false);
			$id = utils::ReadParam('id', '');
			$sField = utils::ReadParam('field', '');
			if ($sClass == 'Attachment')
			{
				$iCacheSec = 31556926; // One year ahead: an attachment cannot change
			}
			else
			{
				$iCacheSec = (int)utils::ReadParam('cache', 0);
			}
			if (!empty($sClass) && ($sClass != 'InlineImage') && !empty($id) && !empty($sField))
			{
				ormDocument::DownloadDocument($oPage, $sClass, $id, $sField, 'attachment');
				if ($iCacheSec > 0)
				{
					$oPage->set_cache($iCacheSec);
					// X-Frame http header : set in page constructor, but we need to allow frame integration for this specific page
					// so we're resetting its value ! (see N°3416)
					$oPage->add_http_headers('');
				}
			}
			break;

		case 'download_inlineimage':
			// No login is required because the "secret" protects us
			// Benefit: the inline image can be inserted into any HTML (templating = $this->html(public_log)$)
			$id = utils::ReadParam('id', '');
			$sSecret = utils::ReadParam('s', '');
			$iCacheSec = 31556926; // One year ahead: an inline image cannot change
			if (!empty($id) && !empty($sSecret)) {
				ormDocument::DownloadDocument($oPage, 'InlineImage', $id, 'contents', 'inline', 'secret', $sSecret);
				$oPage->add_header("Cache-Control: no-transform,public,max-age=$iCacheSec,s-maxage=$iCacheSec");
				$oPage->add_header("Pragma: cache"); // Reset the value set .... where ?
				$oPage->add_header("Expires: "); // Reset the value set in ajax_page

				// X-Frame http header : set in page constructor, but we need to allow frame integration for this specific page
				// so we're resetting its value ! (see N°3416)
				$oPage->add_http_headers('');

				$oPage->add_header("Last-Modified: Wed, 15 Jun 2016 13:21:15 GMT"); // An arbitrary date in the past is ok
			}
			break;
			
		case 'dict':
			$sSignature = Utils::ReadParam('s', ''); // Sanitization prevents / and ..
			$oPage->SetContentType('text/javascript');
			$oPage->set_cache(86400); // Cache for 24 hours

			// X-Frame http header : set in page constructor, but we need to allow frame integration for this specific page
			// so we're resetting its value ! (see N°3416)
			$oPage->add_http_headers('');

			$oPage->add(file_get_contents(Utils::GetCachePath().$sSignature.'.js'));
			break;
			
		default:
		$oPage->p("Invalid query.");
	}

	$oPage->output();
}
catch (Exception $e)
{
	// note: transform to cope with XSS attacks
	echo utils::EscapeHtml($e->GetMessage());
	IssueLog::Error($e->getMessage()."\nDebug trace:\n".$e->getTraceAsString());
}


<?php
/**
 * Copyright (C) 2010-2018 Combodo SARL
 *
 * This file is part of iTop.
 *
 *  iTop is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with iTop. If not, see <http://www.gnu.org/licenses/>
 *
 */

use Combodo\iTop\Application\Search\AjaxSearchException;
use Combodo\iTop\Application\Search\CriterionParser;

require_once('../approot.inc.php');
require_once(APPROOT.'/application/application.inc.php');
require_once(APPROOT.'/application/webpage.class.inc.php');
require_once(APPROOT.'/application/ajaxwebpage.class.inc.php');
require_once(APPROOT.'/application/startup.inc.php');
require_once(APPROOT.'/application/user.preferences.class.inc.php');
require_once(APPROOT.'/application/loginwebpage.class.inc.php');
require_once(APPROOT.'/sources/application/search/ajaxsearchexception.class.inc.php');
require_once(APPROOT.'/sources/application/search/criterionparser.class.inc.php');

try
{
	if (LoginWebPage::EXIT_CODE_OK != LoginWebPage::DoLoginEx(null /* any portal */, false, LoginWebPage::EXIT_RETURN))
	{
		throw new SecurityException('You must be logged in');
	}

	$sParams = stripslashes(utils::ReadParam('params', '', false, 'raw_data'));
	if (!$sParams)
	{
		throw new AjaxSearchException("Invalid query (empty filter)", 400);
	}

	$oPage = new ajax_page("");
	$oPage->no_cache();
	$oPage->SetContentType('text/html');

	$aParams = json_decode($sParams, true);
	$sOQL = CriterionParser::Parse($aParams['base_oql'], $aParams['criterion']);
	$oFilter = DBSearch::FromOQL($sOQL);
	$oDisplayBlock = new DisplayBlock($oFilter, 'list', false);

	$aExtraParams['display_limit'] = true;
	$aExtraParams['truncated'] = true;
	$aExtraParams['currentId'] = uniqid('ajax-search-form');
	$oDisplayBlock->RenderContent($oPage, $aExtraParams);

	$oPage->output();

} catch (AjaxSearchException $e)
{
	http_response_code($e->getCode());
	// note: transform to cope with XSS attacks
	echo htmlentities($e->GetMessage(), ENT_QUOTES, 'utf-8');
	IssueLog::Error($e->getMessage()."\nDebug trace:\n".$e->getTraceAsString());
} catch (SecurityException $e)
{
	http_response_code(403);
	// note: transform to cope with XSS attacks
	echo htmlentities($e->GetMessage(), ENT_QUOTES, 'utf-8');
	IssueLog::Error($e->getMessage()."\nDebug trace:\n".$e->getTraceAsString());
} catch (Exception $e)
{
	http_response_code(500);
	// note: transform to cope with XSS attacks
	echo htmlentities($e->GetMessage(), ENT_QUOTES, 'utf-8');
	IssueLog::Error($e->getMessage()."\nDebug trace:\n".$e->getTraceAsString());
}
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
require_once(APPROOT.'/application/wizardhelper.class.inc.php');

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
	$oFilter = CriterionParser::Parse($aParams['base_oql'], $aParams['criterion']);
	$oDisplayBlock = new DisplayBlock($oFilter, 'list', false);

	$sListParams = stripslashes(utils::ReadParam('list_params', '{}', false, 'raw_data'));
	$aListParams = json_decode($sListParams, true);

	if (array_key_exists('currentId', $aListParams))
	{
		$aExtraParams['currentId'] = $aListParams['currentId'];
	}
	if (array_key_exists('selection_mode', $aListParams))
	{
		$aExtraParams['selection_mode'] = $aListParams['selection_mode'];
	}
	if (array_key_exists('selection_type', $aListParams))
	{
		$aExtraParams['selection_type'] = $aListParams['selection_type'];
		// In case of single selection, the root of the HTML identifiers used is suffixed with "_results" (at least in the external keys)
	}
	if (array_key_exists('json', $aListParams))
	{
		$aJson = $aListParams['json'];
		$sJson = json_encode($aJson);
		$oWizardHelper = WizardHelper::FromJSON($sJson);
		$oObj = $oWizardHelper->GetTargetObject();
		$aExtraParams['query_params'] = array('this' => $oObj);
	}
	if (array_key_exists('cssCount', $aListParams))
	{
		$aExtraParams['cssCount'] = $aListParams['cssCount'];
	}
	if (array_key_exists('table_inner_id', $aListParams))
	{
		$sListId = $aListParams['table_inner_id'];
	}

	$aExtraParams['display_limit'] = true;
	$aExtraParams['truncated'] = true;
	if (isset($sListId))
	{
		$oDisplayBlock->Display($oPage, $sListId, $aExtraParams);
	}
	else
	{
		$oDisplayBlock->RenderContent($oPage, $aExtraParams);
	}

	$oPage->output();

} catch (AjaxSearchException $e)
{
	http_response_code($e->getCode());
	// note: transform to cope with XSS attacks
	echo '<html><head></head><body><div>' . htmlentities($e->GetMessage(), ENT_QUOTES, 'utf-8') . '</div></body></html>';
	IssueLog::Error($e->getMessage()."\nDebug trace:\n".$e->getTraceAsString());
} catch (SecurityException $e)
{
	http_response_code(403);
	// note: transform to cope with XSS attacks
	echo '<html><head></head><body><div>' . htmlentities($e->GetMessage(), ENT_QUOTES, 'utf-8') . '</div></body></html>';
	IssueLog::Error($e->getMessage()."\nDebug trace:\n".$e->getTraceAsString());
} catch (Exception $e)
{
	http_response_code(500);
	// note: transform to cope with XSS attacks
	echo '<html><head></head><body><div>' . htmlentities($e->GetMessage(), ENT_QUOTES, 'utf-8') . '</div></body></html>';
	IssueLog::Error($e->getMessage()."\nDebug trace:\n".$e->getTraceAsString());
}
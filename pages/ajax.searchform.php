<?php
/**
 * Copyright (C) 2013-2019 Combodo SARL
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
	$oKPI = new ExecutionKPI();
	$oKPI->ComputeAndReport('Data model loaded');
	$oKPI = new ExecutionKPI();

	if (LoginWebPage::EXIT_CODE_OK != LoginWebPage::DoLoginEx(null /* any portal */, false, LoginWebPage::EXIT_RETURN))
	{
		throw new SecurityException('You must be logged in');
	}

	$sParams = utils::ReadParam('params', '', false, 'raw_data');
	if (!$sParams)
	{
		throw new AjaxSearchException("Invalid query (empty filter)", 400);
	}

	$oPage = new ajax_page("");
	$oPage->SetContentType('text/html');

	$sListParams = utils::ReadParam('list_params', '{}', false, 'raw_data');
	$aListParams = (array)json_decode($sListParams, true);

	$aParams = json_decode($sParams, true);
	if (array_key_exists('hidden_criteria', $aListParams))
	{
		$sHiddenCriteria = $aListParams['hidden_criteria'];
	}
	else
	{
		$sHiddenCriteria = '';
	}
	$oFilter = CriterionParser::Parse($aParams['base_oql'], $aParams['criterion'], $sHiddenCriteria);

	//IssueLog::Info('Search OQL: "'.$oFilter->ToOQL().'"');
	$oDisplayBlock = new DisplayBlock($oFilter, 'list_search', false);

	foreach($aListParams as $key => $value)
    {
	    $aExtraParams[$key] = $value;
    }

    if (array_key_exists('table_inner_id', $aListParams))
    {
        $sListId = $aListParams['table_inner_id'];
    }

	if (array_key_exists('json', $aListParams))
	{
		$aJson = $aListParams['json'];
		$sJson = json_encode($aJson);
		$oWizardHelper = WizardHelper::FromJSON($sJson);
		$oObj = $oWizardHelper->GetTargetObject();
		if (array_key_exists('query_params', $aExtraParams))
		{
			$aExtraParams['query_params']['this->object()'] = $oObj;
		}
		else
		{
			$aExtraParams['query_params'] = array('this->object()' => $oObj);
		}

//        // Current extkey value, so we can display event if it is not available anymore (eg. archived).
//        $iCurrentExtKeyId = (is_null($oObj)) ? 0 : $oObj->Get($this->sAttCode);
//        $aExtraParams['current_extkey_id'] = $iCurrentExtKeyId;

	}

	if (!isset($aExtraParams['update_history']))
	{
		$aExtraParams['update_history'] = true;
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


	if (isset($aListParams['debug']) || UserRights::IsAdministrator())
	{
		$oPage->StartCollapsibleSection(Dict::S('UI:RunQuery:MoreInfo'), false, 'SearchQuery');
		$oPage->p(Dict::S('UI:RunQuery:DevelopedQuery').htmlentities($oFilter->ToOQL(), ENT_QUOTES, 'UTF-8'));
		$oPage->EndCollapsibleSection();
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
} catch (MySQLException $e)
{
	http_response_code(500);
	// Sanytize error:
	$sMsg = $e->GetMessage();
	$sMsg = preg_replace("@^.* mysql_error = @", '', $sMsg);
	// note: transform to cope with XSS attacks
	echo '<html><head></head><body><div>'.htmlentities($sMsg, ENT_QUOTES, 'utf-8').'</div></body></html>';
	IssueLog::Error($e->getMessage()."\nDebug trace:\n".$e->getTraceAsString());
} catch (Exception $e)
{
	http_response_code(500);
	// note: transform to cope with XSS attacks
	echo '<html><head></head><body><div>' . htmlentities($e->GetMessage(), ENT_QUOTES, 'utf-8') . '</div></body></html>';
	IssueLog::Error($e->getMessage()."\nDebug trace:\n".$e->getTraceAsString());
}

ExecutionKPI::ReportStats();

<?php
/*
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

use Combodo\iTop\Application\Search\AjaxSearchException;
use Combodo\iTop\Application\Search\CriterionParser;
use Combodo\iTop\Application\UI\Base\Component\CollapsibleSection\CollapsibleSectionUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Html\Html;

require_once('../approot.inc.php');
require_once(APPROOT.'/application/application.inc.php');
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

	$oPage = new AjaxPage("");
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
	}

	if (!isset($aExtraParams['update_history']))
	{
		$aExtraParams['update_history'] = true;
	}

	$aExtraParams['display_limit'] = true;

	if (isset($sListId))
	{
		$oPage->AddUiBlock($oDisplayBlock->GetDisplay($oPage, $sListId, $aExtraParams));
	}
	else
	{
		$oDisplayBlock->RenderContent($oPage, $aExtraParams);
	}

	if (isset($aListParams['debug']) || UserRights::IsAdministrator())
	{
		$oCollapsible = CollapsibleSectionUIBlockFactory::MakeStandard(Dict::S('UI:RunQuery:MoreInfo'));
		$oPage->AddSubBlock($oCollapsible);

		$oHtml = new Html(Dict::S('UI:RunQuery:DevelopedQuery').htmlentities($oFilter->ToOQL(), ENT_QUOTES, 'UTF-8'));
		$oCollapsible->AddSubBlock($oHtml);

		/*$oPage->StartCollapsibleSection(Dict::S('UI:RunQuery:MoreInfo'), false, 'SearchQuery');
		$oPage->p(Dict::S('UI:RunQuery:DevelopedQuery').htmlentities($oFilter->ToOQL(), ENT_QUOTES, 'UTF-8'));
		$oPage->EndCollapsibleSection();*/
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

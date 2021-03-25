<?php
/**
 * Copyright (C) 2013-2021 Combodo SARL
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

use Combodo\iTop\Application\UI\Base\Component\Alert\AlertUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Button\ButtonUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\CollapsibleSection\CollapsibleSection;
use Combodo\iTop\Application\UI\Base\Component\FieldSet\FieldSet;
use Combodo\iTop\Application\UI\Base\Component\Form\Form;
use Combodo\iTop\Application\UI\Base\Component\Html\Html;
use Combodo\iTop\Application\UI\Base\Component\Html\HtmlFactory;
use Combodo\iTop\Application\UI\Base\Component\Input\InputUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Input\TextArea;
use Combodo\iTop\Application\UI\Base\Component\Panel\PanelUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Layout\UIContentBlockUIBlockFactory;

require_once('../approot.inc.php');
require_once(APPROOT.'/application/application.inc.php');
require_once(APPROOT.'/application/startup.inc.php');
require_once(APPROOT.'/application/loginwebpage.class.inc.php');

LoginWebPage::DoLogin(); // Check user rights and prompt if needed
ApplicationMenu::CheckMenuIdEnabled('RunQueriesMenu');

/**
 * @param WebPage $oP
 * @param string $sExpression
 */
function ShowExamples($oP, $sExpression)
{
	$bUsingExample = false;

	$aExamples = array(
		'Pedagogic examples' => array(
			"Web applications" => "SELECT WebApplication",
			"Person having an 'A' in their name" => "SELECT Person AS B WHERE B.name LIKE '%A%'",
			"Servers having a name like dbserver1.demo.com or dbserver023.foo.fr" => "SELECT Server WHERE name REGEXP '^dbserver[0-9]+\\\\..+\\\\.[a-z]{2,3}$'",
			"Changes planned on new year's day" => "SELECT Change AS ch WHERE ch.start_date >= '2009-12-31' AND ch.end_date <= '2010-01-01'",
			"IPs in a range" => "SELECT DatacenterDevice AS dev WHERE INET_ATON(dev.managementip) > INET_ATON('10.22.32.224') AND INET_ATON(dev.managementip) < INET_ATON('10.22.32.255')",
			"Persons below a given root organization" => "SELECT Person AS P JOIN Organization AS Node ON P.org_id = Node.id JOIN Organization AS Root ON Node.parent_id BELOW Root.id WHERE Root.id=1",
		),
		'Usefull examples' => array(
			"NW interfaces of equipment in production for customer 'Demo'" => "SELECT PhysicalInterface AS if JOIN DatacenterDevice AS dev ON if.connectableci_id = dev.id WHERE dev.status = 'production' AND dev.organization_name = 'Demo'",
			"My tickets" => "SELECT Ticket AS t WHERE t.agent_id = :current_contact_id",
			"People being owner of an active ticket" => "SELECT Person AS p JOIN UserRequest AS u ON u.agent_id = p.id WHERE u.status != 'closed'",
			"Contracts terminating in the next thirty days" => "SELECT Contract AS c WHERE c.end_date > NOW() AND c.end_date < DATE_ADD(NOW(), INTERVAL 30 DAY)",
			"Orphan tickets (opened one hour ago, still not assigned)" => "SELECT UserRequest AS u WHERE u.start_date < DATE_SUB(NOW(), INTERVAL 60 MINUTE) AND u.status = 'new'",
			"Long lasting incidents (duration > 8 hours)" => "SELECT UserRequest AS u WHERE u.close_date > DATE_ADD(u.start_date, INTERVAL 8 HOUR)",
		),
	);

	$aDisplayData = array();
	$oAppContext = new ApplicationContext();
	$sContext = $oAppContext->GetForForm();
	foreach($aExamples as $sTopic => $aQueries)
	{
		foreach($aQueries as $sDescription => $sOql)
		{
			$sHighlight = '';
			$sDisable = '';
			if ($sOql == $sExpression)
			{
				// this one is currently being tested, highlight it
				$sHighlight = "background-color:yellow;";
				$sDisable = 'disabled';
				// and remember we are testing a query of the list
				$bUsingExample = true;
			}
			//$aDisplayData[$sTopic][] = array(
			$aDisplayData[Dict::S('UI:RunQuery:QueryExamples')][] = array(
				'desc' => "<div style=\"$sHighlight\">".utils::EscapeHtml($sDescription)."</div>",
				'oql' => "<div style=\"$sHighlight\">".utils::EscapeHtml($sOql)."</div>",
				//TODO 3.0.0 : buttons are not styled properly yet...
				// This whole "query examples" may be migrated to TWIG using iTop Twig tags ?
				'go' => "<form method=\"get\"><input type=\"hidden\" name=\"expression\" value=\"$sOql\"><input type=\"submit\" value=\"".Dict::S('UI:Button:Test')."\" $sDisable>$sContext</form>\n",
			);
		}
	}
	$aDisplayConfig = array();
	$aDisplayConfig['desc'] = array(
		'label' => Dict::S('UI:RunQuery:HeaderPurpose'),
		'description' => Dict::S('UI:RunQuery:HeaderPurpose+'),
	);
	$aDisplayConfig['oql'] = array(
		'label' => Dict::S('UI:RunQuery:HeaderOQLExpression'),
		'description' => Dict::S('UI:RunQuery:HeaderOQLExpression+'),
	);
	$aDisplayConfig['go'] = array('label' => '', 'description' => '');

	foreach ($aDisplayData as $sTopic => $aQueriesDisplayData) {
		$bShowOpened = $bUsingExample;
		$oTopic = $oP->GetTableBlock($aDisplayConfig, $aQueriesDisplayData);
		$oTopicSection = new CollapsibleSection($sTopic, [$oTopic]);
		$oTopicSection->SetOpenedByDefault($bShowOpened);
		$oP->AddUiBlock($oTopicSection);
	}
}

$sOperation = utils::ReadParam('operation', 'menu');
$oAppContext = new ApplicationContext();

$oP = new iTopWebPage(Dict::S('UI:RunQuery:Title'));
$oP->SetBreadCrumbEntry('ui-tool-runquery', Dict::S('Menu:RunQueriesMenu'), Dict::S('Menu:RunQueriesMenu+'), '', 'fas fa-terminal', iTopWebPage::ENUM_BREADCRUMB_ENTRY_ICON_TYPE_CSS_CLASSES);

// Main program
$sExpression = utils::ReadParam('expression', '', false, 'raw_data');
$sEncoding = utils::ReadParam('encoding', 'oql');

ShowExamples($oP, $sExpression);

try
{
	if ($sEncoding == 'crypted') {
		// Translate $sExpression into a oql expression
		$sClearText = base64_decode($sExpression);
		echo "<strong>FYI: '$sClearText'</strong><br/>\n";
		$oFilter = DBObjectSearch::unserialize($sExpression);
		$sExpression = $oFilter->ToOQL();
	}

	$oFilter = null;
	$aArgs = array();
	$sSyntaxError = null;

	if (!empty($sExpression))
	{
		try
		{
			$oFilter = DBObjectSearch::FromOQL($sExpression);
		}
		catch(Exception $e)
		{
			if ($e instanceof OqlException)
			{
				$sSyntaxError = $e->getHtmlDesc();
			}
			else
			{
				$sSyntaxError = $e->getMessage();
			}
		}

		if ($oFilter)
		{
			$aArgs = array();
			foreach($oFilter->GetQueryParams() as $sParam => $foo)
			{
				$value = utils::ReadParam('arg_'.$sParam, null, true, 'raw_data');
				if (!is_null($value)) {
					$aArgs[$sParam] = $value;
				} else {
					$aArgs[$sParam] = '';
				}
			}
			$oFilter->SetInternalParams($aArgs);
			$aRealArgs = $aArgs;
		}
	}

	$oQueryForm = new Form();
	$oP->AddUiBlock($oQueryForm);

	$oHiddenParams = new Html($oAppContext->GetForForm());
	$oQueryForm->AddSubBlock($oHiddenParams);

	//--- Query textarea
	$oQueryTitle = new Html('<h2>'.Dict::S('UI:RunQuery:ExpressionToEvaluate').'</h2>');
	$oQueryForm->AddSubBlock($oQueryTitle);
	$oQueryTextArea = new TextArea('expression', utils::EscapeHtml($sExpression), 'expression', 120, 8);
	$oQueryTextArea->SetName('expression');
	$oQueryForm->AddSubBlock($oQueryTextArea);

	$oP->add_linked_script(utils::GetAbsoluteUrlAppRoot()."/js/jquery.hotkeys.js");
	$oP->add_ready_script(<<<EOF
$("#expression").select();
$("#expression").on("keydown", null, "ctrl+return", function() {
	$(this).closest("form").submit();
});
EOF
	);

	if (count($aArgs) > 0) {
		//--- Query arguments
		$oQueryArgsContainer = PanelUIBlockFactory::MakeForInformation('Query arguments')
			->SetCSSClasses(['wizContainer']);
		$oQueryForm->AddSubBlock($oQueryArgsContainer);
		foreach ($aArgs as $sParam => $sValue) {
			$oArgInput = InputUIBlockFactory::MakeForInputWithLabel(
				$sParam,
				'arg_'.$sParam,
				$sValue
			);
			$oQueryArgsContainer->AddSubBlock($oArgInput);
		}
	}

	$oQuerySubmit = ButtonUIBlockFactory::MakeForPrimaryAction(
		Dict::S('UI:Button:Evaluate'),
		null,
		null,
		true
	)->SetTooltip(Dict::S('UI:Button:Evaluate:Title'));
	$oQueryForm->AddSubBlock($oQuerySubmit);


	if ($oFilter) {
		//--- Query filter
		$oP->add("<h2>Query results</h2>\n");

		$oResultBlock = new DisplayBlock($oFilter, 'list', false);
		$oResultBlock->Display($oP, 'runquery');

		// Breadcrumb
		//$iCount = $oResultBlock->GetDisplayedCount();
		$sPageId = "ui-search-".$oFilter->GetClass();
		$sLabel = MetaModel::GetName($oFilter->GetClass());
		$aArgs = array();
		foreach (array_merge($_POST, $_GET) as $sKey => $value) {
			if (is_array($value)) {
				$aItems = array();
				foreach ($value as $sItemKey => $sItemValue) {
					$aArgs[] = $sKey.'['.$sItemKey.']='.urlencode($sItemValue);
				}
			} else {
				$aArgs[] = $sKey.'='.urlencode($value);
			}
		}
		$sUrl = utils::GetAbsoluteUrlAppRoot().'pages/run_query.php?'.implode('&', $aArgs);
		$oP->SetBreadCrumbEntry($sPageId, $sLabel, $oFilter->ToOQL(true), $sUrl, 'fas fa-terminal',
			iTopWebPage::ENUM_BREADCRUMB_ENTRY_ICON_TYPE_CSS_CLASSES);


		//--- More info
		$aMoreInfoBlocks = [];

		$oDevelopedQuerySet = new FieldSet(Dict::S('UI:RunQuery:DevelopedQuery'));
		$oDevelopedQuerySet->AddSubBlock(UIContentBlockUIBlockFactory::MakeForCode(utils::EscapeHtml($oFilter->ToOQL())));
		$aMoreInfoBlocks[] = $oDevelopedQuerySet;

		$oSerializedQuerySet = new FieldSet(Dict::S('UI:RunQuery:SerializedFilter'));
		$oSerializedQuerySet->AddSubBlock(UIContentBlockUIBlockFactory::MakeForCode(utils::EscapeHtml($oFilter->serialize())));
		$aMoreInfoBlocks[] = $oSerializedQuerySet;


		$aModifierProperties = MetaModel::MakeModifierProperties($oFilter);

		// Avoid adding all the fields for counts or "group by" requests
		$aCountAttToLoad = array();
		$sMainClass = null;
		foreach ($oFilter->GetSelectedClasses() as $sClassAlias => $sClass) {
			$aCountAttToLoad[$sClassAlias] = array();
			if (empty($sMainClass)) {
				$sMainClass = $sClass;
			}
		}

		$aOrderBy = MetaModel::GetOrderByDefault($sMainClass);

		if (($oFilter instanceof DBObjectSearch) && !MetaModel::GetConfig()->Get('use_legacy_dbsearch')) {
			// OQL Developed for Count
			$oSQLObjectQueryBuilder = new SQLObjectQueryBuilder($oFilter);
			$oBuild = new QueryBuilderContext($oFilter, $aModifierProperties, null, null, null, $aCountAttToLoad);
			$oCountDevelopedQuerySet = new FieldSet(Dict::S('UI:RunQuery:DevelopedOQLCount'));
			$oCountDevelopedQuerySet->AddSubBlock(UIContentBlockUIBlockFactory::MakeForCode($oSQLObjectQueryBuilder->DebugOQLClassTree($oBuild)));
			$aMoreInfoBlocks[] = $oCountDevelopedQuerySet;
		}

		// SQL Count
		$sSQL = $oFilter->MakeSelectQuery(array(), $aRealArgs, $aCountAttToLoad, null, 0, 0, true);
		$oCountResultQuerySet = new FieldSet(Dict::S('UI:RunQuery:ResultSQLCount'));
		$oCountResultQuerySet->AddSubBlock(UIContentBlockUIBlockFactory::MakeForCode($sSQL));
		$aMoreInfoBlocks[] = $oCountResultQuerySet;

		if (($oFilter instanceof DBObjectSearch) && !MetaModel::GetConfig()->Get('use_legacy_dbsearch')) {
			// OQL Developed
			$oSQLObjectQueryBuilder = new SQLObjectQueryBuilder($oFilter);
			$oBuild = new QueryBuilderContext($oFilter, $aModifierProperties);
			$oOqlDevelopedQuerySet = new FieldSet(Dict::S('UI:RunQuery:DevelopedOQL'));
			$oOqlDevelopedQuerySet->AddSubBlock(UIContentBlockUIBlockFactory::MakeForCode($oSQLObjectQueryBuilder->DebugOQLClassTree($oBuild)));
			$aMoreInfoBlocks[] = $oOqlDevelopedQuerySet;
		}

		// SQL
		$sSQL = $oFilter->MakeSelectQuery($aOrderBy, $aRealArgs, null, null, 0, 0, false);
		$oSqlQuerySet = new FieldSet(Dict::S('UI:RunQuery:ResultSQL'));
		$oSqlQuerySet->AddSubBlock(UIContentBlockUIBlockFactory::MakeForCode($sSQL));
		$aMoreInfoBlocks[] = $oSqlQuerySet;

		$oMoreInfoSection = new CollapsibleSection(Dict::S('UI:RunQuery:MoreInfo'), $aMoreInfoBlocks);
		$oMoreInfoSection->EnableSaveCollapsibleState('run_query__more-info');
		$oP->AddUiBlock($oMoreInfoSection);
	} elseif ($sSyntaxError) {
		$oSyntaxErrorPanel = PanelUIBlockFactory::MakeForFailure(Dict::S('UI:RunQuery:Error'));
		$oP->AddUiBlock($oSyntaxErrorPanel);

		if ($e instanceof OqlException) {
			$sWrongWord = $e->GetWrongWord();
			$aSuggestedWords = $e->GetSuggestions();
			if (is_array($aSuggestedWords) && count($aSuggestedWords) > 0) {
				$sSuggestedWord = OqlException::FindClosestString($sWrongWord, $aSuggestedWords);

				if (strlen($sSuggestedWord) > 0) {
					$sSyntaxErrorText = $e->GetIssue().'<br><em>'.$sWrongWord.'</em>';
					$sBefore = substr($sExpression, 0, $e->GetColumn());
					$sAfter = substr($sExpression, $e->GetColumn() + strlen($sWrongWord));
					$sFixedExpression = $sBefore.$sSuggestedWord.$sAfter;
					$sFixedExpressionHtml = $sBefore.'<span style="background-color:yellow">'.$sSuggestedWord.'</span>'.$sAfter;
					$sSyntaxErrorText .= "<p>Suggesting: $sFixedExpressionHtml</p>";
					$oSyntaxErrorPanel->AddSubBlock(new Html($sSyntaxErrorText));

					$sEscapedExpression = utils::EscapeHtml(addslashes($sFixedExpression));
					$oUseSuggestedQueryButton = ButtonUIBlockFactory::MakeForDestructiveAction('Use this query');
					$oUseSuggestedQueryButton->SetOnClickJsCode(<<<JS
let \$oQueryTextarea = $('textarea[name=expression]');
\$oQueryTextarea.val('$sEscapedExpression').focus();
\$oQueryTextarea.closest('form').submit();
JS
					);
					$oSyntaxErrorPanel->AddSubBlock($oUseSuggestedQueryButton);
				} else {
					$oSyntaxErrorPanel->AddSubBlock(HtmlFactory::MakeParagraph($e->getHtmlDesc()));
				}
			} else {
				$oSyntaxErrorPanel->AddSubBlock(HtmlFactory::MakeParagraph($e->getHtmlDesc()));
			}
		} else {
			$oSyntaxErrorPanel->AddSubBlock(HtmlFactory::MakeParagraph($e->getMessage()));
		}
	}
}
catch (Exception $e) {
	$oErrorAlert = AlertUIBlockFactory::MakeForFailure(
		Dict::Format('UI:RunQuery:Error', $e->getMessage()),
		'<pre>'.$e->getTraceAsString().'</pre>'
	);
	$oErrorAlert->SetOpenedByDefault(false);
	$oP->AddUiBlock($oErrorAlert);
}

$oP->output();


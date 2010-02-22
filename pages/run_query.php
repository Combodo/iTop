<?php
require_once('../application/application.inc.php');
require_once('../application/itopwebpage.class.inc.php');

require_once('../application/startup.inc.php');

require_once('../application/loginwebpage.class.inc.php');
LoginWebPage::DoLogin(); // Check user rights and prompt if needed


function ShowExamples($oP, $sExpression)
{
	$bUsingExample = false;

	$aExamples = array(
		'Pedagogic examples' => array(
			"Applications" => "SELECT bizApplication",
			"Person having an 'A' in their name" => "SELECT bizPerson AS B WHERE B.name LIKE '%A%'",
			"Changes planned on new year's day" => "SELECT bizChangeTicket AS ch WHERE ch.start_date >= '2009-12-31' AND ch.end_date <= '2010-01-01'",
			"IPs in a range" => "SELECT bizDevice AS dev WHERE INET_ATON(dev.mgmt_ip) > INET_ATON('10.22.32.33') AND INET_ATON(dev.mgmt_ip) < INET_ATON('10.22.32.40')"
		),
		'Usefull examples' => array(
			"NW interfaces of equipment in production for customer 'Demo'" => "SELECT bizInterface AS if JOIN bizDevice AS dev ON if.device_id = dev.id WHERE if.status = 'production' AND dev.status = 'production' AND dev.org_name = 'Demo' AND if.physical_type = 'ethernet'",
			"My tickets" => "SELECT bizIncidentTicket AS i WHERE i.agent_id = :current_contact_id",
			"People being owner of an active ticket" => "SELECT bizPerson AS p JOIN bizIncidentTicket AS i ON i.agent_id = p.id WHERE i.ticket_status != 'Closed'",
			"Contracts terminating in the next thirty days" => "SELECT bizContract AS c WHERE c.end_prod > NOW() AND c.end_prod < DATE_ADD(NOW(), INTERVAL 30 DAY)",
			"Orphan tickets (opened one hour ago, still not assigned)" => "SELECT bizIncidentTicket AS i WHERE i.start_date < DATE_SUB(NOW(), INTERVAL 60 MINUTE) AND i.ticket_status = 'New'",
			"Long lasting incidents (duration > 8 hours)" => "SELECT bizIncidentTicket AS i WHERE i.end_date > DATE_ADD(i.start_date, INTERVAL 8 HOUR)",
		),
	);

	$aDisplayData = array();
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
			$aDisplayData['Query examples'][] = array(
				'desc' => "<div style=\"$sHighlight\">".htmlentities($sDescription)."</div>",
				'oql' => "<div style=\"$sHighlight\">".htmlentities($sOql)."</div>",
				'go' => "<form method=\"get\"><input type=\"hidden\" name=\"expression\" value=\"$sOql\"><input type=\"submit\" value=\"Test!\" $sDisable></form>\n",
			);
		}
	}
	$aDisplayConfig = array();
	$aDisplayConfig['desc'] = array('label' => 'Target', 'description' => '');
	$aDisplayConfig['oql'] = array('label' => 'OQL Expression', 'description' => '');
	$aDisplayConfig['go'] = array('label' => '', 'description' => '');

	foreach ($aDisplayData as $sTopic => $aQueriesDisplayData)
	{
		$bShowOpened = $bUsingExample;
		$oP->StartCollapsibleSection($sTopic, $bShowOpened);
		$oP->table($aDisplayConfig, $aQueriesDisplayData);
		$oP->EndCollapsibleSection();
	}
}

$sOperation = utils::ReadParam('operation', 'menu');
$oContext = new UserContext();
$oAppContext = new ApplicationContext();
$iActiveNodeId = utils::ReadParam('menu', -1);
$currentOrganization = utils::ReadParam('org_id', '');

$oP = new iTopWebPage("iTop - Expression Evaluation", $currentOrganization);

// Main program
$sExpression = utils::ReadParam('expression', '');
$sEncoding = utils::ReadParam('encoding', 'oql');

ShowExamples($oP, $sExpression);

try
{
	if ($sEncoding == 'crypted')
	{
		// Translate $sExpression into a oql expression
		$sClearText = base64_decode($sExpression);
		echo "<strong>FYI: '$sClearText'</strong><br/>\n";
		$oFilter = DBObjectSearch::unserialize($sExpression);
		$sExpression = $oFilter->ToOQL();
		exit;
	}
	else
	{
		// leave $sExpression as is
	}

	$oP->add("<form method=\"get\">\n");
	$oP->add("Expression to evaluate:<br/>\n");
	$oP->add("<textarea cols=\"120\" rows=\"8\" name=\"expression\">$sExpression</textarea>\n");
	$oP->add("<input type=\"submit\" value=\" Evaluate \">\n");
	$oP->add("</form>\n");

	if (!empty($sExpression))
	{
		$oFilter = DBObjectSearch::FromOQL($sExpression);
		if ($oFilter)
		{
			$oP->add("<h3>Query results</h3>\n");
			
			$oResultBlock = new DisplayBlock($oFilter, 'list', false);
			$oResultBlock->Display($oP, 1);

			$oP->p('');
			$oP->StartCollapsibleSection('More info on the query', false);
			$oP->p('Query expression redevelopped: '.$oFilter->ToOQL());
			$oP->p('Serialized filter: '.$oFilter->serialize());
			$oP->EndCollapsibleSection();
		}
	}
}
catch(CoreException $e)
{
	$oP->p('<b>An error occured while running the query:</b>');
	$oP->p($e->getHtmlDesc());
}
catch(Exception $e)
{
	$oP->p('<b>An error occured while running the query:</b>');
	$oP->p($e->getMessage());
}

$oP->output();
?>

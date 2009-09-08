<?php
require_once('../application/application.inc.php');
require_once('../application/itopwebpage.class.inc.php');

require_once('../application/startup.inc.php');

require_once('../application/loginwebpage.class.inc.php');
login_web_page::DoLogin(); // Check user rights and prompt if needed

$sOperation = utils::ReadParam('operation', 'menu');
$oContext = new UserContext();
$oAppContext = new ApplicationContext();
$iActiveNodeId = utils::ReadParam('menu', -1);
$currentOrganization = utils::ReadParam('org_id', '');

$oP = new iTopWebPage("iTop - Expression Evaluation", $currentOrganization);

// Main program
$sExpression = utils::ReadParam('expression', '');
$sEncoding = utils::ReadParam('encoding', 'oql');

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

	$aExamples = array(
		"Applications" => "SELECT bizApplication",
		"Changes planned on new year's day" => "SELECT bizChangeTicket AS ch WHERE ch.start_date >= '2009-12-31' AND ch.end_date <= '2010-01-01'",
		"Person having an 'A' in their name" => "SELECT bizPerson AS B WHERE B.name LIKE '%A%'",
		"NW interfaces of equipment in production for customer 'Demo'" => "SELECT bizInterface AS if JOIN bizDevice AS dev ON if.device_id = dev.id WHERE if.status = 'production' AND dev.status = 'production' AND dev.org_name = 'Demo' AND if.physical_type = 'ethernet'"
	);

	$oP->add("<form method=\"get\">\n");
	$oP->add("Expression to evaluate:<br/>\n");
	$oP->add("<textarea cols=\"50\" rows=\"20\" name=\"expression\">$sExpression</textarea>\n");
	$oP->add("<input type=\"submit\" value=\" Evaluate \">\n");
	$oP->add("</form>\n");

	$oP->add("<h3>Examples</h3>\n");
	$aDisplayData = array();
	foreach($aExamples as $sDescription => $sOql)
	{
		$sHighlight = '';
		$sDisable = '';
		if ($sOql == $sExpression)
		{
			// this one is currently being tested, highlight it
			$sHighlight = "background-color:yellow;";
			$sDisable = 'disabled';
		}
		$aDisplayData[] = array(
			'desc' => "<div style=\"$sHighlight\">$sDescription</div>",
			'oql' => "<div style=\"$sHighlight\">$sOql</div>",
			'go' => "<form method=\"get\"><input type=\"hidden\" name=\"expression\" value=\"$sOql\"><input type=\"submit\" value=\"Test!\" $sDisable></form>\n",
		);
	}
	$aDisplayConfig = array();
	$aDisplayConfig['desc'] = array('label' => 'Target', 'description' => '');
	$aDisplayConfig['oql'] = array('label' => 'OQL Expression', 'description' => '');
	$aDisplayConfig['go'] = array('label' => '', 'description' => '');
	$oP->table($aDisplayConfig, $aDisplayData);
	
	if (!empty($sExpression))
	{
		$oFilter = DBObjectSearch::FromOQL($sExpression);
		if ($oFilter)
		{
			$oP->add("<h3>Query results</h3>\n");
			$oP->p('Query expression: '.$oFilter->ToOQL());
			$oP->p('Serialized filter: '.$oFilter->serialize());
			
			$oSet = new CMDBObjectSet($oFilter);
			$oP->p('The query returned '.$oSet->count().' results(s)');
			cmdbAbstractObject::DisplaySet($oP, $oSet);
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

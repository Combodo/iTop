<?php
require_once('../application/application.inc.php');
require_once('../application/webpage.class.inc.php');
require_once('../application/csvpage.class.inc.php');
require_once('../application/xmlpage.class.inc.php');

require_once('../application/startup.inc.php');

require_once('../application/loginwebpage.class.inc.php');
login_web_page::DoLogin(); // Check user rights and prompt if needed

$sOperation = utils::ReadParam('operation', 'menu');
$oContext = new UserContext();
$oAppContext = new ApplicationContext();
$iActiveNodeId = utils::ReadParam('menu', -1);
$currentOrganization = utils::ReadParam('org_id', '');

// Main program
$sExpression = utils::ReadParam('expression', '');
$sFormat = strtolower(utils::ReadParam('format', 'html'));
$oP = null;

if (!empty($sExpression))
{
	$oFilter = DBObjectSearch::FromSibusQL($sExpression);
	if ($oFilter)
	{
		$oSet = new CMDBObjectSet($oFilter);
		switch($sFormat)
		{
			case 'html':
			$oP = new web_page("iTop - Export");
			cmdbAbstractObject::DisplaySet($oP, $oSet);
			break;
			
			case 'csv':
			$oP = new CSVPage("iTop - Export");
			cmdbAbstractObject::DisplaySetAsCSV($oP, $oSet);
			break;
			
			case 'xml':
			$oP = new XMLPage("iTop - Export");
			cmdbAbstractObject::DisplaySetAsXML($oP, $oSet);
			break;
			
			default:
			$oP = new web_page("iTop - Export");
			$oP->add("Unsupported format '$sFormat'. Possible values are: html, csv or xml.");
		}
	}
}
if (!$oP)
{
	// Display a short message about how to use this page
	$oP = new web_page("iTop - Export");
	$oP->p("<strong>General purpose export page.</strong>");
	$oP->p("<strong>Parameters:</strong>");
	$oP->p("<ul><li>expression: a SibusQL expression</li>
			    <li>format: (optional, default is html) the desired output format. Can be one of 'html', 'csv' or 'xml'</li>
		    </ul>");
}

$oP->output();
?>

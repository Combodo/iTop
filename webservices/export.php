<?php
require_once('../application/application.inc.php');
require_once('../application/nicewebpage.class.inc.php');
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
	try
	{
		$oFilter = DBObjectSearch::FromOQL($sExpression);
		if ($oFilter)
		{
			$oSet = new CMDBObjectSet($oFilter);
			switch($sFormat)
			{
				case 'html':
				$oP = new nice_web_page("iTop - Export");
				// The HTML output is made for pages located in the /pages/ folder
				// since this page is in a different folder, let's adjust the HTML 'base' attribute
				// to make the relative hyperlinks in the page work
				$sServerName = $_SERVER['SERVER_NAME'];
				$sProtocol = isset($_SERVER['HTTPS']) ? 'https' : 'http';
				if ($sProtocol == 'http')
				{
					$sPort = ($_SERVER['SERVER_PORT'] == 80) ? '' : ':'.$_SERVER['SERVER_PORT'];
				}
				else
				{
					$sPort = ($_SERVER['SERVER_PORT'] == 443) ? '' : ':'.$_SERVER['SERVER_PORT'];
				}
				$sUrl = "$sProtocol://{$sServerName}{$sPort}/pages/";
				$oP->set_base($sUrl);
				cmdbAbstractObject::DisplaySet($oP, $oSet, array('menu' => false, 'display_limit' => false)); // no menu, no truncated list
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
	catch(Exception $e)
	{
		$oP = new web_page("iTop - Export");
		$oP->p("Error the query can not be executed.");		
		$oP->p($e->GetHtmlDesc());		
	}
}
if (!$oP)
{
	// Display a short message about how to use this page
	$oP = new web_page("iTop - Export");
	$oP->p("<strong>General purpose export page.</strong>");
	$oP->p("<strong>Parameters:</strong>");
	$oP->p("<ul><li>expression: an OQL expression (URL encoded if needed)</li>
			    <li>format: (optional, default is html) the desired output format. Can be one of 'html', 'csv' or 'xml'</li>
		    </ul>");
}

$oP->output();
?>

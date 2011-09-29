<?php
// Copyright (C) 2010 Combodo SARL
//
//   This program is free software; you can redistribute it and/or modify
//   it under the terms of the GNU General Public License as published by
//   the Free Software Foundation; version 3 of the License.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of the GNU General Public License
//   along with this program; if not, write to the Free Software
//   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

/**
 * Export data specified by an OQL
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

if (!defined('__DIR__')) define('__DIR__', dirname(__FILE__));
require_once(__DIR__.'/../approot.inc.php');
require_once(APPROOT.'/application/application.inc.php');
require_once(APPROOT.'/application/nicewebpage.class.inc.php');
require_once(APPROOT.'/application/csvpage.class.inc.php');
require_once(APPROOT.'/application/xmlpage.class.inc.php');

require_once(APPROOT.'/application/startup.inc.php');

try
{
	// Do this before loging, in order to allow setting user credentials from within the file
	utils::UseParamFile();
}
catch(Exception $e)
{
	echo "Error: ".$e->GetMessage()."<br/>\n";
	exit -2;
}

require_once(APPROOT.'/application/loginwebpage.class.inc.php');
LoginWebPage::DoLogin(); // Check user rights and prompt if needed

ApplicationContext::SetUrlMakerClass('iTopStandardURLMaker');

$sOperation = utils::ReadParam('operation', 'menu');
$oAppContext = new ApplicationContext();
$iActiveNodeId = utils::ReadParam('menu', -1);
$currentOrganization = utils::ReadParam('org_id', '');

// Main program
$sExpression = utils::ReadParam('expression', '', true /* Allow CLI */, 'raw_data');
$sFormat = strtolower(utils::ReadParam('format', 'html'));

$sFields = utils::ReadParam('fields', '', true, 'raw_data'); // CSV field list (allows to specify link set attributes, still not taken into account for XML export)
$aFields = explode(',', $sFields);
// Clean the list of columns (empty it if every string is empty)
foreach($aFields as $index => $sField)
{
	$aFields[$index] = trim($sField);
	if(strlen($aFields[$index]) == 0)
	{
		unset($aFields[$index]);
	}
}

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
				$oP = new NiceWebPage("iTop - Export");

				// Integration within MS-Excel web queries + HTTPS + IIS:
				// MS-IIS set these header values with no-cache... while Excel fails to do the job if using HTTPS
				// Then the fix is to force the reset of header values Pragma and Cache-control 
				header("Pragma:", true);
				header("Cache-control:", true);

				// The HTML output is made for pages located in the /pages/ folder
				// since this page is in a different folder, let's adjust the HTML 'base' attribute
				// to make the relative hyperlinks in the page work
				$sUrl = utils::GetAbsoluteUrlAppRoot();
				$oP->set_base($sUrl.'pages/');

				if(count($aFields) > 0)
				{
					$iSearch = array_search('id', $aFields);
					if ($iSearch !== false)
					{
						$bViewLink = true;
						unset($aFields[$iSearch]);
					}
					else
					{
						$bViewLink = false;
					}
					$sFields = implode(',', $aFields);
					$aExtraParams = array('menu' => false, 'display_limit' => false, 'zlist' => false, 'extra_fields' => $sFields, 'view_link' => $bViewLink);
				}
				else
				{
					$aExtraParams = array('menu' => false, 'display_limit' => false, 'zlist' => 'details');
				}

				$oResultBlock = new DisplayBlock($oFilter, 'list', false, $aExtraParams);
				$oResultBlock->Display($oP, 'expresult');
				break;
				
				case 'csv':
				$oP = new CSVPage("iTop - Export");
				$sFields = implode(',', $aFields);
				cmdbAbstractObject::DisplaySetAsCSV($oP, $oSet, array('fields' => $sFields));
				break;
				
				case 'xml':
				$oP = new XMLPage("iTop - Export", true /* passthrough */);
				cmdbAbstractObject::DisplaySetAsXML($oP, $oSet);
				break;
				
				default:
				$oP = new WebPage("iTop - Export");
				$oP->add("Unsupported format '$sFormat'. Possible values are: html, csv or xml.");
			}
		}
	}
	catch(Exception $e)
	{
		$oP = new WebPage("iTop - Export");
		$oP->p("Error the query can not be executed.");		
		$oP->p($e->GetHtmlDesc());		
	}
}
if (!$oP)
{
	// Display a short message about how to use this page
	$oP = new WebPage("iTop - Export");
	$oP->p("<strong>General purpose export page.</strong>");
	$oP->p("<strong>Parameters:</strong>");
	$oP->p("<ul><li>expression: an OQL expression (URL encoded if needed)</li>
			    <li>format: (optional, default is html) the desired output format. Can be one of 'html', 'csv' or 'xml'</li>
			    <li>fields: (optional, no effect on XML format) list of fields (attribute codes) separated by a coma</li>
		    </ul>");
}

$oP->output();
?>

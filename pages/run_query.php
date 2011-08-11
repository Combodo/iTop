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
 * Tools to design OQL queries and test them
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

require_once('../approot.inc.php');
require_once(APPROOT.'/application/application.inc.php');
require_once(APPROOT.'/application/itopwebpage.class.inc.php');

require_once(APPROOT.'/application/startup.inc.php');

require_once(APPROOT.'/application/loginwebpage.class.inc.php');
LoginWebPage::DoLogin(true); // Check user rights and prompt if needed (must be admin)

function ShowExamples($oP, $sExpression)
{
	$bUsingExample = false;

	$aExamples = array(
		'Pedagogic examples' => array(
			"Applications" => "SELECT Application",
			"Person having an 'A' in their name" => "SELECT Person AS B WHERE B.name LIKE '%A%'",
			"Servers having a name like dbserver1.demo.com or dbserver023.foo.fr" => "SELECT Server WHERE name REGEXP '^dbserver[0-9]+\\\\..+\\\\.[a-z]{2,3}$'",
			"Changes planned on new year's day" => "SELECT Change AS ch WHERE ch.start_date >= '2009-12-31' AND ch.end_date <= '2010-01-01'",
			"IPs in a range" => "SELECT InfrastructureCI AS dev WHERE INET_ATON(dev.management_ip) > INET_ATON('10.22.32.224') AND INET_ATON(dev.management_ip) < INET_ATON('10.22.32.255')",
			"Persons below a given root organization" => "SELECT Person AS P JOIN Organization AS Node ON P.org_id = Node.id JOIN Organization AS Root ON Node.parent_id BELOW Root.id WHERE Root.id=1",
		),
		'Usefull examples' => array(
			"NW interfaces of equipment in production for customer 'Demo'" => "SELECT NetworkInterface AS if JOIN InfrastructureCI AS dev ON if.device_id = dev.id WHERE if.status = 'production' AND dev.status = 'production' AND dev.owner_name = 'Demo' AND if.physical_type = 'ethernet'",
			"My tickets" => "SELECT Incident AS i WHERE i.agent_id = :current_contact_id",
			"People being owner of an active ticket" => "SELECT Person AS p JOIN Incident AS i ON i.agent_id = p.id WHERE i.status != 'Closed'",
			"Contracts terminating in the next thirty days" => "SELECT Contract AS c WHERE c.end_date > NOW() AND c.end_date < DATE_ADD(NOW(), INTERVAL 30 DAY)",
			"Orphan tickets (opened one hour ago, still not assigned)" => "SELECT Incident AS i WHERE i.start_date < DATE_SUB(NOW(), INTERVAL 60 MINUTE) AND i.status = 'New'",
			"Long lasting incidents (duration > 8 hours)" => "SELECT Incident AS i WHERE i.close_date > DATE_ADD(i.start_date, INTERVAL 8 HOUR)",
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
				'desc' => "<div style=\"$sHighlight\">".htmlentities($sDescription, ENT_QUOTES, 'UTF-8')."</div>",
				'oql' => "<div style=\"$sHighlight\">".htmlentities($sOql, ENT_QUOTES, 'UTF-8')."</div>",
				'go' => "<form method=\"get\"><input type=\"hidden\" name=\"expression\" value=\"$sOql\"><input type=\"submit\" value=\"".Dict::S('UI:Button:Test')."\" $sDisable>$sContext</form>\n",
			);
		}
	}
	$aDisplayConfig = array();
	$aDisplayConfig['desc'] = array('label' => Dict::S('UI:RunQuery:HeaderPurpose'), 'description' => Dict::S('UI:RunQuery:HeaderPurpose+'));
	$aDisplayConfig['oql'] = array('label' => Dict::S('UI:RunQuery:HeaderOQLExpression'), 'description' => Dict::S('UI:RunQuery:HeaderOQLExpression+'));
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
$oAppContext = new ApplicationContext();

$oP = new iTopWebPage(Dict::S('UI:RunQuery:Title'));

// Main program
$sExpression = utils::ReadParam('expression', '', false, 'raw_data');
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
	}
	else
	{
		// leave $sExpression as is
	}

	$oP->add("<form method=\"get\">\n");
	$oP->add(Dict::S('UI:RunQuery:ExpressionToEvaluate')."<br/>\n");
	$oP->add("<textarea cols=\"120\" rows=\"8\" name=\"expression\">$sExpression</textarea>\n");
	$oP->add("<input type=\"submit\" value=\"".Dict::S('UI:Button:Evaluate')."\">\n");
	$oP->add($oAppContext->GetForForm());
	$oP->add("</form>\n");

	if (!empty($sExpression))
	{
		$oFilter = DBObjectSearch::FromOQL($sExpression);
		if ($oFilter)
		{
			$oP->add("<h3>Query results</h3>\n");
			
			$oResultBlock = new DisplayBlock($oFilter, 'list', false);
			$oResultBlock->Display($oP, 'runquery');

			$oP->p('');
			$oP->StartCollapsibleSection(Dict::S('UI:RunQuery:MoreInfo'), false);
			$oP->p(Dict::S('UI:RunQuery:DevelopedQuery').$oFilter->ToOQL());
			$oP->p(Dict::S('UI:RunQuery:SerializedFilter').$oFilter->serialize());
			$oP->EndCollapsibleSection();
		}
	}
}
catch(CoreException $e)
{
	$oP->p('<b>'.Dict::Format('UI:RunQuery:Error', $e->getHtmlDesc()).'</b>');
}
catch(Exception $e)
{
	$oP->p('<b>'.Dict::Format('UI:RunQuery:Error', $e->getMessage()).'</b>');
}

$oP->output();
?>

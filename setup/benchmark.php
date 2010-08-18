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
 * Page designed to help in benchmarkink the scalability of itop
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

require_once('../application/application.inc.php');
require_once('../application/itopwebpage.class.inc.php');
require_once('../application/wizardhelper.class.inc.php');
require_once('../application/startup.inc.php');
require_once('../application/loginwebpage.class.inc.php');
require_once('../application/utils.inc.php');
require_once('./setuppage.class.inc.php');

class BenchmarkDataCreation
{
	var $m_aRequested;
	var $m_aPlanned;
	var $m_aCreated = array();

	var $m_aStatsByClass = array();

	public function __construct($iPlannedCIs, $iPlannedContacts, $iPlannedContracts)
	{
		$this->m_aRequested = array(
			'CIs' => $iPlannedCIs,
			'Contacts' => $iPlannedContacts,
			'Contracts' => $iPlannedContracts,
		);

		$this->m_aPlanned = array(
			'Network devices' => ceil($iPlannedCIs / 2),
			'Servers' => ceil($iPlannedCIs / 2),
			'Interfaces' => 10 * $iPlannedCIs,
			'Contacts' => $iPlannedContacts,
			'Contracts' => $iPlannedContracts,
			'Incidents' => 2 * 12 * $iPlannedCIs,
			'ServiceCalls' => 1 * 12 * $iPlannedContacts,
			'Changes' => 1 * 12 * $iPlannedCIs,
			'Documents' => 12 * $iPlannedContracts + $iPlannedCIs,
		);
	}

	public function GetPlans()
	{
		return $this->m_aPlanned;
	}

	public function GetRequestInfo()
	{
		return $this->m_aRequested;
	}

	protected function CreateObject($sClass, $aData, $oChange)
	{
		$mu_t1 = MyHelpers::getmicrotime();

		$oMyObject = MetaModel::NewObject($sClass);
		foreach($aData as $sProp => $value)
		{
			$oMyObject->Set($sProp, $value);
		}

		$iId = $oMyObject->DBInsertTrackedNoReload($oChange);

		$this->m_aCreated[$sClass][] = $iId;

		$mu_t2 = MyHelpers::getmicrotime();
		$this->m_aStatsByClass[$sClass][] = $mu_t2 - $mu_t1;
		
		return $iId;
	}

	protected function RandomId($sClass)
	{
		return $this->m_aCreated[$sClass][array_rand($this->m_aCreated[$sClass])];
	}

	static protected function FindId($sClass)
	{
		$oSet = new DBObjectSet(new DBObjectSearch($sClass));
		if ($oSet->Count() < 1)
		{
			return null;
		}

		$oObj = $oSet->Fetch();
		return $oObj->GetKey();
	}

	static protected function FindIdFromOQL($sOQL)
	{
		$oSet = new DBObjectSet(DBObjectSearch::FromOQL($sOQL));
		if ($oSet->Count() < 1)
		{
			return null;
		}

		$oObj = $oSet->Fetch();
		return $oObj->GetKey();
	}

	protected function MakeFeedback($oP, $sClass)
	{
		$iSample = reset($this->m_aCreated[$sClass]);
		$sSample = "<a href=\"../pages/UI.php?operation=details&class=$sClass&id=$iSample\">sample</a>";

		$iDuration = number_format(array_sum($this->m_aStatsByClass[$sClass]), 3);
		$fDurationMin = number_format(min($this->m_aStatsByClass[$sClass]), 3);
		$fDurationMax = number_format(max($this->m_aStatsByClass[$sClass]), 3);
		$fDurationAverage = number_format(array_sum($this->m_aStatsByClass[$sClass]) / count($this->m_aStatsByClass[$sClass]), 3);

		$oP->add("<ul>");
		$oP->add("<li>");
		$oP->add("$sClass: ".count($this->m_aCreated[$sClass])." - $sSample<br/>");
		$oP->add("Duration: $fDurationMin =&gt; $fDurationMax; Avg:$fDurationAverage; Total: $iDuration");
		$oP->add("</li>");
		$oP->add("</ul>");
	}

	public function GoProjections(WebPage $oP, $oChange)
	{
		// User login
		//
		$aData = array(
			'contactid' => self::FindId('Person'),
			'login' => 'foo',
			'password' => 'foo',
			'language' => 'EN US',
		);
		$iLogin = $this->CreateObject('UserLocal', $aData, $oChange);

		// Assign profiles to the new login
		//
		$aData = array(
			'userid' => $iLogin,
			'profileid' => self::FindIdFromOQL("SELECT URP_Profiles WHERE name LIKE 'Configuration Manager'"),
			'reason' => '',
		);
		$iFoo = $this->CreateObject('URP_UserProfile', $aData, $oChange);

		// Dimension
		//
		$aData = array(
			'name' => 'location',
			'description' => '',
			'type' => 'Location',
		);
		$iDimLocation = $this->CreateObject('URP_Dimensions', $aData, $oChange);

		// Project classes
		//
		$aMyClassesToProject = array('NetworkDevice', 'Server');
		foreach($aMyClassesToProject as $sClass)
		{
			$aData = array(
				'dimensionid' => $iDimLocation,
				'class' => $sClass,
				'value' => '<this>',
				'attribute' => 'location_name',
			);
			$iFoo = $this->CreateObject('URP_ClassProjection', $aData, $oChange);
		}

		// Project profiles
		//
		$aProfilesToProject = array(1, 2, 3, 4, 5, 6, 7, 8, 9);
		foreach($aProfilesToProject as $iProfileId)
		{
			$aData = array(
				'dimensionid' => $iDimLocation,
				'profileid' => $iProfileId,
				'value' => 'Grenoble',
				'attribute' => '',
			);
			$iFoo = $this->CreateObject('URP_ProfileProjection', $aData, $oChange);
		}

		$oP->p('Created projections (Cf. login "foo", pwd "foo")');
	}

	public function GoVolume(WebPage $oP, $oChange)
	{
		/////////////////////////
		//
		// Organizations
		//
		$aData = array(
			'name' => 'Benchmark',
		);
		$iOrg = $this->CreateObject('Organization', $aData, $oChange);
		$this->MakeFeedback($oP, 'Organization');
	
		/////////////////////////
		//
		// Locations
		//
		$aData = array(
			'org_id' => $iOrg,
			'name' => 'Rio',
		);
		$iLoc = $this->CreateObject('Location', $aData, $oChange);
		$this->MakeFeedback($oP, 'Location');
		
		/////////////////////////
		//
		// Teams
		//
		$aData = array(
			'org_id' => $iOrg,
			'location_id' => $iLoc,
			'name' => 'Fluminense',
			'email' => 'fluminense@nowhere.fr',
		);
		$iTeam = $this->CreateObject('Team', $aData, $oChange);
		$this->MakeFeedback($oP, 'Team');
	
		/////////////////////////
		//
		// Persons
		//
		for($i = 0 ; $i < $this->m_aPlanned['Contacts'] ; $i++)
		{
			$aData = array(
				'org_id' => $iOrg,
				'location_id' => $iLoc,
				'first_name' => 'Joaõ',
				'name' => 'Ningem #'.$i,
				'email' => 'foo'.$i.'@nowhere.fr',
			);
			$this->CreateObject('Person', $aData, $oChange);
		}
		$this->MakeFeedback($oP, 'Person');
		
		/////////////////////////
		//
		// Services
		//
		$aData = array(
			'name' => 'My Service',
		);
		$iOrg = $this->CreateObject('Service', $aData, $oChange);
		$this->MakeFeedback($oP, 'Service');

		/////////////////////////
		//
		// Service subcategories
		//
		$aData = array(
			'name' => 'My subcategory',
		);
		$iOrg = $this->CreateObject('ServiceSubcategory', $aData, $oChange);
		$this->MakeFeedback($oP, 'ServiceSubcategory');

		/////////////////////////
		//
		// Contracts
		//
		for($i = 0 ; $i < $this->m_aPlanned['Contracts'] ; $i++)
		{
			$aData = array(
				'name' => "Contract #$i",
				'description' => 'Created for benchmarking purposes',
				'org_id' => $this->RandomId('Organization'),
				'provider_id' => $this->RandomId('Organization'),
				'start_date' => '2009-12-25',
				'end_date' => '2019-08-01',
				'support_team_id' => $this->RandomId('Team'),
			);
			$iContract = $this->CreateObject('CustomerContract', $aData, $oChange);
		}
		$this->MakeFeedback($oP, 'CustomerContract');
		
		/////////////////////////
		//
		// Servers
		//
		for($i = 0 ; $i < $this->m_aPlanned['Servers'] ; $i++)
		{
			$aData = array(
				'org_id' => $iOrg,
				'location_id' => $iLoc,
				'name' => 'server'.$i,
			);
			$iServer = $this->CreateObject('Server', $aData, $oChange);

			// Contract/Infra
			//
			$iContractCount = 1;
			for($iLinked = 0 ; $iLinked < $iContractCount ; $iLinked++)
			{
				$aData = array(
					'contract_id' => $this->RandomId('CustomerContract'),
					'ci_id' => $iServer,
				);
				$this->CreateObject('lnkContractToCI', $aData, $oChange);
			}

			// Interfaces
			//
			$iInterfaceCount = 5; // See how aPlanned['Interfaces'] is computed
			for($iLinked = 0 ; $iLinked < $iInterfaceCount ; $iLinked++)
			{
				$aData = array(
					'name' => "eth$iLinked",
					'status' => 'implementation',
					'org_id' => $iOrg,
					'device_id' => $iServer,
				);
				$this->CreateObject('NetworkInterface', $aData, $oChange);
			}
		}
		$this->MakeFeedback($oP, 'Server');

		/////////////////////////
		//
		// Network devices
		//
		for($i = 0 ; $i < $this->m_aPlanned['Network devices'] ; $i++)
		{
			$aData = array(
				'org_id' => $iOrg,
				'location_id' => $iLoc,
				'name' => 'server'.$i,
			);
			$iNWDevice = $this->CreateObject('NetworkDevice', $aData, $oChange);

			// Contract/Infra
			//
			$iContractCount = 1;
			for($iLinked = 0 ; $iLinked < $iContractCount ; $iLinked++)
			{
				$aData = array(
					'contract_id' => $this->RandomId('CustomerContract'),
					'ci_id' => $iNWDevice,
				);
				$this->CreateObject('lnkContractToCI', $aData, $oChange);
			}

			// Interfaces
			//
			$iInterfaceCount = 5; // See how aPlanned['Interfaces'] is computed
			for($iLinked = 0 ; $iLinked < $iInterfaceCount ; $iLinked++)
			{
				$aData = array(
					'name' => "eth$iLinked",
					'status' => 'implementation',
					'org_id' => $iOrg,
					'device_id' => $iNWDevice,
				);
				$this->CreateObject('NetworkInterface', $aData, $oChange);
			}
		}
		$this->MakeFeedback($oP, 'NetworkDevice');
		$this->MakeFeedback($oP, 'NetworkInterface');

		/////////////////////////
		//
		// Incident Tickets
		//
		for($i = 0 ; $i < $this->m_aPlanned['Incidents'] ; $i++)
		{
			$aData = array(
				'org_id' => $iOrg,
				'caller_id' => $this->RandomId('Person'),
				'workgroup_id' => $this->RandomId('Team'),
				'agent_id' => $this->RandomId('Person'),
				'service_id' => $this->RandomId('Service'),
				'servicesubcategory_id' => $this->RandomId('ServiceSubcategory'),
				'title' => 'Incident #'.$i,
				'ticket_log' => 'Testing...',
			);
			$iTicket = $this->CreateObject('Incident', $aData, $oChange);

			// Incident/Infra
			//
			$iInfraCount = rand(0, 6);
			for($iLinked = 0 ; $iLinked < $iInfraCount ; $iLinked++)
			{
				$aData = array(
					'ci_id' => $this->RandomId('Server'),
					'ticket_id' => $iTicket,
				);
				$this->CreateObject('lnkTicketToCI', $aData, $oChange);
			}

			// Incident/Contact
			//
			$iInfraCount = rand(0, 6);
			for($iLinked = 0 ; $iLinked < $iInfraCount ; $iLinked++)
			{
				$aData = array(
					'contact_id' => $this->RandomId('Person'),
					'ticket_id' => $iTicket,
					'role' => 'role '.$iLinked,
				);
				$this->CreateObject('lnkTicketToContact', $aData, $oChange);
			}
		}
		$this->MakeFeedback($oP, 'Incident');

		/////////////////////////
		//
		// Change Tickets
		//
		for($i = 0 ; $i < $this->m_aPlanned['Changes'] ; $i++)
		{
			$aData = array(
				'org_id' => $iOrg,
				'requestor_id' => $this->RandomId('Person'),
				'workgroup_id' => $this->RandomId('Team'),
				'agent_id' => $this->RandomId('Person'),
				'supervisor_group_id' => $this->RandomId('Team'),
				'supervisor_id' => $this->RandomId('Person'),
				'manager_group_id' => $this->RandomId('Team'),
				'manager_id' => $this->RandomId('Person'),
				'title' => 'change #'.$i,
				'description' => "Let's do something there",
			);
			$iTicket = $this->CreateObject('NormalChange', $aData, $oChange);

			// Change/Infra
			//
			$iInfraCount = rand(0, 6);
			for($iLinked = 0 ; $iLinked < $iInfraCount ; $iLinked++)
			{
				$aData = array(
					'ci_id' => $this->RandomId('Server'),
					'ticket_id' => $iTicket,
				);
				$this->CreateObject('lnkTicketToCI', $aData, $oChange);
			}

			// Change/Contact
			//
			$iInfraCount = rand(0, 6);
			for($iLinked = 0 ; $iLinked < $iInfraCount ; $iLinked++)
			{
				$aData = array(
					'contact_id' => $this->RandomId('Person'),
					'ticket_id' => $iTicket,
					'role' => 'role '.$iLinked,
				);
				$this->CreateObject('lnkTicketToContact', $aData, $oChange);
			}
		}
		$this->MakeFeedback($oP, 'NormalChange');
	
		/////////////////////////
		//
		// Service calls
		//
		for($i = 0 ; $i < $this->m_aPlanned['ServiceCalls'] ; $i++)
		{
			$aData = array(
				'org_id' => $iOrg,
				'caller_id' => $this->RandomId('Person'),
				'workgroup_id' => $this->RandomId('Team'),
				'agent_id' => $this->RandomId('Person'),
				'service_id' => $this->RandomId('Service'),
				'servicesubcategory_id' => $this->RandomId('ServiceSubcategory'),
				'title' => 'Call #'.$i,
				'ticket_log' => 'Testing...',
			);
			$iTicket = $this->CreateObject('UserRequest', $aData, $oChange);

			// Call/Infra
			//
			$iInfraCount = rand(0, 6);
			for($iLinked = 0 ; $iLinked < $iInfraCount ; $iLinked++)
			{
				$aData = array(
					'ci_id' => $this->RandomId('Server'),
					'ticket_id' => $iTicket,
				);
				$this->CreateObject('lnkTicketToCI', $aData, $oChange);
			}
		}
		$this->MakeFeedback($oP, 'UserRequest');

		/////////////////////////
		//
		// Documents
		//
		$sMyDoc = '';
		for($i = 0 ; $i < 1000 ; $i++)
		{
			// 100 chars
			$sMyDoc .= "012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678\n";
		}
		$oRefDoc = new ormDocument($sMyDoc, 'text/plain');

		for($i = 0 ; $i < $this->m_aPlanned['Documents'] ; $i++)
		{
			$aData = array(
				//'org_id' => $iOrg,
				'name' => "document$i",
				'contents' => $oRefDoc,
			);
			$this->CreateObject('FileDoc', $aData, $oChange);
		}
		$this->MakeFeedback($oP, 'FileDoc');
	}
}

/**
 * Ask the user what are the settings for the data load
 */  
function DisplayStep1(SetupWebPage $oP)
{
	$sNextOperation = 'step2';
	$oP->add("<h1>iTop benchmarking</h1>\n");
	$oP->add("<h2>Please specify the requested volumes</h2>\n");
	$oP->add("<form method=\"post\" onSubmit=\"return DoSubmit('Evaluating real plans...', 10)\">\n");
	$oP->add("<fieldset><legend>Data load configuration</legend>\n");
	$aForm = array();
	$aForm[] = array(
		'label' => "Main CIs:",
		'input' => "<input id=\"to\" type=\"text\" name=\"plannedcis\" value=\"70\">",
		'help' => ' exclude interfaces, subnets or any other type of secondary CI',
	);
	$aForm[] = array(
		'label' => "Contacts:",
		'input' => "<input id=\"from\" type=\"text\" name=\"plannedcontacts\" value=\"100\">",
		'help' => '',
	);
	$aForm[] = array(
		'label' => "Contracts:",
		'input' => "<input id=\"from\" type=\"text\" name=\"plannedcontracts\" value=\"10\">",
		'help' => '',
	);
	$oP->form($aForm);
	$oP->add("</fieldset>\n");
	$oP->add("<input type=\"hidden\" name=\"operation\" value=\"$sNextOperation\">\n");
	$oP->add("<button type=\"submit\">Next >></button>\n");
	$oP->add("</form>\n");
}


/**
 * Inform the user how many items will be created
 */  
function DisplayStep2(SetupWebPage $oP, $oDataCreation)
{
	$sNextOperation = 'step3';
	$oP->add("<h1>iTop benchmarking</h1>\n");
	$oP->add("<h2>Step 2: review planned volumes</h2>\n");


	$aPlanned = $oDataCreation->GetPlans();
	$aForm = array();
	foreach ($aPlanned as $sKey => $iCount)
	{
		$aForm[] = array(
			'label' => $sKey,
			'input' => $iCount,
		);
	}
	$oP->form($aForm);

	$aRequested = $oDataCreation->GetRequestInfo();
	$oP->add("<form method=\"post\" onSubmit=\"return DoSubmit('Loading data...', 10)\">\n");
	$oP->add("<input type=\"hidden\" name=\"operation\" value=\"$sNextOperation\">\n");
	$oP->add("<input type=\"hidden\" name=\"plannedcis\" value=\"".$aRequested['CIs']."\">\n");
	$oP->add("<input type=\"hidden\" name=\"plannedcontacts\" value=\"".$aRequested['Contacts']."\">\n");
	$oP->add("<input type=\"hidden\" name=\"plannedcontracts\" value=\"".$aRequested['Contracts']."\">\n");
	$oP->add("<button type=\"submit\">Next >></button>\n");
	$oP->add("</form>\n");
}


/**
 * Do create the data set... could take some time to execute
 */  
function DisplayStep3(SetupWebPage $oP, $oDataCreation)
{
//	$sNextOperation = 'step3';

	$oMyChange = MetaModel::NewObject("CMDBChange");
	$oMyChange->Set("date", time());
	$oMyChange->Set("userinfo", "Administrator");
	$iChangeId = $oMyChange->DBInsertNoReload();

	$oDataCreation->GoProjections($oP, $oMyChange);
	$oDataCreation->GoVolume($oP, $oMyChange);
}

/**
 * Main program
 */

LoginWebPage::DoLogin(); // Check user rights and prompt if needed

$sOperation = Utils::ReadParam('operation', 'step1');
$oP = new SetupWebPage('iTop benchmark utility');

try
{
	switch($sOperation)
	{
		case 'step1':
		DisplayStep1($oP);
		break;
		
		case 'step2':
		$oP->no_cache();
		$iPlannedCIs = Utils::ReadParam('plannedcis');
		$iPlannedContacts = Utils::ReadParam('plannedcontacts');
		$iPlannedContracts = Utils::ReadParam('plannedcontracts');

		$oDataCreation = new BenchmarkDataCreation($iPlannedCIs, $iPlannedContacts, $iPlannedContracts);
		DisplayStep2($oP, $oDataCreation);
		break;

		case 'step3':
		$oP->no_cache();
		$iPlannedCIs = Utils::ReadParam('plannedcis');
		$iPlannedContacts = Utils::ReadParam('plannedcontacts');
		$iPlannedContracts = Utils::ReadParam('plannedcontracts');

		$oDataCreation = new BenchmarkDataCreation($iPlannedCIs, $iPlannedContacts, $iPlannedContracts);
		DisplayStep3($oP, $oDataCreation);
		break;

		default:
		$oP->error("Error: unsupported operation '$sOperation'");
		
	}
}
catch(ZZException $e)
{
	$oP->error("Error: '".$e->getMessage()."'");	
}
catch(ZZCoreException $e)
{
	$oP->error("Error: '".$e->getHtmlDesc()."'");	
}
$oP->output();
?>

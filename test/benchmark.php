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

require_once('../approot.inc.php');
require_once(APPROOT.'/application/application.inc.php');
require_once(APPROOT.'/application/wizardhelper.class.inc.php');
require_once(APPROOT.'/application/startup.inc.php');
require_once(APPROOT.'/application/loginwebpage.class.inc.php');
require_once(APPROOT.'/application/utils.inc.php');
require_once(APPROOT.'/setup/setuppage.class.inc.php');

//ini_set('memory_limit', '2048M');

class BenchmarkDataCreation
{
	var $m_iIfByServer;
	var $m_iIfByNWDevice;
	var $m_aRequested;
	var $m_aPlanned;
	var $m_aCreatedByClass = array();
	var $m_aCreatedByDesc = array();

	var $m_aStatsByClass = array();

	/** @var \CMDBChange $m_oChange */
	var $m_oChange;
	public function __construct()
	{
		CMDBObject::SetTrackInfo('Benchmark setup');
	}

	public function PlanStructure($iPlannedContacts, $iPlannedContracts)
	{
		$this->m_aRequested = array(
			'plannedcontacts' => $iPlannedContacts,
			'plannedcontracts' => $iPlannedContracts,
		);
		$this->m_aPlanned = array(
			'Contacts' => $iPlannedContacts,
			'Contracts' => $iPlannedContracts,
			'Documents' => $iPlannedContracts * 2,
		);
	}

	public function PlanCis($iPlannedCIs)
	{
		$this->m_aRequested = array(
			'plannedcis' => $iPlannedCIs,
		);

		$this->m_iIfByServer = 2;
		$this->m_iIfByNWDevice = 10;

		$iServers = ceil($iPlannedCIs * 9 / 10);
		$iNWDevices = ceil($iPlannedCIs / 10);
		$iInterfaces = $iServers * $this->m_iIfByServer + $iNWDevices * $this->m_iIfByNWDevice;
		$iApplications = $iServers * 5;
		$iSolutions = ceil($iApplications / 2);
		$iProcesses = ceil($iSolutions / 2);

		$this->m_aPlanned = array(
			'Network devices' => $iNWDevices,
			'Servers' => $iServers,
			'Interfaces' => $iInterfaces,
			'Application SW' => 2,
			'Applications' => $iApplications,
			'Solutions' => $iSolutions,
			'Processes' => $iProcesses,
		);
	}

	public function PlanTickets($iPlannedTickets, $iBigTicketCis)
	{
		$this->m_aRequested = array(
			'plannedtickets' => $iPlannedTickets,
			'plannedbigticketcis' => $iBigTicketCis,
		);

		$this->m_aPlanned = array(
			'Incidents' => ceil($iPlannedTickets / 2),
			'Changes' => ceil($iPlannedTickets / 2),
			'Big ticket: CIs' => $iBigTicketCis,
		);
	}

	public function ShowPlans($oP)
	{
		$oP->add("<h2>Planned creations</h2>\n");
		$aPlanned = $this->m_aPlanned;
		$aForm = array();
		foreach ($aPlanned as $sKey => $iCount)
		{
			$aForm[] = array(
				'label' => $sKey,
				'input' => $iCount,
			);
		}
		$oP->form($aForm);
	}
	
	public function ShowForm($oP, $sNextOperation)
	{
		$aRequested = $this->m_aRequested;
		$oP->add("<form method=\"post\" onSubmit=\"return DoSubmit('Loading data...', 10)\">\n");
		$oP->add("<input type=\"hidden\" name=\"operation\" value=\"$sNextOperation\">\n");
		foreach($this->m_aRequested as $sName => $sValue)
		{
			$oP->add("<input type=\"hidden\" name=\"$sName\" value=\"$sValue\">\n");
		}
		$oP->add("<button type=\"submit\">Next >></button>\n");
		$oP->add("</form>\n");
	}

	protected function CreateObject($sClass, $aData, $sClassDesc = '')
	{
		$mu_t1 = MyHelpers::getmicrotime();

		$oMyObject = MetaModel::NewObject($sClass);
		foreach($aData as $sProp => $value)
		{
			if (is_array($value))
			{
				// transform into a link set
				$sCSVSpec = implode('|', $value);
				$oAttDef = MetaModel::GetAttributeDef($sClass, $sProp);
				$value = $oAttDef->MakeValueFromString($sCSVSpec, $bLocalizedValue = false, $sSepItem = '|', $sSepAttribute = ';', $sSepValue = ':', $sAttributeQualifier = '"');
			}
			$oMyObject->Set($sProp, $value);
		}

		$iId = $oMyObject->DBInsertNoReload();

		$sClassId = "$sClass ($sClassDesc)";
		$this->m_aCreatedByDesc[$sClassId][] = $iId;
		$this->m_aCreatedByClass[$sClass][] = $iId;

		$mu_t2 = MyHelpers::getmicrotime();
		$this->m_aStatsByClass[$sClass][] = $mu_t2 - $mu_t1;
		
		return $iId;
	}

	static $m_aClassIdCache = array();
	protected function GetClassIds($sClass)
	{
		if (!isset(self::$m_aClassIdCache[$sClass]))
		{
			// Load the cache now
			self::$m_aClassIdCache[$sClass] = array();
			
			$oSet = new DBObjectSet(new DBObjectSearch($sClass));
			while($oObj = $oSet->Fetch())
			{
				self::$m_aClassIdCache[$sClass][] = $oObj->GetKey();
			}
		}
		return self::$m_aClassIdCache[$sClass];
	}

	protected function RandomId($sClass, $sClassDesc = '')
	{
		$sClassId = "$sClass ($sClassDesc)";
		if (isset($this->m_aCreatedByDesc[$sClassId]))
		{
			return $this->m_aCreatedByDesc[$sClassId][array_rand($this->m_aCreatedByDesc[$sClassId])];
		}
		
		$aIds = self::GetClassIds($sClass);
		return $aIds[array_rand($aIds)];
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

	protected function my_array_rand($aData, $iCount)
	{
		if ($iCount == 0)
		{
			return array();
		}
		elseif ($iCount == 1)
		{
			// array_rand() for one item returns only the key
			$key = array_rand($aData);
			$aSample = array($key);
		}
		elseif ($iCount <= count($aData))
		{
			$aSample = array_rand($aData, $iCount);
		}
		else
		{
			$aSample = array_merge(array_keys($aData), self::my_array_rand($aData, $iCount - count($aData)));
		}
		return $aSample;
	}

	protected function CreateLinks($iFrom, $iCount, $sLinkClass, $sAttCodeFrom, $sAttCodeTo)
	{
		$oAttTo = MetaModel::GetAttributeDef($sLinkClass, $sAttCodeTo);
		$sToClass = $oAttTo->GetTargetClass();

		$aTargets = self::GetClassIds($sToClass);
		$aSample = self::my_array_rand($aTargets, $iCount);

		foreach($aSample as $key)
		{
			$aData = array(
				$sAttCodeFrom => $iFrom,
				$sAttCodeTo => $aTargets[$key],
			);
			$this->CreateObject($sLinkClass, $aData);
		}
	}

	public function CreateStructure($oP)
	{
		$aClasses = MetaModel::GetClasses();
		$aActions = array('Read', 'Bulk Read', 'Delete', 'Bulk Delete', 'Modify', 'Bulk Modify');
		$aStdProfiles = array(2, 3, 4, 5, 6, 7, 8, 9);

		////////////////////////////////////////
		// New specific profile, giving access to everything
		//
		$aData = array(
			'name' => 'Data guru',
			'description' => 'Could do anything, because everything is granted',
		);
		$iGuruProfile = $this->CreateObject('URP_Profiles', $aData);
		foreach($aClasses as $sClass)
		{
			foreach($aActions as $sAction)
			{
				$aData = array(
					'profileid' => $iGuruProfile,
					'class' => $sClass,
					'permission' => 'yes',
					'action' => $sAction,
				);
				$this->CreateObject('URP_ActionGrant', $aData);
			}
		}

		// User login with super access rights
		//
		$aData = array(
			'org_id' => self::FindId('Organization'),
			'location_id' => self::FindId('Location'),
			'first_name' => 'Jesus',
			'name' => 'Deus',
			'email' => 'guru@combodo.com',
		);
		$iPerson = $this->CreateObject('Person', $aData);
		$aData = array(
			'contactid' => $iPerson,
			'login' => 'guru',
			'password' => 'guru',
			'language' => 'EN US',
			'profile_list' => array("profileid:$iGuruProfile;reason:he is the one"),
		);
		$iLogin = $this->CreateObject('UserLocal', $aData);

		////////////////////////////////////////
		// User login having all std profiles
		//
		$aData = array(
			'org_id' => self::FindId('Organization'),
			'location_id' => self::FindId('Location'),
			'first_name' => 'Little ze',
			'name' => 'Foo',
			'email' => 'foo@combodo.com',
		);
		$iPerson = $this->CreateObject('Person', $aData);

		$aProfileSet = array();
		foreach($aStdProfiles as $iProfileId)
		{
			$aProfileSet[] = "profileid:$iProfileId;reason:xxx";
		}
		$aData = array(
			'contactid' => $iPerson,
			'login' => 'foo',
			'password' => 'foo',
			'language' => 'EN US',
			'profile_list' => $aProfileSet,
		);
		$iLogin = $this->CreateObject('UserLocal', $aData);

		/////////////////////////
		//
		// Organizations
		//
		$aData = array(
			'name' => 'Benchmark',
		);
		$iOrg = $this->CreateObject('Organization', $aData);
	
		/////////////////////////
		//
		// Locations
		//
		$aData = array(
			'org_id' => $iOrg,
			'name' => 'Rio de Janeiro',
		);
		$iLoc = $this->CreateObject('Location', $aData);
		
		/////////////////////////
		//
		// Teams
		//
		$aData = array(
			'org_id' => $iOrg,
			'location_id' => $iLoc,
			'name' => 'Fluminense',
			'email' => 'fluminense@combodo.com',
		);
		$iTeam = $this->CreateObject('Team', $aData);
	
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
			$iPerson = $this->CreateObject('Person', $aData);

			// Contract/Infra
			//
			$aData = array(
				'contact_id' => $iPerson,
				'team_id' => $this->RandomId('Team'),
			);
			$this->CreateObject('lnkTeamToContact', $aData);
		}
		
		/////////////////////////
		//
		// Services
		//
		$aData = array(
			'org_id' => $iOrg,
			'name' => 'My Service',
		);
		$iService = $this->CreateObject('Service', $aData);

		/////////////////////////
		//
		// Service subcategories
		//
		$aData = array(
			'name' => 'My subcategory',
			'service_id' => $iService,
		);
		$iOrg = $this->CreateObject('ServiceSubcategory', $aData);

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
			$iContract = $this->CreateObject('CustomerContract', $aData);

			// Contract/Contact (10% of contacts)
			//
			$iContactCount = ceil($this->m_aPlanned['Contracts'] / 10);
			for($iLinked = 0 ; $iLinked < $iContactCount ; $iLinked++)
			{
				$aData = array(
					'contact_id' => $this->RandomId('Person'),
					'contract_id' => $iContract,
					'role' => 'role '.$iLinked,
				);
				$this->CreateObject('lnkContractToContact', $aData);
			}
		}

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
				'org_id' => $iOrg,
				'name' => "document$i",
				'contents' => $oRefDoc,
			);
			$this->CreateObject('FileDoc', $aData);
		}
	}

	public function CreateCis($oP)
	{
		$iOrg = $this->FindIdFromOQL("SELECT Organization WHERE name = 'Benchmark'");
		$iLoc = $this->FindIdFromOQL("SELECT Location WHERE org_id = $iOrg");

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
				'status' => 'production',
			);
			$iServer = $this->CreateObject('Server', $aData);

			// Contract/Infra
			$this->CreateLinks($iServer, 1, 'lnkContractToCI', 'ci_id', 'contract_id');

			// Interfaces
			for($iLinked = 0 ; $iLinked < $this->m_iIfByServer ; $iLinked++)
			{
				$aData = array(
					'name' => "eth$iLinked",
					'status' => 'implementation',
					'org_id' => $iOrg,
					'device_id' => $iServer,
					'status' => 'production',
				);
				$this->CreateObject('NetworkInterface', $aData, 'server if');
			}
		}

		/////////////////////////
		//
		// Network devices
		//
		for($i = 0 ; $i < $this->m_aPlanned['Network devices'] ; $i++)
		{
			$aData = array(
				'org_id' => $iOrg,
				'location_id' => $iLoc,
				'name' => 'equipment #'.$i,
				'status' => 'production',
			);
			$iNWDevice = $this->CreateObject('NetworkDevice', $aData);

			// Contract/Infra
			$this->CreateLinks($iNWDevice, 1, 'lnkContractToCI', 'ci_id', 'contract_id');

			// Interfaces
			//
			for($iLinked = 0 ; $iLinked < $this->m_iIfByNWDevice ; $iLinked++)
			{
				$aData = array(
					'name' => "eth$iLinked",
					'status' => 'implementation',
					'org_id' => $iOrg,
					'device_id' => $iNWDevice,
					'connected_if' => $this->RandomId('NetworkInterface', 'server if'),
					'status' => 'production',
				);
				$this->CreateObject('NetworkInterface', $aData, 'equipment if');
			}
		}

		/////////////////////////
		//
		// Application Software
		//
		for($i = 0 ; $i < $this->m_aPlanned['Application SW'] ; $i++)
		{
			$aData = array(
				'name' => 'Software #'.$i,
			);
			$iNWDevice = $this->CreateObject('Application', $aData);
		}

		/////////////////////////
		//
		// Applications
		//
		for($i = 0 ; $i < $this->m_aPlanned['Applications'] ; $i++)
		{
			$aData = array(
				'org_id' => $iOrg,
				'device_id' => $this->RandomId('Server'),
				'software_id' => $this->RandomId('Application'),
				'name' => 'Application #'.$i,
				'status' => 'production',
			);
			$iAppInstance = $this->CreateObject('ApplicationInstance', $aData);

			// Contract/Infra
			$this->CreateLinks($iAppInstance, 1, 'lnkContractToCI', 'ci_id', 'contract_id');
		}

		/////////////////////////
		//
		// Application Solution
		//
		for($i = 0 ; $i < $this->m_aPlanned['Solutions'] ; $i++)
		{
			$aData = array(
				'org_id' => $iOrg,
				'name' => 'Solution #'.$i,
				'status' => 'production',
			);
			$iAppSolution = $this->CreateObject('ApplicationSolution', $aData);

			// Contract/Infra
			$this->CreateLinks($iAppSolution, 1, 'lnkContractToCI', 'ci_id', 'contract_id');
		}

		/////////////////////////
		//
		// Business Process
		//
		for($i = 0 ; $i < $this->m_aPlanned['Processes'] ; $i++)
		{
			$aData = array(
				'org_id' => $iOrg,
				'name' => 'Process #'.$i,
				'status' => 'production',
			);
			$iProcess = $this->CreateObject('BusinessProcess', $aData);

			// Contract/Infra
			$this->CreateLinks($iProcess, 1, 'lnkContractToCI', 'ci_id', 'contract_id');
		}
	}

	public function CreateTickets($oP)
	{
		$iOrg = $this->FindIdFromOQL("SELECT Organization WHERE name = 'Benchmark'");
		$iLoc = $this->FindIdFromOQL("SELECT Location WHERE org_id = $iOrg");

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
				'description' => 'O que aconteceu?',
				'ticket_log' => 'Testing...',
			);
			$iTicket = $this->CreateObject('Incident', $aData);

			// Incident/Infra
			$iInfraCount = rand(1, 6);
			$this->CreateLinks($iTicket, $iInfraCount, 'lnkTicketToCI', 'ticket_id', 'ci_id');

			// Incident/Infra
			$iContactCount = rand(1, 6);
			$this->CreateLinks($iTicket, $iContactCount, 'lnkTicketToContact', 'ticket_id', 'contact_id');
		}

		/////////////////////////
		//
		// Big Ticket
		//
		$aData = array(
			'org_id' => $iOrg,
			'caller_id' => $this->RandomId('Person'),
			'workgroup_id' => $this->RandomId('Team'),
			'agent_id' => $this->RandomId('Person'),
			'service_id' => $this->RandomId('Service'),
			'servicesubcategory_id' => $this->RandomId('ServiceSubcategory'),
			'title' => 'Big ticket',
			'description' => 'O que aconteceu?',
			'ticket_log' => 'Testing...',
		);
		$iTicket = $this->CreateObject('Incident', $aData);

		// Incident/Infra
		$iInfraCount = $this->m_aPlanned['Big ticket: CIs'];
		$this->CreateLinks($iTicket, $iInfraCount, 'lnkTicketToCI', 'ticket_id', 'ci_id');

		// Incident/Infra
		$iContactCount = rand(1, 6);
		$this->CreateLinks($iTicket, $iContactCount, 'lnkTicketToContact', 'ticket_id', 'contact_id');

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
			$iTicket = $this->CreateObject('NormalChange', $aData);

			// Incident/Infra
			$iInfraCount = rand(1, 6);
			$this->CreateLinks($iTicket, $iInfraCount, 'lnkTicketToCI', 'ticket_id', 'ci_id');
	
			// Incident/Infra
			$iContactCount = rand(1, 6);
			$this->CreateLinks($iTicket, $iContactCount, 'lnkTicketToContact', 'ticket_id', 'contact_id');
		}
	}

	public function MakeFeedback($oP)
	{
		foreach($this->m_aCreatedByClass as $sClass => $aClassIds)
		{
			$iSample = reset($aClassIds);
			$sSample = "<a href=\"".utils::GetAbsoluteUrlAppRoot()."pages/UI.php?operation=details&class=$sClass&id=$iSample\">sample</a>";
	
			$iDuration = number_format(array_sum($this->m_aStatsByClass[$sClass]), 3);
			$fDurationMin = number_format(min($this->m_aStatsByClass[$sClass]), 3);
			$fDurationMax = number_format(max($this->m_aStatsByClass[$sClass]), 3);
			$fDurationAverage = number_format(array_sum($this->m_aStatsByClass[$sClass]) / count($this->m_aStatsByClass[$sClass]), 3);
	
			$oP->add("<ul>");
			$oP->add("<li>");
			$oP->add("$sClass: ".count($this->m_aStatsByClass[$sClass])." - $sSample<br/>");
			$oP->add("Duration: $fDurationMin =&gt; $fDurationMax; Avg:$fDurationAverage; Total: $iDuration");
			$oP->add("</li>");
			$oP->add("</ul>");
		}
	}
}

/**
 * Ask the user what are the settings for the data load
 */  
function DisplayStep1(SetupPage $oP)
{
	$sNextOperation = 'step2';
	$oP->add("<h1>iTop benchmarking</h1>\n");

	$oP->add("<form method=\"post\" onSubmit=\"return DoSubmit('Evaluating real plans...', 10)\">\n");
	$oP->add("<fieldset><legend>Data load configuration</legend>\n");
	$aForm = array();
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
	$oP->add("<input type=\"hidden\" name=\"operation\" value=\"create_structure\">\n");
	$oP->add("<button type=\"submit\">Next >></button>\n");
	$oP->add("</form>\n");

	$oP->add("<form method=\"post\" onSubmit=\"return DoSubmit('Evaluating real plans...', 10)\">\n");
	$oP->add("<fieldset><legend>Data load configuration</legend>\n");
	$aForm = array();
	$aForm[] = array(
		'label' => "Main CIs:",
		'input' => "<input id=\"to\" type=\"text\" name=\"plannedcis\" value=\"70\">",
		'help' => ' exclude interfaces, subnets or any other type of secondary CI',
	);
	$oP->form($aForm);
	$oP->add("</fieldset>\n");
	$oP->add("<input type=\"hidden\" name=\"operation\" value=\"create_cis\">\n");
	$oP->add("<button type=\"submit\">Next >></button>\n");
	$oP->add("</form>\n");

	$oP->add("<form method=\"post\" onSubmit=\"return DoSubmit('Evaluating real plans...', 10)\">\n");
	$oP->add("<fieldset><legend>Data load configuration</legend>\n");
	$aForm = array();
	$aForm[] = array(
		'label' => "Tickets:",
		'input' => "<input id=\"to\" type=\"text\" name=\"plannedtickets\" value=\"200\">",
		'help' => ' 50% incidents, 50% changes',
	);
	$aForm[] = array(
		'label' => "CIs for the big ticket:",
		'input' => "<input id=\"to\" type=\"text\" name=\"plannedbigticketcis\" value=\"200\">",
		'help' => 'Number of CI for the single big ticket',
	);
	$oP->form($aForm);
	$oP->add("</fieldset>\n");
	$oP->add("<input type=\"hidden\" name=\"operation\" value=\"create_tickets\">\n");
	$oP->add("<button type=\"submit\">Next >></button>\n");
	$oP->add("</form>\n");
}


/**
 * Main program
 */

LoginWebPage::DoLogin(); // Check user rights and prompt if needed

$sOperation = Utils::ReadParam('operation', 'step1');
$oP = new SetupPage('iTop benchmark utility');

ExecutionKPI::EnableDuration();
$oKPI = new ExecutionKPI();

try
{
	switch($sOperation)
	{
		case 'step1':
		DisplayStep1($oP);
		break;

		case 'create_structure':
			$oP->no_cache();
			$oP->add_xframe_options('DENY');
			$iPlannedContacts = Utils::ReadParam('plannedcontacts');
			$iPlannedContracts = Utils::ReadParam('plannedcontracts');

			$oDataCreation = new BenchmarkDataCreation();
			$oDataCreation->PlanStructure($iPlannedContacts, $iPlannedContracts);
			$oDataCreation->ShowPlans($oP);
			$oDataCreation->ShowForm($oP, 'create_structure_go');
			break;

		case 'create_structure_go':
		$oP->no_cache();
		$iPlannedContacts = Utils::ReadParam('plannedcontacts');
		$iPlannedContracts = Utils::ReadParam('plannedcontracts');

		$oDataCreation = new BenchmarkDataCreation();
		$oDataCreation->PlanStructure($iPlannedContacts, $iPlannedContracts);
		$oDataCreation->CreateStructure($oP);
		$oDataCreation->MakeFeedback($oP);
		break;

		case 'create_cis':
		$oP->no_cache();
		$iPlannedCIs = Utils::ReadParam('plannedcis');

		$oDataCreation = new BenchmarkDataCreation();
		$oDataCreation->PlanCis($iPlannedCIs);
		$oDataCreation->ShowPlans($oP);
		$oDataCreation->ShowForm($oP, 'create_cis_go');
		break;

		case 'create_cis_go':
		$oP->no_cache();
		$iPlannedCIs = Utils::ReadParam('plannedcis');

		$oDataCreation = new BenchmarkDataCreation();
		$oDataCreation->PlanCis($iPlannedCIs);
		$oDataCreation->CreateCis($oP);
		$oDataCreation->MakeFeedback($oP);
		break;

		case 'create_tickets':
		$oP->no_cache();
		$iPlannedTickets = Utils::ReadParam('plannedtickets');
		$iBigTicketCis = Utils::ReadParam('plannedbigticketcis');

		$oDataCreation = new BenchmarkDataCreation();
		$oDataCreation->PlanTickets($iPlannedTickets, $iBigTicketCis);
		$oDataCreation->ShowPlans($oP);
		$oDataCreation->ShowForm($oP, 'create_tickets_go');
		break;

		case 'create_tickets_go':
		$oP->no_cache();
		$iPlannedTickets = Utils::ReadParam('plannedtickets');
		$iBigTicketCis = Utils::ReadParam('plannedbigticketcis');

		$oDataCreation = new BenchmarkDataCreation();
		$oDataCreation->PlanTickets($iPlannedTickets, $iBigTicketCis);
		$oDataCreation->CreateTickets($oP);
		$oDataCreation->MakeFeedback($oP);
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
$oKPI->ComputeAndReport('Total execution');
//DBSearch::RecordQueryTrace();
$oP->output();
?>

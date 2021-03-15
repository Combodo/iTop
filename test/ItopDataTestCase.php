<?php
// Copyright (c) 2010-2021 Combodo SARL
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>
//

namespace Combodo\iTop\Test\UnitTest;

/**
 * Created by PhpStorm.
 * User: Eric
 * Date: 20/11/2017
 * Time: 11:21
 */

use ArchivedObjectException;
use CMDBSource;
use Contact;
use DBObject;
use DBObjectSet;
use DBSearch;
use Exception;
use Farm;
use FunctionalCI;
use Hypervisor;
use lnkContactToFunctionalCI;
use lnkContactToTicket;
use lnkFunctionalCIToTicket;
use MetaModel;
use Person;
use Server;
use TagSetFieldData;
use Ticket;
use URP_UserProfile;
use VirtualHost;
use VirtualMachine;


/** @see \Combodo\iTop\Test\UnitTest\ItopDataTestCase::CreateObjectWithTagSet() */
define('TAG_CLASS', 'FAQ');
define('TAG_ATTCODE', 'domains');

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class ItopDataTestCase extends ItopTestCase
{
	private $iTestOrgId;
	// For cleanup
	private $aCreatedObjects = array();

	const USE_TRANSACTION = true;
	const CREATE_TEST_ORG = false;

	/**
	 * @throws Exception
	 */
	protected function setUp()
	{
		parent::setUp();
		require_once(APPROOT.'application/utils.inc.php');

		$sEnv = 'production';
		$sConfigFile = APPCONF.$sEnv.'/'.ITOP_CONFIG_FILE;
		MetaModel::Startup($sConfigFile, false /* $bModelOnly */, true /* $bAllowCache */, false /* $bTraceSourceFiles */, $sEnv);

		if (static::USE_TRANSACTION)
		{
			CMDBSource::Query('START TRANSACTION');
		}
		if (static::CREATE_TEST_ORG)
		{
			$this->CreateTestOrganization();
		}
	}

	/**
	 * @throws Exception
	 */
	protected function tearDown()
	{
		if (static::USE_TRANSACTION)
		{
			$this->debug("ROLLBACK !!!");
			CMDBSource::Query('ROLLBACK');
		}
		else
		{
			$this->debug("");
			$this->aCreatedObjects = array_reverse($this->aCreatedObjects);
			foreach ($this->aCreatedObjects as $oObject)
			{
				/** @var DBObject $oObject */
				try
				{
					$sClass = get_class($oObject);
					$iKey = $oObject->GetKey();
					$this->debug("Removing $sClass::$iKey");
					$oObject->DBDelete();
				}
				catch (Exception $e)
				{
					$this->debug($e->getMessage());
				}
			}
		}
	}

	/**
	 * @return mixed
	 */
	public function getTestOrgId()
	{
		return $this->iTestOrgId;
	}

	/////////////////////////////////////////////////////////////////////////////
	/// Database Utilities
	/////////////////////////////////////////////////////////////////////////////

	/**
	 * @param string $sClass
	 * @param array $aParams
	 *
	 * @return DBObject
	 * @throws Exception
	 */
	protected function createObject($sClass, $aParams)
	{
		$oMyObj = MetaModel::NewObject($sClass);
		foreach ($aParams as $sAttCode => $oValue)
		{
			$oMyObj->Set($sAttCode, $oValue);
		}
		$oMyObj->DBInsert();
		$iKey = $oMyObj->GetKey();
		$this->debug("Created $sClass::$iKey");
		$this->aCreatedObjects[] = $oMyObj;

		return $oMyObj;
	}

	/**
	 * @param string $sClass
	 * @param $iKey
	 * @param array $aParams
	 *
	 * @return DBObject
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 */
	protected static function updateObject($sClass, $iKey, $aParams)
	{
		$oMyObj = MetaModel::GetObject($sClass, $iKey);
		foreach ($aParams as $sAttCode => $oValue)
		{
			$oMyObj->Set($sAttCode, $oValue);
		}
		$oMyObj->DBUpdate();

		return $oMyObj;
	}

	/**
	 * Create an Organization in database
	 *
	 * @param string $sName
	 *
	 * @return \Organization
	 * @throws Exception
	 */
	protected function CreateOrganization($sName)
	{
		/** @var \Organization $oObj */
		$oObj = $this->createObject('Organization', array(
			'name' => $sName,
		));
		$this->debug("Created Organization {$oObj->Get('name')}");

		return $oObj;
	}

	/**
	 * Create a Ticket in database
	 *
	 * @param int $iNum
	 *
	 * @return Ticket
	 * @throws Exception
	 */
	protected function CreateTicket($iNum)
	{
		/** @var Ticket $oTicket */
		$oTicket = $this->createObject('UserRequest', array(
			'ref' => 'Ticket_'.$iNum,
			'title' => 'TICKET_'.$iNum,
			//'request_type' => 'incident',
			'description' => 'Created for unit tests.',
			'org_id' => $this->getTestOrgId(),
		));
		$this->debug("Created {$oTicket->Get('title')} ({$oTicket->Get('ref')})");

		return $oTicket;
	}

	protected function RemoveTicket($iNum)
	{
		$this->RemoveObjects('UserRequest', "SELECT UserRequest WHERE ref = 'Ticket_$iNum'");
	}

	/**
	 * Create a Ticket in database
	 *
	 * @param string $sClass
	 * @param string $sAttCode
	 * @param string $sTagCode
	 * @param string $sTagLabel
	 * @param string $sTagDescription
	 *
	 * @return \TagSetFieldData
	 * @throws \CoreException
	 */
	protected function CreateTagData($sClass, $sAttCode, $sTagCode, $sTagLabel, $sTagDescription = '')
	{
		$sTagClass = TagSetFieldData::GetTagDataClassName($sClass, $sAttCode);
		$oTagData = $this->createObject($sTagClass, array(
			'code' => $sTagCode,
			'label' => $sTagLabel,
			'obj_class' => $sClass,
			'obj_attcode' => $sAttCode,
			'description' => $sTagDescription,
		));
		$this->debug("Created {$oTagData->Get('code')} ({$oTagData->Get('label')})");

		/** @var \TagSetFieldData $oTagData */
		return $oTagData;
	}

	/**
	 * Create a Ticket in database
	 *
	 * @param string $sClass
	 * @param string $sAttCode
	 * @param string $sTagCode
	 *
	 * @throws \CoreException
	 */
	protected function RemoveTagData($sClass, $sAttCode, $sTagCode)
	{
		$sTagClass = TagSetFieldData::GetTagDataClassName($sClass, $sAttCode);
		$this->RemoveObjects($sTagClass, "SELECT $sTagClass WHERE code = '$sTagCode'");
	}

	private function RemoveObjects($sClass, $sOQL)
	{
		$oFilter = DBSearch::FromOQL($sOQL);
		$aRes = $oFilter->ToDataArray(array('id'));
		foreach ($aRes as $aRow)
		{
			$this->debug($aRow);
			$iKey = $aRow['id'];
			if (!empty($iKey))
			{
				$oObject = MetaModel::GetObject($sClass, $iKey);
				$oObject->DBDelete();
			}
		}
	}

	/**
	 * Create a UserRequest in database
	 *
	 * @param int $iNum
	 * @param int $iTimeSpent
	 * @param int $iOrgId
	 * @param int $iCallerId
	 *
	 * @return \UserRequest
	 * @throws Exception
	 */
	protected function CreateUserRequest($iNum, $iTimeSpent = 0, $iOrgId = 0, $iCallerId = 0)
	{
		/** @var \UserRequest $oTicket */
		$oTicket = $this->createObject('UserRequest', array(
			'ref' => 'Ticket_'.$iNum,
			'title' => 'BUG 1161_'.$iNum,
			//'request_type' => 'incident',
			'description' => 'Add aggregate functions',
			'time_spent' => $iTimeSpent,
			'caller_id' => $iCallerId,
			'org_id' => ($iOrgId == 0 ? $this->getTestOrgId() : $iOrgId),
		));
		$this->debug("Created {$oTicket->Get('title')} ({$oTicket->Get('ref')})");

		return $oTicket;
	}

	/**
	 * Create a Server in database
	 *
	 * @param int $iNum
	 * @param null $iRackUnit
	 *
	 * @return Server
	 * @throws \Exception
	 */
	protected function CreateServer($iNum, $iRackUnit = null)
	{
		/** @var Server $oServer */
		$oServer = $this->createObject('Server', array(
			'name' => 'Server_'.$iNum,
			'org_id' => $this->getTestOrgId(),
			'nb_u' => $iRackUnit,
		));
		$this->debug("Created {$oServer->GetName()} ({$oServer->GetKey()})");

		return $oServer;
	}

	/**
	 * Create a PhysicalInterface in database
	 *
	 * @param int $iNum
	 * @param int $iSpeed
	 * @param int $iConnectableCiId
	 *
	 * @return DBObject
	 * @throws Exception
	 */
	protected function CreatePhysicalInterface($iNum, $iSpeed, $iConnectableCiId)
	{
		$oObj = $this->createObject('PhysicalInterface', array(
			'name' => "$iNum",
			'speed' => $iSpeed,
			'connectableci_id' => $iConnectableCiId,
		));
		$this->debug("Created {$oObj->GetName()} ({$oObj->GetKey()})");

		return $oObj;
	}

	/**
	 * Create a FiberChannelInterface in database
	 *
	 * @param int $iNum
	 * @param int $iSpeed
	 * @param int $iConnectableCiId
	 *
	 * @return DBObject
	 * @throws Exception
	 */
	protected function CreateFiberChannelInterface($iNum, $iSpeed, $iConnectableCiId)
	{
		$oObj = $this->createObject('FiberChannelInterface', array(
			'name' => "$iNum",
			'speed' => $iSpeed,
			'datacenterdevice_id' => $iConnectableCiId,
		));
		$this->debug("Created {$oObj->GetName()} ({$oObj->GetKey()})");

		return $oObj;
	}

	/**
	 * Create a Person in database
	 *
	 * @param int $iNum
	 * @param int $iOrgId
	 *
	 * @return Person
	 * @throws Exception
	 */
	protected function CreatePerson($iNum, $iOrgId = 0)
	{
		/** @var Person $oPerson */
		$oPerson = $this->createObject('Person', array(
			'name' => 'Person_'.$iNum,
			'first_name' => 'Test',
			'org_id' => ($iOrgId == 0 ? $this->getTestOrgId() : $iOrgId),
		));
		$this->debug("Created {$oPerson->GetName()} ({$oPerson->GetKey()})");

		return $oPerson;
	}

	/**
	 * @param string $sLogin
	 * @param int $iProfileId
	 *
	 * @return \DBObject
	 * @throws Exception
	 */
	protected function CreateUser($sLogin, $iProfileId, $sPassword=null)
	{
		if (empty($sPassword)){
			$sPassword = $sLogin;
		}

		$oUserProfile = new URP_UserProfile();
		$oUserProfile->Set('profileid', $iProfileId);
		$oUserProfile->Set('reason', 'UNIT Tests');
		$oSet = DBObjectSet::FromObject($oUserProfile);
		$oUser = $this->createObject('UserLocal', array(
			'contactid' => 2,
			'login' => $sLogin,
			'password' => $sPassword,
			'language' => 'EN US',
			'profile_list' => $oSet,
		));
		$this->debug("Created {$oUser->GetName()} ({$oUser->GetKey()})");

		return $oUser;
	}

	/**
	 * @param \DBObject $oUser
	 * @param int $iProfileId
	 *
	 * @return \DBObject
	 * @throws Exception
	 */
	protected function AddProfileToUser($oUser, $iProfileId)
	{
		$oUserProfile = new URP_UserProfile();
		$oUserProfile->Set('profileid', $iProfileId);
		$oUserProfile->Set('reason', 'UNIT Tests');
		/** @var DBObjectSet $oSet */
		$oSet = $oUser->Get('profile_list');
		$oSet->AddObject($oUserProfile);
		$oUser = $this->updateObject('UserLocal', $oUser->GetKey(), array(
			'profile_list' => $oSet,
		));
		$this->debug("Updated {$oUser->GetName()} ({$oUser->GetKey()})");

		return $oUser;
	}


	/**
	 * Create a Hypervisor in database
	 *
	 * @param int $iNum
	 * @param Server $oServer
	 * @param Farm $oFarm
	 *
	 * @return Hypervisor
	 * @throws Exception
	 */
	protected function CreateHypervisor($iNum, $oServer, $oFarm = null)
	{
		/** @var Hypervisor $oHypervisor */
		$oHypervisor = $this->createObject('Hypervisor', array(
			'name' => 'Hypervisor_'.$iNum,
			'org_id' => $this->getTestOrgId(),
			'server_id' => $oServer->GetKey(),
			'farm_id' => is_null($oFarm) ? 0 : $oFarm->GetKey(),
		));
		if (is_null($oFarm))
		{
			$this->debug("Created {$oHypervisor->GetName()} ({$oHypervisor->GetKey()}) on {$oServer->GetName()}");
		}
		else
		{
			$this->debug("Created {$oHypervisor->GetName()} ({$oHypervisor->GetKey()}) on {$oServer->GetName()} part of {$oFarm->GetName()}");
		}

		return $oHypervisor;
	}

	/**
	 * Create a Farm in database
	 *
	 * @param int $iNum
	 * @param string $sRedundancy
	 *
	 * @return Farm
	 * @throws Exception
	 */
	protected function CreateFarm($iNum, $sRedundancy = '1')
	{
		/** @var Farm $oFarm */
		$oFarm = $this->createObject('Farm', array(
			'name' => 'Farm_'.$iNum,
			'org_id' => $this->getTestOrgId(),
			'redundancy' => $sRedundancy,
		));
		$this->debug("Created {$oFarm->GetName()} ({$oFarm->GetKey()}) redundancy $sRedundancy");

		return $oFarm;
	}

	/**
	 * Create a VM in database
	 *
	 * @param int $iNum
	 * @param VirtualHost $oVirtualHost
	 *
	 * @return VirtualMachine
	 * @throws Exception
	 */
	protected function CreateVirtualMachine($iNum, $oVirtualHost)
	{
		/** @var VirtualMachine $oVirtualMachine */
		$oVirtualMachine = $this->createObject('VirtualMachine', array(
			'name' => 'VirtualMachine_'.$iNum,
			'org_id' => $this->getTestOrgId(),
			'virtualhost_id' => $oVirtualHost->GetKey(),
		));
		$this->debug("Created {$oVirtualMachine->GetName()} ({$oVirtualMachine->GetKey()}) on {$oVirtualHost->GetName()}");

		return $oVirtualMachine;
	}

	protected function CreateObjectWithTagSet()
	{
		$oFaqCategory = MetaModel::GetObject('FAQCategory', 1, false);
		if (empty($oFaqCategory))
		{
			$oFaqCategory = $this->createObject('FAQCategory', array(
				'name' => 'FAQCategory_phpunit',
			));
		}

		/** @var \FAQ $oFaq */
		$oFaq = $this->createObject('FAQ', array(
			'category_id' => $oFaqCategory->GetKey(),
			'title' => 'FAQ_phpunit',
		));
		$this->debug("Created {$oFaq->GetName()}");

		return $oFaq;
	}


	/**
	 * Add a link between a contact and a CI.
	 * The database is not updated.
	 *
	 * @param Contact $oContact
	 * @param FunctionalCI $oCI
	 *
	 * @return lnkContactToFunctionalCI
	 * @throws Exception
	 */
	protected function AddContactToCI($oContact, $oCI)
	{
		$oNewLink = new lnkContactToFunctionalCI();
		$oNewLink->Set('contact_id', $oContact->GetKey());
		$oContacts = $oCI->Get('contacts_list');
		$oContacts->AddItem($oNewLink);
		$oCI->Set('contacts_list', $oContacts);

		$this->debug("Added {$oContact->GetName()} to {$oCI->GetName()}");

		return $oNewLink;
	}

	/**
	 * Remove a link between a contact and a CI.
	 * The database is not updated.
	 *
	 * @param Contact $oContact
	 * @param FunctionalCI $oCI
	 *
	 * @throws Exception
	 */
	protected function RemoveContactFromCI($oContact, $oCI)
	{
		$oContacts = $oCI->Get('contacts_list');
		foreach ($oContacts as $oLnk)
		{
			if ($oLnk->Get('contact_id') == $oContact->GetKey())
			{
				$oContacts->RemoveItem($oLnk->GetKey());
				$oCI->Set('contacts_list', $oContacts);
				$this->debug("Removed {$oContact->GetName()} from {$oCI->Get('name')}");

				return;
			}
		}
	}

	/**
	 * Add a link between a CI and a Ticket.
	 * The database is not updated.
	 *
	 * @param FunctionalCI $oCI
	 * @param Ticket $oTicket
	 * @param string $sImpactCode
	 *
	 * @return array
	 * @throws Exception
	 */
	protected function AddCIToTicket($oCI, $oTicket, $sImpactCode)
	{
		$oNewLink = new lnkFunctionalCIToTicket();
		$oNewLink->Set('functionalci_id', $oCI->GetKey());
		$oNewLink->Set('impact_code', $sImpactCode);
		$oCIs = $oTicket->Get('functionalcis_list');
		$oCIs->AddItem($oNewLink);
		$oTicket->Set('functionalcis_list', $oCIs);

		$this->debug("Added {$oCI->GetName()} to {$oTicket->Get('ref')} with {$sImpactCode}");

		return array($oCI->GetKey() => $sImpactCode);
	}

	/**
	 * Remove a link between a CI and a Ticket.
	 * The database is not updated.
	 *
	 * @param FunctionalCI $oCI
	 * @param Ticket $oTicket
	 *
	 * @throws Exception
	 */
	protected function RemoveCIFromTicket($oCI, $oTicket)
	{
		$oCIs = $oTicket->Get('functionalcis_list');
		foreach ($oCIs as $oLnk)
		{
			if ($oLnk->Get('functionalci_id') == $oCI->GetKey())
			{
				$sImpactCode = $oLnk->Get('impact_code');
				$oCIs->RemoveItem($oLnk->GetKey());
				$oTicket->Set('functionalcis_list', $oCIs);
				$this->debug("Removed {$oCI->GetName()} from {$oTicket->Get('ref')} ({$sImpactCode})");

				return;
			}
		}
		$this->debug("ERROR: {$oCI->GetName()} not attached to {$oTicket->Get('ref')}");
		$this->assertTrue(false);
	}

	/**
	 * Add a link between a Contact and a Ticket.
	 * The database is not updated.
	 *
	 * @param Contact $oContact
	 * @param Ticket $oTicket
	 * @param string $sRoleCode
	 * @param array $aParams
	 *
	 * @return array
	 * @throws Exception
	 */
	protected function AddContactToTicket($oContact, $oTicket, $sRoleCode, $aParams = array())
	{
		$oNewLink = new lnkContactToTicket();
		$oNewLink->Set('contact_id', $oContact->GetKey());
		$oNewLink->Set('role_code', $sRoleCode);
		foreach ($aParams as $sAttCode => $oValue)
		{
			$oNewLink->Set($sAttCode, $oValue);
		}
		$oCIs = $oTicket->Get('contacts_list');
		$oCIs->AddItem($oNewLink);
		$oTicket->Set('contacts_list', $oCIs);

		$this->debug("Added {$oContact->GetName()} to {$oTicket->Get('ref')} with {$sRoleCode}");

		return array($oContact->GetKey() => $sRoleCode);
	}

	/**
	 * Remove a link between a Contact and a Ticket.
	 * The database is not updated.
	 *
	 * @param Contact $oContact
	 * @param Ticket $oTicket
	 *
	 * @throws Exception
	 */
	protected function RemoveContactFromTicket($oContact, $oTicket)
	{
		$oContacts = $oTicket->Get('contacts_list');
		foreach ($oContacts as $oLnk)
		{
			if ($oLnk->Get('contact_id') == $oContact->GetKey())
			{
				$sRoleCode = $oLnk->Get('role_code');
				$oContacts->RemoveItem($oLnk->GetKey());
				$oTicket->Set('contacts_list', $oContacts);
				$this->debug("Removed {$oContact->GetName()} from {$oTicket->Get('ref')} ({$sRoleCode})");

				return;
			}
		}
	}

	/**
	 * Reload a Ticket from the database.
	 *
	 * @param DBObject $oObject
	 *
	 * @return \DBObject|null
	 * @throws ArchivedObjectException
	 * @throws Exception
	 */
	protected function ReloadObject(&$oObject)
	{
		$oObject = MetaModel::GetObject(get_class($oObject), $oObject->GetKey());

		return $oObject;
	}

	/**
	 * Check or Display the CI list of a Ticket.
	 *
	 * @param Ticket $oTicket
	 * @param array $aWaitedCIList { iCIId => sImpactCode }
	 *
	 * @throws Exception
	 */
	protected function CheckFunctionalCIList($oTicket, $aWaitedCIList = array())
	{
		$this->debug("\nResulting functionalcis_list {$oTicket->Get('ref')} ({$oTicket->Get('functionalcis_list')->Count()}):");
		foreach ($oTicket->Get('functionalcis_list') as $oLnk)
		{
			$this->debug($oLnk->Get('functionalci_name')." => ".$oLnk->Get('impact_code')."");
			$iId = $oLnk->Get('functionalci_id');
			if (!empty($aWaitedCIList))
			{
				$this->assertArrayHasKey($iId, $aWaitedCIList);
				$this->assertEquals($aWaitedCIList[$iId], $oLnk->Get('impact_code'));
			}
		}
	}

	/**
	 * Check or Display the Contact list of a DBObject (having a contacts_list).
	 * Can also control other attributes of the link.
	 *
	 * @param Ticket $oTicket
	 * @param array $aWaitedContactList { iContactId => array(attcode => value) }
	 *
	 * @throws Exception
	 */
	protected function CheckContactList($oTicket, $aWaitedContactList = array())
	{
		$this->debug("\nResulting contacts_list {$oTicket->Get('ref')} ({$oTicket->Get('contacts_list')->Count()}):");
		foreach ($oTicket->Get('contacts_list') as $oLnk)
		{
			$this->debug($oLnk->Get('contact_id_friendlyname')." => ".$oLnk->Get('role_code'));
			$iId = $oLnk->Get('contact_id');
			if (!empty($aWaitedContactList))
			{
				$this->assertArrayHasKey($iId, $aWaitedContactList);
				foreach ($aWaitedContactList[$iId] as $sAttCode => $oValue)
				{
					if (MetaModel::IsValidAttCode(get_class($oTicket), $sAttCode))
					{
						$this->assertEquals($oValue, $oLnk->Get($sAttCode));
					}
				}
			}
		}
	}

	protected function CreateTestOrganization()
	{
		// Create a specific organization for the tests
		$oOrg = $this->CreateOrganization('UnitTestOrganization');
		$this->iTestOrgId = $oOrg->GetKey();
	}

	/**
	 *  Assert that a series of operations will trigger a given number of MySL queries
	 *
	 * @param $iExpectedCount  Number of MySQL queries that should be executed
	 * @param callable $oFunction Operations to perform
	 *
	 * @throws \MySQLException
	 * @throws \MySQLQueryHasNoResultException
	 */
	protected static function assertDBQueryCount($iExpectedCount, callable $oFunction)
	{
		$iInitialCount = (int) CMDBSource::QueryToScalar("SHOW SESSION STATUS LIKE 'Queries'", 1);
		$oFunction();
		$iFinalCount = (int) CMDBSource::QueryToScalar("SHOW SESSION STATUS LIKE 'Queries'", 1);
		$iCount = $iFinalCount - 1 - $iInitialCount;
		if ($iCount != $iExpectedCount)
		{
			static::fail("Expected $iExpectedCount queries. $iCount have been executed.");
		}
		else
		{
			// Otherwise PHP Unit will consider that no assertion has been made
			static::assertTrue(true);
		}
	}
}

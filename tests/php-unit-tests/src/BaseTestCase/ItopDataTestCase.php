<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

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
use User;
use utils;
use VirtualHost;
use VirtualMachine;


/** @see \Combodo\iTop\Test\UnitTest\ItopDataTestCase::CreateObjectWithTagSet() */
define('TAG_CLASS', 'FAQ');
define('TAG_ATTCODE', 'domains');

/**
 * Class ItopDataTestCase
 *
 * Helper class to extend for tests needing access to iTop's metamodel
 *
 * **⚠ Warning** Each class extending this one needs to add the following annotations :
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 *
 * @since 2.7.7 3.0.1 3.1.0 N°4624 processIsolation is disabled by default and must be enabled in each test needing it (basically all tests using
 * iTop datamodel)
 */
abstract class ItopDataTestCase extends ItopTestCase
{
	private $iTestOrgId;
	// For cleanup
	private $aCreatedObjects = array();

	/**
	 * @var string Default environment to use for test cases
	 */
	const DEFAULT_TEST_ENVIRONMENT = 'production';
	const USE_TRANSACTION = true;
	const CREATE_TEST_ORG = false;

	/**
	 * @throws Exception
	 */
	protected function setUp(): void
	{
		parent::setUp();

		$this->PrepareEnvironment();

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
	protected function tearDown(): void
	{
		if (static::USE_TRANSACTION) {
			$this->debug("ROLLBACK !!!");
			CMDBSource::Query('ROLLBACK');
		} else {
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
				catch (Exception $e) {
					$this->debug("Error when removing created objects: $sClass::$iKey. Exception message: ".$e->getMessage());
				}
			}
		}

		parent::tearDown();
	}

	/**
	 * @inheritDoc
	 */
	protected function LoadRequiredItopFiles(): void
	{
		parent::LoadRequiredItopFiles();

		$this->RequireOnceItopFile('application/utils.inc.php');
	}

	/**
	 * @return string Environment the test will run in
	 * @since 2.7.9 3.0.4 3.1.0
	 */
	protected function GetTestEnvironment(): string
	{
		return self::DEFAULT_TEST_ENVIRONMENT;
	}

	/**
	 * @return string Absolute path of the configuration file used for the test
	 * @since 2.7.9 3.0.4 3.1.0
	 */
	protected function GetConfigFileAbsPath(): string
	{
		return utils::GetConfigFilePath($this->GetTestEnvironment());
	}

	/**
	 * Prepare the iTop environment for test to run
	 *
	 * @return void
	 * @throws \CoreException
	 * @throws \DictExceptionUnknownLanguage
	 * @throws \MySQLException
	 * @since 2.7.9 3.0.4 3.1.0
	 */
	protected function PrepareEnvironment(): void
	{
		$sEnv = $this->GetTestEnvironment();
		$sConfigFile = $this->GetConfigFileAbsPath();

		// Start MetaModel for the prepared environment
		MetaModel::Startup($sConfigFile, false /* $bModelOnly */, true /* $bAllowCache */, false /* $bTraceSourceFiles */, $sEnv);
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
	 * @param array $aUserRequestCustomParams set fields values for the UserRequest : attcode as key, att value as value.
	 *          If the attcode is already present in the default values, custom value will be kept (see array_merge documentation)
	 *
	 * @return \UserRequest
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 *
	 * @link https://www.php.net/manual/en/function.array-merge.php array_merge PHP function documentation
	 *
	 * @uses \array_merge()
	 * @uses createObject
	 */
	protected function CreateUserRequest($iNum, $aUserRequestCustomParams = []) {
		$aUserRequestDefaultParams = [
			'ref' => 'Ticket_'.$iNum,
			'title' => 'BUG 1161_'.$iNum,
			//'request_type' => 'incident',
			'description' => 'Add aggregate functions',
			'org_id' => $this->getTestOrgId(),
		];

		$aUserRequestParams = array_merge($aUserRequestDefaultParams, $aUserRequestCustomParams);

		/** @var \UserRequest $oTicket */
		$oTicket = $this->createObject('UserRequest', $aUserRequestParams);
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
	protected function CreateUser($sLogin, $iProfileId, $sPassword=null, $iContactid=2)
	{
		if (empty($sPassword)){
			$sPassword = $sLogin;
		}

		$oUserProfile = new URP_UserProfile();
		$oUserProfile->Set('profileid', $iProfileId);
		$oUserProfile->Set('reason', 'UNIT Tests');
		$oSet = DBObjectSet::FromObject($oUserProfile);
		$oUser = $this->createObject('UserLocal', array(
			'contactid' => $iContactid,
			'login' => $sLogin,
			'password' => $sPassword,
			'language' => 'EN US',
			'profile_list' => $oSet,
		));
		$this->debug("Created {$oUser->GetName()} ({$oUser->GetKey()})");

		return $oUser;
	}

	/**
	 * @param string $sLogin
	 * @param int $iProfileId
	 *
	 * @return \UserLocal
	 * @throws Exception
	 */
	protected function CreateContactlessUser($sLogin, $iProfileId, $sPassword = null)
	{
		if (empty($sPassword)) {
			$sPassword = $sLogin;
		}

		$oUserProfile = new URP_UserProfile();
		$oUserProfile->Set('profileid', $iProfileId);
		$oUserProfile->Set('reason', 'UNIT Tests');
		$oSet = DBObjectSet::FromObject($oUserProfile);
		/** @var \UserLocal $oUser */
		$oUser = $this->createObject('UserLocal', array(
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
		$oUser = $this->updateObject(User::class, $oUser->GetKey(), array(
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
	protected function AddCIToTicket($oCI, $oTicket, $sImpactCode = 'manual')
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
				$this->assertTrue(array_key_exists($iId, $aWaitedCIList));
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
				$this->assertTrue(array_key_exists($iId, $aWaitedContactList));
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
		return $oOrg;
	}


}

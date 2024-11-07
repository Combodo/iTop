<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
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
use CMDBObject;
use CMDBSource;
use Combodo\iTop\Service\Events\EventService;
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
use PluginManager;
use Server;
use TagSetFieldData;
use Ticket;
use URP_UserProfile;
use User;
use UserRequest;
use UserRights;
use utils;
use VirtualHost;
use VirtualMachine;
use XMLDataLoader;


/** @see \Combodo\iTop\Test\UnitTest\ItopDataTestCase::CreateObjectWithTagSet() */
define('TAG_CLASS', 'FAQ');
define('TAG_ATTCODE', 'domains');

/**
 * Class ItopDataTestCase
 *
 * Helper class to extend for tests needing access to iTop's metamodel
 *
 * @since 2.7.7 3.0.1 3.1.0 N°4624 processIsolation is disabled by default and must be enabled in each test needing it (basically all tests using
 * iTop datamodel)
 * @since 3.0.4 3.1.1 3.2.0 N°6658 move some setUp/tearDown code to the corresponding methods *BeforeClass to speed up tests process time.
 */
abstract class ItopDataTestCase extends ItopTestCase
{
	private $iTestOrgId;

	// For cleanup
	private $aCreatedObjects = [];
	private $aEventListeners = [];

	/**
	 * @var bool When testing with silo, there are some cache we need to update on tearDown. Doing it all the time will cost too much, so it's opt-in !
	 * @see tearDown
	 * @see ResetMetaModelQueyCacheGetObject
	 */
	protected $bIsUsingSilo = false;

	/**
	 * @var string Default environment to use for test cases
	 */
	const DEFAULT_TEST_ENVIRONMENT = 'production';
	const USE_TRANSACTION = true;
	const CREATE_TEST_ORG = false;

	protected static $aURP_Profiles = [
		'Administrator'         => 1,
		'Portal user'           => 2,
		'Configuration Manager' => 3,
		'Service Desk Agent'    => 4,
		'Support Agent'         => 5,
		'Problem Manager'       => 6,
		'Change Implementor'    => 7,
		'Change Supervisor'     => 8,
		'Change Approver'       => 9,
		'Service Manager'       => 10,
		'Document author'       => 11,
		'Portal power user'     => 12,
		'REST Services User'    => 1024,
	];

	/**
	 * This method is called before the first test of this test class is run (in the current process).
	 */
	public static function setUpBeforeClass(): void
	{
		parent::setUpBeforeClass();
	}

	/**
	 * This method is called after the last test of this test class is run (in the current process).
	 */
	public static function tearDownAfterClass(): void
	{
		\UserRights::FlushPrivileges();
		parent::tearDownAfterClass();
	}

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
			// Create a specific organization for the tests
			$this->iTestOrgId = $this->GivenObjectInDB('Organization', [
			    'name' => 'UnitTestOrganization',
			]);
		}

		$oConfig = MetaModel::GetConfig();
		if ($oConfig->GetEncryptionLibrary() != DEFAULT_ENCRYPTION_LIB
			&& $oConfig->GetEncryptionKey() == DEFAULT_ENCRYPTION_KEY) {
			// Config file is corrupted, let's fix it
			$oConfig->SetEncryptionKey("6eb9d9afa3ee0fbcebe622a33bf57aaeafb7c37998fd24c403c2522c2d60117f");
		}
		// Align the PHP executable with the one used to launch the whole test process
		$oConfig->Set('php_path', PHP_BINARY);
	}

	/**
	 * @throws Exception
	 */
	protected function tearDown(): void
	{
		static::SetNonPublicStaticProperty(\cmdbAbstractObject::class, 'aObjectsAwaitingEventDbLinksChanged', []);
		\cmdbAbstractObject::SetEventDBLinksChangedBlocked(false);

		// disconnection before object deletions. make sure to have enough rights to proceed (especially with user object)
		if (UserRights::IsLoggedIn()) {
			UserRights::Logoff();
		}
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
		// As soon as a rollback has been performed, each object memoized should be discarded
		CMDBObject::SetCurrentChange(null);

		$this->SetNonPublicStaticProperty(UserRights::class, 'm_aCacheUsers', []); // we could have cached rollbacked instances
		if ($this->bIsUsingSilo) {
			$this->ResetMetaModelQueyCacheGetObject();
		}

		foreach ($this->aEventListeners as $sListenerId) {
			EventService::UnRegisterListener($sListenerId);
		}

		CMDBObject::SetCurrentChange(null);

		parent::tearDown();
	}

	/**
	 * Helper to reset the metamodel cache : for a class and a key it will contain the SQL query, that could include silo filter
	 */
	protected function ResetMetaModelQueyCacheGetObject() {
		$this->SetNonPublicStaticProperty(MetaModel::class, 'aQueryCacheGetObject', []);
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
	/// Facades for environment settings
	/////////////////////////////////////////////////////////////////////////////
	/**
	 * Facade for EventService::RegisterListener
	 *
	 * @param string $sEvent
	 * @param callable $callback
	 * @param $sEventSource
	 * @param array $aCallbackData
	 * @param $context
	 * @param float $fPriority
	 * @param $sModuleId
	 *
	 * @return string
	 */
	public function EventService_RegisterListener(string $sEvent, callable $callback, $sEventSource = null, array $aCallbackData = [], $context = null, float $fPriority = 0.0, $sModuleId = ''): string
	{
		$ret = EventService::RegisterListener($sEvent, $callback, $sEventSource, $aCallbackData, $context, $fPriority, $sModuleId);
		if (false !== $ret) {
			$this->aEventListeners[] = $ret;
		}
		return $ret;
	}

	/////////////////////////////////////////////////////////////////////////////
	/// MetaModel Utilities
	/////////////////////////////////////////////////////////////////////////////

	/**
	 * Allow test iApplicationObjectExtension objects to be added to the list of plugins without setup
	 * just require the class file containing the object implementing iApplicationObjectExtension before calling ResetApplicationObjectExtensions()
	 *
	 * @return void
	 */
	protected function ResetApplicationObjectExtensions()
	{
		// Add ObjectModifyExtension to the plugin list
		$this->InvokeNonPublicStaticMethod(MetaModel::class, 'InitExtensions', []);
		// Instantiate the new object
		$this->InvokeNonPublicStaticMethod(PluginManager::class, 'ResetPlugins', []);
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
		foreach ($aRes as $aRow) {
			$this->debug($aRow);
			$iKey = $aRow['id'];
			if (!empty($iKey)) {
				$oObject = MetaModel::GetObject($sClass, $iKey);
				$oObject->DBDelete();
			}
		}
	}

	protected function GetUserRequestParams($iNum) {
		return [
			'ref'         => 'Ticket_'.$iNum,
			'title'       => 'BUG 1161_'.$iNum,
			//'request_type' => 'incident',
			'description' => 'Add aggregate functions',
			'org_id'      => $this->getTestOrgId(),
		];
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
		$aUserRequestDefaultParams = $this->GetUserRequestParams($iNum);

		$aUserRequestParams = array_merge($aUserRequestDefaultParams, $aUserRequestCustomParams);

		/** @var \UserRequest $oTicket */
		$oTicket = $this->createObject(UserRequest::class, $aUserRequestParams);
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
	 * @return \UserLocal
	 * @throws Exception
	 */
	protected function CreateUser($sLogin, $iProfileId, $sPassword=null, $iContactid=2)
	{
		$oUser = $this->CreateContactlessUser($sLogin, $iProfileId, $sPassword);
		$oUser->Set('contactid', $iContactid);
		$oUser->DBWrite();
		return $oUser;
	}

	/**
	 * @param string $sLogin
	 * @param int $iProfileId
	 *
	 * @return \UserLocal
	 * @throws Exception
	 */
	protected function CreateContactlessUser($sLogin, $iProfileId, $sPassword=null)
	{
		if (empty($sPassword)){
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
		/** @var \ormLinkSet $oSet */
		$oSet = $oUser->Get('profile_list');
		$oSet->AddItem($oUserProfile);
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

	protected function CreateTestOrganization()
	{
		// Create a specific organization for the tests
		$oOrg = $this->CreateOrganization('UnitTestOrganization');
		$this->iTestOrgId = $oOrg->GetKey();
		return $oOrg;
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
	protected function assertDBQueryCount($iExpectedCount, callable $oFunction)
	{
		$iInitialCount = (int) CMDBSource::QueryToScalar("SHOW SESSION STATUS LIKE 'Queries'", 1);
		$oFunction();
		$iFinalCount = (int) CMDBSource::QueryToScalar("SHOW SESSION STATUS LIKE 'Queries'", 1);
		$iCount = $iFinalCount - 1 - $iInitialCount;
		if ($iCount != $iExpectedCount)
		{
			$this->fail("Expected $iExpectedCount queries. $iCount have been executed.");
		}
		else
		{
			// Otherwise, PHP Unit will consider that no assertion has been made
			$this->assertTrue(true);
		}
	}

	/**
	 * @since 3.0.4 3.1.1 3.2.0 N°6658 method creation
	 */
	protected function assertDBChangeOpCount(string $sClass, $iId, int $iExpectedCount)
	{
		$oSearch = new \DBObjectSearch('CMDBChangeOp');
		$oSearch->AddCondition('objclass', $sClass);
		$oSearch->AddCondition('objkey', $iId);
		$oSearch->AllowAllData();
		$oSet = new \DBObjectSet($oSearch);
		$iCount = $oSet->Count();
		$this->assertEquals($iExpectedCount, $iCount, "Found $iCount changes for object $sClass::$iId");
	}

	/**
	 * Import a set of XML files describing a consistent set of iTop objects
	 * @param string[] $aFiles
	 * @param boolean $bSearch If true, a search will be performed on each object (based on its reconciliation keys)
	 *                         before trying to import it (existing objects will be updated)
	 * @return int Number of objects created
	 */
	protected function CreateFromXMLFiles($aFiles, $bSearch = false)
	{
		$oLoader = new XMLDataLoader();
		$oLoader->StartSession(CMDBObject::GetCurrentChange());
		foreach($aFiles as $sFilePath)
		{
			$oLoader->LoadFile($sFilePath, false, $bSearch);
		}
		$oLoader->EndSession();

		return $oLoader->GetCountCreated();
	}

	/**
	 * Import a consistent set of iTop objects from the specified XML text string
	 * @param string $sXmlDataset
	 * @param boolean $bSearch If true, a search will be performed on each object (based on its reconciliation keys)
	 *                         before trying to import it (existing objects will be updated)
	 * @return int The number of objects created
	 */
	protected function CreateFromXMLString($sXmlDataset, $bSearch = false)
	{
		$sTmpFileName = tempnam(sys_get_temp_dir(), 'xml');
		file_put_contents($sTmpFileName, $sXmlDataset);

		$ret = $this->CreateFromXMLFiles([$sTmpFileName], $bSearch);

		unlink($sTmpFileName);

		return $ret;
	}


	protected function DBInsertSingleTable($sTableClass, $aValues, $iKey = 0)
	{
		$sTable = MetaModel::DBGetTable($sTableClass);
		// Abstract classes or classes having no specific attribute do not have an associated table
		if ($sTable == '') {
			return false;
		}

		// fields in first array, values in the second
		$aFieldsToWrite = array();
		$aValuesToWrite = array();

		if (!empty($iKey) && ($iKey >= 0)) {
			// Add it to the list of fields to write
			$aFieldsToWrite[] = '`'.MetaModel::DBGetKey($sTableClass).'`';
			$aValuesToWrite[] = CMDBSource::Quote($iKey);
		}

		$aHierarchicalKeys = array();

		foreach (MetaModel::ListAttributeDefs($sTableClass) as $sAttCode => $oAttDef) {
			// Skip this attribute if not defined in this table
			if ((!MetaModel::IsAttributeOrigin($sTableClass, $sAttCode) && !$oAttDef->CopyOnAllTables())
				|| $oAttDef->IsExternalField()) {
				continue;
			}
			if ($oAttDef->IsWritable() && !$oAttDef->IsNullAllowed() && !array_key_exists($sAttCode, $aValues) && $oAttDef->IsNull($oAttDef->GetDefaultValue())) {
				throw new Exception("GivenObjectInDB('$sTableClass'), mandatory attribute '$sAttCode' is missing");
			}
			$currentValue = array_key_exists($sAttCode, $aValues) ? $aValues[$sAttCode] : $oAttDef->GetDefaultValue();
			$aAttColumns = $oAttDef->GetSQLValues($currentValue);
			foreach ($aAttColumns as $sColumn => $sValue) {
				$aFieldsToWrite[] = "`$sColumn`";
				$aValuesToWrite[] = CMDBSource::Quote($sValue);
			}
			if ($oAttDef->IsHierarchicalKey()) {
				$aHierarchicalKeys[$sAttCode] = $oAttDef;
			}
		}

		if (count($aValuesToWrite) == 0) {
			return false;
		}

		if (count($aHierarchicalKeys) > 0) {
			foreach ($aHierarchicalKeys as $sAttCode => $oAttDef) {
				$currentValue = isset($aValues[$sAttCode]) ? $aValues[$sAttCode] : $oAttDef->GetDefaultValue();
				$aHKValues = MetaModel::HKInsertChildUnder($currentValue, $oAttDef, $sTable);
				$aFieldsToWrite[] = '`'.$oAttDef->GetSQLRight().'`';
				$aValuesToWrite[] = $aHKValues[$oAttDef->GetSQLRight()];
				$aFieldsToWrite[] = '`'.$oAttDef->GetSQLLeft().'`';
				$aValuesToWrite[] = $aHKValues[$oAttDef->GetSQLLeft()];
			}
		}
		$sInsertSQL = "INSERT INTO `$sTable` (".join(',', $aFieldsToWrite).') VALUES ('.join(', ', $aValuesToWrite).')';
		$iNewKey = CMDBSource::InsertInto($sInsertSQL);
		// Note that it is possible to have a key defined here, and the autoincrement expected, this is acceptable in a non root class
		if (empty($iKey)) {
			// Take the autonumber
			$iKey = "$iNewKey";
		}

		return $iKey;
	}

	/**
	 * @param string $sClass
	 * @param array $aParams
	 *
	 * @return DBObject
	 * @throws Exception
	 */
	protected function GivenObject(string $sClass, array $aParams): DBObject
	{
		$oMyObj = MetaModel::NewObject($sClass);
		foreach ($aParams as $sAttCode => $oValue)
		{
			$oMyObj->Set($sAttCode, $oValue);
		}

		return $oMyObj;
	}

	/**
	 * @param string $sClass
	 * @param array $aValues
	 *
	 * @return DBObject
	 * @throws Exception
	 */
	protected function GivenObjectInDB($sClass, $aValues)
	{
		// Check and complete the values
		foreach ($aValues as $sAttCode => $oValue) {
			if (MetaModel::IsValidAttCode($sClass, $sAttCode) === false) {
				throw new \Exception("GivenObjectInDB('$sClass'), invalid attribute code '$sAttCode'");
			}
		}
		$sOrgIdAttCode = $sClass::MapContextParam('org_id');
		if ($sOrgIdAttCode !== null) {
			if (!isset($aValues[$sOrgIdAttCode])) {
				$aValues[$sOrgIdAttCode] = $this->getTestOrgId();
			}
		}
		$aValues['finalclass'] = $sClass;

		$sRootClass = MetaModel::GetRootClass($sClass);

		// First query built upon on the root class, because the ID must be created first
		$iKey = $this->DBInsertSingleTable($sRootClass, $aValues);

		// Then do the leaf class, if different from the root class
		if ($sClass != $sRootClass) {
			$this->DBInsertSingleTable($sClass, $aValues, $iKey);
		}

		// Then do the other classes
		foreach (MetaModel::EnumParentClasses($sClass) as $sParentClass) {
			if ($sParentClass == $sRootClass) {
				continue;
			}
			$this->DBInsertSingleTable($sParentClass, $aValues, $iKey);
		}

		// Then cope with the links
		foreach (MetaModel::ListAttributeDefs($sClass) as $sAttCode => $oAttDef) {
			if (!$oAttDef->IsLinkSet()) {
				continue;
			}
			if (!isset($aValues[$sAttCode])) {
				continue;
			}

			$sLinkClass = $oAttDef->GetLinkedClass();
			$sExtKeyToMe = $oAttDef->GetExtKeyToMe();

			if ($aValues[$sAttCode] instanceof \DBObjectSet) {
				$oSet = $aValues[$sAttCode];
				$oSet->Rewind();
				while ($oLnk = $oSet->Fetch()) {
					$aLnkValues = [];
					foreach (MetaModel::ListAttributeDefs($sLinkClass) as $sLnkAttCode => $oLnkAttDef) {
						if ($sLnkAttCode == $sExtKeyToMe) {
							$aLnkValues[$sLnkAttCode] = $iKey;
						} else {
							$aLnkValues[$sLnkAttCode] = $oLnk->Get($sLnkAttCode);
						}
					}
					$this->GivenObjectInDB($sLinkClass, $aLnkValues);
				}
			} elseif (is_array($aValues[$sAttCode])) {
				foreach ($aValues[$sAttCode] as $sLnkValues) {
					$aLnkKeyValuePairs = explode(';', $sLnkValues);
					$aLnkValues = [];
					foreach ($aLnkKeyValuePairs as $sLnkKeyValue) {
						$iSep = strpos($sLnkKeyValue, ':');
						if ($iSep === false) {
							throw new \Exception("GivenObjectInDB($sClass), unexpected value format for $sAttCode, missing ':' in '$sLnkKeyValue'");
						}
						$sLnkAttCode = substr($sLnkKeyValue, 0, $iSep);
						$sLnkValue = substr($sLnkKeyValue, $iSep + 1);
						$aLnkValues[$sLnkAttCode] = $sLnkValue;
					}
					foreach (MetaModel::ListAttributeDefs($sLinkClass) as $sLnkAttCode => $oLnkAttDef) {
						if ($sLnkAttCode == $sExtKeyToMe) {
							$aLnkValues[$sLnkAttCode] = $iKey;
						}
					}
					$this->GivenObjectInDB($sLinkClass, $aLnkValues);
				}
			} else {
				throw new \Exception("createObject($sClass), unexpected value type for $sAttCode");
			}
		}

		return $iKey;
	}

	/**
	 * Create a Farm in database
	 *
	 * @param int $iNum
	 * @param string $sRedundancy
	 *
	 * @throws Exception
	 */
	protected function GivenFarmInDB($iNum, $sRedundancy = '1'): string
	{
		$sName = 'Farm_'.$iNum;
		$sId = $this->GivenObjectInDB('Farm', [
			'name'       => $sName,
			'org_id'     => $this->getTestOrgId(),
			'redundancy' => $sRedundancy,
		]);
		$this->debug("Created $sName ($sId) redundancy $sRedundancy");

		return $sId;
	}

	/**
	 * Create an Application Solution in database
	 *
	 * @param int $iNum
	 * @param string $sRedundancy
	 *
	 * @throws Exception
	 */
	protected function GivenApplicationSolutionInDB($iNum, $sRedundancy = '1'): string
	{
		$sName = 'ApplicationSolution_'.$iNum;
		$sId = $this->GivenObjectInDB('ApplicationSolution', [
			'name'       => $sName,
			'org_id'     => $this->getTestOrgId(),
			'redundancy' => $sRedundancy,
		]);
		$this->debug("Created $sName ($sId) redundancy $sRedundancy");

		return $sId;
	}

	protected function GivenServerInDB($iNum, $iRackUnit = null): string
	{
		$sName = 'Server_'.$iNum;
		$sId = $this->GivenObjectInDB('Server', [
			'name'   => $sName,
			'org_id' => $this->getTestOrgId(),
			'nb_u'   => $iRackUnit,
		]);
		$this->debug("Created $sName ($sId)");

		return $sId;
	}

	protected function GivenHypervisorInDB($iNum, $sServerId, $sFarmId = 0): string
	{
		$sName = 'Hypervisor_'.$iNum;
		$sId = $this->GivenObjectInDB('Hypervisor', [
			'name'      => $sName,
			'org_id'    => $this->getTestOrgId(),
			'server_id' => $sServerId,
			'farm_id'   => $sFarmId,
		]);
		if ($sFarmId === 0) {
			$this->debug("Created $sName ($sId) on server $sServerId");
		} else {
			$this->debug("Created $sName ($sId) on server $sServerId part of farm $sFarmId");
		}

		return $sId;
	}

	protected function GivenPersonInDB($iNum, $iOrgId = 0): string
	{
		$sName = 'Person_'.$iNum;
		$sId = $this->GivenObjectInDB('Person', [
			'name'       => $sName,
			'first_name' => 'Test',
			'org_id'     => ($iOrgId == 0 ? $this->getTestOrgId() : $iOrgId),
		]);
		$this->debug("Created $sName ($sId)");

		return $sId;
	}

	protected function GivenLnkContactToFunctionalCIInDB($sContactId, $sCIId): string
	{
		$sClass = 'lnkContactToFunctionalCI';
		$sId = $this->GivenObjectInDB($sClass, [
			'contact_id' => $sContactId,
			'functionalci_id' => $sCIId,
		]);
		$this->debug("Created $sClass::$sId linking contact $sContactId and CI $sCIId");

		return $sId;
	}

	protected function GivenLnkApplicationSolutionToFunctionalCIInDB($sApplicationSolutionId, $sCIId): string
	{
		$sClass = 'lnkApplicationSolutionToFunctionalCI';
		$sId = $this->GivenObjectInDB($sClass, [
			'applicationsolution_id' => $sApplicationSolutionId,
			'functionalci_id' => $sCIId,
		]);
		$this->debug("Created $sClass::$sId linking ApplicationSolution $sApplicationSolutionId and CI $sCIId");

		return $sId;
	}

	protected function GivenVirtualMachineInDB($iNum, $sVirtualHostId): string
	{
		$sName = 'VirtualMachine_'.$iNum;
		$sId = $this->GivenObjectInDB('VirtualMachine', [
			'name'           => $sName,
			'org_id'         => $this->getTestOrgId(),
			'virtualhost_id' => $sVirtualHostId,
		]);
		$this->debug("Created $sName ($sId) on virtual host $sVirtualHostId");

		return $sId;
	}

	protected function GivenTicketObject($iNum): \UserRequest
	{
		$sTitle = 'TICKET_'.$iNum;
		$sRef = 'Ticket_'.$iNum;

		/** @var \UserRequest $oTicket */
		$oTicket = $this->GivenObject('UserRequest', [
			'ref'         => $sRef,
			'title'       => $sTitle,
			'description' => 'Created for unit tests.',
			'org_id'      => $this->getTestOrgId(),
		]);
		$this->debug("Created $sTitle ($sRef)");

		return $oTicket;
	}

	protected function AddLnkFunctionalCIToTicketObject($sCIId, $oTicket, $sImpactCode = 'manual'): array
	{
		$oNewLink = $this->GivenObject('lnkFunctionalCIToTicket', [
			'functionalci_id' => $sCIId,
			'impact_code' => $sImpactCode,
		]);

		$oCIs = $oTicket->Get('functionalcis_list');
		$oCIs->AddItem($oNewLink);
		$oTicket->Set('functionalcis_list', $oCIs);

		$this->debug("Added CI $sCIId to {$oTicket->Get('ref')} with {$sImpactCode}");

		return array($sCIId => $sImpactCode);
	}

	protected function RemoveLnkFunctionalCIToTicketObject($sCIId, $oTicket)
	{
		$oCIs = $oTicket->Get('functionalcis_list');
		foreach ($oCIs as $oLnk)
		{
			if ($oLnk->Get('functionalci_id') == $sCIId)
			{
				$sImpactCode = $oLnk->Get('impact_code');
				$oCIs->RemoveItem($oLnk->GetKey());
				$oTicket->Set('functionalcis_list', $oCIs);
				$this->debug("Removed CI $sCIId from {$oTicket->Get('ref')} ({$sImpactCode})");

				return;
			}
		}
		$this->debug("ERROR: CI $sCIId not attached to {$oTicket->Get('ref')}");
		$this->assertTrue(false);
	}

	protected function AddLnkContactToTicketObject($sContactId, $oTicket, $sRoleCode, $aParams = array()): array
	{
		$aParams['contact_id'] = $sContactId;
		$aParams['role_code'] = $sRoleCode;
		$oNewLink = $this->GivenObject('lnkContactToTicket', $aParams);

		$oCIs = $oTicket->Get('contacts_list');
		$oCIs->AddItem($oNewLink);
		$oTicket->Set('contacts_list', $oCIs);

		$this->debug("Added contact $sContactId to {$oTicket->Get('ref')} with {$sRoleCode}");

		return array($sContactId => $sRoleCode);
	}

	protected function RemoveLnkContactToTicketObject($sContactId, $oTicket)
	{
		$oContacts = $oTicket->Get('contacts_list');
		foreach ($oContacts as $oLnk)
		{
			if ($oLnk->Get('contact_id') == $sContactId)
			{
				$sRoleCode = $oLnk->Get('role_code');
				$oContacts->RemoveItem($oLnk->GetKey());
				$oTicket->Set('contacts_list', $oContacts);
				$this->debug("Removed contact $sContactId from {$oTicket->Get('ref')} ({$sRoleCode})");

				return;
			}
		}
	}

	protected function GivenUserRestrictedToAnOrganizationInDB(int $iOrganization, string $sProfileId): string
	{
		$sLogin = 'demo_test_'.uniqid(__CLASS__, true);
		$iUser = $this->GivenObjectInDB('UserLocal', [
			'login' => $sLogin,
			'password' => 'tagada-Secret,007',
			'language' => 'EN US',
			'profile_list' => [
				'profileid:'.$sProfileId
			],
			'allowed_org_list' => [
				'allowed_org_id:'.$iOrganization
			],
		]);
		return $sLogin;
	}

	/**
	 * @param string $sPassword
	 * @param array $aProfiles Profile names Example: ['Administrator']
	 *
	 * @return string The unique login
	 * @throws \Exception
	 */
	protected function GivenUserInDB(string $sPassword, array $aProfiles): string
	{
		$sLogin = 'demo_test_'.uniqid(__CLASS__, true);

		$aProfileList = array_map(function($sProfileId) {
			return 'profileid:'.self::$aURP_Profiles[$sProfileId];
		}, $aProfiles);

		$iUser = $this->GivenObjectInDB('UserLocal', [
			'login' => $sLogin,
			'password' => $sPassword,
			'language' => 'EN US',
			'profile_list' => $aProfileList,
		]);
		return $sLogin;
	}

	/**
	 * Can be invoked in setUp or any test method to skip the test if the module is not present
	 *
	 * @param string $sModule e.g: itop-hub-connector
	 *
	 * @return void
	 */
	protected function SkipIfModuleNotPresent(string $sModule): void
	{
		if (!file_exists(\utils::GetAbsoluteModulePath($sModule))) {
			self::markTestSkipped("Test skipped: module '$sModule' is not present");
		}
	}
}

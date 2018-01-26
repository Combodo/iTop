<?php
// Copyright (c) 2010-2017 Combodo SARL
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
use Exception;
use Farm;
use FunctionalCI;
use Hypervisor;
use lnkContactToFunctionalCI;
use MetaModel;
use Person;
use PHPUnit\Framework\TestCase;
use Server;
use Ticket;
use URP_UserProfile;
use VirtualHost;
use VirtualMachine;


/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class ItopDataTestCase extends ItopTestCase
{
	protected $testOrgId;

	/**
	 * @throws Exception
	 */
	protected function setUp()
	{
		parent::setUp();
		//require_once(APPROOT.'/application/startup.inc.php');

        require_once(APPROOT.'/core/cmdbobject.class.inc.php');
        require_once(APPROOT.'/application/utils.inc.php');
        require_once(APPROOT.'/core/contexttag.class.inc.php');
        $sEnv = 'production';
        $sConfigFile = APPCONF.$sEnv.'/'.ITOP_CONFIG_FILE;
        MetaModel::Startup($sConfigFile, false /* $bModelOnly */, true /* $bAllowCache */, false /* $bTraceSourceFiles */, $sEnv);

		CMDBSource::Query('START TRANSACTION');

		// Create a specific organization for the tests
		$oOrg = MetaModel::NewObject('Organization', array('name' => 'UnitTestOrganization'));
		$this->testOrgId = $oOrg->DBInsert();
	}

    /**
     * @throws Exception
     */
	protected  function tearDown()
	{
		CMDBSource::Query('ROLLBACK');
	}

	/**
	 * @return mixed
	 */
	public function getTestOrgId()
	{
		return $this->testOrgId;
	}

    /**
     * PHPUnit complains that there is no test... so here is a dummy one.
     */
    public function testDummy()
    {
        $this->assertTrue(true);
    }

    /////////////////////////////////////////////////////////////////////////////
    /// Database Utilities
    /////////////////////////////////////////////////////////////////////////////

    /**
     * @param string $sClass
     * @param array $aParams
     * @return DBObject
     * @throws Exception
     */
    protected static function createObject($sClass, $aParams)
    {
        $oMyObj = MetaModel::NewObject($sClass);
        foreach($aParams as $sAttCode => $oValue)
        {
            $oMyObj->Set($sAttCode, $oValue);
        }
        $oMyObj->DBInsert();
        return $oMyObj;
    }

    /**
     * Create a Ticket in database
     *
     * @param int $iNum
     * @return Ticket
     * @throws Exception
     */
    protected function CreateTicket($iNum)
    {
        /** @var Ticket $oTicket */
        $oTicket = self::createObject('UserRequest', array(
            'ref' => 'Ticket_'.$iNum,
            'title' => 'BUG 789_'.$iNum,
            'request_type' => 'incident',
            'description' => 'method UpdateImpactedItems() reconstruit le lnkContactToTicket donc impossible de rajouter des champs dans cette classe',
            'org_id' => $this->getTestOrgId(),
        ));
        $this->debug("\nCreated {$oTicket->Get('title')} ({$oTicket->Get('ref')})\n");
        return $oTicket;
    }

    /**
     * Create a Server in database
     * @param int $iNum
     * @return Server
     * @throws Exception
     */
    protected function CreateServer($iNum)
    {
        /** @var Server $oServer */
        $oServer = self::createObject('Server', array(
            'name' => 'Server_'.$iNum,
            'org_id' => $this->getTestOrgId(),
        ));
        $this->debug("Created {$oServer->GetName()} ({$oServer->GetKey()})\n");
        return $oServer;
    }

    /**
     * Create a Person in database
     * @param int $iNum
     * @return Person
     * @throws Exception
     */
    protected function CreatePerson($iNum)
    {
        /** @var Person $oPerson */
        $oPerson = self::createObject('Person', array(
            'name' => 'Person_'.$iNum,
            'first_name' => 'Test',
            'org_id' => $this->getTestOrgId(),
        ));
        $this->debug("Created {$oPerson->GetName()} ({$oPerson->GetKey()})\n");
        return $oPerson;
    }

	/**
	 * @param string $sLogin
	 * @param int $iProfileId
	 * @return \DBObject
	 * @throws Exception
	 */
	protected function CreateUser($sLogin, $iProfileId)
    {
	    $oUserProfile = new URP_UserProfile();
	    $oUserProfile->Set('profileid', $iProfileId);
	    $oUserProfile->Set('reason', 'UNIT Tests');
	    $oSet = DBObjectSet::FromObject($oUserProfile);
    	$oUser = self::createObject('UserLocal', array(
		    'contactid' => 2,
		    'login' => $sLogin,
		    'password' => $sLogin,
		    'language' => 'EN US',
		    'profile_list' => $oSet,
	    ));
	    $this->debug("Created {$oUser->GetName()} ({$oUser->GetKey()})\n");
	    return $oUser;
    }


    /**
     * Create a Hypervisor in database
     * @param int $iNum
     * @param Server $oServer
     * @param Farm $oFarm
     * @return Hypervisor
     * @throws Exception
     */
    protected function CreateHypervisor($iNum, $oServer, $oFarm = null)
    {
        /** @var Hypervisor $oHypervisor */
        $oHypervisor = self::createObject('Hypervisor', array(
            'name' => 'Hypervisor_'.$iNum,
            'org_id' => $this->getTestOrgId(),
            'server_id' => $oServer->GetKey(),
            'farm_id' => is_null($oFarm) ? 0 : $oFarm->GetKey(),
        ));
        if (is_null($oFarm))
        {
            $this->debug("Created {$oHypervisor->GetName()} ({$oHypervisor->GetKey()}) on {$oServer->GetName()}\n");
        }
        else
        {
            $this->debug("Created {$oHypervisor->GetName()} ({$oHypervisor->GetKey()}) on {$oServer->GetName()} part of {$oFarm->GetName()}\n");
        }
        return $oHypervisor;
    }

    /**
     * Create a Farm in database
     * @param int $iNum
     * @param string $sRedundancy
     * @return Farm
     * @throws Exception
     */
    protected function CreateFarm($iNum, $sRedundancy = '1')
    {
        /** @var Farm $oFarm */
        $oFarm = self::createObject('Farm', array(
            'name' => 'Farm_'.$iNum,
            'org_id' => $this->getTestOrgId(),
            'redundancy' => $sRedundancy,
        ));
        $this->debug("Created {$oFarm->GetName()} ({$oFarm->GetKey()}) redundancy $sRedundancy\n");
        return $oFarm;
    }

    /**
     * Create a VM in database
     * @param int $iNum
     * @param VirtualHost $oVirtualHost
     * @return VirtualMachine
     * @throws Exception
     */
    protected function CreateVirtualMachine($iNum, $oVirtualHost)
    {
        /** @var VirtualMachine $oVirtualMachine */
        $oVirtualMachine = self::createObject('VirtualMachine', array(
            'name' => 'VirtualMachine_'.$iNum,
            'org_id' => $this->getTestOrgId(),
            'virtualhost_id' => $oVirtualHost->GetKey(),
        ));
        $this->debug("Created {$oVirtualMachine->GetName()} ({$oVirtualMachine->GetKey()}) on {$oVirtualHost->GetName()}\n");
        return $oVirtualMachine;
    }


    /**
     * Add a link between a contact and a CI.
     * The database is not updated.
     *
     * @param Contact $oContact
     * @param FunctionalCI $oCI
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

        $this->debug("Added {$oContact->GetName()} to {$oCI->GetName()}\n");
        return $oNewLink;
    }

    /**
     * Remove a link between a contact and a CI.
     * The database is not updated.
     *
     * @param Contact $oContact
     * @param FunctionalCI $oCI
     * @throws Exception
     */
    protected function RemoveContactFromCI($oContact, $oCI)
    {
        $oContacts = $oCI->Get('contacts_list');
        foreach($oContacts as $oLnk)
        {
            if ($oLnk->Get('contact_id') == $oContact->GetKey())
            {
                $oContacts->RemoveItem($oLnk->GetKey());
                $oCI->Set('contacts_list', $oContacts);
                $this->debug("Removed {$oContact->GetName()} from {$oCI->Get('name')}\n");
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
     * @throws Exception
     */
    protected function AddCIToTicket($oCI, $oTicket, $sImpactCode)
    {
        $oNewLink = new \lnkFunctionalCIToTicket();
        $oNewLink->Set('functionalci_id', $oCI->GetKey());
        $oNewLink->Set('impact_code', $sImpactCode);
        $oCIs = $oTicket->Get('functionalcis_list');
        $oCIs->AddItem($oNewLink);
        $oTicket->Set('functionalcis_list', $oCIs);

        $this->debug("Added {$oCI->GetName()} to {$oTicket->Get('ref')} with {$sImpactCode}\n");
        return array($oCI->GetKey() => $sImpactCode);
    }

    /**
     * Remove a link between a CI and a Ticket.
     * The database is not updated.
     *
     * @param FunctionalCI $oCI
     * @param Ticket $oTicket
     * @throws Exception
     */
    protected function RemoveCIFromTicket($oCI, $oTicket)
    {
        $oCIs = $oTicket->Get('functionalcis_list');
        foreach($oCIs as $oLnk)
        {
            if ($oLnk->Get('functionalci_id') == $oCI->GetKey())
            {
                $sImpactCode = $oLnk->Get('impact_code');
                $oCIs->RemoveItem($oLnk->GetKey());
                $oTicket->Set('functionalcis_list', $oCIs);
                $this->debug("Removed {$oCI->GetName()} from {$oTicket->Get('ref')} ({$sImpactCode})\n");
                return;
            }
        }
        $this->debug("ERROR: {$oCI->GetName()} not attached to {$oTicket->Get('ref')}\n");
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
     * @throws Exception
     */
    protected function AddContactToTicket($oContact, $oTicket, $sRoleCode, $aParams = array())
    {
        $oNewLink = new \lnkContactToTicket();
        $oNewLink->Set('contact_id', $oContact->GetKey());
        $oNewLink->Set('role_code', $sRoleCode);
        foreach($aParams as $sAttCode => $oValue)
        {
            $oNewLink->Set($sAttCode, $oValue);
        }
        $oCIs = $oTicket->Get('contacts_list');
        $oCIs->AddItem($oNewLink);
        $oTicket->Set('contacts_list', $oCIs);

        $this->debug("Added {$oContact->GetName()} to {$oTicket->Get('ref')} with {$sRoleCode}\n");
        return array($oContact->GetKey() => $sRoleCode);
    }

    /**
     * Remove a link between a Contact and a Ticket.
     * The database is not updated.
     *
     * @param Contact $oContact
     * @param Ticket $oTicket
     * @throws Exception
     */
    protected function RemoveContactFromTicket($oContact, $oTicket)
    {
        $oContacts = $oTicket->Get('contacts_list');
        foreach($oContacts as $oLnk)
        {
            if ($oLnk->Get('contact_id') == $oContact->GetKey())
            {
                $sRoleCode = $oLnk->Get('role_code');
                $oContacts->RemoveItem($oLnk->GetKey());
                $oTicket->Set('contacts_list', $oContacts);
                $this->debug("Removed {$oContact->GetName()} from {$oTicket->Get('ref')} ({$sRoleCode})\n");
                return;
            }
        }
    }

    /**
     * Reload a Ticket from the database.
     *
     * @param DBObject $oObject
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
     * @throws Exception
     */
    protected function CheckFunctionalCIList($oTicket, $aWaitedCIList = array())
    {
        $this->debug("\nResulting functionalcis_list {$oTicket->Get('ref')} ({$oTicket->Get('functionalcis_list')->Count()}):\n");
        foreach($oTicket->Get('functionalcis_list') as $oLnk)
        {
            $this->debug($oLnk->Get('functionalci_name')." => ".$oLnk->Get('impact_code')."\n");
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
     * @throws Exception
     */
    protected function CheckContactList($oTicket, $aWaitedContactList = array())
    {
        $this->debug("\nResulting contacts_list {$oTicket->Get('ref')} ({$oTicket->Get('contacts_list')->Count()}):\n");
        foreach($oTicket->Get('contacts_list') as $oLnk)
        {
            $this->debug($oLnk->Get('contact_id_friendlyname')." => ".$oLnk->Get('role_code')."\n");
            $iId = $oLnk->Get('contact_id');
            if (!empty($aWaitedContactList))
            {
                $this->assertTrue(array_key_exists($iId, $aWaitedContactList));
                foreach($aWaitedContactList[$iId] as $sAttCode => $oValue)
                {
                    if (MetaModel::IsValidAttCode(get_class($oTicket), $sAttCode))
                    {
                        $this->assertEquals($oValue, $oLnk->Get($sAttCode));
                    }
                }
            }
        }
    }


}
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

/**
 * Created by PhpStorm.
 * User: Eric
 * Date: 18/12/2017
 * Time: 13:34
 */

namespace Combodo\iTop\Test\UnitTest\iTopTickets;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use Exception;


/**
 * @group itopVirtualizationMgmt
 * @group itopConfigMgmt
 * @group itopTickets
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class ItopTicketTest extends ItopDataTestCase
{
	const CREATE_TEST_ORG = true;

	/**
     * @throws Exception
     */
    protected function setUp()
	{
		parent::setUp();
	}

	/**
     * <pre>
	 * Given:
	 *
	 * Server1+---->Hypervisor1+---->Person1
	 *
	 * Ticket+---->Server1 (manual)
	 *
	 * Result:
	 *
	 * Ticket+====>Server1,Hypervisor1
	 *       |
	 *       +====>Person1
	 * </pre>
     *
	 * @throws Exception
	 */
	public function testUpdateImpactedItems_Basic()
	{
		$oTicket = $this->CreateTicket(1);
		$oServer1 = $this->CreateServer(1);
		$oHypervisor1 = $this->CreateHypervisor(1, $oServer1);
		$oPerson1 = $this->CreatePerson(1);
		$this->AddContactToCI($oPerson1, $oHypervisor1);
		$oHypervisor1->DBUpdate();

		$aAwaitedCIs = $this->AddCIToTicket($oServer1, $oTicket, 'manual');
		$oTicket->DBUpdate(); // trigger the impact update
		$this->ReloadObject($oTicket); // reload the links

        // Add the computed CIs
        $aAwaitedCIs = $aAwaitedCIs + array($oHypervisor1->GetKey() => 'computed');

        $this->CheckFunctionalCIList($oTicket, $aAwaitedCIs);

        // Computed Contacts
        $aAwaitedContacts = array($oPerson1->GetKey() => array('role_code' => 'computed'));
        $this->CheckContactList($oTicket, $aAwaitedContacts);
        $this->assertEquals(2, $oTicket->Get('functionalcis_list')->Count());
        $this->assertEquals(1, $oTicket->Get('contacts_list')->Count());
	}

    /**
     * <pre>
     * Given:
     *
     * Server1+---->Hypervisor1+---->Person1
     *
     * Ticket+---->Server1 (manual)
     *
     * Result:
     *
     * Ticket+====>Server1,Hypervisor1
     *       |
     *       +====>Person1
     * </pre>
     *
     * @throws Exception
     */
    public function testUpdateImpactedItems_Basic2()
    {
        $oTicket = $this->CreateTicket(1);
        $oServer1 = $this->CreateServer(1);
        $oHypervisor1 = $this->CreateHypervisor(1, $oServer1);
        $oPerson1 = $this->CreatePerson(1);
        $this->AddContactToCI($oPerson1, $oHypervisor1);
        $oHypervisor1->DBUpdate();

        $this->AddCIToTicket($oServer1, $oTicket, 'manual');
        $oTicket->DBUpdate(); // trigger the impact update

        $this->ReloadObject($oTicket); // reload the links

        $this->CheckFunctionalCIList($oTicket);
        $this->CheckContactList($oTicket);
        $this->assertEquals(2, $oTicket->Get('functionalcis_list')->Count());
        $this->assertEquals(1, $oTicket->Get('contacts_list')->Count());

        // Try removing computed entries
        $this->RemoveCIFromTicket($oHypervisor1, $oTicket);
        $this->RemoveContactFromTicket($oPerson1, $oTicket);
        $oTicket->DBUpdate(); // trigger the impact update
        $this->ReloadObject($oTicket); // reload the links

        $this->CheckFunctionalCIList($oTicket);
        $this->CheckContactList($oTicket);
        $this->assertEquals(2, $oTicket->Get('functionalcis_list')->Count());
        $this->assertEquals(1, $oTicket->Get('contacts_list')->Count());
    }

    /**
     * <pre>
     * Given:
     *
     * Server1+---->Hypervisor1+---->Person1
     *
     * Server2    Person2
     *
     * Ticket+---->Server1 (manual), Server2 (computed)
     *
     * Result:
     *
     * Ticket+====>Server1,Hypervisor1
     *       |
     *       +====>Person1
     * </pre>
     *
     * @throws Exception
     */
    public function testUpdateImpactedItems_RemoveUnecessaryEntries()
    {
        $oTicket = $this->CreateTicket(1);
        $oServer1 = $this->CreateServer(1);
        $oHypervisor1 = $this->CreateHypervisor(1, $oServer1);
        $oPerson1 = $this->CreatePerson(1);
        $this->AddContactToCI($oPerson1, $oHypervisor1);
        $oHypervisor1->DBUpdate();

        $oServer2 = $this->CreateServer(2);
        $oPerson2 = $this->CreatePerson(2);

        $this->AddCIToTicket($oServer1, $oTicket, 'manual');
        $this->AddCIToTicket($oServer2, $oTicket, 'computed');
        $this->AddContactToTicket($oPerson2, $oTicket, 'computed');
        $oTicket->DBUpdate(); // trigger the impact update
        $this->ReloadObject($oTicket); // reload the links

        $this->CheckFunctionalCIList($oTicket);
        $this->CheckContactList($oTicket);
        $this->assertEquals(2, $oTicket->Get('functionalcis_list')->Count());
        $this->assertEquals(1, $oTicket->Get('contacts_list')->Count());
    }

    /**
     * Create a first impact chain then remove the root cause, all the chain should be removed.
     *
     * <pre>
     * Given:
     *
     * Server1+---->Hypervisor1+---->Person1
     *
     * Ticket+---->Server1 (manual)
     *
     * Result:
     *
     * Ticket+====>Server1,Hypervisor1
     *       |
     *       +====>Person1
     *
     * Then remove Server1
     *
     * Result:
     *
     * Ticket+====>
     *       |
     *       +====>
     *
     * </pre>
     *
     * @throws Exception
     */
    public function testUpdateImpactedItems_RemoveUnecessaryEntries2()
    {
        $oTicket = $this->CreateTicket(1);
        $oServer1 = $this->CreateServer(1);
        $oHypervisor1 = $this->CreateHypervisor(1, $oServer1);
        $oPerson1 = $this->CreatePerson(1);
        $this->AddContactToCI($oPerson1, $oHypervisor1);
        $oHypervisor1->DBUpdate();

        $this->AddCIToTicket($oServer1, $oTicket, 'manual');
        $oTicket->DBUpdate(); // trigger the impact update
        $this->ReloadObject($oTicket); // reload the links

        $this->CheckFunctionalCIList($oTicket);
        $this->CheckContactList($oTicket);
        $this->assertEquals(2, $oTicket->Get('functionalcis_list')->Count());
        $this->assertEquals(1, $oTicket->Get('contacts_list')->Count());

        $this->RemoveCIFromTicket($oServer1, $oTicket);
        $oTicket->DBUpdate(); // trigger the impact update
        $this->ReloadObject($oTicket); // reload the links

        $this->CheckFunctionalCIList($oTicket);
        $this->CheckContactList($oTicket);
        $this->assertEquals(0, $oTicket->Get('functionalcis_list')->Count());
        $this->assertEquals(0, $oTicket->Get('contacts_list')->Count());
    }


    /**
     * <pre>
     *
     * Server2+---->Hypervisor2+---->Person2
     *
     * Ticket+---->(empty)
     *
     * Result:
     *
     * Ticket+====>(empty)
     * </pre>
     *
     * @throws Exception
     */
    public function testUpdateImpactedItems_NoImpact()
    {
        $oTicket = $this->CreateTicket(1);
        $oServer2 = $this->CreateServer(2);
        $oPerson2 = $this->CreatePerson(2);
        $oHypervisor2 = $this->CreateHypervisor(2, $oServer2);
        $this->AddContactToCI($oPerson2, $oHypervisor2);
        $oHypervisor2->DBUpdate();

        $oTicket->DBUpdate(); // trigger the impact update
        $this->ReloadObject($oTicket); // reload the links

        $this->CheckFunctionalCIList($oTicket);
        $this->CheckContactList($oTicket);
        $this->assertEquals(0, $oTicket->Get('functionalcis_list')->Count());
        $this->assertEquals(0, $oTicket->Get('contacts_list')->Count());
    }

    /**
	 * <pre>
	 * Server1
	 *
	 * Server2+---->Hypervisor2+---->Person2
	 *
	 * Ticket+---->Server1 (manual)
	 *
	 * Result:
	 *
	 * Ticket+====>Server1
	 * </pre>
	 *
	 * @throws Exception
	 */
	public function testUpdateImpactedItems_NoImpact2()
	{
		$oTicket = $this->CreateTicket(1);
		$oServer1 = $this->CreateServer(1);
		$oServer2 = $this->CreateServer(2);
		$oPerson2 = $this->CreatePerson(2);
		$oHypervisor2 = $this->CreateHypervisor(2, $oServer2);
		$this->AddContactToCI($oPerson2, $oHypervisor2);
		$oHypervisor2->DBUpdate();

		$this->AddCIToTicket($oServer1, $oTicket, 'manual');
		$oTicket->DBUpdate(); // trigger the impact update
		$this->ReloadObject($oTicket); // reload the links

        $this->CheckFunctionalCIList($oTicket);
        $this->CheckContactList($oTicket);
        $this->assertEquals(1, $oTicket->Get('functionalcis_list')->Count());
		$this->assertEquals(0, $oTicket->Get('contacts_list')->Count());
	}

	/**
	 * <pre>
	 *                    +-->Person1
	 *                    |
	 *                    +
	 * Server1+---->Hypervisor1+--+
	 *                            |
	 *                            v
	 *                            Farm (1)
	 *                            ^
	 *                            |
	 * Server2+---->Hypervisor2+--+
	 *                    +
	 *                    |
	 *                    +-->Person2
	 *
	 * Ticket+---->Server1 (manual)
	 *
	 * Result:
	 *
	 * Ticket+====>Server1,Hypervisor1
	 *       |
	 *       +====>Person1
	 * </pre>
	 *
	 * @throws Exception
	 */
	public function testUpdateImpactedItems_Redundancy()
	{
		$oFarm = $this->CreateFarm(1);

		$oServer1 = $this->CreateServer(1);
		$oHypervisor1 = $this->CreateHypervisor(1, $oServer1, $oFarm);
		$oContact1 = $this->CreatePerson(1);
		$this->AddContactToCI($oContact1, $oHypervisor1);
		$oHypervisor1->DBUpdate();

		$oServer2 = $this->CreateServer(2);
		$oHypervisor2 = $this->CreateHypervisor(2, $oServer2, $oFarm);
		$oContact2 = $this->CreatePerson(2);
		$this->AddContactToCI($oContact2, $oHypervisor2);
		$oHypervisor2->DBUpdate();

		$oTicket = $this->CreateTicket(1);
		$this->AddCIToTicket($oServer1, $oTicket, 'manual');
		$oTicket->DBUpdate(); // trigger the impact update
		$this->ReloadObject($oTicket); // reload the links

        $this->CheckFunctionalCIList($oTicket);
        $this->CheckContactList($oTicket);
        $this->assertEquals(2, $oTicket->Get('functionalcis_list')->Count());
        $this->assertEquals(1, $oTicket->Get('contacts_list')->Count());
    }

	/**
	 * <pre>
	 *                    +-->Person1
	 *                    |
	 *                    +
	 * Server1+---->Hypervisor1+--+
	 *                            |
	 *                            v
	 *                            Farm (1)
	 *                            ^
	 *                            |
	 * Server2+---->Hypervisor2+--+
	 *                    +
	 *                    |
	 *                    +-->Person2
	 *
	 * Ticket+---->Server1 (manual), Hypervisor2 (manual)
	 *
	 * Result:
	 *
	 * Ticket+====>Server1,Hypervisor1,Farm,Hypervisor2
	 *       |
	 *       +====>Person1,Person2
	 * </pre>
	 *
	 * @throws Exception
	 */
	public function testUpdateImpactedItems_Redundancy2()
	{
        $oFarm = $this->CreateFarm(1);

		$oServer1 = $this->CreateServer(1);
		$oHypervisor1 = $this->CreateHypervisor(1, $oServer1, $oFarm);
		$oContact1 = $this->CreatePerson(1);
		$this->AddContactToCI($oContact1, $oHypervisor1);
		$oHypervisor1->DBUpdate();

		$oServer2 = $this->CreateServer(2);
		$oHypervisor2 = $this->CreateHypervisor(2, $oServer2, $oFarm);
		$oContact2 = $this->CreatePerson(2);
		$this->AddContactToCI($oContact2, $oHypervisor2);
		$oHypervisor2->DBUpdate();

		$oTicket = $this->CreateTicket(1);
		$this->AddCIToTicket($oServer1, $oTicket, 'manual');
		$this->AddCIToTicket($oHypervisor2, $oTicket, 'manual');
		$oTicket->DBUpdate(); // trigger the impact update
		$this->ReloadObject($oTicket); // reload the links

        $this->CheckFunctionalCIList($oTicket);
        $this->CheckContactList($oTicket);
        $this->assertEquals(4, $oTicket->Get('functionalcis_list')->Count());
        $this->assertEquals(2, $oTicket->Get('contacts_list')->Count());
	}


	/**
	 * <pre>
	 *                    +-->Person1
	 *                    |
	 *                    +
	 * Server1+---->Hypervisor1+--+  +-->VM1
	 *                            |  |
	 *                            v  +
	 *                            Farm (1)
	 *                            ^  +
	 *                            |  |
	 * Server2+---->Hypervisor2+--+  +-->VM2
	 *                    +
	 *                    |
	 *                    +-->Person2
	 *
	 * Ticket+---->Server1 (manual), Hypervisor2 (manual)
	 *
	 * Result:
	 *
	 * Ticket+====>Server1,Hypervisor1,Farm,Hypervisor2,VM1,VM2
	 *       |
	 *       +====>Person1,Person2
	 * </pre>
	 *
	 * @throws Exception
	 */
	public function testUpdateImpactedItems_Redundancy3()
	{
        $oFarm = $this->CreateFarm(1);

		$oServer1 = $this->CreateServer(1);
		$oHypervisor1 = $this->CreateHypervisor(1, $oServer1, $oFarm);
		$oContact1 = $this->CreatePerson(1);
		$this->AddContactToCI($oContact1, $oHypervisor1);
		$oHypervisor1->DBUpdate();

		$oServer2 = $this->CreateServer(2);
		$oHypervisor2 = $this->CreateHypervisor(2, $oServer2, $oFarm);
		$oContact2 = $this->CreatePerson(2);
		$this->AddContactToCI($oContact2, $oHypervisor2);
		$oHypervisor2->DBUpdate();

		$this->CreateVirtualMachine(1, $oFarm);
		$this->CreateVirtualMachine(2, $oFarm);

		$oTicket = $this->CreateTicket(1);
		$this->AddCIToTicket($oServer1, $oTicket, 'manual');
		$this->AddCIToTicket($oHypervisor2, $oTicket, 'manual');
		$oTicket->DBUpdate(); // trigger the impact update
		$this->ReloadObject($oTicket); // reload the links

        $this->CheckFunctionalCIList($oTicket);
        $this->CheckContactList($oTicket);
        $this->assertEquals(6, $oTicket->Get('functionalcis_list')->Count());
        $this->assertEquals(2, $oTicket->Get('contacts_list')->Count());
	}

    /**
     * <pre>
     *                    +-->Person1
     *                    |
     *                    +
     * Server1+---->Hypervisor1+--+  +-->VM1
     *                            |  |
     *                            v  +
     *                            Farm (1)
     *                            ^  +
     *                            |  |
     * Server2+---->Hypervisor2+--+  +-->VM2
     *                    +
     *                    |
     *                    +-->Person2
     *
     * Ticket+---->Server1 (manual), Server2 (manual), Hypervisor2 (not_impacted)
     *
     * Result:
     *
     * Ticket+====>Server1,Hypervisor1,Server2,Hypervisor2
     *       |
     *       +====>Person1
     * </pre>
     *
     * @throws Exception
     */
    public function testUpdateImpactedItems_Exclusion()
    {
        $oFarm = $this->CreateFarm(1);

        $oServer1 = $this->CreateServer(1);
        $oHypervisor1 = $this->CreateHypervisor(1, $oServer1, $oFarm);
        $oContact1 = $this->CreatePerson(1);
        $this->AddContactToCI($oContact1, $oHypervisor1);
        $oHypervisor1->DBUpdate();

        $oServer2 = $this->CreateServer(2);
        $oHypervisor2 = $this->CreateHypervisor(2, $oServer2, $oFarm);
        $oContact2 = $this->CreatePerson(2);
        $this->AddContactToCI($oContact2, $oHypervisor2);
        $oHypervisor2->DBUpdate();

        $this->CreateVirtualMachine(1, $oFarm);
        $this->CreateVirtualMachine(2, $oFarm);

        $oTicket = $this->CreateTicket(1);
        $this->AddCIToTicket($oServer1, $oTicket, 'manual');
        $this->AddCIToTicket($oServer2, $oTicket, 'manual');
        $this->AddCIToTicket($oHypervisor2, $oTicket, 'not_impacted');
        $oTicket->DBUpdate(); // trigger the impact update
        $this->ReloadObject($oTicket); // reload the links

        $this->CheckFunctionalCIList($oTicket);
        $this->CheckContactList($oTicket);
        $this->assertEquals(4, $oTicket->Get('functionalcis_list')->Count());
        $this->assertEquals(1, $oTicket->Get('contacts_list')->Count());
    }

    /**
     * <pre>
     *                    +-->Person1
     *                    |
     *                    +
     * Server1+---->Hypervisor1+--+  +-->VM1
     *                            |  |
     *                            v  +
     *                            Farm (1)
     *                            ^  +
     *                            |  |
     * Server2+---->Hypervisor2+--+  +-->VM2
     *                    +
     *                    |
     *                    +-->Person2
     *
     * Ticket+---->Server1 (manual), Server2 (manual)
     *       |
     *       +---->Person2 (do_not_notify)
     *
     * Result:
     *
     * Ticket+====>Server1,Hypervisor1,Server2,Hypervisor2,Farm,VM1,VM2
     *       |
     *       +====>Person1,Person2 (do_not_notify)
     * </pre>
     *
     * @throws Exception
     */
    public function testUpdateImpactedItems_Exclusion2()
    {
        $oFarm = $this->CreateFarm(1);

        $oServer1 = $this->CreateServer(1);
        $oHypervisor1 = $this->CreateHypervisor(1, $oServer1, $oFarm);
        $oContact1 = $this->CreatePerson(1);
        $this->AddContactToCI($oContact1, $oHypervisor1);
        $oHypervisor1->DBUpdate();

        $oServer2 = $this->CreateServer(2);
        $oHypervisor2 = $this->CreateHypervisor(2, $oServer2, $oFarm);
        $oContact2 = $this->CreatePerson(2);
        $this->AddContactToCI($oContact2, $oHypervisor2);
        $oHypervisor2->DBUpdate();

        $this->CreateVirtualMachine(1, $oFarm);
        $this->CreateVirtualMachine(2, $oFarm);

        $oTicket = $this->CreateTicket(1);
        $this->AddCIToTicket($oServer1, $oTicket, 'manual');
        $this->AddCIToTicket($oServer2, $oTicket, 'manual');
        $this->AddContactToTicket($oContact2, $oTicket, 'do_not_notify');
        $oTicket->DBUpdate(); // trigger the impact update
        $this->ReloadObject($oTicket); // reload the links

        $this->CheckFunctionalCIList($oTicket);
        $this->CheckContactList($oTicket);
        $this->assertEquals(7, $oTicket->Get('functionalcis_list')->Count());
        $this->assertEquals(2, $oTicket->Get('contacts_list')->Count());
    }

    /**
     * <pre>
     *                    +-->Person1
     *                    |
     *                    +
     * Server1+---->Hypervisor1+--+  +-->VM1
     *                            |  |
     *                            v  +
     *                            Farm (1)
     *                            ^  +
     *                            |  |
     * Server2+---->Hypervisor2+--+  +-->VM2
     *                    +
     *                    |
     *                    +-->Person2
     *
     * Ticket+---->Server1 (manual), Server2 (manual), Hypervisor2 (not_impacted)
     *       |
     *       +---->Person2 (do_not_notify)
     *
     * Result:
     *
     * Ticket+====>Server1,Hypervisor1,Server2,Hypervisor2
     *       |
     *       +====>Person1,Person2 (do_not_notify)
     * </pre>
     *
     * @throws Exception
     */
    public function testUpdateImpactedItems_Exclusion3()
    {
        $oFarm = $this->CreateFarm(1);

        $oServer1 = $this->CreateServer(1);
        $oHypervisor1 = $this->CreateHypervisor(1, $oServer1, $oFarm);
        $oContact1 = $this->CreatePerson(1);
        $this->AddContactToCI($oContact1, $oHypervisor1);
        $oHypervisor1->DBUpdate();

        $oServer2 = $this->CreateServer(2);
        $oHypervisor2 = $this->CreateHypervisor(2, $oServer2, $oFarm);
        $oContact2 = $this->CreatePerson(2);
        $this->AddContactToCI($oContact2, $oHypervisor2);
        $oHypervisor2->DBUpdate();

        $this->CreateVirtualMachine(1, $oFarm);
        $this->CreateVirtualMachine(2, $oFarm);

        $oTicket = $this->CreateTicket(1);
        $this->AddCIToTicket($oServer1, $oTicket, 'manual');
        $this->AddCIToTicket($oServer2, $oTicket, 'manual');
        $this->AddCIToTicket($oHypervisor2, $oTicket, 'not_impacted');
        $this->AddContactToTicket($oContact2, $oTicket, 'do_not_notify');
        $oTicket->DBUpdate(); // trigger the impact update
        $this->ReloadObject($oTicket); // reload the links

        $this->CheckFunctionalCIList($oTicket);
        $this->CheckContactList($oTicket);
        $this->assertEquals(4, $oTicket->Get('functionalcis_list')->Count());
        $this->assertEquals(2, $oTicket->Get('contacts_list')->Count());
    }

    /**
     * <pre>
     *                    +-->Person1
     *                    |
     *                    +
     * Server1+---->Hypervisor1+--+  +-->VM1
     *                            |  |
     *                            v  +
     *                            Farm (1)
     *                            ^  +
     *                            |  |
     * Server2+---->Hypervisor2+--+  +-->VM2
     *                    +
     *                    |
     *                    +-->Person2
     *
     * Ticket+---->Server1 (manual), Hypervisor2 (not_impacted)
     *
     * Result:
     *
     * Ticket+====>Server1,Hypervisor1, Hypervisor2 (not_impacted)
     *       |
     *       +====>Person1
     * </pre>
     *
     * @throws Exception
     */
    public function testUpdateImpactedItems_Exclusion4()
    {
        $oFarm = $this->CreateFarm(1);

        $oServer1 = $this->CreateServer(1);
        $oHypervisor1 = $this->CreateHypervisor(1, $oServer1, $oFarm);
        $oContact1 = $this->CreatePerson(1);
        $this->AddContactToCI($oContact1, $oHypervisor1);
        $oHypervisor1->DBUpdate();

        $oServer2 = $this->CreateServer(2);
        $oHypervisor2 = $this->CreateHypervisor(2, $oServer2, $oFarm);
        $oContact2 = $this->CreatePerson(2);
        $this->AddContactToCI($oContact2, $oHypervisor2);
        $oHypervisor2->DBUpdate();

        $this->CreateVirtualMachine(1, $oFarm);
        $this->CreateVirtualMachine(2, $oFarm);

        $oTicket = $this->CreateTicket(1);
        $this->AddCIToTicket($oServer1, $oTicket, 'manual');
        $this->AddCIToTicket($oHypervisor2, $oTicket, 'not_impacted');
        $oTicket->DBUpdate(); // trigger the impact update
        $this->ReloadObject($oTicket); // reload the links

        $this->CheckFunctionalCIList($oTicket);
        $this->CheckContactList($oTicket);
        $this->assertEquals(3, $oTicket->Get('functionalcis_list')->Count());
        $this->assertEquals(1, $oTicket->Get('contacts_list')->Count());
    }

    /**
     * <pre>
     *                    +-->Person1
     *                    |
     *                    +
     * Server1+---->Hypervisor1+--+  +-->VM1
     *                            |  |
     *                            v  +
     *                            Farm (1)
     *                            ^  +
     *                            |  |
     * Server2+---->Hypervisor2+--+  +-->VM2
     *                    +
     *                    |
     *                    +-->Person2
     *
     * Ticket1+---->Server1 (manual)
     *
     * Result:
     *
     * Ticket1+====>Server1,Hypervisor1
     *        |
     *        +====>Person1
     *
     * Then:
     *
     * Ticket2+---->Server2 (manual)
     *
     * Result:
     *
     * Ticket2+====>Server2,Hypervisor2,Farm,VM1,VM2
     *        |
     *        +====>Person2
     * </pre>
     *
     * @throws \ArchivedObjectException
     * @throws Exception
     */
    public function testUpdateImpactedItems_Redundancy_two_tickets()
    {
        $oFarm = $this->CreateFarm(1);

        $oServer1 = $this->CreateServer(1);
        $oHypervisor1 = $this->CreateHypervisor(1, $oServer1, $oFarm);
        $oContact1 = $this->CreatePerson(1);
        $this->AddContactToCI($oContact1, $oHypervisor1);
        $oHypervisor1->DBUpdate();

        $oServer2 = $this->CreateServer(2);
        $oHypervisor2 = $this->CreateHypervisor(2, $oServer2, $oFarm);
        $oContact2 = $this->CreatePerson(2);
        $this->AddContactToCI($oContact2, $oHypervisor2);
        $oHypervisor2->DBUpdate();

        $oVM1 = $this->CreateVirtualMachine(1, $oFarm);
        $oVM2 = $this->CreateVirtualMachine(2, $oFarm);

        // Ticket1+---->Server1 (manual)
        $oTicket1 = $this->CreateTicket(1);
        $this->AddCIToTicket($oServer1, $oTicket1, 'manual');
        $oTicket1->DBUpdate(); // trigger the impact update
        $this->ReloadObject($oTicket1); // reload the links

        // Ticket1+====>Server1,Hypervisor1
        // 	      |
        //        +====>Person1
        $this->CheckFunctionalCIList($oTicket1);
        $this->CheckContactList($oTicket1);
        $this->assertEquals(2, $oTicket1->Get('functionalcis_list')->Count());
        $this->assertEquals(1, $oTicket1->Get('contacts_list')->Count());

        // Ticket2+---->Hypervisor2 (manual)
        $oTicket2 = $this->CreateTicket(2);
        $this->AddCIToTicket($oServer2, $oTicket2, 'manual');
        $oTicket2->DBUpdate(); // trigger the impact update
        $this->ReloadObject($oTicket2); // reload the links

        // Ticket2+====>Farm,Hypervisor2,VM1,VM2,Server2
        //        |
        //        +====>Person2
        $aWaitedCIList = array(
            $oFarm->GetKey() => 'computed',
            $oVM1->GetKey() => 'computed',
            $oVM2->GetKey() => 'computed',
            $oHypervisor2->GetKey() => 'computed',
            $oServer2->GetKey() => 'manual');
        $this->CheckFunctionalCIList($oTicket2, $aWaitedCIList);
        $this->CheckContactList($oTicket2);
	    $this->assertEquals(2, $oTicket2->Get('functionalcis_list')->Count());
        $this->assertEquals(1, $oTicket2->Get('contacts_list')->Count());

        // The first ticket is not impacted
        $this->debug("\nCheck that the first ticket has not changed.");
        $this->ReloadObject($oTicket1); // reload the links

        // Ticket1+====>Server1,Hypervisor1
        // 	      |
        //        +====>Person1
        $this->CheckFunctionalCIList($oTicket1);
        $this->CheckContactList($oTicket1);
        $this->assertEquals(2, $oTicket1->Get('functionalcis_list')->Count());
        $this->assertEquals(1, $oTicket1->Get('contacts_list')->Count());
    }

    /**
     * <pre>
     *                    +-->Person1
     *                    |
     *                    +
     * Server1+---->Hypervisor1+--+  +-->VM1
     *                            |  |
     *                            v  +
     * Server3+---->Hypervisor3+->Farm (1)
     *                            ^  +
     *                            |  |
     * Server2+---->Hypervisor2+--+  +-->VM2
     *                    +
     *                    |
     *                    +-->Person2
     *
     * Ticket1+---->Server1 (manual), Hypervisor2(manual)
     *
     * Result:
     *
     * Ticket1+====>Server1,Hypervisor1, Hypervisor2
     *        |
     *        +====>Person1, Person2
     *
     * Then:
     *
     * Ticket2+---->Server2 (manual), Hypervisor3 (manual)
     *
     * Result:
     *
     * Ticket2+====>Server2,Hypervisor2,Hypervisor3,Farm,VM1,VM2
     *        |
     *        +====>Person2
     * </pre>
     *
     * @throws \ArchivedObjectException
     * @throws Exception
     */
    public function testUpdateImpactedItems_Redundancy_two_tickets2()
    {
        $oFarm = $this->CreateFarm(1);

        $oServer1 = $this->CreateServer(1);
        $oHypervisor1 = $this->CreateHypervisor(1, $oServer1, $oFarm);
        $oContact1 = $this->CreatePerson(1);
        $this->AddContactToCI($oContact1, $oHypervisor1);
        $oHypervisor1->DBUpdate();

        $oServer2 = $this->CreateServer(2);
        $oHypervisor2 = $this->CreateHypervisor(2, $oServer2, $oFarm);
        $oContact2 = $this->CreatePerson(2);
        $this->AddContactToCI($oContact2, $oHypervisor2);
        $oHypervisor2->DBUpdate();

        $oServer3 = $this->CreateServer(3);
        $oHypervisor3 = $this->CreateHypervisor(3, $oServer3, $oFarm);


        $oVM1 = $this->CreateVirtualMachine(1, $oFarm);
        $oVM2 = $this->CreateVirtualMachine(2, $oFarm);

        // Ticket1+---->Server1 (manual), Hypervisor2(manual)
        $oTicket1 = $this->CreateTicket(1);
        $this->AddCIToTicket($oServer1, $oTicket1, 'manual');
        $this->AddCIToTicket($oHypervisor2, $oTicket1, 'manual');
        $oTicket1->DBUpdate(); // trigger the impact update
        $this->ReloadObject($oTicket1); // reload the links

        // Ticket1+====>Server1,Hypervisor1,Hypervisor2
        // 	      |
        //        +====>Person1,Person2
        $this->CheckFunctionalCIList($oTicket1);
        $this->CheckContactList($oTicket1);
        $this->assertEquals(3, $oTicket1->Get('functionalcis_list')->Count());
        $this->assertEquals(2, $oTicket1->Get('contacts_list')->Count());

        // Ticket2+---->Server2 (manual)
        $oTicket2 = $this->CreateTicket(2);
        $this->AddCIToTicket($oServer2, $oTicket2, 'manual');
        $this->AddCIToTicket($oHypervisor3, $oTicket2, 'manual');
        $oTicket2->DBUpdate(); // trigger the impact update
        $this->ReloadObject($oTicket2); // reload the links

        // Ticket2+====>Farm,Hypervisor2,VM1,VM2,Server2
        //        |
        //        +====>Person2
        $aWaitedCIList = array(
            $oFarm->GetKey() => 'computed',
            $oVM1->GetKey() => 'computed',
            $oVM2->GetKey() => 'computed',
            $oHypervisor2->GetKey() => 'computed',
            $oHypervisor3->GetKey() => 'manual',
            $oServer2->GetKey() => 'manual');
        $this->CheckFunctionalCIList($oTicket2, $aWaitedCIList);
        $this->CheckContactList($oTicket2);
	    $this->assertEquals(3, $oTicket2->Get('functionalcis_list')->Count());
        $this->assertEquals(1, $oTicket2->Get('contacts_list')->Count());

        // The first ticket is not impacted
        $this->debug("\nCheck that the first ticket has not changed.");
        $this->ReloadObject($oTicket1); // reload the links

        // Ticket1+====>Server1,Hypervisor1,Hypervisor2
        // 	      |
        //        +====>Person1,Person2
        $this->CheckFunctionalCIList($oTicket1);
        $this->CheckContactList($oTicket1);
        $this->assertEquals(3, $oTicket1->Get('functionalcis_list')->Count());
        $this->assertEquals(2, $oTicket1->Get('contacts_list')->Count());
    }

}

<?php


namespace Combodo\iTop\Test\UnitTest\Core;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use Exception;
use lnkPersonToTeam;
use Person;
use Team;

/**
 * Class UniquenessMessageTest
 *
 * @package Combodo\iTop\Test\UnitTest\Core
 */
class UniquenessMessageTest extends ItopDataTestCase
{

	/** @inheritdoc */
	protected function setUp(): void
	{
		parent::setUp();
	}

	/**
	 * Create a Team in database
	 *
	 * @param int $iNum
	 *
	 * @return Team
	 * @throws \Exception
	 */
	protected function CreateTeam($iNum)
	{
		/** @var Team $oTeam */
		$oTeam = $this->createObject('Team', array(
			'name'   => 'Name_'.$iNum,
			'org_id' => $this->getTestOrgId(),
		));
		$this->debug("Created Team {$oTeam->GetName()} ({$oTeam->GetKey()})");

		return $oTeam;
	}

	/**
	 *
	 * @param Person $oPerson
	 * @param Team $oTeam
	 *
	 * @return lnkPersonToTeam
	 * @throws Exception
	 */
	protected function AddPersonToTeam($oPerson, $oTeam)
	{
		$oNewLink = new lnkPersonToTeam();
		$oNewLink->Set('person_id', $oPerson->GetKey());
		$oPersons = $oTeam->Get('persons_list');
		$oPersons->AddItem($oNewLink);
		$oTeam->Set('persons_list', $oPersons);

		return $oNewLink;
	}

	/**
	 * testUniquenessRuleMessage.
	 *
	 * N°5916 - Generic message on Link Uniqueness rules
	 */
	public function testUniquenessRuleMessage()
	{
		$this->CreateTestOrganization();
		$oPerson = $this->CreatePerson(1);
		$oTeam = $this->CreateTeam(1);
		$oLnk = $this->AddPersonToTeam($oPerson, $oTeam);
		$oTeam->DBUpdate();

		// Default error
		$sMessage = $this->InvokeNonPublicMethod(Person::class, 'GetUniquenessRuleMessage', $oPerson, ['no_duplicate']);
		$this->assertEquals("Uniqueness rule 'no_duplicate' in error", $sMessage);

		// Generic message
		$sMessage = $this->InvokeNonPublicMethod(lnkPersonToTeam::class, 'GetUniquenessRuleMessage', $oLnk, ['no_duplicate']);
		$this->assertEquals("Team: Name_1 is already linked to Person: Test Person_1, duplicates are not allowed on this relation.", $sMessage);

		// Specific message
		$sMessage = $this->InvokeNonPublicMethod(Person::class, 'GetUniquenessRuleMessage', $oPerson, ['employee_number']);
		$this->assertEquals("there is already a person in 'UnitTestOrganization' organization with the same employee number", $sMessage);
	}

}

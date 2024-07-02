<?php

namespace Combodo\iTop\Test\UnitTest;

use CMDBSource;
use MetaModel;

/**
 * @covers ItopDataTestCase::GivenObjectInDB
 */
class GivenObjectInDBTest extends ItopDataTestCase
{
	const CREATE_TEST_ORG = true;

	public function testItShouldRelyOnFewerQueriesAsComparedToDBInsert()
	{
		$this->assertDBQueryCount(14, function() use (&$iKey) {
			$iKey = $this->createObject('Organization', ['name' => 'The world company']);
		});

		$this->assertDBQueryCount(2, function() use (&$iKey) {
			$iKey = $this->GivenObjectInDB('Organization', ['name' => 'The world company']);
		});
	}

	public function testItShouldFillInTheOrganizationWhenOmitted()
	{
		$iPerson = $this->GivenObjectInDB('Person', [
			'name' => 'Doe',
			'first_name' => 'John',
		]);

		$oPerson = MetaModel::GetObject('Person', $iPerson);

		$this->assertEquals(
			$this->getTestOrgId(),
			$oPerson->Get('org_id'),
			"When omitted, the org_id should be set to getTestOrgId()"
		);
	}

	public function testItShouldHandleLinksetToo()
	{
		$iPerson = $this->GivenObjectInDB('Person', [
			'name' => 'Doe',
			'first_name' => 'John',
		]);

		$iTeam = $this->GivenObjectInDB('Team', [
			'name' => 'The A Team',
			'persons_list' => [
				"person_id:$iPerson;role_id:1"
			],
		]);

		$oSet = new \DBObjectSet(\DBObjectSearch::FromOQL("SELECT lnkPersonToTeam AS lnk WHERE lnk.team_id = $iTeam AND lnk.person_id = $iPerson"));
		$this->assertEquals(1, $oSet->Count(), "The link between the team and the person should be there");
		$oLnk = $oSet->Fetch();
		$this->assertEquals(1, $oLnk->Get('role_id'), "The role should be correctly set");
	}

	public function testItShouldFailExplicitlyWhenAnAttributeCodeIsUnknown()
	{
		$this->expectException(\Exception::class);
		$this->expectExceptionMessage("GivenObjectInDB('Organization'), invalid attribute code 'amen'");

		$this->GivenObjectInDB('Organization', ['amen' => 'The world company']);
	}

	public function testItShouldFailExplicitlyWhenAMandatoryAttributeIsMissing()
	{
		// Note : a mandatory attribute is an attribute for which the default value cannot be used by default
		// because it is not nullable and the default is "null"

		$this->expectException(\Exception::class);
		$this->expectExceptionMessage("GivenObjectInDB('Organization'), mandatory attribute 'name' is missing");

		$this->GivenObjectInDB('Organization', []);
	}

	/**
	 * @dataProvider SampleObjectsProvider
	 */
	public function testItShouldMakeExactSameObjectsASDBInsert($sClass, $aValues)
	{
		$oObjectCreatedWithCompleteStack = parent::createObject($sClass, $aValues);

		$oObjectFromDBStandard = MetaModel::GetObject($sClass, $oObjectCreatedWithCompleteStack->GetKey());

		// Create by the mean of the efficient method
		$iKey = $this->GivenObjectInDB($sClass, $aValues);

		// Check that it is readable (no exception)
		$oObjectFromDBOptimized = MetaModel::GetObject($sClass, $iKey);

		// Check that an object created by the mean of the std APIs will have the same values
		foreach ($aValues as $sAttCode => $value)
		{
			static::assertEquals(
				$oObjectFromDBStandard->Get($sAttCode),
				$oObjectFromDBOptimized->Get($sAttCode),
				"The value of the attribute '$sAttCode' should be the same as for an object recorded with DBObject::DBInsert"
			);
		}
	}

	public function SampleObjectsProvider()
	{
		return [
			'Organization' => [
				'class' => 'Organization',
				'values' => [
					'name' => 'Orga tartampion',
				]
			],
			'FAQCategory' => [
				'class' => 'FAQCategory',
				'values' => [
					'name' => 'FAQCategory_phpunit',
				]
			],
			'Server' => [
				'class' => 'Server',
				'values' => [
					'name' => 'Server tartampion',
					'org_id' => 1,
					'nb_u' => 123,
	             ]
			],
			'TagSetFieldDataFor_FAQ__domains' => [
				'class' => 'TagSetFieldDataFor_FAQ__domains',
				'values' => [
					'code' => 'tagada'.uniqid(),
					'label' => 'label for tagada'.uniqid(),
					'obj_class' => 'FAQ',
					'obj_attcode' => 'domains',
					'description' => '<p>tartampion</p>',
				]
			],
			'Hypervisor' => [
				'class' => 'Hypervisor',
				'values' => [
					'name' => 'Hypervisor_tartampion',
					'org_id' => 1,
					'server_id' => 1,
					'farm_id' => 0,
				]
			],
			'Rack' => [
				'class' => 'Rack',
				'values' => [
					'name' => "rackamuffin",
					'description' => "rackadescription",
					'org_id' => 1
				]
			],
			'Person' => [
				'class' => 'Person',
				'values' => [
					'name' => 'Person_tartampion',
					'first_name' => 'Test',
					'org_id' => 1,
				]
			],
			'Farm' => [
				'class' => 'Farm',
				'values' => [
					'name' => 'Farm_tartampion',
					'org_id' => 1,
					'redundancy' => '1',
				]
			],
			'UserRequest' => [
				'class' => 'UserRequest',
				'values' => [
					'ref' => 'Ticket_tartampion',
					'title' => 'TICKET_TARTAMPION as a title',
					'description' => '<p>Created for unit tests.</p>',
					'org_id' => 1,
				]
			],
		];
	}
}

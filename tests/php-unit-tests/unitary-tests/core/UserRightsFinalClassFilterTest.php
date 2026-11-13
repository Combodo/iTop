<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Core;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use CMDBSource;
use DBObjectSearch;
use DBObjectSet;
use DBUnionSearch;
use MetaModel;
use FieldExpression;
use ProfilesConfig;
use UserRights;
use UserRightsProfile;

/**
 * @since 3.2.3
 */
class UserRightsFinalClassFilterTest extends ItopDataTestCase
{
	public const CREATE_TEST_ORG = true;

	/** @since 3.2.3 */
	private array $aOriginalGrants;
	/** @since 3.2.3 */
	private array $aOriginalSettings = [];
	/** @since 3.2.3 */
	private int $iProfileId;
	/** @since 3.2.3 */
	private array $aCIIds;
	/** @since 3.2.3 */
	private string $sLogin;

	/** @since 3.2.3 */
	protected function setUp(): void
	{
		parent::setUp();

		$this->aOriginalGrants = $this->GetNonPublicStaticProperty(ProfilesConfig::class, 'aGRANTS');
		foreach (['user_rights_legacy', 'security.disable_joined_classes_filter', 'security.disable_final_classes_filter'] as $sSetting) {
			$this->aOriginalSettings[$sSetting] = MetaModel::GetConfig()->Get($sSetting);
			MetaModel::GetConfig()->Set($sSetting, false);
		}

		$this->iProfileId = (int) $this->GivenObjectInDB('URP_Profiles', [
			'name' => 'Final class filter test',
			'description' => 'Read grants for final class filtering',
		]);
		$this->sLogin = 'final-class-filter-'.uniqid();
		$this->CreateContactlessUser($this->sLogin, $this->iProfileId, uniqid().'Aa1!');
		$iNetworkDeviceType = $this->GivenObjectInDB('NetworkDeviceType', ['name' => 'Final class test device type']);
		$this->aCIIds = [
			'Server' => $this->GivenObjectInDB('Server', ['name' => 'Allowed server']),
			'NetworkDevice' => $this->GivenObjectInDB('NetworkDevice', [
				'name' => 'Denied network device',
				'networkdevicetype_id' => $iNetworkDeviceType,
			]),
		];
	}

	/** @since 3.2.3 */
	protected function tearDown(): void
	{
		$this->SetNonPublicStaticProperty(ProfilesConfig::class, 'aGRANTS', $this->aOriginalGrants);
		foreach ($this->aOriginalSettings as $sSetting => $value) {
			MetaModel::GetConfig()->Set($sSetting, $value);
		}
		UserRights::FlushPrivileges();

		parent::tearDown();
	}

	/** @since 3.2.3 */
	private function LoginWithGrants(array $aGrants, array $aAdditionalProfileGrants = []): void
	{
		$aConfiguredGrants = array_replace($this->aOriginalGrants, $aAdditionalProfileGrants);
		foreach ($aGrants as $sClass => $bAllowed) {
			$aConfiguredGrants[$this->iProfileId.'_'.$sClass.'_r'] = $bAllowed;
		}
		$this->SetNonPublicStaticProperty(ProfilesConfig::class, 'aGRANTS', $aConfiguredGrants);
		UserRights::FlushPrivileges();
		$this->assertTrue(UserRights::Login($this->sLogin));
	}

	/**
	 * @dataProvider ReadGrantsProvider
	 * @since 3.2.3
	 */
	public function testReadGrants(array $aGrants, string $sClass, array $aExpectedClasses): void
	{
		$this->LoginWithGrants($aGrants);
		$oSearch = new DBObjectSearch($sClass);
		$oSearch->AddCondition('id', array_values($this->aCIIds), 'IN');

		$this->AssertVisibleClasses($oSearch, $aExpectedClasses);
	}

	/** @since 3.2.3 */
	public function ReadGrantsProvider(): array
	{
		return [
			'deny descendant through parent' => [
				['*' => true, 'NetworkDevice' => false], 'FunctionalCI', ['Server'],
			],
			'deny parent despite allowed descendant' => [
				['*' => true, 'FunctionalCI' => false, 'Server' => true], 'FunctionalCI', [],
			],
			'deny intermediate class and its descendants' => [
				['*' => true, 'DatacenterDevice+' => false], 'FunctionalCI', [],
			],
			'all concrete descendants denied' => [
				['FunctionalCI' => true], 'FunctionalCI', [],
			],
			'all descendants allowed through inheritance' => [
				['FunctionalCI+' => true, 'FunctionalCI' => true], 'FunctionalCI', ['Server', 'NetworkDevice'],
			],
			'allowed leaf unchanged' => [
				['*' => true], 'Server', ['Server'],
			],
			'denied leaf unchanged' => [
				['*' => true, 'Server' => false], 'Server', [],
			],
		];
	}

	/** @since 3.2.3 */
	public function testAllAllowedDoesNotAddAFilter(): void
	{
		$this->LoginWithGrants(['*' => true]);
		$this->assertTrue(UserRights::GetSelectFilter('FunctionalCI'));
	}

	/** @since 3.2.3 */
	public function testGroupedCountsExcludeDeniedClasses(): void
	{
		$this->LoginWithGrants(['*' => true, 'NetworkDevice' => false]);
		$oSearch = new DBObjectSearch('FunctionalCI');
		$oSearch->AddCondition('id', array_values($this->aCIIds), 'IN');
		$aRows = CMDBSource::QueryToArray($oSearch->MakeGroupByQuery([], [
			'concrete_class' => new FieldExpression('finalclass', 'FunctionalCI'),
		]));
		$this->assertCount(1, $aRows);
		$this->assertSame('Server', $aRows[0]['concrete_class']);
		$this->assertSame(1, (int) $aRows[0]['_itop_count_']);
	}

	/** @since 3.2.3 */
	public function testAnotherHierarchyIsFiltered(): void
	{
		$iPerson = $this->GivenObjectInDB('Person', ['name' => 'Allowed contact', 'first_name' => 'Test']);
		$iTeam = $this->GivenObjectInDB('Team', ['name' => 'Denied contact']);
		$this->LoginWithGrants(['*' => true, 'Team' => false]);
		$oSearch = new DBObjectSearch('Contact');
		$oSearch->AddCondition('id', [$iPerson, $iTeam], 'IN');
		$oSet = new DBObjectSet($oSearch);
		$this->assertSame(1, $oSet->Count());
		$this->assertSame([(int) $iPerson => 'Person'], $oSet->GetColumnAsArray('finalclass'));
	}

	/** @since 3.2.3 */
	public function testConcreteParentRetainsItsOwnObjects(): void
	{
		$aEventValues = ['message' => 'Final class filter test event', 'date' => '2026-01-01 12:00:00'];
		$iEvent = $this->GivenObjectInDB('Event', $aEventValues);
		$iIssue = $this->GivenObjectInDB('EventIssue', array_merge($aEventValues, [
			'issue' => 'Final class filter test issue',
			'page' => 'Final class filter test',
		]));
		$this->LoginWithGrants(['Event' => true, 'Event+' => true, 'EventIssue' => false]);
		$oSearch = new DBObjectSearch('Event');
		$oSearch->AddCondition('id', [$iEvent, $iIssue], 'IN');
		$oSet = new DBObjectSet($oSearch);
		$this->assertSame(1, $oSet->Count());
		$this->assertSame([(int) $iEvent => 'Event'], $oSet->GetColumnAsArray('finalclass'));
	}

	/** @since 3.2.3 */
	public function testFilterCategoryKeepsItsReadException(): void
	{
		$this->LoginWithGrants([]);
		$this->assertNotFalse(UserRights::GetSelectFilter('URP_Profiles'));
	}

	/** @since 3.2.3 */
	public function testUnionSelectFilterStillAppliesClassRestrictions(): void
	{
		$this->LoginWithGrants(['*' => true, 'NetworkDevice' => false]);
		$oOriginalModule = UserRights::GetModuleInstance();
		$oModule = $this->createPartialMock(UserRightsProfile::class, ['GetSelectFilter']);
		$oModule->method('GetSelectFilter')->willReturnCallback(
			static function ($oUser, $sClass, $aSettings = []) use ($oOriginalModule) {
				$oFilter = $oOriginalModule->GetSelectFilter($oUser, $sClass, $aSettings);
				if ($oFilter instanceof DBObjectSearch) {
					return new DBUnionSearch([$oFilter, DBObjectSearch::FromEmptySet($sClass)]);
				}
				return $oFilter;
			}
		);
		$this->SetNonPublicStaticProperty(UserRights::class, 'm_oAddOn', $oModule);
		try {
			$this->assertInstanceOf(DBUnionSearch::class, UserRights::GetSelectFilter('FunctionalCI'));
			$oSearch = new DBObjectSearch('FunctionalCI');
			$oSearch->AddCondition('id', array_values($this->aCIIds), 'IN');
			$this->AssertVisibleClasses($oSearch, ['Server']);
		} finally {
			$this->SetNonPublicStaticProperty(UserRights::class, 'm_oAddOn', $oOriginalModule);
		}
	}

	/** @since 3.2.3 */
	public function testExplicitDenyFromAnotherProfileWins(): void
	{
		$iDenyProfile = $this->GivenObjectInDB('URP_Profiles', [
			'name' => 'Final class deny profile',
			'description' => 'Explicit deny overrides another profile',
		]);
		$this->GivenObjectInDB('URP_UserProfile', [
			'userid' => UserRights::GetUserId($this->sLogin),
			'profileid' => $iDenyProfile,
		]);
		$this->LoginWithGrants(['*' => true], [$iDenyProfile.'_NetworkDevice_r' => false]);

		$oSearch = new DBObjectSearch('FunctionalCI');
		$oSearch->AddCondition('id', array_values($this->aCIIds), 'IN');
		$this->AssertVisibleClasses($oSearch, ['Server']);
	}

	/**
	 * @dataProvider JoinedAliasesProvider
	 * @since 3.2.3
	 */
	public function testJoinedAliases(bool $bDisableJoinFilter, bool $bSelectBoth, string $sLeftClass, string $sRightClass, bool $bVisible): void
	{
		MetaModel::GetConfig()->Set('security.disable_joined_classes_filter', $bDisableJoinFilter);
		$this->LoginWithGrants(['*' => true, 'NetworkDevice' => false]);
		$sSelectedAliases = $bSelectBoth ? 'A, B' : 'A';
		$oSearch = DBObjectSearch::FromOQL(
			"SELECT $sSelectedAliases FROM FunctionalCI AS A JOIN Organization AS O ON A.org_id = O.id
			 JOIN FunctionalCI AS B ON B.org_id = O.id WHERE A.id = :left_id AND B.id = :right_id"
		);
		$oSet = new DBObjectSet($oSearch, [], [
			'left_id' => $this->aCIIds[$sLeftClass],
			'right_id' => $this->aCIIds[$sRightClass],
		]);
		$this->assertSame($bVisible ? 1 : 0, $oSet->Count());
		$aRow = $oSet->FetchAssoc();
		if ($bVisible) {
			$this->assertSame((int) $this->aCIIds[$sLeftClass], (int) $aRow['A']->GetKey());
			if ($bSelectBoth) {
				$this->assertSame((int) $this->aCIIds[$sRightClass], (int) $aRow['B']->GetKey());
			}
		} else {
			$this->assertNull($aRow);
		}
	}

	/** @since 3.2.3 */
	public function JoinedAliasesProvider(): array
	{
		return [
			'allowed aliases' => [false, true, 'Server', 'Server', true],
			'denied selected left alias' => [false, true, 'NetworkDevice', 'Server', false],
			'denied selected right alias' => [false, true, 'Server', 'NetworkDevice', false],
			'denied non-selected alias' => [false, false, 'Server', 'NetworkDevice', false],
			'non-selected alias with join protection disabled' => [true, false, 'Server', 'NetworkDevice', true],
			'selected right alias with join protection disabled' => [true, true, 'Server', 'NetworkDevice', false],
			'selected left alias with join protection disabled' => [true, false, 'NetworkDevice', 'Server', false],
		];
	}

	/**
	 * @dataProvider CompoundQueriesProvider
	 * @since 3.2.3
	 */
	public function testCompoundQueries(string $sOQL): void
	{
		$this->LoginWithGrants(['*' => true, 'NetworkDevice' => false]);
		$oSearch = DBObjectSearch::FromOQL($sOQL, [
			'org_id' => $this->getTestOrgId(),
			'server_id' => $this->aCIIds['Server'],
			'network_id' => $this->aCIIds['NetworkDevice'],
		]);
		$this->AssertVisibleClasses($oSearch, ['Server']);
	}

	/** @since 3.2.3 */
	public function CompoundQueriesProvider(): array
	{
		return [
			'union' => [
				'SELECT FunctionalCI WHERE id = :server_id UNION SELECT FunctionalCI WHERE id = :network_id',
			],
			'nested search' => [
				'SELECT FunctionalCI WHERE id IN (SELECT FunctionalCI WHERE org_id = :org_id)',
			],
		];
	}

	/** @since 3.2.3 */
	public function testOrganizationScopeStillApplies(): void
	{
		$iOtherOrg = $this->GivenObjectInDB('Organization', ['name' => 'Other final class test organization']);
		$iOtherServer = $this->GivenObjectInDB('Server', ['name' => 'Server outside scope', 'org_id' => $iOtherOrg]);
		$this->GivenObjectInDB('URP_UserOrg', [
			'userid' => UserRights::GetUserId($this->sLogin),
			'allowed_org_id' => $this->getTestOrgId(),
		]);
		$this->bIsUsingSilo = true;
		$this->LoginWithGrants(['*' => true, 'NetworkDevice' => false]);
		$oSearch = new DBObjectSearch('FunctionalCI');
		$oSearch->AddCondition('id', array_merge(array_values($this->aCIIds), [$iOtherServer]), 'IN');
		$this->AssertVisibleClasses($oSearch, ['Server']);
	}

	/**
	 * Given a user with read grants and allowed organizations, check the objects returned by a source OQL.
	 * Placeholders {allowed_org_1}, {allowed_org_2} and {denied_org} are replaced by test organization ids.
	 *
	 * @dataProvider SourceOQLProvider
	 * @since 3.2.3
	 */
	public function testSourceOQLReturnsOnlyAllowedClassesAndOrganizations(array $aGrants, string $sSourceOQL, array $aExpectedVisibleObjects, array $aSettings = []): void
	{
		$aOrgIds = [
			'{allowed_org_1}' => (int) $this->GivenObjectInDB('Organization', ['name' => 'Final class allowed org 1']),
			'{allowed_org_2}' => (int) $this->GivenObjectInDB('Organization', ['name' => 'Final class allowed org 2']),
			'{denied_org}' => (int) $this->GivenObjectInDB('Organization', ['name' => 'Final class denied org']),
		];
		$aObjectIds = [];
		foreach ($aOrgIds as $sOrg => $iOrgId) {
			$aObjectIds['Person'.$sOrg] = (int) $this->GivenObjectInDB('Person', ['name' => 'Person '.$sOrg, 'first_name' => 'Test', 'org_id' => $iOrgId]);
			$aObjectIds['Team'.$sOrg] = (int) $this->GivenObjectInDB('Team', ['name' => 'Team '.$sOrg, 'org_id' => $iOrgId]);
		}
		// Memberships, including memberships crossing the allowed organizations scope
		foreach ([
			'Person{allowed_org_1}' => 'Team{allowed_org_1}',
			'Person{allowed_org_2}' => 'Team{denied_org}',
			'Person{denied_org}' => 'Team{allowed_org_1}',
		] as $sPerson => $sTeam) {
			$this->GivenObjectInDB('lnkPersonToTeam', ['person_id' => $aObjectIds[$sPerson], 'team_id' => $aObjectIds[$sTeam]]);
		}
		foreach (['{allowed_org_1}', '{allowed_org_2}'] as $sOrg) {
			$this->GivenObjectInDB('URP_UserOrg', [
				'userid' => UserRights::GetUserId($this->sLogin),
				'allowed_org_id' => $aOrgIds[$sOrg],
			]);
		}
		foreach ($aSettings as $sSetting => $value) {
			MetaModel::GetConfig()->Set($sSetting, $value);
		}
		$this->bIsUsingSilo = true;
		$this->LoginWithGrants($aGrants);

		$oSet = new DBObjectSet(DBObjectSearch::FromOQL(strtr($sSourceOQL, $aOrgIds)));
		$aVisibleObjects = array_keys(array_intersect($aObjectIds, array_map('intval', array_keys($oSet->GetColumnAsArray('id')))));
		sort($aVisibleObjects);
		sort($aExpectedVisibleObjects);
		$this->assertSame($aExpectedVisibleObjects, $aVisibleObjects, 'Objects visible for the user');
		$this->assertSame(count($aExpectedVisibleObjects), $oSet->Count(), 'No other object visible for the user');
	}

	/** @since 3.2.3 */
	public function SourceOQLProvider(): array
	{
		$aContactWithoutTeam = ['*' => true, 'Team' => false];
		$aDisableJoinedClassesFilter = ['security.disable_joined_classes_filter' => true];
		$aDisableFinalClassesFilter = ['security.disable_final_classes_filter' => true];
		$sPersonJoinLink = 'SELECT Person AS P JOIN lnkPersonToTeam AS L ON L.person_id = P.id WHERE L.id > 0';
		$sPersonJoinTeam = 'SELECT Person AS P JOIN lnkPersonToTeam AS L ON L.person_id = P.id JOIN Team AS T ON L.team_id = T.id WHERE T.id > 0';
		$sTeamJoinPerson = 'SELECT Team AS T JOIN lnkPersonToTeam AS L ON L.team_id = T.id JOIN Person AS P ON L.person_id = P.id';
		$sContactJoinLink = 'SELECT Contact AS C JOIN lnkPersonToTeam AS L ON L.person_id = C.id';

		// The joined Contact alias can only match a Team, so it must be restricted to its allowed final classes
		$sPersonJoinContact = 'SELECT Person AS P JOIN lnkPersonToTeam AS L ON L.person_id = P.id JOIN Contact AS C ON L.team_id = C.id';
		$sSelectJoinedContact = 'SELECT C FROM Person AS P JOIN lnkPersonToTeam AS L ON L.person_id = P.id JOIN Contact AS C ON L.team_id = C.id';

		return [
			'Contact without condition, Team denied, final classes filter disabled' => [
				$aContactWithoutTeam, 'SELECT Contact AS C', ['Person{allowed_org_1}', 'Person{allowed_org_2}', 'Team{allowed_org_1}', 'Team{allowed_org_2}'], $aDisableFinalClassesFilter,
			],
			'Team, Team denied, final classes filter disabled' => [
				$aContactWithoutTeam, 'SELECT Team AS T', [], $aDisableFinalClassesFilter,
			],
			'Selected joined Contact, Team denied, final classes filter disabled' => [
				$aContactWithoutTeam, $sSelectJoinedContact, ['Team{allowed_org_1}'], $aDisableFinalClassesFilter,
			],
			'Person joined to Team as Contact, Team denied' => [
				$aContactWithoutTeam, $sPersonJoinContact, [],
			],
			'Person joined to Team as Contact, Team denied, joined classes filter disabled' => [
				$aContactWithoutTeam, $sPersonJoinContact, ['Person{allowed_org_1}', 'Person{allowed_org_2}'], $aDisableJoinedClassesFilter,
			],
			'Person joined to Team as Contact, all contacts allowed' => [
				['*' => true], $sPersonJoinContact, ['Person{allowed_org_1}'],
			],
			'Selected joined Contact, Team denied' => [
				$aContactWithoutTeam, $sSelectJoinedContact, [],
			],
			'Selected joined Contact, Team denied, joined classes filter disabled' => [
				$aContactWithoutTeam, $sSelectJoinedContact, [], $aDisableJoinedClassesFilter,
			],
			'Selected joined Contact, all contacts allowed' => [
				['*' => true], $sSelectJoinedContact, ['Team{allowed_org_1}'],
			],
			'Person joined to link, Team denied' => [
				$aContactWithoutTeam, $sPersonJoinLink, ['Person{allowed_org_1}', 'Person{allowed_org_2}'],
			],
			'Person joined to Team, Team denied' => [
				$aContactWithoutTeam, $sPersonJoinTeam, [],
			],
			'Person joined to Team, Team denied, joined classes filter disabled' => [
				$aContactWithoutTeam, $sPersonJoinTeam, ['Person{allowed_org_1}', 'Person{allowed_org_2}'], $aDisableJoinedClassesFilter,
			],
			'Person joined to Team, all contacts allowed' => [
				['*' => true], $sPersonJoinTeam, ['Person{allowed_org_1}'],
			],
			'Person joined to Team, all contacts allowed, joined classes filter disabled' => [
				['*' => true], $sPersonJoinTeam, ['Person{allowed_org_1}', 'Person{allowed_org_2}'], $aDisableJoinedClassesFilter,
			],
			'Team joined to Person, Team denied' => [
				$aContactWithoutTeam, $sTeamJoinPerson, [],
			],
			'Team joined to Person, all contacts allowed' => [
				['*' => true], $sTeamJoinPerson, ['Team{allowed_org_1}'],
			],
			'Contact joined to link, Team denied' => [
				$aContactWithoutTeam, $sContactJoinLink, ['Person{allowed_org_1}', 'Person{allowed_org_2}'],
			],
			'Contact in an allowed org, Team denied' => [
				$aContactWithoutTeam,
				'SELECT Contact AS C WHERE C.org_id = {allowed_org_2}',
				['Person{allowed_org_2}'],
			],
			'Contact in a denied org, Team denied' => [
				$aContactWithoutTeam,
				'SELECT Contact AS C WHERE C.org_id = {denied_org}',
				[],
			],
			'Contact without condition, Team denied' => [
				$aContactWithoutTeam,
				'SELECT Contact AS C',
				['Person{allowed_org_1}', 'Person{allowed_org_2}'],
			],
			'Person in an allowed org, Team denied' => [
				$aContactWithoutTeam,
				'SELECT Person AS P WHERE P.org_id = {allowed_org_1}',
				['Person{allowed_org_1}'],
			],
			'Team, Team denied' => [
				$aContactWithoutTeam,
				'SELECT Team AS T',
				[],
			],
			'Contact with all contacts allowed' => [
				['*' => true],
				'SELECT Contact AS C',
				['Person{allowed_org_1}', 'Person{allowed_org_2}', 'Team{allowed_org_1}', 'Team{allowed_org_2}'],
			],
		];
	}
	/** @since 3.2.3 */
	public function testAllowAllDataStillBypassesRestrictions(): void
	{
		$this->LoginWithGrants(['*' => true, 'NetworkDevice' => false]);
		$oSearch = new DBObjectSearch('FunctionalCI');
		$oSearch->AddCondition('id', array_values($this->aCIIds), 'IN');
		// Explicitly test the privileged bypass, not a normal user search.
		$oSearch->AllowAllData();
		$this->AssertVisibleClasses($oSearch, ['Server', 'NetworkDevice']);
	}

	/** @since 3.2.3 */
	public function testAdministratorStillBypassesRestrictions(): void
	{
		$sAdminLogin = 'final-class-admin-'.uniqid();
		$this->CreateContactlessUser($sAdminLogin, self::$aURP_Profiles['Administrator'], uniqid().'Aa1!');
		$this->LoginWithGrants(['*' => true, 'NetworkDevice' => false]);
		UserRights::Logoff();
		$this->assertTrue(UserRights::Login($sAdminLogin));
		$this->assertTrue(UserRights::GetSelectFilter('FunctionalCI'));
		$oSearch = new DBObjectSearch('FunctionalCI');
		$oSearch->AddCondition('id', array_values($this->aCIIds), 'IN');
		$this->AssertVisibleClasses($oSearch, ['Server', 'NetworkDevice']);
	}

	/** @since 3.2.3 */
	private function AssertVisibleClasses(\DBSearch $oSearch, array $aExpectedClasses): void
	{
		$oSet = new DBObjectSet($oSearch);
		$this->assertSame(count($aExpectedClasses), $oSet->Count());
		$aExpectedIds = [];
		foreach ($aExpectedClasses as $sClass) {
			$aExpectedIds[$this->aCIIds[$sClass]] = $sClass;
		}
		$aActualIds = $oSet->GetColumnAsArray('finalclass');
		ksort($aExpectedIds);
		ksort($aActualIds);
		$this->assertSame($aExpectedIds, $aActualIds);
	}
}

<?php

namespace Combodo\iTop\Test\UnitTest\Core;

use CMDBSource;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use DBObjectSearch;
use DBObjectSet;
use DBSearch;
use lnkFunctionalCIToTicket;
use MetaModel;
use ormLinkSet;
use UserRequest;
use UserRights;

class DBSearchFilterJoinTest extends ItopDataTestCase
{
	private const RESTRICTED_PROFILE = 'Configuration Manager';
	private $aData = [];
	protected function setUp(): void
	{
		parent::setUp();
		$this->RequireOnceItopFile('application/startup.inc.php');
		$this->aData = $this->CreateDBSearchFilterTestData();
		DBSearch::EnableQueryCache(false, false);
		$this->LoginRestrictedUser($this->aData['allowed_org_id'], self::RESTRICTED_PROFILE);

	}

	protected function tearDown(): void
	{
		parent::tearDown();
	}

	/**
	 * @dataProvider JoinedAndNestedOqlProvider
	 */
	public function testDBSearchFilterAppliedToJoinsWhenEnabled(string $sOql, int $iExpectedCount): void
	{
		$this->EnableJoinFilterConfig(true);

		$oSearch = DBObjectSearch::FromOQL($sOql, ['denied_org' => $this->aData['denied_org_name'], 'allowed_org' => $this->aData['allowed_org_name']]);
		$oSet = new \DBObjectSet($oSearch);
		CMDBSource::TestQuery($oSearch->MakeSelectQuery());
		$this->assertEquals($iExpectedCount, $oSet->Count());
	}

	/**
	 * @dataProvider JoinedAndNestedOqlProvider
	 */
	public function testDBSearchFilterAppliedToJoinsWhenDisabled(string $sOql, int $iExpectedCount, int $iExpectedDisabledCount): void
	{
		$this->EnableJoinFilterConfig(false);

		$oSearch = DBObjectSearch::FromOQL($sOql, ['denied_org' => $this->aData['denied_org_name'], 'allowed_org' => $this->aData['allowed_org_name']]);
		$oSet = new \DBObjectSet($oSearch);
		CMDBSource::TestQuery($oSearch->MakeSelectQuery());
		$this->assertEquals($iExpectedDisabledCount, $oSet->Count());
	}

	/**
	 * @dataProvider JoinedAndNestedOqlProvider
	 */
	public function testAllowAllDataBypassesDBSearchFilterWhenEnabled(string $sOql, int $iExpectedCount, int $iExpectedDisabledCount): void
	{
		$this->EnableJoinFilterConfig(true);

		$oSearch = DBObjectSearch::FromOQL($sOql, ['denied_org' => $this->aData['denied_org_name'], 'allowed_org' => $this->aData['allowed_org_name']]);
		$oSearch->AllowAllData();
		$oSet = new \DBObjectSet($oSearch);
		CMDBSource::TestQuery($oSearch->MakeSelectQuery());
		$this->assertEquals($iExpectedDisabledCount, $oSet->Count());
	}

	/**
	 * @dataProvider JoinedAndNestedOqlProvider
	 */
	public function testAllowAllDataBypassesDBSearchFilterWhenDisabled(string $sOql, int $iExpectedCount, int $iExpectedDisabledCount): void
	{
		$this->EnableJoinFilterConfig(false);

		$oSearch = DBObjectSearch::FromOQL($sOql, ['denied_org' => $this->aData['denied_org_name'], 'allowed_org' => $this->aData['allowed_org_name']]);
		$oSearch->AllowAllData();
		$oSet = new \DBObjectSet($oSearch);
		CMDBSource::TestQuery($oSearch->MakeSelectQuery());
		$this->assertEquals($iExpectedDisabledCount, $oSet->Count());
	}

	public function JoinedAndNestedOqlProvider(): array
	{
		return [
			'join-filter-on-org' => [
				'oql' => "SELECT OSF FROM OSFamily AS OSF JOIN VirtualMachine AS VM ON VM.osfamily_id = OSF.id JOIN Organization AS O ON VM.org_id = O.id WHERE O.name = :denied_org",
				'expected_filtered_count' => 0,
				'expected_unfiltered_count' => 1,
			],
			'nested-in-select' => [
				'oql' => "SELECT OSF FROM OSFamily AS OSF WHERE OSF.id IN (SELECT OSF1 FROM OSFamily AS OSF1 JOIN VirtualMachine AS VM ON VM.osfamily_id = OSF1.id JOIN Organization AS O ON VM.org_id = O.id WHERE O.name = :denied_org)",
				'expected_filtered_count' => 0,
				'expected_unfiltered_count' => 1,

			],
			'userrequest-join-person-org' => [
				'oql' => "SELECT OSF FROM OSFamily AS OSF JOIN VirtualMachine AS VM ON VM.osfamily_id = OSF.id JOIN lnkFunctionalCIToTicket AS L ON L.functionalci_id = VM.id JOIN UserRequest AS UR ON L.ticket_id = UR.id  JOIN Person AS P ON UR.caller_id = P.id JOIN Organization AS O ON P.org_id = O.id WHERE O.name = :denied_org",
				'expected_filtered_count' => 0,
				'expected_unfiltered_count' => 1,
			],
			'union-join-filter-on-org' => [
				'oql' => "SELECT OSF FROM OSFamily AS OSF JOIN VirtualMachine AS VM ON VM.osfamily_id = OSF.id JOIN Organization AS O ON VM.org_id = O.id WHERE O.name = :denied_org UNION SELECT OSF2 FROM OSFamily AS OSF2 JOIN VirtualMachine AS VM2 ON VM2.osfamily_id = OSF2.id JOIN Organization AS O2 ON VM2.org_id = O2.id WHERE O2.name = :allowed_org",
				'expected_filtered_count' => 1,
				'expected_unfiltered_count' => 2,
			],
		];
	}

	private function EnableJoinFilterConfig(bool $bEnabled): void
	{
		$oConfig = MetaModel::GetConfig();
		$oConfig->Set('security.disable_joined_classes_filter', !$bEnabled);
	}

	private function CreateDBSearchFilterTestData(): array
	{
		$sSuffix = 'DBSearchFilterJoinTest';

		$sAllowedOrgName = 'DBSearchFilterAllowedOrg-'.$sSuffix;
		$iAllowedOrgId = $this->GivenObjectInDB('Organization', [
			'name' => $sAllowedOrgName,
		]);

		$this->debug("Org allowed id: $iAllowedOrgId");
		$sDeniedOrgName = 'DBSearchFilterDeniedOrg-'.$sSuffix;
		$iDeniedOrgId = $this->GivenObjectInDB('Organization', [
			'name' => $sDeniedOrgName,
		]);
		$this->debug("Org denied id: $iDeniedOrgId");

		$iDeniedOsFamilyId = $this->GivenObjectInDB('OSFamily', [
			'name' => 'DBSearchFilterOsFamilyDenied-'.$sSuffix,
		]);

		$iAllowedOsFamilyId = $this->GivenObjectInDB('OSFamily', [
			'name' => 'DBSearchFilterOsFamilyAllowed-'.$sSuffix,
		]);

		$iDeniedVMId = $this->GivenObjectInDB('VirtualMachine', [
			'name' => 'DBSearchFilterVmDenied-'.$sSuffix,
			'org_id' => $iDeniedOrgId,
			'osfamily_id' => $iDeniedOsFamilyId,
			'virtualhost_id' => 1,
		]);

		$iVirtualHostId = $this->GivenObjectInDB('Hypervisor', [
			'name'   => 'DBSearchFilterVHost-'.$sSuffix,
			'org_id' => $iAllowedOrgId,
		]);

		$this->GivenObjectInDB('VirtualMachine', [
			'name' => 'DBSearchFilterVmAllowed-'.$sSuffix,
			'org_id' => $iAllowedOrgId,
			'osfamily_id' => $iAllowedOsFamilyId,
			'virtualhost_id' => $iVirtualHostId,
		]);

		$oDeniedPerson = $this->CreatePerson('Denied-'.$sSuffix, $iDeniedOrgId);

		$oUserRequest = $this->CreateUserRequest('Denied'.$sSuffix, [
			'caller_id' => $oDeniedPerson->GetKey(),
			'org_id' => $iDeniedOrgId,
		]);

		// Add Virtual Machine to UserRequest lnk
		$oLinkSet = new ormLinkSet(UserRequest::class, 'functionalcis_list', DBObjectSet::FromScratch(lnkFunctionalCIToTicket::class));

		$oLink = MetaModel::NewObject(lnkFunctionalCIToTicket::class, ['functionalci_id' => $iDeniedVMId]);
		$oLinkSet->AddItem($oLink);

		$oUserRequest->Set('functionalcis_list', $oLinkSet);
		$oUserRequest->DBUpdate();

		return [
			'allowed_org_id' => $iAllowedOrgId,
			'allowed_org_name' => $sAllowedOrgName,
			'denied_org_name' => $sDeniedOrgName,
		];
	}

	private function LoginRestrictedUser(int $iAllowedOrgId, string $sProfileName): void
	{
		$sLogin = $this->GivenUserRestrictedToAnOrganizationInDB($iAllowedOrgId, self::$aURP_Profiles[$sProfileName]);
		UserRights::Login($sLogin);
	}
}

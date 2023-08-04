<?php

namespace Combodo\iTop\Test\UnitTest\Webservices;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use DBObjectSearch;
use DBObjectSet;
use MetaModel;
use URP_Profiles;
use URP_UserProfile;
use User;
use UserInternal;
use UserLocal;
use UserRightsProfile;


/**
 * @group getSelectFilterTest
 * @group sampleDataNeeded
 * @group specificOrgInSampleData
 * Class GetSelectFilterTest
 *
 * @package Combodo\iTop\Test\UnitTest\Webservices
 */
class GetSelectFilterTest extends ItopDataTestCase
{
	private $sLogin;
	private $sPassword = "IAAuytrez9876[}543ç_è-(";
	private $oUser;

	protected function setUp(): void
	{
		parent::setUp();
		require_once(APPROOT.'application/startup.inc.php');

		$oRestProfile = MetaModel::GetObjectFromOQL("SELECT URP_Profiles WHERE name = :name", array('name' => 'REST Services User'), true);
		$oAdminProfile = MetaModel::GetObjectFromOQL("SELECT URP_Profiles WHERE name = :name", array('name' => 'Administrator'), true);

		$this->sLogin = "getselectfilter-user-" . date('dmYHis');

		// Ensure that we have at least one administrator account
		if (is_object($oRestProfile) && is_object($oAdminProfile))
		{
			$this->oUser = $this->CreateUser($this->sLogin, $oRestProfile->GetKey(), $this->sPassword);
			$this->AddProfileToUser($this->oUser, $oAdminProfile->GetKey());
		}
	}

	public function testGetSelectFilter() {
		$oUserRights = new UserRightsProfile();
		$aClasses = get_declared_classes();
		$aUserClasses = [User::class];
		$aUserLocalAncestors = [User::class, UserInternal::class, UserLocal::class];
		foreach ($aClasses as $sClass) {
			if (false === MetaModel::IsValidClass($sClass)) {
				// Some helper classes, children of User, are used as php_parent in the XML, and thus are not part of the datamodel
				// eg: AbstractApplicationToken in the authent-token module (N°6388)
				// We don't want to test those classes !
				continue;
			}
			if (is_subclass_of($sClass, User::class)) {
				$aUserClasses[] = $sClass;
			}
		}

		$oConfig = MetaModel::GetConfig();
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// Default behavior: Administrators, Administrator profile and URP_UserProfile related to administrators are visible
		// via GetSelectFilter

		$oConfig->Set('security.hide_administrators', false);

		$oFilterProfiles = $oUserRights->GetSelectFilter($this->oUser, URP_Profiles::class);
		if ($oFilterProfiles === true)
		{
			$oFilterProfiles = new DBObjectSearch(URP_Profiles::class);
		}
		$oSet = new DBObjectSet($oFilterProfiles);
		$bAdminProfileFound = false;
		while($oProfile = $oSet->Fetch())
		{
			if ($oProfile->GetKey() == 1)
			{
				$bAdminProfileFound = true;
				break;
			}
		}
		$this->assertEquals($bAdminProfileFound, true);

		foreach($aUserLocalAncestors as $sUserClass)
		{
			$bAdminUserFound = false;
			$oFilterUser = $oUserRights->GetSelectFilter($this->oUser,$sUserClass);
			if ($oFilterUser === true)
			{
				$oFilterUser = new DBObjectSearch($sUserClass);
			}
			$oSet = new DBObjectSet($oFilterUser);
			while($oUser = $oSet->Fetch())
			{
				if($oUser->GetKey() == $this->oUser->GetKey())
				{
					$bAdminUserFound = true;
					break;
				}
			}
			$this->assertEquals($bAdminUserFound, true);
		}

		$oFilterLnkProfiles = $oUserRights->GetSelectFilter($this->oUser, URP_UserProfile::class);
		if ($oFilterLnkProfiles === true)
		{
			$oFilterLnkProfiles = new DBObjectSearch(URP_UserProfile::class);
		}
		$oSet = new DBObjectSet($oFilterLnkProfiles);
		// There should some lnk referencing either our administrator account or the Administrator profile
		$bUserFound = false;
		$bProfileFound = false;
		while($oLnk = $oSet->Fetch())
		{
			if($oLnk->Get('userid') == $this->oUser->GetKey())
			{
				$bUserFound = true;
			}
			if($oLnk->Get('profileid') == 1)
			{
				$bProfileFound = true;
			}
		}
		$this->assertEquals($bUserFound, true);
		$this->assertEquals($bProfileFound, true);


		//////////////////////////////////////////////////////////////////////////////////////////////////////
		// Administrator account, Administrator profile and URP_UserProfile related to administrators are now hidden
		// via GetSelectFilter
		$oConfig->Set('security.hide_administrators', true);

		$oFilterProfiles = $oUserRights->GetSelectFilter($this->oUser, URP_Profiles::class);
		$this->assertNotEquals($oFilterProfiles, true); // This class must be filtered
		$oSet = new DBObjectSet($oFilterProfiles);
		while($oProfile = $oSet->Fetch())
		{
			$this->assertNotEquals($oProfile->GetKey(), 1); // No profile should have id = 1 (Administrator)
		}
		foreach($aUserClasses as $sUserClass)
		{
			$oFilterUser = $oUserRights->GetSelectFilter($this->oUser, $sUserClass);
			$this->assertNotEquals($oFilterUser,true); // This class must be filtered
			$oSet = new DBObjectSet($oFilterUser);
			while($oUser = $oSet->Fetch())
			{
				$this->assertNotEquals($oUser->GetKey(), $this->oUser->GetKey()); // Our administrator account should not be visible
			}
		}

		$oFilterLnkProfiles = $oUserRights->GetSelectFilter($this->oUser, URP_UserProfile::class);
		$this->assertNotEquals($oFilterLnkProfiles, true); // This class must be filtered
		$oSet = new DBObjectSet($oFilterLnkProfiles);
		// There should be no lnk referencing either our administrator account or the profile Administrator
		while($oLnk = $oSet->Fetch())
		{
			$this->assertNotEquals($oLnk->Get('userid'), $this->oUser->GetKey());
			$this->assertNotEquals($oLnk->Get('profileid'), 1);
		}

	}
}

<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Service\TemporaryObjects;

use Combodo\iTop\Service\TemporaryObjects\TemporaryObjectConfig;
use Combodo\iTop\Service\TemporaryObjects\TemporaryObjectHelper;
use Combodo\iTop\Service\TemporaryObjects\TemporaryObjectManager;
use Combodo\iTop\Service\TemporaryObjects\TemporaryObjectRepository;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use DBObject;

class TemporaryObjectRepositoryTest extends ItopDataTestCase
{
	const USE_TRANSACTION = true;
	const CREATE_TEST_ORG = false;

	private TemporaryObjectConfig $oTemporaryObjectConfig;

	protected function setUp(): void
	{
		parent::setUp();

		$this->oTemporaryObjectConfig = TemporaryObjectConfig::GetInstance();
	}

	public function testSearchByExpired()
	{
		$sTempId = 'testSearchByExpired';

		$oOrg = $this->CreateTestOrganization();
		$oRepository = TemporaryObjectRepository::GetInstance();
		$this->CreateTemporaryObject($sTempId, $oOrg, 3000);
		$oObjectSet = $oRepository->SearchByExpired();
		$this->assertEquals(0, $oObjectSet->Count());

		$this->CreateTemporaryObject($sTempId, $oOrg, -1);
		$oObjectSet = $oRepository->SearchByExpired();
		$this->assertEquals(1, $oObjectSet->Count());

		$oOrg = $this->CreateTestOrganization();
		$this->CreateTemporaryObject($sTempId, $oOrg, -1);
		$oObjectSet = $oRepository->SearchByExpired();
		$this->assertEquals(2, $oObjectSet->Count());
	}

	public function testSearchByTempId()
	{
		$sTempId = 'testSearchByTempId';

		// First temp object
		$oOrg = $this->CreateTestOrganization();
		$oDescriptor = $this->CreateTemporaryObject($sTempId, $oOrg, 3000);
		$oRepository = TemporaryObjectRepository::GetInstance();
		$oObjectSet = $oRepository->SearchByTempId($sTempId);
		$this->assertEquals(1, $oObjectSet->Count());
		$oDBObject = $oObjectSet->Fetch();
		$this->assertEquals($oDescriptor->GetKey(), $oDBObject->GetKey());
		$this->assertEquals(get_class($oDescriptor), get_class($oDBObject));

		// Second temp object
		$oOrg = $this->CreateTestOrganization();
		$this->CreateTemporaryObject($sTempId, $oOrg, 3000);
		$oObjectSet = $oRepository->SearchByTempId($sTempId);
		$this->assertEquals(2, $oObjectSet->Count());
	}

	public function testCountTemporaryObjectsByTempId()
	{
		$sTempId = 'testCountTemporaryObjectsByTempId';

		// First temp object
		$oOrg = $this->CreateTestOrganization();
		$this->CreateTemporaryObject($sTempId, $oOrg, 3000);
		$oRepository = TemporaryObjectRepository::GetInstance();
		$iCount = $oRepository->CountTemporaryObjectsByTempId($sTempId);
		$this->assertEquals(1, $iCount);

		// Second temp object
		$oOrg = $this->CreateTestOrganization();
		$this->CreateTemporaryObject($sTempId, $oOrg, 3000);
		$iCount = $oRepository->CountTemporaryObjectsByTempId($sTempId);
		$this->assertEquals(2, $iCount);
	}

	private function CreateTemporaryObject($sTempId, DBObject $oDBObject, int $iLifetime)
	{
		$this->oTemporaryObjectConfig->SetConfigTemporaryLifetime($iLifetime);
		$this->oTemporaryObjectConfig->SetConfigTemporaryForce(true);

		$oManager = TemporaryObjectManager::GetInstance();

		return $oManager->CreateTemporaryObject($sTempId, get_class($oDBObject), $oDBObject->GetKey(), TemporaryObjectHelper::OPERATION_CREATE);
	}
}

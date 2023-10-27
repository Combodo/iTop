<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Service\TemporaryObjects;

use Combodo\iTop\Service\TemporaryObjects\TemporaryObjectConfig;
use Combodo\iTop\Service\TemporaryObjects\TemporaryObjectHelper;
use Combodo\iTop\Service\TemporaryObjects\TemporaryObjectManager;
use Combodo\iTop\Service\TemporaryObjects\TemporaryObjectRepository;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;

class TemporaryObjectManagerTest extends ItopDataTestCase
{
	const USE_TRANSACTION = true;
	const CREATE_TEST_ORG = false;

	private TemporaryObjectConfig $oConfig;
	private $oManager;

	protected function setUp(): void
	{
		parent::setUp();

		$this->oConfig = TemporaryObjectConfig::GetInstance();
		$this->oManager = TemporaryObjectManager::GetInstance();
	}

	public function testCreateTemporaryObject()
	{
		$sTempId = 'testCreateTemporaryObject';
		$this->oConfig->SetConfigTemporaryLifetime(3000);
		$this->oConfig->SetConfigTemporaryForce(true);

		$oDescriptor = $this->oManager->CreateTemporaryObject($sTempId, 'FakedClass', -1, TemporaryObjectHelper::OPERATION_CREATE);

		$this->assertNull( $oDescriptor);

		$oOrg = $this->CreateTestOrganization();
		$oDescriptor = $this->CreateTemporaryObject($sTempId, $oOrg, 3000, TemporaryObjectHelper::OPERATION_CREATE);

		$this->assertNotNull( $oDescriptor);
	}

	public function testCancelAllTemporaryObjects()
	{
		$sTempId = 'testCancelAllTemporaryObjects';
		$oRepository = TemporaryObjectRepository::GetInstance();

		$oOrg = $this->CreateTestOrganization();
		$this->CreateTemporaryObject($sTempId, $oOrg, 3000, TemporaryObjectHelper::OPERATION_CREATE);
		$this->assertEquals(1, $oRepository->CountTemporaryObjectsByTempId($sTempId));

		$this->oManager->CancelAllTemporaryObjects($sTempId);
		$this->assertEquals(0, $oRepository->CountTemporaryObjectsByTempId($sTempId));

		$oOrg = $this->CreateTestOrganization();
		$this->CreateTemporaryObject($sTempId, $oOrg, 3000, TemporaryObjectHelper::OPERATION_CREATE);
		$oOrg = $this->CreateTestOrganization();
		$this->CreateTemporaryObject($sTempId, $oOrg, 3000, TemporaryObjectHelper::OPERATION_CREATE);
		$this->assertEquals(2, $oRepository->CountTemporaryObjectsByTempId($sTempId));

		$this->oManager->CancelAllTemporaryObjects($sTempId);
		$this->assertEquals(0, $oRepository->CountTemporaryObjectsByTempId($sTempId));
	}

	public function testExtendTemporaryObjectsLifetime()
	{
		$sTempId = 'testExtendTemporaryObjectsLifetime';
		$oRepository = TemporaryObjectRepository::GetInstance();

		$oOrg = $this->CreateTestOrganization();
		$this->CreateTemporaryObject($sTempId, $oOrg, -1, TemporaryObjectHelper::OPERATION_CREATE);
		$this->assertEquals(1, $oRepository->CountTemporaryObjectsByTempId($sTempId));
		$this->assertEquals(1, $oRepository->SearchByExpired()->Count());

		$this->oConfig->SetConfigTemporaryLifetime(3000);
		$this->oManager->ExtendTemporaryObjectsLifetime($sTempId);
		$this->assertEquals(0, $oRepository->SearchByExpired()->Count());
	}

	public function testGarbageExpiredTemporaryObjects()
	{
		$sTempId = 'testGarbageExpiredTemporaryObjects';
		$oRepository = TemporaryObjectRepository::GetInstance();

		$oOrg = $this->CreateTestOrganization();
		$this->CreateTemporaryObject($sTempId, $oOrg, -1, TemporaryObjectHelper::OPERATION_CREATE);
		$this->assertEquals(1, $oRepository->CountTemporaryObjectsByTempId($sTempId));
		$this->assertEquals(1, $oRepository->SearchByExpired()->Count());

		$this->oManager->GarbageExpiredTemporaryObjects();
		$this->assertEquals(0, $oRepository->CountTemporaryObjectsByTempId($sTempId));

		$oOrg = $this->CreateTestOrganization();
		$this->CreateTemporaryObject($sTempId, $oOrg, -1, TemporaryObjectHelper::OPERATION_CREATE);
		$oOrg = $this->CreateTestOrganization();
		$this->CreateTemporaryObject($sTempId, $oOrg, -1, TemporaryObjectHelper::OPERATION_CREATE);
		$this->assertEquals(2, $oRepository->SearchByExpired()->Count());

		$this->oManager->GarbageExpiredTemporaryObjects();
		$this->assertEquals(0, $oRepository->CountTemporaryObjectsByTempId($sTempId));

		$this->oManager->GarbageExpiredTemporaryObjects();
		$this->assertEquals(0, $oRepository->CountTemporaryObjectsByTempId($sTempId));
	}

	public function testHandleCreatedTemporaryObjects()
	{
		$sTempId = 'testHandleTemporaryObjects';
		$oRepository = TemporaryObjectRepository::GetInstance();

		$oOrg = $this->CreateTestOrganization();
		$oOrgTemp = $this->CreateTestOrganization();
		$oOrg->Set('parent_id', $oOrgTemp->GetKey());
		$oOrg->DBUpdate();

		$aContext = ['create' => ['transaction_id' => $sTempId, 'host_class' => get_class($oOrg), 'host_att_code' => 'parent_id',]];
		$this->oConfig->SetConfigTemporaryForce(true);
		$this->oConfig->SetConfigTemporaryLifetime(3000);

		$this->assertEquals(0, $oRepository->CountTemporaryObjectsByTempId($sTempId));
		$this->oManager->HandleTemporaryObjects($oOrg, $aContext);
		$this->assertEquals(1, $oRepository->CountTemporaryObjectsByTempId($sTempId));

		$aContext = ['finalize' => ['transaction_id' => $sTempId,]];
		$this->oManager->HandleTemporaryObjects($oOrg, $aContext);
		$this->assertEquals(0, $oRepository->CountTemporaryObjectsByTempId($sTempId));
	}

	public function testHandleDeletedTemporaryObjects()
	{
		$sTempId = 'testHandleTemporaryObjectsDelete';
		$oRepository = TemporaryObjectRepository::GetInstance();

		$oOrg = $this->CreateTestOrganization();
		$oOrgTemp = $this->CreateTestOrganization();
		$oOrg->Set('parent_id', $oOrgTemp->GetKey());
		$oOrg->DBUpdate();

		// Create a temporary delete
		$this->assertEquals(0, $oRepository->CountTemporaryObjectsByTempId($sTempId));
		$oTemporaryObjectDescriptor = TemporaryObjectManager::GetInstance()->CreateTemporaryObject($sTempId, get_class($oOrgTemp), $oOrgTemp->Get('id'), TemporaryObjectHelper::OPERATION_DELETE);
		$oTemporaryObjectDescriptor->Set('host_class', get_class($oOrg));
		$oTemporaryObjectDescriptor->Set('host_id', $oOrg->GetKey());
		$oTemporaryObjectDescriptor->Set('host_att_code', 'parent_id');
		$oTemporaryObjectDescriptor->DBUpdate();
		$this->assertEquals(1, $oRepository->CountTemporaryObjectsByTempId($sTempId));

		$aContext = ['finalize' => ['transaction_id' => $sTempId,]];
		$this->oManager->HandleTemporaryObjects($oOrg, $aContext);
		$this->assertEquals(0, $oRepository->CountTemporaryObjectsByTempId($sTempId));
		$oDeletedObject = \MetaModel::GetObject(get_class($oOrgTemp), $oOrgTemp->Get('id'), false);
		$this->assertNull($oDeletedObject);
	}


	private function CreateTemporaryObject($sTempId, $oDBObject, int $iLifetime, string $sOperation)
	{
		$this->oConfig->SetConfigTemporaryLifetime($iLifetime);
		$this->oConfig->SetConfigTemporaryForce(true);

		return $this->oManager->CreateTemporaryObject($sTempId, get_class($oDBObject), $oDBObject->GetKey(), $sOperation);
	}
}

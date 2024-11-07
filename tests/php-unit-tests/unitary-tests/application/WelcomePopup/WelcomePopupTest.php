<?php

use Combodo\iTop\Application\WelcomePopup\Message;
use Combodo\iTop\Application\WelcomePopup\WelcomePopupService;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;

class WelcomePopupTest extends ItopDataTestCase
{
	/**
	 * @dataProvider sortOnImportanceDataProvider
	 */
	public function testSortOnImportance($aToSort, $aExpected)
	{
		$aProvidersMessagesData = [];
		foreach ($aToSort as $aMessageData) {
			$aProvidersMessagesData[] = [
				'message' => new Message($aMessageData['id'], $aMessageData['title'], '', null, [], $aMessageData['importance']),
			];
		}

		$bResult = usort($aProvidersMessagesData, [WelcomePopupService::class, 'SortOnImportance']);
		$this->assertTrue($bResult);

		$aMessageIdsSorted = array_map(function($aItem) {
			return $aItem['message']->GetId();
		}, $aProvidersMessagesData);
		$this->assertEquals($aExpected, $aMessageIdsSorted);
	}
	
	/**
	 * Data provider for testSortOnImportance
	 * @return array[][]|string[][][][]|number[][][][]
	 */
	public function sortOnImportanceDataProvider()
	{
		return [
			'empty array' => [
				'to-sort' => [],
				'expected' => [],
			],
			'3-item array' => [
				'to-sort' => [
					['id' => 'aa1', 'title' => 'AA1', 'importance' => 0 /*iWelcomePopupExtension::ENUM_IMPORTANCE_CRITICAL*/],
					['id' => 'aa2', 'title' => 'AA2', 'importance' => 1 /*iWelcomePopupExtension::ENUM_IMPORTANCE_HIGH*/],
					['id' => 'aa3', 'title' => 'AA3', 'importance' => 0 /*iWelcomePopupExtension::ENUM_IMPORTANCE_CRITICAL*/],
				],
				'expected' => [
					'aa1',
					'aa3',
					'aa2',
				],
			],
			'5-item array' => [
				'to-sort' => [
					['id' => 'aa1', 'title' => 'AA1', 'importance' => 0 /*iWelcomePopupExtension::ENUM_IMPORTANCE_CRITICAL*/],
					['id' => 'aa2', 'title' => 'AA2', 'importance' => 1 /*iWelcomePopupExtension::ENUM_IMPORTANCE_HIGH*/],
					['id' => 'aa3', 'title' => 'AA3', 'importance' => 0 /*iWelcomePopupExtension::ENUM_IMPORTANCE_CRITICAL*/],
					['id' => 'zz1', 'title' => 'ZZ1', 'importance' => 0 /*iWelcomePopupExtension::ENUM_IMPORTANCE_CRITICAL*/],
					['id' => 'zz2', 'title' => 'ZZ2', 'importance' => 1 /*iWelcomePopupExtension::ENUM_IMPORTANCE_HIGH*/],
				],
				'expected' => [
					'aa1',
					'aa3',
					'zz1',
					'aa2',
					'zz2',
				],
			],
		];
	}

	/**
	 * @dataProvider isMessageAcknowledgedDataProvider
	 */
	public function testIsMessageAcknowledged($sMessageId, $aCache, $bExpected)
	{
		$oService = WelcomePopupService::GetInstance();
		$this->InvokeNonPublicMethod(WelcomePopupService::class, 'SetAcknowledgedMessagesCache', $oService, [$aCache]);
		
		$this->assertEquals($bExpected, $this->InvokeNonPublicMethod(WelcomePopupService::class, 'IsMessageAcknowledged', $oService, [$sMessageId]));
	}
	
	public function isMessageAcknowledgedDataProvider()
	{
		return [
			'empty-cache' => [
				'123', [], false,
			],
			'acknowledged' => [
				'123', ['123'], true,
			],
			'non-acknowledged' => [
				'456', ['123'], false,
			],
		];
	}
	
	public function testProcessMessages()
	{
		// Mock a WelcomePopup message provider, with a fixed class name
		$oProvider1 = $this->getMockBuilder(iWelcomePopupExtension::class)->setMockClassName('Provider1')->getMock();
		$oProvider1->expects($this->once())->method('GetMessages')->willReturn([
			new Message('123', 'foo', '<p>Hello Foo</p>', null, [], 0),
			new Message('456', 'bar', '<p>Hello Bar</p>', null, [], 1), // Already acknowledged will be skipped
		]);
		
		// Mock another WelcomePopup message provider, with a different class name
		$oProvider2 = $this->getMockBuilder(iWelcomePopupExtension::class)->setMockClassName('Provider2')->getMock();
		$oProvider2->expects($this->once())->method('GetMessages')->willReturn([
			new Message('789', 'Ga', '<p>Hello Ga</p>', null, [], 1),
			new Message('012', 'Bu', '', null, [], 0, 'ga/bu/zo'),
		]);
		$oService = WelcomePopupService::GetInstance();
		$this->InvokeNonPublicMethod(WelcomePopupService::class, 'SetAcknowledgedMessagesCache', $oService, [[get_class($oProvider1).'::456']]);
		$this->InvokeNonPublicMethod(WelcomePopupService::class, 'SetMessagesProviders', $oService, [[$oProvider1, $oProvider2]]);
		
		$aProvidersMessagesData = $this->InvokeNonPublicMethod(WelcomePopupService::class, 'ProcessMessages', $oService, []);
		$this->assertEquals(
			[
				[
					'uuid' => 'Provider2::012',
					'message' => new Message('012', 'Bu', '', null, [], 0, 'ga/bu/zo'),
					'provider_icon_rel_path' => '',
				],
				[
					'uuid' => 'Provider1::123',
					'message' => new Message('123', 'foo', '<p>Hello Foo</p>', null, [], 0),
					'provider_icon_rel_path' => '',
				],
				[
					'uuid' => 'Provider2::789',
					'message' => new Message('789', 'Ga', '<p>Hello Ga</p>', null, [], 1),
					'provider_icon_rel_path' => '',
				],
			],
			$aProvidersMessagesData
		);
	}

	public function testAcknowledgeMessage()
	{
		self::CreateUser('admin-testAcknowledgeMessage', 1, '-Passw0rd!Complex-');
		UserRights::Login('admin-testAcknowledgeMessage');
		
		// Mock a WelcomePopup message provider, with a fixed class name
		$oProvider1 = $this->getMockBuilder(iWelcomePopupExtension::class)->setMockClassName('Provider1')->getMock();
		$oProvider1->expects($this->exactly(2))->method('AcknowledgeMessage');
			
		// Mock another WelcomePopup message provider, with a different class name
		$oProvider2 = $this->getMockBuilder(iWelcomePopupExtension::class)->setMockClassName('Provider2')->getMock();
		$oProvider2->expects($this->exactly(1))->method('AcknowledgeMessage');

		$sMessageUUID1 = get_class($oProvider1).'::0123456';
		$sMessageUUID2 = get_class($oProvider1).'::456789';
		$sMessageUUID3 = get_class($oProvider2).'::456789'; // Same message id but different provider / UUID
		$oService = WelcomePopupService::GetInstance();
		
		$this->InvokeNonPublicMethod(WelcomePopupService::class, 'SetMessagesProviders', $oService, [[$oProvider1, $oProvider2]]);
		
		$oService->AcknowledgeMessage($sMessageUUID1);
		$this->assertTrue($this->InvokeNonPublicMethod(WelcomePopupService::class, 'IsMessageAcknowledged', $oService, [$sMessageUUID1]));
		$this->assertFalse($this->InvokeNonPublicMethod(WelcomePopupService::class, 'IsMessageAcknowledged', $oService, ['-This-Message-Id-Is-Not-Ack0ledg3dged!']));
		$this->assertFalse($this->InvokeNonPublicMethod(WelcomePopupService::class, 'IsMessageAcknowledged', $oService, [$sMessageUUID3]));
		
		$oService->AcknowledgeMessage($sMessageUUID2);
		$this->assertTrue($this->InvokeNonPublicMethod(WelcomePopupService::class, 'IsMessageAcknowledged', $oService, [$sMessageUUID1]));
		$this->assertTrue($this->InvokeNonPublicMethod(WelcomePopupService::class, 'IsMessageAcknowledged', $oService, [$sMessageUUID2]));
		$this->assertFalse($this->InvokeNonPublicMethod(WelcomePopupService::class, 'IsMessageAcknowledged', $oService, ['-This-Message-Id-Is-Not-Ack0ledg3dged!']));
		$this->assertFalse($this->InvokeNonPublicMethod(WelcomePopupService::class, 'IsMessageAcknowledged', $oService, [$sMessageUUID3]));
		
		$oService->AcknowledgeMessage($sMessageUUID3);
		$this->assertTrue($this->InvokeNonPublicMethod(WelcomePopupService::class, 'IsMessageAcknowledged', $oService, [$sMessageUUID3]));
	}
	
	/**
	 * @dataProvider makeStringFitInProvider
	 */
	public function testMakeStringFitIn($sInput, $iLimit, $sExpected)
	{
		$oService = WelcomePopupService::GetInstance();
		$sFitted = $this->InvokeNonPublicMethod(WelcomePopupService::class, 'MakeStringFitIn', $oService, [$sInput, $iLimit]);
		$this->assertTrue(mb_strlen($sFitted) <= $iLimit);
		$this->assertEquals($sExpected, $sFitted);
	}
	
	public function makeStringFitInProvider()
	{
		return [
			'Simple (no truncation)' => ['/Some/Short/EnoughName', 50, '/Some/Short/EnoughName'],
			'Very long (truncated)' => ['/Some/Very/Loooooooooooooooooooooooooooong/Naaaaaaaaaaaaaaaaaaaaaaaaaame', 50, '4769a98d57a0f2e9b99483f780833faf-aaaaaaaaaaaaaaame'],
			'Long More aggressive truncation' => ['/Some/Very/Loooooooooooooooooooooooooooong/Naaaaaaaaaaaaaaaaaaaaaaaaaame', 45, '4769a98d57a0f2e9b99483f780833faf-aaaaaaaaaame'],
		];
	}

}


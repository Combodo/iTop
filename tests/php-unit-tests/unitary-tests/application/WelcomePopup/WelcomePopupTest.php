<?php
use Combodo\iTop\Application\WelcomePopup\WelcomePopupService;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class WelcomePopupTest extends ItopDataTestCase
{
	/**
	 * @dataProvider sortOnImportanceDataProvider
	 */
	public function testSortOnImportance($aToSort, $aExpected)
	{
		$bResult = usort($aToSort, [WelcomePopupService::class, 'SortOnImportance']);
		$this->assertTrue($bResult);
		$this->assertEquals($aExpected, $aToSort);
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
					['id' => 'aa1', 'title' => 'AA1', 'importance' => 0 /*iWelcomePopup::IMPORTANCE_CRITICAL*/],
					['id' => 'aa2', 'title' => 'AA2', 'importance' => 1 /*iWelcomePopup::IMPORTANCE_HIGH*/],
					['id' => 'aa3', 'title' => 'AA3', 'importance' => 0 /*iWelcomePopup::IMPORTANCE_CRITICAL*/],
				],
				'expected' => [
					['id' => 'aa1', 'title' => 'AA1', 'importance' => 0 /*iWelcomePopup::IMPORTANCE_CRITICAL*/],
					['id' => 'aa3', 'title' => 'AA3', 'importance' => 0 /*iWelcomePopup::IMPORTANCE_CRITICAL*/],
					['id' => 'aa2', 'title' => 'AA2', 'importance' => 1 /*iWelcomePopup::IMPORTANCE_HIGH*/],
				],
			],
			'5-item array' => [
				'to-sort' => [
					['id' => 'aa1', 'title' => 'AA1', 'importance' => 0 /*iWelcomePopup::IMPORTANCE_CRITICAL*/],
					['id' => 'aa2', 'title' => 'AA2', 'importance' => 1 /*iWelcomePopup::IMPORTANCE_HIGH*/],
					['id' => 'aa3', 'title' => 'AA3', 'importance' => 0 /*iWelcomePopup::IMPORTANCE_CRITICAL*/],
					['id' => 'zz1', 'title' => 'ZZ1', 'importance' => 0 /*iWelcomePopup::IMPORTANCE_CRITICAL*/],
					['id' => 'zz2', 'title' => 'ZZ2', 'importance' => 1 /*iWelcomePopup::IMPORTANCE_HIGH*/],
				],
				'expected' => [
					['id' => 'aa1', 'title' => 'AA1', 'importance' => 0 /*iWelcomePopup::IMPORTANCE_CRITICAL*/],
					['id' => 'aa3', 'title' => 'AA3', 'importance' => 0 /*iWelcomePopup::IMPORTANCE_CRITICAL*/],
					['id' => 'zz1', 'title' => 'ZZ1', 'importance' => 0 /*iWelcomePopup::IMPORTANCE_CRITICAL*/],
					['id' => 'aa2', 'title' => 'AA2', 'importance' => 1 /*iWelcomePopup::IMPORTANCE_HIGH*/],
					['id' => 'zz2', 'title' => 'ZZ2', 'importance' => 1 /*iWelcomePopup::IMPORTANCE_HIGH*/],
				],
			],
		];
	}

	/**
	 * @dataProvider isMessageAcknowledgedDataProvider
	 */
	public function testIsMessageAcknowledged($sMessageId, $aCache, $bExpected)
	{
		$oService = new WelcomePopupService();
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

	/**
	 * @dataProvider isMessageValidDataProvider
	 */
	public function testIsMessageValid($aMessage, $bExpected)
	{
		$oService = new WelcomePopupService();
		$aReasons = [];
		$bResult = $this->InvokeNonPublicMethod(WelcomePopupService::class, 'IsMessageValid', $oService, [$aMessage, &$aReasons]);
		if ($bResult !== $bExpected) {
			print_r($aReasons);
		}
		$this->assertEquals($bExpected, $bResult);
		if ($bResult) {
			$this->assertEquals(0, count($aReasons));
		} else {
		   $this->assertNotEquals(0, count($aReasons));
		}
	}
	
	public function isMessageValidDataProvider()
	{
		return [
			'not an array' => [
				'123', false,
			],
			'empty array' => [
				[], false,
			],
			'missing id' => [
				['title' => 'foo', 'importance' => 0, 'html' => '<p>Hello</p>'], false,
			],
			'message Ok (html)' => [
				['id' => '123', 'title' => 'foo', 'importance' => 0, 'html' => '<p>Hello</p>'], true,
			],
			'message Ok (twig)' => [
				['id' => '123', 'title' => 'foo', 'importance' => 0, 'twig' => '/some/path'], true,
			],
			'missing html and twig' => [
				['id' => '123', 'title' => 'foo', 'importance' => 0], false,
			],
		];
	}
	
	public function testProcessMessages()
	{
		// Mock a WelcomePopup message provider, with a fixed class name
		$oProvider1 = $this->getMockBuilder(iWelcomePopup::class)->setMockClassName('Provider1')->getMock();
		$oProvider1->expects($this->once())->method('GetMessages')->willReturn([
			['id' => '123', 'title' => 'foo', 'importance' => 0, 'html' => '<p>Hello Foo</p>'],
			['id' => '456', 'title' => 'bar', 'importance' => 1, 'html' => '<p>Hello Bar</p>'], // Already acknowledged will be skipped
		]);
		
		// Mock another WelcomePopup message provider, with a different class name
		$oProvider2 = $this->getMockBuilder(iWelcomePopup::class)->setMockClassName('Provider2')->getMock();
		$oProvider2->expects($this->once())->method('GetMessages')->willReturn([
			['id' => '789', 'title' => 'Ga', 'importance' => 1, 'html' => '<p>Hello Ga</p>'],
			['id' => '012', 'title' => 'Bu', 'importance' => 0, 'twig' => 'ga/bu/zo'],
			['id' => '000', 'title' => 'Bu', 'importance' => 0], // Invalid, will be ignored
		]);
		$oService = new WelcomePopupService();
		$this->InvokeNonPublicMethod(WelcomePopupService::class, 'SetAcknowledgedMessagesCache', $oService, [[get_class($oProvider1).'::456']]);
		$this->InvokeNonPublicMethod(WelcomePopupService::class, 'SetMessagesProviders', $oService, [[$oProvider1, $oProvider2]]);
		
		$aMessages = $this->InvokeNonPublicMethod(WelcomePopupService::class, 'ProcessMessages', $oService, []);
		$this->assertEquals(
			[
				['id' => '012', 'title' => 'Bu', 'importance' => 0, 'twig' => 'ga/bu/zo', 'uuid' => 'Provider2::012'],
				['id' => '123', 'title' => 'foo', 'importance' => 0, 'html' => '<p>Hello Foo</p>', 'uuid' => 'Provider1::123'],
				['id' => '789', 'title' => 'Ga', 'importance' => 1, 'html' => '<p>Hello Ga</p>', 'uuid' => 'Provider2::789'],
			],
			$aMessages
		);
	}


	public function testAcknowledgeMessage()
	{
		self::CreateUser('admin-testAcknowledgeMessage', 1, '-Passw0rd!Complex-');
		UserRights::Login('admin-testAcknowledgeMessage');
		
		// Mock a WelcomePopup message provider, with a fixed class name
		$oProvider1 = $this->getMockBuilder(iWelcomePopup::class)->setMockClassName('Provider1')->getMock();
		$oProvider1->expects($this->exactly(2))->method('AcknowledgeMessage');
			
		// Mock another WelcomePopup message provider, with a different class name
		$oProvider2 = $this->getMockBuilder(iWelcomePopup::class)->setMockClassName('Provider2')->getMock();
		$oProvider2->expects($this->exactly(1))->method('AcknowledgeMessage');

		$sMessageUUID1 = get_class($oProvider1).'::0123456';
		$sMessageUUID2 = get_class($oProvider1).'::456789';
		$sMessageUUID3 = get_class($oProvider2).'::456789'; // Same message id but different provider / UUID
		$oService = new WelcomePopupService();
		
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
		$oService = new WelcomePopupService();
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


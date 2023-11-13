<?php

namespace Combodo\iTop\Test\UnitTest\SessionTracker;

use Combodo\iTop\Application\Helper\Session;
use Combodo\iTop\SessionTracker\SessionHandler;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use ContextTag;

class SessionHandlerTest extends ItopDataTestCase
{
	private $sFile ;
	protected function setUp(): void
	{
		parent::setUp();
		new ContextTag(ContextTag::TAG_REST);
	}

	protected function tearDown(): void
	{
		parent::tearDown();
		static::SetNonPublicStaticProperty(ContextTag::class, 'aStack', []);

		if (! is_null($this->sFile) && is_file($this->sFile)){
			@unlink($this->sFile);
		}
	}

	private function CreateUserAndLogIn() : ? string {
		$_SESSION = [];
		$oUser = $this->CreateContactlessUser("admin" . uniqid(), 1, "1234@Abcdefg");

		\UserRights::Login($oUser->Get('login'));
		return $oUser->GetKey();
	}

	private function GenerateSessionContent(SessionHandler $oSessionHandler, ?string $sPreviousFileVersionContent) : ?string {
		return $this->InvokeNonPublicMethod(SessionHandler::class, "generate_session_content", $oSessionHandler, $aArgs = [$sPreviousFileVersionContent]);
	}

	public function testGenerateSessionContentNoUserLoggedIn(){
		$oSessionHandler = new SessionHandler();
		$sContent = $this->GenerateSessionContent($oSessionHandler, null);
		$this->assertNull($sContent);
	}

	public function GenerateSessionContentCorruptedPreviousFileContentProvider() {
		return [
			'not a json' => [ "not a json" ],
			'not an array' => [ json_encode("not an array") ],
			'array without creation_time key' => [ json_encode([]) ],
		];
	}

	/**
	 * @dataProvider GenerateSessionContentCorruptedPreviousFileContentProvider
	 */
	public function testGenerateSessionContentCorruptedPreviousFileContent(?string $sPreviousFileVersionContent){
		$sUserId = $this->CreateUserAndLogIn();

		$oSessionHandler = new SessionHandler();
		Session::Set('login_mode', 'toto');

		$sContent = $this->GenerateSessionContent($oSessionHandler, $sPreviousFileVersionContent);

		$this->assertNotNull($sContent);
		$aJson = json_decode($sContent, true);
		$this->assertNotEquals(false, $aJson, $sContent);
		$this->assertEquals($sUserId, $aJson['user_id'] ?? '', $sContent);
		$this->assertEquals(ContextTag::TAG_REST, $aJson['context'] ?? '', $sContent);
		$this->assertNotEquals('', $aJson['creation_time'] ?? '', $sContent);
		$this->assertEquals('toto', $aJson['login_mode'] ?? '', $sContent);
	}

	public function testGenerateSessionContentNoSessionContextChange(){
		$sUserId = $this->CreateUserAndLogIn();

		$oSessionHandler = new SessionHandler();
		Session::Set('login_mode', 'toto');

		//first time
		$sFirstContent = $this->GenerateSessionContent($oSessionHandler, null);

		$this->assertNotNull($sFirstContent);
		$aJson = json_decode($sFirstContent, true);
		$this->assertNotEquals(false, $aJson, $sFirstContent);
		$this->assertEquals($sUserId, $aJson['user_id'] ?? '', $sFirstContent);
		$this->assertEquals(ContextTag::TAG_REST, $aJson['context'] ?? '', $sFirstContent);
		$this->assertNotEquals('', $aJson['creation_time'] ?? '', $sFirstContent);
		$this->assertEquals('toto', $aJson['login_mode'] ?? '', $sFirstContent);

		$sNewContent = $this->GenerateSessionContent($oSessionHandler, $sFirstContent);
		$this->assertEquals($sFirstContent, $sNewContent, $sNewContent);
	}

	public function testGenerateSessionContentWithSessionContextChange(){
		$sUserId = $this->CreateUserAndLogIn();

		$oSessionHandler = new SessionHandler();
		Session::Set('login_mode', 'toto');

		//first time
		$sFirstContent = $this->GenerateSessionContent($oSessionHandler, null);

		$this->assertNotNull($sFirstContent);
		$aJson = json_decode($sFirstContent, true);
		$this->assertNotEquals(false, $aJson, $sFirstContent);
		$this->assertEquals($sUserId, $aJson['user_id'] ?? '', $sFirstContent);
		$this->assertEquals(ContextTag::TAG_REST, $aJson['context'] ?? '', $sFirstContent);
		$sCreationTime = $aJson['creation_time'] ?? '';
		$this->assertNotEquals('', $sCreationTime, $sFirstContent);
		$this->assertEquals('toto', $aJson['login_mode'] ?? '', $sFirstContent);


		new ContextTag(ContextTag::TAG_SYNCHRO);
		$sNewContent = $this->GenerateSessionContent($oSessionHandler, $sFirstContent);
		$this->assertNotNull($sNewContent);
		$this->assertNotEquals($sNewContent, $sFirstContent, $sNewContent);

		$aJson = json_decode($sNewContent, true);
		$this->assertNotEquals(false, $aJson, $sFirstContent);
		$this->assertEquals($sUserId, $aJson['user_id'] ?? '', $sFirstContent);
		$this->assertEquals(ContextTag::TAG_REST . '|' . ContextTag::TAG_SYNCHRO, $aJson['context'] ?? '', $sFirstContent);
		$this->assertEquals($sCreationTime, $aJson['creation_time'] ?? '', $sFirstContent);
		$this->assertEquals('toto', $aJson['login_mode'] ?? '', $sFirstContent);
	}

	private function touchSessionFile(SessionHandler $oSessionHandler, $session_id) : ?string {
		$sRes = $this->InvokeNonPublicMethod(SessionHandler::class, "touch_session_file", $oSessionHandler, $aArgs = [$session_id]);
		clearstatcache();
		return $sRes;
	}

	public function testTouchSessionFileNoUserLoggedIn(){
		$oSessionHandler = new SessionHandler();
		$session_id = uniqid();
		$this->sFile = $this->touchSessionFile($oSessionHandler, $session_id);
		$this->assertEquals(true, is_file($this->sFile), $this->sFile);
		$sContent = file_get_contents($this->sFile);
		$this->assertEquals(null, $sContent);
	}

	public function testTouchSessionFile_UserLoggedIn(){
		$sUserId = $this->CreateUserAndLogIn();
		Session::Set('login_mode', 'toto');

		$oSessionHandler = new SessionHandler();
		$session_id = uniqid();
		$this->sFile = $this->touchSessionFile($oSessionHandler, $session_id);
		$this->assertEquals(true, is_file($this->sFile), $this->sFile);
		$sContent = file_get_contents($this->sFile);

		$this->assertNotNull($sContent);
		$aJson = json_decode($sContent, true);
		$this->assertNotEquals(false, $aJson, $sContent);
		$this->assertEquals($sUserId, $aJson['user_id'] ?? '', $sContent);
		$this->assertEquals(ContextTag::TAG_REST, $aJson['context'] ?? '', $sContent);
		$sCreationTime = $aJson['creation_time'] ?? '';
		$this->assertNotEquals('', $sCreationTime, $sContent);
		$this->assertEquals('toto', $aJson['login_mode'] ?? '', $sContent);

		$this->touchSessionFile($oSessionHandler, $session_id);
		$sNewContent = file_get_contents($this->sFile);
		$this->assertEquals($sContent, $sNewContent, $sNewContent);
	}

	public function testTouchSessionFile_UserLoggedInWithImpersonation(){
		$sUserId = $this->CreateUserAndLogIn();
		Session::Set('login_mode', 'toto');

		$oSessionHandler = new SessionHandler();
		$session_id = uniqid();
		$this->sFile = $this->touchSessionFile($oSessionHandler, $session_id);
		$this->assertEquals(true, is_file($this->sFile), $this->sFile);
		$sContent = file_get_contents($this->sFile);

		$this->assertNotNull($sContent);
		$aJson = json_decode($sContent, true);
		$this->assertNotEquals(false, $aJson, $sContent);
		$this->assertEquals($sUserId, $aJson['user_id'] ?? '', $sContent);
		$this->assertEquals(ContextTag::TAG_REST, $aJson['context'] ?? '', $sContent);
		$sCreationTime = $aJson['creation_time'] ?? '';
		$this->assertNotEquals('', $sCreationTime, $sContent);
		$this->assertEquals('toto', $aJson['login_mode'] ?? '', $sContent);


		$oOtherUser = $this->CreateContactlessUser("admin" . uniqid(), 1, "1234@Abcdefg");
		\UserRights::Impersonate($oOtherUser->Get('login'));
		$this->touchSessionFile($oSessionHandler, $session_id);
		$sNewContent = file_get_contents($this->sFile);
		$this->assertNotEquals($sContent, $sNewContent, $sNewContent);
		$aJson = json_decode($sNewContent, true);
		$this->assertNotEquals(false, $aJson, $sNewContent);
		$this->assertEquals($oOtherUser->GetKey(), $aJson['user_id'] ?? '', $sNewContent);
	}

	public function TouchSessionFile_empty_sessionidProvider(){
		return [
			'empty string' => [ "" ],
			'false' => [ false ],
		];
	}

	/**
	 * @dataProvider TouchSessionFile_empty_sessionidProvider
	 */
	public function testTouchSessionFile_empty_sessionid($session_id) {
		$this->CreateUserAndLogIn();
		Session::Set('login_mode', 'toto');

		$oSessionHandler = new SessionHandler();
		$this->sFile = $this->touchSessionFile($oSessionHandler, $session_id);
		$this->assertNull($this->sFile);
	}

	private function GetFilePath(SessionHandler $oSessionHandler, $session_id) : string {
		return $this->InvokeNonPublicMethod(SessionHandler::class, "get_file_path", $oSessionHandler, $aArgs = [$session_id]);
	}

	public function GgcWithTimeLimit_FileWithData(){
		return [
			'no removal / without time limit' => [
				'iTimeLimit' => -1,
				'max_lifetime' => 1440,
				'iExpectedProcessed' => 0
			],
			'one removal / with time limit' => [
				'iTimeLimit' => time() - 1,
				'max_lifetime' => -1,
				'iExpectedProcessed' => 1
			],
			'all removed / with time limit' => [
				'iTimeLimit' => -1,
				'max_lifetime' => -1,
				'iExpectedProcessed' => 2
			],
		];
	}

	/**
	 * @dataProvider GgcWithTimeLimit_FileWithData
	 */
	public function testGgcWithTimeLimit_FileWithData($iTimeLimit, $max_lifetime, $iExpectedProcessed) {
		$oSessionHandler = new SessionHandler();
		//remove all
		$oSessionHandler->gc_with_time_limit(-1);
		$this->assertEquals([], $oSessionHandler->list_session_files());

		for($i=0; $i<=1; $i++) {
			$this->sFile = $this->GetFilePath($oSessionHandler, uniqid());
			file_put_contents($this->sFile, "fakedata");
		}

		$aFoundSessionFiles = $oSessionHandler->list_session_files();
		foreach ($aFoundSessionFiles as $sFile){
			$this->assertTrue(is_file($sFile));
		}

		$iProcessed = $oSessionHandler->gc_with_time_limit($max_lifetime, $iTimeLimit);
		$this->assertEquals($iExpectedProcessed, $iProcessed);
		$this->assertEquals(2 - $iExpectedProcessed, sizeof($oSessionHandler->list_session_files()));
	}

	public function GgcWithTimeLimit_EmptyFile(){
		return [
			'no removal / without time limit' => [
				'iTimeLimit' => -1,
				'iExpectedProcessed' => 2
			],
			'one removal / with time limit' => [
				'iTimeLimit' => time() - 1,
				'iExpectedProcessed' => 1
			],
		];
	}

	/**
	 * @dataProvider GgcWithTimeLimit_EmptyFile
	 */
	public function testGgcWithTimeLimit_EmptyFile($iTimeLimit, $iExpectedProcessed) {
		$oSessionHandler = new SessionHandler();
		//remove all
		$oSessionHandler->gc_with_time_limit(-1);
		$this->assertEquals([], $oSessionHandler->list_session_files());

		for($i=0; $i<=1; $i++) {
			$this->sFile = $this->GetFilePath($oSessionHandler, uniqid());
			touch($this->sFile);
		}

		$aFoundSessionFiles = $oSessionHandler->list_session_files();
		foreach ($aFoundSessionFiles as $sFile){
			$this->assertTrue(is_file($sFile));
		}

		$iProcessed = $oSessionHandler->gc_with_time_limit(1440, $iTimeLimit);
		$this->assertEquals($iExpectedProcessed, $iProcessed);
		$this->assertEquals(2 - $iExpectedProcessed, sizeof($oSessionHandler->list_session_files()));
	}
}

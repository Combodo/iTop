<?php

namespace Combodo\iTop\Test\UnitTest\SessionTracker;

use Combodo\iTop\Application\Helper\Session;
use Combodo\iTop\SessionTracker\SessionHandler;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use ContextTag;

class SessionHandlerTest extends ItopDataTestCase
{
	private $aFiles ;
	private $oTag ;

	protected function setUp(): void
	{
		parent::setUp();
		$this->aFiles=[];
		$this->oTag = new ContextTag(ContextTag::TAG_REST);
	}

	protected function tearDown(): void
	{
		parent::tearDown();
		$this->oTag = null;

		foreach ($this->aFiles as $sFile){
			if (is_file($sFile)){
				@unlink($sFile);
			}
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

	/*
	 * @covers SessionHandler::generate_session_content
	 */
	public function testGenerateSessionContentNoUserLoggedIn(){
		$oSessionHandler = new SessionHandler();
		$sContent = $this->GenerateSessionContent($oSessionHandler, null);
		$this->assertNull($sContent, "Session content should be null when there is no user logged in");
	}

	public function GenerateSessionContentCorruptedPreviousFileContentProvider() {
		return [
			'not a json' => [ "not a json" ],
			'not an array' => [ json_encode("not an array") ],
			'array without creation_time key' => [ json_encode([]) ],
		];
	}

	/**
	 * @covers SessionHandler::generate_session_content
	 * @dataProvider GenerateSessionContentCorruptedPreviousFileContentProvider
	 */
	public function testGenerateSessionContent_SessionFileRepairment(?string $sFileContent){
		$sUserId = $this->CreateUserAndLogIn();

		$oSessionHandler = new SessionHandler();
		Session::Set('login_mode', 'foo_login_mode');

		$sContent = $this->GenerateSessionContent($oSessionHandler, $sFileContent);

		$this->assertNotNull($sContent, 'Should not return null');
		$aJson = json_decode($sContent, true);
		$this->assertNotEquals(false, $aJson, 'Should return a valid json string, found: '.$sContent);
		$this->assertEquals($sUserId, $aJson['user_id'] ?? '', "Should report the login of the logged in user in [user_id]: $sContent");
		$this->assertEquals(ContextTag::TAG_REST, $aJson['context'] ?? '', "Should report the context tag(s) in [context]: $sContent");
		$this->assertIsInt($aJson['creation_time'] ?? '', "Should report the session start timestamp in [creation_time]: $sContent");
		$this->assertEquals('foo_login_mode', $aJson['login_mode'] ?? '', "Should report the current login mode in [login_mode]: $sContent");
	}

	/*
	 * @covers SessionHandler::generate_session_content
	 */
	public function testGenerateSessionContent(){
		$sUserId = $this->CreateUserAndLogIn();

		$oSessionHandler = new SessionHandler();
		Session::Set('login_mode', 'foo_login_mode');

		//first time
		$sFirstContent = $this->GenerateSessionContent($oSessionHandler, null);

		$this->assertNotNull($sFirstContent, 'Should not return null');
		$aJson = json_decode($sFirstContent, true);
		$this->assertNotEquals(false, $aJson, 'Should return a valid json string, found: '.$sFirstContent);
		$this->assertEquals($sUserId, $aJson['user_id'] ?? '', "Should report the login of the logged in user in [user_id]: $sFirstContent");
		$this->assertEquals(ContextTag::TAG_REST, $aJson['context'] ?? '', "Should report the context tag(s) in [context]: $sFirstContent");
		$this->assertIsInt($aJson['creation_time'] ?? '', "Should report the session start timestamp in [creation_time]: $sFirstContent");
		$this->assertEquals('foo_login_mode', $aJson['login_mode'] ?? '', "Should report the current login mode in [login_mode]: $sFirstContent");

		$iFirstSessionCreationTime = $aJson['creation_time'];

		// Switch context + change user id via impersonation
		// check it is still tracked in session files
		$oOtherUser = $this->CreateContactlessUser("admin" . uniqid(), 1, "1234@Abcdefg");
		$this->assertTrue(\UserRights::Impersonate($oOtherUser->Get('login')), "Failed to execute impersonate on: ".$oOtherUser->Get('login'));
		$oTag2 = new ContextTag(ContextTag::TAG_SYNCHRO);
		$sNewContent = $this->GenerateSessionContent($oSessionHandler, $sFirstContent);
		$this->assertNotNull($sNewContent, 'Should not return null');
		$aJson = json_decode($sNewContent, true);
		$this->assertNotEquals(false, $aJson, 'Should return a valid json string, found: '.$sNewContent);
		$this->assertEquals(ContextTag::TAG_REST . '|' . ContextTag::TAG_SYNCHRO, $aJson['context'] ?? '', "After impersonation, should report the new context tags in [context]: $sNewContent");
		$this->assertEquals($iFirstSessionCreationTime, $aJson['creation_time'] ?? '', "After impersonation, should still report the the session start timestamp in [creation_time]: $sNewContent");
		$this->assertEquals('foo_login_mode', $aJson['login_mode'] ?? '', "After impersonation, should still report the login mode in [login_mode]: $sNewContent");
		$this->assertEquals($oOtherUser->GetKey(), $aJson['user_id'] ?? '', "Should report the impersonate user in [user_id]: $sNewContent");
	}

	private function touchSessionFile(SessionHandler $oSessionHandler, $session_id) : ?string {
		$sRes = $this->InvokeNonPublicMethod(SessionHandler::class, "touch_session_file", $oSessionHandler, $aArgs = [$session_id]);
		if (!is_null($sRes) && is_file($sRes)) {
			// Record the file for cleanup on tearDown
			$this->aFiles[] = $sRes;
		}
		clearstatcache();
		return $sRes;
	}

	/*
	 * @covers SessionHandler::touch_session_file
	 */
	public function testTouchSessionFile_NoUserLoggedIn(){
		$oSessionHandler = new SessionHandler();
		$session_id = uniqid();
		$sFile = $this->touchSessionFile($oSessionHandler, $session_id);
		$this->assertEquals(true, is_file($sFile), "Should return a file name: '$sFile' is not a valid file name");
		$sContent = file_get_contents($sFile);
		$this->assertEquals(null, $sContent, 'Should create an empty file, found: '.$sContent);
	}

	/*
	 * @covers SessionHandler::touch_session_file
	 */
	public function testTouchSessionFile_UserLoggedIn(){
		$sUserId = $this->CreateUserAndLogIn();
		Session::Set('login_mode', 'foo_login_mode');

		$oSessionHandler = new SessionHandler();
		$session_id = uniqid();
		$sFile = $this->touchSessionFile($oSessionHandler, $session_id);
		$this->assertEquals(true, is_file($sFile), "Should return a file name: '$sFile' is not a valid file name");
		$sFirstContent = file_get_contents($sFile);

		$iFirstCTime = filectime($sFile) - 1;
		// Set it in the past to check that it will be further updated (without the need to sleep...)
		touch($sFile, $iFirstCTime);

		$this->assertNotNull($sFirstContent, 'Should not return null');
		$aJson = json_decode($sFirstContent, true);
		$this->assertNotEquals(false, $aJson, 'Should return a valid json string, found: '.$sFirstContent);
		$this->assertEquals($sUserId, $aJson['user_id'] ?? '', "Should report the login of the logged in user in [user_id]: $sFirstContent");
		$this->assertEquals(ContextTag::TAG_REST, $aJson['context'] ?? '', "Should report the context tag(s) in [context]: $sFirstContent");
		$this->assertIsInt($aJson['creation_time'] ?? '', "Should report the session start timestamp in [creation_time]: $sFirstContent");
		$this->assertEquals('foo_login_mode', $aJson['login_mode'] ?? '', "Should report the current login mode in [login_mode]: $sFirstContent");

		$this->touchSessionFile($oSessionHandler, $session_id);
		$sNewContent = file_get_contents($sFile);
		$this->assertEquals($sFirstContent, $sNewContent, 'On successive calls, should not modify an existing session file');
		$this->assertGreaterThan($iFirstCTime, filectime($sFile), 'On successive calls, should have changed the file ctime');
	}

	/**
	 * @covers SessionHandler::touch_session_file
	 */
	public function testTouchSessionFileWithEmptySessionId() {
		$this->CreateUserAndLogIn();
		Session::Set('login_mode', 'toto');

		$oSessionHandler = new SessionHandler();
		$this->assertNull($this->touchSessionFile($oSessionHandler, ''), 'Should return null when session id is an empty string');
		$this->assertNull($this->touchSessionFile($oSessionHandler, false), 'Should return null when session id (boolean) false');
	}

	private function GetFilePath(SessionHandler $oSessionHandler, $session_id) : string {
		$sFile = $this->InvokeNonPublicMethod(SessionHandler::class, "get_file_path", $oSessionHandler, $aArgs = [$session_id]);
		// Record file for cleanup on tearDown
		$this->aFiles[] = $sFile;
		return $sFile;
	}

	public function GgcWithTimeLimitProvider(){
		return [
			'no cleanup time limit' => [
				'iTimeLimit' => -1,
				'iExpectedProcessed' => 2
			],
			'cleanup time limit in the pass => first file removed only' => [
				'iTimeLimit' => time() - 1,
				'iExpectedProcessed' => 1
			],
		];
	}

	/**
	 * @covers SessionHandler::gc_with_time_limit
	 * @covers SessionHandler::list_session_files
	 * @dataProvider GgcWithTimeLimitProvider
	 */
	public function testGgcWithTimeLimit($iTimeLimit, $iExpectedProcessed) {
		$oSessionHandler = new SessionHandler();
		//remove all first
		$oSessionHandler->gc_with_time_limit(-1);
		$this->assertEquals([], $oSessionHandler->list_session_files(), 'list_session_files should report no file at startup');

		$max_lifetime = 1440;
		$iNbExpiredFiles = 2;
		$iNbFiles = 5;
		$iExpiredTimeStamp = time() - $max_lifetime - 1;
		for($i=0; $i<$iNbFiles; $i++) {
			$sFile = $this->GetFilePath($oSessionHandler, uniqid());
			file_put_contents($sFile, "fakedata");

			if ($iNbExpiredFiles > 0){
				$iNbExpiredFiles--;
				touch($sFile, $iExpiredTimeStamp);
			}
		}

		$aFoundSessionFiles = $oSessionHandler->list_session_files();
		$this->assertEquals($iNbFiles, sizeof($aFoundSessionFiles), 'list_session_files should reports all files');
		foreach ($aFoundSessionFiles as $sFile){
			$this->assertTrue(is_file($sFile), 'list_session_files should return a valid file paths, found: '.$sFile);
		}

		$iProcessed = $oSessionHandler->gc_with_time_limit($max_lifetime, $iTimeLimit);
		$this->assertEquals($iExpectedProcessed, $iProcessed, 'gc_with_time_limit should report the count of expired files');
		$this->assertEquals($iNbFiles - $iExpectedProcessed, sizeof($oSessionHandler->list_session_files()), 'gc_with_time_limit should actually remove all processed files');
	}
}

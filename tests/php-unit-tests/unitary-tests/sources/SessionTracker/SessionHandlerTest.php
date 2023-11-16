<?php

namespace Combodo\iTop\Test\UnitTest\SessionTracker;

use Combodo\iTop\Application\Helper\Session;
use Combodo\iTop\SessionTracker\SessionHandler;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use ContextTag;

class SessionHandlerTest extends ItopDataTestCase
{
	private $sFiles ;
	private $oTag ;

	protected function setUp(): void
	{
		parent::setUp();
		$this->sFiles=[];
		$this->oTag = new ContextTag(ContextTag::TAG_REST);
	}

	protected function tearDown(): void
	{
		parent::tearDown();
		$this->oTag = null;

		foreach ($this->sFiles as $sFile){
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

	public function testGenerateSessionContentNoUserLoggedIn(){
		$oSessionHandler = new SessionHandler();
		$sContent = $this->GenerateSessionContent($oSessionHandler, null);
		$this->assertNull($sContent, "session content should be null when no authentification");
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
	public function testGenerateSessionContent_SessionFileRepairment(?string $sFileContent){
		$sUserId = $this->CreateUserAndLogIn();

		$oSessionHandler = new SessionHandler();
		Session::Set('login_mode', 'toto');

		$sContent = $this->GenerateSessionContent($oSessionHandler, $sFileContent);

		$this->assertNotNull($sContent, "authenticated session: not empty file");
		$aJson = json_decode($sContent, true);
		$this->assertNotEquals(false, $aJson, $sContent);
		$this->assertEquals($sUserId, $aJson['user_id'] ?? '', $sContent);
		$this->assertEquals(ContextTag::TAG_REST, $aJson['context'] ?? '', $sContent);
		$this->assertNotEquals('', $aJson['creation_time'] ?? '', "not empty timestamps: $sContent");
		$this->assertEquals('toto', $aJson['login_mode'] ?? '', "proper login_mode tracked: $sContent");
	}

	public function testGenerateSessionContent(){
		$sUserId = $this->CreateUserAndLogIn();

		$oSessionHandler = new SessionHandler();
		Session::Set('login_mode', 'toto');

		//first time
		$sFirstContent = $this->GenerateSessionContent($oSessionHandler, null);

		$this->assertNotNull($sFirstContent);
		$aJson = json_decode($sFirstContent, true);
		$this->assertNotEquals(false, $aJson, $sFirstContent);
		$this->assertEquals($sUserId, $aJson['user_id'] ?? '', "check user_id is tracked: $sFirstContent");
		$this->assertEquals(ContextTag::TAG_REST, $aJson['context'] ?? '', $sFirstContent);
		$sCreationTime = $aJson['creation_time'] ?? '';
		$this->assertNotEquals('', $sCreationTime, "not empty timestamps: $sFirstContent");
		$this->assertEquals('toto', $aJson['login_mode'] ?? '', "proper login_mode tracked: $sFirstContent");

		//switch context + change user id via impersonation
		//check it is still tracked in session files
		//$oOtherUserId = $this->CreateUserAndLogIn();

		$oOtherUser = $this->CreateContactlessUser("admin" . uniqid(), 1, "1234@Abcdefg");
		$this->assertTrue(\UserRights::Impersonate($oOtherUser->Get('login')), "is impersonated");
		$oTag2 = new ContextTag(ContextTag::TAG_SYNCHRO);
		$sNewContent = $this->GenerateSessionContent($oSessionHandler, $sFirstContent);
		$this->assertNotNull($sNewContent);
		$aJson = json_decode($sNewContent, true);
		$this->assertNotEquals(false, $aJson, $sFirstContent);
		$this->assertEquals(ContextTag::TAG_REST . '|' . ContextTag::TAG_SYNCHRO, $aJson['context'] ?? '', "check context changes are tracked: $sFirstContent");
		$this->assertEquals($sCreationTime, $aJson['creation_time'] ?? '', "data have changed but creation_time should be preserved: $sFirstContent");
		$this->assertEquals('toto', $aJson['login_mode'] ?? '', "login_mode preserved: $sFirstContent");
		$this->assertEquals($oOtherUser->GetKey(), $aJson['user_id'] ?? '', "check impersonation is tracked in session: $sFirstContent");
	}

	private function touchSessionFile(SessionHandler $oSessionHandler, $session_id) : ?string {
		$sRes = $this->InvokeNonPublicMethod(SessionHandler::class, "touch_session_file", $oSessionHandler, $aArgs = [$session_id]);
		clearstatcache();
		return $sRes;
	}

	public function testTouchSessionFileNoUserLoggedIn(){
		$oSessionHandler = new SessionHandler();
		$session_id = uniqid();
		$sFile = $this->touchSessionFile($oSessionHandler, $session_id);
		$this->sFiles[]=$sFile;
		$this->assertEquals(true, is_file($sFile), $sFile);
		$sContent = file_get_contents($sFile);
		$this->assertEquals(null, $sContent);
	}

	public function testTouchSessionFile_UserLoggedIn(){
		$sUserId = $this->CreateUserAndLogIn();
		Session::Set('login_mode', 'toto');

		$oSessionHandler = new SessionHandler();
		$session_id = uniqid();
		$sFile = $this->touchSessionFile($oSessionHandler, $session_id);
		$this->sFiles[]=$sFile;
		$this->assertEquals(true, is_file($sFile), $sFile);
		$sFirstContent = file_get_contents($sFile);


		$this->assertNotNull($sFirstContent);
		$aJson = json_decode($sFirstContent, true);
		$this->assertNotEquals(false, $aJson, $sFirstContent);
		$this->assertEquals($sUserId, $aJson['user_id'] ?? '', "check user_id is tracked: $sFirstContent");
		$this->assertEquals(ContextTag::TAG_REST, $aJson['context'] ?? '', $sFirstContent);
		$sCreationTime = $aJson['creation_time'] ?? '';
		$this->assertNotEquals('', $sCreationTime, "not empty timestamps: $sFirstContent");
		$this->assertEquals('toto', $aJson['login_mode'] ?? '', "proper login_mode tracked: $sFirstContent");

		$this->touchSessionFile($oSessionHandler, $session_id);
		$sNewContent = file_get_contents($sFile);
		$this->assertEquals($sFirstContent, $sNewContent, $sNewContent);
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
		$sFile = $this->touchSessionFile($oSessionHandler, $session_id);
		$this->sFiles[]=$sFile;
		$this->assertNull($sFile);
	}

	private function GetFilePath(SessionHandler $oSessionHandler, $session_id) : string {
		return $this->InvokeNonPublicMethod(SessionHandler::class, "get_file_path", $oSessionHandler, $aArgs = [$session_id]);
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
	 * @dataProvider GgcWithTimeLimitProvider
	 */
	public function testGgcWithTimeLimit($iTimeLimit, $iExpectedProcessed) {
		$oSessionHandler = new SessionHandler();
		//remove all first
		$oSessionHandler->gc_with_time_limit(-1);
		$this->assertEquals([], $oSessionHandler->list_session_files());

		$max_lifetime = 1440;
		$iNbExpiredFiles = 2;
		$iNbFiles = 5;
		$iExpiredTimeStamp = time() - $max_lifetime - 1;
		for($i=0; $i<$iNbFiles; $i++) {
			$sFile = $this->GetFilePath($oSessionHandler, uniqid());
			$this->sFiles[]=$sFile;
			file_put_contents($sFile, "fakedata");

			if ($iNbExpiredFiles >0){
				$iNbExpiredFiles--;
				touch($sFile, $iExpiredTimeStamp);
			}
		}

		$aFoundSessionFiles = $oSessionHandler->list_session_files();
		$this->assertEquals(5, sizeof($aFoundSessionFiles));
		foreach ($aFoundSessionFiles as $sFile){
			$this->assertTrue(is_file($sFile));
		}

		$iProcessed = $oSessionHandler->gc_with_time_limit($max_lifetime, $iTimeLimit);
		$this->assertEquals($iExpectedProcessed, $iProcessed);
		$this->assertEquals($iNbFiles - $iExpectedProcessed, sizeof($oSessionHandler->list_session_files()));
	}
}

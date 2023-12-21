<?php
namespace Combodo\iTop\Test\UnitTest\Application;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use MetaModel;

class LoginTest extends ItopDataTestCase {
	protected $sConfigTmpBackupFile;
	protected $sLoginMode;

	protected function setUp(): void {
		parent::setUp();

		clearstatcache();

		//backup config file
		$sConfigPath = MetaModel::GetConfig()->GetLoadedFile();
		$this->sConfigTmpBackupFile = tempnam(sys_get_temp_dir(), "config_");
		MetaModel::GetConfig()->WriteToFile($this->sConfigTmpBackupFile);

		$oConfig = new \Config($sConfigPath);
		$this->sLoginMode = "unimplemented_loginmode";
		$oConfig->AddAllowedLoginTypes($this->sLoginMode);

		@chmod($sConfigPath, 0770);
		$oConfig->WriteToFile();
		@chmod($sConfigPath, 0440);
	}

	protected function tearDown(): void {
		parent::tearDown();

		if (! is_null($this->sConfigTmpBackupFile) && is_file($this->sConfigTmpBackupFile)){
			//put config back
			$sConfigPath = MetaModel::GetConfig()->GetLoadedFile();
			$oConfig = new \Config($this->sConfigTmpBackupFile);
			@chmod($sConfigPath, 0770);
			$oConfig->WriteToFile($sConfigPath);
			@chmod($sConfigPath, 0440);
		}
	}

	public function testLoginInfiniteLoopFix() {
		$iTimeStamp = microtime(true);
		$sOutput = $this->CallItopUrlByCurl(sprintf("/pages/UI.php?login_mode=%s", $this->sLoginMode));
		$iElapsedInMs =  (microtime(true) - $iTimeStamp) * 1000;
		$sMaxExecutionInS = 1;
		$this->assertTrue($iElapsedInMs < $sMaxExecutionInS * 1000, "iTop answered in $iElapsedInMs ms. it should do it in less than $sMaxExecutionInS seconds (max_execution_time)");
		$this->assertFalse(strpos($sOutput, "Fatal error"), "no fatal error due to max execution time should be returned" . $sOutput);
	}

	protected function CallItopUrlByCurl($sUri, ?array $aPostFields=[]){
		$ch = curl_init();

		$sUrl = MetaModel::GetConfig()->Get('app_root_url') . "/$sUri";
		curl_setopt($ch, CURLOPT_URL, $sUrl);
		if (0 !== sizeof($aPostFields)){
			curl_setopt($ch, CURLOPT_POST, 1);// set post data to true
			curl_setopt($ch, CURLOPT_POSTFIELDS, $aPostFields);
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$sOutput = curl_exec($ch);
		curl_close ($ch);

		return $sOutput;
	}
}

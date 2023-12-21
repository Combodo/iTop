<?php
namespace Combodo\iTop\Test\UnitTest\Application;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use MetaModel;

class LoginTest extends ItopDataTestCase {
	protected $sConfigTmpBackupFile;
	protected $sConfigPath;
	protected $sLoginMode;

	protected function setUp(): void {
		parent::setUp();

		clearstatcache();

		//backup config file
		$this->sConfigPath = MetaModel::GetConfig()->GetLoadedFile();
		$this->sConfigTmpBackupFile = tempnam(sys_get_temp_dir(), "config_");
		file_put_contents($this->sConfigTmpBackupFile, file_get_contents($this->sConfigPath));

		$oConfig = new \Config($this->sConfigPath);
		$this->sLoginMode = "unimplemented_loginmode";
		$oConfig->AddAllowedLoginTypes($this->sLoginMode);

		@chmod($this->sConfigPath, 0770);
		$oConfig->WriteToFile();
		@chmod($this->sConfigPath, 0440);
	}

	protected function tearDown(): void {
		if (! is_null($this->sConfigTmpBackupFile) && is_file($this->sConfigTmpBackupFile)){
			//put config back
			@chmod($this->sConfigPath, 0770);
			file_put_contents($this->sConfigPath, file_get_contents($this->sConfigTmpBackupFile));
			@chmod($this->sConfigPath, 0440);
			@unlink($this->sConfigTmpBackupFile);
		}
		parent::tearDown();
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

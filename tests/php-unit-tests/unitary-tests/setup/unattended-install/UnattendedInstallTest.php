<?php

namespace Combodo\iTop\Test\UnitTest\Setup\UnattendedInstall;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;

class UnattendedInstallTest extends ItopDataTestCase
{
    protected function setUp(): void
    {
	    parent::setUp();
    }
	protected function tearDown(): void
	{
		parent::tearDown();
		$aFiles = [
			'web.config',
			'.htaccess',
		];
		foreach ($aFiles as $sFile){
			$sPath = APPROOT."setup/unattended-install/$sFile";
			if (is_file("$sPath.back")){
				rename("$sPath.back", $sPath);
			}
		}
	}

	private function callUnattendedFromHttp() : string {
		$ch = curl_init();

		$sUrl = \MetaModel::GetConfig()->Get('app_root_url');
		curl_setopt($ch, CURLOPT_URL, "$sUrl/setup/unattended-install/unattended-install.php");
		curl_setopt($ch, CURLOPT_POST, 1);// set post data to true
		curl_setopt($ch, CURLOPT_POSTFIELDS, []);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// Force disable of certificate check as most of dev / test env have a self-signed certificate
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

		$sJson = curl_exec($ch);
		curl_close ($ch);
		return $sJson;
	}
	public function testCallUnattendedInstallFromHttp(){
		$sJson = $this->callUnattendedFromHttp();
		if (false !== strpos($sJson, "403 Forbidden")){
			//.htaccess / webconfig effect
			$aFiles = [
				'web.config',
				'.htaccess',
			];
			foreach ($aFiles as $sFile){
				$sPath = APPROOT."setup/unattended-install/$sFile";
				if (is_file("$sPath")) {
					rename($sPath, "$sPath.back");
				}
			}

			$sJson = $this->callUnattendedFromHttp();
		}

		$this->assertEquals("Mode CLI only", $sJson, "even without HTTP protection, script should NOT be called directly by HTTP");
	}

	public function testCallUnattendedInstallFromCLI() {
		$sCliPath = realpath(APPROOT."/setup/unattended-install/unattended-install.php");
		exec(sprintf("%s %s", PHP_BINARY, $sCliPath), $aOutput, $iCode);

		$sOutput = implode('\n', $aOutput);
		var_dump($sOutput);
		$this->assertStringContainsString("Missing mandatory argument `--param-file`", $sOutput);
        if (DIRECTORY_SEPARATOR === '\\') {
            // Windows
            $this->assertEquals(-1, $iCode);
        } else {
            // Linux
            $this->assertEquals(255, $iCode);
        }
	}
}

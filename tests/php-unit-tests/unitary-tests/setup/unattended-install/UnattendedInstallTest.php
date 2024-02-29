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
		$cliPath = realpath(APPROOT."/setup/unattended-install/unattended-install.php");
		$res = exec("php ".$cliPath);

		$this->assertEquals("Param file `default-params.xml` doesn't exist ! Exiting...", $res);
	}

	public function testCallUnattendedInstall_ReadInstallationOption() {
		$cliPath = realpath(APPROOT."/setup/unattended-install/unattended-install.php");
		$sXmlInstallationPath = realpath(APPROOT."/datamodels/2.x/installation.xml");
		$res = exec("php ".$cliPath . " use-installation-xml=$sXmlInstallationPath");

		$this->assertEquals("Param file `default-params.xml` doesn't exist ! Exiting...", $res);
	}
}

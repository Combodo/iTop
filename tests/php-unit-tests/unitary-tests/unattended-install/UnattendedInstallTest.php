<?php

namespace Combodo\iTop\Test\UnitTest\UnattendedInstall;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;

class UnattendedInstallTest extends ItopDataTestCase
{
    protected function setUp(): void
    {
	    parent::setUp();

	    $this->sUrl = \MetaModel::GetConfig()->Get('app_root_url');
    }

	public function testCallUnattendedInstallFromHttp(){
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

		$this->assertEquals("Mode CLI only", $sJson);
	}

	public function testCallUnattendedInstallFromCLI() {
		$cliPath = realpath(APPROOT."/setup/unattended-install/unattended-install.php");
		$res = exec("php ".$cliPath);

		$this->assertEquals("Param file `default-params.xml` doesn't exist ! Exiting...", $res);
	}
}

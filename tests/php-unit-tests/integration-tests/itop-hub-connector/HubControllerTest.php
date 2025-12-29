<?php

namespace Combodo\iTop\Test\UnitTest\HubConnector;

use Combodo\iTop\HubConnector\Controller\HubController;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use DOMFormatException;
use JsonPage;
use MFCompiler;
use SetupLog;
use SetupUtils;
use utils;

/**
* @runClassInSeparateProcess
 */
class HubControllerTest extends ItopDataTestCase
{
	public const USE_TRANSACTION      = false;
	public const AUTHENTICATION_TOKEN = '14b5da9d092f84044187421419a0347e7317bc8cd2b486fdda631be06b959269';
	public const AUTHENTICATION_PASSWORD    = "tagada-Secret,007";

	protected function setUp(): void
	{
		$this->SkipIfModuleNotPresent('itop-hub-connector');
		parent::setUp();
		$this->RequireOnceItopFile('env-production/itop-hub-connector/src/Controller/HubController.php');
	}

	public function testLaunchCompile(): void
	{
		$this->PrepareCompileAuthent();

		$this->CopyProductionModulesIntoHubExtensionDir();

		HubController::GetInstance()->SetOutputHeaders(false);

		HubController::GetInstance()->LaunchCompile();

		$this->CheckReport('{"code":0,"message":"Ok","fields":[]}');
	}

	public function testLaunchDeploy(): void
	{
		$this->testLaunchCompile();
		HubController::GetInstance()->LaunchDeploy();
		$this->CheckReport('{"code":0,"message":"Compilation successful.","fields":[]}');
	}

	private function CheckReport($sExpected)
	{
		$oJsonPage = HubController::GetInstance()->GetLastJsonPage();
		$this->assertEquals($sExpected, $this->InvokeNonPublicMethod(JsonPage::class, 'ComputeContent', $oJsonPage, []));

		//keep line below to avoid: Test code or tested code did not (only) close its own output buffers
		$this->InvokeNonPublicMethod(JsonPage::class, 'RenderContent', $oJsonPage, []);
	}

	private function PrepareCompileAuthent()
	{
		$sUUID = 'hub_'.uniqid();
		$_REQUEST['authent'] = $sUUID;
		$sPath = utils::GetDataPath().'hub/compile_authent';
		file_put_contents($sPath, $sUUID);
		$this->aFileToClean[] = $sPath;
	}

	private function CopyProductionModulesIntoHubExtensionDir()
	{
		$sProdModules = APPROOT.'/data/production-modules';
		$sExtensionDir = APPROOT.'/data/downloaded-extensions/';
		$this->aFileToClean[] = $sExtensionDir;

		SetupUtils::rrmdir($sExtensionDir);
		@mkdir($sExtensionDir);
		SetupUtils::copydir($sExtensionDir, $sProdModules);
	}
}

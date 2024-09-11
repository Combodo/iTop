<?php
namespace Combodo\iTop\Test\UnitTest\Hub;

use Combodo\iTop\Application\Helper\Session;
use Combodo\iTop\Application\UI\Base\iUIBlockFactory;
use Combodo\iTop\Service\InterfaceDiscovery\InterfaceDiscovery;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;

class HubSetupTest extends ItopDataTestCase {
	const USE_TRANSACTION = false;

	public function setUp() : void {
		parent::setUp();

		$this->RequireOnceItopFile("application/utils.inc.php");
		$this->RequireOnceItopFile("core/log.class.inc.php");
		$this->RequireOnceItopFile("setup/runtimeenv.class.inc.php");
		$this->RequireOnceItopFile("setup/backup.class.inc.php");
		$this->RequireOnceItopFile("core/mutex.class.inc.php");
		$this->RequireOnceItopFile("core/dict.class.inc.php");
		$this->RequireOnceItopFile("setup/xmldataloader.class.inc.php");
		$this->RequireOnceItopFile("datamodels/2.x/itop-hub-connector/hubruntimeenvironment.class.inc.php");
	}

	public function testSetupViaHub() {
		$sEnvironment = 'test';
		$_REQUEST['switch_env']=$sEnvironment;
		Session::Set('itop_env', $sEnvironment);

		require_once (APPROOT.'/application/startup.inc.php');
		require_once (APPROOT.'/application/loginwebpage.class.inc.php');


		/*$aSelectedExtensionCodes = ['molkobain-datacenter-view'];
		$aSelectedExtensionDirs = $aSelectedExtensionCodes;
		ini_set('display_errors', 1);
		$oRuntimeEnv = new \HubRunTimeEnvironment($sEnvironment, false); // use a temp environment: production-build
		$oRuntimeEnv->MoveSelectedExtensions(APPROOT.'/data/downloaded-extensions/', $aSelectedExtensionDirs);

		$oConfig = new \Config(APPCONF."$sEnvironment/".ITOP_CONFIG_FILE);

		$oRuntimeEnv->CompileFrom("production", false); // WARNING symlinks does not seem to be compatible with manual Commit

		$oRuntimeEnv->UpdateIncludes($oConfig);*/

		//$oRuntimeEnv->InitDataModel($oConfig, true /* model only */);

		// Safety check: check the inter dependencies, will throw an exception in case of inconsistency
		/*$oRuntimeEnv->AnalyzeInstallation($oConfig, $oRuntimeEnv->GetBuildDir(), true);


		$oRuntimeEnv->CheckMetaModel(); // Will throw an exception if a problem is detected

		// Everything seems Ok so far, commit in env-production!
		$oRuntimeEnv->WriteConfigFileSafe($oConfig);
		$oRuntimeEnv->Commit();*/

		/*$sPath = APPROOT.'data/downloaded-extensions/';
		$aExtraDirs = array();
		if (is_dir($sPath)) {
			$aExtraDirs[] = $sPath; // Also read the extra downloaded-modules directory
		}
		$oExtensionsMap = new \iTopExtensionsMap($sEnvironment, true, $aExtraDirs);*/

		//InterfaceDiscovery::GetInstance()->FindItopClasses(iUIBlockFactory::class);


		$sPassword = "abCDEF12345@";
		/** @var User oUser */
		$this->oUser = $this->CreateContactlessUser('login' . uniqid(),
			ItopDataTestCase::$aURP_Profiles['Administrator'],
			$sPassword
		);
		$sLogin = $this->oUser->Get('login');
		$aPostFields = [
			'auth_user' => $sLogin,
			'auth_pwd' => $sPassword,
		];


		//$oConfig = new \Config();
		$sConfigPath = APPCONF . "$sEnvironment/config-itop.php"; //$oConfig->GetLoadedFile();
		@chmod($sConfigPath, 0770);
		//$oConfig->WriteToFile($sConfigPath);
		//@chmod($sConfigPath, 0440);

		$sOutput = $this->CallItopUrl("/pages/exec.php?exec_module=itop-hub-connector&exec_page=ajax.php&switch_env=$sEnvironment&exec_env=$sEnvironment&maintenance=1",
			[
				'auth_user' => $sLogin,
				'auth_pwd' => $sPassword,
				'operation' => 	"compile",
				'extension_codes[]' =>	"molkobain-datacenter-view",
				'extension_dirs[]' =>	"molkobain-datacenter-view",
				'authent' => '14b5da9d092f84044187421419a0347e7317bc8cd2b486fdda631be06b959269',
			]);

		/*$sOutput = $this->CallItopUrl("/pages/exec.php?exec_module=itop-hub-connector&exec_page=land.php&switch_env=$sEnvironment&exec_env=$sEnvironment&operation=install",
			$aPostFields);*/

		//var_dump($sOutput);

		$aRes = json_decode($sOutput, true);
		$this->assertNotNull($aRes, "output should be a json without any warning:" . PHP_EOL . $sOutput);

	}

	protected function CallItopUrl($sUri, ?array $aPostFields = null, $bXDebugEnabled = false)
	{
		$ch = curl_init();
		if ($bXDebugEnabled) {
			curl_setopt($ch, CURLOPT_COOKIE, 'XDEBUG_SESSION=phpstorm');
		}

		$sUrl = \MetaModel::GetConfig()->Get('app_root_url')."/$sUri";
		var_dump($sUrl);
		curl_setopt($ch, CURLOPT_URL, $sUrl);
		curl_setopt($ch, CURLOPT_POST, 1);// set post data to true
		curl_setopt($ch, CURLOPT_POSTFIELDS, $aPostFields);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$sOutput = curl_exec($ch);
		//echo "$sUrl error code:".curl_error($ch);
		curl_close($ch);

		return $sOutput;
	}
}

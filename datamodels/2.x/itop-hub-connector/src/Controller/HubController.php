<?php

namespace Combodo\iTop\HubConnector\Controller;

use Combodo\iTop\Application\WebPage\JsonPage;
use Combodo\iTop\HubConnector\Model\DBBackupWithErrorReporting;
use Combodo\iTop\HubConnector\setup\HubRunTimeEnvironment;
use Config;
use Dict;
use Exception;
use iTopExtension;
use iTopExtensionsMap;
use iTopMutex;
use LoginWebPage;
use MetaModel;
use MFCompiler;
use RunTimeEnvironment;
use SecurityException;
use SetupLog;
use SetupUtils;
use utils;

require_once(APPROOT.'setup/runtimeenv.class.inc.php');
require_once(APPROOT.'setup/backup.class.inc.php');
require_once(APPROOT.'core/mutex.class.inc.php');
require_once(APPROOT.'core/dict.class.inc.php');
require_once(APPROOT.'setup/xmldataloader.class.inc.php');
require_once(__DIR__.'/../setup/hubruntimeenvironment.class.inc.php');

class HubController
{
	private static HubController $oInstance;
	protected $bOutputHeaders = false;

	protected function __construct()
	{
	}

	final public static function GetInstance(): HubController
	{
		if (!isset(self::$oInstance)) {
			self::$oInstance = new HubController();
		}

		return self::$oInstance;
	}

	final public static function SetInstance(?HubController $oInstance): void
	{
		self::$oInstance = $oInstance;
	}

	public function LaunchBackup()
	{
		require_once(APPROOT.'/application/startup.inc.php');
		require_once(APPROOT.'/application/loginwebpage.class.inc.php');
		LoginWebPage::DoLogin(true); // Check user rights and prompt if needed (must be admin)

		try {
			if (MetaModel::GetConfig()->Get('demo_mode')) {
				throw new Exception('Sorry the installation of extensions is not allowed in demo mode');
			}
			SetupLog::Info('Backup starts...');
			set_time_limit(0);
			$sBackupPath = APPROOT.'/data/backups/manual/backup-';
			$iSuffix = 1;
			$sSuffix = '';
			// Generate a unique name...
			do {
				$sBackupFile = $sBackupPath.date('Y-m-d-His').$sSuffix;
				$sSuffix = '-'.$iSuffix;
				$iSuffix++ ;
			} while (file_exists($sBackupFile));

			$oBackup = $this->DoBackup($sBackupFile);
			$aErrors = $oBackup->GetErrors();
			if (count($aErrors) > 0) {
				SetupLog::Error('Backup failed.');
				SetupLog::Error(implode("\n", $aErrors));
				$this->ReportError(Dict::S('iTopHub:BackupFailed'), -1, $aErrors);
			} else {
				SetupLog::Info('Backup successfully completed.');
				$this->ReportSuccess(Dict::S('iTopHub:BackupOk'));
			}
		} catch (Exception $e) {
			SetupLog::Error($e->getMessage());
			$this->ReportError($e->getMessage(), $e->getCode());
		}
	}

	/**
	 *
	 * @param string $sTargetFile
	 * @throws Exception
	 * @return DBBackupWithErrorReporting
	 */
	public function DoBackup($sTargetFile): DBBackupWithErrorReporting
	{
		// Make sure the target directory exists
		$sBackupDir = dirname($sTargetFile);
		SetupUtils::builddir($sBackupDir);

		$oBackup = new DBBackupWithErrorReporting();
		$oBackup->SetMySQLBinDir(MetaModel::GetConfig()->GetModuleSetting('itop-backup', 'mysql_bindir', ''));
		$sSourceConfigFile = APPCONF.utils::GetCurrentEnvironment().'/'.ITOP_CONFIG_FILE;

		$oMutex = new iTopMutex('backup.'.utils::GetCurrentEnvironment());
		$oMutex->Lock();
		try {
			$oBackup->CreateCompressedBackup($sTargetFile, $sSourceConfigFile);
		} catch (Exception $e) {
			$oMutex->Unlock();
			throw $e;
		}
		$oMutex->Unlock();
		return $oBackup;
	}

	public function LaunchCompile()
	{
		SetupLog::Info('Deployment starts...');
		$sAuthent = utils::ReadParam('authent', '', false, 'raw_data');
		if (!file_exists(utils::GetDataPath().'hub/compile_authent') || $sAuthent !== file_get_contents(utils::GetDataPath().'hub/compile_authent')) {
			throw new SecurityException(Dict::S('iTopHub:FailAuthent'));
		}
		// First step: prepare the datamodel, if it fails, roll-back
		$aSelectedExtensionCodes = utils::ReadParam('extension_codes', []);
		$aSelectedExtensionDirs = utils::ReadParam('extension_dirs', []);

		$oRuntimeEnv = new HubRunTimeEnvironment('production', false); // use a temp environment: production-build
		$oRuntimeEnv->MoveSelectedExtensions(APPROOT.'/data/downloaded-extensions/', $aSelectedExtensionDirs);

		$oConfig = new Config(APPCONF.'production/'.ITOP_CONFIG_FILE);
		if ($oConfig->Get('demo_mode')) {
			throw new Exception('Sorry the installation of extensions is not allowed in demo mode');
		}

		$aSelectModules = $oRuntimeEnv->CompileFrom('production'); // WARNING symlinks does not seem to be compatible with manual Commit

		$oRuntimeEnv->UpdateIncludes($oConfig);

		$oRuntimeEnv->InitDataModel($oConfig, true /* model only */);

		// Safety check: check the inter dependencies, will throw an exception in case of inconsistency
		$oRuntimeEnv->AnalyzeInstallation($oConfig, $oRuntimeEnv->GetBuildDir(), true);

		$oRuntimeEnv->CheckMetaModel(); // Will throw an exception if a problem is detected

		// Everything seems Ok so far, commit in env-production!
		$oRuntimeEnv->WriteConfigFileSafe($oConfig);
		$oRuntimeEnv->Commit();

		// Report the success in a way that will be detected by the ajax caller
		SetupLog::Info('Compilation completed...');

		$this->ReportSuccess('Ok'); // No access to Dict::S here
	}

	public function LaunchDeploy()
	{
		// Second step: update the schema and the data
		// Everything happening below is based on env-production
		$oRuntimeEnv = new RunTimeEnvironment('production', true);

		try {
			SetupLog::Info('Move to production starts...');
			$sAuthent = utils::ReadParam('authent', '', false, 'raw_data');
			if (!file_exists(utils::GetDataPath().'hub/compile_authent') || $sAuthent !== file_get_contents(utils::GetDataPath().'hub/compile_authent')) {
				throw new SecurityException(Dict::S('iTopHub:FailAuthent'));
			}
			unlink(utils::GetDataPath().'hub/compile_authent');
			// Load the "production" config file to clone & update it
			$oConfig = new Config(APPCONF.'production/'.ITOP_CONFIG_FILE);
			SetupUtils::EnterReadOnlyMode($oConfig);

			$oRuntimeEnv->InitDataModel($oConfig, true /* model only */);

			$aAvailableModules = $oRuntimeEnv->AnalyzeInstallation($oConfig, $oRuntimeEnv->GetBuildDir(), true);

			$oRuntimeEnv->CallInstallerHandlers($aAvailableModules, 'BeforeDatabaseCreation');

			$oRuntimeEnv->CreateDatabaseStructure($oConfig, 'upgrade');

			$oRuntimeEnv->CallInstallerHandlers($aAvailableModules, 'AfterDatabaseCreation');

			$oRuntimeEnv->UpdatePredefinedObjects();

			$oRuntimeEnv->CallInstallerHandlers($aAvailableModules, 'AfterDatabaseSetup');

			$oRuntimeEnv->LoadData($aAvailableModules, false /* no sample data*/);

			$oRuntimeEnv->CallInstallerHandlers($aAvailableModules, 'AfterDataLoad');

			// Record the installation so that the "about box" knows about the installed modules
			$sDataModelVersion = $oRuntimeEnv->GetCurrentDataModelVersion();

			$oExtensionsMap = new iTopExtensionsMap();

			// Default choices = as before
			$oExtensionsMap->LoadChoicesFromDatabase($oConfig);
			foreach ($oExtensionsMap->GetAllExtensions() as $oExtension) {
				// Plus all "remote" extensions
				if ($oExtension->sSource == iTopExtension::SOURCE_REMOTE) {
					$oExtensionsMap->MarkAsChosen($oExtension->sCode);
				}
			}
			$aSelectedExtensionCodes = [];
			foreach ($oExtensionsMap->GetChoices() as $oExtension) {
				$aSelectedExtensionCodes[] = $oExtension->sCode;
			}
			$aSelectedExtensions = $oExtensionsMap->GetChoices();
			$oRuntimeEnv->RecordInstallation($oConfig, $sDataModelVersion, array_keys($aAvailableModules), $aSelectedExtensionCodes, 'Done by the iTop Hub Connector');

			// Report the success in a way that will be detected by the ajax caller
			SetupLog::Info('Deployment successfully completed.');
			$this->ReportSuccess(Dict::S('iTopHub:CompiledOK'));
		} catch (Exception $e) {
			if (file_exists(utils::GetDataPath().'hub/compile_authent')) {
				unlink(utils::GetDataPath().'hub/compile_authent');
			}
			// Note: at this point, the dictionnary is not necessarily loaded
			SetupLog::Error(get_class($e).': '.Dict::S('iTopHub:ConfigurationSafelyReverted')."\n".$e->getMessage());
			SetupLog::Error('Debug trace: '.$e->getTraceAsString());
			$this->ReportError($e->getMessage(), $e->getCode());
		} finally {
			SetupUtils::ExitReadOnlyMode();
		}
	}

	/**
	 * Outputs the status of the current ajax execution (as a JSON structure)
	 *
	 * @param string $sMessage
	 * @param bool $bSuccess
	 * @param number $iErrorCode
	 * @param array $aMoreFields
	 *        	Extra fields to pass to the caller, if needed
	 */
	public function ReportStatus($sMessage, $bSuccess, $iErrorCode = 0, $aMoreFields = [])
	{
		// Do not use AjaxPage during setup phases, because it uses InterfaceDiscovery in Twig compilation
		$this->oLastJsonPage = new JsonPage();
		$this->oLastJsonPage->SetOutputHeaders($this->bOutputHeaders);
		$aResult = [
			'code' => $iErrorCode,
			'message' => $sMessage,
			'fields' => $aMoreFields,
		];
		$this->oLastJsonPage->SetData($aResult);
		$this->oLastJsonPage->SetOutputDataOnly(true);
		$this->oLastJsonPage->output();
	}

	private ?JsonPage $oLastJsonPage = null;

	public function GetLastJsonPage(): ?JsonPage
	{
		return $this->oLastJsonPage;
	}

	/**
	 * Helper to output the status of a successful execution
	 *
	 * @param string $sMessage
	 * @param array $aMoreFields
	 *        	Extra fields to pass to the caller, if needed
	 */
	public function ReportSuccess($sMessage, $aMoreFields = [])
	{
		$this->ReportStatus($sMessage, true, 0, $aMoreFields);
	}

	/**
	 * Helper to output the status of a failed execution
	 *
	 * @param string $sMessage
	 * @param number $iErrorCode
	 * @param array $aMoreFields
	 *        	Extra fields to pass to the caller, if needed
	 */
	public function ReportError($sMessage, $iErrorCode, $aMoreFields = [])
	{
		if ($iErrorCode == 0) {
			// 0 means no error, so change it if no meaningful error code is supplied
			$iErrorCode = -1;
		}
		$this->ReportStatus($sMessage, false, $iErrorCode, $aMoreFields);
	}

	/**
	 * Dont print headers for testing purpose mainly
	 * @param bool bOutputHeaders
	 *
	 * @return void
	 */
	public function SetOutputHeaders(bool $bOutputHeaders): void
	{
		$this->bOutputHeaders = $bOutputHeaders;
	}
}

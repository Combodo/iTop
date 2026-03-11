<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Config\Controller;

use Combodo\iTop\Application\TwigBase\Controller\Controller;
use Combodo\iTop\Config\Validator\iTopConfigValidator;
use Config;
use Dict;
use Exception;
use MetaModel;
use SetupUtils;
use utils;

class ConfigEditorController extends Controller
{
	public const ROUTE_NAMESPACE = 'config_editor';
	public const MODULE_NAME = "itop-config";
	protected array $aWarnings = [];
	protected array $aInfo = [];
	protected array $aErrors = [];
	protected array $aSuccesses = [];

	public function __construct()
	{
		parent::__construct(MODULESROOT.static::MODULE_NAME.'/templates', static::MODULE_NAME);
		$this->SetDebugAllowed(false);
	}

	public function OperationEdit(): void
	{
		$bShowEditor = true;
		$sConfigChecksum = '';
		$sCurrentConfig = '';

		try {
			$sOperation = utils::ReadParam('edit_operation');
			if (MetaModel::GetConfig()->Get('demo_mode')) {
				throw new Exception(Dict::S('config-not-allowed-in-demo'), iTopConfigValidator::CONFIG_INFO);
			}

			if (MetaModel::GetModuleSetting('itop-config', 'config_editor', '') == 'disabled') {
				throw new Exception(Dict::S('config-interactive-not-allowed'), iTopConfigValidator::CONFIG_WARNING);
			}

			$sConfigFile = APPROOT.'conf/'.utils::GetCurrentEnvironment().'/config-itop.php';

			$sCurrentConfig = file_get_contents($sConfigFile);
			$sConfigChecksum = md5($sCurrentConfig);

			try {
				if ($sOperation == 'revert') {
					$this->AddAlert(Dict::S('config-reverted'), iTopConfigValidator::CONFIG_WARNING);
				} elseif ($sOperation == 'save') {
					$sTransactionId = utils::ReadParam('transaction_id', '', false, 'transaction_id');
					if (!utils::IsTransactionValid($sTransactionId)) {
						throw new Exception(Dict::S('config-error-transaction'), iTopConfigValidator::CONFIG_ERROR);
					}
					$sChecksum = utils::ReadParam('checksum');
					if ($sChecksum !== $sConfigChecksum) {
						throw new Exception(Dict::S('config-error-file-changed'), iTopConfigValidator::CONFIG_ERROR);
					}

					$sNewConfig = utils::ReadParam('new_config', '', false, 'raw_data');
					$sNewConfig = str_replace("\r\n", "\n", $sNewConfig);
					if ($sNewConfig === $sCurrentConfig) {
						throw new Exception(Dict::S('config-no-change'), iTopConfigValidator::CONFIG_INFO);
					}
					$oValidator = new iTopConfigValidator();

					$oValidator->Validate($sNewConfig);// throws exceptions

					@chmod($sConfigFile, 0770); // Allow overwriting the file
					$sTmpFile = tempnam(SetupUtils::GetTmpDir(), 'itop-cfg-');
					// Don't write the file as-is since it would allow to inject any kind of PHP code.
					// Instead, write the interpreted version of the file
					// Note:
					// The actual raw PHP code will anyhow be interpreted exactly twice: once in TestConfig() above
					// and a second time during the load of the Config object below.
					// If you are really concerned about an iTop administrator crafting some malicious
					// PHP code inside the config file, then turn off the interactive configuration
					// editor by adding the configuration parameter:
					// 'itop-config' => array(
					//     'config_editor' => 'disabled',
					// )
					file_put_contents($sTmpFile, $sNewConfig);
					$oTempConfig = new Config($sTmpFile, true);
					$oTempConfig->WriteToFile($sConfigFile);
					@unlink($sTmpFile);
					@chmod($sConfigFile, 0440); // Read-only

					if ($oValidator->DBPasswordIsOk($oTempConfig->Get('db_pwd'))) {
						$this->AddAlert(Dict::S('config-saved'), iTopConfigValidator::CONFIG_SUCCESS);
					} else {
						$this->AddAlert(Dict::S('config-saved-warning-db-password'), iTopConfigValidator::CONFIG_INFO);
					}

					$this->AddAlert($oValidator->CheckAsyncTasksRetryConfig($oTempConfig), iTopConfigValidator::CONFIG_WARNING);

					// Read the config from disk after save
					$sCurrentConfig = file_get_contents($sConfigFile);
					$sConfigChecksum = md5($sCurrentConfig);
				}
			} catch (Exception $e) {
				$this->AddAlertFromException($e);
			}

			$this->AddAceScripts();
		} catch (Exception $e) {
			$bShowEditor = false;
			$this->AddAlertFromException($e);
		}

		// display page
		$this->DisplayPage([
			'aErrors' => $this->aErrors,
			'aWarnings' => $this->aWarnings,
			'aNotices' => $this->aInfo,
			'aSuccesses' => $this->aSuccesses,
			'bShowEditor' => $bShowEditor,
			'sTransactionId' => utils::GetNewTransactionId(),
			'sChecksum' => $sConfigChecksum,
			'sPrevConfig' => $sCurrentConfig,
			'sNewConfig' => $sCurrentConfig,
		]);
	}

	/**
	 * @return void
	 * @throws \Exception
	 */
	protected function AddAceScripts(): void
	{
		$sAceDir = 'node_modules/ace-builds/src-min/';
		$this->AddLinkedScript(utils::GetAbsoluteUrlAppRoot().$sAceDir.'ace.js');
		$this->AddLinkedScript(utils::GetAbsoluteUrlAppRoot().$sAceDir.'mode-php.js');
		$this->AddLinkedScript(utils::GetAbsoluteUrlAppRoot().$sAceDir.'theme-eclipse.js');
		$this->AddLinkedScript(utils::GetAbsoluteUrlAppRoot().$sAceDir.'ext-searchbox.js');
	}

	public function AddAlertFromException(Exception $e): void
	{
		$this->AddAlert($e->getMessage(), $e->getCode());
	}

	public function AddAlert(array|string $sMessage, $iLevel): void
	{
		if (is_array($sMessage)) {
			foreach ($sMessage as $sSingleMessage) {
				$this->AddAlert($sSingleMessage, $iLevel);
			}
			return;
		}
		switch ($iLevel) {
			case iTopConfigValidator::CONFIG_SUCCESS:
				$this->aSuccesses[] = $sMessage;
				break;
			case iTopConfigValidator::CONFIG_WARNING:
				$this->aWarnings[] = $sMessage;
				break;
			case iTopConfigValidator::CONFIG_INFO:
				$this->aInfo[] = $sMessage;
				break;
			default:
				$this->aErrors[] = $sMessage;
		}
	}

}

<?php

use PhpParser\ParserFactory;
use PhpParser\Node\Expr\Assign;

require_once __DIR__ . '/ModuleDiscoveryEvaluationService.php';
require_once __DIR__ . '/ModuleDiscoveryServiceException.php';

class ModuleDiscoveryService {
	private static ModuleDiscoveryService $oInstance;
	private	static int $iDummyClassIndex = 0;

	protected function __construct() {
	}

	final public static function GetInstance(): ModuleDiscoveryService {
		if (!isset(static::$oInstance)) {
			static::$oInstance = new static();
		}

		return static::$oInstance;
	}

	final public static function SetInstance(?ModuleDiscoveryService $oInstance): void {
		static::$oInstance = $oInstance;
	}

	/**
	 * Read the information from a module file (module.xxx.php)
	 * Closely inspired (almost copied/pasted !!) from ModuleDiscovery::ListModuleFiles
	 * @param string $sModuleFile
	 * @return array
	 * @throws ModuleDiscoveryServiceException
	 */
	public function ReadModuleFileConfigurationLegacy(string $sModuleFilePath) : array
	{
		$aModuleInfo = []; // will be filled by the "eval" line below...
		try
		{
			$aMatches = [];
			$sModuleFileContents = file_get_contents($sModuleFilePath);
			$sModuleFileContents = str_replace(['<?php', '?>'], '', $sModuleFileContents);
			$sModuleFileContents = str_replace('__FILE__', "'".addslashes($sModuleFilePath)."'", $sModuleFileContents);
			preg_match_all('/class ([A-Za-z0-9_]+) extends ([A-Za-z0-9_]+)/', $sModuleFileContents, $aMatches);
			//print_r($aMatches);
			$idx = 0;
			foreach($aMatches[1] as $sClassName)
			{
				if (class_exists($sClassName))
				{
					// rename any class declaration inside the code to prevent a "duplicate class" declaration
					// and change its parent class as well so that nobody will find it and try to execute it
					// Note: don't use the same naming scheme as ModuleDiscovery otherwise you 'll have the duplicate class error again !!
					$sModuleFileContents = str_replace($sClassName.' extends '.$aMatches[2][$idx], $sClassName.'_Ext_'.(ModuleDiscoveryService::$iDummyClassIndex++).' extends DummyHandler', $sModuleFileContents);
				}
				$idx++;
			}
			// Replace the main function call by an assignment to a variable, as an array...
			$sModuleFileContents = str_replace(['SetupWebPage::AddModule', 'ModuleDiscovery::AddModule'], '$aModuleInfo = array', $sModuleFileContents);
			eval($sModuleFileContents); // Assigns $aModuleInfo

			if (count($aModuleInfo) === 0)
			{
				throw new ModuleDiscoveryServiceException("Eval of $sModuleFilePath did  not return the expected information...");
			}

			$this->CompleteConfigWithModuleFilePath($aModuleInfo);
		}
		catch(ModuleDiscoveryServiceException $e)
		{
			// Continue...
			throw $e;
		}
		catch(ParseError $e)
		{
			// Continue...
			throw new ModuleDiscoveryServiceException("Eval of $sModuleFilePath caused a parse error: ".$e->getMessage()." at line ".$e->getLine());
		}
		catch(Exception $e)
		{
			// Continue...
			throw new ModuleDiscoveryServiceException("Eval of $sModuleFilePath caused an exception: ".$e->getMessage(), 0, $e);
		}
		return $aModuleInfo;
	}


	/**
	 * Read the information from a module file (module.xxx.php)
	 * Closely inspired (almost copied/pasted !!) from ModuleDiscovery::ListModuleFiles
	 * @param string $sModuleFile
	 * @return array
	 * @throws ModuleDiscoveryServiceException
	 */
	public function ReadModuleFileConfiguration(string $sModuleFilePath) : array
	{
		try
		{
			$aNodes = ModuleDiscoveryEvaluationService::GetInstance()->ParsePhpCode(file_get_contents($sModuleFilePath));
		}
		catch (PhpParser\Error $e) {
			throw new \ModuleDiscoveryServiceException($e->getMessage(), 0, $e, $sModuleFilePath);
		}

		try {
			foreach ($aNodes as $sKey => $oNode) {
				if ($oNode instanceof \PhpParser\Node\Stmt\Expression) {
					$aModuleConfig = ModuleDiscoveryEvaluationService::GetInstance()->BrowseAddModuleCallAndReturnModuleConfiguration($sModuleFilePath, $oNode);
					if (! is_null($aModuleConfig)){
						$this->CompleteConfigWithModuleFilePath($aModuleConfig);
						return $aModuleConfig;
					}
				}

				if ($oNode instanceof PhpParser\Node\Stmt\If_) {
					$aModuleConfig = ModuleDiscoveryEvaluationService::GetInstance()->BrowseIfStructure($sModuleFilePath, $oNode);
					if (! is_null($aModuleConfig)){
						$this->CompleteConfigWithModuleFilePath($aModuleConfig);
						return $aModuleConfig;
					}
				}
			}
		} catch(ModuleDiscoveryServiceException $e) {
			// Continue...
			throw $e;
		} catch(Exception $e) {
			// Continue...
			throw new ModuleDiscoveryServiceException("Eval of $sModuleFilePath caused an exception: ".$e->getMessage(), 0, $e, $sModuleFilePath);
		}

		throw new ModuleDiscoveryServiceException("No proper call to SetupWebPage::AddModule found in module file", 0, null, $sModuleFilePath);
	}

	/**
	 * N°4789 - Parse datamodel module.xxx.php files instead of interpreting them
	 * additional path added to handle ModuleInstallerAPI declaration during setup only
	 * @param array &$aModuleInfo
	 *
	 * @return void
	 */
	private function CompleteConfigWithModuleFilePath(array &$aModuleInfo)
	{
		if (count($aModuleInfo)==3) {
			$aModuleInfo[2]['module_file_path'] = $aModuleInfo[0];
		}
	}

	/**
	 *
	 * @param \Config $oConfig
	 * @param array $aModuleConfig
	 *
	 * @return void
	 * @throws \ModuleDiscoveryServiceException
	 */
	public function CallInstallerBeforeWritingConfigMethod(Config $oConfig, array $aModuleConfig)
	{
		$sModuleInstallerClass = $this->DeclareModuleInstallerAPI($aModuleConfig);
		if (is_null($sModuleInstallerClass)){
			return;
		}

		$aCallSpec = [$sModuleInstallerClass, 'BeforeWritingConfig'];
		call_user_func_array($aCallSpec, [$oConfig]);
	}

	/**
	 * Call the given handler method for all selected modules having an installation handler
	 *
	 * @param Config $oConfig
	 * @param array $aModuleConfig
	 * @param array $aModule
	 * @param string $sHandlerName
	 *
	 * @throws CoreException
	 */
	public function CallInstallerHandler(Config $oConfig, array $aModuleConfig, array $aModule, $sHandlerName)
	{
		$sModuleInstallerClass = $this->DeclareModuleInstallerAPI($aModuleConfig);
		if (is_null($sModuleInstallerClass)){
			return;
		}

		SetupLog::Info("Calling Module Handler: $sModuleInstallerClass::$sHandlerName(oConfig, {$aModule['version_db']}, {$aModule['version_code']})");
		$aCallSpec = [$sModuleInstallerClass, $sHandlerName];
		if (is_callable($aCallSpec))
		{
			try {
				call_user_func_array($aCallSpec, [MetaModel::GetConfig(), $aModule['version_db'], $aModule['version_code']]);
			} catch (Exception $e) {
				$sErrorMessage = "Module $sModuleId : error when calling module installer class $sModuleInstallerClass for $sHandlerName handler";
				$aExceptionContextData = [
					'ModulelId' => $sModuleId,
					'ModuleInstallerClass' => $sModuleInstallerClass,
					'ModuleInstallerHandler' => $sHandlerName,
					'ExceptionClass' => get_class($e),
					'ExceptionMessage' => $e->getMessage(),
				];
				throw new CoreException($sErrorMessage, $aExceptionContextData, '', $e);
			}
		}
	}
	
	private function DeclareModuleInstallerAPI($aModuleConfig) : ?string
	{
		if (! isset($aModuleConfig['installer'])){
			return null;
		}

		$sModuleInstallerClass = $aModuleConfig['installer'];
		if (!class_exists($sModuleInstallerClass)) {
			$sModuleFilePath = $aModuleConfig['module_file_path'];
			$this->ReadModuleFileConfigurationLegacy($sModuleFilePath);
		}

		if (!class_exists($sModuleInstallerClass))
		{
			throw new CoreException("Wrong installer class: '$sModuleInstallerClass' is not a PHP class - Module: ".$aModuleConfig['label']);
		}
		if (!is_subclass_of($sModuleInstallerClass, 'ModuleInstallerAPI'))
		{
			throw new CoreException("Wrong installer class: '$sModuleInstallerClass' is not derived from 'ModuleInstallerAPI' - Module: ".$aModuleConfig['label']);
		}

		return $sModuleInstallerClass;
	}
}

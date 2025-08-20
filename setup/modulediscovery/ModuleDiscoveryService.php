<?php

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
	public function ReadModuleFileConfiguration(string $sModuleFilePath) : array
	{
		$aModuleInfo = array(); // will be filled by the "eval" line below...
		try
		{
			$aMatches = array();
			$sModuleFileContents = file_get_contents($sModuleFilePath);
			$sModuleFileContents = str_replace(array('<?php', '?>'), '', $sModuleFileContents);
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
			$sModuleFileContents = str_replace(array('SetupWebPage::AddModule', 'ModuleDiscovery::AddModule'), '$aModuleInfo = array', $sModuleFileContents);
			eval($sModuleFileContents); // Assigns $aModuleInfo

			if (count($aModuleInfo) === 0)
			{
				throw new ModuleDiscoveryServiceException("Eval of $sModuleFilePath did  not return the expected information...");
			}
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
	 * @param string $sBooleanExpr
	 *
	 * @return bool
	 * @throws ModuleDiscoveryServiceException
	 */
	public function ComputeBooleanExpression(string $sBooleanExpr) : bool
	{
		$bResult = false;
		try{
			@eval('$bResult = '.$sBooleanExpr.';');
		} catch (Throwable $t) {
			throw new ModuleDiscoveryServiceException("Eval of '$sBooleanExpr' caused an error: ".$t->getMessage());
		}

		return $bResult;
	}
}

class ModuleDiscoveryServiceException extends Exception
{
	/**
	 * ModuleDiscoveryServiceException constructor.
	 *
	 * @param string $sMessage
	 * @param int $iHttpCode
	 * @param Exception|null $oPrevious
	 */
	public function __construct($sMessage, $iHttpCode = 0, Exception $oPrevious = null)
	{
		$e = new \Exception("");

		SetupLog::Warning($sMessage, null, ['previous' => $oPrevious?->getMessage(), 'stack' => $e->getTraceAsString()]);
		parent::__construct($sMessage, $iHttpCode, $oPrevious);
	}
}
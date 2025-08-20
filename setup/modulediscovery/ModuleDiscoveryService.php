<?php

class ModuleDiscoveryService {
	private static ModuleDiscoveryService $oInstance;

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
	 */
	public function ReadModuleFileConfiguration(string $sModuleFilePath) : array
	{
		static $iDummyClassIndex = 0;
		$aModuleInfo = []; // will be filled by the "eval" line below...
		try
		{
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
					// rename the class inside the code to prevent a "duplicate class" declaration
					// and change its parent class as well so that nobody will find it and try to execute it
					$sModuleFileContents = str_replace($sClassName.' extends '.$aMatches[2][$idx], $sClassName.'_'.($iDummyClassIndex++).' extends DummyHandler', $sModuleFileContents);
				}
				$idx++;
			}

			// Replace the main function call by an assignment to a variable, as an array...
			$sModuleFileContents = str_replace(array('SetupWebPage::AddModule', 'ModuleDiscovery::AddModule'), '$aModuleInfo = array', $sModuleFileContents);

			eval($sModuleFileContents); // Assigns $aModuleInfo

			if (count($aModuleInfo) === 0)
			{
				SetupLog::Warning("Eval of $sModuleFilePath did  not return the expected information...");
			}

			//echo "<p>Done.</p>\n";
		}
		catch(ParseError $e)
		{
			// PHP 7
			SetupLog::Warning("Eval of $sModuleFilePath caused a parse exception: ".$e->getMessage()." at line ".$e->getLine());
		}
		catch(Exception $e)
		{
			// Continue...
			SetupLog::Warning("Eval of $sModuleFilePath caused an exception: ".$e->getMessage());
		}

		return $aModuleInfo;
	}

	public function ComputeDependencyExpression(string $sBooleanExpr) : bool
	{
		return @eval('$bResult = '.$sBooleanExpr.'; return $bResult;');
	}


	public function ComputeAutoSelectExpression(string $sBooleanExpr) : bool
	{
		return eval('$bSelected = ('.$sBooleanExpr.'); return $bSelected');
	}
}
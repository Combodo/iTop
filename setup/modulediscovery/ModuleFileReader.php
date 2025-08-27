<?php

use PhpParser\ParserFactory;
use PhpParser\Node\Expr\Assign;

require_once __DIR__ . '/ModuleFileParser.php';
require_once __DIR__ . '/ModuleFileReaderException.php';

class ModuleFileReader {
	private static ModuleFileReader $oInstance;
	private	static int $iDummyClassIndex = 0;

	protected function __construct() {
	}

	final public static function GetInstance(): ModuleFileReader {
		if (!isset(static::$oInstance)) {
			static::$oInstance = new static();
		}

		return static::$oInstance;
	}

	final public static function SetInstance(?ModuleFileReader $oInstance): void {
		static::$oInstance = $oInstance;
	}

	/**
	 * Read the information from a module file (module.xxx.php)
	 * Use this method to load the ModuleInstallerAPI
	 * @param string $sModuleFile
	 * @return array
	 * @throws ModuleFileReaderException
	 */
	public function ReadModuleFileConfigurationUnsafe(string $sModuleFilePath) : array
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
					$sModuleFileContents = str_replace($sClassName.' extends '.$aMatches[2][$idx], $sClassName.'_Ext_'.(ModuleFileReader::$iDummyClassIndex++).' extends DummyHandler', $sModuleFileContents);
				}
				$idx++;
			}
			// Replace the main function call by an assignment to a variable, as an array...
			$sModuleFileContents = str_replace(['SetupWebPage::AddModule', 'ModuleDiscovery::AddModule'], '$aModuleInfo = array', $sModuleFileContents);
			eval($sModuleFileContents); // Assigns $aModuleInfo

			if (count($aModuleInfo) === 0)
			{
				throw new ModuleFileReaderException("Eval of $sModuleFilePath did  not return the expected information...");
			}

			$this->CompleteModuleInfoWithFilePath($aModuleInfo);
		}
		catch(ModuleFileReaderException $e)
		{
			// Continue...
			throw $e;
		}
		catch(ParseError $e)
		{
			// Continue...
			throw new ModuleFileReaderException("Eval of $sModuleFilePath caused a parse error: ".$e->getMessage()." at line ".$e->getLine());
		}
		catch(Exception $e)
		{
			// Continue...
			throw new ModuleFileReaderException("Eval of $sModuleFilePath caused an exception: ".$e->getMessage(), 0, $e);
		}
		return $aModuleInfo;
	}


	/**
	 * Read the information from a module file (module.xxx.php)
	 * @param string $sModuleFile
	 * @return array
	 * @throws ModuleFileReaderException
	 */
	public function ReadModuleFileConfiguration(string $sModuleFilePath) : array
	{
		try
		{
			$aNodes = ModuleFileParser::GetInstance()->ParsePhpCode(file_get_contents($sModuleFilePath));
		}
		catch (PhpParser\Error $e) {
			throw new \ModuleFileReaderException($e->getMessage(), 0, $e, $sModuleFilePath);
		}

		try {
			foreach ($aNodes as $sKey => $oNode) {
				if ($oNode instanceof \PhpParser\Node\Stmt\Expression) {
					$aModuleInfo = ModuleFileParser::GetInstance()->GetModuleInformationFromAddModuleCall($sModuleFilePath, $oNode);
					if (! is_null($aModuleInfo)){
						$this->CompleteModuleInfoWithFilePath($aModuleInfo);
						return $aModuleInfo;
					}
				}

				if ($oNode instanceof PhpParser\Node\Stmt\If_) {
					$aModuleInfo = ModuleFileParser::GetInstance()->GetModuleInformationFromIf($sModuleFilePath, $oNode);
					if (! is_null($aModuleInfo)){
						$this->CompleteModuleInfoWithFilePath($aModuleInfo);
						return $aModuleInfo;
					}
				}
			}
		} catch(ModuleFileReaderException $e) {
			// Continue...
			throw $e;
		} catch(Exception $e) {
			// Continue...
			throw new ModuleFileReaderException("Eval of $sModuleFilePath caused an exception: ".$e->getMessage(), 0, $e, $sModuleFilePath);
		}

		throw new ModuleFileReaderException("No proper call to SetupWebPage::AddModule found in module file", 0, null, $sModuleFilePath);
	}

	/**
	 *
	 * Internal trick: additional path is added into the module info structure to handle ModuleInstallerAPI execution during setup
	 * @param array &$aModuleInfo
	 *
	 * @return void
	 */
	private function CompleteModuleInfoWithFilePath(array &$aModuleInfo)
	{
		if (count($aModuleInfo)==3) {
			$aModuleInfo[2]['module_file_path'] = $aModuleInfo[0];
		}
	}

	public function GetAndCheckModuleInstallerClass($aModuleInfo) : ?string
	{
		if (! isset($aModuleInfo['installer'])){
			return null;
		}

		$sModuleInstallerClass = $aModuleInfo['installer'];
		if (!class_exists($sModuleInstallerClass)) {
			$sModuleFilePath = $aModuleInfo['module_file_path'];
			$this->ReadModuleFileConfigurationUnsafe($sModuleFilePath);
		}

		if (!class_exists($sModuleInstallerClass))
		{
			throw new CoreException("Wrong installer class: '$sModuleInstallerClass' is not a PHP class - Module: ".$aModuleInfo['label']);
		}
		if (!is_subclass_of($sModuleInstallerClass, 'ModuleInstallerAPI'))
		{
			throw new CoreException("Wrong installer class: '$sModuleInstallerClass' is not derived from 'ModuleInstallerAPI' - Module: ".$aModuleInfo['label']);
		}

		return $sModuleInstallerClass;
	}
}

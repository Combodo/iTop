<?php

use PhpParser\ParserFactory;
use PhpParser\Node\Expr\Assign;

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
		$aModuleInfo = []; // will be filled by the "eval" line below...
		try
		{
			$oParser = (new ParserFactory())->createForNewestSupportedVersion();
			$aNodes = $oParser->parse(file_get_contents($sModuleFilePath));
		}
		catch (\Error $e) {
			$sMessage = Dict::Format('config-parse-error', $e->getMessage(), $e->getLine());
			$this->oException = new \ModuleDiscoveryServiceException($sMessage, 0, $e);
		}

		try {
			foreach ($aNodes as $sKey => $oNode) {
				if (false === ($oNode instanceof \PhpParser\Node\Stmt\Expression)) {
					continue;
				}

				/** @var Assign $oAssignation */
				$oAssignation = $oNode->expr;

				if (false === ($oAssignation instanceof PhpParser\Node\Expr\StaticCall)) {
					continue;
				}

				/** @var PhpParser\Node\Expr\StaticCall $oAssignation */

				if ("SetupWebPage" !== $oAssignation?->class?->name) {
					continue;
				}

				if ("AddModule" !== $oAssignation?->name?->name) {
					continue;
				}

				$aArgs = $oAssignation?->args;
				if (count($aArgs) != 3) {
					throw new ModuleDiscoveryServiceException("Not enough parameters when calling SetupWebPage::AddModule");
				}

				$oModuleId = $aArgs[1];
				if (false === ($oModuleId instanceof PhpParser\Node\Arg)) {
					throw new ModuleDiscoveryServiceException("2nd parameter to SetupWebPage::AddModule call issue: " . get_class($oModuleId));
				}

				/** @var PhpParser\Node\Arg $oModuleId */
				if (false === ($oModuleId->value instanceof PhpParser\Node\Scalar\String_)) {
					throw new ModuleDiscoveryServiceException("2nd parameter to SetupWebPage::AddModule not a string: " . get_class($oModuleId->value));
				}

				/** @var PhpParser\Node\Scalar\String_ $sModuleIdStringObj */
				$sModuleIdStringObj = $oModuleId->value;
				$sModuleId = $sModuleIdStringObj->value;

				$oModuleConfigInfo = $aArgs[2];
				if (false === ($oModuleConfigInfo instanceof PhpParser\Node\Arg)) {
					throw new ModuleDiscoveryServiceException("3rd parameter to SetupWebPage::AddModule call issue: " . get_class($oModuleConfigInfo));
				}

				/** @var PhpParser\Node\Arg $oModuleConfigInfo */
				if (false === ($oModuleConfigInfo->value instanceof PhpParser\Node\Expr\Array_)) {
					throw new ModuleDiscoveryServiceException("3rd parameter to SetupWebPage::AddModule not an array: " . get_class($oModuleConfigInfo->value));
				}

				$aModuleConfig=[];
				$this->BrowseArrayStructure($oModuleConfigInfo->value, $aModuleConfig);

				return [
					$sModuleFilePath,
					$sModuleId,
					$aModuleConfig
				];
			}
		} catch(ModuleDiscoveryServiceException $e) {
			// Continue...
			throw $e;
		} catch(Exception $e) {
			// Continue...
			throw new ModuleDiscoveryServiceException("Eval of $sModuleFilePath caused an exception: ".$e->getMessage(), 0, $e);
		}
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

	private function BrowseArrayStructure(PhpParser\Node\Expr\Array_ $oArray, array &$aModuleConfig) : void
	{
		$iIndex=0;
		/** @var \PhpParser\Node\Expr\ArrayItem $oValue */
		foreach ($oArray->items as $oArrayItem){
			if ($oArrayItem->key instanceof PhpParser\Node\Scalar\String_) {
				//dictionnary
				$sKey = $oArrayItem->key->value;
			} else {
				$sKey = $iIndex++;
			}

			$oValue = $oArrayItem->value;

			if ($oValue instanceof PhpParser\Node\Expr\Array_) {
				$aSubConfig=[];
				$this->BrowseArrayStructure($oValue, $aSubConfig);
				$aModuleConfig[$sKey]=$aSubConfig;
			}

			if ($oValue instanceof PhpParser\Node\Scalar\String_) {
				$aModuleConfig[$sKey]=$oValue->value;
				continue;
			}

			if ($oValue instanceof \PhpParser\Node\Expr\ConstFetch) {
				$aModuleConfig[$sKey]= filter_var($oValue->name->name, FILTER_VALIDATE_BOOLEAN);
			}
		}
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
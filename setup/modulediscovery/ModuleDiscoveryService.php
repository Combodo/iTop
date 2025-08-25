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
		try
		{
			$oParser = (new ParserFactory())->createForNewestSupportedVersion();
			$aNodes = $oParser->parse(file_get_contents($sModuleFilePath));
		}
		catch (PhpParser\Error $e) {
			throw new \ModuleDiscoveryServiceException($e->getMessage(), 0, $e, $sModuleFilePath);
		}

		try {
			foreach ($aNodes as $sKey => $oNode) {
				if ($oNode instanceof \PhpParser\Node\Stmt\Expression) {
					$aModuleConfig = $this->ParseCallToAddModuleAndReturnModuleConfiguration($sModuleFilePath, $oNode);
					if (! is_null($aModuleConfig)){
						return $aModuleConfig;
					}
				}

				if ($oNode instanceof PhpParser\Node\Stmt\If_) {
					$aModuleConfig = $this->BrowseIfStructure($sModuleFilePath, $oNode);
					if (! is_null($aModuleConfig)){
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
			} else if ($oArrayItem->key instanceof \PhpParser\Node\Expr\ConstFetch) {
				$sKey = $this->EvaluateConstantExpression($oArrayItem->key);
				if (is_null($sKey)){
					continue;
				}
			}else {
				$sKey = $iIndex++;
			}

			$oValue = $oArrayItem->value;

			if ($oValue instanceof PhpParser\Node\Expr\Array_) {
				$aSubConfig=[];
				$this->BrowseArrayStructure($oValue, $aSubConfig);
				$aModuleConfig[$sKey]=$aSubConfig;
			}

			if ($oValue instanceof PhpParser\Node\Scalar\String_||$oValue instanceof PhpParser\Node\Scalar\Int_) {
				$aModuleConfig[$sKey]=$oValue->value;
				continue;
			}

			if ($oValue instanceof \PhpParser\Node\Expr\ConstFetch) {
				$oEvaluatedConstant = $this->EvaluateConstantExpression($oValue);
				$aModuleConfig[$sKey]= $oEvaluatedConstant;
			}
		}
	}

	/**
	 * @param string $sModuleFilePath
	 * @param \PhpParser\Node\Expr\Assign $oAssignation
	 *
	 * @return array|null
	 * @throws \ModuleDiscoveryServiceException
	 */
	private function ParseCallToAddModuleAndReturnModuleConfiguration(string $sModuleFilePath, \PhpParser\Node\Stmt\Expression $oExpression) : ?array
	{
		/** @var Assign $oAssignation */
		$oAssignation = $oExpression->expr;
		if (false === ($oAssignation instanceof PhpParser\Node\Expr\StaticCall)) {
			return null;
		}

		/** @var PhpParser\Node\Expr\StaticCall $oAssignation */

		if ("SetupWebPage" !== $oAssignation?->class?->name) {
			return null;
		}

		if ("AddModule" !== $oAssignation?->name?->name) {
			return null;
		}

		$aArgs = $oAssignation?->args;
		if (count($aArgs) != 3) {
			throw new ModuleDiscoveryServiceException("Not enough parameters when calling SetupWebPage::AddModule", 0, null, $sModuleFilePath);
		}

		$oModuleId = $aArgs[1];
		if (false === ($oModuleId instanceof PhpParser\Node\Arg)) {
			throw new ModuleDiscoveryServiceException("2nd parameter to SetupWebPage::AddModule call issue: " . get_class($oModuleId), 0, null, $sModuleFilePath);
		}

		/** @var PhpParser\Node\Arg $oModuleId */
		if (false === ($oModuleId->value instanceof PhpParser\Node\Scalar\String_)) {
			throw new ModuleDiscoveryServiceException("2nd parameter to SetupWebPage::AddModule not a string: " . get_class($oModuleId->value), 0, null, $sModuleFilePath);
		}

		/** @var PhpParser\Node\Scalar\String_ $sModuleIdStringObj */
		$sModuleIdStringObj = $oModuleId->value;
		$sModuleId = $sModuleIdStringObj->value;

		$oModuleConfigInfo = $aArgs[2];
		if (false === ($oModuleConfigInfo instanceof PhpParser\Node\Arg)) {
			throw new ModuleDiscoveryServiceException("3rd parameter to SetupWebPage::AddModule call issue: " . get_class($oModuleConfigInfo), 0, null, $sModuleFilePath);
		}

		/** @var PhpParser\Node\Arg $oModuleConfigInfo */
		if (false === ($oModuleConfigInfo->value instanceof PhpParser\Node\Expr\Array_)) {
			throw new ModuleDiscoveryServiceException("3rd parameter to SetupWebPage::AddModule not an array: " . get_class($oModuleConfigInfo->value), 0, null, $sModuleFilePath);
		}

		$aModuleConfig=[];
		$this->BrowseArrayStructure($oModuleConfigInfo->value, $aModuleConfig);

		if (! is_array($aModuleConfig)){
			throw new ModuleDiscoveryServiceException("3rd parameter to SetupWebPage::AddModule not an array: " . get_class($oModuleConfigInfo->value), 0, null, $sModuleFilePath);
		}
		return [
			$sModuleFilePath,
			$sModuleId,
			$aModuleConfig
		];
	}

	/**
	 * @param string $sModuleFilePath
	 * @param \PhpParser\Node\Stmt\If_ $oNode
	 *
	 * @return array|null
	 * @throws \ModuleDiscoveryServiceException
	 */
	private function BrowseIfStructure(string $sModuleFilePath, \PhpParser\Node\Stmt\If_ $oNode) : ?array
	{
		$bCondition = $this->EvaluateBooleanExpression($oNode->cond);
		if ($bCondition) {
			foreach ($oNode->stmts as $oSubNode) {
				if ($oSubNode instanceof \PhpParser\Node\Stmt\Expression) {
					$aModuleConfig = $this->ParseCallToAddModuleAndReturnModuleConfiguration($sModuleFilePath, $oSubNode);
					if (!is_null($aModuleConfig)) {
						return $aModuleConfig;
					}
				}
			}
			return null;
		}

		foreach ($oNode->elseifs as $oElseIfSubNode) {
			/** @var \PhpParser\Node\Stmt\ElseIf_ $oElseIfSubNode*/
			$bCondition = $this->EvaluateBooleanExpression($oElseIfSubNode->cond);
			if($bCondition){
				$aModuleConfig = $this->ParseStatementsAndReturnModuleConfiguration($sModuleFilePath, $oNode->stmts);
				if (!is_null($aModuleConfig)) {
					return $aModuleConfig;
				}
				break;
			}
		}

		$aModuleConfig = $this->ParseStatementsAndReturnModuleConfiguration($sModuleFilePath, $oNode->else->stmts);
		return $aModuleConfig;
	}


	private function ParseStatementsAndReturnModuleConfiguration(string $sModuleFilePath, array $aStmts) : ?array
	{
		foreach ($aStmts as $oSubNode) {
			if ($oSubNode instanceof \PhpParser\Node\Stmt\Expression) {
				$aModuleConfig = $this->ParseCallToAddModuleAndReturnModuleConfiguration($sModuleFilePath, $oSubNode);
				if (!is_null($aModuleConfig)) {
					return $aModuleConfig;
				}
			}
		}

		return null;
	}

	private function EvaluateConstantExpression(\PhpParser\Node\Expr\ArrayItem|\PhpParser\Node\Expr\ConstFetch $oValue) : mixed
	{
		$bResult = false;
		try{
			@eval('$bResult = '.$oValue->name.';');
		} catch (Throwable $t) {
			throw new ModuleDiscoveryServiceException("Eval of ' . $oValue->name . ' caused an error: ".$t->getMessage());
		}

		return $bResult;
	}

	private function EvaluateBooleanExpression(\PhpParser\Node\Expr $oCondExpression) : bool
	{
		//var_dump($oCondExpression);
		return true;
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
	public function __construct($sMessage, $iHttpCode = 0, Exception $oPrevious = null, $sModuleFile=null)
	{
		$e = new \Exception("");

		$aContext = ['previous' => $oPrevious?->getMessage(), 'stack' => $e->getTraceAsString()];
		if (! is_null($sModuleFile)){
			$aContext['module_file'] = $sModuleFile;
		}
		SetupLog::Warning($sMessage, null, $aContext);
		parent::__construct($sMessage, $iHttpCode, $oPrevious);
	}
}
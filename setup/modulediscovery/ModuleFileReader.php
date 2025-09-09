<?php

use Combodo\iTop\PhpParser\Evaluation\PhpExpressionEvaluator;

require_once __DIR__ . '/ModuleFileReaderException.php';
require_once APPROOT . 'sources/PhpParser/Evaluation/PhpExpressionEvaluator.php';

class ModuleFileReader {
	private static ModuleFileReader $oInstance;
	private	static int $iDummyClassIndex = 0;

	private	PhpExpressionEvaluator $oPhpExpressionEvaluator;

	const FUNC_CALL_WHITELIST=[
		"function_exists",
		"class_exists",
		"method_exists"
	];

	const STATIC_CALLWHITELIST=[
		"utils::GetItopVersionWikiSyntax"
	];

	protected function __construct() {
		$this->oPhpExpressionEvaluator = new PhpExpressionEvaluator(static::FUNC_CALL_WHITELIST, static::STATIC_CALLWHITELIST);
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
	 * @param string $sModuleFile
	 * @return array
	 * @throws ModuleFileReaderException
	 */
	public function ReadModuleFileInformation(string $sModuleFilePath) : array
	{
		try
		{
			$oParser = (new \PhpParser\ParserFactory())->createForNewestSupportedVersion();
			$aNodes = $oParser->parse(file_get_contents($sModuleFilePath));
		}
		catch (PhpParser\Error $e) {
			throw new \ModuleFileReaderException($e->getMessage(), 0, $e, $sModuleFilePath);
		}

		try {
			foreach ($aNodes as $sKey => $oNode) {
				if ($oNode instanceof \PhpParser\Node\Stmt\Expression) {
					$aModuleInfo = $this->GetModuleInformationFromAddModuleCall($sModuleFilePath, $oNode);
					if (! is_null($aModuleInfo)){
						$this->CompleteModuleInfoWithFilePath($aModuleInfo);
						return $aModuleInfo;
					}
				}

				if ($oNode instanceof PhpParser\Node\Stmt\If_) {
					$aModuleInfo = $this->GetModuleInformationFromIf($sModuleFilePath, $oNode);
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
	 * Read the information from a module file (module.xxx.php)
	 * Warning: this method is using eval() function to load the ModuleInstallerAPI classes.
	 * Current method is never called at design/runtime. It is acceptable to use it during setup only.
	 * @param string $sModuleFile
	 * @return array
	 * @throws ModuleFileReaderException
	 */
	public function ReadModuleFileInformationUnsafe(string $sModuleFilePath) : array
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
			$this->ReadModuleFileInformationUnsafe($sModuleFilePath);
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

	/**
	 * @param string $sModuleFilePath
	 * @param \PhpParser\Node\Expr\Assign $oAssignation
	 *
	 * @return array|null
	 * @throws \ModuleFileReaderException
	 */
	private function GetModuleInformationFromAddModuleCall(string $sModuleFilePath, \PhpParser\Node\Stmt\Expression $oExpression) : ?array
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
			throw new ModuleFileReaderException("Not enough parameters when calling SetupWebPage::AddModule", 0, null, $sModuleFilePath);
		}

		$oModuleId = $aArgs[1];
		if (false === ($oModuleId instanceof PhpParser\Node\Arg)) {
			throw new ModuleFileReaderException("2nd parameter to SetupWebPage::AddModule call issue: " . get_class($oModuleId), 0, null, $sModuleFilePath);
		}

		/** @var PhpParser\Node\Arg $oModuleId */
		if (false === ($oModuleId->value instanceof PhpParser\Node\Scalar\String_)) {
			throw new ModuleFileReaderException("2nd parameter to SetupWebPage::AddModule not a string: " . get_class($oModuleId->value), 0, null, $sModuleFilePath);
		}

		$sModuleId = $this->oPhpExpressionEvaluator->EvaluateExpression($oModuleId->value);

		$oModuleConfigInfo = $aArgs[2];
		if (false === ($oModuleConfigInfo instanceof PhpParser\Node\Arg)) {
			throw new ModuleFileReaderException("3rd parameter to SetupWebPage::AddModule call issue: " . get_class($oModuleConfigInfo), 0, null, $sModuleFilePath);
		}

		/** @var PhpParser\Node\Arg $oModuleConfigInfo */
		if (false === ($oModuleConfigInfo->value instanceof PhpParser\Node\Expr\Array_)) {
			throw new ModuleFileReaderException("3rd parameter to SetupWebPage::AddModule not an array: " . get_class($oModuleConfigInfo->value), 0, null, $sModuleFilePath);
		}

		$aModuleConfig = $this->oPhpExpressionEvaluator->EvaluateExpression($oModuleConfigInfo->value);

		if (! is_array($aModuleConfig)){
			throw new ModuleFileReaderException("3rd parameter to SetupWebPage::AddModule not an array: " . get_class($oModuleConfigInfo->value), 0, null, $sModuleFilePath);
		}

		return [
			$sModuleFilePath,
			$sModuleId,
			$aModuleConfig,
		];
	}

	/**
	 * @param string $sModuleFilePath
	 * @param \PhpParser\Node\Stmt\If_ $oNode
	 *
	 * @return array|null
	 * @throws \ModuleFileReaderException
	 */
	private function GetModuleInformationFromIf(string $sModuleFilePath, \PhpParser\Node\Stmt\If_ $oNode) : ?array
	{
		$bCondition = $this->oPhpExpressionEvaluator->EvaluateExpression($oNode->cond);
		if ($bCondition) {
			foreach ($oNode->stmts as $oSubNode) {
				if ($oSubNode instanceof \PhpParser\Node\Stmt\Expression) {
					$aModuleConfig = $this->GetModuleInformationFromAddModuleCall($sModuleFilePath, $oSubNode);
					if (!is_null($aModuleConfig)) {
						return $aModuleConfig;
					}
				}
			}

			return null;
		}

		if (! is_null($oNode->elseifs)) {
			foreach ($oNode->elseifs as $oElseIfSubNode) {
				/** @var \PhpParser\Node\Stmt\ElseIf_ $oElseIfSubNode */
				$bCondition = $this->oPhpExpressionEvaluator->EvaluateExpression($oElseIfSubNode->cond);
				if ($bCondition) {
					return $this->GetModuleConfigurationFromStatement($sModuleFilePath, $oElseIfSubNode->stmts);
				}
			}
		}

		if (! is_null($oNode->else)) {
			return $this->GetModuleConfigurationFromStatement($sModuleFilePath, $oNode->else->stmts);
		}

		return null;
	}

	private function GetModuleConfigurationFromStatement(string $sModuleFilePath, array $aStmts) : ?array
	{
		foreach ($aStmts as $oSubNode) {
			if ($oSubNode instanceof \PhpParser\Node\Stmt\Expression) {
				$aModuleConfig = $this->GetModuleInformationFromAddModuleCall($sModuleFilePath, $oSubNode);
				if (!is_null($aModuleConfig)) {
					return $aModuleConfig;
				}
			}
		}

		return null;
	}
}

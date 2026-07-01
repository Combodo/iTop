<?php

namespace Combodo\iTop\Setup\FeatureRemoval;

use ContextTag;
use CoreException;
use Exception;
use IssueLog;
use SetupLog;
use utils;

class ModelReflectionSerializer
{
	private static ModelReflectionSerializer $oInstance;

	protected function __construct()
	{
	}

	final public static function GetInstance(): ModelReflectionSerializer
	{
		if (!isset(self::$oInstance)) {
			self::$oInstance = new ModelReflectionSerializer();
		}

		return self::$oInstance;
	}

	final public static function SetInstance(?ModelReflectionSerializer $oInstance): void
	{
		self::$oInstance = $oInstance;
	}

	public function GetModelFromEnvironment(string $sEnv): array
	{
		IssueLog::Debug(__METHOD__, null, ['env' => $sEnv]);

		$sPHPExec = trim(utils::GetConfig()->Get('php_path'));
		$aOutput = null;
		$iRes = 0;

		$sErrorLabel = "Data consistency check failed: %s";

		//preliminary check
		$sEnvDir = APPROOT."env-$sEnv";
		if (! is_dir($sEnvDir)) {
			$sMsg = sprintf($sErrorLabel, "Missing environment ($sEnvDir)");
			$this->LogSetupError($sMsg);
			throw new CoreException($sMsg);
		}

		$sConfigFile = APPROOT."conf/$sEnv/config-itop.php";
		if (! is_file($sConfigFile)) {
			$sMsg = sprintf($sErrorLabel, "Missing configuration ($sConfigFile)");
			$this->LogSetupError($sMsg);
			throw new CoreException($sMsg);
		}

		$sCommandLine = sprintf("$sPHPExec %s/get_model_reflection.php --env=%s", __DIR__, escapeshellarg($sEnv));
		exec($sCommandLine, $aOutput, $iRes);
		if ($iRes != 0) {
			$sError = $aOutput[0] ?? 'Invalid output when serializing model';
			$this->LogSetupError(sprintf($sErrorLabel, '(cli error) '.$sError), null, ['env' => $sEnv, 'code' => $iRes, "output" => $aOutput, 'cmd' => $sCommandLine]);
			throw new CoreException(sprintf($sErrorLabel, $sError));
		}

		$aClasses = json_decode($aOutput[0] ?? null, true);
		if (false === $aClasses) {
			$sMsg = sprintf($sErrorLabel, 'Invalid JSON');
			$this->LogSetupError($sMsg, null, ['env' => $sEnv, "output" => $aOutput]);
			throw new CoreException($sMsg);
		}

		if (!is_array($aClasses)) {
			$sError = $aOutput[0] ?? 'Invalid json array when serializing model';
			$this->LogSetupError(sprintf($sErrorLabel, '(JSON output not an array) '.$sError), null, ['env' => $sEnv, "classes" => $aClasses, "output" => $aOutput]);
			throw new CoreException(sprintf($sErrorLabel, $sError));
		}

		return $aClasses;
	}

	//could be shared with others in log APIs ?
	private function LogSetupError($sMessage, $sChannel = null, $aContext = []): void
	{
		SetupLog::Enable(APPROOT.'log/setup.log');
		SetupLog::Error($sMessage, $sChannel, $aContext);
	}
}

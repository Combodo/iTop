<?php

namespace Combodo\iTop\Setup\FeatureRemoval;

use ContextTag;
use CoreException;
use Exception;
use IssueLog;
use MetaModel;
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
		IssueLog::Info(__METHOD__, null, ['env' => $sEnv]);

		$sCurrentEnvt = MetaModel::GetEnvironment();
		if ($sCurrentEnvt === $sEnv) {
			$aClasses = MetaModel::GetClasses();
			if (count($aClasses) === 0) {
				//MetaModel not started yet
				$sConfFile = utils::GetConfigFilePath($sEnv);

				MetaModel::Startup($sConfFile, false /* $bModelOnly */, false /* $bAllowCache */, false /* $bTraceSourceFiles */, $sEnv);
				$aClasses = MetaModel::GetClasses();
			}
			return $aClasses;
		}

		$sPHPExec = trim(utils::GetConfig()->Get('php_path'));
		$sOutput = "";
		$iRes = 0;

		$sCommandLine = sprintf("$sPHPExec %s/get_model_reflection.php --env=%s", __DIR__, escapeshellarg($sEnv));
		exec($sCommandLine, $sOutput, $iRes);
		if ($iRes != 0) {
			$this->LogErrorWithProperLogger("Cannot get classes", null, ['env' => $sEnv, 'code' => $iRes, "output" => $sOutput]);
			throw new CoreException("Cannot get classes from env ".$sEnv);
		}

		$aClasses = json_decode($sOutput[0] ?? null, true);
		if (false === $aClasses) {
			$this->LogErrorWithProperLogger("Invalid JSON", null, ['env' => $sEnv, "output" => $sOutput]);
			throw new Exception("cannot get classes");
		}

		if (!is_array($aClasses)) {
			$this->LogErrorWithProperLogger("not an array", null, ['env' => $sEnv, "classes" => $aClasses, "output" => $sOutput]);
			throw new Exception("cannot get classes from $sEnv");
		}

		return $aClasses;
	}

	//could be shared with others in log APIs ?
	private function LogErrorWithProperLogger($sMessage, $sChannel = null, $aContext = []): void
	{
		if (ContextTag::Check(ContextTag::TAG_SETUP)) {
			SetupLog::Error($sMessage, $sChannel, $aContext);
		} else {
			IssueLog::Error($sMessage, $sChannel, $aContext);
		}
	}
}

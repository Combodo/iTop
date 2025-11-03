<?php

namespace Combodo\iTop\Setup\FeatureRemoval;

use CoreException;
use Exception;

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
		\IssueLog::Info(__METHOD__, null, ['env' => $sEnv]);
		$sPHPExec = trim(\MetaModel::GetConfig()->Get('php_path'));
		$sOutput = "";
		$iRes = 0;
		exec(sprintf("$sPHPExec %s/get_model_reflection.php --env='%s'", __DIR__, $sEnv), $sOutput, $iRes);
		if ($iRes != 0) {
			\IssueLog::Error("Cannot get classes", null, ['code' => $iRes, "output" => $sOutput]);
			throw new CoreException("Cannot get classes");
		}

		$aClasses = json_decode($sOutput[0] ?? null, true);
		if (false === $aClasses) {
			\IssueLog::Error("Invalid JSON", null, ["output" => $sOutput]);
			throw new Exception("cannot get classes");
		}

		if (!is_array($aClasses)) {
			\IssueLog::Error("not an array", null, ["classes" => $aClasses]);
			throw new Exception("cannot get classes");
		}

		return $aClasses;
	}
}
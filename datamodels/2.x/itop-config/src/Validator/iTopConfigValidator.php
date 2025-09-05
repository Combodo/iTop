<?php

namespace Combodo\iTop\Config\Validator;

use AsyncTask;
use ReflectionClass;

class iTopConfigValidator {
	const CONFIG_ERROR = 0;
	const CONFIG_WARNING = 1;
	const CONFIG_INFO = 2;
	const CONFIG_SUCCESS = 3;

	/**
	 * @param $sRawConfig
	 *
	 * @throws \Exception
	 */
	public function Validate($sRawConfig):void
	{
		$oiTopConfigValidator = new iTopConfigAstValidator();
		$oiTopConfigValidator->Validate($sRawConfig);

		/// 2 - only after we are sure that there is no malicious code, we can perform a syntax check!
		$oiTopConfigValidator = new iTopConfigSyntaxValidator();
		$oiTopConfigValidator->Validate($sRawConfig);
	}

	function DBPasswordIsOk($sPassword):bool
	{
		$bIsWindows = (array_key_exists('WINDIR', $_SERVER) || array_key_exists('windir', $_SERVER));

		if ($bIsWindows && (preg_match("/[%!\"]/U", $sPassword) !== 0)) {
			return false;
		}

		return true;
	}


	public function CheckAsyncTasksRetryConfig(\Config $oTempConfig): array
	{
		$aWarnings = [];
		foreach (get_declared_classes() as $sPHPClass) {
			$oRefClass = new ReflectionClass($sPHPClass);
			if ($oRefClass->isSubclassOf('AsyncTask') && !$oRefClass->isAbstract()) {
				$aMessages = AsyncTask::CheckRetryConfig($oTempConfig, $oRefClass->getName());

				if (count($aMessages) !== 0) {
					foreach ($aMessages as $sMessage) {
						$aWarnings[] = $sMessage;
					}
				}
			}
		}
		return $aWarnings;

	}
}
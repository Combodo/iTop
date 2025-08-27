<?php

class ModuleDiscoveryServiceException extends Exception
{
	/**
	 * ModuleDiscoveryServiceException constructor.
	 *
	 * @param string $sMessage
	 * @param int $iHttpCode
	 * @param Exception|null $oPrevious
	 */
	public function __construct($sMessage, $iHttpCode = 0, Exception $oPrevious = null, $sModuleFile = null)
	{
		$e = new \Exception("");

		$aContext = ['previous' => $oPrevious?->getMessage(), 'stack' => $e->getTraceAsString()];
		if (!is_null($sModuleFile)) {
			$aContext['module_file'] = $sModuleFile;
		}
		SetupLog::Warning($sMessage, null, $aContext);
		parent::__construct($sMessage, $iHttpCode, $oPrevious);
	}
}
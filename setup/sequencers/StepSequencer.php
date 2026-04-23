<?php

/**
 * Copyright (C) 2013-2026 Combodo SAS
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */
abstract class StepSequencer
{
	public const OK = 1;
	public const ERROR = 2;
	public const WARNING = 3;
	public const INFO = 4;
	protected array $aStepsHistory = [];
	protected Parameters $oParams;
	protected ?Config $oTestConfig = null;
	protected RunTimeEnvironment $oRunTimeEnvironment;
	protected string $sSourceDesc;

	/**
	 * @param \Parameters $oParams
	 * @param \RunTimeEnvironment|null $oRunTimeEnvironment
	 * @param string $sSourceDesc
	 *
	 * @throws \CoreException
	 */
	public function __construct(Parameters $oParams, ?RunTimeEnvironment $oRunTimeEnvironment = null, string $sSourceDesc = 'Setup')
	{
		if (is_null($oRunTimeEnvironment)) {
			$sEnvironment = $oParams->Get('target_env', 'production');
			$this->oRunTimeEnvironment = new RunTimeEnvironment($sEnvironment, false);
		} else {
			$this->oRunTimeEnvironment = $oRunTimeEnvironment;
		}

		$this->oParams = $oParams;
		utils::SetConfig($this->GetConfig());
		$this->sSourceDesc = $sSourceDesc;
	}

	public function LogStep($sStep, $aResult)
	{
		$this->aStepsHistory[] = ['step' => $sStep, 'result' => $aResult];
	}
	public function GetHistory()
	{
		return $this->aStepsHistory;
	}

	/**
	 * Runs all the installation steps in one go and directly outputs
	 * some information about the progress and the success of the various
	 * sequential steps.
	 *
	 * @param bool $bVerbose
	 * @param string|null $sMessage
	 * @param string|null $sComment
	 *
	 * @return boolean True if the installation was successful, false otherwise
	 */
	public function ExecuteAllSteps(bool $bVerbose = true, ?string &$sMessage = null, ?string $sComment = null)
	{
		$sStep = '';
		$sStepLabel = '';
		$iOverallStatus = self::OK;
		do {

			if ($bVerbose) {
				if ($sStep != '') {
					echo "$sStepLabel\n";
					echo "Executing '$sStep'\n";
				} else {
					echo "Starting...\n";
				}
			}
			$aRes = $this->ExecuteStep($sStep, $sComment);
			$this->LogStep($sStep, $aRes);
			$sStep = $aRes['next-step'];
			$sStepLabel = $aRes['next-step-label'];
			$sMessage = $aRes['message'];
			if ($bVerbose) {
				switch ($aRes['status']) {
					case self::OK:
						echo "Ok. ".$aRes['percentage-completed']." % done.\n";
						break;

					case self::ERROR:
						$iOverallStatus = self::ERROR;
						echo "Error: ".$aRes['message']."\n";
						break;

					case self::WARNING:
						$iOverallStatus = self::WARNING;
						echo "Warning: ".$aRes['message']."\n";
						echo $aRes['percentage-completed']." % done.\n";
						break;

					case self::INFO:
						echo "Info: ".$aRes['message']."\n";
						echo $aRes['percentage-completed']." % done.\n";
						break;
				}
			} else {
				switch ($aRes['status']) {
					case self::ERROR:
						$iOverallStatus = self::ERROR;
						break;
					case self::WARNING:
						$iOverallStatus = self::WARNING;
						break;
				}
			}
		} while (($aRes['status'] != self::ERROR) && ($aRes['next-step'] != ''));

		return ($iOverallStatus == self::OK);
	}

	protected function GetNextStep(string $sNextStep, string $sNextStepLabel, int $iPercentComplete, string $sMessage = '', int $iStatus = self::OK): array
	{
		return [
			'status' => $iStatus,
			'message' => $sMessage,
			'next-step' => $sNextStep,
			'next-step-label' => $sNextStepLabel,
			'percentage-completed' => $iPercentComplete,
		];
	}

	protected function DoLogParameters($sPrefix = 'install-', $sOperation = 'Installation')
	{
		// Log the parameters...
		$oDoc = new DOMDocument('1.0', 'UTF-8');
		$oDoc->preserveWhiteSpace = false;
		$oDoc->formatOutput = true;
		$this->oParams->ToXML($oDoc, null, 'installation');
		$sXML = $oDoc->saveXML();
		$sSafeXml = preg_replace('|<pwd>([^<]*)</pwd>|', '<pwd>**removed**</pwd>', $sXML);
		SetupLog::Info('======= '.$sOperation." starts =======\nParameters:\n$sSafeXml\n");

		// Save the response file as a stand-alone file as well
		$sFileName = $sPrefix.date('Y-m-d');
		$index = 0;
		while (file_exists(APPROOT.'log/'.$sFileName.'.xml')) {
			$index++;
			$sFileName = $sPrefix.date('Y-m-d').'-'.$index;
		}
		file_put_contents(APPROOT.'log/'.$sFileName.'.xml', $sSafeXml);
	}

	protected function GetConfig()
	{
		if (! is_null($this->oTestConfig)) {
			return $this->oTestConfig;
		}

		// Caching config here is a bad idea, the first config loaded does not contain module settings
		$sTargetEnvironment = $this->oRunTimeEnvironment->GetBuildEnv();
		$sConfigFile = APPCONF.$sTargetEnvironment.'/'.ITOP_CONFIG_FILE;
		try {
			if (file_exists($sConfigFile)) {
				$oConfig = new Config($sConfigFile);
			} else {
				$oConfig = new Config();
			}

			$oConfig->Set('access_mode', ACCESS_FULL);
		} catch (Exception $e) {
			SetupLog::Exception("Setup error", $e);
			throw $e;
		}

		$aParamValues = $this->oParams->GetParamForConfigArray();
		$oConfig->UpdateFromParams($aParamValues);

		return $oConfig;
	}

	/**
	 * Executes the next step of the installation and reports about the progress
	 * and the next step to perform
	 *
	 * @param string $sStep The identifier of the step to execute
	 * @param string|null $sInstallComment
	 *
	 * @return array (status => , message => , percentage-completed => , next-step => , next-step-label => )
	 */
	abstract public function ExecuteStep($sStep = '', $sInstallComment = null): array;
}

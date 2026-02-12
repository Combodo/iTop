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
	public function ExecuteAllSteps($bVerbose = true, &$sMessage = null, $sComment = null)
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

	abstract public function ExecuteStep($sStep = '', $sComment = null);
}

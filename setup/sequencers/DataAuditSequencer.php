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

require_once(APPROOT.'setup/parameters.class.inc.php');
require_once(APPROOT.'setup/xmldataloader.class.inc.php');
require_once APPROOT.'setup/feature_removal/SetupAudit.php';

require_once(APPROOT.'setup/sequencers/StepSequencer.php');

class DataAuditSequencer extends StepSequencer
{
	public const DATA_AUDIT_FAILED = 100;

	protected const LABELS = [
		'copy' => 'Copying data model files',
		'compile' => 'Compiling the data model',
		'setup-audit' => 'Checking data consistency with the new data model',
		'complete' => 'Check Completed',
	];
	protected const SUCCESS_LABELS = [
		'copy' => 'Data model files copied',
		'compile' => 'Data model compilation completed',
		'setup-audit' => 'Data consistency check completed',
		'complete' => 'All checks completed',
	];

	/**
	 * @inherit
	 */
	public function ExecuteStep($sStep = '', $sInstallComment = null): array
	{
		$fStart = microtime(true);
		try {
			/**
			 * @since 3.2.0 move the ContextTag init at the very beginning of the method
			 * @noinspection PhpUnusedLocalVariableInspection
			 */
			$oContextTag = new ContextTag(ContextTag::TAG_SETUP);
			SetupLog::Info("##### STEP {$sStep} start");
			switch ($sStep) {
				case '':
					return $this->ComputeNextStep($sStep);

				case 'copy':
					$this->oRunTimeEnvironment->CopySetupFiles();
					return $this->ComputeNextStep($sStep);

				case 'compile':
					$aSelectedModules = $this->oParams->Get('selected_modules', []);
					$sSourceDir = $this->oParams->Get('source_dir', 'datamodels/latest');
					$sExtensionDir = $this->oParams->Get('extensions_dir', 'extensions');
					$aRemovedExtensionCodes = $this->oParams->Get('removed_extensions', []);
					$bUseSymbolicLinks = $this->oParams->Get('use_symbolic_links', null) === 'on';
					MetaModel::ResetAllCaches($this->oRunTimeEnvironment->GetBuildEnv());
					$this->oRunTimeEnvironment->DoCompile(
						$aRemovedExtensionCodes,
						$aSelectedModules,
						$sSourceDir,
						$sExtensionDir,
						$bUseSymbolicLinks
					);
					return $this->ComputeNextStep($sStep);

				case 'setup-audit':
					$this->oRunTimeEnvironment->DataToCleanupAudit();
					return $this->ComputeNextStep($sStep);

				case 'complete':
					return $this->GetNextStep('', 'Completed', 100);

				default:
					return $this->GetNextStep('', "Unknown setup step '$sStep'.", 100, '', '', self::ERROR);

			}
		} catch (Exception $e) {
			SetupLog::Exception("$sStep failed", $e);
			$aResult = $this->GetNextStep('', '', 100, $e->getMessage(), '', self::ERROR);
			$aResult['error_code'] = $e->getCode();
			return $aResult;
		} finally {
			$fDuration = round(microtime(true) - $fStart, 2);
			SetupLog::Info("##### STEP {$sStep} duration: {$fDuration}s");
		}
	}

	protected function IsDataAuditRequired(): bool
	{
		if (! array_key_exists('setup-audit', $this->oParams->Get('optional_steps', []))) {
			return false;
		}

		if ('install' === $this->oParams->Get('mode')) {
			return false;
		}

		$aInstalledInfo = $this->oRunTimeEnvironment->GetApplicationVersion($this->GetConfig());
		$sInstalledVersion = $aInstalledInfo['product_version'];

		if ($sInstalledVersion !== ITOP_VERSION_FULL) {
			return false;
		}

		$sFinalEnvDir = APPROOT.'env-'.$this->oRunTimeEnvironment->GetFinalEnv();
		return is_dir($sFinalEnvDir);
	}

	public function GetStepNames(): array
	{
		$aStepNames = [''];
		if (array_key_exists('copy', $this->oParams->Get('optional_steps', []))) {
			$aStepNames[] = 'copy';
		}
		$aStepNames[] = 'compile';
		if ($this->IsDataAuditRequired()) {
			$aStepNames[] = 'setup-audit';
		}
		$aStepNames[] = 'complete';

		return $aStepNames;
	}
}

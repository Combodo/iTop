<?php

/**
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

use Combodo\iTop\Setup\ModuleDependency\DependencyExpression;
use Combodo\iTop\Setup\ModuleDependency\Module;

require_once __DIR__.'/ModuleInstallationException.php';
require_once(APPROOT.'/setup/moduledependency/module.class.inc.php');

class InstallationChoicesToModuleConverter
{
	private static ?InstallationChoicesToModuleConverter $oInstance;

	protected function __construct()
	{
	}

	public static function GetInstance(): InstallationChoicesToModuleConverter
	{
		if (!isset(self::$oInstance)) {
			self::$oInstance = new InstallationChoicesToModuleConverter();
		}

		return self::$oInstance;
	}

	final public static function SetInstance(?InstallationChoicesToModuleConverter $oInstance): void
	{
		self::$oInstance = $oInstance;
	}

	/**
	 * @param array<string> $aInstallationChoices
	 * @param array<string> $aSearchDirs
	 * @param string|null $sInstallationFilePath
	 * @param array|null $aExtensionDirs : module/extension dirs to load if they are compliant with choices
	 *
	 * @return array<string>
	 * @throws \ModuleInstallationException
	 */
	public function GetModules(array $aInstallationChoices, array $aSearchDirs, ?string $sInstallationFilePath = null, ?array $aExtensionDirs = null): array
	{
		$aPackageModules = $this->GetAllModules($aSearchDirs);

		$bInstallationFileProvided = ! is_null($sInstallationFilePath) && is_file($sInstallationFilePath);

		if ($bInstallationFileProvided) {
			$oXMLParameters = new XMLParameters($sInstallationFilePath);
			$aSteps = $oXMLParameters->Get('steps', []);
			if (!is_array($aSteps)) {
				return [];
			}
			$aInstalledModuleNames = $this->FindInstalledPackageModules($aPackageModules, $aInstallationChoices, $aSteps);
		} else {
			$aInstalledModuleNames = $this->FindInstalledPackageModules($aPackageModules, $aInstallationChoices);
		}

		$aInstalledModules = [];
		foreach (array_keys($aPackageModules) as $sModuleId) {
			list($sModuleName) = ModuleDiscovery::GetModuleName($sModuleId);
			if (in_array($sModuleName, $aInstalledModuleNames)) {
				$aInstalledModules[$sModuleName] = $sModuleId;
			}
		}

		if (!is_null($aExtensionDirs)) {
			foreach (array_keys($this->GetAllModules($aExtensionDirs)) as $sModuleId) {
				$oModule = new Module($sModuleId);

				$sPreviousModuleId = $aInstalledModules[$oModule->GetModuleName()] ?? null;
				if (is_null($sPreviousModuleId)) {
					$aInstalledModules[$oModule->GetModuleName()] = $sModuleId;
					continue;
				}

				$oPreviousModule = new Module($sPreviousModuleId);
				if (version_compare($oModule->GetVersion(), $oPreviousModule->GetVersion(), '>')) {
					$aInstalledModules[$oModule->GetModuleName()] = $sModuleId;
				}
			}
		}

		return array_values($aInstalledModules);
	}

	/**
	 * Provide all modules used to compute in @see GetModules()
	 *
	 * @param $aSearchDirs array of directories to search (absolute paths)
	 *
	 * @return array<string, array> A big array moduleID => ModuleData
	 * @throws \Exception
	 */
	protected function GetAllModules(array $aSearchDirs): array
	{

		return ModuleDiscovery::GetAllModules($aSearchDirs);
	}

	private function FindInstalledPackageModules(array $aPackageModules, array $aInstallationChoices, ?array $aInstallationDescription = null): array
	{
		$aInstalledModules = [];

		$this->ProcessDefaultModules($aPackageModules, $aInstalledModules);

		if (is_null($aInstallationDescription)) {
			//in legacy usecase: choices are flat modules list already
			foreach ($aInstallationChoices as $sModuleName) {
				$aInstalledModules[$sModuleName] = true;
			}
		} else {
			$this->GetModuleNamesFromInstallationChoices($aInstallationChoices, $aInstallationDescription, $aInstalledModules);
		}

		$this->ProcessAutoSelectModules($aPackageModules, $aInstalledModules);

		return array_keys($aInstalledModules);
	}

	private function IsDefaultModule(string $sModuleId, array $aModule): bool
	{
		if (($sModuleId === ROOT_MODULE)) {
			return false;
		}

		if (isset($aModule['auto_select'])) {
			return false;
		}

		if ($aModule['category'] === 'authentication') {
			return true;
		}

		return !$aModule['visible'];
	}

	private function ProcessDefaultModules(array &$aPackageModules, array &$aInstalledModules): void
	{
		foreach ($aPackageModules as $sModuleId => $aModule) {
			if ($this->IsDefaultModule($sModuleId, $aModule)) {
				list($sModuleName) = ModuleDiscovery::GetModuleName($sModuleId);
				$aInstalledModules[$sModuleName] = true;
				unset($aPackageModules[$sModuleId]);
			}
		}
	}

	private function IsAutoSelectedModule(array $aInstalledModules, string $sModuleId, array $aModule): bool
	{
		if (($sModuleId === ROOT_MODULE)) {
			return false;
		}

		if (!isset($aModule['auto_select'])) {
			return false;
		}

		try {
			SetupInfo::SetSelectedModules($aInstalledModules);
			return DependencyExpression::GetPhpExpressionEvaluator()->ParseAndEvaluateBooleanExpression($aModule['auto_select']);
		} catch (Exception $e) {
			IssueLog::Error('Error evaluating module auto-select', null, [
				'module' => $sModuleId,
				'error' => $e->getMessage(),
				'evaluated code' => $aModule['auto_select'],
				'stacktrace' => $e->getTraceAsString(),
			]);
		}

		return false;
	}

	private function ProcessAutoSelectModules(array $aPackageModules, array &$aInstalledModules): void
	{
		foreach ($aPackageModules as $sModuleId => $aModule) {
			if ($this->IsAutoSelectedModule($aInstalledModules, $sModuleId, $aModule)) {
				list($sModuleName) = ModuleDiscovery::GetModuleName($sModuleId);
				$aInstalledModules[$sModuleName] = true;
			}
		}
	}

	private function GetModuleNamesFromInstallationChoices(array $aInstallationChoices, array $aInstallationDescription, array &$aModuleNames): void
	{
		foreach ($aInstallationDescription as $aStepInfo) {
			$aOptions = $aStepInfo['options'] ?? null;
			if (is_array($aOptions)) {
				foreach ($aOptions as $aChoiceInfo) {
					$this->ProcessSelectedChoice($aInstallationChoices, $aChoiceInfo, $aModuleNames);
				}
			}
			$aOptions = $aStepInfo['alternatives'] ?? null;
			if (is_array($aOptions)) {
				foreach ($aOptions as $aChoiceInfo) {
					$this->ProcessSelectedChoice($aInstallationChoices, $aChoiceInfo, $aModuleNames);
				}
			}
		}
	}

	private function ProcessSelectedChoice(array $aInstallationChoices, array $aChoiceInfo, array &$aInstalledModules)
	{
		if (!is_array($aChoiceInfo)) {
			return;
		}

		$sMandatory = $aChoiceInfo['mandatory'] ?? 'false';

		$aCurrentModules = $aChoiceInfo['modules'] ?? [];
		$sExtensionCode = $aChoiceInfo['extension_code'];

		$bSelected = ($sMandatory === 'true') || in_array($sExtensionCode, $aInstallationChoices);

		if (!$bSelected) {
			return;
		}

		foreach ($aCurrentModules as $sModuleId) {
			$aInstalledModules[$sModuleId] = true;
		}

		$aAlternatives = $aChoiceInfo['alternatives'] ?? null;
		if (is_array($aAlternatives)) {
			foreach ($aAlternatives as $aSubChoiceInfo) {
				$this->ProcessSelectedChoice($aInstallationChoices, $aSubChoiceInfo, $aInstalledModules);
			}
		}

		$aSubOptionsChoiceInfo = $aChoiceInfo['sub_options'] ?? null;
		if (is_array($aSubOptionsChoiceInfo)) {
			$aSubOptions = $aSubOptionsChoiceInfo['options'] ?? null;
			if (is_array($aSubOptions)) {
				foreach ($aSubOptions as $aSubChoiceInfo) {
					$this->ProcessSelectedChoice($aInstallationChoices, $aSubChoiceInfo, $aInstalledModules);
				}
			}
			$aSubAlternatives = $aSubOptionsChoiceInfo['alternatives'] ?? null;
			if (is_array($aSubAlternatives)) {
				foreach ($aSubAlternatives as $aSubChoiceInfo) {
					$this->ProcessSelectedChoice($aInstallationChoices, $aSubChoiceInfo, $aInstalledModules);
				}
			}
		}
	}
}

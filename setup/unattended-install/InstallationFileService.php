<?php

require_once(dirname(__FILE__, 3) . '/approot.inc.php');
require_once(APPROOT.'/application/utils.inc.php');
require_once(APPROOT.'/application/utils.inc.php');
require_once(APPROOT.'/application/clipage.class.inc.php');
require_once(APPROOT.'/core/config.class.inc.php');
require_once(APPROOT.'/core/log.class.inc.php');
require_once(APPROOT.'/core/kpi.class.inc.php');
require_once(APPROOT.'/core/cmdbsource.class.inc.php');
require_once(APPROOT.'/setup/setuppage.class.inc.php');
require_once(APPROOT.'/setup/wizardcontroller.class.inc.php');
require_once(APPROOT.'/setup/wizardsteps.class.inc.php');
require_once(APPROOT.'/setup/applicationinstaller.class.inc.php');

class InstallationFileService {
	private $sInstallationPath;
	private $aSelectedModules;
	private $aUnSelectedModules;

	public function __construct(string $sInstallationPath) {
		$this->sInstallationPath = $sInstallationPath;
	}

	public function GetSelectedModules(): array {
		return $this->aSelectedModules;
	}

	public function GetUnSelectedModules(): array {
		return $this->aUnSelectedModules;
	}

	public function Init(bool $bAllChecked): void {
		$this->aSelectedModules = [];
		$this->aUnSelectedModules = [];

		$oXMLParameters = new XMLParameters($this->sInstallationPath);
		$aSteps = $oXMLParameters->Get('steps', []);
		if (!is_array($aSteps)) {
			return;
		}

		foreach ($aSteps as $aStepInfo) {
			$aOptions = $aStepInfo["options"] ?? null;
			if (! is_null($aOptions) && is_array($aOptions)) {
				foreach ($aOptions as $aChoiceInfo) {
					$this->ProcessSelectedChoice($aChoiceInfo, $bAllChecked);
				}
			}
			$aOptions = $aStepInfo["alternatives"] ?? null;
			if (! is_null($aOptions) && is_array($aOptions)) {
				foreach ($aOptions as $aChoiceInfo) {
					$this->ProcessSelectedChoice($aChoiceInfo, false);
				}
			}
		}

		$this->aSelectedModules = array_unique($this->aSelectedModules);
		$this->aUnSelectedModules = array_unique($this->aUnSelectedModules);

		$this->aUnSelectedModules = array_diff($this->aUnSelectedModules, $this->aSelectedModules);
	}

	private function ProcessUnSelectedChoice($aChoiceInfo) {
		if (!is_array($aChoiceInfo)) {
			return;
		}

		$aCurrentModules = $aChoiceInfo["modules"] ?? [];
		$this->aUnSelectedModules = array_merge($this->aUnSelectedModules, $aCurrentModules);

		$aAlternatives = $aChoiceInfo["alternatives"] ?? null;
		if (!is_null($aAlternatives) && is_array($aAlternatives)) {
			foreach ($aAlternatives as $aSubChoiceInfo) {
				$this->ProcessUnSelectedChoice($aSubChoiceInfo);
			}
		}

		if (array_key_exists('sub_options', $aChoiceInfo)) {
			if (array_key_exists('options', $aChoiceInfo['sub_options'])) {
				$aSubOptions = $aChoiceInfo['sub_options']['options'];
				if (!is_null($aSubOptions) && is_array($aSubOptions)) {
					foreach ($aSubOptions as $aSubChoiceInfo) {
						$this->ProcessUnSelectedChoice($aSubChoiceInfo);
					}
				}
			}
			if (array_key_exists('alternatives', $aChoiceInfo['sub_options'])) {
				$aSubAlternatives = $aChoiceInfo['sub_options']['alternatives'];
				if (!is_null($aSubAlternatives) && is_array($aSubAlternatives)) {
					foreach ($aSubAlternatives as $aSubChoiceInfo) {
						$this->ProcessUnSelectedChoice($aSubChoiceInfo);
					}
				}
			}
		}
	}

	private function ProcessSelectedChoice($aChoiceInfo, bool $bAllChecked) {
		if (!is_array($aChoiceInfo)) {
			return;
		}

		$sDefault = $aChoiceInfo["default"] ?? "false";
		$sMandatory = $aChoiceInfo["mandatory"] ?? "false";

		$aCurrentModules = $aChoiceInfo["modules"] ?? [];
		$bSelected = $bAllChecked || $sDefault === "true" || $sMandatory === "true";

		if ($bSelected) {
			$this->aSelectedModules = array_merge($this->aSelectedModules, $aCurrentModules);
		} else {
			$this->aUnSelectedModules = array_merge($this->aUnSelectedModules, $aCurrentModules);
		}

		$aAlternatives = $aChoiceInfo["alternatives"] ?? null;
		if (!is_null($aAlternatives) && is_array($aAlternatives)) {
			foreach ($aAlternatives as $aSubChoiceInfo) {
				if ($bSelected) {
					$this->ProcessSelectedChoice($aSubChoiceInfo, $bAllChecked);
				} else {
					$this->ProcessUnSelectedChoice($aSubChoiceInfo);
				}
			}
		}

		if (array_key_exists('sub_options', $aChoiceInfo)) {
			if (array_key_exists('options', $aChoiceInfo['sub_options'])) {
				$aSubOptions = $aChoiceInfo['sub_options']['options'];
				if (!is_null($aSubOptions) && is_array($aSubOptions)) {
					foreach ($aSubOptions as $aSubChoiceInfo) {
						if ($bSelected) {
							$this->ProcessSelectedChoice($aSubChoiceInfo, $bAllChecked);
						} else {
							$this->ProcessUnSelectedChoice($aSubChoiceInfo);
						}
					}
				}
			}
			if (array_key_exists('alternatives', $aChoiceInfo['sub_options'])) {
				$aSubAlternatives = $aChoiceInfo['sub_options']['alternatives'];
				if (!is_null($aSubAlternatives) && is_array($aSubAlternatives)) {
					foreach ($aSubAlternatives as $aSubChoiceInfo) {
						if ($bSelected) {
							$this->ProcessSelectedChoice($aSubChoiceInfo, false);
						} else {
							$this->ProcessUnSelectedChoice($aSubChoiceInfo);
						}
					}
				}
			}
		}
	}
}

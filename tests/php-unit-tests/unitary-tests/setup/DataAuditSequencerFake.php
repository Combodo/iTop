<?php

class DataAuditSequencerFake extends DataAuditSequencer
{
	public function __construct(Parameters $oParams)
	{
		$this->oParams = $oParams;
	}

	protected function DoCopy($aCopies)
	{

	}
	protected function DoBackup($sBackupFileFormat, $sSourceConfigFile, $sMySQLBinDir = null)
	{

	}
	protected function DoCompile($aRemovedExtensionCodes, $aSelectedModules, $sSourceDir, $sExtensionDir, $bUseSymbolicLinks = null, $bEnterMaintenanceMode = true)
	{

	}
	protected function DoUpdateDBSchema($aSelectedModules)
	{

	}
	protected function AfterDBCreate(
		$aAdminParams,
		$aSelectedModules
	) {

	}

	protected function DoLoadFiles(
		$aSelectedModules,
		$bSampleData = false
	) {

	}

	protected function DoCreateConfig(
		$sPreviousConfigFile,
		$sDataModelVersion,
		$aSelectedModuleCodes,
		$aSelectedExtensionCodes,
		$sInstallComment = null
	) {

	}

	protected function DoSetupAudit()
	{

	}
	protected function DoCleanup()
	{

	}

	protected function EnterReadOnlyMode()
	{
	}
	protected function ExitReadOnlyMode()
	{
	}
}

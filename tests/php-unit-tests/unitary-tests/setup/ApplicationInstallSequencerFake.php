<?php

class ApplicationInstallSequencerFake extends ApplicationInstallSequencer
{
	public function __construct(Parameters $oParams)
	{
		$this->oParams = $oParams;
	}

	protected function DoLogParameters($sPrefix = 'install-', $sOperation = '')
	{

	}
	protected function DoCopy($aCopies)
	{

	}
	protected function DoBackup($sBackupFileFormat, $sSourceConfigFile, $sMySQLBinDir = null)
	{

	}
	protected function DoCompile($aRemovedExtensionCodes, $aSelectedModules, $sSourceDir, $sExtensionDir, $bUseSymbolicLinks = null)
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

	protected function EnterReadOnlyMode()
	{
	}
	protected function ExitReadOnlyMode()
	{
	}
}

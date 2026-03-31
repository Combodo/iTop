<?php

/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Service;

use Combodo\iTop\Test\UnitTest\ItopCustomDatamodelTestCase;
use DOMFormatException;
use MFCoreModule;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;
use RunTimeEnvironment;
use SetupLog;
use SetupUtils;
use utils;

/**
 * Class UnitTestRunTimeEnvironment
 *
 * Runtime env. dedicated to creating a temp. environment for a group of unit tests with XML deltas.
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @since N°6097 2.7.10 3.0.4 3.1.1
 */
class UnitTestRunTimeEnvironment extends RunTimeEnvironment
{
	/**
	 * @var false
	 */
	public bool $bUseDelta = true;

	/**
	 * @var true
	 */
	public bool $bUseAdditionalFeatures = false;

	/**
	 * @var string[]
	 */
	protected $aCustomDatamodelFiles = null;

	/**
	 * @var string[]
	 */
	protected $aAdditionExtensionFoldersByCode = null;

	public function CompileFrom($sSourceEnv, $bUseSymLinks = null)
	{
		$sDestModulesDir = APPROOT.'data/'.$this->sBuildEnv.'-modules/';
		if (is_dir($sDestModulesDir)) {
			SetupUtils::rrmdir($sDestModulesDir);
		}

		SetupUtils::copydir(APPROOT.'/data/'.$sSourceEnv.'-modules', $sDestModulesDir, $bUseSymLinks);

		if ($this->bUseAdditionalFeatures) {
			foreach ($this->GetExtensionFoldersToAdd() as $sExtensionCode => $sFolderPath) {
				\SetupLog::Info("ExtensionFoldersToAdd: $sExtensionCode => $sFolderPath");
				$sFolderName = basename($sFolderPath);
				@mkdir($sDestModulesDir.DIRECTORY_SEPARATOR.$sFolderName);
				SetupUtils::copydir($sFolderPath, $sDestModulesDir.DIRECTORY_SEPARATOR.$sFolderName, $bUseSymLinks);
			}
		}

		try {
			parent::CompileFrom($sSourceEnv, $bUseSymLinks);
		} catch (DOMFormatException $e) {
			$sFileName = $sSourceEnv.'.delta.xml';
			SetupLog::Error(__METHOD__, null, [$sFileName => @file_get_contents(APPROOT.'data/'.$sFileName)]);
			throw $e;
		}
	}

	public function IsUpToDate()
	{
		clearstatcache();
		$fLastCompilationTime = filemtime(APPROOT.'env-'.$this->sFinalEnv);
		$aModifiedFiles = [];
		$this->FindFilesModifiedAfter($fLastCompilationTime, APPROOT.'datamodels/2.x', $aModifiedFiles);
		$this->FindFilesModifiedAfter($fLastCompilationTime, APPROOT.'extensions', $aModifiedFiles);
		$this->FindFilesModifiedAfter($fLastCompilationTime, APPROOT.'data/production-modules', $aModifiedFiles);
		foreach ($this->GetCustomDatamodelFiles() as $sCustomDatamodelFile) {
			if (filemtime($sCustomDatamodelFile) > $fLastCompilationTime) {
				$aModifiedFiles[] = $sCustomDatamodelFile;
			}
		}
		// Keep only xml files
		$aFilesToCompile = [];
		foreach ($aModifiedFiles as $sModifiedFile) {
			if (utils::EndsWith($sModifiedFile, '.xml')) {
				$aFilesToCompile[] = $sModifiedFile;
			}
		}
		$aModifiedFiles = $aFilesToCompile;
		if (count($aModifiedFiles) > 0) {
			echo "The following files have been modified after the last compilation:\n";
			foreach ($aModifiedFiles as $sFile) {
				echo " - $sFile\n";
			}
		}
		return (count($aModifiedFiles) === 0);
	}

	/**
	 * @inheritDoc
	 */
	protected function GetMFModulesToCompile($sSourceEnv, $sSourceDir)
	{
		\SetupLog::Info(__METHOD__);
		$aRet = parent::GetMFModulesToCompile($sSourceEnv, $sSourceDir);

		if ($this->bUseDelta) {
			foreach ($this->GetCustomDatamodelFiles() as $sDeltaFile) {
				$sDeltaId = preg_replace('/[^\d\w]/', '', $sDeltaFile);
				$sDeltaName = basename($sDeltaFile);
				$sDeltaDir = dirname($sDeltaFile);
				$oDelta = new MFCoreModule($sDeltaName, "$sDeltaDir/$sDeltaName", $sDeltaFile);
				$aRet[$sDeltaId] = $oDelta;
			}
		}

		return $aRet;
	}

	public function GetExtensionFoldersToAdd(): array
	{
		if (is_null($this->aAdditionExtensionFoldersByCode)) {
			$this->InitViaItopCustomDatamodelTestCaseClasses();
		}

		return $this->aAdditionExtensionFoldersByCode;
	}

	public function GetCustomDatamodelFiles(): array
	{
		if (is_null($this->aCustomDatamodelFiles)) {
			$this->InitViaItopCustomDatamodelTestCaseClasses();
		}

		return $this->aCustomDatamodelFiles;
	}

	public function InitViaItopCustomDatamodelTestCaseClasses()
	{
		$this->aAdditionExtensionFoldersByCode = [];
		$this->aCustomDatamodelFiles = [];

		// Search for the PHP files implementing the method GetDatamodelDeltaAbsPath
		// and extract the delta file path from the method
		// APPROOT, APPROOT/extensions/*, APPROOT/data/production-modules/*, APPROOT/data/production-modules/*/*

		$aTestDirs = [];
		foreach (['', 'extensions/*/', 'data/production-modules/*/', 'data/production-modules/*/*/'] as $sRoot) {
			$aTestDirs = array_merge($aTestDirs, glob(APPROOT.$sRoot.'tests', GLOB_ONLYDIR));
		}

		$aLoadedTestClasses = [];
		foreach ($aTestDirs as $sTestDir) {
			// Iterate on all PHP files in subdirectories
			// Note: grep is not available on Windows, so we will use the PHP Reflection API
			foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($sTestDir)) as $oFile) {
				if ($oFile->isDir()) {
					continue;
				}
				if (! utils::EndsWith($oFile->getFilename(), 'Test.php')) {
					continue;
				}
				$sFile = $oFile->getPathname();
				$sContent = file_get_contents($sFile);
				if (strpos($sContent, 'GetDatamodelDeltaAbsPath') === false) {
					continue;
				}
				$sClass = '';
				$aMatches = [];
				if (preg_match('/namespace\s+([^;]+);/', $sContent, $aMatches)) {
					$sNamespace = $aMatches[1];
					$sClass = $sNamespace.'\\'.basename($sFile, '.php');
				}
				if (preg_match('/\s+class\s+([^ ]+)\s+/', $sContent, $aMatches)) {
					$sClass = $sNamespace.'\\'.$aMatches[1];
				}
				if ($sClass === '') {
					continue;
				}
				if (in_array($sClass, $aLoadedTestClasses)) {
					continue;
				}
				$aLoadedTestClasses[] = $sClass;
				require_once $sFile;
				$oReflectionClass = new ReflectionClass($sClass);
				if ($oReflectionClass->isAbstract()) {
					continue;
				}
				// Check if the class extends ItopCustomDatamodelTestCase
				if (!$oReflectionClass->isSubclassOf(ItopCustomDatamodelTestCase::class)) {
					continue;
				}
				/** @var \Combodo\iTop\Test\UnitTest\ItopCustomDatamodelTestCase $oTestClassInstance */
				$oTestClassInstance = new $sClass();
				if ($oTestClassInstance->GetTestEnvironment() !== $this->sFinalEnv) {
					continue;
				}
				$sDeltaFile = $oTestClassInstance->GetDatamodelDeltaAbsPath();
				if (strlen($sDeltaFile) > 0) {
					if (!is_file($sDeltaFile)) {
						throw new \Exception("Unknown delta file: $sDeltaFile, from test class '$sClass'");
					}
					if (!in_array($sDeltaFile, $this->aCustomDatamodelFiles)) {
						$this->aCustomDatamodelFiles[] = $sDeltaFile;
					}
				}

				$aExtensionsPaths = $oTestClassInstance->GetAdditionalFeaturePaths();
				$this->aAdditionExtensionFoldersByCode = array_merge($this->aAdditionExtensionFoldersByCode, $aExtensionsPaths);
			}
		}
	}

	private function FindFilesModifiedAfter(float $fReferenceTimestamp, string $sPathToScan, array &$aModifiedFiles)
	{
		if (!is_dir($sPathToScan)) {
			return;
		}
		foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($sPathToScan)) as $oFile) {
			if ($oFile->isDir()) {
				continue;
			}
			if (filemtime($oFile->getPathname()) > $fReferenceTimestamp) {
				$aModifiedFiles[] = $oFile->getPathname();
			}
		}
	}
}

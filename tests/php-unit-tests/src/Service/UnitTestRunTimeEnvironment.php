<?php

/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Service;

use Combodo\iTop\Test\UnitTest\ItopCustomDatamodelTestCase;
use IssueLog;
use LogChannels;
use MFCoreModule;
use ReflectionClass;
use RunTimeEnvironment;
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
	 * @inheritDoc
	 */
	protected function GetMFModulesToCompile($sSourceEnv, $sSourceDir)
	{
		$aRet = parent::GetMFModulesToCompile($sSourceEnv, $sSourceDir);

		/** @var string[] $aDeltaFiles Referential of loaded deltas. Mostly to avoid duplicates. */
		$aDeltaFiles = [];
		$aRelatedClasses = $this->GetClassesExtending(
			ItopCustomDatamodelTestCase::class,
			array(
				'[\\\\/]tests[\\\\/]php-unit-tests[\\\\/]vendor[\\\\/]',
				'[\\\\/]tests[\\\\/]php-unit-tests[\\\\/]unitary-tests[\\\\/]datamodels[\\\\/]2.x[\\\\/]authent-local',
			));
		//Combodo\iTop\Test\UnitTest\Application\ApplicationExtensionTest
		//Combodo\iTop\Test\UnitTest\Application\ApplicationExtensionTest
		foreach ($aRelatedClasses as $sClass) {
			$oReflectionClass = new ReflectionClass($sClass);
			$oReflectionMethod = $oReflectionClass->getMethod('GetDatamodelDeltaAbsPath');

			// Filter on classes with an actual XML delta (eg. not \Combodo\iTop\Test\UnitTest\ItopCustomDatamodelTestCase and maybe some other deriving from a class with a delta)
			if ($oReflectionMethod->isAbstract()) {
				continue;
			}

			/** @var \Combodo\iTop\Test\UnitTest\ItopCustomDatamodelTestCase $oTestClassInstance */
			$oTestClassInstance = new $sClass();

			// Check test class is for desired environment
			if ($oTestClassInstance->GetTestEnvironment() !== $this->sFinalEnv) {
				continue;
			}

			// Check XML delta actually exists
			$sDeltaFile = $oTestClassInstance->GetDatamodelDeltaAbsPath();
			if (false === is_file($sDeltaFile)) {
				$this->fail("Could not prepare '$this->sFinalEnv' as the XML delta file '$sDeltaFile' (used in $sClass) does not seem to exist");
			}

			// Avoid duplicates
			if (in_array($sDeltaFile, $aDeltaFiles)) {
				continue;
			}

			// Prepare fake module name for delta
			$sDeltaName = preg_replace('/[^\d\w]/', '', $sDeltaFile);
			// Note: We can't use \MFDeltaModule as we can't specify the ID which leads to only 1 delta being applied... In the future we might introduce a new MFXXXModule, but in the meantime it feels alright (GLA / RQU)
			$oDelta = new MFCoreModule($sDeltaName, $sDeltaName, $sDeltaFile);

			IssueLog::Debug('XML delta found for unit tests', static::class, [
				'Unit test class' => $sClass,
				'Delta file path' => $sDeltaFile,
			]);

			$aDeltaFiles[] = $sDeltaFile;
			$aRet[$sDeltaName] = $oDelta;
		}

		return $aRet;
	}

	protected function GetClassesExtending (string $sExtendedClass, array $aExcludedPath = [])  : array {
		$aMatchingClasses = [];

		$aAutoloadClassMaps =[__DIR__."/../../vendor/composer/autoload_classmap.php"];

		$aClassMap = [];
		$aAutoloaderErrors = [];
		foreach ($aAutoloadClassMaps as $sAutoloadFile) {
			$aTmpClassMap = include $sAutoloadFile;
			/** @noinspection SlowArrayOperationsInLoopInspection we are getting an associative array so the documented workarounds cannot be used */
			$aClassMap = array_merge($aClassMap, $aTmpClassMap);
		}
		foreach ($aClassMap as $sPHPClass => $sPHPFile) {
			$bSkipped = false;
			if (utils::IsNotNullOrEmptyString($sPHPFile)) {
				$sPHPFile = utils::LocalPath($sPHPFile);
				if ($sPHPFile !== false) {
					$sPHPFile = '/'.$sPHPFile; // for regex
					foreach ($aExcludedPath as $sExcludedPath) {
						// Note: We use '#' as delimiters as usual '/' is often used in paths.
						if ($sExcludedPath !== '' && preg_match('#'.$sExcludedPath.'#', $sPHPFile) === 1) {
							$bSkipped = true;
							break;
						}
					}
				} else {
					$bSkipped = true; // file not found
				}
			}

			if (!$bSkipped) {
				try {
					$oRefClass = new ReflectionClass($sPHPClass);
					if ($oRefClass->isSubclassOf($sExtendedClass) &&
						!$oRefClass->isInterface() && !$oRefClass->isAbstract() && !$oRefClass->isTrait()) {
						$aMatchingClasses[] = $sPHPClass;
					}
				}
				catch (Exception $e) {
				}
			}
		}
		return $aMatchingClasses;
	}


}
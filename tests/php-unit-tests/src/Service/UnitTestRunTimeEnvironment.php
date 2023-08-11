<?php

/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Service;

use Combodo\iTop\Test\UnitTest\ItopCustomDatamodelTestCase;
use IssueLog;
use MFCoreModule;
use ReflectionClass;
use RunTimeEnvironment;


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
		foreach (get_declared_classes() as $sClass) {
			// Filter on classes derived from this \Combodo\iTop\Test\UnitTest\ItopCustomDatamodelTestCaseItopCustomDatamodelTestCase
			if (false === is_a($sClass, ItopCustomDatamodelTestCase::class, true)) {
				continue;
			}

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

}
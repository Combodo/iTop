<?php

namespace Combodo\iTop\Test\UnitTest\Setup\FeatureRemoval;

use Combodo\iTop\Setup\FeatureRemoval\SetupAudit;
use Combodo\iTop\Test\UnitTest\ItopCustomDatamodelTestCase;
use Combodo\iTop\Test\UnitTest\Service\UnitTestRunTimeEnvironment;
use Exception;

class SetupAuditTest extends ItopCustomDatamodelTestCase
{
	const ENVT = 'php-unit-extensionremoval-tests';

	protected function setUp(): void
	{
		static::LoadRequiredItopFiles();
		$this->oEnvironment = new UnitTestRunTimeEnvironment(self::ENVT);
		$this->oEnvironment->bUseDelta = false;
		$this->oEnvironment->bUseAdditionalFeatures = true;
		parent::setUp();

		$this->RequireOnceItopFile('/setup/feature_removal/SetupAudit.php');
	}

	public function GetTestEnvironment(): string
	{
		return self::ENVT;
	}

	public function testGetModelFromEnvironment()
	{
		$oSetupAudit = new SetupAudit([]);

		$aExpected = \MetaModel::GetClasses();
		sort($aExpected);

		$aModel = $oSetupAudit->GetModelFromEnvironment($this->GetTestEnvironment());
		sort($aModel);
		$this->assertEquals($aExpected, $aModel);
	}

	public function testGetModelFromEnvironmentFailure()
	{
		$oSetupAudit = new SetupAudit([]);

		$aExpected = \MetaModel::GetClasses();
		sort($aExpected);

		$this->expectException(\CoreException::class);
		$this->expectExceptionMessage("Cannot get classes");
		$aModel = $oSetupAudit->GetModelFromEnvironment('gabuzomeu');
		sort($aModel);
		$this->assertEquals($aExpected, $aModel);
	}

	public function GetDatamodelDeltaAbsPath(): string
	{
		//no delta: empty path provided
		return "";
	}

	public function GetAdditionalFeaturePaths(): array
	{
		$aFeaturePaths = [];
		foreach (glob(__DIR__."/additional_features/*", GLOB_ONLYDIR) as $aFeaturePath) {
			$sCode = basename($aFeaturePath);
			$aFeaturePaths[$sCode] = $aFeaturePath;
		}

		return $aFeaturePaths;
	}

	public function testComputeDryRemoval()
	{
		$oSetupAudit = new SetupAudit();
		$oSetupAudit->SetClassesBeforeRemovalFromCurrentEnv();
		$oSetupAudit->ComputeDryExtensionRemoval(['nominal_ext1', 'finalclass_ext2']);
		$aRemovedClasses = $oSetupAudit->GetRemovedClasses();
		sort($aRemovedClasses);
		$expected = [
			"Feature1Module1MyClass",
			"FinalClassFeature2Module1MyClass",
			"FinalClassFeature2Module1MyFinalClassFromLocation",
		];

		sort($expected);
		$this->assertEquals($expected, $aRemovedClasses);
	}

	public function testComputeMTPWay()
	{
		$oSetupAudit = new SetupAudit();
		$oSetupAudit->ComputeClassesBeforeRemoval('production');
		$oSetupAudit->SetClassesAfterRemovalFromCurrentEnv();
		$oSetupAudit->AuditExtensionsCleanupRules(true);

		$oSetupAudit->SetClassesBeforeRemovalFromCurrentEnv();
		$oSetupAudit->ComputeDryExtensionRemoval(['nominal_ext1', 'finalclass_ext2']);
		$aRemovedClasses = $oSetupAudit->GetRemovedClasses();
		sort($aRemovedClasses);
		$expected = [
			"Feature1Module1MyClass",
			"FinalClassFeature2Module1MyClass",
			"FinalClassFeature2Module1MyFinalClassFromLocation",
		];

		sort($expected);
		$this->assertEquals($expected, $aRemovedClasses);
	}

	public function testAuditExtensionsCleanupRules()
	{
		$sUID = "AuditExtensionsCleanupRules_".uniqid();
		$oOrg = $this->CreateOrganization($sUID);
		$this->createObject('FinalClassFeature1Module1MyFinalClassFromLocation', ['org_id' => $oOrg->GetKey(), 'name' => $sUID, 'name2' => uniqid()]);

		$oSetupAudit = new SetupAudit();
		$aRemovedClasses = [
			"Feature1Module1MyClass",
			"FinalClassFeature1Module1MyClass",
			"FinalClassFeature1Module1MyFinalClassFromLocation",
			"FinalClassFeature2Module1MyClass",
			"FinalClassFeature2Module1MyFinalClassFromLocation",
		];

		//avoid setup dry computation
		$this->SetNonPublicProperty($oSetupAudit, 'aRemovedClasses', $aRemovedClasses);

		$oRules = $oSetupAudit->AuditExtensionsCleanupRules();
		asort($oRules);

		$expected = [
			"FinalClassFeature1Module1MyFinalClassFromLocation" => 1,
			"FinalClassFeature2Module1MyFinalClassFromLocation" => 0,
		];

		asort($expected);
		$this->assertEquals($expected, $oRules);
	}

	public function testAuditExtensionsCleanupRulesFailASAP()
	{
		$sUID = "AuditExtensionsCleanupRules_".uniqid();
		$oOrg = $this->CreateOrganization($sUID);
		$this->createObject('FinalClassFeature1Module1MyFinalClassFromLocation', ['org_id' => $oOrg->GetKey(), 'name' => $sUID, 'name2' => uniqid()]);
		$this->createObject('FinalClassFeature2Module1MyFinalClassFromLocation', ['org_id' => $oOrg->GetKey(), 'name' => $sUID, 'name2' => uniqid()]);

		$oSetupAudit = new SetupAudit(['nominal_ext1', 'finalclass_ext1', 'finalclass_ext2']);
		$aRemovedClasses = [
			"Feature1Module1MyClass",
			"FinalClassFeature1Module1MyClass",
			"FinalClassFeature1Module1MyFinalClassFromLocation",
			"FinalClassFeature2Module1MyClass",
			"FinalClassFeature2Module1MyFinalClassFromLocation",
		];

		//avoid setup dry computation
		$this->SetNonPublicProperty($oSetupAudit, 'aRemovedClasses', $aRemovedClasses);

		$this->expectException(Exception::class);
		$this->expectExceptionMessage('FinalClassFeature1Module1MyFinalClassFromLocation');
		$oSetupAudit->AuditExtensionsCleanupRules(true);
	}
}
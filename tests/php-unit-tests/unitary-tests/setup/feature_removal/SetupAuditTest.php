<?php

namespace Combodo\iTop\Test\UnitTest\Setup\FeatureRemoval;

use Combodo\iTop\DataFeatureRemoval\Service\DataFeatureRemoverExtensionService;
use Combodo\iTop\Setup\FeatureRemoval\ModelReflectionSerializer;
use Combodo\iTop\Setup\FeatureRemoval\SetupAudit;
use Combodo\iTop\Test\UnitTest\ItopCustomDatamodelTestCase;
use Combodo\iTop\Test\UnitTest\Service\UnitTestRunTimeEnvironment;
use Config;
use MetaModel;
use RunTimeEnvironment;
use SetupUtils;
use utils;

class SetupAuditTest extends ItopCustomDatamodelTestCase
{
	public const ENVT = 'php-unit-extensionremoval-tests';

	public function GetDatamodelDeltaAbsPath(): string
	{
		//no delta: empty path provided
		return "";
	}

	public function GetAdditionalExtensionsPaths(): array
	{
		$aFeaturePaths = [];
		foreach (glob(__DIR__."/additional_features/*", GLOB_ONLYDIR) as $aFeaturePath) {
			$sCode = basename($aFeaturePath);
			$aFeaturePaths[$sCode] = $aFeaturePath;
		}

		return $aFeaturePaths;
	}

	protected function tearDown(): void
	{
		parent::tearDown();
	}

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

	public function testRemovedExtensionsAreListedInSetupAudit()
	{
		$sAdditionalExtensionDir = APPROOT.'/extensions/finalclass_ext3';
		SetupUtils::copydir(__DIR__.'/other_features/finalclass_ext3', $sAdditionalExtensionDir);
		$this->aFileToClean[] = $sAdditionalExtensionDir;

		clearstatcache();

		$oRuntimeEnvironment = new RunTimeEnvironment($this->GetTestEnvironment(), false);
		$oRuntimeEnvironment->CopySetupFiles();
		$oConfig = new Config(utils::GetConfigFilePath($this->GetTestEnvironment()));
		$aRemovedExtensions = ['nominal_ext1', 'finalclass_ext2'];
		$aSelectedExtensions = DataFeatureRemoverExtensionService::GetInstance()->GetExtensionMap()->GetSelectedExtensions($oConfig, ['finalclass_ext1', 'finalclass_ext3'], $aRemovedExtensions);
		$aSelectedModules = $oRuntimeEnvironment->GetModulesToLoadFromChoices($oConfig, $aSelectedExtensions);

		$oRuntimeEnvironment->DoCompile($aSelectedExtensions, $aRemovedExtensions, $aSelectedModules);

		$oSetupAudit = new SetupAudit($this->GetTestEnvironment());

		$expected = [
			"Feature1Module1MyClass",
			"FinalClassFeature2Module1MyClass",
			"FinalClassFeature2Module1MyFinalClassFromLocation",
		];
		$this->assertEqualsCanonicalizing($expected, $oSetupAudit->GetRemovedClasses());

		$expected = [
			"FinalClassFeature2Module1MyFinalClassFromLocation" => 0,
		];
		$this->assertEqualsCanonicalizing($expected, $oSetupAudit->RunDataAudit());

		$aClassesAfter = ModelReflectionSerializer::GetInstance()->GetModelFromEnvironment($oSetupAudit->GetEnvAfter());
		$expected = [
			"FinalClassFeature3Module1MyClass",
			"FinalClassFeature3Module1MyFinalClassFromLocation",
		];
		foreach ($expected as $sAddedClass) {
			$this->assertContains(
				$sAddedClass,
				$aClassesAfter,
				"After DryRemoval compilation DM should contain classes coming from finalclass_ext3 extension"
			);
		}
	}

	public function testGetIssues()
	{
		$sUID = "AuditExtensionsCleanupRules_".uniqid();
		$oOrg = $this->CreateOrganization($sUID);
		$this->createObject('FinalClassFeature1Module1MyFinalClassFromLocation', ['org_id' => $oOrg->GetKey(), 'name' => $sUID, 'name2' => uniqid()]);

		$oSetupAudit = new SetupAudit($this->GetTestEnvironment());
		$aRemovedClasses = [
			"Feature1Module1MyClass",
			"FinalClassFeature1Module1MyClass",
			"FinalClassFeature1Module1MyFinalClassFromLocation",
			"FinalClassFeature2Module1MyClass",
			"FinalClassFeature2Module1MyFinalClassFromLocation",
		];

		//avoid setup dry computation
		$this->SetNonPublicProperty($oSetupAudit, 'aRemovedClasses', $aRemovedClasses);

		$expected = [
			"FinalClassFeature1Module1MyFinalClassFromLocation" => 1,
			"FinalClassFeature2Module1MyFinalClassFromLocation" => 0,
		];
		$this->assertEqualsCanonicalizing($expected, $oSetupAudit->RunDataAudit());
	}

	public function testAuditExtensionsCleanupRulesFailASAP()
	{
		$sUID = "AuditExtensionsCleanupRules_".uniqid();
		$oOrg = $this->CreateOrganization($sUID);
		$this->createObject('FinalClassFeature1Module1MyFinalClassFromLocation', ['org_id' => $oOrg->GetKey(), 'name' => $sUID, 'name2' => uniqid()]);
		$this->createObject('FinalClassFeature2Module1MyFinalClassFromLocation', ['org_id' => $oOrg->GetKey(), 'name' => $sUID, 'name2' => uniqid()]);

		$oSetupAudit = new SetupAudit($this->GetTestEnvironment());
		$aRemovedClasses = [
			"Feature1Module1MyClass",
			"FinalClassFeature1Module1MyClass",
			"FinalClassFeature1Module1MyFinalClassFromLocation",
			"FinalClassFeature2Module1MyClass",
			"FinalClassFeature2Module1MyFinalClassFromLocation",
		];

		//avoid setup dry computation
		$this->SetNonPublicProperty($oSetupAudit, 'aRemovedClasses', $aRemovedClasses);

		$expected = [
			"FinalClassFeature1Module1MyFinalClassFromLocation" => 1,
		];
		$this->assertEqualsCanonicalizing($expected, $oSetupAudit->RunDataAudit(true));
	}
}

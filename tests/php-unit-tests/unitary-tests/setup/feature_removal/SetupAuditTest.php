<?php

namespace Combodo\iTop\Test\UnitTest\Setup\FeatureRemoval;

use Combodo\iTop\Setup\FeatureRemoval\DryRemovalRuntimeEnvironment;
use Combodo\iTop\Setup\FeatureRemoval\InplaceSetupAudit;
use Combodo\iTop\Setup\FeatureRemoval\ModelReflectionSerializer;
use Combodo\iTop\Setup\FeatureRemoval\SetupAudit;
use Combodo\iTop\Test\UnitTest\ItopCustomDatamodelTestCase;
use Combodo\iTop\Test\UnitTest\Service\UnitTestRunTimeEnvironment;
use MetaModel;
use SetupUtils;

class SetupAuditTest extends ItopCustomDatamodelTestCase
{
	public const ENVT = 'php-unit-extensionremoval-tests';

	private ?string $sDirPathToRemoveFromExtensionFolder = null;

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

	protected function setUp(): void
	{
		static::LoadRequiredItopFiles();
		$this->oEnvironment = new UnitTestRunTimeEnvironment(self::ENVT);
		$this->oEnvironment->bUseDelta = false;
		$this->oEnvironment->bUseAdditionalFeatures = true;
		parent::setUp();

		$this->RequireOnceItopFile('/setup/feature_removal/SetupAudit.php');
		$this->RequireOnceItopFile('/setup/feature_removal/InplaceSetupAudit.php');
		$this->RequireOnceItopFile('/setup/feature_removal/DryRemovalRuntimeEnvironment.php');
	}

	protected function tearDown(): void
	{
		parent::tearDown();
		if (!is_null($this->sDirPathToRemoveFromExtensionFolder) && is_dir($this->sDirPathToRemoveFromExtensionFolder)) {
			SetupUtils::rrmdir($this->sDirPathToRemoveFromExtensionFolder);
		}
	}

	public function GetTestEnvironment(): string
	{
		return self::ENVT;
	}

	public function testComputeDryRemoval()
	{
		$this->sDirPathToRemoveFromExtensionFolder = APPROOT.'/extensions/finalclass_ext3';
		SetupUtils::copydir(__DIR__.'/other_features/finalclass_ext3', $this->sDirPathToRemoveFromExtensionFolder);
		clearstatcache();

		$oDryRemovalRuntimeEnvt = new DryRemovalRuntimeEnvironment($this->GetTestEnvironment(), ['finalclass_ext3'], ['nominal_ext1', 'finalclass_ext2']);
		$oDryRemovalRuntimeEnvt->CompileFrom($this->GetTestEnvironment());

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
			$this->assertContains($sAddedClass, $aClassesAfter, "After DryRemoval compilation DM should contain classes coming from finalclass_ext3 extension");
		}
	}

	public function testGetRemovedClassesFromSetupWizard()
	{
		$sEnv = MetaModel::GetEnvironment();

		$aClassesBeforeRemoval = ModelReflectionSerializer::GetInstance()->GetModelFromEnvironment($sEnv);
		$aClassesBeforeRemoval[] = "GabuZomeu";

		$oSetupAudit = new InplaceSetupAudit($aClassesBeforeRemoval, $sEnv);
		$oSetupAudit->ComputeClasses();
		$this->assertEquals(["GabuZomeu"], $oSetupAudit->GetRemovedClasses());
	}

	public function testGetIssues()
	{
		$sUID = "AuditExtensionsCleanupRules_".uniqid();
		$oOrg = $this->CreateOrganization($sUID);
		$this->createObject('FinalClassFeature1Module1MyFinalClassFromLocation', ['org_id' => $oOrg->GetKey(), 'name' => $sUID, 'name2' => uniqid()]);

		$oSetupAudit = new SetupAudit(MetaModel::GetEnvironment());
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

		$oSetupAudit = new SetupAudit(MetaModel::GetEnvironment());
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

<?php

namespace Combodo\iTop\Test\UnitTest\Setup\FeatureRemoval;

use Combodo\iTop\Setup\FeatureRemoval\ModelReflectionSerializer;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use MetaModel;

class ModelSerializationTest extends ItopDataTestCase
{
	protected function setUp(): void
	{
		parent::setUp();
		$this->RequireOnceItopFile('/setup/feature_removal/ModelReflectionSerializer.php');
	}

	public function testGetModelFromEnvironment()
	{
		$aModel = ModelReflectionSerializer::GetInstance()->GetModelFromEnvironment($this->GetTestEnvironment());
		$this->assertEqualsCanonicalizing(MetaModel::GetClasses(), $aModel);
	}

	public function testGetModelFromEnvironmentFailure()
	{
		$this->expectException(\CoreException::class);
		$this->expectExceptionMessage("Cannot get classes");
		ModelReflectionSerializer::GetInstance()->GetModelFromEnvironment('gabuzomeu');
	}
}
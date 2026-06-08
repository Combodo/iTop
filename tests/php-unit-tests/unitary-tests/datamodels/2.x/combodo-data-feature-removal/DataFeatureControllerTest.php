<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Module\DataFeatureRemoval\Service;

use Combodo\iTop\DataFeatureRemoval\Controller\DataFeatureRemovalController;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;

/**
 * @see DataFeatureController
 */
class DataFeatureControllerTest extends ItopDataTestCase
{
	protected function setUp(): void
	{
		parent::setUp();
		$this->RequireOnceItopFile('env-production/combodo-data-feature-removal/vendor/autoload.php');
	}

	public function testConvertIntoSetupFormat()
	{
		$oController = new DataFeatureRemovalController();

		$aExtensions = [
			'itop-container-mgmt',
			'combodo-monitoring',
		];
		$expected = '{"0":"itop-container-mgmt","1":"combodo-monitoring"}';
		$this->assertEquals($expected, $this->InvokeNonPublicMethod(DataFeatureRemovalController::class, 'ConvertIntoSetupFormat', $oController, [ $aExtensions]));
	}
}

<?php

namespace Combodo\iTop\Test\UnitTest\Application\TwigBase;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use Combodo\iTop\Portal\Twig\AppExtension;
use Twig_Environment;
use Twig_Loader_Array;

class TwigTest extends ItopDataTestCase
{
	protected function setUp(): void
	{
		parent::setUp();
		$this->RequireOnceItopFile('core/config.class.inc.php');
	}

	/**
	 * Test the fix for ticket N°4384
	 *
	 * @dataProvider TemplateProvider
	 *
	 */
	public function testTemplate($sFileName, $sExpected)
	{
		$this->expectNotToPerformAssertions();
		$sId = 'TestTwig';
		$oAppExtension = new AppExtension();

		// Creating sandbox twig env. to load and test the custom form template
		$oTwig = new Twig_Environment(new Twig_Loader_Array([$sId => $sFileName]));

		// Manually registering filters and functions as we didn't find how to do it automatically
		$aFilters = $oAppExtension->getFilters();
		foreach ($aFilters as $oFilter)
		{
			$oTwig->addFilter($oFilter);
		}
		$aFunctions = $oAppExtension->getFunctions();
		foreach ($aFunctions as $oFunction)
		{
			$oTwig->addFunction($oFunction);
		}
	}

	public static function TemplateProvider()
	{
		$aReturn = array();
		$aReturn['filter_system'] = [
				'sFileName' => 'test.html',
				'expected' =>file_get_contents(dirname(__FILE__).'/test.html'),
			];

		return $aReturn;
	}
}
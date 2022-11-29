<?php
namespace Combodo\iTop\Test\UnitTest;

use Combodo\iTop\Portal\Twig\AppExtension;
use Twig\Environment;
use Twig\Loader\ArrayLoader;
use Twig_Environment;
use Twig_Loader_Array;

class TwigTest extends ItopDataTestCase
{
	protected function setUp(): void
	{
		parent::setUp();
		require_once __DIR__.'/../../core/config.class.inc.php';
	}

	/**
	 * Test the fix for ticket N°4384
	 *
	 * @dataProvider TemplateProvider
	 *
	 */
	public function testTemplate($sFileName, $sExpected)
	{
		$sId = 'TestTwig';
		$oAppExtension = new AppExtension();

		// Creating sandbox twig env. to load and test the custom form template
		$oTwig = new Environment(new ArrayLoader([$sId => $sFileName]));

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

		$sHtml = $oTwig->render($sId, ['AttackerURL' => 'file://'.__DIR__.'/attacker']);

		$this->assertEquals($sExpected, $sHtml);
	}

	public static function TemplateProvider()
	{
		$aReturn = array();
		$aReturn['filter_system'] = [
				'sFileName' => file_get_contents(__DIR__.'/test.html.twig'),
				'expected' => file_get_contents(__DIR__.'/test.html'),
			];

		return $aReturn;
	}
}
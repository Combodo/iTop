<?php

namespace Combodo\iTop\Test\UnitTest\Application\TwigBase;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use Combodo\iTop\Portal\Twig\AppExtension;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class TwigTest extends ItopDataTestCase
{
	/**
	 * @var \Twig\Environment
	 */
	private Environment $oTwig;

	/** @inheritdoc  */
	protected function setUp(): void
	{
		parent::setUp();
		$this->RequireOnceItopFile('core/config.class.inc.php');

		// Creating sandbox twig env. to load and test the custom form template
		$this->oTwig = new Environment(new FilesystemLoader(__DIR__.'/data/'));

		// Manually registering filters and functions as we didn't find how to do it automatically
		$oAppExtension = new AppExtension();
		$aFilters = $oAppExtension->getFilters();
		foreach ($aFilters as $oFilter)
		{
			$this->oTwig->addFilter($oFilter);
		}
		$aFunctions = $oAppExtension->getFunctions();
		foreach ($aFunctions as $oFunction)
		{
			$this->oTwig->addFunction($oFunction);
		}
	}

	/**
	 * Test the fix for ticket
	 * N°7810 - [SECU] Portal code injection - Code Execution possible on iTop server
	 *
	 * Twig filters have been deactivated to ensure no injection is possible in portal.
	 * This test ensures that the filters are not available in the portal.
	 *
	 * @dataProvider FiltersSecurityTemplateProvider
	 *
	 * @throws \Twig\Error\LoaderError
	 * @throws \Twig\Error\RuntimeError
	 * @throws \Twig\Error\SyntaxError
	 */
	public function testFiltersSecurity(string $sName)
	{
		// render twig template
		$sOutput = $this->oTwig->render($sName . '.html.twig');

		// get expected result
		$sExpected = file_get_contents(__DIR__.'/data/' . $sName . '.html');

		// assert equals
		$this->assertEquals($sExpected, $sOutput, $sName . ' filter is not working as expected');
	}

	/**
	 * FiltersSecurityTemplateProvider.
	 *
	 * @return array[]
	 */
	public static function FiltersSecurityTemplateProvider()
	{
		return [
			'filter' => [
				'name' => 'filter'
			],
			'map' => [
				'name' => 'map'
			],
			'reduce' => [
				'name' => 'reduce'
			],
			'sort' => [
				'name' => 'sort'
			],
		];
	}
}
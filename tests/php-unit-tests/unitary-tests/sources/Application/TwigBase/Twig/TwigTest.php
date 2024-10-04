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
		$this->oTwig = new Environment(new FilesystemLoader(__DIR__));

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
	private function RenderTwig(string $sTwigContent): string
	{
		return $this->oTwig->createTemplate($sTwigContent)->render([]);
	}

	public function test_reduce_FilterShouldBeDiscarded()
	{
		$this->assertEquals('[1,2,3]', $this->RenderTwig("{{ [1, 2, 3]|reduce('system') }}"));
		$this->assertEquals('[1,2,3]', $this->RenderTwig("{{ [1, 2, 3]|reduce('anyCallBack') }}"));
		$this->assertEquals('[1,2,3]', $this->RenderTwig("{{ [1, 2, 3]|reduce((carry, val, key) => carry + val) }}"));
	}

	public function test_sort_FilterShouldBeDiscarded()
	{
		$this->assertEquals('[3,1,2]', $this->RenderTwig("{{ [3, 1, 2]|sort('system') }}"));
		$this->assertEquals('[3,1,2]', $this->RenderTwig("{{ [3, 1, 2]|sort('anyCallBack') }}"));
		$this->assertEquals('[3,1,2]', $this->RenderTwig("{{ [3, 1, 2]|sort((a, b) => a > b) }}"));

		$this->ExpectExceptionMessage('Too few arguments to function Combodo\iTop\Application\TwigBase\Twig\Extension::Combodo\iTop\Application\TwigBase\Twig\{closure}()');
		$this->RenderTwig("{{ [3, 1, 2]|sort|join(', ') }}");
	}

	public function test_map_FilterShouldBeDiscarded()
	{
		$this->assertEquals('[1,2,3]', $this->RenderTwig("{{ [1, 2, 3]|map('system') }}"));
		$this->assertEquals('[1,2,3]', $this->RenderTwig("{{ [1, 2, 3]|map('anyCallBack') }}"));
		$this->assertEquals('[1,2,3]', $this->RenderTwig("{{ [1, 2, 3]|map(p => p + 10) }}"));
	}

	public function test_filter_FilterShouldNotAllowTheSystemFunction()
	{
		$this->assertEquals('["ls"]', $this->RenderTwig("{{ ['ls']|filter('system')|raw }}"), 'system() should not be allowed as callback for filter');

		$this->assertEquals('Iterator', $this->RenderTwig("{{ ['Iterator', 'Zabugomeu']|filter('interface_exists')|join(', ') }}"), 'Other functions should be allowed as callback for filter');
		$this->assertEquals('4, 5', $this->RenderTwig("{{ [1, 2, 3, 4, 5]|filter(v => v > 3)|join(', ') }}"), 'Arrow functions should be allowed as callback');
	}
}
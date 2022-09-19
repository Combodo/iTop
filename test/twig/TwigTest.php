<?php
namespace Combodo\iTop\Test\UnitTest;

use AppBundle\Twig\AppExtension;
use Combodo\iTop\Application\TwigBase\Twig\TwigHelper;
use Combodo\iTop\Test\UnitTest\ItopTestCase;

class TwigTest extends ItopTestCase
{

	/**
	 * Test the fix for ticket N°4384
	 *
	 * @dataProvider testTemplateProvider
	 *
	 */
	public function testTemplate($sFileName, $expected)
	{
		$oTwig = TwigHelper::GetTwigEnvironment( dirname(__FILE__));

		$sHtml = $oTwig->render($sFileName.'.twig');
	    $this->assertSame($sHtml, $expected);
	}

	public static function testTemplateProvider()
	{
		$aReturn = array();
		$aReturn['filter_system'] = [
				'sFileName' => 'test.html',
				'expected' =>file_get_contents(dirname(__FILE__).'/test.html'),
			];

		return $aReturn;
	}
}
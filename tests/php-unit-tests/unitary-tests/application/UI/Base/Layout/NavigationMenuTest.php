<?php

namespace UI\Base\Layout;

use ApplicationContext;
use Combodo\iTop\Application\UI\Base\Component\PopoverMenu\PopoverMenu;
use Combodo\iTop\Application\UI\Base\Layout\NavigationMenu\NavigationMenu;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;

/**
 *
 * Class NavigationMenuTest
 *
 * @package UI\Base\Layout
 */
class NavigationMenuTest extends ItopDataTestCase {
	public function IsAllowedProvider(){
		return [
			'show menu' => [ true ],
			'hide menu' => [ false ],
		];
	}

	/**
	 * @dataProvider IsAllowedProvider
	 * test used to make sure backward compatibility is ensured
	 */
	public function testIsAllowed($bExpectedIsAllowed=true){
		\MetaModel::GetConfig()->Set('navigation_menu.show_organization_filter', $bExpectedIsAllowed);
		$oNavigationMenu = new NavigationMenu(
			$this->createMock(ApplicationContext::class),
			$this->createMock(PopoverMenu::class));

		$isAllowed = $oNavigationMenu->IsSiloSelectionEnabled();
		$this->assertEquals($bExpectedIsAllowed, $isAllowed);
	}

	public function testIsAllowed_BackwardCompatibility_NoVariableInConfFile(){
		\MetaModel::GetConfig()->Set('navigation_menu.show_organization_filter', false);

		$sTmpFilePath = tempnam(sys_get_temp_dir(), 'test_');
		$oInitConfig = \MetaModel::GetConfig();
		$oInitConfig->WriteToFile($sTmpFilePath);

		//remove variable for the test
		$aLines = file($sTmpFilePath);

		$aRows = array();

		foreach ($aLines as $key => $sLine) {
			if (!preg_match('/navigation_menu.show_organization_filter/', $sLine)) {
				$aRows[] = $sLine;
			}
		}

		file_put_contents($sTmpFilePath, implode("\n", $aRows));
		$oTempConfig = new \Config($sTmpFilePath);

		$isAllowed = $oTempConfig->Get('navigation_menu.show_organization_filter');

		$this->assertEquals(true, $isAllowed);
		unlink($sTmpFilePath);
	}
}

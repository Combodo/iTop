<?php

namespace UI\Base\Layout;

use ApplicationContext;
use Combodo\iTop\Application\UI\Base\Component\PopoverMenu\PopoverMenu;
use Combodo\iTop\Application\UI\Base\Layout\NavigationMenu\NavigationMenu;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;

class NavigationMenuTest extends ItopDataTestCase {
	private $oConfigToRestore;

	public function setUp(): void
	{
		parent::setUp();
		require_once(APPROOT.'application/themehandler.class.inc.php');
		$this->oConfigToRestore = null;
	}

	public function tearDown(): void
	{
		if (! is_null($this->oConfigToRestore)){
			$oReflexionClass = new \ReflectionClass(\MetaModel::class);
			$oReflexionClass->setStaticPropertyValue('m_oConfig', $this->oConfigToRestore);
		}
	}

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

		$isAllowed = $this->InvokeNonPublicMethod(NavigationMenu::class, "IsOrgMenuFilterAllowed", $oNavigationMenu, []);
		$this->assertEquals($bExpectedIsAllowed, $isAllowed);
	}

	public function testIsAllowedWithNoConfVariable(){
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

		$this->oConfigToRestore = $oInitConfig;
		$oReflexionClass = new \ReflectionClass(\MetaModel::class);
		$oReflexionClass->setStaticPropertyValue('m_oConfig', $oTempConfig);

		$oNavigationMenu = new NavigationMenu(
			$this->createMock(ApplicationContext::class),
			$this->createMock(PopoverMenu::class)
		);

		$isAllowed = $this->InvokeNonPublicMethod(NavigationMenu::class, "IsOrgMenuFilterAllowed", $oNavigationMenu, []);
		$this->assertEquals(true, $isAllowed);
	}
}

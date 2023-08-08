<?php

namespace UI\Base\Component\PopoverMenu;

use Combodo\iTop\Application\UI\Base\Component\PopoverMenu\PopoverMenuFactory;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;

/**
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class PopoverMenuFactoryTest extends ItopDataTestCase {

	public function MakeUserMenuForNavigationMenuProvider(){
		$aNotSortedMenuUIDs = [
			'portal_itop_portal',
			'UI_Preferences',
			'UI_Help',
			'UI_AboutBox'
		];

		return [
			'no conf' => [
				'aConf' => null,
				'aExpectedMenuUIDs' => $aNotSortedMenuUIDs
			],
			'not an array conf' => [
				'aConf' => "wrong conf",
				'aExpectedMenuUIDs' => $aNotSortedMenuUIDs
			],
			'default conf' => [
				'aConf' => [],
				'aExpectedMenuUIDs' => $aNotSortedMenuUIDs
			],
			'same order in conf' => [
				'aConf' => [
					'portal:itop-portal',
					'UI:Preferences',
					'UI:Help',
					'UI:AboutBox',
				],
				'aExpectedMenuUIDs' => $aNotSortedMenuUIDs
			],
			'first menus sorted and last one missing in conf' => [
				'aConf' => [
					"portal:itop-portal",
					"UI:Preferences",
				],
				'aExpectedMenuUIDs' => $aNotSortedMenuUIDs
			],
			'some menus but not all sorted' => [
				'aConf' => [
					'UI:Preferences',
					'UI:AboutBox',
				],
				'aExpectedMenuUIDs' => [
					'UI_Preferences',
					'UI_AboutBox',
					'portal_itop_portal',
					'UI_Help',
				]
			],
			'all user menu sorted' => [
				'aConf' => [
					'UI:Preferences',
					'UI:AboutBox',
					'portal:itop-portal',
					'UI:Help',
				],
				'aExpectedMenuUIDs' => [
					'UI_Preferences',
					'UI_AboutBox',
					'portal_itop_portal',
					'UI_Help',
				]
			 ],
		];
	}
	/**
	 * @dataProvider MakeUserMenuForNavigationMenuProvider
	 */
	public function testMakeUserMenuForNavigationMenu($aConf, $aExpectedMenuUIDs){
		if (! is_null($aConf)){
			\MetaModel::GetConfig()->Set('navigation_menu.sorted_popup_user_menu_items', $aConf);
		}

		$aRes = PopoverMenuFactory::MakeUserMenuForNavigationMenu()->GetSections();
		$this->assertTrue(array_key_exists('misc', $aRes));
		$aUIDsWithDummyRandoString = array_keys($aRes['misc']['aItems']);
		//replace ibo-popover-menu--item-6464cdca5ecf4214716943--UI_AboutBox by UI_AboutBox (for ex)
		$aUIDs = preg_replace('/ibo-popover-menu--item-([^\-]+)--/', '', $aUIDsWithDummyRandoString);
		$this->assertEquals($aExpectedMenuUIDs, $aUIDs);
	}
}

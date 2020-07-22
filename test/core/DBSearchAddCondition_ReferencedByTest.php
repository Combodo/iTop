<?php

namespace Combodo\iTop\Test\UnitTest\Core;

use CMDBSource;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use DBSearch;

/**
 * Class DBSearchIntersectTest
 *
 * @package Combodo\iTop\Test\UnitTest\Core
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class DBSearchAddCondition_ReferencedByTest extends ItopDataTestCase {

	protected function setUp()
	{
		parent::setUp();
		require_once(APPROOT.'application/startup.inc.php');
	}

	/**
	 * @dataProvider AddCondition_ReferencedByProvider
	 *
	 * @param $sLeftSelect
	 * @param $sRightSelect
	 * @param $sParentAtt
	 * @param $sResult
	 *
	 * @throws \CoreException
	 * @throws \CoreWarning
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \OQLException
	 */
	public function testAddCondition_ReferencedBy($sLeftSelect, $sRightSelect, $sParentAtt, $sResult)
	{
		$oLeftSearch = DBSearch::FromOQL($sLeftSelect);
		$oRightSearch = DBSearch::FromOQL($sRightSelect);

		$oResultSearch = $oLeftSearch->Join($oRightSearch,
			DBSearch::JOIN_REFERENCED_BY, $sParentAtt,
			TREE_OPERATOR_EQUALS, $aRealiasingMap);

		CMDBSource::TestQuery($oResultSearch->MakeSelectQuery());
		$this->assertEquals($sResult, $oResultSearch->ToOQL());
	}

	public function AddCondition_ReferencedByProvider()
	{
		// Breakpoint in BrowseBrickController::DisplayAction()
		// $aLevelsProperties[$aLevelsPropertiesKeys[$i]]['search'] = $aLevelsProperties[$aLevelsPropertiesKeys[$i]]['search']->Join($aLevelsProperties[$aLevelsPropertiesKeys[$i + 1]]['search'],
		//							DBSearch::JOIN_REFERENCED_BY, $aLevelsProperties[$aLevelsPropertiesKeys[$i + 1]]['parent_att'],
		//							TREE_OPERATOR_EQUALS, $aRealiasingMap);
		return [
			'Bug 3176' => [
				'left' => "SELECT `L-1-1` FROM Service AS `L-1-1` JOIN lnkCustomerContractToService AS `l1` ON `l1`.service_id = `L-1-1`.id JOIN CustomerContract AS `cc` ON `l1`.customercontract_id = `cc`.id WHERE ((`cc`.`org_id` = 2) AND (`L-1-1`.`status` != 'obsolete'))UNION SELECT `L-1-1` FROM Service AS `L-1-1` JOIN lnkCustomerContractToService AS `l11` ON `l11`.service_id = `L-1-1`.id JOIN CustomerContract AS `cc1` ON `l11`.customercontract_id = `cc1`.id JOIN Organization AS `child` ON `cc1`.org_id = `child`.id JOIN Organization AS `root` ON `child`.parent_id BELOW `root`.id WHERE ((`root`.`id` = 2) AND (`L-1-1`.`status` != 'obsolete'))",
				'right' => "SELECT `L-1-1-1` FROM ServiceSubcategory AS `L-1-1-1` WHERE (`L-1-1-1`.`status` != 'obsolete')",
				'parent_att' => 'service_id',
				'result' => "SELECT `L-1-1` FROM Service AS `L-1-1` JOIN lnkCustomerContractToService AS `l1` ON `l1`.service_id = `L-1-1`.id JOIN CustomerContract AS `cc` ON `l1`.customercontract_id = `cc`.id JOIN ServiceSubcategory AS `L-1-1-1` ON `L-1-1-1`.service_id = `L-1-1`.id WHERE (((`cc`.`org_id` = 2) AND (`L-1-1`.`status` != 'obsolete')) AND (`L-1-1-1`.`status` != 'obsolete')) UNION SELECT `L-1-1` FROM Service AS `L-1-1` JOIN lnkCustomerContractToService AS `l11` ON `l11`.service_id = `L-1-1`.id JOIN CustomerContract AS `cc1` ON `l11`.customercontract_id = `cc1`.id JOIN Organization AS `child` ON `cc1`.org_id = `child`.id JOIN Organization AS `root` ON `child`.parent_id BELOW `root`.id JOIN ServiceSubcategory AS `L-1-1-1` ON `L-1-1-1`.service_id = `L-1-1`.id WHERE (((`root`.`id` = 2) AND (`L-1-1`.`status` != 'obsolete')) AND (`L-1-1-1`.`status` != 'obsolete'))",
			],
			'Bug 2970' => [
				'left' => "SELECT `L-1-1` FROM Service AS `L-1-1` JOIN lnkCustomerContractToService AS `l1` ON `l1`.service_id = `L-1-1`.id JOIN CustomerContract AS `cc` ON `l1`.customercontract_id = `cc`.id WHERE ((`cc`.`org_id` = 2) AND (`L-1-1`.`status` != 'obsolete')) UNION SELECT `L-1-1` FROM Service AS `L-1-1` WHERE (`L-1-1`.`id` = 8)",
				'right' => "SELECT `L-1-1-1` FROM ServiceSubcategory AS `L-1-1-1` JOIN Service AS `s` ON `L-1-1-1`.service_id = `s`.id JOIN lnkCustomerContractToService AS `l1` ON `l1`.service_id = `s`.id JOIN CustomerContract AS `cc` ON `l1`.customercontract_id = `cc`.id WHERE ((`L-1-1-1`.`status` != 'obsolete') AND ((`cc`.`org_id` = 2) AND (`L-1-1-1`.`status` != 'obsolete'))) UNION SELECT `L-1-1-1` FROM ServiceSubcategory AS `L-1-1-1` JOIN Service AS `s1` ON `L-1-1-1`.service_id = `s1`.id WHERE ((`L-1-1-1`.`status` != 'obsolete') AND (`s1`.`id` = 8))",
				'parent_att' => 'service_id',
				'result' => "SELECT `L-1-1` FROM Service AS `L-1-1` JOIN lnkCustomerContractToService AS `l1` ON `l1`.service_id = `L-1-1`.id JOIN CustomerContract AS `cc` ON `l1`.customercontract_id = `cc`.id JOIN ServiceSubcategory AS `L-1-1-1` ON `L-1-1-1`.service_id = `L-1-1`.id WHERE (((`cc`.`org_id` = 2) AND (`L-1-1`.`status` != 'obsolete')) AND ((`L-1-1-1`.`status` != 'obsolete') AND ((`cc`.`org_id` = 2) AND (`L-1-1-1`.`status` != 'obsolete')))) UNION SELECT `L-1-1` FROM Service AS `L-1-1` JOIN ServiceSubcategory AS `L-1-1-1` ON `L-1-1-1`.service_id = `L-1-1`.id JOIN lnkCustomerContractToService AS `l11` ON `l11`.service_id = `L-1-1`.id JOIN CustomerContract AS `cc1` ON `l11`.customercontract_id = `cc1`.id WHERE ((`L-1-1`.`id` = 8) AND ((`L-1-1-1`.`status` != 'obsolete') AND ((`cc1`.`org_id` = 2) AND (`L-1-1-1`.`status` != 'obsolete')))) UNION SELECT `L-1-1` FROM Service AS `L-1-1` JOIN lnkCustomerContractToService AS `l1` ON `l1`.service_id = `L-1-1`.id JOIN CustomerContract AS `cc` ON `l1`.customercontract_id = `cc`.id JOIN ServiceSubcategory AS `L-1-1-1` ON `L-1-1-1`.service_id = `L-1-1`.id WHERE (((`cc`.`org_id` = 2) AND (`L-1-1`.`status` != 'obsolete')) AND ((`L-1-1-1`.`status` != 'obsolete') AND (`L-1-1`.`id` = 8))) UNION SELECT `L-1-1` FROM Service AS `L-1-1` JOIN ServiceSubcategory AS `L-1-1-1` ON `L-1-1-1`.service_id = `L-1-1`.id WHERE ((`L-1-1`.`id` = 8) AND ((`L-1-1-1`.`status` != 'obsolete') AND (`L-1-1`.`id` = 8)))",
			],
//			'test' => [
//				'left' => "",
//				'right' => "",
//				'parent_att' => '',
//				'result' => "",
//			],
		];
	}
}

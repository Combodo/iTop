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
class DBSearchJoinTest extends ItopDataTestCase {
	
	const USE_TRANSACTION = false;

	protected function setUp()
	{
		parent::setUp();
		require_once(APPROOT.'application/startup.inc.php');
	}

	/**
	 * @dataProvider JoinProvider
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
	public function testJoin($sLeftSelect, $sRightSelect, $sParentAtt, $sResult)
	{
		$oLeftSearch = DBSearch::FromOQL($sLeftSelect);
		$oRightSearch = DBSearch::FromOQL($sRightSelect);

		$aRealiasingMap = [];

		$oResultSearch = $oLeftSearch->Join($oRightSearch,
			DBSearch::JOIN_REFERENCED_BY, $sParentAtt,
			TREE_OPERATOR_EQUALS, $aRealiasingMap);

		$this->debug("\nRealiasing Map");
		$this->debug($aRealiasingMap);

		CMDBSource::TestQuery($oResultSearch->MakeSelectQuery());
		$this->assertEquals($sResult, $oResultSearch->ToOQL());

		// rename alias test
		$this->debug("\nBefore renaming");
		$this->debug($oResultSearch->ToOQL());
		$aLevelsPropertiesKeys = ['L-1', 'L-1-1', 'L-1-1-1'];
		foreach ($aLevelsPropertiesKeys as $sLevelAlias)
		{
			if (array_key_exists($sLevelAlias, $aRealiasingMap))
			{
				foreach ($aRealiasingMap[$sLevelAlias] as $sAliasToRename)
				{
					$oResultSearch->RenameAlias($sAliasToRename, $sLevelAlias);
				}
			}
		}
		$this->debug("\nAfter renaming");
		$this->debug($oResultSearch->ToOQL());

	}

	public function JoinProvider()
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
			'Bug 2585' => [
				'left' => "SELECT `L-1-1` FROM Service AS `L-1-1` JOIN lnkCustomerContractToService AS `l1` ON `l1`.service_id = `L-1-1`.id JOIN CustomerContract AS `cc` ON `l1`.customercontract_id = `cc`.id WHERE ((`cc`.`org_id` = 2) AND (`L-1-1`.`status` != 'obsolete')) UNION SELECT `L-1-1` FROM Service AS `L-1-1` WHERE (`L-1-1`.`id` = 8)",
				'right' => "SELECT `L-1-1-1` FROM ServiceSubcategory AS `L-1-1-1` JOIN Service AS `s` ON `L-1-1-1`.service_id = `s`.id JOIN lnkCustomerContractToService AS `l1` ON `l1`.service_id = `s`.id JOIN CustomerContract AS `cc` ON `l1`.customercontract_id = `cc`.id WHERE ((`L-1-1-1`.`status` != 'obsolete') AND ((`cc`.`org_id` = 2) AND (`L-1-1-1`.`status` != 'obsolete'))) UNION SELECT `L-1-1-1` FROM ServiceSubcategory AS `L-1-1-1` JOIN Service AS `s1` ON `L-1-1-1`.service_id = `s1`.id WHERE ((`L-1-1-1`.`status` != 'obsolete') AND (`s1`.`id` = 8))",
				'parent_att' => 'service_id',
				'result' => "SELECT `L-1-1` FROM Service AS `L-1-1` JOIN lnkCustomerContractToService AS `l1` ON `l1`.service_id = `L-1-1`.id JOIN CustomerContract AS `cc` ON `l1`.customercontract_id = `cc`.id JOIN ServiceSubcategory AS `L-1-1-1` ON `L-1-1-1`.service_id = `L-1-1`.id WHERE (((`cc`.`org_id` = 2) AND (`L-1-1`.`status` != 'obsolete')) AND ((`L-1-1-1`.`status` != 'obsolete') AND ((`cc`.`org_id` = 2) AND (`L-1-1-1`.`status` != 'obsolete')))) UNION SELECT `L-1-1` FROM Service AS `L-1-1` JOIN ServiceSubcategory AS `L-1-1-1` ON `L-1-1-1`.service_id = `L-1-1`.id JOIN lnkCustomerContractToService AS `l11` ON `l11`.service_id = `L-1-1`.id JOIN CustomerContract AS `cc1` ON `l11`.customercontract_id = `cc1`.id WHERE ((`L-1-1`.`id` = 8) AND ((`L-1-1-1`.`status` != 'obsolete') AND ((`cc1`.`org_id` = 2) AND (`L-1-1-1`.`status` != 'obsolete')))) UNION SELECT `L-1-1` FROM Service AS `L-1-1` JOIN lnkCustomerContractToService AS `l1` ON `l1`.service_id = `L-1-1`.id JOIN CustomerContract AS `cc` ON `l1`.customercontract_id = `cc`.id JOIN ServiceSubcategory AS `L-1-1-1` ON `L-1-1-1`.service_id = `L-1-1`.id WHERE (((`cc`.`org_id` = 2) AND (`L-1-1`.`status` != 'obsolete')) AND ((`L-1-1-1`.`status` != 'obsolete') AND (`L-1-1`.`id` = 8))) UNION SELECT `L-1-1` FROM Service AS `L-1-1` JOIN ServiceSubcategory AS `L-1-1-1` ON `L-1-1-1`.service_id = `L-1-1`.id WHERE ((`L-1-1`.`id` = 8) AND ((`L-1-1-1`.`status` != 'obsolete') AND (`L-1-1`.`id` = 8)))",
			],
			'Bug 2585 K' => [
				'left' => "SELECT `L-1` FROM ServiceFamily AS `L-1` JOIN Service AS `SHOULD_BE_TRANSLATED` ON `SHOULD_BE_TRANSLATED`.servicefamily_id = `L-1`.id JOIN lnkCustomerContractToService AS `l1` ON `l1`.service_id = `SHOULD_BE_TRANSLATED`.id JOIN CustomerContract AS `cc` ON `l1`.customercontract_id = `cc`.id WHERE (`cc`.`org_id` = 2) UNION SELECT `L-1` FROM ServiceFamily AS `L-1` JOIN Service AS `SHOULD_ALSO_BE_TRANSLATED` ON `SHOULD_ALSO_BE_TRANSLATED`.servicefamily_id = `L-1`.id WHERE (`SHOULD_ALSO_BE_TRANSLATED`.`id` = 8)",
				'right' => "SELECT `L-1-1` FROM Service AS `L-1-1` JOIN lnkCustomerContractToService AS `l1` ON `l1`.service_id = `L-1-1`.id JOIN CustomerContract AS `cc` ON `l1`.customercontract_id = `cc`.id JOIN ServiceSubcategory AS `L-1-1-1` ON `L-1-1-1`.service_id = `L-1-1`.id WHERE (((`cc`.`org_id` = 2) AND (`L-1-1`.`status` != 'obsolete')) AND ((`L-1-1-1`.`status` != 'obsolete') AND ((`cc`.`org_id` = 2) AND (`L-1-1-1`.`status` != 'obsolete')))) UNION SELECT `L-1-1` FROM Service AS `L-1-1` JOIN ServiceSubcategory AS `L-1-1-1` ON `L-1-1-1`.service_id = `L-1-1`.id JOIN lnkCustomerContractToService AS `l11` ON `l11`.service_id = `L-1-1`.id JOIN CustomerContract AS `cc1` ON `l11`.customercontract_id = `cc1`.id WHERE ((`L-1-1`.`id` = 8) AND ((`L-1-1-1`.`status` != 'obsolete') AND ((`cc1`.`org_id` = 2) AND (`L-1-1-1`.`status` != 'obsolete')))) UNION SELECT `L-1-1` FROM Service AS `L-1-1` JOIN lnkCustomerContractToService AS `l1` ON `l1`.service_id = `L-1-1`.id JOIN CustomerContract AS `cc` ON `l1`.customercontract_id = `cc`.id JOIN ServiceSubcategory AS `L-1-1-1` ON `L-1-1-1`.service_id = `L-1-1`.id WHERE (((`cc`.`org_id` = 2) AND (`L-1-1`.`status` != 'obsolete')) AND ((`L-1-1-1`.`status` != 'obsolete') AND (`L-1-1`.`id` = 8))) UNION SELECT `L-1-1` FROM Service AS `L-1-1` JOIN ServiceSubcategory AS `L-1-1-1` ON `L-1-1-1`.service_id = `L-1-1`.id WHERE ((`L-1-1`.`id` = 8) AND ((`L-1-1-1`.`status` != 'obsolete') AND (`L-1-1`.`id` = 8)))",
				'parent_att' => 'servicefamily_id',
				'result' => "SELECT `L-1` FROM ServiceFamily AS `L-1` JOIN Service AS `SHOULD_BE_TRANSLATED` ON `SHOULD_BE_TRANSLATED`.servicefamily_id = `L-1`.id JOIN lnkCustomerContractToService AS `l1` ON `l1`.service_id = `SHOULD_BE_TRANSLATED`.id JOIN CustomerContract AS `cc` ON `l1`.customercontract_id = `cc`.id JOIN ServiceSubcategory AS `L-1-1-1` ON `L-1-1-1`.service_id = `SHOULD_BE_TRANSLATED`.id WHERE ((`cc`.`org_id` = 2) AND (((`cc`.`org_id` = 2) AND (`SHOULD_BE_TRANSLATED`.`status` != 'obsolete')) AND ((`L-1-1-1`.`status` != 'obsolete') AND ((`cc`.`org_id` = 2) AND (`L-1-1-1`.`status` != 'obsolete'))))) UNION SELECT `L-1` FROM ServiceFamily AS `L-1` JOIN Service AS `SHOULD_ALSO_BE_TRANSLATED` ON `SHOULD_ALSO_BE_TRANSLATED`.servicefamily_id = `L-1`.id JOIN lnkCustomerContractToService AS `l1` ON `l1`.service_id = `SHOULD_ALSO_BE_TRANSLATED`.id JOIN CustomerContract AS `cc` ON `l1`.customercontract_id = `cc`.id JOIN ServiceSubcategory AS `L-1-1-1` ON `L-1-1-1`.service_id = `SHOULD_ALSO_BE_TRANSLATED`.id WHERE ((`SHOULD_ALSO_BE_TRANSLATED`.`id` = 8) AND (((`cc`.`org_id` = 2) AND (`SHOULD_ALSO_BE_TRANSLATED`.`status` != 'obsolete')) AND ((`L-1-1-1`.`status` != 'obsolete') AND ((`cc`.`org_id` = 2) AND (`L-1-1-1`.`status` != 'obsolete'))))) UNION SELECT `L-1` FROM ServiceFamily AS `L-1` JOIN Service AS `SHOULD_BE_TRANSLATED` ON `SHOULD_BE_TRANSLATED`.servicefamily_id = `L-1`.id JOIN lnkCustomerContractToService AS `l1` ON `l1`.service_id = `SHOULD_BE_TRANSLATED`.id JOIN CustomerContract AS `cc` ON `l1`.customercontract_id = `cc`.id JOIN ServiceSubcategory AS `L-1-1-1` ON `L-1-1-1`.service_id = `SHOULD_BE_TRANSLATED`.id WHERE ((`cc`.`org_id` = 2) AND ((`SHOULD_BE_TRANSLATED`.`id` = 8) AND ((`L-1-1-1`.`status` != 'obsolete') AND ((`cc`.`org_id` = 2) AND (`L-1-1-1`.`status` != 'obsolete'))))) UNION SELECT `L-1` FROM ServiceFamily AS `L-1` JOIN Service AS `SHOULD_ALSO_BE_TRANSLATED` ON `SHOULD_ALSO_BE_TRANSLATED`.servicefamily_id = `L-1`.id JOIN ServiceSubcategory AS `L-1-1-1` ON `L-1-1-1`.service_id = `SHOULD_ALSO_BE_TRANSLATED`.id JOIN lnkCustomerContractToService AS `l11` ON `l11`.service_id = `SHOULD_ALSO_BE_TRANSLATED`.id JOIN CustomerContract AS `cc1` ON `l11`.customercontract_id = `cc1`.id WHERE ((`SHOULD_ALSO_BE_TRANSLATED`.`id` = 8) AND ((`SHOULD_ALSO_BE_TRANSLATED`.`id` = 8) AND ((`L-1-1-1`.`status` != 'obsolete') AND ((`cc1`.`org_id` = 2) AND (`L-1-1-1`.`status` != 'obsolete'))))) UNION SELECT `L-1` FROM ServiceFamily AS `L-1` JOIN Service AS `SHOULD_BE_TRANSLATED` ON `SHOULD_BE_TRANSLATED`.servicefamily_id = `L-1`.id JOIN lnkCustomerContractToService AS `l1` ON `l1`.service_id = `SHOULD_BE_TRANSLATED`.id JOIN CustomerContract AS `cc` ON `l1`.customercontract_id = `cc`.id JOIN ServiceSubcategory AS `L-1-1-1` ON `L-1-1-1`.service_id = `SHOULD_BE_TRANSLATED`.id WHERE ((`cc`.`org_id` = 2) AND (((`cc`.`org_id` = 2) AND (`SHOULD_BE_TRANSLATED`.`status` != 'obsolete')) AND ((`L-1-1-1`.`status` != 'obsolete') AND (`SHOULD_BE_TRANSLATED`.`id` = 8)))) UNION SELECT `L-1` FROM ServiceFamily AS `L-1` JOIN Service AS `SHOULD_ALSO_BE_TRANSLATED` ON `SHOULD_ALSO_BE_TRANSLATED`.servicefamily_id = `L-1`.id JOIN lnkCustomerContractToService AS `l1` ON `l1`.service_id = `SHOULD_ALSO_BE_TRANSLATED`.id JOIN CustomerContract AS `cc` ON `l1`.customercontract_id = `cc`.id JOIN ServiceSubcategory AS `L-1-1-1` ON `L-1-1-1`.service_id = `SHOULD_ALSO_BE_TRANSLATED`.id WHERE ((`SHOULD_ALSO_BE_TRANSLATED`.`id` = 8) AND (((`cc`.`org_id` = 2) AND (`SHOULD_ALSO_BE_TRANSLATED`.`status` != 'obsolete')) AND ((`L-1-1-1`.`status` != 'obsolete') AND (`SHOULD_ALSO_BE_TRANSLATED`.`id` = 8)))) UNION SELECT `L-1` FROM ServiceFamily AS `L-1` JOIN Service AS `SHOULD_BE_TRANSLATED` ON `SHOULD_BE_TRANSLATED`.servicefamily_id = `L-1`.id JOIN lnkCustomerContractToService AS `l1` ON `l1`.service_id = `SHOULD_BE_TRANSLATED`.id JOIN CustomerContract AS `cc` ON `l1`.customercontract_id = `cc`.id JOIN ServiceSubcategory AS `L-1-1-1` ON `L-1-1-1`.service_id = `SHOULD_BE_TRANSLATED`.id WHERE ((`cc`.`org_id` = 2) AND ((`SHOULD_BE_TRANSLATED`.`id` = 8) AND ((`L-1-1-1`.`status` != 'obsolete') AND (`SHOULD_BE_TRANSLATED`.`id` = 8)))) UNION SELECT `L-1` FROM ServiceFamily AS `L-1` JOIN Service AS `SHOULD_ALSO_BE_TRANSLATED` ON `SHOULD_ALSO_BE_TRANSLATED`.servicefamily_id = `L-1`.id JOIN ServiceSubcategory AS `L-1-1-1` ON `L-1-1-1`.service_id = `SHOULD_ALSO_BE_TRANSLATED`.id WHERE ((`SHOULD_ALSO_BE_TRANSLATED`.`id` = 8) AND ((`SHOULD_ALSO_BE_TRANSLATED`.`id` = 8) AND ((`L-1-1-1`.`status` != 'obsolete') AND (`SHOULD_ALSO_BE_TRANSLATED`.`id` = 8))))",
			],
			'test 2585 K2' => [
				'left' => "SELECT Service AS s JOIN lnkCustomerContractToService AS l1 ON l1.service_id=s.id JOIN CustomerContract AS cc ON l1.customercontract_id=cc.id WHERE cc.org_id = 2 AND s.status != 'obsolete' UNION SELECT Service WHERE id = 8",
				'right' => "SELECT ServiceSubcategory AS ssc JOIN Service AS s ON ssc.service_id=s.id JOIN lnkCustomerContractToService AS l1 ON l1.service_id=s.id JOIN CustomerContract AS cc ON l1.customercontract_id=cc.id WHERE cc.org_id = 2 AND ssc.status != 'obsolete' UNION SELECT ServiceSubcategory AS ssc JOIN Service AS s ON ssc.service_id=s.id WHERE s.id = 8",
				'parent_att' => 'service_id',
				'result' => "SELECT `s` FROM Service AS `s` JOIN lnkCustomerContractToService AS `l1` ON `l1`.service_id = `s`.id JOIN CustomerContract AS `cc` ON `l1`.customercontract_id = `cc`.id JOIN ServiceSubcategory AS `ssc` ON `ssc`.service_id = `s`.id WHERE (((`cc`.`org_id` = 2) AND (`s`.`status` != 'obsolete')) AND ((`cc`.`org_id` = 2) AND (`ssc`.`status` != 'obsolete'))) UNION SELECT `Service` FROM Service AS `Service` JOIN ServiceSubcategory AS `ssc` ON `ssc`.service_id = `Service`.id JOIN lnkCustomerContractToService AS `l11` ON `l11`.service_id = `Service`.id JOIN CustomerContract AS `cc1` ON `l11`.customercontract_id = `cc1`.id WHERE ((`Service`.`id` = 8) AND ((`cc1`.`org_id` = 2) AND (`ssc`.`status` != 'obsolete'))) UNION SELECT `s` FROM Service AS `s` JOIN lnkCustomerContractToService AS `l1` ON `l1`.service_id = `s`.id JOIN CustomerContract AS `cc` ON `l1`.customercontract_id = `cc`.id JOIN ServiceSubcategory AS `ssc` ON `ssc`.service_id = `s`.id WHERE (((`cc`.`org_id` = 2) AND (`s`.`status` != 'obsolete')) AND (`s`.`id` = 8)) UNION SELECT `Service` FROM Service AS `Service` JOIN ServiceSubcategory AS `ssc` ON `ssc`.service_id = `Service`.id WHERE ((`Service`.`id` = 8) AND (`Service`.`id` = 8))",
			],
			'Bug 2585 K3' => [
				'left' => "SELECT `L-1` FROM ServiceFamily AS `L-1` JOIN Service AS `s` ON `s`.servicefamily_id = `L-1`.id WHERE 1",
				'right' => "SELECT `L-1-1` FROM Service AS `L-1-1` WHERE 1",
				'parent_att' => 'servicefamily_id',
				'result' => "SELECT `L-1` FROM ServiceFamily AS `L-1` JOIN Service AS `s` ON `s`.servicefamily_id = `L-1`.id WHERE 1",
			],
//			'test' => [
//				'left' => "",
//				'right' => "",
//				'parent_att' => '',
//				'result' => "",
//			],
		];
	}

	/**
	 * Bug #2970
	 * @throws \CoreException
	 * @throws \CoreWarning
	 * @throws \OQLException
	 */
	public function testFilterOnJoin()
	{
		$sReq1 = "SELECT `L-1` FROM Organization AS `L-1` WHERE (`L-1`.`id` = 2)";
		$sReq2 = "SELECT `L-1-1` FROM CustomerContract AS `L-1-1` JOIN Organization AS `SHOULD_BE_TRANSLATED` ON `L-1-1`.org_id = `SHOULD_BE_TRANSLATED`.id WHERE (((`L-1-1`.`status` = 'active') OR (`L-1-1`.`status` = 'standby')) AND (`SHOULD_BE_TRANSLATED`.`id` = 2))";

		$oFilter1 = DBSearch::FromOQL($sReq1);
		$oFilter2 = DBSearch::FromOQL($sReq2);
		$aRealiasingMap = array();
		$oFilter1 = $oFilter1->Join($oFilter2,
			DBSearch::JOIN_REFERENCED_BY,
			'org_id',
			TREE_OPERATOR_EQUALS, $aRealiasingMap);

		$this->debug("\nRealiasing Map");
		$this->debug($aRealiasingMap);

		$sRes1 = $oFilter1->ToOQL();
		$this->debug("\nJoined");
		$this->debug($sRes1);

		foreach($oFilter1->GetCriteria_ReferencedBy() as $sForeignClass => $aReferences)
		{
			foreach ($aReferences as $sForeignExtKeyAttCode => $aFiltersByOperator)
			{
				foreach ($aFiltersByOperator as $iOperatorCode => $aFilters)
				{
					foreach ($aFilters as $index => $oForeignFilter)
					{
						$this->debug("\nReferencedBy");
						$this->debug($oForeignFilter->ToOQL());
					}
				}
			}
		}

		$this->assertFalse(strpos($sRes1, '`SHOULD_BE_TRANSLATED`.'));

		$sReq3 = "SELECT `CustomerContract` FROM CustomerContract AS `CustomerContract` WHERE (`CustomerContract`.`org_id` IN ('2'))";
		$oFilter3 = DBSearch::FromOQL($sReq3);

		$oFilter1 = $oFilter1->Filter('L-1-1', $oFilter3);

		$sRes1 = $oFilter1->ToOQL();
		$this->debug("\nFiltered");
		$this->debug($sRes1);

		$this->assertFalse(strpos($sRes1, '`SHOULD_BE_TRANSLATED`.'));
	}
}

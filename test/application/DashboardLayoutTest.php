<?php
/**
 * Copyright (C) 2013-2021 Combodo SARL
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */

use Combodo\iTop\Test\UnitTest\ItopTestCase;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 * @covers utils
 */
class DashboardLayoutTest extends ItopTestCase
{
	public function setUp()
	{
		parent::setUp();

		require_once APPROOT.'application/dashboardlayout.class.inc.php';
	}

	/**
	 * @return array
	 */
	public function GetDashletCoordinatesProvider()
	{
		return array(
			'OneColLayout-Cell0' => array('DashboardLayoutOneCol', 0, array(0, 0)),
			'OneColLayout-Cell1' => array('DashboardLayoutOneCol', 1, array(0, 1)),
			'TwoColsLayout-Cell0' => array('DashboardLayoutTwoCols', 0, array(0, 0)),
			'TwoColsLayout-Cell1' => array('DashboardLayoutTwoCols', 1, array(1, 0)),
			'TwoColsLayout-Cell2' => array('DashboardLayoutTwoCols', 2, array(0, 1)),
			'TwoColsLayout-Cell3' => array('DashboardLayoutTwoCols', 3, array(1, 1)),
			'ThreeColsLayout-Cell0' => array('DashboardLayoutThreeCols', 0, array(0, 0)),
			'ThreeColsLayout-Cell1' => array('DashboardLayoutThreeCols', 1, array(1, 0)),
			'ThreeColsLayout-Cell2' => array('DashboardLayoutThreeCols', 2, array(2, 0)),
			'ThreeColsLayout-Cell3' => array('DashboardLayoutThreeCols', 3, array(0, 1)),
			'ThreeColsLayout-Cell4' => array('DashboardLayoutThreeCols', 4, array(1, 1)),
			'ThreeColsLayout-Cell5' => array('DashboardLayoutThreeCols', 5, array(2, 1)),
		);
	}

	/**
	 * @param string $sDashboardLayoutClass
	 * @param int $iCellIdx
	 * @param array $aExpectedCoordinates
	 * @dataProvider GetDashletCoordinatesProvider
	 * @since N°2735
	 */
	public function testGetDashletCoordinates($sDashboardLayoutClass, $iCellIdx, $aExpectedCoordinates)
	{
		$oDashboardLayout = new $sDashboardLayoutClass();
		$aDashletCoordinates = $oDashboardLayout->GetDashletCoordinates($iCellIdx);

		$this->assertEquals($aExpectedCoordinates,$aDashletCoordinates);
	}
}

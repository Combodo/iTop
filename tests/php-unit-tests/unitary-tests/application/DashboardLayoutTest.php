<?php

/**
 * Copyright (C) 2013-2024 Combodo SAS
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

namespace Combodo\iTop\Test\UnitTest\Application;

use Combodo\iTop\Test\UnitTest\ItopTestCase;

/**
 * @covers utils
 */
class DashboardLayoutTest extends ItopTestCase
{
	/**
	 * @return array
	 */
	public function GetDashletCoordinatesProvider()
	{
		return [
			'OneColLayout-Cell0' => ['DashboardLayoutOneCol', 0, [0, 0]],
			'OneColLayout-Cell1' => ['DashboardLayoutOneCol', 1, [0, 1]],
			'TwoColsLayout-Cell0' => ['DashboardLayoutTwoCols', 0, [0, 0]],
			'TwoColsLayout-Cell1' => ['DashboardLayoutTwoCols', 1, [1, 0]],
			'TwoColsLayout-Cell2' => ['DashboardLayoutTwoCols', 2, [0, 1]],
			'TwoColsLayout-Cell3' => ['DashboardLayoutTwoCols', 3, [1, 1]],
			'ThreeColsLayout-Cell0' => ['DashboardLayoutThreeCols', 0, [0, 0]],
			'ThreeColsLayout-Cell1' => ['DashboardLayoutThreeCols', 1, [1, 0]],
			'ThreeColsLayout-Cell2' => ['DashboardLayoutThreeCols', 2, [2, 0]],
			'ThreeColsLayout-Cell3' => ['DashboardLayoutThreeCols', 3, [0, 1]],
			'ThreeColsLayout-Cell4' => ['DashboardLayoutThreeCols', 4, [1, 1]],
			'ThreeColsLayout-Cell5' => ['DashboardLayoutThreeCols', 5, [2, 1]],
		];
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

		$this->assertEquals($aExpectedCoordinates, $aDashletCoordinates);
	}
}

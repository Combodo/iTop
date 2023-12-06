<?php
/**
 * Copyright (C) 2010-2023 Combodo SARL
 *
 *   This file is part of iTop.
 *
 *   iTop is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU Affero General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   iTop is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU Affero General Public License for more details.
 *
 *   You should have received a copy of the GNU Affero General Public License
 *   along with iTop. If not, see <http: *www.gnu.org/licenses/>
 *
 */

namespace Combodo\iTop\Test\UnitTest\Module\iTopPortalBase;

use Combodo\iTop\Portal\Helper\RequestManipulatorHelper;
use Combodo\iTop\Test\UnitTest\ItopTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @covers \Combodo\iTop\Portal\Helper\RequestManipulatorHelper
 */
class RequestManipulatorTest extends ItopTestCase
{

	protected function LoadRequiredItopFiles(): void
	{
		parent::LoadRequiredItopFiles();
		$this->RequireOnceItopFile('datamodels/2.x/itop-portal-base/portal/src/Helper/RequestManipulatorHelper.php');
	}

	public function testReadParam()
	{
		// Create a simple request with only necessary information
		$oRequest = new Request();
		$aValue = ['a', 'b', 'c'];
		$oRequest->request->set('array_value', $aValue);

		// Create a request stack
		$oRequestStack = new RequestStack();
		$oRequestStack->push($oRequest);

		// Instantiate request manipulator helper service
		$oRequestManipulatorHelper = new RequestManipulatorHelper($oRequestStack);

		// I - default null value
		$oNullArrayValue = $oRequestManipulatorHelper->ReadParam('null_array_value',  null, FILTER_UNSAFE_RAW, FILTER_REQUIRE_ARRAY);
		$this->assertNull($oNullArrayValue);

		// II - default empty array value
		$oEmptyArrayValue = $oRequestManipulatorHelper->ReadParam('empty_array_value', [], FILTER_UNSAFE_RAW, FILTER_REQUIRE_ARRAY);
		$this->assertEmpty($oNullArrayValue);

		// III - since symfony 6.4, this code raised a bad request exception
		$this->expectException("Symfony\\Component\\HttpFoundation\\Exception\\BadRequestException");
		$oRequestManipulatorHelper->ReadParam('array_value', null, FILTER_UNSAFE_RAW);

		// IV - control value
		$aReadValue = $oRequestManipulatorHelper->ReadParam('array_value', null, FILTER_UNSAFE_RAW, FILTER_REQUIRE_ARRAY);
		$this->assertEquals($aValue, $aReadValue);
	}


}

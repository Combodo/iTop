<?php
// Copyright (c) 2010-2021 Combodo SARL
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>
//

/**
 * Created by PhpStorm.
 * User: Eric
 * Date: 30/10/2017
 * Time: 13:43
 */

namespace Combodo\iTop\Test\UnitTest\Core;

use Combodo\iTop\Test\UnitTest\ItopTestCase;
use Dict;
use Exception;


/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class dictTest extends ItopTestCase
{
	protected function setUp()
	{
		parent::setUp();
		require_once (APPROOT.'core/dict.class.inc.php');
		require_once 'mockDict.incphp';
	}

    /**
     * @throws Exception
     */
    public function testType()
	{
		$this->assertInternalType('string', Dict::S('Core:AttributeURL'));
		$this->assertInternalType('string', Dict::Format('Change:AttName_SetTo', '1', '2'));
	}
}

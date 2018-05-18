<?php
// Copyright (c) 2010-2017 Combodo SARL
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

namespace Combodo\iTop\Test\UnitTest;
/**
 * Created by PhpStorm.
 * User: Eric
 * Date: 20/11/2017
 * Time: 11:21
 */

use PHPUnit\Framework\TestCase;

define('DEBUG_UNIT_TEST', false);

class ItopTestCase extends TestCase
{
    protected function setUp()
	{
		@include_once '../approot.inc.php';
        @include_once '../../approot.inc.php';
		@include_once '../../../approot.inc.php';
		@include_once '../../../../approot.inc.php';

        $this->debug("\n----------\n---------- ".$this->getName()."\n----------\n");

	}

	protected function debug($sMsg)
    {
        if (DEBUG_UNIT_TEST)
        {
        	if (is_string($sMsg))
	        {
	        	echo "$sMsg\n";
	        }
	        else
	        {
	        	print_r($sMsg);
	        }
        }
    }
}
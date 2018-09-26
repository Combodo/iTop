<?php
/**
 * Copyright (C) 2018 Dennis Lassiter
 *
 * This file is part of iTop.
 *
 *  iTop is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with iTop. If not, see <http://www.gnu.org/licenses/>
 *
 */

class UtilsTest extends \Combodo\iTop\Test\UnitTest\ItopTestCase
{
    public function setUp()
    {
        parent::setUp();
        require_once(APPROOT . 'application/utils.inc.php');
    }

    /**
     * @dataProvider memoryLimitDataProvider
     */
    public function testIsMemoryLimit($expected, $memoryLimit, $requiredMemory)
    {
        $this->assertSame($expected, utils::IsMemoryLimitOk($memoryLimit, $requiredMemory));
    }

    /**
     * DataProvider for testIsMemoryLimitOk
     *
     * @return array
     */
    public function memoryLimitDataProvider()
    {
        return [
            [true, '-1', 1024],
            [true, -1, 1024],
            [true, 1024, 1024],
            [true, 2048, 1024],
            [false, 1024, 2048],
        ];
    }
}
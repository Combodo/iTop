<?php
/**
 * Copyright (C) 2010-2021 Combodo SARL
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

/**
 * Created by PhpStorm.
 * User: Eric
 * Date: 08/03/2018
 * Time: 11:28
 */

namespace Combodo\iTop\Test\UnitTest\Application\Search;

use Combodo\iTop\Application\Search\CriterionParser;
use Combodo\iTop\Test\UnitTest\ItopTestCase;

/**
 * @group itopRequestMgmt
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class CriterionParserTest extends ItopTestCase
{
	protected function setUp()
	{
		parent::setUp();

		require_once(APPROOT."application/startup.inc.php");
		require_once(APPROOT."sources/application/search/criterionparser.class.inc.php");
	}

	public function testParse()
	{
		$sBaseOql = 'SELECT UserRequest';
		$aCriterion = json_decode('{
    "or": [
        {
            "and": [
                {
                    "ref": "UserRequest.start_date",
                    "values": [
                        {
                            "value": "2017-01-01",
                            "label": "2017-01-01 00:00:00"
                        }
                    ],
                    "operator": ">",
                    "oql": ""
                },
                {
                    "ref": "UserRequest.start_date",
                    "values": [
                        {
                            "value": "2018-01-01",
                            "label": "2018-01-01 00:00:00"
                        }
                    ],
                    "operator": "<",
                    "oql": "(`UserRequest`.`start_date` < \'2018-01-01\')"
                }
            ]
        }
    ]
}
', true);
		$oSearch = CriterionParser::Parse($sBaseOql, $aCriterion);

		//$this->debug($oSearch);

		$this->assertEquals("SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE ((`UserRequest`.`start_date` > '2017-01-01') AND (`UserRequest`.`start_date` < '2018-01-01'))", $oSearch->ToOQL());
	}
}

<?php
/**
 * Copyright (C) 2010-2018 Combodo SARL
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
 * Time: 16:46
 */

namespace Combodo\iTop\Test\UnitTest\Application\Search;

use Combodo\iTop\Application\Search\CriterionConversion\CriterionToOQL;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;

class CriterionConversionTest extends ItopDataTestCase
{
	/**
	 * @throws Exception
	 */
	protected function setUp()
	{
		parent::setUp();

		require_once(APPROOT."sources/application/search/criterionconversionabstract.class.inc.php");
	}

	/**
	 * @dataProvider ToOqlProvider
	 */
	public function testToOql($sJSONCriterion, $sExpectedOQL)
	{
		$sOql = CriterionToOQL::Convert(
			json_decode($sJSONCriterion, true)
		);

		$this->debug($sOql);
		$this->assertEquals($sExpectedOQL, $sOql);
	}

	public function ToOqlProvider()
	{
		return array(
			'>' => array(
				'{
                    "ref": "UserRequest.start_date",
                    "values": [
                        {
                            "value": "2017-01-01",
                            "label": "2017-01-01 00:00:00"
                        }
                    ],
                    "operator": ">",
                    "oql": ""
                }',
				"(`UserRequest`.`start_date` > '2017-01-01')"
			),
			'contains' => array(
				'{
                    "ref": "Contact.name",
                    "values": [
                        {
                            "value": "toto",
                            "label": "toto"
                        }
                    ],
                    "operator": "contains",
                    "oql": ""
                }',
				"(`Contact`.`name` LIKE '%toto%')"
			),
			'starts_with' => array(
				'{
                    "ref": "Contact.name",
                    "values": [
                        {
                            "value": "toto",
                            "label": "toto"
                        }
                    ],
                    "operator": "starts_with",
                    "oql": ""
                }',
				"(`Contact`.`name` LIKE 'toto%')"
			),
			'ends_with' => array(
				'{
                    "ref": "Contact.name",
                    "values": [
                        {
                            "value": "toto",
                            "label": "toto"
                        }
                    ],
                    "operator": "ends_with",
                    "oql": ""
                }',
				"(`Contact`.`name` LIKE '%toto')"
			),
			'empty' => array(
				'{
                    "ref": "Contact.name",
                    "values": [
                        {
                            "value": "",
                            "label": ""
                        }
                    ],
                    "operator": "empty",
                    "oql": ""
                }',
				"(`Contact`.`name` = '')"
			),
			'not_empty' => array(
				'{
                    "ref": "Contact.name",
                    "values": [
                        {
                            "value": "",
                            "label": ""
                        }
                    ],
                    "operator": "not_empty",
                    "oql": ""
                }',
				"(`Contact`.`name` != '')"
			),
		);
	}
}

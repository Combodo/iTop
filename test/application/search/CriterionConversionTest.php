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
use Combodo\iTop\Application\Search\CriterionConversion\CriterionToSearchForm;
use Combodo\iTop\Application\Search\CriterionParser;
use Combodo\iTop\Application\Search\SearchForm;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;

class CriterionConversionTest extends ItopDataTestCase
{
	/**
	 * @throws \Exception
	 */
	protected function setUp()
	{
		parent::setUp();

		require_once(APPROOT."sources/application/search/criterionconversionabstract.class.inc.php");
	}

	/**
	 * @dataProvider ToOqlProvider
	 *
	 * @param $sJSONCriterion
	 * @param $sExpectedOQL
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

	/**
	 * @dataProvider ToSearchFormProvider
	 *
	 * @param $aCriterion
	 * @param $sExpectedOperator
	 *
	 * @throws \OQLException
	 */
	function testToSearchForm($aCriterion, $sExpectedOperator)
	{
		$oSearchForm = new SearchForm();
		$oSearch = \DBSearch::FromOQL("SELECT Contact");
		$aFields = $oSearchForm->GetFields(new \DBObjectSet($oSearch));
		$aRes = CriterionToSearchForm::Convert($aCriterion, $aFields, $oSearch->GetJoinedClasses());
		$this->debug($aRes);
		$this->assertEquals($sExpectedOperator, $aRes[0]['operator']);
	}

	function ToSearchFormProvider()
	{
		return array(
			'=' => array(
				json_decode('[
                {
                    "ref": "Contact.name",
                    "widget": "string",
                    "values": [
                        {
                            "value": "toto",
                            "label": "toto"
                        }
                    ],
                    "operator": "=",
                    "oql": "(`Contact`.`name` = \'toto\')"
                }
            ]', true),
				'='
			),
			'starts_with' => array(
				json_decode('[
                {
                    "ref": "Contact.name",
                    "widget": "string",
                    "values": [
                        {
                            "value": "toto%",
                            "label": "toto%"
                        }
                    ],
                    "operator": "LIKE",
                    "oql": "(`Contact`.`name` LIKE \'toto%\')"
                }
            ]', true),
				'starts_with'
			),
			'ends_with' => array(
				json_decode('[
                {
                    "ref": "Contact.name",
                    "widget": "string",
                    "values": [
                        {
                            "value": "%toto",
                            "label": "%toto"
                        }
                    ],
                    "operator": "LIKE",
                    "oql": "(`Contact`.`name` LIKE \'%toto\')"
                }
            ]', true),
				'ends_with'
			),
			'contains' => array(
				json_decode('[
                {
                    "widget": "string",
                    "ref": "Contact.name",
                    "values": [
                        {
                            "value": "%toto%",
                            "label": "%toto%"
                        }
                    ],
                    "operator": "LIKE",
                    "oql": "(`Contact`.`name` LIKE \'%toto%\')"
                }
            ]', true),
				'contains'
			),
			'empty1' => array(
				json_decode('[
                {
                    "widget": "string",
                    "ref": "Contact.name",
                    "values": [
                        {
                            "value": "",
                            "label": ""
                        }
                    ],
                    "operator": "LIKE",
                    "oql": "(`Contact`.`name` LIKE \'\')"
                }
            ]', true),
				'empty'
			),
			'empty2' => array(
				json_decode('[
                {
                    "widget": "string",
                    "ref": "Contact.name",
                    "values": [
                        {
                            "value": "",
                            "label": ""
                        }
                    ],
                    "operator": "=",
                    "oql": "(`Contact`.`name` = \'\')"
                }
            ]', true),
				'empty'
			),
			'not_empty' => array(
				json_decode('[
                {
                    "widget": "string",
                    "ref": "Contact.name",
                    "values": [
                        {
                            "value": "",
                            "label": ""
                        }
                    ],
                    "operator": "!=",
                    "oql": "(`Contact`.`name` != \'\')"
                }
            ]', true),
				'not_empty'
			),
		);
	}

	/**
	 * @dataProvider OqlProvider
	 *
	 * @param $sOQL
	 *
	 * @throws \OQLException
	 */
	function testOqlToForSearchToOql($sOQL)
	{
		$this->debug($sOQL);
		$oSearchForm = new SearchForm();
		$oSearch = \DBSearch::FromOQL($sOQL);
		$aFields = $oSearchForm->GetFields(new \DBObjectSet($oSearch));
		$aCriterion = $oSearchForm->GetCriterion($oSearch, $aFields);

		$aAndCriterion = $aCriterion['or'][0]['and'];

		$aNewCriterion = array();
		foreach($aAndCriterion as $aCriteria)
		{
			if ($aCriteria['widget'] != \AttributeDefinition::SEARCH_WIDGET_TYPE_RAW)
			{
				unset($aCriteria['oql']);
				foreach($aFields as $aCatFields)
				{
					if (isset($aCatFields[$aCriteria['ref']]))
					{
						$aField = $aCatFields[$aCriteria['ref']];
						break;
					}
				}
				if (isset($aField))
				{
					$aCriteria['code'] = $aField['code'];
					$aCriteria['class'] = $aField['class'];
				}
			}

			$aNewCriterion[] = $aCriteria;
		}
		$this->debug($aNewCriterion);

		$aCriterion['or'][0]['and'] = $aNewCriterion;

		$oSearch->ResetCondition();
		$oFilter = CriterionParser::Parse($oSearch->ToOQL(), $aCriterion);

		$this->debug($oFilter->ToOQL());

		$this->assertTrue(true);
	}

	function OqlProvider()
	{
		return array(
			'no criteria' => array('OQL' => 'SELECT WebApplication'),
			'string starts' => array('OQL' => "SELECT Contact WHERE name LIKE 'toto%'"),
			'string ends' => array('OQL' => "SELECT Contact WHERE name LIKE '%toto'"),
			'string contains 1' => array('OQL' => "SELECT Contact WHERE name LIKE '%toto%'"),
			'string contains 2' => array('OQL' => "SELECT Person AS B WHERE B.name LIKE '%A%'"),
			'string regexp' => array('OQL' => "SELECT Server WHERE name REGEXP '^dbserver[0-9]+\\\\\\\\..+\\\\\\\\.[a-z]{2,3}$'"),
			'enum + key =' => array('OQL' => "SELECT Contact WHERE status = 'active' AND org_id = 3"),
			'enum =' => array('OQL' => "SELECT Contact WHERE status = 'active'"),
			'enum IN' => array('OQL' => "SELECT Contact WHERE status IN ('active', 'inactive')"),
			'enum NOT IN 1' => array('OQL' => "SELECT Contact WHERE status NOT IN ('active')"),
			'enum NOT IN 2' => array('OQL' => "SELECT Person AS p JOIN UserRequest AS u ON u.agent_id = p.id WHERE u.status != 'closed'"),
			'enum undefined 1' => array('OQL' => "SELECT FunctionalCI WHERE ((business_criticity = 'high') OR ISNULL(business_criticity)) AND 1"),
			'enum undefined 2' => array('OQL' => "SELECT FunctionalCI WHERE ((business_criticity IN ('high', 'medium')) OR ISNULL(business_criticity)) AND 1"),
			'enum undefined 3' => array('OQL' => "SELECT FunctionalCI WHERE ISNULL(business_criticity)"),
			'key NOT IN' => array('OQL' => "SELECT Contact WHERE org_id NOT IN ('1')"),
			'key IN' => array('OQL' => "SELECT Contact WHERE org_id IN ('1')"),
			'key empty' => array('OQL' => "SELECT Person WHERE location_id = '0'"),
			'Date relative 1' => array('OQL' => "SELECT UserRequest WHERE DATE_SUB(NOW(), INTERVAL 14 DAY) < start_date"),
			'Date relative 2' => array('OQL' => "SELECT Contract AS c WHERE c.end_date > NOW() AND c.end_date < DATE_ADD(NOW(), INTERVAL 30 DAY)"),
			'Date relative 3' => array('OQL' => "SELECT UserRequest AS u WHERE u.close_date > DATE_ADD(u.start_date, INTERVAL 8 HOUR)"),
			'Date relative 4' => array('OQL' => "SELECT UserRequest AS u WHERE u.start_date < DATE_SUB(NOW(), INTERVAL 60 MINUTE) AND u.status = 'new'"),
			'Date between 1' => array('OQL' => "SELECT UserRequest WHERE start_date > '2017-01-01 00:00:00' AND '2018-01-01 00:00:00' >= start_date"),
			'Date between 2' => array('OQL' => "SELECT UserRequest WHERE start_date > '2017-01-01 00:00:00' AND status = 'active' AND org_id = 3 AND '2018-01-01 00:00:00' >= start_date"),
			'Date between 3' => array('OQL' => "SELECT UserRequest WHERE start_date >= '2017-01-01 00:00:00' AND '2017-01-01 00:00:00' >= start_date"),
			'Date between 4' => array('OQL' => "SELECT UserRequest WHERE start_date >= '2017-01-01 00:00:00' AND '2017-01-01 01:00:00' > start_date"),
			'Date between 5' => array('OQL' => "SELECT UserRequest WHERE start_date >= '2017-01-01 00:00:00' AND '2017-01-02 00:00:00' > start_date"),
			'Date between 6' => array('OQL' => "SELECT UserRequest WHERE start_date >= '2017-01-01' AND '2017-01-02' >= start_date"),
			'Date between 7' => array('OQL' => "SELECT CustomerContract WHERE ((start_date >= '2018-03-01') AND (start_date < '2018-04-01'))"),
			'Date =' => array('OQL' => "SELECT CustomerContract WHERE (start_date = '2018-03-01')"),
			'Date =2' => array('OQL' => "SELECT UserRequest WHERE (DATE_FORMAT(start_date, '%Y-%m-%d') = '2018-03-21')"),
			'Num between 1' => array('OQL' => "SELECT Server WHERE nb_u >= 0 AND 1 >= nb_u"),
			'Num ISNULL' => array('OQL' => "SELECT Server WHERE ISNULL(nb_u)"),
			'Hierarchical below' => array('OQL' => "SELECT Person AS P JOIN Organization AS Node ON P.org_id = Node.id JOIN Organization AS Root ON Node.parent_id BELOW Root.id WHERE Root.id=1"),
			'IP range' => array('OQL' => "SELECT DatacenterDevice AS dev WHERE INET_ATON(dev.managementip) > INET_ATON('10.22.32.224') AND INET_ATON(dev.managementip) < INET_ATON('10.22.32.255')"),
		);
	}
}

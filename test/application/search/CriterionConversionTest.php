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
		$aRes = CriterionToSearchForm::Convert($aCriterion, $aFields);
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
	 * @throws \OQLException
	 */
	function testOqlToForSearchToOql($sOQL)
	{
		$oSearchForm = new SearchForm();
		$oSearch = \DBSearch::FromOQL($sOQL);
		$aFields = $oSearchForm->GetFields(new \DBObjectSet($oSearch));
		$aCriterion = $oSearchForm->GetCriterion($oSearch, $aFields);
		$this->debug($sOQL);

		$aAndCriterion = $aCriterion['or'][0]['and'];

		$aNewCriterion = array();
		foreach($aAndCriterion as $aCriteria)
		{
			if (($aCriteria['widget'] == \AttributeDefinition::SEARCH_WIDGET_TYPE_STRING)
				|| ($aCriteria['widget'] == \AttributeDefinition::SEARCH_WIDGET_TYPE_DATE_TIME)
				|| ($aCriteria['widget'] == \AttributeDefinition::SEARCH_WIDGET_TYPE_ENUM))
			{
				unset($aCriteria['oql']);
				$aField = $aFields['zlist'][$aCriteria['ref']];
				$aCriteria['code'] = $aField['code'];
				$aCriteria['class'] = $aField['class'];
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
			array('OQL' => "SELECT Contact WHERE status = 'active'"),
			array('OQL' => "SELECT Contact WHERE status = 'active' AND name LIKE 'toto%'"),
			array('OQL' => "SELECT Contact WHERE status = 'active' AND org_id = 3"),
			array('OQL' => "SELECT Contact WHERE status IN ('active', 'inactive')"),
			array('OQL' => "SELECT Contact WHERE status NOT IN ('active')"),
			array('OQL' => "SELECT UserRequest WHERE DATE_SUB(NOW(), INTERVAL 14 DAY) < start_date"),
			array('OQL' => "SELECT UserRequest WHERE start_date > '2017-01-01 00:00:00' AND '2018-01-01 00:00:00' >= start_date"),
			array('OQL' => "SELECT UserRequest WHERE start_date > '2017-01-01 00:00:00' AND status = 'active' AND org_id = 3 AND '2018-01-01 00:00:00' >= start_date"),
			array('OQL' => "SELECT UserRequest WHERE start_date >= '2017-01-01 00:00:00' AND '2017-01-01 00:00:00' >= start_date"),
			array('OQL' => "SELECT UserRequest WHERE start_date >= '2017-01-01 00:00:00' AND '2017-01-01 01:00:00' > start_date"),
			array('OQL' => "SELECT UserRequest WHERE start_date >= '2017-01-01 00:00:00' AND '2017-01-02 00:00:00' > start_date"),
		);
	}
}

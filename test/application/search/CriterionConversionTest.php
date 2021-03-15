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
 * Time: 16:46
 */

namespace Combodo\iTop\Test\UnitTest\Application\Search;

use AttributeDate;
use AttributeDateTime;
use AttributeDefinition;
use Combodo\iTop\Application\Search\CriterionConversion\CriterionToOQL;
use Combodo\iTop\Application\Search\CriterionConversion\CriterionToSearchForm;
use Combodo\iTop\Application\Search\CriterionParser;
use Combodo\iTop\Application\Search\SearchForm;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use DBObjectSearch;
use DBObjectSet;
use DBSearch;
use Dict;

/**
 * @group itopRequestMgmt
 * @group itopServiceMgmt
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class CriterionConversionTest extends ItopDataTestCase
{
	const CREATE_TEST_ORG = true;

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
	 * @param $sClass
	 * @param $sJSONCriterion
	 * @param $sExpectedOQL
	 *
	 * @throws \Exception
	 */
	public function testToOql($sClass, $sJSONCriterion, $sExpectedOQL)
	{
		$oSearch = new DBObjectSearch($sClass);
		$sOql = CriterionToOQL::Convert(
			$oSearch, json_decode($sJSONCriterion, true)
		);

		$this->debug($sOql);
		$this->assertEquals($sExpectedOQL, $sOql);
	}

	public function ToOqlProvider()
	{
		return array(
			'>' => array(
				'UserRequest',
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
			'contains nothing' => array(
				'Contact',
				'{
                    "ref": "Contact.name",
                    "values": [
                        {
                            "value": "",
                            "label": ""
                        }
                    ],
                    "operator": "contains",
                    "oql": ""
                }',
				"1"
			),
			'contains a regular string' => array(
				'Contact',
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
			// See PR #170
			'contains 0 as a string' => array(
				'Contact',
				'{
                    "ref": "Contact.name",
                    "values": [
                        {
                            "value": "0",
                            "label": "0"
                        }
                    ],
                    "operator": "contains",
                    "oql": ""
                }',
				"(`Contact`.`name` LIKE '%0%')"
			),
			'starts_with nothing' => array(
				'Contact',
				'{
                    "ref": "Contact.name",
                    "values": [
                        {
                            "value": "",
                            "label": ""
                        }
                    ],
                    "operator": "starts_with",
                    "oql": ""
                }',
				"1"
			),
			'starts_with a regular string' => array(
				'Contact',
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
			'starts_with a 0 as a string' => array(
				'Contact',
				'{
                    "ref": "Contact.name",
                    "values": [
                        {
                            "value": "0",
                            "label": "0"
                        }
                    ],
                    "operator": "starts_with",
                    "oql": ""
                }',
				"(`Contact`.`name` LIKE '0%')"
			),
			'ends_with nothing' => array(
				'Contact',
				'{
                    "ref": "Contact.name",
                    "values": [
                        {
                            "value": "",
                            "label": ""
                        }
                    ],
                    "operator": "ends_with",
                    "oql": ""
                }',
				"1"
			),
			'ends_with a regular string' => array(
				'Contact',
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
			'ends_with 0 as a string' => array(
				'Contact',
				'{
                    "ref": "Contact.name",
                    "values": [
                        {
                            "value": "0",
                            "label": "0"
                        }
                    ],
                    "operator": "ends_with",
                    "oql": ""
                }',
				"(`Contact`.`name` LIKE '%0')"
			),
			'empty' => array(
				'Contact',
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
				'Contact',
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
	 * @throws \CoreException
	 * @throws \OQLException
	 */
	function testToSearchForm($aCriterion, $sExpectedOperator)
	{
		$oSearchForm = new SearchForm();
		/** @var \DBObjectSearch $oSearch */
		$oSearch = DBSearch::FromOQL("SELECT Contact");
		$aFields = $oSearchForm->GetFields(new DBObjectSet($oSearch));
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
	 * @param      $sOQL
	 *
	 * @param      $sExpectedOQL
	 *
	 * @param      $aExpectedCriterion
	 *
	 * @throws \DictExceptionUnknownLanguage
	 * @throws \MissingQueryArgument
	 * @throws \OQLException
	 * @throws \CoreException
	 */
	function testOqlToSearchToOql($sOQL, $sExpectedOQL, $aExpectedCriterion)
	{
		// For tests on tags
		$this->CreateTagData(TAG_CLASS, TAG_ATTCODE, 'tag1', 'First');
		$this->CreateTagData(TAG_CLASS, TAG_ATTCODE, 'tag2', 'Second');

		$this->OqlToSearchToOqlAltLanguage($sOQL, $sExpectedOQL, $aExpectedCriterion, "EN US");
	}

	function OqlProvider()
	{
		return array(
			'no criteria' => array(
				'OQL' => 'SELECT WebApplication',
				'ExpectedOQL' => "SELECT `WebApplication` FROM WebApplication AS `WebApplication` WHERE 1",
				'ExpectedCriterion' => array(),
			),
			'string starts' => array(
				'OQL' => "SELECT Contact WHERE name LIKE 'toto%'",
				'ExpectedOQL' => "SELECT `Contact` FROM Contact AS `Contact` WHERE (`Contact`.`name` LIKE 'toto%')",
				'ExpectedCriterion' => array(array('widget' => 'string', 'operator' => 'starts_with', 'values' => array(array('value' => 'toto')))),
			),
			'string ends' => array(
				'OQL' => "SELECT Contact WHERE name LIKE '%toto'",
				'ExpectedOQL' => "SELECT `Contact` FROM Contact AS `Contact` WHERE (`Contact`.`name` LIKE '%toto')",
				'ExpectedCriterion' => array(array('widget' => 'string', 'operator' => 'ends_with', 'values' => array(array('value' => 'toto')))),
			),
			'string contains 1' => array(
				'OQL' => "SELECT Contact WHERE name LIKE '%toto%'",
				'ExpectedOQL' => "SELECT `Contact` FROM Contact AS `Contact` WHERE (`Contact`.`name` LIKE '%toto%')",
				'ExpectedCriterion' => array(array('widget' => 'string', 'operator' => 'contains', 'values' => array(array('value' => 'toto')))),
			),
			'string contains 2' => array(
				'OQL' => "SELECT Person AS B WHERE B.name LIKE '%A%'",
				'ExpectedOQL' => "SELECT `B` FROM Person AS `B` WHERE (`B`.`name` LIKE '%A%')",
				'ExpectedCriterion' => array(array('widget' => 'string', 'operator' => 'contains', 'values' => array(array('value' => 'A')))),
			),
			'string NOT contains' => array(
				'OQL' => "SELECT Person AS B WHERE B.name NOT LIKE '%A%'",
				'ExpectedOQL' => "SELECT `B` FROM Person AS `B` WHERE (`B`.`name` NOT LIKE '%A%')",
				'ExpectedCriterion' => array(array('widget' => 'string', 'operator' => 'NOT LIKE', 'values' => array(array('value' => '%A%')))),
			),
			'string regexp' => array(
				'OQL' => "SELECT Server WHERE name REGEXP '^dbserver[0-9]+\\\\\\\\..+\\\\\\\\.[a-z]{2,3}$'",
				'ExpectedOQL' => "SELECT `Server` FROM Server AS `Server` WHERE (`Server`.`name` REGEXP '^dbserver[0-9]+\\\\\\\\..+\\\\\\\\.[a-z]{2,3}$')",
				'ExpectedCriterion' => array(array('widget' => 'string', 'operator' => 'REGEXP')),
			),
			'enum + key =' => array(
				'OQL' => "SELECT Contact WHERE status = 'active' AND org_id = 3",
				'ExpectedOQL' => "SELECT `Contact` FROM Contact AS `Contact` JOIN Organization AS `Organization` ON `Contact`.org_id = `Organization`.id JOIN Organization AS `Organization1` ON `Organization`.parent_id BELOW `Organization1`.id WHERE ((`Organization1`.`id` = '3') AND (`Contact`.`status` = 'active'))",
				'ExpectedCriterion' => array(array('widget' => 'hierarchical_key', 'operator' => 'IN'), array('widget' => 'enum', 'operator' => 'IN')),
			),
			'enum =' => array(
				'OQL' => "SELECT Contact WHERE status = 'active'",
				'ExpectedOQL' => "SELECT `Contact` FROM Contact AS `Contact` WHERE (`Contact`.`status` = 'active')",
				'ExpectedCriterion' => array(array('widget' => 'enum', 'operator' => 'IN', 'values' => array(array('value' => 'active')))),
			),
			'enum IN' => array(
				'OQL' => "SELECT Contact WHERE status IN ('active', 'inactive')",
				'ExpectedOQL' => "SELECT `Contact` FROM Contact AS `Contact` WHERE 1",
				'ExpectedCriterion' => array(array('widget' => 'enum', 'operator' => 'IN', 'values' => array(array('value' => 'active'), array('value' => 'inactive')))),
			),
			'enum NOT IN 1' => array(
				'OQL' => "SELECT Contact WHERE status NOT IN ('active')",
				'ExpectedOQL' => "SELECT `Contact` FROM Contact AS `Contact` WHERE (`Contact`.`status` = 'inactive')",
				'ExpectedCriterion' => array(array('widget' => 'enum', 'operator' => 'IN', 'values' => array(array('value' => 'inactive')))),
			),
			'enum NOT IN 2' => array(
				'OQL' => "SELECT Person AS p JOIN UserRequest AS u ON u.agent_id = p.id WHERE u.status != 'closed'",
				'ExpectedOQL' => "SELECT `p` FROM Person AS `p` JOIN UserRequest AS `u` ON `u`.agent_id = `p`.id WHERE (`u`.`status` != 'closed')",
				'ExpectedCriterion' => array(array('widget' => 'raw')),
			),
			'enum undefined 1' => array(
				'OQL' => "SELECT FunctionalCI WHERE ((business_criticity = 'high') OR ISNULL(business_criticity)) AND 1",
				'ExpectedOQL' => "SELECT `FunctionalCI` FROM FunctionalCI AS `FunctionalCI` WHERE (((`FunctionalCI`.`business_criticity` = 'high') OR ISNULL(`FunctionalCI`.`business_criticity`)) AND 1)",
				'ExpectedCriterion' => array(array('widget' => 'enum', 'has_undefined' => true, 'operator' => 'IN', 'values' => array(array('value' => 'high'), array('value' => 'null')))),
			),
			'enum undefined 2' => array(
				'OQL' => "SELECT FunctionalCI WHERE ((business_criticity IN ('high', 'medium')) OR ISNULL(business_criticity)) AND 1",
				'ExpectedOQL' => "SELECT `FunctionalCI` FROM FunctionalCI AS `FunctionalCI` WHERE (((`FunctionalCI`.`business_criticity` IN ('high', 'medium')) OR ISNULL(`FunctionalCI`.`business_criticity`)) AND 1)",
				'ExpectedCriterion' => array(array('widget' => 'enum', 'has_undefined' => true, 'operator' => 'IN', 'values' => array(array('value' => 'high'), array('value' => 'medium'), array('value' => 'null')))),
			),
			'enum undefined 3' => array(
				'OQL' => "SELECT FunctionalCI WHERE ISNULL(business_criticity)",
				'ExpectedOQL' => "SELECT `FunctionalCI` FROM FunctionalCI AS `FunctionalCI` WHERE ISNULL(`FunctionalCI`.`business_criticity`)",
				'ExpectedCriterion' => array(array('widget' => 'enum', 'has_undefined' => true, 'operator' => 'IN', 'values' => array(array('value' => 'null')))),
			),
			'key NOT IN' => array(
				'OQL' => "SELECT Contact WHERE org_id NOT IN ('1')",
				'ExpectedOQL' => "SELECT `Contact` FROM Contact AS `Contact` WHERE (`Contact`.`org_id` NOT IN ('1'))",
				'ExpectedCriterion' => array(array('widget' => 'raw', 'operator' => 'NOT IN')),
			),
			'key IN' => array(
				'OQL' => "SELECT Contact WHERE org_id IN ('1')",
				'ExpectedOQL' => "SELECT `Contact` FROM Contact AS `Contact` JOIN Organization AS `Organization` ON `Contact`.org_id = `Organization`.id JOIN Organization AS `Organization1` ON `Organization`.parent_id BELOW `Organization1`.id WHERE (`Organization1`.`id` = '1')",
				'ExpectedCriterion' => array(array('widget' => 'hierarchical_key', 'operator' => 'IN')),
			),
			'key IN 2' => array(
				'OQL' => "SELECT Contact WHERE org_id IN ('1', '999999')",
				'ExpectedOQL' => "SELECT `Contact` FROM Contact AS `Contact` JOIN Organization AS `Organization` ON `Contact`.org_id = `Organization`.id JOIN Organization AS `Organization1` ON `Organization`.parent_id BELOW `Organization1`.id WHERE (`Organization1`.`id` = '1')",
				'ExpectedCriterion' => array(array('widget' => 'hierarchical_key', 'operator' => 'IN')),
			),
			'key empty' => array(
				'OQL' => "SELECT Person WHERE location_id = '0'",
				'ExpectedOQL' => "SELECT `Person` FROM Person AS `Person` WHERE (`Person`.`location_id` = '0')",
				'ExpectedCriterion' => array(array('widget' => 'external_key', 'operator' => 'IN', 'values' => array(array('value' => '0')))),
			),
			'Double field' => array(
				'OQL' => "SELECT UserRequest AS u WHERE u.close_date > u.start_date",
				'ExpectedOQL' => "SELECT `u` FROM UserRequest AS `u` WHERE (`u`.`close_date` > `u`.`start_date`)",
				'ExpectedCriterion' => array(array('widget' => 'raw')),
			),
			'Num between 1' => array(
				'OQL' => "SELECT Server WHERE nb_u >= 0 AND 1 >= nb_u",
				'ExpectedOQL' => "SELECT `Server` FROM Server AS `Server` WHERE ((`Server`.`nb_u` >= '0') AND (`Server`.`nb_u` <= '1'))",
				'ExpectedCriterion' => array(array('widget' => 'numeric', 'operator' => 'between')),
			),
			'Num ISNULL' => array(
				'OQL' => "SELECT Server WHERE ISNULL(nb_u)",
				'ExpectedOQL' => "SELECT `Server` FROM Server AS `Server` WHERE ISNULL(`Server`.`nb_u`)",
				'ExpectedCriterion' => array(array('widget' => 'numeric', 'operator' => 'empty')),
			),
			'Hierarchical below 1' => array(
				'OQL' => "SELECT Person AS P JOIN Organization AS Node ON P.org_id = Node.id JOIN Organization AS Root ON Node.parent_id BELOW Root.id WHERE Root.id=1",
				'ExpectedOQL' => "SELECT `P` FROM Person AS `P` JOIN Organization AS `Node` ON `P`.org_id = `Node`.id JOIN Organization AS `Root` ON `Node`.parent_id BELOW `Root`.id WHERE (`Root`.`id` = '1')",
				'ExpectedCriterion' => array(array('widget' => 'hierarchical_key')),
			),
			'Hierarchical below 2' => array(
				'OQL' => "SELECT `Organization` FROM Organization AS `Organization` JOIN Organization AS `Organization1` ON `Organization`.parent_id = `Organization1`.id JOIN Organization AS `Organization11` ON `Organization1`.parent_id BELOW `Organization11`.id WHERE (((`Organization11`.`id` IN ('1', '2')) OR (`Organization`.`parent_id` = '0')) AND 1)",
				'ExpectedOQL' => "SELECT `Organization` FROM Organization AS `Organization` JOIN Organization AS `Organization1` ON `Organization`.parent_id = `Organization1`.id JOIN Organization AS `Organization11` ON `Organization1`.parent_id BELOW `Organization11`.id WHERE (((`Organization11`.`id` IN ('1', '2')) OR (`Organization`.`parent_id` = '0')) AND 1)",
				'ExpectedCriterion' => array(array('widget' => 'hierarchical_key')),
			),
			'IP range' => array(
				'OQL' => "SELECT DatacenterDevice AS dev WHERE INET_ATON(dev.managementip) > INET_ATON('10.22.32.224') AND INET_ATON(dev.managementip) < INET_ATON('10.22.32.255')",
				'ExpectedOQL' => "SELECT `dev` FROM DatacenterDevice AS `dev` WHERE ((INET_ATON(`dev`.`managementip`) < INET_ATON('10.22.32.255')) AND (INET_ATON(`dev`.`managementip`) > INET_ATON('10.22.32.224')))",
				'ExpectedCriterion' => array(array('widget' => 'raw')),
			),
			'TagSet Matches' => array(
				'OQL' => "SELECT ".TAG_CLASS." WHERE ".TAG_ATTCODE." MATCHES 'tag1'",
				'ExpectedOQL' => "SELECT `".TAG_CLASS."` FROM ".TAG_CLASS." AS `".TAG_CLASS."` WHERE `".TAG_CLASS."`.`".TAG_ATTCODE.'` MATCHES \'tag1 _\'',
				'ExpectedCriterion' => array(array('widget' => 'tag_set')),
			),
			'TagSet Matches2' => array(
				'OQL' => "SELECT ".TAG_CLASS." WHERE ".TAG_ATTCODE." MATCHES 'tag1 tag2'",
				'ExpectedOQL' => "SELECT `".TAG_CLASS."` FROM ".TAG_CLASS." AS `".TAG_CLASS."` WHERE `".TAG_CLASS."`.`".TAG_ATTCODE.'` MATCHES \'tag1 tag2 _\'',
				'ExpectedCriterion' => array(array('widget' => 'tag_set')),
			),
			'TagSet Undefined' => array(
				'OQL' => "SELECT ".TAG_CLASS." WHERE ".TAG_ATTCODE." = ''",
				'ExpectedOQL' => "SELECT `".TAG_CLASS."` FROM ".TAG_CLASS." AS `".TAG_CLASS."` WHERE (`".TAG_CLASS."`.`".TAG_ATTCODE."` = '')",
				'ExpectedCriterion' => array(array('widget' => 'tag_set')),
			),
			'TagSet Undefined and tag' => array(
				'OQL' => "SELECT ".TAG_CLASS." WHERE (((".TAG_ATTCODE." MATCHES 'tag1 tag2') OR (".TAG_ATTCODE." = '')) AND 1)",
				'ExpectedOQL' => "SELECT `".TAG_CLASS."` FROM ".TAG_CLASS." AS `".TAG_CLASS."` WHERE ((`".TAG_CLASS."`.`".TAG_ATTCODE.'` MATCHES \'tag1 tag2 _\' OR (`'.TAG_CLASS."`.`".TAG_ATTCODE."` = '')) AND 1)",
				'ExpectedCriterion' => array(array('widget' => 'tag_set')),
			),
			'TagSet equals' => array(
				'OQL' => "SELECT ".TAG_CLASS." WHERE ".TAG_ATTCODE." = 'tag1 tag2'",
				'ExpectedOQL' => "SELECT `".TAG_CLASS."` FROM ".TAG_CLASS." AS `".TAG_CLASS."` WHERE (`".TAG_CLASS."`.`".TAG_ATTCODE.'` MATCHES \'tag1 _\' AND `'.TAG_CLASS."`.`".TAG_ATTCODE.'` MATCHES \'tag2 _\')',
				'ExpectedCriterion' => array(array('widget' => 'tag_set')),
			),

		);
	}

	/**
	 * @dataProvider OqlProviderDates
	 *
	 * @param      $sOQL
	 *
	 * @param      $sExpectedOQL
	 *
	 * @param      $aExpectedCriterion
	 *
	 * @throws \DictExceptionUnknownLanguage
	 * @throws \MissingQueryArgument
	 * @throws \OQLException
	 * @throws \CoreException
	 */
    function testOqlToForSearchToOqlAltLanguageFR($sOQL, $sExpectedOQL, $aExpectedCriterion)
    {
    	\MetaModel::GetConfig()->Set('date_and_time_format', array('default' => array('date' => 'Y-m-d', 'time' => 'H:i:s', 'date_time' => '$date $time')));
	    $this->OqlToSearchToOqlAltLanguage($sOQL, $sExpectedOQL, $aExpectedCriterion, "FR FR");
    }


	/**
	 * @dataProvider OqlProviderDates
	 *
	 * @param      $sOQL
	 *
	 * @param      $sExpectedOQL
	 *
	 * @param      $aExpectedCriterion
	 *
	 * @throws \DictExceptionUnknownLanguage
	 * @throws \MissingQueryArgument
	 * @throws \OQLException
	 * @throws \CoreException
	 */
    function testOqlToForSearchToOqlAltLanguageEN($sOQL, $sExpectedOQL, $aExpectedCriterion)
    {
	    \MetaModel::GetConfig()->Set('date_and_time_format', array('default' => array('date' => 'Y-m-d', 'time' => 'H:i:s', 'date_time' => '$date $time')));
        $this->OqlToSearchToOqlAltLanguage($sOQL, $sExpectedOQL, $aExpectedCriterion, "EN US");
    }

    function OqlProviderDates()
    {
        return array(

            'Date relative 1' => array(
                'OQL' => "SELECT UserRequest WHERE DATE_SUB(NOW(), INTERVAL 14 DAY) < start_date",
                'ExpectedOQL' => "SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE (DATE_SUB(NOW(), INTERVAL 14 DAY) < `UserRequest`.`start_date`)",
                'ExpectedCriterion' => array(array('widget' => 'raw')),
            ),
            'Date relative 2' => array(
                'OQL' => "SELECT Contract AS c WHERE c.end_date > NOW() AND c.end_date < DATE_ADD(NOW(), INTERVAL 30 DAY)",
                'ExpectedOQL' => "SELECT `c` FROM Contract AS `c` WHERE ((`c`.`end_date` < DATE_ADD(NOW(), INTERVAL 30 DAY)) AND (`c`.`end_date` > NOW()))",
                'ExpectedCriterion' => array(array('widget' => 'raw'), array('widget' => 'raw')),
            ),
            'Date relative 3' => array(
                'OQL' => "SELECT UserRequest AS u WHERE u.close_date > DATE_ADD(u.start_date, INTERVAL 8 HOUR)",
                'ExpectedOQL' => "SELECT `u` FROM UserRequest AS `u` WHERE (`u`.`close_date` > DATE_ADD(`u`.`start_date`, INTERVAL 8 HOUR))",
                'ExpectedCriterion' => array(),
            ),
            'Date relative 4' => array(
                'OQL' => "SELECT UserRequest AS u WHERE u.start_date < DATE_SUB(NOW(), INTERVAL 60 MINUTE) AND u.status = 'new'",
                'ExpectedOQL' => "SELECT `u` FROM UserRequest AS `u` WHERE ((`u`.`start_date` < DATE_SUB(NOW(), INTERVAL 60 MINUTE)) AND (`u`.`status` = 'new'))",
                'ExpectedCriterion' => array(array('widget' => 'raw')),
            ),
            'Date between 1' => array(
                'OQL' => "SELECT UserRequest WHERE start_date > '2017-01-01 00:00:00' AND '2018-01-01 00:00:00' >= start_date",
                'ExpectedOQL' => "SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE ((`UserRequest`.`start_date` >= '2017-01-01 00:00:01') AND (`UserRequest`.`start_date` <= '2018-01-01 00:00:00'))",
                'ExpectedCriterion' => array(array('widget' => 'date_time', 'operator' => 'between_dates')),
            ),
            'Date between 2' => array(
                'OQL' => "SELECT UserRequest WHERE start_date > '2017-01-01 00:00:00' AND status = 'active' AND org_id = 3 AND '2018-01-01 00:00:00' >= start_date",
                'ExpectedOQL' => "SELECT `UserRequest` FROM UserRequest AS `UserRequest` JOIN Organization AS `Organization` ON `UserRequest`.org_id = `Organization`.id JOIN Organization AS `Organization1` ON `Organization`.parent_id BELOW `Organization1`.id WHERE ((((`Organization1`.`id` = '3') AND (`UserRequest`.`start_date` >= '2017-01-01 00:00:01')) AND (`UserRequest`.`start_date` <= '2018-01-01 00:00:00')) AND (`UserRequest`.`status` = 'active'))",
                'ExpectedCriterion' => array(array('widget' => 'hierarchical_key', 'operator' => 'IN'), array('widget' => 'date_time', 'operator' => 'between_dates'), array('widget' => 'enum', 'operator' => 'IN')),
            ),
            'Date between 3' => array(
                'OQL' => "SELECT UserRequest WHERE start_date >= '2017-01-01 00:00:00' AND '2017-01-01 00:00:00' >= start_date",
                'ExpectedOQL' => "SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE ((`UserRequest`.`start_date` >= '2017-01-01 00:00:00') AND (`UserRequest`.`start_date` <= '2017-01-01 00:00:00'))",
                'ExpectedCriterion' => array(array('widget' => 'date_time', 'operator' => 'between_dates')),
            ),
            'Date between 4' => array(
                'OQL' => "SELECT UserRequest WHERE start_date >= '2017-01-01 00:00:00' AND '2017-01-01 01:00:00' > start_date",
                'ExpectedOQL' => "SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE ((`UserRequest`.`start_date` >= '2017-01-01 00:00:00') AND (`UserRequest`.`start_date` <= '2017-01-01 00:59:59'))",
                'ExpectedCriterion' => array(array('widget' => 'date_time', 'operator' => 'between_dates')),
            ),
            'Date between 5' => array(
                'OQL' => "SELECT UserRequest WHERE start_date >= '2017-01-01 00:00:00' AND '2017-01-02 00:00:00' > start_date",
                'ExpectedOQL' => "SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE ((`UserRequest`.`start_date` >= '2017-01-01 00:00:00') AND (`UserRequest`.`start_date` <= '2017-01-01 23:59:59'))",
                'ExpectedCriterion' => array(array('widget' => 'date_time', 'operator' => 'between_dates')),
            ),
            'Date between 6' => array(
                'OQL' => "SELECT UserRequest WHERE start_date >= '2017-01-01' AND '2017-01-02' >= start_date",
                'ExpectedOQL' => "SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE ((`UserRequest`.`start_date` >= '2017-01-01 00:00:00') AND (`UserRequest`.`start_date` <= '2017-01-02 00:00:00'))",
                'ExpectedCriterion' => array(array('widget' => 'date_time', 'operator' => 'between_dates')),
            ),
            'Date between 7' => array(
                'OQL' => "SELECT CustomerContract WHERE ((start_date >= '2018-03-01') AND (start_date < '2018-04-01'))",
                'ExpectedOQL' => "SELECT `CustomerContract` FROM CustomerContract AS `CustomerContract` WHERE ((`CustomerContract`.`start_date` >= '2018-03-01') AND (`CustomerContract`.`start_date` <= '2018-03-31'))",
                'ExpectedCriterion' => array(array('widget' => 'date', 'operator' => 'between_dates')),
            ),
            'Date =' => array(
                'OQL' => "SELECT CustomerContract WHERE (start_date = '2018-03-01')",
                'ExpectedOQL' => "SELECT `CustomerContract` FROM CustomerContract AS `CustomerContract` WHERE ((`CustomerContract`.`start_date` >= '2018-03-01') AND (`CustomerContract`.`start_date` <= '2018-03-01'))",
                'ExpectedCriterion' => array(array('widget' => 'date', 'operator' => 'between_dates')),
            ),
            'Date =2' => array(
                'OQL' => "SELECT UserRequest WHERE (DATE_FORMAT(start_date, '%Y-%m-%d') = '2018-03-21')",
                'ExpectedOQL' => "SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE ((`UserRequest`.`start_date` >= '2018-03-21 00:00:00') AND (`UserRequest`.`start_date` <= '2018-03-21 23:59:59'))",
                'ExpectedCriterion' => array(array('widget' => 'date_time', 'operator' => 'between_dates')),
            ),
            'Date =3' => array(
                'OQL' => "SELECT UserRequest WHERE (DATE_FORMAT(`UserRequest`.`start_date`, '%w') = '4')",
                'ExpectedOQL' => "SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE (DATE_FORMAT(`UserRequest`.`start_date`, '%w') = '4')",
                'ExpectedCriterion' => array(array('widget' => 'raw')),
            ),
        );
    }

	/**
	 *
	 * @param      $sOQL
	 *
	 * @param      $sExpectedOQL
	 *
	 * @param      $aExpectedCriterion
	 *
	 * @param      $sLanguageCode
	 *
	 * @throws \CoreException
	 * @throws \DictExceptionUnknownLanguage
	 * @throws \MissingQueryArgument
	 * @throws \OQLException
	 */
    function OqlToSearchToOqlAltLanguage($sOQL, $sExpectedOQL, $aExpectedCriterion, $sLanguageCode )
    {
        $this->debug($sOQL);


        Dict::SetUserLanguage($sLanguageCode);


        $oSearchForm = new SearchForm();
        $oSearch = DBSearch::FromOQL($sOQL);
        $aFields = $oSearchForm->GetFields(new DBObjectSet($oSearch));
        /** @var \DBObjectSearch $oSearch */
        $aCriterion = $oSearchForm->GetCriterion($oSearch, $aFields);

        $aAndCriterion = $aCriterion['or'][0]['and'];

        $aNewCriterion = array();
        foreach($aAndCriterion as $aCriteria)
        {
            if ($aCriteria['widget'] != AttributeDefinition::SEARCH_WIDGET_TYPE_RAW)
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

            if ($aCriteria['widget'] == AttributeDefinition::SEARCH_WIDGET_TYPE_DATE_TIME || $aCriteria['widget'] == AttributeDefinition::SEARCH_WIDGET_TYPE_DATE)
            {
                $sAttributeClass = ($aCriteria['widget'] == AttributeDefinition::SEARCH_WIDGET_TYPE_DATE_TIME) ? AttributeDateTime::class : AttributeDate::class;

	            /** @var \AttributeDateTime $sAttributeClass */
	            /** @var \DateTimeFormat $oFormat */
	            $oFormat = $sAttributeClass::GetFormat();

                foreach($aCriteria['values'] as $i => $aValue)
                {
                    if (!empty($aValue['value'])) {
                        $aCriteria['values'][$i]['value'] = $oFormat->Format($aValue['value']);
                    }
                }
            }

            $aNewCriterion[] = $aCriteria;
        }
        $this->debug($aNewCriterion);

        $this->assertFalse($this->array_diff_assoc_recursive($aExpectedCriterion, $aNewCriterion), 'Criterion array contains critical parts');

        $aCriterion['or'][0]['and'] = $aNewCriterion;

        $oSearch->ResetCondition();
        $oFilter = CriterionParser::Parse($oSearch->ToOQL(), $aCriterion);

        $sResultOQL = $oFilter->ToOQL();
        $this->debug($sResultOQL);

        $this->assertEquals($sExpectedOQL, $sResultOQL);
    }


	function array_diff_assoc_recursive($array1, $array2)
	{
		foreach($array1 as $key => $value)
		{
			if (is_array($value))
			{
				if (!isset($array2[$key]))
				{
					$difference[$key] = $value;
				}
				elseif (!is_array($array2[$key]))
				{
					$difference[$key] = $value;
				}
				else
				{
					$new_diff = $this->array_diff_assoc_recursive($value, $array2[$key]);
					if ($new_diff !== false)
					{
						$difference[$key] = $new_diff;
					}
				}
			}
			elseif (!array_key_exists($key, $array2) || $array2[$key] != $value)
			{
				$difference[$key] = $value;
			}
		}

		return !isset($difference) ? false : $difference;
	}
}

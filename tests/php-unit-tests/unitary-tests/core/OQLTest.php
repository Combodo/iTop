<?php

/**
 * Created by PhpStorm.
 * User: Eric
 * Date: 31/08/2018
 * Time: 17:03
 */

namespace Combodo\iTop\Test\UnitTest\Core;

use CMDBSource;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use DBObjectSearch;
use DBObjectSet;
use DBSearch;
use Exception;
use MetaModel;
use OqlInterpreter;
use QueryBuilderContext;
use SQLObjectQueryBuilder;
use UserRights;
use utils;

class OQLTest extends ItopDataTestCase
{
	/**
	 * @doesNotPerformAssertions
	 *
	 * @throws \ConfigException
	 * @throws \CoreException
	 */
	public function testOQLSetup()
	{
		$oConfig = utils::GetConfig();
		$oConfig->Set('apc_cache.enabled', false, 'test');
		$oConfig->Set('query_cache_enabled', false, 'test');
		$oConfig->Set('expression_cache_enabled', false, 'test');

		$sConfigFile = $oConfig->GetLoadedFile();
		@chmod($sConfigFile, 0770);
		$oConfig->WriteToFile();
		@chmod($sConfigFile, 0444);
	}

	/**
	 * @dataProvider NestedQueryProvider
	 * @depends testOQLSetup
	 *
	 * @param $sQuery
	 *
	 * @throws \OQLException
	 */
	public function testGoodNestedQueryQueryParser($sQuery)
	{
		$this->debug($sQuery);
		$oOql = new OqlInterpreter($sQuery);
		$oQuery = $oOql->ParseQuery();
		static::assertInstanceOf('OqlQuery', $oQuery);
	}

	public function NestedQueryProvider()
	{
		return [
			["SELECT User AS U JOIN Person AS P ON U.contactid = P.id WHERE U.status='enabled' AND U.id NOT IN (SELECT User AS U JOIN Person AS P ON U.contactid=P.id JOIN URP_UserOrg AS L ON L.userid = U.id WHERE U.status='enabled' AND L.allowed_org_id = P.org_id UNION SELECT User AS U WHERE U.status='enabled' AND U.id NOT IN ( SELECT User AS U JOIN URP_UserOrg AS L ON L.userid = U.id WHERE U.status='enabled'))"],
			['SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE  `UserRequest`.org_id IN (SELECT `Organization` FROM Organization AS `Organization` JOIN Organization AS `Organization1` ON `Organization`.parent_id BELOW `Organization1`.id WHERE (`Organization1`.`id` = \'3\'))'],
			['SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE (`UserRequest`.`org_id` IN (SELECT `Organization` FROM Organization AS `Organization` WHERE `Organization`.`id`=`UserRequest`.`org_id`))'],
			["SELECT UserRequest AS Ur WHERE Ur.id NOT IN (SELECT UserRequest AS Ur JOIN lnkFunctionalCIToTicket AS lnk ON lnk.ticket_id = Ur.id)"],
			["SELECT Ticket AS T WHERE T. finalclass IN ('userrequest' , 'change') AND T.id  NOT IN (SELECT UserRequest AS Ur JOIN lnkFunctionalCIToTicket AS lnk ON lnk.ticket_id = Ur.id  UNION SELECT Change AS C JOIN lnkFunctionalCIToTicket AS lnk ON lnk.ticket_id = C.id)"],
			["SELECT PhysicalDevice WHERE status='production' AND id NOT IN (SELECT PhysicalDevice AS p JOIN lnkFunctionalCIToProviderContract AS l ON l.functionalci_id=p.id)"],
			["SELECT Team WHERE id NOT IN (SELECT Team AS t JOIN lnkPersonToTeam AS l ON l.team_id=t.id WHERE 1)"],
		];
	}

	/**
	 * @dataProvider GoodQueryProvider
	 * @depends testOQLSetup
	 *
	 * @param $sQuery
	 *
	 * @throws \OQLException
	 */
	public function testGoodQueryParser($sQuery)
	{
		$this->debug($sQuery);
		$oOql = new OqlInterpreter($sQuery);
		$oQuery = $oOql->ParseQuery();
		static::assertInstanceOf('OqlQuery', $oQuery);
	}

	public function GoodQueryProvider()
	{
		return [
			['SELECT toto'],
			['SELECT toto WHERE toto.a = 1'],
			['SELECT toto WHERE toto.a = -1'],
			['SELECT toto WHERE toto.a = (1-1)'],
			['SELECT toto WHERE toto.a = (-1+3)'],
			['SELECT toto WHERE toto.a = (3+-1)'],
			['SELECT toto WHERE toto.a = (3--1)'],
			['SELECT toto WHERE toto.a = 0xC'],
			['SELECT toto WHERE toto.a = \'AXDVFS0xCZ32\''],
			['SELECT toto WHERE toto.a = :myparameter'],
			['SELECT toto WHERE toto.a IN (:param1)'],
			['SELECT toto WHERE toto.a IN (:param1, :param2)'],
			['SELECT toto WHERE toto.a=1'],
			['SELECT toto WHERE toto.a = "1"'],
			['SELECT toto WHERE toto.a & 1'],
			['SELECT toto WHERE toto.a | 1'],
			['SELECT toto WHERE toto.a ^ 1'],
			['SELECT toto WHERE toto.a << 1'],
			['SELECT toto WHERE toto.a >> 1'],
			['SELECT toto WHERE toto.a NOT LIKE "That\'s it"'],
			['SELECT toto WHERE toto.a NOT LIKE "That\'s \\"it\\""'],
			['SELECT toto WHERE toto.a NOT LIKE \'That"s it\''],
			['SELECT toto WHERE toto.a NOT LIKE \'That\\\'s it\''],
			['SELECT toto WHERE toto.a NOT LIKE "blah \\\\ truc"'],
			['SELECT toto WHERE toto.a NOT LIKE \'blah \\\\ truc\''],
			['SELECT toto WHERE toto.a NOT LIKE "\\\\"'],
			['SELECT toto WHERE toto.a NOT LIKE "\\""'],
			['SELECT toto WHERE toto.a NOT LIKE "\\"\\\\"'],
			['SELECT toto WHERE toto.a NOT LIKE "\\\\\\""'],
			['SELECT toto WHERE toto.a NOT LIKE ""'],
			['SELECT toto WHERE toto.a NOT LIKE "blah" AND toto.b LIKE "foo"'],
			['SELECT toto WHERE toto.a = 1 AND toto.b LIKE "x" AND toto.f >= 12345'],
			['SELECT Device JOIN Site ON Device.site = Site.id'],
			['SELECT Device JOIN Site ON Device.site = Site.id JOIN Country ON Site.location = Country.id'],
			['SELECT UserRightsMatrixClassGrant WHERE UserRightsMatrixClassGrant.class = \'lnkContactRealObject\' AND UserRightsMatrixClassGrant.action = \'modify\' AND UserRightsMatrixClassGrant.login = \'Denis\''],
			['SELECT A WHERE A.col1 = \'lit1\' AND A.col2 = \'lit2\' AND A.col3 = \'lit3\''],
			['SELECT A JOIN B ON A.myB = B.id WHERE (A.col1 = 123 AND B.col1 = \'aa\') OR (A.col3 = \'zzz\' AND B.col4 > 100)'],
			['SELECT A JOIN B ON A.myB = B.id WHERE (A.col1 = B.col2 AND B.col1 = A.col2) OR (A.col3 = \'\' AND B.col4 > 100)'],
			['SELECT A JOIN B ON A.myB = B.id WHERE A.col1 + B.col2 * B.col1 = A.col2'],
			['SELECT A JOIN B ON A.myB = B.id WHERE A.col1 + (B.col2 * B.col1) = A.col2'],
			['SELECT A JOIN B ON A.myB = B.id WHERE (A.col1 + B.col2) * B.col1 = A.col2'],
			['SELECT A JOIN B ON A.myB = B.id WHERE (A.col1 & B.col2) = A.col2'],
			['SELECT Device AS D_ JOIN Site AS S_ ON D_.site = S_.id WHERE S_.country = "Francia"'],
			['SELECT A FROM A'],
			['SELECT A JOIN B ON A.myB = B.id WHERE A.col1 = 2'],
			['SELECT A FROM A JOIN B ON A.myB = B.id WHERE A.col1 = 2'],
			['SELECT B FROM A JOIN B ON A.myB = B.id WHERE A.col1 = 2'],
			['SELECT A,B FROM A JOIN B ON A.myB = B.id WHERE A.col1 = 2'],
			['SELECT A, B FROM A JOIN B ON A.myB = B.id WHERE A.col1 = 2'],
			['SELECT B,A FROM A JOIN B ON A.myB = B.id WHERE A.col1 = 2'],
			['SELECT  A, B,C FROM A JOIN B ON A.myB = B.id'],
			['SELECT C FROM A JOIN B ON A.myB = B.id WHERE A.col1 = 2'],
			['SELECT A JOIN B ON A.myB BELOW B.id WHERE A.col1 = 2'],
			['SELECT A JOIN B ON B.myA BELOW A.id WHERE A.col1 = 2'],
			['SELECT A JOIN B ON A.myB = B.id JOIN C ON C.parent_id BELOW B.id WHERE A.col1 = 2 AND B.id = 3'],
			['SELECT A JOIN B ON A.myB = B.id JOIN C ON C.parent_id BELOW STRICT B.id WHERE A.col1 = 2 AND B.id = 3'],
			['SELECT A JOIN B ON A.myB = B.id JOIN C ON C.parent_id NOT BELOW B.id WHERE A.col1 = 2 AND B.id = 3'],
			['SELECT A JOIN B ON A.myB = B.id JOIN C ON C.parent_id NOT BELOW STRICT B.id WHERE A.col1 = 2 AND B.id = 3'],
			['SELECT A UNION SELECT B'],
			['SELECT A WHERE A.b = "sdf" UNION SELECT B WHERE B.a = "sfde"'],
			['SELECT A UNION SELECT B UNION SELECT C'],
			['SELECT A UNION SELECT B UNION SELECT C UNION SELECT D'],
			['SELECT A JOIN B ON A.myB = B.id JOIN C ON C.parent_id NOT BELOW B.id WHERE A.col1 = 2 AND B.id = 3 UNION SELECT Device JOIN Site ON Device.site = Site.id JOIN Country ON Site.location = Country.id'],
			['SELECT Person AS B WHERE B.name LIKE \'%A%\''],
			['SELECT Server WHERE name REGEXP \'dbserver[0-9]+\''],
			['SELECT Server WHERE name REGEXP \'^dbserver[0-9]+\\\\..+\\\\.[a-z]{2,3}$\''],
			['SELECT Change AS ch WHERE ch.start_date >= \'2009-12-31\' AND ch.end_date <= \'2010-01-01\''],
			['SELECT DatacenterDevice AS dev WHERE INET_ATON(dev.managementip) > INET_ATON(\'10.22.32.224\') AND INET_ATON(dev.managementip) < INET_ATON(\'10.22.32.255\')'],
			['SELECT Person AS P JOIN Organization AS Node ON P.org_id = Node.id JOIN Organization AS Root ON Node.parent_id BELOW Root.id WHERE Root.id=1'],
			['SELECT PhysicalInterface AS if JOIN DatacenterDevice AS dev ON if.connectableci_id = dev.id WHERE dev.status = \'production\' AND dev.organization_name = \'Demo\''],
			['SELECT Ticket AS t WHERE t.agent_id = :current_contact_id'],
			['SELECT Person AS p JOIN UserRequest AS u ON u.agent_id = p.id WHERE u.status != \'closed\''],
			['SELECT Contract AS c WHERE c.end_date > NOW() AND c.end_date < DATE_ADD(NOW(), INTERVAL 30 DAY)'],
			['SELECT UserRequest AS u WHERE u.start_date < DATE_SUB(NOW(), INTERVAL 60 MINUTE) AND u.status = \'new\''],
			['SELECT UserRequest AS u WHERE u.close_date > DATE_ADD(u.start_date, INTERVAL 8 HOUR)'],
			['SELECT Ticket WHERE tagfield MATCHES \'salad\''],
		];
	}

	/**
	 * @dataProvider BadQueryProvider
	 * @depends testOQLSetup
	 *
	 * @param $sQuery
	 * @param $sExpectedExceptionClass
	 *
	 */
	public function testBadQueryParser($sQuery, $sExpectedExceptionClass)
	{
		$this->debug($sQuery);
		$oOql = new OqlInterpreter($sQuery);
		$sExceptionClass = '';
		try {
			$oOql->ParseQuery();
		} catch (Exception $e) {
			$sExceptionClass = get_class($e);
		}

		static::assertEquals($sExpectedExceptionClass, $sExceptionClass);
	}

	public function BadQueryProvider()
	{
		return [
			['SELECT toto WHERE toto.a = (3++1)', 'OQLParserSyntaxErrorException'],
			['SELECT toto WHHHERE toto.a = "1"', 'OQLParserSyntaxErrorException'],
			['SELECT toto WHERE toto.a == "1"', 'OQLParserSyntaxErrorException'],
			['SELECT toto WHERE toto.a % 1', 'Exception'],
			['SELECT toto WHERE toto.a like \'arg\'', 'OQLParserSyntaxErrorException'],
			['SELECT toto WHERE toto.a NOT LIKE "That\'s "it""', 'OQLParserSyntaxErrorException'],
			['SELECT toto WHERE toto.a NOT LIKE \'That\'s it\'', 'OQLParserSyntaxErrorException'],
			['SELECT toto WHERE toto.a NOT LIKE "blah \\ truc"', 'Exception'],
			['SELECT toto WHERE toto.a NOT LIKE \'blah \\ truc\'', 'Exception'],
			['SELECT A JOIN B ON A.myB = B.id JOIN C ON C.parent_id = B.id WHERE A.col1 BELOW 2 AND B.id = 3', 'OQLParserSyntaxErrorException'],
		];
	}

	/**
	 * Needs actual datamodel
	 * @depends testOQLSetup
	 *
	 * @dataProvider QueryNormalizationProvider
	 *
	 * @param $sQuery
	 * @param $sExpectedExceptionClass
	 *
	 */
	public function testQueryNormalization($sQuery, $sExpectedExceptionClass)
	{
		$this->debug($sQuery);
		$sExceptionClass = '';
		try {
			$oSearch = DBObjectSearch::FromOQL($sQuery);
			static::assertInstanceOf('DBObjectSearch', $oSearch);
		} catch (Exception $e) {
			$sExceptionClass = get_class($e);
		}

		static::assertEquals($sExpectedExceptionClass, $sExceptionClass);
	}

	public function QueryNormalizationProvider()
	{
		return [
		   ['SELECT Contact', ''],
		   ['SELECT Contact WHERE nom_de_famille = "foo"', 'OqlNormalizeException'],
		   ['SELECT Contact AS c WHERE name = "foo"', ''],
		   ['SELECT Contact AS c WHERE nom_de_famille = "foo"', 'OqlNormalizeException'],
		   ['SELECT Contact AS c WHERE c.name = "foo"', ''],
		   ['SELECT Contact AS c WHERE Contact.name = "foo"', 'OqlNormalizeException'],
		   ['SELECT Contact AS c WHERE x.name = "foo"', 'OqlNormalizeException'],

		   ['SELECT Organization AS child JOIN Organization AS root ON child.parent_id BELOW root.id', ''],
		   ['SELECT Organization AS root JOIN Organization AS child ON child.parent_id BELOW root.id', ''],

		   ['SELECT RelationProfessionnelle', 'UnknownClassOqlException'],
		   ['SELECT RelationProfessionnelle AS c WHERE name = "foo"', 'UnknownClassOqlException'],

			// The first query is the base query altered only in one place in the subsequent queries
		   ['SELECT Person AS p JOIN lnkPersonToTeam AS lnk ON lnk.person_id = p.id WHERE p.name LIKE "foo"', ''],
		   ['SELECT Person AS p JOIN lnkXXXXXXXXXXXX AS lnk ON lnk.person_id = p.id WHERE p.name LIKE "foo"', 'UnknownClassOqlException'],
		   ['SELECT Person AS p JOIN lnkPersonToTeam AS lnk ON   p.person_id = p.id WHERE p.name LIKE "foo"', 'OqlNormalizeException'],
		   ['SELECT Person AS p JOIN lnkPersonToTeam AS lnk ON     person_id = p.id WHERE p.name LIKE "foo"', 'OqlNormalizeException'],
		   ['SELECT Person AS p JOIN lnkPersonToTeam AS lnk ON lnk.person_id =   id WHERE p.name LIKE "foo"', 'OqlNormalizeException'],
		   ['SELECT Person AS p JOIN lnkPersonToTeam AS lnk ON lnk.role      = p.id WHERE p.name LIKE "foo"', 'OqlNormalizeException'],
		   ['SELECT Person AS p JOIN lnkPersonToTeam AS lnk ON lnk.team_id   = p.id WHERE p.name LIKE "foo"', 'OqlNormalizeException'],
		   ['SELECT Person AS p JOIN lnkPersonToTeam AS lnk ON lnk.person_id BELOW p.id WHERE p.name LIKE "bar"', ''],
		   ['SELECT Person AS p JOIN lnkPersonToTeam AS lnk ON lnk.person_id = p.org_id WHERE p.name LIKE "foo"', 'OqlNormalizeException'],
		   ['SELECT Person AS p JOIN lnkPersonToTeam AS lnk ON p.id = lnk.person_id WHERE p.name LIKE "foo"', 'OqlNormalizeException'], // inverted the JOIN spec
		   ['SELECT Person AS p JOIN lnkPersonToTeam AS lnk ON lnk.person_id = p.id WHERE   name LIKE "foo"', ''],
		   ['SELECT Person AS p JOIN lnkPersonToTeam AS lnk ON lnk.person_id = p.id WHERE x.name LIKE "foo"', 'OqlNormalizeException'],
		   ['SELECT Person AS p JOIN lnkPersonToTeam AS lnk ON lnk.person_id = p.id WHERE p.eman LIKE "foo"', 'OqlNormalizeException'],
		   ['SELECT Person AS p JOIN lnkPersonToTeam AS lnk ON lnk.person_id = p.id WHERE   eman LIKE "foo"', 'OqlNormalizeException'],
		   ['SELECT Person AS p JOIN lnkPersonToTeam AS lnk ON lnk.person_id = p.id WHERE id = 1', 'OqlNormalizeException'],
		   ['SELECT Person AS p JOIN lnkPersonToTeam AS lnk ON p.id = lnk.person_id WHERE p.name LIKE "foo"', 'OqlNormalizeException'],

		   ['SELECT Person AS p JOIN Organization AS o ON p.org_id = o.id WHERE p.name LIKE "foo" AND o.name LIKE "land"', ''],
		   ['SELECT Person AS p JOIN Organization AS o ON p.location_id = o.id WHERE p.name LIKE "foo" AND o.name LIKE "land"', 'OqlNormalizeException'],
		   ['SELECT Person AS p JOIN Organization AS o ON p.name = o.id WHERE p.name LIKE "foo" AND o.name LIKE "land"', 'OqlNormalizeException'],

		   ['SELECT Person AS p JOIN Organization AS o ON      p.org_id = o.id JOIN Person AS p ON      p.org_id = o.id', 'OqlNormalizeException'],
		   ['SELECT Person      JOIN Organization AS o ON Person.org_id = o.id JOIN Person      ON Person.org_id = o.id', 'OqlNormalizeException'],

		   ['SELECT Person AS p JOIN Location AS l ON p.location_id = l.id', ''],
		   ['SELECT Person AS p JOIN Location AS l ON p.location_id BELOW l.id', 'OqlNormalizeException'],

		   ['SELECT Person FROM Person JOIN Location ON Person.location_id = Location.id', ''],
		   ['SELECT p FROM Person AS p JOIN Location AS l ON p.location_id = l.id', ''],
		   ['SELECT l FROM Person AS p JOIN Location AS l ON p.location_id = l.id', ''],
		   ['SELECT l, p FROM Person AS p JOIN Location AS l ON p.location_id = l.id', ''],
		   ['SELECT p, l FROM Person AS p JOIN Location AS l ON p.location_id = l.id', ''],
		   ['SELECT foo FROM Person AS p JOIN Location AS l ON p.location_id = l.id', 'OqlNormalizeException'],
		   ['SELECT p, foo FROM Person AS p JOIN Location AS l ON p.location_id = l.id', 'OqlNormalizeException'],

			// Joins based on AttributeObjectKey
			//
		   ['SELECT Attachment AS a JOIN UserRequest AS r ON a.item_id = r.id', ''],
		   ['SELECT UserRequest AS r JOIN Attachment AS a ON a.item_id = r.id', ''],
		];
	}

	/**
	 * @depends testOQLSetup
	 * @dataProvider OQLIntersectProvider
	 *
	 * Needs specific datamodel from unit-test-specific module
	 * for lnkGRTypeToServiceSubcategory and GRType classes
	 *
	 * @throws \CoreException
	 * @throws \OQLException
	 */
	public function testOQLIntersect($sOQL1, $sOQL2, $sOQLIntersect)
	{
		// Check that legacy mode is not set
		$this->assertFalse(utils::GetConfig()->Get('apc_cache.enabled'));
		$this->assertFalse(utils::GetConfig()->Get('query_cache_enabled'));
		$this->assertFalse(utils::GetConfig()->Get('expression_cache_enabled'));

		$oSearch1 = DBSearch::FromOQL($sOQL1);
		$oSearch2 = DBSearch::FromOQL($sOQL2);
		$oSearchI = $oSearch1->Intersect($oSearch2);

		$sOQLResult = $oSearchI->ToOQL();
		//$this->debug($sOQLResult);

		self::assertEquals($sOQLIntersect, $sOQLResult);
	}

	public function OQLIntersectProvider()
	{
		return [
			// Wrong result:
			/*
			SELECT `SSC`
				FROM ServiceSubcategory AS `SSC`
				JOIN Service AS `S` ON `SSC`.service_id = `S`.id
				JOIN lnkCustomerContractToService AS `l1` ON `l1`.service_id = `S`.id
				JOIN CustomerContract AS `cc` ON `l1`.customercontract_id = `cc`.id
				JOIN lnkGRTypeToServiceSubcategory AS `l1` ON `l1`.servicesubcategory_id = `SSC`.id
				JOIN GRType AS `GRT` ON `l1`.grtype_id = `GRT`.id
				WHERE ((`GRT`.`id` = :grtype_id) AND ((`cc`.`org_id` = :current_contact->org_id) AND (`SSC`.`status` != 'obsolete')))
			*/
// Needs specific data model from unit-test-specific module for lnkGRTypeToServiceSubcategory and GRType classes
//			'ServiceSubcategory' => array(
//				"SELECT ServiceSubcategory AS SSC JOIN lnkGRTypeToServiceSubcategory AS l1 ON l1.servicesubcategory_id = SSC.id JOIN GRType AS GRT ON l1.grtype_id = GRT.id JOIN Service AS S ON SSC.service_id = S.id WHERE GRT.id = :grtype_id",
//				"SELECT ServiceSubcategory AS ssc JOIN Service AS s ON ssc.service_id=s.id JOIN lnkCustomerContractToService AS l1 ON l1.service_id=s.id JOIN CustomerContract AS cc ON l1.customercontract_id=cc.id WHERE cc.org_id = :current_contact->org_id AND ssc.status != 'obsolete'",
//				"SELECT `SSC` FROM ServiceSubcategory AS `SSC` JOIN Service AS `S` ON `SSC`.service_id = `S`.id JOIN lnkCustomerContractToService AS `l11` ON `l11`.service_id = `S`.id JOIN CustomerContract AS `cc` ON `l11`.customercontract_id = `cc`.id JOIN lnkGRTypeToServiceSubcategory AS `l1` ON `l1`.servicesubcategory_id = `SSC`.id JOIN GRType AS `GRT` ON `l1`.grtype_id = `GRT`.id WHERE ((`GRT`.`id` = :grtype_id) AND ((`cc`.`org_id` = :current_contact->org_id) AND (`SSC`.`status` != 'obsolete')))"
//			),
			'Person' => [
				"SELECT P FROM Person AS P JOIN lnkPersonToTeam AS l1 ON l1.person_id = P.id JOIN Team AS T ON l1.team_id = T.id WHERE T.id = 3",
				"SELECT p FROM Person AS p JOIN Person AS mgr ON p.manager_id = mgr.id JOIN lnkContactToTicket AS l1 ON l1.contact_id = mgr.id JOIN Ticket AS T ON l1.ticket_id = T.id WHERE T.id = 4 AND p.id = 3",
				"SELECT `P` FROM Person AS `P` JOIN Person AS `mgr` ON `P`.manager_id = `mgr`.id JOIN lnkContactToTicket AS `l11` ON `l11`.contact_id = `mgr`.id JOIN Ticket AS `T1` ON `l11`.ticket_id = `T1`.id JOIN lnkPersonToTeam AS `l1` ON `l1`.person_id = `P`.id JOIN Team AS `T` ON `l1`.team_id = `T`.id WHERE ((`T`.`id` = 3) AND ((`T1`.`id` = 4) AND (`P`.`id` = 3)))",
			],
			'Person2' => [
				"SELECT P FROM Person AS P JOIN lnkPersonToTeam AS l1 ON l1.person_id = P.id JOIN Team AS T ON l1.team_id = T.id JOIN Person AS MGR ON P.manager_id = MGR.id WHERE T.id = 3",
				"SELECT p FROM Person AS p JOIN Person AS mgr ON p.manager_id = mgr.id JOIN lnkContactToTicket AS l1 ON l1.contact_id = mgr.id JOIN Ticket AS T ON l1.ticket_id = T.id WHERE T.id = 4 AND p.id = 3",
				"SELECT `P` FROM Person AS `P` JOIN Person AS `MGR` ON `P`.manager_id = `MGR`.id JOIN lnkContactToTicket AS `l11` ON `l11`.contact_id = `MGR`.id JOIN Ticket AS `T1` ON `l11`.ticket_id = `T1`.id JOIN lnkPersonToTeam AS `l1` ON `l1`.person_id = `P`.id JOIN Team AS `T` ON `l1`.team_id = `T`.id WHERE ((`T`.`id` = 3) AND ((`T1`.`id` = 4) AND (`P`.`id` = 3)))",
			],
		];
	}

	/**
	 * @dataProvider MakeSelectQueryProvider
	 * @param $sOQL
	 * @param $sExpectedExceptionClass
	 */
	public function testMakeSelectQuery($sOQL, $sExpectedExceptionClass = '')
	{
		$sExceptionClass = '';
		try {
			$oSearch = DBSearch::FromOQL($sOQL);
			CMDBSource::TestQuery($oSearch->MakeSelectQuery());
		} catch (Exception $e) {
			$this->debug($e->getMessage());
			$sExceptionClass = get_class($e);
		}

		static::assertEquals($sExpectedExceptionClass, $sExceptionClass);
	}

	public function MakeSelectQueryProvider()
	{
		return [
			["SELECT `UserRequest` FROM UserRequest AS `UserRequest` JOIN Person AS `P` ON `UserRequest`.agent_id = `P`.id JOIN Organization AS `Organization` ON `P`.org_id = `Organization`.id WHERE (`UserRequest`.`org_id` IN (SELECT `Organization` FROM Organization AS `Organization` WHERE (`Organization`.`id` = `Toto`.`org_id`)))", 'OqlNormalizeException'],
			["SELECT `UserRequest` FROM UserRequest AS `UserRequest` JOIN Person AS `P` ON `UserRequest`.agent_id = `P`.id JOIN Organization AS `Organization` ON `P`.org_id = `Organization`.id WHERE (`UserRequest`.`org_id` IN (SELECT `Organization` FROM Organization AS `Organization` WHERE (`Organization`.`id` = `UserRequest`.`org_id`)))"],
			["SELECT `U` FROM User AS `U` JOIN Person AS `P` ON `U`.contactid = `P`.id WHERE ((`U`.`status` = 'enabled') AND (`U`.`id` NOT IN (SELECT `U` FROM User AS `U` JOIN Person AS `P` ON `U`.contactid = `P`.id JOIN URP_UserOrg AS `L` ON `L`.userid = `U`.id WHERE ((`U`.`status` = 'enabled') AND (`L`.`allowed_org_id` = `P`.`org_id`)) UNION SELECT `U` FROM User AS `U` WHERE ((`U`.`status` = 'enabled') AND (`U`.`id` NOT IN (SELECT `U` FROM User AS `U` JOIN URP_UserOrg AS `L` ON `L`.userid = `U`.id WHERE (`U`.`status` = 'enabled')))))))"],
			["SELECT `Ur` FROM UserRequest AS `Ur` WHERE (`Ur`.`id` NOT IN (SELECT `Ur` FROM UserRequest AS `Ur` JOIN lnkFunctionalCIToTicket AS `lnk` ON `lnk`.ticket_id = `Ur`.id WHERE 1))"],
			["SELECT `T` FROM Ticket AS `T` WHERE ((`T`.`finalclass` IN ('userrequest', 'change')) AND (`T`.`id` NOT IN (SELECT `Ur` FROM UserRequest AS `Ur` JOIN lnkFunctionalCIToTicket AS `lnk` ON `lnk`.ticket_id = `Ur`.id WHERE 1 UNION SELECT `C` FROM Change AS `C` JOIN lnkFunctionalCIToTicket AS `lnk` ON `lnk`.ticket_id = `C`.id WHERE 1)))"],
			["SELECT `PhysicalDevice` FROM PhysicalDevice AS `PhysicalDevice` WHERE ((`PhysicalDevice`.`status` = 'production') AND (`PhysicalDevice`.`id` NOT IN (SELECT `p` FROM PhysicalDevice AS `p` JOIN lnkFunctionalCIToProviderContract AS `l` ON `l`.functionalci_id = `p`.id WHERE 1)))"],
			["SELECT `U` FROM User AS `U` JOIN Person AS `P` ON `U`.contactid = `P`.id WHERE ((`U`.`status` = 'enabled') AND (`U`.`id` NOT IN (SELECT `U` FROM User AS `U` JOIN Person AS `P` ON `U`.contactid = `P`.id JOIN URP_UserOrg AS `L` ON `L`.userid = `U`.id WHERE ((`U1`.`status` = 'enabled') AND (`L`.`allowed_org_id` = `P`.`org_id`)) UNION SELECT `U` FROM User AS `U` WHERE ((`U`.`status` = 'enabled') AND (`U`.`id` NOT IN (SELECT `U` FROM User AS `U` JOIN URP_UserOrg AS `L` ON `L`.userid = `U`.id WHERE (`U`.`status` = 'enabled')))))))", "OqlNormalizeException"],
			["SELECT Team WHERE id NOT IN (SELECT Team AS t JOIN lnkPersonToTeam AS l ON l.team_id=t.id WHERE 1)"],
			["SELECT UserRequest WHERE id NOT IN (SELECT UserRequest AS u JOIN lnkFunctionalCIToTicket AS l ON l.ticket_id=u.id JOIN PhysicalDevice AS f ON l.functionalci_id=f.id WHERE f.status='production')"],
			["SELECT UserRequest WHERE id NOT IN (SELECT UserRequest AS u JOIN lnkFunctionalCIToTicket AS l ON l.ticket_id=u.id JOIN PhysicalDevice AS f ON l.functionalci_id=f.id WHERE f.status='production' UNION SELECT UserRequest AS u JOIN lnkFunctionalCIToTicket AS l ON l.ticket_id=u.id JOIN ApplicationSolution AS f ON l.functionalci_id=f.id WHERE f.status='active')"],
			["SELECT Person WHERE status='active' AND id NOT IN (SELECT Person AS p JOIN User AS u ON u.contactid=p.id WHERE u.status='enabled')"],
		];
	}

	/**
	 * @dataProvider GetOQLClassTreeProvider
	 * @param $sOQL
	 * @param $sExpectedOQL
	 */
	public function testGetOQLClassTree($sOQL, $sExpectedOQL)
	{
		$oFilter = DBSearch::FromOQL($sOQL);
		$aCountAttToLoad = [];
		$sMainClass = null;
		foreach ($oFilter->GetSelectedClasses() as $sClassAlias => $sClass) {
			$aCountAttToLoad[$sClassAlias] = [];
			if (empty($sMainClass)) {
				$sMainClass = $sClass;
			}
		}
		$aModifierProperties = MetaModel::MakeModifierProperties($oFilter);
		$oSQLObjectQueryBuilder = new SQLObjectQueryBuilder($oFilter);
		$oBuild = new QueryBuilderContext($oFilter, $aModifierProperties, null, null, null, $aCountAttToLoad);
		$sResultOQL = $oSQLObjectQueryBuilder->DebugOQLClassTree($oBuild);

		static::assertEquals($sExpectedOQL, $sResultOQL);
	}

	public function GetOQLClassTreeProvider()
	{
		return [
			'Bug 3660 1' => [
				"SELECT UserRequest AS U JOIN lnkContactToTicket AS l ON l.ticket_id=U.id JOIN Team AS T ON l.contact_id=T.id",
				"SELECT `U` FROM `UserRequest` AS `U`
    INNER JOIN `lnkContactToTicket` AS `l`
      ON `U`.`id` = `l`.`ticket_id` 
        INNER JOIN `Team` AS `T`
          ON `l`.`contact_id` = `T`.`id`",
			],
			'Bug 3660 2' => [
				"SELECT UserRequest AS U JOIN lnkContactToTicket AS l ON l.ticket_id=U.id JOIN Contact AS C ON l.contact_id=C.id",
				"SELECT `U` FROM `UserRequest` AS `U`
    INNER JOIN `lnkContactToTicket` AS `l`
      ON `U`.`id` = `l`.`ticket_id`",
			],
		];
	}

	/**
	 * @dataProvider MakeSelectQueryForCountProvider
	 *
	 * @param $sOQL
	 * @param $sExpectedSQL
	 *
	 * @throws \CoreException
	 * @throws \MissingQueryArgument
	 * @throws \OQLException
	 */
	public function testMakeSelectQueryForCount($sOQL, $sExpectedSQL)
	{
		$oFilter = DBSearch::FromOQL($sOQL);
		// Avoid adding all the fields for counts or "group by" requests
		$aCountAttToLoad = [];
		$sMainClass = null;
		foreach ($oFilter->GetSelectedClasses() as $sClassAlias => $sClass) {
			$aCountAttToLoad[$sClassAlias] = [];
			if (empty($sMainClass)) {
				$sMainClass = $sClass;
			}
		}
		$sSQL = $oFilter->MakeSelectQuery([], [], $aCountAttToLoad, null, 0, 0, true);
		static::assertEquals($sExpectedSQL, $sSQL);
	}

	public function MakeSelectQueryForCountProvider()
	{
		return [
			'Bug 3618' => [
				"SELECT UserRequest WHERE private_log LIKE '%Auteur : %' UNION SELECT UserRequest",
				"SELECT COUNT(*) AS COUNT FROM (SELECT
 1 
 FROM (
SELECT
 DISTINCT `UserRequest_Ticket`.`id` AS `UserRequestid`
 FROM 
   `ticket` AS `UserRequest_Ticket`
 WHERE ((`UserRequest_Ticket`.`private_log` LIKE '%Auteur : %') AND COALESCE((`UserRequest_Ticket`.`finalclass` IN ('UserRequest')), 1))
   
 UNION
 SELECT
 DISTINCT `UserRequest`.`id` AS `UserRequestid`
 FROM 
   `ticket_request` AS `UserRequest`
 WHERE 1
   
) as __selects__
) AS _union_alderaan_",
			],
		];
	}

	private function GivenVMAndLicence(int $iVmOrgId, ?int $iLicenceOrgId): int
	{
		$iOsFamilyId = $this->GivenObjectInDB('OSFamily', [
			'name' => 'Test OS Family',
		]);

		$iOsVersionId = $this->GivenObjectInDB('OSVersion', [
			'name'        => 'Test OS Version',
			'osfamily_id' => $iOsFamilyId,
		]);

		$iOSLicenceId = $this->GivenObjectInDB('OSLicence', [
			'name'         => 'Test OS Licence',
			'osversion_id' => $iOsVersionId,
			'org_id'       => $iLicenceOrgId,
		]);

		$iVClusterId = $this->GivenObjectInDB('Hypervisor', [
			'name'   => 'Test Hypervisor',
			'org_id' => $iVmOrgId,
		]);

		return $this->GivenObjectInDB('VirtualMachine', [
			'name'           => 'Test VM with OS Licence',
			'org_id'         => $iVmOrgId,
			'virtualhost_id' => $iVClusterId,
			'oslicence_id'   => $iOSLicenceId,
		]);
	}

	public function testMultiColumnQueryBehaviorWithOrganizationRestrictions()
	{
		$sAllowedOrgId = $this->GivenObjectInDB('Organization', ['name' => 'Test organization']);
		$sForbiddenOrgId = $this->GivenObjectInDB('Organization', ['name' => 'Test organization not allowed']);

		$iVmWithOsLicenceAllowed = $this->GivenVMAndLicence($sAllowedOrgId, $sAllowedOrgId);
		$iVmWithOsLicenceForbidden = $this->GivenVMAndLicence($sAllowedOrgId, $sForbiddenOrgId);
		$iVmWithoutOsLicence = $this->GivenVMAndLicence($sAllowedOrgId, null);

		$sLoginUserWithAllowedOrg = $this->GivenUserRestrictedToAnOrganizationInDB($sAllowedOrgId, 3);

		$sQuery = <<<OQL
			SELECT VM, OSL
			FROM VirtualMachine AS VM
			JOIN OSLicence AS OSL ON VM.oslicence_id = OSL.id
		OQL;

		UserRights::Login($sLoginUserWithAllowedOrg);

		$oDbObjectSearch = DBObjectSearch::FromOQL($sQuery);

		$oSet = new DBObjectSet($oDbObjectSearch);

		$aQueryResult = $oSet->ToArray();
		$this->assertArrayHasKey($iVmWithOsLicenceAllowed, $aQueryResult, 'The VM with OS Licence in allowed org should be found');
		$this->assertArrayNotHasKey($iVmWithOsLicenceForbidden, $aQueryResult, 'The VM with OS Licence in forbidden org should NOT be found');
		$this->assertArrayHasKey($iVmWithoutOsLicence, $aQueryResult, 'The VM without OS Licence should be found (cf. #727)');
		UserRights::Logoff();
	}

	public function testMultiColumnQueryBehaviorWithoutOrganizationRestrictions()
	{
		$sOrgId = $this->GivenObjectInDB('Organization', ['name' => 'Test organization']);

		$iVmWithOsLicence = $this->GivenVMAndLicence($sOrgId, $sOrgId);
		$iVmWithoutOsLicence = $this->GivenVMAndLicence($sOrgId, null);

		$sLoginUserWithNoRestriction = $this->GivenUserInDB('azerty', ['Configuration Manager']);

		$sQuery = <<<OQL
			SELECT VM, OSL
			FROM VirtualMachine AS VM
			JOIN OSLicence AS OSL ON VM.oslicence_id = OSL.id
		OQL;

		UserRights::Login($sLoginUserWithNoRestriction);

		$oDbObjectSearch = DBObjectSearch::FromOQL($sQuery);

		$oSet = new DBObjectSet($oDbObjectSearch);

		$aQueryResult = $oSet->ToArray();
		$this->assertArrayHasKey($iVmWithOsLicence, $aQueryResult, 'The VM with OS Licence should be found');
		$this->assertArrayHasKey($iVmWithoutOsLicence, $aQueryResult, 'The VM without OS Licence should be found');
		UserRights::Logoff();
	}
}

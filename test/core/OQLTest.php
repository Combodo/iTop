<?php

/**
 * Created by PhpStorm.
 * User: Eric
 * Date: 31/08/2018
 * Time: 17:03
 */

namespace Combodo\iTop\Test\UnitTest\Core;


use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use DBObjectSearch;
use DBSearch;
use Exception;
use OqlInterpreter;
use utils;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class OQLTest extends ItopDataTestCase
{
	const USE_TRANSACTION = false;

	/**
	 * @doesNotPerformAssertions
	 *
	 * @throws \ConfigException
	 * @throws \CoreException
	 */
	public function testOQLSetup()
	{
		utils::GetConfig()->Set('use_legacy_dbsearch', false, 'test');
		utils::GetConfig()->Set('apc_cache.enabled', false, 'test');
		utils::GetConfig()->Set('query_cache_enabled', false, 'test');
		utils::GetConfig()->Set('expression_cache_enabled', false, 'test');
		$sConfigFile = utils::GetConfig()->GetLoadedFile();
		@chmod($sConfigFile, 0770);
		utils::GetConfig()->WriteToFile();
		@chmod($sConfigFile, 0444); // Read-only
	}

	/**
	 * @dataProvider NestedQueryProvider
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
		return array(
			array('SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE  `UserRequest`.org_id IN (SELECT id FROM Organization AS `Organization` JOIN Organization AS `Organization1` ON `Organization`.parent_id BELOW `Organization1`.id WHERE (`Organization1`.`id` = \'3\'))'),
			array('SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE (`UserRequest`.`org_id` IN (SELECT `Organization` FROM Organization AS `Organization` WHERE `Organization`.`id`=`UserRequest`.`org_id`))'),
			array('SELECT toto WHERE id IN (SELECT titi)'),
			array('SELECT toto WHERE id IN (SELECT titi WHERE a=1)'),
			array('SELECT toto WHERE id IN (SELECT titi AS ti JOIN toto AS to ON to.a=ti.b)'),
			array('SELECT toto WHERE id IN (SELECT titi AS ti JOIN toto AS to ON to.a=ti.b WHERE to.a=1)'),
			array('SELECT toto WHERE id NOT IN (SELECT titi)'),
			array("SELECT User AS U JOIN Person AS P ON U.contactid = P.id
WHERE U.status='enabled' AND U.id NOT IN (
  SELECT User AS U
    JOIN Person AS P ON U.contactid=P.id
    JOIN URP_UserOrg AS L ON L.userid = U.id
    WHERE U.status='enabled' AND L.allowed_org_id = P.org_id
  UNION SELECT User AS U
    WHERE U.status='enabled' AND U.id NOT IN (
      SELECT User AS U
      JOIN URP_UserOrg AS L ON L.userid = U.id
      WHERE U.status='enabled'
  )
)"),
			array("SELECT UserRequest AS Ur WHERE Ur.id NOT IN (SELECT UserRequest AS Ur JOIN lnkFunctionalCIToTicket AS lnk ON lnk.ticket_id = Ur.id)"),
			array("SELECT Ticket AS T WHERE T. finalclass IN ('userrequest' , 'change') AND T.id  NOT IN (SELECT UserRequest AS Ur JOIN lnkFunctionalCIToTicket AS lnk ON lnk.ticket_id = Ur.id  UNION SELECT Change AS C JOIN lnkFunctionalCIToTicket AS lnk ON lnk.ticket_id = C.id)"),
			array("SELECT PhysicalDevice WHERE status='production' AND id NOT IN (SELECT PhysicalDevice AS p JOIN lnkFunctionalCIToProviderContract AS l ON l.functionalci_id=p.id)"),
		);
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
        return array(
            array('SELECT toto'),
            array('SELECT toto WHERE toto.a = 1'),
            array('SELECT toto WHERE toto.a = -1'),
            array('SELECT toto WHERE toto.a = (1-1)'),
            array('SELECT toto WHERE toto.a = (-1+3)'),
            array('SELECT toto WHERE toto.a = (3+-1)'),
            array('SELECT toto WHERE toto.a = (3--1)'),
            array('SELECT toto WHERE toto.a = 0xC'),
            array('SELECT toto WHERE toto.a = \'AXDVFS0xCZ32\''),
            array('SELECT toto WHERE toto.a = :myparameter'),
            array('SELECT toto WHERE toto.a IN (:param1)'),
            array('SELECT toto WHERE toto.a IN (:param1, :param2)'),
            array('SELECT toto WHERE toto.a=1'),
            array('SELECT toto WHERE toto.a = "1"'),
            array('SELECT toto WHERE toto.a & 1'),
            array('SELECT toto WHERE toto.a | 1'),
            array('SELECT toto WHERE toto.a ^ 1'),
            array('SELECT toto WHERE toto.a << 1'),
            array('SELECT toto WHERE toto.a >> 1'),
            array('SELECT toto WHERE toto.a NOT LIKE "That\'s it"'),
            array('SELECT toto WHERE toto.a NOT LIKE "That\'s \\"it\\""'),
            array('SELECT toto WHERE toto.a NOT LIKE \'That"s it\''),
            array('SELECT toto WHERE toto.a NOT LIKE \'That\\\'s it\''),
            array('SELECT toto WHERE toto.a NOT LIKE "blah \\\\ truc"'),
            array('SELECT toto WHERE toto.a NOT LIKE \'blah \\\\ truc\''),
            array('SELECT toto WHERE toto.a NOT LIKE "\\\\"'),
            array('SELECT toto WHERE toto.a NOT LIKE "\\""'),
            array('SELECT toto WHERE toto.a NOT LIKE "\\"\\\\"'),
            array('SELECT toto WHERE toto.a NOT LIKE "\\\\\\""'),
            array('SELECT toto WHERE toto.a NOT LIKE ""'),
            array('SELECT toto WHERE toto.a NOT LIKE "blah" AND toto.b LIKE "foo"'),
            array('SELECT toto WHERE toto.a = 1 AND toto.b LIKE "x" AND toto.f >= 12345'),
            array('SELECT Device JOIN Site ON Device.site = Site.id'),
            array('SELECT Device JOIN Site ON Device.site = Site.id JOIN Country ON Site.location = Country.id'),
            array('SELECT UserRightsMatrixClassGrant WHERE UserRightsMatrixClassGrant.class = \'lnkContactRealObject\' AND UserRightsMatrixClassGrant.action = \'modify\' AND UserRightsMatrixClassGrant.login = \'Denis\''),
            array('SELECT A WHERE A.col1 = \'lit1\' AND A.col2 = \'lit2\' AND A.col3 = \'lit3\''),
            array('SELECT A JOIN B ON A.myB = B.id WHERE (A.col1 = 123 AND B.col1 = \'aa\') OR (A.col3 = \'zzz\' AND B.col4 > 100)'),
            array('SELECT A JOIN B ON A.myB = B.id WHERE (A.col1 = B.col2 AND B.col1 = A.col2) OR (A.col3 = \'\' AND B.col4 > 100)'),
            array('SELECT A JOIN B ON A.myB = B.id WHERE A.col1 + B.col2 * B.col1 = A.col2'),
            array('SELECT A JOIN B ON A.myB = B.id WHERE A.col1 + (B.col2 * B.col1) = A.col2'),
            array('SELECT A JOIN B ON A.myB = B.id WHERE (A.col1 + B.col2) * B.col1 = A.col2'),
            array('SELECT A JOIN B ON A.myB = B.id WHERE (A.col1 & B.col2) = A.col2'),
            array('SELECT Device AS D_ JOIN Site AS S_ ON D_.site = S_.id WHERE S_.country = "Francia"'),
            array('SELECT A FROM A'),
            array('SELECT A JOIN B ON A.myB = B.id WHERE A.col1 = 2'),
            array('SELECT A FROM A JOIN B ON A.myB = B.id WHERE A.col1 = 2'),
            array('SELECT B FROM A JOIN B ON A.myB = B.id WHERE A.col1 = 2'),
            array('SELECT A,B FROM A JOIN B ON A.myB = B.id WHERE A.col1 = 2'),
            array('SELECT A, B FROM A JOIN B ON A.myB = B.id WHERE A.col1 = 2'),
            array('SELECT B,A FROM A JOIN B ON A.myB = B.id WHERE A.col1 = 2'),
            array('SELECT  A, B,C FROM A JOIN B ON A.myB = B.id'),
            array('SELECT C FROM A JOIN B ON A.myB = B.id WHERE A.col1 = 2'),
            array('SELECT A JOIN B ON A.myB BELOW B.id WHERE A.col1 = 2'),
            array('SELECT A JOIN B ON B.myA BELOW A.id WHERE A.col1 = 2'),
            array('SELECT A JOIN B ON A.myB = B.id JOIN C ON C.parent_id BELOW B.id WHERE A.col1 = 2 AND B.id = 3'),
            array('SELECT A JOIN B ON A.myB = B.id JOIN C ON C.parent_id BELOW STRICT B.id WHERE A.col1 = 2 AND B.id = 3'),
            array('SELECT A JOIN B ON A.myB = B.id JOIN C ON C.parent_id NOT BELOW B.id WHERE A.col1 = 2 AND B.id = 3'),
            array('SELECT A JOIN B ON A.myB = B.id JOIN C ON C.parent_id NOT BELOW STRICT B.id WHERE A.col1 = 2 AND B.id = 3'),
            array('SELECT A UNION SELECT B'),
            array('SELECT A WHERE A.b = "sdf" UNION SELECT B WHERE B.a = "sfde"'),
            array('SELECT A UNION SELECT B UNION SELECT C'),
            array('SELECT A UNION SELECT B UNION SELECT C UNION SELECT D'),
            array('SELECT A JOIN B ON A.myB = B.id JOIN C ON C.parent_id NOT BELOW B.id WHERE A.col1 = 2 AND B.id = 3 UNION SELECT Device JOIN Site ON Device.site = Site.id JOIN Country ON Site.location = Country.id'),
            array('SELECT Person AS B WHERE B.name LIKE \'%A%\''),
            array('SELECT Server WHERE name REGEXP \'dbserver[0-9]+\''),
            array('SELECT Server WHERE name REGEXP \'^dbserver[0-9]+\\\\..+\\\\.[a-z]{2,3}$\''),
            array('SELECT Change AS ch WHERE ch.start_date >= \'2009-12-31\' AND ch.end_date <= \'2010-01-01\''),
            array('SELECT DatacenterDevice AS dev WHERE INET_ATON(dev.managementip) > INET_ATON(\'10.22.32.224\') AND INET_ATON(dev.managementip) < INET_ATON(\'10.22.32.255\')'),
            array('SELECT Person AS P JOIN Organization AS Node ON P.org_id = Node.id JOIN Organization AS Root ON Node.parent_id BELOW Root.id WHERE Root.id=1'),
            array('SELECT PhysicalInterface AS if JOIN DatacenterDevice AS dev ON if.connectableci_id = dev.id WHERE dev.status = \'production\' AND dev.organization_name = \'Demo\''),
            array('SELECT Ticket AS t WHERE t.agent_id = :current_contact_id'),
            array('SELECT Person AS p JOIN UserRequest AS u ON u.agent_id = p.id WHERE u.status != \'closed\''),
            array('SELECT Contract AS c WHERE c.end_date > NOW() AND c.end_date < DATE_ADD(NOW(), INTERVAL 30 DAY)'),
            array('SELECT UserRequest AS u WHERE u.start_date < DATE_SUB(NOW(), INTERVAL 60 MINUTE) AND u.status = \'new\''),
            array('SELECT UserRequest AS u WHERE u.close_date > DATE_ADD(u.start_date, INTERVAL 8 HOUR)'),
            array('SELECT Ticket WHERE tagfield MATCHES \'salad\''),
        );
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
        try
        {
            $oOql->ParseQuery();
        }
        catch (Exception $e)
        {
            $sExceptionClass = get_class($e);
        }

        static::assertEquals($sExpectedExceptionClass, $sExceptionClass);
    }

    public function BadQueryProvider()
    {
        return array(
            array('SELECT toto WHERE toto.a = (3++1)', 'OQLParserException'),
            array('SELECT toto WHHHERE toto.a = "1"', 'OQLParserException'),
            array('SELECT toto WHERE toto.a == "1"', 'OQLParserException'),
            array('SELECT toto WHERE toto.a % 1', 'Exception'),
            array('SELECT toto WHERE toto.a like \'arg\'', 'OQLParserException'),
            array('SELECT toto WHERE toto.a NOT LIKE "That\'s "it""', 'OQLParserException'),
            array('SELECT toto WHERE toto.a NOT LIKE \'That\'s it\'', 'OQLParserException'),
            array('SELECT toto WHERE toto.a NOT LIKE "blah \\ truc"', 'Exception'),
            array('SELECT toto WHERE toto.a NOT LIKE \'blah \\ truc\'', 'Exception'),
            array('SELECT A JOIN B ON A.myB = B.id JOIN C ON C.parent_id = B.id WHERE A.col1 BELOW 2 AND B.id = 3', 'OQLParserException'),
        );
    }

    /**
     * @dataProvider TypeErrorQueryProvider
     * @depends testOQLSetup
     *
     * @param $sQuery
     *
     * @expectedException \TypeError
     *
     * @throws \OQLException
     */
    public function testTypeErrorQueryParser($sQuery)
    {
        $this->debug($sQuery);
        $oOql = new OqlInterpreter($sQuery);
        $oOql->ParseQuery();
    }

    public function TypeErrorQueryProvider()
    {
        return array(
            array('SELECT A WHERE A.a MATCHES toto'),
        );
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
        try
        {
            $oSearch = DBObjectSearch::FromOQL($sQuery);
            static::assertInstanceOf('DBObjectSearch', $oSearch);
        }
        catch (Exception $e)
        {
            $sExceptionClass = get_class($e);
        }

        static::assertEquals($sExpectedExceptionClass, $sExceptionClass);
    }


    public function QueryNormalizationProvider()
    {
        return array(
           array('SELECT Contact', ''),
           array('SELECT Contact WHERE nom_de_famille = "foo"', 'OqlNormalizeException'),
           array('SELECT Contact AS c WHERE name = "foo"', ''),
           array('SELECT Contact AS c WHERE nom_de_famille = "foo"', 'OqlNormalizeException'),
           array('SELECT Contact AS c WHERE c.name = "foo"', ''),
           array('SELECT Contact AS c WHERE Contact.name = "foo"', 'OqlNormalizeException'),
           array('SELECT Contact AS c WHERE x.name = "foo"', 'OqlNormalizeException'),

           array('SELECT Organization AS child JOIN Organization AS root ON child.parent_id BELOW root.id', ''),
           array('SELECT Organization AS root JOIN Organization AS child ON child.parent_id BELOW root.id', ''),

           array('SELECT RelationProfessionnelle', 'UnknownClassOqlException'),
           array('SELECT RelationProfessionnelle AS c WHERE name = "foo"', 'UnknownClassOqlException'),

            // The first query is the base query altered only in one place in the subsequent queries
           array('SELECT Person AS p JOIN lnkPersonToTeam AS lnk ON lnk.person_id = p.id WHERE p.name LIKE "foo"', ''),
           array('SELECT Person AS p JOIN lnkXXXXXXXXXXXX AS lnk ON lnk.person_id = p.id WHERE p.name LIKE "foo"', 'UnknownClassOqlException'),
           array('SELECT Person AS p JOIN lnkPersonToTeam AS lnk ON   p.person_id = p.id WHERE p.name LIKE "foo"', 'OqlNormalizeException'),
           array('SELECT Person AS p JOIN lnkPersonToTeam AS lnk ON     person_id = p.id WHERE p.name LIKE "foo"', 'OqlNormalizeException'),
           array('SELECT Person AS p JOIN lnkPersonToTeam AS lnk ON lnk.person_id =   id WHERE p.name LIKE "foo"', 'OqlNormalizeException'),
           array('SELECT Person AS p JOIN lnkPersonToTeam AS lnk ON lnk.role      = p.id WHERE p.name LIKE "foo"', 'OqlNormalizeException'),
           array('SELECT Person AS p JOIN lnkPersonToTeam AS lnk ON lnk.team_id   = p.id WHERE p.name LIKE "foo"', 'OqlNormalizeException'),
           array('SELECT Person AS p JOIN lnkPersonToTeam AS lnk ON lnk.person_id BELOW p.id WHERE p.name LIKE "bar"', ''),
           array('SELECT Person AS p JOIN lnkPersonToTeam AS lnk ON lnk.person_id = p.org_id WHERE p.name LIKE "foo"', 'OqlNormalizeException'),
           array('SELECT Person AS p JOIN lnkPersonToTeam AS lnk ON p.id = lnk.person_id WHERE p.name LIKE "foo"', 'OqlNormalizeException'), // inverted the JOIN spec
           array('SELECT Person AS p JOIN lnkPersonToTeam AS lnk ON lnk.person_id = p.id WHERE   name LIKE "foo"', ''),
           array('SELECT Person AS p JOIN lnkPersonToTeam AS lnk ON lnk.person_id = p.id WHERE x.name LIKE "foo"', 'OqlNormalizeException'),
           array('SELECT Person AS p JOIN lnkPersonToTeam AS lnk ON lnk.person_id = p.id WHERE p.eman LIKE "foo"', 'OqlNormalizeException'),
           array('SELECT Person AS p JOIN lnkPersonToTeam AS lnk ON lnk.person_id = p.id WHERE   eman LIKE "foo"', 'OqlNormalizeException'),
           array('SELECT Person AS p JOIN lnkPersonToTeam AS lnk ON lnk.person_id = p.id WHERE id = 1', 'OqlNormalizeException'),
           array('SELECT Person AS p JOIN lnkPersonToTeam AS lnk ON p.id = lnk.person_id WHERE p.name LIKE "foo"', 'OqlNormalizeException'),

           array('SELECT Person AS p JOIN Organization AS o ON p.org_id = o.id WHERE p.name LIKE "foo" AND o.name LIKE "land"', ''),
           array('SELECT Person AS p JOIN Organization AS o ON p.location_id = o.id WHERE p.name LIKE "foo" AND o.name LIKE "land"', 'OqlNormalizeException'),
           array('SELECT Person AS p JOIN Organization AS o ON p.name = o.id WHERE p.name LIKE "foo" AND o.name LIKE "land"', 'OqlNormalizeException'),

           array('SELECT Person AS p JOIN Organization AS o ON      p.org_id = o.id JOIN Person AS p ON      p.org_id = o.id', 'OqlNormalizeException'),
           array('SELECT Person      JOIN Organization AS o ON Person.org_id = o.id JOIN Person      ON Person.org_id = o.id', 'OqlNormalizeException'),

           array('SELECT Person AS p JOIN Location AS l ON p.location_id = l.id', ''),
           array('SELECT Person AS p JOIN Location AS l ON p.location_id BELOW l.id', 'OqlNormalizeException'),

           array('SELECT Person FROM Person JOIN Location ON Person.location_id = Location.id', ''),
           array('SELECT p FROM Person AS p JOIN Location AS l ON p.location_id = l.id', ''),
           array('SELECT l FROM Person AS p JOIN Location AS l ON p.location_id = l.id', ''),
           array('SELECT l, p FROM Person AS p JOIN Location AS l ON p.location_id = l.id', ''),
           array('SELECT p, l FROM Person AS p JOIN Location AS l ON p.location_id = l.id', ''),
           array('SELECT foo FROM Person AS p JOIN Location AS l ON p.location_id = l.id', 'OqlNormalizeException'),
           array('SELECT p, foo FROM Person AS p JOIN Location AS l ON p.location_id = l.id', 'OqlNormalizeException'),

            // Joins based on AttributeObjectKey
            //
           array('SELECT Attachment AS a JOIN UserRequest AS r ON a.item_id = r.id', ''),
           array('SELECT UserRequest AS r JOIN Attachment AS a ON a.item_id = r.id', ''),
        );
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
		$this->assertFalse(utils::GetConfig()->Get('use_legacy_dbsearch'));
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
		return array(
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
			'Person' => array(
				"SELECT P FROM Person AS P JOIN lnkPersonToTeam AS l1 ON l1.person_id = P.id JOIN Team AS T ON l1.team_id = T.id WHERE T.id = 3",
				"SELECT p FROM Person AS p JOIN Person AS mgr ON p.manager_id = mgr.id JOIN lnkContactToTicket AS l1 ON l1.contact_id = mgr.id JOIN Ticket AS T ON l1.ticket_id = T.id WHERE T.id = 4 AND p.id = 3",
				"SELECT `P` FROM Person AS `P` JOIN Person AS `mgr` ON `P`.manager_id = `mgr`.id JOIN lnkContactToTicket AS `l11` ON `l11`.contact_id = `mgr`.id JOIN Ticket AS `T1` ON `l11`.ticket_id = `T1`.id JOIN lnkPersonToTeam AS `l1` ON `l1`.person_id = `P`.id JOIN Team AS `T` ON `l1`.team_id = `T`.id WHERE ((`T`.`id` = 3) AND ((`T1`.`id` = 4) AND (`P`.`id` = 3)))"
			),
			'Person2' => array(
				"SELECT P FROM Person AS P JOIN lnkPersonToTeam AS l1 ON l1.person_id = P.id JOIN Team AS T ON l1.team_id = T.id JOIN Person AS MGR ON P.manager_id = MGR.id WHERE T.id = 3",
				"SELECT p FROM Person AS p JOIN Person AS mgr ON p.manager_id = mgr.id JOIN lnkContactToTicket AS l1 ON l1.contact_id = mgr.id JOIN Ticket AS T ON l1.ticket_id = T.id WHERE T.id = 4 AND p.id = 3",
				"SELECT `P` FROM Person AS `P` JOIN Person AS `MGR` ON `P`.manager_id = `MGR`.id JOIN lnkContactToTicket AS `l11` ON `l11`.contact_id = `MGR`.id JOIN Ticket AS `T1` ON `l11`.ticket_id = `T1`.id JOIN lnkPersonToTeam AS `l1` ON `l1`.person_id = `P`.id JOIN Team AS `T` ON `l1`.team_id = `T`.id WHERE ((`T`.`id` = 3) AND ((`T1`.`id` = 4) AND (`P`.`id` = 3)))"
			),
		);
	}

}

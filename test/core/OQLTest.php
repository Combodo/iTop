<?php

/**
 * Created by PhpStorm.
 * User: Eric
 * Date: 31/08/2018
 * Time: 17:03
 */

namespace Combodo\iTop\Test\UnitTest\Core;


use Combodo\iTop\Test\UnitTest\ItopDataTestCase;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class OQLTest extends ItopDataTestCase
{
	/**
	 * @dataProvider GoodQueryProvider
	 *
	 * @param $sQuery
	 *
	 * @throws \OQLException
	 */
	public function testGoodQuery($sQuery)
	{
		$this->debug($sQuery);
		$oOql = new \OqlInterpreter($sQuery);
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
			array('SELECT Ticket WHERE tagfield MATCHES \'salad\''),
		);
	}

	/**
	 * @dataProvider BadQueryProvider
	 *
	 * @param $sQuery
	 *
	 * @throws \OQLException
	 *
	 * @expectedException \Exception
	 */
	public function testBadQuery($sQuery)
	{
		$this->debug($sQuery);
		$oOql = new \OqlInterpreter($sQuery);
		$oOql->ParseQuery();
		static::fail();
	}

	public function BadQueryProvider()
	{
		return array(
			array('SELECT toto WHERE toto.a = (3++1)'),
			array('SELECT toto WHHHERE toto.a = "1"'),
			array('SELECT toto WHERE toto.a == "1"'),
			array('SELECT toto WHERE toto.a % 1'),
			array('SELECT toto WHERE toto.a like \'arg\''),
			array('SELECT toto WHERE toto.a NOT LIKE "That\'s "it""'),
			array('SELECT toto WHERE toto.a NOT LIKE \'That\'s it\''),
			array('SELECT toto WHERE toto.a NOT LIKE "blah \\ truc"'),
			array('SELECT toto WHERE toto.a NOT LIKE \'blah \\ truc\''),
			array('SELECT A JOIN B ON A.myB = B.id JOIN C ON C.parent_id = B.id WHERE A.col1 BELOW 2 AND B.id = 3'),
			//array('SELECT A WHERE A.a MATCHES toto'),
		);
	}
}

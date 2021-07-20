<?php


namespace Combodo\iTop\Test\UnitTest\Core;


use CMDBSource;
use Combodo\iTop\Test\UnitTest\iTopDataTestCase;
use DateInterval;
use DateTime;
use Expression;
use FunctionExpression;
use MetaModel;
use ScalarExpression;

class ExpressionEvaluateTest extends iTopDataTestCase
{
	const USE_TRANSACTION = false;

	/**
	 * @covers       Expression::GetParameters()
	 * @dataProvider GetParametersProvider
	 *
	 * @param $sExpression
	 * @param $sParentFilter
	 * @param $aExpectedParameters
	 *
	 * @throws \OQLException
	 */
	public function testGetParameters($sExpression, $sParentFilter, $aExpectedParameters)
	{
		$oExpression = Expression::FromOQL($sExpression);
		$aParameters = $oExpression->GetParameters($sParentFilter);
		sort($aExpectedParameters);
		sort($aParameters);
		static::assertEquals($aExpectedParameters, $aParameters);
	}

	public function GetParametersProvider()
	{
		return array(
			array('1 AND 0 OR :hello + :world', null, array('hello', 'world')),
			array('1 AND 0 OR :hello + :world', 'this', array()),
			array(':this->left + :this->right', null, array('this->left', 'this->right')),
			array(':this->left + :this->right', 'this', array('left', 'right')),
			array(':this->left + :this->right', 'that', array()),
			array(':this_left + :this_right', 'this', array()),
		);
	}

	/**
	 * 100x quicker to execute than testExpressionEvaluate
	 *
	 * @covers       Expression::Evaluate()
	 * @covers       Expression::FromOQL()
	 * @relies-on-dataProvider VariousExpressions
	 * @throws \OQLException
	 */
	public function _testExpressionEvaluateAllAtOnce()
	{
		$aTestCases = $this->VariousExpressionsProvider();
		foreach ($aTestCases as $sCaseId => $aTestArgs)
		{
			$this->debug("Case $sCaseId:");
			$this->testVariousExpressions($aTestArgs[0], $aTestArgs[1]);
		}
	}

	/**
	 * @covers       Expression::Evaluate()
	 * @covers       Expression::FromOQL()
	 * @dataProvider VariousExpressionsProvider
	 *
	 * @param string $sExpression
	 * @param string $expectedValue
	 *
	 * @throws \OQLException
	 * @throws \Exception
	 */
	public function testVariousExpressions($sExpression, $expectedValue)
	{
		$oExpression = Expression::FromOQL($sExpression);
		$value = $oExpression->Evaluate(array());
		static::assertEquals($expectedValue, $value);
	}

	public function VariousExpressionsProvider()
	{
		if (false)
		{
			$aExpressions = array(
				// Test case to isolate for troubleshooting purposes
				array('1+1', 2),
			);
		}
		else
		{
			$aExpressions = array(
				// The bare minimum
				array('"blah"', 'blah'),
				array('"\\\\"', '\\'),
				// Arithmetics
				array('2+2', 4),
				array('2+2-2', 2),
				array('2*(3+4)', 14),
				array('(2*3)+4', 10),
				array('2*3+4', 10),
				// Strings
				array("CONCAT('hello', 'world')", 'helloworld'),
				// Not yet parsed - array("CONCAT_WS(' ', 'hello', 'world')", 'hello world'),
				array("SUBSTR('abcdef', 2, 3)", 'bcd'),
				array("TRIM(' Sin dolor  ')", 'Sin dolor'),
				// Comparison operators
				array('1 = 1', 1),
				array('1 != 1', 0),
				array('0 = 1', 0),
				array('0 != 1', 1),
				array('2 > 1', 1),
				array('2 < 1', 0),
				array('1 > 2', 0),
				array('2 > 1', 1),
				array('2 >= 1', 1),
				array('2 >= 2', 1),
				array("'the quick brown dog' LIKE '%QUICK%'", 1),
				array("'the quick brown dog' LIKE '%SLOW%'", 0),
				array("'the quick brown dog' LIKE '%QU_CK%'", 1),
				array("'the quick brown dog' LIKE '%QU_ICK%'", 0),
				array('"400 (km/h)" LIKE "400%"', 1),
				array('"400 (km/h)" LIKE "100%"', 0),
				array('"2020-06-12" > "2020-06-11"', 1),
				array('"2020-06-12" < "2020-06-11"', 0),
				array('" 2020-06-12" > "2020-06-11"', 0), // Leading spaces => a string
				array('" 2020-06-12 " > "2020-06-11"', 0), // Trailing spaces => a string
				array('"2020-06-12 17:35:13" > "2020-06-12 17:35:12"', 1),
				array('"2020-06-12 17:35:13" < "2020-06-12 17:35:12"', 0),
				array('"2020-06-12 17:35:13" > "2020-06-12"', 1),
				array('"2020-06-12 17:35:13" < "2020-06-12"', 0),
				array('"2020-06-12 00:00:00" = "2020-06-12"', 0),
				// Logical operators
				array('0 AND 0', 0),
				array('1 AND 0', 0),
				array('0 AND 1', 0),
				array('1 AND 1', 1),
				array('0 OR 0', 0),
				array('0 OR 1', 1),
				array('1 OR 0', 1),
				array('1 OR 1', 1),
				array('1 AND 0 OR 1', 1),
				// Casting
				array('1 AND "blah"', 0),
				array('1 AND "1"', 1),
				array('1 AND "2"', 1),
				array('1 AND "0"', 0),
				array('1 AND "-1"', 1),
				// Null
				array('NULL', null),
				array('1 AND NULL', null),
				array('CONCAT("Great but...", NULL)', null),
				array('COALESCE(NULL, 123)', 123),
				array('COALESCE(321, 123)', 321),
				array('ISNULL(NULL)', 1),
				array('ISNULL(123)', 0),
				// Date functions
				array("DATE('2020-03-12 13:18:30')", '2020-03-12'),
				array("DATE_FORMAT('2009-10-04 22:23:00', '%Y %m %d %H %i %s')", '2009 10 04 22 23 00'),
				array("DATE(NOW()) = CURRENT_DATE()", 1), // Could fail if executed around midnight!
				array("TO_DAYS('2020-01-02')", 737791),
				array("FROM_DAYS(737791)", '2020-01-02'),
				array("YEAR('2020-05-03')", 2020),
				array("MONTH('2020-05-03')", 5),
				array("DAY('2020-05-03')", 3),
				array("DATE_ADD('2020-02-28 18:00:00', INTERVAL 1 HOUR)", '2020-02-28 19:00:00'),
				array("DATE_ADD('2020-02-28 18:00:00', INTERVAL 1 DAY)", '2020-02-29 18:00:00'),
				array("DATE_SUB('2020-03-01 18:00:00', INTERVAL 1 HOUR)", '2020-03-01 17:00:00'),
				array("DATE_SUB('2020-03-01 18:00:00', INTERVAL 1 DAY)", '2020-02-29 18:00:00'),
				// Misc. functions
				array('IF(1, 123, 567)', 123),
				array('IF(0, 123, 567)', 567),
				array('ELT(3, "a", "b", "c")', 'c'),
				array('ELT(0, "a", "b", "c")', null),
				array('ELT(4, "a", "b", "c")', null),
				array('INET_ATON("128.0.0.1")', 2147483649),
				array('INET_NTOA(2147483649)', '128.0.0.1'),
			);
		}

		// Build a comprehensive index
		$aRet = array();
		foreach ($aExpressions as $aExp)
		{
			$aRet[$aExp[0]] = $aExp;
		}
		return $aRet;
	}

	/**
	 * @covers       Expression::Evaluate()
	 * @dataProvider NotYetParsableExpressionsProvider
	 *
	 * @param string $sExpression
	 * @param string $expectedValue
	 */
	public function testNotYetParsableExpressions($sExpression, $expectedValue)
	{
		$sNewExpression = "return $sExpression;";
		$oExpression = eval($sNewExpression);
		$res = $oExpression->Evaluate(array());
		static::assertEquals($expectedValue, $res);
	}

	public function NotYetParsableExpressionsProvider()
	{
		$aExpressions = array(
			array("new \\FunctionExpression('CONCAT_WS', array(new \\ScalarExpression(' '), new \\ScalarExpression('Hello'), new \ScalarExpression('world!')))", 'Hello world!'),
			array("new \\ScalarExpression('windows\\system32')", 'windows\\system32'),
			array("new \\BinaryExpression(new \\ScalarExpression('100%'), 'LIKE', new \\ScalarExpression('___\%'))", 1),
			array("new \\BinaryExpression(new \ScalarExpression('1000'), 'LIKE', new \ScalarExpression('___\%'))", 0),
			// Net yet parsed - array("TIME(NOW()) = CURRENT_TIME()", 1), // Not relevant
			// Not yet parsed - array("DATE_ADD('2020-02-28 18:00:00', INTERVAL 1 WEEK)", '2020-03-06 18:00:00'),
			// Not yet parsed - array("DATE_SUB('2020-03-01 18:00:00', INTERVAL 1 WEEK)", '2020-02-23 18:00:00'),
			// Not yet parsed - array('ROUND(1.2345, 2)', 1.23),
			// Not yet parsed - array('FLOOR(1.2)', 1),
		);
		// Build a comprehensive index
		$aRet = array();
		foreach ($aExpressions as $aExp)
		{
			$aRet[$aExp[0]] = $aExp;
		}
		return $aRet;
	}

	/**
	 * Check that the test data would give the same result when evaluated by MySQL
	 * It uses the data provider ExpressionProvider, and checks every test case in one single query
	 *
	 * @throws \MySQLException
	 */
	public function testMySQLEvaluateAllAtOnce()
	{
		// Expressions given as an OQL
		$aTests = array_values($this->VariousExpressionsProvider());

		// Expressions given as a PHP statement
		foreach (array_values($this->NotYetParsableExpressionsProvider()) as $i => $aTest)
		{
			$sNewExpression = "return {$aTest[0]};";
			$oExpression = eval($sNewExpression);
			$sExpression = $oExpression->RenderExpression(true);
			$aTests[] = array($sExpression, $aTest[1]);
		}

		$aExpressions = array();
		foreach ($aTests as $i => $aTest)
		{
			$aExpressions[] = "{$aTest[0]} as test_$i";
		}

		$sSelects = implode(', ', $aExpressions);
		$sQuery = "SELECT $sSelects";

		$this->debug($sQuery);
		$aResults = CMDBSource::QueryToArray($sQuery);

		foreach ($aTests as $i => $aTest)
		{
			$value = $aResults[0]["test_$i"];
			$expectedValue = $aTest[1];
			$this->debug("Test #$i: {$aTests[$i][0]} => ".var_export($value, true));
			static::assertEquals($expectedValue, $value);
		}
	}

	/**
	 * @covers DBObject::EvaluateExpression
	 * @dataProvider ExpressionsWithObjectFieldsProvider
	 *
	 * @param $sClass
	 * @param $aValues
	 * @param $sExpression
	 * @param $expected
	 *
	 * @throws \CoreException
	 * @throws \OQLException
	 */
	public function testExpressionsWithObjectFields($sClass, $aValues, $sExpression, $expected)
	{
		$oObject = MetaModel::NewObject($sClass, $aValues);
		$oExpression = Expression::FromOQL($sExpression);

		$res = $oObject->EvaluateExpression($oExpression);

		static::assertEquals($expected, $res);
	}

	public function ExpressionsWithObjectFieldsProvider()
	{
		return [
			['URP_UserProfile', ['profileid' => 2], 'friendlyname', ''],
			['Location', ['name' => 'Grenoble', 'org_id' => 2], 'name', 'Grenoble'],
			['Location', ['name' => 'Grenoble', 'org_id' => 2], 'friendlyname', ''],
			['Location', ['name' => 'Grenoble', 'org_id' => 2], 'org_name', ''],
			['Location', ['name' => 'Grenoble', 'org_id' => 2], 'org_id_friendlyname', ''],
			['Location', ['name' => 'Grenoble', 'org_id' => 2], 'org_id', 2],
			['Location', ['name' => 'Grenoble', 'org_id' => 2], 'CONCAT(SUBSTR(name, 4), " cause")', 'noble cause'],
		];
	}

	/**
	 * @dataProvider ExpressionWithParametersProvider
	 *
	 * @param $sExpression
	 * @param $aParameters
	 * @param $expected
	 *
	 * @throws \OQLException
	 * @throws \Exception
	 */
	public function testExpressionWithParameters($sExpression, $aParameters, $expected)
	{
		$oExpression = Expression::FromOQL($sExpression);
		$res = $oExpression->Evaluate($aParameters);
		static::assertEquals($expected, $res);
	}

	public function ExpressionWithParametersProvider()
	{
		return array(
			array('CONCAT(SUBSTR(name, 4), " cause")', array('name' => 'noble'), 'le cause'),
		);
	}

	/**
	 * Check Expression::IfTrue
	 *
	 * @covers       Expression::FromOQL
	 * @covers       Expression::IsTrue
	 * @dataProvider TrueExpressionsProvider
	 *
	 * @param $sExpression
	 * @param $bExpectTrue
	 *
	 * @throws \OQLException
	 */
	public function testTrueExpressions($sExpression, $bExpectTrue)
	{
		$oExpression = Expression::FromOQL($sExpression);

		$res = $oExpression->IsTrue();
		if ($bExpectTrue)
		{
			static::assertTrue($res, 'arg: '.$sExpression);
		}
		else
		{
			static::assertFalse($res, 'arg: '.$sExpression);
		}
	}

	public function TrueExpressionsProvider()
	{
		$aExpressions = array(
			array('1', true),
			array('0 OR 0', false),
			array('1 AND 1', true),
			array('1 AND (1 OR 0)', true)
		);
		// Build a comprehensive index
		$aRet = array();
		foreach ($aExpressions as $aExp)
		{
			$aRet[$aExp[0]] = $aExp;
		}
		return $aRet;
	}

	/**
	 * @covers       FunctionExpression::Evaluate()
	 * @dataProvider TimeFormatsProvider
	 *
	 * @param $sFormat
	 * @param $bProcessed
	 * @param $sValueOrException
	 *
	 * @throws \CoreException
	 * @throws \MySQLException
	 * @throws \MySQLQueryHasNoResultException
	 * @throws \Exception
	 */
	public function testTimeFormat($sFormat, $bProcessed, $sValueOrException)
	{
		$sDate = '2009-06-04 21:23:24';
		$oExpression = new FunctionExpression('DATE_FORMAT', array(new ScalarExpression($sDate), new ScalarExpression("%$sFormat")));
		if ($bProcessed)
		{
			$sqlValue = CMDBSource::QueryToScalar("SELECT DATE_FORMAT('$sDate', '%$sFormat')");
			static::assertEquals($sqlValue, $sValueOrException, 'Check test against MySQL');

			$res = $oExpression->Evaluate(array());
			static::assertEquals($sValueOrException, $res, 'Check evaluation');
		}
		else
		{
			static::expectException($sValueOrException);
			$oExpression->Evaluate(array());
		}
	}

	public function TimeFormatsProvider()
	{
		$aTests = array(
			array('a', true, 'Thu'),
			array('b', true, 'Jun'),
			array('c', true, '6'),
			array('D', true, '4th'),
			array('d', true, '04'),
			array('e', true, '4'),
			array('f', false, 'NotYetEvaluatedExpression'), // microseconds: no way!
			array('H', true, '21'),
			array('h', true, '09'),
			array('I', true, '09'),
			array('i', true, '23'),
			array('j', true, '155'), // day of the year
			array('k', true, '21'),
			array('l', true, '9'),
			array('M', true, 'June'),
			array('m', true, '06'),
			array('p', true, 'PM'),
			array('r', true, '09:23:24 PM'),
			array('S', true, '24'),
			array('s', true, '24'),
			array('T', true, '21:23:24'),
			array('U', false, 'NotYetEvaluatedExpression'), // Week sunday based (mode 0)
			array('u', false, 'NotYetEvaluatedExpression'), // Week monday based (mode 1)
			array('V', false, 'NotYetEvaluatedExpression'), // Week sunday based (mode 2)
			array('v', true, '23'), // Week monday based (mode 3 - ISO-8601)
			array('W', true, 'Thursday'),
			array('w', true, '4'),
			array('X', false, 'NotYetEvaluatedExpression'),
			array('x', true, '2009'), // to be used with %v (ISO - 8601)
			array('Y', true, '2009'),
			array('y', true, '09'),
		);
		$aRes = array();
		foreach ($aTests as $aTest)
		{
			$aRes["Format %{$aTest[0]}"] = $aTest;
		}
		return $aRes;
	}

	/**
	 * Systematically check all supported format specs, for a given date
	 *
	 * @covers       FunctionExpression::Evaluate()
	 * @dataProvider EveryTimeFormatProvider
	 *
	 * @param $sDate
	 *
	 * @throws \CoreException
	 * @throws \MySQLException
	 * @throws \Exception
	 */
	public function testEveryTimeFormat($sDate)
	{
		$aFormats = $this->TimeFormatsProvider();
		$aSelects = array();
		foreach ($aFormats as $sFormatDesc => $aFormatSpec)
		{
			$sFormat = $aFormatSpec[0];
			$bProcessed = $aFormatSpec[1];
			if ($bProcessed)
			{
				$aSelects["%$sFormat"] = "DATE_FORMAT('$sDate', '%$sFormat') AS `$sFormat`";
			}
		}
		$sSelects = "SELECT ".implode(', ', $aSelects);
		$aRes = CMDBSource::QueryToArray($sSelects);
		$aRow = $aRes[0];
		foreach ($aFormats as $sFormatDesc => $aFormatSpec)
		{
			$sFormat = $aFormatSpec[0];
			$bProcessed = $aFormatSpec[1];
			if ($bProcessed)
			{
				$oExpression = new FunctionExpression('DATE_FORMAT', array(new ScalarExpression($sDate), new ScalarExpression("%$sFormat")));
				$res = $oExpression->Evaluate(array());
				static::assertEquals($aRow[$sFormat], $res, "Format %$sFormat not matching MySQL for '$sDate'");
			}
		}
	}
	public function EveryTimeFormatProvider()
	{
		return array(
			array('1971-07-19 8:40:00'),
			array('1999-12-31 23:59:59'),
			array('2000-01-01 00:00:00'),
			array('2009-06-04 21:23:24'),
			array('2020-02-29 23:59:59'),
			array('2030-10-21 23:59:59'),
			array('2050-12-21 23:59:59'),
		);
	}

	/**
	 * Systematically check all supported format specs, for a range of dates
	 *
	 * @covers       FunctionExpression::Evaluate()
	 * @dataProvider EveryTimeFormatOnDateRangeProvider
	 *
	 * @param $sStartDate
	 * @param $sInterval
	 * @param $iRepeat
	 *
	 * @throws \CoreException
	 * @throws \MySQLException
	 * @throws \Exception
	 */
	public function testEveryTimeFormatOnDateRange($sStartDate, $sInterval, $iRepeat)
	{
		$oDate = new DateTime($sStartDate);
		for ($i = 0 ; $i < $iRepeat ; $i++)
		{
			$sDate = date_format($oDate, 'Y-m-d, H:i:s');
			$this->debug("Checking '$sDate'");
			$this->testEveryTimeFormat($sDate);
			$oDate->add(new DateInterval($sInterval));
		}
	}

	public function EveryTimeFormatOnDateRangeProvider()
	{
		return array(
			'10 years, day by day' => array('2000-01-01', 'P1D', 365 * 10),
			'1 day, hour by hour' => array('2000-01-01 00:01:02', 'PT1H', 24),
			'1 hour, minute by minute' => array('2000-01-01 00:01:02', 'PT1M', 60),
			'1 minute, second by second' => array('2000-01-01 00:01:02', 'PT1S', 60),
		);
	}
}
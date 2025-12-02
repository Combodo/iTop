<?php

namespace Combodo\iTop\Test\UnitTest\Core;

use CMDBSource;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use DateInterval;
use DateTime;
use Expression;
use FunctionExpression;
use MetaModel;
use ScalarExpression;

class ExpressionEvaluateTest extends ItopDataTestCase
{
	public const USE_TRANSACTION = false;

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
		$this->assertEquals($aExpectedParameters, $aParameters);
	}

	public function GetParametersProvider()
	{
		return [
			['1 AND 0 OR :hello + :world', null, ['hello', 'world']],
			['1 AND 0 OR :hello + :world', 'this', []],
			[':this->left + :this->right', null, ['this->left', 'this->right']],
			[':this->left + :this->right', 'this', ['left', 'right']],
			[':this->left + :this->right', 'that', []],
			[':this_left + :this_right', 'this', []],
		];
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
		foreach ($aTestCases as $sCaseId => $aTestArgs) {
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
		$value = $oExpression->Evaluate([]);
		$this->assertEquals($expectedValue, $value);
	}

	public function VariousExpressionsProvider()
	{
		if (false) {
			$aExpressions = [
				// Test case to isolate for troubleshooting purposes
				["'a' IN ('a', 'b')", true],
			];
		} else {
			$aExpressions = [
				// The bare minimum
				['"blah"', 'blah'],
				['"\\\\"', '\\'],

				// Arithmetics
				['2+2', 4],
				['2+2-2', 2],
				['2*(3+4)', 14],
				['(2*3)+4', 10],
				['2*3+4', 10],

				// Strings
				["CONCAT('hello', 'world')", 'helloworld'],

				// Not yet parsed - array("CONCAT_WS(' ', 'hello', 'world')", 'hello world'),
				["SUBSTR('abcdef', 2, 3)", 'bcd'],
				["TRIM(' Sin dolor  ')", 'Sin dolor'],

				// Comparison operators
				['1 = 1', 1],
				['1 != 1', 0],
				['0 = 1', 0],
				['0 != 1', 1],
				['2 > 1', 1],
				['2 < 1', 0],
				['1 > 2', 0],
				['2 > 1', 1],
				['2 >= 1', 1],
				['2 >= 2', 1],
				["'the quick brown dog' LIKE '%QUICK%'", 1],
				["'the quick brown dog' LIKE '%SLOW%'", 0],
				["'the quick brown dog' LIKE '%QU_CK%'", 1],
				["'the quick brown dog' LIKE '%QU_ICK%'", 0],
				['"400 (km/h)" LIKE "400%"', 1],
				['"400 (km/h)" LIKE "100%"', 0],
				['"400 (km/h)" NOT LIKE "400%"', 0],
				['"400 (km/h)" NOT LIKE "100%"', 1],
				['"2020-06-12" > "2020-06-11"', 1],
				['"2020-06-12" < "2020-06-11"', 0],
				['" 2020-06-12" > "2020-06-11"', 0], // Leading spaces => a string
				['" 2020-06-12 " > "2020-06-11"', 0], // Trailing spaces => a string
				['"2020-06-12 17:35:13" > "2020-06-12 17:35:12"', 1],
				['"2020-06-12 17:35:13" < "2020-06-12 17:35:12"', 0],
				['"2020-06-12 17:35:13" > "2020-06-12"', 1],
				['"2020-06-12 17:35:13" < "2020-06-12"', 0],
				['"2020-06-12 00:00:00" = "2020-06-12"', 0],

				// IN operator
				["'a' IN ('a')", true],
				["'a' IN ('b')", false],
				["'a' IN ('a', 'b')", true],
				["'z' IN ('a', 'b')", false],
				["'a' NOT IN ('a')", false],
				["'a' NOT IN ('b')", true],
				["'a' NOT IN ('a', 'b')", false],
				["'z' NOT IN ('a', 'b')", true],

				// Logical operators
				['0 AND 0', 0],
				['1 AND 0', 0],
				['0 AND 1', 0],
				['1 AND 1', 1],
				['0 OR 0', 0],
				['0 OR 1', 1],
				['1 OR 0', 1],
				['1 OR 1', 1],
				['1 AND 0 OR 1', 1],

				// Casting
				['1 AND "blah"', 0],
				['1 AND "1"', 1],
				['1 AND "2"', 1],
				['1 AND "0"', 0],
				['1 AND "-1"', 1],

				// Null
				['NULL', null],
				['1 AND NULL', null],
				['CONCAT("Great but...", NULL)', null],
				['COALESCE(NULL, 123)', 123],
				['COALESCE(321, 123)', 321],
				['ISNULL(NULL)', 1],
				['ISNULL(123)', 0],

				// Date functions
				["DATE('2020-03-12 13:18:30')", '2020-03-12'],
				["DATE_FORMAT('2009-10-04 22:23:00', '%Y %m %d %H %i %s')", '2009 10 04 22 23 00'],
				["DATE(NOW()) = CURRENT_DATE()", 1], // Could fail if executed around midnight!
				["TO_DAYS('2020-01-02')", 737791],
				["FROM_DAYS(737791)", '2020-01-02'],
				["FROM_DAYS(TO_DAYS('2020-01-02'))", '2020-01-02'], // Back and forth conversion to ensure it returns the same
				["YEAR('2020-05-03')", 2020],
				["MONTH('2020-05-03')", 5],
				["DAY('2020-05-03')", 3],
				["DATE_ADD('2020-02-28 18:00:00', INTERVAL 1 HOUR)", '2020-02-28 19:00:00'],
				["DATE_ADD('2020-02-28 18:00:00', INTERVAL 1 DAY)", '2020-02-29 18:00:00'],
				["DATE_SUB('2020-03-01 18:00:00', INTERVAL 1 HOUR)", '2020-03-01 17:00:00'],
				["DATE_SUB('2020-03-01 18:00:00', INTERVAL 1 DAY)", '2020-02-29 18:00:00'],

				// Misc. functions
				['IF(1, 123, 567)', 123],
				['IF(0, 123, 567)', 567],
				['ELT(3, "a", "b", "c")', 'c'],
				['ELT(0, "a", "b", "c")', null],
				['ELT(4, "a", "b", "c")', null],
				['INET_ATON("128.0.0.1")', 2147483649],
				['INET_NTOA(2147483649)', '128.0.0.1'],
			];

			// N°5985 - Test bidirectional conversion across the centuries to ensure that it works on PHP 7.4 => 8.2+ even though the bug has been fixed in PHP 8.1 but still exists in PHP 7.4 => 8.1
			for ($iUpperYearBound = 1925; $iUpperYearBound <= 2100; $iUpperYearBound = $iUpperYearBound + 25) {
				$aExpressions[] = ["FROM_DAYS(TO_DAYS('$iUpperYearBound-01-02'))", "$iUpperYearBound-01-02"];
			}
		}

		// Build a comprehensive index
		$aRet = [];
		foreach ($aExpressions as $aExp) {
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
		$res = $oExpression->Evaluate([]);
		$this->assertEquals($expectedValue, $res);
	}

	public function NotYetParsableExpressionsProvider()
	{
		$aExpressions = [
			["new \\FunctionExpression('CONCAT_WS', array(new \\ScalarExpression(' '), new \\ScalarExpression('Hello'), new \ScalarExpression('world!')))", 'Hello world!'],
			["new \\ScalarExpression('windows\\system32')", 'windows\\system32'],
			["new \\BinaryExpression(new \\ScalarExpression('100%'), 'LIKE', new \\ScalarExpression('___\%'))", 1],
			["new \\BinaryExpression(new \ScalarExpression('1000'), 'LIKE', new \ScalarExpression('___\%'))", 0],
			// Net yet parsed - array("TIME(NOW()) = CURRENT_TIME()", 1), // Not relevant
			// Not yet parsed - array("DATE_ADD('2020-02-28 18:00:00', INTERVAL 1 WEEK)", '2020-03-06 18:00:00'),
			// Not yet parsed - array("DATE_SUB('2020-03-01 18:00:00', INTERVAL 1 WEEK)", '2020-02-23 18:00:00'),
			// Not yet parsed - array('ROUND(1.2345, 2)', 1.23),
			// Not yet parsed - array('FLOOR(1.2)', 1),
		];
		// Build a comprehensive index
		$aRet = [];
		foreach ($aExpressions as $aExp) {
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
		foreach (array_values($this->NotYetParsableExpressionsProvider()) as $i => $aTest) {
			$sNewExpression = "return {$aTest[0]};";
			$oExpression = eval($sNewExpression);
			$sExpression = $oExpression->RenderExpression(true);
			$aTests[] = [$sExpression, $aTest[1]];
		}

		$aExpressions = [];
		foreach ($aTests as $i => $aTest) {
			$aExpressions[] = "{$aTest[0]} as test_$i";
		}

		$sSelects = implode(', ', $aExpressions);
		$sQuery = "SELECT $sSelects";

		$this->debug($sQuery);
		$aResults = CMDBSource::QueryToArray($sQuery);

		foreach ($aTests as $i => $aTest) {
			$value = $aResults[0]["test_$i"];
			$expectedValue = $aTest[1];
			$this->debug("Test #$i: {$aTests[$i][0]} => ".var_export($value, true));
			$this->assertEquals($expectedValue, $value);
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

		$this->assertEquals($expected, $res);
	}

	public function ExpressionsWithObjectFieldsProvider()
	{
		return [
			['URP_UserProfile', ['profileid' => 2], 'friendlyname', ''],
			['Location', ['name' => 'Grenoble', 'org_id' => 2], 'name', 'Grenoble'],
			['Location', ['name' => 'Grenoble', 'org_id' => 2], 'friendlyname', ''],
			['Location', ['name' => 'Grenoble', 'org_id' => 2], 'org_name', 'IT Department'],
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
		$this->assertEquals($expected, $res);
	}

	public function ExpressionWithParametersProvider()
	{
		return [
			['`DBVariables["analyze_sample_percentage"]` > 10', ['DBVariables["analyze_sample_percentage"]' => 20], true],
			['`DataBase["DBDataSize"]`', ['DataBase["DBDataSize"]' => 4096], 4096],
			['`FileSystem["ItopInstallationIntegrity"]`', ['FileSystem["ItopInstallationIntegrity"]' => 'not_conform'], 'not_conform'],
			['`DBTablesInfo["attachment"].DataSize` > 100', ['DBTablesInfo["attachment"].DataSize' => 200], true],
			['`DBTablesInfo[].DataSize` > 100', ['DBTablesInfo[].DataSize' => 50], false],
			['(`DBTablesInfo[].DataSize` > 100) AND (`DBTablesInfo[].DataFree` * 100 / (`DBTablesInfo[].DataSize` + `DBTablesInfo[].IndexSize` + `DBTablesInfo[].DataFree`) > 10)', ['DBTablesInfo[].DataSize' => 200, 'DBTablesInfo[].DataFree' => 100, 'DBTablesInfo[].IndexSize' => 10], true],
			['CONCAT(SUBSTR(name, 4), " cause")', ['name' => 'noble'], 'le cause'],
		];
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
		if ($bExpectTrue) {
			$this->assertTrue($res, 'arg: '.$sExpression);
		} else {
			$this->assertFalse($res, 'arg: '.$sExpression);
		}
	}

	public function TrueExpressionsProvider()
	{
		$aExpressions = [
			['1', true],
			['0 OR 0', false],
			['1 AND 1', true],
			['1 AND (1 OR 0)', true],
		];
		// Build a comprehensive index
		$aRet = [];
		foreach ($aExpressions as $aExp) {
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
		$oExpression = new FunctionExpression('DATE_FORMAT', [new ScalarExpression($sDate), new ScalarExpression("%$sFormat")]);
		if ($bProcessed) {
			$sqlValue = CMDBSource::QueryToScalar("SELECT DATE_FORMAT('$sDate', '%$sFormat')");
			$this->assertEquals($sqlValue, $sValueOrException, 'Check test against MySQL');

			$res = $oExpression->Evaluate([]);
			$this->assertEquals($sValueOrException, $res, 'Check evaluation');
		} else {
			static::expectException($sValueOrException);
			$oExpression->Evaluate([]);
		}
	}

	public function TimeFormatsProvider()
	{
		$aTests = [
			['a', true, 'Thu'],
			['b', true, 'Jun'],
			['c', true, '6'],
			['D', true, '4th'],
			['d', true, '04'],
			['e', true, '4'],
			['f', false, 'NotYetEvaluatedExpression'], // microseconds: no way!
			['H', true, '21'],
			['h', true, '09'],
			['I', true, '09'],
			['i', true, '23'],
			['j', true, '155'], // day of the year
			['k', true, '21'],
			['l', true, '9'],
			['M', true, 'June'],
			['m', true, '06'],
			['p', true, 'PM'],
			['r', true, '09:23:24 PM'],
			['S', true, '24'],
			['s', true, '24'],
			['T', true, '21:23:24'],
			['U', false, 'NotYetEvaluatedExpression'], // Week sunday based (mode 0)
			['u', false, 'NotYetEvaluatedExpression'], // Week monday based (mode 1)
			['V', false, 'NotYetEvaluatedExpression'], // Week sunday based (mode 2)
			['v', true, '23'], // Week monday based (mode 3 - ISO-8601)
			['W', true, 'Thursday'],
			['w', true, '4'],
			['X', false, 'NotYetEvaluatedExpression'],
			['x', true, '2009'], // to be used with %v (ISO - 8601)
			['Y', true, '2009'],
			['y', true, '09'],
		];
		$aRes = [];
		foreach ($aTests as $aTest) {
			$aRes["Format %{$aTest[0]}"] = $aTest;
		}
		return $aRes;
	}

	/**
	 * For a given date,
	 * for all different formats (1st array element returned by {@see static::TimeFormatsProvider}),
	 * compare value returned by :
	 *   * DATE_FORMAT() SQL function,
	 *   * FunctionExpression('DATE_FORMAT', ...) result
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
		$aSelects = [];
		foreach ($aFormats as $sFormatDesc => $aFormatSpec) {
			$sFormat = $aFormatSpec[0];
			$bProcessed = $aFormatSpec[1];
			if ($bProcessed) {
				$aSelects["%$sFormat"] = "DATE_FORMAT('$sDate', '%$sFormat') AS `$sFormat`";
			}
		}
		$sSelects = "SELECT ".implode(', ', $aSelects);
		$aRes = CMDBSource::QueryToArray($sSelects);
		/** @var array $aMysqlDateFormatRsultsForAllFormats format as key, MySQL evaluated result as value */
		$aMysqlDateFormatRsultsForAllFormats = $aRes[0];
		foreach ($aFormats as $sFormatDesc => $aFormatSpec) {
			$sFormat = $aFormatSpec[0];
			$bProcessed = $aFormatSpec[1];
			if ($bProcessed) {
				$oExpression = new FunctionExpression('DATE_FORMAT', [new ScalarExpression($sDate), new ScalarExpression("%$sFormat")]);
				$itopExpressionResult = $oExpression->Evaluate([]);
				$this->assertSame($aMysqlDateFormatRsultsForAllFormats[$sFormat], $itopExpressionResult, "Format %$sFormat not matching MySQL for '$sDate'");
			}
		}
	}
	public function EveryTimeFormatProvider()
	{
		return [
			['1971-07-19 8:40:00'],
			['1999-12-31 23:59:59'],
			['2000-01-01 00:00:00'],
			['2009-06-04 21:23:24'],
			['2020-02-29 23:59:59'],
			['2030-10-21 23:59:59'],
			['2050-12-21 23:59:59'],
		];
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
		for ($i = 0 ; $i < $iRepeat ; $i++) {
			$sDate = date_format($oDate, 'Y-m-d H:i:s');
			$this->debug("Checking '$sDate'");
			$this->testEveryTimeFormat($sDate);
			$oDate->add(new DateInterval($sInterval));
		}
	}

	public function EveryTimeFormatOnDateRangeProvider()
	{
		return [
			'10 years, each 17 days' => ['2000-01-01', 'P17D', 365 * 10 / 17],
			'1 day, hour by hour' => ['2000-01-01 00:01:02', 'PT1H', 24],
			'1 hour, minute by minute' => ['2000-01-01 00:01:02', 'PT1M', 60],
			'1 minute, second by second' => ['2000-01-01 00:01:02', 'PT1S', 60],
		];
	}
}

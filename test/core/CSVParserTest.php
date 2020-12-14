<?php

namespace Combodo\iTop\Test\UnitTest\Core;

use Combodo\iTop\Test\UnitTest\ItopTestCase;
use CSVParser;


class CSVParserTest extends ItopTestCase
{
	protected function setUp()
	{
		parent::setUp();

		require_once(APPROOT.'core/csvparser.class.inc.php');
		require_once(APPROOT.'core/coreexception.class.inc.php');
	}

	public function testFile()
	{
		$sSeparator = ';';
		$sDelimiter = '?';
		$sDataFile = '?field1?;?field2?;?field3?
?line 0, col 0?;?line 0, col 1?;?line 0, col 2?
a;b;c
a;b;<NULL>
 ? a ? ; ? b ? ; ? c ? 
 a ; b ; c 
??;??;??
;;
?a"?;?b?;?c?
?a1
a2?;?b?;?c?
?a1,a2?;?b?;?c?
?a?;?b?;?c1,",c2
,c3?
?a?;?b?;?ouf !?
    spaces trimmed out ; 1234; mac@enroe.com ';

		$aExpectedResult = array(
			array('line 0, col 0', 'line 0, col 1', 'line 0, col 2'),
			array('a', 'b', 'c'),
			array('a', 'b', null),
			array(' a ', ' b ', ' c '),
			array('a', 'b', 'c'),
			array('', '', ''),
			array('', '', ''),
			array('a"', 'b', 'c'),
			array("a1\na2", 'b', 'c'),
			array('a1,a2', 'b', 'c'),
			array('a', 'b', "c1,\",c2\n,c3"),
			array('a', 'b', 'ouf !'),
			array('spaces trimmed out', '1234', 'mac@enroe.com'),
		);

		$oCSVParser = new CSVParser($sDataFile, $sSeparator, $sDelimiter);
		$aData = $oCSVParser->ToArray(1, null, 0);

		foreach ($aData as $iRow => $aRow)
		{
			foreach ($aRow as $iCol => $cellValue)
			{
				$this->assertSame($aExpectedResult[$iRow][$iCol], $cellValue, "Line $iRow, Column $iCol");
			}
		}
	}
}


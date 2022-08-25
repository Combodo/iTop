<?php

namespace Combodo\iTop\Test\UnitTest\Core;

use AttributeURLDefaultPattern;
use Combodo\iTop\Test\UnitTest\ItopTestCase;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class AttributeURLTest extends ItopTestCase {
	public function setUp(): void
	{
		parent::setUp();
		require_once APPROOT.'core/attributedef.class.inc.php';
		require_once APPROOT.'test/core/AttributeURLDefaultPattern.php';
	}

	/**
	 * @throws \Exception
	 * @dataProvider CheckFormatProvider
	 */
	public function testCheckFormat(string $sUrlValue, int $iExpectedResult): void
	{
		$oAttDefUrl = new AttributeURLDefaultPattern('myCode', ["target"=>'_blank', "allowed_values"=>null, "sql"=>'url', "default_value"=>'', "is_null_allowed"=>true, "depends_on"=>array(), "always_load_in_tables"=>false]);
		$bResult = $oAttDefUrl->CheckFormat($sUrlValue);

		$this->assertSame($iExpectedResult, $bResult);
	}

	public function CheckFormatProvider(): array
	{
		return [
			'Simple https URL' => ['https://www.combodo.com/itop', 1],
			'Simple FTP URL' => ['ftp://user:password@myftp.mydomain.com', 1],
			'Sharepoint URL 1' => ['https://mydomain1.sharepoint.com/:i:/r/sites/DSIMyDept/Shared%20Documents/Architecture%20Technique/02%20-%20R%C3%A9seau/Baie%2025C/Baie%201er/Baie-25C-1er.jpg?csf=1&web=1&e=Il3txR', 1],
			'Sharepoint URL 2' => ['https://mydomain2.sharepoint.com/:u:/r/sites/DIS/ITSM/00_Admin_iTOP/iTop%20-%20Upgrade%20manuel/Procedure%20upgrade%20Combodo.url?csf=1&web=1&e=DAF0i3', 1],
			'Alfresco URL 2' => ['http://alfresco.mydomain3.org/share/page/site/books/document-details?nodeRef=workspace://SpacesStore/6274f55f-a25b-4762-a863-77f7066f2034', 1],
			'SF URL' => ['https://sourceforge.net/p/itop/discussion/customizing-itop/thread/707145b859/?limit=25#f53c', 1],
			'SF URL anchor starting with digit' => ['https://sourceforge.net/p/itop/discussion/customizing-itop/thread/b0a2d474ba/?limit=25#2b35', 1],
		];
	}
}

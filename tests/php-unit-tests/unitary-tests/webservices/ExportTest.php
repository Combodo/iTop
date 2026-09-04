<?php

namespace Combodo\iTop\Test\UnitTest\Webservices;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use MetaModel;
use QueryOQL;

class ExportTest extends ItopDataTestCase
{
	public const USE_TRANSACTION = false;
	private $sPassword = "abcDEF12345##";
	private $sLogin;
	private $sPortalLogin;

	protected function setUp(): void
	{
		parent::setUp();

		require_once(APPROOT.'application/startup.inc.php');
		$sUid = date('dmYHis');
		$this->sLogin = "import-".$sUid;
		$this->CreateContactlessUser($this->sLogin, self::$aURP_Profiles['Administrator'], $this->sPassword);
		$this->sPortalLogin = "import-portaluser-".$sUid;
		$this->CreateContactlessUser($this->sPortalLogin, self::$aURP_Profiles['Portal user'], $this->sPassword);
	}

	public function testExportWithExpressionAndFields()
	{
		$aParams = [
			'expression' => 'SELECT User',
			'fields' => 'login',
			'format' => 'csv',
			'filename' => 'toto.csv',
			'charset' => 'UTF-8',
		];
		$sOutput = $this->performExportTesting($aParams, $this->sLogin);

		$sExpectedHeader = <<<HEADER
"Login"

HEADER;

		$this->assertStringContainsString($sExpectedHeader, $sOutput, "Header ($sExpectedHeader)\n should be in export-v2 answer: \n$sOutput");
		$this->assertStringContainsString($this->sLogin, $sOutput, "Login ({$this->sLogin})\n should be in export-v2 answer: \n$sOutput");
	}

	public function testExportWithExpressionAndWithoutFields()
	{
		$aParams = [
			'expression' => 'SELECT User',
			'format' => 'csv',
			'filename' => 'toto.csv',
			'charset' => 'UTF-8',
		];
		$sOutput = $this->performExportTesting($aParams, $this->sLogin);

		$sExpectedHeader = <<<HEADER
"Person","Last name","First name","Email","Organization","Login","Language","Status","log","Type of account","Full name","Person","Person->Obsolete","Person->Organization","Person->Organization->Obsolete"

HEADER;

		$this->assertStringContainsString($sExpectedHeader, $sOutput, "Header ($sExpectedHeader)\n should be in export-v2 answer: \n$sOutput");
		$this->assertTrue(false !== strpos($sOutput, $this->sLogin), "Login ({$this->sLogin})\n should be in export-v2 answer: \n$sOutput");
	}

	public function testExportWithOQLRequestAndWithoutFields()
	{
		$oOuery = $this->createObject(
			QueryOQL::class,
			[
				'name' => "TestExport-".uniqid(),
				'description' => "blabla",
				"oql" => "SELECT User",
			]
		);

		$aParams = [
			'query' => $oOuery->GetKey(),
			'format' => 'spreadsheet',
			'filename' => 'toto.csv',
			'charset' => 'UTF-8',
		];
		$sOutput = $this->performExportTesting($aParams, $this->sLogin);

		$sExpectedHeader = <<<HEADER
<td>Person</td>
<td>Last name</td>
<td>First name</td>
<td>Email</td>
<td>Organization</td>
<td>Login</td>
<td>Language</td>
<td>Status</td>
<td>log</td>
<td>Type of account</td>
<td>Full name</td>
<td>Person</td>
<td>Person->Obsolete</td>
<td>Person->Organization</td>
<td>Person->Organization->Obsolete</td>
HEADER;
		$this->assertStringContainsString("$sExpectedHeader", $sOutput, "Header ($sExpectedHeader)\n should be in export-v2 answer: \n$sOutput");

		$this->assertStringContainsString($this->sLogin, $sOutput, "Login ({$this->sLogin})\n should be in export-v2 answer: \n$sOutput");
	}

	public function testExportWithOqlRequestAndWithoutFieldsButNoReadRights()
	{
		$oOuery = $this->createObject(
			QueryOQL::class,
			[
				'name' => "TestExport-".uniqid(),
				'description' => "blabla",
				"oql" => "SELECT User",
			]
		);

		$aParams = [
			'query' => $oOuery->GetKey(),
			'format' => 'csv',
			'filename' => 'toto.csv',
			'charset' => 'UTF-8',
		];

		try {
			$this->performExportTesting($aParams, $this->sPortalLogin);
		} catch (\Exception $e) {
			$this->assertStringContainsString("PHP Error (parsing, or runtime) ", $e->getMessage());
			$this->assertStringContainsString("ERROR: Missing parameter: fields", $e->getMessage());
		}
	}

	public function testExportWithOQLRequestAndFields()
	{
		$oOuery = $this->createObject(
			QueryOQL::class,
			[
				'name' => "TestExport-".uniqid(),
				'description' => "blabla",
				"fields" => "login, status",
				"oql" => "SELECT User",
			]
		);

		$aParams = [
			'query' => $oOuery->GetKey(),
			'fields' => 'login',
			'format' => 'spreadsheet',
			'filename' => 'toto.csv',
			'charset' => 'UTF-8',
		];
		$sOutput = $this->performExportTesting($aParams, $this->sLogin);

		$sExpectedHeader = <<<HEADER
<td>Login</td>
HEADER;
		$this->assertStringContainsString("$sExpectedHeader", $sOutput, "Header ($sExpectedHeader)\n should be in export-v2 answer: \n$sOutput");

		$this->assertStringContainsString($this->sLogin, $sOutput, "Login ({$this->sLogin})\n should be in export-v2 answer: \n$sOutput");
	}

	public function testExportWithExpressionAndWithoutFieldsButNoReadRights()
	{
		$aParams = [
			'expression' => 'SELECT User',
			'format' => 'csv',
			'filename' => 'toto.csv',
			'charset' => 'UTF-8',
		];

		try {
			$this->performExportTesting($aParams, $this->sPortalLogin);
		} catch (\Exception $e) {
			$this->assertStringContainsString("PHP Error (parsing, or runtime) ", $e->getMessage());
			$this->assertStringContainsString("ERROR: Missing parameter: fields", $e->getMessage());
		}
	}

	private function performExportTesting(array $aParams, $sLogin, $iExpectedExitCode = 0)
	{
		$aRes = \utils::ExecITopScript('webservices/export-v2.php', $aParams, $sLogin, $this->sPassword);
		$aOutput = $aRes[1];
		$sOutput = implode("\n", $aOutput);
		$iRes = $aRes[0];
		$this->assertEquals($iExpectedExitCode, $iRes, "exit code: $iRes | $sOutput");
		return $sOutput;
	}

}

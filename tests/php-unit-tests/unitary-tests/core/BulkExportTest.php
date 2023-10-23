<?php

/**
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Test\UnitTest\Core;


use BulkExport;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use DBObjectSearch;

class BulkExportTest extends ItopDataTestCase
{
	const CREATE_TEST_ORG = true;


	public function OrganizationsForExportProvider()
	{
		$sExportResultPage1 = <<<EOF
"Name"
"org1"
"org11"
"org13"
"org14"
"org2"

EOF;

		$sExportResultPage2 = <<<EOF
"Name"
"org1"
"org11"
"org13"
"org14"
"org2"
"org4"
"org5"
"org7"
"org8"

EOF;

		return [
			'Page1'=>[
				'list_org' => [
					['org1', true],
					['org2', true],
					['org3', false],
					['org4', true],
					['org5', true],
					['org6', false],
					['org7', true],
					['org8', true],
					['org9', false],
					['org11', true],
					['org12', false],
					['org13', true],
					['org14', true],
				],
				'export_org' => $sExportResultPage1,
				'nb_pages' => 1,
				'expected_status' =>'run'
			],
			'Page2'=>[
				'list_org' => [
					['org1', true],
					['org2', true],
					['org3', false],
					['org4', true],
					['org5', true],
					['org6', false],
					['org7', true],
					['org8', true],
					['org9', false],
					['org11', true],
					['org12', false],
					['org13', true],
					['org14', true],
				],
				'export_org' => $sExportResultPage2,
				'nb_pages' => 2,
				'expected_status' =>'done'
			]
		];
	}

	/**
	 * @dataProvider OrganizationsForExportProvider
	 *
	 * @param $aListOrg
	 * @param $sExpectedValue
	 * @param $iNbPage
	 * @param $sExpectedStatus
	 *
	 * @throws \OQLException
	 * @throws \ReflectionException
	 */
	public function testExportWithShowObsoleteParam($aListOrg, 
 $sExpectedValue, $iNbPage, $sExpectedStatus)
	{
		// Create tests organizations to have enough data (some obsolete)
		$iFirstOrg = 0;
		foreach ($aListOrg as $aOrg) {
			$oObj = $this->CreateOrganization($aOrg[0]);
			if ($aOrg[1] === false) {
				$oObj->Set('status', 'inactive');
				$oObj->DBUpdate();
			}
			if($iFirstOrg === 0){
				$iFirstOrg = $oObj->GetKey();
			}
		}
		
		$aResult = [
			// Fallback error, just in case
			'code' => 'error',
			'percentage' => 100,
			'message' => "Export not found for token",
		];
		
		// Prepare status info and for obsolete data to `false` in order to check that we have less organizations
		// in the export result than we have in DB
		$aStatusInfo = [
			"fields" => [
				[
					"sFieldSpec" => "name",
					"sAlias" => "Organization",
					"sClass" => "Organization",
					"sAttCode" => "name",
					"sLabel" => "Name",
					"sColLabel" => "Name"
				]
			],
		    "text_qualifier" => "\"",
		    "charset" => "ISO-8859-1",
		    "separator" => ",",
		    "date_format" => "Y-m-d H:i:s",
		    "formatted_text" => false,
		    "show_obsolete_data" => false
		];

		$oSearch = DBObjectSearch::FromOQL('SELECT Organization  WHERE id >= '.$iFirstOrg);
		$oExporter = BulkExport::FindExporter('csv', $oSearch);
		$oExporter->SetStatusInfo($aStatusInfo);
		$oExporter->SetObjectList($oSearch);
		$oExporter->SetChunkSize(5);

		$data = $oExporter->GetHeader();

		for ($i = 0; $i < $iNbPage; $i++) {
			$data .= $oExporter->GetNextChunk($aResult);
		}
		$this->assertEquals($sExpectedStatus,$aResult['code']);
		$this->assertEquals($sExpectedValue, $data);
	}

}

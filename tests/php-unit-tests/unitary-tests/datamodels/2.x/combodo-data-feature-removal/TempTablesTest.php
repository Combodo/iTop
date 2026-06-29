<?php

namespace Combodo\iTop\Test\UnitTest\Module\DataFeatureRemoval;

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

use CMDBSource;
use Combodo\iTop\DataFeatureRemoval\Entity\DataCleanupSummaryEntity;
use Combodo\iTop\DataFeatureRemoval\Service\StaticDeletionPlan;

class TempTablesTest extends \AbstractCleanup
{
	public function GetDatamodelDeltaAbsPath(): string
	{
		return __DIR__.'/data_cleanup_delta.xml';
	}

	public function testMultipleExtKeys()
	{
		echo "--------------------------------------------\n".__METHOD__."\n";

		$this->GivenDFRObjectsInDB(<<<EOF
			create DFRC01 (name = DFRC01_1)
			create DFRC01 (name = DFRC01_2)
			create DFRC01 (name = DFRC01_3)
			create DFRC01 (name = DFRC01_4)
			create DFRC01 (name = DFRC01_5)
		
			create DFRC3 (name = DFRC3_1, extkey1_id = DFRC01_1)
			create DFRC3 (name = DFRC3_2, extkey1_id = DFRC01_2)
			create DFRC3 (name = DFRC3_3, extkey1_id = DFRC01_3)
						
			create DFRC21 (name = DFRC21_1, extkey1_id = DFRC01_4, extkey2_id = DFRC01_5, extkey3_id = DFRC3_3)
			
			create DFRC4 (name = DFRC4_1, extkey1_id = DFRC21_1)
			
			update DFRC3 (name = DFRC3_2, extkey2_id = DFRC4_1)
		EOF);

		$aClasses = [ 'DFRC01' ];
		$oService = new StaticDeletionPlan();

		$aTempTables = $oService->GetTempTableDefinitions($aClasses);
		echo json_encode($aTempTables, JSON_PRETTY_PRINT)."\n";

		self::assertTrue(true);
	}

	public function testMultipleInitialSubClassesFromSameRoot()
	{

		echo "--------------------------------------------\n".__METHOD__."\n";
		$this->GivenDFRObjectsInDB(<<<EOF
			create DFRC01 (name = DFRC01_1)
			create DFRC01 (name = DFRC01_2)
			create DFRC01 (name = DFRC01_3)
			create DFRC01 (name = DFRC01_4)
			create DFRC01 (name = DFRC01_5)
		
			create DFRC02 (name = DFRC02_1)
			create DFRC02 (name = DFRC02_2)
			create DFRC02 (name = DFRC02_3)
		
			create DFRC03 (name = DFRC03_1)
			create DFRC03 (name = DFRC03_2)
			create DFRC03 (name = DFRC03_3)
			
			create DFRC3 (name = DFRC3_1, extkey1_id = DFRC01_1)
			create DFRC3 (name = DFRC3_2, extkey1_id = DFRC01_2)
			create DFRC3 (name = DFRC3_3, extkey1_id = DFRC01_3)
			
			create DFRC3 (name = DFRC3_4, extkey1_id = DFRC02_1)
			create DFRC3 (name = DFRC3_5, extkey1_id = DFRC02_2)
			create DFRC3 (name = DFRC3_6, extkey1_id = DFRC02_3)
					
			create DFRC3 (name = DFRC3_7, extkey1_id = DFRC03_1)
			create DFRC3 (name = DFRC3_8, extkey1_id = DFRC03_2)
			create DFRC3 (name = DFRC3_9, extkey1_id = DFRC03_3)
						
			create DFRC21 (name = DFRC21_1, extkey1_id = DFRC01_4, extkey2_id = DFRC01_5, extkey3_id = DFRC3_3)
			
			create DFRC22 (name = DFRC22_1, extkey1_id = DFRC03_1)
			create DFRC22 (name = DFRC22_2, extkey1_id = DFRC03_1)
			
			create DFRC4 (name = DFRC4_1, extkey1_id = DFRC21_1)
			create DFRC4 (name = DFRC4_2, extkey1_id = DFRC22_1)
			create DFRC4 (name = DFRC4_3, extkey1_id = DFRC22_2)
			
			update DFRC3 (name = DFRC3_2, extkey2_id = DFRC4_1)
			update DFRC3 (name = DFRC3_5, extkey2_id = DFRC4_2)
			update DFRC3 (name = DFRC3_6, extkey2_id = DFRC4_3)

		EOF);

		$aClasses = [ 'DFRC01', 'DFRC03' ];
		$oService = new StaticDeletionPlan();

		$aTempTables = $oService->GetTempTableDefinitions($aClasses);
		echo json_encode($aTempTables, JSON_PRETTY_PRINT)."\n";

		$this->GetIds($aTempTables);

		$aRes = $oService->GetStaticDeletionPlan($aClasses);
		echo "\nDeletionPlan: ".json_encode($aRes, JSON_PRETTY_PRINT)."\n";

		self::assertTrue(true);
	}

	private function GetIds(array $aTempTables): void
	{
		foreach ($aTempTables as $sDBName => $aQueries) {
			foreach ($aQueries as $sQuery) {
				CMDBSource::Query($sQuery);
			}

			$aIds = CMDBSource::QueryToCol("SELECT id FROM `$sDBName`", 'id');
			echo "\n$sDBName: ".implode(', ', $aIds)."\n";
		}
	}

}

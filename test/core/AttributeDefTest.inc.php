<?php

namespace Combodo\iTop\Test\UnitTest\Core;

use CMDBSource;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use MetaModel;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class AttributeDefTest extends ItopDataTestCase {
	const CREATE_TEST_ORG = true;

	protected function setUp() {
		parent::setUp();
		require_once(APPROOT.'core/attributedef.class.inc.php');

	}

	public function testGetImportColumns(){
		$oAttributeDefinition = MetaModel::GetAttributeDef("ApplicationSolution", "status");
		$aImportColumns = $oAttributeDefinition->GetImportColumns();
		var_dump($aImportColumns);

		$this->assertTrue(is_array($aImportColumns), var_export($aImportColumns, true));
		$this->assertEquals(["status" => "ENUM('active','inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"],
			$aImportColumns);
	}

}
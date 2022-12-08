<?php
/*
 * @copyright   Copyright (C) 2010-2022 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

use Combodo\iTop\Test\UnitTest\ItopTestCase;

class DatamodelsXmlFilesTest extends ItopTestCase
{
	protected function setUp(): void
	{
		parent::setUp();
		require_once APPROOT.'.make/release/update.classes.inc.php';
		require_once APPROOT.'setup/itopdesignformat.class.inc.php';
	}

	public function testGetDesignVersionToSet() {
		$sVersionFor10 = $this->InvokeNonPublicStaticMethod(DatamodelsXmlFiles::class, 'GetDesignVersionToSet', ['1.0']);
		$this->assertNull($sVersionFor10);
		$sVersionFor26 = $this->InvokeNonPublicStaticMethod(DatamodelsXmlFiles::class, 'GetDesignVersionToSet', ['2.6']);
		$this->assertNull($sVersionFor26);
		$sVersionFor27 = $this->InvokeNonPublicStaticMethod(DatamodelsXmlFiles::class, 'GetDesignVersionToSet', ['2.7']);
		$this->assertNull($sVersionFor27);

		$sPreviousDesignVersion = iTopDesignFormat::GetPreviousDesignVersion(ITOP_DESIGN_LATEST_VERSION);
		$sVersionForLatest = $this->InvokeNonPublicStaticMethod(DatamodelsXmlFiles::class, 'GetDesignVersionToSet', [ITOP_DESIGN_LATEST_VERSION]);
		$this->assertNotNull($sVersionForLatest);
		$this->assertSame($sPreviousDesignVersion, $sVersionForLatest);
	}
}

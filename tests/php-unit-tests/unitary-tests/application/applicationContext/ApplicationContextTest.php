<?php

namespace applicationContext;

/**
 * @covers ApplicationContext
 */
class ApplicationContextTest extends \Combodo\iTop\Test\UnitTest\ItopTestCase
{
	public function testGetForLink()
	{
		$oApplicationContext = new MockApplicationContext([
			'org_id' => '3',
			'menu' => 'TargetOverview',
		]);

		$sExpected = '&c[org_id]=3&c[menu]=TargetOverview';
		$sActual = $oApplicationContext->GetForLink(true);
		$this->assertEquals($sExpected, $sActual, 'Query parameters string should include all request parameters prefixed with &');

		$sExpected = 'c[org_id]=3&c[menu]=TargetOverview';
		$sActual = $oApplicationContext->GetForLink();
		$this->assertEquals($sExpected, $sActual, 'Query parameters string should not start with & when $bIncludeAmpersand is false');
	}

}

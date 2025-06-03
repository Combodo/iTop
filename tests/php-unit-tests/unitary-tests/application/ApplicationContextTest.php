<?php

/**
 * @covers ApplicationContext
 */
class ApplicationContextTest extends \Combodo\iTop\Test\UnitTest\ItopTestCase
{
	public function testGetQueryParametersString()
	{
		$_REQUEST['c']['menu'] = 'TargetOverview';
		$_REQUEST['c']['org_id'] = '3';
		$oApplicationContext = new ApplicationContext(true);

		$sExpected = '&c[org_id]=3&c[menu]=TargetOverview';
		$sActual = $oApplicationContext->GetQueryParametersString();
		$this->assertEquals($sExpected, $sActual, 'Query parameters string should include all request parameters prefixed with &');

		$sExpected = 'c[org_id]=3&c[menu]=TargetOverview';
		$sActual = $oApplicationContext->GetQueryParametersString(false);
		$this->assertEquals($sExpected, $sActual, 'Query parameters string should not start with & when $bIncludeAmpersand is false');
	}

}
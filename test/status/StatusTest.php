<?php

/**
 * User: Guy Couronné (guy.couronne@gmail.com)
 * Date: 25/01/2019
 */

namespace Combodo\iTop\Test\UnitTest\Status;

use Combodo\iTop\Test\UnitTest\ItopTestCase;

class StatusTest extends ItopTestCase
{
	public function setUp(): void
	{
		parent::setUp();
		require_once APPROOT.'core/config.class.inc.php'; // for constants
	}

    public function testStatusWrongUrl() {
		$sPath = APPROOT.'/status_wrong.php';

        exec("php $sPath", $aOutput, $iRet);
        $this->assertNotEquals(0, $iRet, "Problem executing status page: $sPath, $iRet, aOutput:\n" . var_export($aOutput, true));

    }

    public function testStatusGood() {
		$sPath = APPROOT.'/webservices/status.php';

		exec("php $sPath", $aOutput, $iRet);
		$this->assertEquals(0, $iRet, "Problem executing status page: $sPath, $iRet, aOutput:\n".var_export($aOutput, true));
	}

	/**
	 *
	 */
	public function testStatusGoodWithJson()
	{
		$sPath = APPROOT.'/webservices/status.php';

		exec("php $sPath", $aOutput, $iRet);
		$sAdditionalInfo = "aOutput:\n".var_export($aOutput, true).'.';

		//Check response
		$this->assertNotEmpty($aOutput[0], 'Empty response. '.$sAdditionalInfo);
		$this->assertJson($aOutput[0], 'Not a JSON. '.$sAdditionalInfo);

		$aResponseDecoded = json_decode($aOutput[0], true);

		//Check status
		$this->assertArrayHasKey('status', $aResponseDecoded, 'JSON does not have a \'status\' field. '.$sAdditionalInfo);
		$this->assertEquals('RUNNING', $aResponseDecoded['status'], 'Status is not \'RUNNING\'. '.$sAdditionalInfo);
		//Check code
		$this->assertArrayHasKey('code', $aResponseDecoded, 'JSON does not have a \'code\' field. '.$sAdditionalInfo);
		$this->assertEquals(0, $aResponseDecoded['code'], 'Code is not 0. '.$sAdditionalInfo);
		//Check message
		$this->assertArrayHasKey('message', $aResponseDecoded, 'JSON does not have a \'message\' field. '.$sAdditionalInfo);
		$this->assertEmpty($aResponseDecoded['message'], 'Message is not empty. '.$sAdditionalInfo);
	}

}

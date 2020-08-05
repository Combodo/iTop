<?php

/**
 * User: Guy Couronné (guy.couronne@gmail.com)
 * Date: 25/01/2019
 */

namespace Combodo\iTop\Test\UnitTest\Status;

use Combodo\iTop\Test\UnitTest\ItopTestCase;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class StatusTest extends ItopTestCase {

    /**
     * 
     */
    public function testStatusWrongUrl() {
        $sPath = __DIR__ . '/status_wrong.php';

        exec("php $sPath", $aOutput, $iRet);
        $this->assertNotEquals(0, $iRet, "Problem executing status page: $sPath, $iRet, aOutput:\n" . var_export($aOutput, true));

    }

    /**
     * 
     */
    public function testStatusGood() {
	    $sPath = __DIR__ . '/status.php';

	    exec("php $sPath", $aOutput, $iRet);
	    $this->assertEquals(0, $iRet, "Problem executing status page: $sPath, $iRet, aOutput:\n" . var_export($aOutput, true));
    }

    /**
     * 
     */
    public function testStatusGoodWithJson() {
	    $sPath = __DIR__ . '/status.php';

	    exec("php $sPath", $aOutput, $iRet);
	    $sAdditionnalInfo = "aOutput:\n" . var_export($aOutput, true);

        //Check response
        $this->assertNotEmpty($aOutput[0], 'Empty response. ' . $sAdditionnalInfo);
        $this->assertJson($aOutput[0], 'Not a JSON. ' . $sAdditionnalInfo);

        $aResponseDecoded = json_decode($aOutput[0], true);

        //Check status
        $this->assertArrayHasKey('status', $aResponseDecoded, 'JSON does not have a status\' field. ' . $sAdditionnalInfo);
        $this->assertEquals('RUNNING', $aResponseDecoded['status'], 'Status is not \'RUNNING\'. ' . $sAdditionnalInfo);
        //Check code
        $this->assertArrayHasKey('code', $aResponseDecoded, 'JSON does not have a code\' field. ' . $sAdditionnalInfo);
        $this->assertEquals(0, $aResponseDecoded['code'], 'Code is not 0. ' . $sAdditionnalInfo);
        //Check message
        $this->assertArrayHasKey('message', $aResponseDecoded, 'JSON does not have a message\' field. ' . $sAdditionnalInfo);
        $this->assertEmpty($aResponseDecoded['message'], 'Message is not empty. ' . $sAdditionnalInfo);
    }

}

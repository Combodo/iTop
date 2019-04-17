<?php

/**
 * User: Guy Couronné (guy.couronne@gmail.com)
 * Date: 25/01/2019
 */

namespace Combodo\iTop\Test\UnitTest\Status;

use Combodo\iTop\Test\UnitTest\ItopTestCase;

/**
 * 
 */
class StatusTest extends ItopTestCase {

    /**
     * 
     */
    public function testStatusWrongUrl() {
        $sPath = APPROOT . 'status_wrong.php';

        exec("php $sPath", $aOutput, $iRet);

        $this->assertNotEquals(0, $iRet, "Problem executing status page: $sPath, $iRet");
    }

    /**
     * 
     */
    public function testStatusGood() {
	    $sPath = APPROOT . 'status.php';

	    exec("php $sPath", $aOutput, $iRet);

	    $this->assertEquals(0, $iRet, "Problem executing status page: $sPath, $iRet");
    }

    /**
     * 
     */
    public function testStatusGoodWithJson() {
	    $sPath = APPROOT . 'status.php';

	    exec("php $sPath", $aOutput, $iRet);

        //Check response
        $this->assertNotEmpty($aOutput[0], 'Empty response');
        $this->assertJson($aOutput[0], 'Not a JSON');

        $aResponseDecoded = json_decode($aOutput[0], true);

        //Check status
        $this->assertArrayHasKey('status', $aResponseDecoded, 'JSON does not have a status\' field');
        $this->assertEquals('RUNNING', $aResponseDecoded['status'], 'Status is not \'RUNNING\'');
        //Check code
        $this->assertArrayHasKey('code', $aResponseDecoded, 'JSON does not have a code\' field');
        $this->assertEquals(0, $aResponseDecoded['code'], 'Code is not 0');
        //Check message
        $this->assertArrayHasKey('message', $aResponseDecoded, 'JSON does not have a message\' field');
        $this->assertEmpty($aResponseDecoded['message'], 'Message is not empty');
    }

}

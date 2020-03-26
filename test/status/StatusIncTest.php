<?php

/**
 * User: Guy Couronné (guy.couronne@gmail.com)
 * Date: 25/01/2019
 */

namespace Combodo\iTop\Test\UnitTest\Status;

/**
 * User: Guy Couronné (guy.couronne@gmail.com)
 * Date: 25/01/2019
 */

use Config;
use PHPUnit\Framework\TestCase;
use function Combodo\iTop\Application\Status\StatusCheckConfigFile;
use function Combodo\iTop\Application\Status\StatusGetAppRoot;
use function Combodo\iTop\Application\Status\StatusStartup;

if (!defined('DEBUG_UNIT_TEST')) {
    define('DEBUG_UNIT_TEST', true);
}

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class StatusIncTest extends TestCase {

    /**
     *
     * @var string 
     */
    protected $sAppRoot = '';

    /**
     * 
     */
    protected function setUp() {
        //AppRoot is the directory containing the directory 
        //Assume getcwd() is runned inside APPROOT/test
        $this->sAppRoot = __DIR__ . '/../../sources/application/status';
    }

    /**
     * @expectedException \Exception
     */
    public function testStatusGetAppRootWrongPath() {
        include_once($this->sAppRoot . '/status.inc.php');

        $sAppRootFilenamewrong = 'approot.inc.php_wrong';

        StatusGetAppRoot($sAppRootFilenamewrong);
    }

    /**
     * 
     */
    public function testStatusGetAppRootGood() {
        include_once($this->sAppRoot . '/status.inc.php');

        StatusGetAppRoot();

        $this->assertNotEmpty(APPROOT);
    }

    /**
     * @expectedException \Exception
     */
    public function testStatusCheckConfigFileWrongPath() {
        include_once($this->sAppRoot . '/status.inc.php');

        $sConfigFilenamewrong = 'config-itop.php_wrong';

        StatusCheckConfigFile($sConfigFilenamewrong);
    }

    /**
     * 
     */
    public function testStatusCheckConfigFileGood() {
        include_once($this->sAppRoot . '/status.inc.php');

        StatusCheckConfigFile();

        $this->assertTrue(true);
    }

    /**
     * @expectedException \MySQLException
     */
    public function testStatusStartupWrongDbPwd() {
        include_once($this->sAppRoot . '/status.inc.php');

        StatusCheckConfigFile();
        require_once(APPROOT . '/core/cmdbobject.class.inc.php');
        require_once(APPROOT . '/application/utils.inc.php');
        require_once(APPROOT . '/core/contexttag.class.inc.php');

        $oConfigWrong = new Config(ITOP_DEFAULT_CONFIG_FILE);
        $oConfigWrong->Set('db_pwd', $oConfigWrong->Get('db_pwd') . '_unittest');

        StatusStartup($oConfigWrong);
    }

    /**
     * 
     */
    public function testStatusStartupGood() {
        include_once($this->sAppRoot . '/status.inc.php');

        StatusStartup();

        $this->assertTrue(true);
    }

}

<?php
namespace Combodo\iTop\Test\UnitTest\Status;



use Combodo\iTop\Application\Status\Status;
use Combodo\iTop\Test\UnitTest\ItopTestCase;
use Config;
use Exception;
use MySQLException;
use function Combodo\iTop\Application\Status\StatusCheckConfigFile;
use function Combodo\iTop\Application\Status\StatusGetAppRoot;
use function Combodo\iTop\Application\Status\StatusStartup;

if (!defined('DEBUG_UNIT_TEST')) {
    define('DEBUG_UNIT_TEST', true);
}

class StatusIncTest extends ItopTestCase {

    /**
     * @var string
     */
    protected $sAppRoot = '';

	protected function setUp(): void
	{
		parent::setUp();
		$this->RequireOnceItopFile('sources/Application/Status/Status.php');
	}

	public function testStatusGetAppRootWrongPath() {
		$this->expectException(Exception::class);
		$sAppRootFilenamewrong = 'approot.inc.php_wrong';

		$oStatus = new Status();
		$this->InvokeNonPublicMethod(Status::class, "StatusGetAppRoot", $oStatus, [$sAppRootFilenamewrong]);
    }

    /**
     * 
     */
    public function testStatusGetAppRootGood() {
	    $oStatus = new Status();
	    $this->InvokeNonPublicMethod(Status::class, "StatusGetAppRoot", $oStatus, []);

        $this->assertNotEmpty(APPROOT);
    }

	public function testStatusCheckConfigFileWrongPath() {
		$this->expectException(Exception::class);
		$sConfigFilenamewrong = 'config-itop.php_wrong';

	    $oStatus = new Status();
	    $this->InvokeNonPublicMethod(Status::class, "StatusCheckConfigFile", $oStatus, [$sConfigFilenamewrong]);
    }

    public function testStatusCheckConfigFileGood() {
	    $oStatus = new Status();
	    $this->InvokeNonPublicMethod(Status::class, "StatusCheckConfigFile", $oStatus, []);

        $this->assertTrue(true);
    }

    /**
     * @runInSeparateProcess Required because Status constructor invokes MetaModel::Startup... which does nothing when already loaded
     */
	public function testStatusStartupWrongDbPwd()
    {
	    $this->RequireOnceItopFile('core/cmdbobject.class.inc.php');
	    $this->RequireOnceItopFile('application/utils.inc.php');
	    $this->RequireOnceItopFile('core/contexttag.class.inc.php');

	    $oConfigWrong = new Config(ITOP_DEFAULT_CONFIG_FILE);
	    $oConfigWrong->Set('db_pwd', $oConfigWrong->Get('db_pwd').'_unittest');
	    $this->expectException(MySQLException::class);
	    new Status($oConfigWrong);
    }

    public function testStatusStartupGood() {
	    $oStatus = new Status();
	    $this->InvokeNonPublicMethod(Status::class, "StatusStartup", $oStatus, []);

        $this->assertTrue(true);
    }

}

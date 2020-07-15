<?php
/**
 * Created by Bruno DA SILVA, working for Combodo
 * Date: 02/01/2020
 * Time: 14:43
 */

use Combodo\iTop\Test\UnitTest\ItopTestCase;

class iTopConfigParserTest extends ItopTestCase
{

	public function setUp()
	{
		parent::setUp();
		require_once APPROOT.'/core/iTopConfigParser.php';
	}


	/**
	 * @dataProvider ParserProvider
	 * @throws \Exception
	 */
	public function testMergeConf($sInitialConfig, $aExpectedVarsMap)
	{
		$oITopConfigParser = new iTopConfigParser($sInitialConfig);

		$this->assertEquals($aExpectedVarsMap, $oITopConfigParser->GetVarsMap());

	}

	public function ParserProvider()
	{

		return array(
			"test MySettings" => array(
				'sInitialConfig' => '<?php
					$a=1;
					$MySettings = array(
						"b" => $a
					);',
				'aExpectedVarsMap' => array(
					'MySettings' => array('b' => '$a'),
					'MyModuleSettings' => array(),
					'MyModules' => array(),
				)
			),

			"test MyModuleSettings" => array(
				'sInitialConfig' => '<?php
					$a=1;
					$MyModuleSettings = array(
						"b" => $a
					);',
				'aExpectedVarsMap' => array(
					'MySettings' => array(),
					'MyModuleSettings' => array('b' => '$a'),
					'MyModules' => array(),
				)
			),


			"test MyModules" => array(
				'sInitialConfig' => '<?php
					$a=1;
					$MyModules = array(
						"b" => $a
					);',
				'aExpectedVarsMap' => array(
					'MySettings' =>array(),
					'MyModuleSettings' => array(),
					'MyModules' => array('b' => '$a'),
				)
			),

			"test MyModules + MyModuleSettings " => array(
				'sInitialConfig' => '<?php
					$MyModules = array(
						"b" => $a
					);
					$MyModuleSettings = array(
						"e" => $d
					);',
				'aExpectedVarsMap' => array(
					'MySettings' =>array(),
					'MyModuleSettings' => array('e' => '$d'),
					'MyModules' => array('b' => '$a'),
				)
			),

			"test preserve gloabl + concatenation" => array(
				'sInitialConfig' => '<?php
					$a=1;
					$MyModules = array(
						"b" =>  $_SERVER["REQUEST_URI"] . "/toto"
					);',
				'aExpectedVarsMap' => array(
					'MySettings' =>array(),
					'MyModuleSettings' => array(),
					'MyModules' => array('b' => '$_SERVER["REQUEST_URI"] . "/toto"'),
				)
			),
			"test MyModules array of arrays" => array(
				'sInitialConfig' => '<?php 
					$a=1;
					$MyModules = array(
					  "date_and_time_format" => array (
						  "default" => 
						  array (
						   "date" => "Y-m-d",
						    "time" => "H:i:s",
						    "date_time" => "$date $time",
						  ),
						),
					);',
				'aExpectedVarsMap' => array(
					'MySettings' =>array(),
					'MyModuleSettings' => array(),
					'MyModules' => array(
						'date_and_time_format' => 'array("default" => array("date" => "Y-m-d", "time" => "H:i:s", "date_time" => "{$date} {$time}"))'
					),
				)
			),
		);
	}

	/**
	 * @doesNotPerformAssertions
	 *
	 * @throws \ConfigException
	 * @throws \CoreException
	 */
	public function testConfigWriteToFile()
	{
		$tmpConfigFileBeforePath = tempnam( '/tmp/', 'config-itop');
		$tmpConfigFileAfterPath = tempnam( '/tmp/', 'config-itop');

		//create new config file
		$sConfigFile = utils::GetConfig()->GetLoadedFile();
		utils::GetConfig()->WriteToFile($tmpConfigFileBeforePath);

		//add few dynamic configurations in MySettings section
		$tmpConfigContentBefore = file_get_contents($tmpConfigFileBeforePath);
		$expected_line = <<< CONF
	'app_root_url' => 'http://\$_SERVER[\\'SERVER_NAME\\']/iTop/',
CONF;
		//add few dynamic configurations in MyModuleSettings section
		$tmpConfigNewContentBefore = preg_replace('/.*\'app_root_url.*,/', $expected_line, $tmpConfigContentBefore);
		$expected_line = <<< CONF
	\$MyModuleSettings = array(
	'shadok_module' => array ('gabu' => '\$_SERVER[\\'ZOMEU\\']'),
CONF;
		$tmpConfigNewContentBefore = preg_replace('/\$MyModuleSettings = array\(/', $expected_line, $tmpConfigNewContentBefore);

		//add few dynamic configurations in MyModules section
		$expected_line = <<< CONF
	'addons' => array(
		'user rights' => 'addons/userrights/userrightsprofile.class.inc.php',
		'user rights2' => '\$_SERVER[\\'TEST\\']'
	),
CONF;
		$tmpConfigNewContentBefore = preg_replace('/.*\'addons.*/', $expected_line, $tmpConfigNewContentBefore);

		unlink($tmpConfigFileBeforePath);
		fwrite(fopen($tmpConfigFileBeforePath, 'w'), $tmpConfigNewContentBefore);

		//write same content again
		$config = new Config($tmpConfigFileBeforePath, true);
		$config->WriteToFile($tmpConfigFileAfterPath);

		//compare
		$tmpConfigContentBefore = file_get_contents($tmpConfigFileAfterPath);
		$tmpConfigContentAfter = file_get_contents($tmpConfigFileAfterPath);
		unlink($tmpConfigFileAfterPath);
		unlink($tmpConfigFileBeforePath);
		$this->assertEquals($tmpConfigContentBefore, $tmpConfigContentAfter);
	}

	/**
	 * @doesNotPerformAssertions
	 *
	 * @throws \ConfigException
	 * @throws \CoreException
	 */
	public function testConfigWriteToFile_FromScratchInstallation()
	{
		clearstatcache();
		$sConfigPath = utils::GetConfigFilePath();
		$tmpSavePath = tempnam( '/tmp/', 'config-itop');

		$conf_exists = is_file($sConfigPath);
		if ($conf_exists)
		{
			rename($sConfigPath, $tmpSavePath);
		}

		$oConfig = new Config($sConfigPath, false);
		try{
			$oConfig->WriteToFile();
			if ($conf_exists)
			{
				rename($tmpSavePath, $sConfigPath);
			}
		}catch(\Exception $e)
		{
			if ($conf_exists)
			{
				rename($tmpSavePath, $sConfigPath);
			}

			$this->assertTrue(false, "failed writetofile with no initial file: " . $e->getMessage());
		}

	}
}

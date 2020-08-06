<?php

namespace Combodo\iTop\Test\UnitTest\Setup;

use Combodo\iTop\Test\UnitTest\ItopTestCase;
use DOMDocument;
use iTopDesignFormat;


/**
 * Class iTopDesignFormatChecklistTest
 * Ticket 3061 - Automatically check the installation.xml consistency
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 *
 * @covers iTopDesignFormat
 *
 * @package Combodo\iTop\Test\UnitTest\Setup
 */
class iTopModuleXmlInstallationChecklistTest extends ItopTestCase
{
	protected function setUp()
	{
		parent::setUp();
	}

	/**
	 * make sure installation.xml is provided and respects XML format
	 */
	public function testInstallationXmlFormat()
	{
		$sInstallationXmlPath = APPROOT . 'datamodels/2.x/installation.xml';
		$this->assertTrue(is_file($sInstallationXmlPath), "$sInstallationXmlPath does not exist");

		$doc = new \DOMDocument();
		try{
			$doc->loadxml(file_get_contents($sInstallationXmlPath));
		}
		catch(\Exception $e)
		{
			$this->assertFalse(true, "$sInstallationXmlPath is not a valid XML content: "  . $e->getMessage());
		}
	}

	/**
	 * make sure installation.xml includes all packaged modules
	 */
	public function testAllModuleAreIncludedInInstallationXml()
	{
		$sInstallationXmlPath = APPROOT.'datamodels/2.x/installation.xml';
		$this->assertTrue(is_file($sInstallationXmlPath), "$sInstallationXmlPath does not exist");

		$sInstallationXmlContent = file_get_contents($sInstallationXmlPath);
		preg_match_all("|<module>(.*)</module>|", $sInstallationXmlContent, $aMatches);
		$aDeclaredModules =  [] ;
		if (!empty($aMatches))
		{
			foreach ($aMatches[1] as $sModule)
			{
				if (!array_key_exists($sModule, $aDeclaredModules))
				{
					$aDeclaredModules[$sModule] = $sModule;
				}
			}
		}

		$aModulesFromDatamodels = $this->GetModulesFromDatamodels(APPROOT.'/datamodels');
		$this->assertArraySubset($aModulesFromDatamodels, $aDeclaredModules, false, "$sInstallationXmlPath does not refer to all provided modules. Refered modules:\n " . var_export($aDeclaredModules, true));
		$this->assertArraySubset($aDeclaredModules, $aModulesFromDatamodels, false, "Not all modules are contained in $sInstallationXmlPath. Refered modules:\n " . var_export($aModulesFromDatamodels, true));²²
	}

	public function GetModulesFromDatamodels($sFolder)
	{
		$aModules = array();
		if (is_dir($sFolder))
		{
			foreach (glob($sFolder."/*") as $sPath)
			{
				if (is_dir($sPath))
				{
					$aModules = array_merge($aModules, $this->GetModulesFromDatamodels($sPath));
				}
				else if (preg_match("/module\..*\.php/", basename($sPath)))
				{
					$sModulePhpContent = file_get_contents($sPath);
					if (strpos($sModulePhpContent, "SetupWebPage::AddModule(")!==false
						&& strpos($sModulePhpContent, "'mandatory' => true,")===false
						&& strpos($sModulePhpContent, "'visible' => false,")===false)
					{
						$sModule = basename(dirname($sPath));
						$aModules[$sModule] = $sModule;
					}
				}
			}
		}
		return $aModules;
	}
}
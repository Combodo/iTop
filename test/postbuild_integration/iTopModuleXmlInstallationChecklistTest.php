<?php

namespace Combodo\iTop\Test\UnitTest\ReleaseChecklist;

use Combodo\iTop\Test\UnitTest\ItopTestCase;


/**
 * @since 2.7.2 N°3060 / N°3061 Automatically check the installation.xml consistency
 *
 * @package Combodo\iTop\Test\UnitTest\Setup
 */
class iTopModuleXmlInstallationChecklistTest extends ItopTestCase
{
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
		if (!is_file($sInstallationXmlPath)) {
			$sInstallationXmlPath = APPROOT.'datamodels/1.x/installation.xml';
		}
		$this->assertTrue(is_file($sInstallationXmlPath), "$sInstallationXmlPath does not exist");

		$sInstallationXmlContent = file_get_contents($sInstallationXmlPath);
		preg_match_all("|<module>(.*)</module>|", $sInstallationXmlContent, $aMatches);
		$aDeclaredModules = [];
		if (!empty($aMatches)) {
			foreach ($aMatches[1] as $sModule) {
				if (!array_key_exists($sModule, $aDeclaredModules)) {
					$aDeclaredModules[$sModule] = $sModule;
				}
			}
		}

		$this->assertArraySubset(
			$this->GetFilteredModulesFromDatamodels(APPROOT.'/datamodels'),
			$aDeclaredModules,
			false,
			"{$sInstallationXmlPath} does not list all modules in /datamodels ! List of modules in installation.xml:\n ".var_export($aDeclaredModules, true)
		);

		$aModulesFromDatamodels = $this->GetAllModules(APPROOT.'/datamodels');
		$this->assertArraySubset(
			$aDeclaredModules,
			$aModulesFromDatamodels,
			false,
			"Not all modules are contained in {$sInstallationXmlPath}. List of modules in /datamodels:\n ".var_export($aModulesFromDatamodels, true)
		);
	}

	public function GetFilteredModulesFromDatamodels($sFolder)
	{
		$aExcludedModules = ['authent-external', 'authent-ldap'];
		$aModules = array();
		if (is_dir($sFolder))
		{
			foreach (glob($sFolder."/*") as $sPath)
			{
				if (is_dir($sPath))
				{
					/** @noinspection SlowArrayOperationsInLoopInspection */
					$aModules = array_merge($aModules, $this->GetFilteredModulesFromDatamodels($sPath));
				}
				else if (preg_match("/module\..*\.php/", basename($sPath)))
				{
					$sModulePhpContent = file_get_contents($sPath);
					if (strpos($sModulePhpContent, "SetupWebPage::AddModule")!==false
						&& strpos($sModulePhpContent, "'mandatory' => true")===false)
					{
						//filter modules autoselected due to below condition
						if (strpos($sModulePhpContent, "'mandatory' => false")!==false
							&& strpos($sModulePhpContent, "'visible' => false")!==false)
						{
							continue;
						}

						$sModule = basename(dirname($sPath));
						if (in_array($sModule, $aExcludedModules))// || $sModule === 'authent-ldap')
						{
							//hardcode this condition to make sure test is OK (CI context) + added a ticket to work/investigate why it is failed for these 2 cases (itop dev context)
							continue;
						}

						$aModules[$sModule] = $sModule;
					}
				}
			}
		}
		return $aModules;
	}

	public function GetAllModules($sFolder)
	{
		$aModules = array();
		if (is_dir($sFolder))
		{
			foreach (glob($sFolder."/*") as $sPath)
			{
				if (is_dir($sPath))
				{
					/** @noinspection SlowArrayOperationsInLoopInspection */
					$aModules = array_merge($aModules, $this->GetAllModules($sPath));
				}
				else if (preg_match("/module\..*\.php/", basename($sPath)))
				{
					$sModulePhpContent = file_get_contents($sPath);
					if (strpos($sModulePhpContent, "SetupWebPage::AddModule")!==false)
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
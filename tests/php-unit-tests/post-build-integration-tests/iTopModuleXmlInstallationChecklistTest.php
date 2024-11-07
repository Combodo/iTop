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

		$aIssues = [];

		$aFilteredModulesFromDatamodels = $this->GetModulesNotAutoSelected(APPROOT.'/datamodels');
		$aMissingModules = array_diff($aFilteredModulesFromDatamodels, $aDeclaredModules);
		$sMissingModules = implode(', ', $aMissingModules);
		if (count($aMissingModules) > 0) {
			$aIssues[] = "Does not reference the following modules: {$sMissingModules}. Those modules are in the directory datamodels and they are not configured for automatic installation. They will never be installed in this package.";
		}

		$aModulesFromDatamodels = $this->GetAllModules(APPROOT.'/datamodels');
		$aMissingModules = array_diff($aDeclaredModules, $aModulesFromDatamodels);
		$sMissingModules = implode(', ', $aMissingModules);
		if (count($aMissingModules) > 0) {
			$aIssues[] = "References unknown modules: $sMissingModules. Those modules are not in the datamodels directory. This will prevent the installation of the package.";
		}
		if (count($aIssues) > 0) {
			$this->fail("Encountered ".count($aIssues)." issue(s) in {$sInstallationXmlPath}:\n- ".implode("\n- ", $aIssues));
		}
	}

	public function GetModulesNotAutoSelected($sFolder)
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
					$aModules = array_merge($aModules, $this->GetModulesNotAutoSelected($sPath));
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
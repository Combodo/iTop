<?php

namespace Combodo\iTop\Test\UnitTest\ReleaseChecklist;

use Combodo\iTop\Test\UnitTest\ItopTestCase;
use iTopDesignFormat;
use PHPUnit\Exception;


/**
 * Class iTopDesignFormatChecklistTest
 * Ticket 3053 - Check XML conversion methods
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 *
 * @covers iTopDesignFormat
 *
 * @package Combodo\iTop\Test\UnitTest\Setup
 */
class TestForITopDesignFormatClass extends ItopTestCase
{
	protected function setUp()
	{
		parent::setUp();

		require_once APPROOT.'setup/modelfactory.class.inc.php';
		require_once APPROOT.'setup/itopdesignformat.class.inc.php';
	}

	/**
	 * release checklist step: make sure we have datamodel conversion functions for new iTop version
	 */
	public function testCurrentVersion_DataModelConversionFunctions()
	{
		$aErrors = [];
		$aDatamodelCurrentVersions = array();
		$aDataModelFiles = $this->GetDataModelFiles(APPROOT.'/datamodels');

		//retrieve current XML version in datamoldels files
		foreach ($aDataModelFiles as $sDataModelFile)
		{
			if (preg_match('/itop_design .* version="([\d\.]*)"/', file_get_contents($sDataModelFile), $aMatches))
			{
				$sVersion = $aMatches[1];
				if (!array_key_exists($sVersion, $aDatamodelCurrentVersions))
				{
					if (trim($sVersion) === '')
					{
						$aErrors[] = "cannot retrieve itop_design datamodel version in $sDataModelFile:1";
						continue;
					}
					
					$aDatamodelCurrentVersions[$sVersion] = $sVersion;
				}
			}
		}

		//make sure there is only one found
		$this->assertTrue(is_array($aDatamodelCurrentVersions));

		$sFirstVersion = array_keys(iTopDesignFormat::$aVersions)[0];
		$sLatestVersion = array_keys(iTopDesignFormat::$aVersions)[count(iTopDesignFormat::$aVersions)-1];
		foreach ($aDatamodelCurrentVersions as $sCurrentVersion)
		{
			try{
				//check we have migration function from current version to previous
				$this->CheckCondition(array_key_exists($sCurrentVersion, iTopDesignFormat::$aVersions), "Missing $sCurrentVersion conversion functions in iTopDesignFormat.");
				$aCurrentVersionInfo = iTopDesignFormat::$aVersions[$sCurrentVersion];
				$this->CheckCondition(is_array($aCurrentVersionInfo), "Wrong $sCurrentVersion config in iTopDesignFormat.");
				$this->CheckCondition(array_key_exists('previous', $aCurrentVersionInfo), "Missing previous for $sCurrentVersion config in iTopDesignFormat.");
				$this->TestDefinedFunction($aCurrentVersionInfo, 'go_to_next', $sCurrentVersion, ($sCurrentVersion=== $sLatestVersion));
				$this->TestDefinedFunction($aCurrentVersionInfo, 'go_to_previous', $sCurrentVersion, ($sCurrentVersion==='1.0'));

				//check we have migration function from N-1 version to current one
				if (($sCurrentVersion!=='1.0')) {
					$sPreviousVersion = $aCurrentVersionInfo['previous'];
					$this->CheckCondition(array_key_exists($sPreviousVersion, iTopDesignFormat::$aVersions),
						"$sCurrentVersion: Missing $sPreviousVersion config in iTopDesignFormat.");
					$aPreviousVersionInfo = iTopDesignFormat::$aVersions[$sPreviousVersion];
					$this->CheckCondition(is_array($aPreviousVersionInfo),
						"$sCurrentVersion: wrong $sPreviousVersion config in iTopDesignFormat.");
					$this->CheckCondition(array_key_exists('previous', $aPreviousVersionInfo),
						"$sCurrentVersion: Missing previous for $sPreviousVersion config in iTopDesignFormat.");
					$this->TestDefinedFunction($aPreviousVersionInfo, 'go_to_previous', $sPreviousVersion, ($sPreviousVersion === '1.0'));
					$this->TestDefinedFunction($aPreviousVersionInfo, 'go_to_next', $sPreviousVersion, ($sPreviousVersion === $sLatestVersion));
				}

				//check we have migration function from current version to next one
				if (($sCurrentVersion!== $sLatestVersion)) {
					$sNextVersion = $aCurrentVersionInfo['next'];
					$this->CheckCondition(array_key_exists($sNextVersion, iTopDesignFormat::$aVersions),
						"$sCurrentVersion: Missing $sNextVersion config in iTopDesignFormat.");
					$aNextVersionInfo = iTopDesignFormat::$aVersions[$sNextVersion];
					$this->CheckCondition(is_array($aNextVersionInfo),
						"$sCurrentVersion: wrong $sNextVersion config in iTopDesignFormat.");
					$this->CheckCondition(array_key_exists('previous', $aNextVersionInfo),
						"$sCurrentVersion: Missing previous for $sNextVersion config in iTopDesignFormat.");
					$this->TestDefinedFunction($aNextVersionInfo, 'go_to_previous', $sNextVersion, ($sNextVersion === '1.0'));
					$this->TestDefinedFunction($aNextVersionInfo, 'go_to_next', $sNextVersion, ($sNextVersion === $sLatestVersion));
				}
			}
			catch(Exception $e)
			{
				$aErrors[] = $e->getMessage();
			}
		}

		if (count($aErrors)!=0)
		{
			$sMsg = "Issue with conversion functions:\n";
			$sMsg .= implode("\n", $aErrors);
			$this->fail($sMsg);
		}
		else
		{
			$this->assertTrue(true);
		}
	}

	private function CheckCondition($bCondition, $sMsg)
	{
		if ($bCondition === false)
		{
			throw new \Exception($sMsg);
		}
	}

	private function TestDefinedFunction($aCurrentVersionInfo, $sFunctionKey, $sVersion, $bNullFunction=false)
	{
		$sInfo = json_encode($aCurrentVersionInfo, true);
		$this->CheckCondition(array_key_exists($sFunctionKey, $aCurrentVersionInfo), "Missing $sFunctionKey in $sVersion config in iTopDesignFormat: " . $sInfo);
		//echo $aCurrentVersionInfo[$sFunctionKey].'\n';
		if ($bNullFunction === false)
		{
			$oReflectionClass = new \ReflectionClass(iTopDesignFormat::class);
			$this->CheckCondition($oReflectionClass->hasMethod($aCurrentVersionInfo[$sFunctionKey]), "wrong go_to_previous function '".$aCurrentVersionInfo[$sFunctionKey]."'' for $sVersion config in iTopDesignFormat." . $sInfo);
		}
		else
		{
			$this->CheckCondition(is_null($aCurrentVersionInfo[$sFunctionKey]), "$sVersion $sFunctionKey function should be null");
		}
	}

	public function GetDataModelFiles($sFolder)
	{
		$aDataModelFiles = array();
		if (is_dir($sFolder))
		{
			foreach (glob($sFolder."/*") as $sPath)
			{
				if (is_dir($sPath))
				{
					$aDataModelFiles = array_merge($aDataModelFiles, $this->GetDataModelFiles($sPath));
				}
				else if (preg_match("/datamodel\..*\.xml/", basename($sPath)))
				{
					$aDataModelFiles[] = $sPath;
				}
			}
		}
		return $aDataModelFiles;
	}
}
<?php

namespace Combodo\iTop\Test\UnitTest\Setup;

use Combodo\iTop\Test\UnitTest\ItopTestCase;
use DOMDocument;
use iTopDesignFormat;


/**
 * Class iTopDesignFormatChecklistTest
 *
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
					$aDatamodelCurrentVersions[$sVersion] = $sVersion;
				}
			}
		}

		//make sure there is only one found
		$this->assertTrue(is_array($aDatamodelCurrentVersions));
		$this->assertEquals(1, count($aDatamodelCurrentVersions), "Found too much XML versions: " . json_encode($aDatamodelCurrentVersions));

		//check we have migration function from new version to previous one
		$sCurrentVersion = array_values($aDatamodelCurrentVersions)[0];
		$this->assertTrue(array_key_exists($sCurrentVersion, iTopDesignFormat::$aVersions), "Release checklist: missing $sCurrentVersion config in iTopDesignFormat ");
		$aCurrentVersionInfo = iTopDesignFormat::$aVersions[$sCurrentVersion];
		$this->assertTrue(is_array($aCurrentVersionInfo), "Release checklist: wrong $sCurrentVersion config in iTopDesignFormat ");
		$this->assertTrue(array_key_exists('previous', $aCurrentVersionInfo), "Release checklist: missing previous for $sCurrentVersion config in iTopDesignFormat ");
		$this->TestDefinedFunction($aCurrentVersionInfo, 'go_to_previous', $sCurrentVersion);

		//check we have migration function from N-1 version to new one
		$sPreviousVersion = $aCurrentVersionInfo['previous'];
		$this->assertTrue(array_key_exists($sPreviousVersion, iTopDesignFormat::$aVersions), "Release checklist: missing $sPreviousVersion config in iTopDesignFormat ");
		$aPreviousVersionInfo = iTopDesignFormat::$aVersions[$sPreviousVersion];
		$this->assertTrue(is_array($aPreviousVersionInfo), "Release checklist: wrong $sPreviousVersion config in iTopDesignFormat ");
		$this->assertTrue(array_key_exists('previous', $aPreviousVersionInfo), "Release checklist: missing previous for $sPreviousVersion config in iTopDesignFormat ");
		$this->TestDefinedFunction($aPreviousVersionInfo, 'go_to_previous', $sPreviousVersion);
		$this->TestDefinedFunction($aPreviousVersionInfo, 'go_to_next', $sPreviousVersion);
	}

	private function TestDefinedFunction($aCurrentVersionInfo, $sFunctionKey, $sVersion)
	{
		$sInfo = json_encode($aCurrentVersionInfo, true);
		$this->assertTrue(array_key_exists($sFunctionKey, $aCurrentVersionInfo), "Release checklist: missing $sFunctionKey in $sVersion config in iTopDesignFormat: " . $sInfo);
		echo $aCurrentVersionInfo[$sFunctionKey].'\n';
		$oReflectionClass = new \ReflectionClass(iTopDesignFormat::class);
		$this->assertTrue($oReflectionClass->hasMethod($aCurrentVersionInfo[$sFunctionKey]), "Release checklist: wrong go_to_previous function '".$aCurrentVersionInfo[$sFunctionKey]."'' for $sVersion config in iTopDesignFormat " . $sInfo);
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
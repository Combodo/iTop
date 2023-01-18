<?php
/*
 * @copyright   Copyright (C) 2010-2022 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

use Combodo\iTop\Test\UnitTest\ItopTestCase;

class DatamodelsXmlFilesTest extends ItopTestCase
{
	protected function setUp(): void
	{
		parent::setUp();
		require_once APPROOT.'.make/release/update.classes.inc.php';
		require_once APPROOT.'setup/itopdesignformat.class.inc.php';
	}

	public function testGetDesignVersionToSet() {
		$sVersionFor10 = $this->InvokeNonPublicStaticMethod(DatamodelsXmlFiles::class, 'GetDesignVersionToSet', ['1.0']);
		$this->assertNull($sVersionFor10);
		$sVersionFor26 = $this->InvokeNonPublicStaticMethod(DatamodelsXmlFiles::class, 'GetDesignVersionToSet', ['2.6']);
		$this->assertNull($sVersionFor26);
		$sVersionFor27 = $this->InvokeNonPublicStaticMethod(DatamodelsXmlFiles::class, 'GetDesignVersionToSet', ['2.7']);
		$this->assertNull($sVersionFor27);

		$sPreviousDesignVersion = iTopDesignFormat::GetPreviousDesignVersion(ITOP_DESIGN_LATEST_VERSION);
		$sVersionForLatest = $this->InvokeNonPublicStaticMethod(DatamodelsXmlFiles::class, 'GetDesignVersionToSet', [ITOP_DESIGN_LATEST_VERSION]);
		$this->assertNotNull($sVersionForLatest);
		$this->assertSame($sPreviousDesignVersion, $sVersionForLatest);
	}

	public function testAllItopXmlFilesCovered()
	{
		$oXmlUpdate = new DatamodelsXmlFiles();
		$aITopDesignFilesFromScript = $oXmlUpdate->GetFiles();
		$aITopDesignFilesFromScript = array_map(function ($value) {
			return str_replace('\\', '/', $value);
		}, $aITopDesignFilesFromScript);

		$oDirectoryIterator = new RecursiveDirectoryIterator(APPROOT,
			FilesystemIterator::KEY_AS_PATHNAME
			| FilesystemIterator::CURRENT_AS_FILEINFO
			| FilesystemIterator::SKIP_DOTS
			| FilesystemIterator::UNIX_PATHS
		);
		$iterator = new RecursiveIteratorIterator(
			$oDirectoryIterator,
			RecursiveIteratorIterator::CHILD_FIRST);
		$oITopDesignIterator = new ITopDesignFilter($iterator);
		$aITopDesignFilesFromDisk = iterator_to_array($oITopDesignIterator);
		$aITopDesignFilesFromDisk = array_map(function ($file) {
			return str_replace('\\', '/', $file->getPathname());
		}, $aITopDesignFilesFromDisk);

		sort($aITopDesignFilesFromScript);
		sort($aITopDesignFilesFromDisk);
		$this->assertEquals($aITopDesignFilesFromDisk, $aITopDesignFilesFromScript, 'The XML files update script is not targeting some iTop design files ! '.DatamodelsXmlFiles::class.' must be updated !');
	}
}

class ITopDesignFilter extends FilterIterator
{
	const DIRECTORIES_TO_EXCLUDE = [
		// iTop repo excluded dirs
		'.doc',
		'.git',
		'.idea',
		'data/', // trailing slash to avoid confusion with datamodels dir
		'env-',
		'extensions',
		'test',

		// clones specificities
		'toolkit',
		'toolkit-pro',
		'.delta',
		'.hacks',
	];
	private $aDirToExcludeFullPath;

	public function __construct($iterator)
	{
		parent::__construct($iterator);

		$this->aDirToExcludeFullPath = array_map(function ($value) {
			$sRawDir = APPROOT.$value;

			return str_replace('\\', '/', $sRawDir);
		}, self::DIRECTORIES_TO_EXCLUDE);
	}

	public function accept()
	{
		$file = $this->current();

		if ($file->isDir()) {
			return false;
		}
		$sFilePath = str_replace('\\', '/', $file->getPathname());
		if (substr($sFilePath, -4) !== '.xml') {
			return false;
		}

		foreach ($this->aDirToExcludeFullPath as $sDirToExclude) {
			if (strpos($sFilePath, $sDirToExclude) === 0) {
				return false;
			}
		}

		if (false === $this->IsITopDesignFile($sFilePath)) {
			return false;
		}

		return true;
	}

	private function IsITopDesignFile($sFilePath)
	{
		$oXmlFile = fopen($sFilePath, 'r');
		if (false === $oXmlFile) {
			return false;
		}

		$sLine1 = fgets($oXmlFile);
		if (strpos($sLine1, '<?xml version="1.0" encoding="UTF-8"?>') !== 0) {
			fclose($oXmlFile);

			return false;
		}
		$sLine2 = fgets($oXmlFile);
		$sITopDesignRootNode = '<itop_design ';
		if (strpos($sLine2, $sITopDesignRootNode) !== 0) {
			fclose($oXmlFile);

			return false;
		}

		fclose($oXmlFile);

		return true;
	}
}
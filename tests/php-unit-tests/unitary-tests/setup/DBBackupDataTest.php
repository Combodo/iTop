<?php

namespace Combodo\iTop\Test\UnitTest\Core;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use DBBackup;
use DBRestore;
use MetaModel;
use SetupUtils;

class DBBackupDataTest extends ItopDataTestCase
{
	/**
	 * @dataProvider prepareFilesToBackupProvider
	 */
	public function testPrepareFilesToBackup(array $aExtraFiles, bool $bUnsafeFileException)
	{
		$sTmpDir = sys_get_temp_dir().'/testPrepareFilesToBackup-'.time();
		$oBackup = new DBBackup(MetaModel::GetConfig());
		MetaModel::GetConfig()->SetModuleSetting('itop-backup', 'extra_files', array_keys($aExtraFiles));
		
		foreach($aExtraFiles as $sExtraFile => $bExists)
		{
			if ($bExists)
			{
				@mkdir(dirname(APPROOT.'/'.$sExtraFile), 0755, true);
				file_put_contents(APPROOT.'/'.$sExtraFile, 'Hello World!');
			}
		}

		try {
			if ($bUnsafeFileException) {
				$this->expectExceptionMessage("Backup: Aborting, resource '$sExtraFile'. Considered as UNSAFE because not inside the iTop directory.");
			}
			$aFiles = $this->InvokeNonPublicMethod('DBBackup', 'PrepareFilesToBackup', $oBackup, [APPROOT . '/conf/production/config-itop.php', $sTmpDir, true]);
			SetupUtils::rrmdir($sTmpDir);
			$aExpectedFiles = [
				$sTmpDir . '/config-itop.php',
			];
			foreach ($aExtraFiles as $sRelFile => $bExists) {
				if ($bExists) {
					$aExpectedFiles[] = $sTmpDir . '/' . $sRelFile;
				}
			}
		} finally {
			// Cleanup
			foreach ($aExtraFiles as $sExtraFile => $bExists) {
				if ($bExists) {
					unlink(APPROOT . '/' . $sExtraFile);
				}
			}
		}

		sort($aFiles);
		sort($aExpectedFiles);
		$this->assertEquals($aFiles, $aExpectedFiles);
	}
	
	function prepareFilesToBackupProvider()
	{
		return [
			'no_extra_file' => ['aExtraFiles' => [], false],
			'one_extra_file' => ['aExtraFiles' => ['foo.txt' => true], false],
			'three_extra_file_and_dir' => ['aExtraFiles' => ['foo.txt' => true, 'gabu/zomeu.xml' => true, 'meuh.html' => true], false],
			'two_extra_file_but_only_one_exists' => ['aExtraFiles' => ['foo.txt' => true, 'meuh.html' => false], false],
			'one_unsafe_file' => ['aExtraFiles' => ['../foo.txt' => true], true],
		];
	}

	/**
	 * @dataProvider restoreListExtraFilesProvider
	 */
	function testRestoreListExtraFiles($aFilesToCreate, $aExpectedRelativeExtraFiles)
	{
		require_once(APPROOT.'/env-production/itop-backup/dbrestore.class.inc.php');

		$sTmpDir = sys_get_temp_dir().'/testRestoreListExtraFiles-'.time();
		
		foreach($aFilesToCreate as $sRelativeName)
		{
			$sDir = $sTmpDir.'/'.dirname($sRelativeName);
			if(!is_dir($sDir))
			{
				mkdir($sDir, 0755, true);
			}
			file_put_contents($sTmpDir.'/'.$sRelativeName, 'Hello world.');
		}
		$aExpectedExtraFiles = [];
		foreach($aExpectedRelativeExtraFiles as $sRelativeName)
		{
			if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
				$sRelativeName = str_replace('/', '\\', $sRelativeName);
				$aExpectedExtraFiles[$sTmpDir.'\\'.$sRelativeName] = APPROOT.'\\'.$sRelativeName;
			}
			else {
				$aExpectedExtraFiles[$sTmpDir.'/'.$sRelativeName] = APPROOT.'/'.$sRelativeName;
			}
		}

		$oRestore = new DBRestore(MetaModel::GetConfig());
		$aExtraFiles = $this->InvokeNonPublicMethod('DBRestore', 'ListExtraFiles', $oRestore, [$sTmpDir]);
		
		asort($aExtraFiles);
		asort($aExpectedExtraFiles);
		$this->assertEquals($aExpectedExtraFiles, $aExtraFiles);
		SetupUtils::rrmdir($sTmpDir);
	}

	function restoreListExtraFilesProvider()
	{
		return [
			'no extra file' => ['aFilesToCreate' => ['config-itop.php', 'itop-dump.sql', 'delta.xml'], 'aExpectedExtraFiles' => []],
			'no extra file (2)' => ['aFilesToCreate' => ['config-itop.php', 'itop-dump.sql', 'delta.xml', 'production-modules/test/module.test.php'], 'aExpectedExtraFiles' => []],
			'one extra file' => ['aFilesToCreate' => ['config-itop.php', 'itop-dump.sql', 'delta.xml', 'production-modules/test/module.test.php', 'collectors/ldap/conf/params.local.xml'], 'aExpectedExtraFiles' => ['collectors/ldap/conf/params.local.xml']],
		];
	}
	
}

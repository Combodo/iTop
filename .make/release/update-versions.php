<?php

/*******************************************************************************
 * Tool to automate version update before release
 *
 * Will update version in the following files :
 *
 * * datamodels/2.x/.../module.*.php
 * * datamodels/2.x/version.xml
 * * css/css-variables.scss $version
 *
 * Usage :
 * `php .make\release\update-versions.php "2.7.0-rc"`
 *
 * @since 2.7.0`
 ******************************************************************************/



require_once (__DIR__.'/../../approot.inc.php');




abstract class FileVersionUpdater
{
	/**
	 * @return string[] full path of files to modify
	 */
	abstract public function GetFiles();

	/**
	 * Warnign : will consume lots of memory on larger files !
	 *
	 * @param string $sVersionLabel
	 * @param string $sFileContent
	 * @param string $sFileFullPath
	 *
	 * @return string file content with replaced values
	 */
	abstract public function UpdateFileContent($sVersionLabel, $sFileContent, $sFileFullPath);

	public function UpdateAllFiles($sVersionLabel)
	{
		$aFilesToUpdate = $this->GetFiles();
		$sFileUpdaterName = get_class($this);
		echo "# Updater : $sFileUpdaterName\n";
		foreach ($aFilesToUpdate as $sFileToUpdateFullPath)
		{
			try
			{
				$sCurrentFileContent = file_get_contents($sFileToUpdateFullPath);
				$sNewFileContent = $this->UpdateFileContent($sVersionLabel, $sCurrentFileContent, $sFileToUpdateFullPath);
				file_put_contents($sFileToUpdateFullPath, $sNewFileContent);
				echo "   - $sFileToUpdateFullPath : OK !\n";
			}
			catch (Exception $e)
			{
				echo "   - $sFileToUpdateFullPath : Error :(\n";
			}
		}
	}
}

abstract class AbstractSingleFileVersionUpdater extends FileVersionUpdater
{
	private $sFileToUpdate;

	public function __construct($sFileToUpdate)
	{
		$this->sFileToUpdate = $sFileToUpdate;
	}

	public function GetFiles()
	{
		return array(APPROOT.$this->sFileToUpdate);
	}
}

class iTopVersionFileUpdater extends AbstractSingleFileVersionUpdater
{
	public function __construct()
	{
		parent::__construct('datamodels/2.x/version.xml');
	}

	/**
	 * @inheritDoc
	 */
	public function UpdateFileContent($sVersionLabel, $sFileContent, $sFileFullPath)
	{
		return preg_replace(
			'/(<version>)[^<]*(<\/version>)/',
			'${1}'.$sVersionLabel.'${2}',
			$sFileContent
		);
	}
}

class CssVariablesFileUpdater extends AbstractSingleFileVersionUpdater
{
	public function __construct()
	{
		parent::__construct('css/css-variables.scss');
	}

	/**
	 * @inheritDoc
	 */
	public function UpdateFileContent($sVersionLabel, $sFileContent, $sFileFullPath)
	{
		return preg_replace(
			'/(\$version: "v)[^"]*(";)/',
			'${1}'.$sVersionLabel.'${2}',
			$sFileContent
		);
	}
}

abstract class AbstractGlobFileVersionUpdater extends FileVersionUpdater
{
	protected $sGlobPattern;

	public function __construct($sGlobPattern)
	{
		$this->sGlobPattern = $sGlobPattern;
	}

	public function GetFiles()
	{
		return glob($this->sGlobPattern);
	}
}

class DatamodelsModulesFiles extends AbstractGlobFileVersionUpdater
{
	public function __construct()
	{
		parent::__construct(APPROOT.'datamodels/2.x/*/module.*.php');
	}

	/**
	 * @inheritDoc
	 */
	public function UpdateFileContent($sVersionLabel, $sFileContent, $sFileFullPath)
	{
		$sModulePath = realpath($sFileFullPath);
		$sModuleFileName = basename($sModulePath, 1);
		$sModuleName = preg_replace('/[^.]+\.([^.]+)\.php/', '$1', $sModuleFileName);

		return preg_replace(
			"/('$sModuleName\/)[^']+(')/",
			'${1}'.$sVersionLabel.'${2}',
			$sFileContent
		);
	}
}




/** @var \FileVersionUpdater[] $aFilesUpdaters */
$aFilesUpdaters = array(
	new iTopVersionFileUpdater(),
	new CssVariablesFileUpdater(),
	new DatamodelsModulesFiles(),
);

if (count($argv) === 1)
{
	echo '/!\ You must pass the new version as parameter';
	exit(1);
}
$sVersionLabel = $argv[1];
if (empty($sVersionLabel))
{
	echo 'Version passed as parameter is empty !';
	exit(2);
}

foreach ($aFilesUpdaters as $oFileVersionUpdater)
{
	$oFileVersionUpdater->UpdateAllFiles($sVersionLabel);
}

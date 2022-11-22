<?php

/*******************************************************************************
 * Classes for updater tools
 *
 * @see update-versions.php
 * @see update-xml.php
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

/**
 * @since 2.7.7 3.0.1 3.1.0 N°4714
 */
class ConstantFileUpdater extends AbstractSingleFileVersionUpdater {
	/** @var string */
	private $sConstantName;

	/**
	 * @param $sConstantName constant to search, for example `ITOP_CORE_VERSION`
	 * @param $sFileToUpdate file containing constant definition
	 */
	public function __construct($sConstantName, $sFileToUpdate)
	{
		$this->sConstantName = $sConstantName;
		parent::__construct($sFileToUpdate);
	}

	/**
	 * @inheritDoc
	 */
	public function UpdateFileContent($sVersionLabel, $sFileContent, $sFileFullPath)
	{
		$sConstantSearchPattern = <<<REGEXP
/define\('{$this->sConstantName}', ?'[^']+'\);/
REGEXP;

		return preg_replace(
			$sConstantSearchPattern,
			"define('{$this->sConstantName}', '{$sVersionLabel}');",
			$sFileContent
		);
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

class DatamodelsXmlFiles extends AbstractGlobFileVersionUpdater
{
	public function __construct()
	{
		parent::__construct(APPROOT.'datamodels/2.x/*/datamodel.*.xml');
	}

	/**
	 * @inheritDoc
	 */
	public function UpdateFileContent($sVersionLabel, $sFileContent, $sFileFullPath)
	{
		require_once APPROOT.'setup/itopdesignformat.class.inc.php';
		$oFileXml = new DOMDocument();
		/** @noinspection PhpComposerExtensionStubsInspection */
		libxml_clear_errors();
		$oFileXml->formatOutput = true;
		$oFileXml->preserveWhiteSpace = false;
		$oFileXml->loadXML($sFileContent);

		$oFileItopFormat = new iTopDesignFormat($oFileXml);
		$bConversionResult = $oFileItopFormat->Convert($sVersionLabel);

		if (false === $bConversionResult) {
			throw new Exception("Error when converting $sFileFullPath");
		}

		return $oFileItopFormat->GetXmlAsString();
	}
}

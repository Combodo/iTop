<?php
/**
 * @copyright   Copyright (C) 2010-2019 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\FilesInformation\Service;


use Dict;
use DOMDocument;
use DOMElement;
use DOMNode;
use DOMXPath;

class FilesIntegrity
{

	/**
	 * Get the files defined in the manifest.xml
	 *
	 * @param string $sManifest full path of the manifest file
	 *
	 * @return array|false list of file info (path, size, md5)
	 * @throws \Exception
	 */
	public static function GetInstalledFiles($sManifest)
	{
		$aFiles = array();

		$aManifestStats = @stat($sManifest);
		if ($aManifestStats === false)
		{
			// No manifest
			return false;
		}

		$oManifestDocument = new DOMDocument('1.0', 'UTF-8');
		@$oManifestDocument->load($sManifest);
		$oXPath = new DOMXPath($oManifestDocument);
		$oNodeList = $oXPath->query('/files');
		if ($oNodeList->length == 0)
		{
			// no files
			return false;
		}
		foreach ($oNodeList as $oItems)
		{
			foreach ($oItems->childNodes as $oFileNode)
			{
				if (($oFileNode instanceof DOMNode))
				{
					if ($oFileNode->hasChildNodes())
					{
						$aFileInfo = array();
						$sFilePath = uniqid(); // just in case no path...
						foreach ($oFileNode->childNodes as $oFileInfo)
						{
							if ($oFileInfo instanceof DOMElement)
							{
								$aFileInfo[$oFileInfo->tagName] = $oFileInfo->textContent;
								if ($oFileInfo->tagName == 'path')
								{
									$sFilePath = $oFileInfo->textContent;
								}
							}
						}
						$aFiles[$sFilePath] = $aFileInfo;
					}
				}
			}
		}

		return $aFiles;
	}

	/**
	 * Check that files present in iTop folder corresponds to the manifest
	 *
	 * @param string $sRootPath
	 *
	 * @throws \Combodo\iTop\FilesInformation\Service\FileIntegrityException
	 */
	public static function CheckInstallationIntegrity($sRootPath = APPROOT)
	{
		$aFilesInfo = FilesIntegrity::GetInstalledFiles($sRootPath.'manifest.xml');

		if ($aFilesInfo === false)
		{
			throw new FileIntegrityException(Dict::Format('FilesInformation:Error:MissingFile', 'manifest.xml'));
		}

		@clearstatcache();
		foreach ($aFilesInfo as $aFileInfo)
		{
			$sFile = $sRootPath.$aFileInfo['path'];
			if (is_file($sFile))
			{
				$aStats = @stat($sFile);
				$iSize = $aStats['size'];
				$sContent = file_get_contents($sFile);
				$sChecksum = md5($sContent);
				if (($iSize != $aFileInfo['size']) || ($sChecksum != $aFileInfo['md5']))
				{
					throw new FileIntegrityException(Dict::Format('FilesInformation:Error:CorruptedFile', $sFile));
				}
			}
			// Packed with missing files...
		}
	}

	public static function IsInstallationConform($sRootPath, &$sErrorMsg)
	{
		$sErrorMsg = '';
		try
		{
			self::CheckInstallationIntegrity($sRootPath);
			return true;
		}
		catch (FileIntegrityException $e)
		{
			$sErrorMsg = $e->getMessage();
		}
		return false;
	}
}

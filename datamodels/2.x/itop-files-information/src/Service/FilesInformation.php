<?php
/**
 * @copyright   Copyright (C) 2010-2019 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\FilesInformation\Service;


use Dict;
use MetaModel;
use utils;

class FilesInformation
{
    private static $sItopOwner;

	/**
	 * Check iTop files access rights to tell if core update is possible
	 *
	 * @param string $sMessage
	 *
	 * @return bool true if core update is possible
	 * @throws \Combodo\iTop\FilesInformation\Service\FileNotExistException
	 */
    public static function CanUpdateCore(&$sMessage)
    {
        self::Init();
        // Check than iTop can write everywhere
        if (!self::CanWriteRecursive('', $sMessage))
        {
            return false;
        }

        return true;
    }

	/**
	 * @param string $sRootPath
	 * @param string $sMessage
	 *
	 * @return bool
	 * @throws \Combodo\iTop\FilesInformation\Service\FileNotExistException
	 */
    private static function CanWriteRecursive($sRootPath = '', &$sMessage = null)
    {
        $aDirStats = FilesInformationUtils::Scan($sRootPath, false);
        foreach ($aDirStats as $sFileName => $aFileStats)
        {
            if (!self::CanWriteToFile($aFileStats))
            {
	            $sMessage = Dict::Format('FilesInformation:Error:CantWriteToFile', $sRootPath.DIRECTORY_SEPARATOR.$sFileName);
                return false;
            }
            if (($sFileName != '.') && ($aFileStats['type'] == 'dir'))
            {
                if (!self::CanWriteRecursive($sRootPath.DIRECTORY_SEPARATOR.$sFileName, $sMessage))
                {
                    return false;
                }
            }
        }
        return true;
    }

	/**
	 * Check if iTop can write
	 * @param string $sFilename absolute path to chack
	 *
	 * @return bool
	 * @throws \Combodo\iTop\FilesInformation\Service\FileNotExistException
	 */
	public static function IsWritable($sFilename)
    {
	    $aFileStats = FilesInformationUtils::GetFileStat(utils::LocalPath($sFilename));
	    return self::CanWriteToFile($aFileStats);
    }

    private static function CanWriteToFile($aFileStats)
    {
        if ($aFileStats['writable'])
        {
            return true;
        }
        if ($aFileStats['file_owner'] == self::$sItopOwner)
        {
            // If iTop owns the file, no pb to write
            return true;
        }
        return false;
    }

    /**
     * @throws \Combodo\iTop\FilesInformation\Service\FileNotExistException
     */
    private static function Init()
    {
        clearstatcache();

        $sSourceConfigFile = MetaModel::GetConfig()->GetLoadedFile();
        $sSourceConfigFile = utils::LocalPath($sSourceConfigFile);

        $aConfigFiles = FilesInformationUtils::Scan(dirname($sSourceConfigFile));
        if (!isset($aConfigFiles[basename($sSourceConfigFile)]))
        {
            return;
        }
        $aConfigStats = $aConfigFiles[basename($sSourceConfigFile)];
        self::$sItopOwner = $aConfigStats['file_owner'];
    }

    public static function GetItopDiskSpace()
    {
        return FilesInformationUtils::GetDirSize(realpath(APPROOT));
    }

	/**
	 * @param $sLocalDirPath
	 *
	 * @return array
	 * @throws \Combodo\iTop\FilesInformation\Service\FileNotExistException
	 */
	public static function GetDirInfo($sLocalDirPath)
    {
    	if (utils::AbsolutePath($sLocalDirPath) === false)
	    {
	    	return array();
	    }
    	return FilesInformationUtils::Scan($sLocalDirPath);
    }

}

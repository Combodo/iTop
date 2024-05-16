<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\FilesInformation\Service;

use utils;

class FilesInformationUtils
{
    public static function Init()
    {
        clearstatcache();
    }

    /**
     * @param string $sPath
     * @param bool $bGetDirSize
     *
     * @return array
     * @throws \Combodo\iTop\FilesInformation\Service\FileNotExistException
     */
    public static function Scan($sPath = '', $bGetDirSize = true)
    {
        $aFileStats = array();

        $sRealRootPath = utils::AbsolutePath($sPath);
        if (empty($sRealRootPath))
        {
        	return $aFileStats;
        }

        $aFiles = scandir($sRealRootPath);

        foreach ($aFiles as $sScanFile)
        {
            if ($sScanFile == '..')
            {
                continue;
            }
            $sFile = $sRealRootPath.DIRECTORY_SEPARATOR.$sScanFile;
            $sFileName = utils::LocalPath($sFile);
	        $aFileStat = self::GetFileStat($sFileName, $bGetDirSize);
	        $aFileStat['basename'] = $sScanFile;
	        $aFileStats[$sScanFile] = $aFileStat;
        }

        return $aFileStats;
    }

    /**
     * @param string $sFilename
     *
     * @param bool $bGetDirSize
     *
     * @return array
     * @throws \Combodo\iTop\FilesInformation\Service\FileNotExistException
     */
    public static function GetFileStat($sFilename, $bGetDirSize = true)
    {
        $sFile = realpath(APPROOT.$sFilename);
        $aStats = @stat($sFile);
        if (!$aStats)
        {
            throw new FileNotExistException($sFilename);
        }

        $aFileStats = array();
        $aFileStats['name'] = $sFilename;
        $aFileStats['size'] = $aStats['size'];

        if (is_dir($sFile))
        {
            // Special dir case
            if ($bGetDirSize)
            {
                // The size is computed by aggregating the sizes on the children
                $aFileStats['size'] = self::GetDirSize($sFile);
            }
        }

	    $aFileStats['display_size'] = utils::BytesToFriendlyFormat($aFileStats['size']);

	    $aFileStats['perms'] = sprintf("0%o", 0777 & $aStats['mode']);
        $aFileStats['mode'] = $aStats['mode'];

        $aTypes = array(
            0140000=>'socket',
            0120000=>'link',
            0100000=>'file',
            0060000=>'block',
            0040000=>'dir',
            0020000=>'char',
            0010000=>'fifo'
        );
        $iRawMode = $aStats['mode'];
        $iMode = decoct($iRawMode & 0170000); // File Encoding Bit

	    $sDisplayMode =(array_key_exists(octdec($iMode),$aTypes))?$aTypes[octdec($iMode)][0]:'u';
	    $sDisplayMode.=(($iRawMode&0x0100)?'r':'-').(($iRawMode&0x0080)?'w':'-');
	    $sDisplayMode.=(($iRawMode&0x0040)?(($iRawMode&0x0800)?'s':'x'):(($iRawMode&0x0800)?'S':'-'));
	    $sDisplayMode.=(($iRawMode&0x0020)?'r':'-').(($iRawMode&0x0010)?'w':'-');
	    $sDisplayMode.=(($iRawMode&0x0008)?(($iRawMode&0x0400)?'s':'x'):(($iRawMode&0x0400)?'S':'-'));
	    $sDisplayMode.=(($iRawMode&0x0004)?'r':'-').(($iRawMode&0x0002)?'w':'-');
	    $sDisplayMode.=(($iRawMode&0x0001)?(($iRawMode&0x0200)?'t':'x'):(($iRawMode&0x0200)?'T':'-'));

	    $aFileStats['display_mode'] = $sDisplayMode;
	    $aFileStats['type'] = $aTypes[octdec($iMode)];
        $aFileStats['readable'] = is_readable($sFile);
        $aFileStats['writable'] = is_writable($sFile);
        $aFileStats['file_owner'] = $aStats['uid'];
        $aFileStats['file_group'] = $aStats['gid'];
        if (function_exists('posix_getpwuid'))
        {
            $aPwUid = @posix_getpwuid($aStats['uid']);
            if (isset($aPwUid['name']))
            {
                $aFileStats['owner_name'] = $aPwUid['name'];
            }
        }
        if (empty($aFileStats['owner_name']))
        {
            $aFileStats['owner_name'] = '';
        }
        if (function_exists('posix_getgrgid'))
        {
            $aGrGid = @posix_getgrgid($aStats['gid']);
            if (isset($aGrGid['name']))
            {
                $aFileStats['group_name'] = $aGrGid['name'];
            }
        }
        if (empty($aFileStats['group_name']))
        {
            $aFileStats['group_name'] = '';
        }
	    $aFileStats['mtime'] = date('Y-m-d H:i:s', $aStats['mtime']);
	    $aFileStats['ctime'] = date('Y-m-d H:i:s', $aStats['ctime']);

        return $aFileStats;
    }

    /**
     * @param string $sPath relative iTop path
     *
     * @return string absolute path
     * @throws \Combodo\iTop\FilesInformation\Service\FileNotExistException
     */
    public static function GetAbsolutePath($sPath)
    {
        $sRootPath = realpath(APPROOT);
        $sFullPath = realpath($sRootPath.DIRECTORY_SEPARATOR.$sPath);
        if (($sFullPath === false) || !utils::StartsWith($sFullPath, $sRootPath))
        {
            throw new FileNotExistException($sPath);
        }
        return $sFullPath;
    }

    public static function GetDirSize($sRealRootPath)
    {
        $aFiles = scandir($sRealRootPath);
        $iSize = 0;
        foreach ($aFiles as $sScanFile)
        {
            if (($sScanFile == '.') || ($sScanFile == '..'))
            {
                continue;
            }
            $sFile = $sRealRootPath.DIRECTORY_SEPARATOR.$sScanFile;
            if (is_dir($sFile))
            {
                $iSize += self::GetDirSize($sFile);
            }
            else
            {
                $aStats = @stat($sFile);
	            if (is_array($aStats)) {
		            $iSize += $aStats['size'];
	            }
            }
        }
        return $iSize;
    }
}

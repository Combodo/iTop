<?php
/**
 *  @copyright   Copyright (C) 2010-2019 Combodo SARL
 *  @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\CoreUpdate\Controller;

use Combodo\iTop\Application\TwigBase\Controller\Controller;
use Combodo\iTop\CoreUpdate\Service\CoreUpdater;
use Combodo\iTop\DBTools\Service\DBToolsUtils;
use Dict;
use Exception;
use SetupUtils;
use utils;

class UpdateController extends Controller
{
    public function OperationSelectUpdateFile()
	{
		$sTransactionId = utils::GetNewTransactionId();
		$aParams = array();
		$aParams['sTransactionId'] = $sTransactionId;
        $aParams['aPreviousInstall'] = $this->GetPreviousInstallations();
        $aParams['sAjaxURL'] = utils::GetAbsoluteUrlModulePage('itop-core-update', 'ajax.php', array('maintenance' => 'true'));
        $aParams['iDiskFreeSpace'] = disk_free_space(APPROOT);
        $aParams['sDiskFreeSpace'] = utils::BytesToFriendlyFormat($aParams['iDiskFreeSpace']);
		$aParams['iFileUploadMaxSize'] = $this->GetFileUploadMaxSize();
		$aParams['sFileUploadMaxSize'] = utils::BytesToFriendlyFormat($aParams['iFileUploadMaxSize']);
		$aParams['sPostMaxSize'] = ini_get('post_max_size');
		$aParams['sUploadMaxSize'] = ini_get('upload_max_filesize');

        $this->DisplayPage($aParams);
	}

	/**
	 * @throws \Exception
	 */
	public function OperationConfirmUpdate()
	{
		$sTransactionId = utils::ReadPostedParam('transaction_id', '', 'transaction_id');
		if (!utils::IsTransactionValid($sTransactionId))
		{
			throw new Exception(Dict::S('iTopUpdate:Error:InvalidToken'));
		}

		$bDoBackup = utils::ReadPostedParam('doBackup', 0, 'integer') == 1;
		$bDoFilesArchive = utils::ReadPostedParam('doFilesArchive', 0, 'integer') == 1;

        $sNewVersion = null;
		$sName = '';
        $sVersionToInstall = '';
        $sError = '';
        try
        {
            if (isset($_FILES['file']))
            {
                $aFileInfo = $_FILES['file'];
                $iError = $aFileInfo['error'];
                if ($iError === UPLOAD_ERR_OK)
                {
                    $sDownloadDir = CoreUpdater::DOWNLOAD_DIR;
                    if (is_dir($sDownloadDir))
                    {
                        SetupUtils::rrmdir($sDownloadDir);
                    }
                    SetupUtils::builddir($sDownloadDir);
                    $sTmpName = $aFileInfo['tmp_name'];
                    $sName = $aFileInfo['name'];
                    $sNewVersion = $sDownloadDir.$sName;
                    if (@move_uploaded_file($sTmpName, $sNewVersion) === false)
                    {
                        throw new Exception(Dict::S('iTopUpdate:Error:FileNotFound'));
                    }
                    CoreUpdater::ExtractDownloadedFile($sNewVersion);
                    $sVersionToInstall = CoreUpdater::GetVersionToInstall();
                }
                else
                {
                    throw new Exception(Dict::S('iTopUpdate:Error:NoFile'));
                }
            }
            else
            {
                throw new Exception(Dict::S('iTopUpdate:Error:NoFile'));
            }
        }
        catch (Exception $e)
        {
            $iError = UPLOAD_ERR_NO_FILE;
            $sError = $e->getMessage();
        }


		$aParams = array();
        $aParams['sName'] = $sName;
        $aParams['bSuccess'] = ($iError == 0);
        $aParams['sError'] = $sError;

		$aParams['bDoBackup'] = $bDoBackup;
		$aParams['bDoFilesArchive'] = $bDoFilesArchive;
        $aParams['sItopArchive'] = CoreUpdater::GetItopArchiveFile();
        $aParams['sBackupFile'] = CoreUpdater::GetBackupFile();

        $sQuestion = Dict::Format('iTopUpdate:UI:ConfirmInstallFile', $sVersionToInstall);
		$aParams['sQuestion'] = $sQuestion;

        $sTransactionId = utils::GetNewTransactionId();
		$aParams['sTransactionId'] = $sTransactionId;

		$this->AddSaas('env-'.utils::GetCurrentEnvironment().'/itop-core-update/css/itop-core-update.scss');
		$this->DisplaySetupPage($aParams);
	}

	public function OperationUpdateCoreFiles()
    {
        $sTransactionId = utils::ReadPostedParam('transaction_id', '', 'transaction_id');
        if (!utils::IsTransactionValid($sTransactionId))
        {
            throw new Exception(Dict::S('iTopUpdate:Error:InvalidToken'));
        }

        $sNewVersion = utils::ReadPostedParam('filename', null, 'filename');
        $bDoBackup = utils::ReadPostedParam('doBackup', 0, 'integer') == 1;
		$bDoFilesArchive = utils::ReadPostedParam('doFilesArchive', 0, 'integer') == 1;
        $sCurrentVersion = Dict::Format('UI:iTopVersion:Long', ITOP_APPLICATION, ITOP_VERSION, ITOP_REVISION, ITOP_BUILD_DATE);

        $aParams = array(
            'sCurrentVersion' => $sCurrentVersion,
            'bDoBackup' => $bDoBackup,
	        'sBackupFile' => CoreUpdater::GetBackupFile(),
			'bDoFilesArchive' => $bDoFilesArchive,
	        'sItopArchive' => CoreUpdater::GetItopArchiveFile(),
            'sNewVersion' => $sNewVersion,
            'sProgressImage' => utils::GetAbsoluteUrlAppRoot().'setup/orange-progress.gif',
            'sSetupToken' => SetupUtils::CreateSetupToken(),
            'sAjaxURL' => utils::GetAbsoluteUrlModulePage('itop-core-update', 'ajax.php', array('maintenance' => 'true')),
        );
        $this->AddLinkedScript(utils::GetAbsoluteUrlAppRoot().'setup/jquery.progression.js');
        $this->AddSaas('env-'.utils::GetCurrentEnvironment().'/itop-core-update/css/itop-core-update.scss');

        $this->DisplaySetupPage($aParams);
    }

    public function OperationRunSetup()
    {
	    SetupUtils::CheckSetupToken(true);
	    $sConfigFile = APPCONF.'production/'.ITOP_CONFIG_FILE;
	    @chmod($sConfigFile, 0770);
	    $sRedirectURL = utils::GetAbsoluteUrlAppRoot().'setup/index.php';
	    header("Location: $sRedirectURL");
    }

    private function GetPreviousInstallations()
    {
        return DBToolsUtils::GetPreviousInstallations();
    }

	// Returns a file size limit in bytes based on the PHP upload_max_filesize
	// and post_max_size
	private function GetFileUploadMaxSize()
	{
		static $iMaxSize = -1;

		if ($iMaxSize < 0)
		{
			// Start with post_max_size.
			$iPostMaxSize = $this->ParseSize(ini_get('post_max_size'));
			if ($iPostMaxSize > 0)
			{
				$iMaxSize = $iPostMaxSize;
			}

			// If upload_max_size is less, then reduce. Except if upload_max_size is
			// zero, which indicates no limit.
			$iUploadMax = $this->ParseSize(ini_get('upload_max_filesize'));
			if ($iUploadMax > 0 && $iUploadMax < $iMaxSize)
			{
				$iMaxSize = $iUploadMax;
			}
		}
		return $iMaxSize;
	}

	private function ParseSize($iSize)
	{
		$sUnit = preg_replace('/[^bkmgtpezy]/i', '', $iSize); // Remove the non-unit characters from the size.
		$iSize = preg_replace('/[^0-9.]/', '', $iSize); // Remove the non-numeric characters from the size.
		if ($sUnit)
		{
			// Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
			return round($iSize * pow(1024, stripos('bkmgtpezy', $sUnit[0])));
		}
		else
		{
			return round($iSize);
		}
	}
}

<?php
/**
 * @copyright   Copyright (C) 2010-2019 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\CoreUpdate\Controller;


use Combodo\iTop\Application\TwigBase\Controller\Controller;
use Combodo\iTop\CoreUpdate\Service\CoreUpdater;
use Combodo\iTop\DBTools\Service\DBToolsUtils;
use Combodo\iTop\FilesInformation\Service\FileNotExistException;
use Combodo\iTop\FilesInformation\Service\FilesInformation;
use Dict;
use Exception;
use IssueLog;
use MetaModel;
use SetupUtils;
use utils;

class AjaxController extends Controller
{
	public function OperationCanUpdateCore()
	{
		$aParams = array();

		try
		{
			$sCanUpdateCore = FilesInformation::CanUpdateCore($sMessage);
			$bCanUpdateCore = ($sCanUpdateCore == 'Yes');
			$aParams['bStatus'] = $bCanUpdateCore;
			if ($bCanUpdateCore)
			{
				$aParams['sMessage'] = Dict::S('iTopUpdate:UI:CanCoreUpdate:Yes');
			}
			else
			{
				$sLink = utils::GetAbsoluteUrlAppRoot().'setup/';
				$aParams['sMessage']  = Dict::Format('iTopUpdate:UI:CannotUpdateUseSetup', $sLink);
			}
		} catch (FileNotExistException $e)
		{
			$aParams['bStatus'] = false;
			$aParams['sMessage'] = Dict::Format('iTopUpdate:UI:CanCoreUpdate:ErrorFileNotExist', $e->getMessage());
		} catch (Exception $e)
		{
			$aParams['bStatus'] = false;
			$aParams['sMessage'] = Dict::Format('iTopUpdate:UI:CanCoreUpdate:Error', $e->getMessage());
		}

		$this->DisplayJSONPage($aParams);
	}

	public function OperationGetItopDiskSpace()
	{
		$aParams = array();
		$aParams['iItopDiskSpace'] = FilesInformation::GetItopDiskSpace();
		$aParams['sItopDiskSpace'] = utils::BytesToFriendlyFormat($aParams['iItopDiskSpace']);
		$this->DisplayJSONPage($aParams);
	}

	public function OperationGetDBDiskSpace()
	{
		$aParams = array();
		$aParams['iDBDiskSpace'] = DBToolsUtils::GetDatabaseSize();
		$aParams['sDBDiskSpace'] = utils::BytesToFriendlyFormat($aParams['iDBDiskSpace']);
		$this->DisplayJSONPage($aParams);
	}

	public function OperationGetCurrentVersion()
	{
		$aParams = array();
		$aParams['sVersion'] = Dict::Format('UI:iTopVersion:Long', ITOP_APPLICATION, ITOP_VERSION, ITOP_REVISION, ITOP_BUILD_DATE);
		$this->DisplayJSONPage($aParams);
	}

	public function OperationEnterMaintenance()
	{
		$aParams = array();
		try
		{
			SetupUtils::CheckSetupToken();
			SetupUtils::EnterReadOnlyMode(MetaModel::GetConfig());
			$iResponseCode = 200;
		} catch (Exception $e)
		{
			IssueLog::Error("EnterMaintenance: ".$e->getMessage());
			$aParams['sError'] = $e->getMessage();
			$iResponseCode = 500;
		}
		$this->DisplayJSONPage($aParams, $iResponseCode);
	}

	public function OperationExitMaintenance()
	{
		$aParams = array();
		try
		{
			SetupUtils::CheckSetupToken(true);
			SetupUtils::ExitReadOnlyMode();
			$iResponseCode = 200;
		} catch (Exception $e)
		{
			IssueLog::Error("ExitMaintenance: ".$e->getMessage());
			$aParams['sError'] = $e->getMessage();
			$iResponseCode = 500;
		}
		$this->DisplayJSONPage($aParams, $iResponseCode);
	}

	public function OperationBackup()
	{
		$aParams = array();
		try
		{
			SetupUtils::CheckSetupToken();
			CoreUpdater::Backup();
			$iResponseCode = 200;
		} catch (Exception $e)
		{
			IssueLog::Error("Backup: ".$e->getMessage());
			$aParams['sError'] = $e->getMessage();
			$iResponseCode = 500;
		}
		$this->DisplayJSONPage($aParams, $iResponseCode);
	}

	public function OperationFilesArchive()
	{
		$aParams = array();
		try
		{
			SetupUtils::CheckSetupToken();
			CoreUpdater::CreateItopArchive();
			$iResponseCode = 200;
		} catch (Exception $e)
		{
			IssueLog::Error("FilesArchive: ".$e->getMessage());
			$aParams['sError'] = $e->getMessage();
			$iResponseCode = 500;
		}
		$this->DisplayJSONPage($aParams, $iResponseCode);
	}

	public function OperationCopyFiles()
	{
		$aParams = array();
		try
		{
			SetupUtils::CheckSetupToken();
			CoreUpdater::CopyCoreFiles();
			$iResponseCode = 200;
		} catch (Exception $e)
		{
			IssueLog::Error("CopyFiles: ".$e->getMessage());
			$aParams['sError'] = $e->getMessage();
			$iResponseCode = 500;
		}

		$this->DisplayJSONPage($aParams, $iResponseCode);
	}

	public function OperationCheckCompile()
	{
		$aParams = array();
		try
		{
			SetupUtils::CheckSetupToken();
			CoreUpdater::CheckCompile();
			$iResponseCode = 200;
		}
		catch (Exception $e)
		{
			IssueLog::Error("Compile: ".$e->getMessage());
			$aParams['sError'] = $e->getMessage();
			$iResponseCode = 500;
		}

		$this->DisplayJSONPage($aParams, $iResponseCode);
	}

	public function OperationCompile()
	{
		$aParams = array();
		try
		{
			SetupUtils::CheckSetupToken();
			CoreUpdater::Compile();
			$iResponseCode = 200;
		}
		catch (Exception $e)
		{
			IssueLog::Error("Compile: ".$e->getMessage());
			$aParams['sError'] = $e->getMessage();
			$iResponseCode = 500;
		}

		$this->DisplayJSONPage($aParams, $iResponseCode);
	}

	public function OperationUpdateDatabase()
	{
		$aParams = array();
		try
		{
			SetupUtils::CheckSetupToken();
			CoreUpdater::UpdateDatabase();
			$iResponseCode = 200;
		}
		catch (Exception $e)
		{
			IssueLog::Error("Compile: ".$e->getMessage());
			$aParams['sError'] = $e->getMessage();
			$iResponseCode = 500;
		}

		$this->DisplayJSONPage($aParams, $iResponseCode);
	}
}

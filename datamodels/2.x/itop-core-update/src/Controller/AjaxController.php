<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\CoreUpdate\Controller;


use Combodo\iTop\Application\TwigBase\Controller\Controller;
use Combodo\iTop\CoreUpdate\Service\CoreUpdater;
use Combodo\iTop\DBTools\Service\DBToolsUtils;
use Combodo\iTop\FilesInformation\Service\FileNotExistException;
use Combodo\iTop\FilesInformation\Service\FilesInformation;
use ContextTag;
use Dict;
use Exception;
use IssueLog;
use MetaModel;
use SecurityException;
use SetupUtils;
use utils;

class AjaxController extends Controller
{
	public const ROUTE_NAMESPACE = 'core_update_ajax';
	protected $oCtxCoreUpdate;

	/**
	 * @param $sViewPath
	 * @param $sModuleName
	 * @param $aAdditionalPaths
	 *
	 * @throws \ConfigException
	 * @throws \CoreException
	 * @throws \DictExceptionUnknownLanguage
	 * @throws \MySQLException
	 */
	public function __construct($sViewPath = '', $sModuleName = 'core', $aAdditionalPaths = [])
	{
		if (!defined('MODULESROOT'))
		{
			define('MODULESROOT', APPROOT.'env-production/');
		}

		require_once(MODULESROOT.'itop-core-update/src/Service/RunTimeEnvironmentCoreUpdater.php');
		require_once(MODULESROOT.'itop-core-update/src/Service/CoreUpdater.php');
		require_once(MODULESROOT.'itop-core-update/src/Controller/AjaxController.php');

		MetaModel::LoadConfig(utils::GetConfig());

		$sViewPath = MODULESROOT.'itop-core-update/templates';
		$sModuleName = 'itop-core-update';
		parent::__construct($sViewPath, $sModuleName, $aAdditionalPaths);

		$this->DisableInDemoMode();
		$this->AllowOnlyAdmin();
		$this->CheckAccess();
		$this->oCtxCoreUpdate = new ContextTag(ContextTag::TAG_SETUP);
	}

	public function OperationCanUpdateCore()
	{
		$aParams = [];

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
				$sLinkManualUpdate = 'https://www.itophub.io/wiki/page?id='.utils::GetItopVersionWikiSyntax().'%3Ainstall%3Aupgrading_itop#manually';
				$aParams['sMessage']  = Dict::Format('iTopUpdate:UI:CannotUpdateUseSetup', $sLink, $sLinkManualUpdate);
				$aParams['sMessageDetails']  = $sMessage;
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
		$aParams = [];
		$aParams['iItopDiskSpace'] = FilesInformation::GetItopDiskSpace();
		$aParams['sItopDiskSpace'] = utils::BytesToFriendlyFormat($aParams['iItopDiskSpace']);
		$this->DisplayJSONPage($aParams);
	}

	public function OperationGetDBDiskSpace()
	{
		$aParams = [];
		$aParams['iDBDiskSpace'] = DBToolsUtils::GetDatabaseSize();
		$aParams['sDBDiskSpace'] = utils::BytesToFriendlyFormat($aParams['iDBDiskSpace']);
		$this->DisplayJSONPage($aParams);
	}

	public function OperationGetCurrentVersion()
	{
		$aParams = [];
		$aParams['sVersion'] = Dict::Format('UI:iTopVersion:Long', ITOP_APPLICATION, ITOP_VERSION, ITOP_REVISION, ITOP_BUILD_DATE);
		$this->DisplayJSONPage($aParams);
	}

	public function OperationEnterMaintenance()
	{
		$aParams = [];
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
		$aParams = [];
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
		$aParams = [];
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
		$aParams = [];
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
		$aParams = [];
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
		$aParams = [];
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
		$aParams = [];
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
		$aParams = [];
		try
		{
			SetupUtils::CheckSetupToken();
			CoreUpdater::UpdateDatabase();
			$iResponseCode = 200;
		}
		catch (Exception $e) {
			IssueLog::Error("Compile: ".$e->getMessage());
			$aParams['sError'] = $e->getMessage();
			$iResponseCode = 500;
		}

		$this->DisplayJSONPage($aParams, $iResponseCode);
	}

	/**
	 * @throws \SecurityException if CSRF token invalid
	 *
	 * @since 3.1.0 N°4919
	 */
	public function OperationLaunchSetup()
	{
		$sTransactionId = utils::ReadParam('transaction_id', '', false, 'transaction_id');
		if (false === utils::IsTransactionValid($sTransactionId)) {
			throw new SecurityException('Access forbidden');
		}

		$sConfigFile = APPCONF.'production/config-itop.php';
		@chmod($sConfigFile, 0770); // Allow overwriting the file

		header('Location: ../setup/');
	}
}

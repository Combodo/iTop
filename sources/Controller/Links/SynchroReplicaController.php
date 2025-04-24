<?php

namespace Combodo\iTop\Controller\Links;

use ApplicationContext;
use ApplicationException;
use Combodo\iTop\Application\TwigBase\Controller\Controller;
use Combodo\iTop\Application\UI\Base\Component\Button\ButtonUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\DataTable\DataTableUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Panel\PanelUIBlockFactory;
use DBObjectSearch;
use Dict;
use Exception;
use iTopWebPage;
use MetaModel;
use utils;

class SynchroReplicaController extends Controller
{
	public const ROUTE_NAMESPACE = 'synchroreplica';

	public function __construct($sViewPath = '', $sModuleName = 'core', $aAdditionalPaths = [])
	{
		$sViewPath = APPROOT.'synchro';
		parent::__construct($sViewPath, $sModuleName, $aAdditionalPaths);

		// Previously in index.php
		$this->DisableInDemoMode();
		$this->AllowOnlyAdmin();
		$this->CheckAccess();
	}

	public static function OperationUnlinkAll(iTopWebPage $oP, ApplicationContext $oAppContext, $sOperation = 'unlink'): void
	{
		$oP->DisableBreadCrumb();
		$sClass = utils::ReadParam('class', '', false, 'class');
		$sFilter = utils::ReadPostedParam('filter', '', 'raw_data');
		$oFullSetFilter = DBObjectSearch::unserialize($sFilter);
		// Add user filter
		$oFullSetFilter->UpdateContextFromUser();
		$aSelectObject = utils::ReadMultipleSelection($oFullSetFilter);
		if ( empty($sClass) || empty($aSelectObject)) // TO DO: check that the class name is valid !
		{
			throw new ApplicationException(Dict::Format('UI:Error:2ParametersMissing', 'class', 'selectObject[]'));
		}
		$sCancelUrl = "./UI.php?operation=search&filter=".urlencode($sFilter)."&".$oAppContext->GetForLink();
		$aContext = array(
			'filter'    => utils::EscapeHtml($sFilter),
			'selectObj' => $aSelectObject,
		);

		$aHeaders = array(
			'object' => array('label' => MetaModel::GetName($sClass), 'description' => Dict::S('UI:ModifiedObject')),
			'status' => array(
				'label'       => Dict::S('UI:BulkModifyStatus'),
				'description' => Dict::S('UI:BulkModifyStatus+'),
			),
			'errors' => array(
				'label'       => Dict::S('UI:BulkModifyErrors'),
				'description' => Dict::S('UI:BulkModifyErrors+'),
			),
		);
		$aRows = array();


		$sHeaderTitle = Dict::Format('UI:Modify_N_ObjectsOf_Class', count($aSelectObject), MetaModel::GetName($sClass));
		$sClassIcon = MetaModel::GetClassIcon($sClass, false);

		// Not in preview mode, do the update for real
		$sTransactionId = utils::ReadPostedParam('transaction_id', '', 'transaction_id');
		if (!utils::IsTransactionValid($sTransactionId, false)) {
			throw new Exception(Dict::S('UI:Error:ObjectAlreadyUpdated'));
		}
		utils::RemoveTransaction($sTransactionId);

		// Avoid too many events
		$iPreviousTimeLimit = ini_get('max_execution_time');
		$iLoopTimeLimit = MetaModel::GetConfig()->Get('max_execution_time_per_loop');
		$aErrors = [];
		foreach ($aSelectObject as $iId) {
			set_time_limit(intval($iLoopTimeLimit));
			/** @var \cmdbAbstractObject $oObj */
			$oReplica = MetaModel::GetObject('SynchroReplica', $iId);
			$bResult = true;
			try {
				if (in_array($sOperation, ['unlink', 'unlinksynchro'])) {
					\IssueLog::Error('unlinking replica '.$oReplica->GetKey());
					$oReplica->UnLink();
				}
				if (in_array($sOperation, ['synchro', 'unlinksynchro'])) {
					\IssueLog::Error('synchro replica '.$oReplica->GetKey());
					$oStatLog = $oReplica->ReSynchro();
					$aErrors = $oStatLog->GetTraces();
				}
			}
			catch (Exception $e) {
				$bResult = false;
				$aErrors[] = $e->getMessage();
			}
			catch (Error $e) {
				$bResult = false;
				$aErrors[] = $e->getMessage();
			}

			$sStatus = $bResult ? Dict::S('UI:BulkModifyStatusModified') : Dict::S('UI:BulkModifyStatusSkipped');

			$aErrorsToDisplay = array_map(function ($sError) {
				return utils::HtmlEntities($sError);
			}, $aErrors);
			$aRows[] = array(
				'object' => $oReplica->GetHyperlink(),
				'status' => $sStatus,
				'errors' => '<p>'.($bResult ? '' : implode('</p><p>', $aErrorsToDisplay)).'</p>',
			);
		}

		set_time_limit(intval($iPreviousTimeLimit));
		$oTable = DataTableUIBlockFactory::MakeForForm('BulkModify', $aHeaders, $aRows);
		$oTable->AddOption("bFullscreen", true);

		$oPanel = PanelUIBlockFactory::MakeForClass($sClass, '');
		$oPanel->SetIcon($sClassIcon);
		$oPanel->SetTitle($sHeaderTitle);
		$oPanel->AddCSSClass('ibo-datatable-panel');
		$oPanel->AddSubBlock($oTable);

		$oP->AddUiBlock($oPanel);
		$oP->AddSubBlock(ButtonUIBlockFactory::MakeForSecondaryAction(Dict::S('UI:Button:Done')))->SetOnClickJsCode("window.location.href='$sCancelUrl'")->AddCSSClass('mt-5');


	}
}
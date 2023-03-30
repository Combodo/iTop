<?php
/*
 * @copyright   Copyright (C) 2010-2022 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Controller\Base\Layout;

use AjaxPage;
use ApplicationException;
use cmdbAbstractObject;
use CMDBObjectSet;
use Combodo\iTop\Application\UI\Base\Component\Alert\AlertUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\QuickCreate\QuickCreateHelper;
use Combodo\iTop\Application\UI\Base\Layout\PageContent\PageContentFactory;
use Combodo\iTop\Controller\AbstractController;
use Combodo\iTop\Service\Base\ObjectRepository;
use CoreCannotSaveObjectException;
use DeleteException;
use Dict;
use Exception;
use IssueLog;
use iTopOwnershipLock;
use iTopWebPage;
use JsonPage;
use MetaModel;
use SecurityException;
use UserRights;
use utils;

/**
 * Class ObjectController
 *
 * @internal
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @since 3.1.0
 * @package Combodo\iTop\Controller\Base\Layout
 */
class ObjectController extends AbstractController
{
	public const ROUTE_NAMESPACE = 'object';

	/**
	 * @return \iTopWebPage|\AjaxPage Object edit form in its webpage
	 * @throws \ApplicationException
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \SecurityException
	 */
	public function OperationModify()
	{
		$bPrintable = utils::ReadParam('printable', '0') === '1';
		$sClass = utils::ReadParam('class', '', false, 'class');
		$sId = utils::ReadParam('id', '');

		// Check parameters
		if (utils::IsNullOrEmptyString($sClass) || utils::IsNullOrEmptyString($sId))
		{
			throw new ApplicationException(Dict::Format('UI:Error:2ParametersMissing', 'class', 'id'));
		}

		$oObj = MetaModel::GetObject($sClass, $sId, false);
		// Check user permissions
		// - Is allowed to view it?
		if (is_null($oObj)) {
			throw new ApplicationException(Dict::S('UI:ObjectDoesNotExist'));
		}

		// - Is allowed to edit it?
		$oSet = CMDBObjectSet::FromObject($oObj);
		if (UserRights::IsActionAllowed($sClass, UR_ACTION_MODIFY, $oSet) == UR_ALLOWED_NO) {
			throw new SecurityException('User not allowed to modify this object', array('class' => $sClass, 'id' => $sId));
		}

		// Prepare web page (should more likely be some kind of response object like for Symfony)
		$aFormExtraParams = array('wizard_container' => 1);
		if ($this->IsHandlingXmlHttpRequest()) {
			$oPage = new AjaxPage('');
			$aFormExtraParams['js_handlers'] = [];
			$aFormExtraParams['noRelations'] = true;
			$aFormExtraParams['hide_transitions'] = true;
			// We display this form in a modal, once we submit (in ajax) we probably want to only close the modal 
			$aFormExtraParams['js_handlers']['form_on_submit'] =
				<<<JS
				event.preventDefault();
				if(bOnSubmitForm === true)
				{
					let oForm = $(this);
					let sUrl = oForm.attr('action');
					let sPosting = $.post( sUrl, oForm.serialize());

					/* Alerts the results */
					sPosting.done(function(data) {
                        // fire event
                        oForm.trigger('itop.form.submitted', [data]);
						if(data.success !== undefined && data.success === true) {
							oForm.closest('[data-role="ibo-modal"]').dialog('close');
						}
						else {
                            /* We're not in submit anymore */
                            window.bInSubmit = false;
                            oForm.attr('data-form-state', 'default');
                            /* Display error popup */
							CombodoModal.OpenErrorModal(data.data.error_message);
						}
					});
				}
JS;


			$aFormExtraParams['js_handlers']['cancel_button_on_click'] =
				<<<JS
				function() {
					$(this).closest('[data-role="ibo-modal"]').dialog('close');
				};
JS;

		} else {
			$oPage = new iTopWebPage('', $bPrintable);
			$oPage->DisableBreadCrumb();
			$oPage->SetContentLayout(PageContentFactory::MakeForObjectDetails($oObj, cmdbAbstractObject::ENUM_DISPLAY_MODE_EDIT));
		}
		// - JS files
		foreach (static::EnumRequiredForModificationJsFilesRelPaths() as $sJsFileRelPath) {
			$oPage->add_linked_script(utils::GetAbsoluteUrlAppRoot().$sJsFileRelPath);
		}

		// Note: Code duplicated to the case 'apply_modify' in UI.php when a data integrity issue has been found
		$oObj->DisplayModifyForm($oPage, $aFormExtraParams); // wizard_container: Display the title above the form

		return $oPage;
	}
	
	/**
	 * @return \iTopWebPage|\JsonPage Object edit form in its webpage
	 * @throws \ApplicationException
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \SecurityException
	 */
	public function OperationApplyNew()
	{
		$bPrintable = utils::ReadParam('printable', '0') === '1';
		$aResult = [];
		if ($this->IsHandlingXmlHttpRequest()) {
			$oPage = new JsonPage();
			$oPage->SetOutputDataOnly(true);
			$aResult['success'] = false;
		} else {
			$oPage = new iTopWebPage('', $bPrintable);
			$oPage->DisableBreadCrumb();
			$this->AddRequiredForModificationJsFilesToPage($oPage);
		}
		
		$sClass = utils::ReadPostedParam('class', '', 'class');
		$sClassLabel = MetaModel::GetName($sClass);
		$sFormPrefix = utils::ReadPostedParam('formPrefix', '', utils::ENUM_SANITIZATION_FILTER_STRING);
		$sTransactionId = utils::ReadPostedParam('transaction_id', '', 'transaction_id');
		$aErrors = array();
		$aWarnings = array();
		if ( empty($sClass) )
		{
			IssueLog::Trace(__CLASS__.'::'.__METHOD__.' Object not created (empty class)', $sClass, array(
				'$sTransactionId' => $sTransactionId,
				'$sUser' => UserRights::GetUser(),
				'HTTP_REFERER' => @$_SERVER['HTTP_REFERER'],
				'REQUEST_URI' => @$_SERVER['REQUEST_URI'],
			));

			throw new ApplicationException(Dict::Format('UI:Error:1ParametersMissing', 'class'));
		}
		if (!utils::IsTransactionValid($sTransactionId, false))
		{
			$sUser = UserRights::GetUser();
			IssueLog::Error(__CLASS__.'::'.__METHOD__." : invalid transaction_id ! data: user='$sUser', class='$sClass'");
	
			if ($this->IsHandlingXmlHttpRequest()) {
				$aResult['data'] = ['error_message' => Dict::S('UI:Error:ObjectAlreadyCreated')];
			} else {
				$oErrorAlert = AlertUIBlockFactory::MakeForFailure(Dict::S('UI:Error:ObjectAlreadyCreated'));
				$oErrorAlert->SetIsClosable(false)
					->SetIsCollapsible(false);
				$oPage->AddUiBlock($oErrorAlert);
			}

			IssueLog::Trace(__CLASS__.'::'.__METHOD__.' Object not created (invalid transaction_id)', $sClass, array(
				'$sTransactionId' => $sTransactionId,
				'$sUser' => UserRights::GetUser(),
				'HTTP_REFERER' => @$_SERVER['HTTP_REFERER'],
				'REQUEST_URI' => @$_SERVER['REQUEST_URI'],
			));
		}
		else
		{
			$oObj = MetaModel::NewObject($sClass);
			if (MetaModel::HasLifecycle($sClass))
			{
				$sStateAttCode = MetaModel::GetStateAttributeCode($sClass);
				$sTargetState = utils::ReadPostedParam('obj_state', '');
				if ($sTargetState != '')
				{
					$sOrigState = utils::ReadPostedParam('obj_state_orig', '');
					if ($sTargetState != $sOrigState)
					{
						$aWarnings[] = Dict::S('UI:StateChanged');
					}
					$oObj->Set($sStateAttCode, $sTargetState);
				}
			}
			$aErrors = $oObj->UpdateObjectFromPostedForm($sFormPrefix);
		}
		if (isset($oObj) && is_object($oObj))
		{
			$sClass = get_class($oObj);
			$sClassLabel = MetaModel::GetName($sClass);

			try
			{
				if (!empty($aErrors) || !empty($aWarnings))
				{
					IssueLog::Trace(__CLASS__.'::'.__METHOD__.' Object not created (see $aErrors)', $sClass, array(
						'$sTransactionId' => $sTransactionId,
						'$aErrors' => $aErrors,
						'$sUser' => UserRights::GetUser(),
						'HTTP_REFERER' => @$_SERVER['HTTP_REFERER'],
						'REQUEST_URI' => @$_SERVER['REQUEST_URI'],
					));

					throw new CoreCannotSaveObjectException(array('id' => $oObj->GetKey(), 'class' => $sClass, 'issues' => $aErrors));
				}

				$oObj->DBInsertNoReload();// No need to reload

				IssueLog::Trace(__CLASS__.'::'.__METHOD__.' Object created', $sClass, array(
					'$id' => $oObj->GetKey(),
					'$sTransactionId' => $sTransactionId,
					'$aErrors' => $aErrors,
					'$sUser' => UserRights::GetUser(),
					'HTTP_REFERER' => @$_SERVER['HTTP_REFERER'],
					'REQUEST_URI' => @$_SERVER['REQUEST_URI'],
				));

				utils::RemoveTransaction($sTransactionId);
				$oPage->set_title(Dict::S('UI:PageTitle:ObjectCreated'));
				QuickCreateHelper::AddClassToHistory($sClass);

				// Compute the name, by reloading the object, even if it disappeared from the silo
				$oObj = MetaModel::GetObject($sClass, $oObj->GetKey(), true /* Must be found */, true /* Allow All Data*/);
				$sName = $oObj->GetName();
				$sMessage = Dict::Format('UI:Title:Object_Of_Class_Created', $sName, $sClassLabel);

				$sNextAction = utils::ReadPostedParam('next_action', '');
				if (!empty($sNextAction)) {
					$oPage->add("<h1>$sMessage</h1>");
					try {
						ApplyNextAction($oPage, $oObj, $sNextAction);
					}
					catch (ApplicationException $e) {
						$sMessage = $e->getMessage();
						$sSeverity = 'info';
						ReloadAndDisplay($oPage, $oObj, 'create', $sMessage, $sSeverity);
					}
				} else {
					// Nothing more to do
					if ($this->IsHandlingXmlHttpRequest()) {
						$aResult['success'] = true;
						$aResult['data'] = ['object' => ObjectRepository::ConvertObjectToArray($oObj, $sClass)];
					} else {
						ReloadAndDisplay($oPage, $oObj, 'create', $sMessage, 'ok');
					}
				}
			}
			catch (CoreCannotSaveObjectException $e) {
				// Found issues, explain and give the user a second chance
				//
				$aIssues = $e->getIssues();
				if ($this->IsHandlingXmlHttpRequest()) {
					$aResult['data'] = ['error_message' => $e->getHtmlMessage()];
				} else {
					$sClassLabel = MetaModel::GetName($sClass);

					$oPage->set_title(Dict::Format('UI:CreationPageTitle_Class', $sClassLabel));
					if (!empty($aIssues)) {
						$oPage->AddHeaderMessage($e->getHtmlMessage(), 'message_error');
					}
					if (!empty($aWarnings)) {
						$sWarnings = implode(', ', $aWarnings);
						$oPage->AddHeaderMessage($sWarnings, 'message_warning');
					}
					$oPage->SetContentLayout(PageContentFactory::MakeForObjectDetails($oObj, cmdbAbstractObject::ENUM_DISPLAY_MODE_CREATE));
					cmdbAbstractObject::DisplayCreationForm($oPage, $sClass, $oObj, [], ['transaction_id' => $sTransactionId, 'wizard_container' => 1, 'keep_source_object' => true]);
				}
			}
		}
		if ($this->IsHandlingXmlHttpRequest()) {
			$oPage->SetData($aResult);
		}

		return $oPage;
	}

	/**
	 * @return \iTopWebPage|\JsonPage
	 * @throws \ApplicationException
	 * @throws \ArchivedObjectException
	 * @throws \ConfigException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \DictExceptionMissingString
	 * @throws \MySQLException
	 */
	public function OperationApplyModify(){
		$bPrintable = utils::ReadParam('printable', '0') === '1';
		$aResult = [];
		if ($this->IsHandlingXmlHttpRequest()) {
			$oPage = new JsonPage();
			$oPage->SetOutputDataOnly(true);
			$aResult['success'] = false;
		} else {
			$oPage = new iTopWebPage('', $bPrintable);
			$oPage->DisableBreadCrumb();
			$this->AddRequiredForModificationJsFilesToPage($oPage);
		}

		$sClass = utils::ReadPostedParam('class', '', 'class');
		$sClassLabel = MetaModel::GetName($sClass);
		$id = utils::ReadPostedParam('id', '');
		$sTransactionId = utils::ReadPostedParam('transaction_id', '', 'transaction_id');
		if ( empty($sClass) || empty($id))
		{
			IssueLog::Trace(__CLASS__.'::'.__METHOD__.' Object not updated (empty class or id)', $sClass, array(
				'$id' => $id,
				'$sTransactionId' => $sTransactionId,
				'$sUser' => UserRights::GetUser(),
				'HTTP_REFERER' => @$_SERVER['HTTP_REFERER'],
				'REQUEST_URI' => @$_SERVER['REQUEST_URI'],
			));
			// TODO 3.1 Do not crash with an exception in ajax
			throw new ApplicationException(Dict::Format('UI:Error:2ParametersMissing', 'class', 'id'));
		}
		$bDisplayDetails = true;
		$oObj = MetaModel::GetObject($sClass, $id, false);
		if ($oObj === null)
		{
			$bDisplayDetails = false;

			if ($this->IsHandlingXmlHttpRequest()) {
				$aResult['data'] = ['error_message' => Dict::S('UI:ObjectDoesNotExist')];
			} else {
				$oPage->set_title(Dict::S('UI:ErrorPageTitle'));
				
				$oErrorAlert = AlertUIBlockFactory::MakeForFailure(Dict::S('UI:ObjectDoesNotExist'));
				$oErrorAlert->SetIsClosable(false)
					->SetIsCollapsible(false);
				$oPage->AddUiBlock($oErrorAlert);

			}
			
			IssueLog::Trace(__CLASS__.'::'.__METHOD__.' Object not updated (id not found)', $sClass, array(
				'$id' => $id,
				'$sTransactionId' => $sTransactionId,
				'$sUser' => UserRights::GetUser(),
				'HTTP_REFERER' => @$_SERVER['HTTP_REFERER'],
				'REQUEST_URI' => @$_SERVER['REQUEST_URI'],
			));
		}
		elseif (!utils::IsTransactionValid($sTransactionId, false))
		{
			//TODO: since $bDisplayDetails= true, there will be an redirection, thus, the content generated here is ignored, only the $sMessage and $sSeverity are used after the redirection
			$sUser = UserRights::GetUser();
			IssueLog::Error(__CLASS__.'::'.__METHOD__."  : invalid transaction_id ! data: user='$sUser', class='$sClass'");
			
			if ($this->IsHandlingXmlHttpRequest()) {
				$aResult['data'] = ['error_message' => Dict::S('UI:Error:ObjectAlreadyUpdated')];
			} else {
				$oPage->set_title(Dict::Format('UI:ModificationPageTitle_Object_Class', $oObj->GetRawName(), $sClassLabel)); // Set title will take care of the encoding
				$oPage->p("<strong>".Dict::S('UI:Error:ObjectAlreadyUpdated')."</strong>\n");
			}
			
			$sMessage = Dict::Format('UI:Error:ObjectAlreadyUpdated', MetaModel::GetName(get_class($oObj)), $oObj->GetName());
			$sSeverity = 'error';

			IssueLog::Trace(__CLASS__.'::'.__METHOD__.' Object not updated (invalid transaction_id)', $sClass, array(
				'$id' => $id,
				'$sTransactionId' => $sTransactionId,
				'$sUser' => UserRights::GetUser(),
				'HTTP_REFERER' => @$_SERVER['HTTP_REFERER'],
				'REQUEST_URI' => @$_SERVER['REQUEST_URI'],
			));
		}
		else
		{
			$aErrors = $oObj->UpdateObjectFromPostedForm();
			$sMessage = '';
			$sSeverity = 'ok';

			if (!$oObj->IsModified() && empty($aErrors))
			{
				if ($this->IsHandlingXmlHttpRequest()) {
					$aResult['data'] = ['error_message' => Dict::Format('UI:Class_Object_NotUpdated', MetaModel::GetName(get_class($oObj)), $oObj->GetName())];
				} else {
					$oPage->set_title(Dict::Format('UI:ModificationPageTitle_Object_Class', $oObj->GetRawName(), $sClassLabel)); // Set title will take care of the encoding
				}
				
				$sMessage = Dict::Format('UI:Class_Object_NotUpdated', MetaModel::GetName(get_class($oObj)), $oObj->GetName());
				$sSeverity = 'info';

				IssueLog::Trace(__CLASS__.'::'.__METHOD__.' Object not updated (see either $aErrors or IsModified)', $sClass, array(
					'$id' => $id,
					'$sTransactionId' => $sTransactionId,
					'$aErrors' => $aErrors,
					'IsModified' => $oObj->IsModified(),
					'$sUser' => UserRights::GetUser(),
					'HTTP_REFERER' => @$_SERVER['HTTP_REFERER'],
					'REQUEST_URI' => @$_SERVER['REQUEST_URI'],
				));
			}
			else
			{
				IssueLog::Trace(__CLASS__.'::'.__METHOD__.' Object updated', $sClass, array(
					'$id' => $id,
					'$sTransactionId' => $sTransactionId,
					'$aErrors' => $aErrors,
					'IsModified' => $oObj->IsModified(),
					'$sUser' => UserRights::GetUser(),
					'HTTP_REFERER' => @$_SERVER['HTTP_REFERER'],
					'REQUEST_URI' => @$_SERVER['REQUEST_URI'],
				));

				try
				{
					if (!empty($aErrors))
					{
						throw new CoreCannotSaveObjectException(array('id' => $oObj->GetKey(), 'class' => $sClass, 'issues' => $aErrors));
					}
					// Transactions are now handled in DBUpdate
					$oObj->DBUpdate();
					$sMessage = Dict::Format('UI:Class_Object_Updated', MetaModel::GetName(get_class($oObj)), $oObj->GetName());
					$sSeverity = 'ok';
					if ($this->IsHandlingXmlHttpRequest()) {
						$aResult['success'] = true;
					}
				}
				catch (CoreCannotSaveObjectException $e)
				{
					// Found issues, explain and give the user a second chance
					//
					$bDisplayDetails = false;
					$aIssues = $e->getIssues();
					if ($this->IsHandlingXmlHttpRequest()) {
						$aResult['data'] = ['error_message' => $e->getHtmlMessage()];
					} else {
						$oPage->AddHeaderMessage($e->getHtmlMessage(), 'message_error');
						$oObj->DisplayModifyForm($oPage,
							array('wizard_container' => true)); // wizard_container: display the wizard border and the title
					}
					
				}
				catch (DeleteException $e)
				{
					if ($this->IsHandlingXmlHttpRequest()) {
						$aResult['data'] = ['error_message' => Dict::Format('UI:Class_Object_NotUpdated', MetaModel::GetName(get_class($oObj)), $oObj->GetName())];
					} else {
						// Say two things:
						// - 1) Don't be afraid nothing was modified
						$sMessage = Dict::Format('UI:Class_Object_NotUpdated', MetaModel::GetName(get_class($oObj)), $oObj->GetName());
						$sSeverity = 'info';
						cmdbAbstractObject::SetSessionMessage(get_class($oObj), $oObj->GetKey(), 'UI:Class_Object_NotUpdated', $sMessage,
							$sSeverity, 0, true /* must not exist */);
						// - 2) Ok, there was some trouble indeed
						$sMessage = $e->getMessage();
						$sSeverity = 'error';
						utils::RemoveTransaction($sTransactionId);
					}
				}
			}
		}
		if ($bDisplayDetails)
		{
			$oObj = MetaModel::GetObject(get_class($oObj), $oObj->GetKey()); //Workaround: reload the object so that the linkedset are displayed properly
			$sNextAction = utils::ReadPostedParam('next_action', '');
			if (!empty($sNextAction))
			{
				try
				{
					ApplyNextAction($oPage, $oObj, $sNextAction);
				}
				catch (ApplicationException $e)
				{
					$sMessage = $e->getMessage();
					$sSeverity = 'info';
					ReloadAndDisplay($oPage, $oObj, 'update', $sMessage, $sSeverity);
				}
			}
			else
			{
				// Nothing more to do
				$sMessage = isset($sMessage) ? $sMessage : '';
				$sSeverity = isset($sSeverity) ? $sSeverity : null;
				if ($this->IsHandlingXmlHttpRequest()) {
					;
				} else{
					ReloadAndDisplay($oPage, $oObj, 'update', $sMessage, $sSeverity);
				}
			}

			$bLockEnabled = MetaModel::GetConfig()->Get('concurrent_lock_enabled');
			if ($bLockEnabled)
			{
				// Release the concurrent lock, if any
				$sOwnershipToken = utils::ReadPostedParam('ownership_token', null, 'raw_data');
				if ($sOwnershipToken !== null)
				{
					// We're done, let's release the lock
					iTopOwnershipLock::ReleaseLock(get_class($oObj), $oObj->GetKey(), $sOwnershipToken);
				}
			}
		}
		if ($this->IsHandlingXmlHttpRequest()) {
			$oPage->SetData($aResult);
		}
		return $oPage;
	}

	/**
	 * Add some JS files that are required during the modification of an object
	 *
	 * @param \iTopWebPage $oPage
	 *
	 * @return void
	 */
	protected function AddRequiredForModificationJsFilesToPage(iTopWebPage &$oPage): void
	{
		foreach (static::EnumRequiredForModificationJsFilesRelPaths() as $sJsFileRelPath) {
			$oPage->add_linked_script("../$sJsFileRelPath");
		}
	}

	/**
	 * @return string[] Rel. paths (to iTop root folder) of required JS files for object modification (create, edit, stimulus, ...)
	 */
	public static function EnumRequiredForModificationJsFilesRelPaths(): array
	{
		return [
			'js/json.js',
			'js/forms-json-utils.js',
			'js/wizardhelper.js',
			'js/wizard.utils.js',
			'js/extkeywidget.js',
			'js/jquery.blockUI.js',
		];
	}

	/**
	 * OperationSearch.
	 *
	 * Search objects via an oql and a friendly name search string
	 *
	 * @return JsonPage
	 */
	public function OperationSearch(): JsonPage
	{
		$oPage = new JsonPage();

		// Retrieve query params
		$sObjectClass = utils::ReadParam('object_class', '', false, utils::ENUM_SANITIZATION_FILTER_STRING);
		$sOql = utils::ReadParam('oql', '', false, utils::ENUM_SANITIZATION_FILTER_RAW_DATA);
		$aFieldsToLoad = json_decode(utils::ReadParam('fields_to_load', '', false, utils::ENUM_SANITIZATION_FILTER_STRING));
		$sSearch = utils::ReadParam('search', '', false, utils::ENUM_SANITIZATION_FILTER_STRING);

		// Retrieve this reference object (for OQL)
		$sThisObjectData = utils::ReadPostedParam('this_object_data', null, utils::ENUM_SANITIZATION_FILTER_RAW_DATA);
		$oThisObj = ObjectRepository::GetObjectFromWizardHelperData($sThisObjectData);

		// Retrieve data post processor
		$aDataProcessor = utils::ReadParam('data_post_processor', null, false, utils::ENUM_SANITIZATION_FILTER_RAW_DATA);

		// Search objects
		$aResult = ObjectRepository::SearchFromOql($sObjectClass, $aFieldsToLoad, $sOql, $sSearch, $oThisObj);

		// Data post processor
		// Note: Data post processor allow you to perform actions on search result (compute object result statistics, add others information...).
		if ($aResult !== null && $aDataProcessor !== null) {
			$aResult = call_user_func(array($aDataProcessor['class_name'], 'Execute'), $aResult, $aDataProcessor['settings']);
		}

		return $oPage->SetData([
			'search_data' => $aResult,
			'success'     => $aResult !== null,
		]);
	}

	/**
	 * OperationGet.
	 *
	 * @return JsonPage
	 */
	public function OperationGet(): JsonPage
	{
		$oPage = new JsonPage();
		$bSuccess = true;
		$aObjectData = null;

		// Retrieve query params
		$sObjectClass = utils::ReadParam('object_class', '', false, utils::ENUM_SANITIZATION_FILTER_STRING);
		$sObjectKey = utils::ReadParam('object_key', '', false, utils::ENUM_SANITIZATION_FILTER_STRING);

		// Retrieve object
		try {
			$oObject = MetaModel::GetObject($sObjectClass, $sObjectKey);
			$aObjectData = ObjectRepository::ConvertObjectToArray($oObject, $sObjectClass);
		}
		catch (Exception $e) {
			$bSuccess = false;
		}

		return $oPage->SetData([
			'object'  => $aObjectData,
			'success' => $bSuccess,
		]);
	}
}
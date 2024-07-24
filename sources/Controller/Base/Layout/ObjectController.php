<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Controller\Base\Layout;

use Combodo\iTop\Application\WebPage\AjaxPage;
use ApplicationContext;
use ApplicationException;
use cmdbAbstractObject;
use CMDBObjectSet;
use Combodo\iTop\Application\Helper\FormHelper;
use Combodo\iTop\Application\Helper\Session;
use Combodo\iTop\Application\UI\Base\Component\Alert\AlertUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\QuickCreate\QuickCreateHelper;
use Combodo\iTop\Application\UI\Base\Layout\Object\ObjectSummary;
use Combodo\iTop\Application\UI\Base\Layout\PageContent\PageContentFactory;
use Combodo\iTop\Controller\AbstractController;
use Combodo\iTop\Service\Base\ObjectRepository;
use Combodo\iTop\Service\Router\Router;
use CoreCannotSaveObjectException;
use DBObjectSearch;
use DBObjectSet;
use DBSearch;
use DBUnionSearch;
use DeleteException;
use Dict;
use Exception;
use IssueLog;
use iTopOwnershipLock;
use Combodo\iTop\Application\WebPage\iTopWebPage;
use Combodo\iTop\Application\WebPage\JsonPage;
use MetaModel;
use SecurityException;
use Combodo\iTop\Service\SummaryCard\SummaryCardService;
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
	 * @throws \CoreException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \MySQLException
	 * @throws \DictExceptionMissingString
	 * @throws \CoreUnexpectedValue
	 * @throws \ConfigException
	 * @throws \ApplicationException
	 * @throws \MissingQueryArgument
	 */
	public function OperationNew()
	{
		$bPrintable = utils::ReadParam('printable', '0') === '1';
		$sClass = utils::ReadParam('class', '', false, 'class');
		$sStateCode = utils::ReadParam('state', '');
		$bCheckSubClass = utils::ReadParam('checkSubclass', true);
		$oAppContext = new ApplicationContext();
		$oRouter = Router::GetInstance();
		
		if ($this->IsHandlingXmlHttpRequest()) {
			$oPage = new AjaxPage('');
		} else {
			$oPage = new iTopWebPage('', $bPrintable);
			$oPage->DisableBreadCrumb();
			$this->AddRequiredForModificationJsFilesToPage($oPage);
		}


		if (empty($sClass))
		{
			throw new ApplicationException(Dict::Format('UI:Error:1ParametersMissing', 'class'));
		}
		
		// If the specified class has subclasses, ask the user an instance of which class to create
		$aSubClasses = MetaModel::EnumChildClasses($sClass, ENUM_CHILD_CLASSES_ALL); // Including the specified class itself
		$aPossibleClasses = array();
		$sRealClass = '';
		if ($bCheckSubClass)
		{
			foreach($aSubClasses as $sCandidateClass)
			{
				if (!MetaModel::IsAbstract($sCandidateClass) && (UserRights::IsActionAllowed($sCandidateClass, UR_ACTION_MODIFY) == UR_ALLOWED_YES))
				{
					$aPossibleClasses[$sCandidateClass] = MetaModel::GetName($sCandidateClass);
				}
			}
			// Only one of the subclasses can be instantiated...
			if (count($aPossibleClasses) === 1)
			{
				$aKeys = array_keys($aPossibleClasses);
				$sRealClass = $aKeys[0];
			}
		}
		else
		{
			$sRealClass = $sClass;
		}

		if (!empty($sRealClass))
		{
			// Set all the default values in an object and clone this "default" object
			$oObjToClone = MetaModel::NewObject($sRealClass);
			// 1st - set context values
			$oAppContext->InitObjectFromContext($oObjToClone);
			// 2nd - set values from the page argument 'default'
			$oObjToClone->UpdateObjectFromArg('default');
			$aPrefillFormParam = array(
				'user' => Session::Get('auth_user'),
				'context' => $oAppContext->GetAsHash(),
				'default' => utils::ReadParam('default', array(), '', 'raw_data'),
				'origin' => 'console',
			);
			// 3rd - prefill API
			$oObjToClone->PrefillForm('creation_from_0', $aPrefillFormParam);

			// Display the creation form
			$sClassLabel = MetaModel::GetName($sRealClass);
			$sClassIcon = MetaModel::GetClassIcon($sRealClass);
			$sObjectTmpKey = $oObjToClone->GetKey();
			$sHeaderTitle = Dict::Format('UI:CreationTitle_Class', $sClassLabel);
			// Note: some code has been duplicated to the case 'apply_new' when a data integrity issue has been found

			$aFormExtraParams = array('wizard_container' => 1, 'keep_source_object' => true);
			
			// - Update flags with parameters set in URL
			FormHelper::UpdateFlagsFromContext($oObjToClone, $aFormExtraParams);
			
			if ($this->IsHandlingXmlHttpRequest()) {
				$aFormExtraParams['js_handlers'] = [];
				$aFormExtraParams['noRelations'] = true;
				$aFormExtraParams['hide_transitions'] = true;
				// Add a random prefix to avoid ID collision for form elements
				$aFormExtraParams['formPrefix'] = utils::Sanitize(uniqid('', true), '', utils::ENUM_SANITIZATION_FILTER_ELEMENT_IDENTIFIER).'_';
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
							CombodoModal.OpenInformativeModal(data.data.error_message, 'error');
						}
					});
				}
JS;

				// Remove blob edition from creation form @see N°5863 to allow blob edition in modal context
				FormHelper::DisableAttributeBlobInputs($sRealClass, $aFormExtraParams);

				if(FormHelper::HasMandatoryAttributeBlobInputs($oObjToClone)){
					$oPage->AddUiBlock(FormHelper::GetAlertForMandatoryAttributeBlobInputsInModal(FormHelper::ENUM_MANDATORY_BLOB_MODE_CREATE));
				}
				
				$aFormExtraParams['js_handlers']['cancel_button_on_click'] =
					<<<JS
				function() {
					$(this).closest('[data-role="ibo-modal"]').dialog('close');
				};
JS;
			} else {
				$oPage->set_title(Dict::Format('UI:CreationPageTitle_Class', $sClassLabel));
				$oPage->SetContentLayout(PageContentFactory::MakeForObjectDetails($oObjToClone, cmdbAbstractObject::ENUM_DISPLAY_MODE_CREATE));
			}

			cmdbAbstractObject::DisplayCreationForm($oPage, $sRealClass, $oObjToClone, array(), $aFormExtraParams);
		} else {
			if ($this->IsHandlingXmlHttpRequest()) {
				$oClassForm = cmdbAbstractObject::DisplayFormBlockSelectClassToCreate($sClass, MetaModel::GetName($sClass), $oAppContext, $aPossibleClasses, ['state' => $sStateCode]);
				$sCurrentUrl = $oRouter->GenerateUrl('object.new');
				$oClassForm->SetOnSubmitJsCode(
					<<<JS
					let me = this;
					let aParam = $(this).serialize();
					aParam['class'] = $(this).find('[name="class"]').val();
					let sPosting = $.post('$sCurrentUrl', aParam);
					sPosting.done(function(data){
                        $(me).closest('[data-role="ibo-modal"]').html(data).dialog({ position: { my: "center", at: "center", of: window }});
					});
                    return false;
JS
				);
				$oPage->AddUiBlock($oClassForm);
			}
			else{
				cmdbAbstractObject::DisplaySelectClassToCreate($sClass, $oPage, $oAppContext, $aPossibleClasses,['state' => $sStateCode]);
			}
		}
		return $oPage;
	}

	/**
	 * @return iTopWebPage|AjaxPage Object edit form in its webpage
	 * @throws \ApplicationException
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \SecurityException
	 * @throws \Exception
	 */
	public function OperationModify()
	{
		$bPrintable = utils::ReadParam('printable', '0') === '1';
		$sClass = utils::ReadParam('class', '', false, 'class');
		$sId = utils::ReadParam('id', '');
		$sFormTitle = utils::ReadPostedParam('form_title', null, utils::ENUM_SANITIZATION_FILTER_STRING);

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
		FormHelper::UpdateFlagsFromContext($oObj, $aFormExtraParams);

		// Allow form title customization
		if (!utils::IsNullOrEmptyString($sFormTitle)) {
			$aFormExtraParams['form_title'] = $sFormTitle;
		}

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

			// Remove blob edition from creation form @see N°5863 to allow blob edition in modal context
			FormHelper::DisableAttributeBlobInputs($sClass, $aFormExtraParams);

			if(FormHelper::HasMandatoryAttributeBlobInputs($oObj)){
				$sMandatoryBlobAttCode = FormHelper::GetMandatoryAttributeBlobInputs($oObj);
				$sAlertFormMandatoryAttMessageMode = FormHelper::ENUM_MANDATORY_BLOB_MODE_MODIFY_EMPTY;
				$oMandatoryBlobAttCodeValue = $oObj->Get($sMandatoryBlobAttCode);
				// If the current value of the mandatory attribute is not empty, display a different message
				if($oMandatoryBlobAttCodeValue instanceof \ormDocument && !$oMandatoryBlobAttCodeValue->IsEmpty()){
					$sAlertFormMandatoryAttMessageMode = FormHelper::ENUM_MANDATORY_BLOB_MODE_MODIFY_FILLED;
				}
				$oPage->AddUiBlock(FormHelper::GetAlertForMandatoryAttributeBlobInputsInModal($sAlertFormMandatoryAttMessageMode));
			}
		} else {
			$oPage = new iTopWebPage('', $bPrintable);
			$oPage->DisableBreadCrumb();
			$oPage->SetContentLayout(PageContentFactory::MakeForObjectDetails($oObj, cmdbAbstractObject::ENUM_DISPLAY_MODE_EDIT));
		}
		// - JS files
		foreach (static::EnumRequiredForModificationJsFilesRelPaths() as $sJsFileRelPath) {
			$oPage->LinkScriptFromAppRoot($sJsFileRelPath);
		}

		// Note: Code duplicated to the case 'apply_modify' in UI.php when a data integrity issue has been found
		$oObj->DisplayModifyForm($oPage, $aFormExtraParams); // wizard_container: Display the title above the form

		return $oPage;
	}
	
	/**
	 * @return iTopWebPage|JsonPage Object edit form in its webpage
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
						'$aErrors'        => $aErrors,
						'$sUser'          => UserRights::GetUser(),
						'HTTP_REFERER'    => @$_SERVER['HTTP_REFERER'],
						'REQUEST_URI'     => @$_SERVER['REQUEST_URI'],
					));

					throw new CoreCannotSaveObjectException(array('id' => $oObj->GetKey(), 'class' => $sClass, 'issues' => $aErrors));
				}

				// Transactions are now handled in DBInsert
				$oObj->SetContextSection('temporary_objects', [
					'finalize' => [
						'transaction_id' => $sTransactionId,
					],
				]);

				$oObj->CheckChangedExtKeysValues();
				$oObj->DBInsertNoReload();


				IssueLog::Trace(__CLASS__.'::'.__METHOD__.' Object created', $sClass, array(
					'$id'             => $oObj->GetKey(),
					'$sTransactionId' => $sTransactionId,
					'$aErrors'        => $aErrors,
					'$sUser'          => UserRights::GetUser(),
					'HTTP_REFERER'    => @$_SERVER['HTTP_REFERER'],
					'REQUEST_URI'     => @$_SERVER['REQUEST_URI'],
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
	 * @return iTopWebPage|JsonPage
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
					'$id'             => $id,
					'$sTransactionId' => $sTransactionId,
					'$aErrors'        => $aErrors,
					'IsModified'      => $oObj->IsModified(),
					'$sUser'          => UserRights::GetUser(),
					'HTTP_REFERER'    => @$_SERVER['HTTP_REFERER'],
					'REQUEST_URI'     => @$_SERVER['REQUEST_URI'],
				));

				try {
					if (!empty($aErrors)) {
						throw new CoreCannotSaveObjectException(array('id' => $oObj->GetKey(), 'class' => $sClass, 'issues' => $aErrors));
					}

					$oObj->CheckChangedExtKeysValues();

					// Transactions are now handled in DBUpdate
					$oObj->SetContextSection('temporary_objects', [
						'finalize' => [
							'transaction_id' => $sTransactionId,
						],
					]);
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
		if ($bDisplayDetails) {
			$oObj = MetaModel::GetObject(get_class($oObj), $oObj->GetKey(), false); //Workaround: reload the object so that the linkedset are displayed properly

			if ($oObj === null) {
				// N°6201 - Change a CI role to "computed" in pop-up freeze the screen
				// Here, the Object might have been removed by impact analysis, we consider this transaction as succeeded.
				$aResult['success'] = true;
			} else {
				$sNextAction = utils::ReadPostedParam('next_action', '');
				if (!empty($sNextAction)) {
					try {
						ApplyNextAction($oPage, $oObj, $sNextAction);
					}
					catch (ApplicationException $e) {
						$sMessage = $e->getMessage();
						$sSeverity = 'info';
						ReloadAndDisplay($oPage, $oObj, 'update', $sMessage, $sSeverity);
					}
				} else {
					// Nothing more to do
					$sMessage = isset($sMessage) ? $sMessage : '';
					$sSeverity = isset($sSeverity) ? $sSeverity : null;
					if ($this->IsHandlingXmlHttpRequest()) {
						;
					} else {
						ReloadAndDisplay($oPage, $oObj, 'update', $sMessage, $sSeverity);
					}
				}

				$bLockEnabled = MetaModel::GetConfig()->Get('concurrent_lock_enabled');
				if ($bLockEnabled) {
					// Release the concurrent lock, if any
					$sOwnershipToken = utils::ReadPostedParam('ownership_token', null, 'raw_data');
					if ($sOwnershipToken !== null) {
						// We're done, let's release the lock
						iTopOwnershipLock::ReleaseLock(get_class($oObj), $oObj->GetKey(), $sOwnershipToken);
					}
				}
			}
		}
		if ($this->IsHandlingXmlHttpRequest()) {
			$oPage->SetData($aResult);
		}
		return $oPage;
	}

	public function OperationSummary() {
		$oPage = new AjaxPage('');

		$sClass = utils::ReadParam('obj_class', '', false, utils::ENUM_SANITIZATION_FILTER_CLASS);
		$sObjectKey = utils::ReadParam('obj_key', 0, false);
		
		// - Check if we are allowed to see/make summary for this class
		if(SummaryCardService::IsAllowedForClass($sClass)){
			if (is_numeric($sObjectKey))
			{
				$oObj = MetaModel::GetObject($sClass, $sObjectKey, false /* MustBeFound */);
			}
			else
			{
				$oObj = MetaModel::GetObjectByName($sClass, $sObjectKey, false /* MustBeFound */);
			}
	
			if($oObj !== null) {
				$oPage->AddUiBlock(new ObjectSummary($oObj));
			}
		}
		else {
			$oPage->AddUiBlock(
				AlertUIBlockFactory::MakeForFailure(Dict::S('UI:Error:ActionNotAllowed'))
					->SetIsCollapsible(false)
					->SetIsClosable(false)
			);
		}
		return $oPage;
	}

	/**
	 * Add some JS files that are required during the modification of an object
	 *
	 * @param iTopWebPage $oPage
	 *
	 * @return void
	 */
	protected function AddRequiredForModificationJsFilesToPage(iTopWebPage &$oPage): void
	{
		foreach (static::EnumRequiredForModificationJsFilesRelPaths() as $sJsFileRelPath) {
			$oPage->LinkScriptFromAppRoot($sJsFileRelPath);
		}
	}

	/**
	 * @return string[] Rel. paths (to iTop root folder) of required JS files for object modification (create, edit, stimulus, ...)
	 */
	public static function EnumRequiredForModificationJsFilesRelPaths(): array
	{
		return [
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
	 * @used-by LinkedSet attribute when in tag display
	 */
	public function OperationSearch(): JsonPage
	{
		$oPage = new JsonPage();
		$oPage->SetOutputDataOnly(true);

		// Retrieve query params
		$sObjectClass = utils::ReadParam('object_class', '', false, utils::ENUM_SANITIZATION_FILTER_STRING);
		$sOql = utils::ReadParam('oql', '', false, utils::ENUM_SANITIZATION_FILTER_RAW_DATA);
		$aFieldsToLoad = json_decode(utils::ReadParam('fields_to_load', '[]', false, utils::ENUM_SANITIZATION_FILTER_STRING));
		$sSearch = utils::ReadParam('search', '', false, utils::ENUM_SANITIZATION_FILTER_STRING);

		// Retrieve this reference object (for OQL)
		$sThisObjectData = utils::ReadPostedParam('this_object_data', null, utils::ENUM_SANITIZATION_FILTER_RAW_DATA);
		$oThisObj = null;
		if($sThisObjectData !== null) {
			$oThisObj = ObjectRepository::GetObjectFromWizardHelperData($sThisObjectData);
		}

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

	public function OperationSearchForMentions(): JsonPage
	{
		$oPage = new JsonPage();
		$oPage->SetOutputDataOnly(true);

		$sMarker = utils::ReadParam('marker', '', false, utils::ENUM_SANITIZATION_FILTER_RAW_DATA);
		$sNeedle = utils::ReadParam('needle', '', false, utils::ENUM_SANITIZATION_FILTER_RAW_DATA);
		$sHostClass = utils::ReadParam('host_class', '', false, utils::ENUM_SANITIZATION_FILTER_CLASS);
		$iHostId = (int) utils::ReadParam('host_id', 0, false, utils::ENUM_SANITIZATION_FILTER_INTEGER);

		// Check parameters
		if (utils::IsNullOrEmptyString($sMarker)) {
			throw new ApplicationException('Invalid parameters, marker must be specified.');
		}
		if (utils::IsNullOrEmptyString($sNeedle)) {
			throw new ApplicationException('Invalid parameters, needle must be specified.');
		}

		$aMentionsAllowedClasses = MetaModel::GetConfig()->Get('mentions.allowed_classes');
		if (isset($aMentionsAllowedClasses[$sMarker]) === false) {
			throw new ApplicationException('Invalid marker "'.$sMarker.'"');
		}

		$aMatches = array();
		// Retrieve mentioned class from marker
		$sMentionedClass = $aMentionsAllowedClasses[$sMarker];
		if (MetaModel::IsValidClass($sMentionedClass) === false) {
			throw new ApplicationException('Invalid class "'.$sMentionedClass.'" for marker "'.$sMarker.'"');
		}

		// Base search used when no trigger configured
		$oSearch = DBSearch::FromOQL("SELECT $sMentionedClass");
		$aSearchParams = ['needle' => "%$sNeedle%"];

		// Retrieve restricting scopes from triggers if any
		$oHostObj = null;
		if (utils::IsNotNullOrEmptyString($sHostClass) && ($iHostId > 0)) {
			$oHostObj = MetaModel::GetObject($sHostClass, $iHostId);
			$aSearchParams['this'] = $oHostObj;

			$aTriggerMentionedSearches = [];

			$aTriggerSetParams = array('class_list' => MetaModel::EnumParentClasses($sHostClass, ENUM_PARENT_CLASSES_ALL));
			$oTriggerSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT TriggerOnObjectMention AS t WHERE t.target_class IN (:class_list)"), array(), $aTriggerSetParams);
			/** @var \TriggerOnObjectMention $oTrigger */
			while ($oTrigger = $oTriggerSet->Fetch()) {
				$sTriggerMentionedOQL = $oTrigger->Get('mentioned_filter');

				// No filter on mentioned objects, don't restrict the scope at all, it can be any object of $sMentionedClass
				if (utils::IsNullOrEmptyString($sTriggerMentionedOQL)) {
					$aTriggerMentionedSearches = [$oSearch];
					break;
				}

				$oTriggerMentionedSearch = DBSearch::FromOQL($sTriggerMentionedOQL);
				$sTriggerMentionedClass = $oTriggerMentionedSearch->GetClass();

				// Filter is not about the mentioned class, don't mind it
				if (is_a($sMentionedClass, $sTriggerMentionedClass, true) === false) {
					continue;
				}

				$aTriggerMentionedSearches[] = $oTriggerMentionedSearch;
			}

			if (count($aTriggerMentionedSearches) > 0) {
				$oSearch = new DBUnionSearch($aTriggerMentionedSearches);
			}
		}

		$sSearchMainClassName = $oSearch->GetClass();
		$sSearchMainClassAlias = $oSearch->GetClassAlias();

		$sObjectImageAttCode = MetaModel::GetImageAttributeCode($sSearchMainClassName);


		// Optimize fields to load
		$aObjectAttCodesToLoad = [];
		if (MetaModel::IsValidAttCode($sSearchMainClassName, $sObjectImageAttCode)) {
			$aObjectAttCodesToLoad[] = $sObjectImageAttCode;
		}

		$aResult = ObjectRepository::SearchFromOql($sSearchMainClassName, $aObjectAttCodesToLoad, $oSearch->ToOQL(), $sNeedle, $oHostObj, MetaModel::GetConfig()->Get('max_autocomplete_results'));

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
		$sObjectKey = utils::ReadParam('object_key', 0, false, utils::ENUM_SANITIZATION_FILTER_INTEGER);

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
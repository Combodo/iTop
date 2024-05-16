<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Controller\Links;

use Combodo\iTop\Application\WebPage\AjaxPage;
use cmdbAbstractObject;
use Combodo\iTop\Application\Helper\FormHelper;
use Combodo\iTop\Application\UI\Base\Component\Form\FormUIBlockFactory;
use Combodo\iTop\Controller\AbstractController;
use Combodo\iTop\Service\Links\LinkSetModel;
use Combodo\iTop\Service\Router\Router;
use Combodo\iTop\Service\Base\ObjectRepository;
use Dict;
use Exception;
use Combodo\iTop\Application\WebPage\JsonPage;
use CoreException;
use DBObject;
use MetaModel;
use UserRights;
use utils;

/**
 * Class LinkSetController
 *
 * @internal
 * @since 3.1.0
 * @package Combodo\iTop\Controller
 */
class LinkSetController extends AbstractController
{
	public const ROUTE_NAMESPACE = 'linkset';

	/**
	 * OperationDeleteLinkedObject.
	 *
	 * @return JsonPage
	 * @throws \CoreException
	 */
	public function OperationDeleteLinkedObject(): JsonPage
	{
		if (!$this->IsHandlingXmlHttpRequest()) {
			throw new CoreException('LinksetController can only be called in ajax.');
		}
		
		$oPage = new JsonPage();
		$sErrorMessage = null;
		$bOperationSuccess = false;

		// retrieve parameters
		$sLinkedObjectClass = utils::ReadParam('linked_object_class', '', false, utils::ENUM_SANITIZATION_FILTER_CLASS);
		$sLinkedObjectObjectKey = utils::ReadParam('linked_object_key', 0, false, utils::ENUM_SANITIZATION_FILTER_STRING);
		$sTransactionId = utils::ReadParam('transaction_id', null, false, utils::ENUM_SANITIZATION_FILTER_TRANSACTION_ID);

		// check transaction id
		if (utils::IsTransactionValid($sTransactionId, false)) {
			try {
				$oDeletionPlan = MetaModel::GetObject($sLinkedObjectClass, $sLinkedObjectObjectKey)->DBDelete();
				$bOperationSuccess = (count($oDeletionPlan->GetIssues()) === 0);
				if (!$bOperationSuccess) {
					$sErrorMessage = json_encode($oDeletionPlan->GetIssues());
				}
			}
			catch (Exception $e) {
				$sErrorMessage = $e->getMessage();
			}
		} else {
			$sErrorMessage = 'invalid transaction id';
		}
		$oPage->SetData([
			'success'       => $bOperationSuccess,
			'error_message' => $sErrorMessage,
		]);

		return $oPage;
	}

	/**
	 * OperationDetachLinkedObject.
	 *
	 * @return JsonPage
	 * @throws \CoreException
	 */
	public function OperationDetachLinkedObject(): JsonPage
	{
		if (!$this->IsHandlingXmlHttpRequest()) {
			throw new CoreException('LinksetController can only be called in ajax.');
		}
		
		$oPage = new JsonPage();
		$sErrorMessage = null;
		$bOperationSuccess = false;

		// retrieve parameters
		$sLinkedObjectClass = utils::ReadParam('linked_object_class', '', false, utils::ENUM_SANITIZATION_FILTER_CLASS);
		$sLinkedObjectKey = utils::ReadParam('linked_object_key', 0, false, utils::ENUM_SANITIZATION_FILTER_STRING);
		$sExternalKeyAttCode = utils::ReadParam('external_key_att_code', null, false, utils::ENUM_SANITIZATION_FILTER_STRING);
		$sTransactionId = utils::ReadParam('transaction_id', null, false, utils::ENUM_SANITIZATION_FILTER_TRANSACTION_ID);

		// check transaction id
		if (utils::IsTransactionValid($sTransactionId, false)) {
			try {
				$oLinkedObject = MetaModel::GetObject($sLinkedObjectClass, $sLinkedObjectKey);
				$oLinkedObject->Set($sExternalKeyAttCode, null);
				$oLinkedObject->DBWrite();
				$bOperationSuccess = true;
			}
			catch (Exception $e) {
				$sErrorMessage = $e->getMessage();
			}
		} else {
			$sErrorMessage = 'invalid transaction id';
		}
		$oPage->SetData([
			'success'       => $bOperationSuccess,
			'error_message' => $sErrorMessage,
		]);

		return $oPage;
	}

	/**
	 * @return AjaxPage Create edit form in its webpage
	 * @throws \ApplicationException
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \SecurityException
	 */
	public function OperationCreateLinkedObject(): AjaxPage
	{
		if (!$this->IsHandlingXmlHttpRequest()) {
			throw new CoreException('LinksetController can only be called in ajax.');
		}

		$oRouter = Router::GetInstance();
		$oPage = new AjaxPage('');

		$sProposedRealClass = utils::ReadParam('class', '', false, 'class');
		$sAttCode = utils::ReadParam('att_code', '', false, 'raw');
		$sClass = utils::ReadParam('host_class', '', false, 'class');
		$sId = utils::ReadParam('host_id', '', false, 'integer');
		$sFormTitle = utils::ReadPostedParam('form_title', null, utils::ENUM_SANITIZATION_FILTER_STRING);

		// For security reasons: check that the "proposed" class is actually a subclass of the linked class
		// and that the current user is allowed to create objects of this class
		$sRealClass = '';

		$aSubClasses = MetaModel::EnumChildClasses($sProposedRealClass, ENUM_CHILD_CLASSES_ALL); // Including the specified class itself
		$aPossibleClasses = array();
		foreach ($aSubClasses as $sCandidateClass) {
			if (!MetaModel::IsAbstract($sCandidateClass) && (UserRights::IsActionAllowed($sCandidateClass, UR_ACTION_MODIFY) == UR_ALLOWED_YES)) {
				if ($sCandidateClass == $sProposedRealClass) {
					$sRealClass = $sProposedRealClass;
				}
				$aPossibleClasses[$sCandidateClass] = MetaModel::GetName($sCandidateClass);
			}
		}

		// Only one of the subclasses can be instantiated...
		if (count($aPossibleClasses) == 1) {
			$aKeys = array_keys($aPossibleClasses);
			$sRealClass = $aKeys[0];
		}
		if ($sRealClass != '') {
			$oLinksetDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
			$sExtKeyToMe = $oLinksetDef->GetExtKeyToMe();
			$aFieldFlags = array($sExtKeyToMe => OPT_ATT_READONLY);
			$oObj = DBObject::MakeDefaultInstance($sRealClass);

			$oSourceObj = MetaModel::GetObject($sClass, $sId);

			$oObj->Set($sExtKeyToMe, $sId);
			$aPrefillParam = array('source_obj' => $oSourceObj);
			$oObj->PrefillForm('creation_from_editinplace', $aPrefillParam);
			// We display this form in a modal, once we submit (in ajax) we probably want to only close the modal
			$sFormOnSubmitJsCode =
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

			
			$aExtraParams = [
				'noRelations'           => true,
				'hide_transitions'      => true,
				'formPrefix'            => $sAttCode,
				'fieldsFlags'           => $aFieldFlags,
				'forceFieldsSubmission' => [
					$sExtKeyToMe,
				],
				'form_title'            => $sFormTitle,
				'custom_button'         => \Dict::S('UI:Button:Add'),
				'js_handlers'           => [
					'form_on_submit'         => $sFormOnSubmitJsCode,
					'cancel_button_on_click' =>
						<<<JS
				function() {
					$(this).closest('[data-role="ibo-modal"]').dialog('close');
				};
JS
					,
				],
			];

			// Remove blob edition from creation form @see N°5863 to allow blob edition in modal context
			FormHelper::DisableAttributeBlobInputs($sRealClass, $aExtraParams);
			
			if(FormHelper::HasMandatoryAttributeBlobInputs($oObj)){
				$oPage->AddUiBlock(FormHelper::GetAlertForMandatoryAttributeBlobInputsInModal(FormHelper::ENUM_MANDATORY_BLOB_MODE_CREATE));
			}	

			cmdbAbstractObject::DisplayCreationForm($oPage, $sRealClass, $oObj, array(), $aExtraParams);
		}
		else
		{
			// - We'll let the user select a class if multiple classes are available
			$oClassForm = FormUIBlockFactory::MakeStandard();
			
			// - When the user submit, redo the same request but with a real class
			
			$sCurrentParameters = json_encode([
				'att_code' => $sAttCode,
				'host_class' => $sClass,
				'host_id' => $sId]);
			$sCurrentUrl = $oRouter->GenerateUrl('linkset.create_linked_object');
			$oClassForm->SetOnSubmitJsCode(
				<<<JS
					let me = this;
					let aParam = $sCurrentParameters;
					aParam['class'] = $(this).find('[name="class"]').val();
					let sPosting = $.post('$sCurrentUrl', aParam);
					sPosting.done(function(data){
                        $(me).closest('[data-role="ibo-modal"]').html(data);
					});
                    return false;
JS
			);

			// - Add a select and a button to validate the form
			$oClassForm->AddSubBlock(cmdbAbstractObject::DisplayBlockSelectClassToCreate($sProposedRealClass, MetaModel::GetName($sProposedRealClass), $aPossibleClasses));

			$oPage->AddUiBlock($oClassForm);
		}

		return $oPage;
	}

	/**
	 * OperationGetRemoteObject.
	 *
	 * @return JsonPage
	 */
	public function OperationGetRemoteObject(): JsonPage
	{
		$oPage = new JsonPage();
		$bSuccess = true;
		$aObjectData = null;

		// Retrieve query params
		$sObjectClass = utils::ReadParam('linked_object_class', '', false, utils::ENUM_SANITIZATION_FILTER_STRING);
		$sObjectKey = utils::ReadParam('linked_object_key', '', false, utils::ENUM_SANITIZATION_FILTER_STRING);
		$sRemoteClass = utils::ReadParam('remote_class', null, false, utils::ENUM_SANITIZATION_FILTER_STRING);
		$sRemoteExtKey = utils::ReadParam('external_key_att_code', null, false, utils::ENUM_SANITIZATION_FILTER_STRING);

		// Retrieve object
		try {
			$oObject = MetaModel::GetObject($sObjectClass, $sObjectKey);
			$sLinkKey = $oObject->GetKey();
			if (!utils::IsNullOrEmptyString($sRemoteExtKey)) {
				$oObject = MetaModel::GetObject($sRemoteClass, $oObject->Get($sRemoteExtKey));
			}
			$aObjectData = ObjectRepository::ConvertObjectToArray($oObject, $sObjectClass);
			$aObjectData['link_keys'] = [$sObjectKey];
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
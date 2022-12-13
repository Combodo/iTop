<?php
/*
 * @copyright   Copyright (C) 2010-2022 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Controller\Links;

use AjaxPage;
use cmdbAbstractObject;
use Combodo\iTop\Application\UI\Base\Layout\PageContent\PageContentFactory;
use Combodo\iTop\Controller\AbstractController;
use DBObject;
use iTopWebPage;
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
	 * @return \JsonPage
	 */
	public function OperationDeleteLinkedObject(): \JsonPage
	{
		$oPage = new \JsonPage();
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
			catch (\Exception $e) {
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
	 * @return \JsonPage
	 */
	public function OperationDetachLinkedObject(): \JsonPage
	{
		$oPage = new \JsonPage();
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
			catch (\Exception $e) {
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
	 * @return \iTopWebPage|\AjaxPage Create edit form in its webpage
	 * @throws \ApplicationException
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \SecurityException
	 */
	public function OperationCreateLinkedObject()
	{
		$bPrintable = utils::ReadParam('printable', '0') === '1';
		$sProposedRealClass = utils::ReadParam('class', '', false, 'class');
		$sAttCode = utils::ReadParam('att_code', '', false, 'raw');
		$sClass = utils::ReadParam('host_class', '', false, 'class');
		$sId = utils::ReadParam('host_id', '', false, 'integer');

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
			$aFieldFlags = array(); // TODO 3.1 array($sExtKeyToMe => OPT_ATT_READONLY);
			$oObj = DBObject::MakeDefaultInstance($sRealClass);

			if ($this->IsHandlingXmlHttpRequest()) {
				$oPage = new AjaxPage('');
			} else {
				$oPage = new iTopWebPage('', $bPrintable);
				$oPage->DisableBreadCrumb();
				$oPage->SetContentLayout(PageContentFactory::MakeForObjectDetails($oObj, cmdbAbstractObject::ENUM_DISPLAY_MODE_CREATE));
			}

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
						oForm.closest('[data-role="ibo-modal"]').dialog('close');
					});
				}
JS
			;
			cmdbAbstractObject::DisplayCreationForm($oPage, $sRealClass, $oObj, array(), array('noRelations' => true, 'fieldsFlags' => $aFieldFlags, 'form_on_submit_js_code' => $sFormOnSubmitJsCode));
			return $oPage;
		}
		return;
	}
}
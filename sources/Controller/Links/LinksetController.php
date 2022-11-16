<?php
/*
 * @copyright   Copyright (C) 2010-2022 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Controller\Links;

use Combodo\iTop\Controller\AbstractController;
use MetaModel;
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
		$sLinkedObjectObjectKey = utils::ReadParam('linked_object_key', 0, false, utils::ENUM_SANITIZATION_FILTER_STRING);
		$sExternalKeyAttCode = utils::ReadParam('external_key_att_code', null, false, utils::ENUM_SANITIZATION_FILTER_STRING);
		$sTransactionId = utils::ReadParam('transaction_id', null, false, utils::ENUM_SANITIZATION_FILTER_TRANSACTION_ID);

		// check transaction id
		if (utils::IsTransactionValid($sTransactionId, false)) {
			try {
				$oLinkedObject = MetaModel::GetObject($sLinkedObjectClass, $sLinkedObjectObjectKey);
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


}
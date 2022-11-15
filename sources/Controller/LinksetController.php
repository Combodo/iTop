<?php
/*
 * @copyright   Copyright (C) 2010-2022 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Controller;

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
	 * @return \JsonPage
	 */
	public function OperationRemoveRemoteObject()
	{
		$oPage = new \JsonPage();
		$sErrorMessage = null;
		$bOperationSuccess = false;

		// retrieve parameters
		$sClass = utils::ReadParam('obj_class', '', false, utils::ENUM_SANITIZATION_FILTER_CLASS);
		$sObjectId = utils::ReadParam('obj_key', 0, false, utils::ENUM_SANITIZATION_FILTER_STRING);
		$sAttCode = utils::ReadParam('att_code', null, false, utils::ENUM_SANITIZATION_FILTER_STRING);
		$sTransactionId = utils::ReadParam('transaction_id', null, false, utils::ENUM_SANITIZATION_FILTER_TRANSACTION_ID);

		// check transaction id
		if (utils::IsTransactionValid($sTransactionId, false)) {
			if (!empty($sAttCode)) { // unlink
				$oLinkedObject = MetaModel::GetObject($sClass, $sObjectId);
				$oLinkedObject->Set($sAttCode, null);
				$oLinkedObject->DBWrite();
				$bOperationSuccess = true;
			} else { // delete
				$oDeletionPlan = MetaModel::GetObject($sClass, $sObjectId)->DBDelete();
				$bOperationSuccess = (count($oDeletionPlan->GetIssues()) === 0);
				if (!$bOperationSuccess) {
					$sErrorMessage = json_encode($oDeletionPlan->GetIssues());
				}
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
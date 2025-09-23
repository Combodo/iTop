<?php
namespace Combodo\iTop\Controller;

use Combodo\iTop\Application\WebPage\JsonPage;
use Combodo\iTop\Application\WelcomePopup\WelcomePopupService;
use Exception;
use utils;

/**
 * Simple controller to acknowledge (via Ajax) welcome popup messages
 * @since 3.2.0
 */
class WelcomePopupController extends AbstractController
{
	/** @inheritDoc */
	const ROUTE_NAMESPACE = "welcome_popup";

	/**
	 * Acknowledge a specific message for the current user
	 * @return \Combodo\iTop\Application\WebPage\JsonPage
	 */
	public function OperationAcknowledgeMessage(): JsonPage
	{
		$oPage = new JsonPage();
		$oPage->SetOutputDataOnly(true);

		try {
			$oService = WelcomePopupService::GetInstance();
			$sMessageUUID = utils::ReadPostedParam('message_uuid', '', false, utils::ENUM_SANITIZATION_FILTER_RAW_DATA);
			$oService->AcknowledgeMessage($sMessageUUID);

			$aResult = ['success' => true];
		}
		catch (Exception $oException) {
			$aResult = [
				'success'       => false,
				'error_message' => $oException->getMessage(),
			];
		}

		$oPage->SetData($aResult);
		return $oPage;
	}
}


<?php
namespace Combodo\iTop\Controller;

use Combodo\iTop\Application\WelcomePopup\WelcomePopupService;
use utils;

/**
 * Simple controller to acknowledge (via Ajax) welcome popup messages
 * @since 3.1.0
 *
 */
class WelcomePopupController extends AbstractController
{
	/**
	 * Operation: welcome_popup.acknowledge_message
	 */
	public function AcknowledgeMessage(): void
	{
		$oService = new WelcomePopupService();
		$sMessageUUID = utils::ReadPostedParam('message_uuid', '', false, utils::ENUM_SANITIZATION_FILTER_RAW_DATA);
		$oService->AcknowledgeMessage($sMessageUUID);
	}
}


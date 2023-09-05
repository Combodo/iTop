<?php
namespace Combodo\iTop\Application\WelcomePopup;

use Dict;
use AbstractWelcomePopup;

/**
 * Implementation of the "default" Welcome Popup message
 * @since 3.1.0
 */
class DefaultWelcomePopup extends AbstractWelcomePopup
{
	public function GetMessages()
	{
		return [
			[
				// Replacement of the welcome popup message which
				// was hard-coded in the pages/UI.php
				'id' => '0001',
				'title' => Dict::S('UI:WelcomeMenu:Title'),
				'twig' => '/templates/pages/backoffice/welcome_popup/default_welcome_popup',
				'importance' => \iWelcomePopup::IMPORTANCE_HIGH,
				'parameters' => [],
			],
		];
	}
}

<?php
namespace Combodo\iTop\Application\WelcomePopup;

use Dict;
use AbstractWelcomePopupExtension;

/**
 * Implementation of the "default" Welcome Popup message
 * @since 3.1.0
 */
class DefaultWelcomePopup extends AbstractWelcomePopupExtension
{
	/**
	 * @inheritDoc
	 */
	public function GetMessages(): array
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

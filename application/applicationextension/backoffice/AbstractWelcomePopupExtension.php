<?php

/**
 * Inherit from this class to provide messages to be displayed in the "Welcome Popup"
 *
 * @api
 * @since 3.2.0
 */
abstract class AbstractWelcomePopupExtension implements iWelcomePopupExtension
{
	/**
	 * @inheritDoc
	 */
	public function GetIconRelPath(): string
	{
		return \Combodo\iTop\Application\Branding::$aLogoPaths[\Combodo\iTop\Application\Branding::ENUM_LOGO_TYPE_MAIN_LOGO_COMPACT]['default'];
	}

	/**
	 * @inheritDoc
	 */
	public function GetMessages(): array
	{
		return [];
	}

	/**
	 * @inheritDoc
	 */
	public function AcknowledgeMessage(string $sMessageId): void
	{
		// No need to process the acknowledgment notice by default
		return;
	}
}

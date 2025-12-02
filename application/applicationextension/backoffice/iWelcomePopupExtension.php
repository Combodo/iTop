<?php

/**
 * Interface to provide messages to be displayed in the "Welcome Popup"
 *
 * @api
 * @since 3.2.0
 */
interface iWelcomePopupExtension
{
	// Importance for ordering messages
	// Just two levels since less important messages have nothing to do in the welcome popup
	public const ENUM_IMPORTANCE_CRITICAL = 0;
	public const ENUM_IMPORTANCE_HIGH = 1;
	public const DEFAULT_IMPORTANCE = self::ENUM_IMPORTANCE_HIGH;

	/**
	 * Overload this method if you need to display an icon representing the provider (eg. your own company logo, module icon, ...)
	 *
	 * @return string Relative path (from app. root) of the icon representing the provider
	 * @api
	 */
	public function GetIconRelPath(): string;

	/**
	 * @return \Combodo\iTop\Application\WelcomePopup\Message[]
	 * @api
	 */
	public function GetMessages(): array;

	/**
	 * Overload this method if the provider needs to do some additional processing after the message ($sMessageId) has been acknowledged by the current user
	 *
	 * @param string $sMessageId
	 * @api
	 */
	public function AcknowledgeMessage(string $sMessageId): void;
}

<?php

/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\WelcomePopup;


use iWelcomePopupExtension;

/**
 * Class MessageFactory
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Application\WelcomePopup
 * @api
 */
class MessageFactory {
	/**
	 * @param string $sId Unique ID of the message within its provider
	 * @param string $sTitle Title of the message in plain text
	 * @param string $sDescription Description of the message, can contain HTML
	 * @param string|null $sIllustrationAbsURI Optional illustration to display with the description, should be an absolute URI (illustration can be on another server)
	 * @param int $iImportance Importance of the message {@see \iWelcomePopupExtension::ENUM_IMPORTANCE_HIGH} and {@see \iWelcomePopupExtension::ENUM_IMPORTANCE_CRITICAL}
	 *
	 * @api
	 * @return \Combodo\iTop\Application\WelcomePopup\Message Message with title / description on the left side, and an optional illustration on the right
	 */
	public static function MakeForLeftTextsRightIllustration(string $sId, string $sTitle, string $sDescription, null|string $sIllustrationAbsURI = null, int $iImportance = iWelcomePopupExtension::DEFAULT_IMPORTANCE): Message
	{
		return new Message(
			$sId,
			$sTitle,
			$sDescription,
			$sIllustrationAbsURI,
			[],
			$iImportance,
			"templates/application/welcome-popup/templates/left-title-description-right-illustration.html.twig"
		);
	}

	/**
	 * @param string $sId Unique ID of the message within its provider
	 * @param string $sTitle Title of the message in plain text
	 * @param string $sDescription Description of the message, can contain HTML
	 * @param string|null $sIllustrationAbsURI Optional illustration to display with the description, should be an absolute URI (illustration can be on another server)
	 * @param int $iImportance Importance of the message {@see \iWelcomePopupExtension::ENUM_IMPORTANCE_HIGH} and {@see \iWelcomePopupExtension::ENUM_IMPORTANCE_CRITICAL}
	 *
	 * @api
	 * @return \Combodo\iTop\Application\WelcomePopup\Message Message with title / description on the right side, and an optional illustration on the left
	 */
	public static function MakeForLeftIllustrationRightTexts(string $sId, string $sTitle, string $sDescription, null|string $sIllustrationAbsURI = null, int $iImportance = iWelcomePopupExtension::DEFAULT_IMPORTANCE): Message
	{
		return new Message(
			$sId,
			$sTitle,
			$sDescription,
			$sIllustrationAbsURI,
			[],
			$iImportance,
			"templates/application/welcome-popup/templates/left-illustration-right-title-description.html.twig"
		);
	}

	public static function MakeForLeftIllustrationAsSVGMarkupRightTexts(string $sId, string $sTitle, string $sDescription, null|string $sIllustrationAbsURI = null, int $iImportance = iWelcomePopupExtension::DEFAULT_IMPORTANCE): Message
	{
		return new Message(
			$sId,
			$sTitle,
			$sDescription,
			$sIllustrationAbsURI,
			[],
			$iImportance,
			"templates/application/welcome-popup/templates/left-illustration-as-svg-markup-right-title-description.html.twig"
		);
	}

	public static function MakeForLeftTextsRightIllustrationAsSVGMarkup(string $sId, string $sTitle, string $sDescription, null|string $sIllustrationAbsURI = null, int $iImportance = iWelcomePopupExtension::DEFAULT_IMPORTANCE): Message
	{
		return new Message(
			$sId,
			$sTitle,
			$sDescription,
			$sIllustrationAbsURI,
			[],
			$iImportance,
			"templates/application/welcome-popup/templates/left-title-description-right-illustration-as-svg-markup.html.twig"
		);
	}

	/**
	 * @param string $sId Unique ID of the message within its provider
	 * @param string $sTitle Title of the message in plain text
	 * @param string $sDescription Description of the message, can contain HTML
	 * @param string $sTWIGTemplateRelPath Rel. path (from app. root) to the TWIG template to use for the rendering of the message content
	 * @param string|null $sIllustrationAbsURI Optional illustration to display with the description, should be an absolute URI (illustration can be on another server)
	 * @param array $aAdditionalParameters Additional parameters to pass to the TWIG
	 * @param int $iImportance Importance of the message {@see \iWelcomePopupExtension::ENUM_IMPORTANCE_HIGH} and {@see \iWelcomePopupExtension::ENUM_IMPORTANCE_CRITICAL}
	 *
	 * @api
	 * @return \Combodo\iTop\Application\WelcomePopup\Message Message with title / description on the right side, and an optional illustration on the left
	 */
	public static function MakeForCustomTemplate(string $sId, string $sTitle, string $sDescription, string $sTWIGTemplateRelPath, null|string $sIllustrationAbsURI = null, array $aAdditionalParameters = [], int $iImportance = iWelcomePopupExtension::DEFAULT_IMPORTANCE): Message
	{
		return new Message(
			$sId,
			$sTitle,
			$sDescription,
			$sIllustrationAbsURI,
			$aAdditionalParameters,
			$iImportance,
			$sTWIGTemplateRelPath
		);
	}
}
<?php

/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\WelcomePopup;


use iWelcomePopupExtension;
use utils;

/**
 * Class Message
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Application\WelcomePopup
 */
class Message {
	/** @var string Default TWIG template */
	protected const DEFAULT_TWIG_TEMPLATE_REL_PATH = 'templates/application/welcome-popup/templates/left-title-description-right-illustration.html.twig';

	/** @var string Unique ID of the message within its provider */
	protected readonly string $sId;
	/** @var int Importance of the message {@see \iWelcomePopupExtension::ENUM_IMPORTANCE_HIGH} and {@see \iWelcomePopupExtension::ENUM_IMPORTANCE_CRITICAL} */
	protected int $iImportance;
	/** @var string Title of the message in plain text */
	protected string $sTitle;
	/** @var string Description of the message, can contain HTML */
	protected string $sDescription;
	/** @var string|null Optional illustration to display with the description, should be an absolute URI (illustration can be on another server) */
	protected null|string $sIllustrationAbsURI;
	/** @var array Additional parameters to pass to {@see \Combodo\iTop\Application\WelcomePopup\Message::$sTWIGTemplateRelPath} */
	protected array $aAdditionalParameters;
	/** @var string|null TWIG template to use for the rendering of the message content (title, description, illustration) */
	protected readonly null|string $sTWIGTemplateRelPath;

	/**
	 * @param string $sId {@see \Combodo\iTop\Application\WelcomePopup\Message::$sId}
	 * @param string $sTitle {@see \Combodo\iTop\Application\WelcomePopup\Message::$sTitle}
	 * @param string $sDescription {@see \Combodo\iTop\Application\WelcomePopup\Message::$sDescription}
	 * @param string|null $sIllustrationAbsURI {@see \Combodo\iTop\Application\WelcomePopup\Message::$sIllustrationAbsURI}
	 * @param array $aAdditionalParameters {@see \Combodo\iTop\Application\WelcomePopup\Message::$aAdditionalParameters}
	 * @param int $iImportance {@see \Combodo\iTop\Application\WelcomePopup\Message::$iImportance}
	 * @param string|null $sTWIGTemplateRelPath {@see \Combodo\iTop\Application\WelcomePopup\Message::$sTWIGTemplateRelPath}
	 */
	public function __construct(string $sId, string $sTitle, string $sDescription, null|string $sIllustrationAbsURI = null, array $aAdditionalParameters = [], int $iImportance = iWelcomePopupExtension::DEFAULT_IMPORTANCE, null|string $sTWIGTemplateRelPath = null)
	{
		$this->sId = $sId;
		$this->sTitle = $sTitle;
		$this->sDescription = $sDescription;
		$this->sIllustrationAbsURI = $sIllustrationAbsURI;
		$this->aAdditionalParameters = $aAdditionalParameters;
		$this->iImportance = $iImportance;
		$this->sTWIGTemplateRelPath = $sTWIGTemplateRelPath ?? static::DEFAULT_TWIG_TEMPLATE_REL_PATH;
	}

	/**
	 * @see \Combodo\iTop\Application\WelcomePopup\Message::$sId
	 * @return string
	 */
	public function GetID(): string
	{
		return $this->sId;
	}

	/**
	 * @see \Combodo\iTop\Application\WelcomePopup\Message::$sDescription
	 * @return string
	 */
	public function GetTitle(): string
	{
		return $this->sTitle;
	}

	/**
	 * @see \Combodo\iTop\Application\WelcomePopup\Message::$sTitle
	 * @param string $sTitle
	 *
	 * @return static
	 */
	public function SetTitle(string $sTitle): static
	{
		$this->sTitle = $sTitle;
		return $this;
	}

	/**
	 * @see \Combodo\iTop\Application\WelcomePopup\Message::$sDescription
	 * @return string
	 */
	public function GetDescription(): string
	{
		return $this->sDescription;
	}

	/**
	 * @see \Combodo\iTop\Application\WelcomePopup\Message::$sDescription
	 * @param string $sDescription
	 *
	 * @return static
	 */
	public function SetDescription(string $sDescription): static
	{
		$this->sDescription = $sDescription;
		return $this;
	}

	/**
	 * @see \Combodo\iTop\Application\WelcomePopup\Message::$sIllustrationAbsURI
	 * @return string|null
	 */
	public function GetIllustrationAbsURI(): ?string
	{
		return $this->sIllustrationAbsURI;
	}

	/**
	 * @see \Combodo\iTop\Application\WelcomePopup\Message::$sIllustrationAbsURI
	 *
	 * @param string|null $sIllustrationAbsURI
	 *
	 * @return static
	 */
	public function SetIllustrationAbsURI(?string $sIllustrationAbsURI): static
	{
		$this->sIllustrationAbsURI = $sIllustrationAbsURI;
		return $this;
	}

	/**
	 * @see \Combodo\iTop\Application\WelcomePopup\Message::HasIllustration()
	 * @return bool
	 */
	public function HasIllustration(): bool
	{
		return utils::IsNotNullOrEmptyString($this->sIllustrationAbsURI);
	}

	/**
	 * @see \Combodo\iTop\Application\WelcomePopup\Message::$aAdditionalParameters
	 * @return array
	 */
	public function GetAdditionalParameters(): array
	{
		return $this->aAdditionalParameters;
	}

	/**
	 * @see \Combodo\iTop\Application\WelcomePopup\Message::$aAdditionalParameters
	 *
	 * @param array $aAdditionalParameters
	 *
	 * @return static
	 */
	public function SetAdditionalParameters(array $aAdditionalParameters): static
	{
		$this->aAdditionalParameters = $aAdditionalParameters;
		return $this;
	}

	/**
	 * @see \Combodo\iTop\Application\WelcomePopup\Message::$iImportance
	 * @return int
	 */
	public function GetImportance(): int
	{
		return $this->iImportance;
	}

	/**
	 * @see \Combodo\iTop\Application\WelcomePopup\Message::$iImportance
	 *
	 * @param int $iImportance
	 *
	 * @return static
	 */
	public function SetImportance(int $iImportance): static
	{
		$this->iImportance = $iImportance;
		return $this;
	}

	/**
	 * @see \Combodo\iTop\Application\WelcomePopup\Message::$sTWIGTemplateRelPath
	 * @return string|null
	 */
	public function GetTWIGTemplateRelPath(): ?string
	{
		return $this->sTWIGTemplateRelPath;
	}
}
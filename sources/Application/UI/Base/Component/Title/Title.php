<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Base\Component\Title;


use Combodo\iTop\Application\UI\Base\Layout\UIContentBlock;
use Combodo\iTop\Application\UI\Base\UIBlock;

/**
 * Class Title
 *
 * @package Combodo\iTop\Application\UI\Base\Component\Title
 */
class Title extends UIContentBlock
{
	// Overloaded constants
	/** @inheritDoc */
	public const BLOCK_CODE = 'ibo-title';
	/** @inheritDoc */
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/components/title/layout';

	/** @var string Icon should be contained (boxed) in the medallion, best for icons with transparent background and some margin around */
	public const ENUM_ICON_COVER_METHOD_CONTAIN = 'contain';
	/** @var string Icon should be a litte zoomed out to cover almost all space, best for icons with transparent background and no margin around (eg. class icons) */
	public const ENUM_ICON_COVER_METHOD_ZOOMOUT = 'zoomout';
	/** @var string Icon should cover all the space, best for icons with filled background */
	public const ENUM_ICON_COVER_METHOD_COVER = 'cover';
	/** @var string */
	public const DEFAULT_ICON_COVER_METHOD = self::ENUM_ICON_COVER_METHOD_CONTAIN;

	/** @var int */
	protected $iLevel;
	/** @var string */
	protected $sIconUrl;
	/** @var string How the icon should cover the medallion, see static::ENUM_ICON_COVER_METHOD_COVER, static::ENUM_ICON_COVER_METHOD_ZOOMOUT */
	protected $sIconCoverMethod;
	protected $bIsMedallion;

	/**
	 * @inheritDoc
	 */
	public function __construct(UIBlock $oTitle, int $iLevel = 1, ?string $sId = null)
	{
		parent::__construct($sId);
		$this->iLevel = $iLevel;
		$this->sIconUrl = null;
		$this->sIconCoverMethod = static::DEFAULT_ICON_COVER_METHOD;
		$this->bIsMedallion = true;
		$this->AddSubBlock($oTitle);
	}

	/**
	 * @return int
	 */
	public function GetLevel(): int
	{
		return $this->iLevel;
	}

	public function SetIcon(string $sIconUrl, string $sIconCoverMethod = self::DEFAULT_ICON_COVER_METHOD, bool $bIsMedallion = true)
	{
		$this->sIconUrl = $sIconUrl;
		$this->sIconCoverMethod = $sIconCoverMethod;
		$this->bIsMedallion = $bIsMedallion;

		return $this;
	}

	public function GetIconUrl(): string
	{
		return $this->sIconUrl;
	}

	public function GetIconCoverMethod(): string
	{
		return $this->sIconCoverMethod;
	}

	public function HasIcon(): string
	{
		return !is_null($this->sIconUrl);
	}

	/**
	 * @return bool
	 */
	public function IsMedallion(): bool
	{
		return $this->bIsMedallion;
	}

}
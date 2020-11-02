<?php
/**
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Component\Title;


use Combodo\iTop\Application\UI\UIBlock;

/**
 * Class Title
 *
 * @package Combodo\iTop\Application\UI\Component\Title
 */
class Title extends UIBlock
{
	// Overloaded constants
	/** @inheritDoc */
	public const BLOCK_CODE = 'ibo-title';
	/** @inheritDoc */
	public const HTML_TEMPLATE_REL_PATH = 'components/title/layout';

	/** @var string Icon should cover all the space, best for icons with filled background */
	public const ENUM_ICON_COVER_METHOD_COVER = 'cover';
	/** @var string Icon should be a litte zoomed out to cover almost all space, best for icons with transparent background and no margin around (eg. class icons) */
	public const ENUM_ICON_COVER_METHOD_ZOOMOUT = 'zoomout';
	/** @var string */
	public const DEFAULT_ICON_COVER_METHOD = self::ENUM_ICON_COVER_METHOD_COVER;

	/** @var string */
	protected $sTitle;
	/** @var int */
	protected $iLevel;
	/** @var string */
	protected $sIconUrl;
	/** @var string How the icon should cover the medallion, see static::ENUM_ICON_COVER_METHOD_COVER, static::ENUM_ICON_COVER_METHOD_ZOOMOUT */
	protected $sIconCoverMethod;

	/**
	 * @inheritDoc
	 */
	public function __construct(string $sTitle = '', int $iLevel = 1, ?string $sId = null)
	{
		parent::__construct($sId);
		$this->sTitle = $sTitle;
		$this->iLevel = $iLevel;
		$this->sIconUrl = null;
		$this->sIconCoverMethod = static::DEFAULT_ICON_COVER_METHOD;
	}

	/**
	 * @return string
	 */
	public function GetTitle(): string
	{
		return $this->sTitle;
	}

	/**
	 * @return int
	 */
	public function GetLevel(): int
	{
		return $this->iLevel;
	}

	public function SetIcon(string $sIconUrl, string $sIconCoverMethod = self::DEFAULT_ICON_COVER_METHOD)
	{
		$this->sIconUrl = $sIconUrl;
		$this->sIconCoverMethod = $sIconCoverMethod;
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

}
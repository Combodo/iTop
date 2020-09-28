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
	public const BLOCK_CODE = 'ibo-title';
	public const HTML_TEMPLATE_REL_PATH = 'components/title/layout';

	/** @var string */
	protected $sTitle;
	/** @var int */
	protected $iLevel;
	/** @var string */
	protected $sIconHtml;

	public function __construct(string $sTitle = '', int $iLevel = 1, ?string $sId = null)
	{
		parent::__construct($sId);
		$this->sTitle = $sTitle;
		$this->iLevel = $iLevel;
		$this->sIconHtml = null;
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

	public function SetIcon(string $sIconHtml): self
	{
		$this->sIconHtml = $sIconHtml;
		return $this;
	}

	public function GetIcon(): string
	{
		return $this->sIconHtml;
	}

	public function HasIcon(): string
	{
		return !is_null($this->sIconHtml);
	}

}
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
	public const JS_TEMPLATE_REL_PATH = 'components/title/layout';

	/** @var string */
	protected $sTitle;
	/** @var int */
	protected $iLevel;

	public function __construct(string $sTitle = '', int $iLevel = 1, ?string $sId = null)
	{
		parent::__construct($sId);
		$this->sTitle = $sTitle;
		$this->iLevel = $iLevel;
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
}
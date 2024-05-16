<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Base\Component\Text;


use Combodo\iTop\Application\UI\Base\UIBlock;

/**
 * Class Text
 *
 * @package Combodo\iTop\Application\UI\Base\Component\Text
 */
class Text extends UIBlock
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-text';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/components/text/layout';

	/**@var string */
	protected $sText;

	/**
	 * Text constructor.
	 *
	 * @param string $sText
	 */
	public function __construct(string $sText, ?string $sId = null)
	{
		$this->sText = $sText;
		parent::__construct($sId);
	}


	/**
	 * @return string
	 */
	public function GetText(): string
	{
		return $this->sText;
	}

}
<?php
/**
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
namespace Combodo\iTop\Application\UI\Base\Component\Input\RichText;
use Combodo\iTop\Application\UI\Base\UIBlock;
use utils;

/**
 * Class RichText
 *
 * @package Combodo\iTop\Application\UI\Base\Component\RichText
 */
class RichText extends UIBlock
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-richtext';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/components/input/richtext/layout';
	public const DEFAULT_JS_TEMPLATE_REL_PATH = 'base/components/input/richtext/layout';
	/** @var string */
	protected $aConfig;

	/**
	 * RichText constructor.
	 *
	 * @param string|null $sId
	 */
	public function __construct(?string $sId = null)
	{
		parent::__construct($sId);
		$this->aConfig = json_encode(utils::GetCkeditorPref());
	}


	/**
	 * @return string
	 */
	public function GetConfig()
	{
		return $this->aConfig;
	}
}
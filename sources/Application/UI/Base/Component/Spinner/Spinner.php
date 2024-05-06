<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Base\Component\Spinner;


use Combodo\iTop\Application\UI\Base\UIBlock;

/**
 * Class Spinner
 *
 * @package Combodo\iTop\Application\UI\Base\Component\Spinner
 */
class Spinner extends UIBlock
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-spinner';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/components/spinner/layout';
	
	/* @var string Display size for inline element, rather small */
	public const ENUM_SPINNER_SIZE_INLINE = 'inline';
	/* @var string Display size for small element, displayed in a column */
	public const ENUM_SPINNER_SIZE_SMALL = 'small';
	/* @var string Display size for medium element, displayed in a column */
	public const ENUM_SPINNER_SIZE_MEDIUM = 'medium';

	/* @var string Display size for large element, displayed in a column */
	public const ENUM_SPINNER_SIZE_LARGE = 'large';
	/* @var string Default display size */
	public const ENUM_SPINNER_SIZE_DEFAULT = self::ENUM_SPINNER_SIZE_INLINE;

	/* @var string */
	private $sDescription = '';
	/* @var string */
	private $sSize = self::ENUM_SPINNER_SIZE_DEFAULT;

	public function __construct(?string $sId = null, $sDescription = '')
	{
		parent::__construct($sId);
		$this->sDescription = $sDescription;
	}

	/**
	 * @return string
	 */
	public function GetDescription(): string
	{
		return $this->sDescription;
	}

	/**
	 * @param string $sDescription
	 * @return $this
	 */
	public function SetDescription($sDescription)
	{
		$this->sDescription = $sDescription;
		return $this;
	}
	
	/**
	 * @return bool
	 */
	public function HasDescription(): bool
	{
		return $this->sDescription !== '';
	}
	
	/**
	 * @return string
	 */
	public function GetSize(): string
	{
		return $this->sSize;
	}

	/**
	 * @param string $sSize
	 * @return $this
	 */
	public function SetSize(string $sSize)
	{
		$this->sSize = $sSize;
		return $this;
	}
}
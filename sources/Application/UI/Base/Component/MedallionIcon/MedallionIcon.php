<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Base\Component\MedallionIcon;


use Combodo\iTop\Application\UI\Base\UIBlock;

/**
 * Class MedallionIcon
 *
 * @package Combodo\iTop\Application\UI\Base\Component\MedallionIcon
 */
class MedallionIcon extends UIBlock
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-medallion-icon';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/components/medallion-icon/layout';

	/** @var string $sImageUrl */
	private $sImageUrl;
	/** @var string $sIconClass */
	private $sIconClass;
	/** @var string $sDescription */
	private $sDescription;

	/**
	 * MedallionIcon constructor.
	 *
	 * @param string $sImageUrl
	 * @param string $sIconClass
	 * @param string|null $sId
	 */
	public function __construct(string $sImageUrl = '', string $sIconClass = '', ?string $sId = null)
	{
		parent::__construct($sId);
		$this->sImageUrl = $sImageUrl;
		$this->sIconClass= $sIconClass;
	}

	/**
	 * @return string
	 */
	public function GetImageUrl(): string
	{
		return $this->sImageUrl;
	}

	/**
	 * @param string $sImageUrl
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\MedallionIcon\MedallionIcon
	 */
	public function SetImageUrl($sImageUrl)
	{
		$this->sImageUrl = $sImageUrl;
		return $this;
	}

	/**
	 * @return string
	 */
	public function GetIconClass(): string
	{
		return $this->sIconClass;
	}

	/**
	 * @param string $sIconClass
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\MedallionIcon\MedallionIcon
	 */
	public function SetIconClass($sIconClass)
	{
		$this->sIconClass = $sIconClass;
		return $this;
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
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\MedallionIcon\MedallionIcon
	 */
	public function SetDescription(string $sDescription)
	{
		$this->sDescription = $sDescription;
		return $this;
	}
	
}
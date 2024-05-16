<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Base\Component\Input;


use Combodo\iTop\Application\UI\Base\UIBlock;

/**
 * @package Combodo\iTop\Application\UI\Base\Component\Input
 */
abstract class AbstractInput extends UIBlock
{
	public const BLOCK_CODE = 'ibo-input';
	/** @var string */
	protected $sName;
	/** @var string */
	protected $sValue;
	/**@var string */
	protected $sPlaceholder;

	public function GetName(): string
	{
		return $this->sName;
	}

	/**
	 * @param string $sName
	 *
	 * @return $this
	 */
	public function SetName(string $sName)
	{
		$this->sName = $sName;

		return $this;
	}

	public function GetValue(): ?string
	{
		return $this->sValue;
	}

	/**
	 * @param string|null $sValue
	 *
	 * @return $this
	 */
	public function SetValue(?string $sValue)
	{
		$this->sValue = $sValue;

		return $this;
	}

	/**
	 * @return string
	 */
	public function GetPlaceholder(): ?string
	{
		return $this->sPlaceholder;
	}

	/**
	 * @param string $sPlaceholder
	 *
	 * @return $this
	 */
	public function SetPlaceholder(string $sPlaceholder)
	{
		$this->sPlaceholder = $sPlaceholder;

		return $this;
	}

}
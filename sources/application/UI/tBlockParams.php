<?php
/**
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI;

/**
 * Trait tBlockParams
 *
 * Add generic parameters to blocks
 *
 * @package Combodo\iTop\Application\UI
 */
trait tBlockParams
{
	/** @var array */
	protected $aParameters;

	/**
	 * @param string $sName
	 *
	 * @return mixed|null
	 */
	public function GetParameter(string $sName)
	{
		if (isset($this->aParameters[$sName])) {
			return $this->aParameters[$sName];
		} else {
			return null;
		}
	}

	/**
	 * @param string $sName
	 * @param $value
	 */
	public function AddParameter(string $sName, $value)
	{
		$this->aParameters[$sName] = $value;
	}

	/**
	 * @return array
	 */
	public function GetParameters(): array
	{
		return $this->aParameters;
	}

	/**
	 * @param array $aParameters
	 */
	public function SetParameters(array $aParameters): void
	{
		$this->aParameters = $aParameters;
	}

}
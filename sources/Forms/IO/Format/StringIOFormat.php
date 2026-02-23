<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\IO\Format;

/**
 * String IO format.
 *
 * @package Combodo\iTop\Forms\IO\Format
 * @since 3.3.0
 */
class StringIOFormat extends AbstractIOFormat
{
	public string $sValue;

	/**
	 * @param string $sValue
	 */
	public function __construct(string $sValue)
	{
		$this->sValue = $sValue;
	}

	public function __toString(): string
	{
		return $this->sValue;
	}

	public function jsonSerialize(): mixed
	{
		return $this->sValue;
	}
}

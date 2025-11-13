<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block\IO\Format;

use JsonSerializable;

class StringIOFormat implements JsonSerializable
{
	public string $sValue;

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
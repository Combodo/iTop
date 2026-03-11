<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\IO\Format;

use Combodo\iTop\Forms\IO\Format\AbstractIOFormat;

class IntegerIOFormat extends AbstractIOFormat
{
	public int $oValue;

	public function __construct(string $oValue)
	{
		$this->oValue = intval($oValue);
	}

	public function __toString(): string
	{
		return strval($this->oValue);
	}

	public function jsonSerialize(): mixed
	{
		return strval($this->oValue);
	}
}

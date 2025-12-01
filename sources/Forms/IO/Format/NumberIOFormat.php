<?php

namespace Combodo\iTop\Forms\IO\Format;

/**
 * Number IO format.
 *
 * @package Combodo\iTop\Forms\IO\Format
 * @since 3.3.0
 */
class NumberIOFormat extends AbstractIOFormat
{
	public mixed $oValue;

	public function __construct(string $oValue)
	{
		$this->oValue = $oValue;
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

<?php

namespace Combodo\iTop\Forms\IO\Format;

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

	public static function IsCompatible(string $sOtherFormatClass): bool
	{
		return is_a($sOtherFormatClass, NumberIOFormat::class, true) || is_a($sOtherFormatClass, RawFormat::class, true);
	}
}

<?php

namespace Combodo\iTop\Forms\IO\Format;

class RawFormat extends AbstractIOFormat
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

	public static function IsCompatible(string $sOtherFormatClass): bool
	{
		return true;
	}
}

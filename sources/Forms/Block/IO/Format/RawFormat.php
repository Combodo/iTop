<?php

namespace Combodo\iTop\Forms\Block\IO\Format;

class RawFormat
{
	public string $oValue;

	public function __construct(string $oValue)
	{
		$this->oValue = $oValue;
		// validation du format sinon exception
	}

	public function __toString(): string
	{
		return strval($this->oValue);
	}
}
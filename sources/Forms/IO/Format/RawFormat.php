<?php

namespace Combodo\iTop\Forms\IO\Format;

class RawFormat
{
	public string $sValue;

	public function __construct(string $sValue)
	{
		$this->sValue = $sValue;
		// validation du format sinon exception
	}

	public function __toString(): string
	{
		return strval($this->sValue);
	}
}
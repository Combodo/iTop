<?php

namespace Combodo\iTop\Forms\Block\IO\Format;

class RawFormat
{
	public function __construct(public string $oValue)
	{
		// validation du format sinon exception
	}

	public function __toString(): string
	{
		return strval($this->oValue);
	}
}
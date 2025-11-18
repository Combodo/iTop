<?php

namespace Combodo\iTop\Forms\IO\Format;

class NumberIOFormat
{
	public mixed $oValue;

	public function __construct(string $oValue)
	{
		$this->oValue = $oValue;
		// validation du format sinon exception
	}

	public function __toString(): string
	{
		return $this->oValue;
	}
}

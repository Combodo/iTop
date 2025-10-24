<?php

namespace Combodo\iTop\Forms\Block\IO\Format;

class ClassIOFormat
{
	public function __construct(public string $sClassName)
	{
		// validation du format sinon exception
	}

	public function __toString(): string
	{
		return $this->sClassName;
	}
}
<?php

namespace Combodo\iTop\Forms\Block\IO\Format;

use JsonSerializable;

class ClassIOFormat implements JsonSerializable
{
	public function __construct(public string $sClassName)
	{
		// validation du format sinon exception
	}

	public function __toString(): string
	{
		return $this->sClassName;
	}

	public function jsonSerialize(): mixed
	{
		return $this->sClassName;
	}
}
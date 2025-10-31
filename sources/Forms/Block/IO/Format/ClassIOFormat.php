<?php

namespace Combodo\iTop\Forms\Block\IO\Format;

use JsonSerializable;

class ClassIOFormat implements JsonSerializable
{
	public string $sClassName;

	public function __construct(string $sClassName)
	{
		$this->sClassName = $sClassName;
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
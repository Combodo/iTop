<?php

namespace Combodo\iTop\Forms\IO\Format;

use JsonSerializable;

class BooleanIOFormat implements JsonSerializable
{
	public function __construct(public bool $bValue)
	{

	}

	public function IsTrue(): bool
	{
		return $this->bValue;
	}

	public function __toString(): string
	{
		return $this->bValue ? 'true' : 'false';
	}

	public function jsonSerialize(): mixed
	{
		return $this->bValue;
	}
}

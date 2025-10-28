<?php

namespace Combodo\iTop\Forms\Block\IO\Format;

use JsonSerializable;

class AttributeIOFormat implements JsonSerializable
{
	public function __construct(public string $sAttributeName)
	{
		// validation du format sinon exception
	}

	public function __toString(): string
	{
		return $this->sAttributeName;
	}

	public function jsonSerialize(): mixed
	{
		return $this->sAttributeName;
	}
}
<?php

namespace Combodo\iTop\Forms\IO\Format;

class AttributeIOFormat extends AbstractIOFormat
{
	public string $sAttributeName;

	public function __construct(string $sAttributeName)
	{
		$this->sAttributeName = $sAttributeName;
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

<?php

namespace Combodo\iTop\Forms\IO\Format;

/**
 * Attribute type IO format.
 *
 * @package Combodo\iTop\Forms\IO\Format
 * @since 3.3.0
 */
class AttributeTypeIOFormat extends AbstractIOFormat
{
	public string $sAttributeType;

	public function __construct(string $sAttributeType)
	{
		$this->sAttributeType = $sAttributeType;
	}

	public function __toString(): string
	{
		return $this->sAttributeType;
	}

	public function jsonSerialize(): mixed
	{
		return $this->sAttributeType;
	}
}

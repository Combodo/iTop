<?php

namespace Combodo\iTop\Forms\IO\Format;

use Combodo\iTop\Service\DependencyInjection\ServiceLocator;
use Combodo\iTop\Forms\IO\FormBlockIOException;

/**
 * Attribute type array IO format.
 *
 * @package Combodo\iTop\Forms\IO\Format
 * @since 3.3.0
 */
class AttributeTypeArrayIOFormat extends AbstractIOFormat
{
	public array $aClasses;

	/**
	 */
	public function __construct(array $aClasses)
	{
		$this->aClasses = $aClasses;
	}

	public function __toString(): string
	{
		return json_encode($this->aClasses);
	}

	public function jsonSerialize(): mixed
	{
		return json_encode($this->aClasses);
	}
}

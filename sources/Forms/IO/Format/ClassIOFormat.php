<?php

namespace Combodo\iTop\Forms\IO\Format;

use Combodo\iTop\Service\DependencyInjection\DIService;
use Combodo\iTop\Forms\IO\FormBlockIOException;

/**
 * Class IO format.
 *
 * @package Combodo\iTop\Forms\IO\Format
 * @since 3.3.0
 */
class ClassIOFormat extends AbstractIOFormat
{
	public string $sClassName;

	/**
	 * @throws \Combodo\iTop\Service\DependencyInjection\DIException
	 * @throws \Combodo\iTop\Forms\IO\FormBlockIOException
	 */
	public function __construct(string $sClassName)
	{
		// Check class validity
		/** @var \ModelReflection $oModelReflection */
		$oModelReflection = DIService::GetInstance()->GetService('ModelReflection');
		if (!$oModelReflection->IsValidClass($sClassName)) {
			throw new FormBlockIOException("Class ".json_encode($sClassName)." is not valid");
		}
		$this->sClassName = $sClassName;
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

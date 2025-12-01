<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\IO\Format;

/**
 * Boolean IO format.
 *
 * @package Combodo\iTop\Forms\IO\Format
 * @since 3.3.0
 */
class BooleanIOFormat extends AbstractIOFormat
{
	public bool $bValue;

	public function __construct(bool $bValue)
	{
		$this->bValue = $bValue;
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

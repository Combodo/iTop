<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\PropertyTree;

use Combodo\iTop\PropertyTree\ValueType\AbstractValueType;

/**
 * @since 3.3.0
 */
abstract class AbstractProperty
{
	/** @var array<AbstractProperty> */
	protected array $aChildren;
	private ?AbstractValueType $oValueType;

	public function GetValueType(): ?AbstractValueType
	{
		return $this->oValueType;
	}

	public function SetValueType(AbstractValueType $oValueType): void
	{
		$this->oValueType = $oValueType;
	}

	public function AddChild(AbstractValueType $oValueType): void
	{
		$this->aChildren[] = $oValueType;
	}

	public function GetChildren(): array
	{
		return $this->aChildren;
	}
}

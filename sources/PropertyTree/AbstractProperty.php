<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\PropertyTree;

use Combodo\iTop\PropertyTree\ValueType\AbstractValueType;

abstract class AbstractProperty
{
	private ?AbstractValueType $oValueType;

	public function GetValueType(): ?AbstractValueType
	{
		return $this->oValueType;
	}

	public function SetValueType(AbstractValueType $oValueType): void
	{
		$this->oValueType = $oValueType;
	}

}

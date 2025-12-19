<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\PropertyType\ValueType\Branch;

use Combodo\iTop\PropertyType\ValueType\AbstractValueType;

/**
 * @since 3.3.0
 */
abstract class AbstractBranchValueType extends AbstractValueType
{
	/** @var AbstractValueType[] */
	protected array $aChildren = [];

	/**
	 * @return bool
	 */
	public function IsLeaf(): bool
	{
		return false;
	}

	/**
	 * @return bool
	 */
	public function IsRoot(): bool
	{
		return is_null($this->oParent);
	}

	/**
	 * @param \Combodo\iTop\PropertyType\ValueType\AbstractValueType $oChild
	 *
	 * @return void
	 */
	public function AddChild(AbstractValueType $oChild): void
	{
		$this->aChildren[$oChild->sId] = $oChild;
	}

	/**
	 * @param string $sId
	 *
	 * @return \Combodo\iTop\PropertyType\ValueType\AbstractValueType|null
	 */
	public function GetChild(string $sId): ?AbstractValueType
	{
		return $this->aChildren[$sId] ?? null;
	}

	/**
	 * @return AbstractValueType[]
	 */
	public function GetChildren(): array
	{
		return $this->aChildren;
	}
}

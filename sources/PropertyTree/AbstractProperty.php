<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\PropertyTree;

use Combodo\iTop\DesignElement;
use Combodo\iTop\PropertyTree\ValueType\AbstractValueType;

/**
 * @since 3.3.0
 */
abstract class AbstractProperty
{
	protected string $sId;
	protected ?string $sLabel;

	/** @var array<AbstractProperty> */
	protected array $aChildren;
	protected ?AbstractValueType $oValueType;

	public function InitFromDomNode(DesignElement $oDomNode)
	{
		$this->sId = $oDomNode->getAttribute('id');
		$this->sLabel = $oDomNode->GetChildText('label');
	}

	abstract public function ToPHP(&$aPHPFragments = []): string;

	public function GetValueType(): ?AbstractValueType
	{
		return $this->oValueType;
	}

	public function AddChild(AbstractProperty $oValueType): void
	{
		$this->aChildren[] = $oValueType;
	}

	public function GetChildren(): array
	{
		return $this->aChildren;
	}
}

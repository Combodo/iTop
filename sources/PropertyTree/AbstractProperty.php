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
	protected ?AbstractProperty $oParent;
	protected string $sId;
	protected ?string $sLabel;

	/** @var array<AbstractProperty> */
	protected array $aChildren = [];
	protected ?AbstractValueType $oValueType;
	protected ?string $sIdWithPath;

	/**
	 * Init property tree node from xml dom node
	 *
	 * @param \Combodo\iTop\DesignElement $oDomNode
	 * @param string $sParentId
	 *
	 * @return void
	 * @throws \DOMFormatException
	 * @throws \Combodo\iTop\PropertyTree\PropertyTreeException
	 */
	public function InitFromDomNode(DesignElement $oDomNode, ?AbstractProperty $oParent = null): void
	{
		$this->oParent = $oParent;
		$this->sId = $oDomNode->getAttribute('id');
		if (is_null($oParent)) {
			$this->sIdWithPath = $this->sId;
		} else {
			$this->sIdWithPath = $oParent->sIdWithPath.'__'.$this->sId;
		}
		$this->sLabel = $oDomNode->GetChildText('label');
	}

	abstract public function ToPHPFormBlock(array &$aPHPFragments = []): string;

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

	public function GetSibling(string $sId): ?AbstractProperty
	{
		if (is_null($this->oParent)) {
			return null;
		}

		foreach ($this->oParent->GetChildren() as $oSibling) {
			if ($oSibling->sId == $sId) {
				return $oSibling;
			}
		}

		return null;
	}

	public function GetIdWithPath(): ?string
	{
		return $this->sIdWithPath;
	}

}

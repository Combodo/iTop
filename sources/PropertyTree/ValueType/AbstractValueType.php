<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\PropertyTree\ValueType;

use Combodo\iTop\DesignElement;
use Combodo\iTop\Forms\IO\FormInput;
use Combodo\iTop\PropertyTree\AbstractProperty;
use utils;

/**
 * @since 3.3.0
 */
abstract class AbstractValueType
{
	abstract public function GetFormBlockClass(): string;

	/** @var FormInput[] */
	protected array $aInputs = [];
	protected array $aOutputs = [];
	protected array $aInputValues = [];
	protected array $aDynamicInputValues = [];
	protected array $aFormBlockOptionsForPHP = [];

	/**
	 * @param \Combodo\iTop\DesignElement $oDomNode
	 * @param \Combodo\iTop\PropertyTree\AbstractProperty $oParent Parent node (used for trees)
	 *
	 * @return void
	 * @throws \DOMFormatException
	 */
	public function InitFromDomNode(DesignElement $oDomNode, AbstractProperty $oParent): void
	{
		$sBlockNodeClass = $this->GetFormBlockClass();
		$oBlockNode = new $sBlockNodeClass('foo');
		foreach ($oBlockNode->GetInputs() as $oInput) {
			$sInputName = $oInput->GetName();
			$this->aInputs[$sInputName] = $oInput;
			$sInputValue = $oDomNode->GetChildText($sInputName);
			if (utils::IsNotNullOrEmptyString($sInputValue)) {
				$this->aInputValues[$sInputName] = $sInputValue;
			}
		}
		foreach ($oBlockNode->GetOutputs() as $oOutput) {
			$this->aOutputs[] = $oOutput->GetName();
		}
	}

	public function GetFormBlockOptions(): array
	{
		return $this->aFormBlockOptionsForPHP;
	}

	public function GetInputValues(): array
	{
		return $this->aInputValues;
	}

	public function GetInputType(string $sInputName): string
	{
		return $this->aInputs[$sInputName]->GetDataType();
	}

	public function GetDynamicInputValues(): array
	{
		return $this->aDynamicInputValues;
	}

	public function GetOutputs(): array
	{
		return $this->aOutputs;
	}

	public function UpdatePHPFragmentsList(array &$aPHPFragments): void
	{
	}
}

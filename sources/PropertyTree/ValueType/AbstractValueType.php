<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\PropertyTree\ValueType;

use Combodo\iTop\DesignElement;
use utils;

/**
 * @since 3.3.0
 */
abstract class AbstractValueType
{
	abstract public function GetFormBlockClass(): string;

	protected array $aInputs = [];

	public function InitFromDomNode(DesignElement $oDomNode): void
	{
		$sBlockNodeClass = $this->GetFormBlockClass();
		$oBlockNode = new $sBlockNodeClass('foo');
		foreach ($oBlockNode->GetInputs() as $oInput) {
			$sInputName = $oInput->GetName();
			$sInputValue = $oDomNode->GetChildText($sInputName);
			if (utils::IsNotNullOrEmptyString($sInputValue)) {
				$this->aInputs[$sInputName] = $sInputValue;
			}
		}
	}

	public function GetInputs(): array
	{
		return $this->aInputs;
	}
}

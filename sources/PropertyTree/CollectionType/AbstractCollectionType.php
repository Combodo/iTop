<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\PropertyTree\CollectionType;

use Combodo\iTop\DesignElement;
use utils;

abstract class AbstractCollectionType
{
	abstract public function GetFormBlockClass(): string;

	public function InitFromDomNode(DesignElement $oDomNode): void
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

}

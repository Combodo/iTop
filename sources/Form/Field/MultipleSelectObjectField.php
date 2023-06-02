<?php

/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Form\Field;


/**
 * Description of MultipleSelectObjectField
 *
 * @author Acc
 * @since 3.1.0
 */
class MultipleSelectObjectField extends SelectObjectField
{
	function SetCurrentValue($currentValue)
	{
		if ($currentValue != null) {
			$this->currentValue = $currentValue;
		} else {
			$this->currentValue = "";
		}

		return $this;
	}
}

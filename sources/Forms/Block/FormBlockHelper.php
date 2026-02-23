<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block;

/**
 * Form block helper.
 *
 * @package Combodo\iTop\Forms\Block
 * @since 3.3.0
 */
class FormBlockHelper
{
	/**
	 * Returns a unique form ID for the given form block, based on its hierarchy.
	 *
	 * @param AbstractFormBlock $oForm
	 *
	 * @return string
	 */
	public static function GetFormId(AbstractFormBlock $oForm): string
	{
		if (is_null($oForm->getParent())) {
			return $oForm->getName();
		}
		return self::GetFormId($oForm->getParent()).'_'.$oForm->getName();
	}
}

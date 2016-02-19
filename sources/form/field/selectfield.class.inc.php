<?php

// Copyright (C) 2010-2016 Combodo SARL
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify	
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>

namespace Combodo\iTop\Form\Field;

use \Closure;
use \Combodo\iTop\Form\Field\MultipleChoicesField;

/**
 * Description of SelectField
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 */
class SelectField extends MultipleChoicesField
{
	const DEFAULT_NULL_CHOICE_LABEL = 'TOTR: - Choisir une valeur -';
	const DEFAULT_STARTS_WITH_NULL_CHOICE = true;

	protected $bStartsWithNullChoice;

	public function __construct($sId, Closure $onFinalizeCallback = null)
	{
		parent::__construct($sId, $onFinalizeCallback);
		$this->bStartsWithNullChoice = static::DEFAULT_STARTS_WITH_NULL_CHOICE;
	}

	/**
	 * Returns if the select starts with a dummy choice before its choices.
	 * This can be useful when you want to force the user to explicitly select a choice.
	 *
	 * @return boolean
	 */
	public function GetStartsWithNullChoice()
	{
		return $this->bStartsWithNullChoice;
	}

	public function SetStartsWithNullChoice($bStartsWithNullChoice)
	{
		$this->bStartsWithNullChoice = $bStartsWithNullChoice;

		if (!array_key_exists(null, $this->aChoices))
		{
			$this->aChoices = array(null => static::DEFAULT_NULL_CHOICE_LABEL) + $this->aChoices;
		}

		return $this;
	}

	/**
	 * Sets the choices for the fields
	 * Overloads the methods for the super class in order to put a dummy choice first if necessary.
	 *
	 * @param array $aChoices
	 * @return \Combodo\iTop\Form\Field\SelectField
	 */
	public function SetChoices($aChoices)
	{
		if ($this->bStartsWithNullChoice && !array_key_exists(null, $aChoices))
		{
			$aChoices = array(null => static::DEFAULT_NULL_CHOICE_LABEL) + $aChoices;
		}

		parent::SetChoices($aChoices);
		return $this;
	}

}

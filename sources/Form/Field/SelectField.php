<?php

// Copyright (C) 2010-2024 Combodo SAS
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

use Closure;
use Dict;

/**
 * Description of SelectField
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @since 2.3.0
 */
class SelectField extends MultipleChoicesField
{
	// Overloaded constants
	/** @inheritDoc  */
	const DEFAULT_MULTIPLE_VALUES_ENABLED = false;

	/** @var string DEFAULT_NULL_CHOICE_LABEL */
	const DEFAULT_NULL_CHOICE_LABEL = 'UI:SelectOne';
	/** @var bool DEFAULT_STARTS_WITH_NULL_CHOICE */
	const DEFAULT_STARTS_WITH_NULL_CHOICE = true;

	/** @var bool $bStartsWithNullChoice */
	protected $bStartsWithNullChoice;

	/**
	 * @inheritDoc
	 */
	public function __construct(string $sId, Closure $onFinalizeCallback = null)
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

    /**
     * @param bool $bStartsWithNullChoice
     *
     * @return $this
     */
	public function SetStartsWithNullChoice(bool $bStartsWithNullChoice)
	{
		$this->bStartsWithNullChoice = $bStartsWithNullChoice;

		return $this;
	}

	/**
	 * Returns the field choices with null choice first
	 *
	 * @return array
	 */
	public function GetChoices()
	{
		$aChoices = parent::GetChoices();
		if ($this->bStartsWithNullChoice && !array_key_exists(null, $aChoices))
		{
			$aChoices = array(null => Dict::S(static::DEFAULT_NULL_CHOICE_LABEL)) + $aChoices;
		}

		return $aChoices;
	}

	/**
	 * Overloads the method to prevent changing this property.
	 *
	 * @param boolean $bMultipleValuesEnabled
	 * @return $this
	 */
	public function SetMultipleValuesEnabled(bool $bMultipleValuesEnabled)
	{
		// We don't allow changing this value
		return $this;
	}

}

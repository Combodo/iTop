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
use \DBSearch;
use Combodo\iTop\Form\Validator\NotEmptyExtKeyValidator;

/**
 * Description of SelectObjectField
 *
 * @author Romain Quetiez <romain.quetiez@combodo.com>
 */
class SelectObjectField extends Field
{
	protected $oSearch;
	protected $iMaximumComboLength;
	protected $iMinAutoCompleteChars;
	protected $bHierarchical;
	protected $iControlType;
	protected $sSearchEndpoint;

	const CONTROL_SELECT = 1;
	const CONTROL_RADIO_VERTICAL = 2;

	public function __construct($sId, Closure $onFinalizeCallback = null)
	{
		parent::__construct($sId, $onFinalizeCallback);
		$this->oSearch = null;
		$this->iMaximumComboLength = null;
		$this->iMinAutoCompleteChars = 3;
		$this->bHierarchical = false;
		$this->iControlType = self::CONTROL_SELECT;
		$this->sSearchEndpoint = null;
	}

	public function SetSearch(DBSearch $oSearch)
	{
		$this->oSearch = $oSearch;
		return $this;
	}

	public function SetMaximumComboLength($iMaximumComboLength)
	{
		$this->iMaximumComboLength = $iMaximumComboLength;
		return $this;
	}

	public function SetMinAutoCompleteChars($iMinAutoCompleteChars)
	{
		$this->iMinAutoCompleteChars = $iMinAutoCompleteChars;
		return $this;
	}

	public function SetHierarchical($bHierarchical)
	{
		$this->bHierarchical = $bHierarchical;
		return $this;
	}

	public function SetControlType($iControlType)
	{
		$this->iControlType = $iControlType;
	}

	public function SetSearchEndpoint($sSearchEndpoint)
	{
		$this->sSearchEndpoint = $sSearchEndpoint;
		return $this;
	}

	/**
	 * Sets if the field is mandatory or not.
	 * Setting the value will automatically add/remove a MandatoryValidator to the Field
	 *
	 * @param boolean $bMandatory
	 * @return \Combodo\iTop\Form\Field\Field
	 */
	public function SetMandatory($bMandatory)
	{
		// Before changing the property, we check if it was already mandatory. If not, we had the mandatory validator
		if ($bMandatory && !$this->bMandatory)
		{
			$this->AddValidator(new NotEmptyExtKeyValidator());
		}

		if (!$bMandatory)
		{
			foreach ($this->aValidators as $iKey => $oValue)
			{
				if ($oValue::Getname() === NotEmptyExtKeyValidator::GetName())
				{
					unset($this->aValidators[$iKey]);
				}
			}
		}

		$this->bMandatory = $bMandatory;
		return $this;
	}

	public function GetSearch()
	{
		return $this->oSearch;
	}

	public function GetMaximumComboLength()
	{
		return $this->iMaximumComboLength;
	}

	public function GetMinAutoCompleteChars()
	{
		return $this->iMinAutoCompleteChars;
	}

	public function GetHierarchical()
	{
		return $this->bHierarchical;
	}

	public function GetControlType()
	{
		return $this->iControlType;
	}

	public function GetSearchEndpoint()
	{
		return $this->sSearchEndpoint;
	}
}

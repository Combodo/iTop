<?php

/**
 * Copyright (C) 2013-2019 Combodo SARL
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */

namespace Combodo\iTop\Form\Field;

use BinaryExpression;
use Closure;
use Combodo\iTop\Form\Validator\NotEmptyExtKeyValidator;
use DBObjectSet;
use DBSearch;
use FieldExpression;
use ScalarExpression;

/**
 * Description of SelectObjectField
 *
 * @author Romain Quetiez <romain.quetiez@combodo.com>
 * @since 2.3.0
 */
class SelectObjectField extends Field
{
	/** @var int CONTROL_SELECT */
	const CONTROL_SELECT = 1;
	/** @var int CONTROL_RADIO_VERTICAL */
	const CONTROL_RADIO_VERTICAL = 2;

	/** @var \DBSearch $oSearch */
	protected $oSearch;
	/** @var int $iMaximumComboLength */
	protected $iMaximumComboLength;
	/** @var int $iMinAutoCompleteChars */
	protected $iMinAutoCompleteChars;
	/** @var bool $bHierarchical */
	protected $bHierarchical;
	/** @var int $iControlType */
	protected $iControlType;
	/** @var string $sSearchEndpoint */
	protected $sSearchEndpoint;

	/**
	 * @inheritDoc
	 */
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

	/**
	 * @param \DBSearch $oSearch
	 *
	 * @return $this
	 */
	public function SetSearch(DBSearch $oSearch)
	{
		$this->oSearch = $oSearch;

		return $this;
	}

	/**
	 * @param int $iMaximumComboLength
	 *
	 * @return $this
	 */
	public function SetMaximumComboLength($iMaximumComboLength)
	{
		$this->iMaximumComboLength = $iMaximumComboLength;

		return $this;
	}

	/**
	 * @param int $iMinAutoCompleteChars
	 *
	 * @return $this
	 */
	public function SetMinAutoCompleteChars($iMinAutoCompleteChars)
	{
		$this->iMinAutoCompleteChars = $iMinAutoCompleteChars;

		return $this;
	}

	/**
	 * @param bool $bHierarchical
	 *
	 * @return $this
	 */
	public function SetHierarchical($bHierarchical)
	{
		$this->bHierarchical = $bHierarchical;

		return $this;
	}

	/**
	 * @param int $iControlType
	 */
	public function SetControlType($iControlType)
	{
		$this->iControlType = $iControlType;
	}

	/**
	 * @param string $sSearchEndpoint
	 *
	 * @return $this
	 */
	public function SetSearchEndpoint($sSearchEndpoint)
	{
		$this->sSearchEndpoint = $sSearchEndpoint;

		return $this;
	}

	/**
	 * @inheritDoc
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

	/**
	 * @return \DBSearch
	 */
	public function GetSearch()
	{
		return $this->oSearch;
	}

	/**
	 * @return int|null
	 */
	public function GetMaximumComboLength()
	{
		return $this->iMaximumComboLength;
	}

	/**
	 * @return int
	 */
	public function GetMinAutoCompleteChars()
	{
		return $this->iMinAutoCompleteChars;
	}

	/**
	 * @return bool
	 */
	public function GetHierarchical()
	{
		return $this->bHierarchical;
	}

	/**
	 * @return int
	 */
	public function GetControlType()
	{
		return $this->iControlType;
	}

	/**
	 * @return string|null
	 */
	public function GetSearchEndpoint()
	{
		return $this->sSearchEndpoint;
	}

	/**
	 * Resets current value if not among allowed ones.
	 * By default, reset is done ONLY when the field is not read-only.
	 *
	 * @param boolean $bAlways Set to true to verify even when the field is read-only.
	 *
	 * @throws \CoreException
	 */
	public function VerifyCurrentValue($bAlways = false)
	{
		if (!$this->GetReadOnly() || $bAlways)
		{
			$oValuesScope = $this->GetSearch()->DeepClone();
			$oBinaryExp = new BinaryExpression(new FieldExpression('id', $oValuesScope->GetClassAlias()), '=',
				new ScalarExpression($this->currentValue));
			$oValuesScope->AddConditionExpression($oBinaryExp);
			$oValuesSet = new DBObjectSet($oValuesScope);

			if ($oValuesSet->Count() === 0)
			{
				$this->currentValue = null;
			}
		}
	}
}

<?php

// Copyright (C) 2010-2018 Combodo SARL
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

namespace Combodo\iTop\Form\Validator;

/**
 * Description of Validator
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 */
class Validator
{
	const VALIDATOR_NAME = 'expression';
	const DEFAULT_REGEXP = '';
	const DEFAULT_ERROR_MESSAGE = 'Core:Validator:Default';

	protected $sRegExp;
	protected $sErrorMessage;

	public static function GetName()
	{
		return static::VALIDATOR_NAME;
	}

    /**
     *
     * @param string $sRegExp
     * @param string $sErrorMessage
     */
	public function __construct($sRegExp = null, $sErrorMessage = null)
	{
		$this->sRegExp = ($sRegExp === null) ? static::DEFAULT_REGEXP : $sRegExp;
		$this->sErrorMessage = ($sErrorMessage === null) ? static::DEFAULT_ERROR_MESSAGE : $sErrorMessage;
		$this->ComputeConstraints();
	}

	/**
	 * Returns the regular expression of the validator.
	 *
	 * @param boolean $bWithSlashes If true, surrounds $sRegExp with '/'. Used with preg_match & co
	 * @return string
	 */
	public function GetRegExp($bWithSlashes = false)
	{
		if ($bWithSlashes)
		{
			$sRet = '/' . str_replace('/', '\\/', $this->sRegExp) . '/';
		}
		else
		{
			$sRet = $this->sRegExp;
		}
		return $sRet;
	}

	public function GetErrorMessage()
	{
		return $this->sErrorMessage;
	}

	public function SetRegExp($sRegExp)
	{
		$this->sRegExp = $sRegExp;
		$this->ComputeConstraints();
		return $this;
	}

	public function SetErrorMessage($sErrorMessage)
	{
		$this->sErrorMessage = $sErrorMessage;
		$this->ComputeConstraints();
		return $this;
	}

	/**
	 * Computes the regular expression and error message when changing constraints on the validator.
	 * Should be called in the validator's setters.
	 */
	public function ComputeConstraints()
	{
		$this->ComputeRegularExpression();
		$this->ComputeErrorMessage();
	}

	/**
	 * Computes the regular expression when changing constraints on the validator.
	 */
	public function ComputeRegularExpression()
	{
		// Overload when necessary
	}

	/**
	 * Computes the error message when changing constraints on the validator.
	 */
	public function ComputeErrorMessage()
	{
		// Overload when necessary
	}

}

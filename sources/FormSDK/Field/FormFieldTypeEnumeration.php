<?php
/*
 * Copyright (C) 2013-2023 Combodo SARL
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

namespace Combodo\iTop\FormSDK\Field;

/**
 * Types of fields.
 *
 * @package FormSDK
 * @since 3.2.0
 */
enum FormFieldTypeEnumeration : string
{
	case TEXT = 'TEXT';
	case NUMBER = 'NUMBER';
	case AREA = 'AREA';
	case DATE = 'DATE';
	case SELECT = 'SELECT';
	case SWITCH = 'SWITCH';
	case DURATION = 'DURATION';
	case FIELDSET = 'FIELDSET';

	/**
	 * Return available options.
	 *
	 * @return string[]
	 */
	public function GetAvailableOptions() : array
	{
		// global options
		$aOptions = ['required', 'disabled', 'attr', 'label', 'label_attr', 'help', 'inherit_data'];

		// specific options
		return  match ($this) {
			FormFieldTypeEnumeration::TEXT =>  array_merge($aOptions,
				['constraints']
			),
			FormFieldTypeEnumeration::SELECT => array_merge($aOptions,
				['placeholder', 'choices', 'expanded', 'multiple']
			),
			FormFieldTypeEnumeration::DATE => array_merge($aOptions,
				['widget']
			),
			FormFieldTypeEnumeration::DURATION => array_merge($aOptions,
				['input', 'with_minutes', 'with_seconds', 'with_weeks', 'with_days']
			),
			FormFieldTypeEnumeration::FIELDSET => array_merge($aOptions,
				['fields', 'layout']
			),
			default => $aOptions,
		};
	}

	/**
	 * Check array of options.
	 *
	 * @param array $aOptions
	 *
	 * @return array{valid: bool, invalid_options: array}
	 */
	public function CheckOptions(array $aOptions) : array
	{
		// invalid options array
		$aInvalidOptions = [];

		// retrieve available options
		$aAvailableOptions = $this->GetAvailableOptions();

		// check each option...
		foreach($aOptions as $sKey => $oOption){
			if(!in_array($sKey, $aAvailableOptions)){
				$aInvalidOptions[] = $sKey;
			}
		}

		return [
			'valid' => empty($aInvalidOptions),
			'invalid_options' => $aInvalidOptions,
		];
	}

}

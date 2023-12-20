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

namespace Combodo\iTop\FormSDK\Field\Description;

/**
 * Form types.
 *
 * @package FormSDK
 * @since 3.2.0
 */
enum FormFieldTypeEnumeration : string
{
	case TEXT = 'TEXT';
	case SELECT = 'SELECT';
//	case SELECT_AJAX = 'SELECT_AJAX';
	case DB_OBJECT = 'DB_OBJECT';

	/**
	 * Return available options.
	 *
	 * @return string[]
	 */
	public function GetAvailableOptions() : array
	{
		$aOptions = ['label', 'required', 'disabled'];

		return match ($this->value) {
			FormFieldTypeEnumeration::SELECT => array_merge($aOptions, ['']),
			default => $aOptions,
		};
	}
}

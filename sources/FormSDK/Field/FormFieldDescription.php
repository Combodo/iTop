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
 * Description of a form field.
 *
 * @package FormSDK
 * @since 3.2.0
 */
class FormFieldDescription
{

	/**
	 * Constructor.
	 *
	 * @param string $sName
	 * @param FormFieldTypeEnumeration $oType
	 * @param array $aOptions
	 */
	public function __construct(
		private readonly string $sName,
		private readonly FormFieldTypeEnumeration $oType,
		private readonly array $aOptions
	)
	{

	}

	/**
	 * Get field type.
	 *
	 * @return FormFieldTypeEnumeration
	 */
	public function GetType() : FormFieldTypeEnumeration
	{
		return $this->oType;
	}

	/**
	 * Get field options.
	 *
	 * @return array
	 */
	public function GetOptions() : array
	{
		return $this->aOptions;
	}

	/**
	 * Get field name.
	 *
	 * @return string
	 */
	public function GetName() : string
	{
		return $this->sName;
	}
}
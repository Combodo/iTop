<?php
/**
 * Copyright (C) 2013-2024 Combodo SAS
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

namespace Combodo\iTop\Application\UI\Base\Component\Input\Set\DataProvider;

/**
 * Interface iDataProvider
 *
 * @package Combodo\iTop\Application\UI\Base\Component\Input\Set\DataProvider
 * @since 3.1.0
 */
interface iDataProvider
{
	public const  TYPE_AJAX_PROVIDER   = "AJAX_PROVIDER";
	public const  TYPE_SIMPLE_PROVIDER = "SIMPLE_PROVIDER";

	/**
	 * GetType.
	 *
	 * @return string
	 */
	public function GetType(): string;

	/**
	 * SetOptions.
	 *
	 * @param array $aOptions
	 *
	 * @return $this
	 */
	public function SetOptions(array $aOptions): iDataProvider;

	/**
	 * SetOption.
	 *
	 * @param string $sKey
	 * @param string $sValue
	 *
	 * @return $this
	 */
	public function SetOption(string $sKey, string $sValue): iDataProvider;

	/**
	 * GetOptions.
	 *
	 * @return array
	 */
	public function GetOptions(): array;

	/**
	 * GetDataValueField.
	 *
	 * @return string
	 */
	public function GetDataValueField(): string;

	/**
	 * SetDataValueField.
	 *
	 * @param string $sField
	 *
	 * @return $this
	 */
	public function SetDataValueField(string $sField): iDataProvider;

	/**
	 * GetDataLabelField.
	 *
	 * @return string
	 */
	public function GetDataLabelField(): string;

	/**
	 * SetDataLabelField.
	 *
	 * @param string $sField
	 *
	 * @return $this
	 */
	public function SetDataLabelField(string $sField): iDataProvider;

	/**
	 * GetDataSearchFields.
	 *
	 * @return array
	 */
	public function GetDataSearchFields(): array;

	/**
	 * SetDataSearchFields.
	 *
	 * @param array $aFields
	 *
	 * @return $this
	 */
	public function SetDataSearchFields(array $aFields): iDataProvider;

	/**
	 * GetGroupField.
	 *
	 * @return string|null
	 */
	public function GetGroupField(): ?string;

	/**
	 * SetGroupField.
	 *
	 * @param string $sField
	 *
	 * @return $this
	 */
	public function SetGroupField(string $sField): iDataProvider;

	/**
	 * GetTooltipField.
	 *
	 * @return string|null
	 */
	public function GetTooltipField(): ?string;

	/**
	 * SetTooltipField.
	 *
	 * @param string $sField
	 *
	 * @return $this
	 */
	public function SetTooltipField(string $sField): iDataProvider;
}
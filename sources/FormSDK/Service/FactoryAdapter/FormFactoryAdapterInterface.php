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

namespace Combodo\iTop\FormSDK\Service\FactoryAdapter;

/**
 * Form factory adapter interface.
 *
 * @package FormSDK
 * @since 3.X.0
 */
interface FormFactoryAdapterInterface
{

	/**
	 * Return adapter identifier.
	 *
	 * @return string
	 */
	public function GetIdentifier() : string;

	/**
	 * Return fields descriptions.
	 *
	 * @return \Combodo\iTop\FormSDK\Field\FormFieldDescription[]
	 */
	public function GetFieldsDescriptions() : array;

	/**
	 * Return fields data.
	 *
	 * @return mixed
	 */
	public function GetFieldsData() : mixed;

	/**
	 * Update fields data.
	 *
	 * @param array $aFormData
	 *
	 * @return bool
	 */
	public function UpdateFieldsData(array $aFormData) : bool;

}
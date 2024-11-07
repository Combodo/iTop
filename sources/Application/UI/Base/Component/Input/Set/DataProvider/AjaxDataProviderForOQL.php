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
 * Class AjaxDataProviderForOQL
 *
 * @package Combodo\iTop\Application\UI\Base\Component\Input\Set\DataProvider
 * @since 3.1.0
 */
class AjaxDataProviderForOQL extends AjaxDataProvider
{

	/**
	 * Constructor.
	 *
	 * @param string $sObjectClass Db Object class
	 * @param string $sOql Oql
	 * @param string|null $sWizardHelperJsVarName Wizard helper name
	 * @param array $aFieldsToLoad Array of fields to load
	 */
	public function __construct(string $sObjectClass, string $sOql, string $sWizardHelperJsVarName = null, array $aFieldsToLoad = [])
	{
		parent::__construct('object.search', [
			'object_class'   => $sObjectClass,
			'oql'            => $sOql,
			'fields_to_load' => json_encode($aFieldsToLoad),
		], [
			'this_object_data' => $sWizardHelperJsVarName != null ? "EVAL_JAVASCRIPT{{$sWizardHelperJsVarName}.UpdateWizardToJSON();}" : "",
		]);

		// Initialization
		$this->Init();
	}

	/**
	 * Initialization.
	 *
	 * @return void
	 */
	private function Init()
	{
		$this->SetDataLabelField('friendlyname')
			->SetDataValueField('key')
			->SetDataSearchFields(['friendlyname', 'additional_field']);
	}

}
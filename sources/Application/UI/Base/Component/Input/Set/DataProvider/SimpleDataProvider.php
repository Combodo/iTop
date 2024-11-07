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
 * Class SimpleDataProvider
 *
 * @package Combodo\iTop\Application\UI\Base\Component\Input\Set\DataProvider
 * @since 3.1.0
 */
class SimpleDataProvider extends AbstractDataProvider
{
	/** @var array $aOptions */
	private array $aOptions;

	/**
	 * Constructor.
	 *
	 * @param array $aOptions
	 */
	public function __construct(array $aOptions = [])
	{
		parent::__construct();

		// retrieve parameters
		$this->SetOptions($aOptions);
	}

	/** @inheritDoc */
	public function GetType(): string
	{
		return iDataProvider::TYPE_SIMPLE_PROVIDER;
	}

	/** @inheritDoc */
	public function SetOptions(array $aOptions): SimpleDataProvider
	{
		$this->aOptions = $aOptions;

		return $this;
	}

	/** @inheritDoc */
	public function SetOption(string $sKey, string $sValue): SimpleDataProvider
	{
		$this->aOptions[$sKey] = $sValue;

		return $this;
	}

	/** @inheritDoc */
	public function GetOptions(): array
	{
		return $this->aOptions;
	}

	/**
	 * GetOptionsGroups.
	 *
	 * @return array
	 */
	public function GetOptionsGroups(): array
	{
		$aGroups = [];
		if ($this->GetGroupField() != null) {
			foreach ($this->GetOptions() as $aOption) {
				if (array_key_exists($this->GetGroupField(), $aOption)) {
					$aGroups[$aOption[$this->GetGroupField()]] = [
						'label' => $aOption[$this->GetGroupField()],
						'value' => $aOption[$this->GetGroupField()],
					];
				}
			}
		}

		return array_values($aGroups);
	}
}
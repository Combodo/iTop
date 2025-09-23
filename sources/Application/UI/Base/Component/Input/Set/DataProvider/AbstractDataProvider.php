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
 * Class AbstractDataProvider
 *
 * @package Combodo\iTop\Application\UI\Base\Component\Input\Set\DataProvider
 * @since 3.1.0
 */
abstract class AbstractDataProvider implements iDataProvider
{
	/** @var string $sDataValueField Field used for input value */
	private string $sDataValueField;

	/** @var string $sDataLabelField Field used for label rendering */
	private string $sDataLabelField;

	/** @var array $aDataSearchFields Fields used for search engine */
	private array $aDataSearchFields;

	/** @var string|null $sGroupField Field used for grouping options */
	private ?string $sGroupField;

	/** @var string|null $sTooltipField Field used for item tooltip */
	private ?string $sTooltipField;

	/**
	 * Constructor.
	 *
	 */
	public function __construct()
	{
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
		$this->sDataLabelField = 'label';
		$this->sDataValueField = 'value';
		$this->aDataSearchFields = ['search'];
		$this->sGroupField = null;
		$this->sTooltipField = 'label';
	}

	/** @inheritDoc */
	public function GetDataValueField(): string
	{
		return $this->sDataValueField;
	}

	/** @inheritDoc */
	public function SetDataValueField(string $sField): AbstractDataProvider
	{
		$this->sDataValueField = $sField;

		return $this;
	}

	/** @inheritDoc */
	public function GetDataLabelField(): string
	{
		return $this->sDataLabelField;
	}

	/** @inheritDoc */
	public function SetDataLabelField(string $sField): AbstractDataProvider
	{
		$this->sDataLabelField = $sField;

		return $this;
	}

	/** @inheritDoc */
	public function GetDataSearchFields(): array
	{
		return $this->aDataSearchFields;
	}

	/** @inheritDoc */
	public function SetDataSearchFields(array $aFields): AbstractDataProvider
	{
		$this->aDataSearchFields = $aFields;

		return $this;
	}

	/** @inheritDoc */
	public function GetGroupField(): ?string
	{
		return $this->sGroupField;
	}

	/** @inheritDoc */
	public function SetGroupField(string $sField): iDataProvider
	{
		$this->sGroupField = $sField;

		return $this;
	}

	/** @inheritDoc */
	public function GetTooltipField(): ?string
	{
		return $this->sTooltipField;
	}

	/** @inheritDoc */
	public function SetTooltipField(string $sField): iDataProvider
	{
		$this->sTooltipField = $sField;

		return $this;
	}

	/**
	 * IsAjaxProviderType.
	 *
	 * @return bool
	 */
	public function IsAjaxProviderType(): bool
	{
		return $this->GetType() === iDataProvider::TYPE_AJAX_PROVIDER;
	}

	/**
	 * IsSimpleProviderType.
	 *
	 * @return bool
	 */
	public function IsSimpleProviderType(): bool
	{
		return $this->GetType() === iDataProvider::TYPE_SIMPLE_PROVIDER;
	}


}
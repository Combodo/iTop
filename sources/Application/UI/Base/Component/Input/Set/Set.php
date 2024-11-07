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

namespace Combodo\iTop\Application\UI\Base\Component\Input\Set;

use Combodo\iTop\Application\UI\Base\Component\Input\AbstractInput;
use Combodo\iTop\Application\UI\Base\Component\Input\Set\DataProvider\iDataProvider;
use Dict;

/**
 * Class Set
 *
 * @package Combodo\iTop\Application\UI\Base\Component\Input\Set
 * @since 3.1.0
 */
class Set extends AbstractInput
{
	// Overloaded constants
	public const BLOCK_CODE                            = 'ibo-set';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH        = 'base/components/input/set/layout';
	public const DEFAULT_JS_ON_READY_TEMPLATE_REL_PATH = 'base/components/input/set/layout';

	public const DEFAULT_JS_FILES_REL_PATH = [
		'js/links/linkset-worker.js',
		'js/object/object-worker.js',
		'js/selectize/plugin_combodo_add_button.js',
		'js/selectize/plugin_combodo_auto_position.js',
		'js/selectize/plugin_combodo_update_operations.js',
		'js/selectize/plugin_combodo_multi_values_synthesis.js',
		'js/selectize/plugin_combodo_min_items.js',
	];

	protected $bIsDisabled = false;

	/** @var int|null $iMaxItems Maximum number of items selectable */
	private ?int $iMaxItems;
	/** @var int|null $iMinItems Minimum number of items selectable */

	private ?int $iMinItems;


	/** @var int|null $iMaxItem Maximum number of displayed options */
	private ?int $iMaxOptions;

	/** @var bool $bHasRemoveItemButton Enable remove item button */
	private bool $bHasRemoveItemButton;

	/** @var bool $bHasAddOptionButton Enable add option button */
	private bool $bHasAddOptionButton;

	/** @var string|null $sAddOptionButtonJsOnClick JS code to execute on button click */
	private ?string $sAddOptionButtonJsOnClick;

	/** @var string $sAddButtonTitle Add button title */
	private string $sAddButtonTitle;

	/** @var string|null $sOnOptionRemoveJs JS code to execute when an option is no longer among available options */
	private ?string $sOnOptionRemoveJs;

	/** @var string|null $sOnOptionAddJs JS code to execute when an option is added to the available options */
	private ?string $sOnOptionAddJs;

	/** @var string|null $sOnItemRemoveJs JS code to execute when a selected item is removed */
	private ?string $sOnItemRemoveJs;

	/** @var string|null $sOnItemAddJs JS code to execute when a new item is selected */
	private ?string $sOnItemAddJs;

	/** @var bool $bIsPreloadEnabled Load data at initialization (ajax data provider only) */
	private bool $bIsPreloadEnabled;

	/** @var string|null $sTemplateOptions Template for rendering options in dropdown (twig) */
	private ?string $sTemplateOptions;

	/** @var string|null $sTemplateItems Template for rendering items in input (twig) */
	private ?string $sTemplateItems;

	/** @var \Combodo\iTop\Application\UI\Base\Component\Input\Set\DataProvider\iDataProvider $oDataProvider Set data provider */
	private iDataProvider $oDataProvider;

	/** @var bool $bIsMultiValuesSynthesis Used for bulk modify for example */
	private bool $bIsMultiValuesSynthesis;

	/** @var bool $bHasError Error flag */
	private bool $bHasError;

	private ?string $sInitialValue = null;

	/**
	 * Constructor.
	 *
	 * @param string|null $sId Block identifier
	 */
	public function __construct(string $sId = null)
	{
		parent::__construct($sId);

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
		$this->SetValue('[]');
		// @todo BDA placeholder depending on autocomplete activation (search...., click to add...)
		$this->SetPlaceholder(Dict::S('Core:AttributeSet:placeholder'));
		$this->iMaxItems = null;
		$this->iMinItems = null;
		$this->iMaxOptions = null;
		$this->bHasRemoveItemButton = true;
		$this->bHasAddOptionButton = false;
		$this->sAddOptionButtonJsOnClick = null;
		$this->sAddButtonTitle = Dict::S('UI:Button:Create');
		$this->sOnItemAddJs = null;
		$this->sOnItemRemoveJs = null;
		$this->sOnOptionAddJs = null;
		$this->sOnOptionRemoveJs = null;
		$this->bIsPreloadEnabled = false;
		$this->sTemplateOptions = null;
		$this->sTemplateItems = null;
		$this->bIsMultiValuesSynthesis = false;
		$this->bHasError = false;
		$this->bIsDisabled = false;
	}

	/**
	 * SetMaxItems.
	 *
	 * @param int|null $iMaxItems
	 *
	 * @return $this
	 */
	public function SetMaxItems(?int $iMaxItems): Set
	{
		$this->iMaxItems = $iMaxItems;

		return $this;
	}

	/**
	 * GetMaxItems.
	 *
	 * @return int|null
	 */
	public function GetMaxItems(): ?int
	{
		return $this->iMaxItems;
	}

	/**
	 * SetMinItems.
	 *
	 * @param int|null $iMinItems
	 *
	 * @return $this
	 * @since 3.2.0
	 */
	public function SetMinItems(?int $iMinItems)
	{
		$this->iMinItems = $iMinItems;

		return $this;
	}

	/**
	 * GetMinItems.
	 *
	 * @return int|null
	 * @since 3.2.0
	 */
	public function GetMinItems(): ?int
	{
		return $this->iMinItems;
	}

	/**
	 * SetMaxOptions.
	 *
	 * @param int|null $iMaxOptions
	 *
	 * @return $this
	 */
	public function SetMaxOptions(?int $iMaxOptions): Set
	{
		$this->iMaxOptions = $iMaxOptions;

		return $this;
	}

	/**
	 * GetMaxOptions.
	 *
	 * @return int|null
	 */
	public function GetMaxOptions(): ?int
	{
		return $this->iMaxOptions;
	}

	/**
	 * SetHasRemoveItemButton.
	 *
	 * @param bool $bHasRemoveItemButton
	 *
	 * @return $this
	 */
	public function SetHasRemoveItemButton(bool $bHasRemoveItemButton): Set
	{
		$this->bHasRemoveItemButton = $bHasRemoveItemButton;

		return $this;
	}

	/**
	 * HasRemoveItemButton.
	 *
	 * @return bool
	 */
	public function HasRemoveItemButton(): bool
	{
		return $this->bHasRemoveItemButton;
	}

	/**
	 * SetAddOptionButtonJsOnClick.
	 *
	 * @param string $sJsOnClick
	 *
	 * @return $this
	 */
	public function SetAddOptionButtonJsOnClick(string $sJsOnClick): Set
	{
		$this->sAddOptionButtonJsOnClick = $sJsOnClick;

		return $this;
	}

	/**
	 * HasAddOptionButtonJsOnClick.
	 *
	 * @return bool
	 */
	public function HasAddOptionButtonJsOnClick(): bool
	{
		return $this->sAddOptionButtonJsOnClick != null;
	}

	/**
	 * GetAddOptionButtonJsOnClick.
	 *
	 * @return string
	 */
	public function GetAddOptionButtonJsOnClick(): string
	{
		return $this->sAddOptionButtonJsOnClick;
	}

	/**
	 * SetHasAddOptionButton.
	 *
	 * @param bool $bHasAddOptionButton
	 *
	 * @return $this
	 */
	public function SetHasAddOptionButton(bool $bHasAddOptionButton): Set
	{
		$this->bHasAddOptionButton = $bHasAddOptionButton;

		return $this;
	}

	/**
	 * HasAddOptionButton.
	 *
	 * @return bool
	 */
	public function HasAddOptionButton(): bool
	{
		return $this->bHasAddOptionButton;
	}

	/**
	 * GetAddButtonTitle.
	 *
	 * @return string
	 */
	public function GetAddButtonTitle(): string
	{
		return $this->sAddButtonTitle;
	}

	/**
	 * SetAddButtonTitle.
	 *
	 * @param string $sTitle
	 *
	 * @return $this
	 */
	public function SetAddButtonTitle(string $sTitle): Set
	{
		$this->sAddButtonTitle = $sTitle;

		return $this;
	}

	/**
	 * SetPreloadEnabled.
	 *
	 * @param bool $bEnabled
	 *
	 * @return $this
	 */
	public function SetPreloadEnabled(bool $bEnabled): Set
	{
		$this->bIsPreloadEnabled = $bEnabled;

		return $this;
	}

	/**
	 * IsPreloadEnabled.
	 *
	 * @return bool
	 */
	public function IsPreloadEnabled(): bool
	{
		return $this->bIsPreloadEnabled;
	}

	/**
	 * SetOptionsTemplate.
	 *
	 * @param string $sTemplate
	 *
	 * @return $this
	 */
	public function SetOptionsTemplate(string $sTemplate): Set
	{
		$this->sTemplateOptions = $sTemplate;

		return $this;
	}

	/**
	 * Return options template.
	 *
	 * @return string
	 */
	public function GetOptionsTemplate(): ?string
	{
		return $this->sTemplateOptions;
	}

	/**
	 * HasOptionsTemplate.
	 *
	 * @return bool
	 */
	public function HasOptionsTemplate(): bool
	{
		return $this->sTemplateOptions != null;
	}

	/**
	 * SetItemsTemplate.
	 *
	 * @param string $sTemplate
	 *
	 * @return $this
	 */
	public function SetItemsTemplate(string $sTemplate): Set
	{
		$this->sTemplateItems = $sTemplate;

		return $this;
	}

	/**
	 * Return items template.
	 *
	 * @return string
	 */
	public function GetItemsTemplate(): ?string
	{
		return $this->sTemplateItems;
	}

	/**
	 * HasItemsTemplate.
	 *
	 * @return bool
	 */
	public function HasItemsTemplate(): bool
	{
		return $this->sTemplateItems != null;
	}

	/**
	 * SetDataProvider.
	 *
	 * @param \Combodo\iTop\Application\UI\Base\Component\Input\Set\DataProvider\iDataProvider $oDataProvider
	 *
	 * @return $this
	 */
	public function SetDataProvider(iDataProvider $oDataProvider): Set
	{
		$this->oDataProvider = $oDataProvider;

		return $this;
	}

	/**
	 * Get data provider.
	 *
	 * @return iDataProvider
	 */
	public function GetDataProvider(): iDataProvider
	{
		return $this->oDataProvider;
	}

	/**
	 * SetIsMultiValuesSynthesis.
	 *
	 * @param bool $bIsMultiValuesSynthesis
	 *
	 * @return $this
	 */
	public function SetIsMultiValuesSynthesis(bool $bIsMultiValuesSynthesis): Set
	{
		$this->bIsMultiValuesSynthesis = $bIsMultiValuesSynthesis;

		return $this;
	}

	/**
	 * IsMultiValuesSynthesis.
	 *
	 * @return bool
	 */
	public function IsMultiValuesSynthesis(): bool
	{
		return $this->bIsMultiValuesSynthesis;
	}

	/**
	 * SetHasError.
	 *
	 * @param $bHasError
	 *
	 * @return $this
	 */
	public function SetHasError($bHasError): Set
	{
		$this->bHasError = $bHasError;

		return $this;
	}

	/**
	 * HasError.
	 *
	 * @return bool
	 */
	public function HasError(): bool
	{
		return $this->bHasError;
	}

	/**
	 * @return string
	 */
	public function GetInitialValue(): string
	{
		if (is_null($this->sInitialValue)) {
			return $this->GetValue();
		}
		return $this->sInitialValue;
	}

	/**
	 * @param string $sInitialValue
	 * @return $this
	 */
	public function SetInitialValue(string $sInitialValue)
	{
		$this->sInitialValue = $sInitialValue;

		return $this;
	}

	/**
	 * @param string|null $sOnOptionRemoveJs
	 *
	 * @return $this
	 */
	public function SetOnOptionRemoveJs(?string $sOnOptionRemoveJs)
	{
		$this->sOnOptionRemoveJs = $sOnOptionRemoveJs;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function GetOnOptionRemoveJs(): ?string
	{
		return $this->sOnOptionRemoveJs;
	}

	/**
	 * @param string|null $sOnOptionAddJs
	 *
	 * @return $this
	 */
	public function SetOnOptionAddJs(?string $sOnOptionAddJs)
	{
		$this->sOnOptionAddJs = $sOnOptionAddJs;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function GetOnOptionAddJs(): ?string
	{
		return $this->sOnOptionAddJs;
	}

	/**
	 * @param string|null $sOnItemRemoveJs
	 *
	 * @return $this
	 */
	public function SetOnItemRemoveJs(?string $sOnItemRemoveJs)
	{
		$this->sOnItemRemoveJs = $sOnItemRemoveJs;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function GetOnItemRemoveJs(): ?string
	{
		return $this->sOnItemRemoveJs;
	}

	/**
	 * @param string|null $sOnItemAddJs
	 *
	 * @return $this
	 */
	public function SetOnItemAddJs(?string $sOnItemAddJs)
	{
		$this->sOnItemAddJs = $sOnItemAddJs;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function GetOnItemAddJs(): ?string
	{
		return $this->sOnItemAddJs;
	}

	/**
	 * @return bool
	 */
	public function IsDisabled(): bool
	{
		return $this->bIsDisabled;
	}

	/**
	 * @param bool $bIsDisabled
	 *
	 * @return $this
	 */
	public function SetIsDisabled(bool $bIsDisabled)
	{
		$this->bIsDisabled = $bIsDisabled;
		return $this;
	}
}
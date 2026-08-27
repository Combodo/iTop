<?php

/**
 * Copyright (C) 2013-2026 Combodo SAS
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

namespace Combodo\iTop\Application\UI\Base\Layout\TopBar\TopBarAction;

use Combodo\iTop\Application\UI\Base\UIBlock;
use utils;

/**
 * Class TopBarAction
 *
 * @package Combodo\iTop\Application\UI\Base\Layout\TopBar\TopBarAction
 * @internal
 * @since 3.3.0
 */
abstract class TopBarQuickAction extends UIBlock
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-top-bar-quick-action';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/layouts/top-bar/top-bar-quick-action/layout';
	public const DEFAULT_JS_TEMPLATE_REL_PATH = 'base/layouts/top-bar/top-bar-quick-action/layout';
	public const DEFAULT_JS_FILES_REL_PATH = [
	];
	public const DEFAULT_CSS_FILES_REL_PATH = [
	];

	protected string $sLabel;
	protected string $sIconClass;
	protected ?string $sTooltip = null;

	/**
	 * TopBarAction constructor.
	 *
	 * @param string $sLabel
	 * @param string $sIconClass
	 * @param string|null $sTooltip
	 * @param string|null $sId
	 *
	 * @throws \CoreException
	 * @throws \DictExceptionMissingString
	 */
	public function __construct(string $sLabel, string $sIconClass, ?string $sTooltip = null, ?string $sId = null)
	{
		parent::__construct($sId);
		$this->sLabel = $sLabel;
		$this->sIconClass = $sIconClass;
		$this->sTooltip = $sTooltip;
	}

	public function GetLabel(): string
	{
		return $this->sLabel;
	}

	public function SetLabel(string $sLabel): static
	{
		$this->sLabel = $sLabel;
		return $this;
	}

	public function GetIconClass(): string
	{
		return $this->sIconClass;
	}

	public function SetIconClass(string $sIconClass): TopBarQuickAction
	{
		$this->sIconClass = $sIconClass;
		return $this;
	}

	public function GetTooltip(): ?string
	{
		return $this->sTooltip;
	}

	public function SetTooltip(?string $sTooltip): TopBarQuickAction
	{
		$this->sTooltip = $sTooltip;
		return $this;
	}

	public function GetAriaAttributes(): array
	{
		$aDefaultValues = [];
		// Default value for aria-label is the tooltip
		if (utils::IsNotNullOrEmptyString($this->sTooltip)) {
			$aDefaultValues['label'] = $this->sTooltip;
		}

		return array_merge($aDefaultValues, parent::GetAriaAttributes());
	}

	public function HasAriaAttributes(): bool
	{
		return  !empty($this->GetAriaAttributes());
	}
}

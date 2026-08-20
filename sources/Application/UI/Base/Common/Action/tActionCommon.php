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

namespace Combodo\iTop\Application\UI\Base\Common\Action;

/**
 * Trait tActionCommon
 *
 * Shared properties and accessors for UI actions.
 *
 * @package Combodo\iTop\Application\UI\Base\Common\Action
 * @internal
 * @since 3.3.0
 */
trait tActionCommon
{
	protected string $sUid;
	protected string $sLabel;
	protected string $sIconClass = '';
	protected ?string $sTooltip = null;
	protected array $aCssClasses = [];

	public function GetUID(): string
	{
		return $this->sUid;
	}

	public function SetUID(string $sUid)
	{
		$this->sUid = $sUid;
		return $this;
	}

	public function GetLabel(): string
	{
		return $this->sLabel;
	}

	public function SetLabel(string $sLabel)
	{
		$this->sLabel = $sLabel;
		return $this;
	}

	public function GetIconClass(): string
	{
		return $this->sIconClass;
	}

	public function SetIconClass(string $sIconClass)
	{
		$this->sIconClass = $sIconClass;
		return $this;
	}

	public function GetTooltip(): ?string
	{
		return $this->sTooltip;
	}

	public function SetTooltip(?string $sTooltip)
	{
		$this->sTooltip = $sTooltip;
		return $this;
	}

	public function GetCssClasses(): array
	{
		return $this->aCssClasses;
	}

	public function SetCssClasses(array $aCssClasses)
	{
		$this->aCssClasses = $aCssClasses;
		return $this;
	}

	public function AddCssClass(string $sCssClass)
	{
		$this->aCssClasses[] = $sCssClass;
		return $this;
	}
}
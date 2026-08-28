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

	public function GetUID(): string
	{
		return $this->sUid;
	}

	public function SetUID(string $sUid): static
	{
		$this->sUid = $sUid;
		return $this;
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

	public function SetIconClass(string $sIconClass): static
	{
		$this->sIconClass = $sIconClass;
		return $this;
	}

	public function GetTooltip(): ?string
	{
		return $this->sTooltip;
	}

	public function SetTooltip(?string $sTooltip): static
	{
		$this->sTooltip = $sTooltip;
		return $this;
	}
}

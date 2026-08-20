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
 * Trait tActionURL
 *
 * Shared properties and accessors for URL-based actions.
 *
 * @package Combodo\iTop\Application\UI\Base\Common\Action
 * @internal
 * @since 3.3.0
 */
trait tActionURL
{
	protected string $sUrl;
	protected ?string $sTarget = null;

	public function GetUrl(): string
	{
		return $this->sUrl;
	}

	public function SetUrl(string $sUrl)
	{
		$this->sUrl = $sUrl;
		return $this;
	}

	public function GetTarget(): ?string
	{
		return $this->sTarget;
	}

	public function SetTarget(?string $sTarget)
	{
		$this->sTarget = $sTarget;
		return $this;
	}
}
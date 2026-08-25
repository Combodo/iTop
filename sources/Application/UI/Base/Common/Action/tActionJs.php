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
 * Trait tActionJs
 *
 * Shared properties and accessors for JS-based actions.
 *
 * @package Combodo\iTop\Application\UI\Base\Common\Action
 * @internal
 * @since 3.3.0
 */
trait tActionJs
{
	protected string $sJsCode;
	protected string $sUrl = '#';
	protected array $aIncludeJSFiles = [];

	public function GetJsCode(): string
	{
		return $this->sJsCode;
	}

	public function SetJsCode(string $sJsCode): static
	{
		$this->sJsCode = $sJsCode;
		return $this;
	}

	public function GetUrl(): string
	{
		return $this->sUrl;
	}

	public function SetUrl(string $sUrl): static
	{
		$this->sUrl = $sUrl;
		return $this;
	}

	public function GetIncludeJSFiles(): array
	{
		return $this->aIncludeJSFiles;
	}

	public function SetIncludeJSFiles(array $aIncludeJSFiles): static
	{
		$this->aIncludeJSFiles = $aIncludeJSFiles;
		return $this;
	}
}

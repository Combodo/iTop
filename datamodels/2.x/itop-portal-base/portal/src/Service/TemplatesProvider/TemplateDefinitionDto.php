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

namespace Combodo\iTop\Portal\Service\TemplatesProvider;

/**
 * Template definition DTO.
 *
 * Describes a template.
 *
 * @package Combodo\iTop\Portal\Service\TemplatesProvider
 * @since 3.2.1
 */
class TemplateDefinitionDto
{
	/**
	 * Create a new template definition instance.
	 *
	 * @param string $sTemplateId template identifier
	 * @param string $sPath template path
	 * @param bool $isOverridable flag set when the template is overridable
	 * @param string|null $sAlias template alias
	 *
	 * @return \Combodo\iTop\Portal\Service\TemplatesProvider\TemplateDefinitionDto
	 */
	public static function Create(string $sTemplateId, string $sPath, bool $isOverridable = true, ?string $sAlias = null): TemplateDefinitionDto
	{
		return new TemplateDefinitionDto($sTemplateId, $sPath, $isOverridable, $sAlias);
	}

	/** @var bool $bIsOverridden flag set when overriding a template */
	private bool $bIsOverridden = false;

	/** @var string|null $sInitialValue Initial template value */
	private ?string $sInitialValue;

	/**
	 * Constructor.
	 *
	 * @param string $sId template identifier
	 * @param string|null $sPath template path
	 * @param bool|null $bIsOverridable flag set when the template is overridable
	 * @param string|null $sAlias template alias
	 */
	private function __construct(
		private readonly string $sId,
		private ?string $sPath = null,
		private readonly ?bool $bIsOverridable = false,
		private readonly ?string $sAlias = null,
	)
	{
		// save overridable values
		$this->sInitialValue = $sPath;
	}

	/**
	 * Return the template identifier.
	 *
	 * @return string
	 */
	public function GetId(): string
	{
		return $this->sId !== null ? $this->sId : '';
	}

	/**
	 * Return the template path.
	 *
	 * @param bool $bInitialValue Return the initial value instead of the overridden one.
	 *
	 * @return string
	 */
	public function GetPath(bool $bInitialValue = false): string
	{
		if($bInitialValue){
			return $this->sInitialValue !== null ? $this->sInitialValue : '';
		}

		return $this->sPath !== null ? $this->sPath : '';
	}

	/**
	 * Return the overridable state.
	 *
	 * @return bool
	 */
	public function IsOverridable(): bool
	{
		return $this->bIsOverridable !== null ? $this->bIsOverridable : false;
	}

	/**
	 * Return the template alias.
	 *
	 * @return string
	 */
	public function GetAlias(): string
	{
		return $this->sAlias !== null ? $this->sAlias : '';
	}

	/**
	 * Override a template.
	 *
	 * @param string $sPath template path
	 *
	 * @return $this
	 */
	public function OverrideTemplatePath(string $sPath): TemplateDefinitionDto
	{
		if ($this->IsOverridable() && $sPath !== $this->sPath) {
			$this->sPath = $sPath;
			$this->bIsOverridden = true;
		}
		return $this;
	}

	/**
	 * Return the overridden flag.
	 *
	 * @noinspection PhpUnused
	 */
	public function IsOverridden(): bool
	{
		return $this->bIsOverridden;
	}

}
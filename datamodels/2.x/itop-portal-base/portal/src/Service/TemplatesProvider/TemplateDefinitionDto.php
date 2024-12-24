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
 *
 * @since 3.2.1
 */
class TemplateDefinitionDto
{
	private bool $bIsOverridden = false;

	private TemplatesKindEnumeration $oInitialType;
	private string $sInitialValue;

	public function __construct(
		private readonly string $sId,
		private ?string $sValue = null,
		private TemplatesKindEnumeration $oType = TemplatesKindEnumeration::PATH,
		private readonly ?bool $bIsOverridable = false,
		private readonly ?string $sAlias = null,
	)
	{
		$this->oInitialType = $oType;
		$this->sInitialValue = $sValue;
	}

	public function GetId(): string
	{
		return $this->sId !== null ? $this->sId : '';
	}

	public function GetType(): ?TemplatesKindEnumeration
	{
		return $this->oType !== null ? $this->oType : null;
	}

	public function GetValue(): string
	{
		return $this->sValue !== null ? $this->sValue : '';
	}

	public function IsOverridable(): bool
	{
		return $this->bIsOverridable !== null ? $this->bIsOverridable : false;
	}

	public function GetAlias(): string
	{
		return $this->sAlias !== null ? $this->sAlias : '';
	}

	public function OverrideTemplate(TemplatesKindEnumeration $oType, string $sValue): TemplateDefinitionDto
	{
		if($this->bIsOverridable){
			$this->oType = $oType;
			$this->sValue = $sValue;
			$this->bIsOverridden = true;
		}
		return $this;
	}

	public function IsOverridden(): bool
	{
		return $this->bIsOverridden;
	}

	public function GetInitialType(): TemplatesKindEnumeration
	{
		return $this->oInitialType;
	}

	public function GetInitialValue(): string
	{
		return $this->sInitialValue;
	}

	public static function Create(string $sTemplateId, string $sValue, TemplatesKindEnumeration $oType = TemplatesKindEnumeration::PATH, bool $isOverridable= true, ?string $sAlias = null): TemplateDefinitionDto
	{
		return new TemplateDefinitionDto($sTemplateId, $sValue, $oType, $isOverridable, $sAlias);
	}

}
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
 * Describe a template.
 *
 * @package Combodo\iTop\Portal\Service\TemplatesProvider
 * @since 3.2.1
 */
class TemplateDefinitionDto
{
	/**
	 * Create a new template definition instance.
	 *
	 * @param string $sTemplateId
	 * @param string $sValue
	 * @param \Combodo\iTop\Portal\Service\TemplatesProvider\TemplatesKindEnumeration $oType
	 * @param bool $isOverridable
	 * @param string|null $sAlias
	 *
	 * @return \Combodo\iTop\Portal\Service\TemplatesProvider\TemplateDefinitionDto
	 */
	public static function Create(string $sTemplateId, string $sValue, TemplatesKindEnumeration $oType = TemplatesKindEnumeration::PATH, bool $isOverridable= true, ?string $sAlias = null): TemplateDefinitionDto
	{
		return new TemplateDefinitionDto($sTemplateId, $sValue, $oType, $isOverridable, $sAlias);
	}

	/** @var bool $bIsOverridden flag set when overriding a template */
	private bool $bIsOverridden = false;

	/** @var \Combodo\iTop\Portal\Service\TemplatesProvider\TemplatesKindEnumeration Initial template type */
	private TemplatesKindEnumeration $oInitialType;

	/** @var string|null $sInitialValue Initial template value */
	private ?string $sInitialValue;

	/**
	 * Constructor.
	 *
	 * @param string $sId
	 * @param string|null $sValue
	 * @param \Combodo\iTop\Portal\Service\TemplatesProvider\TemplatesKindEnumeration $oType
	 * @param bool|null $bIsOverridable
	 * @param string|null $sAlias
	 */
	private function __construct(
		private readonly string $sId,
		private ?string $sValue = null,
		private TemplatesKindEnumeration $oType = TemplatesKindEnumeration::PATH,
		private readonly ?bool $bIsOverridable = false,
		private readonly ?string $sAlias = null,
	)
	{
		// save overridable values
		$this->oInitialType = $oType;
		$this->sInitialValue = $sValue;
	}

	/**
	 * Return the template ID.
	 *
	 * @return string
	 */
	public function GetId(): string
	{
		return $this->sId !== null ? $this->sId : '';
	}

	/**
	 * Return the template type.
	 *
	 * @return \Combodo\iTop\Portal\Service\TemplatesProvider\TemplatesKindEnumeration|null
	 */
	public function GetType(): ?TemplatesKindEnumeration
	{
		return $this->oType !== null ? $this->oType : null;
	}

	/**
	 * Return the template value depending on the template type
	 * @return string
	 */
	public function GetValue(): string
	{
		return $this->sValue !== null ? $this->sValue : '';
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
	 * @param \Combodo\iTop\Portal\Service\TemplatesProvider\TemplatesKindEnumeration $oType
	 * @param string $sValue
	 *
	 * @return $this
	 */
	public function OverrideTemplate(TemplatesKindEnumeration $oType, string $sValue): TemplateDefinitionDto
	{
		if($this->bIsOverridable){
			$this->oType = $oType;
			$this->sValue = $sValue;
			$this->bIsOverridden = true;
		}
		return $this;
	}

	/**
	 * Return the overridden flag.
	 */
	public function IsOverridden(): bool
	{
		return $this->bIsOverridden;
	}

	/**
	 * Return the original template type.
	 *
	 * @return \Combodo\iTop\Portal\Service\TemplatesProvider\TemplatesKindEnumeration
	 */
	public function GetInitialType(): TemplatesKindEnumeration
	{
		return $this->oInitialType;
	}

	/**
	 * Return the original template value.
	 *
	 * @return string
	 */
	public function GetInitialValue(): string
	{
		return $this->sInitialValue;
	}


}
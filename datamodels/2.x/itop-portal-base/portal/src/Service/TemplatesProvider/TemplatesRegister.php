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
 * Template register.
 *
 *
 * @package Combodo\iTop\Portal\Service\TemplatesProvider
 * @since 3.2.1
 */
class TemplatesRegister
{

	/** @var array Templates definitions (possibly altered by portal configuration) */
	private array $aTemplatesDefinitions = [];


	public function __construct(private string $sTemplateUIVersion = 'unset')
	{

	}

	/**
	 * @return string
	 */
	public function GetUIVersion(): string
	{
		return $this->sTemplateUIVersion;
	}

	public function IsProviderExists(string $sProviderId): bool
	{
		return array_key_exists($sProviderId, $this->aTemplatesDefinitions);
	}

	public function IsTemplateExists(string $sProviderId, string $sTemplateId): bool
	{
		return array_key_exists($sProviderId, $this->aTemplatesDefinitions) && array_key_exists($sTemplateId, $this->aTemplatesDefinitions[$sProviderId]);
	}

	/**
	 * Register templates.
	 *
	 * @param string $sProviderId the templates provider id
	 * @param \Combodo\iTop\Portal\Service\TemplatesProvider\TemplateDefinitionDto ...$aTemplatesDefinitions
	 *
	 * @return \Combodo\iTop\Portal\Service\TemplatesProvider\TemplatesRegister
	 */
	public function RegisterTemplates(string $sProviderId, TemplateDefinitionDto...$aTemplatesDefinitions): TemplatesRegister
	{
		// prevent child classes to erase parent templates
		if (array_key_exists($sProviderId, $this->aTemplatesDefinitions)) {
			return $this;
		}

		// register templates...
		$this->aTemplatesDefinitions[$sProviderId] = [];
		foreach ($aTemplatesDefinitions as $oTemplateDefinition) {
			$this->aTemplatesDefinitions[$sProviderId][$oTemplateDefinition->GetId()] = $oTemplateDefinition;
		}

		return $this;
	}

	/**
	 * Get a template definition.
	 *
	 * @param string $sProviderId the templates provider id
	 * @param string $sTemplateId the template id
	 *
	 * @return TemplateDefinitionDto|null
	 */
	public function GetTemplateDefinition(string $sProviderId, string $sTemplateId): ?TemplateDefinitionDto
	{
		// retrieve template path
		if (array_key_exists($sProviderId, $this->aTemplatesDefinitions)) {

			// search in template definitions
			if (array_key_exists($sTemplateId, $this->aTemplatesDefinitions[$sProviderId])) {
				return $this->aTemplatesDefinitions[$sProviderId][$sTemplateId];
			}

			// search in aliases
			foreach ($this->aTemplatesDefinitions[$sProviderId] as $item) {
				/** @var \Combodo\iTop\Portal\Service\TemplatesProvider\TemplateDefinitionDto $item */
				if ($item->GetAlias() === $sTemplateId) {
					return $item;
				}
			}
		}

		return null;
	}

	/**
	 * @param string $sProviderId
	 *
	 * @return array
	 */
	public function GetProviderTemplatesIds(string $sProviderId): array
	{
		return array_map(fn($oTemplateDefinition) => $oTemplateDefinition->GetId(), $this->aTemplatesDefinitions[$sProviderId] ?? ['tile', 'page']);
	}

	/**
	 * Return templates definitions.
	 *
	 * @return array
	 */
	public function GetTemplatesDefinitions(): array
	{
		return $this->aTemplatesDefinitions;
	}
}
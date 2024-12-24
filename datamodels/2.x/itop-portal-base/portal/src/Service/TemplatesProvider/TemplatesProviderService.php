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

use Combodo\iTop\Service\InterfaceDiscovery\InterfaceDiscovery;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

/**
 * Responsible for managing portal templates.
 *
 * @since 3.2.1
 */
class TemplatesProviderService
{
	private string $sTemplateUIVersion = 'portal_base_ui_2017';

	/** @var array Templates definitions (possibly altered by portal configuration) */
	private array $aTemplatesDefinitions = [];

	/**
	 * TemplatesService constructor.
	 *
	 * @param iterable $oTemplatesProviders
	 * @param array $aCombodoPortalInstanceConf
	 */
	public function __construct(
		#[AutowireIterator('combodo.template.provider')]iterable $oTemplatesProviders,
		private array $aCombodoPortalInstanceConf)
	{
		// UI version
		if(isset($aCombodoPortalInstanceConf['properties']['ui_version'])){
			$this->sTemplateUIVersion = $aCombodoPortalInstanceConf['properties']['ui_version'];
		}

		// retrieve properties here
		$aCombodoPortalInstanceConf['bricks'] = [
			'Combodo\\iTop\\Portal\\Controller\\AbstractController' => [
				'page' => 'benji-data-extension/templates/layout.html.twig'
//				'mode_loader' => 'benji-data-extension/templates/empty_for_test.html.twig' ???
//				'modal' => 'benji-data-extension/templates/empty_for_test.html.twig'
			],
			'Combodo\\iTop\\Portal\\Brick\\AbstractBrick' => [
				'page' => 'benji-data-extension/templates/layout.html.twig'
			],
			'Combodo\\iTop\\Portal\\Brick\\PortalBrick' => [
//				'tile' => 'benji-data-extension/templates/tile.html.twig',
			],
			'Combodo\\iTop\\Portal\\Brick\\ManageBrick' => [
//				'tile_default' => 'benji-data-extension/templates/empty_for_test.html.twig',
//				'tile_badge' => 'benji-data-extension/templates/empty_for_test.html.twig',
//				'tile_chart' => 'benji-data-extension/templates/empty_for_test.html.twig',
//				'tile_top_list' => 'benji-data-extension/templates/empty_for_test.html.twig',
//				'page' => 'benji-data-extension/templates/empty_for_test.html.twig',
//				'page_table' => 'benji-data-extension/templates/empty_for_test.html.twig',
//				'page_chart' => 'benji-data-extension/templates/empty_for_test.html.twig',
//				'page_chart_bar' => 'benji-data-extension/templates/empty_for_test.html.twig',
//				'page_chart_Pie' => 'benji-data-extension/templates/empty_for_test.html.twig',
//				'popup_export_excel' => 'benji-data-extension/templates/empty_for_test.html.twig',     ???
			],
		];

		$oTemplatesProviders = InterfaceDiscovery::GetInstance()->FindItopClasses('Combodo\\iTop\\Portal\\Service\\TemplatesProvider\\TemplatesProviderInterface');

		// Initialize templates providers
		$this->RegisterTemplatesProviders2($oTemplatesProviders);

		// Initialize templates providers
//		$this->RegisterTemplatesProviders($oTemplatesProviders);

		// templates overrides
		foreach ($this->aTemplatesDefinitions as $oTemplateProvider => $aTemplates) {
			if(array_key_exists($oTemplateProvider, $aCombodoPortalInstanceConf['bricks'])){
				$aProviderData = $aCombodoPortalInstanceConf['bricks'][$oTemplateProvider];
				foreach($aProviderData as $sTemplateId => $sTemplatePath){
					/** @var TemplateDefinitionDto $TemplateDefinition */
					$TemplateDefinition = $this->aTemplatesDefinitions[$oTemplateProvider][$sTemplateId];
					$TemplateDefinition->OverrideTemplate(TemplatesKindEnumeration::PATH, $sTemplatePath);
				}
			}
		}

		echo '';
	}

	/**
	 * Register templates providers.
	 *
	 * @param iterable $oTemplatesProviders
	 *
	 * @return void
	 */
	private function RegisterTemplatesProviders(iterable $oTemplatesProviders) : void
	{
		// register templates
		foreach ($oTemplatesProviders as $oTemplateProvider) {
			$oTemplateProvider->RegisterTemplates($this);
		}
	}

	/**
	 * Register templates providers.
	 *
	 * @param iterable $oTemplatesProviders
	 *
	 * @return void
	 */
	private function RegisterTemplatesProviders2(iterable $oTemplatesProviders) : void
	{
		// register templates
		foreach ($oTemplatesProviders as $oTemplateProvider) {
			$oTemplateProvider::RegisterTemplates($this);
		}
	}

	/**
	 * Set templates definitions.
	 *
	 * @param string $sScope
	 * @param \Combodo\iTop\Portal\Service\TemplatesProvider\TemplateDefinitionDto ...$aTemplatesDefinitions
	 *
	 * @return $this
	 */
	public function SetTemplatesDefinitions(string $sScope, TemplateDefinitionDto... $aTemplatesDefinitions) : TemplatesProviderService
	{
		// prevent child classes to erase parent templates
		if(array_key_exists($sScope, $this->aTemplatesDefinitions)) {
			return $this;
		}

		foreach ($aTemplatesDefinitions as $oTemplateDefinition) {
			$this->SetTemplateDefinition($sScope, $oTemplateDefinition);
		}

		return $this;
	}

	/**
	 * @param string $sScope
	 * @param TemplateDefinitionDto $oTemplateDefinition
	 *
	 * @return void
	 */
	private function SetTemplateDefinition(string $sScope, TemplateDefinitionDto $oTemplateDefinition) : void
	{
		if(!array_key_exists($sScope, $this->aTemplatesDefinitions)) {
			$this->aTemplatesDefinitions[$sScope] = [];
		}

		$this->aTemplatesDefinitions[$sScope][$oTemplateDefinition->GetId()] = $oTemplateDefinition;
	}

	/**
	 * Get a template path.
	 *
	 * @param string $sScope
	 * @param string $sTemplateId
	 * @param bool $bIsInitial
	 *
	 * @return string|null
	 */
	public function GetTemplatePath(string $sScope, string $sTemplateId, bool $bIsInitial = false) : ?string
	{
		// for registration outside portal src (extensions)
		if(array_key_exists($sScope, $this->aTemplatesDefinitions) === false){
			$sScope::RegisterTemplates($this);
		}
		$oTemplateDefinition = $this->SearchTemplateDefinition($sScope, $sTemplateId);
		if($oTemplateDefinition !== null){
			return $this->GetTemplateDefinitionPath($oTemplateDefinition, $bIsInitial);
		}
		return null;
	}

	/**
	 * Get a template definition.
	 *
	 * @param string $sScope
	 * @param string $sTemplateId
	 *
	 * @return TemplateDefinitionDto|null
	 */
	public function GetTemplateDefinition(string $sScope, string $sTemplateId) : ?TemplateDefinitionDto
	{
		// for registration outside portal src (extensions)
		if(array_key_exists($sScope, $this->aTemplatesDefinitions) === false){
			$sScope::RegisterTemplates($this);
		}
		return $this->SearchTemplateDefinition($sScope, $sTemplateId);
	}

	/**
	 * Search for a template in array of templates definitions.
	 *
	 * @param string $sScope
	 * @param string $sTemplateId
	 *
	 * @return TemplateDefinitionDto|null
	 */
	private function SearchTemplateDefinition(string $sScope, string $sTemplateId) : ?TemplateDefinitionDto
	{
		// retrieve template path
		if(array_key_exists($sScope, $this->aTemplatesDefinitions)){

			// in template definitions
			if(array_key_exists($sTemplateId, $this->aTemplatesDefinitions[$sScope])){
				return $this->aTemplatesDefinitions[$sScope][$sTemplateId];
			}

			// in aliases
			foreach($this->aTemplatesDefinitions[$sScope] as $item){
				/** @var \Combodo\iTop\Portal\Service\TemplatesProvider\TemplateDefinitionDto $item */
				if($item->GetAlias() === $sTemplateId){
					return $item;
				}
			}
		}

		return null;
	}

	/**
	 * Get template definition path.
	 *
	 * @param TemplateDefinitionDto $oTemplateDefinition
	 * @param bool $bIsInitial
	 *
	 * @return string|null
	 */
	private function GetTemplateDefinitionPath(TemplateDefinitionDto $oTemplateDefinition, bool $bIsInitial = false) : ?string
	{
		$oType = $bIsInitial ? $oTemplateDefinition->GetInitialType() : $oTemplateDefinition->GetType();
		$sValue = $bIsInitial ? $oTemplateDefinition->GetInitialValue() : $oTemplateDefinition->GetValue();

		switch($oType){
			// path template
			case TemplatesKindEnumeration::PATH:
				return $sValue;
			// global id template
			case TemplatesKindEnumeration::REFERENCE:
				$sGlobalItem = $this->aTemplatesDefinitions['Combodo\\iTop\\Portal\\Controller\\AbstractController'][$sValue];
				return $sGlobalItem->GetValue();
		}

		return null;
	}

	/**
	 * @return array
	 */
	public function GetTemplatesDefinitions() : array
	{
		return $this->aTemplatesDefinitions;
	}

	/**
	 * @return string
	 */
	public function GetUIVersion() : string
	{
		return $this->sTemplateUIVersion;
	}


}
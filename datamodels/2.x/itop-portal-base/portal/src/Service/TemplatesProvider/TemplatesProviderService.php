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

use Combodo\iTop\Portal\Brick\AbstractBrick;
use Combodo\iTop\Portal\Controller\AbstractController;
use Combodo\iTop\Portal\Controller\DefaultController;
use Combodo\iTop\Service\InterfaceDiscovery\InterfaceDiscovery;
use Exception;
use IssueLog;
use ReflectionClass;

/**
 * Service responsible for managing portal templates.
 *
 * @package Combodo\iTop\Portal\Service\TemplatesProvider
 * @since 3.2.1
 */
class TemplatesProviderService
{
	/** @var string|mixed Templates UI version */
	private string $sTemplateUIVersion = '-';

	/** @var array Templates definitions (possibly altered by portal configuration) */
	private array $aTemplatesDefinitions = [];

	/** @var array overloaded templates paths */
	protected array $aOverloadedTemplatesPaths = [];

	/**
	 * TemplatesService constructor.
	 *
	 * @param array $aCombodoPortalInstanceConf
	 */
	public function __construct(array $aCombodoPortalInstanceConf)
	{
		// UI version
		if(isset($aCombodoPortalInstanceConf['properties']['ui_version'])){
			$this->sTemplateUIVersion = $aCombodoPortalInstanceConf['properties']['ui_version'];
		}

		// Initialize templates providers
		$this->RegisterTemplatesProviders();

		// Portal properties overrides
		$this->PortalPropertiesOverrides($aCombodoPortalInstanceConf['properties']['templates']);
	}

	/**
	 * Overrides templates properties.
	 *
	 * @param array $aPortalTemplatesProperties
	 *
	 * @return void
	 */
	private function PortalPropertiesOverrides(array $aPortalTemplatesProperties) : void
	{
		// Loop through the templates
		foreach ($aPortalTemplatesProperties as $sKey => $oValue){

			switch($sKey){
				case 'layout':
					$oTemplateDefinition = $this->GetTemplateDefinition(AbstractController::class, 'page');
					$oTemplateDefinition->OverrideTemplate(TemplatesKindEnumeration::PATH, $oValue);
					break;
				case 'home':
					$oTemplateDefinition = $this->GetTemplateDefinition(DefaultController::class, 'home');
					$oTemplateDefinition->OverrideTemplate(TemplatesKindEnumeration::PATH, $oValue);
					break;
				default:
					if(is_array($oValue)){
						foreach($oValue as $sTemplateId => $sTemplatePath){
							$oTemplateDefinition = $this->GetTemplateDefinition($sKey, $sTemplateId);
							$oTemplateDefinition?->OverrideTemplate(TemplatesKindEnumeration::PATH, $sTemplatePath);
						}
					}
					break;
			}
		}
	}


	/**
	 * Register templates providers
	 *
	 * @return void
	 */
	private function RegisterTemplatesProviders() : void
	{
		try{
			// search for templates providers
			$oTemplatesProviders = InterfaceDiscovery::GetInstance()->FindItopClasses('Combodo\\iTop\\Portal\\Service\\TemplatesProvider\\TemplatesProviderInterface');

			// register templates
			foreach ($oTemplatesProviders as $oTemplateProvider) {
				$oTemplateProvider::RegisterTemplates($this);
			}
		}
		catch(Exception $e){
			IssueLog::Error($e->getMessage());
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
	 * @param object $oObject
	 * @param string $sTemplateId
	 * @param string $sTileTemplatePath
	 *
	 * @return $this
	 */
	public function SetTemplatePath(object $oObject, string $sTemplateId, string $sTileTemplatePath) : TemplatesProviderService
	{
		$sObjectId = spl_object_id($oObject);

		if(array_key_exists($sObjectId, $this->aOverloadedTemplatesPaths) === false){
			$this->aOverloadedTemplatesPaths[$sObjectId] = [];

			$sId = $sObjectId;
			if($oObject instanceof AbstractBrick){
				$sId = $oObject->GetId();
			}

			$this->aOverloadedTemplatesPaths[$sObjectId]['info'] = [
				'class' => get_class($oObject),
				'id' => $sId,
			];


		}

		if(array_key_exists('templates', $this->aOverloadedTemplatesPaths[$sObjectId]) === false){
			$this->aOverloadedTemplatesPaths[$sObjectId]['templates'] = [];
		}

		$this->aOverloadedTemplatesPaths[$sObjectId]['templates'][$sTemplateId] = $sTileTemplatePath;

		return $this;
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
	 * Search recursively the template path of the brick's.
	 *
	 * @param object $oObject
	 * @param string $sTemplateId
	 *
	 * @return string|null
	 * @since 3.2.1
	 *
	 */
	public function FindBrickDefaultTemplate(object $oObject, string $sTemplateId) : ?string
	{
		$sObjectId = spl_object_id($oObject);

		if(array_key_exists($sObjectId, $this->aOverloadedTemplatesPaths)
		&& array_key_exists($sTemplateId, $this->aOverloadedTemplatesPaths[$sObjectId]['templates'])){
			return $this->aOverloadedTemplatesPaths[$sObjectId]['templates'][$sTemplateId];
		}

		$sCurrentClass = get_class($oObject);
		do{
			$sTemplate = $this->GetTemplatePath($sCurrentClass, $sTemplateId);
			$oReflexion = new ReflectionClass($sCurrentClass);
			$oParent = $oReflexion->getParentClass();
			if($oParent){
				$sCurrentClass = $oReflexion->getParentClass()->getName();
			}
		}while($sTemplate === null && $oParent);

		return $sTemplate;
	}

	/**
	 * @return array
	 */
	public function GetTemplatesDefinitions() : array
	{
		return $this->aTemplatesDefinitions;
	}

	/**
	 * @return array
	 */
	public function GetTemplatesInstancesOverloads() : array
	{
		return $this->aOverloadedTemplatesPaths;
	}

	/**
	 * @return string
	 */
	public function GetUIVersion() : string
	{
		return $this->sTemplateUIVersion;
	}

	/**
	 * Returns the brick overloaded page template path
	 *
	 * @param string $sTemplateId
	 *
	 * @return string|null
	 */
	public function HasInstanceOverloadedTemplate(object $oObject, string $sTemplateId) : ?string
	{
		$sObjectId = spl_object_id($oObject);

		return(array_key_exists($sObjectId, $this->aOverloadedTemplatesPaths)
			&& array_key_exists($sTemplateId, $this->aOverloadedTemplatesPaths[$sObjectId]['templates']));
	}
}
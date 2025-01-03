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
 * The templates provider interface allows any provider to register templates for the portal.
 * The templates registered may be overridden by the portal configuration.
 *
 * Templates are defined in module_design properties section, under the templates key.
 * The layouts for home and default layout still allow to be defined in the portal configuration.
 * Otherwise, the templates for providers are defined as follows:
 * <template id="{class implementing TemplatesProviderInterface}:{template_id}">{path to template}</template>
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

	/** @var array instances overridden templates paths */
	protected array $aInstancesOverriddenTemplatesPaths = [];

	/**
	 * TemplatesService constructor.
	 *
	 * @param array $aCombodoPortalInstanceConf configuration for the current portal instance
	 */
	public function __construct(array $aCombodoPortalInstanceConf)
	{
		// UI version
		if(isset($aCombodoPortalInstanceConf['properties']['ui_version'])){
			$this->sTemplateUIVersion = $aCombodoPortalInstanceConf['properties']['ui_version'];
		}

		// register providers templates
		$this->RegisterProvidersTemplates();

		// overrides templates with portal configuration
		$this->OverrideTemplatesFromPortalProperties($aCombodoPortalInstanceConf['properties']['templates']);
	}

	/**
	 * Register providers templates.
	 *
	 * @return void
	 */
	private function RegisterProvidersTemplates() : void
	{
		try{
			// search for templates providers
			$oTemplatesProviders = InterfaceDiscovery::GetInstance()->FindItopClasses(TemplatesProviderInterface::class);

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
	 * Register templates.
	 *
	 * @param string $sProviderId the templates provider id
	 * @param \Combodo\iTop\Portal\Service\TemplatesProvider\TemplateDefinitionDto ...$aTemplatesDefinitions
	 *
	 * @return $this
	 */
	public function RegisterTemplates(string $sProviderId, TemplateDefinitionDto... $aTemplatesDefinitions) : TemplatesProviderService
	{
		// prevent child classes to erase parent templates
		if(array_key_exists($sProviderId, $this->aTemplatesDefinitions)) {
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
	 * Overrides templates properties.
	 *
	 * @param array $aPortalTemplatesProperties
	 *
	 * @return void
	 */
	private function OverrideTemplatesFromPortalProperties(array $aPortalTemplatesProperties) : void
	{
		// loop through the templates...
		foreach ($aPortalTemplatesProperties as $sKey => $oValue){

			switch($sKey){
				case 'layout':
					$oTemplateDefinition = $this->GetTemplateDefinition(AbstractController::class, 'page');
					$oTemplateDefinition->OverrideTemplate($oValue);
					break;
				case 'home':
					$oTemplateDefinition = $this->GetTemplateDefinition(DefaultController::class, 'home');
					$oTemplateDefinition->OverrideTemplate($oValue);
					break;
				default:
					if(is_array($oValue)){
						foreach($oValue as $sTemplateId => $sTemplatePath){
							$oTemplateDefinition = $this->GetTemplateDefinition($sKey, $sTemplateId);
							$oTemplateDefinition?->OverrideTemplate($sTemplatePath);
						}
					}
					break;
			}
		}
	}

	/**
	 * Override an object instance template path.
	 *
	 * @param object $oObject object instance
	 * @param string $sTemplateId the template id
	 * @param string $sTemplatePath the template path
	 *
	 * @return $this
	 */
	public function OverrideInstanceTemplatePath(object $oObject, string $sTemplateId, string $sTemplatePath) : TemplatesProviderService
	{
		// get object UUID
		$sObjectId = spl_object_id($oObject);

		// initialize overloaded object templates and information
		if(array_key_exists($sObjectId, $this->aInstancesOverriddenTemplatesPaths) === false){

			$this->aInstancesOverriddenTemplatesPaths[$sObjectId] = [];
			$this->aInstancesOverriddenTemplatesPaths[$sObjectId]['templates'] = [];

			// friendly id for troubleshooting
			$sId = $sObjectId;
			if($oObject instanceof AbstractBrick){
				$sId = $oObject->GetId();
			}

			// store object information
			$this->aInstancesOverriddenTemplatesPaths[$sObjectId]['info'] = [
				'class' => get_class($oObject),
				'id' => $sId,
			];

		}

		// store template path
		$this->aInstancesOverriddenTemplatesPaths[$sObjectId]['templates'][$sTemplateId] = $sTemplatePath;

		return $this;
	}

	/**
	 * Get a template path.
	 *
	 * @param string $sProviderId the templates provider id
	 * @param string $sTemplateId the template id
	 * @param bool $bIsInitial
	 *
	 * @return string|null
	 */
	public function GetTemplatePath(string $sProviderId, string $sTemplateId, bool $bIsInitial = false) : ?string
	{
		// search for the template definition
		$oTemplateDefinition = $this->GetTemplateDefinition($sProviderId, $sTemplateId);

		// return the template path
		return $oTemplateDefinition?->GetValue($bIsInitial);
	}

	/**
	 * Get a template definition.
	 *
	 * @param string $sProviderId the templates provider id
	 * @param string $sTemplateId the template id
	 *
	 * @return TemplateDefinitionDto|null
	 */
	public function GetTemplateDefinition(string $sProviderId, string $sTemplateId) : ?TemplateDefinitionDto
	{
		// retrieve template path
		if(array_key_exists($sProviderId, $this->aTemplatesDefinitions)){

			// search in template definitions
			if(array_key_exists($sTemplateId, $this->aTemplatesDefinitions[$sProviderId])){
				return $this->aTemplatesDefinitions[$sProviderId][$sTemplateId];
			}

			// search in aliases
			foreach($this->aTemplatesDefinitions[$sProviderId] as $item){
				/** @var \Combodo\iTop\Portal\Service\TemplatesProvider\TemplateDefinitionDto $item */
				if($item->GetAlias() === $sTemplateId){
					return $item;
				}
			}
		}

		return null;
	}

	/**
	 * Get a provider instance template path.
	 *
	 * @param object $oObject
	 * @param string $sTemplateId
	 *
	 * @return string|null
	 * @since 3.2.1
	 *
	 */
	public function GetProviderInstanceTemplatePath(object $oObject, string $sTemplateId) : ?string
	{
		// object UUID
		$sObjectId = spl_object_id($oObject);

		// if instance overload exists, return it
		if(array_key_exists($sObjectId, $this->aInstancesOverriddenTemplatesPaths)
		&& array_key_exists($sTemplateId, $this->aInstancesOverriddenTemplatesPaths[$sObjectId]['templates'])){
			return $this->aInstancesOverriddenTemplatesPaths[$sObjectId]['templates'][$sTemplateId];
		}

		$sCurrentClass = get_class($oObject);
		do{
			$sTemplate = $this->GetTemplatePath($sCurrentClass, $sTemplateId);
			$oParent = null;
			try{
				$oReflexion = new ReflectionClass($sCurrentClass);
				$oParent = $oReflexion->getParentClass();
				if($oParent){
					$sCurrentClass = $oReflexion->getParentClass()->getName();
				}
			}catch(Exception){}

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
	public function GetInstancesOverriddenTemplatesPaths() : array
	{
		return $this->aInstancesOverriddenTemplatesPaths;
	}

	/**
	 * Returns the brick overridden page template path
	 *
	 * @param object $oObject
	 * @param string $sTemplateId
	 *
	 * @return string|null
	 */
	public function HasInstanceOverriddenTemplate(object $oObject, string $sTemplateId) : ?string
	{
		$sObjectId = spl_object_id($oObject);

		return(array_key_exists($sObjectId, $this->aInstancesOverriddenTemplatesPaths)
			&& array_key_exists($sTemplateId, $this->aInstancesOverriddenTemplatesPaths[$sObjectId]['templates']));
	}

	/**
	 * @return string
	 */
	public function GetUIVersion() : string
	{
		return $this->sTemplateUIVersion;
	}

}
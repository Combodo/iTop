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
use ReflectionClass;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

/**
 * Service responsible for managing portal templates.
 *
 * The templates provider interface allows any provider to register templates for the portal.
 * The templates registered may be overridden by the portal configuration and for a specific instance (brick instance).
 *
 * Templates are defined in module_design properties section, under the templates key.
 * The layouts for home and default layout still allow to be defined in the portal configuration.
 * Otherwise, the templates for providers are defined as follows:
 * <template id="{class implementing TemplatesProviderInterface}:{template_id}">{path to template}</template>
 *
 * Templates are store in register object.
 * This register is cached.
 *
 * @package Combodo\iTop\Portal\Service\TemplatesProvider
 * @since 3.2.1
 */
class TemplatesProviderService
{
	/** @var \Combodo\iTop\Portal\Service\TemplatesProvider\TemplatesRegister templates register */
	protected TemplatesRegister $oTemplateRegister;

	/** @var array instances overridden templates paths */
	protected array $aInstancesOverriddenTemplatesPaths = [];

	/**
	 * TemplatesService constructor.
	 *
	 * @param array $aCombodoPortalInstanceConf configuration for the current portal instance
	 * @param \Symfony\Contracts\Cache\CacheInterface $templatesCachePool cache pool for templates
	 *
	 * @throws \Psr\Cache\InvalidArgumentException
	 */
	public function __construct(array $aCombodoPortalInstanceConf, CacheInterface $templatesCachePool)
	{
		// template register cache
		$this->oTemplateRegister = $templatesCachePool->get('portal_templates_register', function (ItemInterface $item) use ($aCombodoPortalInstanceConf): TemplatesRegister {

			// initialize register
			return $this->InitializeTemplatesRegister($aCombodoPortalInstanceConf);
		});

		// brick should be able to give the templates with GetTileTemplate, GetPageTemplate (so it needs to know the service)
		// a more elegant way would be to use a controller to achieve this
		AbstractBrick::SetTemplatesProviderService($this);
	}

	/**
	 * Initialize templates register.
	 * Store the UI version defined in portal instance configuration.
	 * Iterate throw TemplatesProviderInterface implementations to register templates.
	 * Override templates with portal instance configuration.
	 *
	 * @throws \ReflectionException
	 */
	private function InitializeTemplatesRegister(array $aCombodoPortalInstanceConf): TemplatesRegister
	{
		// UI version
		$sUIVersion = 'unset';
		if (isset($aCombodoPortalInstanceConf['properties']['ui_version'])) {
			$sUIVersion = $aCombodoPortalInstanceConf['properties']['ui_version'];
		}

		// create template register
		$oTemplateRegister = new TemplatesRegister($sUIVersion);

		// search for templates providers
		// only non-abstract classes are discovered.
		// classes implementing the interface needs to call the parent to ensure abstracted class levels templates are registered.
		$oTemplatesProviders = InterfaceDiscovery::GetInstance()->FindItopClasses(TemplatesProviderInterface::class);

		// register default templates...
		foreach ($oTemplatesProviders as $oTemplateProvider) {
			$oTemplateProvider::RegisterTemplates($oTemplateRegister);
		}

		// overrides the templates declared in portal configuration...
		foreach ($aCombodoPortalInstanceConf['properties']['templates'] as $sKey => $oValue) {

			switch ($sKey) {
				case 'layout': // legacy configuration
					$oTemplateDefinition = $oTemplateRegister->GetTemplateDefinition(AbstractController::class, 'page');
					$oTemplateDefinition->OverrideTemplatePath($oValue);
					break;
				case 'home': // legacy configuration
					$oTemplateDefinition = $oTemplateRegister->GetTemplateDefinition(DefaultController::class, 'home');
					$oTemplateDefinition->OverrideTemplatePath($oValue);
					break;
				default:
					if (is_array($oValue)) {
						foreach ($oValue as $sTemplateId => $sTemplatePath) {
							$oTemplateDefinition = $oTemplateRegister->GetTemplateDefinition($sKey, $sTemplateId);
							$oTemplateDefinition?->OverrideTemplatePath($sTemplatePath);
						}
					}
					break;
			}
		}

		return $oTemplateRegister;
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
	public function OverrideInstanceTemplatePath(object $oObject, string $sTemplateId, string $sTemplatePath): TemplatesProviderService
	{
		// get object UUID
		$sObjectId = spl_object_id($oObject);

		// initialize overridden object templates and information
		if (array_key_exists($sObjectId, $this->aInstancesOverriddenTemplatesPaths) === false) {

			$this->aInstancesOverriddenTemplatesPaths[$sObjectId] = [];
			$this->aInstancesOverriddenTemplatesPaths[$sObjectId]['templates'] = [];

			// friendly id
			$sId = $sObjectId;
			if ($oObject instanceof AbstractBrick) {
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
	 * @param string $sProviderClass the templates provider class
	 * @param string $sTemplateId the template id
	 * @param bool $bIsInitial
	 *
	 * @return string|null
	 * @throws \ReflectionException
	 */
	public function GetTemplatePath(string $sProviderClass, string $sTemplateId, bool $bIsInitial = false): ?string
	{
		if ($this->oTemplateRegister->IsProviderExists($sProviderClass)) {

			// I
			// SERVICE DECLARATION
			// the provider class is known by service
			// the class register its templates with service

			// search for the template definition
			$oTemplateDefinition = $this->oTemplateRegister->GetTemplateDefinition($sProviderClass, $sTemplateId);

			// return the template path
			return $oTemplateDefinition?->GetPath($bIsInitial);

		} else {

			// II
			// LEGACY DECLARATION
			// the provider class is unknown by service
			// the class register its templates with legacy constants

			return $this->GetLegacyTemplatePath($sProviderClass, $sTemplateId);
		}
	}

	/**
	 * @param string $sProviderClass
	 * @param string $sTemplateId
	 *
	 * @return string|null
	 * @throws \ReflectionException
	 */
	private function GetLegacyTemplatePath(string $sProviderClass, string $sTemplateId): ?string
	{
		// reflexion for class
		$oReflexion = new ReflectionClass($sProviderClass);

		// class defined constants
		$aClassDefinedConstants = array_diff($oReflexion->getConstants(), $oReflexion->getParentClass()->getConstants());

		// return the constant if exists
		return match ($sTemplateId) {
			'page' => array_key_exists('DEFAULT_PAGE_TEMPLATE_PATH', $aClassDefinedConstants) ? $oReflexion->getConstant('DEFAULT_PAGE_TEMPLATE_PATH') : null,
			'tile' => array_key_exists('DEFAULT_TILE_TEMPLATE_PATH', $aClassDefinedConstants) ? $oReflexion->getConstant('DEFAULT_TILE_TEMPLATE_PATH') : null,
			default => null,
		};
	}

	/**
	 * Get a provider instance template path.
	 *
	 * @param object $oObject
	 * @param string $sTemplateId
	 *
	 * @return string|null
	 *
	 */
	public function GetProviderInstanceTemplatePath(object $oObject, string $sTemplateId): ?string
	{
		// object UUID
		$sObjectId = spl_object_id($oObject);

		// get the provider instance class name
		$sCurrentClass = get_class($oObject);

		// get template definition if it exists
		$oTemplateDefinition = $this->oTemplateRegister->GetTemplateDefinition($sCurrentClass, $sTemplateId);
		$sId = $oTemplateDefinition != null ? $oTemplateDefinition->GetId() : $sTemplateId;

		// if instance override exists, return it
		if (array_key_exists($sObjectId, $this->aInstancesOverriddenTemplatesPaths)
			&& array_key_exists($sId, $this->aInstancesOverriddenTemplatesPaths[$sObjectId]['templates'])) {
			return $this->aInstancesOverriddenTemplatesPaths[$sObjectId]['templates'][$sId];
		}

		// now, we search in class hierarchy for a template
		do {
			$oParent = null;
			try {

				// get template path for current class
				$sTemplate = $this->GetTemplatePath($sCurrentClass, $sTemplateId);
				if ($sTemplate !== null) {
					return $sTemplate;
				}

				// no template defined at this level, try parent class
				$oReflexion = new ReflectionClass($sCurrentClass);
				$oParent = $oReflexion->getParentClass();
				if ($oParent) {
					$sCurrentClass = $oReflexion->getParentClass()->getName();
				}
			}
			catch (Exception) {
			}

		} while ($oParent); // continue while parent class exists

		return null; // no template found
	}

	/**
	 * Return the register.
	 *
	 * @return \Combodo\iTop\Portal\Service\TemplatesProvider\TemplatesRegister
	 */
	public function GetRegister(): TemplatesRegister
	{
		return $this->oTemplateRegister;
	}

	/**
	 * Return instances overridden templates paths.
	 *
	 * @return array
	 */
	public function GetInstancesOverriddenTemplatesPaths(): array
	{
		return $this->aInstancesOverriddenTemplatesPaths;
	}

	/**
	 * Returns true if brick template path is overridden.
	 *
	 * @param object $oObject object instance
	 * @param string $sTemplateId template identifier
	 *
	 * @return bool
	 */
	public function HasInstanceOverriddenTemplate(object $oObject, string $sTemplateId): bool
	{
		// object UUID
		$sObjectId = spl_object_id($oObject);

		return (array_key_exists($sObjectId, $this->aInstancesOverriddenTemplatesPaths)
			&& array_key_exists($sTemplateId, $this->aInstancesOverriddenTemplatesPaths[$sObjectId]['templates']));
	}

}
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

namespace Combodo\iTop\Portal\Controller;

use Combodo\iTop\Portal\Service\TemplatesProvider\TemplatesProviderInterface;
use Combodo\iTop\Portal\Service\TemplatesProvider\TemplateDefinitionDto;
use Combodo\iTop\Portal\Service\TemplatesProvider\TemplatesProviderService;
use Combodo\iTop\Portal\Service\TemplatesProvider\TemplatesRegister;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as SymfonyAbstractController;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * Class AbstractController
 *
 * @package Combodo\iTop\Portal\Controller
 * @author  Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @since   2.3.0
 */
abstract class AbstractController extends SymfonyAbstractController implements TemplatesProviderInterface
{
	public const TEMPLATES_BASE_PATH = 'itop-portal-base/portal/templates/';

	/** @inheritdoc  */
	public static function RegisterTemplates(TemplatesRegister $oTemplatesRegister): void
	{
		$oTemplatesRegister->RegisterTemplates(
			self::class,
			TemplateDefinitionDto::Create('page', static::TEMPLATES_BASE_PATH.'layout.html.twig'),
			TemplateDefinitionDto::Create('navigation_menu', static::TEMPLATES_BASE_PATH.'/pages/navigation_menu.html.twig'),
			TemplateDefinitionDto::Create('modal', static::TEMPLATES_BASE_PATH.'modal/layout.html.twig'),
			TemplateDefinitionDto::Create('loader', static::TEMPLATES_BASE_PATH.'helpers/loader.html.twig'),
			TemplateDefinitionDto::Create('tagset_clic_handler_js', static::TEMPLATES_BASE_PATH.'helpers/tagset_clic_handler.js.twig'),
			TemplateDefinitionDto::Create('session_message', static::TEMPLATES_BASE_PATH.'helpers/session_messages/session_message.html.twig'),
			TemplateDefinitionDto::Create('session_messages', static::TEMPLATES_BASE_PATH.'helpers/session_messages/session_messages.html.twig'),
		);
	}

	/**
	 * @var \Symfony\Component\Routing\RouterInterface symfony router
	 *
	 * @since 3.2.0 N°6933
	 */
	private RouterInterface $oRouter;

	#[Required]
	public function setRouter(RouterInterface $oRouter): void
	{
		$this->oRouter = $oRouter;
	}

	/** @var TemplatesProviderService templates provider service */
	private TemplatesProviderService $oTemplatesService;
	#[Required]
	public function SetTemplatesService(TemplatesProviderService $oTemplatesService): void
	{
		$this->oTemplatesService = $oTemplatesService;

	}

	/**
	 * Return the templates provider service.
	 *
	 * @return \Combodo\iTop\Portal\Service\TemplatesProvider\TemplatesProviderService
	 */
	protected function GetTemplatesProviderService(): TemplatesProviderService
	{
		return $this->oTemplatesService;
	}

	/**
	 * Unlike {@see \Symfony\Bundle\FrameworkBundle\Controller\ControllerTrait::redirectToRoute()}, this method directly calls the route controller without creating a redirection client side
	 *
	 * Default route params will be preserved (see N°4356)
	 *
	 * @param string $sRouteName
	 * @param array $aRouteParams
	 * @param array $aQueryParameters
	 * @param bool $bPreserveDefaultRouteParams if true will merge in aRouteParams the default parameters defined for the specified route
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 *
	 * @since 2.7.6 3.0.0 N°4356 method creation
	 */
	protected function ForwardToRoute($sRouteName, $aRouteParams, $aQueryParameters, $bPreserveDefaultRouteParams = true)
	{
		$oRouteCollection = $this->oRouter->getRouteCollection();
		$aRouteDefaults = $oRouteCollection->get($sRouteName)->getDefaults();

		if ($bPreserveDefaultRouteParams) {
			$aRouteParams = array_merge($aRouteDefaults, $aRouteParams);
		}

		return $this->forward($aRouteDefaults['_controller'], $aRouteParams, $aQueryParameters);
	}

	/**
	 * Returns the controller template path
	 *
	 * @since 3.2.1
	 *
	 * @param string $sTemplateId
	 *
	 * @return string
	 */
	public function GetTemplatePath(string $sTemplateId): string
	{
		return static::GetTemplatesProviderService()->GetProviderInstanceTemplatePath($this, $sTemplateId);
	}

	/**
	 * Sets the brick template path
	 *
	 * @since 3.2.1
	 * @param string $sTemplateId
	 * @param string $sTileTemplatePath
	 *
	 * @return \Combodo\iTop\Portal\Controller\AbstractController
	 */
	public function SetTemplatePath(string $sTemplateId, string $sTileTemplatePath): AbstractController
	{
		static::GetTemplatesProviderService()->OverrideInstanceTemplatePath($this, $sTemplateId, $sTileTemplatePath);
		return $this;
	}
}

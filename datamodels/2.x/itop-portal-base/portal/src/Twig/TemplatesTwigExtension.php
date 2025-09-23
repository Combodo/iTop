<?php
/**
 * Copyright (C) 2013-2023 Combodo SARL
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

namespace Combodo\iTop\Portal\Twig;

use Combodo\iTop\Portal\Service\TemplatesProvider\TemplatesProviderService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * New Twig function useful for extending or including template handled by templates provider service.
 *
 * @package Combodo\iTop\Portal\Twig
 * @since 3.2.1
 */
class TemplatesTwigExtension extends AbstractExtension
{
	const DEFAULT_PROVIDER_CLASS = 'Combodo\\iTop\\Portal\\Controller\\AbstractController';

	public function __construct(private readonly TemplatesProviderService $oTemplatesService)
	{
	}

	/** @inheritdoc  */
	public function getFunctions(): array
	{
		return [
			new TwigFunction('template', [$this, 'GetTemplate'], ['id' => null, 'provider' => null, 'provider_instance' => null]),
			new TwigFunction('template_initial', [$this, 'GetInitialTemplate'], ['id' => null, 'provider' => null]),
		];
	}

	/**
	 * Retrieve the path of the desired template (maybe overridden by configuration or by instance).
	 *
	 * @param string $sId template identifier
	 * @param string $sProviderClass provider class FQN
	 * @param object|null $oProviderInstance the provider instance
	 *
	 * @return string the template path
	 * @throws \ReflectionException
	 */
	public function GetTemplate(string $sId, string $sProviderClass = self::DEFAULT_PROVIDER_CLASS, object $oProviderInstance = null): string
	{
		if ($oProviderInstance === null) {
			return $this->oTemplatesService->GetTemplatePath($sProviderClass, $sId);
		} else {
			return $this->oTemplatesService->GetProviderInstanceTemplatePath($oProviderInstance, $sId);
		}
	}

	/**
	 * Retrieve the initial path of the desired template (hardcoded).
	 *
	 * @param string $sId template identifier
	 * @param string $sProviderClass provider class FQN
	 *
	 * @return string the template path
	 * @throws \ReflectionException
	 */
	public function GetInitialTemplate(string $sId, string $sProviderClass = self::DEFAULT_PROVIDER_CLASS): string
	{
		return $this->oTemplatesService->GetTemplatePath($sProviderClass, $sId, true);
	}
}
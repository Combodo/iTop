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
	const DEFAULT_SCOPE = 'Combodo\\iTop\\Portal\\Controller\\AbstractController';

	public function __construct(private readonly TemplatesProviderService $oTemplatesService)
	{
	}

	/** @inheritdoc  */
	public function getFunctions() : array
	{
		return [
			new TwigFunction('template', [$this, 'Template'], ['id' => null, 'scope' => null]),
			new TwigFunction('initial_template', [$this, 'InitialTemplate'], ['id' => null, 'scope' => null]),
		];
	}

	/**
	 * Retrieve the path of the desired template (maybe overridden by configuration).
	 *
	 * @param string $sId
	 * @param string $sScope
	 *
	 * @return string
	 * @throws \Exception
	 */
	public function Template(string $sId, string $sScope = self::DEFAULT_SCOPE): string
	{
		return $this->oTemplatesService->GetTemplatePath($sScope, $sId);
	}

	/**
	 * Retrieve the initial path of the desired template (hardcoded).
	 *
	 * @param string $sId
	 * @param string $sScope
	 *
	 * @return string
	 * @throws \Exception
	 */
	public function InitialTemplate(string $sId, string $sScope = self::DEFAULT_SCOPE): string
	{
		return $this->oTemplatesService->GetTemplatePath($sScope, $sId, true);
	}
}
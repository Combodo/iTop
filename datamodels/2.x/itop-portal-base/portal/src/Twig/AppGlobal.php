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

namespace Combodo\iTop\Portal\Twig;

use appUserPreferences;
use Combodo\iTop\Portal\EventListener\UserProvider;
use Combodo\iTop\Portal\Service\TemplatesProvider\TemplatesProviderService;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

/**
 * Class AppGlobal
 *
 * Twig global injection.
 *
 * @author Benjamin Dalsass <benjamin.dalsass@combodo.com>
 * @package Combodo\iTop\Portal\Twig
 * @since   3.1.0
 */
class AppGlobal extends AbstractExtension implements GlobalsInterface
{
	/**
	 * Constructor.
	 *
	 * @param \Combodo\iTop\Portal\EventListener\UserProvider $userProvider
	 * @param \Combodo\iTop\Portal\Service\TemplatesProvider\TemplatesProviderService $oTemplateProviderService
	 */
	public function __construct(private UserProvider $userProvider, private TemplatesProviderService $oTemplateProviderService)
	{

	}

	/**
	 * Return global variables.
	 *
	 * @return array
	 */
	public function getGlobals(): array
	{
		$data = [];
		$data['allowed_portals'] = $this->userProvider->getAllowedPortals();
		$data['user_preferences'] = json_decode(appUserPreferences::GetAsJSON(), true);

		$data['ui_settings'] = $this->oTemplateProviderService->GetRegister()->GetSettings();

		return $data;
	}

}

<?php
/*
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

namespace Combodo\iTop\FormSDK\Service;

use Combodo\iTop\FormSDK\Symfony\SymfonyBridge;

/**
 * Form manager service.
 *
 * @package FormSDK
 * @since 3.2.0
 */
class FormManager
{

	/**
	 * Constructor.
	 *
	 * @param \Combodo\iTop\FormSDK\Symfony\SymfonyBridge $oSymfonyBridge
	 */
	public function __construct(
		private readonly SymfonyBridge $oSymfonyBridge
	)
	{
	}

	/**
	 * Create a form factory.
	 *
	 * @return \Combodo\iTop\FormSDK\Service\FormFactory
	 */
	public function CreateFactory() : FormFactory
	{
		return new FormFactory($this->oSymfonyBridge);
	}

}
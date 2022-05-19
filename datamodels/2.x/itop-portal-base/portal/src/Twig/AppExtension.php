<?php
/**
 * Copyright (C) 2013-2021 Combodo SARL
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

use AttributeDate;
use Combodo\iTop\Application\TwigBase\Twig\Extension;
use Twig\Extension\AbstractExtension;

use AttributeDateTime;
use AttributeText;
use Twig_SimpleFilter;
use Twig_SimpleFunction;
use utils;
use Dict;
use MetaModel;

/**
 * Class AppExtension
 *
 * Automatically loaded by portal's Symfony configuration to register TWIG extensions.
 * The class must be kept by it is using the factorized filters/functions of the iTop core.
 *
 * @package Combodo\iTop\Portal\Twig
 * @since   2.7.0
 * @author  Bruno Da Silva <bruno.dasilva@combodo.com>
 */
class AppExtension extends AbstractExtension
{
	/**
	 * @inheritDoc
	 */
	public function getFilters()
	{
		return Extension::GetFilters();
	}

	/**
	 * @inheritDoc
	 */
	public function getFunctions()
	{
		return Extension::GetFunctions();
	}


}
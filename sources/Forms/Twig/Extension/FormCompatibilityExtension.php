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

namespace Combodo\iTop\Forms\Twig\Extension;

use Dict;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Extension to provide compatibility with Symfony/Twig standard functions
 *
 * @package Combodo\iTop\Forms\Twig\Extension
 */
class FormCompatibilityExtension extends AbstractExtension
{
	/** @inheritdoc */
	public function getFilters(): array
	{
		return [

			// Alias of dict_s, to be compatible with Symfony/Twig standard
			new TwigFilter('trans', function ($sStringCode, $aData = null, $sTransDomain = false) {
				return Dict::S($sStringCode);
			}),

		];
	}

}

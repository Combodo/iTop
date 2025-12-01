<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Twig\Extension;

use Dict;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Extension to provide compatibility with Symfony/Twig standard functions
 *
 * @package Combodo\iTop\Forms\Twig\Extension
 * @since 3.3.0
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

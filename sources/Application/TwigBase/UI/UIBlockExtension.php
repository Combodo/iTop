<?php
/**
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\TwigBase\UI;


use Combodo\iTop\Application\UI\Base\iUIBlockFactory;
use Twig\Extension\AbstractExtension;
use utils;

/**
 * Class UIBlockExtension
 *
 * @package Combodo\iTop\Application\TwigBase\UI
 * @author  Eric Espie <eric.espie@combodo.com>
 * @since 3.0.0
 */
class UIBlockExtension extends AbstractExtension
{
	/**
	 * @inheritDoc
	 */
	public function getTokenParsers()
	{
		$aParsers = [];

		$sInterface = iUIBlockFactory::class;
		$aFactoryClasses = utils::GetClassesForInterface($sInterface, 'UIBlockFactory');

		foreach ($aFactoryClasses as $sFactoryClass) {
			$aParsers[] = new UIBlockParser($sFactoryClass);
		}

		return $aParsers;
	}

}
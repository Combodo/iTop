<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\TwigBase\UI;


use Combodo\iTop\Application\UI\Base\iUIBlockFactory;
use Combodo\iTop\Service\InterfaceDiscovery\InterfaceDiscovery;
use Twig\Extension\AbstractExtension;

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

		$aFactoryClasses = InterfaceDiscovery::GetInstance()->FindItopClasses(iUIBlockFactory::class);
		foreach ($aFactoryClasses as $sFactoryClass) {
			$aParsers[] = new UIBlockParser($sFactoryClass);
		}

		return $aParsers;
	}

}
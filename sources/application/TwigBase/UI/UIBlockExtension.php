<?php
/**
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\TwigBase\UI;


use Combodo\iTop\Application\TwigBase\UI\Component\UIHtmlParser;
use Exception;
use ReflectionClass;
use Twig\Extension\AbstractExtension;

class UIBlockExtension extends AbstractExtension
{
	private static $aFactoryClasses = null;

	public function getTokenParsers()
	{
		$aParsers = [new UIHtmlParser()];

		$aClassMap = include APPROOT.'lib/composer/autoload_classmap.php';
		if (is_null(self::$aFactoryClasses)) {
			self::$aFactoryClasses = [];
			$sInterface = "Combodo\\iTop\\Application\\UI\\Base\\iUIBlockFactory";
			foreach ($aClassMap as $sPHPClass => $sPHPFile) {
				if (strpos($sPHPClass, 'UIBlockFactory') !== false) {
					try {
						$oRefClass = new ReflectionClass($sPHPClass);
						if ($oRefClass->implementsInterface($sInterface) && $oRefClass->isInstantiable()) {
							self::$aFactoryClasses[] = $sPHPClass;
						}
					} catch (Exception $e) {
					}
				}
			}
		}

		foreach (self::$aFactoryClasses as $sFactoryClass) {
			$aParsers[] = new UIBlockParser($sFactoryClass);
		}

		return $aParsers;
	}
}
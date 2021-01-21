<?php
/**
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\TwigBase\UI;


use Exception;
use ReflectionClass;
use SetupUtils;
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

		$sInterface = "Combodo\\iTop\\Application\\UI\\Base\\iUIBlockFactory";
		$aFactoryClasses = self::GetClassesForInterface($sInterface);

		foreach ($aFactoryClasses as $sFactoryClass) {
			$aParsers[] = new UIBlockParser($sFactoryClass);
		}

		return $aParsers;
	}

	/**
	 * @param string $sInterface
	 *
	 * @return array|mixed
	 */
	public static function GetClassesForInterface(string $sInterface)
	{
		$aFactoryClasses = [];

		if (!utils::IsDevelopmentEnvironment()) {
			// Try to read from cache
			$aFilePath = explode("\\", $sInterface);
			$sInterfaceName = end($aFilePath);
			$sCacheFileName = utils::GetCachePath()."ImplementingInterfaces/$sInterfaceName.php";
			if (is_file($sCacheFileName)) {
				$aFactoryClasses = include $sCacheFileName;
			}
		}

		if (empty($aFactoryClasses)) {
			$aAutoloadClassMaps = [APPROOT.'lib/composer/autoload_classmap.php'];
			// guess all the autoload class maps from the extensions
			$aAutoloadClassMaps = array_merge($aAutoloadClassMaps, glob(APPROOT.'env-'.utils::GetCurrentEnvironment().'/*/vendor/composer/autoload_classmap.php'));

			$aClassMap = [];
			foreach ($aAutoloadClassMaps as $sAutoloadFile) {
				$aTmpClassMap = include $sAutoloadFile;
				$aClassMap = array_merge($aClassMap, $aTmpClassMap);
			}
			$aClassMap = array_keys($aClassMap);
			// Add already loaded classes
			$aCurrentClasses = get_declared_classes();
			$aClassMap = array_merge($aClassMap, $aCurrentClasses);

			foreach ($aClassMap as $sPHPClass) {
				if (strpos($sPHPClass, 'UIBlockFactory')) {
					try {
						$oRefClass = new ReflectionClass($sPHPClass);
						if ($oRefClass->implementsInterface($sInterface) && $oRefClass->isInstantiable()) {
							$aFactoryClasses[] = $sPHPClass;
						}
					} catch (Exception $e) {
					}
				}
			}

			if (!utils::IsDevelopmentEnvironment()) {
				// Save to cache
				$sCacheContent = "<?php\n\nreturn ".var_export($aFactoryClasses, true).";";
				SetupUtils::builddir(dirname($sCacheFileName));
				file_put_contents($sCacheFileName, $sCacheContent);
			}
		}

		return $aFactoryClasses;
	}
}
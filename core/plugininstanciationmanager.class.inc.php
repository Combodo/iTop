<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


class PluginInstanciationManager
{
	public function InstantiatePlugins($m_aExtensionClassNames, $sInterface)
	{
		$newPerInstanceClasses = array();
		if (array_key_exists($sInterface, $m_aExtensionClassNames))
		{
			foreach ($m_aExtensionClassNames[$sInterface] as $sClassName)
			{
				if (class_exists($sClassName))
				{
					$class = new ReflectionClass($sClassName);

					if ($class->isInstantiable())
					{
						$newPerInstanceClasses[$sClassName] = new $sClassName();
					}
				}
			}
		}
		return $newPerInstanceClasses;
	}
}

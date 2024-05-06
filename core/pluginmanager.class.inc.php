<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


class PluginManager
{

	private $m_aExtensionClassNames;
	private static $m_aExtensionClasses;
	private $m_pluginInstantiationManager;

	public function __construct($m_aExtensionClassNames, $m_pluginInstanciationManager = null)
	{
		if (is_null(self::$m_aExtensionClasses)) {
			self::$m_aExtensionClasses = [];
		}
		$this->m_aExtensionClassNames = $m_aExtensionClassNames;

		if ($m_pluginInstanciationManager == null)
		{
			$this->m_pluginInstantiationManager = new PluginInstanciationManager();
		}
		else
		{
			$this->m_pluginInstantiationManager = $m_pluginInstanciationManager;
		}
	}

	/**
	 * @param string $sInterface
	 * @param string|null $sFilterInstanceOf [optional] if given, only instance of this string will be returned
	 * @param bool $bCanInstantiatePlugins internal use, let this value to true
	 *
	 * @return array classes=>instance implementing the given interface
	 */
	public function EnumPlugins($sInterface, $sFilterInstanceOf = null, $bCanInstantiatePlugins = true)
	{
		$aPlugins = array();
		if (array_key_exists($sInterface, self::$m_aExtensionClasses)) {
			$aAllPlugins = self::$m_aExtensionClasses[$sInterface];

			if (is_null($sFilterInstanceOf)) {
				return $aAllPlugins;
			};

			$aPlugins = array();
			foreach ($aAllPlugins as $sPluginClass => $instance) {
				if ($instance instanceof $sFilterInstanceOf) {
					$aPlugins[$sPluginClass] = $instance;
				}
			}
		}
		else
		{
			if ($bCanInstantiatePlugins && array_key_exists($sInterface, $this->m_aExtensionClassNames))
			{
				$this->InstantiatePlugins($sInterface);

				return $this->EnumPlugins($sInterface, $sFilterInstanceOf, false);
			}
		}
		return $aPlugins;
	}

	public function InstantiatePlugins($sInterface)
	{
		self::$m_aExtensionClasses[$sInterface] = $this->m_pluginInstantiationManager->InstantiatePlugins($this->m_aExtensionClassNames, $sInterface);
	}

	/**
	 * @param string $sInterface
	 * @param string $sClassName
	 * @param bool $bCanInstantiatePlugins internal use, let this value to true
	 *
	 * @return mixed the instance of the specified plug-ins for the given interface
	 */
	public function GetPlugins($sInterface, $sClassName, $bCanInstantiatePlugins = true)
	{
		$oInstance = null;
		if (array_key_exists($sInterface, self::$m_aExtensionClasses))
		{
			if (array_key_exists($sClassName, self::$m_aExtensionClasses[$sInterface]))
			{
				return self::$m_aExtensionClasses[$sInterface][$sClassName];
			}
		}
		else
		{
			if ($bCanInstantiatePlugins && array_key_exists($sInterface, $this->m_aExtensionClassNames))
			{
				$this->InstantiatePlugins($sInterface);
				return $this->GetPlugins($sInterface, $sClassName, false);
			}
		}

		return $oInstance;
	}

	/**
	 * For test purpose
	 * @return void
	 * @since 3.1.0
	 */
	protected static function ResetPlugins()
	{
		self::$m_aExtensionClasses = null;
	}
}

<?php

namespace Combodo\iTop\Core\Configuration;

use Config;

class ConfigurationService
{
	/** @var \Config iTop configuration object */
	private Config $oConfig;

	/**
	 * @throws \CoreException
	 * @throws \ConfigException
	 */
	public function __construct(string $sConfigPath)
	{
		$this->oConfig = new Config($sConfigPath);
	}

	public function Get(string $sParameterName){
		return $this->oConfig->Get($sParameterName);
	}

	public function Exist(string $sParameterName){
		return $this->oConfig->IsProperty($sParameterName);
	}
}
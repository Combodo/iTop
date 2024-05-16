<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Service\TemporaryObjects;

use MetaModel;

/**
 * TemporaryObjectConfig.
 *
 * Handle temporary object configuration.
 *
 * @experimental do not use, this feature will be part of a future version
 *
 * @since 3.1
 */
class TemporaryObjectConfig
{

	private int $iGarbageInterval;
	private int $iConfigTemporaryLifetime;
	private bool $bConfigTemporaryForce;
	private int $iWatchdogInterval;
	private static ?TemporaryObjectConfig $oSingletonInstance = null;

	/**
	 * Constructor.
	 *
	 */
	private function __construct()
	{
		// Retrieve service configuration
		$oConfig = MetaModel::GetConfig();
		$this->iGarbageInterval = $oConfig->Get(TemporaryObjectHelper::CONFIG_GARBAGE_INTERVAL);
		$this->iConfigTemporaryLifetime = $oConfig->Get(TemporaryObjectHelper::CONFIG_TEMP_LIFETIME);
		$this->bConfigTemporaryForce = $oConfig->Get(TemporaryObjectHelper::CONFIG_FORCE);
		$this->iWatchdogInterval = $oConfig->Get(TemporaryObjectHelper::CONFIG_GARBAGE_INTERVAL);
	}

	/**
	 * GetInstance.
	 *
	 * @return TemporaryObjectConfig
	 */
	public static function GetInstance(): TemporaryObjectConfig
	{
		if (is_null(self::$oSingletonInstance)) {
			self::$oSingletonInstance = new TemporaryObjectConfig();
		}

		return self::$oSingletonInstance;
	}
	/**
	 * @return int
	 */
	public function GetGarbageInterval(): int
	{
		return $this->iGarbageInterval;
	}

	/**
	 * @param int $iGarbageInterval
	 */
	public function SetGarbageInterval(int $iGarbageInterval): void
	{
		$this->iGarbageInterval = $iGarbageInterval;
	}

	/**
	 * @return int
	 */
	public function GetConfigTemporaryLifetime(): int
	{
		return $this->iConfigTemporaryLifetime;
	}

	/**
	 * @param int $iConfigTemporaryLifetime
	 */
	public function SetConfigTemporaryLifetime(int $iConfigTemporaryLifetime): void
	{
		$this->iConfigTemporaryLifetime = $iConfigTemporaryLifetime;
	}

	/**
	 * @return bool
	 */
	public function GetConfigTemporaryForce(): bool
	{
		return $this->bConfigTemporaryForce;
	}

	/**
	 * @param bool $bConfigTemporaryForce
	 */
	public function SetConfigTemporaryForce(bool $bConfigTemporaryForce): void
	{
		$this->bConfigTemporaryForce = $bConfigTemporaryForce;
	}

	/**
	 * @return int
	 */
	public function GetWatchdogInterval(): int
	{
		return $this->iWatchdogInterval;
	}

	/**
	 * @param int $iWatchdogInterval
	 */
	public function SetWatchdogInterval(int $iWatchdogInterval): void
	{
		$this->iWatchdogInterval = $iWatchdogInterval;
	}

}
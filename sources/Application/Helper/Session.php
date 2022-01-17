<?php
/**
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\Helper;

/**
 * Session management
 * Allow early session close to have multiple ajax calls in parallel
 * When a session parameter is set, the session is re-opened if necessary
 *
 * @since 3.0.0
 */
class Session
{
	/** @var int|null */
	public static $iSessionId = null;
	/** @var bool */
	protected static $bIsInitialized = false;
	/** @var bool */
	protected static $bSessionStarted = false;

	public static function Start()
	{
		self::$bIsInitialized = true;
		if (!self::$bSessionStarted) {
			session_name('itop-'.md5(APPROOT));
			if (!is_null(self::$iSessionId)) {
				session_id(self::$iSessionId);
				self::$bSessionStarted = session_start();
			} else {
				self::$bSessionStarted = session_start();
				self::$iSessionId = session_id();
			}
		}
	}

	public static function WriteClose()
	{
		if (self::$bSessionStarted) {
			session_write_close();
			self::$bSessionStarted = false;
		}
	}

	/**
	 * @param string|array $key key to access to the session variable. To access to $_SESSION['a']['b'] $key must be ['a', 'b']
	 * @param $value
	 */
	public static function Set($key, $value)
	{
		if (!isset($_SESSION) || self::Get($key) == $value) {
			return;
		}
		$aSession = $_SESSION;
		$sSessionVar = &$aSession;
		if (is_array($key)) {
			foreach ($key as $sKey) {
				$sSessionVar = &$sSessionVar[$sKey];
			}
		} else {
			$sSessionVar = &$sSessionVar[$key];
		}
		$sSessionVar = $value;
		if (!self::$bSessionStarted) {
			self::Start();
			$_SESSION = $aSession;
			self::WriteClose();
		} else {
			$_SESSION = $aSession;
		}
	}

	/**
	 * @param string|array $key key to access to the session variable. To access to $_SESSION['a']['b'] $key must be ['a', 'b']
	 */
	public static function Unset($key)
	{
		if (self::IsSet($key)) {
			$aSession = $_SESSION;
			$sSessionVar = &$aSession;
			$sKey = $key;
			// Get the array containing the last key in order to unset the correct variable
			if (is_array($key)) {
				$sPrevKey = null;
				foreach ($key as $sKey) {
					if (!is_null($sPrevKey)) {
						$sSessionVar = &$sSessionVar[$sPrevKey];
					}
					$sPrevKey = $sKey;
				}
			}
			if (!self::$bSessionStarted) {
				self::Start();
				unset($sSessionVar[$sKey]);
				$_SESSION = $aSession;
				self::WriteClose();
			} else {
				unset($sSessionVar[$sKey]);
				$_SESSION = $aSession;
			}
		}
	}

	/**
	 * @param string|array $key key to access to the session variable. To access to $_SESSION['a']['b'] $key must be ['a', 'b']
	 * @param $default
	 *
	 * @return mixed
	 */
	public static function Get($key, $default = null)
	{
		if (isset($_SESSION)) {
			$aSession = $_SESSION;
			$sSessionVar = &$aSession;
			if (is_array($key)) {
				foreach ($key as $SKey) {
					$sSessionVar = &$sSessionVar[$SKey];
				}
			} else {
				$sSessionVar = &$sSessionVar[$key];
			}

			if (isset($sSessionVar)) {
				return $sSessionVar;
			}
		}
		return $default;
	}

	/**
	 * @param string|array $key key to access to the session variable. To access to $_SESSION['a']['b'] $key must be ['a', 'b']
	 *
	 * @return bool
	 */
	public static function IsSet($key): bool
	{
		if (!isset($_SESSION)) {
			return false;
		}

		$aSession = $_SESSION;
		$sSessionVar = &$aSession;
		if (is_array($key)) {
			foreach ($key as $SKey) {
				$sSessionVar = &$sSessionVar[$SKey];
			}
		} else {
			$sSessionVar = &$sSessionVar[$key];
		}

		return isset($sSessionVar);
	}

	public static function ListVariables(): array
	{
		return array_keys($_SESSION);
	}

	/**
	 * @return bool
	 */
	public static function IsInitialized(): bool
	{
		return self::$bIsInitialized;
	}

	/**
	 * @return bool|string
	 */
	public static function GetLog()
	{
		return print_r($_SESSION, true);
	}
}
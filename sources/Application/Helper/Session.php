<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\Helper;

use Combodo\iTop\SessionTracker\SessionHandler;
use utils;

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
	/** @var bool */
	public static $bAllowCLI = false;

	public static function Start()
	{
		if (self::IsModeCLI()) {
			return;
		}

		if (!self::$bIsInitialized) {
			SessionHandler::session_set_save_handler();
			session_name('itop-'.md5(APPROOT));
		}

		self::$bIsInitialized = true;
		if (!self::$bSessionStarted) {
			if (!is_null(self::$iSessionId)) {
				if (session_id(self::$iSessionId) === false) {
					session_regenerate_id(true);
				}
			}
			self::$bSessionStarted = session_start();
			self::$iSessionId = session_id();
		}
	}

	public static function RegenerateId($bDeleteOldSession = false)
	{
		if (self::IsModeCLI()) {
			return;
		}

		session_regenerate_id($bDeleteOldSession);
		if (self::$bSessionStarted) {
			self::WriteClose();
		}
		self::$bSessionStarted = session_start();
		self::$iSessionId = session_id();
	}

	public static function WriteClose()
	{
		if (self::IsModeCLI()) {
			return;
		}

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

	private static function IsModeCLI(): bool
	{
		if (self::$bAllowCLI) {

			return false;
		}

		return utils::IsModeCLI();
	}
}

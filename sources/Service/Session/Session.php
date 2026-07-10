<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Service\Session;

use Combodo\iTop\SessionTracker\SessionHandler;

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
	public static bool $bAllowCLI = false;

	public static function Start(): void
	{
		if (session_status() === PHP_SESSION_DISABLED) {
			return;
		}

		if (self::IsInitialized()) {
			// Session already started
			self::$iSessionId = session_id();
			return;
		}

		SessionHandler::session_set_save_handler();
		session_name('itop-'.md5(APPROOT));

		if (!is_null(self::$iSessionId)) {
			if (session_id(self::$iSessionId) === false) {
				session_regenerate_id(true);
			}
		}
		session_start();
		self::$iSessionId = session_id();
	}

	public static function RegenerateId($bDeleteOldSession = false): void
	{
		if (session_status() === PHP_SESSION_DISABLED || headers_sent()) {
			return;
		}

		session_regenerate_id($bDeleteOldSession);
		if (session_status() === PHP_SESSION_ACTIVE) {
			self::WriteClose();
		}
		session_start();
		self::$iSessionId = session_id();
	}

	public static function WriteClose(): void
	{
		if (session_status() === PHP_SESSION_DISABLED) {
			return;
		}

		if (session_status() === PHP_SESSION_ACTIVE) {
			session_write_close();
		}
	}

	/**
	 * @param string|array $key key to access to the session variable. To access to $_SESSION['a']['b'] $key must be ['a', 'b']
	 * @param $value
	 */
	public static function Set($key, $value): void
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
		if (session_status() !== PHP_SESSION_ACTIVE) {
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
	public static function Unset($key): void
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
			if (session_status() !== PHP_SESSION_ACTIVE) {
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
	 * Unset all session variables, no matter if they were set by iTop or not
	 *
	 * @return void
	 * @since 3.3.0 N°9625
	 */
	public static function UnsetAll(): void
	{
		foreach (self::ListVariables() as $sKey) {
			self::Unset($sKey);
		}
	}

	/**
	 * @param string|array $key key to access to the session variable. To access to $_SESSION['a']['b'] $key must be ['a', 'b']
	 * @param $default
	 *
	 * @return mixed
	 */
	public static function Get($key, $default = null): mixed
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
	 * @return bool|string
	 */
	public static function GetLog(): string
	{
		return print_r($_SESSION, true);
	}

	public static function IsInitialized(): bool
	{
		return session_status() === PHP_SESSION_ACTIVE || headers_sent();
	}
}

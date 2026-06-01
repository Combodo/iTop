<?php

namespace Combodo\iTop\Service\Startup;

use Combodo\iTop\Application\Helper\Session;
use CoreException;
use IssueLog;

class StartupService
{
	/**
	 * @param string|null $sSwitchEnv
	 * @param bool $bAllowCache
	 *
	 * @return string
	 * @throws CoreException
	 */
	public static function SetItopEnvironment(?string $sSwitchEnv, bool &$bAllowCache): string
	{
		if (static::IsBuildEnvironment($sSwitchEnv)) {
			$oException = new CoreException("Switching to environment '$sSwitchEnv' is not allowed since it is a build environment");
			IssueLog::Exception("Trying to switch to environment '$sSwitchEnv' is not allowed since it is a build environment", $oException);
			throw $oException;
		}

		if (
			($sSwitchEnv != null)
			&& file_exists(APPCONF.$sSwitchEnv.'/'.ITOP_CONFIG_FILE)
			&& (Session::Get('itop_env') !== $sSwitchEnv)
		) {
			Session::Set('itop_env', $sSwitchEnv);
			$sEnv = $sSwitchEnv;
			$bAllowCache = false;
			// Reset the opcache since otherwise the PHP "model" files may still be cached !!
			if (function_exists('opcache_reset')) {
				// Zend opcode cache
				opcache_reset();
			}
			if (function_exists('apc_clear_cache')) {
				// APC(u) cache
				apc_clear_cache();
			}
			// TODO: reset the credentials as well ??
		} elseif (Session::IsSet('itop_env')) {
			$sEnv = Session::Get('itop_env');
		} else {
			$sEnv = ITOP_DEFAULT_ENV;
			Session::Set('itop_env', ITOP_DEFAULT_ENV);
		}

		return $sEnv;
	}

	public static function IsBuildEnvironment(?string $sEnv): bool
	{
		return $sEnv != null && str_ends_with($sEnv, '-build');
	}
}

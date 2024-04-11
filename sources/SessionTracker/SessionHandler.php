<?php

namespace Combodo\iTop\SessionTracker;

use Combodo\iTop\Application\Helper\Session;
use ContextTag;
use Exception;
use IssueLog;
use ReturnTypeWillChange;
use UserRights;
use utils;

/**
 * Class SessionHandler:
 * defaut PHP SessionHandler already relies on files that are accessible by iTop.
 * this new iTop SessionHandler creates additional session files that are located under iTop folders.
 * these new session files are meant to monitor the application and contain additional data:
 * - current user id
 * - context
 * - login_mode
 * - session creation timestamp
 *
 * @author Olivier Dain <olivier.dain@combodo.com>
 * @package Combodo\iTop\SessionTracker
 * @since 3.1.1 3.2.0 N°6901
 */
class SessionHandler extends \SessionHandler
{
	/**
	 * @param string $session_id
	 *
	 * @return bool
	 */
	#[ReturnTypeWillChange]
	public function destroy($session_id) : bool
	{
		IssueLog::Debug("Destroy PHP session", \LogChannels::SESSIONTRACKER, [
			'session_id' => $session_id,
		]);
		$bRes = parent::destroy($session_id);

		if ($bRes) {
			$this->unlink_session_file($session_id);
		}

		return $bRes;
	}

	/**
	 * @param int $max_lifetime
	 */
	#[ReturnTypeWillChange]
	public function gc($max_lifetime) : bool
	{
		IssueLog::Debug("Run PHP sessions garbage collector", \LogChannels::SESSIONTRACKER, [
			'max_lifetime' => $max_lifetime,
		]);
		$iRes = parent::gc($max_lifetime);
		$this->gc_with_time_limit($max_lifetime);
		return $iRes;
	}

	/**
	 * @param string $save_path
	 * @param string $session_name
	 */
	#[ReturnTypeWillChange]
	public function open($save_path, $session_name) : bool
	{
		$bRes = parent::open($save_path, $session_name);

		$session_id = session_id();
		IssueLog::Debug("Open PHP session", \LogChannels::SESSIONTRACKER, [
			'session_id' => $session_id,
		]);

		if ($bRes) {
			$this->touch_session_file($session_id);
		}

		return $bRes;
	}

	/**
	 * @param string $session_id
	 * @param string $data
	 *
	 * @return bool
	 */
	#[ReturnTypeWillChange]
	public function write($session_id, $data) : bool
	{
		$bRes = parent::write($session_id, $data);

		IssueLog::Debug("Write PHP session", \LogChannels::SESSIONTRACKER, [
			'session_id' => $session_id,
			'data' => $data,
		]);

		if ($bRes) {
			$this->touch_session_file($session_id);
		}

		return $bRes;
	}

	public static function session_set_save_handler() : void
	{
		if (false === utils::GetConfig()->Get('sessions_tracking.enabled')){
			//feature disabled
			return;
		}

		$sessionhandler = new SessionHandler();
		session_set_save_handler($sessionhandler, true);

		$iThreshold = utils::GetConfig()->Get('sessions_tracking.gc_threshold');
		$iThreshold = min(100, $iThreshold);
		$iThreshold = max(1, $iThreshold);
		if ((100 != $iThreshold) && (rand(1, 100) > $iThreshold)) {
			return;
		}

		$iMaxLifetime = ini_get('session.gc_maxlifetime') ?? 60;
		$iMaxDurationInSeconds = utils::GetConfig()->Get('sessions_tracking.gc_duration_in_seconds');
		$sessionhandler->gc_with_time_limit($iMaxLifetime, time() + $iMaxDurationInSeconds);
	}

	private function generate_session_content(?string $sPreviousFileVersionContent) : ?string
	{
		try {
			$sUserId = UserRights::GetUserId();
			if (null === $sUserId) {
				return null;
			}

			// Default value in case of
			// - First time file creation
			// - Data corruption (not a json / not an array / no previous creation_time key)
			$iCreationTime = time();

			if (! is_null($sPreviousFileVersionContent)) {
				$aJson = json_decode($sPreviousFileVersionContent, true);
				if (is_array($aJson) && array_key_exists('creation_time', $aJson)) {
					$iCreationTime = $aJson['creation_time'];
				}
			}

			return json_encode (
				[
					'login_mode' => Session::Get('login_mode'),
					'user_id' => $sUserId,
					'creation_time' => $iCreationTime,
					'context' => implode('|', ContextTag::GetStack())
				]
			);
		} catch(Exception $e) {

		}

		return null;
	}

	private function get_file_path($session_id) : string
	{
		return utils::GetDataPath() . "sessions/session_$session_id";
	}

	private function touch_session_file($session_id) : ?string
	{
		if (strlen($session_id) == 0) {
			return null;
		}

		clearstatcache();
		if (! is_dir(utils::GetDataPath() . "sessions")) {
			@mkdir(utils::GetDataPath() . "sessions");
		}

		$sFilePath = $this->get_file_path($session_id);

		$sPreviousFileVersionContent = null;
		if (is_file($sFilePath)) {
			$sPreviousFileVersionContent = file_get_contents($sFilePath);
		}
		$sNewContent = $this->generate_session_content($sPreviousFileVersionContent);
		if (is_null($sNewContent) || ($sPreviousFileVersionContent === $sNewContent)) {
			@touch($sFilePath);
		} else {
			file_put_contents($sFilePath, $sNewContent);
		}

		return $sFilePath;
	}

	private function unlink_session_file($session_id)
	{
		$sFilePath = $this->get_file_path($session_id);
		if (is_file($sFilePath)) {
			@unlink($sFilePath);
		}
	}

	/**
	 * @param int $max_lifetime
	 * @param int $iTimeLimit Unix timestamp of time limit not to exceed. -1 for no limit.
	 *
	 * @return int
	 */
	public function gc_with_time_limit(int $max_lifetime, int $iTimeLimit = -1) : int
	{
		$aFiles = $this->list_session_files();
		$iProcessed = 0;
		$now = time();

		foreach ($aFiles as $sFile) {
			if ($now - filemtime($sFile) > $max_lifetime) {
				@unlink($sFile);
				$iProcessed++;
			}

			if (-1 !== $iTimeLimit && time() > $iTimeLimit) {
				//cleanup processing has to stop after $iTimeLimit
				break;
			}
		}

		return $iProcessed;
	}

	public function list_session_files() : array
	{
		clearstatcache();
		if (! is_dir(utils::GetDataPath() . "sessions")) {
			@mkdir(utils::GetDataPath() . "sessions");
		}

		return glob(utils::GetDataPath() . "sessions/session_*");
	}
}

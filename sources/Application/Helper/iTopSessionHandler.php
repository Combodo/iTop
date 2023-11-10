<?php

namespace Combodo\iTop\Application\Helper;

use ContextTag;
use Exception;
use IssueLog;
use UserRights;

/**
 * Class iTopSessionGarbageCollector
 *
 * @author Olivier Dain <olivier.dain@combodo.com>
 * @package Combodo\iTop\Application\Helper
 * @since 3.1.1 3.2.0
 */
class iTopSessionHandler extends \SessionHandler
{
	public static function session_set_save_handler() : void
	{
		session_set_save_handler(new iTopSessionHandler(), true);
	}

	private function generate_session_content(?string $sPreviousFileVersionContent) : ?string
	{
		try {
			$sUserId = UserRights::GetUserId();
			if (is_null($sUserId)) {
				return null;
			}

			// Default value in case of
			// - First time file creation
			// - Data corruption
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
					'context' => implode(":", ContextTag::GetStack())
				]
			);
		} catch(Exception $e) {

		}

		return null;
	}

	private function get_file_path($session_id) : string
	{
		return APPROOT."/data/session/session_$session_id";
	}

	private function touch_session_file($session_id) : ?string
	{
		if (empty($session_id)) {
			return null;
		}

		clearstatcache();
		if (! is_dir(APPROOT."/data/session")) {
			@mkdir(APPROOT."/data/session");
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

	public function destroy($session_id)
	{
		IssueLog::Debug("destroy($session_id)");
		$bRes = parent::destroy($session_id);

		if ($bRes) {
			$this->unlink_session_file($session_id);
		}

		return $bRes;
	}

	public function gc($max_lifetime)
	{
		IssueLog::Debug("gc($max_lifetime)");
		$iRes = parent::gc($max_lifetime);
		$this->gc_with_time_limit($max_lifetime);
		return $iRes;
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
			if (0 === filesize($sFile) // Unauthentified sessions: immediate cleanup
				|| $now - filemtime($sFile) > $max_lifetime) {
				@unlink($sFile);
				$iProcessed++;
			}

			if (-1 !== $iTimeLimit && time() > $iTimeLimit) {
				break;
			}
		}

		return $iProcessed;
	}

	public function list_session_files() : array
	{
		clearstatcache();
		if (! is_dir(APPROOT."/data/session")) {
			@mkdir(APPROOT."/data/session");
		}

		return glob(APPROOT."/data/session/session_**");
	}

	public function open($save_path, $session_name)
	{
		$bRes = parent::open($save_path, $session_name);

		$session_id = session_id();
		IssueLog::Debug("open($session_id)");

		if ($bRes) {
			$this->touch_session_file($session_id);
		}

		return $bRes;
	}

	public function write($session_id, $data)
	{
		$bRes = parent::write($session_id, $data);

		IssueLog::Debug("write($session_id)");
		if ($bRes) {
				$this->touch_session_file($session_id);
		}

		return $bRes;
	}
}

<?php

namespace Combodo\iTop\Application\Helper;

class iTopSessionHandler extends \SessionHandler {
	public static function session_set_save_handler() : void {
		session_set_save_handler(new iTopSessionHandler(), true);
	}

	private function GenerateSessionContent() : ?string {
		try {
			$sUserId = \UserRights::GetUserId();
			if (! is_null($sUserId)){
				return json_encode(
					[
						'login_mode' => Session::Get('login_mode'),
						'user_id' => $sUserId,
						'context' => implode(":", \ContextTag::GetStack())
					]
				);
			}
		}catch(\Exception $e){}

		return null;
	}
	private function GetFilePath($session_id){
		return APPROOT."/data/session/session_$session_id";
	}

	private function touchSessionFile($session_id){
		clearstatcache();
		if (! is_dir(APPROOT."/data/session")){
			@mkdir(APPROOT."/data/session");
		}
		$sFilePath = $this->GetFilePath($session_id);

		$sSessionData = $this->GenerateSessionContent();

		if (is_null($sSessionData)){
			@touch($sFilePath);
		} else {
			file_put_contents($sFilePath, $sSessionData);
		}
	}

	private function unlinkSessionFile($session_id){
		$sFilePath = $this->GetFilePath($session_id);
		if (is_file($sFilePath)){
			@unlink($sFilePath);
		}
	}

	public function destroy($session_id) {
		\IssueLog::Debug("destroy($session_id)");
		$bRes = parent::destroy($session_id);

		if ($bRes){
			$this->unlinkSessionFile($session_id);
		}

		return $bRes;
	}

	public function gc($max_lifetime) {
		\IssueLog::Debug("gc($max_lifetime)");
		$iRes = parent::gc($max_lifetime);
		$this->gcWithTimeLimit($max_lifetime);
		return $iRes;
	}

	/**
	 * @param int $max_lifetime
	 * @param int $iTimeLimit
	 *
	 * @return int
	 */
	public function gcWithTimeLimit(int $max_lifetime, int $iTimeLimit=-1) : int {
		$aFiles = $this->ListSessionFiles();
		$iProcessed = 0;
		$now = time();

		foreach ($aFiles as $sFile){
			if (0 === filesize($sFile) //unauthentified sessions: immediate cleanup
				|| $now - filemtime($sFile) > $max_lifetime){
				@unlink($sFile);
				$iProcessed++;
			}

			if (-1 !== $iTimeLimit && time() > $iTimeLimit){
				break;
			}
		}

		return $iProcessed;
	}

	public function ListSessionFiles() : array {
		clearstatcache();
		if (! is_dir(APPROOT."/data/session")){
			@mkdir(APPROOT."/data/session");
		}

		return glob(APPROOT."/data/session/session_**");
	}

	public function open($save_path, $session_name) {
		$bRes = parent::open($save_path, $session_name);

		$session_id = session_id();
		\IssueLog::Debug("open($session_id)");

		if ($bRes){
			$this->touchSessionFile($session_id);
		}

		return $bRes;
	}

	public function write($session_id, $data){
		$bRes = parent::write($session_id, $data);

		\IssueLog::Debug("write($session_id)");
		if ($bRes){
				$this->touchSessionFile($session_id);
		}

		return $bRes;
	}
}

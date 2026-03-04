<?php

class CronService
{
	public const HELP_MESSAGE = "php cron.php --auth_user=<login> --auth_pwd=<password> [--param_file=<file>] [--verbose=1] [--debug=1] [--status_only=1]";

	private static CronService $oInstance;

	//used for test only
	private ?iTopMutex $oMutex = null;

	protected function __construct()
	{
	}

	final public static function GetInstance(): CronService
	{
		if (!isset(self::$oInstance)) {
			self::$oInstance = new CronService();
		}

		return self::$oInstance;
	}

	final public static function SetInstance(?CronService $oInstance): void
	{
		self::$oInstance = $oInstance;
	}

	private function GetMutex(): iTopMutex
	{
		if (! is_null($this->oMutex)) {
			return $this->oMutex;
		}

		return new iTopMutex('cron');
	}

	private function IsMutexLocked(): bool
	{
		return $this->GetMutex()->IsLocked();
	}

	public function IsStarted(string $sErrorFile): bool
	{
		$aLines = utils::ReadTail($sErrorFile);
		$sLine = array_shift($aLines);
		return (preg_match('/Starting: (.*)$/', $sLine));
	}

	public function IsStopped(string $sErrorFile): bool
	{
		$aLines = utils::ReadTail($sErrorFile);
		$sLine = array_shift($aLines);
		return (preg_match('/Exiting: (.*)$/', $sLine));
	}

	public function IsFailed(string $sLogFile): bool
	{
		return ! is_null($this->GetErrorMessage($sLogFile));
	}

	public function GetErrorMessage(string $sLogFile): ?string
	{
		$aLines = utils::ReadTail($sLogFile);
		$sLine = array_shift($aLines);
		if (preg_match('/ERROR: (.*)$/', $sLine, $aMatches)) {
			return $aMatches[1];
		}

		if (preg_match('/Exiting: (.*)$/', $sLine, $aMatches)) {
			//stopped
			return null;
		}

		$aStoppedMessages = [
			'Already running',
			'Access restricted to administrators',
			'A maintenance is ongoing',
			'Maintenance detected',
			'iTop is not yet installed',
		];
		foreach ($aStoppedMessages as $sMsg) {
			if (preg_match("/$sMsg/", $sLine, $aMatches)) {
				return $sLine;
			}
		}

		if (false !== strpos($sLine, self::HELP_MESSAGE)) {
			$aLines = utils::ReadTail($sLogFile, 5);
			foreach ($aLines as $sLine) {
				if (preg_match('/ERROR: (.*)$/', $sLine, $aMatches)) {
					return $aMatches[1];
				}
			}
		}

		return null;
	}
}

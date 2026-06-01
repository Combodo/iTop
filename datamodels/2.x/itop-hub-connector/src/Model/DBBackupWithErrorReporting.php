<?php

namespace Combodo\iTop\HubConnector\Model;

use DBBackup;
use IssueLog;

/**
 * Overload of DBBackup to handle logging
 */
class DBBackupWithErrorReporting extends DBBackup
{
	protected $aInfos = [];

	protected $aErrors = [];

	protected function LogInfo($sMsg)
	{
		$this->aInfos[] = $sMsg;
	}

	protected function LogError($sMsg)
	{
		IssueLog::Error($sMsg);
		$this->aErrors[] = $sMsg;
	}

	public function GetInfos(): array
	{
		return $this->aInfos;
	}

	public function GetErrors(): array
	{
		return $this->aErrors;
	}
}

<?php
/**
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Test\UnitTest\Core;


use CMDBSource;
use Exception;
use IssueLog;
use MySQLException;
use utils;

class DeadLockInjection
{
	private $bMustFail = false;
	private $iRequestCount = 0;
	private $iFailAt = 0;
	private $bShowRequest = true;

	/**
	 *
	 * @param int $iFailAt
	 */
	public function SetFailAt($iFailAt)
	{
		$this->bMustFail = true;
		$this->iRequestCount = 0;
		$this->iFailAt = $iFailAt;
		$this->bShowRequest = true;
	}

	/**
	 * @param bool $bShowRequest
	 */
	public function SetShowRequest($bShowRequest)
	{
		$this->bShowRequest = $bShowRequest;
	}


	public function query($sSQL)
	{
		if (utils::StartsWith($sSQL, "SELECT")) {
			return;
		}
		if ($this->bShowRequest) {
			$sShortSQL = substr(preg_replace("/\s+/", " ", substr($sSQL, 0, 180)), 0, 150);
			echo "$sShortSQL\n";
		}
		if (CMDBSource::IsInsideTransaction() && $this->bMustFail) {
			$this->iRequestCount++;
			if ($this->iRequestCount == $this->iFailAt) {
				echo "Generating a FAKE DEADLOCK\n";
				IssueLog::Trace("Generating a FAKE DEADLOCK", 'cmdbsource');
				throw new MySQLException("FAKE DEADLOCK", [], new Exception("FAKE DEADLOCK", 1213));
			}
		}
	}
}
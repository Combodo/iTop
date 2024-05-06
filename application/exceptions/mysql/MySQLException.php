<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

class MySQLException extends CoreException
{
	/**
	 * MySQLException constructor.
	 *
	 * @param string $sIssue
	 * @param array $aContext
	 * @param \Exception $oException
	 * @param \mysqli $oMysqli to use when working with a custom mysqli instance
	 */
	public function __construct($sIssue, $aContext, $oException = null, $oMysqli = null)
	{
		if ($oException != null) {
			$aContext['mysql_errno'] = $oException->getCode();
			$this->code = $oException->getCode();
			$aContext['mysql_error'] = $oException->getMessage();
		} else if ($oMysqli != null) {
			$aContext['mysql_errno'] = $oMysqli->errno;
			$this->code = $oMysqli->errno;
			$aContext['mysql_error'] = $oMysqli->error;
		} else {
			$aContext['mysql_errno'] = CMDBSource::GetErrNo();
			$this->code = CMDBSource::GetErrNo();
			$aContext['mysql_error'] = CMDBSource::GetError();
		}
		parent::__construct($sIssue, $aContext);
		//if is connection error, don't log the default message with password in
		if (mysqli_connect_errno()) {
			error_log($this->message);
			error_reporting(0);
		}
	}
}
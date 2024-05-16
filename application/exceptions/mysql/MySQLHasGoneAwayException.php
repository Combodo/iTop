<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

/**
 * Class MySQLHasGoneAwayException
 *
 * @see https://dev.mysql.com/doc/refman/5.7/en/gone-away.html
 * @since 2.5.0 N°1195
 */
class MySQLHasGoneAwayException extends MySQLException
{
	/**
	 * can not be a constant before PHP 5.6 (http://php.net/manual/fr/language.oop5.constants.php)
	 *
	 * @return int[]
	 */
	public static function getErrorCodes()
	{
		return array(
			2006,
			2013,
		);
	}

	public function __construct($sIssue, $aContext)
	{
		parent::__construct($sIssue, $aContext, null);
	}
}
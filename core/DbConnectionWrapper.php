<?php
/*
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Core;

use mysqli;

/**
 * mysqli object is really hard to mock as it contains lots of attributes & methods ! Thought we need to mock it to test transactions !
 *
 * To solve this, a new attribute exists and is only used in specific use cases, so there are just few things to mock.
 *
 * This object adds more readability than previous model with 2 attributes in {@see CMDBSource}.
 *
 * @used-by \CMDBSource
 *
 * @since 2.7.5 N°3513 new mockable {@see mysqli} object in {@see CMDBSource}
 * @since 3.0.0 N°4325 add this object to avoid confusions and document the wanted behavior
 */
class DbConnectionWrapper
{
	/** @var mysqli */
	protected static $oDbCnxStandard;

	/**
	 * @var mysqli
	 * @used-by \Combodo\iTop\Test\UnitTest\Core\TransactionsTest
	 */
	protected static $oDbCnxMockable;

	public static function GetDbConnection(bool $bIsMockable = false): ?mysqli
	{
		if ($bIsMockable) {
			return self::$oDbCnxMockable;
		}

		return self::$oDbCnxStandard;
	}

	public static function SetDbConnection(mysqli $oMysqli, bool $bIsMockable = false): void
	{
		self::$oDbCnxMockable = $oMysqli;
		if ($bIsMockable) {
			return;
		}

		self::$oDbCnxStandard = $oMysqli;
	}
}
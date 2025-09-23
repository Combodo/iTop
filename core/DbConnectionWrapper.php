<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
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
 * @since 3.0.0 N°4325 Object creation
 *          This wrapper handles the 2 {@mysqli myqsli} attributes that were previously in {@see CMDBSource}
 *          To allow testing we added a second mysqli object (N°3513 in 2.7.5) and code became a bit confusing :/
 *          With this wrapper everything is in the same place, and we can express the intention more clearly !
 */
class DbConnectionWrapper
{
	/** @var mysqli */
	protected static $oDbCnxStandard;

	/**
	 * Can contain a genuine mysqli object, or a mock that would emulate {@see mysqli::query()}
	 *
	 * @var mysqli
	 * @used-by \Combodo\iTop\Test\UnitTest\Core\TransactionsTest
	 */
	protected static $oDbCnxMockableForQuery;

	/**
	 * @param bool $bIsForQuery set to true if using {@see mysqli::query()}
	 *
	 * @return \mysqli|null
	 */
	public static function GetDbConnection(bool $bIsForQuery = false): ?mysqli
	{
		if ($bIsForQuery) {
			return static::$oDbCnxMockableForQuery;
		}

		return static::$oDbCnxStandard;
	}

	public static function SetDbConnection(?mysqli $oMysqli): void
	{
		static::$oDbCnxStandard = $oMysqli;
		static::SetDbConnectionMockForQuery($oMysqli);
	}

	/**
	 * Use this to register a mock that will handle {@see mysqli::query()}
	 *
	 * @param \mysqli|null $oMysqli
	 * @since 3.0.4 3.1.1 3.2.0 Param $oMysqli becomes nullable
	 * @since 3.1.0-4 N°6848 backport of restoring cnx on null parameter value
	 */
	public static function SetDbConnectionMockForQuery(?mysqli $oMysqli = null): void
	{
		if (is_null($oMysqli)) {
			// Reset to standard connection
			static::$oDbCnxMockableForQuery = static::$oDbCnxStandard;
		}
		else {
			static::$oDbCnxMockableForQuery = $oMysqli;
		}
	}
}
<?php
// Copyright (C) 2010-2024 Combodo SAS
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify	
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>
use Combodo\iTop\Application\Helper\Session;

/**
 * This class records the pending "transactions" corresponding to forms that have not been
 * submitted yet, in order to prevent double submissions. When created a transaction remains valid
 * until the user's session expires. This class is actually a wrapper to the underlying implementation
 * which choice is configured via the parameter 'transaction_storage'
 *  
 * @package     iTop
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
class privUITransaction
{
	/**
	 * Create a new transaction id, store it in the session and return its id
	 * @param void
	 * @return int The identifier of the new transaction
	 */
	public static function GetNewTransactionId()
	{
		$bTransactionsEnabled = MetaModel::GetConfig()->Get('transactions_enabled');
		if (!$bTransactionsEnabled)
		{
			return 'notransactions'; // Any value will do
		}
		$sClass = 'privUITransaction'.MetaModel::GetConfig()->Get('transaction_storage');
		if (!class_exists($sClass, false))
		{
			IssueLog::Error("Incorrect value '".MetaModel::GetConfig()->Get('transaction_storage')."' for 'transaction_storage', the class '$sClass' does not exists. Using privUITransactionSession instead for storing sessions.");
			$sClass = 'privUITransactionSession';
		}

		return (string)$sClass::GetNewTransactionId();
	}

	/**
	 * Check whether a transaction is valid or not and (optionally) remove the valid transaction from
	 * the session so that another call to IsTransactionValid for the same transaction id
	 * will return false
	 * @param int $id Identifier of the transaction, as returned by GetNewTransactionId
	 * @param bool $bRemoveTransaction True if the transaction must be removed
	 * @return bool True if the transaction is valid, false otherwise
	 */
	public static function IsTransactionValid($id, $bRemoveTransaction = true)
	{
		$bTransactionsEnabled = MetaModel::GetConfig()->Get('transactions_enabled');
		if (!$bTransactionsEnabled)
		{
			return true; // All values are valid
		}
		$sClass = 'privUITransaction'.MetaModel::GetConfig()->Get('transaction_storage');
		if (!class_exists($sClass, false))
		{
			$sClass = 'privUITransactionSession';
		}
		
		return $sClass::IsTransactionValid($id, $bRemoveTransaction);
	}

	/**
	 * Removes the transaction specified by its id
	 * @param int $id The Identifier (as returned by GetNewTranscationId) of the transaction to be removed.
	 * @return void
	 */
	public static function RemoveTransaction($id)
	{
		$bTransactionsEnabled = MetaModel::GetConfig()->Get('transactions_enabled');
		if (!$bTransactionsEnabled)
		{
			return; // Nothing to do
		}
		$sClass = 'privUITransaction'.MetaModel::GetConfig()->Get('transaction_storage');
		if (!class_exists($sClass, false))
		{
			$sClass = 'privUITransactionSession';
		}
		
		$sClass::RemoveTransaction($id);
	}
}

/**
 * The original mechanism for storing transaction information as an array in the $_SESSION variable
 *
 * Warning, since 2.6.0 the session is regenerated on each login (see PR #20) !
 * Also, we saw some problems when using memcached as the PHP session implementation (see N°1835)
 *
 * @see \Combodo\iTop\Application\Helper\Session
 */
class privUITransactionSession
{
	/**
	 * Create a new transaction id, store it in the session and return its id
	 * @param void
	 * @return int The identifier of the new transaction
	 */
	public static function GetNewTransactionId()
	{
		if (!Session::IsSet('transactions'))
		{
			Session::Set('transactions', []);
		}
		// Strictly speaking, the two lines below should be grouped together
		// by a critical section
		// sem_acquire($rSemIdentified);
		$id = static::GetUserPrefix() . str_replace(array('.', ' '), '', microtime());
		Session::Set(['transactions', $id], true);
		// sem_release($rSemIdentified);
		
		return (string)$id;
	}

	/**
	 * Check whether a transaction is valid or not and (optionally) remove the valid transaction from
	 * the session so that another call to IsTransactionValid for the same transaction id
	 * will return false
	 * @param int $id Identifier of the transaction, as returned by GetNewTransactionId
	 * @param bool $bRemoveTransaction True if the transaction must be removed
	 * @return bool True if the transaction is valid, false otherwise
	 */	
	public static function IsTransactionValid($id, $bRemoveTransaction = true)
	{
		$bResult = false;
		if (Session::IsSet('transactions'))
		{
			// Strictly speaking, the eight lines below should be grouped together
			// inside the same critical section as above
			// sem_acquire($rSemIdentified);
			if (Session::IsSet(['transactions', $id]))
			{
				$bResult = true;
				if ($bRemoveTransaction)
				{
					Session::Unset(['transactions', $id]);
				}
			}
			// sem_release($rSemIdentified);
		}
		return $bResult;
	}
	
	/**
	 * Removes the transaction specified by its id
	 * @param int $id The Identifier (as returned by GetNewTranscationId) of the transaction to be removed.
	 * @return void
	 */
	public static function RemoveTransaction($id)
	{
		if (Session::IsSet('transactions'))
		{
			// Strictly speaking, the three lines below should be grouped together
			// inside the same critical section as above
			// sem_acquire($rSemIdentified);
			if (Session::IsSet(['transactions', $id]))
			{
				Session::Unset(['transactions', $id]);
			}
			// sem_release($rSemIdentified);
		}		
	}

	/**
	 * Returns a string to prefix transaction ID with info from the current user.
	 *
	 * @return string
	 */
	protected static function GetUserPrefix()
	{
		$sPrefix = 'u'.UserRights::GetUserId();
		return $sPrefix.'-';
	}
}

/**
 * An alternate implementation for storing the transactions as temporary files
 * Useful when using an in-memory storage for the session which do not
 * guarantee mutual exclusion for writing
 */
class privUITransactionFile
{
	/** @var int Value to use when no user logged */
	const UNAUTHENTICATED_USER_ID = -666;

	/**
	 * @return int current user id, or {@see self::UNAUTHENTICATED_USER_ID} if no user logged
	 *
	 * @since 2.6.5 2.7.6 3.0.0 N°4289 method creation
	 */
	private static function GetCurrentUserId()
	{
		$iCurrentUserId = UserRights::GetConnectedUserId();
		if ('' === $iCurrentUserId) {
			$iCurrentUserId = static::UNAUTHENTICATED_USER_ID;
		}

		return $iCurrentUserId;
	}

	/**
	 * Create a new transaction id, store it in the session and return its id
	 *
	 * @param void
	 *
	 * @return int The new transaction identifier
	 *
	 * @throws \SecurityException
	 * @throws \Exception
	 *
	 * @since 2.6.5 2.7.6 3.0.0 security hardening + throws SecurityException if no user logged
	 */
	public static function GetNewTransactionId()
	{
		if (!is_dir(APPROOT.'data/transactions'))
		{
			if (!is_writable(APPROOT.'data'))
			{
				throw new Exception('The directory "'.APPROOT.'data" must be writable to the application.');
			}
			// condition avoids race condition N°2345
			// See https://github.com/kalessil/phpinspectionsea/blob/master/docs/probable-bugs.md#mkdir-race-condition
			if (!mkdir($concurrentDirectory = APPROOT.'data/transactions') && !is_dir($concurrentDirectory))
			{
				throw new Exception('Failed to create the directory "'.APPROOT.'data/transactions". Ajust the rights on the parent directory or let an administrator create the transactions directory and give the web sever enough rights to write into it.');
			}
		}

		if (!is_writable(APPROOT.'data/transactions'))
		{
			throw new Exception('The directory "'.APPROOT.'data/transactions" must be writable to the application.');
		}

		$iCurrentUserId = static::GetCurrentUserId();

		self::CleanupOldTransactions();

		$sTransactionIdFullPath = tempnam(APPROOT.'data/transactions', static::GetUserPrefix());
		file_put_contents($sTransactionIdFullPath, $iCurrentUserId, LOCK_EX);

		$sTransactionIdFileName = basename($sTransactionIdFullPath);
		self::Info('GetNewTransactionId: Created transaction: '.$sTransactionIdFileName);

		return $sTransactionIdFileName;
	}

	/**
	 * Check whether a transaction is valid or not and (optionally) remove the valid transaction from
	 * the session so that another call to IsTransactionValid for the same transaction id
	 * will return false
	 *
	 * @param int $id Identifier of the transaction, as returned by GetNewTransactionId
	 * @param bool $bRemoveTransaction True if the transaction must be removed
	 *
	 * @return bool True if the transaction is valid, false otherwise
	 *
	 * @since 2.6.5 2.7.6 3.0.0 N°4289 security hardening
	 */
	public static function IsTransactionValid($id, $bRemoveTransaction = true)
	{
		// Constraint the transaction file within APPROOT.'data/transactions'
		$sTransactionDir = realpath(APPROOT.'data/transactions');
		$sFilepath = utils::RealPath($sTransactionDir.'/'.$id, $sTransactionDir);
		if (($sFilepath === false) || (strlen($sTransactionDir) == strlen($sFilepath)))
		{
			return false;
		}

		clearstatcache(true, $sFilepath);
		$bResult = file_exists($sFilepath);

		if (false === $bResult) {
			self::Info("IsTransactionValid: Transaction '$id' not found. Pending transactions:\n".implode("\n", self::GetPendingTransactions()));
			return false;
		}

		$iCurrentUserId = static::GetCurrentUserId();
		$sTransactionIdUserId = file_get_contents($sFilepath);
		if ($iCurrentUserId != $sTransactionIdUserId) {
			self::Info("IsTransactionValid: Transaction '$id' not existing for current user. Pending transactions:\n".implode("\n", self::GetPendingTransactions()));
			return false;
		}

		if ($bRemoveTransaction)
		{
			$bResult = @unlink($sFilepath);
			if (!$bResult)
			{
				self::Error('IsTransactionValid: FAILED to remove transaction '.$id);
			}
			else
			{
				self::Info('IsTransactionValid: OK. Removed transaction: '.$id);
			}
		}

		return $bResult;
	}

	/**
	 * Removes the transaction specified by its id
	 * @param int $id The Identifier (as returned by GetNewTransactionId) of the transaction to be removed.
	 * @return bool true if the token can be removed
	 *
	 * @since 2.6.5 2.7.6 3.0.0 N°4289 security hardening
	 */
	public static function RemoveTransaction($id)
	{
		/** @noinspection PhpRedundantOptionalArgumentInspection */
		$bResult = static::IsTransactionValid($id, true);
		if (false === $bResult) {
			self::Error("RemoveTransaction: Transaction '$id' is invalid. Pending transactions:\n"
				.implode("\n", self::GetPendingTransactions()));
			return false;
		}

		return true;
	}

	/**
	 * Cleanup old transactions which have been pending since more than 24 hours
	 * Use filemtime instead of filectime since filectime may be affected by operations on the directory (like changing the access rights)
	 */
	protected static function CleanupOldTransactions($sTransactionDir = null)
	{
		$iThreshold = (int) MetaModel::GetConfig()->Get('transactions_gc_threshold');
		$iThreshold = min(100, $iThreshold);
		$iThreshold = max(1, $iThreshold);
		if ((100 != $iThreshold) && (rand(1, 100) > $iThreshold)) {
			return;
		}

		clearstatcache();
		$iLimit = time() - 24*3600;
		$sPattern = $sTransactionDir ? "$sTransactionDir/*" : APPROOT.'data/transactions/*';
		$aTransactions = glob($sPattern);
		foreach($aTransactions as $sFileName)
		{
			if (filemtime($sFileName) < $iLimit)
			{
				@unlink($sFileName);
				self::Info('CleanupOldTransactions: Deleted transaction: '.$sFileName);
			}
		}
	}

	/**
	 * For debugging purposes: gets the pending transactions of the current user
	 * as an array, with the date of the creation of the transaction file
	 */
	protected static function GetPendingTransactions()
	{
		clearstatcache();
		$aResult = array();
		$aTransactions = glob(APPROOT.'data/transactions/'.self::GetUserPrefix().'*');
		foreach($aTransactions as $sFileName)
		{
			$aResult[] = date('Y-m-d H:i:s', filemtime($sFileName)).' - '.basename($sFileName);
		}
		sort($aResult);
		return $aResult;
	}

	/**
	 * Returns a prefix based on the user login instead of its ID for a better usage in tempnam()
	 *
	 * @inheritdoc
	 */
	protected static function GetUserPrefix()
	{
		$sPrefix = substr(UserRights::GetUser(), 0, 10);
		$sPrefix = preg_replace('/[^a-zA-Z0-9-_]/', '_', $sPrefix);
		return $sPrefix.'-';
	}

	protected static function Info($sText)
	{
		self::Write('Info | '.$sText);
	}

	protected static function Warning($sText)
	{
		self::Write('Warning | '.$sText);
	}
			
	protected static function Error($sText)
	{
		self::Write('Error | '.$sText);
	}

	protected static function IsLogEnabled() {
		$oConfig = MetaModel::GetConfig();
		if (is_null($oConfig)) {
			return false;
		}

		$bLogTransactions = $oConfig->Get('log_transactions');
		if (true === $bLogTransactions) {
			return true;
		}

		return false;
	}

	protected static function Write($sText)
	{
		if (false === static::IsLogEnabled()) {
			return;
		}

		$hLogFile = @fopen(APPROOT.'log/transactions.log', 'a');
		if ($hLogFile !== false) {
			flock($hLogFile, LOCK_EX);
			$sDate = date('Y-m-d H:i:s');
			fwrite($hLogFile, "$sDate | $sText\n");
			fflush($hLogFile);
			flock($hLogFile, LOCK_UN);
			fclose($hLogFile);
		}
	}
}

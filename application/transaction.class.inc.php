<?php
// Copyright (C) 2010-2015 Combodo SARL
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

/**
 * This class records the pending "transactions" corresponding to forms that have not been
 * submitted yet, in order to prevent double submissions. When created a transaction remains valid
 * until the user's session expires. This class is actually a wrapper to the underlying implementation
 * which choice is configured via the parameter 'transaction_storage'
 *  
 * @package     iTop
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
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
 * The original (and by default) mechanism for storing transaction information
 * as an array in the $_SESSION variable
 *
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
		if (!isset($_SESSION['transactions']))
		{
				$_SESSION['transactions'] = array();
		}
		// Strictly speaking, the two lines below should be grouped together
		// by a critical section
		// sem_acquire($rSemIdentified);
		$id = str_replace(array('.', ' '), '', microtime()); //1 + count($_SESSION['transactions']);
		$_SESSION['transactions'][$id] = true;
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
		if (isset($_SESSION['transactions']))
		{
			// Strictly speaking, the eight lines below should be grouped together
			// inside the same critical section as above
			// sem_acquire($rSemIdentified);
			if (isset($_SESSION['transactions'][$id]))
			{
				$bResult = true;
				if ($bRemoveTransaction)
				{
					unset($_SESSION['transactions'][$id]);
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
		if (isset($_SESSION['transactions']))
		{
			// Strictly speaking, the three lines below should be grouped together
			// inside the same critical section as above
			// sem_acquire($rSemIdentified);
			if (isset($_SESSION['transactions'][$id]))
			{
				unset($_SESSION['transactions'][$id]);
			}
			// sem_release($rSemIdentified);
		}		
	}
}

/**
 * An alternate implementation for storing the transactions as temporary files
 * Useful when using an in-memory storage for the session which do not
 * guarantee mutual exclusion for writing
 */
class privUITransactionFile
{
	/**
	 * Create a new transaction id, store it in the session and return its id
	 * @param void
	 * @return int The identifier of the new transaction
	 */
	public static function GetNewTransactionId()
	{
		if (!is_dir(APPROOT.'data/transactions'))
		{
			if (!is_writable(APPROOT.'data'))
			{
				throw new Exception('The directory "'.APPROOT.'data" must be writable to the application.');
			}
			if (!@mkdir(APPROOT.'data/transactions'))
			{
				throw new Exception('Failed to create the directory "'.APPROOT.'data/transactions". Ajust the rights on the parent directory or let an administrator create the transactions directory and give the web sever enough rights to write into it.');
			}
		}
		if (!is_writable(APPROOT.'data/transactions'))
		{
			throw new Exception('The directory "'.APPROOT.'data/transactions" must be writable to the application.');
		}
		self::CleanupOldTransactions();
		$id = basename(tempnam(APPROOT.'data/transactions', self::GetUserPrefix()));
		self::Info('GetNewTransactionId: Created transaction: '.$id);

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
		$sFilepath = APPROOT.'data/transactions/'.$id;
		clearstatcache(true, $sFilepath);
		$bResult = file_exists($sFilepath);
		if ($bResult)
		{
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
		}
		else
		{
			self::Info("IsTransactionValid: Transaction '$id' not found. Pending transactions for this user:\n".implode("\n", self::GetPendingTransactions()));
		}
		return $bResult;
	}

	/**
	 * Removes the transaction specified by its id
	 * @param int $id The Identifier (as returned by GetNewTransactionId) of the transaction to be removed.
	 * @return void
	 */
	public static function RemoveTransaction($id)
	{
		$bSuccess = true;
		$sFilepath = APPROOT.'data/transactions/'.$id;
		clearstatcache(true, $sFilepath);
		if(!file_exists($sFilepath))
		{
			$bSuccess = false;
			self::Error("RemoveTransaction: Transaction '$id' not found. Pending transactions for this user:\n".implode("\n", self::GetPendingTransactions()));
		}
		$bSuccess = @unlink($sFilepath);
		if (!$bSuccess)
		{
			self::Error('RemoveTransaction: FAILED to remove transaction '.$id);
		}
		else
		{
			self::Info('RemoveTransaction: OK '.$id);
		}
		return $bSuccess;
	}

	/**
	 * Cleanup old transactions which have been pending since more than 24 hours
	 * Use filemtime instead of filectime since filectime may be affected by operations on the directory (like changing the access rights)
	 */
	protected static function CleanupOldTransactions()
	{
		$iLimit = time() - 24*3600;
		clearstatcache();
		$aTransactions = glob(APPROOT.'data/transactions/*-*');
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
	
	protected static function Write($sText)
	{
		$bLogEnabled = MetaModel::GetConfig()->Get('log_transactions');
		if ($bLogEnabled)
		{
		$hLogFile = @fopen(APPROOT.'log/transactions.log', 'a');
		if ($hLogFile !== false)
		{
			flock($hLogFile, LOCK_EX);
			$sDate = date('Y-m-d H:i:s');
			fwrite($hLogFile, "$sDate | $sText\n");
			fflush($hLogFile);
			flock($hLogFile, LOCK_UN);
			fclose($hLogFile);
			}
		}
	}
}

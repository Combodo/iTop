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
		$id = basename(tempnam(APPROOT.'data/transactions', substr(UserRights::GetUser(), 0, 10).'-'));
		IssueLog::Info('GetNewTransactionId: Created transaction: '.$id);

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
		$bResult = file_exists(APPROOT.'data/transactions/'.$id);
		if ($bResult)
		{
			if ($bRemoveTransaction)
			{
				$bResult = @unlink(APPROOT.'data/transactions/'.$id);
				if (!$bSuccess)
				{
					IssueLog::Error('IsTransactionValid: FAILED to remove transaction '.$id);
				}
				else
				{
					IssueLog::Info('IsTransactionValid: Removed transaction: '.$id);
				}
			}
		}
		else
		{
			IssueLog::Info("IsTransactionValid: Transaction '$id' not found. Pending transactions for this user:\n".implode("\n", self::GetPendingTransactions()));
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
		if(!file_exists(APPROOT.'data/transactions/'.$id))
		{
			$bSuccess = false;
			IssueLog::Info("RemoveTransaction: Transaction '$id' not found. Pending transactions for this user:\n".implode("\n", self::GetPendingTransactions()));
		}
		$bSuccess = @unlink(APPROOT.'data/transactions/'.$id);
		if (!$bSuccess)
		{
			IssueLog::Error('RemoveTransaction: FAILED to remove transaction '.$id);
		}
		return $bSuccess;
	}

	/**
	 * Cleanup old transactions which have been pending since more than 24 hours
	 */
	protected static function CleanupOldTransactions()
	{
		$iLimit = time() - 24*3600;
		$aTransactions = glob(APPROOT.'data/transactions/*-*');
		foreach($aTransactions as $sFileName)
		{
			if (filectime($sFileName) < $iLimit)
			{
				@unlink($sFileName);
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
		$aTransactions = glob(APPROOT.'data/transactions/'.UserRights::GetUser().'-*');
		foreach($aTransactions as $sFileName)
		{
			$aResult[] = date('Y-m-d H:i:s', filectime($sFileName)).' - '.basename($sFileName);
		}
		sort($aResult);
		return $aResult;
	}

}


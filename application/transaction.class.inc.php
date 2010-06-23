<?php
// Copyright (C) 2010 Combodo SARL
//
//   This program is free software; you can redistribute it and/or modify
//   it under the terms of the GNU General Public License as published by
//   the Free Software Foundation; version 3 of the License.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of the GNU General Public License
//   along with this program; if not, write to the Free Software
//   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

/**
 * This class records the pending "transactions" corresponding to forms that have not been
 * submitted yet, in order to prevent double submissions. When created a transaction remains valid
 * until it is "used" by calling IsTransactionValid once, or until it
 * expires (delay = TRANSACTION_EXPIRATION_DELAY, defaults to 4 hours)
 * @package     iTop
 */
// How long a "transaction" is considered valid, i.e. when a form is submitted
// if the form dates back from too long a time, it is considered invalid. This is
// because since HTTP is not a connected protocol, we cannot know when a user disconnects
// from the application (maybe just by closing her browser), so we keep track - in the database - of all pending
// forms that have not yet been submitted. To limit this list we consider that after some time
// a "transaction" is no loger valid an gets purged from the table
define ('TRANSACTION_EXPIRATION_DELAY', 3600*4); // default: 4h

require_once('../core/dbobject.class.php');

class privUITransaction extends DBObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "gui",
			"key_type" => "autoincrement",
			"name_attcode" => "expiration_date",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_transaction",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_AddAttribute(new AttributeDateTime("expiration_date", array("allowed_values"=>null, "sql"=>"expiration_date", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('expiration_date')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('expiration_date')); // Attributes to be displayed for a list
	}
	/**
	 * Create a new transaction, store it in the database and return its id
	 * @param void
	 * @return int The identifier of the new transaction
	 */
	public static function GetNewTransactionId()
	{
		// First remove all the expired transactions...
		self::CleanupExpiredTransactions();
		$oTransaction = new privUITransaction();
		$sDate = date('Y-m-d H:i:s', time()+TRANSACTION_EXPIRATION_DELAY);
		$oTransaction->Set('expiration_date', $sDate); // 4 h delay by default
		$oTransaction->DBInsert();
		return sprintf("%d", $oTransaction->GetKey());
	}

	/**
	 * Check whether a transaction is valid or not and remove the valid transaction from
	 * the database so that another call to IsTransactionValid for the same transaction
	 * will return false
	 * @param int $id Identifier of the transaction, as returned by GetNewTransactionId
	 * @return bool True if the transaction is valid, false otherwise
	 */	
	public static function IsTransactionValid($id)
	{
		// First remove all the expired transactions...
		self::CleanupExpiredTransactions();
		// TO DO put a critical section around this part to be 100% safe...
		// sem_acquire(...)
		$bResult  = false;
		$oTransaction = MetaModel::GetObject('privUITransaction', $id, false /* MustBeFound */);
		if ($oTransaction)
		{
			$bResult = true;
			$oTransaction->DBDelete();
		}
		// sem_release(...)
		return $bResult;
	}

	/**
	 * Remove from the database all transactions that have expired
	 */
	protected static function CleanupExpiredTransactions()
	{
		$sQuery = 'SELECT privUITransaction WHERE expiration_date < NOW()';
		$oSearch = DBObjectSearch::FromOQL($sQuery);
		$oSet = new DBObjectSet($oSearch);
		while($oTransaction = $oSet->Fetch())
		{
			$oTransaction->DBDelete();
		}
	}

}
?>

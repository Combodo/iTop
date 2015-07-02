<?php
// Copyright (C) 2015 Combodo SARL
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
 * Mechanism to obtain an exclusive lock while editing an object
 *
 * @package     iTopORM
 */

/**
 * Persistent storage (in the database) for remembering that an object is locked 
 */
class iTopOwnershipToken extends DBObject
{
	public static function Init()
	{
		$aParams = array
		(
			'category' => 'application',
			'key_type' => 'autoincrement',
			'name_attcode' => array('obj_class', 'obj_key'),
			'state_attcode' => '',
			'reconc_keys' => array(''),
			'db_table' => 'priv_ownership_token',
			'db_key_field' => 'id',
			'db_finalclass_field' => '',
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeDateTime("acquired", array("allowed_values"=>null, "sql"=>'acquired', "default_value"=>'', "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeDateTime("last_seen", array("allowed_values"=>null, "sql"=>'last_seen', "default_value"=>'', "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("obj_class", array("allowed_values"=>null, "sql"=>'obj_class', "default_value"=>'', "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeInteger("obj_key", array("allowed_values"=>null, "sql"=>'obj_key', "default_value"=>'', "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("token", array("allowed_values"=>null, "sql"=>'token', "default_value"=>'', "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("user_id", array("targetclass"=>"User", "jointype"=> '', "allowed_values"=>null, "sql"=>"user_id", "is_null_allowed"=>true, "on_target_delete"=>DEL_SILENT, "depends_on"=>array())));				

		MetaModel::Init_SetZListItems('details', array ('obj_class', 'obj_key', 'last_seen', 'token'));
		MetaModel::Init_SetZListItems('standard_search', array ('obj_class', 'obj_key', 'last_seen', 'token'));
		MetaModel::Init_SetZListItems('list', array ('obj_class', 'obj_key', 'last_seen', 'token'));

	}
}

/**
 * Utility class to acquire/extend/release/kill an exclusive lock on a given persistent object,
 * for example to prevent concurrent edition of the same object.
 * Each lock has an expiration delay of 120 seconds (tunable via the configuration parameter 'concurrent_lock_expiration_delay')
 * A watchdog (called twice during this delay) is in charge of keeping the lock "alive" while an object is being edited.
 */
class iTopOwnershipLock
{
	protected $sObjClass;
	protected $iObjKey;
	protected $oToken;
	
	/**
	 * Acquires an exclusive lock on the specified DBObject. Once acquired, the lock is identified
	 * by a unique "token" string.
	 * @param string $sObjClass The class of the object for which to acquire the lock
	 * @param integer $iObjKey The identifier of the object for which to acquire the lock
	 * @return multitype:boolean iTopOwnershipLock Ambigous <boolean, string, DBObjectSet>
	 */
	public static function AcquireLock($sObjClass, $iObjKey)
	{
		$oMutex = new iTopMutex('lock_'.$sObjClass.'::'.$iObjKey);
		
		$oMutex->Lock();
		$oOwnershipLock = new iTopOwnershipLock($sObjClass, $iObjKey);
		$token = $oOwnershipLock->Acquire();
		$oMutex->Unlock();
		
		return array('success' => $token !== false, 'token' => $token, 'lock' => $oOwnershipLock, 'acquired' => $oOwnershipLock->oToken->Get('acquired'));
	}
	
	/**
	 * Extends the ownership lock or acquires it if none exists
	 * Returns a hash array with 3 elements:
	 * 'status': either true or false, tells if the lock is still owned
	 * 'owner': is status is false, the User object currently owning the lock
	 * 'operation': whether the lock was 'renewed' (i.e. the lock was valid, its duration has been extended) or 'acquired' (there was no valid lock for this object and a new one was created)
	 * @param string $sToken
	 * @return multitype:boolean string User
	 */
	public static function ExtendLock($sObjClass, $iObjKey, $sToken)
	{
		$oMutex = new iTopMutex('lock_'.$sObjClass.'::'.$iObjKey);
		
		$oMutex->Lock();
		$oOwnershipLock = new iTopOwnershipLock($sObjClass, $iObjKey);
		$aResult = $oOwnershipLock->Extend($sToken);
		$oMutex->Unlock();
		
		return $aResult;
	}

	/**
	 * Releases the given lock for the specified object
	 * 
	 * @param string $sObjClass The class of the object
	 * @param int $iObjKey The identifier of the object
	 * @param string $sToken The string identifying the lock
	 * @return boolean
	 */
	public static function ReleaseLock($sObjClass, $iObjKey, $sToken)
	{
		$oMutex = new iTopMutex('lock_'.$sObjClass.'::'.$iObjKey);
	
		$oMutex->Lock();
		$oOwnershipLock = new iTopOwnershipLock($sObjClass, $iObjKey);
		$bResult = $oOwnershipLock->Release($sToken);
		self::DeleteExpiredLocks(); // Cleanup orphan locks
		$oMutex->Unlock();
	
		return $bResult;
	}

	/**
	 * Kills the lock for the specified object
	 *
	 * @param string $sObjClass The class of the object
	 * @param int $iObjKey The identifier of the object
	 * @return boolean
	 */
	public static function KillLock($sObjClass, $iObjKey)
	{
		$oMutex = new iTopMutex('lock_'.$sObjClass.'::'.$iObjKey);
	
		$oMutex->Lock();
		$sOQL = "SELECT iTopOwnershipToken WHERE obj_class = :obj_class AND obj_key = :obj_key";
		$oSet = new DBObjectSet(DBObjectSearch::FromOQL($sOQL, array('obj_class' => $sObjClass, 'obj_key' => $iObjKey)));
		while($oLock = $oSet->Fetch())
		{
			$oLock->DBDelete();
		}
		$oMutex->Unlock();
	}
		
	/**
	 * Checks if an exclusive lock exists on the specified DBObject.
	 * @param string $sObjClass The class of the object for which to acquire the lock
	 * @param integer $iObjKey The identifier of the object for which to acquire the lock
	 * @return multitype:boolean iTopOwnershipLock Ambigous <boolean, string, DBObjectSet>
	 */
	public static function IsLocked($sObjClass, $iObjKey)
	{
		$bLocked = false;
		$oMutex = new iTopMutex('lock_'.$sObjClass.'::'.$iObjKey);
		
		$oMutex->Lock();
		$oOwnershipLock = new iTopOwnershipLock($sObjClass, $iObjKey);
		if ($oOwnershipLock->IsOwned())
		{
			$bLocked = true;
		}
		$oMutex->Unlock();
		
		return array('locked' =>$bLocked, 'owner' => $oOwnershipLock->GetOwner());
	}
	
	/**
	 * Get the current owner of the lock
	 * @return User
	 */
	public function GetOwner()
	{
		if ($this->IsTokenValid())
		{
			return MetaModel::GetObject('User', $this->oToken->Get('user_id'), false);
		}
		return null;
	}

	/**
	 * The constructor is protected. Use the static methods AcquireLock / ExtendLock / ReleaseLock / KillLock
	 * which are protected against concurrent access by a Mutex.
	 * @param string $sObjClass The class of the object for which to create a lock
	 * @param integer $iObjKey The identifier of the object for which to create a lock
	 */
	protected function __construct($sObjClass, $iObjKey)
	{
		$sOQL = "SELECT iTopOwnershipToken WHERE obj_class = :obj_class AND obj_key = :obj_key";
		$oSet = new DBObjectSet(DBObjectSearch::FromOQL($sOQL, array('obj_class' => $sObjClass, 'obj_key' => $iObjKey)));
		$this->oToken = $oSet->Fetch();
		$this->sObjClass = $sObjClass;
		$this->iObjKey = $iObjKey;
		// IssueLog::Info("iTopOwnershipLock::__construct($sObjClass, $iObjKey) oToken::".($this->oToken ? $this->oToken->GetKey() : 'null'));
	}
	
	protected function IsOwned()
	{
		return $this->IsTokenValid();
	}
	
	protected function Acquire($sToken = null)
	{
		if ($this->IsTokenValid())
		{
			// IssueLog::Info("Acquire($sToken) returns false");
			return false;
		}
		else
		{
			$sToken = $this->TakeOwnership($sToken);
			// IssueLog::Info("Acquire($sToken) returns $sToken");
			return $sToken;
		}
	}
	
	/**
	 * Extends the ownership lock or acquires it if none exists
	 * Returns a hash array with 3 elements:
	 * 'status': either true or false, tells if the lock is still owned
	 * 'owner': is status is false, the User object currently owning the lock
	 * 'operation': whether the lock was 'renewed' (i.e. the lock was valid, its duration was extended) or 'expired' (there was no valid lock for this object) or 'lost' (someone else grabbed it)
	 * 'acquired': date at which the lock was initially acquired
	 * @param string $sToken
	 * @return multitype:boolean string User
	 */
	protected function Extend($sToken)
	{
		$aResult = array('status' => true, 'owner' => '', 'operation' => 'renewed');
		
		if ($this->IsTokenValid())
		{
			if ($sToken === $this->oToken->Get('token'))
			{
				$this->oToken->Set('last_seen', date('Y-m-d H:i:s'));
				$this->oToken->DBUpdate();
				$aResult['acquired'] = $this->oToken->Get('acquired');
			}
			else
			{
				// IssueLog::Info("Extend($sToken) returns false");
				$aResult['status'] = false;
				$aResult['operation'] = 'lost';
				$aResult['owner'] = $this->GetOwner();
				$aResult['acquired'] = $this->oToken->Get('acquired');
			}
		}
		else
		{
			$aResult['status'] = false;
			$aResult['operation'] = 'expired';
		}
		// IssueLog::Info("Extend($sToken) returns true");
		return $aResult;
	}
	
	protected function HasOwnership($sToken)
	{
		$bRet = false;
		if ($this->IsTokenValid())
		{
			if ($sToken === $this->oToken->Get('token'))
			{
				$bRet = true;
			}
		}
		// IssueLog::Info("HasOwnership($sToken) return $bRet");
		return $bRet;
	}
	
	protected function Release($sToken)
	{
		$bRet = false;
		// IssueLog::Info("Release... begin [$sToken]");
		if (($this->oToken) && ($sToken === $this->oToken->Get('token')))
		{
			// IssueLog::Info("oToken::".$this->oToken->GetKey().' ('.$sToken.') to be deleted');
			$this->oToken->DBDelete();
			// IssueLog::Info("oToken deleted");
			$this->oToken = null;
			$bRet = true;
		}
		else if ($this->oToken == null)
		{
		// IssueLog::Info("Release FAILED oToken == null !!!");
		}
		else
		{
		// IssueLog::Info("Release FAILED inconsistent tokens: sToken=\"".$sToken.'", oToken->Get(\'token\')="'.$this->oToken->Get('token').'"');
		}
		// IssueLog::Info("Release... end");
		return $bRet;
	}
	
	protected function IsTokenValid()
	{
		$bRet = false;
		if ($this->oToken != null)
		{
			$sToken = $this->oToken->Get('token');
			$sDate = $this->oToken->Get('last_seen');
			if (($sDate != '') && ($sToken != ''))
			{
				$oLastSeenTime = new DateTime($sDate);
				$iNow = date('U');
				if (($iNow - $oLastSeenTime->format('U')) < MetaModel::GetConfig()->Get('concurrent_lock_expiration_delay'))
				{
					$bRet = true;
				}
			}
		}
		return $bRet; 
	}
	
	protected function TakeOwnership($sToken = null)
	{
		if ($this->oToken == null)
		{
			$this->oToken = new iTopOwnershipToken();
			$this->oToken->Set('obj_class', $this->sObjClass);
			$this->oToken->Set('obj_key', $this->iObjKey);
		}
		$this->oToken->Set('acquired', date('Y-m-d H:i:s'));
		$this->oToken->Set('user_id', UserRights::GetUserId());
		$this->oToken->Set('last_seen', date('Y-m-d H:i:s'));
		if ($sToken === null)
		{
			$sToken = sprintf('%X', microtime(true));
		}
		$this->oToken->Set('token', $sToken);
		$this->oToken->DBWrite();
		return $this->oToken->Get('token');
	}
	
	protected static function DeleteExpiredLocks()
	{
		$sOQL = "SELECT iTopOwnershipToken WHERE last_seen < :last_seen_limit";
		$oSet = new DBObjectSet(DBObjectSearch::FromOQL($sOQL, array('last_seen_limit' => date('Y-m-d H:i:s', time() - MetaModel::GetConfig()->Get('concurrent_lock_expiration_delay')))));
		while($oToken = $oSet->Fetch())
		{
			$oToken->DBDelete();
		}
		
	}
}
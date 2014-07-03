<?php
// Copyright (C) 2013 Combodo SARL
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
 * Class iTopMutex
 * A class to serialize the execution of some code sections
 * Emulates the API of PECL Mutex class
 * Relies on MySQL locks because the API sem_get is not always present in the
 * installed PHP.    
 *
 * @copyright   Copyright (C) 2013 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
class iTopMutex
{
	protected $sName;
	protected $hDBLink;
	protected $bLocked; // Whether or not this instance of the Mutex is locked
	static protected $aAcquiredLocks = array(); // Number of instances of the Mutex, having the lock, in this page

	public function __construct($sName, $sDBHost = null, $sDBUser = null, $sDBPwd = null)
	{
		// Compute the name of a lock for mysql
		// Note: the name is server-wide!!!
		$this->sName = 'itop.'.$sName;
		$this->bLocked = false; // Not yet locked

		if (!array_key_exists($this->sName, self::$aAcquiredLocks))
		{
			self::$aAcquiredLocks[$this->sName] = 0;
		}

		// It is a MUST to create a dedicated session each time a lock is required, because
		// using GET_LOCK anytime on the same session will RELEASE the current and unique session lock (known issue)
		$oConfig = utils::GetConfig();
		$sDBHost = is_null($sDBHost) ? $oConfig->GetDBHost() : $sDBHost;
		$sDBUser = is_null($sDBUser) ? $oConfig->GetDBUser() : $sDBUser;
		$sDBPwd = is_null($sDBPwd) ? $oConfig->GetDBPwd() : $sDBPwd;
		$this->InitMySQLSession($sDBHost, $sDBUser, $sDBPwd);
	}

	public function __destruct()
	{
		if ($this->bLocked)
		{
			$this->Unlock();
		}
		mysqli_close($this->hDBLink);
	}

	/**
	 *	Acquire the mutex
	 */	
	public function Lock()
	{
		if ($this->bLocked)
		{
			// Lock already acquired
			return;
		}
		if (self::$aAcquiredLocks[$this->sName] == 0)
		{
			do
			{
				$res = $this->QueryToScalar("SELECT GET_LOCK('".$this->sName."', 3600)");
				if (is_null($res))
				{
					throw new Exception("Failed to acquire the lock '".$this->sName."'");
				}
				// $res === '1' means I hold the lock
				// $res === '0' means it timed out
			}
			while ($res !== '1');
		}
		$this->bLocked = true;
		self::$aAcquiredLocks[$this->sName]++;
	}

	/**
	 *	Attempt to acquire the mutex
	 *	@returns bool True if the mutex is acquired, false if already locked elsewhere	 
	 */	
	public function TryLock()
	{
		if ($this->bLocked)
		{
			return true; // Already acquired
		}
		if (self::$aAcquiredLocks[$this->sName] > 0)
		{
			self::$aAcquiredLocks[$this->sName]++;
			$this->bLocked = true;
			return true;
		}
		
		$res = $this->QueryToScalar("SELECT GET_LOCK('".$this->sName."', 0)");
		if (is_null($res))
		{
			throw new Exception("Failed to acquire the lock '".$this->sName."'");
		}
		// $res === '1' means I hold the lock
		// $res === '0' means it timed out
		if ($res === '1')
		{
			$this->bLocked = true;
			self::$aAcquiredLocks[$this->sName]++;
		}
		return ($res === '1');
	}

	/**
	 *	Release the mutex
	 */	
	public function Unlock()
	{
		if (!$this->bLocked)
		{
			// ??? the lock is not acquired, exit
	        return;	
		}
		if (self::$aAcquiredLocks[$this->sName] == 0)
		{
			return; // Safety net
		}
		
		if (self::$aAcquiredLocks[$this->sName] == 1)
		{
			$res = $this->QueryToScalar("SELECT RELEASE_LOCK('".$this->sName."')");
		}
		$this->bLocked = false;
		self::$aAcquiredLocks[$this->sName]--;
	}



	public function InitMySQLSession($sHost, $sUser, $sPwd)
	{
		$aConnectInfo = explode(':', $sHost);
		if (count($aConnectInfo) > 1)
		{
			// Override the default port
			$sServer = $aConnectInfo[0];
			$iPort = $aConnectInfo[1];
			$this->hDBLink = @mysqli_connect($sServer, $sUser, $sPwd, '', $iPort);
		}
		else
		{
			$this->hDBLink = @mysqli_connect($sHost, $sUser, $sPwd);
		}

		if (!$this->hDBLink)
		{
			throw new Exception("Could not connect to the DB server (host=$sHost, user=$sUser): ".mysqli_connect_error().' (mysql errno: '.mysqli_connect_errno().')');
		}
	}


	protected function QueryToScalar($sSql)
	{
		$result = mysqli_query($this->hDBLink, $sSql);
		if (!$result)
		{
			throw new Exception("Failed to issue MySQL query '".$sSql."': ".mysqli_error($this->hDBLink).' (mysql errno: '.mysqli_errno($this->hDBLink).')');
		}
		if ($aRow = mysqli_fetch_array($result, MYSQLI_BOTH))
		{
			$res = $aRow[0];
		}
		else
		{
			mysqli_free_result($result);
			throw new Exception("No result for query '".$sSql."'");
		}
		mysqli_free_result($result);
		return $res;
	}
}

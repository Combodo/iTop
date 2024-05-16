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


require_once(APPROOT.'/core/simplecrypt.class.inc.php');

/**
 * ormPassword
 * encapsulate the behavior of a one way encrypted password stored hashed
 * with a per password (as random as possible) salt, in order to prevent a "Rainbow table" hack.
 * If a cryptographic random number generator is available (on Linux or Windows)
 * it will be used for generating the salt.
 *
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 * @package     itopORM
 */

class ormPassword
{
	protected $m_sHashed;
	protected $m_sSalt;

	/**
	 * Constructor, initializes the password from the encrypted values
	 */
	public function __construct($sHash = '', $sSalt = '')
	{
		$this->m_sHashed = $sHash;
		//only used for <= 2.5 hashed password
		$this->m_sSalt = $sSalt;
	}
	
	/**
	 * Encrypts the clear text password, with a unique salt
	 */
	public function SetPassword($sClearTextPassword)
	{
		$iHashAlgo = MetaModel::GetConfig()->GetPasswordHashAlgo();
		$this->m_sHashed = password_hash($sClearTextPassword, $iHashAlgo);
	}

	/**
	 * Print the password: displays some stars
	 * @return string
	 */
	public function __toString()
	{
		return '*****'; // Password can not be read
	}

	public function IsEmpty()
	{
		return utils::IsNullOrEmptyString($this->m_sHashed);
	}
	
	public function GetHash()
	{
		return $this->m_sHashed;
	}
	
	public function GetSalt()
	{
		return $this->m_sSalt;
	}
	
	/**
	 * Displays the password: displays some stars
	 * @return string
	 */
	public function GetAsHTML()
	{
		return '*****'; // Password can not be read
	}

	/**
	 * Check if the supplied clear text password matches the encrypted one
	 * @param string $sClearTextPassword
	 * @return boolean True if it matches, false otherwise
	 */
	public function CheckPassword($sClearTextPassword)
	{
		$bResult = false;
		$aInfo = password_get_info($this->m_sHashed);
		if (is_null($aInfo["algo"]) || $aInfo["algo"] === 0)
		{
			//unknown, assume it's a legacy password
			$sHashedPwd = $this->ComputeHash($sClearTextPassword);
			$bResult = ($this->m_sHashed == $sHashedPwd);
		}
		else
		{
			$bResult = password_verify($sClearTextPassword, $this->m_sHashed);
		}
		return $bResult;
	}
		
	/**
	 * Computes the hashed version of a password using a unique salt
	 * for this password. A unique salt is generated if needed
	 * @return string
	 */
	protected function ComputeHash($sClearTextPwd)
	{
		if ($this->m_sSalt == null)
		{
			$this->m_sSalt = SimpleCrypt::GetNewSalt();
		}
		return hash('sha256', $this->m_sSalt.$sClearTextPwd);
	}
}
?>

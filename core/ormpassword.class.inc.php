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

require_once(APPROOT.'/core/simplecrypt.class.inc.php');

/**
 * ormPassword
 * encapsulate the behavior of a one way encrypted password stored hashed
 * with a per password (as random as possible) salt, in order to prevent a "Rainbow table" hack.
 * If a cryptographic random number generator is available (on Linux or Windows)
 * it will be used for generating the salt.
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
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
		$this->m_sSalt = $sSalt;
	}
	
	/**
	 * Encrypts the clear text password, with a unique salt
	 */
	public function SetPassword($sClearTextPassword)
	{
		$this->m_sSalt = SimpleCrypt::GetNewSalt();
		$this->m_sHashed = $this->ComputeHash($sClearTextPassword);
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
		return ($this->m_hashed == null);
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
		$sHashedPwd = $this->ComputeHash($sClearTextPassword);
		if ($this->m_sHashed == $sHashedPwd)
		{
			$bResult = true;
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
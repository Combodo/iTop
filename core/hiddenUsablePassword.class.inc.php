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


/**
 * hiddenUsablePassword
 * Password stored in iTop that should be hidden but passed to other authentication systems
 *
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 * @package     itopORM
 */

class hiddenUsablePassword
{
	protected string $sPwd;

	/**
	 * Constructor, initializes the password from the encrypted values
	 */
	public function __construct(string $sPwd = '')
	{
		$this->sPwd = $sPwd;
	}

	/**
	 * Print the password: displays some stars
	 * @return string
	 */
	public function __toString()
	{
		return $this->GetDisplayValue();
	}

	public function IsEmpty()
	{
		return utils::IsNullOrEmptyString($this->sPwd);
	}

	/**
	 * Displays the password: displays some stars
	 * @return string
	 */
	public function GetAsHTML()
	{
		return $this->GetDisplayValue();
	}

	public function GetDisplayValue()
	{
		if ($this->IsEmpty())
		{
			return '';
		}

		// Password can not be read
		return '******';
	}

	public function GetValueForUsage()
	{
		return $this->sPwd;
	}
}
?>

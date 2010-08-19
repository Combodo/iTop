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
 * UserRightsNull
 * User management Module - say Yeah! to everything
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

class UserRightsNull extends UserRightsAddOnAPI
{
	// Installation: create the very first user
	public function CreateAdministrator($sAdminUser, $sAdminPwd, $sLanguage = 'EN US')
	{
		return true;
	}

	public function IsAdministrator($oUser)
	{
		return true;
	}

	public function Setup()
	{
		return true;
	}

	public function Init()
	{
		return true;
	}

	public function GetFilter($sUserName, $sClass)
	{
		$oNullFilter  = new DBObjectSearch($sClass);
		return $oNullFilter;
	}

	public function GetSelectFilter($oUser, $sClass)
	{
		$oNullFilter  = new DBObjectSearch($sClass);
		return $oNullFilter;
	}

	public function IsActionAllowed($oUser, $sClass, $iActionCode, $oInstanceSet = null)
	{
		return UR_ALLOWED_YES;
	}

	public function IsStimulusAllowed($oUser, $sClass, $sStimulusCode, $oInstanceSet = null)
	{
		return UR_ALLOWED_YES;
	}

	public function IsActionAllowedOnAttribute($oUser, $sClass, $sAttCode, $iActionCode, $oInstanceSet = null)
	{
		return UR_ALLOWED_YES;
	}

	public function FlushPrivileges()
	{
	}
}

UserRights::SelectModule('UserRightsNull');

?>

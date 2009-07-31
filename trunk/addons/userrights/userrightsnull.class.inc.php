<?php

/**
 * UserRightsNull
 * User management Module - say Yeah! to everything
 *
 * @package     iTopORM
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @author      Denis Flaven <denisflave@free.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     1.1.1.1 $
 */


class UserRightsNull extends UserRightsAddOnAPI
{
	// Installation: create the very first user
	public function CreateAdministrator($sAdminUser, $sAdminPwd)
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

	public function CheckCredentials($sUserName, $sPassword)
	{
		return 1;
	}

	public function GetUserId($sUserName)
	{
		return 1;
	}

	public function GetFilter($sUserName, $sClass)
	{
		$oNullFilter  = new DBObjectSearch($sClass);
		return $oNullFilter;
	}

	public function IsActionAllowed($iUserId, $sClass, $iActionCode, dbObjectSet $aInstances)
	{
		return UR_ALLOWED_YES;
	}

	public function IsStimulusAllowed($iUserId, $sClass, $sStimulusCode, dbObjectSet $aInstances)
	{
		return UR_ALLOWED_YES;
	}

	public function IsActionAllowedOnAttribute($iUserId, $sClass, $sAttCode, $iActionCode, dbObjectSet $aInstances)
	{
		return UR_ALLOWED_YES;
	}
}

UserRights::SelectModule('UserRightsNull');

?>

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
		return true;
	}

	public function GetFilter($sUserName, $sClass)
	{
		$oNullFilter  = new DBObjectSearch($sClass);
		return $oNullFilter;
	}

	public function IsActionAllowed($sUserName, $sClass, $iActionCode, dbObjectSet $aInstances)
	{
		return UR_ALLOWED_YES;
	}

	public function IsStimulusAllowed($sUserName, $sClass, $sStimulusCode, dbObjectSet $aInstances)
	{
		return UR_ALLOWED_YES;
	}

	public function IsActionAllowedOnAttribute($sUserName, $sClass, $sAttCode, $iActionCode, dbObjectSet $aInstances)
	{
		return UR_ALLOWED_YES;
	}
}

UserRights::SelectModule('UserRightsNull');

?>

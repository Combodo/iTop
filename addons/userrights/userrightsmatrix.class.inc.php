<?php
// Copyright (C) 2010-2012 Combodo SARL
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
 * UserRightsMatrix (User management Module)
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


class UserRightsMatrixClassGrant extends DBObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "addon/userrights",
			"key_type" => "autoincrement",
			"name_attcode" => "",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_ur_matrixclasses",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		//MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeExternalKey("userid", array("targetclass"=>"User", "jointype"=> "", "allowed_values"=>null, "sql"=>"userid", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("login", array("allowed_values"=>null, "extkey_attcode"=> 'userid', "target_attcode"=>"login")));
		MetaModel::Init_AddAttribute(new AttributeString("class", array("allowed_values"=>null, "sql"=>"class", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));

		MetaModel::Init_AddAttribute(new AttributeString("action", array("allowed_values"=>null, "sql"=>"action", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("permission", array("allowed_values"=>new ValueSetEnum('yes,no'), "sql"=>"permission", "default_value"=>"yes", "is_null_allowed"=>false, "depends_on"=>array())));
	}
}

class UserRightsMatrixClassStimulusGrant extends DBObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "addon/userrights",
			"key_type" => "autoincrement",
			"name_attcode" => "",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_ur_matrixclassesstimulus",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		//MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeExternalKey("userid", array("targetclass"=>"User", "jointype"=> "", "allowed_values"=>null, "sql"=>"userid", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("login", array("allowed_values"=>null, "extkey_attcode"=> 'userid', "target_attcode"=>"login")));
		MetaModel::Init_AddAttribute(new AttributeString("class", array("allowed_values"=>null, "sql"=>"class", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));

		MetaModel::Init_AddAttribute(new AttributeString("stimulus", array("allowed_values"=>null, "sql"=>"action", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("permission", array("allowed_values"=>new ValueSetEnum('yes,no'), "sql"=>"permission", "default_value"=>"yes", "is_null_allowed"=>false, "depends_on"=>array())));
	}
}

class UserRightsMatrixAttributeGrant extends DBObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "addon/userrights",
			"key_type" => "autoincrement",
			"name_attcode" => "",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_ur_matrixattributes",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		//MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeExternalKey("userid", array("targetclass"=>"User", "jointype"=> "", "allowed_values"=>null, "sql"=>"userid", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("login", array("allowed_values"=>null, "extkey_attcode"=> 'userid', "target_attcode"=>"login")));
		MetaModel::Init_AddAttribute(new AttributeString("class", array("allowed_values"=>null, "sql"=>"class", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("attcode", array("allowed_values"=>null, "sql"=>"attcode", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));

		MetaModel::Init_AddAttribute(new AttributeString("action", array("allowed_values"=>null, "sql"=>"action", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("permission", array("allowed_values"=>new ValueSetEnum('yes,no'), "sql"=>"permission", "default_value"=>"yes", "is_null_allowed"=>false, "depends_on"=>array())));
	}
}




class UserRightsMatrix extends UserRightsAddOnAPI
{
	static public $m_aActionCodes = array(
		UR_ACTION_READ => 'read',
		UR_ACTION_MODIFY => 'modify',
		UR_ACTION_DELETE => 'delete',
		UR_ACTION_BULK_READ => 'bulk read',
		UR_ACTION_BULK_MODIFY => 'bulk modify',
		UR_ACTION_BULK_DELETE => 'bulk delete',
	);

	// Installation: create the very first user
	public function CreateAdministrator($sAdminUser, $sAdminPwd, $sLanguage = 'EN US')
	{
		// Maybe we should check that no other user with userid == 0 exists
		$oUser = new UserLocal();
		$oUser->Set('login', $sAdminUser);
		$oUser->Set('password', $sAdminPwd);
		$oUser->Set('contactid', 1); // one is for root !
		$oUser->Set('language', $sLanguage); // Language was chosen during the installation

		// Create a change to record the history of the User object
		$oChange = MetaModel::NewObject("CMDBChange");
		$oChange->Set("date", time());
		$oChange->Set("userinfo", "Initialization");
		$iChangeId = $oChange->DBInsert();

		// Now record the admin user object
		$iUserId = $oUser->DBInsertTrackedNoReload($oChange, true /* skip security */);
		$this->SetupUser($iUserId, true);
		return true;
	}

	public function IsAdministrator($oUser)
	{
		return ($oUser->GetKey() == 1);
	}

	public function IsPortalUser($oUser)
	{
		return ($oUser->GetKey() == 1);
	}

	// Deprecated - create a new module !
	public function Setup()
	{
		// Users must be added manually
		// This procedure will then update the matrix when a new user is found or a new class/attribute appears
		$oUserSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT User"));
		while ($oUser = $oUserSet->Fetch())
		{
			$this->SetupUser($oUser->GetKey());
		}
		return true;
	}

	protected function SetupUser($iUserId, $bNewUser = false)
	{
		foreach(array('bizmodel', 'application', 'gui', 'core/cmdb') as $sCategory)
		{
			foreach (MetaModel::GetClasses($sCategory) as $sClass)
			{
				foreach (self::$m_aActionCodes as $iActionCode => $sAction)
				{
					if ($bNewUser)
					{
						$bAddCell = true;
					}
					else
					{
						$oSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT UserRightsMatrixClassGrant WHERE class = '$sClass' AND action = '$sAction' AND userid = $iUserId"));
						$bAddCell = ($oSet->Count() < 1);
					}
					if ($bAddCell)
					{
						// Create a new entry
						$oMyClassGrant = MetaModel::NewObject("UserRightsMatrixClassGrant");
						$oMyClassGrant->Set("userid", $iUserId);
						$oMyClassGrant->Set("class", $sClass);
						$oMyClassGrant->Set("action", $sAction);
						$oMyClassGrant->Set("permission", "yes");
						$iId = $oMyClassGrant->DBInsertNoReload();
					}
				}
				foreach (MetaModel::EnumStimuli($sClass) as $sStimulusCode => $oStimulus)
				{
					if ($bNewUser)
					{
						$bAddCell = true;
					}
					else
					{
						$oSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT UserRightsMatrixClassStimulusGrant WHERE class = '$sClass' AND stimulus = '$sStimulusCode' AND userid = $iUserId"));
						$bAddCell = ($oSet->Count() < 1);
					}
					if ($bAddCell)
					{
						// Create a new entry
						$oMyClassGrant = MetaModel::NewObject("UserRightsMatrixClassStimulusGrant");
						$oMyClassGrant->Set("userid", $iUserId);
						$oMyClassGrant->Set("class", $sClass);
						$oMyClassGrant->Set("stimulus", $sStimulusCode);
						$oMyClassGrant->Set("permission", "yes");
						$iId = $oMyClassGrant->DBInsertNoReload();
					}
				}
				foreach (MetaModel::GetAttributesList($sClass) as $sAttCode)
				{
					if ($bNewUser)
					{
						$bAddCell = true;
					}
					else
					{
						$oSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT UserRightsMatrixAttributeGrant WHERE class = '$sClass' AND attcode = '$sAttCode' AND userid = $iUserId"));
						$bAddCell = ($oSet->Count() < 1);
					}
					if ($bAddCell)
					{
						foreach (array('read', 'modify') as $sAction)
						{
							// Create a new entry
							$oMyAttGrant = MetaModel::NewObject("UserRightsMatrixAttributeGrant");
							$oMyAttGrant->Set("userid", $iUserId);
							$oMyAttGrant->Set("class", $sClass);
							$oMyAttGrant->Set("attcode", $sAttCode);
							$oMyAttGrant->Set("action", $sAction);
							$oMyAttGrant->Set("permission", "yes");
							$iId = $oMyAttGrant->DBInsertNoReload();
						}
					}
				}
			}
		}
		/*
		// Create the "My Bookmarks" menu item (parent_id = 0, rank = 6)
		if ($bNewUser)
		{
			$bAddMenu = true;
		}
		else
		{
			$oSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT menuNode WHERE type = 'user' AND parent_id = 0 AND user_id = $iUserId"));
			$bAddMenu = ($oSet->Count() < 1);
		}
		if ($bAddMenu)
		{
			$oMenu = MetaModel::NewObject('menuNode');
			$oMenu->Set('type', 'user');
			$oMenu->Set('parent_id', 0);	// It's a toplevel entry
			$oMenu->Set('rank', 6);			// Located just above the Admin Tools section (=7)
			$oMenu->Set('name', 'My Bookmarks');
			$oMenu->Set('label', 'My Favorite Items');
			$oMenu->Set('hyperlink', 'UI.php');
			$oMenu->Set('template', '<p></p><p></p><p style="text-align:center; font-family:Georgia, Times, serif; font-size:32px;">My bookmarks</p><p style="text-align:center; font-family:Georgia, Times, serif; font-size:14px;"><i>This section contains my most favorite search results</i></p>');
			$oMenu->Set('user_id', $iUserId);
			$oMenu->DBInsert();
		}
		*/
	}


	public function Init()
	{
		// Could be loaded in a shared memory (?)
		return true;
	}

	public function GetSelectFilter($oUser, $sClass, $aSettings = array())
	{
		$oNullFilter  = new DBObjectSearch($sClass);
		return $oNullFilter;
	}

	public function IsActionAllowed($oUser, $sClass, $iActionCode, $oInstanceSet = null)
	{
		if (!array_key_exists($iActionCode, self::$m_aActionCodes))
		{
			return UR_ALLOWED_NO;
		}
		$sAction = self::$m_aActionCodes[$iActionCode];

		$oSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT UserRightsMatrixClassGrant WHERE class = '$sClass' AND action = '$sAction' AND userid = '{$oUser->GetKey()}'"));
		if ($oSet->Count() < 1)
		{
			return UR_ALLOWED_NO;
		}

		$oGrantRecord = $oSet->Fetch();
		switch ($oGrantRecord->Get('permission'))
		{
			case 'yes':
				$iRetCode = UR_ALLOWED_YES;
				break;
			case 'no':
			default:
				$iRetCode = UR_ALLOWED_NO;
				break;
		}
		return $iRetCode;
	}

	public function IsActionAllowedOnAttribute($oUser, $sClass, $sAttCode, $iActionCode, $oInstanceSet = null)
	{
		if (!array_key_exists($iActionCode, self::$m_aActionCodes))
		{
			return UR_ALLOWED_NO;
		}
		$sAction = self::$m_aActionCodes[$iActionCode];

		$oSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT UserRightsMatrixAttributeGrant WHERE class = '$sClass' AND attcode = '$sAttCode' AND action = '$sAction' AND userid = '{$oUser->GetKey()}'"));
		if ($oSet->Count() < 1)
		{
			return UR_ALLOWED_NO;
		}

		$oGrantRecord = $oSet->Fetch();
		switch ($oGrantRecord->Get('permission'))
		{
			case 'yes':
				$iRetCode = UR_ALLOWED_YES;
				break;
			case 'no':
			default:
				$iRetCode = UR_ALLOWED_NO;
				break;
		}
		return $iRetCode;
	}

	public function IsStimulusAllowed($oUser, $sClass, $sStimulusCode, $oInstanceSet = null)
	{
		$oSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT UserRightsMatrixClassStimulusGrant WHERE class = '$sClass' AND stimulus = '$sStimulusCode' AND userid = '{$oUser->GetKey()}'"));
		if ($oSet->Count() < 1)
		{
			return UR_ALLOWED_NO;
		}

		$oGrantRecord = $oSet->Fetch();
		switch ($oGrantRecord->Get('permission'))
		{
			case 'yes':
				$iRetCode = UR_ALLOWED_YES;
				break;
			case 'no':
			default:
				$iRetCode = UR_ALLOWED_NO;
				break;
		}
		return $iRetCode;
	}

	public function FlushPrivileges()
	{
	}
}

UserRights::SelectModule('UserRightsMatrix');

?>

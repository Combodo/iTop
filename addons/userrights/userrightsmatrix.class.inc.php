<?php

/**
 * UserRightsMatrix
 * User management Module 
 *
 * @package     iTopORM
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @author      Denis Flaven <denisflave@free.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     1.1.1.1 $
 */


class UserRightsMatrixUsers extends DBObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "addon/userrights",
			"name" => "user",
			"description" => "users and credentials",
			"key_type" => "autoincrement",
			"key_label" => "",
			"name_attcode" => "login",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_ur_matrixusers",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		//MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeInteger("userid", array("label"=>"User id", "description"=>"User identifier (depends on the business model)", "allowed_values"=>null, "sql"=>"userid", "default_value"=>0, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("login", array("label"=>"login", "description"=>"user identification string", "allowed_values"=>null, "sql"=>"login", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("password", array("label"=>"password", "description"=>"user authentication string", "allowed_values"=>null, "sql"=>"pwd", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));

		//MetaModel::Init_InheritFilters();
		MetaModel::Init_AddFilterFromAttribute("userid");
		MetaModel::Init_AddFilterFromAttribute("login");
	}
}

class UserRightsMatrixClassGrant extends DBObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "addon/userrights",
			"name" => "change",
			"description" => "permissions on classes",
			"key_type" => "autoincrement",
			"key_label" => "",
			"name_attcode" => "",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_ur_matrixclasses",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		//MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeExternalKey("userid", array("targetclass"=>"UserRightsMatrixUsers", "jointype"=> "", "label"=>"user", "description"=>"user account", "allowed_values"=>null, "sql"=>"userid", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("login", array("label"=>"Login", "description"=>"Login", "allowed_values"=>null, "extkey_attcode"=> 'userid', "target_attcode"=>"login")));
		MetaModel::Init_AddAttribute(new AttributeString("class", array("label"=>"class", "description"=>"class name", "allowed_values"=>null, "sql"=>"class", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));

		MetaModel::Init_AddAttribute(new AttributeString("action", array("label"=>"action", "description"=>"operations to perform on the given class", "allowed_values"=>null, "sql"=>"action", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("permission", array("label"=>"permission", "description"=>"allowed or not allowed?", "allowed_values"=>new ValueSetEnum('yes,no'), "sql"=>"permission", "default_value"=>"yes", "is_null_allowed"=>false, "depends_on"=>array())));

		//MetaModel::Init_InheritFilters();
		MetaModel::Init_AddFilterFromAttribute("userid");
		MetaModel::Init_AddFilterFromAttribute("login");
		MetaModel::Init_AddFilterFromAttribute("class");
		MetaModel::Init_AddFilterFromAttribute("action");
	}
}

class UserRightsMatrixClassStimulusGrant extends DBObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "addon/userrights",
			"name" => "change",
			"description" => "permissions on classes (stimulus on state machine)",
			"key_type" => "autoincrement",
			"key_label" => "",
			"name_attcode" => "",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_ur_matrixclassesstimulus",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		//MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeExternalKey("userid", array("targetclass"=>"UserRightsMatrixUsers", "jointype"=> "", "label"=>"user", "description"=>"user account", "allowed_values"=>null, "sql"=>"userid", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("login", array("label"=>"Login", "description"=>"Login", "allowed_values"=>null, "extkey_attcode"=> 'userid', "target_attcode"=>"login")));
		MetaModel::Init_AddAttribute(new AttributeString("class", array("label"=>"class", "description"=>"class name", "allowed_values"=>null, "sql"=>"class", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));

		MetaModel::Init_AddAttribute(new AttributeString("stimulus", array("label"=>"action", "description"=>"operations to perform on the given class", "allowed_values"=>null, "sql"=>"action", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("permission", array("label"=>"permission", "description"=>"allowed or not allowed?", "allowed_values"=>new ValueSetEnum('yes,no'), "sql"=>"permission", "default_value"=>"yes", "is_null_allowed"=>false, "depends_on"=>array())));

		//MetaModel::Init_InheritFilters();
		MetaModel::Init_AddFilterFromAttribute("userid");
		MetaModel::Init_AddFilterFromAttribute("login");
		MetaModel::Init_AddFilterFromAttribute("class");
		MetaModel::Init_AddFilterFromAttribute("stimulus");
	}
}

class UserRightsMatrixAttributeGrant extends DBObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "addon/userrights",
			"name" => "change",
			"description" => "permissions on classes",
			"key_type" => "autoincrement",
			"key_label" => "",
			"name_attcode" => "",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_ur_matrixattributes",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		//MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeExternalKey("userid", array("targetclass"=>"UserRightsMatrixUsers", "jointype"=> "", "label"=>"user", "description"=>"user account", "allowed_values"=>null, "sql"=>"userid", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("login", array("label"=>"Login", "description"=>"Login", "allowed_values"=>null, "extkey_attcode"=> 'userid', "target_attcode"=>"login")));
		MetaModel::Init_AddAttribute(new AttributeString("class", array("label"=>"class", "description"=>"class name", "allowed_values"=>null, "sql"=>"class", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("attcode", array("label"=>"attribute", "description"=>"attribute code", "allowed_values"=>null, "sql"=>"attcode", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));

		MetaModel::Init_AddAttribute(new AttributeString("action", array("label"=>"action", "description"=>"operations to perform on the given class", "allowed_values"=>null, "sql"=>"action", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("permission", array("label"=>"permission", "description"=>"allowed or not allowed?", "allowed_values"=>new ValueSetEnum('yes,no'), "sql"=>"permission", "default_value"=>"yes", "is_null_allowed"=>false, "depends_on"=>array())));

		//MetaModel::Init_InheritFilters();
		MetaModel::Init_AddFilterFromAttribute("userid");
		MetaModel::Init_AddFilterFromAttribute("login");
		MetaModel::Init_AddFilterFromAttribute("class");
		MetaModel::Init_AddFilterFromAttribute("attcode");
		MetaModel::Init_AddFilterFromAttribute("action");
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
	public function CreateAdministrator($sAdminUser, $sAdminPwd)
	{
		// Maybe we should check that no other user with userid == 0 exists
		$oUser = new UserRightsMatrixUsers();
		$oUser->Set('login', $sAdminUser);
		$oUser->Set('password', $sAdminPwd);
		$oUser->Set('userid', 1); // one is for root !
		$oUser->DBInsert();
		$this->SetupUser($oUser, true);
		return true;
	}

	public function Setup()
	{
		// Users must be added manually
		// This procedure will then update the matrix when a new user is found or a new class/attribute appears
		$oUserSet = new DBObjectSet(DBObjectSearch::FromSibuSQL("UserRightsMatrixUsers"));
		while ($oUser = $oUserSet->Fetch())
		{
			SetupUser($oUser);
		}
		return true;
	}

	protected function SetupUser($oUser, $bNewUser = false)
	{
		$iUserId = $oUser->GetKey();

		foreach (MetaModel::GetClasses('bizmodel') as $sClass)
		{
			foreach (self::$m_aActionCodes as $iActionCode => $sAction)
			{
				if ($bNewUser)
				{
					$bAddCell = true;
				}
				else
				{
					$oSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT UserRightsMatrixClassGrant WHERE class = '$sClass' AND action = '$sAction' AND userid = $iUserId)"));
					$bAddCell = ($oSet->Count() < 1);
				}
				if ($bAddCell)
				{
					// Create a new entry
					$oMyClassGrant = MetaModel::NewObject("UserRightsMatrixClassGrant");
					$oMyClassGrant->Set("userid", $oUser->GetKey());
					$oMyClassGrant->Set("class", $sClass);
					$oMyClassGrant->Set("action", $sAction);
					$oMyClassGrant->Set("permission", "yes");
					$iId = $oMyClassGrant->DBInsert();
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
					$oMyClassGrant->Set("userid", $oUser->GetKey());
					$oMyClassGrant->Set("class", $sClass);
					$oMyClassGrant->Set("stimulus", $sStimulusCode);
					$oMyClassGrant->Set("permission", "yes");
					$iId = $oMyClassGrant->DBInsert();
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
						$oMyAttGrant->Set("userid", $oUser->GetKey());
						$oMyAttGrant->Set("class", $sClass);
						$oMyAttGrant->Set("attcode", $sAttCode);
						$oMyAttGrant->Set("action", $sAction);
						$oMyAttGrant->Set("permission", "yes");
						$iId = $oMyAttGrant->DBInsert();
					}
				}
			}
		}
	}


	public function Init()
	{
		// Could be loaded in a shared memory (?)
		return true;
	}

	public function CheckCredentials($sUserName, $sPassword)
	{
		$oSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT UserRightsMatrixUsers WHERE login = '$sUserName'"));
		if ($oSet->Count() < 1)
		{
		// todo: throw an exception?
			return false;
		}

		$oLogin = $oSet->Fetch();
		if ($oLogin->Get('password') == $sPassword)
		{
			return true;
		}
		// todo: throw an exception?
		return false;
	}

	public function GetFilter($sUserName, $sClass)
	{
		$oNullFilter  = new DBObjectSearch($sClass);
		return $oNullFilter;
	}

	public function IsActionAllowed($sUserName, $sClass, $iActionCode, dbObjectSet $aInstances)
	{
		if (!array_key_exists($iActionCode, self::$m_aActionCodes))
		{
			return UR_ALLOWED_NO;
		}
		$sAction = self::$m_aActionCodes[$iActionCode];

		$oSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT UserRightsMatrixClassGrant WHERE class = '$sClass' AND action = '$sAction' AND login = '$sUserName'"));
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

	public function IsActionAllowedOnAttribute($sUserName, $sClass, $sAttCode, $iActionCode, dbObjectSet $aInstances)
	{
		if (!array_key_exists($iActionCode, self::$m_aActionCodes))
		{
			return UR_ALLOWED_NO;
		}
		$sAction = self::$m_aActionCodes[$iActionCode];

		$oSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT UserRightsMatrixAttributeGrant WHERE UserRightsMatrixAttributeGrant.class = '$sClass' AND UserRightsMatrixAttributeGrant.attcode = '$sAttCode' AND UserRightsMatrixAttributeGrant.action = '$sAction' AND UserRightsMatrixAttributeGrant.login = '$sUserName'"));
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

	public function IsStimulusAllowed($sUserName, $sClass, $sStimulusCode, dbObjectSet $aInstances)
	{
		$oSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT UserRightsMatrixClassStimulusGrant WHERE class = '$sClass' AND stimulus = '$sStimulusCode' AND login = '$sUserName'"));
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
}

UserRights::SelectModule('UserRightsMatrix');

?>

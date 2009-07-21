<?php

/**
 * UserRightsProfile
 * User management Module, basing the right on profiles and a matrix (similar to UserRightsMatrix, but profiles and other decorations have been added) 
 *
 * @package     iTopORM
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @author      Denis Flaven <denisflave@free.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     1.1.1.1 $
 */


class URP_Users extends DBObject
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
			"db_table" => "priv_urp_users",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		//MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeInteger("userid", array("label"=>"User id", "description"=>"User identifier (depends on the business model)", "allowed_values"=>null, "sql"=>"userid", "default_value"=>0, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("login", array("label"=>"login", "description"=>"user identification string", "allowed_values"=>null, "sql"=>"login", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("password", array("label"=>"password", "description"=>"user authentication string", "allowed_values"=>null, "sql"=>"pwd", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("email", array("label"=>"email", "description"=>"email address", "allowed_values"=>null, "sql"=>"email", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("firstname", array("label"=>"firstname", "description"=>"first name", "allowed_values"=>null, "sql"=>"firstname", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("lastname", array("label"=>"lastname", "description"=>"last name", "allowed_values"=>null, "sql"=>"lastname", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));

		//MetaModel::Init_InheritFilters();
		MetaModel::Init_AddFilterFromAttribute("userid");
		MetaModel::Init_AddFilterFromAttribute("login");
	}
}

class URP_Profiles extends DBObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "addon/userrights",
			"name" => "profile",
			"description" => "usage profiles",
			"key_type" => "autoincrement",
			"key_label" => "",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_urp_profiles",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		//MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeString("name", array("label"=>"name", "description"=>"label", "allowed_values"=>null, "sql"=>"name", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("description", array("label"=>"description", "description"=>"one line description", "allowed_values"=>null, "sql"=>"description", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));

		//MetaModel::Init_InheritFilters();
		MetaModel::Init_AddFilterFromAttribute("name");
		MetaModel::Init_AddFilterFromAttribute("description");
	}
}

class URP_Dimensions extends DBObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "addon/userrights",
			"name" => "dimension",
			"description" => "application dimension (defining silos)",
			"key_type" => "autoincrement",
			"key_label" => "",
			"name_attcode" => "name",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_urp_dimensions",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		//MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeString("name", array("label"=>"name", "description"=>"label", "allowed_values"=>null, "sql"=>"name", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("description", array("label"=>"description", "description"=>"one line description", "allowed_values"=>null, "sql"=>"description", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));

		//MetaModel::Init_InheritFilters();
		MetaModel::Init_AddFilterFromAttribute("name");
		MetaModel::Init_AddFilterFromAttribute("description");
	}
}

class URP_UserProfile extends DBObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "addon/userrights",
			"name" => "user_profile",
			"description" => "user profiles",
			"key_type" => "autoincrement",
			"key_label" => "",
			"name_attcode" => "",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_urp_userprofile",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		//MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeExternalKey("userid", array("targetclass"=>"URP_Users", "jointype"=> "", "label"=>"User", "description"=>"user account", "allowed_values"=>null, "sql"=>"userid", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("userlogin", array("label"=>"Login", "description"=>"User's login", "allowed_values"=>null, "extkey_attcode"=> 'userid', "target_attcode"=>"login")));

		MetaModel::Init_AddAttribute(new AttributeExternalKey("profileid", array("targetclass"=>"URP_Profiles", "jointype"=> "", "label"=>"profile", "description"=>"usage profile", "allowed_values"=>null, "sql"=>"profileid", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("profile", array("label"=>"Profile", "description"=>"Profile name", "allowed_values"=>null, "extkey_attcode"=> 'profileid', "target_attcode"=>"name")));

		//MetaModel::Init_InheritFilters();
		MetaModel::Init_AddFilterFromAttribute("userid");
		MetaModel::Init_AddFilterFromAttribute("profileid");
	}
}

class URP_ProfileProjection extends DBObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "addon/userrights",
			"name" => "profile_projection",
			"description" => "profile projections",
			"key_type" => "autoincrement",
			"key_label" => "",
			"name_attcode" => "",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_urp_profileprojection",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		//MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeExternalKey("dimensionid", array("targetclass"=>"URP_Dimensions", "jointype"=> "", "label"=>"Dimension", "description"=>"application dimension", "allowed_values"=>null, "sql"=>"dimensionid", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("dimension", array("label"=>"Dimension", "description"=>"application dimension", "allowed_values"=>null, "extkey_attcode"=> 'dimensionid', "target_attcode"=>"name")));

		MetaModel::Init_AddAttribute(new AttributeExternalKey("profileid", array("targetclass"=>"URP_Profiles", "jointype"=> "", "label"=>"profile", "description"=>"usage profile", "allowed_values"=>null, "sql"=>"profileid", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("profile", array("label"=>"Profile", "description"=>"Profile name", "allowed_values"=>null, "extkey_attcode"=> 'profileid', "target_attcode"=>"name")));

		MetaModel::Init_AddAttribute(new AttributeString("value", array("label"=>"Value expression", "description"=>"OQL expression (using \$user) | constant", "allowed_values"=>null, "sql"=>"value", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));

		//MetaModel::Init_InheritFilters();
		MetaModel::Init_AddFilterFromAttribute("dimensionid");
		MetaModel::Init_AddFilterFromAttribute("profileid");
	}

	public function ProjectUser(URP_Users $oUser)
	{
		// #@# to be implemented
	}
}

class URP_ClassProjection extends DBObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "addon/userrights",
			"name" => "class_projection",
			"description" => "class projections",
			"key_type" => "autoincrement",
			"key_label" => "",
			"name_attcode" => "",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_urp_classprojection",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		//MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeExternalKey("dimensionid", array("targetclass"=>"URP_Dimensions", "jointype"=> "", "label"=>"Dimension", "description"=>"application dimension", "allowed_values"=>null, "sql"=>"dimensionid", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("dimension", array("label"=>"Dimension", "description"=>"application dimension", "allowed_values"=>null, "extkey_attcode"=> 'dimensionid', "target_attcode"=>"name")));

		MetaModel::Init_AddAttribute(new AttributeString("class", array("label"=>"Class", "description"=>"Target class", "allowed_values"=>null, "sql"=>"class", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));

		MetaModel::Init_AddAttribute(new AttributeString("value", array("label"=>"Value expression", "description"=>"OQL expression (using \$this) | constant", "allowed_values"=>null, "sql"=>"value", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));

		//MetaModel::Init_InheritFilters();
		MetaModel::Init_AddFilterFromAttribute("dimensionid");
		MetaModel::Init_AddFilterFromAttribute("class");
	}
}

class URP_ClassGrant extends DBObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "addon/userrights",
			"name" => "class_permission",
			"description" => "permissions on classes",
			"key_type" => "autoincrement",
			"key_label" => "",
			"name_attcode" => "",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_urp_grant_classes",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		//MetaModel::Init_InheritAttributes();

		// Common to all grant classes (could be factorized by class inheritence, but this has to be benchmarked)
		MetaModel::Init_AddAttribute(new AttributeExternalKey("profileid", array("targetclass"=>"URP_Profiles", "jointype"=> "", "label"=>"Profile", "description"=>"usage profile", "allowed_values"=>null, "sql"=>"profileid", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("profile", array("label"=>"Profile", "description"=>"usage profile", "allowed_values"=>null, "extkey_attcode"=> 'profileid', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeString("class", array("label"=>"class", "description"=>"class name", "allowed_values"=>null, "sql"=>"class", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("permission", array("label"=>"permission", "description"=>"allowed or not allowed?", "allowed_values"=>new ValueSetEnum('yes,no'), "sql"=>"permission", "default_value"=>"yes", "is_null_allowed"=>false, "depends_on"=>array())));

		MetaModel::Init_AddAttribute(new AttributeString("action", array("label"=>"action", "description"=>"operations to perform on the given class", "allowed_values"=>null, "sql"=>"action", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));

		//MetaModel::Init_InheritFilters();
		// Common to all grant classes (could be factorized by class inheritence, but this has to be benchmarked)
		MetaModel::Init_AddFilterFromAttribute("profileid");
		MetaModel::Init_AddFilterFromAttribute("profile");
		MetaModel::Init_AddFilterFromAttribute("class");

		MetaModel::Init_AddFilterFromAttribute("action");
	}
}

class URP_StimulusGrant extends DBObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "addon/userrights",
			"name" => "stimulus_permission",
			"description" => "permissions on stimilus in the life cycle of the object",
			"key_type" => "autoincrement",
			"key_label" => "",
			"name_attcode" => "",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_urp_grant_stimulus",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		//MetaModel::Init_InheritAttributes();

		// Common to all grant classes (could be factorized by class inheritence, but this has to be benchmarked)
		MetaModel::Init_AddAttribute(new AttributeExternalKey("profileid", array("targetclass"=>"URP_Profiles", "jointype"=> "", "label"=>"Profile", "description"=>"usage profile", "allowed_values"=>null, "sql"=>"profileid", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("profile", array("label"=>"Profile", "description"=>"usage profile", "allowed_values"=>null, "extkey_attcode"=> 'profileid', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeString("class", array("label"=>"class", "description"=>"class name", "allowed_values"=>null, "sql"=>"class", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("permission", array("label"=>"permission", "description"=>"allowed or not allowed?", "allowed_values"=>new ValueSetEnum('yes,no'), "sql"=>"permission", "default_value"=>"yes", "is_null_allowed"=>false, "depends_on"=>array())));

		MetaModel::Init_AddAttribute(new AttributeString("stimulus", array("label"=>"action", "description"=>"operations to perform on the given class", "allowed_values"=>null, "sql"=>"action", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));

		//MetaModel::Init_InheritFilters();
		// Common to all grant classes (could be factorized by class inheritence, but this has to be benchmarked)
		MetaModel::Init_AddFilterFromAttribute("profileid");
		MetaModel::Init_AddFilterFromAttribute("profile");
		MetaModel::Init_AddFilterFromAttribute("class");

		MetaModel::Init_AddFilterFromAttribute("stimulus");
	}
}

class URP_AttributeGrant extends DBObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "addon/userrights",
			"name" => "attribute_permission",
			"description" => "permissions at the attributes level",
			"key_type" => "autoincrement",
			"key_label" => "",
			"name_attcode" => "",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_urp_grant_attributes",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		//MetaModel::Init_InheritAttributes();

		// Common to all grant classes (could be factorized by class inheritence, but this has to be benchmarked)
		MetaModel::Init_AddAttribute(new AttributeExternalKey("profileid", array("targetclass"=>"URP_Profiles", "jointype"=> "", "label"=>"Profile", "description"=>"usage profile", "allowed_values"=>null, "sql"=>"profileid", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("profile", array("label"=>"Profile", "description"=>"usage profile", "allowed_values"=>null, "extkey_attcode"=> 'profileid', "target_attcode"=>"name")));
		MetaModel::Init_AddAttribute(new AttributeString("class", array("label"=>"class", "description"=>"class name", "allowed_values"=>null, "sql"=>"class", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("permission", array("label"=>"permission", "description"=>"allowed or not allowed?", "allowed_values"=>new ValueSetEnum('yes,no'), "sql"=>"permission", "default_value"=>"yes", "is_null_allowed"=>false, "depends_on"=>array())));

		MetaModel::Init_AddAttribute(new AttributeString("attcode", array("label"=>"attribute", "description"=>"attribute code", "allowed_values"=>null, "sql"=>"attcode", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("action", array("label"=>"action", "description"=>"operations to perform on the given class", "allowed_values"=>null, "sql"=>"action", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));

		//MetaModel::Init_InheritFilters();
		// Common to all grant classes (could be factorized by class inheritence, but this has to be benchmarked)
		MetaModel::Init_AddFilterFromAttribute("profileid");
		MetaModel::Init_AddFilterFromAttribute("profile");
		MetaModel::Init_AddFilterFromAttribute("class");

		MetaModel::Init_AddFilterFromAttribute("attcode");
		MetaModel::Init_AddFilterFromAttribute("action");
	}
}




class UserRightsProfile extends UserRightsAddOnAPI
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
	public function CreateAdministrator($sAdminUser, $sAdminPwd, $sAdminEmail, $sFirstName, $sLastName, $sPhoneNumber)
	{
		// Maybe we should check that no other user with userid == 0 exists
		$oUser = new URP_Users();
		$oUser->Set('login', $sAdminUser);
		$oUser->Set('password', $sAdminPwd);
		$oUser->Set('email', $sAdminEmail);
		$oUser->Set('firstname', $sFirstName);
		$oUser->Set('lastname', $sLastName);
		$oUser->Set('phonenumber', $sPhoneNumber);
		$oUser->Set('userid', 1); // one is for root !
		$iUserId = $oUser->DBInsertNoReload();
		$this->SetupUser($iUserId, true);
		return true;
	}

	public function Setup()
	{
		// Dimensions/Profiles/Classes/Attributes/Stimuli could be added anytime
		// This procedure will then update the matrix with expected default values
		//

		$oProfileSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT URP_Profiles"));
		while ($oProfile = $oProfileSet->Fetch())
		{
			$this->SetupProfile($oProfile);
		}

		$oDimensionSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT URP_Dimensions"));
		while ($oDimension = $oDimensionSet->Fetch())
		{
			$this->SetupDimension($oDimension);
		}
		return true;
	}

	protected function SetupDimension($oDimension, $bNewDimension = false)
	{
		$iDimensionId = $oDimension->GetKey();

		// Create projections, for any class where it applies
		//
		foreach(array('bizmodel', 'application', 'gui', 'core/cmdb') as $sCategory)
		{
			foreach (MetaModel::GetClasses($sCategory) as $sClass)
			{
				if ($bNewDimension)
				{
					$bAddCell = true;
				}
				else
				{
					$oSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT URP_ClassProjection WHERE class = '$sClass' AND dimensionid = $iDimensionId"));
					$bAddCell = ($oSet->Count() < 1);
				}
				if ($bAddCell)
				{
					// Create a new entry
					$oCProj = MetaModel::NewObject("URP_ClassProjection");
					$oCProj->Set("dimensionid", $iDimensionId);
					$oCProj->Set("class", $sClass);
					$oCProj->Set("value", "true");
					$iId = $oCProj->DBInsertNoReload();
				}
			}
		}
		// Create projections, for any existing profile
		//
		$oProfileSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT URP_Profiles"));
		while ($oProfile = $oProfileSet->Fetch())
		{
			$iProfileId = $oProfile->GetKey();
			if ($bNewDimension)
			{
				$bAddCell = true;
			}
			else
			{
				$oSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT URP_ProfileProjection WHERE dimensionid = $iDimensionId AND profileid = $iProfileId"));
				$bAddCell = ($oSet->Count() < 1);
			}
			if ($bAddCell)
			{
				// Create a new entry
				$oDProj = MetaModel::NewObject("URP_ProfileProjection");
				$oDProj->Set("dimensionid", $iDimensionId);
				$oDProj->Set("profileid", $iProfileId);
				$oDProj->Set("value", "true");
				$iId = $oDProj->DBInsertNoReload();
			}
		}
	}

	protected function SetupProfile($oProfile, $bNewProfile = false)
	{
		$iProfileId = $oProfile->GetKey();

		// Create grant records, for any class where it applies
		//
		foreach(array('bizmodel', 'application', 'gui', 'core/cmdb') as $sCategory)
		{
			foreach (MetaModel::GetClasses($sCategory) as $sClass)
			{
				foreach (self::$m_aActionCodes as $iActionCode => $sAction)
				{
					if ($bNewProfile)
					{
						$bAddCell = true;
					}
					else
					{
						$oSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT URP_ClassGrant WHERE class = '$sClass' AND action = '$sAction' AND profileid = $iProfileId"));
						$bAddCell = ($oSet->Count() < 1);
					}
					if ($bAddCell)
					{
						// Create a new entry
						$oMyClassGrant = MetaModel::NewObject("URP_ClassGrant");
						$oMyClassGrant->Set("profileid", $iProfileId);
						$oMyClassGrant->Set("class", $sClass);
						$oMyClassGrant->Set("action", $sAction);
						$oMyClassGrant->Set("permission", "yes");
						$iId = $oMyClassGrant->DBInsertNoReload();
					}
				}
				foreach (MetaModel::EnumStimuli($sClass) as $sStimulusCode => $oStimulus)
				{
					if ($bNewProfile)
					{
						$bAddCell = true;
					}
					else
					{
						$oSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT URP_StimulusGrant WHERE class = '$sClass' AND stimulus = '$sStimulusCode' AND profileid = $iProfileId"));
						$bAddCell = ($oSet->Count() < 1);
					}
					if ($bAddCell)
					{
						// Create a new entry
						$oMyClassGrant = MetaModel::NewObject("URP_StimulusGrant");
						$oMyClassGrant->Set("profileid", $iProfileId);
						$oMyClassGrant->Set("class", $sClass);
						$oMyClassGrant->Set("stimulus", $sStimulusCode);
						$oMyClassGrant->Set("permission", "yes");
						$iId = $oMyClassGrant->DBInsertNoReload();
					}
				}
				foreach (MetaModel::GetAttributesList($sClass) as $sAttCode)
				{
					if ($bNewProfile)
					{
						$bAddCell = true;
					}
					else
					{
						$oSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT URP_AttributeGrant WHERE class = '$sClass' AND attcode = '$sAttCode' AND profileid = $iProfileId"));
						$bAddCell = ($oSet->Count() < 1);
					}
					if ($bAddCell)
					{
						foreach (array('read', 'modify') as $sAction)
						{
							// Create a new entry
							$oMyAttGrant = MetaModel::NewObject("URP_AttributeGrant");
							$oMyAttGrant->Set("profileid", $iProfileId);
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
		// Create the "My Bookmarks" menu item (parent_id = 0, rank = 6)
		if ($bNewProfile)
		{
			$bAddMenu = true;
		}
		else
		{
			//$oSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT menuNode WHERE type = 'user' AND parent_id = 0 AND user_id = $iUserId"));
			//$bAddMenu = ($oSet->Count() < 1);
		}
		$bAddMenu = false;
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
	}


	public function Init()
	{
		// Could be loaded in a shared memory (?)
		return true;
	}

	public function CheckCredentials($sUserName, $sPassword)
	{
		$oSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT URP_Users WHERE login = '$sUserName'"));
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

		$oSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT URP_ClassGrant WHERE class = '$sClass' AND action = '$sAction' AND login = '$sUserName'"));
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

		$oSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT URP_AttributeGrant WHERE URP_AttributeGrant.class = '$sClass' AND URP_AttributeGrant.attcode = '$sAttCode' AND URP_AttributeGrant.action = '$sAction' AND URP_AttributeGrant.login = '$sUserName'"));
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
		$oSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT URP_StimulusGrant WHERE class = '$sClass' AND stimulus = '$sStimulusCode' AND login = '$sUserName'"));
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

UserRights::SelectModule('UserRightsProfile');

?>

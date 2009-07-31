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
		MetaModel::Init_AddAttribute(new AttributeString("type", array("label"=>"type", "description"=>"class name or data type (projection unit)", "allowed_values"=>new ValueSetEnumClasses('bizmodel', 'String,Integer'), "sql"=>"type", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));

		//MetaModel::Init_InheritFilters();
		MetaModel::Init_AddFilterFromAttribute("name");
		MetaModel::Init_AddFilterFromAttribute("description");
		MetaModel::Init_AddFilterFromAttribute("type");
	}

	public function CheckProjectionSpec($oProjectionSpec)
	{
		$sExpression = $oProjectionSpec->Get('value');
		$sAttribute = $oProjectionSpec->Get('attribute');

		// Shortcut: "any value" or "no value" means no projection
		if (empty($sExpression)) return;
		if ($sExpression == '<any>') return;

		// 1st - compute the data type for the dimension
		//
		$sType = $this->Get('type');
		if (MetaModel::IsValidClass($sType))
		{
			$sExpectedType = $sType;
		}
		else
		{
			$sExpectedType = '_scalar_';
		}

		// 2nd - compute the data type for the projection
		//
		$bIsOql = true;
		$sExpressionClass = '';
		try
		{
			$oObjectSearch = DBObjectSearch::FromOQL($sExpression);
			$sExpressionClass = $oObjectSearch->GetClass();
		}
		catch (OqlException $e)
		{
			$bIsOql = false;
		}
		if ($bIsOql)
		{
			if (empty($sAttribute))
			{
				$sFoundType = $sExpressionClass;
			}
			else
			{
				if (!MetaModel::IsValidAttCode($sExpressionClass, $sAttribute))
				{
					throw new CoreException('Unkown attribute code in projection specification', array('found' => $sAttribute, 'expecting' => MetaModel::GetAttributesList($sExpressionClass), 'class' => $sExpressionClass, 'projection' => $oProjectionSpec));
				}
				$oAttDef = MetaModel::GetAttributeDef($sExpressionClass, $sAttribute);
				if ($oAttDef->IsExternalKey())
				{
					$sFoundType = $oAttDef->GetTargetClass();
				}
				else
				{
					$sFoundType = '_scalar_';
				}
			}
		}
		else
		{
			$sFoundType = '_scalar_';
		}

		// Compare the dimension type and projection type
		if ($sFoundType != $sExpectedType)
		{
			throw new CoreException('Wrong type in projection specification', array('found' => $sFoundType, 'expecting' => $sExpectedType, 'expression' => $sExpression, 'attribute' => $sAttribute, 'projection' => $oProjectionSpec));
		}
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

		MetaModel::Init_AddAttribute(new AttributeString("value", array("label"=>"Value expression", "description"=>"OQL expression (using \$user) | constant | <any>", "allowed_values"=>null, "sql"=>"value", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("attribute", array("label"=>"Attribute", "description"=>"Target attribute code (optional)", "allowed_values"=>null, "sql"=>"attribute", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));

		//MetaModel::Init_InheritFilters();
		MetaModel::Init_AddFilterFromAttribute("dimensionid");
		MetaModel::Init_AddFilterFromAttribute("profileid");
	}

	public function ProjectUser(URP_Users $oUser)
	{
		$sExpr = $this->Get('value');
		if (strtolower(substr($sExpr, 0, 6)) == 'select')
		{ 
			$sColumn = $this->Get('attribute');
			// SELECT...
			$oValueSetDef = new ValueSetObjects($sExpr, $sColumn);
			$aValues = $oValueSetDef->GetValues(array('user' => $oUser), '');
			$aRes = array_values($aValues);
		}
		elseif ($sExpr == '<any>')
		{
			$aRes = null;
		}
		else
		{
			// Constant value(s)
			$aRes = explode(';', trim($sExpr));
		}
		return $aRes;
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

		MetaModel::Init_AddAttribute(new AttributeString("value", array("label"=>"Value expression", "description"=>"OQL expression (using \$this) | constant | <any>", "allowed_values"=>null, "sql"=>"value", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("attribute", array("label"=>"Attribute", "description"=>"Target attribute code (optional)", "allowed_values"=>null, "sql"=>"attribute", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));

		//MetaModel::Init_InheritFilters();
		MetaModel::Init_AddFilterFromAttribute("dimensionid");
		MetaModel::Init_AddFilterFromAttribute("class");
	}

	public function ProjectObject($oObject)
	{
		$sExpr = $this->Get('value');
		if (strtolower(substr($sExpr, 0, 6)) == 'select')
		{ 
			$sColumn = $this->Get('attribute');
			// SELECT...
			$oValueSetDef = new ValueSetObjects($sExpr, $sColumn);
			$aValues = $oValueSetDef->GetValues(array('user' => $oObject), '');
			$aRes = array_values($aValues);
		}
		elseif ($sExpr == '<any>')
		{
			$aRes = null;
		}
		else
		{
			// Constant value(s)
			$aRes = explode(';', trim($sExpr));
		}
		return $aRes;
	}
}

class URP_ActionGrant extends DBObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "addon/userrights",
			"name" => "action_permission",
			"description" => "permissions on classes",
			"key_type" => "autoincrement",
			"key_label" => "",
			"name_attcode" => "",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_urp_grant_actions",
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

		MetaModel::Init_AddAttribute(new AttributeString("stimulus", array("label"=>"stimulus", "description"=>"stimulus code", "allowed_values"=>null, "sql"=>"action", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));

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

		MetaModel::Init_AddAttribute(new AttributeExternalKey("actiongrantid", array("targetclass"=>"URP_ActionGrant", "jointype"=> "", "label"=>"Action grant", "description"=>"action grant", "allowed_values"=>null, "sql"=>"actiongrantid", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("attcode", array("label"=>"attribute", "description"=>"attribute code", "allowed_values"=>null, "sql"=>"attcode", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));

		//MetaModel::Init_InheritFilters();
		MetaModel::Init_AddFilterFromAttribute("actiongrantid");
		MetaModel::Init_AddFilterFromAttribute("attcode");
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
					$oSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT URP_ClassProjection WHERE class = :class AND dimensionid = :dimension"), array(), array('class'=>$sClass, 'dimension'=>$iDimensionId));
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
				$oSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT URP_ProfileProjection WHERE dimensionid = :dimension AND profileid = :profile"), array(), array('dimension'=>$iDimensionId, 'profile'=>$iProfileId));
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
						$oSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT URP_ActionGrant WHERE class = :class AND action = :action AND profileid = :profile"), array(), array('class'=>$sClass, 'action'=>$sAction, 'profile'=>$iProfileId));
						$bAddCell = ($oSet->Count() < 1);
					}
					if ($bAddCell)
					{
						// Create a new entry
						$oMyActionGrant = MetaModel::NewObject("URP_ActionGrant");
						$oMyActionGrant->Set("profileid", $iProfileId);
						$oMyActionGrant->Set("class", $sClass);
						$oMyActionGrant->Set("action", $sAction);
						$oMyActionGrant->Set("permission", "yes");
						$iId = $oMyActionGrant->DBInsertNoReload();
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
						$oSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT URP_StimulusGrant WHERE class = :class AND stimulus = :stimulus AND profileid = :profile"), array(), array('class'=>$sClass, 'stimulus'=>$sStimulusCode, 'profile'=>$iProfileId));
						$bAddCell = ($oSet->Count() < 1);
					}
					if ($bAddCell)
					{
						// Create a new entry
						$oMyStGrant = MetaModel::NewObject("URP_StimulusGrant");
						$oMyStGrant->Set("profileid", $iProfileId);
						$oMyStGrant->Set("class", $sClass);
						$oMyStGrant->Set("stimulus", $sStimulusCode);
						$oMyStGrant->Set("permission", "yes");
						$iId = $oMyStGrant->DBInsertNoReload();
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
		MetaModel::RegisterPlugin('userrights', 'ACbyProfile', array($this, 'CacheData'));
	}

	protected $m_aUsers = array(); // id -> object
	protected $m_aDimensions = array(); // id -> object
	protected $m_aClassProj = array(); // class,dimensionid -> object
	protected $m_aProfiles = array(); // id -> object
	protected $m_aUserProfiles = array(); // userid,profileid -> object
	protected $m_aProPro = array(); // profileid,dimensionid -> object

	protected $m_aClassActionGrants = array(); // profile, class, action -> permission
	protected $m_aObjectActionGrants = array(); // userid, class, id, action -> permission, list of attributes

	public function CacheData()
	{
		// Could be loaded in a shared memory (?)

		$oUserSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT URP_Users"));
		while ($oUser = $oUserSet->Fetch())
		{
			$this->m_aUsers[$oUser->GetKey()] = $oUser; 
		}

		$oDimensionSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT URP_Dimensions"));
		while ($oDimension = $oDimensionSet->Fetch())
		{
			$this->m_aDimensions[$oDimension->GetKey()] = $oDimension; 
		}
		
		$oClassProjSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT URP_ClassProjection"));
		while ($oClassProj = $oClassProjSet->Fetch())
		{
			$this->m_aClassProjs[$oClassProj->Get('class')][$oClassProj->Get('dimensionid')] = $oClassProj; 
		}

		$oProfileSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT URP_Profiles"));
		while ($oProfile = $oProfileSet->Fetch())
		{
			$this->m_aProfiles[$oProfile->GetKey()] = $oProfile; 
		}

		$oUserProfileSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT URP_UserProfile"));
		while ($oUserProfile = $oUserProfileSet->Fetch())
		{
			$this->m_aUserProfiles[$oUserProfile->Get('userid')][$oUserProfile->Get('profileid')] = $oUserProfile; 
		}

		$oProProSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT URP_ProfileProjection"));
		while ($oProPro = $oProProSet->Fetch())
		{
			$this->m_aProPros[$oProPro->Get('profileid')][$oProPro->Get('dimensionid')] = $oProPro; 
		}

/*
		echo "<pre>\n";
		print_r($this->m_aUsers);
		print_r($this->m_aDimensions);
		print_r($this->m_aClassProjs);
		print_r($this->m_aProfiles);
		print_r($this->m_aUserProfiles);
		print_r($this->m_aProPros);
		echo "</pre>\n";
exit;
*/

		return true;
	}

	public function CheckCredentials($sUserName, $sPassword)
	{
		$oSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT URP_Users WHERE login = :login"), array(), array('login' => $sUserName));
		if ($oSet->Count() < 1)
		{
		// todo: throw an exception?
			return false;
		}

		$oUser = $oSet->Fetch();
		if ($oUser->Get('password') == $sPassword)
		{
			return $oUser->GetKey();
		}
		// todo: throw an exception?
		return false;
	}

	public function GetUserId($sUserName)
	{
		$oSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT URP_Users WHERE login = :login"), array(), array('login' => $sUserName));
		if ($oSet->Count() < 1)
		{
		// todo: throw an exception?
			return false;
		}

		$oUser = $oSet->Fetch();
		return $oUser->GetKey();
	}

	public function GetFilter($sUserName, $sClass)
	{
		$oNullFilter  = new DBObjectSearch($sClass);
		return $oNullFilter;
	}

	protected function GetClassActionGrant($iProfile, $sClass, $sAction)
	{
		$aTest = @$this->m_aClassActionGrants[$iProfile][$sClass][$sAction];
		if (isset($aTest)) return $aTest;

		// Get the permission for this profile/class/action
		$oSearch = DBObjectSearch::FromOQL("SELECT URP_ActionGrant WHERE class = :class AND action = :action AND profileid = :profile");
		$oSet = new DBObjectSet($oSearch, array(), array('class'=>$sClass, 'action'=>$sAction, 'profile'=>$iProfile));
		if ($oSet->Count() < 1)
		{
			return null;
		}
		$oGrantRecord = $oSet->Fetch();

		$this->m_aClassActionGrants[$iProfile][$sClass][$sAction] = $oGrantRecord;
		return $oGrantRecord;
	}

	protected function GetObjectActionGrant($oUser, $sClass, $iActionCode, $oObject)
	{
		// load and cache permissions for the current user on the given object
		//
		$aTest = @$this->m_aObjectActionGrants[$oUser->GetKey()][$sClass][$oObject->GetKey][$iActionCode];
		if (is_array($aTest)) return $aTest;

		$sAction = self::$m_aActionCodes[$iActionCode];

		$iInstancePermission = UR_ALLOWED_NO;
		$aAttributes = array();
		foreach($this->GetMatchingProfiles($oUser, $oObject) as $iProfile)
		{
			$oGrantRecord = $this->GetClassActionGrant($iProfile, $sClass, $sAction);
			if (is_null($oGrantRecord))
			{
				continue; // loop to the next profile
			}
			elseif ($oGrantRecord->Get('permission') == 'yes')
			{
				$iInstancePermission = UR_ALLOWED_YES;

				// merge the list of attributes allowed for this profile
				$oSearch = DBObjectSearch::FromOQL("SELECT URP_AttributeGrant WHERE actiongrantid = :actiongrantid");
				$oSet = new DBObjectSet($oSearch, array(), array('actiongrantid' => $oGrantRecord->GetKey()));
				$aAttributes = array_merge($aAttributes, $oSet->GetColumnAsArray('attcode', false));
			}
		}

		$aRes = array(
			'permission' => $iInstancePermission,
			'attributes' => $aAttributes,
		);
		$this->m_aObjectActionGrants[$oUser->GetKey()][$sClass][$oObject->GetKey()][$iActionCode] = $aRes;
		return $aRes;
	}
	
	public function IsActionAllowed($iUserId, $sClass, $iActionCode, dbObjectSet $oInstances)
	{
		$oUser = $this->m_aUsers[$iUserId];

		$oInstances->Rewind();
		while($oObject = $oInstances->Fetch())
		{
			$aObjectPermissions = $this->GetObjectActionGrant($oUser, $sClass, $iActionCode, $oObject);

			$iInstancePermission = $aObjectPermissions['permission'];
			if (isset($iGlobalPermission))
			{
				if ($iInstancePermission != $iGlobalPermission)
				{
					$iGlobalPermission = UR_ALLOWED_DEPENDS;
				}
			}
			else
			{
				$iGlobalPermission = $iInstancePermission;
			}
		}
		if (isset($iGlobalPermission))
		{
			return $iGlobalPermission;
		}
		else
		{
			return UR_ALLOWED_NO;
		}
	}

	public function IsActionAllowedOnAttribute($iUserId, $sClass, $sAttCode, $iActionCode, dbObjectSet $oInstances)
	{
		$oUser = $this->m_aUsers[$iUserId];

		$oInstances->Rewind();
		while($oObject = $oInstances->Fetch())
		{
			$aObjectPermissions = $this->GetObjectActionGrant($oUser, $sClass, $iActionCode, $oObject);

			$aAttributes = $aObjectPermissions['attributes'];
			if (in_array($sAttCode, $aAttributes))
			{
				$iInstancePermission = $aObjectPermissions['permission'];
			}
			else
			{
				$iInstancePermission = UR_ALLOWED_NO; 
			}

			if (isset($iGlobalPermission))
			{
				if ($iInstancePermission != $iGlobalPermission)
				{
					$iGlobalPermission = UR_ALLOWED_DEPENDS;
				}
			}
			else
			{
				$iGlobalPermission = $iInstancePermission;
			}
		}
		if (isset($iGlobalPermission))
		{
			return $iGlobalPermission;
		}
		else
		{
			return UR_ALLOWED_NO;
		}
	}

	public function IsStimulusAllowed($iUserId, $sClass, $sStimulusCode, dbObjectSet $oInstances)
	{
		$oUser = $this->m_aUsers[$iUserId];

		// Note: this code is VERY close to the code of IsActionAllowed()

		$oInstances->Rewind();
		while($oObject = $oInstances->Fetch())
		{
			$iInstancePermission = UR_ALLOWED_NO;
			foreach($this->GetMatchingProfiles($oUser, $oObject) as $iProfile)
			{
				// Get the permission for this profile/class/stimulus
				$oSearch = DBObjectSearch::FromOQL("SELECT URP_StimulusGrant WHERE class = :class AND stimulus = :stimulus AND profileid = :profile");
				$oSet = new DBObjectSet($oSearch, array(), array('class'=>$sClass, 'stimulus'=>$sStimulusCode, 'profile'=>$iProfile));
				if ($oSet->Count() < 1)
				{
					return UR_ALLOWED_NO;
				}
		
				$oGrantRecord = $oSet->Fetch();
				$sPermission = $oGrantRecord->Get('permission');
				if ($sPermission == 'yes')
				{
					$iInstancePermission = UR_ALLOWED_YES;
				}
			}
			if (isset($iGlobalPermission))
			{
				if ($iInstancePermission != $iGlobalPermission)
				{
					$iGlobalPermission = UR_ALLOWED_DEPENDS;
				}
			}
			else
			{
				$iGlobalPermission = $iInstancePermission;
			}
		}
		if (isset($iGlobalPermission))
		{
			return $iGlobalPermission;
		}
		else
		{
			return UR_ALLOWED_NO;
		}
	}

	protected function GetMatchingProfilesByDim($oUser, $oObject, $oDimension)
	{
		//
		// List profiles for which the user projection overlaps the object projection in the given dimension
		//
		$iUser = $oUser->GetKey();
		$sClass = get_class($oObject);
		$iPKey = $oObject->GetKey();
		$iDimension = $oDimension->GetKey();

		$aObjectProjection = $this->m_aClassProjs[$sClass][$iDimension]->ProjectObject($oObject);

		$aRes = array();
		foreach ($this->m_aUserProfiles[$iUser] as $iProfile => $oProfile)
		{
			if (is_null($aObjectProjection))
			{
				$aRes[] = $iProfile;
			}
			else
			{
				// user projection to be cached on a given page !
				$aUserProjection = $this->m_aProPros[$iProfile][$iDimension]->ProjectUser($oUser);
				
				if (is_null($aUserProjection))
				{
					$aRes[] = $iProfile;
				}
				else
				{
					$aMatchingValues = array_intersect($aObjectProjection, $aUserProjection);
					if (count($aMatchingValues) > 0)
					{
						$aRes[] = $iProfile;
					}
				}
			}
		}
		return $aRes;
	}

	protected $m_aMatchingProfiles = array(); // cache of the matching profiles for a given user/object
	
	protected function GetMatchingProfiles($oUser, $oObject)
	{
		$iUser = $oUser->GetKey();
		$sClass = get_class($oObject);
		$iObject = $oObject->GetKey();
		//
		// List profiles for which the user projection overlaps the object projection in each and every dimension
		// Caches the result
		//
		$aTest = @$this->m_aMatchingProfiles[$iUser][$sClass][$iObject];
		if (is_array($aTest))
		{
			return $aTest;
		}

		$aProfileRes = array();
		foreach ($this->m_aDimensions as $iDimension => $oDimension)
		{
			foreach ($this->GetMatchingProfilesByDim($oUser, $oObject, $oDimension) as $iProfile)
			{
				@$aProfileRes[$iProfile] += 1;
			}
		}

		$aRes = array();
		$iDimCount = count($this->m_aDimensions);
		foreach ($aProfileRes as $iProfile => $iMatches)
		{
			if ($iMatches == $iDimCount)
			{
				$aRes[] = $iProfile;
			}
		}
		$this->m_aMatchingProfiles[$iUser][$sClass][$iObject] = $aRes;
		return $aRes; 
	}
}

UserRights::SelectModule('UserRightsProfile');

?>

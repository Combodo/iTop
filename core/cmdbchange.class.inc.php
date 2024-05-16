<?php
/**
 * Persistent class (internal) cmdbChange
 *
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

use Combodo\iTop\Core\CMDBChange\CMDBChangeOrigin;

/**
 * A change as requested/validated at once by user, may groups many atomic changes 
 *
 * @package     iTopORM
 */
class CMDBChange extends DBObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category"            => "core/cmdb, grant_by_profile",
			"key_type"            => "autoincrement",
			"name_attcode"        => "date",
			"state_attcode"       => "",
			"reconc_keys"         => array(),
			"db_table"            => "priv_change",
			"db_key_field"        => "id",
			"db_finalclass_field" => "",
			'indexes'             => array(
				array('origin'),
			),
		);
		MetaModel::Init_Params($aParams);
		//MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeDateTime("date", array("allowed_values"=>null, "sql"=>"date", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("userinfo", array("allowed_values"=>null, "sql"=>"userinfo", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalKey("user_id", array("allowed_values"=>null, "sql"=>"user_id", "targetclass"=>"User", "is_null_allowed"=>true, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("origin", array("allowed_values"=>new ValueSetEnum(implode(',', [CMDBChangeOrigin::INTERACTIVE, CMDBChangeOrigin::CSV_INTERACTIVE, CMDBChangeOrigin::CSV_IMPORT, CMDBChangeOrigin::WEBSERVICE_SOAP, CMDBChangeOrigin::WEBSERVICE_REST, CMDBChangeOrigin::SYNCHRO_DATA_SOURCE, CMDBChangeOrigin::EMAIL_PROCESSING, CMDBChangeOrigin::CUSTOM_EXTENSION])), "sql"=>"origin", "default_value"=>CMDBChangeOrigin::INTERACTIVE, "is_null_allowed"=>true, "depends_on"=>array())));
	}

	/**
	 * Helper to keep track of the author of a given change,
	 * taking into account a variety of cases (contact attached or not, impersonation)
	 *
	 * @return string
	 * @throws \OQLException
	 */
	public static function GetCurrentUserName()
	{
		if (UserRights::IsImpersonated())
		{
			$sUserString = Dict::Format('UI:Archive_User_OnBehalfOf_User', UserRights::GetRealUserFriendlyName(), UserRights::GetUserFriendlyName());
		}
		else
		{
			$sUserString = UserRights::GetUserFriendlyName();
		}
		return $sUserString;
	}

	/**
	 * Return the current user
	 *
	 * @return string|null
	 * @throws \OQLException
	 * @since 3.0.0
	 */
	public static function GetCurrentUserId()
	{
		// Note: We might have use only UserRights::GetRealUserId() as it would have done the same thing in the end
		return UserRights::IsImpersonated() ? UserRights::GetRealUserId() : UserRights::GetUserId();
	}

	public function GetUserName()
	{
		if (preg_match('/^(.*)\\(CSV\\)$/i', $this->Get('userinfo'), $aMatches))
		{
			$sUser = $aMatches[1];
		}
		else
		{
			$sUser = $this->Get('userinfo');
		}
		return $sUser;
	}
}

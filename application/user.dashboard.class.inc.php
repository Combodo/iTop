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
 * Store and retrieve user custom dashboards
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */
require_once(APPROOT.'/core/dbobject.class.php');

/**
 * This class is used to store, in a persistent manner, a dashboard edited by a user
 */
class UserDashboard extends DBObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "gui",
			"key_type" => "autoincrement",
			"name_attcode" => "user_id",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_app_dashboards",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
		);
		
		MetaModel::Init_Params($aParams);
		MetaModel::Init_AddAttribute(new AttributeExternalKey("user_id", array("targetclass"=>"User", "allowed_values"=>null, "sql"=>"user_id", "is_null_allowed"=>false, "on_target_delete"=>DEL_AUTO, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("menu_code", array("allowed_values"=>null, "sql"=>"menu_code", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeText("contents", array("allowed_values"=>null, "sql"=>"contents", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));
	}

	/**
	* Overloading this function here to secure a fix done right before the release
	* The real fix should be to implement this verb in DBObject	
	*/
	public function DBDeleteTracked(CMDBChange $oChange, $bSkipStrongSecurity = null, &$oDeletionPlan = null)
	{
		$this->DBDelete($oDeletionPlan);
	}
}
?>

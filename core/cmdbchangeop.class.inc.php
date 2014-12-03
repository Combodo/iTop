<?php
// Copyright (C) 2010-2013 Combodo SARL
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
 * Persistent classes (internal) : cmdbChangeOp and derived
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


/**
 * Various atomic change operations, to be tracked 
 *
 * @package     iTopORM
 */

class CMDBChangeOp extends DBObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "core/cmdb",
			"key_type" => "autoincrement",
			"name_attcode" => "change",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_changeop",
			"db_key_field" => "id",
			"db_finalclass_field" => "optype",
			'indexes' => array(
				array('objclass', 'objkey'),
			)
		);
		MetaModel::Init_Params($aParams);
		//MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeExternalKey("change", array("allowed_values"=>null, "sql"=>"changeid", "targetclass"=>"CMDBChange", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("date", array("allowed_values"=>null, "extkey_attcode"=>"change", "target_attcode"=>"date")));
		MetaModel::Init_AddAttribute(new AttributeExternalField("userinfo", array("allowed_values"=>null, "extkey_attcode"=>"change", "target_attcode"=>"userinfo")));
		MetaModel::Init_AddAttribute(new AttributeString("objclass", array("allowed_values"=>null, "sql"=>"objclass", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeInteger("objkey", array("allowed_values"=>null, "sql"=>"objkey", "default_value"=>0, "is_null_allowed"=>false, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('change', 'date', 'userinfo')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('change', 'date', 'userinfo')); // Attributes to be displayed for the complete details
	}

	/**
	 * Describe (as a text string) the modifications corresponding to this change
	 */	 
	public function GetDescription()
	{
		return '';
	}

	/**
	 * Safety net: in case the change is not given, let's guarantee that it will
	 * be set to the current ongoing change (or create a new one)	
	 */	
	protected function OnInsert()
	{
		if ($this->Get('change') <= 0)
		{
			$this->Set('change', CMDBObject::GetCurrentChange());
		}
		parent::OnInsert();
	}
}



/**
 * Record the creation of an object  
 *
 * @package     iTopORM
 */
class CMDBChangeOpCreate extends CMDBChangeOp
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "core/cmdb",
			"key_type" => "",
			"name_attcode" => "change",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_changeop_create",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
	}
	
	/**
	 * Describe (as a text string) the modifications corresponding to this change
	 */	 
	public function GetDescription()
	{
		return Dict::S('Change:ObjectCreated');
	}
}


/**
 * Record the deletion of an object 
 *
 * @package     iTopORM
 */
class CMDBChangeOpDelete extends CMDBChangeOp
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "core/cmdb",
			"key_type" => "",
			"name_attcode" => "change",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_changeop_delete",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		// Final class of the object (objclass must be set to the root class for efficiency purposes)
		MetaModel::Init_AddAttribute(new AttributeString("fclass", array("allowed_values"=>null, "sql"=>"fclass", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		// Last friendly name of the object
		MetaModel::Init_AddAttribute(new AttributeString("fname", array("allowed_values"=>null, "sql"=>"fname", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));
	}
	/**
	 * Describe (as a text string) the modifications corresponding to this change
	 */	 
	public function GetDescription()
	{
		return Dict::S('Change:ObjectDeleted');
	}
}


/**
 * Record the modification of an attribute (abstract)
 *
 * @package     iTopORM
 */
class CMDBChangeOpSetAttribute extends CMDBChangeOp
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "core/cmdb",
			"key_type" => "",
			"name_attcode" => "change",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_changeop_setatt",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeString("attcode", array("allowed_values"=>null, "sql"=>"attcode", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		
		// Display lists
		MetaModel::Init_SetZListItems('details', array('date', 'userinfo', 'attcode')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('date', 'userinfo', 'attcode')); // Attributes to be displayed for a list
	}
}

/**
 * Record the modification of a scalar attribute 
 *
 * @package     iTopORM
 */
class CMDBChangeOpSetAttributeScalar extends CMDBChangeOpSetAttribute
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "core/cmdb",
			"key_type" => "",
			"name_attcode" => "change",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_changeop_setatt_scalar",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeString("oldvalue", array("allowed_values"=>null, "sql"=>"oldvalue", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("newvalue", array("allowed_values"=>null, "sql"=>"newvalue", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
		
		// Display lists
		MetaModel::Init_SetZListItems('details', array('date', 'userinfo', 'attcode', 'oldvalue', 'newvalue')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('date', 'userinfo', 'attcode', 'oldvalue', 'newvalue')); // Attributes to be displayed for a list
	}
	
	/**
	 * Describe (as a text string) the modifications corresponding to this change
	 */	 
	public function GetDescription()
	{
		$sResult = '';
		$oTargetObjectClass = $this->Get('objclass');
		$oTargetObjectKey = $this->Get('objkey');
		$oTargetSearch = new DBObjectSearch($oTargetObjectClass);
		$oTargetSearch->AddCondition('id', $oTargetObjectKey, '=');

		$oMonoObjectSet = new DBObjectSet($oTargetSearch);
		if (UserRights::IsActionAllowedOnAttribute($this->Get('objclass'), $this->Get('attcode'), UR_ACTION_READ, $oMonoObjectSet) == UR_ALLOWED_YES)
		{
			if (!MetaModel::IsValidAttCode($this->Get('objclass'), $this->Get('attcode'))) return ''; // Protects against renamed attributes...

			$oAttDef = MetaModel::GetAttributeDef($this->Get('objclass'), $this->Get('attcode'));
			$sAttName = $oAttDef->GetLabel();
			$sNewValue = $this->Get('newvalue');
			$sOldValue = $this->Get('oldvalue');
			$sResult = $oAttDef->DescribeChangeAsHTML($sOldValue, $sNewValue);
		}
		return $sResult;
	}
}

/**
 * Record the modification of a blob
 *
 * @package     iTopORM
 */
class CMDBChangeOpSetAttributeBlob extends CMDBChangeOpSetAttribute
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "core/cmdb",
			"key_type" => "",
			"name_attcode" => "change",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_changeop_setatt_data",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeBlob("prevdata", array("depends_on"=>array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('date', 'userinfo', 'attcode')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('date', 'userinfo', 'attcode')); // Attributes to be displayed for a list
	}
	
	/**
	 * Describe (as a text string) the modifications corresponding to this change
	 */	 
	public function GetDescription()
	{
		// Temporary, until we change the options of GetDescription() -needs a more global revision
		$bIsHtml = true;
		
		$sResult = '';
		$oTargetObjectClass = $this->Get('objclass');
		$oTargetObjectKey = $this->Get('objkey');
		$oTargetSearch = new DBObjectSearch($oTargetObjectClass);
		$oTargetSearch->AddCondition('id', $oTargetObjectKey, '=');

		$oMonoObjectSet = new DBObjectSet($oTargetSearch);
		if (UserRights::IsActionAllowedOnAttribute($this->Get('objclass'), $this->Get('attcode'), UR_ACTION_READ, $oMonoObjectSet) == UR_ALLOWED_YES)
		{
			if (MetaModel::IsValidAttCode($this->Get('objclass'), $this->Get('attcode')))
			{
				$oAttDef = MetaModel::GetAttributeDef($this->Get('objclass'), $this->Get('attcode'));
				$sAttName = $oAttDef->GetLabel();
			}
			else
			{
				// The attribute was renamed or removed from the object ?
				$sAttName = $this->Get('attcode');
			}
			$oPrevDoc = $this->Get('prevdata');
			$sDocView = $oPrevDoc->GetAsHtml();
			$sDocView .= "<br/>".Dict::Format('UI:OpenDocumentInNewWindow_',$oPrevDoc->GetDisplayLink(get_class($this), $this->GetKey(), 'prevdata')).", \n";
			$sDocView .= Dict::Format('UI:DownloadDocument_', $oPrevDoc->GetDownloadLink(get_class($this), $this->GetKey(), 'prevdata'))."\n";
			//$sDocView = $oPrevDoc->GetDisplayInline(get_class($this), $this->GetKey(), 'prevdata');
			$sResult = Dict::Format('Change:AttName_Changed_PreviousValue_OldValue', $sAttName, $sDocView);
		}
		return $sResult;
	}
}
/**
 * Safely record the modification of one way encrypted password
 */
class CMDBChangeOpSetAttributeOneWayPassword extends CMDBChangeOpSetAttribute
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "core/cmdb",
			"key_type" => "",
			"name_attcode" => "change",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_changeop_setatt_pwd",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeOneWayPassword("prev_pwd", array("sql" => 'data', "default_value" => '', "is_null_allowed"=> true, "allowed_values" => null, "depends_on"=>array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('date', 'userinfo', 'attcode')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('date', 'userinfo', 'attcode')); // Attributes to be displayed for a list
	}
	
	/**
	 * Describe (as a text string) the modifications corresponding to this change
	 */	 
	public function GetDescription()
	{
		// Temporary, until we change the options of GetDescription() -needs a more global revision
		$bIsHtml = true;
		
		$sResult = '';
		$oTargetObjectClass = $this->Get('objclass');
		$oTargetObjectKey = $this->Get('objkey');
		$oTargetSearch = new DBObjectSearch($oTargetObjectClass);
		$oTargetSearch->AddCondition('id', $oTargetObjectKey, '=');

		$oMonoObjectSet = new DBObjectSet($oTargetSearch);
		if (UserRights::IsActionAllowedOnAttribute($this->Get('objclass'), $this->Get('attcode'), UR_ACTION_READ, $oMonoObjectSet) == UR_ALLOWED_YES)
		{
			if (MetaModel::IsValidAttCode($this->Get('objclass'), $this->Get('attcode')))
			{
				$oAttDef = MetaModel::GetAttributeDef($this->Get('objclass'), $this->Get('attcode'));
				$sAttName = $oAttDef->GetLabel();
			}
			else
			{
				// The attribute was renamed or removed from the object ?
				$sAttName = $this->Get('attcode');
			}
			$sResult = Dict::Format('Change:AttName_Changed', $sAttName);
		}
		return $sResult;
	}
}

/**
 * Safely record the modification of an encrypted field
 */
class CMDBChangeOpSetAttributeEncrypted extends CMDBChangeOpSetAttribute
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "core/cmdb",
			"key_type" => "",
			"name_attcode" => "change",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_changeop_setatt_encrypted",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeEncryptedString("prevstring", array("sql" => 'data', "default_value" => '', "is_null_allowed"=> true, "allowed_values" => null, "depends_on"=>array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('date', 'userinfo', 'attcode')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('date', 'userinfo', 'attcode')); // Attributes to be displayed for a list
	}
	
	/**
	 * Describe (as a text string) the modifications corresponding to this change
	 */	 
	public function GetDescription()
	{
		// Temporary, until we change the options of GetDescription() -needs a more global revision
		$bIsHtml = true;
		
		$sResult = '';
		$oTargetObjectClass = $this->Get('objclass');
		$oTargetObjectKey = $this->Get('objkey');
		$oTargetSearch = new DBObjectSearch($oTargetObjectClass);
		$oTargetSearch->AddCondition('id', $oTargetObjectKey, '=');

		$oMonoObjectSet = new DBObjectSet($oTargetSearch);
		if (UserRights::IsActionAllowedOnAttribute($this->Get('objclass'), $this->Get('attcode'), UR_ACTION_READ, $oMonoObjectSet) == UR_ALLOWED_YES)
		{
			if (MetaModel::IsValidAttCode($this->Get('objclass'), $this->Get('attcode')))
			{
				$oAttDef = MetaModel::GetAttributeDef($this->Get('objclass'), $this->Get('attcode'));
				$sAttName = $oAttDef->GetLabel();
			}
			else
			{
				// The attribute was renamed or removed from the object ?
				$sAttName = $this->Get('attcode');
			}
			$sPrevString = $this->Get('prevstring');
			$sResult = Dict::Format('Change:AttName_Changed_PreviousValue_OldValue', $sAttName, $sPrevString);
		}
		return $sResult;
	}
}

/**
 * Record the modification of a multiline string (text)
 *
 * @package     iTopORM
 */
class CMDBChangeOpSetAttributeText extends CMDBChangeOpSetAttribute
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "core/cmdb",
			"key_type" => "",
			"name_attcode" => "change",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_changeop_setatt_text",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeText("prevdata", array("allowed_values"=>null, "sql"=>"prevdata", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('date', 'userinfo', 'attcode')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('date', 'userinfo', 'attcode')); // Attributes to be displayed for a list
	}
	
	/**
	 * Describe (as a text string) the modifications corresponding to this change
	 */	 
	public function GetDescription()
	{
		// Temporary, until we change the options of GetDescription() -needs a more global revision
		$bIsHtml = true;
		
		$sResult = '';
		$oTargetObjectClass = $this->Get('objclass');
		$oTargetObjectKey = $this->Get('objkey');
		$oTargetSearch = new DBObjectSearch($oTargetObjectClass);
		$oTargetSearch->AddCondition('id', $oTargetObjectKey, '=');

		$oMonoObjectSet = new DBObjectSet($oTargetSearch);
		if (UserRights::IsActionAllowedOnAttribute($this->Get('objclass'), $this->Get('attcode'), UR_ACTION_READ, $oMonoObjectSet) == UR_ALLOWED_YES)
		{
			if (MetaModel::IsValidAttCode($this->Get('objclass'), $this->Get('attcode')))
			{
				$oAttDef = MetaModel::GetAttributeDef($this->Get('objclass'), $this->Get('attcode'));
				$sAttName = $oAttDef->GetLabel();
			}
			else
			{
				// The attribute was renamed or removed from the object ?
				$sAttName = $this->Get('attcode');
			}
			$sTextView = '<div>'.$this->GetAsHtml('prevdata').'</div>';

			//$sDocView = $oPrevDoc->GetDisplayInline(get_class($this), $this->GetKey(), 'prevdata');
			$sResult = Dict::Format('Change:AttName_Changed_PreviousValue_OldValue', $sAttName, $sTextView);
		}
		return $sResult;
	}
}

/**
 * Record the modification of a multiline string (text)
 *
 * @package     iTopORM
 */
class CMDBChangeOpSetAttributeLongText extends CMDBChangeOpSetAttribute
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "core/cmdb",
			"key_type" => "",
			"name_attcode" => "change",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_changeop_setatt_longtext",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeLongText("prevdata", array("allowed_values"=>null, "sql"=>"prevdata", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('date', 'userinfo', 'attcode')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('date', 'userinfo', 'attcode')); // Attributes to be displayed for a list
	}
	
	/**
	 * Describe (as a text string) the modifications corresponding to this change
	 */	 
	public function GetDescription()
	{
		// Temporary, until we change the options of GetDescription() -needs a more global revision
		$bIsHtml = true;
		
		$sResult = '';
		$oTargetObjectClass = $this->Get('objclass');
		$oTargetObjectKey = $this->Get('objkey');
		$oTargetSearch = new DBObjectSearch($oTargetObjectClass);
		$oTargetSearch->AddCondition('id', $oTargetObjectKey, '=');

		$oMonoObjectSet = new DBObjectSet($oTargetSearch);
		if (UserRights::IsActionAllowedOnAttribute($this->Get('objclass'), $this->Get('attcode'), UR_ACTION_READ, $oMonoObjectSet) == UR_ALLOWED_YES)
		{
			if (MetaModel::IsValidAttCode($this->Get('objclass'), $this->Get('attcode')))
			{
				$oAttDef = MetaModel::GetAttributeDef($this->Get('objclass'), $this->Get('attcode'));
				$sAttName = $oAttDef->GetLabel();
			}
			else
			{
				// The attribute was renamed or removed from the object ?
				$sAttName = $this->Get('attcode');
			}
			$sTextView = '<div>'.$this->GetAsHtml('prevdata').'</div>';

			//$sDocView = $oPrevDoc->GetDisplayInline(get_class($this), $this->GetKey(), 'prevdata');
			$sResult = Dict::Format('Change:AttName_Changed_PreviousValue_OldValue', $sAttName, $sTextView);
		}
		return $sResult;
	}
}

/**
 * Record the modification of a caselog (text)
 * since the caselog itself stores the history
 * of its entries, there is no need to duplicate
 * the text here
 *
 * @package     iTopORM
 */
class CMDBChangeOpSetAttributeCaseLog extends CMDBChangeOpSetAttribute
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "core/cmdb",
			"key_type" => "",
			"name_attcode" => "change",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_changeop_setatt_log",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeInteger("lastentry", array("allowed_values"=>null, "sql"=>"lastentry", "default_value"=>0, "is_null_allowed"=>true, "depends_on"=>array())));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('date', 'userinfo', 'attcode')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('date', 'userinfo', 'attcode')); // Attributes to be displayed for a list
	}
	
	/**
	 * Describe (as a text string) the modifications corresponding to this change
	 */	 
	public function GetDescription()
	{
		// Temporary, until we change the options of GetDescription() -needs a more global revision
		$bIsHtml = true;
		
		$sResult = '';
		$oTargetObjectClass = $this->Get('objclass');
		$oTargetObjectKey = $this->Get('objkey');
		$oTargetSearch = new DBObjectSearch($oTargetObjectClass);
		$oTargetSearch->AddCondition('id', $oTargetObjectKey, '=');

		$oMonoObjectSet = new DBObjectSet($oTargetSearch);
		if (UserRights::IsActionAllowedOnAttribute($this->Get('objclass'), $this->Get('attcode'), UR_ACTION_READ, $oMonoObjectSet) == UR_ALLOWED_YES)
		{
			if (MetaModel::IsValidAttCode($this->Get('objclass'), $this->Get('attcode')))
			{
				$oAttDef = MetaModel::GetAttributeDef($this->Get('objclass'), $this->Get('attcode'));
				$sAttName = $oAttDef->GetLabel();
			}
			else
			{
				// The attribute was renamed or removed from the object ?
				$sAttName = $this->Get('attcode');
			}
			$sResult = Dict::Format('Change:AttName_EntryAdded', $sAttName);
		}
		return $sResult;
	}
}

/**
 * Record an action made by a plug-in  
 *
 * @package     iTopORM
 */
class CMDBChangeOpPlugin extends CMDBChangeOp
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "core/cmdb",
			"key_type" => "",
			"name_attcode" => "change",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_changeop_plugin",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_AddAttribute(new AttributeString("description", array("allowed_values"=>null, "sql"=>"description", "default_value"=>'', "is_null_allowed"=>false, "depends_on"=>array())));
		/* May be used later when implementing an extension mechanism that will allow the plug-ins to store some extra information and still degrades gracefully when the plug-in is desinstalled
		MetaModel::Init_AddAttribute(new AttributeString("extension_class", array("allowed_values"=>null, "sql"=>"extension_class", "default_value"=>'', "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeInteger("extension_id", array("allowed_values"=>null, "sql"=>"extension_id", "default_value"=>0, "is_null_allowed"=>false, "depends_on"=>array())));
		*/
		MetaModel::Init_InheritAttributes();
	}
	
	/**
	 * Describe (as a text string) the modifications corresponding to this change
	 */	 
	public function GetDescription()
	{
		return $this->Get('description');
	}
}

/**
 * Record added/removed objects from within a link set 
 *
 * @package     iTopORM
 */
abstract class CMDBChangeOpSetAttributeLinks extends CMDBChangeOpSetAttribute
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "core/cmdb",
			"key_type" => "",
			"name_attcode" => "change",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_changeop_links",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		// Note: item class/id points to the link class itself in case of a direct link set (e.g. Server::interface_list => Interface)
		//       item class/id points to the remote class in case of a indirect link set (e.g. Server::contract_list => Contract)
		MetaModel::Init_AddAttribute(new AttributeString("item_class", array("allowed_values"=>null, "sql"=>"item_class", "default_value"=>'', "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeInteger("item_id", array("allowed_values"=>null, "sql"=>"item_id", "default_value"=>0, "is_null_allowed"=>false, "depends_on"=>array())));
	}
}

/**
 * Record added/removed objects from within a link set 
 *
 * @package     iTopORM
 */
class CMDBChangeOpSetAttributeLinksAddRemove extends CMDBChangeOpSetAttributeLinks
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "core/cmdb",
			"key_type" => "",
			"name_attcode" => "change",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_changeop_links_addremove",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeEnum("type", array("allowed_values"=>new ValueSetEnum('added,removed'), "sql"=>"type", "default_value"=>"added", "is_null_allowed"=>false, "depends_on"=>array())));
	}

	/**
	 * Describe (as a text string) the modifications corresponding to this change
	 */	 
	public function GetDescription()
	{
		$sResult = '';
		$oTargetObjectClass = $this->Get('objclass');
		$oTargetObjectKey = $this->Get('objkey');
		$oTargetSearch = new DBObjectSearch($oTargetObjectClass);
		$oTargetSearch->AddCondition('id', $oTargetObjectKey, '=');

		$oMonoObjectSet = new DBObjectSet($oTargetSearch);
		if (UserRights::IsActionAllowedOnAttribute($this->Get('objclass'), $this->Get('attcode'), UR_ACTION_READ, $oMonoObjectSet) == UR_ALLOWED_YES)
		{
			if (!MetaModel::IsValidAttCode($this->Get('objclass'), $this->Get('attcode'))) return ''; // Protects against renamed attributes...

			$oAttDef = MetaModel::GetAttributeDef($this->Get('objclass'), $this->Get('attcode'));
			$sAttName = $oAttDef->GetLabel();

			$sItemDesc = MetaModel::GetHyperLink($this->Get('item_class'), $this->Get('item_id'));

			$sResult = $sAttName.' - ';
			switch ($this->Get('type'))
			{
			case 'added':
				$sResult .= Dict::Format('Change:LinkSet:Added', $sItemDesc);
				break;

			case 'removed':
				$sResult .= Dict::Format('Change:LinkSet:Removed', $sItemDesc);
				break;
			}
		}
		return $sResult;
	}
}

/**
 * Record attribute changes from within a link set
 * A single record redirects to the modifications made within the same change  
 *
 * @package     iTopORM
 */
class CMDBChangeOpSetAttributeLinksTune extends CMDBChangeOpSetAttributeLinks
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => "core/cmdb",
			"key_type" => "",
			"name_attcode" => "change",
			"state_attcode" => "",
			"reconc_keys" => array(),
			"db_table" => "priv_changeop_links_tune",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeInteger("link_id", array("allowed_values"=>null, "sql"=>"link_id", "default_value"=>0, "is_null_allowed"=>false, "depends_on"=>array())));
	}

	/**
	 * Describe (as a text string) the modifications corresponding to this change
	 */	 
	public function GetDescription()
	{
		$sResult = '';
		$oTargetObjectClass = $this->Get('objclass');
		$oTargetObjectKey = $this->Get('objkey');
		$oTargetSearch = new DBObjectSearch($oTargetObjectClass);
		$oTargetSearch->AddCondition('id', $oTargetObjectKey, '=');

		$oMonoObjectSet = new DBObjectSet($oTargetSearch);
		if (UserRights::IsActionAllowedOnAttribute($this->Get('objclass'), $this->Get('attcode'), UR_ACTION_READ, $oMonoObjectSet) == UR_ALLOWED_YES)
		{
			if (!MetaModel::IsValidAttCode($this->Get('objclass'), $this->Get('attcode'))) return ''; // Protects against renamed attributes...

			$oAttDef = MetaModel::GetAttributeDef($this->Get('objclass'), $this->Get('attcode'));
			$sAttName = $oAttDef->GetLabel();

			$sLinkClass = $oAttDef->GetLinkedClass();
			$aLinkClasses = MetaModel::EnumChildClasses($sLinkClass, ENUM_CHILD_CLASSES_ALL);

			// Search for changes on the corresponding link
			//
			$oSearch = new DBObjectSearch('CMDBChangeOpSetAttribute');
			$oSearch->AddCondition('change', $this->Get('change'), '=');
			$oSearch->AddCondition('objkey', $this->Get('link_id'), '=');
			if (count($aLinkClasses) == 1)
			{
				// Faster than the whole building of the expression below for just one value ??
				$oSearch->AddCondition('objclass', $sLinkClass, '=');
			}
			else
			{
				$oField = new FieldExpression('objclass',  $oSearch->GetClassAlias());
				$sListExpr = '('.implode(', ', CMDBSource::Quote($aLinkClasses)).')';
				$sOQLCondition = $oField->Render()." IN $sListExpr";
				$oNewCondition = Expression::FromOQL($sOQLCondition);
				$oSearch->AddConditionExpression($oNewCondition);
			}
			$oSet = new DBObjectSet($oSearch);
			$aChanges = array();
			while ($oChangeOp = $oSet->Fetch())
			{
				$aChanges[] = $oChangeOp->GetDescription();
			}
			if (count($aChanges) == 0)
			{
				return '';
			}

			$sItemDesc = MetaModel::GetHyperLink($this->Get('item_class'), $this->Get('item_id'));

			$sResult = $sAttName.' - ';
			$sResult .= Dict::Format('Change:LinkSet:Modified', $sItemDesc);
			$sResult .= ' : '.implode(', ', $aChanges);
		}
		return $sResult;
	}
}
?>

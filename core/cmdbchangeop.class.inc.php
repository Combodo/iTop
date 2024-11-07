<?php
// Copyright (C) 2010-2024 Combodo SAS
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
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


/**
 * Various atomic change operations, to be tracked 
 *
 * @package     iTopORM
 */

/**
 * Interface iCMDBChangeOp
 *
 * @since 3.0.0
 */
interface iCMDBChangeOp
{
	/**
	 * Describe (as an HTML string) the modifications corresponding to this change
	 *
	 * @return string
	 */
	public function GetDescription();
}

class CMDBChangeOp extends DBObject implements iCMDBChangeOp
{
	public static function Init()
	{
		$aParams = array
		(
			"category"            => "core/cmdb, grant_by_profile",
			"key_type"            => "autoincrement",
			"name_attcode"        => "change",
			"state_attcode"       => "",
			"reconc_keys"         => array(),
			"db_table"            => "priv_changeop",
			"db_key_field"        => "id",
			"db_finalclass_field" => "optype",
			'indexes'             => array(
				array('objclass', 'objkey'),
			),
		);
		MetaModel::Init_Params($aParams);
		//MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeExternalKey("change", array("allowed_values"=>null, "sql"=>"changeid", "targetclass"=>"CMDBChange", "is_null_allowed"=>false, "on_target_delete"=>DEL_MANUAL, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeExternalField("date", array("allowed_values"=>null, "extkey_attcode"=>"change", "target_attcode"=>"date")));
		MetaModel::Init_AddAttribute(new AttributeExternalField("userinfo", array("allowed_values"=>null, "extkey_attcode"=>"change", "target_attcode"=>"userinfo")));
		MetaModel::Init_AddAttribute(new AttributeExternalField("user_id", array("allowed_values"=>null, "extkey_attcode"=>"change", "target_attcode"=>"user_id")));
		MetaModel::Init_AddAttribute(new AttributeString("objclass", array("allowed_values"=>null, "sql"=>"objclass", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeObjectKey("objkey", array("allowed_values"=>null, "class_attcode"=>"objclass", "sql"=>"objkey", "is_null_allowed"=>false, "depends_on"=>array())));

		MetaModel::Init_SetZListItems('details', array('change', 'date', 'userinfo')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('change', 'date', 'userinfo')); // Attributes to be displayed for the complete details
	}

	/**
	 * @inheritDoc
	 */
	public function GetDescription()
	{
		return '';
	}

	/**
	 * Safety net:
	 * * if change isn't persisted yet, use the current change and persist it if needed
	 * * in case the change is not given, let's guarantee that it will be set to the current ongoing change (or create a new one)
	 *
	 * @since 2.7.7 3.0.2 3.1.0 N°3717 do persist the current change if needed
	 */
	protected function OnInsert()
	{
		$iChange = $this->Get('change');
		if (($iChange <= 0) || (is_null($iChange))) {
			$oChange = CMDBObject::GetCurrentChange();
			if ($oChange->IsNew()) {
				$oChange->DBWrite();
			}
			$this->Set('change', $oChange);
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
	/**
	 * @inheritDoc
	 */
	public static function Init()
	{
		$aParams = array
		(
			"category"            => "core/cmdb, grant_by_profile",
			"key_type"            => "",
			"name_attcode"        => "change",
			"state_attcode"       => "",
			"reconc_keys"         => array(),
			"db_table"            => "priv_changeop_create",
			"db_key_field"        => "id",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
	}
	
	/**
	 * @inheritDoc
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
	/**
	 * @inheritDoc
	 */
	public static function Init()
	{
		$aParams = array
		(
			"category"            => "core/cmdb, grant_by_profile",
			"key_type"            => "",
			"name_attcode"        => "change",
			"state_attcode"       => "",
			"reconc_keys"         => array(),
			"db_table"            => "priv_changeop_delete",
			"db_key_field"        => "id",
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
	 * @inheritDoc
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
	/**
	 * @inheritDoc
	 */
	public static function Init()
	{
		$aParams = array
		(
			"category"            => "core/cmdb, grant_by_profile",
			"key_type"            => "",
			"name_attcode"        => "change",
			"state_attcode"       => "",
			"reconc_keys"         => array(),
			"db_table"            => "priv_changeop_setatt",
			"db_key_field"        => "id",
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
	/**
	 * @inheritDoc
	 */
	public static function Init()
	{
		$aParams = array
		(
			"category"            => "core/cmdb, grant_by_profile",
			"key_type"            => "",
			"name_attcode"        => "change",
			"state_attcode"       => "",
			"reconc_keys"         => array(),
			"db_table"            => "priv_changeop_setatt_scalar",
			"db_key_field"        => "id",
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
	 * @inheritDoc
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
 * Record the modification of a tag set attribute
 *
 * @package     iTopORM
 */
class CMDBChangeOpSetAttributeTagSet extends CMDBChangeOpSetAttribute
{
	/**
	 * @inheritDoc
	 */
	public static function Init()
    {
        $aParams = array
        (
	        "category"            => "core/cmdb, grant_by_profile",
	        "key_type"            => "",
	        "name_attcode"        => "change",
	        "state_attcode"       => "",
	        "reconc_keys"         => array(),
	        "db_table"            => "priv_changeop_setatt_tagset",
	        "db_key_field"        => "id",
	        "db_finalclass_field" => "",
        );
        MetaModel::Init_Params($aParams);
        MetaModel::Init_InheritAttributes();
        MetaModel::Init_AddAttribute(new AttributeText("oldvalue", array("allowed_values"=>null, "sql"=>"oldvalue", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));
        MetaModel::Init_AddAttribute(new AttributeText("newvalue", array("allowed_values"=>null, "sql"=>"newvalue", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));

        // Display lists
        MetaModel::Init_SetZListItems('details', array('date', 'userinfo', 'attcode', 'oldvalue', 'newvalue')); // Attributes to be displayed for the complete details
        MetaModel::Init_SetZListItems('list', array('date', 'userinfo', 'attcode', 'oldvalue', 'newvalue')); // Attributes to be displayed for a list
    }

	/**
	 * @inheritDoc
	 */
	public function GetDescription()
    {
        $sResult = '';
        $sTargetObjectClass = $this->Get('objclass');
        $oTargetObjectKey = $this->Get('objkey');
        $sAttCode = $this->Get('attcode');
        $oTargetSearch = new DBObjectSearch($sTargetObjectClass);
        $oTargetSearch->AddCondition('id', $oTargetObjectKey, '=');

        $oMonoObjectSet = new DBObjectSet($oTargetSearch);
        if (UserRights::IsActionAllowedOnAttribute($sTargetObjectClass, $sAttCode, UR_ACTION_READ, $oMonoObjectSet) == UR_ALLOWED_YES)
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
 * Record the modification of an URL 
 *
 * @package     iTopORM
 */
class CMDBChangeOpSetAttributeURL extends CMDBChangeOpSetAttribute
{
	/**
	 * @inheritDoc
	 */
	public static function Init()
	{
		$aParams = array
		(
			"category"            => "core/cmdb, grant_by_profile",
			"key_type"            => "",
			"name_attcode"        => "change",
			"state_attcode"       => "",
			"reconc_keys"         => array(),
			"db_table"            => "priv_changeop_setatt_url",
			"db_key_field"        => "id",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		// N°4910 (oldvalue), N°5423 (newvalue)
		// We cannot have validation here, as AttributeUrl validation is field dependant.
		// The validation will be done when editing the iTop object, it isn't the history API responsibility
		//
		// Pattern is retrieved using this order :
		// 1.  try to get the pattern from the field definition (datamodel)
		// 2. from the iTop config
		// 3. config parameter default value
		// see \AttributeURL::GetValidationPattern
		MetaModel::Init_AddAttribute(new AttributeURL("oldvalue", array("allowed_values" => null, "sql" => "oldvalue", "target" => '_blank', "default_value" => null, "is_null_allowed" => true, "depends_on" => array(), "validation_pattern" => '.*')));
		MetaModel::Init_AddAttribute(new AttributeURL("newvalue", array("allowed_values" => null, "sql" => "newvalue", "target" => '_blank', "default_value" => null, "is_null_allowed" => true, "depends_on" => array(), "validation_pattern" => '.*')));

		// Display lists
		MetaModel::Init_SetZListItems('details', array('date', 'userinfo', 'attcode', 'oldvalue', 'newvalue')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('date', 'userinfo', 'attcode', 'oldvalue', 'newvalue')); // Attributes to be displayed for a list
	}

	/**
	 * @inheritDoc
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
	/**
	 * @inheritDoc
	 */
	public static function Init()
	{
		$aParams = array
		(
			"category"            => "core/cmdb, grant_by_profile",
			"key_type"            => "",
			"name_attcode"        => "change",
			"state_attcode"       => "",
			"reconc_keys"         => array(),
			"db_table"            => "priv_changeop_setatt_data",
			"db_key_field"        => "id",
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
	 * @inheritDoc
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
			if (MetaModel::IsValidAttCode($this->Get('objclass'), $this->Get('attcode'))) {
				$oAttDef = MetaModel::GetAttributeDef($this->Get('objclass'), $this->Get('attcode'));
				$sAttName = $oAttDef->GetLabel();
			} else {
				// The attribute was renamed or removed from the object ?
				$sAttName = $this->Get('attcode');
			}
			/** @var \ormDocument $oPrevDoc */
			$oPrevDoc = $this->Get('prevdata');
			if ($oPrevDoc->IsEmpty()) {
				$sPrevious = '';
				$sResult = Dict::Format('Change:AttName_Changed_PreviousValue_OldValue', $sAttName, $sPrevious);
			} else {
				$sFieldAsHtml = $oPrevDoc->GetAsHTML();

				$sDisplayLabel = Dict::S('UI:OpenDocumentInNewWindow_');
				$sDisplayUrl = $oPrevDoc->GetDisplayURL(get_class($this), $this->GetKey(), 'prevdata');

				$sDownloadLabel = Dict::S('UI:DownloadDocument_');
				$sDownloadUrl = $oPrevDoc->GetDownloadURL(get_class($this), $this->GetKey(), 'prevdata');

				$sDocView = <<<HTML
{$sFieldAsHtml}
<a href="{$sDisplayUrl}" target="_blank">{$sDisplayLabel}</a> / <a href="{$sDownloadUrl}">{$sDownloadLabel}</a>
HTML;
				$sResult = Dict::Format('Change:AttName_Changed_PreviousValue_OldValue', $sAttName, $sDocView);
			}
		}
		return $sResult;
	}
}

/**
 * Safely record the modification of one way encrypted password
 */
class CMDBChangeOpSetAttributeOneWayPassword extends CMDBChangeOpSetAttribute
{
	/**
	 * @inheritDoc
	 */
	public static function Init()
	{
		$aParams = array
		(
			"category"            => "core/cmdb, grant_by_profile",
			"key_type"            => "",
			"name_attcode"        => "change",
			"state_attcode"       => "",
			"reconc_keys"         => array(),
			"db_table"            => "priv_changeop_setatt_pwd",
			"db_key_field"        => "id",
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
	 * @inheritDoc
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
	/**
	 * @inheritDoc
	 */
	public static function Init()
	{
		$aParams = array
		(
			"category"            => "core/cmdb, grant_by_profile",
			"key_type"            => "",
			"name_attcode"        => "change",
			"state_attcode"       => "",
			"reconc_keys"         => array(),
			"db_table"            => "priv_changeop_setatt_encrypted",
			"db_key_field"        => "id",
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
	 * @inheritDoc
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
			$sPrevString = $this->GetAsHTML('prevstring');
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
	/**
	 * @inheritDoc
	 */
	public static function Init()
	{
		$aParams = array
		(
			"category"            => "core/cmdb, grant_by_profile",
			"key_type"            => "",
			"name_attcode"        => "change",
			"state_attcode"       => "",
			"reconc_keys"         => array(),
			"db_table"            => "priv_changeop_setatt_text",
			"db_key_field"        => "id",
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
	 * @inheritDoc
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
	/**
	 * @inheritDoc
	 */
	public static function Init()
	{
		$aParams = array
		(
			"category"            => "core/cmdb, grant_by_profile",
			"key_type"            => "",
			"name_attcode"        => "change",
			"state_attcode"       => "",
			"reconc_keys"         => array(),
			"db_table"            => "priv_changeop_setatt_longtext",
			"db_key_field"        => "id",
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
	 * @inheritDoc
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
 * Record the modification of a multiline string (text) containing some HTML markup
 *
 * @package     iTopORM
 */
class CMDBChangeOpSetAttributeHTML extends CMDBChangeOpSetAttributeLongText
{
	/**
	 * @inheritDoc
	 */
	public static function Init()
	{
		$aParams = array
		(
			"category"            => "core/cmdb, grant_by_profile",
			"key_type"            => "",
			"name_attcode"        => "change",
			"state_attcode"       => "",
			"reconc_keys"         => array(),
			"db_table"            => "priv_changeop_setatt_html",
			"db_key_field"        => "id",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		
		// Display lists
		MetaModel::Init_SetZListItems('details', array('date', 'userinfo', 'attcode')); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('date', 'userinfo', 'attcode')); // Attributes to be displayed for a list
	}

	/**
	 * @inheritDoc
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
			if (MetaModel::IsValidAttCode($this->Get('objclass'), $this->Get('attcode'))) {
				$oAttDef = MetaModel::GetAttributeDef($this->Get('objclass'), $this->Get('attcode'));
				$sAttName = $oAttDef->GetLabel();
			} else {
				// The attribute was renamed or removed from the object ?
				$sAttName = $this->Get('attcode');
			}
			$sTextView = $this->Get('prevdata');

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
	/**
	 * @inheritDoc
	 */
	public static function Init()
	{
		$aParams = array
		(
			"category"            => "core/cmdb, grant_by_profile",
			"key_type"            => "",
			"name_attcode"        => "change",
			"state_attcode"       => "",
			"reconc_keys"         => array(),
			"db_table"            => "priv_changeop_setatt_log",
			"db_key_field"        => "id",
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
	 * @inheritDoc
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
			$oObj = $oMonoObjectSet->Fetch();
			$oCaseLog = $oObj->Get($this->Get('attcode'));
			$sTextEntry = '<div class="history_entry history_entry_truncated"><div class="history_html_content">'.$oCaseLog->GetEntryAt($this->Get('lastentry')).'</div></div>';

			$sResult = Dict::Format('Change:AttName_EntryAdded', $sAttName, $sTextEntry);
		}
		return $sResult;
	}

	/**
	 * @param string $sRawText
	 *
	 * @return string
	 */
	protected function ToHtml($sRawText)
	{
		return str_replace(array("\r\n", "\n", "\r"), "<br/>", utils::EscapeHtml($sRawText));
	}
}

/**
 * Record an action made by a plug-in  
 *
 * @package     iTopORM
 */
class CMDBChangeOpPlugin extends CMDBChangeOp
{
	/**
	 * @inheritDoc
	 */
	public static function Init()
	{
		$aParams = array
		(
			"category"            => "core/cmdb, grant_by_profile",
			"key_type"            => "",
			"name_attcode"        => "change",
			"state_attcode"       => "",
			"reconc_keys"         => array(),
			"db_table"            => "priv_changeop_plugin",
			"db_key_field"        => "id",
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
	 * @inheritDoc
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
	/**
	 * @inheritDoc
	 */
	public static function Init()
	{
		$aParams = array
		(
			"category"            => "core/cmdb, grant_by_profile",
			"key_type"            => "",
			"name_attcode"        => "change",
			"state_attcode"       => "",
			"reconc_keys"         => array(),
			"db_table"            => "priv_changeop_links",
			"db_key_field"        => "id",
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
	/**
	 * @inheritDoc
	 */
	public static function Init()
	{
		$aParams = array
		(
			"category"            => "core/cmdb, grant_by_profile",
			"key_type"            => "",
			"name_attcode"        => "change",
			"state_attcode"       => "",
			"reconc_keys"         => array(),
			"db_table"            => "priv_changeop_links_addremove",
			"db_key_field"        => "id",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeEnum("type", array("allowed_values"=>new ValueSetEnum('added,removed'), "sql"=>"type", "default_value"=>"added", "is_null_allowed"=>false, "depends_on"=>array())));
	}

	/**
	 * @inheritDoc
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
	/**
	 * @inheritDoc
	 */
	public static function Init()
	{
		$aParams = array
		(
			"category"            => "core/cmdb, grant_by_profile",
			"key_type"            => "",
			"name_attcode"        => "change",
			"state_attcode"       => "",
			"reconc_keys"         => array(),
			"db_table"            => "priv_changeop_links_tune",
			"db_key_field"        => "id",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeInteger("link_id", array("allowed_values"=>null, "sql"=>"link_id", "default_value"=>0, "is_null_allowed"=>false, "depends_on"=>array())));
	}

	/**
	 * @inheritDoc
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
				$sOQLCondition = $oField->RenderExpression()." IN $sListExpr";
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

/**
 * Record the modification of custom fields
 *
 * @package     iTopORM
 */
class CMDBChangeOpSetAttributeCustomFields extends CMDBChangeOpSetAttribute
{
	/**
	 * @inheritDoc
	 */
	public static function Init()
	{
		$aParams = array
		(
			"category"            => "core/cmdb, grant_by_profile",
			"key_type"            => "",
			"name_attcode"        => "change",
			"state_attcode"       => "",
			"reconc_keys"         => array(),
			"db_table"            => "priv_changeop_setatt_custfields",
			"db_key_field"        => "id",
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
	 * @inheritDoc
	 */
	public function GetDescription()
	{
		$sResult = '';
		if (MetaModel::IsValidAttCode($this->Get('objclass'), $this->Get('attcode')))
		{
			$oTargetObjectClass = $this->Get('objclass');
			$oTargetObjectKey = $this->Get('objkey');
			$oTargetSearch = new DBObjectSearch($oTargetObjectClass);
			$oTargetSearch->AddCondition('id', $oTargetObjectKey, '=');

			$oMonoObjectSet = new DBObjectSet($oTargetSearch);
			if (UserRights::IsActionAllowedOnAttribute($this->Get('objclass'), $this->Get('attcode'), UR_ACTION_READ, $oMonoObjectSet) == UR_ALLOWED_YES)
			{
				$aValues = json_decode($this->Get('prevdata'), true);
				$oAttDef = MetaModel::GetAttributeDef($this->Get('objclass'), $this->Get('attcode'));
				$sAttName = $oAttDef->GetLabel();

				try
				{
					$oHandler = $oAttDef->GetHandler($aValues);
					$sValueDesc = $oHandler->GetAsHTML($aValues);
				}
				catch (Exception $e) {
					$sValueDesc = 'Custom field error: '.utils::EscapeHtml($e->getMessage());
				}
				$sTextView = '<div>'.$sValueDesc.'</div>';

				$sResult = Dict::Format('Change:AttName_Changed_PreviousValue_OldValue', $sAttName, $sTextView);
			}
		}
		return $sResult;
	}
}

<?php

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

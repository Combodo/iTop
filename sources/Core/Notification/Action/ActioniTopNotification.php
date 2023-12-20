<?php

/*
 *  Can't use namespaces in iTop objects (yet)
namespace  Combodo\iTop\Core\Notification\Action;

use ActionNotification;
use AttributeEnum;
use AttributeImage;
use AttributeOQL;
use AttributeString;
use AttributeTemplateString;
use AttributeText;
use DBObjectSearch;
use DBObjectSet;
use MetaModel;
use utils;
use ValueSetEnum;
**/
use Combodo\iTop\Application\Branding;

class ActioniTopNotification extends ActionNotification
{
	public static function Init()
	{
		$aParams = array
		(
			"category"            => "grant_by_profile,core/cmdb,application",
			"key_type"            => "autoincrement",
			"name_attcode"        => "title",
			"state_attcode"       => "",
			"reconc_keys"         => array('title'),
			"db_table"            => "priv_action_itop_notif",
			"db_key_field"        => "id",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeTemplateString("title", array("allowed_values" => null, "sql" => "title", "default_value" => null, "is_null_allowed" => false, "depends_on" => array())));
		MetaModel::Init_AddAttribute(new AttributeText("message", array("sql" => 'message', "is_null_allowed" => true, "default_value" => '', "allowed_values" => null, "depends_on" => array(), "always_load_in_tables" => false)));
		MetaModel::Init_AddAttribute(new AttributeImage("icon", array("sql" => 'icon', "is_null_allowed" => true, "display_max_width" => 96, "display_max_height" => 96, "storage_max_width" => 256, "storage_max_height" => 256, "default_image" => null, "depends_on" => array(), "always_load_in_tables" => false)));
		MetaModel::Init_AddAttribute(new AttributeEnum("priority", array("allowed_values" => new ValueSetEnum('1,2,3,4'), "sql" => "priority", "default_value" => '1', "is_null_allowed" => false, "depends_on" => array())));
		MetaModel::Init_AddAttribute(new AttributeOQL("recipients", array("allowed_values" => null, "sql" => "recipients", "default_value" => null, "is_null_allowed" => false, "depends_on" => array())));
		MetaModel::Init_AddAttribute(new AttributeString("url", array("allowed_values" => null, "sql" => "url", "default_value" => '$this->url()$', "is_null_allowed" => false, "depends_on" => array(), "target" => "_blank")));

		// Display lists
		// - Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('details', array(
			'col:col1' => array(
				'fieldset:ActioniTopNotification:content' => array(
					0 => 'name',
					1 => 'status',
					2 => 'language',
					3 => 'title',
					4 => 'message',
				),
			),
			'col:col2' => array(
				'fieldset:ActioniTopNotification:settings' => array(
					0 => 'priority',
					1 => 'icon',
					2 => 'recipients',
					3 => 'url',
				),
			),
		));

		// - Attributes to be displayed for a list
		MetaModel::Init_SetZListItems('list', array('title', 'priority'));
		// Search criteria
		// - Standard criteria of the search
		MetaModel::Init_SetZListItems('standard_search', array('title', 'priority'));
		// - Default criteria for the search
		MetaModel::Init_SetZListItems('default_search', array('title', 'priority'));
	}

	/**
	 * 
	 *  Create EventiTopNotification for each user
	 * @param $oTrigger
	 * @param $aContextArgs
	 *
	 * @return void
	 * @throws \ArchivedObjectException
	 * @throws \CoreCannotSaveObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \CoreWarning
	 * @throws \MySQLException
	 * @throws \OQLException
	 */
	public function DoExecute($oTrigger, $aContextArgs)
	{
		$oRecipientsSearch = DBObjectSearch::FromOQL($this->Get('recipients'));
		$oRecipientsSet = new DBObjectSet($oRecipientsSearch);
		[$sPreviousLanguage, $aPreviousPluginProperties] = $this->SetNotificationLanguage();
		while ($oRecipient = $oRecipientsSet->Fetch()) {
			$oEvent = new EventiTopNotification();
			$oEvent->Set('title', MetaModel::ApplyParams($this->Get('title'), $aContextArgs));
			$oEvent->Set('message', MetaModel::ApplyParams($this->Get('message'), $aContextArgs));
			$oIcon = !$this->Get('icon')->IsEmpty() ? $this->Get('icon') : MetaModel::GetAttributeDef('EventiTopNotification', 'icon')->MakeRealValue(Branding::GetCompactMainLogoAbsoluteUrl(), $oEvent);
			$oEvent->Set('icon', $oIcon);
			$oEvent->Set('priority', $this->Get('priority'));
			$oEvent->Set('contact_id', $oRecipient->GetKey());
			$oEvent->Set('trigger_id', $oTrigger->GetKey());
			$oEvent->Set('action_id', $this->GetKey());
			$iObjectId = array_key_exists('this->object()', $aContextArgs) ? $aContextArgs['this->object()']->GetKey() : 0;
			$oEvent->Set('object_id', $iObjectId);
			$oEvent->Set('url', MetaModel::ApplyParams($this->Get('url'), $aContextArgs));
			$oEvent->DBInsertNoReload();
		}
		$this->SetNotificationLanguage($sPreviousLanguage, $aPreviousPluginProperties['language_code'] ?? null);
	}
}
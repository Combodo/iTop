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

use Combodo\iTop\Application\TwigBase\Twig\TwigHelper;
use Combodo\iTop\Application\UI\Base\Component\DataTable\DataTableUIBlockFactory;
use Combodo\iTop\Application\WebPage\WebPage;
use Combodo\iTop\Service\Notification\NotificationsRepository;
use Combodo\iTop\Service\Notification\NotificationsService;
use Combodo\iTop\Service\Router\Router;

/**
 * Persistent classes (internal): user defined actions
 *
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


require_once(APPROOT.'/core/asynctask.class.inc.php');
require_once(APPROOT.'/core/email.class.inc.php');

/**
 * A user defined action, to customize the application  
 *
 * @package     iTopORM
 */
abstract class Action extends cmdbAbstractObject
{
	/**
	 * @throws \CoreException
	 * @throws \Exception
	 */
	public static function Init()
	{
		$aParams = array
		(
			"category"                   => "grant_by_profile,core/cmdb",
			"key_type"                   => "autoincrement",
			"name_attcode"               => "name",
			"complementary_name_attcode" => ['finalclass', 'description'],
			"state_attcode"              => "status",
			"reconc_keys"                => ['name'],
			"db_table"                   => "priv_action",
			"db_key_field"               => "id",
			"db_finalclass_field"        => "realclass",
			"style"                      => new ormStyle("ibo-dm-class--Action", "ibo-dm-class-alt--Action", "var(--ibo-dm-class--Action--main-color)", "var(--ibo-dm-class--Action--complementary-color)", null, '../images/icons/icons8-in-transit.svg'),
		);
		MetaModel::Init_Params($aParams);
		//MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeString("name", array("allowed_values" => null, "sql" => "name", "default_value" => null, "is_null_allowed" => false, "depends_on" => array())));
		MetaModel::Init_AddAttribute(new AttributeString("description", array("allowed_values" => null, "sql" => "description", "default_value" => null, "is_null_allowed" => true, "depends_on" => array())));

		MetaModel::Init_AddAttribute(new AttributeEnum("status", array(
			"allowed_values"  => new ValueSetEnum(array('test' => 'Being tested', 'enabled' => 'In production', 'disabled' => 'Inactive')),
			"styled_values"   => [
				'test'     => new ormStyle('ibo-dm-enum--Action-status-test', 'ibo-dm-enum-alt--Action-status-test', 'var(--ibo-dm-enum--Action-status-test--main-color)', 'var(--ibo-dm-enum--Action-status-test--complementary-color)', null, null),
				'enabled'  => new ormStyle('ibo-dm-enum--Action-status-enabled', 'ibo-dm-enum-alt--Action-status-enabled', 'var(--ibo-dm-enum--Action-status-enabled--main-color)', 'var(--ibo-dm-enum--Action-status-enabled--complementary-color)', 'fas fa-check', null),
				'disabled' => new ormStyle('ibo-dm-enum--Action-status-disabled', 'ibo-dm-enum-alt--Action-status-disabled', 'var(--ibo-dm-enum--Action-status-disabled--main-color)', 'var(--ibo-dm-enum--Action-status-disabled--complementary-color)', null, null),
			],
			"display_style"   => 'list',
			"sql"             => "status",
			"default_value"   => "test",
			"is_null_allowed" => false,
			"depends_on"      => array(),
		)));

		MetaModel::Init_AddAttribute(new AttributeLinkedSetIndirect("trigger_list",
			array("linked_class" => "lnkTriggerAction", "ext_key_to_me" => "action_id", "ext_key_to_remote" => "trigger_id", "allowed_values" => null, "count_min" => 0, "count_max" => 0, "depends_on" => array(), "display_style" => 'property')));
		MetaModel::Init_AddAttribute(new AttributeEnum("asynchronous", array("allowed_values" => new ValueSetEnum(['use_global_setting' => 'Use global settings','yes' => 'Yes' ,'no' => 'No']), "sql" => "asynchronous", "default_value" => 'use_global_setting', "is_null_allowed" => false, "depends_on" => array())));

		// Display lists
		// - Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('details', array('name', 'description', 'status', 'trigger_list'));
		// - Attributes to be displayed for a list
		MetaModel::Init_SetZListItems('list', array('finalclass', 'name', 'description', 'status'));
		// Search criteria
		// - Default criteria of the search form
		MetaModel::Init_SetZListItems('default_search', array('name', 'description', 'status'));

	}

	/**
	 * Encapsulate the execution of the action and handle failure & logging
	 *
	 * @param \Trigger $oTrigger
	 * @param array $aContextArgs
	 *
	 * @return mixed
	 */
	abstract public function DoExecute($oTrigger, $aContextArgs);

	/**
	 * @return bool
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 */
	public function IsActive()
	{
		switch($this->Get('status'))
		{
			case 'enabled':
			case 'test':
				return true;

			default:
				return false;
		}
	}

	/**
	 * Return true if the current action status is set on "test"
	 *
	 * @return bool
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 */
	public function IsBeingTested()
	{
		switch($this->Get('status'))
		{
			case 'test':
				return true;

			default:
				return false;
		}
	}

	/**
	 * @inheritDoc
	 * @since 3.0.0
	 */
	public function AfterInsert()
	{
		parent::AfterInsert();
		$this->DoCheckIfHasTrigger();
	}

	/**
	 * @inheritDoc
	 * @since 3.0.0
	 */
	public function AfterUpdate()
	{
		parent::AfterUpdate();
		$this->DoCheckIfHasTrigger();
	}

	/**
	 * Check if the Action has at least 1 trigger linked. Otherwise, it adds a warning.
	 * @return void
	 * @since 3.0.0
	 */
	protected function DoCheckIfHasTrigger()
	{
		$oTriggersSet = $this->Get('trigger_list');
		if ($oTriggersSet->Count() === 0) {
			$this->m_aCheckWarnings[] = Dict::S('Action:WarningNoTriggerLinked');
		}
	}

	/**
	 * @since 3.2.0 N°5472 method creation
	 */
	public function DisplayBareRelations(WebPage $oPage, $bEditMode = false)
	{
		parent::DisplayBareRelations($oPage, false);

		if ($oPage instanceof iTopWebPage) {
			$this->GenerateLastExecutionsTab($oPage, $bEditMode);
		}
	}

	/**
	 * @since 3.2.0 N°5472 method creation
	 */
	protected function GenerateLastExecutionsTab(iTopWebPage $oPage, $bEditMode)
	{
		$oRouter = Router::GetInstance();
		$sActionLastExecutionsPageUrl = $oRouter->GenerateUrl('notifications.action.last_executions_tab', ['action_id' => $this->GetKey()]);
		$oPage->AddAjaxTab('action_errors', $sActionLastExecutionsPageUrl, false, Dict::S('Action:last_executions_tab'));
	}

	/**
	 * @param \Combodo\iTop\Application\WebPage\WebPage $oPage
	 *
	 * @throws \ApplicationException
	 * @throws \ArchivedObjectException
	 * @throws \ConfigException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \DictExceptionMissingString
	 * @throws \InvalidConfigParamException
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 * @throws \ReflectionException
	 * @since 3.2.0 N°5472 method creation
	 */
	public function GetLastExecutionsTabContent(WebPage $oPage): void
	{
		$oConfig = utils::GetConfig();
		$sLastExecutionDaysConfigParamName = 'notifications.last_executions_days';
		$iLastExecutionDays = $oConfig->Get($sLastExecutionDaysConfigParamName);

		if ($iLastExecutionDays < 0) {
			throw new InvalidConfigParamException("Invalid value for {$sLastExecutionDaysConfigParamName} config parameter. Param desc: " . $oConfig->GetDescription($sLastExecutionDaysConfigParamName));
		}

		$sActionQueryOql = 'SELECT EventNotification WHERE action_id = :action_id';
		$aActionQueryParams = ['action_id' => $this->GetKey()];
		if ($iLastExecutionDays > 0) {
			$sActionQueryOql .= ' AND date > DATE_SUB(NOW(), INTERVAL :days DAY)';
			$aActionQueryParams['days'] = $iLastExecutionDays;
			$sActionQueryLimit = Dict::Format('Action:last_executions_tab_limit_days', $iLastExecutionDays);
		} else {
			$sActionQueryLimit = Dict::S('Action:last_executions_tab_limit_none');
		}

		$oActionFilter = DBObjectSearch::FromOQL($sActionQueryOql, $aActionQueryParams);
		$oSet = new DBObjectSet($oActionFilter, ['date' => false]);

		$sPanelTitle = Dict::Format('Action:last_executions_tab_panel_title', $sActionQueryLimit);
		$oExecutionsListBlock = DataTableUIBlockFactory::MakeForResult($oPage, 'action_executions_list', $oSet, ['panel_title' => $sPanelTitle]);

		$oPage->AddUiBlock($oExecutionsListBlock);
	}

	/**
	 * Will be overloaded by the children classes to return the value of their global asynchronous setting (eg. `email_asynchronous` for `\ActionEmail`, `prefer_asynchronous` for `\ActionWebhook`, ...)
	 *
	 * @return bool true if the global setting for this kind of action if to be executed asynchronously, false otherwise.
	 * @since 3.2.0
	 */
	public static function GetAsynchronousGlobalSetting(): bool
	{
		return false;	
	}

	/**
	 * @return bool true if that action instance should be executed asynchronously, otherwise false
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @since 3.2.0
	 */
	public function IsAsynchronous(): bool
	{
		$sAsynchronous = $this->Get('asynchronous');
		if ($sAsynchronous === 'use_global_setting') {
			return static::GetAsynchronousGlobalSetting();
		}
		return $sAsynchronous === 'yes';
	}
}

/**
 * A notification  
 *
 * @package     iTopORM
 */
abstract class ActionNotification extends Action
{
	/**
	 * @inheritDoc
	 * @throws \CoreException
	 */
	public static function Init()
	{
		$aParams = array
		(
			"category"            => "grant_by_profile,core/cmdb",
			"key_type"            => "autoincrement",
			"name_attcode"        => "name",
			"complementary_name_attcode" => ['finalclass', 'description'],
			"state_attcode"       => "",
			"reconc_keys"         => ['name'],
			"db_table"            => "priv_action_notification",
			"db_key_field"        => "id",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		// Display lists
		// - Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('details', array('name', 'description', 'status', 'trigger_list'));
		// - Attributes to be displayed for a list
		MetaModel::Init_SetZListItems('list', array('finalclass', 'description', 'status'));
		MetaModel::Init_AddAttribute(new AttributeApplicationLanguage("language", array("sql"=>"language", "default_value"=>null, "is_null_allowed"=>true, "depends_on"=>array())));

		// Search criteria
		// - Criteria of the std search form
//		MetaModel::Init_SetZListItems('standard_search', array('name'));
		// - Default criteria of the search form
//		MetaModel::Init_SetZListItems('default_search', array('name'));
	}

	/**
	 * @param $sLanguage
	 * @param $sLanguageCode
	 *
	 * @return array [$sPreviousLanguage, $aPreviousPluginProperties]
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \DictExceptionUnknownLanguage
	 * @since 3.2.0
	 */
	public function SetNotificationLanguage($sLanguage = null, $sLanguageCode = null){
		$sPreviousLanguage = Dict::GetUserLanguage();
		$aPreviousPluginProperties = ApplicationContext::GetPluginProperties('QueryLocalizerPlugin');
		$sLanguage = $sLanguage ?? $this->Get('language');
		$sLanguageCode = $sLanguageCode ?? $sLanguage;
		if (!utils::IsNullOrEmptyString($sLanguage)) {
			// If a language is specified for this action, force this language
			// when rendering all placeholders inside this message
			Dict::SetUserLanguage($sLanguage);
			AttributeDateTime::LoadFormatFromConfig();
			ApplicationContext::SetPluginProperty('QueryLocalizerPlugin', 'language_code', $sLanguageCode);
		}
		return [$sPreviousLanguage, $aPreviousPluginProperties];
	}
}

/**
 * An email notification  
 *
 * @package     iTopORM
 */
class ActionEmail extends ActionNotification
{
	/**
	 * @var string
	 * @since 3.0.1
	 */
	const ENUM_HEADER_NAME_MESSAGE_ID = 'Message-ID';
	/**
	 * @var string
	 * @since 3.0.1
	 */
	const ENUM_HEADER_NAME_REFERENCES = 'References';
	/**
	 * @var string
	 * @since 3.1.0
	 */
	const TEMPLATE_BODY_CONTENT = '$content$';
	/**
	 * Wraps the 'body' of the message for previewing inside an IFRAME -- i.e. without any of the iTop stylesheets being applied
	 * @var string
	 * @since 3.1.0
	 */
	const CONTENT_HIGHLIGHT = '<div style="border:2px dashed #6800ff;position:relative;padding:2px;margin-top:14px;"><div style="background-color:#6800ff;color:#fff;font-family:Courier New, sans-serif;font-size:14px;line-height:16px;padding:3px;display:block;position:absolute;top:-22px;right:0;">$content$</div>%s</div>';
	/**
	 * Wraps a placeholder of the email's body for previewing inside an IFRAME -- i.e. without any of the iTop stylesheets being applied
	 * @var string
	 */
	const FIELD_HIGHLIGHT = '<span style="background-color:#6800ff;color:#fff;font-size:smaller;font-family:Courier New, sans-serif;padding:2px;">\\$$1\\$</span>';
	/**
	 * @inheritDoc
	 */
	public static function Init()
	{
		$aParams = array
		(
			"category"            => "grant_by_profile,core/cmdb,application",
			"key_type"            => "autoincrement",
			"name_attcode"        => "name",
			"state_attcode"       => "",
			"reconc_keys"         => array('name'),
			"db_table"            => "priv_action_email",
			"db_key_field"        => "id",
			"db_finalclass_field" => "",
			'style' =>  new ormStyle(null, null, null, null, null, '../images/icons/icons8-mailing.svg'),
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeEmailAddress("test_recipient", array("allowed_values" => null, "sql" => "test_recipient", "default_value" => "", "is_null_allowed" => true, "depends_on" => array())));

		MetaModel::Init_AddAttribute(new AttributeString("from", array("allowed_values" => null, "sql" => "from", "default_value" => null, "is_null_allowed" => false, "depends_on" => array())));
		MetaModel::Init_AddAttribute(new AttributeString("from_label", array("allowed_values" => null, "sql" => "from_label", "default_value" => null, "is_null_allowed" => true, "depends_on" => array())));
		MetaModel::Init_AddAttribute(new AttributeString("reply_to", array("allowed_values" => null, "sql" => "reply_to", "default_value" => null, "is_null_allowed" => true, "depends_on" => array())));
		MetaModel::Init_AddAttribute(new AttributeString("reply_to_label", array("allowed_values" => null, "sql" => "reply_to_label", "default_value" => null, "is_null_allowed" => true, "depends_on" => array())));
		MetaModel::Init_AddAttribute(new AttributeOQL("to", array("allowed_values" => null, "sql" => "to", "default_value" => null, "is_null_allowed" => true, "depends_on" => array())));
		MetaModel::Init_AddAttribute(new AttributeOQL("cc", array("allowed_values" => null, "sql" => "cc", "default_value" => null, "is_null_allowed" => true, "depends_on" => array())));
		MetaModel::Init_AddAttribute(new AttributeOQL("bcc", array("allowed_values" => null, "sql" => "bcc", "default_value" => null, "is_null_allowed" => true, "depends_on" => array())));
		MetaModel::Init_AddAttribute(new AttributeTemplateString("subject", array("allowed_values" => null, "sql" => "subject", "default_value" => null, "is_null_allowed" => false, "depends_on" => array())));
		MetaModel::Init_AddAttribute(new AttributeTemplateHTML("body", array("allowed_values" => null, "sql" => "body", "default_value" => null, "is_null_allowed" => false, "depends_on" => array())));
		MetaModel::Init_AddAttribute(new AttributeEnum("importance", array("allowed_values" => new ValueSetEnum('low,normal,high'), "sql" => "importance", "default_value" => 'normal', "is_null_allowed" => false, "depends_on" => array())));
		MetaModel::Init_AddAttribute(new AttributeBlob("html_template", array("is_null_allowed"=>true, "depends_on"=>array(), "always_load_in_tables"=>false)));
		MetaModel::Init_AddAttribute(new AttributeEnum("ignore_notify", array("allowed_values" => new ValueSetEnum('yes,no'), "sql" => "ignore_notify", "default_value" => 'yes', "is_null_allowed" => false, "depends_on" => array())));
		

		// Display lists
		// - Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('details', array(
			'col:col1' => array(
				'fieldset:ActionEmail:main'    => array(
					0 => 'name',
					1 => 'description',
					2 => 'status',
					3 => 'language',
					4 => 'html_template',
					5 => 'subject',
					6 => 'body',
					// 5 => 'importance', not handled when sending the mail, better hide it then
				),
				'fieldset:ActionEmail:trigger' => array(
					0 => 'trigger_list',
					1 => 'asynchronous'
				),
			),
			'col:col2' => array(
				'fieldset:ActionEmail:recipients' => array(
					0 => 'from',
					1 => 'from_label',
					2 => 'reply_to',
					3 => 'reply_to_label',
					4 => 'test_recipient',
					5 => 'ignore_notify',
					6 => 'to',
					7 => 'cc',
					8 => 'bcc',
				),
			),
		));

		// - Attributes to be displayed for a list
		MetaModel::Init_SetZListItems('list', array('status', 'to', 'subject', 'language'));
		// Search criteria
		// - Standard criteria of the search
		MetaModel::Init_SetZListItems('standard_search', array('name', 'description', 'status', 'subject', 'language'));
		// - Default criteria for the search
		MetaModel::Init_SetZListItems('default_search', array('name', 'description', 'status', 'subject', 'language'));
	}

	// count the recipients found
	protected $m_iRecipients;

	// Errors management : not that simple because we need that function to be
	// executed in the background, while making sure that any issue would be reported clearly
	protected $m_aMailErrors; //array of strings explaining the issue

	/**
	 * Return the list of emails as a string, or a detailed error description
	 *
	 * @param string $sRecipAttCode
	 * @param array $aArgs
	 *
	 * @return string
	 * @throws \ArchivedObjectException
	 * @throws \CoreCannotSaveObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \CoreWarning
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 */
	protected function FindRecipients($sRecipAttCode, $aArgs)
	{
		$oTrigger = $aArgs['trigger->object()'] ?? null;
		$sOQL = $this->Get($sRecipAttCode);
		if (utils::IsNullOrEmptyString($sOQL)) return '';

		try
		{
			$oSearch = DBObjectSearch::FromOQL($sOQL);
			if ($this->Get('ignore_notify') === 'no') {
				// In theory, it is possible to notify *any* kind of object,
				// as long as there is an email attribute in the class
				// So let's not assume that the selected class is a Person
				$sFirstSelectedClass = $oSearch->GetClass();
				if (MetaModel::IsValidAttCode($sFirstSelectedClass, 'notify')) {
					$oSearch->AddCondition('notify', 'yes');
				}
			}
			$oSearch->AllowAllData();
		}
		catch (OQLException $e)
		{
			$this->m_aMailErrors[] = "query syntax error for recipient '$sRecipAttCode'";
			return $e->getMessage();
		}

		$sClass = $oSearch->GetClass();
		// Determine the email attribute (the first one will be our choice)
		foreach (MetaModel::ListAttributeDefs($sClass) as $sAttCode => $oAttDef)
		{
			if ($oAttDef instanceof AttributeEmailAddress)
			{
				$sEmailAttCode = $sAttCode;
				// we've got one, exit the loop
				break;
			}
		}
		if (!isset($sEmailAttCode))
		{
			$this->m_aMailErrors[] = "wrong target for recipient '$sRecipAttCode'";
			return "The objects of the class '$sClass' do not have any email attribute";
		}

		if($oTrigger !== null && in_array('Contact', MetaModel::EnumParentClasses($sClass, ENUM_CHILD_CLASSES_ALL), true)) {
			$aArgs['trigger_id'] = $oTrigger->GetKey();
			$aArgs['action_id'] = $this->GetKey();

			$sSubscribedContactsOQL = NotificationsRepository::GetInstance()->GetSearchOQLContactUnsubscribedByTriggerAndAction();
			$sSubscribedContactsOQL->ApplyParameters($aArgs);
			$sAlias = $oSearch->GetClassAlias();
			$oSearch->AddConditionExpression(Expression::FromOQL("`$sAlias`.id NOT IN ($sSubscribedContactsOQL)"));
		}

		$oSet = new DBObjectSet($oSearch, array() /* order */, $aArgs);
		$aRecipients = array();
		while ($oObj = $oSet->Fetch())
		{
			$sAddress = trim($oObj->Get($sEmailAttCode));
			if (utils::IsNotNullOrEmptyString($sAddress))
			{
				$aRecipients[] = $sAddress;
				$this->m_iRecipients++;
			}
			if ($oTrigger !== null && in_array('Contact', MetaModel::EnumParentClasses($sClass, ENUM_CHILD_CLASSES_ALL), true)) {
				NotificationsService::GetInstance()->RegisterSubscription($oTrigger, $this, $oObj);
			}
		}
		return implode(', ', $aRecipients);
	}

	/**
	 * @inheritDoc
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \CoreWarning
	 */
	public function DoExecute($oTrigger, $aContextArgs)
	{
		if (MetaModel::IsLogEnabledNotification())
		{
			$oLog = new EventNotificationEmail();
			if ($this->IsBeingTested())
			{
				$oLog->Set('message', 'TEST - Notification sent ('.$this->Get('test_recipient').')');
			}
			else
			{
				$oLog->Set('message', 'Notification pending');
			}
			$oLog->Set('userinfo', UserRights::GetUser());
			$oLog->Set('trigger_id', $oTrigger->GetKey());
			$oLog->Set('action_id', $this->GetKey());
			$oLog->Set('object_id', $aContextArgs['this->object()']->GetKey());
			// Must be inserted now so that it gets a valid id that will make the link
			// between an eventual asynchronous task (queued) and the log
			$oLog->DBInsertNoReload();
		}
		else
		{
			$oLog = null;
		}

		try
		{
			$sRes = $this->_DoExecute($oTrigger, $aContextArgs, $oLog);

			if ($this->IsBeingTested())
			{
				$sPrefix = 'TEST ('.$this->Get('test_recipient').') - ';
			}
			else
			{
				$sPrefix = '';
			}

			if ($oLog)
			{
				$oLog->Set('message', $sPrefix . $sRes);
                $oLog->DBUpdate();
            }

		}
		catch (Exception $e)
		{
			if ($oLog)
			{
				$oLog->Set('message', 'Error: '.$e->getMessage());

				try
				{
                    $oLog->DBUpdate();
				}
				catch (Exception $eSecondTryUpdate)
				{
                    IssueLog::Error("Failed to process email ".$oLog->GetKey()." - reason: ".$e->getMessage()."\nTrace:\n".$e->getTraceAsString());

                    $oLog->Set('message', 'Error: more details in the log for email "'.$oLog->GetKey().'"');
                    $oLog->DBUpdate();
                }
			}
		}

	}

	/**
	 * @param \Trigger $oTrigger
	 * @param array $aContextArgs
	 * @param \EventNotification $oLog
	 *
	 * @return string
	 * @throws \CoreException
	 * @throws \Exception
	 */
	protected function _DoExecute($oTrigger, $aContextArgs, &$oLog)
	{
		$sStyles = file_get_contents(APPROOT . utils::GetCSSFromSASS("css/email.scss"));
		$sStyles .= MetaModel::GetConfig()->Get('email_css');
		
		$oEmail = new EMail();
		
		$aEmailContent = $this->PrepareMessageContent($aContextArgs, $oLog);
		$oEmail->SetSubject($aEmailContent['subject']);
		$oEmail->SetBody($aEmailContent['body'], 'text/html', $sStyles);
		$oEmail->SetRecipientTO($aEmailContent['to']);
		$oEmail->SetRecipientCC($aEmailContent['cc']);
		$oEmail->SetRecipientBCC($aEmailContent['bcc']);
		$oEmail->SetRecipientFrom($aEmailContent['from'], $aEmailContent['from_label']);
		$oEmail->SetRecipientReplyTo($aEmailContent['reply_to'], $aEmailContent['reply_to_label']);
		$oEmail->SetReferences($aEmailContent['references']);
		$oEmail->SetMessageId($aEmailContent['message_id']);
		$oEmail->SetInReplyTo($aEmailContent['in_reply_to']);
		
		foreach($aEmailContent['attachments'] as $aAttachment) {
			$oEmail->AddAttachment($aAttachment['data'], $aAttachment['filename'], $aAttachment['mime_type']);
		}
		
		if (empty($this->m_aMailErrors))
		{
			if ($this->m_iRecipients == 0)
			{
				return 'No recipient';
			}
			else
			{
				$aErrors = [];
				$iRes = $oEmail->Send($aErrors, $this->IsAsynchronous() ? Email::ENUM_SEND_FORCE_ASYNCHRONOUS : Email::ENUM_SEND_FORCE_SYNCHRONOUS, $oLog);
				switch ($iRes)
				{
					case EMAIL_SEND_OK:
						return "Sent";

					case EMAIL_SEND_PENDING:
						return "Pending";

					case EMAIL_SEND_ERROR:
						return "Errors: ".implode(', ', $aErrors);
				}
			}
		} else {
			if (is_array($this->m_aMailErrors) && count($this->m_aMailErrors) > 0) {
				$sError = implode(', ', $this->m_aMailErrors);
			} else {
				$sError = 'Unknown reason';
			}

			return 'Notification was not sent: '.$sError;
		}
	}

	/**
	 * @param array $aContextArgs
	 * @param \EventNotification $oLog
	 *
	 * @return array
	 * @throws \ArchivedObjectException
	 * @throws \CoreCannotSaveObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \CoreWarning
	 * @throws \DictExceptionMissingString
	 * @throws \DictExceptionUnknownLanguage
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 * @since 3.1.0 N°918
	 */
	protected function PrepareMessageContent($aContextArgs, &$oLog): array
	{
		$aMessageContent = [
			'to' => '',
			'cc' => '',
			'bcc' => '',
			'from' => '',
			'from_label' => '',
			'reply_to' => '',
			'reply_to_label' => '',
			'subject' => '',
			'body' => '',
			'references' => '',
			'message_id' => '',
			'in_reply_to' => '',
			'attachments' => [],
		];
		$sPreviousUrlMaker = ApplicationContext::SetUrlMakerClass();
		[$sPreviousLanguage, $aPreviousPluginProperties] = $this->SetNotificationLanguage();

		try
		{
			$this->m_iRecipients = 0;
			$this->m_aMailErrors = array();
			
			// Determine recipients
			//
			$aMessageContent['to'] = $this->FindRecipients('to', $aContextArgs);
			$aMessageContent['cc'] = $this->FindRecipients('cc', $aContextArgs);
			$aMessageContent['bcc'] = $this->FindRecipients('bcc', $aContextArgs);
			
			$aMessageContent['from'] = MetaModel::ApplyParams($this->Get('from'), $aContextArgs);
			$aMessageContent['from_label'] = MetaModel::ApplyParams($this->Get('from_label'), $aContextArgs);
			$aMessageContent['reply_to'] = MetaModel::ApplyParams($this->Get('reply_to'), $aContextArgs);
			$aMessageContent['reply_to_label'] = MetaModel::ApplyParams($this->Get('reply_to_label'), $aContextArgs);
			
			$aMessageContent['subject'] = MetaModel::ApplyParams($this->Get('subject'), $aContextArgs);
			$sBody = $this->BuildMessageBody(false);
			$aMessageContent['body'] = MetaModel::ApplyParams($sBody, $aContextArgs);
			
			$oObj = $aContextArgs['this->object()'];
			$aMessageContent['message_id'] = $this->GenerateIdentifierForHeaders($oObj, static::ENUM_HEADER_NAME_MESSAGE_ID);
			$aMessageContent['references'] = $this->GenerateIdentifierForHeaders($oObj, static::ENUM_HEADER_NAME_REFERENCES);
		}
		catch (Exception $e) {
			/** @noinspection PhpUnhandledExceptionInspection */
			throw $e;
		}
		finally {
			ApplicationContext::SetUrlMakerClass($sPreviousUrlMaker);
			$this->SetNotificationLanguage($sPreviousLanguage, $aPreviousPluginProperties['language_code'] ?? null);
		}
		
		if (!is_null($oLog)) {
			// Note: we have to secure this because those values are calculated
			// inside the try statement, and we would like to keep track of as
			// many data as we could while some variables may still be undefined
			if (isset($aMessageContent['to'])) {
				$oLog->Set('to', $aMessageContent['to']);
			}
			if (isset($aMessageContent['cc'])) {
				$oLog->Set('cc', $aMessageContent['cc']);
			}
			if (isset($aMessageContent['bcc'])) {
				$oLog->Set('bcc', $aMessageContent['bcc']);
			}
			if (isset($aMessageContent['from'])) {
				$oLog->Set('from', $aMessageContent['from']);
			}
			if (isset($aMessageContent['subject'])) {
				$oLog->Set('subject', $aMessageContent['subject']);
			}
			if (isset($aMessageContent['body'])) {
				$oLog->Set('body', HTMLSanitizer::Sanitize($aMessageContent['body']));
			}
		}

		if ($this->IsBeingTested()) {
			$sTestBody = $aMessageContent['body'];
			$sTestBody .= "<div style=\"border: dashed;\">\n";
			$sTestBody .= "<h1>Testing email notification ".$this->GetHyperlink()."</h1>\n";
			$sTestBody .= "<p>The email should be sent with the following properties\n";
			$sTestBody .= "<ul>\n";
			$sTestBody .= "<li>TO: {$aMessageContent['to']}</li>\n";
			$sTestBody .= "<li>CC: {$aMessageContent['cc']}</li>\n";
			$sTestBody .= "<li>BCC: {$aMessageContent['bcc']}</li>\n";
			$sTestBody .= empty($aMessageContent['from_label']) ? "<li>From: {$aMessageContent['from']}</li>\n" : "<li>From: {$aMessageContent['from_label']} &lt;{$aMessageContent['from']}&gt;</li>\n";
			$sTestBody .= empty($aMessageContent['reply_to_label']) ? "<li>Reply-To: {$aMessageContent['reply_to']}</li>\n" : "<li>Reply-To: {$aMessageContent['reply_to_label']} &lt;{$aMessageContent['reply_to']}&gt;</li>\n";
			$sTestBody .= "<li>References: {$aMessageContent['references']}</li>\n";
			$sTestBody .= "</ul>\n";
			$sTestBody .= "</p>\n";
			$sTestBody .= "</div>\n";
			$aMessageContent['subject'] = 'TEST['.$aMessageContent['subject'].']';
			$aMessageContent['body'] = $sTestBody;
			$aMessageContent['to'] = $this->Get('test_recipient');
			// N°6677 Ensure emails in test are never sent to cc'd and bcc'd addresses
			$aMessageContent['cc'] = '';
			$aMessageContent['bcc'] = '';
		}
		// Note: N°4849 We pass the "References" identifier instead of the "Message-ID" on purpose as we want notifications emails to group around the triggering iTop object, not just the users' replies to the notification
		$aMessageContent['in_reply_to'] = $aMessageContent['references'];
		
		if (isset($aContextArgs['attachments']))
		{
			$aAttachmentReport = array();
			/** @var \ormDocument $oDocument */
			foreach($aContextArgs['attachments'] as $oDocument)
			{
				$aMessageContent['attachments'][] = ['data' => $oDocument->GetData(), 'filename' => $oDocument->GetFileName(), 'mime_type' => $oDocument->GetMimeType()];
				$aAttachmentReport[] = array($oDocument->GetFileName(), $oDocument->GetMimeType(), strlen($oDocument->GetData() ?? ''));
			}
			$oLog->Set('attachments', $aAttachmentReport);
		}
		
		return $aMessageContent;
	}

	/**
	 * @param \DBObject $oObject
	 * @param string $sHeaderName {@see \ActionEmail::ENUM_HEADER_NAME_REFERENCES}, {@see \ActionEmail::ENUM_HEADER_NAME_MESSAGE_ID}
	 *
	 * @return string The formatted identifier for $sHeaderName based on $oObject
	 * @throws \Exception
	 * @since 3.1.0 N°4849
	 */
	protected function GenerateIdentifierForHeaders(DBObject $oObject, string $sHeaderName): string
	{
		$sObjClass = get_class($oObject);
		$sObjId = $oObject->GetKey();
		$sAppName = utils::Sanitize(ITOP_APPLICATION_SHORT, '', utils::ENUM_SANITIZATION_FILTER_VARIABLE_NAME);

		switch ($sHeaderName) {
			case static::ENUM_HEADER_NAME_MESSAGE_ID:
			case static::ENUM_HEADER_NAME_REFERENCES:
				// Prefix
				$sPrefix = sprintf('%s_%s_%d', $sAppName, $sObjClass, $sObjId);
				if ($sHeaderName === static::ENUM_HEADER_NAME_MESSAGE_ID) {
					$sPrefix .= sprintf('_%F', microtime(true /* get as float*/));
				}
				// Suffix
				$sSuffix = sprintf('@%s.openitop.org', MetaModel::GetEnvironmentId());
				// Identifier
				$sIdentifier = $sPrefix.$sSuffix;
				if ($sHeaderName === static::ENUM_HEADER_NAME_REFERENCES) {
					$sIdentifier = "<$sIdentifier>";
				}

				return $sIdentifier;
		}

		// Requested header name invalid
		$sErrorMessage = sprintf('%s: Could not generate identifier for header "%s", only %s are supported', static::class, $sHeaderName, implode(' / ', [static::ENUM_HEADER_NAME_MESSAGE_ID, static::ENUM_HEADER_NAME_REFERENCES]));
		IssueLog::Error($sErrorMessage, LogChannels::NOTIFICATIONS, [
			'Object' => $sObjClass.'::'.$sObjId.' ('.$oObject->GetRawName().')',
			'Action' => get_class($this).'::'.$this->GetKey().' ('.$this->GetRawName().')',
		]);
		throw new Exception($sErrorMessage);
	}
	
	/**
	 * Compose the body of the message from the 'body' attribute and the HTML template (if any)
	 * @since 3.1.0 N°4849
	 * @param bool $bHighlightPlaceholders If true add some extra HTML around placeholders to highlight them 
	 * @return string
	 */
	protected function BuildMessageBody(bool $bHighlightPlaceholders = false): string
	{
		// Wrap content with a specific class in order to apply styles of HTML fields through the emogrifier (see `css/email.scss`)
		$sBody = <<<HTML
<div class="email-is-html-content">
	{$this->Get('body')}
</div>
HTML;

		/**  @var ormDocument $oHtmlTemplate */
		$oHtmlTemplate = $this->Get('html_template');
		if ($oHtmlTemplate && !$oHtmlTemplate->IsEmpty()) {
			$sHtmlTemplate = $oHtmlTemplate->GetData();
			if (false !== mb_strpos($sHtmlTemplate, static::TEMPLATE_BODY_CONTENT)) {
				if ($bHighlightPlaceholders) {
					// Highlight the $content$ block
					$sBody = sprintf(static::CONTENT_HIGHLIGHT, $sBody);
				}
				$sBody = str_replace(static::TEMPLATE_BODY_CONTENT, $sBody, $oHtmlTemplate->GetData()); // str_replace is ok as long as both strings are well-formed UTF-8
			} else {
				$sBody = $oHtmlTemplate->GetData();
			}
		}
		if($bHighlightPlaceholders) {
			// Highlight all placeholders
			$sBody = preg_replace('/\\$([^$]+)\\$/', static::FIELD_HIGHLIGHT, $sBody);
		}
		return $sBody;
	}
	
	/**
	 * @since 3.1.0 N°4849
	 * @inheritDoc
	 * @see cmdbAbstractObject::DisplayBareRelations()
	 */
	public function DisplayBareRelations(WebPage $oPage, $bEditMode = false)
	{
		parent::DisplayBareRelations($oPage, false);
		if (!$bEditMode) {
			$oPage->SetCurrentTab('action_email__preview', Dict::S('ActionEmail:preview_tab'), Dict::S('ActionEmail:preview_tab+'));
			$sBody = $this->BuildMessageBody(true);
			TwigHelper::RenderIntoPage($oPage, APPROOT.'/', 'templates/datamodel/ActionEmail/email-notification-preview', ['iframe_content' => $sBody]);
		}
	}

	/**
	 * @since 3.1.0
	 * @inheritDoc
	 * @see cmdbAbstractObject::DoCheckToWrite()
	 */
	public function DoCheckToWrite()
	{
		parent::DoCheckToWrite();
		$oHtmlTemplate = $this->Get('html_template');
		if ($oHtmlTemplate && !$oHtmlTemplate->IsEmpty()) {
			if (false === mb_strpos($oHtmlTemplate->GetData(), static::TEMPLATE_BODY_CONTENT)) {
				$this->m_aCheckWarnings[] = Dict::Format('ActionEmail:content_placeholder_missing', static::TEMPLATE_BODY_CONTENT, Dict::S('Class:ActionEmail/Attribute:body'));
			}
		}
	}

	/**
	 * @inheritDoc
	 * @since 3.2.0
	 */
	public static function GetAsynchronousGlobalSetting(): bool
	{
		return utils::GetConfig()->Get('email_asynchronous');
	}
}

<?php
/**
 * Copyright (C) 2013-2020 Combodo SARL
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */

define('OBJECT_PROPERTIES_TAB', 'ObjectProperties');

define('HILIGHT_CLASS_CRITICAL', 'red');
define('HILIGHT_CLASS_WARNING', 'orange');
define('HILIGHT_CLASS_OK', 'green');
define('HILIGHT_CLASS_NONE', '');

define('MIN_WATCHDOG_INTERVAL', 15); // Minimum interval for the watchdog: 15s

require_once(APPROOT.'core/cmdbobject.class.inc.php');
require_once(APPROOT.'application/applicationextension.inc.php');
require_once(APPROOT.'application/utils.inc.php');
require_once(APPROOT.'application/applicationcontext.class.inc.php');
require_once(APPROOT.'application/ui.linkswidget.class.inc.php');
require_once(APPROOT.'application/ui.linksdirectwidget.class.inc.php');
require_once(APPROOT.'application/ui.passwordwidget.class.inc.php');
require_once(APPROOT.'application/ui.extkeywidget.class.inc.php');
require_once(APPROOT.'application/ui.htmleditorwidget.class.inc.php');
require_once(APPROOT.'application/datatable.class.inc.php');
require_once(APPROOT.'sources/renderer/console/consoleformrenderer.class.inc.php');
require_once(APPROOT.'sources/application/search/searchform.class.inc.php');
require_once(APPROOT.'sources/application/search/criterionparser.class.inc.php');
require_once(APPROOT.'sources/application/search/criterionconversionabstract.class.inc.php');
require_once(APPROOT.'sources/application/search/criterionconversion/criteriontooql.class.inc.php');
require_once(APPROOT.'sources/application/search/criterionconversion/criteriontosearchform.class.inc.php');

/**
 * Class cmdbAbstractObject
 */
abstract class cmdbAbstractObject extends CMDBObject implements iDisplay
{
	/** @var string ENUM_OBJECT_MODE_VIEW */
	const ENUM_OBJECT_MODE_VIEW = 'view';
	/** @var string ENUM_OBJECT_MODE_EDIT */
	const ENUM_OBJECT_MODE_EDIT = 'edit';
	/** @var string ENUM_OBJECT_MODE_CREATE */
	const ENUM_OBJECT_MODE_CREATE = 'create';
	/** @var string ENUM_OBJECT_MODE_STIMULUS */
	const ENUM_OBJECT_MODE_STIMULUS = 'stimulus';

	protected $m_iFormId; // The ID of the form used to edit the object (when in edition mode !)
	protected static $iGlobalFormId = 1;
	protected $aFieldsMap;

	/**
	 * If true, bypass IsActionAllowedOnAttribute when writing this object
	 *
	 * @var bool
	 */
	protected $bAllowWrite;
	/**
	 * @var bool
	 */
	protected $bAllowDelete;

	/**
	 * Constructor from a row of data (as a hash 'attcode' => value)
	 *
	 * @param array $aRow
	 * @param string $sClassAlias
	 * @param array $aAttToLoad
	 * @param array $aExtendedDataSpec
	 *
	 * @throws \CoreException
	 */
	public function __construct($aRow = null, $sClassAlias = '', $aAttToLoad = null, $aExtendedDataSpec = null)
	{
		parent::__construct($aRow, $sClassAlias, $aAttToLoad, $aExtendedDataSpec);
		$this->bAllowWrite = false;
		$this->bAllowDelete = false;
	}

	/**
	 * returns what will be the next ID for the forms
	 */
	public static function GetNextFormId()
	{
		return 1 + self::$iGlobalFormId;
	}

	public static function GetUIPage()
	{
		return 'UI.php';
	}

	/**
	 * @param \WebPage $oPage
	 * @param \DBObject $oObj
	 * @param array $aParams
	 *
	 * @throws \Exception
	 */
	public static function ReloadAndDisplay($oPage, $oObj, $aParams)
	{
		$oAppContext = new ApplicationContext();
		// Reload the page to let the "calling" page execute its 'onunload' method.
		// Note 1: The redirection MUST NOT be made via an HTTP "header" since onunload is only called when the actual content of the DOM
		// is replaced by some other content. So the "bouncing" page must provide some content (in our case a script making the redirection).
		// Note 2: make sure that the URL below is different from the one of the "Modify" button, otherwise the button will have no effect. This is why we add "&a=1" at the end !!!
		// Note 3: we use the toggle of a flag in the sessionStorage object to prevent an infinite loop of reloads in case the object is actually locked by another window
		$sSessionStorageKey = get_class($oObj).'_'.$oObj->GetKey();
		$sParams = '';
		foreach($aParams as $sName => $value)
		{
			$sParams .= $sName.'='.urlencode($value).'&'; // Always add a trailing &
		}
		$sUrl = utils::GetAbsoluteUrlAppRoot().'pages/'.$oObj->GetUIPage().'?'.$sParams.'class='.get_class($oObj).'&id='.$oObj->getKey().'&'.$oAppContext->GetForLink().'&a=1';
		$oPage->add_script(
			<<<EOF
	if (!sessionStorage.getItem('$sSessionStorageKey'))
	{
		sessionStorage.setItem('$sSessionStorageKey', 1);
		window.location.href= "$sUrl";
	}
	else
	{
		sessionStorage.removeItem('$sSessionStorageKey');
	}
EOF
		);

		$oObj->Reload();
		$oObj->DisplayDetails($oPage, false);
	}

	/**
	 * @param $sMessageId
	 * @param $sMessage
	 * @param $sSeverity
	 * @param $fRank
	 * @param bool $bMustNotExist
	 *
	 * @see SetSessionMessage()
	 * @since 2.6.0
	 */
	protected function SetSessionMessageFromInstance($sMessageId, $sMessage, $sSeverity, $fRank, $bMustNotExist = false)
	{
		$sObjectClass = get_class($this);
		$iObjectId = $this->GetKey();

		self::SetSessionMessage($sObjectClass, $iObjectId, $sMessageId, $sMessage, $sSeverity, $fRank);
	}

	/**
	 * Set a message displayed to the end-user next time this object will be displayed
	 * Messages are uniquely identified so that plugins can override standard messages (the final work is given to the
	 * last plugin to set the message for a given message id) In practice, standard messages are recorded at the end
	 * but they will not overwrite existing messages
	 *
	 * @param string $sClass The class of the object (must be the final class)
	 * @param int $iKey The identifier of the object
	 * @param string $sMessageId Your id or one of the well-known ids: 'create', 'update' and 'apply_stimulus'
	 * @param string $sMessage The HTML message (must be correctly escaped)
	 * @param string $sSeverity Any of the following: ok, info, error.
	 * @param float $fRank Ordering of the message: smallest displayed first (can be negative)
	 * @param bool $bMustNotExist Do not alter any existing message (considering the id)
	 *
	 * @see SetSessionMessageFromInstance() to call from within an instance
	 */
	public static function SetSessionMessage(
		$sClass, $iKey, $sMessageId, $sMessage, $sSeverity, $fRank, $bMustNotExist = false
	) {
		$sMessageKey = $sClass.'::'.$iKey;
		if (!isset($_SESSION['obj_messages'][$sMessageKey]))
		{
			$_SESSION['obj_messages'][$sMessageKey] = array();
		}
		if (!$bMustNotExist || !array_key_exists($sMessageId, $_SESSION['obj_messages'][$sMessageKey]))
		{
			$_SESSION['obj_messages'][$sMessageKey][$sMessageId] = array(
				'rank' => $fRank,
				'severity' => $sSeverity,
				'message' => $sMessage,
			);
		}
	}

	/**
	 * @param \WebPage $oPage
	 * @param bool $bEditMode
	 *
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \DictExceptionMissingString
	 * @throws \MySQLException
	 * @throws \OQLException
	 * @throws \Exception
	 */
	public function DisplayBareHeader(WebPage $oPage, $bEditMode = false)
	{
		// Standard Header with name, actions menu and history block
		//

		if (!$oPage->IsPrintableVersion())
		{
			// Is there a message for this object ??
			$aMessages = array();
			$aRanks = array();
			if (MetaModel::GetConfig()->Get('concurrent_lock_enabled'))
			{
				$aLockInfo = iTopOwnershipLock::IsLocked(get_class($this), $this->GetKey());
				if ($aLockInfo['locked'])
				{
					$aRanks[] = 0;
					$sName = $aLockInfo['owner']->GetName();
					if ($aLockInfo['owner']->Get('contactid') != 0)
					{
						$sName .= ' ('.$aLockInfo['owner']->Get('contactid_friendlyname').')';
					}
					$aResult['message'] = Dict::Format('UI:CurrentObjectIsLockedBy_User', $sName);
					$aMessages[] = "<div class=\"header_message message_error\">".Dict::Format('UI:CurrentObjectIsLockedBy_User',
							$sName)."</div>";
				}
			}
			$sMessageKey = get_class($this).'::'.$this->GetKey();
			if (array_key_exists('obj_messages', $_SESSION) && array_key_exists($sMessageKey,
					$_SESSION['obj_messages']))
			{
				foreach($_SESSION['obj_messages'][$sMessageKey] as $sMessageId => $aMessageData)
				{
					$sMsgClass = 'message_'.$aMessageData['severity'];
					if(!in_array("<div class=\"header_message $sMsgClass\">".$aMessageData['message']."</div>",$aMessages))
					{
						$aMessages[] = "<div class=\"header_message $sMsgClass\">".$aMessageData['message']."</div>";
						$aRanks[] = $aMessageData['rank'];
					}
				}
				unset($_SESSION['obj_messages'][$sMessageKey]);
			}
			array_multisort($aRanks, $aMessages);
			foreach($aMessages as $sMessage)
			{
				$oPage->add($sMessage);
			}
		}

		if (!$oPage->IsPrintableVersion())
		{
			// action menu
			$oSingletonFilter = new DBObjectSearch(get_class($this));
			$oSingletonFilter->AddCondition('id', $this->GetKey(), '=');
			$oBlock = new MenuBlock($oSingletonFilter, 'details', false);
			$oBlock->Display($oPage, -1);
		}

		// Master data sources
		$aIcons = array();
		if (!$oPage->IsPrintableVersion())
		{
			$oCreatorTask = null;
			$bCanBeDeletedByTask = false;
			$bCanBeDeletedByUser = true;
			$aMasterSources = array();
			$aSyncData = $this->GetSynchroData();
			if (count($aSyncData) > 0)
			{
				foreach($aSyncData as $iSourceId => $aSourceData)
				{
					$oDataSource = $aSourceData['source'];
					$oReplica = reset($aSourceData['replica']); // Take the first one!

					$sApplicationURL = $oDataSource->GetApplicationUrl($this, $oReplica);
					$sLink = $oDataSource->GetName();
					if (!empty($sApplicationURL))
					{
						$sLink = "<a href=\"$sApplicationURL\" target=\"_blank\">".$oDataSource->GetName()."</a>";
					}
					if ($oReplica->Get('status_dest_creator') == 1)
					{
						$oCreatorTask = $oDataSource;
						$bCreatedByTask = true;
					}
					else
					{
						$bCreatedByTask = false;
					}
					if ($bCreatedByTask)
					{
						$sDeletePolicy = $oDataSource->Get('delete_policy');
						if (($sDeletePolicy == 'delete') || ($sDeletePolicy == 'update_then_delete'))
						{
							$bCanBeDeletedByTask = true;
						}
						$sUserDeletePolicy = $oDataSource->Get('user_delete_policy');
						if ($sUserDeletePolicy == 'nobody')
						{
							$bCanBeDeletedByUser = false;
						}
						elseif (($sUserDeletePolicy == 'administrators') && !UserRights::IsAdministrator())
						{
							$bCanBeDeletedByUser = false;
						}
					}
					$aMasterSources[$iSourceId]['datasource'] = $oDataSource;
					$aMasterSources[$iSourceId]['url'] = $sLink;
					$aMasterSources[$iSourceId]['last_synchro'] = $oReplica->Get('status_last_seen');
				}

				if (is_object($oCreatorTask))
				{
					$sTaskUrl = $aMasterSources[$oCreatorTask->GetKey()]['url'];
					if (!$bCanBeDeletedByUser)
					{
						$sTip = "<p>".Dict::Format('Core:Synchro:TheObjectCannotBeDeletedByUser_Source',
								$sTaskUrl)."</p>";
					}
					else
					{
						$sTip = "<p>".Dict::Format('Core:Synchro:TheObjectWasCreatedBy_Source', $sTaskUrl)."</p>";
					}
					if ($bCanBeDeletedByTask)
					{
						$sTip .= "<p>".Dict::Format('Core:Synchro:TheObjectCanBeDeletedBy_Source', $sTaskUrl)."</p>";
					}
				}
				else
				{
					$sTip = "<p>".Dict::S('Core:Synchro:ThisObjectIsSynchronized')."</p>";
				}

				$sTip .= "<p><b>".Dict::S('Core:Synchro:ListOfDataSources')."</b></p>";
				foreach($aMasterSources as $aStruct)
				{
					// Formatting last synchro date
					$oDateTime = DateTime::createFromFormat('Y-m-d H:i:s', $aStruct['last_synchro']);
					$oDateTimeFormat = AttributeDateTime::GetFormat();
					$sLastSynchro = $oDateTimeFormat->Format($oDateTime);

					$oDataSource = $aStruct['datasource'];
					$sLink = $aStruct['url'];
					$sTip .= "<p style=\"white-space:nowrap\">".$oDataSource->GetIcon(true,
							'style="vertical-align:middle"')."&nbsp;$sLink<br/>";
					$sTip .= Dict::S('Core:Synchro:LastSynchro').'<br/>'.$sLastSynchro."</p>";
				}
				$sLabel = htmlentities(Dict::S('Tag:Synchronized'), ENT_QUOTES, 'UTF-8');
				$sSynchroTagId = 'synchro_icon-'.$this->GetKey();
				$aIcons[] = "<div class=\"tag\" id=\"$sSynchroTagId\"><span class=\"object-synchronized fas fa-lock fa-1x\">&nbsp;</span>&nbsp;$sLabel</div>";
				$sTip = addslashes($sTip);
				$oPage->add_ready_script("$('#$sSynchroTagId').qtip( { content: '$sTip', show: 'mouseover', hide: { fixed: true }, style: { name: 'dark', tip: 'topLeft' }, position: { corner: { target: 'bottomMiddle', tooltip: 'topLeft' }} } );");
			}
		}

		if ($this->IsArchived())
		{
			$sLabel = htmlentities(Dict::S('Tag:Archived'), ENT_QUOTES, 'UTF-8');
			$sTitle = htmlentities(Dict::S('Tag:Archived+'), ENT_QUOTES, 'UTF-8');
			$aIcons[] = "<div class=\"tag\" title=\"$sTitle\"><span class=\"object-archived fas fa-archive fa-1x\">&nbsp;</span>&nbsp;$sLabel</div>";
		}
		elseif ($this->IsObsolete())
		{
			$sLabel = htmlentities(Dict::S('Tag:Obsolete'), ENT_QUOTES, 'UTF-8');
			$sTitle = htmlentities(Dict::S('Tag:Obsolete+'), ENT_QUOTES, 'UTF-8');
			$aIcons[] = "<div class=\"tag\" title=\"$sTitle\"><span class=\"object-obsolete fas fa-eye-slash fa-1x\">&nbsp;</span>&nbsp;$sLabel</div>";
		}

		$sObjectIcon = $this->GetIcon();
		$sClassName = MetaModel::GetName(get_class($this));
		$sObjectName = $this->GetName();
		if (count($aIcons) > 0)
		{
			$sTags = '<div class="tags">'.implode('&nbsp;', $aIcons).'</div>';
		}
		else
		{
			$sTags = '';
		}

		$oPage->add(
			<<<EOF
<div class="page_header">
   <div class="object-details-header">
      <div class ="object-icon">$sObjectIcon</div>
      <div class ="object-infos">
		  <h1 class="object-name">$sClassName: <span class="hilite">$sObjectName</span></h1>
		  $sTags
      </div>
   </div>
</div>
EOF
		);
	}

	/**
	 * Display history tab of an object
	 *
	 * @param \WebPage $oPage
	 * @param bool $bEditMode
	 * @param int $iLimitCount
	 * @param int $iLimitStart
	 *
	 * @throws \CoreException
	 */
	public function DisplayBareHistory(WebPage $oPage, $bEditMode = false, $iLimitCount = 0, $iLimitStart = 0)
	{
		// history block (with as a tab)
		$oHistoryFilter = new DBObjectSearch('CMDBChangeOp');
		$oHistoryFilter->AddCondition('objkey', $this->GetKey(), '=');
		$oHistoryFilter->AddCondition('objclass', get_class($this), '=');
		$oBlock = new HistoryBlock($oHistoryFilter, 'table', false);
		$oBlock->SetLimit($iLimitCount, $iLimitStart);
		$oBlock->Display($oPage, 'history');
	}

	/**
	 * Display properties tab of an object
	 *
	 * @param \WebPage $oPage
	 * @param bool $bEditMode
	 * @param string $sPrefix
	 * @param array $aExtraParams
	 *
	 * @return array
	 * @throws \CoreException
	 */
	public function DisplayBareProperties(WebPage $oPage, $bEditMode = false, $sPrefix = '', $aExtraParams = array())
	{
		$aFieldsMap = $this->GetBareProperties($oPage, $bEditMode, $sPrefix, $aExtraParams);


		if (!isset($aExtraParams['disable_plugins']) || !$aExtraParams['disable_plugins'])
		{
			/** @var iApplicationUIExtension $oExtensionInstance */
			foreach(MetaModel::EnumPlugins('iApplicationUIExtension') as $oExtensionInstance)
			{
				$oExtensionInstance->OnDisplayProperties($this, $oPage, $bEditMode);
			}
		}

		// Special case to display the case log, if any...
		// WARNING: if you modify the loop below, also check the corresponding code in UpdateObject and DisplayModifyForm
		foreach(MetaModel::ListAttributeDefs(get_class($this)) as $sAttCode => $oAttDef)
		{
			if ($oAttDef instanceof AttributeCaseLog)
			{
				$sComment = (isset($aExtraParams['fieldsComments'][$sAttCode])) ? $aExtraParams['fieldsComments'][$sAttCode] : '';
				$this->DisplayCaseLog($oPage, $sAttCode, $sComment, $sPrefix, $bEditMode);
				$aFieldsMap[$sAttCode] = $this->m_iFormId.'_'.$sAttCode;
			}
		}

		return $aFieldsMap;
	}

	/**
	 * Add a field to the map: attcode => id used when building a form
	 *
	 * @param string $sAttCode The attribute code of the field being edited
	 * @param string $sInputId The unique ID of the control/widget in the page
	 */
	protected function AddToFieldsMap($sAttCode, $sInputId)
	{
		$this->aFieldsMap[$sAttCode] = $sInputId;
	}

	/**
	 * @param \WebPage $oPage
	 * @param $sAttCode
	 *
	 * @throws \Exception
	 */
	public function DisplayDashboard($oPage, $sAttCode)
	{
		$sClass = get_class($this);
		$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);

		if (!$oAttDef instanceof AttributeDashboard)
		{
			throw new CoreException(Dict::S('UI:Error:InvalidDashboard'));
		}

		// Load the dashboard
		$oDashboard = $oAttDef->GetDashboard();
		if (is_null($oDashboard))
		{
			throw new CoreException(Dict::S('UI:Error:InvalidDashboard'));
		}

		$bCanEdit = UserRights::IsAdministrator() || $oAttDef->IsUserEditable();
		$sDivId = $oDashboard->GetId();
		$oPage->add('<div class="dashboard_contents" id="'.$sDivId.'">');
		$aExtraParams = array(
			'query_params' => $this->ToArgsForQuery(),
			'dashboard_div_id' => $sDivId,
		);
		$oDashboard->Render($oPage, false, $aExtraParams, $bCanEdit);
		$oPage->add('</div>');
	}

	/**
	 * @param \WebPage $oPage
	 * @param bool $bEditMode
	 *
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \DictExceptionMissingString
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 * @throws \Exception
	 */
	public function DisplayBareRelations(WebPage $oPage, $bEditMode = false)
	{
		$aRedundancySettings = $this->FindVisibleRedundancySettings();

		// Related objects: display all the linkset attributes, each as a separate tab
		// In the order described by the 'display' ZList
		$aList = $this->FlattenZList(MetaModel::GetZListItems(get_class($this), 'details'));
		if (count($aList) == 0)
		{
			// Empty ZList defined, display all the linkedset attributes defined
			$aList = array_keys(MetaModel::ListAttributeDefs(get_class($this)));
		}
		$sClass = get_class($this);
		foreach($aList as $sAttCode)
		{
			$oAttDef = MetaModel::GetAttributeDef(get_class($this), $sAttCode);
			if ($oAttDef instanceof AttributeDashboard)
			{
				if ($bEditMode)
				{
					continue;
				}
				$oPage->AddAjaxTab($oAttDef->GetLabel(), utils::GetAbsoluteUrlAppRoot().'pages/ajax.render.php?operation=dashboard&class='.get_class($this).'&id='.$this->GetKey().'&attcode='.$oAttDef->GetCode(), true, 'Class:'.$sClass.'/Attribute:'.$sAttCode);
				continue;
			}

			// Display mode
			if (!$oAttDef->IsLinkset())
			{
				continue;
			} // Process only linkset attributes...

			$sLinkedClass = $oAttDef->GetLinkedClass();

			// Filter out links pointing to obsolete objects (if relevant)
			$oOrmLinkSet = $this->Get($sAttCode);
			$oLinkSet = $oOrmLinkSet->ToDBObjectSet(utils::ShowObsoleteData());

			$iCount = $oLinkSet->Count();
			if ($this->IsNew())
			{
				$iFlags = $this->GetInitialStateAttributeFlags($sAttCode);
			}
			else
			{
				$iFlags = $this->GetAttributeFlags($sAttCode);
			}
			// Adjust the flags according to user rights
			if ($oAttDef->IsIndirect())
			{
				$oLinkingAttDef = MetaModel::GetAttributeDef($sLinkedClass, $oAttDef->GetExtKeyToRemote());
				$sTargetClass = $oLinkingAttDef->GetTargetClass();
				// n:n links => must be allowed to modify the linking class AND  read the target class in order to edit the linkedset
				if (!UserRights::IsActionAllowed($sLinkedClass,
						UR_ACTION_MODIFY) || !UserRights::IsActionAllowed($sTargetClass, UR_ACTION_READ))
				{
					$iFlags |= OPT_ATT_READONLY;
				}
				// n:n links => must be allowed to read the linking class AND  the target class in order to display the linkedset
				if (!UserRights::IsActionAllowed($sLinkedClass,
						UR_ACTION_READ) || !UserRights::IsActionAllowed($sTargetClass, UR_ACTION_READ))
				{
					$iFlags |= OPT_ATT_HIDDEN;
				}
			}
			else
			{
				// 1:n links => must be allowed to modify the linked class in order to edit the linkedset
				if (!UserRights::IsActionAllowed($sLinkedClass, UR_ACTION_MODIFY))
				{
					$iFlags |= OPT_ATT_READONLY;
				}
				// 1:n links => must be allowed to read the linked class in order to display the linkedset
				if (!UserRights::IsActionAllowed($sLinkedClass, UR_ACTION_READ))
				{
					$iFlags |= OPT_ATT_HIDDEN;
				}
			}
			// Non-readable/hidden linkedset... don't display anything
			if ($iFlags & OPT_ATT_HIDDEN)
			{
				continue;
			}

			$sCount = ($iCount != 0) ? " ($iCount)" : "";
			$oPage->SetCurrentTab('Class:'.$sClass.'/Attribute:'.$sAttCode, $oAttDef->GetLabel().$sCount);

			$aArgs = array('this' => $this);
			$bReadOnly = ($iFlags & (OPT_ATT_READONLY | OPT_ATT_SLAVE));
			if ($bEditMode && (!$bReadOnly))
			{
				$sInputId = $this->m_iFormId.'_'.$sAttCode;

				if ($oAttDef->IsIndirect())
				{
					$oLinkingAttDef = MetaModel::GetAttributeDef($sLinkedClass, $oAttDef->GetExtKeyToRemote());
					$sTargetClass = $oLinkingAttDef->GetTargetClass();
				}
				else
				{
					$sTargetClass = $sLinkedClass;
				}
				$oPage->p(MetaModel::GetClassIcon($sTargetClass)."&nbsp;".$oAttDef->GetDescription().'<span id="busy_'.$sInputId.'"></span>');

				$sDisplayValue = ''; // not used
				$sHTMLValue = "<span id=\"field_{$sInputId}\">".self::GetFormElementForField($oPage, $sClass, $sAttCode,
						$oAttDef, $oLinkSet, $sDisplayValue, $sInputId, '', $iFlags, $aArgs).'</span>';
				$this->AddToFieldsMap($sAttCode, $sInputId);
				$oPage->add($sHTMLValue);
			}
			else
			{
				// Display mode
				if (!$oAttDef->IsIndirect())
				{
					// 1:n links
					$sTargetClass = $sLinkedClass;

					$aDefaults = array($oAttDef->GetExtKeyToMe() => $this->GetKey());
					$oAppContext = new ApplicationContext();
					foreach($oAppContext->GetNames() as $sKey)
					{
						// The linked object inherits the parent's value for the context
						if (MetaModel::IsValidAttCode($sClass, $sKey))
						{
							$aDefaults[$sKey] = $this->Get($sKey);
						}
					}
					$aParams = array(
						'target_attr' => $oAttDef->GetExtKeyToMe(),
						'object_id' => $this->GetKey(),
						'menu' => MetaModel::GetConfig()->Get('allow_menu_on_linkset'),
						//'menu_actions_target' => '_blank',
						'default' => $aDefaults,
						'table_id' => $sClass.'_'.$sAttCode,
					);
				}
				else
				{
					// n:n links
					$oLinkingAttDef = MetaModel::GetAttributeDef($sLinkedClass, $oAttDef->GetExtKeyToRemote());
					$sTargetClass = $oLinkingAttDef->GetTargetClass();
					$aParams = array(
						'link_attr' => $oAttDef->GetExtKeyToMe(),
						'object_id' => $this->GetKey(),
						'target_attr' => $oAttDef->GetExtKeyToRemote(),
						'view_link' => false,
						'menu' => false,
						//'menu_actions_target' => '_blank',
						'display_limit' => true, // By default limit the list to speed up the initial load & display
						'table_id' => $sClass.'_'.$sAttCode,
					);
				}
				$oPage->p(MetaModel::GetClassIcon($sTargetClass)."&nbsp;".$oAttDef->GetDescription());
				$oBlock = new DisplayBlock($oLinkSet->GetFilter(), 'list', false);
				$oBlock->Display($oPage, 'rel_'.$sAttCode, $aParams);
			}
			if (array_key_exists($sAttCode, $aRedundancySettings))
			{
				foreach($aRedundancySettings[$sAttCode] as $oRedundancyAttDef)
				{
					$sRedundancyAttCode = $oRedundancyAttDef->GetCode();
					$sValue = $this->Get($sRedundancyAttCode);
					$iRedundancyFlags = $this->GetFormAttributeFlags($sRedundancyAttCode);
					$bRedundancyReadOnly = ($iRedundancyFlags & (OPT_ATT_READONLY | OPT_ATT_SLAVE));

					$oPage->add('<fieldset>');
					$oPage->add('<legend>'.$oRedundancyAttDef->GetLabel().'</legend>');
					if ($bEditMode && (!$bRedundancyReadOnly))
					{
						$sInputId = $this->m_iFormId.'_'.$sRedundancyAttCode;
						$oPage->add("<span id=\"field_{$sInputId}\">".self::GetFormElementForField($oPage, $sClass,
								$sRedundancyAttCode, $oRedundancyAttDef, $sValue, '', $sInputId, '', $iFlags,
								$aArgs).'</span>');
					}
					else
					{
						$oPage->add($oRedundancyAttDef->GetDisplayForm($sValue, $oPage, false, $this->m_iFormId));
					}
					$oPage->add('</fieldset>');
				}
			}
		}
		$oPage->SetCurrentTab('');

		/** @var \iApplicationUIExtension $oExtensionInstance */
		foreach(MetaModel::EnumPlugins('iApplicationUIExtension') as $oExtensionInstance)
		{
			$oExtensionInstance->OnDisplayRelations($this, $oPage, $bEditMode);
		}

		// Display Notifications after the other tabs since this tab disappears in edition
		if (!$bEditMode)
		{
			// Look for any trigger that considers this object as "In Scope"
			// If any trigger has been found then display a tab with notifications
			//			
			$oTriggerSet = new CMDBObjectSet(new DBObjectSearch('Trigger'));
			$aTriggers = array();
			while ($oTrigger = $oTriggerSet->Fetch())
			{
				if ($oTrigger->IsInScope($this))
				{
					$aTriggers[] = $oTrigger->GetKey();
				}
			}
			if (count($aTriggers) > 0)
			{
				$iId = $this->GetKey();
				$aParams = array('triggers' => $aTriggers, 'id' => $iId);
				$aNotifSearches = array();
				$iNotifsCount = 0;
				$aNotificationClasses = MetaModel::EnumChildClasses('EventNotification', ENUM_CHILD_CLASSES_EXCLUDETOP);
				foreach($aNotificationClasses as $sNotifClass)
				{
					$aNotifSearches[$sNotifClass] = DBObjectSearch::FromOQL("SELECT $sNotifClass AS Ev JOIN Trigger AS T ON Ev.trigger_id = T.id WHERE T.id IN (:triggers) AND Ev.object_id = :id");
					$aNotifSearches[$sNotifClass]->SetInternalParams($aParams);
					$oNotifSet = new DBObjectSet($aNotifSearches[$sNotifClass], array());
					$iNotifsCount += $oNotifSet->Count();
				}
				// Display notifications regarding the object: on block per subclass to have the interesting columns
				$sCount = ($iNotifsCount > 0) ? ' ('.$iNotifsCount.')' : '';
				$oPage->SetCurrentTab('UI:NotificationsTab', Dict::S('UI:NotificationsTab').$sCount);

				foreach($aNotificationClasses as $sNotifClass)
				{
					$oPage->p(MetaModel::GetClassIcon($sNotifClass, true).'&nbsp;'.MetaModel::GetName($sNotifClass));
					$oBlock = new DisplayBlock($aNotifSearches[$sNotifClass], 'list', false);
					$oBlock->Display($oPage, 'notifications_'.$sNotifClass, array('menu' => false));
				}
			}
		}
	}

	/**
	 * @param \WebPage $oPage
	 * @param bool $bEditMode
	 * @param string $sPrefix
	 * @param array $aExtraParams
	 *
	 * @return array
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \DictExceptionMissingString
	 * @throws \MySQLException
	 * @throws \OQLException
	 * @throws \Exception
	 */
	public function GetBareProperties(WebPage $oPage, $bEditMode, $sPrefix, $aExtraParams = array())
	{
		$sStateAttCode = MetaModel::GetStateAttributeCode(get_class($this));
		$sClass = get_class($this);
		$aDetailsList = MetaModel::GetZListItems($sClass, 'details');
		$aDetailsStruct = self::ProcessZlist($aDetailsList, array('UI:PropertiesTab' => array()), 'UI:PropertiesTab', 'col1', '');
		// Compute the list of properties to display, first the attributes in the 'details' list, then 
		// all the remaining attributes that are not external fields
		$sEditMode = ($bEditMode) ? 'edit' : 'view';
		$aDetails = array();
		$iInputId = 0;
		$aFieldsMap = array();
		$aFieldsComments = (isset($aExtraParams['fieldsComments'])) ? $aExtraParams['fieldsComments'] : array();
		$aExtraFlags = (isset($aExtraParams['fieldsFlags'])) ? $aExtraParams['fieldsFlags'] : array();

		foreach($aDetailsStruct as $sTab => $aCols)
		{
			$aDetails[$sTab] = array();
			$aTableStyles[] = 'vertical-align:top';
			$aTableClasses = array();
			$aColStyles[] = 'vertical-align:top';
			$aColClasses = array();

			ksort($aCols);
			$iColCount = count($aCols);
			if ($iColCount > 1)
			{
				$aTableClasses[] = 'n-cols-details';
				$aTableClasses[] = $iColCount.'-cols-details';

				$aColStyles[] = 'width:'.floor(100 / $iColCount).'%';
			}
			else
			{
				$aTableClasses[] = 'one-col-details';
			}

			$oPage->SetCurrentTab($sTab);
			$oPage->add('<table style="'.implode('; ', $aTableStyles).'" class="'.implode(' ',
					$aTableClasses).'" data-mode="'.$sEditMode.'"><tr>');
			foreach($aCols as $sColIndex => $aFieldsets)
			{
				$oPage->add('<td style="'.implode('; ', $aColStyles).'" class="'.implode(' ', $aColClasses).'">');
				$sPreviousLabel = '';
				$aDetails[$sTab][$sColIndex] = array();
				foreach($aFieldsets as $sFieldsetName => $aFields)
				{
					if (!empty($sFieldsetName) && ($sFieldsetName[0] != '_'))
					{
						$sLabel = $sFieldsetName;
					}
					else
					{
						$sLabel = '';
					}
					if ($sLabel != $sPreviousLabel)
					{
						if (!empty($sPreviousLabel))
						{
							$oPage->add('<fieldset>');
							$oPage->add('<legend>'.Dict::S($sPreviousLabel).'</legend>');
						}
						$oPage->Details($aDetails[$sTab][$sColIndex]);
						if (!empty($sPreviousLabel))
						{
							$oPage->add('</fieldset>');
						}
						$aDetails[$sTab][$sColIndex] = array();
						$sPreviousLabel = $sLabel;
					}
					foreach($aFields as $sAttCode)
					{
						$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
						$sAttDefClass = get_class($oAttDef);
						$sAttLabel = MetaModel::GetLabel($sClass, $sAttCode);

						if ($bEditMode)
						{
							$sComments = isset($aFieldsComments[$sAttCode]) ? $aFieldsComments[$sAttCode] : '';
							$sInfos = '';
							$iFlags = $this->GetFormAttributeFlags($sAttCode);
							if (array_key_exists($sAttCode, $aExtraFlags))
							{
								// the caller may override some flags if needed
								$iFlags = $iFlags | $aExtraFlags[$sAttCode];
							}
							if ((!$oAttDef->IsLinkSet()) && (($iFlags & OPT_ATT_HIDDEN) == 0) && !($oAttDef instanceof AttributeDashboard))
							{
								$sInputId = $this->m_iFormId.'_'.$sAttCode;
								if ($oAttDef->IsWritable())
								{
									if ($sStateAttCode == $sAttCode)
									{
										// State attribute is always read-only from the UI
										$sHTMLValue = $this->GetStateLabel();
										$val = array(
											'label' => '<label>'.$oAttDef->GetLabel().'</label>',
											'value' => $sHTMLValue,
											'comments' => $sComments,
											'infos' => $sInfos,
										);
									}
									else
									{
										if ($iFlags & (OPT_ATT_READONLY | OPT_ATT_SLAVE))
										{
											// Check if the attribute is not read-only because of a synchro...
											if ($iFlags & OPT_ATT_SLAVE)
											{
												$aReasons = array();
												$this->GetSynchroReplicaFlags($sAttCode, $aReasons);
												$sSynchroIcon = "&nbsp;<img id=\"synchro_$sInputId\" src=\"../images/transp-lock.png\" style=\"vertical-align:middle\"/>";
												$sTip = '';
												foreach($aReasons as $aRow)
												{
													$sDescription = htmlentities($aRow['description'], ENT_QUOTES,
														'UTF-8');
													$sDescription = str_replace(array("\r\n", "\n"), "<br/>",
														$sDescription);
													$sTip .= "<div class='synchro-source'>";
													$sTip .= "<div class='synchro-source-title'>Synchronized with {$aRow['name']}</div>";
													$sTip .= "<div class='synchro-source-description'>$sDescription</div>";
												}
												$sTip = addslashes($sTip);
												$oPage->add_ready_script("$('#synchro_$sInputId').qtip( { content: '$sTip', show: 'mouseover', hide: 'mouseout', style: { name: 'dark', tip: 'leftTop' }, position: { corner: { target: 'rightMiddle', tooltip: 'leftTop' }} } );");
												$sComments = $sSynchroIcon;
											}

											// Attribute is read-only
											$sHTMLValue = "<span id=\"field_{$sInputId}\">".$this->GetAsHTML($sAttCode).'</span>';
										}
										else
										{
											$sValue = $this->Get($sAttCode);
											$sDisplayValue = $this->GetEditValue($sAttCode);
											$aArgs = array('this' => $this, 'formPrefix' => $sPrefix);
											$sHTMLValue = "".self::GetFormElementForField($oPage, $sClass, $sAttCode,
													$oAttDef, $sValue, $sDisplayValue, $sInputId, '', $iFlags,
													$aArgs).'';
										}
										$aFieldsMap[$sAttCode] = $sInputId;
										$val = array(
											'label' => '<span title="'.$oAttDef->GetDescription().'">'.$oAttDef->GetLabel().'</span>',
											'value' => $sHTMLValue,
											'comments' => $sComments,
											'infos' => $sInfos,
										);
									}
								}
								else
								{
									$val = array(
										'label' => '<span title="'.$oAttDef->GetDescription().'">'.$oAttDef->GetLabel().'</span>',
										'value' => "<span id=\"field_{$sInputId}\">".$this->GetAsHTML($sAttCode)."</span>",
										'comments' => $sComments,
										'infos' => $sInfos,
									);
									$aFieldsMap[$sAttCode] = $sInputId;
								}
							}
							else
							{
								$val = null; // Skip this field
							}
						}
						else
						{
							// !bEditMode
							$val = $this->GetFieldAsHtml($sClass, $sAttCode, $sStateAttCode);
						}

						if ($val != null)
						{
							// Add extra data for markup generation
							// - Attribute code and AttributeDef. class
							$val['attcode'] = $sAttCode;
							$val['atttype'] = $sAttDefClass;
							$val['attlabel'] = $sAttLabel;
							$val['attflags'] = ($bEditMode) ? $this->GetFormAttributeFlags($sAttCode) : OPT_ATT_READONLY;

							// - How the field should be rendered
							$val['layout'] = (in_array($oAttDef->GetEditClass(), static::GetAttEditClassesToRenderAsLargeField())) ? 'large' : 'small';

							// - For simple fields, we get the raw (stored) value as well
							$bExcludeRawValue = false;
							foreach (static::GetAttDefClassesToExcludeFromMarkupMetadataRawValue() as $sAttDefClassToExclude)
							{
								if (is_a($sAttDefClass, $sAttDefClassToExclude, true))
								{
									$bExcludeRawValue = true;
									break;
								}
							}
							$val['value_raw'] = ($bExcludeRawValue === false) ? $this->Get($sAttCode) : '';

							// The field is visible, add it to the current column
							$aDetails[$sTab][$sColIndex][] = $val;
							$iInputId++;
						}
					}
				}
				if (!empty($sPreviousLabel))
				{
					$oPage->add('<fieldset>');
					$oPage->add('<legend>'.Dict::S($sFieldsetName).'</legend>');
				}
				$oPage->Details($aDetails[$sTab][$sColIndex]);
				if (!empty($sPreviousLabel))
				{
					$oPage->add('</fieldset>');
				}
				$oPage->add('</td>');
			}
			$oPage->add('</tr></table>');
		}

		return $aFieldsMap;
	}


	/**
	 * @param \WebPage $oPage
	 * @param bool $bEditMode
	 *
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \DictExceptionMissingString
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 * @throws \Exception
	 */
	public function DisplayDetails(WebPage $oPage, $bEditMode = false)
	{
		$sClass = get_class($this);
		$iKey = $this->GetKey();
		$sMode = static::ENUM_OBJECT_MODE_VIEW;

		$sTemplate = Utils::ReadFromFile(MetaModel::GetDisplayTemplate($sClass));
		if (!empty($sTemplate))
		{
			$oTemplate = new DisplayTemplate($sTemplate);
			// Note: to preserve backward compatibility with home-made templates, the placeholder '$pkey$' has been preserved
			//       but the preferred method is to use '$id$'
			$oTemplate->Render($oPage, array(
				'class_name' => MetaModel::GetName($sClass),
				'class' => $sClass,
				'pkey' => $iKey,
				'id' => $iKey,
				'name' => $this->GetName(),
			));
		}
		else
		{
			// Object's details
			// template not found display the object using the *old style*
			$oPage->add(<<<HTML
<!-- Beginning of object-details -->
<div id="search-widget-results-outer" class="object-details" data-object-class="$sClass" data-object-id="$iKey" data-object-mode="$sMode">
HTML
			);
			$this->DisplayBareHeader($oPage, $bEditMode);
			/** @var \iTopWebPage $oPage */
			$oPage->AddTabContainer(OBJECT_PROPERTIES_TAB);
			$oPage->SetCurrentTabContainer(OBJECT_PROPERTIES_TAB);
			$oPage->SetCurrentTab('UI:PropertiesTab');
			$this->DisplayBareProperties($oPage, $bEditMode);
			$this->DisplayBareRelations($oPage, $bEditMode);
			//$oPage->SetCurrentTab('UI:HistoryTab');
			//$this->DisplayBareHistory($oPage, $bEditMode);
			$oPage->AddAjaxTab('UI:HistoryTab',
				utils::GetAbsoluteUrlAppRoot().'pages/ajax.render.php?operation=history&class='.$sClass.'&id='.$iKey);
			$oPage->add(<<<HTML
</div><!-- End of object-details -->
HTML
			);
		}
	}

	/**
	 * @param \WebPage $oPage
	 *
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \DictExceptionMissingString
	 * @throws \Exception
	 */
	public function DisplayPreview(WebPage $oPage)
	{
		$aDetails = array();
		$sClass = get_class($this);
		$aList = MetaModel::GetZListItems($sClass, 'preview');
		foreach($aList as $sAttCode)
		{
			$aDetails[] = array(
				'label' => MetaModel::GetLabel($sClass, $sAttCode),
				'value' => $this->GetAsHTML($sAttCode),
			);
		}
		$oPage->details($aDetails);
	}

	/**
	 * @param \WebPage $oPage
	 * @param \CMDBObjectSet $oSet
	 * @param array $aExtraParams
	 *
	 * @throws \ApplicationException
	 * @throws \CoreException
	 */
	public static function DisplaySet(WebPage $oPage, CMDBObjectSet $oSet, $aExtraParams = array())
	{
		$oPage->add(self::GetDisplaySet($oPage, $oSet, $aExtraParams));
	}

	/**
	 * Simplified version of GetDisplaySet() with less "decoration" around the table (and no paging)
	 * that fits better into a printed document (like a PDF or a printable view)
	 *
	 * @param WebPage $oPage
	 * @param DBObjectSet $oSet
	 * @param array $aExtraParams
	 *
	 * @return string The HTML representation of the table
	 * @throws \CoreException
	 */
	public static function GetDisplaySetForPrinting(WebPage $oPage, DBObjectSet $oSet, $aExtraParams = array())
	{
		$sTableId = isset($aExtraParams['table_id']) ? $aExtraParams['table_id'] : null;

		$bViewLink = true;
		$sSelectMode = 'none';
		$iListId = $sTableId;
		$sClassAlias = $oSet->GetClassAlias();
		$sClassName = $oSet->GetClass();
		$sZListName = 'list';
		$aClassAliases = array($sClassAlias => $sClassName);
		$aList = cmdbAbstractObject::FlattenZList(MetaModel::GetZListItems($sClassName, $sZListName));

		$oDataTable = new PrintableDataTable($iListId, $oSet, $aClassAliases, $sTableId);
		$oSettings = DataTableSettings::GetDataModelSettings($aClassAliases, $bViewLink, array($sClassAlias => $aList));
		$oSettings->iDefaultPageSize = 0;
		$oSettings->aSortOrder = MetaModel::GetOrderByDefault($sClassName);

		return $oDataTable->Display($oPage, $oSettings, false /* $bDisplayMenu */, $sSelectMode, $bViewLink,
			$aExtraParams);

	}

	/**
	 * Get the HTML fragment corresponding to the display of a table representing a set of objects
	 *
	 * @see DisplayBlock to get a similar table but with the JS for pagination & sorting
	 *
	 * @param CMDBObjectSet The set of objects to display
	 * @param array $aExtraParams Some extra configuration parameters to tweak the behavior of the display
	 *
	 * @param WebPage $oPage The page object is used for out-of-band information (mostly scripts) output
	 *
	 * @return String The HTML fragment representing the table of objects. <b>Warning</b> : no JS added to handled
	 *     pagination or table sorting !
	 *
	 * @throws \CoreException*@throws \Exception
	 * @throws \ApplicationException
	 */
	public static function GetDisplaySet(WebPage $oPage, CMDBObjectSet $oSet, $aExtraParams = array())
	{
		if ($oPage->IsPrintableVersion() || $oPage->is_pdf())
		{
			return self::GetDisplaySetForPrinting($oPage, $oSet, $aExtraParams);
		}

		if (empty($aExtraParams['currentId']))
		{
			$iListId = $oPage->GetUniqueId(); // Works only if not in an Ajax page !!
		}
		else
		{
			$iListId = $aExtraParams['currentId'];
		}

		// Initialize and check the parameters
		$bViewLink = isset($aExtraParams['view_link']) ? $aExtraParams['view_link'] : true;
		$sLinkageAttribute = isset($aExtraParams['link_attr']) ? $aExtraParams['link_attr'] : '';
		$iLinkedObjectId = isset($aExtraParams['object_id']) ? $aExtraParams['object_id'] : 0;
		$sTargetAttr = isset($aExtraParams['target_attr']) ? $aExtraParams['target_attr'] : '';
		if (!empty($sLinkageAttribute))
		{
			if ($iLinkedObjectId == 0)
			{
				// if 'links' mode is requested the id of the object to link to must be specified
				throw new ApplicationException(Dict::S('UI:Error:MandatoryTemplateParameter_object_id'));
			}
			if ($sTargetAttr == '')
			{
				// if 'links' mode is requested the d of the object to link to must be specified
				throw new ApplicationException(Dict::S('UI:Error:MandatoryTemplateParameter_target_attr'));
			}
		}
		$bDisplayMenu = isset($aExtraParams['menu']) ? $aExtraParams['menu'] == true : true;
		$bSelectMode = isset($aExtraParams['selection_mode']) ? $aExtraParams['selection_mode'] == true : false;
		$bSingleSelectMode = isset($aExtraParams['selection_type']) ? ($aExtraParams['selection_type'] == 'single') : false;

		$aExtraFieldsRaw = isset($aExtraParams['extra_fields']) ? explode(',',
			trim($aExtraParams['extra_fields'])) : array();
		$aExtraFields = array();
		foreach($aExtraFieldsRaw as $sFieldName)
		{
			// Ignore attributes not of the main queried class
			if (preg_match('/^(.*)\.(.*)$/', $sFieldName, $aMatches))
			{
				$sClassAlias = $aMatches[1];
				$sAttCode = $aMatches[2];
				if ($sClassAlias == $oSet->GetFilter()->GetClassAlias())
				{
					$aExtraFields[] = $sAttCode;
				}
			}
			else
			{
				$aExtraFields[] = $sFieldName;
			}
		}
		$sClassName = $oSet->GetFilter()->GetClass();
		$sZListName = isset($aExtraParams['zlist']) ? ($aExtraParams['zlist']) : 'list';
		if ($sZListName !== false)
		{
			$aList = self::FlattenZList(MetaModel::GetZListItems($sClassName, $sZListName));
			$aList = array_merge($aList, $aExtraFields);
		}
		else
		{
			$aList = $aExtraFields;
		}

		// Filter the list to removed linked set since we are not able to display them here
		foreach($aList as $index => $sAttCode)
		{
			$oAttDef = MetaModel::GetAttributeDef($sClassName, $sAttCode);
			if ($oAttDef instanceof AttributeLinkedSet)
			{
				// Removed from the display list
				unset($aList[$index]);
			}
		}


		if (!empty($sLinkageAttribute))
		{
			// The set to display is in fact a set of links between the object specified in the $sLinkageAttribute
			// and other objects...
			// The display will then group all the attributes related to the link itself:
			// | Link_attr1 | link_attr2 | ... || Object_attr1 | Object_attr2 | Object_attr3 | .. | Object_attr_n |
			$aDisplayList = array();
			$aAttDefs = MetaModel::ListAttributeDefs($sClassName);
			assert(isset($aAttDefs[$sLinkageAttribute]));
			$oAttDef = $aAttDefs[$sLinkageAttribute];
			assert($oAttDef->IsExternalKey());
			// First display all the attributes specific to the link record
			foreach($aList as $sLinkAttCode)
			{
				$oLinkAttDef = $aAttDefs[$sLinkAttCode];
				if ((!$oLinkAttDef->IsExternalKey()) && (!$oLinkAttDef->IsExternalField()))
				{
					$aDisplayList[] = $sLinkAttCode;
				}
			}
			// Then display all the attributes neither specific to the link record nor to the 'linkage' object (because the latter are constant)
			foreach($aList as $sLinkAttCode)
			{
				$oLinkAttDef = $aAttDefs[$sLinkAttCode];
				if (($oLinkAttDef->IsExternalKey() && ($sLinkAttCode != $sLinkageAttribute))
					|| ($oLinkAttDef->IsExternalField() && ($oLinkAttDef->GetKeyAttCode() != $sLinkageAttribute)))
				{
					$aDisplayList[] = $sLinkAttCode;
				}
			}
			// First display all the attributes specific to the link
			// Then display all the attributes linked to the other end of the relationship
			$aList = $aDisplayList;
		}

		$sSelectMode = 'none';
		if ($bSelectMode)
		{
			$sSelectMode = $bSingleSelectMode ? 'single' : 'multiple';
		}

		$sClassAlias = $oSet->GetClassAlias();
		$bDisplayLimit = isset($aExtraParams['display_limit']) ? $aExtraParams['display_limit'] : true;

		$sTableId = isset($aExtraParams['table_id']) ? $aExtraParams['table_id'] : null;
		$aClassAliases = array($sClassAlias => $sClassName);
		$oDataTable = new DataTable($iListId, $oSet, $aClassAliases, $sTableId);
		$oSettings = DataTableSettings::GetDataModelSettings($aClassAliases, $bViewLink, array($sClassAlias => $aList));

		if ($bDisplayLimit)
		{
			$iDefaultPageSize = appUserPreferences::GetPref('default_page_size',
				MetaModel::GetConfig()->GetMinDisplayLimit());
			$oSettings->iDefaultPageSize = $iDefaultPageSize;
		}
		else
		{
			$oSettings->iDefaultPageSize = 0;
		}
		$oSettings->aSortOrder = MetaModel::GetOrderByDefault($sClassName);

		return $oDataTable->Display($oPage, $oSettings, $bDisplayMenu, $sSelectMode, $bViewLink, $aExtraParams);
	}

	/**
	 * @param \WebPage $oPage
	 * @param \CMDBObjectSet $oSet
	 * @param array $aExtraParams
	 *
	 * @return string
	 * @throws \CoreException
	 * @throws \DictExceptionMissingString
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 */
	public static function GetDisplayExtendedSet(WebPage $oPage, CMDBObjectSet $oSet, $aExtraParams = array())
	{
		if (empty($aExtraParams['currentId']))
		{
			$iListId = $oPage->GetUniqueId(); // Works only if not in an Ajax page !!
		}
		else
		{
			$iListId = $aExtraParams['currentId'];
		}
		$aList = array();

		// Initialize and check the parameters
		$bViewLink = isset($aExtraParams['view_link']) ? $aExtraParams['view_link'] : true;
		$bDisplayMenu = isset($aExtraParams['menu']) ? $aExtraParams['menu'] == true : true;
		// Check if there is a list of aliases to limit the display to...
		$aDisplayAliases = isset($aExtraParams['display_aliases']) ? explode(',',
			$aExtraParams['display_aliases']) : array();
		$sZListName = isset($aExtraParams['zlist']) ? ($aExtraParams['zlist']) : 'list';

		$aExtraFieldsRaw = isset($aExtraParams['extra_fields']) ? explode(',',
			trim($aExtraParams['extra_fields'])) : array();
		$aExtraFields = array();
		$sAttCode = '';
		foreach($aExtraFieldsRaw as $sFieldName)
		{
			// Ignore attributes not of the main queried class
			if (preg_match('/^(.*)\.(.*)$/', $sFieldName, $aMatches))
			{
				$sClassAlias = $aMatches[1];
				$sAttCode = $aMatches[2];
				if (array_key_exists($sClassAlias, $oSet->GetSelectedClasses()))
				{
					$aExtraFields[$sClassAlias][] = $sAttCode;
				}
			}
			else
			{
				$aExtraFields['*'] = $sAttCode;
			}
		}

		$aClasses = $oSet->GetFilter()->GetSelectedClasses();
		$aAuthorizedClasses = array();
		foreach($aClasses as $sAlias => $sClassName)
		{
			if ((UserRights::IsActionAllowed($sClassName, UR_ACTION_READ, $oSet) != UR_ALLOWED_NO) &&
				((count($aDisplayAliases) == 0) || (in_array($sAlias, $aDisplayAliases))))
			{
				$aAuthorizedClasses[$sAlias] = $sClassName;
			}
		}
		foreach($aAuthorizedClasses as $sAlias => $sClassName)
		{
			if (array_key_exists($sAlias, $aExtraFields))
			{
				$aList[$sAlias] = $aExtraFields[$sAlias];
			}
			else
			{
				$aList[$sAlias] = array();
			}
			if ($sZListName !== false)
			{
				$aDefaultList = self::FlattenZList(MetaModel::GetZListItems($sClassName, $sZListName));

				$aList[$sAlias] = array_merge($aDefaultList, $aList[$sAlias]);
			}

			// Filter the list to removed linked set since we are not able to display them here
			foreach($aList[$sAlias] as $index => $sAttCode)
			{
				$oAttDef = MetaModel::GetAttributeDef($sClassName, $sAttCode);
				if ($oAttDef instanceof AttributeLinkedSet)
				{
					// Removed from the display list
					unset($aList[$sAlias][$index]);
				}
			}
		}

		$sSelectMode = 'none';

		$oDataTable = new DataTable($iListId, $oSet, $aAuthorizedClasses);

		$oSettings = DataTableSettings::GetDataModelSettings($aAuthorizedClasses, $bViewLink, $aList);

		$bDisplayLimit = isset($aExtraParams['display_limit']) ? $aExtraParams['display_limit'] : true;
		if ($bDisplayLimit)
		{
			$iDefaultPageSize = appUserPreferences::GetPref('default_page_size',
				MetaModel::GetConfig()->GetMinDisplayLimit());
			$oSettings->iDefaultPageSize = $iDefaultPageSize;
		}

		$oSettings->aSortOrder = MetaModel::GetOrderByDefault($sClassName);

		return $oDataTable->Display($oPage, $oSettings, $bDisplayMenu, $sSelectMode, $bViewLink, $aExtraParams);
	}

	/**
	 * @param \WebPage $oPage
	 * @param \CMDBObjectSet $oSet
	 * @param array $aParams
	 * @param string $sCharset
	 *
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 */
	public static function DisplaySetAsCSV(WebPage $oPage, CMDBObjectSet $oSet, $aParams = array(), $sCharset = 'UTF-8')
	{
		$oPage->add(self::GetSetAsCSV($oSet, $aParams, $sCharset));
	}

	/**
	 * @param \DBObjectSet $oSet
	 * @param array $aParams
	 * @param string $sCharset
	 *
	 * @return string
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \Exception
	 */
	public static function GetSetAsCSV(DBObjectSet $oSet, $aParams = array(), $sCharset = 'UTF-8')
	{
		$sSeparator = isset($aParams['separator']) ? $aParams['separator'] : ','; // default separator is comma
		$sTextQualifier = isset($aParams['text_qualifier']) ? $aParams['text_qualifier'] : '"'; // default text qualifier is double quote
		$aFields = null;
		if (isset($aParams['fields']) && (strlen($aParams['fields']) > 0))
		{
			$aFields = explode(',', $aParams['fields']);
		}

		$bFieldsAdvanced = false;
		if (isset($aParams['fields_advanced']))
		{
			$bFieldsAdvanced = (bool)$aParams['fields_advanced'];
		}

		$bLocalize = true;
		if (isset($aParams['localize_values']))
		{
			$bLocalize = (bool)$aParams['localize_values'];
		}

		$aList = array();

		$aClasses = $oSet->GetFilter()->GetSelectedClasses();
		$aAuthorizedClasses = array();
		foreach($aClasses as $sAlias => $sClassName)
		{
			if (UserRights::IsActionAllowed($sClassName, UR_ACTION_READ, $oSet) != UR_ALLOWED_NO)
			{
				$aAuthorizedClasses[$sAlias] = $sClassName;
			}
		}
		$aHeader = array();
		foreach($aAuthorizedClasses as $sAlias => $sClassName)
		{
			$aList[$sAlias] = array();

			foreach(MetaModel::ListAttributeDefs($sClassName) as $sAttCode => $oAttDef)
			{
				if (is_null($aFields) || (count($aFields) == 0))
				{
					// Standard list of attributes (no link sets)
					if ($oAttDef->IsScalar() && ($oAttDef->IsWritable() || $oAttDef->IsExternalField()))
					{
						$sAttCodeEx = $oAttDef->IsExternalField() ? $oAttDef->GetKeyAttCode().'->'.$oAttDef->GetExtAttCode() : $sAttCode;

						if ($oAttDef->IsExternalKey(EXTKEY_ABSOLUTE))
						{
							if ($bFieldsAdvanced)
							{
								$aList[$sAlias][$sAttCodeEx] = $oAttDef;

								if ($oAttDef->IsExternalKey(EXTKEY_RELATIVE))
								{
									$sRemoteClass = $oAttDef->GetTargetClass();
									foreach(MetaModel::GetReconcKeys($sRemoteClass) as $sRemoteAttCode)
									{
										$aList[$sAlias][$sAttCode.'->'.$sRemoteAttCode] = MetaModel::GetAttributeDef($sRemoteClass,
											$sRemoteAttCode);
									}
								}
							}
						}
						else
						{
							// Any other attribute
							$aList[$sAlias][$sAttCodeEx] = $oAttDef;
						}
					}
				}
				else
				{
					// User defined list of attributes
					if (in_array($sAttCode, $aFields) || in_array($sAlias.'.'.$sAttCode, $aFields))
					{
						$aList[$sAlias][$sAttCode] = $oAttDef;
					}
				}
			}
			if ($bFieldsAdvanced)
			{
				$aHeader[] = 'id';
			}
			foreach($aList[$sAlias] as $sAttCodeEx => $oAttDef)
			{
				$aHeader[] = $bLocalize ? MetaModel::GetLabel($sClassName, $sAttCodeEx,
					isset($aParams['showMandatoryFields'])) : $sAttCodeEx;
			}
		}
		$sHtml = implode($sSeparator, $aHeader)."\n";
		$oSet->Seek(0);
		while ($aObjects = $oSet->FetchAssoc())
		{
			$aRow = array();
			foreach($aAuthorizedClasses as $sAlias => $sClassName)
			{
				$oObj = $aObjects[$sAlias];
				if ($bFieldsAdvanced)
				{
					if (is_null($oObj))
					{
						$aRow[] = '';
					}
					else
					{
						$aRow[] = $oObj->GetKey();
					}
				}
				foreach($aList[$sAlias] as $sAttCodeEx => $oAttDef)
				{
					if (is_null($oObj))
					{
						$aRow[] = '';
					}
					else
					{
						$value = $oObj->Get($sAttCodeEx);
						$sCSVValue = $oAttDef->GetAsCSV($value, $sSeparator, $sTextQualifier, $oObj, $bLocalize);
						$aRow[] = iconv('UTF-8', $sCharset.'//IGNORE//TRANSLIT', $sCSVValue);
					}
				}
			}
			$sHtml .= implode($sSeparator, $aRow)."\n";
		}

		return $sHtml;
	}

	/**
	 * @param \WebPage $oPage
	 * @param \CMDBObjectSet $oSet
	 * @param array $aParams
	 *
	 * @throws \Exception
	 */
	public static function DisplaySetAsHTMLSpreadsheet(WebPage $oPage, CMDBObjectSet $oSet, $aParams = array())
	{
		$oPage->add(self::GetSetAsHTMLSpreadsheet($oSet, $aParams));
	}

	/**
	 * Spreadsheet output: designed for end users doing some reporting
	 * Then the ids are excluded and replaced by the corresponding friendlyname
	 *
	 * @param \DBObjectSet $oSet
	 * @param array $aParams
	 *
	 * @return string
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \Exception
	 */
	public static function GetSetAsHTMLSpreadsheet(DBObjectSet $oSet, $aParams = array())
	{
		$aFields = null;
		if (isset($aParams['fields']) && (strlen($aParams['fields']) > 0))
		{
			$aFields = explode(',', $aParams['fields']);
		}

		$bFieldsAdvanced = false;
		if (isset($aParams['fields_advanced']))
		{
			$bFieldsAdvanced = (bool)$aParams['fields_advanced'];
		}

		$bLocalize = true;
		if (isset($aParams['localize_values']))
		{
			$bLocalize = (bool)$aParams['localize_values'];
		}

		$aList = array();

		$aClasses = $oSet->GetFilter()->GetSelectedClasses();
		$aAuthorizedClasses = array();
		foreach($aClasses as $sAlias => $sClassName)
		{
			if (UserRights::IsActionAllowed($sClassName, UR_ACTION_READ, $oSet) != UR_ALLOWED_NO)
			{
				$aAuthorizedClasses[$sAlias] = $sClassName;
			}
		}
		$aHeader = array();
		foreach($aAuthorizedClasses as $sAlias => $sClassName)
		{
			$aList[$sAlias] = array();

			foreach(MetaModel::ListAttributeDefs($sClassName) as $sAttCode => $oAttDef)
			{
				if (is_null($aFields) || (count($aFields) == 0))
				{
					// Standard list of attributes (no link sets)
					if ($oAttDef->IsScalar() && ($oAttDef->IsWritable() || $oAttDef->IsExternalField()))
					{
						$sAttCodeEx = $oAttDef->IsExternalField() ? $oAttDef->GetKeyAttCode().'->'.$oAttDef->GetExtAttCode() : $sAttCode;

						$aList[$sAlias][$sAttCodeEx] = $oAttDef;

						if ($bFieldsAdvanced && $oAttDef->IsExternalKey(EXTKEY_RELATIVE))
						{
							$sRemoteClass = $oAttDef->GetTargetClass();
							foreach(MetaModel::GetReconcKeys($sRemoteClass) as $sRemoteAttCode)
							{
								$aList[$sAlias][$sAttCode.'->'.$sRemoteAttCode] = MetaModel::GetAttributeDef($sRemoteClass,
									$sRemoteAttCode);
							}
						}
					}
				}
				else
				{
					// User defined list of attributes
					if (in_array($sAttCode, $aFields) || in_array($sAlias.'.'.$sAttCode, $aFields))
					{
						$aList[$sAlias][$sAttCode] = $oAttDef;
					}
				}
			}
			// Replace external key by the corresponding friendly name (if not already in the list)
			foreach($aList[$sAlias] as $sAttCode => $oAttDef)
			{
				if ($oAttDef->IsExternalKey())
				{
					unset($aList[$sAlias][$sAttCode]);
					$sFriendlyNameAttCode = $sAttCode.'_friendlyname';
					if (!array_key_exists($sFriendlyNameAttCode,
							$aList[$sAlias]) && MetaModel::IsValidAttCode($sClassName, $sFriendlyNameAttCode))
					{
						$oFriendlyNameAtt = MetaModel::GetAttributeDef($sClassName, $sFriendlyNameAttCode);
						$aList[$sAlias][$sFriendlyNameAttCode] = $oFriendlyNameAtt;
					}
				}
			}

			foreach($aList[$sAlias] as $sAttCodeEx => $oAttDef)
			{
				$sColLabel = $bLocalize ? MetaModel::GetLabel($sClassName, $sAttCodeEx) : $sAttCodeEx;

				$oFinalAttDef = $oAttDef->GetFinalAttDef();
				if (get_class($oFinalAttDef) == 'AttributeDateTime')
				{
					$aHeader[] = $sColLabel.' ('.Dict::S('UI:SplitDateTime-Date').')';
					$aHeader[] = $sColLabel.' ('.Dict::S('UI:SplitDateTime-Time').')';
				}
				else
				{
					$aHeader[] = $sColLabel;
				}
			}
		}


		$sHtml = "<table border=\"1\">\n";
		$sHtml .= "<tr>\n";
		$sHtml .= "<td>".implode("</td><td>", $aHeader)."</td>\n";
		$sHtml .= "</tr>\n";
		$oSet->Seek(0);
		while ($aObjects = $oSet->FetchAssoc())
		{
			$aRow = array();
			foreach($aAuthorizedClasses as $sAlias => $sClassName)
			{
				$oObj = $aObjects[$sAlias];
				foreach($aList[$sAlias] as $sAttCodeEx => $oAttDef)
				{
					if (is_null($oObj))
					{
						$aRow[] = '<td></td>';
					}
					else
					{
						$oFinalAttDef = $oAttDef->GetFinalAttDef();
						if (get_class($oFinalAttDef) == 'AttributeDateTime')
						{
							$sDate = $oObj->Get($sAttCodeEx);
							if ($sDate === null)
							{
								$aRow[] = '<td></td>';
								$aRow[] = '<td></td>';
							}
							else
							{
								$iDate = AttributeDateTime::GetAsUnixSeconds($sDate);
								$aRow[] = '<td>'.date('Y-m-d',
										$iDate).'</td>'; // Format kept as-is for 100% backward compatibility of the exports
								$aRow[] = '<td>'.date('H:i:s',
										$iDate).'</td>'; // Format kept as-is for 100% backward compatibility of the exports
							}
						}
						else
						{
							if ($oAttDef instanceof AttributeCaseLog)
							{
								$rawValue = $oObj->Get($sAttCodeEx);
								$outputValue = str_replace("\n", "<br/>",
									htmlentities($rawValue->__toString(), ENT_QUOTES, 'UTF-8'));
								// Trick for Excel: treat the content as text even if it begins with an equal sign
								$aRow[] = '<td x:str>'.$outputValue.'</td>';
							}
							else
							{
								$rawValue = $oObj->Get($sAttCodeEx);
								// Due to custom formatting rules, empty friendlynames may be rendered as non-empty strings
								// let's fix this and make sure we render an empty string if the key == 0
								if ($oAttDef instanceof AttributeExternalField && $oAttDef->IsFriendlyName())
								{
									$sKeyAttCode = $oAttDef->GetKeyAttCode();
									if ($oObj->Get($sKeyAttCode) == 0)
									{
										$rawValue = '';
									}
								}
								if ($bLocalize)
								{
									$outputValue = htmlentities($oFinalAttDef->GetEditValue($rawValue), ENT_QUOTES,
										'UTF-8');
								}
								else
								{
									$outputValue = htmlentities($rawValue, ENT_QUOTES, 'UTF-8');
								}
								$aRow[] = '<td>'.$outputValue.'</td>';
							}
						}
					}
				}
			}
			$sHtml .= implode("\n", $aRow);
			$sHtml .= "</tr>\n";
		}
		$sHtml .= "</table>\n";

		return $sHtml;
	}

	/**
	 * @param \WebPage $oPage
	 * @param \CMDBObjectSet $oSet
	 * @param array $aParams
	 *
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 */
	public static function DisplaySetAsXML(WebPage $oPage, CMDBObjectSet $oSet, $aParams = array())
	{
		$bLocalize = true;
		if (isset($aParams['localize_values']))
		{
			$bLocalize = (bool)$aParams['localize_values'];
		}

		$aClasses = $oSet->GetFilter()->GetSelectedClasses();
		$aAuthorizedClasses = array();
		foreach($aClasses as $sAlias => $sClassName)
		{
			if (UserRights::IsActionAllowed($sClassName, UR_ACTION_READ, $oSet) != UR_ALLOWED_NO)
			{
				$aAuthorizedClasses[$sAlias] = $sClassName;
			}
		}
		$aList = array();
		$aList[$sAlias] = MetaModel::GetZListItems($sClassName, 'details');
		$oPage->add("<Set>\n");
		$oSet->Seek(0);
		while ($aObjects = $oSet->FetchAssoc())
		{
			if (count($aAuthorizedClasses) > 1)
			{
				$oPage->add("<Row>\n");
			}
			foreach($aAuthorizedClasses as $sAlias => $sClassName)
			{
				$oObj = $aObjects[$sAlias];
				if (is_null($oObj))
				{
					$oPage->add("<$sClassName alias=\"$sAlias\" id=\"null\">\n");
				}
				else
				{
					$sClassName = get_class($oObj);
					$oPage->add("<$sClassName alias=\"$sAlias\" id=\"".$oObj->GetKey()."\">\n");
				}
				foreach(MetaModel::ListAttributeDefs($sClassName) as $sAttCode => $oAttDef)
				{
					if (is_null($oObj))
					{
						$oPage->add("<$sAttCode>null</$sAttCode>\n");
					}
					else
					{
						if ($oAttDef->IsWritable())
						{
							if (!$oAttDef->IsLinkSet())
							{
								$sValue = $oObj->GetAsXML($sAttCode, $bLocalize);
								$oPage->add("<$sAttCode>$sValue</$sAttCode>\n");
							}
						}
					}
				}
				$oPage->add("</$sClassName>\n");
			}
			if (count($aAuthorizedClasses) > 1)
			{
				$oPage->add("</Row>\n");
			}
		}
		$oPage->add("</Set>\n");
	}

	/**
	 * @param \WebPage $oPage
	 * @param \CMDBObjectSet $oSet
	 * @param array $aExtraParams
	 *
	 * @throws \CoreException
	 * @throws \DictExceptionMissingString
	 */
	public static function DisplaySearchForm(WebPage $oPage, CMDBObjectSet $oSet, $aExtraParams = array())
	{

		$oPage->add(self::GetSearchForm($oPage, $oSet, $aExtraParams));
	}

	/**
	 * @param WebPage $oPage
	 * @param CMDBObjectSet $oSet
	 * @param array $aExtraParams
	 *
	 * @return string
	 * @throws CoreException
	 * @throws DictExceptionMissingString
	 */
	public static function GetSearchForm(WebPage $oPage, CMDBObjectSet $oSet, $aExtraParams = array())
	{
		$oSearchForm = new \Combodo\iTop\Application\Search\SearchForm();

		return $oSearchForm->GetSearchForm($oPage, $oSet, $aExtraParams);
	}

	/**
	 * @param \WebPage $oPage
	 * @param string $sClass
	 * @param string $sAttCode
	 * @param \AttributeDefinition $oAttDef
	 * @param string $value
	 * @param string $sDisplayValue
	 * @param string $iId
	 * @param string $sNameSuffix
	 * @param int $iFlags
	 * @param array $aArgs
	 * @param bool $bPreserveCurrentValue Preserve the current value even if not allowed
	 *
	 * @return string
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \DictExceptionMissingString
	 */
	public static function GetFormElementForField($oPage, $sClass, $sAttCode, $oAttDef, $value = '', $sDisplayValue = '', $iId = '', $sNameSuffix = '',	$iFlags = 0, $aArgs = array(), $bPreserveCurrentValue = true)
	{
		$sFormPrefix = isset($aArgs['formPrefix']) ? $aArgs['formPrefix'] : '';
		$sFieldPrefix = isset($aArgs['prefix']) ? $sFormPrefix.$aArgs['prefix'] : $sFormPrefix;
		if ($sDisplayValue == '')
		{
			$sDisplayValue = $value;
		}

		if (isset($aArgs[$sAttCode]) && empty($value))
		{
			// default value passed by the context (either the app context of the operation)
			$value = $aArgs[$sAttCode];
		}

		if (!empty($iId))
		{
			$iInputId = $iId;
		}
		else
		{
			$iInputId = $oPage->GetUniqueId();
		}

		$sHTMLValue = '';
		if (!$oAttDef->IsExternalField())
		{
			$bMandatory = 'false';
			if ((!$oAttDef->IsNullAllowed()) || ($iFlags & OPT_ATT_MANDATORY))
			{
				$bMandatory = 'true';
			}
			$sValidationSpan = "<span class=\"form_validation\" id=\"v_{$iId}\"></span>";
			$sReloadSpan = "<span class=\"field_status\" id=\"fstatus_{$iId}\"></span>";
			$sHelpText = htmlentities($oAttDef->GetHelpOnEdition(), ENT_QUOTES, 'UTF-8');

			// mandatory field control vars
			$aEventsList = array(); // contains any native event (like change), plus 'validate' for the form submission
			$sNullValue = $oAttDef->GetNullValue(); // used for the ValidateField() call in js/forms-json-utils.js
			$sFieldToValidateId = $iId; // can be different than the displayed field (for example in TagSet)

			switch ($oAttDef->GetEditClass())
			{
				case 'Date':
					$aEventsList[] = 'validate';
					$aEventsList[] = 'keyup';
					$aEventsList[] = 'change';
					$sPlaceholderValue = 'placeholder="'.htmlentities(AttributeDate::GetFormat()->ToPlaceholder(),
							ENT_QUOTES, 'UTF-8').'"';

					$sHTMLValue = "<div class=\"field_input_zone field_input_date\"><input title=\"$sHelpText\" class=\"date-pick\" type=\"text\" $sPlaceholderValue name=\"attr_{$sFieldPrefix}{$sAttCode}{$sNameSuffix}\" value=\"".htmlentities($sDisplayValue,
							ENT_QUOTES, 'UTF-8')."\" id=\"$iId\"/></div>{$sValidationSpan}{$sReloadSpan}";
					break;

				case 'DateTime':
					$aEventsList[] = 'validate';
					$aEventsList[] = 'keyup';
					$aEventsList[] = 'change';

					$sPlaceholderValue = 'placeholder="'.htmlentities(AttributeDateTime::GetFormat()->ToPlaceholder(),
							ENT_QUOTES, 'UTF-8').'"';
					$sHTMLValue = "<div class=\"field_input_zone field_input_datetime\"><input title=\"$sHelpText\" class=\"datetime-pick\" type=\"text\" size=\"19\" $sPlaceholderValue name=\"attr_{$sFieldPrefix}{$sAttCode}{$sNameSuffix}\" value=\"".htmlentities($sDisplayValue,
							ENT_QUOTES, 'UTF-8')."\" id=\"$iId\"/></div>{$sValidationSpan}{$sReloadSpan}";
					break;

				case 'Duration':
					$aEventsList[] = 'validate';
					$aEventsList[] = 'change';
					$oPage->add_ready_script("$('#{$iId}_d').bind('keyup change', function(evt, sFormId) { return UpdateDuration('$iId'); });");
					$oPage->add_ready_script("$('#{$iId}_h').bind('keyup change', function(evt, sFormId) { return UpdateDuration('$iId'); });");
					$oPage->add_ready_script("$('#{$iId}_m').bind('keyup change', function(evt, sFormId) { return UpdateDuration('$iId'); });");
					$oPage->add_ready_script("$('#{$iId}_s').bind('keyup change', function(evt, sFormId) { return UpdateDuration('$iId'); });");
					$aVal = AttributeDuration::SplitDuration($value);
					$sDays = "<input title=\"$sHelpText\" type=\"text\" style=\"text-align:right\" size=\"3\" name=\"attr_{$sFieldPrefix}{$sAttCode}[d]{$sNameSuffix}\" value=\"{$aVal['days']}\" id=\"{$iId}_d\"/>";
					$sHours = "<input title=\"$sHelpText\" type=\"text\" style=\"text-align:right\" size=\"2\" name=\"attr_{$sFieldPrefix}{$sAttCode}[h]{$sNameSuffix}\" value=\"{$aVal['hours']}\" id=\"{$iId}_h\"/>";
					$sMinutes = "<input title=\"$sHelpText\" type=\"text\" style=\"text-align:right\" size=\"2\" name=\"attr_{$sFieldPrefix}{$sAttCode}[m]{$sNameSuffix}\" value=\"{$aVal['minutes']}\" id=\"{$iId}_m\"/>";
					$sSeconds = "<input title=\"$sHelpText\" type=\"text\" style=\"text-align:right\" size=\"2\" name=\"attr_{$sFieldPrefix}{$sAttCode}[s]{$sNameSuffix}\" value=\"{$aVal['seconds']}\" id=\"{$iId}_s\"/>";
					$sHidden = "<input type=\"hidden\" id=\"{$iId}\" value=\"".htmlentities($value, ENT_QUOTES,
							'UTF-8')."\"/>";
					$sHTMLValue = Dict::Format('UI:DurationForm_Days_Hours_Minutes_Seconds', $sDays, $sHours, $sMinutes,
							$sSeconds).$sHidden."&nbsp;".$sValidationSpan.$sReloadSpan;
					$oPage->add_ready_script("$('#{$iId}').bind('update', function(evt, sFormId) { return ToggleDurationField('$iId'); });");
					break;

				case 'Password':
					$aEventsList[] = 'validate';
					$aEventsList[] = 'keyup';
					$aEventsList[] = 'change';
					$sHTMLValue = "<div class=\"field_input_zone field_input_password\"><input title=\"$sHelpText\" type=\"password\" name=\"attr_{$sFieldPrefix}{$sAttCode}{$sNameSuffix}\" value=\"".htmlentities($value,
							ENT_QUOTES, 'UTF-8')."\" id=\"$iId\"/></div>{$sValidationSpan}{$sReloadSpan}";
					break;

				case 'OQLExpression':
				case 'Text':
					$aEventsList[] = 'validate';
					$aEventsList[] = 'keyup';
					$aEventsList[] = 'change';
					$sEditValue = $oAttDef->GetEditValue($value);

					$aStyles = array();
					$sStyle = '';
					$sWidth = $oAttDef->GetWidth();
					if (!empty($sWidth))
					{
						$aStyles[] = 'width:'.$sWidth;
					}
					$sHeight = $oAttDef->GetHeight();
					if (!empty($sHeight))
					{
						$aStyles[] = 'height:'.$sHeight;
					}
					if (count($aStyles) > 0)
					{
						$sStyle = 'style="'.implode('; ', $aStyles).'"';
					}

					if ($oAttDef->GetEditClass() == 'OQLExpression')
					{
						$sTestResId = 'query_res_'.$sFieldPrefix.$sAttCode.$sNameSuffix; //$oPage->GetUniqueId();
						$sBaseUrl = utils::GetAbsoluteUrlAppRoot().'pages/run_query.php?expression=';
						$sInitialUrl = $sBaseUrl.urlencode($sEditValue);
						$sAdditionalStuff = "<a id=\"$sTestResId\" target=\"_blank\" href=\"$sInitialUrl\">".Dict::S('UI:Edit:TestQuery')."</a>";
						$oPage->add_ready_script("$('#$iId').bind('change keyup', function(evt, sFormId) { $('#$sTestResId').attr('href', '$sBaseUrl'+encodeURIComponent($(this).val())); } );");
					}
					else
					{
						$sAdditionalStuff = "";
					}
					// Ok, the text area is drawn here
					$sHTMLValue = "<div class=\"field_input_zone field_input_text\"><div class=\"f_i_text_header\"><span class=\"fullscreen_button\" title=\"".Dict::S('UI:ToggleFullScreen')."\"></span></div><textarea class=\"\" title=\"$sHelpText\" name=\"attr_{$sFieldPrefix}{$sAttCode}{$sNameSuffix}\" rows=\"8\" cols=\"40\" id=\"$iId\" $sStyle>".htmlentities($sEditValue,
							ENT_QUOTES, 'UTF-8')."</textarea>$sAdditionalStuff</div>{$sValidationSpan}{$sReloadSpan}";

					$oPage->add_ready_script(
						<<<EOF
                        $('#$iId').closest('.field_input_text').find('.fullscreen_button').on('click', function(oEvent){
                            var oOriginField = $('#$iId').closest('.field_input_text');
                            var oClonedField = oOriginField.clone();
                            oClonedField.addClass('fullscreen').appendTo('body');
                            oClonedField.find('.fullscreen_button').on('click', function(oEvent){
                                // Copying value to origin field
                                oOriginField.find('textarea').val(oClonedField.find('textarea').val());
                                oClonedField.remove();
                                // Triggering change event
                                oOriginField.find('textarea').triggerHandler('change');
                            });
                        });
EOF
					);
					break;

				case 'CaseLog':
					$aStyles = array();
					$sStyle = '';
					$sWidth = $oAttDef->GetWidth();
					if (!empty($sWidth))
					{
						$aStyles[] = 'width:'.$sWidth;
					}
					$sHeight = $oAttDef->GetHeight();
					if (!empty($sHeight))
					{
						$aStyles[] = 'height:'.$sHeight;
					}
					if (count($aStyles) > 0)
					{
						$sStyle = 'style="'.implode('; ', $aStyles).'"';
					}

					$sHeader = '<div class="caselog_input_header"></div>'; // will be hidden in CSS (via :empty) if it remains empty
					$sEditValue = is_object($value) ? $value->GetModifiedEntry('html') : '';
					$sPreviousLog = is_object($value) ? $value->GetAsHTML($oPage, true /* bEditMode */,
						array('AttributeText', 'RenderWikiHtml')) : '';
					$iEntriesCount = is_object($value) ? count($value->GetIndex()) : 0;
					$sHidden = "<input type=\"hidden\" id=\"{$iId}_count\" value=\"$iEntriesCount\"/>"; // To know how many entries the case log already contains

					$sHTMLValue = "<div class=\"field_input_zone field_input_caselog caselog\" $sStyle>$sHeader<textarea class=\"htmlEditor\" style=\"border:0;width:100%\" title=\"$sHelpText\" name=\"attr_{$sFieldPrefix}{$sAttCode}{$sNameSuffix}\" rows=\"8\" cols=\"40\" id=\"$iId\">".htmlentities($sEditValue,
							ENT_QUOTES,
							'UTF-8')."</textarea>$sPreviousLog</div>{$sValidationSpan}{$sReloadSpan}$sHidden";

					// Note: This should be refactored for all types of attribute (see at the end of this function) but as we are doing this for a maintenance release, we are scheduling it for the next main release in to order to avoid regressions as much as possible.
					$sNullValue = $oAttDef->GetNullValue();
					if (!is_numeric($sNullValue))
					{
						$sNullValue = "'$sNullValue'"; // Add quotes to turn this into a JS string if it's not a number
					}
					$sOriginalValue = ($iFlags & OPT_ATT_MUSTCHANGE) ? json_encode($value->GetModifiedEntry('html')) : 'undefined';

					$oPage->add_ready_script("$('#$iId').bind('keyup change validate', function(evt, sFormId) { return ValidateCaseLogField('$iId', $bMandatory, sFormId, $sNullValue, $sOriginalValue) } );"); // Custom validation function

					// Replace the text area with CKEditor
					// To change the default settings of the editor,
					// a) edit the file /js/ckeditor/config.js
					// b) or override some of the configuration settings, using the second parameter of ckeditor()
					$aConfig = array();
					$sLanguage = strtolower(trim(UserRights::GetUserLanguage()));
					$aConfig['language'] = $sLanguage;
					$aConfig['contentsLanguage'] = $sLanguage;
					$aConfig['extraPlugins'] = 'disabler,codesnippet';
					$aConfig['placeholder'] = Dict::S('UI:CaseLogTypeYourTextHere');
					$sConfigJS = json_encode($aConfig);

					$oPage->add_ready_script("$('#$iId').ckeditor(function() { /* callback code */ }, $sConfigJS);"); // Transform $iId into a CKEdit

					$oPage->add_ready_script(
<<<EOF
$('#$iId').bind('update', function(evt){
	BlockField('cke_$iId', $('#$iId').attr('disabled'));
	//Delayed execution - ckeditor must be properly initialized before setting readonly
	var retryCount = 0;
	var oMe = $('#$iId');
	var delayedSetReadOnly = function () {
		if (oMe.data('ckeditorInstance').editable() == undefined && retryCount++ < 10) {
			setTimeout(delayedSetReadOnly, retryCount * 100); //Wait a while longer each iteration
		}
		else
		{
			oMe.data('ckeditorInstance').setReadOnly(oMe.prop('disabled'));
		}
	};
	setTimeout(delayedSetReadOnly, 50);
});
EOF
					);
				break;

				case 'HTML':
					$sEditValue = $oAttDef->GetEditValue($value);
					$oWidget = new UIHTMLEditorWidget($iId, $oAttDef, $sNameSuffix, $sFieldPrefix, $sHelpText,
						$sValidationSpan.$sReloadSpan, $sEditValue, $bMandatory);
					$sHTMLValue = $oWidget->Display($oPage, $aArgs);
					break;

				case 'LinkedSet':
					if ($oAttDef->IsIndirect())
					{
						$oWidget = new UILinksWidget($sClass, $sAttCode, $iId, $sNameSuffix,
							$oAttDef->DuplicatesAllowed());
					}
					else
					{
						$oWidget = new UILinksWidgetDirect($sClass, $sAttCode, $iId, $sNameSuffix);
					}
					$aEventsList[] = 'validate';
					$aEventsList[] = 'change';
					$oObj = isset($aArgs['this']) ? $aArgs['this'] : null;
					$sHTMLValue = $oWidget->Display($oPage, $value, array(), $sFormPrefix, $oObj);
					break;

				case 'Document':
					$aEventsList[] = 'validate';
					$aEventsList[] = 'change';
					$oDocument = $value; // Value is an ormDocument object
					$sFileName = '';
					if (is_object($oDocument))
					{
						$sFileName = $oDocument->GetFileName();
					}
					$iMaxFileSize = utils::ConvertToBytes(ini_get('upload_max_filesize'));
					$sHTMLValue = "<div class=\"field_input_zone field_input_document\">\n";
					$sHTMLValue .= "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"$iMaxFileSize\" />\n";
					$sHTMLValue .= "<input type=\"hidden\" id=\"do_remove_{$iId}\" name=\"attr_{$sFieldPrefix}{$sAttCode}{$sNameSuffix}[remove]\" value=\"0\"/>\n";

					$sHTMLValue .= "<input name=\"attr_{$sFieldPrefix}{$sAttCode}{$sNameSuffix}[filename]\" type=\"hidden\" id=\"$iId\" \" value=\"".htmlentities($sFileName,
							ENT_QUOTES, 'UTF-8')."\"/>\n";
					$sHTMLValue .= "<span id=\"name_$iInputId\"' >".htmlentities($sFileName, ENT_QUOTES,
							'UTF-8')."</span>&#160;&#160;";
					$sHTMLValue .= "<div title=\"".htmlentities(Dict::S('UI:Button:RemoveDocument'), ENT_QUOTES, 'UTF-8'). "\" id=\"remove_attr_$iId\" class=\"button\" onClick=\"$('#file_$iId').val('');UpdateFileName('$iId', '');\" style=\"display: contents;\">";
					$sHTMLValue .= "<div class=\"ui-icon ui-icon-trash\"></div></div>";
					$sHTMLValue .= "</div>";
					$sHTMLValue .= "<br/>\n";
					$sHTMLValue .= "<input title=\"$sHelpText\" name=\"attr_{$sFieldPrefix}{$sAttCode}{$sNameSuffix}[fcontents]\" type=\"file\" id=\"file_$iId\" onChange=\"UpdateFileName('$iId', this.value)\"/>\n";
					$sHTMLValue .= "</div>\n";
					$sHTMLValue .= "{$sValidationSpan}{$sReloadSpan}\n";
					if ($sFileName == '')
					{
						$oPage->add_ready_script("$('#remove_attr_{$iId}').hide();");
					}
					break;

				case 'Image':
					$aEventsList[] = 'validate';
					$aEventsList[] = 'change';
					$oPage->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/edit_image.js');
					$oDocument = $value; // Value is an ormDocument objectm
					$sDefaultUrl = $oAttDef->Get('default_image');
					if (is_object($oDocument) && !$oDocument->IsEmpty())
					{
						$sUrl = 'data:'.$oDocument->GetMimeType().';base64,'.base64_encode($oDocument->GetData());
					}
					else
					{
						$sUrl = null;
					}

					$sHTMLValue = "<div class=\"field_input_zone field_input_image\"><div id=\"edit_$iInputId\" class=\"edit-image\"></div></div>\n";
					$sHTMLValue .= "{$sValidationSpan}{$sReloadSpan}\n";

					$aEditImage = array(
						'input_name' => 'attr_'.$sFieldPrefix.$sAttCode.$sNameSuffix,
						'max_file_size' => utils::ConvertToBytes(ini_get('upload_max_filesize')),
						'max_width_px' => $oAttDef->Get('display_max_width'),
						'max_height_px' => $oAttDef->Get('display_max_height'),
						'current_image_url' => $sUrl,
						'default_image_url' => $sDefaultUrl,
						'labels' => array(
							'reset_button' => htmlentities(Dict::S('UI:Button:ResetImage'), ENT_QUOTES, 'UTF-8'),
							'remove_button' => htmlentities(Dict::S('UI:Button:RemoveImage'), ENT_QUOTES, 'UTF-8'),
							'upload_button' => $sHelpText,
						),
					);
					$sEditImageOptions = json_encode($aEditImage);
					$oPage->add_ready_script("$('#edit_$iInputId').edit_image($sEditImageOptions);");
					break;

				case 'StopWatch':
					$sHTMLValue = "The edition of a stopwatch is not allowed!!!";
					break;

				case 'List':
					// Not editable for now...
					$sHTMLValue = '';
					break;

				case 'One Way Password':
					$aEventsList[] = 'validate';
					$oWidget = new UIPasswordWidget($sAttCode, $iId, $sNameSuffix);
					$sHTMLValue = $oWidget->Display($oPage, $aArgs);
					// Event list & validation is handled  directly by the widget
					break;

				case 'ExtKey':
					$aEventsList[] = 'validate';
					$aEventsList[] = 'change';

					if ($bPreserveCurrentValue)
					{
						$oAllowedValues = MetaModel::GetAllowedValuesAsObjectSet($sClass, $sAttCode, $aArgs, '', $value);
					}
					else
					{
						$oAllowedValues = MetaModel::GetAllowedValuesAsObjectSet($sClass, $sAttCode, $aArgs);
					}
					$sFieldName = $sFieldPrefix.$sAttCode.$sNameSuffix;
					$aExtKeyParams = $aArgs;
					$aExtKeyParams['iFieldSize'] = $oAttDef->GetMaxSize();
					$aExtKeyParams['iMinChars'] = $oAttDef->GetMinAutoCompleteChars();
					$sHTMLValue = UIExtKeyWidget::DisplayFromAttCode($oPage, $sAttCode, $sClass, $oAttDef->GetLabel(),
						$oAllowedValues, $value, $iId, $bMandatory, $sFieldName, $sFormPrefix, $aExtKeyParams);
					$sHTMLValue .= "<!-- iFlags: $iFlags bMandatory: $bMandatory -->\n";
					break;

				case 'RedundancySetting':
					$sHTMLValue = '<table>';
					$sHTMLValue .= '<tr>';
					$sHTMLValue .= '<td>';
					$sHTMLValue .= '<div id="'.$iId.'">';
					$sHTMLValue .= $oAttDef->GetDisplayForm($value, $oPage, true);
					$sHTMLValue .= '</div>';
					$sHTMLValue .= '</td>';
					$sHTMLValue .= '<td>'.$sValidationSpan.$sReloadSpan.'</td>';
					$sHTMLValue .= '</tr>';
					$sHTMLValue .= '</table>';
					$oPage->add_ready_script("$('#$iId :input').bind('keyup change validate', function(evt, sFormId) { return ValidateRedundancySettings('$iId',sFormId); } );"); // Custom validation function
					break;

				case 'CustomFields':
					$sHTMLValue = '<table>';
					$sHTMLValue .= '<tr>';
					$sHTMLValue .= '<td>';
					$sHTMLValue .= '<div id="'.$iId.'_console_form">';
					$sHTMLValue .= '<div id="'.$iId.'_field_set">';
					$sHTMLValue .= '</div>';
					$sHTMLValue .= '</div>';
					$sHTMLValue .= '</td>';
					$sHTMLValue .= '<td>'.$sReloadSpan.'</td>'; // No validation span for this one: it does handle its own validation!
					$sHTMLValue .= '</tr>';
					$sHTMLValue .= '</table>';
					$sHTMLValue .= "<input name=\"attr_{$sFieldPrefix}{$sAttCode}{$sNameSuffix}\" type=\"hidden\" id=\"$iId\" value=\"\"/>\n";

					$oForm = $value->GetForm($sFormPrefix);
					$oRenderer = new \Combodo\iTop\Renderer\Console\ConsoleFormRenderer($oForm);
					$aRenderRes = $oRenderer->Render();

					$aFieldSetOptions = array(
						'field_identifier_attr' => 'data-field-id',
						// convention: fields are rendered into a div and are identified by this attribute
						'fields_list' => $aRenderRes,
						'fields_impacts' => $oForm->GetFieldsImpacts(),
						'form_path' => $oForm->GetId(),
					);
					$sFieldSetOptions = json_encode($aFieldSetOptions);
					$aFormHandlerOptions = array(
						'wizard_helper_var_name' => 'oWizardHelper'.$sFormPrefix,
						'custom_field_attcode' => $sAttCode,
					);
					$sFormHandlerOptions = json_encode($aFormHandlerOptions);
					$oPage->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/form_handler.js');
					$oPage->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/console_form_handler.js');
					$oPage->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/field_set.js');
					$oPage->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/form_field.js');
					$oPage->add_linked_script(utils::GetAbsoluteUrlAppRoot().'js/subform_field.js');
					$oPage->add_ready_script(
<<<EOF
    $('#{$iId}_field_set').field_set($sFieldSetOptions);
    
    $('#{$iId}_console_form').console_form_handler($sFormHandlerOptions);
    $('#{$iId}_console_form').console_form_handler('alignColumns');
	$('#{$iId}_console_form').console_form_handler('option', 'field_set', $('#{$iId}_field_set'));
    // field_change must be processed to refresh the hidden value at anytime
    $('#{$iId}_console_form').bind('value_change', function() { $('#{$iId}').val(JSON.stringify($('#{$iId}_field_set').triggerHandler('get_current_values'))); });
    // Initialize the hidden value with current state
    // update_value is triggered when preparing the wizard helper object for ajax calls
    $('#{$iId}').bind('update_value', function() { $(this).val(JSON.stringify($('#{$iId}_field_set').triggerHandler('get_current_values'))); });
    // validate is triggered by CheckFields, on all the input fields, once at page init and once before submitting the form
    $('#{$iId}').bind('validate', function(evt, sFormId) {
        $(this).val(JSON.stringify($('#{$iId}_field_set').triggerHandler('get_current_values')));
        return ValidateCustomFields('$iId', sFormId); // Custom validation function
    });
EOF
);
					break;

				case 'Set':
				case 'TagSet':
					$oPage->add_linked_script(utils::GetAbsoluteUrlAppRoot().'/js/selectize.min.js');
					$oPage->add_linked_stylesheet(utils::GetAbsoluteUrlAppRoot().'css/selectize.default.css');
					$oPage->add_linked_script(utils::GetAbsoluteUrlAppRoot().'/js/jquery.itop-set-widget.js');

					$oPage->add_dict_entry('Core:AttributeSet:placeholder');

					/** @var \ormSet $value */
					$sJson = $oAttDef->GetJsonForWidget($value, $aArgs);
					$sEscapedJson = htmlentities($sJson, ENT_QUOTES, 'UTF-8');
					$sSetInputName = "attr_{$sFormPrefix}{$sAttCode}";

					// handle form validation
					$aEventsList[] = 'change';
					$aEventsList[] = 'validate';
					$sNullValue = '';
					$sFieldToValidateId = $sFieldToValidateId.AttributeSet::EDITABLE_INPUT_ID_SUFFIX;

					// generate form HTML output
					$sValidationSpan = "<span class=\"form_validation\" id=\"v_{$sFieldToValidateId}\"></span>";
					$sHTMLValue = '<div class="field_input_zone field_input_set"><input id="'.$iId.'" name="'.$sSetInputName.'" type="hidden" value="'.$sEscapedJson.'"></div>'.$sValidationSpan.$sReloadSpan;
					$sScript = "$('#$iId').set_widget({inputWidgetIdSuffix: '".AttributeSet::EDITABLE_INPUT_ID_SUFFIX."'});";
					$oPage->add_ready_script($sScript);

					break;

				case 'String':
				default:
					$aEventsList[] = 'validate';
					// #@# todo - add context information (depending on dimensions)
					$aAllowedValues = $oAttDef->GetAllowedValues($aArgs);
					$iFieldSize = $oAttDef->GetMaxSize();
					if ($aAllowedValues !== null)
					{
						// Discrete list of values, use a SELECT or RADIO buttons depending on the config
						$sDisplayStyle = $oAttDef->GetDisplayStyle();
						switch ($sDisplayStyle)
						{
							case 'radio':
							case 'radio_horizontal':
							case 'radio_vertical':
								$aEventsList[] = 'change';
								$sHTMLValue = "<div class=\"field_input_zone field_input_{$sDisplayStyle}\">";
								$bVertical = ($sDisplayStyle != 'radio_horizontal');
								$sHTMLValue .= $oPage->GetRadioButtons($aAllowedValues, $value, $iId,
									"attr_{$sFieldPrefix}{$sAttCode}{$sNameSuffix}", $bMandatory, $bVertical, '');
								$sHTMLValue .= "</div>{$sValidationSpan}{$sReloadSpan}\n";
								break;

							case 'select':
							default:
								$aEventsList[] = 'change';
								$sHTMLValue = "<div class=\"field_input_zone field_input_string\"><select title=\"$sHelpText\" name=\"attr_{$sFieldPrefix}{$sAttCode}{$sNameSuffix}\" id=\"$iId\">\n";
								$sHTMLValue .= "<option value=\"\">".Dict::S('UI:SelectOne')."</option>\n";
								foreach($aAllowedValues as $key => $display_value)
								{
									if ((count($aAllowedValues) == 1) && ($bMandatory == 'true'))
									{
										// When there is only once choice, select it by default
										if($value != $key)
										{
											$oPage->add_ready_script(
												<<<EOF
$('#$iId').attr('data-validate','dependencies');
EOF
											);
										}
										$sSelected = ' selected';
									}
									else
									{
										$sSelected = ($value == $key) ? ' selected' : '';
									}
									$sHTMLValue .= "<option value=\"$key\"$sSelected>$display_value</option>\n";
								}
								$sHTMLValue .= "</select></div>{$sValidationSpan}{$sReloadSpan}\n";
								break;
						}
					}
					else
					{
						$sHTMLValue = "<div class=\"field_input_zone field_input_string\"><input title=\"$sHelpText\" type=\"text\" maxlength=\"$iFieldSize\" name=\"attr_{$sFieldPrefix}{$sAttCode}{$sNameSuffix}\" value=\"".htmlentities($sDisplayValue,
								ENT_QUOTES, 'UTF-8')."\" id=\"$iId\"/></div>{$sValidationSpan}{$sReloadSpan}";
						$aEventsList[] = 'keyup';
						$aEventsList[] = 'change';

						// Adding tooltip so we can read the whole value when its very long (eg. URL)
						if (!empty($sDisplayValue))
						{
							$oPage->add_ready_script(
								<<<EOF
								var sEscapedVal = $('<div/>').text($('#{$iId}').val()).html();
								$('#{$iId}').qtip( { content: sEscapedVal, show: 'mouseover', hide: 'mouseout', style: { name: 'dark', tip: 'bottomLeft' }, position: { corner: { target: 'topLeft', tooltip: 'bottomLeft' }, adjust: { y: -15}} } );
								
								$('#{$iId}').bind('keyup', function(evt, sFormId){ 
									var oQTipAPI = $(this).qtip('api');
									
									if($(this).val() === '')
									{
										oQTipAPI.hide();
										oQTipAPI.disable(true); 
									}
									else
									{
										oQTipAPI.disable(false); 
									}
									var sEscapedVal = $('<div/>').text($(this).val()).html();                  
									oQTipAPI.updateContent(sEscapedVal);
								});
EOF
							);
						}
					}
					break;
			}
			$sPattern = addslashes($oAttDef->GetValidationPattern()); //'^([0-9]+)$';			
			if (!empty($aEventsList))
			{
				if (!is_numeric($sNullValue))
				{
					$sNullValue = "'$sNullValue'"; // Add quotes to turn this into a JS string if it's not a number
				}
				$sOriginalValue = ($iFlags & OPT_ATT_MUSTCHANGE) ? json_encode($value) : 'undefined';
				$oPage->add_ready_script("$('#$sFieldToValidateId').bind('".implode(' ',
						$aEventsList)."', function(evt, sFormId) { return ValidateField('$sFieldToValidateId', '$sPattern', $bMandatory, sFormId, $sNullValue, $sOriginalValue) } );\n"); // Bind to a custom event: validate
			}
			$aDependencies = MetaModel::GetDependentAttributes($sClass,
				$sAttCode); // List of attributes that depend on the current one
			if (count($aDependencies) > 0)
			{
				// Unbind first to avoid duplicate event handlers in case of reload of the whole (or part of the) form
				$oPage->add_ready_script("$('#$iId').unbind('change.dependencies').bind('change.dependencies', function(evt, sFormId) { return oWizardHelper{$sFormPrefix}.UpdateDependentFields(['".implode("','",
						$aDependencies)."']) } );\n"); // Bind to a custom event: validate
			}
		}
		$oPage->add_dict_entry('UI:ValueMustBeSet');
		$oPage->add_dict_entry('UI:ValueMustBeChanged');
		$oPage->add_dict_entry('UI:ValueInvalidFormat');

		// Note: In 2.8, remove the data-attcode attribute (either because it's has been moved to .field_container in 2.7 or even better because the admin. console has been reworked)
		return "<div id=\"field_{$iId}\" class=\"field_value_container\"><div class=\"attribute-edit\" data-attcode=\"$sAttCode\">{$sHTMLValue}</div></div>";
	}

	/**
	 * @param \WebPage $oPage
	 * @param array $aExtraParams
	 *
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \DictExceptionMissingString
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 * @throws \Exception
	 */
	public function DisplayModifyForm(WebPage $oPage, $aExtraParams = array())
	{
		$sOwnershipToken = null;
		$iKey = $this->GetKey();
		$sClass = get_class($this);
		$sMode = ($iKey > 0) ? static::ENUM_OBJECT_MODE_EDIT : static::ENUM_OBJECT_MODE_CREATE;

		if ($sMode === static::ENUM_OBJECT_MODE_EDIT)
		{
			// The concurrent access lock makes sense only for already existing objects
			$LockEnabled = MetaModel::GetConfig()->Get('concurrent_lock_enabled');
			if ($LockEnabled)
			{
				$sOwnershipToken = utils::ReadPostedParam('ownership_token', null, 'raw_data');
				if ($sOwnershipToken !== null)
				{
					// We're probably inside something like "apply_modify" where the validation failed and we must prompt the user again to edit the object
					// let's extend our lock
				}
				else
				{
					$aLockInfo = iTopOwnershipLock::AcquireLock($sClass, $iKey);
					if ($aLockInfo['success'])
					{
						$sOwnershipToken = $aLockInfo['token'];
					}
					else
					{
						// If the object is locked by the current user, it's worth trying again, since
						// the lock may be released by 'onunload' which is called AFTER loading the current page.
						//$bTryAgain = $oOwner->GetKey() == UserRights::GetUserId();
						self::ReloadAndDisplay($oPage, $this, array('operation' => 'modify'));

						return;
					}
				}
			}
		}

		if (isset($aExtraParams['wizard_container']) && $aExtraParams['wizard_container'])
		{
			$sClassLabel = MetaModel::GetName($sClass);
			$sHeaderTitle = Dict::Format('UI:ModificationTitle_Class_Object', $sClassLabel,
				$this->GetName());

			$oPage->set_title(Dict::Format('UI:ModificationPageTitle_Object_Class', $this->GetRawName(),
				$sClassLabel)); // Set title will take care of the encoding

			$oPage->add(<<<HTML
<!-- Beginning of object-details -->
<div class="object-details" data-object-class="$sClass" data-object-id="$iKey" data-object-mode="$sMode">
	<div class="page_header">
		<h1>{$this->GetIcon()} $sHeaderTitle</h1>
	</div>
	<!-- Beginning of wizContainer -->
	<div class="wizContainer">
HTML
			);
		}
		self::$iGlobalFormId++;
		$this->aFieldsMap = array();
		$sPrefix = '';
		if (isset($aExtraParams['formPrefix']))
		{
			$sPrefix = $aExtraParams['formPrefix'];
		}

		$this->m_iFormId = $sPrefix.self::$iGlobalFormId;
		$oAppContext = new ApplicationContext();
		if (!isset($aExtraParams['action']))
		{
			$sFormAction = utils::GetAbsoluteUrlAppRoot().'pages/'.$this->GetUIPage(); // No parameter in the URL, the only parameter will be the ones passed through the form
		}
		else
		{
			$sFormAction = $aExtraParams['action'];
		}
		// Custom label for the apply button ?
		if (isset($aExtraParams['custom_button']))
		{
			$sApplyButton = $aExtraParams['custom_button'];
		}
		else
		{
			if ($sMode === static::ENUM_OBJECT_MODE_EDIT)
			{
				$sApplyButton = Dict::S('UI:Button:Apply');
			}
			else
			{
				$sApplyButton = Dict::S('UI:Button:Create');
			}
		}
		// Custom operation for the form ?
		if (isset($aExtraParams['custom_operation']))
		{
			$sOperation = $aExtraParams['custom_operation'];
		}
		else
		{
			if ($sMode === static::ENUM_OBJECT_MODE_EDIT)
			{
				$sOperation = 'apply_modify';
			}
			else
			{
				$sOperation = 'apply_new';
			}
		}
		if ($sMode === static::ENUM_OBJECT_MODE_EDIT)
		{
			// The object already exists in the database, it's a modification
			$sButtons = "<input id=\"{$sPrefix}_id\" type=\"hidden\" name=\"id\" value=\"$iKey\">\n";
			$sButtons .= "<input type=\"hidden\" name=\"operation\" value=\"{$sOperation}\">\n";
			$sButtons .= "<button type=\"button\" class=\"action cancel\"><span>".Dict::S('UI:Button:Cancel')."</span></button>&nbsp;&nbsp;&nbsp;&nbsp;\n";
			$sButtons .= "<button type=\"submit\" class=\"action\"><span>{$sApplyButton}</span></button>\n";
		}
		else
		{
			// The object does not exist in the database it's a creation
			$sButtons = "<input type=\"hidden\" name=\"operation\" value=\"$sOperation\">\n";
			$sButtons .= "<button type=\"button\" class=\"action cancel\">".Dict::S('UI:Button:Cancel')."</button>&nbsp;&nbsp;&nbsp;&nbsp;\n";
			$sButtons .= "<button type=\"submit\" class=\"action\"><span>{$sApplyButton}</span></button>\n";
		}

		$aTransitions = $this->EnumTransitions();
		if (!isset($aExtraParams['custom_operation']) && count($aTransitions))
		{
			// transitions are displayed only for the standard new/modify actions, not for modify_all or any other case...
			$oSetToCheckRights = DBObjectSet::FromObject($this);
			$aStimuli = Metamodel::EnumStimuli($sClass);
			foreach($aTransitions as $sStimulusCode => $aTransitionDef)
			{
				$iActionAllowed = (get_class($aStimuli[$sStimulusCode]) == 'StimulusUserAction') ? UserRights::IsStimulusAllowed($sClass,
					$sStimulusCode, $oSetToCheckRights) : UR_ALLOWED_NO;
				switch ($iActionAllowed)
				{
					case UR_ALLOWED_YES:
						$sButtons .= "<button type=\"submit\" name=\"next_action\" value=\"{$sStimulusCode}\" class=\"action\"><span>".$aStimuli[$sStimulusCode]->GetLabel()."</span></button>\n";
						break;

					default:
						// Do nothing
				}
			}
		}

		$sButtonsPosition = MetaModel::GetConfig()->Get('buttons_position');
		$iTransactionId = isset($aExtraParams['transaction_id']) ? $aExtraParams['transaction_id'] : utils::GetNewTransactionId();
		$oPage->SetTransactionId($iTransactionId);
		$oPage->add("<form action=\"$sFormAction\" id=\"form_{$this->m_iFormId}\" enctype=\"multipart/form-data\" method=\"post\" onSubmit=\"return OnSubmit('form_{$this->m_iFormId}');\">\n");
		$sStatesSelection = '';
		if (!isset($aExtraParams['custom_operation']) && $this->IsNew())
		{
			$aInitialStates = MetaModel::EnumInitialStates($sClass);
			//$aInitialStates = array('new' => 'foo', 'closed' => 'bar');
			if (count($aInitialStates) > 1)
			{
				$sStatesSelection = Dict::Format('UI:Create_Class_InState',
						MetaModel::GetName($sClass)).'<select name="obj_state" class="state_select_'.$this->m_iFormId.'">';
				foreach($aInitialStates as $sStateCode => $sStateData)
				{
					$sSelected = '';
					if ($sStateCode == $this->GetState())
					{
						$sSelected = ' selected';
					}
					$sStatesSelection .= '<option value="'.$sStateCode.'" '.$sSelected.'>'.MetaModel::GetStateLabel($sClass,
							$sStateCode).'</option>';
				}
				$sStatesSelection .= '</select>';
				$sStatesSelection .= '<input type="hidden" id="obj_state_orig" name="obj_state_orig" value="'.$this->GetState().'"/>';
				$oPage->add_ready_script(<<<JAVASCRIPT
$('.state_select_{$this->m_iFormId}').change( function() {
	if ($('#obj_state_orig').val() != $(this).val()) {
		$('.state_select_{$this->m_iFormId}').val($(this).val());
		$('#form_{$this->m_iFormId}').data('force_submit', true);
		$('#form_{$this->m_iFormId}').submit();
	}
});
JAVASCRIPT
			);
			}
		}

		$sConfirmationMessage = addslashes(Dict::S('UI:NavigateAwayConfirmationMessage'));
		$sJSToken = json_encode($sOwnershipToken);
		$oPage->add_ready_script(
			<<<EOF
	$(window).on('unload',function() { return OnUnload('$iTransactionId', '$sClass', $iKey, $sJSToken) } );
	window.onbeforeunload = function() {
		if (!window.bInSubmit && !window.bInCancel)
		{
			return '$sConfirmationMessage';	
		}
		// return nothing ! safer for IE
	};
EOF
		);

		if ($sButtonsPosition != 'bottom')
		{
			// top or both, display the buttons here
			$oPage->p($sStatesSelection);
			$oPage->add($sButtons);
		}

		$oPage->AddTabContainer(OBJECT_PROPERTIES_TAB, $sPrefix);
		$oPage->SetCurrentTabContainer(OBJECT_PROPERTIES_TAB);
		$oPage->SetCurrentTab('UI:PropertiesTab');

		$aFieldsMap = $this->DisplayBareProperties($oPage, true, $sPrefix, $aExtraParams);
		if (!is_array($aFieldsMap))
		{
			$aFieldsMap = array();
		}
		if ($sMode === static::ENUM_OBJECT_MODE_EDIT)
		{
			$aFieldsMap['id'] = $sPrefix.'_id';
		}
		// Now display the relations, one tab per relation
		if (!isset($aExtraParams['noRelations']))
		{
			$this->DisplayBareRelations($oPage, true); // Edit mode, will fill $this->aFieldsMap
			$aFieldsMap = array_merge($aFieldsMap, $this->aFieldsMap);
		}

		$oPage->SetCurrentTab('');
		$oPage->add("<input type=\"hidden\" name=\"class\" value=\"$sClass\">\n");
		$oPage->add("<input type=\"hidden\" name=\"transaction_id\" value=\"$iTransactionId\">\n");
		foreach($aExtraParams as $sName => $value)
		{
			if (is_scalar($value))
			{
				$oPage->add("<input type=\"hidden\" name=\"$sName\" value=\"$value\">\n");
			}
		}
		if ($sOwnershipToken !== null)
		{
			$oPage->add("<input type=\"hidden\" name=\"ownership_token\" value=\"".htmlentities($sOwnershipToken,
					ENT_QUOTES, 'UTF-8')."\">\n");
		}
		$oPage->add($oAppContext->GetForForm());
		if ($sButtonsPosition != 'top')
		{
			// bottom or both: display the buttons here
			$oPage->p($sStatesSelection);
			$oPage->add($sButtons);
		}

		// Hook the cancel button via jQuery so that it can be unhooked easily as well if needed
		$sDefaultUrl = utils::GetAbsoluteUrlAppRoot().'pages/UI.php?operation=cancel&'.$oAppContext->GetForLink();
		$oPage->add_ready_script("$('#form_{$this->m_iFormId} button.cancel').click( function() { BackToDetails('$sClass', $iKey, '$sDefaultUrl', $sJSToken)} );");
		$oPage->add("</form>\n");

		if (isset($aExtraParams['wizard_container']) && $aExtraParams['wizard_container'])
		{
			// Close wizContainer and object-details
			$oPage->add(<<<HTML
	</div><!-- End of wizContainer -->
</div><!-- End of object-details -->
HTML
			);
		}

		$iFieldsCount = count($aFieldsMap);
		$sJsonFieldsMap = json_encode($aFieldsMap);
		$sState = $this->GetState();
		$sSessionStorageKey = $sClass.'_'.$iKey;
		$sTempId = utils::GetUploadTempId($iTransactionId);
		$oPage->add_ready_script(InlineImage::EnableCKEditorImageUpload($this, $sTempId));

		$oPage->add_script(
			<<<EOF
		sessionStorage.removeItem('$sSessionStorageKey');
		
		// Create the object once at the beginning of the page...
		var oWizardHelper$sPrefix = new WizardHelper('$sClass', '$sPrefix', '$sState');
		oWizardHelper$sPrefix.SetFieldsMap($sJsonFieldsMap);
		oWizardHelper$sPrefix.SetFieldsCount($iFieldsCount);
EOF
		);
		$oPage->add_ready_script(
			<<<EOF
		oWizardHelper$sPrefix.UpdateWizard();
		// Starts the validation when the page is ready
		CheckFields('form_{$this->m_iFormId}', false);

EOF
		);
		if ($sOwnershipToken !== null)
		{
			$this->GetOwnershipJSHandler($oPage, $sOwnershipToken);
		}
		else
		{
			// Probably a new object (or no concurrent lock), let's add a watchdog so that the session is kept open while editing
			$iInterval = MetaModel::GetConfig()->Get('concurrent_lock_expiration_delay') * 1000 / 2;
			if ($iInterval > 0)
			{
				$iInterval = max(MIN_WATCHDOG_INTERVAL * 1000,
					$iInterval); // Minimum interval for the watchdog is MIN_WATCHDOG_INTERVAL
				$oPage->add_ready_script(
					<<<EOF
				window.setInterval(function() {
					$.post(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php', {operation: 'watchdog'});
				}, $iInterval);
EOF
				);
			}
		}
	}

	/**
	 * @param \WebPage $oPage
	 * @param string $sClass
	 * @param null $oObjectToClone
	 * @param array $aArgs
	 * @param array $aExtraParams
	 *
	 * @return mixed
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 */
	public static function DisplayCreationForm(WebPage $oPage, $sClass, $oObjectToClone = null, $aArgs = array(), $aExtraParams = array())
	{
		$sClass = ($oObjectToClone == null) ? $sClass : get_class($oObjectToClone);

		if ($oObjectToClone == null)
		{
			$oObj = DBObject::MakeDefaultInstance($sClass);
		}
		else
		{
			$oObj = clone $oObjectToClone;
		}

		// Pre-fill the object with default values, when there is only on possible choice
		// AND the field is mandatory (otherwise there is always the possiblity to let it empty)
		$aArgs['this'] = $oObj;
		$aDetailsList = self::FLattenZList(MetaModel::GetZListItems($sClass, 'details'));
		// Order the fields based on their dependencies
		$aDeps = array();
		foreach($aDetailsList as $sAttCode)
		{
			$aDeps[$sAttCode] = MetaModel::GetPrerequisiteAttributes($sClass, $sAttCode);
		}
		$aList = self::OrderDependentFields($aDeps);

		// Now fill-in the fields with default/supplied values
		foreach($aList as $sAttCode)
		{
			if (isset($aArgs['default'][$sAttCode]))
			{
				$oObj->Set($sAttCode, $aArgs['default'][$sAttCode]);
			}
			else
			{
				$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);

				// If the field is mandatory, set it to the only possible value
				$iFlags = $oObj->GetInitialStateAttributeFlags($sAttCode);
				if ((!$oAttDef->IsNullAllowed()) || ($iFlags & OPT_ATT_MANDATORY))
				{
					if ($oAttDef->IsExternalKey())
					{
						/** @var DBObjectSet $oAllowedValues */
						$oAllowedValues = MetaModel::GetAllowedValuesAsObjectSet($sClass, $sAttCode, $aArgs);
						if ($oAllowedValues->CountWithLimit(2) == 1)
						{
							$oRemoteObj = $oAllowedValues->Fetch();
							$oObj->Set($sAttCode, $oRemoteObj->GetKey());
						}
					}
					else
					{
						$aAllowedValues = MetaModel::GetAllowedValues_att($sClass, $sAttCode, $aArgs);
						if (is_array($aAllowedValues) && (count($aAllowedValues) == 1))
						{
							$aValues = array_keys($aAllowedValues);
							$oObj->Set($sAttCode, $aValues[0]);
						}
					}
				}
			}
		}

		return $oObj->DisplayModifyForm($oPage, $aExtraParams);
	}

	/**
	 * @param \WebPage $oPage
	 * @param string $sStimulus
	 * @param null $aPrefillFormParam
	 *
	 * @throws \ApplicationException
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \DictExceptionMissingString
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 */
	public function DisplayStimulusForm(WebPage $oPage, $sStimulus, $aPrefillFormParam = null, $bDisplayBareProperties = true)
	{
		$sClass = get_class($this);
		$iKey = $this->GetKey();
		$sMode = static::ENUM_OBJECT_MODE_STIMULUS;

		$aTransitions = $this->EnumTransitions();
		$aStimuli = MetaModel::EnumStimuli($sClass);
		if (!isset($aTransitions[$sStimulus]))
		{
			// Invalid stimulus
			throw new ApplicationException(Dict::Format('UI:Error:Invalid_Stimulus_On_Object_In_State', $sStimulus,
				$this->GetName(), $this->GetStateLabel()));
		}

		// Check for concurrent access lock
		$LockEnabled = MetaModel::GetConfig()->Get('concurrent_lock_enabled');
		$sOwnershipToken = null;
		if ($LockEnabled)
		{
			$aLockInfo = iTopOwnershipLock::AcquireLock($sClass, $iKey);
			if ($aLockInfo['success'])
			{
				$sOwnershipToken = $aLockInfo['token'];
			}
			else
			{
				// If the object is locked by the current user, it's worth trying again, since
				// the lock may be released by 'onunload' which is called AFTER loading the current page.
				//$bTryAgain = $oOwner->GetKey() == UserRights::GetUserId();
				self::ReloadAndDisplay($oPage, $this, array('operation' => 'stimulus', 'stimulus' => $sStimulus));

				return;
			}
		}
		$sActionLabel = $aStimuli[$sStimulus]->GetLabel();
		$sActionDetails = $aStimuli[$sStimulus]->GetDescription();

		// Get info on current state
		$sCurrentState = $this->GetState();
		$sTargetState = $aTransitions[$sStimulus]['target_state'];

		$oPage->set_title($sActionLabel);
		$oPage->add(<<<HTML
<!-- Beginning of object-details -->
<div class="object-details" data-object-class="$sClass" data-object-id="$iKey" data-object-mode="$sMode" data-object-current-state="$sCurrentState" data-object-target-state="$sTargetState">
	<div class="page_header">
		<h1>$sActionLabel - <span class="hilite">{$this->GetName()}</span></h1>
	</div>
	<h1>$sActionDetails</h1>
HTML
		);

		$aExpectedAttributes = $this->GetTransitionAttributes($sStimulus /*, current state*/);
		if ($aPrefillFormParam != null)
		{
			$aPrefillFormParam['expected_attributes'] = $aExpectedAttributes;
			$this->PrefillForm('state_change', $aPrefillFormParam);
			$aExpectedAttributes = $aPrefillFormParam['expected_attributes'];
		}
		$sButtonsPosition = MetaModel::GetConfig()->Get('buttons_position');
		if ($sButtonsPosition == 'bottom' && $bDisplayBareProperties)
		{
			// bottom: Displays the ticket details BEFORE the actions
			$oPage->add('<div class="ui-widget-content">');
			$this->DisplayBareProperties($oPage);
			$oPage->add('</div>');
		}
		$oPage->add("<div class=\"wizContainer\">\n");
		$oPage->add("<form id=\"apply_stimulus\" method=\"post\" enctype=\"multipart/form-data\" onSubmit=\"return OnSubmit('apply_stimulus');\">\n");
		$aDetails = array();
		$iFieldIndex = 0;
		$aFieldsMap = array();

		// The list of candidate fields is made of the ordered list of "details" attributes + other attributes
		$aAttributes = array();
		foreach($this->FlattenZList(MetaModel::GetZListItems($sClass, 'details')) as $sAttCode)
		{
			$aAttributes[$sAttCode] = true;
		}
		foreach(MetaModel::GetAttributesList($sClass) as $sAttCode)
		{
			if (!array_key_exists($sAttCode, $aAttributes))
			{
				$aAttributes[$sAttCode] = true;
			}
		}
		// Order the fields based on their dependencies, set the fields for which there is only one possible value
		// and perform this in the order of dependencies to avoid dead-ends
		$aDeps = array();
		foreach($aAttributes as $sAttCode => $trash)
		{
			$aDeps[$sAttCode] = MetaModel::GetPrerequisiteAttributes($sClass, $sAttCode);
		}
		$aList = $this->OrderDependentFields($aDeps);

		foreach($aList as $sAttCode)
		{
			// Consider only the "expected" fields for the target state
			if (array_key_exists($sAttCode, $aExpectedAttributes))
			{
				$iExpectCode = $aExpectedAttributes[$sAttCode];
				// Prompt for an attribute if
				// - the attribute must be changed or must be displayed to the user for confirmation
				// - or the field is mandatory and currently empty
				if (($iExpectCode & (OPT_ATT_MUSTCHANGE | OPT_ATT_MUSTPROMPT)) ||
					(($iExpectCode & OPT_ATT_MANDATORY) && ($this->Get($sAttCode) == '')))
				{
					$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
					$aArgs = array('this' => $this);
					// If the field is mandatory, set it to the only possible value
					if ((!$oAttDef->IsNullAllowed()) || ($iExpectCode & OPT_ATT_MANDATORY))
					{
						if ($oAttDef->IsExternalKey())
						{
							/** @var DBObjectSet $oAllowedValues */
							$oAllowedValues = MetaModel::GetAllowedValuesAsObjectSet($sClass, $sAttCode, $aArgs, '',
								$this->Get($sAttCode));
							if ($oAllowedValues->CountWithLimit(2) == 1)
							{
								$oRemoteObj = $oAllowedValues->Fetch();
								$this->Set($sAttCode, $oRemoteObj->GetKey());
							}
						}
						else
						{
							$aAllowedValues = MetaModel::GetAllowedValues_att($sClass, $sAttCode, $aArgs);
							if (is_array($aAllowedValues) && count($aAllowedValues) == 1)
							{
								$aValues = array_keys($aAllowedValues);
								$this->Set($sAttCode, $aValues[0]);
							}
						}
					}
					$sHTMLValue = cmdbAbstractObject::GetFormElementForField($oPage, $sClass, $sAttCode, $oAttDef,
						$this->Get($sAttCode), $this->GetEditValue($sAttCode), 'att_'.$iFieldIndex, '', $iExpectCode,
						$aArgs);
					$aAttrib = array(
						'label' => '<span>'.$oAttDef->GetLabel().'</span>',
						'value' => "<span id=\"field_att_$iFieldIndex\">$sHTMLValue</span>",
					);

					//add attrib for data-attribute
					// Prepare metadata attributes
					$sAttCode = $oAttDef->GetCode();
					$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
					$sAttDefClass = get_class($oAttDef);
					$sAttLabel = MetaModel::GetLabel($sClass, $sAttCode);

					$aAttrib['attcode'] = $sAttCode;
					$aAttrib['atttype'] = $sAttDefClass;
					$aAttrib['attlabel'] = $sAttLabel;
					// - Attribute flags
					$aAttrib['attflags'] = $this->GetFormAttributeFlags($sAttCode) ;
					// - How the field should be rendered
					$aAttrib['layout'] = (in_array($oAttDef->GetEditClass(), static::GetAttEditClassesToRenderAsLargeField())) ? 'large' : 'small';
					// - For simple fields, we get the raw (stored) value as well
					$bExcludeRawValue = false;
					foreach (static::GetAttDefClassesToExcludeFromMarkupMetadataRawValue() as $sAttDefClassToExclude)
					{
						if (is_a($sAttDefClass, $sAttDefClassToExclude, true))
						{
							$bExcludeRawValue = true;
							break;
						}
					}
					$aAttrib['value_raw'] = ($bExcludeRawValue === false) ? $this->Get($sAttCode) : '';

					$aDetails[] = $aAttrib;
					$aFieldsMap[$sAttCode] = 'att_'.$iFieldIndex;
					$iFieldIndex++;
				}
			}
		}

		$oPage->add('<table><tr><td>');
		$oPage->details($aDetails);
		$oPage->add('</td></tr></table>');
		$oPage->add("<input type=\"hidden\" name=\"id\" value=\"".$this->GetKey()."\" id=\"id\">\n");
		$aFieldsMap['id'] = 'id';
		$oPage->add("<input type=\"hidden\" name=\"class\" value=\"$sClass\">\n");
		$oPage->add("<input type=\"hidden\" name=\"operation\" value=\"apply_stimulus\">\n");
		$oPage->add("<input type=\"hidden\" name=\"stimulus\" value=\"$sStimulus\">\n");
		$iTransactionId = utils::GetNewTransactionId();
		$oPage->add("<input type=\"hidden\" name=\"transaction_id\" value=\"".$iTransactionId."\">\n");
		if ($sOwnershipToken !== null)
		{
			$oPage->add("<input type=\"hidden\" name=\"ownership_token\" value=\"".htmlentities($sOwnershipToken,
					ENT_QUOTES, 'UTF-8')."\">\n");
		}
		$oAppContext = new ApplicationContext();
		$oPage->add($oAppContext->GetForForm());
		$oPage->add("<button type=\"button\" class=\"action cancel\" onClick=\"BackToDetails('$sClass', ".$this->GetKey().", '', '$sOwnershipToken')\"><span>".Dict::S('UI:Button:Cancel')."</span></button>&nbsp;&nbsp;&nbsp;&nbsp;\n");
		$oPage->add("<button type=\"submit\" class=\"action\"><span>$sActionLabel</span></button>\n");
		$oPage->add("</form>\n");
		$oPage->add(<<<HTML
	</div>
</div><!-- End of object-details -->
HTML
		);
		if ($sButtonsPosition != 'top' && $bDisplayBareProperties)
		{
			// bottom or both: Displays the ticket details AFTER the actions
			$oPage->add('<div class="ui-widget-content">');
			$this->DisplayBareProperties($oPage);
			$oPage->add('</div>');
		}

		$iFieldsCount = count($aFieldsMap);
		$sJsonFieldsMap = json_encode($aFieldsMap);

		$oPage->add_script(
			<<<EOF
		// Initializes the object once at the beginning of the page...
		var oWizardHelper = new WizardHelper('$sClass', '', '$sTargetState', '{$this->GetState()}', '$sStimulus');
		oWizardHelper.SetFieldsMap($sJsonFieldsMap);
		oWizardHelper.SetFieldsCount($iFieldsCount);
EOF
		);
		$sJSToken = json_encode($sOwnershipToken);
		$oPage->add_ready_script(
			<<<EOF
		// Starts the validation when the page is ready
		CheckFields('apply_stimulus', false);
		$(window).on('unload', function() { return OnUnload('$iTransactionId', '$sClass', $iKey, $sJSToken) } );
EOF
		);

		if ($sOwnershipToken !== null)
		{
			$this->GetOwnershipJSHandler($oPage, $sOwnershipToken);
		}

		// Note: This part (inline images activation) is duplicated in self::DisplayModifyForm and several other places. Maybe it should be refactored so it automatically activates when an HTML field is present, or be an option of the attribute. See bug n°1240.
		$sTempId = utils::GetUploadTempId($iTransactionId);
		$oPage->add_ready_script(InlineImage::EnableCKEditorImageUpload($this, $sTempId));
	}

	public static function ProcessZlist($aList, $aDetails, $sCurrentTab, $sCurrentCol, $sCurrentSet)
	{
		$index = 0;
		foreach($aList as $sKey => $value)
		{
			if (is_array($value))
			{
				if (preg_match('/^(.*):(.*)$/U', $sKey, $aMatches))
				{
					$sCode = $aMatches[1];
					$sName = $aMatches[2];
					switch ($sCode)
					{
						case 'tab':
							if (!isset($aDetails[$sName]))
							{
								$aDetails[$sName] = array('col1' => array());
							}
							$aDetails = self::ProcessZlist($value, $aDetails, $sName, 'col1', '');
							break;

						case 'fieldset':
							if (!isset($aDetailsStruct[$sCurrentTab][$sCurrentCol][$sName]))
							{
								$aDetails[$sCurrentTab][$sCurrentCol][$sName] = array();
							}
							$aDetails = self::ProcessZlist($value, $aDetails, $sCurrentTab, $sCurrentCol, $sName);
							break;

						default:
						case 'col':
							if (!isset($aDetails[$sCurrentTab][$sName]))
							{
								$aDetails[$sCurrentTab][$sName] = array();
							}
							$aDetails = self::ProcessZlist($value, $aDetails, $sCurrentTab, $sName, '');
							break;
					}
				}
			}
			else
			{
				if (empty($sCurrentSet))
				{
					$aDetails[$sCurrentTab][$sCurrentCol]['_'.$index][] = $value;
				}
				else
				{
					$aDetails[$sCurrentTab][$sCurrentCol][$sCurrentSet][] = $value;
				}
			}
			$index++;
		}

		return $aDetails;
	}

	public static function FlattenZList($aList)
	{
		$aResult = array();
		foreach($aList as $value)
		{
			if (!is_array($value))
			{
				$aResult[] = $value;
			}
			else
			{
				$aResult = array_merge($aResult, self::FlattenZList($value));
			}
		}

		return $aResult;
	}

	protected function GetFieldAsHtml($sClass, $sAttCode, $sStateAttCode)
	{
		$retVal = null;
		if ($this->IsNew())
		{
			$iFlags = $this->GetInitialStateAttributeFlags($sAttCode);
		}
		else
		{
			$iFlags = $this->GetAttributeFlags($sAttCode);
		}
		$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
		if ((!$oAttDef->IsLinkSet()) && (($iFlags & OPT_ATT_HIDDEN) == 0) && !($oAttDef instanceof AttributeDashboard))
		{
			// The field is visible in the current state of the object
			if ($sStateAttCode == $sAttCode)
			{
				// Special display for the 'state' attribute itself
				$sDisplayValue = $this->GetStateLabel();
			}
			else
			{
				if ($oAttDef->GetEditClass() == 'Document')
				{
					$oDocument = $this->Get($sAttCode);
					if (!$oDocument->IsEmpty())
					{
						$sDisplayValue = $this->GetAsHTML($sAttCode);
						$sDisplayValue .= "<br/>".Dict::Format('UI:OpenDocumentInNewWindow_',
								$oDocument->GetDisplayLink(get_class($this), $this->GetKey(), $sAttCode)).", \n";
						$sDisplayValue .= "<br/>".Dict::Format('UI:DownloadDocument_',
								$oDocument->GetDownloadLink(get_class($this), $this->GetKey(), $sAttCode)).", \n";
					}
					else
					{
						$sDisplayValue ='';
					}
				}
				elseif ($oAttDef instanceof AttributeDashboard)
				{
					$sDisplayValue = '';
				}
				else
				{
					$sDisplayValue = $this->GetAsHTML($sAttCode);
				}
			}
			$retVal = array(
				'label' => '<span title="'.MetaModel::GetDescription($sClass,
						$sAttCode).'">'.MetaModel::GetLabel($sClass, $sAttCode).'</span>',
				'value' => $sDisplayValue,
			);
		}

		return $retVal;
	}

	/**
	 * Displays a blob document *inline* (if possible, depending on the type of the document)
	 *
	 * @param \WebPage $oPage
	 * @param $sAttCode
	 *
	 * @return string
	 * @throws \CoreException
	 */
	public function DisplayDocumentInline(WebPage $oPage, $sAttCode)
	{
		/** @var \ormDocument $oDoc */
		$oDoc = $this->Get($sAttCode);
		$sClass = get_class($this);
		$Id = $this->GetKey();
		switch ($oDoc->GetMainMimeType()) {
			case 'text':
			case 'html':
				$data = $oDoc->GetData();
				switch ($oDoc->GetMimeType()) {
					case 'text/xml':
						$oPage->add("<iframe id='preview_$sAttCode' src=\"".utils::GetAbsoluteUrlAppRoot()."pages/ajax.render.php?operation=display_document&class=$sClass&id=$Id&field=$sAttCode\" width=\"100%\" height=\"400\">Loading...</iframe>\n");
						break;

					default:
						$oPage->add("<pre>".htmlentities(MyHelpers::beautifulstr($data, 1000, true), ENT_QUOTES,
								'UTF-8')."</pre>\n");
				}
				break;

			case 'application':
				switch ($oDoc->GetMimeType())
				{
					case 'application/pdf':
						$oPage->add("<iframe id='preview_$sAttCode' src=\"".utils::GetAbsoluteUrlAppRoot()."pages/ajax.render.php?operation=display_document&class=$sClass&id=$Id&field=$sAttCode\" width=\"100%\" height=\"400\">Loading...</iframe>\n");
						break;

					default:
						$oPage->add(Dict::S('UI:Document:NoPreview'));
				}
				break;

			case 'image':
				$oPage->add("<img src=\"".utils::GetAbsoluteUrlAppRoot()."pages/ajax.render.php?operation=display_document&class=$sClass&id=$Id&field=$sAttCode\" />\n");
				break;

			default:
				$oPage->add(Dict::S('UI:Document:NoPreview'));
		}
		return '';
	}

	// $m_highlightComparison[previous][new] => next value
	protected static $m_highlightComparison = array(
		HILIGHT_CLASS_CRITICAL => array(
			HILIGHT_CLASS_CRITICAL => HILIGHT_CLASS_CRITICAL,
			HILIGHT_CLASS_WARNING => HILIGHT_CLASS_CRITICAL,
			HILIGHT_CLASS_OK => HILIGHT_CLASS_CRITICAL,
			HILIGHT_CLASS_NONE => HILIGHT_CLASS_CRITICAL,
		),
		HILIGHT_CLASS_WARNING => array(
			HILIGHT_CLASS_CRITICAL => HILIGHT_CLASS_CRITICAL,
			HILIGHT_CLASS_WARNING => HILIGHT_CLASS_WARNING,
			HILIGHT_CLASS_OK => HILIGHT_CLASS_WARNING,
			HILIGHT_CLASS_NONE => HILIGHT_CLASS_WARNING,
		),
		HILIGHT_CLASS_OK => array(
			HILIGHT_CLASS_CRITICAL => HILIGHT_CLASS_CRITICAL,
			HILIGHT_CLASS_WARNING => HILIGHT_CLASS_WARNING,
			HILIGHT_CLASS_OK => HILIGHT_CLASS_OK,
			HILIGHT_CLASS_NONE => HILIGHT_CLASS_OK,
		),
		HILIGHT_CLASS_NONE => array(
			HILIGHT_CLASS_CRITICAL => HILIGHT_CLASS_CRITICAL,
			HILIGHT_CLASS_WARNING => HILIGHT_CLASS_WARNING,
			HILIGHT_CLASS_OK => HILIGHT_CLASS_OK,
			HILIGHT_CLASS_NONE => HILIGHT_CLASS_NONE,
		),
	);

	/**
	 * This function returns a 'hilight' CSS class, used to hilight a given row in a table
	 * There are currently (i.e defined in the CSS) 4 possible values HILIGHT_CLASS_CRITICAL,
	 * HILIGHT_CLASS_WARNING, HILIGHT_CLASS_OK, HILIGHT_CLASS_NONE
	 * To Be overridden by derived classes
	 *
	 * @param void
	 *
	 * @return String The desired higlight class for the object/row
	 */
	public function GetHilightClass()
	{
		// Possible return values are:
		// HILIGHT_CLASS_CRITICAL, HILIGHT_CLASS_WARNING, HILIGHT_CLASS_OK, HILIGHT_CLASS_NONE	
		$current = parent::GetHilightClass(); // Default computation

		// Invoke extensions before the deletion (the deletion will do some cleanup and we might loose some information
		/** @var \iApplicationUIExtension $oExtensionInstance */
		foreach(MetaModel::EnumPlugins('iApplicationUIExtension') as $oExtensionInstance)
		{
			$new = $oExtensionInstance->GetHilightClass($this);
			@$current = self::$m_highlightComparison[$current][$new];
		}

		return $current;
	}

	/**
	 * Re-order the fields based on their inter-dependencies
	 *
	 * @params hash @aFields field_code => array_of_depencies
	 *
	 * @param $aFields
	 * @return array Ordered array of fields or throws an exception
	 * @throws \Exception
	 */
	public static function OrderDependentFields($aFields)
	{
		$aResult = array();
		$iCount = 0;
		do
		{
			$bSet = false;
			$iCount++;
			foreach($aFields as $sFieldCode => $aDeps)
			{
				foreach($aDeps as $key => $sDependency)
				{
					if (in_array($sDependency, $aResult))
					{
						// Dependency is resolved, remove it
						unset($aFields[$sFieldCode][$key]);
					}
					else
					{
						if (!array_key_exists($sDependency, $aFields))
						{
							// The current fields depends on a field not present in the form
							// let's ignore it (since it cannot change)
							unset($aFields[$sFieldCode][$key]);
						}
					}
				}
				if (count($aFields[$sFieldCode]) == 0)
				{
					// No more pending depencies for this field, add it to the list
					$aResult[] = $sFieldCode;
					unset($aFields[$sFieldCode]);
					$bSet = true;
				}
			}
		} while ($bSet && (count($aFields) > 0));

		if (count($aFields) > 0)
		{
			$sMessage = "Error: Circular dependencies between the fields! <pre>".print_r($aFields, true)."</pre>";
			throw(new Exception($sMessage));
		}

		return $aResult;
	}

	/**
	 * Get the list of actions to be displayed as 'shortcuts' (i.e buttons) instead of inside the Actions popup menu
	 *
	 * @param $sFinalClass string The actual class of the objects for which to display the menu
	 *
	 * @return array the list of menu codes (i.e dictionary entries) that can be displayed as shortcuts next to the
	 *     actions menu
	 */
	public static function GetShortcutActions($sFinalClass)
	{
		$sShortcutActions = MetaModel::GetConfig()->Get('shortcut_actions');
		$aShortcutActions = explode(',', $sShortcutActions);

		return $aShortcutActions;
	}

	/**
	 * Maps the given context parameter name to the appropriate filter/search code for this class
	 *
	 * @param string $sContextParam Name of the context parameter, i.e. 'org_id'
	 *
	 * @return string Filter code, i.e. 'customer_id'
	 */
	public static function MapContextParam($sContextParam)
	{
		if ($sContextParam == 'menu')
		{
			return null;
		}
		else
		{
			return $sContextParam;
		}
	}

	/**
	 * Updates the object from a flat array of values
	 *
	 * @param $aAttList array $aAttList array of attcode
	 * @param $aErrors array Returns information about slave attributes
	 * @param $aAttFlags array Attribute codes => Flags to use instead of those from the MetaModel
	 *
	 * @return array of attcodes that can be used for writing on the current object
	 * @throws \CoreException
	 */
	public function GetWriteableAttList($aAttList, &$aErrors, $aAttFlags = array())
	{
		if (!is_array($aAttList))
		{
			$aAttList = $this->FlattenZList(MetaModel::GetZListItems(get_class($this), 'details'));
			// Special case to process the case log, if any...
			// WARNING: if you change this also check the functions DisplayModifyForm and DisplayCaseLog
			foreach(MetaModel::ListAttributeDefs(get_class($this)) as $sAttCode => $oAttDef)
			{

				if (array_key_exists($sAttCode, $aAttFlags))
				{
					$iFlags = $aAttFlags[$sAttCode];
				}
				elseif ($this->IsNew())
				{
					$iFlags = $this->GetInitialStateAttributeFlags($sAttCode);
				}
				else
				{
					$aVoid = array();
					$iFlags = $this->GetAttributeFlags($sAttCode, $aVoid);
				}
				if ($oAttDef instanceof AttributeCaseLog)
				{
					if (!($iFlags & (OPT_ATT_HIDDEN | OPT_ATT_SLAVE | OPT_ATT_READONLY)))
					{
						// The case log is editable, append it to the list of fields to retrieve
						$aAttList[] = $sAttCode;
					}
				}
			}
		}
		$aWriteableAttList = array();
		foreach($aAttList as $sAttCode)
		{
			$oAttDef = MetaModel::GetAttributeDef(get_class($this), $sAttCode);

			if (array_key_exists($sAttCode, $aAttFlags))
			{
				$iFlags = $aAttFlags[$sAttCode];
			}
			elseif ($this->IsNew())
			{
				$iFlags = $this->GetInitialStateAttributeFlags($sAttCode);
			}
			else
			{
				$aVoid = array();
				$iFlags = $this->GetAttributeFlags($sAttCode, $aVoid);
			}
			if ($oAttDef->IsWritable())
			{
				if ($iFlags & (OPT_ATT_HIDDEN | OPT_ATT_READONLY))
				{
					// Non-visible, or read-only attribute, do nothing
				}
				elseif ($iFlags & OPT_ATT_SLAVE)
				{
					$aErrors[$sAttCode] = Dict::Format('UI:AttemptingToSetASlaveAttribute_Name', $oAttDef->GetLabel());
				}
				else
				{
					$aWriteableAttList[$sAttCode] = $oAttDef;
				}
			}
		}

		return $aWriteableAttList;
	}

	/**
	 * Compute the attribute flags depending on the object state
	 */
	public function GetFormAttributeFlags($sAttCode)
	{
		if ($this->IsNew())
		{
			$iFlags = $this->GetInitialStateAttributeFlags($sAttCode);
		}
		else
		{
			$iFlags = $this->GetAttributeFlags($sAttCode);
		}
		if (($iFlags & OPT_ATT_MANDATORY) && $this->IsNew())
		{
			$iFlags = $iFlags & ~OPT_ATT_READONLY; // Mandatory fields cannot be read-only when creating an object
		}

		return $iFlags;
	}

	/**
	 * Updates the object from a flat array of values
	 *
	 * @param $aValues array of attcode => scalar or array (N-N links)
	 *
	 * @return void
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 */
	public function UpdateObjectFromArray($aValues)
	{
		foreach($aValues as $sAttCode => $value)
		{
			$oAttDef = MetaModel::GetAttributeDef(get_class($this), $sAttCode);
			switch ($oAttDef->GetEditClass())
			{
				case 'Document':
				case 'Image':
					// There should be an uploaded file with the named attr_<attCode>
					if ($value['remove'])
					{
						$this->Set($sAttCode, null);
					}
					else
					{
						$oDocument = $value['fcontents'];
						if (!$oDocument->IsEmpty())
						{
							// A new file has been uploaded
							$this->Set($sAttCode, $oDocument);
						}
					}
					break;
				case 'One Way Password':
					// Check if the password was typed/changed
					$aPwdData = $value;
					if (!is_null($aPwdData) && $aPwdData['changed'])
					{
						// The password has been changed or set
						$this->Set($sAttCode, $aPwdData['value']);
					}
					break;
				case 'Duration':
					$aDurationData = $value;
					if (!is_array($aDurationData))
					{
						break;
					}

					$iValue = (((24 * $aDurationData['d']) + $aDurationData['h']) * 60 + $aDurationData['m']) * 60 + $aDurationData['s'];
					$this->Set($sAttCode, $iValue);
					$previousValue = $this->Get($sAttCode);
					if ($previousValue !== $iValue)
					{
						$this->Set($sAttCode, $iValue);
					}
					break;
				case 'CustomFields':
					$this->Set($sAttCode, $value);
					break;
				case 'LinkedSet':
					if ($this->IsValueModified($value))
					{
						$oLinkSet = $this->Get($sAttCode);
						$sLinkedClass = $oAttDef->GetLinkedClass();
						if (array_key_exists('to_be_created', $value) && (count($value['to_be_created']) > 0))
						{
							// Now handle the links to be created
							foreach ($value['to_be_created'] as $aData)
							{
								$sSubClass = $aData['class'];
								if (($sLinkedClass == $sSubClass) || (is_subclass_of($sSubClass, $sLinkedClass)))
								{
									$aObjData = $aData['data'];
									$oLink = MetaModel::NewObject($sSubClass);
									$oLink->UpdateObjectFromArray($aObjData);
									$oLinkSet->AddItem($oLink);
								}
							}
						}
						if (array_key_exists('to_be_added', $value) && (count($value['to_be_added']) > 0))
						{
							// Now handle the links to be added by making the remote object point to self
							foreach ($value['to_be_added'] as $iObjKey)
							{
								$oLink = MetaModel::GetObject($sLinkedClass, $iObjKey, false);
								if ($oLink)
								{
									$oLinkSet->AddItem($oLink);
								}
							}
						}
						if (array_key_exists('to_be_modified', $value) && (count($value['to_be_modified']) > 0))
						{
							// Now handle the links to be added by making the remote object point to self
							foreach ($value['to_be_modified'] as $iObjKey => $aData)
							{
								$oLink = MetaModel::GetObject($sLinkedClass, $iObjKey, false);
								if ($oLink)
								{
									$aObjData = $aData['data'];
									$oLink->UpdateObjectFromArray($aObjData);
									$oLinkSet->ModifyItem($oLink);
								}
							}
						}
						if (array_key_exists('to_be_removed', $value) && (count($value['to_be_removed']) > 0))
						{
							foreach ($value['to_be_removed'] as $iObjKey)
							{
								$oLinkSet->RemoveItem($iObjKey);
							}
						}
						if (array_key_exists('to_be_deleted', $value) && (count($value['to_be_deleted']) > 0))
						{
							foreach ($value['to_be_deleted'] as $iObjKey)
							{
								$oLinkSet->RemoveItem($iObjKey);
							}
						}
						$this->Set($sAttCode, $oLinkSet);
					}
					break;

				case 'TagSet':
					/** @var ormTagSet $oTagSet */
					$oTagSet = $this->Get($sAttCode);
					if (is_null($oTagSet))
					{
						$oTagSet = new ormTagSet(get_class($this), $sAttCode, $oAttDef->GetMaxItems());
					}
					$oTagSet->ApplyDelta($value);
					$this->Set($sAttCode, $oTagSet);
					break;

				case 'Set':
					/** @var ormSet $oSet */
					$oSet = $this->Get($sAttCode);
					if (is_null($oSet))
					{
						$oSet = new ormSet(get_class($this), $sAttCode, $oAttDef->GetMaxItems());
					}
					$oSet->ApplyDelta($value);
					$this->Set($sAttCode, $oSet);
					break;

				default:
					if (!is_null($value))
					{
						$aAttributes[$sAttCode] = trim($value);
						$previousValue = $this->Get($sAttCode);
						if ($previousValue !== $aAttributes[$sAttCode])
						{
							$this->Set($sAttCode, $aAttributes[$sAttCode]);
						}
					}
			}
		}
	}

	private function IsValueModified($value)
	{
		$aModifiedKeys = ['to_be_created', 'to_be_added', 'to_be_modified', 'to_be_removed', 'to_be_deleted'];
		foreach ($aModifiedKeys as $sModifiedKey) {
			if (array_key_exists( $sModifiedKey, $value) && (count($value[$sModifiedKey]) > 0))
			{
				return true;
			}
		}
		return false;
	}

	/**
	 * Updates the object from the POSTed parameters (form)
	 */
	public function UpdateObjectFromPostedForm($sFormPrefix = '', $aAttList = null, $aAttFlags = array())
	{
		if (is_null($aAttList))
		{
			$aAttList = array_keys(MetaModel::ListAttributeDefs(get_class($this)));
		}
		$aValues = array();
		foreach($aAttList as $sAttCode)
		{
			$value = $this->PrepareValueFromPostedForm($sFormPrefix, $sAttCode);
			if (!is_null($value))
			{
				$aValues[$sAttCode] = $value;
			}
		}

		$aErrors = array();
		$aFinalValues = array();
		foreach($this->GetWriteableAttList(array_keys($aValues), $aErrors, $aAttFlags) as $sAttCode => $oAttDef)
		{
			$aFinalValues[$sAttCode] = $aValues[$sAttCode];
		}
		try
		{
			$this->UpdateObjectFromArray($aFinalValues);
		}
		catch (CoreException $e)
		{
			$aErrors[] = $e->getMessage();
		}
		if (!$this->IsNew()) // for new objects this is performed in DBInsertNoReload()
		{
			InlineImage::FinalizeInlineImages($this);
		}

		// Invoke extensions after the update of the object from the form
		/** @var \iApplicationUIExtension $oExtensionInstance */
		foreach(MetaModel::EnumPlugins('iApplicationUIExtension') as $oExtensionInstance)
		{
			$oExtensionInstance->OnFormSubmit($this, $sFormPrefix);
		}

		return $aErrors;
	}

	/**
	 * @param string $sFormPrefix
	 * @param string $sAttCode
	 * @param string $sClass Optional parameter, host object's class for the $sAttCode
	 * @param array $aPostedData Optional parameter, used through recursive calls
	 *
	 * @return array|null
	 * @throws \FileUploadException
	 */
	protected function PrepareValueFromPostedForm($sFormPrefix, $sAttCode, $sClass = null, $aPostedData = null)
	{
		if ($sClass === null)
		{
			$sClass = get_class($this);
		}

		$value = null;

		$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
		switch ($oAttDef->GetEditClass())
		{
			case  'Document':
				$aOtherData = utils::ReadPostedParam("attr_{$sFormPrefix}{$sAttCode}", null, 'raw_data');
				$value = array('fcontents' => utils::ReadPostedDocument("attr_{$sFormPrefix}{$sAttCode}", 'fcontents'), 'remove' => $aOtherData['remove']);
				break;

			case 'Image':
				$value = null;
				$oImage = utils::ReadPostedDocument("attr_{$sFormPrefix}{$sAttCode}", 'fcontents');
				if (!is_null($oImage->GetData()))
				{
					$aSize = utils::GetImageSize($oImage->GetData());
					$oImage = utils::ResizeImageToFit(
						$oImage,
						$aSize[0],
						$aSize[1],
						$oAttDef->Get('storage_max_width'),
						$oAttDef->Get('storage_max_height')
					);
				}
				$aOtherData = utils::ReadPostedParam("attr_{$sFormPrefix}{$sAttCode}", null, 'raw_data');
				if (is_array($aOtherData))
				{
					$value = array('fcontents' => $oImage, 'remove' => $aOtherData['remove']);
				}
				break;

			case 'RedundancySetting':
				$value = $oAttDef->ReadValueFromPostedForm($sFormPrefix);
				break;

			case 'CustomFields':
				$value = $oAttDef->ReadValueFromPostedForm($this, $sFormPrefix);
				break;

			case 'LinkedSet':
				/** @var AttributeLinkedSet $oAttDef */
				$aRawToBeCreated = json_decode(utils::ReadPostedParam("attr_{$sFormPrefix}{$sAttCode}_tbc", '{}',
					'raw_data'), true);
				$aToBeCreated = array();
				foreach($aRawToBeCreated as $aData)
				{
					$sSubFormPrefix = $aData['formPrefix'];
					$sObjClass = isset($aData['class']) ? $aData['class'] : $oAttDef->GetLinkedClass();
					$aObjData = array();
					foreach($aData as $sKey => $value)
					{
						if (preg_match("/^attr_$sSubFormPrefix(.*)$/", $sKey, $aMatches))
						{
							$oLinkAttDef = MetaModel::GetAttributeDef($sObjClass, $aMatches[1]);
							// Recursing over n:n link datetime attributes
							// Note: We might need to do it with other attribute types, like Document or redundancy setting.
							if ($oLinkAttDef instanceof AttributeDateTime)
							{
								$aObjData[$aMatches[1]] = $this->PrepareValueFromPostedForm($sSubFormPrefix,
									$aMatches[1], $sObjClass, $aData);
							}
							else
							{
								$aObjData[$aMatches[1]] = $value;
							}
						}
					}
					$aToBeCreated[] = array('class' => $sObjClass, 'data' => $aObjData);
				}

				$aRawToBeModified = json_decode(utils::ReadPostedParam("attr_{$sFormPrefix}{$sAttCode}_tbm", '{}',
					'raw_data'), true);
				$aToBeModified = array();
				foreach($aRawToBeModified as $iObjKey => $aData)
				{
					$sSubFormPrefix = $aData['formPrefix'];
					$sObjClass = isset($aData['class']) ? $aData['class'] : $oAttDef->GetLinkedClass();
					$aObjData = array();
					foreach($aData as $sKey => $value)
					{
						if (preg_match("/^attr_$sSubFormPrefix(.*)$/", $sKey, $aMatches))
						{
							$oLinkAttDef = MetaModel::GetAttributeDef($sObjClass, $aMatches[1]);
							// Recursing over n:n link datetime attributes
							// Note: We might need to do it with other attribute types, like Document or redundancy setting.
							if ($oLinkAttDef instanceof AttributeDateTime)
							{
								$aObjData[$aMatches[1]] = $this->PrepareValueFromPostedForm($sSubFormPrefix,
									$aMatches[1], $sObjClass, $aData);
							}
							else
							{
								$aObjData[$aMatches[1]] = $value;
							}
						}
					}
					$aToBeModified[$iObjKey] = array('data' => $aObjData);
				}

				$value = array(
					'to_be_created' => $aToBeCreated,
					'to_be_modified' => $aToBeModified,
					'to_be_deleted' => json_decode(utils::ReadPostedParam("attr_{$sFormPrefix}{$sAttCode}_tbd", '[]',
						'raw_data'), true),
					'to_be_added' => json_decode(utils::ReadPostedParam("attr_{$sFormPrefix}{$sAttCode}_tba", '[]',
						'raw_data'), true),
					'to_be_removed' => json_decode(utils::ReadPostedParam("attr_{$sFormPrefix}{$sAttCode}_tbr", '[]',
						'raw_data'), true),
				);
				break;

			case 'Set':
			case 'TagSet':
				$sTagSetJson = utils::ReadPostedParam("attr_{$sFormPrefix}{$sAttCode}", null, 'raw_data');
				$value = json_decode($sTagSetJson, true);
				break;

			default:
				if ($oAttDef instanceof AttributeDateTime) // AttributeDate is derived from AttributeDateTime
				{
					// Retrieving value from array when present (means what we are in a recursion)
					if ($aPostedData !== null && isset($aPostedData['attr_'.$sFormPrefix.$sAttCode]))
					{
						$value = $aPostedData['attr_'.$sFormPrefix.$sAttCode];
					}
					else
					{
						$value = utils::ReadPostedParam("attr_{$sFormPrefix}{$sAttCode}", null, 'raw_data');
					}

					if ($value != null)
					{
						$oDate = $oAttDef->GetFormat()->Parse($value);
						if ($oDate instanceof DateTime)
						{
							$value = $oDate->format($oAttDef->GetInternalFormat());
						}
						else
						{
							$value = null;
						}
					}
				}
				else
				{
					// Retrieving value from array when present (means what we are in a recursion)
					if ($aPostedData !== null && isset($aPostedData['attr_'.$sFormPrefix.$sAttCode]))
					{
						$value = $aPostedData['attr_'.$sFormPrefix.$sAttCode];
					}
					else
					{
						$value = utils::ReadPostedParam("attr_{$sFormPrefix}{$sAttCode}", null, 'raw_data');
					}
				}
				break;
		}

		return $value;
	}

	/**
	 * Updates the object from a given page argument
	 */
	public function UpdateObjectFromArg($sArgName, $aAttList = null, $aAttFlags = array())
	{
		if (is_null($aAttList))
		{
			$aAttList = array_keys(MetaModel::ListAttributeDefs(get_class($this)));
		}
		$aRawValues = utils::ReadParam($sArgName, array(), '', 'raw_data');
		$aValues = array();
		foreach($aAttList as $sAttCode)
		{
			if (isset($aRawValues[$sAttCode]))
			{
				$aValues[$sAttCode] = $aRawValues[$sAttCode];
			}
		}

		$aErrors = array();
		$aFinalValues = array();
		foreach($this->GetWriteableAttList(array_keys($aValues), $aErrors, $aAttFlags) as $sAttCode => $oAttDef)
		{
			if ($oAttDef->IsLinkSet())
			{
				$aFinalValues[$sAttCode] = json_decode($aValues[$sAttCode], true);
			}
			else
			{
				$aFinalValues[$sAttCode] = $aValues[$sAttCode];
			}
		}
		try
		{
			$this->UpdateObjectFromArray($aFinalValues);
		}
		catch (CoreException $e)
		{
			$aErrors[] = $e->getMessage();
		}
		return $aErrors;
	}

	/**
	 * @inheritdoc
	 */
	public function DBInsertNoReload()
	{
		$res = parent::DBInsertNoReload();

		$this->SetWarningsAsSessionMessages('create');

		// Invoke extensions after insertion (the object must exist, have an id, etc.)
		/** @var \iApplicationObjectExtension $oExtensionInstance */
		foreach(MetaModel::EnumPlugins('iApplicationObjectExtension') as $oExtensionInstance)
		{
			$oExtensionInstance->OnDBInsert($this, self::GetCurrentChange());
		}

		return $res;
	}

	/**
	 * @inheritdoc
	 * Attaches InlineImages to the current object
	 */
	protected function OnObjectKeyReady()
	{
		InlineImage::FinalizeInlineImages($this);
	}

	protected function DBCloneTracked_Internal($newKey = null)
	{
		$oNewObj = parent::DBCloneTracked_Internal($newKey);

		// Invoke extensions after insertion (the object must exist, have an id, etc.)
		/** @var \iApplicationObjectExtension $oExtensionInstance */
		foreach(MetaModel::EnumPlugins('iApplicationObjectExtension') as $oExtensionInstance)
		{
			$oExtensionInstance->OnDBInsert($oNewObj, self::GetCurrentChange());
		}

		return $oNewObj;
	}

	public function DBUpdate()
	{
		$res = parent::DBUpdate();

		$this->SetWarningsAsSessionMessages('update');

		// Protection against reentrance (e.g. cascading the update of ticket logs)
		// Note: This is based on the fix made on r 3190 in DBObject::DBUpdate()
		static $aUpdateReentrance = array();
		$sKey = get_class($this).'::'.$this->GetKey();
		if (array_key_exists($sKey, $aUpdateReentrance))
		{
			return $res;
		}
		$aUpdateReentrance[$sKey] = true;

		try
		{
			// Invoke extensions after the update (could be before)
			/** @var \iApplicationObjectExtension $oExtensionInstance */
			foreach (MetaModel::EnumPlugins('iApplicationObjectExtension') as $oExtensionInstance)
			{
				$oExtensionInstance->OnDBUpdate($this, self::GetCurrentChange());
			}
		}
		catch (Exception $e)
		{
			throw $e;
		}
		finally
		{
			unset($aUpdateReentrance[$sKey]);
		}

		return $res;
	}

	/**
	 * @param string $sMessageIdPrefix
	 *
	 * @since 2.6.0
	 */
	protected function SetWarningsAsSessionMessages($sMessageIdPrefix)
	{
		if (!empty($this->m_aCheckWarnings) && is_array($this->m_aCheckWarnings))
		{
			$iMsgNb = 0;
			foreach ($this->m_aCheckWarnings as $sWarningMessage)
			{
				$iMsgNb++;
				$sMessageId = "$sMessageIdPrefix-$iMsgNb"; // each message must have its own messageId !
				$this->SetSessionMessageFromInstance($sMessageId, $sWarningMessage, 'info', 0);
			}
		}
	}

	protected static function BulkUpdateTracked_Internal(DBSearch $oFilter, array $aValues)
	{
		// Todo - invoke the extension
		return parent::BulkUpdateTracked_Internal($oFilter, $aValues);
	}

	protected function DBDeleteTracked_Internal(&$oDeletionPlan = null)
	{
		// Invoke extensions before the deletion (the deletion will do some cleanup and we might loose some information
		/** @var \iApplicationObjectExtension $oExtensionInstance */
		foreach(MetaModel::EnumPlugins('iApplicationObjectExtension') as $oExtensionInstance)
		{
			$oExtensionInstance->OnDBDelete($this, self::GetCurrentChange());
		}

		return parent::DBDeleteTracked_Internal($oDeletionPlan);
	}

	public function IsModified()
	{
		if (parent::IsModified())
		{
			return true;
		}

		// Plugins
		//
		/** @var \iApplicationObjectExtension $oExtensionInstance */
		foreach(MetaModel::EnumPlugins('iApplicationObjectExtension') as $oExtensionInstance)
		{
			if ($oExtensionInstance->OnIsModified($this))
			{
				return true;
			}
		}

		return false;
	}

	/**
	 * Bypass the check of the user rights when writing this object
	 *
	 * @param bool $bAllow True to bypass the checks, false to restore the default behavior
	 */
	public function AllowWrite($bAllow = true)
	{
		$this->bAllowWrite = $bAllow;
	}

	/**
	 * Bypass the check of the user rights when deleting this object
	 *
	 * @param bool $bAllow True to bypass the checks, false to restore the default behavior
	 */
	public function AllowDelete($bAllow = true)
	{
		$this->bAllowDelete = $bAllow;
	}

	/**
	 * @inheritdoc
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \OQLException
	 */
	public function DoCheckToWrite()
	{
		parent::DoCheckToWrite();

		// Plugins
		//
		/** @var \iApplicationObjectExtension $oExtensionInstance */
		foreach(MetaModel::EnumPlugins('iApplicationObjectExtension') as $oExtensionInstance)
		{
			$aNewIssues = $oExtensionInstance->OnCheckToWrite($this);
			if (is_array($aNewIssues) && (count($aNewIssues) > 0)) // Some extensions return null instead of an empty array
			{
				$this->m_aCheckIssues = array_merge($this->m_aCheckIssues, $aNewIssues);
			}
		}

		// User rights
		//
		if (!$this->bAllowWrite)
		{
			$aChanges = $this->ListChanges();
			if (count($aChanges) > 0)
			{
				$aForbiddenFields = array();
				foreach($this->ListChanges() as $sAttCode => $value)
				{
					$bUpdateAllowed = UserRights::IsActionAllowedOnAttribute(get_class($this), $sAttCode,
						UR_ACTION_MODIFY, DBObjectSet::FromObject($this));
					if (!$bUpdateAllowed)
					{
						$oAttCode = MetaModel::GetAttributeDef(get_class($this), $sAttCode);
						$aForbiddenFields[] = $oAttCode->GetLabel();
					}
				}
				if (count($aForbiddenFields) > 0)
				{
					// Security issue
					$this->m_bSecurityIssue = true;
					$this->m_aCheckIssues[] = Dict::Format('UI:Delete:NotAllowedToUpdate_Fields',
						implode(', ', $aForbiddenFields));
				}
			}
		}
	}

	/**
	 * @inheritDoc
	 */
	protected function DoCheckToDelete(&$oDeletionPlan)
	{
		parent::DoCheckToDelete($oDeletionPlan);

		// Plugins
		//
		/** @var \iApplicationObjectExtension $oExtensionInstance */
		foreach(MetaModel::EnumPlugins('iApplicationObjectExtension') as $oExtensionInstance)
		{
			$aNewIssues = $oExtensionInstance->OnCheckToDelete($this);
			if (is_array($aNewIssues) && count($aNewIssues) > 0)
			{
				$this->m_aDeleteIssues = array_merge($this->m_aDeleteIssues, $aNewIssues);
			}
		}

		// User rights
		//
		if (! $this->bAllowDelete)
		{
			$bDeleteAllowed = UserRights::IsActionAllowed(get_class($this), UR_ACTION_DELETE, DBObjectSet::FromObject($this));

			if (!$bDeleteAllowed)
			{
				// Security issue
				$this->m_bSecurityIssue = true;
				$this->m_aDeleteIssues[] = Dict::S('UI:Delete:NotAllowedToDelete');
			}
		}
	}

	/**
	 * Special display where the case log uses the whole "screen" at the bottom of the "Properties" tab
	 *
	 * @param \WebPage $oPage
	 * @param string $sAttCode
	 * @param string $sComment
	 * @param string $sPrefix
	 * @param bool $bEditMode
	 *
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \DictExceptionMissingString
	 * @throws \MySQLException
	 * @throws \OQLException
	 * @throws \Exception
	 */
	public function DisplayCaseLog(WebPage $oPage, $sAttCode, $sComment = '', $sPrefix = '', $bEditMode = false)
	{
		$oPage->SetCurrentTab('UI:PropertiesTab');
		$sClass = get_class($this);

		if ($this->IsNew())
		{
			$iFlags = $this->GetInitialStateAttributeFlags($sAttCode);
		}
		else
		{
			$iFlags = $this->GetAttributeFlags($sAttCode);
		}

		if ($iFlags & OPT_ATT_HIDDEN)
		{
			// The case log is hidden do nothing
		}
		else
		{
			$oAttDef = MetaModel::GetAttributeDef(get_class($this), $sAttCode);
			$sAttDefClass = get_class($oAttDef);
			$sAttLabel = $oAttDef->GetLabel();
			$sAttMetaDataLabel = utils::HtmlEntities($sAttLabel);
			$sAttMetaDataFlagHidden = (($iFlags & OPT_ATT_HIDDEN) === OPT_ATT_HIDDEN) ? 'true' : 'false';
			$sAttMetaDataFlagReadOnly = (($iFlags & OPT_ATT_READONLY) === OPT_ATT_READONLY) ? 'true' : 'false';
			$sAttMetaDataFlagMandatory = (($iFlags & OPT_ATT_MANDATORY) === OPT_ATT_MANDATORY) ? 'true' : 'false';
			$sAttMetaDataFlagMustChange = (($iFlags & OPT_ATT_MUSTCHANGE) === OPT_ATT_MUSTCHANGE) ? 'true' : 'false';
			$sAttMetaDataFlagMustPrompt = (($iFlags & OPT_ATT_MUSTPROMPT) === OPT_ATT_MUSTPROMPT) ? 'true' : 'false';
			$sAttMetaDataFlagSlave = (($iFlags & OPT_ATT_SLAVE) === OPT_ATT_SLAVE) ? 'true' : 'false';

			$sInputId = $this->m_iFormId.'_'.$sAttCode;

			if ((!$bEditMode) || ($iFlags & (OPT_ATT_READONLY | OPT_ATT_SLAVE)))
			{
				// Check if the attribute is not read-only because of a synchro...
				$sSynchroIcon = '';
				if ($iFlags & OPT_ATT_SLAVE)
				{
					$aReasons = array();
					$iSynchroFlags = $this->GetSynchroReplicaFlags($sAttCode, $aReasons);
					$sSynchroIcon = "&nbsp;<img id=\"synchro_$sInputId\" src=\"../images/transp-lock.png\" style=\"vertical-align:middle\"/>";
					$sTip = '';
					foreach($aReasons as $aRow)
					{
						$sDescription = htmlentities($aRow['description'], ENT_QUOTES, 'UTF-8');
						$sDescription = str_replace(array("\r\n", "\n"), "<br/>", $sDescription);
						$sTip .= "<div class=\"synchro-source\">";
						$sTip .= "<div class=\"synchro-source-title\">Synchronized with {$aRow['name']}</div>";
						$sTip .= "<div class=\"synchro-source-description\">$sDescription</div>";
					}
					$sTip = addslashes($sTip);
					$oPage->add_ready_script("$('#synchro_$sInputId').qtip( { content: '$sTip', show: 'mouseover', hide: 'mouseout', style: { name: 'dark', tip: 'leftTop' }, position: { corner: { target: 'rightMiddle', tooltip: 'leftTop' }} } );");
				}

				// Attribute is read-only
				$sHTMLValue = $this->GetAsHTML($sAttCode);
				$sHTMLValue .= '<input type="hidden" id="'.$sInputId.'" name="attr_'.$sPrefix.$sAttCode.'" value="'.htmlentities($this->GetEditValue($sAttCode),
						ENT_QUOTES, 'UTF-8').'"/>';
				$aFieldsMap[$sAttCode] = $sInputId;
				$sComment .= $sSynchroIcon;
			}
			else
			{
				$sValue = $this->Get($sAttCode);
				$sDisplayValue = $this->GetEditValue($sAttCode);
				$aArgs = array('this' => $this, 'formPrefix' => $sPrefix);

				$sCommentAsHtml = ($sComment != '') ? '<span>'.$sComment.'</span><br/>' : '';
				$sFieldAsHtml = self::GetFormElementForField($oPage, $sClass, $sAttCode, $oAttDef, $sValue, $sDisplayValue, $sInputId, '', $iFlags, $aArgs);
				$sHTMLValue = <<<HTML
<div class="field_data">
	<div class="field_value">
		$sCommentAsHtml
		$sFieldAsHtml
	</div>
</div>
HTML;

				$aFieldsMap[$sAttCode] = $sInputId;
			}

			$oPage->add(<<<HTML
<fieldset>
	<legend>{$sAttLabel}</legend>
	<div class="field_container field_large" data-attribute-code="{$sAttCode}" data-attribute-type="{$sAttDefClass}" data-attribute-label="{$sAttMetaDataLabel}"
		data-attribute-flag-hidden="{$sAttMetaDataFlagHidden}" data-attribute-flag-read-only="{$sAttMetaDataFlagReadOnly}" data-attribute-flag-mandatory="{$sAttMetaDataFlagMandatory}"
		data-attribute-flag-must-change="{$sAttMetaDataFlagMustChange}" data-attribute-flag-must-prompt="{$sAttMetaDataFlagMustPrompt}" data-attribute-flag-slave="{$sAttMetaDataFlagSlave}">
		{$sHTMLValue}
	</div>
</fieldset>
HTML
			);
		}
	}

	/**
	 * @param $sCurrentState
	 * @param $sStimulus
	 * @param $bOnlyNewOnes
	 *
	 * @return array
	 * @throws \ApplicationException
	 * @throws \CoreException
	 * @deprecated Since iTop 2.4, use DBObject::GetTransitionAttributes() instead.
	 */
	public function GetExpectedAttributes($sCurrentState, $sStimulus, $bOnlyNewOnes)
	{
		$aTransitions = $this->EnumTransitions();
		$aStimuli = MetaModel::EnumStimuli(get_class($this));
		if (!isset($aTransitions[$sStimulus]))
		{
			// Invalid stimulus
			throw new ApplicationException(Dict::Format('UI:Error:Invalid_Stimulus_On_Object_In_State', $sStimulus,
				$this->GetName(), $this->GetStateLabel()));
		}
		$aTransition = $aTransitions[$sStimulus];
		$sTargetState = $aTransition['target_state'];
		$aTargetStates = MetaModel::EnumStates(get_class($this));
		$aTargetState = $aTargetStates[$sTargetState];
		$aCurrentState = $aTargetStates[$this->GetState()];
		$aExpectedAttributes = $aTargetState['attribute_list'];
		$aCurrentAttributes = $aCurrentState['attribute_list'];

		$aComputedAttributes = array();
		foreach($aExpectedAttributes as $sAttCode => $iExpectCode)
		{
			if (!array_key_exists($sAttCode, $aCurrentAttributes))
			{
				$aComputedAttributes[$sAttCode] = $iExpectCode;
			}
			else
			{
				if (!($aCurrentAttributes[$sAttCode] & (OPT_ATT_HIDDEN | OPT_ATT_READONLY)))
				{
					$iExpectCode = $iExpectCode & ~(OPT_ATT_MUSTPROMPT | OPT_ATT_MUSTCHANGE); // Already prompted/changed, reset the flags
				}
				// Later: better check if the attribute is not *null*
				if (($iExpectCode & OPT_ATT_MANDATORY) && ($this->Get($sAttCode) != ''))
				{
					$iExpectCode = $iExpectCode & ~(OPT_ATT_MANDATORY); // If the attribute is present, then no need to request its presence
				}

				$aComputedAttributes[$sAttCode] = $iExpectCode;
			}

			$aComputedAttributes[$sAttCode] = $aComputedAttributes[$sAttCode] & ~(OPT_ATT_READONLY | OPT_ATT_HIDDEN); // Don't care about this form now

			if ($aComputedAttributes[$sAttCode] == 0)
			{
				unset($aComputedAttributes[$sAttCode]);
			}
		}

		return $aComputedAttributes;
	}

	/**
	 * Display a form for modifying several objects at once
	 * The form will be submitted to the current page, with the specified additional values
	 *
	 * @param \iTopWebPage $oP
	 * @param string $sClass
	 * @param array $aSelectedObj
	 * @param string $sCustomOperation
	 * @param string $sCancelUrl
	 * @param array $aExcludeAttributes
	 * @param array $aContextData
	 *
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 * @throws \OQLException
	 */
	public static function DisplayBulkModifyForm($oP, $sClass, $aSelectedObj, $sCustomOperation, $sCancelUrl, $aExcludeAttributes = array(), $aContextData = array())
	{
		if (count($aSelectedObj) > 0)
		{
			$iAllowedCount = count($aSelectedObj);
			$sSelectedObj = implode(',', $aSelectedObj);

			$sOQL = "SELECT $sClass WHERE id IN (".$sSelectedObj.")";
			$oSet = new CMDBObjectSet(DBObjectSearch::FromOQL($sOQL));

			// Compute the distribution of the values for each field to determine which of the "scalar" fields are homogeneous
			$aList = MetaModel::ListAttributeDefs($sClass);
			$aValues = array();
			foreach($aList as $sAttCode => $oAttDef)
			{
				if ($oAttDef->IsScalar())
				{
					$aValues[$sAttCode] = array();
				}
			}
			while ($oObj = $oSet->Fetch())
			{
				foreach($aList as $sAttCode => $oAttDef)
				{
					if ($oAttDef->IsScalar() && $oAttDef->IsWritable())
					{
						$currValue = $oObj->Get($sAttCode);
						if ($oAttDef instanceof AttributeCaseLog)
						{
							$currValue = ''; // Put a single scalar value to force caselog to mock a new entry. For more info see N°1059.
						}
						elseif ($currValue instanceof ormSet)
						{
							$currValue = $oAttDef->GetEditValue($currValue, $oObj);
						}
						if (is_object($currValue))
						{
							continue;
						} // Skip non scalar values...
						if (!array_key_exists($currValue, $aValues[$sAttCode]))
						{
							$aValues[$sAttCode][$currValue] = array(
								'count' => 1,
								'display' => $oObj->GetAsHTML($sAttCode),
							);
						}
						else
						{
							$aValues[$sAttCode][$currValue]['count']++;
						}
					}
				}
			}
			// Now create an object that has values for the homogeneous values only
			/** @var \cmdbAbstractObject $oDummyObj */
			$oDummyObj = new $sClass(); // @@ What if the class is abstract ?
			$aComments = array();
			function MyComparison($a, $b) // Sort descending
			{
				if ($a['count'] == $b['count'])
				{
					return 0;
				}

				return ($a['count'] > $b['count']) ? -1 : 1;
			}

			$iFormId = cmdbAbstractObject::GetNextFormId(); // Identifier that prefixes all the form fields
			$sReadyScript = '';
			$sFormPrefix = '2_';
			foreach($aList as $sAttCode => $oAttDef)
			{
				$aPrerequisites = MetaModel::GetPrerequisiteAttributes($sClass,
					$sAttCode); // List of attributes that are needed for the current one
				if (count($aPrerequisites) > 0)
				{
					// When 'enabling' a field, all its prerequisites must be enabled too
					$sFieldList = "['{$sFormPrefix}".implode("','{$sFormPrefix}", $aPrerequisites)."']";
					$oP->add_ready_script("$('#enable_{$sFormPrefix}{$sAttCode}').bind('change', function(evt, sFormId) { return PropagateCheckBox( this.checked, $sFieldList, true); } );\n");
				}
				$aDependents = MetaModel::GetDependentAttributes($sClass,
					$sAttCode); // List of attributes that are needed for the current one
				if (count($aDependents) > 0)
				{
					// When 'disabling' a field, all its dependent fields must be disabled too
					$sFieldList = "['{$sFormPrefix}".implode("','{$sFormPrefix}", $aDependents)."']";
					$oP->add_ready_script("$('#enable_{$sFormPrefix}{$sAttCode}').bind('change', function(evt, sFormId) { return PropagateCheckBox( this.checked, $sFieldList, false); } );\n");
				}
				if ($oAttDef->IsScalar() && $oAttDef->IsWritable())
				{
					if ($oAttDef->GetEditClass() == 'One Way Password')
					{

						$sTip = "Unknown values";
						$sReadyScript .= "$('#multi_values_$sAttCode').qtip( { content: '$sTip', show: 'mouseover', hide: 'mouseout', style: { name: 'dark', tip: 'leftTop' }, position: { corner: { target: 'rightMiddle', tooltip: 'leftTop' }} } );";

						$oDummyObj->Set($sAttCode, null);
						$aComments[$sAttCode] = '<input type="checkbox" id="enable_'.$iFormId.'_'.$sAttCode.'" onClick="ToggleField(this.checked, \''.$iFormId.'_'.$sAttCode.'\')"/>';
						$aComments[$sAttCode] .= '<div class="multi_values" id="multi_values_'.$sAttCode.'"> ? </div>';
						$sReadyScript .= 'ToggleField(false, \''.$iFormId.'_'.$sAttCode.'\');'."\n";
					}
					else
					{
						$iCount = count($aValues[$sAttCode]);
						if ($iCount == 1)
						{
							// Homogeneous value
							reset($aValues[$sAttCode]);
							$aKeys = array_keys($aValues[$sAttCode]);
							$currValue = $aKeys[0]; // The only value is the first key
							//echo "<p>current value for $sAttCode : $currValue</p>";
							$oDummyObj->Set($sAttCode, $currValue);
							$aComments[$sAttCode] = '';
							if ($sAttCode != MetaModel::GetStateAttributeCode($sClass))
							{
								$aComments[$sAttCode] .= '<input type="checkbox" checked id="enable_'.$iFormId.'_'.$sAttCode.'"  onClick="ToggleField(this.checked, \''.$iFormId.'_'.$sAttCode.'\')"/>';
							}
							$aComments[$sAttCode] .= '<div class="mono_value">1</div>';
						}
						else
						{
							// Non-homogeneous value
							$aMultiValues = $aValues[$sAttCode];
							uasort($aMultiValues, 'MyComparison');
							$iMaxCount = 5;
							$sTip = "<p><b>".Dict::Format('UI:BulkModify_Count_DistinctValues', $iCount)."</b><ul>";
							$index = 0;
							foreach($aMultiValues as $sCurrValue => $aVal)
							{
								$sDisplayValue = empty($aVal['display']) ? '<i>'.Dict::S('Enum:Undefined').'</i>' : str_replace(array(
									"\n",
									"\r",
								), " ", $aVal['display']);
								$sTip .= "<li>".Dict::Format('UI:BulkModify:Value_Exists_N_Times', $sDisplayValue,
										$aVal['count'])."</li>";
								$index++;
								if ($iMaxCount == $index)
								{
									$sTip .= "<li>".Dict::Format('UI:BulkModify:N_MoreValues',
											count($aMultiValues) - $iMaxCount)."</li>";
									break;
								}
							}
							$sTip .= "</ul></p>";
							$sTip = addslashes($sTip);
							$sReadyScript .= "$('#multi_values_$sAttCode').qtip( { content: '$sTip', show: 'mouseover', hide: 'mouseout', style: { name: 'dark', tip: 'leftTop' }, position: { corner: { target: 'rightMiddle', tooltip: 'leftTop' }} } );";

							if (($oAttDef->GetEditClass() == 'TagSet') || ($oAttDef->GetEditClass() == 'Set'))
							{
								// Set the value by adding the values to the first one
								reset($aMultiValues);
								$aKeys = array_keys($aMultiValues);
								$currValue = $aKeys[0];
								$oDummyObj->Set($sAttCode, $currValue);
								/** @var ormTagSet $oTagSet */
								$oTagSet = $oDummyObj->Get($sAttCode);
								$oTagSet->SetDisplayPartial(true);
								foreach($aKeys as $iIndex => $sValues)
								{
									if ($iIndex == 0)
									{
										continue;
									}
									$aTagCodes = $oAttDef->FromStringToArray($sValues);
									$oTagSet->GenerateDiffFromArray($aTagCodes);
								}
								$oDummyObj->Set($sAttCode, $oTagSet);
							}
							else
							{
								$oDummyObj->Set($sAttCode, null);
							}
							$aComments[$sAttCode] = '';
							if ($sAttCode != MetaModel::GetStateAttributeCode($sClass))
							{
								$aComments[$sAttCode] .= '<input type="checkbox" id="enable_'.$iFormId.'_'.$sAttCode.'" onClick="ToggleField(this.checked, \''.$iFormId.'_'.$sAttCode.'\')"/>';
							}
							$aComments[$sAttCode] .= '<div class="multi_values" id="multi_values_'.$sAttCode.'">'.$iCount.'</div>';
						}
						$sReadyScript .= 'ToggleField('.(($iCount == 1) ? 'true' : 'false').', \''.$iFormId.'_'.$sAttCode.'\');'."\n";
					}
				}
			}

			$sStateAttCode = MetaModel::GetStateAttributeCode($sClass);
			if (($sStateAttCode != '') && ($oDummyObj->GetState() == ''))
			{
				// Hmmm, it's not gonna work like this ! Set a default value for the "state"
				// Maybe we should use the "state" that is the most common among the objects...
				$aMultiValues = $aValues[$sStateAttCode];
				uasort($aMultiValues, 'MyComparison');
				foreach($aMultiValues as $sCurrValue => $aVal)
				{
					$oDummyObj->Set($sStateAttCode, $sCurrValue);
					break;
				}
				//$oStateAtt = MetaModel::GetAttributeDef($sClass, $sStateAttCode);
				//$oDummyObj->Set($sStateAttCode, $oStateAtt->GetDefaultValue());
			}
			$oP->add("<div class=\"page_header\">\n");
			$oP->add("<h1>".$oDummyObj->GetIcon()."&nbsp;".Dict::Format('UI:Modify_M_ObjectsOf_Class_OutOf_N',
					$iAllowedCount, $sClass, $iAllowedCount)."</h1>\n");
			$oP->add("</div>\n");

			$oP->add("<div class=\"wizContainer\">\n");
			$sDisableFields = json_encode($aExcludeAttributes);

			$aParams = array
			(
				'fieldsComments' => $aComments,
				'noRelations' => true,
				'custom_operation' => $sCustomOperation,
				'custom_button' => Dict::S('UI:Button:PreviewModifications'),
				'selectObj' => $sSelectedObj,
				'preview_mode' => true,
				'disabled_fields' => $sDisableFields,
				'disable_plugins' => true,
			);
			$aParams = $aParams + $aContextData; // merge keeping associations

			$oDummyObj->DisplayModifyForm($oP, $aParams);
			$oP->add("</div>\n");
			$oP->add_ready_script($sReadyScript);
			$oP->add_ready_script(
				<<<EOF
$('.wizContainer button.cancel').unbind('click');
$('.wizContainer button.cancel').click( function() { window.location.href = '$sCancelUrl'; } );
EOF
			);

		} // Else no object selected ???
		else
		{
			$oP->p("No object selected !, nothing to do");
		}
	}

	/**
	 * Process the reply made from a form built with DisplayBulkModifyForm
	 *
	 * @param \WebPage $oP
	 * @param string $sClass
	 * @param array $aSelectedObj
	 * @param string $sCustomOperation
	 * @param bool $bPreview
	 * @param string $sCancelUrl
	 * @param array $aContextData
	 *
	 * @throws \ArchivedObjectException
	 * @throws \CoreCannotSaveObjectException
	 * @throws \CoreException
	 * @throws \DictExceptionMissingString
	 * @throws \OQLException
	 */
	public static function DoBulkModify($oP, $sClass, $aSelectedObj, $sCustomOperation, $bPreview, $sCancelUrl, $aContextData = array())
	{
		$aHeaders = array(
			'form::select' => array(
				'label' => "<input type=\"checkbox\" onClick=\"CheckAll('.selectList:not(:disabled)', this.checked);\"></input>",
				'description' => Dict::S('UI:SelectAllToggle+'),
			),
			'object' => array('label' => MetaModel::GetName($sClass), 'description' => Dict::S('UI:ModifiedObject')),
			'status' => array(
				'label' => Dict::S('UI:BulkModifyStatus'),
				'description' => Dict::S('UI:BulkModifyStatus+'),
			),
			'errors' => array(
				'label' => Dict::S('UI:BulkModifyErrors'),
				'description' => Dict::S('UI:BulkModifyErrors+'),
			),
		);
		$aRows = array();

		$oP->add("<div class=\"page_header\">\n");
		$oP->add("<h1>".MetaModel::GetClassIcon($sClass)."&nbsp;".Dict::Format('UI:Modify_N_ObjectsOf_Class',
				count($aSelectedObj), MetaModel::GetName($sClass))."</h1>\n");
		$oP->add("</div>\n");
		$oP->set_title(Dict::Format('UI:Modify_N_ObjectsOf_Class', count($aSelectedObj), $sClass));
		if (!$bPreview)
		{
			// Not in preview mode, do the update for real
			$sTransactionId = utils::ReadPostedParam('transaction_id', '', 'transaction_id');
			if (!utils::IsTransactionValid($sTransactionId, false))
			{
				throw new Exception(Dict::S('UI:Error:ObjectAlreadyUpdated'));
			}
			utils::RemoveTransaction($sTransactionId);
		}
		$iPreviousTimeLimit = ini_get('max_execution_time');
		$iLoopTimeLimit = MetaModel::GetConfig()->Get('max_execution_time_per_loop');
		foreach($aSelectedObj as $iId)
		{
			set_time_limit($iLoopTimeLimit);
			/** @var \cmdbAbstractObject $oObj */
			$oObj = MetaModel::GetObject($sClass, $iId);
			$aErrors = $oObj->UpdateObjectFromPostedForm('');
			$bResult = (count($aErrors) == 0);
			if ($bResult)
			{
				list($bResult, $aErrors) = $oObj->CheckToWrite();
			}
			if ($bPreview)
			{
				$sStatus = $bResult ? Dict::S('UI:BulkModifyStatusOk') : Dict::S('UI:BulkModifyStatusError');
			}
			else
			{
				$sStatus = $bResult ? Dict::S('UI:BulkModifyStatusModified') : Dict::S('UI:BulkModifyStatusSkipped');
			}
			$sCSSClass = $bResult ? HILIGHT_CLASS_NONE : HILIGHT_CLASS_CRITICAL;
			$sChecked = $bResult ? 'checked' : '';
			$sDisabled = $bResult ? '' : 'disabled';
			$aRows[] = array(
				'form::select' => "<input type=\"checkbox\" class=\"selectList\" $sChecked $sDisabled\"></input>",
				'object' => $oObj->GetHyperlink(),
				'status' => $sStatus,
				'errors' => '<p>'.($bResult ? '' : implode('</p><p>', $aErrors)).'</p>',
				'@class' => $sCSSClass,
			);
			if ($bResult && (!$bPreview))
			{
				$oObj->DBUpdate();
			}
		}
		set_time_limit($iPreviousTimeLimit);
		$oP->Table($aHeaders, $aRows);
		if ($bPreview)
		{
			$sFormAction = utils::GetAbsoluteUrlAppRoot().'pages/UI.php'; // No parameter in the URL, the only parameter will be the ones passed through the form
			// Form to submit:
			$oP->add("<form method=\"post\" action=\"$sFormAction\" enctype=\"multipart/form-data\">\n");
			$aDefaults = utils::ReadParam('default', array());
			$oAppContext = new ApplicationContext();
			$oP->add($oAppContext->GetForForm());
			foreach($aContextData as $sKey => $value)
			{
				$oP->add("<input type=\"hidden\" name=\"{$sKey}\" value=\"$value\">\n");
			}
			$oP->add("<input type=\"hidden\" name=\"operation\" value=\"$sCustomOperation\">\n");
			$oP->add("<input type=\"hidden\" name=\"class\" value=\"$sClass\">\n");
			$oP->add("<input type=\"hidden\" name=\"preview_mode\" value=\"0\">\n");
			$oP->add("<input type=\"hidden\" name=\"transaction_id\" value=\"".utils::GetNewTransactionId()."\">\n");
			$oP->add("<button type=\"button\" class=\"action cancel\" onClick=\"window.location.href='$sCancelUrl'\">".Dict::S('UI:Button:Cancel')."</button>&nbsp;&nbsp;&nbsp;&nbsp;\n");
			$oP->add("<button type=\"submit\" class=\"action\"><span>".Dict::S('UI:Button:ModifyAll')."</span></button>\n");
			foreach($_POST as $sKey => $value)
			{
				if (preg_match('/attr_(.+)/', $sKey, $aMatches))
				{
					// Beware: some values (like durations) are passed as arrays
					if (is_array($value))
					{
						foreach($value as $vKey => $vValue)
						{
							$oP->add("<input type=\"hidden\" name=\"{$sKey}[$vKey]\" value=\"".htmlentities($vValue,
									ENT_QUOTES, 'UTF-8')."\">\n");
						}
					}
					else
					{
						$oP->add("<input type=\"hidden\" name=\"$sKey\" value=\"".htmlentities($value, ENT_QUOTES,
								'UTF-8')."\">\n");
					}
				}
			}
			$oP->add("</form>\n");
		}
		else
		{
			$oP->add("<button type=\"button\" onClick=\"window.location.href='$sCancelUrl'\" class=\"action\"><span>".Dict::S('UI:Button:Done')."</span></button>\n");
		}
	}

	/**
	 * Perform all the needed checks to delete one (or more) objects
	 *
	 * @param \WebPage $oP
	 * @param $sClass
	 * @param \DBObject[] $aObjects
	 * @param $bPreview
	 * @param $sCustomOperation
	 * @param array $aContextData
	 *
	 * @throws \CoreException
	 * @throws \DictExceptionMissingString
	 * @throws \Exception
	 */
	public static function DeleteObjects(WebPage $oP, $sClass, $aObjects, $bPreview, $sCustomOperation, $aContextData = array())
	{
		$oDeletionPlan = new DeletionPlan();

		foreach($aObjects as $oObj)
		{
			if ($bPreview)
			{
				$oObj->CheckToDelete($oDeletionPlan);
			}
			else
			{
				$oObj->DBDelete($oDeletionPlan);
			}
		}

		if ($bPreview)
		{
			if (count($aObjects) == 1)
			{
				$oObj = $aObjects[0];
				$oP->add("<h1>".Dict::Format('UI:Delete:ConfirmDeletionOf_Name', $oObj->GetName())."</h1>\n");
			}
			else
			{
				$oP->add("<h1>".Dict::Format('UI:Delete:ConfirmDeletionOf_Count_ObjectsOf_Class', count($aObjects),
						MetaModel::GetName($sClass))."</h1>\n");
			}
			// Explain what should be done
			//
			$aDisplayData = array();
			foreach($oDeletionPlan->ListDeletes() as $sTargetClass => $aDeletes)
			{
				foreach($aDeletes as $iId => $aData)
				{
					$oToDelete = $aData['to_delete'];
					$bAutoDel = (($aData['mode'] == DEL_SILENT) || ($aData['mode'] == DEL_AUTO));
					if (array_key_exists('issue', $aData))
					{
						if ($bAutoDel)
						{
							if (isset($aData['requested_explicitely']))
							{
								$sConsequence = Dict::Format('UI:Delete:CannotDeleteBecause', $aData['issue']);
							}
							else
							{
								$sConsequence = Dict::Format('UI:Delete:ShouldBeDeletedAtomaticallyButNotPossible',
									$aData['issue']);
							}
						}
						else
						{
							$sConsequence = Dict::Format('UI:Delete:MustBeDeletedManuallyButNotPossible',
								$aData['issue']);
						}
					}
					else
					{
						if ($bAutoDel)
						{
							if (isset($aData['requested_explicitely']))
							{
								$sConsequence = ''; // not applicable
							}
							else
							{
								$sConsequence = Dict::S('UI:Delete:WillBeDeletedAutomatically');
							}
						}
						else
						{
							$sConsequence = Dict::S('UI:Delete:MustBeDeletedManually');
						}
					}
					$aDisplayData[] = array(
						'class' => MetaModel::GetName(get_class($oToDelete)),
						'object' => $oToDelete->GetHyperLink(),
						'consequence' => $sConsequence,
					);
				}
			}
			foreach($oDeletionPlan->ListUpdates() as $sRemoteClass => $aToUpdate)
			{
				foreach($aToUpdate as $iId => $aData)
				{
					$oToUpdate = $aData['to_reset'];
					if (array_key_exists('issue', $aData))
					{
						$sConsequence = Dict::Format('UI:Delete:CannotUpdateBecause_Issue', $aData['issue']);
					}
					else
					{
						$sConsequence = Dict::Format('UI:Delete:WillAutomaticallyUpdate_Fields',
							$aData['attributes_list']);
					}
					$aDisplayData[] = array(
						'class' => MetaModel::GetName(get_class($oToUpdate)),
						'object' => $oToUpdate->GetHyperLink(),
						'consequence' => $sConsequence,
					);
				}
			}

			$iImpactedIndirectly = $oDeletionPlan->GetTargetCount() - count($aObjects);
			if ($iImpactedIndirectly > 0)
			{
				if (count($aObjects) == 1)
				{
					$oObj = $aObjects[0];
					$oP->p(Dict::Format('UI:Delete:Count_Objects/LinksReferencing_Object', $iImpactedIndirectly,
						$oObj->GetName()));
				}
				else
				{
					$oP->p(Dict::Format('UI:Delete:Count_Objects/LinksReferencingTheObjects', $iImpactedIndirectly));
				}
				$oP->p(Dict::S('UI:Delete:ReferencesMustBeDeletedToEnsureIntegrity'));
			}

			if (($iImpactedIndirectly > 0) || $oDeletionPlan->FoundStopper())
			{
				$aDisplayConfig = array();
				$aDisplayConfig['class'] = array('label' => 'Class', 'description' => '');
				$aDisplayConfig['object'] = array('label' => 'Object', 'description' => '');
				$aDisplayConfig['consequence'] = array(
					'label' => 'Consequence',
					'description' => Dict::S('UI:Delete:Consequence+'),
				);
				$oP->table($aDisplayConfig, $aDisplayData);
			}

			if ($oDeletionPlan->FoundStopper())
			{
				if ($oDeletionPlan->FoundSecurityIssue())
				{
					$oP->p(Dict::S('UI:Delete:SorryDeletionNotAllowed'));
				}
				elseif ($oDeletionPlan->FoundManualOperation())
				{
					$oP->p(Dict::S('UI:Delete:PleaseDoTheManualOperations'));
				}
				else // $bFoundManualOp
				{
					$oP->p(Dict::S('UI:Delete:PleaseDoTheManualOperations'));
				}
				$oAppContext = new ApplicationContext();
				$oP->add("<form method=\"post\">\n");
				$oP->add("<input type=\"hidden\" name=\"transaction_id\" value=\"".utils::ReadParam('transaction_id', '', false,
						'transaction_id')
					."\">\n");
				$oP->add("<input type=\"button\" onclick=\"window.history.back();\" value=\"".Dict::S('UI:Button:Back')."\">\n");
				$oP->add("<input DISABLED type=\"submit\" name=\"\" value=\"".Dict::S('UI:Button:Delete')."\">\n");
				$oP->add($oAppContext->GetForForm());
				$oP->add("</form>\n");
			}
			else
			{
				if (count($aObjects) == 1)
				{
					$oObj = $aObjects[0];
					$id = $oObj->GetKey();
					$oP->p('<h1>'.Dict::Format('UI:Delect:Confirm_Object', $oObj->GetHyperLink()).'</h1>');
				}
				else
				{
					$oP->p('<h1>'.Dict::Format('UI:Delect:Confirm_Count_ObjectsOf_Class', count($aObjects),
							MetaModel::GetName($sClass)).'</h1>');
				}
				foreach($aObjects as $oObj)
				{
					$aKeys[] = $oObj->GetKey();
				}
				$oFilter = new DBObjectSearch($sClass);
				$oFilter->AddCondition('id', $aKeys, 'IN');
				$oSet = new CMDBobjectSet($oFilter);
				$oP->add('<div id="0">');
				CMDBAbstractObject::DisplaySet($oP, $oSet, array('display_limit' => false, 'menu' => false));
				$oP->add("</div>\n");
				$oP->add("<form method=\"post\">\n");
				foreach($aContextData as $sKey => $value)
				{
					$oP->add("<input type=\"hidden\" name=\"{$sKey}\" value=\"$value\">\n");
				}
				$oP->add("<input type=\"hidden\" name=\"transaction_id\" value=\"".utils::GetNewTransactionId()."\">\n");
				$oP->add("<input type=\"hidden\" name=\"operation\" value=\"$sCustomOperation\">\n");
				$oP->add("<input type=\"hidden\" name=\"filter\" value=\"".htmlentities($oFilter->Serialize(), ENT_QUOTES,
						'UTF-8')."\">\n");
				$oP->add("<input type=\"hidden\" name=\"class\" value=\"$sClass\">\n");
				foreach($aObjects as $oObj)
				{
					$oP->add("<input type=\"hidden\" name=\"selectObject[]\" value=\"".$oObj->GetKey()."\">\n");
				}
				$oP->add("<input type=\"button\" onclick=\"window.history.back();\" value=\"".Dict::S('UI:Button:Back')."\">\n");
				$oP->add("<input type=\"submit\" name=\"\" value=\"".Dict::S('UI:Button:Delete')."\">\n");
				$oAppContext = new ApplicationContext();
				$oP->add($oAppContext->GetForForm());
				$oP->add("</form>\n");
			}
		}
		else // if ($bPreview)...
		{
			// Execute the deletion
			//
			if (count($aObjects) == 1)
			{
				$oObj = $aObjects[0];
				$oP->add("<h1>".Dict::Format('UI:Title:DeletionOf_Object', $oObj->GetName())."</h1>\n");
			}
			else
			{
				$oP->add("<h1>".Dict::Format('UI:Title:BulkDeletionOf_Count_ObjectsOf_Class', count($aObjects),
						MetaModel::GetName($sClass))."</h1>\n");
			}
			// Security - do not allow the user to force a forbidden delete by the mean of page arguments...
			if ($oDeletionPlan->FoundSecurityIssue())
			{
				throw new CoreException(Dict::S('UI:Error:NotEnoughRightsToDelete'));
			}
			if ($oDeletionPlan->FoundManualOperation())
			{
				throw new CoreException(Dict::S('UI:Error:CannotDeleteBecauseManualOpNeeded'));
			}
			if ($oDeletionPlan->FoundManualDelete())
			{
				throw new CoreException(Dict::S('UI:Error:CannotDeleteBecauseOfDepencies'));
			}

			// Report deletions
			//
			$aDisplayData = array();
			foreach($oDeletionPlan->ListDeletes() as $sTargetClass => $aDeletes)
			{
				foreach($aDeletes as $iId => $aData)
				{
					$oToDelete = $aData['to_delete'];

					if (isset($aData['requested_explicitely']))
					{
						$sMessage = Dict::S('UI:Delete:Deleted');
					}
					else
					{
						$sMessage = Dict::S('UI:Delete:AutomaticallyDeleted');
					}
					$aDisplayData[] = array(
						'class' => MetaModel::GetName(get_class($oToDelete)),
						'object' => $oToDelete->GetName(),
						'consequence' => $sMessage,
					);
				}
			}

			// Report updates
			//
			foreach($oDeletionPlan->ListUpdates() as $sTargetClass => $aToUpdate)
			{
				foreach($aToUpdate as $iId => $aData)
				{
					$oToUpdate = $aData['to_reset'];
					$aDisplayData[] = array(
						'class' => MetaModel::GetName(get_class($oToUpdate)),
						'object' => $oToUpdate->GetHyperLink(),
						'consequence' => Dict::Format('UI:Delete:AutomaticResetOf_Fields', $aData['attributes_list']),
					);
				}
			}

			// Report automatic jobs
			//
			if ($oDeletionPlan->GetTargetCount() > 0)
			{
				if (count($aObjects) == 1)
				{
					$oObj = $aObjects[0];
					$oP->p(Dict::Format('UI:Delete:CleaningUpRefencesTo_Object', $oObj->GetName()));
				}
				else
				{
					$oP->p(Dict::Format('UI:Delete:CleaningUpRefencesTo_Several_ObjectsOf_Class', count($aObjects),
						MetaModel::GetName($sClass)));
				}
				$aDisplayConfig = array();
				$aDisplayConfig['class'] = array('label' => 'Class', 'description' => '');
				$aDisplayConfig['object'] = array('label' => 'Object', 'description' => '');
				$aDisplayConfig['consequence'] = array('label' => 'Done', 'description' => Dict::S('UI:Delete:Done+'));
				$oP->table($aDisplayConfig, $aDisplayData);
			}
		}
	}

	/**
	 * Find redundancy settings that can be viewed and modified in a tab
	 * Settings are distributed to the corresponding link set attribute so as to be shown in the relevant tab
	 *
	 * @throws \Exception
	 */
	protected function FindVisibleRedundancySettings()
	{
		$aRet = array();
		foreach(MetaModel::ListAttributeDefs(get_class($this)) as $sAttCode => $oAttDef)
		{
			if ($oAttDef instanceof AttributeRedundancySettings)
			{
				if ($oAttDef->IsVisible())
				{
					$aQueryInfo = $oAttDef->GetRelationQueryData();
					if (isset($aQueryInfo['sAttribute']))
					{
						$oUpperAttDef = MetaModel::GetAttributeDef($aQueryInfo['sFromClass'],
							$aQueryInfo['sAttribute']);
						$oHostAttDef = $oUpperAttDef->GetMirrorLinkAttribute();
						if ($oHostAttDef)
						{
							$sHostAttCode = $oHostAttDef->GetCode();
							$aRet[$sHostAttCode][] = $oAttDef;
						}
					}
				}
			}
		}

		return $aRet;
	}

	/**
	 * Generates the javascript code handle the "watchdog" associated with the concurrent access locking mechanism
	 *
	 * @param Webpage $oPage
	 * @param string $sOwnershipToken
	 */
	protected function GetOwnershipJSHandler($oPage, $sOwnershipToken)
	{
		$iInterval = max(MIN_WATCHDOG_INTERVAL,
				MetaModel::GetConfig()->Get('concurrent_lock_expiration_delay')) * 1000 / 2; // Minimum interval for the watchdog is MIN_WATCHDOG_INTERVAL
		$sJSClass = json_encode(get_class($this));
		$iKey = (int)$this->GetKey();
		$sJSToken = json_encode($sOwnershipToken);
		$sJSTitle = json_encode(Dict::S('UI:DisconnectedDlgTitle'));
		$sJSOk = json_encode(Dict::S('UI:Button:Ok'));
		$oPage->add_ready_script(
			<<<EOF
		window.setInterval(function() {
			if (window.bInSubmit || window.bInCancel) return;
			
			$.post(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php', {operation: 'extend_lock', obj_class: $sJSClass, obj_key: $iKey, token: $sJSToken }, function(data) {
				if (!data.status)
				{
					if ($('.lock_owned').length == 0)
					{
						$('.ui-layout-content').prepend('<div class="header_message message_error lock_owned">'+data.message+'</div>');
						$('<div>'+data.popup_message+'</div>').dialog({title: $sJSTitle, modal: true, autoOpen: true, buttons:[ {text: $sJSOk, click: function() { $(this).dialog('close'); } }], close: function() { $(this).remove(); }});
					}
					$('.wizContainer form button.action:not(.cancel)').prop('disabled', true);
				}
				else if ((data.operation == 'lost') || (data.operation == 'expired'))
				{
					if ($('.lock_owned').length == 0)
					{
						$('.ui-layout-content').prepend('<div class="header_message message_error lock_owned">'+data.message+'</div>');
						$('<div>'+data.popup_message+'</div>').dialog({title: $sJSTitle, modal: true, autoOpen: true, buttons:[ {text: $sJSOk, click: function() { $(this).dialog('close'); } }], close: function() { $(this).remove(); }});
					}
					$('.wizContainer form button.action:not(.cancel)').prop('disabled', true);
				}
			}, 'json');
		}, $iInterval);
EOF
		);
	}

	/**
	 * Return an array of AttributeDefinition EditClass that should be rendered as large field in the UI
	 *
	 * @return array
	 * @since 2.7.0
	 */
	protected static function GetAttEditClassesToRenderAsLargeField(){
		return array(
			'CaseLog',
			'CustomFields',
			'HTML',
			'OQLExpression',
			'Text',
		);
	}

	/**
	 * Return an array of AttributeDefinition classes that should be excluded from the markup metadata when priting raw value (typically large values)
	 * This markup is mostly aimed at CSS/JS hooks for extensions and Behat tests
	 *
	 * @return array
	 * @since 2.7.0
	 *
	 * @internal Do NOT use, this is experimental and most likely to be moved elsewhere when we find its rightful place.
	 */
	public static function GetAttDefClassesToExcludeFromMarkupMetadataRawValue(){
		return array(
			'AttributeBlob',
			'AttributeCustomFields',
			'AttributeDashboard',
			'AttributeLinkedSet',
			'AttributeStopWatch',
			'AttributeSubItem',
			'AttributeTable',
			'AttributeText',
			'AttributePassword',
			'AttributeOneWayPassword',
		);
	}
}

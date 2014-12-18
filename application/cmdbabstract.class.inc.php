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
 * Abstract class that implements some common and useful methods for displaying
 * the objects
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

define('OBJECT_PROPERTIES_TAB', 'ObjectProperties');

define('HILIGHT_CLASS_CRITICAL', 'red');
define('HILIGHT_CLASS_WARNING', 'orange');
define('HILIGHT_CLASS_OK', 'green');
define('HILIGHT_CLASS_NONE', '');

require_once(APPROOT.'/core/cmdbobject.class.inc.php');
require_once(APPROOT.'/application/applicationextension.inc.php');
require_once(APPROOT.'/application/utils.inc.php');
require_once(APPROOT.'/application/applicationcontext.class.inc.php');
require_once(APPROOT.'/application/ui.linkswidget.class.inc.php');
require_once(APPROOT.'/application/ui.linksdirectwidget.class.inc.php');
require_once(APPROOT.'/application/ui.passwordwidget.class.inc.php');
require_once(APPROOT.'/application/ui.extkeywidget.class.inc.php');
require_once(APPROOT.'/application/ui.htmleditorwidget.class.inc.php');
require_once(APPROOT.'/application/datatable.class.inc.php');

abstract class cmdbAbstractObject extends CMDBObject implements iDisplay
{
	protected $m_iFormId; // The ID of the form used to edit the object (when in edition mode !)
	static $iGlobalFormId = 1;
	protected $aFieldsMap;

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
	 * Set a message diplayed to the end-user next time this object will be displayed
	 * Messages are uniquely identified so that plugins can override standard messages (the final work is given to the last plugin to set the message for a given message id)
	 * In practice, standard messages are recorded at the end but they will not overwrite existing messages	 
	 * 	 
	 * @param string $sClass The class of the object (must be the final class)
	 * @param int $iKey The identifier of the object
	 * @param string $sMessageId Your id or one of the well-known ids: 'create', 'update' and 'apply_stimulus'
	 * @param string $sMessage The HTML message (must be correctly escaped)
	 * @param string $sSeverity Any of the following: ok, info, error.
	 * @param float $fRank Ordering of the message: smallest displayed first (can be negative)
	 * @param bool $bMustNotExist Do not alter any existing message (considering the id)	 	 
	 *
	 */
	public static function SetSessionMessage($sClass, $iKey, $sMessageId, $sMessage, $sSeverity, $fRank, $bMustNotExist = false)
	{
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
				'message' => $sMessage
			);
		}
	}	 	 	 	

	function DisplayBareHeader(WebPage $oPage, $bEditMode = false)
	{
		// Standard Header with name, actions menu and history block
		//
		
		// Is there a message for this object ??
		$sMessageKey = get_class($this).'::'.$this->GetKey();
		if (array_key_exists('obj_messages', $_SESSION) && array_key_exists($sMessageKey, $_SESSION['obj_messages']))
		{
			$aMessages = array();
			$aRanks = array();
			foreach ($_SESSION['obj_messages'][$sMessageKey] as $sMessageId => $aMessageData)
			{
				$sMsgClass = 'message_'.$aMessageData['severity'];
				$aMessages[] = "<div class=\"header_message $sMsgClass\">".$aMessageData['message']."</div>";
				$aRanks[] = $aMessageData['rank'];
			}
			array_multisort($aRanks, $aMessages);
			foreach ($aMessages as $sMessage)
			{
				$oPage->add($sMessage);
			}
			unset($_SESSION['obj_messages'][$sMessageKey]);
		}
		
		// action menu
		$oSingletonFilter = new DBObjectSearch(get_class($this));
		$oSingletonFilter->AddCondition('id', $this->GetKey(), '=');
		$oBlock = new MenuBlock($oSingletonFilter, 'details', false);
		$oBlock->Display($oPage, -1);
	
		// Master data sources
		$sSynchroIcon = '';
		$bSynchronized = false;
		$oCreatorTask = null;
		$bCanBeDeletedByTask = false;
		$bCanBeDeletedByUser = true;
		$aMasterSources = array();
		$aSyncData = $this->GetSynchroData();
		if (count($aSyncData) > 0)
		{
			$bSynchronized = true;
			foreach ($aSyncData as $iSourceId => $aSourceData)
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
					else // everybody...
					{
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
					$sTip = "<p>".Dict::Format('Core:Synchro:TheObjectCannotBeDeletedByUser_Source', $sTaskUrl)."</p>";
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
				$oDataSource = $aStruct['datasource'];
				$sLink = $aStruct['url'];
				$sTip .= "<p style=\"white-space:nowrap\">".$oDataSource->GetIcon(true, 'style="vertical-align:middle"')."&nbsp;$sLink<br/>";
				$sTip .= Dict::S('Core:Synchro:LastSynchro').'<br/>'.$aStruct['last_synchro']."</p>";
			}
			$sSynchroIcon = '&nbsp;<img style="vertical-align:middle;" id="synchro_icon" src="../images/locked.png"/>';
			$sTip = addslashes($sTip);
			$oPage->add_ready_script("$('#synchro_icon').qtip( { content: '$sTip', show: 'mouseover', hide: { fixed: true }, style: { name: 'dark', tip: 'leftTop' }, position: { corner: { target: 'rightMiddle', tooltip: 'leftTop' }} } );");
		}
	
		$oPage->add("<div class=\"page_header\"><h1>".$this->GetIcon()."&nbsp;\n");
		$sRefreshIcon = '';
		if ($_SERVER['REQUEST_METHOD'] == 'GET')
		{
			$sRefreshIcon = '<img src="../images/reload.png" style="cursor:pointer;vertical-align:middle;margin-left:1em;" onclick="window.location.reload();" title="'.htmlentities(Dict::S('UI:Button:Refresh'), ENT_QUOTES, 'UTF-8').'"/>';
		}
		$oPage->add(MetaModel::GetName(get_class($this)).": <span class=\"hilite\">".$this->GetName()."</span>$sRefreshIcon $sSynchroIcon</h1>\n");
		$oPage->add("</div>\n");
		
	}

	function DisplayBareHistory(WebPage $oPage, $bEditMode = false, $iLimitCount = 0, $iLimitStart = 0)
	{
		// history block (with as a tab)
		$oHistoryFilter = new DBObjectSearch('CMDBChangeOp');
		$oHistoryFilter->AddCondition('objkey', $this->GetKey(), '=');
		$oHistoryFilter->AddCondition('objclass', get_class($this), '=');
		$oBlock = new HistoryBlock($oHistoryFilter, 'table', false);
		$oBlock->SetLimit($iLimitCount, $iLimitStart);
		$oBlock->Display($oPage, 'history');
	}

	function DisplayBareProperties(WebPage $oPage, $bEditMode = false, $sPrefix = '', $aExtraParams = array())
	{
		$aFieldsMap = $this->GetBareProperties($oPage, $bEditMode, $sPrefix, $aExtraParams);		


		if (!isset($aExtraParams['disable_plugins']) || !$aExtraParams['disable_plugins'])
		{
			foreach (MetaModel::EnumPlugins('iApplicationUIExtension') as $oExtensionInstance)
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
	 * @param string $sAttCode The attribute code of the field being edited
	 * @param string $sInputId The unique ID of the control/widget in the page
	 */
	protected function AddToFieldsMap($sAttCode, $sInputId)
	{
		$this->aFieldsMap[$sAttCode] = $sInputId;
	}

	function DisplayBareRelations(WebPage $oPage, $bEditMode = false)
	{
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
			// Display mode
			if (!$oAttDef->IsLinkset()) continue; // Process only linkset attributes...
			
			// $oSet = new DBObjectSet($this->Get($sAttCode)->GetFilter()); // Why do something so useless ?
			$oSet = $this->Get($sAttCode);
			$iCount = $oSet->Count();
			$sCount = '';
			if ($iCount != 0)
			{
				$sCount = " ($iCount)";
			}
			$oPage->SetCurrentTab($oAttDef->GetLabel().$sCount);
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
				$sLinkedClass = $oAttDef->GetLinkedClass();
				$oLinkingAttDef = 	MetaModel::GetAttributeDef($sLinkedClass, $oAttDef->GetExtKeyToRemote());
				$sTargetClass = $oLinkingAttDef->GetTargetClass();
				// n:n links => must be allowed to modify the linking class AND  read the target class in order to edit the linkedset
				if (!UserRights::IsActionAllowed($sLinkedClass, UR_ACTION_MODIFY) || !UserRights::IsActionAllowed($sTargetClass, UR_ACTION_READ))
				{
					$iFlags |= OPT_ATT_READONLY;
				}
				// n:n links => must be allowed to read the linking class AND  the target class in order to display the linkedset
				if (!UserRights::IsActionAllowed($sLinkedClass, UR_ACTION_READ) || !UserRights::IsActionAllowed($sTargetClass, UR_ACTION_READ))
				{
					$iFlags |= OPT_ATT_HIDDEN;
				}
			}
			else
			{
				// 1:n links => must be allowed to modify the linked class in order to edit the linkedset
				if (!UserRights::IsActionAllowed($oAttDef->GetLinkedClass(), UR_ACTION_MODIFY))
				{
					$iFlags |= OPT_ATT_READONLY;
				}
				// 1:n links => must be allowed to read the linked class in order to display the linkedset
				if (!UserRights::IsActionAllowed($oAttDef->GetLinkedClass(), UR_ACTION_READ))
				{
					$iFlags |= OPT_ATT_HIDDEN;
				}
			}
			// Non-readable/hidden linkedset... don't display anything
			if ($iFlags & OPT_ATT_HIDDEN) continue;
			
			$bReadOnly = ($iFlags & (OPT_ATT_READONLY|OPT_ATT_SLAVE));
			if ($bEditMode && (!$bReadOnly))
			{
				$sInputId = $this->m_iFormId.'_'.$sAttCode;

				$sLinkedClass = $oAttDef->GetLinkedClass();
				if ($oAttDef->IsIndirect())
				{
					$oLinkingAttDef = 	MetaModel::GetAttributeDef($sLinkedClass, $oAttDef->GetExtKeyToRemote());
					$sTargetClass = $oLinkingAttDef->GetTargetClass();
				}
				else
				{
					$sTargetClass = $sLinkedClass;
				}
				$oPage->p(MetaModel::GetClassIcon($sTargetClass)."&nbsp;".$oAttDef->GetDescription().'<span id="busy_'.$sInputId.'"></span>');

				$oValue = $this->Get($sAttCode);
				$sDisplayValue = ''; // not used
				$aArgs = array('this' => $this);
				$sHTMLValue = "<span id=\"field_{$sInputId}\">".self::GetFormElementForField($oPage, $sClass, $sAttCode, $oAttDef, $oValue, $sDisplayValue, $sInputId, '', $iFlags, $aArgs).'</span>';
				$this->AddToFieldsMap($sAttCode,  $sInputId);
				$oPage->add($sHTMLValue);
			}
			else
			{
				// Display mode
				if (!$oAttDef->IsIndirect())
				{
					// 1:n links
					$sTargetClass = $oAttDef->GetLinkedClass();

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
						'menu' => false,
						'default' => $aDefaults,
						'table_id' => $sClass.'_'.$sAttCode,
					);
				}
				else
				{
					// n:n links
					$sLinkedClass = $oAttDef->GetLinkedClass();
					$oLinkingAttDef = 	MetaModel::GetAttributeDef($sLinkedClass, $oAttDef->GetExtKeyToRemote());
					$sTargetClass = $oLinkingAttDef->GetTargetClass();
					$bMenu = ($this->Get($sAttCode)->Count() > 0); // The menu is enabled only if there are already some elements...
					$aParams = array(
							'link_attr' => $oAttDef->GetExtKeyToMe(),
							'object_id' => $this->GetKey(),
							'target_attr' => $oAttDef->GetExtKeyToRemote(),
							'view_link' => false,
							'menu' => false,
							'display_limit' => true, // By default limit the list to speed up the initial load & display
							'table_id' => $sClass.'_'.$sAttCode,
						);
				}
				$oPage->p(MetaModel::GetClassIcon($sTargetClass)."&nbsp;".$oAttDef->GetDescription());
				$oBlock = new DisplayBlock($this->Get($sAttCode)->GetFilter(), 'list', false);
				$oBlock->Display($oPage, 'rel_'.$sAttCode, $aParams);
			}
		}
		$oPage->SetCurrentTab('');

		foreach (MetaModel::EnumPlugins('iApplicationUIExtension') as $oExtensionInstance)
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
			while($oTrigger = $oTriggerSet->Fetch())
			{
				if($oTrigger->IsInScope($this))
				{
					$aTriggers[] = $oTrigger->GetKey();
				}
			}
			if (count($aTriggers) > 0)
			{
				$iId = $this->GetKey();
				$sTriggersList = implode(',', $aTriggers);
				$aNotifSearches = array();
				$iNotifsCount = 0;
				$aNotificationClasses = MetaModel::EnumChildClasses('EventNotification', ENUM_CHILD_CLASSES_EXCLUDETOP);
				foreach($aNotificationClasses as $sNotifClass)
				{
					$aNotifSearches[$sNotifClass] = DBObjectSearch::FromOQL("SELECT $sNotifClass AS Ev JOIN Trigger AS T ON Ev.trigger_id = T.id WHERE T.id IN ($sTriggersList) AND Ev.object_id = $iId");
					$oNotifSet = new DBObjectSet($aNotifSearches[$sNotifClass]);
					$iNotifsCount += $oNotifSet->Count();	
				}
				// Display notifications regarding the object: on block per subclass to have the intersting columns
				$sCount = ($iNotifsCount > 0) ? ' ('.$iNotifsCount.')' : '';
				$oPage->SetCurrentTab(Dict::S('UI:NotificationsTab').$sCount);
				
				foreach($aNotificationClasses as $sNotifClass)
				{
					
					$oPage->p(MetaModel::GetClassIcon($sNotifClass, true).'&nbsp;'.MetaModel::GetName($sNotifClass));
					$oBlock = new DisplayBlock($aNotifSearches[$sNotifClass], 'list', false);
					$oBlock->Display($oPage, 'notifications_'.$sNotifClass, array('menu' => false));
				}
			}
		}
	}

	function GetBareProperties(WebPage $oPage, $bEditMode = false, $sPrefix, $aExtraParams = array())
	{
		$sHtml = '';
		$oAppContext = new ApplicationContext();	
		$sStateAttCode = MetaModel::GetStateAttributeCode(get_class($this));
		$aDetails = array();
		$sClass = get_class($this);
		$aDetailsList = MetaModel::GetZListItems($sClass, 'details');
		$aDetailsStruct = self::ProcessZlist($aDetailsList, array('UI:PropertiesTab' => array()), 'UI:PropertiesTab', 'col1', '');
		// Compute the list of properties to display, first the attributes in the 'details' list, then 
		// all the remaining attributes that are not external fields
		$sHtml = '';
		$aDetails = array();
		$iInputId = 0;
		$aFieldsMap = array();
		$aFieldsComments = (isset($aExtraParams['fieldsComments'])) ? $aExtraParams['fieldsComments'] : array();
		$aExtraFlags = (isset($aExtraParams['fieldsFlags'])) ? $aExtraParams['fieldsFlags'] : array();
		$bFieldComments = (count($aFieldsComments) > 0);
		
		foreach($aDetailsStruct as $sTab => $aCols )
		{
			$aDetails[$sTab] = array();
			ksort($aCols);
			$oPage->SetCurrentTab(Dict::S($sTab));
			$oPage->add('<table style="vertical-align:top"><tr>');
			foreach($aCols as $sColIndex => $aFieldsets)
			{
				$oPage->add('<td style="vertical-align:top">');
				//$aDetails[$sTab][$sColIndex] = array();
				$sLabel = '';
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
						if ($bEditMode)
						{


						$sComments = isset($aFieldsComments[$sAttCode]) ? $aFieldsComments[$sAttCode] : '&nbsp;';
						$sInfos = '&nbsp;';
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
						if (array_key_exists($sAttCode, $aExtraFlags))
						{
							// the caller may override some flags if needed
							$iFlags = $iFlags | $aExtraFlags[$sAttCode];
						}
						$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
						if ( (!$oAttDef->IsLinkSet()) && (($iFlags & OPT_ATT_HIDDEN) == 0))
						{
							$sInputId = $this->m_iFormId.'_'.$sAttCode;
							if ($oAttDef->IsWritable())
							{
								if ($sStateAttCode == $sAttCode)
								{
									// State attribute is always read-only from the UI
									$sHTMLValue = $this->GetStateLabel();
									$val = array('label' => '<span>'.$oAttDef->GetLabel().'</span>', 'value' => $sHTMLValue, 'comments' => $sComments, 'infos' => $sInfos);
								}
								else
								{				
									if ($iFlags & OPT_ATT_HIDDEN)
									{
										// Attribute is hidden, add a hidden input
										$oPage->add('<input type="hidden" id="'.$sInputId.'" name="attr_'.$sPrefix.$sAttCode.'" value="'.htmlentities($this->Get($sAttCode), ENT_QUOTES, 'UTF-8').'"/>');
										$aFieldsMap[$sAttCode] = $sInputId;
									}
									else
									{
										if ($iFlags & (OPT_ATT_READONLY|OPT_ATT_SLAVE))
										{

											// Check if the attribute is not read-only because of a synchro...
											$aReasons = array();
											$sSynchroIcon = '';
											if ($iFlags & OPT_ATT_SLAVE)
											{
												$iSynchroFlags = $this->GetSynchroReplicaFlags($sAttCode, $aReasons);
												$sSynchroIcon = "&nbsp;<img id=\"synchro_$sInputId\" src=\"../images/transp-lock.png\" style=\"vertical-align:middle\"/>";
												$sTip = '';
												foreach($aReasons as $aRow)
												{
													$sTip .= "<p>Synchronized with {$aRow['name']} - {$aRow['description']}</p>";
												}
												$sTip = addslashes($sTip);
												$oPage->add_ready_script("$('#synchro_$sInputId').qtip( { content: '$sTip', show: 'mouseover', hide: 'mouseout', style: { name: 'dark', tip: 'leftTop' }, position: { corner: { target: 'rightMiddle', tooltip: 'leftTop' }} } );");
											}

											// Attribute is read-only
											$sHTMLValue = "<span id=\"field_{$sInputId}\">".$this->GetAsHTML($sAttCode);
											$sHTMLValue .= '<input type="hidden" id="'.$sInputId.'" name="attr_'.$sPrefix.$sAttCode.'" value="'.htmlentities($this->Get($sAttCode), ENT_QUOTES, 'UTF-8').'"/></span>';
											$aFieldsMap[$sAttCode] = $sInputId;
											$sComments = $sSynchroIcon;
										}
										else
										{
											$sValue = $this->Get($sAttCode);
											$sDisplayValue = $this->GetEditValue($sAttCode);
											$aArgs = array('this' => $this, 'formPrefix' => $sPrefix);
											$sHTMLValue = "<span id=\"field_{$sInputId}\">".self::GetFormElementForField($oPage, $sClass, $sAttCode, $oAttDef, $sValue, $sDisplayValue, $sInputId, '', $iFlags, $aArgs).'</span>';
											$aFieldsMap[$sAttCode] = $sInputId;
											
										}
										$val = array('label' => '<span title="'.$oAttDef->GetDescription().'">'.$oAttDef->GetLabel().'</span>', 'value' => $sHTMLValue, 'comments' => $sComments, 'infos' => $sInfos);
									}
								}
							}
							else
							{
								$val = array('label' => '<span title="'.$oAttDef->GetDescription().'">'.$oAttDef->GetLabel().'</span>', 'value' => "<span id=\"field_{$sInputId}\">".$this->GetAsHTML($sAttCode)."</span>", 'comments' => $sComments, 'infos' => $sInfos);
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

	
	function DisplayDetails(WebPage $oPage, $bEditMode = false)
	{
		$sTemplate = Utils::ReadFromFile(MetaModel::GetDisplayTemplate(get_class($this)));
		if (!empty($sTemplate))
		{
			$oTemplate = new DisplayTemplate($sTemplate);
			// Note: to preserve backward compatibility with home-made templates, the placeholder '$pkey$' has been preserved
			//       but the preferred method is to use '$id$'
			$oTemplate->Render($oPage, array('class_name'=> MetaModel::GetName(get_class($this)),'class'=> get_class($this), 'pkey'=> $this->GetKey(), 'id'=> $this->GetKey(), 'name' => $this->GetName()));
		}
		else
		{
			// Object's details
			// template not found display the object using the *old style*
			$this->DisplayBareHeader($oPage, $bEditMode);
			$oPage->AddTabContainer(OBJECT_PROPERTIES_TAB);
			$oPage->SetCurrentTabContainer(OBJECT_PROPERTIES_TAB);
			$oPage->SetCurrentTab(Dict::S('UI:PropertiesTab'));
			$this->DisplayBareProperties($oPage, $bEditMode);
			$this->DisplayBareRelations($oPage, $bEditMode);
			//$oPage->SetCurrentTab(Dict::S('UI:HistoryTab'));
			//$this->DisplayBareHistory($oPage, $bEditMode);
			$oPage->AddAjaxTab(Dict::S('UI:HistoryTab'), utils::GetAbsoluteUrlAppRoot().'pages/ajax.render.php?operation=history&class='.get_class($this).'&id='.$this->GetKey());
		}
	}
	
	function DisplayPreview(WebPage $oPage)
	{
		$aDetails = array();
		$sClass = get_class($this);
		$aList = MetaModel::GetZListItems($sClass, 'preview');
		foreach($aList as $sAttCode)
		{
			$aDetails[] = array('label' => MetaModel::GetLabel($sClass, $sAttCode), 'value' =>$this->GetAsHTML($sAttCode));
		}
		$oPage->details($aDetails);		
	}
	
	public static function DisplaySet(WebPage $oPage, CMDBObjectSet $oSet, $aExtraParams = array())
	{
		$oPage->add(self::GetDisplaySet($oPage, $oSet, $aExtraParams));
	}

	/**
	 * Get the HTML fragment corresponding to the display of a table representing a set of objects
	 * @param WebPage $oPage The page object is used for out-of-band information (mostly scripts) output
	 * @param CMDBObjectSet The set of objects to display
	 * @param Hash $aExtraParams Some extra configuration parameters to tweak the behavior of the display
	 * @return String The HTML fragment representing the table of objects
	 */	
	public static function GetDisplaySet(WebPage $oPage, CMDBObjectSet $oSet, $aExtraParams = array())
	{
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
			if($iLinkedObjectId == 0)
			{
				// if 'links' mode is requested the id of the object to link to must be specified
				throw new ApplicationException(Dict::S('UI:Error:MandatoryTemplateParameter_object_id'));
			}
			if($sTargetAttr == '')
			{
				// if 'links' mode is requested the d of the object to link to must be specified
				throw new ApplicationException(Dict::S('UI:Error:MandatoryTemplateParameter_target_attr'));
			}
		}
		$bDisplayMenu = isset($aExtraParams['menu']) ? $aExtraParams['menu'] == true : true;
		$bTruncated = isset($aExtraParams['truncated']) ? $aExtraParams['truncated'] == true : true;
		$bSelectMode = isset($aExtraParams['selection_mode']) ? $aExtraParams['selection_mode'] == true : false;
		$bSingleSelectMode = isset($aExtraParams['selection_type']) ? ($aExtraParams['selection_type'] == 'single') : false;

		$aExtraFieldsRaw = isset($aExtraParams['extra_fields']) ? explode(',', trim($aExtraParams['extra_fields'])) : array();
		$aExtraFields = array();
		foreach ($aExtraFieldsRaw as $sFieldName)
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
		$sHtml = '';
		$oAppContext = new ApplicationContext();
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
				if ( (!$oLinkAttDef->IsExternalKey()) && (!$oLinkAttDef->IsExternalField()) )
				{
					$aDisplayList[] = $sLinkAttCode;
				}
			}
			// Then display all the attributes neither specific to the link record nor to the 'linkage' object (because the latter are constant)
			foreach($aList as $sLinkAttCode)
			{
				$oLinkAttDef = $aAttDefs[$sLinkAttCode];
				if (($oLinkAttDef->IsExternalKey() && ($sLinkAttCode != $sLinkageAttribute))
					|| ($oLinkAttDef->IsExternalField() && ($oLinkAttDef->GetKeyAttCode()!=$sLinkageAttribute)) )
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
		$aClassAliases = array( $sClassAlias => $sClassName);
		$oDataTable = new DataTable($iListId, $oSet, $aClassAliases, $sTableId);
		$oSettings = DataTableSettings::GetDataModelSettings($aClassAliases, $bViewLink, array($sClassAlias => $aList));
		
		if ($bDisplayLimit)
		{
			$iDefaultPageSize = appUserPreferences::GetPref('default_page_size', MetaModel::GetConfig()->GetMinDisplayLimit());
			$oSettings->iDefaultPageSize = $iDefaultPageSize;
		}
		else
		{
			$oSettings->iDefaultPageSize = 0;
		}
		$oSettings->aSortOrder = MetaModel::GetOrderByDefault($sClassName);
		
		return $oDataTable->Display($oPage, $oSettings, $bDisplayMenu, $sSelectMode, $bViewLink, $aExtraParams);
	}
	
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
		$aDisplayAliases = isset($aExtraParams['display_aliases']) ? explode(',', $aExtraParams['display_aliases']) : array();
		$sZListName = isset($aExtraParams['zlist']) ? ($aExtraParams['zlist']) : 'list';

		$aExtraFieldsRaw = isset($aExtraParams['extra_fields']) ? explode(',', trim($aExtraParams['extra_fields'])) : array();
		$aExtraFields = array();
		foreach ($aExtraFieldsRaw as $sFieldName)
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

		$sHtml = '';
		$oAppContext = new ApplicationContext();
		$aClasses = $oSet->GetFilter()->GetSelectedClasses();
		$aAuthorizedClasses = array();
		foreach($aClasses as $sAlias => $sClassName)
		{
			if ( (UserRights::IsActionAllowed($sClassName, UR_ACTION_READ, $oSet) && (UR_ALLOWED_YES || UR_ALLOWED_DEPENDS)) &&
			( (count($aDisplayAliases) == 0) || (in_array($sAlias, $aDisplayAliases))) )
			{
				$aAuthorizedClasses[$sAlias] = $sClassName;
			}
		}
		$aAttribs = array();
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
				
		$sClassAlias = $oSet->GetClassAlias();
		$oDataTable = new DataTable($iListId, $oSet, $aAuthorizedClasses);

		$oSettings = DataTableSettings::GetDataModelSettings($aAuthorizedClasses, $bViewLink, $aList);
		
		$bDisplayLimit = isset($aExtraParams['display_limit']) ? $aExtraParams['display_limit'] : true;
		if ($bDisplayLimit)
		{
			$iDefaultPageSize = appUserPreferences::GetPref('default_page_size', MetaModel::GetConfig()->GetMinDisplayLimit());
			$oSettings->iDefaultPageSize = $iDefaultPageSize;
		}
		
		$oSettings->aSortOrder = MetaModel::GetOrderByDefault($sClassName);
		
		return $oDataTable->Display($oPage, $oSettings, $bDisplayMenu, $sSelectMode, $bViewLink, $aExtraParams);
	}
	
	static function DisplaySetAsCSV(WebPage $oPage, CMDBObjectSet $oSet, $aParams = array(), $sCharset = 'UTF-8')
	{
		$oPage->add(self::GetSetAsCSV($oSet, $aParams, $sCharset));
	}
	
	static function GetSetAsCSV(DBObjectSet $oSet, $aParams = array(), $sCharset = 'UTF-8')
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
			$bFieldsAdvanced = (bool) $aParams['fields_advanced'];
		}

		$bLocalize = true;
		if (isset($aParams['localize_values']))
		{
			$bLocalize = (bool) $aParams['localize_values'];
		}

		$aList = array();

		$oAppContext = new ApplicationContext();
		$aClasses = $oSet->GetFilter()->GetSelectedClasses();
		$aAuthorizedClasses = array();
		foreach($aClasses as $sAlias => $sClassName)
		{
			if (UserRights::IsActionAllowed($sClassName, UR_ACTION_READ, $oSet) && (UR_ALLOWED_YES || UR_ALLOWED_DEPENDS))
			{
				$aAuthorizedClasses[$sAlias] = $sClassName;
			}
		}
		$aAttribs = array();
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
										$aList[$sAlias][$sAttCode.'->'.$sRemoteAttCode] = MetaModel::GetAttributeDef($sRemoteClass, $sRemoteAttCode);
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
				$aHeader[] = $bLocalize ? MetaModel::GetLabel($sClassName, $sAttCodeEx, isset($aParams['showMandatoryFields'])) : $sAttCodeEx;
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
	
	static function DisplaySetAsHTMLSpreadsheet(WebPage $oPage, CMDBObjectSet $oSet, $aParams = array())
	{
		$oPage->add(self::GetSetAsHTMLSpreadsheet($oSet, $aParams));
	}
	
	/**
	 * Spreadsheet output: designed for end users doing some reporting
	 * Then the ids are excluded and replaced by the corresponding friendlyname
	 */	 	 	
	static function GetSetAsHTMLSpreadsheet(DBObjectSet $oSet, $aParams = array())
	{
		$aFields = null;
		if (isset($aParams['fields']) && (strlen($aParams['fields']) > 0))
		{
			$aFields = explode(',', $aParams['fields']);
		}

		$bFieldsAdvanced = false;
		if (isset($aParams['fields_advanced']))
		{
			$bFieldsAdvanced = (bool) $aParams['fields_advanced'];
		}

		$bLocalize = true;
		if (isset($aParams['localize_values']))
		{
			$bLocalize = (bool) $aParams['localize_values'];
		}

		$aList = array();

		$oAppContext = new ApplicationContext();
		$aClasses = $oSet->GetFilter()->GetSelectedClasses();
		$aAuthorizedClasses = array();
		foreach($aClasses as $sAlias => $sClassName)
		{
			if (UserRights::IsActionAllowed($sClassName, UR_ACTION_READ, $oSet) && (UR_ALLOWED_YES || UR_ALLOWED_DEPENDS))
			{
				$aAuthorizedClasses[$sAlias] = $sClassName;
			}
		}
		$aAttribs = array();
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
								$aList[$sAlias][$sAttCode.'->'.$sRemoteAttCode] = MetaModel::GetAttributeDef($sRemoteClass, $sRemoteAttCode);
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
					if (!array_key_exists($sFriendlyNameAttCode, $aList[$sAlias]) && MetaModel::IsValidAttCode($sClassName, $sFriendlyNameAttCode))
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
								$aRow[] = '<td>'.date('Y-m-d', $iDate).'</td>';
								$aRow[] = '<td>'.date('H:i:s', $iDate).'</td>';								
							}
						}
						else if($oAttDef instanceof AttributeCaseLog)
						{
							$rawValue = $oObj->Get($sAttCodeEx);
							$outputValue = str_replace("\n", "<br/>", htmlentities($rawValue->__toString(), ENT_QUOTES, 'UTF-8'));
							// Trick for Excel: treat the content as text even if it begins with an equal sign
							$aRow[] = '<td x:str>'.$outputValue.'</td>';
						}
						else
						{
							$rawValue = $oObj->Get($sAttCodeEx);
							// Due to custom formatting rules, empty friendlynames may be rendered as non-empty strings
							// let's fix this and make sure we render an empty string if the key == 0
							if ($oAttDef instanceof AttributeFriendlyName)
							{
								$sKeyAttCode = $oAttDef->GetKeyAttCode();
								if ($sKeyAttCode != 'id')
								{
									if ($oObj->Get($sKeyAttCode) == 0)
									{
										$rawValue = '';
									}
								}
							}
							if ($bLocalize)
							{
								$outputValue = htmlentities($oFinalAttDef->GetEditValue($rawValue), ENT_QUOTES, 'UTF-8');
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
			$sHtml .= implode("\n", $aRow);
			$sHtml .= "</tr>\n";
		}
		$sHtml .= "</table>\n";
		
		return $sHtml;
	}
	
	static function DisplaySetAsXML(WebPage $oPage, CMDBObjectSet $oSet, $aParams = array())
	{
		$bLocalize = true;
		if (isset($aParams['localize_values']))
		{
			$bLocalize = (bool) $aParams['localize_values'];
		}

		$oAppContext = new ApplicationContext();
		$aClasses = $oSet->GetFilter()->GetSelectedClasses();
		$aAuthorizedClasses = array();
		foreach($aClasses as $sAlias => $sClassName)
		{
			if (UserRights::IsActionAllowed($sClassName, UR_ACTION_READ, $oSet) && (UR_ALLOWED_YES || UR_ALLOWED_DEPENDS))
			{
				$aAuthorizedClasses[$sAlias] = $sClassName;
			}
		}
		$aAttribs = array();
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
				foreach(MetaModel::ListAttributeDefs($sClassName) as $sAttCode=>$oAttDef)
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

	public static function DisplaySearchForm(WebPage $oPage, CMDBObjectSet $oSet, $aExtraParams = array())
	{

		$oPage->add(self::GetSearchForm($oPage, $oSet, $aExtraParams));
	}
	
	public static function GetSearchForm(WebPage $oPage, CMDBObjectSet $oSet, $aExtraParams = array())
	{
		static $iSearchFormId = 0;
		$bMultiSelect = false;
		$oAppContext = new ApplicationContext();
		$sHtml = '';
		$numCols=4;
		$sClassName = $oSet->GetFilter()->GetClass();

		// Romain: temporarily removed the tab "OQL query" because it was not finalized
		// (especially when used to add a link)
		/*
		$sHtml .= "<div class=\"mini_tabs\" id=\"mini_tabs{$iSearchFormId}\"><ul>
					<li><a href=\"#\" onClick=\"$('div.mini_tab{$iSearchFormId}').toggle();$('#mini_tabs{$iSearchFormId} ul li a').toggleClass('selected');\">".Dict::S('UI:OQLQueryTab')."</a></li>
					<li><a class=\"selected\" href=\"#\" onClick=\"$('div.mini_tab{$iSearchFormId}').toggle();$('#mini_tabs{$iSearchFormId} ul li a').toggleClass('selected');\">".Dict::S('UI:SimpleSearchTab')."</a></li>
					</ul></div>\n";
		*/
		// Simple search form
		if (isset($aExtraParams['currentId']))
		{
			$sSearchFormId = $aExtraParams['currentId'];
		}
		else
		{
			$iSearchFormId = $oPage->GetUniqueId();
			$sSearchFormId = 'SimpleSearchForm'.$iSearchFormId;
			$sHtml .= "<div id=\"ds_$sSearchFormId\" class=\"mini_tab{$iSearchFormId}\">\n";			
		}
		// Check if the current class has some sub-classes
		if (isset($aExtraParams['baseClass']))
		{
			$sRootClass = $aExtraParams['baseClass'];
		}
		else
		{
			$sRootClass = $sClassName;
		}
		$aSubClasses = MetaModel::GetSubclasses($sRootClass);
		if (count($aSubClasses) > 0)
		{
			$aOptions = array();
			$aOptions[MetaModel::GetName($sRootClass)] = "<option value=\"$sRootClass\">".MetaModel::GetName($sRootClass)."</options>\n";
			foreach($aSubClasses as $sSubclassName)
			{
				$aOptions[MetaModel::GetName($sSubclassName)] = "<option value=\"$sSubclassName\">".MetaModel::GetName($sSubclassName)."</options>\n";
			}
			$aOptions[MetaModel::GetName($sClassName)] = "<option selected value=\"$sClassName\">".MetaModel::GetName($sClassName)."</options>\n";
			ksort($aOptions);
			$sContext = $oAppContext->GetForLink();
			$sClassesCombo = "<select name=\"class\" onChange=\"ReloadSearchForm('$sSearchFormId', this.value, '$sRootClass', '$sContext')\">\n".implode('', $aOptions)."</select>\n";
		}
		else
		{
			$sClassesCombo = MetaModel::GetName($sClassName);
		}
		$oUnlimitedFilter = new DBObjectSearch($sClassName);
		$sAction = (isset($aExtraParams['action'])) ? $aExtraParams['action'] : utils::GetAbsoluteUrlAppRoot().'pages/UI.php';
		$sHtml .= "<form id=\"fs_{$sSearchFormId}\" action=\"{$sAction}\">\n"; // Don't use $_SERVER['SCRIPT_NAME'] since the form may be called asynchronously (from ajax.php)
		$sHtml .= "<h2>".Dict::Format('UI:SearchFor_Class_Objects', $sClassesCombo)."</h2>\n";
		$index = 0;
		$sHtml .= "<p>\n";
		$aFilterCriteria = $oSet->GetFilter()->GetCriteria();
		$aMapCriteria = array();
		// Todo: Investigate... The search criteria is an expression, i.e. a tree!
		//     I wonder if that code could work... cleanup required/recommended
		foreach($aFilterCriteria as $aCriteria)
		{
			$aMapCriteria[$aCriteria['filtercode']][] = array('value' => $aCriteria['value'], 'opcode' => $aCriteria['opcode']);
		}
		$aList = MetaModel::GetZListItems($sClassName, 'standard_search');
		$aConsts = $oSet->ListConstantFields(); // Some fields are constants based on the query/context
		$sClassAlias = $oSet->GetFilter()->GetClassAlias();
		foreach($aList as $sFilterCode)
		{
			//$oAppContext->Reset($sFilterCode); // Make sure the same parameter will not be passed twice
			$sHtml .= '<span style="white-space: nowrap;padding:5px;display:inline-block;">';
			$sFilterValue = isset($aConsts[$sClassAlias][$sFilterCode]) ? $aConsts[$sClassAlias][$sFilterCode] : '';
			$sFilterValue = utils::ReadParam($sFilterCode, $sFilterValue, false, 'raw_data');
			$sFilterOpCode = null; // Use the default 'loose' OpCode
			if (empty($sFilterValue))
			{
				if (isset($aMapCriteria[$sFilterCode]))
				{
					if (count($aMapCriteria[$sFilterCode]) > 1)
					{
						$sFilterValue = Dict::S('UI:SearchValue:Mixed');
					}
					else
					{
						$sFilterValue = $aMapCriteria[$sFilterCode][0]['value'];
						$sFilterOpCode = $aMapCriteria[$sFilterCode][0]['opcode'];
					}
					// Todo: Investigate...
					if ($sFilterCode != 'company')
					{
						$oUnlimitedFilter->AddCondition($sFilterCode, $sFilterValue, $sFilterOpCode);
					}
				}
			}

			$oAttDef = MetaModel::GetAttributeDef($sClassName, $sFilterCode);
			if ($oAttDef->IsExternalKey(EXTKEY_ABSOLUTE))
			{
				$oKeyAttDef = $oAttDef->GetFinalAttDef();
				$sKeyAttClass = $oKeyAttDef->GetHostClass();
				$sKeyAttCode = $oKeyAttDef->GetCode();

				$sTargetClass = $oKeyAttDef->GetTargetClass();
				$oSearch = new DBObjectSearch($sTargetClass);
				$oSearch->SetModifierProperty('UserRightsGetSelectFilter', 'bSearchMode', true);
				$oAllowedValues = new DBObjectSet($oSearch);

				$iFieldSize = $oKeyAttDef->GetMaxSize();
				$iMaxComboLength = $oKeyAttDef->GetMaximumComboLength();
				$sHtml .= "<label>".MetaModel::GetFilterLabel($sKeyAttClass, $sKeyAttCode).":</label>&nbsp;";
				$aExtKeyParams = $aExtraParams;
				$aExtKeyParams['iFieldSize'] = $oKeyAttDef->GetMaxSize();
				$aExtKeyParams['iMinChars'] = $oKeyAttDef->GetMinAutoCompleteChars();
				$sHtml .= UIExtKeyWidget::DisplayFromAttCode($oPage, $sKeyAttCode, $sKeyAttClass, $oAttDef->GetLabel(), $oAllowedValues, $sFilterValue, $sSearchFormId.'search_'.$sFilterCode, false, $sFilterCode, '', $aExtKeyParams, true);
			}
			else
			{
				$aAllowedValues = MetaModel::GetAllowedValues_flt($sClassName, $sFilterCode, $aExtraParams);
				if (is_null($aAllowedValues))
				{
					// Any value is possible, display an input box
					$sHtml .= "<label>".MetaModel::GetFilterLabel($sClassName, $sFilterCode).":</label>&nbsp;<input class=\"textSearch\" name=\"$sFilterCode\" value=\"".htmlentities($sFilterValue, ENT_QUOTES, 'utf-8')."\"/>\n";
				}
				else
				{
					//Enum field, display a multi-select combo
					$sValue = "<select class=\"multiselect\" size=\"1\" name=\"{$sFilterCode}[]\" multiple>\n";
					$bMultiSelect = true;
					//$sValue .= "<option value=\"\">".Dict::S('UI:SearchValue:Any')."</option>\n";
					asort($aAllowedValues);
					foreach($aAllowedValues as $key => $value)
					{
						if (is_array($sFilterValue) && in_array($key, $sFilterValue))
						{
							$sSelected = ' selected';
						}
						else if ($sFilterValue == $key)
						{
							$sSelected = ' selected';
						}
						else
						{
							$sSelected = '';
						}
						$sValue .= "<option value=\"$key\"$sSelected>$value</option>\n";
					}
					$sValue .= "</select>\n";
					$sHtml .= "<label>".MetaModel::GetFilterLabel($sClassName, $sFilterCode).":</label>&nbsp;$sValue\n";
				}				
			}
			unset($aExtraParams[$sFilterCode]);
			
			// Finally, add a tooltip if one is defined for this attribute definition
			$sTip = $oAttDef->GetHelpOnSmartSearch();
			if (strlen($sTip) > 0)
			{
				$sTip = addslashes($sTip);
				$sTip = str_replace(array("\n", "\r"), " ", $sTip);
				// :input does represent in form visible input (INPUT, SELECT, TEXTAREA)
				$oPage->add_ready_script("$('form#fs_$sSearchFormId :input[name={$sFilterCode}]').qtip( { content: '$sTip', show: 'mouseover', hide: 'mouseout', style: { name: 'dark', tip: 'leftTop' }, position: { corner: { target: 'rightMiddle', tooltip: 'leftTop' }} } );");
			}
			$index++;
			$sHtml .= '</span> ';
		}
		$sHtml .= "</p>\n";
		$sHtml .= "<p align=\"right\"><input type=\"submit\" value=\"".Dict::S('UI:Button:Search')."\"></p>\n";
		if (isset($aExtraParams['table_id']))
		{
			// Rename to avoid collisions...
			$aExtraParams['_table_id_'] = $aExtraParams['table_id'];
			unset($aExtraParams['table_id']);
		}
		foreach($aExtraParams as $sName => $sValue)
		{
			if (is_scalar($sValue))
			{
				$sHtml .= "<input type=\"hidden\" name=\"$sName\" value=\"".htmlentities($sValue, ENT_QUOTES, 'UTF-8')."\" />\n";
			}
		}
		$sHtml .= "<input type=\"hidden\" name=\"class\" value=\"$sClassName\" />\n";
		$sHtml .= "<input type=\"hidden\" name=\"dosearch\" value=\"1\" />\n";
		$sHtml .= "<input type=\"hidden\" name=\"operation\" value=\"search_form\" />\n";
		$sHtml .= $oAppContext->GetForForm();
		$sHtml .= "</form>\n";		
		if (!isset($aExtraParams['currentId']))
		{
			$sHtml .= "</div><!-- Simple search form -->\n";
		}
		if ($bMultiSelect)
		{
			$aOptions = array(
				'header' => true,
				'checkAllText' => Dict::S('UI:SearchValue:CheckAll'),
				'uncheckAllText' => Dict::S('UI:SearchValue:UncheckAll'),
				'noneSelectedText' => Dict::S('UI:SearchValue:Any'),
				'selectedText' => Dict::S('UI:SearchValue:NbSelected'),
				'selectedList' => 1,
			);
			$sJSOptions = json_encode($aOptions);
			$oPage->add_ready_script("$('.multiselect').multiselect($sJSOptions);");
		}
/*
		// OQL query builder
		$sHtml .= "<div id=\"OQLQuery{$iSearchFormId}\" style=\"display:none\" class=\"mini_tab{$iSearchFormId}\">\n";
		$sHtml .= "<h1>".Dict::S('UI:OQLQueryBuilderTitle')."</h1>\n";
		$sHtml .= "<form id=\"formOQL{$iSearchFormId}\"><table style=\"width:80%;\"><tr style=\"vertical-align:top\">\n";
		$sHtml .= "<td style=\"text-align:right\"><label>SELECT&nbsp;</label><select name=\"oql_class\">";
		$aClasses = MetaModel::EnumChildClasses($sClassName, ENUM_CHILD_CLASSES_ALL);
		$sSelectedClass = utils::ReadParam('oql_class', $sClassName, false, 'class');
		$sOQLClause = utils::ReadParam('oql_clause', '', false, 'raw_data');
		asort($aClasses);
		foreach($aClasses as $sChildClass)
		{
			$sSelected = ($sChildClass == $sSelectedClass) ? 'selected' : '';
			$sHtml.= "<option value=\"$sChildClass\" $sSelected>".MetaModel::GetName($sChildClass)."</option>\n";
		}
		$sHtml .= "</select>&nbsp;</td><td>\n";
		$sHtml .= "<textarea name=\"oql_clause\" style=\"width:100%\">$sOQLClause</textarea></td></tr>\n";
		$sHtml .= "<tr><td colspan=\"2\" style=\"text-align:right\"><input type=\"submit\" value=\"".Dict::S('UI:Button:Query')."\"></td></tr>\n";
		$sHtml .= "<input type=\"hidden\" name=\"dosearch\" value=\"1\" />\n";
		foreach($aExtraParams as $sName => $sValue)
		{
			if (is_scalar($sValue))
			{
				$sHtml .= "<input type=\"hidden\" name=\"$sName\" value=\"$sValue\" />\n";
			}
		}
		$sHtml .= "<input type=\"hidden\" name=\"operation\" value=\"search_oql\" />\n";
		$sHtml .= $oAppContext->GetForForm();
		$sHtml .= "</table></form>\n";
		$sHtml .= "</div><!-- OQL query form -->\n";
*/
		return $sHtml;
	}
	
	public static function GetFormElementForField($oPage, $sClass, $sAttCode, $oAttDef, $value = '', $sDisplayValue = '', $iId = '', $sNameSuffix = '', $iFlags = 0, $aArgs = array())
	{
		static $iInputId = 0;
		$sFieldPrefix = '';
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

		if (!$oAttDef->IsExternalField())
		{
			$bMandatory = 'false';
			if ( (!$oAttDef->IsNullAllowed()) || ($iFlags & OPT_ATT_MANDATORY))
			{
				$bMandatory = 'true';
			}
			$sValidationField = "<span class=\"form_validation\" id=\"v_{$iId}\"></span>";
			$sHelpText = htmlentities($oAttDef->GetHelpOnEdition(), ENT_QUOTES, 'UTF-8');
			$aEventsList = array();
			switch($oAttDef->GetEditClass())
			{
				case 'Date':
				$aEventsList[] ='validate';
				$aEventsList[] ='keyup';
				$aEventsList[] ='change';
				if (($iFlags & OPT_ATT_MANDATORY) && (empty($sDisplayValue)))
				{
					$sDisplayValue = date($oAttDef->GetDateFormat());
				}
				$sHTMLValue = "<input title=\"$sHelpText\" class=\"date-pick\" type=\"text\" size=\"12\" name=\"attr_{$sFieldPrefix}{$sAttCode}{$sNameSuffix}\" value=\"".htmlentities($sDisplayValue, ENT_QUOTES, 'UTF-8')."\" id=\"$iId\"/>&nbsp;{$sValidationField}";
				break;

				case 'DateTime':
				$aEventsList[] ='validate';
				$aEventsList[] ='keyup';
				$aEventsList[] ='change';
				if (($iFlags & OPT_ATT_MANDATORY) && (empty($sDisplayValue)))
				{
					$sDisplayValue = date($oAttDef->GetDateFormat());
				}
				$sHTMLValue = "<input title=\"$sHelpText\" class=\"datetime-pick\" type=\"text\" size=\"20\" name=\"attr_{$sFieldPrefix}{$sAttCode}{$sNameSuffix}\" value=\"".htmlentities($sDisplayValue, ENT_QUOTES, 'UTF-8')."\" id=\"$iId\"/>&nbsp;{$sValidationField}";
				break;

				case 'Duration':
				$aEventsList[] ='validate';
				$aEventsList[] ='change';
				$oPage->add_ready_script("$('#{$iId}_d').bind('keyup change', function(evt, sFormId) { return UpdateDuration('$iId'); });");
				$oPage->add_ready_script("$('#{$iId}_h').bind('keyup change', function(evt, sFormId) { return UpdateDuration('$iId'); });");
				$oPage->add_ready_script("$('#{$iId}_m').bind('keyup change', function(evt, sFormId) { return UpdateDuration('$iId'); });");
				$oPage->add_ready_script("$('#{$iId}_s').bind('keyup change', function(evt, sFormId) { return UpdateDuration('$iId'); });");
				$aVal = AttributeDuration::SplitDuration($value);
				$sDays = "<input title=\"$sHelpText\" type=\"text\" style=\"text-align:right\" size=\"3\" name=\"attr_{$sFieldPrefix}{$sAttCode}[d]{$sNameSuffix}\" value=\"{$aVal['days']}\" id=\"{$iId}_d\"/>";
				$sHours = "<input title=\"$sHelpText\" type=\"text\" style=\"text-align:right\" size=\"2\" name=\"attr_{$sFieldPrefix}{$sAttCode}[h]{$sNameSuffix}\" value=\"{$aVal['hours']}\" id=\"{$iId}_h\"/>";
				$sMinutes = "<input title=\"$sHelpText\" type=\"text\" style=\"text-align:right\" size=\"2\" name=\"attr_{$sFieldPrefix}{$sAttCode}[m]{$sNameSuffix}\" value=\"{$aVal['minutes']}\" id=\"{$iId}_m\"/>";
				$sSeconds = "<input title=\"$sHelpText\" type=\"text\" style=\"text-align:right\" size=\"2\" name=\"attr_{$sFieldPrefix}{$sAttCode}[s]{$sNameSuffix}\" value=\"{$aVal['seconds']}\" id=\"{$iId}_s\"/>";
				$sHidden = "<input type=\"hidden\" id=\"{$iId}\" value=\"".htmlentities($value, ENT_QUOTES, 'UTF-8')."\"/>";
				$sHTMLValue = Dict::Format('UI:DurationForm_Days_Hours_Minutes_Seconds', $sDays, $sHours, $sMinutes, $sSeconds).$sHidden."&nbsp;".$sValidationField;
				$oPage->add_ready_script("$('#{$iId}').bind('update', function(evt, sFormId) { return ToggleDurationField('$iId'); });");				
				break;
				
				case 'Password':
					$aEventsList[] ='validate';
					$aEventsList[] ='keyup';
					$aEventsList[] ='change';
					$sHTMLValue = "<input title=\"$sHelpText\" type=\"password\" size=\"30\" name=\"attr_{$sFieldPrefix}{$sAttCode}{$sNameSuffix}\" value=\"".htmlentities($value, ENT_QUOTES, 'UTF-8')."\" id=\"$iId\"/>&nbsp;{$sValidationField}";
				break;
				
				case 'OQLExpression':
				case 'Text':
					$aEventsList[] ='validate';
					$aEventsList[] ='keyup';
					$aEventsList[] ='change';
					$sEditValue = $oAttDef->GetEditValue($value);
					$aStyles = array();
					$sStyle = '';
					$sWidth = $oAttDef->GetWidth('width', '');

					if (!empty($sWidth))
					{
						$aStyles[] = 'width:'.$sWidth;
					}
					$sHeight = $oAttDef->GetHeight('height', '');
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
					$sHTMLValue = "<table><tr><td><textarea class=\"resizable\" title=\"$sHelpText\" name=\"attr_{$sFieldPrefix}{$sAttCode}{$sNameSuffix}\" rows=\"8\" cols=\"40\" id=\"$iId\" $sStyle>".htmlentities($sEditValue, ENT_QUOTES, 'UTF-8')."</textarea>$sAdditionalStuff</td><td>{$sValidationField}</td></tr></table>";

				break;

				case 'CaseLog':
					$aStyles = array();
					$sStyle = '';
					$sWidth = $oAttDef->GetWidth('width', '');
					if (!empty($sWidth))
					{
						$aStyles[] = 'width:'.$sWidth;
					}
					$sHeight = $oAttDef->GetHeight('height', '');
					if (!empty($sHeight))
					{
						$aStyles[] = 'height:'.$sHeight;
					}
					if (count($aStyles) > 0)
					{
						$sStyle = 'style="'.implode('; ', $aStyles).'"';
					}
					$sHeader = '<div class="caselog_input_header">&nbsp;'.Dict::S('UI:CaseLogTypeYourTextHere').'</div>';
					$sEditValue = $oAttDef->GetEditValue($value);
					$sPreviousLog = is_object($value) ? $value->GetAsHTML($oPage, true /* bEditMode */, array('AttributeText', 'RenderWikiHtml')) : '';
					$iEntriesCount = is_object($value) ? count($value->GetIndex()) : 0;
					$sHidden = "<input type=\"hidden\" id=\"{$iId}_count\" value=\"$iEntriesCount\"/>"; // To know how many entries the case log already contains
					$sHTMLValue = "<div class=\"caselog\" $sStyle><table style=\"width:100%;\"><tr><td>$sHeader<textarea style=\"border:0;width:100%\" title=\"$sHelpText\" name=\"attr_{$sFieldPrefix}{$sAttCode}{$sNameSuffix}\" rows=\"8\" cols=\"40\" id=\"$iId\">".htmlentities($sEditValue, ENT_QUOTES, 'UTF-8')."</textarea>$sPreviousLog</td><td>{$sValidationField}</td></tr></table>$sHidden</div>";
					$oPage->add_ready_script("$('#$iId').bind('keyup change validate', function(evt, sFormId) { return ValidateCaseLogField('$iId', $bMandatory, sFormId) } );"); // Custom validation function
				break;

				case 'HTML':
					$oWidget = new UIHTMLEditorWidget($iId, $sAttCode, $sNameSuffix, $sFieldPrefix, $sHelpText, $sValidationField, $value, $bMandatory);
					$sHTMLValue = $oWidget->Display($oPage, $aArgs);
				break;

				case 'LinkedSet':
					if ($oAttDef->IsIndirect())
					{
						$oWidget = new UILinksWidget($sClass, $sAttCode, $iId, $sNameSuffix, $oAttDef->DuplicatesAllowed(), $aArgs);
					}
					else
					{
						$oWidget = new UILinksWidgetDirect($sClass, $sAttCode, $iId, $sNameSuffix, $aArgs);
					}					
					$aEventsList[] ='validate';
					$aEventsList[] ='change';
					$oObj = isset($aArgs['this']) ? $aArgs['this'] : null;
					$sHTMLValue = $oWidget->Display($oPage, $value, array(), $sFormPrefix, $oObj);
					break;
							
				case 'Document':
					$aEventsList[] ='validate';
					$aEventsList[] ='change';
					$oDocument = $value; // Value is an ormDocument object
					$sFileName = '';
					if (is_object($oDocument))
					{
						$sFileName = $oDocument->GetFileName();
					}
					$iMaxFileSize = utils::ConvertToBytes(ini_get('upload_max_filesize'));
					$sHTMLValue = "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"$iMaxFileSize\" />\n";
					$sHTMLValue .= "<input name=\"attr_{$sFieldPrefix}{$sAttCode}{$sNameSuffix}[filename]\" type=\"hidden\" id=\"$iId\" \" value=\"".htmlentities($sFileName, ENT_QUOTES, 'UTF-8')."\"/>\n";
					$sHTMLValue .= "<span id=\"name_$iInputId\">$sFileName</span><br/>\n";
					$sHTMLValue .= "<input title=\"$sHelpText\" name=\"attr_{$sFieldPrefix}{$sAttCode}{$sNameSuffix}[fcontents]\" type=\"file\" id=\"file_$iId\" onChange=\"UpdateFileName('$iId', this.value)\"/>&nbsp;{$sValidationField}\n";
				break;
				
				case 'StopWatch':
					$sHTMLValue = "The edition of a stopwatch is not allowed!!!";
				break;

				case 'List':
					// Not editable for now...
					$sHTMLValue = '';
				break;
				
				case 'One Way Password':
					$aEventsList[] ='validate';
					$oWidget = new UIPasswordWidget($sAttCode, $iId, $sNameSuffix);
					$sHTMLValue = $oWidget->Display($oPage, $aArgs);
					// Event list & validation is handled  directly by the widget
				break;
				
				case 'ExtKey':
					$aEventsList[] ='validate';
					$aEventsList[] ='change';

					$oAllowedValues = MetaModel::GetAllowedValuesAsObjectSet($sClass, $sAttCode, $aArgs);
					$sFieldName = $sFieldPrefix.$sAttCode.$sNameSuffix;
					$aExtKeyParams = $aArgs;
					$aExtKeyParams['iFieldSize'] = $oAttDef->GetMaxSize();
					$aExtKeyParams['iMinChars'] = $oAttDef->GetMinAutoCompleteChars();	
					$sHTMLValue = UIExtKeyWidget::DisplayFromAttCode($oPage, $sAttCode, $sClass, $oAttDef->GetLabel(), $oAllowedValues, $value, $iId, $bMandatory, $sFieldName, $sFormPrefix, $aExtKeyParams);
					$sHTMLValue .= "<!-- iFlags: $iFlags bMandatory: $bMandatory -->\n";
					break;
					
				case 'String':
				default:
					$aEventsList[] ='validate';
					// #@# todo - add context information (depending on dimensions)
					$aAllowedValues = MetaModel::GetAllowedValues_att($sClass, $sAttCode, $aArgs);
					$iFieldSize = $oAttDef->GetMaxSize();
					if ($aAllowedValues !== null)
					{
						// Discrete list of values, use a SELECT or RADIO buttons depending on the config
						$sDisplayStyle = $oAttDef->GetDisplayStyle();
						switch($sDisplayStyle)
						{
							case 'radio':
							case 'radio_horizontal':
							case 'radio_vertical':
							$sHTMLValue = '';
							$bVertical = ($sDisplayStyle != 'radio_horizontal');
							$sHTMLValue = $oPage->GetRadioButtons($aAllowedValues, $value, $iId, "attr_{$sFieldPrefix}{$sAttCode}{$sNameSuffix}", $bMandatory, $bVertical, $sValidationField);
							$aEventsList[] ='change';
							break;
							
							case 'select':
							default:
							$sHTMLValue = "<select title=\"$sHelpText\" name=\"attr_{$sFieldPrefix}{$sAttCode}{$sNameSuffix}\" id=\"$iId\">\n";
							$sHTMLValue .= "<option value=\"\">".Dict::S('UI:SelectOne')."</option>\n";
							foreach($aAllowedValues as $key => $display_value)
							{
								if ((count($aAllowedValues) == 1) && ($bMandatory == 'true') )
								{
									// When there is only once choice, select it by default
									$sSelected = ' selected';
								}
								else
								{
									$sSelected = ($value == $key) ? ' selected' : '';
								}
								$sHTMLValue .= "<option value=\"$key\"$sSelected>$display_value</option>\n";
							}
							$sHTMLValue .= "</select>&nbsp;{$sValidationField}\n";
							$aEventsList[] ='change';
						}
					}
					else
					{
						$sHTMLValue = "<input title=\"$sHelpText\" type=\"text\" size=\"30\" maxlength=\"$iFieldSize\" name=\"attr_{$sFieldPrefix}{$sAttCode}{$sNameSuffix}\" value=\"".htmlentities($sDisplayValue, ENT_QUOTES, 'UTF-8')."\" id=\"$iId\"/>&nbsp;{$sValidationField}";
						$aEventsList[] ='keyup';
						$aEventsList[] ='change';
					}
				break;
			}
			$sPattern = addslashes($oAttDef->GetValidationPattern()); //'^([0-9]+)$';			
			if (!empty($aEventsList))
			{
				$sNullValue = $oAttDef->GetNullValue();
				if (!is_numeric($sNullValue))
				{
					$sNullValue = "'$sNullValue'"; // Add quotes to turn this into a JS string if it's not a number
				}
				$sOriginalValue = ($iFlags & OPT_ATT_MUSTCHANGE) ? json_encode($value) : 'undefined';
				$oPage->add_ready_script("$('#$iId').bind('".implode(' ', $aEventsList)."', function(evt, sFormId) { return ValidateField('$iId', '$sPattern', $bMandatory, sFormId, $sNullValue, $sOriginalValue) } );\n"); // Bind to a custom event: validate
			}
			$aDependencies = MetaModel::GetDependentAttributes($sClass, $sAttCode); // List of attributes that depend on the current one
			if (count($aDependencies) > 0)
			{
				// Unbind first to avoid duplicate event handlers in case of reload of the whole (or part of the) form
				$oPage->add_ready_script("$('#$iId').unbind('change.dependencies').bind('change.dependencies', function(evt, sFormId) { return oWizardHelper{$sFormPrefix}.UpdateDependentFields(['".implode("','", $aDependencies)."']) } );\n"); // Bind to a custom event: validate
			}
		}
		$oPage->add_dict_entry('UI:ValueMustBeSet');
		$oPage->add_dict_entry('UI:ValueMustBeChanged');
		$oPage->add_dict_entry('UI:ValueInvalidFormat');
		return "<div>{$sHTMLValue}</div>";
	}

	public function DisplayModifyForm(WebPage $oPage, $aExtraParams = array())
	{
		self::$iGlobalFormId++;
		$this->aFieldsMap = array();
		$sPrefix = '';
		if (isset($aExtraParams['formPrefix']))
		{
			$sPrefix = $aExtraParams['formPrefix'];
		}
		$aFieldsComments = (isset($aExtraParams['fieldsComments'])) ? $aExtraParams['fieldsComments'] : array();
		
		$this->m_iFormId = $sPrefix.self::$iGlobalFormId;
		$sClass = get_class($this);
		$oAppContext = new ApplicationContext();
		$sStateAttCode = MetaModel::GetStateAttributeCode($sClass);
		$iKey = $this->GetKey();
		$aDetails = array();
		$aFieldsMap = array();
		if (!isset($aExtraParams['action']))
		{
			$sFormAction = $_SERVER['SCRIPT_NAME']; // No parameter in the URL, the only parameter will be the ones passed through the form
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
		else if ($iKey > 0)
		{
			$sApplyButton = Dict::S('UI:Button:Apply');
		}
		else
		{
			$sApplyButton = Dict::S('UI:Button:Create');
		}
		// Custom operation for the form ?
		if (isset($aExtraParams['custom_operation']))
		{
			$sOperation = $aExtraParams['custom_operation'];			
		}
		else if ($iKey > 0)
		{
			$sOperation = 'apply_modify';
		}
		else
		{
			$sOperation = 'apply_new';
		}
		if ($iKey > 0)
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
				$iActionAllowed = (get_class($aStimuli[$sStimulusCode]) == 'StimulusUserAction') ? UserRights::IsStimulusAllowed($sClass, $sStimulusCode, $oSetToCheckRights) : UR_ALLOWED_NO;
				switch($iActionAllowed)
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
				$sStatesSelection = Dict::Format('UI:Create_Class_InState', MetaModel::GetName($sClass)).'<select name="obj_state" class="state_select_'.$this->m_iFormId.'">';
				foreach($aInitialStates as $sStateCode => $sStateData)
				{
					$sSelected = '';
					if ($sStateCode == $this->GetState())
					{
						$sSelected = ' selected';
					}
					$sStatesSelection .= '<option value="'.$sStateCode.'"'.$sSelected.'>'.MetaModel::GetStateLabel($sClass, $sStateCode).'</option>';
				}
				$sStatesSelection .= '</select>';
				$oPage->add_ready_script("$('.state_select_{$this->m_iFormId}').change( function() { oWizardHelper$sPrefix.ReloadObjectCreationForm('form_{$this->m_iFormId}', $(this).val()); } );");
			}
		}

		$sConfirmationMessage = addslashes(Dict::S('UI:NavigateAwayConfirmationMessage'));
		$oPage->add_ready_script(
<<<EOF
	$(window).unload(function() { return OnUnload('$iTransactionId') } );
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
		$oPage->SetCurrentTab(Dict::S('UI:PropertiesTab'));

		$aFieldsMap = $this->DisplayBareProperties($oPage, true, $sPrefix, $aExtraParams);
		if ($iKey > 0)
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
		$oPage->add($oAppContext->GetForForm());
		if ($sButtonsPosition != 'top')
		{
			// bottom or both: display the buttons here
			$oPage->p($sStatesSelection);
			$oPage->add($sButtons);
		}

		// Hook the cancel button via jQuery so that it can be unhooked easily as well if needed
		$sDefaultUrl = utils::GetAbsoluteUrlAppRoot().'pages/UI.php?operation=cancel&'.$oAppContext->GetForLink();
		$oPage->add_ready_script("$('#form_{$this->m_iFormId} button.cancel').click( function() { BackToDetails('$sClass', $iKey, '$sDefaultUrl')} );");
		$oPage->add("</form>\n");
		
		$iFieldsCount = count($aFieldsMap);
		$sJsonFieldsMap = json_encode($aFieldsMap);
		$sState = $this->GetState();

		$oPage->add_script(
<<<EOF
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
	}

	public static function DisplayCreationForm(WebPage $oPage, $sClass, $oObjectToClone = null, $aArgs = array(), $aExtraParams = array())
	{
		$oAppContext = new ApplicationContext();
		$sClass = ($oObjectToClone == null) ? $sClass : get_class($oObjectToClone);
		$sStateAttCode = MetaModel::GetStateAttributeCode($sClass);
		$aStates = MetaModel::EnumStates($sClass);
		$sStatesSelection = '';
		
		if ($oObjectToClone == null)
		{
			$oObj = MetaModel::NewObject($sClass);
			if (!empty($sStateAttCode))
			{
				$sTargetState = MetaModel::GetDefaultState($sClass);
				$oObj->Set($sStateAttCode, $sTargetState);
			}
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
			$aDeps[$sAttCode] = MetaModel::GetPrequisiteAttributes($sClass, $sAttCode);
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
						$oAllowedValues = MetaModel::GetAllowedValuesAsObjectSet($sClass, $sAttCode, $aArgs);
						if ($oAllowedValues->Count() == 1)
						{
							$oRemoteObj = $oAllowedValues->Fetch();
							$oObj->Set($sAttCode, $oRemoteObj->GetKey());
						}
					}
					else
					{
						$aAllowedValues = MetaModel::GetAllowedValues_att($sClass, $sAttCode, $aArgs);
						if (count($aAllowedValues) == 1)
						{
							$aValues = array_keys($aAllowedValues);
							$oObj->Set($sAttCode, $aValues[0]);
						}
					}
				}
			}
		}
		return $oObj->DisplayModifyForm( $oPage, $aExtraParams);
	}
	
	public function DisplayStimulusForm(WebPage $oPage, $sStimulus)
	{
		$sClass = get_class($this);
		$aTransitions = $this->EnumTransitions();
		$aStimuli = MetaModel::EnumStimuli($sClass);
		if (!isset($aTransitions[$sStimulus]))
		{
			// Invalid stimulus
			throw new ApplicationException(Dict::Format('UI:Error:Invalid_Stimulus_On_Object_In_State', $sStimulus, $this->GetName(), $this->GetStateLabel()));
		}
		$sActionLabel = $aStimuli[$sStimulus]->GetLabel();
		$sActionDetails = $aStimuli[$sStimulus]->GetDescription();
		$aTransition = $aTransitions[$sStimulus];
		$sTargetState = $aTransition['target_state'];
		$aTargetStates = MetaModel::EnumStates($sClass);
		$oPage->add("<div class=\"page_header\">\n");
		$oPage->add("<h1>$sActionLabel - <span class=\"hilite\">{$this->GetName()}</span></h1>\n");
		$oPage->set_title($sActionLabel);
		$oPage->add("</div>\n");
		$aTargetState = $aTargetStates[$sTargetState];
		$aExpectedAttributes = $aTargetState['attribute_list'];
		$oPage->add("<h1>$sActionDetails</h1>\n");
		$sButtonsPosition = MetaModel::GetConfig()->Get('buttons_position');
		if ($sButtonsPosition == 'bottom')
		{
			// bottom: Displays the ticket details BEFORE the actions
			$oPage->add('<div class="ui-widget-content">');
			$this->DisplayBareProperties($oPage);
			$oPage->add('</div>');
		}
		$oPage->add("<div class=\"wizContainer\">\n");
		$oPage->add("<form id=\"apply_stimulus\" method=\"post\" onSubmit=\"return OnSubmit('apply_stimulus');\">\n");
		$aDetails = array();
		$iFieldIndex = 0;
		$aFieldsMap = array();

		$aDetailsList =$this->FlattenZList(MetaModel::GetZListItems($sClass, 'details'));
		// Order the fields based on their dependencies, set the fields for which there is only one possible value
		// and perform this in the order of dependencies to avoid dead-ends
		$aDeps = array();
		foreach($aDetailsList as $sAttCode)
		{
			$aDeps[$sAttCode] = MetaModel::GetPrequisiteAttributes($sClass, $sAttCode);
		}
		$aList =$this->OrderDependentFields($aDeps);

		foreach($aList as $sAttCode)
		{
			// Consider only the "expected" fields for the target state
			if (array_key_exists($sAttCode, $aExpectedAttributes))
			{
				$iExpectCode = $aExpectedAttributes[$sAttCode];
				// Prompt for an attribute if
				// - the attribute must be changed or must be displayed to the user for confirmation
				// - or the field is mandatory and currently empty
				if ( ($iExpectCode & (OPT_ATT_MUSTCHANGE | OPT_ATT_MUSTPROMPT)) ||
					 (($iExpectCode & OPT_ATT_MANDATORY) && ($this->Get($sAttCode) == '')) ) 
				{
					$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
					$aArgs = array('this' => $this);
					// If the field is mandatory, set it to the only possible value
					if ((!$oAttDef->IsNullAllowed()) || ($iExpectCode & OPT_ATT_MANDATORY))
					{
						if ($oAttDef->IsExternalKey())
						{
							$oAllowedValues = MetaModel::GetAllowedValuesAsObjectSet($sClass, $sAttCode, $aArgs);
							if ($oAllowedValues->Count() == 1)
							{
								$oRemoteObj = $oAllowedValues->Fetch();
								$this->Set($sAttCode, $oRemoteObj->GetKey());
							}
						}
						else
						{
							$aAllowedValues = MetaModel::GetAllowedValues_att($sClass, $sAttCode, $aArgs);
							if (count($aAllowedValues) == 1)
							{
								$aValues = array_keys($aAllowedValues);
								$this->Set($sAttCode, $aValues[0]);
							}
						}
					}
					$sHTMLValue = cmdbAbstractObject::GetFormElementForField($oPage, $sClass, $sAttCode, $oAttDef,$this->Get($sAttCode),$this->GetEditValue($sAttCode), 'att_'.$iFieldIndex, '', $iExpectCode, $aArgs);
					$aDetails[] = array('label' => '<span>'.$oAttDef->GetLabel().'</span>', 'value' => "<span id=\"field_att_$iFieldIndex\">$sHTMLValue</span>");
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
		$oPage->add("<input type=\"hidden\" name=\"transaction_id\" value=\"".utils::GetNewTransactionId()."\">\n");
		$oAppContext = new ApplicationContext();
		$oPage->add($oAppContext->GetForForm());
		$oPage->add("<button type=\"button\" class=\"action\" onClick=\"BackToDetails('$sClass', ".$this->GetKey().")\"><span>".Dict::S('UI:Button:Cancel')."</span></button>&nbsp;&nbsp;&nbsp;&nbsp;\n");
		$oPage->add("<button type=\"submit\" class=\"action\"><span>$sActionLabel</span></button>\n");
		$oPage->add("</form>\n");
		$oPage->add("</div>\n");
		if ($sButtonsPosition != 'top')
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
		var oWizardHelper = new WizardHelper('$sClass', '', '$sTargetState');
		oWizardHelper.SetFieldsMap($sJsonFieldsMap);
		oWizardHelper.SetFieldsCount($iFieldsCount);
EOF
		);
		$oPage->add_ready_script(
<<<EOF
		// Starts the validation when the page is ready
		CheckFields('apply_stimulus', false);
EOF
		);		
	}

	public static function ProcessZlist($aList, $aDetails, $sCurrentTab, $sCurrentCol, $sCurrentSet)
	{
		//echo "<pre>ZList: ";
		//print_r($aList);
		//echo "</pre>\n";
		$index = 0;
		foreach($aList as $sKey => $value)
		{
			if (is_array($value))
			{
				if (preg_match('/^(.*):(.*)$/U', $sKey, $aMatches))
				{
					$sCode = $aMatches[1];
					$sName = $aMatches[2];
					switch($sCode)
					{
						case 'tab':
						//echo "<p>Found a tab:  $sName ($sKey)</p>\n";
						if(!isset($aDetails[$sName]))
						{
							$aDetails[$sName] = array('col1' => array());
						}
						$aDetails = self::ProcessZlist($value, $aDetails, $sName, 'col1', '');
						break;
						
						case 'fieldset':
						//echo "<p>Found a fieldset: $sName ($sKey)</p>\n";
						if(!isset($aDetailsStruct[$sCurrentTab][$sCurrentCol][$sName]))
						{
							$aDetails[$sCurrentTab][$sCurrentCol][$sName] = array();
						}
						$aDetails = self::ProcessZlist($value, $aDetails, $sCurrentTab, $sCurrentCol, $sName);
						break;

						default:
						case 'col':
						//echo "<p>Found a column: $sName ($sKey)</p>\n";
						if(!isset($aDetails[$sCurrentTab][$sName]))
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
				//echo "<p>Scalar value: $value, in [$sCurrentTab][$sCurrentCol][$sCurrentSet][]</p>\n";
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

	static function FlattenZList($aList)
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
				$aResult = array_merge($aResult,self::FlattenZList($value));
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
		if ( (!$oAttDef->IsLinkSet()) && (($iFlags & OPT_ATT_HIDDEN) == 0) )
		{
			// The field is visible in the current state of the object
			if ($sStateAttCode == $sAttCode)
			{
				// Special display for the 'state' attribute itself
				$sDisplayValue = $this->GetStateLabel();
			}
			else if ($oAttDef->GetEditClass() == 'Document')
			{
				$oDocument = $this->Get($sAttCode);
				$sDisplayValue = $this->GetAsHTML($sAttCode);
				$sDisplayValue .= "<br/>".Dict::Format('UI:OpenDocumentInNewWindow_', $oDocument->GetDisplayLink(get_class($this), $this->GetKey(), $sAttCode)).", \n";
				$sDisplayValue .= "<br/>".Dict::Format('UI:DownloadDocument_', $oDocument->GetDownloadLink(get_class($this), $this->GetKey(), $sAttCode)).", \n";
			}
			else
			{
				$sDisplayValue = $this->GetAsHTML($sAttCode);
			}
			$retVal = array('label' => '<span title="'.MetaModel::GetDescription($sClass, $sAttCode).'">'.MetaModel::GetLabel($sClass, $sAttCode).'</span>', 'value' => $sDisplayValue);
		}
		return $retVal;
	}
	
	/**
	 * Displays a blob document *inline* (if possible, depending on the type of the document)
	 * @return string
	 */	 	 	
	public function DisplayDocumentInline(WebPage $oPage, $sAttCode)
	{
		$oDoc = $this->Get($sAttCode);
		$sClass = get_class($this);
		$Id = $this->GetKey();
		switch ($oDoc->GetMainMimeType())
		{
			case 'text':
			case 'html':
			$data = $oDoc->GetData();
			switch($oDoc->GetMimeType())
			{
				case 'text/html':
				case 'text/xml':
				$oPage->add("<iframe id='preview_$sAttCode' src=\"".utils::GetAbsoluteUrlAppRoot()."pages/ajax.render.php?operation=display_document&class=$sClass&id=$Id&field=$sAttCode\" width=\"100%\" height=\"400\">Loading...</iframe>\n");
				break;
				
				default:
				$oPage->add("<pre>".htmlentities(MyHelpers::beautifulstr($data, 1000, true), ENT_QUOTES, 'UTF-8')."</pre>\n");			
			}
			break;

			case 'application':
			switch($oDoc->GetMimeType())
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
	 * @param void
	 * @return String The desired higlight class for the object/row
	 */
	public function GetHilightClass()
	{
		// Possible return values are:
		// HILIGHT_CLASS_CRITICAL, HILIGHT_CLASS_WARNING, HILIGHT_CLASS_OK, HILIGHT_CLASS_NONE	
		$current = parent::GetHilightClass(); // Default computation

		// Invoke extensions before the deletion (the deletion will do some cleanup and we might loose some information
		foreach (MetaModel::EnumPlugins('iApplicationUIExtension') as $oExtensionInstance)
		{
			$new = $oExtensionInstance->GetHilightClass($this);
			@$current = self::$m_highlightComparison[$current][$new];
		}
		return $current;
	}
	
	/**
	 * Re-order the fields based on their inter-dependencies
	 * @params hash @aFields field_code => array_of_depencies
	 * @return array Ordered array of fields or throws an exception
	 */
	public static function OrderDependentFields($aFields)
	{
		$bCircular = false;
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
					else if (!array_key_exists($sDependency, $aFields))
					{
						// The current fields depends on a field not present in the form
						// let's ignore it (since it cannot change)
						unset($aFields[$sFieldCode][$key]);						
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
		}
		while($bSet && (count($aFields) > 0));
		
		if (count($aFields) > 0)
		{
			$sMessage =  "Error: Circular dependencies between the fields! <pre>".print_r($aFields, true)."</pre>";
			throw(new Exception($sMessage));
		}
		return $aResult;
	}
	
	/**
	 * Get the list of actions to be displayed as 'shortcuts' (i.e buttons) instead of inside the Actions popup menu
	 * @param $sFinalClass string The actual class of the objects for which to display the menu
	 * @return Array the list of menu codes (i.e dictionary entries) that can be displayed as shortcuts next to the actions menu
	 */
	 public static function GetShortcutActions($sFinalClass)
	 {
	 	$sShortcutActions = MetaModel::GetConfig()->Get('shortcut_actions');
	 	$aShortcutActions = explode(',', $sShortcutActions);
	 	return $aShortcutActions;
	 }
	
	/**
	 * Maps the given context parameter name to the appropriate filter/search code for this class
	 * @param string $sContextParam Name of the context parameter, i.e. 'org_id'
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
	 * @param $aAttList array $aAttList array of attcode
	 * @param $aErrors array Returns information about slave attributes
	 * @param $sTargetState string Target state for which to evaluate the writeable attributes (=current state is empty)
	 * @return array of attcodes that can be used for writing on the current object
	 */
	public function GetWriteableAttList($aAttList, &$aErrors, $sTargetState = '')
	{
		if (!is_array($aAttList))
		{
			$aAttList = $this->FlattenZList(MetaModel::GetZListItems(get_class($this), 'details'));
			// Special case to process the case log, if any...
			// WARNING: if you change this also check the functions DisplayModifyForm and DisplayCaseLog
			foreach(MetaModel::ListAttributeDefs(get_class($this)) as $sAttCode => $oAttDef)
			{
				if ($this->IsNew())
				{
					$iFlags = $this->GetInitialStateAttributeFlags($sAttCode);
				}
				else
				{
					$aVoid = array();
					$iFlags = $this->GetAttributeFlags($sAttCode, $aVoid, $sTargetState);
				}
				if ($oAttDef instanceof AttributeCaseLog)
				{
					if (!($iFlags & (OPT_ATT_HIDDEN|OPT_ATT_SLAVE|OPT_ATT_READONLY)))
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
			
			if ($this->IsNew())
			{
				$iFlags = $this->GetInitialStateAttributeFlags($sAttCode);
			}
			else
			{
				$aVoid = array();
				$iFlags = $this->GetAttributeFlags($sAttCode, $aVoid, $sTargetState);
			}
			if ($oAttDef->IsWritable())
			{
				if ( $iFlags & (OPT_ATT_HIDDEN | OPT_ATT_READONLY))
				{
					// Non-visible, or read-only attribute, do nothing
				}
				elseif($iFlags & OPT_ATT_SLAVE)
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
	 * Updates the object from a flat array of values
	 * @param string $aValues array of attcode => scalar or array (N-N links)
	 * @return void
	 */
	public function UpdateObjectFromArray($aValues)
	{
		foreach($aValues as $sAttCode => $value)
		{
			$oAttDef = MetaModel::GetAttributeDef(get_class($this), $sAttCode);
			if ($oAttDef->IsLinkSet() && $oAttDef->IsIndirect())
			{
				$aLinks = $value;
				$sLinkedClass = $oAttDef->GetLinkedClass();
				$sExtKeyToRemote = $oAttDef->GetExtKeyToRemote();
				$sExtKeyToMe = $oAttDef->GetExtKeyToMe();
				$oLinkedSet = DBObjectSet::FromScratch($sLinkedClass);
				if (is_array($aLinks))
				{
					foreach($aLinks as $id => $aData)
					{
						if (is_numeric($id))
						{
							if ($id < 0)
							{
								// New link to be created, the opposite of the id (-$id) is the ID of the remote object
								$oLink = MetaModel::NewObject($sLinkedClass);
								$oLink->Set($sExtKeyToRemote, -$id);
								$oLink->Set($sExtKeyToMe, $this->GetKey());
							}
							else
							{
								// Existing link, potentially to be updated...
								$oLink = MetaModel::GetObject($sLinkedClass, $id);
							}
							// Now populate the attributes
							foreach($aData as $sName => $value)
							{
								if (MetaModel::IsValidAttCode($sLinkedClass, $sName))
								{
									$oLinkAttDef = MetaModel::GetAttributeDef($sLinkedClass, $sName);
									if ($oLinkAttDef->IsWritable())
									{
										$oLink->Set($sName, $value);
									}
								}
							}
							$oLinkedSet->AddObject($oLink);
						}
					}
				}
				$this->Set($sAttCode, $oLinkedSet);
			}
			elseif ($oAttDef->GetEditClass() == 'Document')
			{
				// There should be an uploaded file with the named attr_<attCode>
				$oDocument = $value['fcontents'];
				if (!$oDocument->IsEmpty())
				{
					// A new file has been uploaded
					$this->Set($sAttCode, $oDocument);
				}
			}
			elseif ($oAttDef->GetEditClass() == 'One Way Password')
			{
				// Check if the password was typed/changed
				$aPwdData = $value;
				if (!is_null($aPwdData) && $aPwdData['changed'])
				{
					// The password has been changed or set
					$this->Set($sAttCode, $aPwdData['value']);
				}
			}
			elseif ($oAttDef->GetEditClass() == 'Duration')
			{
				$aDurationData = $value;
				if (!is_array($aDurationData)) continue;

				$iValue = (((24*$aDurationData['d'])+$aDurationData['h'])*60 +$aDurationData['m'])*60 + $aDurationData['s'];
				$this->Set($sAttCode, $iValue);
				$previousValue = $this->Get($sAttCode);
				if ($previousValue !== $iValue)
				{
					$this->Set($sAttCode, $iValue);
				}
			}
			else if (($oAttDef->GetEditClass() == 'LinkedSet') && !$oAttDef->IsIndirect() &&
			          (($oAttDef->GetEditMode() == LINKSET_EDITMODE_INPLACE) || ($oAttDef->GetEditMode() == LINKSET_EDITMODE_ADDREMOVE)))
			{
				$oLinkset = $this->Get($sAttCode);
				$sLinkedClass = $oLinkset->GetClass();
				$aObjSet = array();
				$oLinkset->Rewind();
				$bModified = false;
				while($oLink = $oLinkset->Fetch())
				{
					if (in_array($oLink->GetKey(), $value['to_be_deleted']))
					{
						// The link is to be deleted, don't copy it in the array
						$bModified = true;
					}
					else
					{
						if (!array_key_exists('to_be_removed', $value) || !in_array($oLink->GetKey(), $value['to_be_removed']))
						{
							$aObjSet[] = $oLink;
						}
					}
				}

				if (array_key_exists('to_be_created', $value) && (count($value['to_be_created']) > 0))
				{
					// Now handle the links to be created
					foreach($value['to_be_created'] as $aData)
					{
						$sSubClass = $aData['class'];
						if ( ($sLinkedClass == $sSubClass) || (is_subclass_of($sSubClass, $sLinkedClass)) )
						{
							$aObjData = $aData['data'];
							
							$oLink = new $sSubClass;
							$oLink->UpdateObjectFromArray($aObjData);
							$aObjSet[] = $oLink;
							$bModified = true;
						}
					}
				}
				if (array_key_exists('to_be_added', $value) && (count($value['to_be_added']) > 0))
				{
					// Now handle the links to be added by making the remote object point to self
					foreach($value['to_be_added'] as $iObjKey)
					{
						$oLink = MetaModel::GetObject($sLinkedClass, $iObjKey, false);
						if ($oLink)
						{
							$aObjSet[] = $oLink;
							$bModified = true;
						}
					}
				}
				if (array_key_exists('to_be_removed', $value) && (count($value['to_be_removed']) > 0))
				{
					// Now handle the links to be removed by making the remote object point to nothing
					// Keep them in the set (modified), DBWriteLinks will handle them
					foreach($value['to_be_removed'] as $iObjKey)
					{
						$oLink = MetaModel::GetObject($sLinkedClass, $iObjKey, false);
						if ($oLink)
						{
							$sExtKeyToMe = $oAttDef->GetExtKeyToMe();
							$oLink->Set($sExtKeyToMe, null);
							$aObjSet[] = $oLink;
							$bModified = true;
						}
					}
				}
				if ($bModified)
				{
					$oNewSet = DBObjectSet::FromArray($oLinkset->GetClass(), $aObjSet);
					$this->Set($sAttCode, $oNewSet);
				}		
			}
			else
			{
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

	/**
	 * Updates the object from the POSTed parameters (form)
	 */
	public function UpdateObjectFromPostedForm($sFormPrefix = '', $aAttList = null, $sTargetState = '')
	{
		if (is_null($aAttList))
		{
			$aAttList = array_keys(MetaModel::ListAttributeDefs(get_class($this)));
		}
		$aValues = array();
		foreach($aAttList as $sAttCode)
		{
			$oAttDef = MetaModel::GetAttributeDef(get_class($this), $sAttCode);
			if ($oAttDef->GetEditClass() == 'Document')
			{
				$value = array('fcontents' => utils::ReadPostedDocument("attr_{$sFormPrefix}{$sAttCode}", 'fcontents'));
			}
			else if (($oAttDef->GetEditClass() == 'LinkedSet') && !$oAttDef->IsIndirect() &&
			         (($oAttDef->GetEditMode() == LINKSET_EDITMODE_INPLACE) || ($oAttDef->GetEditMode() == LINKSET_EDITMODE_ADDREMOVE)) )
			{
				$aRawToBeCreated = json_decode(utils::ReadPostedParam("attr_{$sFormPrefix}{$sAttCode}_tbc", '{}', 'raw_data'), true);
				$aToBeCreated = array();
				foreach($aRawToBeCreated as $aData)
				{
					$sSubFormPrefix = $aData['formPrefix'];
					$sObjClass = $aData['class'];
					$aObjData = array();
					foreach($aData as $sKey => $value)
					{
						if (preg_match("/^attr_$sSubFormPrefix(.*)$/", $sKey, $aMatches))
						{
							$aObjData[$aMatches[1]] = $value;
						}
					}
					$aToBeCreated[] = array('class' => $sObjClass, 'data' => $aObjData);
				}
				
				$value = array('to_be_created' => $aToBeCreated, 
							   'to_be_deleted' => json_decode(utils::ReadPostedParam("attr_{$sFormPrefix}{$sAttCode}_tbd", '[]', 'raw_data'), true), 
							   'to_be_added' => json_decode(utils::ReadPostedParam("attr_{$sFormPrefix}{$sAttCode}_tba", '[]', 'raw_data'), true),
							   'to_be_removed' => json_decode(utils::ReadPostedParam("attr_{$sFormPrefix}{$sAttCode}_tbr", '[]', 'raw_data'), true) );
			}
			else
			{
				$value = utils::ReadPostedParam("attr_{$sFormPrefix}{$sAttCode}", null, 'raw_data');
			}
			if (!is_null($value))
			{
				$aValues[$sAttCode] = $value;
			}
		}

		$aErrors = array();
		$aFinalValues = array();
		foreach($this->GetWriteableAttList(array_keys($aValues), $aErrors, $sTargetState) as $sAttCode => $oAttDef)
		{
			$aFinalValues[$sAttCode] = $aValues[$sAttCode];
		}
		$this->UpdateObjectFromArray($aFinalValues);
		
		// Invoke extensions after the update of the object from the form
		foreach (MetaModel::EnumPlugins('iApplicationUIExtension') as $oExtensionInstance)
		{
			$oExtensionInstance->OnFormSubmit($this, $sFormPrefix);
		}
		
		return $aErrors;
	}

	/**
	 * Updates the object from a given page argument
	 */
	public function UpdateObjectFromArg($sArgName, $aAttList = null, $sTargetState = '')
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
		foreach($this->GetWriteableAttList(array_keys($aValues), $aErrors, $sTargetState) as $sAttCode => $oAttDef)
		{
			$aFinalValues[$sAttCode] = $aValues[$sAttCode];
		}
		$this->UpdateObjectFromArray($aFinalValues);
		return $aErrors;
	}

	public function DBInsertNoReload()
	{
		$res = parent::DBInsertNoReload();

		// Invoke extensions after insertion (the object must exist, have an id, etc.)
		foreach (MetaModel::EnumPlugins('iApplicationObjectExtension') as $oExtensionInstance)
		{
			$oExtensionInstance->OnDBInsert($this, self::GetCurrentChange());
		}

		return $res;
	}

	protected function DBCloneTracked_Internal($newKey = null)
	{
		$oNewObj = parent::DBCloneTracked_Internal($newKey);

		// Invoke extensions after insertion (the object must exist, have an id, etc.)
		foreach (MetaModel::EnumPlugins('iApplicationObjectExtension') as $oExtensionInstance)
		{
			$oExtensionInstance->OnDBInsert($oNewObj, self::GetCurrentChange());
		}
		return $oNewObj;
	}

	public function DBUpdate()
	{
		$res = parent::DBUpdate();

		// Invoke extensions after the update (could be before)
		foreach (MetaModel::EnumPlugins('iApplicationObjectExtension') as $oExtensionInstance)
		{
			$oExtensionInstance->OnDBUpdate($this, self::GetCurrentChange());
		}
		return $res;
	}

	protected static function BulkUpdateTracked_Internal(DBObjectSearch $oFilter, array $aValues)
	{
		// Todo - invoke the extension
		return parent::BulkUpdateTracked_Internal($oFilter, $aValues);
	}

	protected function DBDeleteTracked_Internal(&$oDeletionPlan = null)
	{
		// Invoke extensions before the deletion (the deletion will do some cleanup and we might loose some information
		foreach (MetaModel::EnumPlugins('iApplicationObjectExtension') as $oExtensionInstance)
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
		foreach (MetaModel::EnumPlugins('iApplicationObjectExtension') as $oExtensionInstance)
		{
			if ($oExtensionInstance->OnIsModified($this))
			{
				return true;
			}
		}
		return false;
	}

	public function DoCheckToWrite()
	{
		parent::DoCheckToWrite();

		// Plugins
		//
		foreach (MetaModel::EnumPlugins('iApplicationObjectExtension') as $oExtensionInstance)
		{
			$aNewIssues = $oExtensionInstance->OnCheckToWrite($this);
			if (count($aNewIssues) > 0)
			{
				$this->m_aCheckIssues = array_merge($this->m_aCheckIssues, $aNewIssues);
			}
		}

		// User rights
		//
		$aChanges = $this->ListChanges();
		if (count($aChanges) > 0)
		{
			$aForbiddenFields = array();
			foreach ($this->ListChanges() as $sAttCode => $value)
			{
				$bUpdateAllowed = UserRights::IsActionAllowedOnAttribute(get_class($this), $sAttCode, UR_ACTION_MODIFY, DBObjectSet::FromObject($this));
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
				$this->m_aCheckIssues[] = Dict::Format('UI:Delete:NotAllowedToUpdate_Fields',implode(', ', $aForbiddenFields));
			}
		}
	}

	protected function DoCheckToDelete(&$oDeletionPlan)
	{
		parent::DoCheckToDelete($oDeletionPlan);

		// Plugins
		//
		foreach (MetaModel::EnumPlugins('iApplicationObjectExtension') as $oExtensionInstance)
		{
			$aNewIssues = $oExtensionInstance->OnCheckToDelete($this);
			if (count($aNewIssues) > 0)
			{
				$this->m_aDeleteIssues = array_merge($this->m_aDeleteIssues, $aNewIssues);
			}
		}

		// User rights
		//
		$bDeleteAllowed = UserRights::IsActionAllowed(get_class($this), UR_ACTION_DELETE, DBObjectSet::FromObject($this));
		if (!$bDeleteAllowed)
		{
			// Security issue
			$this->m_bSecurityIssue = true;
			$this->m_aDeleteIssues[] = Dict::S('UI:Delete:NotAllowedToDelete');
		}
	}

	/**
	 * Special display where the case log uses the whole "screen" at the bottom of the "Properties" tab
	 */
	public function DisplayCaseLog(WebPage $oPage, $sAttCode, $sComment = '', $sPrefix = '', $bEditMode = false)
	{
		$oPage->SetCurrentTab(Dict::S('UI:PropertiesTab'));
		$sClass = get_class($this);
		if ($this->IsNew())
		{
			$iFlags = $this->GetInitialStateAttributeFlags($sAttCode);
		}
		else
		{
			$iFlags = $this->GetAttributeFlags($sAttCode);
		}
		if ( $iFlags & OPT_ATT_HIDDEN)
		{
			// The case log is hidden do nothing
		}
		else
		{
			$oAttDef = MetaModel::GetAttributeDef(get_class($this), $sAttCode);
			$sInputId = $this->m_iFormId.'_'.$sAttCode;
			
			if ((!$bEditMode) || ($iFlags & (OPT_ATT_READONLY|OPT_ATT_SLAVE)))
			{
				// Check if the attribute is not read-only because of a synchro...
				$aReasons = array();
				$sSynchroIcon = '';
				if ($iFlags & OPT_ATT_SLAVE)
				{
					$iSynchroFlags = $this->GetSynchroReplicaFlags($sAttCode, $aReasons);
					$sSynchroIcon = "&nbsp;<img id=\"synchro_$sInputId\" src=\"../images/transp-lock.png\" style=\"vertical-align:middle\"/>";
					$sTip = '';
					foreach($aReasons as $aRow)
					{
						$sTip .= "<p>Synchronized with {$aRow['name']} - {$aRow['description']}</p>";
					}
					$sTip = addslashes($sTip);
					$oPage->add_ready_script("$('#synchro_$sInputId').qtip( { content: '$sTip', show: 'mouseover', hide: 'mouseout', style: { name: 'dark', tip: 'leftTop' }, position: { corner: { target: 'rightMiddle', tooltip: 'leftTop' }} } );");
				}

				// Attribute is read-only
				$sHTMLValue = $this->GetAsHTML($sAttCode);
				$sHTMLValue .= '<input type="hidden" id="'.$sInputId.'" name="attr_'.$sPrefix.$sAttCode.'" value="'.htmlentities($this->GetEditValue($sAttCode), ENT_QUOTES, 'UTF-8').'"/>';
				$aFieldsMap[$sAttCode] = $sInputId;
				$sComment .= $sSynchroIcon;
			}
			else
			{
				$sValue = $this->Get($sAttCode);
				$sDisplayValue = $this->GetEditValue($sAttCode);
				$aArgs = array('this' => $this, 'formPrefix' => $sPrefix);
				$sHTMLValue = '';
				if ($sComment != '')
				{
					$sHTMLValue = '<span>'.$sComment.'</span><br/>';
				}
				$sHTMLValue .= "<span id=\"field_{$sInputId}\">".self::GetFormElementForField($oPage, $sClass, $sAttCode, $oAttDef, $sValue, $sDisplayValue, $sInputId, '', $iFlags, $aArgs).'</span>';
				$aFieldsMap[$sAttCode] = $sInputId;
				
			}
			//$aVal = array('label' => '<span title="'.$oAttDef->GetDescription().'">'.$oAttDef->GetLabel().'</span>', 'value' => $sHTMLValue, 'comments' => $sComments, 'infos' => $sInfos);
			$oPage->add('<fieldset><legend>'.$oAttDef->GetLabel().'</legend>');
			$oPage->add($sHTMLValue);
			$oPage->add('</fieldset>');
		}
	}
	
	public function GetExpectedAttributes($sCurrentState, $sStimulus, $bOnlyNewOnes)
	{
		$aTransitions = $this->EnumTransitions();
		$aStimuli = MetaModel::EnumStimuli(get_class($this));
		if (!isset($aTransitions[$sStimulus]))
		{
			// Invalid stimulus
			throw new ApplicationException(Dict::Format('UI:Error:Invalid_Stimulus_On_Object_In_State', $sStimulus,$this->GetName(),$this->GetStateLabel()));
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
				if ( !($aCurrentAttributes[$sAttCode] & (OPT_ATT_HIDDEN|OPT_ATT_READONLY)) )
				{
					$iExpectCode = $iExpectCode & ~(OPT_ATT_MUSTPROMPT|OPT_ATT_MUSTCHANGE); // Already prompted/changed, reset the flags
				}
				//TODO: better check if the attribute is not *null*
				if ( ($iExpectCode & OPT_ATT_MANDATORY) && ($this->Get($sAttCode) != ''))
				{
					$iExpectCode = $iExpectCode & ~(OPT_ATT_MANDATORY); // If the attribute is present, then no need to request its presence
				}

				$aComputedAttributes[$sAttCode] = $iExpectCode;								
			}

			$aComputedAttributes[$sAttCode] = $aComputedAttributes[$sAttCode] & ~(OPT_ATT_READONLY|OPT_ATT_HIDDEN); // Don't care about this form now

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
	 */	 	
	public static function DisplayBulkModifyForm($oP, $sClass, $aSelectedObj, $sCustomOperation, $sCancelUrl, $aExcludeAttributes = array(), $aContextData = array())
	{
		if (count($aSelectedObj) > 0)
		{
			$iAllowedCount = count($aSelectedObj);
			$sSelectedObj = implode(',', $aSelectedObj);

			$sOQL = "SELECT $sClass WHERE id IN (".$sSelectedObj.")";
			$oSet = new CMDBObjectSet(DBObjectSearch::FromOQL($sOQL));
			
			// Compute the distribution of the values for each field to determine which of the "scalar" fields are homogenous
			$aList = MetaModel::ListAttributeDefs($sClass);
			$aValues = array();
			foreach($aList as $sAttCode => $oAttDef)
			{
				if ($oAttDef->IsScalar())
				{
					$aValues[$sAttCode] = array();
				}
			}
			while($oObj = $oSet->Fetch())
			{
				foreach($aList as $sAttCode => $oAttDef)
				{
					if ($oAttDef->IsScalar() && $oAttDef->IsWritable())
					{
						$currValue = $oObj->Get($sAttCode);
						if ($oAttDef instanceof AttributeCaseLog)
						{
							$currValue = ' '; // Don't put an empty string, in case the field would be considered as mandatory...
						}
						if (is_object($currValue)) continue; // Skip non scalar values...
						if(!array_key_exists($currValue, $aValues[$sAttCode]))
						{
							$aValues[$sAttCode][$currValue] = array('count' => 1, 'display' => $oObj->GetAsHTML($sAttCode)); 
						}
						else
						{
							$aValues[$sAttCode][$currValue]['count']++; 
						}
					}
				}
			}
			// Now create an object that has values for the homogenous values only				
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
			$aDependsOn = array();
			$sFormPrefix = '2_';
			foreach($aList as $sAttCode => $oAttDef)
			{
				$aPrerequisites = MetaModel::GetPrequisiteAttributes($sClass, $sAttCode); // List of attributes that are needed for the current one
				if (count($aPrerequisites) > 0)
				{
					// When 'enabling' a field, all its prerequisites must be enabled too
					$sFieldList = "['{$sFormPrefix}".implode("','{$sFormPrefix}", $aPrerequisites)."']";
					$oP->add_ready_script("$('#enable_{$sFormPrefix}{$sAttCode}').bind('change', function(evt, sFormId) { return PropagateCheckBox( this.checked, $sFieldList, true); } );\n");
				}
				$aDependents = MetaModel::GetDependentAttributes($sClass, $sAttCode); // List of attributes that are needed for the current one
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
						$aComments[$sAttCode] = '<input type="checkbox" id="enable_'.$iFormId.'_'.$sAttCode.'" onClick="ToogleField(this.checked, \''.$iFormId.'_'.$sAttCode.'\')"/>';
						$aComments[$sAttCode] .= '<div class="multi_values" id="multi_values_'.$sAttCode.'"> ? </div>';
						$sReadyScript .=  'ToogleField(false, \''.$iFormId.'_'.$sAttCode.'\');'."\n";
					}
					else
					{
						$iCount = count($aValues[$sAttCode]);
						if ($iCount == 1)
						{
							// Homogenous value
							reset($aValues[$sAttCode]);
							$aKeys = array_keys($aValues[$sAttCode]);
							$currValue = $aKeys[0]; // The only value is the first key
							//echo "<p>current value for $sAttCode : $currValue</p>";
							$oDummyObj->Set($sAttCode, $currValue);
							$aComments[$sAttCode] = '';
							if ($sAttCode != MetaModel::GetStateAttributeCode($sClass))
							{
								$aComments[$sAttCode] .= '<input type="checkbox" checked id="enable_'.$iFormId.'_'.$sAttCode.'"  onClick="ToogleField(this.checked, \''.$iFormId.'_'.$sAttCode.'\')"/>';
							}
							$aComments[$sAttCode] .= '<div class="mono_value">1</div>';
						}
						else
						{
							// Non-homogenous value
							$aMultiValues = $aValues[$sAttCode];
							uasort($aMultiValues, 'MyComparison');
							$iMaxCount = 5;
							$sTip = "<p><b>".Dict::Format('UI:BulkModify_Count_DistinctValues', $iCount)."</b><ul>";
							$index = 0;
							foreach($aMultiValues as $sCurrValue => $aVal)
							{
								$sDisplayValue = empty($aVal['display']) ? '<i>'.Dict::S('Enum:Undefined').'</i>' : str_replace(array("\n", "\r"), " ", $aVal['display']);
								$sTip .= "<li>".Dict::Format('UI:BulkModify:Value_Exists_N_Times', $sDisplayValue, $aVal['count'])."</li>";
								$index++;
								if ($iMaxCount == $index)
								{
									$sTip .= "<li>".Dict::Format('UI:BulkModify:N_MoreValues', count($aMultiValues) - $iMaxCount)."</li>";
									break;
								}					
							}
							$sTip .= "</ul></p>";
							$sTip = addslashes($sTip);
							$sReadyScript .= "$('#multi_values_$sAttCode').qtip( { content: '$sTip', show: 'mouseover', hide: 'mouseout', style: { name: 'dark', tip: 'leftTop' }, position: { corner: { target: 'rightMiddle', tooltip: 'leftTop' }} } );";
	
							$oDummyObj->Set($sAttCode, null);
							$aComments[$sAttCode] = '';
							if ($sAttCode != MetaModel::GetStateAttributeCode($sClass))
							{
								$aComments[$sAttCode] .= '<input type="checkbox" id="enable_'.$iFormId.'_'.$sAttCode.'" onClick="ToogleField(this.checked, \''.$iFormId.'_'.$sAttCode.'\')"/>';
							}
							$aComments[$sAttCode] .= '<div class="multi_values" id="multi_values_'.$sAttCode.'">'.$iCount.'</div>';
						}
						$sReadyScript .=  'ToogleField('.(($iCount == 1) ? 'true': 'false').', \''.$iFormId.'_'.$sAttCode.'\');'."\n";
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
			$oP->add("<h1>".$oDummyObj->GetIcon()."&nbsp;".Dict::Format('UI:Modify_M_ObjectsOf_Class_OutOf_N', $iAllowedCount, $sClass, $iAllowedCount)."</h1>\n");
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
				'disable_plugins' => true
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
	 */	 	
	public static function DoBulkModify($oP, $sClass, $aSelectedObj, $sCustomOperation, $bPreview, $sCancelUrl, $aContextData = array())
	{
		$aHeaders = array(
			'form::select' => array('label' => "<input type=\"checkbox\" onClick=\"CheckAll('.selectList:not(:disabled)', this.checked);\"></input>", 'description' => Dict::S('UI:SelectAllToggle+')),
			'object' => array('label' => MetaModel::GetName($sClass), 'description' => Dict::S('UI:ModifiedObject')),
			'status' => array('label' => Dict::S('UI:BulkModifyStatus'), 'description' => Dict::S('UI:BulkModifyStatus+')),
			'errors' => array('label' => Dict::S('UI:BulkModifyErrors'), 'description' => Dict::S('UI:BulkModifyErrors+')),
		);
		$aRows = array();

		$oP->add("<div class=\"page_header\">\n");
		$oP->add("<h1>".MetaModel::GetClassIcon($sClass)."&nbsp;".Dict::Format('UI:Modify_N_ObjectsOf_Class', count($aSelectedObj), MetaModel::GetName($sClass))."</h1>\n");
		$oP->add("</div>\n");
		$oP->set_title(Dict::Format('UI:Modify_N_ObjectsOf_Class', count($aSelectedObj), $sClass));
		if (!$bPreview)
		{
			// Not in preview mode, do the update for real
			$sTransactionId = utils::ReadPostedParam('transaction_id', '');
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
			$oObj = MetaModel::GetObject($sClass, $iId);
			$aErrors = $oObj->UpdateObjectFromPostedForm('');
			$bResult = (count($aErrors) == 0);
			if ($bResult)
			{
				list($bResult, $aErrors) = $oObj->CheckToWrite(true /* Enforce Read-only fields */);
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
				'errors' => '<p>'.($bResult ? '': implode('</p><p>', $aErrors)).'</p>',
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
			$sFormAction = $_SERVER['SCRIPT_NAME']; // No parameter in the URL, the only parameter will be the ones passed through the form
			// Form to submit:
			$oP->add("<form method=\"post\" action=\"$sFormAction\" enctype=\"multipart/form-data\">\n");
			$aDefaults = utils::ReadParam('default', array());
			$oAppContext = new ApplicationContext();
			$oP->add($oAppContext->GetForForm());
			foreach ($aContextData as $sKey => $value)
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
							$oP->add("<input type=\"hidden\" name=\"{$sKey}[$vKey]\" value=\"".htmlentities($vValue, ENT_QUOTES, 'UTF-8')."\">\n");
						}
					}
					else
					{
						$oP->add("<input type=\"hidden\" name=\"$sKey\" value=\"".htmlentities($value, ENT_QUOTES, 'UTF-8')."\">\n");
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
				$oObj->DBDeleteTracked(CMDBObject::GetCurrentChange(), null, $oDeletionPlan);
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
				$oP->add("<h1>".Dict::Format('UI:Delete:ConfirmDeletionOf_Count_ObjectsOf_Class', count($aObjects), MetaModel::GetName($sClass))."</h1>\n");
			}
			// Explain what should be done
			//
			$aDisplayData = array();
			foreach ($oDeletionPlan->ListDeletes() as $sTargetClass => $aDeletes)
			{
				foreach ($aDeletes as $iId => $aData)
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
								$sConsequence = Dict::Format('UI:Delete:ShouldBeDeletedAtomaticallyButNotPossible', $aData['issue']);
							}
						}
						else
						{
							$sConsequence = Dict::Format('UI:Delete:MustBeDeletedManuallyButNotPossible', $aData['issue']);
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
			foreach ($oDeletionPlan->ListUpdates() as $sRemoteClass => $aToUpdate)
			{
				foreach ($aToUpdate as $iId => $aData)
				{
					$oToUpdate = $aData['to_reset'];
					if (array_key_exists('issue', $aData))
					{
						$sConsequence = Dict::Format('UI:Delete:CannotUpdateBecause_Issue', $aData['issue']);
					}
					else
					{
						$sConsequence = Dict::Format('UI:Delete:WillAutomaticallyUpdate_Fields', $aData['attributes_list']);
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
					$oP->p(Dict::Format('UI:Delete:Count_Objects/LinksReferencing_Object', $iImpactedIndirectly, $oObj->GetName()));
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
				$aDisplayConfig['consequence'] = array('label' => 'Consequence', 'description' => Dict::S('UI:Delete:Consequence+'));
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
				$oP->add("<input type=\"hidden\" name=\"transaction_id\" value=\"".utils::ReadParam('transaction_id')."\">\n");
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
					$oP->p('<h1>'.Dict::Format('UI:Delect:Confirm_Count_ObjectsOf_Class', count($aObjects), MetaModel::GetName($sClass)).'</h1>');
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
				foreach ($aContextData as $sKey => $value)
				{
					$oP->add("<input type=\"hidden\" name=\"{$sKey}\" value=\"$value\">\n");
				}
				$oP->add("<input type=\"hidden\" name=\"transaction_id\" value=\"".utils::GetNewTransactionId()."\">\n");
				$oP->add("<input type=\"hidden\" name=\"operation\" value=\"$sCustomOperation\">\n");
				$oP->add("<input type=\"hidden\" name=\"filter\" value=\"".$oFilter->Serialize()."\">\n");
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
				$oP->add("<h1>".Dict::Format('UI:Title:BulkDeletionOf_Count_ObjectsOf_Class', count($aObjects), MetaModel::GetName($sClass))."</h1>\n");		
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
			foreach ($oDeletionPlan->ListDeletes() as $sTargetClass => $aDeletes)
			{
				foreach ($aDeletes as $iId => $aData)
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
			foreach ($oDeletionPlan->ListUpdates() as $sTargetClass => $aToUpdate)
			{
				foreach ($aToUpdate as $iId => $aData)
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
					$oP->p(Dict::Format('UI:Delete:CleaningUpRefencesTo_Several_ObjectsOf_Class', count($aObjects), MetaModel::GetName($sClass)));
				}
				$aDisplayConfig = array();
				$aDisplayConfig['class'] = array('label' => 'Class', 'description' => '');
				$aDisplayConfig['object'] = array('label' => 'Object', 'description' => '');
				$aDisplayConfig['consequence'] = array('label' => 'Done', 'description' => Dict::S('UI:Delete:Done+'));
				$oP->table($aDisplayConfig, $aDisplayData);
			}
		}
	}
}
?>

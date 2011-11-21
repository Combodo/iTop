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
 * Abstract class that implements some common and useful methods for displaying
 * the objects
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
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
require_once(APPROOT.'/application/ui.passwordwidget.class.inc.php');
require_once(APPROOT.'/application/ui.extkeywidget.class.inc.php');
require_once(APPROOT.'/application/ui.htmleditorwidget.class.inc.php');

/**
 * All objects to be displayed in the application (either as a list or as details)
 * must implement this interface.
 */
interface iDisplay
{

	/**
	 * Maps the given context parameter name to the appropriate filter/search code for this class
	 * @param string $sContextParam Name of the context parameter, i.e. 'org_id'
	 * @return string Filter code, i.e. 'customer_id'
	 */
	public static function MapContextParam($sContextParam);
	/**
	 * This function returns a 'hilight' CSS class, used to hilight a given row in a table
	 * There are currently (i.e defined in the CSS) 4 possible values HILIGHT_CLASS_CRITICAL,
	 * HILIGHT_CLASS_WARNING, HILIGHT_CLASS_OK, HILIGHT_CLASS_NONE
	 * To Be overridden by derived classes
	 * @param void
	 * @return String The desired higlight class for the object/row
	 */
	public function GetHilightClass();
	/**
	 * Returns the relative path to the page that handles the display of the object
	 * @return string
	 */
	public static function GetUIPage();
	/**
	 * Displays the details of the object
	 */
	public function DisplayDetails(WebPage $oPage, $bEditMode = false);
}

abstract class cmdbAbstractObject extends CMDBObject implements iDisplay
{
	protected $m_iFormId; // The ID of the form used to edit the object (when in edition mode !)
	static $iGlobalFormId = 1;

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
	
	function DisplayBareHeader(WebPage $oPage, $bEditMode = false)
	{
		// Standard Header with name, actions menu and history block
		//

		// action menu
		$oSingletonFilter = new DBObjectSearch(get_class($this));
		$oSingletonFilter->AddCondition('id', $this->GetKey(), '=');
		$oBlock = new MenuBlock($oSingletonFilter, 'popup', false);
		$oBlock->Display($oPage, -1);
	
		// Master data sources
		$sSynchroIcon = '';
		$oReplicaSet = $this->GetMasterReplica();
		$bSynchronized = false;
		$oCreatorTask = null;
		$bCanBeDeletedByTask = false;
		$bCanBeDeletedByUser = true;
		$aMasterSources = array();
		if ($oReplicaSet->Count() > 0)
		{
			$bSynchronized = true;
			while($aData = $oReplicaSet->FetchAssoc())
			{
				// Assumption: $aData['datasource'] will not be null because the data source id is always set...
				$sApplicationURL = $aData['datasource']->GetApplicationUrl($this, $aData['replica']);
				$sLink = $aData['datasource']->GetName();
				if (!empty($sApplicationURL))
				{
					$sLink = "<a href=\"$sApplicationURL\" target=\"_blank\">".$aData['datasource']->GetName()."</a>";
				}
				if ($aData['replica']->Get('status_dest_creator') == 1)
				{
					$oCreatorTask = $aData['datasource'];
					$bCreatedByTask = true;
				}
				else
				{
					$bCreatedByTask = false;
				}
				if ($bCreatedByTask)
				{
					$sDeletePolicy = $aData['datasource']->Get('delete_policy');
					if (($sDeletePolicy == 'delete') || ($sDeletePolicy == 'update_then_delete'))
					{
						$bCanBeDeletedByTask = true;
					}
					$sUserDeletePolicy = $aData['datasource']->Get('user_delete_policy');
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
				$aMasterSources[$aData['datasource']->GetKey()]['datasource'] = $aData['datasource'];
				$aMasterSources[$aData['datasource']->GetKey()]['url'] = $sLink;
				$aMasterSources[$aData['datasource']->GetKey()]['last_synchro'] = $aData['replica']->Get('status_last_seen');
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
			$oPage->add_ready_script("$('#synchro_icon').qtip( { content: '$sTip', show: 'mouseover', hide: 'unfocus', style: { name: 'dark', tip: 'leftTop' }, position: { corner: { target: 'rightMiddle', tooltip: 'leftTop' }} } );");
		}
	
		$oPage->add("<div class=\"page_header\"><h1>".$this->GetIcon()."&nbsp;\n");
		$oPage->add(MetaModel::GetName(get_class($this)).": <span class=\"hilite\">".$this->GetName()."</span>$sSynchroIcon</h1>\n");
		$oPage->add("</div>\n");
		
	}

	function DisplayBareHistory(WebPage $oPage, $bEditMode = false)
	{
		// history block (with as a tab)
		$oHistoryFilter = new DBObjectSearch('CMDBChangeOp');
		$oHistoryFilter->AddCondition('objkey', $this->GetKey(), '=');
		$oHistoryFilter->AddCondition('objclass', get_class($this), '=');
		$oBlock = new HistoryBlock($oHistoryFilter, 'table', false);
		$oBlock->Display($oPage, -1);
	}

	function DisplayBareProperties(WebPage $oPage, $bEditMode = false, $sPrefix = '', $aExtraParams = array())
	{
		$aFieldsMap = $this->GetBareProperties($oPage, $bEditMode, $sPrefix, $aExtraParams);		

		// Special case to display the case log, if any...
		// WARNING: if you modify the loop below, also check the corresponding code in UpdateObject and DisplayModifyForm
		foreach(MetaModel::ListAttributeDefs(get_class($this)) as $sAttCode => $oAttDef)
		{
			if ($oAttDef instanceof AttributeCaseLog)
			{
				$sComment = (isset($aExtraParams['fieldsComments'][$sAttCode])) ? $aExtraParams['fieldsComments'][$sAttCode] : '';
				$this->DisplayCaseLog($oPage, $sAttCode, $sComment, $sPrefix, $bEditMode);
			}
		}

		foreach (MetaModel::EnumPlugins('iApplicationUIExtension') as $oExtensionInstance)
		{
			$oExtensionInstance->OnDisplayProperties($this, $oPage, $bEditMode);
		}
		
		return $aFieldsMap;
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
			
			$oSet = new DBObjectSet($this->Get($sAttCode)->GetFilter());
			$iCount = $oSet->Count();
			$sCount = '';
			if ($iCount != 0)
			{
				$sCount = " ($iCount)";
			}
			$oPage->SetCurrentTab($oAttDef->GetLabel().$sCount);
			if ($bEditMode)
			{
				if ($this->IsNew())
				{
					$iFlags = $this->GetInitialStateAttributeFlags($sAttCode);
				}
				else
				{
					$iFlags = $this->GetAttributeFlags($sAttCode);
				}
				$sInputId = $this->m_iFormId.'_'.$sAttCode;
				if (get_class($oAttDef) == 'AttributeLinkedSet')
				{
					// 1:n links
					$sTargetClass = $oAttDef->GetLinkedClass();
					if ($this->IsNew())
					{
						$oPage->p(Dict::Format('UI:BeforeAdding_Class_ObjectsSaveThisObject', MetaModel::GetName($sTargetClass)));
					}
					else
					{
						$oPage->p(MetaModel::GetClassIcon($sTargetClass)."&nbsp;".$oAttDef->GetDescription());
	
						$oFilter = new DBObjectSearch($sTargetClass);
						$oFilter->AddCondition($oAttDef->GetExtKeyToMe(), $this->GetKey(),'=');
	
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
							'menu' => true,
							'default' => $aDefaults,
							);
	
						$oBlock = new DisplayBlock($oFilter, 'list', false);
						$oBlock->Display($oPage, $sInputId, $aParams);
					}
				}
				else // get_class($oAttDef) == 'AttributeLinkedSetIndirect'
				{
					// n:n links
					$sLinkedClass = $oAttDef->GetLinkedClass();
					$oLinkingAttDef = 	MetaModel::GetAttributeDef($sLinkedClass, $oAttDef->GetExtKeyToRemote());
					$sTargetClass = $oLinkingAttDef->GetTargetClass();
					$oPage->p(MetaModel::GetClassIcon($sTargetClass)."&nbsp;".$oAttDef->GetDescription().'<span id="busy_'.$sInputId.'"></span>');

					$sValue = $this->Get($sAttCode);
					$sDisplayValue = $this->GetEditValue($sAttCode);
					$aArgs = array('this' => $this);
					$sHTMLValue = "<span id=\"field_{$sInputId}\">".self::GetFormElementForField($oPage, $sClass, $sAttCode, $oAttDef, $sValue, $sDisplayValue, $sInputId, '', $iFlags, $aArgs).'</span>';
					$aFieldsMap[$sAttCode] = $sInputId;
					$oPage->add($sHTMLValue);
				}
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
			// Get the actual class of the current object
			// And look for triggers referring to it
			// If any trigger has been found then display a tab with notifications
			//			
			$sClass = get_class($this);
			$sClassList = implode("', '", MetaModel::EnumParentClasses($sClass, ENUM_PARENT_CLASSES_ALL));
			$oTriggerSet = new CMDBObjectSet(DBObjectSearch::FromOQL("SELECT TriggerOnObject AS T WHERE T.target_class IN ('$sClassList')"));
			if ($oTriggerSet->Count() > 0)
			{
				// Display notifications regarding the object
				$iId = $this->GetKey();
				$oNotifSearch = DBObjectSearch::FromOQL("SELECT EventNotificationEmail AS Ev JOIN TriggerOnObject AS T ON Ev.trigger_id = T.id WHERE T.target_class IN ('$sClassList') AND Ev.object_id = $iId");
				$oNotifSet = new DBObjectSet($oNotifSearch);
				$sCount = ($oNotifSet->Count() > 0) ? ' ('.$oNotifSet->Count().')' : '';
				$oPage->SetCurrentTab(Dict::S('UI:NotificationsTab').$sCount);
				$oBlock = new DisplayBlock($oNotifSearch, 'list', false);
				$oBlock->Display($oPage, 'notifications', array('menu' => false));
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
						$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
						if ( (!$oAttDef->IsLinkSet()) && (($iFlags & OPT_ATT_HIDDEN) == 0))
						{
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
									$sInputId = $this->m_iFormId.'_'.$sAttCode;
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
								$val = array('label' => '<span title="'.$oAttDef->GetDescription().'">'.$oAttDef->GetLabel().'</span>', 'value' => $this->GetAsHTML($sAttCode), 'comments' => $sComments, 'infos' => $sInfos);			
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
			$oPage->SetCurrentTab(Dict::S('UI:HistoryTab'));
			$this->DisplayBareHistory($oPage, $bEditMode);
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
		$aExtraFields = isset($aExtraParams['extra_fields']) ? explode(',', trim($aExtraParams['extra_fields'])) : array();
		
		$sHtml = '';
		$oAppContext = new ApplicationContext();
		$sClassName = $oSet->GetFilter()->GetClass();
		$aAttribs = array();
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

		// Load only the requested columns
		$sClassAlias = $oSet->GetFilter()->GetClassAlias();
		$oSet->OptimizeColumnLoad(array($sClassAlias => $aList));

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
		if ($bSelectMode)
		{
			if (!$bSingleSelectMode)
			{
				$aAttribs['form::select'] = array('label' => "<input type=\"checkbox\" onClick=\"CheckAll('.selectList{$iListId}:not(:disabled)', this.checked);\" class=\"checkAll\"></input>", 'description' => Dict::S('UI:SelectAllToggle+'));
			}
			else
			{
				$aAttribs['form::select'] = array('label' => "", 'description' => '');
			}
		}
		if ($bViewLink)
		{
			$aAttribs['key'] = array('label' => MetaModel::GetName($sClassName), 'description' => '');
		}
		foreach($aList as $sAttCode)
		{
			$aAttribs[$sAttCode] = array('label' => MetaModel::GetLabel($sClassName, $sAttCode), 'description' => MetaModel::GetDescription($sClassName, $sAttCode));
		}
		$aValues = array();
		$bDisplayLimit = isset($aExtraParams['display_limit']) ? $aExtraParams['display_limit'] : true;
		$iMaxObjects = -1;
		//if ($bDisplayLimit && $bTruncated)
		//{
			if ($bDisplayLimit && ($oSet->Count() > MetaModel::GetConfig()->GetMaxDisplayLimit()))
			{
				$iMaxObjects = MetaModel::GetConfig()->GetMinDisplayLimit();
				$oSet->SetLimit($iMaxObjects);
			}
		//}
		$oSet->Seek(0);
		while (($oObj = $oSet->Fetch()) && ($iMaxObjects != 0))
		{
			$aRow = array();
			$sHilightClass = $oObj->GetHilightClass();
			if ($sHilightClass != '')
			{
				$aRow['@class'] = $sHilightClass;	
			}
			if ($bViewLink)
			{
				$aRow['key'] = $oObj->GetHyperLink();
			}
			if ($bSelectMode)
			{
				if (array_key_exists('selection_enabled', $aExtraParams) && isset($aExtraParams['selection_enabled'][$oObj->GetKey()]))
				{
					$sDisabled = ($aExtraParams['selection_enabled'][$oObj->GetKey()]) ? '' : ' disabled="disabled"';
				}
				else
				{
					$sDisabled = '';
				}
				if ($bSingleSelectMode)
				{
					$aRow['form::select'] = "<input type=\"radio\" $sDisabled class=\"selectList{$iListId}\" name=\"selectObject\" value=\"".$oObj->GetKey()."\"></input>";
				}
				else
				{
				$aRow['form::select'] = "<input type=\"checkBox\" $sDisabled class=\"selectList{$iListId}\" name=\"selectObject[]\" value=\"".$oObj->GetKey()."\"></input>";
				}
			}
			foreach($aList as $sAttCode)
			{
				$aRow[$sAttCode] = $oObj->GetAsHTML($sAttCode);
			}
			$aValues[] = $aRow;
			$iMaxObjects--;
		}
		$sHtml .= '<table class="listContainer">';
		$sColspan = '';
//		if (isset($aExtraParams['block_id']))
//		{
//			$divId = $aExtraParams['block_id'];
//		}
//		else
//		{
//			$divId = 'missingblockid';
//		}
		$sFilter = $oSet->GetFilter()->serialize();
		$iMinDisplayLimit = MetaModel::GetConfig()->GetMinDisplayLimit();
		$sCollapsedLabel = Dict::Format('UI:TruncatedResults', $iMinDisplayLimit, $oSet->Count());
		$sLinkLabel = Dict::S('UI:DisplayAll');
		foreach($oSet->GetFilter()->GetInternalParams() as $sName => $sValue)
		{
			$aExtraParams['query_params'][$sName] = $sValue;
		}

		if ($bDisplayMenu)
		{
			$oMenuBlock = new MenuBlock($oSet->GetFilter());
			$sColspan = 'colspan="2"';
			$aMenuExtraParams = $aExtraParams;
			if (!empty($sLinkageAttribute))
			{
				//$aMenuExtraParams['linkage'] = $sLinkageAttribute;
				$aMenuExtraParams = $aExtraParams;
			}
			$sHtml .= $oMenuBlock->GetRenderContent($oPage, $aMenuExtraParams, $iListId);
			$sHtml .= '</td></tr>';
		}
		$sHtml .= "<tr><td $sColspan>";
		$sHtml .= $oPage->GetTable($aAttribs, $aValues);
		$sHtml .= '</td></tr>';
		$sHtml .= '</table>';
		$iCount = $oSet->Count();
		if ($bSelectMode)
		{
			$sHeader = Dict::Format('UI:Pagination:HeaderSelection', '<span id="total">'.$iCount.'</span>', '<span class="selectedCount">0</span>');
		}
		else
		{
			$sHeader = Dict::Format('UI:Pagination:HeaderNoSelection', '<span id="total">'.$iCount.'</span>');
		}
		if ($bDisplayLimit && ($oSet->Count() > MetaModel::GetConfig()->GetMaxDisplayLimit()))
		{
			$sCombo = '<select class="pagesize">';
			for($iPage = 1; $iPage < 5; $iPage++)
			{
				$sSelected = '';
				if ($iPage == 1)
				{
					$sSelected = 'selected="selected"';
				}
				$iNbItems = $iPage * MetaModel::GetConfig()->GetMinDisplayLimit();
				$sCombo .= "<option  $sSelected value=\"$iNbItems\">$iNbItems</option>";
			}
			$sCombo .= "<option  $sSelected value=\"-1\">".Dict::S('UI:Pagination:All')."</option>";
			$sCombo .= '</select>';
			$sPages = Dict::S('UI:Pagination:PagesLabel');
			$sPageSizeCombo = Dict::Format('UI:Pagination:PageSize', $sCombo);
$sHtml =
<<<EOF
<div id="pager{$iListId}" class="pager">
		<p>$sHeader</p>
		<p><table class="pagination"><tr><td>$sPages</td><td><img src="../images/first.png" class="first"/></td>
		<td><img src="../images/prev.png" class="prev"/></td>
		<td><span id="index"></span></td>
		<td><img src="../images/next.png" class="next"/></td>
		<td><img src="../images/last.png" class="last"/></td>
		<td>$sPageSizeCombo</td>
		<td><span id="loading">&nbsp;</span></td>
		</tr>
		</table>
		
		<input type="hidden" name="selectionMode" value="positive"></input>
</div>
EOF
.$sHtml;
			$aArgs = $oSet->GetArgs();
			$sExtraParams = addslashes(str_replace('"', "'", json_encode(array_merge($aExtraParams, $aArgs)))); // JSON encode, change the style of the quotes and escape them
			$sSelectMode = '';
			$sHeaders = '';
			if ($bSelectMode)
			{
				$sSelectMode = $bSingleSelectMode ? 'single' : 'multiple';
				$sHeaders = 'headers: { 0: {sorter: false}},';
			}
			$sDisplayKey = ($bViewLink) ? 'true' : 'false';
			$sDisplayList = json_encode($aList);
			$sCssCount = isset($aExtraParams['cssCount']) ? ", cssCount: '{$aExtraParams['cssCount']}'" : '';
			$iPageSize = MetaModel::GetConfig()->GetMinDisplayLimit();
			$oPage->add_ready_script("$('#{$iListId} table.listResults').tablesorter( { $sHeaders widgets: ['myZebra', 'truncatedList']} ).tablesorterPager({container: $('#pager{$iListId}'), totalRows:$iCount, size: $iPageSize, filter: '$sFilter', extra_params: '$sExtraParams', select_mode: '$sSelectMode', displayKey: $sDisplayKey, displayList: $sDisplayList $sCssCount});\n");
		}
		else
		{
$sHtml =
<<<EOF
<div id="pager{$iListId}" class="pager">
		<p>$sHeader</p>
</div>
EOF
.$sHtml;
			$sHeaders = '';
			if ($bSelectMode)
			{
				$sHeaders = 'headers: { 0: {sorter: false}},';
			}
			$oPage->add_ready_script("$('#{$iListId} table.listResults').tableHover().tablesorter( { $sHeaders widgets: ['myZebra', 'truncatedList']} );\n");
			// Manage how we update the 'Ok/Add' buttons that depend on the number of selected items
			if (isset($aExtraParams['cssCount']))
			{
				$sCssCount = $aExtraParams['cssCount'];
				if ($bSingleSelectMode)
				{
					$sSelectSelector = ":radio[name^=selectObj]";
				}
				else
				{
					$sSelectSelector = ":checkbox[name^=selectObj]";
				}
				$oPage->add_ready_script(
<<<EOF
	$('#{$iListId} table.listResults $sSelectSelector').change(function() {
		var c = $('{$sCssCount}');							
		var v = $('#{$iListId} table.listResults $sSelectSelector:checked').length;
		c.val(v);
		$('#{$iListId} .selectedCount').text(v);
		c.trigger('change');	
	});
EOF
				);
			}
		}

		return $sHtml;
	}
	
	public static function GetDisplayExtendedSet(WebPage $oPage, CMDBObjectSet $oSet, $aExtraParams = array())
	{
		static $iListId = 0;
		$iListId++;
		$aList = array();
		
		// Initialize and check the parameters
		$bViewLink = isset($aExtraParams['view_link']) ? $aExtraParams['view_link'] : true;
		$bDisplayMenu = isset($aExtraParams['menu']) ? $aExtraParams['menu'] == true : true;
		// Check if there is a list of aliases to limit the display to...
		$aDisplayAliases = isset($aExtraParams['display_aliases']) ? explode(',', $aExtraParams['display_aliases']) : array();
		
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
		foreach($aAuthorizedClasses as $sAlias => $sClassName) // TO DO: check if the user has enough rights to view the classes of the list...
		{
			$aList[$sClassName] = MetaModel::GetZListItems($sClassName, 'list');
			if ($bViewLink)
			{
				$aAttribs['key_'.$sAlias] = array('label' => MetaModel::GetName($sClassName), 'description' => '');
			}
			foreach($aList[$sClassName] as $sAttCode)
			{
				$aAttribs[$sAttCode.'_'.$sAlias] = array('label' => MetaModel::GetLabel($sClassName, $sAttCode), 'description' => MetaModel::GetDescription($sClassName, $sAttCode));
			}
		}
		// Load only the requested columns
		$aAttToLoad = array(); // attributes to load
		foreach($aAuthorizedClasses as $sAlias => $sClassName)
		{
			foreach($aList[$sClassName] as $sAttCode)
			{
				$aAttToLoad[$sAlias][] = $sAttCode;
			}
		}
		$oSet->OptimizeColumnLoad($aAttToLoad);

		$aValues = array();
		$oSet->Seek(0);
		$bDisplayLimit = isset($aExtraParams['display_limit']) ? $aExtraParams['display_limit'] : true;
		$iMaxObjects = -1;
		if ($bDisplayLimit)
		{
			if ($oSet->Count() > MetaModel::GetConfig()->GetMaxDisplayLimit())
			{
				$iMaxObjects = MetaModel::GetConfig()->GetMinDisplayLimit();
			}
		}
		while (($aObjects = $oSet->FetchAssoc()) && ($iMaxObjects != 0))
		{
			$aRow = array();
			foreach($aAuthorizedClasses as $sAlias => $sClassName) // TO DO: check if the user has enough rights to view the classes of the list...
			{
				if ($bViewLink)
				{
					if (is_null($aObjects[$sAlias]))
					{
						$aRow['key_'.$sAlias] = '';
					}
					else
					{
						$aRow['key_'.$sAlias] = $aObjects[$sAlias]->GetHyperLink();
					}
				}
				foreach($aList[$sClassName] as $sAttCode)
				{
					if (is_null($aObjects[$sAlias]))
					{
						$aRow[$sAttCode.'_'.$sAlias] = '';
					}
					else
					{
						$aRow[$sAttCode.'_'.$sAlias] = $aObjects[$sAlias]->GetAsHTML($sAttCode);
					}
				}
			}
			$aValues[] = $aRow;
			$iMaxObjects--;
		}
		$sHtml .= '<table class="listContainer">';
		$sColspan = '';
		if ($bDisplayMenu)
		{
			$oMenuBlock = new MenuBlock($oSet->GetFilter());
			$sColspan = 'colspan="2"';
			$aMenuExtraParams = $aExtraParams;
			if (!empty($sLinkageAttribute))
			{
				$aMenuExtraParams = $aExtraParams;
			}
			if ($bDisplayLimit && ($oSet->Count() > MetaModel::GetConfig()->GetMaxDisplayLimit()))
			{
				// list truncated
				$divId = $aExtraParams['block_id'];
				$sFilter = $oSet->GetFilter()->serialize();
				$aExtraParams['display_limit'] = false; // To expand the full list
				$sExtraParams = addslashes(str_replace('"', "'", json_encode($aExtraParams))); // JSON encode, change the style of the quotes and escape them
				$sHtml .= '<tr class="containerHeader"><td>'.Dict::Format('UI:TruncatedResults', MetaModel::GetConfig()->GetMinDisplayLimit(), $oSet->Count()).'&nbsp;&nbsp;<span style=\"cursor:pointer;\" onClick="Javascript:ReloadTruncatedList(\''.$divId.'\', \''.$sFilter.'\', \''.$sExtraParams.'\');">'.Dict::S('UI:DisplayAll').'</span></td><td>';
				$oPage->add_ready_script("$('#{$divId} table.listResults').addClass('truncated');");
				$oPage->add_ready_script("$('#{$divId} table.listResults tr:last td').addClass('truncated');");
			}
			else
			{
				// Full list
				$sHtml .= '<tr class="containerHeader"><td>&nbsp;'.Dict::Format('UI:CountOfResults', $oSet->Count()).'</td><td>';
			}
			$sHtml .= $oMenuBlock->GetRenderContent($oPage, $aMenuExtraParams, $aMenuExtraParams['currentId']);
			$sHtml .= '</td></tr>';
		}
		$sHtml .= "<tr><td $sColspan>";
		$sHtml .= $oPage->GetTable($aAttribs, $aValues);
		$sHtml .= '</td></tr>';
		$sHtml .= '</table>';
		return $sHtml;
	}
	
	static function DisplaySetAsCSV(WebPage $oPage, CMDBObjectSet $oSet, $aParams = array())
	{
		$oPage->add(self::GetSetAsCSV($oSet, $aParams));
	}
	
	static function GetSetAsCSV(DBObjectSet $oSet, $aParams = array())
	{
		$sSeparator = isset($aParams['separator']) ? $aParams['separator'] : ','; // default separator is comma
		$sTextQualifier = isset($aParams['text_qualifier']) ? $aParams['text_qualifier'] : '"'; // default text qualifier is double quote
		$aFields = null;
		if (isset($aParams['fields']) && (strlen($aParams['fields']) > 0))
		{
			$aFields = explode(',', $aParams['fields']);
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
			foreach(MetaModel::ListAttributeDefs($sClassName) as $sAttCode => $oAttDef)
			{
				if (is_null($aFields) || (count($aFields) == 0))
				{
					// Standard list of attributes (no link sets)
					if ($oAttDef->IsScalar() && ($oAttDef->IsWritable() || $oAttDef->IsExternalField()))
					{
						$aList[$sClassName][$sAttCode] = $oAttDef;
					}
				}
				else
				{
					// User defined list of attributes
					if (in_array($sAttCode, $aFields))
					{
						$aList[$sClassName][$sAttCode] = $oAttDef;
					}
				}
			}
			$aHeader[] = 'id';
			foreach($aList[$sClassName] as $sAttCode => $oAttDef)
			{
				$sStar = '';
				if ($oAttDef->IsExternalField())
				{
					$sExtKeyLabel = MetaModel::GetLabel($sClassName, $oAttDef->GetKeyAttCode());
					$oExtKeyAttDef = MetaModel::GetAttributeDef($sClassName, $oAttDef->GetKeyAttCode());
					if (!$oExtKeyAttDef->IsNullAllowed() && isset($aParams['showMandatoryFields']))
					{
						$sStar = '*';
					}
					$sRemoteAttLabel = MetaModel::GetLabel($oAttDef->GetTargetClass(), $oAttDef->GetExtAttCode());
					$oTargetAttDef = MetaModel::GetAttributeDef($oAttDef->GetTargetClass(), $oAttDef->GetExtAttCode());
					$sSuffix = '';
					if ($oTargetAttDef->IsExternalKey())
					{
						$sSuffix = '->id';
					}
					
					$aHeader[] = $sExtKeyLabel.'->'.$sRemoteAttLabel.$sSuffix.$sStar;
				}
				else
				{
					if (!$oAttDef->IsNullAllowed() && isset($aParams['showMandatoryFields']))
					{
						$sStar = '*';
					}
					$aHeader[] = MetaModel::GetLabel($sClassName, $sAttCode).$sStar;
				}
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
				if (is_null($oObj))
				{
					$aRow[] = '';
				}
				else
				{
					$aRow[] = $oObj->GetKey();
				}
				foreach($aList[$sClassName] as $sAttCode => $oAttDef)
				{
					if (is_null($oObj))
					{
						$aRow[] = '';
					}
					else
					{
						$aRow[] = $oObj->GetAsCSV($sAttCode, $sSeparator, $sTextQualifier);
					}
				}
			}
			$sHtml .= implode($sSeparator, $aRow)."\n";
		}
		
		return $sHtml;
	}
	
	static function DisplaySetAsXML(WebPage $oPage, CMDBObjectSet $oSet, $aParams = array())
	{
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
		$aList[$sClassName] = MetaModel::GetZListItems($sClassName, 'details');
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
							$sValue = $oObj->GetAsXML($sAttCode);
							$oPage->add("<$sAttCode>$sValue</$sAttCode>\n");
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
		foreach($aFilterCriteria as $aCriteria)
		{
			$aMapCriteria[$aCriteria['filtercode']][] = array('value' => $aCriteria['value'], 'opcode' => $aCriteria['opcode']);
		}
		$aList = MetaModel::GetZListItems($sClassName, 'standard_search');
		foreach($aList as $sFilterCode)
		{
			//$oAppContext->Reset($sFilterCode); // Make sure the same parameter will not be passed twice
			$sHtml .= '<span style="white-space: nowrap;padding:5px;display:inline-block;">';
			$sFilterValue = '';
			$sFilterValue = utils::ReadParam($sFilterCode, '', false, 'raw_data');
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
					if ($sFilterCode != 'company')
					{
						$oUnlimitedFilter->AddCondition($sFilterCode, $sFilterValue, $sFilterOpCode);
					}
				}
			}

			$oAttDef = MetaModel::GetAttributeDef($sClassName, $sFilterCode);
			if ($oAttDef->IsExternalKey())
			{
				$sTargetClass = $oAttDef->GetTargetClass();
				$oAllowedValues = new DBObjectSet(new DBObjectSearch($sTargetClass));

				$iFieldSize = $oAttDef->GetMaxSize();
				$iMaxComboLength = $oAttDef->GetMaximumComboLength();
				$sHtml .= "<label>".MetaModel::GetFilterLabel($sClassName, $sFilterCode).":</label>&nbsp;";
				$aExtKeyParams = $aExtraParams;
				$aExtKeyParams['iFieldSize'] = $oAttDef->GetMaxSize();
				$aExtKeyParams['iMinChars'] = $oAttDef->GetMinAutoCompleteChars();
				$sHtml .= UIExtKeyWidget::DisplayFromAttCode($oPage, $sFilterCode, $sClassName, $oAttDef->GetLabel(), $oAllowedValues, $sFilterValue, $sSearchFormId.'search_'.$sFilterCode, false, $sFilterCode, '', $aExtKeyParams, true);
			}
			else
			{
				$aAllowedValues = MetaModel::GetAllowedValues_flt($sClassName, $sFilterCode, $aExtraParams);
				if (is_null($aAllowedValues))
				{
					// Any value is possible, display an input box
					$sHtml .= "<label>".MetaModel::GetFilterLabel($sClassName, $sFilterCode).":</label>&nbsp;<input class=\"textSearch\" name=\"$sFilterCode\" value=\"$sFilterValue\"/>\n";
				}
				else
				{
					//Enum field, display a combo
					$sValue = "<select name=\"$sFilterCode\">\n";
					$sValue .= "<option value=\"\">".Dict::S('UI:SearchValue:Any')."</option>\n";
					foreach($aAllowedValues as $key => $value)
					{
						if ($sFilterValue == $key)
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
		foreach($aExtraParams as $sName => $sValue)
		{
			$sHtml .= "<input type=\"hidden\" name=\"$sName\" value=\"$sValue\" />\n";
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
			$sHtml .= "<input type=\"hidden\" name=\"$sName\" value=\"$sValue\" />\n";
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
			$sHelpText = $oAttDef->GetHelpOnEdition();
			$aEventsList = array();
			switch($oAttDef->GetEditClass())
			{
				case 'Date':
				$aEventsList[] ='validate';
				$aEventsList[] ='keyup';
				$aEventsList[] ='change';
				$sHTMLValue = "<input title=\"$sHelpText\" class=\"date-pick\" type=\"text\" size=\"12\" name=\"attr_{$sFieldPrefix}{$sAttCode}{$sNameSuffix}\" value=\"".htmlentities($value, ENT_QUOTES, 'UTF-8')."\" id=\"$iId\"/>&nbsp;{$sValidationField}";
				break;

				case 'DateTime':
				$aEventsList[] ='validate';
				$aEventsList[] ='keyup';
				$aEventsList[] ='change';
				$sHTMLValue = "<input title=\"$sHelpText\" class=\"date-pick\" type=\"text\" size=\"20\" name=\"attr_{$sFieldPrefix}{$sAttCode}{$sNameSuffix}\" value=\"".htmlentities($value, ENT_QUOTES, 'UTF-8')."\" id=\"$iId\"/>&nbsp;{$sValidationField}";
				break;

				case 'Duration':
				$aEventsList[] ='validate';
				$aEventsList[] ='change';
				$oPage->add_ready_script("$('#{$iId}_d').bind('keyup change', function(evt, sFormId) { return UpdateDuration('$iId'); });");
				$oPage->add_ready_script("$('#{$iId}_h').bind('keyup change', function(evt, sFormId) { return UpdateDuration('$iId'); });");
				$oPage->add_ready_script("$('#{$iId}_m').bind('keyup change', function(evt, sFormId) { return UpdateDuration('$iId'); });");
				$oPage->add_ready_script("$('#{$iId}_s').bind('keyup change', function(evt, sFormId) { return UpdateDuration('$iId'); });");
				$aVal = AttributeDuration::SplitDuration($value);
				$sDays = "<input title=\"$sHelpText\" type=\"text\" size=\"3\" name=\"attr_{$sFieldPrefix}{$sAttCode}[d]{$sNameSuffix}\" value=\"{$aVal['days']}\" id=\"{$iId}_d\"/>";
				$sHours = "<input title=\"$sHelpText\" type=\"text\" size=\"2\" name=\"attr_{$sFieldPrefix}{$sAttCode}[h]{$sNameSuffix}\" value=\"{$aVal['hours']}\" id=\"{$iId}_h\"/>";
				$sMinutes = "<input title=\"$sHelpText\" type=\"text\" size=\"2\" name=\"attr_{$sFieldPrefix}{$sAttCode}[m]{$sNameSuffix}\" value=\"{$aVal['minutes']}\" id=\"{$iId}_m\"/>";
				$sSeconds = "<input title=\"$sHelpText\" type=\"text\" size=\"2\" name=\"attr_{$sFieldPrefix}{$sAttCode}[s]{$sNameSuffix}\" value=\"{$aVal['seconds']}\" id=\"{$iId}_s\"/>";
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
					$sHTMLValue = "<table><tr><td><textarea class=\"resizable\" title=\"$sHelpText\" name=\"attr_{$sFieldPrefix}{$sAttCode}{$sNameSuffix}\" rows=\"8\" cols=\"40\" id=\"$iId\" $sStyle>".htmlentities($sEditValue, ENT_QUOTES, 'UTF-8')."</textarea></td><td>{$sValidationField}</td></tr></table>";
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
					$sPreviousLog = is_object($value) ? $value->GetAsHTML() : '';
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
					$aEventsList[] ='validate';
					$aEventsList[] ='change';
					$oWidget = new UILinksWidget($sClass, $sAttCode, $iId, $sNameSuffix, $oAttDef->DuplicatesAllowed(), $aArgs);
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
					$sHTMLValue = UIExtKeyWidget::DisplayFromAttCode($oPage, $sAttCode, $sClass, $oAttDef->GetLabel(), $oAllowedValues, $value, $iId, $bMandatory, $sFieldName, $sFormPrefix, $aArgs);
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
						$sHTMLValue = "<input title=\"$sHelpText\" type=\"text\" size=\"30\" maxlength=\"$iFieldSize\" name=\"attr_{$sFieldPrefix}{$sAttCode}{$sNameSuffix}\" value=\"".htmlentities($value, ENT_QUOTES, 'UTF-8')."\" id=\"$iId\"/>&nbsp;{$sValidationField}";
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
				$oPage->add_ready_script("$('#$iId').bind('".implode(' ', $aEventsList)."', function(evt, sFormId) { return ValidateField('$iId', '$sPattern', $bMandatory, sFormId, $sNullValue) } );\n"); // Bind to a custom event: validate
			}
			$aDependencies = MetaModel::GetDependentAttributes($sClass, $sAttCode); // List of attributes that depend on the current one
			if (count($aDependencies) > 0)
			{
				$oPage->add_ready_script("$('#$iId').bind('change', function(evt, sFormId) { return oWizardHelper{$sFormPrefix}.UpdateDependentFields(['".implode("','", $aDependencies)."']) } );\n"); // Bind to a custom event: validate
			}
		}
		return "<div>{$sHTMLValue}</div>";
	}
	
	public function DisplayModifyForm(WebPage $oPage, $aExtraParams = array())
	{
		self::$iGlobalFormId++;
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
			$this->DisplayBareRelations($oPage, true); // Edit mode
		}

		$oPage->SetCurrentTab('');
		$oPage->add("<input type=\"hidden\" name=\"class\" value=\"$sClass\">\n");
		$oPage->add("<input type=\"hidden\" name=\"transaction_id\" value=\"$iTransactionId\">\n");
		foreach($aExtraParams as $sName => $value)
		{
			$oPage->add("<input type=\"hidden\" name=\"$sName\" value=\"$value\">\n");
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
		$current = HILIGHT_CLASS_NONE; // Not hilighted by default

		// Invoke extensions before the deletion (the deletion will do some cleanup and we might loose some information
		foreach (MetaModel::EnumPlugins('iApplicationObjectExtension') as $oExtensionInstance)
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
			$sMessage =  "Error: Circular dependencies between the fields (or field missing in ZList) ! <pre>".print_r($aFields, true)."</pre>";
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
		foreach (MetaModel::EnumPlugins('iApplicationObjectExtension') as $oExtensionInstance)
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

	protected function DBInsertTracked_Internal($bDoNotReload = false)
	{
		$res = parent::DBInsertTracked_Internal($bDoNotReload);

		// Invoke extensions after insertion (the object must exist, have an id, etc.)
		foreach (MetaModel::EnumPlugins('iApplicationObjectExtension') as $oExtensionInstance)
		{
			$oExtensionInstance->OnDBInsert($this, self::$m_oCurrChange);
		}

		return $res;
	}

	protected function DBCloneTracked_Internal($newKey = null)
	{
		$oNewObj = parent::DBCloneTracked_Internal($newKey);

		// Invoke extensions after insertion (the object must exist, have an id, etc.)
		foreach (MetaModel::EnumPlugins('iApplicationObjectExtension') as $oExtensionInstance)
		{
			$oExtensionInstance->OnDBInsert($oNewObj, self::$m_oCurrChange);
		}
		return $oNewObj;
	}

	protected function DBUpdateTracked_Internal()
	{
		$res = parent::DBUpdateTracked_Internal();

		// Invoke extensions after the update (could be before)
		foreach (MetaModel::EnumPlugins('iApplicationObjectExtension') as $oExtensionInstance)
		{
			$oExtensionInstance->OnDBUpdate($this, self::$m_oCurrChange);
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
			$oExtensionInstance->OnDBDelete($this, self::$m_oCurrChange);
		}

		return parent::DBDeleteTracked_Internal($oDeletionPlan);
	}

	protected static function BulkDeleteTracked_Internal(DBObjectSearch $oFilter)
	{
		// Todo - invoke the extension
		return parent::BulkDeleteTracked_Internal($oFilter);
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
					$oPage->add_ready_script("$('#synchro_$sInputId').qtip( { content: '$sTip', show: 'mouseover', hide: 'mouseout', style: { name: 'dark', tip: 'leftTop' }, position: { corner: { target: 'rightMiddle', tooltip: 'leftTop' }} } );");
				}

				// Attribute is read-only
				$sHTMLValue = $this->GetAsHTML($sAttCode);
				$sHTMLValue .= '<input type="hidden" id="'.$sInputId.'" name="attr_'.$sPrefix.$sAttCode.'" value="'.htmlentities($this->Get($sAttCode), ENT_QUOTES, 'UTF-8').'"/>';
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
			throw new ApplicationException(Dict::Format('UI:Error:Invalid_Stimulus_On_Object_In_State', $sStimulus, $oObj->GetName(), $oObj->GetStateLabel()));
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
}
?>

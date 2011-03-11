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

	public static function GetUIPage()
	{
		return '../pages/UI.php';
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
		$oReplicaSet = $this->GetMasterReplica();
		$bSynchronized = false;
		$bCreated = false;
		$bCanBeDeleted = false;
		$aMasterSources = array();
		if ($oReplicaSet->Count() > 0)
		{
			$bSynchronized = true;
			$sTip = "<p>The object is synchronized with an external data source</p>";
			while($aData = $oReplicaSet->FetchAssoc())
			{
				// Assumption: $aData['datasource'] will not be null because the data source id is always set...
				$sApplicationURL = $aData['datasource']->GetApplicationUrl($this, $aData['replica']);
				$sLink = '';
				if (!empty($sApplicationURL))
				{
				$sLink = "<a href=\"$sApplicationURL\" target=\"_blank\">".$aData['datasource']->GetName()."</a>";
				}
				if ($aData['replica']->Get('status_dest_creator') == 1)
				{
					$sTip .= "<p>The object was <b>created</b> by the external data source $sLink</p>";
					$bCreated = true;
				}
				if ($bCreated)
				{
					$sDeletePolicy = $aData['datasource']->Get('delete_policy');
					if (($sDeletePolicy == 'delete') || ($sDeletePolicy == 'update_then_delete'))
					{
						$bCanBeDeleted = true;
						$sTip .= "<p>The object <b>can be deleted</b> by the external data source $sLink</p>";
					}
				}
				$aMasterSources[$aData['datasource']->GetKey()]['datasource'] = $aData['datasource'];
				$aMasterSources[$aData['datasource']->GetKey()]['url'] = $sLink;
			}
		}
		
		$sSynchroIcon = '';
		if ($bSynchronized)
		{
			$sTip .= "<p><b>List of data sources:</b></p>";
			foreach($aMasterSources as $aStruct)
			{
				$oDataSource = $aStruct['datasource'];
				$sLink = $aStruct['url'];
				$sTip .= "<p style=\"white-space:nowrap\">".$oDataSource->GetIcon(true, 'style="vertical-align:middle"')."&nbsp;$sLink</p>";
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

	function DisplayBareProperties(WebPage $oPage, $bEditMode = false)
	{
		$oPage->add($this->GetBareProperties($oPage, $bEditMode));		

		foreach (MetaModel::EnumPlugins('iApplicationUIExtension') as $oExtensionInstance)
		{
			$oExtensionInstance->OnDisplayProperties($this, $oPage, $bEditMode);
		}
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
			
			$oPage->SetCurrentTab($oAttDef->GetLabel());
			if ($bEditMode)
			{
				$iFlags = $this->GetAttributeFlags($sAttCode);
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
				$oPage->SetCurrentTab(Dict::S('UI:NotificationsTab'));
		
				// Display notifications regarding the object
				$iId = $this->GetKey();
				$oBlock = new DisplayBlock(DBObjectSearch::FromOQL("SELECT EventNotificationEmail AS Ev JOIN TriggerOnObject AS T ON Ev.trigger_id = T.id WHERE T.target_class IN ('$sClassList') AND Ev.object_id = $iId"), 'list', false);
				$oBlock->Display($oPage, 'notifications', array());
			}
		}

		foreach (MetaModel::EnumPlugins('iApplicationUIExtension') as $oExtensionInstance)
		{
			$oExtensionInstance->OnDisplayRelations($this, $oPage, $bEditMode);
		}
	}

	function GetBareProperties(WebPage $oPage, $bEditMode = false)
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
						$val = $this->GetFieldAsHtml($sClass, $sAttCode, $sStateAttCode);
						if ($val != null)
						{
							// Check if the attribute is not mastered by a synchro...
							$aReasons = array();
							$iSynchroFlags = $this->GetSynchroReplicaFlags($sAttCode, $aReasons);
							$sSynchroIcon = '';
							if ($iSynchroFlags & OPT_ATT_READONLY)
							{
								$sSynchroIcon = "&nbsp;<img id=\"synchro_$iInputId\" src=\"../images/transp-lock.png\" style=\"vertical-align:middle\"/>";
								$sTip = '';
								foreach($aReasons as $aRow)
								{
									$sTip .= "<p>Synchronized with {$aRow['name']} - {$aRow['description']}</p>";
								}
								$oPage->add_ready_script("$('#synchro_$iInputId').qtip( { content: '$sTip', show: 'mouseover', hide: 'mouseout', style: { name: 'dark', tip: 'leftTop' }, position: { corner: { target: 'rightMiddle', tooltip: 'leftTop' }} } );");
							}

							$val['value'] .= $sSynchroIcon;
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
		return $sHtml;
	}

	
	function DisplayDetails(WebPage $oPage, $bEditMode = false)
	{
		$sTemplate = Utils::ReadFromFile(MetaModel::GetDisplayTemplate(get_class($this)));
		if (!empty($sTemplate))
		{
			$oTemplate = new DisplayTemplate($sTemplate);
			// Note: to preserve backward compatibility with home-made templates, the placeholder '$pkey$' has been preserved
			//       but the preferred method is to use '$id$'
			$oTemplate->Render($oPage, array('class_name'=> MetaModel::GetName(get_class($this)),'class'=> get_class($this), 'pkey'=> $this->GetKey(), 'id'=> $this->GetKey(), 'name' => $this->Get('friendlyname')));
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
		$aList = self::FlattenZList(MetaModel::GetZListItems($sClassName, $sZListName));
		$aList = array_merge($aList, $aExtraFields);
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
		if ($bSelectMode)
		{
			if (!$bSingleSelectMode)
			{
				$aAttribs['form::select'] = array('label' => "<input type=\"checkbox\" onClick=\"CheckAll('.selectList{$iListId}', this.checked);\"></input>", 'description' => Dict::S('UI:SelectAllToggle+'));
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
		if ($bDisplayLimit && $bTruncated)
		{
			if ($oSet->Count() > MetaModel::GetConfig()->GetMaxDisplayLimit())
			{
				$iMaxObjects = MetaModel::GetConfig()->GetMinDisplayLimit();
				$oSet->SetLimit($iMaxObjects);
			}
		}
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
				if ($bSingleSelectMode)
				{
					$aRow['form::select'] = "<input type=\"radio\" class=\"selectList{$iListId}\" name=\"selectObject\" value=\"".$oObj->GetKey()."\"></input>";
				}
				else
				{
				$aRow['form::select'] = "<input type=\"checkBox\" class=\"selectList{$iListId}\" name=\"selectObject[]\" value=\"".$oObj->GetKey()."\"></input>";
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
		if ($bDisplayLimit && $bTruncated && ($oSet->Count() > MetaModel::GetConfig()->GetMaxDisplayLimit()))
		{
			// list truncated
			$aExtraParams['display_limit'] = true;
			$sHtml .= '<tr class="containerHeader"><td><span id="lbl_'.$iListId.'">'.$sCollapsedLabel.'</span>&nbsp;&nbsp;<a class="truncated" id="trc_'.$iListId.'">'.$sLinkLabel.'</a></td><td>';
			$oPage->add_ready_script(
<<<EOF
	$('#$iListId table.listResults').addClass('truncated');
	$('#$iListId table.listResults tr:last td').addClass('truncated');
EOF
);
		}
		else if ($bDisplayLimit && !$bTruncated && ($oSet->Count() > MetaModel::GetConfig()->GetMaxDisplayLimit()))
		{
			// Collapsible list
			$aExtraParams['display_limit'] = true;
			$sHtml .= '<tr class="containerHeader"><td><span id="lbl_'.$iListId.'">'.Dict::Format('UI:CountOfResults', $oSet->Count()).'</span><a class="truncated" id="trc_'.$iListId.'">'.Dict::S('UI:CollapseList').'</a></td><td>';
		}
		$aExtraParams['truncated'] = false; // To expand the full list when clicked
		$sExtraParamsExpand = addslashes(str_replace('"', "'", json_encode($aExtraParams))); // JSON encode, change the style of the quotes and escape them
		$oPage->add_ready_script(
<<<EOF
	// Handle truncated lists
	$('#trc_$iListId').click(function()
	{
		var state = {};
		
		var currentState = $.bbq.getState( this.id, true ) || 'close';	  
		// Toggle the state!
		if (currentState == 'close')
		{
			state[ this.id ] = 'open';
		}
		else
		{
			state[ this.id ] = 'close';			
		}
		$.bbq.pushState( state );
		$(this).trigger(state[this.id]);	
	});
	$('#trc_$iListId').unbind('open');
	$('#trc_$iListId').bind('open', function()
	{
		ReloadTruncatedList('$iListId', '$sFilter', '$sExtraParamsExpand');
	});
	$('#trc_$iListId').unbind('close');	
	$('#trc_$iListId').bind('close', function()
	{
		TruncateList('$iListId', $iMinDisplayLimit, '$sCollapsedLabel', '$sLinkLabel');
	});
EOF
);
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
				$sHtml .= '<tr class="containerHeader"><td>'.Dict::Format('UI:TruncatedResults', MetaModel::GetConfig()->GetMinDisplayLimit(), $oSet->Count()).'&nbsp;&nbsp;<a href="Javascript:ReloadTruncatedList(\''.$divId.'\', \''.$sFilter.'\', \''.$sExtraParams.'\');">'.Dict::S('UI:DisplayAll').'</a></td><td>';
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
				if ((($oAttDef->IsExternalField()) || ($oAttDef->IsWritable())) && $oAttDef->IsScalar())
				{
					$aList[$sClassName][$sAttCode] = $oAttDef;
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
						$aRow[] = $oObj->GetAsCSV($sAttCode, $sSeparator, '\\');
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
						if (($oAttDef->IsWritable()) && ($oAttDef->IsScalar()))
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
		$sHtml .= "<form id=\"fs_{$sSearchFormId}\" action=\"../pages/UI.php\">\n"; // Don't use $_SERVER['SCRIPT_NAME'] since the form may be called asynchronously (from ajax.php)
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
			$sFilterValue = utils::ReadParam($sFilterCode, '');
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
			$aAllowedValues = MetaModel::GetAllowedValues_flt($sClassName, $sFilterCode, $aExtraParams);
			if ($aAllowedValues != null)
			{
				$oAttDef = MetaModel::GetAttributeDef($sClassName, $sFilterCode);

				if ($oAttDef->IsExternalKey())
				{
					$iFieldSize = $oAttDef->GetMaxSize();
					$iMaxComboLength = $oAttDef->GetMaximumComboLength();
					$oWidget = new UIExtKeyWidget($sFilterCode, $sClassName, $oAttDef->GetLabel(), $aAllowedValues, $sFilterValue, 'search_'.$sFilterCode, false, '', '', '');
					$sHtml .= "<label>".MetaModel::GetFilterLabel($sClassName, $sFilterCode).":</label>&nbsp;";
					$sHtml .= $oWidget->Display($oPage, $aExtraParams, true /* bSearchMode */);
				}
				else
				{
				//Enum field or external key, display a combo
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
				unset($aExtraParams[$sFilterCode]);
			}
			else
			{
				// Any value is possible, display an input box
				$sHtml .= "<label>".MetaModel::GetFilterLabel($sClassName, $sFilterCode).":</label>&nbsp;<input class=\"textSearch\" name=\"$sFilterCode\" value=\"$sFilterValue\"/>\n";
				unset($aExtraParams[$sFilterCode]);
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
		$sSelectedClass = utils::ReadParam('oql_class', $sClassName);
		$sOQLClause = utils::ReadParam('oql_clause', '');
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
				case 'DateTime':
				$aEventsList[] ='validate';
				$aEventsList[] ='keyup';
				$aEventsList[] ='change';
				$sHTMLValue = "<input title=\"$sHelpText\" class=\"date-pick\" type=\"text\" size=\"20\" name=\"attr_{$sFieldPrefix}{$sAttCode}{$sNameSuffix}\" value=\"$value\" id=\"$iId\"/>&nbsp;{$sValidationField}";
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
				$sHidden = "<input type=\"hidden\" id=\"{$iId}\" value=\"$value\"/>";
				$sHTMLValue = Dict::Format('UI:DurationForm_Days_Hours_Minutes_Seconds', $sDays, $sHours, $sMinutes, $sSeconds).$sHidden."&nbsp;".$sValidationField;
				break;
				
				case 'Password':
					$aEventsList[] ='validate';
					$aEventsList[] ='keyup';
					$aEventsList[] ='change';
					$sHTMLValue = "<input title=\"$sHelpText\" type=\"password\" size=\"30\" name=\"attr_{$sFieldPrefix}{$sAttCode}{$sNameSuffix}\" value=\"$value\" id=\"$iId\"/>&nbsp;{$sValidationField}";
				break;
				
				case 'Text':
					$aEventsList[] ='validate';
					$aEventsList[] ='keyup';
					$aEventsList[] ='change';
					$sEditValue = $oAttDef->GetEditValue($value);
					$sHTMLValue = "<table><tr><td><textarea class=\"resizable\" title=\"$sHelpText\" name=\"attr_{$sFieldPrefix}{$sAttCode}{$sNameSuffix}\" rows=\"8\" cols=\"40\" id=\"$iId\">$sEditValue</textarea></td><td>{$sValidationField}</td></tr></table>";
				break;

				case 'HTML':
					$oWidget = new UIHTMLEditorWidget($iId, $sAttCode, $sNameSuffix, $sFieldPrefix, $sHelpText, $sValidationField, $value, $bMandatory);
					$sHTMLValue = $oWidget->Display($oPage, $aArgs);
				break;

				case 'LinkedSet':
					$aEventsList[] ='validate';
					$aEventsList[] ='change';
					$oWidget = new UILinksWidget($sClass, $sAttCode, $iId, $sNameSuffix, $oAttDef->DuplicatesAllowed());
					$sHTMLValue = $oWidget->Display($oPage, $value);
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
					$sHTMLValue .= "<input name=\"attr_{$sFieldPrefix}{$sAttCode}{$sNameSuffix}\" type=\"hidden\" id=\"$iId\" \" value=\"$sFileName\"/>\n";
					$sHTMLValue .= "<span id=\"name_$iInputId\">$sFileName</span><br/>\n";
					$sHTMLValue .= "<input title=\"$sHelpText\" name=\"file_{$sFieldPrefix}{$sAttCode}{$sNameSuffix}\" type=\"file\" id=\"file_$iId\" onChange=\"UpdateFileName('$iId', this.value)\"/>&nbsp;{$sValidationField}\n";
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

					$aAllowedValues = MetaModel::GetAllowedValues_att($sClass, $sAttCode, $aArgs);
					$iFieldSize = $oAttDef->GetMaxSize();
					$iMaxComboLength = $oAttDef->GetMaximumComboLength();
					$oWidget = new UIExtKeyWidget($sAttCode, $sClass, $oAttDef->GetLabel(), $aAllowedValues, $value, $iId, $bMandatory, $sNameSuffix, $sFieldPrefix, $sFormPrefix);
					$sHTMLValue = $oWidget->Display($oPage, $aArgs);
					break;
					
				case 'String':
				default:
					$aEventsList[] ='validate';
					// #@# todo - add context information (depending on dimensions)
					$aAllowedValues = MetaModel::GetAllowedValues_att($sClass, $sAttCode, $aArgs);
					$iFieldSize = $oAttDef->GetMaxSize();
					if ($aAllowedValues !== null)
					{
						// Discrete list of values, use a SELECT
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
					else
					{
						$sHTMLValue = "<input title=\"$sHelpText\" type=\"text\" size=\"30\" maxlength=\"$iFieldSize\" name=\"attr_{$sFieldPrefix}{$sAttCode}{$sNameSuffix}\" value=\"$value\" id=\"$iId\"/>&nbsp;{$sValidationField}";
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
		static $iGlobalFormId = 1;
		$iGlobalFormId++;
		$sPrefix = '';
		if (isset($aExtraParams['formPrefix']))
		{
			$sPrefix = $aExtraParams['formPrefix'];
		}
		$this->m_iFormId = $sPrefix.$iGlobalFormId;
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
		$oPage->add("<form action=\"$sFormAction\" id=\"form_{$this->m_iFormId}\" enctype=\"multipart/form-data\" method=\"post\" onSubmit=\"return CheckFields('form_{$this->m_iFormId}', true)\">\n");

		$oPage->AddTabContainer(OBJECT_PROPERTIES_TAB, $sPrefix);
		$oPage->SetCurrentTabContainer(OBJECT_PROPERTIES_TAB);
		$oPage->SetCurrentTab(Dict::S('UI:PropertiesTab'));
//		$aDetailsList = $this->FLattenZList(MetaModel::GetZListItems($sClass, 'details'));
		//$aFullList = MetaModel::ListAttributeDefs($sClass);
		$aList = array();
		// Compute the list of properties to display, first the attributes in the 'details' list, then 
		// all the remaining attributes that are not external fields
//		foreach($aDetailsList as $sAttCode)
//		{
//			$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
//			if (!$oAttDef->IsExternalField())
//			{
//				$aList[] = $sAttCode;
//			}
//		}

		$aDetailsList = MetaModel::GetZListItems($sClass, 'details');
		$aDetailsStruct = self::ProcessZlist($aDetailsList, array('UI:PropertiesTab' => array()), 'UI:PropertiesTab', 'col1', '');
		$sHtml = '';
		$aDetails = array();
		foreach($aDetailsStruct as $sTab => $aCols )
		{
			$aDetails[$sTab] = array();
			ksort($aCols);
			$oPage->SetCurrentTab(Dict::S($sTab));
			$oPage->add('<table style="vertical-align:top"><tr>');
			foreach($aCols as $sColIndex => $aFieldsets)
			{
				$sLabel = '';
				$sPreviousLabel = '';
				$aDetails[$sTab][$sColIndex] = array();
				$oPage->add('<td style="vertical-align:top">');
				//$aDetails[$sTab][$sColIndex] = array();
				foreach($aFieldsets as $sFieldsetName => $aFields)
				{
					if (!empty($sFieldsetName) && ($sFieldsetName[0]!='_'))
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
						$aVal = null;
						$iFlags = $this->GetAttributeFlags($sAttCode);
						$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
						if ( (!$oAttDef->IsLinkSet()) && (($iFlags & OPT_ATT_HIDDEN) == 0))
						{
							if ($oAttDef->IsWritable())
							{
								if ($sStateAttCode == $sAttCode)
								{
									// State attribute is always read-only from the UI
									$sHTMLValue = $this->GetStateLabel();
									$aVal = array('label' => $oAttDef->GetLabel(), 'value' => $sHTMLValue);
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
										if ($iFlags & OPT_ATT_READONLY)
										{

											// Check if the attribute is not read-only becuase of a synchro...
											$aReasons = array();
											$iSynchroFlags = $this->GetSynchroReplicaFlags($sAttCode, $aReasons);
											$sSynchroIcon = '';
											if ($iSynchroFlags & OPT_ATT_READONLY)
											{
												$sSynchroIcon = "&nbsp;<img id=\"synchro_$sInputId\" src=\"../images/transp-lock.png\" style=\"vertical-align:middle\"/>";
												$sTip = '';
												foreach($aReasons as $aRow)
												{
													$sTip .= "<p>Synchronized with {$aRow['name']} - {$aRow['description']}</p>";
												}
												$oPage->add_ready_script("$('#synchro_$sInputId').qtip( { content: '$sTip', show: 'mouseover', hide: 'mouseout', style: { name: 'dark', tip: 'leftTop' }, position: { corner: { target: 'rightMiddle', tooltip: 'leftTop' }} } );");
											}

											// Attribute is read-only
											$sHTMLValue = $this->GetAsHTML($sAttCode).$sSynchroIcon;
											$sHTMLValue .= '<input type="hidden" id="'.$sInputId.'" name="attr_'.$sPrefix.$sAttCode.'" value="'.htmlentities($this->Get($sAttCode), ENT_QUOTES, 'UTF-8').'"/>';
											$aFieldsMap[$sAttCode] = $sInputId;
										}
										else
										{
											$sValue = $this->Get($sAttCode);
											$sDisplayValue = $this->GetEditValue($sAttCode);
											$aArgs = array('this' => $this, 'formPrefix' => $sPrefix);
											$sHTMLValue = "<span id=\"field_{$sInputId}\">".self::GetFormElementForField($oPage, $sClass, $sAttCode, $oAttDef, $sValue, $sDisplayValue, $sInputId, '', $iFlags, $aArgs).'</span>';
											$aFieldsMap[$sAttCode] = $sInputId;
											
										}
										$aVal = array('label' => '<span title="'.$oAttDef->GetDescription().'">'.$oAttDef->GetLabel().'</span>', 'value' => $sHTMLValue);
									}
								}
							}
							else
							{
								$aVal = array('label' => '<span title="'.$oAttDef->GetDescription().'">'.$oAttDef->GetLabel().'</span>', 'value' => $this->GetAsHTML($sAttCode));			
							}
						}
						if ($aVal != null)
						{
							// The field is visible, add it to the current column
							$aDetails[$sTab][$sColIndex][] = $aVal;
						}				
					}
				}
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
				$oPage->add('</td>');
			}
			$oPage->add('</tr></table>');
		}

		// Now display the relations, one tab per relation
		if (!isset($aExtraParams['noRelations']))
		{
			$this->DisplayBareRelations($oPage, true); // Edit mode
		}

		$oPage->SetCurrentTab('');
		$oPage->add("<input type=\"hidden\" name=\"class\" value=\"$sClass\">\n");
		$oPage->add("<input type=\"hidden\" name=\"transaction_id\" value=\"".utils::GetNewTransactionId()."\">\n");
		foreach($aExtraParams as $sName => $value)
		{
			$oPage->add("<input type=\"hidden\" name=\"$sName\" value=\"$value\">\n");
		}
		$oPage->add($oAppContext->GetForForm());
		if ($iKey > 0)
		{
			// The object already exists in the database, it's modification
			$oPage->add("<input type=\"hidden\" name=\"id\" value=\"$iKey\">\n");
			$oPage->add("<input type=\"hidden\" name=\"operation\" value=\"apply_modify\">\n");			
//			$oPage->add("<button type=\"button\" id=\"btn_cancel_{$sPrefix}\" class=\"action\" onClick=\"BackToDetails('$sClass', $iKey)\"><span>".Dict::S('UI:Button:Cancel')."</span></button>&nbsp;&nbsp;&nbsp;&nbsp;\n");
			$oPage->add("<button type=\"button\" class=\"action cancel\"><span>".Dict::S('UI:Button:Cancel')."</span></button>&nbsp;&nbsp;&nbsp;&nbsp;\n");
			$oPage->add("<button type=\"submit\" class=\"action\"><span>".Dict::S('UI:Button:Apply')."</span></button>\n");
		}
		else
		{
			// The object does not exist in the database it's a creation
			$oPage->add("<input type=\"hidden\" name=\"operation\" value=\"apply_new\">\n");			
//			$oPage->add("<button type=\"button\" id=\"btn_cancel_{$sPrefix}\" class=\"action\" onClick=\"BackToDetails('$sClass', $iKey)\"><span>".Dict::S('UI:Button:Cancel')."</span></button>&nbsp;&nbsp;&nbsp;&nbsp;\n");
			$oPage->add("<button type=\"button\" class=\"action cancel\">".Dict::S('UI:Button:Cancel')."</button>&nbsp;&nbsp;&nbsp;&nbsp;\n");
			$oPage->add("<button type=\"submit\" class=\"action\"><span>".Dict::S('UI:Button:Create')."</span></button>\n");
		}
		// Hook the cancel button via jQuery so that it can be unhooked easily as well if needed
		$sDefaultUrl = '../pages/UI.php?operation=cancel';
		$oPage->add_ready_script("$('#form_{$this->m_iFormId} button.cancel').click( function() { BackToDetails('$sClass', $iKey, '$sDefaultUrl')} );");
		$oPage->add("</form>\n");
		
		$iFieldsCount = count($aFieldsMap);
		$sJsonFieldsMap = json_encode($aFieldsMap);

		$oPage->add_script(
<<<EOF
		// Create the object once at the beginning of the page...
		var oWizardHelper$sPrefix = new WizardHelper('$sClass', '$sPrefix');
		oWizardHelper$sPrefix.SetFieldsMap($sJsonFieldsMap);
		oWizardHelper$sPrefix.SetFieldsCount($iFieldsCount);
EOF
);
		$oPage->add_ready_script(
<<<EOF
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
			$aAllowedValues = MetaModel::GetAllowedValues_att($sClass, $sAttCode, $aArgs);
			if (isset($aArgs['default'][$sAttCode]))
			{
				$oObj->Set($sAttCode, $aArgs['default'][$sAttCode]);			
			}
			elseif (count($aAllowedValues) == 1)
			{
				// If the field is mandatory, set it to the only possible value
				$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
				$iFlags = $oObj->GetAttributeFlags($sAttCode);
				if ( (!$oAttDef->IsNullAllowed()) || ($iFlags & OPT_ATT_MANDATORY))
				{
					$aValues = array_keys($aAllowedValues);
					$oObj->Set($sAttCode, $aValues[0]);
				}
			}
		}
		return $oObj->DisplayModifyForm( $oPage, $aExtraParams);
	}

	protected static function ProcessZlist($aList, $aDetails, $sCurrentTab, $sCurrentCol, $sCurrentSet)
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

	protected static function FlattenZList($aList)
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
		$iFlags = $this->GetAttributeFlags($sAttCode);
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
				$sDisplayValue .= "<br/>".Dict::Format('UI:DownloadDocument_', $oDocument->GetDisplayLink(get_class($this), $this->GetKey(), $sAttCode)).", \n";
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
				$oPage->add("<iframe id='preview_$sAttCode' src=\"../pages/ajax.render.php?operation=display_document&class=$sClass&id=$Id&field=$sAttCode\" width=\"100%\" height=\"400\">Loading...</iframe>\n");
				break;
				
				default:
				$oPage->add("<pre>".htmlentities(MyHelpers::beautifulstr($data, 1000, true), ENT_QUOTES, 'UTF-8')."</pre>\n");			
			}
			break;

			case 'application':
			switch($oDoc->GetMimeType())
			{
				case 'application/pdf':
				$oPage->add("<iframe id='preview_$sAttCode' src=\"../pages/ajax.render.php?operation=display_document&class=$sClass&id=$Id&field=$sAttCode\" width=\"100%\" height=\"400\">Loading...</iframe>\n");
				break;

				default:
				$oPage->add(Dict::S('UI:Document:NoPreview'));
			}
			break;
			
			case 'image':
			$oPage->add("<img src=\"../pages/ajax.render.php?operation=display_document&class=$sClass&id=$Id&field=$sAttCode\" />\n");
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
			$sMessage =  "Error: Circular dependencies between the fields ! <pre>".print_r($aFields, true)."</pre>";
			throw(new Exception($sMessage));
		}
		return $aResult;
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
	 * Updates the object from the POSTed parameters
	 */
	public function UpdateObject($sFormPrefix = '')
	{
		foreach(MetaModel::ListAttributeDefs(get_class($this)) as $sAttCode=>$oAttDef)
		{
			if ($oAttDef->IsLinkSet() && $oAttDef->IsIndirect())
			{
				$aLinks = utils::ReadPostedParam("attr_{$sFormPrefix}{$sAttCode}", '');
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
			else if ($oAttDef->IsWritable())
			{
				$iFlags = $this->GetAttributeFlags($sAttCode);
				if ($iFlags & (OPT_ATT_HIDDEN | OPT_ATT_READONLY))
				{
					// Non-visible, or read-only attribute, do nothing
				}
				elseif ($oAttDef->GetEditClass() == 'Document')
				{
					// There should be an uploaded file with the named attr_<attCode>
					$oDocument = utils::ReadPostedDocument("file_{$sFormPrefix}{$sAttCode}");
					if (!$oDocument->IsEmpty())
					{
						// A new file has been uploaded
						$this->Set($sAttCode, $oDocument);
					}
				}
				elseif ($oAttDef->GetEditClass() == 'One Way Password')
				{
					// Check if the password was typed/changed
					$bChanged = utils::ReadPostedParam("attr_{$sFormPrefix}{$sAttCode}_changed", false);
					if ($bChanged)
					{
						// The password has been changed or set
						$rawValue = utils::ReadPostedParam("attr_{$sFormPrefix}{$sAttCode}", null);
						$this->Set($sAttCode, $rawValue);
					}
				}
				elseif ($oAttDef->GetEditClass() == 'Duration')
				{
					$rawValue = utils::ReadPostedParam("attr_{$sFormPrefix}{$sAttCode}", null);
					if (!is_array($rawValue))
					{
						$iValue = null;
					}
					else
					{
						$iValue = (((24*$rawValue['d'])+$rawValue['h'])*60 +$rawValue['m'])*60 + $rawValue['s'];
					}		
					$this->Set($sAttCode, $iValue);
					$previousValue = $this->Get($sAttCode);
					if ($previousValue !== $iValue)
					{
						$this->Set($sAttCode, $iValue);
					}
				}
				else
				{
					$rawValue = utils::ReadPostedParam("attr_{$sFormPrefix}{$sAttCode}", null);
					if (!is_null($rawValue))
					{
						$aAttributes[$sAttCode] = trim($rawValue);
						$previousValue = $this->Get($sAttCode);
						if ($previousValue !== $aAttributes[$sAttCode])
						{
							$this->Set($sAttCode, $aAttributes[$sAttCode]);
						}
					}
				}
			}
		}
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

	protected function DBDeleteTracked_Internal()
	{
		// Invoke extensions before the deletion (the deletion will do some cleanup and we might loose some information
		foreach (MetaModel::EnumPlugins('iApplicationObjectExtension') as $oExtensionInstance)
		{
			$oExtensionInstance->OnDBDelete($this, self::$m_oCurrChange);
		}

		return parent::DBDeleteTracked_Internal();
	}

	protected static function BulkDeleteTracked_Internal(DBObjectSearch $oFilter)
	{
		// Todo - invoke the extension
		return parent::BulkDeleteTracked_Internal($oFilter);
	}

	public function DoCheckToWrite()
	{
		parent::DoCheckToWrite();
		foreach (MetaModel::EnumPlugins('iApplicationObjectExtension') as $oExtensionInstance)
		{
			$aNewIssues = $oExtensionInstance->OnCheckToWrite($this);
			if (count($aNewIssues) > 0)
			{
				$this->m_aCheckIssues = array_merge($this->m_aCheckIssues, $aNewIssues);
			}
		}
	}

	protected function DoCheckToDelete()
	{
		parent::DoCheckToDelete();
		foreach (MetaModel::EnumPlugins('iApplicationObjectExtension') as $oExtensionInstance)
		{
			$aNewIssues = $oExtensionInstance->OnCheckToDelete($this);
			if (count($aNewIssues) > 0)
			{
				$this->m_aDeleteIssues = array_merge($this->m_aDeleteIssues, $aNewIssues);
			}
		}
	}
}
?>

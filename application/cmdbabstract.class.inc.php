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

require_once('../core/cmdbobject.class.inc.php');
require_once('../application/utils.inc.php');
require_once('../application/applicationcontext.class.inc.php');
require_once('../application/ui.linkswidget.class.inc.php');
require_once('../application/ui.passwordwidget.class.inc.php');

abstract class cmdbAbstractObject extends CMDBObject
{
	protected $m_iFormId; // The ID of the form used to edit the object (when in edition mode !)
	
	public static function GetUIPage()
	{
		return '../pages/UI.php';
	}
	
	public static function ComputeUIPage($sClass)
	{
		static $aUIPagesCache = array(); // Cache to store the php page used to display each class of object
		if (!isset($aUIPagesCache[$sClass]))
		{
			$UIPage = false;
			if (is_callable("$sClass::GetUIPage"))
			{
				$UIPage = eval("return $sClass::GetUIPage();"); // May return false in case of error
			}
			$aUIPagesCache[$sClass] = $UIPage === false ? './UI.php' : $UIPage;
		}
		$sPage = $aUIPagesCache[$sClass];
		return $sPage;
	}

	protected static function MakeHyperLink($sObjClass, $sObjKey, $aAvailableFields)
	{
		if ($sObjKey <= 0) return '<em>'.Dict::S('UI:UndefinedObject').'</em>'; // Objects built in memory have negative IDs

		$oAppContext = new ApplicationContext();	
		$sExtClassNameAtt = MetaModel::GetNameAttributeCode($sObjClass);
		$sPage = self::ComputeUIPage($sObjClass);
        $sAbsoluteUrl = utils::GetAbsoluteUrl(false); // False => Don't get the query string
        $sAbsoluteUrl = substr($sAbsoluteUrl, 0, 1+strrpos($sAbsoluteUrl, '/')); // remove the current page, keep just the path, up to the last /

		// Use the "name" of the target class as the label of the hyperlink
		// unless it's not available in the external attributes...
		if (isset($aAvailableFields[$sExtClassNameAtt]))
		{
			$sLabel = $aAvailableFields[$sExtClassNameAtt];
		}
		else
		{
			$sLabel = implode(' / ', $aAvailableFields);
		}
		// Safety belt
		//
		if (empty($sLabel))
		{
			// Developer's note:
			// This is doing the job for you, but that is just there in case
			// the external fields associated to the external key are blanks
			// The ultimate solution will be to query the name automatically
			// and independantly from the data model (automatic external field)
			// AND make the name be a mandatory field
			//
			$sObject = MetaModel::GetObject($sObjClass, $sObjKey);
			$sLabel = $sObject->GetName();
		}
		// Safety net
		//
		if (empty($sLabel))
		{
			$sLabel = MetaModel::GetName($sObjClass)." #$sObjKey";
		}
		$sHint = MetaModel::GetName($sObjClass)."::$sObjKey";
		return "<a href=\"{$sAbsoluteUrl}{$sPage}?operation=details&class=$sObjClass&id=$sObjKey&".$oAppContext->GetForLink()."\" title=\"$sHint\">$sLabel</a>";
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
		$oPage->add("<div class=\"page_header\"><h1>".$this->GetIcon()."&nbsp;\n");
		$oPage->add(MetaModel::GetName(get_class($this)).": <span class=\"hilite\">".$this->GetName()."</span></h1>\n");
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
				else // get_class($oAttDef) == 'AttributeLinkedSetIndirect'
				{
					// n:n links
					$sLinkedClass = $oAttDef->GetLinkedClass();
					$oLinkingAttDef = 	MetaModel::GetAttributeDef($sLinkedClass, $oAttDef->GetExtKeyToRemote());
					$sTargetClass = $oLinkingAttDef->GetTargetClass();
					$oPage->p(MetaModel::GetClassIcon($sTargetClass)."&nbsp;".$oAttDef->GetDescription());

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
						'menu' => true,
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
							'menu' => true,
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
				$oNotifSet = new CMDBObjectSet(DBObjectSearch::FromOQL("SELECT EventNotificationEmail AS Ev JOIN TriggerOnObject AS T ON Ev.trigger_id = T.id WHERE T.target_class IN ('$sClassList') AND Ev.object_id = $iId"));
				self::DisplaySet($oPage, $oNotifSet);
			}
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
		foreach($aDetailsStruct as $sTab => $aCols )
		{
			$aDetails[$sTab] = array();
			ksort($aCols);
			$oPage->SetCurrentTab(Dict::S($sTab));
			$oPage->add('<table style="vertical-align:top"><tr>');
			foreach($aCols as $sColIndex => $aFieldsets)
			{
				$aDetails[$sTab][$sColIndex] = array();
				foreach($aFieldsets as $sFieldsetName => $aFields)
				{
					//if ($sFieldsetName == '')
					//{
						foreach($aFields as $sAttCode)
						{
							$val = $this->GetFieldAsHtml($sClass, $sAttCode, $sStateAttCode);
							if ($val != null)
							{
								// The field is visible, add it to the current column
								$aDetails[$sTab][$sColIndex][] = $val;
							}				
						}
					//}
				}
				$oPage->add('<td style="vertical-align:top">');
				$oPage->Details($aDetails[$sTab][$sColIndex]);
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
			$sNameAttCode = MetaModel::GetNameAttributeCode(get_class($this));
			// Note: to preserve backward compatibility with home-made templates, the placeholder '$pkey$' has been preserved
			//       but the preferred method is to use '$id$'
			$oTemplate->Render($oPage, array('class_name'=> MetaModel::GetName(get_class($this)),'class'=> get_class($this), 'pkey'=> $this->GetKey(), 'id'=> $this->GetKey(), 'name' => $this->Get($sNameAttCode)));
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
		static $iListId = 0;
		$iListId++;
		
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
				$aAttribs['form::select'] = array('label' => "<input type=\"checkbox\" onChange=\"var value = this.checked; $('.selectList{$iListId}').each( function() { this.checked = value; } );\"></input>", 'description' => Dict::S('UI:SelectAllToggle+'));
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
			if ($oSet->Count() > utils::GetConfig()->GetMaxDisplayLimit())
			{
				$iMaxObjects = utils::GetConfig()->GetMinDisplayLimit();
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
		$divId = $aExtraParams['block_id'];
		$sFilter = $oSet->GetFilter()->serialize();
		$iMinDisplayLimit = utils::GetConfig()->GetMinDisplayLimit();
		$sCollapsedLabel = Dict::Format('UI:TruncatedResults', $iMinDisplayLimit, $oSet->Count());
		$sLinkLabel = Dict::S('UI:DisplayAll');
		foreach($oSet->GetFilter()->GetInternalParams() as $sName => $sValue)
		{
			$aExtraParams['query_params'][$sName] = $sValue;
		}
		if ($bDisplayLimit && $bTruncated && ($oSet->Count() > utils::GetConfig()->GetMaxDisplayLimit()))
		{
			// list truncated
			$aExtraParams['display_limit'] = true;
			$sHtml .= '<tr class="containerHeader"><td><span id="lbl_'.$divId.'">'.$sCollapsedLabel.'</span>&nbsp;&nbsp;<a class="truncated" id="trc_'.$divId.'">'.$sLinkLabel.'</a></td><td>';
			$oPage->add_ready_script(
<<<EOF
	$('#$divId table.listResults').addClass('truncated');
	$('#$divId table.listResults tr:last td').addClass('truncated');
EOF
);
		}
		else if ($bDisplayLimit && !$bTruncated && ($oSet->Count() > utils::GetConfig()->GetMaxDisplayLimit()))
		{
			// Collapsible list
			$aExtraParams['display_limit'] = true;
			$sHtml .= '<tr class="containerHeader"><td><span id="lbl_'.$divId.'">'.Dict::Format('UI:CountOfResults', $oSet->Count()).'</span><a class="truncated" id="trc_'.$divId.'">'.Dict::S('UI:CollapseList').'</a></td><td>';
		}
		$aExtraParams['truncated'] = false; // To expand the full list when clicked
		$sExtraParamsExpand = addslashes(str_replace('"', "'", json_encode($aExtraParams))); // JSON encode, change the style of the quotes and escape them
		$oPage->add_ready_script(
<<<EOF
	// Handle truncated lists
	$('#trc_$divId').click(function()
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
	
	$('#trc_$divId').bind('open', function()
	{
		ReloadTruncatedList('$divId', '$sFilter', '$sExtraParamsExpand');
	});
	
	$('#trc_$divId').bind('close', function()
	{
		TruncateList('$divId', $iMinDisplayLimit, '$sCollapsedLabel', '$sLinkLabel');
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
			$sHtml .= $oMenuBlock->GetRenderContent($oPage, $aMenuExtraParams);
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
			if ($oSet->Count() > utils::GetConfig()->GetMaxDisplayLimit())
			{
				$iMaxObjects = utils::GetConfig()->GetMinDisplayLimit();
			}
		}
		while (($aObjects = $oSet->FetchAssoc()) && ($iMaxObjects != 0))
		{
			$aRow = array();
			foreach($aAuthorizedClasses as $sAlias => $sClassName) // TO DO: check if the user has enough rights to view the classes of the list...
			{
				if ($bViewLink)
				{
					$aRow['key_'.$sAlias] = $aObjects[$sAlias]->GetHyperLink();
				}
				foreach($aList[$sClassName] as $sAttCode)
				{
					$aRow[$sAttCode.'_'.$sAlias] = $aObjects[$sAlias]->GetAsHTML($sAttCode);
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
			if ($bDisplayLimit && ($oSet->Count() > utils::GetConfig()->GetMaxDisplayLimit()))
			{
				// list truncated
				$divId = $aExtraParams['block_id'];
				$sFilter = $oSet->GetFilter()->serialize();
				$aExtraParams['display_limit'] = false; // To expand the full list
				$sExtraParams = addslashes(str_replace('"', "'", json_encode($aExtraParams))); // JSON encode, change the style of the quotes and escape them
				$sHtml .= '<tr class="containerHeader"><td>'.Dict::Format('UI:TruncatedResults', utils::GetConfig()->GetMinDisplayLimit(), $oSet->Count()).'&nbsp;&nbsp;<a href="Javascript:ReloadTruncatedList(\''.$divId.'\', \''.$sFilter.'\', \''.$sExtraParams.'\');">'.Dict::S('UI:DisplayAll').'</a></td><td>';
				$oPage->add_ready_script("$('#{$divId} table.listResults').addClass('truncated');");
				$oPage->add_ready_script("$('#{$divId} table.listResults tr:last td').addClass('truncated');");
			}
			else
			{
				// Full list
				$sHtml .= '<tr class="containerHeader"><td>&nbsp;'.Dict::Format('UI:CountOfResults', $oSet->Count()).'</td><td>';
			}
			$sHtml .= $oMenuBlock->GetRenderContent($oPage, $aMenuExtraParams);
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
				if ($oAttDef->IsExternalField())
				{
					$sExtKeyLabel = MetaModel::GetLabel($sClassName, $oAttDef->GetKeyAttCode());
					$sRemoteAttLabel = MetaModel::GetLabel($oAttDef->GetTargetClass(), $oAttDef->GetExtAttCode());
					$oTargetAttDef = MetaModel::GetAttributeDef($oAttDef->GetTargetClass(), $oAttDef->GetExtAttCode());
					$sSuffix = '';
					if ($oTargetAttDef->IsExternalKey())
					{
						$sSuffix = '->id';
					}
					
					$aHeader[] = $sExtKeyLabel.'->'.$sRemoteAttLabel.$sSuffix;
				}
				else
				{
					$aHeader[] = MetaModel::GetLabel($sClassName, $sAttCode);
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
				$aRow[] = $oObj->GetKey();
				foreach($aList[$sClassName] as $sAttCode => $oAttDef)
				{
					$aRow[] = $oObj->GetAsCSV($sAttCode, $sSeparator, '\\');
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
			    $sClassName = get_class($oObj);
				$oPage->add("<$sClassName alias=\"$sAlias\" id=\"".$oObj->GetKey()."\">\n");
				foreach(MetaModel::ListAttributeDefs($sClassName) as $sAttCode=>$oAttDef)
				{
					if (($oAttDef->IsWritable()) && ($oAttDef->IsScalar()))
					{
						$sValue = $oObj->GetAsXML($sAttCode);
						$oPage->add("<$sAttCode>$sValue</$sAttCode>\n");
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

	// By rom
	function DisplayChangesLog(WebPage $oPage)
	{
		$oFltChangeOps = new CMDBSearchFilter('CMDBChangeOpSetAttribute');
		$oFltChangeOps->AddCondition('objkey', $this->GetKey(), '=');
		$oFltChangeOps->AddCondition('objclass', get_class($this), '=');
		$oSet = new CMDBObjectSet($oFltChangeOps, array('date' => false)); // order by date descending (i.e. false)
		$count = $oSet->Count();
		if ($count > 0)
		{
			$oPage->p(Dict::Format('UI:ChangesLogTitle', $count));
			self::DisplaySet($oPage, $oSet);
		}
		else
		{
			$oPage->p(Dict::S('UI:EmptyChangesLogTitle'));
		}
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
			$iSearchFormId++;
		}
		else
		{
			$iSearchFormId++;
			$sSearchFormId = 'SimpleSearchForm'.$iSearchFormId;
			$sHtml .= "<div id=\"$sSearchFormId\" class=\"mini_tab{$iSearchFormId}\">\n";			
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
			$sClassesCombo = "<select name=\"class\" onChange=\"ReloadSearchForm('$sSearchFormId', this.value, '$sRootClass')\">\n".implode('', $aOptions)."</select>\n";
		}
		else
		{
			$sClassesCombo = MetaModel::GetName($sClassName);
		}
		$oUnlimitedFilter = new DBObjectSearch($sClassName);
		$sHtml .= "<form id=\"form{$iSearchFormId}\">\n";
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
			$oAppContext->Reset($sFilterCode); // Make sure the same parameter will not be passed twice
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
			else
			{
				// Any value is possible, display an input box
				$sHtml .= "<label>".MetaModel::GetFilterLabel($sClassName, $sFilterCode).":</label>&nbsp;<input class=\"textSearch\" name=\"$sFilterCode\" value=\"$sFilterValue\"/>\n";
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
		return $sHtml;
	}
	
	public static function GetFormElementForField($oPage, $sClass, $sAttCode, $oAttDef, $value = '', $sDisplayValue = '', $iId = '', $sNameSuffix = '', $iFlags = 0, $aArgs = array())
	{
		static $iInputId = 0;
		$sFieldPrefix = '';
		if (isset($aArgs['prefix']))
		{
			$sFieldPrefix = $aArgs['prefix'];
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
			$iInputId++;
			$iId = $iInputId;
		}

		if (!$oAttDef->IsExternalField())
		{
			$bMandatory = 0;
			if ( (!$oAttDef->IsNullAllowed()) || ($iFlags & OPT_ATT_MANDATORY))
			{
				$bMandatory = 1;
			}
			$sValidationField = "<span id=\"v_{$iId}\"></span>";
			$sHelpText = $oAttDef->GetHelpOnEdition();
			$aEventsList = array('validate');
			switch($oAttDef->GetEditClass())
			{
				case 'Date':
				case 'DateTime':
				$aEventsList[] ='keyup';
				$aEventsList[] ='change';
				$sHTMLValue = "<input title=\"$sHelpText\" class=\"date-pick\" type=\"text\" size=\"20\" name=\"attr_{$sFieldPrefix}{$sAttCode}{$sNameSuffix}\" value=\"$value\" id=\"$iId\"/>&nbsp;{$sValidationField}";
				break;
				
				case 'Password':
					$aEventsList[] ='keyup';
					$aEventsList[] ='change';
					$sHTMLValue = "<input title=\"$sHelpText\" type=\"password\" size=\"30\" name=\"attr_{$sFieldPrefix}{$sAttCode}{$sNameSuffix}\" value=\"$value\" id=\"$iId\"/>&nbsp;{$sValidationField}";
				break;
				
				case 'Text':
					$aEventsList[] ='keyup';
					$aEventsList[] ='change';
					$sHTMLValue = "<table><tr><td><textarea class=\"resizable\" title=\"$sHelpText\" name=\"attr_{$sFieldPrefix}{$sAttCode}{$sNameSuffix}\" rows=\"8\" cols=\"40\" id=\"$iId\">$value</textarea></td><td>{$sValidationField}</td></tr></table>";
				break;
	
				case 'LinkedSet':
					$aEventsList[] ='change';
					$oWidget = new UILinksWidget($sClass, $sAttCode, $iId, $sNameSuffix);
					$sHTMLValue = $oWidget->Display($oPage, $value);
				break;
							
				case 'Document':
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
				$oWidget = new UIPasswordWidget($sAttCode, $iId, $sNameSuffix);
				$sHTMLValue = $oWidget->Display($oPage, $aArgs);
				// Event list & validation is handled  directly by the widget
				break;
				
				case 'String':
				default:
					// #@# todo - add context information (depending on dimensions)
					$aAllowedValues = MetaModel::GetAllowedValues_att($sClass, $sAttCode, $aArgs);
					$iFieldSize = $oAttDef->GetMaxSize();
					if ($aAllowedValues !== null)
					{
						if (count($aAllowedValues) > 50)
						{
							// too many choices, use an autocomplete
							// The input for the auto complete
							if ($oAttDef->IsNull($value)) // Null values are displayed as ''
							{
								$sDisplayValue = '';
							}
							$sHTMLValue = "<input count=\"".count($aAllowedValues)."\" type=\"text\" id=\"label_$iId\" size=\"30\" maxlength=\"$iFieldSize\" value=\"$sDisplayValue\"/>&nbsp;{$sValidationField}";
							// another hidden input to store & pass the object's Id
							$sHTMLValue .= "<input type=\"hidden\" id=\"$iId\" name=\"attr_{$sFieldPrefix}{$sAttCode}{$sNameSuffix}\" value=\"$value\" />\n";
							$oPage->add_ready_script("\$('#label_$iId').autocomplete('./ajax.render.php', { scroll:true, minChars:3, onItemSelect:selectItem, onFindValue:findValue, formatItem:formatItem, autoFill:true, keyHolder:'#$iId', extraParams:{operation:'autocomplete', sclass:'$sClass',attCode:'".$sAttCode."'}});");
							$oPage->add_ready_script("\$('#label_$iId').blur(function() { $(this).search(); } );");
							$oPage->add_ready_script("\$('#label_$iId').result( function(event, data, formatted) { if (data) { $('#{$iId}').val(data[1]); $('#{$iId}').trigger('change'); } else { $('#{$iId}').val(''); $('#{$iId}').trigger('change');} } );");
							$aEventsList[] ='change';
						}
						else
						{
							// Few choices, use a normal 'select'
							// In case there are no valid values, the select will be empty, thus blocking the user from validating the form
							$sHTMLValue = "<select title=\"$sHelpText\" name=\"attr_{$sFieldPrefix}{$sAttCode}{$sNameSuffix}\" id=\"$iId\">\n";
							$sHTMLValue .= "<option value=\"0\">".Dict::S('UI:SelectOne')."</option>\n";
							foreach($aAllowedValues as $key => $display_value)
							{
								if ((count($aAllowedValues) == 1) && $bMandatory )
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
				$oPage->add_ready_script("$('#$iId').bind('".implode(' ', $aEventsList)."', function(evt, sFormId) { return ValidateField('$iId', '$sPattern', $bMandatory, sFormId, $sNullValue) } );"); // Bind to a custom event: validate
			}
			$aDependencies = MetaModel::GetDependentAttributes($sClass, $sAttCode); // List of attributes that depend on the current one
			if (count($aDependencies) > 0)
			{
				$oPage->add_ready_script("$('#$iId').bind('change', function(evt, sFormId) { return UpdateDependentFields(['".implode("','", $aDependencies)."']) } );"); // Bind to a custom event: validate
			}
		}
		return "<div>{$sHTMLValue}</div>";
	}
	
	public function DisplayModifyForm(WebPage $oPage, $aExtraParams = array())
	{
		static $iGlobalFormId = 0;
		$iGlobalFormId++;
		$this->m_iFormId = $iGlobalFormId;
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

		$oPage->AddTabContainer(OBJECT_PROPERTIES_TAB);
		$oPage->SetCurrentTabContainer(OBJECT_PROPERTIES_TAB);
		$oPage->SetCurrentTab(Dict::S('UI:PropertiesTab'));
		$aDetailsList = $this->FLattenZList(MetaModel::GetZListItems($sClass, 'details'));
		//$aFullList = MetaModel::ListAttributeDefs($sClass);
		$aList = array();
		// Compute the list of properties to display, first the attributes in the 'details' list, then 
		// all the remaining attributes that are not external fields
		foreach($aDetailsList as $sAttCode)
		{
			$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
			if (!$oAttDef->IsExternalField())
			{
				$aList[] = $sAttCode;
			}
		}

		foreach($aList as $sAttCode)
		{
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
						$aDetails[] = array('label' => $oAttDef->GetLabel(), 'value' => $sHTMLValue);
					}
					else
					{
						$iFlags = $this->GetAttributeFlags($sAttCode);				
						if ($iFlags & OPT_ATT_HIDDEN)
						{
							// Attribute is hidden, do nothing
						}
						else
						{
							if ($iFlags & OPT_ATT_READONLY)
							{
								// Attribute is read-only
								$sHTMLValue = $this->GetAsHTML($sAttCode);
							}
							else
							{
								$sValue = $this->Get($sAttCode);
								$sDisplayValue = $this->GetEditValue($sAttCode);
								$aArgs = array('this' => $this);
								$sInputId = $this->m_iFormId.'_'.$sAttCode;
								$sHTMLValue = "<span id=\"field_{$sInputId}\">".self::GetFormElementForField($oPage, $sClass, $sAttCode, $oAttDef, $sValue, $sDisplayValue, $sInputId, '', $iFlags, $aArgs).'</span>';
								$aFieldsMap[$sAttCode] = $sInputId;
								
							}
							$aDetails[] = array('label' => '<span title="'.$oAttDef->GetDescription().'">'.$oAttDef->GetLabel().'</span>', 'value' => $sHTMLValue);
						}
					}
				}
				else
				{
					$aDetails[] = array('label' => '<span title="'.$oAttDef->GetDescription().'">'.$oAttDef->GetLabel().'</span>', 'value' => $this->GetAsHTML($sAttCode));			
				}
			}
		}
		$oPage->details($aDetails);
		// Now display the relations, one tab per relation

		$this->DisplayBareRelations($oPage, true); // Edit mode

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
			$oPage->add("<button type=\"button\" class=\"action\" onClick=\"BackToDetails('$sClass', $iKey)\"><span>".Dict::S('UI:Button:Cancel')."</span></button>&nbsp;&nbsp;&nbsp;&nbsp;\n");
			$oPage->add("<button type=\"submit\" class=\"action\"><span>".Dict::S('UI:Button:Apply')."</span></button>\n");
		}
		else
		{
			// The object does not exist in the database it's a creation
			$oPage->add("<input type=\"hidden\" name=\"operation\" value=\"apply_new\">\n");			
			$oPage->add("<button type=\"button\" class=\"action\" onClick=\"goBack()\"><span>".Dict::S('UI:Button:Cancel')."</span></button>&nbsp;&nbsp;&nbsp;&nbsp;\n");
			$oPage->add("<button type=\"submit\" class=\"action\"><span>".Dict::S('UI:Button:Create')."</span></button>\n");
		}
		$oPage->add("</form>\n");
		
		$iFieldsCount = count($aFieldsMap);
		$sJsonFieldsMap = json_encode($aFieldsMap);

		$oPage->add_script(
<<<EOF
		// Create the object once at the beginning of the page...
		var oWizardHelper = new WizardHelper('$sClass');
		oWizardHelper.SetFieldsMap($sJsonFieldsMap);
		oWizardHelper.SetFieldsCount($iFieldsCount);
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
			$sTargetState = MetaModel::GetDefaultState($sClass);
			$oObj = MetaModel::NewObject($sClass);
			$oObj->Set($sStateAttCode, $sTargetState);
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
			$bMandatory = false;
			$aAllowedValues = MetaModel::GetAllowedValues_att($sClass, $sAttCode, $aArgs);
			if ($aArgs['default'][$sAttCode])
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
		return $oObj->DisplayModifyForm( $oPage, $aExtraParams = array());
	}

	protected static function ProcessZlist($aList, $aDetails, $sCurrentTab, $sCurrentCol, $sCurrentSet)
	{
		//echo "<pre>ZList: ";
		//print_r($aList);
		//echo "</pre>\n";
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
							$aDetails[$sName] = array('col1' => array('' => array()));
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
							$aDetails[$sCurrentTab][$sName] = array('' => array());
						}
						$aDetails = self::ProcessZlist($value, $aDetails, $sCurrentTab, $sName, '');
						break;
					}
				}
			}
			else
			{
				//echo "<p>Scalar value: $value, in [$sCurrentTab][$sCurrentCol][$sCurrentSet][]</p>\n";
				$aDetails[$sCurrentTab][$sCurrentCol][$sCurrentSet][] = $value;
			}
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
				$aResult = array_merge($aResult, $this->FlattenZList($value));
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
				$oPage->add("<pre>".htmlentities(MyHelpers::beautifulstr($data, 1000, true))."</pre>\n");			
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
		return HILIGHT_CLASS_NONE; // Not hilighted by default
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
}
?>

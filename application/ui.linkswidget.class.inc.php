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
 * Class UILinksWidget
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

require_once('../application/webpage.class.inc.php');
require_once('../application/displayblock.class.inc.php');

class UILinksWidget 
{
	protected $m_sClass;
	protected $m_sAttCode;
	protected $m_sNameSuffix;
	protected $m_iInputId;
	protected $m_aAttributes;
	protected $m_sExtKeyToRemote;
	protected $m_sLinkedClass;
	protected $m_sRemoteClass;
	protected static $iWidgetIndex = 0;
	
	public function __construct($sClass, $sAttCode, $iInputId, $sNameSuffix = '')
	{
		$this->m_sClass = $sClass;
		$this->m_sAttCode = $sAttCode;
		$this->m_sNameSuffix = $sNameSuffix;
		$this->m_iInputId = $iInputId;
		$this->m_aEditableFields = array();
		self::$iWidgetIndex++;
			
		$oAttDef = MetaModel::GetAttributeDef($this->m_sClass, $this->m_sAttCode);
		$this->m_sLinkedClass = $oAttDef->GetLinkedClass();
		$this->m_sExtKeyToRemote = $oAttDef->GetExtKeyToRemote();
		$oLinkingAttDef = 	MetaModel::GetAttributeDef($this->m_sLinkedClass, $this->m_sExtKeyToRemote);
		$this->m_sRemoteClass = $oLinkingAttDef->GetTargetClass();
		$sExtKeyToMe = $oAttDef->GetExtKeyToMe();
		$sStateAttCode = MetaModel::GetStateAttributeCode($this->m_sClass);
		$sDefaultState = MetaModel::GetDefaultState($this->m_sClass);		

		$this->m_aEditableFields = array();
		$this->m_aTableConfig = array();
		$this->m_aTableConfig['form::checkbox'] = array( 'label' => "<input class=\"select_all\" type=\"checkbox\" value=\"1\" onChange=\"var value = this.checked; $('#linkedset_{$this->m_sAttCode}{$this->m_sNameSuffix} .selection').each( function() { this.checked = value; } ); oWidget".self::$iWidgetIndex.".OnSelectChange();\">", 'description' => Dict::S('UI:SelectAllToggle+'));

		foreach(MetaModel::ListAttributeDefs($this->m_sLinkedClass) as $sAttCode=>$oAttDef)
		{
			if ($sStateAttCode == $sAttCode)
			{
				// State attribute is always hidden from the UI
			}
			else if (!$oAttDef->IsExternalField() && ($sAttCode != $sExtKeyToMe) && ($sAttCode != $this->m_sExtKeyToRemote) && ($sAttCode != 'finalclass'))
			{
				$iFlags = MetaModel::GetAttributeFlags($this->m_sLinkedClass, $sDefaultState, $sAttCode);				
				if ( !($iFlags & OPT_ATT_HIDDEN) && !($iFlags & OPT_ATT_READONLY) )
				{
					$this->m_aEditableFields[] = $sAttCode;
					$this->m_aTableConfig[$sAttCode] = array( 'label' => $oAttDef->GetLabel(), 'description' => $oAttDef->GetDescription());				
				}
			}
		}
		foreach(MetaModel::GetZListItems($this->m_sRemoteClass, 'list') as $sFieldCode)
		{
			// TO DO: check the state of the attribute: hidden or visible ?
			if ($sFieldCode != 'finalclass')
			{
				$oAttDef = MetaModel::GetAttributeDef($this->m_sRemoteClass, $sFieldCode);
				$this->m_aTableConfig['static::'.$sFieldCode] = array( 'label' => $oAttDef->GetLabel(), 'description' => $oAttDef->GetDescription());
			}
		}
	}
	
	/**
	 * A one-row form for editing a link record
	 * @param WebPage $oP Web page used for the ouput
	 * @param DBObject $oLinkedObj The object to which all the elements of the linked set refer to
	 * @param mixed $linkObjOrId Either the object linked or a unique number for new link records to add
	 * @param Hash $aArgs Extra context arguments
	 * @return string The HTML fragment of the one-row form
	 */
	protected function GetFormRow(WebPage $oP, DBObject $oLinkedObj, $linkObjOrId = null, $aArgs = array() )
	{
		$sPrefix = "$this->m_sAttCode{$this->m_sNameSuffix}";
		$aRow = array();
		if(is_object($linkObjOrId))
		{
			$key = $linkObjOrId->GetKey();
			$sPrefix .= "[$key][";
			$sNameSuffix = "]"; // To make a tabular form
			$aArgs['prefix'] = $sPrefix;
			$aRow['form::checkbox'] = "<input class=\"selection\" type=\"checkbox\" onChange=\"oWidget".self::$iWidgetIndex.".OnSelectChange();\" value=\"$key\">";
			foreach($this->m_aEditableFields as $sFieldCode)
			{
				$oAttDef = MetaModel::GetAttributeDef($this->m_sLinkedClass, $sFieldCode);
				$aRow[$sFieldCode] = cmdbAbstractObject::GetFormElementForField($oP, $this->m_sLinkedClass, $sFieldCode, $oAttDef, $linkObjOrId->Get($sFieldCode), '' /* DisplayValue */, $key, $sNameSuffix, 0, $aArgs);
			}
		}
		else
		{
			// form for creating a new record
			$sPrefix .= "[$linkObjOrId][";
			$sNameSuffix = "]"; // To make a tabular form
			$aArgs['prefix'] = $sPrefix;
			$aRow['form::checkbox'] = "<input class=\"selection\" type=\"checkbox\" onChange=\"oWidget".self::$iWidgetIndex.".OnSelectChange();\" value=\"$linkObjOrId\">";
			$aRow['form::checkbox'] .= "<input type=\"hidden\" name=\"attr_attr_{$sPrefix}id{$sNameSuffix}\" value=\"\">";
			foreach($this->m_aEditableFields as $sFieldCode)
			{
				$oAttDef = MetaModel::GetAttributeDef($this->m_sLinkedClass, $sFieldCode);
				$aRow[$sFieldCode] = cmdbAbstractObject::GetFormElementForField($oP, $this->m_sLinkedClass, $sFieldCode, $oAttDef, '' /* TO DO/ call GetDefaultValue($oObject->ToArgs()) */, '' /* DisplayValue */, '' /* id */, $sNameSuffix, 0, $aArgs);
			}
		}

		foreach(MetaModel::GetZListItems($this->m_sRemoteClass, 'list') as $sFieldCode)
		{
			$aRow['static::'.$sFieldCode] = $oLinkedObj->GetAsHTML($sFieldCode);
		}
		return $aRow;
	}

	/**
	 * Display one row of the whole form
	 * @return none
	 */
	protected function DisplayFormRow(WebPage $oP, $aConfig, $aRow, $iRowId)
	{
		$sHtml = '';
		$sHtml .= "<tr id=\"{$this->m_sAttCode}{$this->m_sNameSuffix}_row_$iRowId\">\n";
		foreach($aConfig as $sName=>$void)
		{
			$sHtml .= "<td>".$aRow[$sName]."</td>\n";
		}
		$sHtml .= "</tr>\n";
		
		return $sHtml;
	}
	
	/**
	 * Display the table with the form for editing all the links at once
	 * @param WebPage $oP The web page used for the output
	 * @param Hash $aConfig The table's header configuration
	 * @param Hash $aData The tabular data to be displayed
	 * @return string Html fragment representing the form table
	 */
	protected function DisplayFormTable(WebPage $oP, $aConfig, $aData)
	{
		$sHtml = '';
		$sHtml .= "<table class=\"listResults\">\n";
		// Header
		$sHtml .= "<thead>\n";
		$sHtml .= "<tr>\n";
		foreach($aConfig as $sName=>$aDef)
		{
			$sHtml .= "<th title=\"".$aDef['description']."\">".$aDef['label']."</th>\n";
		}
		$sHtml .= "</tr>\n";
		$sHtml .= "</thead>\n";
		
		// Content
		$sHtml .= "</tbody>\n";
		if (count($aData) == 0)
		{
			$sHtml .= "<tr id=\"{$this->m_sAttCode}{$this->m_sNameSuffix}_empty_row\"><td colspan=\"".count($aConfig)."\" style=\"text-align:center;\">".Dict::S('UI:Message:EmptyList:UseAdd')."<input type=\"hidden\" name=\"attr_{$this->m_sAttCode}{$this->m_sNameSuffix}\" value=\"\"></td></td>";
		}
		else
		{
			foreach($aData as $iRowId => $aRow)
			{
				$sHtml .= $this->DisplayFormRow($oP, $aConfig, $aRow, $iRowId);
			}		
		}
		$sHtml .= "</tbody>\n";
		
		// Footer
		$sHtml .= "</table>\n";
		
		return $sHtml;
	}
	

	/**
	 * Get the HTML fragment corresponding to the linkset editing widget
	 * @param WebPage $oP The web page used for all the output
	 * @param DBObjectSet The initial value of the linked set
	 * @param Hash $aArgs Extra context arguments
	 * @return string The HTML fragment to be inserted into the page
	 */
	public function Display(WebPage $oPage, DBObjectSet $oValue, $aArgs = array())
	{
		$iWidgetIndex = self::$iWidgetIndex;
		$sHtmlValue = '';
		$sTargetClass = self::GetTargetClass($this->m_sClass, $this->m_sAttCode);
		$sHtmlValue .= "<div id=\"linkedset_{$this->m_sAttCode}{$this->m_sNameSuffix}\">\n";
		$oValue->Rewind();
		$aForm = array();
		$oContext = new UserContext();
		while($oCurrentLink = $oValue->Fetch())
		{
			$aRow = array();
			$key = $oCurrentLink->GetKey();
			$oLinkedObj = $oContext->GetObject($this->m_sRemoteClass, $oCurrentLink->Get($this->m_sExtKeyToRemote));

			$aForm[$key] = $this->GetFormRow($oPage, $oLinkedObj, $oCurrentLink, $aArgs);
		}
		$sHtmlValue .= $this->DisplayFormTable($oPage, $this->m_aTableConfig, $aForm);
		$oPage->add_ready_script(<<<EOF
		oWidget$iWidgetIndex = new LinksWidget('{$this->m_sAttCode}{$this->m_sNameSuffix}', '{$this->m_sClass}', '{$this->m_sAttCode}', '{$this->m_iInputId}', '{$this->m_sNameSuffix}');
		oWidget$iWidgetIndex.Init();
EOF
);
		$sHtmlValue .= "<span style=\"float:left;\">&nbsp;&nbsp;&nbsp;<img src=\"../images/tv-item-last.gif\">&nbsp;&nbsp;<input id=\"{$this->m_sAttCode}{$this->m_sNameSuffix}_btnRemove\" type=\"button\" value=\"".Dict::S('UI:RemoveLinkedObjectsOf_Class')."\" onClick=\"oWidget$iWidgetIndex.RemoveSelected();\" >";
		$sHtmlValue .= "&nbsp;&nbsp;&nbsp;<input id=\"{$this->m_sAttCode}{$this->m_sNameSuffix}_btnAdd\" type=\"button\" value=\"".Dict::Format('UI:AddLinkedObjectsOf_Class', MetaModel::GetName($this->m_sRemoteClass))."\" onClick=\"oWidget$iWidgetIndex.AddObjects();\"></span>\n";
		$sHtmlValue .= "<span style=\"clear:both;\"><p>&nbsp;</p></span>\n";
		$sHtmlValue .= "</div>\n";
		$oPage->add_at_the_end($this->GetObjectPickerDialog($oPage)); // To prevent adding forms inside the main form
		return $sHtmlValue;
	}
	
	/**
	 * This static function is called by the Ajax Page when there is a need to fill an autocomplete combo
	 * @param $oPage WebPage The ajax page used for the output (sent back to the browser)
	 * @param $oContext UserContext The context of the user (for limiting the search)
	 * @param $sClass string The name of the class of the current object being edited
	 * @param $sAttCode string The name of the attribute being edited
	 * @param $sName string The partial name that was typed by the user
	 * @param $iMaxCount integer The maximum number of items to return
	 * @return void
	 */	 	 	  	 	 	 	
	static public function Autocomplete(WebPage $oPage, UserContext $oContext, $sClass, $sAttCode, $sName, $iMaxCount)
	{
		// #@# todo - add context information, otherwise any value will be authorized for external keys
		$aAllowedValues = MetaModel::GetAllowedValues_att($sClass, $sAttCode, array() /* $aArgs */, $sName);
		if ($aAllowedValues != null)
		{
			$iCount = $iMaxCount;
			foreach($aAllowedValues as $key => $value)
			{
				$oPage->add($value."|".$key."\n");
				$iCount--;
				if ($iCount == 0) break;
			}
		}
		else // No limitation to the allowed values
		{
			// Search for all the object of the linked class
			$oAttDef = 	$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
			$sLinkedClass = $oAttDef->GetLinkedClass();
			$sSearchClass = self::GetTargetClass($sClass, $sAttCode);
			$oFilter = $oContext->NewFilter($sSearchClass);
			$sSearchAttCode = MetaModel::GetNameAttributeCode($sSearchClass);
			$oFilter->AddCondition($sSearchAttCode, $sName, 'Begins with');
			$oSet = new CMDBObjectSet($oFilter, array($sSearchAttCode => true));
			$iCount = 0;
			while( ($iCount < $iMaxCount) && ($oObj = $oSet->fetch()) )
			{
				$oPage->add($oObj->GetName()."|".$oObj->GetKey()."\n");
				$iCount++;
			}
		}
	}

	/**
	 * This static function is called by the Ajax Page display a set of objects being linked
	 * to the object being created	 
	 * @param $oPage WebPage The ajax page used for the put^put (sent back to the browser
	 * @param $sClass string The name of the 'linking class' which is the class of the objects to display
	 * @param $sSet JSON serialized set of objects
	 * @param $sExtKeyToMe Name of the attribute in sClass that is pointing to a given object
	 * @param $iObjectId The id of the object $sExtKeyToMe is pointing to
	 * @return void
	 */	 	 	  	 	 	 	
	static public function RenderSet($oPage, $sClass, $sJSONSet, $sExtKeyToMe, $sExtKeyToRemote, $iObjectId)
	{
		$aSet = json_decode($sJSONSet, true); // true means hash array instead of object
		$oSet = CMDBObjectSet::FromScratch($sClass);
		foreach($aSet as $aObject)
		{
			$oObj = MetaModel::NewObject($sClass);
			foreach($aObject as $sAttCode => $value)
			{
				$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
				if ($oAttDef->IsExternalKey() && ($value != 0))
				{
					$oTargetObj = MetaModel::GetObject($oAttDef->GetTargetClass(), $value); // @@ optimization, don't do & query per object in the set !
					$oObj->Set($sAttCode, $oTargetObj);
				}
				else
				{
					$oObj->Set($sAttCode, $value);
				}

			}
			$oSet->AddObject($oObj);
		}
		$aExtraParams = array();
		$aExtraParams['link_attr'] = $sExtKeyToMe;
		$aExtraParams['object_id'] = $iObjectId;
		$aExtraParams['target_attr'] = $sExtKeyToRemote;
		$aExtraParams['menu'] = false;
		$aExtraParams['select'] = false;
		$aExtraParams['view_link'] = false;
		
		cmdbAbstractObject::DisplaySet($oPage, $oSet, $aExtraParams);
	}

	
	protected static function GetTargetClass($sClass, $sAttCode)
	{
		$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
		$sLinkedClass = $oAttDef->GetLinkedClass();
		switch(get_class($oAttDef))
		{
			case 'AttributeLinkedSetIndirect':
			$oLinkingAttDef = 	MetaModel::GetAttributeDef($sLinkedClass, $oAttDef->GetExtKeyToRemote());
			$sTargetClass = $oLinkingAttDef->GetTargetClass();
			break;

			case 'AttributeLinkedSet':
			$sTargetClass = $sLinkedClass;
			break;
		}
		
		return $sTargetClass;
	}
	
	protected function GetObjectPickerDialog($oPage)
	{
		$sHtml = "<div id=\"dlg_{$this->m_sAttCode}{$this->m_sNameSuffix}\">";
		//$oTargetObj = $oContext->GetObject($sTargetClass, $this->m_iObjectId);
		$sHtml .= "<div class=\"wizContainer\">\n";
		//$sHtml .= "<div class=\"page_header\">\n");
		//$sHtml .= "<h1>".Dict::Format('UI:AddObjectsOf_Class_LinkedWith_Class_Instance', MetaModel::GetName($this->m_sLinkedClass), MetaModel::GetName(get_class($oTargetObj)), "<span class=\"hilite\">".$oTargetObj->GetHyperlink()."</span>")."</h1>\n");
		//$sHtml .= "</div>\n");

		$oContext = new UserContext();
		$iWidgetIndex = self::$iWidgetIndex;
		$oFilter = $oContext->NewFilter($this->m_sRemoteClass);
		$oSet = new CMDBObjectSet($oFilter);
		$oBlock = new DisplayBlock($oFilter, 'search', false);
		$sHtml .= $oBlock->GetDisplay($oPage, "SearchFormToAdd_{$this->m_sAttCode}{$this->m_sNameSuffix}", array('open' => true));
		$sHtml .= "<form id=\"ObjectsAddForm_{$this->m_sAttCode}{$this->m_sNameSuffix}\" OnSubmit=\"return oWidget$iWidgetIndex.DoAddObjects(this.id);\">\n";
		$sHtml .= "<div id=\"SearchResultsToAdd_{$this->m_sAttCode}{$this->m_sNameSuffix}\">\n";
		$sHtml .= "<div style=\"height: 100px; background: #fff;border-color:#F6F6F1 #E6E6E1 #E6E6E1 #F6F6F1; border-style:solid; border-width:3px; text-align: center; vertical-align: center;\"><p>".Dict::S('UI:Message:EmptyList:UseSearchForm')."</p></div>\n";
		$sHtml .= "</div>\n";
		$sHtml .= "<input type=\"button\" value=\"".Dict::S('UI:Button:Cancel')."\" onClick=\"$('#dlg_{$this->m_sAttCode}{$this->m_sNameSuffix}').dialog('close');\">&nbsp;&nbsp;<input type=\"submit\" value=\"".Dict::S('UI:Button:Add')."\">";
		$sHtml .= "</div>\n";
		$sHtml .= "</form>\n";
		$sHtml .= "</div>\n";
		$oPage->add_ready_script("$('#dlg_{$this->m_sAttCode}{$this->m_sNameSuffix}').dialog({ autoOpen: false, modal: true });");
		$oPage->add_ready_script("$('#dlg_{$this->m_sAttCode}{$this->m_sNameSuffix}').dialog('option', {title:'".Dict::Format('UI:AddObjectsOf_Class_LinkedWith_Class_Instance', MetaModel::GetName($this->m_sLinkedClass), MetaModel::GetName($this->m_sClass), "<span class=\"hilite\"> ZZZZ </span>")."'});");
		$oPage->add_ready_script("$('#SearchFormToAdd_{$this->m_sAttCode}{$this->m_sNameSuffix} form').bind('submit.uilinksWizard', oWidget$iWidgetIndex.SearchObjectsToAdd);");
		return $sHtml;
	}

	/**
	 * Search for objects to be linked to the current object (i.e "remote" objects)
	 * @param WebPage $oP The page used for the output (usually an AjaxWebPage)
	 * @param UserContext $oContext User context to limit the search...
	 * @param string $sRemoteClass Name of the "remote" class to perform the search on, must be a derived class of m_sRemoteClass
	 * @param Array $aAlreadyLinkedIds List of IDs of objects of "remote" class already linked, to be filtered out of the search
	 */
	public function SearchObjectsToAdd(WebPage $oP, UserContext $oContext, $sRemoteClass = '', $aAlreadyLinkedIds = array())
	{
		if ($sRemoteClass != '')
		{
			// assert(MetaModel::IsParentClass($this->m_sRemoteClass, $sRemoteClass));
			$oFilter = $oContext->NewFilter($sRemoteClass);
		}
		else
		{
			// No remote class specified use the one defined in the linkedset
			$oFilter = $oContext->NewFilter($this->m_sRemoteClass);		
		}
		if (count($aAlreadyLinkedIds) > 0)
		{
			// Positive IDs correspond to existing link records
			// negative IDs correspond to "remote" objects to be linked
			$aLinkIds = array();
			$aRemoteObjIds = array();
			foreach($aAlreadyLinkedIds as $iId)
			{
				if ($iId > 0)
				{
					$aLinkIds[] = $iId;
				}
				else
				{
					$aRemoteObjIds[] = -$iId;
				}
			}
			
			if (count($aLinkIds) >0)
			{
				// Search for the links to find to which "remote" object they are linked
				$oLinkFilter = $oContext->NewFilter($this->m_sLinkedClass);
				$oLinkFilter->AddCondition('id', $aLinkIds, 'IN');
				$oLinkSet = new CMDBObjectSet($oLinkFilter);
				while($oLink = $oLinkSet->Fetch())
				{
					$aRemoteObjIds[] = $oLink->Get($this->m_sExtKeyToRemote);
				}
			}
			$oFilter->AddCondition('id', $aRemoteObjIds, 'NOTIN');
		}
		$oSet = new CMDBObjectSet($oFilter);
		$oBlock = new DisplayBlock($oFilter, 'list', false);
		$oBlock->Display($oP, 'ResultsToAdd', array('menu' => false, 'selection_mode' => true, 'display_limit' => false)); // Don't display the 'Actions' menu on the results
	}
	
	public function DoAddObjects(WebPage $oP, UserContext $oContext, $aLinkedObjectIds = array())
	{
		$aTable = array();
		foreach($aLinkedObjectIds as $iObjectId)
		{
			$oLinkedObj = $oContext->GetObject($this->m_sRemoteClass, $iObjectId);
			if (is_object($oLinkedObj))
			{
				$aRow = $this->GetFormRow($oP, $oLinkedObj, -$iObjectId ); // Not yet created link get negative Ids
				$oP->add($this->DisplayFormRow($oP, $this->m_aTableConfig, $aRow, -$iObjectId)); 
			}
			else
			{
				$oP->p(Dict::Format('UI:Error:Object_Class_Id_NotFound', $this->m_sLinkedClass, $iObjectId));
			}
		}
	}
}
?>

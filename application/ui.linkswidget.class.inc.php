<?php
// Copyright (C) 2010-2012 Combodo SARL
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
 * Class UILinksWidget
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

require_once(APPROOT.'/application/webpage.class.inc.php');
require_once(APPROOT.'/application/displayblock.class.inc.php');

class UILinksWidget 
{
	protected $m_sClass;
	protected $m_sAttCode;
	protected $m_sNameSuffix;
	protected $m_iInputId;
	protected $m_aAttributes;
	protected $m_sExtKeyToRemote;
	protected $m_sExtKeyToMe;
	protected $m_sLinkedClass;
	protected $m_sRemoteClass;
	protected $m_bDuplicatesAllowed;
	
	public function __construct($sClass, $sAttCode, $iInputId, $sNameSuffix = '', $bDuplicatesAllowed = false)
	{
		$this->m_sClass = $sClass;
		$this->m_sAttCode = $sAttCode;
		$this->m_sNameSuffix = $sNameSuffix;
		$this->m_iInputId = $iInputId;
		$this->m_bDuplicatesAllowed = $bDuplicatesAllowed;
		$this->m_aEditableFields = array();
			
		$oAttDef = MetaModel::GetAttributeDef($this->m_sClass, $this->m_sAttCode);
		$this->m_sLinkedClass = $oAttDef->GetLinkedClass();
		$this->m_sExtKeyToRemote = $oAttDef->GetExtKeyToRemote();
		$this->m_sExtKeyToMe = $oAttDef->GetExtKeyToMe();
		$oLinkingAttDef = 	MetaModel::GetAttributeDef($this->m_sLinkedClass, $this->m_sExtKeyToRemote);
		$this->m_sRemoteClass = $oLinkingAttDef->GetTargetClass();
		$sExtKeyToMe = $oAttDef->GetExtKeyToMe();
		$sStateAttCode = MetaModel::GetStateAttributeCode($this->m_sClass);
		$sDefaultState = MetaModel::GetDefaultState($this->m_sClass);		

		$this->m_aEditableFields = array();
		$this->m_aTableConfig = array();
		$this->m_aTableConfig['form::checkbox'] = array( 'label' => "<input class=\"select_all\" type=\"checkbox\" value=\"1\" onClick=\"CheckAll('#linkedset_{$this->m_sAttCode}{$this->m_sNameSuffix} .selection', this.checked); oWidget".$this->m_iInputId.".OnSelectChange();\">", 'description' => Dict::S('UI:SelectAllToggle+'));

		foreach(MetaModel::FlattenZList(MetaModel::GetZListItems($this->m_sLinkedClass, 'list')) as $sAttCode)
		{
			$oAttDef = MetaModel::GetAttributeDef($this->m_sLinkedClass, $sAttCode);
			if ($sStateAttCode == $sAttCode)
			{
				// State attribute is always hidden from the UI
			}
			else if ($oAttDef->IsWritable() && ($sAttCode != $sExtKeyToMe) && ($sAttCode != $this->m_sExtKeyToRemote) && ($sAttCode != 'finalclass'))
			{
				$iFlags = MetaModel::GetAttributeFlags($this->m_sLinkedClass, $sDefaultState, $sAttCode);				
				if ( !($iFlags & OPT_ATT_HIDDEN) && !($iFlags & OPT_ATT_READONLY) )
				{
					$this->m_aEditableFields[] = $sAttCode;
					$this->m_aTableConfig[$sAttCode] = array( 'label' => $oAttDef->GetLabel(), 'description' => $oAttDef->GetDescription());				
				}
			}
		}
		
		$this->m_aTableConfig['static::key'] = array( 'label' => MetaModel::GetName($this->m_sRemoteClass), 'description' => MetaModel::GetClassDescription($this->m_sRemoteClass));
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
	protected function GetFormRow(WebPage $oP, DBObject $oLinkedObj, $linkObjOrId = null, $aArgs = array(), $oCurrentObj )
	{
		$sPrefix = "$this->m_sAttCode{$this->m_sNameSuffix}";
		$aRow = array();
		$aFieldsMap = array();
		if(is_object($linkObjOrId) && (!$linkObjOrId->IsNew()))
		{
			$key = $linkObjOrId->GetKey();
			$iRemoteObjKey =  $linkObjOrId->Get($this->m_sExtKeyToRemote);
			$sPrefix .= "[$key][";
			$sNameSuffix = "]"; // To make a tabular form
			$aArgs['prefix'] = $sPrefix;
			$aArgs['wizHelper'] = "oWizardHelper{$this->m_iInputId}{$key}";
			$aArgs['this'] = $linkObjOrId;
			$aRow['form::checkbox'] = "<input class=\"selection\" type=\"checkbox\" onClick=\"oWidget".$this->m_iInputId.".OnSelectChange();\" value=\"$key\">";
			$aRow['form::checkbox'] .= "<input type=\"hidden\" name=\"attr_{$sPrefix}id{$sNameSuffix}\" value=\"$key\">";
			foreach($this->m_aEditableFields as $sFieldCode)
			{
				$sFieldId = $this->m_iInputId.'_'.$sFieldCode.'['.$linkObjOrId->GetKey().']';
				$sSafeId = utils::GetSafeId($sFieldId);
				$oAttDef = MetaModel::GetAttributeDef($this->m_sLinkedClass, $sFieldCode);
				$aRow[$sFieldCode] = cmdbAbstractObject::GetFormElementForField($oP, $this->m_sLinkedClass, $sFieldCode, $oAttDef, $linkObjOrId->Get($sFieldCode), '' /* DisplayValue */, $sSafeId, $sNameSuffix, 0, $aArgs);
				$aFieldsMap[$sFieldCode] = $sSafeId;
			}
			$sState = $linkObjOrId->GetState();
		}
		else
		{
			// form for creating a new record
			if (is_object($linkObjOrId))
			{
				// New link existing only in memory
				$oNewLinkObj = $linkObjOrId;
				$iRemoteObjKey = $oNewLinkObj->Get($this->m_sExtKeyToRemote);
				$oRemoteObj = MetaModel::GetObject($this->m_sRemoteClass, $iRemoteObjKey);
				$oNewLinkObj->Set($this->m_sExtKeyToMe, $oCurrentObj); // Setting the extkey with the object also fills the related external fields
				$linkObjOrId = -$iRemoteObjKey;
			}
			else
			{
				$iRemoteObjKey = -$linkObjOrId;
				$oNewLinkObj = MetaModel::NewObject($this->m_sLinkedClass);
				$oRemoteObj = MetaModel::GetObject($this->m_sRemoteClass, -$linkObjOrId);
				$oNewLinkObj->Set($this->m_sExtKeyToRemote, $oRemoteObj); // Setting the extkey with the object alsoo fills the related external fields
				$oNewLinkObj->Set($this->m_sExtKeyToMe, $oCurrentObj); // Setting the extkey with the object also fills the related external fields
			}
			$sPrefix .= "[$linkObjOrId][";
			$sNameSuffix = "]"; // To make a tabular form
			$aArgs['prefix'] = $sPrefix;
			$aArgs['wizHelper'] = "oWizardHelper{$this->m_iInputId}_".(-$linkObjOrId);
			$aArgs['this'] = $oNewLinkObj;
			$aRow['form::checkbox'] = "<input class=\"selection\" type=\"checkbox\" onClick=\"oWidget".$this->m_iInputId.".OnSelectChange();\" value=\"$linkObjOrId\">";
			$aRow['form::checkbox'] .= "<input type=\"hidden\" name=\"attr_{$sPrefix}id{$sNameSuffix}\" value=\"\">";
			foreach($this->m_aEditableFields as $sFieldCode)
			{
				$sFieldId = $this->m_iInputId.'_'.$sFieldCode.'['.$linkObjOrId.']';
				$sSafeId = utils::GetSafeId($sFieldId);
				$oAttDef = MetaModel::GetAttributeDef($this->m_sLinkedClass, $sFieldCode);
				$aRow[$sFieldCode] = cmdbAbstractObject::GetFormElementForField($oP, $this->m_sLinkedClass, $sFieldCode, $oAttDef, $oNewLinkObj->Get($sFieldCode) /* TO DO/ call GetDefaultValue($oObject->ToArgs()) */, '' /* DisplayValue */, $sSafeId /* id */, $sNameSuffix, 0, $aArgs);
				$aFieldsMap[$sFieldCode] = $sSafeId;
			}
			$sState = '';
			$oP->add_script(
<<<EOF
$(".date-pick").datepicker({
		showOn: 'button',
		buttonImage: '../images/calendar.png',
		buttonImageOnly: true,
		dateFormat: 'yy-mm-dd',
		constrainInput: false,
		changeMonth: true,
		changeYear: true
	});
$(".datetime-pick").datepicker({
		showOn: 'button',
		buttonImage: '../images/calendar.png',
		buttonImageOnly: true,
		dateFormat: 'yy-mm-dd 00:00:00',
		constrainInput: false,
		changeMonth: true,
		changeYear: true
});
EOF
			);
		}
		
		$sExtKeyToMeId = utils::GetSafeId($sPrefix.$this->m_sExtKeyToMe);
		$aFieldsMap[$this->m_sExtKeyToMe] = $sExtKeyToMeId;
		$aRow['form::checkbox'] .= "<input type=\"hidden\" id=\"$sExtKeyToMeId\" value=\"".$oCurrentObj->GetKey()."\">";

		$sExtKeyToRemoteId = utils::GetSafeId($sPrefix.$this->m_sExtKeyToRemote);
		$aFieldsMap[$this->m_sExtKeyToRemote] = $sExtKeyToRemoteId;
		$aRow['form::checkbox'] .= "<input type=\"hidden\" id=\"$sExtKeyToRemoteId\" value=\"$iRemoteObjKey\">";
		
		$iFieldsCount = count($aFieldsMap);
		$sJsonFieldsMap = json_encode($aFieldsMap);
		
		$oP->add_script(
<<<EOF
var {$aArgs['wizHelper']} = new WizardHelper('{$this->m_sLinkedClass}', '', '$sState');
{$aArgs['wizHelper']}.SetFieldsMap($sJsonFieldsMap);
{$aArgs['wizHelper']}.SetFieldsCount($iFieldsCount);
EOF
		);
		$aRow['static::key'] = $oLinkedObj->GetHyperLink();
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
		$sHtml = "<input type=\"hidden\" name=\"attr_{$this->m_sAttCode}{$this->m_sNameSuffix}\" value=\"\">";
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
		$sEmptyRowStyle = '';
		if (count($aData) != 0)
		{
			$sEmptyRowStyle = 'style="display:none;"';
		}

		foreach($aData as $iRowId => $aRow)
		{
			$sHtml .= $this->DisplayFormRow($oP, $aConfig, $aRow, $iRowId);
		}		
		$sHtml .= "<tr $sEmptyRowStyle id=\"{$this->m_sAttCode}{$this->m_sNameSuffix}_empty_row\"><td colspan=\"".count($aConfig)."\" style=\"text-align:center;\">".Dict::S('UI:Message:EmptyList:UseAdd')."</td></tr>";
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
	 * @param string $sFormPrefix prefix of the fields in the current form
	 * @param DBObject $oCurrentObj the current object to which the linkset is related
	 * @return string The HTML fragment to be inserted into the page
	 */
	public function Display(WebPage $oPage, DBObjectSet $oValue, $aArgs = array(), $sFormPrefix, $oCurrentObj)
	{
		$sHtmlValue = '';
		$sTargetClass = self::GetTargetClass($this->m_sClass, $this->m_sAttCode);
		$sHtmlValue .= "<div id=\"linkedset_{$this->m_sAttCode}{$this->m_sNameSuffix}\">\n";
		$sHtmlValue .= "<input type=\"hidden\" id=\"{$sFormPrefix}{$this->m_iInputId}\">\n";
		$oValue->Rewind();
		$aForm = array();
		while($oCurrentLink = $oValue->Fetch())
		{
			$aRow = array();
			$oLinkedObj = MetaModel::GetObject($this->m_sRemoteClass, $oCurrentLink->Get($this->m_sExtKeyToRemote));
			if ($oCurrentLink->IsNew())
			{
				$key = -$oLinkedObj->GetKey();
				$aForm[$key] = $this->GetFormRow($oPage, $oLinkedObj, $oCurrentLink, $aArgs, $oCurrentObj);
			}
			else
			{
				$key = $oCurrentLink->GetKey();
				$aForm[$key] = $this->GetFormRow($oPage, $oLinkedObj, $oCurrentLink, $aArgs, $oCurrentObj);
			}

		}
		$sHtmlValue .= $this->DisplayFormTable($oPage, $this->m_aTableConfig, $aForm);
		$sDuplicates = ($this->m_bDuplicatesAllowed) ? 'true' : 'false';
		$sWizHelper = 'oWizardHelper'.$sFormPrefix;
		$oPage->add_ready_script(<<<EOF
		oWidget{$this->m_iInputId} = new LinksWidget('{$this->m_sAttCode}{$this->m_sNameSuffix}', '{$this->m_sClass}', '{$this->m_sAttCode}', '{$this->m_iInputId}', '{$this->m_sNameSuffix}', $sDuplicates, $sWizHelper, '{$this->m_sExtKeyToRemote}');
		oWidget{$this->m_iInputId}.Init();
		$('#{$this->m_iInputId}').bind('update_value', function() { $(this).val(oWidget{$this->m_iInputId}.GetUpdatedValue()); })
EOF
);
		$sHtmlValue .= "<span style=\"float:left;\">&nbsp;&nbsp;&nbsp;<img src=\"../images/tv-item-last.gif\">&nbsp;&nbsp;<input id=\"{$this->m_sAttCode}{$this->m_sNameSuffix}_btnRemove\" type=\"button\" value=\"".Dict::S('UI:RemoveLinkedObjectsOf_Class')."\" onClick=\"oWidget{$this->m_iInputId}.RemoveSelected();\" >";
		$sHtmlValue .= "&nbsp;&nbsp;&nbsp;<input id=\"{$this->m_sAttCode}{$this->m_sNameSuffix}_btnAdd\" type=\"button\" value=\"".Dict::Format('UI:AddLinkedObjectsOf_Class', MetaModel::GetName($this->m_sRemoteClass))."\" onClick=\"oWidget{$this->m_iInputId}.AddObjects();\"><span id=\"{$this->m_sAttCode}{$this->m_sNameSuffix}_indicatorAdd\"></span></span>\n";
		$sHtmlValue .= "<span style=\"clear:both;\"><p>&nbsp;</p></span>\n";
		$sHtmlValue .= "</div>\n";
		$oPage->add_at_the_end("<div id=\"dlg_{$this->m_sAttCode}{$this->m_sNameSuffix}\"></div>"); // To prevent adding forms inside the main form
		return $sHtmlValue;
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
	
	public function GetObjectPickerDialog($oPage, $oCurrentObj)
	{
		$sHtml = "<div class=\"wizContainer\" style=\"vertical-align:top;\">\n";
		$oFilter = new DBObjectSearch($this->m_sRemoteClass);
		$this->SetSearchDefaultFromContext($oCurrentObj, $oFilter);
		$oBlock = new DisplayBlock($oFilter, 'search', false);
		$sHtml .= $oBlock->GetDisplay($oPage, "SearchFormToAdd_{$this->m_sAttCode}{$this->m_sNameSuffix}", array('open' => true));
		$sHtml .= "<form id=\"ObjectsAddForm_{$this->m_sAttCode}{$this->m_sNameSuffix}\" OnSubmit=\"return oWidget{$this->m_iInputId}.DoAddObjects(this.id);\">\n";
		$sHtml .= "<div id=\"SearchResultsToAdd_{$this->m_sAttCode}{$this->m_sNameSuffix}\" style=\"vertical-align:top;background: #fff;height:100%;overflow:auto;padding:0;border:0;\">\n";
		$sHtml .= "<div style=\"background: #fff; border:0; text-align:center; vertical-align:middle;\"><p>".Dict::S('UI:Message:EmptyList:UseSearchForm')."</p></div>\n";
		$sHtml .= "</div>\n";
		$sHtml .= "<input type=\"hidden\" id=\"count_{$this->m_sAttCode}{$this->m_sNameSuffix}\" value=\"0\"/>";
		$sHtml .= "<input type=\"button\" value=\"".Dict::S('UI:Button:Cancel')."\" onClick=\"$('#dlg_{$this->m_sAttCode}{$this->m_sNameSuffix}').dialog('close');\">&nbsp;&nbsp;<input id=\"btn_ok_{$this->m_sAttCode}{$this->m_sNameSuffix}\" disabled=\"disabled\" type=\"submit\" value=\"".Dict::S('UI:Button:Add')."\">";
		$sHtml .= "</div>\n";
		$sHtml .= "</form>\n";
		$oPage->add($sHtml);
		$oPage->add_ready_script("$('#dlg_{$this->m_sAttCode}{$this->m_sNameSuffix}').dialog({ width: $(window).width()*0.8, height: $(window).height()*0.8, autoOpen: false, modal: true, resizeStop: oWidget{$this->m_iInputId}.UpdateSizes });");
		$oPage->add_ready_script("$('#dlg_{$this->m_sAttCode}{$this->m_sNameSuffix}').dialog('option', {title:'".addslashes(Dict::Format('UI:AddObjectsOf_Class_LinkedWith_Class', MetaModel::GetName($this->m_sLinkedClass), MetaModel::GetName($this->m_sClass)))."'});");
		$oPage->add_ready_script("$('#SearchFormToAdd_{$this->m_sAttCode}{$this->m_sNameSuffix} form').bind('submit.uilinksWizard', oWidget{$this->m_iInputId}.SearchObjectsToAdd);");
		$oPage->add_ready_script("$('#SearchFormToAdd_{$this->m_sAttCode}{$this->m_sNameSuffix}').resize(oWidget{$this->m_iInputId}.UpdateSizes);");
	}

	/**
	 * Search for objects to be linked to the current object (i.e "remote" objects)
	 * @param WebPage $oP The page used for the output (usually an AjaxWebPage)
	 * @param string $sRemoteClass Name of the "remote" class to perform the search on, must be a derived class of m_sRemoteClass
	 * @param Array $aAlreadyLinkedIds List of IDs of objects of "remote" class already linked, to be filtered out of the search
	 */
	public function SearchObjectsToAdd(WebPage $oP, $sRemoteClass = '', $aAlreadyLinkedIds = array())
	{
		if ($sRemoteClass != '')
		{
			// assert(MetaModel::IsParentClass($this->m_sRemoteClass, $sRemoteClass));
			$oFilter = new DBObjectSearch($sRemoteClass);
		}
		else
		{
			// No remote class specified use the one defined in the linkedset
			$oFilter = new DBObjectSearch($this->m_sRemoteClass);		
		}
		if (!$this->m_bDuplicatesAllowed && count($aAlreadyLinkedIds) > 0)
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
				$oLinkFilter = new DBObjectSearch($this->m_sLinkedClass);
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
		$oBlock->Display($oP, "ResultsToAdd_{$this->m_sAttCode}", array('menu' => false, 'cssCount'=> '#count_'.$this->m_sAttCode.$this->m_sNameSuffix , 'selection_mode' => true, 'table_id' => 'add_'.$this->m_sAttCode)); // Don't display the 'Actions' menu on the results
	}
	
	public function DoAddObjects(WebPage $oP, $oFullSetFilter, $oCurrentObj)
	{
		$aLinkedObjectIds = utils::ReadMultipleSelection($oFullSetFilter);

		foreach($aLinkedObjectIds as $iObjectId)
		{
			$oLinkedObj = MetaModel::GetObject($this->m_sRemoteClass, $iObjectId);
			if (is_object($oLinkedObj))
			{
				$aRow = $this->GetFormRow($oP, $oLinkedObj, -$iObjectId, array(), $oCurrentObj ); // Not yet created link get negative Ids
				$oP->add($this->DisplayFormRow($oP, $this->m_aTableConfig, $aRow, -$iObjectId)); 
			}
			else
			{
				$oP->p(Dict::Format('UI:Error:Object_Class_Id_NotFound', $this->m_sLinkedClass, $iObjectId));
			}
		}
	}
	
	/**
	 * Initializes the default search parameters based on 1) a 'current' object and 2) the silos defined by the context
	 * @param DBObject $oSourceObj
	 * @param DBObjectSearch $oSearch
	 */
	protected function SetSearchDefaultFromContext($oSourceObj, &$oSearch)
	{
		$oAppContext = new ApplicationContext();
		$sSrcClass = get_class($oSourceObj);
		$sDestClass = $oSearch->GetClass();
		foreach($oAppContext->GetNames() as $key)
		{
			// Find the value of the object corresponding to each 'context' parameter
			$aCallSpec = array($sSrcClass, 'MapContextParam');
			$sAttCode = '';
			if (is_callable($aCallSpec))
			{
				$sAttCode = call_user_func($aCallSpec, $key); // Returns null when there is no mapping for this parameter					
			}

			if (MetaModel::IsValidAttCode($sSrcClass, $sAttCode))
			{
				$oAttDef = MetaModel::GetAttributeDef($sSrcClass, $sAttCode);
				$defaultValue = $oSourceObj->Get($sAttCode);

				// Find the attcode for the same 'context' parameter in the destination class
				// and sets its value as the default value for the search condition
				$aCallSpec = array($sDestClass, 'MapContextParam');
				$sAttCode = '';
				if (is_callable($aCallSpec))
				{
					$sAttCode = call_user_func($aCallSpec, $key); // Returns null when there is no mapping for this parameter					
				}
	
				if (MetaModel::IsValidAttCode($sDestClass, $sAttCode) && !empty($defaultValue))
				{
					$oSearch->AddCondition($sAttCode, $defaultValue);
				}
			}
		}
	}
}
?>

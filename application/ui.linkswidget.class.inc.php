<?php
// Copyright (C) 2010-2017 Combodo SARL
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
 * @copyright   Copyright (C) 2010-2017 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

require_once(APPROOT.'application/webpage.class.inc.php');
require_once(APPROOT.'application/displayblock.class.inc.php');

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
	protected $m_aEditableFields;
	protected $m_aTableConfig;

	/**
	 * UILinksWidget constructor.
	 *
	 * @param string $sClass
	 * @param string $sAttCode
	 * @param int $iInputId
	 * @param string $sNameSuffix
	 * @param bool $bDuplicatesAllowed
	 *
	 * @throws \CoreException
	 * @throws \DictExceptionMissingString
	 * @throws \Exception
	 */
	public function __construct($sClass, $sAttCode, $iInputId, $sNameSuffix = '', $bDuplicatesAllowed = false)
	{
		$this->m_sClass = $sClass;
		$this->m_sAttCode = $sAttCode;
		$this->m_sNameSuffix = $sNameSuffix;
		$this->m_iInputId = $iInputId;
		$this->m_bDuplicatesAllowed = $bDuplicatesAllowed;
		$this->m_aEditableFields = array();

		/** @var AttributeLinkedSetIndirect $oAttDef */
		$oAttDef = MetaModel::GetAttributeDef($this->m_sClass, $this->m_sAttCode);
		$this->m_sLinkedClass = $oAttDef->GetLinkedClass();
		$this->m_sExtKeyToRemote = $oAttDef->GetExtKeyToRemote();
		$this->m_sExtKeyToMe = $oAttDef->GetExtKeyToMe();

		/** @var AttributeExternalKey $oLinkingAttDef */
		$oLinkingAttDef = MetaModel::GetAttributeDef($this->m_sLinkedClass, $this->m_sExtKeyToRemote);
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
	 *
	 * @param WebPage $oP Web page used for the ouput
	 * @param DBObject $oLinkedObj Remote object
	 * @param mixed $linkObjOrId Either the object linked or a unique number for new link records to add
	 * @param array $aArgs Extra context arguments
	 * @param DBObject $oCurrentObj The object to which all the elements of the linked set refer to
	 * @param int $iUniqueId A unique identifier of new links
	 * @param boolean $bReadOnly Display link as editable or read-only. Default is false (editable)
	 *
	 * @return array The HTML fragment of the one-row form
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \Exception
	 */
	protected function GetFormRow(WebPage $oP, DBObject $oLinkedObj, $linkObjOrId, $aArgs, $oCurrentObj, $iUniqueId, $bReadOnly = false)
	{
		$sPrefix = "$this->m_sAttCode{$this->m_sNameSuffix}";
		$aRow = array();
		$aFieldsMap = array();
		$iKey = 0;
		if(is_object($linkObjOrId) && (!$linkObjOrId->IsNew()))
		{
			$iKey = $linkObjOrId->GetKey();
			$iRemoteObjKey =  $linkObjOrId->Get($this->m_sExtKeyToRemote);
			$sPrefix .= "[$iKey][";
			$sNameSuffix = "]"; // To make a tabular form
			$aArgs['prefix'] = $sPrefix;
			$aArgs['wizHelper'] = "oWizardHelper{$this->m_iInputId}{$iKey}";
			$aArgs['this'] = $linkObjOrId;

			if($bReadOnly)
            {
                $aRow['form::checkbox'] = "";
                foreach($this->m_aEditableFields as $sFieldCode)
                {
                    $sDisplayValue = $linkObjOrId->GetEditValue($sFieldCode);
                    $aRow[$sFieldCode] = $sDisplayValue;
                }
            }
            else
            {
                $aRow['form::checkbox'] = "<input class=\"selection\" data-remote-id=\"$iRemoteObjKey\" data-link-id=\"$iKey\" data-unique-id=\"$iUniqueId\" type=\"checkbox\" onClick=\"oWidget".$this->m_iInputId.".OnSelectChange();\" value=\"$iKey\">";
                foreach($this->m_aEditableFields as $sFieldCode)
                {
                    $sFieldId = $this->m_iInputId.'_'.$sFieldCode.'['.$linkObjOrId->GetKey().']';
                    $sSafeId = utils::GetSafeId($sFieldId);
                    $sValue = $linkObjOrId->Get($sFieldCode);
                    $sDisplayValue = $linkObjOrId->GetEditValue($sFieldCode);
                    $oAttDef = MetaModel::GetAttributeDef($this->m_sLinkedClass, $sFieldCode);
                    $aRow[$sFieldCode] = '<div class="field_container" style="border:none;"><div class="field_data"><div class="field_value">'.
                        cmdbAbstractObject::GetFormElementForField($oP, $this->m_sLinkedClass, $sFieldCode, $oAttDef, $sValue, $sDisplayValue, $sSafeId, $sNameSuffix, 0, $aArgs).
	                    '</div></div></div>';
                    $aFieldsMap[$sFieldCode] = $sSafeId;
                }
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
				$oNewLinkObj->Set($this->m_sExtKeyToMe, $oCurrentObj); // Setting the extkey with the object also fills the related external fields
			}
			else
			{
				$iRemoteObjKey = $linkObjOrId;
				$oNewLinkObj = MetaModel::NewObject($this->m_sLinkedClass);
				$oRemoteObj = MetaModel::GetObject($this->m_sRemoteClass, $iRemoteObjKey);
				$oNewLinkObj->Set($this->m_sExtKeyToRemote, $oRemoteObj); // Setting the extkey with the object alsoo fills the related external fields
				$oNewLinkObj->Set($this->m_sExtKeyToMe, $oCurrentObj); // Setting the extkey with the object also fills the related external fields
			}
			$sPrefix .= "[-$iUniqueId][";
			$sNameSuffix = "]"; // To make a tabular form
			$aArgs['prefix'] = $sPrefix;
			$aArgs['wizHelper'] = "oWizardHelper{$this->m_iInputId}_".($iUniqueId < 0 ? -$iUniqueId : $iUniqueId);
			$aArgs['this'] = $oNewLinkObj;
			$sInputValue = $iUniqueId > 0 ? "-$iUniqueId" : "$iUniqueId";
			$aRow['form::checkbox'] = "<input class=\"selection\" data-remote-id=\"$iRemoteObjKey\" data-link-id=\"0\" data-unique-id=\"$iUniqueId\" type=\"checkbox\" onClick=\"oWidget".$this->m_iInputId.".OnSelectChange();\" value=\"$sInputValue\">";

			if ($iUniqueId > 0)
			{
				// Rows created with ajax call need OnLinkAdded call.
				//
				$oP->add_ready_script(
					<<<EOF
PrepareWidgets();
oWidget{$this->m_iInputId}.OnLinkAdded($iUniqueId, $iRemoteObjKey);
EOF
				);
			}
			else
			{
				// Rows added before loading the form don't have to call OnLinkAdded.
				// Listeners are already present and DOM is not recreated
				$iPositiveUniqueId = -$iUniqueId;
				$oP->add_ready_script(<<<EOF
oWidget{$this->m_iInputId}.AddLink($iPositiveUniqueId, $iRemoteObjKey);
EOF
				);
			}

			foreach($this->m_aEditableFields as $sFieldCode)
			{
				$sFieldId = $this->m_iInputId.'_'.$sFieldCode.'['.-$iUniqueId.']';
				$sSafeId = utils::GetSafeId($sFieldId);
				$sValue = $oNewLinkObj->Get($sFieldCode);
				$sDisplayValue = $oNewLinkObj->GetEditValue($sFieldCode);
				$oAttDef = MetaModel::GetAttributeDef($this->m_sLinkedClass, $sFieldCode);
				$aRow[$sFieldCode] = '<div class="field_container" style="border:none;"><div class="field_data"><div class="field_value">'.
					cmdbAbstractObject::GetFormElementForField($oP, $this->m_sLinkedClass, $sFieldCode, $oAttDef, $sValue, $sDisplayValue, $sSafeId /* id */, $sNameSuffix, 0, $aArgs).
					'</div></div></div>';
				$aFieldsMap[$sFieldCode] = $sSafeId;
				$oP->add_ready_script(<<<EOF
oWidget{$this->m_iInputId}.OnValueChange($iKey, $iUniqueId, '$sFieldCode', '$sValue');
EOF
					);
			}
			$sState = '';
		}

		if(!$bReadOnly)
        {
            $sExtKeyToMeId = utils::GetSafeId($sPrefix.$this->m_sExtKeyToMe);
            $aFieldsMap[$this->m_sExtKeyToMe] = $sExtKeyToMeId;
            $aRow['form::checkbox'] .= "<input type=\"hidden\" id=\"$sExtKeyToMeId\" value=\"".$oCurrentObj->GetKey()."\">";

            $sExtKeyToRemoteId = utils::GetSafeId($sPrefix.$this->m_sExtKeyToRemote);
            $aFieldsMap[$this->m_sExtKeyToRemote] = $sExtKeyToRemoteId;
            $aRow['form::checkbox'] .= "<input type=\"hidden\" id=\"$sExtKeyToRemoteId\" value=\"$iRemoteObjKey\">";
        }

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
	 * @param WebPage $oP
	 * @param array $aConfig
	 * @param array $aRow
	 * @param int $iRowId
	 * @return string
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
	 * @param array $aConfig The table's header configuration
	 * @param array $aData The tabular data to be displayed
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
	 *
	 * @param WebPage $oPage
	 * @param DBObject|ormLinkSet $oValue
	 * @param array $aArgs Extra context arguments
	 * @param string $sFormPrefix prefix of the fields in the current form
	 * @param DBObject $oCurrentObj the current object to which the linkset is related
	 *
	 * @return string The HTML fragment to be inserted into the page
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \DictExceptionMissingString
	 */
	public function Display(WebPage $oPage, $oValue, $aArgs, $sFormPrefix, $oCurrentObj)
	{
		$sHtmlValue = '';
		$sHtmlValue .= "<div id=\"linkedset_{$this->m_sAttCode}{$this->m_sNameSuffix}\">\n";
		$sHtmlValue .= "<input type=\"hidden\" id=\"{$sFormPrefix}{$this->m_iInputId}\">\n";
		$oValue->Rewind();
		$aForm = array();
		$iAddedId = -1; // Unique id for new links

		$sDuplicates = ($this->m_bDuplicatesAllowed) ? 'true' : 'false';
		// Don't automatically launch the search if the table is huge
		$bDoSearch = !utils::IsHighCardinality($this->m_sRemoteClass);
		$sJSDoSearch = $bDoSearch ? 'true' : 'false';
		$sWizHelper = 'oWizardHelper'.$sFormPrefix;
		$oPage->add_ready_script(<<<EOF
		oWidget{$this->m_iInputId} = new LinksWidget('{$this->m_sAttCode}{$this->m_sNameSuffix}', '{$this->m_sClass}', '{$this->m_sAttCode}', '{$this->m_iInputId}', '{$this->m_sNameSuffix}', $sDuplicates, $sWizHelper, '{$this->m_sExtKeyToRemote}', $sJSDoSearch);
		oWidget{$this->m_iInputId}.Init();
EOF
		);

		while($oCurrentLink = $oValue->Fetch())
		{
		    // We try to retrieve the remote object as usual
			$oLinkedObj = MetaModel::GetObject($this->m_sRemoteClass, $oCurrentLink->Get($this->m_sExtKeyToRemote), false /* Must not be found */);
			// If successful, it means that we can edit its link
			if($oLinkedObj !== null)
            {
                $bReadOnly = false;
            }
            // Else we retrieve it without restrictions (silos) and will display its link as readonly
            else
            {
                $bReadOnly = true;
                $oLinkedObj = MetaModel::GetObject($this->m_sRemoteClass, $oCurrentLink->Get($this->m_sExtKeyToRemote), false /* Must not be found */, true);
            }

            if ($oCurrentLink->IsNew())
            {
                $key = $iAddedId--;
            }
            else
            {
                $key = $oCurrentLink->GetKey();
            }
            $aForm[$key] = $this->GetFormRow($oPage, $oLinkedObj, $oCurrentLink, $aArgs, $oCurrentObj, $key, $bReadOnly);
		}
		$sHtmlValue .= $this->DisplayFormTable($oPage, $this->m_aTableConfig, $aForm);

		$sHtmlValue .= "<span style=\"float:left;\">&nbsp;&nbsp;&nbsp;<img src=\"../images/tv-item-last.gif\">&nbsp;&nbsp;<input id=\"{$this->m_sAttCode}{$this->m_sNameSuffix}_btnRemove\" type=\"button\" value=\"".Dict::S('UI:RemoveLinkedObjectsOf_Class')."\" onClick=\"oWidget{$this->m_iInputId}.RemoveSelected();\" >";
		$sHtmlValue .= "&nbsp;&nbsp;&nbsp;<input id=\"{$this->m_sAttCode}{$this->m_sNameSuffix}_btnAdd\" type=\"button\" value=\"".Dict::Format('UI:AddLinkedObjectsOf_Class', MetaModel::GetName($this->m_sRemoteClass))."\" onClick=\"oWidget{$this->m_iInputId}.AddObjects();\"><span id=\"{$this->m_sAttCode}{$this->m_sNameSuffix}_indicatorAdd\"></span></span>\n";
		$sHtmlValue .= "<span style=\"clear:both;\"><p>&nbsp;</p></span>\n";
		$sHtmlValue .= "</div>\n";
		$oPage->add_at_the_end("<div id=\"dlg_{$this->m_sAttCode}{$this->m_sNameSuffix}\"></div>"); // To prevent adding forms inside the main form
        return $sHtmlValue;
	}

	/**
	 * @param string $sClass
	 * @param string $sAttCode
	 *
	 * @return string
	 * @throws \Exception
	 */
	protected static function GetTargetClass($sClass, $sAttCode)
	{
		/** @var AttributeLinkedSet $oAttDef */
		$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
		$sLinkedClass = $oAttDef->GetLinkedClass();
		$sTargetClass = '';
		switch(get_class($oAttDef))
		{
			case 'AttributeLinkedSetIndirect':
			/** @var AttributeExternalKey $oLinkingAttDef */
			/** @var AttributeLinkedSetIndirect $oAttDef */
			$oLinkingAttDef = 	MetaModel::GetAttributeDef($sLinkedClass, $oAttDef->GetExtKeyToRemote());
			$sTargetClass = $oLinkingAttDef->GetTargetClass();
			break;

			case 'AttributeLinkedSet':
			$sTargetClass = $sLinkedClass;
			break;
		}
		
		return $sTargetClass;
	}

	/**
	 * @param WebPage $oPage
	 * @param DBObject $oCurrentObj
	 * @param $sJson
	 * @param array $aAlreadyLinkedIds
	 *
	 * @throws DictExceptionMissingString
	 * @throws Exception
	 */
	public function GetObjectPickerDialog($oPage, $oCurrentObj, $sJson, $aAlreadyLinkedIds = array(), $aPrefillFormParam = array())
	{
		$sHtml = "<div class=\"wizContainer\" style=\"vertical-align:top;\">\n";

		$oAlreadyLinkedFilter = new DBObjectSearch($this->m_sRemoteClass);
		if (!$this->m_bDuplicatesAllowed && count($aAlreadyLinkedIds) > 0)
		{
			$oAlreadyLinkedFilter->AddCondition('id', $aAlreadyLinkedIds, 'NOTIN');
			$oAlreadyLinkedExpression = $oAlreadyLinkedFilter->GetCriteria();
			$sAlreadyLinkedExpression = $oAlreadyLinkedExpression->Render();
		}
		else
		{
			$sAlreadyLinkedExpression = '';
		}

		$oFilter = new DBObjectSearch($this->m_sRemoteClass);

		if(!empty($oCurrentObj))
		{
			$this->SetSearchDefaultFromContext($oCurrentObj, $oFilter);
			$aPrefillFormParam['filter'] = $oFilter;
			$aPrefillFormParam['dest_class'] = $this->m_sRemoteClass;
			$oCurrentObj->PrefillForm('search', $aPrefillFormParam);
		}
		$oBlock = new DisplayBlock($oFilter, 'search', false);
		$sHtml .= $oBlock->GetDisplay($oPage, "SearchFormToAdd_{$this->m_sAttCode}{$this->m_sNameSuffix}",
			array(
				'menu' => false,
				'result_list_outer_selector' => "SearchResultsToAdd_{$this->m_sAttCode}{$this->m_sNameSuffix}",
				'table_id' => 'add_'.$this->m_sAttCode,
				'table_inner_id' => "ResultsToAdd_{$this->m_sAttCode}{$this->m_sNameSuffix}",
				'selection_mode' => true,
				'json' => $sJson,
				'cssCount' => '#count_'.$this->m_sAttCode.$this->m_sNameSuffix,
				'query_params' => $oFilter->GetInternalParams(),
				'hidden_criteria' => $sAlreadyLinkedExpression,
			));
		$sHtml .= "<form id=\"ObjectsAddForm_{$this->m_sAttCode}{$this->m_sNameSuffix}\">\n";
		$sHtml .= "<div id=\"SearchResultsToAdd_{$this->m_sAttCode}{$this->m_sNameSuffix}\" style=\"vertical-align:top;background: #fff;height:100%;overflow:auto;padding:0;border:0;\">\n";
		$sHtml .= "<div style=\"background: #fff; border:0; text-align:center; vertical-align:middle;\"><p>".Dict::S('UI:Message:EmptyList:UseSearchForm')."</p></div>\n";
		$sHtml .= "</div>\n";
		$sHtml .= "<input type=\"hidden\" id=\"count_{$this->m_sAttCode}{$this->m_sNameSuffix}\" value=\"0\"/>";
		$sHtml .= "<input type=\"button\" value=\"".Dict::S('UI:Button:Cancel')."\" onClick=\"$('#dlg_{$this->m_sAttCode}{$this->m_sNameSuffix}').dialog('close');\">&nbsp;&nbsp;<input id=\"btn_ok_{$this->m_sAttCode}{$this->m_sNameSuffix}\" disabled=\"disabled\" type=\"button\" onclick=\"return oWidget{$this->m_iInputId}.DoAddObjects(this.id);\" value=\"".Dict::S('UI:Button:Add')."\">";
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
	 *
	 * @param WebPage $oP The page used for the output (usually an AjaxWebPage)
	 * @param string $sRemoteClass Name of the "remote" class to perform the search on, must be a derived class of
	 *     m_sRemoteClass
	 * @param array $aAlreadyLinkedIds List of IDs of objects of "remote" class already linked, to be filtered out of
	 *     the search
	 *
	 * @throws \CoreException
	 * @throws \Exception
	 */
	public function SearchObjectsToAdd(WebPage $oP, $sRemoteClass = '', $aAlreadyLinkedIds = array(), $oCurrentObj = null)
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
			$oFilter->AddCondition('id', $aAlreadyLinkedIds, 'NOTIN');
		}
		$this->SetSearchDefaultFromContext($oCurrentObj, $oFilter);
		$oBlock = new DisplayBlock($oFilter, 'list', false);
		$oBlock->Display($oP, "ResultsToAdd_{$this->m_sAttCode}", array('menu' => false, 'cssCount'=> '#count_'.$this->m_sAttCode.$this->m_sNameSuffix , 'selection_mode' => true, 'table_id' => 'add_'.$this->m_sAttCode)); // Don't display the 'Actions' menu on the results
	}

	/**
	 * @param WebPage $oP
	 * @param int $iMaxAddedId
	 * @param $oFullSetFilter
	 * @param DBObject $oCurrentObj
	 *
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 */
	public function DoAddObjects(WebPage $oP, $iMaxAddedId, $oFullSetFilter, $oCurrentObj)
	{
		$aLinkedObjectIds = utils::ReadMultipleSelection($oFullSetFilter);

		$iAdditionId = $iMaxAddedId + 1;
		foreach($aLinkedObjectIds as $iObjectId)
		{
			$oLinkedObj = MetaModel::GetObject($this->m_sRemoteClass, $iObjectId, false);
			if (is_object($oLinkedObj))
			{
				$aRow = $this->GetFormRow($oP, $oLinkedObj, $iObjectId, array(), $oCurrentObj, $iAdditionId); // Not yet created link get negative Ids
				$oP->add($this->DisplayFormRow($oP, $this->m_aTableConfig, $aRow, -$iAdditionId));
				$iAdditionId++;
			}
			else
			{
				$oP->p(Dict::Format('UI:Error:Object_Class_Id_NotFound', $this->m_sLinkedClass, $iObjectId));
			}
		}
	}

	/**
	 * Initializes the default search parameters based on 1) a 'current' object and 2) the silos defined by the context
	 *
	 * @param DBObject $oSourceObj
	 * @param DBSearch|DBObjectSearch $oSearch
	 *
	 * @throws \CoreException
	 * @throws \Exception
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
					// Add Hierarchical condition if hierarchical key
					$oAttDef = MetaModel::GetAttributeDef($sDestClass, $sAttCode);
					if (isset($oAttDef) && ($oAttDef->IsExternalKey()))
					{
						try
						{
							/** @var AttributeExternalKey $oAttDef */
							$sTargetClass = $oAttDef->GetTargetClass();
							$sHierarchicalKeyCode = MetaModel::IsHierarchicalClass($sTargetClass);
							if ($sHierarchicalKeyCode !== false)
							{
								$oFilter = new DBObjectSearch($sTargetClass);
								$oFilter->AddCondition('id', $defaultValue);
								$oHKFilter = new DBObjectSearch($sTargetClass);
								$oHKFilter->AddCondition_PointingTo($oFilter, $sHierarchicalKeyCode, TREE_OPERATOR_BELOW);
								$oSearch->AddCondition_PointingTo($oHKFilter, $sAttCode);
							}
						} catch (Exception $e)
						{
						}
					}
					else
					{
						$oSearch->AddCondition($sAttCode, $defaultValue);
					}
				}
			}
		}
	}
}

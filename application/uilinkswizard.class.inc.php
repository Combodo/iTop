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
 * Class UILinksWizard
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

class UILinksWizard
{
	protected $m_sClass;
	protected $m_sLinkageAttr;
	protected $m_iObjectId;
	protected $m_sLinkedClass;
	protected $m_sLinkingAttCode;
	protected $m_aEditableFields;
	protected $m_aTableConfig;
	
	public function __construct($sClass,  $sLinkageAttr, $iObjectId, $sLinkedClass = '')
	{
		$this->m_sClass = $sClass;
		$this->m_sLinkageAttr = $sLinkageAttr;
		$this->m_iObjectId = $iObjectId;
		$this->m_sLinkedClass = $sLinkedClass; // Will try to guess below, if it's empty
		$this->m_sLinkingAttCode = ''; // Will be filled once we've found the attribute corresponding to the linked class
		
		$this->m_aEditableFields = array();
		$this->m_aTableConfig = array();
		$this->m_aTableConfig['form::checkbox'] = array( 'label' => "<input class=\"select_all\" type=\"checkbox\" value=\"1\" onChange=\"var value = this.checked; $('.selection').each( function() { this.checked = value; } );OnSelectChange();\">", 'description' => Dict::S('UI:SelectAllToggle+'));
		foreach(MetaModel::GetAttributesList($this->m_sClass) as $sAttCode)
		{
			$oAttDef = MetaModel::GetAttributeDef($this->m_sClass, $sAttCode);
			if ($oAttDef->IsExternalKey() && ($sAttCode != $this->m_sLinkageAttr))
			{
				if (empty($this->m_sLinkedClass))
				{
					// This is a class of objects we can manage !
					// Since nothing was specify, any class will do !
					$this->m_sLinkedClass = $oAttDef->GetTargetClass();
					$this->m_sLinkingAttCode = $sAttCode;
				}
				else if ($this->m_sLinkedClass == $oAttDef->GetTargetClass())
				{
					// This is the class of objects we want to manage !
					$this->m_sLinkingAttCode = $sAttCode;
				}
			}
			else if ( (!$oAttDef->IsExternalKey()) && (!$oAttDef->IsExternalField()))
			{
				$this->m_aEditableFields[] = $sAttCode;
				$this->m_aTableConfig[$sAttCode] = array( 'label' => $oAttDef->GetLabel(), 'description' => $oAttDef->GetDescription());
			}
		}
		if (empty($this->m_sLinkedClass))
		{
			throw( new Exception(Dict::Format('UI:Error:IncorrectLinkDefinition_LinkedClass_Class', $sLinkedClass, $sClass)));
		}
		foreach(MetaModel::GetZListItems($this->m_sLinkedClass, 'list') as $sFieldCode)
		{
			$oAttDef = MetaModel::GetAttributeDef($this->m_sLinkedClass, $sFieldCode);
			$this->m_aTableConfig['static::'.$sFieldCode] = array( 'label' => $oAttDef->GetLabel(), 'description' => $oAttDef->GetDescription());
		}
	}

	public function Display(WebPage $oP, $aExtraParams = array())
	{
		$oAttDef = MetaModel::GetAttributeDef($this->m_sClass, $this->m_sLinkageAttr);
		$sTargetClass = $oAttDef->GetTargetClass();
		$oTargetObj = MetaModel::GetObject($sTargetClass, $this->m_iObjectId);

		$oP->set_title("iTop - ".MetaModel::GetName($this->m_sLinkedClass)." objects linked with ".MetaModel::GetName(get_class($oTargetObj)).": ".$oTargetObj->GetRawName());
		$oP->add("<div class=\"wizContainer\">\n");
		$oP->add("<form method=\"post\">\n");
		$oP->add("<div class=\"page_header\">\n");
		$oP->add("<input type=\"hidden\" id=\"linksToRemove\" name=\"linksToRemove\" value=\"\">\n");
		$oP->add("<input type=\"hidden\" name=\"operation\" value=\"do_modify_links\">\n");
		$oP->add("<input type=\"hidden\" name=\"class\" value=\"{$this->m_sClass}\">\n");
		$oP->add("<input type=\"hidden\" name=\"linkage\" value=\"{$this->m_sLinkageAttr}\">\n");
		$oP->add("<input type=\"hidden\" name=\"object_id\" value=\"{$this->m_iObjectId}\">\n");
		$oP->add("<input type=\"hidden\" name=\"linking_attcode\" value=\"{$this->m_sLinkingAttCode}\">\n");
		$oP->add("<h1>".Dict::Format('UI:ManageObjectsOf_Class_LinkedWith_Class_Instance', MetaModel::GetName($this->m_sLinkedClass), MetaModel::GetName(get_class($oTargetObj)), "<span class=\"hilite\">".$oTargetObj->GetHyperlink()."</span>")."</h1>\n");
		$oP->add("</div>\n");
		$oP->add_script(
<<<EOF
		function OnSelectChange()
		{
			var nbChecked = $('.selection:checked').length;
			if (nbChecked > 0)
			{
				$('#btnRemove').removeAttr('disabled');
			}
			else
			{
				$('#btnRemove').attr('disabled','disabled');
			}
		}
		
		function RemoveSelected()
		{
			$('.selection:checked').each(
				function()
				{
					$('#linksToRemove').val($('#linksToRemove').val() + ' ' + this.value);
					$('#row_'+this.value).remove();
				}
			);
			// Disable the button since all the selected items have been removed
			$('#btnRemove').attr('disabled','disabled');
			// Re-run the zebra plugin to properly highlight the remaining lines
			$('.listResults').trigger('update');
			
		}
		
		function AddObjects()
		{
			// TO DO: compute the list of objects already linked with the current Object
			$.post( GetAbsoluteUrlAppRoot()+'pages/ajax.render.php', { 'operation': 'addObjects',
										'class': '{$this->m_sClass}',
										'linkageAttr': '{$this->m_sLinkageAttr}',
										'linkedClass': '{$this->m_sLinkedClass}',
										'objectId': '{$this->m_iObjectId}'
										}, 
				function(data)
				{
					$('#ModalDlg').html(data);
					dlgWidth = $(document).width() - 100;
					$('#ModalDlg').css('width', dlgWidth);
					$('#ModalDlg').css('left', 50);
					$('#ModalDlg').css('top', 50);
					$('#ModalDlg').dialog( 'open' );
				},
				'html'
			);
		}
		
		function SearchObjectsToAdd(currentFormId)
		{
			var theMap = { 'class': '{$this->m_sClass}',
						   'linkageAttr': '{$this->m_sLinkageAttr}',
						   'linkedClass': '{$this->m_sLinkedClass}',
						   'objectId': '{$this->m_iObjectId}'
						 }
			if ($('#'+currentFormId+' :input[name=class]').val() != undefined)
			{
				theMap.linkedClass = $('#'+currentFormId+' :input[name=class]').val();
			}
			// Gather the parameters from the search form
			$('#'+currentFormId+' :input').each(
				function(i)
				{
					if (this.name != '')
					{
						theMap[this.name] = this.value;
					}
				}
			);
			theMap['operation'] = 'searchObjectsToAdd';
			
			// Run the query and display the results
			$.post( GetAbsoluteUrlAppRoot()+'pages/ajax.render.php', theMap, 
				function(data)
				{
					$('#SearchResultsToAdd').html(data);
					$('#SearchResultsToAdd .listResults').tablesorter( { headers: {0: false}}, widgets: ['myZebra', 'truncatedList']} ); // sortable and zebra tables
					
				},
				'html'
			);

			return false;
		}
		
		function DoAddObjects(currentFormId)
		{
			var theMap = { 'class': '{$this->m_sClass}',
						   'linkageAttr': '{$this->m_sLinkageAttr}',
						   'linkedClass': '{$this->m_sLinkedClass}',
						   'objectId': '{$this->m_iObjectId}'
						 }
			
			// Gather the parameters from the search form
			$('#'+currentFormId+' :input').each(
				function(i)
				{
					if ( (this.name != '') && ((this.type != 'checkbox') || (this.checked)) ) 
					{
						//console.log(this.type);
						arrayExpr = /\[\]$/;
						if (arrayExpr.test(this.name))
						{
							// Array
							if (theMap[this.name] == undefined)
							{
								theMap[this.name] = new Array();
							}
							theMap[this.name].push(this.value);
						}
						else
						{
							theMap[this.name] = this.value;
						}
					}
				}
			);
			theMap['operation'] = 'doAddObjects';
			
			// Run the query and display the results
			$.post( GetAbsoluteUrlAppRoot()+'pages/ajax.render.php', theMap, 
				function(data)
				{
					//console.log('Data: ' + data);
					if (data != '')
					{
						$('#empty_row').remove();
					}
					$('.listResults tbody').append(data);
					$('.listResults').trigger('update');
					$('.listResults').tablesorter( { headers: {0: false}}, widgets: ['myZebra', 'truncatedList']} ); // sortable and zebra tables
				},
				'html'
			);
			$('#ModalDlg').dialog('close');
			return false;
		}
		
		function InitForm()
		{
			// make sure that the form is clean
			$('.selection').each( function() { this.checked = false; });
			$('#btnRemove').attr('disabled','disabled');
			$('#linksToRemove').val('');
		}
		
		function SubmitHook() 
		{
			var the_form = this;
			SearchObjectsToAdd(the_form.id);
			return false;
		}
EOF
);
		$oP->add_ready_script("InitForm();");
		$oFilter = new DBObjectSearch($this->m_sClass);
		$oFilter->AddCondition($this->m_sLinkageAttr, $this->m_iObjectId, '=');
		$oSet = new DBObjectSet($oFilter);
		$aForm = array();
		while($oCurrentLink = $oSet->Fetch())
		{
			$aRow = array();
			$key = $oCurrentLink->GetKey();
			$oLinkedObj = MetaModel::GetObject($this->m_sLinkedClass, $oCurrentLink->Get($this->m_sLinkingAttCode));

			$aForm[$key] = $this->GetFormRow($oP, $oLinkedObj, $oCurrentLink);
		}
		//var_dump($aTableLabels);
		//var_dump($aForm);
		$this->DisplayFormTable($oP, $this->m_aTableConfig, $aForm);
		$oP->add("<span style=\"float:left;\">&nbsp;&nbsp;&nbsp;<img src=\"../images/tv-item-last.gif\">&nbsp;&nbsp;<input id=\"btnRemove\" type=\"button\" value=\"".Dict::S('UI:RemoveLinkedObjectsOf_Class')."\" onClick=\"RemoveSelected();\" >");
		$oP->add("&nbsp;&nbsp;&nbsp;<input id=\"btnAdd\" type=\"button\" value=\"".Dict::Format('UI:AddLinkedObjectsOf_Class', MetaModel::GetName($this->m_sLinkedClass))."\" onClick=\"AddObjects();\"></span>\n");
		$oP->add("<span style=\"float:right;\"><input id=\"btnCancel\" type=\"button\" value=\"".Dict::S('UI:Button:Cancel')."\" onClick=\"BackToDetails('".$sTargetClass."', ".$this->m_iObjectId.");\">");
		$oP->add("&nbsp;&nbsp;&nbsp;<input id=\"btnOk\" type=\"submit\" value=\"".Dict::S('UI:Button:Ok')."\"></span>\n");
		$oP->add("<span style=\"clear:both;\"><p>&nbsp;</p></span>\n");
		$oP->add("</div>\n");
		$oP->add("</form>\n");
		if (isset($aExtraParams['StartWithAdd']) && ($aExtraParams['StartWithAdd']))
		{
			$oP->add_ready_script("AddObjects();");
		}
	}
	
	protected function GetFormRow($oP, $oLinkedObj, $currentLink = null )
	{
		$aRow = array();
		if(is_object($currentLink))
		{
			$key = $currentLink->GetKey();
			$sNameSuffix = "[$key]"; // To make a tabular form
			$aRow['form::checkbox'] = "<input class=\"selection\" type=\"checkbox\" onChange=\"OnSelectChange();\" value=\"$key\">";
			$aRow['form::checkbox'] .= "<input type=\"hidden\" name=\"linkId[$key]\" value=\"$key\">";
			foreach($this->m_aEditableFields as $sFieldCode)
			{
				$oAttDef = MetaModel::GetAttributeDef($this->m_sClass, $sFieldCode);
				$aRow[$sFieldCode] = cmdbAbstractObject::GetFormElementForField($oP, $this->m_sClass, $sFieldCode, $oAttDef, $currentLink->Get($sFieldCode), '' /* DisplayValue */, $key, $sNameSuffix);
			}
		}
		else
		{
			// form for creating a new record
			$sNameSuffix = "[$currentLink]"; // To make a tabular form
			$aRow['form::checkbox'] = "<input class=\"selection\" type=\"checkbox\" onChange=\"OnSelectChange();\" value=\"$currentLink\">";
			$aRow['form::checkbox'] .= "<input type=\"hidden\" name=\"linkId[$currentLink]\" value=\"$currentLink\">";
			foreach($this->m_aEditableFields as $sFieldCode)
			{
				$oAttDef = MetaModel::GetAttributeDef($this->m_sClass, $sFieldCode);
				$aRow[$sFieldCode] = cmdbAbstractObject::GetFormElementForField($oP, $this->m_sClass, $sFieldCode, $oAttDef, '' /* TO DO/ call GetDefaultValue($oObject->ToArgs()) */, '' /* DisplayValue */, '' /* id */, $sNameSuffix);
			}
		}
		foreach(MetaModel::GetZListItems($this->m_sLinkedClass, 'list') as $sFieldCode)
		{
			$aRow['static::'.$sFieldCode] = $oLinkedObj->GetAsHTML($sFieldCode);
		}
		return $aRow;
	}
	
	protected function DisplayFormTable(WebPage $oP, $aConfig, $aData)
	{
		$oP->add("<table class=\"listResults\">\n");
		// Header
		$oP->add("<thead>\n");
		$oP->add("<tr>\n");
		foreach($aConfig as $sName=>$aDef)
		{
			$oP->add("<th title=\"".$aDef['description']."\">".$aDef['label']."</th>\n");
		}
		$oP->add("</tr>\n");
		$oP->add("</thead>\n");
		
		// Content
		$oP->add("</tbody>\n");
		if (count($aData) == 0)
		{
			$oP->add("<tr id=\"empty_row\"><td colspan=\"".count($aConfig)."\" style=\"text-align:center;\">".Dict::S('UI:Message:EmptyList:UseAdd')."</td></td>");
		}
		else
		{
			foreach($aData as $iRowId => $aRow)
			{
				$this->DisplayFormRow($oP, $aConfig, $aRow, $iRowId);
			}		
		}
		$oP->add("</tbody>\n");
		
		// Footer
		$oP->add("</table>\n");
	}
	
	protected function DisplayFormRow(WebPage $oP, $aConfig, $aRow, $iRowId)
	{
		$oP->add("<tr id=\"row_$iRowId\">\n");
		foreach($aConfig as $sName=>$void)
		{
			$oP->add("<td>".$aRow[$sName]."</td>\n");
		}
		$oP->add("</tr>\n");
	}
	
	public function DisplayAddForm(WebPage $oP)
	{
		$oAttDef = MetaModel::GetAttributeDef($this->m_sClass, $this->m_sLinkageAttr);
		$sTargetClass = $oAttDef->GetTargetClass();
		$oTargetObj = MetaModel::GetObject($sTargetClass, $this->m_iObjectId);
		$oP->add("<div class=\"wizContainer\">\n");
		//$oP->add("<div class=\"page_header\">\n");
		//$oP->add("<h1>".Dict::Format('UI:AddObjectsOf_Class_LinkedWith_Class_Instance', MetaModel::GetName($this->m_sLinkedClass), MetaModel::GetName(get_class($oTargetObj)), "<span class=\"hilite\">".$oTargetObj->GetHyperlink()."</span>")."</h1>\n");
		//$oP->add("</div>\n");

		$oFilter = new DBObjectSearch($this->m_sLinkedClass);
		$oSet = new CMDBObjectSet($oFilter);
		$oBlock = new DisplayBlock($oFilter, 'search', false);
		$oBlock->Display($oP, 'SearchFormToAdd', array('open' => true));
		$oP->Add("<form id=\"ObjectsAddForm\" OnSubmit=\"return DoAddObjects(this.id);\">\n");
		$oP->Add("<div id=\"SearchResultsToAdd\">\n");
		$oP->Add("<div style=\"height: 100px; background: #fff;border-color:#F6F6F1 #E6E6E1 #E6E6E1 #F6F6F1; border-style:solid; border-width:3px; text-align: center; vertical-align: center;\"><p>".Dict::S('UI:Message:EmptyList:UseSearchForm')."</p></div>\n");
		$oP->Add("</div>\n");
		$oP->add("<input type=\"button\" value=\"".Dict::S('UI:Button:Cancel')."\" onClick=\"$('#ModalDlg').dialog('close');\">&nbsp;&nbsp;<input type=\"submit\" value=\"".Dict::S('UI:Button:Add')."\">");
		$oP->Add("</div>\n");
		$oP->Add("</form>\n");
		$oP->add_ready_script("$('#ModalDlg').dialog('option', {title:'".Dict::Format('UI:AddObjectsOf_Class_LinkedWith_Class_Instance', MetaModel::GetName($this->m_sLinkedClass), MetaModel::GetName(get_class($oTargetObj)), "<span class=\"hilite\">".$oTargetObj->GetHyperlink()."</span>")."'});");
		$oP->add_ready_script("$('div#SearchFormToAdd form').bind('submit.uilinksWizard', SubmitHook);");
	}

	public function SearchObjectsToAdd(WebPage $oP)
	{
		//$oAttDef = MetaModel::GetAttributeDef($this->m_sClass, $this->m_sLinkageAttr);

		$oFilter = new DBObjectSearch($this->m_sLinkedClass);
		$oSet = new CMDBObjectSet($oFilter);
		$oBlock = new DisplayBlock($oFilter, 'list', false);
		$oBlock->Display($oP, 'ResultsToAdd', array('menu' => false, 'selection_mode' => true)); // Don't display the 'Actions' menu on the results
	}
	
	public function DoAddObjects(WebPage $oP, $aLinkedObjectIds = array())
	{
		//$oAttDef = MetaModel::GetAttributeDef($this->m_sClass, $this->m_sLinkageAttr);
		//$sTargetClass = $oAttDef->GetTargetClass();
		//$oP->Add("<!-- nb of objects to add: ".count($aLinkedObjectIds)." -->\n"); // Just to make sure it's not empty
		$aTable = array();
		foreach($aLinkedObjectIds as $iObjectId)
		{
			$oLinkedObj = MetaModel::GetObject($this->m_sLinkedClass, $iObjectId);
			if (is_object($oLinkedObj))
			{
				$aRow = $this->GetFormRow($oP, $oLinkedObj, -$iObjectId ); // Not yet created link get negative Ids
				$this->DisplayFormRow($oP, $this->m_aTableConfig, $aRow, -$iObjectId); 
			}
			else
			{
				echo Dict::Format('UI:Error:Object_Class_Id_NotFound', $this->m_sLinkedClass, $iObjectId);
			}
		}
		//var_dump($aTable);
		//$oP->Add("<!-- end of added list -->\n"); // Just to make sure it's not empty
	}
}
?>

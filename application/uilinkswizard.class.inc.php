<?php
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
		$this->m_aTableConfig['form::checkbox'] = array( 'label' => "<input class=\"select_all\" type=\"checkbox\" value=\"1\" onChange=\"var value = this.checked; $('.selection').each( function() { this.checked = value; } );OnSelectChange();\">", 'description' => "Select / Deselect All");
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
			throw( new Exception("Incorrect link definition: the class of objects to manage: '$sLinkedClass' was not found as an external key in the class '$sClass'"));
		}
		foreach(MetaModel::GetZListItems($this->m_sLinkedClass, 'list') as $sFieldCode)
		{
			$oAttDef = MetaModel::GetAttributeDef($this->m_sLinkedClass, $sFieldCode);
			$this->m_aTableConfig['static::'.$sFieldCode] = array( 'label' => $oAttDef->GetLabel(), 'description' => $oAttDef->GetDescription());
		}
	}

	public function Display(web_page $oP, UserContext $oContext, $aExtraParams = array())
	{
		$oAttDef = MetaModel::GetAttributeDef($this->m_sClass, $this->m_sLinkageAttr);
		$sTargetClass = $oAttDef->GetTargetClass();
		$oTargetObj = $oContext->GetObject($sTargetClass, $this->m_iObjectId);

		$oP->set_title("iTop - ".MetaModel::GetName($this->m_sLinkedClass)." objects linked with ".MetaModel::GetName(get_class($oTargetObj)).": ".$oTargetObj->GetName());
		$oP->add("<div class=\"wizContainer\">\n");
		$oP->add("<form method=\"post\">\n");
		$oP->add("<div class=\"page_header\">\n");
		$oP->add("<input type=\"hidden\" id=\"linksToRemove\" name=\"linksToRemove\" value=\"\">\n");
		$oP->add("<input type=\"hidden\" name=\"operation\" value=\"do_modify_links\">\n");
		$oP->add("<input type=\"hidden\" name=\"class\" value=\"{$this->m_sClass}\">\n");
		$oP->add("<input type=\"hidden\" name=\"linkage\" value=\"{$this->m_sLinkageAttr}\">\n");
		$oP->add("<input type=\"hidden\" name=\"object_id\" value=\"{$this->m_iObjectId}\">\n");
		$oP->add("<input type=\"hidden\" name=\"linking_attcode\" value=\"{$this->m_sLinkingAttCode}\">\n");
		$oP->add("<h1>Manage ".MetaModel::GetName($this->m_sLinkedClass)."s linked with ".MetaModel::GetName(get_class($oTargetObj)).": <span class=\"hilite\">".$oTargetObj->GetHyperlink()."</span></h1>\n");
		$oP->add("</div>\n");
		$oP->add("<script type=\"text/javascript\">\n");
		$oP->add("function OnSelectChange()
		{
			var nbChecked = $('.selection:checked').length;
			if (nbChecked > 0)
			{
				$('#btnRemove').attr('disabled','');
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
			$.get( 'ajax.render.php', { 'operation': 'addObjects',
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
					$('#ModalDlg').jqmShow();
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
			$.get( 'ajax.render.php', theMap, 
				function(data)
				{
					$('#SearchResultsToAdd').html(data);
					nb_rows = $('#SearchResultsToAdd table.listResults tr').length;
					if(nb_rows > 10)
					{
						yOffset = $('#ModalDlg').height() - $('#SearchResultsToAdd table.listResults tbody').height();
						tbodyHeight = $(document).height() - 100 - yOffset;
						if ($('#ModalDlg').height() > ($(document).height() - 100))
						{
							$('#SearchResultsToAdd table.listResults tbody').attr('height', tbodyHeight);
							$('#SearchResultsToAdd .listResults tbody').css('overflow', 'auto');
							$('#SearchResultsToAdd .listResults').tablesorter( { headers: { 0:{sorter: false }}, widgets: ['zebra']} ); // sortable and zebra tables
						}
					}
					
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
			$.get( 'ajax.render.php', theMap, 
				function(data)
				{
					//console.log('Data: ' + data);
					if (data != '')
					{
						$('#empty_row').remove();
					}
					$('.listResults tbody').append(data);
					$('.listResults').trigger('update');
					$('.listResults').tablesorter( { headers: { 0:{sorter: false }}, widgets: ['zebra']} ); // sortable and zebra tables
				},
				'html'
			);
			$('#ModalDlg').jqmHide();
			return false;
		}
		
		function InitForm()
		{
			// make sure that the form is clean
			$('.selection').each( function() { this.checked = false; });
			$('#btnRemove').attr('disabled','disabled');
			$('#linksToRemove').val('');
		}
		");
		$oP->Add("</script>\n");
		$oP->add_ready_script("InitForm();");
		$oFilter = $oContext->NewFilter($this->m_sClass);
		$oFilter->AddCondition($this->m_sLinkageAttr, $this->m_iObjectId, '=');
		$oSet = new DBObjectSet($oFilter);
		$aForm = array();
		while($oCurrentLink = $oSet->Fetch())
		{
			$aRow = array();
			$key = $oCurrentLink->GetKey();
			$oLinkedObj = $oContext->GetObject($this->m_sLinkedClass, $oCurrentLink->Get($this->m_sLinkingAttCode));

			$aForm[$key] = $this->GetFormRow($oP, $oLinkedObj, $oCurrentLink);
		}
		//var_dump($aTableLabels);
		//var_dump($aForm);
		$this->DisplayFormTable($oP, $this->m_aTableConfig, $aForm);
		$oP->add("<span style=\"float:left;\">&nbsp;&nbsp;&nbsp;<img src=\"../images/tv-item-last.gif\">&nbsp;&nbsp;<input id=\"btnRemove\" type=\"button\" value=\" Remove ".MetaModel::GetName($this->m_sLinkedClass)."s \" onClick=\"RemoveSelected();\" >");
		$oP->add("&nbsp;&nbsp;&nbsp;<input id=\"btnAdd\" type=\"button\" value=\" Add ".MetaModel::GetName($this->m_sLinkedClass)."s... \" onClick=\"AddObjects();\"></span>\n");
		$oP->add("<span style=\"float:right;\"><input id=\"btnCancel\" type=\"button\" value=\" Cancel \" onClick=\"goBack();\">");
		$oP->add("&nbsp;&nbsp;&nbsp;<input id=\"btnOk\" type=\"submit\" value=\" Ok \"></span>\n");
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
				$aRow[$sFieldCode] = cmdbAbstractObject::GetFormElementForField($oP, $this->m_sClass, $sFieldCode, $oAttDef, '' /* TO DO/ call GetDefaultValue */, '' /* DisplayValue */, '' /* id */, $sNameSuffix);
			}
		}
		foreach(MetaModel::GetZListItems($this->m_sLinkedClass, 'list') as $sFieldCode)
		{
			$aRow['static::'.$sFieldCode] = $oLinkedObj->GetAsHTML($sFieldCode);
		}
		return $aRow;
	}
	
	protected function DisplayFormTable(web_page $oP, $aConfig, $aData)
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
			$oP->add("<tr id=\"empty_row\"><td colspan=\"".count($aConfig)."\" style=\"text-align:center;\">The list is empty, use 'Add...' to add elements.</td></td>");
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
	
	protected function DisplayFormRow(web_page $oP, $aConfig, $aRow, $iRowId)
	{
		$oP->add("<tr id=\"row_$iRowId\">\n");
		foreach($aConfig as $sName=>$void)
		{
			$oP->add("<td>".$aRow[$sName]."</td>\n");
		}
		$oP->add("</tr>\n");
	}
	
	public function DisplayAddForm(web_page $oP, UserContext $oContext)
	{
		$oAttDef = MetaModel::GetAttributeDef($this->m_sClass, $this->m_sLinkageAttr);
		$sTargetClass = $oAttDef->GetTargetClass();
		$oTargetObj = $oContext->GetObject($sTargetClass, $this->m_iObjectId);
		$oP->add("<div class=\"wizContainer\">\n");
		$oP->add("<div class=\"page_header\">\n");
		$oP->add("<h1>Add ".MetaModel::GetName($this->m_sLinkedClass)."s to ".MetaModel::GetName(get_class($oTargetObj)).": <span class=\"hilite\">".$oTargetObj->GetHyperlink()."</span></h1>\n");
		$oP->add("</div>\n");

		$oFilter = $oContext->NewFilter($this->m_sLinkedClass);
		$oSet = new CMDBObjectSet($oFilter);
		$oBlock = new DisplayBlock($oFilter, 'search', false);
		$oBlock->Display($oP, 'SearchFormToAdd', array('open' => true));
		$oP->Add("<form id=\"ObjectsAddForm\" OnSubmit=\"return DoAddObjects(this.id);\">\n");
		$oP->Add("<div id=\"SearchResultsToAdd\">\n");
		$oP->Add("<div style=\"height: 100px; background: #fff;border-color:#F6F6F1 #E6E6E1 #E6E6E1 #F6F6F1; border-style:solid; border-width:3px; text-align: center; vertical-align: center;\"><p>Use the search form above to search for objects to be added.</p></div>\n");
		$oP->Add("</div>\n");
		$oP->add("<input type=\"button\" value=\"Cancel\" onClick=\"$('#ModalDlg').jqmHide();\">&nbsp;&nbsp;<input type=\"submit\" value=\" Add \">");
		$oP->Add("</div>\n");
		$oP->Add("</form>\n");
		$oP->add_ready_script("$('div#SearchFormToAdd form').bind('submit', function() {var the_form = this; SearchObjectsToAdd(the_form.id); return false;});");
	}

	public function SearchObjectsToAdd(web_page $oP, UserContext $oContext)
	{
		//$oAttDef = MetaModel::GetAttributeDef($this->m_sClass, $this->m_sLinkageAttr);

		$oFilter = $oContext->NewFilter($this->m_sLinkedClass);
		$oSet = new CMDBObjectSet($oFilter);
		$oBlock = new DisplayBlock($oFilter, 'list', false);
		$oBlock->Display($oP, 'ResultsToAdd', array('menu' => false, 'selection_mode' => true, 'display_limit' => false)); // Don't display the 'Actions' menu on the results
	}
	
	public function DoAddObjects(web_page $oP, UserContext $oContext, $aLinkedObjectIds = array())
	{
		//$oAttDef = MetaModel::GetAttributeDef($this->m_sClass, $this->m_sLinkageAttr);
		//$sTargetClass = $oAttDef->GetTargetClass();
		//$oP->Add("<!-- nb of objects to add: ".count($aLinkedObjectIds)." -->\n"); // Just to make sure it's not empty
		$aTable = array();
		foreach($aLinkedObjectIds as $iObjectId)
		{
			$oLinkedObj = $oContext->GetObject($this->m_sLinkedClass, $iObjectId);
			if (is_object($oLinkedObj))
			{
				$aRow = $this->GetFormRow($oP, $oLinkedObj, -$iObjectId ); // Not yet created link get negative Ids
				$this->DisplayFormRow($oP, $this->m_aTableConfig, $aRow, -$iObjectId); 
			}
			else
			{
				echo "Object: $sTargetClass - Id: $iObjectId not found <br/>\n";
			}
		}
		//var_dump($aTable);
		//$oP->Add("<!-- end of added list -->\n"); // Just to make sure it's not empty
	}
}
?>

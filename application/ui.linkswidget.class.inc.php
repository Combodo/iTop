<?php
require_once('../application/webpage.class.inc.php');
require_once('../application/displayblock.class.inc.php');

class UILinksWidget 
{
	protected $m_sClass;
	protected $m_sAttCode;
	protected $m_sNameSuffix;
	protected $m_iInputId;
	
	public function __construct($sClass, $sAttCode, $iInputId, $sNameSuffix = '')
	{
		$this->m_sClass = $sClass;
		$this->m_sAttCode = $sAttCode;
		$this->m_sNameSuffix = $sNameSuffix;
		$this->m_iInputId = $iInputId;
	}
	
	public function Display(web_page $oPage, $oCurrentValuesSet = null)
	{
		$sHTMLValue = '';
		$sTargetClass = self::GetTargetClass($this->m_sClass, $this->m_sAttCode);
		// #@# todo - add context information, otherwise any value will be authorized for external keys
		$aAllowedValues = MetaModel::GetAllowedValues_att($this->m_sClass, $this->m_sAttCode, array(), '');
		$oAttDef = MetaModel::GetAttributeDef($this->m_sClass, $this->m_sAttCode);
		$sExtKeyToRemote = $oAttDef->GetExtKeyToRemote();
		$sExtKeyToMe = $oAttDef->GetExtKeyToMe();
		$sStateAttCode = MetaModel::GetStateAttributeCode($this->m_sClass);
		$sDefaultState = MetaModel::GetDefaultState($this->m_sClass);

		$aAttributes = array();
		$sLinkedClass = $oAttDef->GetLinkedClass();
		foreach(MetaModel::ListAttributeDefs($sLinkedClass) as $sAttCode=>$oAttDef)
		{
			if ($sStateAttCode == $sAttCode)
			{
				// State attribute is always hidden from the UI
			}
			else if (!$oAttDef->IsExternalField() && ($sAttCode != $sExtKeyToMe) && ($sAttCode != $sExtKeyToRemote))
			{
				$iFlags = MetaModel::GetAttributeFlags($this->m_sClass, $sDefaultState, $sAttCode);				
				if ( !($iFlags & OPT_ATT_HIDDEN) && !($iFlags & OPT_ATT_READONLY) )
				{
					$aAttributes[] = $sAttCode;
				}
			}
		}
		$sAttributes = "['".implode("','", $aAttributes)."']";
		if ($oCurrentValuesSet != null)
		{
			// Serialize the link set into a JSon object
			$aCurrentValues = array();
			while($oLinkObj = $oCurrentValuesSet->Fetch())
			{
				$sRow = '{';
				foreach($aAttributes as $sLinkAttCode)
				{
					$sRow.= "\"$sLinkAttCode\": \"".addslashes($oLinkObj->Get($sLinkAttCode))."\", ";
				}
				$sRow .= "\"$sExtKeyToRemote\": ".$oLinkObj->Get($sExtKeyToRemote).'}';
				$aCurrentValues[] = $sRow;
			}
			$sJSON = '['.implode(',', $aCurrentValues).']';
		}
		else
		{
//echo "JSON VA IECH<br/>\n";
		}
//echo "JASON: $sJSON<br/>\n";;

		// Many values (or even a unknown list) display an autocomplete
		if ( (count($aAllowedValues) == 0) || (count($aAllowedValues) > 50) )
		{
			// too many choices, use an autocomplete
			// The input for the auto complete
			$sTitle = $oAttDef->GetDescription();
			$sHTMLValue .= "<script type=\"text/javascript\">\n";
			$sHTMLValue .= "oLinkWidget{$this->m_iInputId} = new LinksWidget('{$this->m_iInputId}', '$sLinkedClass', '$sExtKeyToMe', '$sExtKeyToRemote', $sAttributes);\n";
			$sHTMLValue .= "</script>\n";
			$oPage->add_at_the_end($this->GetObjectPickerDialog($oPage, $sTargetClass, 'oLinkWidget'.$this->m_iInputId.'.OnOk')); // Forms should not be inside forms
			$oPage->add_at_the_end($this->GetLinkObjectDialog($oPage, $this->m_iInputId)); // Forms should not be inside forms
			$sHTMLValue .= "<input type=\"text\" id=\"ac_{$this->m_iInputId}\" size=\"35\" value=\"\" title=\"Type the first 3 characters\"/>";
			$sHTMLValue .= "<input type=\"button\" id=\"ac_add_{$this->m_iInputId}\" value=\" Add... \"  class=\"action\" onClick=\"oLinkWidget{$this->m_iInputId}.AddObject();\"/>";
			$sHTMLValue .= "&nbsp;<input type=\"button\" value=\"Browse...\"  class=\"action\" onClick=\"return ManageObjects('$sTitle', '$sTargetClass', '$this->m_iInputId', '$sExtKeyToRemote');\"/>";
			// another hidden input to store & pass the object's Id
			$sHTMLValue .= "<input type=\"hidden\" id=\"id_ac_{$this->m_iInputId}\" onChange=\"EnableAddButton('{$this->m_iInputId}');\"/>\n";
			$sHTMLValue .= "<input type=\"hidden\" id=\"{$this->m_iInputId}\" name=\"attr_{$this->m_sAttCode}{$this->m_sNameSuffix}\" value=\"\"/>\n";
			$oPage->add_ready_script("\$('#{$this->m_iInputId}').val('$sJSON');\noLinkWidget{$this->m_iInputId}.Init();\n\$('#ac_{$this->m_iInputId}').autocomplete('./ajax.render.php', { minChars:3, onItemSelect:selectItem, onFindValue:findValue, formatItem:formatItem, autoFill:true, keyHolder:'#id_ac_{$this->m_iInputId}', extraParams:{operation:'ui.linkswidget', sclass:'{$this->m_sClass}', attCode:'{$this->m_sAttCode}', max:30}});");
			$oPage->add_ready_script("\$('#ac_add_{$this->m_iInputId}').attr('disabled', 'disabled');");
			$oPage->add_ready_script("\$('#ac_{$this->m_iInputId}').result( function(event, data, formatted) { if (data) { $('#id_ac_{$this->m_iInputId}').val(data[1]); $('#ac_add_{$this->m_iInputId}').attr('disabled', ''); } else { $('#ac_add_{$this->m_iInputId}').attr('disabled', 'disabled'); } } );");
		}
		else
		{
			// Few choices, use a normal 'select'
			$sHTMLValue = "<select name=\"attr_{$this->m_sAttCode}\"  id=\"{$this->m_iInputId}\">\n";
			$sHTMLValue .= "<option value=\"0\">--- select a value ---</option>\n";
			if (count($aAllowedValues) > 0)
			{
				foreach($aAllowedValues as $key => $value)
				{
					$sHTMLValue .= "<option value=\"$key\"$sSelected>$value</option>\n";
				}
			}
			$sHTMLValue .= "</select>\n";
		}
		$sHTMLValue .= "<div id=\"{$this->m_iInputId}_values\">\n";
		if ($oCurrentValuesSet != null)
		{
		 	// transform the DBObjectSet into a CMDBObjectSet !!!
			$aLinkedObjects = $oCurrentValuesSet->ToArray(false);
			// Actual values will be displayed asynchronously, no need to display them here
			//if (count($aLinkedObjects) > 0)
			//{
			//	$oSet = CMDBObjectSet::FromArray($sLinkedClass, $aLinkedObjects);
			//	$oDisplayBlock = DisplayBlock::FromObjectSet($oSet, 'list');
			//	$sHTMLValue .= $oDisplayBlock->GetDisplay($oPage, $this->m_iInputId.'_current', array('linkage' => $sExtKeyToMe, 'menu' => false));
			//}
		}
		$sHTMLValue .= "</div>\n";
		return $sHTMLValue;
	}
	/**
	 * This static function is called by the Ajax Page when there is a need to fill an autocomplete combo
	 * @param $oPage web_page The ajax page used for the put^put (sent back to the browser
	 * @param $oContext UserContext The context of the user (for limiting the search)
	 * @param $sClass string The name of the class of the current object being edited
	 * @param $sAttCode string The name of the attribute being edited
	 * @param $sName string The partial name that was typed by the user
	 * @param $iMaxCount integer The maximum number of items to return
	 * @return void
	 */	 	 	  	 	 	 	
	static public function Autocomplete(web_page $oPage, UserContext $oContext, $sClass, $sAttCode, $sName, $iMaxCount)
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
	 * @param $oPage web_page The ajax page used for the put^put (sent back to the browser
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
				if ($oAttDef->IsExternalKey())
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
	
	protected function GetObjectPickerDialog($oPage, $sTargetClass, $sOkFunction)
	{
		$sHTML = <<< EOF
		<div class="jqmWindow" id="ManageObjectsDlg_{$this->m_iInputId}">
		<div class="wizContainer">
		<div class="page_header"><h1 id="Manage_DlgTitle_{$this->m_iInputId}">Selected Objects</h1></div>
		<table width="100%">
			<tr>
				<td>
					<p>Selected objects:</p>
					<button type="button" class="action" onClick="FilterLeft('$sTargetClass');"><span> Filter... </span></button>
					<p><select id="selected_objects_{$this->m_iInputId}" size="10" multiple onChange="Manage_UpdateButtons('$this->m_iInputId')" style="width:300px;">
					</select></p>
				</td>
				<td style="text-align:center; valign:middle;">
					<p><button type="button" id="btn_add_objects_{$this->m_iInputId}" onClick="Manage_AddObjects('$this->m_iInputId');"> &lt;&lt; Add </button></p>
					<p><button type="button" id="btn_remove_objects_{$this->m_iInputId}" onClick="Manage_RemoveObjects('$this->m_iInputId');"> Remove &gt;&gt; </button></p>
				</td>
				<td>
					<p>Available objects:</p>
					<button type="button" class="action" onClick="FilterRight('$sTargetClass');"><span> Filter... </span></button>
					<p><select id="available_objects_{$this->m_iInputId}" size="10" multiple onChange="Manage_UpdateButtons('$this->m_iInputId')" style="width:300px;">
					</select></p>
				</td>
			</tr>
			<tr>
				<td colspan="3">
				<input type="submit" class="jqmClose" onClick="$('#ManageObjectsDlg_{$this->m_iInputId}').jqmHide(); $sOkFunction('$sTargetClass', 'selected_objects')" value=" Ok " />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" class="jqmClose"> Cancel</button>
				</td>
			</tr>
		</table>
		</div>
		</div>
EOF;
		$oPage->add_ready_script("$('#ManageObjectsDlg_$this->m_iInputId').jqm({overlay:70, modal:true, toTop:true});"); // jqModal Window
		//$oPage->add_ready_script("UpdateObjectList('$sClass');");
		return $sHTML;
	}
	
	protected function GetLinkObjectDialog($oPage, $sId)
	{
		$oAttDef = MetaModel::GetAttributeDef($this->m_sClass, $this->m_sAttCode);
		$sLinkedClass = $oAttDef->GetLinkedClass();
		$sStateAttCode = MetaModel::GetStateAttributeCode($sLinkedClass);
		$sDefaultState = MetaModel::GetDefaultState($sLinkedClass);
		$oAttDef = MetaModel::GetAttributeDef($this->m_sClass, $this->m_sAttCode);
		$sExtKeyToMe = $oAttDef->GetExtKeyToMe();
		$sExtKeyToRemote = $oAttDef->GetExtKeyToRemote();
		
		$sHTML = "<div class=\"jqmWindow\" id=\"LinkDlg_$sId\">\n";
		$sHTML .= "<div class=\"wizContainer\">\n";
		$sHTML .= "<div class=\"page_header\"><h1>".MetaModel::GetName($sLinkedClass)." attributes</h1></div>\n";
		$sHTML .= "<form action=\"./UI.php\" onSubmit=\"return oLinkWidget$sId.OnLinkOk();\">\n";
		$index = 0;
		$aAttrsMap = array();
		$aDetails = array();
		foreach(MetaModel::ListAttributeDefs($sLinkedClass) as $sAttCode=>$oAttDef)
		{
			if ($sStateAttCode == $sAttCode)
			{
				// State attribute is always hidden from the UI
				//$sHTMLValue = $this->GetState();
				//$aDetails[] = array('label' => $oAttDef->GetLabel(), 'value' => $sHTMLValue);
			}
			else if (!$oAttDef->IsExternalField() && ($sAttCode != $sExtKeyToMe) && ($sAttCode != $sExtKeyToRemote))
			{
				$iFlags = MetaModel::GetAttributeFlags($sLinkedClass, $sDefaultState, $sAttCode);				
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
						$sValue = ""; //$this->Get($sAttCode);
						$sDisplayValue = ""; //$this->GetDisplayValue($sAttCode);
						$sSubId = $sId.'_'.$index;
						$aAttrsMap[$sAttCode] = $sSubId;
						$index++;
						$sHTMLValue = cmdbAbstractObject::GetFormElementForField($oPage, $sLinkedClass, $sAttCode, $oAttDef, $sValue, $sDisplayValue, $sSubId, $this->m_sAttCode);
					}
					$aDetails[] = array('label' => $oAttDef->GetLabel(), 'value' => $sHTMLValue);
				}
			}
		}
		$sHTML .= $oPage->GetDetails($aDetails);
		$sHTML .= "<input type=\"submit\" class=\"jqmClose\" onClick=\"oLinkWidget$sId.OnLinkOk()\" value=\" Ok \" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type=\"button\" class=\"jqmClose\"  onClick=\"oLinkWidget$sId.OnLinkCancel()\"> Cancel</button>\n";
		$sHTML .= "</form>\n";
		$sHTML .= "</div>\n";
		$sHTML .= "</div>\n";
		$oPage->add_ready_script("$('#LinkDlg_$sId').jqm({overlay:70, modal:true, toTop:true});"); // jqModal Window
		//$oPage->add_ready_script("UpdateObjectList('$sClass');");
		return $sHTML;
	}
}
?>

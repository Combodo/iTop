<?php
// Copyright (C) 2012 Combodo SARL
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
 * Helper class to build interactive forms to be used either in stand-alone
 * modal dialog or in "property-sheet" panes.
 *
 */
class DesignerForm
{
	protected $aFieldSets;
	protected $sCurrentFieldSet;
	protected $sScript;
	protected $sReadyScript;
	protected $sFormId;
	protected $sFormPrefix;
	protected $sParamsContainer;
	protected $oParentForm;
	protected $aSubmitParams;
	protected $sSubmitTo;
	protected $bReadOnly;
	
	public function __construct()
	{
		$this->aFieldSets = array();
		$this->sCurrentFieldSet = '';
		$this->sScript = '';
		$this->sReadyScript = '';
		$this->sFormPrefix = '';
		$this->sParamsContainer = '';
		$this->sFormId = 'form_'.rand();
		$this->oParentForm = null;
		$this->bReadOnly = false;
		$this->StartFieldSet($this->sCurrentFieldSet);
	}
	
	public function AddField(DesignerFormField $oField)
	{
		if (!is_array($this->aFieldSets[$this->sCurrentFieldSet]))
		{
			$this->aFieldSets[$this->sCurrentFieldSet] = array();
		}
		$this->aFieldSets[$this->sCurrentFieldSet][] = $oField;
		$oField->SetForm($this);
	}
	
	public function StartFieldSet($sLabel)
	{
		$this->sCurrentFieldSet = $sLabel;
		if (!array_key_exists($this->sCurrentFieldSet, $this->aFieldSets))
		{
			$this->aFieldSets[$this->sCurrentFieldSet] = array();
		}
	}
	
	public function Render($oP, $bReturnHTML = false)
	{
		$sReturn = '';
		if ($this->oParentForm == null)
		{
			$sFormId = $this->sFormId;
			$sReturn = '<form id="'.$sFormId.'">';
		}
		else
		{
			$sFormId = $this->oParentForm->sFormId;
		}
		$sHiddenFields = '';
		foreach($this->aFieldSets as $sLabel => $aFields)
		{
			$aDetails = array();
			if ($sLabel != '')
			{
				$sReturn .= '<fieldset>';
				$sReturn .= '<legend>'.$sLabel.'</legend>';
			}
			foreach($aFields as $oField)
			{
				$aRow = $oField->Render($oP, $sFormId);
				if ($oField->IsVisible())
				{
					$sValidation = '&nbsp;<span class="prop_apply">'.$this->GetValidationArea($oField->GetCode()).'</span>';
					$sField = $aRow['value'].$sValidation;
					$aDetails[] = array('label' => $aRow['label'], 'value' => $sField);
				}
				else
				{
					$sHiddenFields .= $aRow['value'];
				}
			}
			$sReturn .= $oP->GetDetails($aDetails);
			if ($sLabel != '')
			{
				$sReturn .= '</fieldset>';
			}
		}
		$sReturn .= $sHiddenFields;
		
		if ($this->oParentForm == null)
		{
			$sReturn .= '</form>';
		}
		if($this->sScript != '')
		{
			$oP->add_script($this->sScript);
		}
		if($this->sReadyScript != '')
		{
			$oP->add_ready_script($this->sReadyScript);
		}
		if ($bReturnHTML)
		{
			return $sReturn;
		}
		else
		{
			$oP->add($sReturn);
		}
	}

	public function SetSubmitParams($sSubmitToUrl, $aSubmitParams)
	{
		$this->sSubmitTo = $sSubmitToUrl;
		$this->aSubmitParams = $aSubmitParams;
	}
	
	public function CopySubmitParams($oParentForm)
	{
		$this->sSubmitTo = $oParentForm->sSubmitTo;
		$this->aSubmitParams = $oParentForm->aSubmitParams;
	}
	
	
	public function RenderAsPropertySheet($oP, $bReturnHTML = false, $sNotifyParentSelector = null)
	{
		$sReturn = '';		
		$sActionUrl = addslashes($this->sSubmitTo);
		$sJSSubmitParams = json_encode($this->aSubmitParams);
		if ($this->oParentForm == null)
		{
			$sFormId = $this->sFormId;
			$sReturn = '<form id="'.$sFormId.'" onsubmit="return false;">';
			$sReturn .= '<table class="prop_table">';
			$sReturn .= '<thead><tr><th class="prop_header">'.Dict::S('UI:Form:Property').'</th><th class="prop_header">'.Dict::S('UI:Form:Value').'</th><th colspan="2" class="prop_header">&nbsp;</th></tr></thead><tbody>';
		}
		else
		{
			$sFormId = $this->oParentForm->sFormId;
		}
		$sHiddenFields = '';
		foreach($this->aFieldSets as $sLabel => $aFields)
		{
			$aDetails = array();
			if ($sLabel != '')
			{
				$sReturn .= '<tr><th colspan="4">'.$sLabel.'</th></tr>';
			}


			foreach($aFields as $oField)
			{
				$aRow = $oField->Render($oP, $sFormId, 'property');
				if ($oField->IsVisible())
				{
					$sFieldId = $this->GetFieldId($oField->GetCode());
					$sValidation = $this->GetValidationArea($oField->GetCode(), '<span title="Apply" class="ui-icon ui-icon-circle-check"/>');
					$sValidationFields = '</td><td class="prop_icon prop_apply">'.$sValidation.'</td><td  class="prop_icon prop_cancel"><span title="Revert" class="ui-icon ui-icon-circle-close"/></td></tr>';
					$sReturn .= '<tr id="row_'.$sFieldId.'"><td class="prop_label">'.$aRow['label'].'</td><td class="prop_value">'.$aRow['value'];
					if (!($oField instanceof DesignerFormSelectorField))
					{
						$sReturn .= $sValidationFields;
					}
					$sNotifyParentSelectorJS = is_null($sNotifyParentSelector) ? 'null' : "'".addslashes($sNotifyParentSelector)."'";
					$sAutoApply = $oField->IsAutoApply() ? 'true' : 'false';
					$this->AddReadyScript(
<<<EOF
$('#row_$sFieldId').property_field({parent_selector: $sNotifyParentSelectorJS, field_id: '$sFieldId', auto_apply: $sAutoApply, value: '', submit_to: '$sActionUrl', submit_parameters: $sJSSubmitParams });
EOF
					);
				}
				else
				{
					$sHiddenFields .= $aRow['value'];
				}
			}
		}
		
		if ($this->oParentForm == null)
		{
			$sFormId = $this->sFormId;
			$sReturn .= '</tbody>';
			$sReturn .= '</table>';
			$sReturn .= $sHiddenFields;
			$sReturn .= '</form>';
			$sReturn .= '<div id="prop_submit_result"/>'; // for the return of the submit operation
		}
		else
		{
			$sReturn .= $sHiddenFields;
		}
		$this->AddReadyScript(
<<<EOF
		$('.prop_table').tableHover();
		var idx = 0;
		$('.prop_table tbody tr').each(function() {
			if ((idx % 2) == 0)
			{
				$(this).addClass('even');
			}
			else
			{
				$(this).addClass('odd');
			}
			idx++;
		});
EOF
		);
		
		if($this->sScript != '')
		{
			$oP->add_script($this->sScript);
		}
		if($this->sReadyScript != '')
		{
			$oP->add_ready_script($this->sReadyScript);
		}
		if ($bReturnHTML)
		{
			return $sReturn;
		}
		else
		{
			$oP->add($sReturn);
		}
	}	
	public function RenderAsDialog($oPage, $sDialogId, $sDialogTitle, $iDialogWidth, $sOkButtonLabel)
	{
		$sDialogTitle = addslashes($sDialogTitle);
		$sOkButtonLabel = addslashes($sOkButtonLabel);
		$sCancelButtonLabel = 'Cancel'; //TODO: localize
		$oPage->add("<div id=\"$sDialogId\">");
		$this->Render($oPage);
		$oPage->add('</div>');
		
		$oPage->add_ready_script(
<<<EOF
$('#$sDialogId').dialog({
		height: 'auto',
		width: 500,
		modal: true,
		title: '$sDialogTitle',
		buttons: [
		{ text: "$sOkButtonLabel", click: function() {
			var oForm = $(this).parents('.ui-dialog :first').find('form');
			oForm.submit();
		} },
		{ text: "$sCancelButtonLabel", click: function() { KillAllMenus(); $(this).dialog( "close" ); $(this).remove(); } },
		],
		close: function() { KillAllMenus(); $(this).remove(); }
	});
	var oForm = $('#$sDialogId form');
	var sFormId = oForm.attr('id');
	ValidateForm(sFormId, true);
EOF
		);		
	}
	
	public function ReadParams(&$aValues = array())
	{
		foreach($this->aFieldSets as $sLabel => $aFields)
		{
			foreach($aFields as $oField)
			{
				$oField->ReadParam($aValues);
			}
		}
		return $aValues;
	}
	
	public function SetPrefix($sPrefix)
	{
		$this->sFormPrefix = $sPrefix;
	}
	
	public function GetPrefix()
	{
		return $this->sFormPrefix;
	}
	
	public function SetReadOnly($bReadOnly = true)
	{
		$this->bReadOnly = $bReadOnly;
	}
	
	public function IsReadOnly()
	{
		if ($this->oParentForm == null)
		{
			return $this->bReadOnly;
		}
		else
		{
			return $this->oParentForm->IsReadOnly();
		}
	}
	
	public function SetParamsContainer($sParamsContainer)
	{
		$this->sParamsContainer = $sParamsContainer;
	}
	
	public function GetParamsContainer()
	{
		if ($this->oParentForm == null)
		{
			return $this->sParamsContainer;
		}
		else
		{
			return $this->oParentForm->GetParamsContainer();
		}
	}
	
	public function SetParentForm($oParentForm)
	{
		$this->oParentForm = $oParentForm;
	}
	
	public function AddScript($sScript)
	{
		$this->sScript .= $sScript;
	}
	
	public function AddReadyScript($sScript)
	{
		$this->sReadyScript .= $sScript;
	}
	
	public function GetFieldId($sCode)
	{
		return $this->sFormPrefix.'attr_'.$sCode;
	}
	
	public function GetFieldName($sCode)
	{
		return 'attr_'.$sCode;
	}
	
	public function GetParamName($sCode)
	{
		return 'attr_'.$sCode;
	}
	
	public function GetValidationArea($sCode, $sContent = '')
	{
		return "<span style=\"display:inline-block;width:20px;\" id=\"v_{$this->sFormPrefix}attr_$sCode\"><span class=\"ui-icon ui-icon-alert\"></span>$sContent</span>";
	}
	public function GetAsyncActionClass()
	{
		return $this->sAsyncActionClass;
	}
}

class DesignerTabularForm extends DesignerForm
{
	protected $aTable;
	
	public function __construct()
	{
		parent::__construct();
		$this->aTable = array();
	}
	public function AddRow($aRow)
	{
		$this->aTable[] = $aRow;
	}
	
	public function Render($oP, $bReturnHTML = false)
	{
		$sReturn = '';
		if ($this->oParentForm == null)
		{
			$sFormId = $this->sFormId;
			$sReturn = '<form id="'.$sFormId.'">';
		}
		else
		{
			$sFormId = $this->oParentForm->sFormId;
		}
		$sHiddenFields = '';
		$sReturn .= '<table style="width:100%">';
		foreach($this->aTable as $aRow)
		{
			$sReturn .= '<tr>';
			foreach($aRow as $field)
			{
				if (!is_object($field))
				{
					// Shortcut: pass a string for a cell containing just a label
					$sReturn .= '<td>'.$field.'</td>';
				}
				else
				{
					$field->SetForm($this);
					$aFieldData = $field->Render($oP, $sFormId);
					if ($field->IsVisible())
					{
						// put the label and value separated by a non-breaking space if needed
						$aData = array();
						foreach(array('label', 'value') as $sCode )
						{
							if ($aFieldData[$sCode] != '')
							{
								$aData[] = $aFieldData[$sCode];
							}						
						}
						$sReturn .= '<td>'.implode('&nbsp;', $aData).'</td>';
					}
					else
					{
						$sHiddenFields .= $aRow['value'];
					}
				}
			}
			$sReturn .= '</tr>';
		}
		$sReturn .= '</table>';
		
		$sReturn .= $sHiddenFields;
		
		if($this->sScript != '')
		{
			$oP->add_script($this->sScript);
		}
		if($this->sReadyScript != '')
		{
			$oP->add_ready_script($this->sReadyScript);
		}
		if ($bReturnHTML)
		{
			return $sReturn;
		}
		else
		{
			$oP->add($sReturn);
		}
	}
	
	public function ReadParams(&$aValues = array())
	{
		foreach($this->aTable as $aRow)
		{
			foreach($aRow as $field)
			{
				if (is_object($field))
				{
					$field->SetForm($this);
					$field->ReadParam($aValues);
				}
			}
		}
		return $aValues;
	}
}

class DesignerFormField
{
	protected $sLabel;
	protected $sCode;
	protected $defaultValue;
	protected $oForm;
	protected $bMandatory;
	protected $bReadOnly;
	protected $bAutoApply;
	
	public function __construct($sCode, $sLabel, $defaultValue)
	{
		$this->sLabel = $sLabel;
		$this->sCode = $sCode;
		$this->defaultValue = $defaultValue;
		$this->bMandatory = false;
		$this->bReadOnly = false;
		$this->bAutoApply = false;
	}
	
	public function GetCode()
	{
		return $this->sCode;
	}
	
	public function SetForm($oForm)
	{
		$this->oForm = $oForm;
	}
	

	public function SetMandatory($bMandatory = true)
	{
		$this->bMandatory = $bMandatory;
	}

	public function SetReadOnly($bReadOnly = true)
	{
		$this->bReadOnly = $bReadOnly;
	}
	
	public function IsReadOnly()
	{
		return ($this->oForm->IsReadOnly() || $this->bReadOnly);
	}

	public function SetAutoApply($bAutoApply)
	{
		$this->bAutoApply = $bAutoApply;
	}

	public function IsAutoApply()
	{
		return $this->bAutoApply;
	}

	public function Render(WebPage $oP, $sFormId, $sRenderMode='dialog')
	{
		$sId = $this->oForm->GetFieldId($this->sCode);
		$sName = $this->oForm->GetFieldName($this->sCode);
		return array('label' => $this->sLabel, 'value' => "<input type=\"text\" id=\"$sId\" name=\"$sName\" value=\"".htmlentities($this->defaultValue, ENT_QUOTES, 'UTF-8')."\">");
	}
	
	public function ReadParam(&$aValues)
	{
		if ($this->IsReadOnly())
		{
			$aValues[$this->sCode] = $this->defaultValue;
		}
		else
		{
			if ($this->oForm->GetParamsContainer() != '')
			{
				$aParams = utils::ReadParam($this->oForm->GetParamsContainer(), array(), false, 'raw_data');
				if (array_key_exists($this->oForm->GetParamName($this->sCode), $aParams))
				{
					$aValues[$this->sCode] = $aParams[$this->oForm->GetParamName($this->sCode)];
				}
				else
				{
					$aValues[$this->sCode] = $this->defaultValue;
				}
			}
			else
			{
				$aValues[$this->sCode] = utils::ReadParam($this->oForm->GetParamName($this->sCode), $this->defaultValue, false, 'raw_data');
			}
		}
	}
	
	public function IsVisible()
	{
		return true;
	}
}

class DesignerLabelField extends DesignerFormField
{
	protected $sDescription;
	
	public function __construct($sLabel, $sDescription)
	{
		parent::__construct('', $sLabel, '');
		$this->sDescription = $sDescription;
	}
	
	public function Render(WebPage $oP, $sFormId, $sRenderMode='dialog')
	{
		$sId = $this->oForm->GetFieldId($this->sCode);
		$sName = $this->oForm->GetFieldName($this->sCode);
		return array('label' => $this->sLabel, 'value' => $sDescription);
	}
	
	public function ReadParam(&$aValues)
	{
	}
	
	public function IsVisible()
	{
		return true;
	}
}

class DesignerTextField extends DesignerFormField
{
	protected $sValidationPattern;
	public function __construct($sCode, $sLabel = '', $defaultValue = '')
	{
		parent::__construct($sCode, $sLabel, $defaultValue);
		$this->sValidationPattern = '';
	}
	
	public function SetValidationPattern($sValidationPattern)
	{
		$this->sValidationPattern = $sValidationPattern;
	}
	
	public function Render(WebPage $oP, $sFormId, $sRenderMode='dialog')
	{
		$sId = $this->oForm->GetFieldId($this->sCode);
		$sName = $this->oForm->GetFieldName($this->sCode);
		$sPattern = addslashes($this->sValidationPattern);
		$sMandatory = $this->bMandatory ? 'true' :  'false';
		$sReadOnly = $this->IsReadOnly() ? 'readonly' :  '';
		$oP->add_ready_script(
<<<EOF
$('#$sId').bind('change keyup validate', function() { ValidateWithPattern('$sId', $sMandatory, '$sPattern', '$sFormId'); } );
{
	var myTimer = null;
	$('#$sId').bind('keyup', function() { clearTimeout(myTimer); myTimer = setTimeout(function() { $('#$sId').trigger('change', {} ); }, 100); });
}
EOF
);
		return array('label' => $this->sLabel, 'value' => "<input type=\"text\" id=\"$sId\" $sReadOnly name=\"$sName\" value=\"".htmlentities($this->defaultValue, ENT_QUOTES, 'UTF-8')."\">");
	}

	public function ReadParam(&$aValues)
	{
		parent::ReadParam($aValues);

		if (($this->sValidationPattern != '') &&(!preg_match('/'.$this->sValidationPattern.'/', $aValues[$this->sCode])) ) 
		{
			$aValues[$this->sCode] = $this->defaultValue;
		}
	}
}

class DesignerLongTextField extends DesignerTextField
{
	public function Render(WebPage $oP, $sFormId, $sRenderMode='dialog')
	{
		$sId = $this->oForm->GetFieldId($this->sCode);
		$sName = $this->oForm->GetFieldName($this->sCode);
		$sPattern = addslashes($this->sValidationPattern);
		$sMandatory = $this->bMandatory ? 'true' :  'false';
		$sReadOnly = $this->IsReadOnly() ? 'readonly' :  '';
		$oP->add_ready_script(
<<<EOF
$('#$sId').bind('change keyup validate', function() { ValidateWithPattern('$sId', $sMandatory, '$sPattern', '$sFormId'); } );
{
	var myTimer = null;
	$('#$sId').bind('keyup', function() { clearTimeout(myTimer); myTimer = setTimeout(function() { $('#$sId').trigger('change', {} ); }, 100); });
}
EOF
);
		return array('label' => $this->sLabel, 'value' => "<textarea id=\"$sId\" $sReadOnly name=\"$sName\">".htmlentities($this->defaultValue, ENT_QUOTES, 'UTF-8')."</textarea>");
	}
}

class DesignerComboField extends DesignerFormField
{
	protected $aAllowedValues;
	protected $bMultipleSelection;
	protected $bOtherChoices;
	
	public function __construct($sCode, $sLabel = '', $defaultValue = '')
	{
		parent::__construct($sCode, $sLabel, $defaultValue);
		$this->aAllowedValues = array();
		$this->bMultipleSelection = false;
		$this->bOtherChoices = false;

		$this->bAutoApply = true;
	}
	
	public function SetAllowedValues($aAllowedValues)
	{
		$this->aAllowedValues = $aAllowedValues;
	}
	
	public function MultipleSelection($bMultipleSelection = true)
	{
		$this->bMultipleSelection = $bMultipleSelection;
	}
	
	public function OtherChoices($bOtherChoices = true)
	{
		$this->bOtherChoices = $bOtherChoices;
	}
	
	
	public function Render(WebPage $oP, $sFormId, $sRenderMode='dialog')
	{

		$sId = $this->oForm->GetFieldId($this->sCode);
		$sName = $this->oForm->GetFieldName($this->sCode);
		$sChecked = $this->defaultValue ? 'checked' : '';
		$sMandatory = $this->bMandatory ? 'true' :  'false';
		$sReadOnly = $this->IsReadOnly() ? 'disabled="disabled"' :  '';
		if ($this->bMultipleSelection)
		{
			$sHtml = "<select multiple size=\"8\"id=\"$sId\" name=\"$sName\" $sReadOnly>";
		}
		else
		{
			$sHtml = "<select id=\"$sId\" name=\"$sName\" $sReadOnly>";
			$sHtml .= "<option value=\"\">".Dict::S('UI:SelectOne')."</option>";
		}
		foreach($this->aAllowedValues as $sKey => $sDisplayValue)
		{
			if ($this->bMultipleSelection)
			{
				$sSelected = in_array($sKey, $this->defaultValue) ? 'selected' : '';
			}
			else
			{
				$sSelected = ($sKey == $this->defaultValue) ? 'selected' : '';
			}
			// Quick and dirty: display the menu parents as a tree
			$sHtmlValue = str_replace(' ', '&nbsp;', htmlentities($sDisplayValue, ENT_QUOTES, 'UTF-8'));
			$sHtml .= "<option value=\"".htmlentities($sKey, ENT_QUOTES, 'UTF-8')."\" $sSelected>$sHtmlValue</option>";
		}
		$sHtml .= "</select>";
		if ($this->bOtherChoices)
		{
			$sHtml .= '<br/><input type="checkbox" id="other_chk_'.$sId.'"><label for="other_chk_'.$sId.'">&nbsp;Other:</label>&nbsp;<input type="text" id="other_'.$sId.'" name="other_'.$sName.'" size="30"/>'; 
		}
		$oP->add_ready_script(
<<<EOF
$('#$sId').bind('change validate', function() { ValidateWithPattern('$sId', $sMandatory, '', '$sFormId'); } );
EOF
);
		return array('label' => $this->sLabel, 'value' => $sHtml);
	}

	public function ReadParam(&$aValues)
	{
		parent::ReadParam($aValues);
		if ($aValues[$this->sCode] == 'null')
		{
			$aValues[$this->sCode] = array();
		}
	}
}

class DesignerBooleanField extends DesignerFormField
{
	public function __construct($sCode, $sLabel = '', $defaultValue = '')
	{
		parent::__construct($sCode, $sLabel, $defaultValue);
		$this->bAutoApply = true;
	}
	
	public function Render(WebPage $oP, $sFormId, $sRenderMode='dialog')
	{
		$sId = $this->oForm->GetFieldId($this->sCode);
		$sName = $this->oForm->GetFieldName($this->sCode);
		$sChecked = $this->defaultValue ? 'checked' : '';
		$sReadOnly = $this->IsReadOnly() ? 'disabled' :  ''; // readonly does not work as expected on checkboxes:
															 // readonly prevents the user from changing the input's value not its state (checked/unchecked)
		return array('label' => $this->sLabel, 'value' => "<input type=\"checkbox\" $sChecked $sReadOnly id=\"$sId\" name=\"$sName\" value=\"true\">");
	}
	
	public function ReadParam(&$aValues)
	{
		if ($this->IsReadOnly())
		{
			$aValues[$this->sCode] = $this->defaultValue;
		}
		else
		{
			$sParamsContainer = $this->oForm->GetParamsContainer();
			if ($sParamsContainer != '')
			{
				$aParams = utils::ReadParam($sParamsContainer, array(), false, 'raw_data');
				if (array_key_exists($this->oForm->GetParamName($this->sCode), $aParams))
				{
					$sValue = $aParams[$this->oForm->GetParamName($this->sCode)];
				}
				else
				{
					$sValue = 'false';
				}
			}
			else
			{
				$sValue = utils::ReadParam($this->oForm->GetParamName($this->sCode), 'false', false, 'raw_data');
			}
		}
		$aValues[$this->sCode] = ($sValue == 'true');
	}
}


class DesignerHiddenField extends DesignerFormField
{
	public function __construct($sCode, $sLabel = '', $defaultValue = '')
	{
		parent::__construct($sCode, $sLabel, $defaultValue);
	}
	
	public function IsVisible()
	{
		return false;
	}
	
	public function Render(WebPage $oP, $sFormId, $sRenderMode='dialog')
	{
		$sId = $this->oForm->GetFieldId($this->sCode);
		$sName = $this->oForm->GetFieldName($this->sCode);
		$sChecked = $this->defaultValue ? 'checked' : '';
		return array('label' =>'', 'value' => "<input type=\"hidden\" id=\"$sId\" name=\"$sName\" value=\"".htmlentities($this->defaultValue, ENT_QUOTES, 'UTF-8')."\">");
	}
}


class DesignerIconSelectionField extends DesignerFormField
{
	protected $aAllowedValues;
	
	public function __construct($sCode, $sLabel = '', $defaultValue = '')
	{
		parent::__construct($sCode, $sLabel, $defaultValue);
		$this->bAutoApply = true;
	}
	
	public function SetAllowedValues($aAllowedValues)
	{
		$this->aAllowedValues = $aAllowedValues;
	}
	
	public function Render(WebPage $oP, $sFormId, $sRenderMode='dialog')
	{
		$sId = $this->oForm->GetFieldId($this->sCode);
		$sName = $this->oForm->GetFieldName($this->sCode);
		$idx = 0;
		foreach($this->aAllowedValues as $index => $aValue)
		{
			if ($aValue['value'] == $this->defaultValue)
			{
				$idx = $index;
				break;
			}
		}
		$sJSItems = json_encode($this->aAllowedValues);
		if (!$this->IsReadOnly())
		{
			$oP->add_ready_script(
<<<EOF
	$('#$sId').icon_select({current_idx: $idx, items: $sJSItems});
EOF
			);
		}
		$sReadOnly = $this->IsReadOnly() ? 'disabled' : '';
		return array('label' =>$this->sLabel, 'value' => "<input type=\"hidden\" id=\"$sId\" name=\"$sName\" value=\"{$this->defaultValue}\"/>");
	}
}

class DesignerSortableField extends DesignerFormField
{
	protected $aAllowedValues;
	
	public function __construct($sCode, $sLabel = '', $defaultValue = '')
	{
		parent::__construct($sCode, $sLabel, $defaultValue);
		$this->aAllowedValues = array();
	}
	
	public function SetAllowedValues($aAllowedValues)
	{
		$this->aAllowedValues = $aAllowedValues;
	}
	
	public function Render(WebPage $oP, $sFormId, $sRenderMode='dialog')
	{
		$bOpen = false;
		$sId = $this->oForm->GetFieldId($this->sCode);
		$sName = $this->oForm->GetFieldName($this->sCode);
		$sHtml = "<span class=\"sort_$sId fieldslist\" id=\"sortable_$sId\">";
		foreach($this->defaultValue as $sValue)
		{
			$sHtml .= "<span class=\"movable_attr\">$sValue</span>";
		}
		$sHtml .="</span>";
		$sIconClass = $bOpen ? 'ui-icon-circle-triangle-s' : 'ui-icon-circle-triangle-e';
		$sStyle = $bOpen ? '' : 'style="display:none"';
		$sHtml .= "<div class=\"fieldspicker\"><table><tr><td><span id=\"collapse_$sId\" class=\"ui-icon $sIconClass\" style=\"opacity: 0.5\"></span>Fields</td></tr><tr><td><div $sStyle id=\"fieldsbasket_$sId\" class=\"sort_$sId fieldsbasket\">";
		foreach($this->aAllowedValues as $sKey => $sDisplayValue)
		{
			$sHtml .= "<span class=\"movable_attr\">$sDisplayValue</span>";
		}
		$sHtml .="</div></td></tr>";
		$sHtml .="<tr id=\"trash_icon_$sId\" $sStyle><td><span class=\"ui-icon ui-icon-trash\" style=\"opacity: 0.5\"></span>Trash</td></tr><tr id=\"trash_$sId\" $sStyle><td><div id=\"recycle_$sId\" class=\"sort_$sId fieldstrash\"></div></div></td></tr></table></div>";
		$oP->add_ready_script(
<<<EOF
	$('#collapse_$sId').click(function() { $(this).toggleClass('ui-icon-circle-triangle-s').toggleClass('ui-icon-circle-triangle-e'); $('#fieldsbasket_$sId').toggle(); $('#trash_icon_$sId').toggle(); $('#trash_$sId').toggle(); } );
	$('#fieldsbasket_$sId .movable_attr').draggable({connectToSortable: '#sortable_$sId', helper: 'clone', revert: false });
	$('#recycle_$sId').sortable({ receive: function(event, ui) { ui.item.animate({opacity: 0.25}, { complete: function() { $(this).remove(); } });} });
	$('#sortable_$sId').sortable({connectWith: '#recycle_$sId', forcePlaceholderSize: true});
	$('#sortable_$sId').disableSelection();
EOF
		);
		return array('label' => $this->sLabel, 'value' => $sHtml);
	}
}

class DesignerFormSelectorField extends DesignerFormField
{
	protected $aSubForms;
	protected $defaultRealValue; // What's stored as default value is actually the index
	public function __construct($sCode, $sLabel = '', $defaultValue = '')
	{
		parent::__construct($sCode, $sLabel, 0);
		$this->defaultRealValue = $defaultValue;
		$this->aSubForms = array();
	}
	
	public function AddSubForm($oSubForm, $sLabel, $sValue)
	{
		$idx = count($this->aSubForms);
		$this->aSubForms[] = array('form' => $oSubForm, 'label' => $sLabel, 'value' => $sValue);
		if ($sValue == $this->defaultRealValue)
		{
			// Store the index of the selected/default form
			$this->defaultValue = count($this->aSubForms) - 1;
		}
	}
	
	public function Render(WebPage $oP, $sFormId, $sRenderMode='dialog')
	{
		$sId = $this->oForm->GetFieldId($this->sCode);
		$sName = $this->oForm->GetFieldName($this->sCode);
		$sReadOnly = $this->IsReadOnly() ? 'disabled="disabled"' :  '';
		
		$sHtml = "<select id=\"$sId\" name=\"$sName\" $sReadOnly>";
		foreach($this->aSubForms as $sKey => $aFormData)
		{
			$sDisplayValue = $aFormData['label'];
			$sSelected = ($sKey == $this->defaultValue) ? 'selected' : '';
			$sHtml .= "<option value=\"".htmlentities($sKey, ENT_QUOTES, 'UTF-8')."\" $sSelected>".htmlentities($sDisplayValue, ENT_QUOTES, 'UTF-8')."</option>";
		}
		$sHtml .= "</select>";
		
		if ($sRenderMode == 'property')
		{
			$sHtml .= '</td><td class="prop_icon prop_apply"><span title="Apply" class="ui-icon ui-icon-circle-check"/></td><td  class="prop_icon prop_cancel"><span title="Revert" class="ui-icon ui-icon-circle-close"/></td></tr>';
		}
				
		foreach($this->aSubForms as $sKey => $aFormData)
		{
			$sId = $this->oForm->GetFieldId($this->sCode);
			$sStyle = ($sKey == $this->defaultValue) ? '' : 'style="display:none"';
			$oSubForm = $aFormData['form'];
			$oSubForm->SetParentForm($this->oForm);
			$oSubForm->CopySubmitParams($this->oForm);
			$oSubForm->SetPrefix($this->oForm->GetPrefix().$sKey.'_');
			
			if ($sRenderMode == 'property')
			{
				$sHtml .= "</tbody><tbody class=\"subform\" id=\"{$sId}_{$sKey}\" $sStyle>";
				$sHtml .= $oSubForm->RenderAsPropertySheet($oP, true);	
			}
			else
			{
				$sHtml .= "<div class=\"subform\" id=\"{$sId}_{$sKey}\" $sStyle>";
				$sHtml .= $oSubForm->Render($oP, true);
				$sHtml .= "</div>";
			}
		}

		$oP->add_ready_script(
<<<EOF
$('#$sId').bind('change reverted', function() { $('.subform').hide(); $('#{$sId}_'+this.value).show(); } );
EOF
);
		return array('label' => $this->sLabel, 'value' => $sHtml);
	}

	public function ReadParam(&$aValues)
	{
		parent::ReadParam($aValues);
		$sKey = $aValues[$this->sCode];
		$aValues[$this->sCode] = $this->aSubForms[$sKey]['value'];
		
		$this->aSubForms[$sKey]['form']->SetPrefix($this->oForm->GetPrefix().$sKey.'_');
		$this->aSubForms[$sKey]['form']->SetParentForm($this->oForm);
		$this->aSubForms[$sKey]['form']->ReadParams($aValues);
	}
}

class DesignerSubFormField extends DesignerFormField
{
	protected $oSubForm;
	public function __construct($sLabel, $oSubForm)
	{
		parent::__construct('', $sLabel, '');
		$this->oSubForm = $oSubForm;
	}
	
	public function Render(WebPage $oP, $sFormId, $sRenderMode='dialog')
	{
		$this->oSubForm->SetParentForm($this->oForm);
		$this->oSubForm->CopySubmitParams($this->oForm);
		
		if ($sRenderMode == 'property')
		{
			$sHtml = $this->oSubForm->RenderAsPropertySheet($oP, true);
		}
		else
		{
			$sHtml = $this->oSubForm->Render($oP, true);
		}
		return array('label' => $this->sLabel, 'value' => $sHtml);
	}

	public function ReadParam(&$aValues)
	{	
		$this->oSubForm->SetParentForm($this->oForm);
		$this->oSubForm->ReadParams($aValues);
	}
}

?>
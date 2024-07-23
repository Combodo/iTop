<?php
// Copyright (C) 2010-2024 Combodo SAS
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
use Combodo\iTop\Application\WebPage\WebPage;

/**
 * Helper class to build interactive forms to be used either in stand-alone
 * modal dialog or in "property-sheet" panes.
 *
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
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
	protected $sHierarchyPath;   // Needed to manage the visibility of nested subform
	protected $sHierarchyParent; // Needed to manage the visibility of nested subform
	protected $sHierarchySelector; // Needed to manage the visibility of nested subform
	protected $bDisplayed;
	protected $aDefaultValues;
	protected $sFieldsSuffix;
	
	public function __construct()
	{
		$this->aFieldSets = array();
		$this->sCurrentFieldSet = '';
		$this->sScript = '';
		$this->sReadyScript = '';
		$this->sFormPrefix = '';
		$this->sFieldsSuffix = '';
		$this->sParamsContainer = '';
		$this->sFormId = 'form_'.rand();
		$this->oParentForm = null;
		$this->bReadOnly = false;
		$this->sHierarchyPath = '';
		$this->sHierarchyParent = '';
		$this->sHierarchySelector = '';
		$this->StartFieldSet($this->sCurrentFieldSet);
		$this->bDisplayed = true;
		$this->aDefaultValues = array();
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
		$sFormId = $this->GetFormId();
		if ($this->oParentForm == null)
		{
			$sReturn = '<form id="'.$sFormId.'">';
		}
		else
		{
			$sReturn = '';
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
			/** @var \DesignerFormField $oField */
			foreach($aFields as $oField) {
				$aRow = $oField->Render($oP, $sFormId);
				if ($oField->IsVisible()) {
					$sValidation = '<span class="prop_apply ibo-prop--apply ibo-button ibo-is-alternative">'.$this->GetValidationArea($oField->GetFieldId()).'</span>';
					$sField = $aRow['value'].$sValidation;
					$aDetails[] = array(
						'label' => $aRow['label'],
						'value' => $sField,
						'attcode' => $oField->GetCode(),
						'attlabel' => $aRow['label'],
						'inputid' => $this->GetFieldId($oField->GetCode()),
						'inputtype' => $oField->GetInputType(),
					);
				} else {
					$sHiddenFields .= $aRow['value'];
				}
			}
			$sReturn .= $oP->GetDetails($aDetails);

			if ($sLabel != '') {
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
	
	public function GetFieldSets()
	{
		return $this->aFieldSets;
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
	
	public function GetSubmitParams()
	{
		return array( 'url' => $this->sSubmitTo, 'params' => $this->aSubmitParams);
	}
	
	/**
	 * Helper to handle subforms hide/show	
	 */	
	public function SetHierarchyPath($sHierarchy)
	{
		$this->sHierarchyPath = $sHierarchy;
	}
	
	/**
	 * Helper to handle subforms hide/show	
	 */	
	public function GetHierarchyPath()
	{
		return $this->sHierarchyPath;
	}
		
	/**
	 * Helper to handle subforms hide/show	
	 */	
	public function SetHierarchyParent($sHierarchy)
	{
		$this->sHierarchyParent = $sHierarchy;
	}
	
	/**
	 * Helper to handle subforms hide/show	
	 */	
	public function GetHierarchyParent()
	{
		return $this->sHierarchyParent;
	}
	
	
	public function RenderAsPropertySheet($oP, $bReturnHTML = false, $sNotifyParentSelector = null)
	{
		$sReturn = '';
		$sActionUrl = addslashes($this->sSubmitTo ?? '');
		$sJSSubmitParams = json_encode($this->aSubmitParams);
		$sFormId = $this->GetFormId();
		if ($this->oParentForm == null) {
			$sReturn = '<form id="'.$sFormId.'" onsubmit="return false;">';
			$sReturn .= '<table class="prop_table">';
			$sReturn .= '<thead><tr><th class="ibo-prop-header">'.Dict::S('UI:Form:Property').'</th><th class="ibo-prop-header">'.Dict::S('UI:Form:Value').'</th><th colspan="2" class="ibo-prop-header">&nbsp;</th></tr></thead><tbody>';
		}

		$sHiddenFields = '';
		foreach ($this->aFieldSets as $sLabel => $aFields) {
			$aDetails = array();
			if ($sLabel != '') {
				$sReturn .= $this->StartRow().'<th colspan="4">'.$sLabel.'</th>'.$this->EndRow();
			}


			foreach ($aFields as $oField) {
				$aRow = $oField->Render($oP, $sFormId, 'property');
				if ($oField->IsVisible()) {
					$sFieldId = $this->GetFieldId($oField->GetCode());
					$sValidation = $this->GetValidationArea($sFieldId, '<div class="ibo-button ibo-is-alternative ibo-is-success" data-tooltip-content="'.Dict::Format('UI:DashboardEdit:Apply').'"><i class="fas fa-check"></i></div>');
					$sValidationFields = '</td><td class="prop_icon prop_apply ibo-prop--apply" >'.$sValidation.'</td><td  class="prop_icon prop_cancel ibo-prop--cancel"><span><div class="ibo-button ibo-is-alternative ibo-is-neutral" data-tooltip-content="'.Dict::Format('UI:DashboardEdit:Revert').'"><i class="fas fa-undo"></i></div></span></td>'
						.$this->EndRow();

					if (is_null($aRow['label'])) {
						$sReturn .= $this->StartRow($sFieldId).'<td class="prop_value" colspan="2">'.$aRow['value'];
					} else {
						$sReturn .= $this->StartRow($sFieldId).'<td class="prop_label">'.$aRow['label'].'</td><td class="prop_value">'.$aRow['value'];
					}
					if (!($oField instanceof DesignerFormSelectorField) && !($oField instanceof DesignerMultipleSubFormField)) {
						$sReturn .= $sValidationFields;
					}
					$sNotifyParentSelectorJS = is_null($sNotifyParentSelector) ? 'null' : "'".addslashes($sNotifyParentSelector)."'";
					$sAutoApply = $oField->IsAutoApply() ? 'true' : 'false';
					$sHandlerEquals = $oField->GetHandlerEquals();
					$sHandlerGetValue = $oField->GetHandlerGetValue();

					$sWidgetClass = $oField->GetWidgetClass();
					$sJSExtraParams = '';
					if (count($oField->GetWidgetExtraParams()) > 0)
					{
						$aExtraParams = array();
						foreach($oField->GetWidgetExtraParams() as $key=> $value)
						{
							$aExtraParams[] = "'$key': ".json_encode($value);
						}
						$sJSExtraParams = ', '.implode(', ', $aExtraParams);						
					}
					$this->AddReadyScript(
<<<EOF
$('#row_$sFieldId').$sWidgetClass({parent_selector: $sNotifyParentSelectorJS, field_id: '$sFieldId', equals: $sHandlerEquals, get_field_value: $sHandlerGetValue, auto_apply: $sAutoApply, value: '', submit_to: '$sActionUrl', submit_parameters: $sJSSubmitParams $sJSExtraParams });
CombodoTooltip.InitTooltipFromMarkup($('#$sFormId [data-tooltip-content]'));
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
			$sReturn .= '<div id="prop_submit_result"></div>'; // for the return of the submit operation
		}
		else
		{
			$sReturn .= $sHiddenFields;
		}
		$this->AddReadyScript(
<<<EOF
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
	
	public function StartRow($sFieldId = null)
	{
		if ($sFieldId != null)
		{
			return '<tr id="row_'.$sFieldId.'" data-path="'.$this->GetHierarchyPath().'" data-selector="'.$this->GetHierarchyParent().'">';
		}
		return '<tr data-path="'.$this->GetHierarchyPath().'" data-selector="'.$this->GetHierarchyParent().'">';
	}
	
	public function EndRow()
	{
		return '</tr>';
	}
	
	public function RenderAsDialog($oPage, $sDialogId, $sDialogTitle, $iDialogWidth, $sOkButtonLabel, $sIntroduction = null, $bAutoOpen = true)
	{
		$this->SetPrefix('dlg_'); // To make sure that the controls have different IDs that the property sheet which may be displayed at the same time
		
		$sDialogTitle = addslashes($sDialogTitle);
		$sOkButtonLabel = addslashes($sOkButtonLabel);
		$sCancelButtonLabel = Dict::S('UI:Button:Cancel');

		$oPage->add("<div id=\"$sDialogId\">");
		if ($sIntroduction != null)
		{
			$oPage->add('<div class="ui-dialog-header">'.$sIntroduction.'</div>');
		}
		$this->Render($oPage);
		$oPage->add('</div>');
		
		$sAutoOpen = $bAutoOpen ? 'true' : 'false';
		$oPage->add_ready_script(
<<<EOF
$('#$sDialogId').dialog({
		height: 'auto',
		maxHeight: $(window).height() * 0.9,
		width: $iDialogWidth,
		modal: true,
		autoOpen: $sAutoOpen,
		title: '$sDialogTitle',
		buttons: [
		{ text: "$sOkButtonLabel", click: function() {
			var oForm = $(this).closest('.ui-dialog').find('form');
			oForm.submit();
			if (AnimateDlgButtons)
			{
				sFormId = oForm.attr('id');
				if (oFormValidation[sFormId].length == 0)
				{
					AnimateDlgButtons(this);
				}
			}
		} },
		{ text: "$sCancelButtonLabel", click: function() { $(this).dialog( "close" ); $(this).remove(); } },
		],
		close: function() { $(this).remove(); }
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
				/** @var \DesignerFormField $oField */
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
		$sPrefix = '';	
		if ($this->oParentForm != null)
		{
			$sPrefix = $this->oParentForm->GetPrefix();
		}
		return $sPrefix.$this->sFormPrefix;
	}

	public function SetSuffix($sSuffix)
	{
		$this->sFieldsSuffix = $sSuffix;
	}
	
	public function GetSuffix()
	{
		$sSuffix = '';
		if ($this->oParentForm != null)
		{
			$sSuffix = $this->oParentForm->GetSuffix();
		}
		return $sSuffix.$this->sFieldsSuffix;
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
	
	public function SetDefaultValues($aDefaultValues)
	{
		if (!is_array($aDefaultValues)) return;
		
		foreach($this->aFieldSets as $sLabel => $aFields)
		{
			foreach($aFields as $oField)
			{
				$oField->SetDefaultValueFrom($aDefaultValues);
			}
		}
	}
	
	public function GetDefaultValues()
	{
		return $this->aDefaultValues;
	}
	
	
	public function GetParentForm()
	{
		return $this->oParentForm;
	}
	
	public function GetFormId()
	{
		if ($this->oParentForm)
		{
			$this->oParentForm->GetFormId();
		}
		return $this->sFormId;
	}
	
	public function SetDisplayed($bDisplayed)
	{
		$this->bDisplayed = $bDisplayed;
	}

	public function IsDisplayed()
	{
		if ($this->oParentForm == null)
		{
			return $this->bDisplayed;
		}
		else
		{
			return ($this->bDisplayed && $this->oParentForm->IsDisplayed());
		}
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
		return $this->GetPrefix().'attr_'.utils::GetSafeId($sCode.$this->GetSuffix());
	}
	
	public function GetFieldName($sCode)
	{
		return 'attr_'.$sCode.$this->GetSuffix();
	}
	
	public function GetParamName($sCode)
	{
		return 'attr_'.$sCode.$this->GetSuffix();
	}
	
	public function GetValidationArea($sId, $sContent = '')
	{
		return "<span id=\"v_{$sId}\">$sContent</span>";
	}
	public function GetAsyncActionClass()
	{
		return $this->sAsyncActionClass;
	}
	
	public function FindField($sFieldCode)
	{
		$oFoundField = false;
		foreach($this->aFieldSets as $sLabel => $aFields)
		{
			foreach($aFields as $oField)
			{
				$oFoundField = $oField->FindField($sFieldCode);
				if ($oFoundField !== false) break;
			}
			if ($oFoundField !== false) break;
		}
		return $oFoundField;		
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

	public function RenderAsPropertySheet($oP, $bReturnHTML = false, $sNotifyParentSelector = null)
	{
		return $this->Render($oP, $bReturnHTML);
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
	/** @var string $sLabel */
	protected $sLabel;
	/** @var string $sCode */
	protected $sCode;
	/** @var mixed $defaultValue */
	protected $defaultValue;
	/** @var \DesignerForm $oForm */
	protected $oForm;
	/** @var bool $bMandatory */
	protected $bMandatory;
	/** @var bool $bReadOnly */
	protected $bReadOnly;
	/** @var bool $bAutoApply */
	protected $bAutoApply;
	/** @var array $aCSSClasses */
	protected $aCSSClasses;
	/** @var bool $bDisplayed */
	protected $bDisplayed;
	/** @var array $aWidgetExtraParams */
	protected $aWidgetExtraParams;

	/**
	 * DesignerFormField constructor.
	 *
	 * @param string $sCode
	 * @param string $sLabel
	 * @param mixed $defaultValue
	 */
	public function __construct($sCode, $sLabel, $defaultValue)
	{
		$this->sLabel = $sLabel;
		$this->sCode = $sCode;
		$this->defaultValue = $defaultValue;
		$this->bMandatory = false;
		$this->bReadOnly = false;
		$this->bAutoApply = false;
		$this->aCSSClasses = [];
		if (ContextTag::Check(ContextTag::TAG_CONSOLE)) {
			$this->aCSSClasses[] = 'ibo-input';
		}
		$this->bDisplayed = true;
		$this->aWidgetExtraParams = array();
	}

	/**
	 * Important, for now we use constants from the \cmdbAbstractObject class, introducing a coupling that should not exist.
	 * This has been traced under N°4241 and will be discussed during the next modernization batch.
	 *
	 * @return string|null Return the input type of the field
	 * @see \cmdbAbstractObject::ENUM_INPUT_TYPE_XXX
	 * @since 3.0.0
	 */
	public function GetInputType(): ?string
	{
		return cmdbAbstractObject::ENUM_INPUT_TYPE_SINGLE_INPUT;
	}

	/**
	 * @return string
	 */
	public function GetCode()
	{
		return $this->sCode;
	}

    /**
     * @param \DesignerForm $oForm
     */
	public function SetForm(DesignerForm $oForm)
	{
		$this->oForm = $oForm;
	}

	/**
	 * @param bool $bMandatory
	 */
	public function SetMandatory($bMandatory = true)
	{
		$this->bMandatory = $bMandatory;
	}

	/**
	 * @param bool $bReadOnly
	 */
	public function SetReadOnly($bReadOnly = true)
	{
		$this->bReadOnly = $bReadOnly;
	}

	/**
	 * @return bool
	 */
	public function IsReadOnly()
	{
		return ($this->oForm->IsReadOnly() || $this->bReadOnly);
	}

	/**
	 * @param bool $bAutoApply
	 */
	public function SetAutoApply($bAutoApply)
	{
		$this->bAutoApply = $bAutoApply;
	}

	/**
	 * @return bool
	 */
	public function IsAutoApply()
	{
		return $this->bAutoApply;
	}

	/**
	 * @param bool $bDisplayed
	 */
	public function SetDisplayed($bDisplayed)
	{
		$this->bDisplayed = $bDisplayed;
	}

	/**
	 * @return bool
	 */
	public function IsDisplayed()
	{
		return $this->bDisplayed;
	}

	/**
	 * @return string
	 */
	public function GetFieldId()
	{
		return $this->oForm->GetFieldId($this->sCode);
	}

	/**
	 * @return string
	 */
	public function GetWidgetClass()
	{
		return 'property_field';
	}

	/**
	 * @return array
	 */
	public function GetWidgetExtraParams()
	{
		return $this->aWidgetExtraParams;
	}

	/**
	 * @param WebPage $oP
	 * @param string $sFormId
	 * @param string $sRenderMode
	 *
	 * @return array
	 */
	public function Render(WebPage $oP, $sFormId, $sRenderMode='dialog')
	{
		$sId = $this->oForm->GetFieldId($this->sCode);
		$sName = $this->oForm->GetFieldName($this->sCode);

		return array('label' => $this->sLabel, 'value' => "<input type=\"text\" id=\"$sId\" name=\"$sName\" value=\"".utils::EscapeHtml($this->defaultValue)."\">");
	}

	/**
	 * @param array $aValues
	 */
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

	/**
	 * @return bool
	 */
	public function IsVisible()
	{
		return true;
	}

	/**
	 * @param string $sCSSClass
	 */
	public function AddCSSClass($sCSSClass)
	{
		$this->aCSSClasses[] = $sCSSClass;
	}
	
	/**
	 * A way to set/change the default value after constructing the field
	 *
	 * @param array $aAllDefaultValue
	 */
	public function SetDefaultValueFrom($aAllDefaultValue)
	{
		if (array_key_exists($this->GetCode(), $aAllDefaultValue))
		{
			$this->defaultValue = $aAllDefaultValue[$this->GetCode()];
		}
	}

	/**
	 * @param $sFieldCode
	 *
	 * @return \DesignerFormField|false
	 */
	public function FindField($sFieldCode)
	{
		if ($this->sCode == $sFieldCode)
		{
			return $this;
		}
		return false;
	}

	/**
	 * @return string
	 */
	public function GetHandlerEquals()
	{
		return 'null';
	}

	/**
	 * @return string
	 */
	public function GetHandlerGetValue()
	{
		return 'null';
	}
}

class DesignerLabelField extends DesignerFormField
{
	/** @var int $iCount A counter to automatically make the field code */
	protected static $iCount = 0;
	/** @var string $sDescription */
	protected $sDescription;

	/**
	 * @inheritdoc
	 */
	public function __construct($sLabel, $sDescription)
	{
		// Increase counter
		static::$iCount++;

		parent::__construct('label_number_' . static::$iCount, $sLabel, '');
		$this->sDescription = $sDescription;
	}

	/**
	 * @inheritdoc
	 */
	public function Render(WebPage $oP, $sFormId, $sRenderMode='dialog')
	{
		$sId = $this->oForm->GetFieldId($this->sCode);
		$sName = $this->oForm->GetFieldName($this->sCode);
		return array('label' => $this->sLabel, 'value' => $this->sDescription);
	}

	/**
	 * @inheritdoc
	 */
	public function ReadParam(&$aValues)
	{
	}

	/**
	 * @inheritdoc
	 */
	public function IsVisible()
	{
		return true;
	}
}

class DesignerTextField extends DesignerFormField
{
	protected $sValidationPattern;
	protected $aForbiddenValues;
	public function __construct($sCode, $sLabel = '', $defaultValue = '')
	{
		parent::__construct($sCode, $sLabel, $defaultValue);
		$this->sValidationPattern = '';
		$this->aForbiddenValues = array();
	}
	
	public function SetValidationPattern($sValidationPattern)
	{
		$this->sValidationPattern = $sValidationPattern;
	}

	public function SetForbiddenValues($aValues, $sExplain, $bCaseSensitive = true)
	{
		$aForbiddenValues = $aValues;
		
		$iDefaultKey = array_search($this->defaultValue, $aForbiddenValues);
		if ($iDefaultKey !== false)
		{
			// The default (current) value is always allowed...
			unset($aForbiddenValues[$iDefaultKey]);
			
		}
		
		$this->aForbiddenValues[] = array('values' => $aForbiddenValues, 'message' => $sExplain, 'case_sensitive' => $bCaseSensitive);
	}
	
	public function Render(WebPage $oP, $sFormId, $sRenderMode='dialog')
	{
		$sId = $this->oForm->GetFieldId($this->sCode);
		
		$sName = $this->oForm->GetFieldName($this->sCode);
		if ($this->IsReadOnly()) {
			$sHtmlValue = "<span>".utils::EscapeHtml($this->defaultValue)."<input type=\"hidden\" id=\"$sId\" name=\"$sName\" value=\"".utils::EscapeHtml($this->defaultValue)."\"/></span>";
		}
		else
		{
			$sPattern = addslashes($this->sValidationPattern);
			if (is_array($this->aForbiddenValues))
			{
				$sForbiddenValues = json_encode($this->aForbiddenValues);
			}
			else
			{
				$sForbiddenValues = '[]'; //Empty JS array
			}
			$sMandatory = $this->bMandatory ? 'true' :  'false';
			$oP->add_ready_script(
<<<EOF
$('#$sId').on('change keyup validate', function() { ValidateWithPattern('$sId', $sMandatory, '$sPattern', $(this).closest('form').attr('id'), $sForbiddenValues); } );
{
	var myTimer = null;
	$('#$sId').on('keyup', function() { clearTimeout(myTimer); myTimer = setTimeout(function() { $('#$sId').trigger('change', {} ); }, 100); });
}
EOF
			);
			$sCSSClasses = '';
			if (count($this->aCSSClasses) > 0) {
				$sCSSClasses = 'class="'.implode(' ', $this->aCSSClasses).'"';
			}
			$sHtmlValue = "<input type=\"text\" $sCSSClasses id=\"$sId\" name=\"$sName\" value=\"".utils::EscapeHtml($this->defaultValue)."\">";
		}
		return array('label' => $this->sLabel, 'value' => $sHtmlValue);
	}

	public function ReadParam(&$aValues)
	{
		parent::ReadParam($aValues);
		$sPattern = '/'.str_replace('/', '\/', $this->sValidationPattern).'/'; // Escape the forward slashes since they are used as delimiters for preg_match
		if (($this->sValidationPattern != '') && (!preg_match($sPattern, $aValues[$this->sCode])) ) 
		{
			$aValues[$this->sCode] = $this->defaultValue;
		}
		else if(($this->aForbiddenValues != null) && in_array($aValues[$this->sCode], $this->aForbiddenValues))
		{
			// Reject the value...
			$aValues[$this->sCode] = $this->defaultValue;
		}
	}
}

class DesignerLongTextField extends DesignerTextField
{
	public function __construct($sCode, $sLabel = '', $defaultValue = '')
	{
		parent::__construct($sCode, $sLabel, $defaultValue);

		if (ContextTag::Check(ContextTag::TAG_CONSOLE)) {
			$this->aCSSClasses[] = 'ibo-input-text';
		}
	}

	/**
	 * @inheritDoc
	 */
	public function GetInputType(): string
	{
		return cmdbAbstractObject::ENUM_INPUT_TYPE_TEXTAREA;
	}

	public function Render(WebPage $oP, $sFormId, $sRenderMode='dialog')
	{
		$sId = $this->oForm->GetFieldId($this->sCode);
		$sName = $this->oForm->GetFieldName($this->sCode);
		$sPattern = addslashes($this->sValidationPattern);
		if (is_array($this->aForbiddenValues))
		{
			$sForbiddenValues = json_encode($this->aForbiddenValues);
		}
		else
		{
			$sForbiddenValues = '[]'; //Empty JS array
		}
		$sMandatory = $this->bMandatory ? 'true' :  'false';
		$sCSSClasses = '';
		if (count($this->aCSSClasses) > 0)
		{
			$sCSSClasses = 'class="'.implode(' ', $this->aCSSClasses).'"';
		}
		if (!$this->IsReadOnly()) {
			$oP->add_ready_script(
				<<<EOF
$('#$sId').on('change keyup validate', function() { ValidateWithPattern('$sId', $sMandatory, '$sPattern',  $(this).closest('form').attr('id'), $sForbiddenValues); } );
{
	var myTimer = null;
	$('#$sId').on('keyup', function() { clearTimeout(myTimer); myTimer = setTimeout(function() { $('#$sId').trigger('change', {} ); }, 100); });
}
EOF
			);
			$sValue = "<textarea $sCSSClasses id=\"$sId\" name=\"$sName\">".$this->PrepareValueForRendering()."</textarea>";
		}
		else {
			$sValue = "<div $sCSSClasses id=\"$sId\">".$this->PrepareValueForRendering()."</div>";
		}
		return array('label' => $this->sLabel, 'value' => $sValue);
	}

	/**
	 * @return string|null The value itself as expected for rendering. May it be encoded, escaped or else.
	 * @since 3.1.0 N°6405
	 */
	protected function PrepareValueForRendering(): ?string
	{
		return utils::EscapeHtml($this->defaultValue);
	}
}

/**
 * Class DesignerXMLField
 *
 * Field to display XML content
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @since 3.1.0 N°6405
 */
class DesignerXMLField extends DesignerLongTextField
{
	/**
	 * @inheritDoc
	 */
	protected function PrepareValueForRendering(): ?string
	{
		return utils::EscapeHtml($this->defaultValue, true);
	}
}

class DesignerIntegerField extends DesignerFormField
{
	protected $iMin; // Lower boundary, inclusive
	protected $iMax; // Higher boundary, inclusive

	public function __construct($sCode, $sLabel = '', $defaultValue = '')
	{
		parent::__construct($sCode, $sLabel, $defaultValue);
		$this->iMin = 0; // Positive integer is the default
		$this->iMax = null;
	}

	public function SetBoundaries($iMin = null, $iMax = null)
	{
		$this->iMin = $iMin;
		$this->iMax = $iMax;
	}
	
	public function Render(WebPage $oP, $sFormId, $sRenderMode='dialog')
	{
		$sId = $this->oForm->GetFieldId($this->sCode);
		
		$sName = $this->oForm->GetFieldName($this->sCode);
		if ($this->IsReadOnly()) {
			$sHtmlValue = "<span>".utils::EscapeHtml($this->defaultValue)."<input type=\"hidden\" id=\"$sId\" name=\"$sName\" value=\"".utils::EscapeHtml($this->defaultValue)."\"/></span>";
		}
		else
		{
			$sMin = json_encode($this->iMin);
			$sMax = json_encode($this->iMax);
			$sMandatory = $this->bMandatory ? 'true' :  'false';
			$oP->add_ready_script(
<<<EOF
$('#$sId').on('change keyup validate', function() { ValidateInteger('$sId', $sMandatory,  $(this).closest('form').attr('id'), $sMin, $sMax); } );
{
	var myTimer = null;
	$('#$sId').on('keyup', function() { clearTimeout(myTimer); myTimer = setTimeout(function() { $('#$sId').trigger('change', {} ); }, 100); });
}
EOF
			);
			$sCSSClasses = '';
			if (count($this->aCSSClasses) > 0) {
				$sCSSClasses = 'class="'.implode(' ', $this->aCSSClasses).'"';
			}
			$sHtmlValue = "<input type=\"text\" $sCSSClasses id=\"$sId\" name=\"$sName\" value=\"".utils::EscapeHtml($this->defaultValue)."\">";
		}
		return array('label' => $this->sLabel, 'value' => $sHtmlValue);
	}

	public function ReadParam(&$aValues)
	{
		parent::ReadParam($aValues);

		if (!is_null($this->iMin) && ($aValues[$this->sCode] < $this->iMin))
		{
			// Reject the value...
			$aValues[$this->sCode] = $this->defaultValue;
		}
		if (!is_null($this->iMax) && ($aValues[$this->sCode] > $this->iMax))
		{
			// Reject the value...
			$aValues[$this->sCode] = $this->defaultValue;
		}
	}
}

class DesignerComboField extends DesignerFormField
{
	protected $aAllowedValues;
	protected $bMultipleSelection;
	protected $bOtherChoices;
	protected $sNullLabel;
	protected $bSorted;
	
	public function __construct($sCode, $sLabel = '', $defaultValue = '')
	{
		parent::__construct($sCode, $sLabel, $defaultValue);
		$this->aAllowedValues = array();
		$this->bMultipleSelection = false;
		$this->bOtherChoices = false;
		$this->sNullLabel = Dict::S('UI:SelectOne');

		if (ContextTag::Check(ContextTag::TAG_CONSOLE)) {
			$this->aCSSClasses[] = 'ibo-input-select';
		}

		$this->bAutoApply = true;
		$this->bSorted = true; // Sorted by default
	}

	/**
	 * @inheritDoc
	 */
	public function GetInputType(): ?string
	{
		if ($this->bMultipleSelection) {
			return cmdbAbstractObject::ENUM_INPUT_TYPE_DROPDOWN_MULTIPLE_CHOICES;
		}
		else {
			return cmdbAbstractObject::ENUM_INPUT_TYPE_DROPDOWN_RAW;
		}
	}

	public function SetAllowedValues(?array $aAllowedValues)
	{
		// Make sure to have an actual array for values
		if (is_null($aAllowedValues)) {
			$aAllowedValues = [];
		}

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

	/**
	 * An empty label will disable the default empty value
	 */	 	
	public function SetNullLabel($sLabel)
	{
		$this->sNullLabel = $sLabel;
	}
	
	public function IsSorted()
	{
		return $this->bSorted;
	}
	
	public function SetSorted($bSorted)
	{
		$this->bSorted = $bSorted;
	}
	
	public function Render(WebPage $oP, $sFormId, $sRenderMode='dialog')
	{
		$sId = $this->oForm->GetFieldId($this->sCode);
		$sName = $this->oForm->GetFieldName($this->sCode);
		$sChecked = $this->defaultValue ? 'checked' : '';
		$sMandatory = $this->bMandatory ? 'true' :  'false';
		$sReadOnly = $this->IsReadOnly() ? 'disabled="disabled"' :  '';
		if ($this->IsSorted() )
		{
			asort($this->aAllowedValues);
		}
		$sCSSClasses = '';
		if (count($this->aCSSClasses) > 0)
		{
			$sCSSClasses = 'class="'.implode(' ', $this->aCSSClasses).'"';
		}
		if ($this->IsReadOnly())
		{
			$aSelected = array();
			$aHiddenValues = array();
			foreach($this->aAllowedValues as $sKey => $sDisplayValue)
			{
				if ($this->bMultipleSelection)
				{
					if(in_array($sKey, $this->defaultValue)) {
						$aSelected[] = $sDisplayValue;
						$aHiddenValues[] = "<input type=\"hidden\" name=\"{$sName}[]\" value=\"".utils::EscapeHtml($sKey)."\"/>";
					}
				} else {
					if ($sKey == $this->defaultValue) {
						$aSelected[] = $sDisplayValue;
						$aHiddenValues[] = "<input type=\"hidden\" id=\"$sId\" name=\"$sName\" value=\"".utils::EscapeHtml($sKey)."\"/>";
					}
				}
			}
			$sHtml = "<span $sCSSClasses>".utils::EscapeHtml(implode(', ', $aSelected)).implode($aHiddenValues)."</span>";
		}
		else
		{
			if ($this->bMultipleSelection)
			{
				$iSize = max(1, min(8, count($this->aAllowedValues)));
				$sHtml = "<span><select $sCSSClasses multiple size=\"$iSize\" id=\"$sId\" name=\"$sName\">";
			}
			else
			{
				$sHtml = "<span class=\"ibo-input-select-wrapper\"><select $sCSSClasses id=\"$sId\" name=\"$sName\">";
				if ($this->sNullLabel != '')
				{
					$sHtml .= "<option value=\"\">".$this->sNullLabel."</option>";
				}
			}
			foreach ($this->aAllowedValues as $sKey => $sDisplayValue) {
				if ($this->bMultipleSelection) {
					$sSelected = in_array($sKey, $this->defaultValue) ? 'selected' : '';
				} else {
					$sSelected = ($sKey == $this->defaultValue) ? 'selected' : '';
				}
				// Quick and dirty: display the menu parents as a tree
				$sHtmlValue = str_replace(' ', '&nbsp;', $sDisplayValue);
				$sHtml .= "<option value=\"".utils::EscapeHtml($sKey)."\" $sSelected>$sHtmlValue</option>";
			}
			$sHtml .= "</select></span>";
			if ($this->bOtherChoices)
			{
				$sHtml .= '<br/><input type="checkbox" id="other_chk_'.$sId.'"><label for="other_chk_'.$sId.'">&nbsp;Other:</label>&nbsp;<input type="text" id="other_'.$sId.'" name="other_'.$sName.'" size="30"/>'; 
			}
			$oP->add_ready_script(
<<<EOF
$('#$sId').on('change validate', function() { ValidateWithPattern('$sId', $sMandatory, '',  $(this).closest('form').attr('id'), null, null); } );
EOF
			);
		}
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
		if (ContextTag::Check(ContextTag::TAG_CONSOLE)) {
			$this->aCSSClasses[] = 'ibo-input-checkbox';
		}
	}

	/**
	 * @inheritDoc
	 */
	public function GetInputType(): ?string
	{
		return cmdbAbstractObject::ENUM_INPUT_TYPE_CHECKBOX;
	}
	
	public function Render(WebPage $oP, $sFormId, $sRenderMode='dialog')
	{
		$sId = $this->oForm->GetFieldId($this->sCode);
		$sName = $this->oForm->GetFieldName($this->sCode);
		$sChecked = $this->defaultValue ? 'checked' : '';
		if ($this->IsReadOnly()) {
			$sLabel = $this->defaultValue ? Dict::S('UI:UserManagement:ActionAllowed:Yes') : Dict::S('UI:UserManagement:ActionAllowed:No'); //TODO use our own yes/no translations
			$sHtmlValue = "<span>".utils::EscapeHtml($sLabel)."<input type=\"hidden\" id=\"$sId\" name=\"$sName\" value=\"".utils::EscapeHtml($this->defaultValue)."\"/></span>";
		}
		else
		{
			$sCSSClasses = '';
			if (count($this->aCSSClasses) > 0)
			{
				$sCSSClasses = 'class="'.implode(' ', $this->aCSSClasses).'"';
			}
			$sHtmlValue = "<input $sCSSClasses type=\"checkbox\" $sChecked id=\"$sId\" name=\"$sName\" value=\"true\">";
		}
		return array('label' => $this->sLabel, 'value' => $sHtmlValue);
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
			$aValues[$this->sCode] = ($sValue == 'true');
		}
	}
}

class DesignerHiddenField extends DesignerFormField
{
	public function __construct($sCode, $sLabel = '', $defaultValue = '')
	{
		parent::__construct($sCode, $sLabel, $defaultValue);
	}

	/**
	 * @inheritDoc
	 */
	public function GetInputType(): ?string
	{
		return null;
	}
	
	public function IsVisible()
	{
		return false;
	}
	
	public function Render(WebPage $oP, $sFormId, $sRenderMode='dialog')
	{
		$sId = $this->oForm->GetFieldId($this->sCode);
		$sName = $this->oForm->GetFieldName($this->sCode);

		return array('label' => '', 'value' => "<input type=\"hidden\" id=\"$sId\" name=\"$sName\" value=\"".utils::EscapeHtml($this->defaultValue)."\">");
	}
}


class DesignerIconSelectionField extends DesignerFormField
{
	protected $sUploadUrl;
	protected $aAllowedValues;
	
	public function __construct($sCode, $sLabel = '', $defaultValue = '')
	{
		parent::__construct($sCode, $sLabel, $defaultValue);
		$this->bAutoApply = true;
		$this->sUploadUrl = null;
	}

	/**
	 * @inheritDoc
	 */
	public function GetInputType(): ?string
	{
		return cmdbAbstractObject::ENUM_INPUT_TYPE_DROPDOWN_DECORATED;
	}
	
	public function SetAllowedValues($aAllowedValues)
	{
		$this->aAllowedValues = $aAllowedValues;
	}

	public function EnableUpload($sIconUploadUrl)
	{
		$this->sUploadUrl = $sIconUploadUrl;
	}

	public function Render(WebPage $oP, $sFormId, $sRenderMode='dialog')
	{
		$sId = $this->oForm->GetFieldId($this->sCode);
		$sName = $this->oForm->GetFieldName($this->sCode);
		$idx = 0;
		$idxFallback = 0;
		foreach ($this->aAllowedValues as $index => $aValue) {
			if ($aValue['value'] == $this->defaultValue) {
				$idx = $index;
				break;
			}
			//fallback if url of default value contains ../
			//for contact, icon is http://localhost/env-production/itop-structure/../../images/icons/icons8-customer.svg => not found http://localhost/images/icons/icons8-customer.svg
			if (basename($aValue['value']) == basename($this->defaultValue)) {
				$idxFallback = $index;
			}
		}
		if ($idx == 0) {
			$idx = $idxFallback;
		}
		$sJSItems = json_encode($this->aAllowedValues);
		$sPostUploadTo = ($this->sUploadUrl == null) ? 'null' : "'{$this->sUploadUrl}'";
		if (!$this->IsReadOnly()) {
			$sDefaultValue = ($this->defaultValue !== '') ? $this->defaultValue : $this->aAllowedValues[$idx]['value'];
			$sCSSClasses = ContextTag::Check(ContextTag::TAG_CONSOLE) ? 'class="ibo-input-select-wrapper"' : '';
			$sValue = "<span $sCSSClasses><input type=\"hidden\" id=\"$sId\" name=\"$sName\" value=\"{$sDefaultValue}\"/></span>";
			$oP->add_ready_script(
				<<<EOF
	$('#$sId').icon_select({current_idx: $idx, items: $sJSItems, post_upload_to: $sPostUploadTo});
EOF
			);
		} else {
			$sValue = '<span style="display:inline-block;line-height:48px;height:48px;"><span><img style="vertical-align:middle" src="'.$this->aAllowedValues[$idx]['icon'].'" />&nbsp;'.utils::EscapeHtml($this->aAllowedValues[$idx]['label']).'</span></span>';
		}
		$sReadOnly = $this->IsReadOnly() ? 'disabled' : '';
		return array('label' => $this->sLabel, 'value' => $sValue);
	}
}

class RunTimeIconSelectionField extends DesignerIconSelectionField
{
	static $aAllIcons = array();
	
	public function __construct($sCode, $sLabel = '', $defaultValue = '')
	{
		parent::__construct($sCode, $sLabel, $defaultValue);
		$aFolderList = [
			APPROOT.'env-'.utils::GetCurrentEnvironment() => utils::GetAbsoluteUrlModulesRoot(),
			APPROOT.'images/icons' => utils::GetAbsoluteUrlAppRoot().'images/icons',
		];
		if (count(self::$aAllIcons) == 0) {
			foreach ($aFolderList as $sFolderPath => $sUrlPrefix) {
				$aIcons = self::FindIconsOnDisk($sFolderPath);
				ksort($aIcons);

				foreach ($aIcons as $sFilePath) {
					self::$aAllIcons[] = array('value' => $sFilePath, 'label' => basename($sFilePath), 'icon' => $sUrlPrefix.$sFilePath);
				}
			}
		}
		$this->SetAllowedValues(self::$aAllIcons);
	}

	static protected function FindIconsOnDisk($sBaseDir, $sDir = '')
	{
		$aFiles = null;
		$sKey = $sBaseDir.'/'.$sDir;
		$sShortKey = abs(crc32($sKey));
		$sCacheFile = utils::GetCachePath().'available-icons-'.$sShortKey.'.php';
		$sCacheClass = 'AvailableIcons_'.$sShortKey;
		if (file_exists($sCacheFile))
		{
			require_once($sCacheFile);
			if ($sCacheClass::$sKey === $sKey) // crc32 collision detection
			{
				$aFiles = $sCacheClass::$aIconFiles;
			}
		}
		if ($aFiles === null)
		{
			$aFiles = self::_FindIconsOnDisk($sBaseDir, $sDir);
			$sAvailableIcons = '<?php'.PHP_EOL;
			$sAvailableIcons .= '// Generated and used by '.__METHOD__.PHP_EOL;
			$sAvailableIcons .= 'class '.$sCacheClass.PHP_EOL;
			$sAvailableIcons .= '{'.PHP_EOL;
			$sAvailableIcons .= '   static $sKey = '.var_export($sKey, true).';'.PHP_EOL;
			$sAvailableIcons .= '   static $aIconFiles = '.var_export($aFiles, true).';'.PHP_EOL;
			$sAvailableIcons .= '}'.PHP_EOL;
			SetupUtils::builddir(dirname($sCacheFile));
			file_put_contents($sCacheFile, $sAvailableIcons, LOCK_EX);
		}

		return $aFiles;
	}

	static protected function _FindIconsOnDisk($sBaseDir, $sDir = '', &$aFilesSpecs = [])
	{
		$aResult = [];
		// Populate automatically the list of icon files
		if ($hDir = @opendir($sBaseDir.'/'.$sDir)) {
			while (($sFile = readdir($hDir)) !== false) {
				$aMatches = array();
				if (($sFile != '.') && ($sFile != '..') && ($sFile != 'lifecycle') && is_dir($sBaseDir.'/'.$sDir.'/'.$sFile)) {
					$sDirSubPath = ($sDir == '') ? $sFile : $sDir.'/'.$sFile;
					$aResult = array_merge($aResult, self::_FindIconsOnDisk($sBaseDir, $sDirSubPath, $aFilesSpecs));
				}
				$sSize = filesize($sBaseDir.'/'.$sDir.'/'.$sFile);
				if (isset($aFilesSpecs[$sFile]) && $aFilesSpecs[$sFile] == $sSize) {
					continue;
				}
				if (preg_match("/\.(png|jpg|jpeg|gif|svg)$/i", $sFile, $aMatches)) // png, jp(e)g, gif and svg are considered valid
				{
					$aResult[$sFile.'_'.$sDir] = $sDir.'/'.$sFile;
					$aFilesSpecs[$sFile] = $sSize;
				}
			}
			closedir($hDir);
		}
		return $aResult;
	}

	public function ValueFromDOMNode($oDOMNode)
	{
		return $oDOMNode->textContent;
	}

	public function ValueToDOMNode($oDOMNode, $value)
	{
		$oTextNode = $oDOMNode->ownerDocument->createTextNode($value);
		$oDOMNode->appendChild($oTextNode);
	}

	public function MakeFileUrl($value)
	{
		return utils::GetAbsoluteUrlModulesRoot().$value;
	}

	public function GetDefaultValue($sClass = 'Contact')
	{
		$sIcon = '';
		if (MetaModel::IsValidClass($sClass))
		{
			$sIconPath = MetaModel::GetClassIcon($sClass, false);
			$sIcon = str_replace(utils::GetAbsoluteUrlModulesRoot(), '', $sIconPath);
		}
		return $sIcon;	
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

	/**
	 * @inheritDoc
	 */
	public function GetInputType(): ?string
	{
		return null;
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
		$sReadOnly = $this->IsReadOnly() ? 'readonly="readonly"' : '';
		$aResult = array('label' => $this->sLabel, 'value' => "<input type=\"hidden\" id=\"$sId\" name=\"$sName\" $sReadOnly value=\"".utils::EscapeHtml($this->defaultValue)."\">");


		$sJSFields = json_encode(array_keys($this->aAllowedValues));
		$oP->add_ready_script(
			"$('#$sId').sortable_field({aAvailableFields: $sJSFields});"
		);

		return $aResult;
	}
}

class DesignerFormSelectorField extends DesignerFormField
{
	protected $aSubForms;
	protected $defaultRealValue; // What's stored as default value is actually the index
	protected $bSorted;
	
	public function __construct($sCode, $sLabel = '', $defaultValue = '')
	{
		parent::__construct($sCode, $sLabel, 0);
		$this->defaultRealValue = $defaultValue;
		$this->aSubForms = array();
		$this->bSorted = true;
		if (ContextTag::Check(ContextTag::TAG_CONSOLE)) {
			$this->aCSSClasses[] = 'ibo-input-select';
		}
	}

	/**
	 * @inheritDoc
	 */
	public function GetInputType(): ?string
	{
		return null;
	}

	public function IsSorted()
	{
		return $this->bSorted;
	}
	
	public function SetSorted($bSorted)
	{
		$this->bSorted = $bSorted;
	}
	
	/**
	 * Callback for sorting an array of $aFormData based ont he labels of the subforms
	 * @param unknown $aItem1
	 * @param unknown $aItem2
	 * @return number
	 */
	static function SortOnFormLabel($aItem1, $aItem2)
	{
		return strcasecmp($aItem1['label'], $aItem2['label']);
	}
		
	public function GetWidgetClass()
	{
		return 'selector_property_field';
	}
	
	public function AddSubForm($oSubForm, $sLabel, $sValue)
	{
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
		$sReadOnly = $this->IsReadOnly() ? 'disabled="disabled"' : '';

		$this->aCSSClasses[] = 'formSelector';

		$sCSSClasses = '';
		if (count($this->aCSSClasses) > 0) {
			$sCSSClasses = 'class="'.implode(' ', $this->aCSSClasses).'"';
		}

		if ($this->IsSorted()) {
			uasort($this->aSubForms, array(get_class($this), 'SortOnFormLabel'));
		}

		if ($this->IsReadOnly()) {
			$sDisplayValue = '';
			$sHiddenValue = '';
			foreach ($this->aSubForms as $iKey => $aFormData) {
				if ($iKey == $this->defaultValue) // Default value is actually the index
				{
					$sDisplayValue = utils::EscapeHtml($aFormData['label']);
					$sHiddenValue = "<input type=\"hidden\" id=\"$sId\" name=\"$sName\" value=\"".utils::EscapeHtml($iKey)."\"/>";
					break;
				}
			}
			$sHtml = "<span $sCSSClasses>".$sDisplayValue.$sHiddenValue."</span>";
		} else {
			$sHtml = "<span class=\"ibo-input-select-wrapper\"><select $sCSSClasses id=\"$sId\" name=\"$sName\" $sReadOnly>";
			foreach ($this->aSubForms as $iKey => $aFormData) {
				$sDisplayValue = utils::EscapeHtml($aFormData['label']);
				$sValue = utils::EscapeHtml($aFormData['value']);
				$sSelected = ($iKey == $this->defaultValue) ? 'selected' : '';
				$sHtml .= "<option data-value=\"$sValue\" value=\"$iKey\" $sSelected>".$sDisplayValue."</option>";
			}
			$sHtml .= "</select></span>";
		}

		if ($sRenderMode == 'property') {
			$sHtml .= '</td><td class="prop_icon prop_apply ibo-prop--apply"><span><button class="ibo-button ibo-is-alternative ibo-is-success" data-tooltip-content="'.Dict::Format('UI:DashboardEdit:Apply').'"><i class="fas fa-check"></i></button></span></td><td class="prop_icon prop_cancel ibo-prop--cancel"><span><button class="ibo-button ibo-is-alternative ibo-is-neutral" data-tooltip-content="'.Dict::Format('UI:DashboardEdit:Revert').'"><i class="fas fa-times"></i></button></span></td></tr>';
		}
		foreach ($this->aSubForms as $sKey => $aFormData) {
			$sId = $this->oForm->GetFieldId($this->sCode);
			$sStyle = (($sKey == $this->defaultValue) && $this->oForm->IsDisplayed()) ? '' : 'style="display:none"';
			$oSubForm = $aFormData['form'];
			$oSubForm->SetParentForm($this->oForm);
			$oSubForm->CopySubmitParams($this->oForm);
			$oSubForm->SetPrefix($this->oForm->GetPrefix().$sKey.'_');

			if ($sRenderMode == 'property') {
				// Note: Managing the visibility of nested subforms had several implications
				// 1) Attributes are displayed in a table and we have to group them in as many tbodys as necessary to hide/show the various options depending on the current selection
				// 2) It is not possible to nest tbody tags. Therefore, it is not possible to manage the visibility the same way as it is done for the dialog mode (using nested divs).
				// The div hierarchy has been emulated by adding attributes to the tbody tags:
				// - data-selector : uniquely identifies the DesignerFormSelectorField that has an impact on the visibility of the node
				// - data-path : uniquely identifies the combination of users choices that must be made to show the node
				// - data-state : records the state, depending on the user choice on the FormSelectorField just above the node, but indepentantly from the visibility in the page (can be visible in the form itself being in a hidden form)
				// Then a series of actions are performed to hide and show the relevant nodes, depending on the user choice
				$sSelector = $this->oForm->GetHierarchyPath().'/'.$this->sCode.$this->oForm->GetSuffix();
				$oSubForm->SetHierarchyParent($sSelector);
				$sPath = $this->oForm->GetHierarchyPath().'/'.$this->sCode.$this->oForm->GetSuffix().'-'.$sKey;
				$oSubForm->SetHierarchyPath($sPath);

				$oSubForm->SetDisplayed($sKey == $this->defaultValue);
				$sHtml .= $oSubForm->RenderAsPropertySheet($oP, true);
			}
			else
			{
				$sHtml .= "<div class=\"subform_{$sId} {$sId}_{$sKey}\" $sStyle>";
				$sHtml .= $oSubForm->Render($oP, true);
				$sHtml .= "</div>";
			}
		}

		if ($sRenderMode == 'property')
		{
			$sSelector = $this->oForm->GetHierarchyPath().'/'.$this->sCode.$this->oForm->GetSuffix();
			$this->aWidgetExtraParams['data_selector'] = $sSelector;
		}
		else
		{
			$oP->add_ready_script(
<<<EOF
$('#$sId').on('change reverted', function() {	$('.subform_{$sId}').hide(); $('.{$sId}_'+this.value).show(); } );
EOF
			);
		}
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
	
	public function SetDefaultValueFrom($aAllDefaultValues)
	{
		if (array_key_exists($this->GetCode(), $aAllDefaultValues))
		{
			$selectedValue = $aAllDefaultValues[$this->GetCode()];
			foreach($this->aSubForms as $iKey => $aFormData)
			{
				$sId = $this->oForm->GetFieldId($this->sCode);
				if ($selectedValue == $aFormData['value'])
				{
					$this->defaultValue =$iKey;
					$oSubForm = $aFormData['form'];
					$oSubForm->SetDefaultValues($aAllDefaultValues);
				}
			}		
		}
	}
	
	public function FindField($sFieldCode)
	{
		$oField = parent::FindField($sFieldCode);
		if ($oField === false)
		{
			// Look in the subforms
			foreach($this->aSubForms as $sKey => $aFormData)
			{
				$oSubForm = $aFormData['form'];
				$oField = $oSubForm->FindField($sFieldCode);
				if ($oField !== false)
				{
					break;
				}
			}
		}
		return $oField;
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

	/**
	 * @inheritDoc
	 */
	public function GetInputType(): ?string
	{
		return null;
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
	
	public function FindField($sFieldCode)
	{
		$oField = parent::FindField($sFieldCode);
		if ($oField === false)
		{
			// Look in the subform
			$oField = $this->oSubForm->FindField($sFieldCode);
		}
		return $oField;
	}
}



class DesignerStaticTextField extends DesignerFormField
{
	public function __construct($sCode, $sLabel = '', $defaultValue = '')
	{
		parent::__construct($sCode, $sLabel, $defaultValue);
	}

	/**
	 * @inheritDoc
	 */
	public function GetInputType(): ?string
	{
		return null;
	}

	public function Render(WebPage $oP, $sFormId, $sRenderMode='dialog')
	{
		return array('label' => $this->sLabel, 'value' => $this->defaultValue);
	}
}


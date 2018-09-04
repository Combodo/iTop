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
 * Class UIWizard
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

class UIWizard
{
	protected $m_oPage;
	protected $m_sClass;
	protected $m_sTargetState;
	protected $m_aWizardSteps;
	
	public function __construct($oPage, $sClass, $sTargetState = '')
	{
		$this->m_oPage = $oPage;
		$this->m_sClass = $sClass;
		if (empty($sTargetState))
		{
			$sTargetState = MetaModel::GetDefaultState($sClass);
		}
		$this->m_sTargetState = $sTargetState;
		$this->m_aWizardSteps = $this->ComputeWizardStructure();
	}
	
	public function GetObjectClass() { return $this->m_sClass; }
	public function GetTargetState() { return $this->m_sTargetState; }
	public function GetWizardStructure() { return $this->m_aWizardSteps; }
	
	/**
	 * Displays one step of the wizard
	 */	 
	public function DisplayWizardStep($aStep, $iStepIndex, &$iMaxInputId, &$aFieldsMap, $bFinishEnabled = false, $aArgs = array())
	{
		if ($iStepIndex == 1) // one big form that contains everything, to make sure that the uploaded files are posted too
		{
			$this->m_oPage->add("<form method=\"post\" enctype=\"multipart/form-data\" action=\"".utils::GetAbsoluteUrlAppRoot()."pages/UI.php\">\n");
		}
		$this->m_oPage->add("<div class=\"wizContainer\" id=\"wizStep$iStepIndex\" style=\"display:none;\">\n");
		$this->m_oPage->add("<a name=\"step$iStepIndex\" />\n");
		$aStates = MetaModel::EnumStates($this->m_sClass);
		$aDetails = array();
		$sJSHandlerCode = ''; // Javascript code to be executed each time this step of the wizard is entered
		foreach($aStep as $sAttCode)
		{
			$oAttDef = MetaModel::GetAttributeDef($this->m_sClass, $sAttCode);
			if ($oAttDef->IsWritable())
			{
				$sAttLabel = $oAttDef->GetLabel();
				$iOptions = isset($aStates[$this->m_sTargetState]['attribute_list'][$sAttCode]) ? $aStates[$this->m_sTargetState]['attribute_list'][$sAttCode] : 0;
		
				$aPrerequisites = $oAttDef->GetPrerequisiteAttributes();
				if ($iOptions & (OPT_ATT_MANDATORY | OPT_ATT_MUSTCHANGE | OPT_ATT_MUSTPROMPT))
				{
					$aFields[$sAttCode] = array();
					foreach($aPrerequisites as $sCode)
					{
						$aFields[$sAttCode][$sCode] = '';
					}
				}
				if (count($aPrerequisites) > 0)
				{
					$aOptions[] = 'Prerequisites: '.implode(', ', $aPrerequisites);
				}
				
				$sFieldFlag = (($iOptions & (OPT_ATT_MANDATORY | OPT_ATT_MUSTCHANGE)) || (!$oAttDef->IsNullAllowed()) )? ' <span class="hilite">*</span>' : '';
				$oDefaultValuesSet = $oAttDef->GetDefaultValue(/* $oObject->ToArgs() */); // @@@ TO DO: get the object's current value if the object exists
				$sHTMLValue = cmdbAbstractObject::GetFormElementForField($this->m_oPage, $this->m_sClass, $sAttCode, $oAttDef, $oDefaultValuesSet, '', "att_$iMaxInputId", '', $iOptions, $aArgs);
				$aFieldsMap["att_$iMaxInputId"] = $sAttCode;
				$aDetails[] = array('label' => '<span title="'.$oAttDef->GetDescription().'">'.$oAttDef->GetLabel().$sFieldFlag.'</span>', 'value' => "<span id=\"field_att_$iMaxInputId\">$sHTMLValue</span>");
				if ($oAttDef->GetValuesDef() != null)
				{
					$sJSHandlerCode .= "\toWizardHelper.RequestAllowedValues('$sAttCode');\n";
				}
				if ($oAttDef->GetDefaultValue() != null)
				{
					$sJSHandlerCode .= "\toWizardHelper.RequestDefaultValue('$sAttCode');\n";
				}
				if ($oAttDef->IsLinkSet())
				{
					$sJSHandlerCode .= "\toLinkWidgetatt_$iMaxInputId.Init();";
				}
				$iMaxInputId++;
			}
		}
		//$aDetails[] = array('label' => '', 'value' => '<input type="button" value="Next &gt;&gt;">');
		$this->m_oPage->details($aDetails);
		$sBackButtonDisabled = ($iStepIndex <= 1) ? 'disabled' : '';
		$sDisabled = $bFinishEnabled ? '' : 'disabled';
		$nbSteps = count($this->m_aWizardSteps['mandatory']) + count($this->m_aWizardSteps['optional']);
		$this->m_oPage->add("<div style=\"text-align:center\">
		<input type=\"button\" value=\"".Dict::S('UI:Button:Back')."\" $sBackButtonDisabled onClick=\"GoToStep($iStepIndex, $iStepIndex - 1)\" />
		<input type=\"button\" value=\"".Dict::S('UI:Button:Next')."\" onClick=\"GoToStep($iStepIndex, 1+$iStepIndex)\" />
		<input type=\"button\" value=\"".Dict::S('UI:Button:Finish')."\" $sDisabled onClick=\"GoToStep($iStepIndex, 1+$nbSteps)\" />
		</div>\n");
		$this->m_oPage->add_script("
function OnEnterStep{$iStepIndex}()
{
	oWizardHelper.ResetQuery();
	oWizardHelper.UpdateWizard();
	
$sJSHandlerCode

	oWizardHelper.AjaxQueryServer();
}
");
		$this->m_oPage->add("</div>\n\n");
	}	

	/**
	 * Display the final step of the wizard: a confirmation screen
	 */	 	
	public function DisplayFinalStep($iStepIndex, $aFieldsMap)
	{
		$oAppContext = new ApplicationContext();
		$this->m_oPage->add("<div class=\"wizContainer\" id=\"wizStep$iStepIndex\" style=\"display:none;\">\n");
		$this->m_oPage->add("<a name=\"step$iStepIndex\" />\n");
		$this->m_oPage->P(Dict::S('UI:Wizard:FinalStepTitle'));
		$this->m_oPage->add("<input type=\"hidden\" name=\"operation\" value=\"wizard_apply_new\" />\n");
		$this->m_oPage->add("<input type=\"hidden\" name=\"transaction_id\" value=\"".utils::GetNewTransactionId()."\" />\n");
		$this->m_oPage->add("<input type=\"hidden\" id=\"wizard_json_obj\" name=\"json_obj\" value=\"\" />\n");
		$sScript = "function OnEnterStep$iStepIndex() {\n";
		foreach($aFieldsMap as $iInputId => $sAttCode)
		{
			$sScript .= "\toWizardHelper.UpdateCurrentValue('$sAttCode');\n";		
		}
		$sScript .= "\toWizardHelper.Preview('object_preview');\n";		
		$sScript .= "\t$('#wizard_json_obj').val(oWizardHelper.ToJSON());\n";		
		$sScript .= "}\n";
		$this->m_oPage->add_script($sScript);
		$this->m_oPage->add("<div id=\"object_preview\">\n");
		$this->m_oPage->add("</div>\n");
		$this->m_oPage->add($oAppContext->GetForForm());		
		$this->m_oPage->add("<input type=\"button\" value=\"".Dict::S('UI:Button:Back')."\" onClick=\"GoToStep($iStepIndex, $iStepIndex - 1)\" />");
		$this->m_oPage->add("<input type=\"submit\" value=\"Create ".MetaModel::GetName($this->m_sClass)."\" />\n");
		$this->m_oPage->add("</div>\n");
		$this->m_oPage->add("</form>\n");
	}	
	/**
	 * Compute the order of the fields & pages in the wizard
	 * @param $oPage iTopWebPage The current page (used to display error messages) 
	 * @param $sClass string Name of the class
	 * @param $sStateCode string Code of the target state of the object
	 * @return hash Two dimensional array: each element represents the list of fields for a given page   
	 */
	protected function ComputeWizardStructure()
	{
		$aWizardSteps = array( 'mandatory' => array(), 'optional' => array());
		$aFieldsDone = array(); // Store all the fields that are already covered by a previous step of the wizard
	
		$aStates = MetaModel::EnumStates($this->m_sClass);
		$sStateAttCode = MetaModel::GetStateAttributeCode($this->m_sClass);
		
		$aMandatoryAttributes = array();
		// Some attributes are always mandatory independently of the state machine (if any)
        foreach(MetaModel::GetAttributesList($this->m_sClass) as $sAttCode)
        {
            $oAttDef = MetaModel::GetAttributeDef($this->m_sClass, $sAttCode);
            if (!$oAttDef->IsExternalField() && !$oAttDef->IsNullAllowed() && 
			    $oAttDef->IsWritable() && ($sAttCode != $sStateAttCode) )
            {
                $aMandatoryAttributes[$sAttCode] = OPT_ATT_MANDATORY;
            }
        }

        // Now check the attributes that are mandatory in the specified state
		if ( (!empty($this->m_sTargetState)) && (count($aStates[$this->m_sTargetState]['attribute_list']) > 0) )
		{
			// Check all the fields that *must* be included in the wizard for this
			// particular target state
			$aFields = array();
			foreach($aStates[$this->m_sTargetState]['attribute_list'] as $sAttCode => $iOptions)
			{
				if ( (isset($aMandatoryAttributes[$sAttCode])) && 
					 ($aMandatoryAttributes[$sAttCode] & (OPT_ATT_MANDATORY | OPT_ATT_MUSTCHANGE | OPT_ATT_MUSTPROMPT)) )
				{
					$aMandatoryAttributes[$sAttCode] |= $iOptions;
				}
				else
				{
					$aMandatoryAttributes[$sAttCode] = $iOptions;
				}
			}
		}
        
		// Check all the fields that *must* be included in the wizard
		// i.e. all mandatory, must-change or must-prompt fields that are
		// not also read-only or hidden.
		// Some fields may be required (null not allowed) from the database
		// perspective, but hidden or read-only from the user interface perspective 
		$aFields = array();
		foreach($aMandatoryAttributes as $sAttCode => $iOptions)
		{
			if ( ($iOptions & (OPT_ATT_MANDATORY | OPT_ATT_MUSTCHANGE | OPT_ATT_MUSTPROMPT)) &&
				 !($iOptions & (OPT_ATT_READONLY | OPT_ATT_HIDDEN)) )
			{
				$oAttDef = MetaModel::GetAttributeDef($this->m_sClass, $sAttCode);
				$aPrerequisites = $oAttDef->GetPrerequisiteAttributes();
				$aFields[$sAttCode] = array();
				foreach($aPrerequisites as $sCode)
				{
					$aFields[$sAttCode][$sCode] = '';
				}
			}
		}
			
		// Now use the dependencies between the fields to order them
		// Start from the order of the 'details'
		$aList = MetaModel::FlattenZlist(MetaModel::GetZListItems($this->m_sClass, 'details'));
		$index = 0;
		$aOrder = array();
		foreach($aFields as $sAttCode => $void)
		{
				$aOrder[$sAttCode] = 999; // At the end of the list...
		}
		foreach($aList as $sAttCode)
		{
			if (array_key_exists($sAttCode, $aFields))
			{
				$aOrder[$sAttCode] = $index;
			}
			$index++;
		}
		foreach($aFields as $sAttCode => $aDependencies)
		{
			// All fields with no remaining dependencies can be entered at this
			// step of the wizard
			if (count($aDependencies) > 0)
			{
				$iMaxPos = 0;
				// Remove this field from the dependencies of the other fields
				foreach($aDependencies as $sDependentAttCode => $void)
				{
					// position the current field after the ones it depends on
					$iMaxPos = max($iMaxPos, 1+$aOrder[$sDependentAttCode]);
				}
			}
		}
		asort($aOrder);
		$aCurrentStep = array();
		foreach($aOrder as $sAttCode => $rank)
		{
			$aCurrentStep[] = $sAttCode;
			$aFieldsDone[$sAttCode] = '';
		}
		$aWizardSteps['mandatory'][] = $aCurrentStep;


		// Now computes the steps to fill the optional fields
		$aFields = array(); // reset
		foreach(MetaModel::ListAttributeDefs($this->m_sClass) as $sAttCode=>$oAttDef)
		{
			$iOptions = (isset($aStates[$this->m_sTargetState]['attribute_list'][$sAttCode])) ? $aStates[$this->m_sTargetState]['attribute_list'][$sAttCode] : 0;				
			if ( ($sStateAttCode != $sAttCode) &&
				 (!$oAttDef->IsExternalField()) &&
				 (($iOptions & (OPT_ATT_HIDDEN | OPT_ATT_READONLY)) == 0) &&
				 (!isset($aFieldsDone[$sAttCode])) )
				 
			{
				// 'State', external fields, read-only and hidden fields
				// and fields that are already listed in the wizard
				// are removed from the 'optional' part of the wizard
				$oAttDef = MetaModel::GetAttributeDef($this->m_sClass, $sAttCode);
				$aPrerequisites = $oAttDef->GetPrerequisiteAttributes();
				$aFields[$sAttCode] = array();
				foreach($aPrerequisites as $sCode)
				{
					if (!isset($aFieldsDone[$sCode]))
					{
						// retain only the dependencies that were not covered
						// in the 'mandatory' part of the wizard
						$aFields[$sAttCode][$sCode] = '';
					}
				}
			}
		}
		// Now use the dependencies between the fields to order them
		while(count($aFields) > 0)
		{
			$aCurrentStep = array();
			foreach($aFields as $sAttCode => $aDependencies)
			{
				// All fields with no remaining dependencies can be entered at this
				// step of the wizard
				if (count($aDependencies) == 0)
				{
					$aCurrentStep[] = $sAttCode;
					$aFieldsDone[$sAttCode] = '';
					unset($aFields[$sAttCode]);
					// Remove this field from the dependencies of the other fields
					foreach($aFields as $sUpdatedCode => $aDummy)
					{
						// remove the dependency
						unset($aFields[$sUpdatedCode][$sAttCode]);
					}
				}
			}
			if (count($aCurrentStep) == 0)
			{
				// This step of the wizard would contain NO field !
				$this->m_oPage->add(Dict::S('UI:Error:WizardCircularReferenceInDependencies'));
				print_r($aFields);
				break;
			}
			$aWizardSteps['optional'][] = $aCurrentStep;
		}
		return $aWizardSteps;
	
	} 
}
?>

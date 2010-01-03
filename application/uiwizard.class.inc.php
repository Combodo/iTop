<?php
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
			$this->m_oPage->add("<form method=\"post\" enctype=\"multipart/form-data\" action=\"../pages/UI.php\">\n");
		}
		$this->m_oPage->add("<div class=\"wizContainer\" id=\"wizStep$iStepIndex\" style=\"display:none;\">\n");
		$this->m_oPage->add("<a name=\"step$iStepIndex\" />\n");
		$aStates = MetaModel::EnumStates($this->m_sClass);
		$aDetails = array();
		$sJSHandlerCode = ''; // Javascript code to be executed each time this step of the wizard is entered
		foreach($aStep as $sAttCode)
		{
			if ($sAttCode != 'finalclass') // Do not display the attribute that stores the actual class name
			{
				$oAttDef = MetaModel::GetAttributeDef($this->m_sClass, $sAttCode);
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
				
				$sFieldFlag = ($iOptions & (OPT_ATT_MANDATORY | OPT_ATT_MUSTCHANGE)) ? ' <span class="hilite">*</span>' : '';
				$oDefaultValuesSet = $oAttDef->GetDefaultValue(); // @@@ TO DO: get the object's current value if the object exists
				$sHTMLValue = cmdbAbstractObject::GetFormElementForField($this->m_oPage, $this->m_sClass, $sAttCode, $oAttDef, $oDefaultValuesSet, '', "att_$iMaxInputId", '', $iOptions, $aArgs);
				$aFieldsMap[$iMaxInputId] = $sAttCode;
				$aDetails[] = array('label' => $oAttDef->GetLabel().$sFieldFlag, 'value' => "<div id=\"field_$iMaxInputId\">$sHTMLValue</div>");
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
		<input type=\"button\" value=\"&lt;&lt; Back \" $sBackButtonDisabled onClick=\"GoToStep($iStepIndex, $iStepIndex - 1)\" />
		<input type=\"button\" value=\" Next &gt;&gt;\" onClick=\"GoToStep($iStepIndex, 1+$iStepIndex)\" />
		<input type=\"button\" value=\" Finish \" $sDisabled onClick=\"GoToStep($iStepIndex, 1+$nbSteps)\" />
		</div>\n");
		$this->m_oPage->add("
<script type=\"text/javascript\">
function OnEnterStep{$iStepIndex}()
{
	oWizardHelper.ResetQuery();
	oWizardHelper.UpdateWizard();
	
$sJSHandlerCode

	oWizardHelper.AjaxQueryServer();
}
</script>\n");
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
		$this->m_oPage->P("Final step: confirmation");
		$this->m_oPage->add("<input type=\"hidden\" name=\"operation\" value=\"wizard_apply_new\" />\n");
		$this->m_oPage->add("<input type=\"hidden\" name=\"transaction_id\" value=\"".utils::GetNewTransactionId()."\" />\n");
		$this->m_oPage->add("<input type=\"hidden\" id=\"wizard_json_obj\" name=\"json_obj\" value=\"\" />\n");
		$this->m_oPage->add("<script type=\"text/javascript\">\n");
		$this->m_oPage->add("function OnEnterStep$iStepIndex() {\n");
		foreach($aFieldsMap as $iInputId => $sAttCode)
		{
			$this->m_oPage->add("\toWizardHelper.UpdateCurrentValue('$sAttCode');\n");		
		}
		$this->m_oPage->add("\toWizardHelper.Preview('object_preview');\n");		
		$this->m_oPage->add("\t$('#wizard_json_obj').val(oWizardHelper.ToJSON());\n");		
		$this->m_oPage->add("}\n");
		$this->m_oPage->add("</script>\n");
		$this->m_oPage->add("<div id=\"object_preview\">\n");
		$this->m_oPage->add("</div>\n");
		$this->m_oPage->add($oAppContext->GetForForm());		
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
		
		if ( (!empty($this->m_sTargetState)) && (count($aStates[$this->m_sTargetState]['attribute_list']) > 0) )
		{
			// Check all the fields that *must* be included in the wizard for this
			// particular target state
			$aFields = array();
			foreach($aStates[$this->m_sTargetState]['attribute_list'] as $sAttCode => $iOptions)
			{
				$oAttDef = MetaModel::GetAttributeDef($this->m_sClass, $sAttCode);
				$sAttLabel = $oAttDef->GetLabel();
		
				if ($iOptions & (OPT_ATT_MANDATORY | OPT_ATT_MUSTCHANGE | OPT_ATT_MUSTPROMPT))
				{
					$aPrerequisites = $oAttDef->GetPrerequisiteAttributes();
					$aFields[$sAttCode] = array();
					foreach($aPrerequisites as $sCode)
					{
						$aFields[$sAttCode][$sCode] = '';
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
					echo "<strong>Error:</strong> Circular reference in the dependencies between the fields.";
					print_r($aFields);
					break;
				}
				$aWizardSteps['mandatory'][] = $aCurrentStep;
			}
		}

		// Now computes the steps to fill the optional fields
		$sStateAttCode = MetaModel::GetStateAttributeCode($this->m_sClass);
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
				$oPage->add("<strong>Error:</strong> Circular reference in the dependencies between the fields.");
				print_r($aFields);
				break;
			}
			$aWizardSteps['optional'][] = $aCurrentStep;
		}
		
		return $aWizardSteps;
	
	} 
}
?>

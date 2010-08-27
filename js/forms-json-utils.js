// ID of the (hidden) form field used to store the JSON representation of the
// object being edited in this page
var sJsonFieldId = 'json_object';

// The memory representation of the object
var oObj = {};

// Mapping between the fields of the form and the attribute of the current object
// If aFieldsMap[2] contains 'foo' it means that oObj.foo corresponds to the field
// of Id 'att_2' in the form 
var aFieldsMap = new Array;

// Update the whole object from the form and also update its
// JSON (serialized) representation in the (hidden) field
function UpdateObjectFromForm(aFieldsMap, oObj)
{
	for(i=0; i<aFieldsMap.length; i++)
	{
		var oElement = document.getElementById('att_'+i);
		var sFieldName = aFieldsMap[i];
		oObj['m_aCurrValues'][sFieldName] = oElement.value;
		sJSON = JSON.stringify(oObj);
		var oJSON = document.getElementById(sJsonFieldId);
		oJSON.value = sJSON;
	}
	return oObj;
}

// Update the specified field from the current object
function UpdateFieldFromObject(idField, aFieldsMap, oObj)
{
	var oElement = document.getElementById('att_'+idField);
	oElement.value = oObj['m_aCurrValues'][aFieldsMap[idField]];
}
// Update all the fields of the Form from the current object
function UpdateFormFromObject(aFieldsMap, oObj)
{
	for(i=0; i<aFieldsMap.length; i++)
	{
		UpdateFieldFromForm(i, aFieldsMap, oObj);
	}
}

// This function is meant to be called from the AJAX page
// It reloads the object (oObj) from the JSON representation
// and also updates the form field that contains the JSON
// representation of the object
function ReloadObjectFromServer(sJSON)
{
	//console.log('JSON value:', sJSON);
	var oJSON = document.getElementById(sJsonFieldId);
	oJSON.value = sJSON;
	oObj = JSON.parse( '(' + sJSON + ')' );
	return oObj;	
}

function GoToStep(iCurrentStep, iNextStep)
{
	var oCurrentStep = document.getElementById('wizStep'+iCurrentStep);
	if (iNextStep > iCurrentStep)
	{
		// Check the values when moving forward
		if (CheckFields('wizStep'+iCurrentStep, true))
		{
			oCurrentStep.style.display = 'none';
			ActivateStep(iNextStep);
		}
	}
	else
	{
		oCurrentStep.style.display = 'none';
		ActivateStep(iNextStep);
	}
}

function ActivateStep(iTargetStep)
{
	UpdateObjectFromForm(aFieldsMap, oObj);
	var oNextStep = document.getElementById('wizStep'+(iTargetStep));
	window.location.href='#step'+iTargetStep;
	// If a handler for entering this step exists, call it
	if (typeof(this['OnEnterStep'+iTargetStep]) == 'function')
	{
		eval( 'OnEnterStep'+iTargetStep+'();');
	}
	oNextStep.style.display = '';
	G_iCurrentStep = iTargetStep;
	//$('#wizStep'+(iTargetStep)).block({ message: null });
}


//function AjaxGetValuesDef(oObj, sClass, sAttCode, iFieldId)
//{
//	var oJSON = document.getElementById(sJsonFieldId);
//	$.get('ajax.render.php?class=' + sClass + '&json_obj=' + oJSON.value + '&att_code=' + sAttCode,
//	   { operation: "allowed_values" },
//	   function(data){
//		 //$('#field_'+iFieldId).html(data);
//		}
//	);
//}
//
//function AjaxGetDefaultValue(oObj, sClass, sAttCode, iFieldId)
//{
//	// Asynchronously call the server to provide a default value if the field is
//	// empty
//	if (oObj['m_aCurrValues'][sAttCode] == '')
//	{
//		var oJSON = document.getElementById(sJsonFieldId);
//		$.get('ajax.render.php?class=' + sClass + '&json_obj=' + oJSON.value + '&att_code=' + sAttCode,
//		   { operation: "default_value" },
//		   function(json_data){
//			 var oObj = ReloadObjectFromServer(json_data);
//			 UpdateFieldFromObject(iFieldId, aFieldsMap, oObj)
//			}
//		);
//	}
//}

// Store the result of the form validation... there may be several forms per page, beware
var oFormErrors = { err_form0: 0 };

function CheckFields(sFormId, bDisplayAlert)
{
	$('#'+sFormId+' :submit').attr('disable', 'disabled');
	$('#'+sFormId+' :button[type=submit]').attr('disable', 'disabled');
	firstErrorId = '';
	
	// The two 'fields' below will be updated when the 'validate' event is processed
	oFormErrors['err_'+sFormId] = 0;		// Number of errors encountered when validating the form
	oFormErrors['input_'+sFormId] = null;	// First 'input' with an error, to set the focus to it
	$('#'+sFormId+' :input').each( function()
	{
		validateEventResult = $(this).trigger('validate', sFormId);
	}
	);
	if(oFormErrors['err_'+sFormId] > 0)
	{
		if (bDisplayAlert)
		{
			alert('Please fill-in all mandatory fields before continuing.');
		}
		$('#'+sFormId+' :submit').attr('disable', '');
		$('#'+sFormId+' :button[type=submit]').attr('disable', '');
		if (oFormErrors['input_'+sFormId] != null)
		{
			$('#'+oFormErrors['input_'+sFormId]).focus();
		}
	}
	return (oFormErrors['err_'+sFormId] == 0); // If no error, submit the form
}

function ValidateField(sFieldId, sPattern, bMandatory, sFormId)
{
	var bValid = true;
	var currentVal = $('#'+sFieldId).val();
	if (bMandatory && ((currentVal == '') || (currentVal == 0) || (currentVal == '[]')))
	{
		bValid = false;
	}
	else if ((currentVal == '') || (currentVal == 0) || (currentVal == '[]'))
	{
		// An empty field is Ok...
		bValid = true;
	}
	else if (sPattern != '')
	{
		re = new RegExp(sPattern);
		//console.log('Validating field: '+sFieldId + ' current value: '+currentVal + ' pattern: '+sPattern );
		bValid = re.test(currentVal);
	}
	if (bValid)
	{
		// Visual feedback - none when it's Ok
		$('#v_'+sFieldId).html(''); //<img src="../images/validation_ok.png" />');
	}
	else
	{
		// Report the error...
		oFormErrors['err_'+sFormId]++;
		if (oFormErrors['input_'+sFormId] == null)
		{
			// Let's remember the first input with an error, so that we can put back the focus on it later
			oFormErrors['input_'+sFormId] = sFieldId;
		}
		// Visual feedback
		$('#v_'+sFieldId).html('<img src="../images/validation_error.png" />');
	}
	//console.log('Form: '+sFormId+' Validating field: '+sFieldId + ' current value: '+currentVal+' pattern: '+sPattern+' result: '+bValid );
	return true; // Do not stop propagation ??
}

function UpdateDependentFields(aFieldNames)
{
	//console.log('UpdateDependentFields:');
	//console.log(aFieldNames);
	index = 0;
	oWizardHelper.ResetQuery();
	oWizardHelper.UpdateWizard();
	while(index < aFieldNames.length )
	{
		sAttCode = aFieldNames[index];
		sFieldId = oWizardHelper.GetFieldId(sAttCode);
		$('#v_'+sFieldId).html('<img src="../images/indicator.gif" />');
		oWizardHelper.RequestAllowedValues(sAttCode);
		index++;
	}
	oWizardHelper.AjaxQueryServer();
}

function ResetPwd(id)
{
	// Reset the values of the password fields
	$('#'+id).val('*****');
	$('#'+id+'_confirm').val('*****');
	// And reset the flag, to tell it that the password remains unchanged
	$('#'+id+'_changed').val(0);
	// Visual feedback, None when it's Ok
	$('#v_'+id).html('');
}

// Called whenever the content of a one way encrypted password changes
function PasswordFieldChanged(id)
{
	// Set the flag, to tell that the password changed
	console.log('Password changed');
	$('#'+id+'_changed').val(1);
}

// Special validation function for one way encrypted password fields
function ValidatePasswordField(id, sFormId)
{
	var bChanged = $('#'+id+'_changed').val();
	if (bChanged)
	{
		if ($('#'+id).val() != $('#'+id+'_confirm').val())
		{
			oFormErrors['err_'+sFormId]++;
			if (oFormErrors['input_'+sFormId] == null)
			{
				// Let's remember the first input with an error, so that we can put back the focus on it later
				oFormErrors['input_'+sFormId] = id;
			}
			// Visual feedback
			$('#v_'+id).html('<img src="../images/validation_error.png" />');
			return false;
		}
	}
	$('#v_'+id).html(''); //<img src="../images/validation_ok.png" />');
	return true;
}
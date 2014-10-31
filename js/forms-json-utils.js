// ID of the (hidden) form field used to store the JSON representation of the
// object being edited in this page
var sJsonFieldId = 'json_object';

// The memory representation of the object
var oObj = {};

// Mapping between the fields of the form and the attribute of the current object
// If aFieldsMap[2] contains 'foo' it means that oObj.foo corresponds to the field
// of Id 'att_2' in the form 
var aFieldsMap = new Array;

window.bInSubmit = false; // For handling form cancellation via OnBeforeUnload events

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

function OnUnload(sTransactionId)
{
	if (!window.bInSubmit)
	{
		// If it's not a submit, then it's a "cancel" (Pressing the Cancel button, closing the window, using the back button...)
		$.post(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php', {operation: 'on_form_cancel', transaction_id: sTransactionId }, function()
		{
			// Do nothing for now...
		});
	}
}

function OnSubmit(sFormId)
{
	window.bInSubmit=true; // This is a submit, make sure that when the page gets unloaded we don't cancel the action
	var bResult = CheckFields(sFormId, true);
	if (!bResult)
	{
		window.bInSubmit = false; // Submit is/will be canceled
	}
	return bResult;
}

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
			alert(Dict.S('UI:FillAllMandatoryFields'));
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

function ReportFieldValidationStatus(sFieldId, sFormId, bValid, sExplain)
{
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
		$('#v_'+sFieldId).html('<img src="../images/validation_error.png" style="vertical-align:middle" data-tooltip="'+sExplain+'"/>');
		$('#v_'+sFieldId).tooltip({
			items: 'span',
			tooltipClass: 'form_field_error',
			content: function() {
				return $(this).find('img').attr('data-tooltip'); // As opposed to the default 'content' handler, do not escape the contents of 'title'
			}
		});
	}
}

function ValidateField(sFieldId, sPattern, bMandatory, sFormId, nullValue, originalValue)
{
	var bValid = true;
	var sExplain = '';
	if ($('#'+sFieldId).attr('disabled'))
	{
		bValid = true; // disabled fields are not checked
	}
	else
	{
		var currentVal = $('#'+sFieldId).val();

		if (currentVal == '$$NULL$$') // Convention to indicate a non-valid value since it may have to be passed as text
		{
			bValid = false;
		}
		else if (bMandatory && (currentVal == nullValue))
		{
			bValid = false;
			sExplain = Dict.S('UI:ValueMustBeSet');
		}
		else if ((originalValue != undefined) && (currentVal == originalValue))
		{
			bValid = false;
			if (originalValue == nullValue)
			{
				sExplain = Dict.S('UI:ValueMustBeSet');
			}
			else
			{
				sExplain = Dict.S('UI:ValueMustBeChanged');
			}
		}
		else if (currentVal == nullValue)
		{
			// An empty field is Ok...
			bValid = true;
		}
		else if (sPattern != '')
		{
			re = new RegExp(sPattern);
			//console.log('Validating field: '+sFieldId + ' current value: '+currentVal + ' pattern: '+sPattern );
			bValid = re.test(currentVal);
			sExplain = Dict.S('UI:ValueInvalidFormat');
		}
	}
	ReportFieldValidationStatus(sFieldId, sFormId, bValid, sExplain);
	//console.log('Form: '+sFormId+' Validating field: '+sFieldId + ' current value: '+currentVal+' pattern: '+sPattern+' result: '+bValid );
	return true; // Do not stop propagation ??
}

function ValidateCKEditField(sFieldId, sPattern, bMandatory, sFormId, nullValue)
{
	var bValid;
	var sTextContent;

	// Get the contents without the tags
	var oFormattedContents = $("#cke_"+sFieldId+" iframe");
	if (oFormattedContents.length == 0)
	{
		var oSourceContents = $("#cke_"+sFieldId+" textarea.cke_source");
		sTextContent = oSourceContents.val();
	}
	else
	{
		sTextContent = oFormattedContents.contents().find("body").text();
	}

	if (bMandatory && (sTextContent == ''))
	{
		bValid = false;
	}
	else
	{
		bValid = true;
	}

	ReportFieldValidationStatus(sFieldId, sFormId, bValid, '');

	setTimeout(function(){ValidateCKEditField(sFieldId, sPattern, bMandatory, sFormId, nullValue);}, 500);
}

/*
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
*/

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
			$('#v_'+id).html('<img src="../images/validation_error.png"  style="vertical-align:middle"/>');
			return false;
		}
	}
	$('#v_'+id).html(''); //<img src="../images/validation_ok.png" />');
	return true;
}

//Special validation function for case log fields, taking into account the history
// to determine if the field is empty or not
function ValidateCaseLogField(sFieldId, bMandatory, sFormId)
{
	bValid = true;
	
	if ($('#'+sFieldId).attr('disabled'))
	{
		bValid = true; // disabled fields are not checked
	}
	else if (!bMandatory)
	{
		bValid = true;
	}
	else
	{
		if (bMandatory)
		{
			var count = $('#'+sFieldId+'_count').val();
			if ( (count == 0) && ($('#'+sFieldId).val() == '') )
			{
				// No previous entry and no content typed
				bValid = false;
			}
		}
	}
	ReportFieldValidationStatus(sFieldId, sFormId, bValid, '');
	return bValid;
}
// Manage a 'duration' field
function UpdateDuration(iId)
{
	var iDays = parseInt($('#'+iId+'_d').val(), 10);
	var iHours = parseInt($('#'+iId+'_h').val(), 10);
	var iMinutes = parseInt($('#'+iId+'_m').val(), 10);
	var iSeconds = parseInt($('#'+iId+'_s').val(), 10);
	
	var iDuration = (((iDays*24)+ iHours)*60+ iMinutes)*60 + iSeconds;
	$('#'+iId).val(iDuration);
	$('#'+iId).trigger('change');
	return true;
}

// Called when filling an autocomplete field
function OnAutoComplete(id, event, data, formatted)
{
	if (data)
	{
		// A valid match was found: data[0] => label, data[1] => value
		if (data[1] != $('#'+id).val())
		{
			$('#'+id).val(data[1]);
			$('#'+id).trigger('change');
			$('#'+id).trigger('extkeychange');
		}
	}
	else
	{
		if ($('#label_'+id).val() == '')
		{
			$('#'+id).val(''); // Empty value
		}
		else
		{
			$('#'+id).val('$$NULL$$'); // Convention: not a valid value
		}
		$('#'+id).trigger('change');
	}
}

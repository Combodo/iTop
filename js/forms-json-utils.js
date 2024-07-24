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

function OnUnload(sTransactionId, sObjClass, iObjKey, sToken)
{
	if (!window.bInSubmit)
	{
		// If it's not a submit, then it's a "cancel" (Pressing the Cancel button, closing the window, using the back button...)
		var sUrl = GetAbsoluteUrlAppRoot()+'pages/ajax.render.php';
		var oFormData = new FormData();
		oFormData.append('operation', 'on_form_cancel');
		oFormData.append('transaction_id', sTransactionId);
		oFormData.append('obj_class', sObjClass);
		oFormData.append('obj_key', iObjKey);
		oFormData.append('token', sToken);
		navigator.sendBeacon(sUrl, oFormData);
	}
}

function OnSubmit(sFormId)
{
	if($('#'+sFormId).attr('data-form-state') === 'onsubmit')
	{
		return false;
	}
	
	$('#'+sFormId).attr('data-form-state','onsubmit');

	window.bInSubmit=true; // This is a submit, make sure that when the page gets unloaded we don't cancel the action

	if ($('#'+sFormId).data('force_submit')) {
		return true;
	}

	var bResult = CheckFields(sFormId, true);
	if (!bResult)
	{
		window.bInSubmit = false; // Submit is/will be canceled
		$('#'+sFormId).attr('data-form-state', 'default');
	}
	return bResult;
}

// Store the result of the form validation... there may be several forms per page, beware
var oFormErrors = { err_form0: 0 };

function CheckFields(sFormId, bDisplayAlert)
{
// if some fields are in wait, no submit is allowed
	if ($('#'+sFormId+' .blockMsg').length>0)
	{
		CombodoModal.OpenWarningModal(Dict.S('UI:Button:Wait'));
		return false;
	}

	$('#'+sFormId+' :submit').prop('disable', true);
	$('#'+sFormId+' :button[type=submit]').prop('disable', true);
	firstErrorId = '';
	
	// The two 'fields' below will be updated when the 'validate' event is processed
	oFormErrors['err_'+sFormId] = 0;		// Number of errors encountered when validating the form
	oFormErrors['input_'+sFormId] = null;	// First 'input' with an error, to set the focus to it
	$('#'+sFormId+' :input').each( function()
	{
		// this is synchronous !
		// each field should register this event to launch ValidateField() if needed
		validateEventResult = $(this).trigger('validate', sFormId);
	}
	);
	if(oFormErrors['err_'+sFormId] > 0)
	{
		if (bDisplayAlert)
		{
			activateFirstTabWithError(sFormId);
			CombodoModal.OpenErrorModal(Dict.S('UI:FillAllMandatoryFields'));
		}
		$('#'+sFormId+' :submit').prop('disable', false);
		$('#'+sFormId+' :button[type=submit]').prop('disable', false);
		if (oFormErrors['input_'+sFormId] != null) {
			$('#'+oFormErrors['input_'+sFormId]).focus();
		}
	}

	return (oFormErrors['err_'+sFormId] == 0); // If no error, submit the form
}

function activateFirstTabWithError(sFormId) {
	var $form = $("#"+sFormId),
		$tabsContainer = $form.find(".ui-widget.ui-widget-content"),
		$tabs = $tabsContainer.find(".ui-tabs-panel");

	$tabs.each(function (index, element) {
		var $fieldsWithError = $(element).find(".form_validation");
		if ($fieldsWithError.length > 0 && ($tabsContainer.tabs("instance") !== undefined))
		{
			$tabsContainer.tabs("option", "active", index);
			return false;
		}
	});
}

function ReportFieldValidationStatus(sFieldId, sFormId, bValid, sExplain)
{
	if (bValid)
	{
		// Visual feedback - none when it's Ok
		$('#field_'+sFieldId+' .ibo-input-wrapper').removeClass('is-error')
		$('#v_'+sFieldId).html('');
		$('#'+sFieldId+'[data-validate*="dependencies"]').trigger('change.dependencies').removeAttr('data-validate');
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

		if($('#field_'+sFieldId+' .ibo-input-wrapper').attr('data-validation') === 'untouched') {
			$('#field_'+sFieldId+' .ibo-input-wrapper').removeAttr('data-validation');
		}
		else{
			$('#field_'+sFieldId+' .ibo-input-wrapper').addClass('is-error');
		}
		
		if ($('#v_'+sFieldId).text() == '')
		{
			$('#v_'+sFieldId).html(sExplain);
		}
	}
}

/**
 * To be launched on each field from normal event (click, change, ...) and 'validate' event for form submission.
 * Calls ReportFieldValidationStatus() to update global vars containing fields status
 * @param sFieldId
 * @param sPattern
 * @param bMandatory
 * @param sFormId
 * @param nullValue
 * @param originalValue
 * @returns {boolean}
 * @constructor
 */
function ValidateField(sFieldId, sPattern, bMandatory, sFormId, nullValue, originalValue)
{
	var bValid = true;
	var sExplain = '';
	if ($('#'+sFieldId).prop('disabled'))
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

function ValidateCKEditField(sFieldId, sPattern, bMandatory, sFormId, nullValue, originalValue)
{
	let oField = $('#'+sFieldId);
	if (oField.length === 0) {
		return false;
	}

	let oCKEditor = CombodoCKEditorHandler.GetInstanceSynchronous('#'+sFieldId);

	var bValid;
	var sExplain = '';
	if (oField.prop('disabled')) {
		bValid = true; // disabled fields are not checked
	} else {
		// If the CKEditor is not yet loaded, we need to wait for it to be ready
		// but as we need this function to be synchronous, we need to call it again when the CKEditor is ready
		if (oCKEditor === undefined){
			CombodoCKEditorHandler.GetInstance('#'+sFieldId).then((oCKEditor) => {
				ValidateCKEditField(sFieldId, sPattern, bMandatory, sFormId, nullValue, originalValue);
			});
			return;
		}
		let sTextContent;
		let sFormattedContent = oCKEditor.getData();

		// Get the contents without the tags
		// Check if we have a formatted content that is HTML, otherwise we just have plain text, and we can use it directly
		sTextContent = $(sFormattedContent).length > 0 ? $(sFormattedContent).text() : sFormattedContent;

		if (sTextContent === '') {
			// No plain text, maybe there is just an image
			let oImg = $(sFormattedContent).find("img");
			if (oImg.length !== 0) {
				sTextContent = 'image';
			}
		}

		// Get the original value without the tags
		let oFormattedOriginalContents = (originalValue !== undefined) ? $('<div></div>').html(originalValue) : undefined;
		let sTextOriginalContents = (oFormattedOriginalContents !== undefined) ? oFormattedOriginalContents.text() : undefined;

		if (bMandatory && (sTextContent === nullValue)) {
			bValid = false;
			sExplain = Dict.S('UI:ValueMustBeSet');
		} else if ((sTextOriginalContents !== undefined) && (sTextContent === sTextOriginalContents)) {
			bValid = false;
			if (sTextOriginalContents === nullValue) {
				sExplain = Dict.S('UI:ValueMustBeSet');
			} else {
				// Note: value change check is not working well yet as the HTML to Text conversion is not exactly the same when done from the PHP value or the CKEditor value.
				sExplain = Dict.S('UI:ValueMustBeChanged');
			}
		} else {
			bValid = true;
		}
		
		// Put and event to check the field when the content changes, remove the event right after as we'll call this same function again, and we don't want to call the event more than once (especially not ^2 times on each call)
		oCKEditor.model.document.once('change:data', (event) => {
			ValidateCKEditField(sFieldId, sPattern, bMandatory, sFormId, nullValue, originalValue);
		});
	}
	
	ReportFieldValidationStatus(sFieldId, sFormId, bValid, sExplain);
	return bValid;
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
			$('#v_'+id).html(Dict.S('UI:Component:Input:Password:DoesNotMatch'));
			$('#field_'+id +' .ibo-input-wrapper').addClass('is-error');

			return false;
		}
	}
	$('#v_'+id).html('');
	$('#field_'+id +' .ibo-input-wrapper').removeClass('is-error');
	return true;
}

//Special validation function for case log fields, taking into account the history
// to determine if the field is empty or not
function ValidateCaseLogField(sFieldId, bMandatory, sFormId, nullValue, originalValue)
{
	var bValid = true;
	var sExplain = '';
	var sTextContent;
	
	if ($('#'+sFieldId).prop('disabled'))
	{
		bValid = true; // disabled fields are not checked
	}
	else
	{
		// Get the contents (with tags)
		// Note: For CaseLog we can't retrieve the formatted contents from CKEditor (unlike in ValidateCKEditorField() method) because of the place holder.
		sTextContent = $('#' + sFieldId).val();
		var count = $('#'+sFieldId+'_count').val();

		if (bMandatory && (count == 0) && (sTextContent == nullValue))
		{
			// No previous entry and no content typed
			bValid = false;
			sExplain = Dict.S('UI:ValueMustBeSet');
		}
		else if ((originalValue != undefined) && (sTextContent == originalValue))
		{
			bValid = false;
			sExplain = Dict.S('UI:ValueMustBeChanged');
		}
	}
	ReportFieldValidationStatus(sFieldId, sFormId, bValid, '' /* sExplain */);

	// We need to check periodically as CKEditor doesn't trigger our events. More details in UIHTMLEditorWidget::Display() @ line 92
	setTimeout(function(){ValidateCaseLogField(sFieldId, bMandatory, sFormId, nullValue, originalValue);}, 500);
}

// Validate the inputs depending on the current setting
function ValidateRedundancySettings(sFieldId, sFormId)
{
	var bValid = true;
	var sExplain = '';

	$('#'+sFieldId+' :input[type="radio"]:checked').parent().find(':input[type="string"]').each(function (){
		var sValue = $(this).val().trim();
		if (sValue == '')
		{
			bValid = false;
			sExplain = Dict.S('UI:ValueMustBeSet');
		}
		else
		{
			// There is something... check if it is a number
			re = new RegExp('^[0-9]+$');
			bValid = re.test(sValue);
			if (bValid)
			{
				var iValue = parseInt(sValue , 10);
				if ($(this).hasClass('redundancy-min-up-percent'))
				{
					// A percentage
					if ((iValue < 0) || (iValue > 100))
					{
						bValid = false;
					}
				}
				else if ($(this).hasClass('redundancy-min-up-count'))
				{
					// A count
					if (iValue < 0)
					{
						bValid = false;
					}
				}

			}
			if (!bValid)
			{
				sExplain = Dict.S('UI:ValueInvalidFormat');
			}
		}
	});

	ReportFieldValidationStatus(sFieldId, sFormId, bValid, sExplain);
	return bValid;
}

//Special validation function for custom fields
function ValidateCustomFields(sFieldId, sFormId)
{
	var oFieldSet = $('#'+sFieldId+'_console_form').console_form_handler('option', 'field_set');
    bValid = oFieldSet.triggerHandler('validate');
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
//deprecated in 2.8
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

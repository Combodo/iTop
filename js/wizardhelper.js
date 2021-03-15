/*
 * Copyright (C) 2010-2021 Combodo SARL
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */

/**
 * Helper to handle dependant fields to be refreshed when parent field is updated.
 * The JS WizardHelper class has a WizardHelper PHP counterpart.
 *
 * How to use it :
 *
 * 1) Initialize by calling :
 * <pre><code>
 *     var oWizardHelper = new WizardHelper('Team', '', '');
 *     oWizardHelper.SetFieldsMap({"name":"2_name","status":"2_status","org_id":"2_org_id","email":"2_email","phone":"2_phone","notify":"2_notify","function":"2_function","id":"_id","persons_list":"2_persons_list","tickets_list":"2_tickets_list","cis_list":"2_cis_list"});
		oWizardHelper.SetFieldsCount(11);
 * </code></pre>
 *
 * 2) On field update launch the UpdateField method, passing the fields to update. This list is retrieved using
 * \MetaModel::GetDependentAttributes.
 * For now this is launched by an handler on the 'change.dependencies' event, and this event is fired by each field (see
 * \cmdbAbstractObject::GetFormElementForField and $aEventsList var)
 * <pre><code>
 *   $('#2_name')
 *      .off('change.dependencies')
 *      .on('change.dependencies', function(evt, sFormId) {
 *          return oWizardHelper.UpdateDependentFields(['friendlyname']);
 *      }
 *   );
 * </code></pre>
 *
 * 3) The WizardHelper JS object will send an XHR query to ajax.render.php with operation=wizard_helper
 * A new WizardHelper PHP object will be initialized with \WizardHelper::FromJSON
 * This will send back to the browser fields updates, by returning JS code that will :
 *
 *   * update JS WizardHelper m_oData attribute
 *   * launch JS WizardHelper UpdateFields() method
 *
 * @param sClass
 * @param sFormPrefix
 * @param sState
 * @param sInitialState
 * @param sStimulus
 * @constructor
 */
function WizardHelper(sClass, sFormPrefix, sState, sInitialState, sStimulus) {
	this.m_oData = {
		'm_sClass': '',
		'm_oFieldsMap': {},
		'm_oCurrentValues': {},
		'm_aDefaultValueRequested': [],
		'm_aAllowedValuesRequested': [],
		'm_oDefaultValue': {},
		'm_oAllowedValues': {},
		'm_iFieldsCount': 0,
		'm_sFormPrefix': sFormPrefix,
		'm_sState': sState,
		'm_bReturnNotEditableFields': false, // if true then will return values and not editable fields
		'm_sWizHelperJsVarName': null // if set will use this name when server returns JS code in \WizardHelper::GetJsForUpdateFields
	};
	this.m_oData.m_sClass = sClass;

	// Setting optional transition data
	if (sInitialState !== undefined)
	{
		this.m_oData.m_sInitialState = sInitialState;
	}
	if (sStimulus !== undefined)
	{
		this.m_oData.m_sStimulus = sStimulus;
	}

	// Methods
	this.SetFieldsMap = function (oFieldsMap) {
		this.m_oData.m_oFieldsMap = oFieldsMap;
	};

	this.SetFieldsCount = function (count) {
		this.m_oData.m_iFieldsCount = count;
	};

	this.GetFieldId = function (sFieldName) {
		id = this.m_oData.m_oFieldsMap[sFieldName];
		return id;
	};

	this.RequestDefaultValue = function (sFieldName) {
		currentValue = this.UpdateCurrentValue(sFieldName);
		if (currentValue == null)
		{
			this.m_oData.m_aDefaultValueRequested.push(sFieldName);
		}
	};

	this.RequestAllowedValues = function (sFieldName) {
		this.m_oData.m_aAllowedValuesRequested.push(sFieldName);
	};

	this.SetCurrentValue = function (sFieldName, currentValue) {
		this.m_oData.m_oCurrentValues[sFieldName] = currentValue;
	};

	this.SetReturnNotEditableFields = function (bReturnNotEditableFields) {
		this.m_oData.m_bReturnNotEditableFields = bReturnNotEditableFields;
	};

	this.SetWizHelperJsVarName = function (sWizHelperJsVarName) {
		this.m_oData.m_sWizHelperJsVarName = sWizHelperJsVarName;
	};

	this.ToJSON = function () {
		return JSON.stringify(this.m_oData);
	};

	this.FromJSON = function (sJSON) {
		//console.log('Parsing JSON:'+sJSON);
		this.m_oData = JSON.parse(sJSON);
	};

	this.ResetQuery = function () {
		this.m_oData.m_aDefaultValueRequested = [];
		this.m_oData.m_oDefaultValue = {};
		this.m_oData.m_aAllowedValuesRequested = [];
		this.m_oData.m_oAllowedValues = {};
	};

	this.UpdateFields = function () {
		var aRefreshed = [];
		//console.log('** UpdateFields **');
		// Set the full HTML for the input field
		for (i = 0; i < this.m_oData.m_aAllowedValuesRequested.length; i++)
		{
			var sAttCode = this.m_oData.m_aAllowedValuesRequested[i];
			var sFieldId = this.m_oData.m_oFieldsMap[sAttCode];
			var bDisabled = $('#'+sFieldId).prop('disabled');
			//console.log('Setting #field_'+sFieldId+' to: '+this.m_oData.m_oAllowedValues[sAttCode]);
			$('#field_'+sFieldId).html(this.m_oData.m_oAllowedValues[sAttCode]);
			if (bDisabled)
			{
				$('#'+sFieldId).prop('disabled', true);
				//$('#'+sFieldId).trigger('update'); // Propagate the disable
			}
			aRefreshed.push(sFieldId);
		}
		// Set the actual value of the input
		for(i=0; i<this.m_oData.m_aDefaultValueRequested.length; i++)
		{
			sAttCode = this.m_oData.m_aDefaultValueRequested[i];
			defaultValue = this.m_oData.m_oDefaultValue[sAttCode];
			sFieldId = this.m_oData.m_oFieldsMap[sAttCode];
			$('#'+sFieldId).val(defaultValue);
			if (!aRefreshed.indexOf(sFieldId))
			{
				aRefreshed.push(sFieldId);
			}
		}
		// For each "refreshed" field, asynchronously trigger a change in case there are dependent fields to update
		for (i = 0; i < aRefreshed.length; i++)
		{
			var sString = "$('#"+aRefreshed[i]+"').trigger('change').trigger('update');";
			window.setTimeout(sString, 1); // Synchronous 'trigger' does nothing, call it asynchronously
		}
	};

	this.UpdateWizard = function () {
		//console.log('** UpdateWizard **')
		for (let sFieldCode in this.m_oData.m_oFieldsMap)
		{
			let sCleanFieldCode = sFieldCode.replace('"', '');
			//console.log(sFieldCode);
			this.UpdateCurrentValue(sCleanFieldCode);
		}
	};

	this.UpdateWizardToJSON = function () {
		this.UpdateWizard();
		return this.ToJSON();
	};

	this.AjaxQueryServer = function () {
		//console.log('data sent:', this.ToJSON());
		//console.log('oWizard:', this);
		$.post(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php',
			{operation: 'wizard_helper', json_obj: this.ToJSON()},
			function (html) {
				$('#ajax_content').html(html);
				$('.blockUI').parent().unblock();
			}
		);
	};

	this.Preview = function (divId) {
		//console.log('data sent:', this.ToJSON());
		//console.log('oWizard:', this);
		$('#'+divId).load(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php?operation=wizard_helper_preview',
			{'json_obj': this.ToJSON()},
			function (responseText, textStatus, XMLHttpRequest) {
				$('#wizStep'+G_iCurrentStep).unblock({fadeOut: 0});
			});
	};
	
	this.UpdateCurrentValue = function (sFieldCode) {
		var $oField = $('#'+this.m_oData.m_oFieldsMap[sFieldCode]);
		$oField.trigger('update_value'); // Give the widget a chance to update its value (if it is aware of this event)
		var value = $oField.val();
		if (value == '')
		{
			value = null;
		}
		this.m_oData.m_oCurrentValues[sFieldCode] = value;
		return value;
	};

	this.UpdateDependentFields = function (aFieldNames) {
		var index = 0,
			nbOfFieldsToUpdate = 0,
			sAttCode,
			sFieldId;

		this.ResetQuery();
		this.UpdateWizard();
		while (index < aFieldNames.length)
		{
			sAttCode = aFieldNames[index];
			sFieldId = this.GetFieldId(sAttCode);
			if (sFieldId !== undefined)
			{
				nbOfFieldsToUpdate++;
				$('#fstatus_'+sFieldId).html('<img src="../images/indicator.gif" />');
				$('#field_'+sFieldId).find('div').block({
					message: '',
					overlayCSS: {backgroundColor: '#f1f1f1', opacity: 0.3}
				});
				this.RequestAllowedValues(sAttCode);
			}
			index++;
		}

		if (nbOfFieldsToUpdate > 0)
		{
			this.AjaxQueryServer();
		}
	};

	this.ReloadObjectCreationForm = function (sFormId, sTargetState) {
		$('#'+sFormId).block();
		this.UpdateWizard();
		this.ResetQuery();
		var sTransactionId = $('input[name=transaction_id]').val();
		$.post(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php',
			{json_obj: this.ToJSON(), operation: 'obj_creation_form', target_state: sTargetState, transaction_id: sTransactionId},
			function (data) {
				// Delete any previous instances of CKEditor
				$('#'+sFormId).find('.htmlEditor').each(function () {
					var sId = $(this).attr('id');
					var editorInst = CKEDITOR.instances[sId];
					if (editorInst.status == 'ready')
					{
						editorInst.destroy(true);
					}
				});

				$('#'+sFormId).html(data);
				onDelayedReady();
				$('#'+sFormId).unblock();
			}
		);
	};
}

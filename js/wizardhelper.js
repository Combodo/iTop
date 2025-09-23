/*
 * Copyright (C) 2010-2024 Combodo SAS
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
		/** {Object} m_aStaticValues Values of the object that are not meant to be changed by the user. Only there to be used in the workflow for dependencies or to be passed through. */
		'm_aStaticValues' : {},
		'm_iFieldsCount': 0,
		'm_sFormPrefix': sFormPrefix,
		'm_sState': sState,
		'm_bReturnNotEditableFields': false, // if true then will return values and not editable fields
		'm_sWizHelperJsVarName': null // if set will use this name when server returns JS code in \WizardHelper::GetJsForUpdateFields
	};
	this.m_oData.m_sClass = sClass;
	/**
	 * Promise resolve callback when dependencies have been updated
	 * @since 3.0.3-2 3.0.4 3.1.1 3.2.0 N°6766
	 * */
	this.m_oDependenciesUpdatedPromiseResolve = null;

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

	/**
	 * Set form object values for fields without field widget.
	 *
	 * @since 3.1
	 *
	 * @param values
	 * @constructor
	 */
	this.SetStaticValues = function(values){
		this.m_oData.m_aStaticValues = values;
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
		const me = this;
		var aRefreshed = [];
		//console.log('** UpdateFields **');
		// Set the full HTML for the input field
		for (i = 0; i < this.m_oData.m_aAllowedValuesRequested.length; i++)
		{
			var sAttCode = this.m_oData.m_aAllowedValuesRequested[i];
			var sFieldId = this.m_oData.m_oFieldsMap[sAttCode];
			var bDisabled = $('#'+sFieldId).prop('disabled');

			// N°4408 Depending if the returned field contains an input or only the display value; we replace the wrapper to avoid dummy nesting (replaceWith), otherwise we replace the content like before (html)
			const sMethodToCall = ($(this.m_oData.m_oAllowedValues[sAttCode]).attr('id') === 'field_'+sFieldId) ? 'replaceWith' : 'html';
			$('#field_'+sFieldId)[sMethodToCall](this.m_oData.m_oAllowedValues[sAttCode]);

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
		for (i = 0; i < aRefreshed.length; i++) {
			var sString = "$('#"+aRefreshed[i]+"').trigger('change').trigger('update');";
			const oPromise = new Promise(function (resolve) {
				// Store the resolve callback so we can call it later from outside
				me.m_oDependenciesUpdatedPromiseResolve = resolve;
			});
			oPromise.then(function () {
				window.setTimeout(sString, 1); // Synchronous 'trigger' does nothing, call it asynchronously
				// Resolve callback is reinitialized in case the redirection fails for any reason and we might need to retry
				me.m_oDependenciesUpdatedPromiseResolve = null;
			});
		}
		if($('[data-field-status="blocked"]').length === 0) {
			$('.disabledDuringFieldLoading').prop("disabled", false).removeClass('disabledDuringFieldLoading');
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
				$('[data-field-status="blocked"]')
					.attr('data-field-status', 'ready')
					.unblock();

				if($('[data-field-status="blocked"]').length === 0) {
					$('.disabledDuringFieldLoading').prop("disabled", false).removeClass('disabledDuringFieldLoading');
				}
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
		// Static values handling
		if(this.m_oData.m_aStaticValues.hasOwnProperty(sFieldCode)){
			const value = this.m_oData.m_aStaticValues[sFieldCode];
			this.m_oData.m_oCurrentValues[sFieldCode] = value;
			return value;
		}
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
		var fieldForm = null;
		while (index < aFieldNames.length )
		{
			sAttCode = aFieldNames[index];
			sFieldId = this.GetFieldId(sAttCode);
			if (sFieldId !== undefined) {
				nbOfFieldsToUpdate++;
				$('#fstatus_' + sFieldId).html('<img src="../images/indicator.gif" />');
				$('#field_' + sFieldId).find('div')
					.attr('data-field-status', 'blocked')
					.block({
						message: '',
						overlayCSS: {backgroundColor: '#f1f1f1', opacity: 0.3}
				});
				fieldForm = $('#field_' + sFieldId).closest('form');
				this.RequestAllowedValues(sAttCode);
			}
			index++;
		}
		
		if ((fieldForm !== null) && ($('[data-field-status="blocked"]').length > 0)) {
			fieldForm.find('button[type=submit]:not(:disabled)').prop("disabled", true).addClass('disabledDuringFieldLoading');
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
					CombodoCKEditorHandler.DeleteInstance(sId);
				});

				$('#'+sFormId).html(data);
				onDelayedReady();
				$('#'+sFormId).unblock();
			}
		);
	};
}

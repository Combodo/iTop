// Copyright (C) 2010-2016 Combodo SARL
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

// Wizard Helper JavaScript class to communicate with the WizardHelper PHP class

if (!Array.prototype.indexOf) // Emulation of the indexOf function for IE and old browsers
{
	Array.prototype.indexOf = function(elt /*, from*/)
	{
		var len = this.length;
		var from = Number(arguments[1]) || 0;
		from = (from < 0) ? Math.ceil(from) : Math.floor(from);

		if (from < 0) from += len;
		for (; from < len; from++)
		{
			if (from in this && this[from] === elt) return from;
		}
		return -1;
	};
}

function WizardHelper(sClass, sFormPrefix, sState, sInitialState, sStimulus)
{
	this.m_oData = { 'm_sClass' : '',
					 'm_oFieldsMap': {},
					 'm_oCurrentValues': {},
					 'm_aDefaultValueRequested': [],
					 'm_aAllowedValuesRequested': [],
					 'm_oDefaultValue': {},
					 'm_oAllowedValues': {},
					 'm_iFieldsCount' : 0,
					 'm_sFormPrefix' : sFormPrefix,
					 'm_sState': sState
					};
	this.m_oData.m_sClass = sClass;

	// Setting optional transition data
	if(sInitialState !== undefined)
	{
		this.m_oData.m_sInitialState = sInitialState;
	}
	if(sStimulus !== undefined)
	{
		this.m_oData.m_sStimulus = sStimulus;
	}

	// Methods
	this.SetFieldsMap = function (oFieldsMap)
	{
		this.m_oData.m_oFieldsMap = oFieldsMap;
	};
	
	this.SetFieldsCount = function (count)
	{
		this.m_oData.m_iFieldsCount = count;
	};
	
	this.GetFieldId = function(sFieldName)
	{
		id = this.m_oData.m_oFieldsMap[sFieldName];
		return id;
	};

	this.RequestDefaultValue = function (sFieldName)
	{
		currentValue = this.UpdateCurrentValue(sFieldName);
		if (currentValue == null)
		{
			this.m_oData.m_aDefaultValueRequested.push(sFieldName);
		}
	};
	
	this.RequestAllowedValues = function (sFieldName)
	{
		this.m_oData.m_aAllowedValuesRequested.push(sFieldName);
	};
	
	this.SetCurrentValue = function (sFieldName, currentValue)
	{
		this.m_oData.m_oCurrentValues[sFieldName] = currentValue;
	};
	
	this.ToJSON = function ()
	{
		return JSON.stringify(this.m_oData);
	};
	
	this.FromJSON = function (sJSON)
	{
		//console.log('Parsing JSON:'+sJSON);
		this.m_oData = JSON.parse(sJSON);
	};

	this.ResetQuery = function ()
	{
		this.m_oData.m_aDefaultValueRequested = [];
		this.m_oData.m_oDefaultValue = {};
		this.m_oData.m_aAllowedValuesRequested = [];
		this.m_oData.m_oAllowedValues = {};
	};
	
	this.UpdateFields = function ()
	{
		var aRefreshed = [];
		//console.log('** UpdateFields **');
		// Set the full HTML for the input field
		for(i=0; i<this.m_oData.m_aAllowedValuesRequested.length; i++)
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
		for(i=0; i<aRefreshed.length; i++)
		{
			var sString = "$('#"+aRefreshed[i]+"').trigger('change').trigger('update');";
			window.setTimeout(sString, 1); // Synchronous 'trigger' does nothing, call it asynchronously
		}
	};
	
	this.UpdateWizard = function ()
	{
		//console.log('** UpdateWizard **')
		for(sFieldCode in this.m_oData.m_oFieldsMap)
		{
			sCleanFieldCode = sFieldCode.replace('"', '');
			//console.log(sFieldCode);
			this.UpdateCurrentValue(sCleanFieldCode);
		}
	};
	
	this.UpdateWizardToJSON = function ()
	{
		this.UpdateWizard();
		return this.ToJSON();
	};
	
	this.AjaxQueryServer = function ()
	{
		//console.log('data sent:', this.ToJSON());
		//console.log('oWizard:', this);
		$.post(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php',
		   { operation: 'wizard_helper', json_obj: this.ToJSON() },
			function(html){
				$('#ajax_content').html(html);
				$('.blockUI').parent().unblock();
				//console.log('data received:', oWizardHelper);
				//oWizardHelper.FromJSON(json_data);
				//oWizardHelper.UpdateFields(); // Is done directly in the html provided by ajax.render.php
				//console.log(oWizardHelper);
				//$('#wizStep'+ G_iCurrentStep).unblock( {fadeOut: 0} );
			});
	};
	
	this.Preview = function (divId)
	{
		//console.log('data sent:', this.ToJSON());
		//console.log('oWizard:', this);
		$('#'+divId).load(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php?operation=wizard_helper_preview',
		   	{'json_obj': this.ToJSON()},
			function(responseText, textStatus, XMLHttpRequest){
				$('#wizStep'+ G_iCurrentStep).unblock( {fadeOut: 0} );
			});
	};
	
	this.UpdateCurrentValue = function (sFieldCode)
	{
		$('#'+this.m_oData.m_oFieldsMap[sFieldCode]).trigger('update_value'); // Give the widget a chance to update its value (if it is aware of this event)
		value = $('#'+this.m_oData.m_oFieldsMap[sFieldCode]).val();
		if (value == '')
		{
			value = null;
		}
		this.m_oData.m_oCurrentValues[sFieldCode] = value;
		return value;		
	};

	this.UpdateDependentFields = function(aFieldNames)
	{
		index = 0;
		this.ResetQuery();
		this.UpdateWizard();
		while(index < aFieldNames.length )
		{
			sAttCode = aFieldNames[index];
			sFieldId = this.GetFieldId(sAttCode);
			if (sFieldId !== undefined) {
				$('#fstatus_' + sFieldId).html('<img src="../images/indicator.gif" />');
				$('#field_' + sFieldId).find('div').block({
					message: '',
					overlayCSS: {backgroundColor: '#f1f1f1', opacity: 0.3}
				});
				this.RequestAllowedValues(sAttCode);
			}
			index++;
		}
		this.AjaxQueryServer();
	};
	
	this.ReloadObjectCreationForm = function(sFormId, sTargetState)
	{
		$('#'+sFormId).block();
		this.UpdateWizard();
		this.ResetQuery();
		var sTransactionId = $('input[name=transaction_id]').val();
		$.post(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php',
			{ json_obj: this.ToJSON(), operation: 'obj_creation_form', target_state: sTargetState, transaction_id: sTransactionId },
			function(data)
			{
				// Delete any previous instances of CKEditor
				$('#'+sFormId).find('.htmlEditor').each(function() {
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

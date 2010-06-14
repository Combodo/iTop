// Wizard Helper JavaScript class to communicate with the WizardHelper PHP class
function WizardHelper(sClass)
{
	this.m_oData = { 'm_sClass' : '',
					 'm_oFieldsMap': {},
					 'm_oCurrentValues': {},
					 'm_aDefaultValueRequested': [],
					 'm_aAllowedValuesRequested': [],
					 'm_oDefaultValue': {},
					 'm_oAllowedValues': {},
					 'm_iFieldsCount' : 0
					};
	this.m_oData.m_sClass = sClass;
	
	// Methods
	this.SetFieldsMap = function (oFieldsMap)
	{
		this.m_oData.m_oFieldsMap = oFieldsMap;
	}
	
	this.SetFieldsCount = function (count)
	{
		this.m_oData.m_iFieldsCount = count;
		
	}
	
	this.GetFieldId = function(sFieldName)
	{
		id = this.m_oData.m_oFieldsMap[sFieldName];
		return id;
	}

	this.RequestDefaultValue = function (sFieldName)
	{
		currentValue = this.UpdateCurrentValue(sFieldName);
		if (currentValue == null)
		{
			this.m_oData.m_aDefaultValueRequested.push(sFieldName);
		}
	}
	this.RequestAllowedValues = function (sFieldName)
	{
		this.m_oData.m_aAllowedValuesRequested.push(sFieldName);
	}
	this.SetCurrentValue = function (sFieldName, currentValue)
	{
		this.m_oData.m_oCurrentValues[sFieldName] = currentValue;
	}
	
	this.ToJSON = function ()
	{
		return JSON.stringify(this.m_oData);
	}
	
	this.FromJSON = function (sJSON)
	{
		//console.log('Parsing JSON:'+sJSON);
		this.m_oData = JSON.parse(sJSON);
	}

	this.ResetQuery = function ()
	{
		this.m_oData.m_aDefaultValueRequested = [];
		this.m_oData.m_oDefaultValue = {};
		this.m_oData.m_aAllowedValuesRequested = [];
		this.m_oData.m_oAllowedValues = {};
	}
	
	this.UpdateFields = function ()
	{
		//console.log('** UpdateFields **');
		// Set the full HTML for the input field
		for(i=0; i<this.m_oData.m_aAllowedValuesRequested.length; i++)
		{
			sAttCode = this.m_oData.m_aAllowedValuesRequested[i];
			sFieldId = this.m_oData.m_oFieldsMap[sAttCode];
			//console.log('Setting #field_'+sFieldId+' to: '+this.m_oData.m_oAllowedValues[sAttCode]);
			$('#field_'+sFieldId).html(this.m_oData.m_oAllowedValues[sAttCode]);
		}
		// Set the actual value of the input
		for(i=0; i<this.m_oData.m_aDefaultValueRequested.length; i++)
		{
			sAttCode = this.m_oData.m_aDefaultValueRequested[i];
			defaultValue = this.m_oData.m_oDefaultValue[sAttCode];
			sFieldId = this.m_oData.m_oFieldsMap[sAttCode];	
			$('#'+sFieldId).val(defaultValue);
		}
	}
	
	this.UpdateWizard = function ()
	{
		//console.log('** UpdateWizard **')
		for(sFieldCode in this.m_oData.m_oFieldsMap)
		{
			sCleanFieldCode = sFieldCode.replace('"', '');
			//console.log(sFieldCode);
			this.UpdateCurrentValue(sCleanFieldCode);
		}
	}
	
	this.AjaxQueryServer = function ()
	{
		//console.log('data sent:', this.ToJSON());
		//console.log('oWizard:', this);
		$.get('ajax.render.php?json_obj=' + this.ToJSON(),
		   { operation: 'wizard_helper' },
			function(html){
				$('body').append(html);
				//console.log('data received:', oWizardHelper);
				//oWizardHelper.FromJSON(json_data);
				//oWizardHelper.UpdateFields(); // Is done directly in the html provided by ajax.render.php
				//console.log(oWizardHelper);
				//$('#wizStep'+ G_iCurrentStep).unblock( {fadeOut: 0} );
			});
	}
	
	this.Preview = function (divId)
	{
		//console.log('data sent:', this.ToJSON());
		//console.log('oWizard:', this);
		$('#'+divId).load('ajax.render.php?operation=wizard_helper_preview',
		   	{'json_obj': this.ToJSON()},
			function(responseText, textStatus, XMLHttpRequest){
				$('#wizStep'+ G_iCurrentStep).unblock( {fadeOut: 0} );
			});
	}
	
	this.UpdateCurrentValue = function (sFieldCode)
	{
		value = $('#'+this.m_oData.m_oFieldsMap[sFieldCode]).val();
		if (value == '')
		{
			value = null;
		}
		this.m_oData.m_oCurrentValues[sFieldCode] = value;
		return value;		
	}
}

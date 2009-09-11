// Wizard Helper JavaScript class to communicate with the WizardHelper PHP class

function WizardHelper(sClass)
{
	this.m_oData = { 'm_sClass' : '',
					 'm_oFieldsMap': {},
					 'm_aCurrentValues': [],
					 'm_aDefaultValueRequested': [],
					 'm_aAllowedValuesRequested': [],
					 'm_aDefaultValue': [],
					 'm_aAllowedValues': [],
					 'm_iFieldsCount' : 0,
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

	this.RequestDefaultValue = function (sFieldName)
	{
		currentValue = this.UpdateCurrentValue(sFieldName);
		sFieldId = this.m_oData.m_oFieldsMap[sFieldName];
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
		this.m_oData.m_aCurrentValues[this.m_oData.m_oFieldsMap[sFieldName]] = currentValue;
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
		this.m_oData.m_aDefaultValue = [];
		this.m_oData.m_aAllowedValuesRequested = [];
		this.m_oData.m_aAllowedValues = [];
	}
	
	this.UpdateFields = function ()
	{
		//console.log('** UpdateFields **')
		for(i=0; i< this.m_oData.m_aAllowedValuesRequested.length; i++)
		{
			sAttCode = this.m_oData.m_aAllowedValuesRequested[i];
			sFieldId = this.m_oData.m_oFieldsMap[sAttCode];
			$('#field_'+sFieldId).html(this.m_oData.m_aAllowedValues[sFieldId]);
		}
		for(i=0; i< this.m_oData.m_aDefaultValueRequested.length; i++)
		{
			sAttCode = this.m_oData.m_aDefaultValueRequested[i];
			sFieldId = this.m_oData.m_oFieldsMap[sAttCode];
			defaultValue = this.m_oData.m_aDefaultValue[sFieldId];
			//console.log('Setting field:'+sFieldId+' ('+sAttCode+') to: '+defaultValue);
			var oElement = document.getElementById('att_'+sFieldId);
			oElement.value = defaultValue;
		}
	}
	
	this.UpdateWizard = function ()
	{
		//console.log('** UpdateWizard **')
		for(i=0; i< this.m_oData.m_iFieldsCount; i++)
		{
			value = $('#att_'+i).val();
			if (value == '')
			{
				value = null;
			}
			this.m_oData.m_aCurrentValues[i] = value;
		}
	}
	
	this.AjaxQueryServer = function ()
	{
		//console.log('data sent:', this.ToJSON());
		console.log('oWizard:', this);
		$.get('ajax.render.php?json_obj=' + this.ToJSON(),
		   { operation: 'wizard_helper' },
			function(json_data){
				//console.log('data received:', json_data);
				//oWizardHelper.FromJSON(json_data);
				oWizardHelper.UpdateFields();
				//console.log(oWizardHelper);
				$('#wizStep'+ G_iCurrentStep).unblock( {fadeOut: 0} );
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
	
	this.UpdateCurrentValue = function (sFieldName)
	{
		sFieldId = this.m_oData.m_oFieldsMap[sFieldName];
		var oElement = document.getElementById('att_'+sFieldId);
		value = oElement.value;
		if (value == '')
		{
			value = null;
		}
		this.m_oData.m_aCurrentValues[sFieldId] = value;
		return value;		
	}
}

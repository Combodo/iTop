//iTop Designer widget for editing properties line by line
$(function()
{
	// the widget definition, where "itop" is the namespace,
	// "property_field" the widget name
	$.widget( "itop.property_field",
	{
		// default options
		options:
		{
			field_id: '',
			submit_to: 'index.php',
			submit_parameters: {operation: 'async_action'},
			do_apply: null,
			do_cancel: null
			
		},
	
		// the constructor
		_create: function()
		{	
			this.element.addClass( "itop-property-field" );
			this.bModified = false;
			
			var me = this;
			if (this.options.field_id != '')
			{
				$('#'+this.options.field_id).bind('change.itop-property-field', function() { me._on_change(); });
				this.value = this._get_field_value();
			}
			this.element.find(".prop_apply").bind('click.itop-property-field', function() { me._do_apply(); });
			this.element.find(".prop_cancel").bind('click.itop-property-field', function() { me._do_cancel(); });
			
			this._refresh();
		},
	
		// called when created, and later when changing options
		_refresh: function()
		{
			if (this.bModified)
			{
				this.element.find(".prop_icon span.ui-icon").css({visibility: ''});
			}
			else
			{
				this.element.find(".prop_icon span.ui-icon").css({visibility: 'hidden'});				
			}
		},
	
		// events bound via _bind are removed automatically
		// revert other modifications here
		_destroy: function()
		{
			this.element.removeClass( "itop-property-field" );
		},
		
		// _setOptions is called with a hash of all options that are changing
		// always refresh when changing options
		_setOptions: function()
		{
			// in 1.9 would use _superApply
			$.Widget.prototype._setOptions.apply( this, arguments );
			this._refresh();
		},
	
		// _setOption is called for each individual option that is changing
		_setOption: function( key, value )
		{
			// in 1.9 would use _super
			$.Widget.prototype._setOption.call( this, key, value );
		},
		_on_change: function()
		{
			var new_value = this._get_field_value();
			if (new_value != this.value)
			{
				this.bModified = true;
			}
			else
			{
				this.bModified = false;
			}
			this._refresh();
		},
		_get_field_value: function()
		{
			var oField = $('#'+this.options.field_id);
			if (oField.attr('type') == 'checkbox')
			{
				return (oField.attr('checked') == 'checked');
			}
			else
			{
				return oField.val();				
			}			
		},
		_get_committed_value: function()
		{
			return { name: $('#'+this.options.field_id).attr('name'), value: this.value };
		},
		_do_apply: function()
		{
			if (this.options.do_apply)
			{
				// specific behavior...
				this.options.do_apply();
			}
			else
			{
				// Validate the field
				sFormId = this.element.closest('form').attr('id');
				var oField = $('#'+this.options.field_id);
				oField.trigger('validate');
				if ( $.inArray(this.options.field_id, oFormValidation[sFormId]) == -1)
				{
					this.bModified = false;
					this.previous_value = this.value;
					this.value = this._get_field_value();
					this._do_submit();
					this._refresh();
				}
			}
		},
		_do_cancel: function()
		{
			if (this.options.do_cancel)
			{
				// specific behavior...
				this.options.do_cancel();
			}
			else
			{
				this.bModified = false;
				var oField = $('#'+this.options.field_id);
				if (oField.attr('type') == 'checkbox')
				{
					if (this.value)
					{
						oField.attr('checked', true);					
					}
					else
					{
						oField.removeAttr('checked');										
					}
				}
				else
				{
					oField.val(this.value);				
				}
				this._refresh();
				oField.trigger('reverted', {type: 'designer', previous_value: this.value });
				oField.trigger('validate');
			}
		},
		_do_submit: function()
		{
			var oData = {};
			this.element.closest('form').find(':input[type=hidden]').each(function()
			{
				// Hidden form fields
				oData[$(this).attr('name')] = $(this).val();
			});
			this.element.closest('form').find('.itop-property-field').each(function()
			{
				var oWidget = $(this).data('property_field');
				if (oWidget)
				{
					var oVal = oWidget._get_committed_value();
					oData[oVal.name] = oVal.value;
				}
			});
			oPostedData = this.options.submit_parameters;
			oPostedData.params = oData;
			oPostedData.params.updated = [ $('#'+this.options.field_id).attr('name') ]; // only one field updated in this case
			oPostedData.params.previous_values = {};
			oPostedData.params.previous_values[oPostedData.params.updated] = this.previous_value; // pass also the previous value(s)		
			$.post(this.options.submit_to, oPostedData, function(data)
			{
				$('#prop_submit_result').html(data);
			});
		}
	});
});

var oFormValidation = {};

function ValidateWithPattern(sFieldId, bMandatory, sPattern, sFormId)
{
	var currentVal = $('#'+sFieldId).val();
	var bValid = true;
	
	if (bMandatory && (currentVal == ''))
	{
		bValid = false;
	}
	
	if ((sPattern != '') && (currentVal != ''))
	{
		re = new RegExp(sPattern);
		bValid = re.test(currentVal);
	}
	if (!bValid)
	{
		$('#v_'+sFieldId).html('<img style="vertical-align:middle;" src="'+GetAbsoluteUrlAppRoot()+'images/validation_error.png">');
		if (oFormValidation[sFormId] == undefined) oFormValidation[sFormId] = [];
		oFormValidation[sFormId].push(sFieldId);
	}
	else
	{
		$('#v_'+sFieldId).html('');
	}
}

function ValidateForm(sFormId, bValidateAll)
{
	oFormValidation[sFormId] = [];
	if (bValidateAll)
	{
		$('#'+sFormId+' :input').trigger('validate');
	}
	else
	{
		// Only the visible fields
		$('#'+sFormId+' :input:visible').each(function() {
			$(this).trigger('validate');
		});
	}
	return oFormValidation[sFormId];
}


function ReadFormParams(sFormId)
{
	var oMap = { };
	$('#'+sFormId+' :input:visible').each( function() {
		var sName = $(this).attr('name');
		if (sName && sName != '')
		{
			if (this.type == 'checkbox')
			{
				oMap[sName] = ($(this).attr('checked') == 'checked');
			}
			else
			{
				oMap[sName] = $(this).val();
			}
			
		}
	});
	return oMap;
}

function SubmitForm(sFormId, onSubmitResult)
{
	var aErrors = ValidateForm(sFormId, false);
	if (aErrors.length == 0)
	{
		var oMap = ReadFormParams(sFormId);
		oMap.module_name = sCurrentModule;
		$('#'+sFormId+' :input:visible').each( function() {
			var sName = $(this).attr('name');
			if (sName && sName != '')
			{
				if (this.type == 'checkbox')
				{
					oMap[sName] = ($(this).attr('checked') == 'checked');
				}
				else
				{
					oMap[sName] = $(this).val();
				}
				
			}
		});
		$.post(GetAbsoluteUrlAppRoot()+'designer/module.php', oMap, function(data)
				{
					onSubmitResult(data);
				});
	}
	else
	{
		// TODO: better error reporting !!!
		alert('Please fill all the fields before continuing...');
	}
}

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
			parent_selector: null,
			field_id: '',
			get_field_value: null,
			equals: null,
			submit_to: 'index.php',
			submit_parameters: {operation: 'async_action'},
			do_apply: null,
			do_cancel: null,
			auto_apply: false
		},
	
		// the constructor
		_create: function()
		{	
			var me = this;

			this.element
				.addClass( "itop-property-field" )
				.bind('apply_changes.itop-property-field', function(){me._do_apply();} );
				
			this.bModified = false;
			
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
				this.element.addClass("itop-property-field-modified");
				this.element.find(".prop_icon span.ui-icon-circle-check").css({visibility: ''});
				this.element.find(".prop_icon span.ui-icon-circle-close").css({visibility: ''});
			}
			else
			{
				this.element.removeClass("itop-property-field-modified");
				this.element.find(".prop_icon span.ui-icon-circle-check").css({visibility: 'hidden'});
				this.element.find(".prop_icon span.ui-icon-circle-close").css({visibility: 'hidden'});
			}
		},
	
		// events bound via _bind are removed automatically
		// revert other modifications here
		_destroy: function()
		{
			this.element.removeClass( "itop-property-field" );
			this.element.removeClass("itop-property-field-modified");
		},
		
		// _setOptions is called with a hash of all options that are changing
		// always refresh when changing options
		_setOptions: function()
		{
			// in 1.9 would use _superApply
			this._superApply(arguments);
			this._refresh();
		},
	
		// _setOption is called for each individual option that is changing
		_setOption: function( key, value )
		{
			// in 1.9 would use _super
			this._superApply(arguments);
		},
		_on_change: function()
		{
			var new_value = this._get_field_value();
			if (this._equals(new_value, this.value))
			{
				this.bModified = false;
			}
			else
			{
				this.bModified = true;
				if (this.options.auto_apply)
				{
					this._do_apply();
				}
			}
			this._refresh();
		},
		_equals: function( value1, value2 )
		{
			if (this.options.equals === null)
			{
				return value1 == value2;
			}
			else
			{
				return this.options.equals(value1, value2);
			}
		},
		_get_field_value: function()
		{
			if (this.options.get_field_value === null)
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
			}
			else
			{
				return this.options.get_field_value();
			}			
		},
		_get_committed_value: function()
		{
			return { name: $('#'+this.options.field_id).attr('name'), value: this.value };
		},
		_do_apply: function()
		{
			if (this.options.parent_selector)
			{
				$(this.options.parent_selector).trigger('mark_as_modified');
			}
			if (this.options.do_apply)
			{
				// specific behavior...
				if (this.options.do_apply())
				{
					this.bModified = false;
					this.previous_value = this.value;
					this.value = this._get_field_value();
					this._refresh();
				}
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
				var oWidget = $(this).data('itopProperty_field');
				if (oWidget && oWidget._is_visible())
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
		},
		_is_visible: function()
		{
			return this.element.is(':visible');
		}
	});
});

var oFormValidation = {};

function ValidateWithPattern(sFieldId, bMandatory, sPattern, sFormId, aForbiddenValues, sExplainForbiddenValues)
{
	var currentVal = $('#'+sFieldId).val();
	var bValid = true;
	var sMessage = null;
	
	if (bMandatory && (currentVal == ''))
	{
		bValid = false;
	}
	if ((sPattern != '') && (currentVal != ''))
	{
		re = new RegExp(sPattern);
		bValid = re.test(currentVal);
	}
	if (aForbiddenValues)
	{
		for(var i in aForbiddenValues)
		{
			if (aForbiddenValues[i] == currentVal)
			{
				bValid = false;
				sMessage = sExplainForbiddenValues;
				break;
			}
		}
	}

	if (oFormValidation[sFormId] == undefined) oFormValidation[sFormId] = [];
	if (!bValid)
	{
		$('#v_'+sFieldId).addClass('ui-state-error');
		oFormValidation[sFormId].push(sFieldId);
		if (sMessage)
		{
			$('#'+sFieldId).attr('title', sMessage).tooltip();
			if ($('#'+sFieldId).is(":focus"))
			{
				$('#'+sFieldId).tooltip('open');
			}
		}
	}
	else
	{
		$('#v_'+sFieldId).removeClass('ui-state-error');
		if ($('#'+sFieldId).data('uiTooltip'))
		{
			$('#'+sFieldId).tooltip('close');
		}
		$('#'+sFieldId).removeAttr('title');
		// Remove the element from the array 
		iFieldIdPos = jQuery.inArray(sFieldId, oFormValidation[sFormId]);
		if (iFieldIdPos > -1)
		{
			oFormValidation[sFormId].splice(iFieldIdPos, 1);			
		}
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
	$('#'+sFormId+' :input').each( function() {
		if ($(this).parent().is(':visible'))
		{
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
		$('#'+sFormId+' :input').each( function() {
			if ($(this).parent().is(':visible'))
			{
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

function RemoveSubForm(sId, sUrl, oParams)
{
	$.post(sUrl, oParams, function(data) {
		$('body').append(data);
	});
}

function AddSubForm(sId, sUrl, oParams)
{
	var aIndexes = JSON.parse($('#'+sId).val());
	var iLast = aIndexes[aIndexes.length - 1];
	var iNewIdx = 1 + iLast;
	oParams.new_index = iNewIdx;
	
	$.post(sUrl, oParams, function(data) {
		$('body').append(data);
	});
}

function InitFormSelectorField(sId, sSelector)
{
	$('#'+sId).bind('change reverted init', function() {
		// Mark all the direct children as hidden
		$('tr[data-selector="'+sSelector+'"]').attr('data-state', 'hidden');
		// Mark the selected one as visible
		var sSelectedHierarchy = sSelector+'-'+this.value; 
		$('tr[data-path="'+sSelectedHierarchy+'"]').attr('data-state', 'visible');
					
		// Show all items behind the current one
		$('tr[data-path^="'+sSelector+'"]').show();
		// Hide items behind the current one as soon as it is behind a hidden node (or itself is marked as hidden) 
		$('tr[data-path^="'+sSelector+'"][data-state="hidden"]').each(function() {
			$(this).hide();
			var sPath = $(this).attr('data-path');
			$('tr[data-path^="'+sPath+'/"]').hide();
		});			
	}).trigger('init'); // initial refresh
}

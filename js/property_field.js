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
			auto_apply: false,
			can_apply: true
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
				// In case there is an hidden input having the same id (somewhere else in the page), the change event does not occur unless the input loses the focus
				// To reduce the impact, let's handle keyup as well
				$('#'+this.options.field_id, this.element).bind('change.itop-property-field keyup.itop-property-field', function() { me._on_change(); });
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
				if (this.options.can_apply)
				{
					this.element.find(".prop_icon span.ui-icon-circle-check").css({visibility: ''});					
				}
				else
				{
					this.element.find(".prop_icon span.ui-icon-circle-check").css({visibility: 'hidden'});
				}
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
				if (this.options.auto_apply && this.options.can_apply)
				{
					this._do_apply();
				}
			}
			this._refresh();
			if (this.options.parent_selector)
			{
				$(this.options.parent_selector).trigger('subitem_changed');
			}
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
				var oField = $('#'+this.options.field_id, this.element);
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
		get_field_name: function()
		{
			return $('#'+this.options.field_id, this.element).attr('name');
		},
		_get_committed_value: function()
		{
			return { name: $('#'+this.options.field_id, this.element).attr('name'), value: this.value };
		},
		_get_current_value: function()
		{
			return { name: $('#'+this.options.field_id, this.element).attr('name'), value: this._get_field_value() };
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
				var oField = $('#'+this.options.field_id, this.element);
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
				var oField = $('#'+this.options.field_id, this.element);
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
				if (this.options.parent_selector)
				{
					$(this.options.parent_selector).trigger('subitem_changed');
				}
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
			oPostedData.params.updated = [ $('#'+this.options.field_id, this.element).attr('name') ]; // only one field updated in this case
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
		},
		mark_as_applied: function()
		{
			this.bModified = false;
			this.previous_value = this.value;
			this.value = this._get_field_value();
			this._refresh();
		},
		validate: function()
		{
			var oField = $('#'+this.options.field_id, this.element);
			oField.trigger('validate');
		}
	});
});


$(function()
{
	// the widget definition, where "itop" is the namespace,
	// "selector_property_field" the widget name
	$.widget( "itop.selector_property_field", $.itop.property_field,
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
			auto_apply: false,
			can_apply: true,
			data_selector: ''
		},
	
		// the constructor
		_create: function()
		{	
			var me = this;
			this._superApply();
						
			this.element
				.addClass( "itop-selector-property-field" );
			
			$('#'+this.options.field_id).bind('reverted init', function() {
					me._update_subform();
				}).trigger('init'); // initial refresh
			
			this.element.bind('subitem_changed', function() {
				me._on_subitem_changed();
			});
		},
		_update_subform: function()
		{
			var sSelector = this.options.data_selector;
			var me = this;
			
			// Mark all the direct children as hidden
			$('tr[data-selector="'+sSelector+'"]').attr('data-state', 'hidden');
			// Mark the selected one as visible
			var sSelectedHierarchy = sSelector+'-'+$('#'+this.options.field_id).val(); 
			$('tr[data-path="'+sSelectedHierarchy+'"]').attr('data-state', 'visible');
						
			// Show all items behind the current one
			$('tr[data-path^="'+sSelector+'"]').show();
			// Hide items behind the current one as soon as it is behind a hidden node (or itself is marked as hidden) 
			$('tr[data-path^="'+sSelector+'"][data-state="hidden"]').each(function() {
				$(this).hide();
				var sPath = $(this).attr('data-path');
				$('tr[data-path^="'+sPath+'/"]').hide();
			});

			$('tr[data-path^="'+sSelector+'"]').each(function() {
				if($(this).is(':visible'))
				{
					var oPropField = $(this).closest('.itop-property-field');
					oPropField.property_field('option', {can_apply: !me.bModified, parent_selector: '#'+me.element.attr('id') });
					oPropField.property_field('validate');
				}
			});	
		},
		// events bound via _bind are removed automatically
		// revert other modifications here
		_destroy: function()
		{
			this.element.removeClass( "itop-selector-property-field" );
			this._superApply();
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
			}
			
			this._update_subform();
					
			if (this.options.auto_apply && this.options.can_apply)
			{
				this._do_apply();
			}
			this._on_subitem_changed(); // initial validation
			this._refresh();
		},
		_do_apply: function()
		{
			this._superApply();
			this._update_subform();
		},
		_do_submit: function()
		{
			var oData = {};
			this.element.closest('form').find(':input[type=hidden]').each(function()
			{
				// Hidden form fields
				oData[$(this).attr('name')] = $(this).val();
			});

			var sSelector = this.options.data_selector;
			var me = this;
			var aUpdated = [];
			$('tr[data-path^="'+sSelector+'"]').each(function() {
				if($(this).is(':visible'))
				{
					var sName = $(this).closest('.itop-property-field').property_field('mark_as_applied').property_field('get_field_name');
					if (typeof sName == 'string')
					{
						aUpdated.push(sName);						
					}
				}
			});				
			this.element.closest('form').find('.itop-property-field').each(function()
			{
				var oWidget = $(this).data('itopProperty_field');
				if (!oWidget)
				{
					// try the form selector widget
					oWidget = $(this).data('itopSelector_property_field');
				}
				if (oWidget && oWidget._is_visible())
				{
					var oVal = oWidget._get_committed_value();
					oData[oVal.name] = oVal.value;
				}
			});

			var oPostedData = this.options.submit_parameters;
			var sName = $('#'+this.options.field_id, this.element).attr('name');
			oPostedData.params = oData;
			oPostedData.params.updated = [];
			aUpdated.push(sName); // several fields updated in this case
			oPostedData.params.updated = aUpdated;
			oPostedData.params.previous_values = {};
			oPostedData.params.previous_values[sName] = this.previous_value; // pass also the previous value(s)		
			
			$.post(this.options.submit_to, oPostedData, function(data)
			{
				$('#prop_submit_result').html(data);
			});
		},
		_on_subitem_changed : function()
		{
			sFormId = this.element.closest('form').attr('id');
			oFormValidation[sFormId] = [];
			this.options.can_apply = true;
			var sSelector = this.options.data_selector
			$('tr[data-path^="'+sSelector+'"]').each(function() {
				if($(this).is(':visible'))
				{
					var oPropField = $(this).closest('.itop-property-field');
					oPropField.property_field('validate');
				}
			});
			this.options.can_apply = (oFormValidation[sFormId].length == 0); // apply allowed only if no error
			this._refresh();
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
		iFieldIdPos = jQuery.inArray(sFieldId, oFormValidation[sFormId]);
		if (iFieldIdPos == -1)
		{
			oFormValidation[sFormId].push(sFieldId);			
		}
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

function ValidateInteger(sFieldId, bMandatory, sFormId, iMin, iMax, sExplainFormat)
{
	var currentVal = $('#'+sFieldId).val();
	var bValid = true;
	var sMessage = null;
	
	if (bMandatory && (currentVal == ''))
	{
		bValid = false;
	}

	re = new RegExp('^$|^-?[0-9]+$');
	bValid = re.test(currentVal);

	if (bValid && (currentVal != ''))
	{
		// It is a valid number, let's check the boundaries
		var iValue = parseInt(currentVal, 10);
	
		if ((iMin != null) && (iValue < iMin))
		{
			bValid = false;
		}
	
		if ((iMax != null) && (iValue > iMax))
		{
			bValid = false;
		}

		if (!bValid && (sExplainFormat != undefined))
		{
			sMessage = sExplainFormat;
		}
	}

	if (oFormValidation[sFormId] == undefined) oFormValidation[sFormId] = [];
	if (!bValid)
	{
		$('#v_'+sFieldId).addClass('ui-state-error');
		iFieldIdPos = jQuery.inArray(sFieldId, oFormValidation[sFormId]);
		if (iFieldIdPos == -1)
		{
			oFormValidation[sFormId].push(sFieldId);			
		}
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


//iTop Form field
;
$(function()
{
	// the widget definition, where 'itop' is the namespace,
	// 'form_field' the widget name
	$.widget( 'itop.form_field',
	{
		// default options
		options:
		{
			validators: null,
			validate_callback: 'validate',				  // When using an anonymous function, use the 'me' parameter to acces the current widget : function(me){ return me.validate(); },
			on_validation_callback: function(data){  },
			get_current_value_callback: 'getCurrentValue',
			set_current_value_callback: function(me, oEvent, oData){ console.log('Form field: set_current_value_callback must be overloaded, this is the default callback.'); }	
		},
   
		// the constructor
		_create: function()
		{
			var me = this;
			
			this.element.addClass('form_field');

			this.element
			.bind('set_validators', function(oEvent, oData){
				oEvent.stopPropagation();
				me.options.validators = oData;
			});
			this.element
			.bind('validate get_current_value set_current_value', function(oEvent, oData){
				oEvent.stopPropagation();
		
				var callback = me.options[oEvent.type+'_callback'];
				
				if(typeof callback === 'string')
				{
					return me[callback](oEvent, oData);
				}
				else if(typeof callback === 'function')
				{
					return callback(me, oEvent, oData);
				}
				else
				{
					console.log('Form field : callback type must be a function or a existing function name of the widget');
					return false;
				}
			});
		},
		// called when created, and later when changing options
		_refresh: function()
		{

		},
		// events bound via _bind are removed automatically
		// revert other modifications here
		_destroy: function()
		{
			this.element
			.removeClass('form_field');
		},
		// _setOptions is called with a hash of all options that are changing
		// always refresh when changing options
		_setOptions: function()
		{
			this._superApply(arguments);
		},
		// _setOption is called for each individual option that is changing
		_setOption: function( key, value )
		{
			this._super( key, value );
		},
		getCurrentValue: function()
		{
			var value = null;

			this.element.find(':input:not(:button)').each(function(iIndex, oElem){
				if($(oElem).is(':hidden:not(.ck-hidden,.ck-input-text)') || $(oElem).is(':text:not(.ck-input-text)') || $(oElem).is(':password') || $(oElem).is('textarea'))
				{
					value = $(oElem).val();
				}
				else if($(oElem).is('select'))
				{
					if($(oElem).is('select[multiple]'))
					{
						value = [];
						$(oElem).find('option:selected').each(function(){
							value.push($(this).val());
						});
					}
					else
					{
						value = $(oElem).val();
					}
				}
				else if($(oElem).is(':checkbox') || $(oElem).is(':radio'))
				{
					if(value === null)
					{
						value = [];
					}
					if($(oElem).is(':checked'))
					{
						value.push($(oElem).val());
					}
				}
				else
				{
					console.log('Form field : Input type not handle yet.');
				}
			});
			
			return value;
		},
		validate: function(oEvent, oData)
		{
			var oResult = { is_valid: true, error_messages: [] };

			// Doing data validation
			if(this.options.validators !== null)
			{
				// Extracting value for the field
				var oValue = this.element.triggerHandler('get_current_value');
				if(oValue === null)
				{
					console.log('Form field : Warning, there was no value for "'+this.element.attr('data-field-id')+'"');
					return oResult;
				}
				
				var bMandatory = (this.options.validators.mandatory !== undefined);
				var bNotEmptyExtKey = (this.options.validators.notemptyextkey !== undefined);
				var bEmpty = ($.isArray(oValue)) ? (oValue.length === 0) : (oValue === '' || oValue === undefined);
				var value = oValue;
				
				// This is just a safety check in case a field doesn't always return an object when no value assigned, so we have to check the mandatory validator here...
				// ... But this should never happen.
				//if( (aValueKeys.length === 0) && bMandatory )
				if( bEmpty && bMandatory )
				{
					oResult.is_valid = false;
					oResult.error_messages.push(this.options.validators.mandatory.message);
				}
				else if( bEmpty && bNotEmptyExtKey )
				{
					oResult.is_valid = false;
					oResult.error_messages.push(this.options.validators.notemptyextkey.message);
				}
				// ... Field empty but not mandatory, no need to validate
				else if( bEmpty && !bMandatory )
				{
					// It's okay, no need to validate
				}
				// ... Otherwise, we check every validators
				else
				{
					for(var sValidatorType in this.options.validators)
					{
						var oValidator = this.options.validators[sValidatorType];
						if(sValidatorType === 'mandatory')
						{
							// Works for string, array, object
							if($.isEmptyObject(value))
							{
								oResult.is_valid = false;
								oResult.error_messages.push(oValidator.message);
							}
							// ... In case of non empty array, we have to check if the value is not null
							else if($.isArray(value))
							{
								for(var i in value)
								{
									if(typeof value[i] === 'string')
									{
										if($.isEmptyObject(value[i]))
										{
											oResult.is_valid = false;
											oResult.error_messages.push(oValidator.message);
										}
									}
									else
									{
										console.log('Form field: mandatory validation not supported yet for the type "' + (typeof value[i]) +'"');
									}
								}
							}
						}
						else if(sValidatorType === 'LinkedSetValidator'){
							// Prevent form submit if inputs are invalid state
							// @see Bug N°803 - Allow display & edition of attributes on n:n relations on Portal
							oResult.error_messages.push(oValidator.message);
							const aLinkedSetInvalidInputs = $('input:invalid', this.element);
							if(aLinkedSetInvalidInputs.length > 0){
								aLinkedSetInvalidInputs.each(function(e){
									const $Input = $(this);
									const aInputValidity = $Input[0].validity;
									if(aInputValidity.valueMissing){
										oResult.is_valid = false;
									}
									if(aInputValidity.patternMismatch){
										oResult.is_valid = false;
									}
								});
							}
						}
						else
						{
							var oRegExp = new RegExp(oValidator.reg_exp, "g");
							if(typeof value === 'string')
							{
								if(!oRegExp.test(value))
								{
									oResult.is_valid = false;
									oResult.error_messages.push(oValidator.message);
								}
							}
							else if($.isArray(value))
							{	
								for(var i in value)
								{
									if(value[i] === 'string' && !oRegExp.test(value))
									{
										oResult.is_valid = false;
										oResult.error_messages.push(oValidator.message);
									}
								}
							}
							else
							{
								console.log('Form field: validation not supported yet for the type "' + (typeof value) +'"');
							}
						}
					}
				}
			}
			
			this.options.on_validation_callback(this, oResult);
			
			return oResult;
		},
		// Debug helper
		showOptions: function()
		{
			return this.options;
		}
	});
});

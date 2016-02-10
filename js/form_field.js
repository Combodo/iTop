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
            validators: null
        },
   
        // the constructor
        _create: function()
        {
            var me = this;
            
            this.element
            .addClass('form_field');
           
            this.element
            .bind('field_change.form_field', function(event, data){
                me._onFieldChange(event, data);
            });

            this.element
            .bind('set_validators.form_field', function(event, data){
                me.options.validators = data;
            });

            this.element
                .bind('validate.form_field', function(event, data){
                    return me.validate();
                });

            this.element
                .bind('set_current_value.form_field', function(event, data){
                    return me.getCurrentValue();
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
            var value = {};
            
            this.element.find(':input').each(function(index, elem){
                if($(elem).is(':hidden') || $(elem).is(':text') || $(elem).is('textarea'))
                {
                    value[$(elem).attr('name')] = $(elem).val();
                }
                else if($(elem).is('select'))
                {
                    value[$(elem).attr('name')] = [];
                    $(elem).find('option:selected').each(function(){
                        value[$(elem).attr('name')].push($(this).val());
                    });
                }
                else if($(elem).is(':checkbox') || $(elem).is(':radio'))
                {
                    if(value[$(elem).attr('name')] === undefined)
                    {
                        value[$(elem).attr('name')] = [];
                    }
                    if($(elem).is(':checked'))
                    {
                        value[$(elem).attr('name')].push($(elem).val());
                    }
                }
                else
                {
                    console.log('Form field : Input type not handle yet.');
                }
            });
            
            return value;
        },
        validate: function()
        {
            var oResult = { is_valid: true, error_messages: [] };
                        
            // Doing data validation
            if(this.options.validators !== null)
            {
                var bMandatory = (this.options.validators.mandatory !== undefined);
                // Extracting value for the field
                var oValue = this.getCurrentValue();
                var aValueKeys = Object.keys(oValue);
                
                // This is just a safety check in case a field doesn't always return an object when no value assigned, so we have to check the mandatory validator here...
                // ... But this should never happen.
                if( (aValueKeys.length === 0) && bMandatory )
                {
                    oResult.is_valid = false;
                    oResult.error_messages.push(this.options.validators.mandatory.message);
                }
                // ... Otherwise, we check every validators
                else if(aValueKeys.length > 0)
                {
                    var value = oValue[aValueKeys[0]];
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
                            // ... In case of none empty array, we have to check is the value is not null
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
            
            // Rendering visual feedback on the field
            this.element.removeClass('has-success has-warning has-error')
            this.element.find('.help-block').html('');
            if(!oResult.is_valid)
            {
                this.element.addClass('has-error');
                for(var i in oResult.error_messages)
                {
                    this.element.find('.help-block').append($('<p>' + oResult.error_messages[i] + '</p>'));
                }
            }
            
            return oResult;
        },
        showOptions: function()
        {
            return this.options;
        }
    });
});

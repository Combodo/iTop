//iTop Form handler
;
$(function()
{
    // the widget definition, where 'itop' is the namespace,
    // 'form_handler' the widget name
    $.widget( 'itop.form_handler',
    {
        // default options
        options:
        {
            formmanager_class: null,
            formmanager_data: null,
            field_identifier_attr: 'data-field-id', // convention: fields are rendered into a div and are identified by this attribute
            fields_list: null,
            fields_impacts: {},
            touched_fields: [],
            submit_btn_selector: null,
            cancel_btn_selector: null,
            endpoint: null,
            is_modal: false,
            is_valid: true,
            script_element: null,
            style_element: null
        },

        buildData:
        {
            script_code: '',
            style_code: ''
        },
   
        // the constructor
        _create: function()
        {
            var me = this;
            
            this.element
            .addClass('form_handler');
            
            this.element
            .bind('field_change', function(event, data){
                me._onFieldChange(event, data);
            });
            
            // Creating DOM elements if not using user's specifics
            if(this.options.script_element === null)
            {
                this.options.script_element = $('<script type="text/javascript"></script>');
                this.element.after(this.options.script_element);
            }
            if(this.options.style_element === null)
            {
                this.options.style_element = $('<style></style>');
                this.element.before(this.options.style_element);
            }
            
            // Building the form
            if(this.options.fields_list !== null)
            {
                this.buildForm();
            }
            
            // Binding buttons
            if(this.options.submit_btn_selector !== null)
            {
                this.options.submit_btn_selector.off('click').on('click', function(event){ me._onSubmitClick(event); });
            }
            if(this.options.cancel_btn_selector !== null)
            {
                this.options.cancel_btn_selector.off('click').on('click', function(event){ me._onCancelClick(event); });
            }
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
            .removeClass('form_handler');
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
        getCurrentValues: function()
        {
            var result = {};
            
            for(var i in this.options.fields_list)
            {
                var field = this.options.fields_list[i];
                if(this.element.find('[' + this.options.field_identifier_attr + '="'+field.id+'"]').hasClass('form_field'))
                {
                    $.extend(true, result, this.element.find('[' + this.options.field_identifier_attr + '="'+field.id+'"]').triggerHandler('get_current_value'));
                }
                else
                {
                    console.log('Form handler : Cannot retrieve current value from field [' + this.options.field_identifier_attr + '="'+field.id+'"] as it seems to have no itop.form_field widget attached.');
                }
            }
            
            return result;
        },
        _getRequestedFields: function(sourceFieldName)
        {
            var fieldsName = [];
            
            if(this.options.fields_impacts[sourceFieldName] !== undefined)
            {
                for(var i in this.options.fields_impacts[sourceFieldName])
                {
                    fieldsName.push(this.options.fields_impacts[sourceFieldName][i]);
                }
            }
            
            return fieldsName;
        },
        _onFieldChange: function(event, data)
        {
            var me = this;
            
            // Data checks
            if(this.options.endpoint === null)
            {
                console.log('Form handler : An endpoint must be defined.');
                return false;
            }
            if(this.options.formmanager_class === null)
            {
                console.log('Form handler : Form manager class must be defined.');
                return false;
            }
            if(this.options.formmanager_data === null)
            {
                console.log('Form handler : Form manager data must be defined.');
                return false;
            }
            
            // Set field as touched so we know that we have to do checks on it later
            if(this.options.touched_fields.indexOf(data.name) < 0)
            {
                this.options.touched_fields.push(data.name);
            }
            
            var requestedFields = this._getRequestedFields(data.name);
            if(requestedFields.length > 0)
            {
                this._disableFormBeforeLoading();
                $.post(
                    this.options.endpoint,
                    {
                        operation: 'update',
                        formmanager_class: this.options.formmanager_class,
                        formmanager_data: JSON.stringify(this.options.formmanager_data),
                        current_values: this.getCurrentValues(),
                        requested_fields: requestedFields
                    },
                    function(data){
                        me._onUpdateSuccess(data);
                    }
                )
                .fail(function(data){ me._onUpdateFailure(data); })
                .always(function(data){ me._onUpdateAlways(data); });
            }
            else
            {
                // Check self NOW as they are no ajax call
                this.element.find('[' + this.options.field_identifier_attr + '="' + data.name + '"]').trigger('validate');
            }
        },
        // Intended for overloading in derived classes
        _onSubmitClick: function(event)
        {
        },
        // Intended for overloading in derived classes
        _onCancelClick: function(event)
        {
        },
        // Intended for overloading in derived classes
        _onUpdateSuccess: function(data)
        {
            if(data.form.updated_fields !== undefined)
            {
                this.buildData.script_code = '';
                this.buildData.style_code = '';

                for (var i in data.form.updated_fields)
                {
                    var updated_field = data.form.updated_fields[i];
                    this.options.fields_list[updated_field.id] = updated_field;
                    this._prepareField(updated_field.id);
                }

                // Adding code to the dom
                this.options.script_element.append('\n\n// Appended by update at ' + Date() + '\n' + this.buildData.script_code);
                this.options.style_element.append('\n\n// Appended by update at ' + Date() + '\n' + this.buildData.style_code);

                // Evaluating script code as adding it to dom did not executed it (only script from update !)
                eval(this.buildData.script_code);
            }
        },
        // Intended for overloading in derived classes
        _onUpdateFailure: function(data)
        {
        },
        // Intended for overloading in derived classes
        _onUpdateAlways: function(data)
        {
            // Check all touched AFTER ajax is complete, otherwise the renderer will redraw the field in the mean time.
            for(var i in this.options.touched_fields)
            {
                this.element.find('[' + this.options.field_identifier_attr + '="' + this.options.touched_fields[i] + '"]').trigger('validate');
            }
            this._enableFormAfterLoading();
        },
        // Intended for overloading in derived classes
        _disableFormBeforeLoading: function()
        {
        },
        // Intended for overloading in derived classes
        _enableFormAfterLoading: function()
        {
        },
        _loadCssFile: function(url)
        {
            if (!$('link[href="'+url+'"]').length)
                $('<link href="'+url+'" rel="stylesheet">').appendTo('head');
        },
        _loadJsFile: function(url)
        {
            if (!$('script[src="'+url+'"]').length)
                $.getScript(url);
        },
        // Place a field for which no container exists
        _addField: function(field_id)
        {
            $('<div ' + this.options.field_identifier_attr + '="'+field_id+'"></div>').appendTo(this.element);
        },
        _prepareField: function(field_id)
        {
            var field = this.options.fields_list[field_id];

            if(this.element.find('[' + this.options.field_identifier_attr + '="'+field.id+'"]').length === 1)
            {
                // We replace the node instead of just replacing the inner html so the previous widget is automatically destroyed.
                this.element.find('[' + this.options.field_identifier_attr + '="'+field.id+'"]').replaceWith( $('<div ' + this.options.field_identifier_attr + '="'+field.id+'"></div>') );
            }
            else
            {
                this._addField(field.id);
            }

            var field_container = this.element.find('[' + this.options.field_identifier_attr + '="'+field.id+'"]');
            // HTML
            if( (field.html !== undefined) && (field.html !== '') )
            {
                field_container.html(field.html);
            }
            // JS files
            if( (field.js_files !== undefined) && (field.js_files.length > 0) )
            {
                for(var j in field.js_files)
                {
                    this._loadJsFile(field.js_files[i]);
                }
            }
            // CSS files
            if( (field.css_files !== undefined) && (field.css_files.length > 0) )
            {
                for(var j in field.css_files)
                {
                    this._loadCssFile(field.css_files[i]);
                }
            }
            // JS inline
            if( (field.js_inline !== undefined) && (field.js_inline !== '') )
            {
                this.buildData.script_code += '; '+ field.js_inline;
            }
            // CSS inline
            if( (field.css_inline !== undefined) && (field.css_inline !== '') )
            {
                this.buildData.style_code += ' '+ field.css_inline;
            }
            // JS widget itop.form_field
            var json_validators = (field.validators != undefined) ? JSON.stringify(field.validators) : 'null';
            this.buildData.script_code += '; $("[' + this.options.field_identifier_attr + '=\'' + field.id + '\']").form_field({ validators: ' + json_validators + ' });';
            
        },
        buildForm: function()
        {
            this.buildData.script_code = '';
            this.buildData.style_code = '';

            for(var i in this.options.fields_list)
            {
                var field = this.options.fields_list[i];
                if(field.id === undefined)
                {
                    console.log('Form handler : An field must have at least an id property.');
                    return false;
                }

                this._prepareField(field.id);
            }

            this.options.script_element.text('$(document).ready(function(){ '+this.buildData.script_code+' });');
            this.options.style_element.text(this.buildData.style_code);
            
            eval(this.options.script_element.text());
        },
        showOptions: function() // Debug helper
        {
            console.log(this.options);
        }
    });
});

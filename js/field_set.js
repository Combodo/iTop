//iTop Form handler
;
$(function()
{
    // the widget definition, where 'itop' is the namespace,
    // 'form_handler' the widget name
    $.widget( 'itop.field_set',
    {
        // default options
        options:
        {
            field_identifier_attr: 'data-field-id', // convention: fields are rendered into a div and are identified by this attribute
            fields_list: null,
            fields_impacts: {},
            touched_fields: [],
            is_valid: true,
            form_path: '',
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
            .addClass('field_set');
            
            this.element
            .bind('field_change', function(event, data){
                console.log('field_set: field_change');
                me._onFieldChange(event, data);
            })
            .bind('update_form', function(event, data){
                console.log('field_set: update_form');
                me._onUpdateForm(event, data);
            })
            .bind('get_current_values', function(event, data){
                console.log('field_set: get_current_values');
                return me._onGetCurrentValues(event, data);
            })
            .bind('validate', function(event, data){
                if (data === undefined)
                {
                    data = {};
                }
                console.log('field_set: validate');
                return me._onValidate(event, data);
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
            .removeClass('field_set');
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
        _getField: function (sFieldId)
        {
            return this.element.find('[' + this.options.field_identifier_attr + '="'+sFieldId+'"][data-form-path="'+this.options.form_path+'"]');
        },
        _onGetCurrentValues: function(event, data)
        {
            event.stopPropagation();

            var result = {};
            
            for(var i in this.options.fields_list)
            {
                var field = this.options.fields_list[i];
                if(this._getField(field.id).hasClass('form_field'))
                {
                    result[field.id] = this._getField(field.id).triggerHandler('get_current_value');
                }
                else
                {
                    console.log('Field set : Cannot retrieve current value from field [' + this.options.field_identifier_attr + '="'+field.id+'"] as it seems to have no itop.form_field widget attached.');
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
            event.stopPropagation();

            // Set field as touched so we know that we have to do checks on it later
            if(this.options.touched_fields.indexOf(data.name) < 0)
            {
                this.options.touched_fields.push(data.name);
            }

            // Validate the field
            var oRes = this._getField(data.name).triggerHandler('validate', {touched_fields_only: true});
            if (!oRes.is_valid)
            {
                this.options.is_valid = false;
            }

            var requestedFields = this._getRequestedFields(data.name);
            if(requestedFields.length > 0)
            {
                this.element.trigger('update_fields', {form_path: this.options.form_path, requested_fields: requestedFields});
            }
        },
        _onUpdateForm: function(event, data)
        {
            event.stopPropagation();

            this.buildData.script_code = '';
            this.buildData.style_code = '';

            for (var i in data.updated_fields)
            {
                var updated_field = data.updated_fields[i];
                this.options.fields_list[updated_field.id] = updated_field;
                this._prepareField(updated_field.id);
            }

            // Adding code to the dom
            this.options.script_element.append('\n\n// Appended by update at ' + Date() + '\n' + this.buildData.script_code);
            this.options.style_element.append('\n\n// Appended by update at ' + Date() + '\n' + this.buildData.style_code);

            // Evaluating script code as adding it to dom did not executed it (only script from update !)
            eval(this.buildData.script_code);
        },
        _onValidate: function(event, data)
        {
            event.stopPropagation();

            this.options.is_valid = true;


            var aFieldsToValidate = [];
            if ((data.touched_fields_only !== undefined) && (data.touched_fields_only === true))
            {
                aFieldsToValidate = this.options.touched_fields;
            }
            else
            {
                // Requires IE9+ Object.keys(this.options.fields_list);
                for (var sFieldId in this.options.fields_list)
                {
                    aFieldsToValidate.push(sFieldId);
                }
            }

            for(var i in aFieldsToValidate)
            {
                var oRes = this._getField(aFieldsToValidate[i]).triggerHandler('validate', data);
                if (!oRes.is_valid)
                {
                    this.options.is_valid = false;
                }
            }
            return this.options.is_valid;
        },
        showOptions: function() // Debug helper
        {
            console.log(this.options);
            return this.options;
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
            $('<div ' + this.options.field_identifier_attr + '="'+field_id+'" data-form-path="' + this.options.form_path + '"></div>').appendTo(this.element);
        },
        _prepareField: function(field_id)
        {
            var field = this.options.fields_list[field_id];

            if(this._getField(field.id).length === 1)
            {
                // We replace the node instead of just replacing the inner html so the previous widget is automatically destroyed.
                this._getField(field.id).replaceWith( $('<div ' + this.options.field_identifier_attr + '="'+field.id+'" data-form-path="' + this.options.form_path + '"></div>') );
            }
            else
            {
                this._addField(field.id);
            }

            var field_container = this._getField(field.id);
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
                    console.log('Field set : An field must have at least an id property.');
                    return false;
                }

                this._prepareField(field.id);
            }

            this.options.script_element.text('$(document).ready(function(){ '+this.buildData.script_code+' });');
            this.options.style_element.text(this.buildData.style_code);
            
            eval(this.options.script_element.text());
        }
    });
});

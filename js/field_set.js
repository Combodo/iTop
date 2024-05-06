/*
 * Copyright (C) 2013-2024 Combodo SAS
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */

//iTop Field set
//Used by itop.form_handler and itop.subform_field to list their fields
;
$(function()
{
	// the widget definition, where 'itop' is the namespace,
	// 'field_set' the widget name
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
			.bind('field_change', function(oEvent, oData){
				me._onFieldChange(oEvent, oData);
			})
			.bind('update_form', function(oEvent, oData){
				me._onUpdateForm(oEvent, oData);
			})
			.bind('get_current_values', function(oEvent, oData){
				return me._onGetCurrentValues(oEvent, oData);
			})
			.bind('validate', function(oEvent, oData){
				if (oData === undefined)
				{
					oData = {};
				}
				
				return me._onValidate(oEvent, oData);
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
		getField: function (sFieldId)
		{
			return this.element.find('[' + this.options.field_identifier_attr + '="' + sFieldId + '"][data-form-path="' + this.options.form_path + '"]');
		},
		_onGetCurrentValues: function(oEvent, oData)
		{
			oEvent.stopPropagation();

			var oResult = {};
			
			for(var i in this.options.fields_list)
			{
				var oField = this.options.fields_list[i];
				if(this.getField(oField.id).hasClass('form_field'))
				{
					oResult[oField.id] = this.getField(oField.id).triggerHandler('get_current_value');
				}
				else
				{
					if(window.console)
					{
						console.log('Field set : Cannot retrieve current value from field [' + this.options.field_identifier_attr + '="' + oField.id + '"][data-form-path="' + this.options.form_path + '"] as it seems to have no itop.form_field widget attached.');
					}
				}
			}
			
			return oResult;
		},
		_getRequestedFields: function(sSourceFieldName)
		{
			var aFieldsName = [];
			
			if(this.options.fields_impacts[sSourceFieldName] !== undefined)
			{
				for(var i in this.options.fields_impacts[sSourceFieldName])
				{
					aFieldsName.push(this.options.fields_impacts[sSourceFieldName][i]);
				}
			}
			
			return aFieldsName;
		},
		_onFieldChange: function(oEvent, oData)
		{
			oEvent.stopPropagation();

			// Set field as touched so we know that we have to do checks on it later
			if(this.options.touched_fields.indexOf(oData.name) < 0)
			{
				this.options.touched_fields.push(oData.name);
			}

			// Validate the field
			var oResult = this.getField(oData.name).triggerHandler('validate', {touched_fields_only: true});
			if ( (oResult !== undefined) && !oResult.is_valid)
			{
				this.options.is_valid = false;
			}

			// Request update on dependent fields
			var oRequestedFields = this._getRequestedFields(oData.name);
			if(oRequestedFields.length > 0)
			{
				this.element.trigger('update_fields', {form_path: this.options.form_path, requested_fields: oRequestedFields});
			}

			// Notify form that fields have been touched
			this.element.trigger('fields_touched');
		},
		_onUpdateForm: function(oEvent, oData)
		{
			oEvent.stopPropagation();

			this.buildData.script_code = '';
			this.buildData.style_code = '';

			for (var i in oData.updated_fields)
			{
				var oUpdatedField = oData.updated_fields[i];
				this.options.fields_list[oUpdatedField.id] = oUpdatedField;
				this._prepareField(oUpdatedField.id);
			}

			// Adding code to the dom
			// Note : We use text() instead of append(), otherwise the code will be interpreted as DOM tags (text + <img /> + ...) and can break some browsers
			this.options.script_element.text( this.options.script_element.text() + '\n\n// Appended by update on ' + Date() + '\n' + this.buildData.script_code);
			this.options.style_element.text( this.options.style_element.text() + '\n\n// Appended by update on ' + Date() + '\n' + this.buildData.style_code);

			// Evaluating script code as adding it to dom did not executed it (only script from update !)
			eval(this.buildData.script_code);
		},
		_onValidate: function(oEvent, oData)
		{
			oEvent.stopPropagation();

			this.options.is_valid = true;

			var aFieldsToValidate = [];
			if ((oData.touched_fields_only !== undefined) && (oData.touched_fields_only === true))
			{
				aFieldsToValidate = this.options.touched_fields;
			}
			else
			{
				// TODO : Requires IE9+ Object.keys(this.options.fields_list);
				for (var sFieldId in this.options.fields_list)
				{
					aFieldsToValidate.push(sFieldId);
				}
			}

			for(var i in aFieldsToValidate)
			{
				var oField = this.getField(aFieldsToValidate[i]);
				// Checking if the field still exists as it could have been from a dynamic subform (Typically with custom fields)
				if(oField.length > 0 && oField.hasClass('form_field'))
				{
					var oRes = oField.triggerHandler('validate', oData);
					if (!oRes.is_valid)
					{
						this.options.is_valid = false;
					}
				}
			}
			return this.options.is_valid;
		},
		_loadCssFile: function(url)
		{
			if (!$('link[href="' + url + '"]').length)
				$('<link href="' + url + '" rel="stylesheet">').appendTo('head');
		},
		_loadJsFile: function(url)
		{
			if (!$('script[src="' + url + '"]').length)
				$.getScript(url);
		},
		// Place a field for which no container exists
		_addField: function(sFieldId)
		{
			$('<div ' + this.options.field_identifier_attr + '="' + sFieldId + '" data-form-path="' + this.options.form_path + '"></div>').appendTo(this.element);
		},
		_prepareField: function(sFieldId)
		{
			var oField = this.options.fields_list[sFieldId];

			if(this.getField(oField.id).length === 1)
			{
				// We replace the node instead of just replacing the inner html so the previous widget is automatically destroyed.
				this.getField(oField.id).replaceWith(
					$('<div ' + this.options.field_identifier_attr + '="' + oField.id + '" data-form-path="' + this.options.form_path + '"></div>')
				);
			}
			else
			{
				this._addField(oField.id);
			}

			var oFieldContainer = this.getField(oField.id);
			// HTML
			if( (oField.html !== undefined) && (oField.html !== '') )
			{
				oFieldContainer.html(oField.html);
			}
			// HTML metadata
			if( (oField.html_metadata !== undefined) && (Object.keys(oField.html_metadata).length > 0) )
			{
				for(var i in oField.html_metadata)
				{
					oFieldContainer.attr('data-' + i, oField.html_metadata[i]);
				}
			}
			// JS files
			if( (oField.js_files !== undefined) && (oField.js_files.length > 0) )
			{
				for(var i in oField.js_files)
				{
					this._loadJsFile(oField.js_files[i]);
				}
			}
            // CSS files
            if( (oField.css_files !== undefined) && (oField.css_files.length > 0) )
            {
                for(var i in oField.css_files)
                {
                    this._loadCssFile(oField.css_files[i]);
                }
            }
            // CSS classes
            if( (oField.css_classes !== undefined) && (oField.css_classes.length > 0) )
            {
                for(var i in oField.css_classes)
                {
                    oFieldContainer.addClass(oField.css_classes[i]);
                }
            }
			// JS inline
			if( (oField.js_inline !== undefined) && (oField.js_inline !== '') )
			{
				this.buildData.script_code += '; '+ oField.js_inline;
			}
			// CSS inline
			if( (oField.css_inline !== undefined) && (oField.css_inline !== '') )
			{
				this.buildData.style_code += ' '+ oField.css_inline;
			}
			
		},
		buildForm: function()
		{
			this.buildData.script_code = '';
			this.buildData.style_code = '';

			for(var i in this.options.fields_list)
			{
				var oField = this.options.fields_list[i];
				if(oField.id === undefined)
				{
					console.log('Field set : A field must have at least an id property.');
					return false;
				}

				this._prepareField(oField.id);
			}

			this.options.script_element.text('$(document).ready(function(){ ' + this.buildData.script_code + ' });');
			this.options.style_element.text(this.buildData.style_code);
			
			eval(this.options.script_element.text());
			
			// Sending event to let know that form is built
			// Warning: It seems that the event is triggered before form_field widgets are initialized on fields, which can lead to erratic behavior
			this.element.trigger('form_built');
		},
		hasTouchedFields: function()
		{
			return (this.options.touched_fields.length > 0);
		},
		// Debug helper
		showOptions: function()
		{
			return this.options;
		},
	});
});

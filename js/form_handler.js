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
			submit_btn_selector: null,
			cancel_btn_selector: null,
			endpoint: null,
			is_modal: false,
			field_set: null
		},

		// the constructor
		_create: function()
		{
			var me = this;
			
			this.element
			.addClass('form_handler');

			// Binding events
			this.element.bind('update_fields', function(oEvent, oData){
				me._onUpdateFields(oEvent, oData);
			});
			this.element.bind('fields_touched', function(oEvent){
				me._onFieldsTouched(oEvent);
			});

			// Binding buttons
			if(this.options.submit_btn_selector !== null)
			{
				this.options.submit_btn_selector.off('click').on('click', function(oEvent){ me._onSubmitClick(oEvent); });
			}
			if(this.options.cancel_btn_selector !== null)
			{
				this.options.cancel_btn_selector.off('click').on('click', function(oEvent){ me._onCancelClick(oEvent); });
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

		// Methods
		getCurrentValues: function()
		{
			return this.options.field_set.triggerHandler('get_current_values');
		},
		/**
		 * @private
		 * @since 3.0.2 3.1.0
		 */
		_updatePreviousValues: function()
		{
			let me = this;
			if(this.element.find('[data-attribute-previous-value]').length > 0) {
				let aPreviousValues = {};
				$(this.element.find('[data-attribute-previous-value]')).each(function (iIdx, oElem) {
					aPreviousValues[$(oElem).data('field-id')] = $(oElem).data('attribute-previous-value');
				});
				me.element.find('[data-field-id="previous_values"]').find('input[type="hidden"]').val(JSON.stringify(aPreviousValues));
			}
		},
		// Events callback
		// - Update fields depending on the update ones
		_onUpdateFields: function(oEvent, oData)
		{
			var me = this;
			me._updatePreviousValues();
			var sFormPath = oData.form_path;

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
			
			this._disableFormBeforeLoading();
			$.post(
				this.options.endpoint,
				{
					operation: 'update',
					formmanager_class: this.options.formmanager_class,
					formmanager_data: JSON.stringify(this.options.formmanager_data),
					current_values: this.getCurrentValues(),
					requested_fields: oData.requested_fields,
					form_path: sFormPath
				},
				function(oData){
					me._onUpdateSuccess(oData, sFormPath);
				}
			)
			.fail(function(oData){ me._onUpdateFailure(oData, sFormPath); })
			.always(function(oData){ me._onUpdateAlways(oData, sFormPath); });
		},
		// - Callback when some fields have been touched
		_onFieldsTouched: function(oEvent)
		{
			this.element.attr('data-fields-touched', 'true');
		},
		// Intended for overloading in derived classes
		_onSubmitClick: function(oEvent)
		{
		},
		// Intended for overloading in derived classes
		_onCancelClick: function(oEvent)
		{
		},
		// Intended for overloading in derived classes
		_onUpdateSuccess: function(oData, sFormPath)
		{
			var me = this;
			if(oData.form.hidden_fields !== undefined)
			{
				$.each(oData.form.hidden_fields, function( index, value ) {
					me.element.find('[data-form-path="' + sFormPath + '"][data-field-id="'+value+'"][data-attribute-flag-hidden="false"]').children().remove();
				});
			}
			if(oData.form.updated_fields !== undefined)
			{
				me.element.find('[data-form-path="' + sFormPath + '"]').trigger('update_form', {updated_fields: oData.form.updated_fields});
			}
		},
		// Intended for overloading in derived classes
		_onUpdateFailure: function(oData, sFormPath)
		{
		},
		// Intended for overloading in derived classes
		_onUpdateAlways: function(oData, sFormPath)
		{
			// Check all touched AFTER ajax is complete, otherwise the renderer will redraw the field in the mean time.
			this.element.find('[data-form-path="' + sFormPath + '"]').trigger('validate');
			this._enableFormAfterLoading();
		},

		// Helpers
		// Intended for overloading in derived classes
		_disableFormBeforeLoading: function()
		{
		},
		// Intended for overloading in derived classes
		_enableFormAfterLoading: function()
		{
		},
		// Returns true if the form fields have been touched
		_hasBeenTouched: function()
		{
			return (this.element.attr('data-fields-touched') === 'true');
		},
		// Debug helper
		showOptions: function()
		{
			console.log(this.options);
		}
	});
});

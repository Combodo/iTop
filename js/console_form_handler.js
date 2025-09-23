// Copyright (C) 2010-2024 Combodo SAS
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>

//iTop Form handler
;
$(function()
{
    // the widget definition, where 'itop' is the namespace,
    // 'consoleform_handler' the widget name
    $.widget( 'itop.console_form_handler', $.itop.form_handler,
    {
        // default options
        options:
        {
            wizard_helper_var_name: '', // Name of the global variable pointing to the wizard helper
            custom_field_attcode: ''
        },

        // the constructor
        _create: function()
        {
            var me = this;
            
            this.element.append('<div class="last-error"></div>')
                .addClass('console_form_handler');

            this.options.oWizardHelper = window[this.options.wizard_helper_var_name];

            this._super();
        },
   
        // events bound via _bind are removed automatically
        // revert other modifications here
        _destroy: function()
        {
            this.element.removeClass('console_form_handler');
            this._super();
        },
        _onUpdateFields: function(event, data)
        {
            var me = this;
	        me._updatePreviousValues();
            var sFormPath = data.form_path;
            var sUpdateUrl = GetAbsoluteUrlAppRoot()+'pages/ajax.render.php';

            this.element.find('[data-form-path="' + sFormPath + '"]').block({message:''});
            $.post(
                sUpdateUrl,
                {
                    operation: 'custom_fields_update',
                    attcode: this.options.custom_field_attcode,
                    requested_fields: data.requested_fields,
                    form_path: sFormPath,
                    json_obj: this.options.oWizardHelper.UpdateWizardToJSON()
                },
                function(data){
                    me.element.find('.last-error').text('');
                    if ('form' in data) {
                        me._onUpdateSuccess(data, sFormPath);
                    }
                }
            )
                .fail(function(data){ me._onUpdateFailure(data, sFormPath); })
                .always(function(data){
                    me.alignColumns();
                    me.element.find('[data-form-path="' + sFormPath + '"]').unblock();
                    if ('error' in data) {
                        console.log('Update field failure: '+data.error);
                        me.element.find('.last-error').text(data.error);
                    }
                    me._onUpdateAlways(data, sFormPath);
	                me.element.find('[data-field-id="previous_values"]').find('input[type="hidden"]').val('{}');
                });
        },
        // On initialization or update
        alignColumns: function()
        {
            var iMaxWidth = 0;
            var oLabels = this.element.find('td.form-field-label');
            // Reset the width to the automatic (original) value
            oLabels.width('');
            oLabels.each(function() {
                iMaxWidth = Math.max(iMaxWidth, $(this).width());
            });
            oLabels.width(iMaxWidth);
        },
        // Intended for overloading in derived classes
        _onSubmitClick: function()
        {
        },
        // Intended for overloading in derived classes
        _onCancelClick: function()
        {
        },
        // Intended for overloading in derived classes
        _onUpdateFailure: function(data)
        {
        },
        // Intended for overloading in derived classes
        _disableFormBeforeLoading: function()
        {
        },
        // Intended for overloading in derived classes
        _enableFormAfterLoading: function()
        {
        },
    });
});

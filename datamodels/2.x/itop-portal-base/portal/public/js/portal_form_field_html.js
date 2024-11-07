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

//iTop Portal Form field HTML
//Used for field containing html data such as rich editors, html blocks, ...
;
$(function()
{
	// the widget definition, where 'itop' is the namespace,
	// 'portal_form_field' the widget name
	$.widget( 'itop.portal_form_field_html', $.itop.portal_form_field,
	{
		// the constructor
		_create: function()
		{
			this.element
			.addClass('portal_form_field_html');
	
			this._super();
		},  
		// events bound via _bind are removed automatically
		// revert other modifications here
		_destroy: function()
		{
			this.element
			.removeClass('portal_form_field_html');
	
			this._super();
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
		validate: function(oEvent, oData)
		{
			const oResult = { is_valid: true, error_messages: [] };
			
			// Doing data validation
			if(this.options.validators !== null)
			{
				const bMandatory = (this.options.validators['mandatory'] !== undefined);
				const oTextArea = this.element.find('textarea')[0];

				// Checks are very basic for now
				if(oTextArea.value === '' && bMandatory ){
					oResult.is_valid = false;
					oResult.error_messages.push(this.options.validators['mandatory'].message);
				}
			}
			
			this.options.on_validation_callback(this, oResult);
			
			return oResult;
		}
	});
});

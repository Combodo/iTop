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

;
$(function()
{
	// the widget definition, where 'itop' is the namespace,
	// 'breadcrumbs' the widget name
	$.widget( 'itop.quick_create',
	{
		// default options
		options:
		{
			max_autocomplete_results: 10,
		},
		css_classes:
		{
			opened: 'ibo-is-opened',
			hidden: 'ibo-is-hidden',
		},
		js_selectors:
		{
			icon: '[data-role="ibo-quick-create--icon"]',
			form: '[data-role="ibo-quick-create--head"]',
			input: '[data-role="ibo-quick-create--input"]',
			compartment_element: '[data-role="ibo-quick-create--compartment-element"]',
			select_dropdown_parent: '[data-role="ibo-quick-create--compartment-results"]'
		},
   
		// the constructor
		_create: function()
		{
			this.element.addClass('ibo-quick-create');
			this._initializeMarkup();
			this._bindEvents();
		},
		// events bound via _bind are removed automatically
		// revert other modifications here
		_destroy: function()
		{
			this.element.removeClass('ibo-quick-create');
		},
		_initializeMarkup: function()
		{
			const me = this;

			// Instantiate selectize.js on input
			this.element.find(this.js_selectors.input).selectize({
				dropdownParent: this.js_selectors.select_dropdown_parent,
				dropdownClass: 'ibo-quick-create--compartment-results--container',
				dropdownContentClass: 'ibo-quick-create--compartment-results--element',
				openOnFocus: false,
				maxItems: 1,
				maxOptions: this.options.max_autocomplete_results,
			});

			// Remove some inline styling from the widget
			this.element.find('.selectize-input > input').css('width', '');
		},
		_bindEvents: function()
		{
			const me = this;
			const oBodyElem = $('body');

			this.element.find(this.js_selectors.icon).on('click', function(oEvent){
				me._onIconClick(oEvent);
			});
			this.element.find(this.js_selectors.form).on('submit', function(oEvent){
				me._onFormSubmit(oEvent);
			});
			this.element.find(this.js_selectors.input).on('change', function(oEvent){
				me._onInputOptionSelected(oEvent, $(this));
			});
			this.element.on('open_drawer', function(oEvent){
				me._onIconClick(oEvent);
			});
			// Mostly for outside clicks that should close elements
			oBodyElem.on('click', function(oEvent){
				me._onBodyClick(oEvent);
			});
			// Mostly for hotkeys
			oBodyElem.on('keyup', function(oEvent){
				me._onBodyKeyUp(oEvent);
			});
		},
		_onIconClick: function(oEvent)
		{
			// Avoid anchor glitch
			oEvent.preventDefault();

			if(this._isDrawerOpened())
			{
				this._closeDrawer();
			}
			else
			{
				this._openDrawer();
				// Focus in the input for a better UX
				this._setFocusOnInput();
			}
		},
		_onFormSubmit: function(oEvent)
		{
			const sSearchValue = this.element.find(this.js_selectors.input).val();

			// Submit form only if something in the input
			if(sSearchValue === '')
			{
				oEvent.preventDefault();
			}
		},
		_onInputOptionSelected: function(oEvent, oInputElem)
		{
			// Submit form directly on change
			this.element.find(this.js_selectors.form).trigger('submit');
		},
		_onBodyClick: function(oEvent)
		{
			if($(oEvent.target.closest('.ibo-quick-create')).length === 0)
			{
				this._closeDrawer();
			}
		},
		_onBodyKeyUp: function(oEvent)
		{
			// Note: We thought about extracting the oEvent.key in a variable to lower case it, but this would be done
			// on every single key up in the application, which might not be what we want... (time consuming)
			if((oEvent.altKey === true) && (oEvent.key === 'n' || oEvent.key === 'N'))
			{
				if(this._isDrawerOpened())
				{
					this._setFocusOnInput();
				}
				// If drawer is closed, we trigger the click on the icon in order for the other widget to behave like they should (eg. close themselves)
				else
				{
					this.element.find(this.js_selectors.icon).trigger('click');
				}
			}
		},

		// Methods
		_isDrawerOpened: function()
		{
			return this.element.hasClass(this.css_classes.opened);
		},
		_openDrawer: function()
		{
			this.element.find(this.js_selectors.compartment_element).removeClass(this.css_classes.hidden);
			this.element.addClass(this.css_classes.opened);
		},
		_closeDrawer: function()
		{
			this.element.removeClass(this.css_classes.opened);
			//Note: Elements are hidden to avoid having the keyboard navigation "TAB" passing throught them when they are not displayed
			this.element.find(this.js_selectors.compartment_element).addClass(this.css_classes.hidden);
		},
		_setFocusOnInput: function()
		{
			this.element.find('.selectize-input > input').trigger('focus');
		}
	});
});
